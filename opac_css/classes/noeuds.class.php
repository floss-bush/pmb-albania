<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: noeuds.class.php,v 1.7 2008-11-03 15:52:33 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/thesaurus.class.php");

class noeuds{
	
	
	var $id_noeud = 0;				//Identifiant du noeud 
	var $autorite = '';
	var $num_parent = 0;
	var $num_renvoi_voir = 0;
	var $visible = '1';
	var	$num_thesaurus = 0;				//Identifiant du thesaurus de rattachement

	 
	//Constructeur.	 
	function noeuds($id=0) {
		
		global $dbh;
	
		if ($id) {
			$this->id_noeud = $id;
			$this->load();	
		}
	}	
	
	
	// charge le noeud à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from noeuds where id_noeud = '".$this->id_noeud."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->id_noeud = $obj->id_noeud;
		$this->autorite = $obj->autorite;
		$this->num_parent = $obj->num_parent;
		$this->num_renvoi_voir = $obj->num_renvoi_voir;
		$this->visible = $obj->visible;
		$this->num_thesaurus = $obj->num_thesaurus;
		$this->path = $obj->path;
	
	}

	
	// enregistre le noeud en base.
	function save(){
		
		global $dbh;
		
		if (!$this->num_thesaurus) die ('Erreur de création noeud');
		
		if ($this->id_noeud) {	//Mise à jour noeud
			
			$q = 'update noeuds set autorite =\''.addslashes($this->autorite).'\', ';
			$q.= 'num_parent = \''.$this->num_parent.'\', num_renvoi_voir = \''.$this->num_renvoi_voir.'\', ';
			$q.= 'visible = \''.$this->visible.'\', num_thesaurus = \''.$this->num_thesaurus.'\' ';
			$q.= 'where id_noeud = \''.$this->id_noeud.'\' ';
			mysql_query($q, $dbh);

		} else {
			
			$q = 'insert into noeuds set autorite = \''.addslashes($this->autorite).'\', ';
			$q.= 'num_parent = \''.$this->num_parent.'\', num_renvoi_voir = \''.$this->num_renvoi_voir.'\', ';
			$q.= 'visible = \''.$this->visible.'\', num_thesaurus = \''.$this->num_thesaurus.'\' ';
			mysql_query($q, $dbh);
			$this->id_noeud = mysql_insert_id($dbh);
		}

		// Mis à jour du path de lui-meme, et de tous les fils
		$thes = thesaurus::getByEltId($this->id_noeud);

		$id_top = $thes->num_noeud_racine;
		$path='';		
		$id_tmp=$this->id_noeud;
		while (true) {
			$q = "select num_parent from noeuds where id_noeud = '".$id_tmp."' limit 1";
			$r = mysql_query($q, $dbh);
			$id_tmp= $id_cur = mysql_result($r, 0, 0);
			print $id_tmp." ";
			if (!$id_cur || $id_cur == $id_top) break;
			if($path) $path='/'.$path;
			$path=$id_tmp.$path;			
		}
		$this->process_categ_path($this->id_noeud,$path);
	}
	
	function process_categ_path($id_noeud=0, $path='') {
		global $dbh;

		if(!$id_noeud) $id_noeud = $this->id_noeud; 	
		
		if($path) $path.='/';
		$path.=$id_noeud;
		
		$res = noeuds::listChilds($id_noeud, 0);
		while (($row = mysql_fetch_object ($res))) {
			// la categorie a des filles qu'on va traiter
			$this->process_categ_path ($row->id_noeud,$path);
		}		
		$req="update noeuds set path='$path' where id_noeud='$id_noeud' ";
		mysql_query($req,$dbh);		
	}
		

	//fonctions !!!

	//supprime un noeud et toutes ses références
	function delete($id_noeud=0) {
		
		global $dbh;
		
		if(!$id_noeud) $id_noeud = $this->id_noeud; 	

		// Supprime les categories.
		$q = "delete from categories where num_noeud = '".$id_noeud."' ";
		mysql_query($q, $dbh);
		
		// Supprime les renvois voir_aussi vers ce noeud. 
		$q= "delete from voir_aussi where num_noeud_dest = '".$id_noeud."' ";
		mysql_query($q, $dbh);
		
		// Supprime les renvois voir_aussi depuis ce noeud. 
		$q= "delete from voir_aussi where num_noeud_orig = '".$id_noeud."' ";
		mysql_query($q, $dbh);
		
		// Supprime les associations avec des notices. 
		$q= "delete from notices_categories where num_noeud = '".$id_noeud."' ";
		mysql_query($q, $dbh);

		// Supprime le noeud.
		$q = "delete from noeuds where id_noeud = '".$id_noeud."' ";
		mysql_query($q, $dbh);
				
	}


	// recherche si une autorite existe deja dans un thesaurus, 
	// et retourne le noeud associe
	function searchAutorite($num_thesaurus, $autorite) {
		
		global $dbh;
		
		$q = "select id_noeud from noeuds where num_thesaurus = '".$num_thesaurus."' ";
		$q.= "and autorite = '".addslashes($autorite)."' limit 1";
		$r = mysql_query($q, $dbh);
		if (mysql_num_rows($r) == 0) return FALSE;
		$noeud = new noeuds(mysql_result($r, 0, 0));
		return $noeud;
	}
	
	
	//recherche si un noeud a des fils
	function hasChild($id_noeud=0) {
	
		global $dbh;

		if(!$id_noeud) $id_noeud = $this->id_noeud; 	
		$q = "select count(1) from noeuds where num_parent = '".$id_noeud."' ";
		$r = mysql_query($q, $dbh);
		return mysql_result($r, 0, 0);
	}	

		
	//recherche si un noeud est le renvoi voir d'un autre noeud.
	function isTarget($id_noeud=0) {
		
		global $dbh;
		
		if(!$id_noeud) $id_noeud = $this->id_noeud; 
		$q = "select count(1) from noeuds where num_renvoi_voir = '".$id_noeud."' ";
		$r = mysql_query($q, $dbh);
		return mysql_result($r, 0, 0);
	}		


	//Indique si un noeud est protégé (noeuds ORPHELINS et NONCLASSES).
	function isProtected($id_noeud=0) {
		
		global $dbh;
		
		if(!$id_noeud) $id_noeud = $this->id_noeud; 
		$q = "select autorite from noeuds where id_noeud = '".$id_noeud."' ";
		$r = mysql_query($q, $dbh);
		$a = mysql_result($r, 0, 0);
		if( $a == 'ORPHELINS' || $a == 'NONCLASSES') return TRUE;
		else return FALSE;
	}		


	//Liste les ancetres d'un noeud et les retourne sous forme d'un tableau 
	function listAncestors($id_noeud=0) {
		
		global $dbh;
		if(!$id_noeud) {
			$id_noeud = $this->id_noeud;
			$path= $this->path;
		} else {
			$q = "select path from noeuds where id_noeud = '".$id_noeud."' ";
			$r = mysql_query($q, $dbh);
			$path=mysql_result($r, 0, 0);			
		}
		if ($path){ 
			$id_list=explode('/',$path);
			krsort($id_list);
			return $id_list;		
		}		
		if(!$id_noeud) {
			$id_noeud = $this->id_noeud;
		}
		$thes = thesaurus::getByEltId($id_noeud);

		$id_top = $thes->num_noeud_racine;
		$i = 0;		
		$id_list[$i] = $id_noeud;
		while (($id_list[$i] != $id_top)&&($id_list[$i]!=0)) {
			$q = "select num_parent from noeuds where id_noeud = '".$id_list[$i]."' limit 1";
			$r = mysql_query($q, $dbh);
			$i++;
			$id_list[$i] = mysql_result($r, 0, 0);
		}
		return $id_list;		
	}
		
}
?>