<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.7 2010-04-15 12:48:24 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusions principales
switch ($quoi) {
	case 'procs':
		$catalog_layout = str_replace('<!--!!sous_menu_choisi!! -->', $msg["caddie_menu_gestion_procs"], $catalog_layout);
		print $catalog_layout ;
		include ("./catalog/caddie/gestion/procs.inc.php");
		break;
	case 'remote_procs':
		$catalog_layout = str_replace('<!--!!sous_menu_choisi!! -->', $msg["remote_procedures_catalog_title"], $catalog_layout);
		print $catalog_layout ;
		include ("./catalog/caddie/gestion/remote_procs.inc.php");
		break;
	case 'panier':
	default:
		$catalog_layout = str_replace('<!--!!sous_menu_choisi!! -->', $msg["caddie_menu_gestion_panier"], $catalog_layout);
		print $catalog_layout ;
		include ("./catalog/caddie/gestion/panier.inc.php");
		break;
	}
