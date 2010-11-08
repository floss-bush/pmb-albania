<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_display.class.php,v 1.6 2010-08-17 13:21:26 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$class_path/marc_table.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/serie.class.php");
require_once("$include_path/notice_authors.inc.php");
require_once("$include_path/isbn.inc.php");


if (!sizeof($tdoc)) $tdoc = new marc_list('doctype');
if (!count($fonction_auteur)) {
	$fonction_auteur = new marc_list('function');
	$fonction_auteur = $fonction_auteur->table;
}

// définition de la classe d'affichage des monographies en liste pour selecteur
class sel_mono_display {
	
	var $notice_id = 0;		//id notice
	var $notice = '';		//objet notice
  	var $header = '';		//entete
	var $result	= '';		//affichage final
	var $isbd = '';			//isbd notice
	var $responsabilites =	array("responsabilites" => array(),"auteurs" => array());  //auteurs
	var $statut = '' ;		//statut notice
	var $tit_serie = '';	//titre serie
	var $tit1 = '';			//titre 1
	var $nb_expl = 0;		//nb exemplaires

	var $base_url = '';					//URL a associer aux elements cliquables
  	var $action = '';					//action a effectuer pour retour des parametres		
	var $action_values = array();		//tableau des elements à modifier dans l'action
		
	var $code = '';			//isbn ou code EAN de la notice à afficher
	var $titre = '';		//titre renvoye
	var $auteur1 = '';		//auteur1 renvoye
	var $editeur1 = '';		//editeur1 renvoye
	var $collection = '';	//collection renvoyee
	var $prix = '0.00';		//prix renvoye

	
	// constructeur
	function sel_mono_display($notice_id, $base_url) {

		if (!$notice_id) return;
		$this->notice_id=$notice_id;
	  	$this->base_url=$base_url;
	}
	

	//creation formulaire
	function doForm() {
	
		$this->getData();
		$this->responsabilites = get_notice_authors($this->notice_id) ;
		$this->doHeader();
		$this->doContent();
		$this->finalize();
	}

	
	// récupération des valeurs en table
	function getData() {
		global $dbh;
		
		$q = "SELECT * FROM notices WHERE notice_id='".$this->notice_id."' ";
		$r = mysql_query($q, $dbh);
		if(mysql_num_rows($r)) {
			$this->notice = mysql_fetch_object($r);
		}
		$q = "select count(*) from exemplaires where expl_notice='".$this->notice_id."' ";
		$r = mysql_query($q, $dbh);
		$this->nb_expl = mysql_result($r,0,0);
	}
	

	// creation header
	function doHeader() {
		
		global $dbh, $charset;
		global $pmb_notice_reduit_format;
		
		//gen. statut
		if ($this->notice->statut) {
			$rqt_st = "SELECT class_html , gestion_libelle FROM notice_statut WHERE id_notice_statut='".$this->notice->statut."' ";
			$res_st = mysql_query($rqt_st, $dbh) or die ($rqt_st. " ".mysql_error()) ;
			$class_html = " class='".mysql_result($res_st, 0, 0)."' ";
			if ($this->notice->statut>1) $txt = mysql_result($res_st, 0, 1) ;
			else $txt = "" ;
		} else {
			$class_html = " class='statutnot1' " ;
			$txt = "" ;
		}
		if ($this->notice->commentaire_gestion) { 
			if ($txt) $txt .= ":\r\n".$this->notice->commentaire_gestion ;
			else $txt = $this->notice->commentaire_gestion ;
		}
		if ($txt) {
			$statut = "<small><span $class_html style='margin-right: 3px;'><a href=# onmouseover=\"z=document.getElementById('zoom_statut".$this->notice_id."'); z.style.display=''; \" onmouseout=\"z=document.getElementById('zoom_statut".$this->notice_id."'); z.style.display='none'; \"><img src='./images/spacer.gif' width='10' height='10' /></a></span></small>";
			$statut .= "<div id='zoom_statut".$this->notice_id."' style='border: solid 2px #555555; background-color: #FFFFFF; position: absolute; display:none; z-index: 2000;'><b>".nl2br(htmlentities($txt,ENT_QUOTES, $charset))."</b></div>" ;
		} else $statut = "<small><span $class_html style='margin-right: 3px;'><img src='./images/spacer.gif' width='10' height='10' /></span></small>";
		$this->statut = $statut ; 
		
		
		//aff. titre série
		if($this->notice->tparent_id) {
			$parent = new serie($this->notice->tparent_id);
			$this->tit_serie = $parent->name;
			$this->header.= $this->tit_serie;
			if($this->notice->tnvol) {
				$this->header .= ',&nbsp;'.$this->notice->tnvol;
			}
		}
		
		//aff. titre1
		$this->tit1 = $this->notice->tit1;		
		$this->tit_serie ? $this->header.= '.&nbsp;'.$this->tit1 : $this->header.= $this->tit1;
		
		//aff. auteur1
		$as = array_search ("0", $this->responsabilites["responsabilites"]);
		$aut1_libelle = array() ;
		$lib_auteur='';
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			if ($auteur->isbd_entry) {
				$lib_auteur=$auteur->isbd_entry;
			}
		} else {
			$as = array_keys ($this->responsabilites["responsabilites"], "1" );
			for ($i = 0 ; $i < count($as) ; $i++) {
				$indice = $as[$i] ;
				$auteur_1 = $this->responsabilites["auteurs"][$indice];
				$auteur = new auteur($auteur_1["id"]);
				$aut1_libelle[]= $auteur->isbd_entry;
			}
			$auteurs_liste = implode ("; ",$aut1_libelle) ;
			if ($auteurs_liste) {
				$this->header .= ' / '. $auteurs_liste;
				$lib_auteur=$auteurs_liste;
			}
		}
		if ($lib_auteur!='') {
			$this->header .= ' / '. $lib_auteur;
		}
		
