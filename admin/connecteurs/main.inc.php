<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.6 2011-04-15 15:16:02 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);

switch($sub) {
	case 'in':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_connecteurs_in"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_connecteurs_in"].$msg[1003].$msg[1001]);
		include('./admin/connecteurs/in.inc.php');
		break;
	case 'out':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_connecteurs_out"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_connecteurs_out"].$msg[1003].$msg[1001]);
		include('./admin/connecteurs/out.inc.php');
		break;
	case 'categ':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_connecteurs_categ"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_connecteurs_categ"].$msg[1003].$msg[1001]);
		include('./admin/connecteurs/categ.inc.php');
		break;
	case 'out_sets':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_connecteurs_sets"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_connecteurs_sets"].$msg[1003].$msg[1001]);
		include('./admin/connecteurs/out_sets.inc.php');
		break;
	case 'categout_sets':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_connecteurs_categsets"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_connecteurs_categsets"].$msg[1003].$msg[1001]);
		include('./admin/connecteurs/out_set_categ.inc.php');
		break;
	case 'out_auth':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_connecteurs_outauth"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_connecteurs_outauth"].$msg[1003].$msg[1001]);
		include('./admin/connecteurs/out_auth.inc.php');
		break;
	case 'enrichment' :
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_connecteurs_enrichment"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_connecteurs_enrichment"].$msg[1003].$msg[1001]);
		include('./admin/connecteurs/enrichment.inc.php');
		break;
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout."<br />";
		echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_connecteurs.txt");
		break;
}
?>
