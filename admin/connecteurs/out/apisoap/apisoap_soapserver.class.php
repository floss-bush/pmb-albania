<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: apisoap_soapserver.class.php,v 1.8 2010-04-15 08:15:24 erwanmartin Exp $
//Here be komodo dragons

/*
	Ce fichier contient l'implémentation du serveur PMBAPI->SOAP
*/

global $class_path, $base_path, $include_path;

require_once ("$base_path/includes/init.inc.php");
require_once ("$base_path/admin/connecteurs/out/apisoap/apisoap.class.php");
require_once ($class_path."/external_services.class.php");
require_once ($include_path."/connecteurs_out_common.inc.php");
require_once($class_path."/external_services_caches.class.php");

//Cette fonction prend l'url courante et supprime le wsdl de l'url
function serverURL() {
	$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
	$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
	$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
	$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["SCRIPT_NAME"];

	$urls = array();
	foreach ($_GET as $agetname => $agetvalue) {
		if (strtoupper($agetname) == "WSDL")
			continue;

		if ($agetvalue)
			$urls[] = urlencode($agetname)."=".urlencode($agetvalue);
		else
			$urls[] = urlencode($agetname);
	}
	if (count($urls)) {
		$url .= "?";
		$url .= implode('&', $urls);
	}
	return $url;
}

class apisoap_soapserver {
	var $connector_object;
	var $server;
	
	function apisoap_soapserver($connector_object) {
		$this->connector_object = $connector_object;
	}
	
	function return_error($error_string) {
		highlight_string(print_r($error_string, true));
		die();
	}
	
