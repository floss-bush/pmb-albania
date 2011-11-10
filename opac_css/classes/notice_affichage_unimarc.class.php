<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_affichage_unimarc.class.php,v 1.27.2.4 2011-10-07 09:59:08 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$class_path/publisher.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/marc_table.class.php");
require_once($class_path."/parametres_perso.class.php");
require_once($class_path."/category.class.php");
require_once($include_path."/isbn.inc.php");
require_once($include_path."/rss_func.inc.php") ;
require_once($class_path."/resa_planning.class.php") ;
include_once($include_path."/templates/expl_list.tpl.php");
require_once($include_path."/resa_func.inc.php"); 

function cmpexpl($a, $b)
{
	$c1 = isset($a["priority"]) ? $a["priority"] : "";
	$c2 = isset($b["priority"]) ? $b["priority"] : "";
	if ($c1 == $c2) {
		$c1 = isset($a["content"]["v"]) ? $a["content"]["v"] : "";
		$c2 = isset($b["content"]["v"]) ? $b["content"]["v"] : "";
		return strcmp($c1, $c2);		
	}
	return $c2-$c1;
}

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

// definition de la classe d'affichage des notices
class notice_affichage_unimarc {
	var $notice_id		= 0;		// id de la notice a afficher
	var $notice_header	= "" ;		// titre + auteur principaux
						// le terme affichage correspond au code HTML qui peut etre envoye avec un print
	var $notice_isbd	= "" ;		// Affichage ISBD de la notice
	var $notice_public	= "" ;		// Affichage public PMB de la notice
	var $notice_indexations	= "" ;		// Affichage des indexations categories et mots cles, peut etre ajoute a $notice_isbd ou a $notice_public afin d'avoir l'affichage complet PMB
	var $notice_exemplaires	= "" ;		// Affichage des exemplaires, peut etre ajoute a $notice_isbd ou a $notice_public afin d'avoir l'affichage complet PMB
	var $notice_explnum	= "" ;		// Affichage des exemplaires numeriques, peut etre ajoute a $notice_isbd ou a $notice_public afin d'avoir l'affichage complet PMB
	var $notice_notes	= "" ;		// Affichage des notes de contenu et resume, peut etre ajoute a $notice_isbd ou a $notice_public afin d'avoir l'affichage complet PMB
	var $notice;				// objet notice tel que fetche dans la table notices, 
						//		augmente de $this->notice->serie_name si serie il y a
						//		augmente de n_gen, n_contenu, n_resume si on est alle les chercher car non ISBD standard
	var $responsabilites 	= array("responsabilites" => array(),"auteurs" => array());  // les auteurs avec tout ce qu'il faut
	var $categories 	= array();	// les id des categories
	var $auteurs_principaux	= "" ;		// ce qui apparait apres le titre pour le header
  	var $auteurs_tous	= "" ;		// Tous les auteurs avec leur fonction
  	var $categories_toutes	= "" ;		// Toutes les categories dans lesquelles est rangee la notice

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
  	
  	var $action		= '';	// URL � associer au header
	var $header		= '';	// chaine accueillant le chapeau de notice (peut-�tre cliquable)
	var $tit_serie		= '';	// titre de s�rie si applicable
	var $tit1		= '';	// valeur du titre 1
	var $result		= '';	// affichage final
	var $isbd		= '';	// isbd de la notice en fonction du level d�fini
	var $expl		= 0;	// flag indiquant si on affiche les infos d'exemplaire
	var $link_expl		= '';	// lien associ� � un exemplaire
	var $show_resa		= 0;	// flag indiquant si on affiche les infos de resa
	var $p_perso;
	var $cart_allowed = 0;
	var $avis_allowed = 0;
	var $tag_allowed = 0;
	var $to_print = 0;
	var $affichage_resa_expl = "" ; // lien r�servation, exemplaires et exemplaires num�riques, en tableau comme il faut  
	var $affichage_expl = "" ;  // la m�me chose mais sans le lien r�servation

	var $statut = 1 ;  			// Statut (id) de la notice
	var $statut_notice = "" ;  	// Statut (libell�) de la notice
	var $visu_notice = 1 ;  	// Visibilit� de la notice � tout le monde
	var $visu_notice_abon = 0 ; // Visibilit� de la notice aux abonn�s uniquement
	var $visu_expl = 1 ;  		// Visibilit� des exemplaires de la notice � tout le monde
	var $visu_expl_abon = 0 ;  	// Visibilit� des exemplaires de la notice aux abonn�s uniquement
	var $visu_explnum = 1 ;  	// Visibilit� des exemplaires num�riques de la notice � tout le monde
	var $visu_explnum_abon = 0 ;// Visibilit� des exemplaires num�riques de la notice aux abonn�s uniquement
	
	var $childs = array() ; // filles de la notice
	var $notice_childs = "" ; // l'�quivalent � afficher
	var $anti_loop="";
	var $seule = 0 ;
	var $premier = "PUBLIC" ;
	var $double_ou_simple = 2 ;
	var $avis_moyenne ; // Moyenne des  avis
	var $avis_qte; // Quantit� d'un avis 
	
	var $antiloop=array();
	
	var $unimarc=array();
	var $source_id;
	var $source_name;
	var $entrepots_localisations=array();
	
