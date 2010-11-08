<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: start_export.class.php,v 1.13 2009-12-09 14:30:30 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once ("$include_path/parser.inc.php");
require_once ("$base_path/admin/convert/export.class.php");
require_once("$class_path/export_param.class.php");

//Récupération du chemin du fichier de paramétrage de l'import
function _item_($param) {
	global $export_type;
	global $i;
	global $param_path;
	global $export_type_l;

	if ($i == $export_type) {
		$param_path = $param['PATH'];
		$export_type_l = $param['NAME'];
	}
	$i ++;
}

function _item_export_list_($param) {
	global $export_list;
	global $i, $iall;
	
	if ($param["EXPORT"]=="yes") {
		$t=array();
		$t["NAME"]=$param["EXPORTNAME"];
		$t["PATH"]=$param["PATH"];
		$t["ID"]=$i;
		$t["IDALL"]=$iall;
		$export_list[]=$t;
	}
	$i++;
	$iall++;
}

//Récupération du paramètre d'import
function _output_($param) {
	global $output;
	global $output_type;
	global $output_params;

	$output = $param['IMPORTABLE'];
	$output_type = $param['TYPE'];
	$output_params = $param;
}

function _input_($param) {
	global $specialexport;
	global $input_type;
	global $input_params;

	$input_type = $param['TYPE'];
	$input_params = $param;
	
	if ($param["SPECIALEXPORT"]=="yes") {
		$specialexport=true; 
	} else $specialexport=false;
}

//Récupération des étapes de conversion
function _step_($param) {
	global $step;

	$step[] = $param;
}

//Récupération du nom de l'import
function _import_name_($param) {
	global $import_name;

	$import_name = $param['value'];
}

class start_export {

	var $export_type;
	var $id_notice;
	var $prepared_notice;
	var $output_notice;
    var $message_convert;
    var $error;
    
    function start_export($id_notice,$type_export,$is_externe=false) {
    		global $i;
    		global $param_path;
    		global $specialexport;
    		global $output_type;
    		global $output_params;
    		global $step;
    		global $export_type;
    		global $base_path;
			global $class_path;
			global $include_path;
			
			$step=array();    		

    		if ($id_notice) {
    			$this->id_notice=$id_notice;
    			$this->export_type=$type_export;
    			$export_type=$type_export;
    			
    			//Récupération du répertoire
				$i = 0;
				$param_path = "";
				if (file_exists("$base_path/admin/convert/imports/catalog_subst.xml"))
					$fic_catal = "$base_path/admin/convert/imports/catalog_subst.xml";
				else
					$fic_catal = "$base_path/admin/convert/imports/catalog.xml";		
				_parser_($fic_catal, array("ITEM" => "_item_"), "CATALOG");

				//Lecture des paramètres
				
				_parser_("$base_path/admin/convert/imports/".$param_path."/params.xml", array("IMPORTNAME" => "_import_name_","STEP" => "_step_","OUTPUT" => "_output_","INPUT" => "_input_"), "PARAMS");

				//Si l'export est spécial, on charge la fonction d'export
				if ($specialexport) require_once("$base_path/admin/convert/imports/".$param_path."/export.inc.php");			
    			
    			//En fonction du type de fichier de sortie, inclusion du script de gestion des sorties
				switch ($output_type) {
					case "xml" :
						require_once ("$base_path/admin/convert/imports/output_xml.inc.php");
					break;
					case "iso_2709" :
						require_once ("$base_path/admin/convert/imports/output_iso_2709.inc.php");
					break;
					case "custom" :
						require_once ("$base_path/admin/convert/imports/".$param_path."/".$output_params['SCRIPT']);
					break;
					case "txt":
						require_once ("$base_path/admin/convert/imports/output_txt.inc.php");
					break;
					default :
						die($msg["export_cant_find_output_type"]);
				}
				
				$e_notice=array();
				if($_SESSION["param_export"]["notice_exporte"]) $notice_exporte = $_SESSION["param_export"]["notice_exporte"]; 
				else $notice_exporte=array();
				/*if($_SESSION["param_export"]["bulletin_exporte"]) $bulletin_exporte = $_SESSION["param_export"]["bulletin_exporte"]; 
				else $bulletin_exporte=array();*/
				// Inutile car pas d'exemplaires exportés
				if (!$specialexport) {
					$param = new export_param(EXP_DEFAULT_OPAC);
					$e = new export(array($this->id_notice),$notice_exporte);
					do{
					   if($is_externe){
					   	   $e_notice = $this->entrepot_to_xml($this->id_notice);
					   } else{
					   	  $nn = $e -> get_next_notice("","","",0,$param->get_parametres($param->context));
					   	  if ($e->notice) $e_notice[]=$e->notice;
					   }
					} while($nn);
					$notice_exporte=$e->notice_exporte;
					$_SESSION["param_export"]["notice_exporte"]=$notice_exporte;
				} else {
					$e_notice = _export_($this->id_notice);
				}
				
				if(!is_array($e_notice)){
					$this->prepared_notice=$e_notice;
					$this->output_notice.=$this->transform();
				} else {
					for($i=0;$i<sizeof($e_notice);$i++){
						$this->prepared_notice=$e_notice[$i];
						$this->output_notice.=$this->transform();
					}
				}
    		}
    }
    
    function get_mime_type() {
    	global $output_params;
    	
    	return $output_params["MIMETYPE"];
    }
    
    function get_suffix() {
    	global $output_params;
    	
    	return $output_params["SUFFIX"];
    }
    
