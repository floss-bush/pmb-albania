<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: quotas.class.php,v 1.12 2009-10-22 08:36:03 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Classe de calcul des quotas

require_once($include_path."/parser.inc.php");
require_once($class_path."/marc_table.class.php");

class quota {
	
	var $type_id;
	var $quota_type;
	var $elements_values_id;
	var $elements_values;
	var $elements_forc_id;
	var $elements_forc;
	var $error_message;
	var $force;
	var $table;
	var $descriptor;
	
	function parse_quotas($descriptor="") {
		global $_parsed_quotas_;
		global $_quotas_elements_;
		global $_quotas_types_;
		global $lang;
		global $include_path;
		global $_quotas_table_;
		
		//Recherche du fichier de description
		if ($this->descriptor)
			$p_descriptor=$this->descriptor;
		else if ($descriptor)
			$p_descriptor=$descriptor;
		else
			$p_descriptor=$include_path."/quotas/$lang.xml";
		
		//Parse le fichier dans un tableau
		$fp=fopen($p_descriptor,"r") or die("Can't find XML file $p_descriptor");
		$xml=fread($fp,filesize($p_descriptor));
		fclose($fp);
		$param=_parser_text_no_function_($xml, "PMBQUOTAS");
	
		if (!$param["TABLE"]) {
			$this->table="quotas";
		} else $this->table=$param["TABLE"];
		
		$_quotas_table_=$this->table;
		
		//Récupération des éléments
		$_quotas_elements_=array();
		
		for ($i=0; $i<count($param["ELEMENTS"][0]["ELEMENT"]); $i++) {
			$p_elt=$param["ELEMENTS"][0]["ELEMENT"][$i];
			$elt=array();
			$elt["NAME"]=$p_elt["NAME"];
			$elt["ID"]=$p_elt["ID"];
			$elt["COMMENT"]=$p_elt["COMMENT"];
			$elt["LINKEDTO"]=$p_elt["LINKEDTO"][0]["value"];
			$elt["TABLELINKED"]=$p_elt["TABLELINKED"][0]["value"];
			$elt["TABLELINKED_BY"]=$p_elt["TABLELINKED"][0]["BY"];
			$elt["LINKEDFIELD"]=$p_elt["LINKEDFIELD"][0]["value"];
			$elt["LINKEDID"]=$p_elt["LINKEDID"][0]["value"];
			$elt["LINKEDID_BY"]=$p_elt["LINKEDID"][0]["BY"];
			$elt["TABLE"]=$p_elt["TABLE"][0]["value"];
			if ($p_elt["TABLE"][0]["TYPE"]=="marc_list") {
				$ml=new marc_list($elt["TABLE"]);
				reset($ml->table);
				$requete="create temporary table ".$elt["TABLE"]." (id varchar(255),libelle varchar(255)) ENGINE=MyISAM ";
				mysql_query($requete);
				while (list($key,$val)=each($ml->table)) {
					$requete="insert into ".$elt["TABLE"]." (id,libelle) values('".addslashes($key)."','".addslashes($val)."')";
					mysql_query($requete);
				}
				$elt["FIELD"]="id";
				$elt["LABEL"]="libelle";
			} else {
				$elt["FIELD"]=$p_elt["FIELD"][0]["value"];
				$elt["LABEL"]=$p_elt["LABEL"][0]["value"];
			}
			define($elt["NAME"],$elt["ID"]);
			$_quotas_elements_[]=$elt;
		}
		
		//Récupération des types
		$_quotas_types_=array();
		
		for ($i=0; $i<count($param["TYPES"][0]["TYPE"]); $i++) {
			$p_typ=$param["TYPES"][0]["TYPE"][$i];
			$typ=array();
			$typ["NAME"]=$p_typ["NAME"];
			$typ["ID"]=$p_typ["ID"];
			$typ["COMMENT"]=$p_typ["COMMENT"];
			$typ["SHORT_COMMENT"]=$p_typ["SHORT_COMMENT"];
			$typ["COMMENTFORCELEND"]=$p_typ["COMMENTFORCELEND"];
			$typ["FILTER_ID"]=$p_typ["FILTER_ID"];
			
			$p_typ_entity=$p_typ["ENTITY"][0];
			$typ["ENTITY"]=$p_typ_entity["NAME"];
			if ($p_typ_entity["MAXQUOTA"]=="yes") $typ["MAX_QUOTA"]=true;
			
			$typ["COUNT_TABLE"]=$p_typ_entity["COUNTTABLE"][0]["value"];
			$typ["COUNT_FIELD"]=$p_typ_entity["COUNTFIELD"][0]["value"];
			$typ["COUNT_FILTER"]=$p_typ_entity["COUNTFILTER"][0]["value"];
			$typ["MAX_ERROR_MESSAGE"]=$p_typ_entity["MAX_ERROR_MESSAGE"][0]["value"];
			$typ["PARTIAL_ERROR_MESSAGE"]=$p_typ_entity["PARTIAL_ERROR_MESSAGE"][0]["value"];
			$typ["DEFAULT_ERROR_MESSAGE"]=$p_typ_entity["DEFAULT_ERROR_MESSAGE"][0]["value"];
			
			if ($p_typ["MAX"]=="yes") $typ["MAX"]=true; else $typ["MAX"]=false;
			if ($p_typ["MIN"]=="yes") $typ["MIN"]=true; else $typ["MIN"]=false;
			if ($p_typ["FORCELEND"]=="yes") $typ["FORCELEND"]=true; else $typ["FORCELEND"]=false;
			
			$quotas=array();
			$countfields=array();
			for ($j=0; $j<count($p_typ["QUOTAS"][0]["ON"]); $j++) {
				$quotas[]=$p_typ["QUOTAS"][0]["ON"][$j]["value"];
				$countfields[]=$p_typ["QUOTAS"][0]["ON"][$j]["COUNTFIELDS"];
			}
			
			$typ["QUOTAS"]=$quotas;
			$typ["COUNTFIELDS"]=$countfields;
			
			define($typ["NAME"],$typ["ID"]);
			$_quotas_types_[]=$typ;
		}
		
		$_parsed_quotas_=1;
	}
	
