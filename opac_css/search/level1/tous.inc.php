<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: tous.inc.php,v 1.37 2010-08-11 10:08:23 ngantier Exp $
// premier niveau de recherche OPAC sur tous

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusion classe pour affichage tous (level 1)
require_once($base_path.'/includes/templates/tous.tpl.php');
require_once($base_path.'/classes/notice.class.php');
require_once($base_path.'/includes/notice_affichage.inc.php');

if ($opac_search_other_function) require_once($include_path."/".$opac_search_other_function);

//droits d'acces emprunteur/notice
$acces_j='';
if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_2= $ac->setDomain(2);
	$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
} 

// on regarde comment la saisie utilisateur se présente
$aq=new analyse_query(stripslashes($user_query),0,0,1,1);
$aq_empty=new analyse_query(stripslashes($user_query));
$aq1=new analyse_query(stripslashes($user_query));
if($user_query=="*")$opac_indexation_docnum_allfields=0;
$clause="";

if ($acces_j) {
	$members=$aq->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice", "num_notice = notice_id");
	$members1=$aq1->get_query_members("notices","index_wew","index_sew","notice_id", "num_notice = notice_id");
	$statut_j='';
} else {
	$members=$aq->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice","num_notice = notice_id and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")");
	$members1=$aq1->get_query_members("notices","index_wew","index_sew","notice_id","num_notice = notice_id and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")");
	$statut_j=',notice_statut';
}
$members_empty=$aq_empty->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice");
if (($members_empty["where"]!="()")&&($opac_default_operator)) {
	$where="(".$members["where"].") or (".$members_empty["where"].")";
} else {
	$where=$members["where"];
}

$search_terms = $aq->get_positive_terms($aq->tree);
//On enlève le dernier terme car il s'agit de la recherche booléenne complète
unset($search_terms[count($search_terms)-1]);

//Si on recherche aussi dans les docs numériques
if($opac_indexation_docnum_allfields){
	
	if($acces_j){
		$members_num_noti = $aq1->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_notice");
		$restrict_noti="";
		$members_num_bull = $aq1->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_bulletin");
		$restrict_bull="";
	} else {
		$members_num_noti = $aq1->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_notice"," statut=id_notice_statut and (((notice_visible_opac=1 and notice_visible_opac_abon=0) ) "
					.($_SESSION["user_code"]?" or ((notice_visible_opac_abon=1 and notice_visible_opac=1) ) or ((notice_visible_opac_abon=0 and notice_visible_opac=1) )":"").")");
		$restrict_noti=" and (".$members_num_noti["restrict"].")";
		$members_num_bull = $aq1->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_bulletin",
					" bulletin_notice=notice_id and statut=id_notice_statut and (((notice_visible_opac=1 and notice_visible_opac_abon=0) ) "
					.($_SESSION["user_code"]?" or ((notice_visible_opac_abon=1 and notice_visible_opac=1)) or ((notice_visible_opac_abon=0 and notice_visible_opac=1) )":"").")");
		$restrict_bull=" and (".$members_num_bull["restrict"].")";	
	}	
	if ($typdoc) {
		$filtre=" and typdoc='".$typdoc."' ";
	}
	$join = "(
	select tc.notice_id, sum(tc.pert) as pert, tc.typdoc from (
	(
	select notice_id, ".$members["select"]."+".$members1["select"]." as pert,typdoc 
	from notices left join notices_global_index on num_notice=notice_id $statut_j $acces_j 
	where ".$members["where"]." $restrict_noti $filtre
	) 
	union 
	(
	select notice_id, ".$members_num_noti["select"]." as pert,typdoc 
	from notices left join explnum on explnum_notice=notice_id $statut_j $acces_j 
	where  ".$members_num_noti["where"]." $restrict_noti $filtre
	)
	union 
	(
	select if(num_notice,num_notice,bulletin_notice) as notice_id, ".$members_num_bull["select"]." as pert,typdoc 
	from explnum join bulletins on explnum_bulletin=bulletin_id ,notices $statut_j $acces_j 
	where bulletin_notice=notice_id and ".$members_num_bull["where"]." $restrict_bull $filtre
	)	
	)as tc group by notice_id
	) as uni";
	
}

$clause="where (($where)) ";
if ($members["restrict"]) {
	$clause.= "and (".$members["restrict"].")";
}
if ($typdoc) {
	$clause.=" and typdoc='".$typdoc."' ";
}
$pert=$members["select"];
$pert1=$members1["select"];
$pert=$pert."+".$pert1."*".$opac_title_ponderation." as pert";

if ($opac_search_other_function) search_other_function_clause($clause);
$tri = "ORDER BY pert DESC, index_infos_global ";

