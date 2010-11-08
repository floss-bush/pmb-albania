<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sru_protocol.class.php,v 1.3 2009-05-16 10:52:56 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;

if (version_compare(PHP_VERSION,'5','>=') && extension_loaded('xsl')) {
	if (substr(phpversion(), 0, 1) == "5") @ini_set("zend.ze1_compatibility_mode", "0");
	require_once($include_path.'/xslt-php4-to-php5.inc.php');
	}

define("SR_MODE_AUTO", 1);
define("SR_MODE_STYLESHEETS", 2);

//Un petit parser-dom �l�gant
//Auteur: FLORENT TETART
class xml_dom_sru {
	var $xml;
	var $charset;
	var $tree;
	var $error=false;
	var $error_message="";
	var $depth=0;
	var $last_elt=array();
	var $n_elt=array();
	var $cur_elt=array();
	var $last_char=false;
	
	function close_node() {
		$this->last_elt[$this->depth-1]["CHILDS"][]=$this->cur_elt;
		$this->last_char=false;
		$this->cur_elt=$this->last_elt[$this->depth-1];
		$this->depth--;
	}
	
	function startElement($parser,$name,$attribs) {
		if ($this->last_char) $this->close_node();
		$this->last_elt[$this->depth]=$this->cur_elt;
		$this->cur_elt=array();

		$this->cur_elt["NAME"]=$name;
		$this->cur_elt["ATTRIBS"]=$attribs;
		$this->cur_elt["TYPE"]=1;
		$this->last_char=false;
		$this->depth++;

	}
	
	function endElement($parser,$name) {
		if ($this->last_char) $this->close_node();
		$this->close_node();
	}
	
	function charElement($parser,$char) {
		if ($this->last_char) $this->close_node();
		if ($this->charset != 'utf-8') {
			$char = utf8_decode($char);
		}
		$this->last_char=true;
		$this->last_elt[$this->depth]=$this->cur_elt;
		$this->cur_elt=array();
		$this->cur_elt["DATA"]=$char;
		$this->cur_elt["TYPE"]=2;
		$this->depth++;
	}
	
	function xml_dom_sru($xml,$charset="iso-8859-1") {
		$this->charset=$charset;
		$this->cur_elt=array("NAME"=>"document","TYPE"=>"0");
		
		//Initialisation du parser
		$xml_parser=xml_parser_create($this->charset);
		xml_set_object($xml_parser,$this);
		xml_parser_set_option( $xml_parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option( $xml_parser, XML_OPTION_SKIP_WHITE, 1 );
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser,"charElement");
		
		if (!xml_parse($xml_parser, $xml)) {
       		$this->error_message=sprintf("XML error: %s at line %d",xml_error_string(xml_get_error_code($xml_parser)),xml_get_current_line_number($xml_parser));
       		$this->error=true;
		}
		$this->tree=$this->last_elt[0];
	}
	
	function get_node($path,$node="") {
		if ($node=="") $node=&$this->tree;
		$paths=explode("/",$path);
		for ($i=0; $i<count($paths); $i++) {
			if ($i==count($paths)-1) {
				$pelt=explode("@",$paths[$i]);
				if (count($pelt)==1) { 
					$p=$pelt[0]; 
				} else {
					$p=$pelt[1];
					$attr=$pelt[0];
				}
			} else $p=$paths[$i];
			if (preg_match("/\[([0-9]*)\]$/",$p,$matches)) {
				$name=substr($p,0,strlen($p)-strlen($matches[0]));
				$n=$matches[1];
			} else {
				$name=$p;
				$n=0;
			}
			$nc=0;
			$found=false;
			for ($j=0; $j<count($node["CHILDS"]); $j++) {
				if (($node["CHILDS"][$j]["TYPE"]==1)&&($node["CHILDS"][$j]["NAME"]==$name)) {
					//C'est celui l� !!
					if ($nc==$n) {
						$node=&$node["CHILDS"][$j];
						$found=true;
						break;
					} else $nc++;
				}
			}
			if (!$found) return false;
		}
		return $node;
	}
	
