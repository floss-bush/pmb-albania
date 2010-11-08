<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.2 2007-03-10 09:25:49 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($sub) {
    case 'definition':
    default:
		echo window_title($database_window_title.$msg[dsi_menu_flux]);
		include_once("./dsi/rss/rss.inc.php");
		break;
    }