	/*
	 * Cette fonction converti un champs input type manifest en type wsdl
	 * 
	 */
	function input_to_wsdl($input, $method_name, $base_group_name, &$additional_definitions, $nodetype="element", $base_type=false) {
		//Corespondance entre les types des manifests et les types wsdl
		$corresponding_scalar_types = array(
			"string" => "string",
			"integer" => "int",
			"boolean" => "boolean"
		);
		global $declared_types; //Le tableau qui référence les types que l'on a définit
		global $type_aliases; //Le tableau qui référence les alias des types, pour les type importés
		$default_data_type = 'string'; //En cas de type inconnu
		
		$result = "";
		switch ($input->type) {
			case 'scalar':
				if (isset($type_aliases[$base_group_name.'_'.$input->datatype])) {
					$corresponding_type = $type_aliases[$base_group_name.'_'.$input->datatype];
					$corresponding_type = 'tns:'.$corresponding_type;
				}
				else if (isset($declared_types[$base_group_name.'_'.$input->datatype])) {
					$corresponding_type = $base_group_name.'_'.$input->datatype;
					$corresponding_type = 'tns:'.$corresponding_type;
				}
				else {
					$corresponding_type = isset($corresponding_scalar_types[$input->datatype]) ? $corresponding_scalar_types[$input->datatype] : $default_data_type;
					$corresponding_type = 'xsd:'.$corresponding_type;
				}
				$cardinality_information = "";
				if ($input->optional)
					$cardinality_information = 'minOccurs="0" maxOccurs="1"';
				//Type simple, facile
				if ($nodetype == "element")
					$result .= '<xsd:element name="'.XMLEntities($input->name).'" type="'.$corresponding_type.'" '.$cardinality_information.'/>';
				else if ($nodetype == "part")
					$result .= '<wsdl:part name="'.XMLEntities($input->name).'" type="'.$corresponding_type.'" '.$cardinality_information.'/>';
				break;
			case 'array':
				//Tableau: il faut déclarer le type et le référencer. Si on génère une part, on peut balancer une structure à occurence multiple; sinon il faut déclarer un type tableau et le remplir.
				
				//Référence
				if ($nodetype == "element") {
					$result .= '<wsdl:element name="'.XMLEntities($input->name).'" type="tns:ArrayOf'.$method_name."_".XMLEntities($input->name).'"/>';
					
				if (!isset($additional_definitions["ArrayOf".$method_name."_".XMLEntities($input->name)])) {

						//Si le tableau contient un seul élément, on le définit comme un tableau de ce type, sinon il faut définir un type tableau
						if (count($input->struct) == 1) {
							if (isset($type_aliases[$base_group_name.'_'.$input->struct[0]->datatype])) {
								$corresponding_type = $type_aliases[$base_group_name.'_'.$input->struct[0]->datatype];
								$corresponding_type = 'tns:'.$corresponding_type;
							}
							else if (isset($declared_types[$base_group_name.'_'.$input->struct[0]->datatype])) {
								$corresponding_type = $base_group_name.'_'.$input->struct[0]->datatype;
								$corresponding_type = 'tns:'.$corresponding_type;
							}
							else {
								$corresponding_type = isset($corresponding_scalar_types[$input->struct[0]->datatype]) ? $corresponding_scalar_types[$input->struct[0]->datatype] : $default_data_type;
								$corresponding_type = 'xsd:'.$corresponding_type;
							}
							$array_content_type = $corresponding_type;
						}
						else 
							$array_content_type = "tns:".$method_name."_".XMLEntities($input->name).'_struct';
						$additional_definition = "";
						$additional_definition .= '<xsd:complexType name="ArrayOf'.$method_name."_".XMLEntities($input->name).'">';
						$additional_definition .= '  <xsd:complexContent>';
						$additional_definition .= '    <xsd:restriction base="soapenc:Array">';
						$additional_definition .= '      <xsd:attribute ref="soapenc:arrayType" wsdl:arrayType="'.$array_content_type.'[]"/>';
						$additional_definition .= '    </xsd:restriction>';
						$additional_definition .= '  </xsd:complexContent>';
						$additional_definition .= '</xsd:complexType>';
						$additional_definitions["ArrayOf".$method_name."_".XMLEntities($input->name)] = $additional_definition;
					}
				}
				else if ($nodetype == "part") {
					$result .= '<wsdl:part name="'.XMLEntities($input->name).'" type="tns:ArrayOf'.$method_name."_".XMLEntities($input->name).'"/>';
	
					if (!isset($additional_definitions["ArrayOf".$method_name."_".XMLEntities($input->name)])) {

						//Si le tableau contient un seul élément, on le définit comme un tableau de ce type, sinon il faut définir un type tableau
						if (count($input->struct) == 1) {
							if (isset($type_aliases[$base_group_name.'_'.$input->struct[0]->datatype])) {
								$corresponding_type = $type_aliases[$base_group_name.'_'.$input->struct[0]->datatype];
								$corresponding_type = 'tns:'.$corresponding_type;
							}
							else if (isset($declared_types[$base_group_name.'_'.$input->struct[0]->datatype])) {
								$corresponding_type = $base_group_name.'_'.$input->struct[0]->datatype;
								$corresponding_type = 'tns:'.$corresponding_type;
							}
							else {
								$corresponding_type = isset($corresponding_scalar_types[$input->struct[0]->datatype]) ? $corresponding_scalar_types[$input->struct[0]->datatype] : $default_data_type;
								$corresponding_type = 'xsd:'.$corresponding_type;
							}
							$array_content_type = $corresponding_type;
						}
						else 
							$array_content_type = "tns:".$method_name."_".XMLEntities($input->name).'_struct';
						$additional_definition = "";
						$additional_definition .= '<xsd:complexType name="ArrayOf'.$method_name."_".XMLEntities($input->name).'">';
						$additional_definition .= '  <xsd:complexContent>';
						$additional_definition .= '    <xsd:restriction base="soapenc:Array">';
						$additional_definition .= '      <xsd:attribute ref="soapenc:arrayType" wsdl:arrayType="'.$array_content_type.'[]"/>';
						$additional_definition .= '    </xsd:restriction>';
						$additional_definition .= '  </xsd:complexContent>';
						$additional_definition .= '</xsd:complexType>';
						$additional_definitions["ArrayOf".$method_name."_".XMLEntities($input->name)] = $additional_definition;
					}
				}
				if (count($input->struct) > 1) {
					if (!isset($additional_definitions[$method_name."_".XMLEntities($input->name).'_struct'])) {
						$additional_definition = "";
						$additional_definition .= '<xsd:complexType name="'.$method_name."_".XMLEntities($input->name).'_struct">';
						$additional_definition .= '<xsd:sequence>';
						foreach($input->struct as $anotherparam)
							$additional_definition .= $this->input_to_wsdl($anotherparam, $method_name, $base_group_name, $additional_definitions, "element");
						$additional_definition .= '</xsd:sequence>';
						$additional_definition .= '</xsd:complexType>';
						$additional_definitions[$method_name."_".XMLEntities($input->name).'_struct'] = $additional_definition;
					}
				}
				break;
			case 'structure':
				//Structure: il faut déclarer le type en question et lui faire référence
				
				if ($base_type)
					$declared_name = $method_name;
				else
					$declared_name = $method_name."_".XMLEntities($input->name).'_struct'; 
				
				//Référence
				if ($nodetype == "element") {
					$result .= '<xsd:element name="'.XMLEntities($input->name).'" type="tns:'.$declared_name.'"></xsd:element>';
				}
				else if ($nodetype == "part") {
					$result .= '<wsdl:part name="'.XMLEntities($input->name).'" type="tns:'.$declared_name.'"/>';
				}
					
				//Déclaration du type (ça peut être récursif)
				if (!isset($additional_definitions[$declared_name])) {
					$additional_definition = "";
					$additional_definition .= '<xsd:complexType name="'.$declared_name.'">';
					$additional_definition .= '<xsd:sequence>';
					foreach($input->struct as $anotherparam)
						$additional_definition .= $this->input_to_wsdl($anotherparam, $method_name, $base_group_name, $additional_definitions, "element");
					$additional_definition .= '</xsd:sequence>';
					$additional_definition .= '</xsd:complexType>';
					$additional_definitions[$declared_name] = $additional_definition;
				}
				break;
		}
		return $result;
	}
	
