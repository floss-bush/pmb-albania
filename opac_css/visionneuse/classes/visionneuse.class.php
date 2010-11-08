<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: visionneuse.class.php,v 1.5 2010-07-08 15:28:34 arenou Exp $

require_once($visionneuse_path."/api/params.interface.php");
require_once($visionneuse_path."/classes/docNum.class.php");
require_once($visionneuse_path."/api/pmb/pmb.class.php");
require_once($visionneuse_path."/includes/templates/visionneuse.tpl.php");
require_once($visionneuse_path."/classes/messages.class.php");

class visionneuse {
	var $visionneuse_path = "";
	var $classParam;				//classe de paramétrage de la visionneuse
	var $docToRender;				
	var $mimetypeClass;
	var $message;					//messages localisés

    function visionneuse($driver,$visionneuse_path,$lvl="visionneuse",$lang="fr_FR",$tab_params=array()){
	   	$this->visionneuse_path = $visionneuse_path;
	  	//on instancie la bonne classe
    	$this->classParam = new $driver($tab_params,$this->visionneuse_path);
    	//on instancie également les messages localisés...
    	$this->message = new message($this->visionneuse_path."/includes/message/$lang.xml");
    	switch ($lvl){
    		case "visionneuse" :
    			$this->display();
    			$this->classParam->cleanCache();
    			break;
    		case "afficheur" :
    			$this->classParam->getDocById($tab_params["explnum"]);
				$this->renderDoc();
    			break;
    	}
    }
    
    function display(){
		global $visionneuse;
		global $charset;
		//on commence par remettre les champs cachés du formulaire...
		$hiddenFields = "";
		foreach($this->classParam->params as $key => $value){
			//sauf les paramètres qui n'ont pas été postés, mais créés à la main ou modifiés plus tard en javascript... 
			if ($key != "position" && $key != "start"){
			$hiddenFields .="
			<input type='hidden' name='$key' id='$key' value='".htmlentities(stripslashes($value),ENT_QUOTES,$charset)."' />";
			}
		}
		$visionneuse = str_replace("!!hiddenFields!!",$hiddenFields,$visionneuse);

		//et c'est parti
		//on s'occupe en premier du conteneur du document
		$visionneuse = str_replace("!!height!!",$this->classParam->getParam("maxY"),$visionneuse);
		//on insère le contenu propre au document;
		$docNum = new docNum($this->classParam->getCurrentDoc(),$this->classParam);
		$docToDisplay = $docNum->fetchDisplay();
		foreach($docToDisplay as $key => $value){
			if($key != "post")
				$visionneuse = str_replace("!!$key!!",$value,$visionneuse);
		}
		//maintenant le kit de survie du navigateur
		$visionneuse = str_replace("!!position!!",$this->classParam->current,$visionneuse);
		if($this->classParam->getNbDocs()==1){
			$visionneuse = str_replace("!!previous_style!!","none;",$visionneuse);
			$visionneuse = str_replace("!!next_style!!","none;",$visionneuse);			
    	}elseif($this->classParam->current ==0){
			$visionneuse = str_replace("!!previous_style!!","none;",$visionneuse);
			$visionneuse = str_replace("!!next_style!!","block-inline;",$visionneuse);
		}elseif($this->classParam->current == sizeof($this->classParam->listeDocs)-1){
			$visionneuse = str_replace("!!previous_style!!","block-inline;",$visionneuse);
			$visionneuse = str_replace("!!next_style!!","none;",$visionneuse);		
		}else{
			$visionneuse = str_replace("!!previous_style!!","block-inline;",$visionneuse);
			$visionneuse = str_replace("!!next_style!!","block-inline;",$visionneuse);					
		}
		$visionneuse = str_replace("!!max_pos!!",$this->classParam->getNbDocs()-1,$visionneuse);		
		$visionneuse = str_replace("!!current_position!!",($this->classParam->current+1)." / ".$this->classParam->getNbDocs(),$visionneuse);
	
		//on localise les messages
		$visionneuse = str_replace("!!close!!",$this->message->table['close'],$visionneuse);
		$visionneuse = str_replace("!!fullscreen!!",$this->message->table['fullscreen'],$visionneuse);
		$visionneuse = str_replace("!!normal!!",$this->message->table['normal'],$visionneuse);
		$visionneuse = str_replace("!!previous!!",$this->message->table['previous'],$visionneuse);
		$visionneuse = str_replace("!!next!!",$this->message->table['next'],$visionneuse);
		
		//tout est bon, on affiche le tout...
		print $visionneuse;		
    }		
    
    function renderDoc(){
    	$docNum = new docNum($this->classParam->getCurrentDoc(),$this->classParam);
    	$docNum->render();
    }
}
?>