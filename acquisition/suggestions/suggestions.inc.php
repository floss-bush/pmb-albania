<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions.inc.php,v 1.34 2010-02-23 16:43:48 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//URL de retour du form de création/modification de suggestion
$back_url = "onClick=\"document.location='./acquisition.php?categ=sug&action=list'\"";

require_once($base_path.'/acquisition/suggestions/func_suggestions.inc.php');
require_once($class_path.'/suggestions_map.class.php');

if ($acquisition_sugg_display) {
	require_once($acquisition_sugg_display);
} else {
	require_once('suggestions_display.inc.php');
}

$sug_map = new suggestions_map();

//Traitement des transitions
if ($transition) {
	$sug_map->doTransition($transition, $chk);

	if ($sug_map->getState_DISPLAY($transition)!='NO') {
		$statut = $sug_map->getState_ID($transition);
	} else {
		$statut=-1;
	}
} 

//Traitement des changements de categories
if ($acquisition_sugg_categ== '1' && $action == 'to_categ') {
	$sug_map->changeCateg($chk, $to_categ);
}


//Traitement des actions
print "<h1>".htmlentities($msg['acquisition_sug_ges'],ENT_QUOTES, $charset)."</h1>";

switch($action) {

	case 'list':
		if($sug_map->has_unimarc){
			catalog_notice_form();
		} else {
			if($catnoti){
				$ids =explode(",",$chk);
				require_once($class_path.'/serials.class.php');
				for($i=0;$i<count($ids);$i++){
					$sug = new suggestions($ids[$i]);
					if($sug->sugg_noti_unimarc){						
						$sug->catalog_notice();
					}
				}
			}
		  show_list_sug();
		}			
		break;

	case 'modif':
		$update_action ="./acquisition.php?categ=sug&action=update&id_bibli=".$id_bibli."&id_sug=".$id_sug;
		show_form_sug($update_action);
		break;
	
	case 'update' :
		update_sug();
		show_list_sug();
		break;

	case 'delete' :
		sup_sug();
		show_list_sug();
		break;

	case 'fusChk':
		sug_fusChk();
		break;

	case 'fusVal':
		sug_fusVal();
		show_list_sug();
		break;

	case 'catalog':
		update_sug();
		if($catal_type)
			include($base_path.'/acquisition/suggestions/analysis_form.inc.php');
		else include($base_path.'/acquisition/suggestions/notice_form.inc.php');	
		break;		
		
	case 'upd_notice':
		include($base_path.'/acquisition/suggestions/update_notice.inc.php');
		$update_action ="./acquisition.php?categ=sug&action=update&id_bibli=".$id_bibli."&id_sug=".$id_sug;
		show_form_sug($update_action);
		break;		

	case 'del_pj':
		 $explnum_doc = new explnum_doc($id_pj);
		 $explnum_doc->delete();
		 $req="delete from explnum_doc_sugg where num_explnum_doc='".$id_pj."'";
		 mysql_query($req,$dbh);		 
		 $del_url = "./acquisition.php?categ=sug&action=update&id_bibli=".$id_bibli."&id_sug=".$id_sug;
		 show_form_sug($del_url);
	 break;
	case 'empr_sug':
		break;
	case 'record_uni':		
		//Recherche de la fonction auxiliaire d'intégration
		if ($z3950_import_modele) 
			require_once($base_path."/catalog/z3950/".$z3950_import_modele);
		else require_once($base_path."/catalog/z3950/func_other.inc.php");
		
		require_once($class_path.'/mono_display.class.php');
		require_once($class_path.'/serial_display.class.php');
		
		save_unimarc_notice();
		break;		
	default:
		show_list_sug();	
		break;
		
}

?>

