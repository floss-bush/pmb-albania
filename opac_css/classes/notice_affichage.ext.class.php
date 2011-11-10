<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_affichage.ext.class.php,v 1.101.2.9 2011-09-06 09:11:23 jpermanne Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");


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


 // Use this class if you want to show responsability functions before authors. 
 // This class defines a new fetch_auteurs function that overwrites the one included in the parent class notice_affichage
 // using this function you can load the author functions from the litteral_function.xml file if this exists in the lang directory.
 // Marco Vaninetti

class notice_affichage_custom_it extends notice_affichage {
   
	function fetch_auteurs() {
		global $fonction_auteur0;
		global $dbh ;
		global $include_path;
		global $lang, $tdoc, $langue_doc;
		$this->responsabilites  = array() ;
		$auteurs = array() ;
		
		$res["responsabilites"] = array() ;
		$res["auteurs"] = array() ;
		
		// if literal_function.xml exists we use this instead of function.xml
	
		$ISBDv2=0;
	
		if (is_file("$include_path/marc_tables/$lang/literal_function.xml")) $ISBDv2=1;
	
		if (!count($tdoc)) $tdoc = new marc_list('doctype');
		if (!count($fonction_auteur0)) {
			if ($ISBDv2)
				$fonction_auteur0 = new marc_list('literal_function');
			else
				$fonction_auteur0 = new marc_list('function');
			$fonction_auteur0 = $fonction_auteur0->table;
		}
		if (!count($langue_doc)) {
			$langue_doc = new marc_list('lang');
			$langue_doc = $langue_doc->table;
		}
		
		$rqt = "SELECT author_id, responsability_fonction, responsability_type, author_name, author_rejete, author_type, author_date, author_see, author_web ";
		$rqt.= "FROM responsability, authors ";
		$rqt.= "WHERE responsability_notice='".$this->notice_id."' AND responsability_author=author_id ";
		$rqt.= "ORDER BY responsability_type, responsability_ordre, responsability_fonction " ;
		$res_sql = mysql_query($rqt, $dbh);
		while (($notice=mysql_fetch_object($res_sql))) {
			$responsabilites[] = $notice->responsability_type ;
			if ($notice->author_rejete) $auteur_isbd = $notice->author_rejete." ".$notice->author_name ;
				else  $auteur_isbd = $notice->author_name ;
			// on s'arrête là pour auteur_titre = "Prénom NOM" uniquement
			$auteur_titre = $auteur_isbd ;
			// on complète auteur_isbd pour l'affichage complet
			if ($notice->author_date) $auteur_isbd .= " (".$notice->author_date.")" ;
			// URL de l'auteur
			if ($notice->author_web) $auteur_web_link = " <a href='$notice->author_web' target='_blank'><img src='./images/globe.gif' border='0'/></a>";
				else $auteur_web_link = "" ;
			if (!$this->to_print) $auteur_isbd .= $auteur_web_link ;
			$auteur_isbd = inslink($auteur_isbd, str_replace("!!id!!", $notice->author_id, $this->lien_rech_auteur)) ;
				
			if ($notice->responsability_fonction) $fonction_aut=$fonction_auteur0[$notice->responsability_fonction] ;
			else {
				$fonction_aut="";
				$notice->responsability_fonction="0";
			}
			
			$auteurs[] = array( 
					'id' => $notice->author_id,
					'fonction' => $notice->responsability_fonction,
					'responsability' => $notice->responsability_type,
					'name' => $notice->author_name,
					'rejete' => $notice->author_rejete,
					'date' => $notice->author_date,
					'type' => $notice->author_type,
					'fonction_aff' => $fonction_aut,
					'auteur_isbd' => $auteur_isbd,
					'auteur_titre' => $auteur_titre
					) ;
		}
			
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
			for ($i = 0 ; $i < count($as) ; $i++) {
				$indice = $as[$i] ;
				$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
				$aut1_libelle[]= $auteur_1["auteur_titre"];
			}
			$auteurs_liste = implode ("; ",$aut1_libelle) ;
			if ($auteurs_liste) $this->auteurs_principaux = $auteurs_liste ;
		}
		$flag1=0;
		// $this->auteurs_tous
		$mention_resp = array() ;
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$mention_resp_lib = $auteur_0["auteur_isbd"];
			if($auteur_0["fonction"]!="0" and $auteur_0["fonction"]!= 70)  $mention_resp_lib= $auteur_0["fonction_aff"]." ".$mention_resp_lib;
			$first_mention=$auteur_0["fonction_aff"];
			$mention_resp[] = $mention_resp_lib ;
		}
		$i=0;
		$as = array_keys ($this->responsabilites["responsabilites"], "1" ) ;
		
		while ($i < count($as) ) {
			$j=count($as)-$i-1;
			$indice = $as[$j] ;
			$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			$flag= $auteur_1["fonction"];
			$fonct_aff=$auteur_1["fonction_aff"];
			$mention_resp_lib = "";
			$k=0;
			$sep="";
			while ($flag==$auteur_1["fonction"]) {
				$mention_resp_lib =$auteur_1["auteur_isbd"].$sep.$mention_resp_lib;
				if ($k==0) $sep= " e ";
				else $sep=",";
				$k++;
				$indice = $as[$j-$k] ;
				$auteur_1 = $this->responsabilites["auteurs"][$indice] ;
			}
			$i=$i+$k;
					
			if($fonct_aff==$first_mention) {
				if ($k==1)$mention_resp_lib=$mention_resp[0]." e ".$mention_resp_lib;
				else $mention_resp_lib=$mention_resp[0].", ".$mention_resp_lib;
				$flag1++;
			} else if($fonct_aff !="") $mention_resp_lib=$fonct_aff." ".$mention_resp_lib;
			$mention_resp1[] = $mention_resp_lib ;
		}
		$mention_resp1 =array_reverse($mention_resp1);
		
		if($flag1==1) $mention_resp=$mention_resp1;
		else 	$mention_resp= array_merge($mention_resp,$mention_resp1);
		$as = array_keys ($this->responsabilites["responsabilites"], "2" ) ;
		$i=0;
		while ($i < count($as) ) {
			$j=count($as)-$i-1;
			$indice = $as[$j] ;
			$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			$flag= $auteur_2["fonction"];
			$fonct_aff=$auteur_2["fonction_aff"];
			$mention_resp_lib = "";
			$k=0;
			$sep="";
			while ($flag==$auteur_2["fonction"]) {
				$mention_resp_lib =$auteur_2["auteur_isbd"].$sep.$mention_resp_lib;
				if ($k==0) $sep= " e ";
				else $sep=",";
				$k++;
					$indice = $as[$j-$k] ;
					$auteur_2 = $this->responsabilites["auteurs"][$indice] ;
			}
			$i=$i+$k;
			$mention_resp_lib =$fonct_aff." ".$mention_resp_lib;
			$mention_resp2[] =$mention_resp_lib ;
		
		}
		$mention_resp2 =array_reverse($mention_resp2);
		$mention_resp= array_merge($mention_resp,$mention_resp2);
		$libelle_mention_resp = implode (" ; ",$mention_resp) ;
		if ($libelle_mention_resp) $this->auteurs_tous = $libelle_mention_resp ;
		else $this->auteurs_tous ="" ;
	} // end fetch_auteurs
	
} // end class notice_affichage_custom_it


class notice_affichage_custom_bretagne extends notice_affichage {
		
	function do_public($short=0,$ex=1) {
	
		global $dbh;
		global $msg;
		global $charset;
		global $opac_url_base, $opac_permalink;
	
		$this->fetch_categories() ;
		$this->notice_public="<table>";
		
		// ******* afin de pouvoir concaténer en td /td sous-collection et collection le cas échéant
		global $colspanbretagne;
		if ($this->notice->subcoll_id || ($this->notice->year && $this->notice->ed1_id)) $colspanbretagne = " colspan='3' ";
		else $colspanbretagne = "";
	
		// Notices parentes
		$this->notice_public.=$this->parents;
	
		// constitution de la mention de titre
		if ($this->notice->serie_name) {
			$this->notice_public.= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['tparent_start']."</span></td><td $colspanbretagne>".inslink($this->notice->serie_name,  str_replace("!!id!!", $this->notice->tparent_id, $this->lien_rech_serie));;
			if ($this->notice->tnvol)
				$this->notice_public .= ',&nbsp;'.$this->notice->tnvol;
			$this->notice_public .="</td></tr>";
		}
		
		$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['title']." :</span></td>";
		$this->notice_public .= "<td $colspanbretagne><span class='public_title'>".$this->notice->tit1 ;
		
		if ($this->notice->tit4) $this->notice_public .= ": ".$this->notice->tit4 ;
		$this->notice_public.="</span></td></tr>";
		
		if ($this->notice->tit2) $this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t2']." :</span></td><td $colspanbretagne>".$this->notice->tit2."</td></tr>" ;
		if ($this->notice->tit3) $this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t3']." :</span></td><td $colspanbretagne>".$this->notice->tit3."</td></tr>" ;
		
		if ($this->auteurs_tous) $this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['auteur_start']."</span></td><td $colspanbretagne>".$this->auteurs_tous."</td></tr>";
		
		// mention d'édition
		if ($this->notice->mention_edition) $this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['mention_edition_start']."</span></td><td $colspanbretagne>".$this->notice->mention_edition."</td></tr>";
		
		// zone de l'éditeur 
		if ($this->notice->year) {
			$annee = ", ".$this->notice->year;
			$colspanbretagneediteur="";
		} else $colspanbretagneediteur=$colspanbretagne;
	
		if ($this->notice->ed1_id) {
			$editeur = new publisher($this->notice->ed1_id);
			$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['editeur_start']."</span></td><td $colspanbretagneediteur>".inslink($editeur->display,  str_replace("!!id!!", $this->notice->ed1_id, $this->lien_rech_editeur)). $annee."</td></tr>" ;
			$annee = "" ;
		}
	
		// *** collection  et sous-collection
		// ******* concaténer en td /td sous-collection et collection
		if ($this->notice->nocoll) $affnocoll = " ".str_replace("!!nocoll!!", $this->notice->nocoll, $msg['subcollection_details_nocoll']) ;
		else $affnocoll = "";
		if($this->notice->subcoll_id) {
			$subcollection = new subcollection($this->notice->subcoll_id);
			$collection = new collection($this->notice->coll_id);
			$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td>".inslink($collection->name,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection))."</td></tr>";
			$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['subcoll_start']."</span></td><td>".inslink($subcollection->name,  str_replace("!!id!!", $this->notice->subcoll_id, $this->lien_rech_subcollection));
			$this->notice_public .=$affnocoll."</td></tr>";
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td $colspanbretagne>".inslink($collection->isbd_entry,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection)) ;
			$this->notice_public .=$affnocoll."</td></tr>";
		}
	
		// $annee est vide si ajoutée avec l'éditeur, donc si pas éditeur, on l'affiche ici
		if ($annee) $this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['year_start']."</span></td><td $colspanbretagne>".$this->notice->year."</td></tr>" ;
	
		// Titres uniformes
		if(($tu_liste=$this->notice->tu->get_print_type(2,$opac_url_base."/index.php?lvl=titre_uniforme_see&id=" ))) {
			$this->notice_public.= 
			"<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['titre_uniforme_aff_public']."</span></td><td $colspanbretagne>".$tu_liste."</td></tr>";
		}	
		// zone de la collation
		if($this->notice->npages) {
			if ($this->notice->niveau_biblio<>"a") {
				$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start']."</span></td><td $colspanbretagne>".$this->notice->npages."</td></tr>";
			} else {
				$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start_perio']."</span></td><td $colspanbretagne>".$this->notice->npages."</td></tr>";
			}
		}
		if ($this->notice->ill)
			$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['ill_start']."</span></td><td $colspanbretagne>".$this->notice->ill."</td></tr>";
		if ($this->notice->size)
			$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['size_start']."</span></td><td $colspanbretagne>".$this->notice->size."</td></tr>";
		if ($this->notice->accomp)
			$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['accomp_start']."</span></td><td $colspanbretagne>".$this->notice->accomp."</td></tr>";
			
		// note générale
		if ($this->notice->n_gen) $zoneNote = nl2br(htmlentities($this->notice->n_gen,ENT_QUOTES, $charset));
		if ($zoneNote) $this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_gen_start']."</span></td><td $colspanbretagne>".$zoneNote."</td></tr>";
	
		// Permalink avec Id
		if ($opac_permalink) $ret.= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td $colspanbretagne><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";
	
		// langues
		if (count($this->langues)) {
			$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['537']." :</span></td><td $colspanbretagne>".$this->construit_liste_langues($this->langues);
			if (count($this->languesorg)) $this->notice_public .= " <span class='etiq_champ'>".$msg['711']." :</span> ".$this->construit_liste_langues($this->languesorg);
			$this->notice_public.="</td></tr>";
		} elseif (count($this->languesorg)) {
			$this->notice_public .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['711']." :</span></td><td $colspanbretagne>".$this->construit_liste_langues($this->languesorg)."</td></tr>"; 
		}
		if (!$short) $this->notice_public .= $this->aff_suite() ; 
		else $this->notice_public.=$this->genere_in_perio();
		$this->notice_public.="</table>\n";
		
		//Notices liées
		// ajoutées en dehors de l'onglet PUBLIC ailleurs
		if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
	
		return;
	} // end do_public

// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour éviter la redondance de calcul
function aff_suite() {
	global $msg;
	global $charset;
	global $opac_allow_tags_search;

	global $tdoc;

	// afin d'éviter de recalculer un truc déjà calculé...
	// MODIF ER 21 oct 2008 : pour la Bretagne, on recalcule forcément à cause du colspanbretagne
	// if ($this->affichage_suite) return $this->affichage_suite ;
	
	// ******* afin de pouvoir concaténer en td /td sous-collection et collection le cas échéant, 
	//     récupérer  $colspanbretagne calculé par do_public
	global $colspanbretagne;

	// serials : si article
	$ret .= $this->genere_in_perio () ;
	
	//Espace
	$ret.="\n\t<tr class='tr_spacer'><td class='td_spacer'>&nbsp;</td><td $colspanbretagne></td></tr>";
	
	// résumé
	if($this->notice->n_resume)
 		$ret .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td $colspanbretagne>".nl2br($this->notice->n_resume)."</td></tr>";

	// note de contenu
	if($this->notice->n_contenu) 
 		$ret .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td $colspanbretagne>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";

	// toutes indexations
	$ret_index = "";
	// Catégories
	if ($this->categories_toutes) 
		$ret_index .= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['categories_start']."</span></td><td $colspanbretagne>".$this->categories_toutes."</td></tr>";
			
	// Affectation du libellé mots clés ou tags en fonction de la recherche précédente	
	if ($opac_allow_tags_search == 1) $libelle_key = $msg['tags'];
	else $libelle_key = $msg['motscle_start'];
			
	// indexation libre
	$mots_cles = $this->do_mots_cle() ;
	if ($mots_cles)
		$ret_index.= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$libelle_key."</span></td><td $colspanbretagne>".$mots_cles."</td></tr>";
		
	// indexation interne
	if($this->notice->indexint) {
		$indexint = new indexint($this->notice->indexint);
		$ret_index.= "\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['indexint_start']."</span></td><td $colspanbretagne>".inslink($indexint->name,  str_replace("!!id!!", $this->notice->indexint, $this->lien_rech_indexint))." ".nl2br(htmlentities($indexint->comment,ENT_QUOTES, $charset))."</td></tr>" ;
		}

	if ($ret_index) {
		$ret.=$ret_index;
		// espace
		// $ret.="<tr class='tr_spacer'><td class='td_spacer'>&nbsp;</td><td $colspanbretagne></td></tr>";
	}
	
	//Champs personalisés
	$perso_aff = "" ;
	if (!$this->p_perso->no_special_fields) {
		$perso_=$this->p_perso->show_fields($this->notice_id);
		for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
			$p=$perso_["FIELDS"][$i];
			if ($p['OPAC_SHOW'] && $p["AFF"] && $p["NAME"]=="type_nature") {
				$perso_aff .="\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".substr($msg['typdocdisplay_start'],0,4)." :</span></td><td $colspanbretagne>".$tdoc->table[$this->notice->typdoc]."&nbsp; ;";
				$perso_aff .=" &nbsp;".$p["AFF"]."</td></tr>";
			} elseif($p['OPAC_SHOW'] && $p["AFF"]) {
				$perso_aff .="\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".strip_tags($p["TITRE"])."</span></td><td $colspanbretagne>".$p["AFF"]."</td></tr>";
			}
		}
	}
	if ($perso_aff) {
		$ret .= $perso_aff ;
	}
	
	if ($this->notice->lien) {
		//$ret.="\n\t<tr class='tr_spacer'><td class='td_spacer'>&nbsp;</td><td $colspanbretagne></td></tr>";
		$ret.="\n\t<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["lien_start"]."</span></td><td $colspanbretagne>" ;
		if (substr($this->notice->eformat,0,3)=='RSS') {
			$ret .= affiche_rss($this->notice->notice_id) ;
		} else {
			if (strlen($this->notice->lien)>80) {
				$ret.="<a href=\"".$this->notice->lien."\" target=\"top\" class='lien856'>".htmlentities(substr($this->notice->lien, 0, 80),ENT_QUOTES,$charset)."</a>&nbsp;[...]";
			} else {
				$ret.="<a href=\"".$this->notice->lien."\" target=\"top\" class='lien856'>".htmlentities($this->notice->lien,ENT_QUOTES,$charset)."</a>";
			}		
		}
		$ret.="</td></tr>";
		if ($this->notice->eformat && substr($this->notice->eformat,0,3)!='RSS') $ret.="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["eformat_start"]."</span></td><td $colspanbretagne>".htmlentities($this->notice->eformat,ENT_QUOTES,$charset)."</td></tr>";
	}
	
	$this->affichage_suite = $ret ;
	return $ret ;
	} 

	
} // end class notice_affichage_custom_bretagne


class notice_affichage_custom_alstom extends notice_affichage {


// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour éviter la redondance de calcul
function aff_suite() {
	global $msg;
	global $charset;
	
	// afin d'éviter de recalculer un truc déjà calculé...
	if ($this->affichage_suite) return $this->affichage_suite ;
	
	// serials : si article
	$ret .= $this->genere_in_perio () ;
	
	//Espace
	$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
	
	// résumé
	if($this->notice->n_resume)
 		$ret .= "<tr><td align='right' class='bg-grey'><b>".$msg['n_resume_start']."</b></td><td>".nl2br($this->notice->n_resume)."</td></tr>";

	// note de contenu
	if($this->notice->n_contenu) 
 		$ret .= "<tr><td align='right' class='bg-grey' style='color:#000000' ><b>".$msg['n_contenu_start']."</b></td><td  style='color:#000000'>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";

	// Catégories
	if($this->categories_toutes) 
		$ret .= "<tr><td align='right' class='bg-grey'><b>".$msg['categories_start']."</b></td><td>".$this->categories_toutes."</td></tr>";
			
	// indexation libre
	$mots_cles = $this->do_mots_cle() ;
	if($mots_cles)
		$ret .= "<tr><td align='right' class='bg-grey'><b>".$msg['motscle_start']."</b></td><td>".$mots_cles."</td></tr>";
	
	// indexation interne
	if($this->notice->indexint) {
		$indexint = new indexint($this->notice->indexint);
		$ret .= "<tr><td align='right' class='bg-grey'><b>".$msg['indexint_start']."</b></td><td>".inslink($indexint->name,  str_replace("!!id!!", $this->notice->indexint, $this->lien_rech_indexint))." ".nl2br(htmlentities($indexint->comment,ENT_QUOTES, $charset))."</td></tr>" ;
		}
	
	//Champs personalisés
	$perso_aff = "" ;
	if (!$this->p_perso->no_special_fields) {
		$perso_=$this->p_perso->show_fields($this->notice_id);
		for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
			$p=$perso_["FIELDS"][$i];
			if ($p['OPAC_SHOW'] && $p["AFF"]) $perso_aff .="<tr><td align='right' class='bg-grey'>".$p["TITRE"]."</td><td>".$p["AFF"]."</td></tr>";
			}
		}
	if ($perso_aff) {
		//Espace
		$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
		$ret .= $perso_aff ;
		}
	
	if ($this->notice->lien) {
		$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
		$ret.="<tr><td align='right' class='bg-grey'
		style='color:#3B793B'><b>".$msg["lien_start"]."</b></td><td>" ;
		if (substr($this->notice->eformat,0,3)=='RSS') {
			$ret .= affiche_rss($this->notice->notice_id) ;
			} else {
				$ret.="<a style='color:#3B793B' href=\"".$this->notice->lien."\" target=\"top\">".htmlentities($this->notice->lien,ENT_QUOTES,$charset)."</a></td></tr>";
				}
		$ret.="</td></tr>";
		if ($this->notice->eformat && substr($this->notice->eformat,0,3)!='RSS') $ret.="<tr><td align='right' class='bg-grey'><b>".$msg["eformat_start"]."</b></td><td>".htmlentities($this->notice->eformat,ENT_QUOTES,$charset)."</td></tr>";
		}
	
	$this->affichage_suite = $ret ;
	return $ret ;
	} 
	
}

class notice_affichage_mw extends notice_affichage {
	//affichage alterné de 2 styles différents dans les lignes du tableau des notices

	var $x="";		//gestion de l'alternance des lignes colorées dans le tableau HTML

