<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serials.class.php,v 1.121.2.5 2011-09-08 09:48:16 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// classes de gestion des périodiques
require_once($class_path."/parametres_perso.class.php");
require_once($include_path."/notice_authors.inc.php");
require_once($include_path."/notice_categories.inc.php");
require_once($class_path."/thesaurus.class.php");
require_once($class_path."/editor.class.php");
require_once($class_path."/mono_display.class.php");
require_once($class_path."/acces.class.php");

/* ------------------------------------------------------------------------------------
        classe serial : classe de gestion des notices chapeau
--------------------------------------------------------------------------------------- */
class serial {
	
	// classe de la notice chapeau des périodiques
	
	var $serial_id       = 0;         // id de ce périodique
	var $biblio_level    = 's';       // niveau bibliographique
	var $hierar_level    = '1';       // niveau hiérarchique
	var $typdoc          = '';        // type UNIMARC du document
	var $code            = '';        // codebarre du périodique
	var $tit1            = '';        // titre propre
	var $tit3            = '';        // titre parallèle
	var $tit4            = '';        // complément du titre propre
	var $ed1_id          = 0;         // id de l'éditeur 1
	var $ed1             = '';        // forme affichable de l'éditeur 1
	var $ed2_id          = 0;         // id de l'éditeur 2
	var $ed2             = '';        // forme affichable de l'éditeur 2
	var $n_gen           = '';        // note générale
	var $n_contenu		 = '';		  // note de contenu
	var $n_resume        = '';        // note de résumé
	var $categories =	array();// les categories
	var $indexint        = 0;         // id indexation interne
	var $indexint_lib    = '';        // libelle indexation interne
	var $index_l         = '';        // indexation libre
	var $langues = array();
	var $languesorg = array();
	var $lien            = '';        // URL associée
	var $eformat         = '';        // type de la ressource électronique
	var $responsabilites =	array("responsabilites" => array(),"auteurs" => array());  // les auteurs
	var $statut 		= 0 ; 		// statut 
	var $commentaire_gestion = '' ;
	var $thumbnail_url = '' ;
	var $signature= '';
	var $notice_mere=array();
	var $notice_mere_id=array();
	var $relation_type=array();
	var $relation_rank=array();
	var $date_parution_perio = '';
	var $opac_visible_bulletinage = 1;

	// constructeur
	function serial($id=0) {
		
		// si id, allez chercher les infos dans la base
		if($id) {
			$this->serial_id = $id;
			$this->fetch_serial_data();
		}
		return $this->serial_id;
	}
		    
	// récupération des infos en base
	function fetch_serial_data() {
		global $dbh;
		global $fonction_auteur;
		
		$myQuery = mysql_query("SELECT * FROM notices WHERE notice_id='".$this->serial_id."' LIMIT 1", $dbh);
		$myPerio = mysql_fetch_object($myQuery);
		
		// type du document
		$this->typdoc  = $myPerio->typdoc;
		// statut de la notice
		$this->statut  = $myPerio->statut;
		$this->commentaire_gestion  = $myPerio->commentaire_gestion;
		$this->thumbnail_url		= $myPerio->thumbnail_url;
	
		// code-barre
		$this->code = $myPerio->code;
	
		// mentions de titre
		$this->tit1 = $myPerio->tit1;
		$this->tit3 = $myPerio->tit3;
		$this->tit4 = $myPerio->tit4;
		
		// libelle des auteurs
		$this->responsabilites = get_notice_authors($this->serial_id) ;
		
		// libelle des éditeurs
		if($myPerio->ed1_id) {
			$this->ed1_id = $myPerio->ed1_id;
			$editeur = new editeur($this->ed1_id);
			$this->ed1 = $editeur->display;
		}
		if($myPerio->ed2_id) {
			$this->ed2_id = $myPerio->ed2_id;
			$editeur = new editeur($this->ed2_id);
			$this->ed2 = $editeur->display;
		}
		
		// année d'édition
		$this->year = $myPerio->year;
		$this->date_parution_perio = $this->get_date_parution($this->year);
		
		// zone des notes
		$this->n_gen = $myPerio->n_gen;
		$this->n_contenu = $myPerio->n_contenu;
		$this->n_resume = $myPerio->n_resume;
		
		// mise à jour des catégories
		$this->categories = get_notice_categories($this->serial_id) ;
			
		// indexation interne
		if($myPerio->indexint) {
			$this->indexint = $myPerio->indexint;
			$indexint = new indexint($this->indexint);
			if ($indexint->comment) $this->indexint_lib = $indexint->name." - ".$indexint->comment ; 
			else $this->indexint_lib = $indexint->name ;
		}
		
		// indexation libre
		$this->index_l = $myPerio->index_l;
		
		// libelle des langues
		$this->langues	= get_notice_langues($this->serial_id, 0) ;	// langues de la publication
		$this->languesorg	= get_notice_langues($this->serial_id, 1) ; // langues originales
		
		// lien vers une ressource électronique
		$this->lien = $myPerio->lien;
		$this->eformat = $myPerio->eformat;
		$this->signature = $myPerio->signature;
		
		// Montrer ou pas le bulletinage en opac
		$this->opac_visible_bulletinage = $myPerio->opac_visible_bulletinage;
	
		//relations
		$requete="select linked_notice, relation_type, rank from notices_relations where num_notice=".$this->serial_id." order by rank";
		$result_rel=mysql_query($requete);
		if (mysql_num_rows($result_rel)) {
			while (($r_rel=mysql_fetch_object($result_rel))) {
				$this->notice_mere[]=$this->get_notice_title($r_rel->linked_notice);
				$this->notice_mere_id[]=$r_rel->linked_notice;
				$this->relation_type[]=$r_rel->relation_type;
				$this->relation_rank[]=$r_rel->rank;
			}
		}
	
		return $myQuery->nbr_rows;
	}
	
	//Récupération d'un titre de notice
	function get_notice_title($notice_id) {
		$requete="select serie_name, tnvol, tit1, code from notices left join series on serie_id=tparent_id where notice_id=".$notice_id;
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$r=mysql_fetch_object($resultat);
			return ($r->serie_name?$r->serie_name." ":"").($r->tnvol?$r->tnvol." ":"").$r->tit1.($r->code?" (".$r->code.")":"");
		}
		return '';
	}
	
	//Récupérer une date au format AAAA-MM-JJ
	function get_date_parution($annee) {
		
		if($annee){
			$pattern='/(\d{4})/';
			if(preg_match($pattern,$annee,$matches)){
				$date_tmp = $matches[0].'-01-01';
				return $date_tmp;
			} else return '0000-00-00'; 
		}			
		return '0000-00-00';
		
	}
	
	// fonction de mise à jour ou de création d'un périodique
	function update($value) {
		
		global $dbh;
		
		// formatage des valeurs de $value
		// $value est un tableau contenant les infos du périodique
		
		if(!$value['tit1']) return 0;
		
		//niveau bib et hierarchique
		$value['niveau_biblio'] = "s";
		$value['niveau_hierar'] = "1";
		
		// mise à jour des index
		$ind_wew = $value['tit1']." ".$value['tit2']." ".$value['tit3']." ".$value['tit4'] ;
		$ind_sew = strip_empty_words($ind_wew) ;
		$ind_sew = " $ind_sew " ; 
		$value['index_sew'] = $ind_sew ; 
		$value['index_wew'] = $ind_wew ; 
		$value['index_serie'] = "" ; 
	
		// champ d'indexation libre
		if ($value['index_l']) $value['index_l']=clean_tags($value['index_l']);
		if ($value['index_l']) $value['index_matieres'] = " ".strip_empty_words($value['index_l'])." ";
		else $value['index_matieres']='';
		
		// notes
		if ($value['n_gen']) $value['index_n_gen'] = " ".strip_empty_words($value['n_gen'])." ";
		else $value['index_n_gen']='' ;
		
		if ($value['n_contenu']) $value['index_n_contenu'] = " ".strip_empty_words($value['n_contenu'])." ";
		else $value['index_n_contenu']='';
		
		if ($value['n_resume']) $value['index_n_resume'] = " ".strip_empty_words($value['n_resume'])." ";
		else $value['index_n_resume']='' ;
		
		$values = '';
		while(list($cle, $valeur) = each($value)) {
			$values ? $values .= ",$cle='$valeur'" : $values .= "$cle='$valeur'";
		}
		
		if($this->serial_id) {
			// modif
			$q = "UPDATE notices SET $values , update_date=sysdate() WHERE notice_id=".$this->serial_id;
			mysql_query($q, $dbh);
			
			audit::insert_modif (AUDIT_NOTICE, $this->serial_id) ;
			return $this->serial_id;
		} else {
			// create
			$q = "INSERT INTO notices SET $values , create_date=sysdate(), update_date=sysdate() ";
			mysql_query($q, $dbh);
			$this->serial_id = mysql_insert_id($dbh);
			audit::insert_creation (AUDIT_NOTICE, $this->serial_id) ;
			return $this->serial_id;
		}	
	}
	
	
	// fonction générant le form de saisie de notice chapeau
	function do_form() {
		global $msg;
		global $style;
		global $charset;
		global $ptab;
		global $serial_top_form;
		global $fonction_auteur;
		global $include_path, $class_path ;
		global $pmb_type_audit,$select_categ_prop ;
		global $value_deflt_fonction;
		global $thesaurus_mode_pmb, $thesaurus_classement_mode_pmb ;
		
		require_once("$class_path/author.class.php");
		$fonction = new marc_list('function');
		
		// mise à jour des flags de niveau hiérarchique
		if ($this->serial_id) $serial_top_form = str_replace('!!form_title!!', $msg[4004], $serial_top_form);
			else $serial_top_form = str_replace('!!form_title!!', $msg[4003], $serial_top_form);
		$serial_top_form = str_replace('!!b_level!!', $this->biblio_level, $serial_top_form);
		$serial_top_form = str_replace('!!h_level!!', $this->hierar_level, $serial_top_form);
		$serial_top_form = str_replace('!!id!!', $this->serial_id, $serial_top_form);
		
		// mise à jour de l'onglet 0
	 	$ptab[0] = str_replace('!!tit1!!',	htmlentities($this->tit1,ENT_QUOTES, $charset)	, $ptab[0]);
	 	$ptab[0] = str_replace('!!tit3!!',	htmlentities($this->tit3,ENT_QUOTES, $charset)	, $ptab[0]);
	 	$ptab[0] = str_replace('!!tit4!!',	htmlentities($this->tit4,ENT_QUOTES, $charset)	, $ptab[0]);
		
		$serial_top_form = str_replace('!!tab0!!', $ptab[0], $serial_top_form);
		
		// initialisation avec les paramètres du user :
		if (!$this->langues) {
			global $value_deflt_lang ;
			if ($value_deflt_lang) {
				$lang = new marc_list('lang');
				$this->langues[] = array( 
					'lang_code' => $value_deflt_lang,
					'langue' => $lang->table[$value_deflt_lang]
					) ;
				}
			}
	
		if (!$this->statut) {
			global $deflt_notice_statut ;
			if ($deflt_notice_statut) $this->statut = $deflt_notice_statut;
				else $this->statut = 1;
			}
		if (!$this->typdoc) {
			global $xmlta_doctype ;
			$this->typdoc = $xmlta_doctype ;
			}
		
		// mise à jour de l'onglet 1
		// constitution de la mention de responsabilité
		//$this->responsabilites
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			}
		if ($value_deflt_fonction && $auteur_0["id"]==0) $auteur_0["fonction"] = $value_deflt_fonction ;
		$ptab[1] = str_replace('!!aut0_id!!',			$auteur_0["id"], $ptab[1]);
		$ptab[1] = str_replace('!!aut0!!',				htmlentities($auteur->display,ENT_QUOTES, $charset), $ptab[1]);
		$ptab[1] = str_replace('!!f0_code!!',			$auteur_0["fonction"], $ptab[1]);
		$ptab[1] = str_replace('!!f0!!',				$fonction->table[$auteur_0["fonction"]], $ptab[1]);
	
		$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
		$max_aut1 = (count($as)) ;
		if ($max_aut1==0) $max_aut1=1;
		for ($i = 0 ; $i < $max_aut1 ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			if ($value_deflt_fonction && $auteur_1["id"]==0 && $i==0) $auteur_1["fonction"] = $value_deflt_fonction ;
			$ptab_aut_autres = str_replace('!!iaut!!', $i, $ptab[11]) ;
				
			$ptab_aut_autres = str_replace('!!aut1_id!!',			$auteur_1["id"], $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!aut1!!',				htmlentities($auteur->display,ENT_QUOTES, $charset), $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f1_code!!',			$auteur_1["fonction"], $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f1!!',				$fonction->table[$auteur_1["fonction"]], $ptab_aut_autres);
			$autres_auteurs .= $ptab_aut_autres ;
			}
		$ptab[1] = str_replace('!!max_aut1!!', $max_aut1, $ptab[1]);
		
		$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
		$max_aut2 = (count($as)) ;
		if ($max_aut2==0) $max_aut2=1;
		for ($i = 0 ; $i < $max_aut2 ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_2["id"]);
			if ($value_deflt_fonction && $auteur_2["id"]==0 && $i==0) $auteur_2["fonction"] = $value_deflt_fonction ;
			$ptab_aut_autres = str_replace('!!iaut!!', $i, $ptab[12]) ;
				
			$ptab_aut_autres = str_replace('!!aut2_id!!',			$auteur_2["id"], $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!aut2!!',				htmlentities($auteur->display,ENT_QUOTES, $charset), $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f2_code!!',			$auteur_2["fonction"], $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f2!!',				$fonction->table[$auteur_2["fonction"]], $ptab_aut_autres);
			$auteurs_secondaires .= $ptab_aut_autres ;
			}
		$ptab[1] = str_replace('!!max_aut2!!', $max_aut2, $ptab[1]);
		
		$ptab[1] = str_replace('!!autres_auteurs!!', $autres_auteurs, $ptab[1]);
		$ptab[1] = str_replace('!!auteurs_secondaires!!', $auteurs_secondaires, $ptab[1]);
		$serial_top_form = str_replace('!!tab1!!', $ptab[1], $serial_top_form);
		
		// mise à jour de l'onglet 2
		$ptab[2] = str_replace('!!ed1_id!!',	$this->ed1_id	, $ptab[2]);
		$ptab[2] = str_replace('!!ed1!!',		htmlentities($this->ed1,ENT_QUOTES, $charset)	, $ptab[2]);
		$ptab[2] = str_replace('!!ed2_id!!',	$this->ed2_id	, $ptab[2]);
		$ptab[2] = str_replace('!!ed2!!',		htmlentities($this->ed2,ENT_QUOTES, $charset)	, $ptab[2]);
		
		$serial_top_form = str_replace('!!tab2!!', $ptab[2], $serial_top_form);
	
		// mise à jour de l'onglet 30 (code)
		$ptab[30] = str_replace('!!cb!!',	htmlentities($this->code,ENT_QUOTES, $charset)	, $ptab[30]);
	
		$serial_top_form = str_replace('!!tab30!!', $ptab[30], $serial_top_form);
		$serial_top_form = str_replace('!!year!!', $this->year, $serial_top_form);
		
		// mise à jour de l'onglet 3 (notes)
		$ptab[3] = str_replace('!!n_gen!!',		htmlentities($this->n_gen,ENT_QUOTES, $charset)	, $ptab[3]);
		$ptab[3] = str_replace('!!n_contenu!!',		htmlentities($this->n_contenu,ENT_QUOTES, $charset)	, $ptab[3]);
		$ptab[3] = str_replace('!!n_resume!!',	htmlentities($this->n_resume,ENT_QUOTES, $charset)	, $ptab[3]);
		
		$serial_top_form = str_replace('!!tab3!!', $ptab[3], $serial_top_form);
		
		// mise à jour de l'onglet 4 
		// catégories
		if (sizeof($this->categories)==0) $max_categ = 1 ;
			else $max_categ = sizeof($this->categories) ; 
		for ($i = 0 ; $i < $max_categ ; $i++) {
			$categ_id = $this->categories[$i]["categ_id"] ;
			$categ = new category($categ_id);
			
			if ($i==0) $ptab_categ = str_replace('!!icateg!!', $i, $ptab[40]) ;
				else $ptab_categ = str_replace('!!icateg!!', $i, $ptab[401]) ;
				
			if ($thesaurus_mode_pmb && $categ->id) $nom_tesaurus='['.$categ->thes->getLibelle().'] ' ;
				else $nom_tesaurus='' ;
			$ptab_categ = str_replace('!!categ_id!!',			$categ_id, $ptab_categ);
			$ptab_categ = str_replace('!!categ_libelle!!',		htmlentities($nom_tesaurus.$categ->catalog_form,ENT_QUOTES, $charset), $ptab_categ);
			$categ_repetables .= $ptab_categ ;
			}
		$ptab[4] = str_replace('!!max_categ!!', $max_categ, $ptab[4]);
		$ptab[4] = str_replace('!!categories_repetables!!', $categ_repetables, $ptab[4]);
		
		// indexation interne
		$ptab[4] = str_replace('!!indexint_id!!',	$this->indexint		, $ptab[4]);
		$ptab[4] = str_replace('!!indexint!!',	htmlentities($this->indexint_lib,ENT_QUOTES,$charset)	, $ptab[4]);
		if ($this->indexint){
			$indexint = new indexint($this->indexint);
			if ($indexint->comment) $disp_indexint= $indexint->name." - ".$indexint->comment ;
			else $disp_indexint= $indexint->name ;
			if ($thesaurus_classement_mode_pmb) { // plusieurs classements/indexations décimales autorisés en parametrage
				if ($indexint->name_pclass) $disp_indexint="[".$indexint->name_pclass."] ".$disp_indexint;
			}
			$ptab[4] = str_replace('!!indexint!!', htmlentities($disp_indexint,ENT_QUOTES, $charset), $ptab[4]);
			$ptab[4] = str_replace('!!num_pclass!!', $indexint->id_pclass, $ptab[4]);
		} else {
			$ptab[4] = str_replace('!!indexint!!', '', $ptab[4]);
			$ptab[4] = str_replace('!!num_pclass!!', '', $ptab[4]);
		}
			
		// indexation libre
		$ptab[4] = str_replace('!!f_indexation!!', htmlentities($this->index_l,ENT_QUOTES, $charset), $ptab[4]);
		global $pmb_keyword_sep ;
		$sep="'$pmb_keyword_sep'";
		if (!$pmb_keyword_sep) $sep="' '";
		if(ord($pmb_keyword_sep)==0xa || ord($pmb_keyword_sep)==0xd) $sep=$msg['catalogue_saut_de_ligne'];
		$ptab[4] = str_replace("!!sep!!",htmlentities($sep,ENT_QUOTES, $charset),$ptab[4]);
		$serial_top_form = str_replace('!!tab4!!', $ptab[4], $serial_top_form);
	
		// mise à jour de l'onglet 5 : langues
		// langues répétables
		if (sizeof($this->langues)==0) 
			$max_lang = 1 ;
		else 
			$max_lang = sizeof($this->langues) ; 
		for ($i = 0 ; $i < $max_lang ; $i++) {
			if ($i) 
				$ptab_lang = str_replace('!!ilang!!', $i, $ptab[501]) ;
			else 
				$ptab_lang = str_replace('!!ilang!!', $i, $ptab[50]) ;
			if ( sizeof($this->langues)==0 ) { 
				$ptab_lang = str_replace('!!lang_code!!', '', $ptab_lang);
				$ptab_lang = str_replace('!!lang!!', '', $ptab_lang);		
			} else {
				$ptab_lang = str_replace('!!lang_code!!', $this->langues[$i]["lang_code"], $ptab_lang);
				$ptab_lang = str_replace('!!lang!!',htmlentities($this->langues[$i]["langue"],ENT_QUOTES, $charset), $ptab_lang);
			}
			$lang_repetables .= $ptab_lang ;
		}
		$ptab[5] = str_replace('!!max_lang!!', $max_lang, $ptab[5]);
		$ptab[5] = str_replace('!!langues_repetables!!', $lang_repetables, $ptab[5]);
	
		// langues originales répétables
		if (sizeof($this->languesorg)==0) 
			$max_langorg = 1 ;
		else 
			$max_langorg = sizeof($this->languesorg) ; 
		for ($i = 0 ; $i < $max_langorg ; $i++) {
			if ($i) 
				$ptab_lang = str_replace('!!ilangorg!!', $i, $ptab[511]) ;		
			else 
				$ptab_lang = str_replace('!!ilangorg!!', $i, $ptab[51]) ;
				
			if ( sizeof($this->languesorg)==0 ) { 
				$ptab_lang = str_replace('!!langorg_code!!', '', $ptab_lang);
				$ptab_lang = str_replace('!!langorg!!', '', $ptab_lang);		
			} else {
				$ptab_lang = str_replace('!!langorg_code!!', $this->languesorg[$i]["lang_code"], $ptab_lang);
				$ptab_lang = str_replace('!!langorg!!',htmlentities($this->languesorg[$i]["langue"],ENT_QUOTES, $charset), $ptab_lang);
			}
				$langorg_repetables .= $ptab_lang ;
		}
		$ptab[5] = str_replace('!!max_langorg!!', $max_langorg, $ptab[5]);
		$ptab[5] = str_replace('!!languesorg_repetables!!', $langorg_repetables, $ptab[5]);
	
		$serial_top_form = str_replace('!!tab5!!', $ptab[5], $serial_top_form);
		
		// mise à jour de l'onglet 6
	 	$ptab[6] = str_replace('!!lien!!',		htmlentities($this->lien,ENT_QUOTES, $charset)		, $ptab[6]);
	 	$ptab[6] = str_replace('!!eformat!!',	htmlentities($this->eformat,ENT_QUOTES, $charset)		, $ptab[6]);
		
		$serial_top_form = str_replace('!!tab6!!', $ptab[6], $serial_top_form);
		
		//Mise à jour de l'onglet 7
		$p_perso=new parametres_perso("notices");
		
		if (!$p_perso->no_special_fields) {
			$perso_=$p_perso->show_editable_fields($this->serial_id);
		
			$perso="";
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				$perso.="<div id='move_".$p["NAME"]."' movable='yes' title=\"".htmlentities($p["TITRE"],ENT_QUOTES, $charset)."\">
						<div class='row'><label for='".$p["NAME"]."' class='etiquette'>".$p["TITRE"]."</label></div>
						<div class='row'>".$p["AFF"]."</div>
						</div>";
			}
			$perso.=$perso_["CHECK_SCRIPTS"];
			$ptab[7]=str_replace("!!champs_perso!!",$perso,$ptab[7]);
		} else 
			$ptab[7]="\n<script>function check_form() { return true; }</script>\n";
		
		$serial_top_form = str_replace('!!tab7!!', $ptab[7], $serial_top_form);
	
		//Liens vers d'autres notices
		//Recuperation de la notice mere
		$relations="";
		$n_rel=0;
	
		for ($ir=0; $ir<count($this->notice_mere_id); $ir++) {
			if ($ir==0) $pattern_rel=$ptab[130]; else  $pattern_rel=$ptab[131];
			$pattern_rel=str_replace("!!notice_relations_id!!",$this->notice_mere_id[$ir],$pattern_rel);
			$pattern_rel=str_replace("!!notice_relations_libelle!!",htmlentities($this->notice_mere[$ir],ENT_QUOTES,$charset),$pattern_rel);
			$pattern_rel=str_replace("!!notice_relations_rank!!",$this->relation_rank[$ir],$pattern_rel);
			$pattern_rel=str_replace("!!n_rel!!",$ir,$pattern_rel);
			
			//Recuperation des types de relation
			$liste_type_relation=new marc_select("relationtypeup","f_rel_type_$ir",$this->relation_type[$ir]);
			$type_relation=$liste_type_relation->display;
			$pattern_rel=str_replace("!!f_notice_type_relations!!",$type_relation,$pattern_rel);
			$relations.=$pattern_rel;
			$n_rel++;
		}
		if (!$n_rel) {
			$pattern_rel=$ptab[130];
			$pattern_rel=str_replace("!!notice_relations_id!!","",$pattern_rel);
			$pattern_rel=str_replace("!!notice_relations_libelle!!","",$pattern_rel);
			$pattern_rel=str_replace("!!notice_relations_rank!!","0",$pattern_rel);
			$pattern_rel=str_replace("!!n_rel!!",$ir,$pattern_rel);
			//Recuperation des types de relation
			$liste_type_relation=new marc_select("relationtypeup","f_rel_type_0", $value_deflt_relation);
			$type_relation=$liste_type_relation->display;
			$pattern_rel=str_replace("!!f_notice_type_relations!!",$type_relation,$pattern_rel);
			$relations.=$pattern_rel;
			$n_rel=1;
		}
		
		//Nombre de relations
		$ptab[13]=str_replace("!!max_rel!!",$n_rel,$ptab[13]);
		
		//Liens multiples
		$ptab[13]=str_replace("!!notice_relations!!",$relations,$ptab[13]);
		$ptab[13] = str_replace('!!notice_id_no_replace!!', '', $ptab[13]);
		$serial_top_form = str_replace('!!tab11!!', $ptab[13],$serial_top_form);
	
		
		// champs de gestion
		$select_statut = gen_liste_multiple ("select id_notice_statut, gestion_libelle from notice_statut order by 2", "id_notice_statut", "gestion_libelle", "id_notice_statut", "form_notice_statut", "", $this->statut, "", "","","",0) ;
		$ptab[8] = str_replace('!!notice_statut!!', $select_statut, $ptab[8]);
		$ptab[8] = str_replace('!!commentaire_gestion!!',htmlentities($this->commentaire_gestion,ENT_QUOTES, $charset), $ptab[8]);
		$ptab[8] = str_replace('!!thumbnail_url!!',htmlentities($this->thumbnail_url,ENT_QUOTES, $charset), $ptab[8]);
		if($this->opac_visible_bulletinage) $opac_visible_bulletinage="checked";
		else $opac_visible_bulletinage="";
		$ptab[8] = str_replace('!!opac_visible_bulletinage!!',$opac_visible_bulletinage, $ptab[8]);
		$ptab[8] = str_replace('!!display_bulletinage!!',"", $ptab[8]);
		
		//affichage des formulaires des droits d'acces
		$rights_form = $this->get_rights_form();
		$ptab[8] = str_replace('<!-- rights_form -->', $rights_form, $ptab[8]);
		$serial_top_form = str_replace('!!tab8!!', $ptab[8],$serial_top_form);
	
