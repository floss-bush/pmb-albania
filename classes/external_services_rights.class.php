<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: external_services_rights.class.php,v 1.2 2010-01-30 14:34:52 erwanmartin Exp $

//Gestion des droits des services externes

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$class_path/external_services.class.php");

define("ES_RIGHTS_UNKNOWN_GROUP_OR_METHOD",1);
define("ES_RIGHTS_ANONYMOUS_USER_BAD_PMB_RIGHTS_FOR_METHOD",2);
define("ES_RIGHTS_ANONYMOUS_USER_BAD_PMB_RIGHTS_FOR_GROUP",3);
define("ES_RIGHTS_USER_BAD_PMB_RIGHTS_FOR_METHOD",4);
define("ES_RIGHTS_USER_BAD_PMB_RIGHTS_FOR_GROUP",5);
define("ES_RIGHTS_BAD_PMB_RIGHTS_FOR_THIS_USER",6);
define("ES_RIGHTS_GROUP_OR_METHOD_FORBIDDEN",7);

class es_rights {
	var $group;
	var $method;
	var $available=true;
	var $anonymous_user;
	var $users=array();
	
	function es_rights($group,$method) {
		$this->group=$group;
		$this->method=$method;
	}
}

class external_services_rights extends es_base {
	var $es;			//Services externes
	var $users=array();	//Tableau des users
	var $all_rights;	//Tous les droits !
	
	function external_services_rights($external_services) {
		//Instantiation de la classe external_services
		$this->es=$external_services;
		
		//Récupération des droits des utilisateurs
		$resultat=mysql_query("select * from users");
		while ($r=mysql_fetch_object($resultat)) {
			$this->users[$r->userid]= clone $r;
		}
		
		//Calcul de tous les droits existants
		$constants=get_defined_constants(true);
		$this->all_rights=0;
		foreach ($constants["user"] as $key=>$val) {
			if (substr($key,strlen($key)-5,5)=="_AUTH") $this->all_rights|=$val;
		}
	}
	
	function user_rights($user) {
		return $this->users[$user]->rights;
	}
	
	//Récupère les droits d'une méthode
	function get_rights($group,$method) {
		global $msg;
		$this->clear_error();
		//Vérification que le groupe / méthode existe
		if (((!$method)&&($this->es->group_exists($group)))||($this->es->method_exists($group,$method))) {
			$es_r=new es_rights($group,$method);
			$requete="select available, num_user, anonymous from es_methods, es_methods_users where groupe='".addslashes($group)."' and method='".addslashes($method)."' and id_method=num_method";
			$resultat=mysql_query($requete);
			if ($resultat) {
				$first=true;
				while ($r=mysql_fetch_object($resultat)) {
					if ($first) $es_r->available=$r->available;
					if ($r->anonymous) 
						$es_r->anonymous_user=$r->num_user;
					else
						$es_r->users[]=$r->num_user;
				}
			}
			return $es_r;
		} else {
			$this->set_error(ES_RIGHTS_UNKNOWN_GROUP_OR_METHOD,$msg["es_rights_error_unknown_group"]);
			return false;
		}
	}
	
	function has_basic_rights($user,$group,$method) {
		global $msg;
		$this->clear_error();
		//Compilation de tous les droits
		if ($this->es->group_exists($group)) {
			$group_rights=0;
			$has_rights=false;
			foreach ($this->es->catalog->groups[$group]->methods as $method_name=>$m) {
				if ($m->rights) {
					$group_rights|=$m->rights;
					$has_rights=true;
				}
			}
			if (!$has_rights) $group_rights=$this->all_rights;
			if ((!$method)&&(!($group_rights&$this->user_rights($user)))) return false;
			if ($this->es->method_exists($group,$method)) {
				$method_rights=$this->es->catalog->groups[$group]->methods[$method]->rights;
				if (!$method_rights) $method_rights=$this->all_rights;
				if (($method)&&(!($method_rights&$this->user_rights($user)))) return false;
			} else {
				if ($method) {
					$this->set_error(ES_RIGHTS_UNKNOWN_GROUP_OR_METHOD,$msg["es_rights_error_unknown_group"]);
					return false;
				}
			}
			return true;
		} else {
			$this->set_error(ES_RIGHTS_UNKNOWN_GROUP_OR_METHOD,$msg["es_rights_error_unknown_group"]);
		}
		return false;
	}
	
