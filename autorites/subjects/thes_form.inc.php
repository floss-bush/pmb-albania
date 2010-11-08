<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: thes_form.inc.php,v 1.10 2009-12-18 11:18:25 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusions diverses
include_once("$include_path/templates/thesaurus.tpl.php");
require_once("$class_path/thesaurus.class.php");
require_once("$class_path/XMLlist.class.php");

if(!$id_thes) $id_thes = 0;
$update_url = "./autorites.php?categ=categories&sub=thes_update&id_thes=".$id_thes;
$delete_url = "./autorites.php?categ=categories&sub=thes_delete&id_thes=".$id_thes;
$cancel_url = "./autorites.php?categ=categories&sub=thes"; 


//Récuperation de la liste des langues définies pour l'interface
$langages = new XMLlist("$include_path/messages/languages.xml", 1);
$langages->analyser();
$lg = $langages->table;

//Récuperation de la liste des langues définies pour les thésaurus
$thes_liste_trad = thesaurus::getTranslationsList();
$lg1 = array();
foreach($thes_liste_trad as $dummykey=>$item) {
	if ($lg[$item]!= '') $lg1[$item] = $lg[$item];
}


if($id_thes) {	//modification
	
	$title = $msg[thes_modification];
	$delete_button = "<input type='button' class='bouton' value='$msg[63]' onClick=\"confirm_delete();\">";
			
	// on récupère les données du thesaurus
	$thes = new thesaurus($id_thes);
	
	$identifiant_thesaurus = "<div class='row'><label class='etiquette' >".$msg[38]."</label></div>";
	$identifiant_thesaurus.= "<div class='row'><input type='text' class='saisie-5emd' id='numero_thesaurus' name='numero_thesaurus' readonly='readonly' value='".$id_thes."' /></div>";

	$libelle_thesaurus = $thes->libelle_thesaurus;	

	$langue_defaut = htmlentities(addslashes($lg[$thes->langue_defaut]),ENT_QUOTES, $charset);
		
} else {	//creation
	
		
	$title = $msg[thes_creation];
	$delete_button = '';
	
	$identifiant_thesaurus = '';
	$libelle_thesaurus = '';
	
	$langue_defaut = "<select class='saisie-30em' id='langue_defaut' name='langue_defaut' >";
	foreach($lg1 as $key=>$value){
		$langue_defaut.= "<option value='".$key."' ";
		if($key == $lang)$langue_defaut.= " selected ";
		$langue_defaut.= " >".htmlentities(addslashes($value),ENT_QUOTES, $charset)."</option>";
	}
	$langue_defaut.= "</select>";
	
}

if(($id_thes) && thesaurus::hasCateg($id_thes)){
	$thes_form = str_replace('!!thesaurus_as_categ!!', "oui", $thes_form);
}else{
	$thes_form = str_replace('!!thesaurus_as_categ!!', "non", $thes_form);
}

$thes_form = str_replace('!!id_thes!!', $id_thes, $thes_form);
$thes_form = str_replace('!!form_title!!', $title, $thes_form);
$thes_form = str_replace('!!identifiant_thesaurus!!', $identifiant_thesaurus, $thes_form);
$thes_form = str_replace('!!libelle_thesaurus!!', $libelle_thesaurus, $thes_form);
$thes_form = str_replace('!!langue_defaut!!', $langue_defaut, $thes_form);
$thes_form = str_replace('!!update_url!!', $update_url, $thes_form);
$thes_form = str_replace('!!delete_url!!', $delete_url, $thes_form);
$thes_form = str_replace('!!cancel_url!!', $cancel_url, $thes_form);

$thes_form = str_replace('!!delete_button!!', $delete_button, $thes_form);


print $thes_form;
