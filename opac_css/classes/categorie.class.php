<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categorie.class.php,v 1.21 2010-01-26 13:56:38 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// OPAC. Classe d'affichage des catégories

require_once ($base_path.'/classes/thesaurus.class.php');
require_once ($base_path.'/classes/noeuds.class.php');
require_once ($base_path.'/classes/categories.class.php');

class categorie {
	
	var $id				= 	0;		// id de la catégorie
	var $libelle		=	'';		// libellé de la catégorie
	var $parent			=	0;		// id parent
	var $voir			=	0;		// id renvoi
	var $has_child		= 	0;		// nombre d'enfants de la catégorie
	var $has_notices	=	0;		// nombre de notices utilisant la catégorie
	var $thes;						// thésaurus lié à la catégorie 

	
	// constructeur
	function categorie($id) {
		$this->id = $id;
		if ($id) $this->get_data();
	}


	function get_data() {
		
		global $dbh;
		global $categorie_separator;
		global $lang;
		
		// on récupère les infos de la catégorie
	
		$this->thes = thesaurus::getByEltId($this->id); 
		if (categories::exists($this->id, $lang)) $lg=$lang; else $lg=$this->thes->langue_defaut;
			
		$query = "select ";
		$query.= "categories.libelle_categorie,categories.note_application, categories.comment_public, ";
		$query.= "noeuds.num_parent, noeuds.num_renvoi_voir ";
		$query.= "from noeuds, categories ";
		$query.= "where categories.langue = '".$lg."' "; 
		$query.= "and noeuds.id_noeud = '".$this->id."' ";
		$query.= "and noeuds.id_noeud = categories.num_noeud ";
		$query.= "limit 1";
		$result = mysql_query($query, $dbh);
		
		$current = mysql_fetch_object($result);
		$this->libelle 	= $current->libelle_categorie;
		$this->parent	= $current->num_parent;
		$this->voir		= $current->num_renvoi_voir;
		$this->note		= $current->note_application;
		$this->comment  = $current->comment_public;
		
			
		// on regarde si la catégorie à des enfants
		$query = "select count(1) from noeuds where num_parent = '".$this->id."' ";
		$result = mysql_query($query, $dbh);
		$this->has_child = mysql_result($result, 0, 0);

		// on regarde si la catégorie à des associées
		$query = "select count(1) from voir_aussi where num_noeud_orig = '".$this->id."' or num_noeud_dest = '".$this->id."' ";
		$result = mysql_query($query, $dbh);
		$this->has_child = $this->has_child + mysql_result($result, 0, 0);

		// on regarde si la catégorie est utilisée dans des notices
		$query = "select count(1) from notices_categories where num_noeud = '".$this->id."' ";
		$result = mysql_query($query, $dbh);
		$this->has_notices = mysql_result($result, 0, 0);


	}


	function categ_path($sep=' &gt; ',$css) {
	
		global $dbh;
		global $css;
		global $main;
		global $lang;
		
		if(!$this->id) return;
		
		$desc_categ = $this->zoom_categ($this->id, $this->comment);
		$current = "$sep<a href='./index.php?lvl=categ_see&id=".$this->id."&main=$main'".$desc_categ['java_com'].">".$this->libelle.'</a>'." ".$desc_categ['zoom'];
		// si pas de parent, le path se résume à la catégorie
		
		if(!$this->parent) return $current;
	
		// les parents sont mis en tableau
		$parent_id = $this->parent;
		$path_array = array();
		
		$path_array = categories::listAncestors($parent_id, $lang);
	
		while(list($cle, $valeur) = each($path_array)) {
			$ret .= $sep."<a href='./index.php?lvl=categ_see&id=${valeur['num_noeud']}&main=$main'>";
			$ret .= $valeur['libelle_categorie'].'</a>';
		}
		return $ret.$current;
	}
	
	
	function zoom_categ($id, $note) {
		global $charset;
		global $opac_show_infobulles_categ;
		
		if($opac_show_infobulles_categ) {
			if ($note) {
				$zoom_com = "<div id='zoom".$id."' class='categmouseout' >";
				$zoom_com.= htmlentities($note, ENT_QUOTES, $charset);
				$zoom_com.="</div>";
				$java_com = " onmouseover=\"y=document.getElementById('zoom".$id."'); y.className='categmouseover'; \" onmouseout=\"y=document.getElementById('zoom".$id."'); y.className='categmouseout'; \"" ;	
			} else {
				$zoom_com = "" ;
				$java_com = "" ;		
			}
			$result_zoom = array (zoom => $zoom_com, java_com => $java_com);
		}	
			return $result_zoom;
	}


