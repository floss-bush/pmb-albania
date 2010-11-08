<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: make_multi_sugg.inc.php,v 1.3 2010-01-18 21:21:52 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path."/classes/suggestion_multi.class.php");

if($action) $act=$action;
$sug = new suggestion_multi($notices); 

switch($act){
	
	case 'save_multi_sugg':
		$sug->save();
	break;
	case 'transform_caddie':
		if($notice){
			$sug->liste_sugg = $notice;
		} else 	$sug->liste_sugg = $_SESSION['cart'];
		print $sug->display_form();
	break;
	case 'transform_list':
		$sug->liste_sugg = explode(",",$notice_filtre); 
		print $sug->display_form();
	break;
	default:
		print $sug->display_form();	
	break;
}
	
?>