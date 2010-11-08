<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.7 2008-06-10 08:00:12 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/resa_planning.class.php");
require_once("$include_path/resa_planning_func.inc.php");
require_once("$include_path/templates/resa_planning.tpl.php");


switch ($categ) {

	case 'resa_planning' :
		print "<h1>".$msg['resa_menu']." &gt; ".$msg['resa_menu_planning']."</h1>";
		print $msg_a_pointer ;

		switch($resa_action) {
			
			case 'search_resa' : //Recherche pour réservation

				print (aff_entete($id_empr));
				
				switch($mode) {
					case 1:
						// recherche catégorie/sujet
						print $menu_search[1];
						include('./circ/resa_planning/subjects/main.inc.php');
						break;
					case 5:
						// recherche par termes
						print $menu_search[6];
						include('./circ/resa_planning/terms/main.inc.php');
						break;
					case 2:
						// recherche éditeur/collection
						print $menu_search[2];
						include('./circ/resa_planning/publishers/main.inc.php');
						break;
					case 3:
						// accès aux paniers
						print $menu_search[3];
						include('./circ/resa_planning/cart.inc.php');
						break;
					case 6:
						// recherches avancees
						print $menu_search[6];
						include('./circ/resa_planning/extended/main.inc.php');
						break;	
					default :
						// recherche auteur/titre
						print $menu_search[0];
						$action_form = "./circ.php?categ=resa_planning&mode=0&id_empr=$id_empr&groupID=$groupID" ;
						include('./circ/resa_planning/authors/main.inc.php');
						break;
				}				
				break;

			case 'add_resa' : //Ajout d'une réservation depuis une recherche catalogue

				print (aff_entete($id_empr));

				$display = new mono_display($id_notice, 6, '', 0, '', '', '', 0, 1, 1, 1);
				print ($display->result);
				print "<script type='text/javascript' src='./javascript/tablist.js'></script>\n";
				
				$form_resa_dates = str_replace('!!resa_date_debut!!', formatdate(today()), $form_resa_dates);
				$form_resa_dates = str_replace('!!resa_date_fin!!', formatdate(today()), $form_resa_dates);
				$form_resa_dates = str_replace('!!resa_deb!!', today(), $form_resa_dates);
				$form_resa_dates = str_replace('!!resa_fin!!', today(), $form_resa_dates);				
				print $form_resa_dates;
				
				//Affichage des réservations planifiées sur le document courant par le lecteur courant
				print doc_planning_list($id_empr, $id_notice);
							
				break;

			case 'add_resa_suite' :	//Enregistrement réservation depuis fiche 

				//On vérifie les dates
				$query="SELECT DATEDIFF('$resa_fin', '$resa_deb') AS diff";
				print $query;
				$resultatdate=mysql_query($query);
				if( mysql_numrows($resultatdate) ) {
					$resdate=mysql_fetch_object($resultatdate);
					if($resdate->diff > 0 ) {
						$r = new resa_planning();
						$r->resa_idempr = $id_empr;
						$r->resa_idnotice = $id_notice;
						$r->resa_date_debut = $resa_deb;
						$r->resa_date_fin = $resa_fin;
						$r->save();
						
						$q="select empr_cb from empr where id_empr='".$id_empr."' ";
						$r=mysql_result(mysql_query($q, $dbh), 0, 0);
						
						print "<script type='text/javascript'>document.location='./circ.php?categ=pret&form_cb=".rawurlencode($r)."'</script>";
					
					} else {
						
						print (aff_entete($id_empr));
	
						$display = new mono_display($id_notice, 6, '', 0, '', '', '', 0, 1, 1, 1);
						print ($display->result);
						print "<script type='text/javascript' src='./javascript/tablist.js'></script>\n";
						
						$form_resa_dates = str_replace('!!resa_date_debut!!', formatdate($resa_deb), $form_resa_dates);
						$form_resa_dates = str_replace('!!resa_date_fin!!', formatdate($resa_fin), $form_resa_dates);
						$form_resa_dates = str_replace('!!resa_deb!!', $resa_deb, $form_resa_dates);
						$form_resa_dates = str_replace('!!resa_fin!!', $resa_fin, $form_resa_dates);

						print $form_resa_dates;
						
						//Affichage des réservations planifiées sur le document courant par le lecteur courant
						print doc_planning_list($id_empr, $id_notice);
						
					}
				}
				break;

			case 'enr_resa' :	//Enregistrement réservation depuis liste
				if($id_empr)
				foreach($id_empr as $key=>$value) {
		
					if ($resa_date_debut[$key]) {
						//On vérifie les dates
						$tresa_date_debut = explode('-', extraitdate($resa_date_debut[$key]));
						if (strlen($tresa_date_debut[2])==1) $tresa_date_debut[2] = '0'.$tresa_date_debut[2];
						if (strlen($tresa_date_debut[1])==1) $tresa_date_debut[1] = '0'.$tresa_date_debut[1];
						$resa_date_debut = implode('', $tresa_date_debut);
						
						$tresa_date_fin = explode('-', extraitdate($resa_date_fin[$key]));
						if (strlen($tresa_date_fin[2])==1) $tresa_date_fin[2] = '0'.$tresa_date_fin[2];
						if (strlen($tresa_date_fin[1])==1) $tresa_date_fin[1] = '0'.$tresa_date_fin[1];
						$resa_date_fin = implode('', $tresa_date_fin); 	
						
						if ( (@checkdate($tresa_date_debut[1], $tresa_date_debut[2], $tresa_date_debut[0])) 
								&& (@checkdate($tresa_date_fin[1], $tresa_date_fin[2], $tresa_date_fin[0])) 
								&& (strlen($resa_date_debut)==8) && (strlen($resa_date_fin)==8) 
								&& ($resa_date_debut < $resa_date_fin) ) {
							$r = new resa_planning($key);
							$r->resa_date_debut=implode('-', $tresa_date_debut);
							$r->resa_date_fin=implode('-', $tresa_date_fin);;
							$r->save();
	
						}
					}
				}
				print pmb_bidi(planning_list()) ;
				break;
				
			case 'val_resa':	//Validation réservation depuis liste
		
				for($i=0;$i<count($resa_check);$i++) {
		
					$key = $resa_check[$i];
					
					//On vérifie les dates
					$tresa_date_debut = explode('-', extraitdate($resa_date_debut[$key]));
					if (strlen($tresa_date_debut[2])==1) $tresa_date_debut[2] = '0'.$tresa_date_debut[2];
					if (strlen($tresa_date_debut[1])==1) $tresa_date_debut[1] = '0'.$tresa_date_debut[1];
					$resa_date_debut = implode('', $tresa_date_debut);
					
					$tresa_date_fin = explode('-', extraitdate($resa_date_fin[$key]));
					if (strlen($tresa_date_fin[2])==1) $tresa_date_fin[2] = '0'.$tresa_date_fin[2];
					if (strlen($tresa_date_fin[1])==1) $tresa_date_fin[1] = '0'.$tresa_date_fin[1];
					$resa_date_fin = implode('', $tresa_date_fin); 	
					
					if ( (checkdate($tresa_date_debut[1], $tresa_date_debut[2], $tresa_date_debut[0])) 
							&& (checkdate($tresa_date_fin[1], $tresa_date_fin[2], $tresa_date_fin[0])) 
							&& (strlen($resa_date_debut)==8) && (strlen($resa_date_fin)==8) 
							&& ($resa_date_debut < $resa_date_fin) ) {
						$r = new resa_planning($key);
						$r->resa_date_debut=implode('-', $tresa_date_debut);
						$r->resa_date_fin=implode('-', $tresa_date_fin);
						$r->resa_validee='1';
						$r->save();
				
					}
				}
				print pmb_bidi(planning_list()) ;
				break;
		
			case 'suppr_resa':	//Suppression réservation depuis liste
		
				for($i=0;$i<count($resa_check);$i++) {
					$key = $resa_check[$i];
					resa_planning::delete($key);
				}	
				print pmb_bidi(planning_list()) ;
				break;
			
			case 'conf_resa':

				for($i=0;$i<count($resa_check);$i++) {
					$key = $resa_check[$i];
					alert_empr_resa_planning ($resa_check[$i], $id_empr[$resa_check[$i]]) ;
				}
				print pmb_bidi(planning_list()) ;
				break;
		
			default :
				print pmb_bidi(planning_list()) ;		
				break;	
		}
		break;
		
	case 'pret' :
		switch ($action) {
			case 'suppr_resa' :	//Suppression réservation depuis fiche lecteur
				resa_planning::delete($id_resa);
				break;
				
			default :
				break;
		}
		break;

	default :
		break;
}
	

?>