	// génération de l'affichage public----------------------------------------
	function do_public($short=0,$ex=1) {
		
		global $msg;
		global $tdoc;
		global $charset;
		global $opac_url_base;
		
		$this->fetch_categories() ;
		$this->notice_public = "<table>";

		// constitution de la mention de titre
		$x="";
		if ($this->notice->serie_name) {
			if ($x=="2") $x=""; 
				else $x="2";
			$this->notice_public.= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['tparent_start']."</b></td><td class='bg-grey$x'>".inslink($this->notice->serie_name,  str_replace("!!id!!", $this->notice->tparent_id, $this->lien_rech_serie));;
			if ($this->notice->tnvol)
			$this->notice_public .= ',&nbsp;'.$this->notice->tnvol;
			$this->notice_public .="</td></tr>";
		}
		if ($x=="2") $x=""; 
			else $x="2";
		$this->notice_public.= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['title']." :</b></td>";
		$this->notice_public .= "<td class='bg-grey$x'>".$this->notice->tit1 ;

		if ($this->notice->tit4) $this->notice_public .= ": ".$this->notice->tit4 ;
		$this->notice_public.="</td></tr>";

		if ($this->notice->tit2) {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['other_title_t2']." :</b></td><td class='bg-grey$x'>".$this->notice->tit2."</td></tr>" ;
		}
		if ($this->notice->tit3) {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['other_title_t3']." :</b></td><td class='bg-grey$x'>".$this->notice->tit3."</td></tr>" ;
		}

		//type de document
		if ($tdoc->table[$this->notice->typdoc]) {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['typdocdisplay_start']."</b></td><td class='bg-grey$x'>".$tdoc->table[$this->notice->typdoc]."</td></tr>";
		}

		//auteur
		if ($this->auteurs_tous) {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['auteur_start']."</b></td><td class='bg-grey$x'>".$this->auteurs_tous."</td></tr>";
		}

		// mention d'édition
		if ($this->notice->mention_edition) {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['mention_edition_start']."</b></td><td class='bg-grey$x'>".$this->notice->mention_edition."</td></tr>";
		}

		//Date de publication
		if ($this->notice->ed1_id) {
			if ($x=="2") {$x="";} else {$x="2";}
			$editeur = new publisher($this->notice->ed1_id);
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['editeur_start']."</b></td><td class='bg-grey$x'>".inslink($editeur->display,  str_replace("!!id!!", $this->notice->ed1_id, $this->lien_rech_editeur)) ;
			$this->notice_public.="</td></tr>";
			if ($this->notice->year){
				if ($x=="2") {$x="";} else {$x="2";}
				$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['year_start']."</b></td><td class='bg-grey$x'>".$this->notice->year."</td></tr>";
				$annee=true;
			}
		}


		// collection
		if($this->notice->subcoll_id) {
			if ($x=="2") $x=""; 
				else $x="2";
			$subcollection = new subcollection($this->notice->subcoll_id);
			$collection = new collection($this->notice->coll_id);
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['coll_start']."</b></td><td class='bg-grey$x'>".inslink($collection->name,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection))."</td></tr>" ;
			if ($x=="2") $x=""; 
				else $x="2";
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['subcoll_start']."</b></td><td class='bg-grey$x'>".inslink($subcollection->name,  str_replace("!!id!!", $this->notice->subcoll_id, $this->lien_rech_subcollection))."</td></tr>" ;
		}
		elseif ($this->notice->coll_id) {
			if ($x=="2") {$x="";} else {$x="2";}
			$collection = new collection($this->notice->coll_id);
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['coll_start']."</b></td><td class='bg-grey$x'>".inslink($collection->isbd_entry,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection)) ;
		}
		if ($this->notice->nocoll) $this->notice_public .= " ".str_replace("!!nocoll!!", $this->notice->nocoll, $msg['subcollection_details_nocoll']) ;
		$this->notice_public.="</td></tr>";

		// ajout $annee si pas vide. Est vide si déjà ajouté plus haut
		if (!$annee) $this->notice_public .= $annee ;
		
		// Titres uniformes
		if(($tu_liste=$this->notice->tu->get_print_type(2,$opac_url_base."/index.php?lvl=titre_uniforme_see&id=" ))) {
			$this->notice_public.= 
			"<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['titre_uniforme_aff_public']."</span></td>
			<td>".$tu_liste."</td></tr>";
		}	
		// zone de la collation
		if($this->notice->npages)
		if ($this->notice->niveau_biblio<>"a") {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'   class='bg-grey$x'><b>".$msg['npages_start']."</b></td><td class='bg-grey$x'>".$this->notice->npages."</td></tr>";
		}
		else {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['npages_start_perio']."</b></td><td class='bg-grey$x'>".$this->notice->npages."</td></tr>";
		}

		if ($this->notice->ill){
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['ill_start']."</b></td><td class='bg-grey$x'>".$this->notice->ill."</td></tr>";
		}
		if ($this->notice->size){
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['size_start']."</b></td><td class='bg-grey$x'>".$this->notice->size."</td></tr>";
		}
		if ($this->notice->accomp){
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['accomp_start']."</b></td><td class='bg-grey$x'>".$this->notice->accomp."</td></tr>";
		}

		// ISBN ou NO. commercial
		if ($this->notice->code){
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['code_start']."</b></td><td class='bg-grey$x'>".$this->notice->code."</td></tr>";
		}

		if ($this->notice->prix){
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['price_start']."</b></td><td class='bg-grey$x'>".$this->notice->prix."</td></tr>";
		}

		// note générale
		if ($this->notice->n_gen) $zoneNote = nl2br(htmlentities($this->notice->n_gen,ENT_QUOTES, $charset));
		if ($zoneNote) {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['n_gen_start']."</b></td><td class='bg-grey$x'>".$zoneNote."</td></tr>";
		}

		// langues
		if (count($this->langues)) {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['537']." :</b></td><td class='bg-grey$x'>".$this->construit_liste_langues($this->langues);
			if (count($this->languesorg)) $this->notice_public .= " <b>".$msg['711']." :</b> ".$this->construit_liste_langues($this->languesorg);
			$this->notice_public.="</td></tr>";
		} else
		if (count($this->languesorg)) {
			if ($x=="2") {$x="";} else {$x="2";}
			$this->notice_public .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['711']." :</b></td><td class='bg-grey$x'>".$this->construit_liste_langues($this->languesorg)."</td></tr>";
		}

		if (!$short) $this->notice_public .= $this->aff_suite() ; else $this->notice_public.=$this->genere_in_perio();
		$this->notice_public.="</table>\n";

		if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;

		return;
	}

	function aff_suite() {
		global $msg;
		global $charset;

		// afin d'éviter de recalculer un truc déjà calculé...
		if ($this->affichage_suite) return $this->affichage_suite ;

		// serials : si article
		$ret .= $this->genere_in_perio () ;

		//Espace
		$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";

		// résumé
		$x="" ;
		if($this->notice->n_resume){
			if ($x=="2") $x=""; 
				else $x="2";
			$ret .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['n_resume_start']."</b></td><td class='bg-grey$x'>".nl2br($this->notice->n_resume)."</td></tr>";
		}

		// note de contenu
		if($this->notice->n_contenu){
			if ($x=="2") {$x="";} else {$x="2";}
			$ret .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['n_contenu_start']."</b></td><td class='bg-grey$x'>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
		}

		// Catégories
		if($this->categories_toutes){
			if ($x=="2") {$x="";} else {$x="2";}
			$ret .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['categories_start']."</b></td><td class='bg-grey$x'>".$this->categories_toutes."</td></tr>";
		}

		// indexation libre
		$mots_cles = $this->do_mots_cle() ;
		if($mots_cles){
			if ($x=="2") {$x="";} else {$x="2";}
			$ret .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['motscle_start']."</b></td><td class='bg-grey$x'>".$mots_cles."</td></tr>";
		}

		// indexation interne
		if($this->notice->indexint) {
			if ($x=="2") {$x="";} else {$x="2";}
			$indexint = new indexint($this->notice->indexint);
			$ret .= "<tr><td align='right'  class='bg-grey$x'><b>".$msg['indexint_start']."</b></td><td class='bg-grey$x'>".inslink($indexint->name,  str_replace("!!id!!", $this->notice->indexint, $this->lien_rech_indexint))." ".nl2br(htmlentities($indexint->comment,ENT_QUOTES, $charset))."</td></tr>" ;
		}

		//Champs personalisés
		$perso_aff = "" ;
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				if ($x=="2") {$x="";} else {$x="2";}
				$p=$perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]) $perso_aff .="<tr><td align='right' class='bg-grey$x'>".$p["TITRE"]."</td><td class='bg-grey$x'>".$p["AFF"]."</td></tr>";
			}
		}
		if ($perso_aff) {
			//Espace
			$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
			$ret .= $perso_aff ;
		}

		if ($this->notice->lien) {
			if ($x=="2") {$x="";} else {$x="2";}
			$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
			$ret.="<tr><td align='right'  class='bg-grey$x'><b>".$msg["lien_start"]."</b></td><td class='bg-grey$x'>" ;
			if (substr($this->notice->eformat,0,3)=='RSS') {
				$ret .= affiche_rss($this->notice->notice_id) ;
			} else {
				$ret.="<a href=\"".$this->notice->lien."\" target=\"top\">".htmlentities($this->notice->lien,ENT_QUOTES,$charset)."</a>";
			}
			$ret.="</td></tr>";
			if ($this->notice->eformat && substr($this->notice->eformat,0,3)!='RSS'){
				if ($x=="2") {$x="";} else {$x="2";}
				$ret.="<tr><td align='right'  class='bg-grey$x'><b>".$msg["eformat_start"]."</b></td><td class='bg-grey$x'>".htmlentities($this->notice->eformat,ENT_QUOTES,$charset)."</td></tr>";
			}
		}

		$this->affichage_suite = $ret ;
		return $ret ;
	}

	// fonction d'affichage des exemplaires, résa et expl_num
	function aff_resa_expl() {
		global $opac_resa ;
		global $opac_max_resa ;
		global $opac_show_exemplaires ;
		global $msg;
		global $dbh;
		global $popup_resa ;
		global $opac_resa_popup ; // la résa se fait-elle par popup ?
		global $allow_book ;
		
		// afin d'éviter de recalculer un truc déjà calculé...
		if ($this->affichage_resa_expl) return $this->affichage_resa_expl ;
		
		if ( (is_null($this->dom) &&$opac_show_exemplaires && $this->visu_expl && (!$this->visu_expl_abon || ($this->visu_expl_abon && $_SESSION["user_code"]))) || ($this->rights & 8) ) {

			$resa_check=check_statut($this->notice_id,0) ;
			// vérification si exemplaire réservable
			if ($resa_check) {
				// déplacé dans le IF, si pas visible : pas de bouton résa
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
					// utilisateur pas connecté
					// préparation lien réservation sans être connecté
					$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
					if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"w=window.open('./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
						else $ret .= "<a href='./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup' id='bt_resa'>".$msg["bulletin_display_place_resa"]."</a>" ;
					$ret .= $message_nbresa ;
					$ret .= "<br />";
					}
				}
			$temp = $this->expl_list($this->notice->niveau_biblio,$this->notice->notice_id);
			$ret .= $temp ;
			$this->affichage_expl = $temp ;
		}

		if ($this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) {
			if (($explnum = show_explnum_per_notice($this->notice_id, 0, ''))) {
				$ret .= "<h3>$msg[explnum]</h3>".$explnum;
				$this->affichage_expl .= "<h3>$msg[explnum]</h3>".$explnum;
			}
		}
		$this->affichage_resa_expl = $ret ;
		return $ret ;
	}
	
	// génération du de l'affichage double avec onglets ---------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	function genere_double($depliable=1, $premier='ISBD') {
		global $msg;
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $opac_avis_allow;
		global $opac_allow_add_tag;
		
		$basket="<img src='mw/images/commun/cale.gif' border='0' width='1' height='8'><br /><div style='float:left;'>";
		if ($this->cart_allowed) {
			$basket.="<a href='cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."' target='cart_info'><img src='mw/images/commun/basket_small_20x20.gif' border='0' align='absmiddle' title=\"".$msg[notice_title_basket]."\" alt=\"".$msg[notice_title_basket]."\">".$msg[notice_bt_panier]."</a>";
		}
		 if (($opac_avis_allow && $opac_avis_allow != 2) || ($_SESSION["user_code"] && $opac_avis_allow == 2)) {//Avis
				$basket.="&nbsp;&nbsp;<a href='#' onclick=\"javascript:open('avis.php?todo=liste&noticeid=$this->notice_id','avis','width=520,height=290,scrollbars=yes,resizable=yes')\"><img src='mw/images/commun/avis.gif' align='absmiddle' border='0'>".$msg[notice_bt_avis]."</a><br /><br />";
		}
		if (($opac_allow_add_tag==1)||(($opac_allow_add_tag==2)&&($_SESSION["user_code"]))){//add tags
				$basket.="&nbsp;&nbsp;<a href='#' onclick=\"javascript:open('addtags.php?noticeid=$this->notice_id','Ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes')\"><img src='mw/images/commun/tag.gif'align='absmiddle' border='0'>".$msg[notice_bt_tag]."</a>";
		}
		if ((!$this->cart_allowed)&&($opac_avis_allow==0)) {
			$basket.="";
		}
		$basket.="</div><br /><br />";
		
		
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
			else $case_a_cocher = "" ;
	
		if ($this->notice->niveau_biblio=="s") 
				$icon="icon_per_16x16.gif";
			elseif ($this->notice->niveau_biblio=="a")
				$icon="icon_art_16x16.gif";
			else
				$icon="icon_".$this->notice->typdoc."_16x16.gif";	
	
		if ($depliable) {
			$template="
			<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_plus' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['expandable_notice']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\">";
			if ($icon) $template.="
					<img src=\"images/$icon\" />";
			$template.="		
				<span class=\"notice-heada\">!!heada!!</span>
	    		<br />
				</div>
			<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">";
		} else {
			$template="<div class='parent'>
							$case_a_cocher";
			if ($icon) $template.="
					<img src=\"images/$icon\" />";
			$template.="
							<span class=\"notice-heada\">!!heada!!</span>";
		}
		$template.=$basket;
		$template.="<ul id='onglets_isbd_public'>";
	    if ($premier=='ISBD') $template.="
	    	<li id='onglet_isbd!!id!!' class='isbd_public_active'><a href='#' onclick=\"show_what('ISBD', '!!id!!'); return false;\">ISBD</a></li>
	    	<li id='onglet_public!!id!!' class='isbd_public_inactive'><a href='#' onclick=\"show_what('PUBLIC', '!!id!!'); return false;\">Public</a></li>
			</ul>
			<div id='div_isbd!!id!!' style='display:block;'>!!ISBD!!</div>
	  		<div id='div_public!!id!!' style='display:none;'>!!PUBLIC!!</div>";
	  		else $template.="
	  			<li id='onglet_public!!id!!' class='isbd_public_active'><a href='#' onclick=\"show_what('PUBLIC', '!!id!!'); return false;\">Public</a></li>
				<li id='onglet_isbd!!id!!' class='isbd_public_inactive'><a href='#' onclick=\"show_what('ISBD', '!!id!!'); return false;\">ISBD</a></li>
	    		</ul>
				<div id='div_public!!id!!' style='display:block;'>!!PUBLIC!!</div>
	  			<div id='div_isbd!!id!!' style='display:none;'>!!ISBD!!</div>";
		
		
	 	$template.="</div>";
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
			$template = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='mw/index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!ISBD!!", $template);
			$template = str_replace('!!PUBLIC!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='mw/index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!PUBLIC!!", $template);
			} elseif ($this->notice->niveau_biblio =='a') { 
				$template = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!ISBD!!", $template);
				$template = str_replace('!!PUBLIC!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!PUBLIC!!", $template);
			}
		
		$this->result = str_replace('!!id!!', $this->notice_id, $template);
		$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		$this->do_image($this->notice_isbd,$depliable);
		$this->result = str_replace('!!ISBD!!', $this->notice_isbd, $this->result);
		$this->do_image($this->notice_public,$depliable);
		$this->result = str_replace('!!PUBLIC!!', $this->notice_public, $this->result);
	}

	// génération de l'affichage simple sans onglet ----------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	function genere_simple($depliable=1, $what='ISBD') {
		global $msg; 
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $opac_avis_allow;
		global $opac_allow_add_tag;
		
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
			else $case_a_cocher = "" ;
		
		$basket="<img src='mw/images/commun/cale.gif' border='0' width='1' height='8'><br /><div style='float:left;'>";
		if ($this->cart_allowed) {
			$basket.="<a href='cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."' target='cart_info'><img src='mw/images/commun/basket_small_20x20.gif' border='0' align='absmiddle' title=\"".$msg[notice_title_basket]."\" alt=\"".$msg[notice_title_basket]."\">".$msg[notice_bt_panier]."</a>";
		}
		if ($opac_avis_allow){	//Avis
				$basket.="&nbsp;&nbsp;<a href='#' onclick=\"javascript:open('avis.php?todo=liste&noticeid=$this->notice_id','avis','width=520,height=290,scrollbars=yes,resizable=yes')\"><img src='mw/images/commun/avis.gif' align='absmiddle' border='0'>".$msg[notice_bt_avis]."</a>";
		}
		if (($opac_allow_add_tag==1)||(($opac_allow_add_tag==2)&&($_SESSION["user_code"]))){//add tags
				$basket.="&nbsp;&nbsp;<a href='#' onclick=\"javascript:open('addtags.php?noticeid=$this->notice_id','Ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes')\"><img src='mw/images/commun/tag.gif' align='absmiddle' border='0'>".$msg[notice_bt_tag]."</a>";
		}
		if ((!$this->cart_allowed)&&($opac_avis_allow==0)) {
			 	$basket.="";
		}
		$basket.="</div><br /><br />";
		
		if ($this->notice->niveau_biblio=="s") 
				$icon="icon_per_16x16.gif";
			elseif ($this->notice->niveau_biblio=="a")
				$icon="icon_art_16x16.gif";
			else
				$icon="icon_".$this->notice->typdoc."_16x16.gif";	
	
		if ($depliable) { 
			$template="
			<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_plus' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg["expandable_notice"]."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\">";
			if ($icon) $template.="
					<img src=\"images/$icon\" />";
			$template.="
	    		<span class=\"notice-heada\">!!heada!!</span><br />
	    		</div>			
			<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">".$basket."!!ISBD!!</div>";
		}
			else {
				$template="
				\n<div id=\"el!!id!!Parent\" class=\"parent\">
	    				$case_a_cocher";
				if ($icon) $template.="
					<img src=\"images/$icon\" />";
				$template.="
	    				<span class=\"heada\">!!heada!!</span><br />
		    			</div>			
				\n<div id='el!!id!!Child' class='child' >".$basket."
				!!ISBD!!
				\n</div>";
		}
			
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
			$template = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='mw/index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!ISBD!!", $template);
		} elseif ($this->notice->niveau_biblio =='a') { 
			$template = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!ISBD!!", $template);
		}
		
		$this->result = str_replace('!!id!!', $this->notice_id, $template);
		$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		
		if ($what=='ISBD') {
			$this->do_image($this->notice_isbd,$depliable);
			$this->result = str_replace('!!ISBD!!', $this->notice_isbd, $this->result);
		} else {
			$this->do_image($this->notice_public,$depliable);
			$this->result = str_replace('!!ISBD!!', $this->notice_public, $this->result);
		}
	}
}

class notice_affichage_categ_regroup extends notice_affichage {
	
	// récupération des categories ------------------------------------------------------------------
	function fetch_categories() {
		global $opac_categories_affichage_ordre;
		$this->categories = get_notice_categories($this->notice_id) ;
		// catégories
		$categ_repetables=array() ;
		$max_categ = sizeof($this->categories) ; 
		for ($i = 0 ; $i < $max_categ ; $i++) {
			$categ_id = $this->categories[$i]["categ_id"] ;
			$categ = new category($categ_id);
			$categ_repetables[$categ->thes->libelle_thesaurus][$categ_id] = $categ->libelle;
			//$categ_repetables[$categ_id] = $categ->catalog_form;
		}
		$categ_final_table=array();    
        while (list($key,$val)=each($categ_repetables)) {
        	$categ_final_table[$key]=$key;
			if ($opac_categories_affichage_ordre!="1")
				asort($val) ;
        	reset($val);
        	$categ_r=array();
            while (list($categ_id,$libelle)=each($val)) {
            	$categ_r[$categ_id] = inslink($libelle,  str_replace("!!id!!", $categ_id, $this->lien_rech_categ));
            }
            $categ_final_table[$key].="<br />&nbsp;".implode(", ",$categ_r);
        }
		$this->categories_toutes = implode("<br />",$categ_final_table) ;
	}
}

class notice_affichage_epires extends notice_affichage {
	
	// récupération des categories ------------------------------------------------------------------
	function fetch_categories() {
		$this->categories = get_notice_categories($this->notice_id) ;
		// catégories
		$categ_repetables=array() ;
		$max_categ = sizeof($this->categories) ; 
		for ($i = 0 ; $i < $max_categ ; $i++) {
			$categ_id = $this->categories[$i]["categ_id"] ;
			$categ = new category($categ_id);
			$categ_repetables[$categ->path_table[0]["libelle"]][$categ_id] = $categ->libelle;
			}
		$categ_final_table=array();
		while (list($key,$val)=each($categ_repetables)) {
			$categ_final_table[$key]=$key;
			asort($val) ;
			reset($val);
			$categ_r=array();
			while (list($categ_id,$libelle)=each($val)) {
				$categ_r[$categ_id] = inslink($libelle,  str_replace("!!id!!", $categ_id, $this->lien_rech_categ));
				}
			$categ_final_table[$key].="<br />&nbsp;".implode(", ",$categ_r);
			}
		$this->categories_toutes = implode("<br />",$categ_final_table) ;
		}
	}
	
class notice_affichage_id extends notice_affichage {
	
	
	function aff_suite() {	
		global $msg;
		global $charset;
		
		if ($this->affichage_suite) return $this->affichage_suite ;
		
		$ret=parent::aff_suite();
		$ret.= "<tr><td align='right' class='bg-grey'><b>".$msg["notice_id_start"]."</b></td><td>".htmlentities($this->notice_id,ENT_QUOTES, $charset)."</td></tr>";
		$this->affichage_suite=$ret;
		return $ret ;
	}
}

// Demande CNL affichage de trouver le livre près de chez vous http://www.placedeslibraires.fr/detaillivre.php?gencod= isbn
class notice_affichage_placedeslibraires extends notice_affichage {
	
	
	function aff_suite() {	
		global $msg;
		global $charset;
		
		if ($this->affichage_suite) return $this->affichage_suite ;
		$link="<a href='http://www.placedeslibraires.fr/detaillivre.php?gencod=".htmlentities(str_replace("-","",$this->notice->code),ENT_QUOTES, $charset)."'><i>".$msg["notice_trouver_le_livre"]."</i></a>";
		$ret=parent::aff_suite();
		$ret.= "<tr><td align='right' class='bg-grey'><b>".$msg["notice_librairie"]."</b></td><td>".$link."</td></tr>";
		$this->affichage_suite=$ret;
		return $ret ;
	}
}

// Demande Livr'Jeunes Nantes
class notice_affichage_livrjeunes extends notice_affichage {

	function genere_double($depliable=1, $premier='ISBD') {
		$this->genere_simple($depliable, 'PUBLIC');
	}
	
	function genere_simple($depliable=1, $what='ISBD') {
		global $msg; 
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $icon_doc;
		global $allow_tag ; // l'utilisateur a-t-il le droit d'ajouter un tag
		
		$this->double_ou_simple = 1 ;
		$this->notice_childs = $this->genere_notice_childs();
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
		
		if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\" class=\"img_basket\"><img src='images/basket_small_20x20.gif' align='absmiddle' border='0' title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
		else $basket="";
		
		//add tags
		if (($this->tag_allowed==1)||(($this->tag_allowed==2)&&($_SESSION["user_code"])&&($allow_tag)))
			$img_tag.="&nbsp;&nbsp;<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='images/tag.png' align='absmiddle' border='0' title=\"".$msg['notice_title_tag']."\" alt=\"".$msg['notice_title_tag']."\"></a>&nbsp;&nbsp;";
		
		// LivrJeunes : avis supprimés d'ici + what=PUBLIC
		$what='PUBLIC';
				 
		if ($basket) $basket="<div>".$basket.$img_tag."</div>";
	
		$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		
		if ($depliable) { 
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_plus' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg["expandable_notice"]."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\">";
			if ($icon) $template.="
					<img src=\"images/$icon\" />";
			$template.="
	    		<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span><br />
	    		</div>			
			<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">".$basket."!!ISBD!!\n
				!!SUITE!!
				</div>";
		} else {
			$template="
				\n<div id=\"el!!id!!Parent\" class=\"parent\">
	    				$case_a_cocher";
			if ($icon) $template.="
					<img src=\"images/$icon\" />";
			$template.="
	    				<span class=\"heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span><br />
		    			</div>			
				\n<div id='el!!id!!Child' class='child' >".$basket."
				!!ISBD!!
				!!SUITE!!
				</div>";
		}
			
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
			$template = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!ISBD!!", $template);
		} elseif ($this->notice->niveau_biblio =='a') { 
			$template = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;!!ISBD!!", $template);
		} elseif ($this->notice->niveau_biblio =='b') { 
			$template_in = str_replace('!!ISBD!!', "<span class='fond-article'>[".$msg['isbd_type_bul']."]</span>&nbsp;!!ISBD!!", $template_in);
		}
		
		$this->result = str_replace('!!id!!', $this->notice_id, $template);
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
		
		$this->aff_resa_expl();
		global $action;
		if ($action=="print") {
			$this->notice_public .= $this->affichage_resa_expl ;
		}
	}
	
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

		// afin d'éviter de recalculer un truc déjà calculé...
		if ($this->affichage_resa_expl) return $this->affichage_resa_expl ;
		
		if (($avis_en_bas = $this->avis_detail())) {
			$ret = $avis_en_bas;
		}
		if ( (is_null($this->dom) && $opac_show_exemplaires && $this->visu_expl && (!$this->visu_expl_abon || ($this->visu_expl_abon && $_SESSION["user_code"]))) || ($this->rights & 8) ) {
			$temp = $this->expl_list($this->notice->niveau_biblio,$this->notice->notice_id, $this->bulletin_id);
			$ret .= $temp ;
			$this->affichage_expl = $ret ; 
		}
		if ($this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) 	
			if ($this->notice->niveau_biblio=="b" && ($explnum = show_explnum_per_notice(0, $this->bulletin_id, ''))) {
				$ret .= "<h3>$msg[explnum]</h3>".$explnum;
				$this->affichage_expl .= "<h3>$msg[explnum]</h3>".$explnum;
			} elseif (($explnum = show_explnum_per_notice($this->notice_id,0, ''))) {
				$ret .= "<h3>$msg[explnum]</h3>".$explnum;
				$this->affichage_expl .= "<h3>$msg[explnum]</h3>".$explnum;
			} 
		if (($autres_lectures = $this->autres_lectures($this->notice_id,$this->bulletin_id))) {
			$ret .= $autres_lectures;
		}
		$this->affichage_resa_expl = $ret ;
		return $ret ;
	} 
	
