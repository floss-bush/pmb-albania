<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: connecteurs.class.php,v 1.7 2008-09-06 13:43:18 gueluneau Exp $

//if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/parser.inc.php");

class connector {
	var $repository;				//Est-ce un entrepot ?
	var $timeout;					//Time-out
	var $retry;						//Nombre de réessais
	var $ttl;						//Time to live
	var $parameters;				//Paramètres propres au connecteur
	var $sources;					//Sources disponibles
	var $msg;						//Messages propres au connecteur
	var $connector_path;
	
	function connector($connector_path="") {
		$this->fetch_global_properties();
		$this->get_messages($connector_path);
		$this->connector_path=$connector_path;
	}
	
	//Signature de la classe
	function get_id() {
		return "";
	}
	
	//Est-ce un entrepot ?
	function is_repository() {
		return 0;
	}
	
	function get_messages($connector_path) {
		global $lang;
		
		if (file_exists($connector_path."/messages/".$lang.".xml")) {
			$file_name=$connector_path."/messages/".$lang.".xml";
		} else if (file_exists($connector_path."/messages/fr_FR.xml")) {
			$file_name=$connector_path."/messages/fr_FR.xml";
		}
		if ($file_name) {
			$xmllist=new XMLlist($file_name);
			$xmllist->analyser();
			$this->msg=$xmllist->table;
		}
	}
	
