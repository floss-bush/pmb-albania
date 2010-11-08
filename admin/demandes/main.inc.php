<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.1 2009-10-01 13:29:24 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($sub) {
	case 'theme':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["demandes_theme"], $admin_layout);
		print $admin_layout;
		include("./admin/demandes/theme.inc.php");		
		break;
	case 'type':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["demandes_nature"], $admin_layout);
		print $admin_layout;
		include("./admin/demandes/type.inc.php");		
		break;
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[131].$msg[1003].$msg[1001]);
		break;
}
?>