// fontion qui génère le bloc H3 + table des avis détaillés
function avis_detail () {
	global $dbh, $msg;
	global $action; // pour gérer l'affichage des avis en impression de panier
	
	$sql_avis = "select note, commentaire, sujet from avis where num_notice='$this->notice_id' and valide=1 order by note desc, id_avis desc";
	$r_avis = mysql_query($sql_avis, $dbh) or die ("<br />".mysql_error()."<br />".$sql_avis."<br />");	
	
	$sql_avisnb = "select note, count(id_avis) as nb_by_note from avis where num_notice='$this->notice_id' and valide=1 group by note ";
	$r_avisnb = mysql_query($sql_avisnb, $dbh) or die ("<br />".mysql_error()."<br />".$sql_avisnb."<br />");
	while ($datanb=mysql_fetch_object($r_avisnb)) 
		$rowspan[$datanb->note]=$datanb->nb_by_note ;
		
	if (mysql_num_rows($r_avis)) {
		// comptage des avis par note afin de mettre les bons rowspan
		$odd_even=1;
		$note_conserve=-1;
		$ret="";
		while (($data=mysql_fetch_object($r_avis))) { 
			// on affiche les résultats 
			if ($note_conserve!=$data->note) {
				if ($odd_even==0) {
					$pair_impair="odd";
					$odd_even=1;
				} else if ($odd_even==1) {
					$pair_impair="even";
					$odd_even=0;
				}
				$categ_avis=$msg['avis_detail_note_'.$data->note];
				$note_conserve=$data->note;
				$tr_javascript=" class='$pair_impair' ";
				$ret .= "<tr $tr_javascript>";
				$ret .= "<td class='avis_detail_note_".$data->note."' width='15%' rowspan='".$rowspan[$data->note]."'>".$categ_avis."</td>";
			} else {
				$categ_avis='&nbsp;';
				$ret .= "<tr $tr_javascript>";
			}
			    
			$ret .= "<td class='avis_detail_commentaire_".$data->note."'>".$data->commentaire."
					<br />
					<span class='avis_detail_signature'>".$data->sujet."</span></td>";    		
			$ret .= "</tr>\n";
			
		}

		if ($action=="print") {
			$ret = "<h3 class='avis_detail'>".$msg['avis_detail']."
				</h3>
				<table style='width:100%;'>".$ret."</table>";
		} else {
			$ret = "<h3 class='avis_detail'>".$msg['avis_detail']."
					<span class='lien_ajout_avis'> : 
						<a href='#' onclick=\"open('avis.php?todo=add&noticeid=$this->notice_id','avis','width=600,height=290,scrollbars=yes,resizable=yes'); return false;\">".str_replace("!!nb_avis!!",$this->avis_qte,$msg['avis_detail_nb_ajt'])."</a>
					</span></h3>
					<table style='width:100%;'>".$ret."</table>";
		}
	} else {
		if ($action=="print") {
			$ret = "<h3 class='avis_detail'>".$msg['avis_detail_aucun_ajt']."
				</h3>";
		} else {
			$ret="<h3 class='avis_detail'>".$msg['avis_detail']."
					<span class='lien_ajout_avis'>
						<a href='#' onclick=\"open('avis.php?todo=add&noticeid=$this->notice_id','avis','width=600,height=290,scrollbars=yes,resizable=yes'); return false;\">".$msg['avis_detail_aucun_ajt']."</a>
					</span></h3>" ;
		}
	}
	return $ret;
}
	
	

}


// abiodoc >> generation des liens vers la boutique openstudio
class notice_affichage_abiodoc extends notice_affichage {
	
	var $explnum_shoplink="./../boutique/produit.php?ref=";
	var $abiodoc_app_val="";
	var $abiodoc_app_lib="";
	
	// génération du de l'affichage double avec onglets ---------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	function genere_double($depliable=1, $premier='ISBD') {
		global $msg;
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $icon_doc,$biblio_doc,$tdoc;
		global $allow_tag; // l'utilisateur a-t-il le droit d'ajouter un tag
		$this->result ="";
		if(!$this->notice_id) return;	

		$this->get_abiodoc_app();
		
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
		if (($this->sugg_allowed ==2)|| ($_SESSION["user_code"] && $this->sugg_allowed ==1)) $img_tag .= $this->affichage_suggestion($this->notice_id);
		
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
	
		$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		if ($depliable) {
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\"><div class=\"notice-parent_abiodoc_col1\" >
				$case_a_cocher
	    		<img class='img_plus".$this->abiodoc_app_val."' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['expandable_notice']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\" />";
			$template.="<span class='abiodoc_app".$this->abiodoc_app_val."'>".htmlentities($this->abiodoc_app_lib,ENT_QUOTES,$charset)."</span></div><div class=\"notice-parent_abiodoc_col2\" >";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>
				</div>
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">";
		} else {
			$template="<div class='parent'>$case_a_cocher";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
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
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
		if($this->notice->opac_visible_bulletinage) $voir_bulletins="&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>";
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
		$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
		if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result); 		
		$this->result = str_replace('!!SUITE!!', "", $this->result);
	}
	
	// génération du de l'affichage simple sans onglet ----------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	function genere_simple($depliable=1, $what='ISBD') {
		global $msg; 
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $icon_doc,$biblio_doc,$tdoc;
		global $allow_tag ; // l'utilisateur a-t-il le droit d'ajouter un tag
		if(!$this->notice_id) return;
		
		$this->get_abiodoc_app();
		
		$this->double_ou_simple = 1 ;
		$this->notice_childs = $this->genere_notice_childs();
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
		
		if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\" class=\"img_basket\"><img src='images/basket_small_20x20.gif' align='absmiddle' border='0' title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
		else $basket="";
		
		//add tags
		if (($this->tag_allowed==1)||(($this->tag_allowed==2)&&($_SESSION["user_code"])&&($allow_tag)))
			$img_tag.="<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='images/tag.png' align='absmiddle' border='0' title=\"".$msg['notice_title_tag']."\" alt=\"".$msg['notice_title_tag']."\"></a>";
		
		 //Avis
		if (($this->avis_allowed && $this->avis_allowed !=2)|| ($_SESSION["user_code"] && $this->avis_allowed ==2))
			$img_tag .= $this->affichage_avis($this->notice_id);
		
		//Suggestions
		if (($this->sugg_allowed ==2)|| ($_SESSION["user_code"] && $this->sugg_allowed ==1)) $img_tag .= $this->affichage_suggestion($this->notice_id);	
		 
		$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		
		if ($depliable) { 
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\"><div class=\"notice-parent_abiodoc_col1\" >
				$case_a_cocher
	    		<img class='img_plus".$this->abiodoc_app_val."' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg["expandable_notice"]."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\"/>";
			$template.="<span class='abiodoc_app".$this->abiodoc_app_val."'>".htmlentities($this->abiodoc_app_lib,ENT_QUOTES,$charset)."</span></div><div class=\"notice-parent_abiodoc_col2\" >";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">
	    		";			
		} else {
			$template="
				\n<div id=\"el!!id!!Parent\" class=\"parent\">
	    		$case_a_cocher";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}			
    		$template.="<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span><br />";
		}
		$template.="!!CONTENU!!
					!!SUITE!!</div>";
		
		if($basket || $img_tag) $template_in.="<ul id='onglets_isbd_public'>
						<li id='baskets!!id!!' class='onglet_basket'>$basket</li>
	  					<li id='tags!!id!!' class='onglet_tags'>$img_tag</li>
					   </ul>	
		";
		if($what =='ISBD') $template_in.="		    	
				<div id='div_isbd!!id!!' style='display:block;'>!!ISBD!!</div>
	  			<div id='div_public!!id!!' style='display:none;'>!!PUBLIC!!</div>";
		else $template_in.="
		    	<div id='div_public!!id!!' style='display:block;'>!!PUBLIC!!</div>
				<div id='div_isbd!!id!!' style='display:none;'>!!ISBD!!</div>"
	  			; 	
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
			$template_in = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!ISBD!!", $template_in);
			$template_in = str_replace('!!PUBLIC!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!PUBLIC!!", $template_in);
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
		if($this->notice_header)
			$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		else $this->result = str_replace('!!heada!!', '', $this->result);
		$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
		
		if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result);
		else $this->result = str_replace('!!SUITE!!', '', $this->result);
				
	}
	
	
	function get_abiodoc_app() {
		global $dbh;
		$q="select v.notices_custom_integer,l.notices_custom_list_lib from notices_custom_values v join notices_custom_lists l on v.notices_custom_champ=l.notices_custom_champ and v.notices_custom_integer=l.notices_custom_list_value where v.notices_custom_champ='27' and v.notices_custom_origine='".$this->notice_id."'  limit 1 ";
		$r=mysql_query($q, $dbh);
		$result=array();
		if (mysql_num_rows($r)) {
			$this->abiodoc_app_val= mysql_result($r,0,0);
			$this->abiodoc_app_lib= mysql_result($r,0,1);
		}
	}
	
	// génération du header----------------------------------------------------
	function do_header() {
		
		global $charset;
		global $opac_notice_reduit_format ;
		global $opac_url_base, $msg ;
		
		
		$type_reduit = substr($opac_notice_reduit_format,0,1);
		if ($type_reduit=="E" || $type_reduit=="P" ) {
			// peut-être veut-on des personnalises ?
			$perso_voulus_temp = substr($opac_notice_reduit_format,2) ;
			if ($perso_voulus_temp!="") $perso_voulus = explode(",",$perso_voulus_temp);
		}
		
		if ($type_reduit=="E") {
			// zone de l'editeur 
			if ($this->notice->ed1_id) {
				$editeur = new publisher($this->notice->ed1_id);
				$editeur_reduit = $editeur->display ;
				if ($this->notice->year) $editeur_reduit .= " - ".$this->notice->year." ";
			} elseif ($this->notice->year) { 
				// année mais pas d'éditeur et si pas un article
				if($this->notice->niveau_biblio != 'a' && $this->notice->niveau_hierar != 2) 	$editeur_reduit = $this->notice->year." ";
			}
		} else $editeur_reduit = "" ;
		
		//Champs personnalises a ajouter au reduit 
		if (!$this->p_perso->no_special_fields) {
			if (count($perso_voulus)) {
				$this->p_perso->get_values($this->notice_id) ;
				for ($i=0; $i<count($perso_voulus); $i++) {
					$perso_voulu_aff .= $this->p_perso->get_formatted_output($this->p_perso->values[$perso_voulus[$i]],$perso_voulus[$i])." " ;
				}
			} else $perso_voulu_aff = "" ;
		} else $perso_voulu_aff = "" ;
		
		//Si c'est un périodique, ajout du titre et bulletin
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2)  {
			 $aff_perio_title="<i>in ".$this->parent_title.", ".$this->parent_numero." (".($this->parent_date?$this->parent_date:"[".$this->parent_aff_date_date."]").")</i>";
		}
		
		//Si c'est une notice de bulletin ajout du titre et bulletin
		if($this->notice->niveau_biblio == 'b' && $this->notice->niveau_hierar == 2)  {
			$aff_bullperio_title = "<span class='isbulletinof'><i> ".($this->parent_date?sprintf($msg["bul_titre_perio"],$this->parent_title):sprintf($msg["bul_titre_perio"],$this->parent_title.", ".$this->parent_numero." [".$this->parent_aff_date_date."]"))."</i></span>";
		} else $aff_bullperio_title="";
		
		// récupération du titre de série
		// constitution de la mention de titre
		if($this->notice->serie_name) {
			$this->notice_header = $this->notice->serie_name;
			if($this->notice->tnvol) $this->notice_header .= $this->notice->tnvol;
		} elseif ($this->notice->tnvol) $this->notice_header .= $this->notice->tnvol;
		
		if ($this->notice_header) $this->notice_header .= ". ".$this->notice->tit1 ;
		else $this->notice_header = $this->notice->tit1;
		
		$this->notice_header .= $aff_bullperio_title;
		$this->notice_header_without_html = $this->notice_header;	
		$this->notice_header = "<span class='header_title'>".$this->notice_header."</span>";	
				
		$notice_header_suite = "";
		if ($type_reduit=="T" && $this->notice->tit4) $notice_header_suite = " : ".$this->notice->tit4;
		if ($this->auteurs_principaux) $notice_header_suite .= " / ".$this->auteurs_principaux;
		if ($editeur_reduit) $notice_header_suite .= " / ".$editeur_reduit ;
		if ($perso_voulu_aff) $notice_header_suite .= " / ".$perso_voulu_aff ;
		if ($aff_perio_title) $notice_header_suite .= " ".$aff_perio_title;
		$this->notice_header_without_html .= $notice_header_suite ;
		$this->notice_header .= $notice_header_suite."</span>"  ;	
		$this->notice_header_with_link=inslink($this->notice_header, str_replace("!!id!!", $this->notice_id, $this->lien_rech_notice)) ;
		
		if ($this->notice->lien) {
			// ajout du lien pour les ressources electroniques
			$this->notice_header .= "&nbsp;<span><a href=\"".$this->notice->lien."\" target=\"__LINK__\">";
			$this->notice_header .= "<img src=\"images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
			$this->notice_header .= " alt=\"";
			$this->notice_header .= $this->notice->eformat;
			$this->notice_header .= "\" title=\"";
			$this->notice_header .= $this->notice->eformat;
			$this->notice_header .= "\">";
			$this->notice_header .= "</a></span>";
		} 
		
		if ($this->notice->niveau_biblio == 'b') {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_statut FROM explnum, bulletins WHERE bulletins.num_notice = ".$this->notice_id." AND bulletins.bulletin_id = explnum.explnum_bulletin order by explnum_id";
		} else {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_statut FROM explnum WHERE explnum_notice = ".$this->notice_id." order by explnum_id";
		}
		
		$explnums = mysql_query($sql_explnum);
		$explnumscount = mysql_num_rows($explnums);
		
		if ($explnumscount == 1) {
			$explnumrow = mysql_fetch_object($explnums);
			
			if ($explnumrow->explnum_statut==1) {	//document payant
				$this->notice_header .= "&nbsp;<span><a href=\"".$this->explnum_shoplink.$explnumrow->explnum_id."\" target=\"__LINK__\">";
				$this->notice_header .= "<img src=\"images/icon_k_16x16.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
			} else {								//document gratuit
				$this->notice_header .= "&nbsp;<span><a href=\"doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">";
				$this->notice_header .= "<img src=\"images/globe_orange.png\" border=\"0\" align=\"middle\" hspace=\"3\"";
			}
			
			$this->notice_header .= " alt=\"";
			$this->notice_header .= htmlentities($explnumrow->explnum_nom, ENT_QUOTES, $charset);
			$this->notice_header .= "\" title=\"";
			$this->notice_header .= htmlentities($explnumrow->explnum_nom, ENT_QUOTES, $charset);
			$this->notice_header .= "\">";
			$this->notice_header .= "</a></span>";
		} elseif ($explnumscount > 1) {
			$explnumrow = mysql_fetch_object($explnums);
			$this->notice_header .= "<img src=\"images/globe_rouge.png\" border=\"0\" align=\"middle\" hspace=\"3\">";
		}
		
	}

			
	// fonction d'affichage des exemplaires numeriques
	function aff_explnum () {
		
		global $msg;
		$ret='';
		if ( (is_null($this->docnum) && $this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) || ($this->rights & 16) ) {
			if ($this->notice->niveau_biblio=="b" && ($explnum = $this->show_explnum_per_notice(0, $this->bulletin_id))) {
				$ret .= "<h3>$msg[explnum]</h3>".$explnum;
				$this->affichage_expl .= "<h3>$msg[explnum]</h3>".$explnum;
			} elseif (($explnum = $this->show_explnum_per_notice($this->notice_id,0))) {
				$ret .= "<h3>$msg[explnum]</h3>".$explnum;
				$this->affichage_expl .= "<h3>$msg[explnum]</h3>".$explnum;
			}
		}		 
		return $ret;
	}
	
		
	// fonction retournant les infos d'exemplaires numeriques pour une notice ou un bulletin donne
	function show_explnum_per_notice($no_notice, $no_bulletin) {
		
		// params :
		// $link_expl= lien associe a l'exemplaire avec !!explnum_id!! a mettre à jour
		global $dbh;
		global $charset;
		global $opac_url_base ;
		
		if (!$no_notice && !$no_bulletin) return "";
		
		global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
		create_tableau_mimetype() ;
		
		// récupération du nombre d'exemplaires
		$requete = "SELECT * FROM explnum WHERE ";
		if ($no_notice && !$no_bulletin) {
			$requete .= "explnum_notice='$no_notice' ";
		} elseif (!$no_notice && $no_bulletin) {
			$requete .= "explnum_bulletin='$no_bulletin' ";
		} elseif ($no_notice && $no_bulletin) {
			$requete .= "explnum_bulletin='$no_bulletin' or explnum_notice='$no_notice' ";
		}
		$requete .= " order by explnum_mimetype, explnum_id ";
		$res = mysql_query($requete, $dbh);
		$nb_ex = mysql_num_rows($res);
		
		if($nb_ex) {
			// on récupère les données des exemplaires
			$i = 1 ;
			while (($expl = mysql_fetch_object($res))) {
				if ($i==1) {
					$ligne="<tr><td class='docnum' width='33%'>!!1!!</td><td class='docnum' width='33%'>!!2!!</td><td class='docnum' width='33%'>!!3!!</td></tr>" ;
				}
				$alt = htmlentities($expl->explnum_nom." - ".$expl->explnum_mimetype,ENT_QUOTES, $charset);
				
				if ($expl->explnum_vignette) {
					$obj="<img src='vig_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' border='0'>";
				} else {// trouver l'icone correspondant au mime_type
					$obj="<img src='images/mimetype/".icone_mimetype($expl->explnum_mimetype, $expl->explnum_extfichier)."' alt='$alt' title='$alt' border='0'>";
				}		
				$expl_liste_obj = "<center>";
				
				if ($expl->explnum_statut==1) {	//document payant
					$expl_liste_obj .= "<a href='".$this->explnum_shoplink.$expl->explnum_id."' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;
				} else {						//document gratuit
					$expl_liste_obj .= "<a href='doc_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;
				}
				
				if ($_mimetypes_byext_[$expl->explnum_extfichier]["label"]) {
					$explmime_nom = $_mimetypes_byext_[$expl->explnum_extfichier]["label"] ;
				} elseif ($_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"]) {
					$explmime_nom = $_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"] ;
				} else {
					$explmime_nom = $expl->explnum_mimetype ;
				}
				
				$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."<div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
				$expl_liste_obj .= "</center>";
				$ligne = str_replace("!!$i!!", $expl_liste_obj, $ligne);
				$i++;
				if ($i==4) {
					$ligne_finale .= $ligne ;
					$i=1;
				}
			}
			if (!$ligne_finale) $ligne_finale = $ligne ;
				elseif ($i!=1) $ligne_finale .= $ligne ;
			$ligne_finale = str_replace('!!2!!', "&nbsp;", $ligne_finale);
			$ligne_finale = str_replace('!!3!!', "&nbsp;", $ligne_finale);
			
		} else return "";
		$entry .= "<table class='docnum'>$ligne_finale</table>";
		return $entry;
	
	}
		

}

// pour affichage du descriptif du produit dans la boutique
class notice_affichage_abiodoc_boutique extends notice_affichage {
	
	var $explnum_shoplink="./../boutique/produit.php?ref=";
	
	// génération du header----------------------------------------------------
	function do_header() {
		
		global $charset;
		global $opac_notice_reduit_format ;
		global $opac_url_base, $msg ;
		
		$type_reduit = substr($opac_notice_reduit_format,0,1);
		if ($type_reduit=="E" || $type_reduit=="P" ) {
			// peut-être veut-on des personnalises ?
			$perso_voulus_temp = substr($opac_notice_reduit_format,2) ;
			if ($perso_voulus_temp!="") $perso_voulus = explode(",",$perso_voulus_temp);
		}
		
		if ($type_reduit=="E") {
			// zone de l'editeur 
			if ($this->notice->ed1_id) {
				$editeur = new publisher($this->notice->ed1_id);
				$editeur_reduit = $editeur->display ;
				if ($this->notice->year) $editeur_reduit .= " - ".$this->notice->year." ";
			} elseif ($this->notice->year) { 
				// année mais pas d'éditeur et si pas un article
				if($this->notice->niveau_biblio != 'a' && $this->notice->niveau_hierar != 2) 	$editeur_reduit = $this->notice->year." ";
			}
		} else $editeur_reduit = "" ;
		
		//Champs personnalises a ajouter au reduit 
		if (!$this->p_perso->no_special_fields) {
			if (count($perso_voulus)) {
				$this->p_perso->get_values($this->notice_id) ;
				for ($i=0; $i<count($perso_voulus); $i++) {
					$perso_voulu_aff .= $this->p_perso->get_formatted_output($this->p_perso->values[$perso_voulus[$i]],$perso_voulus[$i])." " ;
				}
			} else $perso_voulu_aff = "" ;
		} else $perso_voulu_aff = "" ;
		
		//Si c'est un périodique, ajout du titre et bulletin
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2)  {
			 $aff_perio_title="<i>in ".$this->parent_title.", ".$this->parent_numero." (".($this->parent_date?$this->parent_date:"[".$this->parent_aff_date_date."]").")</i>";
		}
		
		//Si c'est une notice de bulletin ajout du titre et bulletin
		if($this->notice->niveau_biblio == 'b' && $this->notice->niveau_hierar == 2)  {
			$aff_bullperio_title = "<span class='isbulletinof'><i> ".($this->parent_date?sprintf($msg["bul_titre_perio"],$this->parent_title):sprintf($msg["bul_titre_perio"],$this->parent_title.", ".$this->parent_numero." [".$this->parent_aff_date_date."]"))."</i></span>";
		} else $aff_bullperio_title="";
		
		// récupération du titre de série
		// constitution de la mention de titre
		if($this->notice->serie_name) {
			$this->notice_header = $this->notice->serie_name;
			if($this->notice->tnvol) $this->notice_header .= $this->notice->tnvol;
		} elseif ($this->notice->tnvol) $this->notice_header .= $this->notice->tnvol;
		
		if ($this->notice_header) $this->notice_header .= ". ".$this->notice->tit1 ;
		else $this->notice_header = $this->notice->tit1;
		
		$this->notice_header .= $aff_bullperio_title;
		$this->notice_header_without_html = $this->notice_header;	
		$this->notice_header = "<span class='header_title'>".$this->notice_header."</span>";	
				
