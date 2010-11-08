<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.9 2008-11-27 15:54:26 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de switch recherche éditeurs/collections

// affichage de l'iframe pour le browser de collections
require_once($class_path."/searcher.class.php");

$unq=md5(microtime());

$link = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_notice=!!id!!";
$link_serial = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=view_serial&serial_id=!!id!!";
$link_analysis = '';
$link_bulletin = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=!!id!!";
$link_notice_bulletin = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=!!id!!";

$browser_url = "./circ/resa/publishers/publisher_browser.php?id_empr=$id_empr&groupID=$groupID&unq=$unq";

$sh=new searcher_publisher("./circ.php?categ=resa&mode=2&id_empr=$id_empr&groupID=$groupID");

?>