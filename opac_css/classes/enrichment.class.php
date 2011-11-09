<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: enrichment.class.php,v 1.2.2.1 2011-06-24 08:16:02 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/connecteurs.class.php");
require_once($class_path."/marc_table.class.php");
require_once($include_path."/parser.inc.php");

class enrichment {
	var $enhancer = array();
	var $active = array();
	var $typnotice = "";
	var $typdoc = "";
	var $catalog;
	var $enrichmentsTabHeaders = array();

    function enrichment($typnotice="",$typdoc="") {
    	global $base_path;
    	
    	$this->typnotice = $typnotice;
    	$this->typdoc = $typdoc;
    	$this->fetch_sources();
    	$this->fetch_data();
    }
    
	//On récupère la liste des sources dispos pour enrichir
    function fetch_sources(){
  		global $base_path;
  		
  		$connectors = new connecteurs();
  		$this->catalog = $connectors->catalog;
    	foreach ($connectors->catalog as $id=>$prop) {
			$comment=$prop['COMMENT'];
			//Recherche du nombre de sources
			$n_sources=0;
			if($prop['ENRICHMENT'] == "yes"){
				if (is_file($base_path."/admin/connecteurs/in/".$prop['PATH']."/".$prop['NAME'].".class.php")) {
					require_once($base_path."/admin/connecteurs/in/".$prop['PATH']."/".$prop['NAME'].".class.php");
					eval("\$conn=new ".$prop['NAME']."(\"".$base_path."/admin/connecteurs/in/".$prop['PATH']."\");");
					$conn->get_sources();
					foreach($conn->sources as $source_id=>$s) {
						if($s['ENRICHMENT'] == 1){
	   						$this->enhancer[] = array(
	   							'id' =>$s['SOURCE_ID'],
	   							'name' =>$s['NAME']
	   						);
						}
					}
	    		}
			}
    	}  	
    }

    //Récupération des données existantes
	function fetch_data(){
    	$rqt = "select * from sources_enrichment";
    	if($this->typnotice && $this->typdoc){
    		$rqt.= " where (source_enrichment_typnotice like '".$this->typnotice."' and source_enrichment_typdoc like '') or (source_enrichment_typnotice like '".$this->typnotice."' and source_enrichment_typdoc like '".$this->typdoc."')";
    	}  
    	$res = mysql_query($rqt);
    	if(mysql_num_rows($res)){
    		while($r= mysql_fetch_object($res)){
    			$this->active[$r->source_enrichment_typnotice.$r->source_enrichment_typdoc][] = $r->source_enrichment_num;
    		}
    	}
    }
    
	//retourne les éléments à rajouter dans le head, les calculs aux besoins;
	function getHeaders(){
		global $include_path;
		
		
		if(!$this->enrichmentsTabHeaders) $this->generateHeaders();
		//l'enrichissement se fait en ajax...
		$this->enrichmentsTabHeaders[]="
	<!-- Enrichissement de notice en Ajax-->
	<script type='text/javascript' src='$include_path/javascript/enrichment.js'></script>";
		//si les notices ne sont pas dépliables, on lance le tout à la fin du chargement de la page...
	//	$this->enrichmentsTabHeaders[]="<script type='text/javascript'>getAllEnrichment();</script> ";
		return implode("\n",$this->enrichmentsTabHeaders);
	}
	
	//Méthode qui génère les éléments à insérer dans le header pour le bon fonctionnement des enrichissements
	function generateHeaders(){
		global $base_path;

		$this->enrichmentsTabHeaders =array();
		$alreadyIncluded = array();
		foreach($this->active as $type => $sources){
			foreach($sources as $source_id){
				if(!in_array($source_id,$alreadyIncluded)){
					//on récupère les infos de la source nécessaires pour l'instancier
					$name = connecteurs::get_class_name($source_id);
					foreach($this->catalog as $connector){
						if($connector['NAME'] == $name){
							if (is_file($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php")){
								require_once($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php");
								$conn = new $name($base_path."/admin/connecteurs/in/".$connector['PATH']);
								$this->enrichmentsTabHeaders = array_merge($this->enrichmentsTabHeaders,$conn->getEnrichmentHeader());
								$this->enrichmentsTabHeaders = array_unique($this->enrichmentsTabHeaders);
							}
						}
					}
					$alreadyIncluded[]=$source_id;
				}
			}
		}
	}
	
	function getTypeOfEnrichment($notice_id){
		global $base_path;
		global $msg;
		
		$this->parseType();
		if($this->active[$this->typnotice.$this->typdoc]) $type = $this->typnotice.$this->typdoc;
		else $type = $this->typnotice;
		if($this->active[$type]){
			foreach($this->active[$type] as $source_id){
				//on récupère les infos de la source nécessaires pour l'instancier
				$name = connecteurs::get_class_name($source_id);	
				foreach($this->catalog as $connector){
					if($connector['NAME'] == $name){
						if (is_file($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php")){
							require_once($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php");
							$conn = new $name($base_path."/admin/connecteurs/in/".$connector['PATH']);
							$info = $conn->getTypeOfEnrichment($source_id);
							for($i=0 ; $i<count($info['type']) ; $i++){
								if(!is_array($info['type'][$i])) {
									$info['type'][$i] = array(
										'code' => $info['type'][$i], 
										'label' => $msg[substr($this->type[$info['type'][$i]],4)]
									);
								}
								
							}
							$infos[] = $info;
						}
					}
				}			
			}
		}
		return $infos;		
	}
		
	function getEnrichment($notice_id,$enrichmentType ="",$enrichPage=1){
		global $base_path;
		$infos = array();
		if($this->active[$this->typnotice.$this->typdoc]) $type = $this->typnotice.$this->typdoc;
		else $type = $this->typnotice;
		if($this->active[$type]){
			foreach($this->active[$type] as $source_id){
				//on récupère les infos de la source nécessaires pour l'instancier
				$name = connecteurs::get_class_name($source_id);	
				foreach($this->catalog as $connector){
					if($connector['NAME'] == $name){
						if (is_file($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php")){
							require_once($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php");
							$conn = new $name($base_path."/admin/connecteurs/in/".$connector['PATH']);
							$eTypes = $conn->getTypeOfEnrichment($source_id);
							if($enrichmentType){
								$bool = false;
								for($i=0 ; $i<count($eTypes['type']) ; $i++){
									if(is_array($eTypes['type'][$i])){
										if($enrichmentType == $eTypes['type'][$i]['code']) $bool =true;
									}else{
										if($enrichmentType == $eTypes['type'][$i]) $bool =true;
									}
								}
								if(!$enrichmentType || $bool)
									$infos[] = $conn->getEnrichment($notice_id,$source_id,$enrichmentType,$enrichPage);	
							}
						}
					}
				}			
			}
		}
		return $infos;
	}
	
	function parseType(){
		global $include_path,$lang;
	
		$file = $include_path."/enrichment/categories.xml";
		$xml = file_get_contents($file);
		$types= _parser_text_no_function_($xml,"XMLLIST");
		foreach($types['ENTRY'] as $type){
			$this->type[$type['CODE']] = $type['value'];
		}
	}
}
?>