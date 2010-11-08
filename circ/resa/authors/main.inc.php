<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.7 2008-11-27 15:54:26 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// recherche notice (resa) : page de switch recherche auteurs/titres
require_once($class_path."/searcher.class.php");

if ($ex_query) 
	require_once("circ/resa/authors/expl.inc.php");
else {
	$link = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_notice=!!id!!";
	$link_serial = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=view_serial&serial_id=!!id!!";
	$link_analysis = '';
	$link_bulletin = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=!!id!!";
	$link_notice_bulletin = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=!!id!!";
	
	$sh=new searcher_title("./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID");
}

