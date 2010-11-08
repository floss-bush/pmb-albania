<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: entrez.class.php,v 1.2 2009-10-14 11:43:29 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;

if (version_compare(PHP_VERSION,'5','>=') && extension_loaded('xsl')) {
	if (substr(phpversion(), 0, 1) == "5") @ini_set("zend.ze1_compatibility_mode", "0");
	require_once($include_path.'/xslt-php4-to-php5.inc.php');
}

require_once($class_path."/connecteurs.class.php");
require_once($class_path."/nusoap/nusoap.php");

/**There be komodo dragons**/

class entrez extends connector {
	var $available_entrezdatabases = array("pubmed" => "PubMed");
	
    function entrez($connector_path="") {
    	parent::connector($connector_path);
    }
	
    function get_id() {
    	return "entrez";
    }
    
    //Est-ce un entrepot ?
	function is_repository() {
		return 2;
	}
    
    function source_get_property_form($source_id) {
    	global $charset;
    	
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		if (!isset($entrez_database))
			$entrez_database = "pubmed";
		
		if (!isset($entrez_maxresults))
			$entrez_maxresults = 100;
		$entrez_maxresults += 0;
		
		$options = "";
		foreach ($this->available_entrezdatabases as $code => $caption)
			$options .= '<option value="'.$code.'" '.($code == $entrez_database ? "selected" : "").'>'.htmlentities($caption, ENT_QUOTES, $charset).'</option>';

		$form="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["entrez_database"]."</label>
			</div>
			<div class='colonne_suite'>
				<select name=\"entrez_database\">
					".$options."
				</select>
			</div>
		</div>";
		
		$form.="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["entrez_maxresults"]."</label>
			</div>
			<div class='colonne_suite'>
				<input name=\"entrez_maxresults\" type=\"text\" value=\"".$entrez_maxresults."\">
			</div>
		</div>";

		$form.="
	<div class='row'></div>
";
		return $form;
    }
	
    function make_serialized_source_properties($source_id) {
    	global $entrez_database, $entrez_maxresults;
    	$t["entrez_database"]=stripslashes($entrez_database);
    	$t["entrez_maxresults"]=$entrez_maxresults+0;

		$this->sources[$source_id]["PARAMETERS"]=serialize($t);
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
		$this->fetch_global_properties();
		return "";
	}
	
	function make_serialized_properties() {
		$this->parameters="";
	}
    
	function apply_xsl_to_xml($xml, $xsl) {
		global $charset;
		$xh = xslt_create();
		xslt_set_encoding($xh, $charset);
		$arguments = array(
	   	  '/_xml' => $xml,
	   	  '/_xsl' => $xsl
		);
		$result = xslt_process($xh, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments);
		xslt_free($xh);
		return $result;		
	}
	
	//Fonction de recherche
	function search($source_id,$query,$search_id) {
		global $base_path;
		
		$params=$this->get_source_params($source_id);
		$this->fetch_global_properties();
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		if (!isset($entrez_database)) {
			$this->error_message = $this->msg["entrez_unconfigured"];
			$this->error = 1;
			$return;
		}

		$unimarc_pubmed_mapping = array (
			'XXX' => '',
			'200$a' => '[Title]',
			'7XX' => '[Author]',
			'210$c' => '[Journal]',
			'010$a' => '[uid]'
		);
		
		$search_query = "";

		if (count($query) == 1) {
			$aquery_words = explode(" ", $query[0]->values[0]);
			$search_querys=array();
			foreach($aquery_words as $aquery_word) {
				$search_querys[] = $aquery_word.(isset($unimarc_pubmed_mapping[$query[0]->ufield]) ? $unimarc_pubmed_mapping[$query[0]->ufield] : '');
			}
			$search_query=implode(" AND ", $search_querys);
		}
		else {
			foreach($query as $aquery) {
				$aquery_words = explode(" ", $aquery->values[0]);
				$search_querys=array();
				foreach($aquery_words as $aquery_word) {
					$search_querys[] = '"'.$aquery_word.'"'. (isset($unimarc_pubmed_mapping[$aquery->ufield]) ? $unimarc_pubmed_mapping[$aquery->ufield] : '');
				}
				$sub_search_query = implode(' AND ', $search_querys);
				if ($search_query)
					$search_query = $search_query . " " . $aquery->inter . " " . $sub_search_query;
				else 
					$search_query = $sub_search_query;
			}			
		}

		require_once 'entrez_protocol.class.php';
		$entrez_client = new entrez_request($entrez_database, $search_query);
		$entrez_client->get_next_idlist($entrez_maxresults);
		$entrez_client->retrieve_currentidlist_notices();
		$responses = $entrez_client->get_current_responses();
		
		$xsl_transform = file_get_contents($base_path."/admin/connecteurs/in/entrez/xslt/pubmed_to_unimarc.xsl");
		$notices_pmbunimarc = array();
		foreach ($responses as $aresponse) {
			$anotice = $this->apply_xsl_to_xml($aresponse, $xsl_transform);
			$notices_pmbunimarc[] = $anotice;
		}
		foreach($notices_pmbunimarc as $anotice)
			$this->rec_records($anotice, $source_id, $search_id, $search_query);
	}
	
