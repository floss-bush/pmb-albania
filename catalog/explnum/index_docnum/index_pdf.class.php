<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: index_pdf.class.php,v 1.3 2010-07-09 15:12:40 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

/**
 * Classe qui permet la gestion de l'indexation des fichiers PDF
 */
class index_pdf{
	
	var $fichier='';
	
	function index_pdf($filename){
		$this->fichier = $filename;
	}
	
	/**
	 * Méthode qui retourne le texte à indexer des pdf
	 */
	function get_text($filename){
		global $charset;
		
		$fp = popen("pdftotext -enc UTF-8 ".$filename." -", "r");
		while(!feof($fp)){
			$line = fgets($fp,4096); 
			$texte .= $line;
			// Si trop gros, il faudra faire ceci, ou pas:
			// if(strlen($texte)>65536) break;
		}
		pclose($fp);
	
		if($charset != "utf-8"){
			return utf8_decode($texte);
		}
		return $texte;
	}
}
?>