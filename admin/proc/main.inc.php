<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.3 2008-03-19 11:48:25 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/parameters.class.php");
require_once ($include_path."/templates/procs_exp_imp.tpl.php");
require_once ($include_path."/procs_exp_imp.inc.php");

switch($sub) {
	case 'clas':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[admin_menu_act_perso_clas], $admin_layout);
		print $admin_layout;
		include("./admin/proc/clas.inc.php");
		break;
	case 'req':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[admin_menu_req], $admin_layout);
		print $admin_layout;
		include("./admin/proc/req.inc.php");
		break;
	case 'proc':
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg[admin_menu_act_perso], $admin_layout);
		print $admin_layout;
		include("./admin/proc/proc.inc.php");
		break;
}


