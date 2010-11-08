<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_searcher.class.php,v 1.3 2009-12-24 15:28:25 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Classe de recherche pour selecteurs

require_once("$class_path/analyse_query.class.php");
require_once("$class_path/sel_display.class.php");
require_once("$base_path/selectors/templates/sel_searcher_templates.tpl.php");

//Classe générique de recherche
if(!defined('AUT_LIST')) define("AUT_LIST",1);
if(!defined('ELT_LIST'))define("ELT_LIST",2);
if(!defined('AUT_SEARCH'))define("AUT_SEARCH",3);

$tab_query=array();
$tab_query['notice']=$msg['selector_lib_noti'];
$tab_query['bulletin']=$msg['selector_lib_bull'];
$tab_query['article']=$msg['selector_lib_art'];
$tab_query['abt']=$msg['selector_lib_abt'];
$tab_query['frais']=$msg['selector_lib_frais'];
$tab_query['panier']=$msg['selector_lib_caddie'];
$tab_query['sug']=$msg['selector_lib_sug'];


class sel_searcher {

	var $etat;								//Etat de la recherche
	var $page;								//Page courante de la recherche
	var $nbresults;							//Nombre de résultats de la dernière recherche
	var $nbepage;
	var $aut_id;							//Numéro d'autorité pour la recherche
	var $aut_type;							//Type d'autorité pour la recherche
	var $store_form;						//Formulaire contenant les infos de navigation plus des champs pour la recherche
	var $first_search_result;
	var $direct = 0;

	//Elements obligatoires
	var $base_url = '';						//url de base pour les menus, 	
	var $tab_choice=array();				//Liste des choix a effectuer dans le menu
	
	var $elt_f_list = '';					//Formulaire d'affichage des elements
	var $elt_b_list = '';					//Affichage Debut de liste elements
	var $elt_e_list = '';					//Affichage Fin de liste elements
	var $elt_r_list = '';					//Affichage ligne element
	var $elt_r_list_values = array();		//tableau des elements a afficher dans la liste
	var $action = '';						//Action a transmettre pour retour des parametres
	var $action_values = array();			//tableau des elements à modifier dans l'action
	var $back_script = '';					//Script a executer sur selection d'un element
	
	var $aut_b_list = '';					//Affichage Debut de liste autorites
	var $aut_e_list = '';					//Affichage Fin de liste autorites
	var $aut_r_list = '';					//Affichage ligne autorite
	var $aut_r_list_values = array();		//tableau des autorites a afficher dans la liste
	