	var $notice_expired = false;
	
// constructeur------------------------------------------------------------
function notice_affichage_unimarc($id, $liens, $cart=0, $to_print=0, $entrepots_localisations=array()) {
  	// $id = id de la notice � afficher
  	// $liens	 = tableau de liens tel que ci-dessous
  	// $cart : afficher ou pas le lien caddie
  	// $to_print = affichage mode impression ou pas

	global $opac_avis_allow;
	global $opac_allow_add_tag;

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
	$this->entrepots_localisations = $entrepots_localisations;
	
	if ($to_print) {
		$this->avis_allowed = 0;
		$this->tag_allowed = 0;
		} else {
			$this->avis_allowed = $opac_avis_allow;
			$this->tag_allowed = $opac_allow_add_tag;
			}
		
	$this->to_print = $to_print;
	
  	// $seule : si 1 la notice est affich�e seule et dans ce cas les notices childs sont en mode d�pliable
  	global $seule ;
  	$this->seule = $seule ;

  	if(!$id)
  		return;
	else {
		$id+=0;
		$this->notice_id = $id;
		$this->fetch_data();
	}
	
	//$this->p_perso=new parametres_perso("notices");
}

// r�cup�ration des valeurs en table---------------------------------------
function fetch_data() {
	global $dbh;

	$requete = "SELECT source_id FROM external_count WHERE rid=".addslashes($this->notice_id);
	$myQuery = mysql_query($requete, $dbh);
	$source_id = mysql_result($myQuery, 0, 0);

	$requete="select * from entrepot_source_$source_id where recid='".addslashes($this->notice_id)."' group by ufield,usubfield,field_order,subfield_order,value";
	$myQuery = mysql_query($requete, $dbh);
	
	$notice="";
	$lpfo="";
	$n_ed=-1;
	
	$exemplaires = array();
	$doc_nums = array();
	
	if(mysql_num_rows($myQuery)) {
		$is_article = false;
		while ($l=mysql_fetch_object($myQuery)) {
			if (!$this->source_id) {
				$this->source_id=$l->source_id;
				$requete="select name from connectors_sources where source_id=".$l->source_id;
				$rsname=mysql_query($requete);
				if (mysql_num_rows($rsname)) $this->source_name=mysql_result($rsname,0,0);
			}
			$this->unimarc[$l->ufield][$l->field_order][$l->usubfield][$l->subfield_order];
			switch ($l->ufield) {
				//dt
				case "dt":
					$notice->typdoc=$l->value;
					break;
				case "bl":
					if($l->value == 'a'){
						$notice->niveau_biblio=$l->value;
					} else $notice->niveau_biblio='m'; //On force le document au type monographie 					
					break;
				case "hl":					
					if($l->value == '2'){
						$notice->niveau_hierar=$l->value;
					} else $notice->niveau_hierar='0'; //On force le niveau � z�ro
					break;
				//ISBN
				case "010":
					if ($l->usubfield=="a") $notice->code=$l->value;
					break;
				//Titres
				case "200":
					switch ($l->usubfield) {
						case "a":
							$notice->tit1.=($notice->tit1?" ":"").$l->value;
							break;
						case "c":
							$notice->tit2.=($notice->tit2?" ":"").$l->value;
							break;
						case "d":
							$notice->tit3.=($notice->tit3?" ":"").$l->value;
							break;
						case "e":
							$notice->tit4.=($notice->tit4?" ":"").$l->value;
							break;
					}
					break;
				//Editeur
				case "210":
					if($l->field_order!=$lpfo) {
						$lpfo=$l->field_order;
						$n_ed++;
					}
					switch ($l->usubfield) {
						case "a":
							$this->publishers[$n_ed]["city"]=$l->value;
							break;
						case "c":
							$this->publishers[$n_ed]["name"]=$l->value;
							break;
						case "d":
							$this->publishers[$n_ed]["year"]=$l->value;
							$this->year=$l->value;
							break;
					}
					break;
				//Collation
				case "215":
					switch ($l->usubfield) {
						case "a":
							$notice->npages=$l->value;
							break;
						case "c":
							$notice->ill=$l->value;
							break;
						case "d":
							$notice->size=$l->value;
							break;
						case "e":
							$notice->accomp=$l->value;
							break;
					}
					break;
				//Note generale
				case "300":
					$notice->n_gen=$l->value;
					break;
				//Note de contenu
				case "327":
					$notice->n_contenu=$l->value;
					break;
				//Note de resume
				case "330":
					$notice->n_resume=$l->value;
					break;
				//Serie ou P�rio
				case "461":		
					switch($l->usubfield){
						case 'x':
							$this->perio_issn = $l->value;
						break;
						case 't':
							$this->parent_title = $l->value;
							$notice->serie_name = $l->value;
						break;
						case '9':
							$is_article = true;
					    break;
					}	
					if($is_article)
						$notice->serie_name = "";	
					else {
						$this->parent_title = "";
						$this->perio_issn = "";
					}				
					break;
				//Bulletins
				case "463" :
					switch($l->usubfield){
						case 't':
							$notice->bulletin_titre = $l->value;
						break;
						case 'v':
							$this->parent_numero = $l->value;
						break;
						case 'd':
							$this->parent_aff_date_date = $l->value;
						break;
						case 'e':
							$this->parent_date = $l->value;
						break;
					}
					break;
				//Mots cles
				case "610":
					switch ($l->usubfield) {
						case "a":
							$notice->index_l.=($notice->index_l?" / ":"").$l->value;
							break;
					}
					break;
				//URL
				case "856":
					switch ($l->usubfield) {
						case "u":
							$notice->lien=$l->value;
							break;
						case "q":
							$notice->eformat=$l->value;
							break;
						case "t":
							$notice->lien_texte=$l->value;
							break;
					}
					break;
				case "996":
					$exemplaires[$l->field_order][$l->usubfield] = $l->value; 
					break;
				//Thumbnail
				case "896":
					switch ($l->usubfield) {
						case "a":
							$notice->thumbnail_url=$l->value;
					}
					break;
				//Documents num�riques
				case "897":
					$doc_nums[$l->field_order][$l->usubfield] = $l->value;
					break;
			}
		}
	}
	
	$this->exemplaires = $exemplaires;
	$this->docnums = $doc_nums;
	
	$this->notice=$notice;
	if (!$this->notice->typdoc) $this->notice->typdoc='a';
	
	// serials : si article
	//if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2) 
	//$this->get_bul_info();	
	
	$this->fetch_categories() ;

	$this->fetch_auteurs() ;
	
	//$this->fetch_visibilite() ;
	$this->fetch_langues(0) ;
	$this->fetch_langues(1) ;
	//$this->fetch_avis();
	
	//$this->childs=array();
	
	return mysql_num_rows($myQuery);
	} // fin fetch_data

function fetch_visibilite() {
	global $dbh;
	global $hide_explnum;
	$requete = "SELECT opac_libelle, notice_visible_opac, expl_visible_opac, notice_visible_opac_abon, expl_visible_opac_abon, explnum_visible_opac, explnum_visible_opac_abon FROM notice_statut WHERE id_notice_statut='".$this->notice->statut."' ";
	$myQuery = mysql_query($requete, $dbh);
	if(mysql_num_rows($myQuery)) {
		$statut_temp = mysql_fetch_object($myQuery);
		$this->statut_notice =        $statut_temp->opac_libelle  ;
		$this->visu_notice =          $statut_temp->notice_visible_opac  ;
		$this->visu_notice_abon =     $statut_temp->notice_visible_opac_abon  ;
		$this->visu_expl =            $statut_temp->expl_visible_opac  ;
		$this->visu_expl_abon =       $statut_temp->expl_visible_opac_abon  ;
		$this->visu_explnum =         $statut_temp->explnum_visible_opac  ;
		$this->visu_explnum_abon =    $statut_temp->explnum_visible_opac_abon  ;

		if ($hide_explnum) {
			$this->visu_explnum=0;
			$this->visu_explnum_abon=0;
		}
	}
	
}

// recuperation des auteurs ---------------------------------------------------------------------
// retourne $this->auteurs_principaux = ce qu'on va afficher en titre du resultat
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
	
	$requete = "SELECT source_id FROM external_count WHERE rid=".addslashes($this->notice_id);
	$myQuery = mysql_query($requete, $dbh);
	$source_id = mysql_result($myQuery, 0, 0);	
	
	$rqt = "select ufield,field_order,usubfield,subfield_order,value from entrepot_source_$source_id where recid='".addslashes($this->notice_id)."' and ufield like '7%' group by ufield,usubfield,field_order,subfield_order,value order by recid,field_order,subfield_order";
	$res_sql=mysql_query($rqt);
	
	$id_aut="";
	$n_aut=-1;
	while ($l=mysql_fetch_object($res_sql)) {
		if ($l->field_order!=$id_aut) {
			if ($n_aut!=-1) {
				$auteurs[$n_aut]["auteur_titre"]=$auteurs[$n_aut]["rejete"].($auteurs[$n_aut]["rejete"]?" ":"").$auteurs[$n_aut]["name"];
				$auteurs[$n_aut]["auteur_isbd"]=$auteurs[$n_aut]["auteur_titre"].($auteurs[$n_aut]["fonction_aff"]?" ,":"").$auteurs[$n_aut]["fonction_aff"];
			}
			$n_aut++;
			switch ($l->ufield) {
				case "700":
				case "710":
					$responsabilites[]=0;
					break;
				case "701":
				case "711":
					$responsabilites[]=1;
					break;
				case "702":
				case "712":
					$responsabilites[]=2;
					break;
			}
			switch (substr($l->ufield,0,2)) {
				case "70":
					$auteurs[$n_aut]["type"]=1;
					break;
				case "71":
					$auteurs[$n_aut]["type"]=2;
					break;
			}
			$auteurs[$n_aut]["id"]=$l->recid.$l->field_order;
			$id_aut=$l->field_order;
		}
		switch ($l->usubfield) {
			case "4":
				$auteurs[$n_aut]["fonction"]=$l->value;
				$auteurs[$n_aut]["fonction_aff"]=$fonction_auteur[$l->value];
				break;
			case "a":
				$auteurs[$n_aut]["name"]=$l->value;
				break;
			case "b":
				$auteurs[$n_aut]["rejete"]=$l->value;
				break;
		}
	}
	if ($n_aut!=-1) {
			$auteurs[$n_aut]["auteur_titre"]=$auteurs[$n_aut]["rejete"].($auteurs[$n_aut]["rejete"]?" ":"").$auteurs[$n_aut]["name"];
			$auteurs[$n_aut]["auteur_isbd"]=$auteurs[$n_aut]["auteur_titre"].($auteurs[$n_aut]["fonction_aff"]?" ,":"").$auteurs[$n_aut]["fonction_aff"];
	}
	
	if (!$responsabilites) $responsabilites = array();
	if (!$auteurs) $auteurs = array();
	$res["responsabilites"] = $responsabilites ;
	$res["auteurs"] = $auteurs ;
	$this->responsabilites = $res;
	
	// $this->auteurs_principaux 
	// on ne prend que le auteur_titre = "Prenom NOM"
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
				$aut1_libelle[]= $auteur_1["auteur_titre"];
				}
			$auteurs_liste = implode ("; ",$aut1_libelle) ;
			if ($auteurs_liste) $this->auteurs_principaux = $auteurs_liste ;
			}
	
	// $this->auteurs_tous
	$mention_resp = array() ;
	$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
	if ($as!== FALSE && $as!== NULL) {
		$auteur_0 = $this->responsabilites["auteurs"][$as] ;
		$mention_resp_lib = $auteur_0["auteur_isbd"];
		$mention_resp[] = $mention_resp_lib ;
		}
	
	$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
	for ($i = 0 ; $i < count($as) ; $i++) {
		$indice = $as[$i] ;
		$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
		$mention_resp_lib = $auteur_1["auteur_isbd"];
		$mention_resp[] = $mention_resp_lib ;
		}
	
	$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
	for ($i = 0 ; $i < count($as) ; $i++) {
		$indice = $as[$i] ;
		$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
		$mention_resp_lib = $auteur_2["auteur_isbd"];
		$mention_resp[] = $mention_resp_lib ;
		}
	
	$libelle_mention_resp = implode ("; ",$mention_resp) ;
	if ($libelle_mention_resp) $this->auteurs_tous = $libelle_mention_resp ;
		else $this->auteurs_tous ="" ;
} // fin fetch_auteurs


