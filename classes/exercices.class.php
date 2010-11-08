<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: exercices.class.php,v 1.12 2009-05-28 08:13:47 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$class_path/actes.class.php");
require_once("$class_path/budgets.class.php");

if(!defined('STA_EXE_CLO')) define('STA_EXE_CLO', 0);	//Statut		0 = Cloturé
if(!defined('STA_EXE_ACT')) define('STA_EXE_ACT', 1);	//Statut		1 = Actif
if(!defined('STA_EXE_DEF')) define('STA_EXE_DEF', 3);	//Statut		3 = Actif par défaut

class exercices{
	
	var $id_exercice = 0;					//Identifiant de l'exercice 
	var $num_entite = 0;
	var $libelle = '';
	var $date_debut = '2006-01-01';
	var $date_fin = '2006-01-01';
	var $statut = STA_EXE_ACT;			//Statut de l'exercice

	 
	//Constructeur.	 
	function exercices($id_exercice= 0) {
		
		if ($id_exercice) {
			$this->id_exercice = $id_exercice;
			$this->load();	
		}	
	
	}	
	
	
	// charge l'exercice à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from exercices where id_exercice = '".$this->id_exercice."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->id_exercice = $obj->id_exercice;
		$this->num_entite = $obj->num_entite;
		$this->libelle = $obj->libelle;
		$this->date_debut = $obj->date_debut;
		$this->date_fin = $obj->date_fin;
		$this->statut = $obj->statut;
	}

	
	// enregistre l'exercice en base.
	function save(){
		
		global $dbh;
		
		if( (!$this->num_entite) || ($this->libelle == '') ) die("Erreur de création exercice");
		
		if($this->id_exercice) {
			
			$q = "update exercices set num_entite = '".$this->num_entite."', libelle ='".$this->libelle."', ";
			$q.= "date_debut = '".$this->date_debut."', date_fin = '".$this->date_fin."', statut = '".$this->statut."' ";
			$q.= "where id_exercice = '".$this->id_exercice."' ";
			mysql_query($q, $dbh);

		} else {
			
			$q = "insert into exercices set num_entite = '".$this->num_entite."', libelle = '".$this->libelle."', ";
			$q.= "date_debut =  '".$this->date_debut."', date_fin = '".$this->date_fin."', statut = '".$this->statut."' ";
			mysql_query($q, $dbh);
			$this->id_exercice = mysql_insert_id($dbh);
			$this->load();

		}
	}


	//supprime un exercice de la base
	function delete($id_exercice= 0) {
		
		global $dbh;

		if(!$id_exercice) $id_exercice = $this->id_exercice; 	
		
		//Suppression des actes
//TODO Voir suppression du lien entre actes et exercices 

 		$res_actes = actes::listByExercice($id_exercice); 
		while (($row = mysql_fetch_object($res_actes))) {
			actes::delete($row->id_acte);
		}

		//Suppression des budgets
		$res_budgets = budgets::listByExercice($id_exercice);
		while (($row = mysql_fetch_object($res_budgets))) {
			budgets::delete($row->id_budget);
		}
		//Suppression de l'exercice
		$q = "delete from exercices where id_exercice = '".$id_exercice."' ";
		mysql_query($q, $dbh);
					
	}

	
	//retourne une requete pour la liste des exercices de l'entité
	function listByEntite($id_entite, $mask='-1', $order='date_debut desc') {
		
		$q = "select * from exercices where num_entite = '".$id_entite."' "; 
		if ($mask != '-1') $q.= "and (statut & '".$mask."') = '".$mask."' ";
		$q.= "order by ".$order." ";
		return $q;
				
	}



	//Vérifie si un exercice existe			
	function exists($id_exercice){
		
		global $dbh;
		$q = "select count(1) from exercices where id_exercice = '".$id_exercice."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}
	
		
	//Vérifie si le libellé d'un exercice existe déjà pour une entité			
	function existsLibelle($id_entite, $libelle, $id_exercice=0){
		
		global $dbh;
		
		$q = "select count(1) from exercices where libelle = '".$libelle."' and num_entite = '".$id_entite."' ";
		if ($id_exercice) $q.= "and id_exercice != '".$id_exercice."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	//Compte le nb de budgets affectés à un exercice			
	function hasBudgets($id_exercice=0){
		
		global $dbh;
		if (!$id_exercice) $id_exercice = $this->id_exercice;
		$q = "select count(1) from budgets where num_exercice = '".$id_exercice."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	//Compte le nb de budgets actifs affectés à un exercice			
	function hasBudgetsActifs($id_exercice=0){
		
		global $dbh;
		if (!$id_exercice) $id_exercice = $this->id_exercice;
		$q = "select count(1) from budgets where num_exercice = '".$id_exercice."' and statut != '2' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	//Compte le nb d'actes affectés à un exercice			
	function hasActes($id_exercice=0){
		
		global $dbh;
		if (!$id_exercice) $id_exercice = $this->id_exercice;
		$q = "select count(1) from actes where num_exercice = '".$id_exercice."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}	


	//Compte le nb d'actes actifs affectés à un exercice
	//Actes actifs == commandes non soldées et non payées				
	function hasActesActifs($id_exercice=0){
		
		global $dbh;
		if (!$id_exercice) $id_exercice = $this->id_exercice;
		$q = "select count(1) from actes where num_exercice = '".$id_exercice."' ";
		$q.= "and (type_acte = 0 and (statut & 32) != 32) "; 
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}	


	//choix exercice par défaut pour une entité
	function setDefault($id_exercice=0) {
		
		global $dbh;
		if (!$id_exercice) $id_exercice = $this->id_exercice;
		$q = "update exercices set statut = '".STA_EXE_ACT."' where statut = '".STA_EXE_DEF."' and num_entite = '".$this->num_entite."' limit 1 "; 
		mysql_query($q, $dbh);
		$q = "update exercices set statut = '".STA_EXE_DEF."' where id_exercice = '".$this->id_exercice."' limit 1 ";
		mysql_query($q, $dbh);
		
	}
	
	//Recuperation de l'exercice session
	function getSessionExerciceId() {
		global $deflt3exercice;
		if (!$_SESSION['id_exercice'] && $deflt3exercice) {
			$_SESSION['id_exercice']=$deflt3exercice;
		}
		return $_SESSION['id_exercice'];
	}

	//Definition de l'exercice session
	function setSessionExerciceId($deflt3exercice) {
		$_SESSION['id_exercice']=$deflt3exercice;
		return;
	}
	
	//optimization de la table exercices
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE exercices', $dbh);
		return $opt;
				
	}
	
				
}
?>