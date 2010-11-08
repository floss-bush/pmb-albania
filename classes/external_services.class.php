<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: external_services.class.php,v 1.8 2010-07-29 14:08:15 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

/*
==========================================================================================
Comment ça marche toutes ces classes?

        .----------------------------------.
        |             es_base              |
        |----------------------------------| hérite de
        | classe de base, contient le      |<-------------------------.
        | mécanisme des erreurs            |                          |
        '----------------------------------'                          |
héritent de ^             ^ hérite de                  .----------------------------.
            |             |                            |        es_parameter        |
            |             |                     [0..n] |----------------------------|
            |   .------------------.    ^------------->| représente une variable d' |
            |   |    es_method     |    |              | entrée d'une méthode       |
            |   |------------------|    |              '----------------------------'
            |   | représente une   |----.                             ^ hérite de
            |   | méthode de l'API |    |                             |
            |   '------------------'    |                             |
            |             ^             |              .----------------------------.
            |             |             |              |         es_result          |
            |             |             |       [0..n] |----------------------------|
            |     .---------------.     v------------->| représente une variable de |
            |     |   es_group    |                    | retour d'une méthode       |
            |     |---------------|                    '----------------------------'
            '-----| contient des  |
            ^     | méthodes      |
            |     '---------------'
            |             ^
            |             |
            |             |
            |     .---------------.               .-------------------------------.
            |     |  es_catalog   |               |       external_services       |
            |     |---------------|[1]            |-------------------------------|
            '-----| contient des  |<--------------| gère les différentes méthodes |
            ^     | groupes       |               | et génère le proxy associé    |
            |     '---------------'               '-------------------------------'
            |                                                     |
            |-----------------------------------------------------'

==========================================================================================
* */

require_once("$include_path/parser.inc.php");
require_once("$class_path/external_services_rights.class.php");
require_once($class_path."/external_services_caches.class.php");
require_once("$include_path/connecteurs_out_common.inc.php");

define("ES_GROUP_CANNOT_READ_MANIFEST_FILE",1);
define("ES_METHOD_NO_GROUP_DEFINED",2);
define("ES_PARAMETER_UNKNOWN_PARAMETER_TYPE",3);
define("ES_CATALOG_CANNOT_READ_CATALOG_FILE",4);

//Classe de base avec gestion des erreurs
class es_base {
	var $error=false;
	var $error_message="";
	var $description="";
	
	function set_error($error_code,$error_message) {
		$this->error=$error_code;
		$this->error_message=$error_message;
	}
	
	function copy_error($object) {
		$this->error=$object->error;
		$this->error_message=$object->error_message;
	}
	
	function clear_error() {
		$this->error=false;
		$this->error_message="";
	}
}

//Paramètre d'une fonction
class es_parameter extends es_base {
	var $name="";
	var $type="scalar";
	var $datatype="string";
	var $nodename="PARAM";
	var $optional=false;
	
	//Pour les paramètres structure : un tableau de type es_parametre;
	var $struct=array();
	
	//Constructeur
	function es_parameter($param="") {
		if (is_array($param)) {
			$this->name=$param["NAME"];
			$this->datatype=$param["DATATYPE"];
			$this->optional=$param["OPTIONAL"];
			//Selon le type (param ou result), ça change
			$classname = get_class($this);
			switch($param["TYPE"]) {
				case "scalar":
					break;
				case "array":
					for ($i=0; $i<count($param[$this->nodename]); $i++) {
						$parametre=$param[$this->nodename][$i];
						$p=new $classname($parametre);
						if (!$p->error) 
							$this->struct[]=$p; 
						else {
							$this->copy_error($p);
							return;
						} 
					}
					break;
				case "structure":
					for ($i=0; $i<count($param[$this->nodename]); $i++) {
						$parametre=$param[$this->nodename][$i];
						$p=new $classname($parametre);
						if (!$p->error) 
							$this->struct[]=$p; 
						else {
							$this->copy_error($p);
							return;
						} 
					}
					break;
				default:
					$this->set_error(ES_PARAMETER_UNKNOWN_PARAMETER_TYPE,"Type de paramètre inconnu");
					return;
			}
			$this->type=$param["TYPE"];
		}
	}
}

//Résultat d'une fonction
class es_result extends es_parameter {
	var $nodename="RESULT";
}

//Composant d'un type
class es_part extends es_parameter {
	var $nodename="PART";
}

