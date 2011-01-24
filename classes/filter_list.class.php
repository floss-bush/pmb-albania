<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: filter_list.class.php,v 1.27 2010-12-02 11:07:25 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/parser.inc.php");

class filter_list {
	var $filter_name; //nom du filtre (fichier)
	var $filter_source; //source xml (texte xml ou fichier)
	var $params; //tableau xml
	var $fixedfields; //tableau des champs fixes du xml
	var $specialfields; //tableau des champs spéciaux du xml
	var $fixedcolumns; //colonnes fixes (ids séparés par des virgules)
	var $sortablecolumns; //liste des champs dans l'ordre où s'effectuera le tri 
	var $filtercolumns; //liste des champs sur lesquels filtrer
	var $displaycolumns; //liste des champs à afficher
	var $specialcolumns; //liste des champs spéciaux à afficher (ids séparés par des virgules)
	var $multiple; //option multiple de la liste des filtres
	var $error; //booléen d'erreur
	var $error_message; //message de l'erreur
	var $css=""; //style d'affichage
	var $scripts=""; //scripts sur les lignes de résultat
	var $page; //page en cours
	var $nb_per_page; //nombre d'enregistrements par page
	var $query; //texte de la requête finale
	var $t_query; //ressource id de la requête finale
	var $no_filter; //booléen de filtrage ou non
	var $original_query; //requête d'origine
	var $select_original=""; //select d'origine
	var $where_original=""; //where d'origine
	var $from_original=""; //from d'origine
	var $filtered_query=""; //résultat de filters_query
	
    function filter_list($filter_name,$filter_source="",$display,$filter,$sort) {
    	$this->filter_name=$filter_name;
    	$this->filter_source=$filter_source;
    	$this->parse();	
    	$this->fixedcolumns=$this->params["REFERENCE"][0]["FIXED"];
    	$this->displaycolumns=$display;
    	if (!$this->displaycolumns) $this->displaycolumns=$this->fixedcolumns;
    	$this->sortablecolumns=$sort;
    	if (!$this->sortablecolumns) $this->sortablecolumns=$this->fixedcolumns;
    	$this->filtercolumns=$filter;
    	if (!$this->filtercolumns) $this->filtercolumns=$this->fixedcolumns;
    }
    
    //fonction d'activation du filtre
    function activate_filters() {
    	global $msg;
    	global $charset;
    	$requete=$this->display_query();
    	if (!$this->no_filter) {
    		if ($this->original_query) {
    			$temp=substr($this->original_query,0,strpos(strtolower($this->original_query),"from"));
    			$pos=strpos($temp,$this->params["REFERENCEKEY"][0][value]);
    			if ($pos===FALSE) {
    				$this->error=true;
    				$this->error_message=htmlentities(str_replace("%s",$this->params["REFERENCEKEY"][0][value],$msg["filters_original_query_no_key"]),ENT_QUOTES,$charset);
    			} else {
    				if ($this->original_query) $requete.=",table_filter_tempo";
    			}
    		}
    		if (!$this->filtered_query)		
    			$where=$this->filters_query();
    		else
    			$where=$this->filtered_query;
    				
    		if ($where) $requete.=$where;
    			else $this->no_filter=1;
    	}
    	if (($this->original_query)&&(!$this->error)) {
    		//création d'une table temporaire
    		$creer_table_tempo="CREATE TEMPORARY TABLE table_filter_tempo ENGINE=MyISAM (".$this->original_query.")";
    		@mysql_query ($creer_table_tempo);
    		$modif_primaire="ALTER TABLE table_filter_tempo add PRIMARY KEY (".$this->params["REFERENCEKEY"][0][value].")";
    		@mysql_query ($modif_primaire);  
    		$requete.=" and ".$this->params["REFERENCE"][0][value].".".$this->params["REFERENCEKEY"][0][value]."=table_filter_tempo.".$this->params["REFERENCEKEY"][0][value];
    		$requete.=" group by ".$this->params["REFERENCE"][0][value].".".$this->params["REFERENCEKEY"][0][value];
    	}
    	$requete.=$this->sort_query();
    	if (!$this->no_filter) {
    		$this->t_query=mysql_query($requete);
    	}
    	$requete.=$this->pager_query();
    	$this->query=$requete;
    	if (mysql_error()) $erreur=mysql_error();
    	if ($erreur) {
    		$this->error=true;
    		$this->error_message=$erreur;
    	} 	
    }
    
