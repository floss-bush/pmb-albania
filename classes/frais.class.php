<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: frais.class.php,v 1.9 2007-03-10 09:25:48 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class frais{
	
	
	var $id_frais = 0;					//Identifiant du frais 
	var $libelle = '';
	var $condition_frais = '';
	var $montant = '000000.00';
	var $num_cp_compta = 0;
	var $num_tva_achat = 0;
	

	 
	//Constructeur.	 
	function frais($id_frais= 0) {
		
		if ($id_frais) {
			$this->id_frais = $id_frais;
			$this->load();	
		}
	}
	
		
	// charge le frais à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from frais where id_frais = '".$this->id_frais."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->libelle = $obj->libelle;
		$this->condition_frais = $obj->condition_frais;
		$this->montant = $obj->montant;
		$this->num_cp_compta = $obj->num_cp_compta;
		$this->num_tva_achat = $obj->num_tva_achat;

	}

	
	// enregistre le frais de tva en base.
	function save(){
		
		global $dbh;
		
		if($this->libelle == '') die("Erreur de création frais"); 
		
		if($this->id_frais) {
			
			$q = "update frais set libelle ='".$this->libelle."', condition_frais = '".$this->condition_frais."', ";
			$q.= "montant = '".$this->montant."', num_cp_compta = '".$this->num_cp_compta."', ";
			$q.= "num_tva_achat = '".$this->num_tva_achat."' ";
			$q.= "where id_frais = '".$this->id_frais."' ";
			$r = mysql_query($q, $dbh);
	
		} else {
		
			$q = "insert into frais set libelle = '".$this->libelle."', condition_frais =  '".$this->condition_frais."', ";
			$q.= "montant = '".$this->montant."', num_cp_compta = '".$this->num_cp_compta."', num_tva_achat = '".$this->num_tva_achat."' ";
			$r = mysql_query($q, $dbh);
			$this->id_frais = mysql_insert_id($dbh);
		
		}
	
	}


	//supprime un taux de tva de la base
	function delete($id_frais=0) {
		
		global $dbh;

		if(!$id_frais) $id_frais = $this->id_frais; 	

		$q = "delete from frais where id_frais = '".$id_frais."' ";
		$r = mysql_query($q, $dbh);
				
	}


	//Retourne un Resultset contenant la liste des frais
	function listFrais() {
		
		global $dbh;

		$q = "select * from frais order by libelle ";
		$r = mysql_query($q, $dbh);
		return $r;
				
	}
	
	
	//Vérifie si un frais existe			
	function exists($id_frais){
		
		global $dbh;
		$q = "select count(1) from frais where id_frais = '".$id_frais."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}
	
		
	//Vérifie si le libellé d'un frais annexe existe déjà			
	function existsLibelle($libelle, $id_frais=0){
		
		global $dbh;
		$q = "select count(1) from frais where libelle = '".$libelle."' ";
		if ($id_frais) $q.= "and id_frais != '".$id_frais."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}

		
	//Vérifie si le frais est utilisé dans les fournisseurs	
	function hasFournisseurs($id_frais){
		
		global $dbh;
		if (!$id_frais) $id_frais = $this->id_frais;
		$q = "select count(1) from entites where num_frais = '".$id_frais."' and type_entite = '0'";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	//optimization de la table taux de tva
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE frais', $dbh);
		return $opt;
				
	}
	
				
}
?>