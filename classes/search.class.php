<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search.class.php,v 1.128 2010-08-27 07:48:10 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Classe de gestion des recherches avancees

require_once($include_path."/parser.inc.php");
require_once($class_path."/parametres_perso.class.php");
require_once($include_path."/templates/search.tpl.php");
require_once($class_path."/analyse_query.class.php");
require_once($class_path."/sort.class.php");
require_once("$class_path/acces.class.php");
require_once($include_path."/isbn.inc.php");
require_once("$class_path/fiche.class.php");

if ($pmb_allow_external_search && (SESSrights & ADMINISTRATION_AUTH) && $id_connector_set)
	require_once($class_path."/connecteurs_out_sets.class.php");			

class mterm {
	var	$ufield;		//Nom du champ UNIMARC
	var $op;			//Operateur
	var $values;		//Liste des valeurs (tableau)
	var $vars;			//Valeurs annexes
	var $sub;			//sous liste de termes (tableau)
	var $inter;			//operateur entre ce terme et le precedent
	
	function mterm($ufield,$op,$values,$vars,$inter) {
		$this->ufield=$ufield;
		$this->op=$op;
		$this->values=$values;
		$this->vars=$vars;
		$this->inter=$inter;
	}
	
	function set_sub($sub) {
		$this->sub=$sub;
	}
}

class search {

	var $operators;
	var $op_empty;
	var $fixedfields;
	var $dynamicfields;
	var $specialfields;
	var $pp;
	var $error_message;
	var $link;
	var	$link_expl; 
	var	$link_explnum;
	var $link_serial;
	var $link_analysis;
	var	$link_bulletin;
	var	$link_explnum_serial;
	var $rec_history;
	var $tableau_speciaux;
	var $operator_multi_value;
	var $full_path='';
	
	var $dynamics_not_visible;
	var $specials_not_visible;
	
	var $isfichier = false;
	
    function search($rec_history=false,$fichier_xml="",$full_path='') {
    	global $launch_search;
    	
    	$this->parse_search_file($fichier_xml,$full_path);
    	$this->strip_slashes();
    	foreach ( $this->dynamicfields as $key => $value ) {
       		$this->pp[$key]=new parametres_perso($value["TYPE"]);
		}
		$this->rec_history=$rec_history;
		$this->full_path = $full_path;		
    }
    
    function strip_slashes() {
    	global $search, $explicit_search;
    	for ($i=0; $i<count($search); $i++) {
    		$s=explode("_",$search[$i]);
    		$field_="field_".$i."_".$search[$i];
    		global $$field_;
    		$field=$$field_;
    		for ($j=0; $j<count($field); $j++) {
    			$field[$j]=stripslashes($field[$j]);
    		}
    		if ($explicit_search) {
    			if ($s[0]=="f") {
    				$ff=$this->fixedfields[$s[1]];
    				switch ($ff["INPUT_TYPE"]) {
    					case "date":
    						$field_temp=extraitdate($field[0]);
    						$field[0]=$field_temp;
    						break;
    					default:
    					//Rien a faire
    						break;
    				}
    			}
    		}
    		$$field_=$field;
    	}
    }
    
    function get_id_from_datatype($datatype, $fieldType = "d") {
    	reset($this->dynamicfields[$fieldType]["FIELD"]);
    	while (list($key,$val)=each($this->dynamicfields[$fieldType]["FIELD"])) {
    		if ($val["DATATYPE"]==$datatype) return $key;
    	}
    	return "";
    }
    
