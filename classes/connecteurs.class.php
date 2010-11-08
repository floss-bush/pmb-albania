<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: connecteurs.class.php,v 1.19 2009-06-16 12:52:28 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/parser.inc.php");
require_once($include_path."/templates/connecteurs.tpl.php");

class connector {
	var $repository;				//Est-ce un entrepot ?
	var $timeout;					//Time-out
	var $retry;						//Nombre de rï¿½essais
	var $ttl;						//Time to live
	var $parameters;				//Paramï¿½tres propres au connecteur
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
	
	//Rï¿½cupï¿½ration de la liste des sources
	function get_sources() {
		$sources=array();
		$requete="SELECT connectors_sources.*, source_sync.cancel, source_sync.percent, source_sync.date_sync FROM connectors_sources LEFT JOIN source_sync ON ( connectors_sources.source_id = source_sync.source_id ) where id_connector='".addslashes($this->get_id())."'";
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
				$s["CANCELLED"]=$r->cancel;
				$s["PERCENT"]=$r->percent;
				$s["DATESYNC"]=$r->date_sync;
				$s["LASTSYNCDATE"]=$r->last_sync_date;
				$sources[$r->source_id]=$s;
			}
		}
		$this->sources=$sources;
		return $sources;
	}
	
	//Rï¿½cupï¿½ration des paramï¿½tres d'une source
	function get_source_params($source_id) {
		if ($source_id) {
			$requete="select * from connectors_sources where id_connector='".addslashes($this->get_id())."' and source_id=".$source_id."";
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
	
	//Formulaire des propriï¿½tï¿½s d'une source
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
	
	//Formulaire de sauvegarde des propriï¿½tï¿½s d'une source
	function source_save_property_form($source_id) {
		global $source_categories;
		$this->make_serialized_source_properties($source_id);

		$this->sources[$source_id]["OPAC_ALLOWED"] = $this->sources[$source_id]["OPAC_ALLOWED"] ? 1 : 0;
		$requete="replace into connectors_sources (source_id,id_connector,parameters,comment,name,repository,retry,ttl,timeout,opac_allowed) values('".$source_id."','".addslashes($this->get_id())."','".addslashes($this->sources[$source_id]["PARAMETERS"])."','".addslashes($this->sources[$source_id]["COMMENT"])."','".addslashes($this->sources[$source_id]["NAME"])."','".addslashes($this->sources[$source_id]["REPOSITORY"])."','".addslashes($this->sources[$source_id]["RETRY"])."','".addslashes($this->sources[$source_id]["TTL"])."','".addslashes($this->sources[$source_id]["TIMEOUT"])."','".addslashes($this->sources[$source_id]["OPAC_ALLOWED"])."')";
		$result = mysql_query($requete);
		if (!$source_id) $source_id = mysql_insert_id(); 

		$table_entrepot_sql = "CREATE TABLE IF NOT EXISTS `entrepot_source_".$source_id."` (
							  `connector_id` varchar(20) NOT NULL default '',
							  `source_id` int(11) unsigned NOT NULL default '0',
							  `ref` varchar(220) NOT NULL default '',
							  `date_import` datetime NOT NULL default '0000-00-00 00:00:00',
							  `ufield` char(3) NOT NULL default '',
							  `usubfield` char(1) NOT NULL default '',
							  `field_order` int(10) unsigned NOT NULL default '0',
							  `subfield_order` int(10) unsigned NOT NULL default '0',
							  `value` text NOT NULL,
							  `i_value` text NOT NULL,
							  `recid` bigint(20) unsigned NOT NULL default '0',
							  `search_id` varchar(32) NOT NULL default '',
							  PRIMARY KEY  (`connector_id`,`source_id`,`ref`,`ufield`,`usubfield`,`field_order`,`subfield_order`,`search_id`),
							  KEY `usubfield` (`usubfield`),
							  KEY `ufield_2` (`ufield`,`usubfield`),
							  KEY `recid_2` (`recid`,`ufield`,`usubfield`),
							  KEY `source_id` (`source_id`),
							  KEY `i_recid_source_id` (`recid`,`source_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=latin1";
		mysql_query($table_entrepot_sql);
		
		//Mise ï¿½ jour des catï¿½gories
		$sql = "DELETE FROM connectors_categ_sources WHERE num_source = ".$source_id;
		mysql_query($sql);
		if ($source_categories) {
			$values = array();
			foreach($source_categories as $acateg_id) {
				if (!$acateg_id) 
					continue;
				$values[] = "(".addslashes($acateg_id).", ".addslashes($source_id).")";
			}
			$values = implode(",", $values);
			if ($values) {
				$sql = "INSERT INTO `connectors_categ_sources` (`num_categ`, `num_source`) VALUES ".$values;
				mysql_query($sql) or die (mysql_error());					
			}
		}		
		return $result;
	}
	
	//Suppression d'une source
	function del_source($source_id) {
		$table_entrepot_sql = "DROP TABLE `entrepot_source_$source_id`;";
		mysql_query($table_entrepot_sql);
		
		$requete="delete from connectors_sources where source_id=$source_id and id_connector='".addslashes($this->get_id())."'";
		return mysql_query($requete);
	}
	
	//Rï¿½cupï¿½ration  des proriï¿½tï¿½s globales par dï¿½faut du connecteur (timeout, retry, repository, parameters)
	function fetch_default_global_values() {
		$this->timeout=5;
		$this->repository=2;
		$this->retry=3;
		$this->ttl=1800;
		$this->parameters="";
	}
	
	//Rï¿½cupï¿½ration  des proriï¿½tï¿½s globales du connecteur (timeout, retry, repository, parameters)
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
	
	//Formulaire des propriï¿½tï¿½s gï¿½nï¿½rales
	function get_property_form() {
		$this->fetch_global_properties();
		//Affichage du formulaire en fonction de $this->parameters
		if ($this->parameters) {
		} else {
			//Affichage du formulaire vide
		}	
	}
	
	function make_serialized_properties() {
		//Mise en forme des paramï¿½tres ï¿½ partir de variables globales (mettre le rï¿½sultat dans $this->parameters)
	}
	
	//Sauvegarde des propriï¿½tï¿½s gï¿½nï¿½rales
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
	
	//Annulation de la mise a jour (faux = synchro conservee dans la table, vrai = synchro supprimee dans la table)
	function cancel_maj($source_id) {
		return false;
	}
	
	//Annulation de la mise a jour (faux = synchro conservee dans la table, vrai = synchro supprimee dans la table)
	function break_maj($source_id) {
		return false;
	}
	
	//Formulaire complementaire facultatif pour la synchronisation
	function form_pour_maj_entrepot($source_id) {
		return false;
	}

	//Nécessaire pour passer les valeurs obtenues dans form_pour_maj_entrepot au javascript asynchrone
	function get_maj_environnement($source_id) {
		return false;
	}
	
	//M.A.J. Entrepot lie a une source
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
	
	//Recherche d'une page de resultat
	function get_page_result($search_id,$page, $n_per_page) {
	}
	
	//Nombre de rï¿½sultats d'une recherche
	function get_n_results($search_id) {
	}
	
	//Recuperation de la valeur d'une autorite
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
	
	var $catalog=array();			//Liste des connecteurs declares
	
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
	
	function show_connector_form($id) {
		global $base_path,$charset,$admin_connecteur_global_params,$lang,$msg;
		//Inclusion de la classe
		require_once($base_path."/admin/connecteurs/in/".$this->catalog[$id]["PATH"]."/".$this->catalog[$id]["NAME"].".class.php");
		eval("\$conn=new ".$this->catalog[$id]["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$this->catalog[$id]["PATH"]."\");");
		$connector_form=$conn->get_property_form();
		$connector_form=str_replace("!!special_form!!",$connector_form,$admin_connecteur_global_params);
		//Remplacement des valeurs par dï¿½faut
		$connector_form=str_replace("!!id!!",$id,$connector_form);
		$connector_form=str_replace("!!connecteur!!",htmlentities($this->catalog[$id]["COMMENT"],ENT_QUOTES,$charset),$connector_form);
		switch ($conn->is_repository()) {
			//Oui
			case 1:
				$connector_form=str_replace("!!repository!!","<input type='hidden' value='1' name='repository' id='repository'/>".$msg["connecteurs_yes"],$connector_form);
				break;
			//Non
			case 2:
				$connector_form=str_replace("!!repository!!","<input type='hidden' value='2' name='repository' id='repository'/>".$msg["connecteurs_no"],$connector_form);
				break;
			//Possible
			case 3:
				$connector_form=str_replace("!!repository!!","<select name='repository' id='repositiory'><option value='1' ".($conn->repository==1?"selected":"").">".$msg["connecteurs_yes"]."</option><option value='2' ".($conn->repository==2?"selected":"").">".$msg["connecteurs_no"]."</option></select>",$connector_form);
				break;
		}
		$connector_form=str_replace("!!timeout!!",$conn->timeout,$connector_form);
		$connector_form=str_replace("!!ttl!!",$conn->ttl,$connector_form);
		$connector_form=str_replace("!!retry!!",$conn->retry,$connector_form);
		return $connector_form;
	}
	
	function show_source_form($id,$source_id="") {
		global $base_path,$charset,$admin_connecteur_source_global_params,$lang,$msg, $dbh;
		
		//Inclusion de la classe
		require_once($base_path."/admin/connecteurs/in/".$this->catalog[$id]["PATH"]."/".$this->catalog[$id]["NAME"].".class.php");
		eval("\$conn=new ".$this->catalog[$id]["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$this->catalog[$id]["PATH"]."\");");
		$connector_form=$conn->source_get_property_form($source_id);
		$s=$conn->get_source_params($source_id);
		$connector_form=str_replace("!!special_form!!",$connector_form,$admin_connecteur_source_global_params);
		//Remplacement des valeurs par defaut
		$connector_form=str_replace("!!id!!",$id,$connector_form);
		$connector_form=str_replace("!!source_id!!",$source_id,$connector_form);
		$connector_form=str_replace("!!connecteur!!",htmlentities($this->catalog[$id]["COMMENT"],ENT_QUOTES,$charset),$connector_form);
		$connector_form=str_replace("!!source!!",htmlentities($s["NAME"],ENT_QUOTES,$charset),$connector_form);
		$connector_form=str_replace("!!name!!",htmlentities($s["NAME"],ENT_QUOTES,$charset),$connector_form);
		$connector_form=str_replace("!!comment!!",htmlentities($s["COMMENT"],ENT_QUOTES,$charset),$connector_form);
		
		$categories_select = '<select MULTIPLE name="source_categories[]">';
		$categories_select .= '<option value="">'.$msg["source_no_category"].'</option>';
		$categories_sql = "SELECT connectors_categ.*, connectors_categ_sources.num_categ FROM connectors_categ LEFT JOIN connectors_categ_sources ON (connectors_categ_sources.num_categ = connectors_categ.connectors_categ_id AND connectors_categ_sources.num_source = ".(isset($source_id) ? $source_id : '-1').")";

		$res = mysql_query($categories_sql, $dbh);
		while($row=mysql_fetch_object($res)) {
			$categories_select .= '<option value="'.$row->connectors_categ_id.'" '.(isset($row->num_categ) ? "SELECTED" : "").'>'.htmlentities($row->connectors_categ_name , ENT_QUOTES,$charset).'</option>';			
		}		
		$categories_select .= '</select>';
		$connector_form=str_replace("!!categories!!", $categories_select, $connector_form);
				
		if ($s["OPAC_ALLOWED"]) $connector_form=str_replace("!!opac_allowed_checked!!","checked",$connector_form);
		else $connector_form=str_replace("!!opac_allowed_checked!!","",$connector_form);
		switch ($conn->is_repository()) {
			//Oui
			case 1:
				$connector_form=str_replace("!!repository!!","<input type='hidden' value='1' name='repository' id='repository'/>".$msg["connecteurs_yes"],$connector_form);
				break;
			//Non
			case 2:
				$connector_form=str_replace("!!repository!!","<input type='hidden' value='2' name='repository' id='repository'/>".$msg["connecteurs_no"],$connector_form);
				break;
			//Possible
			case 3:
				$connector_form=str_replace("!!repository!!","<select name='repository' id='repositiory'><option value='1' ".($s["REPOSITORY"]==1?"selected":"").">".$msg["connecteurs_yes"]."</option><option value='2' ".($s["REPOSITORY"]==2?"selected":"").">".$msg["connecteurs_no"]."</option></select>",$connector_form);
				break;
		}
		$connector_form=str_replace("!!timeout!!",$s["TIMEOUT"],$connector_form);
		$connector_form=str_replace("!!ttl!!",$s["TTL"],$connector_form);
		$connector_form=str_replace("!!retry!!",$s["RETRY"],$connector_form);
		if (!$source_id) {
			$bt_suppr="";
		} else {
			$bt_suppr="<input type='button' class='bouton' value='".$msg["63"]."' onClick='this.form.act.value=\"delete_source\"; this.form.submit();'/>";
		}
		$connector_form=str_replace("!!bt_supprimer!!",$bt_suppr,$connector_form);
		return $connector_form;
	}
}

?>