	//Constructeur
	function sel_searcher($base_url) {
		
		global $etat,$aut_type,$aut_id,$page;
			
		$this->base_url=$base_url;
		$this->etat=$etat;
		$this->aut_type=$aut_type;
		$this->aut_id=$aut_id;
		$this->page=$page;
		
		//$this->run();
	}

	
	function run() {
		
		$this->set_menu();
		if (!$this->etat) {
			$this->show_form();
		} else {
			switch ($this->etat) {
				case "first_search":
					$r=$this->make_first_search();
					$this->first_search_result=$r;
					switch ($r) {
						case AUT_SEARCH:
							$this->etat="aut_search"; 
							$this->direct=1;
							$this->make_aut_search();
							$this->make_store_form();
							$this->aut_store_search();
							$this->aut_elt_list();
							$this->pager();
							break;
						case AUT_LIST:
							$this->make_store_form();
							$this->store_search();
							$this->aut_list();
							$this->pager();
							break;
						case ELT_LIST:
							$this->make_store_form();
							$this->store_search();
							$this->elt_list();
							$this->pager();
							break;
					}
					break;
				case "aut_search":
					$this->make_aut_search();
					$this->make_store_form();
					$this->aut_store_search();
					$this->aut_elt_list();
					$this->pager();
					break;
			}
		}
	}

	
	function set_menu() {
		
		global $charset;
		global $form_query, $nav_bar, $other_query;
		global $tab_query;

		$menu_query = $nav_bar;
		foreach($this->tab_choice as $typ_query) {
			if (array_key_exists($typ_query, $tab_query)) {
				$menu_query = str_replace('<!-- other_query -->', $other_query.'<!-- other_query -->', $menu_query);
				$menu_query = str_replace('!!typ_query!!', $typ_query, $menu_query);
				$menu_query = str_replace('!!lib!!', htmlentities($tab_query[$typ_query], ENT_QUOTES, $charset),  $menu_query);
				if ($typ_query==$this->cur_typ_query) {
					$menu_query = str_replace('!!class!!', "class='sel_navbar_current'",  $menu_query);
				} else {
					$menu_query = str_replace('!!class!!', '',  $menu_query);
				}
			}
		}
		$form_query = str_replace('!!menu_query!!', $menu_query, $form_query);
	}	

	
	function show_form() {

		global $charset;
		global $form_query, $elt_query, $extended_query;
		
		$form_query = str_replace("!!elt_query!!", htmlentities(stripslashes($elt_query),ENT_QUOTES, $charset), $form_query);
		$form_query = str_replace("<!-- extended_query -->", $extended_query, $form_query );
		$form_query = str_replace("!!action_url!!", $this->base_url."&typ_query=".$this->cur_typ_query, $form_query);
		print $form_query;
	}
	
	
	function pager() {

		global $msg;

		if (!$this->nbresults) return;
		
		$suivante = $this->page+1;
		$precedente = $this->page-1;
		if (!$this->page) $page_en_cours=0 ;
			else $page_en_cours=$this->page ;
				
		// affichage du lien précédent si necessaire
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
		print "<div class='row'><div align='center'>$nav_bar</div></div>";
	}

	
	function make_store_form() {
		$this->store_form="<form name='store_search' action='".$this->base_url."&typ_query=".$this->cur_typ_query."' method='post' style='display:none'>
		<input type='hidden' name='aut_type' value='".$this->aut_type."'/>
		<input type='hidden' name='aut_id' value='".$this->aut_id."'/>
		<input type='hidden' name='etat' value='".$this->etat."'/>
		<input type='hidden' name='page' value='".$this->page."'/>
		!!first_search_variables!!
		</form>";
	}

	function show_elt() {
	}

	
	function make_first_search() {
		//A surcharger par la fonction qui fait la première recherche après la soumission du formulaire de recherche
		//La fonction renvoie AUT_LIST (le résultat de la recherche est une liste d'autorité)
		//ou ELT_LIST (le résultat de la recherche est une liste d'élements)
		//La fonction doit mettre à jour le nombre de résultats dans $this->nbresults
	}

	
	function make_aut_search() {
		//A surcharger par la fonction qui fait la recherche des éléments à partir d'un numéro d'autorité (stoqué dans $this->aut_id)
		//La fonction doit mettre à jour le nombre de résultats dans $this->nbresults
	}

	
	function store_search() {
		//A surcharger par la fonction qui écrit les variables du formulaire "store_search" pour stoquer les champs de recherche
		//En liste de résultat de la première recherche. Il faut remplacer la chaine "!!first_search_variables!!" dans $this->store_form
		global $elt_query;
		global $charset;
		$champs="<input type='hidden' name='elt_query' value='".htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	
	function aut_store_search() {
		//A surcharger par la fonction qui écrit les variables du formulaire "store_search" pour stoquer les champs de recherche
		//En liste de résultat de la première recherche. Il faut remplacer la chaine "!!first_search_variables!!" dans $this->store_form
		global $elt_query;
		global $charset;
		$champs="<input type='hidden' name='elt_query' value='".htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}

	
	function aut_list() {
		//A surcharger par la fonction qui affiche la liste des autorités issues de la première recherche
	}

	
	function elt_list() {
		//A surcharger par la fonction qui affiche la liste des éléments issues de la première recherche
	}

	
	function aut_elt_list() {
		//A surcharger par la fonction qui affiche la liste des éléments sous l'autorité $this->aut_id
	}

	
	function rec_env() {
		//A surcharger par la fonction qui enregistre
	}
}


class sel_searcher_notice_mono extends sel_searcher {
	
