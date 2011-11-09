<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmbesRepositories.class.php,v 1.2 2011-02-17 14:31:30 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/external_services.class.php");

class pmbesRepositories extends external_services_api_class {
	var $error=false;		//Y-a-t-il eu une erreur
	var $error_message="";	//Message correspondant à l'erreur
	var $es;				//Classe mère qui implémente celle-ci !
	var $msg;
	
	function restore_general_config() {
		
	}
	
	function form_general_config() {
		return false;
	}
	
	function save_general_config() {
		
	}
	
	function list_agnostic_repositories($source_id, $notice) {
		$result = array();
		
		$sql = 'SELECT source_id, comment, name FROM connectors_sources WHERE id_connector = \'agnostic\'';
		$res = mysql_query($sql);
		
		while($row = mysql_fetch_assoc($res)) {
			$result[] = array(
				'id' => $row["source_id"],
				'name' => $row["name"],
				'comment' => $row["comment"],
			);
		}
		
		return $result;
	}
	
	function rec_record($source_id, $record) {
		global $charset,$base_path;
		
		$record = charset_pmb_normalize($record);
		$n_header = array();
		foreach($record["header"] as $aheader_field) {
			switch($aheader_field["name"]) {
				case "rs":
					$n_header["rs"] = $aheader_field["value"]; 
					break;
				case "ru": 
					$n_header["ru"] = $aheader_field["value"];
					break;
				case "el": 
					$n_header["el"] = $aheader_field["value"];
					break;
				case "bl": 
					$n_header["bl"] = $aheader_field["value"];
					break;
				case "hl": 
					$n_header["hl"] = $aheader_field["value"];
					break;
				case "dt": 
					$n_header["dt"] = $aheader_field["value"];
					break;
				default:
					break;
			}
		}
		
		$ref = md5(print_r($record, true));

		//Suppression d'un éventuel doublon
		$requete="delete from entrepot_source_".$source_id." where ref='".addslashes($ref)."'";
		mysql_query($requete);		
		
		//Récupération d'un ID
		$requete="insert into external_count (recid, source_id) values('".addslashes("agnostic ".$source_id." ".$ref)."', ".$source_id.")";
		$rid=mysql_query($requete);
		if ($rid) $recid=mysql_insert_id();
		
		if (!$recid)
			return false;

		$date_import=date( 'Y-m-d H:i:s',time());
			
		$ufield="";
		$usubfield="";
		$field_order=0;
		$subfield_order=0;
		$value="";

		foreach($n_header as $hc=>$code) {
			$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid) values(
			'".addslashes('agnostic')."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
			'".$hc."','',0,0,'".addslashes($code)."','',$recid)";
			mysql_query($requete);
		}		
		
		for ($i=0; $i<count($record["f"]); $i++) {
			$ufield=$record['f'][$i]["c"];
			$field_ind = $record['f'][$i]['ind'];
			$field_order=$i;
			$ss=$record['f'][$i]['s'];
			if (is_array($ss)) {
				for ($j=0; $j<count($ss); $j++) {
					$usubfield=$ss[$j]["c"];
					$value=$ss[$j]["value"];
					$subfield_order=$j;
					$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,field_ind,usubfield,field_order,subfield_order,value,i_value,recid) values(
					'".addslashes('agnostic')."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
					'".addslashes($ufield)."','".addslashes($field_ind)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
					' ".addslashes(strip_empty_words($value))." ',$recid)";
					mysql_query($requete);
				}
			}
		}
	}
	
	function add_unimarc_notice_to_repository($source_id, $notice) {
		$source_id += 0;
		$sql = 'SELECT 1 FROM connectors_sources WHERE source_id = '.$source_id.' AND id_connector = \'agnostic\'';
		if(!mysql_num_rows(mysql_query($sql)))
			throw new Exception('Source not found.');
		$this->rec_record($source_id, $notice);
		return array("notice" => $notice);
	}
}




?>