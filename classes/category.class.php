<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: category.class.php,v 1.30 2009-04-02 17:43:39 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des 'auteurs'
if ( ! defined( 'CATEGORY_CLASS' ) ) {
  define( 'CATEGORY_CLASS', 1 );
require_once("$class_path/thesaurus.class.php");

//Renvoi récursivement la liste des notices référançant un noeuds et ses enfants
function get_category_notice_count($node_id, &$listcontent) {
	//On ajoute les notices du noeuds
	$asql = "SELECT notcateg_notice FROM notices_categories WHERE num_noeud = ".$node_id;
	$ares = mysql_query($asql);
	while ($arow=mysql_fetch_row($ares)) {
		$listcontent[] = $arow[0];
	}

	//Et on recurse		
	$asql = "SELECT id_noeud FROM noeuds WHERE num_parent = ".$node_id;
	$ares = mysql_query($asql);
	while ($arow=mysql_fetch_row($ares)) {
		get_category_notice_count($arow[0], &$listcontent);
	}
}

class category {
	
// ---------------------------------------------------------------
//		propriétés de la classe
// ---------------------------------------------------------------
var $id=0;
var $libelle='';
var $commentaire='';
var $catalog_form=''; // forme pour affichage complet
var $isbd_entry_lien_gestion=''; // pour affichage avec lien vers la gestion
var $parent_id=0;
var $parent_libelle = '';
var $voir_id=0;
var $has_child=FALSE;
var $has_parent=FALSE;
var $path_table;	// tableau contenant le path éclaté (ids et libellés)
var $associated_terms; // tableau des termes associés
var $thes;		//le thesaurus d'appartenance

// ---------------------------------------------------------------
//		category($id) : constructeur
// ---------------------------------------------------------------
function category($id=0) {
	if($id) {
		// on cherche à atteindre une notice existante
		$this->id = $id;
		$this->thes = thesaurus::getByEltId($id);
		$this->getData();
	} else {
		// la notice n'existe pas
		$this->id = 0;
		$this->getData();
	}
}

// ---------------------------------------------------------------
//		getData() : récupération des propriétés
// ---------------------------------------------------------------
function getData() {
	global $dbh;
	global $lang;
	global $opac_url_base, $use_opac_url_base;
	global $thesaurus_categories_show_only_last ; // le paramètre pour afficher le chemin complet ou pas
	$anti_recurse=array();
	
	if(!$this->id) return;

	$requete = "SELECT noeuds.id_noeud as categ_id, ";
	$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle, ";
	$requete.= "noeuds.num_parent as categ_parent, ";
	$requete.= "noeuds.num_renvoi_voir as categ_see, ";
	$requete.= "if (catlg.num_noeud is null, catdef.note_application, catlg.note_application) as categ_comment ";
	$requete.= "FROM noeuds left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = '".$this->thes->langue_defaut."' ";
	$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
	$requete.= "where noeuds.id_noeud = '".$this->id."' limit 1 ";

	$result = mysql_query($requete, $dbh);	
	if(!mysql_num_rows($result)) return;
	
	$data = mysql_fetch_object($result);
	$this->id = $data->categ_id;
	$id_top = $this->thes->num_noeud_racine;
	$this->libelle = $data->categ_libelle;
	$this->commentaire = $data->categ_comment;
	$this->parent_id = $data->categ_parent;
	$this->voir_id = $data->categ_see;
	//$anti_recurse[$this->voir_id]=1;
	if($this->parent_id != $id_top) $this->has_parent = TRUE;

	$requete = "SELECT 1 FROM noeuds WHERE num_parent='".$this->id."' limit 1";
	$result = @mysql_query($requete, $dbh);
	if (mysql_num_rows($result)) $this->has_child = TRUE;


	// constitution du chemin
	$anti_recurse[$this->id]=1;
	if ($this->has_parent) {
		// si notre catégorie a un parent, on initie la boucle en le récupérant
		/* $requete = "SELECT noeuds.id_noeud as categ_id, ";
		$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle, ";
		$requete.= "noeuds.num_parent as categ_parent, ";
		$requete.= "noeuds.num_renvoi_voir as categ_see, ";
		$requete.= "if (catlg.num_noeud is null, catdef.note_application, catlg.note_application) as categ_comment ";
		$requete.= "FROM noeuds left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = '".$this->thes->langue_defaut."' ";
		$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
		$requete.= "where noeuds.id_noeud = '".$this->parent_id."' limit 1 ";
		

		ER 12/08/2008 NOUVELLE VERSION OPTIMISEE DESSOUS : */
		$requete = "select id_noeud as categ_id, num_noeud, num_parent as categ_parent, libelle_categorie as categ_libelle,	num_renvoi_voir as categ_see, note_application as categ_comment,if(langue = '".$lang."',2, if(langue= '".$this->thes->langue_defaut."' ,1,0)) as p
			FROM noeuds, categories where id_noeud ='".$this->parent_id."' 
			AND noeuds.id_noeud = categories.num_noeud 
			order by p desc limit 1";
		
		$result=@mysql_query($requete);
		if (mysql_num_rows($result)) {
			$parent = mysql_fetch_object($result);
			$anti_recurse[$parent->categ_id]=1;
			$this->path_table[] = array(
						'id' => $parent->categ_id,
						'libelle' => $parent->categ_libelle,
						'commentaire' => $parent->categ_comment);
			// on remonte les ascendants
			while (($parent->categ_parent != $id_top) &&(!$anti_recurse[$parent->categ_parent])) {
				/*
				$requete = "SELECT noeuds.id_noeud as categ_id, ";
				$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle, ";
				$requete.= "noeuds.num_parent as categ_parent, ";
				$requete.= "noeuds.num_renvoi_voir as categ_see, ";
				$requete.= "if (catlg.num_noeud is null, catdef.note_application, catlg.note_application) as categ_comment ";
				$requete.= "FROM noeuds left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = '".$this->thes->langue_defaut."' ";
				$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
				$requete.= "where noeuds.id_noeud = '".$parent->categ_parent."' limit 1 ";

				ER 12/08/2008 NOUVELLE VERSION OPTIMISEE DESSOUS : */
				$requete = "select id_noeud as categ_id, num_noeud, num_parent as categ_parent, libelle_categorie as categ_libelle,	num_renvoi_voir as categ_see, note_application as categ_comment,if(langue = '".$lang."',2, if(langue= '".$this->thes->langue_defaut."' ,1,0)) as p
					FROM noeuds, categories where id_noeud ='".$parent->categ_parent."' 
					AND noeuds.id_noeud = categories.num_noeud 
					order by p desc limit 1";
				$result=@mysql_query($requete);
				if (mysql_num_rows($result)) {
					$parent = mysql_fetch_object($result);
					$anti_recurse[$parent->categ_id]=1;
					$this->path_table[] = array(
								'id' => $parent->categ_id,
								'libelle' => $parent->categ_libelle,
								'commentaire' => $parent->categ_comment);
				} else {
					break;
				}
			}
		} else {
			$this->path_table=array();
		}
	} else {
		$this->path_table = array();
	}
	
	// ceci remet le tableau dans l'ordre général->particulier
	$this->path_table = array_reverse($this->path_table);

	if ($thesaurus_categories_show_only_last) {
		$this->catalog_form = $this->libelle;
		
		// si notre catégorie a un parent, on initie la boucle en le récupérant
		/*
		$requete_temp = "SELECT noeuds.id_noeud as categ_id, ";
		$requete_temp.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle ";
		$requete_temp.= "FROM noeuds left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = '".$this->thes->langue_defaut."' ";
		$requete_temp.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
		$requete_temp.= "where noeuds.id_noeud = '".$this->parent_id."' limit 1 ";

		ER 12/08/2008 NOUVELLE VERSION OPTIMISEE DESSOUS : */
		$requete_temp = "select id_noeud as categ_id, num_noeud, num_parent as categ_parent, libelle_categorie as categ_libelle,	num_renvoi_voir as categ_see, note_application as categ_comment,if(langue = '".$lang."',2, if(langue= '".$this->thes->langue_defaut."' ,1,0)) as p
			FROM noeuds, categories where id_noeud ='".$this->parent_id."' 
			AND noeuds.id_noeud = categories.num_noeud 
			order by p desc limit 1";
		
		$result_temp=@mysql_query($requete_temp);
		if (mysql_num_rows($result_temp)) {
			$parent = mysql_fetch_object($result_temp);
			$this->parent_libelle = $parent->categ_libelle ;
		} else $this->parent_libelle ; 

	} elseif(sizeof($this->path_table)) {
		while(list($i, $l) = each($this->path_table)) {
			$temp_table[] = $l['libelle'];
		}
		$this->parent_libelle = join(':', $temp_table);
		$this->catalog_form = $this->parent_libelle.':'.$this->libelle;
	} else {
		$this->catalog_form = $this->libelle;
	}

	// Ajoute un lien sur la fiche catégorie si l'utilisateur à accès aux autorités, ou bien en envoi en OPAC.
	if ($use_opac_url_base) $url_base_lien_aut = $opac_url_base."index.php?&lvl=categ_see&id=" ;
	else $url_base_lien_aut="./autorites.php?categ=categories&sub=categ_form&id=";
	if (SESSrights & AUTORITES_AUTH || $use_opac_url_base) $this->isbd_entry_lien_gestion = "<a href='".$url_base_lien_aut.$this->id."' class='lien_gestion'>".$this->catalog_form."</a>";
	else $this->isbd_entry_lien_gestion = $this->catalog_form;
	
	//Recherche des termes associés
	$requete = "select count(1) from categories where num_noeud = '".$this->id."' and langue = '".$lang."' ";
	$result = mysql_query($requete, $dbh);
	if (mysql_result($result, 0,0) == 0) $lg = $this->thes->langue_defaut ; 
	else $lg = $lang;  

	$requete = "SELECT distinct voir_aussi.num_noeud_dest as categ_assoc_categassoc, ";
	$requete.= "categories.libelle_categorie as categ_libelle, categories.note_application as categ_comment ";
	$requete.= "FROM voir_aussi, categories ";
	$requete.= "WHERE voir_aussi.num_noeud_orig='".$this->id."' ";
	$requete.= "AND categories.num_noeud=voir_aussi.num_noeud_dest "; 
	$requete.= "AND categories.langue = '".$lg."' ";

	$result=@mysql_query($requete,$dbh);
	while ($ta=mysql_fetch_object($result)) {

		//Recherche des renvois réciproques
		$requete1 = "select count(1) from voir_aussi where num_noeud_orig = '".$ta->categ_assoc_categassoc."' and num_noeud_dest = '".$this->id."' ";
		if (mysql_result(mysql_query($requete1, $dbh), 0, 0)) $rec=1;
		else $rec=0;
		
		$this->associated_terms[] = array(
			'id' => $ta->categ_assoc_categassoc,
			'libelle' => $ta->categ_libelle,
			'commentaire' => $ta->categ_comment,
			'rec' => $rec);
	}	 
}

function has_notices() {
	global $dbh;
	global $thesaurus_auto_postage_montant,$thesaurus_auto_postage_descendant,$thesaurus_auto_postage_nb_montant,$thesaurus_auto_postage_nb_descendant;
	global $thesaurus_auto_postage_etendre_recherche,$nb_level_enfants,$nb_level_parents;
	$thesaurus_auto_postage_descendant = $thesaurus_auto_postage_montant=0;
	// Autopostage actif
	if ($thesaurus_auto_postage_descendant || $thesaurus_auto_postage_montant ) {
		if(!isset($nb_level_enfants)) {
			// non defini, prise des valeurs par défaut
			if(isset($_SESSION["nb_level_enfants"]) && $thesaurus_auto_postage_etendre_recherche) $nb_level_descendant=$_SESSION["nb_level_enfants"];
			else $nb_level_descendant=$thesaurus_auto_postage_nb_descendant;
		} else {
			$nb_level_descendant=$nb_level_enfants;
		}				
		
		// lien Etendre auto_postage
		if(!isset($nb_level_parents)) {
			// non defini, prise des valeurs par défaut
			if(isset($_SESSION["nb_level_parents"]) && $thesaurus_auto_postage_etendre_recherche) $nb_level_montant=$_SESSION["nb_level_parents"];
			else $nb_level_montant=$thesaurus_auto_postage_nb_montant;
		} else {
			$nb_level_montant=$nb_level_parents;
		}	
		$_SESSION["nb_level_enfants"]=	$nb_level_descendant;
		$_SESSION["nb_level_parents"]=	$nb_level_montant;
		
		$q = "select path from noeuds where id_noeud = '".$this->id."' ";
		$r = mysql_query($q);
		$path=mysql_result($r, 0, 0);
		$nb_pere=substr_count($path,'/');
		// Si un path est renseigné et le paramètrage activé			
		if ($path && ($thesaurus_auto_postage_descendant || $thesaurus_auto_postage_montant || $thesaurus_auto_postage_etendre_recherche) && ($nb_level_montant || $nb_level_descendant)){
			
			//Recherche des fils 
			if(($thesaurus_auto_postage_descendant || $thesaurus_auto_postage_etendre_recherche)&& $nb_level_descendant) {
				if($nb_level_descendant != '*' && is_numeric($nb_level_descendant))
					$liste_fils=" path regexp '^$path(\\/[0-9]*){0,$nb_level_descendant}$' ";
				else 
					$liste_fils=" path regexp '^$path(\\/[0-9]*)*' ";
			} else {
				$liste_fils=" id_noeud='".$this->id."' ";
			}
					
			// recherche des pères
			if(($thesaurus_auto_postage_montant || $thesaurus_auto_postage_etendre_recherche) && $nb_level_montant) {
				
				$id_list_pere=explode('/',$path);			
				$stop_pere=0;
				if($nb_level_montant != '*' && is_numeric($nb_level_montant)) $stop_pere=$nb_pere-$nb_level_montant;
				for($i=$nb_pere;$i>=$stop_pere; $i--) {
					$liste_pere.= " or id_noeud='".$id_list_pere[$i]."' ";
				}
			}			
			// requete permettant de remonter les notices associées à la liste des catégories trouvées;
			$suite_req = " FROM noeuds inner join notices_categories on id_noeud=num_noeud inner join notices on notcateg_notice=notice_id 
				WHERE ($liste_fils $liste_pere) and notices_categories.notcateg_notice = notices.notice_id ";					
		} else {	
			// cas normal d'avant		
			$suite_req=" FROM notices_categories, notices WHERE notices_categories.num_noeud = '".$this->id."' and notices_categories.notcateg_notice = notices.notice_id ";
		}	
	
		$query ="SELECT COUNT(1) ".$suite_req;
	} else {
		// Autopostage désactivé	
		$query ="SELECT COUNT(1) FROM notices_categories WHERE notices_categories.num_noeud='".$this->id."' ";
		
	}	 
	$result = mysql_query($query, $dbh);
	return (mysql_result($result, 0, 0));
	}

	function notice_count($include_subcategories=true) {
		/*
		 * $include_subcategories : Inclue également les notices dans les catégories filles
		 */
		if (!$include_subcategories) {
			$asql = "SELECT notcateg_notice FROM notices_categories WHERE num_noeud = ".$this->id;
			$ares = mysql_query($asql);
			while ($arow=mysql_fetch_row($ares)) {
				$listcontent[] = $arow[0];
			}
			$notice_count = count($listcontent);
			return $notice_count;
		}
		else {
			$listcontent = array();
			get_category_notice_count($this->id, $listcontent);
			$listcontent = array_unique($listcontent); //S'agirait pas d'avoir deux fois la même notice comptée.
			$notice_count = count($listcontent);
			return $notice_count;
		}
	}
	
	
} # fin de définition de la classe category

} # fin de déclaration
