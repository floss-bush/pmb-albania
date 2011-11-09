<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: connecteurs_out_common.inc.php,v 1.6 2011-02-17 14:31:30 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Change quelques charactère pour ne pas déranger le XML
function XMLEntities($string) {
	return str_replace ( array ( '&', '"', "'", '<', '>', '' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '&apos;' ), $string );
}

//Renvoi l'url courante de la page (complête) http://example.com/machin/truc/fichier.php?get1=sdfsdf&get2=zerzer
function curPageURL() {
	$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
	$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
	$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
	$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
	return $url;
}

//Renvoi l'url courante de la page sans get http://example.com/machin/truc/fichier.php
function curPageBaseURL() {
	$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
	$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
	$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
	$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER['SCRIPT_NAME'];
	return $url;
}

//Normaliser une chaine en utf-8 en fonction de l'encodage de PMB
function utf8_normalize($chaine) {
	global $charset;
	
	if ($charset!="utf-8") return utf8_encode($chaine); else return $chaine;
}

function charset_pmb_normalize($mixed){
	global $charset;
	$is_array = is_array($mixed);
	$is_object = is_object($mixed);
	if($is_array || $is_object){
		foreach($mixed as $key => $value){
			 if($is_array) $mixed[$key]=charset_pmb_normalize($value);
			 else $mixed->$key=charset_pmb_normalize($value);
		}
	}elseif ($charset!="utf-8") {
		$mixed =utf8_decode($mixed);	
	} 
	return $mixed;
}

function object_to_array(&$mixed) {
    if(is_object($mixed)) 
    	$mixed = (array) $mixed;
    if(is_array($mixed)) {
        foreach($mixed as $key => &$val) {
            object_to_array($mixed[$key]);
        }
    } 
}

?>