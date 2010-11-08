<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: noeuds.class.php,v 1.18 2010-06-16 12:13:47 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/thesaurus.class.php");
require_once($class_path."/category.class.php");
require_once($include_path."/templates/category.tpl.php");
require_once("$include_path/user_error.inc.php");
require_once("$include_path/misc.inc.php");

class noeuds{
	
	
	var $id_noeud = 0;				//Identifiant du noeud 
	var $autorite = '';
	var $num_parent = 0;
	var $num_renvoi_voir = 0;
	var $visible = '1';
	var	$num_thesaurus = 0;			//Identifiant du thesaurus de rattachement
	 
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
		$req="update noeuds set path='$path' where id_noeud=$id_noeud";
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
		
		// liens entre autorités 
		require_once("$class_path/aut_link.class.php");
		$aut_link= new aut_link(AUT_TABLE_CATEG,$id_noeud);
		$aut_link->delete();
				
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


	//Indique si un noeud est protégé (TOP, ORPHELINS et NONCLASSES).
	function isProtected($id_noeud=0) {
		
		global $dbh;
		
		if(!$id_noeud) $id_noeud = $this->id_noeud; 
		$q = "select autorite from noeuds where id_noeud = '".$id_noeud."' ";
		$r = mysql_query($q, $dbh);
		$a = mysql_result($r, 0, 0);
		if( $a=='TOP' || $a=='ORPHELINS' || $a=='NONCLASSES') return TRUE;
			else return FALSE;
	}		


	//Indique si un noeud est racine (non modifiable).
	function isRacine($id_noeud=0) {
		
		global $dbh;
		
		if (!$id_noeud) return FALSE;
		$q = "select * from thesaurus where num_noeud_racine = '".$id_noeud."' limit 1 ";
		$r = mysql_query($q, $dbh);
		if( mysql_num_rows($r)) return TRUE;
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
		$thes = thesaurus::getByEltId($id_noeud);

		$id_top = $thes->num_noeud_racine;
		$i = 0;		
		$id_list[$i] = $id_noeud;
		while (true) {
			$q = "select num_parent from noeuds where id_noeud = '".$id_list[$i]."' limit 1";
			$r = mysql_query($q, $dbh);
			$id_cur = mysql_result($r, 0, 0);
			if (!$id_cur || $id_cur == $id_top) break;
			$i++;
			$id_list[$i] = mysql_result($r, 0, 0);
		}
		return $id_list;		
	}
	
	
	//Liste les enfants d'un noeud sous forme de resultset (si $renvoi=0, ne retourne pas les noeuds renvoyés)
	function listChilds($id_noeud=0, $renvoi=0) {
	
		global $dbh;

		if(!$id_noeud) $id_noeud = $this->id_noeud; 	
		$q = "select id_noeud from noeuds where num_parent = '".$id_noeud."' ";
		$q.= "and autorite not in ('ORPHELINS', 'NONCLASSES') ";
		if (!$renvoi) $q.= "and num_renvoi_voir = '0' ";
		$r = mysql_query($q, $dbh);
		return $r;
	}	
	
	//recherche si un noeud est utilisé dans une notice.
	function isUsedInNotices($id_noeud=0) {
		
		global $dbh;
		
		if(!$id_noeud) $id_noeud = $this->id_noeud; 
		$q = "select count(1) from notices_categories where num_noeud = '".$id_noeud."' ";
		$r = mysql_query($q, $dbh);
		return mysql_result($r, 0, 0);
	}		


