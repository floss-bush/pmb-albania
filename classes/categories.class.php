<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categories.class.php,v 1.24.2.1 2011-05-12 09:21:51 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/noeuds.class.php");
require_once($class_path."/thesaurus.class.php");
require_once($class_path."/notice.class.php");

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
		global $msg;
		global $include_path;
		
		$no = new noeuds($this->num_noeud);
		$num_thesaurus = $no->num_thesaurus; 
		
		//On teste si la categorie existe à ce niveau 
		//$categ_exist = $this->searchLibelle($this->libelle_categorie,$num_thesaurus,$this->langue,$no->num_parent);
		//if ($categ_exist != 0) {
			//require_once("$include_path/user_error.inc.php");
			//warning($msg['create_category'],$msg['create_category_double']);
			//on supprime les relations dans la base
			//$this->delete($this->num_noeud,$this->langue);
			//$no->delete($this->num_noeud);
			//return FALSE;
		//} else {		
			$q = "update categories set ";
			$q.= "num_thesaurus = '".$num_thesaurus."', ";
			$q.= "libelle_categorie = '".addslashes($this->libelle_categorie)."', ";
			$q.= "note_application = '".addslashes($this->note_application)."', ";
			$q.= "comment_public = '".addslashes($this->comment_public)."', ";
			$q.= "comment_voir = '".addslashes($this->comment_voir)."', ";
			$q.= "index_categorie = ' ".trim($this->index_categorie)." ' ";
			$q.= "where num_noeud = '".$this->num_noeud."' and langue = '".$this->langue."' "; 
			$r = mysql_query($q, $dbh);
			categories::update_index($this->num_noeud);
			$this->update_index_path_word();
		//}
	}
	
	function update_index_path_word(){
		global $dbh;
		global $msg;
		global $include_path;	
		global $thesaurus_auto_postage_search;
		global $thesaurus_auto_postage_search_nb_descendant,$thesaurus_auto_postage_search_nb_montant;	

		/*	auto_postage_descendant:
		* 		Soit categ : Europe:France:Sarthe
		* 		et une notice sous la categ Sarthe.
		* 	la recherche tous champs de Europe va sortir la notice sous la categ Sarthe 
		*/				 
		$no = new noeuds($this->num_noeud);
		$num_thesaurus = $no->num_thesaurus;	
		$path=$no->path;	
		$liste_num_noeud=explode('/',$path);
		// pour l'index coté gestion
		$lib_list=array();	
		if($thesaurus_auto_postage_search){	
			$limit=$thesaurus_auto_postage_search_nb_descendant;		
			if($limit){				
				$liste_num_noeud=explode('/',$path);
				if($limit != '*') array_splice($liste_num_noeud,0,count($liste_num_noeud)-$limit-1);
				$select_num_noeud=implode(',',$liste_num_noeud);
				$q = "select libelle_categorie from categories where num_noeud in( $select_num_noeud ) and langue = '".$this->langue."' and num_thesaurus=$num_thesaurus";
				$r = mysql_query($q, $dbh);
				while ($row = mysql_fetch_object($r))	{
					$lib_list[]= $row->libelle_categorie; 
				}
			}
		}		
		
		/*	auto_postage_montant:
		 * 		Soit categ : Europe:France:Sarthe
		 * 		et une notice sous la categ Europe.
		 * 	la recherche tous champs de Sarthe va sortir la notice sous la categ Europe 
		 */ 
		$liste_fils="";
		if($thesaurus_auto_postage_search){	
			$limit=$thesaurus_auto_postage_search_nb_montant;		
			if($limit){	
				if( is_numeric($limit))
					$liste_fils=" path regexp '^$path(\\/[0-9]*){0,$limit}$' ";
				elseif($limit == '*') 
					$liste_fils=" (path like '$path/%' or  path = '$path') ";	
				if($liste_fils)	{
					$q = "select libelle_categorie from categories,noeuds where id_noeud=num_noeud
					and $liste_fils and langue = '".$this->langue."' and categories.num_thesaurus=$num_thesaurus and noeuds.num_thesaurus=$num_thesaurus";
					$r = mysql_query($q, $dbh);
					while ($row = mysql_fetch_object($r))	{
						$lib_list[]= $row->libelle_categorie; 
					}
				}			
			}				
		}
				
		// Si rien, on ne met que le libelle de la categ
		if(!count($lib_list))$lib_list[]=$this->libelle_categorie;
		//$lib_list=array_unique  ($lib_list);
		$index=implode(" ",$lib_list);		
		$clean_index=strip_empty_words($index);
		
		$q = "update categories set ";
		$q.= "path_word_categ = ' ".trim(addslashes($index))." ', ";		
		$q.= "index_path_word_categ = ' ".trim(addslashes($clean_index))." ' ";
		$q.= "where num_noeud = '".$this->num_noeud."' and langue = '".$this->langue."' and num_thesaurus=$num_thesaurus"; 
		$r = mysql_query($q, $dbh);		
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
	function listAncestorNames($num_noeud=0, $langue) {
		
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
	function listAncestors($num_noeud=0, $langue='') {
		
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
			
		if(!$num_noeud) {
			$num_noeud = $this->num_noeud;
			$langue = $this->langue;
		}
		$thes = thesaurus::getByEltId($num_noeud);
		$list = array();

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
		$r = mysql_query($q, $dbh);

		return $r;
	}


	//optimization de la table categories
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE categories', $dbh);
		return $opt;
		
	}			


	//recherche $libelle dans les libellés de la table categories et retourne 0 si non trouvé
	//sinon retourne identifiant de categorie
	function searchLibelle($libelle, $id_thesaurus=0, $lg=0, $num_parent=0) {
		
		global $dbh;
		global $lang;
		global $thesaurus_defaut;
		
		if (!$lg) $lg = $lang;
		if (!$id_thesaurus) $id_thesaurus = $thesaurus_defaut;
		
		$q = "select id_noeud from noeuds, categories where 1 ";
		if ($id_thesaurus != -1) $q.= "and noeuds.num_thesaurus = '".$id_thesaurus."' ";
		if ($num_parent) $q.= "and noeuds.num_parent = '".$num_parent."' ";
		if ($lg != -1) $q.= "and categories.langue = '".$lg."' ";
		$q.= "and categories.libelle_categorie = '".$libelle."' ";
		$q.= "and noeuds.id_noeud = categories.num_noeud ";
		$q.= "limit 1";
		$r = mysql_query($q, $dbh); 
		if (mysql_num_rows($r)) return mysql_result($r, 0, 0);
			else return 0;
		
	}

		
	//---------------------------------------------------------------
	// update_index($id) : maj des n-uplets la table notice_global_index 
	// en rapport avec cette actégorie	
	//---------------------------------------------------------------
	function update_index($id) {
		global $dbh;
		// On cherche tous les n-uplet de la table notice correspondant à cette catégorie.
		$found = mysql_query("select distinct notcateg_notice from notices_categories where num_noeud='".$id ."' ",$dbh);
		// Pour chaque n-uplet trouvés on met a jour la table notice_global_index avec l'auteur modifié :
		$num = mysql_num_rows($found);
	   	for($j=0;$j < $num; $j++) {
	   		$mesNotices = mysql_fetch_object($found);
	   		$notice_id = $mesNotices->notcateg_notice;
			notice::majNoticesGlobalIndex($notice_id);
			notice::majNoticesMotsGlobalIndex($notice_id,'subject');
	   	}
	}
	
	function getlibelle($num_noeud=0, $langue=""){
		global $dbh;
		$lib="";
		if(!$num_noeud) {
			$num_noeud = $this->num_noeud;
			$langue = $this->langue;
		}
		$thes = thesaurus::getByEltId($num_noeud);
		if (categories::exists($num_noeud, $langue)) $lg=$langue; 
		else $lg=$thes->langue_defaut; 
		$q = "select libelle_categorie from categories where num_noeud = '".$num_noeud."' ";
		$q.= "and langue = '".$lg."' limit 1";
		$r = mysql_query($q, $dbh);
		if (mysql_num_rows($r))	{
			$lib= mysql_result($r, 0, 0); 
		}
		
		return $lib;
	}

}
?>