/*		
		//affichage des formulaires des droits d'acces
		$rights_form = $this->get_rights_form();
		$ptab[14] = str_replace('<!-- rights_form -->', $rights_form, $ptab[14]);
		$serial_top_form = str_replace('!!tab14!!', $ptab[14],$serial_top_form);
*/
				
		// ajout des selecteurs
		$select_doc = new marc_select('doctype', 'typdoc', $this->typdoc, "get_pos(); initIt(); ajax_parse_dom();");
		$serial_top_form = str_replace('!!doc_type!!', $select_doc->display, $serial_top_form);
		
		// Ajout des localisations pour édition
		$select_loc="";
		global $PMBuserid, $pmb_form_editables;
		if ($PMBuserid==1 && $pmb_form_editables==1) {
			$req_loc="select idlocation,location_libelle from docs_location";
			$res_loc=mysql_query($req_loc);
			if (mysql_num_rows($res_loc)>1) {	
				$select_loc="<select name='grille_location' id='grille_location' style='display:none' onChange=\"get_pos();initIt(); if (inedit) move_parse_dom(relative);\">\n";
				$select_loc.="<option value='0'>Toutes les localisations</option>\n";
				while (($r=mysql_fetch_object($res_loc))) {
					$select_loc.="<option value='".$r->idlocation."'>".$r->location_libelle."</option>\n";
				}
				$select_loc.="</select>\n";
			}
		}	
		$serial_top_form=str_replace("!!location!!",$select_loc,$serial_top_form);
	
	
		if($this->serial_id) {
			$link_annul = "onClick=\"unload_off();history.go(-1);\"";
			if ($pmb_type_audit) 
				$link_audit =  "<input class='bouton' type='button' onClick=\"openPopUp('./audit.php?type_obj=1&object_id=$this->serial_id', 'audit_popup', 700, 500, -2, -2, '$select_categ_prop')\" title='$msg[audit_button]' value='$msg[audit_button]' />";
			else 
				$link_audit = "" ;
		} else {
				$link_annul = "onClick=\"unload_off();document.location='./catalog.php?categ=serials';\"";
				$link_audit = "" ;
		}
		
		$serial_top_form = str_replace('!!annul!!', $link_annul, $serial_top_form);
		$serial_top_form = str_replace('!!id_form!!', md5(microtime()), $serial_top_form);
		$serial_top_form = str_replace('!!link_audit!!', $link_audit, $serial_top_form);
		
		return $serial_top_form;
		
	}


	
	//creationformulaire des droits d'acces
	function get_rights_form() {
	
			global $dbh,$msg,$charset;
			global $gestion_acces_active,$gestion_acces_user_notice, $gestion_acces_empr_notice;
			global $gestion_acces_user_notice_def, $gestion_acces_empr_notice_def;
			global $PMBuserid;
			
			if ($gestion_acces_active!=1) return '';
			$ac = new acces();
			
			$form = '';
			$c_form = "<label class='etiquette'><!-- domain_name --></label>
						<div class='row'>
				    	<div class='colonne3'>".htmlentities($msg['dom_cur_prf'],ENT_QUOTES,$charset)."</div>
				    	<div class='colonne_suite'><!-- prf_rad --></div>
				    	</div>
				    	<div class='row'>
				    	<div class='colonne3'>".htmlentities($msg['dom_cur_rights'],ENT_QUOTES,$charset)."</div>
					    <div class='colonne_suite'><!-- r_rad --></div>
					    <div class='row'><!-- rights_tab --></div>
					    </div>";
				
			if($gestion_acces_user_notice==1) {
				
				$r_form=$c_form;
				$dom_1 = $ac->setDomain(1);	
				$r_form = str_replace('<!-- domain_name -->', htmlentities($dom_1->getComment('long_name'), ENT_QUOTES, $charset) ,$r_form);
				if($this->serial_id) {
	
					//profil ressource
					$def_prf=$dom_1->getComment('res_prf_def_lib');
					$res_prf=$dom_1->getResourceProfile($this->serial_id);
					$q=$dom_1->loadUsedResourceProfiles();
			
					//recuperation droits utilisateur
					$user_rights = $dom_1->getRights($PMBuserid,$this->serial_id,3);
					
					if($user_rights & 2) {
						$p_sel=gen_liste($q,'prf_id','prf_name', 'res_prf[1]', '', $res_prf, '0', $def_prf , '0', $def_prf );
						$p_rad = "<input type='radio' name='prf_rad[1]' value='R' ";
						if ($gestion_acces_user_notice_def!='1') $p_rad.= "checked='checked' ";
						$p_rad.= ">".htmlentities($msg['dom_rad_calc'],ENT_QUOTES,$charset)."</input><input type='radio' name='prf_rad[1]' value='C' ";
						if ($gestion_acces_user_notice_def=='1') $p_rad.= "checked='checked' ";
						$p_rad.= ">".htmlentities($msg['dom_rad_def'],ENT_QUOTES,$charset)." $p_sel</input>";
						$r_form = str_replace('<!-- prf_rad -->', $p_rad, $r_form);
					} else {
						$r_form = str_replace('<!-- prf_rad -->', htmlentities($dom_1->getResourceProfileName($res_prf), ENT_QUOTES, $charset), $r_form);
					}

					
					//droits/profils utilisateurs
					if($user_rights & 1) {
						$r_rad = "<input type='radio' name='r_rad[1]' value='R' ";
						if ($gestion_acces_user_notice_def!='1') $r_rad.= "checked='checked' ";
						$r_rad.= ">".htmlentities($msg['dom_rad_calc'],ENT_QUOTES,$charset)."</input><input type='radio' name='r_rad[1]' value='C' ";
						if ($gestion_acces_user_notice_def=='1') $r_rad.= "checked='checked' ";
						$r_rad.= ">".htmlentities($msg['dom_rad_def'],ENT_QUOTES,$charset)."</input>";
						$r_form = str_replace('<!-- r_rad -->', $r_rad, $r_form);
					}
					
					//recuperation profils utilisateurs
					$t_u=array();
					$t_u[0]= $dom_1->getComment('user_prf_def_lib');	//niveau par defaut
					$qu=$dom_1->loadUsedUserProfiles();
					$ru=mysql_query($qu, $dbh);
					if (mysql_num_rows($ru)) {
						while(($row=mysql_fetch_object($ru))) {
					        $t_u[$row->prf_id]= $row->prf_name;
						}
					}
	
					//recuperation des controles dependants de l'utilisateur 	
					$t_ctl=$dom_1->getControls(0);
					
					//recuperation des droits 
					$t_rights = $dom_1->getResourceRights($this->serial_id);
									
					if (count($t_u)) {
		
						$h_tab = "<div class='dom_div'><table class='dom_tab'><tr>";
						foreach($t_u as $k=>$v) {
							$h_tab.= "<th class='dom_col'>".htmlentities($v, ENT_QUOTES, $charset)."</th>";			
						}
						$h_tab.="</tr><!-- rights_tab --></table></div>";
						
						$c_tab = '<tr>';
						foreach($t_u as $k=>$v) {
								
							$c_tab.= "<td><table style='border:1px solid;'><!-- rows --></table></td>";
							$t_rows = "";
									
							foreach($t_ctl as $k2=>$v2) {
															
								$t_rows.="
									<tr>
										<td style='width:25px;' ><input type='checkbox' ";
								if ($t_rights[$k][$res_prf] & (pow(2,$k2-1))) {
									$t_rows.= "checked='checked' ";
								}
								if(($user_rights & 1)==0) $t_rows.="disabled='disabled' "; 
								$t_rows.= "/></td>
										<td>".htmlentities($v2, ENT_QUOTES, $charset)."</td>
									</tr>";
							}						
							$c_tab = str_replace('<!-- rows -->', $t_rows, $c_tab);
						}
						$c_tab.= "</tr>";
						
					}
					$h_tab = str_replace('<!-- rights_tab -->', $c_tab, $h_tab);
					$r_form=str_replace('<!-- rights_tab -->', $h_tab, $r_form);
					
				} else {
					$r_form = str_replace('<!-- prf_rad -->', htmlentities($msg['dom_prf_unknown'], ENT_QUOTES, $charset), $r_form);
					$r_form = str_replace('<!-- r_rad -->', htmlentities($msg['dom_rights_unknown'], ENT_QUOTES, $charset), $r_form);
				}
				$form.= $r_form;
				
			}
		
			if($gestion_acces_empr_notice==1) {
				
				$r_form=$c_form;
				$dom_2 = $ac->setDomain(2);	
				$r_form = str_replace('<!-- domain_name -->', htmlentities($dom_2->getComment('long_name'), ENT_QUOTES, $charset) ,$r_form);
				if($this->serial_id) {
					
					//profil ressource
					$def_prf=$dom_2->getComment('res_prf_def_lib');
					$res_prf=$dom_2->getResourceProfile($this->serial_id);
					$q=$dom_2->loadUsedResourceProfiles();
					
					//Recuperation droits generiques utilisateur
					$user_rights = $dom_2->getDomainRights(0,$res_prf);

					if($user_rights & 2) {
						$p_sel=gen_liste($q,'prf_id','prf_name', 'res_prf[2]', '', $res_prf, '0', $def_prf , '0', $def_prf );
						$p_rad = "<input type='radio' name='prf_rad[2]' value='R' ";
						if ($gestion_acces_empr_notice_def!='1') $p_rad.= "checked='checked' ";
						$p_rad.= ">".htmlentities($msg['dom_rad_calc'],ENT_QUOTES,$charset)."</input><input type='radio' name='prf_rad[2]' value='C' ";
						if ($gestion_acces_empr_notice_def=='1') $p_rad.= "checked='checked' ";
						$p_rad.= ">".htmlentities($msg['dom_rad_def'],ENT_QUOTES,$charset)." $p_sel</input>";
						$r_form=str_replace('<!-- prf_rad -->',$p_rad,$r_form);
					} else {
						$r_form = str_replace('<!-- prf_rad -->', htmlentities($dom_2->getResourceProfileName($res_prf), ENT_QUOTES, $charset), $r_form);
					}
					
					//droits/profils utilisateurs
					if($user_rights & 1) {
						$r_rad = "<input type='radio' name='r_rad[2]' value='R' ";
						if ($gestion_acces_empr_notice_def!='1') $r_rad.= "checked='checked' ";
						$r_rad.= ">".htmlentities($msg['dom_rad_calc'],ENT_QUOTES,$charset)."</input><input type='radio' name='r_rad[2]' value='C' ";
						if ($gestion_acces_empr_notice_def=='1') $r_rad.= "checked='checked' "; 
						$r_rad.= ">".htmlentities($msg['dom_rad_def'],ENT_QUOTES,$charset)."</input>";
						$r_form = str_replace('<!-- r_rad -->', $r_rad, $r_form);
					}
					
					//recuperation profils utilisateurs
					$t_u=array();
					$t_u[0]= $dom_2->getComment('user_prf_def_lib');	//niveau par defaut
					$qu=$dom_2->loadUsedUserProfiles();
					$ru=mysql_query($qu, $dbh);
					if (mysql_num_rows($ru)) {
						while(($row=mysql_fetch_object($ru))) {
					        $t_u[$row->prf_id]= $row->prf_name;
						}
					}
				
					//recuperation des controles dependants de l'utilisateur
					$t_ctl=$dom_2->getControls(0);
		
					//recuperation des droits 
					$t_rights = $dom_2->getResourceRights($this->serial_id);
									
					if (count($t_u)) {
		
						$h_tab = "<div class='dom_div'><table class='dom_tab'><tr>";
						foreach($t_u as $k=>$v) {
							$h_tab.= "<th style='dom_col'>".htmlentities($v, ENT_QUOTES, $charset)."</th>";			
						}
						$h_tab.="</tr><!-- rights_tab --></table></div>";
						
						$c_tab = '<tr>';
						foreach($t_u as $k=>$v) {
								
							$c_tab.= "<td><table style='border:1px solid;'><!-- rows --></table></td>";
							$t_rows = "";
									
							foreach($t_ctl as $k2=>$v2) {
															
								$t_rows.="
									<tr>
										<td style='width:25px;' ><input type='checkbox' name='chk_rights[2][".$k."][".$k2."]' value='1' ";
								if ($t_rights[$k][$res_prf] & (pow(2,$k2-1))) {
									$t_rows.= "checked='checked' ";
								}
								if(($user_rights & 1)==0) $t_rows.="disabled='disabled' ";
								$t_rows.="/></td>
										<td>".htmlentities($v2, ENT_QUOTES, $charset)."</td>
									</tr>";
							}						
							$c_tab = str_replace('<!-- rows -->', $t_rows, $c_tab);
						}
						$c_tab.= "</tr>";
						
					}
					$h_tab = str_replace('<!-- rights_tab -->', $c_tab, $h_tab);;
					$r_form=str_replace('<!-- rights_tab -->', $h_tab, $r_form);
					
				} else {
					$r_form = str_replace('<!-- prf_rad -->', htmlentities($msg['dom_prf_unknown'], ENT_QUOTES, $charset), $r_form);
					$r_form = str_replace('<!-- r_rad -->', htmlentities($msg['dom_rights_unknown'], ENT_QUOTES, $charset), $r_form);
				}
				$form.= $r_form;
				
			}
			return $form;
		}			

	
	// ---------------------------------------------------------------
	//		replace_form : affichage du formulaire de remplacement
	// ---------------------------------------------------------------
	function replace_form() {
		global $perio_replace;
		global $msg;
		global $include_path;
	
		// a compléter
		if(!$this->serial_id) {
			require_once("$include_path/user_error.inc.php");
			error_message($msg[161], $msg[162], 1, './catalog.php');
			return false;
			}
	
		$perio_replace=str_replace('!!old_perio_libelle!!', $this->tit1, $perio_replace);
		$perio_replace=str_replace('!!serial_id!!', $this->serial_id, $perio_replace);
		print $perio_replace;
	}
	
	// ---------------------------------------------------------------
	//		replace($by) : remplacement du périodique
	// ---------------------------------------------------------------
	function replace($by) {
	
		global $msg;
		global $dbh;
	
		if (($this->serial_id == $by) || (!$this->serial_id))  {
			return $msg[223];
		}
	
		// remplacement dans les bulletins
		$requete = "UPDATE bulletins SET bulletin_notice='$by' WHERE bulletin_notice='$this->serial_id' ";
		mysql_query($requete, $dbh);
	
		// remplacement dans notice relations => cas ou il existe des notices de bulletins
		$requete = "UPDATE notices_relations SET linked_notice='$by' WHERE linked_notice='$this->serial_id' ";
		mysql_query($requete, $dbh);
		
		// autres liens
		$requete = "UPDATE notices_relations SET num_notice='$by' WHERE num_notice='$this->serial_id' ";
		mysql_query($requete, $dbh);
		
		// remplacement des docs numériques
		$requete = "update explnum SET explnum_notice='$by' WHERE explnum_notice='$this->serial_id' " ;
		@mysql_query($requete, $dbh);
			
		// remplacement des etats de collections
		$requete = "update collections_state SET id_serial='$by' WHERE id_serial='$this->serial_id' " ;
		@mysql_query($requete, $dbh);	

		$this->serial_delete();
		
		return FALSE;
	}
	
	// suppression d'une notice chapeau, uniquement notice
	function serial_delete() {
		
		global $dbh;
	
		$requete = "SELECT bulletin_id,num_notice from bulletins WHERE bulletin_notice='".$this->serial_id."' ";
		$myQuery1 = mysql_query($requete, $dbh);
		if($myQuery1 && mysql_num_rows($myQuery1)) {
			while(($bul = mysql_fetch_object($myQuery1))) {				
				$bulletin=new bulletinage($bul->bulletin_id);
				$bulletin->delete();
			}	
		}
			
		// élimination des docs numériques
		$requete = "DELETE FROM explnum WHERE explnum_notice in ($this->serial_id)" ;
		@mysql_query($requete, $dbh);
	
		// suppression des entrées dans les caddies
		$query_caddie = "select caddie_id from caddie_content, caddie where type='NOTI' and object_id in ($this->serial_id) and caddie_id=idcaddie ";
		$result_caddie = @mysql_query($query_caddie, $dbh);
		while(($cad = mysql_fetch_object($result_caddie))) {
			$req_suppr_caddie="delete from caddie_content where caddie_id = '$cad->caddie_id' and object_id in ($this->serial_id) " ;
			@mysql_query($req_suppr_caddie, $dbh);
		}
	
		//élimination des champs persos
		$p_perso=new parametres_perso("notices");
		$p_perso->delete_values($this->serial_id);
	
		// suppression des audits
		audit::delete_audit (AUDIT_NOTICE, $this->serial_id) ;
	
		// suppression des categories
		$rqt_del = "delete from notices_categories where notcateg_notice='$this->serial_id' ";
		@mysql_query($rqt_del, $dbh);
	
		// suppression des bannettes
		$rqt_del = "delete from bannette_contenu where num_notice='$this->serial_id' ";
		@mysql_query($rqt_del, $dbh);
	
		// suppression des tags
		$rqt_del = "delete from tags where num_notice='$this->serial_id' ";
		@mysql_query($rqt_del, $dbh);
	
		// suppression des avis
		$rqt_del = "delete from avis where num_notice='$this->serial_id' ";
		@mysql_query($rqt_del, $dbh);
	
		//suppression des langues
		$query = "delete from notices_langues where num_notice='".$this->serial_id."' ";
		@mysql_query($query, $dbh);
		
		// suppression index global
		$query = "delete from notices_global_index where num_notice='".$this->serial_id."' ";
		@mysql_query($query, $dbh);
		
		// Effacement des occurences de la notice ds la table notices_mots_global_index :
		$requete = "DELETE FROM notices_mots_global_index WHERE id_notice=".$this->serial_id;
		@mysql_query($requete, $dbh);
	
		// liens entre notices
		$requete = "DELETE FROM notices_relations WHERE linked_notice='$this->serial_id' ";
		mysql_query($requete, $dbh);
		
		// autres liens entre notices
		$requete = "DELETE FROM  notices_relations WHERE num_notice='$this->serial_id' ";
		mysql_query($requete, $dbh);
		
		//suppression des droits d'acces user_notice
		$requete = "delete from acces_res_1 where res_num=".$this->serial_id;
		@mysql_query($requete, $dbh);	

		//suppression des droits d'acces empr_notice
		$requete = "delete from acces_res_2 where res_num=".$this->serial_id;
		@mysql_query($requete, $dbh);	
								
		// suppression des abonnements
		$requete = "SELECT abt_id from abts_abts WHERE num_notice='".$this->serial_id."' ";
		$result_abts = mysql_query($requete, $dbh);
		while(($abts = mysql_fetch_object($result_abts))) { 	
			$requete = "DELETE FROM abts_grille_abt WHERE num_abt=".$abts->abt_id;
			mysql_query($requete, $dbh);		
		}	
		$requete = "DELETE FROM abts_abts WHERE num_notice='".$this->serial_id."' ";
		mysql_query($requete, $dbh);	
		
		// suppression des modeles		
		$requete = "SELECT modele_id from abts_modeles WHERE num_notice='".$this->serial_id."' ";
		$result_modele = mysql_query($requete, $dbh);
		while(($modele = mysql_fetch_object($result_modele))) { 	
			$requete = "DELETE FROM abts_grille_modele WHERE num_modele=".$modele->modele_id;
			mysql_query($requete, $dbh);		
			// elimine les liens entre modele et abonnements
			$requete = "DELETE FROM abts_abts_modeles WHERE modele_id=".$modele->modele_id;
			mysql_query($requete, $dbh);
		}	
		$requete = "DELETE FROM abts_modeles WHERE num_notice='".$this->serial_id."' ";
		mysql_query($requete, $dbh);
		
		// Suppression des etats de collections
		$collstate=new collstate(0,$this->serial_id);
		$collstate->delete();	
		
		//si intégré depuis une source externe, on suprrime aussi la référence
		$query="delete from notices_externes where num_notice=".$this->serial_id;
		@mysql_query($query, $dbh);
		
		// on supprime la notice
		$requete = "DELETE FROM notices WHERE notice_id='".$this->serial_id."' ";
		mysql_query($requete, $dbh);
		$result = mysql_affected_rows($dbh);
		
		return $result;
	}

} // fin définition classe

