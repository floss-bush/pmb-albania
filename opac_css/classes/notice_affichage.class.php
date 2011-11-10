<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_affichage.class.php,v 1.268.2.7 2011-10-07 09:59:08 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($base_path."/classes/author.class.php");
require_once($base_path."/classes/collection.class.php");
require_once($base_path."/classes/subcollection.class.php");
require_once($base_path."/classes/categorie.class.php");
require_once($class_path."/publisher.class.php");
require_once($class_path."/serie.class.php");
require_once($class_path."/marc_table.class.php");
require_once($class_path."/parametres_perso.class.php");
require_once($class_path."/category.class.php");
require_once($include_path."/isbn.inc.php");
require_once($include_path."/rss_func.inc.php") ;
require_once($class_path."/resa_planning.class.php") ;
include_once($include_path."/templates/expl_list.tpl.php");
require_once($include_path."/resa_func.inc.php"); 
require_once($class_path."/tu_notice.class.php");
require_once($class_path."/collstate.class.php");
require_once("$class_path/acces.class.php");
require_once($class_path."/indexint.class.php");
require_once($class_path."/notice_affichage.ext.class.php");
require_once($include_path."/notice_authors.inc.php");
require_once($class_path."/enrichment.class.php");	

if (!count($tdoc)) $tdoc = new marc_list('doctype');
if (!count($fonction_auteur)) {
	$fonction_auteur = new marc_list('function');
	$fonction_auteur = $fonction_auteur->table;
}
if (!count($langue_doc)) {
	$langue_doc = new marc_list('lang');
	$langue_doc = $langue_doc->table;
}
if (!count($icon_doc)) {
	$icon_doc = new marc_list('icondoc');
	$icon_doc = $icon_doc->table;
}
if(!count($biblio_doc)) {
	$biblio_doc = new marc_list('nivbiblio');
	$biblio_doc = $biblio_doc->table;
}


// définition de la classe d'affichage des notices
class notice_affichage {
	var $notice_id		= 0;					// id de la notice à afficher
	var $notice_header	= "" ;					// titre + auteur principaux
	var $notice_header_without_html	= "" ;		// titre + auteur principaux sans <span>
	var $notice_header_with_link="" ;			// titre + auteur principaux avec un lien sur la notice
	var $notice_header_globe_link	= "" ;		// le globe du lien
			// le terme affichage correspond au code HTML qui peut être envoyé avec un print
	var $notice_isbd	= "" ;			// Affichage ISBD de la notice
	var $notice_public	= "" ;			// Affichage public PMB de la notice
	var $notice_indexations	= "" ;		// Affichage des indexations catégories et mots clés, peut être ajouté à $notice_isbd ou à $notice_public afin d'avoir l'affichage complet PMB
	var $notice_exemplaires	= "" ;		// Affichage des exemplaires, peut être ajouté à $notice_isbd ou à $notice_public afin d'avoir l'affichage complet PMB
	var $notice_explnum	= "" ;			// Affichage des exemplaires numériques, peut être ajouté à $notice_isbd ou à $notice_public afin d'avoir l'affichage complet PMB
	var $notice_notes	= "" ;			// Affichage des notes de contenu et résumé, peut être ajouté à $notice_isbd ou à $notice_public afin d'avoir l'affichage complet PMB
	var $notice;				// objet notice tel que fetché dans la table notices, 
						//		augmenté de $this->notice->serie_name si série il y a
						//		augmenté de n_gen, n_contenu, n_resume si on est allé les chercher car non ISBD standard
	var $responsabilites 	= array("responsabilites" => array(),"auteurs" => array());  // les auteurs avec tout ce qu'il faut
	var $categories 	= array();	// les id des categories
	var $auteurs_principaux	= "" ;		// ce qui apparait après le titre pour le header
  	var $auteurs_tous	= "" ;		// Tous les auteurs avec leur fonction
  	var $categories_toutes	= "" ;		// Toutes les catégories dans lesquelles est rangée la notice

	var $lien_rech_notice 		;
	var $lien_rech_auteur 		;
  	var $lien_rech_editeur 		;
  	var $lien_rech_serie 		;
  	var $lien_rech_collection 	;
  	var $lien_rech_subcollection 	;
  	var $lien_rech_indexint 	;
  	var $lien_rech_motcle 		;
  	var $lien_rech_categ 		;
  	var $lien_rech_perio 		;
  	var $lien_rech_bulletin 	;
 	var $liens = array();
 	
 	var $langues = array();
	var $languesorg = array();
  	
  	var $action		= '';	// URL à associer au header
	var $header		= '';	// chaine accueillant le chapeau de notice (peut-être cliquable)
	var $tit_serie		= '';	// titre de série si applicable
	var $tit1		= '';	// valeur du titre 1
	var $result		= '';	// affichage final
	var $isbd		= '';	// isbd de la notice en fonction du level défini
	var $expl		= 0;	// flag indiquant si on affiche les infos d'exemplaire
	var $link_expl		= '';	// lien associé à un exemplaire
	var $show_resa		= 0;	// flag indiquant si on affiche les infos de resa
	var $p_perso;
	var $cart_allowed = 0;
	var $avis_allowed = 0;
	var $tag_allowed = 0;
	var $sugg_allowed = 0;
	var $to_print = 0;
	var $affichage_resa_expl = "" ; // lien réservation, exemplaires et exemplaires numériques, en tableau comme il faut  
	var $affichage_expl = "" ;  // la même chose mais sans le lien réservation

	var $statut = 1 ;  			// Statut (id) de la notice
	var $statut_notice = "" ;  	// Statut (libellé) de la notice
	var $visu_notice = 1 ;  	// Visibilité de la notice à tout le monde
	var $visu_notice_abon = 0 ; // Visibilité de la notice aux abonnés uniquement
	var $visu_expl = 1 ;  		// Visibilité des exemplaires de la notice à tout le monde
	var $visu_expl_abon = 0 ;  	// Visibilité des exemplaires de la notice aux abonnés uniquement
	var $visu_explnum = 1 ;  	// Visibilité des exemplaires numériques de la notice à tout le monde
	var $visu_explnum_abon = 0 ;// Visibilité des exemplaires numériques de la notice aux abonnés uniquement
	
	var $childs = array() ; // filles de la notice
	var $notice_childs = "" ; // l'équivalent à afficher
	var $anti_loop="";
	var $seule = 0 ;
	var $premier = "PUBLIC" ;
	var $double_ou_simple = 2 ;
	var $avis_moyenne ; // Moyenne des  avis
	var $avis_qte; // Quantité d'un avis 
	
	var $antiloop=array();
	var $bulletin_id=0;		// id du bulletin s'il s'agit d'une notice de bulletin
	
	var $dom_2 = NULL;			// objet domain 
	var $rights = 0;			// droits d'acces emprunteur/notice
	var $header_only = 0;		// pour ne prendre que le nécessaire pour composer le titre
	var $parents = "";			// la chaine des parents, utilisée pour do_parents en isbd et en public
	var $no_header = 0 ;		// ne pas afficher de header, permet de masquer l'icône
	var $notice_header_without_doclink=""; // notice_header sans les icones de lien url et d'indication de documents numériques
	var $notice_header_doclink=""; // les icones de lien url et d'indication de documents numériques
	// constructeur------------------------------------------------------------
	function notice_affichage($id, $liens, $cart=0, $to_print=0,$header_only=0,$no_header=0) {
	  	// $id = id de la notice à afficher
	  	// $liens	 = tableau de liens tel que ci-dessous
	  	// $cart : afficher ou pas le lien caddie
	  	// $to_print = affichage mode impression ou pas
	  	
		global $opac_avis_allow;
		global $opac_allow_add_tag;
		global $opac_show_suggest_notice;
		global $gestion_acces_active,$gestion_acces_empr_notice;
		
		if (!$id) return;

		$id+=0;
		//droits d'acces emprunteur/notice
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$this->dom_2= $ac->setDomain(2);
			$this->rights= $this->dom_2->getRights($_SESSION['id_empr_session'], $id); 
		}	  	
		
	 	if (!$liens) $liens=array();
		$this->lien_rech_notice 		=       $liens['lien_rech_notice']; 
		$this->lien_rech_auteur 		=       $liens['lien_rech_auteur'];       
		$this->lien_rech_editeur 		=       $liens['lien_rech_editeur'];      
		$this->lien_rech_serie 			=       $liens['lien_rech_serie'];      
		$this->lien_rech_collection 	=       $liens['lien_rech_collection'];   
		$this->lien_rech_subcollection 	=       $liens['lien_rech_subcollection'];
		$this->lien_rech_indexint 		=       $liens['lien_rech_indexint'];     
		$this->lien_rech_motcle 		=       $liens['lien_rech_motcle'];       
		$this->lien_rech_categ 			=       $liens['lien_rech_categ'];        
		$this->lien_rech_perio 			=       $liens['lien_rech_perio'];        
		$this->lien_rech_bulletin 		=       $liens['lien_rech_bulletin']; 
		$this->liens = $liens;    
		$this->cart_allowed = $cart;
		$this->no_header = $no_header ;
		if ($to_print) {
			$this->avis_allowed = 0;
			$this->tag_allowed = 0;
			$this->sugg_allowed = 0;
		} else {
			$this->avis_allowed = $opac_avis_allow;
			$this->tag_allowed = $opac_allow_add_tag;
			$this->sugg_allowed = $opac_show_suggest_notice;
		}
			
		$this->to_print = $to_print;
		$this->header_only = $header_only;
	  	// $seule : si 1 la notice est affichée seule et dans ce cas les notices childs sont en mode dépliable
	  	global $seule ;
	  	$this->seule = $seule ;
	  	$this->docnum_allowed = 1;
	
