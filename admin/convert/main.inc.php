<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.8 2009-05-04 15:09:03 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$include_path/templates/export_param.tpl.php");

switch($sub) {
	case 'import':
		$admin_layout = str_replace('!!menu_sous_rub!!', "$msg[admin_convExterne]", $admin_layout);
		print $admin_layout;
		include("./admin/convert/import.inc.php");
		break;
	case 'export':
		$admin_layout = str_replace('!!menu_sous_rub!!', "$msg[admin_ExportPMB]", $admin_layout);
		print $admin_layout;
		include("./admin/convert/export.inc.php");
		break;
	case 'paramopac':
		$admin_layout = str_replace('!!menu_sous_rub!!', "$msg[admin_param_export_opac]", $admin_layout);
		$form_entete_param = str_replace('!!param_title!!',$msg['admin_param_export_opac'],$form_entete_param);
		$form_entete_param = str_replace('!!action!!',"./admin.php?categ=convert&sub=paramopac",$form_entete_param);
	case 'paramgestion':
		$admin_layout = str_replace('!!menu_sous_rub!!', "$msg[admin_param_export_gestion]", $admin_layout);
		$form_entete_param = str_replace('!!param_title!!',$msg['admin_param_export_gestion'],$form_entete_param);
		$form_entete_param = str_replace('!!action!!',"./admin.php?categ=convert&sub=paramgestion",$form_entete_param);
		print $admin_layout;
		include("./admin/convert/export_param.php");
		break;
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_convert.txt");
		break;
}