	function get_nodes($path,$node="") {
		$n=0;
		$nodes="";
		while ($nod=$this->get_node($path."[$n]",$node)) {
			$nodes[]=$nod;
			$n++;
		}
		return $nodes;
	}
	
	function get_sub_nodes($path, $node="") {
		$el_node = $this->get_node($path, $node);
		if ($el_node['CHILDS']) 
			return $el_node['CHILDS'];
		return false;
	}
	
	function get_datas($node,$force_entities=false) {
		$char="";
		if ($node["TYPE"]!=1) return false;
		//Recherche des fils et v�rification qu'il n'y a que du texte !
		$flag_text=true;
		if (isset($node["CHILDS"]))
			for ($i=0; $i<count($node["CHILDS"]); $i++) {
				if ($node["CHILDS"][$i]["TYPE"]!=2) $flag_text=false;
			}
		if ((!$flag_text)&&(!$force_entities)) {
			$force_entities=true;
		}
		if (isset($node["CHILDS"]))
			for ($i=0; $i<count($node["CHILDS"]); $i++) {
				if ($node["CHILDS"][$i]["TYPE"]==2)
					if ($force_entities) 
						$char.=htmlspecialchars($node["CHILDS"][$i]["DATA"],ENT_NOQUOTES,$this->charset);
					else $char.=$node["CHILDS"][$i]["DATA"];
				else {
					$char.="<".$node["CHILDS"][$i]["NAME"];
					if (count($node["CHILDS"][$i]["ATTRIBS"])) {
						foreach ($node["CHILDS"][$i]["ATTRIBS"] as $key=>$val) {
							$char.=" ".$key."=\"".htmlspecialchars($val,ENT_NOQUOTES,$this->charset)."\"";
						}
					}
					$char.=">";
					$char.=$this->get_datas($node["CHILDS"][$i],$force_entities);
					$char.="</".$node["CHILDS"][$i]["NAME"].">";
				}
			}
		return $char;
	}
	
	function get_attributes($node) {
		if ($node["TYPE"]!=1) return false;
		return $node["ATTRIBS"];
	}
	
	function get_node_name($node) {
		return $node["NAME"];
	}

	function get_node_value($node) {
		return $this->get_datas($node);
	}
	
	function get_node_type($node) {
		return $node["TYPE"];		
	}
	
	function get_value($path,$node="") {
		$value="";
		$elt=$this->get_node($path,$node);
		if ($elt) {
			$paths=explode("/",$path);
			$pelt=explode("@",$paths[count($paths)-1]);
			if (count($pelt)>1) {
				$a=$pelt[0];
				//Recherche de l'attribut
				if (preg_match("/\[([0-9]*)\]$/",$a,$matches)) {
					$attr=substr($a,0,strlen($a)-strlen($matches[0]));
					$n=$matches[1];
				} else {
					$attr=$a;
					$n=0;
				}
				$nc=0;
				$found=false;
				foreach($elt["ATTRIBS"] as $key=>$val) {
					if ($key==$attr) {
						//C'est celui l� !!
						if ($nc==$n) {
							$value=$val;
							$found=true;
							break;
						} else $nc++;
					}
				}
				if (!$found) $value="";
			} else {
				$value=$this->get_datas($elt);
			}
		}
		return $value;
	}
	
	function get_values($path,$node="") {
		$n=0;
		while ($elt=$this->get_node($path."[$n]",$node)) {
			$elts[$n]=$elt;
			$n++;
		}
		if (count($elts)) {
			for ($i=0; $i<count($elts); $i++) {
				$elt=$elts[$i];
				$paths=explode("/",$path);
				$pelt=explode("@",$paths[count($paths)-1]);
				if (count($pelt)>1) {
					$a=$pelt[0];
					//Recherche de l'attribut
					if (preg_match("/\[([0-9]*)\]$/",$a,$matches)) {
						$attr=substr($a,0,strlen($a)-strlen($matches[0]));
						$n=$matches[1];
					} else {
						$attr=$a;
						$n=0;
					}
					$nc=0;
					$found=false;
					foreach($elt["ATTRIBS"] as $key=>$val) {
						if ($key==$attr) {
							//C'est celui l� !!
							if ($nc==$n) {
								$values[]=$val;
								$found=true;
								break;
							} else $nc++;
						}
					}
					if (!$found) $values[]="";
				} else {
					$values[]=$this->get_datas($elt);
				}
			}
		}
		return $values;
	}
}

