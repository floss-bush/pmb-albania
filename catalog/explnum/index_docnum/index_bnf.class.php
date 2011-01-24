<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: index_bnf.class.php,v 1.1 2010-12-02 08:33:45 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");
require_once($class_path."/xml_dom.class.php");
/**
 * Classe qui permet la gestion de l'indexation des fichiers de la BNF
 */
class index_bnf{
	
	var $fichier='';
	var $zip = "";
	
	/**
	 * Constructeur
	 */
	function index_bnf($filename){
		$this->fichier = $filename;
	}
	
	/**
	 * Récupération du texte à indexer dans l'archive
	 */
	function get_text($filename){
		$this->zip = zip_open($filename);
 		if ($this->zip) {
			while ($zip_entry = zip_read($this->zip)) {
				$t = array();
				$tab = explode("/",dirname(zip_entry_name($zip_entry)));
 				$type_images_doc_num = $tab[count($tab)-1];
 				if($type_images_doc_num == "X"){
 					if(zip_entry_open($this->zip, $zip_entry, "r")) {
						$xmlGz=zip_entry_read($zip_entry,zip_entry_filesize($zip_entry));
						$tmpfile=tempnam("/tmp","ocr");
						@file_put_contents($tmpfile,$xmlGz);
						ob_start();
						readgzfile($tmpfile);
						$xml=ob_get_clean();
						$xml_dom = new xml_dom($xml, "iso-8859-1");
						$textBlocs = @$xml_dom->get_nodes("alto/Layout/Page/PrintSpace/TextBlock");	
						if($textBlocs){
							foreach($textBlocs as $textBloc){
								$textlines = $xml_dom->get_nodes("TextLine",$textBloc);
								foreach($textlines as $textline){
									$strings = $xml_dom->get_nodes("String",$textline);
									foreach($strings as $string){
										$attrs = $xml_dom->get_attributes($string);
										foreach($attrs as $attr=>$value){
											if($attr == 'CONTENT') $texte_final.= " ".$value;
										}
									}		
								}
							}
						}
 					}	
 				}
			}
 		}
		return $texte_final;
	}
}
?>