/* ------------------------------------------------------------------------------------
        classe bulletinage : classe de gestion des bulletinages
--------------------------------------------------------------------------------------- */
class bulletinage extends serial {
	var $bulletin_id      = 0 ;  // id de ce bulletinage
	var $bulletin_titre   = '';  // titre propre du bulletin
	var $bulletin_numero  = '';  // mention de numéro sur la publication
	var $bulletin_notice  = 0 ;  // id notice parent = id du périodique relié
	var $bulletin_cb      = '';  // Code EAN13 (+ addon) du bulletin
	var $mention_date     = '';  // mention de date sur la publication au format texte libre
	var $date_date        = '';  // date de la publication au format date 
	var $aff_date_date    = '';  // date de la publication au format date correct pour affichage 
	var $display          = '';  // forme à afficher pour prêt, listes, etc...
	var $header 		  = '';  // forme du bulletin allégé pour l'affichage (résa)
	var $nb_analysis      = 0 ;  // nombre de notices de dépouillement
	var $bull_num_notice  = 0 ;  //Numéro de la notice liée
	
	//Notice de bulletin
	var $b_biblio_level    = 'b';       // niveau bibliographique
	var $b_hierar_level    = '2';       // niveau hiérarchique
	var $b_typdoc          = '';        // type UNIMARC du document
	var $b_code            = '';        // codebarre du périodique
	var $b_tit1            = '';        // titre propre
	var $b_tit3            = '';        // titre parallèle
	var $b_tit4            = '';        // complément du titre propre
	var $b_ed1_id          = 0;         // id de l'éditeur 1
	var $b_ed1             = '';        // forme affichable de l'éditeur 1
	var $b_ed2_id          = 0;         // id de l'éditeur 2
	var $b_ed2             = '';        // forme affichable de l'éditeur 2
	var $b_n_gen           = '';        // note générale
	var $b_n_contenu	   = '';		// note de contenu
	var $b_n_resume        = '';        // note de résumé
	var $b_categories =	array();// les categories
	var $b_indexint        = 0;         // id indexation interne
	var $b_indexint_lib    = '';        // libelle indexation interne
	var $b_index_l         = '';        // indexation libre
	var $b_langues = array();
	var $b_languesorg = array();
	var $b_lien            = '';        // URL associée
	var $b_eformat         = '';        // type de la ressource électronique
	var $b_responsabilites =	array("responsabilites" => array(),"auteurs" => array());  // les auteurs
	var $b_statut 		= 0 ; 			// statut 
	var $b_commentaire_gestion = '' ;
	var $b_thumbnail_url = '' ;
	var $b_npages = ''; 				//Nombre de pages
	var $b_ill = '';					//Illustration
	var $b_size = '';					//Taille
	var $b_accomp = '';					//Matériel d'accompagnement
	var $b_prix = '';					//Prix	
	
	
	// données de(s) exemplaire(s) : un tableau d'objets
	var $expl;
	// données des exemplaires numériques
	var $explnum;
	var $nbexplnum;
	
	// constructeur
	function bulletinage($bulletin_id, $serial_id=0, $link_explnum='',$localisation=0,$make_display=true) {
		global $dbh;
		global $pmb_droits_explr_localises, $explr_invisible;
			
		$this->bulletin_id = $bulletin_id;
		if($this->bulletin_id) $this->fetch_bulletin_data();
		
		if($serial_id) $this->bulletin_notice = $serial_id;
		$this->serial($this->bulletin_notice);
		
		// si le bulletin n'a pas de notice associée, son typedoc par défaut sera celui de la notice chapeau
		if (!$this->b_typdoc) $this->b_typdoc  = $this->typdoc;
		if($make_display){//Je ne crée la partie affichage que quand j'en ai besoin
			$this->make_display();
			$this->make_short_display();
		}
		
		// on récupère les données d'exemplaires liés
		$this->expl = array();
		if($this->bulletin_id) {
			$requete = "SELECT count(1) from analysis where analysis_bulletin='".$this->bulletin_id."'";
			$query_nb_analysis = mysql_query ($requete, $dbh);
			$this->nb_analysis = mysql_result ($query_nb_analysis, 0, 0) ;
			
			// visibilité des exemplaires:
			if ($pmb_droits_explr_localises && $explr_invisible) $where_expl_localises = " and expl_location not in ($explr_invisible)";
				else $where_expl_localises = "";
			if ($localisation > 0) $where_localisation =" and expl_location=$localisation ";
				else $where_localisation = "";
				
			$requete = "SELECT exemplaires.*, tdoc_libelle, section_libelle";
			$requete .= ", statut_libelle, location_libelle";
			$requete .= ", codestat_libelle, pret_flag ";
			$requete .= " FROM exemplaires, docs_type, docs_section, docs_statut, docs_location, docs_codestat ";
			$requete .= "  WHERE exemplaires.expl_bulletin=".$this->bulletin_id."$where_expl_localises $where_localisation";
			$requete .= " AND docs_type.idtyp_doc=exemplaires.expl_typdoc";
			$requete .= " AND docs_section.idsection=exemplaires.expl_section";
			$requete .= " AND docs_statut.idstatut=exemplaires.expl_statut";
			$requete .= " AND docs_location.idlocation=exemplaires.expl_location";
			$requete .= " AND docs_codestat.idcode=exemplaires.expl_codestat";	
			$myQuery = mysql_query($requete, $dbh);
			if(mysql_num_rows($myQuery)) {
				while(($expl = mysql_fetch_object($myQuery))) $this->expl[] = $expl;
		
				/* note : le tableau est constitué d'objet dont les propriétés sont :
								id exemplaire			expl_id;
								code-barre			expl_cb;
								notice				expl_notice;
								bulletinage			expl_bulletin;
								type doc			expl_typdoc;
								libelle type doc		tdoc_libelle;
								cote				expl_cote;
								section				expl_section;
								libelle section			section_libelle;
								statut				expl_statut;
								libelle statut			statut_libelle;
								localisation			expl_location;
								libelle localisation		location_libelle;
								code statistique		expl_codestat;
								libelle code_stat		codestat_libelle;
								date de dépot BDP par exemple		expl_date_depot;
								date de retour		expl_date_retour;
								note				expl_note;
								prix				expl_prix;
								owner				$expl->expl_owner;
				*/
				}
			$requete = "SELECT explnum.* FROM explnum WHERE explnum_bulletin='".$this->bulletin_id."' ";
			$myQuery = mysql_query($requete, $dbh);
			$this->nbexplnum = mysql_num_rows($myQuery) ;
			if($make_display && $this->nbexplnum){//Je ne crée la partie affichage que quand j'en ai besoin
				$this->explnum = show_explnum_per_notice(0, $this->bulletin_id, $link_explnum);
			}
		}
		return $this->bulletin_id;
	}
	
