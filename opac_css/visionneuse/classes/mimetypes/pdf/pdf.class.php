<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pdf.class.php,v 1.5 2010-07-08 15:28:34 arenou Exp $

require_once($visionneuse_path."/classes/mimetypes/affichage.class.php");

class pdf extends affichage{
	var $doc;					//le document numérique à afficher
	var $driver;				//class driver de la visionneuse
	var $params;				//paramètres éventuels
	var $toDisplay= array();	//tableau des infos à afficher	
	var $tabParam = array();	//tableau décrivant les paramètres de la classe
	var $parameters = array();	//tableau des paramètres de la classe
 
    function pdf($doc=0) {
    	if($doc){
    		$this->doc = $doc; 
    		$this->driver = $doc->driver;
    		$this->params = $doc->params;
    		$this->getParamsPerso();
    	}
    }
    
    function fetchDisplay(){
    	global $visionneuse_path,$base_path;
     	//le titre
    	$this->toDisplay["titre"] = $this->doc->titre;
    	//le pdf
    	$this->toDisplay["doc"] = "<iframe name='docnum' src='visionneuse.php?lvl=afficheur&explnum=".$this->doc->id.$this->doc->search."' width='".$this->parameters["size_x"]."' height='".$this->parameters["size_y"]."'></iframe>";
		//la description
		$this->toDisplay["desc"] = $this->doc->desc;
		return $this->toDisplay;  	
    }
    
    function render(){
    	header("Content-Type: application/pdf");
    	print $this->driver->openCurrentDoc();
    }
    
    function getTabParam(){
		$this->tabParam = array(
			"size_x"=>array("type"=>"text","name"=>"size_x","value"=>$this->parameters['size_x'],"desc"=>"Largeur du document"),
			"size_y"=>array("type"=>"text","name"=>"size_y","value"=>$this->parameters['size_y'],"desc"=>"Hauteur du document"),
		);
       	return $this->tabParam;
    }
    
	function getParamsPerso(){
		$params = $this->driver->getClassParam('pdf');
		$this->unserializeParams($params);
		if($this->parameters['size_x'] == 0) $this->parameters['size_x'] = $this->driver->getParam("maxX");
		if($this->parameters['size_y'] == 0) $this->parameters['size_y'] = $this->driver->getParam("maxY");
	}
	
	function unserializeParams($paramsToUnserialized){
		$this->parameters = unserialize($paramsToUnserialized);
		return $this->parameters;
	}
	
	function serializeParams($paramsToSerialized){
		$this->parameters =$paramsToSerialized;
		return serialize($paramsToSerialized);
	}
}
?>