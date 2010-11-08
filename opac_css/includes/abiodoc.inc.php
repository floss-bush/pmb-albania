<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abiodoc.inc.php,v 1.2 2010-09-14 14:08:04 dbellamy Exp $

function search_other_function_filters() {
	global $abiodoc_app,$charset;

	$requete="select notices_custom_list_value,notices_custom_list_lib from notices_custom_lists where notices_custom_champ='27' order by notices_custom_list_lib";
	$resultat=mysql_query($requete);

	$r="<select name='abiodoc_app'>" ;
	$r.="<option value='' ";
	if($abiodoc_app=="") $r.="selected=\"selected\" ";
	$r.=">Tous les partenaires</option>";
	if (mysql_numrows($resultat)) {
		while (($app = mysql_fetch_object($resultat))) {
			$selected="";
			if ($app->notices_custom_list_value==$abiodoc_app) {
				$selected="selected=\"selected\"";
			} else {
				$selected='';
			}
			$r.= "<option value='$app->notices_custom_list_value' $selected>".$app->notices_custom_list_lib."</option>";
		}
	}
	$r.="</select>";	
	return $r;
}

function search_other_function_clause(&$clause) {
	global $abiodoc_app;
	$r="";
	if ($abiodoc_app) {
		$r.=", notices_custom_values as e0 ".$clause." and e0.notices_custom_origine=notice_id and e0.notices_custom_integer='".$abiodoc_app."' and e0.notices_custom_champ='27' ";
	}
	if ($r=="") $r=$clause;
	if ($clause==$r) return false; else {
		$clause=$r;
		return true;
	}
}

function search_other_function_has_values() {
	global $abiodoc_app;
	if ($abiodoc_app) return true; else return false;
}


function search_other_function_rec_history($n) {
	global $abiodoc_app;
	$_SESSION["abiodoc_app".$n]=$abiodoc_app;
}

function search_other_function_get_history($n) {
	global $abiodoc_app;
	$abiodoc_app=$_SESSION["abiodoc_app".$n];
}

function search_other_function_human_query($n) {
	$r="";
	$abiodoc_app=$_SESSION["abiodoc_app".$n];
	if ($abiodoc_app) {
		$app="";
		$requete="select notices_custom_list_lib from notices_custom_lists where notices_custom_champ='27' and notices_custom_list_value='".$abiodoc_app."' limit 1 ";
		$resultat=mysql_query($requete);
		if (mysql_numrows($resultat)) {
			$res=mysql_fetch_object($resultat);
			$app=$res->notices_custom_list_lib;
		}
		if ($app) $r="appartenance : ".$app;
	}
	return $r;
}


function search_other_function_post_values() {
	global $abiodoc_app;
	return "<input type=\"hidden\" name=\"abiodoc_app\" value=\"$abiodoc_app\">\n";
}


?>