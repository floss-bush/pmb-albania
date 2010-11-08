<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.6 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($sub) {
	case 'orinot':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[orinot_origine], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[orinot_origine].$msg[1003].$msg[1001]);
		include("./admin/notices/origine_notice.inc.php");
		break;
	case 'perso':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[admin_menu_noti_perso], $admin_layout);
		print $admin_layout;
		include("./admin/notices/perso.inc.php");
		break;
	case 'statut':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[admin_menu_noti_statut], $admin_layout);
		print $admin_layout;
		include("./admin/notices/statut.inc.php");
		break;
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[131].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_notices.txt");
		break;
}
