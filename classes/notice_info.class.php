<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_info.class.php,v 1.15 2010-12-29 11:21:36 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// Récupératiuon des info de notices
require_once($class_path."/parametres_perso.class.php");
require_once($include_path."/notice_authors.inc.php");
require_once("$class_path/author.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/subcollection.class.php");
require_once($include_path."/notice_categories.inc.php");
require_once($include_path."/explnum.inc.php");

class notice_info {
	var $notice;	
	
	//Initialisation avec une liste de numeros de notices (si liste vide alors on prend toute la base)
	function notice_info($id,$environement=array()) {			
		$this->notice_id=$id;
		$this->environement=$environement;
		if(!$this->environement["short"]) $this->environement["short"] = 6;
		if(!$this->environement["ex"])	$this->environement["ex"] = 0;
		if(!$this->environement["exnum"]) $this->environement["exnum"] = 1;
		
		if(!$this->environement["link"]) $this->environement["link"] = "./catalog.php?categ=isbd&id=!!id!!" ;
		if(!$this->environement["link_analysis"]) $this->environement["link_analysis"] = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!" ;
		if(!$this->environement["link_explnum"]) $this->environement["link_explnum"] = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!" ;
		if(!$this->environement["link_bulletin"]) $this->environement["link_bulletin"] = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!" ;
		
		$this->fetch_data();
	}
	
