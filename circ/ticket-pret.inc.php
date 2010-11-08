<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ticket-pret.inc.php,v 1.18 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$base_path/circ/pret_func.inc.php");
// liste des prêts et réservations
// prise en compte du param d'envoi de ticket de prêt électronique
// la liste n'est envoyée que si pas de cb_doc, si cb_doc, c'est que c'est un ticket unique d'un prêt et dans ce cas, le ticket électronique est envoyé par pret.inc.php 
if ($empr_electronic_loan_ticket && !$cb_doc && $param_popup_ticket) {
	electronic_ticket($id_empr) ;
	}

// popup d'impression PDF pour fiche lecteur
// reçoit : id_empr et éventuellement cb_doc
// Démarrage et configuration du pdf

$ourPDF = new $fpdf('P', 'mm', 'A4');
$ourPDF->Open();
$ourPDF->addPage();
$ourPDF->SetLeftMargin(10);																		
$ourPDF->SetTopMargin(10);

$offsety = 40;

biblio_info( 10, 10) ;
lecteur_info($id_empr, 90, 10+$offsety, $dbh);
date_edition(10,70+$offsety);

if ($cb_doc == "") {
	$rqt = "select expl_cb from pret, exemplaires where pret_idempr='".$id_empr."' and pret_idexpl=expl_id order by pret_date " ;
	$req = mysql_query($rqt) or die($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error()); 
	
	$ourPDF->SetXY (10,80+$offsety);
	$ourPDF->setFont($pmb_pdf_font, 'BI', 20);
	$ourPDF->multiCell(190, 20, $msg["prets_en_cours"], 0, 'L', 0);
	$i=0;
	$nb_page=0;
	$nb_par_page = 10;
	$nb_1ere_page = 7;
	$taille_bloc = 18 ;
	while ($data = mysql_fetch_array($req)) {
		if ($nb_page==0 && $i<$nb_1ere_page) {
				$pos_page = 100+$offsety+$taille_bloc*$i;
				}
		if (($nb_page==0 && $i==$nb_1ere_page) || ((($i-$nb_1ere_page) % $nb_par_page)==0)) {
			$ourPDF->addPage();
			$nb_page++;
			}
		if ($nb_page>=1) {
			$pos_page = 10+($taille_bloc*($i-$nb_1ere_page-($nb_page-1)*$nb_par_page));
			}
		expl_info ($data['expl_cb'],20,$pos_page,$dbh,0,65);
		$i++;
		}

	// Impression des réservations en cours
	$rqt = "select resa_idnotice, resa_idbulletin from resa where resa_idempr='".$id_empr."' " ;
	$req = mysql_query($rqt) or die($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error()); 
	if (mysql_num_rows($req) > 0) {
		if ($nb_page==0 && $i<$nb_1ere_page) {
				$pos_page = 100+$offsety+$taille_bloc*$i;
				}
		if (($nb_page==0 && $i==$nb_1ere_page) || ((($i-$nb_1ere_page) % $nb_par_page)==0)) {
			$ourPDF->addPage();
			$nb_page++;
			}
		if ($nb_page>=1) {
			$pos_page = 10+($taille_bloc*($i-$nb_1ere_page-($nb_page-1)*$nb_par_page));
			}
		$i++;
		$ourPDF->SetXY (10,$pos_page+7);
		$ourPDF->setFont($pmb_pdf_font, 'BI', 20);
		$ourPDF->multiCell(190, 20, $msg["documents_reserves"], 0, 'L', 0);
		
		while ($data = mysql_fetch_array($req)) {
		if ($nb_page==0 && $i<$nb_1ere_page) {
				$pos_page = 100+$offsety+$taille_bloc*$i;
				}
		if (($nb_page==0 && $i==$nb_1ere_page) || ((($i-$nb_1ere_page) % $nb_par_page)==0)) {
			$ourPDF->addPage();
			$nb_page++;
			}
		if ($nb_page>=1) {
			$pos_page = 10+($taille_bloc*($i-$nb_1ere_page-($nb_page-1)*$nb_par_page));
			}
			not_bull_info_resa ($id_empr, $data['resa_idnotice'],$data['resa_idbulletin'],20,$pos_page,$dbh, 65);
			$i++;
			}
		} // fin if résas

	} else {
		$ourPDF->SetXY (10,80+$offsety);
		$ourPDF->setFont($pmb_pdf_font, 'BI', 20);
		$ourPDF->multiCell(190, 20, $msg["ticket_de_pret"], 0, 'L', 0);

		expl_info ($cb_doc,20,100+$offsety,$dbh,0,65);
		}
		
//$ourPDF->SetXY (10,276);
//$ourPDF->setFont('Arial', 'I', 8);
//$ourPDF->multiCell(190, 0, "En cours de réalisation", 0, 1, 'L', 0);

$ourPDF->OutPut();

?>
