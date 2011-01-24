<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fichier_gestion.inc.php,v 1.4 2010-11-26 16:17:27 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/parametres_perso.class.php");
require_once($class_path."/fiche.class.php");

switch($mode){
	case 'champs':		
		print "<h1>".htmlentities($msg['fichier_gestion_champs_title'],ENT_QUOTES,$charset)."</h1>";
		$option_visibilite=array();
		$option_visibilite["multiple"]="block";
		$option_visibilite["obligatoire"]="block";
		$option_visibilite["search"]="block";
		$option_visibilite["export"]="none";
		$option_visibilite["exclusion"]="none";
		$p_perso=new parametres_perso($prefix,"./fichier.php?categ=gerer&mode=champs",$option_visibilite);
		$p_perso->proceed();
		break;
	case 'reindex':
		$fiche = new fiche();
		switch($act){
			case 'run':
				$fiche->reindex_all();
				break;
			default:
				$fiche->show_reindex_form();
			break;
		}
		break;
	case 'display':
		$fiche = new fiche();
		switch($sub){
			case 'position':
				$fichier_menu_display = str_replace("!!menu_sous_rub!!",htmlentities($msg['fichier_display_position'],ENT_QUOTES,$charset),$fichier_menu_display);
				print $fichier_menu_display;
				break;
			case 'list':
				$fiche->show_search_list($act,"./fichier.php?categ=consult&mode=search&perso_word=$perso_word",$page);
				break;	
			default:
				$fichier_menu_display = str_replace("!!menu_sous_rub!!","",$fichier_menu_display);
				print $fichier_menu_display;
				break;
		}		
		
		break;
}