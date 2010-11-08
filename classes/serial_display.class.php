<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serial_display.class.php,v 1.119 2010-07-30 13:17:48 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/parametres_perso.class.php");
require_once($class_path."/marc_table.class.php");
require_once($class_path."/collstate.class.php");

// récupération des codes de fonction
if (!count($fonction_auteur)) {
	$fonction_auteur = new marc_list('function');
	$fonction_auteur = $fonction_auteur->table;
}

// récupération des codes langues
if (!count($langue_doc)) {
	$f_lang = new marc_list('lang');
	$langue_doc = $f_lang->table;
} 

// propriétés pour le selecteur de panier (kinda template)
$selector_prop = "toolbar=no, dependent=yes, width=500, height=400, resizable=yes, scrollbars=yes";
$cart_click = "onClick=\"openPopUp('./cart.php?object_type=NOTI&item=!!item!!&unq=!!unique!!', 'cart', 600, 700, -2, -2, '$selector_prop')\"";

// définition de la classe d'affichage des périodiques
class serial_display {
	var $notice_id				= 0;	// id de la notice à afficher
  	var $notice;					// objet notice (tel que fetché dans la table 'notices'
	var $bul_id				= 0 ;	// id de bulletin récupéré pour l'ISBD
  	var $action_serial_org			= '';	// lien à activer si la notice est s1 (notice chapeau)
  	var $action_analysis_org		= '';	// lien à activer si la notice est a2 (dépouillment)
	var $action_serial			= '';	// lien modifié pour le header
	var $action_analysis			= '';	// lien modifié pour le header (nécessite !!bul_id!!)
	var $action_bulletin			= '';	// action pour la notion de bulletin
	var $header				= '';	// chaine accueillant le chapeau de notice (peut-être cliquable)
	var $tit1				= '';	// valeur du titre 1
	var $parent_id				= 0;	// id de la notice parent
	var $parent_title			= '';	// titre de la notice parent si a2
	var $parent_numero			= '';	// mention de numérotation dans le bulletinage associé
	var $parent_date			= '';	// mention de date (txt) dans le bulletinage associé
	var $parent_date_date			= '';	// mention de date (date) dans le bulletinage associé
	var $parent_aff_date_date		= '';	// mention de date (date) dans le bulletinage associé au format correct pour affichage
	var $result				= '';	// affichage final
	var $level				= 1;	// niveau d'affichage
	var $isbd				= '';	// isbd de la notice en fonction du level défini
	var $responsabilites 			=	array("responsabilites" => array(),"auteurs" => array());  // les auteurs
	var $categories 			=	array();// les categories
	var $lien_explnum			= '';	// Lien de gestion des documents numériques associés
	var $bouton_explnum			= 0 ;	// bouton ou pas d'ajout de doc numérique
	var $p_perso;
	var $show_explnum=1;
	var $show_statut=0;
	var $childs=array(); //Filles de la notice
	var $print_mode=0;
	var $langues = array();
	var $languesorg = array();
	var $aff_statut = '' ; // carré de couleur pour signaler le statut de la notice
	var $show_opac_hidden_fields=true;
	var $drag=0;
	var $anti_loop="";
	var $no_link=false;
	var $serial_nb_bulletins=0;
	var $serial_nb_exemplaires=0;
	var $serial_nb_articles=0;
	// constructeur
	function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", 
	$bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true, $draggable=0,$ajax_mode=0 , $anti_loop='',$no_link=false) {
		// $id = id de la notice à afficher
		// $action_serial = URL à atteindre si la notice est une notice chapeau
		// $action_analysis = URL à atteindre si la notice est un dépouillement
		// note dans ces deux variable, '!!id!!' sera remplacé par l'id de cette notice
		// les deux liens s'excluent mutuellement, bien sur.  
		// $level :
		//	0 : juste le header (titre  / auteur principal avec le lien si applicable) 
		// $lien_suppr_cart = lien de suppression de la notice d'un caddie
		global $pmb_recherche_ajax_mode;
	  	if($pmb_recherche_ajax_mode){
			$this->ajax_mode=$ajax_mode;
		  	if($this->ajax_mode) {
				if (is_object($id)){
					$param['id']=$id->notice_id;
				} else {
					$param['id']=$id;
				}	
				$param['function_to_call']="serial_display";  	
			  	//if($level)$param['level']=$level;	//6
				if($action_serial)$param['action_serial']=$action_serial;  		
				if($action_analysis)$param['action_analysis']=$action_analysis;
				if($action_bulletin)$param['action_bulletin']=$action_bulletin;
//			  	if($lien_suppr_cart)$param['lien_suppr_cart']=$lien_suppr_cart;
//			  	if($lien_explnum)$param['lien_explnum']=$lien_explnum;	
				if($bouton_explnum)$param['bouton_explnum']=$bouton_explnum;  		
			  	if($print)$param['print']=$print;	
			  //	if($show_explnum)$param['show_explnum']=$show_explnum;	
			  	//if($show_statut)$param['show_statut']=$show_statut;
			  	//if($show_opac_hidden_fields)$param['show_opac_hidden_fields']=$show_opac_hidden_fields;
			  	//if($draggable)$param['draggable']=$draggable;//1

			  	$this->mono_display_cmd=serialize($param);
		  	}
	  	}
		$this->lien_explnum = $lien_explnum ;
		$this->bouton_explnum = $bouton_explnum ;
		$this->print_mode=$print;
		$this->show_explnum=$show_explnum;
		$this->show_statut=$show_statut;
		$this->anti_loop=$anti_loop;
		$this->no_link=$no_link;
		if(!$id) return; else {
			if (is_object($id)){
				$this->notice_id = $id->notice_id;
				$this->notice = $id;
			} else {
					$this->notice_id = $id;
					$this->serial_display_fetch_data();
			}
		}
				
		$this->show_opac_hidden_fields=$show_opac_hidden_fields;
		if(!$this->ajax_mode)$this->p_perso=new parametres_perso("notices");
		
		$this->responsabilites = get_notice_authors($this->notice_id) ;
	
		// mise à jour des catégories
		if(!$this->ajax_mode)$this->categories = get_notice_categories($this->notice_id) ;
	
		$this->level = $level;
		$this->lien_suppr_cart = $lien_suppr_cart;
	
		// si la notice est a2 (dépouillement), on récupère les données du bulletinage
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2) {
			$this->get_bul_info();
		}
	
		// mise à jour des liens
		if (SESSrights & CATALOGAGE_AUTH){
			$this->action_serial_org = $action_serial;
			$this->action_analysis = $action_analysis;
			$this->action_bulletin = $action_bulletin;
			if ($action_serial && $this->notice->niveau_biblio == 's' && $this->notice->niveau_hierar == '1')
				$this->action_serial = str_replace('!!id!!', $this->notice_id, $action_serial);
			if ($action_analysis && $this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == '2') {
				$this->action_analysis = str_replace('!!id!!', $this->notice_id, $this->action_analysis);
				$this->action_analysis = str_replace('!!bul_id!!', $this->bul_id, $this->action_analysis);
				}
			$this->lien_explnum = str_replace('!!serial_id!!', $this->notice_id, $this->lien_explnum);
			$this->lien_explnum = str_replace('!!analysis_id!!', $this->notice_id, $this->lien_explnum);
			$this->lien_explnum = str_replace('!!bul_id!!', $this->bul_id, $this->lien_explnum);
			$this->drag=$draggable;
		}else{
			$this->action_serial_org = "";
			$this->action_analysis = "";
			$this->action_bulletin = "";
			$this->action_serial = "";
			$this->lien_explnum = "";
			$this->drag="";
		}
		
		
		$this->do_header();
	
		if($level)
			$this->init_javascript();
		$this->isbd = 'ISBD';
	
		if(!$this->ajax_mode) {
			$this->childs=array();
			$requete="select num_notice as notice_id,relation_type from notices_relations,notices where linked_notice=".$this->notice_id." and num_notice=notice_id order by relation_type, rank,create_date";
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				while ($r=mysql_fetch_object($resultat)) {
					$this->childs[$r->relation_type][]=$r->notice_id;
				}
			} 
		}	
		
		switch($level) {
			case 0:
				// là, c'est le niveau 0 : juste le header
				//$this->do_header();
				$this->result = $this->header;
				break;
			default:
				// niveau 1 et plus : header + isbd à générer
				//$this->do_header(); 
				if(!$this->ajax_mode) $this->do_isbd();
				if(!$this->ajax_mode) $this->finalize();
				break;
			}
		return;
	
		}
	
	// récupération des info de bulletinage (si applicable)
	function get_bul_info() {
		global $dbh;
		global $msg ;
		
		// récupération des données du bulletin et de la notice apparentée
		$requete = "SELECT b.tit1,b.notice_id,b.code,a.*,c.*, date_format(date_date, '".$msg["format_date"]."') as aff_date_date "; 
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
			$this->code=$parent->code;
			$this->bul_id = $parent->bulletin_id;
			$this->parent_numero = $parent->bulletin_numero;
			$this->parent_date = $parent->mention_date;
			$this->parent_date_date = $parent->date_date;
			$this->parent_aff_date_date = $parent->aff_date_date;
			}
		}
	
	// finalisation du résultat (écriture de l'isbd)
	function finalize() {
		global $dbh;
		global $msg ;
		
		// Différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
			$this->result = str_replace('!!serial_type!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>", $this->result);
			} else { 
				$this->result = str_replace('!!serial_type!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>", $this->result);
				}
		$this->result = str_replace('!!ISBD!!', $this->isbd, $this->result);
		}
	
	// génération du template javascript
	function init_javascript() {
		global $cart_click;
		global $msg, $base_path,$pmb_recherche_ajax_mode, $art_to_show;
		$current=$_SESSION["CURRENT"];
		if ($current!==false) {
			$print_action = "&nbsp;<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&notice_id=!!notice_id!!&action_print=print_prepare','print',500,600,-2,-2,'scrollbars=yes,menubar=0'); w.focus(); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
		}	

		if(($art_to_show == $this->notice_id) && $art_to_show){
			$open_tag = "startOpen=\"Yes\"";
			$anchor = "<a name='anchor_$art_to_show'></a>";
		} else {
			$open_tag = "";
			$anchor = "";
		}
		
		if($pmb_recherche_ajax_mode && $this->ajax_mode){
			$javascript_template ="$anchor
		<div id=\"el!!id!!Parent\" class=\"notice-parent\">
    		<img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el!!id!!Img\" param='".rawurlencode($this->mono_display_cmd)."' title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase_ajax('el!!id!!', true,this.getAttribute('param')); return false;\" hspace=\"3\">
    		<span class=\"notice-heada\">!!heada!!</span>
		</div>
		<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\" $open_tag >
 		</div>";
	
		} else{
			if(SESSrights & CATALOGAGE_AUTH){
				$caddie="<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title=\"${msg[400]}\" $cart_click>";	
			}else{
				$caddie="";	
			}
				
			$javascript_template ="$anchor
			<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				<img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\" />
				<span class=\"notice-heada\">!!heada!!</span>
			</div>
			<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\" $open_tag >
				$caddie$print_action !!serial_type!! !!ISBD!!
 		</div>";
		}
		$this->result = str_replace('!!id!!', $this->notice_id.($this->anti_loop?"_p".implode("_",$this->anti_loop):""), $javascript_template);
		$this->result = str_replace('!!item!!', $this->notice_id, $this->result);
		$this->result = str_replace('!!unique!!', md5(microtime()), $this->result);
		$this->result = str_replace('!!heada!!', $this->lien_suppr_cart.$this->header, $this->result);
		$this->result = str_replace('!!notice_id!!', $this->notice_id, $this->result);
	}
	
	// génération de l'isbd
	function do_isbd() {
		global $dbh;
		global $fonction_auteur;
		global $langue_doc;
		global $msg;
		global $charset;
		global $thesaurus_mode_pmb, $thesaurus_categories_categ_in_line, $pmb_keyword_sep ;
		global $pmb_etat_collections_localise,$pmb_droits_explr_localises,$explr_visible_mod, $thesaurus_categories_affichage_ordre;
		global $categories_memo,$libelle_thesaurus_memo;
		global $categories_top,$use_opac_url_base,$thesaurus_categories_show_only_last;
		global $load_tablist_js;
		global $pmb_show_notice_id;
		global $base_path;
		global $sort_children;
		
		$this->isbd = $this->notice->tit1;
		
		// constitution de la mention de titre
		$tit3 = $this->notice->tit3;
		$tit4 = $this->notice->tit4;
		if($tit3) $this->isbd .= "&nbsp;= $tit3";
		if($tit4) $this->isbd .= "&nbsp;: $tit4";
	
		// constitution de la mention de responsabilité
		
		$mention_resp = array() ;
		
		// constitution de la mention de responsabilité
		//$this->responsabilites
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			if ($this->print_mode)
				$mention_resp_lib = $auteur->isbd_entry;
			else
				$mention_resp_lib = $auteur->isbd_entry_lien_gestion;
			if (!$this->print_mode) $mention_resp_lib .= $auteur->author_web_link ;
			if ($auteur_0["fonction"]) $mention_resp_lib .= ", ".$fonction_auteur[$auteur_0["fonction"]];
			$mention_resp[] = $mention_resp_lib ;
		}
		
		$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			if ($this->print_mode)
				$mention_resp_lib = $auteur->isbd_entry;
			else
				$mention_resp_lib = $auteur->isbd_entry_lien_gestion;
			if (!$this->print_mode) $mention_resp_lib .= $auteur->author_web_link ;
			if ($auteur_1["fonction"]) $mention_resp_lib .= ", ".$fonction_auteur[$auteur_1["fonction"]];
			$mention_resp[] = $mention_resp_lib ;
		}
		
		$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_2["id"]);
			if ($this->print_mode)
				$mention_resp_lib = $auteur->isbd_entry;
			else
				$mention_resp_lib = $auteur->isbd_entry_lien_gestion;
			if (!$this->print_mode) $mention_resp_lib .= $auteur->author_web_link ;
			if ($auteur_2["fonction"])
				$mention_resp_lib .= ", ".$fonction_auteur[$auteur_2["fonction"]];
			$mention_resp[] = $mention_resp_lib ;
		}
			
		$libelle_mention_resp = implode ("; ",$mention_resp) ;
		if($libelle_mention_resp)
			$this->isbd .= "&nbsp;/ ". $libelle_mention_resp ." " ;
	
		// zone de l'adresse (ne concerne que s1)
		if ($this->notice->niveau_biblio == 's' && $this->notice->niveau_hierar == 1) {
			if($this->notice->ed1_id) {
				$editeur = new editeur($this->notice->ed1_id);
				if ($this->print_mode)
					$editeurs .= $editeur->isbd_entry;
				else
					$editeurs .= $editeur->isbd_entry_lien_gestion; 
			}
			if($this->notice->ed2_id) {
				$editeur = new editeur($this->notice->ed2_id);
				if ($this->print_mode) $ed_isbd=$editeur->isbd_entry; else $ed_isbd=$editeur->isbd_entry_lien_gestion;
				if($editeurs)
					$editeurs .= '&nbsp;; '.$ed_isbd;
				else
					$editeurs .= $ed_isbd;
			}

			if($this->notice->year) 
				$editeurs ? $editeurs .= ', '.$this->notice->year : $editeurs = $this->notice->year;
			//else 
				//$editeurs ? $editeurs .= ', [s.d.]' : $editeurs = "[s.d.]";
			
			if($editeurs)
				$this->isbd .= ".&nbsp;-&nbsp;$editeurs";
				// code ici pour la gestion des éditeurs 
		}
		
		// zone de la collation (ne concerne que a2, mention de pagination)
		// pour les périodiques, on rebascule en zone de note
		// avec la mention du périodique parent		
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2) {
			
			$bulletin = $this->parent_title;
			if($this->parent_numero) {
				$bulletin .= ' '.$this->parent_numero;
			}
			// affichage de la mention de date utile : mention_date si existe, sinon date_date
			if ($this->parent_date)
				$date_affichee = " (".$this->parent_date.")";
			else if ($this->parent_date_date)
				$date_affichee .= " [".formatdate($this->parent_date_date)."]";
			else
				$date_affichee="" ;
			$bulletin .= $date_affichee;
	
			if($this->action_bulletin) {
				$this->action_bulletin = str_replace('!!id!!', $this->bul_id, $this->action_bulletin);
				$bulletin = "<a href=\"".$this->action_bulletin."\">".htmlentities($bulletin,ENT_QUOTES, $charset)."</a>";
			}
			$mention_parent = "in <b>$bulletin</b>";
		}
			
		if($mention_parent) {
			$this->isbd .= "<br />$mention_parent";
			$pagination = htmlentities($this->notice->npages,ENT_QUOTES, $charset);
			if($pagination)
				$this->isbd .= ".&nbsp;-&nbsp;$pagination"; 
		}

		//In
		//Recherche des notices parentes
		if (!$this->no_link) {
			$requete="select linked_notice, relation_type, rank, l.niveau_biblio as lnb, l.niveau_hierar as lnh from notices_relations, notices as l where num_notice=".$this->notice_id." and linked_notice=l.notice_id order by relation_type,rank";
			$result_linked=mysql_query($requete) or die(mysql_error());
			//Si il y en a, on prépare l'affichage
			if (mysql_num_rows($result_linked)) {
				global $relation_listup ;
				if (!$relation_listup) $relation_listup=new marc_list("relationtypeup");
			}
			$r_type=array();
			$ul_opened=false;
			$r_type_local="";
			//Pour toutes les notices liées
			
			while (($r_rel=mysql_fetch_object($result_linked))) {
				//Pour avoir le lien par défaut
				if (!$this->print_mode && (SESSrights & CATALOGAGE_AUTH)) $link_parent='./catalog.php?categ=isbd&id=!!id!!'; else $link_parent="";
				
				if ($r_rel->lnb=='s' && $r_rel->lnh=='1') {
					// c'est une notice chapeau
					global $link_serial,$link_analysis, $link_bulletin, $link_explnum_serial ;
					$link_serial_sub = "./catalog.php?categ=serials&sub=view&serial_id=".$r_rel->linked_notice;			
					// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true, $draggable=0 ) {
					$serial = new serial_display($r_rel->linked_notice, 0, $link_serial_sub, $link_analysis, $link_bulletin, "", "", 0, $this->print_mode, $this->show_explnum, $this->show_statut, $this->show_opac_hidden_fields, 1, true);
					$aff = $serial->header;				
				} 
				else if ($r_rel->lnb=='a' && $r_rel->lnh=='2') {
					// c'est un dépouillement de bulletin
					global $link_serial, $link_analysis, $link_bulletin, $link_explnum_serial ;
					if(!$link_analysis){
						$link_analysis="./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!";
					}
					$serial = new serial_display($r_rel->linked_notice, 0, $link_serial, $link_analysis, $link_bulletin, "", "", 0, $this->print_mode, $this->show_explnum, $this->show_statut, $this->show_opac_hidden_fields, 1, true);
					$aff = $serial->result;
				}
				else {
					if($link_parent && $r_rel->lnb=='b' && $r_rel->lnh=='2'){
						$requete="SELECT bulletin_id FROM bulletins WHERE num_notice='".$r_rel->linked_notice."'";
						$res=mysql_query($requete);
						if(mysql_num_rows($res)){
							$link_parent="./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".mysql_result($res,0,0);
						}
					}
					// dans les autres cas
					//function mono_display($id, $level=1, $action='', $expl=1, $expl_link='', $lien_suppr_cart="", $explnum_link='', $show_resa=0, $print=0, $show_explnum=1, $show_statut=0, $anti_loop='', $draggable=0, $no_link=false, $show_opac_hidden_fields=true,$ajax_mode=0) {
					$parent_notice=new mono_display($r_rel->linked_notice,0,$link_parent, 1, '', "", '', 0, $this->print_mode, $this->show_explnum, $this->show_statut, '', 1, true, $this->show_opac_hidden_fields, 0);
					$aff = $parent_notice->header ;
					$this->nb_expl+=$parent_notice->nb_expl;
				}
				
				//Présentation différente si il y en a un ou plusieurs
				if (mysql_num_rows($result_linked)==1) {
					$this->isbd.="<br /><b>".$relation_listup->table[$r_rel->relation_type]."</b> ".$aff."<br />";
				} else {
					if ($r_rel->relation_type!=$r_type_local) {
						$r_type_local=$r_rel->relation_type;
						if ($ul_opened) {
							$this->isbd.="</ul>"; 
							$this->isbd.="\n<b>".$relation_listup->table[$r_rel->relation_type]."</b>";
							$this->isbd.="\n<ul class='notice_rel'>\n";
							$ul_opened=true;
						} else { 
							$this->isbd.="\n<br />"; 
							$this->isbd.="\n<b>".$relation_listup->table[$r_rel->relation_type]."</b>";
							$this->isbd.="\n<ul class='notice_rel'>\n";
							$ul_opened=true; 
						}
					}
					$this->isbd.="\n<li>".$aff."</li>\n";
				}
			}
			if ($ul_opened) $this->isbd.="\n</ul>\n";
		}
		
		if($pmb_show_notice_id){
    	   	$prefixe = explode(",",$pmb_show_notice_id);
			$this->isbd .= "<br /><b>".$msg['notice_id_libelle']."&nbsp;</b>".($prefixe[1] ? $prefixe[1] : '').$this->notice_id."<br />";
		}
		// fin du niveau 1
		if($this->level == 1)
			return;
			
		// début du niveau 2
		// note générale
		if($this->notice->n_gen)
			$this->isbd .= "<br /><b>$msg[265]</b>:&nbsp;".nl2br(htmlentities($this->notice->n_gen,ENT_QUOTES, $charset));
		// note de contenu : non-applicable aux périodiques ??? Ha bon pourquoi ?
		if($this->notice->n_contenu)
			$this->isbd .= "<br /><b>$msg[266]</b>:&nbsp;".nl2br($this->notice->n_contenu);
		// résumé
		if($this->notice->n_resume)
			$this->isbd .= "<br /><b>$msg[267]</b>:&nbsp;".nl2br($this->notice->n_resume);
	
		// fin du niveau 2
		if($this->level == 2)
			return;
			
		// début du niveau 3
		// fin du niveau 3
		if($this->level == 3)
			return;
			
		// début du niveau 4
		// catégories
		$categ_repetables = array() ;	
		if(!count($categories_top)) {		
			$q = "select num_thesaurus,id_noeud from noeuds where num_parent in(select id_noeud from noeuds where autorite='TOP') ";
			$r = mysql_query($q, $dbh);
			while($res = mysql_fetch_object($r)) {
				$categories_top[]=$res->id_noeud;		
			}		
		}	
		$requete = "select * from (
			select libelle_thesaurus, c0.libelle_categorie as categ_libelle, n0.id_noeud , n0.num_parent, langue_defaut,id_thesaurus, if(c0.langue = '".$lang."',2, if(c0.langue= thesaurus.langue_defaut ,1,0)) as p, ordre_vedette, ordre_categorie 
			FROM noeuds as n0, categories as c0,thesaurus,notices_categories 
			where notices_categories.num_noeud=n0.id_noeud and n0.id_noeud = c0.num_noeud and n0.num_thesaurus=id_thesaurus and 
			notices_categories.notcateg_notice=".$this->notice_id."	order by id_thesaurus, n0.id_noeud, p desc
			) as list_categ group by id_noeud";
		if ($thesaurus_categories_affichage_ordre==1) $requete .= " order by ordre_vedette, ordre_categorie";
		
		$result_categ=@mysql_query($requete);
		if (mysql_num_rows($result_categ)) {
			while($res_categ = mysql_fetch_object($result_categ)) {
				$libelle_thesaurus=$res_categ->libelle_thesaurus;
				$categ_id=$res_categ->id_noeud 	;
				$libelle_categ=$res_categ->categ_libelle ;
				$num_parent=$res_categ->num_parent ;
				$langue_defaut=$res_categ->langue_defaut ;
				$categ_head=0;
				if(in_array($categ_id,$categories_top)) $categ_head=1;
				
				if ($thesaurus_categories_show_only_last || $categ_head) {			
					if ($use_opac_url_base) $url_base_lien_aut = $opac_url_base."index.php?&lvl=categ_see&id=" ;
					else $url_base_lien_aut="./autorites.php?categ=categories&sub=categ_form&id=";
					if ( (SESSrights & AUTORITES_AUTH || $use_opac_url_base) && (!$this->print_mode) ) $libelle_aff_complet = "<a href='".$url_base_lien_aut.$categ_id."' class='lien_gestion'>".$libelle_categ."</a>";
					else $libelle_aff_complet =$libelle_categ;
					if ($thesaurus_mode_pmb) {
						$categ_repetables[$libelle_thesaurus][] = $libelle_aff_complet;
					} else $categ_repetables['MONOTHESAURUS'][] = $libelle_aff_complet;						
					
				} else {
					if(!$categories_memo[$categ_id]) {
						$anti_recurse[$categ_id]=1;
						$path_table='';
						$requete = "select id_noeud as categ_id, num_noeud, num_parent as categ_parent, libelle_categorie as categ_libelle, num_renvoi_voir as categ_see, note_application as categ_comment, if(langue = '".$lang."',2, if(langue= '".$langue_defaut."' ,1,0)) as p
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
	
						if ($use_opac_url_base) $url_base_lien_aut = $opac_url_base."index.php?&lvl=categ_see&id=" ;
						else $url_base_lien_aut="./autorites.php?categ=categories&sub=categ_form&id=";
						if ((SESSrights & AUTORITES_AUTH || $use_opac_url_base) && (!$this->print_mode) ) $libelle_aff_complet = "<a href='".$url_base_lien_aut.$categ_id."' class='lien_gestion'>".$catalog_form."</a>";
						else $libelle_aff_complet =$catalog_form;
						if ($thesaurus_mode_pmb) {
							$categ_repetables[$libelle_thesaurus][] = $libelle_aff_complet;
						} else $categ_repetables['MONOTHESAURUS'][] = $libelle_aff_complet;
						
						$categories_memo[$categ_id]=$libelle_aff_complet;
						$libelle_thesaurus_memo[$categ_id]=$libelle_thesaurus;				
						
					} else {
						if ($thesaurus_mode_pmb) $categ_repetables[$libelle_thesaurus_memo[$categ_id]][] =$categories_memo[$categ_id];
						else $categ_repetables['MONOTHESAURUS'][] =$categories_memo[$categ_id] ;
					}					
				}
			}					
		}			
		
		while (list($nom_tesaurus, $val_lib)=each($categ_repetables)) {
			//c'est un tri par libellé qui est demandé
			if ($thesaurus_categories_affichage_ordre==0){
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
				
			if ($thesaurus_mode_pmb) {
				if (!$thesaurus_categories_categ_in_line) $categ_repetables_aff = "[".$nom_tesaurus."]".implode("<br />[".$nom_tesaurus."]",$val_lib) ;
				else $categ_repetables_aff = "<b>".$nom_tesaurus."</b><br />".implode(" $pmb_keyword_sep ",$val_lib) ;
			} else if (!$thesaurus_categories_categ_in_line) $categ_repetables_aff = implode("<br />",$val_lib) ;
			else $categ_repetables_aff = implode(" $pmb_keyword_sep ",$val_lib) ;
			
			if($categ_repetables_aff) $tmpcateg_aff .= "<br />$categ_repetables_aff";
		}
		if ($tmpcateg_aff) $this->isbd .= "<br />$tmpcateg_aff";
			
		// fin du niveau 4
		if($this->level == 4)
			return;
			
		// début du niveau 5
		// langues
		if(count($this->langues)) {
			$langues = "<b>${msg[537]}</b>&nbsp;: ".construit_liste_langues($this->langues);
			}
		if(count($this->languesorg)) {
			$langues .= " <b>${msg[711]}</b>&nbsp;: ".construit_liste_langues($this->languesorg);
			}
		if($langues)
			$this->isbd .= "<br />$langues";
			
		// indexation libre
		if($this->notice->index_l)
			$this->isbd .= "<br /><b>${msg[324]}</b>&nbsp;: ".htmlentities($this->notice->index_l,ENT_QUOTES, $charset);
		
		// indexation interne
		if($this->notice->indexint) {
			$indexint = new indexint($this->notice->indexint);
			if ($this->print_mode) $indexint_isbd=$indexint->display; else $indexint_isbd=$indexint->isbd_entry_lien_gestion;
			$this->isbd .= "<br /><b>${msg[indexint_catal_title]}</b>&nbsp;: ".$indexint_isbd;
		}
		
		//code (ISSN,...)
		if ($this->notice->code) $this->isbd .="<br /><b>${msg[165]}</b>&nbsp;: ".$this->notice->code;
			
		//Champs personalisés
		$perso_aff = "" ;
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				// ajout de && ($p['OPAC_SHOW']||$this->show_opac_hidden_fields) afin de masquer les champs masqué de l'POAC en diff de bannette.
				if ($p["AFF"] && ($p['OPAC_SHOW']||$this->show_opac_hidden_fields)) $perso_aff .="<br />".$p["TITRE"]." ".$p["AFF"];
			}
		}
		if ($perso_aff) $this->isbd.=$perso_aff ;
			
		//Notices liées
		if ((count($this->childs))&&(!$this->print_mode)&&(!$this->no_link)) {
			$link = './catalog.php?categ=isbd&id=!!id!!';
			$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
			$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!'; 
			global $relation_typedown;
			if (!$relation_typedown) $relation_typedown=new marc_list("relationtypedown");
			reset($this->childs);
			if(!$load_tablist_js) $aff_childs="<script type='text/javascript' src='./javascript/tablist.js'></script>\n";
			$aff_childs.="<br />";
			$load_tablist_js=1;
			$anti_loop=$this->anti_loop;
			$anti_loop[]=$this->notice_id;
			$n_childs=0;
			while ((list($rel_type,$child_notices)=each($this->childs))&&($n_childs<50)) {
				$aff_intermediaire="";
				for ($i=0; $i<count($child_notices); $i++) {
					$as=array_search($child_notices[$i],$anti_loop);
					if ($as===false) {
						global $pmb_notice_fille_format;
						if($pmb_notice_fille_format) $level_fille = 0;
						else $level_fille = 6;
						
						// il faut aller chercher le niveau biblio et niveau hierar de la notice liée
						$requete_nbnh="select l.niveau_biblio as lnb, l.niveau_hierar as lnh, rank from notices as l join notices_relations on num_notice=notice_id where notice_id='".$child_notices[$i]."' ";
						$r_rel=mysql_fetch_object(mysql_query($requete_nbnh));
						if($r_rel->rank != $i){
							$req = "update notices_relations set rank='$i' where num_notice='".$child_notices[$i]."' and relation_type='".$rel_type."' and linked_notice='".$anti_loop[count($serial->anti_loop)-1]."'";
							mysql_query($req,$dbh);	
						}
						if ($r_rel->lnb=='a' && $r_rel->lnh=='2') {
							// c'est un dépouillement de bulletin
							$serial = new serial_display($child_notices[$i], $level_fille, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum_serial, 0, 0, 1, 1 );
							
							if((count($serial->anti_loop) == 1) && $sort_children){
								//Drag pour trier les notices filles
								$id_elt =  $serial->notice_id.($serial->anti_loop?"_p".implode("_",$serial->anti_loop):"");
								$drag_fille = "<div id=\"drag_".$id_elt."\" handler=\"handle_".$id_elt."\" dragtype='daughter' draggable='yes' recepttype='daughter' recept='yes' 
									dragicon=\"$base_path/images/icone_drag_notice.png\" dragtext='".htmlentities($serial->tit1,ENT_QUOTES,$charset)."' callback_before=\"is_expandable\" 
									callback_after=\"\" downlight=\"noti_downlight\" highlight=\"noti_highlight\" fille='$child_notices[$i]' pere='".$anti_loop[count($serial->anti_loop)-1]."' order='$i' type_rel=\"$rel_type\">";
								$drag_fille .= "<span id=\"handle_".$id_elt."\" style=\"float:left; padding-right : 7px\"><img src=\"$base_path/images/sort.png\" style='width:12px; vertical-align:middle' /></span>";
								$display_fille = $serial->result;
							} else {
								$drag_fille ="";
								$display_fille = ($pmb_notice_fille_format ? "<li>".$serial->result."</li>" : $serial->result);
							}
							$aff = $drag_fille.$display_fille;
							if($drag_fille)
								$aff .= "</div>";		
						} elseif ($r_rel->lnb=='b' && $r_rel->lnh=='2') {
							// c'est une notice de bulletin, on n'affiche rien en aff de notice chapeau
							$aff = "";
						} elseif ($r_rel->lnb=='s' && $r_rel->lnh=='1') {
							// c'est une notice de pério
							global $link_serial, $link_analysis, $link_bulletin, $link_explnum_serial ;
							$link_serial_sub = "./catalog.php?categ=serials&sub=view&serial_id=".$child_notices[$i];				
							$serial = new serial_display($child_notices[$i], $level_fille, $link_serial_sub, $link_analysis, $link_bulletin, "", $link_explnum_serial, 0, $this->print_mode, 1, 1 ,1,0,0,$anti_loop);
							
							if((count($serial->anti_loop) == 1) && $sort_children){
								//Drag pour trier les notices filles
								$id_elt =  $serial->notice_id.($serial->anti_loop?"_p".implode("_",$serial->anti_loop):"");
								$drag_fille = "<div id=\"drag_".$id_elt."\" handler=\"handle_".$id_elt."\" dragtype='daughter' draggable='yes' recepttype='daughter' recept='yes' 
									dragicon=\"$base_path/images/icone_drag_notice.png\" dragtext='".htmlentities($serial->tit1,ENT_QUOTES,$charset)."' callback_before=\"is_expandable\" 
									callback_after=\"\" downlight=\"noti_downlight\" highlight=\"noti_highlight\" fille='$child_notices[$i]' pere='".$anti_loop[count($serial->anti_loop)-1]."' order='$i' type_rel=\"$rel_type\">";
								$drag_fille .= "<span id=\"handle_".$id_elt."\" style=\"float:left; padding-right : 7px\"><img src=\"$base_path/images/sort.png\" style='width:12px; vertical-align:middle' /></span>";
								$display_fille = $serial->result;
							} else {
								$drag_fille = "";
								$display_fille = ($pmb_notice_fille_format ? "<li>".$serial->result."</li>" : $serial->result);
							}	
							$aff = $drag_fille.$display_fille;
							if($drag_fille)
								$aff .= "</div>";	
						} else { 
							$display = new mono_display($child_notices[$i], $level_fille, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1,$anti_loop,$this->drag);
							
							if((count($display->anti_loop) == 1) && $sort_children) {
								//Drag pour trier les notices filles
								$id_elt =  $display->notice_id.($display->anti_loop?"_p".implode("_",$display->anti_loop):"");
								$drag_fille = "<div id=\"drag_".$id_elt."\" handler=\"handle_".$id_elt."\" dragtype='daughter' draggable='yes' recepttype='daughter' recept='yes' 
									dragicon=\"$base_path/images/icone_drag_notice.png\" dragtext='".htmlentities($display->tit1,ENT_QUOTES,$charset)."' callback_before=\"is_expandable\" 
									callback_after=\"\" downlight=\"noti_downlight\" highlight=\"noti_highlight\" fille='$child_notices[$i]' pere='".$anti_loop[count($display->anti_loop)-1]."' order='$i' type_rel=\"$rel_type\" >";
								$drag_fille .= "<span id=\"handle_".$id_elt."\" style=\"float:left; padding-right : 7px\"><img src=\"$base_path/images/sort.png\" style='width:12px; vertical-align:middle' /></span>";
								$display_fille = $display->result;
							} else {
								$drag_fille = "";
							    $display_fille = ($pmb_notice_fille_format ? "<li>".$display->result."</li>" : $display->result);
							}	
							$display->result=str_replace("<!-- !!bouton_modif!! -->"," ",$display->result);
							$aff = $drag_fille.$display_fille;
							$this->nb_expl+=$display->nb_expl;
							if($drag_fille)
								$aff .= "</div>";
						}
						$aff_intermediaire.=$aff;
					}
					$n_childs++;
				}
				if ($aff_intermediaire) {
					$aff_childs.="<b>".$relation_typedown->table[$rel_type]."</b>";
					$aff_childs.="<blockquote>";
					if($pmb_notice_fille_format) 
						$aff_childs.= "<ul class='notice_rel'>";
					$aff_childs.= $aff_intermediaire ;
					if($pmb_notice_fille_format) 
						$aff_childs.= "</ul>";
					$aff_childs.="</blockquote>";
				}
			}
			$this->isbd.=$aff_childs;
		}
		$this->do_image($this->isbd);
		
		//Documents numériques
		if ($this->show_explnum) {
			$explnum = show_explnum_per_notice($this->notice_id, 0, $this->lien_explnum);
			if ($explnum) $this->isbd .= "<br /><b>$msg[explnum_docs_associes]</b><br />".$explnum ;
			if ($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == '2' && (SESSrights & CATALOGAGE_AUTH) && $this->bouton_explnum) $this->isbd .= "<br /><input type='button' class='bouton' value=' $msg[explnum_ajouter_doc] ' onClick=\"document.location='./catalog.php?categ=serials&analysis_id=$this->notice_id&sub=analysis&action=explnum_form&bul_id=$this->bul_id'\">" ;
		}
		
		// fin du niveau 5
		if($this->level == 5)
			return;
			
		// début du niveau 6		
		if($this->notice->niveau_biblio=="s") {	
			// Si notice-mère alors on compte le nombre de numéros (bulletins)		
			$this->isbd.=$this->get_etat_periodique();
			$this->isbd.=$this->print_etat_periodique();
			//état des collections		
			$collstate = new collstate(0,$this->notice_id);
			//$this->isbd.= $collstate->get_callstate_isbd();
			if($pmb_etat_collections_localise)
				$collstate->get_display_list("",0,0,0,1,0);
			else 	
				$collstate->get_display_list("",0,0,0,0,0);	
			if($collstate->nbr) {
				$this->isbd .= "<br /><b>".$msg["abts_onglet_collstate"]."</b><br />";
				$this->isbd.=$collstate->liste;
			}
		}
		// fin du niveau 6
		return;
		
		
	}
	