	//Récupération de la liste des sources
	function get_sources() {
		$sources=array();
		$requete="select * from connectors_sources where id_connector='".addslashes($this->get_id())."' and opac_allowed=1";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			while ($r=mysql_fetch_object($resultat)) {
				$s["SOURCE_ID"]=$r->source_id;
				$s["PARAMETERS"]=$r->parameters;
				$s["NAME"]=$r->name;
				$s["COMMENT"]=$r->comment;
				$s["RETRY"]=$r->retry;
				$s["REPOSITORY"]=$r->repository;
				$s["TTL"]=$r->ttl;
				$s["TIMEOUT"]=$r->timeout;
				$s["OPAC_ALLOWED"]=$r->opac_allowed;
				$sources[$r->source_id]=$s;
			}
		}
		$this->sources=$sources;
		return $sources;
	}
	
	//Récupération des paramètres d'une source
	function get_source_params($source_id) {
		if ($source_id) {
			$requete="select * from connectors_sources where id_connector='".addslashes($this->get_id())."' and source_id=".$source_id."  and opac_allowed=1";
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$r=mysql_fetch_object($resultat);
				$s["SOURCE_ID"]=$r->source_id;
				$s["PARAMETERS"]=$r->parameters;
				$s["NAME"]=$r->name;
				$s["COMMENT"]=$r->comment;
				$s["RETRY"]=$r->retry;
				$s["REPOSITORY"]=$r->repository;
				$s["TTL"]=$r->ttl;
				$s["TIMEOUT"]=$r->timeout;
				$s["OPAC_ALLOWED"]=$r->opac_allowed;
			} 
		} else {
			$s["SOURCE_ID"]="";
			$s["PARAMETERS"]="";
			$s["NAME"]="Nouvelle source";
			$s["COMMENT"]="";
			$s["RETRY"]=$this->retry;
			$s["REPOSITORY"]=$this->repository;
			$s["TTL"]=$this->ttl;
			$s["TIMEOUT"]=$this->timeout;
			$s["OPAC_ALLOWED"]=0;
		}
		return $s;
	}
	
	//Formulaire des propriétés d'une source
	function source_get_property_form($source_id) {
		$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]	
		} else {
			//Affichage du formulaire vide
		}
	}
	
	function make_serialized_source_properties($source_id) {
		$this->sources[$source_id]["PARAMETERS"]="";
	}
	
	//Formulaire de sauvegarde des propriétés d'une source
	function source_save_property_form($source_id) {
		$this->make_serialized_source_properties($source_id);
		$requete="replace into connectors_sources (source_id,id_connector,parameters,comment,name,repository,retry,ttl,timeout,opac_allowed) values('".$source_id."','".addslashes($this->get_id())."','".addslashes($this->sources[$source_id]["PARAMETERS"])."','".addslashes($this->sources[$source_id]["COMMENT"])."','".addslashes($this->sources[$source_id]["NAME"])."','".addslashes($this->sources[$source_id]["REPOSITORY"])."','".addslashes($this->sources[$source_id]["RETRY"])."','".addslashes($this->sources[$source_id]["TTL"])."','".addslashes($this->sources[$source_id]["TIMEOUT"])."','".addslashes($this->sources[$source_id]["OPAC_ALLOWED"])."')";
		return mysql_query($requete);
	}
	
	//Suppression d'une source
	function del_source($source_id) {
		$requete="delete from connectors_sources where source_id=$source_id and id_connector='".addslashes($this->get_id())."'";
		return mysql_query($requete);
	}
	
	//Récupération  des proriétés globales par défaut du connecteur (timeout, retry, repository, parameters)
	function fetch_default_global_values() {
		$this->timeout=5;
		$this->repository=2;
		$this->retry=3;
		$this->ttl=1800;
		$this->parameters="";
	}
	
	//Récupération  des proriétés globales du connecteur (timeout, retry, repository, parameters)
	function fetch_global_properties() {
		$requete="select * from connectors where connector_id='".addslashes($this->get_id())."'";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$r=mysql_fetch_object($resultat);
			$this->repository=$r->repository;
			$this->timeout=$r->timeout;
			$this->retry=$r->retry;
			$this->ttl=$r->ttl;
			$this->parameters=$r->parameters;
		} else {
			$this->fetch_default_global_values();
		}
	}
	
	//Formulaire des propriétés générales
	function get_property_form() {
		$this->fetch_global_properties();
		//Affichage du formulaire en fonction de $this->parameters
		if ($this->parameters) {
		} else {
			//Affichage du formulaire vide
		}	
	}
	
	function make_serialized_properties() {
		//Mise en forme des paramètres à partir de variables globales (mettre le résultat dans $this->parameters)
	}
	
	//Sauvegarde des propriétés générales
	function save_property_form() {
		$this->make_serialized_properties();
		$requete="replace into connectors (connector_id,parameters, retry, timeout, ttl, repository) values('".addslashes($this->get_id())."',
		'".addslashes($this->parameters)."','".$this->retry."','".$this->timeout."','".$this->ttl."','".$this->repository."')";
		return mysql_query($requete);
	}
	
	//Supression des notices dans l'entrepot !
	function del_notices($source_id) {
		$requete="select * from source_sync where source_id=".$source_id;
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$r=mysql_fetch_object($resultat);
			if (!$r->cancel) return false;
		}
		mysql_query("TRUNCATE TABLE entrepot_source_".$source_id);
		mysql_query("delete from source_sync where source_id=".$source_id);
		return true;
	}
	
	//Annulation de la mise à jour (faux = synchro conservée dans la table, vrai = synchro supprimée dans la table)
	function cancel_maj($source_id) {
		return false;
	}
	
	//Annulation de la mise à jour (faux = synchro conservée dans la table, vrai = synchro supprimée dans la table)
	function break_maj($source_id) {
		return false;
	}
	
	//M.A.J. Entrepôt lié à une source
	function maj_entrepot($source_id,$callback_progress="",$recover=false,$recover_env="") {
	}
	
	//Export d'une notice en UNIMARC
	function to_unimarc($notice) {
	}
	
	//Export d'une notice en Dublin Core (c'est le minimum)
	function to_dublin_core($notice) {
	}
	
	//Fonction de recherche
	function search($source_id,$query,$search_id) {
	}
	
	//Recherche d'une page de résultat
	function get_page_result($search_id,$page, $n_per_page) {
	}
	
	//Nombre de résultats d'une recherche
	function get_n_results($search_id) {
	}
	
	//Récupération de la valeur d'une autorité
	function get_values_from_id($id,$ufield) {
		$r="";
		switch ($ufield) {
			//Categorie
			case "60X":
				$requete="select libelle_categorie from categories where num_noeud=".$id;
				$r_cat=mysql_query($requete);
				if (@mysql_num_rows($r_cat)) {
					$r=mysql_result($r_cat,0,0);
				}
				break;
			//Dewey
			case "676\$a686\$a":
				$requete="select indexint_name from indexint where indexint_id=".$id;
				$r_indexint=mysql_query($requete);
				if (@mysql_num_rows($r_indexint)) {
					$r=mysql_result($r_indexint,0,0);
				}
				break;
			//Editeur
			case "210\$c":
				$requete="select ed_name from publishers where ed_id=".$id;
				$r_pub=mysql_query($requete);
				if (@mysql_num_rows($r_pub)) {
					$r=mysql_result($r_pub,0,0);
				}
				break;
			//Collection
			case "225\$a410\$t":
				$requete="select collection_name from collections where collection_id=".$id;
				$r_coll=mysql_query($requete);
				if (@mysql_num_rows($r_coll)) {
					$r=mysql_result($r_coll,0,0);
				}
				break;
			//Sous collection
			case "225\$i411\$t":
				$requete="select sub_coll_name from sub_collections where sub_coll_id=".$id;
				$r_subcoll=mysql_query($requete);
				if (@mysql_num_rows($r_subcoll)) {
					$r=mysql_result($r_subcoll,0,0);
				}
				break;
			//Auteur
			case "7XX":
				$requete="select concat(author_name,', ',author_rejete) from authors where author_id=".$id;
				$r_author=mysql_query($requete);
				if (@mysql_num_rows($r_author)) {
					$r=mysql_result($r_author,0,0);
				}
				break;
		}
		return $r;
	}
	
	function get_unimarc_search_fields() {
    	$fields=array();
    	//Calcul de la liste des champs disponibles
		$sc=new search(false,"search_fields_unimarc");
		$lf=$sc->get_unimarc_fields();
		$sc=new search(false,"search_simple_fields_unimarc");
		$lfs=$sc->get_unimarc_fields();
		//On fusionne les deux listes
		foreach($lf as $ufield=>$values) {
			if (substr($ufield,0,3)=="id:") {
				$ufield=substr($ufield,3);
			}
			$fields[$ufield]["TITLE"]=$values["TITLE"];
			foreach($values["OPERATORS"] as $op=>$top) {
				$fields[$ufield]["OPERATORS"][$op]=$top;
			}
		}
		foreach($lfs as $ufield=>$values) {
			if (substr($ufield,0,3)=="id:") {
				$ufield=substr($ufield,3);
			}
			if (!$fields[$ufield]["TITLE"])
				$fields[$ufield]["TITLE"]=$values["TITLE"];
			else {
				foreach($values["TITLE"] as $key=>$title) {
					if (array_search($title,$fields[$ufield]["TITLE"])===false) {
						$fields[$ufield]["TITLE"][]=$title;
					}
				}
			}
			foreach($values["OPERATORS"] as $op=>$top) {
				$fields[$ufield]["OPERATORS"][$op]=$top;
			}
		}
		return $fields;
    }
} 