class xml_dom_explainresponse extends xml_dom_sru {
	var $in_record_data=false;
	var $remove_namespace_information = false;
	var $tag_and_attribs_name_lowercase;
	var $xlm_namespaces = array();
		
	function xml_dom_explainresponse($xml,$charset="iso-8859-1", $remove_namespace_information=false, $tag_and_attribs_name_lowercase=true) {
		$this->remove_namespace_information = $remove_namespace_information;
		$this->tag_and_attribs_name_lowercase = $tag_and_attribs_name_lowercase;
		parent::xml_dom_sru($xml,$charset);
	}
	
	function startElement($parser,$name,$attribs) {
		
		if ($this->remove_namespace_information) {
			if (strpos($name, ':'))
				$name = substr($name, strpos($name, ':')+1);
		}
	
		if ($this->tag_and_attribs_name_lowercase) {
			$name = strtolower($name);			
			$attribs = array_change_key_case($attribs, CASE_LOWER);
		}
		
		parent::startElement($parser,$name,$attribs);
	}
	
	function endElement($parser,$name) {
		parent::endElement($parser,$name);		
	}	
}

class xml_dom_RetrieveResponse extends xml_dom_sru {
	var $in_record_data=false;
	var $remove_namespace_information = false;
	var $tag_and_attribs_name_lowercase;
	var $xlm_namespaces = array();
		
	function xml_dom_RetrieveResponse($xml,$charset="iso-8859-1", $remove_namespace_information=false, $tag_and_attribs_name_lowercase=true) {
		$this->remove_namespace_information = $remove_namespace_information;
		$this->tag_and_attribs_name_lowercase = $tag_and_attribs_name_lowercase;
		parent::xml_dom_sru($xml,$charset);
	}
	
	function startElement($parser,$name,$attribs) {
		if (!$this->in_record_data) {
			foreach($attribs as $key => $value) {
				if (preg_match('/.*:(.+)/', $key))
					$this->xlm_namespaces[strtolower($key)] = $value;
			}

			if ($this->tag_and_attribs_name_lowercase) {
				$name = strtolower($name);			
				$attribs = array_change_key_case($attribs, CASE_LOWER);
			}

			if (preg_match('/^:?recorddata$/', substr($name, strpos($name, ':'))) && $this->depth <= 4) {
				$this->in_record_data = true;
			}
		
			if ($this->remove_namespace_information) {
				if (strpos($name, ':'))
					$name = substr($name, strpos($name, ':')+1);
			}			
		}
	
		parent::startElement($parser,$name,$attribs);
	}
	
	function endElement($parser,$name) {
		if ($this->tag_and_attribs_name_lowercase) {
			$name = strtolower($name);			
		}
		if (preg_match('/^:?recorddata$/', substr($name, strpos($name, ':')+1)) && $this->depth <= 4) {
			$this->in_record_data = false;
		}
		parent::endElement($parser,$name);		
	}	
}

//Classe d'analyse d'un r�sultat de requ�te
class sru_analyse_request {
	var $data = '';
	var $operation;
	var $result;
	var $error = false;
	var $error_message = '';
	var $schema_config="";
	var $style_sheets_to_apply;
	var $mode;
	
	function sru_analyse_request ($data, $operation, $schema_config="", $mode=SR_MODE_AUTO) {
		$this->data = $data;
		$this->operation = $operation; 
		$this->schema_config = $schema_config;
		$this->mode = $mode;
	}
	
	function process($charset='ISO-8859-1') {
		return false;
	} 
}