//Un type
class es_type extends es_base {
	var $name='';
	var $description='';
	var $imported=false;
	var $imported_from = "";
	var $struct=array();
	var $type="structure";
	
	function es_type($type) {
		if (isset($type["IMPORTED"]) && $type["IMPORTED"]) {
			$this->name = $type["NAME"];
			$this->imported = true;
			$this->imported_from = $type["IMPORTED_FROM"];
			return;
		}
		if (isset($type["DESCRIPTION"][0]["value"]))
			$this->description=$type["DESCRIPTION"][0]["value"];
		$this->name = $type["NAME"];
		if (isset($type["PART"])) {
			foreach ($type["PART"] as $part) {
				$part_object = new es_part($part);
				$this->struct[] = $part_object;
			}
		}
	}
}

//Référence d'une methode dans une autre méthode
class es_requirement extends es_base {
	var $group="";
	var $name="";
	var $version="";

	//Constructeur
	function es_requirement($param="") {
		if (is_array($param)) {
			$this->group=$param["GROUP"];
			$this->name=$param["NAME"];
			$this->version=$param["VERSION"];
		}
	}
	
    public function __toString()
    {
        return $this->group.'_'.$this->name;
    }
}

//Requirement d'une methode
class es_pmb_requirement extends es_base {
	var $start_path="";
	var $file="";

	//Constructeur
	function es_pmb_requirement($param="") {
		if (is_array($param)) {
			$this->start_path=$param["START_PATH"];
			$this->file=$param["FILE"];
		}
	}
	
	//Permet de pouvoir comparer deux instances, dans un array_unique par exemple
    public function __toString()
    {
        return $this->start_path.'___'.$this->file;
    }

}

//Méthode
class es_method extends es_base {
	var $group;
	var $name;
	//Tableau de es_params
	var $inputs=array();
	//Tableau de es_results
	var $outputs=array();
	//Descriptions
	var $description;
	var $input_description="";
	var $output_description="";
	//Droits pour cette méthode
	var $rights=0;
	//Numéro de version de cette méthode
	var $version=0;
	//méthodes nécessaires pour executer cette méthode
	var $requirements=array();
	var $recurvised_requirement_list=array();
	//require_once nécessaires pour executer cette méthode
	var $pmb_file_requirements=array();
	//Défini si la méthode a besoin des messages localisés
	var $language_independant=false;
	
	function es_method($method="",$group="") {
		if (is_array($method)) {
			if (!$group) {
				$this->set_error(ES_METHOD_NO_GROUP_DEFINED,"No group defined");
				return;
			}
			//Analyse du tableau
			$this->group=$group;
			$this->name=$method["NAME"];
			$this->description=$method["COMMENT"];
			$this->version=$method["VERSION"];
			if ($method["RIGHTS"]) {
				$rights=explode("|",$method["RIGHTS"]);
				for ($i=0; $i<count($rights); $i++) {
					$this->rights|=constant($rights[$i]);
				}
			}
			if (isset($method["LANGUAGE_INDEPENDANT"]) && $method["LANGUAGE_INDEPENDANT"] == 'true')
				$this->language_independant = true;
			//Lecture des inputs
			$this->input_description=$method["INPUTS"][0]["DESCRIPTION"][0]["value"];
			for ($i=0; $i<count($method["INPUTS"][0]["PARAM"]); $i++) {
				$parameter=$method["INPUTS"][0]["PARAM"][$i];
				$p=new es_parameter($parameter);
				if (!$p->error) 
					$this->inputs[]=$p;
				else {
					$this->copy_error($p);
					return;
				}
			}
			//Lecture des outputs
			$this->output_description=$method["OUTPUTS"][0]["DESCRIPTION"][0]["value"];
			for ($i=0; $i<count($method["OUTPUTS"][0]["RESULT"]); $i++) {
				$result=$method["OUTPUTS"][0]["RESULT"][$i];
				$r=new es_result($result);
				if (!$r->error)
					$this->outputs[]=$r;
				else {
					$this->copy_error($r);
					return;
				}
			}
			
			//Lecture des requirements
			if (isset($method["REQUIREMENTS"][0]["REQUIREMENT"])) {
				for ($i=0; $i<count($method["REQUIREMENTS"][0]["REQUIREMENT"]); $i++) {
					$result=$method["REQUIREMENTS"][0]["REQUIREMENT"][$i];
					$r=new es_requirement($result);
					if (!$r->error) {
						$this->requirements[]=$r;
						$this->recurvised_requirement_list[] = $r->__toString();
					}
					else {
						$this->copy_error($r);
						return;
					}
				}
			}
			
			//Lecture des pmb_requirements
			if (isset($method["PMB_REQUIREMENTS"][0]["PMB_REQUIREMENT"])) {
				for ($i=0; $i<count($method["PMB_REQUIREMENTS"][0]["PMB_REQUIREMENT"]); $i++) {
					$result=$method["PMB_REQUIREMENTS"][0]["PMB_REQUIREMENT"][$i];
					$r=new es_pmb_requirement($result);
					if (!$r->error)
						$this->pmb_file_requirements[]=$r;
					else {
						$this->copy_error($r);
						return;
					}
				}
			}
		}
	}
}