class connecteurs {
	
	var $catalog=array();			//Liste des connecteurs déclarés
	
	//Constructeur
	function connecteurs() {
		global $base_path;
		if (file_exists($base_path."/admin/connecteurs/in/catalog_subst.xml")) 
			$catalog=$base_path."/admin/connecteurs/in/catalog_subst.xml";
		else
			$catalog=$base_path."/admin/connecteurs/in/catalog.xml";
		$this->parse_catalog($catalog);
	}
	
	function get_class_name($source_id) {
		$connector_id="";
		$requete="select id_connector from connectors_sources where source_id=".$source_id;
		$resultat=mysql_query($requete);
		if (@mysql_num_rows($resultat)) {
			$connector_id=mysql_result($resultat,0,0);
		}
		return $connector_id;
	}
	
	function parse_catalog($catalog) {
		global $base_path,$lang;
		//Construction du tableau des connecteurs disponbibles
		$xml=file_get_contents($catalog);
		$param=_parser_text_no_function_($xml,"CATALOG");
		for ($i=0; $i<count($param["ITEM"]); $i++) {
			$item=$param["ITEM"][$i];
			$t=array();
			$t["PATH"]=$item["PATH"];
			//Parse du manifest du connecteur!
			$xml_manifest=file_get_contents($base_path."/admin/connecteurs/in/".$item["PATH"]."/manifest.xml");
			$manifest=_parser_text_no_function_($xml_manifest,"MANIFEST");
			$t["NAME"]=$manifest["NAME"][0]["value"];
			$t["AUTHOR"]=$manifest["AUTHOR"][0]["value"];
			$t["ORG"]=$manifest["ORG"][0]["value"];
			$t["DATE"]=$manifest["DATE"][0]["value"];
			$t["STATUS"]=$manifest["STATUS"][0]["value"];
			$t["URL"]=$manifest["URL"][0]["value"];
			$t["REPOSITORY"]=$manifest["REPOSITORY"][0]["value"];
			//Commentaires
			$comment=array();
			for ($j=0; $j<count($manifest["COMMENT"]); $j++) {
				if ($manifest["COMMENT"][$j]["lang"]==$lang) { 
					$comment=$manifest["COMMENT"][$j]["value"];
					break;
				} else if (!$manifest["COMMENT"][$j]["lang"]) {
					$c_default=$manifest["COMMENT"][$j]["value"];	
				}
			}
			if ($j==count($manifest["COMMENT"])) $comment=$c_default;
			$t["COMMENT"]=$comment;
			$this->catalog[$item["ID"]]=$t;
		}
	}
}
?>
