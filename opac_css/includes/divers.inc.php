<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: divers.inc.php,v 1.10.2.1 2011-07-21 08:51:35 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// formatdate() : retourne une date formatée comme il faut
function formatdate($date_a_convertir) {
	global $msg;
	global $dbh;
	
	$resultatdate=mysql_query("select date_format('".$date_a_convertir."', '".$msg["format_date_sql"]."') as date_conv ");
	$date_conv=mysql_result($resultatdate,0,0);
	return $date_conv ;
	//return date($msg[1005],strtotime($date_a_convertir));
	}

// extraitdate() : retourne une date formatée comme il faut
function extraitdate($date_a_convertir) {
	global $msg;
	
	$format_local = str_replace ("%","",$msg["format_date"]);
	$format_local = str_replace ("-","",$format_local);
	$format_local = str_replace ("/","",$format_local);
	$format_local = str_replace ("\\","",$format_local);
	$format_local = str_replace (".","",$format_local);
	$format_local = str_replace (" ","",$format_local);
	$format_local = str_replace ($msg["format_date_input_separator"],"",$format_local);
	list($date[substr($format_local,0,1)],$date[substr($format_local,1,1)],$date[substr($format_local,2,1)]) = sscanf($date_a_convertir,$msg["format_date_input"]) ;
	if ($date['Y'] && $date['m'] && $date['d']){
		 //$date_a_convertir = $date['Y']."-".$date['m']."-".$date['d'] ;
		 $date_a_convertir = sprintf("%04d-%02d-%02d",$date['Y'],$date['m'],$date['d']);
	} else {
		$date_a_convertir="";
	}
	return $date_a_convertir ;
}	

function detectFormatDate($date_a_convertir,$compl="01"){
	global $msg;
	if(preg_match(getDatePattern(),$date_a_convertir)){
		$date = extraitdate($date_a_convertir);
	}elseif(preg_match(getDatePattern("short"),$date_a_convertir)){
		$format = str_replace ("%","",$msg["format_date_short"]);
		$format = str_replace ("-","",$format);
		$format = str_replace ("/","",$format);
		$format = str_replace ("\\","",$format);
		$format = str_replace (".","",$format);
		$format = str_replace (" ","",$format);
		$format = str_replace ($msg["format_date_input_separator"],"",$format);
		list($date[substr($format,0,1)],$date[substr($format,1,1)],$date[substr($format,2,1)]) = sscanf($date_a_convertir,$msg["format_date_short_input"]);
		if ($date['Y'] && $date['m']){
		 $date = sprintf("%04d-%02d-%02s",$date['Y'],$date['m'],$compl);		
		}else{
			$date = "0000-00-00";
		}
	}elseif(preg_match(getDatePattern("year"),$date_a_convertir,$matches)){
		$date = $matches[0]."-".$compl."-".$compl;
	}else{
		$date = "0000-00-00";
	}

	return $date;
}

function getDatePattern($format="long"){
	global $msg;
	switch($format){
		case "long" :
			$format_date = str_replace ("%","",$msg["format_date"]);
			break;
		case "short" :
			$format_date = str_replace ("%","",$msg["format_date_short"]);
			break;
		case "year":
			$format_date = "Y"; 
			break;
	}
	$format_date = str_replace ("-"," ",$format_date);
	$format_date = str_replace ("/"," ",$format_date);
	$format_date = str_replace ("\\"," ",$format_date);
	$format_date = str_replace ("."," ",$format_date);	
	$format_date=explode(" ",$format_date);
	$pattern = array();
	for($i=0;$i<count($format_date);$i++){
		switch($format_date[$i]){
			case "m" :
			case "d" :
				$pattern[$i] =  '\d{1,2}';
			break;
			case "Y" :
				$pattern[$i] =  '\d{4}';
			break;
		}
	}	
	return "#".implode($pattern,".")."#";
}

// verif_date permet de vérifier si une date saisie est valide
function verif_date($date) {
	global $msg;
	$mysql_date= extraitdate($date);
	$rqt= "SELECT DATE_ADD('" .$mysql_date. "', INTERVAL 0 DAY)";
	if($result=mysql_query($rqt))
		if($row = mysql_fetch_row($result))	
			if($row[0]){
				return $row[0];
			}
	return false;
}

function compose_date($date) {
	// on recherche à recomposer une date entière valide.
	if(is_numeric($date)) {
		//c'est un année, on rajoute 01/01/ devant
		$date_gen="01/01/".sprintf("%04d",$date);
	} else {
		$param=explode("/",$date);
		if(count($param)== 2) {
			$date_gen="01/".$date;
		} else if(count($param)== 3) {
			$date_gen=$date;
		}
		
	}
	return verif_date($date_gen);	
}
			