	//Récupération d'un élément à partir de son nom
	function get_element_by_name($element_name) {
		global $_quotas_elements_;
		global $_parsed_quotas_;
		
		if ($_parsed_quotas_) {
			reset($_quotas_elements_);
			while (list($key,$val)=each($_quotas_elements_)) {
				if ($val["NAME"]==$element_name)
					return $key;
			}
			return -1;
		} else return -1;
	}
	
	//Récupération d'un élément à partir de son ID
	function get_element_by_id($element_id) {
		global $_quotas_elements_;
		global $_parsed_quotas_;
	
		if ($_parsed_quotas_) {
			reset($_quotas_elements_);
			while (list($key,$val)=each($_quotas_elements_)) {
				if ($val["ID"]==$element_id)
					return $key;
			}
			return -1;
		} else return -1;
	}
	
	//Récupération de l'ID d'un élément par son nom
	function get_element_id_by_name($element_name) {
		global $_quotas_elements_;
		global $_parsed_quotas_;
		
		if ($_parsed_quotas_) {
			reset($_quotas_elements_);
			while (list($key,$val)=each($_quotas_elements_)) {
				if ($val["NAME"]==$element_name)
					return $val["ID"];
			}
			return -1;
		} else return -1;
	}
	
	//Récupération de l'ID de plusieurs éléments par leur noms séparés par des virgules
	function get_elements_id_by_names($elements) {
		global $_quotas_elements_;
		global $_parsed_quotas_;
		
		$id=0;
		$elts=explode(",",$elements);
		for ($j=0; $j<count($elts); $j++) {
			$id|=$this->get_element_id_by_name($elts[$j]);
		}
		
		return $id;
	}
	
	//Récupération de la structure type de quota par son id
	function get_quota_type_by_id($type_id) {
		global $_parsed_quotas_;
		global $_quotas_types_;
		
		$r=array();
		if ($_parsed_quotas_) {
			for ($i=0; $i<count($_quotas_types_); $i++) {
				if ($_quotas_types_[$i]["ID"]==$type_id) {
					$r=$_quotas_types_[$i];
					break;
				}
			}
		}
		return $r;
	}
	
	//Récupération de la structure type de quota par son id
	function get_quota_type_by_name($type_id) {
		global $_parsed_quotas_;
		global $_quotas_types_;
		
		$r=array();
		if ($_parsed_quotas_) {
			for ($i=0; $i<count($_quotas_types_); $i++) {
				if ($_quotas_types_[$i]["NAME"]==$type_id) {
					$r=$_quotas_types_[$i];
					break;
				}
			}
		}
		return $r;
	}
	
	//Récupération des valeurs liées aux paramètres généraux du type de quota
	function get_values() {
		global $min_value,$max_value,$default_value,$conflict_value,$conflict_list,$force_lend,$max_quota;
		
		$requete="select constraint_type, elements, value from ".$this->table." where quota_type=".$this->quota_type["ID"]." and constraint_type in ('MIN','MAX','DEFAULT','CONFLICT','PRIORITY','FORCE_LEND','MAX_QUOTA')";

		$resultat=mysql_query($requete);
		while ($r=mysql_fetch_object($resultat)) {
			switch ($r->constraint_type) {
				case 'MIN':
					if ($this->quota_type["MIN"])
						$min_value=$r->value;
					else
						$min_value=0;
					break;
				case 'MAX':
					if ($this->quota_type["MAX"])
						$max_value=$r->value;
					else
						$max_value=0;
					break;
				case 'DEFAULT':
					$default_value=$r->value;
					break;
				case 'CONFLICT':
					$conflict_value=$r->value;
					break;
				case 'PRIORITY':
					$conflict_list[$r->value]=$r->elements;
					break;
				case 'FORCE_LEND':
					$force_lend=1;
					break;
				case 'MAX_QUOTA':
					$max_quota=$r->value;
					break;
			}
		} 
		
		if (!$conflict_value) $conflict_value=4;
		//Valeurs par défaut de vérification
		$n=0;
		if ($conflict_value==4) {
			for ($i=(count($this->quota_type["QUOTAS"])-1); $i>=0; $i--) {
				if (!$conflict_list[$n]) {
					$conflict_list[$n]=$this->get_elements_id_by_names($this->quota_type["QUOTAS"][$i]);
				}
				$n++;
			}
		}
	}
	
	//Récupération du tableau des ids de chaque élément composant un id multiple
	function get_table_ids_from_elements_id($id) {
		global $_quotas_elements_;
		
		$r=array();
		for ($i=0; $i<count($_quotas_elements_); $i++) {
			if (((int)$id&(int)$_quotas_elements_[$i]["ID"])==(int)$_quotas_elements_[$i]["ID"]) {
				$r[]=$_quotas_elements_[$i]["ID"];
			}
		}
		return $r;
	}
	
	//Récupération du tableau des ids de chaque élément composant un id multiple trié par ordre décrit dans le type de quota
	function get_table_ids_from_elements_id_ordered($id) {
		$ids=array();
		for ($i=0; $i<count($this->quota_type["QUOTAS"]); $i++) {
			if ($this->get_elements_id_by_names($this->quota_type["QUOTAS"][$i])==$id) {
				$names=explode(",",$this->quota_type["QUOTAS"][$i]);
				for ($j=0; $j<count($names); $j++) {
					$ids[]=$this->get_element_id_by_name($names[$j]);
				}
				break;
			}
		}
		return $ids;
	}
	
	function get_quotas_index_from_elements_id($id) {
		for ($i=0; $i<count($this->quota_type["QUOTAS"]); $i++) {
			if ($this->get_elements_id_by_names($this->quota_type["QUOTAS"][$i])==$id) {
				return $i;
			}
		}
		return -1;
	}
	
