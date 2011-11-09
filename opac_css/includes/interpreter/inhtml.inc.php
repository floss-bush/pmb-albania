<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: inhtml.inc.php,v 1.1.2.2 2011-06-27 15:56:04 gueluneau Exp $

require_once ($include_path . "/misc.inc.php");

$func_format['if_logged']= aff_if_logged;
$func_format['if_logged_lang']= aff_if_logged_lang;
$func_format['message_lang']= aff_message_lang;
$func_format['if_param']= aff_if_param;

$var_format = array();

function aff_if_param($param) {
	//Nom de la variable a tester, valeur, si =, si <>
	$varname=$param[0];
	global $$varname;
	if ($$varname==$param[1]) $ret=$param[2]; else $ret=$param[3];
	return $ret;
}

function aff_if_logged($param) {
	if ($_SESSION['id_empr_session']) {
		$ret = $param[0];
	}else {
		if($param[1]) $ret = $param[1];
		else $ret ="";
	}
	return $ret;
}

function aff_if_logged_lang($param) {
	global $lang;
	if ($lang==$param[2]) {
		if ($_SESSION['id_empr_session']) {
			$ret = $param[0];
		}else {
			if($param[1]) $ret = $param[1];
			else $ret ="";
		}
	} else $ret="";
	return $ret;
}

function aff_message_lang($param) {
	global $lang;
	if ($lang==$param[1])
	return $param[0]; else return "";
}
?>