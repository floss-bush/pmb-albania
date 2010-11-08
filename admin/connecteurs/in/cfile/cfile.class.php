<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cfile.class.php,v 1.7 2009-11-06 16:38:23 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");
require_once("$include_path/isbn.inc.php");
require_once("$class_path/caddie.class.php");
require_once ("$include_path/parser.inc.php");
require_once($base_path."/admin/convert/xml_unimarc.class.php");

if (version_compare(PHP_VERSION,'5','>=') && extension_loaded('xsl')) {
	if (substr(phpversion(), 0, 1) == "5") @ini_set("zend.ze1_compatibility_mode", "0");
	require_once($include_path.'/xslt-php4-to-php5.inc.php');
}


function cfile_file_item_($param) {
	global $catalogs;
	
	if (($param['VISIBLE'] != 'no') && ($param['IMPORT'] == 'yes') || ($param['OUTPUT_PMBXML'] == 'yes')) {
		$catalogs[]= array(
			"name" => $param['NAME'],
			"path" => $param['PATH']
		);
	}
}

class cfile extends connector {
	//Variables internes pour la progression de la récupération des notices
	var $callback_progress;		//Nom de la fonction de callback progression passée par l'appellant
	var $current_set;			//Set en cours de synchronisation
	var $total_sets;			//Nombre total de sets sélectionnés
	var $metadata_prefix;		//Préfixe du format de données courant
	var $source_id;				//Numéro de la source en cours de synchro
	var $search_id;
	var $xslt_transform;		//Feuille xslt transmise
	var $sets_names;			//Nom des sets pour faire plus joli !!
	var $del_old;				//Supression ou non des notices dejà existantes
	var $url;
	var $username;
	var $password;
	
	//Résultat de la synchro
	var $error;					//Y-a-t-il eu une erreur	
	var $error_message;			//Si oui, message correspondant
	
    function cfile($connector_path="") {
    	parent::connector($connector_path);
    }
    
    function get_id() {
    	return "cfile";
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
    	global $charset, $basepath;
    	
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		if (!isset($convert_type))
			$convert_type = "";
		
		//Lecture des différents imports possibles
		if (file_exists($basepath."admin/convert/imports/catalog_subst.xml"))
			$fic_catal = $basepath."admin/convert/imports/catalog_subst.xml";
		else
			$fic_catal = $basepath."admin/convert/imports/catalog.xml";
		
		global $catalogs;
		$catalogs=array();
		_parser_($fic_catal,array("ITEM"=>"cfile_file_item_"),"CATALOG");

		$convert_select = '<select name="convert_type">';

		$selected = $convert_type == 'none_unimarc' ? "selected" : "";
		$convert_select .= '<option '.$selected.' value="none_unimarc">'.$this->msg["cfile_noconversion_unimarc"].'</option>';
		$selected = $convert_type == 'none_xml' ? "selected" : "";
		$convert_select .= '<option '.$selected.' value="none_xml">'.$this->msg["cfile_noconversion_pmbxml"].'</option>';
		foreach($catalogs as $catalog) {
			$selected = $convert_type == $catalog["path"] ? "selected" : "";
			$convert_select .= '<option '.$selected.' value="'.$catalog["path"].'">'.htmlentities($catalog["name"]).'</option>';			
		}
		$convert_select .= '</select>';

		$form = "";
		$form .= "<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["cfile_conversion"]."</label>
			</div>
			<div class='colonne_suite'>
				".$convert_select."
			</div>
		</div>
		<div class='row'>
		";

		$xsl_exemplaire_input = "";
		if (isset($xslt_exemplaire)) {
			$xsl_exemplaire_input .= '<select name="action_xsl_expl"><option value="keep">'.sprintf($this->msg["cfile_keep_xsl_exemplaire"], $xslt_exemplaire["name"]).'</option><option value="delete">'.$this->msg["cfile_delete_xsl_exemplaire"].'</option></select>';
		}
		
		$xsl_exemplaire_input .= '&nbsp;<input onchange="document.source_form.action_xsl_expl.selectedIndex=1" type="file" name="xsl_exemplaire">';

		$form.="
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_profils'>".$this->msg["cfile_xsl_exemplaire"]."</label>
			</div>
			<div class='colonne_suite'>
				".$xsl_exemplaire_input."
			</div>
		</div>
		<div class='row'>
		";

		return $form;
    }
    
