<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.14 2008-11-22 06:50:03 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

include_once("$class_path/bannette.class.php");
include_once("$class_path/equation.class.php");
include_once("$class_path/classements.class.php");
require_once("$class_path/docs_location.class.php");
include_once("$class_path/rss_flux.class.php");
require_once("./dsi/func_abo.inc.php");
require_once("./dsi/func_pro.inc.php");
require_once("./dsi/func_common.inc.php");
require_once("./dsi/func_clas.inc.php");
require_once("./dsi/func_equ.inc.php");
require_once("./dsi/func_diff.inc.php");
require_once("./dsi/func_rss.inc.php");
require_once("$base_path/admin/convert/start_export.class.php");

switch($categ) {
	case 'options':
		include('./dsi/options/main.inc.php');
		break;
	case 'equations':
		include('./dsi/equations/main.inc.php');
		break;
	case 'bannettes':
		include('./dsi/bannettes/main.inc.php');
		break;
	case 'diffuser':
		@set_time_limit($pmb_set_time_limit) ;
		include('./dsi/diffuser/main.inc.php');
		break;
	case 'fluxrss':
		include('./dsi/rss/main.inc.php');
		break;
	default:
        include("$include_path/messages/help/$lang/dsi.txt");
		break;
}
