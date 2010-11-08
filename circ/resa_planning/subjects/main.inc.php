<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.2 2007-03-10 09:03:17 touraine37 Exp $

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


$link = "./circ.php?categ=resa_planning&resa_action=add_resa&id_empr=$id_empr&groupID=$groupID&id_notice=!!id!!";
$link_serial = '';
$link_analysis = '';
$link_bulletin = '';

$unq = md5(microtime());
$browser_url = './circ/resa_planning/subjects/categ_browser.php?id_thes='.$id_thes.'&id_empr='.$id_empr.'&groupID='.$groupID.'&unq='.$unq;

$sh=new searcher_subject("./circ.php?categ=resa_planning&resa_action=search_resa&mode=1&id_empr=$id_empr&groupID=$groupID&unq=$unq");

?>