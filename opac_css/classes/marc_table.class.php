<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: marc_table.class.php,v 1.17 2010-06-16 12:13:48 ngantier Exp $

// classe de gestion des tables MARC en XML

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

if ( ! defined( 'MARC_TABLE_CLASS' ) ) {
  define( 'MARC_TABLE_CLASS', 1 );

require_once("$class_path/XMLlist.class.php");

class marc_list {

// propriétés

	var $table;
	var $parser;

// méthodes

	// constructeur
	function marc_list($type) {
		global $lang;
		global $charset;
		global $include_path;
		switch($type) {
			case 'country':
				$parser = new XMLlist("$include_path/marc_tables/$lang/country.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'icondoc':
				$parser = new XMLlist("$include_path/marc_tables/icondoc.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'icondoc_big':
				$parser = new XMLlist("$include_path/marc_tables/icondoc_big.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'lang':
				$parser = new XMLlist("$include_path/marc_tables/$lang/lang.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'doctype':
				$parser = new XMLlist("$include_path/marc_tables/$lang/doctype.xml", 0);
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'recordtype':
				$parser = new XMLlist("$include_path/marc_tables/$lang/recordtype.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'function':
				$parser = new XMLlist("$include_path/marc_tables/$lang/function.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'literal_function':
				$parser = new XMLlist("$include_path/marc_tables/$lang/literal_function.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'section_995':
				$parser = new XMLlist("$include_path/marc_tables/$lang/section_995.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'typdoc_995':
				$parser = new XMLlist("$include_path/marc_tables/$lang/typdoc_995.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;			
			case 'codstatdoc_995':
				$parser = new XMLlist("$include_path/marc_tables/$lang/codstat_995.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;			
			case 'diacritique':
			// Armelle : a priori plus utile
				$parser = new XMLlist("$include_path/marc_tables/diacritique.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case 'nivbiblio':
				$parser = new XMLlist("$include_path/marc_tables/$lang/nivbiblio.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;			
			case 'relationtypeup':
				$parser = new XMLlist("$include_path/marc_tables/$lang/relationtypeup.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;		
			case 'relationtypedown':
				$parser = new XMLlist("$include_path/marc_tables/$lang/relationtypedown.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case "etat_demandes":
				$parser = new XMLlist("$include_path/marc_tables/$lang/etat_demandes.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;
			case "type_actions":
				$parser = new XMLlist("$include_path/marc_tables/$lang/type_actions_demandes.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;	
			case 'relationtype_aut':
				$parser = new XMLlist("$include_path/marc_tables/$lang/relationtype_aut.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;	
			case 'relationtype_autup':
				$parser = new XMLlist("$include_path/marc_tables/$lang/relationtype_autup.xml");
				$parser->analyser();
				$this->table = $parser->table;
				break;	
			default:
				$this->table=array();
				break;
		}
	}

}

class marc_select {

// propriétés

	var $display;

// méthodes

	// constructeur


	function marc_select($type, $name='mySelector', $selected='')
	{
		$source = new marc_list($type);
		$this->display = "<select name='$name'>";

		if($selected) {
			foreach($source->table as $value=>$libelle) {
				if(!($value == $selected))
					$tag = "<option value='$value'>";
				else
					$tag = "<option value='$value' SELECTED>";

				$this->display .= "$tag$libelle</option>";
			}

		} else {

			// cirque à cause d'un bug d'IE
			reset($source->table);
			$this->display .= "<option value='".key($source->table)."' SELECTED>";
			$this->display .= pos($source->table).'</option>';

			while(next($source->table)) {
				$this->display .= "<option value='".key($source->table)."'>";
				$this->display .= pos($source->table).'</option>';
			}

		}
		$this->display .= "</select>";

	}
}

} # fin de déclaration
