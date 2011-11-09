<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: z3950.class.php,v 1.15.2.1 2011-05-12 11:56:50 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path,$include_path;
require_once($class_path."/connecteurs.class.php");
require_once($class_path."/search.class.php");
require_once($base_path."/admin/convert/convert.class.php");
require_once($base_path."/admin/convert/xml_unimarc.class.php");

if (version_compare(PHP_VERSION,'5','>=') && extension_loaded('xsl')) {
	if (substr(phpversion(), 0, 1) == "5") @ini_set("zend.ze1_compatibility_mode", "0");
	require_once($include_path.'/xslt-php4-to-php5.inc.php');
}

function _item_z3950_($param) {
	global $catalog;
	global $n_typ_total;
	if ($param["IMPORT"]=="yes") {
		$t["NAME"]=$param["NAME"];
		$t["INDEX"]=$n_typ_total;
		$t["PATH"]=$param["PATH"];
		$catalog[]=$t;
	}
	$n_typ_total++;
}

class z3950 extends connector {
	//Variables internes pour la progression de la récupération des notices
	var $del_old;				//Supression ou non des notices dejà existantes
	var $profiles;				//Profils par défaut
	
	//Résultat de la synchro
	var $error;					//Y-a-t-il eu une erreur	
	var $error_message;			//Si oui, message correspondant
	
	var $convert_path_order=array();	//Table de correspondance entre le chemin d'une conversion et son suméro d'ordre
	
    function z3950($connector_path="") {
    	parent::connector($connector_path);
    }
    
    function get_id() {
    	return "z3950";
    }
    
    //Est-ce un entrepot ?
	function is_repository() {
		return 2;
	}
    
