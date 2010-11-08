<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_show.class.php,v 1.14 2009-05-16 10:52:43 dbellamy Exp $
//
// Gestion de l'affichage d'un notice d'un terme du thésaurus

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/category.class.php");
require_once($class_path."/thesaurus.class.php");

class term_show {

	var $base_query;				//Paramètres supplémentaires passés dans les URL
	var $term;						//Terme à afficher
	var $parent_link;				//Nom de la fonction à appeller pour afficher les liens d'action à côté des catégories
	var $url_for_term_show;			//URL a rappeller
	var $keep_tilde;
	var $id_thes = 0;
	var $thes;
	
    function term_show($term,$url_for_term_show,$base_query,$parent_link,$keep_tilde=0, $id_thes) {
    	$this->base_query=$base_query;
    	$this->term=$term;
    	$this->parent_link=$parent_link;
    	$this->url_for_term_show=$url_for_term_show;
    	$this->keep_tilde=$keep_tilde;
    	$this->id_thes = $id_thes;
  		$this->thes = new thesaurus($id_thes); 
    }
    
    function has_child($categ_id) {
//		$requete="select count(1) from categories where categ_parent=$categ_id";
		$requete = "select count(1) from noeuds where num_parent = '".$categ_id."' ";
		$resultat=mysql_query($requete);
		return mysql_result($resultat,0,0);
	}

	//Récupération du chemin
	function get_categ_lib_($categ_id) {
		global $charset;
		
		$re='';
	
		//Instanciation de la catégorie
		$r=new category($categ_id);
	
		//Récupération du chemin
		for ($i=0; $i<count($r->path_table); $i++) {
			if ($re!='') $re.=' - ';
			//Si la catégorie ne commence pas par "~", on affiche le libelle avec un lien pour la recherche sur le terme, sinon on affiche ~
			if (($r->path_table[$i]['libelle'][0]!='~')||($this->keep_tilde))
				$re.="<a href=\"".$this->url_for_term_show.'?term='.rawurlencode($r->path_table[$i]['libelle']).'&id_thes='.$this->id_thes.'&'.$this->base_query."\">".htmlentities($r->path_table[$i]['libelle'],ENT_QUOTES,$charset).'</a>';
			else
				$re.='~';
		}
		if ($re!='') $re.=' - ';
		//Si le libellé de la catégorie ne commence pas par "~", on affiche le libellé avec un lien sinon ~
		if (($r->libelle[0]!='~')||($this->keep_tilde))
			$re.="<a href=\"".$this->url_for_term_show.'?term='.rawurlencode($r->libelle).'&id_thes='.$this->id_thes.'&'.$this->base_query."\">".htmlentities($r->libelle,ENT_QUOTES,$charset).'</a>';
		else $re.='~';
		return $re;
	}

	function get_categ_lib($categ_id, $categ_libelle) {
		global $charset;
		
		$r=new category($categ_id);
		if ($r->parent_id) {
			$path=$this->get_categ_lib_($r->parent_id);
		}
		if ($r->libelle!=$categ_libelle) {
			$re="<a href=\"".$this->url_for_term_show.'?term='.rawurlencode($r->libelle).'&id_thes='.$this->id_thes.'&'.$this->base_query."\">".htmlentities($r->libelle,ENT_QUOTES,$charset).'</a>';
			if ($path) $re.=' <font size=1>('.$path.')</font>';
		} else {
			if ($path) $re=$path;
		}
		return $re;
	}

	function is_same_lib($categ_libelle,$categ_id) {
		$r=new category($categ_id);
		if ($r->libelle==$categ_libelle) return true; else return false;
	}