//Classe d'analyse des requ�tes "explain"
class sru_analyse_explain extends sru_analyse_request {
	var $data = '';
	var $operation;
	var $record_schema;
	
	function sru_analyse_explain ($data, $operation, $schema_config="", $mode=SR_MODE_AUTO) {
		parent::sru_analyse_request($data, $operation, $schema_config, $mode);
	}
	
	function process($charset='ISO-8859-1') {
		$result = array();
		$dom = new xml_dom_explainresponse($this->data, $charset, true);
		$node = $dom->get_node('explainresponse/record');
		
		//R�cup�ration et v�rification de la suportabilit� du record schema
		$buffer = $dom->get_value('recordschema', $node);
		if (!$buffer) {
			$this->error = true;
			$this->error_message = 'No Record Schema';
			return false;
		}
		else {
			$this->record_schema = '';
			if (array_key_exists($buffer, $this->schema_config))
				$this->record_schema = $buffer;
			else {
				foreach($this->schema_config as $aschema_id => $aschema_content) {
					if (in_array($buffer, $aschema_content["long_formats"])){
						$this->record_schema = $aschema_id;
						break;						
					}
				}
			}	
/*			if (!$this->record_schema) {
				$this->error = true;
				$this->error_message = 'Unknown Record Schema: '.$buffer;
				return false;				
			}*/
			
			$result["recordSchema"] = $this->record_schema;
		} 

		//Propriet� du r�sultat
		$buffer = $dom->get_value('recordpacking', $node);
		if ($buffer)
			$result["recordPacking"] = $buffer; 

		$buffer = $dom->get_value('recordidentifier', $node);
		if ($buffer)
			$result["recordIdentifier"] = $buffer;
			
		$buffer = $dom->get_value('recordposition', $node);
		if ($buffer)
			$result["recordPosition"] = $buffer;
			
		if ($node2 = $dom->get_node('recorddata/explain/serverinfo', $node)) {
			$result["serverInfo"] = array();
			
			$attribs = $dom->get_attributes($node2);
			if (isset($attribs['protocol']))
				$result["serverInfo"]['protocol'] = $attribs['protocol'];
			
			if ($buffer = $dom->get_value('host', $node2))
				$result["serverInfo"]['host'] = $buffer;
			if ($buffer = $dom->get_value('port', $node2))
				$result["serverInfo"]['port'] = $buffer;
			if ($buffer = $dom->get_value('database', $node2))
				$result["serverInfo"]['database'] = $buffer;
			if ($buffer = $dom->get_value('numrecs', $node2))
				$result["serverInfo"]['numRecs'] = $buffer;				
			if ($buffer = $dom->get_value('lastupdate', $node2))
				$result["serverInfo"]['lastUpdate'] = $buffer;
		}
		
		if ($node2 = $dom->get_node('recorddata/explain/authentication', $node)) {
			$result["authentication"] = array();			
			
			if ($buffer = $dom->get_value('user', $node2))
				$result["serverInfo"]['user'] = $buffer;
			if ($buffer = $dom->get_value('group', $node2))
				$result["serverInfo"]['group'] = $buffer;
			if ($buffer = $dom->get_value('password', $node2))
				$result["serverInfo"]['password'] = $buffer;
		}
		
		if ($node2 = $dom->get_node('recorddata/explain/databaseinfo', $node)) {
			$result["databaseinfo"] = array();			
			
			if ($node3 = $dom->get_node('title', $node2)){
				$result["databaseinfo"]["title"]["value"] = $dom->get_value('title', $node2);
	
				$attribs = $dom->get_attributes($node3);
				if (isset($attribs['lang']))
					$result["databaseinfo"]["title"]["lang"] = $attribs['lang'];								
				if (isset($attribs['primary']))
					$result["databaseinfo"]["title"]["primary"] = $attribs['primary'];
			}
			
			if ($node3 = $dom->get_node('description', $node2)){
				$result["databaseinfo"]["description"]["value"] = $dom->get_value('description', $node2);
	
				$attribs = $dom->get_attributes($node3);
				if (isset($attribs['lang']))
					$result["databaseinfo"]["description"]["lang"] = $attribs['lang'];								
				if (isset($attribs['primary']))
					$result["databaseinfo"]["description"]["primary"] = $attribs['primary'];
			}			

			if ($buffer = $dom->get_value('author', $node2))
				$result["databaseinfo"]['author'] = $buffer;			

			if ($buffer = $dom->get_value('contact', $node2))
				$result["databaseinfo"]['contact'] = $buffer;

			if ($buffer = $dom->get_value('extent', $node2))
				$result["databaseinfo"]['extent'] = $buffer;

			if ($buffer = $dom->get_value('history', $node2))
				$result["databaseinfo"]['history'] = $buffer;

			if ($buffer = $dom->get_value('langusage', $node2))
				$result["databaseinfo"]['langusage'] = $buffer;

			if ($buffer = $dom->get_value('restriction', $node2))
				$result["databaseinfo"]['restriction'] = $buffer;

		}	
			
		if ($node2 = $dom->get_node('recorddata/explain/metainfo', $node)) {
			$result["metainfo"] = array();			
			
			if ($buffer = $dom->get_value('restriction', $node2))
				$result["metainfo"]['datemodified'] = $buffer;
			if ($buffer = $dom->get_value('aggregatedfrom', $node2))
				$result["metainfo"]['aggregatedfrom'] = $buffer;
			if ($buffer = $dom->get_value('dateaggregated', $node2))
				$result["metainfo"]['dateaggregated'] = $buffer;
		}
		
		if ($nodesindex = $dom->get_nodes('recorddata/explain/indexinfo', $node)) {
			$result["indexinfo"] = array();
			foreach($nodesindex as $node2) {
				$nodes3 = $dom->get_nodes('index', $node2);
				foreach ($nodes3 as $nodeindex) {
					$index = array();
					$nodes4 = $dom->get_nodes('title', $nodeindex);
					$nodes4values = $dom->get_values('title', $nodeindex);				
					for($i=0, $count=count($nodes4); $i<$count; $i++) {
						$index[$i]['title'] = $nodes4values[$i];
						$index[$i]['lang'] = 'en';					
					}
					
					$node4 = $dom->get_node('map/name', $nodeindex);
					$node4attribs = $dom->get_attributes($node4);
	
					if (isset($node4attribs["indexset"]))
						$index_set = $node4attribs["indexset"];
					else if (isset($node4attribs["set"]))					
						$index_set = $node4attribs["set"];
					else 
						$index_set = "cql";
					$node4value = $dom->get_value('map/name', $nodeindex);
					$index['map'] = $node4value;
					$index['set'] = $index_set;
	//				print_r($index);
					$result["indexinfo"][] = $index;
				}
			}
		}
		
		if ($node2 = $dom->get_node('recorddata/explain/configinfo', $node)) {
			$result["configinfo"] = array();			
			
			$nodes3 = $dom->get_nodes('default', $node2);
			$nodes3values = $dom->get_values('default', $node2);
			for($i=0, $count=count($nodes3); $i<$count; $i++) {
				$attribs = $dom->get_attributes($nodes3[$i]);
				$name = $attribs["type"];
				$value = $nodes3values[$i];
				$result["configinfo"]["defaults"][$name] = $value;
			}			
			
			$nodes3 = $dom->get_nodes('supports', $node2);
			$nodes3values = $dom->get_values('supports', $node2);
			for($i=0, $count=count($nodes3); $i<$count; $i++) {
				$attribs = $dom->get_attributes($nodes3[$i]);
				$name = $attribs["type"];
				$value = $nodes3values[$i];
				$result["configinfo"][$name][] = $value;
			}			
			
		}
		
		if ($nodesschema = $dom->get_nodes('recorddata/explain/schemainfo', $node)) {
			$result["schemainfo"] = array();	
			foreach($nodesschema as $node2) {
				$nodes3 = $dom->get_nodes('schema', $node2);
				for($i=0, $count=count($nodes3); $i<$count; $i++) {
					$attribs = $dom->get_attributes($nodes3[$i]);
					$name = $attribs["name"];
					$value = $dom->get_value('title', $nodes3[$i]);
					$result["schemainfo"][$name] = $value;
				}				
			}
		}
			
		$this->result = $result;
		return true;
	} 	
}