	function child_list($image='./images/folder.gif',$css) {
	
		global $css;
		global $dbh;
		global $opac_categories_nb_col_subcat, $opac_categories_sub_mode;
		global $main;
		global $lang;
		global $charset;
		global $base_path;
		$current_col = 0;	
			
		// récupération des enfants
		
		if ($this->id == $this->thes->num_noeud_racine) $result = categories::listChilds($this->id, $lang, 0, $opac_categories_sub_mode);
		else 
		$result = categories::listChilds($this->id, $lang, 1, $opac_categories_sub_mode);
		
		if(mysql_num_rows($result) < $opac_categories_nb_col_subcat) {

			// nombre de sous-catégories réduit
			while($child=mysql_fetch_object($result)) {
				$libelle = $child->libelle_categorie;
				$note = $child->comment_public;
				$id = $child->num_noeud;
				$c2_categ = new category($id);
				
					if($child->num_renvoi_voir) {
						$libelle = "<i>$libelle</i>@";
						$id = $child->num_renvoi_voir;
					} 
					 
					// Si il y a présence d'un commentaire affichage du layer					
					$result_com = $this->zoom_categ($id, $note);				
					
					$l .= "<a href='./index.php?lvl=categ_see&id=$id&main=$main' class='small'>";
					
					if($c2_categ->has_notices())
						$l .= " <img src='$base_path/images/folder_search.gif' border=0 align='absmiddle' />";
					else
						$l .= "<img src='$image' border='0' align='top' />";
				
					$l .="<a/>".$result_com['zoom'];
					$l .= "<a href='./index.php?lvl=categ_see&id=$id&main=$main' class='small' ".$result_com['java_com'].">".$libelle."</a><br />";
				}
			$l = "<br /><div style='margin-left:48px'>$l</div>";
		} else {
				$l = "<table border='0' style='margin-left:48px' cellpadding='3'>";
				while($child=mysql_fetch_object($result)) {
					$libelle = $child->libelle_categorie;
					$note = $child->comment_public;
					$id = $child->num_noeud;
					$c_categ =  new category($id);
					
					if($child->num_renvoi_voir) {
						$libelle = "<i>$libelle</i>@";
						$id = $child->num_renvoi_voir;
					}
					// Si il y a présence d'un commentaire affichage du layer					
					$result_com = categorie::zoom_categ($id, $note);
					if ($current_col == 0) $l .= "\n<tr>";  
					$l .= "<td align='top'><a href='./index.php?lvl=categ_see&id=$id&main=$main' class='small'>";
					
					if($c_categ->has_notices())
						$l .= " <img src='$base_path/images/folder_search.gif' border=0 align='absmiddle' />";		
					else		
						$l .= "<img src='$image' border='0' align='top' />";

					$l .= "</a>".$result_com['zoom'];
					$l .= "<a href='./index.php?lvl=categ_see&id=$id&main=$main' class='small' ".$result_com['java_com'].">".$libelle."</a></td>";

					if ($current_col == $opac_categories_nb_col_subcat-1 ) {
						$l .= '</tr>';
						$current_col = 0;
					} else $current_col++;
				}
				$l .= '</table>';
			}
		return $l; 
	}
		
}