	function has_rights($user,$group,$method) {
		global $msg;
		$user += 0;
		$this->clear_error();
		//La méthode est-elle disponible
		//Recherche de la disponibilité du groupe
		$requete="select available from es_methods where groupe='".addslashes($group)."' and available=0";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) 
			$available=mysql_result($resultat,0,0);
		else {
			 $requete="select available from es_methods where groupe='".addslashes($group)."' and method='".addslashes($method)."'";
			 $resultat=mysql_query($requete);
			 if (mysql_num_rows($resultat)) 
				$available=mysql_result($resultat,0,0);
		}
		
		//Si user est vide, on recherche l'utilisateur anonyme
		if ($user=="") {
			//Recherche de l'anonyme de la méthode
			$requete="select num_user from es_methods, es_methods_users where groupe='".addslashes($group)."' and method='".addslashes($method)."' and num_method=id_method and anonymous=1";
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$user_u=mysql_result($resultat,0,0);
			} else {
				//Si il n'y en a pas, on recherche celui du groupe
				$requete="select num_user from es_methods, es_methods_users where groupe='".addslashes($group)."' and num_method=id_method and anonymous=1";
				$resultat=mysql_query($requete);
				if (mysql_num_rows($resultat)) {
					$user_u=mysql_result($resultat,0,0);
				} else {
					return false;
				}
			}
		} else {
			//L'utilisateur est fourni, on regarde si il peut utiliser la méthode
			
			//Voyons si il a les accès complet au groupe directement
			$sql = "SELECT COUNT(1) FROM es_methods_users LEFT JOIN es_methods ON (es_methods_users.num_method = id_method) WHERE groupe = '".addslashes($group)."' AND method = '' AND available = 1 AND num_user = ".$user;
			$res = mysql_query($sql);
			$full_group_allowed = mysql_result($res,0,0);
			if ($full_group_allowed)
				$user_u = $user;
			else {
				//Voyons si il a les accès à la méthode
				$sql = "SELECT COUNT(1) FROM es_methods_users LEFT JOIN es_methods ON (es_methods_users.num_method = id_method) WHERE groupe = '".addslashes($group)."' AND method = '".addslashes($method)."' AND available = 1 AND num_user = ".$user;
				$res = mysql_query($sql);
				$method_allowed = mysql_result($res,0,0);
				if ($method_allowed)
					$user_u = $user;
			}
		}
		//Si utilisateur trouvé, on vérifie ses droits de base
		if (!$this->has_basic_rights($user_u,$group,$method)) {
			$this->set_error(ES_RIGHTS_BAD_PMB_RIGHTS_FOR_THIS_USER,sprintf($msg["es_rights_bad_user_rights"],$this->users[$user_u]->username));
		} else if (!$available) {
			$this->set_error(ES_RIGHTS_GROUP_OR_METHOD_FORBIDDEN,$msg["es_rights_group_forbidden"]);
		} else return true;
		return false;
	}
	
	function set_rights($es_r) {
		global $msg;
		$this->clear_error();
		//Vérification des droits 
		if (((!$es_r->method)&&($this->es->group_exists($es_r->group)))||($this->es->method_exists($es_r->group,$es_r->method))) {
			//Vérification des droits
			if ((($es_r->anonymous_user)&&($this->has_basic_rights($es_r->anonymous_user,$es_r->group,$es_r->method)))||(!$es_r->anonymous_user)) {
				//Pour chaque user, vérification des droits !
				for ($i=0; $i<count($es_r->users); $i++) {
					if (($es_r->users[$i]!=$es_r->anonymous_user)&&(!$this->has_basic_rights($es_r->users[$i],$es_r->group,$es_r->method))) {
						if ($es_r->method)
							$this->set_error(ES_RIGHTS_USER_BAD_PMB_RIGHTS_FOR_METHOD,sprintf($msg["es_rights_user_unsifficent_rights"],$this->users[$es_r->users[$i]]->username,$es_r->method,$es_r->group));
						else $this->set_error(ES_RIGHTS_USER_BAD_PMB_RIGHTS_FOR_GROUP,sprintf($msg["es_rights_user_unsifficent_rights_group"],$this->users[$es_r->users[$i]]->username,$es_r->group));
						return false; 
					}
				}
				//Tout va bien, on insère !!
				//Recherche de l'ancien id
				$id_method=0;
				$requete="select id_method from es_methods where groupe='".addslashes($es_r->group)."' and method='".addslashes($es_r->method)."'";
				$resultat=mysql_query($requete);
				if (mysql_num_rows($resultat)) $id_method=mysql_result($resultat,0,0);
				if ($id_method) {
					$requete="delete from es_methods where groupe='".addslashes($es_r->group)."' and method='".addslashes($es_r->method)."'";
					mysql_query($requete);
					$requete="delete from es_methods_users where num_method=$id_method";
					mysql_query($requete);
				}
				//Insertion maintenant
				$requete="insert into es_methods (groupe,method,available) values('".addslashes($es_r->group)."','".addslashes($es_r->method)."',".$es_r->available.")";
				mysql_query($requete);
				$id_method=mysql_insert_id();
				if ($es_r->anonymous_user) mysql_query("insert into es_methods_users (num_method,num_user,anonymous) values($id_method,$es_r->anonymous_user,1)");
				for ($i=0; $i<count($es_r->users); $i++) {
					if ($es_r->users[$i]!=$es_r->anonymous_user) {
						mysql_query("insert into es_methods_users (num_method,num_user,anonymous) values($id_method,".$es_r->users[$i].",0)");
					}
				}
				return true;
			} else {
				if ($es_r->method)
					$this->set_error(ES_RIGHTS_ANONYMOUS_USER_BAD_PMB_RIGHTS_FOR_METHOD,sprintf($msg["es_rights_unsufficent_anonymous_user_method"],$this->users[$es_r->anonymous_user]->username,$es_r->method,$es_r->group));
				else $this->set_error(ES_RIGHTS_ANONYMOUS_USER_BAD_PMB_RIGHTS_FOR_GROUP,sprintf($msg["es_rights_unsufficent_anonymous_user_group"],$this->users[$es_r->anonymous_user]->username,$es_r->group));
				return false; 
			}
		} else {
			$this->set_error(ES_RIGHTS_UNKNOWN_GROUP_OR_METHOD,$msg["es_rights_error_unknown_group"]);
			return false;
		}
	}
	
	//Utilisateurs possibles en fonction des droits d'un groupe ou d'une méthode
	function possible_users($group,$method) {
		$this->clear_error();
		//Si pas de méthode, consolidation au niveau du groupe
		if ($this->es->group_exists($group)) {
			if (!$method) {	
				$group_rights=0;
				$has_rights=false;
				foreach ($this->es->catalog->groups[$group]->methods as $method_name=>$m) {
					if ($m->rights) {
						$group_rights|=$m->rights;
						$has_rights=true;
					}
				}
				if (!$has_rights) $group_rights=$this->all_rights;
				
				//Recherche des emprunteurs qui on le droit
				$r_users=array();
				foreach($this->users as $user_id=>$user) {
					if ($user->rights&$group_rights) $r_users[]=$user_id;
				}
			} else {
				if ($this->es->method_exists($group,$method)) {
					$r_users=array();
					$method_rights=$this->es->catalog->groups[$group]->methods[$method]->rights;
					if (!$method_rights) $method_rights=$this->all_rights;
					foreach($this->users as $user_id=>$user) {
						if ($user->rights&$method_rights) $r_users[]=$user_id;
					}
				} else {
					$this->set_error(ES_RIGHTS_UNKNOWN_GROUP_OR_METHOD,$msg["es_rights_error_unknown_group"]);
					return false;
				}
			}
			return $r_users;
		} else {
			$this->set_error(ES_RIGHTS_UNKNOWN_GROUP_OR_METHOD,$msg["es_rights_error_unknown_group"]);
			return false;
		}
	}
}
?>