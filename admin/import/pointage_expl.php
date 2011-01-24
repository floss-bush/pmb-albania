<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pointage_expl.php,v 1.15 2011-01-12 10:39:44 touraine37 Exp $

// définition du minimum nécéssaire 
$base_path="../..";                            
$base_auth = "ADMINISTRATION_AUTH";  
$base_title = "";    
require_once ("$base_path/includes/init.inc.php");  

// les requis par pointage_expl.php ou ses sous modules
include("$include_path/isbn.inc.php");
include("$include_path/marc_tables/$lang/empty_words");
include("$class_path/XMLlist.class.php");
include("$class_path/lender.class.php");
include("$class_path/docs_statut.class.php");
include("$class_path/docs_section.class.php");
include("$class_path/docs_location.class.php");
include("$class_path/docs_type.class.php");
include("$class_path/docs_codestat.class.php");
include("$class_path/lender.class.php");
include("$class_path/author.class.php");
include("$class_path/serie.class.php");
include("$include_path/templates/expl.tpl.php"); 
require_once("$include_path/notice_authors.inc.php");
require_once("$include_path/notice_categories.inc.php");

		
if ($book_statut_id=="" || $book_section_id=="" || $book_location_id=="") {
	$action="";
} else {
	$nouv_statut = new docs_statut($book_statut_id);
	$nouv_section = new docs_section($book_section_id);
	$nouv_location = new docs_location($book_location_id);
	$nouv_support = new docs_type($book_doctype_id);
	$nouv_proprio = new lender($book_lender_id);
	$nouv_codestat = new docs_codestat($book_codestat_id);
}
		