/**
 * Creation de l'image vignette associée
 *
 * @param  $entree
 */
function do_image(&$entree) {
	global $pmb_book_pics_show ;
	global $pmb_book_pics_url ;
	// pour url OPAC en diff DSI
	global $prefix_url_image ;	
	global $depliable ;
	
	if ($this->notice->code || $this->notice->thumbnail_url) {
		if ($pmb_book_pics_show=='1' && ($pmb_book_pics_url || $this->notice->thumbnail_url)) {
			$code_chiffre = pmb_preg_replace('/-|\.| /', '', $this->notice->code);
			$url_image = $pmb_book_pics_url ;
			$url_image = $prefix_url_image."getimage.php?url_image=".urlencode($url_image)."&noticecode=!!noticecode!!&vigurl=".urlencode($this->notice->thumbnail_url) ;
			if ($depliable) $image = "<img id='PMBimagecover".$this->notice_id."' src='".$prefix_url_image."images/vide.png' align='right' hspace='4' vspace='2' isbn='".$code_chiffre."' url_image='".$url_image."' vigurl=\"".$this->notice->thumbnail_url."\">";
			else {
				if ($this->notice->thumbnail_url) $url_image_ok=$this->notice->thumbnail_url;
				else $url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
				$image = "<img src='".$url_image_ok."' align='right' hspace='4' vspace='2'>";
			}
		} else $image="" ;
		if ($image) {
			$entree = "<table width='100%'><tr><td valign=top>$entree</td><td valign=top align=right>$image</td></tr></table>" ;
		} else {
			$entree = "<table width='100%'><tr><td valign=top>$entree</td></tr></table>" ;
		}
			
	} else {
		$entree = "<table width='100%'><tr><td valign=top>$entree</td></tr></table>" ;
	}
}

	function get_etat_periodique() {
		global $dbh;
		$bulletins=0;
		$nb_expl=0;
		$nb_notices=0;
		if($this->notice->niveau_biblio=="s") {
			$requete = "SELECT * FROM bulletins WHERE bulletin_notice=".$this->notice_id;
			$Query = mysql_query($requete, $dbh);
			$bulletins=mysql_num_rows($Query);
			while (($row = mysql_fetch_array($Query))) {
				$requete2 = "SELECT count( * )  AS nb_notices FROM  analysis WHERE analysis_bulletin =".$row['bulletin_id'];
				$Query2 = mysql_query($requete2, $dbh);
				$analysis_array=mysql_fetch_array($Query2);
				$nb_notices+=$analysis_array['nb_notices'];
				$requete3 = "SELECT count( expL_id )  AS nb_expl FROM  exemplaires WHERE expl_bulletin =".$row['bulletin_id'];
				$Query3 = mysql_query($requete3, $dbh);
				$expl_array=mysql_fetch_array($Query3);
				$nb_expl+=$expl_array['nb_expl'];			
			};
				
			$this->serial_nb_bulletins=$bulletins;
			$this->serial_nb_exemplaires=$nb_expl;
			$this->serial_nb_articles=$nb_notices;
		}	
	}	
			
	function print_etat_periodique() {
		global $msg;
		if($this->notice->niveau_biblio=="s") {
			// Cas général : au moins un bulletin
			if ($this->serial_nb_bulletins > 0)
				{$affichage .="<br />\n
				<b>".$msg["serial_bulletinage_etat"]."</b>
				<table border='0' class='expl-list'>
				<tr><td><strong>".$this->serial_nb_bulletins."</strong> ".$msg["serial_nb_bulletin"]."
				<strong>".$this->serial_nb_exemplaires."</strong> ".$msg["bulletin_nb_ex"]."	
				<strong>".$this->serial_nb_articles."</strong> ".$msg["serial_nb_articles"]."	
				</td>
				</tr></table>";
									
			} else { // 0 bulletin
				$affichage .="<br /><br />\n
				<b>".$msg["serial_bulletinage_etat"]."</b>
				<table border='0' class='expl-list'>
				<tr><td><strong>$bulletins</strong>
				".$msg["serial_nb_bulletin"]." : <strong>";
				$affichage .=$msg["bull_no_expl"];
				$affichage .="</strong></td>
				</tr></table>";
			}
		}	
		return $affichage;
	}	
		
	// génération du header
	function do_header() {
		global $dbh, $base_path;
		global $charset;
		
		if ($this->notice->statut) {
			$rqt_st = "SELECT class_html , gestion_libelle FROM notice_statut WHERE id_notice_statut='".$this->notice->statut."' ";
			$res_st = mysql_query($rqt_st, $dbh);
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
		$this->aff_statut = $statut ; 
		
		$this->header = htmlentities($this->notice->tit1,ENT_QUOTES, $charset);
		$this->memo_titre=$this->notice->tit1;
		$this->memo_complement_titre=$this->notice->tit4;
		$this->memo_titre_parallele=$this->notice->tit3;
		$aut1_libelle = array() ;
		//$this->responsabilites
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			if ($auteur->isbd_entry)
				$this->header .= ' / '. $auteur->isbd_entry;
		} else {
			$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
			for ($i = 0 ; $i < count($as) ; $i++) {
				$indice = $as[$i] ;
				$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
				$auteur = new auteur($auteur_1["id"]);
				$aut1_libelle[]= $auteur->isbd_entry;
			}
			$auteurs_liste = implode ("; ",$aut1_libelle) ;
			if ($auteurs_liste)
				$this->header .= ' / '. $auteurs_liste ;
		}
		if (!$this->print_mode) {
			if($this->notice->niveau_biblio == 's' && $this->notice->niveau_hierar == 1) {
				if($this->action_serial)
					$this->header = "<a href=\"".$this->action_serial."\">".$this->header.'</a>';			
			}	
			if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2) {
				if($this->action_analysis)
					$this->header= "<a href=\"".$this->action_analysis."\">".$this->header.'</a>';
				if ($this->level!=2) 
					$this->header=$this->header." <i>in ".$this->parent_title." (".$this->parent_numero." ".($this->parent_date?$this->parent_date:$this->parent_aff_date_date).")</i> ";
			}
		}
		global $use_opac_url_base, $opac_url_base, $use_dsi_diff_mode ;
		if($this->notice->lien) {
			// ajout du lien pour les ressources électroniques			
			if (!$this->print_mode || $use_dsi_diff_mode){
				$this->header .= "<a href=\"".$this->notice->lien."\" target=\"__LINK__\">";
				if (!$use_opac_url_base) $this->header .= "<img src=\"./images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
				else $this->header .= "<img src=\"".$opac_url_base."images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
				$this->header .= " alt=\"";
				$this->header .= $this->notice->eformat;
				$this->header .= "\" title=\"";
				$this->header .= $this->notice->eformat;				
				$this->header .= "\">";
				$this->header .= "</a>";		
			}			
			else {
				$this->header .= '<br />';
				$this->header .= '<font size="-1">'.$this->notice->lien.'</font>';
			}				
		}
		if (!$this->print_mode) {
			$sql_explnum = "SELECT explnum_id, explnum_nom FROM explnum WHERE explnum_notice = ".$this->notice_id;
			$explnums = mysql_query($sql_explnum);
			$explnumscount = mysql_num_rows($explnums);
			if ($explnumscount == 1) {
				$explnumrow = mysql_fetch_object($explnums);
				if (!$use_opac_url_base) $this->header .= "<a href=\"./doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">";
				else $this->header .= "<a href=\"".$opac_url_base."doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">";
				if (!$use_opac_url_base) $this->header .= "<img src=\"./images/globe_orange.png\" border=\"0\" align=\"middle\" hspace=\"3\"";
				else $this->header .= "<img src=\"".$opac_url_base."images/globe_orange.png\" border=\"0\" align=\"middle\" hspace=\"3\"";
				$this->header .= " alt=\"";
				$this->header .= htmlentities($explnumrow->explnum_nom);
				$this->header .= "\" title=\"";
				$this->header .= htmlentities($explnumrow->explnum_nom);
				$this->header .= "\">";
				$this->header .='</a>';
			}
			else if ($explnumscount > 1) {
				if (!$use_opac_url_base) $this->header .= "<img src=\"./images/globe_rouge.png\" border=\"0\" align=\"middle\" hspace=\"3\">";
				else $this->header .= "<img src=\"".$opac_url_base."images/globe_rouge.png\" border=\"0\" align=\"middle\" hspace=\"3\">";
			}
			
			if ($this->drag) $this->header.="<span onMouseOver='if(init_drag) init_drag();' id=\"NOTI_drag_".$this->notice_id."\" dragicon=\"$base_path/images/icone_drag_notice.png\" dragtext=\"".htmlentities($this->notice->tit1,ENT_QUOTES, $charset)."\" draggable=\"yes\" dragtype=\"notice\" callback_before=\"show_carts\" callback_after=\"\" style=\"padding-left:7px\"><img src=\"".$base_path."/images/notice_drag.png\"/></span>";
			if ($this->show_statut) $this->header = $this->aff_statut.$this->header ;
		}	
	}
	  
	// récupération des valeurs en table
	function serial_display_fetch_data() {
		global $dbh;
		$requete = "SELECT * FROM notices WHERE notice_id=".$this->notice_id.' LIMIT 1';
		$myQuery = mysql_query($requete, $dbh);
		if (mysql_num_rows($myQuery))
			$this->notice = mysql_fetch_object($myQuery);
		return mysql_num_rows($myQuery);
		}
		
	} // fin classe serial_display
	
	// -------------------------------------------------------------------
	//   classe bulletinage_display : utilisée pour le prêt de documents
	// -------------------------------------------------------------------
	class bulletinage_display {
	var $bul_id = 0;		// id du bulletinage à afficher
	var $display = '';		// texte à afficher
	var $parent_title = '';		// titre général de la revue à laquelle fait référence ce bulletinage
	var $bulletin_titre = '';	// titre de ce bulletin
	var $numerotation = '';		// mention de numérotation sur la revue
	var $periode	  = '';		// mention de date de la revue (txt)
	var $date_date	  = '';		// mention de date de la revue (date)
	var $header	  = '';		// pour affichage réduit
	 
	// constructeur
	function bulletinage_display($id=0) {
		if(!$id) {
			$this->display = "Error : bul_id is null";
			return $this->bul_id;
		}
	
		$this->bul_id = $id;
		
		$this->fetch_bulletinage_data();	
		$this->make_display();
	
		return $this->bul_id;	
	}
	
	// fabrication de la mention à afficher
	function make_display() {
		if ($this->parent_title) {
			$this->display = $this->parent_title;
			} else {
				$this->display = "error: unknown record";
				return;
				}
	
		if((!$this->numerotation && !$this->periode && !$this->bulletin_titre && !$this->date_date) || !$this->bul_id) {
			$this->display .= " error : missing information"; 
			}
				
		if($this->numerotation)
			$this->display .= '. '.$this->numerotation;
		
		$this->header = $this->display;
		
		// affichage de la mention de date utile : mention_date si existe, sinon date_date
		if ($this->periode)
			$date_affichee = " (".$this->periode.") ";
			else $date_affichee .= " [".$this->aff_date_date."]";
		$this->display .= $date_affichee;
		
		if ($date_affichee) $this->header .= $date_affichee ;
		}

	// récupération des infos bulletinage en base
	function fetch_bulletinage_data() {
		global $dbh;
		global $msg ;
		
		$requete = "SELECT bulletins.*, notices.tit1, date_format(date_date, '".$msg["format_date"]."') as aff_date_date FROM bulletins, notices ";
		$requete .= " WHERE bulletins.bulletin_id=".$this->bul_id;
		$requete .= " AND notices.notice_id=bulletins.bulletin_notice";
		$requete .= " AND notices.niveau_biblio='s' AND notices.niveau_hierar='1' LIMIT 1";		
	
		$myQuery = mysql_query($requete, $dbh);
		if(mysql_num_rows($myQuery)) {
			$result = mysql_fetch_object($myQuery);
			$this->parent_title = $result->tit1;
			$this->bulletin_titre = $result->bulletin_titre;
			$this->numerotation = $result->bulletin_numero;
			$this->periode = $result->mention_date;
			$this->date_date = $result->date_date;
			$this->aff_date_date = $result->aff_date_date;
			$this->bul_id = $result->bulletin_id;
		}
		
		return;
	}
} // class serial_display