	function output_to_wsdl($output, $method_name, $base_group_name, &$additional_definitions, $nodetype="element") {
		//C'est la même que pour les inputs
		return $this->input_to_wsdl($output, $method_name, $base_group_name, $additional_definitions, $nodetype);
	}

	function type_to_wsdl($output, $method_name, $base_group_name, &$additional_definitions, $nodetype="element") {
		//C'est la même que pour les inputs
		return $this->input_to_wsdl($output, $method_name, $base_group_name, $additional_definitions, $nodetype, true);
	}
	
	/*
	 * Cette fonction converti la liste des fonctions de la source en un fichier wsdl, puis l'envoi
	 * 
	 */
	function return_wsdl($source_object, $user_id=0) {
		global $charset;
		global $declared_types;
		global $type_aliases;

		$cache_ref = "api_soap_wsdl_".$source_object->id;
		if ($source_object->connector->config["cache_wsdl"]) {
			//Voyons si on peut trouver quelque chose de pas trop vieux dans le cache
			$es_cache = new external_services_cache('es_cache_blob', 3600);
			$cached_result = $es_cache->decache_single_object($cache_ref, CACHE_TYPE_MISC);
			if ($cached_result !== false) {
				//Il est bon? On l'envoi
				if (!isset($_GET["nx"]))
					header('Content-Type: text/xml');
				print $cached_result;
				exit();
			}
		}
		
		//Récupérons la liste des fonctions que l'on doit exporter
		$api_catalog = new es_catalog();
		$api_es = new external_services();
		//$api_rights = new external_services_rights($api_es);
		$final_method_list = array();
		foreach ($source_object->config["exported_functions"] as $amethod) {
	//		if ($api_rights->has_rights($user_id, $amethod["group"], $amethod["name"])) {
				$final_method_list[] = $amethod;
	//		}
		}
		
		//Les entêtes du fichier
		$wsdl_headers = '<?xml version="1.0" encoding="UTF-8"?>
		<?xml-stylesheet type="text/xsl" href="connecteurs/out/apisoap/wsdl-viewer.xsl"?>
		<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tns="http://sigb.net/pmb/es/apisoap" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" name="PMBSOAPAPI" targetNamespace="http://sigb.net/pmb/es/apisoap">';
		
		//Les entêtes des déclaration de type
		$wsdl_types = '<wsdl:types>
		    <xsd:schema targetNamespace="http://sigb.net/pmb/es/apisoap" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">
		    	<xsd:import namespace="http://schemas.xmlsoap.org/soap/encoding/"/>';
		
		//On va construire la liste des types à déclarer en fonction de ce que l'on va rencontrer, puis on y reviendra
		$additional_definitions = array();
		$declared_types = array();
		$type_aliases = array();

		//Ajoutons les types du manifest s'il y en a
		$handled_groups=array();
		foreach ($final_method_list as $amethod) {
			//Vérifions si on a pas déjà traité les types de ce groupe
			if (isset($handled_groups[$amethod["group"]]))
				continue;

			//Ajoutons les types du groupe de la methode courante
			foreach ($api_catalog->groups[$amethod["group"]]->types as &$atype) {
				if ($atype->imported) {
					$base_name = $api_catalog->groups[$atype->imported_from]->name.'_'.$atype->name;
					$local_base_name = $api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->group.'_'.$atype->name;
					if (isset($declared_types[$base_name])) {
						if (!isset($type_aliases[$local_base_name]))
							$type_aliases[$local_base_name] = $base_name;
						continue;
					}
					$this->type_to_wsdl($api_catalog->groups[$atype->imported_from]->types[$atype->name], $base_name, $atype->imported_from, $additional_definitions, "element");
					$type_aliases[$local_base_name] = $base_name;
				}
				else {
					$base_name = $api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->group.'_'.$atype->name;
					if (isset($declared_types[$base_name]))
						continue;
					$this->type_to_wsdl($atype, $base_name, $amethod["group"], $additional_definitions, "element");
				}
				$declared_types[$base_name] = true;
			}

			$handled_groups[$amethod["group"]] = true;
		}
		
		//Construction des messages
		$wsdl_messages = "";
		foreach ($final_method_list as $amethod) {
			$method_name = $api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->group.'_'.$api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->name;
			
			$wsdl_messages .= '<wsdl:message name="'.XMLEntities($method_name).'Request">';
			foreach ($api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->inputs as $ainput) {
				$wsdl_messages .= $this->input_to_wsdl($ainput, $method_name, $amethod["group"], $additional_definitions, "part");
			}
	  		$wsdl_messages .= '</wsdl:message>';
	
	  		$wsdl_messages .= '<wsdl:message name="'.XMLEntities($method_name).'Response">';
	  		$output_count = count($api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->outputs);
	  		if ($output_count > 1) {
	  			
				$additional_definition = "";
				$additional_definition .= '<xsd:complexType name="'.XMLEntities($method_name).'ResponseStruct">';
				$additional_definition .= '<xsd:sequence>';
	  			foreach ($api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->outputs as $ainput) {
					$additional_definition .= $this->output_to_wsdl($ainput, $method_name, $amethod["group"], $additional_definitions, "element");
				}
				$additional_definition .= '</xsd:sequence>';
				$additional_definition .= '</xsd:complexType>';
				$additional_definitions[XMLEntities($method_name).'ResponseStruct'] = $additional_definition;	  			
	  			
				$wsdl_messages .= '<wsdl:part name="result" type="tns:'.XMLEntities($method_name).'ResponseStruct"/>';
	  		}
	  		else if($output_count) {
				$wsdl_messages .= $this->output_to_wsdl($api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->outputs[0], $method_name, $amethod["group"], $additional_definitions, "part");
	  		}
	  		$wsdl_messages .= '</wsdl:message>';
	  		
		}
		
		//On a maintenant les types, on fini de construire le bloc associé
		$wsdl_types .= implode("", $additional_definitions);
		$wsdl_types .= '</xsd:schema>
		     	</wsdl:types>';
		
		//PortType
		$wsdl_porttype = "";
		$wsdl_porttype .= '<wsdl:portType name="PMBSOAPAPI">';
		foreach ($final_method_list as $amethod) {
			$method_name = $api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->group.'_'.$api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->name;
			$method_group = $amethod["group"];
			$method_description = $api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->description;
			$input_description = $api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->input_description;
			$output_description = $api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->output_description;
			$wsdl_porttype .= '
			       <wsdl:operation name="'.XMLEntities($method_name).'">
	                 <wsdl:documentation>'.XMLEntities($api_es->get_text($method_description, $method_group)).'</wsdl:documentation>
			         <wsdl:input message="tns:'.XMLEntities($method_name).'Request">
			           <wsdl:documentation>'.XMLEntities($api_es->get_text($input_description, $method_group)).'</wsdl:documentation>
			         </wsdl:input>
			         <wsdl:output message="tns:'.XMLEntities($method_name).'Response">
			           <wsdl:documentation>'.XMLEntities($api_es->get_text($output_description, $method_group)).'</wsdl:documentation>
			         </wsdl:output>
			       </wsdl:operation>';
		}
		$wsdl_porttype .= '</wsdl:portType>';
		
		//Binding
		$wsdl_binding = "";
		$wsdl_binding .= '<wsdl:binding name="PMBSOAPAPI_Binding" type="tns:PMBSOAPAPI">
			    <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>';
		foreach ($final_method_list as $amethod) {
			$method_name = $api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->group.'_'.$api_catalog->groups[$amethod["group"]]->methods[$amethod["name"]]->name;
			$wsdl_binding .= '
				   <wsdl:operation name="'.XMLEntities($method_name).'">
				      <soap:operation soapAction="http://sigb.net/pmb/es/apisoap/'.XMLEntities($method_name).'" style="rpc"/>
				      <wsdl:input>
 			            <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" use="encoded" />
  				      </wsdl:input>
				      <wsdl:output>
				        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" use="encoded" />
				      </wsdl:output>
				   </wsdl:operation>';
		}
		$wsdl_binding .= '</wsdl:binding>';
		
		//Service
		$server_location = serverURL();
		$wsdl_service = "";
		$wsdl_service .= '
	  <wsdl:service name="PMBSOAPAPI">
	    <wsdl:port binding="tns:PMBSOAPAPI_Binding" name="PMBSOAPAPI">
	      <soap:address location="'.XMLEntities($server_location).'"/>
	    </wsdl:port>
	  </wsdl:service>';
		$wsdl_footer = '</wsdl:definitions>';
		
		
		//On conbine le tout pour donner le wsdl final
		$wsdl = $wsdl_headers . $wsdl_types . $wsdl_messages . $wsdl_porttype . $wsdl_binding . $wsdl_service . $wsdl_footer;
		if ($charset != 'utf-8')
			$wsdl = utf8_encode($wsdl);
		
		//On le met en cache si on le souhaite
		if ($source_object->connector->config["cache_wsdl"]) {
			$es_cache = new external_services_cache('es_cache_blob', 600);
			$es_cache->encache_single_object($cache_ref, CACHE_TYPE_MISC, $wsdl);
		}
			
		//Et on l'envoi
		if (!isset($_GET["nx"]))
			header('Content-Type: text/xml');
		print $wsdl;
		exit();
	}
	