	function fetch_analysis_info() {
		if (($this->niveau_biblio=="a")&&($this->niveau_hierar==2)) {
			$requete="select tit1,bulletin_numero,date_date,mention_date from analysis join bulletins on (analysis_bulletin=bulletin_id) join notices on (bulletin_notice=notice_id) where " .
					"analysis_notice=".$this->notice_id;
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$r=mysql_fetch_object($resultat);
				$this->serial_title=$r->tit1;
				$this->bulletin_numero=$r->bulletin_numero;
				$this->bulletin_mention_date=$r->mention_date;
				$this->bulletin_date_date=formatdate($r->date_date);
			}
		}
	}
	
	//Generation XML de la prochaine notice (renvoi true si prochaine notice, false si plus de notices disponibles)
	function fetch_data() {
		global $opac_show_book_pics ;
		global $opac_book_pics_url ;
		global $opac_url_base ;
		global $fonction_auteur,$msg;
		//Recuperation des infos de la notice
		$requete = "select * from notices where notice_id=".$this->notice_id;
		$resultat = mysql_query($requete);
		$res = mysql_fetch_object($resultat);
		$this->notice=$res;
		
/*		$this->langues	= get_notice_langues($this->notice_id, 0) ;	// langues de la publication
		$this->languesorg	= get_notice_langues($this->notice_id, 1) ; // langues originales
	*/
		$this->memo_isbn = $this->notice->code ; 
		$this->niveau_biblio=$this->notice->niveau_biblio;
		$this->niveau_hierar=$this->notice->niveau_hierar;
		
		//Recherche des infos du périodique
		$this->fetch_analysis_info();
		if($res->niveau_biblio != 's' && $res->niveau_biblio != 'a') {
//			$display = new mono_display($this->notice_id, $this->environement["short"], $this->environement["link"], 1, $this->environement["link_expl"], '', $this->environement["link_explnum"],1);
		} else {
			// on a affaire à un périodique
//			$display = new serial_display($this->notice_id, $this->environement["short"], $this->environement["link_serial"], $this->environement["link_analysis"], $this->environement["link_bulletin"], "", $this->environement["link_explnum"], 0, 0, 1, 1, true, 1);
		}
//		$this->memo_notice_display= $display;
		
		//Titres
		//Titre de serie et composition du titre
		$this->memo_series[]=array();
		if($res->tparent_id) {
			$requete = "select * from series where serie_id=".$res->tparent_id;
			$resultat = mysql_query($requete);
			if (($serie = mysql_fetch_object($resultat))) {
				$this->memo_series[]=$serie;
				$this->memo_titre .=$serie;
				if($this->notice->tnvol) {					
					$this->memo_titre .= ', '.$res->tnvol;	
				}		
			}
		} elseif($this->notice->tnvol){
			$this->memo_titre .= $res->tnvol;
		}
		
		$this->memo_titre ? $this->memo_titre .= '. '.$res->tit1 : $this->memo_titre = $res->tit1;	
		
		if ($res->niveau_biblio=='b') {
			$rqt="select tit1, date_format(date_date, '".$msg["format_date"]."') as aff_date_date, bulletin_numero as num_bull from bulletins,notices where bulletins.num_notice='".$this->notice_id."' and notices.notice_id=bulletins.bulletin_notice";
			$execute_query=mysql_query($rqt);
			$row=mysql_fetch_object($execute_query);
			$this->memo_titre.=" ".(!$row->aff_date_date?sprintf($msg["bul_titre_perio"],$row->tit1):sprintf($msg["bul_titre_perio"],$row->tit1.", ".$row->num_bull." [".$row->aff_date_date."]"));
		}		
				
		$this->memo_complement_titre=$res->tit4;
		$this->memo_titre_parallele=$res->tit3;
		
		$this->memo_notice = $res;
				
		$this->memo_bulletin=array();
		if($res->niveau_biblio == 'b' && $res->niveau_hierar == '2'){
			$req_bulletin = "SELECT bulletin_id, bulletin_numero, date_date, mention_date, bulletin_titre, bulletin_numero from bulletins WHERE num_notice=".$res->notice_id;
			$result_bull = mysql_query($req_bulletin);
			while(($bull=mysql_fetch_object($result_bull))){
				$this->memo_bulletin[]=$bull;
			}
		}
		
		//Titre du pério pour les notices de bulletin		
		$this->memo_notice_bulletin=array();
		if($res->niveau_biblio == 'b' && $res->niveau_hierar == '2'){				
			$req_bulletin = "SELECT bulletin_id, bulletin_numero, date_date, mention_date, bulletin_titre, bulletin_numero, tit1 as titre from bulletins, notices WHERE bulletin_notice=notice_id AND num_notice=".$res->notice_id;
			$result_bull = mysql_query($req_bulletin);
			while(($bull=mysql_fetch_object($result_bull))){
				$this->memo_notice_bulletin[]=$bull;
			}				
		}

		//Langage
		$this->memo_lang	= get_notice_langues($this->notice_id, 0) ;	// langues de la publication
		$this->memo_lang_or	= get_notice_langues($this->notice_id, 1) ; // langues originales
			
				
		//Auteurs
		//Recherche des auteurs;
		$this->responsabilites = get_notice_authors($this->notice_id);	
		$mention_resp = $mention_resp_1 = $mention_resp_2 = array() ;	
		$isbd_entry_1 = $isbd_entry_2 = array() ;		
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			if ($this->print_mode) $mention_resp_lib = $auteur->isbd_entry; 
			else $mention_resp_lib = $auteur->isbd_entry_lien_gestion;
			if (!$this->print_mode) $mention_resp_lib .= $auteur->author_web_link ;
			if ($auteur_0["fonction"]) $mention_resp_lib .= ", ".$fonction_auteur[$auteur_0["fonction"]];
			$mention_resp[] = $mention_resp_lib;
			$this->memo_auteur_principal=$auteur->isbd_entry;
			if ($auteur->isbd_entry){
//				$this->memo_titre.= ' / '. $auteur->isbd_entry;
			}	
		}		
		$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_1["id"]);
			if ($this->print_mode) $mention_resp_lib = $auteur->isbd_entry; 
			else $mention_resp_lib = $auteur->isbd_entry_lien_gestion;
			if (!$this->print_mode) $mention_resp_lib .= $auteur->author_web_link ;
			if ($auteur_1["fonction"]) $mention_resp_lib .= ", ".$fonction_auteur[$auteur_1["fonction"]];
			$mention_resp[] = $mention_resp_lib;
			$mention_resp_1[] = $mention_resp_lib;
			$isbd_entry_1[]= $auteur->isbd_entry;
		}	
		$this->memo_mention_resp_1 = implode ("; ",$mention_resp_1);	
		$this->memo_auteur_autre = implode ("; ",$isbd_entry_2);
			
		$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
		for ($i = 0 ; $i < count($as) ; $i++) {
			$indice = $as[$i] ;
			$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			$auteur = new auteur($auteur_2["id"]);
			if ($this->print_mode) $mention_resp_lib = $auteur->isbd_entry; 
			else $mention_resp_lib = $auteur->isbd_entry_lien_gestion;
			if (!$this->print_mode) $mention_resp_lib .= $auteur->author_web_link ;
			if ($auteur_2["fonction"]) $mention_resp_lib .= ", ".$fonction_auteur[$auteur_2["fonction"]];
			$mention_resp[] = $mention_resp_lib;
			$mention_resp_2[]= $mention_resp_lib;
			$isbd_entry_2[]= $auteur->isbd_entry;
		}	
		$this->memo_mention_resp_2 = implode ("; ",$mention_resp_2);		
		$this->memo_auteur_secondaire = implode ("; ",$isbd_entry_2);
				
		$this->memo_libelle_mention_resp = implode ("; ",$mention_resp);	

		// on récupère la collection au passage, si besoin est
		if($this->notice->subcoll_id) {
			$collection = new subcollection($this->notice->subcoll_id);
			$info=$this->get_info_editeur($collection->editeur);			
			$this->memo_collection=$collection->isbd_entry;
			$this->memo_ed1=$info["isbd_entry"];		
			$editeurs=$info["isbd_entry"];				
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$info=$this->get_info_editeur($collection->parent);			
			$this->memo_collection=$collection->isbd_entry;
			$this->memo_ed1=$info["isbd_entry"];	
			$editeurs=$info["isbd_entry"];		
		} elseif ($this->notice->ed1_id) {
			$info=$this->get_info_editeur($this->notice->ed1_id);			
			$this->memo_ed1=$info["isbd_entry"];	
			$editeurs=$info["isbd_entry"];		
		}		
		if($this->notice->ed2_id) {
			$info=$this->get_info_editeur($this->notice->ed2_id);	
			$this->memo_ed2=$info["isbd_entry"];
			$editeurs ? $editeurs .= '&nbsp;; '.$info["isbd_entry"] : $editeurs = $info["isbd_entry"];
		}
	
		if($this->notice->year) {
			$editeurs ? $editeurs .= ', '.$this->notice->year : $editeurs = $this->notice->year;
//			$this->memo_titre.=' ('.$this->notice->year.')';
		} elseif ($this->notice->niveau_biblio!='b') $editeurs ? $editeurs .= ', [s.d.]' : $editeurs = "[s.d.]";
		$this->memo_year=$this->notice->year;
	
		if ($editeurs) $this->isbd .= ".&nbsp;-&nbsp;$editeurs";		
		
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
		$this->memo_collation=$collation;

		//Recherche du code dewey
		$requete = "select * from indexint where indexint_id=".$res -> indexint;
		$resultat = mysql_query($requete);
		if (($code_dewey=mysql_fetch_object($resultat))) {
			$this->memo_dewey=$code_dewey;
		}

		//Traitement des exemplaires
		$this->memo_exemplaires=array();
		$requete = "select expl_id, expl_cb,expl_cote,expl_statut,statut_libelle, statusdoc_codage_import, expl_typdoc, tdoc_libelle, tdoc_codage_import, expl_note, expl_comment, expl_section, section_libelle, sdoc_codage_import, expl_owner, lender_libelle, codestat_libelle, statisdoc_codage_import, expl_date_retour, expl_date_depot, expl_note, pret_flag, location_libelle, locdoc_codage_import 
		from exemplaires, docs_statut, docs_type, docs_section, docs_codestat, lenders, docs_location 
		where expl_notice=".$res -> notice_id." and expl_statut=idstatut and expl_typdoc=idtyp_doc and expl_section=idsection and expl_owner=idlender and expl_codestat=idcode and expl_location=idlocation";
		$resultat = mysql_query($requete);		
		while (($ex = mysql_fetch_object($resultat))) {
			//Champs perso d'exemplaires			
			$parametres_perso=array();
			$mes_pp=new parametres_perso("expl");
			if (!$mes_pp->no_special_fields) {			
				$mes_pp->get_values($ex->expl_id);
				$values = $mes_pp->values;
				foreach ( $values as $field_id => $vals ) {
					foreach ( $vals as $value ) {				
						$parametres_perso[$mes_pp->t_fields[$field_id]["NAME"]]["TITRE"]=$mes_pp->t_fields[$field_id]["TITRE"];
						$parametres_perso[$mes_pp->t_fields[$field_id]["NAME"]]["VALUE"]=$mes_pp->get_formatted_output(array($value),$field_id);	
					}
				}							
			}
			$ex->parametres_perso=$parametres_perso;
			$this->memo_exemplaires[]=$ex;
		}
		
		//Descripteurs
		$requete="SELECT libelle_categorie FROM categories, notices_categories WHERE notcateg_notice=".$res->notice_id." and categories.num_noeud = notices_categories.num_noeud ORDER BY ordre_categorie";
		$resultat=mysql_query($requete);
		$this->memo_categories=array();
		while (($cat = mysql_fetch_object($resultat))) {
			$this->memo_categories[]=$cat;
		}
				
		//Champs perso de notice traite par la table notice_custom
		$mes_pp= new parametres_perso("notices");
		$mes_pp->get_values($res->notice_id);
		$values = $mes_pp->values;
		$this->parametres_perso=array();
		foreach ( $values as $field_id => $vals ) {
			foreach ( $vals as $value ) {
			 	$this->parametres_perso[$mes_pp->t_fields[$field_id]["NAME"]]["TITRE"]=$mes_pp->t_fields[$field_id]["TITRE"];
				$this->parametres_perso[$mes_pp->t_fields[$field_id]["NAME"]]["VALUE"]=$mes_pp->get_formatted_output(array($value),$field_id);				
			}
		}		

		//Notices liées, relations entre notices
		//les notices mères
		$requete="SELECT num_notice, linked_notice, relation_type, rank from notices_relations where num_notice=".$res->notice_id." order by num_notice, rank asc";
		$resultat=mysql_query($requete);
		while(($notice_fille=mysql_fetch_object($resultat))) {						
			$this->memo_notice_mere[]=$notice_fille->linked_notice;										
		}
	
		// les notices filles	
		$requete="SELECT num_notice, linked_notice, relation_type, rank from notices_relations where linked_notice=".$res->notice_id." order by num_notice, rank asc";
		$resultat=mysql_query($requete);
		while(($notice_mere=mysql_fetch_object($resultat))) {						
			$this->memo_notice_fille[]=$notice_mere->num_notice;			
		}
			
		// liens vers les périodiques pour les notices d'article
		$req_perio_link = "SELECT notice_id, tit1, code from bulletins,analysis,notices WHERE bulletin_notice=notice_id and bulletin_id=analysis_bulletin and analysis_notice=".$res->notice_id;
		$result_perio_link=mysql_query($req_perio_link);
		while(($notice_perio_link=mysql_fetch_object($result_perio_link))){
			$this->memo_notice_article[]=$notice_perio_link->notice_id;
		}		
	
		// bulletinage pour les notices de pério			
		$req_bulletinage = "SELECT bulletin_id, bulletin_numero, date_date, mention_date, bulletin_titre, bulletin_numero from bulletins, notices WHERE bulletin_notice = notice_id AND notice_id=".$res->notice_id;
		$result_bulletinage=mysql_query($req_bulletinage);					
		while(($notice_bulletinage=mysql_fetch_object($result_bulletinage))){
			$this->memo_bulletinage[]=$notice_bulletinage->bulletin_id;
		}					
				
		// liens vers les bulletins pour les notices d'article
		$req_bull_link = "SELECT bulletin_id, bulletin_numero, date_date, mention_date, bulletin_titre, bulletin_numero from bulletins, analysis WHERE bulletin_id=analysis_bulletin and analysis_notice=".$res->notice_id;
		$result_bull_link=mysql_query($req_bull_link);						
		while(($notice_bull_link=mysql_fetch_object($result_bull_link))){
			$this->memo_article_bulletinage[]=$notice_bull_link->bulletin_id;
		}			
					
		$paramaff["mine_type"]=1;
		$this->memo_explnum_assoc=show_explnum_per_notice($res->notice_id, 0,"",$paramaff);
		
		if ($this->notice->code || $this->notice->thumbnail_url) {
			if ($opac_show_book_pics=='1' && ($opac_book_pics_url || $this->notice->thumbnail_url)) {
				$code_chiffre = pmb_preg_replace('/-|\.| /', '', $this->notice->code);
				$url_image = $opac_book_pics_url ;
				$url_image = $opac_url_base."getimage.php?url_image=".urlencode($url_image)."&noticecode=!!noticecode!!&vigurl=".urlencode($this->notice->thumbnail_url) ;
				
				if ($this->notice->thumbnail_url) $url_image_ok=$this->notice->thumbnail_url;
				else $url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
				$this->memo_image = "<img src='".$url_image_ok."' align='right' hspace='4' vspace='2'>";
				$this->memo_url_image=$url_image;
				
			} else{
				$this->memo_image="" ;
				$this->memo_url_image="";
			}
		}	
		
		return true;
		
	}
	
	function get_info_editeur($id) {
		$info=array();
		$requete = "SELECT * FROM publishers WHERE ed_id=$id LIMIT 1 ";
		$result = @mysql_query($requete);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			mysql_free_result($result);
			$id		= $temp->ed_id;
			$name		= $temp->ed_name;
			$adr1		= $temp->ed_adr1;
			$adr2		= $temp->ed_adr2;
			$cp		= $temp->ed_cp;
			$ville	= $temp->ed_ville;
			$pays		= $temp->ed_pays;
			$web		= $temp->ed_web;
			$ed_comment= $temp->ed_comment	;

			// Determine le lieu de publication
			$l = '';
			if ($adr1)  $l = $adr1;
			if ($adr2)  $l = ($l=='') ? $adr2 : $l.', '.$adr2;
			if ($cp)    $l = ($l=='') ? $cp   : $l.', '.$cp;
			if ($pays)  $l = ($l=='') ? $pays : $l.', '.$pays;
			if ($ville) $l = ($l=='') ? $ville : $ville.' ('.$l.')';
			if ($l=='')       $l = '[S.l.]';
				
			// Determine le nom de l'editeur
			if ($name) $n = $name; else $n = '[S.n.]';
				
			// Constitue l'ISBD pour le coupe lieu/editeur
			if ($l == '[S.l.]' AND $n == '[S.n.]') $isbd_entry = '[S.l.&nbsp;: s.n.]';
			else $isbd_entry = $l.'&nbsp;: '.$n;
			$info['isbd_entry']=$isbd_entry;
		}	
		return($info);
	}
}
?>
