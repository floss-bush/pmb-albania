<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: offres_remises.class.php,v 1.7 2007-03-10 09:25:48 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class offres_remises{
	
	
	var $num_fournisseur = 0;				//Identifiant du fournisseur 
	var $num_produit = 0;					//Identifiant du type de produit
	var $remise = '0.00';					//Remise applicable en %
	var $condition_remise = '';

	 
	//Constructeur.	 
	function offres_remises($num_fournisseur=0, $num_produit=0) {
		
		global $dbh;
		
		if ($num_fournisseur || $num_produit) {
			$this->num_fournisseur = $num_fournisseur;
			$this->num_produit = $num_produit;
			$this->load();			
		}
	}	


	// charge une offre de remise à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from offres_remises where num_fournisseur = '".$this->num_fournisseur."' and num_produit = '".$this->num_produit."' ";
		$r = mysql_query($q, $dbh);
		$obj = mysql_fetch_object($r);
		$this->remise = $obj->remise;
		$this->condition_remise = $obj->condition_remise;

	}

	
	// enregistre une offre de remise en base.
	function save(){
		
		global $dbh;
		
		if(!$this->num_fournisseur || !$this->num_produit) die("Erreur de création offres_remises");
		
		$q = "select count(1) from offres_remises where num_fournisseur = '".$this->num_fournisseur."' and num_produit = '".$this->num_produit."' ";
		$r = mysql_query($q, $dbh);
		if (mysql_result($r, 0, 0) != 0) {

			$q = "update offres_remises set remise = '".$this->remise."', condition_remise ='".$this->condition_remise."' ";
			$q.= "where num_fournisseur = '".$this->num_fournisseur."' and num_produit = '".$this->num_produit."' ";
			$r = mysql_query($q, $dbh);
			
		} else {

			$q = "insert into offres_remises set num_fournisseur = '".$this->num_fournisseur."', num_produit = '".$this->num_produit."', ";
			$q.= "remise =  '".$this->remise."', condition_remise = '".$this->condition_remise."' ";
			$r = mysql_query($q, $dbh);

		}
	}


	//supprime un exercice de la base
	function delete($num_fournisseur, $num_produit) {
		
		global $dbh;

		$q = "delete from offres_remises where num_fournisseur = '".$num_fournisseur."' and num_produit = '".$num_produit."' ";
		$r = mysql_query($q, $dbh);
				
	}

	
	//optimization de la table offres_remises
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE offres_remises', $dbh);
		return $opt;
				
	}
	
				
}
?>