    function unserialize_source_params($source_id) {
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			$params["PARAMETERS"]=$vars;
		}
		return $params;
    }
    
    function get_profiles() {
    	$xml_profile=file_get_contents($this->connector_path."/profil.xml");
    	$param=_parser_text_no_function_($xml_profile,"PROFILES");
    	for ($i=0; $i<count($param["PROFILE"]); $i++) {
    		$profile=$param["PROFILE"][$i];
    		$t=array();
    		$t["name"]=$profile["NAME"];
    		if (substr($profile["COMMENT"],0,4)=="msg:") 
    			$t["comment"]=$this->msg[substr($profile["COMMENT"],4)]; 
    		else $t["comment"]=$profile["COMMENT"];
    		for ($j=0; $j<count($profile["UFIELDS"][0]["UFIELD"]); $j++) {
    			$ufield=$profile["UFIELDS"][0]["UFIELD"][$j];
    			$t["ufields"][$ufield["NAME"]]=$ufield["IDS"];
    		}
    		for ($j=0; $j<count($profile["OPERATORS"][0]["OPERATOR"]); $j++) {
    			$operator=$profile["OPERATORS"][0]["OPERATOR"][$j];
    			$t["operators"][$operator["NAME"]]=$operator["TYPES"];
    		}
    		$this->profiles[]=$t;
    	}
    }
    
    function parse_convert_catalog() {
    	global $catalog,$base_path;
    	//Liste des transformations possibles avant import
		$catalog=array();
		$n_typ_total=0;
		if (file_exists("$base_path/admin/convert/imports/catalog_subst.xml"))
			$fic_catal = "$base_path/admin/convert/imports/catalog_subst.xml";
		else
			$fic_catal = "$base_path/admin/convert/imports/catalog.xml";
		
		_parser_($fic_catal, array("ITEM" => "_item_z3950_"), "CATALOG");
    	$this->convert_path_order=$catalog;
    }
    
    function source_get_property_form($source_id) {
    	global $charset,$base_path,$catalog,$reload;
    	
    	if (!$reload) {    	
	    	$params=$this->get_source_params($source_id);
			if ($params["PARAMETERS"]) {
				//Affichage du formulaire avec $params["PARAMETERS"]
				$vars=unserialize($params["PARAMETERS"]);
				foreach ($vars as $key=>$val) {
					global $$key;
					$$key=$val;
				}	
			}
    	} else {
    		global $z3950_profil,$url,$z3950_base,$z3950_login,$z3950_password,$z3950_format,$z3950_port,$z3950_convert,$z3950_max_notices;
    	}
    			
    	if (!($z3950_max_notices*1)) $z3950_max_notices=100;
    	
		//Liste des transformations possibles avant import
		$this->parse_convert_catalog();
		
		//Création de la liste des types d'import
		$export_type="<select name=\"z3950_convert\" id=\"z3950_convert\">\n";
		$export_type.="<option value=\"0\">".$this->msg["z3950_no_convert"]."</option>\n";
		for ($i=0; $i<count($this->convert_path_order); $i++) {
			$export_type.="<option value=\"".$this->convert_path_order[$i]["PATH"]."\"".($z3950_convert==$this->convert_path_order[$i]["PATH"]?" selected":"").">".$this->convert_path_order[$i]["NAME"]."</option>\n";
		}
		$export_type.="</select>";
		
		$form="
		<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["z3950_url"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' class='saisie-60em' name='url' id='url' value='".htmlentities($url,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_base'>".$this->msg["z3950_port"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' class='saisie-10em' name='z3950_port' id='z3950_port' value='".htmlentities($z3950_port,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_base'>".$this->msg["z3950_base"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' class='saisie-30em' name='z3950_base' id='z3950_base' value='".htmlentities($z3950_base,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_login'>".$this->msg["z3950_login"]."</label>&nbsp;
			</div>
			<div class='colonne_suite'>
				<input type='text' class='saisie-20em' name='z3950_login' id='z3950_login' value='".htmlentities($z3950_login,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_password'>".$this->msg["z3950_password"]."</label>&nbsp;
			</div>
			<div class='colonne_suite'>
				<input type='text' class='saisie-20em' name='z3950_password' id='z3950_password' value='".htmlentities($z3950_password,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_format'>".$this->msg["z3950_format"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' class='saisie-30em' name='z3950_format' id='z3950_format' value='".htmlentities($z3950_format,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_max_notices'>".$this->msg["z3950_max_notices"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' class='saisie-10em' name='z3950_max_notices' id='z3950_max_notices' value='".htmlentities($z3950_max_notices,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_convert'>".$this->msg["z3950_convert"]."</label>
			</div>
			<div class='colonne_suite'>
				".$export_type."
			</div>
		</div>";
		
		$xsl_exemplaire_input = "";
		if ($xslt_exemplaire) {
			$xsl_exemplaire_input .= '<select name="action_xsl_expl"><option value="keep">'.sprintf($this->msg["z3950_keep_xsl_exemplaire"], $xslt_exemplaire["name"]).'</option><option value="delete">'.$this->msg["z3950_delete_xsl_exemplaire"].'</option></select>';
		}
		
		$xsl_exemplaire_input .= '&nbsp;<input onchange="document.source_form.action_xsl_expl.selectedIndex=1" type="file" name="xsl_exemplaire"/>';

		$form.="
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_profils'>".$this->msg["z3950_xsl_exemplaire"]."</label>
			</div>
			<div class='colonne_suite'>
				".$xsl_exemplaire_input."
			</div>
		</div>
		";
		
		//Lecture des profils
		$this->get_profiles();
		
		$profils="<input type='hidden' name='reload' value=''/>
			<select name='z3950_profil' id='z3950_profils'>
				<option value=''>Manuel</option>
		";
		for ($i=0; $i<count($this->profiles); $i++) {
			$profils.="<option value='".$this->profiles[$i]["name"]."'".($z3950_profil==$this->profiles[$i]["name"]?" selected":"").">".htmlentities($this->profiles[$i]["comment"],ENT_QUOTES,$charset)."</option>\n";
		}
		$profils.="</select><input type='button' value='".$this->msg["z3950_bib1_calculate"]."' class='bouton_small' onClick='this.form.reload.value=1; this.form.action=\"".basename($_SERVER["REQUEST_URI"])."\"; this.form.act.value=\"add_source\"; this.form.submit();'/>&nbsp;".$this->msg["z3950_warning_bib1"]."\n";
		
		$form.="
		<div class='row'>
			<div class='colonne3'>
				<label for='z3950_profils'>".$this->msg["z3950_profils"]."</label>
			</div>
			<div class='colonne_suite'>
				".$profils."
			</div>
		</div>
		<div class='row'>
		";

		$fields=$this->get_unimarc_search_fields();
		
		//Si c'est un recalcul 
		if ($reload) {
			//Recherche du profil
			for ($i=0; $i<count($this->profiles); $i++) {
				if ($this->profiles[$i]["name"]==$z3950_profil) {
					$profil=$this->profiles[$i];
					break;
				}
			}
		}
		
		$form_bib1="<table class='quadrille'><tr><th>Unimarc</th><th>Champ</th><th>Propriétés</th></tr>\n";
		foreach ($fields as $ufield=>$values) {
			if ($ufield!="FORBIDDEN") {
				$form_bib1.="<tr><td>".htmlentities(" (".$ufield.")",ENT_QUOTES,$charset)."</td><td>".nl2br(htmlentities(implode("\n",$values["TITLE"]),ENT_QUOTES,$charset))."</td><td>";
				$form_bib1.="<table class='quadrille'>\n";
				$form_bib1.="<tr><th>Opérateur</th><th>Att. 1 (Use)</th><th>Att. 2 (Relation)</th><th>Att. 3 (Position)</th><th>Att. 4 (Structure)</th><th>Att. 5 (Truncation)</th><th>Att. 6 (Completeness)</th></tr>\n";
				//Calcul du Att1 si reload
				if ($reload) {
					foreach ($values["OPERATORS"] as $op=>$top) {
						$bibli="bib1_".str_replace("\$","",$ufield)."_".$op."_0";
						global $$bibli;
						$$bibli=$profil["ufields"][$ufield];
						if ($profil["operators"][$op]) {
							$ops=explode(",",$profil["operators"][$op]);
							for ($i=0; $i<count($ops); $i++) {
								$ops_=explode("=",$ops[$i]);
								$opst[$ops_[0]]=$ops_[1];							
							}
							for ($i=1; $i<6; $i++) {
								$bibli="bib1_".str_replace("\$","",$ufield)."_".$op."_".$i;
								global $$bibli;
								$$bibli="";
								if ($opst[$i+1]) {
									$$bibli=$opst[$i+1];
								}
							}
						} else {
							for ($i=1; $i<6; $i++) {
								$bibli="bib1_".str_replace("\$","",$ufield)."_".$op."_".$i;
								global $$bibli;
								$$bibli="";
							}
						}
					} 						
				} else if (count($z3950_bib1)) {
					foreach ($z3950_bib1 as $bib1=>$bib1_value) {
						global $$bib1;
						$$bib1=$bib1_value;
					}
				}
				foreach ($values["OPERATORS"] as $op=>$top) {
					$form_bib1.="<tr><td>".htmlentities(($top?$top:$op),ENT_QUOTES,$charset)."</td>";
					for ($i=0; $i<6; $i++) {
						$bibli="bib1_".str_replace("\$","",$ufield)."_".$op."_".$i;
						global $$bibli;
						$form_bib1.="<td class='quadrille_sub'><input type='text' name='bib1_".str_replace("\$","",$ufield)."_".$op."_".$i."' value='".htmlentities($$bibli,ENT_QUOTES,$charset)."' style='width:4em'/></td>";
					}
					$form_bib1.="</tr>";
				}
				$form_bib1.="</table>\n";
				$form_bib1.="</td></tr>\n";
			}
		}
		$form_bib1.="</table>\n";
		$form.="<script src='javascript/tablist.js'></script>\n";
		$form.=gen_plus("bib1",$this->msg["z3950_bib1"],$form_bib1);
		$form.="
		</div>
		<div class='row'></div>";
		return $form;
    }
    
    function make_serialized_source_properties($source_id) {
    	global $url,$z3950_base,$z3950_login,$z3950_password,$z3950_max_notices,$z3950_format,$z3950_port,$z3950_convert,$z3950_profil, $action_xsl_expl;
    	$t["url"]=stripslashes($url);
    	$t["z3950_base"]=stripslashes($z3950_base);
  		$t["z3950_login"]=stripslashes($z3950_login);
  		$t["z3950_password"]=stripslashes($z3950_password);
  		$t["z3950_max_notices"]=stripslashes($z3950_max_notices);
  		$t["z3950_format"]=stripslashes($z3950_format);
  		$t["z3950_port"]=stripslashes($z3950_port);
  		$t["z3950_convert"]=stripslashes($z3950_convert);
  		$t["z3950_profil"]=stripslashes($z3950_profil);
  		
  		$fields=$this->get_unimarc_search_fields();
  		
  		//Enregistrement des profils
  		foreach ($fields as $ufield=>$values) {
  			foreach ($values["OPERATORS"] as $op=>$top) {
  				for ($i=0; $i<6; $i++) {
  					$bib1="bib1_".str_replace("\$","",$ufield)."_".$op."_".$i;
  					global $$bib1;
  					$t["z3950_bib1"][$bib1]=$$bib1;
  				}
  			}
  		}
  		
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
		$this->repository=2;
		$this->retry=3;
		$this->ttl=1800;
		$this->parameters="";
	}
	
	 //Formulaire des propriétés générales
	function get_property_form() {
		global $charset;
		$this->fetch_global_properties();
		//Affichage du formulaire en fonction de $this->parameters
	}
    
    function make_serialized_properties() {
		//Mise en forme des paramètres à partir de variables globales (mettre le résultat dans $this->parameters)
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
							$requete="insert into entrepot_source_$source_id (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid) values(
							'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".$date_import."',
							'".addslashes($field)."','',".$field_order.",0,'".addslashes($val[$i])."',
							' ".addslashes(strip_empty_words($val[$i]))." ',$recid,'".addslashes($search_id)."')";
							mysql_query($requete);
						}
					}
					$field_order++;
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
	
	function parse_query($query) {
		global $z3950_bib1;
		$r="";
		
		for ($i=count($query)-1; $i>=0; $i--) {
			//if (($query[$i]->inter)&&($i)) $r.=" ".$query[$i]->inter." ";
			if (!$query[$i]->sub) {
				$af=explode(":",$query[$i]->ufield);
				$isid=false;
				if (count($af)>1) {
					if ($af[0]=="id") $isid=true; 
					$amf=$af[1];
				} else $amf=$af[0];
				$ufield=str_replace("\$","",$amf);
				if ($ufield!="FORBIDDEN") {			
					if ($isid) {
						$value=$this->get_values_from_id($query[$i]->values[0],$amf);
					} else $value=$query[$i]->values[0];
					//Recherche de la valeur
					$bib1="bib1_".$ufield."_".$query[$i]->op."_";
					if ($z3950_bib1[$bib1."0"]) {
						if (($query[$i]->inter)&&($i>0)) {
							$r.=" @".$query[$i]->inter;
						}	
						$uns=explode(",",$z3950_bib1[$bib1."0"]);
						for ($k=0; $k<count($uns); $k++) {
							if ($k<count($uns)-1) $r.=" @or";
							$r.=" @attr 1=".$uns[$k];
							for ($j=1; $j<6 ; $j++) {
								$bib=$bib1.$j;
								if ($z3950_bib1[$bib]) {
									$r.=" @attr ".($j+1)."=".$z3950_bib1[$bib];
								}
							}
							$r.=" \"".$value."\"";
						}
					}
				}
			} else {
				$r.=$this->parse_query($query[$i]->sub);
			}
		}
		return $r;
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
	
	function get_convert_order($path) {
		if (count($this->convert_path_order)==0) {
			$this->parse_convert_catalog();
		}
		foreach ($this->convert_path_order as $c) {
			if ($c["PATH"]==$path) return $c["INDEX"];
		}
		return 0;
	}
	
	//Fonction de recherche
	function search($source_id,$query,$search_id) {
		global $base_path, $charset, $include_path;
		
		//global $url,$z3950_base,$z3950_login,$z3950_password,$z3950_max_notices,$z3950_format,$z3950_port,$z3950_convert,$z3950_profil;
		$this->error=false;
		$this->error_message="";
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
		
		if (!($z3950_max_notices*1)) $z3950_max_notices=100;
		
		//Tranformation de la recherche en requete rpn bib1
		$rpn=$this->parse_query($query);
		$zurl=$url.($z3950_port?":".$z3950_port:"").($z3950_base?"/".$z3950_base:"");
		$opts=array();
		if ($z3950_login) $opts["user"]=$z3950_login;
		if ($z3950_password) $opts["password"]=$z3950_password;
		$opts["piggyback"]=false;
		$yaz_id=yaz_connect($zurl,$opts);
		yaz_element($yaz_id,"F");
		yaz_range($yaz_id,1,$z3950_max_notices);
		yaz_syntax($yaz_id,strtolower($z3950_format));
		yaz_search($yaz_id,"rpn",$rpn." ");
		$opts_wait=array("timeout"=>$params["TIMEOUT"]);
		yaz_wait($opts_wait);
		if (yaz_error($yaz_id)) {
			$this->error=true;
			$this->error_message=yaz_error($yaz_id);
		} else {
			$n_results=yaz_hits($yaz_id);
			if ($n_results>$z3950_max_notices) $n_results=$z3950_max_notices;
			$convert_order=$this->get_convert_order($z3950_convert);
			for ($k=1; $k<=$n_results; $k++) {
				$notice = yaz_record($yaz_id, $k,"raw");
				//Conversion de la notice
				if ($z3950_convert) {
					$export= new convert($notice,$convert_order) ;
					if (!$export->error) $cnotice=$export->output_notice; else $cnotice="";
				} else $cnotice=$notice;
				if ($cnotice) {
					//Conversion de la notice en XML
					$xmlunimarc=new xml_unimarc();
					$nxml=$xmlunimarc->iso2709toXML_notice($cnotice);
					if ($xmlunimarc->is_utf8) $rcharset="utf-8"; else $rcharset=$charset;
					$xmlunimarc->notices_xml_[0] = '<?xml version="1.0" encoding="'.$rcharset.'"?>'.$xmlunimarc->notices_xml_[0];
					if ($xslt_exemplaire) {
						$xmlunimarc->notices_xml_[0] = $this->apply_xsl_to_xml($xmlunimarc->notices_xml_[0], $xslt_exemplaire["content"]);
					}
//					print_r($xmlunimarc->notices_xml_[0]);
					if ($nxml>=1) {
						$params=_parser_text_no_function_($xmlunimarc->notices_xml_[0] ,"NOTICE");
						$this->rec_record($params,$source_id,$search_id);
					}
				}
			}
		}
	}
	
	function maj_entrepot($source_id,$callback_progress="",$recover=false,$recover_env="") {
		return 0;
	}
}
?>