	var $t_query;
	var $cur_typ_query='notice';
	
	
	function make_first_search() {

		global $msg,$dbh;
		global $elt_query;		
		global $notice_statut_query, $doctype_query;
		global $nb_per_page, $nb_per_page_select;
		
		if (!$nb_per_page) {
			$nb_per_page=$nb_per_page_select; 
		}
		
		$restrict = "niveau_biblio='m' ";				
		if ($notice_statut_query !='-1') {
			$restrict.= "and statut='".$notice_statut_query."' ";
		}
		
		if ($doctype_query !='-1') {
			$restrict.= "and typdoc='".$doctype_query."' ";
		}
		$suite_rqt="or code='".$elt_query."' "; 
		$isbn_verif=traite_code_isbn(stripslashes($elt_query));
		if (isISBN($isbn_verif)) {
			$suite_rqt.="or code='".formatISBN($isbn_verif,13)."' ";
			$suite_rqt.="or code='".formatISBN($isbn_verif,10)."' ";
			
			$q_count = "select count(*) from notices where ".$restrict." and (0 ".$suite_rqt.")";
			$r_count = mysql_query($q_count, $dbh);
			$n_count = mysql_result($r_count,0,0);
			$this->nbresults = $n_count;
			
			$q_list = "select notice_id from notices where ".$restrict." and (0 ".$suite_rqt.") limit ".$this->page*$nb_per_page.", ".$nb_per_page." "; 
			$r_list = mysql_query($q_list,$dbh);
			$this->t_query=$r_list;
			$this->nbepage=ceil($this->nbresults/$nb_per_page);
			
		}else{
			$aq=new analyse_query(stripslashes($elt_query));
			if ($aq->error) {
				$this->show_form();
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				return ;
			}else{
				$q_members = $aq->get_query_members("notices","index_wew","index_sew","notice_id");
					
				$q_count = "select count(*) from notices where ".$restrict." and (".$q_members["where"]." ".$suite_rqt.")";
				$r_count = mysql_query($q_count, $dbh);
				$n_count = mysql_result($r_count,0,0);
				$this->nbresults = $n_count;
				
				$q_list = "select notice_id, ".$q_members['select']." as pert from notices where ".$restrict." and (".$q_members["where"]." ".$suite_rqt.") ".$q_members['post']." limit ".$this->page*$nb_per_page.", ".$nb_per_page." "; 
				$r_list = mysql_query($q_list,$dbh);
				$this->t_query=$r_list;
				$this->nbepage=ceil($this->nbresults/$nb_per_page);
			}
		}
		return ELT_LIST;
	}

	
	function store_search() {

		global $elt_query;
		global $notice_statut_query, $doctype_query;
		global $charset;
		
		$champs="<input type='hidden' name='elt_query' value='".htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='notice_statut_query' value='".htmlentities(stripslashes($notice_statut_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='doctype_query' value='".htmlentities(stripslashes($doctype_query),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}
	
	
	function elt_list() {

		global $msg, $charset;
		global $elt_query;

		$research .= '<b>'.htmlentities($msg['selector_lib_noti'],ENT_QUOTES,$charset).'</b>&nbsp;'.htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset);
	
		$this->show_form();
		if ($this->nbresults) {
			$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
			$this->elt_b_list = str_replace('!!research!!', $research, $this->elt_b_list);
			print $this->elt_b_list;

			// on lance la requête
			while(($nz=mysql_fetch_object($this->t_query))) {
				// notice de monographie
				$mono = new sel_mono_display($nz->notice_id,$this->base_url);
				$mono->action=$this->action;
				$mono->action_values=$this->action_values;
				$mono->doForm();
				$list.= $this->elt_r_list;
				if (count($this->elt_r_list_values) ) {
					foreach($this->elt_r_list_values as $v) {
						$list = str_replace("!!$v!!", $mono->$v, $list);
					}
				}
			}
			print $list;
			// fin de liste
			print $this->elt_e_liste;
			print $this->back_script;
		} else {
			error_message_history($msg[357], $msg[1915],1);
		}
	}
}


class sel_searcher_notice_article extends sel_searcher {
	