    //fonction de traduction des filtres en langage naturel
    function make_human_filters()
    {
    	global $class_path;
    	global $msg;
    	global $charset;
    	    	    	
    	$ret="";
    	
    	$human_filters=htmlentities($msg["filters_label"],ENT_QUOTES,$charset)." ";
    	$s=explode(",",$this->filtercolumns);	
    	//parcours des champs 
    	for ($i=0;$i<count($s);$i++) {
    		if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    			//champs personnalisés
    			require_once($class_path."/parametres_perso.class.php");
    			$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    			if (!$cp->no_special_fields) {
    				$id=substr($s[$i],1);
    				$valeurs_post="f".$cp->t_fields[$id]["NAME"];
    				global $$valeurs_post;
    				$v=array();
    				if ($$valeurs_post) $v=$$valeurs_post;
    				//Récupération du champ
    				$field=array();
					$field[ID]=$id;
					$field[NAME]=$cp->t_fields[$id][NAME];
					$field[MANDATORY]=$cp->t_fields[$id][MANDATORY];
					$field[ALIAS]=$cp->t_fields[$id][TITRE];
					$field[DATATYPE]=$cp->t_fields[$id][DATATYPE];
					$field[OPTIONS][0]=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$cp->t_fields[$id][OPTIONS], "OPTIONS");
					$field[VALUES]=$v;
					$field[PREFIX]=$this->params["REFERENCE"][0]["PREFIXNAME"];
					if (($cp->t_fields[$id][TYPE]!="list")&&($cp->t_fields[$id][TYPE]!="query_list")) {
						$field[OPTIONS][0][UNSELECT_ITEM][0][VALUE]="-1";
						$field[OPTIONS][0][UNSELECT_ITEM][0][value]=$msg["empr_perso_all_values"];
					}
					$human_filters.=strtolower($cp->t_fields[$id]["TITRE"])." \"";
					$temp=array();
					foreach($v as $dummykey) {
						if ($dummykey!=$field[OPTIONS][0][UNSELECT_ITEM][0][VALUE]) {
    						if (($field[DATATYPE]=="text")||($field[DATATYPE]=="comment")) $temp[]=$dummykey;
    							else $temp[]=$cp->get_formatted_output($dummykey,$id);
						}
					}
					if (count($temp)) {
						$human_filters.=implode(",",$temp);
					}
					$human_filters.="\" ".htmlentities($msg["filters_sort_next"],ENT_QUOTES,$charset)." ";	
    			}
    		} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    	 		//champs fixes
    			//est-ce que le champ est filtrable	
    	 		if ($this->fixedfields[$s[$i]]["FILTERABLE"]=="yes") {
    	 			$nom_valeur_post="f".$this->fixedfields[$s[$i]]["ID"];
    				$valeur_post=array();
    				global $$nom_valeur_post;
    				$valeur_post=$$nom_valeur_post;
    				if (is_array($valeur_post)) {
    					$t[0]=-1;
    					$v=array_diff($valeur_post,$t);
    					if (count($v)) {
    						if (substr($this->fixedfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->fixedfields[$s[$i]]["NAME"],4,strlen($this->fixedfields[$s[$i]]["NAME"])-4)];
    							else $nom=$this->fixedfields[$s[$i]]["NAME"];
    						$human_filters.=strtolower($nom)." \"";
    						if ($this->fixedfields[$s[$i]]["TABLE"]) {
    							$temp=array();
    							foreach($v as $dummykey) {
    	 							$requete="select ".$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value]." from ";
    	 							$requete.=$this->fixedfields[$s[$i]]["TABLE"][0][value];
    	 							$requete.=" where ".$this->fixedfields[$s[$i]]["TABLEKEY"][0][value]."='".$dummykey."'";
    	 							$execute_query=mysql_query($requete);
    	 							$resultat_query=mysql_fetch_row($execute_query);
    	 							$temp[]=$resultat_query[0];	
    	 						}
    	 						$human_filters.=implode(",",$temp);
    						} else {
    							$human_filters.=htmlentities(stripslashes(implode(",",$v)),ENT_QUOTES,$charset);
    						}
    						$human_filters.="\" ".htmlentities($msg["filters_sort_next"],ENT_QUOTES,$charset)." ";
    					}
    				}
    	 		}	 
    	 	} else {
    	 		$nom_valeur_post="f".$this->specialfields[$s[$i]]["ID"];
    			$valeur_post=array();
    			global $$nom_valeur_post;
    			$valeur_post=$$nom_valeur_post;
    			if (is_array($valeur_post)) {
    				$t[0]=-1;
    				$v=array_diff($valeur_post,$t);
    				if (count($v)) {
    					$temp=array();
    					if (substr($this->specialfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->specialfields[$s[$i]]["NAME"],4,strlen($this->specialfields[$s[$i]]["NAME"])-4)];
    						else $nom=$this->specialfields[$s[$i]]["NAME"];
    					$human_filters.=strtolower($nom)." \"";
    					foreach($v as $dummykey) {
    						$temp[]=$dummykey;
    					}
    					$human_filters.=htmlentities(stripslashes(implode(",",$temp)),ENT_QUOTES,$charset);
    					$human_filters.="\" ".htmlentities($msg["filters_sort_next"],ENT_QUOTES,$charset)." ";
    				}
    			}
    		}    					
    	}
    	if ($human_filters!=htmlentities($msg["filters_label"],ENT_QUOTES,$charset)." ") $human_filters=substr($human_filters,0,strlen($human_filters)-(strlen($msg["filters_sort_next"])+2));	
    		else $human_filters="";
    	    	    	
    	//champs triables
    	$human_sort=htmlentities($msg["sort_label"],ENT_QUOTES,$charset)." ";
    	$s=explode(",",$this->sortablecolumns);	
    	//parcours des champs 
    	for ($j=0;$j<count($s);$j++) {
    		$sort_list="sort_list_".$j;
    		global $$sort_list;
    		if (!$$sort_list) $$sort_list=$s[$j];
    		if ($$sort_list!="-1") {
    			for ($i=0;$i<count($s);$i++) {
    				if ($$sort_list==$s[$i]) {
    					if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    						//champs personnalisés
    						require_once($class_path."/parametres_perso.class.php");
    						$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    						if (!$cp->no_special_fields) {
    							$id=substr($s[$i],1,strlen($s[$i])-1);
    							$human_sort.=strtolower($cp->t_fields[$id]["TITRE"])." ".htmlentities($msg["filters_sort_next"],ENT_QUOTES,$charset)." ";
    						}
    					} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    	 					//champs fixes
    						//est-ce que le champ est filtrable	
    	 					if ($this->fixedfields[$s[$i]]["SORTABLE"]=="yes") {
    							if (substr($this->fixedfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->fixedfields[$s[$i]]["NAME"],4,strlen($this->fixedfields[$s[$i]]["NAME"])-4)];
    								else $nom=$this->fixedfields[$s[$i]]["NAME"];
    							$human_sort.=strtolower($nom)." ".htmlentities($msg["filters_sort_next"],ENT_QUOTES,$charset)." ";
    						}	 
    					} else {
    						if (substr($this->specialfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->specialfields[$s[$i]]["NAME"],4,strlen($this->specialfields[$s[$i]]["NAME"])-4)];
    							else $nom=$this->specialfields[$s[$i]]["NAME"];
    						$human_sort.=strtolower($nom)." ".htmlentities($msg["filters_sort_next"],ENT_QUOTES,$charset)." ";
    					}
    				}    					
    			}
    		}
    	}
    	if ($human_sort!=htmlentities($msg["sort_label"],ENT_QUOTES,$charset)." ") $human_sort=substr($human_sort,0,strlen($human_sort)-(strlen($msg["filters_sort_next"])+2));	
    			else $human_sort="";
    	
    	if (($human_sort)||($human_filters)) $ret="<div class='row'>".$human_filters."</div><div class='row'>".$human_sort."</div>";	
    	return $ret;
    }
    
    //fonction de manipulation de données pour le contenu du select et du from dans la requête
    function display_query() {
    	global $class_path;
    	global $msg, $charset;
    	$froms=array();
    	$dependant_left_joins=array();
    	$ret="";
    	$select=$this->params["REFERENCE"][0][value].".".$this->params["REFERENCEKEY"][0][value].",";
    	$bool_perso_exist=false;
    	$bool_ref_exist=false;

    	$s=explode(",",$this->displaycolumns);	
    	//parcours des champs 
    	for ($i=0;$i<count($s);$i++) {
    		if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    			//champs personnalisés
    			require_once($class_path."/parametres_perso.class.php");
    			$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    			if (!$cp->no_special_fields) {
    				$id=substr($s[$i],1,strlen($s[$i])-1);
    				print("<br />");
    				if ($cp->t_fields[$id][TYPE]=="list") {
    					$afrom=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values".$id."";
    					$afrom.=" left join ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_lists ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_lists".$id." on (".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values".$id.".".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id]["DATATYPE"]."=".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_lists".$id.".".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_list_value AND ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values".$id.".".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_champ = ".$id.")";
    					$froms[] = $afrom;
    					$select.=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values".$id.".".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id][DATATYPE]." ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id][DATATYPE].$id.",";
    				} else {
    					$afrom = " left join ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values".$id." on (".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values".$id."".".".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_origine"." = ".$this->params["REFERENCE"][0][value].".".$this->params["REFERENCEKEY"][0][value]." AND ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values".$id.".".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_champ = ".$id.")";
    					$dependant_left_joins[] = $afrom;
    					$select.=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values".$id.".".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id][DATATYPE]." ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id][DATATYPE].$id.",";
    				}
    				$bool_perso_exist=true;
    			}
    		} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    			//champs fixes
    			//est-ce que le champ est affichable
    			if ($this->fixedfields[$s[$i]]["DISPLAYABLE"]=="yes") {
    				if ($this->fixedfields[$s[$i]]["TABLE"][0][value]) {
    	 				if ($this->fixedfields[$s[$i]]["LINK"]) {
    	 					for ($x=0;$x<count($this->fixedfields[$s[$i]]["LINK"]);$x++) {
    	 						//de quelle type est la relation
    	 						if ($this->fixedfields[$s[$i]]["LINK"][$x]["TYPE"]=="nn") {
    	 							$afrom=$this->params["REFERENCE"][0][value]." left join ".$this->fixedfields[$s[$i]]["LINK"][$x]["TABLE"][0][value]." on (";
    	 							$afrom.=$this->params["REFERENCE"][0][value].".".$this->params["REFERENCEKEY"][0][value]."=";
    	 							$afrom.=$this->fixedfields[$s[$i]]["LINK"][$x]["TABLE"][0][value].".".$this->fixedfields[$s[$i]]["LINK"][$x]["REFERENCEFIELD"][0][value].")";
    	 							$afrom.=" left join ".$this->fixedfields[$s[$i]]["TABLE"][0][value]." on (".$this->fixedfields[$s[$i]]["TABLE"][0][value].".".$this->fixedfields[$s[$i]]["TABLEKEY"][0][value]."=";
    	 							$afrom.=$this->fixedfields[$s[$i]]["LINK"][$x]["TABLE"][0][value].".".$this->fixedfields[$s[$i]]["LINK"][$x]["EXTERNALFIELD"][0][value].")";
    	 							$froms[] = $afrom;
    	 							$bool_ref_exist=true;
    	 							$select.="GROUP_CONCAT(distinct ".$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value].") as ".$this->fixedfields[$s[$i]]["TABLEFIELD"][0]["NAME"].",";
    	 						} else {
    	 							$select.=$this->fixedfields[$s[$i]]["TABLE"][0][value].".";
    	 							$select.=$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value].",";
    	 							$froms[]=$this->fixedfields[$s[$i]]["TABLE"][0][value];
    	 						}
    	 					}	
    	 				} else $from.=$this->fixedfields[$s[$i]]["TABLE"][0][value].",";
    	 			} else {
    	 				$fields=explode(",",$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value]);
    	 				for ($g=0;$g<count($fields);$g++) {
    	 					$select.=$this->params["REFERENCE"][0][value].".".$fields[$g].",";
    	 				}
    	 			}
    	 		}		 
    	 	}   					
    	}

    	$u=explode(",",$this->filtercolumns);
    	$w=array_diff($u,$s);
    	//parcours des champs 
    	foreach ($w as $dummykey) {
    		if ((substr($dummykey,0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    			//champs personnalisés
    			require_once($class_path."/parametres_perso.class.php");
    			$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    			if (!$cp->no_special_fields) {
    				$id=substr($dummykey,1,strlen($dummykey)-1);
    				if ($bool_perso_exist==false) {	
    					$afrom=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values";
    					$afrom.=" left join ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_lists on (".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values.".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id]["DATATYPE"]."=".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_lists.".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_list_value)";
    					$froms[] = $afrom;
    					$bool_perso_exist=true;
    				}
    			}
    	 	} elseif (array_key_exists($dummykey,$this->fixedfields)) {
    	 		//champs fixes
    			//est-ce que le champ est filtrable	
    			if ($this->fixedfields[$dummykey]["FILTERABLE"]=="yes") {
    				if ($this->fixedfields[$dummykey]["TABLE"][0][value]) {
    	 				if ($this->fixedfields[$dummykey]["LINK"]) {
    						for ($x=0;$x<count($this->fixedfields[$dummykey]["LINK"]);$x++) {
    	 						//de quelle type est la relation
    	 						if ($this->fixedfields[$dummykey]["LINK"][$x]["TYPE"]=="nn") {
    	 							$afrom=$this->params["REFERENCE"][0][value]." left join ".$this->fixedfields[$dummykey]["LINK"][$x]["TABLE"][0][value]." on (";
    	 							$afrom.=$this->params["REFERENCE"][0][value].".".$this->params["REFERENCEKEY"][0][value]."=";
    	 							$bool_ref_exist=true;
    	 							$afrom.=$this->fixedfields[$dummykey]["LINK"][$x]["TABLE"][0][value].".".$this->fixedfields[$dummykey]["LINK"][$x]["REFERENCEFIELD"][0][value].")";
    	 							$afrom.=" left join ".$this->fixedfields[$dummykey]["TABLE"][0][value]." on (".$this->fixedfields[$dummykey]["TABLE"][0][value].".".$this->fixedfields[$dummykey]["TABLEKEY"][0][value]."=";
    	 							$afrom.=$this->fixedfields[$dummykey]["LINK"][$x]["TABLE"][0][value].".".$this->fixedfields[$dummykey]["LINK"][$x]["EXTERNALFIELD"][0][value].")";
    	 							$froms[] = $afrom;
    	 						} else {
    	 							$froms[]=$this->fixedfields[$dummykey]["TABLE"][0][value]."";
    	 						}
    	 					}
    	 				} else $froms[]=$this->fixedfields[$dummykey]["TABLE"][0][value]."";		
    	 			}
    	 		}		 
    	 	}  					
    	}
    	    	
    	$v=explode(",",$this->sortablecolumns);
    	$k=array_diff($v,$s);
    	$t=array_diff($k,$w);
    	 	
    	//parcours des champs 
    	foreach ($t as $dummykey) {
    		if ((substr($dummykey,0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    			//champs personnalisés
    			require_once($class_path."/parametres_perso.class.php");
    			$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    			if (!$cp->no_special_fields) {
    				$id=substr($dummykey,1,strlen($dummykey)-1);
    				if ($bool_perso_exist==false) {	
    					$afrom=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values";
    					$afrom.=" left join ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_lists on (".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values.".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id]["DATATYPE"]."=".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_lists.".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_list_value)";
    					$froms[]=$afrom;
    				}
    			}
    		} elseif (array_key_exists($dummykey,$this->fixedfields)) {
    			//champs fixes
    			//est-ce que le champ est triable	
    			if ($this->fixedfields[$dummykey]["SORTABLE"]=="yes") {
    				if ($this->fixedfields[$dummykey]["TABLE"][0][value]) {		
    					if ($this->fixedfields[$dummykey]["LINK"]) {
    						for ($x=0;$x<count($this->fixedfields[$dummykey]["LINK"]);$x++) {
    	 						//de quelle type est la relation
    							if ($this->fixedfields[$dummykey]["LINK"][$x]["TYPE"]=="nn") {
    	 							$afrom=$this->params["REFERENCE"][0][value]." left join ".$this->fixedfields[$dummykey]["LINK"][$x]["TABLE"][0][value]." on (";
    	 							$afrom.=$this->params["REFERENCE"][0][value].".".$this->params["REFERENCEKEY"][0][value]."=";
    	 							$bool_ref_exist=true;
    	 							$afrom.=$this->fixedfields[$dummykey]["LINK"][$x]["TABLE"][0][value].".".$this->fixedfields[$dummykey]["LINK"][$x]["REFERENCEFIELD"][0][value].")";
    	 							$afrom.=" left join ".$this->fixedfields[$dummykey]["TABLE"][0][value]." on (".$this->fixedfields[$dummykey]["TABLE"][0][value].".".$this->fixedfields[$dummykey]["TABLEKEY"][0][value]."=";
    	 							$afrom.=$this->fixedfields[$dummykey]["LINK"][$x]["TABLE"][0][value].".".$this->fixedfields[$dummykey]["LINK"][$x]["EXTERNALFIELD"][0][value].")";
    	 							$froms[]=$afrom;
    	 						} else {
    	 							$froms[]=$this->fixedfields[$dummykey]["TABLE"][0][value]."";
    	 						}
    	 					}
    	 				} else $froms[].=$this->fixedfields[$dummykey]["TABLE"][0][value]."";
    	 			}
    	 		}		 
    	 	}    					
    	}
    	if ($bool_ref_exist==false) {
    	 	$froms[]=$this->params["REFERENCE"][0][value];
    	 	$bool_ref_exist=true;
   		 }	   	 	
    	
    	if ($this->select_original) $select.=$this->select_original.",";
    	if ($this->from_original) $from.=$this->from_original.",";
    	$select=substr($select,0,strlen($select)-1);
		$froms[0] .= " ".implode(" ", $dependant_left_joins);
		$from = implode(",",$froms);
    	$ret="select ".$select." from ".$from;
    	return $ret; 	
    }
    
    //fonction de manipulation de données pour le contenu de l'order by dans la requête
    function sort_query() {
    	 global $class_path;
    	 global $msg, $charset;
    	 
    	 $ret="";
    	 $orderby="";
    	    	 
    	$s=explode(",",$this->sortablecolumns);	
    	//parcours des champs 
    	for ($j=0;$j<count($s);$j++) {
    		$sort_list="sort_list_".$j;
    		global $$sort_list;
    		if (!$$sort_list) $$sort_list=$s[$j];
    		if ($$sort_list!="-1") {
    			for ($i=0;$i<count($s);$i++) {
    				if ($$sort_list==$s[$i]) {
    					if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    						//champs personnalisés
    						require_once($class_path."/parametres_perso.class.php");
    						$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    						if (!$cp->no_special_fields) {
    							$id=substr($s[$i],1,strlen($s[$i])-1);
    							if ($cp->t_fields[$id][TYPE]=="list") {
    								$orderby.=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_list_lib,";
    							} else {
    								$orderby.=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id][DATATYPE].",";
    							}
    						}
    	 				} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    	 					//champs fixes
    						//est-ce que le champ est triable	
    	 					if ($this->fixedfields[$s[$i]]["SORTABLE"]=="yes") {
    	 						if ($this->fixedfields[$s[$i]]["TABLE"][0][value]) {
    	 							for ($x=0;$x<count($this->fixedfields[$s[$i]]["LINK"]);$x++) {
    	 								//de quelle type est la relation
    	 								if ($this->fixedfields[$s[$i]]["LINK"][$x]["TYPE"]=="nn") {
											$orderby.=$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value].",";
    	 								} else {
    	 									$orderby.=$this->fixedfields[$s[$i]]["TABLE"][0][value].".";
    	 									$orderby.=$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value].",";
    	 								}
    	 							}		
    	 						} else {
    	 							$orderby.=$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value].",";	
    	 						}
    	 					} 
    	 				} else {
    	 					$name_function=$this->specialfields[$s[$i]]["FUNCTION"];
							$r="";
							$v=array();
							$t=array();	
							$param=$this->specialfields[$s[$i]]["PARAM"][0][value];
							$execute_query=mysql_query("select ".$this->params["REFERENCEKEY"][0][value]." from ".$this->params["REFERENCE"][0][value]);
							while ($row=mysql_fetch_array($execute_query)) {
								$t[]=$row[$this->params["REFERENCEKEY"][0][value]];
							}
							mysql_free_result($execute_query);
							$bool=true;
							eval("\$r=".$name_function."(\$t,\$param,\$v,\$bool);");
    	 				}    					
    				}
    	 		}
    		}
    	}	   	 	
    	 
    	if ($orderby) {
    		$orderby=substr($orderby,0,strlen($orderby)-1);
    		$ret=" order by ".$orderby;
    	}
    	return $ret; 	
    }
    
    //fonction de manipulation de données pour le contenu du where dans la requête
    function filters_query() {
    	global $class_path;
    	global $msg, $charset;
    	$ret="";
    	$where="";
    	if ($this->where_original) $where.=$this->where_original." and ";
    	
    	$s=explode(",",$this->filtercolumns);	
    	//parcours des champs 
    	for ($i=0;$i<count($s);$i++) {
    		if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    			//champs personnalisés
    			require_once($class_path."/parametres_perso.class.php");
    			$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    			if (!$cp->no_special_fields) {
    				$id=substr($s[$i],1,strlen($s[$i])-1);
    				$valeurs_post="f".$cp->t_fields[$id]["NAME"];
    				$v=array();
    				global $$valeurs_post;
    				if ($$valeurs_post) $v=$$valeurs_post;
    				//Récupération du champ
    				$field=array();
					$field[ID]=$id;
					$field[NAME]=$cp->t_fields[$id][NAME];
					$field[MANDATORY]=$cp->t_fields[$id][MANDATORY];
					$field[ALIAS]=$cp->t_fields[$id][TITRE];
					$field[DATATYPE]=$cp->t_fields[$id][DATATYPE];
					$field[OPTIONS][0]=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$cp->t_fields[$id][OPTIONS], "OPTIONS");
					$field[VALUES]=$v;
					$field[PREFIX]=$this->params["REFERENCE"][0]["PREFIXNAME"];
					if (($cp->t_fields[$id][TYPE]!="list")&&($cp->t_fields[$id][TYPE]!="query_list")) {
						$field[OPTIONS][0][UNSELECT_ITEM][0][VALUE]="-1";
						$field[OPTIONS][0][UNSELECT_ITEM][0][value]=$msg["empr_perso_all_values"];
						$field[OPTIONS][0][DEFAULT_VALUE][0][value]="-1";
					}
					$bool=false;
					$t[0]=$field[OPTIONS][0][UNSELECT_ITEM][0][VALUE];
    				$w=array_diff($v,$t);
    				if (count($w)) {
    					$where.=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values.".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$field[DATATYPE]." in ('".implode("','",$v)."') and ";
					}
					$where.=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values.".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_champ=".$id." and ";
					$where.=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values.".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_origine=".$this->params["REFERENCE"][0][value].".".$this->params["REFERENCEKEY"][0][value]." and ";
    			}
    		} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    			//champs fixes
    			//est-ce que le champ est filtrable	
    			if ($this->fixedfields[$s[$i]]["FILTERABLE"]=="yes") {
    				$nom_valeurs_post="f".$this->fixedfields[$s[$i]]["ID"];
    				$valeurs_post=array();
    				global $$nom_valeurs_post;
    				$valeurs_post=$$nom_valeurs_post;
    				if (is_array($valeurs_post)) {
    					$t[0]=-1;
    					$v=array_diff($valeurs_post,$t);
    					if (count($v)) {
    						if ($this->fixedfields[$s[$i]]["TABLE"][0][value]) $where.=$this->fixedfields[$s[$i]]["TABLE"][0][value].".".$this->fixedfields[$s[$i]]["TABLEKEY"][0][value]." in (".implode(",",$v).") and ";
    							else $where.=$this->params["REFERENCE"][0][value].".".$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value]." in ('".implode("','",$v)."') and ";
    					}
    				} 	
    				if ($this->fixedfields[$s[$i]]["TABLE"][0][value]) {	
    					for ($x=0;$x<count($this->fixedfields[$s[$i]]["LINK"]);$x++) {
    						//de quelle type est la relation
    						if ($this->fixedfields[$s[$i]]["LINK"][$x]["TYPE"]=="1n") {
    							$where.=$this->params["REFERENCE"][0][value].".".$this->fixedfields[$s[$i]]["LINK"][$x]["REFERENCEFIELD"][0][value]."=";
    							$where.=$this->fixedfields[$s[$i]]["TABLE"][0][value].".".$this->fixedfields[$s[$i]]["TABLEKEY"][0][value]." and ";
    						}
    					}
    				}
    			} 
    		} else {
    			$this->no_filter=1;
    		}    					
    	}	   	 	
    	
    	$u=explode(",",$this->sortablecolumns);
    	$t=array_diff($u,$s);
    	//parcours des champs 
    	foreach ($t as $dummykey) {
    		if ((substr($dummykey,0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    			//champs personnalisés
    			require_once($class_path."/parametres_perso.class.php");
    			$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    			if (!$cp->no_special_fields) {
    				$id=substr($dummykey,1,strlen($dummykey)-1);
    				$where.=$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values.".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_origine=".$this->params["REFERENCE"][0][value].".".$this->params["REFERENCEKEY"][0][value]." and ";
    			}
    		} elseif (array_key_exists($dummykey,$this->fixedfields)) {
    			//champs fixes
    			//est-ce que le champ est filtrable	
    			if ($this->fixedfields[$dummykey]["SORTABLE"]=="yes") {
    				if ($this->fixedfields[$dummykey]["TABLE"][0][value]) {	
    	 				for ($x=0;$x<count($this->fixedfields[$dummykey]["LINK"]);$x++) {
    	 					//de quelle type est la relation
    	 					if ($this->fixedfields[$dummykey]["LINK"][$x]["TYPE"]=="1n") {
    	 						$where.=$this->params["REFERENCE"][0][value].".".$this->fixedfields[$dummykey]["LINK"][$x]["REFERENCEFIELD"][0][value]."=";
    	 						$where.=$this->fixedfields[$dummykey]["TABLE"][0][value].".".$this->fixedfields[$dummykey]["TABLEKEY"][0][value]." and ";
    	 					}
    	 				}
    				}
    	 		} 
    	 	} else {
    			$this->no_filter=1;
    		}    					
    	}	   	 	
    	
    	if ($where) $where=substr($where,0,strlen($where)-4);
    	if ($where) $ret=" where ".$where;

    	$this->filtered_query = $ret;
    	return $ret;	
    }
    
    //fonction de manipulation de données pour la limitation des résultats
    function pager_query() {
    	$ret="";
    	if (($this->page)&&($this->nb_per_page)) {
    		$limit=($this->page-1)*$this->nb_per_page.",".$this->nb_per_page;		
    		$ret=" limit ".$limit;
    	}
    	return $ret;	
    }
    
    //fonction de création du lien de la page
    function display_pager() {
    	global $class_path;
    	global $msg;
    	
    	$ret="";
    	$nb_lignes=$this->nb_lines_query();
    	if ($nb_lignes!=0) {
    		$ret = "<div align='center'>";
    		$nbepages = ceil($nb_lignes/$this->nb_per_page);
			$suivante = $this->page+1;
			$precedente = $this->page-1;
			if ($nbepages!=1) {
				$ret .= "<a id='premiere' href='#' onClick='document.forms[\"form_filters\"].page.value=1;document.forms[\"form_filters\"].submit();'><img src='./images/first.gif' border='0' alt='".$msg['first_page']."' hspace='6' align='middle' title='".$msg['first_page']."' /></a>";
				$ret .= "<a id='precedente' href='#' onClick='document.forms[\"form_filters\"].page.value=".$precedente.";document.forms[\"form_filters\"].submit();'><img src='./images/left.gif' border='0' alt='".$msg[48]."' hspace='6' align='middle' title='".$msg[48]."' /></a>";
			}
			$deb = $this->page-10;
			if ($deb<1) $deb=1;
			$fin = $this->page+10;
			if($fin>$nbepages)$fin=$nbepages; 
			for ($i = $deb; ($i <= $nbepages) && ($i<=$fin) ; $i++) {
				if($i==$this->page) {
					$ret .= "<strong>".$i."</strong>";
				} else {
					$ret .= "<a href='#' onClick='document.forms[\"form_filters\"].page.value=".$i.";document.forms[\"form_filters\"].submit();'>".$i."</a>";
				}
				if ($i<$nbepages) $ret.=" ";
			}
			if ($suivante<=$nbepages) {
				$ret .= "<a href='#' onClick='document.forms[\"form_filters\"].page.value=".$suivante.";document.forms[\"form_filters\"].submit();'><img src='./images/right.gif' border='0' alt='".$msg[49]."' hspace='6' align='middle' title='".$msg[49]."' /></a>";
			}

			if(($this->page)<$nbepages)  {
				$ret .= "<a id='derniere' href='#' onClick='document.forms[\"form_filters\"].page.value=".$nbepages.";document.forms[\"form_filters\"].submit();'><img src='./images/last.gif' border='0' alt='".$msg['last_page']."' hspace='6' align='middle' title='".$msg['last_page']."' /></a>";
			}
			$ret.="</div>";
    	}
    	return $ret;	
    }
    
    //fonction de récupération du nombre de lignes de la requête
    function nb_lines_query() {
    	if (!$this->no_filter) {
    		if ($this->t_query) {
    			$compt=mysql_num_rows($this->t_query);
    			return $compt;	
    		}
    	} else {
    		if ($this->original_query) {
    			$resultat=mysql_query($this->original_query);
    			$compt=mysql_num_rows($resultat);
    			return $compt;	
    		}
    	}
    }
    
    //fonction de récupération d'une ligne de la requête
    function extract_line_query($n_line) {
    	if (!$this->no_filter) {
    		if ($this->t_query) {
    			$i=1;
    			while ($row=mysql_fetch_object($this->t_query)) {
    				if ($i==$n_line) {
    					return $row;
    					exit();	
    				}
    				$i++;	
    			}	
    		}
    	} else {
    		if ($this->original_query) {
    			$i=1;
    			$resultat=mysql_query($this->original_query);
    			while ($row=mysql_fetch_object($resultat)) {
    				if ($i==$n_line) {
    					return $row;
    					exit();	
    				}
    				$i++;	
    			}	
    		}
    	}	
    } 
    
    //fonction d'affichage des entêtes de colonnes de la requête
    function display_columns() {
    	global $class_path;
    	global $msg;
    	global $charset;
    	$aff="";
    	
    	$s=explode(",",$this->displaycolumns);
    	//parcours des champs 
    	for ($i=0;$i<count($s);$i++) {
    		//détermination d'un champ personnalisé
    		if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    			//champs personnalisés
    			require_once($class_path."/parametres_perso.class.php");
    			$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    			if (!$cp->no_special_fields) {
    				$id=substr($s[$i],1);
    				$aff.="<th>".htmlentities($cp->t_fields[$id][TITRE],ENT_QUOTES,$charset)."</th>";
    			}
    		} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    			//champs fixes
    			//est-ce que le champ est affichable
    			if ($this->fixedfields[$s[$i]]["DISPLAYABLE"]=="yes") {
    				if (substr($this->fixedfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->fixedfields[$s[$i]]["NAME"],4,strlen($this->fixedfields[$s[$i]]["NAME"])-4)];
						else $nom=$this->fixedfields[$s[$i]]["NAME"];
					$aff.="<th>".htmlentities($nom,ENT_QUOTES,$charset)."</th>";
    			}
    		} else {
    			if (substr($this->specialfields[$s[$i]]["NAME"],0,4)=="msg:") {
    				$nom=$msg[substr($this->specialfields[$s[$i]]["NAME"],4,strlen($this->specialfields[$s[$i]]["NAME"])-4)];	
    			} else {
    				$nom=$this->specialfields[$s[$i]]["NAME"];
    			}
    			$aff.="<th>".htmlentities($nom,ENT_QUOTES,$charset)."</th>";
    		}
    	}
    	return $aff;    				
    }
    
    //fonction d'affichage du résultat
    function display_result() {
    	global $class_path;
    	$aff="";
    	if ($this->query) {
    		$execute_query=mysql_query($this->query);
    		$aff.="<table class='".$this->css["table"]["class"]."' style='".$this->css["table"]["style"]."'>";
    		$parity = 0;
    		while (($result=mysql_fetch_array($execute_query))) {
    			$onmouseout=$this->scripts["row"]["onmouseout"];
    			$onmouseover=$this->scripts["row"]["onmouseover"];
    			$onmousedown=$this->scripts["row"]["onmousedown"];
    			if ($parity % 2) {
    				$pair_impair = $this->css["row_even"]["class"];
    				$pair_impair_style = $this->css["row_even"]["style"];
    				$onmouseout=str_replace('!!parity!!',$this->css["row_even"]["class"],$onmouseout);
    				$onmouseover=str_replace('!!parity!!',$this->css["row_even"]["class"],$onmouseover);
    				$onmouseout=str_replace('!!parity!!',$this->css["row_even"]["class"],$onmouseout);
    			} else { 
    				$pair_impair = $this->css["row_odd"]["class"];
    				$pair_impair_style = $this->css["row_odd"]["style"];
    				$onmouseout=str_replace('!!parity!!',$this->css["row_odd"]["class"],$onmouseout);
    				$onmouseover=str_replace('!!parity!!',$this->css["row_odd"]["class"],$onmouseover);
    				$onmousedown=str_replace('!!parity!!',$this->css["row_odd"]["class"],$onmousedown);
    			}
    			$ligne="";
    			
    			$s=explode(",",$this->displaycolumns);
    	    	//parcours des champs 
    			for ($i=0;$i<count($s);$i++) {
    				//détermination de la valeur
    				if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    					//champs perso
    					require_once($class_path."/parametres_perso.class.php");
    					$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
   						$id=substr($s[$i],1);
    					$cp->get_values($result[$this->params["REFERENCEKEY"][0]["value"]]);
    					if (!$cp->no_special_fields) {
//    						$temp=$result[$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id]["DATATYPE"].$id];
    						$onmouseout=str_replace("!!".$s[$i]."!!",rawurlencode($temp),$onmouseout);
    						$onmouseover=str_replace("!!".$s[$i]."!!",rawurlencode($temp),$onmouseover);
    						$onmousedown=str_replace("!!".$s[$i]."!!",rawurlencode($temp),$onmousedown);
    						$temp=$cp->get_formatted_output($cp->values[$id], $id);
    						if (!$temp) $temp="&nbsp;";
    						$ligne.="<td class='".$this->css["cols"][$i]["class"]."' style='".$this->css["cols"][$i]["style"]."'>".$temp."</td>";
    					}
    				} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    					//champs fixes
    					$f=array();
    					if ($this->fixedfields[$s[$i]]["LINK"]) {
    						for ($x=0;$x<count($this->fixedfields[$s[$i]]["LINK"]);$x++) {
    							if ($this->fixedfields[$s[$i]]["LINK"][$x]["TYPE"]=="nn") {
    								$f[0]=$this->fixedfields[$s[$i]]["TABLEFIELD"][0]["NAME"];
    							} else {
    								$f[0]=$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value];
    							}
    						}
    					} else {
    						$f=explode(",",$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value]);
    					}
    					$b=0;
    					while ($b<count($f)) {
    						$temp=$result[$f[$b]];
    						if ($temp == '') $temp="&nbsp;";	
    						$ligne.="<td class='".$this->css["cols"][$i]["class"]."' style='".$this->css["cols"][$i]["style"]."'>".$temp."</td>";
    						$b++; 
    					} 
    					$onmouseout=str_replace("!!".$s[$i]."!!",rawurlencode($temp),$onmouseout);
    					$onmouseover=str_replace("!!".$s[$i]."!!",rawurlencode($temp),$onmouseover);
    					$onmousedown=str_replace("!!".$s[$i]."!!",rawurlencode($temp),$onmousedown);  							
    				} else {
    					$name_function=$this->specialfields[$s[$i]]["FUNCTION"];
						$r="";
						$key=$result[$this->params["REFERENCEKEY"][0][value]];
						eval("\$r=".$name_function."(\$key);");
						$ligne.=$r;
    				}
    			}	
    			$onmouseout=str_replace("!!".$this->params["REFERENCEKEY"][0][value]."!!",$result[$this->params["REFERENCEKEY"][0][value]],$onmouseout);
    			$onmouseover=str_replace("!!".$this->params["REFERENCEKEY"][0][value]."!!",$result[$this->params["REFERENCEKEY"][0][value]],$onmouseover);
    			$onmousedown=str_replace("!!".$this->params["REFERENCEKEY"][0][value]."!!",$result[$this->params["REFERENCEKEY"][0][value]],$onmousedown);
    			$aff.="<tr class='".$pair_impair."' style='$pair_impair_style' onmouseover=\"this.className='surbrillance';".$onmouseover."\" onmouseout=\"".$onmouseout."\" onmousedown='".$onmousedown."'>";					
				$aff.=$ligne;
				$aff.="</tr>";
				$parity += 1;
    		}
    		$aff.="</table>";		
    	}
    	return $aff;	
    }
                
    //fonction d'affichage des filtres applicables sur la liste des emprunteurs
    function display_filters() {
    	global $msg;
    	global $charset;
    	global $class_path;
    	global $aff_filter_list_empr;
    	
    	$aff="";
    	$s=explode(",",$this->filtercolumns);
    		
    	//parcours des champs 
    	for ($i=0;$i<count($s);$i++) {
    		//détermination d'un champ personnalisé
    		if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    			//champs personnalisés
    			require_once($class_path."/parametres_perso.class.php");
    			$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    			if (!$cp->no_special_fields) {
    				$id=substr($s[$i],1,strlen($s[$i])-1);
    				$valeurs_post="f".$cp->t_fields[$id]["NAME"];
    				$v=array();
    				global $$valeurs_post;
    				if ($$valeurs_post) $v=$$valeurs_post;
    				$aff.="<div class='left'><div style='vertical-align: middle'>".htmlentities($cp->t_fields[$id][TITRE],ENT_QUOTES,$charset)."</div>&nbsp;&nbsp;";
					//Récupération du champ
    				$field=array();
					$field[ID]=$id;
					$field[NAME]=$cp->t_fields[$id][NAME];
					$field[MANDATORY]=$cp->t_fields[$id][MANDATORY];
					$field[ALIAS]=$cp->t_fields[$id][TITRE];
					$field[DATATYPE]=$cp->t_fields[$id][DATATYPE];
					$field[OPTIONS][0]=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$cp->t_fields[$id][OPTIONS], "OPTIONS");
					if (($cp->t_fields[$id][TYPE]!="list")&&($cp->t_fields[$id][TYPE]!="query_list")) {
						$field[OPTIONS][0][UNSELECT_ITEM][0][VALUE]="-1";
						$field[OPTIONS][0][UNSELECT_ITEM][0][value]=$msg["empr_perso_all_values"];
						$field[OPTIONS][0][QUERY][0][value]="select ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id][DATATYPE]." from ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_values where ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_champ=".$id." group by ".$this->params["REFERENCE"][0]["PREFIXNAME"]."_custom_".$cp->t_fields[$id][DATATYPE];
						if ((!$v)||($v[0]=="")||($v[0]=="-1")) {
							$field[OPTIONS][0][DEFAULT_VALUE][0][value]="-1";
						}
					} 
					$field[VALUES]=$v;
					$field[PREFIX]=$this->params["REFERENCE"][0]["PREFIXNAME"];
					$name="f".$cp->t_fields[$id][NAME];
					$multiple=$this->multiple;
					$r="";
					eval("\$r=".$aff_filter_list_empr[$cp->t_fields[$id][TYPE]]."(\$field,\$name,\$multiple);");
					$aff.=$r."</div>";
    			}	
    		} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    			//champs fixes
    			//est-ce que le champ est filtrable
    			if ($this->fixedfields[$s[$i]]["FILTERABLE"]=="yes") {
    				if (substr($this->fixedfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->fixedfields[$s[$i]]["NAME"],4,strlen($this->fixedfields[$s[$i]]["NAME"])-4)];
    					else $nom=$this->fixedfields[$s[$i]]["NAME"];
    				
    				$aff.="<div class='left'><div style='vertical-align: middle'>".htmlentities($nom,ENT_QUOTES,$charset)."</div>&nbsp;&nbsp;";
    				$requete="select ";
    				//détermination d'une table étrangère
    				if ($this->fixedfields[$s[$i]]["TABLE"][0][value]) {
    					$requete.=$this->fixedfields[$s[$i]]["TABLEKEY"][0][value].",";
    					$from=$this->fixedfields[$s[$i]]["TABLE"][0][value];
    					$where="";
    				} else {
    					$from=$this->params["REFERENCE"][0][value];
    					$where=" where ".$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value]." is not null and ".$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value]."<>''";	
    					$where.=" group by ".$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value];
    				}			
    				$requete.=$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value];
    				$requete.=" from ".$from."".$where." order by ".$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value];
    				$execute_query=mysql_query($requete);
    				if ($execute_query) {
    					$valeurs_post="f".$this->fixedfields[$s[$i]]["ID"];
    					global $$valeurs_post;
    					if ($this->multiple) $multiple="size=5 multiple";
    					$aff.="<select name='f".$this->fixedfields[$s[$i]]["ID"]."[]' $multiple>\n";
    					$aff.="<option value='-1'";
    					if (!$$valeurs_post) $$valeurs_post=-1;
    					if (is_array($$valeurs_post)) {
    						$as=array_search("-1",$$valeurs_post);
							if (($as!==FALSE)&&($as!==NULL)) $aff.=" selected";
    					} else {
    						if ($$valeurs_post==-1) $aff.=" selected";	
    					}
    					if (substr($this->fixedfields[$s[$i]]["DEFAULTVALUE"],0,4)=="msg:") $nom=$msg[substr($this->fixedfields[$s[$i]]["DEFAULTVALUE"],4,strlen($this->fixedfields[$s[$i]]["DEFAULTVALUE"])-4)];
    						else $nom=$this->fixedfields[$s[$i]]["DEFAULTVALUE"];
    					$aff.=">".htmlentities($nom,ENT_QUOTES,$charset)."</option>\n"; 	
    					
    					while($resultat=mysql_fetch_array($execute_query)) {
    						if ($this->fixedfields[$s[$i]]["TABLE"][0][value]) {
    							$valeur_temp=$resultat[$this->fixedfields[$s[$i]]["TABLEKEY"][0][value]];
    						} else {
    							$valeur_temp=htmlentities($resultat[$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value]],ENT_QUOTES,$charset);
    						}
    						$aff.="<option value='".$valeur_temp."'";
    						if (is_array($$valeurs_post)) {
    							$as=array_search($valeur_temp,$$valeurs_post);
    							if (($as!==FALSE)&&($as!==NULL)) $aff.=" selected";	
    						} else {
    							if ($valeur_temp==$$valeurs_post) $aff.=" selected";	
    						}
    						$aff.=">";
    						if ($this->fixedfields[$s[$i]]["TYPE"]=="date") $valeur=formatdate($resultat[$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value]]);
    							else $valeur=$resultat[$this->fixedfields[$s[$i]]["TABLEFIELD"][0][value]];
    						$aff.=htmlentities($valeur,ENT_QUOTES,$charset)."</option>\n";	
    					}
    					$aff.="</select></div>\n";
    				}
    			}
    		} else {
    			$name_function=$this->specialfields[$s[$i]]["FUNCTION"];
				$r="";
				$t=array();
				if (substr($this->specialfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->specialfields[$s[$i]]["NAME"],4,strlen($this->specialfields[$s[$i]]["NAME"])-4)];
					else $nom=$this->specialfields[$s[$i]]["NAME"];
				$aff.="<div class='left'><div style='vertical-align: middle'>".htmlentities($nom,ENT_QUOTES,$charset)."</div>&nbsp;&nbsp;";
				if ($this->multiple) $multiple="size=5 multiple";
				$aff.="<select name='f".$this->specialfields[$s[$i]]["ID"]."[]' $multiple>\n";
    			$aff.="<option value='-1'";
    			$valeurs_post="f".$this->specialfields[$s[$i]]["ID"];
    			global $$valeurs_post;
    			$v=array();
    			$v=$$valeurs_post;
    			if ((!$$valeurs_post)&&(!is_array($$valeurs_post))) $aff.=" selected";
    			else {	
    				if (is_array($$valeurs_post)) {
    					$as=array_search("-1",$$valeurs_post);
						if (($as!==FALSE)&&($as!==NULL)) $aff.=" selected";
    				} else $aff.=" selected";
				}
				if (substr($this->specialfields[$s[$i]]["DEFAULTVALUE"],0,4)=="msg:") $nom=$msg[substr($this->specialfields[$s[$i]]["DEFAULTVALUE"],4,strlen($this->specialfields[$s[$i]]["DEFAULTVALUE"])-4)];
					else $nom=$this->specialfields[$s[$i]]["DEFAULTVALUE"];
    			$aff.=">".htmlentities($nom,ENT_QUOTES,$charset)."</option>\n"; 	
				$param=$this->specialfields[$s[$i]]["PARAM"][0][value];
				$execute_query=mysql_query("select ".$this->params["REFERENCEKEY"][0][value]." from ".$this->params["REFERENCE"][0][value]);
				while ($row=mysql_fetch_array($execute_query)) {
					$t[]=$row[$this->params["REFERENCEKEY"][0][value]];
				}
				mysql_free_result($execute_query);
				eval("\$r=".$name_function."(\$t,\$param,\$v);");
				$aff.=$r;
				$aff.="</select></div>\n";
    		}	
    	}
    	return $aff;
    }
    
    //fonction d'affichage des tris applicables sur la liste des emprunteurs
    function display_sort() {
    	global $msg;
    	global $charset;
    	global $class_path;
    	    	    	
    	$aff=$msg["sort_label"]."&nbsp;&nbsp;";
    	$s=explode(",",$this->sortablecolumns);
    	//parcours des champs 
    	for ($j=0;$j<=count($s)-2;$j++) {
    		$liste="&nbsp;&nbsp;<select name='sort_list_".$j."'><option value='-1'";
    		$sort_list="sort_list_".$j;
    		global $$sort_list;
    		if (!$$sort_list) $$sort_list=$s[$j];
    		if ($$sort_list==-1) $liste.=" selected";
    		$liste.=">".$msg["tri_inactif"]."</option>";
    		for ($i=0;$i<count($s);$i++) {
    			//détermination d'un champ personnalisé
    			if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    				//champs personnalisés
    				require_once($class_path."/parametres_perso.class.php");
    				$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    				if (!$cp->no_special_fields) {
    					$id=substr($s[$i],1,strlen($s[$i])-1);
    					$liste.="<option value='".$s[$i]."'";
						if ($$sort_list) {
							if ($$sort_list==$s[$i]) $liste.=" selected";	
						} else { 
							$liste.=" selected";
						}
						$liste.=">".htmlentities($cp->t_fields[$id][TITRE],ENT_QUOTES,$charset)."</option>\n";
    				}	
    			} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    				//champs fixes
    				//est-ce que le champ est triable
    				if ($this->fixedfields[$s[$i]]["SORTABLE"]=="yes") {
    					$liste.="<option value='".$s[$i]."'";
    					if ($$sort_list) {
							if ($$sort_list==$s[$i]) $liste.=" selected";	
						} else { 
							$liste.=" selected";
						}
						if (substr($this->fixedfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->fixedfields[$s[$i]]["NAME"],4,strlen($this->fixedfields[$s[$i]]["NAME"])-4)];
    						else $nom=$this->fixedfields[$s[$i]]["NAME"];
    					$liste.=">".htmlentities($nom,ENT_QUOTES,$charset)."</option>\n";		
    				}
    			} else {
    				$liste.="<option value='".$s[$i]."'";
    				if ($$sort_list) {
						if ($$sort_list==$s[$i]) $liste.=" selected";	
					} else { 
						$liste.=" selected";
					}
					if (substr($this->fixedfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->fixedfields[$s[$i]]["NAME"],4,strlen($this->fixedfields[$s[$i]]["NAME"])-4)];
    						else $nom=$this->fixedfields[$s[$i]]["NAME"];
    				$liste.=">".htmlentities($nom,ENT_QUOTES,$charset)."</option>\n";			
    			}
    		}
    		$liste.="</select>&nbsp;&nbsp;".$msg["filters_sort_next"]."&nbsp;&nbsp;";	
    		$aff.=$liste;
    	}
    	$liste="<select name='sort_list_".$j."'><option value='-1'";
    	$sort_list="sort_list_".$j;
    	global $$sort_list;
    	if (!$$sort_list) $$sort_list=$s[$j];
    	if ($$sort_list==-1) $liste.=" selected";
    	$liste.=">".$msg["tri_inactif"]."</option>";
    	for ($i=0;$i<=count($s)-1;$i++) {
    		//détermination d'un champ personnalisé
    		if ((substr($s[$i],0,1)=="#")&&($this->params["REFERENCE"][0]["DYNAMICFIELDS"]=="yes")) {
    			//champs personnalisés
    			require_once($class_path."/parametres_perso.class.php");
    			$cp=new parametres_perso($this->params["REFERENCE"][0]["PREFIXNAME"]);
    			if (!$cp->no_special_fields) {
    				$id=substr($s[$i],1,strlen($s[$i])-1);
    				$liste.="<option value='".$s[$i]."'";
					if ($$sort_list) {
						if ($$sort_list==$s[$i]) $liste.=" selected";	
					} else $liste.=" selected";	
					$liste.=">".htmlentities($cp->t_fields[$id][TITRE],ENT_QUOTES,$charset)."</option>\n";
    			}	
    		} elseif (array_key_exists($s[$i],$this->fixedfields)) {
    			//champs fixes
    			//est-ce que le champ est triable
    			if ($this->fixedfields[$s[$i]]["SORTABLE"]=="yes") {
    				$liste.="<option value='".$s[$i]."'";
					if ($$sort_list) {
						if ($$sort_list==$s[$i]) $liste.=" selected";	
					} else $liste.=" selected";
					if (substr($this->fixedfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->fixedfields[$s[$i]]["NAME"],4,strlen($this->fixedfields[$s[$i]]["NAME"])-4)];
    					else $nom=$this->fixedfields[$s[$i]]["NAME"];	
    				$liste.=">".htmlentities($nom,ENT_QUOTES,$charset)."</option>\n";		
    			}
    		} else {
    			$liste.="<option value='".$s[$i]."'";
    			if ($$sort_list) {
					if ($$sort_list==$s[$i]) $liste.=" selected";	
				} else { 
					$liste.=" selected";
				}
				if (substr($this->specialfields[$s[$i]]["NAME"],0,4)=="msg:") $nom=$msg[substr($this->specialfields[$s[$i]]["NAME"],4,strlen($this->fixedfields[$s[$i]]["NAME"])-4)];
    				else $nom=$this->specialfields[$s[$i]]["NAME"];
    			$liste.=">".htmlentities($nom,ENT_QUOTES,$charset)."</option>\n";			
    		}
    	}
    	$liste.="</select>\n";	
    	$aff.=$liste;
    	
    	return $aff;
    }
    
    //fonction de génération du xml par rapport à une requête
    function gen_xml($requete,$champ_pivot) {
    	global $msg;
    	$execute_query=mysql_query($requete);
    	
    	$ret="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>";
    	$ret.="<fields>";
    	
    	$table = mysql_field_table($execute_query, $champ_pivot);
    	
    	$header="<reference dynamicfields=\"\" prefixname=\"\">".$table."</reference>";
    	$header.="<referencekey>".$champ_pivot."</referencekey>";
    	
    	$i=0;
    	while ($i < mysql_num_fields($execute_query)) {
    		$meta = mysql_fetch_field($execute_query);
    		if ($meta) {
    			$fields.="<field name=\"".$meta->name."\" type=\"".$meta->type."\" value=\"\" id=\"".$i."\" filterable=\"yes\" sortable=\"yes\" displayable=\"yes\" defaultvalue=\"\">";	
    			$fields.="<tablefield>".$meta->name."</tablefield>";
    		} else {
    			$fields.="<field>";
    			$this->error=true;
    			$this->error_message=str_replace('%s',$i,$msg["erreur_detail_champ"]);
    			$ret.=str_replace('%s',$i,$msg["erreur_detail_champ"]);
    		}
    		$fields.="</field>";	
    	}
    	$fields.="<specialfields></specialfields>";
    	$ret.=$header.$fields."</fields>";
    	$ret=htmlspecialchars($ret);
    	return $ret; 	
    }
    
    function parse() {
    	global $include_path;
    	    	
    	if (!$this->filter_name) {
    		$xml=$this->filter_source;
    		$params=_parser_text_no_function_($xml,"FIELDS");
    		$this->params=$params;
    	} else {
    		if (file_exists($include_path."/filters_list/".$this->filter_name."/".$this->filter_source."_subst.xml")) {
    			$fp=fopen($include_path."/filters_list/".$this->filter_name."/".$this->filter_source."_subst.xml","r");	
    		} else {
    			$fp=fopen($include_path."/filters_list/".$this->filter_name."/".$this->filter_source.".xml","r");
    		}
    		if ($fp) {
    			$xml=fread($fp,filesize($include_path."/filters_list/".$this->filter_name."/".$this->filter_source.".xml"));
    			fclose($fp);
    			$params=_parser_text_no_function_($xml,"FIELDS");
    			$this->params=$params;
    		} else {
    			$this->error=true;
    			$this->error_message="Can't open definition file";
    		}
    	}
    	//lecture des champs fixes
    	for ($i=0;$i<count($params["FIXEDFIELDS"][0]["FIELD"]);$i++) {
    		$this->fixedfields[$params["FIXEDFIELDS"][0]["FIELD"][$i]["VALUE"]]=$params["FIXEDFIELDS"][0]["FIELD"][$i];
    	}
    	//lecture des champs spéciaux
    	for ($i=0;$i<count($params["SPECIALFIELDS"][0]["FIELD"]);$i++) {
    		$this->specialfields[$params["SPECIALFIELDS"][0]["FIELD"][$i]["ID"]]=$params["SPECIALFIELDS"][0]["FIELD"][$i];
    	}
    }
}
?>