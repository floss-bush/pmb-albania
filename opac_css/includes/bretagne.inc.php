<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bretagne.inc.php,v 1.3 2007-08-06 14:35:02 touraine37 Exp $

function search_other_function_filters() {
	global $bretagne_section,$charset;
	if ($bretagne_section=="") $bretagne_section=array();
	$requete="select notices_custom_list_value,notices_custom_list_lib from notices_custom_lists where notices_custom_champ=6";
	$resultat=mysql_query($requete);
	while ($res=mysql_fetch_object($resultat)) {
		$r.="<input type='checkbox' name='bretagne_section[]' value='".$res->notices_custom_list_value."' ";
		$as=array_search($res->notices_custom_list_value,$bretagne_section);
		if (($as!==null)&&($as!==false)) $r.="checked";
		$r.=">&nbsp;".htmlentities($res->notices_custom_list_lib,ENT_QUOTES,$charset)."&nbsp;";
	}
	return $r;
}

function search_other_function_clause(&$clause) {
	global $bretagne_section;
	if ($bretagne_section=="") $bretagne_section=array();
	$section=implode(",",$bretagne_section);
	$r="";
	if ($section) {
		$r.=", notices_custom_values as e0 ".$clause." and e0.notices_custom_origine=notice_id and notices_custom_integer in (".$section.") and notices_custom_champ=6";
	}
	if ($r=="") $r=$clause;
	if ($clause==$r) return false; else {
		$clause=$r;
		return true;
	}
}

function search_other_function_has_values() {
	global $bretagne_section;
	if ($bretagne_section=="") $bretagne_section=array();
	if (count($bretagne_section)) return true; else return false;
}

function search_other_function_rec_history($n) {
	global $bretagne_section;
	$_SESSION["bretagne_section".$n]=$bretagne_section;
}

function search_other_function_get_history($n) {
	global $bretagne_section;
	$bretagne_section=$_SESSION["bretagne_section".$n];
}

function search_other_function_human_query($n) {
	$r="";
	$bretagne_section=$_SESSION["bretagne_section".$n];
	$section=implode(",",$bretagne_section);
	if ($section) {
		$requete="select notices_custom_list_value,notices_custom_list_lib from notices_custom_lists where notices_custom_champ=6 and notices_custom_list_value in ($section)";
		$resultat=mysql_query($requete);
		while ($res=mysql_fetch_object($resultat)) $sect[]=$res->notices_custom_list_lib;
		$r=implode(" ou ",$sect);
		if ($r) $r="section(s) : ".$r;
	}
	return $r;
}

?>