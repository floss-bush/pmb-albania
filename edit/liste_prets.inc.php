<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_prets.inc.php,v 1.7 2010-01-07 10:50:04 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// popup d'impression PDF pour lettres de retard par groupe
// reçoit : liste des groupes cochés $coch_groupe

// Démarrage et configuration du pdf
$ourPDF = new $fpdf('P', 'mm', 'A4');
$ourPDF->Open();


$j=0;
if ($id_groupe) $coch_groupe[0]=$id_groupe;
while ($coch_groupe[$j]) {
	$id_groupe=$coch_groupe[$j];
	
	$ourPDF->addPage();
	//$ourPDF->SetMargins(10,10,10);
	$ourPDF->SetLeftMargin(10);
	$ourPDF->SetTopMargin(10);
	// paramétrage spécifique à ce document :
	$offsety = 0;
	if(!$pmb_hide_biblioinfo_letter) biblio_info( 10, 10, 1) ;
	groupe_adresse($id_groupe, 150, 10+$offsety, $dbh,true);
	date_edition(10,15+$offsety);

	$ourPDF->SetXY (10,15+$offsety);
	$ourPDF->setFont($pmb_pdf_font, 'BI', 14);
	$ourPDF->multiCell(190, 20, $msg["prets_en_cours"], 0, 'L', 0);
	$i=0;
	$nb_page=0;
	$indice_page=0;
	$nb_par_page = 21;
	$nb_1ere_page = 19;
	$taille_bloc_expl = 12 ;
	$debut_expl_1er_page=35+$offsety;
	$debut_expl_page=10;
			
	//requete par rapport à un groupe d'emprunteurs
	$rqt1 = "select empr_id from empr_groupe, empr, pret where groupe_id='".$id_groupe."' and empr_groupe.empr_id=empr.id_empr and pret.pret_idempr=empr_groupe.empr_id group by empr_id order by empr_nom, empr_prenom";
	$req1 = mysql_query($rqt1) or die($msg['err_sql'].'<br />'.$rqt1.'<br />'.mysql_error());
	
	while ($data1=mysql_fetch_array($req1)) {
		$id_empr=$data1['empr_id'];	
		if ($nb_page==0 && $indice_page==$nb_1ere_page) {
			$ourPDF->addPage();
			$nb_page++;
			$indice_page = 0 ;
		} elseif (($nb_page>=1) && ((($indice_page-$nb_1ere_page) % $nb_par_page)==0)) { 
			$ourPDF->addPage();
			$nb_page++;
			$indice_page = 0 ;
		}
		if ($nb_page==0) $pos_page = $debut_expl_1er_page+$taille_bloc_expl*$indice_page;
		else $pos_page = $debut_expl_page+$taille_bloc_expl*$indice_page;
		
		lecteur_info($id_empr,10,$pos_page,$dbh, 1, 0);
		$indice_page++;
		
		//requete par rapport à un emprunteur
		$rqt = "select expl_cb from pret, exemplaires where pret_idempr='".$id_empr."' and pret_idexpl=expl_id order by pret_date " ;	
		$req = mysql_query($rqt) or die($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error());
		
		while ($data = mysql_fetch_array($req)) {
			if ($nb_page==0 && $indice_page==$nb_1ere_page) {
				$ourPDF->addPage();
				$nb_page++;
				$indice_page = 0 ;
			} elseif (($nb_page>=1) && ((($indice_page-$nb_1ere_page) % $nb_par_page)==0)) { 
				$ourPDF->addPage();
				$nb_page++;
				$indice_page = 0 ;
			}
			if ($nb_page==0) $pos_page = $debut_expl_1er_page+$taille_bloc_expl*$indice_page;
			else $pos_page = $debut_expl_page+$taille_bloc_expl*$indice_page;
			expl_info ($data['expl_cb'],10,$pos_page-5,$dbh, 1, 80);
			$indice_page++;
		}	
	}
	$j++;
}

header("Content-Type: application/pdf");
$ourPDF->OutPut();

?>