	var $t_query;
	var $cur_typ_query='article';
	
	
	function make_first_search() {

		global $msg,$dbh;
		global $elt_query;		
		global $notice_statut_query, $doctype_query;
		global $nb_per_page, $nb_per_page_select;
		
		if (!$nb_per_page) {
			$nb_per_page=$nb_per_page_select; 
		}
		
		$restrict = "niveau_biblio='a' ";				
		if ($notice_statut_query !='-1') {
			$restrict.= "and statut='".$notice_statut_query."' ";
		}
		
		if ($doctype_query !='-1') {
			$restrict.= "and typdoc='".$doctype_query."' ";
		}
		
		$aq=new analyse_query(stripslashes($elt_query));
		if ($aq->error) {
			$this->show_form();
			error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
		} else {
			
			$q_members = $aq->get_query_members("notices","index_wew","index_sew","notice_id");			
			$q_count = "select count(*) from notices where ".$restrict." and (".$q_members["where"]." ".$suite_rqt.")";
			$r_count = mysql_query($q_count, $dbh);
			$n_count = mysql_result($r_count,0,0);
			$this->nbresults = $n_count;
			
			$q_list = "select notice_id, ".$q_members['select']." as pert from notices where ".$restrict." and (".$q_members["where"]." ".$suite_rqt.") ".$q_members['post']." limit ".$this->page*$nb_per_page.", ".$nb_per_page." "; 
			$r_list = mysql_query($q_list,$dbh);
			$this->t_query=$r_list;
			$this->nbepage=ceil($this->nbresults/$nb_per_page);
			return ELT_LIST;
		}
	}

	
	function store_search() {

		global $elt_query;
		global $notice_statut_query, $doctype_query;
		global $charset;
		
		$champs="<input type='hidden' name='elt_query' value='".htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='notice_statut_query' value='".htmlentities(stripslashes($notice_statut_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='doctype_query' value='".htmlentities(stripslashes($doctype_query),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}
	
	
	function elt_list() {

		global $msg, $charset;
		global $elt_query;

		$research .= '<b>'.htmlentities($msg['selector_lib_noti'],ENT_QUOTES,$charset).'</b>&nbsp;'.htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset);
	
		$this->show_form();
		if ($this->nbresults) {
			$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
			$this->elt_b_list = str_replace('!!research!!', $research, $this->elt_b_list);
			print $this->elt_b_list;

			// on lance la requête
			while(($nz=mysql_fetch_object($this->t_query))) {
				// notice d'article
				$art = new sel_article_display($nz->notice_id,$this->base_url);
				$art->action=$this->action;
				$art->action_values=$this->action_values;
				$art->doForm();
				$list.= $this->elt_r_list;
				if (count($this->elt_r_list_values) ) {
					foreach($this->elt_r_list_values as $v) {
						$list = str_replace("!!$v!!", $art->$v, $list);
					}
				}
			}
			print $list;
			// fin de liste
			print $this->elt_e_liste;
			print $this->back_script;
		} else {
			error_message_history($msg[357], $msg[1915],1);
		}
	}
}


class sel_searcher_bulletin extends sel_searcher {
	
