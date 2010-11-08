<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice.inc.php,v 1.2 2008-09-25 20:48:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/parametres_perso.class.php");
require_once($include_path."/misc.inc.php");

// recopié et adapté de la classe de gestion notice.class.php
function majNoticesGlobalIndex($notice, $NoIndex = 1, $contenuflux="") {
	global $dbh;
	
	mysql_query("delete from notices_global_index where num_notice = ".$notice." AND no_index = ".$NoIndex,$dbh);
	$titres = mysql_query("select index_serie, tnvol, index_wew, index_sew, index_l, index_matieres, n_gen, n_contenu, n_resume, index_n_gen, index_n_contenu, index_n_resume, eformat from notices where notice_id = ".$notice, $dbh);
	$mesNotices = mysql_fetch_assoc($titres);
	$tit = $mesNotices['index_wew'];
	$indTit = $mesNotices['index_sew'];
	$indMat = $mesNotices['index_matieres'];
	$indL = $mesNotices['index_l'];
	$indResume = $mesNotices['index_n_resume'];
	$indGen = $mesNotices['index_n_gen'];
	$indContenu = $mesNotices['index_n_contenu'];
	$resume = $mesNotices['n_resume'];
	$gen = $mesNotices['n_gen'];
	$contenu = $mesNotices['n_contenu'];
	$indSerie = $mesNotices['index_serie'];
	$tvol = $mesNotices['tnvol'];
	$eformatlien = $mesNotices['eformat'];
	$infos_global=' ';
	$infos_global_index=" ";
	   
	mysql_query("insert into notices_global_index (num_notice, no_index, infos_global, index_infos_global) values(".$notice.",".$NoIndex.",
	CONCAT(' ".addslashes($tvol)." ','".addslashes($tit)." ','".addslashes($resume)." ','".addslashes($gen)." ','".addslashes($contenu)." ','".addslashes($indL)." '),
	CONCAT(' ".$indSerie." ','".addslashes($indTit)." ','".addslashes($indResume)." ','".addslashes($indGen)." ','".addslashes($indContenu)." ','".addslashes($indMat)." '))",$dbh);
	
	// Authors : 
	$auteurs = mysql_query("select author_name, author_rejete, index_author from authors, responsability WHERE responsability_author = author_id AND responsability_notice = $notice", $dbh);
	$numA = mysql_num_rows($auteurs);
	for($j=0;$j < $numA; $j++) {
		$mesAuteurs = mysql_fetch_assoc($auteurs);
		$infos_global.= $mesAuteurs['author_name'].' '.$mesAuteurs['author_rejete'].' ';
		$infos_global_index.=strip_empty_chars($mesAuteurs['author_name'].' '.$mesAuteurs['author_rejete'])." ";
	}
	mysql_free_result($auteurs);
	
	// Nom du pï¿½riodique associï¿½e ï¿½ la notice de dï¿½pouillement le cas ï¿½chï¿½ant :
	$temp = mysql_query("select bulletin_notice, bulletin_titre, index_titre, index_wew, index_sew from analysis, bulletins, notices  WHERE analysis_notice=".$notice." and analysis_bulletin = bulletin_id and bulletin_notice=notice_id", $dbh);
	$numP = mysql_num_rows($temp);
	if ($numP) {
		// La notice appartient a un perdiodique, on selectionne le titre de pï¿½riodique :
		$mesTemp = mysql_fetch_assoc($temp);
	  	$infos_global.= $mesTemp['index_wew'].' '.$mesTemp['bulletin_titre'].' '.$mesTemp['index_titre'].' ';
	  	$infos_global_index.=strip_empty_words($mesTemp['index_wew'].' '.$mesTemp['bulletin_titre'].' '.$mesTemp['index_titre'])." ";				
	}
	mysql_free_result($temp);
	
	// Categories : 
	$noeud = mysql_query("select notices_categories.num_noeud,libelle_categorie from notices_categories,categories where notcateg_notice = ".$notice." and notices_categories.num_noeud=categories.num_noeud order by ordre_categorie", $dbh);
	$numNoeuds = mysql_num_rows($noeud);
	// Pour chaque noeud trouvï¿½s on cherche les noeuds parents et les noeuds fils :
	for($j=0;$j < $numNoeuds; $j++) {
		// On met ï¿½ jours la table notices_global_index avec le noeud trouvï¿½:
	 	$mesNoeuds = mysql_fetch_assoc($noeud);
		$noeudInit = $mesNoeuds['num_noeud'];
		$infos_global.= $mesNoeuds['libelle_categorie']." ";
	 	$infos_global_index.= strip_empty_words($mesNoeuds['libelle_categorie'])." ";
	}
	
	// Sous-collection : 
	$subColls = mysql_query("select sub_coll_name, index_sub_coll from notices, sub_collections WHERE subcoll_id = sub_coll_id AND notice_id = ".$notice, $dbh);
	$numSC = mysql_num_rows($subColls);
	for($j=0;$j < $numSC; $j++) {
		$mesSubColl = mysql_fetch_assoc($subColls);
		$infos_global.=$mesSubColl['index_sub_coll'].' '.$mesSubColl['sub_coll_name'].' ';
		$infos_global_index.=strip_empty_words($mesSubColl['index_sub_coll'].' '.$mesSubColl['sub_coll_name'])." ";
	}
	mysql_free_result($subColls);
	
	// Indexation numï¿½rique : 
	$indexNums = mysql_query("select indexint_name, indexint_comment, index_indexint from notices, indexint WHERE indexint = indexint_id AND notice_id = ".$notice, $dbh);
	$numIN = mysql_num_rows($indexNums);
	for($j=0;$j < $numIN; $j++) {
		$mesindexNums = mysql_fetch_assoc($indexNums);
		$infos_global.=$mesindexNums['indexint_name'].' '.$mesindexNums['indexint_comment'].' ';
		$infos_global_index.=strip_empty_words($mesindexNums['indexint_name'].' '.$mesindexNums['indexint_comment'])." ";
	}
	mysql_free_result($indexNums);
	
	// Collection : 
	$Colls = mysql_query("select collection_name, index_coll from notices, collections WHERE coll_id = collection_id AND notice_id = ".$notice, $dbh);
	$numCo = mysql_num_rows($Colls);
	for($j=0;$j < $numCo; $j++) {
		$mesColl = mysql_fetch_assoc($Colls);
		$infos_global.= $mesColl['collection_name'].' ';
		$infos_global_index.=strip_empty_words($mesColl['collection_name'])." ";
	}
	mysql_free_result($Colls);
	 
	// Editeurs : 
	$editeurs = mysql_query("select ed_name, index_publisher from notices, publishers WHERE (ed1_id = ed_id OR ed2_id = ed_id) AND notice_id = ".$notice, $dbh);
	$numE = mysql_num_rows($editeurs);
	for($j=0;$j < $numE; $j++) {
		$mesEditeurs = mysql_fetch_assoc($editeurs);				
		$infos_global.= $mesEditeurs['ed_name'].' ';
		$infos_global_index.=strip_empty_chars($mesEditeurs['ed_name'])." ";			
	}
	mysql_free_result($editeurs);
	  
	mysql_free_result($titres);
	
	 // champ perso cherchable
	$p_perso=new parametres_perso("notices");	
	$mots_perso=$p_perso->get_fields_recherche($notice);
	if($mots_perso) {
		$infos_global.= $mots_perso.' ';
		$infos_global_index.= strip_empty_words($mots_perso)." ";	
	}
	
	// flux RSS éventuellement
	$eformat=array();
	$eformat = explode(' ', $eformatlien) ;
	if ($eformat[0]=='RSS' && $eformat[3]=='1') {
		$flux=strip_tags($contenuflux) ;
		$infos_global_index.= strip_empty_words($flux)." ";
	}
	
	mysql_query("UPDATE notices_global_index SET infos_global = CONCAT(infos_global,'".addslashes($infos_global)." '), index_infos_global = CONCAT(index_infos_global,'".addslashes($infos_global_index)." ') WHERE num_notice = ".$notice." AND no_index = ".$NoIndex, $dbh);		
}

?>