	// fabrication de la version affichable
	function make_display() {
		$this->display = $this->tit1;
		if($this->bulletin_numero) $this->display .= '. '.$this->bulletin_numero;
		// affichage de la mention de date utile : mention_date si existe, sinon date_date
		if ($this->mention_date) {
			$date_affichee = " (".$this->mention_date.")";
		} else if ($this->date_date) {
				$date_affichee = " [".$this->aff_date_date."]";
		} else { 
			$date_affichee = "" ;
		}
		$this->display .= $date_affichee;
		
		if ($this->bulletin_titre)	
			$this->display .= " : ".$this->bulletin_titre;
		if ($this->bulletin_cb)	
			$this->display .= ". ".$this->bulletin_cb;
		if ($this->bull_num_notice) { 
			$m_display=new mono_display($this->bull_num_notice,5);
			$this->display.="<blockquote>".gen_plus($m_display->notice_id,$m_display->header,$m_display->isbd)."</blockquote>";
		}
	}
	
	//fabrication de la version allégée pour l'affichage
	function make_short_display(){
		$this->header = $this->tit1;
		if($this->bulletin_numero) $this->header .= '. '.$this->bulletin_numero;
		// affichage de la mention de date utile : mention_date si existe, sinon date_date
		if ($this->mention_date) {
			$date_affichee = " (".$this->mention_date.")";
		} else if ($this->date_date) {
				$date_affichee = " [".$this->aff_date_date."]";
		} else { 
			$date_affichee = "" ;
		}
		$this->header .= $date_affichee;
		
	}
	
	// récupération des infos sur le bulletinage
	function fetch_bulletin_data() {
		global $dbh;
		global $msg;
		
		$myQuery = mysql_query("SELECT *, date_format(date_date, '".$msg["format_date"]."') as aff_date_date FROM bulletins WHERE bulletin_id='".$this->bulletin_id."' ", $dbh);
		
		if(mysql_num_rows($myQuery)) {
			$bulletin = mysql_fetch_object($myQuery);
			$this->bulletin_titre  = $bulletin->bulletin_titre;
			$this->bulletin_notice = $bulletin->bulletin_notice;
			$this->bulletin_numero = $bulletin->bulletin_numero;
			$this->bulletin_cb     = $bulletin->bulletin_cb;
			$this->mention_date    = $bulletin->mention_date;
			$this->date_date       = $bulletin->date_date;
			$this->aff_date_date   = $bulletin->aff_date_date;
			$this->bull_num_notice = $bulletin->num_notice;
			
			
			global $fonction_auteur;
		
			$myQueryBull = mysql_query("SELECT * FROM notices WHERE notice_id='".$this->bull_num_notice."' LIMIT 1", $dbh);
			$myBull = mysql_fetch_object($myQueryBull);
			
			// type du document
			$this->b_typdoc  = $myBull->typdoc;
			// statut de la notice
			$this->b_statut  = $myBull->statut;
			$this->b_commentaire_gestion  = $myBull->commentaire_gestion;
			$this->b_thumbnail_url		  = $myBull->thumbnail_url;
			
			// code-barre
			$this->b_code = $myBull->code;
			
			// mentions de titre
			$this->b_tit1 = $myBull->tit1;
			$this->b_tit3 = $myBull->tit3;
			$this->b_tit4 = $myBull->tit4;
							
			// libelle des auteurs
			$this->b_responsabilites = get_notice_authors($this->bull_num_notice) ;
			
			// libelle des éditeurs
			if($myBull->ed1_id) {
				$this->b_ed1_id = $myBull->ed1_id;
				$editeur = new editeur($this->b_ed1_id);
				$this->b_ed1 = $editeur->display;
			}
			if($myBull->ed2_id) {
				$this->b_ed2_id = $myBull->ed2_id;
				$editeur = new editeur($this->b_ed2_id);
				$this->b_ed2 = $editeur->display;
			}
			
			//Collation
			$this->b_npages = $myBull->npages;
			$this->b_ill = $myBull->ill;
			$this->b_size = $myBull->size;
			$this->b_accomp = $myBull->accomp;
			$this->b_prix = $myBull->prix;
			
			// zone des notes
			$this->b_n_gen = $myBull->n_gen;
			$this->b_n_contenu = $myBull->n_contenu;
			$this->b_n_resume = $myBull->n_resume;
			
			// mise à jour des catégories
			$this->b_categories = get_notice_categories($this->bull_num_notice) ;
				
			// indexation interne
			if($myBull->indexint) {
				$this->b_indexint = $myBull->indexint;
				$indexint = new indexint($this->b_indexint);
				if ($indexint->comment) $this->b_indexint_lib = $indexint->name." - ".$indexint->comment ; 
				else $this->b_indexint_lib = $indexint->name ;
			}
			
			// indexation libre
			$this->b_index_l = $myBull->index_l;
			
			// libelle des langues
			$this->b_langues	= get_notice_langues($this->bull_num_notice, 0) ;	// langues de la publication
			$this->b_languesorg	= get_notice_langues($this->bull_num_notice, 1) ; // langues originales
			
			// lien vers une ressource électronique
			$this->b_lien = $myBull->lien;
			$this->b_eformat = $myBull->eformat;
		}
		
			
		if ($this->date_date=="0000-00-00") {
			$this->date_date = "";
			$this->aff_date_date = "";
		}
			
		return mysql_num_rows($myQuery);
	}
	
	// fonction de mise à jour d'une entrée MySQL de bulletinage
	function update($value,$dont_update_bul=false) {
		global $dbh;
		
		if(is_array($value)) {
			$this->bulletin_titre  = $value['bul_titre'];
			$this->bulletin_numero = $value['bul_no'];
			$this->bulletin_cb     = $value['bul_cb'];
			$this->mention_date    = $value['bul_date'];
			
			// Note YPR : à revoir
			if ($value['date_date']) $this->date_date = $value['date_date'];
				else $this->date_date = today();
						
			// construction de la requete :
			$data = "bulletin_titre='".$this->bulletin_titre."'";
			$data .= ",bulletin_numero='".$this->bulletin_numero."'";
			$data .= ",bulletin_cb='".$this->bulletin_cb."'";
			$data .= ",mention_date='".$this->mention_date."'";
			$data .= ",date_date='".$this->date_date."'";
			$data .= ",index_titre=' ".strip_empty_words($this->bulletin_titre)." '";
					
			if(!$this->bulletin_id) {
				// si c'est une creation, on ajoute l'id du parent la date et on cree la notice !
				$data .= ",bulletin_notice='".$this->bulletin_notice."'";
				// fabrication de la requete finale
				$requete = "INSERT INTO bulletins SET $data";
				$myQuery = mysql_query($requete, $dbh);
				$insert_last_id = mysql_insert_id($dbh) ; 
				audit::insert_creation (AUDIT_BULLETIN, $insert_last_id) ;
				$this->bulletin_id=$insert_last_id ;
			} else {
				$requete ="UPDATE bulletins SET $data WHERE bulletin_id='".$this->bulletin_id."' LIMIT 1";
				$myQuery = mysql_query($requete, $dbh);
				audit::insert_modif (AUDIT_BULLETIN, $this->bulletin_id) ;
				$date_tmp=$value['date_parution'];
				$requete="UPDATE notices SET date_parution='".$date_tmp."', year='".$value['year']."' WHERE notice_id in (SELECT analysis_notice FROM analysis WHERE analysis_bulletin=$this->bulletin_id)";
				mysql_query($requete,$dbh);
				$date_tmp='';
			}
		} else return;
		
		global $include_path;
		
		if (!$dont_update_bul) {
			// formatage des valeurs de $value
			// $value est un tableau contenant les infos du périodique
			if(!$value['tit1']) {
				$this->bull_num_notice=0;
				//return;
			}
			 
			//Nettoyage des infos bulletin
			unset($value['bul_titre']);
			unset($value['bul_no']);
			unset($value['bul_cb']);
			unset($value['bul_date']);
			unset($value['date_date']);
			
			// mise à jour des index
			$ind_wew = $value['tit1']." ".$value['tit2']." ".$value['tit3']." ".$value['tit4'] ;
			$ind_sew = strip_empty_words($ind_wew) ;
			$ind_sew = " $ind_sew " ; 
			$value['index_sew'] = $ind_sew ; 
			$value['index_wew'] = $ind_wew ; 
			$value['index_serie'] = "" ; 
			
			if ($value['index_l']) $value['index_l']=clean_tags($value['index_l']);
	
			// champ d'indexation libre
			if ($value['index_l']) $value['index_matieres'] = " ".strip_empty_words($value['index_l'])." ";
				else $value['index_matieres']='';
			
			// notes
			if ($value['n_gen']) $value['index_n_gen'] = " ".strip_empty_words($value['n_gen'])." ";
				else $value['index_n_gen']='' ;
			if ($value['n_contenu']) $value['index_n_contenu'] = " ".strip_empty_words($value['n_contenu'])." ";
				else $value['index_n_contenu']='';
			if ($value['n_resume']) $value['index_n_resume'] = " ".strip_empty_words($value['n_resume'])." ";
				else $value['index_n_resume']='' ;
			
			if(is_array($value['aut']) && $value['aut'][0]['id']) $value['aut']='aut_exist';
			else $value['aut']='';	
			
			if(is_array($value['categ']) && $value['categ'][0]['id']) $value['categ']='categ_exist';
			else $value['categ']='';	
	
			//type de document
			//$value['typdoc']=$value['typdoc'];
			$empty = "";
			if ($value['force_empty'])
				$empty = "perso";
			unset($value['force_empty']);
				
			$values = '';
			while(list($cle, $valeur) = each($value)) {
				if (($cle!="statut")&&($cle!="tit1")&&($cle!="niveau_hierar")&&($cle!="niveau_biblio")&&($cle!="index_sew")&&($cle!="index_wew")&&($cle!="typdoc")&&($cle!="date_parution")&&($cle!="year")) {
					if ((($cle=="indexint")&&($valeur))||($cle!="indexint"))
						$empty.=$valeur;
				}
				if($cle=='aut' || $cle=='categ'){
					$values.='';
				} else{
					$values ? $values .= ",$cle='$valeur'" : $values .= "$cle='$valeur'";	
				}			
			}
			
			if($this->bull_num_notice) {
				
				if ($empty) {
					// modif
					mysql_query("UPDATE notices SET $values , update_date=sysdate() WHERE notice_id=".$this->bull_num_notice, $dbh);
					audit::insert_modif (AUDIT_NOTICE, $this->bull_num_notice) ;
				} else {
					notice::del_notice($this->bull_num_notice);
					$this->bull_num_notice="";
					mysql_query("update bulletins set num_notice=0 where bulletin_id=".$this->bulletin_id);
				}
				return $this->bulletin_id;
				
			} else {
				
				// create
				if ($empty) {
					mysql_query("INSERT INTO notices SET $values , create_date=sysdate(), update_date=sysdate()  ", $dbh);
					$this->bull_num_notice = mysql_insert_id($dbh);
					audit::insert_creation (AUDIT_NOTICE, $this->bull_num_notice) ;

					//Mise à jour du bulletin
					$requete="update bulletins set num_notice=".$this->bull_num_notice." where bulletin_id=".$this->bulletin_id;
					mysql_query($requete);
					//Mise à jour des liens bulletin -> notice mère
					$requete="insert into notices_relations (num_notice,linked_notice,relation_type,rank) values(".$this->bull_num_notice.",".$this->serial_id.",'b',1)";
					mysql_query($requete);
				}
				return $this->bulletin_id;
			}
			
		} else {
			
			if ($this->bull_num_notice) {
				//Mise à jour du bulletin
				$requete="update bulletins,notices set num_notice=".$this->bull_num_notice.",bulletin_titre=tit1 where bulletin_id=".$this->bulletin_id." and notice_id=".$this->bull_num_notice;
				mysql_query($requete);
				
				//Mise à jour des liens bulletin -> notice mere
				$requete="insert into notices_relations (num_notice,linked_notice,relation_type,rank) values(".$this->bull_num_notice.",".$this->serial_id.",'b',1)";
				mysql_query($requete);
				//Recherche des articles
				$requete="select analysis_notice from analysis where analysis_bulletin=".$this->bulletin_id;
				$resultat_analysis=mysql_query($requete);
				$n=1;
				while (($r_a=mysql_fetch_object($resultat_analysis))) {
					$requete="insert into notices_relations (num_notice,linked_notice,relation_type,rank) values(".$r_a->analysis_notice.",".$this->bull_num_notice.",'a',$n)";
					mysql_query($requete);
					$n++;
				}
			}
			return $this->bulletin_id;
		}
	}
	
