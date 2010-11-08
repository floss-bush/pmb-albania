<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.3 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// recherche notice (resa_planning) : page de switch recherche auteurs/titres
require_once($class_path.'/searcher.class.php');

if ($ex_query) 
	require_once('circ/resa_planning/authors/expl.inc.php');
else {
	$link = './circ.php?categ=resa_planning&resa_action=add_resa&id_empr='.$id_empr.'&groupID='.$groupID.'&id_notice=!!id!!';
	$sh=new searcher_title('./circ.php?categ=resa_planning&resa_action=search_resa&id_empr='.$id_empr.'&groupID='.$groupID);
}

