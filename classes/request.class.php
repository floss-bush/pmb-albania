<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: request.class.php,v 1.3 2009-05-16 11:21:58 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path.'/parser.inc.php');
require_once($include_path.'/templates/requests.tpl.php');

if(!defined('REQ_TYP_FRE')) define('REQ_TYP_FRE', 1);	//Type requete	1 = Libre


class request {
	
	var $idproc = 0;				//id de procedure
	var $name = '';					//nom de procédure
	var $requete = '';				//requete SQL
	var $comment = '';				//commentaires sur la procedure
	var $autorisations = array();	//autorisation d'utilisation de la procedure
	var $parameters = '';			//parametres d'execution de la procedure
	var $num_classement = 0;		//Classement de la procedure
	var $p_mode = REQ_MOD_FRE;		//mode de procedure
	var $p_form = '';				//formulaire XML de description de la procedure
	
	
	//Constructeur
	function request($idproc=0) {

		if ($idproc) {
			$this->idproc = $idproc;
			$this->load();	
		}
	}

	// charge une procedure a partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select name, requete, comment, autorisations, parameters, num_classement, p_mode, p_form from procs where idproc = '".$this->idproc."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);

		$this->name = $obj->name;
		$this->requete = $obj->requete;
		$this->comment = $obj->comment;
		$this->autorisations = explode(' ', $obj->autorisations);
		$this->parameters = $obj->parameters;
		$this->p_type = $obj->p_type;
		$this->num_classement = $obj->num_classement; 
		$this->p_mode = $obj->p_mode;
		$this->p_form = $obj->p_form;
		
		
	}
	
	
	// enregistre une procedure en base.
	function save(){
		
		global $dbh;
			
		if ($this->idproc) {
		
			$q = "update procs set ";
			$q.= "requete = '".addslashes($this->requete)."', ";
			$q.= "comment = '".addslashes($this->comment)."', ";
			$q.= "autorisation = '".implode(' ', $this->autorisations)."', ";
			$q.= "parameters ='".addslashes($this->parameters)."', ";
			$q.= "num_classement = '".$this->num_classement."', ";
			$q.= "p_mode = '".$this->p_mode."', ";
			$q.= "p_form = '".addslashes($this->p_form)."' ";
			$q.= "where idproc = '".$this->idproc."' ";
			mysql_query($q, $dbh);
		
		} else {

			$q = "insert into procs set ";
			$q.= "requete = '".addslashes($this->requete)."', ";
			$q.= "comment = '".addslashes($this->comment)."', ";
			$q.= "autorisation = '".implode(' ', $this->autorisations)."', ";
			$q.= "parameters ='".addslashes($this->parameters)."', ";
			$q.= "num_classement = '".$this->num_classement."', ";
			$q.= "p_mode = '".$this->p_mode."', ";
			$q.= "p_form = '".addslashes($this->p_form)."' ";
			mysql_query($q, $dbh);
			$this->idproc = mysql_insert_id($dbh);			
		}
	}


	//supprime une procedure de la base
	function delete($idproc = 0) {
		
		global $dbh;

		if(!$idproc) $idproc = $this->idproc; 	
		$q = "delete from procs where idproc = '".$idproc."' ";
		mysql_query($q, $dbh);
				
	}
	

	//retourne un form pour les autorisations d'une requete ou les autorisations par defaut si requete non creee
	function getAutorisationsForm() {
		
		global $dbh, $charset;
		global $req_auth;
		
		if (is_object($this)) {
			$aut = $this->autorisations;
		} else {
			$aut = array('1');
		}
		
		//recuperation des utilisateurs
		$q = "SELECT userid, username FROM users ";
		$r = mysql_query($q, $dbh);
		$p_user = array();
		while (($row=mysql_fetch_row($r))) {
			$p_user[$row[0]]=$row[1];
		}
		
		$form = "";
		$id_check_list='';
		foreach($p_user as $userid=>$username) {

			$form.= $req_auth;
			$form = str_replace('!!user_name!!', htmlentities($username,ENT_QUOTES, $charset), $form);
			$form = str_replace('!!user_id!!', $userid, $form);
			if (in_array($userid, $aut)) { 
				$chk = 'checked=\'checked\'';
			} else {
				$chk = '';
			}
			$form = str_replace('!!checked!!', $chk, $form);
			
			$id_check="user_aut[".$userid."]";
			if($id_check_list)$id_check_list.='|';
			$id_check_list.=$id_check;			
		}
		$form.="<input type='hidden' id='auto_id_list' name='auto_id_list' value='$id_check_list' >";
		return $form;

	}	
	
}

?>