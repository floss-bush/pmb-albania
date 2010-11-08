<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: apijsonrpc.class.php,v 1.5 2010-04-12 07:10:16 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path;
require_once($class_path."/connecteurs_out.class.php");
require_once($class_path."/external_services.class.php");
require_once($class_path."/external_services_esusers.class.php");
require_once ("$base_path/admin/connecteurs/out/apijsonrpc/apijsonrpc_jsonrpcserver.class.php");

class apijsonrpc extends connecteur_out {
	var $json_input = '';
	
	function get_config_form() {
		$result = $this->msg["no_configuration_required"];
		return $result;
	}
	
	function update_config_from_form() {
		return;
	}
	
	function instantiate_source_class($source_id) {
		return new apijsonrpc_source($this, $source_id, $this->msg);
	}
	
	//On chargera nous même les messages si on en a besoin
	function need_global_messages() {
		return false;
	}
	
	function process($source_id, $pmb_user_id) {
		global $base_path;

		$apijsonrpc_jsonrpcserver = new apijsonrpc_jsonrpcserver($this);
		$apijsonrpc_jsonrpcserver->process($source_id, $pmb_user_id, $this->json_input);
		
		//Rien
		return;
	}
	
	function return_json_error($message, $request) {
		$response = array (
			'id' => $request['id'],
			'result' => NULL,
			'error' => $message
		);
		// output the response
		if (!empty($request['id'])) { // notifications don't want response
			header('content-type: text/javascript');
			echo json_encode($response);
		}
		die();
	}
	
	function get_running_pmb_userid($source_id) {
		$user_id = 1;
		$this->json_input = json_decode(file_get_contents('php://input'),true);
		if (!$this->json_input)
			return 1;
		
		$credentials_user = '';
		$credentials_password = '';
		
		if (isset($this->json_input["auth_user"])) {
			$credentials_user = $this->json_input["auth_user"];
			$credentials_password = isset($this->json_input["auth_pw"]) ? $this->json_input["auth_pw"] : '';
		}
		if (isset($_SERVER['PHP_AUTH_USER'])) {
			$credentials_user = $_SERVER['PHP_AUTH_USER'];
			$credentials_password = $_SERVER['PHP_AUTH_PW'];
		}
		
		if (!$credentials_user) {
			//Si on ne nous fourni pas de credentials, alors on teste l'utilisateur anonyme
			$user_id = connector_out_check_credentials('', '', $source_id);
			if ($user_id === false) {
			    $this->return_json_error('Access with no credentials is forbidden.', $this->json_input);
			}
		} else {
			$user_id = connector_out_check_credentials($credentials_user, $credentials_password, $source_id);
			if ($user_id === false) {
			    $this->return_json_error('Bad credentials.', $this->json_input);
			}
		}
		
		return $user_id;
	}
}

class apijsonrpc_source extends connecteur_out_source {

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
		global $database;
		$result .= '<div class=row><label class="etiquette" for="api_exported_functions">'.$this->msg["apijsonrpc_service_endpoint"].'</label><br />';
		if ($this->id) {
			$result .= '<a target="_blank" href="ws/connector_out.php?source_id='.$this->id.'">ws/connector_out.php?source_id='.$this->id.'</a>';
		}
		else {
			$result .= $this->msg["apijsonrpc_service_endpoint_unrecorded"];
		}
		$result .= "</div>";
		
		//Fonction exportées
		$result  .= '<div class=row><label class="etiquette" for="api_exported_functions">'.$this->msg["apijsonrpc_exported_functions"].'</label><br />';
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
		
		return;
	}

}

?>