	function rec_records($noticesxml, $source_id, $search_id, $search_term="") {
		global $charset,$base_path;
		if (!trim($noticesxml))
			return;

		$rec_uni_dom=new xml_dom_entrez($noticesxml,$charset);
		$notices=$rec_uni_dom->get_nodes("unimarc/notice");
		foreach ($notices as $anotice) {
			$this->rec_record($rec_uni_dom, $anotice, $source_id, $search_id, $search_term);
		}
	}
	
	function rec_record($rec_uni_dom, $noticenode, $source_id, $search_id, $search_term="") {
		global $charset,$base_path;
		
		if (!$rec_uni_dom->error) {
			//Initialisation
			$ref="";
			$ufield="";
			$usubfield="";
			$field_order=0;
			$subfield_order=0;
			$value="";
			$date_import=date("Y-m-d H:i:s",time());
			
			$fs=$rec_uni_dom->get_nodes("f", $noticenode);

			$fs[] = array("NAME" => "f", "ATTRIBS" => array("c" => "1000"), 'TYPE' => 1, "CHILDS" => array(array("DATA" => $search_term, "TYPE" => 2)));
			//Recherche du 001
			if ($fs)
				for ($i=0; $i<count($fs); $i++) {
					if ($fs[$i]["ATTRIBS"]["c"]=="001") {
						$ref=$rec_uni_dom->get_datas($fs[$i]);
						break;
					}
				}
			if (!$ref) $ref = md5($record);
			//Mise à jour
			if ($ref) {
				//Si conservation des anciennes notices, on regarde si elle existe
				if (!$this->del_old) {
					$requete="select count(*) from entrepot_source_".$source_id." where ref='".addslashes($ref)."'";
					$rref=mysql_query($requete);
					if ($rref) $ref_exists=mysql_result($rref,0,0);
				}
				//Si pas de conservation des anciennes notices, on supprime
				if ($this->del_old) {
					$requete="delete from entrepot_source_".$source_id." where ref='".addslashes($ref)."'";
					mysql_query($requete);
				}
				$ref_exists = false;
				//Si pas de conservation ou refï¿½rence inexistante
				if (($this->del_old)||((!$this->del_old)&&(!$ref_exists))) {
					//Insertion de l'entï¿½te
					$n_header["rs"]=$rec_uni_dom->get_value("unimarc/notice/rs");
					$n_header["ru"]=$rec_uni_dom->get_value("unimarc/notice/ru");
					$n_header["el"]=$rec_uni_dom->get_value("unimarc/notice/el");
					$n_header["bl"]=$rec_uni_dom->get_value("unimarc/notice/bl");
					$n_header["hl"]=$rec_uni_dom->get_value("unimarc/notice/hl");
					$n_header["dt"]=$rec_uni_dom->get_value("unimarc/notice/dt");
					
					//Rï¿½cupï¿½ration d'un ID
					$requete="insert into external_count (recid, source_id) values('".addslashes($this->get_id()." ".$source_id." ".$ref)."', ".$source_id.")";
					$rid=mysql_query($requete);
					if ($rid) $recid=mysql_insert_id();
					
					foreach($n_header as $hc=>$code) {
						$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid, search_id) values(
						'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
						'".$hc."','',-1,0,'".addslashes($code)."','',$recid, '$search_id')";
						mysql_query($requete);
					}
					if ($fs)
					for ($i=0; $i<count($fs); $i++) {
						$ufield=$fs[$i]["ATTRIBS"]["c"];
						$field_order=$i;
						$ss=$rec_uni_dom->get_nodes("s",$fs[$i]);
						if (is_array($ss)) {
							for ($j=0; $j<count($ss); $j++) {
								$usubfield=$ss[$j]["ATTRIBS"]["c"];
								$value=$rec_uni_dom->get_datas($ss[$j]);
								$subfield_order=$j;
								$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid, search_id) values(
								'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
								'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
								' ".addslashes(strip_empty_words($value))." ',$recid, '$search_id')";
								mysql_query($requete);
							}
						} else {
							$value=$rec_uni_dom->get_datas($fs[$i]);
							$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid, search_id) values(
							'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
							'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
							' ".addslashes(strip_empty_words($value))." ',$recid, '$search_id')";
							mysql_query($requete);
						}
					}
				}
				$this->n_recu++;
			}
		}
	}
	
}

?>