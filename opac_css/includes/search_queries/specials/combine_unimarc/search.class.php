<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search.class.php,v 1.5 2010-08-27 07:48:10 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/rec_history.inc.php");

//Classe de gestion de la recherche spécial "combine"

class combine_search_unimarc {
	var $id;
	var $n_ligne;
	var $params;
	var $search;

	//Constructeur
    function combine_search_unimarc($id,$n_ligne,$params,&$search) {
    	$this->id=$id;
    	$this->n_ligne=$n_ligne;
    	$this->params=$params;
    	$this->search=&$search;
    }
    
    //fonction de récupération des opérateurs disponibles pour ce champ spécial (renvoie un tableau d'opérateurs)
    function get_op() {
    	$operators = array();
    	if ($_SESSION["nb_queries"]!=0) {
    		$operators["EQ"]="=";
    	}
    	return $operators;
    }
    
    //fonction de récupération de l'affichage de la saisie du critère
    function get_input_box() {
    	global $msg;
    	
    	//Récupération de la valeur de saisie
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	    	
    	if ($_SESSION["nb_queries"]!=0) {
    		$r1.="<option value='-1' alt=\"".$msg["default_search_histo"]."\" title=\"".$msg["default_search_histo"]."\"";
    		if ($valeur[0]==-1) {
    			$r1 .= " selected";
    		}
    		$r1.=">".$msg["default_search_histo"]."</option>";
    		
    		$bool=false;
    		
    		//parcours de l'historique des recherches
    		for ($i=$_SESSION["nb_queries"]; $i>=1; $i--) {
    			$temp=html_entity_decode(strip_tags(($i).") ".substr(get_human_query_level_two($i),strpos(get_human_query_level_two($i),":")+2,strlen(get_human_query_level_two($i))-(strpos(get_human_query_level_two($i),":")+2))));    			
    			$r1.="<option value='".$i."' alt=\"".$temp."\" title=\"".$temp."\"";
    			if ($valeur) {
    				if ($valeur[0]==$i) {
    					$r1 .= " selected";
    				}
    			} 
    			$r1.=">".$this->cutlongwords($temp)."</option>";	
    			$bool=true;
    		}
    		if ($bool===true) {	
    			$r="<select name='field_".$this->n_ligne."_s_".$this->id."[]'>";
    			$r.=$r1;
    			$r.="</select>";
    		} else {
    			$r = "<b>".$msg["histo_empty"]."</b>";
    		}
    	} else {
    		$r = "<b>".$msg["histo_empty"]."</b>";
    	}
    	return $r;
    }
    
    //fonction de conversion de la saisie en quelque chose de compatible avec l'environnement
    function transform_input() {
    }
    
