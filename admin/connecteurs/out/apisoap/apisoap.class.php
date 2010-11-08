<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: apisoap.class.php,v 1.7 2010-01-30 14:42:54 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path;
require_once($class_path."/connecteurs_out.class.php");
require_once($class_path."/external_services.class.php");
require_once($class_path."/external_services_esusers.class.php");

class apisoap extends connecteur_out {
	
	function get_config_form() {
		$this->config["cache_wsdl"] = isset($this->config["cache_wsdl"]) ? $this->config["cache_wsdl"] : true;
		$result = "";
		$result .=	'<div class=row><input id="cache_wsdl" '.($this->config["cache_wsdl"] ? 'checked' : '').' name="cache_wsdl" type="checkbox">'.'<label class="etiquette" for="cache_wsdl">'.$this->msg["apisoap_cache_wsdl"].'</label><br />';
		$result .=	'</div>';
		return $result;
	}
	
	function update_config_from_form() {
		global $cache_wsdl;
		$this->config["cache_wsdl"] = isset($cache_wsdl);
		return;
	}
	
	function instantiate_source_class($source_id) {
		return new apisoap_source($this, $source_id, $this->msg);
	}
	
	//On chargera nous même les messages si on en a besoin
	function need_global_messages() {
		return false;
	}
	
	function process($source_id, $pmb_user_id) {
		global $base_path;
		require_once ($base_path."/admin/connecteurs/out/apisoap/apisoap_soapserver.class.php");
		
		$apisoapserver = new apisoap_soapserver($this);
		$apisoapserver->process($source_id, $pmb_user_id);
		
		//Rien
		return;
	}
	
	function get_running_pmb_userid($source_id) {
		global $wsdl;
		$get_wsdl = isset($wsdl);
		
		//Si on ne souhaite que le wsdl, alors on laisse passer
		if ($get_wsdl)
			return 1;
		
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			//Si on ne nous fourni pas de credentials, alors on teste l'utilisateur anonyme
			$user_id = connector_out_check_credentials('', '', $source_id);
			if ($user_id === false) {
			    header('WWW-Authenticate: Basic realm="PMB SOAP"');
			    header('HTTP/1.0 401 Unauthorized');
				exit();
			}
		}
		else {
			//Sinon on teste les credentiels fournis
			$rawusername = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
			$user_id = connector_out_check_credentials($rawusername, $password, $source_id);
			if ($user_id === false) {
			    header('WWW-Authenticate: Basic realm="PMB SOAP"');
			    header('HTTP/1.0 401 Unauthorized');
				exit();		
			}
		}
		
		return $user_id;
	}
}

class apisoap_source extends connecteur_out_source {

