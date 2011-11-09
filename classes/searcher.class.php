<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: searcher.class.php,v 1.106 2011-03-29 13:52:31 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Classe de recherche en catalogage...

require_once("$class_path/analyse_query.class.php");
require_once("$class_path/thesaurus.class.php");
require_once("$class_path/sort.class.php");
require_once("$include_path/templates/searcher_templates.tpl.php");


//droits d'acces lecture notice
$acces_j='';
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_j = $dom_1->getJoin($PMBuserid,4,'notice_id');
} 


//Classe générique de recherche

define("AUT_LIST",1);
define("NOTICE_LIST",2);
define("AUT_SEARCH",3);

class searcher {

	var $type;                    //Type de recherche
	var $etat;                    //Etat de la recherche
	var $page;                    //Page courante de la recherche
	var $nbresults;               //Nombre de résultats de la dernière recherche
	var $nbepage;
	var $nb_per_page;
	var $id;                    	//Numéro d'autorité pour la recherche
	var $store_form;            	//Formulaire contenant les infos de navigation plus des champs pour la recherche
	var $base_url;
	var $first_search_result;
	var $text_query;
	var $text_query_tri; 			//pour les tris texte de la requête d'origine modifié par la classe tri
	var $human_query;
	var $human_notice_query;
	var $human_aut_query;
	var $docnum;
	var $direct=0;
	var $rec_history=false;
	var $sort;
	
	//Constructeur
	function searcher($base_url,$rec_history=false) {
		global $type,$etat,$aut_id,$page, $docnum_query,$auto_postage_query;

		$this->sort = new sort('notices','base');
		$this->type=$type;
		$this->etat=$etat;
		$this->page=$page;
		$this->id=$aut_id;
		$this->base_url=$base_url;
		$this->rec_history=$rec_history;
		$this->docnum = ($docnum_query?1:0);
		$this->auto_postage_query = ($auto_postage_query?1:0);
		$this->run();
	}

	function make_store_form() {
		$this->store_form="<form name='store_search' action='".$this->base_url."' method='post' style='display:none'>
		<input type='hidden' name='type' value='".$this->type."'/>
		<input type='hidden' name='etat' value='".$this->etat."'/>
		<input type='hidden' name='page' value='".$this->page."'/>";
		$this->store_form.="!!first_search_variables!!";
		$this->store_form.="</form>";
	}

	function pager() {
		global $msg;

		if (!$this->nbresults) return;
		
		$suivante = $this->page+1;
		$precedente = $this->page-1;
		if (!$this->page) $page_en_cours=0 ;
			else $page_en_cours=$this->page ;
				
		// affichage du lien précédent si nécéssaire
		if($precedente >= 0)
				$nav_bar .= "<a href='#' onClick=\"document.store_search.page.value=$precedente; document.store_search.submit(); return false;\"><img src='./images/left.gif' border='0'  title='$msg[48]' alt='[$msg[48]]' hspace='3' align='middle'></a>";

		$deb = $page_en_cours - 10 ;
		if ($deb<0) $deb=0;
		for($i = $deb; ($i < $this->nbepage) && ($i<$page_en_cours+10); $i++) {
			if($i==$page_en_cours) $nav_bar .= "<strong>".($i+1)."</strong>";
				else $nav_bar .= "<a href='#' onClick=\"document.store_search.page.value=$i; document.store_search.submit(); return false;\">".($i+1)."</a>";
			if($i<$this->nbepage) $nav_bar .= " "; 
			}
        
		if($suivante<$this->nbepage)
				$nav_bar .= "<a href='#' onClick=\"document.store_search.page.value=$suivante; document.store_search.submit(); return false;\"><img src='./images/right.gif' border='0' title='$msg[49]' alt='[$msg[49]]' hspace='3' align='middle'></a>";

		// affichage de la barre de navigation
		print "<div align='center'>$nav_bar</div>";
	}

	function show_notice() {
	}
	
	
	function run() {
		if (!$this->etat) {
				$this->show_form();
		} else {
				switch ($this->etat) {
					case "first_search":
						$r=$this->make_first_search();
						//echo "req first:".$this->text_query."<br />";
						$this->first_search_result=$r;
						switch ($r) {
								case AUT_LIST:
									$this->make_store_form();
									$this->store_search();
									$this->aut_list();
									$this->pager();
									break;
								case NOTICE_LIST:
									$this->make_store_form();
									$this->store_search();
									$this->sort_notices();
									$this->notice_list();
									$this->pager();
									break;
								case AUT_SEARCH:
									$this->etat="aut_search";
									$this->direct=1;
									$this->make_aut_search();
									$this->make_store_form();
									$this->aut_store_search();
									$this->sort_notices();
									$this->aut_notice_list();
									$this->pager();
									break;
						}
						if ($this->rec_history)
								$this->rec_env();
						break;
					case "aut_search":
						$this->make_aut_search();
						$this->make_store_form();
						$this->aut_store_search();
						$this->sort_notices();
						$this->aut_notice_list();
						$this->pager();
						if ($this->rec_history)
								$this->rec_env();
						break;
				}
		}
	}
	

	function show_form() {
		//A surcharger par la fonction qui affiche le formulaire de recherche
	}

	function make_first_search() {
		//A surcharger par la fonction qui fait la première recherche après la soumission du formulaire de recherche
		//La fonction renvoie AUT_LIST (le résultat de la recherche est une liste d'autorité)
		//ou NOTICE_LIST (le résultat de la recherche est une liste de notices)
		//La fonction doit mettre à jour le nombre de résultats dans $this->nbresults
	}

	function make_aut_search() {
		//A surcharger par la fonction qui fait la recherche des notices à partir d'un numéro d'autorité (stoqué dans $this->id)
		//La fonction doit mettre à jour le nombre de résultats dans $this->nbresults
	}

	function store_search() {
		//A surcharger par la fonction qui écrit les variables du formulaire "store_search" pour stoquer les champs de recherche
		//En liste de résultat de la première recherche. Il faut remplacer la chaine "!!first_search_variables!!" dans $this->store_form
	}

	function aut_store_search() {
		//A surcharger par la fonction qui écrit les variables du formulaire "store_search" pour stoquer les champs de recherche
		//En liste de résultat de la première recherche. Il faut remplacer la chaine "!!first_search_variables!!" dans $this->store_form
	}

	function aut_list() {
		//A surcharger par la fonction qui affiche la liste des autorités issues de la première recherche
	}

	function notice_list() {
		//A surcharger par la fonction qui affiche la liste des notices issues de la première recherche
	}

	function aut_notice_list() {
		//A surcharger par la fonction qui affiche la liste des notice sous l'autorité $this->id
	}

	function rec_env() {
		//A surcharger pa la fonction qui enregistre
	}

	function convert_simple_multi() {
		//A surcharger par la fonction qui convertit des recherches simples en multi-critères
	}

	function sort_notices() {
		global $msg;
		global $pmb_nb_max_tri;
		
		if ($this->nbresults<=$pmb_nb_max_tri) {
			if ($_SESSION["tri"]) {
				//$this->text_query_tri = $this->text_query;
				//$this->text_query_tri = str_replace("limit ".$this->page*$this->nb_per_page.",".$this->nb_per_page, "limit 0,".$this->nbresults,$this->text_query_tri);
				
				//if ($this->nb_per_page) {
					//$this->sort->limit = "limit ".$this->page*$this->nb_per_page.",".$this->nb_per_page;
				//}
				//$this->text_query_tri = $this->sort->appliquer_tri($_SESSION["tri"],$this->text_query,"notice_id");
				if ($this->nb_per_page) {
					$this->text_query_tri = $this->sort->appliquer_tri($_SESSION["tri"],$this->text_query,"notice_id", $this->page*$this->nb_per_page, $this->nb_per_page);
					//$this->text_query_tri .= " LIMIT ".$this->page*$this->nb_per_page.",".$this->nb_per_page;
				} else {
					$this->text_query_tri = $this->sort->appliquer_tri($_SESSION["tri"],$this->text_query,"notice_id",0,0);
					
				}
				//echo ($this->text_query_tri."<br />");
				$this->t_query = @mysql_query($this->text_query_tri);
			
				if (!$this->t_query) {
					print mysql_error();	
				}
			} else {
				if (strpos($this->text_query,"limit")===false) {
					if ($this->nb_per_page) {
						$this->text_query .= "limit ".$this->page*$this->nb_per_page.",".$this->nb_per_page;
					}
				} else {
					if ($this->nb_per_page) {
						$this->text_query = str_replace("limit 0,".$this->nbresults,"limit ".$this->page*$this->nb_per_page.",".$this->nb_per_page,$this->text_query);
					}
				}	
				$this->t_query=@mysql_query($this->text_query);	
			}
		} else {
			if (strpos($this->text_query,"limit")===false) {
				if ($this->nb_per_page) {
					$this->text_query .= "limit ".$this->page*$this->nb_per_page.",".$this->nb_per_page;
				}
			} else {
				if ($this->nb_per_page) {
					$this->text_query = str_replace("limit 0,".$this->nbresults,"limit ".$this->page*$this->nb_per_page.",".$this->nb_per_page,$this->text_query);
				}
			}
			$this->t_query = @mysql_query($this->text_query);	
		}
	}
}


class searcher_title extends searcher {
	var $t_query;

	function show_form() {
		global $msg;
		global $dbh;
		global $charset,$lang;
		global $NOTICE_author_query;
		global $title_query,$all_query, $author_query,$ex_query,$typdoc_query, $statut_query, $docnum_query, $pmb_indexation_docnum_allfields, $pmb_indexation_docnum;
		global $categ_query,$thesaurus_auto_postage_search,$auto_postage_query;
		// on commence par créer le champ de sélection de document
		// récupération des types de documents utilisés.
		$query = "SELECT count(typdoc), typdoc ";
		$query .= "FROM notices where typdoc!='' GROUP BY typdoc";
		$result = @mysql_query($query, $dbh);
		$toprint_typdocfield .= "  <option value=''>$msg[tous_types_docs]</option>\n";
		$doctype = new marc_list('doctype');
		while ($obj = @mysql_fetch_row($result)) {
				$toprint_typdocfield .= "  <option value='$obj[1]'";
				if ($typdoc_query==$obj[1]) $toprint_typdocfield.=" selected";
				$toprint_typdocfield .=">".htmlentities($doctype->table[$obj[1]]." ($obj[0])",ENT_QUOTES, $charset)."</OPTION>\n";
		}

		// récupération des statuts de documents utilisés.
		$query = "SELECT count(statut), id_notice_statut, gestion_libelle ";
		$query .= "FROM notices, notice_statut where id_notice_statut=statut GROUP BY id_notice_statut, gestion_libelle order by gestion_libelle";
		$result = mysql_query($query, $dbh);
		$toprint_statutfield .= "  <option value=''>$msg[tous_statuts_notice]</option>\n";
		while ($obj = @mysql_fetch_row($result)) {
				$toprint_statutfield .= "  <option value='$obj[1]'";
				if ($statut_query==$obj[1]) $toprint_statutfield.=" selected";
				$toprint_statutfield .=">".htmlentities($obj[2]."  (".$obj[0].")",ENT_QUOTES, $charset)."</OPTION>\n";
		}

		$NOTICE_author_query = str_replace("!!typdocfield!!", $toprint_typdocfield, $NOTICE_author_query);
		$NOTICE_author_query = str_replace("!!statutfield!!", $toprint_statutfield, $NOTICE_author_query);
		$NOTICE_author_query = str_replace("!!title_query!!",  htmlentities(stripslashes($title_query ),ENT_QUOTES, $charset),  $NOTICE_author_query);
		$NOTICE_author_query = str_replace("!!all_query!!", htmlentities(stripslashes($all_query),ENT_QUOTES, $charset),  $NOTICE_author_query);
		$NOTICE_author_query = str_replace("!!author_query!!", htmlentities(stripslashes($author_query),ENT_QUOTES, $charset),  $NOTICE_author_query);
		$NOTICE_author_query = str_replace("!!categ_query!!", htmlentities(stripslashes($categ_query),ENT_QUOTES, $charset),  $NOTICE_author_query);
		
		$checkbox="";
		if($thesaurus_auto_postage_search){			
			$checkbox = "
			<div class='colonne'>
				<div class='row'>
					<input type='checkbox' !!auto_postage_checked!! id='auto_postage_query' name='auto_postage_query'/><label for='auto_postage_query'>".$msg["search_autopostage_check"]."</label>
				</div>
			</div>";
			$checkbox = str_replace("!!auto_postage_checked!!",   (($auto_postage_query) ? 'checked' : ''),  $checkbox);			
		} 
		$NOTICE_author_query = str_replace("!!auto_postage!!",   $checkbox,  $NOTICE_author_query);	
		
		$NOTICE_author_query = str_replace("!!ex_query!!",     htmlentities(stripslashes($ex_query    ),ENT_QUOTES, $charset),  $NOTICE_author_query);
		if($pmb_indexation_docnum){			
			$checkbox = "<div class='colonne'>
				<div class='row'>
				  <input type='checkbox' !!docnum_query_checked!! id='docnum_query' name='docnum_query'/><label for='docnum_query'>$msg[docnum_indexation]</label>
				</div>
			</div>";
			$checkbox = str_replace("!!docnum_query_checked!!",   (($pmb_indexation_docnum_allfields || $docnum_query) ? 'checked' : ''),  $checkbox);
			$NOTICE_author_query = str_replace("!!docnum_query!!",   $checkbox,  $NOTICE_author_query);
		} else $NOTICE_author_query = str_replace("!!docnum_query!!", '' ,  $NOTICE_author_query);
		$NOTICE_author_query = str_replace("!!base_url!!",     $this->base_url,$NOTICE_author_query);
		print pmb_bidi($NOTICE_author_query);
	}

