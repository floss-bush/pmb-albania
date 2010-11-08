<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_form.inc.php,v 1.6 2009-11-04 14:37:54 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$include_path/templates/catalog.tpl.php");
require_once("$include_path/isbn.inc.php");
require_once("$include_path/marc_tables/$lang/empty_words");
require_once("$class_path/marc_table.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/indexint.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/category.class.php");
require_once("$class_path/notice.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/expl.class.php");
require_once("$class_path/explnum.class.php");
require_once("$class_path/emprunteur.class.php");
require_once("$include_path/fields_empr.inc.php");
require_once("$include_path/datatype.inc.php");
require_once("$include_path/parser.inc.php");
require_once("$include_path/notice_authors.inc.php");
require_once("$include_path/notice_categories.inc.php");
require_once("$include_path/explnum.inc.php") ;
require_once("$include_path/expl_info.inc.php") ;
require_once("$include_path/bull_info.inc.php") ;
require_once("$include_path/resa_func.inc.php") ;

require_once("$class_path/suggestions.class.php");


require_once("$class_path/serials.class.php");

if ($pmb_prefill_cote) require_once("./catalog/expl/$pmb_prefill_cote");
	else require_once("./catalog/expl/custom_no_cote.inc.php");
	
// page de catalogage
//Récupération des éléments de la suggestion
$sug = new suggestions($id_sug);

if($sug->sugg_noti_unimarc){
	require_once("$class_path/z3950_notice.class.php");
	//Recherche de la fonction auxiliaire d'intégration
	if ($z3950_import_modele) {
		require_once($base_path."/catalog/z3950/".$z3950_import_modele);
	} else require_once($base_path."/catalog/z3950/func_other.inc.php");	
	
	//si on on une notice unimarc stockée, on la traite
	$z=new z3950_notice("unimarc",$sug->sugg_noti_unimarc);
	$z->message_retour = $msg[76];
	if($z->bibliographic_level == "a" && $z->hierarchic_level=="2"){
		$form=$z->get_form("acquisition.php?categ=sug&action=record_uni&id_bibli=$id_bibli&id_sug=".$sug->id_suggestion,0,true,true);
	} else { 
		$form=$z->get_form("acquisition.php?categ=sug&action=record_uni&id_bibli=$id_bibli&id_sug=".$sug->id_suggestion,0,true);
	}
	$form=str_replace("<!--!!form_title!!-->","<h3>".$msg['acquisition_catalogue_uni']." : ".htmlentities($sug->titre,ENT_QUOTES,$charset)."</h3>",$form);
	print $form;
	
} else {
	// si seulement un isbn, recherche si la notice est déjà existante pour dédoublonner
	if(!$id && $cod) {				
		$id= notice::get_notice_id_from_cb($cod);
	}					
	// affichage du form de création/modification d'une notice
	$myNotice = new notice($id, $cod);
	if(!$myNotice->id) {
		$myNotice->tit1 = $sug->titre;
		$myNotice->code = $sug->code;
		$myNotice->prix = $sug->prix;
	}
	
	$myNotice->action = "./acquisition.php?categ=sug&action=upd_notice&id_bibli=".$id_bibli."&id_sug=".$id_sug."&id=";
	$myNotice->link_annul = "./acquisition.php?categ=sug&action=modif&id_bibli=".$id_bibli."&id_sug=".$id_sug;
	
	print $myNotice->show_form();
	
	//TODO A revoir pour le transfert des auteurs et éditeurs
	if(!$myNotice->id) {
		if($sug->auteur)print "<script type='text/javascript'>openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut0_id&param2=f_aut0&deb_rech=".$sug->auteur."', 'select_aut1', 400, 400, 0, 0, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes');</script>";
		if($sug->editeur)print "<script type='text/javascript'>openPopUp('./select.php?what=editeur&caller=notice&p1=f_ed1_id&p2=f_ed1&p3=f_coll_id&p4=f_coll&p5=f_subcoll_id&p6=f_subcoll&deb_rech=".$sug->editeur."', 'select_ed1', 400, 400, 30, 30, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes');</script>";
	}
}
