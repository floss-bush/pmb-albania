<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions_categ.class.php,v 1.3 2008-09-22 22:09:45 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class suggestions_categ{
	
	 
	var $id_categ = 0;							//Identifiant de categorie de suggestions	
	var $libelle_categ  = '';					//Libelle  de categorie de suggestions

	 
	//Constructeur.	 
	function suggestions_categ($id_categ= 0) {
		
		global $dbh;
	
		if ($id_categ) {
			$this->id_categ = $id_categ;
			$this->load();	
		}

	}	
	
	
	// charge une categorie de suggestions à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from suggestions_categ where id_categ = '".$this->id_categ."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->libelle_categ = $obj->libelle_categ;

	}

	
	// enregistre une categorie de suggestions en base.
	function save(){
		
		global $dbh;

		if( $this->libelle_categ == '' ) die("Erreur de création catégorie de suggestions");
	
		if ($this->id_categ) {
			
			$q = "update suggestions_categ set libelle_categ = '".$this->libelle_categ."' ";
			$q.= "where id_categ = '".$this->id_categ."' ";
			$r = mysql_query($q, $dbh);
			
		} else {
			
			$q = "insert into suggestions_categ set libelle_categ = '".$this->libelle_categ."' ";
			$r = mysql_query($q, $dbh);
			$this->id_categ = mysql_insert_id($dbh);
		
		}
	}


	//Retourne une liste des categories de suggestions (tableau id->libelle)
	function getCategList() {
		
		global $dbh;
		$list_categ = array();

		$q = "select * from suggestions_categ order by libelle_categ ";
		$r = mysql_query($q, $dbh);
		while ($row = mysql_fetch_object($r)){
			$list_categ[$row->id_categ] = $row->libelle_categ;
		}
		return $list_categ;
			
	}


	//Vérifie si une categorie de suggestions existe			
	function exists($id_categ) {
		
		global $dbh;
		$q = "select count(1) from suggestions_categ where id_categ = '".$id_categ."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}
	
		
	//Vérifie si le libelle d'une categorie de suggestions existe déjà en base
	function existsLibelle($libelle, $id_categ=0) {

		global $dbh;
		
		$q = "select count(1) from suggestions_categ where libelle_categ = '".$libelle."' ";
		if($id_categ) $q.= "and id_categ != '".$id_categ."' ";
		$r = mysql_query($q, $dbh);
		return mysql_result($r, 0, 0);

	}


	//supprime une categorie de suggestions de la base
	function delete($id_categ= 0) {
		
		global $dbh;

		if(!$id_categ) $id_categ = $this->id_categ; 	

		$q = "delete from suggestions_categ where id_categ = '".$id_categ."' ";
		$r = mysql_query($q, $dbh);
				
	}


	//Vérifie si la categorie de suggestions est utilisee dans les suggestions	
	function hasSuggestions($id_categ){
		
		global $dbh;
		if (!$id_categ) $id_categ = $this->id_categ;
		$q = "select count(1) from suggestions where num_categ = '".$id_categ."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	//optimization de la table suggestions_categ
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE suggestions_categ', $dbh);
		return $opt;
				
	}
	
				
}?>