	function make_first_search() {
		
		global $msg,$charset,$lang,$dbh;
		global $title_query,$all_query, $author_query,$ex_query,$typdoc_query, $statut_query, $etat, $docnum_query;		
		global $categ_query,$thesaurus_auto_postage_search, $auto_postage_query;
		global $nb_per_page_a_search;
		global $class_path;
		global $pmb_default_operator;
		global $acces_j;
		

		if ($nb_per_page_a_search) $this->nb_per_page=$nb_per_page_a_search; else $this->nb_per_page=3;
		$author_per_page=10;
		
		$restrict='';
		if ($typdoc_query) $restrict = "and typdoc='".$typdoc_query."' ";
		if ($statut_query) $restrict.= "and statut='".$statut_query."' ";
			
		if (!$author_query && !$all_query && !$categ_query) {
			
				// Recherche sur le titre uniquement :
				$aq=new analyse_query(stripslashes($title_query));

		} else if ($author_query && $title_query && !$all_query && !$categ_query) {
			
				// Recherche sur l'auteur et le titre :
				$aq_auth=new analyse_query(stripslashes($author_query),0,0,1,1);
				if (!$aq_auth->error) {
					$aq=new analyse_query(stripslashes($title_query));
					if (!$aq->error) {
						$members_auth=$aq_auth->get_query_members("authors","concat(author_name,', ',author_rejete)","index_author","author_id");
						$members_title=$aq->get_query_members("notices","index_wew","index_sew","notice_id",$restrict);

						$requete_count = "select count(distinct notice_id) from notices ";
						$requete_count.= $acces_j;
						$requete_count.= ", authors, responsability where (".$members_auth["where"].") and (".$members_title["where"].") ";
						$requete_count.= "and responsability_author=author_id and responsability_notice=notice_id ";
						$requete_count.= $restrict;
						
						$requete = "select distinct notice_id,".$members_title["select"]."+".$members_auth["select"]." as pert from notices ";
						$requete.= $acces_j;
						$requete.= ", authors, responsability where (".$members_auth["where"].") and (".$members_title["where"].") ";
						$requete.= "and responsability_author=author_id and responsability_notice=notice_id ";
						$requete.= $restrict." order by pert desc, index_serie, tnvol, index_sew ";

						$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
						$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
						$this->text_query=$requete;
						//la requete et la limitation d'enregistrements seront traitées et exécutées dans sort_notices
						return NOTICE_LIST;
					}
				} else {
					$aq=$aq_auth;
				}
				
		} else if (!$title_query && !$all_query && !$categ_query) {
			
				// Recherche sur l'auteur uniquement :
				$aq=new analyse_query(stripslashes($author_query),0,0,1,1);
				if (!$aq->error) {
					if ($typdoc_query || $statut_query || $acces_j) {

						$restrict ="and responsability_author=author_id and responsability_notice=notice_id ".$restrict." ";
						$members=$aq->get_query_members("authors","concat(author_name,', ',author_rejete)","index_author","author_id");
						
						$requete_count = "select count(distinct author_id) from authors, responsability, notices ";
						$requete_count.= $acces_j;
						$requete_count.= "where ".$members["where"]." ";
						$requete_count.= $restrict;
						
						$requete = "select author_id,".$members["select"]." as pert from authors, responsability, notices ";
						$requete.= $acces_j;
						$requete.= "where ".$members["where"]." ";
						$requete.= $restrict." group by author_id order by pert desc,author_name, author_rejete,author_numero , author_subdivision limit ".($this->page*$author_per_page).",".$author_per_page;
						
					} else {
						$requete_count=$aq->get_query_count("authors","concat(author_name,', ',author_rejete)","index_author","author_id");
						$t_query=$aq->get_query_members("authors","concat(author_name,', ',author_rejete)","index_author","author_id");
						$requete="select author_id,".$t_query["select"]." as pert from authors where ".$t_query["where"]." group by author_id order by pert desc,author_name, author_rejete, author_numero , author_subdivision limit ".($this->page*$author_per_page).",".$author_per_page;
					}
	
					$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
					$this->t_query=@mysql_query($requete,$dbh);
					$this->nbepage=ceil($this->nbresults/$author_per_page);
					return AUT_LIST;
				}
				
		} else if (!$title_query && !$author_query && !$categ_query) {
			
			// Recherche sur tous les champs (index global) uniquement :
			$aq=new analyse_query(stripslashes($all_query),0,0,1,1);
			$aq2=new analyse_query(stripslashes($all_query));
			if (!$aq->error) {
				$aq1=new analyse_query(stripslashes($all_query),0,0,1,1);
				$members1=$aq1->get_query_members("notices","index_wew","index_sew","notice_id",$restrict);
				global $pmb_title_ponderation;
				$pert1="+".$members1["select"]."*".$pmb_title_ponderation;
				$members=$aq->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice");
				$members2=$aq2->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice");
				if (($members2["where"]!="()")&&($pmb_default_operator)) {
					$where_term="(".$members["where"]." or ".$members2["where"].")";
				} else {
					$where_term=$members["where"];
				}												
				if($docnum_query && $all_query!='*'){
					//Si on a activé la recherche dans les docs num
					//On traite les notices
					$members_num_noti = $aq2->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_notice","",0,0,true);
					$members_num_bull = $aq2->get_query_members("explnum","explnum_index_wew","explnum_index_sew","explnum_bulletin","",0,0,true);
					
					$join = "(
					select tc.notice_id, sum(tc.pert) as pert, tc.typdoc from (
					(
					select notice_id, ".$members["select"]."+".$members1["select"]." as pert,typdoc 
					from notices join notices_global_index on num_notice=notice_id $acces_j 
					where ".$members["where"]." $restrict 
					) 
					union 
					(
					select notice_id, ".$members_num_noti["select"]." as pert,typdoc 
					from notices join explnum on explnum_notice=notice_id $acces_j 
					where  ".$members_num_noti["where"]." $restrict 
					)
					union 
					(
					select if(num_notice,num_notice,bulletin_notice) as notice_id, ".$members_num_bull["select"]." as pert,typdoc 
					from explnum join bulletins on explnum_bulletin=bulletin_id ,notices $acces_j 
					where bulletin_notice=notice_id and ".$members_num_bull["where"]." $restrict 
					)	
					)as tc group by notice_id
					)";
					$requete_count = "select count(distinct notice_id) from ($join) as union_table";
					$requete="select uni.notice_id, sum(pert) as pert  from ($join) as uni join notices n on n.notice_id=uni.notice_id group by uni.notice_id order by pert desc, index_serie, tnvol, index_sew ";
					
				} else {
					$restrict.= " and num_notice = notice_id ";
					$requete_count = "select count(1) from notices ";
					$requete_count.= $acces_j;
					$requete_count.= ", notices_global_index ";
					$requete_count.= "where ".$where_term." ";
					$requete_count.= $restrict;
									
					$requete = "select notice_id,".$members["select"]."$pert1 as pert from notices ";
					$requete.= $acces_j;
					$requete.= ", notices_global_index ";
					$requete.= "where $where_term ";
					$requete.= $restrict." order by pert desc, index_serie, tnvol, index_sew ";  	
				}

				$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
				//la requete et la limitation d'enregistrements seront traitées et exécutées dans sort_notices
				$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
				$this->text_query=$requete;
				return NOTICE_LIST;
			}
		} else if(!$author_query && !$categ_query) {
			
			// Recherche sur le titre et l'index global :
			$aq_all=new analyse_query(stripslashes($all_query),0,0,1,1);
			$aq_all2=new analyse_query(stripslashes($all_query));
			if (!$aq_all->error) {
				$aq=new analyse_query(stripslashes($title_query));
				if (!$aq->error) {
					$members_all=$aq_all->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice");
					$members_all2=$aq_all2->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice");
					if (($members_all2["where"]!="()")&&($pmb_default_operator)) {
						$where_term="(".$members_all["where"]." or ".$members_all2["where"].")";
					} else {
						$where_term=$members_all["where"];
					}
					$members_title=$aq->get_query_members("notices","index_wew","index_sew","notice_id",$restrict);
					
					$requete_count = "select count(distinct notice_id) from notices ";
					$requete_count.= $acces_j;
					$requete_count.= ", notices_global_index where ($where_term) and (".$members_title["where"].") and num_notice = notice_id ";
					$requete_count.= $restrict;
					
					$requete = "select distinct notice_id, ".$members_title["select"]."+".$members_all["select"]." as pert from notices ";
					$requete.= $acces_j;
					$requete.= ", notices_global_index where ($where_term) and (".$members_title["where"].") and num_notice = notice_id ";
					$requete.= $restrict." order by pert desc, index_serie, tnvol, index_sew "; 
					
					$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
					//la requete et la limitation d'enregistrements seront traitées et exécutées dans sort_notices
					$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
					$this->text_query=$requete;
					return NOTICE_LIST;
				}
			} else {
				$aq=$aq_all;
			}
			
		} else if (!$title_query && !$categ_query) {
			
			// Recherche sur l'auteur et l'index global :
			$aq_auth=new analyse_query(stripslashes($author_query),0,0,1,1);
			if (!$aq_auth->error) {
				$aq=new analyse_query(stripslashes($all_query),0,0,1,1);
				$aq_all2=new analyse_query(stripslashes($all_query));
				if (!$aq->error) {
					$members_auth=$aq_auth->get_query_members("authors","concat(author_name,', ',author_rejete)","index_author","author_id");
					$members_all=$aq->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice",$restrict);
					$members_all2=$aq_all2->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice",$restrict);
					if (($members_all2["where"]!="()")&&($pmb_default_operator)) {
						$where_term="(".$members_all["where"]." or ".$members_all2["where"].")";
					} else {
						$where_term=$members_all["where"];
					}
					
					$requete_count = "select count(distinct notice_id) from notices ";
					$requete_count.= $acces_j;
					$requete_count.= ", notices_global_index, authors, responsability ";
					$requete_count.= "where (".$members_auth["where"].") and ($where_term) and responsability_author=author_id ";
					$requete_count.= "and responsability_notice=num_notice and num_notice=notice_id ";
					$requete_count.= $restrict;
					
					$requete = "select distinct notice_id, ".$members_all["select"]."+".$members_auth["select"]." as pert from notices ";
					$requete.= $acces_j;
					$requete.= ", notices_global_index, authors, responsability where (".$members_auth["where"].") and ($where_term) and responsability_author=author_id ";
					$requete.= "and responsability_notice=num_notice and num_notice=notice_id ";
					$requete.= $restrict." order by pert desc, index_infos_global "; 
					
					$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
					//la requete et la limitation d'enregistrements seront traitées et exécutées dans sort_notices
					$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
					$this->text_query=$requete;
					return NOTICE_LIST;
				}
			} else {
				$aq=$aq_auth;
			}
		} elseif($categ_query){
			$aq_auth=new analyse_query(stripslashes($categ_query),0,0,0,0);
			if (!$aq_auth->error) {
				if($thesaurus_auto_postage_search && $auto_postage_query)
					$members_auth=$aq_auth->get_query_members("categories","path_word_categ","index_path_word_categ","num_noeud");						
				else 
					$members_auth=$aq_auth->get_query_members("categories","libelle_categorie","index_categorie","num_noeud");						
				$requete_count = "select count(distinct notice_id) from notices ";
				$requete_count.= $acces_j;
				$requete_count.= ", categories, noeuds, notices_categories ";
				$requete_count.= "where (".$members_auth["where"].")  ";
				$requete_count.= "and id_noeud= categories.num_noeud and notices_categories.num_noeud=categories.num_noeud and notcateg_notice = notice_id ";
				$requete_count.= $restrict;
					
				$requete = "select distinct notice_id, ".$members_auth["select"]." as pert from notices ";
				$requete.= $acces_j;
				$requete.= ", categories, noeuds, notices_categories ";
				$requete.= "where (".$members_auth["where"].") ";
				$requete.= "and id_noeud= categories.num_noeud and notices_categories.num_noeud=categories.num_noeud and notcateg_notice = notice_id ";
				$requete.= $restrict." order by pert desc "; 
					
				$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
				//la requete et la limitation d'enregistrements seront traitées et exécutées dans sort_notices
				$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
				$this->text_query=$requete;
				return NOTICE_LIST;	
					
			} else {
				$aq=$aq_auth;
			}
			
		} else {			
			// Recherche sur l'auteur, l'index global et le titre :
			$aq_auth=new analyse_query(stripslashes($author_query),0,0,1,1);
			if (!$aq_auth->error) {
				$aq=new analyse_query(stripslashes($all_query),0,0,1,1);
				$aq_all2=new analyse_query(stripslashes($all_query));
				if (!$aq->error) {
					$aq_title=new analyse_query(stripslashes($title_query));
					if (!$aq_title->error) {
						$members_auth=$aq_auth->get_query_members("authors","concat(author_name,', ',author_rejete)","index_author","author_id");
						$members_all=$aq->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice");
						$members_all2=$aq_all2->get_query_members("notices_global_index","infos_global","index_infos_global","num_notice",$restrict);
						if (($members_all2["where"]!="()")&&($pmb_default_operator)) {
							$where_term="(".$members_all["where"]." or ".$members_all2["where"].")";
						} else {
							$where_term=$members_all["where"];
						}
						$members_title=$aq_title->get_query_members("notices","index_wew","index_sew","notice_id",$restrict);
						
						$requete_count = "select count(distinct notice_id) from notices ";
						$requete_count.= $acces_j;
						$requete_count.= ", notices_global_index, authors,responsability ";
						$requete_count.= "where (".$members_auth["where"].") and ($where_term) and (".$members_title["where"].") ";
						$requete_count.= "and responsability_author=author_id and responsability_notice=num_notice and num_notice = notice_id ";
						$requete_count.= $restrict;
						
						$requete = "select distinct notice_id,".$members_all["select"]."+".$members_auth["select"]."+".$members_title["select"]." as pert from notices ";
						$requete.= $acces_j;
						$requete.= ", notices_global_index, authors,responsability ";
						$requete.= "where (".$members_auth["where"].") and ($where_term) and (".$members_title["where"].") ";
						$requete.= "and responsability_author=author_id and responsability_notice=num_notice and num_notice = notice_id ";
						$requete.= $restrict." order by pert desc, index_serie, tnvol, index_sew "; 
						
						$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
						//la requete et la limitation d'enregistrements seront traitées et exécutées dans sort_notices
						$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
						$this->text_query=$requete;
						return NOTICE_LIST;
					}
				}
			} else {
				$aq=$aq_auth;
			}
		}

