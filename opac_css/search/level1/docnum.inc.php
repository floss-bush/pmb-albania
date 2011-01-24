<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: docnum.inc.php,v 1.7 2010-10-12 15:49:31 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// premier niveau de recherche OPAC sur les documents numériques

// inclusion classe pour affichage notices (level 1)
require_once($base_path.'/includes/templates/notice.tpl.php');
require_once($base_path.'/classes/notice.class.php');

if ($opac_search_other_function) require_once($include_path."/".$opac_search_other_function);

if ($typdoc) $restrict="typdoc='".$typdoc."'"; else $restrict="";

//droits d'acces emprunteur/notice
$acces_j='';
if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_2= $ac->setDomain(2);
	$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
} 

// on regarde comment la saisie utilisateur se presente
$aq=new analyse_query(stripslashes($user_query));

if ($acces_j) {
	$members=$aq->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_notice");
	$clause="where ".$members["where"];	 
	
	$members_bull=$aq->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_bulletin");
	$clause_bull="where ".$members_bull["where"];
	$statut_j='';
} else {
	$members=$aq->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_notice" ," explnum_notice=notice_id and statut=id_notice_statut and (((notice_visible_opac=1 and notice_visible_opac_abon=0) and (explnum_visible_opac=1 and explnum_visible_opac_abon=0)) ".($_SESSION["user_code"]?" or ((notice_visible_opac_abon=1 and notice_visible_opac=1) and (explnum_visible_opac=1 and explnum_visible_opac_abon=1)) or ((notice_visible_opac_abon=0 and notice_visible_opac=1) and (explnum_visible_opac=1 and explnum_visible_opac_abon=1))":"").")");
	$clause="where ".$members["where"]." and (".$members["restrict"].")";
	
	$members_bull=$aq->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_bulletin" ,"explnum_bulletin=bulletin_id and bulletin_notice=notice_id and statut=id_notice_statut and (((notice_visible_opac=1 and notice_visible_opac_abon=0) and (explnum_visible_opac=1 and explnum_visible_opac_abon=0)) ".($_SESSION["user_code"]?" or ((notice_visible_opac_abon=1 and notice_visible_opac=1) and (explnum_visible_opac=1 and explnum_visible_opac_abon=1)) or ((notice_visible_opac_abon=0 and notice_visible_opac=1) and (explnum_visible_opac=1 and explnum_visible_opac_abon=1))":"").")");
	$clause_bull="where ".$members_bull["where"]." and (".$members_bull["restrict"].")";
	
	$statut_j=',notice_statut';
}

if ($opac_search_other_function) {
	search_other_function_clause($clause);
	search_other_function_clause($clause_bull);
}

$search_terms = $aq->get_positive_terms($aq->tree);
//On enlève le dernier terme car il s'agit de la recherche booléenne complète
unset($search_terms[count($search_terms)-1]);

$pert=$members["select"]." as pert";
$tri="order by pert desc, index_serie, tnvol, index_sew";

if ($restrict) {
	$clause.=" and ".$restrict;
	$clause_bull.=" and ".$restrict;
}

if($clause) {
	// instanciation de la nouvelle requête 
	$q_docnum_noti = "select explnum_id  from explnum, notices $statut_j $acces_j $clause "; 
	$q_docnum_bull = "select explnum_id  from bulletins, explnum, notices $statut_j $acces_j $clause_bull ";
	$q_docnum = "select count(explnum_id)  from ( $q_docnum_noti UNION $q_docnum_bull) as uni";
	$docnum = mysql_query($q_docnum, $dbh);
	$nb_result_docnum = mysql_result($docnum, 0, 0); 

	//Enregistrement des stats
	if($pmb_logs_activate){
		global $nb_results_tab;
		$nb_results_tab['docnum'] = $nb_result_docnum;
	}
	
	
	$req_typdoc_noti="select distinct typdoc from notices $statut_j $acces_j $clause group by typdoc"; 
	$req_typdoc_bull = "select distinct typdoc from bulletins, explnum,notices $statut_j $acces_j $clause_bull group by typdoc";  
	$req_typdoc = "($req_typdoc_noti) UNION ($req_typdoc_bull)";
	$res_typdoc = mysql_query($req_typdoc, $dbh);	
	$t_typdoc=array();	
	while (($tpd=mysql_fetch_object($res_typdoc))) {
		$t_typdoc[]=$tpd->typdoc;
	}
	$l_typdoc=implode(",",$t_typdoc);
	if ($nb_result_docnum) {
		// tout bon, y'a du résultat, on lance le pataquès d'affichage
		// (affichage sur une ligne cliquable, maybe...
		print "<strong>$msg[docnum]</strong> ".$nb_result_docnum." $msg[results] ";
		// $found = mysql_query("select * from notices $clause $tri LIMIT $opac_search_results_first_level", $dbh);
		// si il y a d'autres résultats, je met le lien 'plus de résultats'
		// Le lien validant le formulaire est inséré avant le formulaire, cela évite les blancs à l'écran
		print "<a href=\"javascript:document.forms['search_docnum'].submit()\">$msg[suite]&nbsp;<img src='./images/search.gif' border='0' align='absmiddle'/></a>";
		$form = "<div style=search_result><form name=\"search_docnum\" action=\"./index.php?lvl=more_results\" method=\"post\">";
		$form .= "<input type=\"hidden\" name=\"user_query\" value=\"".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."\">\n";
		if (function_exists("search_other_function_post_values")){ $form .=search_other_function_post_values(); }
		$form .= "<input type=\"hidden\" name=\"mode\" value=\"docnum\">\n";
		$form .= "<input type=\"hidden\" name=\"count\" value=\"".$nb_result_docnum."\">\n";
		$form .= "<input type=\"hidden\" name=\"clause\" value=\"".htmlentities($clause,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"clause_bull\" value=\"".htmlentities($clause_bull,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"pert\" value=\"".htmlentities($pert,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"l_typdoc\" value=\"".htmlentities($l_typdoc,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"tri\" value=\"".htmlentities($tri,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"search_terms\" value=\"".htmlentities(serialize($search_terms),ENT_QUOTES,$charset)."\"></form></div>\n";
		
		print $form;
	}
}

?>