// recuperation des categories ------------------------------------------------------------------
function fetch_categories() {
	global $opac_thesaurus, $opac_categories_categ_in_line, $pmb_keyword_sep;

	$requete = "SELECT source_id FROM external_count WHERE rid=".addslashes($this->notice_id);
	$myQuery = mysql_query($requete);
	$source_id = mysql_result($myQuery, 0, 0);	

	$rqt = "select ufield,field_order,usubfield,subfield_order,value from entrepot_source_$source_id where recid='".addslashes($this->notice_id)."' and ufield like '60%' group by ufield,usubfield,field_order,subfield_order,value order by recid,field_order,subfield_order";
	$res_sql=mysql_query($rqt);

	$id_categ="";
	$n_categ=-1;
	$categ_l=array();
	while ($l=mysql_fetch_object($res_sql)) {
		if ($l->field_order!=$id_categ) {
			if ($n_categ!=-1) {
				$categ_libelle=$categ_l["a"].($categ_l["x"]?" - ".implode(" - ",$categ_l["x"]):"").($categ_l["y"]?" - ".implode(" - ",$categ_l["y"]):"").($categ_l["z"]?" - ".implode(" - ",$categ_l["z"]):"");
				$this->categories_toutes.=($this->categories_toutes?"<br />":"").$categ_libelle;
			}
			$categ_l=array();
			$n_categ++;
			$id_categ=$l->field_order;
		}
		$categ_l[$l->usubfield]=$l->value;
	}
	if ($n_categ>=0) {
		$categ_libelle=$categ_l["a"].($categ_l["x"]?" - ".implode(" - ",$categ_l["x"]):"").($categ_l["y"]?" - ".implode(" - ",$categ_l["y"]):"").($categ_l["z"]?" - ".implode(" - ",$categ_l["z"]):"");
		$this->categories_toutes.=($this->categories_toutes?"<br />":"").$categ_libelle;
	}
}

function fetch_langues($quelle_langues=0) {
	global $dbh;

	global $marc_liste_langues ;
	if (!$marc_liste_langues) $marc_liste_langues=new marc_list('lang');

	$requete = "SELECT source_id FROM external_count WHERE rid=".addslashes($this->notice_id);
	$myQuery = mysql_query($requete);
	$source_id = mysql_result($myQuery, 0, 0);	

	$rqt = "select ufield,field_order,usubfield,subfield_order,value from entrepot_source_$source_id where recid='".addslashes($this->notice_id)."' and ufield like '101' group by ufield,usubfield,field_order,subfield_order,value order by recid,field_order,subfield_order";
	$res_sql=mysql_query($rqt);

	$langues = array() ;

	$subfield=array("0"=>"a","1"=>"c");

	while ($l=mysql_fetch_object($res_sql)) {
		if ($l->usubfield==$subfield[$quelle_langues]) {
			if ($marc_liste_langues->table[$l->value]) { 
				$langues[] = array( 
					'lang_code' => $l->value,
					'langue' => $marc_liste_langues->table[$l->value]
				) ;
			}
		}
	}
	
	if (!$quelle_langues) $this->langues = $langues;
		else $this->languesorg = $langues;
}

function fetch_avis()
{
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
}

function affichage_etat_collections() {
	global $msg;
	global $pmb_etat_collections_localise;
	
	//etat des collections
	$affichage="";
	if ($pmb_etat_collections_localise) {
		$restrict_location=" and idlocation=location_id";	
		$table_location=",docs_location";
		$select_location=",location_libelle";
	} else $restrict_location=" group by id_serial";
	$rqt="select state_collections$select_location from collections_state$table_location where id_serial=".$this->notice_id.$restrict_location;
	$execute_query=mysql_query($rqt);
	if ($execute_query) {
		if (mysql_num_rows($execute_query)) {
			$affichage = "<br /><strong>".$msg["perio_etat_coll"]."</strong><br />";
			$bool=false;
			while ($r=mysql_fetch_object($execute_query)) {
				if ($r->state_collections) {
					if ($r->location_libelle) $affichage .= "<strong>".$r->location_libelle."</strong> : ";
					$affichage .= $r->state_collections."<br />\n";	
					$bool=true;
				}
			}
			if ($bool==false) $affichage="";
		}
	}
	return $affichage;
}


function construit_liste_langues($tableau) {
	$langues = "";
	for ($i = 0 ; $i < sizeof($tableau) ; $i++) {
		if ($langues) $langues.=" ";
		$langues .= $tableau[$i]["langue"]." (<i>".$tableau[$i]["lang_code"]."</i>)";
		}
	return $langues;
}

// Fonction d'affichage des avis
function affichage_avis($notice_id) {
	global $dbh;
	global $msg;
	
	$nombre_avis = "";
	
	//Affichage des Etoiles et nombre d'avis
		if ($this->avis_qte > 0) {
			$nombre_avis = "<a href='#' title=\"".$msg['notice_title_avis']."\" onclick=\"open('avis.php?todo=liste&noticeid=$notice_id','avis','width=600,height=290,scrollbars=yes,resizable=yes'); return false;\">".$this->avis_qte."&nbsp;".$msg['notice_bt_avis']."</a>";
			$etoiles_moyenne = $this->stars($this->avis_moyenne);		
		} else {
			$nombre_avis = "<a href='#' title=\"".$msg['notice_title_avis']."\" onclick=\"open('avis.php?todo=liste&noticeid=$notice_id','avis','width=600,height=290,scrollbars=yes,resizable=yes'); return false;\">".$msg['avis_aucun']."</a>";
			$cpt_star = -1;
		}
		
		// Affichage du nombre d'avis ainsi que la note moyenne et les etoiles associees
		$img_tag .= $nombre_avis."<a href='#' title=\"".$msg['notice_title_avis']."\" onclick=\"open('avis.php?todo=liste&noticeid=$notice_id','avis','width=600,height=290,scrollbars=yes,resizable=yes'); return false;\">".$etoiles_moyenne."</a>";	
		
		return $img_tag;
}

// Gestion des etoiles pour les avis
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
}

