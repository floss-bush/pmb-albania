<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: oai.class.php,v 1.17.2.1 2011-07-18 19:03:41 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");
require_once($base_path."/admin/connecteurs/in/oai/oai_protocol.class.php");

if (version_compare(PHP_VERSION,'5','>=') && extension_loaded('xsl')) {
	if (substr(phpversion(), 0, 1) == "5") @ini_set("zend.ze1_compatibility_mode", "0");
	require_once($include_path.'/xslt-php4-to-php5.inc.php');
	}

class oai extends connector {
	//Variables internes pour la progression de la récupération des notices
	var $callback_progress;		//Nom de la fonction de callback progression passée par l'appellant
	var $current_set;			//Set en cours de synchronisation
	var $total_sets;			//Nombre total de sets sélectionnés
	var $metadata_prefix;		//Préfixe du format de données courant
	var $source_id;				//Numéro de la source en cours de synchro
	var $n_recu;				//Nombre de notices reçues
	var $xslt_transform;		//Feuille xslt transmise
	var $sets_names;			//Nom des sets pour faire plus joli !!
	var $del_old;				//Supression ou non des notices dejà existantes
	
	//Résultat de la synchro
	var $error;					//Y-a-t-il eu une erreur	
	var $error_message;			//Si oui, message correspondant
	
    function oai($connector_path="") {
    	parent::connector($connector_path);
    }
    
    function get_id() {
    	return "oai";
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
		$form="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["oai_url"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='url' id='url' class='saisie-60em' value='".htmlentities($url,ENT_QUOTES,$charset)."'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='clean_base_url'>".$this->msg["oai_clean_url"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='checkbox' name='clean_base_url' id='clean_base_url' value='1' ".($clean_base_url?"checked":"")."/>
			</div>
		</div>
		<div class='row'>
		";
		if (!$url) 
			$form.="<h3 style='text-align:center'>".$this->msg["rec_addr"]."</h3>";
		else {
			//Intérogation du serveur
			$oai_p=new oai20($url,$charset,$params["TIMEOUT"]);
			if ($oai_p->error) {
				$form.="<h3 style='text-align:center'>".sprintf($this->msg["error_contact_server"],$oai_p->error_message)."</h3>";
			} else {
				$form.="<h3 style='text-align:center'>".$oai_p->repositoryName."</h3>";
				if ($oai_p->description)
					$form.="
					<div class='row'>
						<div class='colonne3'>
							<label>".$this->msg["oai_desc"]."</label>
						</div>
						<div class='colonne_suite'>
							".htmlentities($oai_p->description,ENT_QUOTES,$charset)."
						</div>
					</div>
					";
				$form.="
				<div class='row'>
					<div class='colonne3'>
						<label>".$this->msg["oai_older_metatdatas"]."</label>
					</div>
					<div class='colonne_suite'>
						".formatdate($oai_p->earliestDatestamp)."
					</div>
				</div>
				<div class='row'>
					<div class='colonne3'>
						<label>".$this->msg["oai_email_admin"]."</label>
					</div>
					<div class='colonne_suite'>
						".$oai_p->adminEmail."
					</div>
				</div>
				<div class='row'>
					<div class='colonne3'>
						<label>".$this->msg["oai_granularity"]."</label>
					</div>
					<div class='colonne_suite'>
						".($oai_p->granularity=="YYYY-MM-DD"?$this->msg["oai_one_day"]:$this->msg["oai_minute"])."
					</div>
				</div>";
				if ($oai_p->has_feature("SETS")) {
					$form.="
				<div class='row'>
					<div class='colonne3'>
						<label for='sets'>".$this->msg["oai_sets_to_sync"]."</label>
					</div>
					<div class='colonne_suite'>";
					if (count($oai_p->sets)<80) $combien = count($oai_p->sets);
					else $combien=80; 
					$form.="<select id='sets' name='sets[]' class='saisie-80em' multiple='yes' size='".$combien."'>";
					foreach ($oai_p->sets as $set=>$setname) {
						$form.="<option value='".htmlentities($set,ENT_QUOTES,$charset)."' alt='".htmlentities($setname,ENT_QUOTES,$charset)."' title='".htmlentities($setname,ENT_QUOTES,$charset)."' ".(@array_search($set,$sets)!==false?"selected":"").">".htmlentities($setname,ENT_QUOTES,$charset)."</option>\n";
					}
					$form.="	</select>
					</div>
				</div>";
				}
				$form.="
				<div class='row'>
					<div class='colonne3'>
						<label for='formats'>".$this->msg["oai_preference_format"]."</label>
					</div>
					<div class='colonne_suite'>
						<select name='formats' id='formats'>";
					if (!is_array($formats))
						$formats = array($formats);
					for ($i=0; $i<count($oai_p->metadatas) ;$i++) {
						$form.="<option value='".htmlentities($oai_p->metadatas[$i]["PREFIX"],ENT_QUOTES,$charset)."' alt='".htmlentities($oai_p->metadatas[$i]["PREFIX"],ENT_QUOTES,$charset)."' title='".htmlentities($oai_p->metadatas[$i]["PREFIX"],ENT_QUOTES,$charset)."' ".(@array_search($oai_p->metadatas[$i]["PREFIX"],$formats)!==false?"selected":"").">".htmlentities($oai_p->metadatas[$i]["PREFIX"],ENT_QUOTES,$charset)."</option>\n";
					}
					$form.="	</select> ".$this->msg["oai_xslt_file"]." <input type='file' name='xslt_file' />";
					if ($xsl_transform) $form.="<br /><i>".sprintf($this->msg["oai_xslt_file_linked"],$xsl_transform["name"])."</i> : ".$this->msg["oai_del_xslt_file"]." <input type='checkbox' name='del_xsl_transform' value='1'/>";
					$form.="						</div>
				</div>";
				if (($oai_p->deletedRecord=="persistent")||($oai_p->deletedRecord=="transient")) {
					$form.="
				<div class='row'>
					<div class='colonne3'>
						<label>".sprintf($this->msg["oai_del_marked_elts"],($oai_p->deletedRecord=="persistent"?$this->msg["oai_del_marked_persistent"]:$this->msg["oai_del_marked_temp"])).")</label>
					</div>
					<div class='colonne_suite'>
						<label for='del_yes'>".$this->msg["oai_yes"]."</label><input type='radio' name='del_deleted' id='del_yes' value='1' ".($del_deleted==1?"checked":"").">
						<label for='del_no'>".$this->msg["oai_no"]."</label><input type='radio' name='del_deleted' id='del_no' value='0' ".($del_deleted==0?"checked":"").">
					</div>
				</div>";
				}
			}
		}
		$form.="
	</div>
	<div class='row'></div>
";
		return $form;
    }
    