	function get_config_form() {
		global $charset;
		$result = parent::get_config_form();
		
		$api_catalog = new es_catalog();
		$api_functions = array();
		foreach ($api_catalog->groups as $agroup) {
			foreach ($agroup->methods as $amethod) {
				$api_functions[$agroup->name][] = $amethod->name;
			}
		}

		if (!isset($this->config["exported_functions"]))
			$this->config["exported_functions"] = array();
		$selected_functions = array();
		foreach ($this->config["exported_functions"] as $afunction) {
			$selected_functions[] = $afunction["group"]."|_|".$afunction["name"];
		}

		//Adresse d'utilisation
		$result  .= '<div class=row><label class="etiquette" for="api_exported_functions">'.$this->msg["apisoap_service_endpoint"].'</label><br />';
		if ($this->id) {
			$result .= '<a target="_blank" href="ws/connector_out.php?source_id='.$this->id.'&wsdl">ws/connector_out.php?source_id='.$this->id.'&wsdl</a>';
		}
		else {
			$result .= $this->msg["apisoap_service_endpoint_unrecorded"];
		}
		$result .= "</div>";
		
		//Fonction exportées
		$result  .= '<div class=row><label class="etiquette" for="api_exported_functions">'.$this->msg["apisoap_exported_functions"].'</label><br />';
		$api_select = '<select MULTIPLE name="api_exported_functions[]">';
		foreach ($api_functions as $agroup_name => $agroup) {
			$api_select .= '<optgroup label="'.htmlentities($agroup_name ,ENT_QUOTES, $charset).'">';
			foreach ($agroup as $amethodname) {
				$davalue = $agroup_name."|_|".$amethodname;
				$api_select .= '<option '.(in_array($davalue, $selected_functions) ? 'selected' : "").' value="'.htmlentities($davalue ,ENT_QUOTES, $charset).'">'.htmlentities($amethodname ,ENT_QUOTES, $charset).'</option>';
			}
			$api_select .= '</optgroup>';
		}
		$api_select .= '</select>';
		$result .= $api_select;
		$result .= "</div>";
		
		//Autentification
/*		$authorized_config_types = array('none', 'groups');
		if (!isset($this->config["authentication_type"]) || !in_array($this->config["authentication_type"], $authorized_config_types))
			$this->config["authentication_type"] = 'none';
		if (!isset($this->config["authorized_groups"]))
			$this->config["authorized_groups"] = array();
		$result  .= '<div class=row><label class="etiquette" for="api_authentication">'.$this->msg["apisoap_authentication"].'</label><br />';
		$onchange_js = "document.getElementById('authorized_groups').disabled = document.getElementById('api_authentication_none').checked";
		$result .= '<input onchange="'.$onchange_js.'" id="api_authentication_none" name="authentication_type" type="radio" '.($this->config["authentication_type"] == 'none' ? 'checked' : '').' value="none">'.$this->msg["apisoap_authenfication_none"]."<br />";
		$result .= '<input onchange="'.$onchange_js.'" id="api_authentication_groups" name="authentication_type" type="radio" '.($this->config["authentication_type"] == 'groups' ? 'checked' : '').' value="groups">'.$this->msg["apisoap_authenfication_group"]."<br>";
		
		$es_groups = new es_esgroups;
		$group_list = '<select id="authorized_groups" MULTIPLE '.($this->config["authentication_type"] == 'groups' ? '' : 'disabled').' name="authorized_groups[]">';
		foreach ($es_groups->groups as &$aesgroup) {
			$group_list .= '<option '.(in_array($aesgroup->esgroup_id, $this->config["authorized_groups"]) ? 'selected' : '').' value="'.$aesgroup->esgroup_id.'">'.htmlentities($aesgroup->esgroup_name.' ('.$aesgroup->esgroup_fullname.')', ENT_QUOTES, $charset).'</option>';
		}
		$group_list .= '</select>';
		$result .= "<blockquote>".$group_list."<blockquote>";
		
		//$result .= '<br />';*/
		return $result;
	}

	function update_config_from_form() {
		parent::update_config_from_form();
		global $api_exported_functions, $authentication_type, $authorized_groups;
		
		if (!$api_exported_functions)
			$api_exported_functions = array();
		if (!isset($authentication_type) || !$authentication_type)
			$authentication_type = 'none';
		if (!isset($authorized_groups) || !$authorized_groups)
			$authorized_groups = array();
		
		//Récupérons la liste des fonctions pour virer de l'entrée les noms de fonctions qui n'existent pas
		$api_catalog = new es_catalog();
		$api_functions = array();
		foreach ($api_catalog->groups as $agroup) {
			foreach ($agroup->methods as $amethod) {
				$api_functions[] = $agroup->name."|_|".$amethod->name;
			}
		}
		$api_exported_functions = array_intersect($api_exported_functions, $api_functions);
		
		//Enregistrons
		$config_exported = array();
		foreach ($api_exported_functions as $afunction) {
			$dafunction = explode("|_|", $afunction);
			$config_exported[] = array("group" => $dafunction[0], "name" => $dafunction[1]);
		}
		$this->config["exported_functions"] = $config_exported;
		
/*		//Autentification
		$authorized_config_types = array('none', 'groups');
		if (!in_array($authentication_type, $authorized_config_types))
			$authentication_type = 'none';
		$this->config["authentication_type"] = $authentication_type;
		
		$base_autorized_group_ids = array();
		$es_groups = new es_esgroups;
		foreach ($es_groups->groups as $aesgroup)
			$base_autorized_group_ids[] = $aesgroup->esgroup_id;
		$authorized_groups = array_intersect($base_autorized_group_ids, $authorized_groups);
		$this->config["authorized_groups"] = $authorized_groups;*/
		
		return;
	}

}

?>