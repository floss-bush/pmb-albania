<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes_actions.inc.php,v 1.5 2010-08-27 14:25:08 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/demandes_actions.class.php");
require_once($class_path."/demandes.class.php");
require_once($class_path."/demandes_notes.class.php");
require_once($class_path."/explnum_doc.class.php");

$actions = new demandes_actions($idaction);
$demandes = new demandes($iddemande);
$notes = new demandes_notes($idnote,$idaction);
$explnum_doc = new explnum_doc($iddocnum);


switch($sub){
	case 'com':
		switch($act){
			case 'close_fil':
				$actions->close_fil();
			break;
		}
		$actions->show_com_form();
		break;
	case 'rdv_plan':
		switch($act){
			case 'close_rdv':
				$actions->close_rdv();
			break;
		}
		$actions->show_planning_form();
		break;
	case 'rdv_val':
		switch($act){
			case 'val_rdv':
				$actions->valider_rdv();
			break;
		}
		$actions->show_rdv_val_form();
		break;
	default:
		switch($act){		
			case 'add_action':
				$actions->show_modif_form();
			break;
			case 'save_action':
				$actions->save();
				$actions->show_consultation_form();
			break;
			case 'modif':
				$actions->show_modif_form();
			break;
			case 'see':
				$actions->show_consultation_form();
			break;
			case 'suppr_action':
				$actions->delete();
				$demandes->show_consult_form();
			break;
			case 'save_note':
				$notes->save();
				$actions->show_consultation_form();
			break;
			case 'suppr_note':
				$notes->delete();
				$actions->show_consultation_form();
			break;
			case 'add_docnum':
				$actions->show_docnum_form();
			break;
			case 'save_docnum':
				if($f_url){
					$explnum_doc->explnum_doc_url = stripslashes($f_url);
					$explnum_doc->explnum_doc_mime = 'URL';
					$explnum_doc->explnum_doc_nomfichier = stripslashes($f_nom ? $f_nom : $f_url);
					$explnum_doc->save();
				} else {
					if(!$_FILES['f_fichier']['error']){
						$explnum_doc->load_file($_FILES['f_fichier']);
						$explnum_doc->analyse_file();
					}
					if($f_nom) $explnum_doc->setName($f_nom);
					$explnum_doc->save();
				}	
					global $ck_prive,$ck_rapport;
					$req = "replace into explnum_doc_actions set 
						num_explnum_doc='".$explnum_doc->explnum_doc_id."', 
						num_action='".$actions->id_action."',
						prive='".($ck_prive ? 1 : 0 )."',
						rapport='".($ck_rapport ? 1 : 0 )."'
						";
					mysql_query($req,$dbh);
				
				$actions->show_consultation_form();
			break;
			case 'suppr_docnum':
				$explnum_doc->delete();
				$req = "delete from explnum_doc_actions where num_explnum_doc='".$explnum_doc->explnum_doc_id."'";
				mysql_query($req,$dbh);
				$actions->show_consultation_form();
			break;
			case 'modif_docnum':
				$actions->show_docnum_form();
			break;
		}
	break;
}


?>