	//Récupération du titre correspondant aux éléments du quota (par xxx et par xxx et ...)
	function get_title_by_elements_id($id) {
		global $_quotas_elements_;
		global $msg;
		
		$ids=$this->get_table_ids_from_elements_id($id);
		$r=array();
		for ($i=0; $i<count($ids); $i++) {
			$r[]=$msg["quotas_by"]." ".$_quotas_elements_[$this->get_element_by_id($ids[$i])]["COMMENT"];
		}
		return implode(" ".$msg["quotas_and"]." ",$r);
	}
	
	//Récupération des valeurs de quota et de forçage dans la base pour un ou des éléments
	function get_elements_values($id) {
		
		//Récupération des valeurs de quota
		$requete="select quota_type,constraint_type,elements,value from ".$this->table." where quota_type=".$this->quota_type["ID"]." and elements=".$id." and (constraint_type like 'Q:%' or constraint_type like 'F:%')";
		$resultat=mysql_query($requete);
		
		while ($r=mysql_fetch_object($resultat)) {
			//Analyse de la contrainte
			$constraint=substr($r->constraint_type,2);
			$constraint_type=substr($r->constraint_type,0,1);
			$values=explode(",",$constraint);
			$ids=array();
			for ($i=0; $i<count($values); $i++) {
				$v=explode("=",$values[$i]);
				$ids[$v[0]]=$v[1];
			}
			if ($constraint_type=="Q") {
				$this->elements_values_id[]=$ids;
				$this->elements_values[]=$r->value;
			} else {
				$this->elements_forc_id[]=$ids;
				$this->elements_forc[]=$r->value;
			}
		}
	}
	
	//Recherche d'une valeur de quota déjà saisie avec les valeurs de chaque élément
	function search_for_element_value($values_ids,$ids) {
		
		for ($i=0; $i<count($this->elements_values_id); $i++) {
			$elvid=$this->elements_values_id[$i];
			$test=1;
			for ($j=0; $j<count($ids); $j++) {
				$test&=($elvid[$ids[$j]]==$values_ids[$j]);
			}
			if ($test) return $this->elements_values[$i];
		}
		return "";
	}
	
	//Recherche d'une valeur de forçage déjà saisie avec les valeurs de chaque élément
	function search_for_element_forc($values_ids,$ids) {
		
		for ($i=0; $i<count($this->elements_forc_id); $i++) {
			$elvid=$this->elements_forc_id[$i];
			$test=1;
			for ($j=0; $j<count($ids); $j++) {
				$test&=($elvid[$ids[$j]]==$values_ids[$j]);
			}
			if ($test) return $this->elements_forc[$i];
		}
		return "";
	}
	
	//Affichage récursif de la liste des quotas pour les éléments choisis
	function show_quota_list($level,$prefixe_label,$prefixe_varname,$ids,$elements,&$ta) {
		global $min_value,$max_value,$max_quota;
		global $msg;
		global $charset;
		
		$jsscript="r=true; if (!check_nan(this)) r=false; ";
		if (($this->quota_type["MAX"])&&($max_value)&&(!$max_quota)) {
			$jsscript.="if (!check_max('!!val!!',$max_value)) r=false; ";
		}
		if (($this->quota_type["MIN"])&&($min_value)&&(!$max_quota)) {
			$jsscript.="if (!check_min('!!val!!',$min_value)) r=false; ";
		}
		if ($jsscript) $jsscript.=" if (!r) { this.value=''; this.focus(); return false;}";
		//Peut-on forcer le prêt ?	
		if ($this->quota_type["FORCELEND"]) $force_lend=1;
		//Pour chaque élément
		for ($i=0; $i<count($elements[$ids[$level]]); $i++) {
			
			//On récupère le label du champ concerné
			$prefixe_label_=$elements[$ids[$level]][$i]["LABEL"];
			
			//Construction du nom de la variable
			$prefixe_varname_=$prefixe_varname."_".$elements[$ids[$level]][$i]["ID"];
			$ta.="<tr>";
			for ($j=0; $j<$level; $j++) {
					$ta.="<td>&nbsp;</td>";
				}
			//Si on n'est pas au dernier niveau, on appel récursivement
			if ($level<(count($ids)-1)) {
				$ta.="<th colspan='".(count($ids)+1+$force_lend-$level)."'>".$prefixe_label_."</th></tr>\n"; 
				//Appel récursif
				$this->show_quota_list($level+1,$prefixe_label_,$prefixe_varname_,$ids,$elements,$ta);
			} else {
				//Si c'est le dernier niveau
				$values_ids=explode("_",substr($prefixe_varname_,1));
				//Recherche d'une valeur de quota déjà enregistrée
				$quota=$this->search_for_element_value($values_ids,$ids);
				//Recherche d'une valeur de forçage de prêt déjà enregistrée
				if ($force_lend) {
					$forc=$this->search_for_element_forc($values_ids,$ids);
					if ($forc) $checked="checked"; else $checked="";
				}
				if ($jsscript) $jsscript_=str_replace("!!val!!","val".$prefixe_varname_,$jsscript); else $jsscript_="";
				$ta.="<td>".$prefixe_label_."</td><td><input type='text' class='saisie-5em' name='val".$prefixe_varname_."' value='".$quota."' ";
				if ($jsscript_) $ta.="onChange=\"".$jsscript_."\"";
				$ta.="/></td>";
				if ($force_lend)
					$ta.="<td><input type='checkbox' name='forc".$prefixe_varname_."' value='1' ".$checked."/></td>";
				$ta.="</tr>\n";
			}
		}
	}
	
