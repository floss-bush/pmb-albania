<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_main.inc.php,v 1.3 2009-10-01 13:29:24 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//En fonction de $categ, il inclut les fichiers correspondants

switch($categ):
	case 'misc':
		include('./ajax/misc/misc.inc.php');
	break;
	case 'liste_lecture' :
		include('./ajax/ajax_liste_lecture.inc.php');
	break;
	case 'demandes' :
		include('./ajax/ajax_demandes.inc.php');
	break;
	default:
	break;		
endswitch;	