// generation du de l'affichage double avec onglets ---------------------------------------------
//	si $depliable=1 alors inclusion du parent / child
function genere_double($depliable=1, $premier='ISBD') {
	global $msg;
	global $css;
	global $cart_aff_case_traitement;
	global $opac_url_base ;
	global $dbh;
	global $icon_doc, $tdoc, $biblio_doc;
	global $allow_tag ; // l'utilisateur a-t-il le droit d'ajouter un tag
	
	$this->premier = $premier ;
	$this->double_ou_simple = 2 ;
	$this->notice_childs = $this->genere_notice_childs();
	if ($this->cart_allowed) 
		$basket="<a href=\"cart_info.php?id=es".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\"><img src=\"images/basket_small_20x20.gif\" border=\"0\" title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
	else 
		$basket="";
	
	//add tags
	//if ( ($this->tag_allowed==1) || ( ($this->tag_allowed==2)&&($_SESSION["user_code"])&&($allow_tag) ) )
	//	$img_tag.="<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='".$opac_url_base."images/tag.png' align='absmiddle' border='0' title=\"".$msg['notice_title_tag']."\" alt=\"".$msg['notice_title_tag']."\" ></a>";	
	
	 //Avis
	 //if ($this->avis_allowed) {
	//	$img_tag .= $this->affichage_avis($this->notice_id);
	 //}
			
	// preparation de la case a cocher pour traitement panier
	if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;

	$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
	
	if ($depliable) {
		$template="
			<div id=\"el!!id!!Parent\" class=\"notice-parent\">
			$case_a_cocher";		
		if(!$this->notice_expired)
			$template.="
    			<img class='img_plus' src=\"".$opac_url_base."images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['expandable_notice']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\" />";
		if ($icon) $template.="
					<img src=\"".$opac_url_base."images/$icon\" alt='".$biblio_doc[$this->notice->niveau_biblio]." : ".$tdoc->table[$this->notice->typdoc]."' title='".$biblio_doc[$this->notice->niveau_biblio]." : ".$tdoc->table[$this->notice->typdoc]."'/>";
		$template.="		
			<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>
    		<br />
			</div>
			<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-left:-6px;margin-bottom:6px;display:none;\">";
		} else {
			$template="<div class='parent'>$case_a_cocher";
			if ($icon) $template.="<img src=\"".$opac_url_base."images/$icon\" />";
			$template.="<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>";
			}
 	$template.="!!CONTENU!!
				!!SUITE!!</div>";

	//$template_in=$basket;
	$template_in.="<ul id='onglets_isbd_public'>";
    if ($premier=='ISBD') $template_in.="
    	<li id='baskets!!id!!' class='onglet_basket'>$basket</li>
    	<li id='onglet_isbd!!id!!' class='isbd_public_active'><a href='#' onclick=\"show_what('ISBD', '!!id!!'); return false;\">".$msg['ISBD']."</a></li>
    	<li id='onglet_public!!id!!' class='isbd_public_inactive'><a href='#' onclick=\"show_what('PUBLIC', '!!id!!'); return false;\">".$msg['Public']."</a></li>
    	<li id='tags!!id!!' class='onglet_tags'>$img_tag</li>
		</ul>
		<div id='div_isbd!!id!!' style='display:block;'>!!ISBD!!</div>
  		<div id='div_public!!id!!' style='display:none;'>!!PUBLIC!!</div>";
  		else $template_in.="
	    	<li id='baskets!!id!!' class='onglet_basket'>$basket</li>
  			<li id='onglet_public!!id!!' class='isbd_public_active'><a href='#' onclick=\"show_what('PUBLIC', '!!id!!'); return false;\">".$msg['Public']."</a></li>
			<li id='onglet_isbd!!id!!' class='isbd_public_inactive'><a href='#' onclick=\"show_what('ISBD', '!!id!!'); return false;\">".$msg['ISBD']."</a></li>
	    	<li id='tags!!id!!' class='onglet_tags'>$img_tag</li>
			</ul>
			<div id='div_public!!id!!' style='display:block;'>!!PUBLIC!!</div>
  			<div id='div_isbd!!id!!' style='display:none;'>!!ISBD!!</div>";
	
	// Serials : diff�rence avec les monographies on affiche [p�riodique] et [article] devant l'ISBD
	if ($this->notice->niveau_biblio =='s') {
		$lien_bull = (count($this->get_bulletins()) ? "&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>" : "");
		$template_in = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>$lien_bull&nbsp;!!ISBD!!", $template_in);
		$template_in = str_replace('!!PUBLIC!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>$lien_bull&nbsp;!!PUBLIC!!", $template_in);
	} elseif ($this->notice->niveau_biblio =='a') { 
		$template_in = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!ISBD!!", $template_in);
		$template_in = str_replace('!!PUBLIC!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!PUBLIC!!", $template_in);
	}
	
	
	$template_in = str_replace('!!ISBD!!', $this->notice_isbd, $template_in);
	$template_in = str_replace('!!PUBLIC!!', $this->notice_public, $template_in);
	$template_in = str_replace('!!id!!', "es". $this->notice_id, $template_in);
	$this->do_image($template_in,$depliable);

	$this->result = str_replace('!!id!!', "es". $this->notice_id, $template);
	$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
	$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
	if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result); 		
	$this->result = str_replace('!!SUITE!!', "", $this->result);
	}

// generation de l'affichage simple sans onglet ----------------------------------------------
//	si $depliable=1 alors inclusion du parent / child
function genere_simple($depliable=1, $what='ISBD') {
	global $msg; 
	global $opac_cart_allow;
	global $css;
	global $cart_aff_case_traitement;
	global $opac_url_base ;
	global $dbh;
	global $icon_doc, $tdoc, $biblio_doc;
	global $allow_tag ; // l'utilisateur a-t-il le droit d'ajouter un tag
	$cpt_star = 4;
	
	$this->double_ou_simple = 1 ;
	$this->notice_childs = $this->genere_notice_childs();
	// preparation de la case a cocher pour traitement panier
	if ($cart_aff_case_traitement) 
		$case_a_cocher = "<input type='checkbox' value='' name='notice[]'/>&nbsp;";
	else 
		$case_a_cocher = "" ;
	
	if ($this->cart_allowed) 
		$basket="<a href=\"cart_info.php?id=es".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\"><img src='images/basket_small_20x20.gif' align='absmiddle' border='0' title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
	else 
		$basket="";
	
	//add tags
	if (($this->tag_allowed==1)||(($this->tag_allowed==2)&&($_SESSION["user_code"])&&($allow_tag)))
		$img_tag.="&nbsp;&nbsp;<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='".$opac_url_base."images/tag.png' align='absmiddle' border='0' title=\"".$msg['notice_title_tag']."\" alt=\"".$msg['notice_title_tag']."\"></a>&nbsp;&nbsp;";
	
	 //Avis
	 if ($this->avis_allowed) {
		$img_tag .= $this->affichage_avis($this->notice_id);
	 }

	if ($basket) $basket="<div>".$basket.$img_tag."</div>";

	$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
	
	if ($depliable) { 
		$template="
		<div id=\"el!!id!!Parent\" class=\"notice-parent\">
			$case_a_cocher
    		<img class='img_plus' src=\"".$opac_url_base."images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg["expandable_notice"]."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\">";
		if ($icon) $template.="
				<img src=\"".$opac_url_base."images/$icon\" alt='".$biblio_doc[$this->notice->niveau_biblio]." : ".$tdoc->table[$this->notice->typdoc]."' title='".$biblio_doc[$this->notice->niveau_biblio]." : ".$tdoc->table[$this->notice->typdoc]."'/>";
		$template.="
    		<span class=\"notice-heada\">!!heada!!</span><br />
    		</div>			
		<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">".$basket."!!ISBD!!\n
			!!SUITE!!
			</div>";
	}
		else {
			$template="
			\n<div id=\"el!!id!!Parent\" class=\"parent\">
    				$case_a_cocher";
			if ($icon) $template.="
				<img src=\"".$opac_url_base."images/$icon\" />";
			$template.="
    				<span class=\"heada\">!!heada!!</span><br />
	    			</div>			
			\n<div id='el!!id!!Child' class='child' >".$basket."
			!!ISBD!!
			!!SUITE!!
			</div>";
	}
		
	
	// Serials : difference avec les monographies on affiche [periodique] et [article] devant l'ISBD
	if ($this->notice->niveau_biblio =='s') {
		$lien_bull = (count($this->get_bulletins())  ? "&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>" : "");
		$template = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>$lien_bull&nbsp;!!ISBD!!", $template);
	} elseif ($this->notice->niveau_biblio =='a') { 
		$template = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!ISBD!!", $template);
		}
	
	$this->result = str_replace('!!id!!', "es". $this->notice_id, $template);
	$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
	
	if ($what=='ISBD') {
		$this->do_image($this->notice_isbd,$depliable);
		$this->result = str_replace('!!ISBD!!', $this->notice_isbd, $this->result);
	} else {
		$this->do_image($this->notice_public,$depliable);
		$this->result = str_replace('!!ISBD!!', $this->notice_public, $this->result);
	} 
	if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result);
		else $this->result = str_replace('!!SUITE!!', '', $this->result);
			
	}

