<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: index_txt.class.php,v 1.1 2009-06-11 15:57:52 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

/**
 * Classe qui permet la gestion de l'indexation des fichiers texte (.txt)
 */
class index_txt{
	
	var $fichier='';
	
	/**
	 * Constructeur
	 */
	function index_html($filename){
		$this->fichier = $filename;
	}
	
	/**
	 * Récupération du texte à indexer dans le fichier texte (.txt)
	 */
	function get_text($filename){
		
		$fp = fopen($filename, "r");
		while(!feof($fp)){
			$line = fgets($fp,4096); 
			$texte .= $line;
		}
		fclose($fp);

		return $texte;
	}
}
?>
