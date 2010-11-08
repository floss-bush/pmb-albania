<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: category.php,v 1.7 2010-07-28 07:44:39 mbertin Exp $
//
// Affichage de la zone de recherche et choix du mode de navigation dans les catégories

$base_path="..";                            
$base_auth = "";  
$base_title = "Selection";

require_once ("$base_path/includes/init.inc.php");  
require_once("$class_path/marc_table.class.php");
require_once("$class_path/thesaurus.class.php");

// modules propres à select.php ou à ses sous-modules
include_once ("$javascript_path/misc.inc.php");
print reverse_html_entities();

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "category.php?caller=$caller&p1=$p1&p2=$p2&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn&keep_tilde=$keep_tilde&parent=";

include("$base_path/selectors/templates/category.tpl.php");

print $sel_header;

//recuperation du thesaurus session en fonction du caller 
switch ($caller) {
	case 'notice' :
		if (!$id_thes) $id_thes = thesaurus::getNoticeSessionThesaurusId();
		thesaurus::setNoticeSessionThesaurusId($id_thes);
		break;
	case 'categ_form' :
		if (!$id_thes) $id_thes = thesaurus::getSessionThesaurusId();
		thesaurus::setSessionThesaurusId($id_thes);
		break;
	default :
		if (!$id_thes) $id_thes = thesaurus::getSessionThesaurusId();
		thesaurus::setSessionThesaurusId($id_thes);
		break;
}
$thes = new thesaurus($id_thes);


//affichage du selectionneur de thesaurus
$liste_thesaurus = thesaurus::getThesaurusList();

$sel_thesaurus = '';
if ($thesaurus_mode_pmb != 0) {	 //la liste des thesaurus n'est pas affichée en mode monothesaurus
	$sel_thesaurus = "<select class='saisie-20em' id='id_thes' name='id_thes' ";

	//si on vient du form de categories, le choix du thesaurus n'est pas possible
	if($caller == 'categ_form') $sel_thesaurus.= "disabled "; 
	$sel_thesaurus.= "onchange = \"this.form.submit()\">" ;
	foreach($liste_thesaurus as $id_thesaurus=>$libelle_thesaurus) {
		$sel_thesaurus.= "<option value='".$id_thesaurus."' "; ;
		if ($id_thesaurus == $id_thes) $sel_thesaurus.= " selected";
		$sel_thesaurus.= ">".htmlentities($libelle_thesaurus,ENT_QUOTES,$charset)."</option>";
	}
	$sel_thesaurus.= "<option value=-1 ";
	if ($id_thes == -1) $sel_thesaurus.= "selected ";
	$sel_thesaurus.= ">".htmlentities($msg['thes_all'],ENT_QUOTES, $charset)."</option>";
	$sel_thesaurus.= "</select>&nbsp;";
}	
$sel_search_form=str_replace("!!sel_thesaurus!!",$sel_thesaurus,$sel_search_form);


// traitement en entrée des requêtes utilisateur
if ($deb_rech) $f_user_input = $deb_rech ;

if(!$f_user_input && !$user_input) {
	$user_input='';
} else {
	// traitement de la saisie utilisateur
	if(!$user_input && $f_user_input) $user_input = $f_user_input;
}

switch ($search_type) {
	case "term":
		$sel_search_form=str_replace("!!t_checked!!","checked",$sel_search_form);
		$sel_search_form=str_replace("!!h_checked!!","",$sel_search_form);
		$src='term_browse.php';
		break;	
	default:
		$sel_search_form=str_replace("!!h_checked!!","checked",$sel_search_form);
		$sel_search_form=str_replace("!!t_checked!!","",$sel_search_form);
		$src='category_browse.php';
		break;
}

$sel_search_form=str_replace("!!f_user_input_value!!",htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset),$sel_search_form);
print $sel_search_form;

if(!$parent) $parent=0;

print "<script>parent.category_browse.location='$src?caller=$caller&p1=$p1&p2=$p2&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn&keep_tilde=$keep_tilde&parent=$parent&id2=$id2&id_thes=$id_thes&user_input=".rawurlencode(stripslashes($user_input))."&f_user_input=".rawurlencode(stripslashes($f_user_input))."';</script>\n";
print $sel_footer;
