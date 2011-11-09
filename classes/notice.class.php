<?php
// +-------------------------------------------------+
//  2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice.class.php,v 1.118.2.2 2011-09-22 12:23:26 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");


// classe de gestion des notices
if ( ! defined( 'NOTICE_CLASS' ) ) {
  define( 'NOTICE_CLASS', 1 );

	require_once("$class_path/author.class.php");
	require_once("$class_path/marc_table.class.php");
	require_once("$class_path/category.class.php");
	require_once("$class_path/serie.class.php");
	require_once("$class_path/indexint.class.php");
//	require_once("$class_path/tu_notice.class.php");
	require_once($class_path."/parametres_perso.class.php");
	require_once($class_path."/audit.class.php");
	include_once($include_path."/notice_authors.inc.php");
	include_once($include_path."/notice_categories.inc.php");
	require_once($class_path."/thesaurus.class.php");
	require_once($class_path."/noeuds.class.php");
	require_once($include_path."/parser.inc.php");
	require_once($include_path."/rss_func.inc.php");	
	require_once("$class_path/acces.class.php");
			
	class notice {
	
		// proprietes
		var $libelle_form = '';
		var $id = 0;
		var $duplicate_from_id = 0;
		var $tit1 = '';			// titre propre
		var $tit2 = '';			// titre propre 2
		var $tit3 = '';			// titre parallele
		var $tit4 = '';			// complement du titre
		var $tparent_id = '';		// id du titre parent
		var $tparent = '';		// libelle du titre parent
		var $tnvol = '';		// numero de partie
		var $responsabilites =	array("responsabilites" => array(),"auteurs" => array());  // les auteurs
		var $ed1_id = '';		// id editeur 1
		var $ed1 ='';			// libelle editeur 1
		var $coll_id = '';		// id collection
		var $coll = '';			// libelle collection
		var $subcoll_id = '';		// id sous collection
		var $subcoll = '';		// libelle sous collection
		var $year = '';			// annee de publication
		var $nocoll = '';		// no. dans la collection
		var $mention_edition = '';	// mention d'edition (1ere, deuxieme...)
		var $ed2_id = '';		// id editeur 2
		var $ed2 ='';			// libelle editeur 2
		var $code = '';			// ISBN, code barre commercial ou no. commercial
		var $npages = '';		// importance materielle (nombre de pages, d'elements...)
		var $ill = '';			// mention d'illustration
		var $size = '';			// format
		var $prix = '';			// prix du document
		var $accomp = '';		// materiel d'accompagnement
		var $n_gen = '';		// note generale
		var $n_contenu = '';		// note de contenu
		var $n_resume = '';		// resume/extrait
		var $categories =	array();// les categories
		var $indexint = 0;		// indexation interne
		var $index_l = '';		// indexation libre
		var $langues = array();
		var $languesorg = array();
		var $lien = '';			// URL de la ressource electronique associee
		var $eformat = '';		// format de la ressource electronique associee
		var $ok = 1;
		var $type_doc = '';
		var $biblio_level = 'm';	// niveau bibliographique
		var $hierar_level = '0';	// niveau hierarchique
		var $action = './catalog.php?categ=update&id=';
		var $link_annul = './catalog.php';
		var $statut = 0 ; // statut 
		var $commentaire_gestion = '' ; // commentaire de gestion 
		var $thumbnail_url = '' ;
		var $notice_mere=array();
		var $notice_mere_id=array();
		var $relation_type=array();
		var $relation_rank=array();
		var $date_parution;
		// methodes
		
		
		// constructeur
		function notice($id=0, $cb='') {
			global $dbh;
			global $msg;
			global $include_path, $class_path ;
		
			if($id) {
				$fonction = new marc_list('function');
		
				$this->id = $id;
				$this->libelle_form = $msg[278];  // libelle du form : modification d'une notice

				$requete = "SELECT * FROM notices WHERE notice_id='$id' LIMIT 1 ";
				$result = @mysql_query($requete, $dbh);
		
				if($result) {
					$notice = mysql_fetch_object($result);
		
					$this->type_doc = $notice->typdoc;				// type du document
					$this->tit1		= $notice->tit1;				// titre propre
					$this->tit2		= $notice->tit2;				// titre propre 2
					$this->tit3		= $notice->tit3;				// titre parallele
					$this->tit4		= $notice->tit4;				// complement du titre
					$this->tparent_id	= $notice->tparent_id;				// id du titre parent
		
					// libelle du titre parent
					if($this->tparent_id) {
						$serie = new serie($this->tparent_id);
						$this->tparent = $serie->name;
					} else {
						$this->tparent 		= '';
					}
		
					$this->tnvol		= $notice->tnvol;				// numero de partie
					
					$this->responsabilites = get_notice_authors($this->id) ;
					$this->subcoll_id 	= $notice->subcoll_id;				// id sous collection
					$this->coll_id 		= $notice->coll_id;				// id collection
					$this->ed1_id		= $notice->ed1_id	;			// id editeur 1
		
					require_once("$class_path/editor.class.php");
		
					if($this->subcoll_id) {
						require_once("$class_path/subcollection.class.php");
						require_once("$class_path/collection.class.php");
						$collection = new subcollection($this->subcoll_id);
						$this->subcoll = $collection->name;
					}
		
					if($this->coll_id) {
						require_once("$class_path/collection.class.php");
						$collection = new collection($this->coll_id);
						$this->coll = $collection->name;
					}
		
					if($this->ed1_id) {
						$editeur = new editeur($this->ed1_id);
						$this->ed1 = $editeur->display;
					}
		
					$this->year 		= $notice->year;				// annee de publication
					$this->nocoll		= $notice->nocoll;				// no. dans la collection
					$this->mention_edition		= $notice->mention_edition;	// mention d'edition (1ere, deuxieme...)
					$this->ed2_id		= $notice->ed2_id;				// id editeur 2
		
					if($this->ed2_id) {		// libelle editeur 2
						$editeur = new editeur($this->ed2_id);
						$this->ed2 = $editeur->display;
					}
		
					$this->code		= $notice->code;				// ISBN, code barre commercial ou no. commercial
		
					$this->npages		= $notice->npages;				// importance materielle (nombre de pages, d'elements...)
					$this->ill		= $notice->ill;					// mention d'illustration
					$this->size		= $notice->size;				// format
					$this->prix		= $notice->prix;				// Prix du document
					$this->accomp		= $notice->accomp;				// materiel d'accompagnement
		
					$this->n_gen		= $notice->n_gen;				// note generale
					$this->n_contenu	= $notice->n_contenu;				// note de contenu
					$this->n_resume		= $notice->n_resume;				// resume/extrait
		
					$this->categories = get_notice_categories($this->id) ;
		
					$this->indexint		= $notice->indexint;				// indexation interne
					$this->index_l		= $notice->index_l;				// indexation libre
		
					$this->langues	= get_notice_langues($this->id, 0) ;	// langues de la publication
					$this->languesorg	= get_notice_langues($this->id, 1) ; // langues originales
		
					$this->lien	= $notice->lien;				// URL de la ressource electronique associee
					$this->eformat	= $notice->eformat;				// format de la ressource electronique associee
					$this->biblio_level = $notice->niveau_biblio;   	    	// niveau bibliographique
					$this->hierar_level = $notice->niveau_hierar;       		// niveau hierarchique
					$this->statut = $notice->statut;
					$this->date_parution = $this->get_date_parution($notice->year);
										
					$requete="select linked_notice, relation_type, rank from notices_relations where num_notice=".$id." order by rank";
					$result_rel=mysql_query($requete);
					if (mysql_num_rows($result_rel)) {
						while (($r_rel=mysql_fetch_object($result_rel))) {
							$this->notice_mere[]=$this->get_notice_title($r_rel->linked_notice);
							$this->notice_mere_id[]=$r_rel->linked_notice;
							$this->relation_type[]=$r_rel->relation_type;
							$this->relation_rank[]=$r_rel->rank;
						}
					}
					 
					$this->commentaire_gestion = $notice->commentaire_gestion;
					$this->thumbnail_url = $notice->thumbnail_url; 
				} else {
					require_once("$include_path/user_error.inc.php");
					error_message("", $msg[280], 1, "./catalog.php");
					$this->ok = 0;
				}
				return;
			} else {
		    	// initialisation des valeurs (vides)
				$this->libelle_form = $msg[270];  // libelle du form : creation d'une notice
				$this->id = 0;
				$this->code = $cb;
				// initialisation avec les parametres du user :
				global $value_deflt_lang, $value_deflt_relation ;
				if ($value_deflt_lang) {
					$lang = new marc_list('lang');
					$this->langues[] = array( 
						'lang_code' => $value_deflt_lang,
						'langue' => $lang->table[$value_deflt_lang]
						) ;
				}
				global $deflt_notice_statut ;
				if ($deflt_notice_statut) $this->statut = $deflt_notice_statut;
					else $this->statut = 1;
				
				global $xmlta_doctype ;
				$this->type_doc = $xmlta_doctype ;
				
				global $notice_parent;
				if ($notice_parent) {
					$this->notice_mere[0]=$this->get_notice_title($notice_parent);
					$this->notice_mere_id[0]=$notice_parent;
					//Recherche d'un type plausible
					$requete="select relation_type from notices_relations where num_notice=$notice_parent order by rank desc limit 1";
					$resultat=mysql_query($requete);
					if (@mysql_num_rows($resultat)) {
						$this->relation_type[0]=mysql_result($resultat,0,0);
					} else $this->relation_type[0]=$value_deflt_relation ;
				}
				// penser au test d'existence de la notice sur code-barre
				return;
			}
		}
				
		// Donne l'id de la notice par son isbn 
		function get_notice_id_from_cb($code) {

			if(!$code) return 0;
			$isbn = traite_code_isbn($code);
			
			if(isISBN10($isbn)) {
				$isbn13 = formatISBN($isbn,13);
				$isbn10 = $isbn;
			} elseif (isISBN13($isbn)) {
				$isbn10 = formatISBN($isbn,10);
				$isbn13 = $isbn;				
			} else {
				// ce n'est pas un code au format isbn
				$isbn10=$code;
			}
					
			$requete = "SELECT notice_id FROM notices WHERE ( code='$isbn10' or code='$isbn13') and code !='' LIMIT 1 ";						
			if(($result = mysql_query($requete))) {
				if (mysql_num_rows($result)) {
					$notice = mysql_fetch_object($result);
					return($notice->notice_id);
				}	
			}
			return 0;
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
			return detectFormatDate($annee);
		}
		
		// affichage du form associe
		function show_form() {
			
			global $msg;
			global $charset;
			global $include_path, $class_path;
			global $current_module ;
			global $pmb_type_audit,$select_categ_prop, $z3950_accessible ;
			global $value_deflt_fonction, $value_deflt_relation;
			global $thesaurus_mode_pmb ;
			global $PMBuserid, $pmb_form_editables,$thesaurus_classement_mode_pmb;
			
			include("$include_path/templates/catal_form.tpl.php");
			$fonction = new marc_list('function');
			
			// mise a jour de l'action en fonction de l'id
			$this->action .= $this->id;
		
			// mise a jour de l'en-tete du formulaire
			if ($this->notice_mere[0]) $this->libelle_form.=" ".$msg["catalog_notice_fille_lib"]." ".substr($this->notice_mere[0],0,100).(count($this->notice_mere)>1?", ...":"");
			$form_notice = str_replace('!!libelle_form!!', $this->libelle_form, $form_notice);
	
			// mise a jour des flags de niveau hierarchique
			$form_notice = str_replace('!!b_level!!', $this->biblio_level, $form_notice);
			$form_notice = str_replace('!!h_level!!', $this->hierar_level, $form_notice);
		
			// mise a jour de l'onglet 0
			$ptab[0] = str_replace('!!tit1!!',				htmlentities($this->tit1,ENT_QUOTES, $charset)			, $ptab[0]);
			$ptab[0] = str_replace('!!tit2!!',				htmlentities($this->tit2,ENT_QUOTES, $charset)			, $ptab[0]);
			$ptab[0] = str_replace('!!tit3!!',				htmlentities($this->tit3,ENT_QUOTES, $charset)			, $ptab[0]);
			$ptab[0] = str_replace('!!tit4!!',				htmlentities($this->tit4,ENT_QUOTES, $charset)			, $ptab[0]);
			$ptab[0] = str_replace('!!tparent_id!!',		$this->tparent_id										, $ptab[0]);
			$ptab[0] = str_replace('!!tparent!!',			htmlentities($this->tparent,ENT_QUOTES, $charset)		, $ptab[0]);
			$ptab[0] = str_replace('!!tnvol!!',				htmlentities($this->tnvol,ENT_QUOTES, $charset)			, $ptab[0]);
		
			$form_notice = str_replace('!!tab0!!', $ptab[0], $form_notice);
		
			// mise a jour de l'onglet 1
			// constitution de la mention de responsabilite
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
			$form_notice = str_replace('!!tab1!!', $ptab[1], $form_notice);
		
			// mise a jour de l'onglet 2
			$ptab[2] = str_replace('!!ed1_id!!',			$this->ed1_id			, $ptab[2]);
			$ptab[2] = str_replace('!!ed1!!',				htmlentities($this->ed1,ENT_QUOTES, $charset)				, $ptab[2]);
			$ptab[2] = str_replace('!!coll_id!!',			$this->coll_id			, $ptab[2]);
			$ptab[2] = str_replace('!!coll!!',				htmlentities($this->coll,ENT_QUOTES, $charset)				, $ptab[2]);
			$ptab[2] = str_replace('!!subcoll_id!!',			$this->subcoll_id		, $ptab[2]);
			$ptab[2] = str_replace('!!subcoll!!',			htmlentities($this->subcoll,ENT_QUOTES, $charset)			, $ptab[2]);
			$ptab[2] = str_replace('!!year!!',				$this->year				, $ptab[2]);
			$ptab[2] = str_replace('!!nocoll!!',			htmlentities($this->nocoll,ENT_QUOTES, $charset)			, $ptab[2]);
			$ptab[2] = str_replace('!!mention_edition!!',			htmlentities($this->mention_edition,ENT_QUOTES, $charset)			, $ptab[2]);
			$ptab[2] = str_replace('!!ed2_id!!',			$this->ed2_id			, $ptab[2]);
			$ptab[2] = str_replace('!!ed2!!',				htmlentities($this->ed2,ENT_QUOTES, $charset)				, $ptab[2]);
		
			$form_notice = str_replace('!!tab2!!', $ptab[2], $form_notice);
		
			// mise a jour de l'onglet 3
			$ptab[3] = str_replace('!!cb!!',				$this->code				, $ptab[3]);
			$ptab[3] = str_replace('!!notice_id!!',			$this->id				, $ptab[3]);
		
			$form_notice = str_replace('!!tab3!!', $ptab[3], $form_notice);
			
			// Gestion des titres uniformes 
			global $pmb_use_uniform_title;
			if ($pmb_use_uniform_title) {
				$tu=new tu_notice($this->id); 	
				$ptab[230] = str_replace("!!titres_uniformes!!", $tu->get_form("notice"), $ptab[230]);
				$form_notice = str_replace('!!tab230!!', $ptab[230], $form_notice);
			}				

			// mise a jour de l'onglet 4
			$ptab[4] = str_replace('!!npages!!',	htmlentities($this->npages	,ENT_QUOTES, $charset)	, $ptab[4]);
			$ptab[4] = str_replace('!!ill!!',		htmlentities($this->ill		,ENT_QUOTES, $charset)	, $ptab[4]);
			$ptab[4] = str_replace('!!size!!',		htmlentities($this->size	,ENT_QUOTES, $charset)	, $ptab[4]);
			$ptab[4] = str_replace('!!prix!!',		htmlentities($this->prix	,ENT_QUOTES, $charset)	, $ptab[4]);
			$ptab[4] = str_replace('!!accomp!!',	htmlentities($this->accomp	,ENT_QUOTES, $charset)	, $ptab[4]);
		
			$form_notice = str_replace('!!tab4!!', $ptab[4], $form_notice);
		
			// mise a jour de l'onglet 5
			$ptab[5] = str_replace('!!n_gen!!',		htmlentities($this->n_gen	,ENT_QUOTES, $charset)	, $ptab[5]);
			$ptab[5] = str_replace('!!n_contenu!!',	htmlentities($this->n_contenu	,ENT_QUOTES, $charset)	, $ptab[5]);
			$ptab[5] = str_replace('!!n_resume!!',	htmlentities($this->n_resume	,ENT_QUOTES, $charset)	, $ptab[5]);
		
			$form_notice = str_replace('!!tab5!!', $ptab[5], $form_notice);
		
			// mise a jour de l'onglet 6
			// categories
			if (sizeof($this->categories)==0) $max_categ = 1 ;
				else $max_categ = sizeof($this->categories) ; 
			for ($i = 0 ; $i < $max_categ ; $i++) {
				$categ_id = $this->categories[$i]["categ_id"] ;
				$categ = new category($categ_id);
				
				if ($i==0) $ptab_categ = str_replace('!!icateg!!', $i, $ptab[60]) ;
					else $ptab_categ = str_replace('!!icateg!!', $i, $ptab[601]) ;
					
				$ptab_categ = str_replace('!!categ_id!!',			$categ_id, $ptab_categ);
				if ( sizeof($this->categories)==0 ) { 
					$ptab_categ = str_replace('!!categ_libelle!!', '', $ptab_categ);		
				} else {
					if ($thesaurus_mode_pmb) $nom_tesaurus='['.$categ->thes->getLibelle().'] ' ;
						else $nom_tesaurus='' ;
					$ptab_categ = str_replace('!!categ_libelle!!',	htmlentities($nom_tesaurus.$categ->catalog_form,ENT_QUOTES, $charset), $ptab_categ);
				}
				$categ_repetables .= $ptab_categ ;
			}
			$ptab[6] = str_replace('!!max_categ!!', $max_categ, $ptab[6]);
			$ptab[6] = str_replace('!!categories_repetables!!', $categ_repetables, $ptab[6]);
		
			// indexation interne
			$ptab[6] = str_replace('!!indexint_id!!', $this->indexint, $ptab[6]);
			if ($this->indexint){
				$indexint = new indexint($this->indexint);
				if ($indexint->comment) $disp_indexint= $indexint->name." - ".$indexint->comment ;
				else $disp_indexint= $indexint->name ;
				if ($thesaurus_classement_mode_pmb) { // plusieurs classements/indexations decimales autorises en parametrage
					if ($indexint->name_pclass) $disp_indexint="[".$indexint->name_pclass."] ".$disp_indexint;
				}
				$ptab[6] = str_replace('!!indexint!!', htmlentities($disp_indexint,ENT_QUOTES, $charset), $ptab[6]);
				$ptab[6] = str_replace('!!num_pclass!!', $indexint->id_pclass, $ptab[6]);
			} else {
				$ptab[6] = str_replace('!!indexint!!', '', $ptab[6]);
				$ptab[6] = str_replace('!!num_pclass!!', '', $ptab[6]);
			}
		
			// indexation libre
			$ptab[6] = str_replace('!!f_indexation!!', htmlentities($this->index_l,ENT_QUOTES, $charset), $ptab[6]);
			global $pmb_keyword_sep ;
			
			//if (!$pmb_keyword_sep) $pmb_keyword_sep=" ";
			$sep="'$pmb_keyword_sep'";
			if (!$pmb_keyword_sep) $sep="' '";
			if(ord($pmb_keyword_sep)==0xa || ord($pmb_keyword_sep)==0xd) $sep=$msg['catalogue_saut_de_ligne'];
			$ptab[6] = str_replace("!!sep!!",htmlentities($sep,ENT_QUOTES, $charset),$ptab[6]);
			$form_notice = str_replace('!!tab6!!', $ptab[6], $form_notice);
		
			// mise a jour de l'onglet 7 : langues
			// langues repetables
			if (sizeof($this->langues)==0) $max_lang = 1 ;
				else $max_lang = sizeof($this->langues) ; 
			for ($i = 0 ; $i < $max_lang ; $i++) {
				if ($i) $ptab_lang = str_replace('!!ilang!!', $i, $ptab[701]) ;
					else $ptab_lang = str_replace('!!ilang!!', $i, $ptab[70]) ;
				if ( sizeof($this->langues)==0 ) { 
					$ptab_lang = str_replace('!!lang_code!!', '', $ptab_lang);
					$ptab_lang = str_replace('!!lang!!', '', $ptab_lang);		
				} else {
					$ptab_lang = str_replace('!!lang_code!!', $this->langues[$i]["lang_code"], $ptab_lang);
					$ptab_lang = str_replace('!!lang!!',htmlentities($this->langues[$i]["langue"],ENT_QUOTES, $charset), $ptab_lang);
				}
				$lang_repetables .= $ptab_lang ;
			}
			$ptab[7] = str_replace('!!max_lang!!', $max_lang, $ptab[7]);
			$ptab[7] = str_replace('!!langues_repetables!!', $lang_repetables, $ptab[7]);
		
			// langues originales repetables
			if (sizeof($this->languesorg)==0) $max_langorg = 1 ;
				else $max_langorg = sizeof($this->languesorg) ; 
			for ($i = 0 ; $i < $max_langorg ; $i++) {
				if ($i) $ptab_lang = str_replace('!!ilangorg!!', $i, $ptab[711]) ;
					else $ptab_lang = str_replace('!!ilangorg!!', $i, $ptab[71]) ;
				if ( sizeof($this->languesorg)==0 ) { 
					$ptab_lang = str_replace('!!langorg_code!!', '', $ptab_lang);
					$ptab_lang = str_replace('!!langorg!!', '', $ptab_lang);		
				} else {
					$ptab_lang = str_replace('!!langorg_code!!', $this->languesorg[$i]["lang_code"], $ptab_lang);
					$ptab_lang = str_replace('!!langorg!!',htmlentities($this->languesorg[$i]["langue"],ENT_QUOTES, $charset), $ptab_lang);
				}
				$langorg_repetables .= $ptab_lang ;
			}
			$ptab[7] = str_replace('!!max_langorg!!', $max_langorg, $ptab[7]);
			$ptab[7] = str_replace('!!languesorg_repetables!!', $langorg_repetables, $ptab[7]);
		
			$form_notice = str_replace('!!tab7!!', $ptab[7], $form_notice);
		
			// mise a jour de l'onglet 8
			$ptab[8] = str_replace('!!lien!!',			htmlentities($this->lien	,ENT_QUOTES, $charset)	, $ptab[8]);
			$ptab[8] = str_replace('!!eformat!!',		htmlentities($this->eformat	,ENT_QUOTES, $charset)	, $ptab[8]);
		
			$form_notice = str_replace('!!tab8!!', $ptab[8], $form_notice);
		
			//Mise a jour de l'onglet 9
			$p_perso=new parametres_perso("notices");
			
			if (!$p_perso->no_special_fields) {
				// si on duplique, construire le formulaire avec les donnees de la notice d'origine
				if ($this->duplicate_from_id) $perso_=$p_perso->show_editable_fields($this->duplicate_from_id);
					else $perso_=$p_perso->show_editable_fields($this->id);
				$perso="";
				for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
					$p=$perso_["FIELDS"][$i];
					$perso.="<div id='move_".$p["NAME"]."' movable='yes' title=\"".htmlentities($p["TITRE"],ENT_QUOTES, $charset)."\">
								<div class='row'><label for='".$p["NAME"]."' class='etiquette'>".htmlentities($p["TITRE"],ENT_QUOTES, $charset)."</label></div>
								<div class='row'>".$p["AFF"]."</div>
							 </div>";
				}
				$perso.=$perso_["CHECK_SCRIPTS"];
				$ptab[9]=str_replace("!!champs_perso!!",$perso,$ptab[9]);
			} else 
				$ptab[9]="\n<script>function check_form() { return true; }</script>\n";
			
			$form_notice = str_replace('!!tab9!!', $ptab[9], $form_notice);
		
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
			
			$form_notice = str_replace('!!tab11!!', $ptab[13],$form_notice);
		
			// champs de gestion
			$select_statut = gen_liste_multiple ("select id_notice_statut, gestion_libelle from notice_statut order by 2", "id_notice_statut", "gestion_libelle", "id_notice_statut", "form_notice_statut", "", $this->statut, "", "","","",0) ;
			$ptab[10] = str_replace('!!notice_statut!!', $select_statut, $ptab[10]);
			$ptab[10] = str_replace('!!commentaire_gestion!!',htmlentities($this->commentaire_gestion,ENT_QUOTES, $charset), $ptab[10]);
			$ptab[10] = str_replace('!!thumbnail_url!!',htmlentities($this->thumbnail_url,ENT_QUOTES, $charset), $ptab[10]);

			//affichage des formulaires des droits d'acces
			$rights_form = $this->get_rights_form();
			$ptab[10] = str_replace('<!-- rights_form -->', $rights_form, $ptab[10]);
			
			$form_notice = str_replace('!!tab10!!', $ptab[10], $form_notice);			
				
			
			// definition de la page cible du form
			$form_notice = str_replace('!!action!!', $this->action, $form_notice);
		
			// ajout des selecteurs
			$select_doc = new marc_select('doctype', 'typdoc', $this->type_doc, "get_pos(); expandAll(); ajax_parse_dom(); if (inedit) move_parse_dom(relative); else initIt();");
			$form_notice = str_replace('!!doc_type!!', $select_doc->display, $form_notice);
		
			$form_notice = str_replace('!!notice_id_no_replace!!', $this->id, $form_notice);
		
			// Ajout des localisations pour edition
			$select_loc="";
			if ($PMBuserid==1) {
				$req_loc="select idlocation,location_libelle from docs_location";
				$res_loc=mysql_query($req_loc);
				if (mysql_num_rows($res_loc)>1) {	
					$select_loc="<select name='grille_location' id='grille_location' style='display:none' onChange=\"get_pos(); expandAll(); if (inedit) move_parse_dom(relative); else initIt();\">\n";
					$select_loc.="<option value='0'>Toutes les localisations</option>\n";
					while (($r=mysql_fetch_object($res_loc))) {
						$select_loc.="<option value='".$r->idlocation."'>".$r->location_libelle."</option>\n";
					}
					$select_loc.="</select>\n";
				}
			}	
			$form_notice=str_replace("!!location!!",$select_loc,$form_notice);
		
			// affichage du lien pour suppression et du lien d'annulation
			if ($this->id) {
				$link_supp = "
				<script type=\"text/javascript\">
					function confirm_delete() {
						result = confirm(\"{$msg[confirm_suppr_notice]}\");
			       		if(result) {
			       			unload_off();
			           		document.location = './catalog.php?categ=delete&id=".$this->id."'
						} 
					}
				</script>
				<input type='button' class='bouton' value=\"{$msg[63]}\" onClick=\"confirm_delete();\" />";
				$link_annul = "<input type='button' class='bouton' value=\"{$msg[76]}\" onClick=\"unload_off();history.go(-1);\" />";
				$link_remplace =  "<input type='button' class='bouton' value='$msg[158]' onclick='unload_off();document.location=\"./catalog.php?categ=remplace&id=".$this->id."\"' />";
				$link_duplicate =  "<input type='button' class='bouton' value='$msg[notice_duplicate_bouton]' onclick='unload_off();document.location=\"./catalog.php?categ=duplicate&id=".$this->id."\"' />";
				if ($z3950_accessible) $link_z3950 = "<input type='button' class='bouton' value='$msg[notice_z3950_update_bouton]' onclick='unload_off();document.location=\"./catalog.php?categ=z3950&id_notice=".$this->id."&isbn=".$this->code."\"' />";
					else $link_z3950="";
				if ($pmb_type_audit) $link_audit =  "<input class='bouton' type='button' onClick=\"openPopUp('./audit.php?type_obj=1&object_id=$this->id', 'audit_popup', 700, 500, -2, -2, '$select_categ_prop')\" title='$msg[audit_button]' value='$msg[audit_button]' />";
					else $link_audit = "" ;
			} else {
				$link_supp = "";
				$link_remplace = "";
				$link_duplicate = "" ;
				$link_z3950 = "" ;
				$link_audit = "" ;
				if ($this->notice_mere_id) $link_annul = "<input type='button' class='bouton' value=\"{$msg[76]}\" onClick=\"unload_off();history.go(-1);\" />"; 
					else $link_annul = "<input type='button' class='bouton' value=\"{$msg[76]}\" onClick=\"unload_off();document.location='".$this->link_annul."';\" />";
			}
			$form_notice = str_replace('!!link_supp!!', $link_supp, $form_notice);
			$form_notice = str_replace('!!link_annul!!', $link_annul, $form_notice);
			$form_notice = str_replace('!!link_remplace!!', $link_remplace, $form_notice);
			$form_notice = str_replace('!!link_duplicate!!', $link_duplicate, $form_notice);
			$form_notice = str_replace('!!link_z3950!!', $link_z3950, $form_notice);
			$form_notice = str_replace('!!link_audit!!', $link_audit, $form_notice);
			return $form_notice;
		}
		
		
		//creation formulaire droits d'acces pour notices
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
				if($this->id) {
	
					//profil ressource
					$def_prf=$dom_1->getComment('res_prf_def_lib');
					$res_prf=$dom_1->getResourceProfile($this->id);
					$q=$dom_1->loadUsedResourceProfiles();
					
					//recuperation droits utilisateur
					$user_rights = $dom_1->getRights($PMBuserid,$this->id,3);
					
					if($user_rights & 2) {
						$p_sel = gen_liste($q,'prf_id','prf_name', 'res_prf[1]', '', $res_prf, '0', $def_prf , '0', $def_prf);
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
					$t_rights = $dom_1->getResourceRights($this->id);
									
					if (count($t_u)) {
		
						$h_tab = "<div class='dom_div'><table class='dom_tab'><tr>";
						foreach($t_u as $k=>$v) {
							$h_tab.= "<th class='dom_col'>".htmlentities($v, ENT_QUOTES, $charset)."</th>";			
						}
						$h_tab.="</tr><!-- rights_tab --></table></div>";
						
						$c_tab = '<tr>';
						foreach($t_u as $k=>$v) {
								
							$c_tab.= "<td><table style='border:1px solid;' ><!-- rows --></table></td>";
							$t_rows = "";
									
							foreach($t_ctl as $k2=>$v2) {
															
								$t_rows.="
									<tr>
										<td style='width:25px;' ><input type='checkbox' name='chk_rights[1][".$k."][".$k2."]' value='1' ";
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
				if($this->id) {
					
					//profil ressource
					$def_prf=$dom_2->getComment('res_prf_def_lib');
					$res_prf=$dom_2->getResourceProfile($this->id);
					$q=$dom_2->loadUsedResourceProfiles();
					
					//Recuperation droits generiques utilisateur
					$user_rights = $dom_2->getDomainRights(0,$res_prf);
					
					if($user_rights & 2) {
						$p_sel = gen_liste($q,'prf_id','prf_name', 'res_prf[2]', '', $res_prf, '0', $def_prf , '0', $def_prf);
						$p_rad = "<input type='radio' name='prf_rad[2]' value='R' ";
						if ($gestion_acces_empr_notice_def!='1') $p_rad.= "checked='checked' ";
						$p_rad.= ">".htmlentities($msg['dom_rad_calc'],ENT_QUOTES,$charset)."</input><input type='radio' name='prf_rad[2]' value='C' ";
						if ($gestion_acces_empr_notice_def=='1') $p_rad.= "checked='checked' ";
						$p_rad.= ">".htmlentities($msg['dom_rad_def'],ENT_QUOTES,$charset)." $p_sel</input>";
						$r_form = str_replace('<!-- prf_rad -->', $p_rad, $r_form);
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
					$t_rights = $dom_2->getResourceRights($this->id);
									
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

		
		// ---------------------------------------------------------------
		//		replace_form : affichage du formulaire de remplacement
		// ---------------------------------------------------------------
		function replace_form() {
			global $notice_replace;
			global $msg;
			global $include_path;
		
			// a completer
			if(!$this->id) {
				require_once("$include_path/user_error.inc.php");
				error_message($msg[161], $msg[162], 1, './catalog.php');
				return false;
			}
		
			$notice_replace=str_replace('!!old_notice_libelle!!', $this->tit1." - ".$this->code, $notice_replace);
			$notice_replace=str_replace('!!id!!', $this->id, $notice_replace);
			print $notice_replace;
			return true;
		}
		
		// ---------------------------------------------------------------
		//		replace($by) : remplacement de la notice
		// ---------------------------------------------------------------
		function replace($by) {
		
			global $msg;
			global $dbh;
		
			if($this->id == $by) {
				return $msg[223];
			}
			if (($this->id == $by) || (!$this->id)) {
				return $msg[223];
			}
		
			$by_notice= new notice($by);
			if ($this->biblio_level != $by_notice->biblio_level || $this->hierar_level != $by_notice->hierar_level) {
				return $msg[catal_rep_not_err1];
			}
				
			// remplacement dans les exemplaires numériques
			$requete = "UPDATE explnum SET explnum_notice='$by' WHERE explnum_notice='$this->id' ";
			mysql_query($requete, $dbh);
			
			// remplacement dans les exemplaires
			$requete = "UPDATE exemplaires SET expl_notice='$by' WHERE expl_notice='$this->id' ";
			mysql_query($requete, $dbh);
			
			// remplacement dans les depouillements
			$requete = "UPDATE analysis SET analysis_notice='$by' WHERE analysis_notice='$this->id' ";
			mysql_query($requete, $dbh);
			
			// remplacement dans les bulletins
			$requete = "UPDATE bulletins SET bulletin_notice='$by' WHERE bulletin_notice='$this->id' ";
			mysql_query($requete, $dbh);
			
			// remplacement dans les notices filles
			/*$requete = "UPDATE notices_relations SET num_notice='$by' WHERE num_notice='$this->id' ";
			@mysql_query($requete, $dbh);
			$requete = "UPDATE notices_relations SET linked_notice='$by' WHERE linked_notice='$this->id' ";
			@mysql_query($requete, $dbh);*/
			
			// remplacement dans les resas
			$requete = "UPDATE resa SET resa_idnotice='$by' WHERE resa_idnotice='$this->id' ";
			mysql_query($requete, $dbh);

			//Suppression de la notice
			notice::del_notice($this->id);
			return FALSE;
		}
		
		function del_notice ($id) {

			global $dbh ;
			
			$p_perso=new parametres_perso("notices");
			$p_perso->delete_values($id);
			
			$requete = "DELETE FROM notices_categories WHERE notcateg_notice='$id'" ;
			@mysql_query($requete, $dbh);
		
			$requete = "DELETE FROM notices_langues WHERE num_notice='$id'" ;
			@mysql_query($requete, $dbh);
			
			$requete = "DELETE FROM notices WHERE notice_id='$id'" ;
			@mysql_query($requete, $dbh);
			audit::delete_audit (AUDIT_NOTICE, $id) ;
			
			// Effacement de l'occurence de la notice ds la table notices_global_index :
			$requete = "DELETE FROM notices_global_index WHERE num_notice=".$id;
			@mysql_query($requete, $dbh);
			
			// Effacement des occurences de la notice ds la table notices_mots_global_index :
			$requete = "DELETE FROM notices_mots_global_index WHERE id_notice=".$id;
			@mysql_query($requete, $dbh);
			
			$requete = "delete from notices_relations where num_notice='$id' OR linked_notice='$id' ";
			@mysql_query($requete, $dbh);
					
			// elimination des docs numeriques
			$requete = "DELETE FROM explnum WHERE explnum_notice='$id'" ;
			@mysql_query($requete, $dbh);
			
			$requete = "DELETE FROM responsability WHERE responsability_notice='$id'" ;
			@mysql_query($requete, $dbh);
				
			$requete = "DELETE FROM bannette_contenu WHERE num_notice='$id'" ;
			@mysql_query($requete, $dbh);
				
			$requete = "delete from caddie_content using caddie, caddie_content where caddie_id=idcaddie and type='NOTI' and object_id='".$id."' ";
			@mysql_query($requete, $dbh);
			
			$requete = "delete from analysis where analysis_notice='".$id."' ";
			@mysql_query($requete, $dbh);

			$requete = "update bulletins set num_notice=0 where num_notice='".$id."' ";
			@mysql_query($requete, $dbh);	
			
			//Suppression de la reference a la notice dans la table suggestions
			$requete = "UPDATE suggestions set num_notice = 0 where num_notice=".$id;
			@mysql_query($requete, $dbh);	
				
			//suppression des droits d'acces user_notice
			$requete = "delete from acces_res_1 where res_num=".$id;
			@mysql_query($requete, $dbh);	
			
			//suppression des droits d'acces empr_notice
			$requete = "delete from acces_res_2 where res_num=".$id;
			@mysql_query($requete, $dbh);	
			
			//suppression des droits d'acces empr_notice
			$requete = "delete from avis where num_notice=".$id;
			@mysql_query($requete, $dbh);	
						
			// Supression des liens avec les titres uniformes
			$requete = "DELETE FROM notices_titres_uniformes WHERE ntu_num_notice='$id'" ;			
			@mysql_query($requete, $dbh);	
			
			//Suppression dans les listes de lecture partagées
			$requete = "SELECT id_liste, notices_associees from opac_liste_lecture" ;			
			$res=mysql_query($requete, $dbh);
			$id_tab=array();
			while(($notices=mysql_fetch_object($res))){
				$id_tab = explode(',',$notices->notices_associees);
				for($i=0;$i<sizeof($id_tab);$i++){
					if($id_tab[$i] == $id){
						unset($id_tab[$i]);
					}
				}
				$requete = "UPDATE opac_liste_lecture set notices_associees='".addslashes(implode(',',$id_tab))."' where id_liste='".$notices->id_liste."'";
				mysql_query($requete,$dbh);
			}
			
			// Suppression des résas 
			$requete = "DELETE FROM resa WHERE resa_idnotice=".$id;
			mysql_query($requete, $dbh);
			
			// Suppression des transferts_demande			
			$requete = "DELETE FROM transferts_demande using transferts_demande, transferts WHERE num_transfert=id_transfert and num_notice=".$id;
			mysql_query($requete, $dbh);
			// Suppression des transferts
			$requete = "DELETE FROM transferts WHERE num_notice=".$id;
			mysql_query($requete, $dbh);
			
		}
		
		// Donne les id des notices liés a une notice		
		function get_list_child($notice_id,$liste=array()){
			$tab=array();
			$liste[]=$notice_id;
			$requete="select num_notice as notice_id from notices_relations where linked_notice=".$notice_id." order by rank";						
			$res_child=@mysql_query($requete);
			if(mysql_num_rows($res_child)) {
				while (($child=mysql_fetch_object($res_child))) {
					if(!in_array($child->notice_id,$liste)) {
						$liste[]=$child->notice_id;
						$tab_tmp=notice::get_list_child($child->notice_id,$liste);					
						$tab=array_merge($tab,$tab_tmp);	
					}else {
						// cas de rebouclage d'une fille sur une mère: donc on sort.  
						$tab[]=$notice_id;
						return	$tab;				
					}
				}	
				mysql_free_result($res_child);
			}	
			$tab[]=$notice_id;
			return	$tab;
		}	
		
		function majNotices_clean_tags($notice=0) {
			global $dbh;

			$requete = "select index_l ,notice_id from notices where index_l is not null and index_l!='' ";
			if($notice) {				
				$requete.= " and notice_id = $notice ";
			}			
			$res = mysql_query($requete, $dbh);
		
			while (($r = mysql_fetch_object($res))) {	
				$requete = "update notices set index_l='".addslashes(clean_tags($r->index_l))."' where notice_id=".$r->notice_id;			
				mysql_query($requete, $dbh);
			}
		}	
						
		// Fonction statique pour la creation / maj d'un n-uplet dans la table "notices_global_index" lors de la creation ou mise a jour d'une notice.
		function majNoticesGlobalIndex($notice, $NoIndex = 1) {
			global $dbh;
			
			mysql_query("delete from notices_global_index where num_notice = ".$notice." AND no_index = ".$NoIndex,$dbh);
			$titres = mysql_query("select index_serie, tnvol, index_wew, index_sew, index_l, index_matieres, n_gen, n_contenu, n_resume, index_n_gen, index_n_contenu, index_n_resume, eformat from notices where notice_id = ".$notice, $dbh);
		   	$mesNotices = mysql_fetch_assoc($titres);
			$tit = $mesNotices['index_wew'];
			$indTit = $mesNotices['index_sew'];
			$indMat = $mesNotices['index_matieres'];
			$indL = $mesNotices['index_l'];
			$indResume = $mesNotices['index_n_resume'];
			$indGen = $mesNotices['index_n_gen'];
			$indContenu = $mesNotices['index_n_contenu'];
			$resume = $mesNotices['n_resume'];
			$gen = $mesNotices['n_gen'];
			$contenu = $mesNotices['n_contenu'];
			$indSerie = $mesNotices['index_serie'];
			$tvol = $mesNotices['tnvol'];
			$eformatlien = $mesNotices['eformat'];
		   	$infos_global=' '.$tvol.' '.$tit.' '.$resume.' '.$gen.' '.$contenu.' '.$indL.' ';
		   	$infos_global_index=' '.$indSerie.' '.$indTit.' '.$indResume.' '.$indGen.' '.$indContenu.' '.$indMat.' ';
			
			
		   	// Authors : 
		   	$auteurs = mysql_query("select author_type, author_name, author_rejete, author_date, author_lieu,author_ville,author_pays,author_numero,author_subdivision, index_author from authors, responsability WHERE responsability_author = author_id AND responsability_notice = $notice", $dbh);
		   	$numA = mysql_num_rows($auteurs);
		   	for($j=0;$j < $numA; $j++) {
		   		$mesAuteurs = mysql_fetch_assoc($auteurs);
		   		$infos_global.= 
		   			$mesAuteurs['author_name'].' '.
			   		$mesAuteurs['author_rejete'].' '.
			   		$mesAuteurs['author_lieu'].' '.
			   		$mesAuteurs['author_ville'].' '.
			   		$mesAuteurs['author_pays'].' '.
			   		$mesAuteurs['author_numero'].' '.
			   		$mesAuteurs['author_subdivision'].' ';
			   	if($mesAuteurs['author_type'] == "72") $infos_global.= ' '.$mesAuteurs['author_date'].' ';
			   	$infos_global_index.=strip_empty_chars(
			   		$mesAuteurs['author_name'].' '.
			   		$mesAuteurs['author_rejete'].' '.
			   		$mesAuteurs['author_lieu'].' '.
			   		$mesAuteurs['author_ville'].' '.
			   		$mesAuteurs['author_pays'].' '.
			   		$mesAuteurs['author_numero'].' '.
			   		$mesAuteurs['author_subdivision']).' ';
			   	if($mesAuteurs['author_type'] == "72") $infos_global_index.= strip_empty_chars($mesAuteurs['author_date']." ");
		   	}
		   	mysql_free_result($auteurs);
		   	
		   	// Nom du periodique associe a la notice de depouillement le cas echeant :
		   	$temp = mysql_query("select bulletin_notice, bulletin_titre, index_titre, index_wew, index_sew from analysis, bulletins, notices  WHERE analysis_notice=".$notice." and analysis_bulletin = bulletin_id and bulletin_notice=notice_id", $dbh);
		   	$numP = mysql_num_rows($temp);
		   	if ($numP) {
				// La notice appartient a un periodique, on selectionne le titre de periodique :
		   		$mesTemp = mysql_fetch_assoc($temp);
			  	$infos_global.= $mesTemp['index_wew'].' '.$mesTemp['bulletin_titre'].' '.$mesTemp['index_titre'].' ';
			  	$infos_global_index.=strip_empty_words($mesTemp['index_wew'].' '.$mesTemp['bulletin_titre'].' '.$mesTemp['index_titre']).' ';		   		
		   	}
		   	mysql_free_result($temp);
		   	
		   	// Categories : 
		   	$noeud = mysql_query("select libelle_categorie from notices_categories,categories where notcateg_notice = ".$notice." and notices_categories.num_noeud=categories.num_noeud order by ordre_categorie", $dbh);
		   	$numNoeuds = mysql_num_rows($noeud);
		   	// Pour chaque noeud trouve on cherche les noeuds parents et les noeuds fils :
		   	for($j=0;$j < $numNoeuds; $j++) {
		   		// On met a jour la table notices_global_index avec le noeud trouve:
			 	$mesNoeuds = mysql_fetch_assoc($noeud);
			   	$infos_global.= $mesNoeuds['libelle_categorie'].' ';
			 	$infos_global_index.= strip_empty_words($mesNoeuds['libelle_categorie']).' ';
		   	}
		   	
		   	// Sous-collection : 
		   	$subColls = mysql_query("select sub_coll_name, index_sub_coll from notices, sub_collections WHERE subcoll_id = sub_coll_id AND notice_id = ".$notice, $dbh);
		   	$numSC = mysql_num_rows($subColls);
		   	for($j=0;$j < $numSC; $j++) {
		   		$mesSubColl = mysql_fetch_assoc($subColls);
		   		$infos_global.=$mesSubColl['index_sub_coll'].' '.$mesSubColl['sub_coll_name'].' ';
		   		$infos_global_index.=strip_empty_words($mesSubColl['index_sub_coll'].' '.$mesSubColl['sub_coll_name']).' ';
		   	}
		   	mysql_free_result($subColls);
		   	
		   	// Indexation numerique : 
		   	$indexNums = mysql_query("select indexint_name, indexint_comment from notices, indexint WHERE indexint = indexint_id AND notice_id = ".$notice, $dbh);
		   	$numIN = mysql_num_rows($indexNums);
		   	for($j=0;$j < $numIN; $j++) {
		   		$mesindexNums = mysql_fetch_assoc($indexNums);
		   		$infos_global.=$mesindexNums['indexint_name'].' '.$mesindexNums['indexint_comment'].' ';
		   		$infos_global_index.=strip_empty_words($mesindexNums['indexint_name'].' '.$mesindexNums['indexint_comment']).' ';
		   	}
		   	mysql_free_result($indexNums);
		   	
		   	// Collection : 
		   	$Colls = mysql_query("select collection_name ,collection_issn from notices, collections WHERE coll_id = collection_id AND notice_id = ".$notice, $dbh);
		   	$numCo = mysql_num_rows($Colls);
		   	for($j=0;$j < $numCo; $j++) {
		   		$mesColl = mysql_fetch_assoc($Colls);
		   		$infos_global.= $mesColl['collection_name'].' '.$mesColl['collection_issn'].' ';
		   		$infos_global_index.=strip_empty_words($mesColl['collection_name']).' '.strip_empty_words($mesColl['collection_issn']).' ';
		   	}
		   	mysql_free_result($Colls);
		    	
		   	// Editeurs : 
		   	$editeurs = mysql_query("select ed_name from notices, publishers WHERE (ed1_id = ed_id OR ed2_id = ed_id) AND notice_id = ".$notice, $dbh);
		   	$numE = mysql_num_rows($editeurs);
		   	for($j=0;$j < $numE; $j++) {
		   		$mesEditeurs = mysql_fetch_assoc($editeurs);		   		
		   		$infos_global.= $mesEditeurs['ed_name'].' ';
		   		$infos_global_index.=strip_empty_chars($mesEditeurs['ed_name']).' ';	   		
		   	}
		   	mysql_free_result($editeurs);
		  
			mysql_free_result($titres);

			// Titres Uniformes : 
		   	$tu = mysql_query("select ntu_titre, tu_name, tu_tonalite from notices_titres_uniformes,titres_uniformes WHERE tu_id=ntu_num_tu and ntu_num_notice=".$notice, $dbh);
		   	$numtu = mysql_num_rows($tu);
		   	for($j=0;$j < $numtu; $j++) {
		   		$mesTu = mysql_fetch_assoc($tu);		   		
		   		$infos_global.=$mesTu['ntu_titre'].' '.$mesTu['tu_name'].' '.$mesTu['tu_tonalite'].' ';
		   		$infos_global_index.=strip_empty_words($mesTu['ntu_titre'].' '.$mesTu['tu_name'].' '.$mesTu['tu_tonalite']).' ';		   		   		
		   	}
		   	mysql_free_result($tu);			
		   	
			// indexer les cotes des etat des collections : 
			$p_perso=new parametres_perso("collstate");	
		   	$coll = mysql_query("select collstate_id, collstate_cote from collections_state WHERE id_serial=".$notice, $dbh);
		   	$numcoll = mysql_num_rows($coll);
		   	for($j=0;$j < $numcoll; $j++) {
		   		$mescoll = mysql_fetch_assoc($coll);		   		
		   		$infos_global.=$mescoll['collstate_cote'].' ';
		   		$infos_global_index.=strip_empty_words($mescoll['collstate_cote']).' ';	
		   		// champ perso cherchable		   	
				$mots_perso=$p_perso->get_fields_recherche($mescoll['collstate_id']);
				if($mots_perso) {
					$infos_global.= $mots_perso.' ';
					$infos_global_index.= strip_empty_words($mots_perso).' ';	
				}		   			   		
		   	}
		   	mysql_free_result($coll);	
	
		    // champ perso cherchable
		   	$p_perso=new parametres_perso("notices");	
			$mots_perso=$p_perso->get_fields_recherche($notice);
			if($mots_perso) {
				$infos_global.= $mots_perso.' ';
				$infos_global_index.= strip_empty_words($mots_perso).' ';	
			}
			
			// flux RSS éventuellement
			$eformat=array();
			$eformat = explode(' ', $eformatlien) ;
			if ($eformat[0]=='RSS' && $eformat[3]=='1') {
				$flux=strip_tags(affiche_rss($notice)) ;
				$infos_global_index.= strip_empty_words($flux).' ';
			}
			mysql_query("insert into notices_global_index SET num_notice=".$notice.",no_index =".$NoIndex.", infos_global='".addslashes($infos_global)."', index_infos_global='".addslashes($infos_global_index)."'" , $dbh);		
		}
		
		
		// Fonction statique pour la creation / maj d'un n-uplet dans la table "notices_mots_global_index" lors de la creation ou mise a jour d'une notice.
		function majNoticesMotsGlobalIndex($notice, $datatype='all') {

			global $include_path;
			global $dbh, $champ_base;
			
			//recuperation du fichier xml de configuration
			if(!count($champ_base)) {
				$fp=fopen($include_path."/indexation/notices/champs_base.xml","r");
	    		if ($fp) {
					$xml=fread($fp,filesize($include_path."/indexation/notices/champs_base.xml"));
				}
				fclose($fp);
				$champ_base=_parser_text_no_function_($xml,"INDEXATION");
			}
			$tableau=$champ_base;

			
			//analyse des donnees des tables
			$temp_not=array();
			$temp_not['t'][0][0]=$tableau['REFERENCE'][0][value] ;
			$temp_not['f'][0][0]=$tableau['REFERENCEKEY'][0][value] ;
			$temp_ext=array();
			$t_code_champ=array();
			
			for ($i=0;$i<count($tableau['FIELD']);$i++) { //pour chacun des champs decrits
				
				//recuperation de la liste des informations a mettre a jour
				if ( $datatype=='all' || ($datatype==$tableau['FIELD'][$i]['DATATYPE']) ) {
				
					if ($tableau['FIELD'][$i]['EXTERNAL']=="yes") {
						//Stockage de la structure pour un accès plus facile
						$temp_ext[$tableau['FIELD'][$i]['ID']]=$tableau['FIELD'][$i];
					} else {
						$temp_not['f'][0][$tableau['FIELD'][$i]['ID']]= $tableau['FIELD'][$i]['TABLEFIELD'][0][value];
					}
					$t_code_champ[]=$tableau['FIELD'][$i]['ID'];
				}
			}
			if (count($t_code_champ)) {

				$tab_req=array();
				//Recherche des champs directs
				if($datatype=='all') {
					$tab_req[0]= "select ".implode(',',$temp_not['f'][0])." from ".$temp_not['t'][0][0];
					$tab_req[0].=" where ".$tableau['REFERENCEKEY'][0][value]."='".$notice."'";
				}
				foreach($temp_ext as $k=>$v) {
					//Construction de la requete
					//Champs pour le select
					$select=array();
					for ($j=0;$j<count($v['TABLEFIELD']);$j++) {
						$select[]=$v['TABLEFIELD'][$j]["value"];
					}
					//Jointures
					$jointures=array();
					if ($v["LINK"][0]["TYPE"]=="nn") {
						$j=array();
						//1) jointure de la table de référence vers la table de lien
						$j["fromtable"]=$temp_not['t'][0][0];
						$j["totable"]=$v["LINK"][0]["TABLE"][0]["value"];
						$j["on"]=$j["fromtable"].".".$temp_not['f'][0][0]."=".$j["totable"].".".$v["LINK"][0]["REFERENCEFIELD"][0]["value"];
						$j["type"]="join";
						$jointures[]=$j;
						//2) jointure de la table de lien vers la table destination
						$j=array();
						$j["totable"]=$v["TABLE"][0]["value"];
						$j["on"]=$v["LINK"][0]["TABLE"][0]["value"].".".$v["LINK"][0]["EXTERNALFIELD"][0]["value"]."=".$j["totable"].".".$v["TABLEKEY"][0]["value"];
						$j["type"]="join";
						$jointures[]=$j;					
					} else if ($v["LINK"][0]["TYPE"]=="1n") {
						$j=array();
						//1) jointure de la table de référence vers la table liée
						$j["fromtable"]=$temp_not['t'][0][0];
						$j["totable"]=$v["TABLE"][0]["value"];
						$j["on"]=$j["fromtable"].".".$v["LINK"][0]["REFERENCEFIELD"][0]["value"]."=".$j["totable"].".".$v["TABLEKEY"][0]["value"];
						$j["type"]="join";
						$jointures[]=$j;
					}
					$tab_req[$k]="select ".implode(",",$select)." from ";
					$jointure="";
					foreach($jointures as $j) {
						if ($j["fromtable"]) $jointure.=" ".$j["fromtable"];
						$jointure.=" ".$j["type"]." ".$j["totable"]." on (".$j["on"].")";
					}
					$tab_req[$k].=$jointure." where ".$temp_not['t'][0][0].".".$temp_not['f'][0][0]."=".$notice;
				}

				//qu'est-ce qu'on efface?
				$req_del="delete from notices_mots_global_index where id_notice='".$notice."' and code_champ in ('".implode("','",$t_code_champ)."')";
				mysql_query($req_del,$dbh);
				
				//qu'est-ce qu'on met a jour ?
				$tab_insert=array(); 		
				foreach($tab_req as $k=>$v) {
					$r=mysql_query($v,$dbh);
					$tab_mots=array();
					if (mysql_num_rows($r)) {
						while(($tab_row=mysql_fetch_row($r))) {
							foreach($tab_row as $liste_mots) {
								if($liste_mots!='') {
									$tab_tmp=explode(' ',strip_empty_words($liste_mots));
									foreach($tab_tmp as $mot) {
										$tab_mots[$mot]++;
									}
								}
							}
						}
					}
					foreach ($tab_mots as $mot=>$qte) {
						$tab_insert[]="($notice,$k,'".$mot."',$qte)";
						//(id_notice,code_champ,mot,nbr_mot),
					}
				}
				$req_insert="insert into notices_mots_global_index values ".implode(',',$tab_insert);
				mysql_query($req_insert,$dbh);
		}
	}
	}
} # fin de declaration

