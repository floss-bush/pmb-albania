<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: flexpdf.class.php,v 1.1.2.4 2011-05-16 20:19:26 gueluneau Exp $

require_once($visionneuse_path."/classes/mimetypes/affichage.class.php");

class flexpdf extends affichage{
	var $doc;					//le document numérique à afficher
	var $driver;				//class driver de la visionneuse
	var $params;				//paramètres éventuels
	var $toDisplay= array();	//tableau des infos à afficher	
	var $tabParam = array();	//tableau décrivant les paramètres de la classe
	var $parameters = array();	//tableau des paramètres de la classe
 
    function flexpdf($doc=0) {
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
    	//la visonneuse pdf
    	$this->toDisplay["doc"]="
    	<script type='text/javascript' src='$visionneuse_path/classes/mimetypes/flexpdf/flexpaper/js/flexpaper_flash_debug.js'></script>
    	<a href='doc_num.php?explnum_id=".$this->doc->id."'>Télécharger le document hors de la visionneuse</a>
    	<div id='flexpaperFrameViewer' style='margin:auto;display:block'></div>
    	<script type='text/javascript'> 
    			window.onload = function(){
					var iframe= document.getElementById('flexpaperFrameViewer');
					iframe.style.width = '".$this->parameters["size_x"]."%';
					iframe.style.height = ((getFrameHeight()-40-80)*".($this->parameters["size_y"]/100).")+'px';				
					var fp = new FlexPaperViewer(	
							 '$visionneuse_path/classes/mimetypes/flexpdf/flexpaper/FlexPaperViewer',
							 'flexpaperFrameViewer', { config : {
							 SwfFile : 'visionneuse.php?lvl=afficheur&explnum=".$this->doc->id."',
							 //SwfFile : 'visionneuse/temp/20.swf',
							 Scale : 0.6, 
							 ZoomTransition : 'easeOut',
							 ZoomTime : 0.5,
							 ZoomInterval : 0.2,
							 FitPageOnLoad : true,
							 FitWidthOnLoad : false,
							 PrintEnabled : ".($this->parameters["print_allowed"]?"true":"false").",
							 FullScreenAsMaxWindow : false,
							 ProgressiveLoading : true,
							 MinZoomSize : 0.2,
							 MaxZoomSize : 5,
							 SearchMatchAll : true,
							 InitViewMode : 'Portrait',
							 
							 ViewModeToolsVisible : true,
							 ZoomToolsVisible : true,
							 NavToolsVisible : true,
							 CursorToolsVisible : true,
							 SearchToolsVisible : true,
	  						
	  						 localeChain: 'fr_FR'
							}});
				}";
    	if ($this->doc->search) {
    		$this->toDisplay["doc"].="	
				window.onDocumentLoaded=function() {
					getDocViewer().searchText('".addslashes(substr($this->doc->search,9,strlen($this->doc->search)-10))."');
				}";
    	}
    	$this->toDisplay["doc"].="
	        </script>
	        
    	";
    	//if ($this->parameters['autoresize'] == 1)
		//la description
		$this->toDisplay["desc"] = $this->doc->desc;
		return $this->toDisplay;  	
    }
    
    function render(){
    	global $visionneuse_path;
    	$this->driver->cleanCache();
    	if (!$this->driver->isInCache($this->doc->id)) {
    		file_put_contents("$visionneuse_path/temp/".$this->doc->id.".pdf",$this->driver->openCurrentDoc());
    		//print "pdf2swf $visionneuse_path/temp/".$this->doc->id.".pdf -o $visionneuse_path/temp/".$this->doc->id.".swf -f -T 9 -t -s storeallcharacters";
    		exec($this->parameters["pdf2swf_path"]."/pdf2swf -f -T 9 -t -s storeallcharacters $visionneuse_path/temp/".$this->doc->id.".pdf -o $visionneuse_path/temp/".$this->doc->id.".swf");
    		$this->driver->setInCache($this->doc->id,file_get_contents("$visionneuse_path/temp/".$this->doc->id.".swf"));
    		unlink("$visionneuse_path/temp/".$this->doc->id.".swf");
    		unlink("$visionneuse_path/temp/".$this->doc->id.".pdf");
    	}
    	print $this->driver->openCurrentDoc();
    }
    
    function getTabParam(){
		$this->tabParam = array(
			"size_x"=>array("type"=>"text","name"=>"size_x","value"=>$this->parameters['size_x'],"desc"=>"Largeur du document en % de l'espace visible"),
			"size_y"=>array("type"=>"text","name"=>"size_y","value"=>$this->parameters['size_y'],"desc"=>"Hauteur du document en % de l'espace visible"),
			"print_allowed"=>array("type"=>"checkbox","name"=>"print_allowed","value"=>1,"desc"=>"Autoriser l'impression"),
			"pdf2swf_path"=>array("type"=>"text","name"=>"pdf2swf_path","value"=>$this->parameters['pdf2swf_path'],"desc"=>"Chemin de l'exécutable pdf2swf")
		);
       	return $this->tabParam;
    }
    
	function getParamsPerso(){
		$params = $this->driver->getClassParam('flexpdf');
		$this->unserializeParams($params);
		if($this->parameters['size_x'] == 0) $this->parameters['size_x'] = $this->driver->getParam("maxX");
		if($this->parameters['size_y'] == 0) $this->parameters['size_y'] = $this->driver->getParam("maxY");
		if(!$this->parameters['print_allowed']) $this->parameters['print_allowed'] = 0;
	}
	
	function unserializeParams($paramsToUnserialized){
		$this->parameters = unserialize($paramsToUnserialized);
		if(!$this->parameters['print_allowed']) $this->parameters['print_allowed'] = 0;
		return $this->parameters;
	}
	
	function serializeParams($paramsToSerialized){
		if(!$paramsToSerialized['print_allowed']) $paramsToSerialized['print_allowed'] = 0;
		$this->parameters =$paramsToSerialized;
		return serialize($paramsToSerialized);
	}
}
?>
