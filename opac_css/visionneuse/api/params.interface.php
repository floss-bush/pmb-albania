<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: params.interface.php,v 1.2 2010-07-05 08:48:31 arenou Exp $
 
 //on défini les méthodes à implémenter pour une classe de paramétrage...

interface params{
 	//renvoi un paramètre
 	function getParam($parameter);
 	//renvoi le nombre de documents
 	function getNbDocs();
 	//renvoi le document courant
 	function getCurrentDoc();
 	//renvoi le suivant
 	function getDoc($numDoc);
}

class base_params implements params {
	var $listeDocs = array();		//tableau de documents
	var $listeMimetypes = array();	//tableau listant les différents mimetypes des documents
	var $current = 0;				//position courante dans le tableau
	var $currentDoc = "";			//Document courant
	var $currentMimetype = "";		//mimetype courant
	var $params;					//tableau de paramètres utiles pour la recontructions des requetes...et même voir plus
	var $position = 0;				//
	var $listeBulls = array();
	var $listeNotices = array();
	
	function getParam($parameter){
		return $this->params[$parameter];
	}
	
	function getNbDocs(){
		return sizeof($this->listeDocs);
	}
	
	function getCurrentDoc(){
		return $this->currentDoc;
	}

	//renvoi un document précis sinon renvoi faux
 	function getDoc($numDoc){
 		if($numDoc >= 0 && $numDoc <= $this->getNbDocs()-1){
 			$this->current = $numDoc;
 			return $this->getCurrentDoc();
 		}else return false;
 	}
	
 	function isInCache($id){
 		global $visionneuse_path;
 		return file_exists($visionneuse_path."/temp/$id");
  	}
 	
 	function setInCache($id,$data){
 		global $visionneuse_path;
 		file_put_contents($visionneuse_path."/temp/$id",$data);	
 	}
 	
 	function readInCache($id){
 		global $visionneuse_path;
  		$data = "";
  		$data = file_get_contents($visionneuse_path."/temp/$id");	
 		return $data;	
 	}
 	
 	function cleanCache(){
 		global $visionneuse_path;

	    $dh = opendir($visionneuse_path."/temp/");
	    if (!$dh) return;
	    $files = array();
	    $totalSize = 0;
	
	    while (($file = readdir($dh)) !== false){
	        if ($file != "." && $file != "..") {
		    	$stat = stat($visionneuse_path."/temp/".$file);
	        	$files[$file] = array("mtime"=>$stat['mtime']);
	        	$totalSize += $stat['size'];
	        }
	    }
 		closedir($dh);
		$deleteList = array();
		foreach ($files as $file => $stat) {
			//si le dernier accès au fichier est de plus de 3h, on vide...
			if( time() - $stat["mtime"] > (3600*3))
				unlink($visionneuse_path."/temp/".$file);
		}
 	}
}
?>