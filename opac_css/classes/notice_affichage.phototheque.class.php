<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_affichage.phototheque.class.php,v 1.20 2010-08-19 13:14:38 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//require_once("$class_path/publisher.class.php");
//require_once("$class_path/marc_table.class.php");
//require_once($class_path."/parametres_perso.class.php");
//require_once($class_path."/category.class.php");
//require_once($include_path."/isbn.inc.php");

	
	
class notice_affichage_custom_mixte_photos extends notice_affichage
{
	
	// generation du header----------------------------------------------------
	function do_header() {
		global $opac_notice_reduit_format ;
		
		$type_reduit = substr($opac_notice_reduit_format,0,1);
		if ($type_reduit=="E" || $type_reduit=="P" ) {
			// peut-etre veut-on des personnalises ?
			$perso_voulus_temp = substr($opac_notice_reduit_format,2) ;
			if ($perso_voulus_temp!="")
				$perso_voulus = explode(",",$perso_voulus_temp);
			}
		
		if ($type_reduit=="E") {
			// zone de l'editeur 
			if($this->notice->year)
				$annee = $this->notice->year ;
			if ($this->notice->ed1_id) {
				$editeur = new publisher($this->notice->ed1_id);
				$editeur_reduit = $editeur->display ;
				if ($annee) {
					$editeur_reduit .= " - $annee ";
					$annee = "" ;
					}  
				} else { // annee mais pas d'editeur
					$editeur_reduit = " / $annee ";
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
		
		//Si c'est un depouillement, ajout du titre et bulletin
		if($this->notice->niveau_biblio == 'a' && $this->notice->niveau_hierar == 2)  {
			 $aff_perio_title="<i>in ".$this->parent_title." (".$this->parent_numero." ".($this->parent_date?$this->parent_date:"[".$this->parent_aff_date_date."]").")</i>";
		}
		
		// recuperation du titre de serie
			// constitution de la mention de titre
		if($this->notice->serie_name) {
			$this->notice_header = $this->notice->serie_name;
			if($this->notice->tnvol)
				$this->notice_header .= ',&nbsp;'.$this->notice->tnvol;
			}
		if ($this->notice_header) $this->notice_header .= ".&nbsp;".$this->notice->tit1 ;
			else $this->notice_header = $this->notice->tit1;
		if ($type_reduit=="T" && $this->notice->tit4) $this->notice_header = $this->notice_header." : ".$this->notice->tit4;
		if ($this->auteurs_principaux) $this->notice_header .= " / ".$this->auteurs_principaux;
		if ($editeur_reduit) $this->notice_header .= " / ".$editeur_reduit ;
		if ($perso_voulu_aff) $this->notice_header .= " / ".$perso_voulu_aff ;
		if ($aff_perio_title) $this->notice_header .= " ".$aff_perio_title;
		$this->notice_header_with_link=inslink($this->notice_header, str_replace("!!id!!", $this->notice_id, $this->lien_rech_notice)) ;
		
	}	
	
	// generation de l'isbd----------------------------------------------------
	function do_isbd($short=0,$ex=1) {
		global $dbh;
		global $msg;
		global $tdoc;
		global $charset, $opac_url_base;
	
		$this->notice_isbd="";
		
		//In
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
			$parent_notice=new notice_affichage($r_rel->linked_notice,$this->liens,1,$this->to_print);
			$parent_notice->visu_expl = 0 ;
			$parent_notice->visu_explnum = 0 ;
			$parent_notice->do_header();
			//Presentation differente si il y en a un ou plusieurs
			if (mysql_num_rows($result_linked)==1) {
				$this->notice_isbd.="<br /><b>".$relation_listup->table[$r_rel->relation_type]."</b> <a href='".str_replace("!!id!!",$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>".$parent_notice->notice_header."</a><br /><br />";
			} else {
				if (!$r_type[$r_rel->relation_type]) {
					$r_type[$r_rel->relation_type]=1;
					if ($ul_opened) $this->notice_isbd.="</ul>"; else { $this->notice_isbd.="<br />"; $ul_opened=true; }
					$this->notice_isbd.="<b>".$relation_listup->table[$r_rel->relation_type]."</b>";
					$this->notice_isbd.="<ul class='notice_rel'>\n";
				}
				$this->notice_isbd.="<li><a href='".str_replace("!!id!!",$r_rel->linked_notice,$this->lien_rech_notice)."&seule=1'>".$parent_notice->notice_header."</a></li>\n";
			}
			if (mysql_num_rows($result_linked)>1) $this->notice_isbd.="</ul>\n";
		}
		
		// constitution de la mention de titre
		if($this->notice->serie_name) {
			$serie_temp .= inslink($this->notice->serie_name,  str_replace("!!id!!", $this->notice->tparent_id, $this->lien_rech_serie));
			if($this->notice->tnvol) $serie_temp .= ',&nbsp;'.$this->notice->tnvol;
		}
		if ($serie_temp) $this->notice_isbd .= $serie_temp.".&nbsp;".$this->notice->tit1 ;
		else $this->notice_isbd .= $this->notice->tit1;
	
		//commentaire du type de document
		//$this->notice_isbd .= ' ['.$tdoc->table[$this->notice->typdoc].']';
		if ($this->notice->tit3) $this->notice_isbd .= "&nbsp;= ".$this->notice->tit3 ;
		if ($this->notice->tit4) $this->notice_isbd .= "&nbsp;: ".$this->notice->tit4 ;
		if ($this->notice->tit2) $this->notice_isbd .= "&nbsp;; ".$this->notice->tit2 ;
		
		if ($this->auteurs_tous) $this->notice_isbd .= " / ".$this->auteurs_tous;
		
		// mention d'edition
		if($this->notice->mention_edition) $this->notice_isbd .= " &nbsp;. -&nbsp; ".$this->notice->mention_edition;
		
		// zone de collection et editeur
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
			if(isISBN($this->notice->code)) $zoneISBN = 'ISBN ';
			else $zoneISBN .= '.&nbsp;- ';
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
			$langues = "<b>${msg[537]}</b>&nbsp;: ".$this->construit_liste_langues($this->langues);
		}
		if(count($this->languesorg)) {
			$langues .= " <b>${msg[711]}</b>&nbsp;: ".$this->construit_liste_langues($this->languesorg);
		}
		if ($langues) $this->notice_isbd .= "<br />".$langues ;
		
		if (!$short) {
		  	$this->notice_isbd .="<br />
	    		<img class='img_plus' src=\"".$opac_url_base."images/plus.gif\" name=\"imEx\" id=\"el_notes_".$this->notice_id."Img\" title=\"".$msg["expandable_notice"]."\" border=\"0\" onClick=\"expandBase('el_notes_".$this->notice_id."', true); return false;\" hspace=\"3\">
	    		<b>notes</b>		
				<div id=\"el_notes_".$this->notice_id."Child\" class=\"child\" style=\"margin-bottom:6px;display:none;\">    
	    		<table>";
			$this->notice_isbd .= $this->aff_suite() ;
			$this->notice_isbd .="</table></div>";
		} else {
			$this->notice_isbd.=$this->genere_in_perio();
		}
	
		//Notices liees
		// ajoutees en dehors de l'onglet PUBLIC ailleurs
		
		if ($ex) $this->affichage_resa_expl = $this->aff_resa_expl() ;
	}	

			
	// fonction d'affichage de la suite ISBD ou PUBLIC : partie commune, pour eviter la redondance de calcul
	function aff_suite() {
		global $msg;
		global $charset;
		
		// afin d'eviter de recalculer un truc deja calcule..
		if ($this->affichage_suite) return $this->affichage_suite ;
		
		// serials : si article
		$ret .= $this->genere_in_perio () ;
	
			
		// resume	if($this->notice->n_resume)
	 	if($this->notice->n_resume) $ret .= "<tr><td align='right' class='bg-grey'><b>".$msg['n_resume_start']."</b></td><td>".nl2br(htmlentities($this->notice->n_resume,ENT_QUOTES, $charset))."</td></tr>";
	
		// note de contenu
		if($this->notice->n_contenu) $ret .= "<tr><td align='right' class='bg-grey'><b>".$msg['n_contenu_start']."</b></td><td>".nl2br(htmlentities($this->notice->n_contenu,ENT_QUOTES, $charset))."</td></tr>";
	
		// Categories
		if($this->categories_toutes) $ret .= "<tr><td align='right' class='bg-grey'><b>".$msg['categories_start']."</b></td><td>".$this->categories_toutes."</td></tr>";
				
		// indexation libre
		$mots_cles = $this->do_mots_cle() ;
		if($mots_cles) $ret .= "<tr><td align='right' class='bg-grey'><b>".$msg['motscle_start']."</b></td><td>".$mots_cles."</td></tr>";
		
		// indexation interne
		if($this->notice->indexint) {
			$indexint = new indexint($this->notice->indexint);
			$ret .= "<tr><td align='right' class='bg-grey'><b>".$msg['indexint_start']."</b></td><td>".inslink($indexint->name,  str_replace("!!id!!", $this->notice->indexint, $this->lien_rech_indexint))." ".nl2br(htmlentities($indexint->comment,ENT_QUOTES, $charset))."</td></tr>" ;
		}
		
		//Champs personnalises
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
			//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
			$ret .= $perso_aff ;
		}
		
		if ($this->notice->lien) {
			//$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
			$ret.="<tr><td align='right' class='bg-grey'><b>".$msg["lien_start"]."</b></td><td>" ;
			if (substr($this->notice->eformat,0,3)=='RSS') {
				$ret .= affiche_rss($this->notice->notice_id) ;
			} else {
				$ret.="<a href=\"".$this->notice->lien."\" target=\"top\">".htmlentities($this->notice->lien,ENT_QUOTES,$charset)."</a></td></tr>";
			}
			$ret.="</td></tr>";
			if ($this->notice->eformat && substr($this->notice->eformat,0,3)!='RSS') $ret.="<tr><td align='right' class='bg-grey'><b>".$msg["eformat_start"]."</b></td><td>".htmlentities($this->notice->eformat,ENT_QUOTES,$charset)."</td></tr>";
		}
		
		$this->affichage_suite = $ret ;
		return $ret ;
	} 
		
			
	// fonction d'affichage des exemplaires, resa et expl_num
	function aff_resa_expl() {
		global $opac_resa ;
		global $opac_max_resa ;
		global $opac_show_exemplaires, $opac_url_base ;
		global $msg,$charset;
		global $dbh;
		global $popup_resa ;
		global $opac_resa_popup ; // la resa se fait-elle par popup ?
		global $allow_book ;
		// afin d'eviter de recalculer un truc deja calcule...
		if ($this->affichage_resa_expl) return $this->affichage_resa_expl ;
		
		if ( (is_null($this->dom) && $opac_show_exemplaires && $this->visu_expl && (!$this->visu_expl_abon || ($this->visu_expl_abon && $_SESSION["user_code"]))) || ($this->rights & 8) ) {
	
			$resa_check=check_statut($this->notice_id,0) ;
			// vérification si exemplaire réservable
			if ($resa_check) {
				// deplace dans le IF, si pas visible : pas de bouton resa 
				$requete_resa = "SELECT count(1) FROM resa WHERE resa_idnotice='$this->notice_id'";
				$nb_resa_encours = mysql_result(mysql_query($requete_resa,$dbh), 0, 0) ;
				if ($nb_resa_encours) $message_nbresa = str_replace("!!nbresa!!", $nb_resa_encours, $msg["resa_nb_deja_resa"]) ;
				if (($this->notice->niveau_biblio=="m") && ($_SESSION["user_code"] && $allow_book) && $opac_resa && !$popup_resa) {
					//$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
					if ($opac_max_resa==0 || $opac_max_resa>$nb_resa_encours) {
						if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"w=window.open('./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
						else $ret .= "<a href='./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup' id='bt_resa'>".$msg["bulletin_display_place_resa"]."</a>" ;
						$ret .= $message_nbresa ;
					} else $ret .= str_replace("!!nb_max_resa!!", $opac_max_resa, $msg["resa_nb_max_resa"]) ; 
					$ret.= "<br />";
				} elseif ( ($this->notice->niveau_biblio=="m") && !($_SESSION["user_code"]) && $opac_resa && !$popup_resa) {
					// utilisateur pas connecte				// preparation lien reservation sans etre connecte				//$ret .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
					//if ($opac_resa_popup) $ret .= "<a href='#' onClick=\"w=window.open('./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
					//	else $ret .= "<a href='./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup' id='bt_resa'>".$msg["bulletin_display_place_resa"]."</a>" ;
					$ret .= $message_nbresa ;
				}
			}
			$temp = $this->expl_list($this->notice->niveau_biblio,$this->notice->notice_id);
			$ret .= $temp ;
			$this->affichage_expl = $temp ; 
		}
	
	     if ($this->notice->typdoc!="k") {
	    	if ( (is_null($this->dom) && $this->visu_explnum && (!$this->visu_explnum_abon || ($this->visu_explnum_abon && $_SESSION["user_code"]))) || ($this->rights & 16) ){	
				if (($explnum = show_explnum_per_notice($this->notice_id, 0, ''))) {
					$ret.= 
			          "<img class='img_plus' src=\"".$opac_url_base."images/plus.gif\" name=\"imEx\" id=\"el_docnum_".$this->notice_id."Img\" title=\"".$msg["expandable_notice"]."\" border=\"0\" onClick=\"expandBase('el_docnum_".$this->notice_id."', true); return false;\" hspace=\"3\">
	    		      <b>".htmlentities($msg['expl num'],ENT_QUOTES,$charset)."</b>		
	        		  <div id=\"el_docnum_".$this->notice_id."Child\" class=\"child\" style=\"margin-bottom:6px;display:none;\">";    
					$ret.= $explnum;
					$ret.="</div>";    			
					$this->affichage_expl .= "<h3>$msg[explnum]</h3>".$explnum;
	    		}
	    	}
	    	$this->affichage_resa_expl = $ret ;
	    }  
	    return $ret ;
    }	

		
// fonction de generation du tableau des exemplaires
function expl_list($type,$id,$bull_id=0) {	
	global $dbh;
	global $msg, $charset;
	global $expl_list_header, $expl_list_footer, $opac_url_base;
	
	// ecrasement du template d'affichage des exemplaires pour eviter tout conflit GM
	$expl_list_header="<table class=\"tableau_expl_liste\">";
	$expl_list_footer ="</table>";
	
	
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
		$compteur=0;
		while(($expl = mysql_fetch_object($res))) {
			$compteur = $compteur+1;
			$expl_liste .= "<tr><th>$msg[barcode]</th><th>$msg[cotation]</th><th>$msg[typdoc_support]</th><th>$msg[statut]</th></tr>";
			$expl_liste .= "<tr><td>".$expl->expl_cb."</td><td><strong>".$expl->expl_cote."</strong></td>
				<td>".$expl->tdoc_libelle."</td>";
			
			$requete_resa = "SELECT count(1) from resa where resa_cb='$expl->expl_cb' ";
			$flag_resa = mysql_result(mysql_query($requete_resa, $dbh),0,0);
			$requete_resa = "SELECT count(1) from resa_ranger where resa_cb='$expl->expl_cb' ";
			$flag_resa = $flag_resa + mysql_result(mysql_query($requete_resa, $dbh),0,0);
			if ($flag_resa) {
				$nb_resa--;
				$situation = "<strong>$msg[expl_reserve]</strong>";
			} else {
				if ($expl->pret_flag) {
					if($expl->pret_retour) {
						// exemplaire sorti
						$situation = "<strong>$msg[out_until] ".formatdate($expl->pret_retour).'</strong>';
					} else { // pas sorti
						$situation = "<strong>".$msg['available']."</strong>";
					}
				} else { // pas pretable
					// exemplaire pas pretable, on affiche juste "exclu du pret"
					$situation = "<strong>".$msg['exclu']."</strong>";
					// $situation = "<strong>".$expl->statut_libelle.'</strong>';
				}
			} // fin if else $flag_resa 
			$expl_liste .= "<td>$situation </td>";
			$expl_liste .="</tr>\r\n
			<tr><th colspan=\"2\">$msg[situation]</th><th colspan=\"2\">$msg[section]</th></tr>";	
			$expl_liste .= "<tr><td colspan=\"2\">";
			if ($expl->num_infopage) 
				$expl_liste .= "<a href=\"".$opac_url_base."index.php?lvl=infopages&pagesid=".$expl->num_infopage."\" alt=\"".$msg['location_more_info']."\" title=\"".$msg['location_more_info']."\">".htmlentities($expl->location_libelle, ENT_QUOTES, $charset)."</a>";
			else
				$expl_liste .= $expl->location_libelle.
			$expl_liste .= "</td><td colspan=\"2\">".$expl->section_libelle."</td></tr>";

		} // fin while
		
		// affichage de la liste d'exemplaires calcule ci-dessus
		if ($expl_liste) $expl_liste = $expl_list_header.$expl_liste.$expl_list_footer;
		return $expl_liste;
	}
	
