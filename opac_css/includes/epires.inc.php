<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: epires.inc.php,v 1.3 2007-03-10 10:05:50 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function search_other_function_filters() {
	global $typ_notice,$charset,$annee_parution;
	$r="";
	$r.="Année de parution <input type='text' size='5' name='annee_parution' value='".htmlentities($annee_parution,ENT_QUOTES,$charset)."'/>&nbsp;Restreindre à <input type='checkbox' name=\"typ_notice[a]\" value='1' ".($typ_notice['a']?"checked":"")."/>&nbsp;Articles de revues&nbsp;<input type='checkbox' name=\"typ_notice[m]\" value='1' ".($typ_notice['m']?"checked":"")."/>&nbsp;Tout sauf revues";
	return $r;
}

function search_other_function_clause(&$clause) {
	global $typ_notice,$annee_parution;
	reset($typ_notice);
	$t_n=array();
	while (list($key,$val)=each($typ_notice)) {
		$t_n[]=$key;
	}
	$t_n=implode("','",$t_n);
	if ($t_n) {
		$t_n="'".$t_n."'";
		$r=$clause." and niveau_biblio in (".$t_n.")";
	}
	if ($annee_parution) {
		if ($r=="") $r=$clause;
		$r.=" and year like '%".$annee_parution."%'";
	}
	if ($r=="") $r=$clause;
	if ($clause==$r) return false; else {
		$clause=$r;
		return true;
	}
}

function search_other_function_has_values() {
	global $typ_notice, $annee_parution;
	if ((count($typ_notice))||($annee_parution)) return true; else return false;
}

function search_other_function_rec_history($n) {
	global $typ_notice,$annee_parution;
	$_SESSION["typ_notice".$n]=$typ_notice;
	$_SESSION["annee_parution".$n]=$annee_parution;
}

function search_other_function_get_history($n) {
	global $typ_notice,$annee_parution;
	$typ_notice=$_SESSION["typ_notice".$n];
	$annee_parution=$_SESSION["annee_parution".$n];
}

function search_other_function_human_query($n) {
	$r="";
	$notices_t=array("m"=>"Monographies","s"=>"Périodiques","a"=>"Articles");
	$typ_notice=$_SESSION["typ_notice".$n];
	$annee_parution=$_SESSION["annee_parution".$n];
	if (count($typ_notice)) {
		$r.="pour les types de notices ";
		reset($typ_notice);
		$t_l=array();
		while (list($key,$val)=each($typ_notice)) {
			$t_l[]=$notices_t[$key];
		}
		$r.=implode(", ",$t_l);
	}
	if ($annee_parution) {
		if ($r) $r.=" ";
		$r.="parus en ".$annee_parution;
	}
	return $r;
}

?>