	function show_tree($categ_id,$prefixe,$level,$max_level) {

		global $charset;
		global $msg;
		global $lang;
		global $dbh;
		
		$pl=$this->parent_link;
		global $$pl;
		
		$res='';
		
		if ($this->has_child($categ_id)) {
			if ($level<($max_level)) {

				$requete = "select catdef.num_noeud as categ_id, ";
				$requete.= "noeuds.num_renvoi_voir as categ_see, ";
				$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle, ";
				$requete.= "noeuds.num_parent as categ_parent, ";
				$requete.= "if (catlg.num_noeud is null, catdef.note_application, catlg.note_application) as categ_comment, ";
				$requete.= "if (catlg.num_noeud is null, catdef.index_categorie, catlg.index_categorie) as index_categorie ";
				$requete.= "from noeuds left join categories as catdef on noeuds.id_noeud=catdef.num_noeud and catdef.langue = '".$this->thes->langue_defaut."' "; 
				$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' "; 
				$requete.= "where ";
				$requete.= "noeuds.num_thesaurus = '".$this->id_thes."' ";
				$requete.= "and noeuds.num_parent = '".$categ_id."' ";
				$requete.= "order by categ_libelle ";

				$resultat_2=mysql_query($requete);
				while ($r2=mysql_fetch_object($resultat_2)) {
					$visible=$pl($r2->categ_id,$r2->categ_see);
					if ($visible["VISIBLE"]) {
						$res.='<font size=2>'.$visible['LINK'].'&nbsp;'.$prefixe." - <a href=\"".$this->url_for_term_show.'?term='.rawurlencode($r2->categ_libelle).'&id_thes='.$this->id_thes.'&'.$this->base_query."\">".htmlentities($r2->categ_libelle,ENT_QUOTES,$charset).'</a></font>';
						if ($r2->categ_see) {
							$res.='<br /><font size=1>&nbsp;&nbsp;<i>'.$msg['term_show_see'].' '.$this->get_categ_lib($r2->categ_see,$r2->categ_libelle);
							if ($this->is_same_lib($r2->categ_libelle,$r2->categ_see)) $res.=' - '.htmlentities($r2->categ_libelle,ENT_QUOTES,$charset);
							$res.='</i></font>';
						}
						$res.='<br />';
					}
					$res.=$this->show_tree($r2->categ_id,$prefixe." - <a href=\"".$this->url_for_term_show.'?term='.rawurlencode($r2->categ_libelle).'&id_thes='.$this->id_thes.'&'.$this->base_query."\">".htmlentities($r2->categ_libelle,ENT_QUOTES,$charset).'</a>',$level+1,$max_level);
				}
			}
		}
		return $res;
	}

	function get_level($categ_id) {
		$l=0;
		$parent=new category($categ_id);
		$l=count($parent->path_table);
		return $l;
	}

