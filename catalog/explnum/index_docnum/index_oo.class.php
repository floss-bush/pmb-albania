<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: index_oo.class.php,v 1.2 2010-12-01 16:09:12 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/zip.class.php");

/**
 * Classe qui permet la gestion de l'indexation des fichiers OpenOffice
 */
class index_oo{
	
	var $fichier='';
	
	function index_oo($filename){
		$this->fichier = $filename;
	}
	
	/**
	 * Méthode qui retourne le texte à indexer des docs OpenOffice
	 */
	function get_text($filename){
		global $charset;
		
		$zip = new zip($filename);
		$texte = $zip->getFileContent("content.xml");		

		//On enlève toute les balises offices
		preg_match_all("(<([^<>]*)>)",$texte,$result);	
		for($i=0;$i<sizeof($result[0]);$i++){
			$texte = str_replace($result[0][$i]," ",$texte);
		}
		
		$texte = str_replace("&apos;","'",$texte);
		$texte = str_replace("&nbsp;"," ",$texte);
		if($charset != "utf-8"){
			$texte =  utf8_decode($texte);		
		}
		$texte = html_entity_decode($texte,ENT_QUOTES,$charset);
		return $texte;
		
	}
}
?>