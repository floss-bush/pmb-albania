<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestion_source.class.php,v 1.1 2009-07-31 14:37:05 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class suggestion_source{
	
	var $id_source=0;
	var $libelle_source='';
	
	/*
	 * Constructeur
	 */
	function suggestion_source($id=0){
		global $dbh;
		
		$this->id_source = $id;
		
		if(!$this->id_source){
			
			$this->libelle_source = '';
		} else {
			$req="select libelle_source from suggestions_source where id_source='".$this->id_source."'";
			$res = mysql_query($req,$dbh);
			$src = mysql_fetch_object($res);
			$this->libelle_source = $src->libelle_source;
		}
	}
}
?>