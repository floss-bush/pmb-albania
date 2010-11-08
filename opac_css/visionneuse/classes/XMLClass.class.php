<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: XMLClass.class.php,v 1.2 2010-07-05 08:48:31 arenou Exp $

class XMLClass {
	var $defaultMimetypeFile;			//xml décrivant les classes à utilisé par défaut
	var $defaultMimetype;				//tab résultant du xml par defaut	
	var $mimetypeFiles = array();		//tableau associatif des manisfest par classes d'affichage 
	var $classMimetypes = array();		//tableau associatif résultant des différents manifest, décrivant les mimetypes supportés par chaque classe
		
    function XMLClass($file=""){
    	$this->file = $file;   	
	}
    
 	//Méthodes
 	function defaultMimetypeParse($parser, $nom, $attributs){
		global $_starttag; $_starttag=true;
		if($nom == 'MIMETYPE' && $attributs['TYPE'] && $attributs['CLASS']){
			$this->defaultMimetype[$attributs['TYPE']] = $attributs['CLASS'];
		}
		if($nom == 'MIMETYPES'){
			$this->defaultMimetype = array();
		}
	}

	//on fait tout dans la méthode débutBalise....
	function finBalise($parser, $nom){//besoin de rien
	}   
	function texte($parser, $data){//la non plus
	}
	
	function analyser($file=""){
 		global $charset;
		
		if($file != "") $xmlToParse = $file;
		else $xmlToParse = $this->file;
		
		if (!($fp = @fopen($xmlToParse , "r"))) {
			die("impossible d'ouvrir le fichier $xmlToParse");
			}
		$data = fread ($fp,filesize($xmlToParse));

 		$rx = "/<?xml.*encoding=[\'\"](.*?)[\'\"].*?>/m";
		if (preg_match($rx, $data, $m)) $encoding = strtoupper($m[1]);
			else $encoding = "ISO-8859-1";
		
 		$this->analyseur = xml_parser_create($encoding);
 		xml_parser_set_option($this->analyseur, XML_OPTION_TARGET_ENCODING, $charset);		
		xml_parser_set_option($this->analyseur, XML_OPTION_CASE_FOLDING, true);
		xml_set_object($this->analyseur, &$this);
		xml_set_element_handler($this->analyseur, "debutBalise", "finBalise");
		xml_set_character_data_handler($this->analyseur, "texte");
	
		fclose($fp);

		if ( !xml_parse( $this->analyseur, $data, TRUE ) ) {
			die( sprintf( "erreur XML %s à la ligne: %d ( $xmlToParse )\n\n",
			xml_error_string(xml_get_error_code( $this->analyseur ) ),
			xml_get_current_line_number( $this->analyseur) ) );
		}

		xml_parser_free($this->analyseur);
 	}
}
?>