	//Affichage de la table des quotas
	function show_quota_table($elements_id) {
		global $_quotas_elements_;
		global $force_lend;
		global $msg;
		global $charset;
		
		$this->get_values();
		$this->get_elements_values($elements_id);
	
		$ids=$this->get_table_ids_from_elements_id_ordered($elements_id);
		
		//Pour chaque id on récupère les identifiants et les labels du champ concerné
		//Stockage dans $elements
		for ($i=0; $i<count($ids); $i++) {
			$_quota_element_=$_quotas_elements_[$this->get_element_by_id($ids[$i])];
			
			$requete="select ".$_quota_element_["FIELD"].", ".$_quota_element_["LABEL"]." from ".$_quota_element_["TABLE"];
			$resultat=mysql_query($requete);
			while ($r=mysql_fetch_array($resultat)) {
				$t=array();
				$t["ID"]=$r[$_quota_element_["FIELD"]];
				$t["LABEL"]=$r[$_quota_element_["LABEL"]];
				$elements[$ids[$i]][]=$t;
			}
		}
		//Affichage
		//Fonctions de vérification des bornes
		if ($this->quota_type["MIN"]) {
			$ta.="<script>
				function check_min(variable,min_value) {
					if (eval('document.forms[0].'+variable+'.value')<min_value) {
						alert('".addslashes($msg["quotas_lt_min"])." '+min_value);
						return false;
					}
					return true;
				}
			</script>\n";
		}
		if ($this->quota_type["MAX"]) {
			$ta.="<script>
				function check_max(variable,max_value) {
					if (eval('document.forms[0].'+variable+'.value')>max_value) {
						alert('".addslashes($msg["quotas_gt_max"])." '+max_value);
						return false;
					}
					return true;
				}
			</script>\n";
		}
		$ta.="<script>
			function check_nan(e) {
				if(isNaN(e.value)) { 
					alert('".addslashes($msg["quotas_nan"])."');
					return false;
				}
				return true;
			}
		</script>\n";
		$ta.="<table>\n";
		$ta.="<tr>";
		for ($i=0; $i<count($ids); $i++) {
			$ta.="<th>".$_quotas_elements_[$this->get_element_by_id($ids[$i])]["COMMENT"]."</th>";
		}
		
		$force="";
		if ($this->quota_type["FORCELEND"]) {
			if ($force_lend)
				//Si le forçage est autorisé en règle générale, en particulier il faut proposer l'interdiction 
				$force=$msg["quotas_dont_lend_element"];
			else
				//Si le forçage n'est pas autorisé en règle générale, il faut proposer l'autorisation de le faire
				$force=$msg["quotas_force_lend_element"];
		}	
		
		
		$ta.="<th>".$this->quota_type["SHORT_COMMENT"]."</th>";
		if ($force) $ta.="<th>".htmlentities($force,ENT_QUOTES,$charset)."</th>";
		$ta.="</tr>\n";
		//Affichage récursif de la liste des quotas
		$this->show_quota_list(0,"","",$ids,$elements,$ta);
		$ta.="</table>\n";
		return $ta;
	}
	
	//Enregistrement récursif des quotas saisis
	function rec_quota_list($level,$prefixe_varname,$ids,$elements,$elements_id) {
		//Forçage du prêt ?
		if ($this->quota_type["FORCELEND"]) $force_lend=1;
		//Pour tous les éléments
		for ($i=0; $i<count($elements[$ids[$level]]); $i++) {
			$prefixe_varname_=$prefixe_varname."_".$elements[$ids[$level]][$i]["ID"];
			//Si on n'est pas au dernier niveau alors appel récursif
			if ($level<(count($ids)-1)) {
				$this->rec_quota_list($level+1,$prefixe_varname_,$ids,$elements,$elements_id);
			} else {
				//Si l'on est au dernier niveau
				$values_ids=explode("_",substr($prefixe_varname_,1));
				$val="val".$prefixe_varname_;
				if ($force_lend) {
					$forc="forc".$prefixe_varname_;
					global $$forc;
				}
				global $$val;
				//Si il y a une valeur de quota saisie, construction de la requête d'enregistrement
				if ($$val!=="") {
					$constraint="Q:";
					$quota=array();
					for ($j=0; $j<count($ids); $j++) {
						$quota[]=$ids[$j]."=".$values_ids[$j];
					}
					$constraint.=implode(",",$quota);
					$requete="insert into ".$this->table." (quota_type,constraint_type,elements,value) values(".$this->quota_type["ID"].",'".$constraint."',$elements_id,".$$val.")";
					mysql_query($requete);
				}
				//Si le forçage est coché, construction de la requête d'enregistrement
				if (($$forc)&&($force_lend)) {
					$constraint="F:";
					$quota=array();
					for ($j=0; $j<count($ids); $j++) {
						$quota[]=$ids[$j]."=".$values_ids[$j];
					}
					$constraint.=implode(",",$quota);
					$requete="insert into ".$this->table." (quota_type,constraint_type,elements,value) values(".$this->quota_type["ID"].",'".$constraint."',$elements_id,".$$forc.")";
					mysql_query($requete);
				}
			}
		}
	}
	
	//Enregistrement des quotas après soumission du tableau
	function rec_quota($elements_id) {
		global $first;
		global $ids_order;
		global $_quotas_elements_;
		//Si formulaire soumis
		if ($first) {
			//Suppression des quotas dans la table
			$requete="delete from ".$this->table." where quota_type=".$this->quota_type["ID"]." and (constraint_type like 'Q:%' or constraint_type like 'F:%') and elements=".$elements_id;
			mysql_query($requete);
			
			//Recherche des combinaisons possibles et enregistrement
			//Liste des ids
			$ids=explode(",",$ids_order);
			
			//Pour chaque id on récupère l'identifiant et le label
			for ($i=0; $i<count($ids); $i++) {
				$_quota_element_=$_quotas_elements_[$this->get_element_by_id($ids[$i])];
				$requete="select ".$_quota_element_["FIELD"].", ".$_quota_element_["LABEL"]." from ".$_quota_element_["TABLE"];
				$resultat=mysql_query($requete);
				while ($r=mysql_fetch_array($resultat)) {
					$t=array();
					$t["ID"]=$r[$_quota_element_["FIELD"]];
					$t["LABEL"]=$r[$_quota_element_["LABEL"]];
					$elements[$ids[$i]][]=$t;
				}
			}
			
			//Enregistrement récursif
			$this->rec_quota_list(0,"",$ids,$elements,$elements_id);
		}
	}
	
