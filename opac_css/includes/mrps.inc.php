<?php
function search_other_function_filters() {
	global $cnl_bibli;
	global $charset;
	global $dbh;
	$r.="<select name='cnl_bibli'>";
	$r.="<option value=''>tout site</option>";
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
	return $r;
}

function search_other_function_clause(&$clause) {
	global $cnl_bibli;
	if ($cnl_bibli) {
		$r=" ".$clause." and notice_id in (select expl_notice from exemplaires where expl_location='$cnl_bibli' UNION select  bulletin_notice from bulletins join exemplaires on expl_bulletin=bulletin_id  where expl_location='$cnl_bibli' )";
	} else $r=$clause;
	if ($r=="") $r=$clause;
	if ($clause==$r) return false; else {
		$clause=$r;
		return true;
	}
}

function search_other_function_has_values() {
	global $cnl_bibli;
	if ($cnl_bibli) return true; else return false;
}

function search_other_function_rec_history($n) {
	global $cnl_bibli;
	$_SESSION["cnl_bibli".$n]=$cnl_bibli;
}

function search_other_function_get_history($n) {
	global $cnl_bibli;
	$cnl_bibli=$_SESSION["cnl_bibli".$n];
}

function search_other_function_human_query($n) {
	global $dbh;
	global $cnl_bibli;
	$r="";
	$cnl_bibli=$_SESSION["cnl_bibli".$n];
	if ($cnl_bibli) {
		$r="bibliotheque : ";
		$requete="select location_libelle from docs_location where idlocation='".$cnl_bibli."' limit 1";
		$res=mysql_query($requete);
		$r.=@mysql_result($res,0,0);
	}
	return $r;
}

function search_other_function_post_values() {
	global $cnl_bibli;
	return "<input type=\"hidden\" name=\"cnl_bibli\" value=\"$cnl_bibli\">\n";
}

?>