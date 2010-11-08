<?php 
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rss_func.inc.php,v 1.11 2009-05-16 10:52:50 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($include_path."/notice.inc.php");

// --------- funtion affichage RSS, reçoit fichier XML
function affiche_rss($id_rss=0) {

$req_rss = "select lien, eformat from notices where notice_id='$id_rss' " ;
$res_rss = mysql_query($req_rss);
$rss = mysql_fetch_object($res_rss);

$rss_lien = $rss->lien;
$rss_lu = explode(' ', $rss->eformat) ;
$rss_time = $rss_lu[1] ;

if ($rss_time=='0' || !$rss_time) return affiche_rss_from_url($rss->lien) ;
	else {
		$req_content = "select if(sysdate()<date_add(rss_last, interval $rss_time minute), rss_content, null) as contenu, if(sysdate()<date_add(rss_last, interval $rss_time minute), rss_content_parse, null) as contenu_parse from rss_content where rss_id='$id_rss' " ;
		$res_content = mysql_query($req_content);
		if ($content = mysql_fetch_object($res_content)) {
			// on a trouvé un truc dans la table
			if ($content->contenu) {
				$etat_cache_rss = 1 ;
			} else {
				// truc trouvé mais périmé
				$etat_cache_rss = 2 ;
			}
		} else {
			// même pas trouvé
			$etat_cache_rss = 0 ; 
		}
		switch ($etat_cache_rss) {
			case 1 :
				if ($rss_lu[3]=='1') majNoticesGlobalIndex($id_rss, 1, $content->contenu_parse);
				return $content->contenu_parse ;
				break ;
			case 2 :
				$fichier = lit_fichier_rss($rss_lien) ;
				$contenu_parse = affiche_rss_from_fichier($fichier);
				$rq = "update rss_content set rss_content='".addslashes($fichier)."', rss_content_parse='".addslashes($contenu_parse)."' where rss_id='$id_rss' ";
				mysql_query($rq);
				if ($rss_lu[3]=='1') majNoticesGlobalIndex($id_rss, 1, $contenu_parse);
				return $contenu_parse ;
				break ;
			case 0 :
				$fichier = lit_fichier_rss($rss_lien) ;
				$contenu_parse = affiche_rss_from_fichier($fichier);
				$rq = "insert into rss_content set rss_id='$id_rss', rss_content='".addslashes($fichier)."', rss_content_parse='".addslashes($contenu_parse)."' ";
				mysql_query($rq);
				if ($rss_lu[3]=='1') majNoticesGlobalIndex($id_rss, 1, $contenu_parse);
				return $contenu_parse ;
				break ;
			}
		}	
}

function lit_fichier_rss($url_fichier) {
	global $opac_curl_available ;
	
	$res="";
	if ($opac_curl_available) {
		$ch = curl_init($url_fichier);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		configurer_proxy_curl($ch);
		$res=curl_exec($ch);
		curl_close($ch);
	} else {
		$fp=fopen($url_fichier,"r");
		if ($fp) {
			while (!feof($fp)) $res.=fread($fp,2048);
			fclose($fp);
		}
	}

	return $res;
}

// --------- funtion affichage RSS, reçoit URL fichier XML
function affiche_rss_from_url($url_fichier="") {

$fp=lit_fichier_rss($url_fichier) ;
if ($fp) {
	$red=true;
	$content=str_replace("&nbsp;"," ",$fp);
	//Parse du fichier
	$param=_parser_text_no_function_($content);
	list($forme,$val)=each($param);
	$param=$val[0];
	for ($j=0; $j<count($param["CHANNEL"]); $j++) {
		$current=$param["CHANNEL"][$j];
		$articles.="<div class='row'>";
		if ($current["IMAGE"][0]) $articles.="<a href='".$current["IMAGE"][0]["LINK"][0]["value"]."' target='_blank'><img src='".$current["IMAGE"][0]["URL"][0]["value"]."' border='0' alt='".$current["IMAGE"][0]["TITLE"][0]["value"]."' title='".$current["IMAGE"][0]["TITLE"][0]["value"]."' align='center'></a>&nbsp;";
		$articles.="<b>".$current["TITLE"][0]["value"]."</b>";
		if (strpos($forme,"RDF")!==false) $current=$param;
		$articles.="<table>";
		$pair=false;
		for ($k=0; $k<count($current["ITEM"]); $k++) {
			if (!$pair) $articles.="<tr valign='top'>";
			$item=$current["ITEM"][$k];
			$articles.="<td width='50%' valign='top'><i><a href='".$item["LINK"][0]["value"]."' target='_blank'>".$item["TITLE"][0]["value"]."</a></i><br />".$item["DESCRIPTION"][0]["value"]."</td>";
			if ($pair) $articles.="</tr>";
			$pair=!$pair;
			}
		if ($pair) $articles.="<td>&nbsp;</td></tr>";
		$articles.="</table>";
		$articles.="</div>";
		}
	}
return $articles;		
}

// --------- funtion affichage RSS, reçoit fichier XML
function affiche_rss_from_fichier($fichier="") {

$content = $fichier ;
	$content=str_replace("&nbsp;"," ",$content);
	//Parse du fichier
	$param=_parser_text_no_function_($content);
	list($forme,$val)=each($param);
	$param=$val[0];
	for ($j=0; $j<count($param["CHANNEL"]); $j++) {
		$current=$param["CHANNEL"][$j];
		$articles.="<div class='row'>";
		if ($current["IMAGE"][0]) $articles.="<a href='".$current["IMAGE"][0]["LINK"][0]["value"]."' target='_blank'><img src='".$current["IMAGE"][0]["URL"][0]["value"]."' border='0' alt='".$current["IMAGE"][0]["TITLE"][0]["value"]."' title='".$current["IMAGE"][0]["TITLE"][0]["value"]."' align='center'></a>&nbsp;";
		$articles.="<b>".$current["TITLE"][0]["value"]."</b>";
		if (strpos($forme,"RDF")!==false) $current=$param;
		$articles.="<table>";
		$pair=false;
		for ($k=0; $k<count($current["ITEM"]); $k++) {
			if (!$pair) $articles.="<tr valign='top'>";
			$item=$current["ITEM"][$k];
			$articles.="<td width='50%' valign='top'><i><a href='".$item["LINK"][0]["value"]."' target='_blank'>".$item["TITLE"][0]["value"]."</a></i><br />".$item["DESCRIPTION"][0]["value"]."</td>";
			if ($pair) $articles.="</tr>";
			$pair=!$pair;
			}
		if ($pair) $articles.="<td>&nbsp;</td></tr>";
		$articles.="</table>";
		$articles.="</div>";
		}
return $articles;		
}

