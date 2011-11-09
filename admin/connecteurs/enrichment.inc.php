<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: enrichment.inc.php,v 1.1 2011-04-15 15:16:02 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($opac_notice_enrichment){
	require_once($class_path."/enrichment.class.php");
	$enrichment = new enrichment();
	
	switch($action){
		case "update" : 
			$enrichment->update();
			$enrichment->show_form();
			break;
		default :
			$enrichment->show_form();
			break;
	}
}else error_message("plop",$msg['admin_connecteurs_enrichment_active_param']);
?>