switch ($action) {
	case 'pointage':
		/* faire ici la validation du noex précédent */
		if ($noex_valide) {
			$requete  = "UPDATE exemplaires set";
			$requete .= " expl_statut ='".$book_statut_id."',";	
			$requete .= " expl_section ='".$book_section_id."',";	
			$requete .= " expl_location ='".$book_location_id."',";	
			$requete .= " expl_typdoc ='".$book_doctype_id."',";	
			$requete .= " expl_codestat ='".$book_codestat_id."',";	
			$requete .= " expl_owner ='".$book_lender_id."',";	
			$requete .= " expl_note ='".$expl_note."',";	
			$requete .= " expl_comment ='".$expl_comment."'";
			$requete .= " WHERE expl_cb='".$noex_valide."'";
			$result = @mysql_query($requete, $dbh);
		}
                	
		/* on a un num d'exemplaire à afficher */
		if ($noex) { 
			$requete  = "SELECT e.*, t.*, s.*, st.*, l.*, stat.*, lend.*, n.*";
			$requete .= " FROM exemplaires e";
			$requete .= " left join docs_type t on e.expl_typdoc=t.idtyp_doc ";
			$requete .= " left join docs_section s on e.expl_section=s.idsection ";	
			$requete .= " left join docs_statut st on e.expl_statut=st.idstatut ";	
			$requete .= " left join docs_location l on e.expl_location=l.idlocation";	
			$requete .= " left join docs_codestat stat on e.expl_codestat=stat.idcode ";
			$requete .= " left join lenders lend on e.expl_owner=lend.idlender ";
			$requete .= " left join notices n on e.expl_notice=n.notice_id ";
			$requete .= " WHERE e.expl_cb='".$noex."'";
			$requete .= " LIMIT 1";
			$result = mysql_query($requete, $dbh) or die (mysql_error()." ".$requete);
                	
			if(mysql_num_rows($result)) {
				$item = mysql_fetch_object($result);
				$header="";
				if($item->tparent_id) {
					$serie = new serie($item->tparent_id);
					$tparent = $serie->name;
				}
				$tparent ? $header = $tparent : $header = '';
				$item->tnvol && $header ? $header .= ", $item->tnvol" : $header = '';
				$header ? $header .= '. ' : $header = '';
				
				$responsabilites = get_notice_authors($item->notice_id) ;
				$as = array_search ("0", $responsabilites["responsabilites"]) ;
				if ($as!== FALSE && $as!== NULL) {
					$auteur_0 = $responsabilites["auteurs"][$as] ;
					$auteur = new auteur($auteur_0["id"]);
					$header_aut .= $auteur->isbd_entry;
				} else {
					$aut1_libelle=array();
					$as = array_keys ($responsabilites["responsabilites"], "1" ) ;
					for ($i = 0 ; $i < count($as) ; $i++) {
						$indice = $as[$i] ;
						$auteur_1 = $responsabilites["auteurs"][$indice] ;
						$auteur = new auteur($auteur_1["id"]);
						$aut1_libelle[]= $auteur->isbd_entry;
					}
					$header_aut .= implode (", ",$aut1_libelle) ;
				}
				
				$header_aut ? $header .= $item->tit1.' / '.$header_aut: $header .= $item->tit1;
				
				$expl_pointage = str_replace('!!notice!!', $header, $expl_pointage);
				$expl_pointage = str_replace('!!action!!', $action, $expl_pointage);
				$expl_pointage = str_replace('!!id!!', $item->expl_notice, $expl_pointage);
				$expl_pointage = str_replace('!!cb!!', $item->expl_cb, $expl_pointage);
				$expl_pointage = str_replace('!!note!!', $item->expl_note, $expl_pointage);
				$expl_pointage = str_replace('!!comment!!', $item->expl_comment, $expl_pointage);
				$expl_pointage = str_replace('!!cote!!', $item->expl_cote, $expl_pointage);
				
				// select "type document"
				$expl_pointage = str_replace('!!type_doc!!', $item->tdoc_libelle, $expl_pointage);		
        			
				// select "section"
				$expl_pointage = str_replace('!!section!!', $item->section_libelle, $expl_pointage);
        			
				// select "statut"
				$expl_pointage = str_replace('!!statut!!', $item->statut_libelle, $expl_pointage);
        			
				// select "localisation"
				$expl_pointage = str_replace('!!localisation!!', $item->location_libelle, $expl_pointage);
        			
				// select "lender"
				$expl_pointage = str_replace('!!owner!!', $item->lender_libelle, $expl_pointage);
        			
				// select "code statistique"
				$expl_pointage = str_replace('!!codestat!!', $item->codestat_libelle, $expl_pointage);
				
				// select "propriétaire=lender"
				$expl_pointage = str_replace('!!owner!!', $item->lender_libelle, $expl_pointage);
				
				$expl_pointage = str_replace('!!noex_valide!!', $noex, $expl_pointage);
				$expl_pointage = str_replace('!!annuler_action!!', './pointage_expl.php?action=pointage&book_statut_id='.$book_statut_id, $expl_pointage);
				
				$expl_pointage = str_replace('!!nouveau_statut!!', $nouv_statut->libelle, $expl_pointage);
				$expl_pointage = str_replace('!!nouvelle_section!!', $nouv_section->libelle, $expl_pointage);
				$expl_pointage = str_replace('!!nouvelle_location!!', $nouv_location->libelle, $expl_pointage);
				$expl_pointage = str_replace('!!nouveau_support!!', $nouv_support->libelle, $expl_pointage);
				$expl_pointage = str_replace('!!nouveau_codestat!!', $nouv_codestat->libelle, $expl_pointage);
				$expl_pointage = str_replace('!!nouveau_proprio!!', $nouv_proprio->lender_libelle, $expl_pointage);
				
				$expl_pointage_base = str_replace('!!explencoursdevalidation!!', $expl_pointage, $expl_pointage_base);
				} else {
					$expl_pointage_base = str_replace('!!explencoursdevalidation!!', "<hr /> $noex : $msg[367]...<hr />", $expl_pointage_base);
				}
		} else {
			$expl_pointage_base = str_replace('!!explencoursdevalidation!!', "", $expl_pointage_base);
		}
		$expl_pointage_base = str_replace('!!book_statut_id!!', docs_statut::gen_combo_box($book_statut_id), $expl_pointage_base);
		$expl_pointage_base = str_replace('!!book_section_id!!', docs_section::gen_combo_box($book_section_id), $expl_pointage_base);
		$expl_pointage_base = str_replace('!!book_location_id!!', docs_location::gen_combo_box($book_location_id), $expl_pointage_base);
		$expl_pointage_base = str_replace('!!book_doctype_id!!', docs_type::gen_combo_box($book_doctype_id), $expl_pointage_base);
		$expl_pointage_base = str_replace('!!book_codestat_id!!', docs_codestat::gen_combo_box($book_codestat_id), $expl_pointage_base);
		$expl_pointage_base = str_replace('!!book_lender_id!!', lender::gen_combo_box($book_lender_id), $expl_pointage_base);
		print $expl_pointage_base;
		break;
		
	default:
		include("$include_path/messages/help/$lang/pointage_expl.txt");
		print "
			<form class='form-$current_module' METHOD='post' ACTION=\"pointage_expl.php\">
			<h3>$msg[562]</h3>
			<div class='form-contenu'>
			
				<div class='row'>
					<div class='colonne4'>
						<!-- CB -->
						<label class='etiquette' for='f_ex_statut'>$msg[291]</label>
						<div class='row'>
							<input type='text' class='saisie-20em' name='noex' value=''>
							</div>
					</div>
				
					<div class='colonne4'>
						<!-- statut -->
						<label class='etiquette' for='f_ex_stat'>$msg[297]</label>
						<div class='row'>
							".docs_statut::gen_combo_box($book_statut_id)."
						</div>
					</div>
					
					<div class='colonne4'>
						<!-- section -->
						<label class='etiquette' for='f_ex_section'>$msg[295]</label>
						<div class='row'>
							".docs_section::gen_combo_box($book_section_id)."
						</div>
					</div>
					
					<div class='colonne_suite'>
						<!-- localisation -->
						<label class='etiquette' for='f_ex_location'>$msg[298]</label>
						<div class='row'>
							".docs_location::gen_combo_box($book_location_id)."
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='colonne4'>
						<div class='row'>
							&nbsp;
						</div>
					</div>
					
					<div class='colonne4'>
						<!-- typdoc=support -->
						<label class='etiquette' for='f_ex_typdoc'>$msg[294]</label>
						<div class='row'>
							".docs_type::gen_combo_box($book_doctype_id)."
						</div>
					</div>
			
					<div class='colonne4'>
						<!-- codestat -->
						<label class='etiquette' for='f_ex_cstat'>$msg[299]</label>
						<div class='row'>
							".docs_codestat::gen_combo_box($book_codestat_id)."
						</div>
					</div>
					
				<div class='colonne_suite'>
						<!-- owner -->
						<label class='etiquette' for='f_ex_owner'>$msg[651]</label>
						<div class='row'>
							".lender::gen_combo_box($book_lender_id)."
						</div>
					</div>
				</div>
				<div class='row'> </div>
			
			</div>	
			<INPUT TYPE=\"SUBMIT\" class='bouton' NAME=\"upload\" VALUE=\"".$msg[502]."\">
			<INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\">
			<INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"pointage_expl\">
			<INPUT NAME=\"action\" TYPE=\"hidden\" value=\"pointage\">
			</FORM>";
		break;
}

function expl_pointage($action, $annuler='') {

	global $expl_pointage;
	global $msg;
	
	

} /* fin expl_pointage */



?>

