<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: output_rtf.inc.php,v 1.5 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function _get_header_($output_params) {
	$r="";
	$f_rtf=@fopen("imports/word/".$output_params['RTFTEMPLATE'][0]['value'],"r");
	while (!feof($f_rtf)) {
		$line=fgets($f_rtf,4096);
		if (strpos($line,"!!START!!")===false) {
			$r.=$line;
		} else break;
	}
	fclose($f_rtf);
    return $r;
}

function _get_footer_($output_params) {
	$r="";
	$f_rtf=@fopen("imports/word/".$output_params['RTFTEMPLATE'][0]['value'],"r");
	while (!feof($f_rtf)) {
		$line=fgets($f_rtf,4096);
		if (strpos($line,"!!STOP!!")!==false) {
			break;
		}
	}
	while (!feof($f_rtf)) {
		$line=fgets($f_rtf,4096);
		$r.=$line;
	}
	fclose($f_rtf);
    return $r;
}