		$notice_header_suite = "";
		if ($type_reduit=="T" && $this->notice->tit4) $notice_header_suite = " : ".$this->notice->tit4;
		if ($this->auteurs_principaux) $notice_header_suite .= " / ".$this->auteurs_principaux;
		if ($editeur_reduit) $notice_header_suite .= " / ".$editeur_reduit ;
		if ($perso_voulu_aff) $notice_header_suite .= " / ".$perso_voulu_aff ;
		if ($aff_perio_title) $notice_header_suite .= " ".$aff_perio_title;
		$this->notice_header_without_html .= $notice_header_suite ;
		$this->notice_header .= $notice_header_suite."</span>"  ;	
		$this->notice_header_with_link=inslink($this->notice_header, str_replace("!!id!!", $this->notice_id, $this->lien_rech_notice)) ;
		
	}

	// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour éviter la redondance de calcul
	function aff_suite() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search, $opac_permalink, $opac_url_base;
		
		// afin d'éviter de recalculer un truc déjà calculé...
		if ($this->affichage_suite_flag) return $this->affichage_suite ;
		
		$ret .= $this->genere_in_perio () ;
		
		// résumé
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td>".nl2br($this->notice->n_resume)."</td></tr>";
	
		// note de contenu
		if($this->notice->n_contenu) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
	
		$this->affichage_suite = $ret ;
		$this->affichage_suite_flag = 1 ;
		return $ret ;
	} 
	
			
	// fonction d'affichage des exemplaires numeriques
	function aff_explnum () {
		
		return "";
	}
		
	function aff_resa_expl () {
		
		return "";
	}
	
	function show_explnum_per_notice($no_notice, $no_bulletin) {
		return "";
	
	}

}

// prao >> authentification sur kportal pour les resas
class notice_affichage_prao extends notice_affichage {
	
	function aff_resa_expl() {

		global $opac_resa_popup;
		
		parent::aff_resa_expl();
		$this->affichage_resa_expl=str_replace("do_resa.php?", "do_resa_prao.php?",$this->affichage_resa_expl);
		$ret=$this->affichage_resa_expl;
		return $ret; 
	}


}

// MBA
class notice_affichage_mba extends notice_affichage {
	// génération de l'affichage public----------------------------------------
	function do_public($short=0,$ex=1) {
		global $dbh;
		global $msg;
		global $tdoc;
		global $charset;
		global $memo_notice;
		
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
	
		//Complément du titre parallèle dans le Champ personalisé sstitre_parallele
		$sstitre_parallele="";
		$sstitre_parallele1="";
		if (!$this->p_perso->no_special_fields) {
			if(!$this->memo_perso_) $this->memo_perso_=$this->p_perso->show_fields($this->notice_id);			
			for ($i=0; $i<count($this->memo_perso_["FIELDS"]); $i++) {
				$p=$this->memo_perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]) {
					if($p["NAME"] == "sstitre_parallele") {
						$sstitre_parallele=$p["AFF"];						
					}	
					if($p["NAME"] == "titre_parallele") {
						$sstitre_parallele1=str_replace("/","<br>",$p["AFF"]);
					}					
				}
			}
		}
		if($sstitre_parallele)$sstitre_parallele=" : ".$sstitre_parallele;
		if ($this->notice->tit3) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>Titre parallèle :</span></td><td>".$this->notice->tit3.$sstitre_parallele."</td></tr>" ;
		
		if ($sstitre_parallele1) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>Autres titres parallèles :</span></td><td>".$sstitre_parallele1."</td></tr>" ;
				
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
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td>".inslink($collection->name,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection))."</td></tr>" ;
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['subcoll_start']."</span></td><td>".inslink($subcollection->name,  str_replace("!!id!!", $this->notice->subcoll_id, $this->lien_rech_subcollection)) ;
			$this->notice_public .=$affnocoll."</td></tr>";
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td>".inslink($collection->isbd_entry,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection)) ;
			$this->notice_public .=$affnocoll."</td></tr>";
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
		$collation=$this->notice->npages;
		if($collation && $this->notice->ill)$collation.=" : ";
		$collation.=$this->notice->ill;
		if($collation && $this->notice->size)$collation.=", ";
		$collation.=$this->notice->size;
		
		if($collation) $this->notice_public.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_collation']."</span></td><td>".$collation."</td></tr>";
		
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
	}	
	
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
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td>".nl2br($this->notice->n_resume)."</td></tr>";
	
		// note de contenu
		if($this->notice->n_contenu) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
	
		// Permalink avec Id
		if ($opac_permalink) $ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";
		
		//Champs personalisés
		$perso_aff = $perso_aff_suite = $titre = $loc = $etablissement = $date = $lieu_ed = "" ;
		if (!$this->p_perso->no_special_fields) {
			if(!$this->memo_perso_) $this->memo_perso_=$this->p_perso->show_fields($this->notice_id);		
			for ($i=0; $i<count($this->memo_perso_["FIELDS"]); $i++) {
				$p=$this->memo_perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]) {
					if($p["NAME"] == "t_d_f_titre")$titre=$p["AFF"];	
					elseif($p["NAME"] == "t_d_f_lieu_etabl")$lieu_ed=$p["AFF"];					
					elseif($p["NAME"] == "t_d_f_date")$date=$p["AFF"];	
					elseif($p["NAME"] == "sstitre_parallele");//rien, il est affiché après le titre paralelle
					elseif($p["NAME"] == "titre_parallele");//rien, les autres titres parralleles sont affichés après le titre paralelle
					else $perso_aff_suite.="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$p["TITRE"]."</span></td><td>".$p["AFF"]."</td></tr>";
				}
			}
		}
		
		if($titre){
			$perso_aff= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_titre_de_forme']."</span></td><td>".$titre."</td></tr>" ;
			$lieu=explode("/",$lieu_ed);
			if(count($lieu)){
				for($l=0;$l<count($lieu);$l++){
					$perso_aff.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'></span></td><td>".$lieu[$l]."</td></tr>" ;
				}
			}
			
			if($date){
				$perso_aff.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'></span></td><td>".$date."</td></tr>" ;
			}
		}
		$ret .=$perso_aff.$perso_aff_suite;
		
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
		
		$this->affichage_suite = $ret ;
		$this->affichage_suite_flag = 1 ;
		return $ret ;
	} 	

}

/*
 * Classe d'affichage pour Philip Morris
 */
class notice_affichage_pmi extends notice_affichage {

	var $collectivite_tous = "";
	var $customs = array();
	
	/*
	 * Affichage public
	 */
	function do_public($short=0,$ex=1){
		global $dbh;
			global $msg;
			global $tdoc;
			global $charset;
			global $memo_notice;
			
			$this->notice_public="";
			if(!$this->notice_id) return;
			
			// Chargement des champs persos
			if(!$this->customs) $this->customs = $this->load_custom_fields();
	
			// Notices parentes
			$this->notice_public.=$this->parents;
		
			$this->notice_public .= "<table>";
			// constitution de la mention de titre
					
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['title']." :</span></td>";
			$this->notice_public .= "<td><span class='public_title'>".$this->notice->tit1 ;
			
			if ($this->notice->tit4) $this->notice_public .= "&nbsp;: ".$this->notice->tit4 ;
			$this->notice_public.="</span></td></tr>";
			
			if ($this->notice->tit2) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t2']." :</span></td><td>".$this->notice->tit2."</td></tr>" ;
			if ($this->notice->tit3) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t3']." :</span></td><td>".$this->notice->tit3."</td></tr>" ;
			
			//Responsabilités
				
			if ($this->auteurs_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['auteur_start']."</span></td><td>".$this->auteurs_tous."</td></tr>";
			if ($this->congres_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['congres_aff_public_libelle']."</span></td><td>".$this->congres_tous."</td></tr>";
			if ($this->collectivite_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['collectivites_search'].":</span></td><td>".$this->collectivite_tous."</td></tr>";
			
			// zone de l'éditeur 
			if ($this->notice->year)
				$annee = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['year_start']."</span></td><td>".$this->notice->year."</td></tr>" ;
	
			// $annee est vide si ajoutée avec l'éditeur, donc si pas éditeur, on l'affiche ici
			$this->notice_public .= $annee ;
			
			//Subtype
			if($this->customs["SUBTYPE"]) $this->notice_public .= $this->customs["SUBTYPE"] ;
			
			if (!$short) $this->notice_public .= $this->aff_suite_public(); 
			else $this->notice_public.=$this->genere_in_perio();
		
			$this->notice_public.="</table>\n";
			
			//etat des collections
			if ($this->notice->niveau_biblio=='s' && $this->notice->niveau_hierar==1) $this->notice_public.=$this->affichage_etat_collections();	
			
			// exemplaires, résas et compagnie
			if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
		
			return;	
	}
	
	
	// fonction d'affichage de la suite PUBLIC 
	function aff_suite_public() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search,  $opac_url_base;
		
		$ret .= $this->genere_in_perio () ;
				
		/** toutes indexations **/
		$ret_index = "";
		// Catégories
		if ($this->categories_toutes) $ret_index .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['categories_start']."</span></td><td>".$this->categories_toutes."</td></tr>";
				
		// Affectation du libellé mots clés ou tags en fonction de la recherche précédente	
		if($opac_allow_tags_search == 1) $libelle_key = $msg['tags'];
		else $libelle_key = 	$msg['motscle_start'];
				
		// indexation libre
		$mots_cles = $this->do_mots_cle() ;
		if($mots_cles) $ret_index.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$libelle_key."</span></td><td>".nl2br($mots_cles)."</td></tr>";

		if ($ret_index) 
			$ret.=$ret_index;
			
		// résumé
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td>".nl2br($this->notice->n_resume)."</td></tr>";
		
		$this->affichage_suite = $ret ;
		return $ret ;
	} 
	
	// fonction d'affichage de la suite PUBLIC 
	function aff_suite_isbd() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search, $opac_permalink, $opac_url_base;
		
		$ret .= $this->genere_in_perio () ;
				
		/** toutes indexations **/
		$ret_index = "";
		// Catégories
		if ($this->categories_toutes) $ret_index .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['categories_start']."</span></td><td>".$this->categories_toutes."</td></tr>";
				
		// Affectation du libellé mots clés ou tags en fonction de la recherche précédente	
		if($opac_allow_tags_search == 1) $libelle_key = $msg['tags'];
		else $libelle_key = 	$msg['motscle_start'];
				
		// indexation libre
		$mots_cles = $this->do_mots_cle() ;
		if($mots_cles) $ret_index.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$libelle_key."</span></td><td>".nl2br($mots_cles)."</td></tr>";

		if ($ret_index) 
			$ret.=$ret_index;
			
		// résumé
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td>".nl2br($this->notice->n_resume)."</td></tr>";
		
		// ISBN ou NO. commercial
		if ($this->notice->code) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['code_start']."</span></td><td>".$this->notice->code."</td></tr>";
			
		//Persos
		if($this->customs["DISCO"]) $ret .= $this->customs["DISCO"];
		if($this->customs["PUBMED"]) $ret .= $this->customs["PUBMED"];
		if($this->customs["DOI"]) $ret .= $this->customs["DOI"];
		
		// Permalink avec Id
		if ($opac_permalink) $ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";
	
		$this->affichage_suite = $ret ;
		return $ret ;
	} 
	
	/*
	 * Chargement des champs persos
	 */
	function load_custom_fields(){
		
		$custom_fields = array();
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]){
					$value = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".strip_tags($p["TITRE"])."</span></td><td>".$p["AFF"]."</td></tr>";					
					if ($p["NAME"] == "pmi_doi_identifier"){
						$custom_fields["DOI"] = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".strip_tags($p["TITRE"])."</span></td><td><a href='http://dx.doi.org/".$p["AFF"]."' target='_BLANK'>http://dx.doi.org/".$p["AFF"]."</a></td></tr>";
					}
					if ($p["NAME"] == "subtype"){
						$custom_fields["SUBTYPE"] = $value;
					}
					if ($p["NAME"] == "publishedOR"){
						$custom_fields["REPOS"] = $value;
					}	
					if ($p["NAME"] == "pmi_published_by_pmi"){
						$custom_fields["PMI_PUBLISHED"] = $value;
					}
					if ($p["NAME"] == "r_object_id"){
						$custom_fields["DISCO"] = $value;
					}
					if ($p["NAME"] == "pmi_xref_dbase_id"){
						$custom_fields["PUBMED"] = $value;
					}
				}    
			}
		}
		
		return $custom_fields;
	}
	
	/*
	 * Récuperation des autorites
	 */
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
			if ($notice->author_web) $auteur_web_link = " <a href='$notice->author_web' target='_blank'><img src='images/globe.gif' border='0'/></a>";
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
			$auteurs_liste = implode ("; ",$aut1_libelle) ;
			if ($auteurs_liste) $this->auteurs_principaux = $auteurs_liste ;
		}
	
		// $this->auteurs_tous
		$mention_resp = array() ;
		$collectivite_resp = array();
		$congres_resp = array() ;
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$mention_resp_lib = $auteur_0["auteur_isbd"];
			if($this->responsabilites["auteurs"][$as]["type"]==72) {
				$congres_resp[] = $mention_resp_lib ;
			} else if($this->responsabilites["auteurs"][$as]["type"]==71){
				$collectivite_resp[] = $mention_resp_lib;
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
			} else if($this->responsabilites["auteurs"][$indice]["type"]==71){
				$collectivite_resp[] = $mention_resp_lib;
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
			} else if($this->responsabilites["auteurs"][$indice]["type"]==71){
				$collectivite_resp[] = $mention_resp_lib;
			} else {
				$mention_resp[] = $mention_resp_lib ;
			}		
		}
		
		
		$libelle_mention_resp = implode ("; ",$mention_resp) ;
		if ($libelle_mention_resp) $this->auteurs_tous = $libelle_mention_resp ;
		else $this->auteurs_tous ="" ;
		
		$libelle_collectivite_resp = implode ("; ",$collectivite_resp) ;
		if ($libelle_collectivite_resp) $this->collectivite_tous = $libelle_collectivite_resp ;
		else $this->collectivite_tous ="" ;
		
		$libelle_congres_resp = implode ("; ",$congres_resp) ;
		if ($libelle_congres_resp) $this->congres_tous = $libelle_congres_resp ;
		else $this->congres_tous ="" ;
		
	}
	
	/*
	 * Affichage ISBD
	 */
	function do_isbd($short=0,$ex=1) {
		
		global $dbh;
		global $msg;
		global $tdoc;
		global $charset;
		global $opac_notice_affichage_class;
		global $memo_notice;
		
		$this->notice_isbd="";
		if(!$this->notice_id) return;
		
		// Chargement des champs persos
		if(!$this->customs) $this->customs = $this->load_custom_fields();
		
		// Notices parentes
		$this->notice_isbd.=$this->parents;
	
		$this->notice_isbd .= "<table>";
		// constitution de la mention de titre
				
		$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['title']." :</span></td>";
		$this->notice_isbd .= "<td><span class='public_title'>".$this->notice->tit1 ;
		
		if ($this->notice->tit4) $this->notice_isbd .= "&nbsp;: ".$this->notice->tit4 ;
		$this->notice_isbd.="</span></td></tr>";
		
		if ($this->notice->tit2) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t2']." :</span></td><td>".$this->notice->tit2."</td></tr>" ;
		if ($this->notice->tit3) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t3']." :</span></td><td>".$this->notice->tit3."</td></tr>" ;
		
		//Auteurs	
		if ($this->auteurs_tous) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['auteur_start']."</span></td><td>".$this->auteurs_tous."</td></tr>";
		if ($this->congres_tous) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['congres_aff_public_libelle']."</span></td><td>".$this->congres_tous."</td></tr>";
		if ($this->collectivite_tous) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['collectivites_search'].":</span></td><td>".$this->collectivite_tous."</td></tr>";
		
		
		//PMI-AUTHORED
		$this->notice_isbd .= $this->customs["PMI_PUBLISHED"];
		
		// zone de l'éditeur 
		if ($this->notice->year)
			$annee = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['year_start']."</span></td><td>".$this->notice->year."</td></tr>" ;
	
		if ($this->notice->ed1_id) {
			$editeur = new publisher($this->notice->ed1_id);
			$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['editeur_start']."</span></td><td>".inslink($editeur->display,  str_replace("!!id!!", $this->notice->ed1_id, $this->lien_rech_editeur))."</td></tr>" ;
			if ($annee) {
				$this->notice_isbd .= $annee ;
				$annee = "" ;
			}  
		}
		// Autre editeur
		if ($this->notice->ed2_id) {
			$editeur_2 = new publisher($this->notice->ed2_id);
			$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_editor']."</span></td><td>".inslink($editeur_2->display,  str_replace("!!id!!", $this->notice->ed2_id, $this->lien_rech_editeur))."</td></tr>" ;
		}					
		// $annee est vide si ajoutée avec l'éditeur, donc si pas éditeur, on l'affiche ici
		$this->notice_isbd .= $annee ;
		
		//Open Repository
		$this->notice_isbd .= $this->customs["REPOS"];
		
		//Subtype
		$this->notice_isbd .= $this->customs["SUBTYPE"];
		
		// zone de la collation
		if($this->notice->npages) {
			if ($this->notice->niveau_biblio<>"a") {
				$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start']."</span></td><td>".$this->notice->npages."</td></tr>";
			} else {
				$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start_perio']."</span></td><td>".$this->notice->npages."</td></tr>";
			}
		}
		if ($this->notice->ill) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['ill_start']."</span></td><td>".$this->notice->ill."</td></tr>";
			
		// langues
		if (count($this->langues)) {
			$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['537']." :</span></td><td>".$this->construit_liste_langues($this->langues);
			if (count($this->languesorg)) $this->notice_isbd .= " <span class='etiq_champ'>".$msg['711']." :</span> ".$this->construit_liste_langues($this->languesorg);
			$this->notice_isbd.="</td></tr>";
		} elseif (count($this->languesorg)) {
			$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['711']." :</span></td><td>".$this->construit_liste_langues($this->languesorg)."</td></tr>"; 
		}
		if (!$short) $this->notice_isbd .= $this->aff_suite_isbd(); 
		else $this->notice_isbd.=$this->genere_in_perio();
	
		$this->notice_isbd.="</table>\n";
		
		//etat des collections
		if ($this->notice->niveau_biblio=='s' && $this->notice->niveau_hierar==1) $this->notice_isbd.=$this->affichage_etat_collections();	
		
		// exemplaires, résas et compagnie
		if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
	
		return;	
	
	}	
	
	// fonction d'affichage des exemplaires numeriques
	function aff_explnum () {
		
		global $msg;
		$ret='';
		if ( (is_null($this->dom_2) && $this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) || ($this->rights & 16)){
			if ($this->notice->niveau_biblio=="b" && ($explnum = $this->show_explnum_per_notice(0, $this->bulletin_id, ''))) {
				$ret .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
				$this->affichage_expl .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
			} elseif (($explnum = $this->show_explnum_per_notice($this->notice_id,0, ''))) {
				$ret .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
				$this->affichage_expl .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
			}
		}		 
		return $ret;
	}
	
	// fonction retournant les infos d'exemplaires numériques pour une notice ou un bulletin donné
	function show_explnum_per_notice($no_notice, $no_bulletin, $link_expl='') {
		
		// params :
		// $link_expl= lien associé à l'exemplaire avec !!explnum_id!! à mettre à jour
		global $dbh;
		global $charset;
		global $opac_url_base ;
		
		if (!$no_notice && !$no_bulletin) return "";
		
		global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
		create_tableau_mimetype() ;
	
		// récupération du nombre d'exemplaires
		$requete = "SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, explnum_vignette, explnum_nomfichier, explnum_extfichier 
		FROM explnum left join explnum_location on (num_explnum=explnum_id) WHERE ";
		if ($no_notice && !$no_bulletin) $requete .= "explnum_notice='$no_notice' ";
		elseif (!$no_notice && $no_bulletin) $requete .= "explnum_bulletin='$no_bulletin' ";
		elseif ($no_notice && $no_bulletin) $requete .= "explnum_bulletin='$no_bulletin' or explnum_notice='$no_notice' ";
		$requete .= " and (num_location='".$_SESSION['empr_location']."' or num_location is null) order by explnum_mimetype, explnum_id ";
		$res = mysql_query($requete, $dbh);
		$nb_ex = mysql_num_rows($res);
		
		if($nb_ex) {
			// on récupère les données des exemplaires
			$i = 1 ;
			global $search_terms;
			
			while (($expl = mysql_fetch_object($res))) {
				if ($i==1) $ligne="<tr><td class='docnum' width='33%'>!!1!!</td><td class='docnum' width='33%'>!!2!!</td><td class='docnum' width='33%'>!!3!!</td></tr>" ;
				if ($link_expl) {
					$tlink = str_replace("!!explnum_id!!", $expl->explnum_id, $link_expl);
					$tlink = str_replace("!!notice_id!!", $expl->explnum_notice, $tlink);					
					$tlink = str_replace("!!bulletin_id!!", $expl->explnum_bulletin, $tlink);					
					} 
				$alt = htmlentities($expl->explnum_nom." - ".$expl->explnum_mimetype,ENT_QUOTES, $charset) ;
				
				if ($expl->explnum_vignette) $obj="<img src='/vig_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' border='0'>";
					else // trouver l'icone correspondant au mime_type
						$obj="<img src='/images/mimetype/".icone_mimetype($expl->explnum_mimetype, $expl->explnum_extfichier)."' alt='$alt' title='$alt' border='0'>";		
				$expl_liste_obj = "<center>";
				
				$words_to_find="";
				if(($expl->explnum_mimetype=='application/pdf') ||($expl->explnum_mimetype=='URL' && (strpos($expl->explnum_nom,'.pdf')!==false))){
					$words_to_find = "#search=\"".trim(str_replace('*','',implode(' ',$search_terms)))."\"";
				}
				$expl_liste_obj .= "<a href='/doc_num.php?explnum_id=$expl->explnum_id$words_to_find' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;
				
				if ($_mimetypes_byext_[$expl->explnum_extfichier]["label"]) $explmime_nom = $_mimetypes_byext_[$expl->explnum_extfichier]["label"] ;
					elseif ($_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"]) $explmime_nom = $_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"] ;
						else $explmime_nom = $expl->explnum_mimetype ;
				
				
				if ($tlink) {
					$expl_liste_obj .= "<a href='$tlink'>";
					$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."</a><div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
					} else {
						$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."<div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
						}
				$expl_liste_obj .= "</center>";
				$ligne = str_replace("!!$i!!", $expl_liste_obj, $ligne);
				$i++;
				if ($i==4) {
					$ligne_finale .= $ligne ;
					$i=1;
					}
				}
			if (!$ligne_finale) $ligne_finale = $ligne ;
				elseif ($i!=1) $ligne_finale .= $ligne ;
			$ligne_finale = str_replace('!!2!!', "&nbsp;", $ligne_finale);
			$ligne_finale = str_replace('!!3!!', "&nbsp;", $ligne_finale);
			
			} else return "";
		$entry .= "<table class='docnum'>$ligne_finale</table>";
		return $entry;
	
	}
	
