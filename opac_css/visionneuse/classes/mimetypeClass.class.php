<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mimetypeClass.class.php,v 1.3 2010-07-05 08:48:31 arenou Exp $

require_once("$visionneuse_path/classes/XMLClass.class.php");

class mimetypeClass extends XMLClass{
	var $defaultMimetype=array();	//tableau associatif (mimetype => class)  
	var $repertoire;				//répertoire des classes d'affichages
	var $currentRep;				//rep courant 
	var $analyseur;					//parseur
	var $mimetypeFiles;				//tableau de l'ensemble des manifest   (class => manifest)
	var $classMimetypes;			//tableau associatif des mimetypes supportés par chaque classe (class => (mimetype1,mimetype2,...))
	var $mimetypeClasses;			//tableau associatif des classes dispo pour chaque mimetype (mimetype => (class1,class2,...))
	var $descriptions;				//tableau associatif des descriptions de chaque classe (class => desc)
	var $screenshoots;				//tableau associatif des screenshoots (class => url) 
	
	
    function mimetypeClass($repertoire){
    	$this->repertoire = $repertoire;
    	$this->lireRep($this->repertoire);
    	$this->invertMimetypeTab();
    }
    
	//Méthodes
	function debutBalise($parser, $nom, $attributs){
		global $_starttag; $_starttag=true;
		if($nom == 'MANIFEST' && $attributs['NAME']){
			$this->currentClass = $attributs['NAME'];
		}
		if($nom == 'MIMETYPE' && $attributs['NAME']){
			$this->classMimetypes[$this->currentClass][]=$attributs['NAME'];
		}
		if($nom == 'DESC' && $attributs['MSG']){
			$this->descriptions[$this->currentClass]=$attributs['MSG'];
		}
		if($nom == 'SCREENSHOOT' && $attributs['URL']){
			$this->screenshoots[$this->currentClass]=$attributs['URL'];
		}
	}
	
	//on fait tout dans la méthode débutBalise....
	function finBalise($parser, $nom){//besoin de rien
	}   
	function texte($parser, $data){//la non plus
	}
	
	function analyser($file){
 		global $charset;
		
		if (!($fp = @fopen($file , "r"))) {
			die("impossible d'ouvrir le fichier $file");
			}
		$data = fread ($fp,filesize($file));

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
			die( sprintf( "erreur XML %s à la ligne: %d ( $file )\n\n",
			xml_error_string(xml_get_error_code( $this->analyseur ) ),
			xml_get_current_line_number( $this->analyseur) ) );
		}

		xml_parser_free($this->analyseur);
 	}
 
  	function lireRep($rep){
  		
		$dh = opendir($rep);
		if (!$dh) return;
		while (($file = readdir($dh)) !== false){
			//on évite les repertoires système...
			if ($file != "." && $file != "..") {
				//si c'est un répertoire, on est sur un sous-dossier de mimtypes qui contient une classe et son manisfest
				if(is_dir($rep.$file)){
					if (file_exists($rep.$file."/manifest.xml")){
						$this->analyser($rep.$file."/manifest.xml");
					}
				}
			}
		}	
		closedir($dh);
	}

	function invertMimetypeTab(){
		foreach($this->classMimetypes as $class => $mimetypes){
			foreach($mimetypes as $mimetype){
				$exist = false;
				for($i=0 ; $i<sizeof($this->mimetypeClasses[$mimetype]);$i++){
					if($this->mimetypeClasses[$mimetype][0] == $class)
					$exist = true;
				}
				if ($exist === false) $this->mimetypeClasses[$mimetype][] = $class;
			}
		}
	}
}
?>