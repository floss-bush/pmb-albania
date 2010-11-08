<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: agnostic.class.php,v 1.1 2010-06-23 00:39:20 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");

class agnostic extends connector {
    
    function get_id() {
    	return "agnostic";
    }
    
    //Est-ce un entrepot ?
	function is_repository() {
		return 1;
	}
    
    function unserialize_source_params($source_id) {
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			$params["PARAMETERS"]=$vars;
		}
		return $params;
    }
    
    function source_get_property_form($source_id) {
		return "";
    }
    
    function make_serialized_source_properties($source_id) {
		$this->sources[$source_id]["PARAMETERS"]=array();
	}
	
	//Récupération  des proriétés globales par défaut du connecteur (timeout, retry, repository, parameters)
	function fetch_default_global_values() {
		$this->timeout=5;
		$this->repository=1;
		$this->retry=3;
		$this->ttl=1800;
		$this->parameters="";
	}
	
	//Formulaire des propriétés générales
	function get_property_form() {
		$this->fetch_global_properties();
		return "";
	}
	
	function make_serialized_properties() {
		$this->parameters="";
	}
	
		
	function cancel_maj($source_id) {
		return true;
	}
	
	function break_maj($source_id) {
		return true;
	}
	
	function form_pour_maj_entrepot($source_id) {
		return false;
	}
	
	//Nécessaire pour passer les valeurs obtenues dans form_pour_maj_entrepot au javascript asynchrone
	function get_maj_environnement($source_id) {
		return array();
	}
	
	function sync_custom_page($source_id) {
		return '';
	}
	
	function maj_entrepot($source_id,$callback_progress="",$recover=false,$recover_env="") {
		return 0;
	}
	
	
}