if($clause) {
	if ($restrict) $clause.=" and ".$restrict;
	// instanciation de la nouvelle requête
	if(!$opac_indexation_docnum_allfields)
		$q_titres = "select count(distinct num_notice) from notices_global_index, notices $statut_j $acces_j $clause ";
	else $q_titres = "select count(distinct(notice_id)) from  $join ";

	$titres = mysql_query($q_titres, $dbh);
	$nb_result = mysql_result($titres, 0, 0);
	
	//Enregistrement des stats
	if($pmb_logs_activate){
		global $nb_results_tab;
		$nb_results_tab['tous'] = $nb_result;
	}
	if(!$opac_indexation_docnum_allfields){
		$req_typdoc="select distinct typdoc from notices_global_index, notices $statut_j $acces_j $clause ";
		if($opac_visionneuse_allow)
			$req_typdoc="select distinct typdoc, count(explnum_id) as nbexplnum from notices_global_index, notices left join explnum on explnum_notice=notice_id and explnum_mimetype in ($opac_photo_filtre_mimetype) $statut_j $acces_j $clause group by typdoc";
	}else {
		$req_typdoc ="select distinct typdoc from $join"; 
		if($opac_visionneuse_allow)
			$req_typdoc ="select distinct typdoc, count(explnum_id) as nbexplnum from $join left join explnum on notice_id=explnum_notice and explnum_mimetype in ($opac_photo_filtre_mimetype) group by typdoc";
	}
	$res_typdoc = mysql_query($req_typdoc, $dbh);		
	$t_typdoc=array();	
	while ($tpd=mysql_fetch_object($res_typdoc)) {
		$t_typdoc[]=$tpd->typdoc;
		if($opac_visionneuse_allow)
			$nbexplnum_to_photo += $tpd->nbexplnum;		
	}
	$l_typdoc=implode(",",$t_typdoc);
	
	if ($nb_result) {
		$libelle=($opac_indexation_docnum_allfields ? " [$msg[docnum_search_with]] " : '');
	  if($opac_show_results_first_page) {
		// tout bon, y'a du résultat, on lance le pataquès d'affichage
		if(!$opac_indexation_docnum_allfields)
			$q_titres_notices = "select distinct notice_id, $pert from notices_global_index, notices $statut_j $acces_j $clause order by pert desc, index_serie, tnvol, index_sew limit $opac_nb_results_first_page ";
		else $q_titres_notices = "select distinct uni.notice_id, pert from  $join join notices n on n.notice_id=uni.notice_id order by pert desc, index_serie, tnvol, index_sew limit $opac_nb_results_first_page";
		$titres_notices = mysql_query($q_titres_notices, $dbh);
		
		if($nb_result > $opac_nb_results_first_page) {
	  		print "<strong>".$msg['tous']."</strong> ".$opac_nb_results_first_page." ".$msg['notice_premiere']." ".$nb_result." ".$msg['results']." ";
	  		print "<a href=\"javascript:document.forms['search_tous'].submit()\">".$msg['notice_toute']."&nbsp;<img src=./images/search.gif border='0' align='absmiddle'/></a><br />";
	  	} else {
	  		print "<strong>".$msg['tous'].$libelle."</strong> ".$nb_result." ".$msg['results']." ";	
	  		print "<a href=\"javascript:document.forms['search_tous'].submit()\"> ".$msg['suite']."&nbsp;<img src=./images/search.gif border='0' align='absmiddle'/></a><br />";
	  	}  	
	  	// Récupération des titres de notices
	  	print "<div id='res_first_page'>\n";
		if ($opac_notices_depliable) print $begin_result_liste;
		while ($res_notices = mysql_fetch_object($titres_notices)) {
			print pmb_bidi(aff_notice($res_notices->notice_id));
		}
		print "</div>\n";
	} else {
	  	print "<strong>".$msg['tous'].$libelle."</strong> ".$nb_result." ".$msg['results']."";
	  	print "<a href=\"javascript:document.forms['search_tous'].submit()\"> ".$msg['suite']."&nbsp;<img src=./images/search.gif border='0' align='absmiddle'/></a>";
	}	
  	
  	$form = "<div style=search_result><form name=\"search_tous\" action=\"./index.php?lvl=more_results\" method=\"post\">\n";
	if (function_exists("search_other_function_post_values")){ $form .=search_other_function_post_values(); }
  	$form .= "<input type=\"hidden\" name=\"user_query\" value=\"".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."\">\n";
  	$form .= "<input type=\"hidden\" name=\"mode\" value=\"tous\">\n";
  	$form .= "<input type=\"hidden\" name=\"count\" value=\"".$nb_result."\">\n";
  	$form .= "<input type=\"hidden\" name=\"clause\" value=\"".htmlentities($clause,ENT_QUOTES,$charset)."\">\n";
  	if($opac_indexation_docnum_allfields) 
  		$form .= "<input type=\"hidden\" name=\"join\" value=\"".htmlentities($join,ENT_QUOTES,$charset)."\">\n";
  	$form .= "<input type=\"hidden\" name=\"pert\" value=\"".htmlentities($pert,ENT_QUOTES,$charset)."\">\n";
  	$form .= "<input type=\"hidden\" name=\"l_typdoc\" value=\"".htmlentities($l_typdoc,ENT_QUOTES,$charset)."\">\n";
  	$form .= "<input type=\"hidden\" name=\"tri\" value=\"".htmlentities($tri,ENT_QUOTES,$charset)."\">\n";
  	$form .= "<input type=\"hidden\" name=\"nbexplnum_to_photo\" value=\"".$nbexplnum_to_photo."\">\n</form></div>\n";
  	print $form;
  }
}