	// le resume des articles, bulletins et exemplaires des notices meres
	if ($type=="s") return "";
	} // fin function expl_list

	
// generation du de l'affichage simple sans onglet ----------------------------------------------
//	si $depliable=1 alors inclusion du parent / child
function genere_simple($depliable=1, $what='ISBD') {
	global $msg, $charset;
	global $dbh; 
	global $cart_aff_case_traitement;
	global $opac_url_base ;
	global $opac_avis_allow;
	global $opac_allow_add_tag;
	global $allow_tag ; // l'utilisateur a-t-il le droit d'ajouter un tag

	$this->notice_childs = $this->genere_notice_childs();
	// preparation de la case a cocher pour traitement panier
	if ($cart_aff_case_traitement) $case_a_cocher = "<input type='checkbox' value='!!id!!' name='notice[]'/>&nbsp;";
	else $case_a_cocher = "" ;
	
	if ($this->cart_allowed) $basket="<a href=\"cart_info.php?id=".$this->notice_id."&header=".rawurlencode($this->notice_header)."\" target=\"cart_info\"><img src='".$opac_url_base."images/basket_small_20x20.gif' align='absmiddle' border='0' title=\"".$msg[notice_title_basket]."\" alt=\"".$msg[notice_title_basket]."\"></a>"; 
	else $basket="";

	 //Avis
	 if (($opac_avis_allow && $opac_avis_allow !=2) || ($_SESSION["user_code"] && $opac_avis_allow == 2)) $basket.="&nbsp;&nbsp;<a href='#' onclick=\"open('avis.php?todo=liste&noticeid=$this->notice_id','avis','width=520,height=290,scrollbars=yes,resizable=yes'); return false;\"><img src='".$opac_url_base."images/avis.png' align='absmiddle' border='0' title=\"".$msg[notice_title_avis]."\" alt=\"".$msg[notice_title_avis]."\"></a>";	 
	//add tags
	if (($opac_allow_add_tag==1)||(($opac_allow_add_tag==2)&&($_SESSION["user_code"])&&($allow_tag))) $basket.="&nbsp;&nbsp;<a href='#' onclick=\"open('addtags.php?noticeid=$this->notice_id','ajouter_un_tag','width=350,height=150,scrollbars=yes,resizable=yes'); return false;\"><img src='".$opac_url_base."images/tag.png'align='absmiddle' border='0' title=\"".$msg[notice_title_tag]."\" alt=\"".$msg[notice_title_tag]."\"></a>";
	if ($basket) $basket="<div>".$basket."</div>";

	if ($this->notice->niveau_biblio=="s") 
		$icon="icon_per_16x16.gif";
	elseif ($this->notice->niveau_biblio=="a")
		$icon="icon_art_16x16.gif";
	else
		$icon="icon_".$this->notice->typdoc."_16x16.gif";	

	if ((!$depliable) && ($this->notice->typdoc=="k")) { 
		$template="
			<div id=\"el!!id!!Global\" class=\"notice-global-photo\">
			<div id=\"el!!id!!Parent\" class=\"notice-parent\">\n
		$case_a_cocher";
		if ($icon) $template.="
				<img src=\"".$opac_url_base."images/$icon\" />";
		$template.="
    		<span class=\"notice-heada\">!!heada!!</span>
		    <center>!!DOCNUM1!!</center>\n
    		</div>			
			<div id=\"el!!id!!Child\" class=\"notice-child-photo\" style=\"margin-bottom:6px;\">".$basket."!!ISBD!!\n
			</div>\n
			</div>\n";
	} else {
		$template="
			<div id=\"el!!id!!Global\" class=\"notice-global-nophoto\">\n
			<div id=\"el!!id!!Parent\" class=\"notice-parent\">\n
    	$case_a_cocher";
		if ($icon) $template.="
				<img src=\"".$opac_url_base."images/$icon\" />";
		$template.="
    		<span class=\"heada\">!!heada!!</span><br />
	    	</div>			
			\n<div id='el!!id!!Child' class='child' >".$basket."
			!!ISBD!!
			!!SUITE!!
			</div>\r\n</div>\n";
	}
	$no_notice = $this->notice_id;
	if ($this->notice->typdoc=="k") {
		
		// Affichage du premier exemplaire numerique
		if ($no_notice) { 
			// Traitement exemplaire numerique	
			$requete = "SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, explnum_vignette, explnum_nomfichier, explnum_extfichier FROM explnum WHERE ";
			$requete .= "explnum_notice='$no_notice' ";
			$requete .= " order by explnum_id LIMIT 1";
			$res = mysql_query($requete, $dbh) or die ($requete." ".mysql_error());
			$nb_ex = mysql_num_rows($res);
		}
		
		if($nb_ex) {
			// on recupere les donnees des exemplaires
			$i = 1 ;
			while (($expl = mysql_fetch_object($res))) {
				$ligne="!!1!!" ;
				if ($link_expl) {
					$tlink = str_replace("!!explnum_id!!", $expl->explnum_id, $link_expl);
					$tlink = str_replace("!!notice_id!!", $expl->explnum_notice, $tlink);					
					$tlink = str_replace("!!bulletin_id!!", $expl->explnum_bulletin, $tlink);					
				} 
				$alt = htmlentities($expl->explnum_nom." - ".$expl->explnum_mimetype,ENT_QUOTES, $charset) ;
				
				global $prefix_url_image ;
				if ($prefix_url_image) $tmpprefix_url_image = $prefix_url_image; 
				else $tmpprefix_url_image = "./" ;
		
				if ($expl->explnum_vignette) $obj="<img src='".$tmpprefix_url_image."vig_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' border='0'>";
				else // trouver l'icone correspondant au mime_type
				$obj="<img src='".$tmpprefix_url_image."images/mimetype/".icone_mimetype($expl->explnum_mimetype, $expl->explnum_extfichier)."' alt='$alt' title='$alt' border='0'>";
				
				$expl_liste_obj = "";
				$expl_liste_obj .= "<a href='index.php?lvl=notice_display&id=".$this->notice_id."&mode_phototeque=1' alt='$alt' title='$alt'>".$obj."</a><br />" ;
				
				if ($_mimetypes_byext_[$expl->explnum_extfichier]["label"]) $explmime_nom = $_mimetypes_byext_[$expl->explnum_extfichier]["label"] ;
				elseif ($_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"]) $explmime_nom = $_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"] ;
				else $explmime_nom = $expl->explnum_mimetype ;
				/* Pas besoin de l'affichage des titres ni format
				 if ($tlink) {
					$expl_liste_obj .= "<a href='$tlink'>";
					$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."</a><div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
					} else {
						$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."<div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
						}
				*/
				$expl_liste_obj .= "";
				$ligne = str_replace("!!$i!!", $expl_liste_obj, $ligne);
			}
			$ligne_finale = $ligne ;
		} 
	
		$entry .= "$ligne_finale";
	}
	
	// Si un document numerique, renvoi du doc num $entry dans le template, sinon vide
	if ($nb_ex) $template = str_replace('!!DOCNUM1!!', $entry, $template);
	else $template = str_replace('!!DOCNUM1!!', "", $template);

	// Serials : difference avec les monographies on affiche [periodique] et [article] devant l'ISBD
	if ($this->notice->niveau_biblio =='s') {
		$template = str_replace('!!ISBD!!', "<span class='fond-mere'>[".$msg['isbd_type_perio']."]</span>&nbsp;<a href='index.php?lvl=notice_display&id=".$this->notice_id."'><i>".$msg["see_bull"]."</i></a>&nbsp;!!ISBD!!", $template);
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
	if ($this->affichage_resa_expl || $this->notice_childs) $this->result = str_replace('!!SUITE!!', $this->notice_childs.$this->affichage_resa_expl, $this->result);
	else $this->result = str_replace('!!SUITE!!', '', $this->result);
	}
}


class notice_affichage_id_photos extends notice_affichage_custom_mixte_photos {
	
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


// fin classe perso pour experimentation affichage mixte et photo-gallerie	