//Classe représentant un groupe de fonctions
class es_group extends es_base {
	var $name;
	//Tableau de es_type
	var $types=array();
	//Tableau de es_methods
	var $methods=array();
	//Identifiant unique du groupe
	var $id="";
	//Description
	var $description="";
	//Tableau des messages du groupe
	var $msg=array();
	
	function es_group($group_name,$id) {
		global $base_path,$lang;
		//Lecture des propriétés du fichier manifest
		$xml=@file_get_contents($base_path."/external_services/$group_name/manifest.xml");
		if (!$xml) {
			$this->set_error(ES_GROUP_CANNOT_READ_MANIFEST_FILE,"Can't read manifest file");
			return;
		}
		
		$this->name=$group_name;
		$this->id=$id;
		
		//Parse du fichier
		$methods=_parser_text_no_function_($xml,"MANIFEST");
		
		$this->description=$methods["DESCRIPTION"][0]["value"];
		
		//Pour chaque type, on instancie sa représentation
		if (isset($methods["TYPES"][0]["TYPE"])) {
			foreach ($methods["TYPES"][0]["TYPE"] as $atype) {
				$t = new es_type($atype);
				if (!$t->error)
					$this->types[$t->name] = $t;
				else {
					$this->copy_error($t);
					return;
				}
			}
		}
		
		//Pour chaque méthode, on instancie sa représentation
		for ($i=0; $i<count($methods["METHODS"][0]["METHOD"]); $i++) {
			$method=$methods["METHODS"][0]["METHOD"][$i];
			$m=new es_method($method,$this->name);
			if (!$m->error) 
				$this->methods[$m->name]=$m;
			else {
				$this->copy_error($m);
				return;
			}
		}
		
		//Lecture du fichier des messages
		if (!file_exists($base_path."/external_services/$group_name/messages/$lang.xml")) $tlang="fr_FR"; else $tlang=$lang;
		if (file_exists($base_path."/external_services/$group_name/messages/$tlang.xml")) {
			$msg_list=new XMLlist($base_path."/external_services/$group_name/messages/$tlang.xml");
			$msg_list->analyser();
			$this->msg=$msg_list->table;
		}
	}
}

//Classe de lecture du catalogue
class es_catalog extends es_base {
	
	var $groups; //Tableau de groupes
	
	var $recursive_depth;
	
	function es_catalog() {
		global $base_path;
		if (file_exists($base_path."/external_services/catalog_subst.xml")) 
			$catalog_file=$base_path."/external_services/catalog_subst.xml";
		else
			$catalog_file=$base_path."/external_services/catalog.xml";
			
		$xml=@file_get_contents($catalog_file);
		
		if (!$xml) {
			$this->set_error(ES_CATALOG_CANNOT_READ_CATALOG_FILE,"Fichier catalog introuvable");
			return;
		}
		
		$catalog=_parser_text_no_function_($xml,"CATALOG");
		
		//Dépouillement du résultat
		for ($i=0; $i<count($catalog["ITEM"]);$i++) {
			$g=new es_group($catalog["ITEM"][$i]["NAME"],$catalog["ITEM"][$i]["ID"]);
			if (!$g->error)
				$this->groups[$g->name]=$g;
			else {
				$this->copy_error($g);
			}
		}
		
		//Construit la liste des dépendances des fichiers php dont les méthodes ont besoin (exemple: $class_path/acces.class.php)
		$this->recursive_depth = 0;
		$this->create_requirements_lists();
		
		//Construit la liste des dépendandes des autres méthodes dont les méthodes ont besoin.
		$this->recursive_depth = 0;
		$this->fix_imported_pmb_requirements();
	}
	
