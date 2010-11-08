<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.2 2010-03-16 14:25:22 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);

switch($sub) {
	case 'pret':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["selfservice_admin_pret"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["selfservice_admin_menu"].$msg[1003].$msg[1001]);
		include('./admin/selfservice/pret.inc.php');
	break;
	case 'retour':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["selfservice_admin_retour"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["selfservice_admin_menu"].$msg[1003].$msg[1001]);
		include('./admin/selfservice/retour.inc.php');
	break;

	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout."<br />";
		echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_selfservice.txt");
	break;
}
?>