    function get_exports() {
    	global $export_list;
    	global $i, $iall;
    	global $base_path;
    	$i=0;
    	$iall=0;
    	if (file_exists("$base_path/admin/convert/imports/catalog_subst.xml")) 
			$catalog="$base_path/admin/convert/imports/catalog_subst.xml";
		else
			$catalog="$base_path/admin/convert/imports/catalog.xml";
    	_parser_($catalog, array("ITEM" => "_item_export_list_"), "CATALOG");
    	return $export_list;
    }
    
    function get_header() {
    	global $base_path;
    	global $output_params;
    	return _get_header_($output_params);
    }
    
    function get_footer() {
    	global $base_path;
    	global $output_params;
    	return _get_footer_($output_params);
    }
    
    function transform() {
   		global $msg;
   		global $step;
		global $param_path;
		global $n_errors;
		global $message_convert;
    	global $input_type;
    	global $base_path;
    	global $include_path;
    	global $class_path;
    	global $base_path;
    	
    	$notice=$this->prepared_notice;
    	
    	//Inclusion des librairies éventuelles
		for ($i = 0; $i < count($step); $i ++) {
			if ($step[$i]['TYPE'] == "custom") {
				//echo "$base_path/admin/convert/imports/".$param_path."/".$step[$i][SCRIPT][0][value];
				require_once ("$base_path/admin/convert/imports/".$param_path."/".$step[$i]['SCRIPT'][0]['value']);
			}
		}

		require_once ("xmltransform.php");

		//En fonction du type de fichier d'entrée, inclusion du script de gestion des entrées
		switch ($input_type) {
			case "xml" :
				require_once ("$base_path/admin/convert/imports/input_xml.inc.php");
			break;
			case "iso_2709" :
				require_once ("$base_path/admin/convert/imports/input_iso_2709.inc.php");
			break;
			case "text" :
				require_once("$base_path/admin/convert/imports/input_text.inc.php");
				break;
			case "custom" :
				require_once ("$base_path/admin/convert/imports/$param_path/".$input_params['SCRIPT']);
				break;
			default :
			die($msg["ie_import_entry_not_valid"]);
		}

		for ($i = 0; $i < count($step); $i ++) {
			$s = $step[$i];
			$islast=($i==count($step)-1);
			$isfirst=($i==0);
			switch ($s[TYPE]) {
					case "xmltransform" :
						$r = perform_xslt($notice, $s, $islast, $isfirst, $param_path);
						break;
					case "toiso" :
						$r = toiso($notice, $s, $islast, $isfirst, $param_path);
						break;
					case "isotoxml" :
						$r = isotoxml($notice, $s, $islast, $isfirst, $param_path);
						break;
					case "texttoxml":
						$r = texttoxml($notice, $s, $islast, $isfirst, $param_path);
						break;
					case "custom" :
						eval("\$r=".$s['CALLBACK'][0][value]."(\$notice, \$s, \$islast, \$isfirst, \$param_path);");
						break;
			}
			if (!$r['VALID']) {
					$this->n_errors=true;
					$this->message_convert= $r['ERROR'];
					$notice = ""; //$r['ERROR'];
					break;
			} else {
					$notice = $r['DATA'];
			}
		}
		return $notice;
    }

	// Récupération de l'id à partir du nom de l'export
	function get_id_by_path($path) {
	   	global $export_list;
		if (!count($export_list)) start_export::get_exports() ;
		for ($i=0;$i<count($export_list);$i++) {
			if ($export_list[$i]["PATH"]==$path) return $export_list[$i]["IDALL"] ;
		}
	}
	
	/*
	 * Récupération de la notice unimarc par l'entrepot
	 */
	function entrepot_to_xml($recid) {
		global $dbh,$base_path;
		
		$requete = "SELECT source_id FROM external_count WHERE rid=".addslashes($recid).";";
		$myQuery = mysql_query($requete, $dbh);
		$source_id = mysql_result($myQuery, 0, 0);
		
		$requete="select * from entrepot_source_$source_id where recid='".addslashes($recid)."' group by ufield,usubfield,field_order,subfield_order,value order by ufield,field_order,usubfield,subfield_order";
		$resultat = mysql_query($requete, $dbh);
		$entete="";
		
		$field_order = "";
		$champs="";	
		$entete = "<notice>\n";
		while ($r=mysql_fetch_object($resultat)) {
			switch ($r->ufield) {
				case "rs":
					$entete .= "<rs>".$r->value."</rs>\n";
					break;
				case "dt":
					$entete .= "<dt>".$r->value."</dt>\n";
					break;
				case "bl":
					$entete .= "<bl>".$r->value."</bl>\n";
					break;
				case "hl":
					$entete .= "<hl>".$r->value."</hl>\n";
					break;
				case "el":
					$entete .= "<el>".$r->value."</el>\n";
					break;
				case "ru":
					$entete .= "<ru>".$r->value."</ru>\n";
					break;
				case "001":
					$champs = "<f c='".$r->ufield."' ind=''>".$r->value;
					break;
				default:						
					if($r->field_order == $field_order){
						if($r->usubfield)
							$champs .= "<s c='".$r->usubfield."'>".$r->value."</s>\n"; 
						else $champs .= $r->value;
					} elseif($r->field_order != $field_order){
						$champs .= "</f>\n";
						$champs .= "<f c='".$r->ufield."' ind='  '>\n";
						if($r->usubfield)
							$champs .= "<s c='".$r->usubfield."'>".$r->value."</s>\n"; 
						else $champs .= $r->value;
						$field_order = $r->field_order;
					}					
				break;
			}
		}
		$champs .= "</f>\n</notice>";
		$fi = fopen($base_path."/temp/test_exp.txt","w+");
		fwrite($fi,$entete.$champs);
		fclose($fi);
		return $entete.$champs;
	}
}

?>