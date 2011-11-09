<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serie.class.php,v 1.8 2011-02-02 20:08:44 gueluneau Exp $

// définition de la classe de gestion des 'titres de séries'

if ( ! defined( 'SERIE_CLASS' ) ) {
  define( 'SERIE_CLASS', 1 );

require_once($base_path.'/includes/templates/serie.tpl.php');
  
class serie {

	// ---------------------------------------------------------------
	//  propriétés de la classe
	// ---------------------------------------------------------------

	var $id       = 0;        // MySQL serie_id in table 'series'
	var $name     = '';       // serie name
	var $index    = '';       // serie form for index


	// ---------------------------------------------------------------
	//  série($id) : constructeur
	// ---------------------------------------------------------------

	function serie($id) {
		// on regarde si on a une série-objet ou un id de série
		if (is_object($id)) {
			$this->get_primaldatafrom($id);
		} else {
			$this->id = $id;
			$this->get_primaldata();
		}
	}



	// ---------------------------------------------------------------
	//  get_primaldata() : récupération infos subcollection à partir de l'id
	// ---------------------------------------------------------------

	function get_primaldata() {
		global $dbh;
		$requete = "SELECT * FROM series WHERE serie_id='".addslashes($this->id)."' ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$obj = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->get_primaldatafrom($obj);
		} else {
			// pas de collection avec cette clé
			$this->id     = 0;
			$this->name   = '';
			$this->index  = '';
		}
	}



	// ---------------------------------------------------------------
	//  get_primaldatafrom($obj) : récupération infos collection à partir d'un collection-objet
	// ---------------------------------------------------------------

	function get_primaldatafrom($obj) {
		$this->id = $obj->serie_id;
		$this->name = $obj->serie_name;
		$this->index = $obj->serie_index;
	}

	// ---------------------------------------------------------------
	//  print_resume($level) : affichage d'informations sur la série
	// ---------------------------------------------------------------

	function print_resume($level = 2,$css) {
		global $css;
		if(!$this->id) return;

		// adaptation par rapport au niveau de détail souhaité
		switch ($level) {
			// case x :
			case 2 :
			default :
				global $serie_level2_display;
				$publisher_display = $serie_level2_display;
				break;
			}

		$print = $publisher_display;

		// remplacement des champs statiques
		$print = str_replace("!!id!!", $this->id, $print);
		$print = str_replace("!!name!!", $this->name, $print);

		return $print;
		}

} # fin de définition de la classe serie

} # fin de délaration