		if ($aq->error) {
			$this->show_form();
			error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
		} else {
			$aq_title=new analyse_query(stripslashes($title_query));
			$members_title=$aq_title->get_query_members("notices","index_wew","index_sew","notice_id",$restrict);
			
			$requete_count = "select count(distinct notice_id) from notices ";
			$requete_count.= $acces_j;
			$requete_count.= "where (".$members_title["where"].") ";
			$requete_count.= $restrict;
			$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
			
			$requete = "select distinct notice_id, ".$members_title["select"]." as pert from notices ";
			$requete.= $acces_j;
			$requete.= "where (".$members_title["where"].") ";
			$requete.= $restrict." ";
			$requete.="order by pert desc, index_serie, tnvol, index_sew "; 

			//la requete et la limitation d'enregistrements seront traitées et exécutées dans sort_notices
			$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
			$this->text_query=$requete;
			return NOTICE_LIST;
		}
	}

	
	function make_aut_search() {
		global $msg;
		global $charset;
		global $nb_per_page_a_search;
		global $typdoc_query, $statut_query;
		global $acces_j;

		$restrict='';
		if ($typdoc_query) $restrict = "and typdoc='".$typdoc_query."' ";
		if ($statut_query) $restrict.= "and statut='".$statut_query."' ";
		
		if ($nb_per_page_a_search) $this->nb_per_page=$nb_per_page_a_search; else $this->nb_per_page=3;
		
		$requete_count = "select count(distinct notice_id) from notices ";
		$requete_count.= $acces_j;
		$requete_count.= ", responsability where notice_id=responsability_notice and responsability_author=".$this->id." ";
		$requete_count.= $restrict;
		
		$requete = "select distinct notice_id from notices ";
		$requete.= $acces_j; 
		$requete.= ", responsability where notice_id=responsability_notice and responsability_author=".$this->id." ";
		$requete.= $restrict." ";
		$requete.= "order by index_serie, tnvol, index_sew ";
//		$requete.= "limit ".($this->page*$this->nb_per_page).", ".$this->nb_per_page;
		
		$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
		$this->t_query=@mysql_query($requete);
		$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
		$this->text_query=$requete;
	}

	function store_search() {
		global $title_query,$all_query, $author_query,$typdoc_query, $statut_query,$categ_query;
		global $charset;
		$champs="<input type='hidden' name='title_query' value='".htmlentities(stripslashes($title_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='all_query' value='".htmlentities(stripslashes($all_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='author_query' value='".htmlentities(stripslashes($author_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='typdoc_query' value='".htmlentities(stripslashes($typdoc_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='statut_query' value='".htmlentities(stripslashes($statut_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='categ_query' value='".htmlentities(stripslashes($categ_query),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	function aut_store_search() {
		global $typdoc_query, $statut_query;
		global $charset;
		$champs="<input type='hidden' name='aut_id' value='".htmlentities(stripslashes($this->id),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='typdoc_query' value='".htmlentities(stripslashes($typdoc_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='statut_query' value='".htmlentities(stripslashes($statut_query),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	function aut_list() {
		global $msg;
		global $charset;
		global $author_query;
		global $typdoc_query, $statut_query;
		global $pmb_allow_external_search;
		
		$research.="<b>${msg[234]}</b>&nbsp;".htmlentities(stripslashes($author_query),ENT_QUOTES,$charset);
		$this->human_query=$research;
		$this->human_aut_query=$research;

		if ($this->nbresults) {
				$research .= " => ".$this->nbresults." ".$msg["search_resultat"];
				print pmb_bidi("<div class='othersearchinfo'>$research</div>");
				$author_list="<table>\n";
				$parity = 0 ;
				while ($author=@mysql_fetch_object($this->t_query)) {
					if ($parity % 2) {
						$pair_impair = "even";
					} else {
						$pair_impair = "odd";
					}
					$parity += 1;

					$auteur = new auteur($author->author_id);

					$notice_count_sql = "SELECT count(*) FROM responsability WHERE responsability_author = ".$author->author_id;
					$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
					
					if($auteur->see) {
						$link = $this->base_url."&aut_id=".$auteur->id."&etat=aut_search&typdoc_query=".$typdoc_query."&statut_query=".$statut_query;
						$link_see = $this->base_url."&aut_id=".$auteur->see."&etat=aut_search&typdoc_query=".$typdoc_query."&statut_query=".$statut_query;
						$forme = $auteur->display.".&nbsp;- ".$msg["see"]."&nbsp;: <a href='$link_see' class='lien_gestion'>$auteur->see_libelle</a> ";
					} else {
						$link = $this->base_url."&aut_id=".$auteur->id."&etat=aut_search&typdoc_query=".$typdoc_query."&statut_query=".$statut_query;
						$forme = $auteur->display;
					}

					$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='$link';\" ";
					$author_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td>$forme</td><td>".$notice_count."</td></tr>";
				}
				$author_list.="</table>\n";
				print pmb_bidi($author_list);
		} else {
				$this->show_form();
				$cles="<strong>".htmlentities(stripslashes($author_query),ENT_QUOTES, $charset)."</strong>";
				//if ($pmb_allow_external_search) $external="<a href='catalog.php?categ=search&mode=7&from_mode=0&external_type=simple'>".$msg["connecteurs_external_search_sources"]."</a>";
				error_message($msg[357], sprintf($msg["connecteurs_no_title"],$cles,$external), 0, "./catalog.php?categ=search&mode=0");
		}
	}

	
	function notice_list_common($title) {
		global $begin_result_liste;
		global $end_result_liste;
		global $msg;
		global $charset;
		global $pmb_nb_max_tri;
		global $title_query,$author_query, $all_query,$categ_query;
		global $link,$link_expl,$link_explnum,$link_serial,$link_analysis,$link_bulletin,$link_explnum_serial,$link_notice_bulletin;
		global $pmb_allow_external_search;
		global $load_tablist_js;
		
		if ($this->nbresults) {
				$research=$title;
				$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
				print pmb_bidi("<div class='othersearchinfo'>$research</div>");
				print $begin_result_liste;
				$load_tablist_js=1;
				//Affichage des liens paniers et impression
				if ($this->rec_history) {
					
					if (($this->etat=='first_search')&&((string)$this->page=="")) {
						$current=count($_SESSION["session_history"]);
					} else { 
						$current=$_SESSION["CURRENT"];
					}
					if ($current!==false) {
						echo "&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$current&action=print_prepare','print',600,700,-2,-2,'scrollbars=yes,menubar=0,resizable=yes'); return false;\">";
						echo "<img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;";
						$tri_id_info = $_SESSION["tri"] ? "&sort_id=".$_SESSION["tri"] : "";
						echo "<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&action_print=print_prepare".$tri_id_info."','print',500,600,-2,-2,'scrollbars=yes,menubar=0'); return false;\">";
						echo "<img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
						if ($pmb_allow_external_search) {
							echo "&nbsp;<a href='catalog.php?categ=search&mode=7&from_mode=0&external_type=simple'>";
							echo "<img src='./images/external_search.png' border='0' align='center' alt=\"".$msg["connecteurs_external_search_sources"]."\" title=\"".$msg["connecteurs_external_search_sources"]."\"/></a>";
						}
						
						// on affiche l'icone de tri seulement si on a atteint un nb maxi de résultats
						if ($this->nbresults<=$pmb_nb_max_tri) {
							
							//affichage de l'icone de tri
							echo "<a href=# onClick=\"document.getElementById('history').src='./sort.php?type_tri=notices'; document.getElementById('history').style.display='';return false;\" ";
							echo "alt=\"".$msg['tris_dispos']."\" title=\"".$msg['tris_dispos']."\">";
							echo "<img src=./images/orderby_az.gif align=middle hspace=3></a>";
							
							//si on a un tri actif on affiche sa description
							if ($_SESSION["tri"]) {
								echo $msg['tri_par']." ".$this->sort->descriptionTriParId($_SESSION["tri"]);
							}
						}
					}
				}

				// on lance la requête
				$recherche_ajax_mode=0;
				$nb=0;
				while(($nz=@mysql_fetch_object($this->t_query))) {
					if($nb++>5) $recherche_ajax_mode=1;
					$n=@mysql_fetch_object(@mysql_query("SELECT * FROM notices WHERE notice_id=".$nz->notice_id));
					
					switch ($n->niveau_biblio) {
						case 's' :
						case 'a' :
							// on a affaire à un périodique
							$serial = new serial_display($n, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum_serial, 0, 0, 1, 1, true, 1 ,$recherche_ajax_mode);
							print $serial->result;
							break;
						case 'b' :
							// on a affaire à un bulletin
							$rqt_bull_info = "SELECT s.notice_id as id_notice_mere, bulletin_id as id_du_bulletin, b.notice_id as id_notice_bulletin FROM notices as s, notices as b, bulletins WHERE b.notice_id=$n->notice_id and s.notice_id=bulletin_notice and num_notice=b.notice_id";
							$bull_ids=@mysql_fetch_object(mysql_query($rqt_bull_info));
							//si on a les droits
							if(SESSrights & CATALOGAGE_AUTH){
								//on teste la validité du lien
								if(!$link_notice_bulletin){
									$link_notice_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id='.$bull_ids->id_du_bulletin;
								} else {
									$link_notice_bulletin = str_replace("!!id!!",$bull_ids->id_du_bulletin,$link_notice_bulletin);
								}
							}
							elseif($link_notice_bulletin) $link_notice_bulletin = str_replace("!!id!!",$bull_ids->id_du_bulletin,$link_notice_bulletin);
							$display = new mono_display($n, 6, $link_notice_bulletin, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
							$link_notice_bulletin='';
							print $display->result;
							break;
						default:
						case 'm' :
							// notice de monographie
							$display = new mono_display($n, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1   , false,true,$recherche_ajax_mode);
							print $display->result;
							break ;
					}
				}
				// fin de liste
				print $end_result_liste;
		} else {
			$this->show_form();
			$cles="<strong>".$title."</strong>";
			if ($pmb_allow_external_search) $external="<a href='catalog.php?categ=search&mode=7&from_mode=0&external_type=simple'>".$msg["connecteurs_external_search_sources"]."</a>";
			error_message($msg[357], sprintf($msg["connecteurs_no_title"],$cles,$external), 0, "./catalog.php?categ=search&mode=0");
		}
	}

	
	function notice_list() {
		global $msg;
		global $charset;
		global $title_query,$author_query,$all_query,$categ_query;
		
		if($this->docnum){
			$libelle = " [".$msg[docnum_search_with]."]";
		} else $libelle ='';
		if ($title_query) {
			$research .= "<b>${msg[233]}</b>&nbsp;".htmlentities(stripslashes($title_query),ENT_QUOTES,$charset);
		}
		if ($all_query && !$title_query) {
			$research.="<b>${msg[global_search]}$libelle</b>&nbsp;".htmlentities(stripslashes($all_query),ENT_QUOTES,$charset);
		} else if (($all_query && $title_query)) {
			$research.= ", <b>${msg[global_search]}$libelle</b>&nbsp;".htmlentities(stripslashes($all_query),ENT_QUOTES,$charset);
		}	
		if ($author_query) {
			$research.=", <b>${msg[234]}</b>&nbsp;".htmlentities(stripslashes($author_query),ENT_QUOTES,$charset);
		}
		if ($categ_query) {
			$research .= "<b>${msg["search_categorie_title"]}</b>&nbsp;".htmlentities(stripslashes($categ_query),ENT_QUOTES,$charset);
		}

		$this->human_query=$research;
		$this->human_notice_query=$research;

		$this->notice_list_common($research);
	}

	
	function aut_notice_list() {
		global $msg;
		global $charset;

		$auteur = new auteur($this->id);
		$research.="<b>${msg[234]}</b>&nbsp;".$auteur->display;

		$this->human_notice_query=$research;

		$this->notice_list_common($research);
	}

	
	function rec_env() {
		global $msg;
		switch ($this->etat) {
				case 'first_search':
					if ((string)$this->page=="") {
						$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["URI"]=$this->base_url;
						$_POST["etat"]="";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$this->human_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=$msg["354"];
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["DOCNUM_QUERY"]=$this->docnum;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["AUTO_POSTAGE_QUERY"]=$this->auto_postage_query;
					}
					if ((string)$this->page=="") { $_POST["page"]=0; $page=0; }
					if (($this->first_search_result==AUT_LIST)&&($_SESSION["CURRENT"]!==false)) {
						$_POST["etat"]="first_search";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]["HUMAN_QUERY"]=$this->human_aut_query;
					}
					if (($this->first_search_result==NOTICE_LIST)&&($_SESSION["CURRENT"]!==false)) {
						$_POST["etat"]="first_search";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["HUMAN_QUERY"]=$this->human_notice_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["DOCNUM_QUERY"]=$this->docnum;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["AUTO_POSTAGE_QUERY"]=$this->auto_postage_query;
					}
					break;
				case 'aut_search':
					if ($_SESSION["CURRENT"]!==false) {
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['HUMAN_QUERY']=$this->human_notice_query;
					}
					break;
		}
		$_SESSION["last_required"]=false;
	}
	
	function convert_simple_multi($id_champ) {
		global $search;
		
		$x=0;
		
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["title_query"]) {
			$op_="BOOLEAN";
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["title_query"];
			
			$search[$x]="f_6";
			//opérateur
    		$op="op_".$x."_".$search[$x];
    		global $$op;
    		$$op=$op_;
    		    			
    		//contenu de la recherche
    		$field="field_".$x."_".$search[$x];
    		$field_=array();
    		$field_[0]=$valeur_champ;
    		global $$field;
    		$$field=$field_;
    	    	
    		//opérateur inter-champ
    		$inter="inter_".$x."_".$search[$x];
    		global $$inter;
    		$$inter="";
    			    		
    		//variables auxiliaires
    		$fieldvar_="fieldvar_".$x."_".$search[$x];
    		global $$fieldvar_;
    		$$fieldvar_="";
    		$fieldvar=$$fieldvar_;
			$x++;
		} 
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["all_query"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["all_query"];
			$op_="BOOLEAN";
			
			$search[$x]="f_7";
			//opérateur
    		$op="op_".$x."_".$search[$x];
    		global $$op;
    		$$op=$op_;
    		    			
    		//contenu de la recherche
    		$field="field_".$x."_".$search[$x];
    		$field_=array();
    		$field_[0]=$valeur_champ;
    		global $$field;
    		$$field=$field_;
    	    	
    		//opérateur inter-champ
    		$inter="inter_".$x."_".$search[$x];
    		global $$inter;
    		$$inter="";
    			    		
    		//variables auxiliaires
    		$fieldvar_="fieldvar_".$x."_".$search[$x];
    		global $$fieldvar_;
    		$t["is_num"][0]=$_SESSION["session_history"][$id_champ]["NOTI"]["DOCNUM_QUERY"];
    		$t["ck_affiche"][0]=$_SESSION["session_history"][$id_champ]["NOTI"]["DOCNUM_QUERY"];
    		$$fieldvar_=$t;
    		$fieldvar=$$fieldvar_;
			$x++;
		}
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["author_query"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["author_query"];
			
			$op_="BOOLEAN";
			$search[$x]="f_8";
			
			//opérateur
    		$op="op_".$x."_".$search[$x];
    		global $$op;
    		$$op=$op_;
    		    			
    		//contenu de la recherche
    		$field="field_".$x."_".$search[$x];
    		$field_=array();
    		$field_[0]=$valeur_champ;
    		global $$field;
    		$$field=$field_;
    	    	
    		//opérateur inter-champ
    		$inter="inter_".$x."_".$search[$x];
    		global $$inter;
    		if ($x>0) {
    			$$inter="and";
    		} else {
    			$$inter="";
    		}	    		
    		//variables auxiliaires
    		$fieldvar_="fieldvar_".$x."_".$search[$x];
    		global $$fieldvar_;
    		$$fieldvar_="";
    		$fieldvar=$$fieldvar_;
			$x++;
		} else {
			if ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"]) {
				$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"];
							
				$op_="EQ";
				$search[$x]="f_8";
				//opérateur
    			$op="op_".$x."_".$search[$x];
    			global $$op;
    			$$op=$op_;
    		    			
    			//contenu de la recherche
    			$field="field_".$x."_".$search[$x];
    			$field_=array();
    			$field_[0]=$valeur_champ;
    			global $$field;
    			$$field=$field_;
    	    	
    			//opérateur inter-champ
    			$inter="inter_".$x."_".$search[$x];
    			global $$inter;
    			if ($x>0) {
    				$$inter="and";
    			} else {
    				$$inter="";
    			}
    			    		
    			//variables auxiliaires
    			$fieldvar_="fieldvar_".$x."_".$search[$x];
    			global $$fieldvar_;
    			$$fieldvar_="";
    			$fieldvar=$$fieldvar_;
				$x++;
			}
		}
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["typdoc_query"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["typdoc_query"];
			$op_="EQ";
			$search[$x]="f_9";
			//opérateur
    		$op="op_".$x."_".$search[$x];
    		global $$op;
    		$$op=$op_;
    		    			
    		//contenu de la recherche
    		$field="field_".$x."_".$search[$x];
    		$field_=array();
    		$field_[0]=$valeur_champ;
    		global $$field;
    		$$field=$field_;
    	    	
    		//opérateur inter-champ
    		$inter="inter_".$x."_".$search[$x];
    		global $$inter;
    		if ($x>0) {
    			$$inter="and";
    		} else {
    			$$inter="";
    		}	    		
    		//variables auxiliaires
    		$fieldvar_="fieldvar_".$x."_".$search[$x];
    		global $$fieldvar_;
    		$$fieldvar_="";
    		$fieldvar=$$fieldvar_;
			$x++;
		} 
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["statut_query"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["statut_query"];
			$op_="EQ";
			$search[$x]="f_10";
			//opérateur
    		$op="op_".$x."_".$search[$x];
    		global $$op;
    		$$op=$op_;
    		    			
    		//contenu de la recherche
    		$field="field_".$x."_".$search[$x];
    		$field_=array();
    		$field_[0]=$valeur_champ;
    		global $$field;
    		$$field=$field_;
    	    	
    		//opérateur inter-champ
    		$inter="inter_".$x."_".$search[$x];
    		global $$inter;
    		if ($x>0) {
    			$$inter="and";
    		} else {
    			$$inter="";
    		}	    		
    		//variables auxiliaires
    		$fieldvar_="fieldvar_".$x."_".$search[$x];
    		global $$fieldvar_;
    		$$fieldvar_="";
    		$fieldvar=$$fieldvar_;
		} 
	}
	
	function convert_simple_multi_unimarc($id_champ) {
		global $search;
		
		$x=0;
		
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["title_query"]) {
			$op_="BOOLEAN";
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["title_query"];
			
			$search[$x]="f_6";
			//opérateur
    		$op="op_".$x."_".$search[$x];
    		global $$op;
    		$$op=$op_;
    		    			
    		//contenu de la recherche
    		$field="field_".$x."_".$search[$x];
    		$field_=array();
    		$field_[0]=$valeur_champ;
    		global $$field;
    		$$field=$field_;
    	    	
    		//opérateur inter-champ
    		$inter="inter_".$x."_".$search[$x];
    		global $$inter;
    		$$inter="";
    			    		
    		//variables auxiliaires
    		$fieldvar_="fieldvar_".$x."_".$search[$x];
    		global $$fieldvar_;
    		$$fieldvar_="";
    		$fieldvar=$$fieldvar_;
			$x++;
		} 
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["all_query"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["all_query"];
			$op_="BOOLEAN";
			
			$search[$x]="f_7";
			//opérateur
    		$op="op_".$x."_".$search[$x];
    		global $$op;
    		$$op=$op_;
    		    			
    		//contenu de la recherche
    		$field="field_".$x."_".$search[$x];
    		$field_=array();
    		$field_[0]=$valeur_champ;
    		global $$field;
    		$$field=$field_;
    	    	
    		//opérateur inter-champ
    		$inter="inter_".$x."_".$search[$x];
    		global $$inter;
    		$$inter="";
    			    		
    		//variables auxiliaires
    		$fieldvar_="fieldvar_".$x."_".$search[$x];
    		global $$fieldvar_;
    		$$fieldvar_="";
    		$fieldvar=$$fieldvar_;
			$x++;
		}
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["author_query"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["author_query"];
			
			$op_="BOOLEAN";
			$search[$x]="f_8";
			
			//opérateur
    		$op="op_".$x."_".$search[$x];
    		global $$op;
    		$$op=$op_;
    		    			
    		//contenu de la recherche
    		$field="field_".$x."_".$search[$x];
    		$field_=array();
    		$field_[0]=$valeur_champ;
    		global $$field;
    		$$field=$field_;
    	    	
    		//opérateur inter-champ
    		$inter="inter_".$x."_".$search[$x];
    		global $$inter;
    		if ($x>0) {
    			$$inter="and";
    		} else {
    			$$inter="";
    		}	    		
    		//variables auxiliaires
    		$fieldvar_="fieldvar_".$x."_".$search[$x];
    		global $$fieldvar_;
    		$$fieldvar_="";
    		$fieldvar=$$fieldvar_;
			$x++;
		} else {
			if ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"]) {
				$author_id=$_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"];
				$requete="select concat(author_name,', ',author_rejete) from authors where author_id=".$author_id;
				$r_author=mysql_query($requete);
				if (@mysql_num_rows($r_author)) {
					$valeur_champ=mysql_result($r_author,0,0);
				}
				$op_="BOOLEAN";
				$search[$x]="f_8";
				//opérateur
    			$op="op_".$x."_".$search[$x];
    			global $$op;
    			$$op=$op_;
    		    			
    			//contenu de la recherche
    			$field="field_".$x."_".$search[$x];
    			$field_=array();
    			$field_[0]=$valeur_champ;
    			global $$field;
    			$$field=$field_;
    	    	
    			//opérateur inter-champ
    			$inter="inter_".$x."_".$search[$x];
    			global $$inter;
    			if ($x>0) {
    				$$inter="and";
    			} else {
    				$$inter="";
    			}
    			    		
    			//variables auxiliaires
    			$fieldvar_="fieldvar_".$x."_".$search[$x];
    			global $$fieldvar_;
    			$$fieldvar_="";
    			$fieldvar=$$fieldvar_;
				$x++;
			}
		}
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["typdoc_query"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["typdoc_query"];
			$op_="EQ";
			$search[$x]="f_9";
			//opérateur
    		$op="op_".$x."_".$search[$x];
    		global $$op;
    		$$op=$op_;
    		    			
    		//contenu de la recherche
    		$field="field_".$x."_".$search[$x];
    		$field_=array();
    		$field_[0]=$valeur_champ;
    		global $$field;
    		$$field=$field_;
    	    	
    		//opérateur inter-champ
    		$inter="inter_".$x."_".$search[$x];
    		global $$inter;
    		if ($x>0) {
    			$$inter="and";
    		} else {
    			$$inter="";
    		}	    		
    		//variables auxiliaires
    		$fieldvar_="fieldvar_".$x."_".$search[$x];
    		global $$fieldvar_;
    		$$fieldvar_="";
    		$fieldvar=$$fieldvar_;
			$x++;
		} 
		//Pas de statut !
	}
}