    function make_serialized_source_properties($source_id) {
    	global $convert_type, $action_xsl_expl;
		$t = array();
		$t["convert_type"] = $convert_type;
		
  		if($action_xsl_expl == "keep") {
	    	$oldparams=$this->get_source_params($source_id);
			if ($oldparams["PARAMETERS"]) {
				//Affichage du formulaire avec $params["PARAMETERS"]
				$oldvars=unserialize($oldparams["PARAMETERS"]);
			}
	  		$t["xslt_exemplaire"] = $oldvars["xslt_exemplaire"];  			
  		} else {
			if (($_FILES["xsl_exemplaire"])&&(!$_FILES["xsl_exemplaire"]["error"])) {
				$axslt_info["name"] = $_FILES["xsl_exemplaire"]["name"];
				$axslt_info["content"] = file_get_contents($_FILES["xsl_exemplaire"]["tmp_name"]);
		  		$t["xslt_exemplaire"] = $axslt_info;
			}  			
  		}
		
		$this->sources[$source_id]["PARAMETERS"]=serialize($t);
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
	
	function rec_record($record,$source_id,$search_id) {
		global $charset,$base_path;
		$date_import=date("Y-m-d H:i:s",time());
		$r=array();
		//Inversion du tableau
		$r["rs"]=($record["RS"][0]["value"]?$record["RS"][0]["value"]:"*");
		$r["ru"]=($record["RU"][0]["value"]?$record["RU"][0]["value"]:"*");
		$r["el"]=($record["EL"][0]["value"]?$record["EL"][0]["value"]:"*");
		$r["bl"]=($record["BL"][0]["value"]?$record["BL"][0]["value"]:"*");
		$r["hl"]=($record["HL"][0]["value"]?$record["HL"][0]["value"]:"*");
		$r["dt"]=($record["DT"][0]["value"]?$record["DT"][0]["value"]:"*");
		
		$exemplaires = array();
		
		for ($i=0; $i<count($record["F"]); $i++) {
			if ($record["F"][$i]["C"] == 996) {
				//C'est une localisation, les localisations ne sont pas fusionnées.
				$t=array();
				for ($j=0; $j<count($record["F"][$i]["S"]); $j++) {
					//Sous champ
					$sub=$record["F"][$i]["S"][$j];
					$t[$sub["C"]]=$sub["value"];
				}
				$exemplaires[]=$t;					
			}
			else if ($record["F"][$i]["value"]) 
				$r[$record["F"][$i]["C"]][]=$record["F"][$i]["value"];
			else {
				$t=array();
				for ($j=0; $j<count($record["F"][$i]["S"]); $j++) {
					//Sous champ
					$sub=$record["F"][$i]["S"][$j];
					$t[$sub["C"]][]=$sub["value"];
				}
				$r[$record["F"][$i]["C"]][]=$t;
			}
		}
		$record=$r;
	
		//Recherche du 001
		$ref=$record["001"][0];
		//Mise à jour 
		if (!$ref) $ref = md5(print_r($record, true));
		if ($ref) {
			//Si conservation des anciennes notices, on regarde si elle existe
			if (!$this->del_old) {
				$requete="select count(*) from entrepot_source_$source_id where ref='".addslashes($ref)."'";
				$rref=mysql_query($requete);
				if ($rref) $ref_exists=mysql_result($rref,0,0);
			}
			//Si pas de conservation des anciennes notices, on supprime
			if ($this->del_old) {
				$requete="delete from entrepot_source_$source_id where ref='".addslashes($ref)."'";
				mysql_query($requete);
			}
			//Si pas de conservation ou reférence inexistante
			if (($this->del_old)||((!$this->del_old)&&(!$ref_exists))) {
				//Insertion de l'entête
				$n_header["rs"]=$record["rs"];
				$n_header["ru"]=$record["ru"];
				$n_header["el"]=$record["el"];
				$n_header["bl"]=$record["bl"];
				$n_header["hl"]=$record["hl"];
				$n_header["dt"]=$record["dt"];
				
				//Récupération d'un ID
				$requete="insert into external_count (recid, source_id) values('".addslashes($this->get_id()." ".$source_id." ".$ref)."', $source_id)";
				$rid=mysql_query($requete);
				if ($rid) $recid=mysql_insert_id();
				
				foreach($n_header as $hc=>$code) {
					$requete="insert into entrepot_source_$source_id (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values(
					'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".$date_import."',
					'".$hc."','',-1,0,'".addslashes($code)."','',$recid,'".addslashes($search_id)."')";
					mysql_query($requete);
				}
				$field_order=0;
				foreach($exemplaires as $exemplaire) {
					$sub_field_order = 0;
					foreach($exemplaire as $exkey => $exvalue) {
						$requete="insert into entrepot_source_$source_id (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values(
						'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".$date_import."',
						'996','".addslashes($exkey)."',".$field_order.",".$sub_field_order.",'".addslashes($exvalue)."',
						' ".addslashes(strip_empty_words($exvalue))." ',$recid,'".addslashes($search_id)."')";
						mysql_query($requete);
						$sub_field_order++;						
					}					
					$field_order++;					
				}
				foreach ($record as $field=>$val) {
					for ($i=0; $i<count($val); $i++) {
						if (is_array($val[$i])) {
							foreach ($val[$i] as $sfield=>$vals) {
								for ($j=0; $j<count($vals); $j++) {
									$requete="insert into entrepot_source_$source_id (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values(
									'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".$date_import."',
									'".addslashes($field)."','".addslashes($sfield)."',".$field_order.",".$j.",'".addslashes($vals[$j])."',
									' ".addslashes(strip_empty_words($vals[$j]))." ',$recid,'".addslashes($search_id)."')";
									mysql_query($requete);
								}
							}
						} else {
							$requete="insert into entrepot_source_$source_id (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values(
							'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".$date_import."',
							'".addslashes($field)."','',".$field_order.",0,'".addslashes($val[$i])."',
							' ".addslashes(strip_empty_words($val[$i]))." ',$recid,'".addslashes($search_id)."')";
							mysql_query($requete);
						}
						$field_order++;
					}
				}
			}
		}
	}
		
	function cancel_maj($source_id) {
		return false;
	}
	
	function break_maj($source_id) {
		return false;
	}
	
	function form_pour_maj_entrepot($source_id) {
		global $base_path, $id;
		//Allons chercher plein d'informations utiles et amusantes
		$params=$this->get_source_params($source_id);
		$this->fetch_global_properties();
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		if (!isset($convert_type))
			$convert_type = "none_unimarc";

		$form = "";
		switch ($convert_type) {
			case "none_unimarc":
				//On importe de l'unimarc direct
				$form .= '<label for="import_file">'.$this->msg["cfile_please_enter_file"].'</label><br />';
				$form .= '<input type="file" name="import_file" class=\'saisie-80em\'>';
				$form .= '<input type="hidden" name="outputtype" value="iso_2709">';
//				$form .= "<script>document.sync_form.action='".$base_path."/admin.php?categ=connecteurs&sub=in&act=sync_custom_page&id=".$id."&source_id=".$source_id."'</script>";			
				break;
			case "none_xml":
				//On importe du pmb-XML unimarc direct
				$form .= '<label for="import_file">'.$this->msg["cfile_please_enter_file"].'</label><br />';
				$form .= '<input type="file" name="import_file" class=\'saisie-80em\'>';
				$form .= '<input type="hidden" name="outputtype" value="xml">';
//				$form .= "<script>document.sync_form.action='".$base_path."/admin.php?categ=connecteurs&sub=in&act=sync_custom_page&id=".$id."&source_id=".$source_id."'</script>";			
				break;
			default:
				//Une conversion est nécéssaire
				$form .= '<label for="import_file">'.$this->msg["cfile_please_enter_file"].'</label><br />';
				$form .= '<input type="file" name="import_file" class=\'saisie-80em\'>';
				$form .= '<input type="hidden" name="import_type" value="'.$convert_type.'">';
				$form .= "<script>document.sync_form.action='".$base_path."/admin.php?categ=connecteurs&sub=in&act=sync_custom_page&id=".$id."&source_id=".$source_id."'</script>";
				break;			 
		}
				
		$form .= "<br /><br />";
		return $form;
	}
	
	//Nécessaire pour passer les valeurs obtenues dans form_pour_maj_entrepot au javascript asynchrone
	function get_maj_environnement($source_id) {
		global $outputtype, $import_type;
		global $base_path;
		$envt=array();
		//Copie du fichier dans le répertoire temporaire
		$origine=str_replace(" ","",microtime());
		$origine=str_replace("0.","",$origine);
		if ($_FILES['import_file']['name']) {
			if (!@copy($_FILES['import_file']['tmp_name'], "$base_path/temp/".$origine.$_FILES['import_file']['name'])) {
					error_message_history($msg["ie_tranfert_error"], $msg["ie_transfert_error_detail"], 1);
					exit;
			} 
			else
				$file_in = $origine.$_FILES['import_file']['name'];
		}
		$envt["file_in"] = $file_in;
		if (!$import_type)
			$envt["outputtype"] = $outputtype;
		$envt["import_type"] = $import_type;
		$envt["origine"] = $origine;
		return $envt;
	}
	
	function sync_custom_page($source_id) {
		global $base_path, $id, $file_in, $origine;
		//Allons chercher plein d'informations utiles et amusantes
		$params=$this->get_source_params($source_id);
		$this->fetch_global_properties();
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		if (!isset($convert_type))
			$convert_type = "";
		//Convertissons le $convert_type en un nombre, vu que c'est ce que mange le script d'import
		$convert_type;
		
		$env = $this->get_maj_environnement($source_id);
		$file_in = $env["file_in"];
		
		$redirect_url = "../../admin.php?categ=connecteurs&sub=in&act=sync&source_id=".$source_id."&go=1&id=$id&env=".urlencode(serialize($env));
		$content = "";
		$content .= '' .
				'<div><iframe name="ieimport" frameborder="0" scrolling="yes" width="100%" height="500" src="'.$base_path.'/admin/convert/start_import.php?import_type='.$convert_type.'&file_in='.urlencode($file_in).'&redirect='.urlencode($redirect_url).'">
				</div>
				<noframes>
				</noframes>';
		return $content;
	}
	
	function maj_entrepot($source_id,$callback_progress="",$recover=false,$recover_env="") {
		global $dbh, $base_path, $file_in, $suffix, $converted, $origine, $charset, $outputtype;
		//Allons chercher plein d'informations utiles et amusantes
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
		if (!isset($xslt_exemplaire))
			$xslt_exemplaire = "";
		
		$file_type = "iso_2709";
		//Récupérons le nom du fichier
		if ($converted) {
			//Fichier converti
			$f = explode(".", $file_in);
			if (count($f) > 1) {
				unset($f[count($f) - 1]);
			}
			$final_file = implode(".", $f).".".$suffix."~";
			$final_file = "$base_path/temp/".$final_file;
			$file_type = $outputtype;
		}
/*		else if (!$file_in) {
			//Le fichier vient d'être uploadé
			$origine=str_replace(" ","",microtime());
			$origine=str_replace("0.","",$origine);			
			if ($_FILES['import_file']['name']) {
				if (!@copy($_FILES['import_file']['tmp_name'], "$base_path/temp/".$origine.$_FILES['import_file']['name'])) {
						error_message_history($msg["ie_tranfert_error"], $msg["ie_transfert_error_detail"], 1);
						exit;
				} 
				else
					$file_in = "$base_path/temp/".$origine.$_FILES['import_file']['name'];
			}
			$final_file = $file_in;
			$file_type = $outputtype;
		}*/
		else {
			$final_file = "$base_path/temp/".$file_in;
			$file_type = $outputtype;
		}

		/*
		 * ISO-2709
		 * */
		if ($file_type == "iso_2709") {
			//Chargeons ces notices dans la base			
			$this->loadfile_in_table_unimarc($final_file, $origine);
	
			$import_marc_count = "SELECT count(*) FROM import_marc";
			$count_total = mysql_result(mysql_query($import_marc_count, $dbh), 0, 0);
			if (!$count_total) {
				return 0;
			}
			$count_lu = 0;
			$latest_percent = floor(100 * $count_lu / $count_total);
	
			//Et c'est parti
			$import_sql = "SELECT id_import, notice FROM import_marc WHERE origine = ".$origine;
			$res = mysql_query($import_sql);
			while ($row = mysql_fetch_assoc($res)) {
				$xmlunimarc=new xml_unimarc();
				$nxml=$xmlunimarc->iso2709toXML_notice($row["notice"]);
				$xmlunimarc->notices_xml_[0] = '<?xml version="1.0" encoding="'.$charset.'"?>'.$xmlunimarc->notices_xml_[0];
				if ($xslt_exemplaire) {
					$xmlunimarc->notices_xml_[0] = $this->apply_xsl_to_xml($xmlunimarc->notices_xml_[0], $xslt_exemplaire["content"]);
				}
				if ($nxml==1) {
					$params=_parser_text_no_function_($xmlunimarc->notices_xml_[0] ,"NOTICE");
					$this->rec_record($params,$source_id, 0);
					$count_lu++;
				}
				
				$sql_delete = "DELETE FROM import_marc WHERE id_import = ".$row['id_import'];
				@mysql_query($sql_delete);
				
				if (floor(100 * $count_lu / $count_total) > $latest_percent) {
					//Mise à jour de source_sync pour reprise en cas d'erreur
	/*				$envt["current_origine"]=$origine;
					$envt["already_read_count"]=$count_lu;
					$requete="update source_sync set env='".addslashes(serialize($envt))."' where source_id=".$source_id;
					mysql_query($requete);*/
					
					//Inform
					$callback_progress($count_lu / $count_total, $count_lu, $count_total);
					$latest_percent = floor(100 * $count_lu / $count_total);
					flush();
					ob_flush();		
				}
			}			
		}
		/*
		 * XML-PMB UNIMARC
		 * */
		else if ($file_type == "xml") {
			//Chargeons ces notices dans la base
			$this->loadfile_in_table_xml($final_file, $origine);
			
			$import_marc_count = "SELECT count(*) FROM import_marc";
			$count_total = mysql_result(mysql_query($import_marc_count, $dbh), 0, 0);
			if (!$count_total) {
				return 0;
			}
			$count_lu = 0;
			$latest_percent = floor(100 * $count_lu / $count_total);
	
			//Et c'est parti
			$import_sql = "SELECT id_import, notice FROM import_marc WHERE origine = ".$origine;
			$res = mysql_query($import_sql);
			while ($row = mysql_fetch_assoc($res)) {
				$xmlunimarc = '<?xml version="1.0" encoding="'.$charset.'"?>'.$row["notice"];
				
				if ($xslt_exemplaire) {
					$xmlunimarc = $this->apply_xsl_to_xml($xmlunimarc, $xslt_exemplaire["content"]);
				}
				
				$params=_parser_text_no_function_($xmlunimarc,"NOTICE");
				$this->rec_record($params,$source_id, 0);
				$count_lu++;
				
				$sql_delete = "DELETE FROM import_marc WHERE id_import = ".$row['id_import'];
				@mysql_query($sql_delete);
				
				if (floor(100 * $count_lu / $count_total) > $latest_percent) {
					//Mise à jour de source_sync pour reprise en cas d'erreur
	/*				$envt["current_origine"]=$origine;
					$envt["already_read_count"]=$count_lu;
					$requete="update source_sync set env='".addslashes(serialize($envt))."' where source_id=".$source_id;
					mysql_query($requete);*/
					
					//Inform
					$callback_progress($count_lu / $count_total, $count_lu, $count_total);
					$latest_percent = floor(100 * $count_lu / $count_total);
					flush();
					ob_flush();		
				}
			}
		}


		
		return $count_lu;
	}
	
	function loadfile_in_table_unimarc ($filename, $origine) {
		global $msg, $dbh ;
		global $sub, $book_lender_name ;
		global $noticenumber, $pb_fini, $recharge ;

		if ($noticenumber=="") $noticenumber=0;
		if (!file_exists($filename)) {
			printf ($msg[506],$filename); /* The file %s doesn't exist... */
			return;
		}
		
		if (filesize($filename)==0) {
			printf ($msg[507],$filename); /* The file % is empty, it's going to be deleted */
			unlink ($filename);
			return;
		}
		
		$handle = fopen ($filename, "rb");
		if (!$handle) {
			printf ($msg[508],$filename); /* Unable to open the file %s ... */
			return;
		}
		
		$file_size=filesize ($filename);
	
		$contents = fread ($handle, $file_size);
		fclose ($handle);
		
		/* The whole file is in $contents, let's read it */
		$str_lu="";
		$j=0;
		$i=0;
		$pb_fini="";
		$txt="";
		while ( ($i<=strlen($contents)) && ($pb_fini=="") ) {
			$car_lu=substr($contents,$i,1) ;
			$i++;
			if ($i<=strlen($contents)) {
				if ($car_lu != chr(0x1d)) {
					/* the read car isn't the end of the notice */
					$str_lu = $str_lu.$car_lu;
				} else {
					/* the read car is the end of a notice */
					$str_lu = $str_lu.$car_lu;
					$j++;
					$sql = "INSERT INTO import_marc (notice, origine) VALUES(\"".addslashes($str_lu)."\", $origine)";
					$sql_result = mysql_query($sql) or die ("Couldn't insert record!");
					$str_lu="";
				}
			} else { /* the wole file has been read */
				$pb_fini="EOF";
			}
		} /* end while red file */	
		
		if ($pb_fini=="NOTEOF") $recharge="YES"; else $recharge="NO" ;
		if ($pb_fini=="EOF") { /* The file has been read, we can delete it */
			unlink ($filename);
		}
	} // fin fonction de load
	
	function loadfile_in_table_xml ($filename, $origine) {
		$index=array();
		$i=false;
		$n=1;
		$fcontents="";
		$fi = fopen ($filename, "rb");
		while ($i===false) {
			$i=strpos($fcontents,"<notice>");
			if ($i===false) $i=strpos($fcontents,"<notice ");
			if ($i!==false) {
				$i1=strpos($fcontents,"</notice>");
				while ((!feof($fi))&&($i1===false)) {
					$fcontents.=fread($fi,4096);
					$i1=strpos($fcontents,"</notice>");
				}
				if ($i1!==false) {
					$notice=substr($fcontents,$i,$i1+strlen("</notice>")-$i);
					$requete="insert into import_marc (no_notice, notice, origine) values($n,'".addslashes($notice)."','$origine')";
					mysql_query($requete);
					$n++;
					$index[]=$n;
					$fcontents=substr($fcontents,$i1+strlen("</notice>"));
					$i=false;
				}
			} else {
				if (!feof($fi))
					$fcontents.=fread($fi,4096);
				else break;
			}
		}
		fclose ($fi);
		unlink ($filename);
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
	
}
?>