    function make_serialized_source_properties($source_id) {
    	global $url,$clean_base_url,$sets,$formats,$del_deleted,$del_xsl_transform;
    	$t["url"]=stripslashes($url);
    	$t["clean_base_url"]=$clean_base_url;
    	$t["sets"]=$sets;
    	$t["formats"]=$formats;
    	$t["del_deleted"]=$del_deleted;
    	
    	//Vérification du fichier
    	if (($_FILES["xslt_file"])&&(!$_FILES["xslt_file"]["error"])) {
    		$xslt_file_content=array();
    		$xslt_file_content["name"]=$_FILES["xslt_file"]["name"];
    		$xslt_file_content["code"]=file_get_contents($_FILES["xslt_file"]["tmp_name"]);
    		$t["xsl_transform"]=$xslt_file_content;
    	} else if ($del_xsl_transform) {
			$t["xsl_transform"]="";
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
	
	function progress($query,$token) {
		$callback_progress=$this->callback_progress;
		if ($token["completeListSize"]) {
			$percent=($this->current_set/$this->total_sets)+(($token["cursor"]/$token["completeListSize"])/$this->total_sets);
			$nlu=$this->n_recu;
			$ntotal="inconnu";
			//$nlu=$token["cursor"];
			//$ntotal=$token["completeListSize"];
		} else {
			$percent=($this->current_set/$this->total_sets);
			$nlu=$this->n_recu;
			$ntotal="inconnu";
		}
		$callback_progress($percent,$nlu,$ntotal);
	}
	
	function rec_record($record) {
		global $charset,$base_path;
		
		$rec=new oai_record($record,$charset,$base_path."/admin/connecteurs/in/oai/xslt",$this->metadata_prefix,$this->xslt_transform,$this->sets_names);
		$rec_uni=$rec->unimarc;
		if ($rec->error) echo 'erreur!<br />';
		if (!$rec->error) {
			//On a un enregistrement unimarc, on l'enregistre
			$rec_uni_dom=new xml_dom($rec_uni,$charset);
			if (!$rec_uni_dom->error) {
				//Initialisation
				$ref="";
				$ufield="";
				$usubfield="";
				$field_order=0;
				$subfield_order=0;
				$value="";
				$date_import=$rec->header["DATESTAMP"];
				
				$fs=$rec_uni_dom->get_nodes("unimarc/notice/f");
				//Recherche du 001
				for ($i=0; $i<count($fs); $i++) {
					if ($fs[$i]["ATTRIBS"]["c"]=="001") {
						$ref=$rec_uni_dom->get_datas($fs[$i]);
						break;
					}
				}
				//Mise à jour 
				if ($ref) {
					//Si conservation des anciennes notices, on regarde si elle existe
					if (!$this->del_old) {
						$requete="select count(*) from entrepot_source_".$this->source_id." where ref='".addslashes($ref)."'";
						$rref=mysql_query($requete);
						if ($rref) $ref_exists=mysql_result($rref,0,0);
					}
					//Si pas de conservation des anciennes notices, on supprime
					if ($this->del_old) {
						$requete="delete from entrepot_source_".$this->source_id." where ref='".addslashes($ref)."'";
						mysql_query($requete);
					}
					//Si pas de conservation ou reférence inexistante
					if (($this->del_old)||((!$this->del_old)&&(!$ref_exists))) {
						//Insertion de l'entête
						$n_header["rs"]=$rec_uni_dom->get_value("unimarc/notice/rs");
						$n_header["ru"]=$rec_uni_dom->get_value("unimarc/notice/ru");
						$n_header["el"]=$rec_uni_dom->get_value("unimarc/notice/el");
						$n_header["bl"]=$rec_uni_dom->get_value("unimarc/notice/bl");
						$n_header["hl"]=$rec_uni_dom->get_value("unimarc/notice/hl");
						$n_header["dt"]=$rec_uni_dom->get_value("unimarc/notice/dt");
						
						//Récupération d'un ID
						$requete="insert into external_count (recid, source_id) values('".addslashes($this->get_id()." ".$this->source_id." ".$ref)."', ".$this->source_id.")";
						$rid=mysql_query($requete);
						if ($rid) $recid=mysql_insert_id();
						
						foreach($n_header as $hc=>$code) {
							$requete="insert into entrepot_source_".$this->source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid) values(
							'".addslashes($this->get_id())."',".$this->source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
							'".$hc."','',-1,0,'".addslashes($code)."','',$recid)";
							mysql_query($requete);
						}
						
						for ($i=0; $i<count($fs); $i++) {
							$ufield=$fs[$i]["ATTRIBS"]["c"];
							$field_order=$i;
							$ss=$rec_uni_dom->get_nodes("s",$fs[$i]);
							if (is_array($ss)) {
								for ($j=0; $j<count($ss); $j++) {
									$usubfield=$ss[$j]["ATTRIBS"]["c"];
									$value=$rec_uni_dom->get_datas($ss[$j]);
									$subfield_order=$j;
									$requete="insert into entrepot_source_".$this->source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid) values(
									'".addslashes($this->get_id())."',".$this->source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
									'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
									' ".addslashes(strip_empty_words($value))." ',$recid)";
									mysql_query($requete);
								}
							} else {
								$value=$rec_uni_dom->get_datas($fs[$i]);
								$requete="insert into entrepot_source_".$this->source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid) values(
								'".addslashes($this->get_id())."',".$this->source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
								'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
								' ".addslashes(strip_empty_words($value))." ',$recid)";
								mysql_query($requete);
							}
						}
					}
					$this->n_recu++;
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
		global $charset;
		$source_id=$source_id+0;
		$params=$this->get_source_params($source_id);
		$vars=unserialize($params["PARAMETERS"]);

		$datefrom = 0;
		$oai_p=new oai20($vars['url'],$charset,$params["TIMEOUT"]);
		if (!$oai_p->error) 		
			$earliestdate = strtotime(substr($oai_p->earliestDatestamp, 0, 10));

		$sql = " SELECT MAX(UNIX_TIMESTAMP(date_import)) FROM entrepot_source_".$source_id;
		$res = mysql_result(mysql_query($sql), 0, 0);
		$datefrom = $res ? $res : $earliestdate;
		$latest_date_database_string = $res ? formatdate(date("Y-m-d", $res)) : "<i>".$this->msg["oai_syncinfo_nonotice"]."</i>";
		
		$dateuntil = "";		
		$form = "<blockquote>";
		$form .= "
				".$this->msg["oai_get_notices"]." 
				<br /><br />
				".$this->msg["oai_between_part1"]." <br />
				<strong>
					<input type='hidden' name='form_from' value='".date("Y-m-d",$datefrom)."' />
					<input type=\"text\" readonly size=\"10\" name=\"form_from_lib\" value=\"".formatdate(date("Y-m-d",$datefrom))."\">
					<input class='bouton' type='button' name='form_from_button' value='Selectionner' onClick=\"openPopUp('./select.php?what=calendrier&caller=sync_form&date_caller=".date("Ymd",$datefrom)."&param1=form_from&param2=form_from_lib&auto_submit=NO&date_anterieure=YES', 'date_adhesion', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"   /> (facultatif)
				</strong>
				<br /> ".$this->msg["oai_between_part2"]." <br />
				<strong>
					<input type='hidden' name='form_until' value='$dateuntil' />
					<input type=\"text\" readonly size=\"10\" name=\"form_until_lib\" value=\"\">
					<input class='bouton' type='button' name='form_until_button' value='Selectionner' onClick=\"openPopUp('./select.php?what=calendrier&caller=sync_form&date_caller=$dateuntil&param1=form_until&param2=form_until_lib&auto_submit=NO&date_anterieure=YES', 'date_adhesion', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"   /> (facultatif)
				</strong>
		<br /><br />
";
		
		$form .= sprintf($this->msg["oai_syncinfo_date_serverearlyiest"], formatdate(date("Y-m-d",$earliestdate)));
		$form .= "<br />".sprintf($this->msg["oai_syncinfo_date_baserecent"], $latest_date_database_string);
		
		$form .= "</blockquote>";
		return $form;
	}	
	
	//Nécessaire pour passer les valeurs obtenues dans form_pour_maj_entrepot au javascript asynchrone
	function get_maj_environnement($source_id) {
		global $form_from;
		global $form_until;
		$envt=array();
		$envt["form_from"]=$form_from;
		$envt["form_until"]=$form_until;
		return $envt;
	}
	
	function maj_entrepot($source_id,$callback_progress="",$recover=false,$recover_env="") {
		global $charset;
		global $form_from, $form_until;
		
		$form_from = strtotime($form_from);
		if ($form_until) 
			$form_until = strtotime($form_until);
		else $form_until = '';
		
		$this->callback_progress=$callback_progress;
		$params=$this->unserialize_source_params($source_id);
		$p=$params["PARAMETERS"];
		$this->metadata_prefix=$p["formats"];
		$this->source_id=$source_id;
		$this->n_recu=0;
		$this->xslt_transform=$p["xsl_transform"]["code"];
		
		//Connexion
		$oai20=new oai20($p["url"],$charset,$p["TIMEOUT"],$p["clean_base_url"]);
		if (!$oai20->error) {
			if ($recover) {
				$envt=unserialize($recover_env);
				$sets=$envt["sets"];
				$date_start=$envt["date_start"];
				$date_end=$envt["date_end"];
				$this->del_old=false;
			} else {
				//Recherche de la dernière date...
				$requete="select unix_timestamp(max(date_import)) from entrepot_source_".$source_id." where 1;";
				$resultat=mysql_query($requete);
				if (mysql_num_rows($resultat)) {
					$last_date=mysql_result($resultat,0,0);
					if ($last_date) {
						//En fonction de la granularité, on ajoute une seconde ou un jour !
						if ($oai20->granularity=="YYYY-MM-DD") $last_date+=3600*24; else $last_date+=1;
					} else {
						$earliest_date=new iso8601($oai20->granularity);
						$last_date=$earliest_date->iso8601_to_unixtime($oai20->earliestDatestamp);
					}
				} else {
					$earliest_date=new iso8601($oai20->granularity);
					$last_date=$earliest_date->iso8601_to_unixtime($oai20->earliestDatestamp);
				}
				//Affectation de la date de départ
				if ($form_from)
					$date_start=$form_from;
				else
					$date_start=$last_date;
					
				$date_end = $form_until;
				//Recherche des sets sélectionnés
				$this->sets_names=$oai20->sets;
				for ($i=0; $i<count($p["sets"]);$i++) {
					if ($oai20->sets[$p["sets"][$i]]) {
						$sets[]=$p["sets"][$i];
					}
				}
				$this->del_old=true;
			}
			
			//Mise à jour de source_sync pour reprise en cas d'erreur
			$envt["sets"]=$sets;
			$envt["date_start"]=$date_start;
			$envt["date_end"]=$date_end;
			$requete="update source_sync set env='".addslashes(serialize($envt))."' where source_id=".$source_id;
			mysql_query($requete);
			
			//Lancement de la requête
			$this->current_set=0;
			$this->total_sets=count($sets);
			if (count($sets)) {
				for ($i=0; $i<count($sets); $i++) {
					$this->current_set=$i;
					$oai20->list_records($date_start,$date_end,$sets[$i],$p["formats"],array(&$this,"rec_record"),array(&$this,"progress"));
					if (($oai20->error)&&($oai20->error_oai_code!="noRecordsMatch")) {
						$this->error=true;
						$this->error_message.=$oai20->error_message."<br />";
					}
				}
			} else {
				$this->current_set=0;
				$this->total_sets=1;
				$oai20->list_records($date_start,$date_end,"",$p["formats"],array(&$this,"rec_record"),array(&$this,"progress"));
				if (($oai20->error)&&($oai20->error_oai_code!="noRecordsMatch")) {
					$this->error=true;
					$this->error_message.=$oai20->error_message."<br />";
				}
			}
		} else {
			$this->error=true;
			$this->error_message=$oai20->error_message;
		}
		return $this->n_recu;
	}
}
?>