	function apply_conflict($q) {
		global $conflict_value, $default_value, $conflict_list;
		if(!$q)return;
		$this->get_values();
		$test=true;
		$nb_quotas=0;
		$total_quotas=0;
		$total_id_quotas=0;
		$max_quota="";
		$max_quota_id=0;
		$min_quota="";
		$min_quota_id=0;
		$r=array();
		$r_error=array("ID"=>"","VALUE"=>-1);
		reset($q);
		//Test valeurs toutes vides et calcul du min, du max et du total
		while (list($key,$val)=each($q)) {
			$test&=($val==="");
			if ($val!=="") {
				if ($max_quota==="") {
					$max_quota=$val;
					$max_quota_id=$key;
				} else
					if ($val>$max_quota) {
						$max_quota=$val;
						$max_quota_id=$key;
					}
				if ($min_quota==="") {
					$min_quota=$val;
					$min_quota_id=$key;
				} else
					if ($val<$min_quota) {
						$min_quota=$val;
						$min_quota_id=$key;
					}
				$nb_quotas++;
				$total_quotas+=$val;
				$total_id_quotas+=$key;
			}
		}
		//Si les valeurs sont toutes vides et qu'il y a une valeur par défaut alors on la renvoie
		if (($test)&&($default_value)) {
			$r["ID"]=""; 
			$r["VALUE"]=$default_value;
			return $r;
		}
		//Si les valeurs sont toutes vides et qu'il n'y a pas de valeur par défaut alors erreur
		if (($test)&&(!$default_value)) return $r_error;
		//Si il n'y a qu'un quota possible, on le renvoie
		if ($nb_quotas==1) {
			$r["ID"]=$total_id_quotas;
			$r["VALUE"]=$total_quotas;
			return $r;
		}
		//Sinon, on applique les règles de priorité
		switch ($conflict_value) {
			case 1:
				//Le maximum
				$r["ID"]=$max_quota_id;
				$r["VALUE"]=$max_quota;
				return $r;
				break;
			case 2:
				//Le minimum
				$r["ID"]=$min_quota_id;
				$r["VALUE"]=$min_quota;
				return $r;
				break;
			case 3:
				//La valeur par défaut
				if ($default_value) {
					$r["ID"]="";
					$r["VALUE"]=$default_value;
					return $r;
				} else return $r_error;
				break;
			case 4:
				//Dans l'ordre
				for ($i=0; $i<count($conflict_list); $i++) {
					if ($q[$conflict_list[$i]]!=="") {
						$r["ID"]=$conflict_list[$i];
						$r["VALUE"]=$q[$conflict_list[$i]];
						return $r;
					}
				}
				break;
			default:
				//Si plantage général, retourne erreur !!
				return $r_error;
		}
	}
	
