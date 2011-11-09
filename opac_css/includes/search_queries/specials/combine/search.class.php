<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search.class.php,v 1.13.2.1 2011-07-07 14:27:38 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/rec_history.inc.php");

//Classe de gestion de la recherche spécial "combine"

class combine_search {
	var $id;
	var $n_ligne;
	var $params;
	var $search;

	//Constructeur
    function combine_search($id,$n_ligne,$params,&$search) {
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
    	global $charset;
    	global $get_input_box_id;
    	
    	//Récupération de la valeur de saisie
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	
    	if ($_SESSION["nb_queries"]!=0) {
	    	if(!$get_input_box_id)$get_input_box_id="input_box_id_0";
	    	else	$get_input_box_id++;
	    	   	
	    	//$r="&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td colspan='3'>";
	    	$r .="<script type='text/javascript' src='./javascript/tablist.js'></script>
	    	<div id='$get_input_box_id' class='notice-parent'>	    	
			<img src='./images/plus.gif' class='img_plus' name='imEx' id='$get_input_box_id"."Img' title='".addslashes($msg['plus_detail'])."' border='0' onClick=\"expandBase('$get_input_box_id', true); return false;\" hspace='3'>
			<span class='notice-heada'>		
				<input type='hidden' name='field_".$this->n_ligne."_s_".$this->id."[]'  id='".$get_input_box_id."_value' value='!!value_selected!!'/>	
				<label id='".$get_input_box_id."_label' >!!label_selected!!</label>
			</span>
			
			</div>
			<div id='$get_input_box_id"."Child' class='notice-child' style='margin-bottom:6px;display:none;width:94%' $max>
				<table class='table-no-border'>
				!!contenu!!
				</table>
			</div>
			";
			
    		if ($valeur) {
    			if ($valeur[0]=='-1') {
    				$r=str_replace("!!value_selected!!","-1", $r);
    				$r=str_replace("!!label_selected!!",$msg["default_search_histo"], $r);
    			}
    		} else {
    			$r=str_replace("!!value_selected!!","-1", $r);
    			$r=str_replace("!!label_selected!!",$msg["default_search_histo"], $r);   			
    		}

    		$style_odd="class='odd' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='odd'\" ";
    		$style_even="class='even' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='even'\" ";
    		$onclick="onClick=\"document.getElementById('".$get_input_box_id."_label').innerHTML='".addslashes($msg["default_search_histo"])."';document.getElementById('".$get_input_box_id."_value').value='-1';expandBase('$get_input_box_id', true); return false;\"";

    		$liste="<tr $style_even><td $onclick >".$msg["default_search_histo"]."</td></tr>";
    		$bool=false;
    		
    		//parcours de l'historique des recherches
    		for ($i=$_SESSION["nb_queries"]; $i>=1; $i--) { 
    			if($_SESSION["notice_view".$i]["search_mod"]){  			
	 	   			$temp=html_entity_decode(strip_tags(($i).") ".substr(get_human_query_level_two($i),strpos(get_human_query_level_two($i),":")+2,strlen(get_human_query_level_two($i))-(strpos(get_human_query_level_two($i),":")+2))));    			
	    			$onclick="onClick=\"document.getElementById('".$get_input_box_id."_label').innerHTML=this.innerHTML;document.getElementById('".$get_input_box_id."_value').value='$i';expandBase('$get_input_box_id', true); return false;\"";
	    			
	    			if(($pair=1-$pair)) $style=$style_odd;
	    			else $style=$style_even;
	    			$liste.="<tr $style><td $onclick >$temp</td></tr>";
	
	    			if ($valeur) {
	    				if ($valeur[0]==$i) {
	        				$r=str_replace("!!value_selected!!","$i", $r);
	    					$r=str_replace("!!label_selected!!",$temp, $r);   						
	    				}
	    			} 
    			}
    		}
    		$r=str_replace("!!contenu!!",$liste, $r);						
    	} else {
    		$r .= "<b>".$msg["histo_empty"]."</b>";
    	}			
    	return $r;
    }
    
    //fonction de conversion de la saisie en quelque chose de compatible avec l'environnement
    function transform_input() {
    }
    
    //fonction de création de la requête (retourne une table temporaire)
    function make_search() {
    	global $opac_indexation_docnum_allfields;
    	
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
					$t["is_num"][0]= $opac_indexation_docnum_allfields;
    				$t["ck_affiche"][0]=$opac_indexation_docnum_allfields;
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
				case 'docnum':
					$search[0]="f_16";
					$op_="BOOLEAN";	
					$valeur_champ=$_SESSION["user_query".$valeur[0]]["search_id"];
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
    			if($t) $$fieldvar_=$t;
    			else $$fieldvar_="";
    			$fieldvar=$$fieldvar_;	
								
    			if($_SESSION["typdoc".$valeur[0]]){    				
    				$search[1]="f_9";
					$op_="EQ";	
					$valeur_champ=$_SESSION["typdoc".$valeur[0]];
    				//opérateur
	    			$op="op_1_".$search[1];
	    			global $$op;
	    			$$op=$op_;
	    		    			
	    			//contenu de la recherche
	    			$field="field_1_".$search[1];
	    			$field_=array();
	    			$field_[0]=$valeur_champ;
	    			global $$field;
	    			$$field=$field_;
	    	    	    	    	
	    	    	//opérateur inter-champ
	    			$inter="inter_1_".$search[1];
	    			global $$inter;
	    			$$inter="and";	    			    			    				
    			}

	       		$es=new search("search_simple_fields");	
	       	break;	
			case 'extended_search':
				get_history($valeur[0]);
				$es=new search();
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
    							
				$es=new search("search_simple_fields");	
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
    			
				$es=new search("search_simple_fields");
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
								
	       		$es=new search("search_simple_fields");	
	       	break;	
			case 'extended_search':
				get_history($valeur[0]);
				$es=new search();
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
    							
				$es=new search("search_simple_fields");	
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
    			
				$es=new search("search_simple_fields");
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