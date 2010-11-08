<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_search.class.php,v 1.22 2009-05-16 11:21:58 dbellamy Exp $
//
// Gestion de la recherche des termes dans le thésaurus

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($base_path."/classes/category.class.php");
require_once($class_path."/analyse_query.class.php");
require_once($class_path."/thesaurus.class.php");

class term_search {
	var $id_thes = 0;				//Etendue de la recherche (identifiant thesaurus ou multi-thesaurus si 0)
	var $thes;
	var $search_term_name;			//Nom de la variable contenant le terme recherché dans les catégories
	var $search_term_origin_name;	//Nom de la variable contenant le terme recherché saisi par l'utilisateur
    var $search_term;				//Terme recherché dans les catégories
	var $search_term_oigin;			//Terme recherché saisi par l'utilisateur
    var $n_per_page;				//Nombre de résultats par page
    var $base_query;				//Paramètres supplémentaires à passer dans l'url
    var $url_for_term_show;			//Page à appeller pour l'affichage de la fiche du terme
	var $url_for_term_search;		//Page à appeller pour l'affichage de la liste des termes correspondants à la recherche
	var $offset;					//offset en fonction de la page courante
	var $page;    					//Page courante (récupérée du formulaire)
	var $n_total;					//Nombre de termes total correspondants à la recherche
    var $keep_tilde;				//Affichage ou non des catégories cachées
    var $order;						//Stockage de la clause select de calcul de pertinence
    var $error_message;				//Erreur renvoyée par l'analyse de la chaine
    var $where;						//Clause where après analyse de la chaine
    var $aq;
     
    //Constructeur
    function term_search($search_term_name,$search_term_origin_name,$n_per_page=500,$base_query,$url_for_term_show,$url_for_term_search,$keep_tilde=0,$id_thes=0) {

    	global $page;
    	
		//recuperation du thesaurus session 
		if(!$id_thes) {
			$id_thes = thesaurus::getSessionThesaurusId();
		} else {
			thesaurus::setSessionThesaurusId($id_thes);
		}   	  
		  	
    	$this->search_term_name=$search_term_name;
    	$this->search_term_origin_name=$search_term_origin_name;
    	
    	global $$search_term_name;
    	global $$search_term_origin_name;
    	
    	$this->search_term=stripslashes($$search_term_name);
    	$this->search_term_origin=stripslashes($$search_term_origin_name);
    	
    	$this->n_per_page=$n_per_page;
    	$this->base_query=$base_query;
    	$this->url_for_term_show=$url_for_term_show;
    	$this->url_for_term_search=$url_for_term_search;
    	$this->keep_tilde=$keep_tilde;
		
		$this->id_thes = $id_thes;		
   		if ($id_thes != -1) $this->thes= new thesaurus($id_thes);
    	
    	if ($page=="") $page=0;
    	$this->page=$page;
    	$this->offset=$page*$this->n_per_page;
    	
    	$this->get_term_count();
    }
    
    //Affichage du navigateur de pages
    function page_navigator() {
    	$url_page=$this->url_for_term_search."?".$this->search_term_name."=".rawurlencode($this->search_term)."&".$this->search_term_origin_name."=".rawurlencode($this->search_term_origin);
    	
		if ($this->offset!=0) $navig="<a href=\"$url_page&page=".($this->page-1)."&".$this->base_query."\">&lt;</a>";
		$navig.=" (".($this->offset+1)."-".min($this->offset+$this->n_per_page,$this->n_total).")/".$this->n_total." ";
		if (($this->offset+$this->n_per_page+1)<$this->n_total) $navig.="<a href=\"$url_page&page=".($this->page+1)."&".$this->base_query."\">&gt;</a>";
		return $navig;	
    }
    
