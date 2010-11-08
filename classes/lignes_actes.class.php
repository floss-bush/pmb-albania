<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lignes_actes.class.php,v 1.19 2009-12-24 15:28:25 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class lignes_actes{
	
	
	var $id_ligne = 0;					//Identifiant de la ligne d'acte	
	var $type_ligne = 0;				//type de ligne de commande (0=texte, 1=notice, 2=bulletin, 3=frais, 4=abt, 5=article)
	var $num_acte = 0;					//Identifiant de l'acte auquel est rattachée la ligne
	var $lig_ref = 0;					//Identifiant de la ligne de l'acte à laquelle est liée cette ligne (pour commande ->livraison)
	var $num_acquisition = 0;			//Identifiant de l'acquisition ayant déclenché la commande (optionnel)
	var $num_rubrique = 0;				//Identifiant du numéro de rubrique budgétaire à laquelle est affectée la ligne d'acte
	var $num_produit = '';				//Identifiant de notice ou 0 si produit non géré
	var $num_type = '0';				//Identifiant du type de produit
	var $libelle = '';					//Libelle de la ligne de commande, reprend titre, editeur, auteur, collection, ...
	var $code = '';						//ISBN, ISSN, ...
	var $prix = '0.00';					//Prix de l'ouvrage
	var $tva = '0.00';					//Tva applicable sur l'ouvrage
	var $remise = '0.00';				//Remise sur ligne
	var $nb = 0;						//nb d'articles 					
	var $date_ech = '0000-00-00';		//Date d'échéance
	var $date_cre = '0000-00-00';		//Date de création de ligne
	var $statut = 0;					
	var $index_ligne = '';				//Index de recherche

	 
	//Constructeur.	 
	function lignes_actes($id_ligne= 0) {
		
		global $dbh;
	
		if ($id_ligne) {
			$this->id_ligne = $id_ligne;
			$this->load();	
		}
		
	}	
	
	
	// charge une ligne d'acte à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from lignes_actes where id_ligne = '".$this->id_ligne."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->type_ligne = $obj->type_ligne;
		$this->num_acte = $obj->num_acte;
		$this->lig_ref = $obj->lig_ref;
		$this->num_acquisition = $obj->num_acquisition;
		$this->num_rubrique = $obj->num_rubrique;
		$this->num_produit = $obj->num_produit;
		$this->num_type = $obj->num_type;
		$this->libelle = $obj->libelle;
		$this->code = $obj->code;
		$this->prix = $obj->prix;
		$this->tva = $obj->tva;
		$this->remise = $obj->remise;
		$this->nb = $obj->nb;
		$this->date_ech = $obj->date_ech;
		$this->date_cre = $obj->date_cre;
		$this->statut = $obj->statut;
		
	}

	
	// enregistre une ligne d'acte en base
	function save(){
		
		global $dbh;
		
		if (!$this->num_acte) die("Erreur de création Lignes_Actes");
		
		if ($this->id_ligne) {
			
			$q = "update lignes_actes set type_ligne = '".$this->type_ligne."', num_acte = '".$this->num_acte."', lig_ref = '".$this->lig_ref."', num_acquisition = '".$this->num_acquisition."', ";
			$q.= "num_rubrique = '".$this->num_rubrique."', num_produit = '".$this->num_produit."', num_type = '".$this->num_type."', ";
			$q.= "libelle = '".$this->libelle."', code = '".$this->code."', prix = '".$this->prix."', tva = '".$this->tva."', nb = '".$this->nb."', ";
			$q.= "remise = '".$this->remise."', date_ech = '".$this->date_ech."', date_cre = '".$this->date_cre."', statut = '".$this->statut."', "; 
			$q.= "index_ligne = ' ".strip_empty_words($this->libelle)." '";
			$q.= "where id_ligne = '".$this->id_ligne."' ";
			$r = mysql_query($q, $dbh);

		} else {

			$q = "insert into lignes_actes set type_ligne = '".$this->type_ligne."', num_acte = '".$this->num_acte."', lig_ref = '".$this->lig_ref."', num_acquisition = '".$this->num_acquisition."', num_rubrique = '".$this->num_rubrique."', ";
			$q.= "num_produit = '".$this->num_produit."', num_type = '".$this->num_type."', libelle = '".$this->libelle."', code = '".$this->code."', prix = '".$this->prix."', tva = '".$this->tva."', nb = '".$this->nb."', ";
			$q.= "remise = '".$this->remise."', date_ech = '".$this->date_ech."', date_cre = '".today()."', statut = '".$this->statut."', ";
			$q.= "index_ligne = ' ".strip_empty_words($this->libelle)." '";
			$r = mysql_query($q, $dbh);
			$this->id_ligne = mysql_insert_id($dbh);
			
		}
	}


	//supprime une ligne d'acte de la base
	function delete($id_ligne= 0) {
		
		global $dbh;

		if(!$id_ligne) $id_ligne = $this->id_ligne; 	

		$q = "delete from lignes_actes where id_ligne = '".$id_ligne."' ";
		$r = mysql_query($q, $dbh);
				
	}


	//retourne les lignes de livraison pour une ligne de commande
	//Si num_acte est indiqué, recherche uniquement dans les enregistrements de l'acte correspondant
	function getLivraisons($id_lig, $num_acte=0) {
		
		global $dbh;
		
		if ($num_acte) {
			$q = "select * from lignes_actes where lig_ref = '".$id_lig."' and num_acte = '".$num_acte."' order by id_ligne ";
		} else {
			$q = "select lignes_actes.* from actes,lignes_actes where actes.type_acte = '2' and lignes_actes.lig_ref = '".$id_lig."' ";
			$q.= "and lignes_actes.num_acte = actes.id_acte order by id_ligne ";
		}
		$r = mysql_query($q, $dbh);
		return $r;
	}


	//retourne les lignes de facture pour une ligne de commande
	//Si num_acte est indiqué, recherche uniquement dans les enregistrements de l'acte correspondant
	function getFactures($id_lig, $num_acte=0) {
		
		global $dbh;
		
		if ($num_acte) {
			$q = "select * from lignes_actes where lig_ref = '".$id_lig."' and num_acte = '".$num_acte."' order by id_ligne ";
		} else {
			$q = "select lignes_actes.* from actes,lignes_actes where actes.type_acte = '3' and lignes_actes.lig_ref = '".$id_lig."' ";
			$q.= "and lignes_actes.num_acte = actes.id_acte order by id_ligne ";
		}
		$r = mysql_query($q, $dbh);
		return $r;
	}


	//optimization de la table lignes_actes
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE lignes_actes', $dbh);
		return $opt;
				
	}
	
				
}
?>