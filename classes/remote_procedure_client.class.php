<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: remote_procedure_client.class.php,v 1.2.4.1 2011-05-02 10:26:16 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class remote_procedure_client {
	var $server_adress="";
	var $server_username="";
	var $server_key="";

	function array_to_object($array, $recursive=false) {
		$return = new stdClass();
		foreach ($array as $k => $v) {
			if ($recursive && is_array($v)) {
				$return->$k = $this->array_to_object($v);
			}
			else {
				$return->$k = $v;
			}
		}
	
		return $return;
	}
	
	function remote_procedure_client($server_adress, $server_username, $server_key) {
		$this->server_adress = $server_adress;
		$this->server_username = $server_username;
		$this->server_key = $server_key;
	}

	function get_procs($type="", $set="") {
		global $charset, $class_path, $pmb_version_brut;
		global $msg;
		global $pmb_curl_proxy;

		$params = array("credentials" => array("user"=>$this->server_username, "key" => $this->server_key));
		if ($set)
			$params["set"] = $set;
		if ($type)
			$params["type"] = $type;
		$params["pmbversion"] = $pmb_version_brut;
		
		//Utilisons php_soap si disponible
		if (extension_loaded("soap")) {
			$soap_client_parameters = array();
			if($pmb_curl_proxy!=''){
				$param_proxy = explode(',',$pmb_curl_proxy);
				$adresse_proxy = $param_proxy[0];
				$port_proxy = $param_proxy[1];
				$user_proxy = $param_proxy[2];
				$pwd_proxy = $param_proxy[3];
				$soap_client_parameters = array('proxy_host'     => $adresse_proxy,
	                                  			'proxy_port'     => $port_proxy,
	                                  			'proxy_login'    => $user_proxy,
	                                  			'proxy_password' => $pwd_proxy);
			}
			
			$soap_client_parameters['features'] = SOAP_SINGLE_ELEMENT_ARRAYS;
			$client = new SoapClient($this->server_adress, $soap_client_parameters);
			if (!$client) {
				return (object)array("error_information" => ((object)array("error_code" => 1 ,"error_string" => $msg["remote_procedures_error_client"])));
			}
			$result = $client->get_procs($params);
			
			if ($charset != 'utf-8') {
				if (isset($result->elements)) {
					if (!is_array($result->elements))
						$result->elements = array($result->elements);
						
					foreach ($result->elements as $index => $aprocedure) {
						$result->elements[$index]->name = utf8_decode($aprocedure->name);
						$result->elements[$index]->comment = utf8_decode($aprocedure->comment);
						$result->elements[$index]->sql = utf8_decode($aprocedure->sql);
						$result->elements[$index]->params = utf8_decode($aprocedure->params);
						$result->elements[$index]->current_attached_set = utf8_decode($aprocedure->current_attached_set);
						if (isset($aprocedure->sets)) {
							if (!is_array($aprocedure->sets))
								$result->elements[$index]->sets = array($result->elements[$index]->sets);
							foreach($result->elements[$index]->sets as $set_index => $aset) {
								$result->elements[$index]->sets[$set_index]->set_caption = utf8_decode($result->elements[$index]->sets[$set_index]->set_caption);
							}
						}
					}
				}
			}
		}
		//Sinon, utilisons le client nusoap
		else {
			$adresse_proxy = false;
			$port_proxy = false;
			$user_proxy = false;
			$pwd_proxy = false;
			if($pmb_curl_proxy!=''){
				$param_proxy = explode(',',$pmb_curl_proxy);
				$adresse_proxy = $param_proxy[0];
				$port_proxy = $param_proxy[1];
				$user_proxy = $param_proxy[2];
				$pwd_proxy = $param_proxy[3];
			}
			
			require_once($class_path."/nusoap/nusoap.php");
			$client = new nusoapclient($this->server_adress, true, $adresse_proxy, $port_proxy, $user_proxy, $pwd_proxy);
			$client->decode_utf8 = ($charset != 'utf-8');
			if ($err=$client->getError()) {
				return (object)array("error_information" => ((object)array("error_code" => 1 ,"error_string" => $err)));
			}
			$result = $client->call("get_procs", array("parameters" => $params), "http://www.sigb.net/pmb/");
			//Si une seule procédure est renvoyée, soap ne renvoi pas un tableau, et alors elements contient directement une procedure
			//Mais nous voulons un tableau, donc nous devons traiter le cas
			if (isset($result["elements"]["id"]))
				$result["elements"] = array($result["elements"]);

			$result = $this->array_to_object($result);
			if (isset($result->elements))
				foreach ($result->elements as $index => $value)
					$result->elements[$index] = $this->array_to_object($result->elements[$index], true);
		}
		
		return $result;
	}
	
	function get_proc($proc_id,$type="") {
		global $msg;
		$result = array("error_message" => "", "procedure" => NULL);
		$procedures = $this->get_procs($type);
		$the_procedure = "";
			
		if ($procedures) {
			if ($procedures->error_information->error_code) {
				$result["error_message"] = $procedures->error_information->error_string; 
				return $result;
			}
			else {
				if (isset($procedures->elements))
					foreach ($procedures->elements as $aprocedure) {
						if ($aprocedure->id == $proc_id) {
							$the_procedure = $aprocedure;
							break;
						}
					}
			}
		}
		if ($the_procedure)
			$result["procedure"] = $the_procedure;
		else 
			$result["error_message"] = $msg["remote_procedures_proc_notfound"];
		return $result;
	}
	
	function parse_parameters($parameters) {
		$parsed_parameters=array();
		if (!$parameters || $parameters == "NULL") return;
		$pp = _parser_text_no_function_($parameters);
		if (isset($pp["FIELDS"][0]["FIELD"])) {
			foreach($pp["FIELDS"][0]["FIELD"] as $afield) {
				$parsed_parameters[$afield["NAME"]] = array("type" => $afield["TYPE"][0], "options" => $afield["OPTIONS"][0], 'title' => $afield["ALIAS"][0]);
			}
		}
		return $parsed_parameters;
	}
}

?>