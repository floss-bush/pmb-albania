<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.15 2009-11-30 10:39:25 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de switch recherche sujets
require_once($class_path."/searcher.class.php");
require_once("$class_path/thesaurus.class.php");

//recuperation du thesaurus session 
if(!$id_thes) {
$id_thes = thesaurus::getSessionThesaurusId();
} else {
	thesaurus::setSessionThesaurusId($id_thes);
}


$link = './catalog.php?categ=isbd&id=!!id!!';
$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!';
	
$link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
$link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
$link_explnum_serial = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";

$browser_url = "./catalog/notices/search/subjects/categ_browser.php?id_thes=".$id_thes;


$rec_history=true;
if (($no_rec_history)&&((string)$page=="")) {
	$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["NOLINK"]=true;
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$msg["histo_free_browse"];
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=$msg["355"];
	$_POST["page"]=0;
	$page=0;
}

$sh=new searcher_subject("./catalog.php?categ=search&mode=1",$rec_history);
