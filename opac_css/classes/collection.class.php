<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collection.class.php,v 1.9 2010-11-02 16:20:29 ngantier Exp $

// définition de la classe de gestion des collections
// inclure :
// classes/publisher.class.php

if ( ! defined( 'COLLECTION_CLASS' ) ) {
  define( 'COLLECTION_CLASS', 1 );

class collection {

	// ---------------------------------------------------------------
	//  propriétés de la classe
	// ---------------------------------------------------------------

	// note : '//' signifie appartenant à la table concernée
	//        '////' signifie deviné avec des requêtes sur d'autres tables
	var $id;                 // MySQL id in table 'collections'
	var $name;               // collection name
	var $parent;             // MySQL id of parent publisher
	var $publisher_libelle; //// name of parent publisher
	var $publisher_isbd;    //// isbd form of publisher
	var $display;           //// usable form for displaying	( _name_ (_publisher_) )
	var $isbd_entry;        //// isbd form
	var $issn;               // ISSN of collection
	var $collection_web;		// web de collection
	var $collection_web_link;	// lien web de collection
	var $comment;
// ---------------------------------------------------------------
//  collection($id) : constructeur
// ---------------------------------------------------------------
function collection($id) {
	// on regarde si on a une collection-objet ou un id de collection
	if (is_object($id))
		$this->get_primaldatafrom($id);
	else {
		$this->id = $id;
		$this->get_primaldata();
	}
	$this->get_otherdata();
}



// ---------------------------------------------------------------
//  get_primaldata() : récupération infos collection à partir de l'id
// ---------------------------------------------------------------
function get_primaldata() {
	global $dbh;
	$requete = "SELECT * FROM collections WHERE collection_id=$this->id LIMIT 1 ";
	$result = @mysql_query($requete, $dbh);
	if(mysql_num_rows($result)) {
		$obj = mysql_fetch_object($result);
		mysql_free_result($result);
		$this->get_primaldatafrom($obj);
	} else {
		// pas de collection avec cette clé
		$this->id                = 0;
		$this->name              = '';
		$this->parent            = '';
		$this->publisher_libelle = '';
		$this->publisher_isbd    = '';
		$this->display           = '';
		$this->issn              = '';
		$this->isbd_entry        = '';
		$this->collection_web	 = '';
		$this->collection_web_link = "" ;
		$this->comment = "" ;
	}
}

// ---------------------------------------------------------------
//  get_primaldatafrom($obj) : récupération infos collection à partir d'un collection-objet
// ---------------------------------------------------------------
function get_primaldatafrom($obj) {
	global $charset;
	
	$this->id = $obj->collection_id;
	$this->name = $obj->collection_name;
	$this->parent = $obj->collection_parent;
	$this->issn = $obj->collection_issn;
	$this->collection_web= $obj->collection_web;
	$this->comment= $obj->collection_comment;
	if($obj->collection_web) 
		$this->collection_web_link = " <a href='$obj->collection_web' target=_blank title='".htmlentities($obj->collection_web,ENT_QUOTES,$charset)."' alt='".htmlentities($obj->collection_web,ENT_QUOTES,$charset)."' ><img src='./images/globe.gif' border=0 /></a>";
	else 
		$this->collection_web_link = "" ;
}

// ---------------------------------------------------------------
//  get_otherdata() : calcul des données n'appartenant pas à la table
// ---------------------------------------------------------------
function get_otherdata() {
	$publisher = new publisher($this->parent);
	$this->publisher_isbd = $publisher->isbd_entry;
	$this->publisher_libelle = $publisher->name;
	$this->isbd_entry = $this->issn ? $this->name.', ISSN '.$this->issn : $this->name;
	$this->display = $this->name.' ('.$this->publisher_libelle.')';
}

// ---------------------------------------------------------------
//  print_resume($level) : affichage d'informations sur la collection
// ---------------------------------------------------------------
function print_resume($level = 2,$css) {
	global $css;
	global $msg;
	
	if(!$this->id)
		return;

	// adaptation par rapport au niveau de détail souhaité
	switch ($level) {
		// case x :
		case 2 :
		default :
			global $collection_level2_display;
			global $collection_level2_no_issn_info;

			$collection_display = $collection_level2_display;
			$collection_no_issn_info = $collection_level2_no_issn_info;
			break;
		}

	$print = $collection_display;
	// remplacement des champs statiques
	$print = str_replace("!!name!!", $this->name." ".$this->collection_web_link, $print);
	$print = str_replace("!!issn!!", $this->issn ? $this->issn : $collection_no_issn_info, $print);
	$print = str_replace("!!publ!!", $this->publisher_libelle, $print);
	$print = str_replace("!!publ_isbd!!", $this->publisher_isbd, $print);
	$print = str_replace("!!isbd!!", $this->isbd_entry, $print);
	$print = str_replace("!!comment!!", $this->comment, $print);
	// remplacement des champs dynamiques
	if (ereg("!!publisher!!", $print)) {
		$remplacement = "<a href='index.php?lvl=publisher_see&id=$this->parent'>$this->publisher_libelle</a>";
		$print = str_replace("!!publisher!!", $remplacement, $print);
		}

	if (ereg("!!subcolls!!", $print)) {
		global $dbh;
		$query = "select sub_coll_id, sub_coll_name from sub_collections where sub_coll_parent=".$this->id;
		$result = mysql_query($query, $dbh);
		if(mysql_num_rows($result)) {
			$remplacement = $msg["subcollection_attached"]."\n<ul>\n";
			while ($obj = mysql_fetch_object($result)) 
				$remplacement .= "<li><a href='index.php?lvl=subcoll_see&id=".$obj->sub_coll_id."'>".$obj->sub_coll_name."</a></li>\n";
			mysql_free_result($result);
			$remplacement .= "</ul>\n";
			} else $remplacement = "";
		$print = str_replace("!!subcolls!!", $remplacement, $print);
		}

	return $print;
	}

} # fin de définition de la classe collection

} # fin de délaration