    //fonction de création de la requête (retourne une table temporaire)
    function make_search() {
    	
    	//Récupération de la valeur de saisie
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	
    	if (!$this->is_empty($valeur)) {
    		
    		//enregistrement de l'environnement courant
    		$this->search->push();
    		
    		//on instancie la classe search avec le nom de la nouvelle table temporaire
			switch ($_SESSION["search_type".$valeur[0]]) {
			case 'simple_search':
				global $search;
				
				switch($_SESSION["notice_view".$valeur[0]]["search_mod"]) {
					case 'title':
						$search[0]="f_6";
						$op_="BOOLEAN";
						$valeur_champ=$_SESSION["user_query".$valeur[0]];
					break;
					case 'all':
						$search[0]="f_7";
						$op_="BOOLEAN";
						$valeur_champ=$_SESSION["user_query".$valeur[0]];
					break;
					case 'abstract':
						$search[0]="f_13";
						$op_="BOOLEAN";
						$valeur_champ=$_SESSION["user_query".$valeur[0]];
					break;
					case 'keyword':
						$search[0]="f_12";
						$op_="BOOLEAN";
						$valeur_champ=$_SESSION["user_query".$valeur[0]];
					break;
					case 'author_see':
						$search[0]="f_8";	
						$op_="BOOLEAN";
						//Recherche de l'auteur
						$author_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
						$requete="select concat(author_name,', ',author_rejete) from authors where author_id=".$author_id;
						$r_author=mysql_query($requete);
						if (@mysql_num_rows($r_author)) {
							$valeur_champ=mysql_result($r_author,0,0);
						}
					break;
					case 'categ_see':
						$search[0]="f_1";	
						$op_="BOOLEAN";
						//Recherche de la catégorie
						$categ_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
						$requete="select libelle_categorie from categories where num_noeud=".$categ_id;
						$r_cat=mysql_query($requete);
						if (@mysql_num_rows($r_cat)) {
							$valeur_champ=mysql_result($r_cat,0,0);
						}
					break;		
					case 'indexint_see':	
						$search[0]="f_2";
						$op_="BOOLEAN";
						//Recherche de l'indexation
						$indexint_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
						$requete="select indexint_name from indexint where indexint_id=".$indexint_id;
						$r_indexint=mysql_query($requete);
						if (@mysql_num_rows($r_indexint)) {
							$valeur_champ=mysql_result($r_indexint,0,0);
						}
					break;		
					case 'coll_see':	
						$search[0]="f_4";
						$op_="BOOLEAN";
						//Recherche de l'indexation
						$coll_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
						$requete="select collection_name from collections where collection_id=".$coll_id;
						$r_coll=mysql_query($requete);
						if (@mysql_num_rows($r_coll)) {
							$valeur_champ=mysql_result($r_coll,0,0);
						}
					break;		
					case 'publisher_see':	
						$search[0]="f_3";
						$op_="BOOLEAN";
						//Recherche de l'éditeur
						$publisher_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
						$requete="select ed_name from publishers where ed_id=".$publisher_id;
						$r_pub=mysql_query($requete);
						if (@mysql_num_rows($r_pub)) {
							$valeur_champ=mysql_result($r_pub,0,0);
						}
					break;		
					case 'subcoll_see':	
						$search[0]="f_5";
						$op_="BOOLEAN";
						//Recherche de l'éditeur
						$subcoll_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
						$requete="select sub_coll_name from sub_collections where sub_coll_id=".$subcoll_id;
						$r_subcoll=mysql_query($requete);
						if (@mysql_num_rows($r_subcoll)) {
							$valeur_champ=mysql_result($r_subcoll,0,0);
						}
					break;
					case 'titre_uniforme_see':	
						$search[0]="f_6";
						$op_="BOOLEAN";
						//Recherche de l'éditeur
						$tu_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
						$requete="select tu_name from titre_uniformes where tu_id=".$tu_id;
						$r_tu=mysql_query($requete);
						if (@mysql_num_rows($r_tu)) {
							$valeur_champ=mysql_result($r_tu,0,0);
						}
					break;							
				}
				//opérateur
    			$op="op_0_".$search[0];
    			global $$op;
    			$$op=$op_;
    		    			
    			//contenu de la recherche
    			$field="field_0_".$search[0];
    			$field_=array();
    			$field_[0]=$valeur_champ;
    			global $$field;
    			$$field=$field_;
    	    	    	    	
    	    	//opérateur inter-champ
    			$inter="inter_0_".$search[0];
    			global $$inter;
    			$$inter="";
    			    		
    			//variables auxiliaires
    			$fieldvar_="fieldvar_0_".$search[0];
    			global $$fieldvar_;
    			$$fieldvar_="";
    			$fieldvar=$$fieldvar_;	
								
	       		$es=new search("search_simple_fields_unimarc");	
	       	break;	
			case 'extended_search':
				get_history($valeur[0]);
				$es=new search("search_fields_unimarc");
			break;
			case 'term_search':
				global $search;
				
				$search[0]="f_1";
				$op_="BOOLEAN";
				//Recherche de la catégorie
				$categ_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
				$requete="select libelle_categorie from categories where num_noeud=".$categ_id;
				$r_cat=mysql_query($requete);
				if (@mysql_num_rows($r_cat)) {
					$valeur_champ=mysql_result($r_cat,0,0);
				}	
				
				//opérateur
    			$op="op_0_".$search[0];
    			global $$op;
    			$$op=$op_;
    		    			
    			//contenu de la recherche
    			$field="field_0_".$search[0];
    			$field_=array();
    			$field_[0]=$valeur_champ;
    			global $$field;
    			$$field=$field_;
    	    	
    	    	//opérateur inter-champ
    			$inter="inter_0_".$search[0];
    			global $$inter;
    			$$inter="";
    			    		
    			//variables auxiliaires
    			$fieldvar_="fieldvar_0_".$search[0];
    			global $$fieldvar_;
    			$$fieldvar_="";
    			$fieldvar=$$fieldvar_;
    							
				$es=new search("search_simple_fields_unimarc");	
			break;
			case 'module':
				global $search;
	       			       		
	       		switch($_SESSION["notice_view".$valeur[0]]["search_mod"]) {
	       		case 'categ_see':
					$search[0]="f_1";
					$op_="BOOLEAN";
					//Recherche de la catégorie
					$categ_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
					$requete="select libelle_categorie from categories where num_noeud=".$categ_id;
					$r_cat=mysql_query($requete);
					if (@mysql_num_rows($r_cat)) {
						$valeur_champ=mysql_result($r_cat,0,0);
					}	
				break;		
				case 'indexint_see':	
					$search[0]="f_2";	
					$op_="BOOLEAN";
					//Recherche de l'indexation
					$indexint_id=$_SESSION["notice_view".$valeur[0]]["search_id"];
					$requete="select indexint_name from indexint where indexint_id=".$indexint_id;
					$r_indexint=mysql_query($requete);
					if (@mysql_num_rows($r_indexint)) {
						$valeur_champ=mysql_result($r_indexint,0,0);
					}
				break;		
				case 'etagere_see':
					$search[0]="f_14";
				break;	
				case 'section_see':
					$search[0]="f_15";
					global $search_localisation;
					$search_localisation=$_SESSION["notice_view".$valeur[0]]["search_location"];
				break;
				}
				
				if (!$op_) {
					$op_="EQ";
					$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				}
					
				//opérateur
    			$op="op_0_".$search[0];
    			global $$op;
    			$$op=$op_;
    		    			
    			//contenu de la recherche
    			$field="field_0_".$search[0];
    			$field_=array();
    			$field_[0]=$valeur_champ;
    			global $$field;
    			$$field=$field_;
    	    	
    	    	//opérateur inter-champ
    			$inter="inter_0_".$search[0];
    			global $$inter;
    			$$inter="";
    			    		
    			//variables auxiliaires
    			$fieldvar_="fieldvar_0_".$search[0];
    			global $$fieldvar_;
    			//fieldvar attention pour la section
    			$$fieldvar_="";
    			$fieldvar=$$fieldvar_;
    			
				$es=new search("search_simple_fields_unimarc");
			break;
			
			}
						
			$table_tempo=$es->make_search("tempo_".$valeur[0]);
									
			//restauration de l'environnement courant
			$this->search->pull();
			
    	}
		return $table_tempo; 
    }
    