	var $t_query;
	var $cur_typ_query='bulletin';
	
	
	function make_first_search() {

		global $msg,$dbh;
		global $elt_query;
		global $nb_per_page, $nb_per_page_select;
		
		if (!$nb_per_page) {
			$nb_per_page=$nb_per_page_select; 
		}
						
		$restrict = "niveau_biblio='s' ";				
		$restrict.= "and bulletin_notice=notice_id ";
		
		$suite_rqt="or code='".$elt_query."' ";
		
		$issn_verif=traite_code_ISSN(stripslashes($elt_query));
		if (isISSN(stripslashes($elt_query))) {
			$suite_rqt.=" or code='".$issn_verif."' ";
			$q_count = "select count(distinct notice_id) from notices, bulletins where ".$restrict." and (0 ".$suite_rqt.")";
			$r_count = mysql_query($q_count);
			$n_count = mysql_result($r_count,0,0);
			$this->nbresults = $n_count;
			
			$q_list = "select distinct(notice_id) from notices, bulletins where ".$restrict." and (0 ".$suite_rqt.") limit ".$this->page*$nb_per_page.", ".$nb_per_page." "; 
			$r_list = mysql_query($q_list,$dbh);
			$this->t_query=$r_list;
			$this->nbepage=ceil($this->nbresults/$nb_per_page);
		}else{
			$aq=new analyse_query(stripslashes($elt_query));
			if ($aq->error) {
				$this->show_form();
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				return ;
			} else {
				$q_members = $aq->get_query_members("notices","index_wew","index_sew","notice_id");	
				$q_count = "select count(distinct notice_id) from notices, bulletins where ".$restrict." and (".$q_members["where"]." ".$suite_rqt.")";
				$r_count = mysql_query($q_count);
				$n_count = mysql_result($r_count,0,0);
				$this->nbresults = $n_count;
				
				$q_list = "select distinct(notice_id), ".$q_members['select']." as pert from notices, bulletins where ".$restrict." and (".$q_members["where"]." ".$suite_rqt.") ".$q_members['post']." limit ".$this->page*$nb_per_page.", ".$nb_per_page." "; 
				$r_list = mysql_query($q_list,$dbh);
				$this->t_query=$r_list;
				$this->nbepage=ceil($this->nbresults/$nb_per_page);
				
			}
		}
		return AUT_LIST;
	}

	
	function make_aut_search() {
		
		global $dbh;
		global $nb_per_page, $nb_per_page_select;
		
		if (!$nb_per_page) {
			$nb_per_page=$nb_per_page_select; 
		}
			
		switch ($this->aut_type) {
			case 'perio':
				$q_count="select count(*) from bulletins where bulletin_notice='".$this->aut_id."' ";
				$q_list="select bulletin_id from bulletins where bulletin_notice='".$this->aut_id."' order by date_date desc, bulletin_numero desc limit ".($this->page*$nb_per_page).",".$nb_per_page;
				break;
		}
		$r_count = mysql_query($q_count, $dbh);
		$n_count = mysql_result($r_count,0,0);
		$this->nbresults=$n_count;
		
		$r_list = mysql_query($q_list, $dbh);
		$this->t_query=$r_list;
		$this->nbepage=ceil($this->nbresults/$nb_per_page);
	}
	
	
	function aut_list() {

		global $msg, $charset;
		global $elt_query;
		
		$research .= '<b>'.htmlentities($msg['771'],ENT_QUOTES,$charset).'</b>&nbsp;'.htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset);
	
		$this->show_form();
		if ($this->nbresults) {
			$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
			$this->aut_b_list = str_replace('!!research!!', $research, $this->aut_b_list);
			print $this->aut_b_list;

			// on lance la requete
			while(($nz=mysql_fetch_object($this->t_query))) {
				// notice de perio
				$perio = new sel_serial_display($nz->notice_id, $this->base_url);
				$perio->action="<a href='".$this->base_url."&typ_query=".$this->cur_typ_query."&etat=aut_search&aut_type=perio&aut_id=!!aut_id!!' >!!display!!</a>";
				$perio->doForm();
				$list.= $this->aut_r_list;
				if (count($this->aut_r_list_values)) {
					foreach($this->aut_r_list_values as $v) {
						$list=str_replace("!!$v!!", $perio->$v, $list);
					}
				}
			}
			print $list;
			// fin de liste
			print $this->aut_e_liste;
		} else {
			error_message_history($msg[357], $msg[1915], 1);
		}
	}

	
	function aut_elt_list() {
			
		global $msg, $charset;
		global $elt_query;

		$research .= '<b>'.htmlentities($msg['selector_lib_bull'],ENT_QUOTES,$charset).'</b>&nbsp;'.htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset);
	
