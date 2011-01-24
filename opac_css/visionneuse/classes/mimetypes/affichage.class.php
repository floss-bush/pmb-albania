<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: affichage.class.php,v 1.8 2010-10-15 15:28:47 arenou Exp $

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
    	$this->toDisplay["doc"] = "<iframe name='docnum' id='docnum' src='doc_num.php?explnum_id=".$this->doc->id."' width='".$this->driver->getParam("maxX")."' height='".$this->driver->getParam("maxY")."'></iframe>";
		$this->toDisplay["doc"] .= 	"
		<script type='text/javascript'>
			window.onload = checkSize;
			function checkSize(){
				var iframe= document.getElementById('docnum');
				if (isNaN(iframe.width) || iframe.width/getFrameWidth() <= 0.9 || iframe.width/getFrameWidth() >= 1){
					iframe.width = '90%';
					iframe.height = ((getFrameHeight()-40-80)*0.9)+'px';
				}				
			}
		</script>";
		//la description
		$this->toDisplay["desc"] = $this->doc->desc;
		//toPost
		return $this->toDisplay;
    }
}
?>