	function fix_imported_pmb_requirement(&$amethod) {
		if ($this->recursive_depth > 5) //Faut pas pousser mémé dans les orties: évitons une recursion infinie.
			return;
		$this->recursive_depth++;
		if ($amethod->requirements) {
			foreach ($amethod->requirements as &$arequirement) {
				if (isset($this->groups[$arequirement->group]->methods[$arequirement->name])) {
					$this->fix_imported_pmb_requirement($this->groups[$arequirement->group]->methods[$arequirement->name]);
				}
				if (!isset($this->groups[$arequirement->group]->methods[$arequirement->name]->pmb_file_requirements) || !$this->groups[$arequirement->group]->methods[$arequirement->name]->pmb_file_requirements)
					continue;
				$amethod->pmb_file_requirements = array_merge($amethod->pmb_file_requirements, $this->groups[$arequirement->group]->methods[$arequirement->name]->pmb_file_requirements);
				$amethod->pmb_file_requirements = array_unique($amethod->pmb_file_requirements);
			}
		}
		$this->recursive_depth--;
	}
	
	function fix_imported_pmb_requirements() {
		foreach ($this->groups as &$agroup) {
			foreach ($agroup->methods as &$amethod) {
				$this->fix_imported_pmb_requirement($amethod);
			}
		}
	}
	
	function create_requirements_list(&$amethod) {
		if ($this->recursive_depth > 5) //Faut pas pousser mémé dans les orties: évitons une recursion infinie.
			return;
		$this->recursive_depth++;
		if ($amethod->requirements) {
			foreach ($amethod->requirements as &$arequirement) {
				if (isset($this->groups[$arequirement->group]->methods[$arequirement->name])) {
					$this->create_requirements_list($this->groups[$arequirement->group]->methods[$arequirement->name]);
				}
				if (!isset($this->groups[$arequirement->group]->methods[$arequirement->name]->recurvised_requirement_list) || !$this->groups[$arequirement->group]->methods[$arequirement->name]->recurvised_requirement_list)
					continue;
				$amethod->recurvised_requirement_list = array_merge($amethod->recurvised_requirement_list, $this->groups[$arequirement->group]->methods[$arequirement->name]->recurvised_requirement_list);
				$amethod->recurvised_requirement_list = array_unique($amethod->recurvised_requirement_list);
			}
		}
		$this->recursive_depth--;
	}
	
	function create_requirements_lists() {
		foreach ($this->groups as &$agroup) {
			foreach ($agroup->methods as &$amethod) {
				$this->create_requirements_list($amethod);
			}
		}
	}

}

class external_services_api_class {
	protected  $proxy_parent=NULL;
	protected $msg=array();
	protected $es = NULL;
	
	function external_services_api_class($external_services, $group_name, &$proxy_parent) {
		$this->proxy_parent = &$proxy_parent;
		$this->es=$external_services;
		$this->msg=$this->es->msg($group_name);
	}
}

//Classe qui implémente les fonctions externes
class external_services extends es_base {
	var $msg=array();
	var $catalog;
	var $proxy;	//Classe regroupant toutes les fonctions
	
	//Constructeur
	function external_services($allow_caching=false) {
		if ($allow_caching) {
			$es_cache = new external_services_cache('es_cache_blob', 86400);
			
			//Vérifions que le catalogue xml n'a pas changé avant de chercher dans le cache
			$situation = $this->compute_situation_catalog_identity();
			$old_situation = $es_cache->decache_single_object('external_service_catalog_situation', CACHE_TYPE_MISC);
			if ($old_situation == $situation) {
				$cached_result = $es_cache->decache_single_object('external_service_catalog', CACHE_TYPE_MISC);
				if ($cached_result !== false) {
					$cached_result = unserialize(base64_decode($cached_result));
					$this->catalog = $cached_result;
				}
			}
		}

		if (!$this->catalog) {
			//Parse des bibliothèques disponibles
			$this->catalog=new es_catalog();
			if ($this->catalog->error) {
				$this->copy_error($this->catalog);
				return;
			}
			
			if ($allow_caching) {
				//Mettons le catalogue dans le cache
				$es_cache = new external_services_cache('es_cache_blob', 86400);
				$es_cache->encache_single_object('external_service_catalog', CACHE_TYPE_MISC, base64_encode(serialize($this->catalog)));
				$es_cache->encache_single_object('external_service_catalog_situation', CACHE_TYPE_MISC, $situation);
			}
		}

	}
	
