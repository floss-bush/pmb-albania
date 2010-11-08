<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmbesSearch.class.php,v 1.15 2010-08-13 08:35:20 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/external_services.class.php");

define("SEARCH_ALL",0);
define("SEARCH_TITLE",1);
define("SEARCH_AUTHOR",2);
define("SEARCH_EDITOR",3);
define("SEARCH_COLLECTION",4);
define("SEARCH_CATEGORIES",5);

define("ERROR_SEARCH_UNKNOWN_FIELD",1);


class pmbesSearch extends external_services_api_class {
	var $error=false;		//Y-a-t-il eu une erreur
	var $error_message="";	//Message correspondant à l'erreur

	function restore_general_config() {
		
	}
	
	function form_general_config() {
		return false;
	}
	
	function save_general_config() {
		
	}
	
	function update_session_date($session_id) {
		global $dbh;
		$sql = "UPDATE es_searchsessions SET es_searchsession_lastseendate = NOW() WHERE es_searchsession_id = '".addslashes($session_id)."'";
		mysql_query($sql, $dbh);
	}
	
	function noticeids_to_recordformats($noticesids, $record_format, $recordcharset='iso-8859-1', $includeLinks=true, $includeItems=false) {
		$converter = new external_services_converter_notices(1, 600);
		$converter->set_params(array("include_links" => $includeLinks, "include_items" => $includeItems, "include_authorite_ids" => true));
		return $converter->convert_batch($noticesids, $record_format, $recordcharset);
	}

	function external_noticeids_to_recordformats($noticesids, $record_format, $recordcharset='iso-8859-1') {
		$converter = new external_services_converter_external_notices(4, 600);
		$converter->set_params(array());
		return $converter->convert_batch($noticesids, $record_format, $recordcharset);
	}
	
	function make_search($search_realm, $PMBUserId, $OPACEmprId) {
		global $dbh;
		global $pmb_external_service_search_cache, $pmb_external_service_session_duration;
		$pmb_external_service_search_cache+=0;
		$pmb_external_service_session_duration+=0;
		$PMBUserId+=0;
		$OPACEmprId+=0;

		$search_cache = new external_services_searchcache($search_realm, '', $PMBUserId, $OPACEmprId);
		$search_cache->update();
		$search_unique_name = $search_cache->search_unique_id;
		$result_count = $search_cache->get_result_count();
		$result_typdoc_list = $search_cache->get_typdoc_list();

		if (!$search_unique_name) {
			return array("searchId"=>0,"nbResults"=>0,"nPerPages"=>20);
		}
		
		//Deletons les sessions trop vieilles
		$sql = "DELETE FROM es_searchsessions WHERE es_searchsession_lastseendate + INTERVAL ".$pmb_external_service_session_duration." SECOND <= NOW()";
		mysql_query($sql, $dbh);
		
		//Générons un numéro de session
		$session_id = md5(microtime());
		$sql = "INSERT INTO es_searchsessions (es_searchsession_id, es_searchsession_searchnum, es_searchsession_searchrealm, es_searchsession_pmbuserid, es_searchsession_opacemprid, es_searchsession_lastseendate) VALUES ('".$session_id."', '".$search_unique_name."', '".addslashes($search_realm)."', ".$PMBUserId.", ".$OPACEmprId.", NOW())";
		mysql_query($sql, $dbh);

		return array("searchId"=>$session_id,"nbResults"=>$result_count,"typdocs"=>$result_typdoc_list);
	}
	

	function simpleSearch($searchType=0,$searchTerm="",$PMBUserId=-1, $OPACEmprId=-1) {
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$searchTerm = utf8_encode($searchTerm);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$searchTerm = utf8_decode($searchTerm);	
		}
		