	//Récupération de la valeur de forçage d'un quota par son id
	function get_force_value_by_id($struct,$element_id) {
		global $_quotas_elements_;
		
		//vérification des paramètres
		for ($i=0; $i<count($this->quota_type["QUOTAS"]); $i++) {
			$on=explode(",",$this->quota_type["QUOTAS"][$i]);
			for ($j=0; $j<count($on); $j++) {
				$s[$on[$j]]=$this->get_element_by_name($on[$j]);
			}
		}
		reset($s);
		$flag=true;
		$values=array();
		while (list($key,$val)=each($s)) {
			$_quota_element_=$_quotas_elements_[$val];
			if ($struct[$_quota_element_["LINKEDTO"]]=="") {
				$flag=false;
				break;
			} else {
				$requete="select ".$_quota_element_["LINKEDFIELD"]." from ".$_quota_element_["TABLELINKED"];
				$requete.=" where ".$_quota_element_["LINKEDID"]."='".$struct[$_quota_element_["LINKEDTO"]]."'";
				$resultat=mysql_query($requete);
				$values[$_quota_element_["ID"]]=@mysql_result($resultat,0,0);
			}
		}
		if ($flag) {
			//Pour le quota, récupération des valeurs
			$elements_id=$element_id;
			$ids=$this->get_table_ids_from_elements_id($elements_id);
			//Construction de la requête
			$from="";
			$where="";
			for ($j=0; $j<count($ids); $j++) {
				if ($from) $from.=",";
				$from.=$this->table." as q$j";
				$fregexp="F:".$ids[$j]."=".$values[$ids[$j]]."$|F:".$ids[$j]."=".$values[$ids[$j]].",|F:.*,".$ids[$j]."=".$values[$ids[$j]]."$|F:.*,".$ids[$j]."=".$values[$ids[$j]].",";
				if ($where) {
					$where.=" and q$j.constraint_type regexp '".$fregexp."' and q$j.quota_type=q0.quota_type and q$j.constraint_type=q0.constraint_type and q$j.elements=q0.elements";
				} else {
					$where="q0.constraint_type regexp '".$fregexp."' and q0.quota_type=".$this->quota_type["ID"]." and q0.elements=".$elements_id;
				}
			}
			$requete="select q0.value from ".$from." where ".$where;
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat))
				return mysql_result($resultat,0,0);
			else
				return 0;
		} else return 0;
	}
	
	//Récupération de la valeur d'un quota par son id
	function get_quota_value_by_id($struct,$element_id) {
		global $_quotas_elements_;
		
		//vérification des paramètres
		for ($i=0; $i<count($this->quota_type["QUOTAS"]); $i++) {
			$on=explode(",",$this->quota_type["QUOTAS"][$i]);
			for ($j=0; $j<count($on); $j++) {
				$s[$on[$j]]=$this->get_element_by_name($on[$j]);
			}
		}
		reset($s);
		$flag=true;
		$values=array();
		while (list($key,$val)=each($s)) {
			$_quota_element_=$_quotas_elements_[$val];
			if ($struct[$_quota_element_["LINKEDTO"]]=="") {
				$flag=false;
				break;
			} else {
				$requete="select ".$_quota_element_["LINKEDFIELD"]." from ".$_quota_element_["TABLELINKED"];
				$requete.=" where ".$_quota_element_["LINKEDID"]."='".$struct[$_quota_element_["LINKEDTO"]]."'";
				$resultat=mysql_query($requete);
				$values[$_quota_element_["ID"]]=@mysql_result($resultat,0,0);
			}
		}
		if ($flag) {
			//Pour le quota, récupération des valeurs
			$elements_id=$element_id;
			$ids=$this->get_table_ids_from_elements_id($elements_id);
			//Construction de la requête
			$from="";
			$where="";
			for ($j=0; $j<count($ids); $j++) {
				if ($from) $from.=",";
				$from.=$this->table." as q$j";
				$qregexp="Q:".$ids[$j]."=".$values[$ids[$j]]."$|Q:".$ids[$j]."=".$values[$ids[$j]].",|Q:.*,".$ids[$j]."=".$values[$ids[$j]]."$|Q:.*,".$ids[$j]."=".$values[$ids[$j]].",";
				if ($where) {
					$where.=" and q$j.constraint_type regexp '".$qregexp."' and q$j.quota_type=q0.quota_type and q$j.constraint_type=q0.constraint_type and q$j.elements=q0.elements";
				} else {
					$where="q0.constraint_type regexp '".$qregexp."' and q0.quota_type=".$this->quota_type["ID"]." and q0.elements=".$elements_id;
				}
			}
			$requete="select q0.value from ".$from." where ".$where;
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat))
				return mysql_result($resultat,0,0);
			else
				return -1;
		} else return -1;
	}
	
	function get_object_for_indirect_element($_quota_element_,$struct) {
		$flag=true;
		//Je prends tous les éléments que je trouve et je recherche récursivement le plus défavorale
		$requete="select distinct ".$_quota_element_["LINKEDFIELD"]." from ".$_quota_element_["TABLELINKED"];
		$requete.=" where ".$_quota_element_["LINKEDID_BY"]."='".$struct[$_quota_element_["LINKEDTO"]]."'";
		$resultat=@mysql_query($requete);
		if (!mysql_num_rows($resultat)) {
			$flag=false;
		} else {
			$min_quota=-1;
			$min_quota_with_id=array();
			$no_limit=0;
			$no_limit_with_id=array();
			while ($r=mysql_fetch_array($resultat)) {
				$struct_=$struct;
				//Pour l'élément, je recherche le premier objet associé
				$requete="select ".$_quota_element_["LINKEDID"]." from ".$_quota_element_["TABLELINKED"]." where ".$_quota_element_["LINKEDID_BY"]."='".$struct[$_quota_element_["LINKEDTO"]]."' and ".$_quota_element_["LINKEDFIELD"]."='".$r[$_quota_element_["LINKEDFIELD"]]."' limit 1";
				$resultat_object=@mysql_query($requete);
				$struct_[$_quota_element_["LINKEDTO"]]=mysql_result($resultat_object,0,0);
				$quota_by=$this->get_quota_value_with_id($struct_,true);
				if ($quota_by["VALUE"]==-1) {
					$no_limit++;
					$no_limit_with_id=$quota_by;
					$no_limit_with_id["BY"]=$struct_[$_quota_element_["LINKEDTO"]];
				} else {
					if ($min_quota==-1) {
						$min_quota_with_id=$quota_by;
						$min_quota_with_id["BY"]=$struct_[$_quota_element_["LINKEDTO"]];
						$min_quota=$quota_by["VALUE"];
					} else {
						if ($quota_by["VALUE"]<$min_quota) {
							$min_quota_with_id=$quota_by;
							$min_quota=$quota_by["VALUE"];
							$min_quota_with_id["BY"]=$struct_[$_quota_element_["LINKEDTO"]];
						}
					}
				}
			}
			if ($min_quota==-1) $min_quota_with_id=$no_limit_with_id;
		}
		if (!$flag) return -1; else return $min_quota_with_id["BY"];
	}
	
	//Récupération de valeur d'un quota et de son id
	function get_quota_value_with_id($struct,$by=false) {
		global $_quotas_elements_;

		$s=array() ;
		
		//vérification des paramètres
		for ($i=0; $i<count($this->quota_type["QUOTAS"]); $i++) {
			$on=explode(",",$this->quota_type["QUOTAS"][$i]);
			for ($j=0; $j<count($on); $j++) {
				$s[$on[$j]]=$this->get_element_by_name($on[$j]);
			}
		}
		reset($s);
		$flag=true;
		$values=array();
		while (list($key,$val)=each($s)) {
			$_quota_element_=$_quotas_elements_[$val];
			if ($struct[$_quota_element_["LINKEDTO"]]=="") {
				$flag=false;
				break;
			} else {
				//Si c'est une recherche indirecte (tout ça pour faire plaisir à Eric !!)
				if (($_quota_element_["TABLELINKED_BY"])&&(!$by)) {
					$indirect=$this->get_object_for_indirect_element($_quota_element_,$struct);
					$flag_indirect=false;
					if ($indirect!=-1) {
						$flag_indirect=true;
						$struct[$_quota_element_["LINKEDID"]]=$indirect;
					} else {
						$flag=false;
						break;
					}
				}
				//Avec l'objet, je prends l'élément associé
				$requete="select ".$_quota_element_["LINKEDFIELD"]." from ".$_quota_element_["TABLELINKED"];
				$requete.=" where ".$_quota_element_["LINKEDID"]."='".$struct[$flag_indirect?$_quota_element_["LINKEDID"]:$_quota_element_["LINKEDTO"]]."'";
				$resultat=mysql_query($requete);
				$values[$_quota_element_["ID"]]=@mysql_result($resultat,0,0);
			}
		}
		if ($flag) {
			//Pour chaque quota, récupération des valeurs
			for ($i=0; $i<count($this->quota_type["QUOTAS"]); $i++) {
				$elements_id=$this->get_elements_id_by_names($this->quota_type["QUOTAS"][$i]);
				$ids=$this->get_table_ids_from_elements_id($elements_id);
				//Construction de la requête
				$from="";
				$where="";
				for ($j=0; $j<count($ids); $j++) {
					if ($from) $from.=",";
					$from.=$this->table." as q$j";
					$qregexp="Q:".$ids[$j]."=".$values[$ids[$j]]."$|Q:".$ids[$j]."=".$values[$ids[$j]].",|Q:.*,".$ids[$j]."=".$values[$ids[$j]]."$|Q:.*,".$ids[$j]."=".$values[$ids[$j]].",";
					if ($where) {
						$where.=" and q$j.constraint_type regexp '".$qregexp."' and q$j.quota_type=q0.quota_type and q$j.constraint_type=q0.constraint_type and q$j.elements=q0.elements";
					} else {
						$where="q0.constraint_type regexp '".$qregexp."' and q0.quota_type=".$this->quota_type["ID"]." and q0.elements=".$elements_id;
					}
				}
				$requete="select q0.value from ".$from." where ".$where;
				$resultat=mysql_query($requete);
				if (mysql_num_rows($resultat))
					$q[$elements_id]=mysql_result($resultat,0,0);
				else
					$q[$elements_id]="";
			}
			//Application des règles de priorité
			return $this->apply_conflict($q);
		} else return array("ID"=>"","VALUE"=>-1);
	}
	
	function get_quota_value($struct) {
		$r=$this->get_quota_value_with_id($struct);
		return $r["VALUE"];
	}
	
	function get_force_value($struct) {
		global $force_lend;
		$r=$this->get_quota_value_with_id($struct);
		$force=$force_lend;
		
	
		if (($this->quota_type["FORCELEND"])&&($r["ID"]))
		{
			$force^=$this->get_force_value_by_id($struct,$r["ID"]);
		}
		
		return $force;
			
	}
	
	function get_criterias_title_by_elements_id($struct,$id) {
		global $_quotas_elements_;
		global $msg;
		
		$title=array();
		
		$ids=$this->get_table_ids_from_elements_id_ordered($id);
		for ($i=0; $i<count($ids); $i++) {
			$element=$_quotas_elements_[$this->get_element_by_id($ids[$i])];
			if ($element["TABLE"]==$element["TABLELINKED"]) {
				$requete="select ".$element["LABEL"]." from ".$element["TABLE"]." where ".$element["FIELD"]."='".$struct[$element["LINKEDTO"]]."'";
			} else {
				$requete="select ".$element["LABEL"]." from ".$element["TABLE"].", ".$element["TABLELINKED"]." where ".$element["FIELD"]."=".$element["LINKEDFIELD"]." and ".$element["LINKEDID"]."='".$struct[$element["LINKEDTO"]]."'";
			}
			$resultat=mysql_query($requete);
			$title[]=@mysql_result($resultat,0,0);
		}
		return implode(" ".$msg["quotas_and"]." ",$title);
	}
	
	function check_quota($struct) {
		global $max_value,$max_quota,$force_lend;
		global $_quotas_elements_;
		global $msg;
		
		//Récupération du quota à partir des paramètres passés
		$r=$this->get_quota_value_with_id($struct);
	
		//Récupération de l'entité
		$_quota_element_entity_=$_quotas_elements_[$this->get_element_by_name($this->quota_type["ENTITY"])];
			
		//Le forçage est initialisé à la valeur des paramètres généraux
		$force=$force_lend;
		
		//Au départ, il n'y a pas d'erreur
		$error=0;
		
		//Compte du nombre total déjà présent
		$requete="select count(1) from ".$this->quota_type["COUNT_TABLE"]." where ".$this->quota_type["COUNT_FIELD"]."=".$struct[$_quota_element_entity_["LINKEDTO"]];
		if ($this->quota_type["COUNT_FILTER"]) $requete.=" and ".$this->quota_type["COUNT_FILTER"];

		$resultat=mysql_query($requete);
		$nb_total=@mysql_result($resultat,0,0);

		//!!A vérifier si valeur par défaut equiv quota le plus large !

		//Machine d'état
		$state="QUOTA_START";
		
		while ($state!="QUOTA_END") {
			switch ($state) {
				case "QUOTA_START":
					//Vérification que l'on a récupéré une contrainte : si oui -> vérification du nombre lié à cette contrainte (QUOTA_CHECK)
					//Si non, on vérifie juste que le nombre total ne dépassera pas le maximum possible (QUOTA_MAX)
					if ($r["VALUE"]==-1) $state="QUOTA_MAX"; else $state="QUOTA_CHECK";
					break;
				case "QUOTA_CHECK":
					//Est-ce que le quota est l'entité ou est indéterminé ?
					//Si entité ou vide --> On vérifie que le total comptabilisé ne dépasse pas ce quota (QUOTA_ENTITY_OR_EMPTY)
					//Si pas entité et pas vide --> On vérifie que le total partiel ne dépasse pas le quota (QUOTA_PARTIAL)
					if (($r["ID"]==$_quota_element_entity_["ID"])||($r["ID"]=="")) $state="QUOTA_ENTITY_OR_EMPTY"; else $state="QUOTA_PARTIAL";
					break;
				case "QUOTA_PARTIAL":
					//Vérification que le nombre total partiel ne dépasse pas le quota
					//Si nombre partiel dépasse --> erreur et fin (QUOTA_END)
					//Si nombre partiel ne dépasse pas --> Vérification que le nombre total ne dépassera pas le maximum autorisé (QUOTA_MAX)
					
					//calcul du nombre partiel
					$quota=$this->get_quotas_index_from_elements_id($r["ID"]);
					$elements=$this->quota_type["QUOTAS"][$quota];
					$countfields=$this->quota_type["COUNTFIELDS"][$quota];
					
					$elements_=explode(",",$elements);
					$countfields_=explode(",",$countfields);
					$from="";
					$where="";
					
					//Pour chaque élément
					$groupby_=array();
					$tablelinked=array();
					$count_distinct=array();
					for ($i=0; $i<count($elements_); $i++) {
						//Si ce n'est pas l'entité
						if ($elements_[$i]<>$this->quota_type["ENTITY"]) {
							//Réqupération de l'élément par son nom
							$element=$_quotas_elements_[$this->get_element_by_name($elements_[$i])];
							//Lien avec la table de l'objet (ex : exemplaire)
							//La table a-t-elle déjà été citée dans la requete ?
							if (!$tablelinked[$element["TABLELINKED"]]) {
								$from.=", ".$element["TABLELINKED"];
								$tablelinked[$element["TABLELINKED"]]=true;
								$already_present=false;
							} else $already_present=true;
							//Si c'est un object indirect (ex : notice passée pour le type d'exemplaire) alors on va chercher l'objet ayant le quota le plus défavorable
							if ($element["TABLELINKED_BY"]) {
								$indirect=$this->get_object_for_indirect_element($element,$struct);
								if ($indirect!=-1)
									$struct[$element["LINKEDTO"]]=$indirect;
								else
									$struct[$element["LINKEDTO"]]=0;
							}
							//Récupération de l'id de l'élément à partir de ce qui est transmis dans struct
							$requete="select ".$element["LINKEDFIELD"]." from ".$element["TABLELINKED"]." where ".$element["LINKEDID"]."='".$struct[$element["LINKEDTO"]]."'";
							$resultat=mysql_query($requete);
							$linkedid=@mysql_result($resultat,0,0);
							if ($element["TABLELINKED_BY"]) {
								if (!$already_present) {
									$where.=" and ".$countfields_[$i]."=".$element["LINKEDID_BY"];
									$count_distinct[]=$countfields_[$i];
								}
								$where.=" and ".$element["LINKEDFIELD"]."='".$linkedid."'";
								$groupby_[]=$countfields_[$i];
							} else {
								if (!$already_present) {
									$where.=" and ".$countfields_[$i]."=".$element["LINKEDID"];
									$count_distinct[]=$countfields_[$i];
								}
								$where.=" and ".$element["LINKEDFIELD"]."='".$linkedid."'";
							}
						}
					}
					$group_by=implode(",",$groupby_);
					$distinct=count($count_distinct)?"distinct ".implode(",",$count_distinct):"1";
					$requete="select count(".$distinct.") from ".$this->quota_type["COUNT_TABLE"].$from." where ".$this->quota_type["COUNT_FIELD"]."='".$struct[$_quota_element_entity_["LINKEDTO"]]."'".$where;
					if ($this->quota_type["COUNT_FILTER"]) $requete.=" and ".$this->quota_type["COUNT_FILTER"];
					if ($group_by) $requete.=" group by ".$group_by;
					$resultat=mysql_query($requete);
					$nb_partial=@mysql_result($resultat,0,0);
					//Si nombre partiel+1>quota alors non !!
					if ($nb_partial+1>$r["VALUE"]) {
						$this->error_message=sprintf($this->quota_type["PARTIAL_ERROR_MESSAGE"],$this->get_criterias_title_by_elements_id($struct,$r["ID"]),$r["VALUE"]);
						if ($this->quota_type["FORCELEND"]) {
							$force^=$this->get_force_value_by_id($struct,$r["ID"]);
						}
						$this->force=$force;
						$error=-1;
						$state="QUOTA_END"; 
					} else {
						$error=0;
						$state="QUOTA_MAX";
					}
					break;	
				case "QUOTA_ENTITY_OR_EMPTY":
					//Vérification que le nombre total ne dépasse pas le quota
					//Si dépassement --> erreur et fin (QUOTA_END)
					//Si pas dépassement --> Vérification que le nombre total de dépassera pas le maximum autorisé (QUOTA_MAX)
				
					//Si nombre total+1>quota alors non !!
					if ($nb_total+1>$r["VALUE"]) {
						if ($r["ID"]) {
							$this->error_message=sprintf($this->quota_type["PARTIAL_ERROR_MESSAGE"],$this->get_criterias_title_by_elements_id($struct,$r["ID"]),$r["VALUE"]);
							if ($this->quota_type["FORCELEND"])
								$force^=$this->get_force_value_by_id($struct,$r["ID"]);
						}
						else
							$this->error_message=sprintf($this->quota_type["DEFAULT_ERROR_MESSAGE"],$r["VALUE"]);
						$this->force=$force;
						$error=-1;
						$state="QUOTA_END";
					} else {
						$error=0;
						$state="QUOTA_MAX";
					}
					break; 
				case "QUOTA_MAX":
					//Vérification que le nombre total ne dépassera pas le maximum autorisé
					//Si dépassement : erreur
					//Dans tous les cas, on termine (QUOTA_END)
					//Récupération du max
					if ($this->quota_type["MAX"]) {
						$max_=-1;
						$max_message="";
						$partial=0;
						if ($max_quota) {
							$max_=$this->get_quota_value_by_id($struct,$_quota_element_entity_["ID"]);
						}
						if ($max_==-1)
							$max_=$max_value;
						else {
							$partial=1;
							$max_message=$msg["quotas_by"]." ".$this->get_criterias_title_by_elements_id($struct,$_quota_element_entity_["ID"]);
						}
					}
					//Si maximum trouvé et total+1>max_ alors non !!
					if (($max_!=-1)&&($max_!=0)) {
						if ($nb_total+1>$max_) {
							$this->error_message=sprintf($this->quota_type["MAX_ERROR_MESSAGE"],$max_message,$max_);
							if (($this->quota_type["FORCELEND"])&&($partial)) {
								$force^=$this->get_force_value_by_id($struct,$_quota_element_entity_["ID"]);
							}
							$this->force=$force;
							$error=-1;
						} else $error=0;
					} else $error=0;
					$state="QUOTA_END";
					break;
				default:
					return $error;
					break;
			}
		}
		//QUOTA_END atteint : Fin de la vérification : on renvoi la valeur $error,le message d'erreur si $error=-1 est stocké dans $this->error_message
		//forçage ou non dans $this->force
		return $error;
	}
	
	//Constructeur
    function quota($type_id="",$descriptor="") {
    	global $_parsed_quotas_;
    	global $lang;
    	global $include_path;
		global $_quotas_table_;
    
    	if ($descriptor=="") $this->descriptor=$include_path."/quotas/$lang.xml"; else $this->descriptor=$descriptor;
    
    	if (!$_parsed_quotas_[$this->descriptor]) $this->parse_quotas(); else $this->table=$_quotas_table_;
    	
    	if ($type_id!="") {
    		$this->type_id=$type_id;
    		$this->quota_type=$this->get_quota_type_by_id($type_id);
    		if (count($this->quota_type)==0) $this->quota_type=$this->get_quota_type_by_name($type_id);
    	}
    }
}

?>