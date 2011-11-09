<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_enrichment.inc.php,v 1.1.2.1 2011-06-24 08:16:03 arenou Exp $

require_once($class_path."/enrichment.class.php");
$return = array(
	'state' => 0,
	'notice_id' => $id,
	'result' => array(),
	'error' => ""
	
);

if(!$id){
	$return['error'] = "no input";
}else{
	$rqt= "select niveau_biblio, typdoc from notices where notice_id='".$id."'";
	$res = mysql_query($rqt);
	if(mysql_num_rows($res)){
		$r = mysql_fetch_object($res);
		$enrichment = new enrichment($r->niveau_biblio, $r->typdoc);
		switch($action){
			case "gettype":
				$typeofenrichment = $enrichment->getTypeOfEnrichment($id);
				$return["result"] = $typeofenrichment;
				break;
			default :
				if($enrichPage)	$enhance = $enrichment->getEnrichment($id,$type,$enrichPage);
				else $enhance = $enrichment->getEnrichment($id,$type);
				$return["result"] = $enhance;
				break;
		}
		$return["state"] = 1;
	}
}

//On renvoie du JSON dans le charset de PMB...
if(!$debug){
header("Content-Type:application/json; charset=$charset");
$return = charset_pmb_normalize($return);
print json_encode($return);
}

//function json_pmb_encode($mixed,$lvl=0){
//	$json ="";
//	foreach ($mixed as $key => $value){
//		if($json!="")$json.=",";
//		
//		if(!is_int($key))$json.= "\"$key\":";
//		if(is_array($value) || is_object($value)){
//			$json.=json_pmb_encode($value,$lvl++);
//		}else{
//			$json.="\"".$value."\"";
//		} 
//	}
//	if(is_array($mixed) && is_int($key)) $json = "[".$json."]";
//	else $json = "{".$json."}";
//	return $json;
//}

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
		$mixed =utf8_encode($mixed);	
	} 
	return $mixed;
}
?>