// generation de l'isbd----------------------------------------------------
function do_isbd($short=0,$ex=1) {
	global $dbh;
	global $msg;
	global $tdoc;
	global $charset;
	global $opac_notice_affichage_class;
	
	$this->notice_isbd="";
	
	if($this->notice_expired ){		
		return $this->notice_isbd;
	}
	//In
	//Recherche des notices parentes
	$requete="select linked_notice, relation_type, rank from notices_relations where num_notice=".$this->notice_id." order by relation_type,rank";
	$result_linked=mysql_query($requete);
	//Si il y en a, on prepare l'affichage
	if (mysql_num_rows($result_linked)) {
		global $relation_listup ;
		if (!$relation_listup) $relation_listup=new marc_list("relationtypeup");
	}
	$r_type=array();
	$ul_opened=false;
	//Pour toutes les notices liees
	while ($r_rel=mysql_fetch_object($result_linked)) {
		if ($opac_notice_affichage_class) $notice_affichage=$opac_notice_affichage_class; else $notice_affichage="notice_affichage";
		$parent_notice=new $notice_affichage($r_rel->linked_notice,$this->liens,$this->cart,$this->to_print);
		$parent_notice->visu_expl = 0 ;
		$parent_notice->visu_explnum = 0 ;
		$parent_notice->do_header();
		//Presentation differente si il y en a un ou plusieurs
		if (mysql_num_rows($result_linked)==1) {
			$this->notice_isbd.="<br /><b>".$relation_listup->table[$r_rel->relation_type]."</b> ";
			if ($this->lien_rech_notice) $this->notice_isbd.="<a href='".str_replace("!!id!!","es".$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>";
			$this->notice_isbd.=$parent_notice->notice_header;
			if ($this->lien_rech_notice) $this->notice_isbd.="</a>";
			$this->notice_isbd="<br /><br />";
		} else {
			if (!$r_type[$r_rel->relation_type]) {
				$r_type[$r_rel->relation_type]=1;
				if ($ul_opened) $this->notice_isbd.="</ul>"; else { $this->notice_isbd.="<br />"; $ul_opened=true; }
				$this->notice_isbd.="<b>".$relation_listup->table[$r_rel->relation_type]."</b>";
				$this->notice_isbd.="<ul class='notice_rel'>\n";
			}
			$this->notice_isbd.="<li>";
			if ($this->lien_rech_notice) $this->notice_isbd.="<a href='".str_replace("!!id!!","es".$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>";
			$this->notice_isbd.=$parent_notice->notice_header;
			if ($this->lien_rech_notice) $this->notice_isbd.="</a>";
			$this->notice_isbd.="</li>\n";
		}
		if (mysql_num_rows($result_linked)>1) $this->notice_isbd.="</ul>\n";
	}
	
	// constitution de la mention de titre
	if($this->notice->serie_name) {
		$serie_temp .= inslink($this->notice->serie_name,  str_replace("!!id!!","es". $this->notice->tparent_id, $this->lien_rech_serie));
		if($this->notice->tnvol)
			$serie_temp .= ',&nbsp;'.$this->notice->tnvol;
		}
	if ($serie_temp) $this->notice_isbd .= $serie_temp.".&nbsp;".$this->notice->tit1 ;
		else $this->notice_isbd .= $this->notice->tit1;

	$this->notice_isbd .= ' ['.$tdoc->table[$this->notice->typdoc].']';
	if ($this->notice->tit3) $this->notice_isbd .= "&nbsp;= ".$this->notice->tit3 ;
	if ($this->notice->tit4) $this->notice_isbd .= "&nbsp;: ".$this->notice->tit4 ;
	if ($this->notice->tit2) $this->notice_isbd .= "&nbsp;; ".$this->notice->tit2 ;
	
	if ($this->auteurs_tous) $this->notice_isbd .= " / ".$this->auteurs_tous;
	
	// mention d'edition
	if($this->notice->mention_edition) $this->notice_isbd .= " &nbsp;. -&nbsp; ".$this->notice->mention_edition;
	
	// zone de collection et editeur
	if($this->notice->subcoll_id) {
		$collection = new subcollection($this->notice->subcoll_id);
		$editeurs .= inslink($collection->publisher_libelle, str_replace("!!id!!","es". $collection->publisher, $this->lien_rech_editeur));
		$collections = inslink($collection->isbd_entry,  str_replace("!!id!!","es". $this->notice->subcoll_id, $this->lien_rech_subcollection));
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$editeurs .= inslink($collection->publisher_libelle, str_replace("!!id!!","es". $collection->parent, $this->lien_rech_editeur));
			$collections = inslink($collection->isbd_entry,  str_replace("!!id!!","es". $this->notice->coll_id, $this->lien_rech_collection));
			} elseif ($this->notice->ed1_id) {
				$editeur = new publisher($this->notice->ed1_id);
				$editeurs .= inslink($editeur->isbd_entry,  str_replace("!!id!!","es". $this->notice->ed1_id, $this->lien_rech_editeur));
				}
	
	if (is_array($this->publishers)) {
		for ($i=0; $i<count($this->publishers) ;$i++) {
			$editeur[$i]=$this->publishers[$i]["name"].($this->publishers[$i]["city"]?" (".$this->publishers[$i]["city"].")":"");
		}
		$editeurs=implode("&nbsp;: ",$editeur);
	}
	
	if($this->notice->ed2_id) {
		$editeur = new publisher($this->notice->ed2_id);
		$editeurs ? $editeurs .= '&nbsp;: '.inslink($editeur->isbd_entry,  str_replace("!!id!!","es". $this->notice->ed2_id, $this->lien_rech_editeur)) : $editeurs = inslink($editeur->isbd_entry,  str_replace("!!id!!","es". $this->notice->ed2_id, $this->lien_rech_editeur));
		}

	if($this->notice->year) 
		$editeurs ? $editeurs .= ', '.$this->notice->year : $editeurs = $this->notice->year;
		else if ($this->notice->niveau_biblio == 'm' && $this->notice->niveau_hierar == 0) 
			$editeurs ? $editeurs .= ', [s.d.]' : $editeurs = "[s.d.]";

	if($editeurs)
		$this->notice_isbd .= "&nbsp;.&nbsp;-&nbsp;$editeurs";
	
	// zone de la collation
	if($this->notice->npages)
		$collation = $this->notice->npages;
	if($this->notice->ill)
		$collation .= '&nbsp;: '.$this->notice->ill;
	if($this->notice->size)
		$collation .= '&nbsp;; '.$this->notice->size;
	if($this->notice->accomp)
		$collation .= '&nbsp;+ '.$this->notice->accomp;
		
	if($collation)
		$this->notice_isbd .= "&nbsp;.&nbsp;-&nbsp;$collation";
	
	if($collections) {
		if($this->notice->nocoll)
			$collections .= '; '.$this->notice->nocoll;
		$this->notice_isbd .= ".&nbsp;-&nbsp;($collections)".' ';
		}

	$this->notice_isbd .= '.';
		
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
	
	// note generale
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

	//Notices liees
	// ajoutees en dehors de l'onglet PUBLIC ailleurs
	
	$this->notice_isbd .= $this->expl_list();
	
	//if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
}	

// generation de l'affichage public----------------------------------------
function do_public($short=0,$ex=1) {
	global $dbh;
	global $msg;
	global $tdoc;
	global $charset;
	
	$this->notice_public="";
	if($this->notice_expired){
		return $this->notice_public;
	}

	$this->fetch_categories() ;
	//In
	//Recherche des notices parentes
	$requete="select linked_notice, relation_type, rank from notices_relations where num_notice=".$this->notice_id." order by relation_type,rank";
	$result_linked=mysql_query($requete);
	//Si il y en a, on prepare l'affichage
	if (mysql_num_rows($result_linked)) {
		global $relation_listup ;
		if (!$relation_listup) $relation_listup=new marc_list("relationtypeup");
	}
	$r_type=array();
	$ul_opened=false;
	//Pour toutes les notices liees
	while ($r_rel=mysql_fetch_object($result_linked)) {
		$parent_notice=new notice_affichage($r_rel->linked_notice,$this->liens,1,$this->to_print);
		$parent_notice->visu_expl = 0 ;
		$parent_notice->visu_explnum = 0 ;
		$parent_notice->do_header();
		//Presentation differente si il y en a un ou plusieurs
		if (mysql_num_rows($result_linked)==1) {
			$this->notice_public.="<br /><b>".$relation_listup->table[$r_rel->relation_type]."</b> <a href='".str_replace("!!id!!","es".$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>".$parent_notice->notice_header."</a><br /><br />";
		} else {
			if (!$r_type[$r_rel->relation_type]) {
				$r_type[$r_rel->relation_type]=1;
				if ($ul_opened) $this->notice_public.="</ul>"; else { $this->notice_public.="<br />"; $ul_opened=true; }
				$this->notice_public.="<b>".$relation_listup->table[$r_rel->relation_type]."</b>";
				$this->notice_public.="<ul class='notice_rel'>\n";
			}
			$this->notice_public.="<li><a href='".str_replace("!!id!!","es".$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>".$parent_notice->notice_header."</a></li>\n";
		}
		if (mysql_num_rows($result_linked)>1) $this->notice_public.="</ul>\n";
	}

	$this->notice_public .= "<table>";
	// constitution de la mention de titre
	if ($this->notice->serie_name) {
		$this->notice_public.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['tparent_start']."</span></td><td>".inslink($this->notice->serie_name,  str_replace("!!id!!","es". $this->notice->tparent_id, $this->lien_rech_serie));;
		if ($this->notice->tnvol)
			$this->notice_public .= ',&nbsp;'.$this->notice->tnvol;
		$this->notice_public .="</td></tr>";
		}
	
	$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['title']." :</span></td>";
	$this->notice_public .= "<td>".$this->notice->tit1 ;
	
	if ($this->notice->tit4) $this->notice_public .= ": ".$this->notice->tit4 ;
	$this->notice_public.="</td></tr>";
	
	if ($this->notice->tit2) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t2']." :</span></td><td>".$this->notice->tit2."</td></tr>" ;
	if ($this->notice->tit3) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t3']." :</span></td><td>".$this->notice->tit3."</td></tr>" ;
	
	if ($tdoc->table[$this->notice->typdoc]) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['typdocdisplay_start']."</span></td><td>".$tdoc->table[$this->notice->typdoc]."</td></tr>";
	
	if ($this->auteurs_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['auteur_start']."</span></td><td>".$this->auteurs_tous."</td></tr>";
	
	// mention d'edition
	if ($this->notice->mention_edition) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['mention_edition_start']."</span></td><td>".$this->notice->mention_edition."</td></tr>";
	
	// zone de l'editeur 
	if ($this->year)
		$annee = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['year_start']."</span></td><td>".$this->year."</td></tr>" ;

	if ($this->notice->ed1_id) {
		$editeur = new publisher($this->notice->ed1_id);
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['editeur_start']."</span></td><td>".inslink($editeur->display,  str_replace("!!id!!","es". $this->notice->ed1_id, $this->lien_rech_editeur)) ;
		if ($annee) {
			$this->notice_public .= $annee ;
			$annee = "" ;
			}  
		}
		
	if (is_array($this->publishers)) {
		for ($i=0; $i<count($this->publishers) ;$i++) {
			$this->notice_public.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['editeur_start']."</span></td><td>";
			$this->notice_public.= htmlentities($this->publishers[$i]["name"].($this->publishers[$i]["city"]?" (".$this->publishers[$i]["city"].")":""),ENT_QUOTES,$charset);
			$this->notice_public."</td></tr>";
		}
	}
	if ($annee) {
		$this->notice_public .= $annee ;
		$annee = "" ;
	}  
	//$this->notice_public."</td></tr>";
	// collection  
	if($this->notice->subcoll_id) {
		$subcollection = new subcollection($this->notice->subcoll_id);
		$collection = new collection($this->notice->coll_id);
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td>".inslink($collection->name,  str_replace("!!id!!","es". $this->notice->coll_id, $this->lien_rech_collection))."</td></tr>" ;
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['subcoll_start']."</span></td><td>".inslink($subcollection->name,  str_replace("!!id!!","es". $this->notice->subcoll_id, $this->lien_rech_subcollection)) ;
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td>".inslink($collection->isbd_entry,  str_replace("!!id!!","es". $this->notice->coll_id, $this->lien_rech_collection)) ;
			}
	if ($this->notice->nocoll) $this->notice_public .= " ".str_replace("!!nocoll!!", $this->notice->nocoll, $msg['subcollection_details_nocoll']) ;
	$this->notice_public.="</td></tr>";
	
	// ajout $annee si pas vide. Est vide si deja ajoute plus haut
	$this->notice_public .= $annee ;
	
	// zone de la collation
	if($this->notice->npages)
		if ($this->notice->niveau_biblio<>"a") $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start']."</span></td><td>".$this->notice->npages."</td></tr>";
			else $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start_perio']."</span></td><td>".$this->notice->npages."</td></tr>";

	if ($this->notice->ill)
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['ill_start']."</span></td><td>".$this->notice->ill."</td></tr>";
	if ($this->notice->size)
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['size_start']."</span></td><td>".$this->notice->size."</td></tr>";
	if ($this->notice->accomp)
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['accomp_start']."</span></td><td>".$this->notice->accomp."</td></tr>";
		
	// ISBN ou NO. commercial
	if ($this->notice->code)
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['code_start']."</span></td><td>".$this->notice->code."</td></tr>";

	if ($this->notice->prix) 
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['price_start']."</span></td><td>".$this->notice->prix."</td></tr>";

	// note generale
	if ($this->notice->n_gen) $zoneNote = nl2br(htmlentities($this->notice->n_gen,ENT_QUOTES, $charset));
	if ($zoneNote) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_gen_start']."</span></td><td>".$zoneNote."</td></tr>";

	// langues
	if (count($this->langues)) {
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['537']." :</span></td><td>".$this->construit_liste_langues($this->langues);
		if (count($this->languesorg)) $this->notice_public .= " <span class='etiq_champ'>".$msg['711']." :</span> ".$this->construit_liste_langues($this->languesorg);
		$this->notice_public.="</td></tr>";
		} else 
			if (count($this->languesorg)) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['711']." :</span></td><td>".$this->construit_liste_langues($this->languesorg)."</td></tr>"; 

	//Documents num�riques
	$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['entrepot_notice_docnum']."</span></td><td>";
	if ($this->docnums) {
		$this->notice_public .= "<ul>";
		foreach($this->docnums as $docnum) {
			if (!$docnum["a"])
				continue;
			$this->notice_public .= "<li>";
			if ($docnum["b"])
				$this->notice_public .= $docnum["b"].": ";
			$this->notice_public .= "<i><a href=\"".htmlentities($docnum["a"],ENT_QUOTES,$charset)."\">".$docnum["a"]."</a></i>";			
			$this->notice_public .= "</li>";
		}		
		$this->notice_public .= "</ul>";
	}
	$this->notice_public .= "</td></tr>";
			
	if (!$short) $this->notice_public .= $this->aff_suite() ; else $this->notice_public.=$this->genere_in_perio();
	$this->notice_public.="</table>\n";
	
	//etat des collections
	if ($this->notice->niveau_biblio=='s'&&$this->notice->niveau_hierar==1) $this->notice_public.=$this->affichage_etat_collections();	
	
	//Notices liees
	// ajoutees en dehors de l'onglet PUBLIC ailleurs

	$this->notice_public .= $this->expl_list();
	//if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;

	return;
	}	

// generation du header----------------------------------------------------
function do_header() {
	global $dbh;
	global $charset;
	global $opac_notice_reduit_format ;
	global $opac_url_base ;
	
	$type_reduit = substr($opac_notice_reduit_format,0,1);
	if ($type_reduit=="E" || $type_reduit=="P" ) {
		// peut-etre veut-on des personnalises ?
		$perso_voulus_temp = substr($opac_notice_reduit_format,2) ;
		if ($perso_voulus_temp!="")
			$perso_voulus = explode(",",$perso_voulus_temp);
		}
	
	if ($type_reduit=="E") {
		// zone de l'editeur 
		if (is_array($this->publishers[0])) {
			$editeur_reduit = $this->publishers[0]["name"].($this->publishers[0]["city"]?" (".$this->publishers[0]["city"].")":"") ;
			if ($this->publishers[0]["year"]) {
				$editeur_reduit .= " - ".$this->publishers[0]["city"]." ";
				}  
			} elseif ($this->notice->year) { // annee mais pas d'editeur
				$editeur_reduit = $this->notice->year." ";
				}
		} else $editeur_reduit = "" ;
	
	//Si c'est un periodique, ajout du titre et bulletin
	if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2)  {
		 $aff_perio_title="<i>in ".$this->parent_title;
		 if($this->parent_numero && ($this->parent_date || $this->parent_aff_date_date)){
		 	$aff_perio_title .= " (".$this->parent_numero.", ".($this->parent_date?$this->parent_date:"[".$this->parent_aff_date_date."]").")</i>";
		 } elseif(!$this->parent_numero && ($this->parent_date || $this->parent_aff_date_date)){
		 	$aff_perio_title .= " (".($this->parent_date?$this->parent_date:"[".$this->parent_aff_date_date."]").")</i>";
		 } elseif($this->parent_numero && !($this->parent_date || $this->parent_aff_date_date)){
		 	$aff_perio_title .= " (".$this->parent_numero.")</i>";
		 }
	}
	//Source
	if ($this->source_name) {
		$this->notice_header=$this->source_name." : ";
	}
	// recuperation du titre de serie
		// constitution de la mention de titre
	if($this->notice->serie_name) {
		$this->notice_header .= $this->notice->serie_name;
		if($this->notice->tnvol)
			$this->notice_header .= ', '.$this->notice->tnvol;
		}
	if ($this->notice->serie_name) $this->notice_header .= ". ".$this->notice->tit1 ;
		else $this->notice_header.= $this->notice->tit1;
	if ($type_reduit=="T" && $this->notice->tit4) $this->notice_header = $this->notice_header." : ".$this->notice->tit4;
	if ($this->auteurs_principaux) $this->notice_header .= " / ".$this->auteurs_principaux;
	if ($editeur_reduit) $this->notice_header .= " / ".$editeur_reduit ;
	if ($aff_perio_title) $this->notice_header .= " ".$aff_perio_title;
}

  
// Construction des mots cle----------------------------------------------------
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
	$mots_cles = implode("&nbsp; ", $tableau_mots);
	return $mots_cles ; 
	}

// recuperation des info de bulletinage (si applicable)
function get_bul_info() {
	global $dbh;
	global $msg;
	// recuperation des donnees du bulletin et de la notice apparentee
	$requete = "SELECT b.tit1,b.notice_id,a.*,c.*, date_format(date_date, '".$msg["format_date"]."') as aff_date_date "; 
	$requete .= "from analysis a, notices b, bulletins c";
	$requete .= " WHERE a.analysis_notice=".$this->notice_id;
	$requete .= " AND c.bulletin_id=a.analysis_bulletin";
	$requete .= " AND c.bulletin_notice=b.notice_id";
	$requete .= " LIMIT 1";
	$myQuery = mysql_query($requete, $dbh);
	if (mysql_num_rows($myQuery)) {
		$parent = mysql_fetch_object($myQuery);
		$this->parent_title = $parent->tit1;
		$this->parent_id = $parent->notice_id;
		$this->bul_id = $parent->bulletin_id;
		$this->parent_numero = $parent->bulletin_numero;
		$this->parent_date = $parent->mention_date;
		$this->parent_date_date = $parent->date_date;
		$this->parent_aff_date_date = $parent->aff_date_date;
		}
	}

// fonction de generation de ,la mention in titre du perio + numero
function genere_in_perio () {
	global $charset ;
	// serials : si article
	if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2) {	
		$bulletin = $this->parent_title;
		$notice_mere = inslink($this->parent_title, str_replace("!!id!!","es". $this->parent_id, $this->lien_rech_perio));
		if($this->parent_numero) 
			$numero = $this->parent_numero." " ;
		// affichage de la mention de date utile : mention_date si existe, sinon date_date
		if ($this->parent_date)
			$date_affichee = " (".$this->parent_date.")";
			elseif ($this->parent_date_date)
				$date_affichee .= " [".formatdate($this->parent_date_date)."]";
				else $date_affichee="" ;
		$bulletin = inslink($numero.$date_affichee, str_replace("!!id!!","es". $this->bul_id, $this->lien_rech_bulletin));
		$mention_parent = "<b>in</b> $notice_mere > $bulletin ";
		$retour .= "<br />$mention_parent";
		$pagination = htmlentities($this->notice->npages,ENT_QUOTES, $charset);
		if ($pagination) $retour .= ".&nbsp;-&nbsp;$pagination";
		}
	return $retour ;
	}

// fonction d'affichage des exemplaires, resa et expl_num
function aff_resa_expl() {
	global $opac_resa ;
	global $opac_max_resa ;
	global $opac_show_exemplaires ;
	global $msg;
	global $dbh;
	global $popup_resa ;
	global $opac_resa_popup ; // la resa se fait-elle par popup ?
	global $opac_resa_planning; // la resa est elle planifiee
	global $allow_book;
	
	// afin d'eviter de recalculer un truc deja calcule...
	if ($this->affichage_resa_expl) return $this->affichage_resa_expl ;
	
	if ($opac_show_exemplaires && $this->visu_expl && (!$this->visu_expl_abon || ($this->visu_expl_abon && $_SESSION["user_code"]))) {

		if (!$opac_resa_planning) {
			$resa_check=check_statut($this->notice_id,0) ;
			// verification si exemplaire reservable
			if ($resa_check) {
				// deplace dans le IF, si pas visible : pas de bouton resa 
				$requete_resa = "SELECT count(1) FROM resa WHERE resa_idnotice='$this->notice_id'";
				$nb_resa_encours = mysql_result(mysql_query($requete_resa,$dbh), 0, 0) ;
				if ($nb_resa_encours) $message_nbresa = str_replace("!!nbresa!!", $nb_resa_encours, $msg["resa_nb_deja_resa"]) ;
				if (($this->notice->niveau_biblio=="m") && ($_SESSION["user_code"] && $allow_book) && $opac_resa && !$popup_resa) {
					$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
					if ($opac_max_resa==0 || $opac_max_resa>$nb_resa_encours) {
						if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"w=window.open('./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
							else $ret .= "<a href='./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup' id='bt_resa'>".$msg["bulletin_display_place_resa"]."</a>" ;
						$ret .= $message_nbresa ;
						} else $ret .= str_replace("!!nb_max_resa!!", $opac_max_resa, $msg["resa_nb_max_resa"]) ; 
					$ret.= "<br />";
					} elseif ( ($this->notice->niveau_biblio=="m") && !($_SESSION["user_code"]) && $opac_resa && !$popup_resa) {
						// utilisateur pas connecte
						// preparation lien reservation sans etre connecte
						$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
						if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"w=window.open('./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
							else $ret .= "<a href='./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup' id='bt_resa'>".$msg["bulletin_display_place_resa"]."</a>" ;
						$ret .= $message_nbresa ;
						$ret .= "<br />";
						}
				} // fin if resa_check
			$temp = $this->expl_list($this->notice->niveau_biblio,$this->notice->notice_id);
			$ret .= $temp ;
			$this->affichage_expl = $temp ; 
		
		} else {
			// planning de reservations
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
				// utilisateur pas connecte
				// preparation lien reservation sans etre connecte
				$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
				if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"w=window.open('./do_resa.php?lvl=resa_planning&id_notice=".$this->notice_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;\" id='bt_resa'>".$msg["bulletin_display_place_resa"]."</a>" ;
					else $ret .= "<a href='./do_resa.php?lvl=resa_planning&id_notice=".$this->notice_id."&oresa=popup' id='bt_resa'>".$msg["bulletin_display_place_resa"]."</a>" ;
				$ret .= $message_nbresa ;
				$ret .= "<br />";
			}
	
			$temp = $this->expl_list($this->notice->niveau_biblio,$this->notice->notice_id);
			$ret .= $temp ;
			$this->affichage_expl = $temp ; 
		}
	}
	
	if ($this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) 	
		if ($explnum = show_explnum_per_notice($this->notice_id, 0, '')) {
			$ret .= "<h3>$msg[explnum]</h3>".$explnum;
			$this->affichage_expl .= "<h3>$msg[explnum]</h3>".$explnum;
		}
	if ($autres_lectures = $this->autres_lectures($this->notice_id,$this->bulletin_id)) {
		$ret .= $autres_lectures;
	}
	$this->affichage_resa_expl = $ret ;
	return $ret ;
} 


// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour eviter la redondance de calcul
function aff_suite() {
	global $msg;
	global $charset;
	global $mode;
	global $opac_allow_tags_search;
	
	// afin d'eviter de recalculer un truc deja calcule...
	if ($this->affichage_suite) return $this->affichage_suite ;
	
	// serials : si article
	$ret .= $this->genere_in_perio () ;
	
	//Espace
	$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
	
	// resume
	if($this->notice->n_resume)
 		$ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_resume,ENT_QUOTES, $charset))."</td></tr>";

	// note de contenu
	if($this->notice->n_contenu) 
 		$ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";

	// Categories
	if($this->categories_toutes) 
		$ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['categories_start']."</span></td><td>".$this->categories_toutes."</td></tr>";
			
			
	// Affectation du libelle mots cles ou tags en fonction de la recherche precedente	
	
	if($opac_allow_tags_search == 1)
		$libelle_key = $msg['tags'];
	else
		$libelle_key = 	$msg['motscle_start'];
			
	// indexation libre
	$mots_cles = $this->do_mots_cle() ;
	if($mots_cles)
		$ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$libelle_key."</span></td><td>".$mots_cles."</td></tr>";
		
	// indexation interne
	if($this->notice->indexint) {
		$indexint = new indexint($this->notice->indexint);
		$ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['indexint_start']."</span></td><td>".inslink($indexint->name,  str_replace("!!id!!","es". $this->notice->indexint, $this->lien_rech_indexint))." ".nl2br(htmlentities($indexint->comment,ENT_QUOTES, $charset))."</td></tr>" ;
		}
	
	//Champs personalises
	//$perso_aff = "" ;
	//if (!$this->p_perso->no_special_fields) {
	//	$perso_=$this->p_perso->show_fields($this->notice_id);
	//	for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
	//		$p=$perso_["FIELDS"][$i];
	//		if ($p['OPAC_SHOW'] && $p["AFF"]) $perso_aff .="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$p["TITRE"]."</span></td><td>".$p["AFF"]."</td></tr>";
	//		}
	//	}
	//if ($perso_aff) {
		//Espace
	//	$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
	//	$ret .= $perso_aff ;
	//	}
	
	if ($this->notice->lien) {
		$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
		$ret.="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["lien_start"]."</span></td><td>" ;
		if (substr($this->notice->eformat,0,3)=='RSS') {
			$ret .= affiche_rss($this->notice->notice_id) ;
			} else {
				$ret.="<a href=\"".$this->notice->lien."\" target=\"top\">".htmlentities($this->notice->lien_texte?$this->notice->lien_texte:$this->notice->lien,ENT_QUOTES,$charset)."</a></td></tr>";
				}
		$ret.="</td></tr>";
		if ($this->notice->eformat && substr($this->notice->eformat,0,3)!='RSS') $ret.="<tr><td align='right' class='bg-grey'><b>".$msg["eformat_start"]."</b></td><td>".htmlentities($this->notice->eformat,ENT_QUOTES,$charset)."</td></tr>";
		}
	
	$this->affichage_suite = $ret ;
	return $ret ;
	} 


