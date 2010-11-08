<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.11 2007-03-10 08:32:24 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($sub) {
	case 'typdoc':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[admin_menu_docs_type], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[admin_menu_docs_type].$msg[1003].$msg[1001]);
		include("./admin/docs/typ_doc.inc.php");
		break;
	case 'codstat':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[24], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[24].$msg[1003].$msg[1001]);
		include("./admin/docs/cod_stat.inc.php");
		break;
	case 'location':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[21], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[21].$msg[1003].$msg[1001]);
		include("./admin/docs/location.inc.php");
		break;
	case 'section':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[19], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[19].$msg[1003].$msg[1001]);
		include("./admin/docs/section.inc.php");
		break;
	case 'statut':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[20], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[20].$msg[1003].$msg[1001]);
		include("./admin/docs/statut.inc.php");
		break;
	case 'orinot':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[orinot_origine], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[orinot_origine].$msg[1003].$msg[1001]);
		include("./admin/docs/origine_notice.inc.php");
		break;
	case 'lenders':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[554], $admin_layout);
		print $admin_layout;
		include("./admin/docs/lender.inc.php");
		break;
	case 'perso':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[admin_menu_noti_perso], $admin_layout);
		print $admin_layout;
		include("./admin/docs/perso.inc.php");
		break;
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[131].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_docs.txt");
		break;
}
