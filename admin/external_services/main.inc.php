<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.1 2009-07-15 07:53:37 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);

switch($sub) {
	case 'general':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["es_admin_general"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["es_admin_general"].$msg[1003].$msg[1001]);
		include('./admin/external_services/general.inc.php');
		break;
	case 'peruser':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["es_admin_peruser"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["es_admin_peruser"].$msg[1003].$msg[1001]);
		include('./admin/external_services/peruser.inc.php');
		break;
	case 'esusers':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["es_admin_esusers"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["es_admin_esusers"].$msg[1003].$msg[1001]);
		include('./admin/external_services/esusers.inc.php');
		break;
	case 'esusergroups':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["es_admin_esusergroups"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["es_admin_esusergroups"].$msg[1003].$msg[1001]);
		include('./admin/external_services/esusergroups.inc.php');
		break;
/*	case 'es_tests':
		$admin_layout = str_replace('!!menu_sous_rub!!', "Tests", $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title."Tests".$msg[1003].$msg[1001]);
		include('./admin/external_services/tests.inc.php');
		break;*/
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout."<br>";
		echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_external_services.txt");
		break;
}
?>