// fonction de generation du tableau des exemplaires
function expl_list() {	
	global $dbh;
	global $msg, $charset;
	global $expl_list_header, $expl_list_footer;
	$expl_liste = "sdfsdfsdf";
	
	if (!$this->exemplaires)
		return;
	
	$expl_output = $expl_list_header;
	$count = 1;
	
	$expl996 = array(
		"f" => $msg["extexpl_codebar"],
		"k" => $msg["extexpl_cote"],
		"v" => $msg["extexpl_location"],
		"x" => $msg["extexpl_section"],
		"1" => $msg["extexpl_statut"],
		"a" => $msg["extexpl_emprunteur"],
		"e" => $msg["extexpl_doctype"],
		"u" => $msg["extexpl_note"]
	);
	
	$final_location = array();
	foreach ($this->exemplaires as $expl) {
		$alocation = array();
		//Si on trouve une localisation, on la convertie en libelle et on l'oublie si sp�cifi�
		if (isset($expl["v"]) && preg_match("/\d{9}/", $expl["v"]) && $this->entrepots_localisations) {
			if (isset($this->entrepots_localisations[$expl["v"]])) {
				if (!$this->entrepots_localisations[$expl["v"]]["visible"]) {
					continue;
				}
				$alocation["priority"] = $this->entrepots_localisations[$expl["v"]]["visible"];

				$expl["v"] = $this->entrepots_localisations[$expl["v"]]["libelle"];
			}
		}
		if (!isset($alocation["priority"]))
			$alocation["priority"] = 1;
		$alocation["content"] = $expl;			
		$final_location[] = $alocation;
	}

	if (!$final_location)
		return;
		
	//trions
	usort($final_location, "cmpexpl");		
	
	$expl_output .= "<tr>";
	foreach ($expl996 as $caption996) {
		$expl_output .= "<th>".$caption996."</th>";
	}
	$expl_output .= "</tr>";
	
	foreach ($final_location as $expl) {
		$axepl_output = "<tr>";
		foreach ($expl996 as $key996 => $caption996) {
			if (isset($expl["content"][$key996])) {
				$axepl_output .= "<td>".$expl["content"][$key996]."</td>";				
			}
			else {
				$axepl_output .= "<td></td>";				
			}
		}
		$axepl_output .= "</tr>";
		$expl_output .= $axepl_output;
		$count++;
	}
	$expl_output .= $expl_list_footer;
	
	return $expl_output;
	
	global $expl_list_header, $expl_list_footer;
	// les depouillements n'ont pas d'exemplaire
	if ($type=="a") return "" ;
	
	// les exemplaires des monographies
	if ($type=="m") {
		$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, docs_type.*";
		$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl, docs_location, docs_section, docs_statut, docs_type";
		$requete .= " WHERE expl_notice='$id' and expl_bulletin='$bull_id'";
		$requete .= " AND location_visible_opac=1 AND section_visible_opac=1";
		$requete .= " AND exemplaires.expl_location=docs_location.idlocation";
		$requete .= " AND exemplaires.expl_section=docs_section.idsection ";
		$requete .= " AND exemplaires.expl_statut=docs_statut.idstatut ";
		$requete .= " AND exemplaires.expl_typdoc=docs_type. idtyp_doc ";
		// recuperation du nombre d'exemplaires
		$res = mysql_query($requete, $dbh);
		$expl_liste="";
		$requete_resa = "SELECT count(1) from resa where resa_idnotice='$id' ";
		$nb_resa = mysql_result(mysql_query($requete_resa, $dbh),0,0);
		while($expl = mysql_fetch_object($res)) {
			$compteur = $compteur+1;
			$expl_liste .= "<tr><td>";
			$expl_liste .= $expl->expl_cb."&nbsp;";
			$expl_liste .= "</td><td><strong>";
			$expl_liste .= $expl->expl_cote."&nbsp;";
			$expl_liste .= "</strong></td><td>";
			$expl_liste .= $expl->tdoc_libelle."&nbsp;";
			$expl_liste .= "</td><td>";
			$expl_liste .= $expl->location_libelle."&nbsp;";
			$expl_liste .= "</td><td>";
			$expl_liste .= $expl->section_libelle."&nbsp;";
			
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
						} else { // pas pretable
							// exemplaire pas pretable, on affiche juste "exclu du pret"
							$situation = "<strong>".$msg['exclu']."</strong>";
							// $situation = "<strong>".$expl->statut_libelle.'</strong>';
							}
					} // fin if else $flag_resa 
			$expl_liste .= "</td><td>$situation </td>";
			$expl_liste .="</tr>";	
			} // fin while
		
		// affichage de la liste d'exemplaires calculee ci-dessus
		if (!$expl_liste) $expl_liste = $expl_list_header."<tr class=even><td colspan=6>".$msg["no_expl"]."</td></tr>".$expl_list_footer;
			else $expl_liste = $expl_list_header.$expl_liste.$expl_list_footer;
		return $expl_liste;
		}
	
	// le resume des articles, bulletins et exemplaires des notices meres
	if ($type=="s") {
		return "";
		}
	} // fin function expl_list

