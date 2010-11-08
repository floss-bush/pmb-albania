<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.4 2007-03-10 09:25:49 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo window_title($database_window_title.$msg[dsi_menu_title]);
switch($sub) {
    case 'lancer':
		include_once("./dsi/diffuser/lancer.inc.php");
		break;
    case 'auto':
		include_once("./dsi/diffuser/auto.inc.php");
		break;
    case 'manu':
		include_once("./dsi/diffuser/manu.inc.php");
		break;
    default:
        break;
    }
