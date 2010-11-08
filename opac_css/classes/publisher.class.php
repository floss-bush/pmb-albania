<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: publisher.class.php,v 1.18 2010-08-17 13:21:26 mbertin Exp $

// définition de la classe de gestion des 'editeurs'

if ( ! defined( 'PUBLISHER_CLASS' ) ) {
  define( 'PUBLISHER_CLASS', 1 );

class publisher {

// ---------------------------------------------------------------
//		propriétés de la classe
// ---------------------------------------------------------------

	// note : '//' signifie appartenant à la table concernée
	//        '////' signifie deviné avec des requêtes sur d'autres tables
	var $id;          // MySQL id in table 'publishers'
	var $name;        // publisher name
	var $adr1;        // adress line 1
	var $adr2;        // adress line 2
	var $cp;          // zip code
	var $ville;       // city
	var $pays;        // country
	var $web;         // url of web site
	var $link;       //// url of web site (clickable)
	var $display;    //// usable form for displaying ( _name_ (_ville_) or just _name_ )
	var $isbd_entry; //// isbd like version ( _ville_ (_country ?_) : _name_ )
	var $ed_comment="";


	// ---------------------------------------------------------------
	//  publisher($id) : constructeur
	// ---------------------------------------------------------------

	function publisher($id) {
		// on regarde si on a un publisher-objet ou un id de publisher
		if (is_object($id)) {
			$this->get_primaldatafrom($id);
		} else {
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
		$requete = "SELECT * FROM publishers WHERE ed_id='".$this->id."'";
		$result = mysql_query($requete, $dbh);
		if (mysql_num_rows($result)) {
			$obj = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->get_primaldatafrom($obj);
		} else {
			// pas de collection avec cette clé
			$this->id          = 0;
			$this->name        = '';
			$this->adr1        = '';
			$this->adr2        = '';
			$this->cp          = '';
			$this->ville       = '';
			$this->pays        = '';
			$this->web         = '';
			$this->link        = '';
			$this->display     = '';
			$this->isbd_entry  = '';
			$this->ed_comment  = '';
		}
	}



	// ---------------------------------------------------------------
	//  get_primaldatafrom($obj) : récupération infos collection à partir d'un collection-objet
	// ---------------------------------------------------------------

	function get_primaldatafrom($obj) {
		$this->id 	= $obj->ed_id;
		$this->name = $obj->ed_name;
		$this->adr1 = $obj->ed_adr1;
		$this->adr2 = $obj->ed_adr2;
		$this->cp 	= $obj->ed_cp;
		$this->ville = $obj->ed_ville;
		$this->pays = $obj->ed_pays;
		$this->web = $obj->ed_web;
		$this->ed_comment = $obj->ed_comment;
	}



	// ---------------------------------------------------------------
	//  get_otherdata() : calcul des données n'appartenant pas à la table
	// ---------------------------------------------------------------

	function get_otherdata() {
		if ($this->web) {
			$this->link = "<a href='$this->web' target='_new'>$this->web</a>";
		} else {
			$this->link = '';
		}
		
		// Détermine le lieu de publication
		$l = '';
		if ($this->adr1)  $l = $this->adr1;
		if ($this->adr2)  $l = ($l=='') ? $this->adr2 : $l.', '.$this->adr2;
		if ($this->cp)    $l = ($l=='') ? $this->cp   : $l.', '.$this->cp;
		if ($this->pays)  $l = ($l=='') ? $this->pays : $l.', '.$this->pays;
		if ($this->ville) $l = ($l=='') ? $this->ville : $this->ville.' ('.$l.')';
		if ($l=='')       $l = '[S.l.]';
		
		// Détermine le nom de l'éditeur
		if ($this->name) $n = $this->name; else $n = '[S.n.]';
		
		// Constitue l'ISBD pour le coupe lieu/éditeur
		if ($l == '[S.l.]' AND $n == '[S.n.]') $this->isbd_entry = '[S.l.&nbsp;: s.n.]';
		else $this->isbd_entry = $l.'&nbsp;: '.$n;
		if ($this->ville) {
			if ($this->pays) $this->display = "$this->ville [$this->pays] : $this->name";
			else $this->display = "$this->ville : $this->name";
		} else {
			$this->display = $this->name;
		}

	}

	// ---------------------------------------------------------------
	//  print_resume($level) : affichage d'informations sur la collection
	// ---------------------------------------------------------------

	function print_resume($level = 2,$css) {
		global $css,$msg;
		if(!$this->id)
			return;

		// adaptation par rapport au niveau de détail souhaité
		switch ($level) {
			// case x :
			case 2 :
			default :
				global $publisher_level2_display;

				$publisher_display = $publisher_level2_display;
				break;
		}

		$print = $publisher_display;

		// remplacement des champs statiques
		$print = str_replace("!!id!!", $this->id, $print);
		$print = str_replace("!!name!!", $this->name, $print);
		$print = str_replace("!!adr1!!", $this->adr1, $print);
		$print = str_replace("!!adr2!!", $this->adr2, $print);
		$print = str_replace("!!cp!!", $this->cp, $print);
		$print = str_replace("!!ville!!", $this->ville, $print);
		$print = str_replace("!!pays!!", $this->pays, $print);
		if ($this->web) $print = str_replace("!!site_web!!", "<a href='$this->web' target='_blank'><img src='./images/globe.gif' border='0' /></a>", $print);
		else $print = str_replace("!!site_web!!", "", $print);
		$print = str_replace("!!isbd!!", $this->isbd_entry, $print);
		$print = str_replace("!!aut_comment!!", $this->ed_comment, $print);


		if (ereg("!!colls!!", $print)) {
			global $dbh;
			$query = "select collection_id, collection_name from collections where collection_parent='".$this->id."' order by index_coll";
			$result = mysql_query($query, $dbh);
			if(mysql_num_rows($result)) {
				$remplacement = $msg[publishers_collections]."\n<ul>\n";
				while ($obj = mysql_fetch_object($result)) {
					$remplacement .= "<li><a href='index.php?lvl=coll_see&id=".$obj->collection_id."'>".$obj->collection_name."</a></li>\n";
				}
				mysql_free_result($result);
				$remplacement .= "</ul>\n";
			} else {
				$remplacement = "";
			}
			$print = str_replace("!!colls!!", $remplacement, $print);
		}

		if (ereg("!!address!!", $print)) {
			if (($this->adr1 != "") && ($this->cp != "") && ($this->ville != "")) {
				$remplacement = $this->adr1;
				if ($this->adr2 != "") $remplacement .= "<br />\n".$this->adr2;
				$remplacement .= "<br />\n".$this->cp." ".$this->ville;
				if ($this->pays != "") $remplacement .= "<br />\n".$this->pays;
			} else {
				$remplacement = "";
			}
			$print = str_replace("!!address!!", $remplacement, $print);
		}

		return $print;
	}

} # fin de définition de la classe éditeur

} # fin de délaration

