<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: thesaurus.class.php,v 1.16 2011-04-06 09:39:12 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/noeuds.class.php");
require_once($class_path."/categories.class.php");

class thesaurus {
		
	var $id_thesaurus = 0;					//Identifiant du thesaurus
	var $libelle_thesaurus = '';
	var $active	= '1';
	var $opac_active = '1';
	var $langue_defaut = 'fr_FR';
	var	$num_noeud_racine = 0; 				//Index du noeud racine du thesaurus
	
	var $num_noeud_orphelins = 0; 			// Index Noeud orphelins du thesaurus
	var $num_noeud_nonclasses = 0; 			// Index Noeud nonclasses du thesaurus 
	 
	// Constructeur.
	function thesaurus($id=0) {
				
		if ($id) {
			$this->id_thesaurus = $id;
			$this->load();
		}
	}
		
	
	// charge le thesaurus à partir de la base.
	function load(){
	
		global $dbh;

		$q = "select * from thesaurus where id_thesaurus = '".$this->id_thesaurus."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->id_thesaurus = $obj->id_thesaurus;
		$this->libelle_thesaurus = $obj->libelle_thesaurus;
		$this->active = $obj->active;
		$this->opac_active = $obj->opac_active;
		$this->langue_defaut = $obj->langue_defaut;
		$this->num_noeud_racine = $obj->num_noeud_racine;
		
		$q = "select id_noeud from noeuds where num_thesaurus = '".$this->id_thesaurus."' and autorite = 'ORPHELINS' ";
		$r = mysql_query($q, $dbh);
		if(mysql_num_rows($r))	$this->num_noeud_orphelins = mysql_result($r, 0, 0);
		else $this->num_noeud_orphelins=0;
		 
		$q = "select id_noeud from noeuds where num_thesaurus = '".$this->id_thesaurus."' and autorite = 'NONCLASSES' ";
		$r = mysql_query($q, $dbh);
		if(mysql_num_rows($r))	$this->num_noeud_nonclasses= mysql_result($r, 0, 0);
		else $this->num_noeud_nonclasses=0;
		
	}
	
	
	// enregistre le thesaurus en base.
	function save(){	
		
		global $dbh;
		global $msg;
		
		if($this->libelle_thesaurus == '') die("Erreur de création thésaurus");
		if($this->langue_defaut == '') $this->langue_defaut='fr_FR';
		
		if($this->id_thesaurus) {	//mise à jour thesaurus
		
			$q = "update thesaurus set libelle_thesaurus = '".$this->libelle_thesaurus."' ";
			$q.= ", active = '".$this->active."' ";
			$q.= ", opac_active = '".$this->opac_active."' ";
			$q.= ", langue_defaut = '".$this->langue_defaut."' ";
			$q.= "where id_thesaurus = '".$this->id_thesaurus."' ";
			$r = mysql_query($q, $dbh);
		
		} else {	//création thesaurus
			
			$q = "insert into thesaurus set libelle_thesaurus = '".$this->libelle_thesaurus."', active = '1', opac_active = '1', langue_defaut = '".$this->langue_defaut."' ";
			$r = mysql_query($q, $dbh);
			$this->id_thesaurus = mysql_insert_id($dbh);

			//creation noeud racine
			$noeud = new noeuds(); 
			$noeud->autorite = 'TOP';
			$noeud->num_parent = 0;
			$noeud->num_renvoi_voir = 0;
			$noeud->visible = '0';
			$noeud->num_thesaurus = $this->id_thesaurus;
			$noeud->save();
			
			$this->num_noeud_racine = $noeud->id_noeud;
			
			//rattachement noeud racine au thesaurus
			$q = "update thesaurus set num_noeud_racine = '".$this->num_noeud_racine."' ";
			$q.= "where id_thesaurus = '".$this->id_thesaurus."' ";
			$r = mysql_query($q, $dbh);
			
			//creation noeud orphelins
			$noeud = new noeuds();
			$noeud->autorite = 'ORPHELINS';
			$noeud->num_parent = $this->num_noeud_racine;
			$noeud->num_renvoi_voir = 0;
			$noeud->visible = '0';
			$noeud->num_thesaurus = $this->id_thesaurus;
			$noeud->save();
			$this->num_noeud_orphelins = $noeud->id_noeud;

			//Creation catégorie orphelins langue par défaut
			$categ = new categories($this->num_noeud_orphelins, $this->langue_defaut);
			$categ->libelle_categorie = $msg["thes_orphelins"];
			$categ->save();						
			
			//creation noeud non classes;		 
			$noeud = new noeuds();
			$noeud->autorite = 'NONCLASSES';
			$noeud->num_parent = $this->num_noeud_racine;
			$noeud->num_renvoi_voir = 0;
			$noeud->visible = '0';
			$noeud->num_thesaurus = $this->id_thesaurus;
			$noeud->save();
			$this->num_noeud_nonclasses = $noeud->id_noeud;
			
			//Creation catégorie non classes langue par défaut
			$categ = new categories($this->num_noeud_nonclasses, $this->langue_defaut);
			$categ->libelle_categorie = $msg["thes_non_classes"];
			$categ->save();						

		}  
	}
	

