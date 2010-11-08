<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search.class.php,v 1.3 2010-08-27 07:48:10 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $msg,$lang,$charset,$base_path,$class_path,$include_path;
require_once($class_path."/searcher.class.php");

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
    	if (count($_SESSION["session_history"])!=0) {
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
    	
    	//parcours de l'historique des recherches
    	if (count($_SESSION["session_history"])) {
    		if(!$get_input_box_id)$get_input_box_id="input_box_id_0";
	    	else	$get_input_box_id++;
	    	   	
	    	//$r="&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td colspan='3'>";
	    	$r .="<script type='text/javascript' src='./javascript/tablist.js'></script>
	    	<div id='$get_input_box_id' class='notice-parent'>	    	
			<img src='./images/plus.gif' class='img_plus' name='imEx' id='$get_input_box_id"."Img' title='détail' border='0' onClick=\"expandBase('$get_input_box_id', true); return false;\" hspace='3'>
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
    		/*echo "<pre>";
    		print_r($_SESSION["session_history"]);
    		echo "</pre>";*/
    	    for ($i=count($_SESSION["session_history"])-1; $i>=0; $i--) {
    			if ($_SESSION["session_history"][$i]["NOTI"] || $_SESSION["session_history"][$i]["EXPL"]) {
    				$temp=html_entity_decode(strip_tags(($i+1).") ".$_SESSION["session_history"][$i]["QUERY"]["HUMAN_QUERY"]),ENT_QUOTES,$charset);
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
    	global $search;
    	    	    	
    	//Récupération de la valeur de saisie
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	
    	if (!$this->is_empty($valeur)) {
    		
    		//enregistrement de l'environnement courant
    		$this->search->push();
    		//Récupération et mise en variables globales des valeurs de l'historique
    		if ($_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"]["search"][0]) {
    			$search=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"]["search"];
    			//Pour chaque champ
    			for ($i=0; $i<count($search); $i++) {
    			
    				//Récupération de l'opérateur
    				$op="op_".$i."_".$search[$i];
    				global $$op;
    				$$op=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"][$op];
    			    			
    				//Récupération du contenu de la recherche
    				$field_="field_".$i."_".$search[$i];
    				global $$field_;
    				$$field_=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"][$field_];
    				$field=$$field_;
    		
    				//Récupération de l'opérateur inter-champ
    				$inter="inter_".$i."_".$search[$i];
    				global $$inter;
    				$$inter=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"][$inter];
    			    		
    				//Récupération des variables auxiliaires
    				$fieldvar_="fieldvar_".$i."_".$search[$i];
    				global $$fieldvar_;
    				$$fieldvar_=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"][$fieldvar_];
    				$fieldvar=$$fieldvar_;
    			}
    		} else {
    			if (!$_SESSION["session_history"][$valeur[0]]["NOTI"]["GET"]["idcaddie"]) {
    				switch ($_SESSION["session_history"][$valeur[0]]["NOTI"]["GET"]["mode"])
    				{
    					case 0:
    						searcher_title::convert_simple_multi_unimarc($valeur[0]);	
    					break;
    					case 1:			
    						searcher_subject::convert_simple_multi_unimarc($valeur[0]);	
    					break;
    					case 2:
    						searcher_publisher::convert_simple_multi_unimarc($valeur[0]);	
    					break;
    				}
    			} else {
    				$op_="EQ";
    				$valeur_champ=$_SESSION["session_history"][$valeur[0]]["NOTI"]["GET"]["idcaddie"];
    				$search[0]="f_11";
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
    			}	
    		}
    			    		    		    		
    		//on instancie la classe search avec le nom de la nouvelle table temporaire
			if ($_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"]["search"][0]) {
				$sc=new search(false,"search_fields_unimarc");
			} else {
				$sc=new search(false,"search_simple_fields_unimarc");
			}
				
			$table_tempo=$sc->make_search("tempo_".$valeur[0]);
						
			//restauration de l'environnement courant
			$this->search->pull();
			return $table_tempo;
    	} 
    }
    
    function make_unimarc_query() {
    	global $search;
    	    	    	
    	//Récupération de la valeur de saisie
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	
    	if (!$this->is_empty($valeur)) {
    		
    		//enregistrement de l'environnement courant
    		$this->search->push();
    		//Récupération et mise en variables globales des valeurs de l'historique
    		if ($_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"]["search"][0]) {
    			$search=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"]["search"];
    			//Pour chaque champ
    			for ($i=0; $i<count($search); $i++) {
    			
    				//Récupération de l'opérateur
    				$op="op_".$i."_".$search[$i];
    				global $$op;
    				$$op=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"][$op];
    			    			
    				//Récupération du contenu de la recherche
    				$field_="field_".$i."_".$search[$i];
    				global $$field_;
    				$$field_=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"][$field_];
    				$field=$$field_;
    		
    				//Récupération de l'opérateur inter-champ
    				$inter="inter_".$i."_".$search[$i];
    				global $$inter;
    				$$inter=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"][$inter];
    			    		
    				//Récupération des variables auxiliaires
    				$fieldvar_="fieldvar_".$i."_".$search[$i];
    				global $$fieldvar_;
    				$$fieldvar_=$_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"][$fieldvar_];
    				$fieldvar=$$fieldvar_;
    			}
    		} else {
    			if (!$_SESSION["session_history"][$valeur[0]]["NOTI"]["GET"]["idcaddie"]) {
    				switch ($_SESSION["session_history"][$valeur[0]]["NOTI"]["GET"]["mode"])
    				{
    					case 0:
    						searcher_title::convert_simple_multi($valeur[0]);	
    					break;
    					case 1:			
    						searcher_subject::convert_simple_multi($valeur[0]);	
    					break;
    					case 2:
    						searcher_publisher::convert_simple_multi($valeur[0]);	
    					break;
    				}
    			} else {
    				$op_="EQ";
    				$valeur_champ=$_SESSION["session_history"][$valeur[0]]["NOTI"]["GET"]["idcaddie"];
    				$search[0]="f_11";
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
    			}	
    		}
    			    		    		    		
    		//on instancie la classe search avec le nom de la nouvelle table temporaire
			if ($_SESSION["session_history"][$valeur[0]]["QUERY"]["POST"]["search"][0]) {
				$sc=new search(false,"search_fields_unimarc");
			} else {
				$sc=new search(false,"search_simple_fields_unimarc");
			}
				
			$mt=$sc->make_unimarc_query();
						
			//restauration de l'environnement courant
			$this->search->pull();
			return $mt;
    	} 
    }
    
    //fonction de traduction littérale de la requête effectuée (renvoie un tableau des termes saisis)
    function make_human_query() {
    	
    	$litteral=array();
    			
    	//Récupération de la valeur de saisie 
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	if (!$this->is_empty($valeur)) {
			if($_SESSION["session_history"][$valeur[0]]["NOTI"]["HUMAN_QUERY"]){
    			$litteral[0]= $_SESSION["session_history"][$valeur[0]]["NOTI"]["HUMAN_QUERY"];
    		}else{
    			$litteral[0]= $_SESSION["session_history"][$valeur[0]]["EXPL"]["HUMAN_QUERY"];
    		}
    	}	
		return $litteral;    
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
}
?>