	//recherche si un noeud est utilisé dans la table voir_aussi.
	function isUsedInSeeAlso($id_noeud=0) {
		
		global $dbh;
		
		if(!$id_noeud) $id_noeud = $this->id_noeud; 
		$q = "select count(1) from voir_aussi where num_noeud_orig = '".$id_noeud."' ";
		$q.= "or num_noeud_dest = '".$id_noeud."' ";
		$r = mysql_query($q, $dbh);
		return mysql_result($r, 0, 0);
	}		

	
	//optimization de la table noeuds
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE noeuds', $dbh);
		return $opt;
				
	}
	
	//vérification de l'unicité du numéro d'autorité dans le thésaurus
	function isUnique($num_thesaurus, $num_aut='', $id_noeud=0) {
		
		global $dbh;
		if ($num_aut=='') return true;
		$q = 'select count(1) from noeuds where num_thesaurus=\''.$num_thesaurus.'\' ';
		$q.= 'and autorite=\''.addslashes($num_aut).'\' ';
		if ($id_noeud) $q.= 'and id_noeud != \''.$id_noeud.'\' ';
		$r = mysql_query($q, $dbh);
		if(mysql_result($r, 0, 0)==0) return true;
			else return false;
	}
	
	// ---------------------------------------------------------------
	//		replace_categ_form : affichage du formulaire de remplacement
	// ---------------------------------------------------------------
	function replace_categ_form($parent=0) {
		global $form_categ_replace;
		global $thesaurus_mode_pmb;
		global $msg;
		
		if(!$this->id_noeud) {
			error_message($msg[161], $msg[162], 1, "./autorites.php?categ=categories&sub=&parent=".$parent."&id=0");//Voir éventuelement pour mettre un message valable quand le cas se présentera
			return false;
		}
		
		$categ = new category($this->id_noeud);
		if ($thesaurus_mode_pmb) $nom_tesaurus='['.$categ->thes->getLibelle().'] ' ;
		else $nom_tesaurus='' ;
		$form_categ_replace=str_replace('!!old_categ_libelle!!',$nom_tesaurus.$categ->catalog_form, $form_categ_replace);
		$form_categ_replace=str_replace('!!id!!',$this->id_noeud, $form_categ_replace);
		$form_categ_replace=str_replace('!!parent!!',$this->num_parent, $form_categ_replace);
		print pmb_bidi($form_categ_replace);
		return true;
	}		
	
	// ---------------------------------------------------------------
	//		replace : Remplacement d'un noeud du thésaurus par un autre
	// ---------------------------------------------------------------
	function replace($by=0) {
		global $msg,$dbh;
		if (($this->id_noeud == $by) || (!$this->id_noeud) || (!$by))  {
			return $msg["categ_imposible_remplace_elle_meme"];
		}
		
		$noeuds_a_garder = new noeuds($by);
		
		//Si les noeuds sont du même thésaurus
		if($noeuds_a_garder->num_thesaurus == $this->num_thesaurus){
			//On déplace les catégories qui renvoi vers l'ancien noeuds pour qu'elle renvoie vers le nouveau
			if($this->isTarget()){
				$requete="UPDATE noeuds SET num_renvoi_voir='".$by."' WHERE num_renvoi_voir='".$this->id_noeud."' and id_noeud!='".$by."' ";
				@mysql_query($requete, $dbh);
			}
			//On garde les liens voir_aussi
			$requete="UPDATE ignore voir_aussi SET num_noeud_orig='".$by."' WHERE num_noeud_orig='".$this->id_noeud."' and num_noeud_dest!='".$by."' ";
			@mysql_query($requete, $dbh);
			$requete="UPDATE ignore voir_aussi SET num_noeud_dest='".$by."' WHERE num_noeud_dest='".$this->id_noeud."' and num_noeud_orig!='".$by."'";
			@mysql_query($requete, $dbh);
		}
		
		if($this->isTarget()){//Si le noeuds à supprimé est utilisé pour des renvois et qu'il reste des liens on les supprime
			//On supprime les renvoies
			$requete="UPDATE noeuds SET num_renvoi_voir='0' WHERE num_renvoi_voir='".$this->id_noeud."'";
			@mysql_query($requete, $dbh);
		}
		
		//On déplace les notices liées
		$requete= "UPDATE ignore notices_categories SET num_noeud='".$by."' where num_noeud = '".$this->id_noeud."' ";
		@mysql_query($requete, $dbh);
		
		//On supprime le noeuds
		$this->delete();
		
		return "";
	}
}
?>