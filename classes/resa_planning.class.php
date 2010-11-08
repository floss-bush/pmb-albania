<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa_planning.class.php,v 1.2 2007-03-10 09:25:48 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class resa_planning{
	
	
	var $id_resa = 0;							//Identifiant de réservation	
	var $resa_idempr = 0;						//Identifiant du lecteur ayant fait la réservation
	var $resa_idnotice = 0;						//Identifiant de la notice sur laquelle est posée la réservation
	var $resa_date = NULL;						//Date et heure de la demande
	var $resa_date_debut = '0000-00-00';		//Date de début de la réservation
	var $resa_date_fin = '0000-00-00';			//Date de fin de la réservation
	var $resa_validee = 0;						//Réservation validée si 1
	var $resa_confirmee = 0;					//Réservation confirmée si 1

	 
	//Constructeur.	 
	function resa_planning($id_resa= 0) {
		
		if ($id_resa) {
			$this->id_resa = $id_resa;
			$this->load();	
		}
	}

	
	// charge une réservation plannifiée à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from resa_planning where id_resa = '".$this->id_resa."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->resa_idempr = $obj->resa_idempr;
		$this->resa_idnotice = $obj->resa_idnotice;
		$this->resa_date = $obj->resa_date;
		$this->resa_date_debut = $obj->resa_date_debut;
		$this->resa_date_fin = $obj->resa_date_fin;
		$this->resa_validee = $obj->resa_validee;
		$this->resa_confirmee = $obj->resa_confirmee;

	}

	
	// enregistre une réservation plannifiée en base.
	function save(){
		
		global $dbh;
		
		if ( !$this->resa_idempr || !$this->resa_idnotice || !$this->resa_date_debut || !$this->resa_date_fin ) die("Erreur de création resa_planning");
	
		if ($this->id_resa) {
			
			$q = "update resa_planning set resa_date_debut = '".$this->resa_date_debut."', resa_date_fin = '".$this->resa_date_fin."', ";
			$q.= "resa_validee = '".$this->resa_validee."', resa_confirmee = '".$this->resa_confirmee."' ";
			$q.= "where id_resa = '".$this->id_resa."' ";
			$r = mysql_query($q, $dbh);
			
		} else {
			
			$q = "insert into resa_planning set resa_idempr = '".$this->resa_idempr."', resa_idnotice = '".$this->resa_idnotice."', resa_date = SYSDATE(), ";
			$q.= "resa_date_debut = '".$this->resa_date_debut."', resa_date_fin = '".$this->resa_date_fin."', resa_validee = '0', resa_confirmee = '0' ";
			$r = mysql_query($q, $dbh);
			$this->id_resa = mysql_insert_id($dbh);
			
		}

	}


	//supprime une réservation plannifiée de la base
	function delete($id_resa= 0) {
		
		global $dbh;

		if(!$id_resa) $id_resa = $this->id_resa; 	

		$q = "delete from resa_planning where id_resa = '".$id_resa."' ";
		$r = mysql_query($q, $dbh);
				
	}


	//Compte le nb de réservations planifiée sur une notice
	function countResa($id_notice=0) {
		
		global $dbh;
		
		if (!$id_notice) $id_notice=$this->resa_idnotice;
		$q = "SELECT count(1) FROM resa_planning WHERE resa_idnotice='".$id_notice."' ";
		$r = mysql_query($q, $dbh);
		return mysql_result(mysql_query($r,$dbh), 0, 0);
	}

	
	//optimization de la table resa_planning
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE resa_planning', $dbh);
		return $opt;
				
	}
	
				
}
?>