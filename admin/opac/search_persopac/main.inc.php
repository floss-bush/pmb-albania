<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.1 2009-05-20 15:19:29 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de switch recherche notice

// inclusions principales

switch($section) {
	case "liste":
		// affichage de la liste des recherches en opac
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_menu_search_persopac"], $admin_layout);
		print $admin_layout;		
		include("./admin/opac/search_persopac/liste.inc.php");
	break;	
	default :
		// affichage de la liste des recherches en opac
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["search_persopac_list_title"], $admin_layout);
		print $admin_layout;	
		include("./admin/opac/search_persopac/liste.inc.php");
	break;
}