		$this->show_form();
		if ($this->nbresults) {
			$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
			$this->elt_b_list = str_replace('!!research!!', $research, $this->elt_b_list);
			print $this->elt_b_list;

			// on lance la requête
			while(($nz=mysql_fetch_object($this->t_query))) {
				// bulletin
				$bull = new sel_bulletin_display($nz->bulletin_id, $this->base_url);
				$bull->action=$this->action;
				$bull->action_values=$this->action_values;
				$bull->doForm();
				$list.= $this->elt_r_list;
				if (count($this->elt_r_list_values)) {
					foreach($this->elt_r_list_values as $v) {
						$list = str_replace("!!$v!!", $bull->$v, $list);
					}
				}
			}
			print $list;
			// fin de liste
			print $this->elt_e_liste;
			print $this->back_script;
		} else {
			error_message_history($msg[357], $msg[1915], 1);
		}
	}
}


class sel_searcher_frais extends sel_searcher {
	
	var $t_query;
	var $cur_typ_query='frais';
	
	
	function make_first_search() {

		global $msg,$dbh;
		global $elt_query;
		global $nb_per_page, $nb_per_page_select;
		
		if (!$nb_per_page) {
			$nb_per_page=$nb_per_page_select; 
		}
		
		$aq=new analyse_query(stripslashes($elt_query));
		if ($aq->error) {
			$this->show_form();
			error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
		} else {
			$q_count=$aq->get_query_count('frais','libelle','libelle','id_frais');
			$r_count = mysql_query($q_count);
			$n_count = mysql_result($r_count,0,0);
			$this->nbresults = $n_count;
			
			$q_list = $aq->get_query('frais','libelle','libelle','id_frais', $this->page*$nb_per_page , $nb_per_page); 
			$r_list = mysql_query($q_list,$dbh);
			$this->t_query=$r_list;
			$this->nbepage=ceil($this->nbresults/$nb_per_page);
			return ELT_LIST;
		}
	}


	function elt_list() {

		global $msg, $charset;
		global $elt_query;
		
		$research .= '<b>'.htmlentities($msg['selector_lib_frais'],ENT_QUOTES,$charset).'</b>&nbsp;'.htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset);
	
		$this->show_form();
		if ($this->nbresults) {
			$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
			$this->elt_b_list = str_replace('!!research!!', $research, $this->elt_b_list);
			print $this->elt_b_list;

			// on lance la requête
			while(($nz=mysql_fetch_object($this->t_query))) {
				
				// frais annexes
				$frais = new sel_frais_display($nz->id_frais, $this->base_url);
				$frais->action=$this->action;
				$frais->action_values=$this->action_values;
				$frais->doForm();
				$list.= $this->elt_r_list;
				if (count($this->elt_r_list_values) ) {
					foreach($this->elt_r_list_values as $v) {
						$list = str_replace("!!$v!!", $frais->$v, $list);
					}
				}
			}
			print $list;
			// fin de liste
			print $this->elt_e_liste;
			print $this->back_script;
		} else {
			error_message_history($msg[357], $msg[1915], 1);
		}
	}
}


class sel_searcher_abt extends sel_searcher {
	
