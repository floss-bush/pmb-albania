<?php
function search_other_function_filters() {
	global $section_public,$typ_notice;
	global $charset;
	global $dbh;
	
	$r.="<select name='section_public'>";
	$r.="<option value=''>tout public</option>" .
	/*	"<option value='a'>adultes</option>" .
		"<option value='j'>jeunes</option>" .
		"<option value='e'>enfants</option>" .
		"<option value='pl'>premières lectures</option>";*/
	$requete="select section_libelle, sdoc_codage_import from docs_section where section_visible_opac=1 and sdoc_codage_import != '' group by sdoc_codage_import order by sdoc_codage_import";
	$result = mysql_query($requete, $dbh);
	$option_section_public_libelle="";
	if (mysql_numrows($result)){
		while ($sec = mysql_fetch_object($result)) {
			$selected="";
			if ($section_public==$sec->sdoc_codage_import) {$selected="selected";}
			switch ($sec->sdoc_codage_import) {
				case "a" : 
						$option_section_public_libelle="adultes";
						break;
				case "j" :
						$option_section_public_libelle="jeunes";
						break;
				case "e" :
						$option_section_public_libelle="enfants";
						break;
				case "pl" :
						$option_section_public_libelle="premières lectures";
						break;
				default :
						$option_section_public_libelle=$sec->section_libelle;
			}					 
			$r.= "<option value='".$sec->sdoc_codage_import."' $selected>$option_section_public_libelle</option>";
		}
	} 
	$r.="</select>";
	$r.="Restreindre à <input type='checkbox' name=\"typ_notice[a]\" value='1' ".($typ_notice['a']?"checked":"")."/>&nbsp;Articles de revues&nbsp;<input type='checkbox' name=\"typ_notice[m]\" value='1' ".($typ_notice['m']?"checked":"")."/>&nbsp;Tout sauf revues";
	return $r;
}

function search_other_function_clause(&$clause) {
	global $dbh;
	global $section_public;
	global $typ_notice;
	
	reset($typ_notice);
	$t_n=array();
	while (list($key,$val)=each($typ_notice)) {
		$t_n[]=$key;
	}
	$t_n=implode("','",$t_n);
	
	if ($section_public || $t_n) {
		if ($section_public) {
			$requete="select distinct idsection from docs_section where section_visible_opac=1 and sdoc_codage_import = '".$section_public."' order by sdoc_codage_import";
			$result = mysql_query($requete, $dbh);
			$public="";
			if (mysql_numrows($result)){
				while ($sect = mysql_fetch_object($result)) {
					if ($public) $public .= ", "; 
					$public .= $sect->idsection;
				}
			}
			$r=",exemplaires ".$clause." and notices.notice_id=exemplaires.expl_notice and expl_section in ($public)";
		}
		if ($t_n && !$section_public) {
			$t_n="'".$t_n."'";
			$r=$clause." and niveau_biblio in (".$t_n.")";
		} else if ($t_n && $section_public) {
			$t_n="'".$t_n."'";
			$r.=" and niveau_biblio in (".$t_n.")";
		}
	} else $r=$clause;
	
	if ($r=="") $r=$clause;
	if ($clause==$r) return false; else {
		$clause=$r;
		return true;
	}
}

function search_other_function_has_values() {
	global $section_public,$typ_notice;
	if ($section_public && count($typ_notice)) return true; else return false;
}

function search_other_function_rec_history($n) {
	global $section_public,$typ_notice;
	$_SESSION["section_public".$n]=$section_public;
	$_SESSION["typ_notice".$n]=$typ_notice;
}

function search_other_function_get_history($n) {
	global $section_public,$typ_notice;
	$section_public=$_SESSION["section_public".$n];
	$typ_notice=$_SESSION["typ_notice".$n];
}

function search_other_function_human_query($n) {
	global $dbh;
	global $section_public;
	$r="";
	$section_public=$_SESSION["section_bibli".$n];
	$section_public_human_value="";
	if ($section_public) {
		switch ($section_public) {
			case "a" : 
					$section_public_human_value="adultes";
					break;
			case "j" :
					$section_public_human_value="jeunes";
					break;
			case "e" :
					$section_public_human_value="enfants";
					break;
			case "pl" :
					$section_public_human_value="premières lectures";
					break;
			default :
					$section_public_human_value=$section_public;
		}					 
		$r="public : ".$section_public_human_value;
	}
	
	$notices_t=array("m"=>"Monographies","s"=>"Périodiques","a"=>"Articles");
	$typ_notice=$_SESSION["typ_notice".$n];
	if (count($typ_notice)) {
		$r.="pour les types de notices ";
		reset($typ_notice);
		$t_l=array();
		while (list($key,$val)=each($typ_notice)) {
			$t_l[]=$notices_t[$key];
		}
		$r.=implode(", ",$t_l);
	}
	
	return $r;
}

function search_other_function_post_values() {
	global $section_public;
	return "<input type=\"hidden\" name=\"section_public\" value=\"$section_public\">\n";
}

?>