//Classe d'analyse des requ�tes "searchRetrieve"
class sru_analyse_searchretrieve extends sru_analyse_request {
	var $data = '';
	var $operation;
	var $error = false;
	var $error_message = '';
	
	function sru_analyse_searchretrieve ($data, $operation, $schema_config="", $mode=SR_MODE_AUTO) {
		parent::sru_analyse_request($data, $operation, $schema_config, $mode);
	}
	
	function record_to_xml_unimarc($record, $style_sheets, $charset) {
		global $xslt_base_path;
		global $debug;
		if ($debug)
			highlight_string(print_r($record, true));
//		file_put_contents('dublincoreextended1.xml', $record);
		$xh = xslt_create();
//		echo $charset;
		xslt_set_encoding($xh, $charset);
		$result = $record;
		foreach ($style_sheets as $style_sheet) {
			if ($debug)
				echo '<h3>'.$style_sheet.'</h3>';
			/* Traitement du document */
			$arguments = array(
		   	  '/_xml' => $result,
		   	  '/_xsl' => $style_sheet
			);
			$result = xslt_process($xh, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments);
			if ($debug)
				highlight_string(print_r($result, true));
			if (!$result) {
				$this->error = true;
				$this->error_message = "Sorry, notice could not be transformed by $style_sheet the reason is that ".xslt_error($xh)." and the error code is ".xslt_errno($xh);
			} 			
		}
		xslt_free($xh);
		return $result;		
	}
	
