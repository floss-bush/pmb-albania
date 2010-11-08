<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: paiements.class.php,v 1.8 2008-09-22 22:09:45 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class paiements{
	
	
	var $id_paiement = 0;					//Identifiant du paiement 
	var $libelle = '';
	var $commentaire = '';

	 
	//Constructeur.	 
	function paiements($id_paiement= 0) {
		
		if ($id_paiement) {
			$this->id_paiement = $id_paiement;
			$this->load();	
		}
	}	


	// charge le paiement à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from paiements where id_paiement = '".$this->id_paiement."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->libelle = $obj->libelle;
		$this->commentaire = $obj->commentaire;

	}

	
	// enregistre le paiement en base.
	function save(){
		
		global $dbh;
		
		if($this->libelle =='') Die("Erreur de création paiement");
		
		if($this->id_paiement) {
			
			$q = "update paiements set libelle ='".$this->libelle."', commentaire = '".$this->commentaire."' ";
			$q.= "where id_paiement = '".$this->id_paiement."' ";
			$r = mysql_query($q, $dbh);
		
		} else {
		
			$q = "insert into paiements set libelle = '".$this->libelle."', commentaire = '".$this->commentaire."' ";
			mysql_query($q, $dbh);
			$this->id_paiement = mysql_insert_id($dbh);
		
		}

	}


	//supprime un paiement de la base
	function delete($id_paiement= 0) {
		
		global $dbh;

		if(!$id_paiement) $id_paiement = $this->id_paiement; 	

		$q = "delete from paiements where id_paiement = '".$id_paiement."' ";
		mysql_query($q, $dbh);
				
	}

	
	//Retourne un Resultset contenant la liste des modes de paiement
	function listPaiements() {
		
		global $dbh;

		$q = "select * from paiements order by libelle ";
		$r = mysql_query($q, $dbh);
		return $r;
				
	}
	
	
	//Vérifie si un mode de paiement existe			
	function exists($id_paiement){
		
		global $dbh;
		$q = "select count(1) from paiements where id_paiement = '".$id_paiement."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}
	
		
	//Vérifie si le libellé d'un mode de paiement existe déjà			
	function existsLibelle($libelle, $id_paiement=0){
		
		global $dbh;
		$q = "select count(1) from paiements where libelle = '".$libelle."' ";
		if ($id_paiement) $q.= "and id_paiement != '".$id_paiement."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	//Vérifie si le mode de paiement est utilisé dans les fournisseurs	
	function hasFournisseurs($id_paiement){
		
		global $dbh;
		if (!$id_paiement) $id_paiement = $this->$id_paiement;
		$q = "select count(1) from entites where num_paiement = '".$id_paiement."' and type_entite = '0'";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}
	
	
	//optimization de la table paiements
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE paiements', $dbh);
		return $opt;
				
	}
	
				
}
?>