	// fonction d'affichage du formulaire de mise à jour
	function do_form() {
		global $serial_bul_form;
		global $msg;
		global $charset ;
		global $pmb_type_audit,$select_categ_prop ;
		
		//Notice
		global $ptab,$ptab_bul;
		global $fonction_auteur;
		global $include_path, $class_path ;
		global $pmb_type_audit,$select_categ_prop ;
		global $value_deflt_fonction;
		global $thesaurus_mode_pmb, $thesaurus_classement_mode_pmb ;
		
		require_once("$class_path/author.class.php");
		$fonction = new marc_list('function');
		
		// mise à jour des flags de niveau hiérarchique
		//if ($this->serial_id) $serial_bul_form = str_replace('!!form_title!!', $msg[4004], $serial_bul_form);
		//	else $serial_bul_form = str_replace('!!form_title!!', $msg[4003], $serial_bul_form);
		$serial_bul_form = str_replace('!!b_level!!', $this->b_biblio_level, $serial_bul_form);
		$serial_bul_form = str_replace('!!h_level!!', $this->b_hierar_level, $serial_bul_form);
		$serial_bul_form = str_replace('!!id!!', $this->bull_num_notice, $serial_bul_form);
		
		// mise à jour de l'onglet 0
	 	//$ptab[0] = str_replace('!!tit1!!',	htmlentities($this->tit1,ENT_QUOTES, $charset)	, $ptab[0]);
	 	$ptab_bul[0] = str_replace('!!tit3!!',	htmlentities($this->b_tit3,ENT_QUOTES, $charset)	, $ptab_bul[0]);
	 	$ptab_bul[0] = str_replace('!!tit4!!',	htmlentities($this->b_tit4,ENT_QUOTES, $charset)	, $ptab_bul[0]);
		
		$serial_bul_form = str_replace('!!tab0!!', $ptab_bul[0], $serial_bul_form);
		
		// initialisation avec les paramètres du user :
		if (!$this->b_langues) {
			global $value_deflt_lang ;
			if ($value_deflt_lang) {
				$lang = new marc_list('lang');
				$this->b_langues[] = array( 
					'lang_code' => $value_deflt_lang,
					'langue' => $lang->table[$value_deflt_lang]
					) ;
			}
		}
	
		if (!$this->b_statut) {
			global $deflt_notice_statut ;
			if ($deflt_notice_statut) $this->b_statut = $deflt_notice_statut;
				else $this->b_statut = 1;
		}
		if (!$this->b_typdoc) {
			global $xmlta_doctype ;
			$this->b_typdoc = $xmlta_doctype ;
		}
		
		// ajout des selecteurs
		$select_doc = new marc_select('doctype', 'typdoc', $this->b_typdoc, "get_pos(); initIt(); ajax_parse_dom();");
		$serial_bul_form = str_replace('!!doc_type!!', $select_doc->display, $serial_bul_form);
		
		// Ajout des localisations pour édition
		$select_loc="";
		global $PMBuserid, $pmb_form_editables;
		if ($PMBuserid==1 && $pmb_form_editables==1) {
			$req_loc="select idlocation,location_libelle from docs_location";
			$res_loc=mysql_query($req_loc);
			if (mysql_num_rows($res_loc)>1) {	
				$select_loc="<select name='grille_location' id='grille_location' style='display:none' onChange=\"get_pos();initIt(); if (inedit) move_parse_dom(relative);\">\n";
				$select_loc.="<option value='0'>Toutes les localisations</option>\n";
				while (($r=mysql_fetch_object($res_loc))) {
					$select_loc.="<option value='".$r->idlocation."'>".$r->location_libelle."</option>\n";
				}
				$select_loc.="</select>\n";
			}
		}	
		$serial_bul_form=str_replace("!!location!!",$select_loc,$serial_bul_form);
	
		// mise à jour de l'onglet 1
		// constitution de la mention de responsabilité
		//$this->responsabilites
		$as = array_search ("0", $this->b_responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->b_responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			}
		if ($value_deflt_fonction && $auteur_0["id"]==0) $auteur_0["fonction"] = $value_deflt_fonction ;
		$ptab[1] = str_replace('!!aut0_id!!',			$auteur_0["id"], $ptab[1]);
		$ptab[1] = str_replace('!!aut0!!',				htmlentities($auteur->display,ENT_QUOTES, $charset), $ptab[1]);
		$ptab[1] = str_replace('!!f0_code!!',			$auteur_0["fonction"], $ptab[1]);
		$ptab[1] = str_replace('!!f0!!',				$fonction->table[$auteur_0["fonction"]], $ptab[1]);
	
		$as = array_keys ($this->b_responsabilites["responsabilites"], "1" ) ;
		$max_aut1 = (count($as)) ;
		if ($max_aut1==0) $max_aut1=1;
		for ($i = 0 ; $i < $max_aut1 ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->b_responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			if ($value_deflt_fonction && $auteur_1["id"]==0 && $i==0) $auteur_1["fonction"] = $value_deflt_fonction ;
			$ptab_aut_autres = str_replace('!!iaut!!', $i, $ptab[11]) ;
				
			$ptab_aut_autres = str_replace('!!aut1_id!!',			$auteur_1["id"], $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!aut1!!',				htmlentities($auteur->display,ENT_QUOTES, $charset), $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f1_code!!',			$auteur_1["fonction"], $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f1!!',				$fonction->table[$auteur_1["fonction"]], $ptab_aut_autres);
			$autres_auteurs .= $ptab_aut_autres ;
			}
		$ptab[1] = str_replace('!!max_aut1!!', $max_aut1, $ptab[1]);
		
		$as = array_keys ($this->b_responsabilites["responsabilites"], "2" ) ;
		$max_aut2 = (count($as)) ;
		if ($max_aut2==0) $max_aut2=1;
		for ($i = 0 ; $i < $max_aut2 ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->b_responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_2["id"]);
			if ($value_deflt_fonction && $auteur_2["id"]==0 && $i==0) $auteur_2["fonction"] = $value_deflt_fonction ;
			$ptab_aut_autres = str_replace('!!iaut!!', $i, $ptab[12]) ;
				
			$ptab_aut_autres = str_replace('!!aut2_id!!',			$auteur_2["id"], $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!aut2!!',				htmlentities($auteur->display,ENT_QUOTES, $charset), $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f2_code!!',			$auteur_2["fonction"], $ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f2!!',				$fonction->table[$auteur_2["fonction"]], $ptab_aut_autres);
			$auteurs_secondaires .= $ptab_aut_autres ;
			}
		$ptab[1] = str_replace('!!max_aut2!!', $max_aut2, $ptab[1]);
		
		$ptab[1] = str_replace('!!autres_auteurs!!', $autres_auteurs, $ptab[1]);
		$ptab[1] = str_replace('!!auteurs_secondaires!!', $auteurs_secondaires, $ptab[1]);
		$serial_bul_form = str_replace('!!tab1!!', $ptab[1], $serial_bul_form);
		
		// mise à jour de l'onglet 2
		/*$ptab[2] = str_replace('!!ed1_id!!',	$this->ed1_id	, $ptab[2]);
		$ptab[2] = str_replace('!!ed1!!',		htmlentities($this->ed1,ENT_QUOTES, $charset)	, $ptab[2]);
		$ptab[2] = str_replace('!!ed2_id!!',	$this->ed2_id	, $ptab[2]);
		$ptab[2] = str_replace('!!ed2!!',		htmlentities($this->ed2,ENT_QUOTES, $charset)	, $ptab[2]);
		
		$serial_bul_form = str_replace('!!tab2!!', $ptab[2], $serial_bul_form);*/
	
		// mise à jour de l'onglet 30 (code)
		$ptab[30] = str_replace('!!cb!!',	htmlentities($this->b_code,ENT_QUOTES, $charset)	, $ptab[30]);
	
		$serial_bul_form = str_replace('!!tab30!!', $ptab[30], $serial_bul_form);
		
		// mise à jour de l'onglet 3 (notes)
		$ptab[3] = str_replace('!!n_gen!!',		htmlentities($this->b_n_gen,ENT_QUOTES, $charset)	, $ptab[3]);
		$ptab[3] = str_replace('!!n_contenu!!',	htmlentities($this->b_n_contenu,ENT_QUOTES, $charset)	, $ptab[3]);
		$ptab[3] = str_replace('!!n_resume!!',	htmlentities($this->b_n_resume,ENT_QUOTES, $charset)	, $ptab[3]);
		
		$serial_bul_form = str_replace('!!tab3!!', $ptab[3], $serial_bul_form);
		
		// mise à jour de l'onglet 4 
		// catégories
		if (sizeof($this->b_categories)==0) $max_categ = 1 ;
			else $max_categ = sizeof($this->b_categories) ; 
		for ($i = 0 ; $i < $max_categ ; $i++) {
			$categ_id = $this->b_categories[$i]["categ_id"] ;
			$categ = new category($categ_id);
			
			if ($i==0) $ptab_categ = str_replace('!!icateg!!', $i, $ptab[40]) ;
				else $ptab_categ = str_replace('!!icateg!!', $i, $ptab[401]) ;
				
			if ($thesaurus_mode_pmb && $categ->id) $nom_tesaurus='['.$categ->thes->getLibelle().'] ' ;
				else $nom_tesaurus='' ;
			$ptab_categ = str_replace('!!categ_id!!',			$categ_id, $ptab_categ);
			$ptab_categ = str_replace('!!categ_libelle!!',		htmlentities($nom_tesaurus.$categ->catalog_form,ENT_QUOTES, $charset), $ptab_categ);
			$categ_repetables .= $ptab_categ ;
			}
		$ptab[4] = str_replace('!!max_categ!!', $max_categ, $ptab[4]);
		$ptab[4] = str_replace('!!categories_repetables!!', $categ_repetables, $ptab[4]);
		
		// indexation interne
		$ptab[4] = str_replace('!!indexint_id!!',	$this->b_indexint		, $ptab[4]);
		$ptab[4] = str_replace('!!indexint!!',	htmlentities($this->b_indexint_lib,ENT_QUOTES,$charset)	, $ptab[4]);
		if ($this->indexint){
			$indexint = new indexint($this->indexint);
			if ($indexint->comment) $disp_indexint= $indexint->name." - ".$indexint->comment ;
			else $disp_indexint= $indexint->name ;
			if ($thesaurus_classement_mode_pmb) { // plusieurs classements/indexations décimales autorisés en parametrage
				if ($indexint->name_pclass) $disp_indexint="[".$indexint->name_pclass."] ".$disp_indexint;
			}
			$ptab[4] = str_replace('!!indexint!!', htmlentities($disp_indexint,ENT_QUOTES, $charset), $ptab[4]);
			$ptab[4] = str_replace('!!num_pclass!!', $indexint->id_pclass, $ptab[4]);
		} else {
			$ptab[4] = str_replace('!!indexint!!', '', $ptab[4]);
			$ptab[4] = str_replace('!!num_pclass!!', '', $ptab[4]);
		}
	
		// indexation libre
		$ptab[4] = str_replace('!!f_indexation!!', htmlentities($this->b_index_l,ENT_QUOTES, $charset), $ptab[4]);
		global $pmb_keyword_sep ;
		$sep="'$pmb_keyword_sep'";
		if (!$pmb_keyword_sep) $sep="' '";
		if(ord($pmb_keyword_sep)==0xa || ord($pmb_keyword_sep)==0xd) $sep=$msg['catalogue_saut_de_ligne'];
		$ptab[4] = str_replace("!!sep!!",htmlentities($sep,ENT_QUOTES, $charset),$ptab[4]);
		$serial_bul_form = str_replace('!!tab4!!', $ptab[4], $serial_bul_form);
	
		// Collation
		$ptab[41] = str_replace("!!npages!!", htmlentities($this->b_npages,ENT_QUOTES, $charset), $ptab[41]);
		$ptab[41] = str_replace("!!ill!!", htmlentities($this->b_ill,ENT_QUOTES, $charset), $ptab[41]);
		$ptab[41] = str_replace("!!size!!", htmlentities($this->b_size,ENT_QUOTES, $charset), $ptab[41]);
		$ptab[41] = str_replace("!!accomp!!", htmlentities($this->b_accomp,ENT_QUOTES, $charset), $ptab[41]);
		$ptab[41] = str_replace("!!prix!!", htmlentities($this->b_prix,ENT_QUOTES, $charset), $ptab[41]);
		$serial_bul_form = str_replace('!!tab41!!', $ptab[41], $serial_bul_form);
	
		// mise à jour de l'onglet 5 : langues
		// langues répétables
		if (sizeof($this->b_langues)==0) $max_lang = 1 ;
			else $max_lang = sizeof($this->b_langues) ; 
		for ($i = 0 ; $i < $max_lang ; $i++) {
			if ($i) $ptab_lang = str_replace('!!ilang!!', $i, $ptab[501]) ;
				else $ptab_lang = str_replace('!!ilang!!', $i, $ptab[50]) ;
			if ( sizeof($this->b_langues)==0 ) { 
				$ptab_lang = str_replace('!!lang_code!!', '', $ptab_lang);
				$ptab_lang = str_replace('!!lang!!', '', $ptab_lang);		
				} else {
					$ptab_lang = str_replace('!!lang_code!!', $this->b_langues[$i]["lang_code"], $ptab_lang);
					$ptab_lang = str_replace('!!lang!!',htmlentities($this->b_langues[$i]["langue"],ENT_QUOTES, $charset), $ptab_lang);
					}
			$lang_repetables .= $ptab_lang ;
			}
		$ptab[5] = str_replace('!!max_lang!!', $max_lang, $ptab[5]);
		$ptab[5] = str_replace('!!langues_repetables!!', $lang_repetables, $ptab[5]);
	
		// langues originales répétables
		if (sizeof($this->b_languesorg)==0) $max_langorg = 1 ;
			else $max_langorg = sizeof($this->b_languesorg) ; 
		for ($i = 0 ; $i < $max_langorg ; $i++) {
			if ($i) $ptab_lang = str_replace('!!ilangorg!!', $i, $ptab[511]) ;
				else $ptab_lang = str_replace('!!ilangorg!!', $i, $ptab[51]) ;
			if ( sizeof($this->b_languesorg)==0 ) { 
				$ptab_lang = str_replace('!!langorg_code!!', '', $ptab_lang);
				$ptab_lang = str_replace('!!langorg!!', '', $ptab_lang);		
				} else {
					$ptab_lang = str_replace('!!langorg_code!!', $this->b_languesorg[$i]["lang_code"], $ptab_lang);
					$ptab_lang = str_replace('!!langorg!!',htmlentities($this->b_languesorg[$i]["langue"],ENT_QUOTES, $charset), $ptab_lang);
					}
			$langorg_repetables .= $ptab_lang ;
			}
		$ptab[5] = str_replace('!!max_langorg!!', $max_langorg, $ptab[5]);
		$ptab[5] = str_replace('!!languesorg_repetables!!', $langorg_repetables, $ptab[5]);
	
		$serial_bul_form = str_replace('!!tab5!!', $ptab[5], $serial_bul_form);
		
		// mise à jour de l'onglet 6
	 	$ptab[6] = str_replace('!!lien!!',		htmlentities($this->b_lien,ENT_QUOTES, $charset)		, $ptab[6]);
	 	$ptab[6] = str_replace('!!eformat!!',	htmlentities($this->b_eformat,ENT_QUOTES, $charset)		, $ptab[6]);
		
		$serial_bul_form = str_replace('!!tab6!!', $ptab[6], $serial_bul_form);
		
		//Mise à jour de l'onglet 7
		$p_perso=new parametres_perso("notices");
		
		if (!$p_perso->no_special_fields) {
			$perso_=$p_perso->show_editable_fields($this->bull_num_notice);
		
			$perso="";
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				$perso.="<div id='move_".$p["NAME"]."' movable='yes' title=\"".htmlentities($p["TITRE"],ENT_QUOTES, $charset)."\">
						<div class='row'><label for='".$p["NAME"]."' class='etiquette'>".$p["TITRE"]."</label></div>
						<div class='row'>".$p["AFF"]."</div>
						</div>";
			}
			$perso.=$perso_["CHECK_SCRIPTS"];
			$ptab[7]=str_replace("!!champs_perso!!",$perso,$ptab[7]);
		} else 
			$ptab[7]="\n<script>function check_form() { return true; }</script>\n";
		$serial_bul_form = str_replace('!!tab7!!', $ptab[7], $serial_bul_form);
	
		// champs de gestion
		$select_statut = gen_liste_multiple ("select id_notice_statut, gestion_libelle from notice_statut order by 2", "id_notice_statut", "gestion_libelle", "id_notice_statut", "form_notice_statut", "", $this->b_statut, "", "","","",0) ;
		$ptab[8] = str_replace('!!notice_statut!!', $select_statut, $ptab[8]);
		$ptab[8] = str_replace('!!commentaire_gestion!!',htmlentities($this->b_commentaire_gestion,ENT_QUOTES, $charset), $ptab[8]);
		$ptab[8] = str_replace('!!thumbnail_url!!',htmlentities($this->b_thumbnail_url,ENT_QUOTES, $charset), $ptab[8]);
		
		$display_opac_bulletinage = " style='display:none' ";
		$ptab[8] = str_replace('!!display_bulletinage!!',$display_opac_bulletinage, $ptab[8]);
		
		//affichage des formulaires des droits d'acces
		$rights_form = $this->get_rights_form();
		$ptab[8] = str_replace('<!-- rights_form -->', $rights_form, $ptab[8]);
		$serial_bul_form = str_replace('!!tab8!!', $ptab[8], $serial_bul_form);
		
		/*if($this->serial_id) {
			$link_annul = "./catalog.php?categ=serials&sub=view&serial_id=".$this->serial_id;
			if ($pmb_type_audit) $link_audit =  "<input class='bouton' type='button' onClick=\"window.open('./audit.php?type_obj=1&object_id=$this->serial_id', 'audit_popup', '$select_categ_prop')\" title='$msg[audit_button]' value='$msg[audit_button]' />";
				else $link_audit = "" ;
		} else {
			$link_annul = "./catalog.php?categ=serials";
			$link_audit = "" ;
		}*/

		//Bulletin
		if($this->bulletin_id) {
			$link_annul = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!';
			$serial_bul_form = str_replace('!!form_title!!', $msg[4006], $serial_bul_form);
			$date_date_formatee = formatdate($this->date_date);
			if ($pmb_type_audit) 
				$link_audit =  "<input class='bouton' type='button' onClick=\"openPopUp('./audit.php?type_obj=3&object_id=$this->bulletin_id', 'audit_popup', 700, 500, -2, -2, '$select_categ_prop')\" title='$msg[audit_button]' value='$msg[audit_button]' />";
			else 
				$link_audit = "" ;
		} else {
			$link_annul = './catalog.php?categ=serials&sub=view&serial_id=!!serial_id!!';
			$serial_bul_form = str_replace('!!form_title!!', $msg[4005], $serial_bul_form);
			$this->date_date = today();
			$date_date_formatee = "";
			$link_audit = "" ;
		}
		$serial_bul_form = str_replace('!!annul!!',     $link_annul,            $serial_bul_form);			 
		$serial_bul_form = str_replace('!!serial_id!!', $this->serial_id,       $serial_bul_form);
		$serial_bul_form = str_replace('!!bul_id!!',    $this->bulletin_id,     $serial_bul_form);
		$serial_bul_form = str_replace('!!bul_titre!!',htmlentities($this->bulletin_titre,ENT_QUOTES, $charset),$serial_bul_form);
		$serial_bul_form = str_replace('!!bul_no!!',    htmlentities($this->bulletin_numero,ENT_QUOTES, $charset), $serial_bul_form);
		$serial_bul_form = str_replace('!!bul_date!!',htmlentities($this->mention_date,ENT_QUOTES, $charset),$serial_bul_form);
		$serial_bul_form = str_replace('!!bul_cb!!',$this->bulletin_cb,     $serial_bul_form);
		
		$date_clic = "onClick=\"openPopUp('./select.php?what=calendrier&caller=notice&date_caller=".str_replace('-', '', $this->date_date)."&param1=date_date&param2=date_date_lib&auto_submit=NO&date_anterieure=YES', 'date_date', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"  ";
		$date_date = "<input type='hidden' name='date_date' value='".str_replace('-','', $this->date_date)."' />
				<input class='saisie-10em' type='text' name='date_date_lib' value='".$date_date_formatee."' />
				<input class='bouton' type='button' name='date_date_lib_bouton' value='".$msg["bouton_calendrier"]."' ".$date_clic." />";
		$serial_bul_form = str_replace('!!date_date!!', $date_date, $serial_bul_form);
		$serial_bul_form = str_replace('!!link_audit!!', $link_audit, $serial_bul_form);
		//$serial_bul_form = str_replace('caller=notice',"caller=serial_bul_form",$serial_bul_form);
		//$serial_bul_form = str_replace('document.notice',"document.serial_bul_form",$serial_bul_form);
		return $serial_bul_form;
	}

	
	//creationformulaire des droits d'acces
	function get_rights_form() {
	
		global $dbh,$msg,$charset;
		global $gestion_acces_active, $gestion_acces_empr_notice;
		global $gestion_acces_empr_notice_def;
		
		
		if ($gestion_acces_active!=1) return '';
		$ac = new acces();
		
		$form = '';
		$c_form = "<label class='etiquette'><!-- domain_name --></label>
					<div class='row'>
			    	<div class='colonne3'>".htmlentities($msg['dom_cur_prf'],ENT_QUOTES,$charset)."</div>
			    	<div class='colonne_suite'><!-- prf_rad --></div>
			    	</div>
			    	<div class='row'>
			    	<div class='colonne3'>".htmlentities($msg['dom_cur_rights'],ENT_QUOTES,$charset)."</div>
				    <div class='colonne_suite'><!-- r_rad --></div>
				    <div class='row'><!-- rights_tab --></div>
				    </div>";
			
	
		if($gestion_acces_empr_notice==1) {
			
			$r_form=$c_form;
			$dom_2 = $ac->setDomain(2);	
			$r_form = str_replace('<!-- domain_name -->', htmlentities($dom_2->getComment('long_name'), ENT_QUOTES, $charset) ,$r_form);
			if($this->bull_num_notice) {
				
				//profil ressource
				$def_prf=$dom_2->getComment('res_prf_def_lib');
				$res_prf=$dom_2->getResourceProfile($this->bull_num_notice);
				$q=$dom_2->loadUsedResourceProfiles();
				
				//Recuperation droits generiques utilisateur
				$user_rights = $dom_2->getDomainRights(0,$res_prf);

				if($user_rights & 2) {
					$p_sel=gen_liste($q,'prf_id','prf_name', 'res_prf[2]', '', $res_prf, '0', $def_prf , '0', $def_prf );
					$p_rad = "<input type='radio' name='prf_rad[2]' value='R' ";
					if ($gestion_acces_empr_notice_def!='1') $p_rad.= "checked='checked' ";
					$p_rad.= ">".htmlentities($msg['dom_rad_calc'],ENT_QUOTES,$charset)."</input><input type='radio' name='prf_rad[2]' value='C' ";
					if ($gestion_acces_empr_notice_def=='1') $p_rad.= "checked='checked' ";
					$p_rad.= ">".htmlentities($msg['dom_rad_def'],ENT_QUOTES,$charset)." $p_sel</input>";
					$r_form=str_replace('<!-- prf_rad -->',$p_rad,$r_form);
				} else {
					$r_form = str_replace('<!-- prf_rad -->', htmlentities($dom_2->getResourceProfileName($res_prf), ENT_QUOTES, $charset), $r_form);
				}
				
				//droits/profils utilisateurs
				if($user_rights & 1) {
					$r_rad = "<input type='radio' name='r_rad[2]' value='R' ";
					if ($gestion_acces_empr_notice_def!='1') $r_rad.= "checked='checked' ";
					$r_rad.= ">".htmlentities($msg['dom_rad_calc'],ENT_QUOTES,$charset)."</input><input type='radio' name='r_rad[2]' value='C' ";
					if ($gestion_acces_empr_notice_def=='1') $r_rad.= "checked='checked' ";
					$r_rad.= ">".htmlentities($msg['dom_rad_def'],ENT_QUOTES,$charset)."</input>";
					$r_form = str_replace('<!-- r_rad -->', $r_rad, $r_form);
				}
				
				//recuperation profils utilisateurs
				$t_u=array();
				$t_u[0]= $dom_2->getComment('user_prf_def_lib');	//niveau par defaut
				$qu=$dom_2->loadUsedUserProfiles();
				$ru=mysql_query($qu, $dbh);
				if (mysql_num_rows($ru)) {
					while(($row=mysql_fetch_object($ru))) {
				        $t_u[$row->prf_id]= $row->prf_name;
					}
				}
			
				//recuperation des controles dependants de l'utilisateur
				$t_ctl=$dom_2->getControls(0);
	
				//recuperation des droits 
				$t_rights = $dom_2->getResourceRights($this->bull_num_notice);
								
				if (count($t_u)) {
	
					$h_tab = "<div class='dom_div'><table class='dom_tab'><tr>";
					foreach($t_u as $k=>$v) {
						$h_tab.= "<th class='dom_col'>".htmlentities($v, ENT_QUOTES, $charset)."</th>";			
					}
					$h_tab.="</tr><!-- rights_tab --></table></div>";
					
					$c_tab = '<tr>';
					foreach($t_u as $k=>$v) {
							
						$c_tab.= "<td><table style='border:1px solid;'><!-- rows --></table></td>";
						$t_rows = "";
								
						foreach($t_ctl as $k2=>$v2) {
														
							$t_rows.="
								<tr>
									<td style='width:25px;' ><input type='checkbox' name='chk_rights[2][".$k."][".$k2."]' value='1' ";
							if ($t_rights[$k][$res_prf] & (pow(2,$k2-1))) {
								$t_rows.= "checked='checked' ";
							}
							if(($user_rights & 1)==0) $t_rows.="disabled='disabled' ";
							$t_rows.="/></td>
									<td>".htmlentities($v2, ENT_QUOTES, $charset)."</td>
								</tr>";
						}						
						$c_tab = str_replace('<!-- rows -->', $t_rows, $c_tab);
					}
					$c_tab.= "</tr>";
					
				}
				$h_tab = str_replace('<!-- rights_tab -->', $c_tab, $h_tab);;
				$r_form=str_replace('<!-- rights_tab -->', $h_tab, $r_form);
				
			} else {
				$r_form = str_replace('<!-- prf_rad -->', htmlentities($msg['dom_prf_unknown'], ENT_QUOTES, $charset), $r_form);
				$r_form = str_replace('<!-- r_rad -->', htmlentities($msg['dom_rights_unknown'], ENT_QUOTES, $charset), $r_form);
			}
			$form.= $r_form;
			
		}
		return $form;
	}			
		
		
	function delete_analysis () {
	
		global $dbh;
		if($this->bulletin_id) {
			$requete = "SELECT analysis_notice FROM analysis WHERE analysis_bulletin=".$this->bulletin_id;
			$myQuery2 = mysql_query($requete, $dbh);
			while(($dep = mysql_fetch_object($myQuery2))) {
				$ana=new analysis($dep->analysis_notice);
				$ana->analysis_delete();
			}
			
			
		}
	}
	