	function compute_situation_catalog_identity() {
		global $base_path;
		if (file_exists($base_path."/external_services/catalog_subst.xml")) 
			$catalog_file=$base_path."/external_services/catalog_subst.xml";
		else
			$catalog_file=$base_path."/external_services/catalog.xml";
			
		$xml=@file_get_contents($catalog_file);
		
		if (!$xml) {
			return "";
		}
		
		$catalog=_parser_text_no_function_($xml,"CATALOG");
		
		//Dépouillement du résultat
		$identity = "";
		for ($i=0; $i<count($catalog["ITEM"]);$i++) {
			$identity .= $catalog["ITEM"][$i]["NAME"].filemtime($base_path."/external_services/".$catalog["ITEM"][$i]["NAME"]."/manifest.xml")."_";
		}
		$identity = md5($identity);
		return $identity;
	}
	
	function get_proxy($user, $restrict_use_to_function_list=array()) {
		if ($this->proxy) return $this->proxy;
		$proxy_desc=array();
		$rights=new external_services_rights($this);
		$proxy="
class es_proxy extends es_base {
	var \$es;
	var \$user".($user?"=$user":"").";
	var \$description=\"\";
	var \$error_callback_function=NULL;
	var \$input_charset='utf-8';
";
		
		$proxy_method_requires = "";
		
		$proxy_err_calback_set = "
		
	function set_error_callback(\$callback_function) {
		\$this->error_callback_function = \$callback_function;
	}
		";
		
		$proxy_init="

	function init() {";
		
		//Si on nous soumet une liste de fonctions, il ne faut pas oublier les éventuelles dépendances de celles-ci.
		if ($restrict_use_to_function_list) {
			 $restrict_use_to_function_list_requirements = array();
			 foreach ($this->catalog->groups as $group_name=>$es_group) {
				foreach ($es_group->methods as $method_name=>$es_method) {
					if ($restrict_use_to_function_list && !in_array($group_name.'_'.$method_name, $restrict_use_to_function_list))
						continue;
					if (!$es_method->recurvised_requirement_list)
						continue;
					$restrict_use_to_function_list_requirements = array_merge($restrict_use_to_function_list_requirements, $es_method->recurvised_requirement_list);
				}
			 }
			if (!$restrict_use_to_function_list_requirements)
				$restrict_use_to_function_list_requirements = array();
			$restrict_use_to_function_list = array_merge($restrict_use_to_function_list, $restrict_use_to_function_list_requirements);
			$restrict_use_to_function_list = array_unique($restrict_use_to_function_list);
		}
		
		$pmb_file_requirements = array();
		
		//Création dess variables des classes correspondantes aux groupes
		foreach ($this->catalog->groups as $group_name=>$es_group) {
			//Création des fonctions
			$group_has_method=false;
			$methods_desc=array();
			foreach ($es_group->methods as $method_name=>$es_method) {
				if ($restrict_use_to_function_list && !in_array($group_name.'_'.$method_name, $restrict_use_to_function_list))
					continue;

				if (!$es_method->pmb_file_requirements)
					$es_method->pmb_file_requirements = array();
				$pmb_file_requirements = array_merge($pmb_file_requirements, $es_method->pmb_file_requirements);
					
				//Les droits sont-ils là ?
				if ($rights->has_rights($user,$group_name,$method_name)) {
					
					//Construction des paramètres de la méthode
					$params=array();
					for ($i=0; $i<count($es_method->inputs); $i++) {
						$params[].="\$".$es_method->inputs[$i]->name;
					}
					$group_has_method=true;
					$proxy_func.="
	function ".$group_name."_".$method_name."(".implode(",",$params).") {
		try {
		\$result =  \$this->".$group_name."->".$method_name."(".implode(",",$params).");
		} catch(Exception \$e) {
			if (\$this->error_callback_function)
				call_user_func(\$this->error_callback_function, \$e);
		}
		return \$result;
	}
";
					$mdesc=array();
					$mdesc["name"]=$method_name;
					$mdesc["description"]=$this->get_text($es_method->description,$group_name);
					$mdesc["inputs_description"]=$this->get_text($es_method->input_description,$group_name);
					$mdesc["outputs_description"]=$this->get_text($es_method->output_description,$group_name);
					$methods_desc[]=$mdesc;
				}
			}
			if ($group_has_method) {
				//Fonction d'initialisation
				$proxy_init.="
		\$this->".$group_name."=new ".$group_name."(\$this->es, '".$group_name."', \$this);";
				
				//Variable pour la classe du groupe
				$proxy.="
	var \$".$group_name.";";
				
				//Require pour le groupe
				$proxy_require.="require_once(\$base_path.\"/external_services/".$group_name."/".$group_name.".class.php\");
";			
				//Description du groupe
				$gdesc=array();
				$gdesc["name"]=$group_name;
				$gdesc["description"]=$this->get_text($es_group->description,$group_name);
				$gdesc["methods"]=$methods_desc;
				$proxy_desc[]=$gdesc;
			}
		}
		
		$pmb_file_requirements = array_unique($pmb_file_requirements);
		$name_variable_correspondance = array(
			"class" => '$class_path',
			"base" => '$base_path',
			"include" => '$include_path'
		);
		foreach ($pmb_file_requirements as $arequirement) {
			if (!$name_variable_correspondance[$arequirement->start_path])
				continue;
			$proxy_method_requires .= 'require_once("'.$name_variable_correspondance[$arequirement->start_path].'/'.$arequirement->file.'");'."\n";
		}
		
		$proxy_init.="
	}
";
		$proxy_end="
	function es_proxy(\$external_services) {
		\$this->es=\$external_services;
		\$this->init();
	}
}
";
		//Instanciation de la classe proxy !
		$proxy=$proxy_method_requires.$proxy_require.$proxy.$proxy_init.$proxy_err_calback_set.$proxy_func.$proxy_end;
		
		//Restauration de l'environnement global
		foreach ($GLOBALS as $var_name=>$value) {
			global $$var_name;
		}
		
		//Enregistrons le nom des variables qui existent déjà avant l'eval
		$before_eval_vars = get_defined_vars();
		eval($proxy);
		
		$this->proxy=new es_proxy($this);
		//Affectation des descriptions
		$this->proxy->description=$proxy_desc;
		//Affectation du charset
		global $charset;
		$this->proxy->input_charset = $charset;
		
		//Maintenant nous avons sortir toutes les variables globales générée par l'eval du contexte de la fonction
		$function_variable_names = array("function_variable_names" => 0, "before_eval_vars" => 0, "created" => 0);
		$created = array_diff_key(get_defined_vars(), $GLOBALS, $function_variable_names, $before_eval_vars);
		foreach ($created as $created_name => $on_sen_fiche)
			global $$created_name;
		extract($created);

		return $this->proxy;
	}
	
