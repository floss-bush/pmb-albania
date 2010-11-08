<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_pret.inc.php,v 1.30 2010-01-07 10:50:04 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$base_path/circ/pret_func.inc.php");
// liste des prêts et réservations
// prise en compte du param d'envoi de ticket de prêt électronique si l'utilisateur le veut !
if ($empr_electronic_loan_ticket && $param_popup_ticket) {
	electronic_ticket($id_empr) ;
	}

// popup d'impression PDF pour fiche lecteur
// reçoit : id_empr
// Démarrage et configuration du pdf
$ourPDF = new $fpdf('P', 'mm', 'A4');
$ourPDF->Open();

//requete par rapport à un emprunteur
$rqt = "select expl_cb from pret, exemplaires where pret_idempr='".$id_empr."' and pret_idexpl=expl_id order by pret_date " ;	
$req = mysql_query($rqt) or die($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error());
$count = mysql_num_rows($req);

$ourPDF->addPage();
//$ourPDF->SetMargins(10,10,10);
$ourPDF->SetLeftMargin(10);
$ourPDF->SetTopMargin(10);
// paramétrage spécifique à ce document :
$offsety = 0;

if(!$pmb_hide_biblioinfo_letter) biblio_info( 10, 10, 1) ;
$offsety=(ceil($ourPDF->GetStringWidth($biblio_name)/90)-1)*10; //90=largeur de la cell, 10=hauteur d'une ligne
lecteur_info($id_empr, 90, 10+$offsety, $dbh, 1,1);
date_edition(10,15+$offsety);

$ourPDF->SetXY (10,22+$offsety);
$ourPDF->setFont($pmb_pdf_font, 'BI', 14);
$ourPDF->multiCell(190, 20, $msg["prets_en_cours"]." (".($count).")", 0, 'L', 0);
$i=0;
$nb_page=0;
$nb_par_page = 21;
$nb_1ere_page = 19;
$taille_bloc = 12 ;
while ($data = mysql_fetch_array($req)) {
	if ($nb_page==0 && $i<$nb_1ere_page) {
		$pos_page = 35+$offsety+$taille_bloc*$i;
		}
	if (($nb_page==0 && $i==$nb_1ere_page) || ((($i-$nb_1ere_page) % $nb_par_page)==0)) {
		$ourPDF->addPage();
		$nb_page++;
		}
	if ($nb_page>=1) {
		$pos_page = 10+($taille_bloc*($i-$nb_1ere_page-($nb_page-1)*$nb_par_page));
		}
	expl_info ($data['expl_cb'],10,$pos_page,$dbh, 1, 80);
	$i++;
	}

mysql_free_result($req);

header("Content-Type: application/pdf");
$ourPDF->OutPut();

