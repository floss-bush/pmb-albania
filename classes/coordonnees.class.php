<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: coordonnees.class.php,v 1.8 2007-03-10 09:25:48 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class coordonnees{
	
	
	var $id_contact = 0;			//Identifiant du contact	
	var $num_entite = 0;			//Identifiant de l'entité à laquelle est rattaché le contact
	var $type_coord = 0;			//type de coordonnées (0=non précisé, 1=principale/facturation, 2=livraison)
	var $libelle = '';				//Libellé adresse si <> de raison sociale entité
	var $contact = '';				//Genre, Nom, Prenom du contact
	var $adr1 = '';					//Ligne 1 adresse
	var $adr2 = '';					//Ligne 2 adresse
	var $cp = '';					//Code postal
	var $ville = '';				//Ville
	var $etat = '';					//Etat
	var $pays = '';					//Pays
	var $tel1 = '';					//Numéro de tél 1
	var $tel2 = '';					//Numéro de tél 2
	var $fax = '';					//Numéro de fax
	var $email = '';				//Email
	var $commentaires = '';			//Commentaires sur le contact			

	 
	//Constructeur.	 
	function coordonnees($id_contact= 0) {
		
		global $dbh;
	
		if ($id_contact) {
			$this->id_contact = $id_contact;
			$this->load();	
		} 
	}	
	
	
	// charge un contact à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from coordonnees where id_contact = '".$this->id_contact."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->num_entite = $obj->num_entite;
		$this->type_coord = $obj->type_coord;
		$this->libelle = $obj->libelle;
		$this->contact = $obj->contact;
		$this->adr1 = $obj->adr1;
		$this->adr2 = $obj->adr2;
		$this->cp = $obj->cp;
		$this->ville = $obj->ville;
		$this->etat = $obj->etat;
		$this->pays = $obj->pays;
		$this->tel1 = $obj->tel1;
		$this->tel2 = $obj->tel2;
		$this->fax = $obj->fax;
		$this->email = $obj->email;
		$this->commentaires = $obj->commentaires;

	}

	
	// enregistre un contact en base.
	function save(){
		
		global $dbh;

		if( !$this->num_entite ) die ("Erreur de création coordonnées");
		
		if ($this->id_contact) {
		
			$q = "update coordonnees set num_entite = '".$this->num_entite."', type_coord = '".$this->type_coord."', libelle = '".$this->libelle."', contact = '".$this->contact."', ";
			$q.= "adr1 = '".$this->adr1."', adr2 = '".$this->adr2."', cp = '".$this->cp."', ville = '".$this->ville."', ";
			$q.= "etat = '".$this->etat."', pays = '".$this->pays."', tel1 = '".$this->tel1."', tel2 = '".$this->tel2."', ";
			$q.= "fax = '".$this->fax."', email = '".$this->email."', commentaires = '".$this->commentaires."' ";
			$q.= "where id_contact = '".$this->id_contact."' ";
			$r = mysql_query($q, $dbh);

		} else {
			
			$q = "insert into coordonnees set num_entite = '".$this->num_entite."', type_coord = '".$this->type_coord."', libelle = '".$this->libelle."', contact = '".$this->contact."', ";
			$q.= "adr1 = '".$this->adr1."', adr2 = '".$this->adr2."', cp = '".$this->cp."', ville = '".$this->ville."', ";
			$q.= "etat = '".$this->etat."', pays = '".$this->pays."', tel1 = '".$this->tel1."', tel2 = '".$this->tel2."', ";
			$q.= "fax = '".$this->fax."', email = '".$this->email."', commentaires = '".$this->commentaires."' "; 
			$r = mysql_query($q, $dbh);
			$this->id_contact = mysql_insert_id($dbh);
		}
	}


	//supprime un contact de la base
	function delete($id_contact= 0) {
		
		global $dbh;

		if(!$id_contact) $id_contact = $this->id_contact; 	

		$q = "delete from coordonnees where id_contact = '".$id_contact."' ";
		$r = mysql_query($q, $dbh);
				
	}


	//Recherche si un contact existe déjà dans la base à partir de son identifiant
	function exists($id_contact=0) {
		
		global $dbh;
		if (!$id_contact) $id_contact = $this->id_contact;
		$q = "select count(1) from coordonnees where id_contact = '".$id_contact."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	
	//optimization de la table coordonnees
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE coordonnees', $dbh);
		return $opt;
				
	}
	
				
}
?>