	// supprime le thesaurus.
	function delete($id_thes=0){	
		
		global $dbh;
		global $msg;
		
		if (!$id_thes) $id_thes = $this->id_thesaurus;
 				
  		$q = "select id_noeud from noeuds where num_thesaurus = '".$id_thes."' ";
  		$r = mysql_query($q, $dbh);
  		while ($row = mysql_fetch_row($r)){
  			noeuds::delete($row[0]);
  			/*$q1 = "delete from categories where num_noeud = '".$row[0]."' ";
  			$r1 = mysql_query($q1, $dbh);
  			$q2 = "delete from noeuds where id_noeud = '".$row[0]."' ";
  			$r2 = mysql_query($q2, $dbh);
  			*/
  		}
  		$q = "delete from thesaurus where id_thesaurus = '".$id_thes."' ";
  		$r = mysql_query($q, $dbh);
 
	}	

	//Retourne un objet thesaurus à partir de l'ID d'un de ses noeuds
	function getByEltId($id_noeud){
		
		global $dbh;
 		
		$q = "select num_thesaurus from noeuds where id_noeud = '".$id_noeud."' ";
		$r = mysql_query($q, $dbh);
		if (mysql_num_rows($r) == 0) return NULL;
		
		$thes = new thesaurus(mysql_result($r, 0, 0));

		return $thes; 						
	}

	//Indique si un thesaurus possede des categories autres que les categories de base (TOP, ORPHELINS, NONCLASSES)
	function hasCateg($id_thes=0){
		
		global $dbh;
		
		if (!$id_thes) $id_thes = $this->id_thesaurus;
 		
		$q = "select count(1) from noeuds where num_thesaurus = '".$id_thes."' ";
		$r = mysql_query($q, $dbh);
		if (mysql_result($r, 0, 0) > 3) return TRUE; 
			else return FALSE;		
		
	}

	//Indique si un thesaurus est utilise pour les notices
	function hasNotices($id_thes=0) {
		
		global $dbh;
 		
 		if (!$id_thes) $id_thes = $this->id_thesaurus;
 		 		
		$q = "select count(1) from notices_categories, noeuds where noeuds.num_thesaurus = '".$id_thes."' ";
		$q.= "and noeuds.id_noeud = notices_categories.num_noeud "; 
		$r = mysql_query($q, $dbh);
		if (mysql_result($r, 0, 0) != 0) return TRUE; 
			else return FALSE;		
		
	}


	//Retourne un tableau des langues affichées dans les thésaurus
	function getTranslationsList() {
		global $dbh;
		
		$q = "select valeur_param from parametres where type_param = 'thesaurus' and sstype_param = 'liste_trad' ";
		$r = mysql_query($q, $dbh);
		$a = explode(',',mysql_result($r, 0, 0));
		return $a;
	}


