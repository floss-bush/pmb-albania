<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: genes.inc.php,v 1.9 2011-01-17 10:42:35 mhalm Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function search_other_function_filters() {
	global $typ_notice, $charset, $annee_parution, $cnl_bibli, $dbh;
	
	$r.="<select name='cnl_bibli'>";
	$r.="<option value=''>tous les sites</option>";
	$requete="select location_libelle,idlocation from docs_location where location_visible_opac=1";
	$result = mysql_query($requete, $dbh);
	if (mysql_numrows($result)){
		while ($loc = mysql_fetch_object($result)) {
			$selected="";
			if ($cnl_bibli==$loc->idlocation) {$selected="selected";}
			$r.= "<option value='$loc->idlocation' $selected>$loc->location_libelle</option>";
		}
	}
	$r.="</select>";

	// Année de parution : fonctionnel mais désactivé pour l'instant
	//$r.="Année de parution <input type='text' size='5' name='annee_parution' value='".htmlentities($annee_parution,ENT_QUOTES,$charset)."'/>";
	$r.="<br/>Restreindre à <input type='checkbox' name=\"typ_notice[a]\" value='1' ".($typ_notice['a']?"checked":"")."/>&nbsp;Articles de revues<input type='checkbox' name=\"typ_notice[s]\" value='1' ".($typ_notice['s']?"checked":"")."/>&nbsp;Revues&nbsp;<input type='checkbox' name=\"typ_notice[m]\" value='1' ".($typ_notice['m']?"checked":"")."/>&nbsp;Tout sauf revues";
	return $r;
}

function search_other_function_clause(&$clause) {
	global $typ_notice,$annee_parution;
	global $cnl_bibli;
	reset($typ_notice);
	if ($cnl_bibli) {
		$r=",exemplaires ".$clause." and notices.notice_id=exemplaires.expl_notice and expl_location=$cnl_bibli";
	} else $r=$clause;
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
	global $cnl_bibli;
	if (((count($typ_notice))||($annee_parution))||($cnl_bibli)) return true; else return false;
}

function search_other_function_rec_history($n) {
	global $typ_notice,$annee_parution;
	global $cnl_bibli;
	$_SESSION["cnl_bibli".$n]=$cnl_bibli;
	$_SESSION["typ_notice".$n]=$typ_notice;
	$_SESSION["annee_parution".$n]=$annee_parution;
}

function search_other_function_get_history($n) {
	global $typ_notice,$annee_parution;
	global $cnl_bibli;
	$cnl_bibli=$_SESSION["cnl_bibli".$n];
	$typ_notice=$_SESSION["typ_notice".$n];
	$annee_parution=$_SESSION["annee_parution".$n];
}

function search_other_function_human_query($n) {
	global $dbh;
	global $cnl_bibli;
	$r="";
	$cnl_bibli=$_SESSION["cnl_bibli".$n];
	if ($cnl_bibli) {
		$r="bibliotheque : ";
		$requete="select location_libelle from docs_location where id_location='".$cnl_bibli."' limit 1";
		$res=mysql_query($requete);
		$r.=@mysql_result($res,0,0);
		$r=" ";
	}
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

function search_other_function_post_values() {
	global $cnl_bibli;
	return "<input type=\"hidden\" name=\"cnl_bibli\" value=\"$cnl_bibli\">\n";
}

?>