    function get_field($i,$n,$search,$pp) {
    	global $charset;
    	global $aff_list_empr_search;
    	global $msg;
    	global $include_path;
    	
    	$r="";
    	$s=explode("_",$search);
    	
    	//Champ
    	$val="field_".$i."_".$search;
    	global $$val;
    	$v=$$val;
    	if ($v=="") $v=array();
    	
    	//Variables
    	$fieldvar_="fieldvar_".$i."_".$search;
		global $$fieldvar_;
		$fieldvar=$$fieldvar_;
    	
     	if ($s[0]=="f") {
    		//Champs fixes
    		$ff=$this->fixedfields[$s[1]];
    		
    	   	//Variables globales et input
    		for ($j=0; $j<count($ff["VAR"]); $j++) {
    			switch ($ff["VAR"][$j]["TYPE"]) {
    				case "input":
 			  		  	$valvar="fieldvar_".$i."_".$search."[\"".$ff["VAR"][$j]["NAME"]."\"]";
   					 	global $$valvar;
   				 		$vvar[$ff["VAR"][$j]["NAME"]]=$$valvar;
   				 		if ($vvar[$ff["VAR"][$j]["NAME"]]=="") $vvar[$ff["VAR"][$j]["NAME"]]=array();
   				 		$var_table[$ff["VAR"][$j]["NAME"]]=$vvar[$ff["VAR"][$j]["NAME"]];
   				 		break;
   				 	case "global":
   				 		$global_name=$ff["VAR"][$j]["NAME"];
   				 		global $$global_name;
   				 		$var_table[$ff["VAR"][$j]["NAME"]]=$$global_name;
   				 		break;
    			}
    		}
    		
    		switch ($ff["INPUT_TYPE"]) {
     			case "authoritie":
						$fnamesans="field_".$n."_".$search;
						$fname="field_".$n."_".$search."[]";
						$fname_id="field_".$n."_".$search."_id";
						$ajax=$ff["INPUT_OPTIONS"]["AJAX"];
						$selector=$ff["INPUT_OPTIONS"]["SELECTOR"];
						$p1=$ff["INPUT_OPTIONS"]["P1"];
						$p2=$ff["INPUT_OPTIONS"]["P2"];
						global $thesaurus_mode_pmb;
						if($ajax == "categories_mul" and $thesaurus_mode_pmb == 1){
							$fnamevar_id = "linkfield=\"fieldvar_".$n."_".$search."[id_thesaurus][]\"";
						}else{
							$fnamevar_id = "";
						}
						$r="<input autfield=\"$fname_id\" completion=\"$ajax\" $fnamevar_id id='$fnamesans' name=\"$fname\" value='".htmlentities($v[0],ENT_QUOTES,$charset)."' type=\"text\">" .
						"<input class=\"bouton\" value=\"...\" " .
						"onclick=\"openPopUp('./select.php?what=$selector&caller=search_form&mode=un&$p1=$fname_id&$p2=$fname&deb_rech='+escape(document.getElementById('$fnamesans').value), 'select_author0', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" type=\"button\">" .
						"<input name=\"$fname_id\" id=\"$fname_id\" value=\"\" type=\"hidden\">";
    				break;
    			case "text":
    				$r="<input type='text' name='field_".$n."_".$search."[]' value='".htmlentities($v[0],ENT_QUOTES,$charset)."'/>";
    				break;
    			case "query_list":
    				$requete=$ff["INPUT_OPTIONS"]["QUERY"][0]["value"];
    				$resultat=mysql_query($requete);
    				$r="<select name='field_".$n."_".$search."[]' multiple size='5'>";
    				while ($opt=mysql_fetch_row($resultat)) {
    					$r.="<option value='".htmlentities($opt[0],ENT_QUOTES,$charset)."' ";
    					$as=array_search($opt[0],$v);
    					if (($as!==null)&&($as!==false)) $r.=" selected";
    					$r.=">".htmlentities($opt[1],ENT_QUOTES,$charset)."</option>";
    				}
    				$r.="</select>";
    				break;
    			case "list":
    				$options=$ff["INPUT_OPTIONS"]["OPTIONS"][0];
    				$r="<select name='field_".$n."_".$search."[]' multiple size='5'>";
    				sort($options["OPTION"]);
    				for ($i=0; $i<count($options["OPTION"]); $i++) {
    					$r.="<option value='".htmlentities($options["OPTION"][$i]["VALUE"],ENT_QUOTES,$charset)."' ";
    					$as=array_search($options["OPTION"][$i]["VALUE"],$v);
    					if (($as!==null)&&($as!==false)) $r.=" selected";
    					if (substr($options["OPTION"][$i]["value"],0,4)=="msg:") {
    						$r.=">".htmlentities($msg[substr($options["OPTION"][$i]["value"],4,strlen($options["OPTION"][$i]["value"])-4)],ENT_QUOTES,$charset)."</option>";
    					} else {
    						$r.=">".htmlentities($options["OPTION"][$i]["value"],ENT_QUOTES,$charset)."</option>";
    					}
    				}
    				$r.="</select>";
    				break;
    			case "marc_list":
    				$options=new marc_list($ff["INPUT_OPTIONS"]["NAME"][0]["value"]);
    				asort($options->table);
    				reset($options->table);

  		  			// gestion restriction par code utilise.
  		  			if ($ff["INPUT_OPTIONS"]["RESTRICTQUERY"][0]["value"]) {
  		  				$restrictquery=mysql_query($ff["INPUT_OPTIONS"]["RESTRICTQUERY"][0]["value"]);
				  		if ($restrictqueryrow=@mysql_fetch_row($restrictquery)) {
				  			if ($restrictqueryrow[0]) {
				  				$restrictqueryarray=explode(",",$restrictqueryrow[0]);
				  				$existrestrict=true;
				  			} else $existrestrict=false;
				  		} else $existrestrict=false;
  		  			} else $existrestrict=false;

    				$r="<select name='field_".$n."_".$search."[]' multiple size='5' class=\"ext_search_txt\">";
    				while (list($key,$val)=each($options->table)) {
    					if ($existrestrict && array_search($key,$restrictqueryarray)!==false) {
    						$r.="<option value='".htmlentities($key,ENT_QUOTES,$charset)."' ";
	    					$as=array_search($key,$v);
    						if (($as!==null)&&($as!==false)) $r.=" selected";
    						$r.=">".htmlentities($val,ENT_QUOTES,$charset)."</option>";
    					} elseif (!$existrestrict) {
    						$r.="<option value='".htmlentities($key,ENT_QUOTES,$charset)."' ";
	    					$as=array_search($key,$v);
    						if (($as!==null)&&($as!==false)) $r.=" selected";
    						$r.=">".htmlentities($val,ENT_QUOTES,$charset)."</option>";
    					}    						
    				}
    				$r.="</select>";
    				break;
    			case "date":
    				$r="<input type='text' name='field_".$n."_".$search."[]' value='".htmlentities(format_date($v[0]),ENT_QUOTES,$charset)."'/>";
    				break;
    		}
    		//Traitement des variables d'entree
    		//Variables
	    	for ($j=0; $j<count($ff["VAR"]); $j++) {
   		 		if ($ff["VAR"][$j]["TYPE"]=="input") {
   		 			$varname=$ff["VAR"][$j]["NAME"];
   		 			$visibility=1;
   		 			$vis=$ff["VAR"][$j]["OPTIONS"]["VAR"][0];
   		 			if ($vis["NAME"]) {
   		 				$vis_name=$vis["NAME"];
   		 				global $$vis_name;
   		 				if ($vis["VISIBILITY"]=="no") $visibility=0;
   		 				for ($k=0; $k<count($vis["VALUE"]); $k++) {
   		 					if ($vis["VALUE"][$k]["value"]==$$vis_name) {
   		 						if ($vis["VALUE"][$k]["VISIBILITY"]=="no") $sub_vis=0; else $sub_vis=1;
   		 						if ($vis["VISIBILITY"]=="no") $visibility|=$sub_vis; else $visibility&=$sub_vis;
   		 						break;
   		 					}
   		 				}
   		 			}
   		 			
   		 			//Recherche de la valeur par defaut
   		 			$vdefault=$ff["VAR"][$j]["OPTIONS"]["DEFAULT"][0];
   		 			if ($vdefault) {
   			 			switch ($vdefault["TYPE"]) {
   			 				case "var":
   			 						$default=$var_table[$vdefault["value"]];
   			 					break;
   			 				case "value":
   			 				default:
   			 						$default=$vdefault["value"];
   			 			}
   		 			} else $vdefault="";
   		 				
   		 			if ($visibility) {
	   		 			$r.="&nbsp;".$ff["VAR"][$j]["COMMENT"];
			  		  	$input=$ff["VAR"][$j]["OPTIONS"]["INPUT"][0];
			  		  	switch ($input["TYPE"]) {
			  		  		case "query_list":
			  		  			if ((!$fieldvar[$varname])&&($default)) $fieldvar[$varname][0]=$default;
			  		  			$r.="&nbsp;<select id=\"fieldvar_".$n."_".$search."[".$varname."][]\" name=\"fieldvar_".$n."_".$search."[".$varname."][]\">\n";
			  		  			$query_list_result=@mysql_query($input["QUERY"][0]["value"]);
			  		  			$var_tmp=$concat="";
			  		  			while ($line=mysql_fetch_array($query_list_result)) {
			  		  				if($concat)$concat.=",";
			  		  				$concat.=$line[0];
			  		  				$var_tmp.="<option value=\"".htmlentities($line[0],ENT_QUOTES,$charset)."\"";
			  		  				$as=@array_search($line[0],$fieldvar[$varname]);
			  		  				if (($as!==false)&&($as!==NULL)) $var_tmp.=" selected";
			  		  				$var_tmp.=">".htmlentities($line[1],ENT_QUOTES,$charset)."</option>\n";
			  		  			}
			  		  			if($input["QUERY"][0]["ALLCHOICE"] == "yes"){
			  		  				$r.="<option value=\"".htmlentities($concat,ENT_QUOTES,$charset)."\"";
			  		  				$as=@array_search($concat,$fieldvar[$varname]);
			  		  				if (($as!==false)&&($as!==NULL)) $r.=" selected";
			  		  				$r.=">".htmlentities($msg[substr($input["QUERY"][0]["TITLEALLCHOICE"],4,strlen($input["QUERY"][0]["TITLEALLCHOICE"])-4)],ENT_QUOTES,$charset)."</option>\n";
			  		  			}
			  		  			$r.=$var_tmp;
			  		  			$r.="</select>";
			  		  			break;
			  		  		case "checkbox" :
			  		  			if(!$input["DEFAULT_ON"]){
			  				  		if ((!$fieldvar[$varname])&&($default)) $fieldvar[$varname][0]=$default;
			  		  			} elseif(!$fieldvar[$input["DEFAULT_ON"]][0]) $fieldvar[$varname][0] =$default;
			  		  			$r.="&nbsp;<input type=\"checkbox\" name=\"fieldvar_".$n."_".$search."[".$varname."][]\" value=\"".$input["VALUE"][0]["value"]."\" ";
			  		  			if($input["VALUE"][0]["value"] == $fieldvar[$varname][0]) $r.="checked";			  		  			
			  		  			$r.="/>\n";
			  		  			break;
			  		  		case "hidden":
			  		  			if ((!$fieldvar[$varname])&&($default)) $fieldvar[$varname][0]=$default;
			  		  			if(is_array($input["VALUE"][0])) $hidden_value=$input["VALUE"][0]["value"]; 
			  		  			else $hidden_value=$fieldvar[$varname][0];
			  		  			$r.="<input type='hidden' name=\"fieldvar_".$n."_".$search."[".$varname."][]\" value=\"".htmlentities($hidden_value,ENT_QUOTES,$charset)."\"/>";
			  		  			break;
			  		  	}
   		 			} else {
   		 				if($vis["HIDDEN"] != "no")
   		 					$r.="<input type='hidden' name=\"fieldvar_".$n."_".$search."[".$varname."][]\" value=\"".htmlentities($default,ENT_QUOTES,$charset)."\"/>";
   		 			}
   		 		}
   	 		}
   	 	} elseif (array_key_exists($s[0],$this->pp)){
   	 		//Recuperation du champ
    		$field=array();
			$field[ID]=$s[1];
			$field[NAME]=$this->pp[$s[0]]->t_fields[$s[1]][NAME];
			$field[MANDATORY]=$this->pp[$s[0]]->t_fields[$s[1]][MANDATORY];
			$field[ALIAS]=$this->pp[$s[0]]->t_fields[$s[1]][TITRE];
			$field[DATATYPE]=$this->pp[$s[0]]->t_fields[$s[1]][DATATYPE];
			$field[OPTIONS][0]=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$this->pp[$s[0]]->t_fields[$s[1]][OPTIONS], "OPTIONS");
			$field[VALUES]=$v;
			$field[PREFIX]=$this->pp[$s[0]]->prefix;
			eval("\$r=".$aff_list_empr_search[$this->pp[$s[0]]->t_fields[$s[1]][TYPE]]."(\$field,\$check_scripts,\"field_".$n."_".$search."\");");
   	 	} elseif ($s[0]=="s") {
    		//appel de la fonction get_input_box de la classe du champ special
    		$type=$this->specialfields[$s[1]]["TYPE"];
    		for ($is=0; $is<$this->tableau_speciaux["TYPE"]; $is++) {
				if ($this->tableau_speciaux["TYPE"][$is]["NAME"]==$type) {
					$sf=$this->specialfields[$s[1]];
					if ($this->full_path && file_exists($this->full_path."/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php"))
						require_once($this->full_path."/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
					else
						require_once($include_path."/search_queries/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
					$specialclass= new $this->tableau_speciaux["TYPE"][$is]["CLASS"]($s[1],$n,$sf,$this);
					$r=$specialclass->get_input_box();	
					break;
				}
    		}
     	}
    	return $r;
    }
    
    function make_search($prefixe="") {
    	global $search;
    	global $dbh;
    	global $msg;
    	global $include_path;
    	global $pmb_multi_search_operator;
    	 	
    	$this->error_message="";
	   	$last_table="";
	   	$field_keyName=$this->keyName;
	   	//Pour chaque champ
    	for ($i=0; $i<count($search); $i++) {
    		//construction de la requete
    		$s=explode("_",$search[$i]);
    		
    		//Recuperation de l'operateur
    		$op="op_".$i."_".$search[$i];
    		
    		//Recuperation du contenu de la recherche
    		$field_="field_".$i."_".$search[$i];
    		global $$field_;
    		$field=$$field_;
    		
    		//Recuperation de l'operateur inter-champ
    		$inter="inter_".$i."_".$search[$i];
    		global $$inter;
    		global $$op;
    		
    		//Recuperation des variables auxiliaires
    		$fieldvar_="fieldvar_".$i."_".$search[$i];
    		global $$fieldvar_;
    		$fieldvar=$$fieldvar_;
    		
			//Si c'est un champ fixe
    		if ($s[0]=="f") {
   	 			$ff=$this->fixedfields[$s[1]];
   	 			//Calcul des variables
   	 			$var_table=array();
   	 			for ($j=0; $j<count($ff["VAR"]); $j++) {
		    		switch ($ff["VAR"][$j]["TYPE"]) {
						case "input":
					 		$var_table[$ff["VAR"][$j]["NAME"]]=@implode(",",$fieldvar[$ff["VAR"][$j]["NAME"]]);
		 					break;
					 	case "global":
					 		$global_name=$ff["VAR"][$j]["NAME"];
		 					global $$global_name;
		 			 		$var_table[$ff["VAR"][$j]["NAME"]]=$$global_name;
				 			break;
				 		case "calculated":
				 			$calc=$ff["VAR"][$j]["OPTIONS"]["CALC"][0];
				 			switch ($calc["TYPE"]) {
				 				case "value_from_query":
				 					$query_calc=$calc["QUERY"][0]["value"];
				 					@reset($var_table);
				 					while (list($var_name,$var_value)=@each($var_table)) {
				 						$query_calc=str_replace("!!".$var_name."!!",$var_value,$query_calc);
				 					}
				 					$r_calc=mysql_query($query_calc);
				 					$var_table[$ff["VAR"][$j]["NAME"]]=@mysql_result($r_calc,0,0);
				 					break;
				 			}
				 			break;
			 		}
			  	}
	 			$q_index=$ff["QUERIES_INDEX"];
	 			//Recuperation de la requete associee au champ et a l'operateur
	 			$q=$ff["QUERIES"][$q_index[$$op]];
	 			
	 			//Si c'est une requete conditionnelle, on sélectionne la bonne requete et on supprime les autres
	 			if($q[0]["CONDITIONAL"]){	 				
	 				$k_default=0;
	 				$q_temp = array();
	 				$q_temp["OPERATOR"]=$q["OPERATOR"];
	 				for($k=0; $k<count($q)-1;$k++){
	 					if($var_table[$q[$k]["CONDITIONAL"]["name"]]== $q[$k]["CONDITIONAL"]["value"]) break;
	 					if ($q[$k]["CONDITIONAL"]["value"] == "default") $k_default=$k;	 						
	 				} 
	 				if($k == count($q)-1) $k=$k_default;
	 				$q_temp[0] = $q[$k];
	 				$q= $q_temp;
	  			}
	  		
				//Remplacement par les variables eventuelles pour chaque requete
				for ($k=0; $k<count($q)-1; $k++) {
	 				reset($var_table);
					while (list($var_name,$var_value)=each($var_table)) {
						$q[$k]["MAIN"]=str_replace("!!".$var_name."!!",$var_value,$q[$k]["MAIN"]);
						$q[$k]["MULTIPLE_TERM"]=str_replace("!!".$var_name."!!",$var_value,$q[$k]["MULTIPLE_TERM"]);
					}
				}	
							
				$last_main_table="";
				//Pour chaque valeur du champ
				for ($j=0; $j<count($field); $j++) {
					//Pour chaque requete
					$field_origine=$field[$j];
					for ($z=0; $z<count($q)-1; $z++) {
	   					//Si le nettoyage de la saisie est demande
	   					if($q[$z]["KEEP_EMPTYWORD"])	$field[$j]=strip_empty_chars($field_origine);
						elseif ($q[$z]["REGDIACRIT"]) $field[$j]=strip_empty_words($field_origine);
						$main=$q[$z]["MAIN"];
						//Si il y a plusieurs termes possibles on construit la requete avec le terme !!multiple_term!!
						if ($q[$z]["MULTIPLE_WORDS"]) {
							$terms=explode(" ",$field[$j]);
							//Pour chaque terme,
							$multiple_terms=array();
							for ($k=0; $k<count($terms); $k++) {
								$multiple_terms[]=str_replace("!!p!!",$terms[$k],$q[$z]["MULTIPLE_TERM"]);
							}
							$final_term=implode(" ".$q[$z]["MULTIPLE_OPERATOR"]." ",$multiple_terms);
							$main=str_replace("!!multiple_term!!",$final_term,$main);
						//Si la saisie est un ISBN
						} else if ($q[$z]["ISBN"]) {
							//Code brut
							$terms[0]=$field[$j];
							//EAN ?
							if (isEAN($field[$j])) {
								//C'est un isbn ?
								if (isISBN($field[$j])) {
									$rawisbn = preg_replace('/-|\.| /', '', $field[$j]);
									//On envoi tout ce qu'on sait faire en matiere d'ISBN, en raw et en formatte, en 10 et en 13
									$terms[1]=formatISBN($rawisbn,10);
									$terms[2]=formatISBN($rawisbn,13);
									$terms[3]=preg_replace('/-|\.| /', '', $terms[1]);
									$terms[4]=preg_replace('/-|\.| /', '', $terms[2]);
								}
							}
							else if (isISBN($field[$j])) {
								$rawisbn = preg_replace('/-|\.| /', '', $field[$j]);
								//On envoi tout ce qu'on sait faire en matiere d'ISBN, en raw et en formatte, en 10 et en 13
								$terms[1]=formatISBN($rawisbn,10);
								$terms[2]=formatISBN($rawisbn,13);
								$terms[3]=preg_replace('/-|\.| /', '', $terms[1]);
								$terms[4]=preg_replace('/-|\.| /', '', $terms[2]);
							}
							//Pour chaque terme,
							$multiple_terms=array();
							for ($k=0; $k<count($terms); $k++) {
								$multiple_terms[]=str_replace("!!p!!",$terms[$k],$q[$z]["MULTIPLE_TERM"]);
							}
							$final_term=implode(" ".$q[$z]["MULTIPLE_OPERATOR"]." ",$multiple_terms);
							$main=str_replace("!!multiple_term!!",$final_term,$main);
						} else if ($q[$z]["BOOLEAN"]) {
							$aq=new analyse_query($field[$j]);
							$aq1=new analyse_query($field[$j],0,0,1,1);
							if ($q[$z]["KEEP_EMPTY_WORDS_FOR_CHECK"]) $err=$aq1->error; else $err=$aq->error;
							if (!$err) {
								if (is_array($q[$z]["TABLE"])) {
									for ($z1=0; $z1<count($q[$z]["TABLE"]); $z1++) {
										if (!$q[$z]["KEEP_EMPTY_WORDS"][$z1]) 
											$members=$aq->get_query_members($q[$z]["TABLE"][$z1],$q[$z]["INDEX_L"][$z1],$q[$z]["INDEX_I"][$z1],$q[$z]["ID_FIELD"][$z1],$q[$z]["RESTRICT"][$z1]);
										else $members=$aq1->get_query_members($q[$z]["TABLE"][$z1],$q[$z]["INDEX_L"][$z1],$q[$z]["INDEX_I"][$z1],$q[$z]["ID_FIELD"][$z1],$q[$z]["RESTRICT"][$z1]);
										$main=str_replace("!!pert_term_".($z1+1)."!!",$members["select"],$main);
										$main=str_replace("!!where_term_".($z1+1)."!!",$members["where"],$main);
									}
								} else {
									if ($q[$z]["KEEP_EMPTY_WORDS"])
										$members=$aq1->get_query_members($q[$z]["TABLE"],$q[$z]["INDEX_L"],$q[$z]["INDEX_I"],$q[$z]["ID_FIELD"],$q[$z]["RESTRICT"]);
									else $members=$aq->get_query_members($q[$z]["TABLE"],$q[$z]["INDEX_L"],$q[$z]["INDEX_I"],$q[$z]["ID_FIELD"],$q[$z]["RESTRICT"]);
									$main=str_replace("!!pert_term!!",$members["select"],$main);
									$main=str_replace("!!where_term!!",$members["where"],$main);
								}
							} else {
								$main="select notice_id from notices where notice_id=0";
								$this->error_message=sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message);
							}
						} else $main=str_replace("!!p!!",addslashes($field[$j]),$main);
						//Y-a-t-il une close repeat ?
						if ($q[$z]["REPEAT"]) {
							//Si oui, on repete !!
							$onvals=$q[$z]["REPEAT"]["ON"];
							global $$onvals;
							$onvalst=explode($q[$z]["REPEAT"]["SEPARATOR"],$$onvals);
							$mains=array();
							for ($ir=0; $ir<count($onvalst); $ir++) {
								$mains[]=str_replace("!!".$q[$z]["REPEAT"]["NAME"]."!!",$onvalst[$ir],$main);
							}
							$main=implode(" ".$q[$z]["REPEAT"]["OPERATOR"]." ",$mains);
							$main="select * from (".$main.") as sbquery".($q[$z]["REPEAT"]["ORDERTERM"]?" order by ".$q[$z]["REPEAT"]["ORDERTERM"]:"");
						}
						if ($z<(count($q)-2)) mysql_query($main);
					}		
					
					if($q["DEFAULT_OPERATOR"]){
						$operator=$q["DEFAULT_OPERATOR"];
					} else {
						$operator = ($pmb_multi_search_operator?$pmb_multi_search_operator:"or");
					}
					
					if (count($field)>1) {
						if($operator == "or"){
							//Ou logique si plusieurs valeurs
							if ($prefixe) {
								$requete="create temporary table ".$prefixe."mf_".$j." ENGINE=MyISAM ".$main;	
								@mysql_query($requete,$dbh);
								$requete="alter table ".$prefixe."mf_".$j." add idiot int(1)";
								@mysql_query($requete);
								$requete="alter table ".$prefixe."mf_".$j." add unique($field_keyName)";
								@mysql_query($requete);
							} else {
								$requete="create temporary table mf_".$j." ENGINE=MyISAM ".$main;
								@mysql_query($requete,$dbh);
								$requete="alter table mf_".$j." add idiot int(1)";
								@mysql_query($requete);
								$requete="alter table mf_".$j." add unique($field_keyName)";
								@mysql_query($requete);
							}
		
							if ($last_main_table) {
								if ($prefixe) {
									$requete="insert ignore into ".$prefixe."mf_".$j." select ".$last_main_table.".* from ".$last_main_table;
								} else {
									$requete="insert ignore into mf_".$j." select ".$last_main_table.".* from ".$last_main_table;
								}
	 							mysql_query($requete,$dbh);
								//mysql_query("drop table mf_".$j,$dbh);
								mysql_query("drop table ".$last_main_table,$dbh);
							} //else mysql_query("drop table mf_".$j,$dbh);
							if ($prefixe) {
								$last_main_table=$prefixe."mf_".$j;
							} else {
								$last_main_table="mf_".$j;
							}
						} elseif($operator == "and"){
							//ET logique si plusieurs valeurs
							if ($prefixe) {
								$requete="create temporary table ".$prefixe."mf_".$j." ENGINE=MyISAM ".$main;	
								@mysql_query($requete,$dbh);
								$requete="alter table ".$prefixe."mf_".$j." add idiot int(1)";
								@mysql_query($requete);
								$requete="alter table ".$prefixe."mf_".$j." add unique($field_keyName)";
								@mysql_query($requete);
							} else {
								$requete="create temporary table mf_".$j." ENGINE=MyISAM ".$main;
								@mysql_query($requete,$dbh);
								$requete="alter table mf_".$j." add idiot int(1)";
								@mysql_query($requete);
								$requete="alter table mf_".$j." add unique($field_keyName)";
								@mysql_query($requete);
							}
							
							if ($last_main_table) {
								if ($prefixe) {
									$requete="create temporary table ".$prefixe."and_result_".$j." ENGINE=MyISAM select ".$last_tables.".* from ".$last_tables." where exists ( select ".$prefixe."mf_".$j.".* from ".$prefixe."mf_".$j." where ".$last_tables.".notice_id=".$prefixe."mf_".$j.".notice_id)";
								} else {
									$requete="create temporary table and_result_".$j." ENGINE=MyISAM select ".$last_tables.".* from ".$last_tables." where exists ( select mf_".$j.".* from mf_".$j." where ".$last_tables.".notice_id=mf_".$j.".notice_id)";
								}
	 							mysql_query($requete,$dbh);
								mysql_query("drop table ".$last_tables,$dbh);
								
							} 
							if ($prefixe) {
								$last_tables=$prefixe."mf_".$j;
							} else {
								$last_tables="mf_".$j;
							}
							if ($prefixe) {
								$last_main_table = $prefixe."and_result_".$j;
							} else {
								$last_main_table = "and_result_".$j;
							}
						}
					} //else print $main;
				}
				if ($last_main_table){
					$main="select * from ".$last_main_table;
				}
			} elseif (array_key_exists($s[0],$this->pp)) {
				$datatype=$this->pp[$s[0]]->t_fields[$s[1]]["DATATYPE"];
				$df=$this->dynamicfields[$s[0]]["FIELD"][$this->get_id_from_datatype($datatype,$s[0])];
				$q_index=$df["QUERIES_INDEX"];
	 			$q=$df["QUERIES"][$q_index[$$op]];
 				//Pour chaque valeur du champ
				$last_main_table="";
				if (count($field)==0) $field[0]="";
				for ($j=0; $j<count($field); $j++) {
					if($q["KEEP_EMPTYWORD"])	$field[$j]=strip_empty_chars($field[$j]);
					elseif ($q["REGDIACRIT"]) $field[$j]=strip_empty_words($field[$j]);
					$main=$q["MAIN"];
					//Si il y a plusieurs termes possibles
					if ($q["MULTIPLE_WORDS"]) {
						$terms=explode(" ",$field[$j]);
						//Pour chaque terme
						$multiple_terms=array();
						for ($k=0; $k<count($terms); $k++) {
							$mt=str_replace("!!p!!",addslashes($terms[$k]),$q["MULTIPLE_TERM"]);
							$mt=str_replace("!!field!!",$s[1],$mt);	
							$multiple_terms[]=$mt;
						}
						$final_term=implode(" ".$q["MULTIPLE_OPERATOR"]." ",$multiple_terms);
						$main=str_replace("!!multiple_term!!",$final_term,$main);
					} else {
						$main=str_replace("!!p!!",addslashes($field[$j]),$main);
					}
					$main=str_replace("!!field!!",$s[1],$main);
					
					//Choix de l'operateur dans la liste
					if($q["DEFAULT_OPERATOR"]){
						$operator =$q["DEFAULT_OPERATOR"];
					} else {
						$operator = ($pmb_multi_search_operator?$pmb_multi_search_operator:"or");
					}
					if (count($field)>1) {
						if($operator == "or"){
								//Ou logique si plusieurs valeurs
								if ($prefixe) {
									$requete="create temporary table ".$prefixe."mf_".$j." ENGINE=MyISAM ".$main;	
									@mysql_query($requete,$dbh);
									$requete="alter table ".$prefixe."mf_".$j." add idiot int(1)";
									@mysql_query($requete);
									$requete="alter table ".$prefixe."mf_".$j." add unique($field_keyName)";
									@mysql_query($requete);
								} else {
									$requete="create temporary table mf_".$j." ENGINE=MyISAM ".$main;
									@mysql_query($requete,$dbh);
									$requete="alter table mf_".$j." add idiot int(1)";
									@mysql_query($requete);
									$requete="alter table mf_".$j." add unique($field_keyName)";
									@mysql_query($requete);
								}
			
								if ($last_main_table) {
									if ($prefixe) {
										$requete="insert ignore into ".$prefixe."mf_".$j." select ".$last_main_table.".* from ".$last_main_table;
									} else {
										$requete="insert ignore into mf_".$j." select ".$last_main_table.".* from ".$last_main_table;
									}
		 							mysql_query($requete,$dbh);
									//mysql_query("drop table mf_".$j,$dbh);
									mysql_query("drop table ".$last_main_table,$dbh);
								} //else mysql_query("drop table mf_".$j,$dbh);
								if ($prefixe) {
									$last_main_table=$prefixe."mf_".$j;
								} else {
									$last_main_table="mf_".$j;
								}
							} elseif($operator == "and"){
								
								//ET logique si plusieurs valeurs
								if ($prefixe) {
									$requete="create temporary table ".$prefixe."mf_".$j." ENGINE=MyISAM ".$main;	
									@mysql_query($requete,$dbh);
									$requete="alter table ".$prefixe."mf_".$j." add idiot int(1)";
									@mysql_query($requete);
									$requete="alter table ".$prefixe."mf_".$j." add unique($field_keyName)";
									@mysql_query($requete);
								} else {
									$requete="create temporary table mf_".$j." ENGINE=MyISAM ".$main;
									@mysql_query($requete,$dbh);
									$requete="alter table mf_".$j." add idiot int(1)";
									@mysql_query($requete);
									$requete="alter table mf_".$j." add unique($field_keyName)";
									@mysql_query($requete);
								}
								
								if ($last_main_table) {
									if ($prefixe) {
										$requete="create temporary table ".$prefixe."and_result_".$j." ENGINE=MyISAM select ".$last_tables.".* from ".$last_tables." where exists ( select ".$prefixe."mf_".$j.".* from ".$prefixe."mf_".$j." where ".$last_tables.".notice_id=".$prefixe."mf_".$j.".notice_id)";
									} else {
										$requete="create temporary table and_result_".$j." ENGINE=MyISAM select ".$last_tables.".* from ".$last_tables." where exists ( select mf_".$j.".* from mf_".$j." where ".$last_tables.".notice_id=mf_".$j.".notice_id)";
									}
		 							mysql_query($requete,$dbh);
									mysql_query("drop table ".$last_tables,$dbh);
									
								} 
								if ($prefixe) {
									$last_tables=$prefixe."mf_".$j;
								} else {
									$last_tables="mf_".$j;
								}
								if ($prefixe) {
									$last_main_table = $prefixe."and_result_".$j;
								} else {
									$last_main_table = "and_result_".$j;
								}
							}
						} //else print $main;
					}		
				
				if ($last_main_table)
					$main="select * from ".$last_main_table;	
			} elseif ($s[0]=="s") {
				//instancier la classe de traitement du champ special
    			$type=$this->specialfields[$s[1]]["TYPE"];
  		  		for ($is=0; $is<$this->tableau_speciaux["TYPE"]; $is++) {
					if ($this->tableau_speciaux["TYPE"][$is]["NAME"]==$type) {
						$sf=$this->specialfields[$s[1]];
						require_once($include_path."/search_queries/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
						$specialclass= new $this->tableau_speciaux["TYPE"][$is]["CLASS"]($s[1],$i,$sf,$this);
						$last_main_table=$specialclass->make_search();
						break;
					}
    			}
    		}
			if (!$last_main_table) {
    			if ($prefixe) {
    				$table=$prefixe."t_".$i."_".$search[$i];
    				$requete="create temporary table ".$prefixe."t_".$i."_".$search[$i]." ENGINE=MyISAM ".$main;
    				mysql_query($requete,$dbh);
					$requete="alter table ".$prefixe."t_".$i."_".$search[$i]." add idiot int(1)";
					@mysql_query($requete);
					$requete="alter table ".$prefixe."t_".$i."_".$search[$i]." add unique($field_keyName)";
					mysql_query($requete);
    			} else {
    				$table="t_".$i."_".$search[$i];
	 				$requete="create temporary table t_".$i."_".$search[$i]." ENGINE=MyISAM ".$main;
    				mysql_query($requete,$dbh);
					$requete="alter table t_".$i."_".$search[$i]." add idiot int(1)";
					@mysql_query($requete);
					$requete="alter table t_".$i."_".$search[$i]." add unique($field_keyName)";
					mysql_query($requete);
    			}
			} else {
				$table=$last_main_table;
			}
			//$requete="drop table ".$last_main_table;
			//mysql_query($requete);
			if ($prefixe) {
				$requete="create temporary table ".$prefixe."t".$i." ENGINE=MyISAM ";
			} else {
				$requete="create temporary table t".$i." ENGINE=MyISAM ";
			}
			$isfirst_criteria=false;
			switch ($$inter) {
				case "and":
					$requete.="select ".$table.".* from $last_table,$table where ".$table.".$field_keyName=".$last_table.".$field_keyName and $table.idiot is null and $last_table.idiot is null";
					@mysql_query($requete,$dbh);
					break;
				case "or":
					//Si la table précédente est vide, c'est comme au premier jour !
					$requete_c="select count(*) from ".$last_table;
					if (!mysql_result(mysql_query($requete_c),0,0)) {
						$isfirst_criteria=true;
					} else {
						$requete.="select * from ".$table;
						@mysql_query($requete,$dbh);
						if ($prefixe) {
							$requete="alter table ".$prefixe."t".$i." add idiot int(1)";
							@mysql_query($requete);
							$requete="alter table ".$prefixe."t".$i." add unique($field_keyName)";
							@mysql_query($requete);
						} else {
							$requete="alter table t".$i." add idiot int(1)";
							@mysql_query($requete);
							$requete="alter table t".$i." add unique($field_keyName)";
							@mysql_query($requete);
						}
						if ($prefixe) {
							$requete="insert into ".$prefixe."t".$i." ($field_keyName,idiot) select distinct ".$last_table.".".$field_keyName.",".$last_table.".idiot from ".$last_table." left join ".$table." on ".$last_table.".$field_keyName=".$table.".$field_keyName where ".$table.".$field_keyName is null";
						} else {
							$requete="insert into t".$i." ($field_keyName,idiot) select distinct ".$last_table.".".$field_keyName.",".$last_table.".idiot from ".$last_table." left join ".$table." on ".$last_table.".$field_keyName=".$table.".$field_keyName where ".$table.".$field_keyName is null";
							//print $requete;
						}
						@mysql_query($requete,$dbh);
					}
					break;
				case "ex":
					//$requete_not="create temporary table ".$table."_b select notices.notice_id from notices left join ".$table." on notices.notice_id=".$table.".notice_id where ".$table.".notice_id is null";
					//@mysql_query($requete_not);
					//$requete_not="alter table ".$table."_b add idiot int(1), add unique(notice_id)";
					//@mysql_query($requete_not);
					$requete.="select ".$last_table.".* from $last_table left join ".$table." on ".$table.".$field_keyName=".$last_table.".$field_keyName where ".$table.".$field_keyName is null";
					@mysql_query($requete);
					//$requete="drop table ".$table."_b";
					//@mysql_query($requete);
					if ($prefixe) {
						$requete="alter table ".$prefixe."t".$i." add idiot int(1)";
						@mysql_query($requete);
						$requete="alter table ".$prefixe."t".$i." add unique($field_keyName)";
						@mysql_query($requete);
					} else {
						$requete="alter table t".$i." add idiot int(1)";
						@mysql_query($requete);
						$requete="alter table t".$i." add unique($field_keyName)";
						@mysql_query($requete);
					}
					break;
				default:
					$isfirst_criteria=true;
					@mysql_query($requete,$dbh);
					$requete="alter table $table add idiot int(1)";
					@mysql_query($requete);
					$requete="alter table $table add unique($field_keyName)";
					@mysql_query($requete);
					break;
			}
			if (!$isfirst_criteria) {
				mysql_query("drop table if exists $last_table",$dbh);
				mysql_query("drop table if exists $table",$dbh);
				if ($prefixe) {
					$last_table=$prefixe."t".$i;
				} else {
					$last_table="t".$i;	
				}
			} else {
				mysql_query("drop table if exists $last_table",$dbh);
				$last_table=$table;
			}
    	}
    	return $last_table;
    }
    
    function make_hidden_search_form($url,$form_name="search_form",$target="",$close_form=true) {
    	global $search;
    	global $charset;
    	global $page;
    	
    	$r="<form name='$form_name' action='$url' style='display:none' method='post'";
    	if ($target) $r.=" target='$target'";
    	$r.=">\n";
    	
    	for ($i=0; $i<count($search); $i++) {
    		$inter="inter_".$i."_".$search[$i];
    		global $$inter;
    		$op="op_".$i."_".$search[$i];
    		global $$op;
    		$field_="field_".$i."_".$search[$i];
    		global $$field_;
    		$field=$$field_;
    		//Recuperation des variables auxiliaires
    		$fieldvar_="fieldvar_".$i."_".$search[$i];
    		global $$fieldvar_;
    		$fieldvar=$$fieldvar_;
    		
    		if (!is_array($fieldvar)) $fieldvar=array();
    		
    		$r.="<input type='hidden' name='search[]' value='".htmlentities($search[$i],ENT_QUOTES,$charset)."'/>";
    		$r.="<input type='hidden' name='".$inter."' value='".htmlentities($$inter,ENT_QUOTES,$charset)."'/>";
    		$r.="<input type='hidden' name='".$op."' value='".htmlentities($$op,ENT_QUOTES,$charset)."'/>";
    		for ($j=0; $j<count($field); $j++) {
    			$r.="<input type='hidden' name='".$field_."[]' value='".htmlentities($field[$j],ENT_QUOTES,$charset)."'/>";
    		}
    		reset($fieldvar);
    		while (list($var_name,$var_value)=each($fieldvar)) {
    			for ($j=0; $j<count($var_value); $j++) {
    				$r.="<input type='hidden' name='".$fieldvar_."[".$var_name."][]' value='".htmlentities($var_value[$j],ENT_QUOTES,$charset)."'/>";
    			}
    		}
    	}
    	$r.="<input type='hidden' name='page' value='$page'/>";
    	global $dsi_active;
    	if ($dsi_active) {
    		global $id_equation;
    		$r.="<input type='hidden' name='id_equation' value='$id_equation'/>";
    		}
    	if ($close_form) $r.="</form>";
    	return $r;
    }
    
    function make_human_query() {
    	global $search;
    	global $msg;
    	global $charset;
    	global $include_path;
		global $pmb_multi_search_operator;
		
		$r="";
    	for ($i=0; $i<count($search); $i++) {
    		$s=explode("_",$search[$i]);
    		if ($s[0]=="f") {
    			$title=$this->fixedfields[$s[1]]["TITLE"]; 
    		} elseif(array_key_exists($s[0],$this->pp)){
    			$title=$this->pp[$s[0]]->t_fields[$s[1]]["TITRE"];
    		} elseif ($s[0]=="s") {
    			$title=$this->specialfields[$s[1]]["TITLE"];
    		}
    		$op="op_".$i."_".$search[$i];
    		global $$op;
    		//faire un test de classe et getop()
    		$operator=$this->operators[$$op];
    		$field_="field_".$i."_".$search[$i];
    		global $$field_;
    		$field=$$field_;
    		
    		//Recuperation des variables auxiliaires
    		$fieldvar_="fieldvar_".$i."_".$search[$i];
    		global $$fieldvar_;
    		$fieldvar=$$fieldvar_;
    		if (!is_array($fieldvar)) $fieldvar=array(); 
    		
    		$field_aff=array();
    		$fieldvar_aff=array();
    		$operator_multi = ($pmb_multi_search_operator?$pmb_multi_search_operator:"or");
    		if (array_key_exists($s[0],$this->pp)) {
    			$datatype=$this->pp[$s[0]]->t_fields[$s[1]]["DATATYPE"];
    			$df=$this->dynamicfields[$s[0]]["FIELD"][$this->get_id_from_datatype($datatype,$s[0])];
				$q_index=$df["QUERIES_INDEX"];
	 			$q=$df["QUERIES"][$q_index[$$op]];
    			if ($q["DEFAULT_OPERATOR"])
    				$operator_multi=$q["DEFAULT_OPERATOR"];
    			for ($j=0; $j<count($field); $j++) {
	    			$field_aff[$j]=$this->pp[$s[0]]->get_formatted_output(array(0=>$field[$j]),$s[1]);
    			}
    		} elseif ($s[0]=="f") {
    			$ff=$this->fixedfields[$s[1]];
	 			$q_index=$ff["QUERIES_INDEX"];
	 			$q=$ff["QUERIES"][$q_index[$$op]];
    			if ($q["DEFAULT_OPERATOR"])
    				$operator_multi=$q["DEFAULT_OPERATOR"];
    			switch ($this->fixedfields[$s[1]]["INPUT_TYPE"]) {
    				case "list":
    					$options=$this->fixedfields[$s[1]]["INPUT_OPTIONS"]["OPTIONS"][0];
    					$opt=array();
    					for ($j=0; $j<count($options["OPTION"]); $j++) {
    						if (substr($options["OPTION"][$j]["value"],0,4)=="msg:") {
    							$opt[$options["OPTION"][$j]["VALUE"]]=$msg[substr($options["OPTION"][$j]["value"],4,strlen($options["OPTION"][$j]["value"])-4)];
    						} else {
    							$opt[$options["OPTION"][$j]["VALUE"]]=$options["OPTION"][$j]["value"];
    						}
    					}
    					for ($j=0; $j<count($field); $j++) {
    						$field_aff[$j]=$opt[$field[$j]];
    					}
    					break;
    				case "query_list":
    					$requete=$this->fixedfields[$s[1]]["INPUT_OPTIONS"]["QUERY"][0]["value"];
    					$resultat=mysql_query($requete);
    					$opt=array();
    					while ($r_=@mysql_fetch_row($resultat)) {
    						$opt[$r_[0]]=$r_[1];
    					}
    					for ($j=0; $j<count($field); $j++) {
    						$field_aff[$j]=$opt[$field[$j]];
    					}
    					break;
    				case "marc_list":
    					$opt=new marc_list($this->fixedfields[$s[1]]["INPUT_OPTIONS"]["NAME"][0]["value"]);
    					for ($j=0; $j<count($field); $j++) {
    						$field_aff[$j]=$opt->table[$field[$j]];
    					}
    					break;
    				case "date":
    					$field_aff[0]=format_date($field[0]);
    					break;
    				default:
    					$field_aff=$field;
    					break;		
    			}
    			
    			//Ajout des variables si necessaire
    			reset($fieldvar);
    			$fieldvar_aff=array();
    			while (list($var_name,$var_value)=each($fieldvar)) {
    				//Recherche de la variable par son nom
    				$vvar=$this->fixedfields[$s[1]]["VAR"];
    				for ($j=0; $j<count($vvar); $j++) {
    					if (($vvar[$j]["TYPE"]=="input")&&($vvar[$j]["NAME"]==$var_name)) {
    						
    						//Calcul de la visibilite
    						$varname=$vvar[$j]["NAME"];
   		 					$visibility=1;
   		 					$vis=$vvar[$j]["OPTIONS"]["VAR"][0];
   		 					if ($vis["NAME"]) {
   		 						$vis_name=$vis["NAME"];
   		 						global $$vis_name;
   		 						if ($vis["VISIBILITY"]=="no") $visibility=0;
   		 						for ($k=0; $k<count($vis["VALUE"]); $k++) {
   		 							if ($vis["VALUE"][$k]["value"]==$$vis_name) {
   		 								if ($vis["VALUE"][$k]["VISIBILITY"]=="no") $sub_vis=0; else $sub_vis=1;
   		 								if ($vis["VISIBILITY"]=="no") $visibility|=$sub_vis; else $visibility&=$sub_vis;
   		 								break;
   		 							}
   		 						}
   		 					}
    						
    						$var_list_aff=array();
    						$flag_aff = false;
    						
    						if ($visibility) {		
    							switch ($vvar[$j]["OPTIONS"]["INPUT"][0]["TYPE"]) {
    								case "query_list":
    									$query_list=$vvar[$j]["OPTIONS"]["INPUT"][0]["QUERY"][0]["value"];
       									$r_list=mysql_query($query_list);
    									while ($line=mysql_fetch_array($r_list)) {
    										$as=array_search($line[0],$var_value);
    										if (($as!==false)&&($as!==NULL)) {
    											$var_list_aff[]=$line[1];
    										}
    									}
    									if($vvar[$j]["OPTIONS"]["INPUT"][0]["QUERY"][0]["ALLCHOICE"] == "yes" && count($var_list_aff) == 0){
    										$var_list_aff[]=$msg[substr($vvar[$j]["OPTIONS"]["INPUT"][0]["QUERY"][0]["TITLEALLCHOICE"],4,strlen($vvar[$j]["OPTIONS"]["INPUT"][0]["QUERY"][0]["TITLEALLCHOICE"])-4)];
    									}
    									$fieldvar_aff[]=implode(" ".$msg["search_or"]." ",$var_list_aff); 
    									$flag_aff=true;
    									break;
    								case "checkbox":
    									$value = $var_value[0];
    									$label_list = $vvar[$j]["OPTIONS"]["INPUT"][0]["COMMENTS"][0]["LABEL"];
    									for($indice=0;$indice<count($label_list);$indice++){
    										if($value == $label_list[$indice]["VALUE"]){
    											$libelle = $label_list[$indice]["value"];
    											break; 
    										}
    									}
    									    									
    									$fieldvar_aff[]=$libelle;
    									$flag_aff=true;
    									break;
    							}
    							if($flag_aff) $fieldvar_aff[count($fieldvar_aff)-1]=$vvar[$j]["COMMENT"]." : ".$fieldvar_aff[count($fieldvar_aff)-1];
    						}
    					}
    				}
    			}
    		} elseif ($s[0]=="s") {
    			//appel de la fonction make_human_query de la classe du champ special
    			//Recherche du type
    			$type=$this->specialfields[$s[1]]["TYPE"];
    			for ($is=0; $is<$this->tableau_speciaux["TYPE"]; $is++) {
					if ($this->tableau_speciaux["TYPE"][$is]["NAME"]==$type) {
						$sf=$this->specialfields[$s[1]];
						require_once($include_path."/search_queries/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
						$specialclass= new $this->tableau_speciaux["TYPE"][$is]["CLASS"]($s[1],$i,$sf,$this);
						$field_aff=$specialclass->make_human_query();
						$field_aff[0]=html_entity_decode(strip_tags($field_aff[0]),ENT_QUOTES,$charset);
						break;
					}
    			}
    		}
    		
	   		switch ($operator_multi) {
    			case "and":
    				$op_list=$msg["search_and"];
    				break;
    			case "or":
    				$op_list=$msg["search_or"];
    				break;
    			default:
    				$op_list=$msg["search_or"];
    				break;
    		}
    		$texte=implode(" ".$op_list." ",$field_aff);
    		if (count($fieldvar_aff)) $texte.=" [".implode(" ; ",$fieldvar_aff)."]";
    		
    		$inter="inter_".$i."_".$search[$i];
    		global $$inter;
    		switch ($$inter) {
    			case "and":
    				$inter_op=$msg["search_and"];
    				break;
    			case "or":
    				$inter_op=$msg["search_or"];
    				break;
    			case "ex":
    				$inter_op=$msg["search_exept"];
    				break;
    			default:
    				$inter_op="";
    				break;
    		}
    		
    		if ($inter_op) $inter_op="<strong>".htmlentities($inter_op,ENT_QUOTES,$charset)."</strong>";
    		$r.=$inter_op." <i><strong>".htmlentities($title,ENT_QUOTES,$charset)."</strong> ".htmlentities($operator,ENT_QUOTES,$charset)." (".htmlentities($texte,ENT_QUOTES,$charset).")</i> ";
    	}
    	return $r;
    }
    
    function make_unimarc_query() {
    	global $search;
    	global $msg;
    	global $charset;
    	global $include_path;

		$mt=array();
		
    	for ($i=0; $i<count($search); $i++) {
    		$sub="";
    		$s=explode("_",$search[$i]);
    		if ($s[0]=="f") {
    			$title=$this->fixedfields[$s[1]]["UNIMARCFIELD"]; 
    		} elseif (array_key_exists($s[0],$this->pp)){
    			$title=$this->pp[$s[0]]->t_fields[$s[1]]["UNIMARCFIELD"];
    		} elseif ($s[0]=="s") {
    			$title=$this->specialfields[$s[1]]["UNIMARCFIELD"];
    		}
    		$op="op_".$i."_".$search[$i];
    		global $$op;
    		//faire un test de classe et getop()
    		//$operator=$this->operators[$$op];
    		$field_="field_".$i."_".$search[$i];
    		global $$field_;
    		$field=$$field_;
    		
    		//Recuperation des variables auxiliaires
    		$fieldvar_="fieldvar_".$i."_".$search[$i];
    		global $$fieldvar_;
    		$fieldvar=$$fieldvar_;
    		if (!is_array($fieldvar)) $fieldvar=array(); 
    		
    		$field_aff=array();
    		
    		if(array_key_exists($s[0],$this->pp)){
    			for ($j=0; $j<count($field); $j++) {
    				$field_aff[$j]=$this->pp[$s[0]]->get_formatted_output(array(0=>$field[$j]),$s[1]);
    			}
    		} elseif ($s[0]=="f") {
    			switch ($this->fixedfields[$s[1]]["INPUT_TYPE"]) {
    				case "list":
    					$options=$this->fixedfields[$s[1]]["INPUT_OPTIONS"]["OPTIONS"][0];
    					$opt=array();
    					for ($j=0; $j<count($options["OPTION"]); $j++) {
    						if (substr($options["OPTION"][$j]["value"],0,4)=="msg:") {
    							$opt[$options["OPTION"][$j]["VALUE"]]=$msg[substr($options["OPTION"][$j]["value"],4,strlen($options["OPTION"][$j]["value"])-4)];
    						} else {
    							$opt[$options["OPTION"][$j]["VALUE"]]=$options["OPTION"][$j]["value"];
    						}
    					}
    					for ($j=0; $j<count($field); $j++) {
    						$field_aff[$j]=$opt[$field[$j]];
    					}
    					break;
    				case "query_list":
    					$requete=$this->fixedfields[$s[1]]["INPUT_OPTIONS"]["QUERY"][0]["value"];
    					$resultat=mysql_query($requete);
    					$opt=array();
    					while ($r_=@mysql_fetch_row($resultat)) {
    						$opt[$r_[0]]=$r_[1];
    					}
    					for ($j=0; $j<count($field); $j++) {
    						$field_aff[$j]=$opt[$field[$j]];
    					}
    					break;
    				case "marc_list":
    					$opt=new marc_list($this->fixedfields[$s[1]]["INPUT_OPTIONS"]["NAME"][0]["value"]);
    					for ($j=0; $j<count($field); $j++) {
    						$field_aff[$j]=$opt->table[$field[$j]];
    					}
    					break;
    				case "date":
    					$field_aff[0]=format_date($field[0]);
    					break;
    				default:
    					$field_aff=$field;
    					break;		
    			}
    			
    			//Ajout des variables si necessaire
    			reset($fieldvar);
    			$fieldvar_aff=array();
    			while (list($var_name,$var_value)=each($fieldvar)) {
    				//Recherche de la variable par son nom
    				$vvar=$this->fixedfields[$s[1]]["VAR"];
    				for ($j=0; $j<count($vvar); $j++) {
    					if (($vvar[$j]["TYPE"]=="input")&&($vvar[$j]["NAME"]==$var_name)) {
    						
    						//Calcul de la visibilite
    						$varname=$vvar[$j]["NAME"];
   		 					$visibility=1;
   		 					$vis=$vvar[$j]["OPTIONS"]["VAR"][0];
   		 					if ($vis["NAME"]) {
   		 						$vis_name=$vis["NAME"];
   		 						global $$vis_name;
   		 						if ($vis["VISIBILITY"]=="no") $visibility=0;
   		 						for ($k=0; $k<count($vis["VALUE"]); $k++) {
   		 							if ($vis["VALUE"][$k]["value"]==$$vis_name) {
   		 								if ($vis["VALUE"][$k]["VISIBILITY"]=="no") $sub_vis=0; else $sub_vis=1;
   		 								if ($vis["VISIBILITY"]=="no") $visibility|=$sub_vis; else $visibility&=$sub_vis;
   		 								break;
   		 							}
   		 						}
   		 					}
    						
    						$var_list_aff=array();
    						
    						if ($visibility) {		
    							switch ($vvar[$j]["OPTIONS"]["INPUT"][0]["TYPE"]) {
    								case "query_list":
    									$query_list=$vvar[$j]["OPTIONS"]["INPUT"][0]["QUERY"][0]["value"];
       									$r_list=mysql_query($query_list);
    									while ($line=mysql_fetch_array($r_list)) {
    										$as=array_search($line[0],$var_value);
    										if (($as!==false)&&($as!==NULL)) {
    											$var_list_aff[]=$line[1];
    										}
    									}
    									$fieldvar_aff[]=implode(" ".$msg["search_or"]." ",$var_list_aff);
    									break;
    							}
    							$fieldvar_aff[count($fieldvar_aff)-1]=$vvar[$j]["COMMENT"]." : ".$fieldvar_aff[count($fieldvar_aff)-1];
    						}
    					}
    				}
    			}
    		} elseif ($s[0]=="s") {
    			//appel de la fonction make_unimarc_query de la classe du champ special
    			//Recherche du type
    			$type=$this->specialfields[$s[1]]["TYPE"];
    			for ($is=0; $is<$this->tableau_speciaux["TYPE"]; $is++) {
					if ($this->tableau_speciaux["TYPE"][$is]["NAME"]==$type) {
						$sf=$this->specialfields[$s[1]];
						require_once($include_path."/search_queries/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
						$specialclass= new $this->tableau_speciaux["TYPE"][$is]["CLASS"]($s[1],$i,$sf,$this);
						$sub=$specialclass->make_unimarc_query();
						break;
					}
    			}
    		}
    		
    		$inter="inter_".$i."_".$search[$i];
    		global $$inter;
    		
    		$mterm=new mterm($title,$$op,$field_aff,$fieldvar_aff,$$inter);
    		if ((is_array($sub))&&(count($sub))) $mterm->set_sub($sub); else if (is_array($sub)) unset($mterm);
    		if ($mterm) $mt[]=$mterm;
    	}
    	return $mt;
    }
    
    function get_results($url,$url_to_search_form,$hidden_form=true,$search_target="") {
    	global $dbh;
    	global $begin_result_liste;
    	global $nb_per_page_search;
    	global $page;
    	global $charset;
    	global $search;
    	global $msg;
    	global $pmb_nb_max_tri;
    	global $affich_tris_result_liste;
    	global $pmb_allow_external_search;
 
    	$start_page=$nb_per_page_search*$page;
    	
    	//Y-a-t-il des champs ?
    	if (count($search)==0) {
    		array_pop($_SESSION["session_history"]);
    		error_message_history($msg["search_empty_field"], $msg["search_no_fields"], 1);
    		exit();
    	}
    	
    	//Verification des champs vides
    	for ($i=0; $i<count($search); $i++) {
    		$op="op_".$i."_".$search[$i];
    		global $$op;
     		$field_="field_".$i."_".$search[$i];
    		global $$field_;
    		$field=$$field_;
    		$s=explode("_",$search[$i]);
    		$bool=false;
    		if ($s[0]=="f") {
    			$champ=$this->fixedfields[$s[1]]["TITLE"];
    			if ((string)$field[0]=="") {
    				$bool=true;
    			}
    		} elseif(array_key_exists($s[0],$this->pp)) {
    			$champ=$this->pp[$s[0]]->t_fields[$s[1]]["TITRE"];
    			if ((string)$field[0]=="") {
    				$bool=true;
    			}
    		} elseif($s[0]=="s") {
    			$champ=$this->specialfields[$s[1]]["TITLE"];
    			$type=$this->specialfields[$s[1]]["TYPE"];
		 		for ($is=0; $is<$this->tableau_speciaux["TYPE"]; $is++) {
					if ($this->tableau_speciaux["TYPE"][$is]["NAME"]==$type) {
						$sf=$this->specialfields[$s[1]];
						global $include_path;
						require_once($include_path."/search_queries/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
						$specialclass= new $this->tableau_speciaux["TYPE"][$is]["CLASS"]($s[1],$sf,$i,$this);
						$bool=$specialclass->is_empty($field);
						break;
					}
				}
    		}
    		if (($bool)&&(!$this->op_empty[$$op])) {
    			array_pop($_SESSION["session_history"]);
    			error_message_history($msg["search_empty_field"], sprintf($msg["search_empty_error_message"],$champ), 1);
    			exit();
    		}
    	}
    	
    	$table=$this->make_search();
    	return $table;
 
    }
        
    function show_results($url,$url_to_search_form,$hidden_form=true,$search_target="", $acces=false) {
    	global $dbh;
    	global $begin_result_liste;
    	global $nb_per_page_search;
    	global $page;
    	global $charset;
    	global $search;
    	global $msg;
    	global $pmb_nb_max_tri;
    	global $affich_tris_result_liste;
    	global $pmb_allow_external_search;
    	global $debug;
		global $gestion_acces_active, $gestion_acces_user_notice,$PMBuserid, $pmb_allow_external_search;
		global $link_bulletin;

 				
		$start_page=$nb_per_page_search*$page;
    	
    	//Y-a-t-il des champs ?
    	if (count($search)==0) {
    		array_pop($_SESSION["session_history"]);
    		error_message_history($msg["search_empty_field"], $msg["search_no_fields"], 1);
    		exit();
    	}
    	$recherche_externe=true;//Savoir si l'on peut faire une recherche externe à partir des critères choisis
    	//Verification des champs vides
    	for ($i=0; $i<count($search); $i++) {
    		$op="op_".$i."_".$search[$i];
    		global $$op;
     		$field_="field_".$i."_".$search[$i];
    		global $$field_;
    		$field=$$field_;
    		$s=explode("_",$search[$i]);
    		$bool=false;
    		if ($s[0]=="f") {
    			$champ=$this->fixedfields[$s[1]]["TITLE"];
    			if ((string)$field[0]=="") {
    				$bool=true;
    			}
    		} elseif(array_key_exists($s[0],$this->pp)) {
    			$recherche_externe=false;
    			$champ=$this->pp[$s[0]]->t_fields[$s[1]]["TITRE"];
    			if ((string)$field[0]=="") {
    				$bool=true;
    			}
    		} elseif($s[0]=="s") {
    			$recherche_externe=false;
    			$champ=$this->specialfields[$s[1]]["TITLE"];
		 		$type=$this->specialfields[$s[1]]["TYPE"];
		 		for ($is=0; $is<$this->tableau_speciaux["TYPE"]; $is++) {
					if ($this->tableau_speciaux["TYPE"][$is]["NAME"]==$type) {
						$sf=$this->specialfields[$s[1]];
						global $include_path;
						require_once($include_path."/search_queries/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
						$specialclass= new $this->tableau_speciaux["TYPE"][$is]["CLASS"]($s[1],$sf,$i,$this);
						$bool=$specialclass->is_empty($field);
						break;
					}
				}
    		}
    		if (($bool)&&(!$this->op_empty[$$op])) {
    			array_pop($_SESSION["session_history"]);
    			error_message_history($msg["search_empty_field"], sprintf($msg["search_empty_error_message"],$champ), 1);
    			exit();
    		}
    	}
    	
    	$table=$this->make_search();

		if ($acces==true && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	    	$this->filter_searchtable_from_accessrights($table, $PMBuserid);
		}
    	
		$requete="select count(1) from $table";
		if($res=mysql_query($requete)){
			$nb_results=mysql_result($res,0,0); 
		}else{
			array_pop($_SESSION["session_history"]);
    		error_message_history("",$msg["search_impossible"], 1);
    		exit();
		}
    	
    	
    	//gestion du tri
    	$has_sort = false;
 		if ($nb_results <= $pmb_nb_max_tri) {
			if ($_SESSION["tri"]) {
				$sort = new sort('notices','base');
				$requete = $sort->appliquer_tri($_SESSION["tri"],"SELECT * FROM " . $table, "notice_id", $start_page, $nb_per_page_search);
				$table = $sort->table_tri_tempo;	
				$has_sort = true; 
			}
		}
		// fin gestion tri
    	//Y-a-t-il une erreur lors de la recherche ?
    	if ($this->error_message) {
    		array_pop($_SESSION["session_history"]);
    		error_message_history("", $this->error_message, 1);
    		exit();
    	}
    	
    	if ($hidden_form)
    		print $this->make_hidden_search_form($url);
    	
    	$requete="select $table.*,notices.niveau_biblio from ".$table.",notices where notices.notice_id=$table.notice_id"; 
    	if(count($search) > 1 && !$has_sort) 
    		$requete .= " order by index_serie, tnvol, index_sew";
    	$requete .= " limit ".$start_page.",".$nb_per_page_search;
    	
    	$resultat=mysql_query($requete,$dbh);
    	
    	$human_requete = $this->make_human_query();
    	print "<strong>".$msg["search_search_extended"]."</strong> : ".$human_requete ;
		if ($debug) print "<br />".$this->serialize_search();
		if ($nb_results) {
			print " => ".$nb_results." ".$msg["1916"]."<br />\n";
			print $begin_result_liste;
			if ($this->rec_history) {
				//Affichage des liens paniers et impression
				$current=$_SESSION["CURRENT"];
				if ($current!==false) {
					$tri_id_info = $_SESSION["tri"] ? "&sort_id=".$_SESSION["tri"] : "";
					print "&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$current&action=print_prepare$tri_id_info','print',600,700,-2,-2,'scrollbars=yes,menubar=0,resizable=yes'); return false;\"><img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&action_print=print_prepare$tri_id_info','print',500,600,-2,-2,'scrollbars=yes,menubar=0'); w.focus(); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
					if ($pmb_allow_external_search){
						if($recherche_externe){
							$tag_a="href='catalog.php?categ=search&mode=7&from_mode=6&external_type=multi'";
						}else{
							$tag_a="onClick=\"alert('".$msg["search_interdite_externe"]."')\"";
						}
						print "&nbsp;<a ".$tag_a." ><img src='./images/external_search.png' border='0' align='center' alt=\"".$msg["connecteurs_external_search_sources"]."\" title=\"".$msg["connecteurs_external_search_sources"]."\"/></a>";
					}
					if ($nb_results<=$pmb_nb_max_tri) {
						print $affich_tris_result_liste;
					}
				}
			}
		} else print "<br />".$msg["1915"]." ";
		print "<input type='button' class='bouton' onClick=\"document.search_form.action='$url_to_search_form'; document.search_form.target='$search_target'; document.search_form.submit(); return false;\" value=\"".$msg["search_back"]."\"/>";
		global $dsi_active;
		if ($dsi_active) {
			global $id_equation, $priv_pro, $id_empr;
			if ($id_equation) $mess_bouton = $msg['dsi_sauvegarder_equation'] ;
				else $mess_bouton = $msg["dsi_transformer_equation"] ;
			print "&nbsp;<input  type='button' class='bouton' onClick=\"document.forms['transform_dsi'].submit(); \" value=\"".$mess_bouton."\"/>
						<form name='transform_dsi' style='display:none;' method='post' action='./dsi.php'>";
			if ($priv_pro=="PRI") print "
						<input type=hidden name='categ' value='bannettes' />
						<input type=hidden name='sub' value='abo' />
						<input type=hidden name='suite' value='transform_equ' />
						<input type=hidden name='id_equation' value='$id_equation' />
						<input type=hidden name='id_empr' value='$id_empr' />
						<input type=hidden name='requete' value='".htmlentities($this->serialize_search(),ENT_QUOTES,$charset)."' />
						</form>";
				else print "
						<input type=hidden name='categ' value='equations' />
						<input type=hidden name='sub' value='gestion' />
						<input type=hidden name='suite' value='transform' />
						<input type=hidden name='id_equation' value='$id_equation' />
						<input type=hidden name='requete' value='".htmlentities($this->serialize_search(),ENT_QUOTES,$charset)."' />
						</form>";
			}
			
		// transformation de la recherche en multicriteres: on reposte tout avec mode=8
		print "&nbsp;<input  type='button' class='bouton' onClick='document.search_transform.submit(); return false;' value=\"".$msg["search_notice_to_expl_transformation"]."\"/>";
		print "<form name='search_transform' action='./catalog.php?categ=search&mode=8&sub=launch' style=\"display:none\" method='post'>";	
		$memo_search="";
		foreach($_POST as $key =>$val) {
			if($val) {
				if(is_array($val)) {
					foreach($val as $cle=>$val_array) {
						if(is_array($val_array)){
							foreach($val_array as $valeur){
								$memo_search.= "<input type='hidden' name=\"".$key."[".$cle."][]\" value='".htmlentities($valeur,ENT_QUOTES,$charset)."'/>";
							}
						} else $memo_search.= "<input type='hidden' name='".$key."[]' value='".htmlentities($val_array,ENT_QUOTES,$charset)."'/>";
					}
				}
				else $memo_search.="<input type='hidden' name='$key' value='$val'/>";
			}		
		}	
		print "$memo_search</form>";
		
		//transformation en set pour connecteur externe
		global $id_connector_set;
		$id_connector_set+=0;
		//Il faut que l'on soit passé par le formulaire d'édition de set pour avoir $id_connector_set pour ne pas avoir le bouton tout le temps vu qu'il sert rarement
		if ($pmb_allow_external_search && (SESSrights & ADMINISTRATION_AUTH) && $id_connector_set) {
			//Il faut qu'il y ait des sets multi critères si on veut pouvoir associer la recherche à quelque chose
			if (connector_out_sets::get_typed_set_count(2)) {
				print '<form name="export_to_outset" style="display:none;" method="post" action="./admin.php?categ=connecteurs&sub=out_sets&action=import_notice_search_into_set&candidate_id='.$id_connector_set.'"><input type="hidden" name="toset_search" value="'.htmlentities($this->serialize_search(),ENT_QUOTES,$charset).'" /></form>';
				print '&nbsp;<input type="button" onClick="document.forms[\'export_to_outset\'].submit(); " class="bouton" value="'.htmlentities($msg["search_notice_to_connector_out_set"] ,ENT_QUOTES, $charset).'">';
			}
		}
		
    	while ($r=mysql_fetch_object($resultat)) {
    		if($nb++>5)	$recherche_ajax_mode=1;
    		switch($r->niveau_biblio) {
				case 'm' :
					// notice de monographie
					$nt = new mono_display($r->notice_id, 6, $this->link, 1, $this->link_expl, '', $this->link_explnum,1, 0, 1, 1, "", 1, false,true,$recherche_ajax_mode);
					break ;
				case 's' :
				case 'a' :
					// on a affaire a un periodique
					// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true, $draggable=0 ) {
					$nt = new serial_display($r->notice_id, 6, $this->link_serial, $this->link_analysis, $this->link_bulletin, "", $this->link_explnum_serial, 0, 0, 1, 1, true, 1  ,$recherche_ajax_mode);
					break;
				case 'b' :
					// on a affaire a un bulletin
					$rqt_bull_info = "SELECT s.notice_id as id_notice_mere, bulletin_id as id_du_bulletin, b.notice_id as id_notice_bulletin FROM notices as s, notices as b, bulletins WHERE b.notice_id=$r->notice_id and s.notice_id=bulletin_notice and num_notice=b.notice_id";
					$bull_ids=@mysql_fetch_object(mysql_query($rqt_bull_info));
					if(!$link_bulletin){
						$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id='.$bull_ids->id_du_bulletin;
					} else {
						$link_bulletin = str_replace("!!id!!",$bull_ids->id_du_bulletin,$link_bulletin);
					}					
					$nt = new mono_display($r->notice_id, 6, $link_bulletin, 1, $this->link_expl, '', $this->link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
					$link_bulletin ='';
					break;
			}
    		echo "<div class='row'>".$nt->result."</div>";
    	}

    	//Gestion de la pagination
    	if ($nb_results) {
	  	  	$n_max_page=ceil($nb_results/$nb_per_page_search);
	   	 	
	   	 	if (!$page) $page_en_cours=0 ;
				else $page_en_cours=$page ;
		
	   	 	// affichage du lien precedent si necessaire
   		 	if ($page>0) {
   		 		$nav_bar .= "<a href='#' onClick='document.search_form.page.value-=1; ";
   		 		if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
   		 		$nav_bar .= "document.search_form.submit(); return false;'>";
   	 			$nav_bar .= "<img src='./images/left.gif' border='0'  title='".$msg[48]."' alt='[".$msg[48]."]' hspace='3' align='middle'/>";
    			$nav_bar .= "</a>";
    		}
        	
			$deb = $page_en_cours - 10 ;
			if ($deb<0) $deb=0;
			for($i = $deb; ($i < $n_max_page) && ($i<$page_en_cours+10); $i++) {
				if($i==$page_en_cours) $nav_bar .= "<strong>".($i+1)."</strong>";
					else {
						$nav_bar .= "<a href='#' onClick=\"if ((isNaN(document.search_form.page.value))||(document.search_form.page.value=='')) document.search_form.page.value=1; else document.search_form.page.value=".($i)."; ";
    					if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
		    			$nav_bar .= "document.search_form.submit(); return false;\">";
    					$nav_bar .= ($i+1);
    					$nav_bar .= "</a>";
						}
				if($i<$n_max_page) $nav_bar .= " "; 
				}
        	
			if(($page+1)<$n_max_page) {
    			$nav_bar .= "<a href='#' onClick=\"if ((isNaN(document.search_form.page.value))||(document.search_form.page.value=='')) document.search_form.page.value=1; else document.search_form.page.value=parseInt(document.search_form.page.value)+parseInt(1); ";
    			if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
    			$nav_bar .= "document.search_form.submit(); return false;\">";
    			$nav_bar .= "<img src='./images/right.gif' border='0' title='".$msg[49]."' alt='[".$msg[49]."]' hspace='3' align='middle'>";
    			$nav_bar .= "</a>";
        		} else 	$nav_bar .= "";
			$nav_bar = "<div align='center'>$nav_bar</div>";
	   	 	echo $nav_bar ;
	   	 	
    	}  	
    }
    
    function show_results_unimarc($url,$url_to_search_form,$hidden_form=true,$search_target="") {
    	global $dbh;
    	global $begin_result_liste;
    	global $nb_per_page_search;
    	global $page;
    	global $charset;
    	global $search;
    	global $msg;
    	global $pmb_nb_max_tri;
    	global $affich_tris_result_liste;
    	global $pmb_allow_external_search;
    	
    	$start_page=$nb_per_page_search*$page;
    	
    	//Y-a-t-il des champs ?
    	if (count($search)==0) {
    		error_message_history($msg["search_empty_field"], $msg["search_no_fields"], 1);
    		exit();
    	}
    	//Verification des champs vides
    	for ($i=0; $i<count($search); $i++) {
    		$op="op_".$i."_".$search[$i];
    		global $$op;
     		$field_="field_".$i."_".$search[$i];
    		global $$field_;
    		$field=$$field_;
    		$s=explode("_",$search[$i]);
    		$bool=false;
    		if ($s[0]=="f") {
    			$champ=$this->fixedfields[$s[1]]["TITLE"];
    			if ((string)$field[0]=="") {
    				$bool=true;
    			}
    		} elseif(array_key_exists($s[0],$this->pp)) {
    			$champ=$this->pp[$s[0]]->t_fields[$s[1]]["TITRE"];
    			if ((string)$field[0]=="") {
    				$bool=true;
    			}
    		} elseif($s[0]=="s") {
    			$champ=$this->specialfields[$s[1]]["TITLE"];
    			$type=$this->specialfields[$s[1]]["TYPE"];
		 		for ($is=0; $is<$this->tableau_speciaux["TYPE"]; $is++) {
					if ($this->tableau_speciaux["TYPE"][$is]["NAME"]==$type) {
						$sf=$this->specialfields[$s[1]];
						global $include_path;
						require_once($include_path."/search_queries/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
						$specialclass= new $this->tableau_speciaux["TYPE"][$is]["CLASS"]($s[1],$sf,$i,$this);
						$bool=$specialclass->is_empty($field);
						break;
					}
				}
    		}
    		if (($bool)&&(!$this->op_empty[$$op])) {
    			error_message_history($msg["search_empty_field"], sprintf($msg["search_empty_error_message"],$champ), 1);
    			exit();
    		}
    	}
    	global $inter_1_f_1;
    	$table=$this->make_search();
    	$requete="select count(1) from $table";
    	if($res=mysql_query($requete)){
			$nb_results=mysql_result($res,0,0); 
		}else{
    		error_message_history("",$msg["search_impossible"], 1);
    		exit();
		}
    	
    	/*
    	//gestion du tri
    	if ($nb_results<=$pmb_nb_max_tri) {
			if ($_SESSION["tri"]) {
				$sort=new sort('notices','base');
				$sort->table_tri_tempo=$table;
				$sort->table_primary_tri_tempo="notice_id";
				$sort->limit="limit ".$start_page.",".$nb_per_page_search;
				$requete=$sort->appliquer_tri();
				if (substr($requete,0,1)=="(") $creer_table_tempo="CREATE TEMPORARY TABLE tri_tempo ENGINE=MyISAM ".$requete."";
					else $creer_table_tempo="CREATE TEMPORARY TABLE tri_tempo ENGINE=MyISAM (".$requete.")";
				@mysql_query ($creer_table_tempo);
				$modif_primaire="ALTER TABLE tri_tempo PRIMARY KEY notice_id";
				@mysql_query ($modif_primaire);
				$table="tri_tempo";
			}
    	} 
		// fin gestion tri
		*/
		
    	//Y-a-t-il une erreur lors de la recherche ?
    	if ($this->error_message) {
    		error_message_history("", $this->error_message, 1);
    		exit();
    	}
    	
    	if ($hidden_form)
    		print $this->make_hidden_search_form($url);
    	
    	//$requete="select $table.* from $table left join entrepots on recid=notice_id and (ufield='200' and usubfield='a') or (recid is null) order by i_value"; 
    	//$requete .= " limit ".$start_page.",".$nb_per_page_search;
    	//$resultat=mysql_query($requete,$dbh);
		
		$requete = "select * from $table";
		$requete .= " limit ".$start_page.",".$nb_per_page_search;
		
		$resultat=mysql_query($requete,$dbh);
		
    	$human_requete = $this->make_human_query();
    	print "<strong>".$msg["search_search_extended"]."</strong> : ".$human_requete ;
		
		if ($nb_results) {
			print " => ".$nb_results." ".$msg["1916"]."<br />\n";
			print $begin_result_liste;
			if ($this->rec_history) {
				//Affichage des liens paniers et impression
				$current=$_SESSION["CURRENT"];
				if ($current!==false) {
					$tri_id_info = $_SESSION["tri"] ? "&sort_id=".$_SESSION["tri"] : "";
					print "&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$current&action=print_prepare$tri_id_info','print',600,700,-2,-2,'scrollbars=yes,menubar=0,resizable=yes'); return false;\"><img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&action_print=print_prepare$tri_id_info','print',500,600,-2,-2,'scrollbars=yes,menubar=0'); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
				}
			}
		} else print "<br />".$msg["1915"]." ";
		print "<input type='button' class='bouton' onClick=\"document.search_form.action='$url_to_search_form'; document.search_form.target='$search_target'; document.search_form.submit(); return false;\" value=\"".$msg["search_back"]."\"/>";
		global $dsi_active;
		if (($dsi_active)&&false) {
			global $id_equation, $priv_pro, $id_empr;
			if ($id_equation) $mess_bouton = $msg['dsi_sauvegarder_equation'] ;
				else $mess_bouton = $msg["dsi_transformer_equation"] ;
			print "&nbsp;<input  type='button' class='bouton' onClick=\"document.forms['transform_dsi'].submit(); \" value=\"".$mess_bouton."\"/>
						<form name='transform_dsi' style='display:none;' method='post' action='./dsi.php'>";
			if ($priv_pro=="PRI") print "
						<input type=hidden name='categ' value='bannettes' />
						<input type=hidden name='sub' value='abo' />
						<input type=hidden name='suite' value='transform_equ' />
						<input type=hidden name='id_equation' value='$id_equation' />
						<input type=hidden name='id_empr' value='$id_empr' />
						<input type=hidden name='requete' value='".htmlentities($this->serialize_search(),ENT_QUOTES,$charset)."' />
						</form>";
				else print "
						<input type=hidden name='categ' value='equations' />
						<input type=hidden name='sub' value='gestion' />
						<input type=hidden name='suite' value='transform' />
						<input type=hidden name='id_equation' value='$id_equation' />
						<input type=hidden name='requete' value='".htmlentities($this->serialize_search(),ENT_QUOTES,$charset)."' />
						</form>";
			}
		flush();
		$entrepots_localisations = array();
		$entrepots_localisations_sql = "SELECT * FROM entrepots_localisations ORDER BY loc_visible DESC";
		$res = mysql_query($entrepots_localisations_sql);
		while ($row = mysql_fetch_array($res)) {
			$entrepots_localisations[$row["loc_code"]] = array("libelle" => $row["loc_libelle"], "visible" => $row["loc_visible"]); 
		}
	
    	while ($r=mysql_fetch_object($resultat)) {
    		/*if($r->niveau_biblio != 's' && $r->niveau_biblio != 'a') {
				// notice de monographie
				$nt = new mono_display($r->notice_id, 6, $this->link, 1, $this->link_expl, '', $this->link_explnum,1, 0, 1, 1, "", 1);
			} else {
				// on a affaire a un periodique
				$nt = new serial_display($r->notice_id, 6, $this->link_serial, $this->link_analysis, $this->link_bulletin, "", $this->link_explnum_serial, 0, 0, 1, 1 );
			}*/
			$nt = new mono_display_unimarc($r->notice_id,6, 1, 0, 1, false, $entrepots_localisations);
    		echo "<div class='row'>".$nt->result."</div>";
    	}
    	
    	//Gestion de la pagination
    	if ($nb_results) {
	  	  	$n_max_page=ceil($nb_results/$nb_per_page_search);
	   	 	
	   	 	if (!$page) $page_en_cours=0 ;
				else $page_en_cours=$page ;
		
	   	 	// affichage du lien precedent si necessaire
   		 	if ($page>0) {
   		 		$nav_bar .= "<a href='#' onClick='document.search_form.page.value-=1; ";
   		 		if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
   		 		$nav_bar .= "document.search_form.submit(); return false;'>";
   	 			$nav_bar .= "<img src='./images/left.gif' border='0'  title='".$msg[48]."' alt='[".$msg[48]."]' hspace='3' align='middle'/>";
    			$nav_bar .= "</a>";
    		}
        	
			$deb = $page_en_cours - 10 ;
			if ($deb<0) $deb=0;
			for($i = $deb; ($i < $n_max_page) && ($i<$page_en_cours+10); $i++) {
				if($i==$page_en_cours) $nav_bar .= "<strong>".($i+1)."</strong>";
					else {
						$nav_bar .= "<a href='#' onClick=\"if ((isNaN(document.search_form.page.value))||(document.search_form.page.value=='')) document.search_form.page.value=1; else document.search_form.page.value=".($i)."; ";
    					if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
		    			$nav_bar .= "document.search_form.submit(); return false;\">";
    					$nav_bar .= ($i+1);
    					$nav_bar .= "</a>";
						}
				if($i<$n_max_page) $nav_bar .= " "; 
				}
        	
			if(($page+1)<$n_max_page) {
    			$nav_bar .= "<a href='#' onClick=\"if ((isNaN(document.search_form.page.value))||(document.search_form.page.value=='')) document.search_form.page.value=1; else document.search_form.page.value=parseInt(document.search_form.page.value)+parseInt(1); ";
    			if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
    			$nav_bar .= "document.search_form.submit(); return false;\">";
    			$nav_bar .= "<img src='./images/right.gif' border='0' title='".$msg[49]."' alt='[".$msg[49]."]' hspace='3' align='middle'>";
    			$nav_bar .= "</a>";
        		} else 	$nav_bar .= "";
			$nav_bar = "<div align='center'>$nav_bar</div>";
	   	 	echo $nav_bar ;
	   	 	
    	}  	
    }
    
    function filter_searchtable_from_accessrights($table, $PMBUserId) {
    	global $dbh;
    	//droits d'acces lecture notice
		$ac= new acces();
		$dom_1= $ac->setDomain(1);
		$usr_prf = $dom_1->getUserProfile(PMBUserId);
		
		$requete = "delete from $table using $table, acces_res_1 ";
		$requete.= "where ";
		$requete.= "$table.notice_id = res_num and usr_prf_num=".$usr_prf." ";
		$requete.= "and (((res_rights ^ res_mask) & 4)=0) ";
		mysql_query($requete, $dbh);
    }
    
    //Templates des listes d'operateurs
    function show_form($url,$result_url,$result_target='',$memo_url='') {
    	global $charset;
    	global $search;
    	global $add_field;
    	global $delete_field;
    	global $launch_search;
    	global $page;
    	global $search_form;
    	global $msg;
    	global $include_path;   	
    	global $option_show_expl,$option_show_notice_fille;
    	global $pmb_extended_search_auto;
		
    	if($option_show_expl)$option_show_expl_check="checked='checked'";
    	if($option_show_notice_fille)$option_show_notice_fille_check="checked='checked'";
    	$option="
    		<div class='row'>
    			<h3>".$msg['search_option_show_title']."</h3>
    			<input $option_show_expl_check value='1' name='option_show_expl' id='option_show_expl'  type='checkbox'>".$msg["search_option_show_expl"]."
    			<input $option_show_notice_fille_check value='1' name='option_show_notice_fille' id='option_show_notice_fille'  type='checkbox'>".$msg["search_option_show_notice_fille"]."
    		</div><div class='row'>&nbsp;</div>";    	
    	$search_form=str_replace("<!--!!limitation_affichage!!-->",$option,$search_form);
    	
    	if (($add_field)&&(($delete_field==="")&&(!$launch_search)))
    		$search[]=$add_field;
    	
    	$search_form=str_replace("!!url!!",$url,$search_form);
    	if(!$memo_url) $memo_url="catalog.php?categ=search_perso&sub=form";
    	$search_form=str_replace("!!memo_url!!",$memo_url,$search_form);
    	
    	//Génération de la liste des champs possibles
    	if($this->limited_search){ 
    		$search_form = str_replace("!!limit_search!!","<input type='hidden' id='limited_search' name='limited_search' />",$search_form);  		
	    	$limit_search = " this.form.limited_search.value='1'; ";
    	} else {
    		$search_form = str_replace("!!limit_search!!","",$search_form);
    		$limit_search = "";
    	}
    	if ($pmb_extended_search_auto) $r="<select name='add_field' id='add_field' onChange=\"if (this.form.add_field.value!='') { this.form.action='$url'; this.form.target=''; $limit_search this.form.submit();} else { alert('".htmlentities($msg["multi_select_champ"],ENT_QUOTES,$charset)."'); }\" >\n";
    	else $r="<select name='add_field' id='add_field'>\n";
    	$r.="<option value='' style='color:#000000'>".htmlentities($msg["multi_select_champ"],ENT_QUOTES,$charset)."</font></option>\n";
    	
    	//Champs fixes
    	if($this->fixedfields){
	    	reset($this->fixedfields);
	    	$open_optgroup=0;
			$open_optgroup_deja_affiche=0;
			$open_optgroup_en_attente_affiche=0;
	    	while (list($id,$ff)=each($this->fixedfields)) {
	    		if ($ff["SEPARATOR"]) {
	    			if ($open_optgroup) $r.="</optgroup>\n";
	    			// $r.="<option disabled style='border-left:0px;border-right:0px;border-top:0px;border-bottom:1px;border-style:solid;'></option>\n";
	    			$r_opt_groupe="<optgroup label='".htmlentities($ff["SEPARATOR"],ENT_QUOTES,$charset)."' class='erreur'>\n";
	    			$open_optgroup=0;
	    			$open_optgroup_deja_affiche=0;
	    			$open_optgroup_en_attente_affiche=1;
	    		}
	    		if ($ff["VISIBLE"]) {
	    			if ($open_optgroup_en_attente_affiche && !$open_optgroup_deja_affiche) {
	    				$r.=$r_opt_groupe ;
	    				$open_optgroup_deja_affiche = 1 ;
	    				$open_optgroup_en_attente_affiche = 0 ;
	    				$open_optgroup = 1 ; 
	    			}
	    			$r.="<option value='f_".$id."' style='color:#000000'>".htmlentities($ff["TITLE"],ENT_QUOTES,$charset)."</font></option>\n";
	    		}
	    	}
    	}

    	//Champs fixes
    	/*reset($this->fixedfields);
    	$open_optgroup=0;
    	while (list($id,$ff)=each($this->fixedfields)) {
    		if ($ff["SEPARATOR"]) {
    			if ($open_optgroup) $r.="</optgroup>\n";
    			// $r.="<option disabled style='border-left:0px;border-right:0px;border-top:0px;border-bottom:1px;border-style:solid;'></option>\n";
    			$r.="<optgroup label='".htmlentities($ff["SEPARATOR"],ENT_QUOTES,$charset)."' class='erreur'>\n";
    			$open_optgroup=1;
    		}
    		$r.="<option value='f_".$id."' style='color:#000000'>".htmlentities($ff["TITLE"],ENT_QUOTES,$charset)."</font></option>\n";
    	}*/
    	
    	//Champs dynamiques
    	if ($open_optgroup) $r.="</optgroup>\n";
    	// $r.="<option disabled style='border-left:0px;border-right:0px;border-top:0px;border-bottom:1px;border-style:solid;'></option>\n";
    	if(!$this->dynamics_not_visible){
    		foreach ( $this->dynamicfields as $key => $value ) {
    			if(!$this->pp[key]->no_special_fields){
       				$r.="<optgroup label='".$msg["search_custom_".$value["TYPE"]]."' class='erreur'>\n";
	   		 		reset($this->pp[$key]->t_fields);
	   		 		while (list($id,$df)=each($this->pp[$key]->t_fields)) {
	    				$r.="<option value='".$key."_".$id."' style='color:#000000'>".htmlentities($df["TITRE"],ENT_QUOTES,$charset)."</option>\n";
	    			}
	    			$r.="</optgroup>\n";
	    		}
			}
    	}
    	//Champs speciaux
    	if (!$this->specials_not_visible && $this->specialfields) {
   		 	while (list($id,$sf)=each($this->specialfields)) {
   		 		if ($sf["SEPARATOR"]) {
   		 			if ($open_optgroup) $r.="</optgroup>\n";
  		  			// $r.="<option disabled style='border-left:0px;border-right:0px;border-top:0px;border-bottom:1px;border-style:solid;'></option>\n";
    				$r.="<optgroup label='".htmlentities($sf["SEPARATOR"],ENT_QUOTES,$charset)."' class='erreur'>\n";
    				$open_optgroup=1;
    			}
    			$r.="<option value='s_".$id."' style='color:#000000'>".htmlentities($sf["TITLE"],ENT_QUOTES,$charset)."</font></option>\n";
    		}
    	}
    	$r.="</select>";
    	   	
    	$search_form=str_replace("!!field_list!!",$r,$search_form);
    	
    	//Affichage des champs deja saisis
    	$r="";
    	$n=0;
    	$r.="<table class='table-no-border'>\n";
    	for ($i=0; $i<count($search); $i++) {
    		if ((string)$i!=$delete_field) {
    			$r.="<tr>";
    			$r.="<td>";
    			$r.="<input type='hidden' name='search[]' value='".$search[$i]."'>";
    			$f=explode("_",$search[$i]);
    			$r.="</td>";
    			if ($n>0) {
    				$r.="<td>";
    				$inter="inter_".$i."_".$search[$i];
    				global $$inter;
    				$r.="<select name='inter_".$n."_".$search[$i]."'>";
    				$r.="<option value='and' ";
    				if ($$inter=="and")
    					$r.=" selected";
    				$r.=">".$msg["search_and"]."</option>";
    				$r.="<option value='or' ";
    				if ($$inter=="or")
    					$r.=" selected";
    				$r.=">".$msg["search_or"]."</option>";
    				$r.="<option value='ex' ";
    				if ($$inter=="ex")
    					$r.=" selected";
    				$r.=">".$msg["search_exept"]."</option>";
    				$r.="</select>";
    				$r.="</td><td>";
    			} else $r.="<td>&nbsp;</td><td>";
    			if ($f[0]=="f") {
    				$r.=htmlentities($this->fixedfields[$f[1]]["TITLE"],ENT_QUOTES,$charset);
    			} elseif ($f[0]=="s") {
    				$r.=htmlentities($this->specialfields[$f[1]]["TITLE"],ENT_QUOTES,$charset);
    			} elseif (array_key_exists($f[0],$this->pp)) {
    				$r.=htmlentities($this->pp[$f[0]]->t_fields[$f[1]]["TITRE"],ENT_QUOTES,$charset);
    			}
    			$r.="</td>";
    			//Recherche des operateurs possibles
    			$r.="<td>";
    			$op="op_".$i."_".$search[$i];
    			global $$op;
    			if ($f[0]=="f") {	
     				$r.="<select name='op_".$n."_".$search[$i]."'>\n";
    				for ($j=0; $j<count($this->fixedfields[$f[1]]["QUERIES"]); $j++) {
    					$q=$this->fixedfields[$f[1]]["QUERIES"][$j];
    					$r.="<option value='".$q["OPERATOR"]."' ";
    					if ($$op==$q["OPERATOR"]) $r.="selected";
    					$r.=">".htmlentities($this->operators[$q["OPERATOR"]],ENT_QUOTES,$charset)."</option>\n";
    				}
    				$r.="</select>";
    			} elseif (array_key_exists($f[0],$this->pp)) {
    				$datatype=$this->pp[$f[0]]->t_fields[$f[1]]["DATATYPE"];
    				$type=$this->pp[$f[0]]->t_fields[$f[1]]["TYPE"];
    				$df=$this->get_id_from_datatype($datatype, $f[0]);
    				$r.="<select name='op_".$n."_".$search[$i]."'>\n";
    				for ($j=0; $j<count($this->dynamicfields[$f[0]]["FIELD"][$df]["QUERIES"]); $j++) {
    					$q=$this->dynamicfields[$f[0]]["FIELD"][$df]["QUERIES"][$j];
    					$as=array_search($type,$q["NOT_ALLOWED_FOR"]);
    					if (!(($as!==null)&&($as!==false))) {
    						$r.="<option value='".$q["OPERATOR"]."' ";
    						if ($$op==$q["OPERATOR"]) $r.="selected";
    						$r.=">".htmlentities($this->operators[$q["OPERATOR"]],ENT_QUOTES,$charset)."</option>\n";
    					}
    				}
    				$r.="</select>";
    				$r.="&nbsp;";
    			} elseif ($f[0]=="s") {
					//appel de la fonction get_input_box de la classe du champ special
					$type=$this->specialfields[$f[1]]["TYPE"];
   			 		for ($is=0; $is<$this->tableau_speciaux["TYPE"]; $is++) {
						if ($this->tableau_speciaux["TYPE"][$is]["NAME"]==$type) {
							$sf=$this->specialfields[$f[1]];
							require_once($include_path."/search_queries/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
							$specialclass= new $this->tableau_speciaux["TYPE"][$is]["CLASS"]($f[1],$sf,$n,$this);
							$q=$specialclass->get_op();
							if (count($q)) {
								$r.="<span class='search_sous_critere'><select name='op_".$n."_".$search[$i]."'>\n";
								foreach ($q as $key => $value) {
									$r.="<option value='".$key."' ";
	    							if ($$op==$key) $r.="selected";
	    							$r.=">".htmlentities($value,ENT_QUOTES,$charset)."</option>\n";
								}
								$r .= "</select></span>";
							} else print "&nbsp;";
							break;
						}
    				}
    			}
    			$r.="</td>";
    			
    			//Affichage du champ de saisie
    			$r.="<td>";
    			$r.=$this->get_field($i,$n,$search[$i],$this->pp);
    			$r.="</td>";
    			$delnotallowed=false;
    			if ($f[0]=="f") {
    				$delnotallowed=$this->fixedfields[$f[1]]["DELNOTALLOWED"];
       			} elseif ($f[0]=="s") {
    				$delnotallowed=$this->specialfields[$f[1]]["DELNOTALLOWED"];
    			}
    			if($this->limited_search) 
    				$script_limit = " this.form.limited_search.value='0'; ";
    			else $script_limit = "";
    			$r.="<td>".(!$delnotallowed?"<input type='button' class='bouton' value='".$msg["raz"]."' onClick=\"this.form.delete_field.value='".$n."'; this.form.action='$url'; this.form.target=''; $script_limit this.form.submit();\">":"&nbsp;")."</td>";
    			$r.="</tr>\n";
    			$n++;
    		}
    	}
    	$r.="</table>\n";
    	
    	//Recherche explicite
    	$r.="<input type='hidden' name='explicit_search' value='1'/>\n";
    	
    	$search_form=str_replace("!!already_selected_fields!!",$r,$search_form);
    	$search_form=str_replace("!!page!!",$page,$search_form);
    	$search_form=str_replace("!!result_url!!",$result_url,$search_form);

    	global $dsi_active;
    	if ($dsi_active) {
    		global $id_equation;
    		$search_form=str_replace("!!id_equation!!",$id_equation,$search_form);
    		} else {
    			$search_form=str_replace("!!id_equation!!","",$search_form);
    			}

    	global $id_connector_set;
    	if (isset($id_connector_set))
    		$search_form=str_replace("!!id_connector_set!!",$id_connector_set,$search_form);
    	else 
    		$search_form=str_replace("!!id_connector_set!!","",$search_form);
    			
    	if ($result_target) $r="this.form.target='$result_target';"; else $r="";
    	$search_form=str_replace("!!target_js!!",$r,$search_form);

		$search_form .= "\n\n<script type=\"text/javascript\" >
			function change_source_checkbox(changing_control, source_id) {
				var i=0; var count=0;
				onoff = changing_control.checked;
				for(i=0; i<document.search_form.elements.length; i++)
				{
					if(document.search_form.elements[i].name == 'source[]')	{
						if (document.search_form.elements[i].value == source_id)
							document.search_form.elements[i].checked = onoff;
					}
				}	
			
			}
</script>";

    	return $search_form;
    }
    
    //Parse du fichier de configuration
    function parse_search_file($fichier_xml,$full_path='') {
    	global $include_path;
    	global $msg;
    	
    	if(!$full_path){
	    	if ($fichier_xml!="") {
	    		if (file_exists($include_path."/search_queries/".$fichier_xml."_subst.xml")) {
	    			$fp=fopen($include_path."/search_queries/".$fichier_xml."_subst.xml","r") or die("Can't find XML file");
	    			$size=filesize($include_path."/search_queries/".$fichier_xml."_subst.xml");
	    		} else {
	    			$fp=fopen($include_path."/search_queries/".$fichier_xml.".xml","r") or die("Can't find XML file");
	    			$size=filesize($include_path."/search_queries/".$fichier_xml.".xml");
	    		}
	    	} else {
	    		if (file_exists($include_path."/search_queries/search_fields_subst.xml")) {
	    			$fp=fopen($include_path."/search_queries/search_fields_subst.xml","r") or die("Can't find XML file");
	    			$size=filesize($include_path."/search_queries/search_fields_subst.xml");
	    		} else {
	    			$fp=fopen($include_path."/search_queries/search_fields.xml","r") or die("Can't find XML file");
	    			$size=filesize($include_path."/search_queries/search_fields.xml");
	    		}
	    	}
    	} else{
    		if (file_exists($full_path.$fichier_xml."_subst.xml")) {
    			$fp=fopen($full_path.$fichier_xml."_subst.xml","r") or die("Can't find XML file");
    			$size=filesize($full_path.$fichier_xml."_subst.xml");
    		} else {
    			$fp=fopen($full_path.$fichier_xml.".xml","r") or die("Can't find XML file");
    			$size=filesize($full_path.$fichier_xml.".xml");
    		} 
    	}		
    	    	
		$xml=fread($fp,$size);
		fclose($fp);
		$param=_parser_text_no_function_($xml, "PMBFIELDS");
		
		//Lecture des operateurs
		for ($i=0; $i<count($param["OPERATORS"][0]["OPERATOR"]); $i++) {
			$operator_=$param["OPERATORS"][0]["OPERATOR"][$i];
			if (substr($operator_["value"],0,4)=="msg:") {
				$this->operators[$operator_["NAME"]]=$msg[substr($operator_["value"],4,strlen($operator_["value"])-4)];
			} else {
				$this->operators[$operator_["NAME"]]=$operator_["value"];	
			}
			if ($operator_["EMPTYALLOWED"]=="yes") $this->op_empty[$operator_["NAME"]]=true; else $this->op_empty[$operator_["NAME"]]=false;
		}
		
		//Lecture des champs fixes
		for ($i=0; $i<count($param["FIXEDFIELDS"][0]["FIELD"]); $i++) {
			$t=array();
			$ff=$param["FIXEDFIELDS"][0]["FIELD"][$i];
			if (substr($ff["TITLE"],0,4)=="msg:") {
				$t["TITLE"]=$msg[substr($ff["TITLE"],4,strlen($ff["TITLE"])-4)];	
			} else {
				$t["TITLE"]=$ff["TITLE"];	
			}
			$t["UNIMARCFIELD"]=$ff["UNIMARCFIELD"];
			$t["INPUT_TYPE"]=$ff["INPUT"][0]["TYPE"];
			$t["INPUT_OPTIONS"]=$ff["INPUT"][0];
			if (substr($ff["SEPARATOR"],0,4)=="msg:") {
				$t["SEPARATOR"]=$msg[substr($ff["SEPARATOR"],4,strlen($ff["SEPARATOR"])-4)];
			} else {
				$t["SEPARATOR"]=$ff["SEPARATOR"];	
			}
			//Visibilite
			$t["VISIBLE"]=($ff["VISIBLE"]=="no"?false:true);
			
			//Variables
			for ($j=0; $j<count($ff["VARIABLE"]); $j++) {
				$v=array();
				$vv=$ff["VARIABLE"][$j];
				$v["NAME"]=$vv["NAME"];
				$v["TYPE"]=$vv["TYPE"];
				if (substr($vv["COMMENT"],0,4)=="msg:") {
					$v["COMMENT"]=$msg[substr($vv["COMMENT"],4,strlen($vv["COMMENT"])-4)];;
				} else {
					$v["COMMENT"]=$vv["COMMENT"];	
				}
				//Recherche des options
				reset($vv);
				while (list($key,$val)=each($vv)) {
					if (is_array($val)) {
						$v["OPTIONS"][$key]=$val;
					}
				}
				$t["VAR"][]=$v;
			}
			
			$q=array();
			for ($j=0; $j<count($ff["QUERY"]); $j++) {
				$q["OPERATOR"]=$ff["QUERY"][$j]["FOR"];
				if (($ff["QUERY"][$j]["MULTIPLE"]=="yes")||($ff["QUERY"][$j]["CONDITIONAL"]=="yes")) {
					if($ff["QUERY"][$j]["MULTIPLE"]=="yes") $element = "PART";
					else $element = "VAR";
					
					for ($k=0; $k<count($ff["QUERY"][$j][$element]); $k++) {
						$pquery=$ff["QUERY"][$j][$element][$k];						
						if($element == "VAR"){
							$q[$k]["CONDITIONAL"]["name"] = $pquery["NAME"];
							$q[$k]["CONDITIONAL"]["value"] = $pquery["VALUE"][0]["value"];
						}
						if ($pquery["MULTIPLEWORDS"]=="yes")
							$q[$k]["MULTIPLE_WORDS"]=true;
						else
							$q[$k]["MULTIPLE_WORDS"]=false;
						if ($pquery["REGDIACRIT"]=="yes")
							$q[$k]["REGDIACRIT"]=true;
						else
							$q[$k]["REGDIACRIT"]=false;						
						if ($pquery["KEEP_EMPTYWORD"]=="yes")
							$q[$k]["KEEP_EMPTYWORD"]=true;
						else
							$q[$k]["KEEP_EMPTYWORD"]=false;
							
						if ($pquery["REPEAT"]) {
							$q[$k]["REPEAT"]["NAME"]=$pquery["REPEAT"][0]["NAME"];
							$q[$k]["REPEAT"]["ON"]=$pquery["REPEAT"][0]["ON"];
							$q[$k]["REPEAT"]["SEPARATOR"]=$pquery["REPEAT"][0]["SEPARATOR"];
							$q[$k]["REPEAT"]["OPERATOR"]=$pquery["REPEAT"][0]["OPERATOR"];
							$q[$k]["REPEAT"]["ORDERTERM"]=$pquery["REPEAT"][0]["ORDERTERM"];
						}
						if ($pquery["BOOLEANSEARCH"]=="yes") {
							$q[$k]["BOOLEAN"]=true;
							if ($pquery["BOOLEAN"]) {
								for ($z=0; $z<count($pquery["BOOLEAN"]); $z++) {
									$q[$k]["TABLE"][$z]=$pquery["BOOLEAN"][$z]["TABLE"][0]["value"];
									$q[$k]["INDEX_L"][$z]=$pquery["BOOLEAN"][$z]["INDEX_L"][0]["value"];
									$q[$k]["INDEX_I"][$z]=$pquery["BOOLEAN"][$z]["INDEX_I"][0]["value"];
									$q[$k]["ID_FIELD"][$z]=$pquery["BOOLEAN"][$z]["ID_FIELD"][0]["value"];
									if ($pquery["BOOLEAN"][$z]["KEEP_EMPTY_WORDS"][0]["value"]=="yes") {
										$q[$k]["KEEP_EMPTY_WORDS"][$z]=1;
										$q[$k]["KEEP_EMPTY_WORDS_FOR_CHECK"]=1;
									}
								}
							} else {
								$q[$k]["TABLE"]=$pquery["TABLE"][0]["value"];
								$q[$k]["INDEX_L"]=$pquery["INDEX_L"][0]["value"];
								$q[$k]["INDEX_I"]=$pquery["INDEX_I"][0]["value"];
								$q[$k]["ID_FIELD"]=$pquery["ID_FIELD"][0]["value"];
								if ($pquery["KEEP_EMPTY_WORDS"][0]["value"]=="yes") {
									$q[$k]["KEEP_EMPTY_WORDS"]=1;
									$q[$k]["KEEP_EMPTY_WORDS_FOR_CHECK"]=1;
								}
							}
						} else $q[$k]["BOOLEAN"]=false;
						if ($pquery["ISBNSEARCH"]=="yes") {
							$q[$k]["ISBN"]=true;
						} else $q[$k]["ISBN"]=false;
						$q[$k]["MAIN"]=$pquery["MAIN"][0]["value"];
						$q[$k]["MULTIPLE_TERM"]=$pquery["MULTIPLETERM"][0]["value"];
						$q[$k]["MULTIPLE_OPERATOR"]=$pquery["MULTIPLEOPERATOR"][0]["value"];
					}
					$t["QUERIES"][]=$q;
					$t["QUERIES_INDEX"][$q["OPERATOR"]]=count($t["QUERIES"])-1;
				} else {
					if ($ff["QUERY"][$j]["MULTIPLEWORDS"]=="yes")
						$q[0]["MULTIPLE_WORDS"]=true;
					else
						$q[0]["MULTIPLE_WORDS"]=false;
					if ($ff["QUERY"][$j]["REGDIACRIT"]=="yes")
						$q[0]["REGDIACRIT"]=true;
					else
						$q[0]["REGDIACRIT"]=false;					
					if ($ff["QUERY"][$j]["KEEP_EMPTYWORD"]=="yes")
						$q[0]["KEEP_EMPTYWORD"]=true;
					else
						$q[0]["KEEP_EMPTYWORD"]=false;						
					if ($ff["QUERY"][$j]["REPEAT"]) {
						$q[0]["REPEAT"]["NAME"]=$ff["QUERY"][$j]["REPEAT"][0]["NAME"];
						$q[0]["REPEAT"]["ON"]=$ff["QUERY"][$j]["REPEAT"][0]["ON"];
						$q[0]["REPEAT"]["SEPARATOR"]=$ff["QUERY"][$j]["REPEAT"][0]["SEPARATOR"];
						$q[0]["REPEAT"]["OPERATOR"]=$ff["QUERY"][$j]["REPEAT"][0]["OPERATOR"];
						$q[0]["REPEAT"]["ORDERTERM"]=$ff["QUERY"][$j]["REPEAT"][0]["ORDERTERM"];
					}
					if ($ff["QUERY"][$j]["BOOLEANSEARCH"]=="yes") {
						$q[0]["BOOLEAN"]=true;
						if ($ff["QUERY"][$j]["BOOLEAN"]) {
							for ($z=0; $z<count($ff["QUERY"][$j]["BOOLEAN"]); $z++) {
								$q[0]["TABLE"][$z]=$ff["QUERY"][$j]["BOOLEAN"][$z]["TABLE"][0]["value"];
								$q[0]["INDEX_L"][$z]=$ff["QUERY"][$j]["BOOLEAN"][$z]["INDEX_L"][0]["value"];
								$q[0]["INDEX_I"][$z]=$ff["QUERY"][$j]["BOOLEAN"][$z]["INDEX_I"][0]["value"];
								$q[0]["ID_FIELD"][$z]=$ff["QUERY"][$j]["BOOLEAN"][$z]["ID_FIELD"][0]["value"];
								if ($ff["QUERY"][$j]["BOOLEAN"][$z]["KEEP_EMPTY_WORDS"][0]["value"]=="yes") {
									$q[0]["KEEP_EMPTY_WORDS"][$z]=1;
									$q[0]["KEEP_EMPTY_WORDS_FOR_CHECK"]=1;
								}
							}
						} else {
							$q[0]["TABLE"]=$ff["QUERY"][$j]["TABLE"][0]["value"];
							$q[0]["INDEX_L"]=$ff["QUERY"][$j]["INDEX_L"][0]["value"];
							$q[0]["INDEX_I"]=$ff["QUERY"][$j]["INDEX_I"][0]["value"];
							$q[0]["ID_FIELD"]=$ff["QUERY"][$j]["ID_FIELD"][0]["value"];
							if ($ff["QUERY"][$j]["KEEP_EMPTY_WORDS"][0]["value"]=="yes") {
								$q[0]["KEEP_EMPTY_WORDS"]=1;
								$q[0]["KEEP_EMPTY_WORDS_FOR_CHECK"]=1;
							}
						}
					} else $q[0]["BOOLEAN"]=false;
					if ($ff["QUERY"][$j]["ISBNSEARCH"]=="yes") {
						$q[0]["ISBN"]=true;
					} else $q[0]["ISBN"]=false;
					$q[0]["MAIN"]=$ff["QUERY"][$j]["MAIN"][0]["value"];
					$q[0]["MULTIPLE_TERM"]=$ff["QUERY"][$j]["MULTIPLETERM"][0]["value"];
					$q[0]["MULTIPLE_OPERATOR"]=$ff["QUERY"][$j]["MULTIPLEOPERATOR"][0]["value"];
					$t["QUERIES"][]=$q;
					$t["QUERIES_INDEX"][$q["OPERATOR"]]=count($t["QUERIES"])-1;
				}
			}
			$this->fixedfields[$ff["ID"]]=$t;
		}
		
		//Lecture des champs dynamiques
		if ($param["DYNAMICFIELDS"][0]["VISIBLE"]=="no") $this->dynamics_not_visible=true;
		if(!$param["DYNAMICFIELDS"][0]["FIELDTYPE"]){//Pour le cas ou on est des fichiers subst basé sur l'ancienne version 
			$tmp=$param["DYNAMICFIELDS"][0]["FIELD"];
			unset($param["DYNAMICFIELDS"]);
			$param["DYNAMICFIELDS"][0]["FIELDTYPE"][0]["PREFIX"]="d";
			$param["DYNAMICFIELDS"][0]["FIELDTYPE"][0]["TYPE"]="notices";
			$param["DYNAMICFIELDS"][0]["FIELDTYPE"][0]["FIELD"]=$tmp;
			unset($tmp);
		}
		for ($h=0; $h <count($param["DYNAMICFIELDS"][0]["FIELDTYPE"]); $h++){
			$champType=array();
			$ft=$param["DYNAMICFIELDS"][0]["FIELDTYPE"][$h];
			$champType["TYPE"]=$ft["TYPE"];
			for ($i=0; $i<count($ft["FIELD"]); $i++) {
				$t=array();
				$ff=$ft["FIELD"][$i];
				$t["DATATYPE"]=$ff["DATATYPE"];
				$q=array();
				for ($j=0; $j<count($ff["QUERY"]); $j++) {
					$q["OPERATOR"]=$ff["QUERY"][$j]["FOR"];
					if ($ff["QUERY"][$j]["MULTIPLEWORDS"]=="yes")
						$q["MULTIPLE_WORDS"]=true;
					else
						$q["MULTIPLE_WORDS"]=false;
					if ($ff["QUERY"][$j]["REGDIACRIT"]=="yes")
						$q["REGDIACRIT"]=true;
					else
						$q["REGDIACRIT"]=false;				
					if ($ff["QUERY"][$j]["KEEP_EMPTYWORD"]=="yes")
						$q["KEEP_EMPTYWORD"]=true;
					else
						$q["KEEP_EMPTYWORD"]=false;
					if ($ff["QUERY"][$j]["DEFAULT_OPERATOR"])
						$q["DEFAULT_OPERATOR"] = $ff["QUERY"][$j]["DEFAULT_OPERATOR"]; 					
					$q["NOT_ALLOWED_FOR"]=array();
					$naf=$ff["QUERY"][$j]["NOTALLOWEDFOR"];
					if ($naf) {
						$naf_=explode(",",$naf);
						$q["NOT_ALLOWED_FOR"]=$naf_;
					}
					
					$q["MAIN"]=$ff["QUERY"][$j]["MAIN"][0]["value"];
					$q["MULTIPLE_TERM"]=$ff["QUERY"][$j]["MULTIPLETERM"][0]["value"];
					$q["MULTIPLE_OPERATOR"]=$ff["QUERY"][$j]["MULTIPLEOPERATOR"][0]["value"];
					$t["QUERIES"][]=$q;
					$t["QUERIES_INDEX"][$q["OPERATOR"]]=count($t["QUERIES"])-1;
				}
				$champType["FIELD"][$ff["ID"]]=$t;
			}
			$this->dynamicfields[$ft["PREFIX"]]=$champType;
		}
		//Lecture des champs speciaux
		if ($param["SPECIALFIELDS"][0]["VISIBLE"]=="no") $this->specials_not_visible=true;
		for ($i=0; $i<count($param["SPECIALFIELDS"][0]["FIELD"]); $i++) {
			$t=array();
			$sf=$param["SPECIALFIELDS"][0]["FIELD"][$i];
			if (substr($sf["TITLE"],0,4)=="msg:") {
				$t["TITLE"]=$msg[substr($sf["TITLE"],4,strlen($sf["TITLE"])-4)];	
			} else {
				$t["TITLE"]=$sf["TITLE"];	
			}
			$t["UNIMARCFIELD"]=$sf["UNIMARCFIELD"];
			if (substr($sf["SEPARATOR"],0,4)=="msg:") {
				$t["SEPARATOR"]=$msg[substr($sf["SEPARATOR"],4,strlen($sf["SEPARATOR"])-4)];
			} else {
				$t["SEPARATOR"]=$sf["SEPARATOR"];	
			}
			$t["TYPE"]=$sf["TYPE"];
			$t["DELNOTALLOWED"]=($sf["DELNOTALLOWED"]=="yes"?true:false);
			$this->specialfields[$sf["ID"]]=$t;
		}
		if (count($this->specialfields)!=0) {
			if (file_exists($include_path."/search_queries/specials/catalog_subst.xml")) {
				$nom_fichier=$include_path."/search_queries/specials/catalog_subst.xml";
			} else {
				$nom_fichier=$include_path."/search_queries/specials/catalog.xml";	
			}
			$parametres=file_get_contents($nom_fichier);
			$this->tableau_speciaux=_parser_text_no_function_($parametres, "SPECIALFIELDS");
		}
		$this->keyName = $param["KEYNAME"][0]["value"];
		if(!$this->keyName) $this->keyName="notice_id";
    }
    
    function serialize_search() {
    	global $search;
    	
    	$to_serialize=array();
    	$to_serialize["SEARCH"]=$search;
    	for ($i=0; $i<count($search); $i++) {
    		$op="op_".$i."_".$search[$i];
    		$field_="field_".$i."_".$search[$i];
    		$inter="inter_".$i."_".$search[$i];
    		$fieldvar="fieldvar_".$i."_".$search[$i];
    		global $$op;
    		global $$field_;
    		global $$inter;
    		global $$fieldvar;
    		$to_serialize[$i]["SEARCH"]=$search[$i];
    		$to_serialize[$i]["OP"]=$$op;
    		$to_serialize[$i]["FIELD"]=$$field_;
    		$to_serialize[$i]["INTER"]=$$inter;
    		$to_serialize[$i]["FIELDVAR"]=$$fieldvar;
    	}
    	return serialize($to_serialize);
    }
    
    function unserialize_search($serialized) {
    	global $search;
    	$to_unserialize=unserialize($serialized);
    	$search=$to_unserialize["SEARCH"];
    	for ($i=0; $i<count($search); $i++) {
    		$op="op_".$i."_".$search[$i];
    		$field_="field_".$i."_".$search[$i];
    		$inter="inter_".$i."_".$search[$i];
    		$fieldvar="fieldvar_".$i."_".$search[$i];
    		global $$op;
    		global $$field_;
    		global $$inter;
    		global $$fieldvar;
    		$$op=$to_unserialize[$i]["OP"];
    		$$field_=$to_unserialize[$i]["FIELD"];
    		$$inter=$to_unserialize[$i]["INTER"];
    		$$fieldvar=$to_unserialize[$i]["FIELDVAR"];
    	}
    }
    
    function make_serialized_human_query($serialized) {
    	//global $search;
    	global $msg;
    	global $charset;
    	
    	$to_unserialize=unserialize($serialized);
    	$search=$to_unserialize["SEARCH"];
    	for ($i=0; $i<count($search); $i++) {
    		$op="op_".$i."_".$search[$i];
    		$field_="field_".$i."_".$search[$i];
    		$inter="inter_".$i."_".$search[$i];
    		$fieldvar="fieldvar_".$i."_".$search[$i];
    		$$op=$to_unserialize[$i]["OP"];
    		$$field_=$to_unserialize[$i]["FIELD"];
     		$$inter=$to_unserialize[$i]["INTER"];
     		$$fieldvar=$to_unserialize[$i]["FIELDVAR"];
    	}
    	
    	$r="";
    	for ($i=0; $i<count($search); $i++) {
    		$s=explode("_",$search[$i]);
    		if ($s[0]=="f") {
    			$title=$this->fixedfields[$s[1]]["TITLE"]; 
    		} elseif (array_key_exists($s[0],$this->pp)) {
    			$title=$this->pp[$s[0]]->t_fields[$s[1]]["TITRE"];
    		} elseif ($s[0]=="s") {
    			$title=$this->specialfields[$s[1]]["TITLE"];
    		}
    		$op="op_".$i."_".$search[$i];
     		$operator=$this->operators[$$op];
    		$field_="field_".$i."_".$search[$i];
    		$field=$$field_;
    		//Recuperation des variables auxiliaires
    		$fieldvar_="fieldvar_".$i."_".$search[$i];
    		$fieldvar=$$fieldvar_;
    		if (!is_array($fieldvar)) $fieldvar=array();
    		
    		$field_aff=array();
    		if (array_key_exists($s[0],$this->pp)) {
    			$datatype=$this->pp[$s[0]]->t_fields[$s[1]]["DATATYPE"];
				$df=$this->dynamicfields[$s[0]]["FIELD"][$this->get_id_from_datatype($datatype,$s[0])];
				$q_index=$df["QUERIES_INDEX"];
	 			$q=$df["QUERIES"][$q_index[$$op]];
    			if ($q["DEFAULT_OPERATOR"])
    				$operator_multi=$q["DEFAULT_OPERATOR"];
    			for ($j=0; $j<count($field); $j++) {
	    			$field_aff[$j]=$this->pp[$s[0]]->get_formatted_output(array(0=>$field[$j]),$s[1]);
    			}
    		} elseif($s[0]=="f") {
    			$ff=$this->fixedfields[$s[1]];
	 			$q_index=$ff["QUERIES_INDEX"];
	 			$q=$ff["QUERIES"][$q_index[$$op]];
    			if ($q["DEFAULT_OPERATOR"])
    				$operator_multi=$q["DEFAULT_OPERATOR"];
    			switch ($this->fixedfields[$s[1]]["INPUT_TYPE"]) {
    				case "list":
    					$options=$this->fixedfields[$s[1]]["INPUT_OPTIONS"]["OPTIONS"][0];
    					$opt=array();
    					for ($j=0; $j<count($options["OPTION"]); $j++) {
    						if (substr($options["OPTION"][$j]["value"],0,4)=="msg:") {
    							$opt[$options["OPTION"][$j]["VALUE"]]=$msg[substr($options["OPTION"][$j]["value"],4,strlen($options["OPTION"][$j]["value"])-4)];
    						} else {
    							$opt[$options["OPTION"][$j]["VALUE"]]=$options["OPTION"][$j]["value"];	
    						}
    					}
    					for ($j=0; $j<count($field); $j++) {
    						$field_aff[$j]=$opt[$field[$j]];
    					}
    					break;
    				case "query_list":
    					$requete=$this->fixedfields[$s[1]]["INPUT_OPTIONS"]["QUERY"][0]["value"];
    					$resultat=mysql_query($requete);
    					$opt=array();
    					while ($r_=@mysql_fetch_row($resultat)) {
    						$opt[$r_[0]]=$r_[1];
    					}
    					for ($j=0; $j<count($field); $j++) {
    						$field_aff[$j]=$opt[$field[$j]];
    					}
    					break;
    				case "marc_list":
    					$opt=new marc_list($this->fixedfields[$s[1]]["INPUT_OPTIONS"]["NAME"][0]["value"]);
    					for ($j=0; $j<count($field); $j++) {
    						$field_aff[$j]=$opt->table[$field[$j]];
    					}
    					break;
    				default:
    					$field_aff=$field;
    					break;		
    			}
    		}
    		
    		//Ajout des variables si necessaire
    		reset($fieldvar);
    		$fieldvar_aff=array();
    		while (list($var_name,$var_value)=each($fieldvar)) {
    			//Recherche de la variable par son nom
    			$vvar=$this->fixedfields[$s[1]]["VAR"];
    			for ($j=0; $j<count($vvar); $j++) {
    				if (($vvar[$j]["TYPE"]=="input")&&($vvar[$j]["NAME"]==$var_name)) {
    					
    					//Calcul de la visibilite
    					$varname=$vvar[$j]["NAME"];
   		 				$visibility=1;
   		 				$vis=$vvar[$j]["OPTIONS"]["VAR"][0];
   		 				if ($vis["NAME"]) {
   		 					$vis_name=$vis["NAME"];
   		 					global $$vis_name;
   		 					if ($vis["VISIBILITY"]=="no") $visibility=0;
   		 					for ($k=0; $k<count($vis["VALUE"]); $k++) {
   		 						if ($vis["VALUE"][$k]["value"]==$$vis_name) {
   		 							if ($vis["VALUE"][$k]["VISIBILITY"]=="no") $sub_vis=0; else $sub_vis=1;
   		 							if ($vis["VISIBILITY"]=="no") $visibility|=$sub_vis; else $visibility&=$sub_vis;
   		 							break;
   		 						}
   		 					}
   		 				}
    					
    					$var_list_aff=array();
    					
    					if ($visibility) {		
    						switch ($vvar[$j]["OPTIONS"]["INPUT"][0]["TYPE"]) {
    							case "query_list":
    								$query_list=$vvar[$j]["OPTIONS"]["INPUT"][0]["QUERY"][0]["value"];
       								$r_list=mysql_query($query_list);
    								while ($line=mysql_fetch_array($r_list)) {
    									$as=array_search($line[0],$var_value);
    									if (($as!==false)&&($as!==NULL)) {
    										$var_list_aff[]=$line[1];
    									}
    								}
    								$fieldvar_aff[]=implode(" ".$msg["search_or"]." ",$var_list_aff);
    								break;
    						}
    						$fieldvar_aff[count($fieldvar_aff)-1]=$vvar[$j]["COMMENT"]." : ".$fieldvar_aff[count($fieldvar_aff)-1];
    					}
    				}
    			}
    		}
    		
    		switch ($operator_multi) {
    			case "and":
    				$op_list=$msg["search_and"];
    				break;
    			case "or":
    				$op_list=$msg["search_or"];
    				break;
    			default:
    				$op_list=$msg["search_or"];
    				break;
    		}
    		$texte=implode(" ".$op_list." ",$field_aff);
    		//$texte=implode(" ".$msg["search_or"]." ",$field_aff);
    		if (count($fieldvar_aff)) $texte.=" [".implode(" ; ",$fieldvar_aff)."]";
    		
    		$inter="inter_".$i."_".$search[$i];
    		switch ($$inter) {
    			case "and":
    				$inter_op=$msg["search_and"];
    				break;
    			case "or":
    				$inter_op=$msg["search_or"];
    				break;
    			case "ex":
    				$inter_op=$msg["search_exept"];
    				break;
    			default:
    				$inter_op="";
    				break;
    		}
    		if ($inter_op) $inter_op="<strong>".htmlentities($inter_op,ENT_QUOTES,$charset)."</strong>";
    		$r.=$inter_op." <i><strong>".htmlentities($title,ENT_QUOTES,$charset)."</strong> ".htmlentities($operator,ENT_QUOTES,$charset)." (".htmlentities($texte,ENT_QUOTES,$charset).")</i> ";
    	}
    	return $r;
    }

	function push() {
		global $search;
		global $pile_search;
		$pile_search[]=$this->serialize_search();
		for ($i=0; $i<count($search); $i++) {
			$op="op_".$i."_".$search[$i];
    		$field_="field_".$i."_".$search[$i];
    		$inter="inter_".$i."_".$search[$i];
    		$fieldvar="fieldvar_".$i."_".$search[$i];
    		global $$op;
    		global $$field_;
    		global $$inter;
    		global $$fieldvar;
    		$$op="";
    		$$field_="";
    		$$inter="";
    		$$fieldvar="";
		}
		$search="";
	}
	
	function pull() {
		global $pile_search;
		$this->unserialize_search($pile_search[count($pile_search)-1]);
		$t=array();
		for ($i=0; $i<count($pile_search)-1; $i++) {
			$t[$i]=$pile_search[$i];
		}
		$pile_search=$t;
	}
	
	function get_unimarc_fields() {
		$r=array();
		foreach($this->fixedfields as $id=>$values) {
			if ($values["UNIMARCFIELD"]) {
				$r[$values["UNIMARCFIELD"]]["TITLE"][]=$values["TITLE"];
				foreach($values["QUERIES_INDEX"] as $op=>$top) {
					$r[$values["UNIMARCFIELD"]]["OPERATORS"][$op]=$this->operators[$op];
				}
			}
		}
		return $r;
	}
	
	function show_results_fichier($url,$url_to_search_form,$hidden_form=true,$search_target="", $acces=false) {
    	global $dbh;
    	global $begin_result_liste;
    	global $nb_per_page_search;
    	global $page;
    	global $charset;
    	global $search;
    	global $msg;
    	global $pmb_nb_max_tri;
    	global $affich_tris_result_liste;
    	global $pmb_allow_external_search;
    	global $debug;
		global $gestion_acces_active, $gestion_acces_user_notice,$PMBuserid, $pmb_allow_external_search;
		global $link_bulletin;

 				
		$start_page=$nb_per_page_search*$page;
    	
    	//Y-a-t-il des champs ?
    	if (count($search)==0) {
    		error_message_history($msg["search_empty_field"], $msg["search_no_fields"], 1);
    		exit();
    	}
    	
    	//Verification des champs vides
    	for ($i=0; $i<count($search); $i++) {
    		$op="op_".$i."_".$search[$i];
    		global $$op;
     		$field_="field_".$i."_".$search[$i];
    		global $$field_;
    		$field=$$field_;
    		$s=explode("_",$search[$i]);
    		$bool=false;
    		if ($s[0]=="f") {
    			$champ=$this->fixedfields[$s[1]]["TITLE"];
    			if ((string)$field[0]=="") {
    				$bool=true;
    			}
    		} elseif(array_key_exists($s[0],$this->pp)) {
    			$champ=$this->pp[$s[0]]->t_fields[$s[1]]["TITRE"];
    			if ((string)$field[0]=="") {
    				$bool=true;
    			}
    		} elseif($s[0]=="s") {
    			$champ=$this->specialfields[$s[1]]["TITLE"];
    			$type=$this->specialfields[$s[1]]["TYPE"];
		 		for ($is=0; $is<$this->tableau_speciaux["TYPE"]; $is++) {
					if ($this->tableau_speciaux["TYPE"][$is]["NAME"]==$type) {
						$sf=$this->specialfields[$s[1]];
						global $include_path;
						require_once($include_path."/search_queries/specials/".$this->tableau_speciaux["TYPE"][$is]["PATH"]."/search.class.php");
						$specialclass= new $this->tableau_speciaux["TYPE"][$is]["CLASS"]($s[1],$sf,$i,$this);
						$bool=$specialclass->is_empty($field);
						break;
					}
				}
    		}
    		if (($bool)&&(!$this->op_empty[$$op])) {
    			error_message_history($msg["search_empty_field"], sprintf($msg["search_empty_error_message"],$champ), 1);
    			exit();
    		}
    	}
    	
    	$table=$this->make_search();

		if ($acces==true && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	    	$this->filter_searchtable_from_accessrights($table, $PMBuserid);
		}
    	
		$requete="select count(1) from $table";
		$nb_results=mysql_result(mysql_query($requete),0,0);
    	
		
    	//Y-a-t-il une erreur lors de la recherche ?
    	if ($this->error_message) {
    		error_message_history("", $this->error_message, 1);
    		exit();
    	}
    	
    	if ($hidden_form)
    		print $this->make_hidden_search_form($url);
    	
    	
    	$human_requete = $this->make_human_query();
    	print "<strong>".$msg["search_search_extended"]."</strong> : ".$human_requete ;
		if ($debug) print "<br />".$this->serialize_search();
		if ($nb_results) {
			print " => ".$nb_results." ".$msg["fiche_found"]."<br />\n";
		} else print "<br />".$msg["1915"]." ";
		
    	
		$requete="select $table.* from ".$table.",fiche where fiche.id_fiche=$table.id_fiche"; 
    	$requete .= " limit ".$start_page.",".$nb_per_page_search;
    	
    	$resultat=mysql_query($requete,$dbh);
    	
    	if(mysql_num_rows($resultat)){
	    	$result_fic=array();
			$fic = new fiche();
	    	while ($r=mysql_fetch_object($resultat)) {
	    		$result_fic[$r->id_fiche] = $fic->get_values($r->id_fiche,1);
	    	}
	    	if($result_fic)
	    		print "<div class='row'>".$fic->display_results_tableau($result_fic)."</div>";
    	}
    	if($this->limited_search)
    		$limit_script = "&limited_search=1";
    	else $limit_script="";
		print "<div class='row'><input type='button' class='bouton' onClick=\"document.search_form.action='$url_to_search_form$limit_script'; document.search_form.target='$search_target'; document.search_form.submit(); return false;\" value=\"".$msg["search_back"]."\"/></div>";
    	//Gestion de la pagination
    	if ($nb_results) {
	  	  	$n_max_page=ceil($nb_results/$nb_per_page_search);
	   	 	
	   	 	if (!$page) $page_en_cours=0 ;
				else $page_en_cours=$page ;
		
	   	 	// affichage du lien precedent si necessaire
   		 	if ($page>0) {
   		 		$nav_bar .= "<a href='#' onClick='document.search_form.page.value-=1; ";
   		 		if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
   		 		$nav_bar .= "document.search_form.submit(); return false;'>";
   	 			$nav_bar .= "<img src='./images/left.gif' border='0'  title='".$msg[48]."' alt='[".$msg[48]."]' hspace='3' align='middle'/>";
    			$nav_bar .= "</a>";
    		}
        	
			$deb = $page_en_cours - 10 ;
			if ($deb<0) $deb=0;
			for($i = $deb; ($i < $n_max_page) && ($i<$page_en_cours+10); $i++) {
				if($i==$page_en_cours) $nav_bar .= "<strong>".($i+1)."</strong>";
					else {
						$nav_bar .= "<a href='#' onClick=\"if ((isNaN(document.search_form.page.value))||(document.search_form.page.value=='')) document.search_form.page.value=1; else document.search_form.page.value=".($i)."; ";
    					if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
		    			$nav_bar .= "document.search_form.submit(); return false;\">";
    					$nav_bar .= ($i+1);
    					$nav_bar .= "</a>";
						}
				if($i<$n_max_page) $nav_bar .= " "; 
				}
        	
			if(($page+1)<$n_max_page) {
    			$nav_bar .= "<a href='#' onClick=\"if ((isNaN(document.search_form.page.value))||(document.search_form.page.value=='')) document.search_form.page.value=1; else document.search_form.page.value=parseInt(document.search_form.page.value)+parseInt(1); ";
    			if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
    			$nav_bar .= "document.search_form.submit(); return false;\">";
    			$nav_bar .= "<img src='./images/right.gif' border='0' title='".$msg[49]."' alt='[".$msg[49]."]' hspace='3' align='middle'>";
    			$nav_bar .= "</a>";
        		} else 	$nav_bar .= "";
			$nav_bar = "<div align='center'>$nav_bar</div>";
	   	 	echo $nav_bar ;
	   	 	
    	}  	
    }
	
}
?>