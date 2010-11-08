<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: message.inc.php,v 1.7 2008-06-19 15:49:34 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$temp_aff = alerte_message_administration () ;
if ($temp_aff) $aff_alerte.= "<ul>".$temp_aff."</ul>" ;

function alerte_message_administration () {	
	global $dbh ;
	global $msg;
	
	return "" ;
}

