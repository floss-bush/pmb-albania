<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: parametres_perso.class.php,v 1.17.2.1 2011-09-12 10:16:37 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Gestion des champs personalisés simplifiée pour l'OPAC

require_once($include_path."/parser.inc.php");
require_once($include_path."/fields_empr.inc.php");
require_once($include_path."/datatype.inc.php");


class parametres_perso {
	
	var $prefix;
	var $no_special_fields;
	var $values;
	
	//Créateur : passer dans $prefix le type de champs persos et dans $base_url l'url a appeller pour les formulaires de gestion	
	function parametres_perso($prefix) {
		global $_custom_prefixe_;
		
		$this->prefix=$prefix;
		$this->base_url=$base_url;
		$_custom_prefixe_=$prefix;
		
		//Lecture des champs
		$this->no_special_fields=0;
		$this->t_fields=array();
		$requete="select idchamp, name, titre, type, datatype, obligatoire, options, multiple, export from ".$this->prefix."_custom order by ordre";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)==0)
			$this->no_special_fields=1;
		else {
			while ($r=mysql_fetch_object($resultat)) {
				$this->t_fields[$r->idchamp]["DATATYPE"]=$r->datatype;
				$this->t_fields[$r->idchamp]["NAME"]=$r->name;
				$this->t_fields[$r->idchamp]["TITRE"]=$r->titre;
				$this->t_fields[$r->idchamp]["TYPE"]=$r->type;
				$this->t_fields[$r->idchamp]["OPTIONS"]=$r->options;
				$this->t_fields[$r->idchamp]["MANDATORY"]=$r->obligatoire;
				$this->t_fields[$r->idchamp]["OPAC_SHOW"]=$r->multiple;
				$this->t_fields[$r->idchamp]["EXPORT"]=$r->export;
			}
		}
	}
	
	//Récupération des valeurs stockées dans les base pour un emprunteur ou autre
	function get_values($id) {
		//Récupération des valeurs stockées 
		if ((!$this->no_special_fields)&&($id)) {
			$this->values = array() ;
			$requete="select ".$this->prefix."_custom_champ,".$this->prefix."_custom_origine,".$this->prefix."_custom_small_text, ".$this->prefix."_custom_text, ".$this->prefix."_custom_integer, ".$this->prefix."_custom_date, ".$this->prefix."_custom_float from ".$this->prefix."_custom_values where ".$this->prefix."_custom_origine=".$id;
			$resultat=mysql_query($requete);
			while ($r=mysql_fetch_array($resultat)) {
				$this->values[$r[$this->prefix."_custom_champ"]][]=$r[$this->prefix."_custom_".$this->t_fields[$r[$this->prefix."_custom_champ"]]["DATATYPE"]];
			}
		} else $this->values=array();
	}
	
	//Affichage des champs en lecture seule pour visualisation d'un fiche emprunteur ou autre...
	//Retourne le tableau $perso de valeur des champs :
	//$perso["FIELDS"]=tableau des champs
	//Pour le Xième champ : 
	//	$perso["FIELDS"][X]["TITRE"] : libellé du champ
	//	$perso["FIELDS"][X]["AFF"] : contenu du champ
	//	$perso["FIELDS"][X]["OPAC_SHOW"] : affichable ou pas dans l'opac (1=affichable, 0=non affichable)
	
	function show_fields($id) {
		global $val_list_empr;
		global $charset;
		
		$perso=array();
		//Récupération des valeurs stockées pour l'emprunteur
		$this->get_values($id);
		if (!$this->no_special_fields) {
			//Affichage champs persos
			$c=0;
			reset($this->t_fields);
			while (list($key,$val)=each($this->t_fields)) {
				$t=array();
				$t["TITRE"]="<b>".htmlentities($val["TITRE"],ENT_QUOTES,$charset)."&nbsp;: </b>";
				$t["TITRE_CLEAN"]=htmlentities($val["TITRE"],ENT_QUOTES,$charset);
				$t["OPAC_SHOW"]=$val["OPAC_SHOW"];
				$field=array();
				$field["ID"]=$key;
				$field["NAME"]=$this->t_fields[$key]["NAME"];
				$field["MANDATORY"]=$this->t_fields[$key]["MANDATORY"];
				$field["ALIAS"]=$this->t_fields[$key]["TITRE"];
				$field["DATATYPE"]=$this->t_fields[$key]["DATATYPE"];
				$field["OPTIONS"][0]=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$this->t_fields[$key]["OPTIONS"], "OPTIONS");
				$field["VALUES"]=$this->values[$key];
				$field["PREFIX"]=$this->prefix;
				$aff=$val_list_empr[$this->t_fields[$key]["TYPE"]]($field,$this->values[$key]);
				if (is_array($aff) && $aff[ishtml] == true)$t["AFF"] = $aff["value"];
				else $t["AFF"]=htmlentities($aff,ENT_QUOTES,$charset);
				$t["ID"]=$field["ID"];
				$t["NAME"]=$field["NAME"];
				$perso["FIELDS"][]=$t;
			}
		}
		return $perso;
	}
	
	function get_formatted_output($values,$field_id) {
		global $val_list_empr, $charset;
		
		$field=array();
		$field["ID"]=$field_id;
		$field["NAME"]=$this->t_fields[$field_id]["NAME"];
		$field["MANDATORY"]=$this->t_fields[$field_id]["MANDATORY"];
		$field["ALIAS"]=$this->t_fields[$field_id]["TITRE"];
		$field["DATATYPE"]=$this->t_fields[$field_id]["DATATYPE"];
		$field["OPTIONS"][0]=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$this->t_fields[$field_id]["OPTIONS"], "OPTIONS");
		$field["VALUES"]=$values;
		$field["PREFIX"]=$this->prefix;
		$aff=$val_list_empr[$this->t_fields[$field_id]["TYPE"]]($field,$values);
		if(is_array($aff)) return $aff['withoutHTML']; 
		else return $aff;
	}

	function get_fields_recherche($id) {
		$return_val='';
		
		$this->get_values($id);
		if (!$this->no_special_fields) {
			reset($this->t_fields);		
			while (list($key,$val)=each($this->t_fields)) {
				if($this->t_fields[$key]["SEARCH"] ) {
					for ($i=0; $i<count($this->values[$key]); $i++) {
						$return_val.=$this->values[$key][$i].' ';
					}
				}	
			}
		}		
		return stripslashes($return_val);
	}	

}
?>