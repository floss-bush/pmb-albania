<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.10 2009-11-30 16:44:16 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// recherche notice (catalogage) : page de switch recherche auteurs/titres

// on commence par créer le champs de sélection de document
// récupération des types de documents utilisés.
require_once($class_path."/searcher.class.php");

if($pmb_show_notice_id && $f_notice_id){
	require_once("catalog/notices/search/authors/id_notice.inc.php");
} elseif ($ex_query){ 
	require_once("catalog/notices/search/authors/expl.inc.php");
} else {
	$link = './catalog.php?categ=isbd&id=!!id!!';
	$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
	$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!';
	
	$link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
	$link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
	$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
	$link_explnum_serial = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";
	
	$sh=new searcher_title("./catalog.php?categ=search&mode=0",true);
	if ($shcut=='B') print "<script type='text/javascript'>document.forms['NOTICE_author_query'].elements['ex_query'].focus();</script>" ;
	
}