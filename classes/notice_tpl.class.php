<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_tpl.class.php,v 1.2 2010-09-14 14:57:10 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$include_path/templates/notice_tpl.tpl.php");
require_once("$class_path/notice_tpl_gen.class.php");
require_once("$class_path/marc_table.class.php");

class notice_tpl {
	
	// ---------------------------------------------------------------
	//		propriétés de la classe
	// ---------------------------------------------------------------	
	var $id;		// MySQL id in table 'notice_tpl'
	var $name;		// nom du template
	var $comment;	// description du template
	var $code ; // Code du template
	
	// ---------------------------------------------------------------
	//		constructeur
	// ---------------------------------------------------------------
	function notice_tpl($id=0) {			
		$this->id = $id;
		$this->getData();
	}
	
	// ---------------------------------------------------------------
	//		getData() : récupération infos 
	// ---------------------------------------------------------------
	function getData() {
		global $dbh,$msg;

		$this->name = '';			
		$this->comment = '';
		$this->id_test = '';
		$this->code=array();
		$this->code[0]=array();
		
		$req_loc="select idlocation,location_libelle from docs_location";
		$res_loc=mysql_query($req_loc);
		
		$this->location_label[0]=$msg["all_location"];
		if (mysql_num_rows($res_loc)) {	
			while (($r=mysql_fetch_object($res_loc))) {
				$this->code[$r->idlocation]=array();
				$this->location_label[$r->idlocation]=$r->location_libelle;
			}
		}	
		$source = new marc_list("doctype");
		$source_tab = $source->table;
		$type_doc[0]="";
		$this->type_doc_label[0]=$msg["tous_types_docs"];
		foreach($source_tab as $key=>$libelle) {
			$type_doc[$key]="";
			$this->type_doc_label[$key]=$libelle;
		}
		foreach($this->code as $key =>$val) {
			$this->code[$key]["0"]=$type_doc;
			$this->code[$key]["m"]=$type_doc;
			$this->code[$key]["a"]=$type_doc;
			$this->code[$key]["s"]=$type_doc;
			$this->code[$key]["b"]=$type_doc;
		}
		$this->type_notice["0"]=$msg["notice_tpl_notice_all"];
		$this->type_notice["m"]=$msg["notice_tpl_notice_monographie"];
		$this->type_notice["a"]=$msg["notice_tpl_notice_article"];
		$this->type_notice["s"]=$msg["notice_tpl_notice_periodique"];
		$this->type_notice["b"]=$msg["notice_tpl_notice_bulletin"];
	
		if($this->id) {
			$requete = "SELECT * FROM notice_tpl WHERE notpl_id='".$this->id."' LIMIT 1 ";
			$result = @mysql_query($requete, $dbh);
			if(mysql_num_rows($result)) {
				$temp = mysql_fetch_object($result);				
				$this->name	= $temp->notpl_name;
				$this->comment	= $temp->notpl_comment;
				$this->show_opac	= $temp->notpl_show_opac;					
				$this->id_test	= $temp->notpl_id_test;			
				$requete = "SELECT * FROM notice_tplcode  WHERE num_notpl='".$this->id."' ";
				$result_code = @mysql_query($requete, $dbh);
				if(mysql_num_rows($result_code)) {
					while(($temp_code= mysql_fetch_object($result_code))) {
						$this->code[$temp_code->notplcode_localisation][$temp_code->notplcode_niveau_biblio] [$temp_code->notplcode_typdoc]=$temp_code->nottplcode_code;	
					}
				}
			} else {
				// pas trouvé avec cette clé
				$this->id = 0;								
			}
		}
	}
	
