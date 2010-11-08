<?php
function search_other_function_filters() {
	global $heyrieux_public;
	global $charset;
	global $dbh;
	$r.="<select name='heyrieux_public'>";
	$r.="<option value=''>tout public</option>" .
	/*	"<option value='a'>adultes</option>" .
		"<option value='j'>jeunes</option>" .
		"<option value='e'>enfants</option>" .
		"<option value='pl'>premières lectures</option>";*/
	$requete="select sdoc_codage_import from docs_section where section_visible_opac=1 and sdoc_codage_import != '' group by sdoc_codage_import order by sdoc_codage_import";
	$result = mysql_query($requete, $dbh);
	$option_heyrieux_public_libelle="";
	if (mysql_numrows($result)){
		while ($sec = mysql_fetch_object($result)) {
			$selected="";
			if ($heyrieux_public==$sec->sdoc_codage_import) {$selected="selected";}
			switch ($sec->sdoc_codage_import) {
				case "a" : 
						$option_heyrieux_public_libelle="adultes";
						break;
				case "j" :
						$option_heyrieux_public_libelle="jeunes";
						break;
				case "e" :
						$option_heyrieux_public_libelle="enfants";
						break;
				case "pl" :
						$option_heyrieux_public_libelle="premières lectures";
						break;
				default :
						$option_heyrieux_public_libelle=$sec->sdoc_codage_import;
			}					 
			$r.= "<option value='".$sec->sdoc_codage_import."' $selected>$option_heyrieux_public_libelle</option>";
		}
	} 
	$r.="</select>";
	return $r;
}

function search_other_function_clause(&$clause) {
	global $dbh;
	global $heyrieux_public;
	if ($heyrieux_public) {
		$requete="select distinct idsection from docs_section where section_visible_opac=1 and sdoc_codage_import = '".$heyrieux_public."' order by sdoc_codage_import";
		$result = mysql_query($requete, $dbh);
		$public="";
		if (mysql_numrows($result)){
			while ($sect = mysql_fetch_object($result)) {
				if ($public) $public .= ", "; 
				$public .= $sect->idsection;
			}
		}
		$r=",exemplaires ".$clause." and notices.notice_id=exemplaires.expl_notice and expl_section in ($public)";
	} else $r=$clause;
	if ($r=="") $r=$clause;
	if ($clause==$r) return false; else {
		$clause=$r;
		return true;
	}
}

function search_other_function_has_values() {
	global $heyrieux_public;
	if ($heyrieux_public) return true; else return false;
}

function search_other_function_rec_history($n) {
	global $heyrieux_public;
	$_SESSION["heyrieux_public".$n]=$heyrieux_public;
}

function search_other_function_get_history($n) {
	global $heyrieux_public;
	$heyrieux_public=$_SESSION["heyrieux_public".$n];
}

function search_other_function_human_query($n) {
	global $dbh;
	global $heyrieux_public;
	$r="";
	$heyrieux_public=$_SESSION["heyrieux_bibli".$n];
	$heyrieux_public_human_value="";
	if ($heyrieux_public) {
		switch ($heyrieux_public) {
			case "a" : 
					$heyrieux_public_human_value="adultes";
					break;
			case "j" :
					$heyrieux_public_human_value="jeunes";
					break;
			case "e" :
					$heyrieux_public_human_value="enfants";
					break;
			case "pl" :
					$heyrieux_public_human_value="premières lectures";
					break;
			default :
					$heyrieux_public_human_value=$heyrieux_public;
		}					 
		$r="public : ".$heyrieux_public_human_value;
	}
	return $r;
}

function search_other_function_post_values() {
	global $heyrieux_public;
	return "<input type=\"hidden\" name=\"heyrieux_public\" value=\"$heyrieux_public\">\n";
}

?>