	function parse_record($node, $dom, $charset, $schema_config="") {
		global $xslt_base_path;
		
		$result = array();
		$buffer = $dom->get_value('recordpacking', $node);
		if ($buffer)
			$result["record_packing"] = $buffer;


		if ($this->mode == SR_MODE_AUTO) {
			//R�cup�ration et v�rification de la suportabilit� du record schema
			$buffer = $dom->get_value('recordschema', $node);
			if (!$buffer) {
				$this->error = true;
				$this->error_message = 'No Record Schema';
				return false;
			}
			else {
				$this->record_schema = '';
				if (array_key_exists($buffer, $schema_config))
					$this->record_schema = $buffer;
				else {
					foreach($schema_config as $aschema_id => $aschema_content) {
						if (in_array($buffer, $aschema_content["long_formats"])){
							$this->record_schema = $aschema_id;
							break;						
						}
					}
				}	
			if (!$this->record_schema)
				$this->record_schema = $buffer;			
	/*			if (!$record_schema) {
					$this->error = true;
					$this->error_message = 'Unknown Record Schema: '.$buffer;
					return false;				
				}*/
				$result["recordSchema"] = $this->record_schema;
			}			
		}
 
		$buffer = $dom->get_value('recordposition', $node);
		if ($buffer)
			$result["record_position"] = $buffer;

		$buffer = $dom->get_value('recordidentifier', $node);
		if ($buffer)
			$result["record_identifier"] = $buffer;
		
		$buffer_node = $dom->get_node('recorddata', $node);
		$buffer = $dom->get_datas($buffer_node, true);
		if ($buffer)
			$result["record_xml"] = $buffer;		

//		print_r($schema_config);

		if ($this->mode == SR_MODE_AUTO) {
			$to_unimarc_style_sheets = array();		
			if ($this->record_schema ) {
				if (isset($schema_config[$this->record_schema]["stylesheets"])) {
					foreach($schema_config[$this->record_schema]["stylesheets"] as $style_sheet_filename)
						$to_unimarc_style_sheets[] = file_get_contents($xslt_base_path."/".$style_sheet_filename);
				}
			}
		}
		else if ($this->mode == SR_MODE_STYLESHEETS) {
			$to_unimarc_style_sheets = $this->style_sheets_to_apply;
		}
	
		if ($to_unimarc_style_sheets) {
//			highlight_string(print_r($to_unimarc_style_sheets, true));
			$xml_namespaces_xml = '';
			foreach($dom->xlm_namespaces as $key => $value) {
				$xml_namespaces_xml .= $key.'="'.$value.'" ';
			}
			$record_xml = '<?xml version="1.0" encoding="'.$charset.'"?>';
			$record_xml .= '<record '.$xml_namespaces_xml.'>';
			$record_xml .= $result["record_xml"];
			$record_xml .= '</record>';
			
			if ($buffer = $this->record_to_xml_unimarc($record_xml, $to_unimarc_style_sheets, $charset)) {
				$result["record_unimarc"] = $buffer;				
			}
			else {
				return false;
			}
			
		}
		else {
			echo 'Aucune Style Sheet disponible pour le schema '.$this->record_schema.'<br />';
		}
		
/*		if ($record_schema == 'dcx')
			$data_path = 'recorddata/dcx';
		else 
			$data_path = 'recorddata';		
		
		if ($node2 = $dom->get_sub_nodes($data_path, $node)) {
			foreach($node2 as $recorddata) {
				if ($dom->get_node_type($recorddata) != 2)
					$result['data'][$dom->get_node_name($recorddata)][] = $dom->get_node_value($recorddata);
			}
		}*/			
			
		return $result;		
	}
	
