<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mono_display_unimarc.class.php,v 1.24 2009-11-18 15:18:08 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$class_path/marc_table.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/indexint.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/category.class.php");
require_once($class_path."/parametres_perso.class.php");
require_once($class_path."/emprunteur.class.php");
require_once($include_path."/notice_authors.inc.php");
require_once($include_path."/notice_categories.inc.php");
require_once($include_path."/explnum.inc.php");
require_once($include_path."/isbn.inc.php");
require_once($include_path."/resa_func.inc.php");


if (!sizeof($tdoc)) $tdoc = new marc_list('doctype');
if (!count($fonction_auteur)) {
	$fonction_auteur = new marc_list('function');
	$fonction_auteur = $fonction_auteur->table;
	}
if (!count($langue_doc)) {
	$langue_doc = new marc_list('lang');
	$langue_doc = $langue_doc->table;
	}
// propriï¿½tï¿½s pour le selecteur de panier 
$selector_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";
$cart_click = "onClick=\"openPopUp('./cart.php?object_type=NOTI&item=!!id!!&unq=!!unique!!', 'cart', 600, 700, -2, -2, '$selector_prop')\"";


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

// dï¿½finition de la classe d'affichage des monographies en liste
class mono_display_unimarc {
	var $notice_id		= 0;	// id de la notice ï¿½ afficher
	var $isbn		= 0;	// isbn ou code EAN de la notice ï¿½ afficher
  	var $notice;			// objet notice (tel que fetchï¿½ dans la table 'notices'
	var $langues = array();
	var $languesorg = array();
  	var $action		= '';	// URL ï¿½ associer au header
	var $header		= '';	// chaine accueillant le chapeau de notice (peut-ï¿½tre cliquable)
	var $tit_serie		= '';	// titre de sï¿½rie si applicable
	var $tit1		= '';	// valeur du titre 1
	var $result		= '';	// affichage final
	var $level		= 1;	// niveau d'affichage
	var $isbd		= '';	// isbd de la notice en fonction du level dï¿½fini
	var $expl		= 0;	// flag indiquant si on affiche les infos d'exemplaire
	var $nb_expl	= 0;	//nombre d'exemplaires
	var $link_expl		= '';	// lien associï¿½ ï¿½ un exemplaire
	var $responsabilites =	array("responsabilites" => array(),"auteurs" => array());  // les auteurs
	var $auteurs_principaux;
	var $auteurs_tous;
	var $categories_toutes;
	var $collections;
	var $publishers;
	var $print_mode=0;
	var $show_explnum=1;
	var $no_link;
	var $entrepots_localisations=array();
	var $docnums;
	
// constructeur------------------------------------------------------------
function mono_display_unimarc($id, $level=1, $expl=1, $print=0, $show_explnum=1, $no_link=false, $entrepots_localisations=array()) {
  	// $id = id de la notice ï¿½ afficher
  	// $action	 = URL associï¿½e au header
	// $level :
	//		0 : juste le header (titre  / auteur principal avec le lien si applicable) 
	// 			suppression des niveaux entre 1 et 6, seul reste level
	//		1 : ISBD seul, pas de note, bouton modif, expl, explnum et rï¿½sas
	// 		6 : cas gï¿½nï¿½ral dï¿½taillï¿½ avec notes, categ, langues, indexation... + boutons
	// $expl -> affiche ou non les exemplaires associï¿½s
  	
  	$this->notice_id = $id;
	$this->mono_display_fetch_data();		
	$this->fetch_auteurs();
	$this->level=$level;
	$this->expl = $expl;
	$this->entrepots_localisations = $entrepots_localisations;

	// mise ï¿½ jour des catï¿½gories
	$this->categories = get_notice_categories($this->notice_id) ;
				
	$this->do_header();

	switch($level) {
		case 0:
			// lï¿½, c'est le niveau 0 : juste le header
			$this->result = $this->header;
			break;
		default:
			// niveau 1 et plus : header + isbd ï¿½ gï¿½nï¿½rer
			$this->init_javascript();
			$this->do_isbd();
			$this->finalize();
			break;
		}	
	return;

}

function fetch_auteurs() {
	global $fonction_auteur;
	global $dbh ;

	$this->responsabilites  = array() ;
	$auteurs = array() ;
	
	$res["responsabilites"] = array() ;
	$res["auteurs"] = array() ;
	
	$requete = "SELECT source_id FROM external_count WHERE rid=".addslashes($this->notice_id);
	$myQuery = mysql_query($requete);
	$source_id = mysql_result($myQuery, 0, 0);	
	
	$rqt = "select recid,ufield,field_order,usubfield,subfield_order,value from entrepot_source_$source_id where recid='".addslashes($this->notice_id)."' and ufield like '7%' group by ufield,usubfield,field_order,subfield_order,value order by recid,field_order,subfield_order";
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
	// on ne prend que le auteur_titre = "Prï¿½nom NOM"
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

// rï¿½cupï¿½ration des categories ------------------------------------------------------------------
function fetch_categories() {
	global $pmb_keyword_sep;

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

// finalisation du rï¿½sultat (ï¿½criture de l'isbd)
function finalize() {
	$this->result = str_replace('!!ISBD!!', $this->isbd, $this->result);
	}

// gï¿½nï¿½ration du template javascript---------------------------------------
function init_javascript() {
	global $msg, $notice_id;
	
	if (isset($notice_id))
		$notice_id_info = "&notice_id=".$notice_id;
	else
		$notice_id_info = "";
	
	// propriï¿½tï¿½s pour le selecteur de panier 
	//$selector_prop = "toolbar=no, dependent=yes, width=500, height=400, resizable=yes, scrollbars=yes";
	$cart_click = "onClick=\"document.search_form.action='catalog.php?categ=search&mode=7&sub=integre".$notice_id_info."&item=!!id!!'; document.search_form.submit()\"";
	
	$javascript_template ="
		<div id=\"el!!id!!Parent\" class=\"notice-parent\">
    		<img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\">
    		<span class=\"notice-heada\">!!heada!!</span>
    		<br />
		</div>
		<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">
        <img src='./images/sauv.gif' align='middle' alt='basket' title=\"".$msg["connecteurs_integre"]."\" alt=\"".$msg["connecteurs_integre"]."\" $cart_click>
			 !!ISBD!!
 		</div>";
 		
	$this->result = str_replace('!!id!!', $this->notice_id.($this->anti_loop?"_p".$this->anti_loop[count($this->anti_loop)-1]:""), $javascript_template);
	$this->result = str_replace('!!heada!!', $this->lien_suppr_cart.$this->header, $this->result);
	}

// génération de l'isbd----------------------------------------------------
function do_isbd() {
	global $dbh;
	global $langue_doc;
	global $msg;
	global $tdoc;
	global $fonction_auteur;
	global $charset;
	global $thesaurus_mode_pmb, $thesaurus_categories_categ_in_line, $pmb_keyword_sep, $thesaurus_categories_affichage_ordre;
	global $pmb_show_notice_id;
	
	
	// constitution de la mention de titre
	if($this->tit_serie) {
		$this->isbd = $this->tit_serie; 
		if($this->notice->tnvol)
			$this->isbd .= ',&nbsp;'.$this->notice->tnvol;
	}
	$this->isbd ? $this->isbd .= '.&nbsp;'.$this->tit1 : $this->isbd = $this->tit1;

	$this->isbd .= ' ['.$tdoc->table[$this->notice->typdoc].']';
	$tit2 = $this->notice->tit2;
	$tit3 = $this->notice->tit3;
	$tit4 = $this->notice->tit4;
	if($tit3) $this->isbd .= "&nbsp;= $tit3";
	if($tit4) $this->isbd .= "&nbsp;: $tit4";
	if($tit2) $this->isbd .= "&nbsp;; $tit2";
	
	$mention_resp = array() ;
	
	// constitution de la mention de responsabilitï¿½
	//$this->responsabilites
	$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
	if ($as!== FALSE && $as!== NULL) {
		$auteur_0 = $this->responsabilites["auteurs"][$as] ;
		$mention_resp_lib=$auteur_0["auteur_titre"];
		if ($auteur_0["fonction"]) $mention_resp_lib .= ", ".$fonction_auteur[$auteur_0["fonction"]];
		$mention_resp[] = $mention_resp_lib ;
	}
	
	$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
	for ($i = 0 ; $i < count($as) ; $i++) {
		$indice = $as[$i] ;
		$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
		$mention_resp_lib=$auteur_1["auteur_titre"];
		if ($auteur_1["fonction"]) $mention_resp_lib .= ", ".$fonction_auteur[$auteur_1["fonction"]];
		$mention_resp[] = $mention_resp_lib ;
	}
	
	$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
	for ($i = 0 ; $i < count($as) ; $i++) {
		$indice = $as[$i] ;
		$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
		$mention_resp_lib=$auteur_2["auteur_titre"];
		if ($auteur_2["fonction"]) $mention_resp_lib .= ", ".$fonction_auteur[$auteur_2["fonction"]];
		$mention_resp[] = $mention_resp_lib ;
	}
		
	$libelle_mention_resp = implode ("; ",$mention_resp) ;
	if($libelle_mention_resp) $this->isbd .= "&nbsp;/ $libelle_mention_resp" ;

	// mention d'ï¿½dition
	if($this->notice->mention_edition) $this->isbd .= ".&nbsp;-&nbsp;".$this->notice->mention_edition;
	
	// zone de l'adresse
	// on rï¿½cupï¿½re la collection au passage, si besoin est
	if ($this->collections) {
		$collections = $this->collections[0]["name"];
	}
	$editeurs=array();
	for ($i=0; $i<count($this->publishers); $i++) {
		$editeurs[]=$this->publishers[$i]["name"].($this->publishers[$i]["city"]?" (".$this->publishers[$i]["city"].")":"");
	}
	$editeurs=implode("&nbsp;; ",$editeurs);
	
	if($this->notice->year) 
		$editeurs ? $editeurs .= ', '.$this->notice->year : $editeurs = $this->notice->year;
	else 
		$editeurs ? $editeurs .= ', [s.d.]' : $editeurs = "[s.d.]";

	$this->isbd .= ".&nbsp;-&nbsp;$editeurs";
	
	// zone de la collation (ne concerne que a2)
	if($this->notice->npages)
		$collation = $this->notice->npages;
	if($this->notice->ill)
		$collation .= ': '.$this->notice->ill;
	if($this->notice->size)
		$collation .= '; '.$this->notice->size;
	if($this->notice->accomp)
		$collation .= '+ '.$this->notice->accomp;
		
	if($collation)
		$this->isbd .= ".&nbsp;-&nbsp;$collation";
	
	
	if($collections) {
		if($this->notice->nocoll)
			$collections .= '; '.$this->notice->nocoll;
		$this->isbd .= ".&nbsp;-&nbsp;($collections)".' ';
	}

	$this->isbd .= '.';
		
	// note gï¿½nï¿½rale
	if($this->notice->n_gen)
 		$zoneNote = nl2br(htmlentities($this->notice->n_gen,ENT_QUOTES, $charset)).' ';
		
	// ISBN ou NO. commercial
	if($this->notice->code) {
		if(isISBN($this->notice->code)) {
			if ($zoneNote) { $zoneNote .= '.&nbsp;-&nbsp;ISBN '; } else { $zoneNote = 'ISBN ';}
			} else {
				if($zoneNote) $zoneNote .= '.&nbsp;-&nbsp;';
				}
		$zoneNote .= $this->notice->code;
		}
	if($this->notice->prix) {
		if($this->notice->code) {$zoneNote .= '&nbsp;: '.$this->notice->prix;}
			else { 
				if ($zoneNote) 	{ $zoneNote .= '&nbsp; '.$this->notice->prix;}
					else	{ $zoneNote = $this->notice->prix;}
				}
		}

	if($zoneNote)
		$this->isbd .= "<br /><br />$zoneNote.";
	
	if($pmb_show_notice_id){
       	$prefixe = explode(",",$pmb_show_notice_id);
		$this->isbd .= "<br /><b>".$msg['notice_id_libelle']."&nbsp;</b>".($prefixe[1] ? $prefixe[1] : '').$this->notice_id."<br />";
	}
	// niveau 1
	if($this->level == 1) {
		$this->isbd .= "<!-- !!bouton_modif!! -->";
		if ($this->expl) {
			$this->isbd .= "<br /><b>${msg[285]}</b>";
			$this->isbd .= $this->show_expl_per_notice($this->notice->notice_id, $this->link_expl);
			//if ($this->show_explnum) {
			//	$explnum_assoc = show_explnum_per_notice($this->notice->notice_id, 0,$this->link_explnum);
			//	if ($explnum_assoc) $this->isbd .= "<b>$msg[explnum_docs_associes]</b>".$explnum_assoc;
			//	}
			}
		$this->do_image($this->isbd) ;
		return;
	}			

	// rï¿½sumï¿½
	if($this->notice->n_resume)
 		// $this->isbd .= "<br /><b>${msg[267]}</b>&nbsp;: ".nl2br(htmlentities($this->notice->n_resume,ENT_QUOTES, $charset));
 		$this->isbd .= "<br /><b>${msg[267]}</b>&nbsp;: ".nl2br($this->notice->n_resume);

	// note de contenu
	if($this->notice->n_contenu) 
 		// $this->isbd .= "<br /><b>${msg[266]}</b>&nbsp;: ".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset));
		$this->isbd .= "<br /><b>${msg[266]}</b>&nbsp;: ".nl2br($this->notice->n_contenu);

	// catï¿½gories
	if ($this->categories_toutes) $this->isbd .= "<br />".$this->categories_toutes;
	
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
		$this->isbd .= "<br /><b>${msg[324]}</b>&nbsp;: ".$this->notice->index_l;
	
	// indexation interne
	if($this->notice->indexint_name) {
		$this->isbd .= "<br /><b>${msg[indexint_catal_title]}</b>&nbsp;: ".$this->notice->indexint_name;
	}
	
	if ($this->docnums) {
		$this->isbd .= "<br /><br />";
		$this->isbd .= "<b>".$msg["entrepot_notice_docnum"]."</b>";
		$this->isbd .= "<ul>";
		foreach($this->docnums as $docnum) {
			if (!$docnum["a"])
				continue;
			$this->isbd .= "<li>";
			if ($docnum["b"])
				$this->isbd .= $docnum["b"].": ";
			$this->isbd .= "<i><a href=\"".htmlentities($docnum["a"])."\">".$docnum["a"]."</a></i>";			
			$this->isbd .= "</li>";
		}		
		$this->isbd .= "</ul>";
	}
	
	$this->do_image($this->isbd) ;
	if($this->expl) {
		$expl_aff = $this->show_expl_per_notice();
		if ($expl_aff) {
			$this->isbd .= "<br /><br /><b>${msg[285]}</b>";
			$this->isbd .= $expl_aff;
		} 
	}
	$this->isbd .= "<!-- !!bouton_modif!! -->";
	//if ($this->show_explnum) {
	//	$explnum_assoc = show_explnum_per_notice($this->notice->notice_id, 0, $this->link_explnum);
	//	if ($explnum_assoc) $this->isbd .= "<b>$msg[explnum_docs_associes]</b>".$explnum_assoc;
	//}
	return;
}	

// gï¿½nï¿½ration du header----------------------------------------------------
function do_header() {
	
	global $dbh;
	global $charset;
	global $pmb_notice_reduit_format;
	global $base_path;
	global $msg;
	
	$aut1_libelle = array() ;
	
	// rï¿½cupï¿½ration du titre de sï¿½rie
	if($this->notice->serie_name) {	
		$this->tit_serie = $this->notice->serie_name;
		$this->header = $this->tit_serie;
		if($this->notice->tnvol)
			$this->header .= ',&nbsp;'.$this->notice->tnvol;
		}
	
	$this->tit1 = $this->notice->tit1;		
	$this->header ? $this->header .= '.&nbsp;'.$this->tit1 : $this->header = $this->tit1;
	
	if ($this->source_name) {
		$this->header=$this->source_name." : ".$this->header;
	}
	
	//$this->responsabilites
	$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
	if ($as!== FALSE && $as!== NULL) {
		$auteur_0 = $this->responsabilites["auteurs"][$as] ;
		//$auteur = new auteur($auteur_0["id"]);
		if ($auteur_0["auteur_isbd"]) $this->header .= ' / '. $auteur_0["auteur_titre"];
	} else {
		$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$aut1_libelle[]= $auteur_1["auteur_titre"];
		}
		$auteurs_liste = implode ("; ",$aut1_libelle) ;
		if ($auteurs_liste) $this->header .= ' / '. $auteurs_liste ;
	}
	
	switch ($pmb_notice_reduit_format) {
		case "1":
			if ($this->notice->year != '') $this->header.=' ('.htmlentities($this->notice->year, ENT_QUOTES, $charset).')';
			break;
		case "2":
			if ($this->notice->year != '') $this->header.=' ('.htmlentities($this->notice->year, ENT_QUOTES, $charset).')';
			if ($this->notice->code != '') $this->header.=' / '.htmlentities($this->notice->code, ENT_QUOTES, $charset);
			break;
		default : 
			break;
	}
	if ($this->drag)
		$drag="<span id=\"NOTI_drag_".$this->notice_id.($this->anti_loop?"_p".$this->anti_loop[count($this->anti_loop)-1]:"")."\"  dragicon=\"$base_path/images/icone_drag_notice.png\" dragtext=\"".$this->header."\" draggable=\"yes\" dragtype=\"notice\" callback_before=\"show_carts\" callback_after=\"\" style=\"padding-left:7px\"><img src=\"".$base_path."/images/notice_drag.png\"/></span>";
	if($this->action) {
		$this->header = "<a href=\"".$this->action."\">".$this->header.'</a>';
	}
	if ($this->notice->niveau_biblio=='b') {
		$rqt="select tit1 from bulletins,notices where bulletins.num_notice='".$this->notice_id."' and notices.notice_id=bulletins.bulletin_notice";
		$execute_query=mysql_query($rqt);
		$row=mysql_fetch_object($execute_query);
		$this->header.=" <i>".str_replace("%s",$row->tit1,$msg["bul_titre_perio"])."</i>";
		mysql_free_result($execute_query);
	}



	if($this->notice->lien) {
		// ajout du lien pour les ressourcenotice_parent_useds ï¿½lectroniques
		$this->header .= "<a href=\"".$this->notice->lien."\" target=\"__LINK__\">";
		global $use_opac_url_base, $opac_url_base ;
		if (!$use_opac_url_base) $this->header .= "<img src=\"./images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
			else $this->header .= "<img src=\"".$opac_url_base."images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
		$this->header .= " alt=\"";
		$this->header .= $this->notice->eformat;
		$this->header .= "\" title=\"";
		$this->header .= $this->notice->eformat;
		$this->header .= "\">";
		$this->header .='</a>';
	}
}
  
// rï¿½cupï¿½ration des valeurs en table---------------------------------------
function mono_display_fetch_data() {
	global $dbh;
	
	$requete = "SELECT source_id FROM external_count WHERE rid=".addslashes($this->notice_id);
	$myQuery = mysql_query($requete, $dbh);
	$source_id = mysql_result($myQuery, 0, 0);

	$requete="select * from entrepot_source_".$source_id." where recid='".addslashes($this->notice_id)."' order by ufield,field_order,usubfield,subfield_order,value";
	$myQuery = mysql_query($requete, $dbh);
	
	$notice="";
	$lpfo="";
	$n_ed=-1;
	$n_coll=-1;
	$exemplaires = array();
	$doc_nums = array();
	
	if(mysql_num_rows($myQuery)) {
		$notice->notice_id=$this->notice_id;
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
//					$notice->niveau_biblio=$l->value;
					$notice->niveau_biblio='m'; //On force le document au type monographie 
					break;
				case "hl":
					$notice->niveau_hierar=0; //On force le niveau ï¿½ zï¿½ro.
//					$notice->niveau_hierar=$l->value; 
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
							$notice->year=$l->value;
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
				case "225":
					if($l->field_order!=$lpfo) {
						$lpfo=$l->field_order;
						$n_coll++;
					}
					switch ($l->usubfield) {
						case "a":
							$this->collections[$n_coll]["name"]=$l->value;
							break;
						case "x":
							$this->collections[$n_coll]["ISSN"]=$l->value;
							break;
						case "i":
							$this->collections[$n_coll]["subcoll_name"]=$l->value;
							break;
						case "v":
							$this->collections[$n_coll]["volume"]=$l->value;
							$notice->nocoll=$l->value;
							break;
					}
				//Note gï¿½nï¿½rale
				case "300":
					$notice->n_gen=$l->value;
					break;
				//Note de contenu
				case "327":
					$notice->n_contenu=$l->value;
					break;
				//Note de rï¿½sumï¿½
				case "330":
					$notice->n_resume=$l->value;
					break;
				//Sï¿½rie
				case "461":
					if ($l->usubfield=="t") $notice->serie_name=$l->value;
					if ($l->usubfield=="v") $notice->tnvol=$l->value;
					break;
				//Mots clï¿½s
				case "610":
					switch ($l->usubfield) {
						case "a":
							$notice->index_l.=($notice->index_l?" / ":"").$l->value;
							break;
					}
					break;
				case "676":
					switch ($l->usubfield) {
						case "a":
							$notice->indexint_name=$l->value;
							break;
					}
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
				//Documents numériques
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
	
/*	$requete = "SELECT * FROM notices WHERE notice_id='".$this->notice_id."' ";
	$myQuery = mysql_query($requete, $dbh);
	if(mysql_num_rows($myQuery)) {
		$this->notice = mysql_fetch_object($myQuery);
		}
	$this->langues	= get_notice_langues($this->notice_id, 0) ;	// langues de la publication
	$this->languesorg	= get_notice_langues($this->notice_id, 1) ; // langues originales*/

	$this->isbn = $this->notice->code ; 
	return mysql_num_rows($myQuery);
}

// fonction retournant les infos d'exemplaires pour une notice donnï¿½e
function show_expl_per_notice() {
	global $msg;
	global $dbh;
	
	if (!$this->exemplaires)
		return;
	
	$expl_output = "<table border='0' class='expl-list'>";
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
		//Si on trouve une localisation, on la convertie en libelle et on l'oublie si spécifié
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

	$expl_output .= "<tr>";
	foreach ($expl996 as $caption996) {
		$expl_output .= "<th>".$caption996."</th>";
	}
	$expl_output .= "</tr>";

	//trions
	usort($final_location, "cmpexpl");

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
	$expl_output .= "</table>";
	
	return $expl_output;
	
}
	

function do_image(&$entree) {
	global $pmb_book_pics_show ;
	global $pmb_book_pics_url ;
	// pour url OPAC en diff DSI
	global $prefix_url_image ;
	
	global $depliable ;
	
	if ($depliable!==0) $depliable=1 ;
	
	if ($this->notice->code || $this->notice->thumbnail_url) {
		if ($pmb_book_pics_show=='1' && ($pmb_book_pics_url || $this->notice->thumbnail_url)) {
			$code_chiffre = pmb_preg_replace('/-|\.| /', '', $this->notice->code);
			$url_image = $pmb_book_pics_url ;
			$url_image = $prefix_url_image."getimage.php?url_image=".urlencode($url_image)."&noticecode=!!noticecode!!&vigurl=".urlencode($this->notice->thumbnail_url) ;
			if ($depliable) $image = "<img id='PMBimagecover".$this->notice_id."' src='".$prefix_url_image."images/vide.png' align='right' hspace='4' vspace='2' isbn='".$code_chiffre."' url_image='".$url_image."'>";
				else {
					$url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
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

}