	// ---------------------------------------------------------------
	//		replace_form : affichage du formulaire de remplacement
	// ---------------------------------------------------------------
	function replace_form() {
		global $bulletin_replace;
		global $msg,$dbh,$charset;
		global $include_path;
	
		if(!$this->bulletin_id) {
			require_once("$include_path/user_error.inc.php");
			error_message($msg[161], $msg[162], 1, './catalog.php');
			return false;
		}
		$requete = "SELECT analysis_notice FROM analysis WHERE analysis_bulletin=".$this->bulletin_id;
		$myQuery2 = mysql_query($requete, $dbh);
		if( mysql_num_rows($myQuery2)) {
			$del_depouillement="<label class='etiquette' for='del'>".$msg['replace_bulletin_checkbox']."</label><input value='1' yes='' name='del' id='del' type='checkbox' checked>";
		}		
		$bulletin_replace=str_replace('!!old_bulletin_libelle!!',$this->bulletin_numero." [".formatdate($this->date_date)."] ".htmlentities($this->mention_date,ENT_QUOTES, $charset)." ". htmlentities($this->bulletin_titre,ENT_QUOTES, $charset), $bulletin_replace);
		$bulletin_replace=str_replace('!!bul_id!!', $this->bulletin_id, $bulletin_replace);
		$bulletin_replace=str_replace('!!serial_id!!', $this->serial_id, $bulletin_replace);
		$bulletin_replace=str_replace('!!del_depouillement!!', $del_depouillement, $bulletin_replace);
		print $bulletin_replace;
	}
	
	// ---------------------------------------------------------------
	//		replace($by) : remplacement du périodique
	// ---------------------------------------------------------------
	function replace($by,$del_article=0) {
		global $msg;
		global $dbh;
	
		// traitement des dépouillements du bulletin
		if($del_article) {
			// suppression des notices de dépouillement
			$this->delete_analysis();				
		} else {	
			// sinon on ratache les dépouillements existants
			$requete = "UPDATE analysis SET analysis_bulletin=$by where analysis_bulletin=".$this->bulletin_id;
			@mysql_query($requete, $dbh);
		}
		// ratachement des exemplaires
		$requete = "UPDATE exemplaires SET expl_bulletin=$by WHERE expl_bulletin=".$this->bulletin_id;
		@mysql_query($requete, $dbh);
		
		// élimination des docs numériques
		$requete = "UPDATE explnum SET explnum_bulletin=$by WHERE explnum_bulletin=".$this->bulletin_id;
		@mysql_query($requete, $dbh);
						
		$this->delete();
		return false;
	}
	// Suppression de bulletin
	function delete() {
		global $dbh;
		
		//suppression des notices de dépouillement
		$this->delete_analysis();
		
		//suppression des exemplaires
		$req_expl = "select expl_id from exemplaires where expl_bulletin = '$this->bulletin_id' " ;
		
		$result_expl = @mysql_query($req_expl, $dbh);
		while(($expl = mysql_fetch_object($result_expl))) {
			exemplaire::del_expl($expl->expl_id);		
		}
	
		// expl numériques 	
		$req_explNum = "select explnum_id from explnum where explnum_bulletin=".$this->bulletin_id." ";
		$result_explNum = @mysql_query($req_explNum, $dbh);
		while(($explNum = mysql_fetch_object($result_explNum))) {
			$myExplNum = new explnum($explNum->explnum_id);
			$myExplNum->delete();		
		}		
		
		$query_caddie = "select caddie_id from caddie_content, caddie where type='BULL' and object_id ='$this->bulletin_id' and caddie_id=idcaddie ";
		$result_caddie = @mysql_query($query_caddie, $dbh);
		while(($cad = @mysql_fetch_object($result_caddie))) {
			$req_suppr_caddie="delete from caddie_content where caddie_id = '$cad->caddie_id' and object_id=$this->bulletin_id " ;
			mysql_query($req_suppr_caddie, $dbh);
		}
		
		// Suppression des résas du bulletin
		$requete = "DELETE FROM resa WHERE resa_idbulletin=".$this->bulletin_id;
		mysql_query($requete, $dbh);
		
		// Suppression des transferts_demande			
		$requete = "DELETE FROM transferts_demande using transferts_demande, transferts WHERE num_transfert=id_transfert and num_bulletin=".$this->bulletin_id;
		mysql_query($requete, $dbh);
		// Suppression des transferts
		$requete = "DELETE FROM transferts WHERE num_bulletin=".$this->bulletin_id;
		mysql_query($requete, $dbh);
					
		//suppression de la notice du bulletin
		$requete="select num_notice from bulletins where bulletin_id=".$this->bulletin_id;
		$res_nbul=mysql_query($requete);
		if (mysql_num_rows($res_nbul)) {
			$num_notice=mysql_result($res_nbul,0,0);
			if ($num_notice) {
				notice::del_notice($num_notice);
				//si intégré depuis une source externe, on suprrime aussi la référence
				$query="delete from notices_externes where num_notice=".$num_notice;
				@mysql_query($query, $dbh);
			}
		}			
	
		// Suppression de ce bulletin
		$requete = "DELETE FROM bulletins WHERE bulletin_id=".$this->bulletin_id;
		mysql_query($requete, $dbh);
		audit::delete_audit (AUDIT_BULLETIN, $this->bulletin_id) ;	
	}

} // fin définition classe

// mark dep

/* ------------------------------------------------------------------------------------
        classe analysis : classe de gestion des dépouillements
--------------------------------------------------------------------------------------- */
class analysis extends bulletinage {
	
	var $analysis_id		= 0;     // id de ce dépouillement
	var $id_bulletinage		= 0;     // id du bulletinage contenant ce dépouillement
	var $analysis_biblio_level	= 'a';   // niveau bibliographique
	var $analysis_hierar_level	= '2';   // niveau hiérarchique
	var $analysis_typdoc		= '';   // type de document (imprimé par défaut)
	var $analysis_tit1		= '';    // titre propre
	var $analysis_tit3		= '';    // titre parallèle
	var $analysis_tit4		= '';    // complément du titre propre
	var $analysis_n_gen		= '';    // note générale
	var $analysis_n_contenu = '';	 // note de contenu
	var $analysis_n_resume		= '';    // note de résumé
	var $analysis_categories =	array(); // les categories
	var $analysis_indexint		= 0;     // id indexint
	var $analysis_indexint_lib	= '';    // libelle indexint
	var $analysis_index_l		= '';    // indexation libre
	var $analysis_eformat  		= '';    // format de la ressource
	var $analysis_langues = array();
	var $analysis_languesorg = array();
	var $analysis_lien		= '';    // lien vers une ressource électronique
	var $action			= '';    // cible du formulaire généré par la méthode do_form
	var $analysis_pages		= '';    // mention de pagination
	var $responsabilites_dep =	array("responsabilites" => array(),"auteurs" => array());  // les auteurs
	var $analysis_statut = 0 ;
	var $analysis_commentaire_gestion = '' ;
	var $analysis_thumbnail_url = '' ;
	
	// constructeur
	function analysis($analysis_id, $bul_id=0) {
		// param : l'article hérite-t-il de l'URL de la notice chapeau
		global $pmb_serial_link_article;
		$this->analysis_id = $analysis_id;
		if ($bul_id) $this->id_bulletinage = $bul_id;
		
		if ($this->analysis_id) $this->fetch_analysis_data();
		
		// récupération des infos du bulletinage parent
		$this->bulletinage($this->id_bulletinage);
	
		// si c'est une création, on renseigne les valeurs héritées de la notice chapeau
		if (!$this->analysis_id) {
			$this->analysis_langues = $this->langues;
			$this->analysis_languesorg = $this->languesorg;
			$this->analysis_statut = $this->statut;
			// Héritage du lien de la notice chapeau
			if ($pmb_serial_link_article) {
				$this->analysis_lien = $this->lien;
				$this->analysis_eformat = $this->eformat;
				}
			}
		// afin d'avoir forcément un typdoc
		if(!$this->analysis_typdoc){
			if ($this->b_typdoc) $this->analysis_typdoc = $this->b_typdoc;
			else $this->analysis_typdoc = $this->typdoc;
		}
		return $this->analysis_id;
		}
	
	// récupération des infos en base
	function fetch_analysis_data() {
		global $dbh;
		global $fonction_auteur;
		
		$myQuery = mysql_query("SELECT * FROM notices WHERE notice_id='".$this->analysis_id."' LIMIT 1", $dbh);
		$myAnalysis = mysql_fetch_object($myQuery);
		
		// type du document
		$this->analysis_typdoc  = $myAnalysis->typdoc;
		// statut
		$this->analysis_statut  = $myAnalysis->statut;
		$this->analysis_commentaire_gestion = $myAnalysis->commentaire_gestion ;
		$this->analysis_thumbnail_url	    = $myAnalysis->thumbnail_url ;
	
		// mentions de titre
		$this->analysis_tit1 = $myAnalysis->tit1;
		$this->analysis_tit2 = $myAnalysis->tit2;
		$this->analysis_tit3 = $myAnalysis->tit3;
		$this->analysis_tit4 = $myAnalysis->tit4;
		
		// libelle des auteurs
		$this->responsabilites_dep = get_notice_authors($this->analysis_id) ;
		
		// Mention de pagination
		$this->analysis_pages = $myAnalysis->npages;
		
		// zone des notes
		$this->analysis_n_gen = $myAnalysis->n_gen;
		$this->analysis_n_contenu = $myAnalysis->n_contenu;
		$this->analysis_n_resume = $myAnalysis->n_resume;
		
		// mise à jour des catégories
		$this->analysis_categories = get_notice_categories($this->analysis_id) ;
	
		// indexation interne
		if($myAnalysis->indexint) {
			$this->analysis_indexint = $myAnalysis->indexint;
			$indexint = new indexint($this->analysis_indexint);
			if ($indexint->comment) $this->analysis_indexint_lib = $indexint->name." - ".$indexint->comment ; 
				else $this->analysis_indexint_lib = $indexint->name ;
			}
		
		// indexation libre
		$this->analysis_index_l = $myAnalysis->index_l;
		
		// libelle des langues
		$this->analysis_langues	= get_notice_langues($this->analysis_id, 0) ;	// langues de la publication
		$this->analysis_languesorg	= get_notice_langues($this->analysis_id, 1) ; // langues originales
		
		// lien vers une ressource électronique
		$this->analysis_lien = $myAnalysis->lien;
		if($this->analysis_lien) $this->analysis_eformat = $myAnalysis->eformat;
		else $this->analysis_eformat ="";
		
		return $myQuery->nbr_rows;
		}
	
