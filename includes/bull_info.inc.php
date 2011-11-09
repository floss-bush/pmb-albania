<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bull_info.inc.php,v 1.47.2.1 2011-08-31 09:20:45 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage des infos bulletin

require_once($include_path."/resa_func.inc.php");
require_once($class_path."/emprunteur.class.php");

// get_expl : retourne un tableau HTML avec les exemplaires du bulletinage
function get_expl($expl) {
	global $msg, $dbh, $base_path, $charset;
	global $cart_link_non;
	global $explr_invisible, $explr_visible_unmod, $explr_visible_mod, $pmb_droits_explr_localises ;
	global $pmb_transferts_actif;
	global $pmb_expl_list_display_comments;
	
	// attention, $bul est un array
	if(!sizeof($expl) || !is_array($expl)) {
		return $msg["bull_no_expl"];
	}
	$explr_tab_invis=explode(",",$explr_invisible);
	$explr_tab_unmod=explode(",",$explr_visible_unmod);
	$explr_tab_modif=explode(",",$explr_visible_mod);
		
	$result  = "<table border=\"0\" cellspacing=\"1\">";
	$result .= "<tr><th>".$msg[293]."</th><th>".$msg[4016]."</th><th>".$msg[4017]."</th><th>".$msg[4018]."</th><th>".$msg[4019]."</th><th>".$msg[4015]."</th><th></th>";
		
	while(list($cle, $valeur) = each($expl)) {
		/*$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, tdoc_libelle, ";
		$requete .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
		$requete .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
		$requete .= " IF(pret_retour>sysdate(),0,1) as retard " ;
		$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl ";
		$requete .= " left join docs_location on exemplaires.expl_location=docs_location.idlocation ";
		$requete .= " left join docs_section on exemplaires.expl_section=docs_section.idsection ";
		$requete .= " left join docs_statut on exemplaires.expl_statut=docs_statut.idstatut ";
		$requete .= " left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc  ";
		$requete .= " WHERE expl_id='$valeur->expl_id' ";*/
		$requete = "SELECT pret_idempr, ";
		$requete .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour ";
		$requete .= " FROM pret ";
		$requete .= " WHERE pret_idexpl='$valeur->expl_id' ";
		$result_prets = mysql_query($requete, $dbh) or die ("<br />".mysql_error()."<br />".$requete);
		if (mysql_num_rows($result_prets)) $expl_pret = mysql_fetch_object($result_prets) ;
		else $expl_pret="";
		$situation = "";
		// prêtable ou pas s'il est prêté, on affiche son état
		if (is_object($expl_pret) && $expl_pret->pret_idempr) {
			// exemplaire sorti
			$rqt_empr = "SELECT empr_nom, empr_prenom, id_empr, empr_cb FROM empr WHERE id_empr='$expl_pret->pret_idempr' ";
			$res_empr = mysql_query ($rqt_empr, $dbh) ;
			$res_empr_obj = mysql_fetch_object ($res_empr) ;
			$situation = "<strong>${msg[358]} ".$expl_pret->aff_pret_retour."</strong>";
			global $empr_show_caddie, $selector_prop_ajout_caddie_empr;
			if ($empr_show_caddie && (SESSrights & CIRCULATION_AUTH)) {
				$img_ajout_empr_caddie="<img src='./images/basket_empr.gif' align='middle' alt='basket' title=\"${msg[400]}\" onClick=\"openPopUp('./cart.php?object_type=EMPR&item=".$expl->pret_idempr."', 'cart', 600, 700, -2, -2, '$selector_prop_ajout_caddie_empr')\">&nbsp;";
			} else { 
				$img_ajout_empr_caddie="";
			}
			$situation .= "<br />$img_ajout_empr_caddie<a href='./circ.php?categ=pret&form_cb=".rawurlencode($res_empr_obj->empr_cb)."'>$res_empr_obj->empr_prenom $res_empr_obj->empr_nom</a>";
		} else {
			// tester si réservé				
			$result_resa = mysql_query("select 1 from resa where resa_cb='".addslashes($valeur->expl_cb)."' ", $dbh) or die ();
			$reserve = mysql_num_rows($result_resa);
			if ($reserve) 
				$situation = "<strong>".$msg['expl_reserve']."</strong>"; // exemplaire réservé
			elseif ($valeur->pret_flag)  
				$situation = "<strong>${msg[359]}</strong>"; // exemplaire disponible
			else 
				$situation = "";
		}
		
		if(SESSrights & CATALOGAGE_AUTH){
			$selector_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";
			$cart_click_expl = "onClick=\"openPopUp('./cart.php?object_type=EXPL&item=!!item!!', 'cart', 600, 700, -2, -2, '$selector_prop')\"";
			$cart_link = "<img src='./images/basket_small_20x20.gif' align='center' alt='middle' title=\"${msg[400]}\" $cart_click_expl>";	
			$ajout_expl_panier = str_replace('!!item!!', $valeur->expl_id, $cart_link) ;
		}else{
			$ajout_expl_panier ="";
		}
		
		//si les transferts sont activés
		if ($pmb_transferts_actif) {
			//si l'exemplaire n'est pas transferable on a une image vide
			$dispo_pour_transfert = transfert::est_transferable ( $valeur->expl_id );
			if (SESSrights & TRANSFERTS_AUTH && $dispo_pour_transfert)
				//l'icon de demande de transfert
				$ajout_expl_panier .= "<a href=\"#\" onClick=\"openPopUp('./catalog/transferts/transferts_popup.php?expl=" . $valeur->expl_id . "', 'cart', 600, 450, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');\">" . "<img src='./images/peb_in.png' align='center' border=0 alt=\"" . $msg ["transferts_alt_libelle_icon"] . "\" title=\"" . $msg ["transferts_alt_libelle_icon"] . "\"></a>";
			else
				$ajout_expl_panier .= "<img src='./images/spacer.gif' align='center' height=20 width=20>";
			
		}
	
		$as_invis = false;
		$as_unmod = false;
		$as_modif = true;		
		global $flag_no_delete_bulletin;
		$flag_no_delete_bulletin=0;
		//visibilité des exemplaires
		if ($pmb_droits_explr_localises) {
			$as_invis = in_array($valeur->expl_location,$explr_tab_invis);
			$as_unmod = in_array($valeur->expl_location,$explr_tab_unmod);
			//$as_modif = in_array($valeur->expl_location,$explr_tab_modif);
			
			if(!($as_modif=in_array  ($valeur->expl_location,$explr_tab_modif) )) $flag_no_delete_bulletin=1;

		} 
		if ($cart_link_non || !(SESSrights & CATALOGAGE_AUTH)) 
			$link =  htmlentities($valeur->expl_cb,ENT_QUOTES, $charset);
		else {
			if ($as_modif) 
				$link = "<a href=\"./catalog.php?categ=serials&sub=bulletinage&action=expl_form&bul_id=".$valeur->expl_bulletin."&expl_id=".$valeur->expl_id."\">".htmlentities($valeur->expl_cb,ENT_QUOTES, $charset)."</a>";
			else 
				$link = htmlentities($valeur->expl_cb,ENT_QUOTES, $charset);
		}
		
		if ($situation) $situation="<br />".$situation;
		if(SESSrights & CATALOGAGE_AUTH){
			$ajout_expl_panier.="<span id='EXPL_drag_".$valeur->expl_id."'  dragicon=\"$base_path/images/icone_drag_notice.png\" dragtext=\"".htmlentities($valeur->expl_cb,ENT_QUOTES, $charset)."\" draggable=\"yes\" dragtype=\"notice\" callback_before=\"show_carts\" callback_after=\"\" style=\"padding-left:7px\"><img src=\"".$base_path."/images/notice_drag.png\"/></span>";
		}
		
		$line = "<tr>";
		if (($valeur->expl_note || $valeur->expl_comment) && $pmb_expl_list_display_comments) $line .= "<td rowspan='2'>$link</td>";
		else $line .= "<td>$link</td>";
		$line .= "<td>$valeur->expl_cote</td>";
		$line .= "<td>$valeur->location_libelle</td>";
		$line .= "<td>$valeur->section_libelle</td>";
		$line .= "<td>$valeur->statut_libelle.$situation</td>";
		$line .= "<td>$valeur->tdoc_libelle</td>";
		$line .= "<td>$ajout_expl_panier</td>";
		if (($valeur->expl_note || $valeur->expl_comment) && $pmb_expl_list_display_comments) {
			$notcom=array();
			$line .= "<tr><td colspan='6'>";
			if ($valeur->expl_note && ($pmb_expl_list_display_comments & 1)) $notcom[] .= "<span class='erreur'>$valeur->expl_note</span>";
			if ($valeur->expl_comment && ($pmb_expl_list_display_comments & 2)) $notcom[] .= "$valeur->expl_comment";
			$line .= implode("<br />",$notcom);
			$line .= "</tr>";
		}
		$result .= $line; 		
	} //while(list($cle, $valeur) = each($expl))
	
	$result .= "</table>";
	
	return $result;
}


