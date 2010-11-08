<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: translation.class.php,v 1.2 2009-03-27 15:46:32 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");
require_once($include_path."/templates/translation.tpl.php");
/**
 * Classe permettant de gérer les traductions de libellé
 * Utilise la table translation, croisée avec le nom de la table et du champ à traduire
 * Mémorise et récupère le texte dans la lange voulue
 * 
 "CREATE TABLE translation (
    trans_table VARCHAR( 255 ) NOT NULL default '',
    trans_field VARCHAR( 255 ) NOT NULL default '',
    trans_lang VARCHAR( 255 ) NOT NULL default '',
   	trans_num INT( 8 ) UNSIGNED NOT NULL default 0 ,
    trans_text VARCHAR( 255 ) NOT NULL default '',
    PRIMARY KEY trans (trans_table,trans_field,trans_lang,trans_num),
    index i_lang(trans_lang)
   )";  
 */
	
class translation {

var $trans_table="";
var $trans_field="";

function translation($id,$trans_table,$trans_field,$liste_langue) {
	$this->table = $trans_table;
	$this->field = $trans_field;
	$this->num=$id;
	$this->liste_langue=explode(",",$liste_langue);
	$this->fetch_data();
}

// récupération des infos en base
function fetch_data() {
	global $dbh;

	$this->text=array();
	
	$req="SELECT * FROM translation WHERE trans_table='".$this->table."' and trans_field='$this->field' and trans_num='".$this->num."' ";
	$myQuery = mysql_query($req, $dbh);
	if(mysql_num_rows($myQuery)){		
		while(($myreq=mysql_fetch_object($myQuery))) {	
			$langue=$myreq->trans_lang;
			$this->num=$myreq->trans_num;
			$this->text["$langue"]=$myreq->trans_text;

		}	
	} 
	
}

/**
 * Retourne la traduction dans la langue voulue, ou le libellé par défaut
 */
function get_text($lang="") {

}
function set_text($lang="") {

}	
function delete() {
	global $dbh;
	$req="delete from translation WHERE trans_table='".$this->table."' and trans_field='$this->field' and trans_num='$this->num' ";
	mysql_query($req, $dbh);
}
function get_form($label,$field_id,$field_name,$field_value,$class_saisie,$style_form="display: none;") {
	global $msg,$charset;
	global $translation_tpl_form_javascript,$translation_tpl_form,$translation_tpl_line_form;
	global $translation_tpl_form_javascript_flag;
	global $lang;
	$line="";
	$nb=0;
	foreach($this->liste_langue as $langue) {
		if($langue != $lang) {
			//<input class='!!class_saisie!!' name='!!field_id!!_!!lang!!' value='!!text' type='text'></div>
			$line.=str_replace("!!libelle_lang!!",$langue,$translation_tpl_line_form);		
			$line=str_replace("!!lang!!",$langue,$line);		
			$line=str_replace("!!text!!",htmlentities($this->text["$langue"],ENT_QUOTES,$charset),$line);		
			$nb++;
			//print $line; exit;
		}	
	}
	
	$form =str_replace("!!lang_list!!",$line,$translation_tpl_form);
	if($nb) $translation_button="<input class='bouton_small' value='".$msg["translation_button"]."' onclick=\"translation_view('lang_!!field_id!!')\" type='button'>";
	else $translation_button="";
	$form=str_replace("!!translation_button!!",$translation_button,$form);	
	$form=str_replace("!!label!!",$label,$form);
	$form=str_replace("!!class_saisie!!",$class_saisie,$form);
	$form=str_replace("!!field_id!!",$field_id,$form);
	$form=str_replace("!!field_name!!",$field_name,$form);
	$form=str_replace("!!field_value!!",htmlentities($field_value,ENT_QUOTES,$charset),$form);
	$form=str_replace("!!class_form!!","class_form",$form);	
	$form=str_replace("!!style_form!!",$style_form,$form);	

	if(!$translation_tpl_form_javascript_flag) {
		$form=$translation_tpl_form_javascript.$form;
		$translation_tpl_form_javascript_flag++;
	}
	return $form;
}

function update($input_field) {
	global $dbh;
	// effacer les anciens
	$this->delete();
	foreach($this->liste_langue as $langue) {
		$field=$input_field."_".$langue;		
		global $$field;
		$text=$$field;
		if($text) {
			$req="INSERT into translation Set trans_table='".$this->table."' , trans_field='$this->field' ,trans_lang='$langue', trans_num='".$this->num."'  ,trans_text='$text' ";	
			mysql_query($req, $dbh);
		}
		
	}
}	
}
?>