	function process($charset='ISO-8859-1') {
		$result = array();
		$dom = new xml_dom_RetrieveResponse($this->data, $charset, true);
		$node = $dom->get_node('searchretrieveresponse');
		
		$buffer = $dom->get_value('numberofrecords', $node);
		if ($buffer)
			$result["number_of_records"] = $buffer;
		else
			$result["number_of_records"] = 0;
			
		$buffer = $dom->get_value('resultsetid', $node);
		if ($buffer)
			$result["result_set_id"] = $buffer; 

		$buffer = $dom->get_value('resultsetidletime', $node);
		if ($buffer)
			$result["result_set_idle_time"] = $buffer;
			
		$buffer = $dom->get_value('nextrecordposition', $node);
		if ($buffer)
			$result["next_record_position"] = $buffer;

		if ($node2 = $dom->get_nodes('records/record', $node)) {
			$count=0;
			foreach($node2 as $record) {
				$arecord = $this->parse_record($record, $dom, $charset, $this->schema_config);
				if ($arecord) {
					$result['records'][$count] = $arecord;
					$count++;					
				}
				else {
					return false;
				}
			}
		}
			
		$this->result = $result;
		return true;
	} 	
}


class sru_get_data {
    var $error=false;
    var $error_message="";
    var $response_date;			//Date de r�ponse
    var $charset="iso-8859-1";
    var $time_out;				//Temps maximum d'interrogation de la source
    var $xml_parser;			//Ressource parser
    var $retry_after;			//D�lais avant r��ssai
    var $data = '';
    					
    function sru_get_data($url="", $charset="iso-8859-1", $time_out="") {
    	$this->charset=$charset;
    	$this->time_out=$time_out;
    	if ($url) $this->get_data($url);
    }
    
    function parse_xml($ch,$data) {
    	if (!$this->retry_after) {
	    	//Parse de la ressource
	    	if (!xml_parse($this->xml_parser, $data)) {
	       		$this->error_message=sprintf("XML error: %s at line %d",xml_error_string(xml_get_error_code($this->xml_parser)),xml_get_current_line_number($this->xml_parser));
	       		$this->error=true;
	       		return strlen($data);
	    	} 
    	}
    	return strlen($data);
	}
    
    function verif_header($ch,$headers) {
    	$h=explode("\n",$headers);
    	for ($i=0; $i<count($h); $i++) {
    		$v=explode(":",$h[$i]);
    		if ($v[0]=="Retry-After") { $this->retry_after=$v[1]*1; }
    	}
    	return strlen($headers);
    }
    