	function return_soapfault_from_api_exception($e) {
		$this->server->fault("Interal API Error", $e->getMessage());
	}
	
	function process($source_id, $pmb_user_id) {
		global $charset;
		global $wsdl;
		$get_wsdl = isset($wsdl);
		
		$the_source = $this->connector_object->instantiate_source_class($source_id);
		if (!isset($the_source->config["exported_functions"]))
			$this->return_error("Source wasn't configured");
		
		//Si on nous demande le wsdl, on le génère et on l'envoi
		if (isset($get_wsdl) && $get_wsdl) {
			$this->return_wsdl($the_source, 0);
		}
			
		//Si on ne veut pas le wsdl ou qu'on ne demande rien de soap, alors on ne fait rien
		if (!isset($_SERVER["HTTP_SOAPACTION"]) || !$_SERVER["HTTP_SOAPACTION"])
			die();
		
		//L'url du wsdl dépend de l'url courante, et on rajoute le ?wsdl
		$wsdl_location = curPageURL();
		$wsdl_location .= (strpos($wsdl_location, "?") === false ? "?wsdl" : "&wsdl");
		
		//Pas de cache, ça nuit au developpement
		ini_set("soap.wsdl_cache_enabled", ($the_source->config["cache_wsdl"] ? 1 : 0));
		
		//Récupérons à partir des entêtes le nom de l'opération que l'on souhaite éxécuter.
		//Exemple d'entête: 
		//	SOAPAction: "http://sigb.net/pmb/es/apisoap/pmbesZWMTest1_credential_testfunction"\r\n
		$soap_operation = substr(strrchr($_SERVER["HTTP_SOAPACTION"], "/"), 1, -1);
		if (!$soap_operation)
			die();
		
		//Instantions le serveur SOAP
		$this->server = new SoapServer($wsdl_location, array('encoding'=>$charset, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS));
		
		//Instantions la classe qui contient les fonctions
		$ess = new external_services(true);
		if ($ess->operation_need_messages($soap_operation)) {
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
		$proxy = $ess->get_proxy($pmb_user_id, array($soap_operation));
		$proxy->set_error_callback(array(&$this, "return_soapfault_from_api_exception"));
		$proxy->input_charset = 'utf-8';
		
		if (!method_exists($proxy, $soap_operation))
			$this->server->fault("unknown_method_or_bad_credentials", "Could not find the method according to your group internal's credentials");
		
		//Donnons au serveur SOAP le proxy
		$this->server->setObject($proxy);

		//Et c'est parti!
		$this->server->handle();
	}
}




?>