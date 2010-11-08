<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.6 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch ($sub) {
	case 'lieux' :
		//Gestion des lieux
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["sauv_title_lieux"], $admin_layout);
		print $admin_layout;
		echo window_title($msg["sauv_title_lieux"]);
		include ("./admin/sauvegarde/lieux.inc.php");
		break;
	case 'tables' :
		//Gestion des groupes de tables
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["sauv_title_tables"], $admin_layout);
		print $admin_layout;
		echo window_title($msg["sauv_title_tables"]);
		include ("./admin/sauvegarde/tables.inc.php");
		break;
	case 'gestsauv' :
		//Gestion des sauvegardes
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["sauv_title_jeux"], $admin_layout);
		print $admin_layout;
		echo window_title($msg["sauv_title_jeux"]);
		include ("./admin/sauvegarde/sauvegardes.inc.php");
		break;
	case 'launch' :
		//Page de lancement d'une sauvegarde
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["sauv_title_launch"], $admin_layout);
		print $admin_layout;
		echo window_title($msg["sauv_title_launch"]);
		include("./admin/sauvegarde/launch.inc.php");
		break;
	case 'list' :
		//Page de gestion des sauvegardes déjà effectuées
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["sauv_title_sauv_list"], $admin_layout);
		print $admin_layout;
		echo window_title($msg["sauv_title_sauv_list"]);
		include("./admin/sauvegarde/sauvegarde_list.inc.php");
		break;
	default :
		//Page de gestion des sauvegardes déjà effectuées
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout;
		include("$include_path/messages/help/$lang/admin_sauvegarde.txt");
		break;
	}

?>