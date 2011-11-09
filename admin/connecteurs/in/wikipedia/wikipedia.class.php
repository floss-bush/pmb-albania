<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: wikipedia.class.php,v 1.1 2011-04-15 15:16:03 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");
require_once($class_path."/curl.class.php");
require_once($class_path."/nusoap/nusoap.php");

class wikipedia extends connector {
	//Variables internes pour la progression de la récupération des notices
	var $del_old;				//Supression ou non des notices dejà existantes
	
	var $profile;				//Profil Amazon
	var $match;					//Tableau des critères UNIMARC / AMAZON
	var $current_site;			//Site courant du profile (n°)
	var $searchindexes;			//Liste des indexes de recherche possibles pour le site
	var $current_searchindex;	//Numéro de l'index de recherche de la classe
	var $match_index;			//Type de recherche (power ou simple)
	var $types;					//Types de documents pour la conversino des notices
	
	//Résultat de la synchro
	var $error;					//Y-a-t-il eu une erreur	
	var $error_message;			//Si oui, message correspondant
	
    function wikipedia($connector_path="") {
    	parent::connector($connector_path);
    }
    
    function get_id() {
    	return "wikipedia";
    }
    
    //Est-ce un entrepot ?
	function is_repository() {
		return 2;
	}
    
    function unserialize_source_params($source_id) {
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			$params["PARAMETERS"]=$vars;
		}
		return $params;
    }
    
    function get_libelle($message) {
    	if (substr($message,0,4)=="msg:") return $this->msg[substr($message,4)]; else return $message;
    }
    
    function source_get_property_form($source_id) {
		return "";
    }
    
    function make_serialized_source_properties($source_id) {
    	$this->sources[$source_id]["PARAMETERS"]=serialize(array());
	}
	
	//Récupération  des proriétés globales par défaut du connecteur (timeout, retry, repository, parameters)
	function fetch_default_global_values() {
		$this->timeout=5;
		$this->repository=2;
		$this->retry=3;
		$this->ttl=1800;
		$this->parameters="";
	}
	
	 //Formulaire des propriétés générales
	function get_property_form() {
		return "";
	}
    
    function make_serialized_properties() {
    	global $accesskey, $secretkey;
		//Mise en forme des paramètres à partir de variables globales (mettre le résultat dans $this->parameters)
		$keys = array();
    	
    	$keys['accesskey']=$accesskey;
		$keys['secretkey']=$secretkey;
		$this->parameters = serialize($keys);
	}

	function enrichment_is_allow(){
		return true;
	}
	
	function getEnrichmentHeader(){
		$header= array();
		$header[]= "<!-- Script d'enrichissement pour wikipedia-->";
		return $header;
	}
	
	function getTypeOfEnrichment($source_id){
		$type['type'] = array(
			array( 
				'code' => "wiki",
				'label' => "Wikipédia"
			)
		);		
		$type['source_id'] = $source_id;
		return $type;
	}
	
	function getEnrichment($notice_id,$source_id,$type=""){
		$enrichment= array();
		//on renvoi ce qui est demandé... si on demande rien, on renvoi tout..
		switch ($type){
			case "wiki" :
			default :
				$enrichment['wiki']['content'] = $this->noticeInfos($notice_id);
				break;
		}		
		$enrichment['source_label']=$this->msg['gbooks_enrichment_source'];
		return $enrichment;
	}
	
	function noticeInfos($notice_id){
		
		$rqt = "select tit1 from notices where notice_id='$notice_id'";
		$res =mysql_query($rqt);
		if(mysql_num_rows($res)){
			$titre = mysql_result($res,0,0);
			$curl = new Curl();
			$xmlToParse = $curl->get("http://fr.wikipedia.org/w/api.php?action=query&meta=siteinfo&siprop=namespaces&format=xml");
			print $xmlToParse;
		}
	}
}
?>