// génération du header----------------------------------------------------
	function do_header() {

		global $opac_notice_reduit_format ;
		global $opac_url_base, $msg ;
		global $memo_notice;
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
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2)  {
			 $aff_perio_title="<i>in ".$this->parent_title.", ".$this->parent_numero." (".($this->parent_date?$this->parent_date:"[".$this->parent_aff_date_date."]").")</i>";
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
		$this->notice_header_without_html = $this->notice_header;	
		$this->notice_header = "<span class='header_title'>".$this->notice_header."</span>";	
				
		$notice_header_suite = "";
		if ($type_reduit=="T" && $this->notice->tit4) $notice_header_suite = " : ".$this->notice->tit4;
		if ($this->auteurs_principaux) $notice_header_suite .= " / ".$this->auteurs_principaux;
		if ($editeur_reduit) $notice_header_suite .= " / ".$editeur_reduit ;
		if ($perso_voulu_aff) $notice_header_suite .= " / ".$perso_voulu_aff ;
		if ($aff_perio_title) $notice_header_suite .= " ".$aff_perio_title;
		$this->notice_header_without_html .= $notice_header_suite ;
		$this->notice_header .= $notice_header_suite."</span>"  ;	
		//$this->notice_header.="&nbsp;<span id=\"drag_symbol_drag_noti_".$this->notice->notice_id."\" style=\"visibility:hidden\"><img src=\"images/drag_symbol.png\"\></span>";
		$this->notice_header_with_link=inslink($this->notice_header, str_replace("!!id!!", $this->notice_id, $this->lien_rech_notice)) ;
		
		if ($this->notice->lien) {
			if(!$this->notice->eformat) $info_bulle=$msg["open_link_url_notice"];
			else $info_bulle=$this->notice->eformat;
			// ajout du lien pour les ressource électroniques
			$this->notice_header .= "&nbsp;<span><a href=\"".$this->notice->lien."\" target=\"__LINK__\">";
			$this->notice_header .= "<img src=\"images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
			$this->notice_header .= " alt=\"";
			$this->notice_header .= $info_bulle;
			$this->notice_header .= "\" title=\"";
			$this->notice_header .= $info_bulle;
			$this->notice_header .= "\">";
			$this->notice_header .= "</a></span>";
		} 
		if ($this->notice->niveau_biblio == 'b') {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier, explnum_url FROM explnum, bulletins join explnum_location on (num_explnum=explnum_id and num_location='".$_SESSION['empr_location']."' ) WHERE bulletins.num_notice = ".$this->notice_id." AND bulletins.bulletin_id = explnum.explnum_bulletin order by explnum_id";
		} else {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier,explnum_url FROM explnum join explnum_location on (num_explnum=explnum_id and num_location='".$_SESSION['empr_location']."' ) WHERE explnum_notice = ".$this->notice_id." order by explnum_id";
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
				$this->notice_header .= "&nbsp;<span>";		
				$this->notice_header .= "<a href=\"./doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">";
				$this->notice_header .= "<img src=\"./images/globe_orange.png\" border=\"0\" align=\"middle\" hspace=\"3\"";
				$this->notice_header .= " alt=\"";
				$this->notice_header .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header .= "\" title=\"";
				$this->notice_header .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header .= "\">";
				$this->notice_header .= "</a></span>";
			} elseif ($explnumscount > 1) {
				$explnumrow = mysql_fetch_object($explnums);
				$info_bulle=$msg["info_docs_num_notice"];
				$this->notice_header .= "<img src=\"./images/globe_rouge.png\" alt=\"$info_bulle\" \" title=\"$info_bulle\" border=\"0\" align=\"middle\" hspace=\"3\">";
			}
		}
		$memo_notice[$this->notice_id]["header"]=$this->notice_header;
		$memo_notice[$this->notice_id]["niveau_biblio"]	= $this->notice->niveau_biblio;
	}
	
}

/*
 * Classe d'affichage OPAC pour supagro
 */
class notice_affichage_supagro extends notice_affichage {
	
	
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
		if($tdoc->table[$this->notice->typdoc]) $this->notice_isbd .= ' ['.$tdoc->table[$this->notice->typdoc].']';
		
		
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
		if ($langues) $this->notice_isbd .= "<br />".$langues."<br />" ;
		
		//Champs personalisés
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]) $this->notice_isbd .="<span class='etiq_champ'>".$p["TITRE"]."</span>".$p["AFF"]."<br />";
			}
		}
				
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
	}	
	
	// génération de l'affichage public----------------------------------------
	function do_public($short=0,$ex=1) {
		global $dbh;
		global $msg;
		global $tdoc;
		global $charset;
		global $memo_notice;
		
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
		
		//Champs personalisés
		$perso_aff = "" ;
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]) $this->notice_public .="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$p["TITRE"]."</span></td><td>".$p["AFF"]."</td></tr>";
			}
		}
		
		
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
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td>".inslink($collection->name,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection))."</td></tr>" ;
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['subcoll_start']."</span></td><td>".inslink($subcollection->name,  str_replace("!!id!!", $this->notice->subcoll_id, $this->lien_rech_subcollection)) ;
			$this->notice_public .=$affnocoll."</td></tr>";
		} elseif ($this->notice->coll_id) {
			$collection = new collection($this->notice->coll_id);
			$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['coll_start']."</span></td><td>".inslink($collection->isbd_entry,  str_replace("!!id!!", $this->notice->coll_id, $this->lien_rech_collection)) ;
			$this->notice_public .=$affnocoll."</td></tr>";
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
	}	
		
	// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour éviter la redondance de calcul
	function aff_suite() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search, $opac_permalink, $opac_url_base;
		
		
		$ret .= $this->genere_in_perio () ;		
		
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
		}
		
		// résumé
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td>".nl2br($this->notice->n_resume)."</td></tr>";
	
		// note de contenu
		if($this->notice->n_contenu) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
	
		// Permalink avec Id
		if ($opac_permalink) $ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";
	
		
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
		
		$this->affichage_suite = $ret ;
		return $ret ;
	} 
}
	
/*
 * Classe d'affichage pour le CRIPS  
 */
class notice_affichage_crips extends notice_affichage {	
	// génération du de l'affichage double avec onglets ---------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	var $customs = array();

	function genere_double($depliable=1, $premier='ISBD') {
		global $msg;
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $icon_doc,$biblio_doc,$tdoc;
		global $allow_tag; // l'utilisateur a-t-il le droit d'ajouter un tag
		$this->result ="";
		if(!$this->notice_id) return;	
		$this->premier = $premier ;
		$this->double_ou_simple = 2 ;
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
		if (($this->sugg_allowed ==2)|| ($_SESSION["user_code"] && $this->sugg_allowed ==1)) $img_tag .= $this->affichage_suggestion($this->notice_id);
		
		// préparation de la case à  cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
	
		$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		if ($depliable) {
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_".$this->notice->typdoc."' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['expandable_notice']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\" />";
//			if ($icon) {
//    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
//    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
//    			//$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
//    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">";
		} else {
			$template="<div class='parent'>$case_a_cocher";
//			if ($icon) {
//    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
//    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
//    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
//    		}
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
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
		if($this->notice->opac_visible_bulletinage) $voir_bulletins="&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>";
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
		$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
		if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result); 		
		$this->result = str_replace('!!SUITE!!', "", $this->result);
	}
	
	// génération du de l'affichage simple sans onglet ----------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	function genere_simple($depliable=1, $what='ISBD') {
		global $msg; 
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $icon_doc,$biblio_doc,$tdoc;
		global $allow_tag ; // l'utilisateur a-t-il le droit d'ajouter un tag
		if(!$this->notice_id) return;
		
		$this->double_ou_simple = 1 ;
		$this->notice_childs = $this->genere_notice_childs();
		// préparation de la case Ã  cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
		
		if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\" class=\"img_basket\"><img src='images/basket_small_20x20.gif' align='absmiddle' border='0' title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
		else $basket="";
		
		//add tags
		if (($this->tag_allowed==1)||(($this->tag_allowed==2)&&($_SESSION["user_code"])&&($allow_tag)))
			$img_tag.="<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='images/tag.png' align='absmiddle' border='0' title=\"".$msg['notice_title_tag']."\" alt=\"".$msg['notice_title_tag']."\"></a>";
		
		 //Avis
		if (($this->avis_allowed && $this->avis_allowed !=2)|| ($_SESSION["user_code"] && $this->avis_allowed ==2))
			$img_tag .= $this->affichage_avis($this->notice_id);
		
		//Suggestions
		if (($this->sugg_allowed ==2)|| ($_SESSION["user_code"] && $this->sugg_allowed ==1)) $img_tag .= $this->affichage_suggestion($this->notice_id);	
		 
		$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		
		if ($depliable) { 
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img  class='img_".$this->notice->typdoc."' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['expandable_notice']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\" />";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			//$template.="<img  src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">
	    		";			
		} else {
			$template="
				\n<div id=\"el!!id!!Parent\" class=\"parent\">
	    		$case_a_cocher";
//			if ($icon) {
//    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
//    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
//    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
//    		}			
    		$template.="<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span><br />";
		}
		$template.="!!CONTENU!!
					!!SUITE!!</div>";
		
		if($basket || $img_tag) $template_in.="<ul id='onglets_isbd_public'>
						<li id='baskets!!id!!' class='onglet_basket'>$basket</li>
	  					<li id='tags!!id!!' class='onglet_tags'>$img_tag</li>
					   </ul>	
		";
		if($what =='ISBD') $template_in.="		    	
				<div id='div_isbd!!id!!' style='display:block;'>!!ISBD!!</div>
	  			<div id='div_public!!id!!' style='display:none;'>!!PUBLIC!!</div>";
		else $template_in.="
		    	<div id='div_public!!id!!' style='display:block;'>!!PUBLIC!!</div>
				<div id='div_isbd!!id!!' style='display:none;'>!!ISBD!!</div>"
	  			; 	
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
			$template_in = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!ISBD!!", $template_in);
			$template_in = str_replace('!!PUBLIC!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!PUBLIC!!", $template_in);
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
		if($this->notice_header)
			$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		else $this->result = str_replace('!!heada!!', '', $this->result);
		$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
		
		if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result);
		else $this->result = str_replace('!!SUITE!!', '', $this->result);
				
	}
	
// génération du header----------------------------------------------------
	function do_header() {

		global $opac_notice_reduit_format ;
		global $opac_url_base, $msg ;
		global $memo_notice;
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
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2)  {
			 $aff_perio_title="<i>in ".$this->parent_title.", ".$this->parent_numero." (".($this->parent_date?$this->parent_date:"[".$this->parent_aff_date_date."]").")</i>";
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
		$this->notice_header_without_html = $this->notice_header;	
		$this->notice_header = "<span class='header_title'>".$this->notice_header."</span>";	
				
		$notice_header_suite = "";
		if ($type_reduit=="T" && $this->notice->tit4) $notice_header_suite = " : ".$this->notice->tit4;
		if ($this->auteurs_principaux) $notice_header_suite .= " / ".$this->auteurs_principaux;
		if ($editeur_reduit) $notice_header_suite .= " / ".$editeur_reduit ;
		if ($perso_voulu_aff) $notice_header_suite .= " / ".$perso_voulu_aff ;
		if ($aff_perio_title) $notice_header_suite .= " ".$aff_perio_title;
		$this->notice_header_without_html .= $notice_header_suite ;
		$this->notice_header .= $notice_header_suite."</span>"  ;	
		//$this->notice_header.="&nbsp;<span id=\"drag_symbol_drag_noti_".$this->notice->notice_id."\" style=\"visibility:hidden\"><img src=\"images/drag_symbol.png\"\></span>";
		$this->notice_header_with_link=inslink($this->notice_header, str_replace("!!id!!", $this->notice_id, $this->lien_rech_notice)) ;
		
		if ($this->notice->lien) {
			if(!$this->notice->eformat) $info_bulle=$msg["open_link_url_notice"];
			else $info_bulle=$this->notice->eformat;
			// ajout du lien pour les ressource électroniques
			$this->notice_header .= "&nbsp;<span><a href=\"".$this->notice->lien."\" target=\"__LINK__\">";
			$this->notice_header .= "<img src=\"styles/crips/images/oeil.png\" border=\"0\" hspace=\"3\"";
			$this->notice_header .= " alt=\"";
			$this->notice_header .= $info_bulle;
			$this->notice_header .= "\" title=\"";
			$this->notice_header .= $info_bulle;
			$this->notice_header .= "\">";
			$this->notice_header .= "</a></span>";
		} 
		if ($this->notice->niveau_biblio == 'b') {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier, explnum_url FROM explnum, bulletins WHERE bulletins.num_notice = ".$this->notice_id." AND bulletins.bulletin_id = explnum.explnum_bulletin order by explnum_id";
		} else {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier,explnum_url FROM explnum WHERE explnum_notice = ".$this->notice_id." order by explnum_id";
		}
		$explnums = mysql_query($sql_explnum);
		$explnumscount = mysql_num_rows($explnums);

		if ( !$this->notice->lien && (is_null($this->dom_2) && $this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"])))  || ($this->rights & 16) ) {
			while($explnumrow = mysql_fetch_object($explnums)){
				if ($explnumrow->explnum_nomfichier){
					if($explnumrow->explnum_nom == $explnumrow->explnum_nomfichier)	$info_bulle=$msg["open_doc_num_notice"].$explnumrow->explnum_nomfichier;
					else $info_bulle=$explnumrow->explnum_nom;
				}elseif ($explnumrow->explnum_url){
					if($explnumrow->explnum_nom == $explnumrow->explnum_url)	$info_bulle=$msg["open_link_url_notice"].$explnumrow->explnum_url;
					else $info_bulle=$explnumrow->explnum_nom;
				}	
				$this->notice_header .= "&nbsp;<span>";		
				$this->notice_header .= "<a href=\"./doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">";
				$this->notice_header .= "<img src=\"./styles/crips/images/oeil.png\" border=\"0\" hspace=\"3\"";
				$this->notice_header .= " alt=\"";
				$this->notice_header .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header .= "\" title=\"";
				$this->notice_header .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header .= "\">";
				$this->notice_header .= "</a></span>";
			}			
		}
		$memo_notice[$this->notice_id]["header"]=$this->notice_header;
		$memo_notice[$this->notice_id]["niveau_biblio"]	= $this->notice->niveau_biblio;
	}
	
	function do_image(&$entree,$depliable) {
		global $opac_show_book_pics ;
		global $opac_book_pics_url ;
		global $opac_url_base ;
				
		if ($this->notice->code || $this->notice->thumbnail_url) {
			if ($opac_show_book_pics=='1' && ($opac_book_pics_url || $this->notice->thumbnail_url)) {
				$code_chiffre = pmb_preg_replace('/-|\.| /', '', $this->notice->code);
				$url_image = $opac_book_pics_url ;
				$url_image = $opac_url_base."getimage.php?url_image=".urlencode($url_image)."&noticecode=!!noticecode!!&vigurl=".urlencode($this->notice->thumbnail_url) ;
				
				$hauteur_vig = "";	
				if(strpos($this->notice->thumbnail_url,"AFFICHES_VIGNETTES") !== false){
					$hauteur_vig = "";				
				} else $hauteur_vig= " height=\"150px\" ";
				if ($depliable) $image = "<img src='images/vide.png' hspace='4' vspace='2' $hauteur_vig isbn='".$code_chiffre."' url_image='".$url_image."' border='1px solid #ccccff' vigurl=\"".$this->notice->thumbnail_url."\">";
				else {
					if ($this->notice->thumbnail_url) $url_image_ok=$this->notice->thumbnail_url;
					else $url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
					$image = "<img src='".$url_image_ok."' $hauteur_vig align='right' hspace='4' vspace='2'>";
				}
			} else $image="" ;
			if ($image) {
				$entree = "<table width='100%'><tr><td>$image</td></tr><tr><td>$entree</td></tr></table>" ;
			} else {
				$entree = "<table width='100%'><tr><td>$entree</td></tr></table>" ;
			}
				
		} else {
			$entree = "<table width='100%'><tr><td>$entree</td></tr></table>" ;
		}
	}
	
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
		
		// Chargement des champs persos
		if(!$this->customs) $this->customs = $this->load_custom_fields();
		
		// Notices parentes
		$this->notice_isbd.=$this->parents;
		
		// constitution de la mention de titre
		if($this->notice->serie_name) {
			$serie_temp .= inslink($this->notice->serie_name,  str_replace("!!id!!", $this->notice->tparent_id, $this->lien_rech_serie));
			if($this->notice->tnvol) $serie_temp .= ',&nbsp;'.$this->notice->tnvol;
		}
		if ($serie_temp) $this->notice_isbd .= $serie_temp.".&nbsp;".$this->notice->tit1 ;
		else $this->notice_isbd .= $this->notice->tit1;
	
		$this->notice_isbd .= ' ['.$tdoc->table[$this->notice->typdoc].']';
		if ($this->notice->tit3) $this->notice_isbd .= "&nbsp;= ".$this->notice->tit3 ;
		if ($this->notice->tit4) $this->notice_isbd .= "&nbsp;: ".$this->notice->tit4 ;
		if ($this->notice->tit2) $this->notice_isbd .= "&nbsp;; ".$this->notice->tit2 ;
		
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
			$this->notice_isbd .= $this->aff_suite_isbd() ;
			$this->notice_isbd .="</table>";
		} else {
			$this->notice_isbd.=$this->genere_in_perio();
		}
	
		//etat des collections
		if ($this->notice->niveau_biblio=='s'&&$this->notice->niveau_hierar==1) $this->notice_isbd.=$this->affichage_etat_collections();	
	
		//Notices liées
		// ajoutées en dehors de l'onglet PUBLIC ailleurs
		
		if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
	}	
	
	// génération de l'affichage public----------------------------------------
	function do_public($short=0,$ex=1) {
		global $dbh;
		global $msg;
		global $tdoc;
		global $charset;
		global $memo_notice;
		
		$this->notice_public="";
		if(!$this->notice_id) return;
		
		// Chargement des champs persos
		if(!$this->customs) $this->customs = $this->load_custom_fields();
		
		// Notices parentes
		$this->notice_public.=$this->parents;
	
		$this->notice_public .= "<table>";
		// constitution de la mention de titre
		if ($this->notice->serie_name) {
			$this->notice_public.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['tparent_start']."</span></td><td>".inslink($this->notice->serie_name,  str_replace("!!id!!", $this->notice->tparent_id, $this->lien_rech_serie));;
			if ($this->notice->tnvol) $this->notice_public .= ',&nbsp;'.$this->notice->tnvol;
			$this->notice_public .="</td></tr>";
		}
		
		$this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ' >".$msg['title']." :</span></td>";
		$this->notice_public .= "<td><span class='public_title'>".$this->notice->tit1 ;
		
		if ($this->notice->tit4) $this->notice_public .= "&nbsp;: ".$this->notice->tit4 ;
		$this->notice_public.="</span></td></tr>";
		
		if ($this->notice->tit2) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t2']." :</span></td><td>".$this->notice->tit2."</td></tr>" ;
		if ($this->notice->tit3) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t3']." :</span></td><td>".$this->notice->tit3."</td></tr>" ;
		
		if ($tdoc->table[$this->notice->typdoc]) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['typdocdisplay_start']."</span></td><td>".$tdoc->table[$this->notice->typdoc]."</td></tr>";
		
		//Nature du document
		if($this->customs["NATURE"]) $this->notice_public .= $this->customs["NATURE"];
		
		if ($this->auteurs_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['auteur_start']."</span></td><td>".$this->auteurs_tous."</td></tr>";
		if ($this->congres_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['congres_aff_public_libelle']."</span></td><td>".$this->congres_tous."</td></tr>";
		
		// mention d'édition
		if ($this->notice->mention_edition) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['mention_edition_start']."</span></td><td>".$this->notice->mention_edition."</td></tr>";
		
		// zone de l'éditeur 
		if ($this->notice->year)
			$annee = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['year_start']."</span></td><td>".$this->notice->year."</td></tr>" ;
		
		//Date de publication
		if($this->customs["PUBLICATION"]) $this->notice_public .= $this->customs["PUBLICATION"];
			
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
		if (!$short) $this->notice_public .= $this->aff_suite_public() ; 
		else $this->notice_public.=$this->genere_in_perio();
	
		$this->notice_public.="</table>\n";
		
		//etat des collections
		if ($this->notice->niveau_biblio=='s' && $this->notice->niveau_hierar==1) $this->notice_public.=$this->affichage_etat_collections();	
		
		// exemplaires, résas et compagnie
		if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
	
		return;
	}	
	
	// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour éviter la redondance de calcul
	function aff_suite_public() {
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
		
		//Thématique
		if($this->customs["THEMATIQUE"]) $ret_index .= $this->customs["THEMATIQUE"];
		
		// Catégories
		if ($this->categories_toutes) $ret_index .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['categories_start']."</span></td><td>".$this->categories_toutes."</td></tr>";
		
		//Public cible
		if($this->customs["CIBLE"]) $ret_index .= $this->customs["CIBLE"];
				
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
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td>".nl2br($this->notice->n_resume)."</td></tr>";
	
		// note de contenu
		if($this->notice->n_contenu) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
	
		// Permalink avec Id
		if ($opac_permalink) $ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";

		//Signataire
		if($this->customs["SIGNATAIRE"]) $ret .= $this->customs["SIGNATAIRE"];
		
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
		
		$this->affichage_suite = $ret ;
		$this->affichage_suite_flag = 1 ;
		return $ret ;
	} 
	
	// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour éviter la redondance de calcul
	function aff_suite_isbd() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search, $opac_permalink, $opac_url_base;
		
		// afin d'éviter de recalculer un truc déjà calculé...
		if ($this->affichage_suite_flag) return $this->affichage_suite ;
		
		$ret .= $this->genere_in_perio () ;
		
		//Espace
		//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
		
		if($this->customs["NATURE"]) $ret.= $this->customs["NATURE"];
		if($this->customs["PUBLICATION"]) $ret .= $this->customs["PUBLICATION"];
		
		// toutes indexations
		$ret_index = "";
		
		//Thématique
		if($this->customs["THEMATIQUE"]) $ret_index .= $this->customs["THEMATIQUE"];
		
		// Catégories
		if ($this->categories_toutes) $ret_index .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['categories_start']."</span></td><td>".$this->categories_toutes."</td></tr>";
		
		//Public cible
		if($this->customs["CIBLE"]) $ret_index .= $this->customs["CIBLE"];
				
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
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td>".nl2br($this->notice->n_resume)."</td></tr>";
	
		// note de contenu
		if($this->notice->n_contenu) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
	
		// Permalink avec Id
		if ($opac_permalink) $ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";

		//Signataire
		if($this->customs["SIGNATAIRE"]) $ret .= $this->customs["SIGNATAIRE"];
		
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
		
		$this->affichage_suite = $ret ;
		$this->affichage_suite_flag = 1 ;
		return $ret ;
	} 
	
	/*
	 * Chargement des champs persos
	 */
	function load_custom_fields(){
		
		$custom_fields = array();
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]){
					$value = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".strip_tags($p["TITRE"])."</span></td><td>".$p["AFF"]."</td></tr>";					
					if ($p["NAME"] == "thematiques"){
						$custom_fields["THEMATIQUE"] = $value;
					}
					if ($p["NAME"] == "date_de_publication"){
						$custom_fields["PUBLICATION"] = $value;
					}	
					if ($p["NAME"] == "public_cible"){
						$custom_fields["CIBLE"] = $value;
					}
					if ($p["NAME"] == "ancien_type_doc"){
						$custom_fields["NATURE"] = $value;
					}
					if ($p["NAME"] == "signataire"){
						$custom_fields["SIGNATAIRE"] = $value;
					}
				}    
			}
		}
		
		return $custom_fields;
	}
}

/*
 * Classe d'affichage OPAC pour le CEDIAS
 */
class notice_affichage_cedias extends notice_affichage {
	
	// fonction d'affichage des exemplaires numeriques
	function aff_explnum () {
		global $msg,$dbh;
		$ret='';
		if ( (is_null($this->dom_2) && $this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) || ($this->rights & 16)){
			if ($this->notice->niveau_biblio=="b" && ($explnum = $this->show_explnum_per_notice(0, $this->bulletin_id, ''))) {
				$ret .= "<h3>$msg[explnum]</h3>".$explnum;
				$this->affichage_expl .= "<h3>$msg[explnum]</h3>".$explnum;
			} else {
				if(($explnum = $this->show_explnum_per_notice($this->notice_id,0, ''))){
					$ret .= "<h3>$msg[explnum]</h3>".$explnum;
					$this->affichage_expl .= "<h3>$msg[explnum]</h3>".$explnum;
				}				
				if($this->notice->niveau_biblio=="a" && $this->notice->niveau_hierar=="2"){
					//cas des dépouillements...
					$req = "select analysis_bulletin from analysis where analysis_notice='".$this->notice_id."'";
					$res = mysql_query($req,$dbh);
					if(mysql_num_rows($res)){
						$bulletin_id = mysql_result($res,0,0);
						$explnum_bull = $this->show_explnum_per_notice(0,$bulletin_id, '');
						if($explnum_bull){
							$ret .= "<h3>".$msg['explnum_bulletin']."</h3>".$explnum_bull;
							$this->affichage_expl .= "<h3>".$msg['explnum_bulletin']."</h3>".$explnum_bull;
						}
					}
				}
			}
		}		 
		return $ret;
	}
	