		switch ($searchType) {
			case SEARCH_ALL:
				$searchId=7;
				break;
			case SEARCH_TITLE:
				$searchId=6;
				break;
			case SEARCH_AUTHOR:
				$searchId=8;
				break;
			case SEARCH_EDITOR:
				$searchId=3;
				break;
			case SEARCH_COLLECTION:
				$searchId=4;
				break;
			default:
				$this->error=ERROR_SEARCH_UNKNOWN_FIELD;
				$this->error_message=$this->msg["unknown_field"];
				$searchId=0;
				break;
		}
		if ($searchId) {
			global $search;
			$search[0]="f_".$searchId;
			$field="field_0_".$search[0];
			global $$field;
			$$field=array($searchTerm);
			$op="op_0_".$search[0];
			global $$op;
			$$op="BOOLEAN";
			
			return $this->make_search('search_simple_fields', $PMBUserId, $OPACEmprId);
			
		} else return "";

	}
	
	function simpleSearchLocalise($searchType=0,$searchTerm="",$PMBUserId=-1, $OPACEmprId=-1,$location,$section=0) {
		global $dbh;
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$searchTerm = utf8_encode($searchTerm);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$searchTerm = utf8_decode($searchTerm);	
		}
		
		$req = "select count(1) from docsloc_section where num_section='".$section."' and num_location='".$location."'";
		$res = mysql_query($req,$dbh);
		
		$sec_valide = false;
		if(mysql_num_rows($res)){
			$sec_valide = true;
		}
		
		switch ($searchType) {
			case SEARCH_ALL:
				$searchId=(($section && $sec_valide) ? 25 : 20);
				break;
			case SEARCH_TITLE:
				$searchId=(($section&& $sec_valide) ? 24 : 19);
				break;
			case SEARCH_AUTHOR:
				$searchId=(($section && $sec_valide) ? 26 : 21);
				break;
			case SEARCH_EDITOR:
				$searchId=(($section && $sec_valide) ? 22 : 17);
				break;
			case SEARCH_COLLECTION:
				$searchId=(($section && $sec_valide) ? 23 : 18);
				break;
			default:
				$this->error=ERROR_SEARCH_UNKNOWN_FIELD;
				$this->error_message=$this->msg["unknown_field"];
				$searchId=0;
				break;
		}
		if ($searchId) {
			global $search;
			$search[0]="f_".$searchId;
			$field="field_0_".$search[0];
			global $$field;
			$$field=array($searchTerm);
			$op="op_0_".$search[0];
			global $$op;
			$$op="BOOLEAN";
			$fieldvar="fieldvar_0_".$search[0];
			global $$fieldvar;
			${$fieldvar}["location"][0] = $location;
			if($section){
				${$fieldvar}["section"][0] = $section;
			}
			
			
			return $this->make_search('search_simple_fields', $PMBUserId, $OPACEmprId);
			
		} else return "";

	}
	
	function getAdvancedSearchFields($search_realm, $vlang, $fetch_values) {
		global $dbh, $msg, $lang, $include_path, $class_path;

		//Allons chercher les infos dans le cache si elles existent
		if ($fetch_values) {
			$cache_ref = "getAdvancedSearchFields_results_valued_".$lang."_".$search_realm;
		}
		else {
			$cache_ref = "getAdvancedSearchFields_results_".$lang."_".$search_realm;
		}
		$es_cache = new external_services_cache('es_cache_blob', 86400);
		$cached_result = $es_cache->decache_single_object($cache_ref, CACHE_TYPE_MISC);
		if ($cached_result !== false) {
			$cached_result = unserialize(base64_decode($cached_result));
			return $cached_result;
		}
		
		$opac_realm=false;
		$full_path='';
		if (substr($search_realm, 0, 5) == 'opac|') {
			$search_realm = substr($search_realm, 5);
			global $base_path;
			$full_path = $base_path."/opac_css/includes/search_queries/";
			$opac_realm = true;
		}
		
		//Ajoutant la langue demandée à l'environnement
		if ($opac_realm) {
			if (file_exists("$base_path/opac_css/includes/messages/$vlang.xml")) {
				//Allons chercher les messages
				include_once("$class_path/XMLlist.class.php");
				$messages = new XMLlist("$base_path/opac_css/includes/messages/$vlang.xml", 0);
				$messages->analyser();
				global $msg;
				$msg = $messages->table;
			}
		}
		else {
			if ($vlang != $lang && file_exists("$include_path/messages/$vlang.xml")) {
				//Allons chercher les messages
				include_once("$class_path/XMLlist.class.php");
				$messages = new XMLlist("$include_path/messages/$vlang.xml", 0);
				$messages->analyser();
				global $msg;
				$msg = $messages->table;
			}
		}

		$s=new search(false, $search_realm, $full_path);
		$results=array();
		foreach ($s->fixedfields as $id => $content) {
			$results[] = $this->getAdvancedSearchField($id, $search_realm, $vlang, $fetch_values, $s, true);
		}
		
		//Mettons le resultat dans le cache
		$es_cache = new external_services_cache('es_cache_blob', 86400);
		$es_cache->encache_single_object($cache_ref, CACHE_TYPE_MISC, base64_encode(serialize($results)));
		
		return $results;
	}
	
	function getAdvancedSearchField($field_id, $search_realm, $vlang, $fetch_values, $search_object=NULL, $nocache=false) {
		global $dbh, $msg, $lang, $include_path, $class_path;
		if (!$nocache) {
			//Allons chercher les infos dans le cache si elles existent
			$cache_ref = "getAdvancedSearchField_result_".$field_id."_".$lang."_".$search_realm;
			$es_cache = new external_services_cache('es_cache_blob', 86400);
			$cached_result = $es_cache->decache_single_object($cache_ref, CACHE_TYPE_MISC);
			if ($cached_result !== false) {
				$cached_result = unserialize(base64_decode($cached_result));
				return $cached_result;
			}
		}
		
		//Si on nous passe le $search_object, c'est que tout l'environnement est prêt
		if (!$search_object) {

			$opac_realm=false;
			$full_path='';
			if (substr($search_realm, 0, 5) == 'opac|') {
				$search_realm = substr($search_realm, 5);
				global $base_path;
				$full_path = $base_path."/opac_css/includes/search_queries/";
				$opac_realm = true;
			}
			
			//Ajoutant la langue demandée à l'environnement
			if ($opac_realm) {
				if (file_exists("$base_path/opac_css/includes/messages/$vlang.xml")) {
					//Allons chercher les messages
					include_once("$class_path/XMLlist.class.php");
					$messages = new XMLlist("$base_path/opac_css/includes/messages/$vlang.xml", 0);
					$messages->analyser();
					global $msg;
					$msg = $messages->table;
				}
			}
			else {
				if ($vlang != $lang && file_exists("$include_path/messages/$vlang.xml")) {
					//Allons chercher les messages
					include_once("$class_path/XMLlist.class.php");
					$messages = new XMLlist("$include_path/messages/$vlang.xml", 0);
					$messages->analyser();
					global $msg;
					$msg = $messages->table;
				}
			}
			
			$search_object=new search(false, $search_realm, $full_path);
		}

		if (!isset($search_object->fixedfields[$field_id]))
			throw new Exception("id not found");

		$content = $search_object->fixedfields[$field_id];
		
		$aresult = array("operators" => array());
		$aresult["id"] = $field_id;
		$aresult["label"] = $content["TITLE"];
		$aresult["type"] = $content["INPUT_TYPE"];
		foreach($content["QUERIES"] as $aquery) {
			$aresult["operators"][] = array("id" => $aquery["OPERATOR"], "label" => $search_object->operators[$aquery["OPERATOR"]]);
		}
		$aresult["values"] = array();
		if ($fetch_values) {
			switch ($content["INPUT_TYPE"]) {
				case "query_list":
					$aresult["values"] = array();
	   				$requete=$content["INPUT_OPTIONS"]["QUERY"][0]["value"];
	   				$resultat=mysql_query($requete, $dbh);
	   				while ($opt=mysql_fetch_row($resultat)) {
						$aresult["values"][] = array(
							"value_id" => $opt[0],
							"value_caption" => utf8_normalize($opt[1])
						);
	   				}
					break;
				case "list":
					if (!isset($content["INPUT_OPTIONS"]["OPTIONS"][0]["OPTION"]))
						break;
					foreach ($content["INPUT_OPTIONS"]["OPTIONS"][0]["OPTION"] as $aoption) {
						if (substr($aoption["value"],0,4)=="msg:") {
							$aoption["value"] = $msg[substr($aoption["value"],4)];
						}
						$aresult["values"][] = array(
							"value_id" => $aoption["VALUE"],
							"value_caption" => utf8_normalize($aoption["value"])
						);						
					}
					break;
				case "marc_list":
	   				$options=new marc_list($content["INPUT_OPTIONS"]["NAME"][0]["value"]);
	   				asort($options->table);
	   				reset($options->table);
	
	 		  			// gestion restriction par code utilise.
	 		  			if ($content["INPUT_OPTIONS"]["RESTRICTQUERY"][0]["value"]) {
	 		  				$restrictquery=mysql_query($content["INPUT_OPTIONS"]["RESTRICTQUERY"][0]["value"], $dbh);
				  		if ($restrictqueryrow=@mysql_fetch_row($restrictquery)) {
				  			if ($restrictqueryrow[0]) {
				  				$restrictqueryarray=explode(",",$restrictqueryrow[0]);
				  				$existrestrict=true;
				  			} else $existrestrict=false;
				  		} else $existrestrict=false;
	 		  			} else $existrestrict=false;
	
	   				while (list($key,$val)=each($options->table)) {
	   					if ($existrestrict && array_search($key,$restrictqueryarray)!==false) {
							$aresult["values"][] = array(
								"value_id" => $key,
								"value_caption" => utf8_normalize($val)
							);
	   					} elseif (!$existrestrict) {
							$aresult["values"][] = array(
								"value_id" => $key,
								"value_caption" => utf8_normalize($val)
							);
	   					}    						
	   				}
	   				$r.="</select>";
					break;
				case "text":
				case "authoritie":
				default:
					$aresult["values"] = array();
					break;
			}
		}
		
		if (!$nocache) {
			//Mettons le resultat dans le cache
			$es_cache = new external_services_cache('es_cache_blob', 86400);
			$es_cache->encache_single_object($cache_ref, CACHE_TYPE_MISC, base64_encode(serialize($aresult)));
		}
		
		return $aresult;

	}
	
	function advancedSearch($search_realm, $search_description, $PMBUserId=-1, $OPACEmprId=-1) {
		global $search;

		object_to_array($search_description);
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			foreach ($search_description as $index => $afield_s) {
				$search_description[$index]["value"] = utf8_encode($search_description[$index]["value"]);
			}
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			foreach ($search_description as $index => $afield_s) {
				$search_description[$index]["value"] = utf8_decode($search_description[$index]["value"]);
			}	
		}
		
		$count=0;
		foreach ($search_description as $afield_s) {
			$search[$count]="f_".$afield_s["field"];
			$field="field_".$count."_".$search[$count];
			global $$field;
			$$field=array($afield_s["value"]);
			$op="op_".$count."_".$search[$count];
			global $$op;
			$$op=$afield_s["operator"];
			if ($count) {
				$inter="inter_".$count."_".$search[$count];
				global $$inter;
				$$inter = $afield_s["inter"];
			}
			global $explicit_search;
			$explicit_search=1;
			$count++;
		}
		
		return $this->make_search($search_realm, $PMBUserId, $OPACEmprId);
		
	}
	
	function get_sort_types() {
		$result = array();
		
		global $include_path, $msg;
		$nomfichier = $include_path . "/sort/" . "notices". "/sort.xml";

		if (file_exists($nomfichier)) {
			$fp = fopen($nomfichier, "r");
		}

		if ($fp) {
			//un fichier est ouvert donc on le lit
			$xml = fread($fp, filesize($nomfichier));
			//on le ferme
			fclose($fp);
			//on le parse pour le transformer en tableau
			$params = _parser_text_no_function_($xml, "SORT");
			
			foreach ($params["FIELD"] as $aparam) {
				$result[] = array(
					"sort_name" => $aparam["TYPE"]."_".$aparam["ID"],
					"sort_caption" =>  utf8_normalize($msg[$aparam["NAME"]])
				);
			}
		} 
		return $result;
	}
	
	function fetchSearchRecords($searchId, $firstRecord, $recordCount, $recordFormat, $recordCharset='iso-8859-1', $includeLinks=true, $includeItems=false) {
		//On tri par défaut selon la pertinence des résultats
		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsSorted($searchId, $firstRecord, $recordCount, $recordFormat, $recordCharset, $includeLinks, $includeItems, "d_num_6");
	}

	function fetchSearchRecordsSorted($searchId, $firstRecord, $recordCount, $recordFormat, $recordCharset='iso-8859-1', $includeLinks=true, $includeItems=false, $sort_type="") {
		global $dbh;
		$firstRecord+=0;
		$recordCount+=0;

		//Cherchons la session
		$sql = "SELECT * FROM es_searchsessions WHERE es_searchsession_id = '".addslashes($searchId)."'";
		$res = mysql_query($sql, $dbh);
		if (!mysql_numrows($res)) {
			return array();
		}
		$row = mysql_fetch_assoc($res);
		$this->update_session_date($searchId);

		$search_unique_id = $row["es_searchsession_searchnum"];
		$search_realm = $row["es_searchsession_searchrealm"];
		$pmbuserid = $row["es_searchsession_pmbuserid"];
		$opacemprid = $row["es_searchsession_opacemprid"];
		
		if (!$search_unique_id) {
			return array();
		}

		$search_cache = new external_services_searchcache($search_realm, $search_unique_id, $pmbuserid, $opacemprid);
		$notice_ids = $search_cache->get_results($firstRecord, $recordCount, $sort_type);

		if ($search_cache->external_search) {
			$records = $this->external_noticeids_to_recordformats($notice_ids, $recordFormat, $recordCharset);
		}
		else {
			$records = $this->noticeids_to_recordformats($notice_ids, $recordFormat, $recordCharset, $includeLinks, $includeItems);
		}
		
		$results = array();
		foreach ($records as $notice_id => $record_value) {
			$results[] = array(
				"noticeId" => $notice_id,
				"noticeContent" => $record_value
			);
		}

		return $results;
	}
	
	function fetchSearchRecordsArray($searchId, $firstRecord, $recordCount, $recordCharset='iso-8859-1', $includeLinks=true, $includeItems=false) {
		//On tri par défaut selon la pertinence des résultats
		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsArraySorted($searchId, $firstRecord, $recordCount, $recordCharset, $includeLinks, $includeItems, "d_num_6");
	}
	
	function fetchSearchRecordsArraySorted($searchId, $firstRecord, $recordCount, $recordCharset='iso-8859-1', $includeLinks=true, $includeItems=false, $sort_type="") {
		global $dbh;
		$firstRecord+=0;
		$recordCount+=0;

		//Cherchons la session
		$sql = "SELECT * FROM es_searchsessions WHERE es_searchsession_id = '".addslashes($searchId)."'";
		$res = mysql_query($sql, $dbh);
		if (!mysql_numrows($res)) {
			return array();
		}
		$row = mysql_fetch_assoc($res);
		$this->update_session_date($searchId);

		$search_unique_id = $row["es_searchsession_searchnum"];
		$search_realm = $row["es_searchsession_searchrealm"];
		$pmbuserid = $row["es_searchsession_pmbuserid"];
		$opacemprid = $row["es_searchsession_opacemprid"];
		
		if (!$search_unique_id) {
			return array();
		}

		$search_cache = new external_services_searchcache($search_realm, $search_unique_id, $pmbuserid, $opacemprid);
		$notice_ids = $search_cache->get_results($firstRecord, $recordCount, $sort_type);
		
		$records = $this->noticeids_to_recordformats($notice_ids, "raw_array", $recordCharset, $includeLinks, $includeItems);
		$array_results = array_values($records);
		
		return $array_results;
	}
	
	function listExternalSources($OPACUserId=-1) {
		global $dbh, $msg;
		$sql = 'SELECT connectors_sources.source_id, connectors_sources.name, connectors_sources.comment, connectors_categ.connectors_categ_name FROM connectors_sources LEFT JOIN connectors_categ_sources ON (connectors_categ_sources.num_source = connectors_sources.source_id) LEFT JOIN connectors_categ ON (connectors_categ.connectors_categ_id = connectors_categ_sources.num_categ) WHERE 1 '.($OPACUserId != -1 ? 'AND connectors_sources.opac_allowed = 1' : '').' ORDER BY connectors_categ.connectors_categ_name, connectors_sources.name';
		$res = mysql_query($sql, $dbh);
		$results = array();
		$categs = array();
		while($row = mysql_fetch_assoc($res)) {
			$categs[$row['connectors_categ_name'] ? $row['connectors_categ_name'] : $msg['source_no_category']][] = array(
				'source_id' => $row['source_id'],
				'source_caption' => utf8_normalize($row['name']),
				'source_comment' => utf8_normalize($row['comment']),
			);
		}
		foreach($categs as $categ_name => $categ_content) {
			$results[] = array(
				'category_caption' => utf8_normalize($categ_name),
				'sources' => $categ_content,
			);
		}
		return $results;
	}
	
	function fetchSearchRecordsFull($searchId, $firstRecord, $recordCount, $recordCharset='iso-8859-1', $includeLinks=true, $includeItems=false) {
		//On tri par défaut selon la pertinence des résultats
		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsFullSorted($searchId, $firstRecord, $recordCount, $recordCharset, $includeLinks, $includeItems, "d_num_6");
	}
	
	function fetchSearchRecordsFullSorted($searchId, $firstRecord, $recordCount, $recordCharset='iso-8859-1', $includeLinks=true, $includeItems=false,$sort_type='') {
		global $dbh;
		$firstRecord+=0;
		$recordCount+=0;

		//Cherchons la session
		$sql = "SELECT * FROM es_searchsessions WHERE es_searchsession_id = '".addslashes($searchId)."'";
		$res = mysql_query($sql, $dbh);
		if (!mysql_numrows($res)) {
			return array();
		}
		$row = mysql_fetch_assoc($res);
		$this->update_session_date($searchId);

		$search_unique_id = $row["es_searchsession_searchnum"];
		$search_realm = $row["es_searchsession_searchrealm"];
		$pmbuserid = $row["es_searchsession_pmbuserid"];
		$opacemprid = $row["es_searchsession_opacemprid"];
		
		if (!$search_unique_id) {
			return array();
		}

		$search_cache = new external_services_searchcache($search_realm, $search_unique_id, $pmbuserid, $opacemprid);
		$notice_ids = $search_cache->get_results($firstRecord, $recordCount, $sort_type);
		
		
		$records = $this->proxy_parent->pmbesNotices_fetchNoticeListFull($notice_ids,"raw_array_assoc", $recordCharset, $includeLinks, $includeItems);	
		return $records;
		$array_results = array_values($records);
		
		return $array_results;
	}
	
	function fetchSearchRecordsFullWithBullId($searchId, $firstRecord, $recordCount, $recordCharset='iso-8859-1', $includeLinks=true, $includeItems=false) {
		//On tri par défaut selon la pertinence des résultats
		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsFullWithBullIdSorted($searchId, $firstRecord, $recordCount, $recordCharset, $includeLinks, $includeItems, "d_num_6");
	}
	
	function fetchSearchRecordsFullWithBullIdSorted($searchId, $firstRecord, $recordCount, $recordCharset='iso-8859-1', $includeLinks=true, $includeItems=false,$sort_type='') {
		global $dbh;
		$firstRecord+=0;
		$recordCount+=0;

		//Cherchons la session
		$sql = "SELECT * FROM es_searchsessions WHERE es_searchsession_id = '".addslashes($searchId)."'";
		$res = mysql_query($sql, $dbh);
		if (!mysql_numrows($res)) {
			return array();
		}
		$row = mysql_fetch_assoc($res);
		$this->update_session_date($searchId);

		$search_unique_id = $row["es_searchsession_searchnum"];
		$search_realm = $row["es_searchsession_searchrealm"];
		$pmbuserid = $row["es_searchsession_pmbuserid"];
		$opacemprid = $row["es_searchsession_opacemprid"];
		
		if (!$search_unique_id) {
			return array();
		}

		$search_cache = new external_services_searchcache($search_realm, $search_unique_id, $pmbuserid, $opacemprid);
		$notice_ids = $search_cache->get_results($firstRecord, $recordCount, $sort_type);
		
		
		$records = $this->proxy_parent->pmbesNotices_fetchNoticeListFullWithBullId($notice_ids,"raw_array_assoc", $recordCharset, $includeLinks, $includeItems);	
		return $records;
		$array_results = array_values($records);
		
		return $array_results;
	}	
}



?>