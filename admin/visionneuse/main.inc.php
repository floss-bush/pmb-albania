<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.2 2010-07-02 09:45:56 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);

$visionneuse_path = $base_path."/opac_css/visionneuse"; 
require_once($visionneuse_path."/classes/defaultConf.class.php");
require_once($visionneuse_path."/classes/mimetypeClass.class.php");

$class_param = new mimetypeClass($visionneuse_path."/classes/mimetypes/");

//on récup les paramétrages actuels...
$mimetypeConf = unserialize(htmlspecialchars_decode($opac_visionneuse_params));
if(sizeof($mimetypeConf)==0){
	$defaultConf = new defaultConf();
	$mimetypeConfByDefault = $defaultConf->defaultMimetype;
}

switch($sub) {
	case 'class':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["visionneuse_admin_class"].($quoi != "" ? " > $quoi" : ""), $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["visionneuse_admin_menu"].$msg[1003].$msg[1001]);
		include('./admin/visionneuse/class_dispo.inc.php');
	break;
	case 'mimetype':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["visionneuse_admin_mimetype"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["visionneuse_admin_menu"].$msg[1003].$msg[1001]);
		include('./admin/visionneuse/mimetype.inc.php');
	break;
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout."<br />";
		echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_visionneuse.txt");
	break;
}
?>