	function show_explnum_per_notice($no_notice, $no_bulletin, $link_expl='') {
		// params :
		// $link_expl= lien associé à l'exemplaire avec !!explnum_id!! à mettre à jour
		global $dbh;
		global $msg;
		global $charset;
		global $opac_url_base ;
		global $opac_visionneuse_allow;
		
		if (!$no_notice && !$no_bulletin) return "";
		
		global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
		create_tableau_mimetype() ;
		
		// récupération du nombre d'exemplaires
		$requete = "SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, explnum_vignette, explnum_nomfichier, explnum_extfichier FROM explnum WHERE ";
		if ($no_notice && !$no_bulletin) $requete .= "explnum_notice='$no_notice' ";
		elseif (!$no_notice && $no_bulletin) $requete .= "explnum_bulletin='$no_bulletin' ";
		elseif ($no_notice && $no_bulletin) $requete .= "explnum_bulletin='$no_bulletin' or explnum_notice='$no_notice' ";
		$requete .= " order by explnum_mimetype, explnum_id ";
		$res = mysql_query($requete, $dbh);
		$nb_ex = mysql_num_rows($res);
		
		if($nb_ex) {
			// on récupère les données des exemplaires
			$i = 1 ;
			global $search_terms;
		
			//Champ perso note de docnum
			$perso_display="";
			if (!$this->p_perso->no_special_fields) {
				$perso_=$this->p_perso->show_fields($this->notice_id);
				for ($j=0; $j<count($perso_["FIELDS"]); $j++) {
					$p=$perso_["FIELDS"][$j];
					if (($p['NAME']=='note_docnum')) $perso_display = $p['AFF'];
				}
			}
			
			while (($expl = mysql_fetch_object($res))) {
				if ($i==1) $ligne="<tr><td class='docnum' width='33%'>!!1!!</td><td class='docnum' width='33%'>!!2!!</td><td class='docnum' width='33%'>!!3!!</td></tr>" ;
				if ($link_expl) {
					$tlink = str_replace("!!explnum_id!!", $expl->explnum_id, $link_expl);
					$tlink = str_replace("!!notice_id!!", $expl->explnum_notice, $tlink);					
					$tlink = str_replace("!!bulletin_id!!", $expl->explnum_bulletin, $tlink);					
					} 
				$alt = htmlentities($expl->explnum_nom." - ".$expl->explnum_mimetype,ENT_QUOTES, $charset) ;
				
				if ($expl->explnum_vignette) $obj="<img src='/vig_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' border='0'>";
					else // trouver l'icone correspondant au mime_type
						$obj="<img src='/images/mimetype/".icone_mimetype($expl->explnum_mimetype, $expl->explnum_extfichier)."' alt='$alt' title='$alt' border='0'>";		
				$expl_liste_obj = "<center>";
				
				$words_to_find="";
				if(($expl->explnum_mimetype=='application/pdf') ||($expl->explnum_mimetype=='URL' && (strpos($expl->explnum_nom,'.pdf')!==false))){
					if(sizeof($search_terms)>0)$words_to_find = "#search=\"".trim(str_replace('*','',implode(' ',$search_terms)))."\"";
				}

				if ($opac_visionneuse_allow){
					$link="<script type='text/javascript' src='$opac_url_base/visionneuse/javascript/visionneuse.js'></script>
						<script type='text/javascript'>
							if(typeof(sendToVisionneuse) == 'undefined'){
								function sendToVisionneuse(explnum_id){
									document.getElementById('visionneuseIframe').src = 'visionneuse.php?'+(typeof(explnum_id) != 'undefined' ? 'explnum_id='+explnum_id+\"\" : '\'');
								}
							}
						</script>
						<a href='#' onclick=\"open_visionneuse(sendToVisionneuse,".$expl->explnum_id.");return false;\" alt='$alt' title='$alt'>".$obj."</a><br />";
					$expl_liste_obj .=$link;
				}else{
					$suite_url_explnum ="doc_num.php?explnum_id=$expl->explnum_id$words_to_find";
					$expl_liste_obj .= "<a href='$opac_url_base$suite_url_explnum' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;
				}
				if ($_mimetypes_byext_[$expl->explnum_extfichier]["label"]) $explmime_nom = $_mimetypes_byext_[$expl->explnum_extfichier]["label"] ;
					elseif ($_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"]) $explmime_nom = $_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"] ;
						else $explmime_nom = $expl->explnum_mimetype ;				
				
				if ($tlink) {
					$expl_liste_obj .= "<a href='$tlink'>";
					$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."</a><div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
				} else {
					$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."<div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
				}
				
				$expl_liste_obj .= "<div class='explnum_type'>".$perso_display."</div>";
				$expl_liste_obj .= "</center>";
				$ligne = str_replace("!!$i!!", $expl_liste_obj, $ligne);
				$i++;
				if ($i==4) {
					$ligne_finale .= $ligne ;
					$i=1;
					}
				}
				if (!$ligne_finale) $ligne_finale = $ligne ;
					elseif ($i!=1) $ligne_finale .= $ligne ;
				$ligne_finale = str_replace('!!2!!', "&nbsp;", $ligne_finale);
				$ligne_finale = str_replace('!!3!!', "&nbsp;", $ligne_finale);
			
		} else return "";
		$entry .= "<table class='docnum'>$ligne_finale</table>";
		return $entry;
	}
}

/*
 * Classe d'affichage pour l'OFDT  
 */
class notice_affichage_ofdt extends notice_affichage {	
	var $customs = array();
	
// génération de l'affichage public----------------------------------------
	function do_public($short=0,$ex=1) {
		global $dbh;
		global $msg;
		global $tdoc;
		global $charset;
		global $memo_notice;
		
		$this->notice_public="";
		if(!$this->notice_id) return;
		
		// Chargement des champs persos
		if(!$this->customs) $this->customs = $this->load_custom_fields();

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
		
		if ($this->notice->tit2) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t2']."</span></td><td>".$this->notice->tit2."</td></tr>" ;
		if ($this->notice->tit3) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t3']."</span></td><td>".$this->notice->tit3."</td></tr>" ;
		
		if ($tdoc->table[$this->notice->typdoc]) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['typdocdisplay_start']."</span></td><td>".$tdoc->table[$this->notice->typdoc]."</td></tr>";
		
		if ($this->auteurs_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['auteur_start']."</span></td><td>".$this->auteurs_tous."</td></tr>";
		if ($this->congres_tous) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['congres_aff_public_libelle']."</span></td><td>".$this->congres_tous."</td></tr>";
		
		// mention d'édition
		if ($this->notice->mention_edition) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['mention_edition_start']."</span></td><td>".$this->notice->mention_edition."</td></tr>";
		
		// zone de l'éditeur 
		if ($this->notice->year)
			$annee = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['year_start']." : </span></td><td>".$this->notice->year."</td></tr>" ;
	
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
	}
	
	// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour éviter la redondance de calcul
	function aff_suite() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search, $opac_permalink, $opac_url_base;
		
		
		// Chargement des champs persos
		if(!$this->customs) $this->customs = $this->load_custom_fields();		

		
		// afin d'éviter de recalculer un truc déjà calculé...
		if ($this->affichage_suite_flag) return $this->affichage_suite ;
		
		$ret .= $this->genere_in_perio () ;
		
		// toutes indexations
		$ret_index = "";
		
		// indexation interne
		if($this->notice->indexint) {
			$indexint = new indexint($this->notice->indexint);
			$ret_index.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['indexint_start']."</span></td><td>".inslink($indexint->name,  str_replace("!!id!!", $this->notice->indexint, $this->lien_rech_indexint))." ".nl2br(htmlentities($indexint->comment,ENT_QUOTES, $charset))."</td></tr>" ;
		}	
		
		// Catégories
		if ($this->categories_toutes) $ret_index .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['categories_start']."</span></td><td>".$this->categories_toutes."</td></tr>";
				
		// Affectation du libellé mots clés ou tags en fonction de la recherche précédente	
		if($opac_allow_tags_search == 1) $libelle_key = $msg['tags'];
		else $libelle_key = 	$msg['motscle_start'];
			
		if($this->customs["DOMAINE_TOXI"])$ret_index.=$this->customs["DOMAINE_TOXI"];
		
		// indexation libre
		$mots_cles = $this->do_mots_cle() ;
		if($mots_cles) $ret_index.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$libelle_key."</span></td><td>".nl2br($mots_cles)."</td></tr>";
			
		if ($ret_index) {
			$ret.=$ret_index;
			//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
		}
		
		// résumé
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td>".nl2br($this->notice->n_resume)."</td></tr>";
	
		// note de contenu
		if($this->notice->n_contenu) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
	
		// Permalink avec Id
		if ($opac_permalink) $ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";
	
		//Champs personalisés
		$perso_aff = "" ;
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"] && $p["NAME"] != "doma") $perso_aff .="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".strip_tags($p["TITRE"])."</span></td><td>".$p["AFF"]."</td></tr>";
			}
		}
		if ($perso_aff) {
			//Espace
			//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
			$ret .= $perso_aff ;
		}
		
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
		
		$this->affichage_suite = $ret ;
		$this->affichage_suite_flag = 1 ;
		return $ret ;
	}
	
		/*
	 * Chargement des champs persos
	 */
	function load_custom_fields(){
		$custom_fields = array();
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]){
					$value = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".strip_tags($p["TITRE"])."</span></td><td>".$p["AFF"]."</td></tr>";					
					if ($p["NAME"] == "doma"){
						$custom_fields["DOMAINE_TOXI"] = $value;
					}
				}    
			}
		}
		
		return $custom_fields;
	}
	
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
		else $mots_cles = implode(" $pmb_keyword_sep ", $tableau_mots);
		return $mots_cles ; 
	}
}

class notice_affichage_commande_copie extends notice_affichage {
	var $send_order ="";
	
	function do_header() {	
		global $msg;
		global $charset;
		global $lang;
		global $opac_url_base,$lang;
		
		if ($this->notice_header) return $this->notice_header ;
		
		parent::do_header();
		
		//booléen pour les articles de 5 ans et plus
		$condition_art = false;
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar ==2){
			if (date("Y")-($this->parent_date*1) >=5 ){
				$condition_art = true;
			}
		}

		$condition_chap = false;
		if($this->notice->typdoc == "w"){
			//si une année est défini
			if ($this->notice->year != ""){
				//en début d'année (de janvier à juin) on prend année strictement < à 2ans
				if(date("m")*1 <=6){
					if(date("Y")-$this->notice->year > 2){
						$condition_chap = true;
					}
				//dans le 2ème semestre on prend année <= à 2ans...	
				}else{
					if(date("Y")-$this->notice->year >= 2){
						$condition_chap = true;
					}					
				}
			}
		}
		
		if($condition_chap|| $condition_art)
		$this->send_order.= "
		&nbsp;<img src='images_bsf/commander_$lang.gif' onclick='document.send_order$this->notice_id.submit();'>
		<form method='post' name='send_order$this->notice_id' target='_blank' action='index.php?lvl=extend&sub=send_order'>
			<input type='hidden' name='order_notice_id' id='order_notice_id' value='$this->notice_id' />
		</form>";
	}
	
	// génération du de l'affichage double avec onglets ---------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	function genere_double($depliable=1, $premier='ISBD') {
		global $msg;
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $icon_doc,$biblio_doc,$tdoc;
		global $allow_tag; // l'utilisateur a-t-il le droit d'ajouter un tag
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
		if (($this->sugg_allowed ==2)|| ($_SESSION["user_code"] && $this->sugg_allowed ==1)) $img_tag .= $this->affichage_suggestion($this->notice_id);
		
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
	
		$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		if ($depliable) {
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_plus' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['expandable_notice']."\" border=\"0\" onclick=\"expandBase('el!!id!!', true);return false;\" hspace=\"3\" />";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>!!send_order!!
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">";
		} else {
			$template="<div class='parent'>$case_a_cocher";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>!!send_order!!";
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
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
		if($this->notice->opac_visible_bulletinage) $voir_bulletins="&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>";
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
		$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		$this->result = str_replace('!!send_order!!', $this->send_order, $this->result);
		$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
		if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result); 		
		$this->result = str_replace('!!SUITE!!', "", $this->result);
	}
	
	// génération du de l'affichage simple sans onglet ----------------------------------------------
	//	si $depliable=1 alors inclusion du parent / child
	function genere_simple($depliable=1, $what='ISBD') {
		global $msg; 
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $icon_doc,$biblio_doc,$tdoc;
		global $allow_tag ; // l'utilisateur a-t-il le droit d'ajouter un tag
		if(!$this->notice_id) return;
		
		$this->double_ou_simple = 1 ;
		$this->notice_childs = $this->genere_notice_childs();
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
		
		if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\" class=\"img_basket\"><img src='images/basket_small_20x20.gif' align='absmiddle' border='0' title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
		else $basket="";
		
		//add tags
		if (($this->tag_allowed==1)||(($this->tag_allowed==2)&&($_SESSION["user_code"])&&($allow_tag)))
			$img_tag.="<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='images/tag.png' align='absmiddle' border='0' title=\"".$msg['notice_title_tag']."\" alt=\"".$msg['notice_title_tag']."\"></a>";
		
		 //Avis
		if (($this->avis_allowed && $this->avis_allowed !=2)|| ($_SESSION["user_code"] && $this->avis_allowed ==2))
			$img_tag .= $this->affichage_avis($this->notice_id);
		
		//Suggestions
		if (($this->sugg_allowed ==2)|| ($_SESSION["user_code"] && $this->sugg_allowed ==1)) $img_tag .= $this->affichage_suggestion($this->notice_id);	
		 
		$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		
		if ($depliable) { 
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_plus' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg["expandable_notice"]."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\"/>";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>!!send_order!!
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">
	    		";			
		} else {
			$template="
				\n<div id=\"el!!id!!Parent\" class=\"parent\">
	    		$case_a_cocher";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    			
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}			
    		$template.="<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>!!send_order!!<br />";
		}
		$template.="!!CONTENU!!
					!!SUITE!!</div>";
		
		if($basket || $img_tag) $template_in.="<ul id='onglets_isbd_public'>
						<li id='baskets!!id!!' class='onglet_basket'>$basket</li>
	  					<li id='tags!!id!!' class='onglet_tags'>$img_tag</li>
					   </ul>	
		";
		if($what =='ISBD') $template_in.="		    	
				<div id='div_isbd!!id!!' style='display:block;'>!!ISBD!!</div>
	  			<div id='div_public!!id!!' style='display:none;'>!!PUBLIC!!</div>";
		else $template_in.="
		    	<div id='div_public!!id!!' style='display:block;'>!!PUBLIC!!</div>
				<div id='div_isbd!!id!!' style='display:none;'>!!ISBD!!</div>"
	  			; 	
		
		// Serials : différence avec les monographies on affiche [périodique] et [article] devant l'ISBD
		if ($this->notice->niveau_biblio =='s') {
			$template_in = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!ISBD!!", $template_in);
			$template_in = str_replace('!!PUBLIC!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!PUBLIC!!", $template_in);
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
		if($this->notice_header)
			$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		else $this->result = str_replace('!!heada!!', '', $this->result);
		if ($this->send_order)
		$this->result = str_replace('!!send_order!!', $this->send_order, $this->result);
		else $this->result = str_replace('!!send_order!!', '', $this->result);
		$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
		
		if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result);
		else $this->result = str_replace('!!SUITE!!', '', $this->result);
				
	}
}


/*
 * Classe d'affichage pour le RECI 
 */
class notice_affichage_reci extends notice_affichage {	
	
	// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour éviter la redondance de calcul
	function aff_suite() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search, $opac_permalink, $opac_url_base;
		
		// afin d'éviter de recalculer un truc déjà calculé...
		if ($this->affichage_suite_flag) return $this->affichage_suite ;
		
		$ret .= $this->genere_in_perio () ;
		
		// résumé
		if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_resume_start']."</span></td><td class='td_resume'>".nl2br($this->notice->n_resume)."</td></tr>";
	
		// note de contenu
		if($this->notice->n_contenu) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['n_contenu_start']."</span></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
		
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
		if ($perso_aff) {
			//Espace
			//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
			$ret .= $perso_aff ;
		}
		
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
				$ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";	
			}else {
				$ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=bulletin_display&id=".$this->bulletin_id."'>".substr($opac_url_base."index.php?lvl=bulletin_display&id=".$this->bulletin_id,0,80)."</a></td></tr>";
			}	
		}
		$this->affichage_suite = $ret ;
		$this->affichage_suite_flag = 1 ;
		return $ret ;
	} 
}	

class notice_affichage_ireps extends notice_affichage {
	
	// génération du de l'affichage double avec onglets
	//	si $depliable=1 alors inclusion du parent / child
	function genere_double($depliable=1, $premier='ISBD') {
		global $msg;
		global $cart_aff_case_traitement;
		global $opac_url_base ;
		global $opac_visionneuse_allow;
		global $icon_doc,$biblio_doc,$tdoc;
		global $allow_tag; // l'utilisateur a-t-il le droit d'ajouter un tag
		global $allow_sugg;// l'utilisateur a-t-il le droit de faire une suggestion
		global $lvl;	   // pour savoir qui demande l'affichage
		
		$this->result ="";
		if(!$this->notice_id) return;	
		$this->premier = $premier ;
		$this->double_ou_simple = 2 ;
		$this->notice_childs = $this->genere_notice_childs();
		if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\" class=\"img_basket\"><img src=\"/styles/ireps/images/panier.png\" border=\"0\" title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
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
		
		if ($depliable) {
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_plus' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['expandable_notice']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\" />";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    		
    			$info_bulle_icon=htmlentities($info_bulle_icon,ENT_QUOTES,$charset);	
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">";
		} else {
			$template="<div class='parent'>$case_a_cocher";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    	
    			$info_bulle_icon=htmlentities($info_bulle_icon,ENT_QUOTES,$charset);		
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>";
		}
	 	$template.="!!CONTENU!!
					!!SUITE!!</div>";
	
		//$template_in=$basket;
		$template_in.="<ul id='onglets_isbd_public'>";
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
		global $icon_doc,$biblio_doc,$tdoc;
		global $allow_tag ; // l'utilisateur a-t-il le droit d'ajouter un tag
		global $allow_sugg; // l'utilisateur a-t-il le droit de faire une suggestion
		global $lvl;		// pour savoir qui demande l'affichage
		
		if(!$this->notice_id) return;
		
		$this->double_ou_simple = 1 ;
		$this->notice_childs = $this->genere_notice_childs();
		// préparation de la case à cocher pour traitement panier
		if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
		else $case_a_cocher = "" ;
		
		if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\" class=\"img_basket\"><img src='/styles/ireps/images/panier.png' align='absmiddle' border='0' title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
		else $basket="";
		
		//add tags
		if (($this->tag_allowed==1)||(($this->tag_allowed==2)&&($_SESSION["user_code"])&&($allow_tag)))
			$img_tag.="<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='images/tag.png' align='absmiddle' border='0' title=\"".$msg['notice_title_tag']."\" alt=\"".$msg['notice_title_tag']."\"></a>";
		
		 //Avis
		if (($this->avis_allowed && $this->avis_allowed !=2)|| ($_SESSION["user_code"] && $this->avis_allowed ==2))
			$img_tag .= $this->affichage_avis($this->notice_id);
		
		//Suggestions
		if (($this->sugg_allowed ==2)|| ($_SESSION["user_code"] && ($this->sugg_allowed ==1) && $allow_sugg)) $img_tag .= $this->affichage_suggestion($this->notice_id);	
		 
		if ($this->no_header) $icon="";
		else $icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
		
		if ($depliable) { 
			$template="
				<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				$case_a_cocher
	    		<img class='img_plus' src=\"images/plus.gif\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg["expandable_notice"]."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\"/>";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg['info_bulle_icon']);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon); 
    			$info_bulle_icon= htmlentities($info_bulle_icon,ENT_QUOTES,$charset);			
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}
    		$template.="		
				<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span>
	    		<br />
				</div>
				<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">
	    		";			
		} else {
			$template="
				\n<div id=\"el!!id!!Parent\" class=\"parent\">
	    		$case_a_cocher";
			if ($icon) {
    			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    			$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);    		
    			$info_bulle_icon= htmlentities($info_bulle_icon,ENT_QUOTES,$charset);
    			$template.="<img src=\"images/$icon\" alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
    		}			
    		$template.="<span class=\"notice-heada\" draggable=\"yes\" dragtype=\"notice\" id=\"drag_noti_!!id!!\">!!heada!!</span><br />";
		}
		$template.="!!CONTENU!!
					!!SUITE!!</div>";
		
		if($basket || $img_tag) $template_in.="<ul id='onglets_isbd_public'>
						<li id='baskets!!id!!' class='onglet_basket'>$basket</li>
	  					<li id='tags!!id!!' class='onglet_tags'>$img_tag</li>
					   </ul>	
		";
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
		if($this->notice_header)
			$this->result = str_replace('!!heada!!', $this->notice_header, $this->result);
		else $this->result = str_replace('!!heada!!', '', $this->result);
		$this->result = str_replace('!!CONTENU!!', $template_in, $this->result);
		
		if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result);
		else $this->result = str_replace('!!SUITE!!', '', $this->result);
				
	} // fin genere_simple($depliable=1, $what='ISBD')


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
		
		$this->notice_header_without_html = $this->notice_header;	
	
		$this->notice_header = "<span !!zoteroNotice!! class='header_title'>".$this->notice_header."</span>";	
		//on ne propose à Zotero que les monos et les articles...
		if($this->notice->niveau_biblio == "m" ||($this->notice->niveau_biblio == "a" && $this->notice->niveau_hierar == 2)) {
			$this->notice_header =str_replace("!!zoteroNotice!!"," notice='".$this->notice_id."' ",$this->notice_header);
		}else $this->notice_header =str_replace("!!zoteroNotice!!","",$this->notice_header);
		$notice_header_suite = "";
		if ($type_reduit=="T" && $this->notice->tit4) $notice_header_suite = " : ".$this->notice->tit4;
		if ($this->auteurs_principaux) $notice_header_suite .= " / ".$this->auteurs_principaux;
		
		if ($this->notice->niveau_biblio =='m') {
			switch($type_reduit) {
				case '1':
					if ($this->notice->year != '') $notice_header_suite.=' / ('.htmlentities($this->notice->year,ENT_QUOTES,$charset).')';
					break;
				case '2':
					if ($this->notice->year != '' && $this->notice->niveau_biblio!='b') $notice_header_suite.=' / ('.htmlentities($this->notice->year, ENT_QUOTES, $charset).')';
					if ($this->notice->code != '') $notice_header_suite.=' / '.htmlentities($this->notice->code, ENT_QUOTES, $charset);
					break;
				default:
					break;
			}
		}
		
		if ($editeur_reduit) $notice_header_suite .= " / ".$editeur_reduit ;
		if ($perso_voulu_aff) $notice_header_suite .= " / ".$perso_voulu_aff ;
		if ($aff_perio_title) $notice_header_suite .= " ".$aff_perio_title;
		$this->notice_header_without_html .= $notice_header_suite ;
		$this->notice_header .= $notice_header_suite."</span>";	
		//$this->notice_header.="&nbsp;<span id=\"drag_symbol_drag_noti_".$this->notice->notice_id."\" style=\"visibility:hidden\"><img src=\"images/drag_symbol.png\"\></span>";
		
		if ($this->notice->lien) {
			if(!$this->notice->eformat) $info_bulle=$msg["open_link_url_notice"];
			else $info_bulle=$this->notice->eformat;
			// ajout du lien pour les ressource électroniques
			$this->notice_header .= "&nbsp;<span class='notice_link'><a href=\"".$this->notice->lien."\" target=\"__LINK__\">";
			$this->notice_header .= "<img src=\"images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
			$this->notice_header .= " alt=\"";
			$this->notice_header .= $info_bulle;
			$this->notice_header .= "\" title=\"";
			$this->notice_header .= $info_bulle;
			$this->notice_header .= "\">";
			$this->notice_header .= "</a></span>";
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
				$this->notice_header .= "&nbsp;<span>";		
				if ($opac_visionneuse_allow && $this->docnum_allowed){
					$this->notice_header .="
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
					$this->notice_header .= "<a href=\"./doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">";
				}
				$this->notice_header .= "<img src=\"./images/globe_orange.png\" border=\"0\" align=\"middle\" hspace=\"3\"";
				$this->notice_header .= " alt=\"";
				$this->notice_header .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header .= "\" title=\"";
				$this->notice_header .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header .= "\">";
				$this->notice_header .= "</a></span>";
			} elseif ($explnumscount > 1) {
				$explnumrow = mysql_fetch_object($explnums);
				$info_bulle=$msg["info_docs_num_notice"];
				$this->notice_header .= "<img src=\"./images/globe_rouge.png\" alt=\"$info_bulle\" \" title=\"$info_bulle\" border=\"0\" align=\"middle\" hspace=\"3\">";
			}
		}
		$memo_notice[$this->notice_id]["header"]=$this->notice_header;
		$memo_notice[$this->notice_id]["niveau_biblio"]	= $this->notice->niveau_biblio;
		
		$this->notice_header_with_link=inslink($this->notice_header, str_replace("!!id!!", $this->notice_id, $this->lien_rech_notice)) ;
	} // fin do_header()
	
	
	
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
		if ($perso_aff) {
			//Espace
			//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
			$ret .= $perso_aff ;
		}
		
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
				$ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>index.php?lvl=notice_display&id=".$this->notice_id."</a></td></tr>";	
			}else {
				$ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=bulletin_display&id=".$this->bulletin_id."'>index.php?lvl=bulletin_display&id=".$this->bulletin_id."</a></td></tr>";
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
					$expl_liste.="<td class='".$colonnesarray[$i]."'><a href=\"index.php?lvl=infopages&pagesid=".$expl->num_infopage."\" alt=\"".$msg['location_more_info']."\" title=\"".$msg['location_more_info']."\">".htmlentities($colencours, ENT_QUOTES, $charset)."</a></td>";
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
				$situation = $msg['expl_reserve'];
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
						$situation .= $msg['out_until'].formatdate($expl->pret_retour);
						// ****** Affichage de l'emprunteur
					} else { // pas sorti
						$situation = $msg['available'];
					}
				} else { // pas prêtable
					// exemplaire pas prêtable, on affiche juste "exclu du pret"
					if (($pmb_transferts_actif=="1")&&("".$expl->expl_statut.""==$transferts_statut_transferts))
						$situation = $msg['reservation_lib_entransfert'];
					else
						$situation = $msg['exclu'];
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

	
}

