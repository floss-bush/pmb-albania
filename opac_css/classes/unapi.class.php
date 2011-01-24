<?php
// +--------------------------------------------------------------------------+
// | PMB est sous licence GPL, la réutilisation du code est cadrée            |
// +--------------------------------------------------------------------------+
// $Id: unapi.class.php,v 1.2 2010-12-23 09:12:45 arenou Exp $

require_once ($include_path."/parser.inc.php");

class unapi {
	var $id = 0;	//id de la notice
	var $format;	//format demandé
	var $notice;	//notice dans le format demandé
	var $formats;	//tableau regroupant les infos du XML

    function unapi($format,$id) {
    	$this->format = $format;
    	$this->id = $id;
    	$this->formats = array();
    	
    	$this->getFormats();
    	
    	if($this->format){
    		if($this->id) $this->getNotice();
    	}else{
    		$this->sendFormats();
    	}   	
    }
    
	function getFormats(){
    	global $charset;
    	global $base_path;
    	
    	//l'entete du xml    	
		$this->xml = "<?xml version='1.0' encoding='$charset'?>
	<formats ".($this->id ? "id='".$this->id."'": "").">";

		if (file_exists("$base_path/admin/convert/imports/zotero_subst.xml"))
			$fic_zotero = "$base_path/admin/convert/imports/zotero_subst.xml";
		else $fic_zotero = "$base_path/admin/convert/imports/zotero.xml";	
		_parser_($fic_zotero, array("FORMAT" => array('obj' => $this,'method' => "getFormatInfo")), "FORMATS");
		$this->xml .= "
	</formats>"; 			
	}   
   
    function getFormatInfo($format){
    	global $charset;
    	
    	$this->formats[$format['NAME']] = $format;
    	$this->xml .= "
		<format name='".$format['NAME']."' type='".$format['TYPE']."'/>";
    }
    
     function sendFormats(){
     	global $charset;
  
		header("Content-type: application/xml; charset=" .$charset, true);
		print $this->xml;
    }
       
    function getNotice(){
    	global $charset;

		//on récupère l'identifiant du l'export associé au format
		$this->typeExport = start_export::get_id_by_path($this->formats[$this->format]['TRANSFORM']);
		
		//on a ce qu'il faut, on récupère la notice dans le bon format
    	$this->notice = cree_export_notices(array($this->id),$this->typeExport,1);
	
		//on envoi le bon mimetype
		if($this->formats[$this->format]['TYPE'])
			header("Content-type: ".$this->formats[$this->format]['TYPE']."; charset=" .$charset, true);
		//on affiche la notice
		print $this->notice;
    }
}
?>