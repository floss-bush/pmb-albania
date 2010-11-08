<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice.class.php,v 1.30 2010-07-02 08:15:15 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// OPAC. Classe de gestion des notices
if ( ! defined('NOTICE_CLASS') )
{
define('NOTICE_CLASS', 1);

// constantes pour connaître les champs valides
define('N_VALID_PRIMAL',       1); //
define('N_VALID_AUTHORS',      2); //
define('N_VALID_TYPDOC',       4); //
define('N_VALID_PARENT_TITLE', 8);
define('N_VALID_PUBLISHERS',   16); //
define('N_VALID_COLLECTION',   32); //
define('N_VALID_LANG',         64); //

class notice {

	var $id            = 0;   // id de la notice
	var $typdoc        = '';  // type du document
	var $typdocdisplay = '';  // type du document
	var $tit1          = '';  // titre propre
	var $tit2          = '';  // titre propre 2
	var $tit3          = '';  // titre parallèle
	var $tit4          = '';  // complément du titre
	var $tparent_id    = 0;   // id du titre parent
	var $tparent       = '';  // libellé du titre parent
	var $tnvol         = '';  // numéro de partie
	var $responsabilites =	array("responsabilites" => array(),"auteurs" => array());  // les auteurs
	var $ed1_id        = 0;   // id éditeur 1
	var $ed1           = '';  // libellé éditeur 1
	var $coll_id       = 0;   // id collection
	var $coll          = '';  // libellé collection
	var $subcoll_id    = 0;   // id sous collection
	var $subcoll       = '';  // libellé sous collection
	var $ed2_id        = 0;   // id éditeur 2
	var $ed2           = '';  // libellé éditeur 2
	var $code          = '';  // ISBN, code barre commercial ou no. commercial
	var $npages        = '';  // importance matérielle (nombre de pages, d'éléments...)
	var $ill           = '';  // mention d'illustration
	var $size          = '';  // format
	var $prix = '';			// prix du document
	var $year          = '';  // année de publication
	var $nocoll        = '';  // no. dans la collection
	var $accomp        = '';  // matériel d'accompagnement
	var $n_gen         = '';  // note générale
	var $n_contenu     = '';  // note de contenu
	var $n_resume      = '';  // resumé/extrait
	var $categories =array(); // les categories
	var $indexint = 0;		// indexation interne
	var $index_l       = '';  // indexation libre
	var $lien          = '';  // URL de la ressource électronique associée
	var $eformat       = '';  // format de la ressource électronique associée
	var $index_sew    = '';  // pseudo index titre strippé
	var $index_wew    = '';  // pseudo index titre 
	var $index_serie   = '';  // pseudo index serie
	var $statut 		= ''; //statut de la notice
	var $niveau_biblio = 'm'; //niveau biblio utilisé pour les périodiques : 'm' monographie 'a' article
	var $niveau_hierar = '0'; //niveau hiérarchique utilisé pour les périodiques

	var $validfields   = 0;   // champs valides



// constructeur
function notice($id) {
	global $fonction_auteur;
	global $base_path;
	
	require_once($base_path."/classes/author.class.php");
	require_once($base_path."/classes/marc_table.class.php");
	require_once($base_path."/includes/notice_authors.inc.php");

	// récupération des codes de fonction
	if (!count($fonction_auteur)) {
		$fonction_auteur = new marc_list('function');
		$fonction_auteur = $fonction_auteur->table;
		}

	// on regarde si on a une notice-objet ou un id de notice
	if (is_object($id)) {
		$this->get_primaldatafrom($id);
		} else {
			$this->id = $id;
			$this->get_primaldata();
			}
	// mise à jour des catégories
	$this->categories = get_notice_categories($this->notice_id) ;

	}
		
function fetch_visibilite() {
	global $dbh;
	global $hide_explnum;
	$requete = "SELECT opac_libelle, notice_visible_opac, expl_visible_opac, notice_visible_opac_abon, expl_visible_opac_abon, explnum_visible_opac, explnum_visible_opac_abon FROM notice_statut WHERE id_notice_statut='".$this->statut."' ";
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

function get_primaldata() {
	global $dbh;
	global $fonction_auteur;
	
	// on récupère les infos de la notice
	$query = "select * from notices where notice_id=".$this->id." limit 1";
	$result = mysql_query($query, $dbh);
	if(mysql_num_rows($result)) {
		$obj = mysql_fetch_object($result);
		mysql_free_result($result);
		$this->get_primaldatafrom($obj);
		}
	}

function get_primaldatafrom($obj) {
	global $fonction_auteur;
	
	// prend les données à partir d'un objet retourné par une requête sur la table notices
	$this->id = $obj->notice_id;
	$this->typdoc = $obj->typdoc;
	$this->tit1 = $obj->tit1;
	$this->tit2 = $obj->tit2;
	$this->tit3 = $obj->tit3;
	$this->tit4 = $obj->tit4;
	$this->tparent_id = $obj->tparent_id;
	$this->tparent = $obj->tparent;
	$this->tnvol = $obj->tnvol;

	$this->responsabilites = get_notice_authors($this->id) ;

	$this->ed1_id = $obj->ed1_id;
	$this->ed2_id = $obj->ed2_id;

	$this->coll_id = $obj->coll_id;
	$this->subcoll_id = $obj->subcoll_id;

	$this->year = $obj->year;
	$this->nocoll = $obj->nocoll;
	$this->code = $obj->code;
	$this->npages = $obj->npages;
	$this->ill = $obj->ill;
	$this->size = $obj->size;
	$this->prix = $obj->prix;
	$this->accomp = $obj->accomp;
	$this->n_gen = $obj->n_gen;
	$this->n_contenu = $obj->n_contenu;
	$this->n_resume = $obj->n_resume;

	$this->index_ = $obj->index_l;
	$this->lien = $obj->lien;
	$this->eformat = $obj->eformat;
	$this->index_sew = $obj->index_sew;
	$this->index_wew = $obj->index_wew;
	$this->index_serie = $obj->index_serie;

	$this->niveau_biblio = $obj->niveau_biblio;
	$this->niveau_hierar = $obj->niveau_hierar;
	$this->prix	= $obj->prix;	
	$this->statut= $obj->statut;
	
	$this->validfields = N_VALID_PRIMAL;
	}



function check_fields($fields) 	{
	global $opac_show_book_pics ;
	global $fonction_auteur;
	global $base_path ;
	// informations sur les noms et fonctions des auteurs
	if (($fields & N_VALID_AUTHORS) && ! ($this->validfields & N_VALID_AUTHORS)) {
		$this->responsabilites = get_notice_authors($this->id) ;
		$this->validfields = $this->validfields | N_VALID_AUTHORS;
		}

	// informations type de document
	if (($fields & N_VALID_TYPDOC) && ! ($this->validfields & N_VALID_TYPDOC)) {
		if($this->typdoc) {
			require_once($base_path."/classes/marc_table.class.php");
			$doctype = new marc_list('doctype');
			$this->typdocdisplay = $doctype->table[$this->typdoc];
			}
		$this->validfields = $this->validfields | N_VALID_TYPDOC;
		}

	// informations langue du document et langue de l'original
	if (($fields & N_VALID_LANG) && ! ($this->validfields & N_VALID_LANG)) {
		if($this->lang_code || $this->org_lang_code) {
			require_once($base_path."/classes/marc_table.class.php");
			$lang = new marc_list('lang');
			if($this->lang_code) { // libellé langue de la publication
				$this->lang = $lang->table[$this->lang_code];
				}
			if($this->org_lang_code) {	// libellé de la langue originale
				$this->org_lang = $lang->table[$this->org_lang_code];
				}
			}
		$this->validfields = $this->validfields | N_VALID_LANG;
		}

	// informations collection et sous-colection
	if (($fields & N_VALID_COLLECTION) && ! ($this->validfields & N_VALID_COLLECTION)) {
		if ($this->coll_id) {
			require_once($base_path."/classes/collection.class.php");
			require_once($base_path."/classes/publisher.class.php");
			$coll = new collection($this->coll_id);
			$this->coll = $coll->name;
			}
		if ($this->subcoll_id) {
			require_once($base_path."/classes/collection.class.php");
			require_once($base_path."/classes/subcollection.class.php");
			$subcoll = new subcollection($this->subcoll_id);
			$this->subcoll = $subcoll->name;
			}
		$this->validfields = $this->validfields | N_VALID_COLLECTION;
		}

	// informations éditeurs
	if (($fields & N_VALID_PUBLISHERS) && ! ($this->validfields & N_VALID_PUBLISHERS)) {
		require_once($base_path."/classes/publisher.class.php");

		if ($this->ed1_id) {
			$publisher = new publisher($this->ed1_id);
			$this->ed1 = $publisher->display;
			}
		if ($this->ed2_id) {
			$publisher = new publisher($this->ed2_id);
			$this->ed2 = $publisher->display;
			}
		$this->validfields = $this->validfields | N_VALID_PUBLISHERS;
		}

	// libellé du titre parent
	if (($fields & N_VALID_PARENT_TITLE) && ! ($this->validfields & N_VALID_PARENT_TITLE)) {
		
		if($this->tparent_id) {
			require_once($base_path."/classes/serie.class.php");
			$serie = new serie($this->tparent_id);
			$this->tparent = $serie->name;
			}
		$this->validfields = $this->validfields | N_VALID_PARENT_TITLE;
		}
	}

function print_resume($level = 2,$css)	{
	global $fonction_auteur;
	global $base_path ;
	// récupération localisation
	require_once($base_path."/includes/localisation.inc.php");
	global $msg;
	global $css;
	global $opac_show_book_pics ;
 	global $charset;
	if(!$this->id)
		return;

	// adaptation par rapport au niveau de détail souhaité
	// niveau par défaut : 2
	// niveau 3 : format public
	switch ($level) {
		case 1 :
			global $notice_level1_display;
			global $notice_level1_no_coll_info;
			global $notice_level1_no_author_info;
			global $notice_level1_no_authors_info;
			global $notice_level1_no_publisher_info;

			$notice_display = $notice_level1_display;
			$notice_no_coll_information = $notice_level1_no_coll_info;
			$notice_no_author_information = $notice_level1_no_author_info;
			$notice_no_authors_information = $notice_level1_no_authors_info;
			$notice_no_publisher_information = $notice_level1_no_publisher_info;

			// utilise les bons champs IMPORTANT
			$this->check_fields(N_VALID_TYPDOC | N_VALID_AUTHORS | N_VALID_PARENT_TITLE);
			break;

		case 3 :
			// Format Public
			global $notice_level3_display;
			global $notice_level3_no_coll_info;
			global $notice_level3_no_author_info;
			global $notice_level3_no_authors_info;
			global $notice_level3_no_publisher_info;

			$notice_display = $notice_level3_display;
			$notice_no_coll_information = $notice_level3_no_coll_info;
			$notice_no_author_information = $notice_level3_no_author_info;
			$notice_no_authors_information = $notice_level3_no_authors_info;
			$notice_no_publisher_information = $notice_level3_no_publisher_info;
			// utilise les bons champs IMPORTANT
			$this->check_fields(N_VALID_TYPDOC | N_VALID_AUTHORS | N_VALID_PUBLISHERS | N_VALID_COLLECTION | N_VALID_PARENT_TITLE);
			break;
		case 2 :
		default :
			global $notice_level2_display;
			global $notice_level2_no_coll_info;
			global $notice_level2_no_author_info;
			global $notice_level2_no_authors_info;
			global $notice_level2_no_publisher_info;

			$notice_display = $notice_level2_display;
			$notice_no_coll_information = $notice_level2_no_coll_info;
			$notice_no_author_information = $notice_level2_no_author_info;
			$notice_no_authors_information = $notice_level2_no_authors_info;
			$notice_no_publisher_information = $notice_level2_no_publisher_info;

			// utilise les bons champs IMPORTANT
			$this->check_fields(N_VALID_TYPDOC | N_VALID_AUTHORS | N_VALID_PUBLISHERS | N_VALID_COLLECTION);
			break;
		}

	$print = $notice_display;

	// remplacement des champs statiques
	// $print = str_replace("!!notice_id!!", $this->id, $print);
	if ($this->id<>"") {
			$print = str_replace("!!notice_id!!", "<font size=1>$msg[notice_id_start] ".$this->id." $msg[notice_id_end]</font>"."<br />", $print);
			$print = str_replace("!!notice_id_only!!", $this->id, $print);
			} else {
				$print = str_replace("!!notice_id!!", "", $print);
				$print = str_replace("!!notice_id_only!!","", $print);
				}


	if ($this->niveau_hierar<>"2") $print = str_replace("!!niveau_hierar!!", "<b>".$msg["hierarchical_level"]."</b> ".$this->niveau_hierar."<br />", $print);
		else $print = str_replace("!!niveau_hierar!!", "", $print);

	if ($this->typdoc<>"") {
		if ($this->niveau_hierar<>"2") {
			// ce n'est pas un article
			$print = str_replace("!!typdoc!!", $this->typdoc." ".$msg["article"], $print);
			$print = str_replace("!!typdocpic!!", "<img src=./images/icon_".$this->typdoc.".gif> ".$this->niveau_hierar, $print);						
			} else {
				// c'est un article
				$print = str_replace("!!typdoc!!", $this->typdoc, $print);
				$print = str_replace("!!typdocpic!!", "<img src=./images/icon_".$this->typdoc.".gif>".$this->niveau_hierar, $print);						
				}	
		} else {
			$print = str_replace("!!typdoc!!", "", $print);
			$print = str_replace("!!typdocpic!!", "", $print);
			}

	if ($this->tit1<>"") {
		switch($this->niveau_biblio) {
			case "s":
				$tit1_ico = "<a href='#' onClick='window.open(\"./includes/messages/fr_FR/icons.html\", \"icones__PMB\", \"scrollbars=yes, toolbar=no, dependent=yes, width=400, height=400, resizable=yes\"); return false' title='".$msg["serial"]."'><img src='./images/icon_per.gif' border='0' alt='".$msg["serial"]."' align='absmiddle'/>
				<img src='./images/icon_".$this->typdoc.".gif' border='0' align='absmiddle'/></a>";
				break;
			case "a":
				$tit1_ico = "<a href='#' onClick='window.open(\"./includes/messages/fr_FR/icons.html\", \"icones__PMB\", \"scrollbars=yes, toolbar=no, dependent=yes, width=400, height=400, resizable=yes\"); return false' title='".$msg["article"]."'><img src='./images/icon_art.gif' border='0' alt='".$msg["article"]."' align='absmiddle'/>
				<img src='./images/icon_".$this->typdoc.".gif' border='0' align='absmiddle'/></a>";
				break;
			case "m":
			default :
				$tit1_ico ="<a href='#' onClick='window.open(\"./includes/messages/fr_FR/icons.html\", \"icones__PMB\", \"scrollbars=yes, toolbar=no, dependent=yes, width=400, height=400, resizable=yes\"); return false' title='".$this->typdocdisplay."'><img src='./images/icon_".$this->typdoc.".gif' border='0' align='absmiddle'/></a>";
				break;
			}
		$print = str_replace("!!tit1!!", $this->tit1, $print);
		$print = str_replace("!!tit1_ico!!", $tit1_ico, $print);
		$print = str_replace("!!tit1display!!", "<b>$msg[tit1display_start]</b>".$this->tit1."$msg[tit1display_end]<br />", $print);
		} else {
			$print = str_replace("!!tit1!!","", $print);
			}
			
	if ($this->tit2<>"") $print = str_replace("!!tit2!!", "<b>$msg[tit2_start]</b>".$this->tit2."<br />", $print);
		else $print = str_replace("!!tit2!!", "", $print);

	if ($this->tit3<>"") $print = str_replace("!!tit3!!", "<b>$msg[tit3_start]</b>".$this->tit3."<br />", $print);
		else $print = str_replace("!!tit3!!", "", $print);

	if ($this->tit4<>"") $print = str_replace("!!tit4!!", "<b>$msg[tit4_start]</b>".$this->tit4."<br />", $print);
		else $print = str_replace("!!tit4!!","", $print);

	if ($this->typdocdisplay<>"") $print = str_replace("!!typdocdisplay!!", "<b>$msg[typdocdisplay_start]</b> ".$this->typdocdisplay."<br />", $print);
		else $print = str_replace("!!typdocdisplay!!", $this->typdoc, $print);

	if ($this->tparent<>"") {
		if ($this->tnvol) $tparent_libelle = "<b>$msg[tparent_start]</b>".$this->tparent." [".$this->tnvol."]<br />" ;
			else $tparent_libelle = "<b>$msg[tparent_start]</b>".$this->tparent."<br />" ;
		$print = str_replace("!!tparent!!", $tparent_libelle, $print);
		} else $print = str_replace("!!tparent!!", "", $print);

	if ($this->tnvol<>"") $print = str_replace("!!tnvol!!", "<b>$msg[tnvol_start]</b>".$this->tnvol."<br />", $print);
		else $print = str_replace("!!tnvol!!", "", $print);

	// constitution de la mention de responsabilité
	//$this->responsabilites
	$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
	if ($as!== FALSE && $as!== NULL) {
		$auteur_0 = $this->responsabilites["auteurs"][$as] ;
		$auteur = new auteur($auteur_0["id"]);
		if ($auteur_0["fonction"]) $f_auteur =" (".$fonction_auteur[$auteur_0["fonction"]].")";
			else $f_auteur="";
		$mention_resp_lib = "<b>".$msg[auteur_start]."</b> "."<a href='index.php?lvl=author_see&id=".$auteur_0["id"]."' title='".$auteur->info_bulle."'>$auteur->isbd_entry</a> ".$f_auteur."<br />";
		$mention_resp[] = $mention_resp_lib ;
		}
	
	$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
	for ($i = 0 ; $i < count($as) ; $i++) {
		$indice = $as[$i] ;
		$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
		$auteur = new auteur($auteur_1["id"]);
		if ($auteur_1["fonction"]) $f_auteur =" (".$fonction_auteur[$auteur_1["fonction"]].")";
			else $f_auteur="";
		$mention_resp_lib = "<b>".$msg[auteur_start]."</b> "."<a href='index.php?lvl=author_see&id=".$auteur_1["id"]."' title='".$auteur->info_bulle."'>$auteur->isbd_entry</a> ".$f_auteur."<br />";
		$mention_resp[] = $mention_resp_lib ;
		}
	
	$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
	for ($i = 0 ; $i < count($as) ; $i++) {
		$indice = $as[$i] ;
		$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
		$auteur = new auteur($auteur_2["id"]);
		if ($auteur_2["fonction"]) $f_auteur =" (".$fonction_auteur[$auteur_2["fonction"]].")";
			else $f_auteur="";
		$mention_resp_lib = "<b>".$msg[auteur_start]."</b> "."<a href='index.php?lvl=author_see&id=".$auteur_2["id"]."' title='".$auteur->info_bulle."'>$auteur->isbd_entry</a> ".$f_auteur."<br />";
		$mention_resp[] = $mention_resp_lib ;
		}
	if (!$mention_resp) $mention_resp=array();	
	$libelle_mention_resp = implode (" ",$mention_resp) ;
	
	// **********************************************
	if ($this->ed1<>"") $print = str_replace("!!ed1!!", "<b>$msg[ed1_start]</b>".$this->ed1."<br />", $print);
		else $print = str_replace("!!ed1!!", "", $print);
			
	if ($this->ed2<>"") $print = str_replace("!!ed2!!", "<b>$msg[ed2_start]</b>".$this->ed2."<br />", $print);
		else $print = str_replace("!!ed2!!", "", $print);

	if ($this->coll<>"") $print = str_replace("!!coll!!", "<b>$msg[coll_start]</b>".$this->coll."<br />", $print);
		else $print = str_replace("!!coll!!", "", $print);
		
	if ($this->subcoll<>"") $print = str_replace("!!subcoll!!", "<b>$msg[subcoll_start]</b>".$this->subcoll."<br />", $print);
		else $print = str_replace("!!subcoll!!", "", $print);

	if ($this->year<>"") $print = str_replace("!!year!!", "<b>$msg[year_start]</b>".$this->year."<br />", $print);
		else $print = str_replace("!!year!!", "", $print);

	if ($this->nocoll<>"") $print = str_replace("!!nocoll!!", "<b>$msg[nocoll_start]</b>".$this->nocoll."<br />", $print);
		else $print = str_replace("!!nocoll!!", "", $print);

	if ($this->code<>"") {
		// Si c'est un livre, c'est magique alors on affiche l'image tirée de chez amazon europe.
		if (($this->typdoc=='a') && ($opac_show_book_pics=='1')) {
			$code_chiffre = pmb_preg_replace('/-|\.| /', '', $this->code);
			//if (@fopen("http://images-eu.amazon.com/images/P/".$code_chiffre.".08.MZZZZZZZ.jpg","r"))
			if (isISBN($this->code))  
				$print = str_replace("!!image_petit!!", "<img src='http://images-eu.amazon.com/images/P/".$code_chiffre.".08.MZZZZZZZ.jpg' align='right' hspace='4' vspace='2'>", $print);
				else $print = str_replace("!!image_petit!!", "", $print);	
		} else 	$print = str_replace("!!image_petit!!", "", $print);		
		$print = str_replace("!!code!!", "<b>$msg[code_start]</b>".$this->code."<br />", $print);
		} else {
			$print = str_replace("!!image_petit!!", "", $print);		
			$print = str_replace("!!code!!", "", $print);
			}
			
	if ($this->npages<>"") $print = str_replace("!!npages!!", "<b>$msg[npages_start]</b>".$this->npages."<br />", $print);
		else $print = str_replace("!!npages!!", "", $print);

	if ($this->ill<>"") $print = str_replace("!!ill!!", "<b>$msg[ill_start]</b>".$this->ill."<br />", $print);
		else $print = str_replace("!!ill!!", "", $print);

	if ($this->size<>"") $print = str_replace("!!size!!", "<b>$msg[size_start]</b>".$this->size."<br />", $print);
		else $print = str_replace("!!size!!", "", $print);

	if ($this->accomp<>"") $print = str_replace("!!accomp!!", "<b>$msg[accomp_start]</b>".$this->accomp."<br />", $print);
		else $print = str_replace("!!accomp!!", "", $print);

 	if ($this->n_gen<>"") $print = str_replace("!!n_gen!!", "<b>$msg[n_gen_start]</b>".nl2br(htmlentities($this->n_gen,ENT_QUOTES, $charset))."<br />", $print);
		else $print = str_replace("!!n_gen!!", "", $print);

 	if ($this->n_contenu<>"") $print = str_replace("!!n_contenu!!", "<b>$msg[n_contenu_start]</b>".nl2br(htmlentities($this->n_contenu,ENT_QUOTES, $charset))."<br />", $print);
		else $print = str_replace("!!n_contenu!!", "", $print);

 	if ($this->n_resume<>"") $print = str_replace("!!n_resume!!", "<b>$msg[n_resume_start]</b>".nl2br(htmlentities($this->n_resume,ENT_QUOTES, $charset))."<br />", $print);
		else $print = str_replace("!!n_resume!!","", $print);

	if ($this->index_l<>"") $print = str_replace("!!index_l!!", "<b>$msg[index_l_start]</b>".nl2br($this->index_l)."<br />", $print);
		else $print = str_replace("!!index_l!!", "", $print);

	if ($this->lang<>"") $print = str_replace("!!lang!!", $this->lang."<br />", $print);
		else $print = str_replace("!!lang!!", "", $print);

	if ($this->org_lang<>"") $print = str_replace("!!org_lang!!", $this->org_lang."<br />", $print);
		else $print = str_replace("!!org_lang!!","", $print);

	if ($this->lien<>"") $print = str_replace("!!lien!!", "<br /><a href=\"".$this->lien."\" title=".$this->lien." alt=".$this->lien."><img src=./images/docweb.gif border=0> $this->lien</a>", $print);
		else $print = str_replace("!!lien!!", "", $print);

	if ($this->eformat<>"") $print = str_replace("!!eformat!!", "[".$this->eformat."]<br />"."<br />", $print);
		else $print = str_replace("!!eformat!!", "", $print);

	if ($this->prix<>"") $print = str_replace("!!prix!!", "<b>$msg[price_start]</b> ".$this->prix." <b>$msg[price_end]</b> ", $print);
		else $print = str_replace("!!prix!!", "", $print);

	// remplacement des champs dynamiques
	if (ereg("!!auteur!!", $print)); {
		if ($libelle_mention_resp) $print = str_replace("!!auteur!!", $libelle_mention_resp, $print);
			else $print = str_replace("!!auteur!!", $notice_no_authors_information, $print);
		}

	if (ereg("!!editeur!!", $print)) {
		$remplacement = "";
		if ($this->ed1_id) $remplacement = "<b>$msg[editeur_start]</b> "."<a href='index.php?lvl=publisher_see&id=$this->ed1_id'>$this->ed1</a>"."<br />";
			elseif ($this->ed2_id) $remplacement = "<b>$msg[editeur_start]</b> "."<a href='index.php?lvl=publisher_see&id=$this->ed2_id'>$this->ed2</a>"."<br />";
				else $remplacement = "";
		$print = str_replace("!!editeur!!", $remplacement, $print);
		}

	if (ereg("!!collection!!", $print)) {
		$remplacement = "";
		if ($this->coll_id) $remplacement = "<b>$msg[coll_start]</b> <a href='index.php?lvl=coll_see&id=$this->coll_id'>$this->coll</a>";

		if ($this->subcoll_id) {
			$remplacement .= $remplacement ? ", " : "";
			$remplacement .= "(<a href='index.php?lvl=subcoll_see&id=$this->subcoll_id'>$this->subcoll</a>)<br />";
			} else $remplacement .= "<br />";

		if ($remplacement == "") $remplacement = "";
		$print = str_replace("!!collection!!", $remplacement, $print);
		}

	if (ereg("!!level1!!", $print)) {
		if ($this->tparent) {
			if ($this->tnvol) $titre_affiche = $this->tit1." - ".$this->tparent." [".$this->tnvol."]" ;
				else $titre_affiche = $this->tit1." - ".$this->tparent ;
			} else $titre_affiche = $this->tit1 ;
		if ($this->tit1) $titre = "<a href=index.php?lvl=notice_display&id=$this->id>$titre_affiche</a>";
			elseif ($this->tit2) $titre = "<a href=index.php?lvl=notice_display&id=$this->id>$this->tit2</a>";
				elseif ($this->tit3) $titre = "<a href=index.php?lvl=notice_display&id=$this->id>$this->tit3</a>";
					elseif ($this->tit4) $titre = "<a href=index.php?lvl=notice_display&id=$this->id>$this->tit4</a>";
						else $titre = "";

		// ***
		//$this->responsabilites
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			$auteur = $auteur->isbd_entry;
			} else {
				$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
				for ($i = 0 ; $i < count($as) ; $i++) {
					$indice = $as[$i] ;
					$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
					$auteur = new auteur($auteur_1["id"]);
					$aut1_libelle[]= $auteur->isbd_entry;
					}
				if (!$aut1_libelle) $aut1_libelle=array();
				$auteur = implode (", ",$aut1_libelle) ;
				}
		// ***
		$remplacement = $titre;
		if (($remplacement != "") && ($auteur != ""))
			$remplacement .= " / ".$auteur;

		$print = str_replace("!!level1!!", $remplacement, $print);
		}

	return $print;
	}
	

} // fin de la classe Notice

} // fin de définition de NOTICE_CLASS