	  	if(!$id) return;
		else {
			$this->notice_id = $id;
			if(!$this->fetch_data()) return;
		}
		global $memo_p_perso_notices;
		if(!$memo_p_perso_notices)	
			$memo_p_perso_notices=$this->p_perso=new parametres_perso("notices");
		else $this->p_perso=$memo_p_perso_notices;
	}

	// récupération des valeurs en table---------------------------------------
	function fetch_data() {
		
		global $dbh;
		
		if(is_null($this->dom_2)) {
			$requete = "SELECT notice_id, typdoc, tit1, tit2, tit3, tit4, tparent_id, tnvol, ed1_id, ed2_id, coll_id, subcoll_id, year, nocoll, mention_edition,code, npages, ill, size, accomp, lien, eformat, index_l, indexint, niveau_biblio, niveau_hierar, origine_catalogage, prix, n_gen, n_contenu, n_resume, statut, thumbnail_url, opac_visible_bulletinage ";
			$requete.= "FROM notices WHERE notice_id='".$this->notice_id."' ";
		} else {
			$requete = "SELECT notice_id, typdoc, tit1, tit2, tit3, tit4, tparent_id, tnvol, ed1_id, ed2_id, coll_id, subcoll_id, year, nocoll, mention_edition,code, npages, ill, size, accomp, lien, eformat, index_l, indexint, niveau_biblio, niveau_hierar, origine_catalogage, prix, n_gen, n_contenu, n_resume, thumbnail_url, opac_visible_bulletinage ";
			$requete.= "FROM notices ";
			$requete.= "WHERE notice_id='".$this->notice_id."'";
		}
		$myQuery = mysql_query($requete, $dbh);
		if(mysql_num_rows($myQuery)) {
			$this->notice = mysql_fetch_object($myQuery);
		} else {
			$this->statut_notice =        "" ;
			$this->statut =				  0 ;
			$this->visu_notice =          0 ;
			$this->visu_notice_abon =     0 ;
			$this->visu_expl =            0 ;
			$this->visu_expl_abon =       0 ;
			$this->visu_explnum =         0 ;
			$this->visu_explnum_abon =    0 ;
			$this->notice_id=0;
			$this->opac_visible_bulletinage=0;
			return 0 ;
		}
		
		if (!$this->notice->typdoc) $this->notice->typdoc='a';
		if ($this->notice->tparent_id) {
			$requete_serie = "SELECT serie_name FROM series WHERE serie_id='".$this->notice->tparent_id."' ";
			$myQuery_serie = mysql_query($requete_serie, $dbh);
			if (mysql_num_rows($myQuery_serie)) {
				$serie = mysql_fetch_object($myQuery_serie);
				$this->notice->serie_name = $serie->serie_name ; 
			}
		}
		// serials : si article
		if(!$this->header_only) if ($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2) $this->get_bul_info();
		if ($this->notice->niveau_biblio == 'b' && $this->notice->niveau_hierar == 2) $this->get_bul_info();	
		
		if(!$this->header_only)$this->fetch_categories();
		$this->fetch_auteurs();
		$this->fetch_titres_uniformes();
		$this->fetch_visibilite();
		if(!$this->header_only) $this->fetch_langues(0);
		if(!$this->header_only) $this->fetch_langues(1);
		if(!$this->header_only) $this->fetch_avis();
		
		$this->childs=array();
		if(!$this->header_only) {		
			if (is_null($this->dom_2)) {
				$acces_j='';
				$statut_j=',notice_statut';
				$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
			} else {
				$acces_j = $this->dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
				$statut_j = "";
				$statut_r = "";	
			}
		
			if ($this->notice->niveau_biblio =='b') {
				// notice de bulletins, les relations sont dans la table analysis
				$requete = "select analysis_notice as notice_id, 'd' as relation_type from analysis JOIN bulletins ON bulletin_id = analysis_bulletin, notices $acces_j  $statut_j ";
				$requete.= "where num_notice=$this->notice_id AND notice_id = analysis_notice $statut_r ";
				$requete.= "order by analysis_notice DESC";
			} else {
				// autres notices
				$requete ="select num_notice as notice_id,relation_type from notices_relations join notices on num_notice=notice_id $acces_j $statut_j ";
				$requete.= "where linked_notice='".$this->notice_id."' $statut_r order by relation_type, rank";
			}
			
			// on va pré-remplir les childs avec les parents dont le libellé de la relation en up ou down est le même.
			$this->get_parents_as_childs();
			
			$resultat=mysql_query($requete); // il y a des enfants ?
			if (mysql_num_rows($resultat)) {
				while (($r=mysql_fetch_object($resultat))) $this->childs[$r->relation_type][]=$r->notice_id;
			}
			$this->do_parents();
		}
		
		return mysql_num_rows($myQuery);
	} // fin fetch_data

	
	function fetch_visibilite() {
		global $dbh;
		global $hide_explnum;
		
		$requete = "SELECT opac_libelle, notice_visible_opac, expl_visible_opac, notice_visible_opac_abon, expl_visible_opac_abon, explnum_visible_opac, explnum_visible_opac_abon FROM notice_statut WHERE id_notice_statut='".$this->notice->statut."' ";
		$myQuery = mysql_query($requete, $dbh);
		if(mysql_num_rows($myQuery)) {
			$statut_temp = mysql_fetch_object($myQuery);
	
			$this->statut_notice =        $statut_temp->opac_libelle;
			$this->visu_notice =          $statut_temp->notice_visible_opac;
			$this->visu_notice_abon =     $statut_temp->notice_visible_opac_abon;
			$this->visu_expl =            $statut_temp->expl_visible_opac;
			$this->visu_expl_abon =       $statut_temp->expl_visible_opac_abon;
			$this->visu_explnum =         $statut_temp->explnum_visible_opac;
			$this->visu_explnum_abon =    $statut_temp->explnum_visible_opac_abon;
		
			if ($hide_explnum) {
				$this->visu_explnum=0;
				$this->visu_explnum_abon=0;
			}
		}
	} // fin fetch_visibilite()

	// récupération des auteurs ---------------------------------------------------------------------
	// retourne $this->auteurs_principaux = ce qu'on va afficher en titre du résultat
	// retourne $this->auteurs_tous = ce qu'on va afficher dans l'isbd
	// NOTE: now we have two functions:
	// 		fetch_auteurs()  	the pmb-standard one
	
	function fetch_auteurs() {
		global $fonction_auteur;
		global $dbh ;
		global $opac_url_base ;
	
		$this->responsabilites  = array() ;
		$auteurs = array() ;
		
		$res["responsabilites"] = array() ;
		$res["auteurs"] = array() ;
		
		$rqt = "SELECT author_id, responsability_fonction, responsability_type, author_type,author_name, author_rejete, author_type, author_date, author_see, author_web ";
		$rqt.= "FROM responsability, authors ";
		$rqt.= "WHERE responsability_notice='".$this->notice_id."' AND responsability_author=author_id ";
		$rqt.= "ORDER BY responsability_type, responsability_ordre " ;
		$res_sql = mysql_query($rqt, $dbh);
		while (($notice=mysql_fetch_object($res_sql))) {
			$responsabilites[] = $notice->responsability_type ;
			$info_bulle="";
			if($notice->author_type==72 || $notice->author_type==71) {			
				$congres=new auteur($notice->author_id);
				$auteur_isbd=$congres->isbd_entry;
				$auteur_titre=$congres->display;			
				$info_bulle=" title='".$congres->info_bulle."' ";
			} else {
				if ($notice->author_rejete) $auteur_isbd = $notice->author_rejete." ".$notice->author_name ;
				else  $auteur_isbd = $notice->author_name ;
				// on s'arrête là pour auteur_titre = "Prénom NOM" uniquement
				$auteur_titre = $auteur_isbd ;
				// on complète auteur_isbd pour l'affichage complet
				if ($notice->author_date) $auteur_isbd .= " (".$notice->author_date.")" ;
			}	
			// URL de l'auteur
			if ($notice->author_web) $auteur_web_link = " <a href='$notice->author_web' target='_blank'><img src='".$opac_url_base."images/globe.gif' border='0'/></a>";
			else $auteur_web_link = "" ;
			if (!$this->to_print) $auteur_isbd .= $auteur_web_link ;
			$auteur_isbd = inslink($auteur_isbd, str_replace("!!id!!", $notice->author_id, $this->lien_rech_auteur),$info_bulle) ;
			if ($notice->responsability_fonction) $auteur_isbd .= ", ".$fonction_auteur[$notice->responsability_fonction] ;
			$auteurs[] = array( 
					'id' => $notice->author_id,
					'fonction' => $notice->responsability_fonction,
					'responsability' => $notice->responsability_type,
					'name' => $notice->author_name,
					'rejete' => $notice->author_rejete,
					'date' => $notice->author_date,
					'type' => $notice->author_type,
					'fonction_aff' => $fonction_auteur[$notice->responsability_fonction],
					'auteur_isbd' => $auteur_isbd,
					'auteur_titre' => $auteur_titre
					) ;
		}
		if (!$responsabilites) $responsabilites = array();
		if (!$auteurs) $auteurs = array();
		$res["responsabilites"] = $responsabilites ;
		$res["auteurs"] = $auteurs ;
		$this->responsabilites = $res;
		
		// $this->auteurs_principaux 
		// on ne prend que le auteur_titre = "Prénom NOM"
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$this->auteurs_principaux = $auteur_0["auteur_titre"];
		} else {
			$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
			$aut1_libelle = array();
			for ($i = 0 ; $i < count($as) ; $i++) {
				$indice = $as[$i] ;
				$auteur_1 = $this->responsabilites["auteurs"][$indice] ;			
				if($auteur_1["type"]==72 || $auteur_1["type"]==72) {			
					$congres=new auteur($auteur_1["id"]);
					$aut1_libelle[]=$congres->display;
				} else {
					$aut1_libelle[]= $auteur_1["auteur_titre"];
				}	
			}
			$auteurs_liste = implode (" ; ",$aut1_libelle) ;
			if ($auteurs_liste) $this->auteurs_principaux = $auteurs_liste ;
		}
	
		// $this->auteurs_tous
		$mention_resp = array() ;
		$congres_resp = array() ;
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$mention_resp_lib = $auteur_0["auteur_isbd"];
			if($this->responsabilites["auteurs"][$as]["type"]==72) {
				$congres_resp[] = $mention_resp_lib ;
			} else {
				$mention_resp[] = $mention_resp_lib ;
			}	
		}
		
		$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$mention_resp_lib = $auteur_1["auteur_isbd"];
			if($this->responsabilites["auteurs"][$indice]["type"]==72) {
				$congres_resp[] = $mention_resp_lib ;
			} else {
				$mention_resp[] = $mention_resp_lib ;
			}	
		}
		
		$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			$mention_resp_lib = $auteur_2["auteur_isbd"];
			if($this->responsabilites["auteurs"][$indice]["type"]==72) {
				$congres_resp[] = $mention_resp_lib ;
			} else {
				$mention_resp[] = $mention_resp_lib ;
			}		
		}
		
		$libelle_mention_resp = implode (" ; ",$mention_resp) ;
		if ($libelle_mention_resp) $this->auteurs_tous = $libelle_mention_resp ;
		else $this->auteurs_tous ="" ;
		
		$libelle_congres_resp = implode (" ; ",$congres_resp) ;
		if ($libelle_congres_resp) $this->congres_tous = $libelle_congres_resp ;
		else $this->congres_tous ="" ;
		
	} // fin fetch_auteurs
	
	
	// récupération des categories ------------------------------------------------------------------
	function fetch_categories() {
		global $opac_thesaurus, $opac_categories_categ_in_line, $pmb_keyword_sep, $opac_categories_affichage_ordre;
		global $dbh;
		global $lang,$opac_categories_show_only_last;
		global $categories_memo,$libelle_thesaurus_memo;
		global $categories_top;
		
		$categ_repetables = array() ;	
		if(!count($categories_top)) {		
			$q = "select num_thesaurus,id_noeud from noeuds where num_parent in(select id_noeud from noeuds where autorite='TOP') ";
			$r = mysql_query($q, $dbh);
			while(($res = mysql_fetch_object($r))) {
				$categories_top[]=$res->id_noeud;		
			}		
		}	
		$requete = "select * from (
			select libelle_thesaurus, c0.libelle_categorie as categ_libelle, n0.id_noeud , n0.num_parent, langue_defaut,id_thesaurus, if(c0.langue = '".$lang."',2, if(c0.langue= thesaurus.langue_defaut ,1,0)) as p, ordre_vedette, ordre_categorie
			FROM noeuds as n0, categories as c0,thesaurus,notices_categories 
			where notices_categories.num_noeud=n0.id_noeud and n0.id_noeud = c0.num_noeud and n0.num_thesaurus=id_thesaurus and 
			notices_categories.notcateg_notice=".$this->notice_id."	order by id_thesaurus, n0.id_noeud, p desc
			) as list_categ group by id_noeud";
		if ($opac_categories_affichage_ordre==1) $requete .= " order by ordre_vedette, ordre_categorie";
		
		$result_categ=@mysql_query($requete);
		if (mysql_num_rows($result_categ)) {
			while(($res_categ = mysql_fetch_object($result_categ))) {
				$libelle_thesaurus=$res_categ->libelle_thesaurus;
				$categ_id=$res_categ->id_noeud 	;
				$libelle_categ=$res_categ->categ_libelle ;
				$num_parent=$res_categ->num_parent ;
				$langue_defaut=$res_categ->langue_defaut ;
				$categ_head=0;
				if(in_array($categ_id,$categories_top))$categ_head=1;
				
				if ($opac_categories_show_only_last || $categ_head) {
					if ($opac_thesaurus) $catalog_form="[".$libelle_thesaurus."] ".$libelle_categ;	
					$libelle_aff_complet = inslink($libelle_categ,  str_replace("!!id!!", $categ_id, $this->lien_rech_categ));
					if ($opac_thesaurus) $categ_repetables[$libelle_thesaurus][] =$libelle_aff_complet;
					else $categ_repetables['MONOTHESAURUS'][] =$libelle_aff_complet ;
					
				} else {
					if(!$categories_memo[$categ_id]) {
						$anti_recurse[$categ_id]=1;
						$path_table='';
						$requete = "
						select id_noeud as categ_id, 
						num_noeud, num_parent as categ_parent, libelle_categorie as categ_libelle,
						num_renvoi_voir as categ_see, 
						note_application as categ_comment,
						if(langue = '".$lang."',2, if(langue= '".$langue_defaut."' ,1,0)) as p
						FROM noeuds, categories where id_noeud ='".$num_parent."' 
						AND noeuds.id_noeud = categories.num_noeud 
						order by p desc limit 1";
						
						$result=@mysql_query($requete);
						if (mysql_num_rows($result)) {
							$parent = mysql_fetch_object($result);
							$anti_recurse[$parent->categ_id]=1;
							$path_table[] = array(
										'id' => $parent->categ_id,
										'libelle' => $parent->categ_libelle);
							
							// on remonte les ascendants
							while (($parent->categ_parent)&&(!$anti_recurse[$parent->categ_parent])) {
								$requete = "select id_noeud as categ_id, num_noeud, num_parent as categ_parent, libelle_categorie as categ_libelle,	num_renvoi_voir as categ_see, note_application as categ_comment, if(langue = '".$lang."',2, if(langue= '".$langue_defaut."' ,1,0)) as p
									FROM noeuds, categories where id_noeud ='".$parent->categ_parent."' 
									AND noeuds.id_noeud = categories.num_noeud 
									order by p desc limit 1";
								$result=@mysql_query($requete);
								if (mysql_num_rows($result)) {
									$parent = mysql_fetch_object($result);
									$anti_recurse[$parent->categ_id]=1;
									$path_table[] = array(
												'id' => $parent->categ_id,
												'libelle' => $parent->categ_libelle);
								} else {
									break;
								}
							}
						$anti_recurse=array();
						} else $path_table=array();
						// ceci remet le tableau dans l'ordre général->particulier					
						$path_table = array_reverse($path_table);				
						if(sizeof($path_table)) {
							$temp_table='';
							while(list($xi, $l) = each($path_table)) {
								$temp_table[] = $l['libelle'];
							}
							$parent_libelle = join(':', $temp_table);
							$catalog_form = $parent_libelle.':'.$libelle_categ;
						} else {
							$catalog_form = $libelle_categ;
						}				
						// pour libellé complet mais sans le nom du thésaurus 
						$libelle_aff_complet = $catalog_form ;				
						
						if ($opac_thesaurus) $catalog_form="[".$libelle_thesaurus."] ".$catalog_form;	
							
						//$categ = new category($categ_id);
						$libelle_aff_complet = inslink($libelle_aff_complet,  str_replace("!!id!!", $categ_id, $this->lien_rech_categ));
						if ($opac_thesaurus) $categ_repetables[$libelle_thesaurus][] =$libelle_aff_complet;
						else $categ_repetables['MONOTHESAURUS'][] =$libelle_aff_complet ;
						
						$categories_memo[$categ_id]=$libelle_aff_complet;
						$libelle_thesaurus_memo[$categ_id]=$libelle_thesaurus;				
						
					} else {
						if ($opac_thesaurus) $categ_repetables[$libelle_thesaurus_memo[$categ_id]][] =$categories_memo[$categ_id];
						else $categ_repetables['MONOTHESAURUS'][] =$categories_memo[$categ_id] ;
					}					
				}
			}					
		}
			
		while (list($nom_tesaurus, $val_lib)=each($categ_repetables)) {
			//c'est un tri par libellé qui est demandé
			if ($opac_categories_affichage_ordre==0){
				$tmp=array();
				foreach ( $val_lib as $key => $value ) {
					$tmp[$key]=strip_tags($value);
				}
				$tmp=array_map("convert_diacrit",$tmp);//On enlève les accents
				$tmp=array_map("strtoupper",$tmp);//On met en majuscule
				asort($tmp);//Tri sur les valeurs en majuscule sans accent
				foreach ( $tmp as $key => $value ) {
	       			$tmp[$key]=$val_lib[$key];//On reprend les bons couples clé / libellé
				}
				$val_lib=$tmp;
			}
			if ($opac_thesaurus) {
				if (!$opac_categories_categ_in_line) {
					$categ_repetables_aff = "[".$nom_tesaurus."]".implode("<br />[".$nom_tesaurus."]",$val_lib) ;
				}else {
					$categ_repetables_aff = "<b>".$nom_tesaurus."</b><br />".implode(" $pmb_keyword_sep ",$val_lib) ;
				}
			} elseif (!$opac_categories_categ_in_line) {
				$categ_repetables_aff = implode("<br />",$val_lib) ;
			} else {
				$categ_repetables_aff = implode(" $pmb_keyword_sep ",$val_lib) ;
			}		
			if($categ_repetables_aff) $tmpcateg_aff .= "$categ_repetables_aff<br />";
		}
		$this->categories_toutes = $tmpcateg_aff;
	} // fin fetch_categories()
	
	//Titres uniformes
	function fetch_titres_uniformes() {	
		global $opac_url_base;
		$this->notice->tu= new tu_notice($this->notice_id);	
		$this->notice->tu_print_type_2=$this->notice->tu->get_print_type(2,$opac_url_base."/index.php?lvl=titre_uniforme_see&id=" );
	} // fin fetch_titres_uniformes()
	
	function fetch_langues($quelle_langues=0) {
		global $dbh;
		global $marc_liste_langues ;
		if (!$marc_liste_langues) $marc_liste_langues=new marc_list('lang');
	
		$langues = array() ;
		$rqt = "select code_langue from notices_langues where num_notice='$this->notice_id' and type_langue=$quelle_langues ";
		$res_sql = mysql_query($rqt, $dbh);
		while (($notice=mysql_fetch_object($res_sql))) {
			if ($notice->code_langue)
				$langues[] = array( 
					'lang_code' => $notice->code_langue,
					'langue' => $marc_liste_langues->table[$notice->code_langue]
					) ;
		}
		if (!$quelle_langues) $this->langues = $langues;
		else $this->languesorg = $langues;
	} // fin fetch_langues($quelle_langues=0)
	
	function fetch_avis() {
		global $dbh;
		
		$sql="select avg(note) as m from avis where valide=1 and num_notice='$this->notice_id' group by num_notice";
		$r = mysql_query($sql, $dbh);
		
		$sql_nb = "select * from avis where valide=1 and num_notice='$this->notice_id'";
		$r_nb = mysql_query($sql_nb, $dbh);	
		
		$qte_avis = mysql_num_rows($r_nb);
		$loc = mysql_fetch_object($r);
		if($loc->m > 0) $moyenne=number_format($loc->m,1, ',', '');
		$this->avis_moyenne = $moyenne;
		$this->avis_qte = $qte_avis;
	} // fin fetch_avis()
	
	function affichage_etat_collections() {
		global $msg;
		global $pmb_etat_collections_localise;
		
		$collstate=new collstate(0,$this->notice_id);
		if($pmb_etat_collections_localise)
			$collstate->get_display_list("",0,0,0,1);
		else 	
			$collstate->get_display_list("",0,0,0,0);	
		if($collstate->nbr) {
			$affichage.= "<h3><span id='titre_exemplaires'>".$msg["perio_etat_coll"]."</span></h3>";
			$affichage.=$collstate->liste;
		}
		return $affichage;
	} // fin affichage_etat_collections()
	
	
	function construit_liste_langues($tableau) {
		$langues = "";
		for ($i = 0 ; $i < sizeof($tableau) ; $i++) {
			if ($langues) $langues.=" ";
			$langues .= $tableau[$i]["langue"]." (<i>".$tableau[$i]["lang_code"]."</i>)";
		}
		return $langues;
	} // fin construit_liste_langues($tableau)
	
	// Fonction d'affichage des avis
	function affichage_avis($notice_id) {
		global $msg;
		$nombre_avis = "";
		//Affichage des Etoiles et nombre d'avis
		if ($this->avis_qte > 0) {
			$nombre_avis = "<a href='#' title=\"".$msg['notice_title_avis']."\" onclick=\"open('avis.php?todo=liste&noticeid=$notice_id','avis','width=600,height=290,scrollbars=yes,resizable=yes'); return false;\">".$this->avis_qte."&nbsp;".$msg['notice_bt_avis']."</a>";
			$etoiles_moyenne = $this->stars($this->avis_moyenne);	
			$img_tag .= $nombre_avis."<a href='#' title=\"".$msg['notice_title_avis']."\" onclick=\"open('avis.php?todo=liste&noticeid=$notice_id','avis','width=600,height=290,scrollbars=yes,resizable=yes'); return false;\">".$etoiles_moyenne."</a>";	
		} else {
			$nombre_avis = "<a href='#' title=\"".$msg['notice_title_avis']."\" onclick=\"open('avis.php?todo=liste&noticeid=$notice_id','avis','width=600,height=290,scrollbars=yes,resizable=yes'); return false;\">".$msg['avis_aucun']."</a>";
			$img_tag .= $nombre_avis;
		}
		return $img_tag;
	} // fin affichage_avis($notice_id)
	
	//Fonction d'affichage des suggestions
	function affichage_suggestion($notice_id){
		global $msg;
		$do_suggest="<a href='#' onclick=\"w=window.open('./do_resa.php?lvl=make_sugg&oresa=popup&id_notice=$notice_id','doresa','scrollbars=yes,width=600,height=600,menubar=0,resizable=yes'); w.focus(); return false;\">".$msg['suggest_notice_opac']."</a>";
		return $do_suggest;
	} // fin affichage_suggestion($notice_id)
	
	// Gestion des étoiles pour les avis
	function stars() {
		$etoiles_moyenne="";
		$cpt_star = 4;
		
		for ($i = 1; $i <= $this->avis_moyenne; $i++) {
			$etoiles_moyenne.="<img border=0 src='images/star.png' align='absmiddle'>";
		}
					
		if(substr($this->avis_moyenne,2) > 1) {
			$etoiles_moyenne .= "<img border=0 src='images/star-semibright.png' align='absmiddle'>";
			$cpt_star = 3;
		}
				
		for ( $j = round($this->avis_moyenne);$j <= $cpt_star ; $j++) {
			$etoiles_moyenne .= "<img border=0 src='images/star_unlight.png' align='absmiddle'>";
		}	
		return $etoiles_moyenne;
	} // fin stars()
	
	// génération du de l'affichage double avec onglets ---------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	function genere_double($depliable=1, $premier='ISBD') {
		global $msg;
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $opac_visionneuse_allow;
		global $opac_show_social_network;
		global $icon_doc,$biblio_doc,$tdoc;
		global $opac_notice_enrichment;
		global $allow_tag; // l'utilisateur a-t-il le droit d'ajouter un tag
		global $allow_sugg;// l'utilisateur a-t-il le droit de faire une suggestion
		global $lvl;	   // pour savoir qui demande l'affichage
		
		$this->result ="";
		if(!$this->notice_id) return;	
		$this->premier = $premier ;
		$this->double_ou_simple = 2 ;
		$this->notice_childs = $this->genere_notice_childs();
		if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\" class=\"img_basket\"><img src=\"images/basket_small_20x20.gif\" border=\"0\" title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
		else $basket="";
		
		//add tags
		if ( ($this->tag_allowed==1) || ( ($this->tag_allowed==2)&&($_SESSION["user_code"])&&($allow_tag) ) )
			$img_tag.="<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='images/tag.png' align='absmiddle' border='0' title=\"".$msg['notice_title_tag']."\" alt=\"".$msg['notice_title_tag']."\"></a>";	
		
		 //Avis
		if (($this->avis_allowed && $this->avis_allowed !=2)|| ($_SESSION["user_code"] && $this->avis_allowed ==2)) $img_tag .= $this->affichage_avis($this->notice_id);	
		
		//Suggestions
		if (($this->sugg_allowed ==2)|| ($_SESSION["user_code"] && ($this->sugg_allowed ==1) && $allow_sugg)) $img_tag .= $this->affichage_suggestion($this->notice_id);
		
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
	
		if ($this->no_header) $icon="";
		else $icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		if($opac_notice_enrichment ){
			$enrichment = new enrichment();
			if($enrichment->active[$this->notice->niveau_biblio.$this->notice->typdoc]){
				$source_enrichment = implode(",",$enrichment->active[$this->notice->niveau_biblio.$this->notice->typdoc]);
			}else if ($enrichment->active[$this->notice->niveau_biblio]){
				$source_enrichment = implode(",",$enrichment->active[$this->notice->niveau_biblio]);	
			}
		}
		if ($depliable) {
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_plus' src=\"".$opac_url_base."images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['expandable_notice']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\" />";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"".$opac_url_base."images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>".$this->notice_header_doclink."
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\" ".($source_enrichment ? "enrichment='".$source_enrichment."'" : "").">";
		} else {
			$template="<div class='parent'>$case_a_cocher";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"".$opac_url_base."images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>".$this->notice_header_doclink;
		}
	 	$template.="!!CONTENU!!
					!!SUITE!!</div>";
	
		if($this->notice->niveau_biblio != "b"){
			$this->permalink = "index.php?lvl=notice_display&id=".$this->notice_id;
		}else {
			$this->permalink = "index.php?lvl=bulletin_display&id=".$this->bulletin_id;
		}	
	
		if($opac_show_social_network){		
			$template_in.="
		<div class='addthis_toolbox addthis_default_style ' 
			addthis:url='".$opac_url_base."fb.php?title=".strip_tags($this->notice_header_without_html)."&url=".rawurlencode($this->permalink)."'>
			<a class='addthis_button_facebook_like' fb:like:layout='button_count'></a>
			<a class='addthis_button_tweet'></a>
			<a class='addthis_counter addthis_pill_style'></a>
		</div>";	
		}
		
		$template_in.="
		<ul id='onglets_isbd_public'>";
	    if ($premier=='ISBD'){ 
	    	if ($basket) $template_in.="
		    	<li id='baskets!!id!!' class='onglet_basket'>$basket</li>";
	    	$template_in.="
	    		<li id='onglet_isbd!!id!!' class='isbd_public_active'><a href='#' onclick=\"show_what('ISBD', '!!id!!'); return false;\">".$msg['ISBD']."</a></li>
	    		<li id='onglet_public!!id!!' class='isbd_public_inactive'><a href='#' onclick=\"show_what('PUBLIC', '!!id!!'); return false;\">".$msg['Public']."</a></li>
	    		<li id='tags!!id!!' class='onglet_tags'>$img_tag</li>
			</ul>
			<div id='div_isbd!!id!!' style='display:block;'>!!ISBD!!</div>
	  		<div id='div_public!!id!!' style='display:none;'>!!PUBLIC!!</div>";
	    } else { 
	    	if ($basket) $template_in.="
		    	<li id='baskets!!id!!' class='onglet_basket'>$basket</li>";
	    	$template_in.="
	  			<li id='onglet_public!!id!!' class='isbd_public_active'><a href='#' onclick=\"show_what('PUBLIC', '!!id!!'); return false;\">".$msg['Public']."</a></li>
				<li id='onglet_isbd!!id!!' class='isbd_public_inactive'><a href='#' onclick=\"show_what('ISBD', '!!id!!'); return false;\">".$msg['ISBD']."</a></li>
		    	<li id='tags!!id!!' class='onglet_tags'>$img_tag</li>
			</ul>
			<div id='div_public!!id!!' style='display:block;'>!!PUBLIC!!</div>
	  		<div id='div_isbd!!id!!' style='display:none;'>!!ISBD!!</div>";
	    }
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {	
			if($this->get_bulletins()){
				if ($lvl == "notice_display")$voir_bulletins="&nbsp;&nbsp;<a href='#tab_bulletin'><i>".$msg["see_bull"]."</i></a>";
				else $voir_bulletins="&nbsp;&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>";
			}
			//si visionneuse active...	
			if ($opac_visionneuse_allow)	{
				if($test=$this->get_bulletins_docnums()){
					$voir_docnum_bulletins="
					<script type='text/javascript' src='$opac_url_base/visionneuse/javascript/visionneuse.js'></script>
					<a href='#' onclick=\"open_visionneuse(sendToVisionneusePerio".$this->notice_id.");return false;\">".$msg["see_docnum_bull"]."</a>
					<script type='text/javascript'>
						function sendToVisionneusePerio".$this->notice_id."(){
							document.getElementById('visionneuseIframe').src = 'visionneuse.php?mode=perio_bulletin&idperio=".$this->notice_id."';
						}
					</script>";
				}
			}
			if($this->open_to_search()) {
				$search_in_serial ="&nbsp;<a href='index.php?lvl=index&search_type_asked=extended_search&search_in_perio=$this->notice_id'><i>".$msg["rechercher_in_serial"]."</i></a>";
			}
			$template_in = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>".$voir_bulletins.$voir_docnum_bulletins.$search_in_serial."&nbsp;!!ISBD!!", $template_in);
			$template_in = str_replace('!!PUBLIC!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>".$voir_bulletins.$voir_docnum_bulletins.$search_in_serial."&nbsp;!!PUBLIC!!", $template_in);
		} elseif ($this->notice->niveau_biblio =='a') { 
			$template_in = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!ISBD!!", $template_in);
			$template_in = str_replace('!!PUBLIC!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!PUBLIC!!", $template_in);
		} elseif ($this->notice->niveau_biblio =='b') { 
			$template_in = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_bul']."]</span>&nbsp;!!ISBD!!", $template_in);
			$template_in = str_replace('!!PUBLIC!!', "<span class='fond-article'>[".$msg['isbd_type_bul']."]</span>&nbsp;!!PUBLIC!!", $template_in);
		}
		
		$template_in = str_replace('!!ISBD!!', $this->notice_isbd, $template_in);
		$template_in = str_replace('!!PUBLIC!!', $this->notice_public, $template_in);
		$template_in = str_replace('!!id!!', $this->notice_id, $template_in);
		$this->do_image($template_in,$depliable);
	
		$this->result = str_replace('!!id!!', $this->notice_id, $template);
		if($this->notice_header_doclink){
			$this->result = str_replace('!!heada!!', $this->notice_header_without_doclink, $this->result);
		}else
			$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
		if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result); 		
		$this->result = str_replace('!!SUITE!!', "", $this->result);
	} // fin genere_double($depliable=1, $premier='ISBD')
	
	// génération du de l'affichage simple sans onglet ----------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	function genere_simple($depliable=1, $what='ISBD') {
		global $msg; 
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $opac_notice_enrichment;
		global $opac_show_social_network;
		global $icon_doc,$biblio_doc,$tdoc;
		global $opac_notice_enrichment;
		global $allow_tag ; // l'utilisateur a-t-il le droit d'ajouter un tag
		global $allow_sugg; // l'utilisateur a-t-il le droit de faire une suggestion
		global $lvl;		// pour savoir qui demande l'affichage
		
		if(!$this->notice_id) return;
		
		$this->double_ou_simple = 1 ;
		$this->notice_childs = $this->genere_notice_childs();
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
		
		if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\" class=\"img_basket\"><img src='".$opac_url_base."images/basket_small_20x20.gif' align='absmiddle' border='0' title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
		else $basket="";
		
		//add tags
		if (($this->tag_allowed==1)||(($this->tag_allowed==2)&&($_SESSION["user_code"])&&($allow_tag)))
			$img_tag.="<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='".$opac_url_base."images/tag.png' align='absmiddle' border='0' title=\"".$msg['notice_title_tag']."\" alt=\"".$msg['notice_title_tag']."\"></a>";
		
		 //Avis
		if (($this->avis_allowed && $this->avis_allowed !=2)|| ($_SESSION["user_code"] && $this->avis_allowed ==2))
			$img_tag .= $this->affichage_avis($this->notice_id);
		
		//Suggestions
		if (($this->sugg_allowed ==2)|| ($_SESSION["user_code"] && ($this->sugg_allowed ==1) && $allow_sugg)) $img_tag .= $this->affichage_suggestion($this->notice_id);	
		 
		if ($this->no_header) $icon="";
		else $icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		if($opac_notice_enrichment ){
			$enrichment = new enrichment();
			if($enrichment->active[$this->notice->niveau_biblio.$this->notice->typdoc]){
				$source_enrichment = implode(",",$enrichment->active[$this->notice->niveau_biblio.$this->notice->typdoc]);
			}else if ($enrichment->active[$this->notice->niveau_biblio]){
				$source_enrichment = implode(",",$enrichment->active[$this->notice->niveau_biblio]);	
			}
		}
		
		if ($depliable) { 
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_plus' src=\"".$opac_url_base."images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg["expandable_notice"]."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\"/>";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"".$opac_url_base."images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>".$this->notice_header_doclink."
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\" ".($source_enrichment ? "enrichment='".$source_enrichment."'" : "").">
	    		";			
		} else {
			$template="
				\n<div id=\"el!!id!!Parent\" class=\"parent\">
	    		$case_a_cocher";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"".$opac_url_base."images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}			
    		$template.="<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span><br />".$this->notice_header_doclink;
		}
		$template.="!!CONTENU!!
					!!SUITE!!</div>";
					
		if($this->notice->niveau_biblio != "b"){
			$this->permalink = "index.php?lvl=notice_display&id=".$this->notice_id;
		}else {
			$this->permalink = "index.php?lvl=bulletin_display&id=".$this->bulletin_id;
		}	
	
		if($opac_show_social_network){		
			$template_in.="
		<div class='addthis_toolbox addthis_default_style ' 
			addthis:url='".$opac_url_base."fb.php?title=".strip_tags($this->notice_header_without_html)."&url=".rawurlencode($this->permalink)."'>
			<a class='addthis_button_facebook_like' fb:like:layout='button_count'></a>
			<a class='addthis_button_tweet'></a>
			<a class='addthis_counter addthis_pill_style'></a>
		</div>";	
		}			
		
		if($basket || $img_tag || $opac_notice_enrichment){
			$template_in.="
		<ul id='onglets_isbd_public'>";
			if ($basket) $template_in.="<li id='baskets!!id!!' class='onglet_basket'>$basket</li>";
			if($opac_notice_enrichment){
				if($what =='ISBD') $template_in.="<li id='onglet_isbd!!id!!' class='isbd_public_active'><a href='#' onclick=\"show_what('ISBD', '!!id!!'); return false;\">".$msg['ISBD']."</a></li>";
				else $template_in.="<li id='onglet_public!!id!!' class='isbd_public_active'><a href='#' onclick=\"show_what('PUBLIC', '!!id!!'); return false;\">".$msg['Public']."</a></li>";
			}
			$template_in.="
	  			<li id='tags!!id!!' class='onglet_tags'>$img_tag</li>
		</ul>";	
		}
		
		if($what =='ISBD') $template_in.="		    	
				<div id='div_isbd!!id!!' style='display:block;'>!!ISBD!!</div>
	  			<div id='div_public!!id!!' style='display:none;'>!!PUBLIC!!</div>";
		else $template_in.="
		    	<div id='div_public!!id!!' style='display:block;'>!!PUBLIC!!</div>
				<div id='div_isbd!!id!!' style='display:none;'>!!ISBD!!</div>"
	  			; 	
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
			if($this->get_bulletins()){
				if ($lvl == "notice_display")$voir_bulletins="&nbsp;&nbsp;<a href='#tab_bulletin'><i>".$msg["see_bull"]."</i></a>";
				else $voir_bulletins="&nbsp;&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>";
			} 
			$template_in = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>$voir_bulletins&nbsp;!!ISBD!!", $template_in);
			$template_in = str_replace('!!PUBLIC!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>$voir_bulletins&nbsp;!!PUBLIC!!", $template_in);
		} elseif ($this->notice->niveau_biblio =='a') { 
			$template_in = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!ISBD!!", $template_in);
			$template_in = str_replace('!!PUBLIC!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!PUBLIC!!", $template_in);
		} elseif ($this->notice->niveau_biblio =='b') { 
			$template_in = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_bul']."]</span>&nbsp;!!ISBD!!", $template_in);
			$template_in = str_replace('!!PUBLIC!!', "<span class='fond-article'>[".$msg['isbd_type_bul']."]</span>&nbsp;!!PUBLIC!!", $template_in);
		}
		
		$template_in = str_replace('!!ISBD!!', $this->notice_isbd, $template_in);
		$template_in = str_replace('!!PUBLIC!!', $this->notice_public, $template_in);
		$template_in = str_replace('!!id!!', $this->notice_id, $template_in);
		$this->do_image($template_in,$depliable);
		
		
		$this->result = str_replace('!!id!!', $this->notice_id, $template);
		if($this->notice_header_doclink){
			$this->result = str_replace('!!heada!!', $this->notice_header_without_doclink, $this->result);
		}elseif($this->notice_header)
			$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		else $this->result = str_replace('!!heada!!', '', $this->result);
		$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
		
		if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result);
		else $this->result = str_replace('!!SUITE!!', '', $this->result);
				
	} // fin genere_simple($depliable=1, $what='ISBD')
	
	// génération de l'isbd----------------------------------------------------
	function do_isbd($short=0,$ex=1) {
		global $dbh;
		global $msg;
		global $tdoc;
		global $charset;
		global $opac_notice_affichage_class;
		global $memo_notice;
		
		$this->notice_isbd="";
		if(!$this->notice_id) return;

		// Notices parentes
		$this->notice_isbd.=$this->parents;
		
		// constitution de la mention de titre
		if($this->notice->serie_name) {
			$serie_temp .= inslink($this->notice->serie_name,  str_replace("!!id!!", $this->notice->tparent_id, $this->lien_rech_serie));
			if($this->notice->tnvol) $serie_temp .= ',&nbsp;'.$this->notice->tnvol;
		}
		if ($serie_temp) $this->notice_isbd .= $serie_temp.".&nbsp;".$this->notice->tit1 ;
		else $this->notice_isbd .= $this->notice->tit1;
	
		if ($this->notice->tit3) $this->notice_isbd .= "&nbsp;= ".$this->notice->tit3 ;
		if ($this->notice->tit4) $this->notice_isbd .= "&nbsp;: ".$this->notice->tit4 ;
		if ($this->notice->tit2) $this->notice_isbd .= "&nbsp;; ".$this->notice->tit2 ;
		
		$this->notice_isbd .= ' ['.$tdoc->table[$this->notice->typdoc].']';
		
		if ($this->auteurs_tous) $this->notice_isbd .= " / ".$this->auteurs_tous;
		if ($this->congres_tous) $this->notice_isbd .= " / ".$this->congres_tous;
		
		// mention d'édition
		if($this->notice->mention_edition) $this->notice_isbd .= " &nbsp;. -&nbsp; ".$this->notice->mention_edition;
		
		// zone de collection et éditeur
		if($this->notice->subcoll_id) {
			$collection = new subcollection($this->notice->subcoll_id);
			$editeurs .= inslink($collection->publisher_libelle, str_replace("!!id!!", $collection->publisher, $this->lien_rech_editeur));
			$collections = inslink($collection->isbd_entry,  str_replace("!!id!!", $this->notice->subcoll_id, $this->lien_rech_subcollection));
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$editeurs .= inslink($collection->publisher_libelle, str_replace("!!id!!", $collection->parent, $this->lien_rech_editeur));
			$collections = inslink($collection->isbd_entry,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection));
		} elseif ($this->notice->ed1_id) {
			$editeur = new publisher($this->notice->ed1_id);
			$editeurs .= inslink($editeur->isbd_entry,  str_replace("!!id!!", $this->notice->ed1_id, $this->lien_rech_editeur));
		}
		
		if($this->notice->ed2_id) {
			$editeur = new publisher($this->notice->ed2_id);
			$editeurs ? $editeurs .= '&nbsp;: '.inslink($editeur->isbd_entry,  str_replace("!!id!!", $this->notice->ed2_id, $this->lien_rech_editeur)) : $editeurs = inslink($editeur->isbd_entry,  str_replace("!!id!!", $this->notice->ed2_id, $this->lien_rech_editeur));
		}
	
		if($this->notice->year) $editeurs ? $editeurs .= ', '.$this->notice->year : $editeurs = $this->notice->year;
		elseif ($this->notice->niveau_biblio == 'm' && $this->notice->niveau_hierar == 0) 
				$editeurs ? $editeurs .= ', [s.d.]' : $editeurs = "[s.d.]";
	
		if($editeurs) $this->notice_isbd .= "&nbsp;.&nbsp;-&nbsp;$editeurs";
		
		// zone de la collation
		if($this->notice->npages) $collation = $this->notice->npages;
		if($this->notice->ill) $collation .= '&nbsp;: '.$this->notice->ill;
		if($this->notice->size) $collation .= '&nbsp;; '.$this->notice->size;
		if($this->notice->accomp) $collation .= '&nbsp;+ '.$this->notice->accomp;
		if($collation) $this->notice_isbd .= "&nbsp;.&nbsp;-&nbsp;$collation";
		if($collections) {
			if($this->notice->nocoll) $collections .= '; '.$this->notice->nocoll;
			$this->notice_isbd .= ".&nbsp;-&nbsp;($collections)".' ';
		}
	
		if(substr(trim($this->notice_isbd), -1) != "."){
			$this->notice_isbd .= '.';
		}
			
		// ISBN ou NO. commercial
		if($this->notice->code) {
			if(isISBN($this->notice->code)) $zoneISBN = '<b>ISBN</b>&nbsp;: ';
			else $zoneISBN .= '<b>'.$msg["issn"].'</b>&nbsp;: ';
			$zoneISBN .= $this->notice->code;
		}
		if($this->notice->prix) {
			if($this->notice->code) $zoneISBN .= '&nbsp;: '.$this->notice->prix;
			else { 
				if ($zoneISBN) $zoneISBN .= '&nbsp; '.$this->notice->prix;
				else $zoneISBN = $this->notice->prix;
			}
		}
		if($zoneISBN) $this->notice_isbd .= "<br />".$zoneISBN;
		
		// note générale
		if($this->notice->n_gen) $zoneNote = nl2br(htmlentities($this->notice->n_gen,ENT_QUOTES, $charset));
		if($zoneNote) $this->notice_isbd .= "<br />".$zoneNote;
				
	
		// langues
		if(count($this->langues)) {
			$langues = "<span class='etiq_champ'>${msg[537]}</span>&nbsp;: ".$this->construit_liste_langues($this->langues);
		}
		if(count($this->languesorg)) {
			$langues .= " <span class='etiq_champ'>${msg[711]}</span>&nbsp;: ".$this->construit_liste_langues($this->languesorg);
		}
		if ($langues) $this->notice_isbd .= "<br />".$langues ;
		
		if (!$short) {
			$this->notice_isbd .="<table>";
			$this->notice_isbd .= $this->aff_suite() ;
			$this->notice_isbd .="</table>";
		} else {
			$this->notice_isbd.=$this->genere_in_perio();
		}
	
		//etat des collections
		if ($this->notice->niveau_biblio=='s'&&$this->notice->niveau_hierar==1) $this->notice_isbd.=$this->affichage_etat_collections();	
	
		//Notices liées
		// ajoutées en dehors de l'onglet PUBLIC ailleurs
		
		if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
	} // fin do_isbd($short=0,$ex=1)
	
	// génération de l'affichage public----------------------------------------
	function do_public($short=0,$ex=1) {
		global $dbh;
		global $msg;
		global $tdoc;
		global $charset;
		global $memo_notice;
		global $opac_notice_affichage_class;
		
		$this->notice_public="";
		if(!$this->notice_id) return;

		// Notices parentes
		$this->notice_public.=$this->parents;
			
		$this->notice_public .= "<table>";
		// constitution de la mention de titre
		if ($this->notice->serie_name) {
			$this->notice_public.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['tparent_start']."</span></td><td>".inslink($this->notice->serie_name,  str_replace("!!id!!", $this->notice->tparent_id, $this->lien_rech_serie));;
			if ($this->notice->tnvol) $this->notice_public .= ',&nbsp;'.$this->notice->tnvol;
			$this->notice_public .="</td></tr>";
		}
		
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['title']." :</span></td>";
		$this->notice_public .= "<td><span class='public_title'>".$this->notice->tit1 ;
		
		if ($this->notice->tit4) $this->notice_public .= "&nbsp;: ".$this->notice->tit4 ;
		$this->notice_public.="</span></td></tr>";
		
		if ($this->notice->tit2) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t2']." :</span></td><td>".$this->notice->tit2."</td></tr>" ;
		if ($this->notice->tit3) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t3']." :</span></td><td>".$this->notice->tit3."</td></tr>" ;
		
		if ($tdoc->table[$this->notice->typdoc]) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['typdocdisplay_start']."</span></td><td>".$tdoc->table[$this->notice->typdoc]."</td></tr>";
		
		if ($this->auteurs_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['auteur_start']."</span></td><td>".$this->auteurs_tous."</td></tr>";
		if ($this->congres_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['congres_aff_public_libelle']."</span></td><td>".$this->congres_tous."</td></tr>";
		
		// mention d'édition
		if ($this->notice->mention_edition) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['mention_edition_start']."</span></td><td>".$this->notice->mention_edition."</td></tr>";
		
		// zone de l'éditeur 
		if ($this->notice->year)
			$annee = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['year_start']."</span></td><td>".$this->notice->year."</td></tr>" ;
	
		if ($this->notice->ed1_id) {
			$editeur = new publisher($this->notice->ed1_id);
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['editeur_start']."</span></td><td>".inslink($editeur->display,  str_replace("!!id!!", $this->notice->ed1_id, $this->lien_rech_editeur))."</td></tr>" ;
			if ($annee) {
				$this->notice_public .= $annee ;
				$annee = "" ;
			}  
		}
		// Autre editeur
		if ($this->notice->ed2_id) {
			$editeur_2 = new publisher($this->notice->ed2_id);
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_editor']."</span></td><td>".inslink($editeur_2->display,  str_replace("!!id!!", $this->notice->ed2_id, $this->lien_rech_editeur))."</td></tr>" ;
		}
		
		// collection  
		if ($this->notice->nocoll) $affnocoll = " ".str_replace("!!nocoll!!", $this->notice->nocoll, $msg['subcollection_details_nocoll']) ;
		else $affnocoll = "";
		if($this->notice->subcoll_id) {
			$subcollection = new subcollection($this->notice->subcoll_id);
			$collection = new collection($this->notice->coll_id);
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td>".inslink($collection->name,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection))." ".$collection->collection_web_link."</td></tr>" ;
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['subcoll_start']."</span></td><td>".inslink($subcollection->name,  str_replace("!!id!!", $this->notice->subcoll_id, $this->lien_rech_subcollection)) ;
			$this->notice_public .=$affnocoll."</td></tr>";
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td>".inslink($collection->isbd_entry,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection)) ;
			$this->notice_public .=$affnocoll." ".$collection->collection_web_link."</td></tr>";
		}
		
		// $annee est vide si ajoutée avec l'éditeur, donc si pas éditeur, on l'affiche ici
		$this->notice_public .= $annee ;
	
		// Titres uniformes
		if($this->notice->tu_print_type_2) {
			$this->notice_public.= 
			"<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['titre_uniforme_aff_public']."</span></td>
			<td>".$this->notice->tu_print_type_2."</td></tr>";
		}	
		// zone de la collation
		if($this->notice->npages) {
			if ($this->notice->niveau_biblio<>"a") {
				$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start']."</span></td><td>".$this->notice->npages."</td></tr>";
			} else {
				$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start_perio']."</span></td><td>".$this->notice->npages."</td></tr>";
			}
		}
		if ($this->notice->ill) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['ill_start']."</span></td><td>".$this->notice->ill."</td></tr>";
		if ($this->notice->size) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['size_start']."</span></td><td>".$this->notice->size."</td></tr>";
		if ($this->notice->accomp) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['accomp_start']."</span></td><td>".$this->notice->accomp."</td></tr>";
			
		// ISBN ou NO. commercial
		if ($this->notice->code) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['code_start']."</span></td><td>".$this->notice->code."</td></tr>";
	
		if ($this->notice->prix) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['price_start']."</span></td><td>".$this->notice->prix."</td></tr>";
	
		// note générale
		if ($this->notice->n_gen) $zoneNote = nl2br(htmlentities($this->notice->n_gen,ENT_QUOTES, $charset));
		if ($zoneNote) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_gen_start']."</span></td><td>".$zoneNote."</td></tr>";
	
		// langues
		if (count($this->langues)) {
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['537']." :</span></td><td>".$this->construit_liste_langues($this->langues);
			if (count($this->languesorg)) $this->notice_public .= " <span class='etiq_champ'>".$msg['711']." :</span> ".$this->construit_liste_langues($this->languesorg);
			$this->notice_public.="</td></tr>";
		} elseif (count($this->languesorg)) {
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['711']." :</span></td><td>".$this->construit_liste_langues($this->languesorg)."</td></tr>"; 
		}
		if (!$short) $this->notice_public .= $this->aff_suite() ; 
		else $this->notice_public.=$this->genere_in_perio();
	
		$this->notice_public.="</table>\n";
		
		//etat des collections
		if ($this->notice->niveau_biblio=='s' && $this->notice->niveau_hierar==1) $this->notice_public.=$this->affichage_etat_collections();	
		
		// exemplaires, résas et compagnie
		if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
	
		return;
	} // fin do_public($short=0,$ex=1)
	
	// génération du header----------------------------------------------------
	function do_header() {

		global $opac_notice_reduit_format ;
		global $opac_url_base, $msg ;
		global $memo_notice;
		global $opac_visionneuse_allow;
		global $opac_url_base;
		$this->notice_header="";
		if(!$this->notice_id) return;	
		
		$type_reduit = substr($opac_notice_reduit_format,0,1);
		if ($type_reduit=="E" || $type_reduit=="P" ) {
			// peut-être veut-on des personnalisés ?
			$perso_voulus_temp = substr($opac_notice_reduit_format,2) ;
			if ($perso_voulus_temp!="") $perso_voulus = explode(",",$perso_voulus_temp);
		}
		
		if ($type_reduit=="E") {
			// zone de l'éditeur 
			if ($this->notice->ed1_id) {
				$editeur = new publisher($this->notice->ed1_id);
				$editeur_reduit = $editeur->display ;
				if ($this->notice->year) $editeur_reduit .= " - ".$this->notice->year." ";
			} elseif ($this->notice->year) { 
				// année mais pas d'éditeur et si pas un article
				if($this->notice->niveau_biblio != 'a' && $this->notice->niveau_hierar != 2) 	$editeur_reduit = $this->notice->year." ";
			}
		} else $editeur_reduit = "" ;
		
		//Champs personalisés à ajouter au réduit 
		if (!$this->p_perso->no_special_fields) {
			if (count($perso_voulus)) {
				$this->p_perso->get_values($this->notice_id) ;
				for ($i=0; $i<count($perso_voulus); $i++) {
					$perso_voulu_aff .= $this->p_perso->get_formatted_output($this->p_perso->values[$perso_voulus[$i]],$perso_voulus[$i])." " ;
				}
			} else $perso_voulu_aff = "" ;
		} else $perso_voulu_aff = "" ;
		
		//Si c'est un depouillement, ajout du titre et bulletin
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2 && $this->parent_title)  {
			 $aff_perio_title="<i>".$msg[in_serial]." ".$this->parent_title.", ".$this->parent_numero." (".($this->parent_date?$this->parent_date:"[".$this->parent_aff_date_date."]").")</i>";
		}
		
		//Si c'est une notice de bulletin ajout du titre et bulletin
		if($this->notice->niveau_biblio == 'b' && $this->notice->niveau_hierar == 2)  {
			$aff_bullperio_title = "<span class='isbulletinof'><i> ".($this->parent_date?sprintf($msg["bul_titre_perio"],$this->parent_title):sprintf($msg["bul_titre_perio"],$this->parent_title.", ".$this->parent_numero." [".$this->parent_aff_date_date."]"))."</i></span>";
		} else $aff_bullperio_title="";
		
		// récupération du titre de série
		// constitution de la mention de titre
		if($this->notice->serie_name) {
			$this->notice_header = $this->notice->serie_name;
			if($this->notice->tnvol) $this->notice_header .= ', '.$this->notice->tnvol;
		} elseif ($this->notice->tnvol) $this->notice_header .= $this->notice->tnvol;
		
		if ($this->notice_header) $this->notice_header .= ". ".$this->notice->tit1 ;
		else $this->notice_header = $this->notice->tit1;
		
		$this->notice_header .= $aff_bullperio_title;
		
		if ($this->notice->niveau_biblio =='m') {
			switch($type_reduit) {
				case '1':
					if ($this->notice->year != '') $this->notice_header.=' ('.htmlentities($this->notice->year,ENT_QUOTES,$charset).')';
					break;
				case '2':
					if ($this->notice->year != '' && $this->notice->niveau_biblio!='b') $this->notice_header.=' ('.htmlentities($this->notice->year, ENT_QUOTES, $charset).')';
					if ($this->notice->code != '') $this->notice_header.=' / '.htmlentities($this->notice->code, ENT_QUOTES, $charset);
					break;
				default:
					break;
			}
		}
		
		$this->notice_header_without_html = $this->notice_header;	
	
		$this->notice_header = "<span !!zoteroNotice!! class='header_title'>".$this->notice_header."</span>";	
		//on ne propose à Zotero que les monos et les articles...
		if($this->notice->niveau_biblio == "m" ||($this->notice->niveau_biblio == "a" && $this->notice->niveau_hierar == 2)) {
			$this->notice_header =str_replace("!!zoteroNotice!!"," notice='".$this->notice_id."' ",$this->notice_header);
		}else $this->notice_header =str_replace("!!zoteroNotice!!","",$this->notice_header);
		$notice_header_suite = "";
		if ($type_reduit=="T" && $this->notice->tit4) $notice_header_suite = " : ".$this->notice->tit4;
		if ($this->auteurs_principaux) $notice_header_suite .= " / ".$this->auteurs_principaux;
		if ($editeur_reduit) $notice_header_suite .= " / ".$editeur_reduit ;
		if ($perso_voulu_aff) $notice_header_suite .= " / ".$perso_voulu_aff ;
		if ($aff_perio_title) $notice_header_suite .= " ".$aff_perio_title;
		$this->notice_header_without_html .= $notice_header_suite ;
		//$this->notice_header .= $notice_header_suite."</span>";
		//Un  span de trop ?	
		$this->notice_header .= $notice_header_suite;
		//$this->notice_header.="&nbsp;<span id=\"drag_symbol_drag_noti_".$this->notice->notice_id."\" style=\"visibility:hidden\"><img src=\"images/drag_symbol.png\"\></span>";
		$this->notice_header_doclink="";
		if ($this->notice->lien) {
			if(!$this->notice->eformat) $info_bulle=$msg["open_link_url_notice"];
			else $info_bulle=$this->notice->eformat;
			// ajout du lien pour les ressource électroniques			
			$this->notice_header_doclink .= "&nbsp;<span class='notice_link'><a href=\"".$this->notice->lien."\" target=\"__LINK__\">";
			$this->notice_header_doclink .= "<img src=\"".$opac_url_base."images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
			$this->notice_header_doclink .= " alt=\"";
			$this->notice_header_doclink .= $info_bulle;
			$this->notice_header_doclink .= "\" title=\"";
			$this->notice_header_doclink .= $info_bulle;
			$this->notice_header_doclink .= "\">";
			$this->notice_header_doclink .= "</a></span>";			
		} 
		if ($this->notice->niveau_biblio == 'b') {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier, explnum_url FROM explnum, bulletins WHERE bulletins.num_notice = ".$this->notice_id." AND bulletins.bulletin_id = explnum.explnum_bulletin order by explnum_id";
		} else {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier,explnum_url FROM explnum WHERE explnum_notice = ".$this->notice_id." order by explnum_id";
		}
		$explnums = mysql_query($sql_explnum);
		$explnumscount = mysql_num_rows($explnums);

		if ( (is_null($this->dom_2) && $this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"])))  || ($this->rights & 16) ) {
			if ($explnumscount == 1) {
				$explnumrow = mysql_fetch_object($explnums);
				if ($explnumrow->explnum_nomfichier){
					if($explnumrow->explnum_nom == $explnumrow->explnum_nomfichier)	$info_bulle=$msg["open_doc_num_notice"].$explnumrow->explnum_nomfichier;
					else $info_bulle=$explnumrow->explnum_nom;
				}elseif ($explnumrow->explnum_url){
					if($explnumrow->explnum_nom == $explnumrow->explnum_url)	$info_bulle=$msg["open_link_url_notice"].$explnumrow->explnum_url;
					else $info_bulle=$explnumrow->explnum_nom;
				}	
				$this->notice_header_doclink .= "&nbsp;<span>";		
				if ($opac_visionneuse_allow && $this->docnum_allowed){
					$this->notice_header_doclink .="
					<script type='text/javascript' src='$opac_url_base/visionneuse/javascript/visionneuse.js'></script>
					<script type='text/javascript'>
						if(typeof(sendToVisionneuse) == 'undefined'){
							function sendToVisionneuse(explnum_id){
								document.getElementById('visionneuseIframe').src = 'visionneuse.php?'+(typeof(explnum_id) != 'undefined' ? 'explnum_id='+explnum_id+\"\" : '\'');
							}
						}
					</script>
					<a href='#' onclick=\"open_visionneuse(sendToVisionneuse,".$explnumrow->explnum_id.");return false;\" alt='$alt' title='$alt'>";
					
				}else{
					$this->notice_header_doclink .= "<a href=\"./doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">";
				}
				$this->notice_header_doclink .= "<img src=\"./images/globe_orange.png\" border=\"0\" align=\"middle\" hspace=\"3\"";
				$this->notice_header_doclink .= " alt=\"";
				$this->notice_header_doclink .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header_doclink .= "\" title=\"";
				$this->notice_header_doclink .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header_doclink .= "\">";
				$this->notice_header_doclink .= "</a></span>";
			} elseif ($explnumscount > 1) {
				$explnumrow = mysql_fetch_object($explnums);
				$info_bulle=$msg["info_docs_num_notice"];
				$this->notice_header_doclink .= "<img src=\"./images/globe_rouge.png\" alt=\"$info_bulle\" \" title=\"$info_bulle\" border=\"0\" align=\"middle\" hspace=\"3\">";
			}
		}
		$this->notice_header_without_doclink=$this->notice_header;
		$this->notice_header.=$this->notice_header_doclink;
		$memo_notice[$this->notice_id]["header"]=$this->notice_header;
		$memo_notice[$this->notice_id]["niveau_biblio"]	= $this->notice->niveau_biblio;
		
		$this->notice_header_with_link=inslink($this->notice_header, str_replace("!!id!!", $this->notice_id, $this->lien_rech_notice)) ;
	} // fin do_header()
	
	
	// Construction des parents-----------------------------------------------------
	function do_parents() {
		global $dbh;
		global $msg;
		global $charset;
		global $memo_notice;
		global $opac_notice_affichage_class;
		global $relation_listup, $parents_to_childs ;
		
		// Pour ne pas afficher en parents les liens transférer dans les childs
		if (sizeof($parents_to_childs)>0) $clause = " AND relation_type not in ('".implode("','", $parents_to_childs)."') ";

		// gestion des droits d'affichage des parents
		if (is_null($this->dom_2)) {
			$acces_j='';
			$statut_j=',notice_statut';
			$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
		} else {
			$acces_j = $this->dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
			$statut_j = "";
			$statut_r = "";	
		}
		
		//Recherche des notices parentes
		$requete="select linked_notice, relation_type, rank from notices_relations join notices on notice_id=linked_notice $acces_j $statut_j 
				where num_notice=".$this->notice_id." $clause $statut_r
				order by relation_type,rank";
		$result_linked=mysql_query($requete,$dbh);
		//Si il y en a, on prépare l'affichage
		if (!mysql_num_rows($result_linked)) {
			$this->parents = "";
			return ;
		}

		$this->parents = "";
		
		if (!$relation_listup) $relation_listup=new marc_list("relationtypeup");
		$r_type=array();
		$ul_opened=false;
		//Pour toutes les notices liées
		while (($r_rel=mysql_fetch_object($result_linked))) {			
			if ($opac_notice_affichage_class) $notice_affichage=$opac_notice_affichage_class; else $notice_affichage="notice_affichage";
			if($memo_notice[$r_rel->linked_notice]["header"]) {
				$parent_notice->notice_header=$memo_notice[$r_rel->linked_notice]["header"];	
			} else {
				$parent_notice=new $notice_affichage($r_rel->linked_notice,$this->liens,1,$this->to_print,1);
				$parent_notice->visu_expl = 0 ;
				$parent_notice->visu_explnum = 0 ;
				$parent_notice->do_header();
			}		
			//Présentation différente si il y en a un ou plusieurs
			if (mysql_num_rows($result_linked)==1) {
				// si une seule, peut-être est-ce une notice de bulletin, aller cherche $this>bulletin_id
				$this->parents.="<br /><b>".$relation_listup->table[$r_rel->relation_type]."</b> ";
				if ($this->lien_rech_notice) $this->parents.="<a href='".str_replace("!!id!!",$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>";
				$this->parents.=$parent_notice->notice_header;
				if ($this->lien_rech_notice) $this->parents.="</a>";
				$this->parents.="<br /><br />";
				// si une seule, peut-être est-ce une notice de bulletin, aller cherche $this->bulletin_id
				$rqbull="select bulletin_id from bulletins where num_notice=".$this->notice_id;
				$rqbullr=mysql_query($rqbull);
				$rqbulld=@mysql_fetch_object($rqbullr);
				$this->bulletin_id=$rqbulld->bulletin_id; 
			} else {
				if (!$r_type[$r_rel->relation_type]) {
					$r_type[$r_rel->relation_type]=1;
					if ($ul_opened) $this->parents.="</ul>"; 
					else { 
						$this->parents.="<br />"; 
						$ul_opened=true; 
					}
					$this->parents.="<b>".$relation_listup->table[$r_rel->relation_type]."</b>";
					$this->parents.="<ul class='notice_rel'>\n";
				}
				$this->parents.="<li>";
				if ($this->lien_rech_notice) $this->parents.="<a href='".str_replace("!!id!!",$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>";
				$this->parents.=$parent_notice->notice_header;
				if ($this->lien_rech_notice) $this->notice_public.="</a>";
				$this->parents.="</li>\n";
			}
			if (mysql_num_rows($result_linked)>1) 
				$this->parents.="</ul>\n";
		}
	return ;
	} // fin do_parents()
	
	// Construction des mots clé----------------------------------------------------
	function do_mots_cle() {
		global $pmb_keyword_sep ;
		if (!$pmb_keyword_sep) $pmb_keyword_sep=" ";
		
		if (!trim($this->notice->index_l)) return "";
		
		$tableau_mots = explode ($pmb_keyword_sep,trim($this->notice->index_l)) ;
	
		if (!sizeof($tableau_mots)) return "";
		for ($i=0; $i<sizeof($tableau_mots); $i++) {
			$mots=trim($tableau_mots[$i]) ;
			$tableau_mots[$i] = inslink($mots, str_replace("!!mot!!", urlencode($mots), $this->lien_rech_motcle)) ;
		}
		if(ord($pmb_keyword_sep)==0xa || ord($pmb_keyword_sep)==0xd) 	$mots_cles = implode("<br />", $tableau_mots);
		else $mots_cles = implode("&nbsp; ", $tableau_mots);
		return $mots_cles ; 
	}
	
	// récupération des info de bulletinage (si applicable)
	function get_bul_info() {
		global $dbh;
		global $msg;
		if ($this->notice->niveau_biblio == 'a') {
			// récupération des données du bulletin et de la notice apparentée
			$requete = "SELECT b.tit1,b.notice_id,a.*,c.*, date_format(date_date, '".$msg["format_date"]."') as aff_date_date "; 
			$requete .= "from analysis a, notices b, bulletins c";
			$requete .= " WHERE a.analysis_notice=".$this->notice_id;
			$requete .= " AND c.bulletin_id=a.analysis_bulletin";
			$requete .= " AND c.bulletin_notice=b.notice_id";
			$requete .= " LIMIT 1";
		} elseif ($this->notice->niveau_biblio == 'b') {
			// récupération des données du bulletin et de la notice apparentée
			$requete = "SELECT tit1,notice_id,b.*, date_format(date_date, '".$msg["format_date"]."') as aff_date_date "; 
			$requete .= "from bulletins b, notices";
			$requete .= " WHERE num_notice=$this->notice_id ";
			$requete .= " AND  bulletin_notice=notice_id ";
			$requete .= " LIMIT 1";
		}
		$myQuery = mysql_query($requete, $dbh);
		if (mysql_num_rows($myQuery)) {
			$parent = mysql_fetch_object($myQuery);
			$this->parent_title = $parent->tit1;
			$this->parent_id = $parent->notice_id;
			$this->bulletin_id = $parent->bulletin_id;
			$this->parent_numero = $parent->bulletin_numero;
			$this->parent_date = $parent->mention_date;
			$this->parent_date_date = $parent->date_date;
			$this->parent_aff_date_date = $parent->aff_date_date;
		}
	} // fin get_bul_info()
	
	// fonction de génération de ,la mention in titre du pério + numéro
	function genere_in_perio () {
		global $charset ;
		// serials : si article
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2) {	
			$bulletin = $this->parent_title;
			$notice_mere = inslink("<span class='perio_title'>".$this->parent_title."</span>", str_replace("!!id!!", $this->parent_id, $this->lien_rech_perio));
			if($this->parent_numero) $numero = $this->parent_numero." " ;
			// affichage de la mention de date utile : mention_date si existe, sinon date_date
			if ($this->parent_date) $date_affichee = " (".$this->parent_date.")";
			elseif ($this->parent_date_date) $date_affichee .= " [".formatdate($this->parent_date_date)."]";
			else $date_affichee="" ;
			$bulletin = inslink("<span class='bull_title'>".$numero.$date_affichee."</span>", str_replace("!!id!!", $this->bulletin_id, $this->lien_rech_bulletin));
			$mention_parent = "<b>in</b> $notice_mere > $bulletin ";
			$retour .= "<br />$mention_parent";
			$pagination = htmlentities($this->notice->npages,ENT_QUOTES, $charset);
			if ($pagination) $retour .= ".&nbsp;-&nbsp;$pagination";
		}
		return $retour ;
	} // fin genere_in_perio ()
	
	// fonction d'affichage des exemplaires, résa et expl_num
	function aff_resa_expl() {
		global $opac_resa ;
		global $opac_max_resa ;
		global $opac_show_exemplaires ;
		global $msg;
		global $dbh;
		global $popup_resa ;
		global $opac_resa_popup ; // la résa se fait-elle par popup ?
		global $opac_resa_planning; // la résa est elle planifiée
		global $allow_book;
		global $opac_show_exemplaires_analysis;
		
		// afin d'éviter de recalculer un truc déjà calculé...
		if ($this->affichage_resa_expl_flag) return $this->affichage_resa_expl ;

		if ( (is_null($this->dom_2) && $opac_show_exemplaires && $this->visu_expl && (!$this->visu_expl_abon || ($this->visu_expl_abon && $_SESSION["user_code"]))) || ($this->rights & 8) ) {
	
			if (!$opac_resa_planning) {
				if($this->bulletin_id) $resa_check=check_statut(0,$this->bulletin_id) ;
				else $resa_check=check_statut($this->notice_id,0) ;
				// vérification si exemplaire réservable
				if ($resa_check) {
					// déplacé dans le IF, si pas visible : pas de bouton résa 
					if ($this->bulletin_id) $requete_resa = "SELECT count(1) FROM resa WHERE resa_idbulletin='$this->bulletin_id' ";
					else $requete_resa = "SELECT count(1) FROM resa WHERE resa_idnotice='$this->notice_id' ";
					$nb_resa_encours = mysql_result(mysql_query($requete_resa,$dbh), 0, 0) ;
					if ($nb_resa_encours) $message_nbresa = str_replace("!!nbresa!!", $nb_resa_encours, $msg["resa_nb_deja_resa"]) ;
					if (($this->notice->niveau_biblio=="m" || $this->notice->niveau_biblio=="b" || ($this->notice->niveau_biblio=="a" && $opac_show_exemplaires_analysis)) && ($_SESSION["user_code"] && $allow_book) && $opac_resa && !$popup_resa) {
						$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
						if ($opac_max_resa==0 || $opac_max_resa>$nb_resa_encours) {
							if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"if(confirm('".$msg["confirm_resa"]."')){w=window.open('./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&id_bulletin=".$this->bulletin_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;}else return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
							else $ret .= "<a href='./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&id_bulletin=".$this->bulletin_id."&oresa=popup' onClick=\"return confirm('".$msg["confirm_resa"]."')\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
							$ret .= $message_nbresa ;
						} else $ret .= str_replace("!!nb_max_resa!!", $opac_max_resa, $msg["resa_nb_max_resa"]) ; 
						$ret.= "<br />";
					} elseif ( ($this->notice->niveau_biblio=="m" || $this->notice->niveau_biblio=="b" || ($this->notice->niveau_biblio=="a" && $opac_show_exemplaires_analysis)) && !($_SESSION["user_code"]) && $opac_resa && !$popup_resa) {
						// utilisateur pas connecté
						// préparation lien réservation sans être connecté
						$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
						if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"if(confirm('".$msg["confirm_resa"]."')){w=window.open('./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&id_bulletin=".$this->bulletin_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;}else return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
						else $ret .= "<a href='./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&id_bulletin=".$this->bulletin_id."&oresa=popup' onClick=\"return confirm('".$msg["confirm_resa"]."')\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
						$ret .= $message_nbresa ;
						$ret .= "<br />";
					}
				} // fin if resa_check
				$temp = $this->expl_list($this->notice->niveau_biblio,$this->notice->notice_id, $this->bulletin_id);
				$ret .= $temp ;
				$this->affichage_expl = $temp ; 
			
			} else {
				// planning de réservations
				$nb_resa_encours = resa_planning::countResa($this->notice_id);
				if ($nb_resa_encours) $message_nbresa = str_replace("!!nbresa!!", $nb_resa_encours, $msg["resa_nb_deja_resa"]) ;
				if (($this->notice->niveau_biblio=="m") && ($_SESSION["user_code"] && $allow_book) && $opac_resa && !$popup_resa) {
					$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
					if ($opac_max_resa==0 || $opac_max_resa>$nb_resa_encours) {
						if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"w=window.open('./do_resa.php?lvl=resa_planning&id_notice=".$this->notice_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
						else $ret .= "<a href='./do_resa.php?lvl=resa_planning&id_notice=".$this->notice_id."&oresa=popup' id='bt_resa'>".$msg["bulletin_display_place_resa"]."</a>" ;
						$ret .= $message_nbresa ;
					} else $ret .= str_replace("!!nb_max_resa!!", $opac_max_resa, $msg["resa_nb_max_resa"]) ; 
					$ret.= "<br />";
				} elseif ( ($this->notice->niveau_biblio=="m") && !($_SESSION["user_code"]) && $opac_resa && !$popup_resa) {
					// utilisateur pas connecté
					// préparation lien réservation sans être connecté
					$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
					if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"w=window.open('./do_resa.php?lvl=resa_planning&id_notice=".$this->notice_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
					else $ret .= "<a href='./do_resa.php?lvl=resa_planning&id_notice=".$this->notice_id."&oresa=popup' id='bt_resa'>".$msg["bulletin_display_place_resa"]."</a>" ;
					$ret .= $message_nbresa ;
					$ret .= "<br />";
				}
		
				$temp = $this->expl_list($this->notice->niveau_biblio,$this->notice->notice_id, $this->bulletin_id);
				$ret .= $temp ;
				$this->affichage_expl = $temp ; 
			}
		}
		
		//affichage exemplaires numeriques
		if($this->docnum_allowed) $ret.= $this->aff_explnum();
		
		if (($autres_lectures = $this->autres_lectures($this->notice_id,$this->bulletin_id))) {
			$ret .= $autres_lectures;
		}
		$this->affichage_resa_expl = $ret ;
		$this->affichage_resa_expl_flag = 1 ;
		return $ret ;
	} 
	
	
	// fonction d'affichage des exemplaires numeriques
	function aff_explnum () {
		
		global $msg;
		$ret='';
		if ( (is_null($this->dom_2) && $this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) || ($this->rights & 16)){
			if ($this->notice->niveau_biblio=="b" && ($explnum = show_explnum_per_notice(0, $this->bulletin_id, ''))) {
				$ret .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
				$this->affichage_expl .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
			} elseif (($explnum = show_explnum_per_notice($this->notice_id,0, ''))) {
				$ret .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
				$this->affichage_expl .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
			}
		}		 
		return $ret;
	} // fin aff_explnum ()
	
	
	// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour éviter la redondance de calcul
	function aff_suite() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search, $opac_permalink, $opac_url_base;
		
		// afin d'éviter de recalculer un truc déjà calculé...
		if ($this->affichage_suite_flag) return $this->affichage_suite ;
		
		$ret .= $this->genere_in_perio () ;
		
		//Espace
		//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
		
		// toutes indexations
		$ret_index = "";
		// Catégories
		if ($this->categories_toutes) $ret_index .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['categories_start']."</span></td><td>".$this->categories_toutes."</td></tr>";
				
		// Affectation du libellé mots clés ou tags en fonction de la recherche précédente	
		if($opac_allow_tags_search == 1) $libelle_key = $msg['tags'];
		else $libelle_key = 	$msg['motscle_start'];
				
		// indexation libre
		$mots_cles = $this->do_mots_cle() ;
		if($mots_cles) $ret_index.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$libelle_key."</span></td><td>".nl2br($mots_cles)."</td></tr>";
			
		// indexation interne
		if($this->notice->indexint) {
			$indexint = new indexint($this->notice->indexint);
			$ret_index.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['indexint_start']."</span></td><td>".inslink($indexint->name,  str_replace("!!id!!", $this->notice->indexint, $this->lien_rech_indexint))." ".nl2br(htmlentities($indexint->comment,ENT_QUOTES, $charset))."</td></tr>" ;
		}
		if ($ret_index) {
			$ret.=$ret_index;
			//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
		}
		
		// résumé
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td class='td_resume'>".nl2br($this->notice->n_resume)."</td></tr>";
	
		// note de contenu
		if($this->notice->n_contenu) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
	
		//Champs personalisés
		$perso_aff = "" ;
		if (!$this->p_perso->no_special_fields) {
			// $this->memo_perso_ permet au affichages personalisés dans notice_affichage_ex de gagner du temps
			if(!$this->memo_perso_) $this->memo_perso_=$this->p_perso->show_fields($this->notice_id);			
			for ($i=0; $i<count($this->memo_perso_["FIELDS"]); $i++) {
				$p=$this->memo_perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]) $perso_aff .="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".strip_tags($p["TITRE"])."</span></td><td>".$p["AFF"]."</td></tr>";
			}
		}
		$ret .= $perso_aff ;
		
		if ($this->notice->lien) {
			//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
			$ret.="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["lien_start"]."</span></td><td>" ;
			if (substr($this->notice->eformat,0,3)=='RSS') {
				$ret .= affiche_rss($this->notice->notice_id) ;
			} else {
				if (strlen($this->notice->lien)>80) {
					$ret.="<a href=\"".$this->notice->lien."\" target=\"top\" class='lien856'>".htmlentities(substr($this->notice->lien, 0, 80),ENT_QUOTES,$charset)."</a>&nbsp;[...]";
				} else {
					$ret.="<a href=\"".$this->notice->lien."\" target=\"top\" class='lien856'>".htmlentities($this->notice->lien,ENT_QUOTES,$charset)."</a>";
				}
				$ret.="</td></tr>";
			}
			$ret.="</td></tr>";
			if ($this->notice->eformat && substr($this->notice->eformat,0,3)!='RSS') $ret.="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["eformat_start"]."</span></td><td>".htmlentities($this->notice->eformat,ENT_QUOTES,$charset)."</td></tr>";
		}
		// Permalink avec Id
		if ($opac_permalink) {
			if($this->notice->niveau_biblio != "b"){
				$ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='".$opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";	
			}else {
				$ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='".$opac_url_base."index.php?lvl=bulletin_display&id=".$this->bulletin_id."'>".substr($opac_url_base."index.php?lvl=bulletin_display&id=".$this->bulletin_id,0,80)."</a></td></tr>";
			}	
		}
		$this->affichage_suite = $ret ;
		$this->affichage_suite_flag = 1 ;
		return $ret ;
	} // fin aff_suite()
	
	// fonction de génération du tableau des exemplaires
	function expl_list($type,$id,$bull_id=0) {	
		global $dbh;
		global $msg, $charset;
		global $expl_list_header, $expl_list_footer;
		global $opac_expl_data, $opac_expl_order, $opac_url_base;
		global $pmb_transferts_actif,$transferts_statut_transferts;
		global $memo_p_perso_expl;
		global $opac_show_empty_items_block ;
		global $opac_show_exemplaires_analysis;

	global $expl_list_header_loc_tpl,$opac_aff_expl_localises;

	$nb_expl_autre_loc=0;
	$nb_perso_aff=0;
		// les dépouillements ou périodiques n'ont pas d'exemplaire
		if (($type=="a" && !$opac_show_exemplaires_analysis) || $type=="s") return "" ;
		if(!$memo_p_perso_expl)	$memo_p_perso_expl=new parametres_perso("expl");
		$header_found_p_perso=0;
		
		// les exemplaires des monographies
		if ($type=="m") {
			$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, docs_type.*, docs_codestat.*, lenders.*";
			$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl, docs_location, docs_section, docs_statut, docs_type, docs_codestat, lenders";
			$requete .= " WHERE expl_notice='$id' and expl_bulletin='$bull_id'";
			$requete .= " AND location_visible_opac=1 AND section_visible_opac=1";
			$requete .= " AND exemplaires.expl_location=docs_location.idlocation";
			$requete .= " AND exemplaires.expl_section=docs_section.idsection ";
			$requete .= " AND exemplaires.expl_statut=docs_statut.idstatut ";
			$requete .= " AND exemplaires.expl_typdoc=docs_type. idtyp_doc ";
			$requete .= " AND exemplaires.expl_codestat=docs_codestat.idcode ";
			$requete .= " AND exemplaires.expl_owner=lenders.idlender ";
			if ($opac_expl_order) $requete .= " ORDER BY $opac_expl_order ";
			$requete_resa = "SELECT count(1) from resa where resa_idnotice='$id' ";
		} // fin si "m"
		
		// les exemplaires des bulletins
		if ($type=="b") {
			$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, docs_type.*, docs_codestat.*, lenders.*";
			$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl, docs_location, docs_section, docs_statut, docs_type, docs_codestat, lenders";
			$requete .= " WHERE expl_notice='0' and expl_bulletin='$bull_id'";
			$requete .= " AND location_visible_opac=1 AND section_visible_opac=1";
			$requete .= " AND exemplaires.expl_location=docs_location.idlocation";
			$requete .= " AND exemplaires.expl_section=docs_section.idsection ";
			$requete .= " AND exemplaires.expl_statut=docs_statut.idstatut ";
			$requete .= " AND exemplaires.expl_typdoc=docs_type. idtyp_doc ";
			$requete .= " AND exemplaires.expl_codestat=docs_codestat.idcode ";
			$requete .= " AND exemplaires.expl_owner=lenders.idlender ";
			if ($opac_expl_order) $requete .= " ORDER BY $opac_expl_order ";
			$requete_resa = "SELECT count(1) from resa where resa_idbulletin='$bull_id' ";
		} // fin si "b"
		
		// les exemplaires des bulletins des articles affichés
		// ERICROBERT : A faire ici !
		if ($type=="a" && $opac_show_exemplaires_analysis) {
			$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, docs_type.*, docs_codestat.*, lenders.*";
			$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl, docs_location, docs_section, docs_statut, docs_type, docs_codestat, lenders";
			$requete .= " WHERE expl_notice='0' and expl_bulletin='$bull_id'";
			$requete .= " AND location_visible_opac=1 AND section_visible_opac=1";
			$requete .= " AND exemplaires.expl_location=docs_location.idlocation";
			$requete .= " AND exemplaires.expl_section=docs_section.idsection ";
			$requete .= " AND exemplaires.expl_statut=docs_statut.idstatut ";
			$requete .= " AND exemplaires.expl_typdoc=docs_type. idtyp_doc ";
			$requete .= " AND exemplaires.expl_codestat=docs_codestat.idcode ";
			$requete .= " AND exemplaires.expl_owner=lenders.idlender ";
			if ($opac_expl_order) $requete .= " ORDER BY $opac_expl_order ";
			$requete_resa = "SELECT count(1) from resa where resa_idbulletin='$bull_id' ";
		} // fin si "a"
		
		// récupération du nombre d'exemplaires
		$res = mysql_query($requete, $dbh);
		
		if (!$opac_expl_data) $opac_expl_data="expl_cb,expl_cote,tdoc_libelle,location_libelle,section_libelle";
		$colonnesarray=explode(",",$opac_expl_data);
		
		$expl_list_header_deb="<tr>";
		for ($i=0; $i<count($colonnesarray); $i++) {
			eval ("\$colencours=\$msg[expl_header_".$colonnesarray[$i]."];");
			$expl_list_header_deb.="<th class='expl_header_".$colonnesarray[$i]."'>".htmlentities($colencours,ENT_QUOTES, $charset)."</th>";
		}
		$expl_list_header_deb.="<th>$msg[statut]</th>";
		$expl_liste="";
		$nb_resa = mysql_result(mysql_query($requete_resa, $dbh),0,0);
		while(($expl = mysql_fetch_object($res))) {
			$compteur = $compteur+1;
			$expl_liste .= "<tr>";
			$colencours="";
			for ($i=0; $i<count($colonnesarray); $i++) {
				eval ("\$colencours=\$expl->".$colonnesarray[$i].";");
				if ($colonnesarray[$i]=="location_libelle" && $expl->num_infopage) 
					$expl_liste.="<td class='".$colonnesarray[$i]."'><a href=\"".$opac_url_base."index.php?lvl=infopages&pagesid=".$expl->num_infopage."\" alt=\"".$msg['location_more_info']."\" title=\"".$msg['location_more_info']."\">".htmlentities($colencours, ENT_QUOTES, $charset)."</a></td>";
				else 
					$expl_liste.="<td class='".$colonnesarray[$i]."'>".htmlentities($colencours,ENT_QUOTES, $charset)."</td>";
			}
	
			$requete_resa = "SELECT count(1) from resa where resa_cb='$expl->expl_cb' ";
			$flag_resa = mysql_result(mysql_query($requete_resa, $dbh),0,0);
			$requete_resa = "SELECT count(1) from resa_ranger where resa_cb='$expl->expl_cb' ";
			$flag_resa = $flag_resa + mysql_result(mysql_query($requete_resa, $dbh),0,0);
			$situation = "";
			if ($flag_resa) {
				$nb_resa--;
				$situation = "<strong>$msg[expl_reserve]</strong>";
			} else {
				if ($expl->pret_flag) {
					if($expl->pret_retour) { // exemplaire sorti
						global $opac_show_empr ;
						if ((($opac_show_empr==1) && ($_SESSION["user_code"])) || ($opac_show_empr==2)) {
							$rqt_empr = "SELECT empr_nom, empr_prenom, id_empr, empr_cb FROM empr WHERE id_empr='$expl->pret_idempr' ";
							$res_empr = mysql_query ($rqt_empr, $dbh) ;
							$res_empr_obj = mysql_fetch_object ($res_empr) ;
							$situation = $msg[entete_show_empr].htmlentities(" $res_empr_obj->empr_prenom $res_empr_obj->empr_nom",ENT_QUOTES, $charset)."<br />";
						} 
						$situation .= "<strong>$msg[out_until] ".formatdate($expl->pret_retour).'</strong>';
						// ****** Affichage de l'emprunteur
					} else { // pas sorti
						$situation = "<strong>".$msg['available']."</strong>";
					}
				} else { // pas prêtable
					// exemplaire pas prêtable, on affiche juste "exclu du pret"
					if (($pmb_transferts_actif=="1")&&("".$expl->expl_statut.""==$transferts_statut_transferts))
						$situation = "<strong>".$msg['reservation_lib_entransfert']."</strong>";
					else
						$situation = "<strong>".$msg['exclu']."</strong>";
					// $situation = "<strong>".$expl->statut_libelle.'</strong>';
				}
			} // fin if else $flag_resa 
			$expl_liste .= "<td class='expl_situation'>$situation </td>";
			
			//Champs personalisés
			$perso_aff = "" ;
			if (!$memo_p_perso_expl->no_special_fields) {
				$perso_=$memo_p_perso_expl->show_fields($expl->expl_id);
				for ($i=0; $i<count($perso_["FIELDS"]); $i++) {				
					$p=$perso_["FIELDS"][$i];
					if ($p['OPAC_SHOW'] ) {
						if(!$header_found_p_perso) {
							$header_perso_aff.="<th class='expl_header_tdoc_libelle'>".$p["TITRE_CLEAN"]."</th>";
							$nb_perso_aff++;
						}
						if( $p["AFF"])	{
							$perso_aff.="<td class='p_perso'>".$p["AFF"]."</td>";		
						}	
						else $perso_aff.="<td class='p_perso'>&nbsp;</td>";
					}				
				}
			}
			$header_found_p_perso=1;
			$expl_liste.=$perso_aff;
			
			$expl_liste .="</tr>";	
		$expl_liste_all.=$expl_liste;
		
		if($opac_aff_expl_localises && $_SESSION["empr_location"]) {			
			if($expl->expl_location==$_SESSION["empr_location"]) {
				$expl_liste_loc.=$expl_liste;
			} else $nb_expl_autre_loc++;	
		}	
		$expl_liste="";
		
		} // fin while
		//S'il y a des titres de champs perso dans les exemplaires 
		if($header_perso_aff) {
			$expl_list_header_deb.=$header_perso_aff;
		}	
		
	if($opac_aff_expl_localises && $_SESSION["empr_location"] && $nb_expl_autre_loc) {	
		// affichage avec onglet selon la localisation
		if(!$expl_liste_loc) $expl_liste_loc="<tr class=even><td colspan='".(count($colonnesarray)+1+$nb_perso_aff)."'>".$msg["no_expl"]."</td></tr>";	
		$expl_liste_all=str_replace("!!EXPL!!",$expl_list_header_deb.$expl_liste_all,$expl_list_header_loc_tpl);	
		$expl_liste_all=str_replace("!!EXPL_LOC!!",$expl_list_header_deb.$expl_liste_loc,$expl_liste_all);	
		$expl_liste_all=str_replace("!!mylocation!!",$_SESSION["empr_location_libelle"],$expl_liste_all);
		$expl_liste_all=str_replace("!!id!!",$id+$bull_id,$expl_liste_all);
	} else {
		// affichage de la liste d'exemplaires calculée ci-dessus
		if (!$expl_liste_all && $opac_show_empty_items_block==1) {
			$expl_liste_all = $expl_list_header.$expl_list_header_deb."<tr class=even><td colspan='".(count($colonnesarray)+1)."'>".$msg["no_expl"]."</td></tr>".$expl_list_footer;
		} elseif (!$expl_liste_all && $opac_show_empty_items_block==0) {
			$expl_liste_all = ""; 
		} else {
			$expl_liste_all = $expl_list_header.$expl_list_header_deb.$expl_liste_all.$expl_list_footer;
		}
	}
	return $expl_liste_all;
		
	} // fin function expl_list
	
	// fontion qui génère le bloc H3 + table des autres lectures
	function autres_lectures ($notice_id=0,$bulletin_id=0) {
		global $dbh, $msg;
		global $opac_autres_lectures_tri;
		global $opac_autres_lectures_nb_mini_emprunts;
		global $opac_autres_lectures_nb_maxi;
		global $opac_autres_lectures_nb_jours_maxi;
		global $opac_autres_lectures;
		global $gestion_acces_active,$gestion_acces_empr_notice;
		
		if (!$opac_autres_lectures || (!$notice_id && !$bulletin_id)) return "";
	
		if (!$opac_autres_lectures_nb_maxi) $opac_autres_lectures_nb_maxi = 999999 ;
		if ($opac_autres_lectures_nb_jours_maxi) $restrict_date=" date_add(oal.arc_fin, INTERVAL $opac_autres_lectures_nb_jours_maxi day)>=sysdate() AND ";
		if ($notice_id) $pas_notice = " oal.arc_expl_notice!=$notice_id AND ";
		if ($bulletin_id) $pas_bulletin = " oal.arc_expl_bulletin!=$bulletin_id AND ";
		// Ajout ici de la liste des notices lues par les lecteurs de cette notice
		$rqt_autres_lectures = "SELECT oal.arc_expl_notice, oal.arc_expl_bulletin, count(*) AS total_prets,
					trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if(mention_date, concat(' (',mention_date,')') ,if (date_date, concat(' (',date_format(date_date, '%d/%m/%Y'),')') ,'')))) as tit, if(notices_m.notice_id, notices_m.notice_id, notices_s.notice_id) as not_id 
				FROM ((((pret_archive AS oal JOIN
					(SELECT distinct arc_id_empr FROM pret_archive nbec where (nbec.arc_expl_notice='".$notice_id."' AND nbec.arc_expl_bulletin='".$bulletin_id."') AND nbec.arc_id_empr !=0) as nbec
					ON (oal.arc_id_empr=nbec.arc_id_empr and oal.arc_id_empr!=0 and nbec.arc_id_empr!=0))
					LEFT JOIN notices AS notices_m ON arc_expl_notice = notices_m.notice_id )
					LEFT JOIN bulletins ON arc_expl_bulletin = bulletins.bulletin_id) 
					LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id)
				WHERE $restrict_date $pas_notice $pas_bulletin oal.arc_id_empr !=0
				GROUP BY oal.arc_expl_notice, oal.arc_expl_bulletin
				HAVING total_prets>=$opac_autres_lectures_nb_mini_emprunts 
				ORDER BY $opac_autres_lectures_tri 
				"; 
	
		$res_autres_lectures = mysql_query($rqt_autres_lectures) or die ("<br />".mysql_error()."<br />".$rqt_autres_lectures."<br />");
		if (mysql_num_rows($res_autres_lectures)) {
			$odd_even=1;
			$inotvisible=0;
			$ret="";
	
			//droits d'acces emprunteur/notice
			$acces_j='';
			if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
				$ac= new acces();
				$dom_2= $ac->setDomain(2);
				$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
			}
				
			if($acces_j) {
				$statut_j='';
				$statut_r='';
			} else {
				$statut_j=',notice_statut';
				$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
			}
			
			while (($data=mysql_fetch_array($res_autres_lectures))) { // $inotvisible<=$opac_autres_lectures_nb_maxi
				$requete = "SELECT  1  ";
				$requete .= " FROM notices $acces_j $statut_j  WHERE notice_id='".$data[not_id]."' $statut_r ";
				$myQuery = mysql_query($requete, $dbh);
				if (mysql_num_rows($myQuery) && $inotvisible<=$opac_autres_lectures_nb_maxi) { // mysql_num_rows($myQuery)
					$inotvisible++;
					$titre = $data['tit'];
					// **********
					$responsab = array("responsabilites" => array(),"auteurs" => array());  // les auteurs
					$responsab = get_notice_authors($data['not_id']) ;
					$as = array_search ("0", $responsab["responsabilites"]) ;
					if ($as!== FALSE && $as!== NULL) {
						$auteur_0 = $responsab["auteurs"][$as] ;
						$auteur = new auteur($auteur_0["id"]);
						$mention_resp = $auteur->isbd_entry;
					} else {
						$as = array_keys ($responsab["responsabilites"], "1" ) ;
						for ($i = 0 ; $i < count($as) ; $i++) {
							$indice = $as[$i] ;
							$auteur_1 = $responsab["auteurs"][$indice] ;
							$auteur = new auteur($auteur_1["id"]);
							$aut1_libelle[]= $auteur->isbd_entry;
						}
						$mention_resp = implode (", ",$aut1_libelle) ;
					}
					$mention_resp ? $auteur = $mention_resp : $auteur="";
				
					// on affiche les résultats 
					if ($odd_even==0) {
						$pair_impair="odd";
						$odd_even=1;
					} else if ($odd_even==1) {
						$pair_impair="even";
						$odd_even=0;
					}
					if ($data['arc_expl_notice']) $tr_javascript=" class='$pair_impair' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./index.php?lvl=notice_display&id=".$data['not_id']."&seule=1';\" style='cursor: pointer' ";
						else $tr_javascript=" class='$pair_impair' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./index.php?lvl=bulletin_display&id=".$data['arc_expl_bulletin']."';\" style='cursor: pointer' ";
					$ret .= "<tr $tr_javascript>";
					$ret .= "<td>".$titre."</td>";    
					$ret .= "<td>".$auteur."</td>";    		
					$ret .= "</tr>\n";
				}
			}
			if ($ret) $ret = "<h3 class='autres_lectures'>".$msg['autres_lectures']."</h3><table style='width:100%;'>".$ret."</table>";
		} else $ret="";
		
	return $ret;
	} // fin autres_lectures ($notice_id=0,$bulletin_id=0)
	
	function do_image(&$entree,$depliable) {
		global $opac_show_book_pics ;
		global $opac_book_pics_url ;
		global $opac_url_base ;
				
		if ($this->notice->code || $this->notice->thumbnail_url) {
			if ($opac_show_book_pics=='1' && ($opac_book_pics_url || $this->notice->thumbnail_url)) {
				$code_chiffre = pmb_preg_replace('/-|\.| /', '', $this->notice->code);
				$url_image = $opac_book_pics_url ;
				$url_image = $opac_url_base."getimage.php?url_image=".urlencode($url_image)."&noticecode=!!noticecode!!&vigurl=".urlencode($this->notice->thumbnail_url) ;
				if ($depliable) $image = "<img class='vignetteimg' src='".$opac_url_base."images/vide.png' align='right' hspace='4' vspace='2' isbn='".$code_chiffre."' url_image='".$url_image."' vigurl=\"".$this->notice->thumbnail_url."\" />";
				else {
					if ($this->notice->thumbnail_url) $url_image_ok=$this->notice->thumbnail_url;
					else $url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
					$image = "<img class='vignetteimg' src='".$url_image_ok."' align='right' hspace='4' vspace='2' />";
				}
			} else $image="" ;
			if ($image) {
				$entree = "<table width='100%'><tr><td valign='top'>$entree</td><td valign='top' align='right'>$image</td></tr></table>" ;
			} else {
				$entree = "<table width='100%'><tr><td>$entree</td></tr></table>" ;
			}
				
		} else {
			$entree = "<table width='100%'><tr><td>$entree</td></tr></table>" ;
		}
	} // fin do_image(&$entree,$depliable)
	
	function get_parents_as_childs() {
		global $dbh, $relation_typedown, $relation_listup, $parents_to_childs;
		// pour préparation des cas où les libellés sont identiques en up et en down
		if (!$relation_typedown) $relation_typedown=new marc_list("relationtypedown");
		if (!$relation_listup) $relation_listup=new marc_list("relationtypeup");
		if (!$parents_to_childs) {
			while (list($rel_type,$child_notices)=each($relation_typedown->table)) {
				if ($relation_typedown->table[$rel_type]==$relation_listup->table[$rel_type]) {
					$parents_to_childs[]=$rel_type;
				}
			}
		}
		if (sizeof($parents_to_childs)>0) {
			$clause = "'".implode("','", $parents_to_childs)."'";

			// gestion des droits d'affichage des parents
			if (is_null($this->dom_2)) {
				$acces_j='';
				$statut_j=',notice_statut';
				$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
			} else {
				$acces_j = $this->dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
				$statut_j = "";
				$statut_r = "";	
			}
			
			//Recherche des notices parentes
			$requete="select linked_notice, relation_type, rank from notices_relations join notices on notice_id=linked_notice $acces_j $statut_j 
					where num_notice=".$this->notice_id." and relation_type in (".$clause.") $statut_r
					order by relation_type,rank";
			$resultat=mysql_query($requete,$dbh);
			//S'il y en a
			if (mysql_num_rows($resultat)) {
				// à transférer dans les childs 
				while (($r=mysql_fetch_object($resultat))) $this->childs[$r->relation_type][]=$r->linked_notice;
			}
		}
	} // fin get_parents_as_childs()
	
	function genere_notice_childs() {
		global $msg, $opac_notice_affichage_class ;
		global $memo_notice;
		global $relation_typedown;
		
		$this->antiloop[$this->notice_id]=true;
		//Notices liées
		if ($this->notice_childs) return $this->notice_childs;
		if ((count($this->childs))&&(!$this->to_print)) {
			if ($this->seule) $affichage="";
			else $affichage = "<a href='".str_replace("!!id!!",$this->notice_id,$this->lien_rech_notice)."&seule=1'>".$msg[voir_contenu_detail]."</a>";
			if (!$relation_typedown) $relation_typedown=new marc_list("relationtypedown");
			reset($this->childs);
			$affichage.="<br />";
			while (list($rel_type,$child_notices)=each($this->childs)) {
				$affichage="<b>".$relation_typedown->table[$rel_type]."</b>";
				if ($this->seule) {
				} else $affichage.="<ul>";
				$bool=false;	
				for ($i=0; (($i<count($child_notices))&&(($i<20)||($this->seule))); $i++) {
					if (!$this->antiloop[$child_notices[$i]]) {							
						if(!$this->seule && $memo_notice[$child_notices[$i]]["niveau_biblio"]!='b' && $memo_notice[$child_notices[$i]]["header"]) {
							$affichage.="<li><a href='".str_replace("!!id!!",$child_notices[$i],$this->lien_rech_notice)."'>".$memo_notice[$child_notices[$i]]["header"]."</a></li>";						
							$bool=true;	
						} else if (!$memo_notice[$child_notices[$i]]["niveau_biblio"]) {
							if($this->seule) $header_only=0; else $header_only=1;
							if ($opac_notice_affichage_class) $child_notice=new $opac_notice_affichage_class($child_notices[$i],$this->liens,$this->cart_allowed,$this->to_print,$header_only);
							else $child_notice=new notice_affichage($child_notices[$i],$this->liens,$this->cart_allowed,$this->to_print,$header_only);
							if ($child_notice->notice->niveau_biblio!='b') {
								$child_notice->antiloop=$this->antiloop;
								$child_notice->do_header();
								if ($this->seule) {
									$child_notice->do_isbd();
									$child_notice->do_public();
									if ($this->double_ou_simple == 2 ) $child_notice->genere_double(1, $this->premier) ;
									$child_notice->genere_simple(1, $this->premier) ;
									$affichage .= $child_notice->result ;
								} else {
									$child_notice->visu_expl = 0 ;
									$child_notice->visu_explnum = 0 ;
									$affichage.="<li><a href='".str_replace("!!id!!",$child_notices[$i],$this->lien_rech_notice)."'>".$child_notice->notice_header."</a></li>";
								}
								$bool=true;	
							}							
						}
					}
				}
				if ($bool) $aff_childs.=$affichage;			
				if ($bool && (count($child_notices)>20) && (!$this->seule)) {
					$aff_childs.="<br />";
					if ($this->lien_rech_notice) $aff_childs.="<a href='".str_replace("!!id!!",$this->notice_id,$this->lien_rech_notice)."&seule=1'>";
					$aff_childs.=sprintf($msg["see_all_childs"],20,count($child_notices),count($child_notices)-20);
					if ($this->lien_rech_notice) $aff_childs.="</a>";
				}
				if ($this->seule) {
				} else $aff_childs.="</ul>";
			}
			$this->notice_childs=$aff_childs."<br />";
		} else $this->notice_childs = "" ;
		return $this->notice_childs ;
	}
	
	function get_bulletins(){
		global $dbh;
		$bullarray=array();
		if($this->notice->opac_visible_bulletinage){
			$requete = "SELECT count(bulletin_id) FROM bulletins where bulletin_id in(
				SELECT bulletin_id FROM bulletins WHERE bulletin_notice='".$this->notice_id."' and num_notice=0
				) or bulletin_id in(
				SELECT bulletin_id FROM bulletins,notice_statut, notices WHERE bulletin_notice='".$this->notice_id."'
				and notice_id=num_notice
				and statut=id_notice_statut 
				and((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")) ";
			$res = mysql_query($requete,$dbh);
			if(mysql_num_rows($res)){
				//Renvoie le nombre de bulletins
				return mysql_result($res,0,0);
			}
		}
		return 0;
	}
	
	function get_bulletins_docnums() {
		global $dbh;
		$bull_in_perio = "SELECT bulletin_id FROM bulletins,notice_statut, notices WHERE bulletin_notice='".$this->notice_id."'
				and notice_id=num_notice
				and statut=id_notice_statut 
				and((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":")");
		$requete = "SELECT count(explnum_id) FROM explnum where explnum_bulletin in(
				SELECT bulletin_id FROM bulletins WHERE bulletin_notice='".$this->notice_id."' and num_notice=0
				) or explnum_bulletin in($bull_in_perio)
				or explnum_notice in(SELECT analysis_notice FROM analysis WHERE analysis_bulletin in ($bull_in_perio))";
		$res = mysql_query($requete,$dbh);
		if(mysql_num_rows($res)){
			return mysql_result($res,0,0);
		}
		return 0;
	}
	
	/*
	 * Un pério est ouvert à la recherche si il possède au moins un article ou une notice de bulletin
	 */
	function open_to_search(){
		global $dbh;
		
		$requete = "SELECT * FROM bulletins where bulletin_id in(
			select bulletin_id from bulletins join analysis on analysis_bulletin=bulletin_id where bulletin_notice='".$this->notice_id."' 
			union
			SELECT bulletin_id FROM bulletins,notice_statut, notices WHERE bulletin_notice='".$this->notice_id."'
			and notice_id=num_notice
			and statut=id_notice_statut 
			and((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))";
		$res = mysql_query($requete,$dbh);
		if(mysql_num_rows($res)){
			return mysql_num_rows($res);
		}
		return 0;
	}
	
}
