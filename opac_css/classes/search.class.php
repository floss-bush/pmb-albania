<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search.class.php,v 1.90 2010-08-27 07:48:10 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Classe de gestion des recherches avancees

require_once($include_path."/isbn.inc.php");
require_once($include_path."/parser.inc.php");
require_once($class_path."/parametres_perso.class.php");
require_once($include_path."/templates/search.tpl.php");
require_once($class_path."/analyse_query.class.php");

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
	var $dynamics_not_visible;
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
	var $tableau_speciaux;
	var $operator_multi_value;
	
    function search($fichier_xml="") {
    	global $launch_search;
 		 			
    	$this->parse_search_file($fichier_xml);
    	$this->strip_slashes();
		foreach ( $this->dynamicfields as $key => $value ) {
       		$this->pp[$key]=new parametres_perso($value["TYPE"]);
		}
    }
    
    function strip_slashes() {
    	global $search,$explicit_search;
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
					global $opac_thesaurus;
					if($ajax == "categories" and $opac_thesaurus == 1){
						$fnamevar_id = "linkfield=\"fieldvar_".$n."_".$search."[id_thesaurus][]\"";
					}else{
						$fnamevar_id = "";
					}	       
					$r="<span class='search_value'><input autfield=\"$fname_id\" completion=\"$ajax\" $fnamevar_id id='$fnamesans' name=\"$fname\" value='".htmlentities($v[0],ENT_QUOTES,$charset)."' type=\"text\" class=\"ext_search_txt\"></span>" .
					"<span class='search_dico'><img src='images/dictionnaire.png' align='middle' onClick=\"document.getElementById('$fnamesans').focus(); simulate_event('$fnamesans');\"></span>" .						
					"<input name=\"$fname_id\" id=\"$fname_id\" value=\"\" type=\"hidden\">";
    				break;
    			case "text":
    				$r="<span class='search_value'><input type='text' name='field_".$n."_".$search."[]' value='".htmlentities($v[0],ENT_QUOTES,$charset)."' class=\"ext_search_txt\"/></span>";
    				break;
    			case "query_list":
    				$requete=$ff["INPUT_OPTIONS"]["QUERY"][0]["value"];
    				$resultat=mysql_query($requete);
    				$r="<span class='search_value'><select name='field_".$n."_".$search."[]' multiple size='5' class=\"ext_search_txt\">";
    				while ($opt=mysql_fetch_row($resultat)) {
    					$r.="<option value='".htmlentities($opt[0],ENT_QUOTES,$charset)."' ";
    					$as=array_search($opt[0],$v);
    					if (($as!==null)&&($as!==false)) $r.=" selected";
    					$r.=">".htmlentities($opt[1],ENT_QUOTES,$charset)."</option>";
    				}
    				$r.="</select></span>";
    				break;
    			case "list":
    				$options=$ff["INPUT_OPTIONS"]["OPTIONS"][0];
    				$r="<span class='search_value'><select name='field_".$n."_".$search."[]' multiple size='5' class=\"ext_search_txt\">";
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
    				$r.="</select></span>";
    				break;
    			case "marc_list":
    				$options=new marc_list($ff["INPUT_OPTIONS"]["NAME"][0]["value"]);
    				asort($options->table);
    				reset($options->table);

  		  			// gestion restriction par code utilise.
  		  			if ($ff["INPUT_OPTIONS"]["RESTRICTQUERY"][0]["value"]) {
  		  				$restrictquery=@mysql_query($ff["INPUT_OPTIONS"]["RESTRICTQUERY"][0]["value"]);
				  		if ($restrictqueryrow=mysql_fetch_row($restrictquery)) {
				  			if ($restrictqueryrow[0]) {
				  				$restrictqueryarray=explode(",",$restrictqueryrow[0]);
				  				$existrestrict=true;
				  			} else $existrestrict=false;
				  		} else $existrestrict=false;
  		  			} else $existrestrict=false;

    				$r="<span class='search_value'><select name='field_".$n."_".$search."[]' multiple size='5' class=\"ext_search_txt\">";
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
    				$r.="</select></span>";
    				break;
    			case "date":
    				$r="<span class='search_value'><input type='text' name='field_".$n."_".$search."[]' value='".htmlentities(format_date($v[0]),ENT_QUOTES,$charset)."' class=\"ext_search_txt\"/></span>";
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
			  		  			$r.="&nbsp;<span class='search_value'><select  id=\"fieldvar_".$n."_".$search."[".$varname."][]\" name=\"fieldvar_".$n."_".$search."[".$varname."][]\">\n";
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
			  		  			$r.="</select></span>";
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
   	 	} elseif (array_key_exists($s[0],$this->pp)) {
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
    	global $search, $msg ;
    	global $dbh;
    	global $include_path;
    	global $opac_multi_search_operator;
    	
	   	$last_table="";
	   	
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
						else $field[$j]=$field_origine;
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
						$operator = ($opac_multi_search_operator?$opac_multi_search_operator:"or");
					}
    				
    				//Ou logique si plusieurs valeurs
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
    				if($q["KEEP_EMPTYWORD"]) $field[$j]=strip_empty_chars($field[$j]);
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
						$operator=$q["DEFAULT_OPERATOR"];
					} else {
						$operator = ($opac_multi_search_operator?$opac_multi_search_operator:"or");
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
    				$requete="alter table ".$prefixe."t_".$i."_".$search[$i]." add unique(notice_id)";
    				mysql_query($requete);
	   			} else {
	   				$table="t_".$i."_".$search[$i];
   	 				$requete="create temporary table t_".$i."_".$search[$i]." ENGINE=MyISAM ".$main;
	   				mysql_query($requete,$dbh);
    				$requete="alter table t_".$i."_".$search[$i]." add idiot int(1) default 1";
    				@mysql_query($requete);
    				$requete="alter table t_".$i."_".$search[$i]." add unique(notice_id)";
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
    		switch ($$inter) {
    			case "and":
    				$requete.="select ".$table.".* from $last_table,$table where ".$table.".notice_id=".$last_table.".notice_id"; 
					//and $table.idiot is null and $last_table.idiot is null";
					@mysql_query($requete,$dbh);
    				break;
    			case "or":
    				$requete.="select * from ".$table;
    				@mysql_query($requete,$dbh);
    				if ($prefixe) {
    					$requete="alter table ".$prefixe."t".$i." add idiot int(1)";
    					@mysql_query($requete);
    					$requete="alter table ".$prefixe."t".$i." add unique(notice_id)";
    					@mysql_query($requete);
   					} else {
   						$requete="alter table t".$i." add idiot int(1)";
   						@mysql_query($requete);
   						$requete="alter table t".$i." add unique(notice_id)";
   						@mysql_query($requete);
   					}
    				if ($prefixe) {
    					$requete="insert into ".$prefixe."t".$i." (notice_id,idiot) select distinct ".$last_table.".notice_id,".$last_table.".idiot from ".$last_table." left join ".$table." on ".$last_table.".notice_id=".$table.".notice_id where ".$table.".notice_id is null";
    				} else {
    					$requete="insert into t".$i." (notice_id,idiot) select distinct ".$last_table.".notice_id,".$last_table.".idiot from ".$last_table." left join ".$table." on ".$last_table.".notice_id=".$table.".notice_id where ".$table.".notice_id is null";
    				}
    				@mysql_query($requete,$dbh);
    				break;
    			case "ex":
    				//$requete_not="create temporary table ".$table."_b select notices.notice_id from notices left join ".$table." on notices.notice_id=".$table.".notice_id where ".$table.".notice_id is null";
    				//@mysql_query($requete_not);
    				//$requete_not="alter table ".$table."_b add idiot int(1), add unique(notice_id)";
    				//@mysql_query($requete_not);
    				//$requete.="select ".$table."_b.* from $last_table,".$table."_b where ".$table."_b.notice_id=".$last_table.".notice_id";
    				$requete.="select ".$last_table.".* from $last_table left join ".$table." on ".$table.".notice_id=".$last_table.".notice_id where ".$table.".notice_id is null";
    				@mysql_query($requete);
    				//$requete="drop table ".$table."_b";
    				//@mysql_query($requete);
    				if ($prefixe) {
    					$requete="alter table ".$prefixe."t".$i." add idiot int(1)";
    					@mysql_query($requete);
    					$requete="alter table ".$prefixe."t".$i." add unique(notice_id)";
    					@mysql_query($requete);
    				} else {
    					$requete="alter table t".$i." add idiot int(1)";
    					@mysql_query($requete);
    					$requete="alter table t".$i." add unique(notice_id)";
    					@mysql_query($requete);
    				}
   					break;
   				default:
   					$requete.="select * from ".$table;
    				@mysql_query($requete,$dbh);
   					if ($prefixe) {
    					$requete="alter table ".$prefixe."t".$i." add idiot int(1)";
    					@mysql_query($requete);
    					$requete="alter table ".$prefixe."t".$i." add unique(notice_id)";
    					@mysql_query($requete);
    				} else {
    					$requete="alter table t".$i." add idiot int(1)";
    					@mysql_query($requete);
    					$requete="alter table t".$i." add unique(notice_id)";
    					@mysql_query($requete);
    				}
    				break;
    		}
    		mysql_query("drop table if exists $last_table",$dbh);
    		mysql_query("drop table if exists $table",$dbh);
    		if ($prefixe) {
    			$last_table=$prefixe."t".$i;
    		} else {
    			$last_table="t".$i;	
    		}
    	}
    	return $last_table;
    }
    
    function make_hidden_search_form($url,$form_name="form_values",$target="",$close_form=true) {
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
    			$r.="<input type='hidden' name='".$field_."[]' value='".htmlentities($field[$j],ENT_QUOTES,$charset)."'/>\n";
    		}
    		reset($fieldvar);
    		while (list($var_name,$var_value)=each($fieldvar)) {
    			for ($j=0; $j<count($var_value); $j++) {
    				$r.="<input type='hidden' name='".$fieldvar_."[".$var_name."][]' value='".htmlentities($var_value[$j],ENT_QUOTES,$charset)."'/>";
    			}
    		}
    	}
    	$r.="<input type='hidden' name='page' value='$page'/>\n";
    	if ($close_form) $r.="</form>";
    	return $r;
    }
    
    function make_human_query() {
    	global $search;
    	global $msg;
    	global $charset;
    	global $include_path;
    	
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
    		
    		switch ($this->operator_multi_value) {
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
    	
    	$r="";
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
    			//appel de la fonction make_human_query de la classe du champ special
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
    
    function show_results($url,$url_to_search_form,$hidden_form=true,$search_target="") {
    	global $dbh;
    	global $begin_result_liste;
    	global $opac_search_results_per_page;
    	$nb_per_page_search = $opac_search_results_per_page;
    	global $page;
    	global $charset;
    	global $search;
    	global $msg, $opac_notices_depliable ;
    	global $debug;
    	
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
		
		//Y-a-t-il une erreur lors de la recherche ?
    	if ($this->error_message) {
    		error_message_history("", $this->error_message, 1);
    		exit();
    	}
    	
    	if ($hidden_form)
    		print $this->make_hidden_search_form($url);
    	$requete="select count(1) from $table";
    	$nb_results=mysql_result(mysql_query($requete),0,0);

/*
    	$requete="select $table.*,notices.niveau_biblio from ".$table.",notices where notices.notice_id=$table.notice_id order by notices.index_sew limit ".$start_page.",".$nb_per_page_search;
    	$resultat=mysql_query($requete,$dbh);*/
    	
		print pmb_bidi("<strong>".$msg["search_search_extended"]."</strong> : ".$this->make_human_query());
		if ($nb_results) {
			print " => ".$nb_results." ".$msg["1916"]."<br />\n";
			if ($opac_notices_depliable) print $begin_result_liste;
		} else print "<br />".$msg["1915"]." ";
		print "<input type='button' class='bouton' onClick=\"document.search_form.action='$url_to_search_form'; document.search_form.target='$search_target'; document.search_form.submit(); return false;\" value=\"".$msg["search_back"]."\"/>";
    	while ($r=mysql_fetch_object($resultat)) {
    		if($r->niveau_biblio != 's' && $r->niveau_biblio != 'a') {
				// notice de monographie
				$nt = new mono_display($r->notice_id, 6, $this->link, 1, $this->link_expl, '', $this->link_explnum,1);
			} else {
				// on a affaire a un periodique
				$nt = new serial_display($r->notice_id, 6, $this->link_serial, $this->link_analysis, $this->link_bulletin, "", $this->link_explnum_serial, 0 );
				}
    		echo pmb_bidi("<div class='row'>".$nt->result."</div>");
    	}
    	
    	//Gestion de la pagination
    	if ($nb_results) {
	  	  	$n_max_page=ceil($nb_results/$nb_per_page_search);
	   	 	echo "<div align='center'>";
   		 	if ($page>0) {
   		 		echo "<a href='#' onClick='document.search_form.page.value-=1; ";
   		 		if (!$hidden_form) echo "document.search_form.launch_search.value=1; ";
   		 		echo "document.search_form.submit(); return false;'>";
   	 			echo "<img src='./images/left.gif' border='0'  title='".$msg["prec_page"]."' alt='[".$msg["prec_page"]."]' hspace='3' align='middle'/>";
    			echo "</a>";
    		}
    		echo "<strong>page ".($page+1)."/".$n_max_page."</strong>";
    		if (($page+1)<$n_max_page) {
    			echo "<a href='#' onClick=\"if ((isNaN(document.search_form.page.value))||(document.search_form.page.value=='')) document.search_form.page.value=1; else document.search_form.page.value=parseInt(document.search_form.page.value)+parseInt(1); ";
    			if (!$hidden_form) echo "document.search_form.launch_search.value=1; ";
    			echo "document.search_form.submit(); return false;\">";
    			echo "<img src='./images/right.gif' border='0' title='".$msg["next_page"]."' alt='[".$msg["next_page"]."]' hspace='3' align='middle'>";
    			echo "</a>";
    		}
    		echo "</div>";
    	}  	
    }
    
    function show_results_unimarc($url,$url_to_search_form,$hidden_form=true,$search_target="") {
    	global $dbh;
    	global $begin_result_liste;
    	global $opac_notices_depliable;
    	global $opac_search_results_per_page;
    	$nb_per_page_search = $opac_search_results_per_page;
    	global $page;
    	global $charset;
    	global $search;
    	global $msg;
    	global $affich_tris_result_liste;
    	global $count;
    	global $add_cart_link;    	
    	
    	$start_page=$nb_per_page_search*($page-1);
    	
    	//Y-a-t-il des champs ?
    	if (count($search)==0) {
    		return;
    	}

    	$table=$this->make_search();
    	$requete="select count(1) from $table";
    	$nb_results=mysql_result(mysql_query($requete),0,0);
    	$count=$nb_results;
    	
    	$requete = "select * from $table";
		$requete .= " limit ".$start_page.",".$nb_per_page_search;
		
		$resultat=mysql_query($requete,$dbh);
		
    	print "	<div id=\"resultatrech\"><h3>$msg[resultat_recherche]</h3>\n
		<div id=\"resultatrech_container\">
		<div id=\"resultatrech_see\">
		";

		print pmb_bidi("<h3>$nb_results $msg[titles_found] ".$this->make_human_query()." <input type='button' class='bouton' value='".$msg["connecteurs_alter_criteria"]."' onClick='document.form_values.action=\"./index.php?lvl=search_result&search_type_asked=external_search\"; document.form_values.submit();'/></h3>");
		
		if ($opac_show_suggest) {
			$bt_sugg = "&nbsp;&nbsp;&nbsp;<a href=# ";		
			if ($opac_resa_popup) $bt_sugg .= " onClick=\"w=window.open('./do_resa.php?lvl=make_sugg&oresa=popup','doresa','scrollbars=yes,width=600,height=600,menubar=0,resizable=yes'); w.focus(); return false;\"";
			else $bt_sugg .= "onClick=\"document.location='./do_resa.php?lvl=make_sugg&oresa=popup' \" ";			
			$bt_sugg.= " >".$msg[empr_bt_make_sugg]."</a>";
			print $bt_sugg;
		}
		flush();
		
		$entrepots_localisations = array();
		$entrepots_localisations_sql = "SELECT * FROM entrepots_localisations ORDER BY loc_visible DESC";
		$res = mysql_query($entrepots_localisations_sql);
		while ($row = mysql_fetch_array($res)) {
			$entrepots_localisations[$row["loc_code"]] = array("libelle" => $row["loc_libelle"], "visible" => $row["loc_visible"]); 
		}	
		
		if ($opac_notices_depliable) print $begin_result_liste;
		
		print $add_cart_link;
		
		print "	</div>\n
		<div id=\"resultatrech_liste\">";
		print "<blockquote>";
    	while ($r=mysql_fetch_object($resultat)) {
			print aff_notice_unimarc($r->notice_id, 0, $entrepots_localisations);
    	}
    	print "</blockquote>";   
    	print " </div>\n
		</div>
		</div>"; 	
    }    
    
    // fonction de calcul de la visibilite d'un champ de recherche
    function visibility($ff) {
    	
    	if (!count($ff["VARVIS"])) return $ff["VISIBILITY"];
     	 
    	for ($i=0; $i<count($ff["VARVIS"]); $i++) {
    		$name=$ff["VARVIS"][$i]["NAME"] ;
    		global $$name;
    		$visibilite=$ff["VARVIS"][$i]["VISIBILITY"] ;
    		if (isset($ff["VARVIS"][$i]["VALUE"][$$name])) {
    			if ($visibilite) 
    				$test = $ff["VARVIS"][$i]["VALUE"][$$name] ;
    			else  
    				$test = $visibilite || $ff["VARVIS"][$i]["VALUE"][$$name] ;
    			return $test ;
    		}
    	} // fin for
    	// aucune condition verifiee : on retourne la valeur par defaut
    	return $ff["VISIBILITY"] ;
    }
    
    //Templates des listes d'operateurs
    function show_form($url,$result_url,$result_target='') {
    	global $charset;
    	global $search;
    	global $add_field;
    	global $delete_field;
    	global $launch_search;
    	global $page;
    	global $search_form;
    	global $msg;
    	global $include_path;
    	global $opac_extended_search_auto;
    	global $limitsearch;
    	if (($add_field)&&(($delete_field==="")&&(!$launch_search)))
    		$search[]=$add_field;

    	$search_form=str_replace("!!url!!",$url,$search_form);
    	//Generation de la liste des champs possibles
    	if ($opac_extended_search_auto) $r="<select name='add_field' id='add_field' onChange=\"if (this.form.add_field.value!='') { this.form.action='$url'; this.form.target=''; this.form.submit();} else { alert('".htmlentities($msg["multi_select_champ"],ENT_QUOTES,$charset)."'); }\" >\n";
    	else $r="<select name='add_field' id='add_field'>\n";
    	$r.="<option value='' style='color:#000000'>".htmlentities($msg["multi_select_champ"],ENT_QUOTES,$charset)."</font></option>\n";
    	
    	//Champs fixes
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
    		if ($this->visibility($ff)) {
    			if ($open_optgroup_en_attente_affiche && !$open_optgroup_deja_affiche) {
    				$r.=$r_opt_groupe ;
    				$open_optgroup_deja_affiche = 1 ;
    				$open_optgroup_en_attente_affiche = 0 ;
    				$open_optgroup = 1 ; 
    			}
    			$r.="<option value='f_".$id."' style='color:#000000'>".htmlentities($ff["TITLE"],ENT_QUOTES,$charset)."</font></option>\n";
    		}
    	}
    	
    	//Champs dynamiques
    	if ($open_optgroup) $r.="</optgroup>\n";
    	// $r.="<option disabled style='border-left:0px;border-right:0px;border-top:0px;border-bottom:1px;border-style:solid;'></option>\n";
    	$r_custom="";
    	$custom_flag=false;
    	
    	if(!$this->dynamics_not_visible){
    		foreach ( $this->dynamicfields as $key => $value ) {
    			if(!$this->pp[key]->no_special_fields){
       				$r_custom.="<optgroup label='".$msg["search_custom_".$value["TYPE"]]."' class='erreur'>\n";
	   		 		reset($this->pp[$key]->t_fields);
	   		 		while (list($id,$df)=each($this->pp[$key]->t_fields)) {
	    				if ($df["OPAC_SHOW"]) {
   		 					$custom_flag=true;
	    					$r_custom.="<option value='".$key."_".$id."' style='color:#000000'>".htmlentities($df["TITRE"],ENT_QUOTES,$charset)."</option>\n";
	    				}
	    			}
	    			$r_custom.="</optgroup>\n";
	    		}
			}
    	}

    	if ($custom_flag) $r.=$r_custom;
    	
    	//Champs speciaux
    	while (list($id,$sf)=each($this->specialfields)) {
    		if ($sf["SEPARATOR"]) {
    			if ($open_optgroup) $r.="</optgroup>\n";
    			// $r.="<option disabled style='border-left:0px;border-right:0px;border-top:0px;border-bottom:1px;border-style:solid;'></option>\n";
    			$r.="<optgroup label='".htmlentities($sf["SEPARATOR"],ENT_QUOTES,$charset)."' class='erreur'>\n";
    			$open_optgroup=1;
    		}
    		$r.="<option value='s_".$id."' style='color:#000000'>".htmlentities($sf["TITLE"],ENT_QUOTES,$charset)."</font></option>\n";
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
    			
    			if ($n>0) {
    				$r.="<td>";
    				$inter="inter_".$i."_".$search[$i];
    				global $$inter;
    				$r.="<span class='search_operator'><select name='inter_".$n."_".$search[$i]."'>";
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
    				$r.="</select></span>";
    				$r.="</td><td>";
    			} else $r.="<td>&nbsp;</td><td>";
    			$r.="<span class='search_critere'>";
    			if ($f[0]=="f") {
    				$r.=htmlentities($this->fixedfields[$f[1]]["TITLE"],ENT_QUOTES,$charset);
    			} elseif ($f[0]=="s") {
    				$r.=htmlentities($this->specialfields[$f[1]]["TITLE"],ENT_QUOTES,$charset);
    			} elseif (array_key_exists($f[0],$this->pp)) {
    				$r.=htmlentities($this->pp[$f[0]]->t_fields[$f[1]]["TITRE"],ENT_QUOTES,$charset);
    			}
    			$r.="</span></td>";
    			//Recherche des operateurs possibles
    			$r.="<td>";
    			$op="op_".$i."_".$search[$i];
    			global $$op;
    			if ($f[0]=="f") {	
     				$r.="<span class='search_sous_critere'><select name='op_".$n."_".$search[$i]."'>\n";
    				for ($j=0; $j<count($this->fixedfields[$f[1]]["QUERIES"]); $j++) {
    					$q=$this->fixedfields[$f[1]]["QUERIES"][$j];
    					$r.="<option value='".$q["OPERATOR"]."' ";
    					if ($$op==$q["OPERATOR"]) $r.="selected";
    					$r.=">".htmlentities($this->operators[$q["OPERATOR"]],ENT_QUOTES,$charset)."</option>\n";
    				}
    				$r.="</select></span>";
    			} elseif (array_key_exists($f[0],$this->pp)) {
    				
    				$datatype=$this->pp[$f[0]]->t_fields[$f[1]]["DATATYPE"];
    				$type=$this->pp[$f[0]]->t_fields[$f[1]]["TYPE"];
    				
    				$df=$this->get_id_from_datatype($datatype, $f[0]);
    				$r.="<span class='search_sous_critere'><select name='op_".$n."_".$search[$i]."'>\n";
    				for ($j=0; $j<count($this->dynamicfields[$f[0]]["FIELD"][$df]["QUERIES"]); $j++) {
    					$q=$this->dynamicfields[$f[0]]["FIELD"][$df]["QUERIES"][$j];
    					$as=array_search($type,$q["NOT_ALLOWED_FOR"]);
    					if (!(($as!==null)&&($as!==false))) {
    						$r.="<option value='".$q["OPERATOR"]."' ";
    						if ($$op==$q["OPERATOR"]) $r.="selected";
    						$r.=">".htmlentities($this->operators[$q["OPERATOR"]],ENT_QUOTES,$charset)."</option>\n";
    					}
    				}
    				$r.="</select></span>";
    				$r.="&nbsp;";
    			} elseif ($f[0]=="s") {
					//appel de la fonction get_input_box de la classe du champ spï¿½cial
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
    			$r.="</span></td>";
    			$delnotallowed=false;
    			if ($f[0]=="f") {
    				$delnotallowed=$this->fixedfields[$f[1]]["DELNOTALLOWED"];
       			} elseif ($f[0]=="s") {
    				$delnotallowed=$this->specialfields[$f[1]]["DELNOTALLOWED"];
    			}
 //   			global $onglet_persopac;
    			if(!$limitsearch){
	    			$r.="<td><span class='search_cancel'>".(!$delnotallowed?"<input type='button' class='bouton' value='".$msg["raz"]."' onClick=\"this.form.delete_field.value='".$n."'; this.form.action='$url'; this.form.target=''; this.form.submit();\">":"&nbsp;")."</span>";
	    			$r.="</td>";
    			}
    			$r.="</tr>\n";
    		
    			//Si c'est le dernier, on afficher le bouton rechercher...
    			if (($i==(count($search)-1))||(($delete_field==(count($search)-1))&&($i==(count($search)-2)))) 
    				$r.="\n<tr><td colspan='6' align='center' id='td_search_submit'>
						<span class='search_submit'><input type='button' class='boutonrechercher' value='".$msg["142"]."' onClick=\"this.form.launch_search.value=1; this.form.action='!!result_url!!'; this.form.page.value=''; !!target_js!! this.form.submit()\"/></span>
						\n</td></tr>";

    			$n++;
    		}
    	}
    	$r.="</table>\n";
    	
    	//Recherche explicite
    	$r.="<input type='hidden' name='explicit_search' value='1'/>\n";
    	
    	$search_form=str_replace("!!already_selected_fields!!",$r,$search_form);
    	$search_form=str_replace("!!page!!",$page,$search_form);
    	$search_form=str_replace("!!result_url!!",$result_url,$search_form);

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
    function parse_search_file($fichier_xml) {
    	global $include_path;
    	global $msg;
    	
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
			$t["DELNOTALLOWED"]=($ff["DELNOTALLOWED"]=="yes"?true:false);
			
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

			if (!isset($ff["VISIBILITY"]))
				$t["VISIBILITY"]=true;
			else 
				if ($ff["VISIBILITY"]=="yes") $t["VISIBILITY"]=true; else $t["VISIBILITY"]=false;
			$q=array();
			for ($j=0; $j<count($ff["QUERY"]); $j++) {
				$q["OPERATOR"]=$ff["QUERY"][$j]["FOR"];
				if ($ff["QUERY"][$j]["MULTIPLE"]=="yes"||($ff["QUERY"][$j]["CONDITIONAL"]=="yes")) {
					if($ff["QUERY"][$j]["MULTIPLE"]=="yes") $element = "PART";
					else $element = "VAR";
					for ($k=0; $k<count($ff["QUERY"][$j]["$element"]); $k++) {
						$pquery=$ff["QUERY"][$j][$element][$k];						
						if($element == "VAR"){
							$q[$k]["CONDITIONAL"]["name"] = $pquery["NAME"];
							$q[$k]["CONDITIONAL"]["value"] = $pquery["VALUE"][0]["value"];
						}
						if ($pquery["MULTIPLEWORDS"]=="yes")
							$q[$k]["MULTIPLE_WORDS"]=true;
						else
							$q[$k]["MULTIPLE_WORDS"]=false;
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

			// recuperation des visibilites parametrees
			for ($j=0; $j<count($ff["VAR"]); $j++) {
				$q=array();
				$q["NAME"]=$ff["VAR"][$j]["NAME"];
				if ($ff["VAR"][$j]["VISIBILITY"]=="yes") 
					$q["VISIBILITY"]=true;
				else 
					$q["VISIBILITY"]=false;
				for ($k=0; $k<count($ff["VAR"][$j]["VALUE"]); $k++) {
					$v=array();
					if ($ff["VAR"][$j]["VALUE"][$k]["VISIBILITY"]=="yes")
						$v[$ff["VAR"][$j]["VALUE"][$k]["value"]] = true ;
					else 
						$v[$ff["VAR"][$j]["VALUE"][$k]["value"]] = false ;
				} // fin for <value ...
				$q["VALUE"] = $v ;
				$t["VARVIS"][] = $q ;
			} // fin for
			
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
    } // fin parse_search_file

	// pour la gestion avec la DSI, recopiee de la partie gestion
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
    		
    		$texte=implode(" ".$msg["search_or"]." ",$field_aff);
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
}
?>