	// ---------------------------------------------------------------
	//		show_list : affichage de la liste des éléments
	// ---------------------------------------------------------------	
	function show_list($link="./edit.php") {	
		global $dbh, $charset,$msg;
		global $notice_tpl_liste, $notice_tpl_liste_ligne;
		
		$requete = "SELECT * FROM notice_tpl ORDER BY notpl_name ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$pair="odd";
			while(($temp = mysql_fetch_object($result))){	
				$id = $temp->notpl_id;			
				$name = $temp->notpl_name;
				$comment = $temp->notpl_comment;
				if($temp->notpl_show_opac)	$show_opac=$msg["notice_tpl_show_opac_yes"];
				else $show_opac=$msg["notice_tpl_show_opac_no"];
					
				
				if($pair=="even") $pair ="odd";	else $pair ="even";
				// contruction de la ligne
				$ligne=$notice_tpl_liste_ligne;
				
				$ligne = str_replace("!!name!!",	htmlentities($name,ENT_QUOTES, $charset), $ligne);
				$ligne = str_replace("!!comment!!",	htmlentities($comment,ENT_QUOTES, $charset), $ligne);
				$ligne = str_replace("!!show_opac!!",	$show_opac, $ligne);	
				$ligne = str_replace("!!pair!!",	$pair, $ligne);					
				$ligne = str_replace("!!link_edit!!",	$link."?categ=tpl&sub=notice&action=edit&id=$id", $ligne);	
				$ligne = str_replace("!!link_eval!!",	$link."?categ=tpl&sub=notice&action=eval&id=$id&id_test=".$this->id_test, $ligne);	
				$ligne = str_replace("!!id!!",		$id, $ligne);	
				$tableau.=$ligne;			
			}				
		}
		$liste = str_replace("!!notice_tpl_liste!!",$tableau, $notice_tpl_liste);	
		$liste = str_replace("!!link_ajouter!!",	$link."?categ=tpl&sub=notice&action=edit", $liste);	
		return $liste;
	}	
	
	// ---------------------------------------------------------------
	//		show_form : affichage du formulaire de saisie
	// ---------------------------------------------------------------
	function show_form($link="./edit.php") {
	
		global $msg;
		global $notice_tpl_form, $notice_tpl_form_code;
		global $charset;

		$form=$notice_tpl_form;		
		$action = $link."?categ=tpl&sub=notice&action=update&id=!!id!!";
		
		if($this->id) {
			$libelle = $msg["notice_tpl_modifier"];			
			$button_delete = "<input type='button' class='bouton' value='".$msg[63]."' onClick=\"confirm_delete();\">";
			$action_delete = $link."?categ=tpl&sub=notice&action=delete&id=!!id!!";
			if($this->show_opac) $show_opac=" checked='checked' "; else $show_opac="";
		} else {			
			$libelle = $msg["notice_tpl_ajouter"];
			$button_delete ="";
			$action_delete="";
		}
		foreach($this->code as $id_location =>$tab_typenotice) {	
			$form_typenotice_all='';
	
			foreach($tab_typenotice as $typenotice =>$tab_typedoc) {								
				$form_code_typedoc='';
				foreach($tab_typedoc as  $typedoc=>$code) {	
					$form_code_temp = str_replace("!!loc!!", $id_location, $notice_tpl_form_code);
					$form_code_temp = str_replace("!!typenotice!!",	$typenotice, $form_code_temp);
					$form_code_temp = str_replace("!!typedoc!!", $typedoc, $form_code_temp);
					$form_code_temp = str_replace("!!code!!", htmlentities($code,ENT_QUOTES, $charset), $form_code_temp);					
					$form_code_typedoc.= gen_plus("plus_typedoc".$id_location."_".$typenotice."_".$typedoc,$this->type_doc_label["$typedoc"],$form_code_temp);					
				}		
				$form_typenotice_all.= gen_plus("plus_typenotice".$id_location."_".$typenotice."_",$this->type_notice["$typenotice"],$form_code_typedoc);					
			}	
			$form_code.=gen_plus("plus_location".$id_location,$this->location_label[$id_location],$form_typenotice_all);
		}
		$form = str_replace("!!libelle!!",	$libelle, $form);
		$form = str_replace("!!name!!",		htmlentities($this->name,ENT_QUOTES, $charset), $form);
		$form = str_replace("!!comment!!",	htmlentities($this->comment,ENT_QUOTES, $charset), $form);
		$form = str_replace("!!id_test!!",	htmlentities($this->id_test,ENT_QUOTES, $charset), $form);
		$form = str_replace("!!show_opac!!",$show_opac, $form);
		$form = str_replace("!!code_part!!", $form_code, $form);		
	
		$form = str_replace("!!action!!",	$action, $form);		
		$form = str_replace("!!delete!!",	$button_delete,	$form);
		$form = str_replace("!!action_delete!!",$action_delete,	$form);
		$form = str_replace("!!id!!",		$this->id, $form);			
		return $form;
	}
	
	// ---------------------------------------------------------------
	//		delete() : suppression 
	// ---------------------------------------------------------------
	function delete() {
		global $dbh;
		global $msg;
		
		if(!$this->id)	return $msg[403]; 

		// effacement dans la table
		$requete = "DELETE FROM notice_tpl WHERE notpl_id='".$this->id."' ";
		mysql_query($requete, $dbh);
		$requete = "DELETE FROM  notice_tplcode  WHERE num_notpl='".$this->id."' ";
		mysql_query($requete, $dbh);
		
		return false;
	}
	
	
	
	// ---------------------------------------------------------------
	//		update($value) : mise à jour 
	// ---------------------------------------------------------------
	function update($value) {
	
		global $dbh;
		global $msg;
		global $include_path;
			
		// nettoyage des chaînes en entrée		
		$value['name'] = addslashes(clean_string($value['name']));
		$value['comment'] = addslashes($value['comment']);		
		$value['id_test'] = addslashes($value['id_test']);		
		$value['show_opac'] = addslashes($value['show_opac']);
		
		if(!$value['name'])	return false;
		
		$requete  = "SET  ";
		$requete .= "notpl_name='".$value["name"]."', ";	
		$requete .= "notpl_id_test='".$value["id_test"]."', ";			
		$requete .= "notpl_comment='".$value["comment"]."', ";		
		$requete .= "notpl_show_opac='".$value["show_opac"]."' ";		
		 
		if($this->id) {
			// update
			$requete = "UPDATE notice_tpl $requete WHERE notpl_id=".$this->id." ";
			if(!mysql_query($requete, $dbh)) {		
				require_once("$include_path/user_error.inc.php"); 
				warning($msg["notice_tpl_modifier"], $msg["notice_tpl_modifier_erreur"]);
				return false;
			}
		} else {
			// creation
			$requete = "INSERT INTO notice_tpl ".$requete;
			if(mysql_query($requete, $dbh)) {
				$this->id=mysql_insert_id();				
			} else {
				require_once("$include_path/user_error.inc.php"); 
				warning($msg["notice_tpl_ajouter"], $msg["notice_tpl_ajouter_erreur"]);
				return false;
			}
		}
		
		// insertion du code 
		$requete = "DELETE FROM  notice_tplcode  WHERE num_notpl='".$this->id."' ";
		mysql_query($requete, $dbh);
		
		if($value['code'])
		foreach($value['code'] as $id_location =>$tab_typenotice) {				
			foreach($tab_typenotice as $typenotice =>$tab_typedoc) {					
				foreach($tab_typedoc as  $typedoc=>$code) {	
					$requete = "INSERT INTO notice_tplcode SET 
						num_notpl='".$this->id."',
						notplcode_localisation='$id_location', 
						notplcode_typdoc='$typedoc',
						notplcode_niveau_biblio='$typenotice', 
						nottplcode_code='". addslashes($code)."' ";	
					if(!mysql_query($requete, $dbh)) {
						require_once("$include_path/user_error.inc.php"); 
						warning($msg["notice_tpl_ajouter"], $msg["notice_tpl_ajouter_erreur"]);
						return false;
					}						
				}	
							
			}			
		}	
		return true;
	}
		
	function update_from_form() {
		global $name, $code_list, $comment,$id_test,$show_opac;
		
		$value['name']=stripslashes($name);
		$value['comment']=stripslashes($comment);
		$value['id_test']=stripslashes($id_test);
		$value['show_opac']=stripslashes($show_opac);

		foreach($code_list as $input_code)	{
			$code="";
			eval("global \$".$input_code.";\$code= $".$input_code.";");
			if($code) {
				list($label,$location,$type_notice,$type_doc)=explode("_",$input_code);				
				$value["code"]["$location"]["$type_notice"]["$type_doc"]=stripslashes($code);
			}
		}
		$this->update($value); 		
	}
	
	function gen_tpl_select($select_name="notice_tpl", $selected_id=0) {		
		global $msg;
		
		$requete = "SELECT notpl_id, concat(notpl_name,'. ',notpl_comment) as nom  FROM notice_tpl ORDER BY notpl_name ";
		$onchange="";
		return gen_liste ($requete, "notpl_id", "nom", $select_name, $onchange, $selected_id, 0, $msg["notice_tpl_list_default"], 0,$msg["notice_tpl_list_default"], 0) ;
	}
		
	function show_eval($notice_id=0) {
		global $notice_tpl_eval;
		global $deflt2docs_location;
		
		if(!$notice_id)$notice_id=$this->id_test;
		$notice_tpl_gen=new notice_tpl_gen($this->id); 
		$tpl= $notice_tpl_gen->build_notice($notice_id,$deflt2docs_location); 
		$form = str_replace("!!tpl!!",	$tpl, $notice_tpl_eval);		
		
		return $form;
	}	

} // fin class 


