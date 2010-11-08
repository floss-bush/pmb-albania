<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.8 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($sub) {
	case 'zbib':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[768], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[768].$msg[1003].$msg[1001]);
		include("./admin/z3950/zbib.inc.php");
		break;
	case 'zattr':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[769], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[769].$msg[1003].$msg[1001]);
		include("./admin/z3950/zattr.inc.php");
		break;
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[770].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_z3950.txt");
		break;
	}

	