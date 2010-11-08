<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: logs.inc.php,v 1.3 2009-07-07 13:14:52 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");
require_once($base_path."/classes/record_log.class.php");

global $pmb_logs_activate;
if($pmb_logs_activate){
	global $log;
	$log = new record_log();	
}
?>