class searcher_subject extends searcher {
	var $s_query="";
	var $i_query="";
	var $id_query="";
	var $nb_s;
	var $nb_i;
	var $nb_id;
	var $t_query;


	function show_form() {
		global $search_subject;
		global $search_indexint,$search_indexint_id;
		global $msg;
		global $charset;
		global $current_module;
		global $select3_prop,$search_form_categ,$browser;
		global $browser_url;
		global $thesaurus_mode_pmb;
		global $id_thes;

		//affichage du selectionneur de thesaurus et du lien vers les thésaurus
		$liste_thesaurus = thesaurus::getThesaurusList();
		$sel_thesaurus = '';
		$lien_thesaurus = '';
		
		if ($thesaurus_mode_pmb != 0) {	 //la liste des thesaurus n'est pas affichée en mode monothesaurus
			$sel_thesaurus = "<select class='saisie-30em' id='id_thes' name='id_thes' ";
			$sel_thesaurus.= "onchange = \"document.location = '".$this->base_url."&id_thes='+document.getElementById('id_thes').value; \">" ;
			foreach($liste_thesaurus as $id_thesaurus=>$libelle_thesaurus) {
				$sel_thesaurus.= "<option value='".$id_thesaurus."' "; ;
				if ($id_thesaurus == $id_thes) $sel_thesaurus.= " selected";
				$sel_thesaurus.= ">".htmlentities($libelle_thesaurus,ENT_QUOTES,$charset)."</option>";
			}
			$sel_thesaurus.= "<option value=-1 ";
			if ($id_thes == -1) $sel_thesaurus.= "selected ";
			$sel_thesaurus.= ">".htmlentities($msg['thes_all'],ENT_QUOTES,$charset)."</option>";
			$sel_thesaurus.= "</select>&nbsp;";
		
		}	
		$search_form_categ=str_replace("<!-- sel_thesaurus -->",$sel_thesaurus,$search_form_categ);
		
		
		//affichage du choix de langue pour la recherche
		//		$sel_langue = '';
		//		$sel_langue = "<div class='row'>";
		//		$sel_langue.= "<input type='checkbox' name='lg_search' id='lg_search' value='1' />&nbsp;".htmlentities($msg['thes_sel_langue'],ENT_QUOTES, $charset);
		//		$sel_langue.= "</div><br />";
		//		$search_form_categ=str_replace("<!-- sel_langue -->",$sel_langue,$search_form_categ);
		
	
		$search_form_categ=str_replace("!!base_url!!",$this->base_url,$search_form_categ);
		$browser=str_replace("!!browser_url!!",$browser_url,$browser);
		print pmb_bidi($search_form_categ.$browser);
	}
	

