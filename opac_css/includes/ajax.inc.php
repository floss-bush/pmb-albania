<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax.inc.php,v 1.1 2008-02-25 13:51:02 ngantier Exp $

/***********************************************
 *function ajax_http_send_response
 *	Send the response at the http_send_request (in http_request.js) without error
 *input :
 *	- $ack : text to be send
 *	- $type : type of header: 'text/html' by default
 *Output:
 *	send an header and $ack to the client
 */	
function ajax_http_send_response($ack='',$type='text/html'){
	global $charset;
	header("Content-Type: $type; charset=$charset");
	print $ack;
}

/***********************************************
 *function ajax_http_send_error
 *	Send the response at the http_send_request (in http_request.js) with an error
 *input :
 *	- $error : Error code 
 *	- $ack : text to be send
 *Output:
 * 	send the header error and $ack to the client
 */	
function ajax_http_send_error($error='404 Not Found',$ack=''){
	header("HTTP/1.0 $error");	
	print $ack;
}

function array2xml($buffer) {
global $charset;
  $xml = "<?xml version='1.0' encoding='iso-8859-1'?>";	
  $xml.= "<pmb_services version=\"1.0\">\n";		
	foreach($buffer as $val) {
		$xml .= "<param>\n";       
		foreach ($val as $key => $value) {
			if(!is_array($value)) {
				$value=htmlspecialchars($value,ENT_QUOTES,$charset);
			    $xml .= "<$key>".$value."</$key>\n";
			}    
		}       
		$xml .= "</param>\n";
    }
    $xml .= "</pmb_services>"; 
    return $xml;
}
