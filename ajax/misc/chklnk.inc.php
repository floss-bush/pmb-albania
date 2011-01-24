<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: chklnk.inc.php,v 1.1 2011-01-20 14:36:25 arenou Exp $

require_once ("$class_path/curl.class.php");
if($link != ""){
//	if()
	$curl = new Curl();
	$curl->limit=1024;//Limite à 1Ko
	if ($timeout) $curl->timeout = $timeout;
	$response = $curl->get($link);
	if ($response) $msg = $response->headers['Status-Code'];
	else $msg = "can't resolve $link";
	
}else $msg = "empty link";
print $msg;
?>