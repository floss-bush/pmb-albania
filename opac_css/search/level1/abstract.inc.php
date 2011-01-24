<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abstract.inc.php,v 1.33 2010-11-17 17:15:24 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// premier niveau de recherche OPAC sur résumé/notes

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
$aq=new analyse_query(stripslashes($user_query));
if ($acces_j) {
	$members=$aq->get_query_members("notices","concat(n_gen,' ',n_contenu,' ',n_resume)","concat(index_n_gen,index_n_contenu,index_n_resume)","notice_id");
	$clause="where ".$members["where"]." and (trim(concat(index_n_gen,index_n_contenu,index_n_resume))!='' or concat(n_gen,' ',n_contenu,' ',n_resume)!='')";
	$statut_j='';
} else {
	$members=$aq->get_query_members("notices","concat(n_gen,' ',n_contenu,' ',n_resume)","concat(index_n_gen,index_n_contenu,index_n_resume)","notice_id","statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")");
	$clause="where ".$members["where"]." and (trim(concat(index_n_gen,index_n_contenu,index_n_resume))!='' or concat(n_gen,' ',n_contenu,' ',n_resume)!='') and (".$members["restrict"].")";
	$statut_j=',notice_statut';
}

$search_terms = $aq->get_positive_terms($aq->tree);
//On enlève le dernier terme car il s'agit de la recherche booléenne complète
unset($search_terms[count($search_terms)-1]);

if ($opac_search_other_function) search_other_function_clause($clause);

if ($typdoc) $clause.=" and typdoc='".$typdoc."' ";

$tri = " ORDER BY pert DESC, index_serie, tnvol, index_sew ";
$pert=$members["select"]." as pert";

if($clause) {
	// instanciation de la nouvelle requête
	$q_abstract="select count(distinct notice_id) from notices $statut_j $acces_j $clause";
	$abstract = mysql_query($q_abstract, $dbh);
	$nb_result_abstract = mysql_result($abstract, 0 , 0); 
	
	//Enregistrement des stats
	if($pmb_logs_activate){
		global $nb_results_tab;
		$nb_results_tab['abstract'] = $nb_result_abstract;
	}
	
	$req_typdoc="select distinct notices.typdoc from notices $statut_j $acces_j $clause group by notices.typdoc";
	if($opac_visionneuse_allow){
		$req_bull="select distinct notices.typdoc, count(explnum_id) as nbexplnum  from notices left join bulletins on bulletins.num_notice = notice_id and bulletins.num_notice != 0 left join explnum on explnum_mimetype in ($opac_photo_filtre_mimetype) and explnum_bulletin = bulletin_id and explnum_bulletin !=0  $statut_j $acces_j $clause group by notices.typdoc";
		$req_noti="select distinct notices.typdoc, count(explnum_id) as nbexplnum  from notices left join explnum on explnum_mimetype in ($opac_photo_filtre_mimetype) and (explnum_notice=notice_id) $statut_j $acces_j $clause group by notices.typdoc";
		$req_typdoc = "select distinct typdoc, sum(nbexplnum) as nbexplnum from ($req_noti union $req_bull) as uni group by typdoc" ; 			
	}
	$res_typdoc = mysql_query($req_typdoc, $dbh);		
	$t_typdoc=array();	
	$nbexplnum_to_photo = 0;
	while ($tpd=mysql_fetch_object($res_typdoc)) {
		$t_typdoc[]=$tpd->typdoc;
		if($opac_visionneuse_allow)
			$nbexplnum_to_photo += $tpd->nbexplnum;
	}

	$l_typdoc=implode(",",$t_typdoc);
	if ($nb_result_abstract) {
		// tout bon, y'a du résultat, on lance le pataquès d'affichage
		// (affichage sur une ligne cliquable, maybe...
		// $found = mysql_query("select * from notices $clause $tri LIMIT $opac_search_results_first_level", $dbh);

		print "<div style=search_result id=\"titre\" name=\"titre\">";
		print "<strong>$msg[abstract]</strong> ".$nb_result_abstract." $msg[results] ";
		// Le lien validant le formulaire est inséré avant le formulaire, cela évite les blancs à l'écran
		//print "</ul>$msg[suite]&nbsp;<img src='./images/search.gif' border='0'/>";
		print "<a href=\"javascript:document.forms['search_abstract'].submit()\">$msg[suite]&nbsp;<img src=./images/search.gif border=0 align=absmiddle></a>";
		$form = "<form name=\"search_abstract\" action=\"./index.php?lvl=more_results\" method=\"post\">";
		if (function_exists("search_other_function_post_values")){ $form .=search_other_function_post_values(); }
		$form .= "<input type=\"hidden\" name=\"user_query\" value=\"".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"mode\" value=\"abstract\">\n";
		$form .= "<input type=\"hidden\" name=\"count\" value=\"".$nb_result_abstract."\">\n";
		$form .= "<input type=\"hidden\" name=\"clause\" value=\"".htmlentities($clause,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"pert\" value=\"".htmlentities($pert,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"l_typdoc\" value=\"".htmlentities($l_typdoc,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"tri\" value=\"".htmlentities($tri,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"search_terms\" value=\"".htmlentities(serialize($search_terms),ENT_QUOTES,$charset)."\">";		
		$form .= "<input type=\"hidden\" name=\"nbexplnum_to_photo\" value=\"".$nbexplnum_to_photo."\">\n</form></div>\n";
		print $form;
		}
	}
