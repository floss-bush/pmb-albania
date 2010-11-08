<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categories.class.php,v 1.13 2008-07-24 12:56:08 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/noeuds.class.php");
require_once($class_path."/thesaurus.class.php");

class categories{
	
	
	var $num_noeud;					//Identifiant du noeud de rattachement
	var $langue;
	var $libelle_categorie = '';
	var $note_application = '';
	var $comment_public = '';
	var	$comment_voir = '';
	var $index_categorie = '';

	
	//Constructeur	 
	function categories($num_noeud, $langue) {

		global $dbh;
		$this->num_noeud = $num_noeud;				
		$this->langue = $langue;
		$q = "select count(1) from categories where num_noeud = '".$this->num_noeud."' and langue = '".$this->langue."' ";
		$r = mysql_query($q, $dbh);
		if (mysql_result($r, 0, 0) != 0) {
			$this->load();
		} else {
			$q = "insert into categories set num_noeud = '".$this->num_noeud."', langue = '".$langue."', ";
			$q.= "libelle_categorie = '', note_application = '', comment_public = '', ";
			$q.= "comment_voir = '', index_categorie = '' ";
			$r = mysql_query($q, $dbh);
		} 
	}


	// charge la catégorie à partir de la base si elle existe.
	function load(){
	
		global $dbh;

		$q = "select * from categories where num_noeud = '".$this->num_noeud."' and langue = '".$this->langue."' limit 1";
		$r = mysql_query($q, $dbh);
		$obj = mysql_fetch_object($r);
		$this->libelle_categorie = $obj->libelle_categorie;				
		$this->note_application = $obj->note_application;				
		$this->comment_public = $obj->comment_public;				
		$this->comment_voir = $obj->comment_voir;
		$this->index_categorie = $obj->index_categorie;

	}
	
	// enregistre la catégorie en base.
	function save(){
		
		global $dbh;

		$no = new noeuds($this->num_noeud);
		$num_thesaurus = $no->num_thesaurus; 
		
		$q = "update categories set ";
		$q.= "num_thesaurus = '".$num_thesaurus."', ";
		$q.= "libelle_categorie = '".addslashes($this->libelle_categorie)."', ";
		$q.= "note_application = '".addslashes($this->note_application)."', ";
		$q.= "comment_public = '".addslashes($this->comment_public)."', ";
		$q.= "comment_voir = '".addslashes($this->comment_voir)."', ";
		$q.= "index_categorie = ' ".$this->index_categorie." ' ";
		$q.= "where num_noeud = '".$this->num_noeud."' and langue = '".$this->langue."' "; 
		$r = mysql_query($q, $dbh);
		categories::update_index($this->num_noeud);
		
	}
	
	//verifie si une categorie existe dans la langue concernée
	function exists($num_noeud, $langue) {
	
		global $dbh;
		
		$q = "select count(1) from categories where num_noeud = '".$num_noeud."' and langue = '".$langue."' ";
		$r = mysql_query($q, $dbh);
		if (mysql_result($r, 0, 0) == 0) return FALSE;
			else return TRUE;		
	}
	
	//supprime une categorie en base.
	function delete($num_noeud, $langue) {

		global $dbh;
		
		$q = "delete from categories where num_noeud = '".$num_noeud."' and langue = '".$langue."' ";
		$r = @mysql_query($q, $dbh);
	}		


