<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_main.inc.php,v 1.1 2010-06-21 09:10:48 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$prefix = "gestfic0";
switch($categ){
	
	case 'fiche':
		include('./fichier/ajax/fiche_ajax.inc.php');
		break;
	
}