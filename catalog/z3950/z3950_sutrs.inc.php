<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// functions for conversion of SUTRS record
// authors: Marco Vaninetti, Massimo Mancini
// state: experimental ;-)
// +-------------------------------------------------+
// $Id: z3950_sutrs.inc.php,v 1.9 2009-10-01 09:41:59 mbertin Exp $


if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//require_once ("$include_path/parser.inc.php");
require_once ("$include_path/parser.inc.php");
require_once("$class_path/XMLlist.class.php");

$clang = new XMLlist("$include_path/marc_tables/$lang/lang.xml", 0);
$clang->analyser();
$codelang = $clang->table;
while (list($l,$d)=each($codelang)) $codelang[$l]=strtolower($d);

function _item_($param) {
	global $import_type;
	global $i;
	global $param_path;
	global $import_type_l;

	if ($i == $import_type) {
		$param_path = $param['PATH'];
		$import_type_l = $param['NAME'];
	}
	$i ++;
}

//Récupération du nom de l'import
function _import_name_($param) {
	global $import_name;

	$import_name = $param['value'];
}

//Récupération du nombre de notices à traiter par passe
function _n_per_pass_($param) {
	global $n_per_pass;

	$n_per_pass = $param['value'];
}

//Récupération du type d'entrée
function _input_($param) {
	global $input_type;
	global $input_params;

	$input_type = $param['TYPE'];
	$input_params = $param;
}

//Récupération des étapes de conversion
function _step_($param) {
	global $step;

	$step[] = $param;
}

//Récupération du paramètre d'import
function _output_($param) {
	global $output;
	global $output_type;
	global $output_params;

	$output = $param['IMPORTABLE'];
	$output_type = $param['TYPE'];
	$output_params=$param;
}

function convert_notice($notice) {
	global $step;
	global $param_path;
	global $n_errors;
	global $message_convert;
	global $n_current;

			
	$r = texttoxml($notice, $step[0], "0", "1", $param_path);
	if (!$r['VALID']) {
		$n_errors ++;
		$message_convert.= "<b>Notice ". ($n_current)." : </b>".$r['ERROR']."<br />\n";
		$notice = "";
		break;
		} else {
			$notice = $r['DATA'];
			}
	$r = toiso($notice, $step[1], "1","0", $param_path);
	if (!$r['VALID']) {
		$n_errors ++;
		$message_convert.= "<b>Notice ". ($n_current)." : </b>".$r['ERROR']."<br />\n";
		$notice = "";
		break;
	} else {
		$notice = $r['DATA'];
		if($r['WARNING']){
			$n_errors ++;
			$message_convert.= "<b>Notice ". ($n_current)." : </b>".$r['WARNING']."<br />\n";
		}
	}

	return $notice;
}

function sutrs_record($ss,$sutrs_lang) {
	$base_path = "../..";
	//global $class_path;
	global $lang;
	global $include_path;
	global $charset;
	global $campi,$sep,$fun,$codelang;

//	require_once ("$include_path/parser.inc.php");
//	require_once("$class_path/XMLlist.class.php");

	// functions server specific
	require_once("$include_path/sutrs_zserver/$sutrs_lang/sutrs_func.php");

	// localisation (fichier XML) (valeur par défaut)
	$labels = new XMLlist("$include_path/sutrs_zserver/$sutrs_lang/sutrs.xml", 0);
	$labels->analyser();
	$campo = $labels->table;

	//global $fun;
	// resp.functions (fichier XML) (valeur par défaut)
	$funcs = new XMLlist("$include_path/sutrs_zserver/$sutrs_lang/sutrs_authfun.xml", 0);
	$funcs->analyser();
	$fun = $funcs->table;

	
	$notice=from_sutrs($ss,$campo);
	return $notice;
}

?>