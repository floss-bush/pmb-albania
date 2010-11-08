<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.11 2008-12-24 14:27:20 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de switch recherche notice

// inclusions principales
require_once("$include_path/templates/notice_search.tpl.php");
if($id) {
	// notice sélectionnée -> création de la page de notice
	// include du fichier des opérations d'affichage
	include('./catalog/notices/isbd.inc.php');
} else {
	switch($mode) {
		case 1:
			// recherche catégorie/sujet INDEXATION INTERNE
			print $menu_search[1];
			include('./catalog/notices/search/subjects/main.inc.php');
			break;
		case 5:
			// recherche par termes
			print $menu_search[5];
			include('./catalog/notices/search/terms/main.inc.php');
			break;
		case 2:
			// recherche éditeur/collection
			print $menu_search[2];
			include('./catalog/notices/search/publishers/main.inc.php');
			break;
		case 3:
			// accès aux paniers
			print $menu_search[3];
			include('./catalog/notices/search/cart.inc.php');
			break;
		case 4:
			// autres recherches
			print $menu_search[4];
			include('./catalog/notices/search/others.inc.php');
			break;		
		case 6:
			// recherches avancees
			print $menu_search[6];
			include('./catalog/notices/search/extended/main.inc.php');
			break;
		case 7:
			// recherches externe
			print $menu_search[7];
			include('./catalog/notices/search/external/main.inc.php');
			break;	
		case 8:
			// recherches exemplaires
			print $menu_search[8];
			include('./catalog/notices/search/expl/main.inc.php');
			break;		
		case 9:
			// recherches titres uniformes
			print $menu_search[9];
			include('./catalog/notices/search/titres_uniformes/main.inc.php');
			break;
		case 10:
			// recherches titres de série
			print $menu_search[10];
			include('./catalog/notices/search/titre_serie/main.inc.php');
			break;						
		default :
			// recherche auteur/titre
			print $menu_search[0];
			include('./catalog/notices/search/authors/main.inc.php');
			break;
	}
	print $layout_end;
}
