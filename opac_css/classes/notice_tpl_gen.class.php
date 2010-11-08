<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_tpl_gen.class.php,v 1.2 2010-09-14 14:57:10 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once ($class_path . "/parse_format.class.php");
require_once ($class_path . "/notice_info.class.php");

class notice_tpl_gen {
	
	// ---------------------------------------------------------------
	//		propriétés de la classe
	// ---------------------------------------------------------------	
	var $id;		// MySQL id in table 'notice_tpl'
	var $name;		// nom du template
	var $comment;	// description du template
	var $code ; 	// Code du template
	
	// ---------------------------------------------------------------
	//		constructeur
	// ---------------------------------------------------------------
	function notice_tpl_gen($id=0) {			
		$this->id = $id;
		$this->getData();
	}
	
	// ---------------------------------------------------------------
	//		getData() : récupération infos 
	// ---------------------------------------------------------------
	function getData() {
		global $dbh;

		$this->name = '';			
		$this->comment = '';
		$this->code =array();
		if($this->id) {
			$requete = "SELECT * FROM notice_tpl WHERE notpl_id='".$this->id."' LIMIT 1 ";
			$result = @mysql_query($requete, $dbh);
			if(mysql_num_rows($result)) {
				$temp = mysql_fetch_object($result);				
				$this->name	= $temp->notpl_name;
				$this->comment	= $temp->notpl_comment;
					
				// récup code		
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
	
	function build_notice($id_notice,$location=0){
		global $dbh,$parser_environnement;
		
		$parser=new parse_format('notice_tpl.inc.php');			
		
		$requete = "SELECT typdoc, niveau_biblio FROM notices WHERE notice_id='".$id_notice."' LIMIT 1 ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);				
			$typdoc	= $temp->typdoc;			
			$niveau_biblio	= $temp->niveau_biblio;				
			//$niveau_hierar	= $temp->niveau_hierar;		
		} else return "";
	
		// Recherche du code à appliquer (du particulier au général)
		if($this->code[$location][$typdoc][$niveau_biblio]) {
			$code=$this->code[$location][$typdoc][$niveau_biblio];
		} elseif ($this->code[$location][$typdoc][0]) {
			$code=$this->code[$location][$typdoc][0];
		} elseif ($this->code[$location][0][0]) {
			$code=$this->code[$location][0][0];
		} elseif ($this->code[0][$typdoc][$niveau_biblio]) {
			$code=$this->code[0][$typdoc][$niveau_biblio];
		} elseif ($this->code[0][$typdoc][0]) {
			$code=$this->code[0][$typdoc][0];
		} elseif ($this->code[0][0][0]) {
			$code=$this->code[0][0][0];
		} else return "";
		
		$temp = mysql_fetch_object($result);							
		$parser->cmd = $code;
		$parser_environnement['id_notice']=$id_notice;
		return $parser->exec_cmd();		
	}
	
	function gen_tpl_select($select_name="notice_tpl", $selected_id=0, $onchange="") {		
		global $msg;
		
		$requete = "SELECT notpl_id, concat(notpl_name,'. ',notpl_comment) as nom FROM notice_tpl where notpl_show_opac=1 ORDER BY notpl_name ";
		return gen_liste ($requete, "notpl_id", "nom", $select_name, $onchange, $selected_id, 0, $msg["notice_tpl_list_default"], 0,$msg["notice_tpl_list_default"], 0) ;
	}
} // fin class 