// get_analysis : retourne les dépouillements pour un bulletinage donné
function get_analysis($bul_id) {
	global $dbh;

	if(!$bul_id) return '';

	$requete = "SELECT * FROM analysis WHERE analysis_bulletin=$bul_id ORDER BY analysis_notice"; 	
	$myQuery = mysql_query($requete, $dbh);

	// attention, c'est complexe là. on définit ce qui va se passer pour les liens affichés dans les notices
	// 1. si le lien est vers une notice chapeau de périodique
	$link_serial = "./catalog.php?categ=serials&sub=view&serial_id=!!id!!";
	// 2. si le lien est vers un dépouillement
	$link_analysis = "./catalog.php?categ=serials&sub=analysis&action=analysis_form&bul_id=$bul_id&analysis_id=!!id!!";
	// 3. si le lien est vers un bulletin
	$link_bulletin = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!";
	// note : si une de ces trois variables est vide, aucun lien n'est crée en ce qui la concerne dans les notices
	// exemple : dans cette page, on affiche les infos sur ce bulletinage, il ne sert donc à rien d'afficher un lien
	// vers celui-ci. donc :
	$link_bulletin = '';
	 
	
	while($analysis=mysql_fetch_object($myQuery)) {
		$link_explnum = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&analysis_id=$analysis->analysis_notice&bul_id=$bul_id&explnum_id=!!explnum_id!!";
		// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true ) {
		$display = new serial_display($analysis->analysis_notice, 6, $link_serial, $link_analysis, $link_bulletin,"",$link_explnum, 1, 0, 1, 1, true, 1);
		$analysis_list .= $display->result;
	}

	return $analysis_list;
} 