class notice_affichage_invs extends notice_affichage {
	// récupération des auteurs ---------------------------------------------------------------------
	// retourne $this->auteurs_principaux = ce qu'on va afficher en titre du résultat
	// retourne $this->auteurs = ce qu'on va afficher dans l'isbd
	// retourne $this->appartenance = ce qu'on va afficher dans l'isbd
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
			if ($notice->author_web) $auteur_web_link = " <a href='$notice->author_web' target='_blank'><img src='images/globe.gif' border='0'/></a>";
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
			$appartenance_mention_resp[] = $auteur_2["auteur_isbd"];

		}
		
		$libelle_mention_resp = implode (" ; ",$mention_resp) ;
		if ($libelle_mention_resp) $this->auteurs = $libelle_mention_resp ;
		else $this->auteurs ="" ;
		
		$libelle_congres_resp = implode (" ; ",$congres_resp) ;
		if ($libelle_congres_resp) $this->congres_tous = $libelle_congres_resp ;
		else $this->congres_tous ="" ;
		
		$appartenance_libelle_mention_resp = implode (" ; ",$appartenance_mention_resp) ;
		if ($appartenance_libelle_mention_resp) $this->appartenance = $appartenance_libelle_mention_resp ;
		else $this->appartenance ="" ;
		
		
		$this->auteurs_tous = $this->auteurs.($this->auteurs && $this->appartenance ? " ; " : "").$this->appartenance;
		
	} // fin fetch_auteurs
	
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
			
		$this->notice_public .= "<table class='invs-notice'>";
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
		
		if ($this->auteurs) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['auteur_start']."</span></td><td>".$this->auteurs."</td></tr>";
		if ($this->appartenance) $this->notice_public .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['appartenance_auteur_start']."</span></td><td>".$this->appartenance."</td></tr>";
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
		if ($perso_aff) {
			//Espace
		//	if($this->notice->n_resume) $ret.="<tr><td class='bg-grey'>&nbsp;</td><td>&nbsp;</td></tr>";
			$ret .= $perso_aff ;
		}
		
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
				$ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";	
			}else {
				$ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=bulletin_display&id=".$this->bulletin_id."'>".substr($opac_url_base."index.php?lvl=bulletin_display&id=".$this->bulletin_id,0,80)."</a></td></tr>";
			}	
		}
		$this->affichage_suite = $ret ;
		$this->affichage_suite_flag = 1 ;
		return $ret ;
	} // fin aff_suite()
	
}

/*
 * WMO-CUSTOM: Classe d'affichage pour WMO - World meteorological Organisation
 */
class notice_affichage_wmo extends notice_affichage {

	var $collectivite_tous = "";
	var $customs = array();
	
		/*
	 * Affichage public
	 */
	function do_public($short=0,$ex=1){
		global $dbh;
			global $msg;
			global $charset;
			global $memo_notice;
			global $lang, $tdoc, $langue_doc;
			global $icon_doc,$biblio_doc;
			
			
			$this->notice_public="";
			if(!$this->notice_id) return;
			
					
			//vignette
			if ($this->notice->thumbnail_url) {$url_image_ok=$this->notice->thumbnail_url;}
			else {$url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
			$image = $url_image_ok;}
	
			if ($this->notice->thumbnail_url) {
				$vignette ="<img src='".$url_image_ok."'id='vignette_wmo'>";
			}
			$this->notice_public .="<div class='vignette'>".$vignette."</div>";
    		
			
			/*if ($this->notice->niveau_biblio =='b') "<style type='css/text'>
			img.icondoc{
			display:none;
			}
			</style>";*/
			
			// constitution de la mention de titre
			
			if ($this->notice->niveau_biblio =='s' || $this->notice->niveau_biblio =='a') {
			$this->notice_public .= "<div id='wmo_serial_title'><h3 id='wmo_title_detailed'>".$this->notice->tit1;}
			elseif ($this->notice->niveau_biblio =='b') {
			$this->notice_public .= "<div id='wmo_bulletin_title'><h3 id='wmo_title_detailed'>".$this->notice->tit1;}
			else {$this->notice_public .= "<div id='wmo_title_detailed'><h3 id='wmo_title_detailed'>".$this->notice->tit1;
			}
			if ($this->notice->tit4) $this->notice_public .= ":&nbsp;".$this->notice->tit4."</h3></div>";
			else $this->notice_public .="</h3></div>";
						
			if ($this->notice->tit2) $this->notice_public .= "<br/>".$this->notice->tit2;
		
			
			
			//Recherche des notices parentes
			$requete="select linked_notice, relation_type, rank from notices_relations where num_notice=".$this->notice_id." order by relation_type,rank";
			$result_linked=mysql_query($requete,$dbh);
			//Si il y en a, on prépare l'affichage
			if (mysql_num_rows($result_linked)) {
				global $relation_listup ;
				if (!$relation_listup) $relation_listup=new marc_list("relationtypeup");
			}
			$r_type=array();
			$ul_opened=false;
			//Pour toutes les notices liées
			while (($r_rel=mysql_fetch_object($result_linked))) {			
				if($memo_notice[$r_rel->linked_notice]["header"]) {
					$parent_notice->notice_header=$memo_notice[$r_rel->linked_notice]["header"];	
				} else {
					$parent_notice=new notice_affichage($r_rel->linked_notice,$this->liens,1,$this->to_print,1);
					$parent_notice->visu_expl = 0 ;
					$parent_notice->visu_explnum = 0 ;
					$parent_notice->do_header();
				}		
				//Présentation différente si il y en a un ou plusieurs
				if (mysql_num_rows($result_linked)==1) {
					$this->notice_public.="<br /><b class='parent_notice'>".$relation_listup->table[$r_rel->relation_type]."</b> ";
					if ($this->lien_rech_notice) $this->notice_public.="<a href='".str_replace("!!id!!",$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>";
					$this->notice_public.=$parent_notice->notice_header;
					if ($this->lien_rech_notice) $this->notice_public.="</a>";
					$this->notice_public.="<br />";
					// si une seule, peut-être est-ce une notice de bulletin, aller cherche $this>bulletin_id
					$rqbull="select bulletin_id from bulletins where num_notice=".$this->notice_id;
					$rqbullr=mysql_query($rqbull);
					$rqbulld=@mysql_fetch_object($rqbullr);
					$this->bulletin_id=$rqbulld->bulletin_id;
			
				} else {
					if (!$r_type[$r_rel->relation_type]) {
						$r_type[$r_rel->relation_type]=1;
						if ($ul_opened) $this->notice_public.="</ul>"; else { $this->notice_public.="<br />"; $ul_opened=true; }
						$this->notice_public.="<div class='linked_notices'>".$relation_listup->table[$r_rel->relation_type]."</div>";
						$this->notice_public.="<ul class='notice_rel'>\n";
					}
					$this->notice_public.="<li>";
					if ($this->lien_rech_notice) $this->notice_public.="<a href='".str_replace("!!id!!",$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>";
					$this->notice_public.=$parent_notice->notice_header;
					if ($this->lien_rech_notice) $this->notice_public.="</a>";
					$this->notice_public.="</li>\n";
				}
				if (mysql_num_rows($result_linked)>1) $this->notice_public.="</ul>\n";
			}
			
			
			//Responsabilités
			
			//auteurs physiques
			$this->notice_public .="<span class='responsability'>";
			if ($this->auteurs_tous) $this->notice_public .= $this->auteurs_tous;
			
			//auteurs collectivités
			if ($this->auteurs_tous && $this->collectivite_tous) 
			$this->notice_public .=",&nbsp;".$this->collectivite_tous;
			elseif (!$this->auteurs_tous) $this->notice_public .=$this->collectivite_tous;
			
			//auteurs congrès
			if (($this->auteurs_tous || $this->collectivite_tous) && $this->congres_tous) 
			$this->notice_public .=",&nbsp;".$this->congres_tous;
			elseif (!$this->auteurs_tous || !$this->collectivite_tous) 
			$this->notice_public .=$this->congres_tous;
		
			
		
			
			// zone de l'éditeur 
			if ($this->notice->ed1_id) {
				$editeur = new publisher($this->notice->ed1_id);
				$editeur_reduit = inslink($editeur->display,  str_replace("!!id!!", $this->notice->ed1_id, $this->lien_rech_editeur)) ;
				if ($this->notice->year) $editeur_reduit .= ",&nbsp;".$this->notice->year." ";
				$editeur_reduit="&nbsp;-&nbsp;".$editeur_reduit;
			} elseif ($this->notice->year) { 
				// année mais pas d'éditeur et si pas un article
				if($this->notice->niveau_biblio != 'a' && $this->notice->niveau_hierar != 2) 	$editeur_reduit = $this->notice->year." ";
			} else $editeur_reduit = "" ;
			
			$this->notice_public .=$editeur_reduit;
			$this->notice_public .="</span>";
			
			//Subtype
			if($this->customs["SUBTYPE"]) $this->notice_public .= $this->customs["SUBTYPE"] ;
			
			if (!$short) $this->notice_public .= $this->aff_suite_public(); 
			else $this->notice_public.=$this->genere_in_perio();
			
			//etat des collections
			if ($this->notice->niveau_biblio=='s' && $this->notice->niveau_hierar==1) $this->notice_public.=$this->affichage_etat_collections();	
			
			
			// exemplaires, résas et compagnie
			if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl();
		
			return;	
	}
	
	
	// fonction d'affichage de la suite PUBLIC 
	function aff_suite_public() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search,  $opac_url_base;
		
		$ret .= $this->genere_in_perio () ;
				
		// résumé
		if($this->notice->n_resume) $ret .= "<p class='resume_wmo'>".$this->notice->n_resume."</p>";
		
		//Langues		
		if (count($this->langues)) {
			$ret.= "<p class='wmo_languages'><span class='field_label'>".$msg['537'].":&nbsp;</span>".$this->construit_liste_langues($this->langues);
			if (count($this->languesorg)) $ret.= ",&nbsp;".$this->construit_liste_langues($this->languesorg);
			$this->notice_public.="</p>";
			} elseif (count($this->languesorg)) {
			$ret.= ",&nbsp;".$this->construit_liste_langues($this->languesorg)."</p>"; 
			}
			
		// Champs personnalisés
		$perso_aff = "" ;
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]) $ret .="<br/><span class='perso_wmo'>".$p["TITRE"]."</span>".$p["AFF"]."<br/></span>";
			}
		}
		
		/** toutes indexations **/
		$ret_index = "";
		
		// Catégories
		
		// Affectation du libellé mots clés ou tags en fonction de la recherche précédente	
		if($opac_allow_tags_search == 1) $libelle_key = $msg['tags'];
		else $libelle_key = $msg['motscle_start'];
		$mots_cles = $this->do_mots_cle() ;
		
		
		if ($this->categories_toutes) {$ret_index .= "<p class='tags_wmo'><img src='styles/wmo/images/keywords.png' align='top' alt='keywords'>&nbsp;<span class='field_label'>".$msg['motscle_start']."</span>".$this->categories_toutes;}
		if($mots_cles) $ret_index.= ", ".nl2br($mots_cles);
		
		$ret_index .= "</p>";
		if ($ret_index) 
			$ret.=$ret_index;
		
		$this->affichage_suite = $ret ;
		return $ret ;
		
	
	} 
	
	// fonction d'affichage de la suite PUBLIC 
	function aff_suite_isbd() {
		global $msg;
		global $charset;
		global $opac_allow_tags_search, $opac_permalink, $opac_url_base;
		
		$ret .= $this->genere_in_perio () ;
				
		
		
		// ISBN ou NO. commercial
		if ($this->notice->code) $ret .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['code_start']."</span></td><td>".$this->notice->code."</td></tr>";
			
		
		
		// Permalink avec Id
		if ($opac_permalink) $ret.= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg["notice_permalink"]."</span></td><td><a href='index.php?lvl=notice_display&id=".$this->notice_id."'>".substr($opac_url_base."index.php?lvl=notice_display&id=".$this->notice_id,0,80)."</a></td></tr>";
	
		$this->affichage_suite = $ret ;
		return $ret ;
	} 
	
	
	
	/*
	 * Récuperation des autorites
	 */
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
			if ($notice->author_web) $auteur_web_link = " <a href='$notice->author_web' target='_blank'><img src='images/globe.gif' border='0'/></a>";
			else $auteur_web_link = "" ;
			if (!$this->to_print) $auteur_isbd .= $auteur_web_link ;
			$auteur_isbd = inslink($auteur_isbd, str_replace("!!id!!", $notice->author_id, $this->lien_rech_auteur),$info_bulle) ;
			/*if ($notice->responsability_fonction) $auteur_isbd .= ", ".$fonction_auteur[$notice->responsability_fonction] ;*/
			$auteurs[] = array( 
					'id' => $notice->author_id,
					'fonction' => $notice->responsability_fonction,
					'responsability' => $notice->responsability_type,
					'name' => $notice->author_name,
					'rejete' => $notice->author_rejete,
					'date' => $notice->author_date,
					'type' => $notice->author_type,
					
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
			$auteurs_liste = implode ("; ",$aut1_libelle) ;
			if ($auteurs_liste) $this->auteurs_principaux = $auteurs_liste ;
		}
	
		// $this->auteurs_tous
		$mention_resp = array() ;
		$collectivite_resp = array();
		$congres_resp = array() ;
		$as = array_search ("0", $this->responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $this->responsabilites["auteurs"][$as] ;
			$mention_resp_lib = $auteur_0["auteur_isbd"];
			if($this->responsabilites["auteurs"][$as]["type"]==72) {
				$congres_resp[] = $mention_resp_lib ;
			} else if($this->responsabilites["auteurs"][$as]["type"]==71){
				$collectivite_resp[] = $mention_resp_lib;
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
			} else if($this->responsabilites["auteurs"][$indice]["type"]==71){
				$collectivite_resp[] = $mention_resp_lib;
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
			} else if($this->responsabilites["auteurs"][$indice]["type"]==71){
				$collectivite_resp[] = $mention_resp_lib;
			} else {
				$mention_resp[] = $mention_resp_lib ;
			}		
		}
		
		
		$libelle_mention_resp = implode ("; ",$mention_resp) ;
		if ($libelle_mention_resp) $this->auteurs_tous = $libelle_mention_resp ;
		else $this->auteurs_tous ="" ;
		
		$libelle_collectivite_resp = implode ("; ",$collectivite_resp) ;
		if ($libelle_collectivite_resp) $this->collectivite_tous = $libelle_collectivite_resp ;
		else $this->collectivite_tous ="" ;
		
		$libelle_congres_resp = implode ("; ",$congres_resp) ;
		if ($libelle_congres_resp) $this->congres_tous = $libelle_congres_resp ;
		else $this->congres_tous ="" ;
		
	}
	
	
	/*
	 * Affichage ISBD
	 */
	function do_isbd($short=0,$ex=1) {
		
		global $dbh;
		global $msg;
		global $tdoc;
		global $charset;
		global $opac_notice_affichage_class;
		global $memo_notice;
		
		$this->notice_isbd="";
		if(!$this->notice_id) return;
		
	
		
		//Recherche des notices parentes
		$requete="select linked_notice, relation_type, rank from notices_relations where num_notice=".$this->notice_id." order by relation_type,rank";
		$result_linked=mysql_query($requete,$dbh);
		//Si il y en a, on prépare l'affichage
		if (mysql_num_rows($result_linked)) {
			global $relation_listup ;
			if (!$relation_listup) $relation_listup=new marc_list("relationtypeup");
		}
		$r_type=array();
		$ul_opened=false;
		//Pour toutes les notices liées
		while (($r_rel=mysql_fetch_object($result_linked))) {			
			if($memo_notice[$r_rel->linked_notice]["header"]) {
				$parent_notice->notice_header=$memo_notice[$r_rel->linked_notice]["header"];	
			} else {
				$parent_notice=new notice_affichage($r_rel->linked_notice,$this->liens,1,$this->to_print,1);
				$parent_notice->visu_expl = 0 ;
				$parent_notice->visu_explnum = 0 ;
				$parent_notice->do_header();
			}		
			//Présentation différente si il y en a un ou plusieurs
			if (mysql_num_rows($result_linked)==1) {
				$this->notice_isbd.="<br /><b>".$relation_listup->table[$r_rel->relation_type]."</b> ";
				if ($this->lien_rech_notice) $this->notice_isbd.="<a href='".str_replace("!!id!!",$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>";
				$this->notice_isbd.=$parent_notice->notice_header;
				if ($this->lien_rech_notice) $this->notice_isbd.="</a>";
				$this->notice_isbd.="<br /><br />";
				// si une seule, peut-être est-ce une notice de bulletin, aller cherche $this>bulletin_id
				$rqbull="select bulletin_id from bulletins where num_notice=".$this->notice_id;
				$rqbullr=mysql_query($rqbull);
				$rqbulld=@mysql_fetch_object($rqbullr);
				$this->bulletin_id=$rqbulld->bulletin_id;
		
			} else {
				if (!$r_type[$r_rel->relation_type]) {
					$r_type[$r_rel->relation_type]=1;
					if ($ul_opened) $this->notice_isbd.="</ul>"; else { $this->notice_isbd.="<br />"; $ul_opened=true; }
					$this->notice_isbd.="<b>".$relation_listup->table[$r_rel->relation_type]."</b>";
					$this->notice_isbd.="<ul class='notice_rel'>\n";
				}
				$this->notice_isbd.="<li>";
				if ($this->lien_rech_notice) $this->notice_isbd.="<a href='".str_replace("!!id!!",$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>";
				$this->notice_isbd.=$parent_notice->notice_header;
				if ($this->lien_rech_notice) $this->notice_isbd.="</a>";
				$this->notice_isbd.="</li>\n";
			}
			if (mysql_num_rows($result_linked)>1) $this->notice_isbd.="</ul>\n";
		}
	
		$this->notice_isbd .= "<table>";
		// constitution de la mention de titre
				
		$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['title']." :</span></td>";
		$this->notice_isbd .= "<td><span class='public_title'>".$this->notice->tit1 ;
		
		if ($this->notice->tit4) $this->notice_isbd .= "&nbsp;: ".$this->notice->tit4 ;
		$this->notice_isbd.="</span></td></tr>";
		
		if ($this->notice->tit2) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t2']." :</span></td><td>".$this->notice->tit2."</td></tr>" ;
		if ($this->notice->tit3) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_title_t3']." :</span></td><td>".$this->notice->tit3."</td></tr>" ;
		
		//Auteurs	
		if ($this->auteurs_tous) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['auteur_start']."</span></td><td>".$this->auteurs_tous."</td></tr>";
		if ($this->congres_tous) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['congres_aff_public_libelle']."</span></td><td>".$this->congres_tous."</td></tr>";
		if ($this->collectivite_tous) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['collectivites_search'].":</span></td><td>".$this->collectivite_tous."</td></tr>";
		
		
			
		// zone de l'éditeur 
		if ($this->notice->year)
			$annee = "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['year_start']."</span></td><td>".$this->notice->year."</td></tr>" ;
	
		if ($this->notice->ed1_id) {
			$editeur = new publisher($this->notice->ed1_id);
			$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['editeur_start']."</span></td><td>".inslink($editeur->display,  str_replace("!!id!!", $this->notice->ed1_id, $this->lien_rech_editeur))."</td></tr>" ;
			if ($annee) {
				$this->notice_isbd .= $annee ;
				$annee = "" ;
			}  
		}
		// Autre editeur
		if ($this->notice->ed2_id) {
			$editeur_2 = new publisher($this->notice->ed2_id);
			$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['other_editor']."</span></td><td>".inslink($editeur_2->display,  str_replace("!!id!!", $this->notice->ed2_id, $this->lien_rech_editeur))."</td></tr>" ;
		}					
		// $annee est vide si ajoutée avec l'éditeur, donc si pas éditeur, on l'affiche ici
		$this->notice_isbd .= $annee ;
		
		//Open Repository
		$this->notice_isbd .= $this->customs["REPOS"];
		
		//Subtype
		$this->notice_isbd .= $this->customs["SUBTYPE"];
		
		// zone de la collation
		if($this->notice->npages) {
			if ($this->notice->niveau_biblio<>"a") {
				$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start']."</span></td><td>".$this->notice->npages."</td></tr>";
			} else {
				$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['npages_start_perio']."</span></td><td>".$this->notice->npages."</td></tr>";
			}
		}
		if ($this->notice->ill) $this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['ill_start']."</span></td><td>".$this->notice->ill."</td></tr>";
			
		// langues
		if (count($this->langues)) {
			$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['537']." :</span></td><td>".$this->construit_liste_langues($this->langues);
			if (count($this->languesorg)) $this->notice_isbd .= " <span class='etiq_champ'>".$msg['711']." :</span> ".$this->construit_liste_langues($this->languesorg);
			$this->notice_isbd.="</td></tr>";
		} elseif (count($this->languesorg)) {
			$this->notice_isbd .= "<tr><td align='right' class='bg-grey'><span class='etiq_champ'>".$msg['711']." :</span></td><td>".$this->construit_liste_langues($this->languesorg)."</td></tr>"; 
		}
		
		//Champs personalisés
		if (!$this->p_perso->no_special_fields) {
			$perso_=$this->p_perso->show_fields($this->notice_id);
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				if ($p['OPAC_SHOW'] && $p["AFF"]) $this->notice_isbd .="<span class='etiq_champ'>".$p["TITRE"]."</span>".$p["AFF"]."<br />";
			}
		}
		
		if (!$short) $this->notice_isbd .= $this->aff_suite_isbd(); 
		else $this->notice_isbd.=$this->genere_in_perio();
	
		$this->notice_isbd.="</table>\n";
		
		//etat des collections
		if ($this->notice->niveau_biblio=='s' && $this->notice->niveau_hierar==1) $this->notice_isbd.=$this->affichage_etat_collections();	
		
		// exemplaires, résas et compagnie
		if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
	
		return;	
	
	}	
	
	// fonction d'affichage des exemplaires numeriques
	function aff_explnum () {
		
		global $msg;
		$ret='';
		if ( (is_null($this->dom_2) && $this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) || ($this->rights & 16)){
			if ($this->notice->niveau_biblio=="b" && ($explnum = $this->show_explnum_per_notice(0, $this->bulletin_id, ''))) {
				$ret .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
				$this->affichage_expl .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
			} elseif (($explnum = $this->show_explnum_per_notice($this->notice_id,0, ''))) {
				$ret .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
				$this->affichage_expl .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
			}
		}		 
		return $ret;
	}
	/*
	 * Chargement des champs persos
	 */
	 

	
	// fonction retournant les infos d'exemplaires numériques pour une notice ou un bulletin donné
	function show_explnum_per_notice($no_notice, $no_bulletin, $link_expl='') {
		
		// params :
		// $link_expl= lien associé à l'exemplaire avec !!explnum_id!! à mettre à jour
		global $dbh;
		global $charset;
		global $opac_url_base ;
		
		if (!$no_notice && !$no_bulletin) return "";
		
		global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
		create_tableau_mimetype() ;
	
		// récupération du nombre d'exemplaires
		$requete = "SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, explnum_vignette, explnum_nomfichier, explnum_extfichier 
		FROM explnum join explnum_location on (num_explnum=explnum_id and num_location='".$_SESSION['empr_location']."') WHERE ";
		if ($no_notice && !$no_bulletin) $requete .= "explnum_notice='$no_notice' ";
		elseif (!$no_notice && $no_bulletin) $requete .= "explnum_bulletin='$no_bulletin' ";
		elseif ($no_notice && $no_bulletin) $requete .= "explnum_bulletin='$no_bulletin' or explnum_notice='$no_notice' ";
		$requete .= " order by explnum_mimetype, explnum_id ";
		$res = mysql_query($requete, $dbh);
		$nb_ex = mysql_num_rows($res);
		
		if($nb_ex) {
			// on récupère les données des exemplaires
			$i = 1 ;
			global $search_terms;
			
			while (($expl = mysql_fetch_object($res))) {
				if ($i==1) $ligne="<tr><td class='docnum' width='33%'>!!1!!</td><td class='docnum' width='33%'>!!2!!</td><td class='docnum' width='33%'>!!3!!</td></tr>" ;
				if ($link_expl) {
					$tlink = str_replace("!!explnum_id!!", $expl->explnum_id, $link_expl);
					$tlink = str_replace("!!notice_id!!", $expl->explnum_notice, $tlink);					
					$tlink = str_replace("!!bulletin_id!!", $expl->explnum_bulletin, $tlink);					
					} 
				$alt = htmlentities($expl->explnum_nom." - ".$expl->explnum_mimetype,ENT_QUOTES, $charset) ;
				
				if ($expl->explnum_vignette) $obj="<img src='/vig_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' border='0'>";
					else // trouver l'icone correspondant au mime_type
						$obj="<img src='/images/mimetype/".icone_mimetype($expl->explnum_mimetype, $expl->explnum_extfichier)."' alt='$alt' title='$alt' border='0'>";		
				$expl_liste_obj = "<center>";
				
				$words_to_find="";
				if(($expl->explnum_mimetype=='application/pdf') ||($expl->explnum_mimetype=='URL' && (strpos($expl->explnum_nom,'.pdf')!==false))){
					$words_to_find = "#search=\"".trim(str_replace('*','',implode(' ',$search_terms)))."\"";
				}
				$expl_liste_obj .= "<a href='/doc_num.php?explnum_id=$expl->explnum_id$words_to_find' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;
				
				if ($_mimetypes_byext_[$expl->explnum_extfichier]["label"]) $explmime_nom = $_mimetypes_byext_[$expl->explnum_extfichier]["label"] ;
					elseif ($_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"]) $explmime_nom = $_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"] ;
						else $explmime_nom = $expl->explnum_mimetype ;
				
				
				if ($tlink) {
					$expl_liste_obj .= "<a href='$tlink'>";
					$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."</a><div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
					} else {
						$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."<div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
						}
				$expl_liste_obj .= "</center>";
				$ligne = str_replace("!!$i!!", $expl_liste_obj, $ligne);
				$i++;
				if ($i==4) {
					$ligne_finale .= $ligne ;
					$i=1;
					}
				}
			if (!$ligne_finale) $ligne_finale = $ligne ;
				elseif ($i!=1) $ligne_finale .= $ligne ;
			$ligne_finale = str_replace('!!2!!', "&nbsp;", $ligne_finale);
			$ligne_finale = str_replace('!!3!!', "&nbsp;", $ligne_finale);
			
			} else return "";
		$entry .= "<table class='docnum'>$ligne_finale</table>";
		return $entry;
	
	}
	
	// récupération des categories ------------------------------------------------------------------
	function fetch_categories() {
		$this->categories = get_notice_categories($this->notice_id) ;
		// catégories
		$categ_repetables=array() ;
		$max_categ = sizeof($this->categories) ; 
		for ($i = 0 ; $i < $max_categ ; $i++) {
			$categ_id = $this->categories[$i]["categ_id"] ;
			$categ = new category($categ_id);
			$categ_repetables[$categ->path_table[0]["libelle"]][$categ_id] = $categ->libelle;
			}
		$categ_final_table=array();
		while (list($key,$val)=each($categ_repetables)) {
			$categ_final_table[$key]=$key;
			asort($val) ;
			reset($val);
			$categ_r=array();
			while (list($categ_id,$libelle)=each($val)) {
				$categ_r[$categ_id] = inslink($libelle,  str_replace("!!id!!", $categ_id, $this->lien_rech_categ));
				}
			$categ_final_table[$key].="&nbsp;".implode(", ",$categ_r);
			}
		$this->categories_toutes = implode($categ_final_table) ;
		}
	
	
// génération du header----------------------------------------------------
	function do_header() {

		global $opac_notice_reduit_format ;
		global $opac_url_base, $msg ;
		global $memo_notice;
		global $icon_doc,$biblio_doc;
		global $msg;
		global $tdoc;
		$this->notice_header="";
		if(!$this->notice_id) return;	
		
		$this->notice_header .="</span><span class='wmo_header'>";
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
				$editeur_reduit = inslink($editeur->display,  str_replace("!!id!!", $this->notice->ed1_id, $this->lien_rech_editeur)) ;
				if ($this->notice->year) $editeur_reduit .= ",&nbsp; ".$this->notice->year." ";
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
		
				
		//Vignette
			
		if ($this->notice->thumbnail_url) {$url_image_ok=$this->notice->thumbnail_url;}
			else {$url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
			$image = $url_image_ok;}
	
		if($this->notice->thumbnail_url && $this->notice->typdoc == "n"){
				$vignette ="<div class='web_vignette'> <img src='".$url_image_ok."'id='web_vignette_wmo'></div>";
				}
				elseif ($this->notice->thumbnail_url) {
				$vignette ="<div class='vignette'> <img src='".$url_image_ok."'id='petite_vignette_wmo'></div>";
				}
				$this->notice_header .=$vignette;
		
		//icondoc
			$icon = $icon_doc[$this->notice->niveau_biblio.$this->notice->typdoc];
			$info_bulle_icon=str_replace("!!niveau_biblio!!",$biblio_doc[$this->notice->niveau_biblio],$msg["info_bulle_icon"]);
    		$info_bulle_icon=str_replace("!!typdoc!!",$tdoc->table[$this->notice->typdoc],$info_bulle_icon);   
    		
			$this->notice_header .="<img src=\"images/$icon\" class='icondoc' alt='".$info_bulle_icon."' title='".$info_bulle_icon."'/>";
		
		
		//Titre + WMO-URL de la notice sur le header
		if ($this->notice_header && $this->notice->tit4) {$this->notice_header .= "<b class='heada_wmo'><a href='index.php?lvl=notice_display&id=".$this->notice_id."' class='heada_wmo'>".$this->notice->tit1."&nbsp;: ".$this->notice->tit4."</a>";}
			elseif (!$this->notice->tit4) $this->notice_header .= "<b class='heada_wmo'><a href='index.php?lvl=notice_display&id=".$this->notice_id."' class='heada_wmo'>".$this->notice->tit1."</a>" ;
		
		
		// récupération du titre de série
		// constitution de la mention de titre
		if($this->notice->serie_name) {
			$this->notice_header .= ", ".$this->notice->serie_name;
			if($this->notice->tnvol) $this->notice_header .= ', '.$this->notice->tnvol;
		} elseif ($this->notice->tnvol) $this->notice_header .= ", ".$this->notice->tnvol;
		$this->notice_header .= "</b>";
		
		$this->notice_header .= $aff_bullperio_title;
		$this->notice_header_without_html = $this->notice_header;	
		$this->notice_header = $this->notice_header;	
		
		//Si c'est un depouillement, ajout du titre et bulletin
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2)  {
			
			 $aff_perio_title="<i>in ".$this->parent_title.", ".$this->parent_numero." (".($this->parent_date?$this->parent_date:"[".$this->parent_aff_date_date."]").")</i>";
		}
		if ($aff_perio_title) $notice_header_suite .= " ".$aff_perio_title;
			
		//$this->notice_header.="&nbsp;<span id=\"drag_symbol_drag_noti_".$this->notice->notice_id."\" style=\"visibility:hidden\"><img src=\"images/drag_symbol.png\"\></span>";
		$this->notice_header_with_link=inslink($this->notice_header, str_replace("!!id!!", $this->notice_id, $this->lien_rech_notice)) ;
		
		if ($this->notice->lien) {
			if(!$this->notice->eformat) $info_bulle=$msg["open_link_url_notice"];
			else $info_bulle=$this->notice->eformat;
			// ajout du lien pour les ressource électroniques
			$this->notice_header .= "&nbsp;<a href=\"".$this->notice->lien."\" target=\"__LINK__\">";
			$this->notice_header .= "<img src=\"styles/wmo/images/globe.gif\" class='globe_wmo'";
			$this->notice_header .= " alt=\"";
			$this->notice_header .= $info_bulle;
			$this->notice_header .= "\" title=\"";
			$this->notice_header .= $info_bulle;
			$this->notice_header .= "\">";
			$this->notice_header .= "</a>";
		} 
		
		//Conditions d'accès
		
		
		$notice_header_suite = "<br/>";
		
		//Si c'est une notice de bulletin ajout du titre et bulletin
		if($this->notice->niveau_biblio == 'b' && $this->notice->niveau_hierar == 2)  {
			$aff_bullperio_title = "<span class='isbulletinof'><i> ".($this->parent_date?sprintf($msg["bul_titre_perio"],$this->parent_title):sprintf($msg["bul_titre_perio"],$this->parent_title.", ".$this->parent_numero." [".$this->parent_aff_date_date."]"))."</i></span>";
		} else $aff_bullperio_title="";
		
		if ($aff_bullperio_title) $notice_header_suite .= " ".$aff_bullperio_title."<br/>";
		
		
		$notice_header_suite .= "<span class='responsabilite_wmo'>";
		//auteurs physiques
		if ($this->auteurs_tous) $notice_header_suite .= $this->auteurs_tous;
		//auteurs collectivités
		if ($this->auteurs_tous && $this->collectivite_tous) 
		$notice_header_suite .=",&nbsp;".$this->collectivite_tous;
			elseif (!$this->auteurs_tous) $notice_header_suite .=$this->collectivite_tous;
		//auteurs congrès
		if (($this->auteurs_tous || $this->collectivite_tous) && $this->congres_tous) 
		$notice_header_suite .=",&nbsp;".$this->congres_tous;
			elseif (!$this->auteurs_tous || !$this->collectivite_tous) $notice_header_suite .=$this->congres_tous;
		//editeur
		if (($this->auteurs_tous || $this->collectivite_tous ||$this->congres_tous) && $editeur_reduit) $notice_header_suite .= "&nbsp;-&nbsp;".$editeur_reduit;
			elseif (!$this->auteurs_tous || !$this->collectivite_tous || !$this->congres_tous) $notice_header_suite .=$editeur_reduit;
		$notice_header_suite .="</span><br/><span class='resume_wmo'>";
		
		//résumé
		$maxcara=300;
		if($this->notice->n_resume && strlen($this->notice->n_resume)>$maxcara) {
		$resume_wmo = substr($this->notice->n_resume, 0, $maxcara);
		$position_espace = strrpos($resume_wmo, " "); 
		$resume_wmo = substr($resume_wmo, 0, $position_espace);
		$resume_wmo .="...";
		$notice_header_suite.=$resume_wmo;		
		}else{
		$resume_wmo = $this->notice->n_resume;
		$notice_header_suite.=$resume_wmo;	
		}
		$notice_header_suite.="</span>";
			
		//Champs personnalisés
		if ($perso_voulu_aff) $notice_header_suite .= " / ".$perso_voulu_aff ;
	
		$this->notice_header_without_html .= $notice_header_suite ;
		$this->notice_header .= $notice_header_suite;
		if ($this->notice->niveau_biblio == 'b') {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier, explnum_url FROM explnum, bulletins join explnum_location on (num_explnum=explnum_id and num_location='".$_SESSION['empr_location']."' ) WHERE bulletins.num_notice = ".$this->notice_id." AND bulletins.bulletin_id = explnum.explnum_bulletin order by explnum_id";
		} else {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier,explnum_url FROM explnum join explnum_location on (num_explnum=explnum_id and num_location='".$_SESSION['empr_location']."' ) WHERE explnum_notice = ".$this->notice_id." order by explnum_id";
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
				$this->notice_header .= "&nbsp;<span>";		
				$this->notice_header .= "<a href=\"./doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">";
				$this->notice_header .= "<img src=\"./images/attachment.png\" border=\"0\" align=\"middle\" hspace=\"3\"";
				$this->notice_header .= " alt=\"";
				$this->notice_header .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header .= "\" title=\"";
				$this->notice_header .= htmlentities($info_bulle,ENT_QUOTES,$charset);
				$this->notice_header .= "\">";
				$this->notice_header .= "</a></span>";
			} elseif ($explnumscount > 1) {
				$explnumrow = mysql_fetch_object($explnums);
				$info_bulle=$msg["info_docs_num_notice"];
				$this->notice_header .= "<img src=\"./images/attachment.png\" alt=\"$info_bulle\" \" title=\"$info_bulle\" border=\"0\" align=\"middle\" hspace=\"3\">";
			}
		}
		$memo_notice[$this->notice_id]["header"]=$this->notice_header;
		$memo_notice[$this->notice_id]["niveau_biblio"]	= $this->notice->niveau_biblio;
		
		
		if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode(strip_tags($this->notice_header))."\" target=\"cart_info\" class=\"img_basket\"><img src=\"images/basket_small_20x20.gif\" border=\"0\" title=\"".$msg['notice_title_basket']."\" alt=\"".$msg['notice_title_basket']."\"></a>"; 
		else $basket="";
		$this->notice_header .= "</span><br/><div class='basket_wmo'>".$basket."</div></span><hr id='record_separator'>";
	}
}