    //fonction de traduction littérale de la requête effectuée (renvoie un tableau des termes saisis)
    function make_human_query() {
    	global $msg;
    	global $include_path;
    	
    	$litteral=array();
    			
    	//Récupération de la valeur de saisie 
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	
    	if (!$this->is_empty($valeur)) {
    		$litteral[0]= get_human_query_level_two($valeur[0]);
    	}	
		return $litteral;    
    }
    
    function make_unimarc_query() {
    	//Récupération de la valeur de saisie
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	
    	if (!$this->is_empty($valeur)) {
    		
    		//enregistrement de l'environnement courant
    		$this->search->push();
    		
    		//on instancie la classe search avec le nom de la nouvelle table temporaire
			switch ($_SESSION["search_type".$valeur[0]]) {
			case 'simple_search':
				global $search;
				
				switch($_SESSION["notice_view".$valeur[0]]["search_mod"]) {
				case 'title':
					$search[0]="f_6";
					$op_="BOOLEAN";
					$valeur_champ=$_SESSION["user_query".$valeur[0]];
				break;
				case 'all':
					$search[0]="f_7";
					$op_="BOOLEAN";
					$valeur_champ=$_SESSION["user_query".$valeur[0]];
				break;
				case 'abstract':
					$search[0]="f_13";
					$op_="BOOLEAN";
					$valeur_champ=$_SESSION["user_query".$valeur[0]];
				break;
				case 'keyword':
					$search[0]="f_12";
					$op_="BOOLEAN";
					$valeur_champ=$_SESSION["user_query".$valeur[0]];
				break;
				case 'author_see':
					$search[0]="f_8";	
					$op_="EQ";
					$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				break;
				case 'categ_see':
					$search[0]="f_1";	
					$op_="EQ";
					$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				break;		
				case 'indexint_see':	
					$search[0]="f_2";
					$op_="EQ";	
					$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				break;		
				case 'coll_see':	
					$search[0]="f_4";
					$op_="EQ";	
					$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				break;		
				case 'publisher_see':	
					$search[0]="f_3";
					$op_="EQ";	
					$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				break;		
				case 'subcoll_see':	
					$search[0]="f_5";
					$op_="EQ";	
					$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				break;
				case 'titre_uniforme_see':	
					$search[0]="f_6";
					$op_="EQ";	
					$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				break;		
				}
				//opérateur
    			$op="op_0_".$search[0];
    			global $$op;
    			$$op=$op_;
    		    			
    			//contenu de la recherche
    			$field="field_0_".$search[0];
    			$field_=array();
    			$field_[0]=$valeur_champ;
    			global $$field;
    			$$field=$field_;
    	    	    	    	
    	    	//opérateur inter-champ
    			$inter="inter_0_".$search[0];
    			global $$inter;
    			$$inter="";
    			    		
    			//variables auxiliaires
    			$fieldvar_="fieldvar_0_".$search[0];
    			global $$fieldvar_;
    			$$fieldvar_="";
    			$fieldvar=$$fieldvar_;	
								
	       		$es=new search("search_simple_fields_unimarc");	
	       	break;	
			case 'extended_search':
				get_history($valeur[0]);
				$es=new search("search_fields_unimarc");
			break;
			case 'term_search':
				global $search;
				
				$search[0]="f_1";
				$op_="EQ";
				$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				
				//opérateur
    			$op="op_0_".$search[0];
    			global $$op;
    			$$op=$op_;
    		    			
    			//contenu de la recherche
    			$field="field_0_".$search[0];
    			$field_=array();
    			$field_[0]=$valeur_champ;
    			global $$field;
    			$$field=$field_;
    	    	
    	    	//opérateur inter-champ
    			$inter="inter_0_".$search[0];
    			global $$inter;
    			$$inter="";
    			    		
    			//variables auxiliaires
    			$fieldvar_="fieldvar_0_".$search[0];
    			global $$fieldvar_;
    			$$fieldvar_="";
    			$fieldvar=$$fieldvar_;
    							
				$es=new search("search_simple_fields_unimarc");	
			break;
			case 'module':
				global $search;
	       			       		
	       		switch($_SESSION["notice_view".$valeur[0]]["search_mod"]) {
	       		case 'categ_see':
					$search[0]="f_1";	
				break;		
				case 'indexint_see':	
					$search[0]="f_2";	
				break;		
				case 'etagere_see':
					$search[0]="f_14";
				break;	
				case 'section_see':
					$search[0]="f_15";
					global $search_localisation;
					$search_localisation=$_SESSION["notice_view".$valeur[0]]["search_location"];
				break;
				}
				
				$op_="EQ";
				$valeur_champ=$_SESSION["notice_view".$valeur[0]]["search_id"];
				
				//opérateur
    			$op="op_0_".$search[0];
    			global $$op;
    			$$op=$op_;
    		    			
    			//contenu de la recherche
    			$field="field_0_".$search[0];
    			$field_=array();
    			$field_[0]=$valeur_champ;
    			global $$field;
    			$$field=$field_;
    	    	
    	    	//opérateur inter-champ
    			$inter="inter_0_".$search[0];
    			global $$inter;
    			$$inter="";
    			    		
    			//variables auxiliaires
    			$fieldvar_="fieldvar_0_".$search[0];
    			global $$fieldvar_;
    			//fieldvar attention pour la section
    			$$fieldvar_="";
    			$fieldvar=$$fieldvar_;
    			
				$es=new search("search_simple_fields_unimarc");
			break;
			
			}
						
			$mt=$es->make_unimarc_query();
									
			//restauration de l'environnement courant
			$this->search->pull();
			
    	}
		return $mt; 
    }
    
    //fonction de découpage d'une chaine trop longue
    function cutlongwords($valeur) {
    	if (strlen($valeur)>=50) {
    		$pos=strrpos(substr($valeur,0,50)," ");
    		if ($pos) {
    			$valeur=substr($valeur,0,$pos+1)."...";
    		} 
    	} 
    	return $valeur;		
    }
    
	//fonction de vérification du champ saisi ou sélectionné
    function is_empty($valeur) {
    	if (count($valeur)) {
    		if ($valeur[0]=="-1") return true;
    			else return ($valeur[0] === false);
    	} else {
    		return true;
    	}
    }
}
?>