    //Récupération du terme where pour la recherche
    function get_where_term() {
    	
    	global $msg;
    	
    	//Si il y a déjà un terme where calculé alors renvoi tout de suite
    	if ($this->where) return $this->where;
    	
    	//Si il y a un terme saisi alors close where
		if ($this->search_term) {
			$this->error_message="";
			$aq=new analyse_query($this->search_term);
			if (!$aq->error) {
				$members=$aq->get_query_members("categories","libelle_categorie","index_categorie","num_noeud");
				$where_term = "and ".$members["where"];
				$this->order = $members["select"];
				$this->where = $where_term;
				$this->aq = $aq;
			} else {
				$this->error_message=sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message);
			}
		}
		return $where_term;
    }
    
    //Récupération du nombre de termes correspondants à la recherche
    function get_term_count() {
    	
    	global $lang;
    	global $thesaurus_mode_pmb;
    	global $dbh;    	
    	
    	//Comptage du nombre de termes
    	$where_term=$this->get_where_term();
		if ($where_term) {
			$members_catdef = $this->aq->get_query_members("catdef", "catdef.libelle_categorie", "catdef.index_categorie", "catdef.num_noeud");
			$members_catlg = $this->aq->get_query_members("catlg", "catlg.libelle_categorie", "catlg.index_categorie", "catlg.num_noeud");
		}

		if ($this->id_thes != -1){	//1 seul thesaurus
				
			if ( ($thesaurus_mode_pmb!='1') || ($lang==$this->thes->langue_defaut) || (in_array($lang, thesaurus::getTranslationsList())===false) ) { 	//Recherche dans la langue par défaut du thesaurus

				$q = "select count(distinct libelle_categorie) ";
				$q.= "from categories as catdef ";
				$q.= "where 1 ";
				if ($where_term) $q.= "and ".$members_catdef["where"]." ";
				$q.= "and catdef.num_thesaurus = '".$this->id_thes."' ";
				$q.= "and catdef.langue = '".$this->thes->langue_defaut."' "; 
				$q.= "and catdef.libelle_categorie not like '~%' ";
				
				$r = mysql_query($q);
				$this->n_total=mysql_result($r, 0, 0);
			
			} else {		//Recherche dans la langue de l'interface ou dans la langue par défaut du thesaurus

				$q = "drop table if exists cattmp ";
				$r = mysql_query($q, $dbh);
	
				$q1 = "create temporary table cattmp engine=myisam select ";
				$q1.= "if(catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle ";
				$q1.= "from categories as catdef "; 
				$q1.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' "; 
				$q1.= "where 1 ";
				if ($where_term) $q1.= "and if(catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catlg["where"].") ";
				$q1.= "and catdef.num_thesaurus = '".$this->id_thes."' ";
				$q1.= "and catdef.langue = '".$this->thes->langue_defaut."' "; 
				$q1.= "and catdef.libelle_categorie not like '~%' ";
				$r1 = mysql_query($q1, $dbh);
	
				$q2 = "select count(distinct categ_libelle) from cattmp ";
				$r2 = mysql_query($q2);
	
				$this->n_total=mysql_result($r2, 0, 0);
			}
			
		} else {

			//tous les thesaurus
			//on recherche dans la langue de l'interface ou dans la langue par défaut du thesaurus
			$q = "drop table if exists cattmp ";
			$r = mysql_query($q, $dbh);

			$q1 = "create temporary table cattmp engine=myisam select ";
			$q1.= "id_thesaurus, ";
			$q1.= "if(catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle ";
			$q1.= "from thesaurus ";
			$q1.= "left join categories as catdef on id_thesaurus=catdef.num_thesaurus and catdef.langue=thesaurus.langue_defaut ";
			$q1.= "left join categories as catlg on catdef.num_noeud=catlg.num_noeud and catlg.langue = '".$lang."' ";
			$q1.= "where 1 ";
			if ($where_term) $q1.= "and (if(catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catlg["where"].") ) ";
			$q1.= "and catdef.libelle_categorie not like '~%' ";
			$resultat1 = mysql_query($q1, $dbh);
			
			$q2 = "select count(distinct id_thesaurus,categ_libelle) from cattmp ";					
		  	$r2=mysql_query($q2);
		 	$this->n_total=mysql_result($r2,0,0);
		}

}
    
    
    //Affichage de la liste des résultats
    function show_list_of_terms() {
    	
    	global $charset;
    	global $msg;
    	global $lang;
    	global $dbh;
    	global $thesaurus_mode_pmb;
    	
    	//Si il y a eu erreur lors de la première analyse...
    	if ($this->error_message) {
    		return $this->error_message;
    	}
    	
		//Recherche des termes correspondants à la requête
		$where_term=$this->get_where_term();
		if($where_term) {
			$members_catdef = $this->aq->get_query_members("catdef", "catdef.libelle_categorie", "catdef.index_categorie", "catdef.num_noeud");
			$members_catlg = $this->aq->get_query_members("catlg", "catlg.libelle_categorie", "catlg.index_categorie", "catlg.num_noeud");
		}

		if ($this->id_thes != -1){		//1 seul thesaurus
			
			if ( ($thesaurus_mode_pmb!='1') || ($lang==$this->thes->langue_defaut) || (in_array($lang, thesaurus::getTranslationsList())===false) ) { 	//Recherche dans la langue par défaut du thesaurus

				$requete = "select count(num_noeud) as nb, ";
				$requete.= "num_thesaurus, ";
				$requete.= "num_noeud as categ_id, ";
				$requete.= "libelle_categorie as categ_libelle, ";
				$requete.= "catdef.index_categorie as indexcat ";
				if ($where_term) $requete.= ", ".$members_catdef["select"]." as pert ";
				$requete.= "from categories as catdef "; 
				$requete.= "where 1 ";
				if ($where_term) $requete.= "and ".$members_catdef["where"]." ";
				$requete.= "and num_thesaurus = '".$this->id_thes."' ";
				$requete.= "and catdef.langue = '".$this->thes->langue_defaut."' ";
				$requete.= "and catdef.libelle_categorie not like '~%' ";
				$requete.= "group by categ_libelle ";
				$requete.= "order by ";
				if ($where_term) $requete.= "pert desc, ";
				$requete.= "indexcat asc ";
				$requete.= "limit ".$this->offset.",".$this->n_per_page;
			
			} else {		//Recherche dans la langue de l'interface ou dans la langue par défaut du thesaurus

				$requete = "select count(catdef.num_noeud) as nb, ";
				$requete.= "catdef.num_thesaurus, ";
				$requete.= "catdef.num_noeud as categ_id, ";
				$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie ) as categ_libelle, ";
				$requete.= "if (catlg.num_noeud is null, catdef.index_categorie , catlg.index_categorie ) as indexcat ";
				if ($where_term) $requete.= ", if (catlg.num_noeud is null, ".$members_catdef["select"].", ".$members_catlg["select"].") as pert ";
				$requete.= "from categories as catdef "; 
				$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
				$requete.= "where 1 ";
				if ($where_term) $requete.= "and (if(catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catlg["where"].") ) ";
				$requete.= "and catdef.num_thesaurus = '".$this->id_thes."' ";
				$requete.= "and catdef.langue = '".$this->thes->langue_defaut."' ";
				$requete.= "and catdef.libelle_categorie not like '~%' ";
				$requete.= "group by categ_libelle ";
				$requete.= "order by ";
				if ($where_term) $requete.= "pert desc, ";
				$requete.= "indexcat asc ";
				$requete.= "limit ".$this->offset.",".$this->n_per_page;
				
			}
			
		} else {
			
			//tous les thesaurus
			//on recherche dans la langue de l'interface ou dans la langue par défaut du thesaurus

			$requete = "select count(catdef.num_noeud) as nb, ";
			$requete.= "catdef.num_thesaurus, ";
			$requete.= "catdef.num_noeud as categ_id, ";
			$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie , catlg.libelle_categorie ) as categ_libelle, ";
			$requete.= "if (catlg.num_noeud is null, catdef.index_categorie , catlg.index_categorie ) as indexcat ";
			if ($where_term) $requete.= ", if (catlg.num_noeud is null, ".$members_catdef["select"].", ".$members_catlg["select"].") as pert ";
			$requete.= "from thesaurus ";
			$requete.= "left join categories as catdef on id_thesaurus=catdef.num_thesaurus and catdef.langue=thesaurus.langue_defaut ";
			$requete.= "left join categories as catlg on catdef.num_noeud=catlg.num_noeud and catlg.langue = '".$lang."' ";
			if ($where_term) $requete.= "where if(catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catlg["where"].") ";
			$requete.= "group by categ_libelle, catdef.num_thesaurus ";
			$requete.= "order by ";
			if ($where_term) $requete.= "pert desc, ";
			$requete.= "catdef.num_thesaurus, indexcat asc ";
			$requete.= "limit ".$this->offset.",".$this->n_per_page;
		}

		$resultat=mysql_query($requete, $dbh);


		$res='<b>';
		if ($this->search_term!='') $res.=$msg['term_search_found_term'].'<i>'.htmlentities($this->search_term_origin,ENT_QUOTES,$charset); else $res.='<i>'.$msg['term_search_all_terms'];
		$res.="</i></b>\n";

		//Navigateur de page
		$res.=$this->page_navigator();

		//Affichage des termes trouvés
		$class='colonne2';
		while ($r=mysql_fetch_object($resultat)) {
			$show=1;
			
			//S'il n'y a qu'un seul résultat, vérification que ce n'est pas un terme masqué
			if (($r->nb==1)&&(!$this->keep_tilde)) {

				$t_test = new category($resultat->categ_id);

				if (($t_test->catalog_form[0]=='~')&&(!$t_test->voir_id)) $show=0;
			}
			if ($show) {
				$res.="<div class='".$class."' >";
				if ($r->nb>1) $nbre_termes ='('.$r->nb.') ';
					else  $nbre_termes ='' ;
				
				$res.= $nbre_termes."<a href=\"".$this->url_for_term_show.'?term='.rawurlencode($r->categ_libelle).'&id_thes='.$r->num_thesaurus.'&'.$this->base_query."\" target=\"term_show\" >";
				if ($this->id_thes == -1) {	 //le nom du thesaurus n'est pas affiché si 1 seul thesaurus
					$res.= '['.htmlentities(addslashes(thesaurus::getLibelle($r->num_thesaurus)),ENT_QUOTES,$charset).'] ';
				}
				$res.= htmlentities($r->categ_libelle,ENT_QUOTES,$charset)."</a>\n";
				$res.="<br />\n";
				$res.='</div>';
				if ($class=='colonne2') $class='colonne_suite'; else $class='colonne2';
			}
		}
		if ($class=='colonne_suite') $res.="<div class=\"colonne_suite\"></div>\n";
		return $res;
    }
}
?>
