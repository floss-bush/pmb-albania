<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: subcollection.class.php,v 1.7 2010-11-02 16:20:29 ngantier Exp $

// définition de la classe de gestion des 'sous-collections'

if ( ! defined( 'SUB_COLLECTION_CLASS' ) ) {
  define( 'SUB_COLLECTION_CLASS', 1 );

class subcollection {

	// ---------------------------------------------------------------
	//  propriétés de la classe
	// ---------------------------------------------------------------

	// note : '//' signifie appartenant à la table concernée
	//        '////' signifie deviné avec des requêtes sur d'autres tables
	var $id;                  // MySQL id in table 'collections'
	var $name;                // collection name
	var $parent;              // MySQL id of parent collection
	var $parent_libelle;     //// name of parent collection
	var $parent_isbd;        //// name of parent collection, isbd form
	var $publisher;          //// MySQL id of publisher
	var $publisher_libelle;  //// name of parent publisher
	var $publisher_isbd;     //// isbd form of publisher
	var $display;            //// usable form for displaying	( _collection_. _name_ (_editeur_) )
	var $isbd_entry;         //// ISBD form ( _collection_. _name_ )
	var $issn;                // ISSN of sub collection
	var $comment;


	// ---------------------------------------------------------------
	//  subcollection($id) : constructeur
	// ---------------------------------------------------------------

	function subcollection($id=0)
	{
		// on regarde si on a une subcollection-objet ou un id de subcollection
		if (is_object($id))
		{
			$this->get_primaldatafrom($id);
		}
		else
		{
			$this->id = $id;
			$this->get_primaldata();
		}
		$this->get_otherdata();
	}



	// ---------------------------------------------------------------
	//  get_primaldata() : récupération infos subcollection à partir de l'id
	// ---------------------------------------------------------------

	function get_primaldata()
	{
		global $dbh;
		$requete = "SELECT * FROM sub_collections WHERE sub_coll_id=$this->id ";
		$result = mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$obj = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->get_primaldatafrom($obj);
		} else {
			// pas de sous-collection avec cette clé
			$this->id                 = 0;
			$this->name               = '';
			$this->parent             = '';
			$this->parent_libelle     = '';
			$this->parent_isbd        = '';
			$this->publisher          = '';
			$this->publisher_libelle  = '';
			$this->publisher_isbd     = '';
			$this->display            = '';
			$this->issn               = '';
			$this->isbd_entry         = '';
			$this->comment         	  = '';
		}
	}



	// ---------------------------------------------------------------
	//  get_primaldatafrom($obj) : récupération infos collection à partir d'un collection-objet
	// ---------------------------------------------------------------

	function get_primaldatafrom($obj)
	{
		$this->id = $obj->sub_coll_id;
		$this->name = $obj->sub_coll_name;
		$this->parent = $obj->sub_coll_parent;
		$this->issn = $obj->sub_coll_issn;
		$this->comment = $obj->subcollection_comment;
	}



	// ---------------------------------------------------------------
	//  get_otherdata() : calcul des données n'appartenant pas à la table
	// ---------------------------------------------------------------

	function get_otherdata()
	{
		if ($this->parent) {
			$parentcoll = new collection($this->parent);
			$this->parent_libelle = $parentcoll->name;
			$this->parent_isbd = $parentcoll->isbd_entry;
			$this->publisher = $parentcoll->parent;
			$this->publisher_libelle = $parentcoll->publisher_libelle;
			$this->publisher_isbd = $parentcoll->publisher_isbd;
		}
		else
		{
			$this->parent_libelle = "";
			$this->parent_isbd = "";
			$this->publisher = "";
			$this->publisher_libelle = "";
			$this->publisher_isbd = "";
		}
		$this->display = $this->parent_libelle.'.&nbsp;'.$this->name.'&nbsp;('.$this->publisher_libelle.')';
		$this->isbd_entry = $this->issn ? $this->parent_libelle.'.&nbsp;'.$this->name.', ISSN '.$this->issn : $this->parent_libelle.'.&nbsp;'.$this->name ;
	}

	// ---------------------------------------------------------------
	//  print_resume($level) : affichage d'informations sur la sous-collection
	// ---------------------------------------------------------------

	function print_resume($level = 2,$css)
	{
		global $css;
		if(!$this->id)
			return;

		// adaptation par rapport au niveau de détail souhaité
		switch ($level) {
			// case x :
			case 2 :
			default :
				global $subcollection_level2_display;
				global $subcollection_level2_no_issn_info;

				$subcollection_display = $subcollection_level2_display;
				$subcollection_no_issn_info = $subcollection_level2_no_issn_info;
				break;
		}

		$print = $subcollection_display;
		
		// remplacement des champs statiques
		$print = str_replace("!!name!!", $this->name, $print);
		$print = str_replace("!!issn!!", $this->issn ? $this->issn : $subcollection_no_issn_info, $print);
		$print = str_replace("!!publ!!", $this->publisher_libelle, $print);
		$print = str_replace("!!publ_isbd!!", $this->publisher_isbd, $print);
		$print = str_replace("!!coll!!", $this->parent_libelle, $print);
		$print = str_replace("!!coll_isbd!!", $this->parent_isbd, $print);
		$print = str_replace("!!isbd!!", $this->isbd_entry, $print);
		$print = str_replace("!!comment!!", $this->comment, $print);

		// remplacement des champs dynamiques
		if (ereg("!!publisher!!", $print))
		{
			$remplacement = "<a href='index.php?lvl=publisher_see&id=$this->publisher'>$this->publisher_libelle</a>";
			$print = str_replace("!!publisher!!", $remplacement, $print);
		}

		if (ereg("!!collection!!", $print))
		{
			$remplacement = "<a href='index.php?lvl=coll_see&id=$this->parent'>$this->parent_libelle</a>";
			$print = str_replace("!!collection!!", $remplacement, $print);
		}

		return $print;
	}

} # fin de définition de la classe subcollection

} # fin de délaration
