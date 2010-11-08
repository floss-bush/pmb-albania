<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lettre-retard.inc.php,v 1.34 2010-05-18 14:49:21 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");
require_once($include_path."/sms.inc.php");

// popup d'impression PDF pour lettre retard de prêt
// reçoit : id_empr et éventuellement cb_doc
function get_texts($relance) {
	global $format_page,$marge_page_gauche, $marge_page_droite, $largeur_page, $fdp, $after_list, $limite_after_list, $before_list, $madame_monsieur, $nb_1ere_page, $nb_par_page, $taille_bloc_expl, $debut_expl_1er_page, $debut_expl_page, $before_recouvrement,$after_recouvrement;
	global $biblio_name, $biblio_phone, $biblio_email;

	$var = "pdflettreretard_".$relance."fdp";
	global $$var;
	eval ("\$fdp=\"".$$var."\";");

	// le texte après la liste des ouvrages en retard
	$var = "pdflettreretard_".$relance."after_list";
	global $$var;
	eval ("\$after_list=\"".$$var."\";");
	
	// Le texte avant la liste des ouvrages qui passeront en recouvrement
	$var = "pdflettreretard_".$relance."before_recouvrement";
	global $$var;
	eval ("\$before_recouvrement=\"".$$var."\";");
	
	// Le texte après la liste des ouvrages qui passeront en recouvrement
	$var = "pdflettreretard_".$relance."after_recouvrement";
	global $$var;
	eval ("\$after_recouvrement=\"".$$var."\";");
		
	
	// la position verticale limite du texte after_liste (si >, saut de page et impression)
	$var = "pdflettreretard_".$relance."limite_after_list";
	global $$var;
	$limite_after_list = $$var;
			
	// le texte avant la liste des ouvrges en retard
	$var = "pdflettreretard_".$relance."before_list";
	global $$var;
	eval ("\$before_list=\"".$$var."\";");
	
	// le "Madame, Monsieur," ou tout autre truc du genre "Cher adhérent,"
	$var = "pdflettreretard_".$relance."madame_monsieur";
	global $$var;
	eval ("\$madame_monsieur=\"".$$var."\";");
	
	// le nombre de blocs expl à imprimer sur la première page
	$var = "pdflettreretard_".$relance."nb_1ere_page";
	global $$var;
	$nb_1ere_page = $$var;
	
	// le nombre de blocs expl à imprimer sur les pages suivantes
	$var = "pdflettreretard_".$relance."nb_par_page";
	global $$var;
	$nb_par_page = $$var;
	
	// la taille d'un bloc expl en retard affiché
	$var = "pdflettreretard_".$relance."taille_bloc_expl";
	global $$var;
	$taille_bloc_expl = $$var;
	
	// la position verticale du premier bloc expl sur la première page
	$var = "pdflettreretard_".$relance."debut_expl_1er_page";
	global $$var;
	$debut_expl_1er_page = $$var;
	
	// la position verticale du premier bloc expl sur les pages suivantes
	$var = "pdflettreretard_".$relance."debut_expl_page";
	global $$var;
	$debut_expl_page = $$var;
	
	// la marge gauche des pages
	$var = "pdflettreretard_".$relance."marge_page_gauche";
	global $$var;
	$marge_page_gauche = $$var;
	
	// la marge droite des pages
	$var = "pdflettreretard_".$relance."marge_page_droite";
	global $$var;
	$marge_page_droite = $$var;
	
	// la largeur des pages
	$var = "pdflettreretard_1largeur_page";
	global $$var;
	$largeur_page = $$var;
	
	// la hauteur des pages
	$var = "pdflettreretard_1hauteur_page";
	global $$var;
	$hauteur_page = $$var;
	
	// le format des pages
	$var = "pdflettreretard_1format_page";
	global $$var;
	$format_page = $$var;
} // fin function get_texts


$largeur_page=$pdflettreretard_1largeur_page;
$hauteur_page=$pdflettreretard_1hauteur_page;

$taille_doc=array($largeur_page,$hauteur_page);

$format_page=$pdflettreretard_1format_page;

$ourPDF = new $fpdf($format_page, 'mm', $taille_doc);
$ourPDF->Open();

