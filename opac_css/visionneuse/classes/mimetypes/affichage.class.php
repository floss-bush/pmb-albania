<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: affichage.class.php,v 1.4 2010-07-06 12:11:50 arenou Exp $

class affichage {
	var $doc;				//le document numérique à afficher
	var $params;			//paramètres éventuels
	var $driver;			//driver de la visionneuse

    function affichage($doc) {
    	$this->doc = $doc; 
    	$this->driver = $doc->driver;
    	$this->params = $doc->params;
    }
    
    function fetchDisplay(){
    	global $visionneuse_path,$base_path;
     	//le titre
    	$this->toDisplay["titre"] = $this->doc->titre;
    	//le pdf
    	//$this->toDisplay["doc"] = "<iframe src='".$visionneuse_path."/pdf.php?id=".$this->doc->id."' width='".$this->params["maxX"]."' height='".$this->params["maxY"]."'></iframe>";
    	$this->toDisplay["doc"] = "<iframe name='docnum' src='doc_num.php?explnum_id=".$this->doc->id."' width='".$this->driver->getParam("maxX")."' height='".$this->driver->getParam("maxY")."'></iframe>";
		//la description
		$this->toDisplay["desc"] = $this->doc->desc;
		//toPost
		return $this->toDisplay;
    }
}
?>