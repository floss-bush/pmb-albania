<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: keyword.inc.php,v 1.31 2010-07-02 08:15:15 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// premier niveau de recherche OPAC sur mot-clé

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
// en fonction de cela, je détermine la clause de recherche
$aq=new analyse_query(stripslashes($user_query));

if ($acces_j) {
	$members=$aq->get_query_members("notices","index_l","index_matieres","notice_id");
	$clause="where ".$members["where"]." and (trim(index_matieres)!='' or index_l!='')";	 
	$statut_j='';
} else {
	$members=$aq->get_query_members("notices","index_l","index_matieres","notice_id","statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")");
	$clause="where ".$members["where"]." and (trim(index_matieres)!='' or index_l!='') and (".$members["restrict"].")";
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
	//$requete = "select count(distinct notice_id) from notices, notice_statut $clause";
	$requete = "select count(distinct notice_id) from notices $statut_j $acces_j $clause";
	$kwd = mysql_query($requete, $dbh);
	// je récupère le nombre de résultats dans les propriétés de la classe
	$nb_result_keywords = mysql_result($kwd, 0, 0);
	
	//$req_typdoc="select distinct notices.typdoc from notices, notice_statut $clause";
	$req_typdoc="select distinct typdoc from notices $statut_j $acces_j $clause group by typdoc";
	if($opac_visionneuse_allow)
		$req_typdoc="select distinct typdoc, count(explnum_id) as nbexplnum from notices left join explnum on explnum_notice=notice_id and explnum_mimetype in ($opac_photo_filtre_mimetype) $statut_j $acces_j $clause group by typdoc";
	$res_typdoc = mysql_query($req_typdoc, $dbh);		
	$t_typdoc=array();	
	$nbexplnum_to_photo=0;
	while ($tpd=mysql_fetch_object($res_typdoc)) {
		$t_typdoc[]=$tpd->typdoc;
		if($opac_visionneuse_allow)
			$nbexplnum_to_photo += $tpd->nbexplnum;
	}
	$l_typdoc=implode(",",$t_typdoc);

	//Enregistrement des stats
	if($pmb_logs_activate){
		global $nb_results_tab;
		$nb_results_tab['keywords'] = $nb_result_keywords;
	}

	if ($nb_result_keywords) {
		// tout bon, y'a du résultat, on lance le pataquès d'affichage
		// (affichage sur une ligne cliquable, maybe...

		// constitution du tableau des mots-clé
		$mots_cle_chaine = '';
		
		print "<div style=search_result id=\"titre\" name=\"titre\">";
		print "<strong>";
		if($opac_allow_tags_search)
			print $msg['tag'];
		else
			print $msg['keywords'];
		print "</strong> ".$nb_result_keywords."&nbsp;".$msg['results']."&nbsp";
		// Le lien validant le formulaire est inséré avant le formulaire, cela évite les blancs à l'écran
		//print "</ul>$msg[suite]&nbsp;<img src='./images/search.gif' border='0'/>";
		print "<a href=\"javascript:document.forms['search_keywords'].submit()\">$msg[suite]&nbsp;<img src=./images/search.gif border=0 align=absmiddle></a>";
		$form = "<form name=\"search_keywords\" action=\"./index.php?lvl=more_results\" method=\"post\">";
		$form .= "<input type=\"hidden\" name=\"user_query\" value=\"".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."\">\n";
		if (function_exists("search_other_function_post_values")){ $form .=search_other_function_post_values(); }
		$form .= "<input type=\"hidden\" name=\"mode\" value=\"keyword\">\n";
		$form .= "<input type=\"hidden\" name=\"clause\" value=\"".htmlentities($clause,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"count\" value=\"".$nb_result_keywords."\">\n";
		$form .= "<input type=\"hidden\" name=\"pert\" value=\"".htmlentities($pert,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"l_typdoc\" value=\"".htmlentities($l_typdoc,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"tri\" value=\"".htmlentities($tri,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"search_terms\" value=\"".htmlentities(serialize($search_terms),ENT_QUOTES,$charset)."\">";
		$form .= "<input type=\"hidden\" name=\"nbexplnum_to_photo\" value=\"".$nbexplnum_to_photo."\">\n</form></div>\n";
		print $form;
	}
}