	function show_error($car,$input,$error_message) {
		global $browser_url;
		global $browser,$search_form_categ;
		global $msg;
		
		
		$search_form_categ=str_replace("!!base_url!!",$this->base_url,$search_form_categ);
		print pmb_bidi($search_form_categ);
		error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$car,$input,$error_message));
		$browser=str_replace("!!browser_url!!",$browser_url,$browser);
		print pmb_bidi($browser);
	}

	function make_first_search() {
		
		global $search_subject;
		global $search_indexint,$search_indexint_id,$aut_type;
		global $msg;
		global $charset;
		global $browser,$search_form_categ,$browser_url;
		global $lang;
		global $dbh;
		global $id_thes;
		global $thesaurus_mode_pmb;
		
		if ($search_indexint_id) {
				$this->id=$search_indexint_id;
				$aut_type="indexint";
				return AUT_SEARCH;
		}

		$this->nbresults=0;

		if ($search_subject) {
				$aq=new analyse_query(stripslashes($search_subject));
				if (!$aq->error) {

					$members_catdef = $aq->get_query_members("catdef", "catdef.libelle_categorie", "catdef.index_categorie", "catdef.num_noeud");
					$members_catlg = $aq->get_query_members("catlg", "catlg.libelle_categorie", "catlg.index_categorie", "catlg.num_noeud");
	
					$requete = "select distinct catdef.num_noeud as categ_id, ";
					$requete.= "if (catlg.num_noeud is null, ".$members_catdef["select"].", ".$members_catlg["select"].") as pert ";
					$requete.= "from thesaurus left join noeuds on thesaurus.id_thesaurus = noeuds.num_thesaurus ";
					$requete.= "left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = thesaurus.langue_defaut ";
					$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
					$requete.= "where 1 ";
					if ($id_thes !=-1) $requete.= "and thesaurus.id_thesaurus = '".$id_thes."' ";
					$requete.= "and catdef.libelle_categorie not like '~%' ";
					$requete.= "and (if (catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catlg["where"].") ) order by pert desc,catlg.index_categorie,catdef.index_categorie";
					$this->s_query = mysql_query($requete, $dbh);
					$requete = "select count(distinct catdef.num_noeud) as nb ";
					$requete.= "from thesaurus left join noeuds on thesaurus.id_thesaurus = noeuds.num_thesaurus ";
					$requete.= "left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = thesaurus.langue_defaut ";
					$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
					$requete.= "where 1 ";
					if ($id_thes !=-1) $requete.= "and thesaurus.id_thesaurus = '".$id_thes."' ";
					$requete.= "and catdef.libelle_categorie not like '~%' ";
					$requete.= "and (if (catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catlg["where"].") ) ";

					$this->nb_s = @mysql_result(@mysql_query($requete, $dbh), 0, 0);

										
				} else {
					
					$this->show_error($aq->current_car,$aq->input_html,$aq->error_message);
					return;
				}
		}

		if ($search_indexint) {
				$aq=new analyse_query(stripslashes($search_indexint));
				if (!$aq->error) {
					
					$this->nb_id=@mysql_result(@mysql_query("select count(distinct indexint_id) from indexint where indexint_name like '".str_replace("*","%",$search_indexint)."' order by indexint_name*1"),0,0);
					if ($this->nb_id) {
						$this->id_query=@mysql_query("select indexint_id from indexint where indexint_name like '".str_replace("*","%",$search_indexint)."' order by indexint_name*1");
						if ($this->nb_id==1) {
								$id=@mysql_fetch_object($this->id_query);
								$this->id=$id->indexint_id;
								$aut_type="indexint";
								return AUT_SEARCH;
						}
					}
					$this->nb_i=@mysql_result(@mysql_query($aq->get_query_count("indexint","indexint_comment","index_indexint","indexint_id")),0,0);
					if ($this->nb_i)
						$this->i_query=@mysql_query($aq->get_query("indexint","indexint_comment","index_indexint","indexint_id"));
				} else {
					$this->show_error($aq->current_car,$aq->input_html,$aq->error_message);
					return;
				}
		}
		
		if (($this->nb_s+$this->nb_i+$this->nb_id)==0) {
			
			
			//affichage du selectionneur de thesaurus et du lien vers les thésaurus
			$liste_thesaurus = thesaurus::getThesaurusList();
			$sel_thesaurus = '';
			$lien_thesaurus = '';
			
			if ($thesaurus_mode_pmb != 0) {	 //la liste des thesaurus n'est pas affichée en mode monothesaurus
				$sel_thesaurus = "<select class='saisie-30em' id='id_thes' name='id_thes' ";
				$sel_thesaurus.= "onchange = \"document.location = '".$this->base_url."&id_thes='+document.getElementById('id_thes').value; \">" ;
				foreach($liste_thesaurus as $id_thesaurus=>$libelle_thesaurus) {
					$sel_thesaurus.= "<option value='".$id_thesaurus."' "; ;
					if ($id_thesaurus == $id_thes) $sel_thesaurus.= " selected";
					$sel_thesaurus.= ">".htmlentities($libelle_thesaurus,ENT_QUOTES,$charset)."</option>";
				}
				$sel_thesaurus.= "<option value=-1 ";
				if ($id_thes == -1) $sel_thesaurus.= "selected ";
				$sel_thesaurus.= ">".htmlentities($msg['thes_all'],ENT_QUOTES,$charset)."</option>";
				$sel_thesaurus.= "</select>&nbsp;";
			
			}	
			$search_form_categ=str_replace("<!-- sel_thesaurus -->",$sel_thesaurus,$search_form_categ);
			
			
			//affichage du choix de langue pour la recherche
			//		$sel_langue = '';
			//		$sel_langue = "<div class='row'>";
			//		$sel_langue.= "<input type='checkbox' name='lg_search' id='lg_search' value='1' />&nbsp;".htmlentities($msg['thes_sel_langue'],ENT_QUOTES, $charset);
			//		$sel_langue.= "</div><br />";
			//		$search_form_categ=str_replace("<!-- sel_langue -->",$sel_langue,$search_form_categ);
			
			
			
			$search_form_categ=str_replace("!!base_url!!",$this->base_url,$search_form_categ);
			print pmb_bidi($search_form_categ);
			error_message($msg["searcher_no_result"],$msg["searcher_no_result_desc"]);
			$browser=str_replace("!!browser_url!!",$browser_url,$browser);
			print pmb_bidi($browser);
			return;

		}

		return AUT_LIST;
	}

	function make_aut_search() {
		global $dbh;
		global $aut_type,$nb_per_page_a_search;
		global $thesaurus_auto_postage_montant,$thesaurus_auto_postage_descendant,$thesaurus_auto_postage_nb_montant,$thesaurus_auto_postage_nb_descendant;
		global $thesaurus_auto_postage_etendre_recherche,$nb_level_enfants,$nb_level_parents,$base_path,$msg;
		global $acces_j;
		
		if ($nb_per_page_a_search) $this->nb_per_page=$nb_per_page_a_search; else $this->nb_per_page=3;

		switch ($aut_type) {
			case "indexint":
				$requete_count="select count(distinct notice_id) from notices ";
				$requete_count.= $acces_j;
				$requete_count.= "where indexint=".$this->id." ";
				
				$requete="select notice_id from notices ";
				$requete.= $acces_j;
				$requete.= "where indexint=".$this->id." ";
//				$requete.= "order by index_serie,tnvol,index_sew limit ".($this->page*$this->nb_per_page).",".$this->nb_per_page;
				break;
				
			case "categ":
				//Lire le champ path du noeud pour étendre la recherche éventuellement au fils et aux père de la catégorie
				// lien Etendre auto_postage
				if(!isset($nb_level_enfants)) {
					// non defini, prise des valeurs par défaut
					if(isset($_SESSION["nb_level_enfants"]) && $thesaurus_auto_postage_etendre_recherche) $nb_level_descendant=$_SESSION["nb_level_enfants"];
					else $nb_level_descendant=$thesaurus_auto_postage_nb_descendant;
				} else {
					$nb_level_descendant=$nb_level_enfants;
				}						
				// lien Etendre auto_postage
				if(!isset($nb_level_parents)) {
					// non defini, prise des valeurs par défaut
					if(isset($_SESSION["nb_level_parents"]) && $thesaurus_auto_postage_etendre_recherche) $nb_level_montant=$_SESSION["nb_level_parents"];
					else $nb_level_montant=$thesaurus_auto_postage_nb_montant;
				} else {
					$nb_level_montant=$nb_level_parents;
				}	
				$_SESSION["nb_level_enfants"]=	$nb_level_descendant;
				$_SESSION["nb_level_parents"]=	$nb_level_montant;
				
				$q = "select path from noeuds where id_noeud = '".$this->id."' ";
				$r = mysql_query($q, $dbh);
				$path=mysql_result($r, 0, 0);
				$nb_pere=substr_count($path,'/');
				
				// Si un path est renseigné et le paramètrage activé			
				if ($path && ($thesaurus_auto_postage_descendant || $thesaurus_auto_postage_montant || $thesaurus_auto_postage_etendre_recherche) && ($nb_level_montant || $nb_level_descendant)){
					//Recherche des fils 
					if(($thesaurus_auto_postage_descendant || $thesaurus_auto_postage_etendre_recherche)&& $nb_level_descendant) {
						if($nb_level_descendant != '*' && is_numeric($nb_level_descendant))
							$liste_fils=" path regexp '^$path(\\/[0-9]*){0,$nb_level_descendant}$' ";
						else 
							$liste_fils=" path like '$path/%' or id_noeud='".$this->id."' ";
					} else {
						$liste_fils=" id_noeud = '".$this->id."' ";
					}							
					// recherche des pères
					if(($thesaurus_auto_postage_montant || $thesaurus_auto_postage_etendre_recherche) && $nb_level_montant) {						
						$id_list_pere=explode('/',$path);			
						$stop_pere=0;
						if($nb_level_montant != '*' && is_numeric($nb_level_montant)) $stop_pere=$nb_pere-$nb_level_montant;
						// si les fils intégré, il y a déjà la categ courant dans la requête
						if($liste_fils) $i=$nb_pere-1;
						else $i=$nb_pere;
						for($i;$i>=$stop_pere; $i--) {
							$liste_pere.= " or id_noeud='".$id_list_pere[$i]."' ";
						}
					}			
					// requete permettant de remonter les notices associées à la liste des catégories trouvées;
					$suite_req = "FROM noeuds inner join notices_categories on id_noeud=num_noeud inner join notices on notcateg_notice=notice_id ";
					$suite_req.= $acces_j;
					$suite_req.= "WHERE ($liste_fils $liste_pere) and notices_categories.notcateg_notice = notices.notice_id ";
				} else {	
					// cas normal d'avant		
					$suite_req = "FROM notices_categories, notices ";
					$suite_req.= $acces_j;
					$suite_req.= "WHERE notices_categories.num_noeud = '".$this->id."' and notices_categories.notcateg_notice = notices.notice_id ";
				}
				if ($path) {
					if ($thesaurus_auto_postage_etendre_recherche == 1 || ($thesaurus_auto_postage_etendre_recherche == 2 && !$nb_pere)) {
						$input_txt="<input name='nb_level_enfants' type='text' size='2' value='$nb_level_descendant' 
							onchange=\"document.location='".$this->base_url."&aut_id=".$this->id."&aut_type=categ&etat=aut_search&no_rec_history=1&nb_level_enfants='+this.value\">";
						$auto_postage_form=str_replace("!!nb_level_enfants!!",$input_txt,$msg["categories_autopostage_enfants"]);
						
					}elseif ($thesaurus_auto_postage_etendre_recherche == 2 && $nb_pere) {
						$input_txt="<input name='nb_level_enfants' id='nb_level_enfants' type='text' size='2' value='$nb_level_descendant' 
							onchange=\"document.location='".$this->base_url."&aut_id=".$this->id."&aut_type=categ&etat=aut_search&no_rec_history=1&nb_level_enfants='+this.value+'&nb_level_enfants='+this.value+'&nb_level_parents='+document.getElementById('nb_level_parents').value;\">";
						$auto_postage_form=str_replace("!!nb_level_enfants!!",$input_txt,$msg["categories_autopostage_parents_enfants"]);
		
						$input_txt="<input name='nb_level_parents' id='nb_level_parents' type='text' size='2' value='$nb_level_montant'		
							onchange=\"document.location='".$this->base_url."&aut_id=".$this->id."&aut_type=categ&etat=aut_search&no_rec_history=1&nb_level_parents='+this.value+'&nb_level_enfants='+document.getElementById('nb_level_enfants').value;\">";
						$auto_postage_form=str_replace("!!nb_level_parents!!",$input_txt,$auto_postage_form);
				
					}elseif ($thesaurus_auto_postage_etendre_recherche == 3 ) {
						if($nb_pere) {
							$input_txt="<input name='nb_level_parents' type='text' size='2' value='$nb_level_montant'
								onchange=\"document.location='".$this->base_url."&aut_id=".$this->id."&aut_type=categ&etat=aut_search&no_rec_history=1&nb_level_parents='+this.value\">";
							$auto_postage_form=str_replace("!!nb_level_parents!!",$input_txt,$msg["categories_autopostage_parents"]);
						}
					}
					$this->auto_postage_form=$auto_postage_form;
				}
				$requete_count="select count(distinct notice_id) ".$suite_req;
				$requete = "select distinct notice_id ".$suite_req."order by index_serie,tnvol,index_sew ";//limit ".($this->page*$this->nb_per_page).",".$this->nb_per_page;
				break;
		}
		$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
		$this->t_query=@mysql_query($requete);
		$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
		$this->text_query=$requete;
	}

	function store_search() {
		global $search_subject;
		global $search_indexint,$search_indexint_id,$show_empty;
		global $charset;
		$champs="<input type='hidden' name='search_subject' value='".htmlentities(stripslashes($search_subject),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='search_indexint' value='".htmlentities(stripslashes($search_indexint),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='search_indexint_id' value='".htmlentities(stripslashes($search_indexint_id),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='show_empty' value='".$show_empty."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print pmb_bidi($this->store_form);
	}

	function aut_store_search() {
		global $charset,$aut_type;
		$champs="<input type='hidden' name='aut_id' value='".htmlentities(stripslashes($this->id),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='aut_type' value='".htmlentities(stripslashes($aut_type),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print pmb_bidi($this->store_form);
	}

	function aut_list() {
		global $search_subject;
		global $search_indexint,$search_indexint_id;
		global $msg;
		global $charset;
		global $show_empty;
		$pair_impair = "";
		$parity = 0;
		
		if ($search_subject) $human[]="<b>".$msg["histo_subject"]."</b> ".htmlentities(stripslashes($search_subject),ENT_QUOTES,$charset);
		if ($search_indexint) $human[]="<b>".$msg["histo_indexint"]."</b> ".htmlentities(stripslashes($search_indexint),ENT_QUOTES,$charset);
		$this->human_query=implode(", ",$human);
		$this->human_aut_query=implode(", ",$human);
		if ($this->nb_s) {
				$empty=false;
				print "<strong>${msg[23]} : ".sprintf($msg["searcher_results"],$this->nb_s)."</strong><hr /><table>";
				while($categ=@mysql_fetch_object($this->s_query)) {
					$pair_impair = $parity % 2 ? "even" : "odd";
					
					$temp = new category($categ->categ_id);
					if($temp->voir_id) {
						$cr=$temp->catalog_form;
						$temp = new category($temp->voir_id);
						$display = htmlentities($cr,ENT_QUOTES,$charset)." -> <i>".htmlentities($temp->catalog_form,ENT_QUOTES,$charset)."@</i>";
					} else {
								$display = htmlentities($temp->catalog_form,ENT_QUOTES,$charset);
					}
					if($temp->has_notices()) {
						$notice_count = $temp->notice_count(false);
						$link_categ = "<td><a href='".$this->base_url."&aut_id=".$temp->id."&aut_type=categ&etat=aut_search'>$display</a></td><td>$notice_count</td>";
					}
					else {
						$empty=true;
						if ($show_empty) $link_categ = "<td>$display</td><td></td>"; else $link_categ="";
					}
					if ($link_categ)
						print "<tr class=\"$pair_impair\">$link_categ</tr>";
				}
				print "</table>";
				if (($empty)&&(!$show_empty)) print "<a href='#' onClick=\"document.store_search.show_empty.value=1; document.store_search.page.value=0; document.store_search.submit(); return false;\">".$msg["searcher_categ_empty_results"]."</a>";
		}
		if (($this->nb_i)||($this->nb_id)) {
				if ($this->nb_id) {
					print "<br /><strong>${msg[indexint_catal_title]} ".$msg["searcher_exact_indexint"].": ".sprintf($msg["searcher_results"],$this->nb_id)."</strong><hr /><table>";
					$id_=array();
					$empty=false;
					while($indexint=@mysql_fetch_object($this->id_query)) {
						$pair_impair = $parity % 2 ? "even" : "odd";					
					
						$id_[$indexint->indexint_id]=1;
						$temp = new indexint($indexint->indexint_id);
						$display = htmlentities($temp->name." - ".$temp->comment,ENT_QUOTES,$charset);
						if($temp->has_notices()) {
							$notice_count_sql = "SELECT count(*) FROM notices WHERE indexint = ".$temp->indexint_id;
							$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
							$link = "<td><a href='".$this->base_url."&aut_id=".$temp->indexint_id."&aut_type=indexint&etat=aut_search'>$display</a></td><td>".$notice_count."</td>";
						}
						else {
								$empty=true;
								if ($show_empty) $link = "<td>$display</td><td></td>"; else $link="";
						}
						if ($link) {
							print "<tr class=\"$pair_impair\">$link</tr>";
							$parity += 1;
						}
					}
					print "</table>";
					if (($empty)&&(!$show_empty)) print "<a href='#' onClick=\"document.store_search.show_empty.value=1; document.store_search.page.value=0; document.store_search.submit(); return false;\">".$msg["searcher_indexint_empty_results"]."</a><br /><br />";
				}
				$i_="";
				if ($this->nb_i) {
					$empty=false;
					while($indexint=@mysql_fetch_object($this->i_query)) {
						$pair_impair = $parity % 2 ? "even" : "odd";
						if (!$id_[$indexint->indexint_id]) {
								$temp = new indexint($indexint->indexint_id);
								$display = htmlentities($temp->name." - ".$temp->comment,ENT_QUOTES,$charset);
								if($temp->has_notices()) {
									$notice_count_sql = "SELECT count(*) FROM notices WHERE indexint = ".$temp->indexint_id;
									$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
									$link = "<td><a href='".$this->base_url."&aut_id=".$temp->indexint_id."&aut_type=indexint&etat=aut_search'>$display</a></td><td>".$notice_count."</td>";
								}
								else {
									$empty=true;
									if ($show_empty) $link = "<td>$display</td><td></td>"; else $link="";
								}
								if ($link) {
									$i_.="<tr class=\"$pair_impair\">$link</tr>";
									$parity += 1;
								}
						} else $this->nb_i--;
					}
					$i_="<br /><strong>${msg[indexint_catal_title]} ".$msg["searcher_descr_indexint"]." : ".sprintf($msg["searcher_results"],$this->nb_i)."</strong><hr /><table>".$i_;
					$i_.="</table>";
					if (($empty)&&(!$show_empty)) $i_.="<a href='#' onClick=\"document.store_search.show_empty.value=1; document.store_search.page.value=0; document.store_search.submit(); return false;\">".$msg["searcher_indexint_empty_results"]."</a>";
					print $i_;
				}
		}
	}

	function notice_list_common($title) {
		global $begin_result_liste;
		global $end_result_liste;
		global $msg;
		global $charset;
		global $pmb_nb_max_tri;
		global $link,$link_expl,$link_explnum,$link_serial,$link_analysis,$link_bulletin,$link_explnum_serial,$link_notice_bulletin;
		global $pmb_allow_external_search;
		global $load_tablist_js;
		$research=$title;
		$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
		if($this->auto_postage_form) $research.="&nbsp;&nbsp;".$this->auto_postage_form;
		print "<div class='othersearchinfo'>$research</div>";
		print $begin_result_liste;
		$load_tablist_js=1;
		//Affichage des liens paniers et impression
		if ($this->rec_history) {
			if ((($this->etat=='first_search')&&((string)$this->page==""))||($this->direct))
				$current=count($_SESSION["session_history"]);
			else 
				$current=$_SESSION["CURRENT"];
			
			if ($current!==false) {
				echo "&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$current&action=print_prepare','print',600,700,-2,-2,'scrollbars=yes,menubar=0,resizable=yes'); w.focus(); return false;\">";
				echo "<img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;";
				$tri_id_info = $_SESSION["tri"] ? "&sort_id=".$_SESSION["tri"] : "";
				echo "<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&action_print=print_prepare".$tri_id_info."','print',500,600,-2,-2,'scrollbars=yes,menubar=0'); return false;\">";
				echo "<img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"></a>";
				if ($pmb_allow_external_search) { 
					print "&nbsp;<a href='catalog.php?categ=search&mode=7&from_mode=1&external_type=simple'>";
					echo "<img src='./images/external_search.png' border='0' align='center' alt=\"".$msg["connecteurs_external_search_sources"]."\" title=\"".$msg["connecteurs_external_search_sources"]."\"/></a>";
				}
				// on affiche l'icone de tri seulement si on a atteint un nb maxi de résultats
				if ($this->nbresults<=$pmb_nb_max_tri) {
					
					//affichage de l'icone de tri
					echo "<a href=# onClick=\"document.getElementById('history').src='./sort.php?type_tri=notices'; document.getElementById('history').style.display='';return false;\" ";
					echo "alt=\"".$msg['tris_dispos']."\" title=\"".$msg['tris_dispos']."\">";
					echo "<img src=./images/orderby_az.gif align=middle hspace=3></a>";
					
					//si on a un tri actif on affiche sa description
					if ($_SESSION["tri"]) {
						echo $msg['tri_par']." ".$this->sort->descriptionTriParId($_SESSION["tri"]);
					}
				}
			}
		}
		
		// on lance la requête
		$recherche_ajax_mode=0;
		$nb=0;
		while(($nz=@mysql_fetch_object($this->t_query))) {
				$n=@mysql_fetch_object(@mysql_query("SELECT * FROM notices WHERE notice_id=".$nz->notice_id));
				if($nb++>5)$recherche_ajax_mode=1;
				switch($n->niveau_biblio) {
					case 'm' :
						// notice de monographie
						$display = new mono_display($n, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
						print $display->result;
						break ;
					case 's' :
					case 'a' :
						// on a affaire à un périodique
						// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true, $draggable=0 ) {
						$serial = new serial_display($n, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum_serial, 0, 0, 1, 1, true, 1,$recherche_ajax_mode );
						print $serial->result;
						break;
					case 'b' :
						// on a affaire à un bulletin
						$rqt_bull_info = "SELECT s.notice_id as id_notice_mere, bulletin_id as id_du_bulletin, b.notice_id as id_notice_bulletin FROM notices as s, notices as b, bulletins WHERE b.notice_id=$n->notice_id and s.notice_id=bulletin_notice and num_notice=b.notice_id";
						$bull_ids=@mysql_fetch_object(mysql_query($rqt_bull_info));
						if(!$link_notice_bulletin){
							$link_notice_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id='.$bull_ids->id_du_bulletin;
						} else {
							$link_notice_bulletin = str_replace("!!id!!",$bull_ids->id_du_bulletin,$link_notice_bulletin);
						}
						$display = new mono_display($n, 6, $link_notice_bulletin, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
						$link_notice_bulletin = '';
						print $display->result;
						break;
				}
		}
		// fin de liste
		print $end_result_liste;
	}

	function aut_notice_list() {
		global $msg;
		global $charset;
		global $aut_type;
		global $search_subject,$search_indexint;

		if ($this->direct) {
			if ($search_subject) $human[]="<b>".$msg["histo_subject"]."</b> ".htmlentities(stripslashes($search_subject),ENT_QUOTES,$charset);
			if ($search_indexint) $human[]="<b>".$msg["histo_indexint"]."</b> ".htmlentities(stripslashes($search_indexint),ENT_QUOTES,$charset);
			$this->human_query=implode(", ",$human);
			$this->human_aut_query=implode(", ",$human);
		}
		switch ($aut_type) {
			case "indexint":
				$temp = new indexint($this->id);
				$display = "<b>".$msg["searcher_indexint"]."</b>&nbsp;".htmlentities($temp->name." - ".$temp->comment,ENT_QUOTES,$charset);
				$this->human_notice_query=$display;
				break;
			case "categ":
				$display = "<b>".$msg["searcher_categ"]."</b>&nbsp;";
				$temp = new category($this->id);
				if($temp->voir_id) {
					$cr=$temp->catalog_form;
					$temp = new category($temp->voir_id);
					$display.=htmlentities($cr,ENT_QUOTES,$charset)." -> <i>".htmlentities($temp->catalog_form,ENT_QUOTES,$charset)."@</i>";
				} else {
							$display.=htmlentities($temp->catalog_form,ENT_QUOTES,$charset);
				}
				$this->human_notice_query=$display;
				break;
		}
		$this->notice_list_common($display);
	}

	function rec_env() {
		global $msg;
		switch ($this->etat) {
				case 'first_search':
					if ((string)$this->page=="") {
						$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["URI"]=$this->base_url;
						$_POST["etat"]="";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$this->human_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=$msg["355"];
					}
					if ((string)$this->page=="") { $_POST["page"]=0; $page=0; }
					if (($this->first_search_result==AUT_LIST)&&($_SESSION["CURRENT"]!==false)) {
						$_POST["etat"]="first_search";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]["HUMAN_QUERY"]=$this->human_aut_query;
					}
					if (($this->first_search_result==NOTICE_LIST)&&($_SESSION["CURRENT"]!==false)) {
						$_POST["etat"]="first_search";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["HUMAN_QUERY"]=$this->human_notice_query;
					}
					break;
				case 'aut_search':
					if ($this->direct) {
						$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["URI"]=$this->base_url;
					//	$_POST["etat"]="";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$this->human_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["HUMAN_TITLE"]=$msg["335"];
					}
					if ((string)$this->page=="") { $_POST["page"]=0; $page=0; }
					if ($_SESSION["CURRENT"]!==false) {
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['HUMAN_QUERY']=$this->human_notice_query;
					}
					
					break;
		}
		$_SESSION["last_required"]=false;
	}
	
	function convert_simple_multi($id_champ) {
		global $search;
		
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_indexint_id"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_indexint_id"];
			$op_="EQ";
			$search[0]="f_2";
		} elseif ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_indexint"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_indexint"];
			$op_="EXACT";	
			$search[0]="f_2";
		} elseif ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_subject"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_subject"];
			$op_="EQ";
			$search[0]="f_1";
		} elseif ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"]) {
			switch ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_type"]) {
				case "indexint":
					$search[0]="f_2";
					break;
				case "categ":
					$search[0]="f_1";
					break;
			}
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"];
			$op_="EQ";	
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
	}
	
	function convert_simple_multi_unimarc($id_champ) {
		global $search;
		
		if ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_indexint_id"]) {
			$indexint_id=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_indexint_id"];
			//Recherche de l'indexation
			$requete="select indexint_name from indexint where indexint_id=".$indexint_id;
			$r_indexint=mysql_query($requete);
			if (@mysql_num_rows($r_indexint)) {
				$valeur_champ=mysql_result($r_indexint,0,0);
			}
			$op_="BOOLEAN";
			$search[0]="f_2";
		} elseif ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_indexint"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_indexint"];
			$op_="BOOLEAN";	
			$search[0]="f_2";
		} elseif ($_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_subject"]) {
			$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["POST"]["search_subject"];
			$op_="BOOLEAN";
			$search[0]="f_1";
		} elseif ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"]) {
			switch ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_type"]) {
				case "indexint":
					$search[0]="f_2";
					//Recherche de l'indexation
					$indexint_id=$_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"];
					$requete="select indexint_name from indexint where indexint_id=".$indexint_id;
					$r_indexint=mysql_query($requete);
					if (@mysql_num_rows($r_indexint)) {
						$valeur_champ=mysql_result($r_indexint,0,0);
					}
					break;
				case "categ":
					$search[0]="f_1";
					//Recherche de la catégorie
					$categ_id=$_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"];
					$requete="select libelle_categorie from categories where num_noeud=".$categ_id;
					$r_cat=mysql_query($requete);
					if (@mysql_num_rows($r_cat)) {
						$valeur_champ=mysql_result($r_cat,0,0);
					}
					break;
			}
			$op_="BOOLEAN";	
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
	}
}

