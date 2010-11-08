<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.9 2009-05-16 11:11:52 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($sub) {
	case 'tables':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[716], $admin_layout);
		print $admin_layout."<br />";
		include("./admin/misc/tables.inc.php");
		break;
	case 'mysql':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[718], $admin_layout);
		print $admin_layout."<br />";
		include("./admin/misc/mysql.inc.php");
		break;
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout."<br />";
		echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_misc.txt");
		break;
}
