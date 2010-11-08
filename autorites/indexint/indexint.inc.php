<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: indexint.inc.php,v 1.8 2007-07-31 09:23:03 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// on a besoin des templates indexation interne
include("$include_path/templates/indexint.tpl.php");

// la classe de gestion des indexation interne
require_once("$class_path/indexint.class.php");

print "<h1>".$msg[140]."&nbsp;: ".$msg["indexint_menu_title"]."</h1>";

switch($sub) {
	case 'reach':
		include('./autorites/indexint/indexint_list.inc.php');
		break;
	case 'delete':
		$indexint = new indexint($id,$id_pclass);
		$sup_result = $indexint->delete();
		if(!$sup_result)
			include('./autorites/indexint/indexint_list.inc.php');
			else {
				error_message($msg[132], $sup_result, 1, "./autorites.php?categ=indexint&sub=indexint_form&id=$id");
				}
		break;
	case 'replace':
		if(!$n_indexint_id) {
			$indexint = new indexint($id,$id_pclass);
			$indexint->replace_form();
		} else {
			// routine de remplacement
			$indexint = new indexint($id,$id_pclass);
			$rep_result = $indexint->replace($n_indexint_id);
			if(!$rep_result)
				include('./autorites/indexint/indexint_list.inc.php');
			else {
				error_message($msg[132], $rep_result, 1, "./autorites.php?categ=indexint&sub=indexint_form&id=$id");
			}
		} 
		break;
	case 'update':
		// mettre à jour 
		$indexint = new indexint($id,$id_pclass);
		$indexint->update($indexint_nom, $indexint_comment,$id_pclass);
		include('./autorites/indexint/indexint_list.inc.php');
		break;
	case 'indexint_form':
	// création 
		if(!$id) {
			// affichage du form pour création
			$indexint = new indexint(0,$id_pclass);
			$indexint->show_form();
		} else {
			// affichage du form pour modification
			$indexint = new indexint($id,$id_pclass);
			$indexint->show_form($id);
		}
		break;
	case 'indexint_last':
		$last_param=1;
		$tri_param = "order by indexint_id desc ";
		$limit_param = "limit 0, $pmb_nb_lastautorities ";
		$clef = "";
		$nbr_lignes = 0 ;
		include('./autorites/indexint/indexint_list.inc.php');
		break;
	case 'pclass':
		include('./autorites/indexint/pclass.inc.php');
	break;		
	case 'pclass_form':
		include('./autorites/indexint/pclass_form.inc.php');
	break;		
	case 'pclass_update' :
		include('./autorites/indexint/pclass_update.inc.php');
		break; 
	case 'pclass_delete' :
		include('./autorites/indexint/pclass_delete.inc.php');
		break; 
	default:
	// affichage du début de la liste (par défaut)
		include('./autorites/indexint/indexint_list.inc.php');
		break;
}