	// génération du form de saisie
	function analysis_form($notice_type=false) {
		global $style;
		global $msg;
		global $pdeptab;
		global $analysis_top_form;
		global $fonction_auteur;
	 	global $charset;
		global $include_path, $class_path ;
		global $pmb_type_audit,$select_categ_prop ;
		global $value_deflt_fonction;
		global $thesaurus_mode_pmb, $thesaurus_classement_mode_pmb ;
		
		require_once("$class_path/author.class.php");
		$fonction = new marc_list('function');
		
		// inclusion de la feuille de style des expandables
		print $style;
		
		// mise à jour des flags de niveau hiérarchique
		$select_doc = new marc_select('doctype', 'typdoc', $this->analysis_typdoc, "get_pos(); initIt(); ajax_parse_dom();");
		$analysis_top_form = str_replace('!!doc_type!!', $select_doc->display, $analysis_top_form);
		//$analysis_top_form = str_replace('!!doc_type!!', $this->analysis_typdoc, $analysis_top_form);
		$analysis_top_form = str_replace('!!b_level!!', $this->analysis_biblio_level, $analysis_top_form);
		$analysis_top_form = str_replace('!!h_level!!', $this->analysis_hierar_level, $analysis_top_form);
		$analysis_top_form = str_replace('!!id!!', $this->serial_id, $analysis_top_form);
		
		// mise à jour de l'onglet 0
	 	$pdeptab[0] = str_replace('!!tit1!!',	htmlentities($this->analysis_tit1,ENT_QUOTES, $charset)	, $pdeptab[0]);
	 	$pdeptab[0] = str_replace('!!tit2!!',	htmlentities($this->analysis_tit2,ENT_QUOTES, $charset)	, $pdeptab[0]);
	 	$pdeptab[0] = str_replace('!!tit3!!',	htmlentities($this->analysis_tit3,ENT_QUOTES, $charset)	, $pdeptab[0]);
	 	$pdeptab[0] = str_replace('!!tit4!!',	htmlentities($this->analysis_tit4,ENT_QUOTES, $charset)	, $pdeptab[0]);
		
		$analysis_top_form = str_replace('!!tab0!!', $pdeptab[0], $analysis_top_form);
		
		// initialisation avec les paramètres du user :
		if (!$this->analysis_langues) {
			global $value_deflt_lang ;
			if ($value_deflt_lang) {
				$lang = new marc_list('lang');
				$this->analysis_langues[] = array( 
					'lang_code' => $value_deflt_lang,
					'langue' => $lang->table[$value_deflt_lang]
					) ;
				}
			}
	
		// mise à jour de l'onglet 1
		// constitution de la mention de responsabilité
		//$this->responsabilites
		$as = array_search ("0", $this->responsabilites_dep["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites_dep["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			}
		if ($value_deflt_fonction && $auteur_0["id"]==0) $auteur_0["fonction"] = $value_deflt_fonction ;
		$pdeptab[1] = str_replace('!!aut0_id!!',		$auteur_0["id"], $pdeptab[1]);
		$pdeptab[1] = str_replace('!!aut0!!',			htmlentities($auteur->display,ENT_QUOTES, $charset), $pdeptab[1]);
		$pdeptab[1] = str_replace('!!f0_code!!',		$auteur_0["fonction"], $pdeptab[1]);
		$pdeptab[1] = str_replace('!!f0!!',				$fonction->table[$auteur_0["fonction"]], $pdeptab[1]);
	
		$as = array_keys ($this->responsabilites_dep["responsabilites"], "1" ) ;
		$max_aut1 = (count($as)) ;
		if ($max_aut1==0) $max_aut1=1;
		for ($i = 0 ; $i < $max_aut1 ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites_dep["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			if ($value_deflt_fonction && $auteur_1["id"]==0 && $i==0) $auteur_1["fonction"] = $value_deflt_fonction ;
			$ptab_aut_autres = str_replace('!!iaut!!',		$i,																	$pdeptab[11]) ;
				
			$ptab_aut_autres = str_replace('!!aut1_id!!',	$auteur_1["id"],													$ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!aut1!!',		htmlentities($auteur->display,ENT_QUOTES, $charset),	$ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f1_code!!',	$auteur_1["fonction"],												$ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f1!!',		$fonction->table[$auteur_1["fonction"]],							$ptab_aut_autres);
			$autres_auteurs .= $ptab_aut_autres ;
		}
		$pdeptab[1] = str_replace('!!max_aut1!!', $max_aut1, $pdeptab[1]);
		
		$as = array_keys ($this->responsabilites_dep["responsabilites"], "2" ) ;
		$max_aut2 = (count($as)) ;
		if ($max_aut2==0) $max_aut2=1;
		for ($i = 0 ; $i < $max_aut2 ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->responsabilites_dep["auteurs"][$indice] ;
			$auteur = new auteur($auteur_2["id"]);
			if ($value_deflt_fonction && $auteur_2["id"]==0 && $i==0) $auteur_2["fonction"] = $value_deflt_fonction ;
			$ptab_aut_autres = str_replace('!!iaut!!',		$i,																	$pdeptab[12]) ;
				
			$ptab_aut_autres = str_replace('!!aut2_id!!',	$auteur_2["id"],													$ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!aut2!!',		htmlentities($auteur->display,ENT_QUOTES, $charset),	$ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f2_code!!',	$auteur_2["fonction"],												$ptab_aut_autres);
			$ptab_aut_autres = str_replace('!!f2!!',		$fonction->table[$auteur_2["fonction"]],							$ptab_aut_autres);
			$auteurs_secondaires .= $ptab_aut_autres ;
		}
		$pdeptab[1] = str_replace('!!max_aut2!!',				$max_aut2,				$pdeptab[1]);
		
		$pdeptab[1] = str_replace('!!autres_auteurs!!',			$autres_auteurs,		$pdeptab[1]);
		$pdeptab[1] = str_replace('!!auteurs_secondaires!!',	$auteurs_secondaires,	$pdeptab[1]);
		$analysis_top_form = str_replace('!!tab1!!', $pdeptab[1], $analysis_top_form);
	
		// mise à jour de l'onglet 2
	 	$pdeptab[2] = str_replace('!!pages!!',	htmlentities($this->analysis_pages,ENT_QUOTES, $charset)	, $pdeptab[2]);
		
		$analysis_top_form = str_replace('!!tab2!!', $pdeptab[2], $analysis_top_form);
		
		// mise à jour de l'onglet 3 (notes)
	 	$pdeptab[3] = str_replace('!!n_gen!!',		htmlentities($this->analysis_n_gen,		ENT_QUOTES, $charset), $pdeptab[3]);
	 	$pdeptab[3] = str_replace('!!n_contenu!!',		htmlentities($this->analysis_n_contenu,		ENT_QUOTES, $charset), $pdeptab[3]);
	 	$pdeptab[3] = str_replace('!!n_resume!!',	htmlentities($this->analysis_n_resume,	ENT_QUOTES, $charset), $pdeptab[3]);
		
		$analysis_top_form = str_replace('!!tab3!!', $pdeptab[3], $analysis_top_form);
		
		// mise à jour de l'onglet 4
		// catégories
		if (sizeof($this->analysis_categories)==0) $max_categ = 1 ;
			else $max_categ = sizeof($this->analysis_categories) ; 
			
		for ($i = 0 ; $i < $max_categ ; $i++) {
			$categ_id = $this->analysis_categories[$i]["categ_id"] ;
			$categ = new category($categ_id);
			
			if ($i==0) $ptab_categ = str_replace('!!icateg!!', $i, $pdeptab[40]) ;
				else $ptab_categ = str_replace('!!icateg!!', $i, $pdeptab[401]) ;
	
			if ($thesaurus_mode_pmb && $categ_id) {
				$nom_tesaurus='['.thesaurus::getLibelle($categ->thes->id_thesaurus).'] ' ;
			} else {
				$nom_tesaurus='' ;
			}
			$ptab_categ = str_replace('!!categ_id!!',			$categ_id, $ptab_categ);
			$ptab_categ = str_replace('!!categ_libelle!!',		htmlentities($nom_tesaurus.$categ->catalog_form,ENT_QUOTES, $charset), $ptab_categ);
			$categ_repetables .= $ptab_categ ;
			}
		$pdeptab[4] = str_replace('!!max_categ!!', 				$max_categ, 		$pdeptab[4]);
		$pdeptab[4] = str_replace('!!categories_repetables!!',	$categ_repetables, $pdeptab[4]);
		
		// indexation interne
		$pdeptab[4] = str_replace('!!indexint_id!!',	$this->analysis_indexint,								 			$pdeptab[4]);
		$pdeptab[4] = str_replace('!!indexint!!',		htmlentities($this->analysis_indexint_lib,ENT_QUOTES, $charset),	$pdeptab[4]);
		if ($this->indexint){
			$indexint = new indexint($this->indexint);
			if ($indexint->comment) $disp_indexint= $indexint->name." - ".$indexint->comment ;
			else $disp_indexint= $indexint->name ;
			if ($thesaurus_classement_mode_pmb) { // plusieurs classements/indexations décimales autorisés en parametrage
				if ($indexint->name_pclass) $disp_indexint="[".$indexint->name_pclass."] ".$disp_indexint;
			}
			$pdeptab[4] = str_replace('!!indexint!!', htmlentities($disp_indexint,ENT_QUOTES, $charset), $pdeptab[4]);
			$pdeptab[4] = str_replace('!!num_pclass!!', $indexint->id_pclass, $pdeptab[4]);
		} else {
			$pdeptab[4] = str_replace('!!indexint!!', '', $pdeptab[4]);
			$pdeptab[4] = str_replace('!!num_pclass!!', '', $pdeptab[4]);
		}
		
		// indexation libre
	 	$pdeptab[4] = str_replace('!!f_indexation!!', htmlentities($this->analysis_index_l,ENT_QUOTES, $charset)	, $pdeptab[4]);
		global $pmb_keyword_sep ;
		$sep="'$pmb_keyword_sep'";
		if (!$pmb_keyword_sep) $sep="' '";
		if(ord($pmb_keyword_sep)==0xa || ord($pmb_keyword_sep)==0xd) $sep=$msg['catalogue_saut_de_ligne'];
		$pdeptab[4] = str_replace("!!sep!!",htmlentities($sep,ENT_QUOTES, $charset),$pdeptab[4]);	
		$analysis_top_form = str_replace('!!tab4!!', $pdeptab[4], $analysis_top_form);
		
		// mise à jour de l'onglet 5 : Langues
		// langues répétables
		if (sizeof($this->analysis_langues)==0) $max_lang = 1 ;
			else $max_lang = sizeof($this->analysis_langues) ; 
		for ($i = 0 ; $i < $max_lang ; $i++) {
			if ($i) $ptab_lang = str_replace('!!ilang!!', $i, $pdeptab[501]) ;
				else $ptab_lang = str_replace('!!ilang!!', $i, $pdeptab[50]) ;
			if ( sizeof($this->analysis_langues)==0 ) { 
				$ptab_lang = str_replace('!!lang_code!!', '', $ptab_lang);
				$ptab_lang = str_replace('!!lang!!', '', $ptab_lang);		
				} else {
					$ptab_lang = str_replace('!!lang_code!!', $this->analysis_langues[$i]["lang_code"], $ptab_lang);
					$ptab_lang = str_replace('!!lang!!',htmlentities($this->analysis_langues[$i]["langue"],ENT_QUOTES, $charset), $ptab_lang);
					}
			$lang_repetables .= $ptab_lang ;
			}
		$pdeptab[5] = str_replace('!!max_lang!!', $max_lang, $pdeptab[5]);
		$pdeptab[5] = str_replace('!!langues_repetables!!', $lang_repetables, $pdeptab[5]);
	
		// langues originales répétables
		if (sizeof($this->analysis_languesorg)==0) $max_langorg = 1 ;
			else $max_langorg = sizeof($this->analysis_languesorg) ; 
		for ($i = 0 ; $i < $max_langorg ; $i++) {
			if ($i) $ptab_lang = str_replace('!!ilangorg!!', $i, $pdeptab[511]) ;
				else $ptab_lang = str_replace('!!ilangorg!!', $i, $pdeptab[51]) ;
			if ( sizeof($this->analysis_languesorg)==0 ) { 
				$ptab_lang = str_replace('!!langorg_code!!', '', $ptab_lang);
				$ptab_lang = str_replace('!!langorg!!', '', $ptab_lang);		
				} else {
					$ptab_lang = str_replace('!!langorg_code!!', $this->analysis_languesorg[$i]["lang_code"], $ptab_lang);
					$ptab_lang = str_replace('!!langorg!!',htmlentities($this->analysis_languesorg[$i]["langue"],ENT_QUOTES, $charset), $ptab_lang);
					}
			$langorg_repetables .= $ptab_lang ;
			}
		$pdeptab[5] = str_replace('!!max_langorg!!', $max_langorg, $pdeptab[5]);
		$pdeptab[5] = str_replace('!!languesorg_repetables!!', $langorg_repetables, $pdeptab[5]);
	
		$analysis_top_form = str_replace('!!tab5!!', $pdeptab[5], $analysis_top_form);
		
		// mise à jour de l'onglet 6
	 	$pdeptab[6] = str_replace('!!lien!!',		htmlentities($this->analysis_lien,ENT_QUOTES, $charset)		, $pdeptab[6]);
	 	$pdeptab[6] = str_replace('!!eformat!!',	htmlentities($this->analysis_eformat,ENT_QUOTES, $charset)		, $pdeptab[6]);
		$analysis_top_form = str_replace('!!tab6!!', $pdeptab[6], $analysis_top_form);
		
		//Mise à jour de l'onglet 7
		$p_perso=new parametres_perso("notices");
		
		if (!$p_perso->no_special_fields) {
			$perso_=$p_perso->show_editable_fields($this->analysis_id);
		
			$perso="";
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				$perso.="<div id='move_".$p["NAME"]."' movable='yes' title=\"".htmlentities($p["TITRE"],ENT_QUOTES, $charset)."\">
						<div class='row'><label for='".$p["NAME"]."' class='etiquette'>".$p["TITRE"]."</label></div>
						<div class='row'>".$p["AFF"]."</div>
						</div>";
			}
			$perso.=$perso_["CHECK_SCRIPTS"];
			$pdeptab[7]=str_replace("!!champs_perso!!",$perso,$pdeptab[7]);
		} else 
			$pdeptab[7]="\n<script>function check_form() { return true; }</script>\n";
		$analysis_top_form = str_replace('!!tab7!!', $pdeptab[7], $analysis_top_form);
				
		// champs de gestion
		$select_statut = gen_liste_multiple ("select id_notice_statut, gestion_libelle from notice_statut order by 2", "id_notice_statut", "gestion_libelle", "id_notice_statut", "form_notice_statut", "", $this->analysis_statut, "", "","","",0) ;
		$pdeptab[8] = str_replace('!!notice_statut!!', $select_statut, $pdeptab[8]);
		$pdeptab[8] = str_replace('!!commentaire_gestion!!',htmlentities($this->analysis_commentaire_gestion,ENT_QUOTES, $charset), $pdeptab[8]);
		$pdeptab[8] = str_replace('!!thumbnail_url!!',htmlentities($this->thumbnail_url,ENT_QUOTES, $charset), $pdeptab[8]);
		
		//affichage des formulaires des droits d'acces
		$rights_form = $this->get_rights_form();
		$pdeptab[8] = str_replace('<!-- rights_form -->', $rights_form, $pdeptab[8]);
		
		$analysis_top_form = str_replace('!!tab8!!', $pdeptab[8], $analysis_top_form);
	
		
		// définition de la page cible du form
		$analysis_top_form = str_replace('!!action!!', $this->action, $analysis_top_form);
		
		// mise à jour du type de document
		$analysis_top_form = str_replace('!!doc_type!!', $this->analysis_typdoc, $analysis_top_form);
	
		// Ajout des localisations pour édition
		$select_loc="";
		global $PMBuserid, $pmb_form_editables;
		if ($PMBuserid==1 && $pmb_form_editables==1) {
			$req_loc="select idlocation,location_libelle from docs_location";
			$res_loc=mysql_query($req_loc);
			if (mysql_num_rows($res_loc)>1) {	
				$select_loc="<select name='grille_location' id='grille_location' style='display:none' onChange=\"get_pos();initIt(); if (inedit) move_parse_dom(relative);\">\n";
				$select_loc.="<option value='0'>Toutes les localisations</option>\n";
				while ($r=mysql_fetch_object($res_loc)) {
					$select_loc.="<option value='".$r->idlocation."'>".$r->location_libelle."</option>\n";
				}
				$select_loc.="</select>\n";
			}
		}	
		$analysis_top_form=str_replace("!!location!!",$select_loc,$analysis_top_form);
	
		// affichage du lien pour suppression
		if($this->analysis_id) {
			$link_supp = "
				<script type=\"text/javascript\">
					<!--
					function confirmation_delete() {
					result = confirm(\"${msg['confirm_suppr']} ?\");
					if(result) {
						unload_off();
						document.location = './catalog.php?categ=serials&sub=analysis&action=delete&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!';				
					}	
				}
					-->
				</script>
				<input type='button' class='bouton' value=\"{$msg[63]}\" onClick=\"confirmation_delete();\">&nbsp;";
			$form_titre = $msg[4023];
			if ($pmb_type_audit) 
				$link_audit =  "<input class='bouton' type='button' onClick=\"openPopUp('./audit.php?type_obj=1&object_id=$this->analysis_id', 'audit_popup', 700, 500, -2, -2, '$select_categ_prop')\" title='$msg[audit_button]' value='$msg[audit_button]' />";
			else 
				$link_audit = "" ;
		} else {
				$link_supp = "";
				$form_titre = $msg[4022];
				$link_audit = "" ;    
		}
		
		$analysis_top_form = str_replace('!!link_supp!!', $link_supp, $analysis_top_form);
		$analysis_top_form = str_replace('!!form_title!!', $form_titre, $analysis_top_form);
		
		// mise à jour des infos du dépouillement
		$analysis_top_form = str_replace('!!bul_id!!', $this->id_bulletinage, $analysis_top_form);
		$analysis_top_form = str_replace('!!analysis_id!!', $this->analysis_id, $analysis_top_form);
		$analysis_top_form = str_replace('!!link_audit!!', $link_audit, $analysis_top_form);
		
		if($notice_type){
			global $analysis_type_form;
			
			$date_clic = "onClick=\"openPopUp('./select.php?what=calendrier&caller=notice&date_caller=&param1=f_bull_new_date&param2=date_date_lib&auto_submit=NO&date_anterieure=YES', 'date_date', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"  ";
			$date_date = "<input type='hidden' id='f_bull_new_date' name='f_bull_new_date' value='' />
				<input class='saisie-10em' type='text' name='date_date_lib' value='' />
				<input class='bouton' type='button' name='date_date_lib_bouton' value='".$msg["bouton_calendrier"]."' ".$date_clic." />";
			
			$analysis_type_form = str_replace("!!date_date!!",$date_date,$analysis_type_form);
			$analysis_type_form = str_replace("!!perio_type_new!!","checked",$analysis_type_form);
			$analysis_type_form = str_replace("!!bull_type_new!!","checked",$analysis_type_form);
			$analysis_type_form = str_replace("!!perio_type_use_existing!!","",$analysis_type_form);
			$analysis_type_form = str_replace("!!bull_type_use_existing!!","",$analysis_type_form);
			
			$analysis_top_form = str_replace("!!type_catal!!",$analysis_type_form,$analysis_top_form);
			
			
			return $analysis_top_form;
			
		} else $analysis_top_form = str_replace("!!type_catal!!","",$analysis_top_form);
		
		return $analysis_top_form;
		
	}

	
	//creationformulaire des droits d'acces
	function get_rights_form() {
	
			global $dbh,$msg,$charset;
			global $gestion_acces_active, $gestion_acces_empr_notice;
			global $gestion_acces_empr_notice_def;
			
			if ($gestion_acces_active!=1) return '';
			$ac = new acces();
			
			$form = '';
			$c_form = "<label class='etiquette'><!-- domain_name --></label>
						<div class='row'>
				    	<div class='colonne3'>".htmlentities($msg['dom_cur_prf'],ENT_QUOTES,$charset)."</div>
				    	<div class='colonne_suite'><!-- prf_rad --></div>
				    	</div>
				    	<div class='row'>
				    	<div class='colonne3'>".htmlentities($msg['dom_cur_rights'],ENT_QUOTES,$charset)."</div>
					    <div class='colonne_suite'><!-- r_rad --></div>
					    <div class='row'><!-- rights_tab --></div>
					    </div>";
				
		
			if($gestion_acces_empr_notice==1) {
				
				$r_form=$c_form;
				$dom_2 = $ac->setDomain(2);	
				$r_form = str_replace('<!-- domain_name -->', htmlentities($dom_2->getComment('long_name'), ENT_QUOTES, $charset) ,$r_form);
				if($this->analysis_id) {
					
					//profil ressource
					$def_prf=$dom_2->getComment('res_prf_def_lib');
					$res_prf=$dom_2->getResourceProfile($this->analysis_id);
					$q=$dom_2->loadUsedResourceProfiles();
					
					//Recuperation droits generiques utilisateur
					$user_rights = $dom_2->getDomainRights(0,$res_prf);

					if($user_rights & 2) {
						$p_sel=gen_liste($q,'prf_id','prf_name', 'res_prf[2]', '', $res_prf, '0', $def_prf , '0', $def_prf );
						$p_rad = "<input type='radio' name='prf_rad[2]' value='R' ";
						if ($gestion_acces_empr_notice_def!='1') $p_rad.= "checked='checked' ";
						$p_rad.= ">".htmlentities($msg['dom_rad_calc'],ENT_QUOTES,$charset)."</input><input type='radio' name='prf_rad[2]' value='C' ";
						if ($gestion_acces_empr_notice_def=='1') $p_rad.= "checked='checked' ";
						$p_rad.= ">".htmlentities($msg['dom_rad_def'],ENT_QUOTES,$charset)." $p_sel</input>";
						$r_form=str_replace('<!-- prf_rad -->',$p_rad,$r_form);
					} else {
						$r_form = str_replace('<!-- prf_rad -->', htmlentities($dom_2->getResourceProfileName($res_prf), ENT_QUOTES, $charset), $r_form);
					}
					
					//droits/profils utilisateurs
					if($user_rights & 1) {
						$r_rad = "<input type='radio' name='r_rad[2]' value='R' ";
						if ($gestion_acces_empr_notice_def!='1') $r_rad.= "checked='checked' ";
						$r_rad.= ">".htmlentities($msg['dom_rad_calc'],ENT_QUOTES,$charset)."</input><input type='radio' name='r_rad[2]' value='C' ";
						if ($gestion_acces_empr_notice_def=='1') $r_rad.= "checked='checked' ";
						$r_rad.= ">".htmlentities($msg['dom_rad_def'],ENT_QUOTES,$charset)."</input>";
						$r_form = str_replace('<!-- r_rad -->', $r_rad, $r_form);
					}
					
					//recuperation profils utilisateurs
					$t_u=array();
					$t_u[0]= $dom_2->getComment('user_prf_def_lib');	//niveau par defaut
					$qu=$dom_2->loadUsedUserProfiles();
					$ru=mysql_query($qu, $dbh);
					if (mysql_num_rows($ru)) {
						while(($row=mysql_fetch_object($ru))) {
					        $t_u[$row->prf_id]= $row->prf_name;
						}
					}
				
					//recuperation des controles dependants de l'utilisateur
					$t_ctl=$dom_2->getControls(0);
		
					//recuperation des droits 
					$t_rights = $dom_2->getResourceRights($this->analysis_id);
									
					if (count($t_u)) {
		
						$h_tab = "<div class='dom_div'><table class='dom_tab'><tr>";
						foreach($t_u as $k=>$v) {
							$h_tab.= "<th class='dom_col'>".htmlentities($v, ENT_QUOTES, $charset)."</th>";			
						}
						$h_tab.="</tr><!-- rights_tab --></table></div>";
						
						$c_tab = '<tr>';
						foreach($t_u as $k=>$v) {
								
							$c_tab.= "<td><table style='border:1px solid;'><!-- rows --></table></td>";
							$t_rows = "";
									
							foreach($t_ctl as $k2=>$v2) {
															
								$t_rows.="
									<tr>
										<td style='width:25px;' ><input type='checkbox' name='chk_rights[2][".$k."][".$k2."]' value='1' ";
								if ($t_rights[$k][$res_prf] & (pow(2,$k2-1))) {
									$t_rows.= "checked='checked' ";
								}
								if(($user_rights & 1)==0) $t_rows.="disabled='disabled' ";
								$t_rows.="/></td>
										<td>".htmlentities($v2, ENT_QUOTES, $charset)."</td>
									</tr>";
							}						
							$c_tab = str_replace('<!-- rows -->', $t_rows, $c_tab);
						}
						$c_tab.= "</tr>";
						
					}
					$h_tab = str_replace('<!-- rights_tab -->', $c_tab, $h_tab);;
					$r_form=str_replace('<!-- rights_tab -->', $h_tab, $r_form);
					
				} else {
					$r_form = str_replace('<!-- prf_rad -->', htmlentities($msg['dom_prf_unknown'], ENT_QUOTES, $charset), $r_form);
					$r_form = str_replace('<!-- r_rad -->', htmlentities($msg['dom_rights_unknown'], ENT_QUOTES, $charset), $r_form);
				}
				$form.= $r_form;
				
			}
			return $form;
		}			

		
	
	
	
	// fonction de mise à jour d'une entrée MySQL de bulletinage
	
	function analysis_update($values) {
		
		global $dbh;
	
	    if(is_array($values)) {
			$this->analysis_biblio_level	=	'a';
			$this->analysis_hierar_level	=	'2';
			$this->analysis_typdoc		=	$values['typdoc'];
			$this->analysis_statut		=	$values['statut'];
			$this->analysis_commentaire_gestion	=	$values['f_commentaire_gestion'];
			$this->analysis_thumbnail_url		=	$values['f_thumbnail_url'];
			$this->analysis_tit1		=	$values['f_tit1'];
			$this->analysis_tit2		=	$values['f_tit2'];
			$this->analysis_tit3		=	$values['f_tit3'];
			$this->analysis_tit4		=	$values['f_tit4'];
			$this->analysis_n_gen		=	$values['f_n_gen'];
			$this->analysis_n_contenu	=	$values['f_n_contenu'];
			$this->analysis_n_resume	=	$values['f_n_resume'];
			$this->analysis_indexint	=	$values['f_indexint_id'];
			$this->analysis_index_l		=	$values['f_indexation'];
			$this->analysis_lien		=	$values['f_lien'];
			$this->analysis_eformat		=	$values['f_eformat'];
			$this->analysis_pages		=	$values['pages'];
			$this->analysis_signature			=	$values['signature']; 
			
			// insert de year à partir de la date de parution du bulletin
			if($this->date_date) {
				$this->analysis_year= substr($this->date_date,0,4);
			}
			$this->date_parution_perio = $this->date_date;
				
			// mise à jour des index
			$ind_wew = $this->analysis_tit1." ".$this->analysis_tit2." ".$this->analysis_tit3." ".$this->analysis_tit4 ;
			$ind_sew = strip_empty_words($ind_wew) ; 
			
			// champ d'indexation libre
			if ($this->analysis_index_l) $index_matieres = ' '.strip_empty_words($this->analysis_index_l).' ' ;
				else $index_matieres = '';
		
			// notes 
			$this->analysis_n_gen ? $index_n_gen = ' '.strip_empty_words($this->analysis_n_gen).' ' : $index_n_gen = '';
			$this->analysis_n_contenu ? $index_n_contenu = ' '.strip_empty_words($this->analysis_n_contenu).' ' : $index_n_contenu = '';
			$this->analysis_n_resume ? $index_n_resume = ' '.strip_empty_words($this->analysis_n_resume).' ' : $index_n_resume = '';
	
			// construction de la requête :
			$data = "typdoc='".$this->analysis_typdoc."'";
			$data .= ", statut='".$this->analysis_statut."'";
			$data .= ", tit1='".$this->analysis_tit1."'";
			$data .= ", tit3='".$this->analysis_tit3."'";
			$data .= ", tit4='".$this->analysis_tit4."'";
			$data .= ", year='".$this->analysis_year."'";
			$data .= ", npages='".$this->analysis_pages."'";
			$data .= ", n_contenu='".$this->analysis_n_contenu."'";
			$data .= ", n_gen='".$this->analysis_n_gen."'";
			$data .= ", n_resume='$this->analysis_n_resume'";
			$data .= ", lien='".$this->analysis_lien."'";
			$data .= ", eformat='".$this->analysis_eformat."'";
			$data .= ", indexint='".$this->analysis_indexint."'";
			$data .= ", index_l='".clean_tags($this->analysis_index_l)."'";
			$data .= ", index_sew=' ".$ind_sew." '";
			$data .= ", index_wew='".$ind_wew."'";
			$data .= ", index_serie=''";
			$data .= ", index_matieres='".$index_matieres."'";
			$data .= ", niveau_biblio='".$this->analysis_biblio_level."'";
			$data .= ", niveau_hierar='".$this->analysis_hierar_level."'";
			$data .= ", index_n_gen='".$index_n_gen."'";
			$data .= ", index_n_contenu='".$index_n_contenu."'";
			$data .= ", index_n_resume='".$index_n_resume."'";
			$data .= ", commentaire_gestion='".$this->analysis_commentaire_gestion."'";
			$data .= ", thumbnail_url='".$this->analysis_thumbnail_url."'";
			$data .= ", signature='".$this->analysis_signature."'";
			$data .= ", date_parution='".$this->date_parution_perio."'";    	    
	
			if(!$this->analysis_id) {
				
	    		// si c'est une création
	    		// fabrication de la requête finale
	    		$requete = "INSERT INTO notices SET $data , create_date=sysdate(), update_date=sysdate() ";
	    		$myQuery = mysql_query($requete, $dbh);
				$this->analysis_id = mysql_insert_id($dbh);
									
				// si l'insertion est OK, il faut créer l'entrée dans la table 'analysis'
				if($this->analysis_id) {
					audit::insert_creation (AUDIT_NOTICE, $this->analysis_id) ;
					$requete = 'INSERT INTO analysis SET';
					$requete .= ' analysis_bulletin='.$this->id_bulletinage;
					$requete .= ', analysis_notice='.$this->analysis_id;
					$myQuery = mysql_query($requete, $dbh);					
				}
				return $this->analysis_id;
			
			} else {
				
				$requete ="UPDATE notices SET $data , update_date=sysdate() WHERE notice_id='".$this->analysis_id."' LIMIT 1";
				$myQuery = mysql_query($requete, $dbh);
				audit::insert_modif (AUDIT_NOTICE, $this->analysis_id) ;
				if ($myQuery) $result = $this->analysis_id;
			}
	    	return $result;
		} //if(is_array($values))
	}
	
	
	// suppression d'un dépouillement
	function analysis_delete() {
		
		global $dbh;
		
		//elimination des docs numeriques
		$requete = "DELETE FROM explnum WHERE explnum_notice in ($this->analysis_id)" ;
		@mysql_query($requete, $dbh);
	
		// suppression des entrees dans les caddies
		$query_caddie = "select caddie_id from caddie_content, caddie where type='NOTI' and object_id in ($this->analysis_id) and caddie_id=idcaddie ";
		$result_caddie = @mysql_query($query_caddie, $dbh);
		while(($cad = mysql_fetch_object($result_caddie))) {
			$req_suppr_caddie="delete from caddie_content where caddie_id = '$cad->caddie_id' and object_id in ($this->analysis_id) " ;
			@mysql_query($req_suppr_caddie, $dbh);
		}
	
		//elimination des champs persos
		$p_perso=new parametres_perso("notices");
		$p_perso->delete_values($this->analysis_id);
	
		// on supprime l'entree dans la table 'analysis'
		$requete = "DELETE FROM analysis WHERE analysis_notice=".$this->analysis_id;
		mysql_query($requete, $dbh);
		$result = mysql_affected_rows($dbh);
	
		// on supprime la notice du dépouillement
		$requete = "DELETE FROM notices WHERE notice_id='".$this->analysis_id."' ";
		mysql_query($requete, $dbh);
		$result += mysql_affected_rows($dbh);
		
		//suppression des droits d'acces user_notice
		$requete = "delete from acces_res_1 where res_num=".$this->analysis_id;
		@mysql_query($requete, $dbh);	
				
		//suppression des droits d'acces empr_notice
		$requete = "delete from acces_res_2 where res_num=".$this->analysis_id;
		@mysql_query($requete, $dbh);	
		
		// suppression des audits
		audit::delete_audit (AUDIT_NOTICE, $this->analysis_id) ;
	
		// suppression des categories
		$rqt_del = "delete from notices_categories where notcateg_notice='$this->analysis_id' ";
		@mysql_query($rqt_del, $dbh);
	
		// suppression des responsabilités
		$rqt_del = "delete from responsability where responsability_notice='$this->analysis_id' ";
		@mysql_query($rqt_del, $dbh);
	
		// suppression des bannettes
		$rqt_del = "delete from bannette_contenu where num_notice='$this->analysis_id' ";
		@mysql_query($rqt_del, $dbh);
	
		// suppression des tags
		$rqt_del = "delete from tags where num_notice='$this->analysis_id' ";
		@mysql_query($rqt_del, $dbh);
	
		// suppression des avis
		$rqt_del = "delete from avis where num_notice='$this->analysis_id' ";
		@mysql_query($rqt_del, $dbh);
	
		//suppression des langues
		$query = "delete from notices_langues where num_notice='".$this->analysis_id."' ";
		@mysql_query($query, $dbh);
		
		// suppression index global
		$query = "delete from notices_global_index where num_notice='".$this->analysis_id."' ";
		@mysql_query($query, $dbh);
		
		//Suppression de la reference a la notice dans la table suggestions
		$query = "UPDATE suggestions set num_notice = 0 where num_notice=".$this->analysis_id;
		@mysql_query($query, $dbh);
		
		//Suppression de la référence de la source si exitante..
		$query="delete from notices_externes where num_notice=".$this->analysis_id;
		@mysql_query($query, $dbh);
		
		return $result;
		
		
	}

} // fin définition classe

/*
  aide-mémoire
  à l'issue de l'héritage mutiple, on a les propriétés :

  class serial

    $serial_id            id de ce périodique
    $biblio_level         niveau bibliographique
    $hierar_level         niveau hiérarchique
    $typdoc               type UNIMARC du document (imprimé par défaut)
    $tit1                 titre propre
    $tit3                 titre parallèle
    $tit4                 complément du titre propre
    $ed1_id               id de l'éditeur 1
    $ed1                  forme affichable de l'éditeur 1
    $ed2_id               id de l'éditeur 2
    $ed2                  forme affichable de l'éditeur 2
    $n_gen                note générale
    $n_resume             note de résumé
    $index_l              indexation libre
    $lien                 URL associée
    $eformat              type de la ressource électronique
    $action               cible du formulaire généré par la méthode do_form

  class bulletinage
  
    $bulletin_id         id de ce bulletinage
    $bulletin_titre      titre propre
    $bulletin_numero     mention de numéro sur la publication
    $bulletin_notice     id notice parent = id du périodique relié
    $bulletin_cb         code barre EAN13 (+addon)
    $mention_date        mention de date sur la publication
    $date_date           date de création de l'entrée de bulletinage
    $display             forme à afficher pour prêt, listes, etc...

  class analysis
  
	$analysis_id            id de ce dépouillement
	$id_bulletinage         id du bulletinage contenant ce dépouillement
	$analysis_biblio_level  niveau bibliographique
	$analysis_hierar_level  niveau hiérarchique
	$analysis_typdoc        type de document (imprimé par défaut)
	$analysis_tit1          titre propre
	$analysis_tit3          titre parallèle
	$analysis_tit4          complément du titre propre
	$analysis_aut1_id       id de l'auteur 1
	$analysis_aut1          ** forme affichable de l'auteur 1
	$analysis_f1_code       code de fonction auteur 1
	$analysis_f1            ** fonction auteur 1
	$analysis_aut2_id       id de l'auteur 2
	$analysis_aut2          ** forme affichable de l'auteur 2
	$analysis_f2_code       code de fonction auteur 2
	$analysis_f2            ** fonction auteur 1
	$analysis_aut3_id       id de l'auteur 3
	$analysis_aut3          ** forme affichable de l'auteur 3
	$analysis_f3_code       code de fonction auteur 3
	$analysis_f3            ** fonction auteur 3
	$analysis_aut4_id       id de l'auteur 4
	$analysis_aut4          ** forme affichable de l'auteur 4
	$analysis_f4_code       code de fonction auteur 4
	$analysis_f4            ** fonction auteur 4
	$analysis_ed1_id        id de l'éditeur 1
	$analysis_ed1           forme affichable de l'éditeur 1
	$analysis_ed2_id        id de l'éditeur 2
	$analysis_ed2           forme affichable de l'éditeur 2
	$analysis_n_gen         note générale
	$analysis_n_resume      note de résumé
	$analysis_index_l       indexation libre
	$analysis_eformat  	 format de la ressource
	$analysis_lien          lien vers une ressource électronique
	$action          	 cible du formulaire généré par la méthode do_form
	$analysis_pages         mention de pagination
	

*/