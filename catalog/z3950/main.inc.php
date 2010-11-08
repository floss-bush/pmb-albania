<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.7 2007-03-10 08:50:38 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo window_title("Z39-50");
require ("./catalog/z3950/z3950_func.inc.php");
require ("$include_path/templates/z3950.tpl.php");

switch($action) {
	case 'search':
		include ("./catalog/z3950/z_frame.php");
		break;
	case 'display':
		include ("./catalog/z3950/display.inc.php");
		break;
	case 'import':
	case 'integrer':
	case 'integrerexpl':
		include ("./catalog/z3950/import.inc.php");
		break;
	default:
		include ("./catalog/z3950/search.inc.php");
		break;
}

