<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main_ajax.inc.php,v 1.1 2008-01-25 15:00:15 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch ($moyen) {
	case 'douchette':
		include ("./catalog/caddie/pointage/douchette_ajax.inc.php");
	break;
	default:

	break;
}