switch($pdfdoc) {
	case "lettre_retard_groupe" :
		get_texts($relance);
		if ($id_groupe) lettre_retard_par_groupe($id_groupe) ;
			else {
				$j=0;
				while ($coch_groupe[$j]) {
					$id_groupe=$coch_groupe[$j];
					$rqt = "select distinct groupe_id from pret, empr_groupe where pret_retour < curdate() and empr_id=pret_idempr and groupe_id=$id_groupe" ;
					$req = mysql_query($rqt, $dbh) or die ($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error()); 
					while ($data = mysql_fetch_object($req)) {
						lettre_retard_par_groupe($data->groupe_id) ;
					}
					$j++;
				}
			}
		break;
	case "lettre_retard" :
	default :
		get_texts($relance);	
		if (!$id_empr) {
			$empr=$empr_print;
			$print_all = isset($printall) ? $printall : 0;
			
			$restrict_localisation="";
			if ($empr) {
				$restrict_localisation = " id_empr in (".implode(",",$empr).") and "; 
			} elseif ($pmb_lecteurs_localises) {
				if ($empr_location_id=="") $empr_location_id = $deflt2docs_location ;
				if ($empr_location_id!=0) $restrict_localisation = " empr_location='$empr_location_id' AND ";							
			}
			
			// parametre listant les champs de la table empr pour effectuer le tri d'impression des lettres		
			if($pdflettreretard_impression_tri) $order_by= " ORDER BY $pdflettreretard_impression_tri";
			else $order_by= "";

			$rqt="select id_empr, concat(empr_nom,' ',empr_prenom) as  empr_name, empr_cb, empr_mail, empr_tel1, empr_sms, count(pret_idexpl) as empr_nb, $pdflettreretard_impression_tri from empr, pret, exemplaires where $restrict_localisation pret_retour<now() and pret_idempr=id_empr  and pret_idexpl=expl_id group by id_empr $order_by";							
			$req=mysql_query($rqt) or die('Erreur SQL !<br />'.$rqt.'<br />'.mysql_error()); ;
			while ($r = mysql_fetch_object($req)) {
				if (($pmb_gestion_financiere)&&($pmb_gestion_amende)) {
					$amende=new amende($r->id_empr);
					$level=$amende->get_max_level();
					$niveau_min=$level["level_min"];
					$printed=$level["printed"];
					if ($printed==2) $printed=0;
					mysql_query("update pret set printed=1 where printed=2 and pret_idempr=".$r->id_empr);
					$not_mail=true;
					if ((($mailretard_priorite_email==1)&&($r->empr_mail))&&($niveau_min<3)) $not_mail=false;
					if ((($print_all || !$printed)&&($niveau_min))&&($not_mail)) {
						$niveau=$niveau_min;
						get_texts($niveau);
						lettre_retard_par_lecteur($r->id_empr) ;
						$ourPDF->SetMargins($marge_page_gauche,$marge_page_gauche);
					}
				} else {
					if (!$niveau) $niveau=1;
					get_texts($niveau);
					lettre_retard_par_lecteur($r->id_empr) ;
					$ourPDF->SetMargins($marge_page_gauche,$marge_page_gauche);
				}
				if($empr_sms_activation && $r->empr_tel1 && $r->empr_sms ){	
					$res_envoi_sms=send_sms($r->empr_name, $r->empr_tel1,"",$empr_sms_msg_retard,$biblio_name, $biblio_email, $headers, "", $PMBuseremailbcc, 1);
				}	
			} // fin while		
		} else {
			if (!$niveau) $niveau=1;
			get_texts($niveau);
			lettre_retard_par_lecteur($id_empr) ;
			$ourPDF->SetMargins($marge_page_gauche,$marge_page_gauche);
			if($empr_sms_activation) {
				$rqt="select concat(empr_nom,' ',empr_prenom) as  empr_name, empr_mail, empr_tel1, empr_sms from empr where id_empr='".$id_empr."' and empr_tel1!='' and empr_sms=1";							
				$req=mysql_query($rqt) or die('Erreur SQL !<br />'.$rqt.'<br />'.mysql_error()); ;
				if ($r = mysql_fetch_object($req)) {
					$res_envoi_sms=send_sms($r->empr_name, $r->empr_tel1,"",$empr_sms_msg_retard,$biblio_name, $biblio_email, $headers, "", $PMBuseremailbcc, 1);
				}
			}
		}
		break;
	}
$ourPDF->OutPut();