	var $t_query;
	var $cur_typ_query='abt';
	
	
	function make_first_search() {

		global $msg,$dbh;
		global $elt_query;
		global $location_query, $date_ech_query;
		global $nb_per_page, $nb_per_page_select;
		
		if (!$nb_per_page) {
			$nb_per_page=$nb_per_page_select; 
		}

		$restrict = "1 ";
		if ($location_query!='-1') {
			$restrict.= "and location_id='".$location_query."' ";
		}
		$restrict.= "and notice_id=num_notice ";
		
		if ($date_ech_query!='-1') {
			$restrict.= "and date_fin < '".$date_ech_query."' ";	
		}
		
		$suite_rqt="or code='".$elt_query."' ";
		
		$issn_verif=traite_code_ISSN(stripslashes($elt_query));
		if (isISSN(stripslashes($elt_query))) {
			$suite_rqt.=" or code='".$issn_verif."' ";			
			$q_count = "select count(abt_id) from notices, abts_abts where ".$restrict." and (0 ".$suite_rqt.")";
			$r_count = mysql_query($q_count);
			$n_count = mysql_result($r_count,0,0);
			$this->nbresults = $n_count;
			
			$q_list = "select abt_id from notices, abts_abts where ".$restrict." and (0 ".$suite_rqt.") limit ".$this->page*$nb_per_page.", ".$nb_per_page." "; 
			$r_list = mysql_query($q_list,$dbh);
			$this->t_query=$r_list;
			$this->nbepage=ceil($this->nbresults/$nb_per_page);
			
		}else{
			$aq=new analyse_query(stripslashes($elt_query));
			if ($aq->error) {
				$this->show_form();
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				return ;
			} else {
				$q_members = $aq->get_query_members("notices","index_wew","index_sew","abt_id");			
				$q_count = "select count(abt_id) from notices, abts_abts where ".$restrict." and (".$q_members["where"]." ".$suite_rqt.")";
				$r_count = mysql_query($q_count);
				$n_count = mysql_result($r_count,0,0);
				$this->nbresults = $n_count;
				
				$q_list = "select abt_id, ".$q_members['select']." as pert from notices, abts_abts where ".$restrict." and (".$q_members["where"]." ".$suite_rqt.") ".$q_members['post']." limit ".$this->page*$nb_per_page.", ".$nb_per_page." "; 
				$r_list = mysql_query($q_list,$dbh);
				$this->t_query=$r_list;
				$this->nbepage=ceil($this->nbresults/$nb_per_page);
			}
		}
		return ELT_LIST;
	}

	
	function store_search() {

		global $elt_query;
		global $location_query, $date_ech_query;
		global $charset;
		
		$champs="<input type='hidden' name='elt_query' value='".htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='location_query' value='".htmlentities(stripslashes($location_query),ENT_QUOTES,$charset)."'/>";
		$champs.="<input type='hidden' name='date_ech_query' value='".htmlentities(stripslashes($date_ech_query),ENT_QUOTES,$charset)."'/>";
		$this->store_form=str_replace("!!first_search_variables!!",$champs,$this->store_form);
		print $this->store_form;
	}
	
	
	function elt_list() {

		global $msg, $charset;
		global $elt_query;

		$research .= '<b>'.htmlentities($msg['selector_lib_abt'],ENT_QUOTES,$charset).'</b>&nbsp;'.htmlentities(stripslashes($elt_query),ENT_QUOTES,$charset);
	
		$this->show_form();
		if ($this->nbresults) {
			$research .= " => ".sprintf($msg["searcher_results"],$this->nbresults);
			$this->elt_b_list = str_replace('!!research!!', $research, $this->elt_b_list);
			print $this->elt_b_list;

			// on lance la requête 
			while(($nz=mysql_fetch_object($this->t_query))) {
				// abonnement
				$abt = new sel_abt_display($nz->abt_id, $this->base_url);
				$abt->action=$this->action;
				$abt->action_values=$this->action_values;
				$abt->doForm();
				$list.= $this->elt_r_list;
				if (count($this->elt_r_list_values)) {
					foreach($this->elt_r_list_values as $v) {
						$list = str_replace("!!$v!!", $abt->$v, $list);
					}
				}
			}
			print $list;
			// fin de liste
			print $this->elt_e_liste;
			print $this->back_script;
		} else {
			error_message_history($msg[357], $msg[1915],1);
		}
	}	
}

?>