	function get_group_list() {
		$r=array();
		foreach ($this->catalog->groups as $group_name=>$group) {
			$t=array();
			$t["name"]=$group_name;
			$t["description"]=$this->get_text($group->description,$group_name);
			foreach ($group->methods as $method_name=>$method) {
				$m=array();
				$m["name"]=$method_name;
				$m["description"]=$this->get_text($method->description,$group_name);
				$m["inputs_description"]=$this->get_text($method->input_description,$group_name);
				$m["outputs_description"]=$this->get_text($method->output_description,$group_name);
				$t["methods"][]=$m;
			}
			$r[]=$t;
		}
		return $r;
	}
	
	function group_exists($group) {
		if (is_object($this->catalog->groups[$group])) return true; else return false;
	}
	
	function method_exists($group,$method) {
		if ($this->group_exists($group)) {
			if (is_object($this->catalog->groups[$group]->methods[$method])) return true;
		}
		return false;
	}
	
	function save_persistent($group_name,$uniqueid,$message) {
		//Sauvegarde de manière persistente 
		$requete="insert into external_persist (group_name,uniqueid,message) values('".addslashes($group_name)."','".addslashes($uniqueid)."','".addslashes($message)."')";
		$r=mysql_query($requete);
		if ($r) return true; else return false;
	}
	
	function msg($group_name) {
		return $this->catalog->groups[$group_name]->msg;
	}
	
	function get_text($text,$group_name) {
		if (substr($text,0,4)=="msg:") {
			$lmsg=$this->msg($group_name);
			return $lmsg[substr($text,4)];
		} else return $text;
	}
	
	function operation_need_messages($operation) {
		foreach ($this->catalog->groups as &$agroup) {
			foreach ($agroup->methods as &$amethod) {
				if ($operation == $agroup->name."_".$amethod->name) {
					return !$amethod->language_independant;
				}
			}
		}
	}
}
?>