	//Liste les libelles des ancetres d'une categorie dans la langue concernée 
	//a partir de la racine du thesaurus
	function listAncestorNames($num_noeud=0, $langue='') {
		
		global $dbh;
			
		if(!$num_noeud) {
			$num_noeud = $this->num_noeud;
			$langue = $this->langue;
		}
		$thes = thesaurus::getByEltId($num_noeud);
		$id_list = noeuds::listAncestors($num_noeud);
		$id_list = array_reverse($id_list);
		$lib_list = '';
		
		foreach($id_list as $dummykey=>$id) {
			if (categories::exists($id, $langue)) $lg=$langue; 
			else $lg=$thes->langue_defaut; 
			$q = "select libelle_categorie from categories where num_noeud = '".$id."' ";
			$q.= "and langue = '".$lg."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r))	{
				$lib_list.= mysql_result($r, 0, 0); 
				if ($id != $num_noeud) $lib_list.= ':';
			}
		}
		return $lib_list;
	
	}


	//Retourne un tableau des ancetres d'une categorie dans la langue concernée 
	//a partir de la racine du thesaurus
	function listAncestors($num_noeud=0, $langue) {
		
		global $dbh;
			
		if(!$num_noeud) {
			$num_noeud = $this->num_noeud;
			$langue = $this->langue;
		}
		$thes = thesaurus::getByEltId($num_noeud);
		$id_list = noeuds::listAncestors($num_noeud);
		$id_list = array_reverse($id_list);
		$anc_list = array();

		foreach($id_list as $key=>$id) {
			if (categories::exists($id, $langue)) $lg=$langue; 
			else $lg=$thes->langue_defaut; 
			$q = "select * from noeuds, categories ";
			$q.= "where categories.num_noeud = '".$id."' ";
			$q.= "and categories.langue = '".$lg."' ";
			$q.= "and categories.num_noeud = noeuds.id_noeud ";
			$q.= "limit 1";
			$r = mysql_query($q, $dbh);
			
			while ($row = mysql_fetch_object($r))	{
				$anc_list[$id]['num_noeud'] = $row->num_noeud;
				$anc_list[$id]['num_parent'] = $row->num_parent;
				$anc_list[$id]['num_renvoi_voir'] = $row->num_renvoi_voir;
				$anc_list[$id]['visible'] = $row->visible;
				$anc_list[$id]['num_thesaurus'] = $row->num_thesaurus;
				$anc_list[$id]['langue'] = $row->langue;
				$anc_list[$id]['libelle_categorie'] = $row->libelle_categorie;
				$anc_list[$id]['note_application'] = $row->note_application;
				$anc_list[$id]['comment_public'] = $row->comment_public;
				$anc_list[$id]['comment_voir'] = $row->comment_voir;
				$anc_list[$id]['index_categorie'] = $row->index_categorie;
				$anc_list[$id]['autorite'] = $row->autorite;
			}
		}
		return $anc_list;
	}
	

	//Retourne un resultset des enfants d'une categorie dans la langue concernée 
	function listChilds($num_noeud=0, $langue, $keep_tilde=1, $ordered=0) {
		
		global $dbh;
		global $opac_categories_nav_max_display;	
		if(!$num_noeud) {
			$num_noeud = $this->num_noeud;
			$langue = $this->langue;
		}
		$thes = thesaurus::getByEltId($num_noeud);
		$list = array();
		if($opac_categories_nav_max_display > 0) $limit= " limit $opac_categories_nav_max_display ";
		else $limit='';
		$q = "select ";
		$q.= "catdef.num_noeud, noeuds.autorite, noeuds.num_parent, noeuds.num_renvoi_voir, noeuds.visible, noeuds.num_thesaurus, ";
		$q.= "if (catlg.num_noeud is null, catdef.langue, catlg.langue ) as langue, ";
		$q.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie ) as libelle_categorie, ";
		$q.= "if (catlg.num_noeud is null, catdef.note_application, catlg.note_application ) as note_application, ";
		$q.= "if (catlg.num_noeud is null, catdef.comment_public, catlg.comment_public ) as comment_public, ";
		$q.= "if (catlg.num_noeud is null, catdef.comment_voir, catlg.comment_voir ) as comment_voir, ";
		$q.= "if (catlg.num_noeud is null, catdef.index_categorie, catlg.index_categorie ) as index_categorie ";
		$q.= "from noeuds left join categories as catdef on noeuds.id_noeud=catdef.num_noeud and catdef.langue = '".$thes->langue_defaut."' "; 
		$q.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$langue."' "; 
		$q.= "where ";
		$q.= "noeuds.num_parent = '".$num_noeud."' ";
		if (!$keep_tilde) $q.= "and catdef.libelle_categorie not like '~%' ";
		if ($ordered !== 0) $q.= "order by ".$ordered." ";
		$q.=$limit;
		$r = mysql_query($q, $dbh);

		return $r;
	}

}

?>