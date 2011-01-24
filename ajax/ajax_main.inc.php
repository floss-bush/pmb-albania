<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_main.inc.php,v 1.5 2011-01-20 14:36:25 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//En fonction de $categ, il inclut les fichiers correspondants

switch($categ):
	case 'misc':
		include('./ajax/misc/misc.inc.php');
	break;
	case 'alert':
		include('./ajax/misc/alert.inc.php');
	break;
	case 'menuhide':
		include('./ajax/misc/menuhide.inc.php');
	break;
	case 'tri':
		include('./ajax/misc/tri.inc.php');
	break;
	case 'chklnk':
		include('./ajax/misc/chklnk.inc.php');
	break;
	default:
	break;		
endswitch;	