		//aff. annee
		switch ($pmb_notice_reduit_format) {
			case '1':
				if ($this->notice->year != '') $this->header.=' ('.htmlentities($this->notice->year, ENT_QUOTES, $charset).')';
				break;
			case "2":
				if ($this->notice->year != '') $this->header.=' ('.htmlentities($this->notice->year, ENT_QUOTES, $charset).')';
				if ($this->notice->code != '') $this->header.=' / '.htmlentities($this->notice->code, ENT_QUOTES, $charset);
				break;
			default : 
				break;
		}
		
		//renv. code
		$this->code = $this->notice->code; 
		
		//renvoi titre
		$this->titre = $this->tit_serie;
		if($this->notice->tnvol) {
			$this->titre.= ',&nbsp;'.$this->notice->tnvol;
		}
		$this->titre ? $this->titre .= '.&nbsp;'.$this->tit1 : $this->titre = $this->tit1;
		
		//renv. auteur1
		$this->auteur1=$lib_auteur;
		
		//renv. editeur, collection
		if($this->notice->subcoll_id) {
			$collection = new subcollection($this->notice->subcoll_id);
			$ed_obj = new editeur($collection->editeur) ;
			$this->editeur1 = $ed_obj->isbd_entry;
			$this->collection = $collection->isbd_entry;
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$ed_obj = new editeur($collection->parent) ;
			$this->editeur1 = $ed_obj->isbd_entry; 
			$this->collection = $collection->isbd_entry;
		} elseif ($this->notice->ed1_id) {
			$editeur = new editeur($this->notice->ed1_id);
			$this->editeur1 = $editeur->isbd_entry;
		}
		$this->ed_date = $this->notice->year;
		//renv. prix
		$this->prix=$this->notice->prix;
	}
	
	
	// creation contenu
	function doContent() {
		global $tdoc;
		global $fonction_auteur;
	
		//mention titre
		$this->isbd = $this->titre;
		$this->isbd .= ' ['.$tdoc->table[$this->notice->typdoc].']';
		if($this->notice->tit3) $this->isbd .= "&nbsp;= ".$this->notice->tit3;
		if($this->notice->tit4) $this->isbd .= "&nbsp;: ".$this->notice->tit4;
		if($this->notice->tit2) $this->isbd .= "&nbsp;; ".$this->notice->tit2;
		
		//mention responsabilité
		$mention_resp = array() ;
		$as = array_search ("0", $this->responsabilites["responsabilites"]);
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_0["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_0["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		$as = array_keys ($this->responsabilites["responsabilites"], "1" );
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_1["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_1["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_2["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_2["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_2["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		$libelle_mention_resp = implode ("; ",$mention_resp) ;
		if($libelle_mention_resp) {
				$this->isbd .= "&nbsp;/ $libelle_mention_resp" ;
		}			
	
		// mention edition
		if($this->notice->mention_edition) {
			$this->isbd .= ".&nbsp;-&nbsp;".$this->notice->mention_edition;
		}
		
		// zone de l'adresse
		// on récupère la collection au passage, si besoin est
		if($this->notice->subcoll_id) {
			$collection = new subcollection($this->notice->subcoll_id);
			$ed_obj = new editeur($collection->editeur) ;
			$editeurs .= $ed_obj->isbd_entry; 
			$collections = $collection->isbd_entry;
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$ed_obj = new editeur($collection->parent) ;
				$editeurs .= $ed_obj->isbd_entry; 
				$collections = $collection->isbd_entry;
		} elseif ($this->notice->ed1_id) {
			$editeur = new editeur($this->notice->ed1_id);
			$editeurs .= $editeur->isbd_entry;
		}
		if($this->notice->ed2_id) {
			$editeur = new editeur($this->notice->ed2_id);
			$ed_isbd=$editeur->isbd_entry;
			$editeurs ? $editeurs .= '&nbsp;; '.$ed_isbd : $editeurs = $ed_isbd;
		}
		if($this->notice->year) {
			$editeurs ? $editeurs .= ', '.$this->notice->year : $editeurs = $this->notice->year;
		}
		if ($editeurs) {
			$this->isbd .= ".&nbsp;-&nbsp;$editeurs";
		}
		
		// zone de la collation
		if($this->notice->npages) {
			$collation = $this->notice->npages;
		}
		if($this->notice->ill) {
			$collation .= ': '.$this->notice->ill;
		}
		if($this->notice->size) {
			$collation .= '; '.$this->notice->size;
		}
		if($this->notice->accomp) {
			$collation .= '+ '.$this->notice->accomp;
		}
		if($collation) {
			$this->isbd .= ".&nbsp;-&nbsp;$collation";
		}
		if($collections) {
			if($this->notice->nocoll) {
				$collections .= '; '.$this->notice->nocoll;
			}
			$this->isbd .= ".&nbsp;-&nbsp;($collections)".' ';
		}

		if(substr(trim($this->isbd), -1) != "."){
			$this->isbd .= '.';
		}
		// ISBN ou NO. commercial
		if($this->notice->code) {
			if(isISBN($this->notice->code)) {
				$zoneNote = 'ISBN ';
			}
			$zoneNote .= $this->notice->code;
		}

		//prix code
		if($this->notice->prix) {
			if($this->notice->code) {
				$zoneNote .= '&nbsp;: '.$this->notice->prix;
			} else { 
				if ($zoneNote) { 
					$zoneNote .= '&nbsp; '.$this->notice->prix;
				} else {
					$zoneNote = $this->notice->prix;
				}
			}
		}
		if($zoneNote) {
			$this->isbd .= "<br /><br />$zoneNote.";
		}
	}	

	
	//génération du template javascript
	function finalize() {
		
		global $msg;
		
		$javascript_template ="
						<div id='el_!!id!!_Parent' class='notice-parent'>
							<img src='./images/plus.gif' class='img_plus' name='imEx' id='el_!!id!!_Img' title='".$msg['admin_param_detail']."' onClick=\"expandBase('el_!!id!!_', true); return false;\" />
							<span class='notice-heada'>!!header!!</span>
							<div id='el_!!id!!_Child' class='notice-child' style='width:inherit;display:none;' >
	   					 		!!isbd!!
							</div>
						</div>";

		if ($this->action) {
			$this->header = str_replace('!!display!!', $this->header, $this->action);
			$this->header = $this->statut.$this->header;
			$this->header = str_replace('!!id!!', $this->notice_id, $this->header);
			if (count($this->action_values)) {
				foreach($this->action_values as $v) {
					$this->header = str_replace("!!$v!!", addslashes($this->$v), $this->header);
				}
			}
		}
		
		$this->result = str_replace('!!id!!', $this->notice_id, $javascript_template);
		$this->result = str_replace('!!header!!', $this->header, $this->result);
		
		$this->result = str_replace('!!isbd!!', $this->isbd, $this->result);		
	}
}



// définition de la classe d'affichage des périodiques en liste pour selecteur
class sel_serial_display {

	var $notice_id = 0;				// id de la notice à afficher 	
	var $notice;					// objet notice (tel que fetché dans la table 'notices'
	var $header	= '';				// chaine accueillant le chapeau de notice (peut-être cliquable)

	var $tit1 = '';					// valeur du titre 1
	var $result = '';				// affichage final
	var $level = 1;					// niveau d'affichage
	var $isbd = '';					// isbd de la notice
	var $nb_bull = 0;				// nombre de bulletins
	var $nb_expl = 0;				// nombre d'exemplaires
	var $nb_art = 0;				// nombre d'articles
	var $responsabilites = array("responsabilites" => array(),"auteurs" => array());  // les auteurs
	var $show_statut = 1;
	var $aff_statut = '' ; 			// carré de couleur pour signaler le statut de la notice

  	var $base_url = '';				// URL à associer aux éléments cliquables
  	var $action = '';				// URL à associer aux notices		
	var $action_values = array();	// tableau des elements à modifier dans l'action

	
	// constructeur
	function sel_serial_display ($notice_id, $base_url) {

		if (!$notice_id) return;
		$this->notice_id = $notice_id;
	  	$this->base_url = $base_url;
	  	
	}

	//creation formulaire
	function doForm() {
			
		$this->getData();
		$this->responsabilites = get_notice_authors($this->notice_id) ;
		$this->doHeader();
		$this->initJavascript();
		$this->doContent();
		$this->finalize();
		return;
	}

		
	// récupération des valeurs en table
	function getData() {
		global $dbh;
		
		$q = "SELECT * FROM notices WHERE notice_id=".$this->notice_id;
		$r = mysql_query($q, $dbh);
		if (mysql_num_rows($r)) {
			$this->notice = mysql_fetch_object($r);
		}
	}
	
	
	// creation header
	function doHeader() {
		
		global $dbh, $charset;
		
		if ($this->notice->statut) {
			$rqt_st = "SELECT class_html , gestion_libelle FROM notice_statut WHERE id_notice_statut='".$this->notice->statut."' ";
			$res_st = mysql_query($rqt_st, $dbh);
			$class_html = " class='".mysql_result($res_st, 0, 0)."' ";
			if ($this->notice->statut>1) {
				$txt = mysql_result($res_st, 0, 1);
			} else {
				$txt = '';
			}
		} else {
			$class_html = " class='statutnot1' " ;
			$txt = '' ;
		}
		if ($this->notice->commentaire_gestion) { 
			if ($txt) {
				$txt .= ":\r\n".$this->notice->commentaire_gestion ;
			} else {
				$txt = $this->notice->commentaire_gestion ;
			}
		}
		if ($txt) {
			$statut = "<small><span $class_html style='margin-right: 3px;'><a href=# onmouseover=\"z=document.getElementById('zoom_statut".$this->notice_id."'); z.style.display=''; \" onmouseout=\"z=document.getElementById('zoom_statut".$this->notice_id."'); z.style.display='none'; \"><img src='./images/spacer.gif' width='10' height='10' /></a></span></small>";
			$statut .= "<div id='zoom_statut".$this->notice_id."' style='border: solid 2px #555555; background-color: #FFFFFF; position: absolute; display:none; z-index: 2000;'><b>".nl2br(htmlentities($txt,ENT_QUOTES, $charset))."</b></div>" ;
		} else {
			$statut = "<small><span $class_html style='margin-right: 3px;'><img src='./images/spacer.gif' width='10' height='10' /></span></small>";
		}
		$this->aff_statut = $statut ; 
		
		$this->header = htmlentities($this->notice->tit1,ENT_QUOTES, $charset);
	
		//$this->responsabilites
		$aut1_libelle = array() ;
		$lib_auteur='';
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			if ($auteur->isbd_entry) {
				$lib_auteur=$auteur->isbd_entry;
			}
		} else {
			$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
			for ($i = 0 ; $i < count($as) ; $i++) {
				$indice = $as[$i] ;
				$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
				$auteur = new auteur($auteur_1["id"]);
				$aut1_libelle[]= $auteur->isbd_entry;
			}
			$auteurs_liste = implode ("; ",$aut1_libelle) ;
			if ($auteurs_liste) {
				$this->header .= ' / '. $auteurs_liste ;
				$lib_auteur=$auteurs_liste;
			}
		}
		if ($lib_auteur!='') {
			$this->header .= ' / '. $lib_auteur;
		}
		
		if ($this->action) {
			$this->header = str_replace('!!display!!', $this->header, $this->action);
			$this->header = str_replace('!!aut_id!!', $this->notice_id, $this->header);
			
			if (count($this->action_values)) {
				foreach($this->action_values as $v) {
					$this->header = str_replace("!!$v!!", addslashes($this->$v), $this->header);
				}
			}
		}
		
		if ($this->show_statut) {
			$this->header = $this->aff_statut.$this->header ;
		}
	}

	
	// génération du template javascript
	function initJavascript() {
		global $msg;
		
		$javascript_template ="
						<div id='el_!!id!!_Parent' class='notice-parent'>
							<img src='./images/plus.gif' class='img_plus' name='imEx' id='el_!!id!!_Img' title='".$msg['admin_param_detail']."' onClick=\"expandBase('el_!!id!!_', true); return false;\" />
							<span class='notice-heada'>!!header!!</span>
							<div id='el_!!id!!_Child' class='notice-child' style='width:inherit;display:none;' >
	   					 		!!serial_type!! !!isbd!!
							</div>
						</div>";
		$this->result = str_replace('!!id!!', $this->notice_id, $javascript_template);
		$this->result = str_replace('!!header!!', $this->header, $this->result);		
	}

	
	// creation contenu
	function doContent() {
		global $dbh, $msg;
		global $fonction_auteur;
		global $pmb_etat_collections_localise, $pmb_droits_explr_localises, $explr_visible_mod;
		
		$this->isbd = $this->notice->tit1;
		
		// constitution de la mention de titre
		$tit3 = $this->notice->tit3;
		$tit4 = $this->notice->tit4;
		if($tit3) $this->isbd .= "&nbsp;= $tit3";
		if($tit4) $this->isbd .= "&nbsp;: $tit4";
	
		// constitution de la mention de responsabilité
		//$this->responsabilites
		$mention_resp = array() ;
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_0["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_0["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		
		$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_1["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_1["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		
		$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_2["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_2["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_2["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
			
		$libelle_mention_resp = implode ("; ",$mention_resp) ;
		if($libelle_mention_resp) {
			$this->isbd .= "&nbsp;/ ". $libelle_mention_resp ." " ;
		}
	
		// zone de l'adresse
		if($this->notice->ed1_id) {
			$editeur = new editeur($this->notice->ed1_id);
			$editeurs .= $editeur->isbd_entry;
		}
		if($this->notice->ed2_id) {
			$editeur = new editeur($this->notice->ed2_id);
			$ed_isbd=$editeur->isbd_entry; 
			if($editeurs) {
				$editeurs .= '&nbsp;; '.$ed_isbd;
			} else {
				$editeurs .= $ed_isbd;
			}
		}

		if($this->notice->year) 
			$editeurs ? $editeurs .= ', '.$this->notice->year : $editeurs = $this->notice->year;
			
		if($editeurs) {
			$this->isbd .= ".&nbsp;-&nbsp;$editeurs";
		}

		//code (ISSN,...)
		if ($this->notice->code) $this->isbd .="<br /><b>${msg[165]}</b>&nbsp;: ".$this->notice->code;
			
					
		// Si notice-mère alors on compte le nombre de numéros (bulletins)
		if($this->notice->niveau_biblio=="s") {
			$requete = "SELECT * FROM bulletins WHERE bulletin_notice=".$this->notice_id;
			$Query = mysql_query($requete, $dbh);
			$this->nb_bull=mysql_num_rows($Query);
			while (($row = mysql_fetch_array($Query))) {
				$requete2 = "SELECT count( * )  AS nb_art FROM  analysis WHERE analysis_bulletin =".$row['bulletin_id'];
				$Query2 = mysql_query($requete2, $dbh);
				$analysis_array=mysql_fetch_array($Query2);
				$this->nb_art+=$analysis_array['nb_art'];
				$requete3 = "SELECT count( expL_id ) AS nb_expl FROM  exemplaires WHERE expl_bulletin =".$row['bulletin_id'];
				$Query3 = mysql_query($requete3, $dbh);
				$expl_array=mysql_fetch_array($Query3);
				$this->nb_expl+=$expl_array['nb_expl'];			
			}
				
			// Cas général : au moins un bulletin
			if (mysql_num_rows($Query)>0)
				{$this->isbd .="<br /><br />\n
				<b>".$msg["serial_bulletinage_etat"]."</b>
				<table border='0' class='expl-list'>
				<tr><td><strong>$this->nb_bull</strong> ".$msg["serial_nb_bulletin"]."
				<strong>$this->nb_expl</strong> ".$msg["bulletin_nb_ex"]."	
				<strong>$this->nb_art</strong> ".$msg["serial_nb_articles"]."	
				</td>
				</tr></table>";
									
			} else { // 0 bulletin
				$this->isbd .="<br /><br />\n
				<b>".$msg["serial_bulletinage_etat"]."</b>
				<table border='0' class='expl-list'>
				<tr><td><strong>$this->nb_bull</strong>
				".$msg["serial_nb_bulletin"]." : <strong>";
				$this->isbd .=$msg["bull_no_expl"];
				$this->isbd .="</strong></td>
				</tr></table>";
			}
			
			//état des collections
			if ($pmb_etat_collections_localise&&$pmb_droits_explr_localises&&$explr_visible_mod) {
				$restrict_location=" and location_id in (".$explr_visible_mod.") and idlocation=location_id";	
				$table_location=",docs_location";
				$select_location=",location_libelle";
			} else {
				$restrict_location=" group by id_serial";
			}
			$rqt="select state_collections$select_location from collections_state$table_location where id_serial=".$this->notice_id.$restrict_location;
			$execute_query=mysql_query($rqt);
			if ($execute_query) {
				if (mysql_num_rows($execute_query)) {
					$bool=false;
					$affichage="<br /><strong>".$msg["4001"]."</strong><br />";
					while (($r=mysql_fetch_object($execute_query))) {
						if ($r->state_collections) {
							if ($r->location_libelle) $affichage .= "<strong>".$r->location_libelle."</strong> : ";
							$affichage .= $r->state_collections."<br />\n";
							$bool=true;
						}	
					}
					if ($bool==true) $this->isbd .= $affichage;
				}
			}
		}
		return;
	}	
	
	
	// finalisation du résultat
	function finalize() {
		
		global $msg ;
		
		$this->result = str_replace('!!isbd!!', $this->isbd, $this->result);
		$this->result = str_replace('!!serial_type!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>", $this->result);
	}
	
}



//définition de la classe d'affichage des bulletins en liste pour selecteur
class sel_bulletin_display {

	var $result = '';
	var $bulletin_id=0;
	var $bulletin = '';
	var $nb_expl=0;
	
 	var $base_url = '';				// URL à associer aux éléments cliquables
  	var $action = '';				// URL à associer aux notices		
	var $action_values = array();	// tableau des elements à modifier dans l'action
	
	var $titre = '';				//titre renvoye
	var $editeur1 = '';				//editeur 1 renvoye
	var $numero = '';				//numero renvoye
	var $aff_date = '';				//date renvoyee
	var $prix = '0.00';				//prix renvoye
	var $code = '';					//code renvoye
	
	//constructeur
	function sel_bulletin_display($bulletin_id, $base_url) {
		
		if(!$bulletin_id) return;
		$this->bulletin_id=$bulletin_id;
		$this->base_url=$base_url;
	}
	
	function doForm() {
		
		global $charset;
		
		$this->getData();
		
		$this->titre = $this->bulletin->tit1;
		if ($this->bulletin->ed1_id) {
			$ed1= new editeur($this->bulletin->ed1_id);
			$this->editeur1 = $ed1->isbd_entry;
		}
		if ($this->bulletin->bulletin_numero!=='') {
			$this->numero = $this->bulletin->bulletin_numero;
		}
		if ($this->bulletin->libelle_periode) {
			$this->aff_date = "(".$this->bulletin->libelle_periode.")";
		}
		if ($this->bulletin->date_date!='0000-00-00') {
			$this->aff_date.= " [".$this->bulletin->aff_date_date."]";
		}	
		if ($this->bulletin->bulletin_cb!='') {
			$this->code = $this->bulletin->bulletin_cb;
		}
		
		$aff = $this->titre;					
		if($this->numero) {
			$aff.= '. '.$this->numero;
		}
		$aff.= ' '.$this->aff_date;			
				
		$aff = htmlentities($aff, ENT_QUOTES, $charset);
		if ($this->action) {
			$aff= str_replace('!!display!!', "<b>$aff</b>", $this->action);
			if (count($this->action_values)) {
				foreach($this->action_values as $v) {
					$aff = str_replace("!!$v!!", addslashes($this->$v), $aff);
				}
			}
		}	
		$this->result = $aff;					
	}
	
	// récupération des valeurs en table
	function getData() {
		
		global $dbh, $msg;
		
		$q = "SELECT notices.tit1, notices.ed1_id, notices.code, bulletins.*, date_format(bulletins.date_date, '".$msg['format_date']."') as aff_date_date FROM bulletins join notices on bulletin_notice=notice_id WHERE bulletin_id='".$this->bulletin_id."' ";
		$r = mysql_query($q, $dbh);
		if(mysql_num_rows($r)) {
			$this->bulletin = mysql_fetch_object($r);
		}
	
		$q = "select count(*) from exemplaires where expl_bulletin='".$this->bulletin_id."' ";
		$r = mysql_query($q, $dbh);
		$this->nb_expl = mysql_result($r,0,0);

	}
}


	
//définition de la classe d'affichage des abonnements en liste pour selecteur
class sel_abt_display {

	var $abt_id=0;					//id abonnement
	var $abt = '';					//objet abonnement 
	var $header = '';				//entete
	var $result = '';				// affichage final
	var $isbd = '';					// isbd notice
	var $responsabilites =	array("responsabilites" => array(),"auteurs" => array());  //auteurs
	var $aff_date_echeance = '';	//date echeance abt actuel	
	
 	var $base_url = '';				// URL à associer aux éléments cliquables
  	var $action = '';				// URL à associer aux notices		
	var $action_values = array();	// tableau des elements à modifier dans l'action
	
	var $code = '';					//code renvoye
	var $titre = '';				//titre renvoye
	var $editeur1 = '';				//editeur 1 renvoye
	var $periodicite = '';			//periodicite
	var $duree = '';				//duree abt
	var $prix = '0.00';				//prix renvoye
	var $aff_date_debut = '';		//date debut abt renvoyee	

//TODO
	var $nb_num = 0;				//nb numeros
	
	
	//constructeur
	function sel_abt_display($abt_id, $base_url) {

		if(!$abt_id) return;
		$this->abt_id=$abt_id;
		$this->base_url=$base_url;
	}
	
	
	//creation formulaire
	function doForm() {
		
		$this->getData();
		$this->responsabilites = get_notice_authors($this->abt->num_notice) ;
		$this->doHeader();
		$this->doContent();
		$this->finalize();
	}		

	
	// récupération des valeurs en table
	function getData() {

		global $dbh;
		
		$q = "SELECT abts_abts.*, ";
		$q.= "notices.tit1, notices.tit3, notices.tit4, notices.ed1_id, notices.ed2_id, notices.year, notices.code, notices.prix, ";
		$q.= "abts_modeles.num_periodicite, abts_periodicites.libelle, ";
		$q.= "docs_location.location_libelle ";
		$q.= "FROM abts_abts ";
		$q.= "join abts_abts_modeles on abts_abts.abt_id=abts_abts_modeles.abt_id ";
		$q.= "join abts_modeles on abts_abts_modeles.modele_id=abts_modeles.modele_id ";
		$q.= "left join abts_periodicites on abts_modeles.num_periodicite=abts_periodicites.periodicite_id ";
		$q.= "join notices on abts_abts.num_notice=notices.notice_id ";
		$q.= "join docs_location on abts_abts.location_id=docs_location.idlocation ";
		$q.= "where abts_abts.abt_id='".$this->abt_id."' ";
		$r = mysql_query($q, $dbh); 
		$this->abt = mysql_fetch_object($r);
	}
	
	
	// creation header
	function doHeader() {
		
		global $dbh, $msg, $charset;
		
		//aff. nom abonnement
		$this->header = htmlentities($this->abt->abt_name, ENT_QUOTES, $charset);
		
		//aff. localisation
		$this->header.= "&nbsp;/&nbsp;".htmlentities($this->abt->location_libelle, ENT_QUOTES, $charset);
		
		//aff. periodicite
		if ($this->abt->num_periodicite) {
			$this->header.= "&nbsp;(".htmlentities($this->abt->libelle, ENT_QUOTES, $charset);
		} else {
			$this->header.= "&nbsp;(".htmlentities($msg['abonnements_periodicite_manuel'], ENT_QUOTES, $charset);
		}
		
		//aff. duree
		$this->header.= "&nbsp;-&nbsp;".$this->abt->duree_abonnement."&nbsp;".htmlentities($msg['abonnements_periodicite_unite_mois'], ENT_QUOTES, $charset).")";

		//aff. date echeance
		$this->aff_date_echeance = format_date($this->abt->date_fin); 

		//renv. code
		$this->code=$this->abt->code;
		
		//renv. titre
		$this->titre = $this->abt->tit1;
		if ($this->abt->tit3) $this->titre.= "&nbsp;= ".$this->abt->tit3;
		if ($this->abt->tit4) $this->titre.= "&nbsp;: ".$this->abt->tit4;
		
		//renv. editeur1
		if ($this->abt->ed1_id) {
			$editeur = new editeur($this->abt->ed1_id);
			$this->editeur1 = $editeur->isbd_entry;
		}
		
		//renv. periodicite 
		if ($this->abt->num_periodicite) {
			$this->periodicite=$this->abt->libelle;
		}

		//renv. duree
		$this->duree = $this->abt->duree_abonnement;
		
		//renv. date debut abt
		$q = "select date_add('".$this->abt->date_fin."', interval 1 day) as date_debut, ";
		$q.= " date_add('".$this->abt->date_fin."', interval ".$this->duree." month) as date_fin ";
		$r = mysql_query($q, $dbh);
		$obj = mysql_fetch_object($r); 
		$this->aff_date_debut = format_date($obj->date_debut);
		
//TODO A revoir, car le prix n'est pas accessible sur les notices de perio
		//renv. prix
		if ($this->abt->prix!=='') $this->prix=$this->abt->prix;
		
	}
	
	
	// creation contenu
	function doContent() {
		
		global $msg;
		global $fonction_auteur;
		
		//mention titre
		$this->isbd = $this->titre;
		
		//mention responsabilité
		$mention_resp = array() ;
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_0["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_0["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_1["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_1["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_2["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_2["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_2["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		$libelle_mention_resp = implode ("; ",$mention_resp) ;
		if($libelle_mention_resp) {
			$this->isbd .= "&nbsp;/ ". $libelle_mention_resp ." " ;
		}
	
		// zone de l'adresse
		if($this->abt->ed1_id) {
			$editeur = new editeur($this->abt->ed1_id);
			$ed_isbd .= $editeur->isbd_entry;
		}
		if($this->abt->year) {
			$ed_isbd ? $ed_isbd .= ', '.$this->abt->year : $ed_isbd = $this->abt->year;
		}	
		if($ed_isbd) {
			$this->isbd .= ".&nbsp;-&nbsp;$ed_isbd";
		}

		//code (ISSN,...)
		if ($this->abt->code) $this->isbd .="<br /><b>${msg[165]}</b>&nbsp;: ".$this->abt->code;
	}	
	
	
	//génération du template javascript
	function finalize() {
		
		global $msg;
		
		$javascript_template ="
			<div id='el_!!id!!_Parent' class='notice-parent'>
				<img src='./images/plus.gif' class='img_plus' name='imEx' id='el_!!id!!_Img' title='".$msg['admin_param_detail']."' onClick=\"expandBase('el_!!id!!_', true); return false;\" />
				<span class='notice-heada'>!!header!!</span>
				<div id='el_!!id!!_Child' class='notice-child' style='width:inherit;display:none;' >
   			 		!!serial_type!! !!isbd!!
				</div>
			</div>";
		
		if ($this->action) {
			$this->header = str_replace('!!display!!', $this->header, $this->action);
			$this->header = str_replace('!!id!!', $this->abt_id, $this->header);
			
			if (count($this->action_values)) {
				foreach($this->action_values as $v) {
					$this->header = str_replace("!!$v!!", addslashes($this->$v), $this->header);
				}
			}
		}	
		
		$this->result = str_replace('!!id!!', $this->abt_id, $javascript_template);
		$this->result = str_replace('!!header!!', $this->header, $this->result);		

		$this->result = str_replace('!!isbd!!', $this->isbd, $this->result);
		$this->result = str_replace('!!serial_type!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>", $this->result);
	}	
}	


require_once("$class_path/frais.class.php");
//Classe d'affichage des frais dans un selecteur
class sel_frais_display extends frais {
	
	var $result='';
	var $lib_montant='';
	var $taux_tva = '0.00';
	
	var $base_url = '';			//URL a associer aux elements cliquables
	var $action = '';
	var $action_values = array();
	
	//Constructeur.	 
	function sel_frais_display($id_frais, $base_url) {
		
		if (!$id_frais) return;
		parent::frais($id_frais);
		$this->base_url=$base_url;
	}
	
	function doForm(){
		
		global $charset;
		global $acquisition_gestion_tva, $pmb_gestion_devise;
		
		if(!$this->id_frais) return;
		
		if ($acquisition_gestion_tva && $this->num_tva_achat) {
			$tva = new tva_achats($this->num_tva_achat);
			$this->taux_tva = $tva->taux_tva;
		}
		
		$aff = htmlentities($this->libelle, ENT_QUOTES, $charset);
		if ($this->action) {
			$aff= str_replace('!!display!!', "<b>$aff</b>", $this->action);
			if (count($this->action_values)) {
				foreach($this->action_values as $v) {
					$aff = str_replace("!!$v!!", addslashes($this->$v), $aff);
				}
			}
		}
		$this->result = $aff;
		$this->lib_montant = $this->montant.'&nbsp;'.$pmb_gestion_devise;
	}
	
}


// définition de la classe d'affichage des articles en liste pour selecteur
class sel_article_display {
	
	var $notice_id = 0;		//id notice
	var $notice = '';		//objet notice
  	var $header = '';		//entete
	var $result	= '';		//affichage final
	var $isbd = '';			//isbd notice
	var $responsabilites =	array("responsabilites" => array(),"auteurs" => array());  //auteurs
	var $statut = '' ;		//statut notice
	var $tit_serie = '';	//titre serie
	var $tit1 = '';			//titre 1

	var $parent_title = '';
	var $parent_numero = '';
	var $parent_date = '';
	var $parent_date_date = '';
	var $parent_aff_date_date = '';
	
	var $base_url = '';					//URL a associer aux elements cliquables
  	var $action = '';					//action a effectuer pour retour des parametres		
	var $action_values = array();		//tableau des elements à modifier dans l'action
		
	var $code = '';			//isbn ou code EAN de la notice à afficher
	var $titre = '';		//titre renvoye
	var $auteur1 = '';		//auteur1 renvoye
	var $in_bull = '';		//lien bulletin renvoye
	var $prix = '0.00';		//prix renvoye

	
	// constructeur
	function sel_article_display($notice_id, $base_url) {

		if (!$notice_id) return;
		$this->notice_id=$notice_id;
	  	$this->base_url=$base_url;
	}
	

	//creation formulaire
	function doForm() {
	
		$this->getData();
		$this->responsabilites = get_notice_authors($this->notice_id) ;
		$this->doHeader();
		$this->doContent();
		$this->finalize();
	}

	
	// récupération des valeurs en table
	function getData() {
		global $dbh,$msg;
		
		$q = "SELECT * FROM notices WHERE notice_id='".$this->notice_id."' ";
		$r = mysql_query($q, $dbh);
		if(mysql_num_rows($r)) {
			$this->notice = mysql_fetch_object($r);
		}
		// récupération des données du bulletin et de la notice apparentée
		$requete = "SELECT b.tit1,c.*,date_format(date_date, '".$msg["format_date"]."') as aff_date_date "; 
		$requete.= "from analysis a, notices b, bulletins c ";
		$requete.= "WHERE a.analysis_notice=".$this->notice_id." ";
		$requete.= "AND c.bulletin_id=a.analysis_bulletin ";
		$requete .= "AND c.bulletin_notice=b.notice_id ";
		$requete.= "LIMIT 1";
		$myQuery = mysql_query($requete, $dbh);
		if (mysql_num_rows($myQuery)) {
			$parent = mysql_fetch_object($myQuery);
			$this->parent_title = $parent->tit1;
			$this->parent_numero = $parent->bulletin_numero;
			$this->parent_date = $parent->mention_date;
			$this->parent_date_date = $parent->date_date;
			$this->parent_aff_date_date = $parent->aff_date_date;
		}
	}
	
	// creation header
	function doHeader() {
		
		global $dbh, $charset;
		global $pmb_notice_reduit_format;
		
		//gen. statut
		if ($this->notice->statut) {
			$rqt_st = "SELECT class_html , gestion_libelle FROM notice_statut WHERE id_notice_statut='".$this->notice->statut."' ";
			$res_st = mysql_query($rqt_st, $dbh) or die ($rqt_st. " ".mysql_error()) ;
			$class_html = " class='".mysql_result($res_st, 0, 0)."' ";
			if ($this->notice->statut>1) $txt = mysql_result($res_st, 0, 1) ;
			else $txt = "" ;
		} else {
			$class_html = " class='statutnot1' " ;
			$txt = "" ;
		}
		if ($this->notice->commentaire_gestion) { 
			if ($txt) $txt .= ":\r\n".$this->notice->commentaire_gestion ;
			else $txt = $this->notice->commentaire_gestion ;
		}
		if ($txt) {
			$statut = "<small><span $class_html style='margin-right: 3px;'><a href=# onmouseover=\"z=document.getElementById('zoom_statut".$this->notice_id."'); z.style.display=''; \" onmouseout=\"z=document.getElementById('zoom_statut".$this->notice_id."'); z.style.display='none'; \"><img src='./images/spacer.gif' width='10' height='10' /></a></span></small>";
			$statut .= "<div id='zoom_statut".$this->notice_id."' style='border: solid 2px #555555; background-color: #FFFFFF; position: absolute; display:none; z-index: 2000;'><b>".nl2br(htmlentities($txt,ENT_QUOTES, $charset))."</b></div>" ;
		} else $statut = "<small><span $class_html style='margin-right: 3px;'><img src='./images/spacer.gif' width='10' height='10' /></span></small>";
		$this->statut = $statut ; 
		
		$this->tit1 = $this->notice->tit1;		
		$this->header.= $this->tit1;
		
		//aff. auteur1
		$as = array_search ("0", $this->responsabilites["responsabilites"]);
		$aut1_libelle = array() ;
		$lib_auteur='';
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			if ($auteur->isbd_entry) {
				$lib_auteur=$auteur->isbd_entry;
			}
		} else {
			$as = array_keys ($this->responsabilites["responsabilites"], "1" );
			for ($i = 0 ; $i < count($as) ; $i++) {
				$indice = $as[$i] ;
				$auteur_1 = $this->responsabilites["auteurs"][$indice];
				$auteur = new auteur($auteur_1["id"]);
				$aut1_libelle[]= $auteur->isbd_entry;
			}
			$auteurs_liste = implode ("; ",$aut1_libelle) ;
			if ($auteurs_liste) {
				$this->header .= ' / '. $auteurs_liste;
				$lib_auteur=$auteurs_liste;
			}
		}
		if ($lib_auteur!='') {
			$this->header .= ' / '. $lib_auteur;
		}
		
		//renvoi lien bulletin
		$this->in_bull= "in ".$this->parent_title." (".$this->parent_numero." ".($this->parent_date?$this->parent_date:$this->parent_aff_date_date).")";

		$this->header=$this->header." <i>".$this->in_bull."</i> ";
		
		//aff. annee
		switch ($pmb_notice_reduit_format) {
			case '1':
				if ($this->notice->year != '') $this->header.=' ('.htmlentities($this->notice->year, ENT_QUOTES, $charset).')';
				break;
			case "2":
				if ($this->notice->year != '') $this->header.=' ('.htmlentities($this->notice->year, ENT_QUOTES, $charset).')';
				if ($this->notice->code != '') $this->header.=' / '.htmlentities($this->notice->code, ENT_QUOTES, $charset);
				break;
			default : 
				break;
		}
		
		//renvoi titre
		$this->titre = $this->tit1;
		
		//renv. auteur1
		$this->auteur1=$lib_auteur;
		
		//renv. prix
		$this->prix=$this->notice->prix;
	
	}
	
	
	// creation contenu
	function doContent() {
		global $tdoc;
		global $fonction_auteur;
	
		//mention titre
		$this->isbd = $this->titre;
		if($this->notice->tit4) $this->isbd .= "&nbsp;: ".$this->notice->tit4;
		if($this->notice->tit2) $this->isbd .= "&nbsp;; ".$this->notice->tit2;
		$this->isbd .= ' ['.$tdoc->table[$this->notice->typdoc].']';	
		//mention responsabilité
		$mention_resp = array() ;
		$as = array_search ("0", $this->responsabilites["responsabilites"]);
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_0["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_0["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		$as = array_keys ($this->responsabilites["responsabilites"], "1" );
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_1["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_1["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_2["id"]);
			$mention_resp_lib = $auteur->isbd_entry;
			if ($auteur_2["fonction"]) {
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_2["fonction"]];
			}
			$mention_resp[] = $mention_resp_lib ;
		}
		$libelle_mention_resp = implode ("; ",$mention_resp) ;
		if($libelle_mention_resp) {
				$this->isbd .= "&nbsp;/ $libelle_mention_resp" ;
		}			
	
		// zone de la collation
		if($this->notice->npages) {
			$collation = $this->notice->npages;
		}
		if($collation) {
			$this->isbd .= ".&nbsp;-&nbsp;$collation";
		}
		$this->isbd .= '.';

		//prix
		if($this->notice->prix) {
			$zoneNote = $this->notice->prix;
		}
		if($zoneNote) {
			$this->isbd .= "<br /><br />$zoneNote.";
		}
	}	

	
	//génération du template javascript
	function finalize() {
		
		global $msg;
		
		$javascript_template ="
						<div id='el_!!id!!_Parent' class='notice-parent'>
							<img src='./images/plus.gif' class='img_plus' name='imEx' id='el_!!id!!_Img' title='".$msg['admin_param_detail']."' onClick=\"expandBase('el_!!id!!_', true); return false;\" />
							<span class='notice-heada'>!!header!!</span>
							<div id='el_!!id!!_Child' class='notice-child' style='width:inherit;display:none;' >
	   					 		!!isbd!!
							</div>
						</div>";

		if ($this->action) {
			$this->header = str_replace('!!display!!', $this->header, $this->action);
			$this->header = $this->statut.$this->header;
			$this->header = str_replace('!!id!!', $this->notice_id, $this->header);
			if (count($this->action_values)) {
				foreach($this->action_values as $v) {
					$this->header = str_replace("!!$v!!", addslashes($this->$v), $this->header);
				}
			}
		}
		
		$this->result = str_replace('!!id!!', $this->notice_id, $javascript_template);
		$this->result = str_replace('!!header!!', $this->header, $this->result);
		
		$this->result = str_replace('!!isbd!!', $this->isbd, $this->result);		
	}
}

?>