    function get_data($url) {
    	//Remise � z�ro des erreurs
    	$this->error=false;
    	$this->error_message="";
    	
    	//Initialisation de la ressource
    	$ch = curl_init();
		// configuration des options CURL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_WRITEFUNCTION,array(&$this,"parse_xml"));
		curl_setopt($ch, CURLOPT_HEADERFUNCTION,array(&$this,"verif_header"));	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		if ($this->time_out) curl_setopt($ch, CURLOPT_TIMEOUT,$this->time_out);
    	//Reinitialisation du "retry_after"
		$this->retry_after="";    	
    	
		configurer_proxy_curl($ch);
		
    	//Explosion des arguments de la requ�te pour ceux qui ne respectent pas la norme !!
    	$query=substr($url,strpos($url,"?")+1);
    	$query=explode("&",$query);
    	for ($i=0; $i<count($query); $i++) {
    		if (strpos($query[$i],"operation")!==false) {
    			$operation=substr($query[$i],9);
    			break;
    		}
    	}    	
    	
    	//Initialisation du parser
		$this->xml_parser=xml_parser_create("utf-8");
		xml_parser_set_option( $this->xml_parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option( $this->xml_parser, XML_OPTION_SKIP_WHITE, 1 );
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$n_try=0;
		$data =  $cexec=curl_exec($ch);
		while (($cexec)&&($this->retry_after)&&($n_try<3)) {
			$n_try++; 
			sleep((int)$this->retry_after*1);
			$this->retry_after="";
			$data = $cexec=curl_exec($ch);
		}
		if (!$cexec) {
			$this->error=true;
			$this->error_message=curl_error($ch);
		}

		xml_parser_free($this->xml_parser);
		$this->xml_parser="";
		curl_close($ch);
		
		if ($this->error) { $this->error_message.=" - ".$url; unset($s); return; }
		
		$this->data = $data;
    }
} 	

class sru_request {
	var $base_url;
	var $operation;
	var $parameters;
	var $operations_allowed = array("explain", "searchRetrieve");
	var $error = false;
	var $error_message = '';
	var $data = '';
	var $schema_config;
	var $style_sheets_to_apply;
	var $mode;
	
	function sru_request($base_url, $operation, $parameters=array(), $schema_config='', $mode=SR_MODE_AUTO) {
		
		if (!in_array($operation, $this->operations_allowed)) {
			$this->error = true;
			$this->error_message = 'Operation not allowed';
		}
		$this->base_url = $base_url;		
		$this->operation = $operation;
		$this->parameters = $parameters;
		$this->schema_config = $schema_config;
		$this->mode = $mode;
	}
	
	function analyse_response($charset='ISO-8859-1') {
		$parameters = '';
		foreach ($this->parameters as $name => $value)
			$parameters[] .= $name.'='.urlencode($value);
		$url = $this->base_url . '?operation='.$this->operation.'&'.implode('&', $parameters);
		//echo $url;

		$get_data = new sru_get_data($url, $charset);
		
		switch ($this->operation) { 
			case 'explain': {
				$analyser = new sru_analyse_explain($get_data->data, $this->operation, $this->schema_config, SR_MODE_AUTO);
				break;
			}
			case 'searchRetrieve': {
				$analyser = new sru_analyse_searchretrieve($get_data->data, $this->operation, $this->schema_config, $this->mode);
				$analyser->style_sheets_to_apply = $this->style_sheets_to_apply;
				break;
			}
			
		}
		
		$analyser->process($charset);
		if ($analyser->error) {
			print $analyser->error_message;
			return false;
		}
		
//		highlight_string(print_r($analyser->result, true));
		return $analyser->result; 
	}
	
}


global $debug;
$debug = false;

global $xslt_base_path;
$xslt_base_path = "admin/connecteurs/in/sru/xslt";
//$xslt_base_path = "./xslt";

global $script_base_path;
$script_base_path = "admin/connecteurs/in/sru";
//$script_base_path = ".";

?>