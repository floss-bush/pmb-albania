<?php 
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: to_rtf.inc.php,v 1.3 2007-03-10 10:05:51 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function convert_middle($notice, $s, $islast, $isfirst, $param_path) {
	global $rtf_pattern;

	if ($rtf_pattern == "") {
		$f_rtf = fopen("admin/convert/imports/$param_path/".$s['RTFTEMPLATE'][0]['value'], "rt");
		if (!$f_rtf) die( "pb d'ouverture: "."admin/convert/imports/$param_path/".$s['RTFTEMPLATE'][0]['value'] ) ;
		
		while (!feof($f_rtf)) {
			$line = fgets($f_rtf, 4096);
			if ($line === false) die( "pb de lecture: "."admin/convert/imports/$param_path/".$s['RTFTEMPLATE'][0]['value'] ) ;
			if (strpos($line, "!!START!!") !== false) {
				break;
			}
		}
		//Lecture du pattern
		while (!feof($f_rtf)) {
			$line = fgets($f_rtf, 4096);
			if (strpos($line, "!!STOP!!") === false) {
				$rtf_pattern.= $line;
			} else
				break;
		}
		fclose($f_rtf);
	}
	$t_notice = explode(";", $notice);
	$r_ = str_replace("!!TITLE!!", $t_notice[0], $rtf_pattern);
	$r_ = str_replace("!!AUTHOR!!", $t_notice[1], $r_);
	$r['VALID'] = true;
	$r['ERROR'] = "";
	$r['DATA'] = $r_;
	return $r;
}