// affichage d'informations pour une entrée de bulletinage
function show_bulletinage_info($bul_id, $lien_cart_ajout=1, $lien_cart_suppr=0, $flag_pointe=0 ) {
	global $dbh;
	global $msg, $base_path, $charset;
	global $liste_script;
	global $liste_debut;
	global $liste_fin;
	global $bul_action_bar;
	global $bul_cb_form;
	global $selector_prop;
	global $url_base_suppr_cart ;
	global $page, $nbr_lignes, $nb_per_page;
	
	$cart_click_bull = "onClick=\"openPopUp('./cart.php?object_type=BULL&item=!!item!!', 'cart', 600, 700, '$selector_prop')\"";
	
	//Calcul des variables pour la suppression d'items
	if($nb_per_page){
		$modulo = $nbr_lignes%$nb_per_page;
		if($modulo == 1){
			$page_suppr = (!$page ? 1 : $page-1);
		} else {
			$page_suppr = $page;
		}	
		$nb_after_suppr = ($nbr_lignes ? $nbr_lignes-1 : 0);	
	}
	
	$affichage_final = "";
	if ($bul_id) {
		if (SESSrights & CATALOGAGE_AUTH) {
			$myBul = new bulletinage($bul_id, 0, "./catalog.php?categ=serials&sub=bulletinage&action=explnum_form&bul_id=$bul_id&explnum_id=!!explnum_id!!");
			
			// lien vers la notice chapeau
			$link_parent = "<a href=\"./catalog.php?categ=serials\">".$msg[4010]."</a>";
			$link_parent .= "<img src=\"./images/d.gif\" align=\"middle\" hspace=\"5\">";
			$link_parent .= "<a href=\"./catalog.php?categ=serials&sub=view&serial_id=";
			$link_parent .= $myBul->bulletin_notice."\">".$myBul->tit1.'</a>';
			$link_parent .= "<img src=\"./images/d.gif\" align=\"middle\" hspace=\"5\">";
			
			if ($myBul->bulletin_numero) 
				$link_bulletin = $myBul->bulletin_numero." ";
			
			// affichage de la mention de date utile : mention_date si existe, sinon date_date
			if ($myBul->mention_date)
				$date_affichee = " (".$myBul->mention_date.")";
				else if ($myBul->date_date)
						$date_affichee = " [".formatdate($myBul->date_date)."]";
					else 
						$date_affichee = "" ;
			
			$link_bulletin .= $date_affichee;

			$link_parent .= "<a href='./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$bul_id'>$link_bulletin</a>" ;
			$affichage_final .= "<div class='row'><div class='perio-barre'>".$link_parent."</div></div>";
			
			if ($lien_cart_ajout) {
				$cart_link = "<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title=\"${msg[400]}\" $cart_click_bull>";
				$cart_link = str_replace('!!item!!', $bul_id, $cart_link);
				$cart_link.="<span id='BULL_drag_".$bul_id."'  dragicon=\"$base_path/images/icone_drag_notice.png\" dragtext=\"".htmlentities($link_bulletin,ENT_QUOTES,$charset)."\" draggable=\"yes\" dragtype=\"notice\" callback_before=\"show_carts\" callback_after=\"\" style=\"padding-left:7px\"><img src=\"".$base_path."/images/notice_drag.png\"/></span>";
			} else 
				$cart_link="" ;
				
			if ($lien_cart_suppr) {
				if ($flag_pointe) $marque_flag ="<img src='images/tick.gif'/>" ;
				else $marque_flag ="" ;
				$cart_link .= "<a href='$url_base_suppr_cart&action=del_item&object_type=BULL&item=$bul_id&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a> $marque_flag";
			}
				
		}else{
			$myBul = new bulletinage($bul_id, 0, "");
			$cart_link="";
		}
		
		$bul_action_bar = str_replace('!!bul_id!!', $bul_id, $bul_action_bar);
		$bul_action_bar = str_replace('!!nb_expl!!', sizeof($myBul->expl), $bul_action_bar);
		
		$bul_isbd = $myBul->display;
		
		$javascript_template ="
		<div id=\"el!!id!!Parent\" class=\"notice-parent\">
    		<img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\">
    		<span class=\"notice-heada\">!!heada!!</span>
    		<br />
		</div>
		<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">
           		!!ISBD!!
 		</div>";
		$aff_expandable = str_replace('!!id!!', $bul_id, $javascript_template);
		$aff_expandable = str_replace('!!heada!!', $cart_link." ".$bul_isbd, $aff_expandable);

		// affichage des exemplaires associés
		$list_expl  = "<div class='exemplaires-perio'>";
		$list_expl .= "<h3>".$msg[4012]."</h3>";
		$list_expl .= "<div class='row'>".get_expl($myBul->expl)."</div></div>";
		$affichage_final .= $list_expl;
		
		$aff_expl_num=$myBul->explnum ;
		if ($aff_expl_num) {
			$list_expl = "<div class='exemplaires-perio'><h3>".$msg[explnum_docs_associes]."</h3>";
			$list_expl .= "<div class='row'>".$aff_expl_num."</div></div>";
			$affichage_final .=  $list_expl;
		} 
		
		// zone d'affichage des dépouillements
		$liste = get_analysis($bul_id);
		if($liste) {
			$liste_dep = $liste;
			$liste_dep .= $liste_fin;
			// inclusion du javascript inline
			$liste_dep .= $liste_script;
		} else {
			$liste_dep .= "<div class='row'>".htmlentities($msg['bull_no_item'],ENT_QUOTES,$charset)."</div>";
		}
		$affichage_final .= "
			<div class='depouillements-perio'>
				<h3>".$msg[4013]."</h3>
				<div class='row'>
					$liste_dep
					</div>
				</div>";
		$aff_resa=resa_list (0, $bul_id, 0) ;
		if ($aff_resa) 
			$affichage_final .= "<h3>$msg[resas]</h3>".$aff_resa;
	}
	$aff_expandable = str_replace('!!ISBD!!', $affichage_final, $aff_expandable);

	return $aff_expandable ;
}