	function show_notice() {
		global $history;
		global $charset;
		global $msg;
		global $dbh;
		global $lang;
			
		$pl=$this->parent_link;
		global $$pl;

		$res='';
		
		if ($history!='') {
			$res.="<a href=\"".$this->url_for_term_show.'?term='.rawurlencode(stripslashes($history)).'&id_thes='.$this->id_thes.'&'.$this->base_query."\">&lt;</a>&nbsp;";
		}

		//Récupération des catégories ayant le même libellé

		$requete = "select catdef.num_noeud as categ_id, ";
		$requete.= "noeuds.num_parent as categ_parent, ";
		$requete.= "noeuds.num_renvoi_voir as categ_see, ";
		$requete.= "noeuds.num_thesaurus, ";
		$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle, ";
		$requete.= "if (catlg.num_noeud is null, catdef.note_application, catlg.note_application ) as categ_comment, ";
		$requete.= "if (catlg.num_noeud is null, catdef.index_categorie, catlg.index_categorie ) as index_categorie ";
		$requete.= "from noeuds left join categories as catdef on noeuds.id_noeud=catdef.num_noeud and catdef.langue = '".$this->thes->langue_defaut."' "; 
		$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' "; 
		$requete.= "where ";
		$requete.= "noeuds.num_thesaurus = '".$this->id_thes."' ";
		$requete.= "and (if (catlg.num_noeud is null, catdef.libelle_categorie = '".addslashes($this->term)."', catlg.libelle_categorie = '".addslashes($this->term)."') ) ";
		$requete.= "order by categ_libelle ";

		$resultat_1=mysql_query($requete);

		//Initialisation du tableau des renvois (permet d'éviter d'afficher deux fois un même renvoi)
		$t_see=array();

		//Pour chaque catégorie ayant le même libellé
		while ($r1=mysql_fetch_object($resultat_1)) {

			//Lecture du chemin vers la catégorie
			$renvoi=$this->get_categ_lib($r1->categ_id,$this->term).' ';
	
			//Si la catégorie est une sous catégorie d'une terme "~", alors c'est un renvoi d'un terme orphelin ou on en tient pas compte
			if (($renvoi[0]=='~')&&($r1->categ_see)&&(!$this->keep_tilde)) {
				//Si le renvoi n'existe pas déjà, on l'affiche et on l'enregistre
				if (!$t_see[$r1->categ_see]) {
					$visible=$pl($r1->categ_id,$r1->categ_see);
					if ($visible['VISIBLE'])
						$res.=$visible['LINK'].'&nbsp;<i>'.$msg['term_show_see'].' </i>'.$this->get_categ_lib($r1->categ_see,$this->tem).'<br />';
					$t_see[$r1->categ_see]=1;
				}
			} else {
				if (($renvoi[0]!='~')||($this->keep_tilde)) {
					//Si la catégorie n'est pas une sous catégorie d'un terme "~", on affiche le chemin
					$visible=$pl($r1->categ_id,$r1->categ_see);
					if ($visible['VISIBLE']) {
						$res.=$visible['LINK'].'&nbsp;'.$renvoi.' - <b>'.$this->term.'</b><br />';
						//Si il y a un renvoi, on l'affiche
						if ($r1->categ_see) {
							$res.='<i><font size=1>&nbsp;&nbsp;Voir : '.$this->get_categ_lib($r1->categ_see,$r1->categ_libelle);
							//Si c'est le même libellé, on l'ajoute au chemin parent, sans lien
							if ($this->is_same_lib($r1->categ_libelle,$r1->categ_see)) $res.=' - '.htmlentities($r1->categ_libelle,ENT_QUOTES,$charset);
							$res.='</font></i><br />';
						}
					}
				}
			}
			
			//Si le renvoi ne commence pas par "~" alors on affiche les sous niveaux et les catégories associées
			if (($renvoi[0]!='~')||($this->keep_tilde)) {
				//Affichage des premiers sous niveaux
				$res.='<blockquote>';
				//Recherche du niveau de la catégorie (0,1 ou supérieur à 1)
				$l=$this->get_level($r1->categ_id);
				//Si le niveau est supérieur à 1, on affiche que deux sous niveaux sinon 3
				if ($l>1) $max_level=3; else $max_level=2;
		
				//Affichage des n sous premiers niveaux
				$res.=$this->show_tree($r1->categ_id,$this->term,0,$max_level);
				$res.='</blockquote>';
				
				//Recherche des catégories associées
				$requete = "select count(1) from voir_aussi where voir_aussi.num_noeud_orig = '".$r1->categ_id."' ";
				$nta=mysql_result(mysql_query($requete),0,0);
				//Si il y en a
				if ($nta) {
					$res.='<blockquote>';

					$requete = "select distinct noeuds.id_noeud as categ_id, ";
					$requete.= "noeuds.num_renvoi_voir as categ_see, ";
					$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle ";
					$requete.= "from voir_aussi left join noeuds on noeuds.id_noeud=voir_aussi.num_noeud_dest ";
					$requete.= "left join categories as catdef on noeuds.id_noeud=catdef.num_noeud and catdef.langue = '".$this->thes->langue_defaut."' "; 
					$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' "; 
					$requete.= "where ";
					$requete.= "voir_aussi.num_noeud_orig = '".$r1->categ_id."' ";
					$requete.= "order by categ_libelle ";

					$resultat_ta=mysql_query($requete);

					$first=1;
					$res1='';
					while ($r_ta=mysql_fetch_object($resultat_ta)) {
						$visible=$pl($r_ta->categ_id,$r_ta->categ_see);
						if ($visible['VISIBLE']) {
							if (!$first) $res1.=", "; else $first=0;
							$res1.=$visible['LINK']."&nbsp;<a href=\"".$this->url_for_term_show.'?term='.rawurlencode($r_ta->categ_libelle).'&id_thes='.$this->id_thes.'&'.$this->base_query."\">".htmlentities($r_ta->categ_libelle,ENT_QUOTES,$charset).'</a>';
						}
					}
					if ($res1!='') $res.='<font size=2><i>'.$msg['term_show_see_also'].'<blockquote>'.$res1.'</blockquote></i></font>';
					$res.='</blockquote>';
				}
			}
		}
		$res.='</blockquote>';
		return $res;
	}
}
?>
