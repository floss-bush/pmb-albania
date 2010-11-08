<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: apijsonrpc_jsonrpcserver.class.php,v 1.4 2010-04-15 08:15:12 erwanmartin Exp $
//Here be komodo dragons

/*
	Ce fichier contient l'implémentation du serveur PMBAPI->JSON-RPC
*/

global $class_path, $base_path, $include_path;

require_once ("$base_path/includes/init.inc.php");
require_once ($class_path."/external_services.class.php");

require_once 'jsonRPCServer.php';

class apijsonrpc_jsonrpcserver {
	var $connector_object;
	var $server;
	
	function apijsonrpc_jsonrpcserver($connector_object) {
		$this->connector_object = $connector_object;
	}
	
	function return_error($error_string) {
		highlight_string(print_r($error_string, true));
		die();
	}
	
	
	function process($source_id, $pmb_user_id, $json_input) {
		global $charset;
		global $wsdl;
		global $class_path;
		
		$the_source = $this->connector_object->instantiate_source_class($source_id);
		if (!isset($the_source->config["exported_functions"]))
			$this->return_error("Source wasn't configured");

		$allowed_methods = array();
		foreach($the_source->config["exported_functions"] as $aallowed_method)
			$allowed_methods[] = $aallowed_method['group'].'_'.$aallowed_method['name'];
			
		$json_operation = '';
		$request = $json_input;
		if ($request)
			$json_operation = $request["method"];
			
		//Instantions la classe qui contient les fonctions
		$ess = new external_services(true);
		if ($json_operation && $ess->operation_need_messages($json_operation)) {
			//Allons chercher les messages
			global $class_path;
			global $include_path;
			global $lang;
			require_once("$class_path/XMLlist.class.php");
			$messages = new XMLlist("$include_path/messages/$lang.xml", 0);
			$messages->analyser();
			global $msg;
			$msg = $messages->table;
		}
		if ($json_operation)
			$proxy = $ess->get_proxy($pmb_user_id, array($json_operation));
		else
			$proxy = $ess->get_proxy($pmb_user_id);
		$proxy->input_charset = 'utf-8';

		jsonRPCServer::handle($proxy, $allowed_methods, $json_input) or print 'No request';
	}
}




?>