// fontion qui genere le bloc H3 + table des autres lectures
function autres_lectures ($notice_id=0,$bulletin_id=0) {
	global $dbh, $msg;
	global $opac_autres_lectures_tri;
	global $opac_autres_lectures_nb_mini_emprunts;
	global $opac_autres_lectures_nb_maxi;
	global $opac_autres_lectures_nb_jours_maxi;
	global $opac_autres_lectures;
	
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
		while ($data=mysql_fetch_array($res_autres_lectures)) { // $inotvisible<=$opac_autres_lectures_nb_maxi
			$requete = "SELECT  1  ";
			$requete .= " FROM notices, notice_statut  WHERE notice_id='".$data[not_id]."' and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
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
			
				// on affiche les resultats 
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
	}

function do_image(&$entree,$depliable) {
	global $opac_show_book_pics ;
	global $opac_book_pics_url ;
	global $opac_url_base ;
	if ($this->notice->code<>"") {
		if ($opac_show_book_pics=='1' && $opac_book_pics_url) {
			$code_chiffre = pmb_preg_replace('/-|\.| /', '', $this->notice->code);
			$url_image = $opac_book_pics_url ;
			$url_image = $opac_url_base."getimage.php?url_image=".urlencode($url_image)."&noticecode=!!noticecode!!" ;
			if ($depliable) $image = "<img class='vignetteimg' src='".$opac_url_base."images/vide.png' align='right' hspace='4' vspace='2' isbn='".$code_chiffre."' url_image='".$url_image."'>";
				else {
					$url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
					$image = "<img class='vignetteimg' src='".$url_image_ok."' align='right' hspace='4' vspace='2'>";
					}
			} else $image="" ;
		if ($image) {
			$entree = "<table width='100%'><tr><td>$entree</td><td valign=top align=right>$image</td></tr></table>" ;
			} else {
				$entree = "<table width='100%'><tr><td>$entree</td></tr></table>" ;
				}
			
		} else {
			$entree = "<table width='100%'><tr><td>$entree</td></tr></table>" ;
			}
	}

function genere_notice_childs(){
	global $msg, $opac_notice_affichage_class ;

	$this->antiloop[$this->notice_id]=true;
	//Notices liees
	if ($this->notice_childs) return $this->notice_childs;
	if ((count($this->childs))&&(!$this->to_print)) {
		if ($this->seule) $affichage="";
			else $affichage = "<a href='".str_replace("!!id!!","es".$this->notice_id,$this->lien_rech_notice)."&seule=1'>".$msg[voir_contenu_detail]."</a>";
		global $relation_typedown;
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
					if ($opac_notice_affichage_class) $child_notice=new $opac_notice_affichage_class($child_notices[$i],$this->liens,$this->cart_allowed,$this->to_print);
						else $child_notice=new notice_affichage($child_notices[$i],$this->liens,$this->cart_allowed,$this->to_print);
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
							$affichage.="<li><a href='".str_replace("!!id!!","es".$child_notices[$i],$this->lien_rech_notice)."'>".$child_notice->notice_header."</a></li>";
						}
						$bool=true;
					}
				}
			}
			if ($bool==true) $aff_childs.=$affichage;
			if ((count($child_notices)>20)&&(!$this->seule)) {
				$aff_childs.="<br />";
				if ($this->lien_rech_notice) $aff_childs.="<a href='".str_replace("!!id!!","es".$this->notice_id,$this->lien_rech_notice)."&seule=1'>";
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
		
		if($this->notice->opac_visible_bulletinage){
			$requete = "SELECT * FROM bulletins where bulletin_id in(
				SELECT bulletin_id FROM bulletins WHERE bulletin_notice='".$this->notice_id."' and num_notice=0
				) or bulletin_id in(
				SELECT bulletin_id FROM bulletins,notice_statut, notices WHERE bulletin_notice='".$this->notice_id."'
				and notice_id=num_notice
				and statut=id_notice_statut 
				and((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")) ";
			$res = mysql_query($requete,$dbh);print $requete; exit();
			if(mysql_num_rows($res)){
				return mysql_fetch_array($res);
			}
		} else return 0;
	}
}