class notice_affichage_ensosp extends notice_affichage {
	// fonction d'affichage des exemplaires numeriques
	function aff_explnum () {
		
		global $msg;
		$ret='';

		if ( (is_null($this->dom_2) && $this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) || ($this->rights & 16)){
			if ($this->notice->niveau_biblio=="b" && ($explnum = $this->show_explnum_per_notice(0, $this->bulletin_id, ''))) {
				$ret .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
				$this->affichage_expl .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
			} elseif (($explnum = $this->show_explnum_per_notice($this->notice_id,0, ''))) {
				$ret .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
				$this->affichage_expl .= "<h3><span id='titre_explnum'>$msg[explnum]</span></h3>".$explnum;
			}
		}		 
		return $ret;
	} // fin aff_explnum ()
	
	// fonction retournant les infos d'exemplaires numériques pour une notice ou un bulletin donné
	function show_explnum_per_notice($no_notice, $no_bulletin, $link_expl='') {
		
//		highlight_string(print_r($_SERVER['REMOTE_ADDR'],true));
		// params :
		// $link_expl= lien associé à l'exemplaire avec !!explnum_id!! à mettre à jour
		global $dbh;
		global $charset;
		global $opac_url_base ;
		global $opac_visionneuse_allow;
		global $opac_photo_filtre_mimetype;
		
		if (!$no_notice && !$no_bulletin) return "";
		
		global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
		create_tableau_mimetype() ;
		
		// récupération du nombre d'exemplaires
		$requete = "SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, explnum_vignette, explnum_nomfichier, explnum_extfichier, explnum_repertoire FROM explnum WHERE ";
		if ($no_notice && !$no_bulletin) $requete .= "(explnum_notice='$no_notice' and explnum_bulletin=0) ";
		elseif (!$no_notice && $no_bulletin) $requete .= "(explnum_bulletin='$no_bulletin' and explnum_notice) ";
		elseif ($no_notice && $no_bulletin) $requete .= "(explnum_bulletin='$no_bulletin' and explnum_notice=0) or (explnum_notice='$no_notice' and explnum_bulletin) ";
		$requete .= " order by explnum_mimetype, explnum_id ";
		$res = mysql_query($requete, $dbh);
		$nb_ex = mysql_num_rows($res);
		
		if ($nb_ex) {
			// on récupère les données des exemplaires
			$i = 1 ;
			global $search_terms;
			
			while (($expl = mysql_fetch_object($res))) {
				if ($i==1) $ligne="<tr><td class='docnum' width='33%'>!!1!!</td><td class='docnum' width='33%'>!!2!!</td><td class='docnum' width='33%'>!!3!!</td></tr>" ;
				if ($link_expl) {
					$tlink = str_replace("!!explnum_id!!", $expl->explnum_id, $link_expl);
					$tlink = str_replace("!!notice_id!!", $expl->explnum_notice, $tlink);					
					$tlink = str_replace("!!bulletin_id!!", $expl->explnum_bulletin, $tlink);					
					} 
				$alt = htmlentities($expl->explnum_nom." - ".$expl->explnum_mimetype,ENT_QUOTES, $charset) ;
				
				if ($expl->explnum_vignette) $obj="<img src='vig_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' border='0'>";
					else // trouver l'icone correspondant au mime_type
						$obj="<img src='images/mimetype/".icone_mimetype($expl->explnum_mimetype, $expl->explnum_extfichier)."' alt='$alt' title='$alt' border='0'>";		
				$expl_liste_obj = "<center>";
				
				$words_to_find="";
				if (($expl->explnum_mimetype=='application/pdf') ||($expl->explnum_mimetype=='URL' && (strpos($expl->explnum_nom,'.pdf')!==false))){
					$words_to_find = "#search=\"".trim(str_replace('*','',implode(' ',$search_terms)))."\"";
				}
				if ($opac_visionneuse_allow)
					$allowed_mimetype = explode(",",str_replace("'","",$opac_photo_filtre_mimetype));
				if ($allowed_mimetype && in_array($expl->explnum_mimetype,$allowed_mimetype)){
					$link="
						<script type='text/javascript' src='visionneuse/javascript/visionneuse.js'></script>
						<script type='text/javascript'>
							if(typeof(sendToVisionneuse) == 'undefined'){
								function sendToVisionneuse(explnum_id){
									document.getElementById('visionneuseIframe').src = 'visionneuse.php?'+(typeof(explnum_id) != 'undefined' ? 'explnum_id='+explnum_id+\"\" : '\'');
								}
							}
						</script>
						<a href='#' onclick=\"open_visionneuse(sendToVisionneuse,".$expl->explnum_id.");return false;\" alt='$alt' title='$alt'>".$obj."</a><br />";
					$expl_liste_obj .=$link;
				} else {
					if($expl->explnum_repertoire != 0){
						switch($_SERVER['REMOTE_ADDR']){
							case "92.103.17.90":
							case "92.103.17.91":
							case "92.103.17.92":
							case "92.103.17.93":
							case "92.103.17.94":
							case "217.128.195.136":
							case "193.251.186.82":
							case "80.13.185.226":
							case "80.14.211.232":
							case "80.13.10.218":
							case "80.13.195.117":
								//accès interne
								$expl_liste_obj .= "<a href='http://ressources.ensosp.fr/".$expl->explnum_nomfichier."' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;					
								break;
							default :
								//accès externe
								$suite_url_explnum ="doc_num.php?explnum_id=$expl->explnum_id";
								$expl_liste_obj .= "<a href='".$opac_url_base.$suite_url_explnum."' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;
								break;
						}
					}else{
						//accès externe
						$suite_url_explnum ="doc_num.php?explnum_id=$expl->explnum_id";
						$expl_liste_obj .= "<a href='".$opac_url_base.$suite_url_explnum."' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;
					}
				}
	
				if ($_mimetypes_byext_[$expl->explnum_extfichier]["label"]) $explmime_nom = $_mimetypes_byext_[$expl->explnum_extfichier]["label"] ;
				elseif ($_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"]) $explmime_nom = $_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"] ;
				else $explmime_nom = $expl->explnum_mimetype ;
				
				
				if ($tlink) {
					$expl_liste_obj .= "<a href='$tlink'>";
					$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."</a><div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
					} else {
						$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."<div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
						}
				$expl_liste_obj .= "</center>";
				$ligne = str_replace("!!$i!!", $expl_liste_obj, $ligne);
				$i++;
				if ($i==4) {
					$ligne_finale .= $ligne ;
					$i=1;
					}
				}
			if (!$ligne_finale) $ligne_finale = $ligne ;
			elseif ($i!=1) $ligne_finale .= $ligne ;
			$ligne_finale = str_replace('!!2!!', "&nbsp;", $ligne_finale);
			$ligne_finale = str_replace('!!3!!', "&nbsp;", $ligne_finale);
			
			} else return "";
		$entry .= "<table class='docnum'>$ligne_finale</table>";
		return $entry;
	}
	
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
			$this->notice_header_doclink .= "<img src=\"images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
			$this->notice_header_doclink .= " alt=\"";
			$this->notice_header_doclink .= $info_bulle;
			$this->notice_header_doclink .= "\" title=\"";
			$this->notice_header_doclink .= $info_bulle;
			$this->notice_header_doclink .= "\">";
			$this->notice_header_doclink .= "</a></span>";			
		} 
		if ($this->notice->niveau_biblio == 'b') {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier, explnum_url, explnum_repertoire FROM explnum, bulletins WHERE bulletins.num_notice = ".$this->notice_id." AND bulletins.bulletin_id = explnum.explnum_bulletin order by explnum_id";
		} else {
			$sql_explnum = "SELECT explnum_id, explnum_nom, explnum_nomfichier,explnum_url, explnum_repertoire FROM explnum WHERE explnum_notice = ".$this->notice_id." order by explnum_id";
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
					if($explnumrow->explnum_repertoire != 0){
						switch($_SERVER['REMOTE_ADDR']){
							case "92.103.17.90":
							case "92.103.17.91":
							case "92.103.17.92":
							case "92.103.17.93":
							case "92.103.17.94":
							case "217.128.195.136":
							case "193.251.186.82":
							case "80.13.185.226":
							case "80.14.211.232":
							case "80.13.10.218":
							case "80.13.195.117":
								//accès interne
								$this->notice_header_doclink .= "<a href='http://ressources.ensosp.fr/".$explnumrow->explnum_nomfichier."' target=\"__LINK__\">" ;						
								break;
							default :
								//accès externe
								$this->notice_header_doclink .= "<a href=\"./doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">" ;
								break;
						}
					}else{
						//accès externe
						$suite_url_explnum ="doc_num.php?explnum_id=$expl->explnum_id";
						$this->notice_header_doclink .= "<a href=\"./doc_num.php?explnum_id=".$explnumrow->explnum_id."\" target=\"__LINK__\">" ;
					}
					
					$this->notice_header_doclink .= "";
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
}