	//recuperation du thesaurus session
	function getSessionThesaurusId() {
		
		global $thesaurus_defaut;
		global $thesaurus_mode_pmb;
		global $deflt_thesaurus;
		
		if (!$_SESSION["id_thesau"]) { 
			
			//choix du thesaurus à afficher si l'on est pas déjà dans un thesaurus 
			//thesaurus par défaut de l'application en mode monothesaurus
			//thesaurus par defaut de l'utilisateur en mode multithesaurus
			
			switch ($thesaurus_mode_pmb) {	// gestion niveau thesaurus
				case "0" :					// Mono thesaurus
					$id_thes = $thesaurus_defaut;
					$_SESSION["id_thesau"] = $id_thes;
					break;
				case "1" :					// Multi thesaurus
					if (!$deflt_thesaurus) $id_thes = $thesaurus_defaut; 
					else $id_thes = $deflt_thesaurus; 
					$_SESSION["id_thesau"] = $id_thes;
					break;
		
				default :					//mal défini -> Mono thesaurus
					$id_thes = $thesaurus_defaut;
					$_SESSION["id_thesau"] = $id_thes;
				break;
			}
		}
		return $_SESSION["id_thesau"];
	}
	
	
	//définition du thesaurus session
	function setSessionThesaurusId($id_thes) {
	
		$_SESSION["id_thesau"] = $id_thes;
	}


	//recuperation du thesaurus session pour les notices
	function getNoticeSessionThesaurusId() {
		
		global $thesaurus_defaut;
		global $thesaurus_mode_pmb;
		global $deflt_thesaurus;
		
		if (!$_SESSION["notice_id_thes"]) { 
			
			//choix du thesaurus à afficher si l'on est pas déjà dans un thesaurus 
			//thesaurus par défaut de l'application en mode monothesaurus
			//thesaurus par defaut de l'utilisateur en mode multithesaurus
			
			switch ($thesaurus_mode_pmb) {	// gestion niveau thesaurus
		
				case "0" :					// Mono thesaurus
					$id_thes = $thesaurus_defaut;
					$_SESSION["notice_id_thes"] = $id_thes;
					break;
				case "1" :					// Multi thesaurus
					if (!$deflt_thesaurus) $id_thes = $thesaurus_defaut; 
					else $id_thes = $deflt_thesaurus; 
					$_SESSION["notice_id_thes"] = $id_thes;
					break;
				default :					//mal défini -> Mono thesaurus
					$id_thes = $thesaurus_defaut;
					$_SESSION["notice_id_thes"] = $id_thes;
				break;
			}
		}
		return $_SESSION["notice_id_thes"];
	}
	
	
	//définition du thesaurus session pour les notices
	function setNoticeSessionThesaurusId($id_thes) {
	
			$_SESSION["notice_id_thes"] = $id_thes;
	}

	
	//retourne le libelle du thesaurus
	function getLibelle($id_thes=0) {
		
		global $dbh;
		
		if (!$id_thes) {
			return $this->libelle_thesaurus;
		} else {
			$q = "select libelle_thesaurus from thesaurus where id_thesaurus = '".$id_thes." limit 1'";
			$r = mysql_query($q, $dbh);
			return mysql_result($r, 0, 0); 
		} 
	}
	
	
	//retourne la liste des thesaurus dans un tableau associé id_thesaurus=>libelle_thesaurus
	function getThesaurusList(){
		
		global $dbh;
		
		$list_thes = array();
		$q = "select id_thesaurus, libelle_thesaurus from thesaurus where 1";
		$r = mysql_query($q, $dbh);
		while ($row = @mysql_fetch_object($r)){
			$list_thes[$row->id_thesaurus] = $row->libelle_thesaurus;
		}
		return $list_thes;
		
	}

	
}
?>