class searcher_publisher extends searcher {
	var $p_query;
	var $c_query;
	var $s_query;
	var $nb_p;
	var $nb_c;
	var $nb_s;
	var $t_query;
	
	function show_form() {
		global $search_form_editeur,$browser_editeur,$browser_url;

		$search_form_editeur=str_replace("!!base_url!!",$this->base_url,$search_form_editeur);
		$browser_editeur=str_replace("!!browser_url!!",$browser_url,$browser_editeur);
		print $search_form_editeur.$browser_editeur;
	}

	function show_error($car,$input,$error_message) {
		global $browser_url;
		global $browser,$search_form_editeur;
		global $msg;
		$search_form_editeur=str_replace("!!base_url!!",$this->base_url,$search_form_editeur);
		print $search_form_editeur;
		error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$car,$input,$error_message));
		$browser=str_replace("!!browser_url!!",$browser_url,$browser);
		print $browser;
	}

	function make_first_search() {
		global $search_ed;
		global $msg,$charset;
		global $browser,$browser_url,$search_form_editeur;

		$aq=new analyse_query(stripslashes($search_ed),0,0,1,1);
		if (!$aq->error) {
				$this->nbresults=0;

				//Recherche dans les éditeurs
				$rq_p_c=$aq->get_query_count("publishers","ed_name","index_publisher","ed_id");
				$this->nb_p=@mysql_result(@mysql_query($rq_p_c),0,0);
				if ($this->nb_p) {
					$rq_p=$aq->get_query("publishers","ed_name","index_publisher","ed_id");
					$this->p_query=@mysql_query($rq_p);
				}
				//Recherche des collections
				$rq_c_c=$aq->get_query_count("collections","collection_name","index_coll","collection_id");
				$this->nb_c=@mysql_result(@mysql_query($rq_c_c),0,0);
				if ($this->nb_c) {
					$rq_c=$aq->get_query("collections","collection_name","index_coll","collection_id");
					$this->c_query=@mysql_query($rq_c);
				}
				//Recherche des sous collections
				$rq_s_c=$aq->get_query_count("sub_collections","sub_coll_name","index_sub_coll","sub_coll_id");
				$this->nb_s=@mysql_result(@mysql_query($rq_s_c),0,0);
				if ($this->nb_s) {
					$rq_s=$aq->get_query("sub_collections","sub_coll_name","index_sub_coll","sub_coll_id");
					$this->s_query=@mysql_query($rq_s);
				}
				if (($this->nb_p+$this->nb_c+$this->nb_s)==0) {
					$search_form_editeur=str_replace("!!base_url!!",$this->base_url,$search_form_editeur);
					print $search_form_editeur;
					error_message($msg["searcher_no_result"],$msg["searcher_no_result_desc"]);
					$browser=str_replace("!!browser_url!!",$browser_url,$browser);
					print $browser;
					return;
				} else return AUT_LIST;
		} else {
				$this->show_error($aq->current_car,$aq->input_html,$aq->error_message);
		}
	}

	function make_aut_search() {
		global $aut_type,$mag,$charset,$nb_per_page_a_search;
		global $acces_j;

		if ($nb_per_page_a_search) $this->nb_per_page=$nb_per_page_a_search; else $this->nb_per_page=3;

		switch ($aut_type) {
				case "publisher":
					$requete_count = "select count(distinct notice_id) from notices ";
					$requete_count.= $acces_j;
					$requete_count.= "where (ed1_id='".$this->id."' or ed2_id='".$this->id."') ";
					
					$requete = "select distinct notice_id from notices ";
					$requete.= $acces_j;
					$requete.= "where (ed1_id='".$this->id."' or ed2_id='".$this->id."') ";
//					$requete.= "order by index_serie,tnvol,index_sew limit ".($this->page*$this->nb_per_page).",".$this->nb_per_page;
					break;
					
				case "collection":
					$requete_count = "select count(distinct notice_id) from notices ";
					$requete_count.= $acces_j;
					$requete_count.= "where coll_id='".$this->id."' ";
					
					$requete = "select distinct notice_id from notices ";
					$requete.= $acces_j;
					$requete.= "where coll_id='".$this->id."' ";
//					$requete.= "order by index_serie,tnvol,index_sew limit ".($this->page*$this->nb_per_page).",".$this->nb_per_page;
					break;
					
				case "subcoll":
					$requete_count = "select count(distinct notice_id) from notices ";
					$requete_count.= $acces_j;
					$requete_count.= "where subcoll_id='".$this->id."' ";
					
					$requete = "select distinct notice_id from notices ";
					$requete.= $acces_j;
					$requete.= "where subcoll_id='".$this->id."' ";
//					$requete.= "order by index_serie,tnvol,index_sew limit ".($this->page*$this->nb_per_page).",".$this->nb_per_page;
					break;

		}
		$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
		$this->t_query=@mysql_query($requete);
		$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
		$this->text_query=$requete;
	}

	function store_search() {
		global $search_ed;
		global $show_empty;
		global $charset;
		$champs="<input type='hidden' name='search_ed' value='".htmlentities(stripslashes($search_ed),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='show_empty' value='".$show_empty."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	function aut_store_search() {
		global $charset,$aut_type;
		$champs="<input type='hidden' name='aut_id' value='".htmlentities(stripslashes($this->id),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='aut_type' value='".htmlentities(stripslashes($aut_type),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	function aut_list() {
		global $msg,$charset;
		global $search_ed;

		$this->human_query="<b>".$msg["356"]." </b> ".htmlentities(stripslashes($search_ed),ENT_QUOTES,$charset);
		$this->human_aut_query=$this->human_query;
		
		$pair_impair = "";
		$parity = 0;
		if ($this->nb_p) {
				print "<strong>".$msg["searcher_publisher"]." : ".sprintf($msg["searcher_results"],$this->nb_p)."</strong><hr /><table>";
				while ($p=@mysql_fetch_object($this->p_query)) {
					$pair_impair = $parity % 2 ? "even" : "odd";
					$temp=new editeur($p->ed_id);
					$notice_count_sql = "SELECT count(*) FROM notices WHERE ed1_id = ".$p->ed_id." OR ed2_id = ".$p->ed_id;
					$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
					print "<tr class=\"".$pair_impair."\"><td><a href='".$this->base_url."&etat=aut_search&aut_type=publisher&aut_id=".$p->ed_id."'>".htmlentities($temp->display,ENT_QUOTES,$charset)."</a>";
					if($temp->web) {
						print "&nbsp;<a href=\"".$temp->web."\" target=\"_web\">";
						print "<img src=\"./images/globe.gif\" border=\"0\" align=\"top\"></a>";
					}
					print "</td><td>$notice_count</td></tr>\n";
					$parity++;
				}
				print "</table>\n";
		}
		if ($this->nb_c) {
				print "<strong>".$msg["searcher_coll"]." : ".sprintf($msg["searcher_results"],$this->nb_c)."</strong><hr /><table>";
				while ($c=@mysql_fetch_object($this->c_query)) {
					$pair_impair = $parity % 2 ? "even" : "odd";
					$temp=new collection($c->collection_id);
					$notice_count_sql = "SELECT count(*) FROM notices WHERE coll_id = ".$c->collection_id;
					$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
					print "<tr class=\"".$pair_impair."\"><td><a href='".$this->base_url."&etat=aut_search&aut_type=collection&aut_id=".$c->collection_id."'>".htmlentities($temp->display,ENT_QUOTES,$charset)."</a></td><td>$notice_count</td></tr>\n";
					$parity++;
				}
				print "</table>\n";
		}
		if ($this->nb_s) {
				print "<strong>".$msg["searcher_subcoll"]." : ".sprintf($msg["searcher_results"],$this->nb_s)."</strong><hr /><table>";
				while ($s=@mysql_fetch_object($this->s_query)) {
					$pair_impair = $parity % 2 ? "even" : "odd";
					$temp=new subcollection($s->sub_coll_id);
					$notice_count_sql = "SELECT count(*) FROM notices WHERE subcoll_id = ".$s->sub_coll_id;
					$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
					print "<tr class=\"".$pair_impair."\"><td><a href='".$this->base_url."&etat=aut_search&aut_type=subcoll&aut_id=".$s->sub_coll_id."'>".$temp->display."</a></td><td>$notice_count</td></tr>\n";
					$parity++;
				}
				print "</table>\n";
		}
	}

	function notice_list_common($title) {
		global $begin_result_liste;
		global $end_result_liste;
		global $msg;
		global $charset;
		global $pmb_nb_max_tri;
		global $link,$link_expl,$link_explnum,$link_serial,$link_analysis,$link_bulletin,$link_explnum_serial,$link_notice_bulletin;
		global $pmb_allow_external_search;
		global $load_tablist_js;
		$research=$title;
		$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
		print "<div class='othersearchinfo'>$research</div>";
		print $begin_result_liste;
		$load_tablist_js=1;
		//Affichage des liens paniers et impression
		if ($this->rec_history) {
			$current=$_SESSION["CURRENT"];
			if ($current!==false) {
				$tri_id_info = $_SESSION["tri"] ? "&sort_id=".$_SESSION["tri"] : "";
				print "&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$current&action=print_prepare".$tri_id_info."','print',500, 600, -2, -2, 'scrollbars=yes,menubar=0,resizable=yes'); w.focus(); return false;\"><img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&action_print=print_prepare','print', 500, 600, -2, -2, 'scrollbars=yes,menubar=0'); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
				if ($pmb_allow_external_search) print "&nbsp;<a href='catalog.php?categ=search&mode=7&from_mode=3&external_type=simple'><img src='./images/external_search.png' border='0' align='center' alt=\"".$msg["connecteurs_external_search_sources"]."\" title=\"".$msg["connecteurs_external_search_sources"]."\"/></a>";
				// on affiche l'icone de tri seulement si on a atteint un nb maxi de résultats
				if ($this->nbresults<=$pmb_nb_max_tri) {
					
					//affichage de l'icone de tri
					echo "<a href=# onClick=\"document.getElementById('history').src='./sort.php?type_tri=notices'; document.getElementById('history').style.display='';return false;\" ";
					echo "alt=\"".$msg['tris_dispos']."\" title=\"".$msg['tris_dispos']."\">";
					echo "<img src=./images/orderby_az.gif align=middle hspace=3></a>";
					
					//si on a un tri actif on affiche sa description
					if ($_SESSION["tri"]) {
						echo $msg['tri_par']." ".$this->sort->descriptionTriParId($_SESSION["tri"]);
					}
				}
			}
		}
		// on lance la requête
		$recherche_ajax_mode=0;
		$nb=0;
		while(($nz=@mysql_fetch_object($this->t_query))) {
				$n=@mysql_fetch_object(@mysql_query("SELECT * FROM notices WHERE notice_id=".$nz->notice_id));
				if($nb++>5)$recherche_ajax_mode=1;
				switch($n->niveau_biblio) {
					case 'm' :
						// notice de monographie
						$display = new mono_display($n, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
						print $display->result;
						break ;
					case 's' :
					case 'a' :
						// on a affaire à un périodique
						// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true, $draggable=0 ) {
						$serial = new serial_display($n, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum_serial, 0, 0, 1, 1, true, 1,$recherche_ajax_mode );
						print $serial->result;
						break;
					case 'b' :
						// on a affaire à un bulletin
						$rqt_bull_info = "SELECT s.notice_id as id_notice_mere, bulletin_id as id_du_bulletin, b.notice_id as id_notice_bulletin FROM notices as s, notices as b, bulletins WHERE b.notice_id=$n->notice_id and s.notice_id=bulletin_notice and num_notice=b.notice_id";
						$bull_ids=@mysql_fetch_object(mysql_query($rqt_bull_info));
						if(!$link_notice_bulletin){
							$link_notice_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id='.$bull_ids->id_du_bulletin;
						} else {
							$link_notice_bulletin = str_replace("!!id!!",$bull_ids->id_du_bulletin,$link_notice_bulletin);
						}
						$display = new mono_display($n, 6, $link_notice_bulletin, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
						$link_notice_bulletin = '';
						print $display->result;
						break;
				}
		}
		// fin de liste
		print $end_result_liste;
	}

	function aut_notice_list() {
		global $msg;
		global $charset;
		global $aut_type;

		switch ($aut_type) {
				case "publisher":
					$temp = new editeur($this->id);
					$display = "<b>".$msg["searcher_publisher"]."</b>&nbsp;".htmlentities($temp->display,ENT_QUOTES,$charset);
					$this->human_notice_query=$display;
					break;
				case "collection":
					$display = "<b>".$msg["searcher_coll"]."</b>&nbsp;";
					$temp = new collection($this->id);
					$display.= htmlentities($temp->display,ENT_QUOTES,$charset);
					$this->human_notice_query=$display;
					break;
				case "subcoll":
					$display = "<b>".$msg["searcher_subcoll"]."</b>&nbsp;";
					$temp = new subcollection($this->id);
					$display.=$temp->display;
					$this->human_notice_query=$display;
					break;
		}
		$this->notice_list_common($display);
	}

	function rec_env() {
		global $msg;
		switch ($this->etat) {
				case 'first_search':
					if ((string)$this->page=="") {
						$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["URI"]=$this->base_url;
						$_POST["etat"]="";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$this->human_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=$msg["356"];
					}
					if ((string)$this->page=="") { $_POST["page"]=0; $page=0; }
					if (($this->first_search_result==AUT_LIST)&&($_SESSION["CURRENT"]!==false)) {
						$_POST["etat"]="first_search";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]["HUMAN_QUERY"]=$this->human_aut_query;
					}
					if (($this->first_search_result==NOTICE_LIST)&&($_SESSION["CURRENT"]!==false)) {
						$_POST["etat"]="first_search";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["HUMAN_QUERY"]=$this->human_notice_query;
					}
					break;
				case 'aut_search':
					if ($_SESSION["CURRENT"]!==false) {
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['HUMAN_QUERY']=$this->human_notice_query;
					}
					break;
		}
		$_SESSION["last_required"]=false;
	}
	
	function convert_simple_multi($id_champ) {
		global $search;
		
		$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"];
		$op_="EQ";
		
		switch ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_type"]) {
			case "publisher":
				$search[0]="f_3";
			break;
			case "collection":
				$search[0]="f_4";
			break;
			case "subcoll":
				$search[0]="f_5";
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
	}
	
	function convert_simple_multi_unimarc($id_champ) {
		global $search;
		
		$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"];
		$op_="BOOLEAN";
		
		switch ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_type"]) {
			case "publisher":
				$search[0]="f_3";
				//Recherche de l'éditeur
				$publisher_id=$valeur_champ;
				$requete="select ed_name from publishers where ed_id=".$publisher_id;
				$r_pub=mysql_query($requete);
				if (@mysql_num_rows($r_pub)) {
					$valeur_champ=mysql_result($r_pub,0,0);
				}
			break;
			case "collection":
				$search[0]="f_4";
				//Recherche de l'indexation
				$coll_id=$valeur_champ;
				$requete="select collection_name from collections where collection_id=".$coll_id;
				$r_coll=mysql_query($requete);
				if (@mysql_num_rows($r_coll)) {
					$valeur_champ=mysql_result($r_coll,0,0);
				}
			break;
			case "subcoll":
				$search[0]="f_5";
				//Recherche de la sous-collection
				$subcoll_id=$valeur_champ;
				$requete="select sub_coll_name from sub_collections where sub_coll_id=".$subcoll_id;
				$r_subcoll=mysql_query($requete);
				if (@mysql_num_rows($r_subcoll)) {
					$valeur_champ=mysql_result($r_subcoll,0,0);
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
	}
}

class searcher_titre_uniforme extends searcher {
	var $p_query;
	var $c_query;
	var $s_query;
	var $nb_p;
	var $nb_c;
	var $nb_s;
	var $t_query;
	
	function show_form() {
		global $search_form_titre_uniforme,$browser_titre_uniforme,$browser_url;

		$search_form_titre_uniforme=str_replace("!!base_url!!",$this->base_url,$search_form_titre_uniforme);
		$browser_titre_uniforme=str_replace("!!browser_url!!",$browser_url,$browser_titre_uniforme);
		print $search_form_titre_uniforme.$browser_titre_uniforme;	
	}

	function show_error($car,$input,$error_message) {
		global $browser_url;
		global $browser,$search_form_editeur;
		global $msg;
		$search_form_editeur=str_replace("!!base_url!!",$this->base_url,$search_form_editeur);
		print $search_form_editeur;
		error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$car,$input,$error_message));
		$browser=str_replace("!!browser_url!!",$browser_url,$browser);
		print $browser;
	}

	function make_first_search() {
		global $search_tu;
		global $msg,$charset;
		global $browser,$browser_url,$search_form_titre_uniforme;

		$aq=new analyse_query(stripslashes($search_tu),0,0,1,1);
		if (!$aq->error) {
			$this->nbresults=0;

			//Recherche dans les titres uniformes
			$rq_tu_count=$aq->get_query_count("titres_uniformes","tu_name","index_tu","tu_id");
			$this->nb_tu=@mysql_result(@mysql_query($rq_tu_count),0,0);
			if ($this->nb_tu) {
				$rq_tu=$aq->get_query("titres_uniformes","tu_name","index_tu","tu_id");
				$this->tu_query=@mysql_query($rq_tu);
				return AUT_LIST;
			}else {
				$search_form_titre_uniforme=str_replace("!!base_url!!",$this->base_url,$search_form_titre_uniforme);
				print $search_form_titre_uniforme;
				error_message($msg["searcher_no_result"],$msg["searcher_no_result_desc"]);
				$browser=str_replace("!!browser_url!!",$browser_url,$browser);
				print $browser;
				return;
			}  
		} else {
			$this->show_error($aq->current_car,$aq->input_html,$aq->error_message);
		}
	}

	function make_aut_search() {
		global $aut_type,$mag,$charset,$nb_per_page_a_search;
		global $acces_j;

		if ($nb_per_page_a_search) $this->nb_per_page=$nb_per_page_a_search; else $this->nb_per_page=3;

		switch ($aut_type) {
			case "titre_uniforme":
				$requete_count = "select count(distinct ntu_num_notice) from notices_titres_uniformes, notices ";
				$requete_count.= $acces_j;
				$requete_count.= "where ntu_num_notice=notice_id and ntu_num_tu='".$this->id."' ";
				
				$requete = "select distinct notice_id from notices_titres_uniformes, notices ";
				$requete.= $acces_j;
				$requete.= "where ntu_num_notice=notice_id and ntu_num_tu='".$this->id."' ";
//				$requete.= "order by index_serie,tnvol,index_sew limit ".($this->page*$this->nb_per_page).",".$this->nb_per_page;
			break;
		}
		$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
		$this->t_query=@mysql_query($requete);
		$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
		$this->text_query=$requete;
	}

	function store_search() {
		global $search_ed;
		global $show_empty;
		global $charset;
		$champs="<input type='hidden' name='search_ed' value='".htmlentities(stripslashes($search_ed),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='show_empty' value='".$show_empty."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	function aut_store_search() {
		global $charset,$aut_type;
		$champs="<input type='hidden' name='aut_id' value='".htmlentities(stripslashes($this->id),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='aut_type' value='".htmlentities(stripslashes($aut_type),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	function aut_list() {
		global $msg,$charset;
		global $search_tu;
		$this->human_query="<b>".$msg["356"]." </b> ".htmlentities(stripslashes($search_tu),ENT_QUOTES,$charset);
		$this->human_aut_query=$this->human_query;
		
		$pair_impair = "";
		$parity = 0;
		if ($this->nb_tu) {
			print "<strong>".$msg["search_by_titre_uniforme"]." : ".sprintf($msg["searcher_results"],$this->nb_tu)."</strong><hr /><table>";
			while (($p=@mysql_fetch_object($this->tu_query))) {
				$pair_impair = $parity % 2 ? "even" : "odd";
				$temp=new titre_uniforme($p->tu_id);
				$notice_count_sql = "SELECT count(*) FROM notices_titres_uniformes WHERE ntu_num_tu = ".$p->tu_id ;
				$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
				print "<tr class=\"".$pair_impair."\"><td><a href='".$this->base_url."&etat=aut_search&aut_type=titre_uniforme&aut_id=".$p->tu_id."'>".htmlentities($temp->name,ENT_QUOTES,$charset)."</a>";
				
				print "</td><td>$notice_count</td></tr>\n";
				$parity++;
			}
			print "</table>\n";
		}
	}

	function notice_list_common($title) {
		global $begin_result_liste;
		global $end_result_liste;
		global $msg;
		global $charset;
		global $pmb_nb_max_tri;
		global $link,$link_expl,$link_explnum,$link_serial,$link_analysis,$link_bulletin,$link_explnum_serial;
		global $pmb_allow_external_search;
		global $load_tablist_js;
		$research=$title;
		$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
		print "<div class='othersearchinfo'>$research</div>";
		print $begin_result_liste;
		$load_tablist_js=1;
		//Affichage des liens paniers et impression
		if ($this->rec_history) {
			$current=$_SESSION["CURRENT"];
			if ($current!==false) {
				$tri_id_info = $_SESSION["tri"] ? "&sort_id=".$_SESSION["tri"] : "";
				print "&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$current&action=print_prepare".$tri_id_info."','print',500, 600, -2, -2, 'scrollbars=yes,menubar=0,resizable=yes'); w.focus(); return false;\"><img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&action_print=print_prepare','print', 500, 600, -2, -2, 'scrollbars=yes,menubar=0'); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
				if ($pmb_allow_external_search) print "&nbsp;<a href='catalog.php?categ=search&mode=7&from_mode=3&external_type=simple'><img src='./images/external_search.png' border='0' align='center' alt=\"".$msg["connecteurs_external_search_sources"]."\" title=\"".$msg["connecteurs_external_search_sources"]."\"/></a>";
				// on affiche l'icone de tri seulement si on a atteint un nb maxi de résultats
				if ($this->nbresults<=$pmb_nb_max_tri) {
					
					//affichage de l'icone de tri
					echo "<a href=# onClick=\"document.getElementById('history').src='./sort.php?type_tri=notices'; document.getElementById('history').style.display='';return false;\" ";
					echo "alt=\"".$msg['tris_dispos']."\" title=\"".$msg['tris_dispos']."\">";
					echo "<img src=./images/orderby_az.gif align=middle hspace=3></a>";
					
					//si on a un tri actif on affiche sa description
					if ($_SESSION["tri"]) {
						echo $msg['tri_par']." ".$this->sort->descriptionTriParId($_SESSION["tri"]);
					}
				}
			}
		}
		// on lance la requête
		$recherche_ajax_mode=0;
		$nb=0;
		while(($nz=@mysql_fetch_object($this->t_query))) {
			$n=@mysql_fetch_object(@mysql_query("SELECT * FROM notices WHERE notice_id=".$nz->notice_id));
			if($nb++>5)$recherche_ajax_mode=1;
			switch($n->niveau_biblio) {
				case 'm' :
					// notice de monographie
					$display = new mono_display($n, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
					print $display->result;
				break ;
				case 's' :
				case 'a' :
					// on a affaire à un périodique
					// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true, $draggable=0 ) {
					$serial = new serial_display($n, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum_serial, 0, 0, 1, 1, true, 1,$recherche_ajax_mode );
					print $serial->result;
				break;
				case 'b' :
					// on a affaire à un bulletin
					$rqt_bull_info = "SELECT s.notice_id as id_notice_mere, bulletin_id as id_du_bulletin, b.notice_id as id_notice_bulletin FROM notices as s, notices as b, bulletins WHERE b.notice_id=$n->notice_id and s.notice_id=bulletin_notice and num_notice=b.notice_id";
					$bull_ids=@mysql_fetch_object(mysql_query($rqt_bull_info));
					$link_notice_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id='.$bull_ids->id_du_bulletin;
					$display = new mono_display($n, 6, $link_notice_bulletin, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
					print $display->result;
				break;
			}
		}
		// fin de liste
		print $end_result_liste;
	}

	function aut_notice_list() {
		global $msg;
		global $charset;
		global $aut_type;

		switch ($aut_type) {
			case "titre_uniforme":
				$temp = new titre_uniforme($this->id);
				$display = "<b>".$msg["search_by_titre_uniforme"]."</b>&nbsp;".htmlentities($temp->name,ENT_QUOTES,$charset);
				$this->human_notice_query=$display;
			break;
		}
		$this->notice_list_common($display);
	}

	function rec_env() {
		global $msg;
		switch ($this->etat) {
			case 'first_search':
				if ((string)$this->page=="") {
					$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["URI"]=$this->base_url;
					$_POST["etat"]="";
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['POST']=$_POST;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['GET']=$_GET;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]=array();
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]=array();
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$this->human_query;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=$msg["356"];
				}
				if ((string)$this->page=="") { $_POST["page"]=0; $page=0; }
				if (($this->first_search_result==AUT_LIST)&&($_SESSION["CURRENT"]!==false)) {
					$_POST["etat"]="first_search";
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['URI']=$this->base_url;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['POST']=$_POST;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['GET']=$_GET;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['PAGE']=$this->page+1;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]["HUMAN_QUERY"]=$this->human_aut_query;
				}
				if (($this->first_search_result==NOTICE_LIST)&&($_SESSION["CURRENT"]!==false)) {
					$_POST["etat"]="first_search";
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["HUMAN_QUERY"]=$this->human_notice_query;
				}
			break;
			case 'aut_search':
				if ($_SESSION["CURRENT"]!==false) {
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
					$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['HUMAN_QUERY']=$this->human_notice_query;
				}
			break;
		}
		$_SESSION["last_required"]=false;
	}
	
	function convert_simple_multi($id_champ) {
		global $search;
		
		$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"];
		$op_="EQ";
		
		switch ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_type"]) {
			case "titre_uniforme":
				$search[0]="f_3";//!!!!!!!!! a modifier
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
	}
	
	function convert_simple_multi_unimarc($id_champ) {
		global $search;
		
		$valeur_champ=$_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_id"];
		$op_="BOOLEAN";
		
		switch ($_SESSION["session_history"][$id_champ]["NOTI"]["GET"]["aut_type"]) {
			case "titre_uniforme":
				$search[0]="f_3";
				//Recherche de l'éditeur
				$tu_id=$valeur_champ;
				$requete="select tu_name from titres_uniformes where tu_id=".$tu_id;
				$r_pub=mysql_query($requete);
				if (@mysql_num_rows($r_pub)) {
					$valeur_champ=mysql_result($r_pub,0,0);
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
	}
}


class searcher_serie extends searcher {
	var $p_query;
	var $c_query;
	var $s_query;
	var $nb_p;
	var $nb_c;
	var $nb_s;
	var $t_query;
	
	function show_form() {}

	function show_error($car,$input,$error_message) {}

	function make_first_search() {}

	function make_aut_search() {
		global $aut_type,$mag,$charset,$nb_per_page_a_search;
		global $acces_j;

		if ($nb_per_page_a_search) $this->nb_per_page=$nb_per_page_a_search; else $this->nb_per_page=3;
		
		switch($aut_type){
			case 'tit_serie':
				$requete_count = "select count(distinct notice_id) from notices ";
				$requete_count.= $acces_j;
				$requete_count.= "where index_serie in (select serie_index from series where serie_id='".$this->id."' ) ";
				
				$requete = "select distinct notice_id from notices ";
				$requete.= $acces_j;
				$requete.= "where index_serie in (select serie_index from series where serie_id='".$this->id."' ) ";
//				$requete.= "order by index_serie,tnvol,index_sew limit ".($this->page*$this->nb_per_page).",".$this->nb_per_page;
		}			
		$this->nbresults=@mysql_result(@mysql_query($requete_count),0,0);
		$this->t_query=@mysql_query($requete);
		$this->nbepage=ceil($this->nbresults/$this->nb_per_page);
		$this->text_query=$requete;		
	}

	function store_search() {
		global $search_ed;
		global $show_empty;
		global $charset;
		$champs="<input type='hidden' name='search_ed' value='".htmlentities(stripslashes($search_ed),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='show_empty' value='".$show_empty."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	function aut_store_search() {
		global $charset,$aut_type;
		$champs="<input type='hidden' name='aut_id' value='".htmlentities(stripslashes($this->id),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='aut_type' value='".htmlentities(stripslashes($aut_type),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	function aut_list() {}

	function notice_list_common($title) {
		global $begin_result_liste;
		global $end_result_liste;
		global $msg;
		global $charset;
		global $pmb_nb_max_tri;
		global $link,$link_expl,$link_explnum,$link_serial,$link_analysis,$link_bulletin,$link_explnum_serial,$link_notice_bulletin;
		global $pmb_allow_external_search;
		global $load_tablist_js;
		$research=$title;
		$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
		print "<div class='othersearchinfo'>$research</div>";
		print $begin_result_liste;
		$load_tablist_js=1;
		//Affichage des liens paniers et impression
		if ($this->rec_history) {
			$current=$_SESSION["CURRENT"];
			if ($current!==false) {
				$tri_id_info = $_SESSION["tri"] ? "&sort_id=".$_SESSION["tri"] : "";
				print "&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$current&action=print_prepare".$tri_id_info."','print',500, 600, -2, -2, 'scrollbars=yes,menubar=0,resizable=yes'); w.focus(); return false;\"><img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&action_print=print_prepare','print', 500, 600, -2, -2, 'scrollbars=yes,menubar=0'); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
				if ($pmb_allow_external_search) print "&nbsp;<a href='catalog.php?categ=search&mode=7&from_mode=3&external_type=simple'><img src='./images/external_search.png' border='0' align='center' alt=\"".$msg["connecteurs_external_search_sources"]."\" title=\"".$msg["connecteurs_external_search_sources"]."\"/></a>";
				// on affiche l'icone de tri seulement si on a atteint un nb maxi de résultats
				if ($this->nbresults<=$pmb_nb_max_tri) {
					
					//affichage de l'icone de tri
					echo "<a href=# onClick=\"document.getElementById('history').src='./sort.php?type_tri=notices'; document.getElementById('history').style.display='';return false;\" ";
					echo "alt=\"".$msg['tris_dispos']."\" title=\"".$msg['tris_dispos']."\">";
					echo "<img src=./images/orderby_az.gif align=middle hspace=3></a>";
					
					//si on a un tri actif on affiche sa description
					if ($_SESSION["tri"]) {
						echo $msg['tri_par']." ".$this->sort->descriptionTriParId($_SESSION["tri"]);
					}
				}
			}
		}
		// on lance la requête
		$recherche_ajax_mode=0;
		$nb=0;
		while(($nz=@mysql_fetch_object($this->t_query))) {
				$n=@mysql_fetch_object(@mysql_query("SELECT * FROM notices WHERE notice_id=".$nz->notice_id));
				if($nb++>5)$recherche_ajax_mode=1;
				switch($n->niveau_biblio) {
					case 'm' :
						// notice de monographie
						$display = new mono_display($n, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
						print $display->result;
						break ;
					case 's' :
					case 'a' :
						// on a affaire à un périodique
						// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true, $draggable=0 ) {
						$serial = new serial_display($n, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum_serial, 0, 0, 1, 1, true, 1,$recherche_ajax_mode );
						print $serial->result;
						break;
					case 'b' :
						// on a affaire à un bulletin
						$rqt_bull_info = "SELECT s.notice_id as id_notice_mere, bulletin_id as id_du_bulletin, b.notice_id as id_notice_bulletin FROM notices as s, notices as b, bulletins WHERE b.notice_id=$n->notice_id and s.notice_id=bulletin_notice and num_notice=b.notice_id";
						$bull_ids=@mysql_fetch_object(mysql_query($rqt_bull_info));
						if(!$link_notice_bulletin){
							$link_notice_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id='.$bull_ids->id_du_bulletin;
						} else {
							$link_notice_bulletin = str_replace("!!id!!",$bull_ids->id_du_bulletin,$link_notice_bulletin);
						}
						$display = new mono_display($n, 6, $link_notice_bulletin, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1  , false,true,$recherche_ajax_mode);
						$link_notice_bulletin='';
						print $display->result;
						break;
				}
		}
		// fin de liste
		print $end_result_liste;
	}

	function aut_notice_list() {
		$this->notice_list_common($display);
	}

	function rec_env() {
		global $msg;
		switch ($this->etat) {
				case 'first_search':
					if ((string)$this->page=="") {
						$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["URI"]=$this->base_url;
						$_POST["etat"]="";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]=array();
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$this->human_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=$msg["356"];
					}
					if ((string)$this->page=="") { $_POST["page"]=0; $page=0; }
					if (($this->first_search_result==AUT_LIST)&&($_SESSION["CURRENT"]!==false)) {
						$_POST["etat"]="first_search";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["AUT"]["HUMAN_QUERY"]=$this->human_aut_query;
					}
					if (($this->first_search_result==NOTICE_LIST)&&($_SESSION["CURRENT"]!==false)) {
						$_POST["etat"]="first_search";
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["HUMAN_QUERY"]=$this->human_notice_query;
					}
					break;
				case 'aut_search':
					if ($_SESSION["CURRENT"]!==false) {
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['URI']=$this->base_url;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['POST']=$_POST;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['GET']=$_GET;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['PAGE']=$this->page+1;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['TEXT_QUERY']=$this->text_query;
						$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]['HUMAN_QUERY']=$this->human_notice_query;
					}
					break;
		}
		$_SESSION["last_required"]=false;
	}
	
	function convert_simple_multi($id_champ) {}
	
	function convert_simple_multi_unimarc($id_champ) {}
}
?>