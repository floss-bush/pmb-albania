<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl_info.inc.php,v 1.46 2009-09-24 13:07:45 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/audit.class.php");

// affichage des infos exemplaires
function print_info ($expl, $mode_affichage = 0, $affichage_emprunteurs = 1, $affichage_zone_notes = 1) {
	global $msg;
	// $expl est l'objet exemplaire rempli avec ce qu'il faut
	// $mode_affichage : 
	//	0 en liste dépliable : le contenu est affiché dans le div
	//	1 : le contenu est affiché APRES l'isbd, sans liste dépliable
	//	2 : le contenu n'est pas affiché du tout
	
	if(!is_object($expl)) die("serious application error occured in ./circ/visu_ex.inc [print_info()]. Please contact developpment team");

	switch($mode_affichage) {
		case '0':
			$temp= "
				<div id='el!!id!!Parent' class='notice-parent'>
	    			<img src='./images/plus.gif' class='img_plus' name='imEx' id='el!!id!!Img' title='".$msg['admin_param_detail']."' border='0' onClick=\"expandBase('el!!id!!', true); return false;\" hspace='3'>
	    			<span class='notice-heada'>!!heada!!</span>
	    			<br />
				</div>
				<div id='el!!id!!Child' class='notice-child' style='margin-bottom:6px;display:none;'>
	        	   	!!contenu!!
	 			</div>
				";
			$temp = str_replace('!!id!!', $expl->expl_id, $temp);
			if ($expl->expl_bulletin) {
				if (SESSrights & CATALOGAGE_AUTH) $heada = "<a href='./catalog.php?categ=serials&sub=bulletinage&action=expl_form&bul_id=".$expl->expl_bulletin."&expl_id=".$expl->expl_id."'>".$msg[376]."&nbsp;".$expl->expl_cb."</a> / ".$expl->aff_reduit ;
				else $heada = "<a href='./circ.php?categ=visu_ex&form_cb_expl=".rawurlencode($expl->expl_cb)."'>".$msg[376]."&nbsp;".$expl->expl_cb."</a> / ".$expl->aff_reduit ;
			} else {
				if (SESSrights & CATALOGAGE_AUTH) $heada = "<a href='./catalog.php?categ=edit_expl&id=".$expl->expl_notice."&expl_id=".$expl->expl_id."'>".$msg[376]."&nbsp;".$expl->expl_cb."</a> / ".$expl->aff_reduit ;
				else $heada = "<a href='./circ.php?categ=visu_ex&form_cb_expl=".rawurlencode($expl->expl_cb)."'>".$msg[376]."&nbsp;".$expl->expl_cb."</a> / ".$expl->aff_reduit ;
			}
			$temp = str_replace('!!heada!!', $expl->lien_suppr_cart.$heada, $temp);
			break;
		case '1':
			$selector_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";
			$cart_click_expl = "onClick=\"openPopUp('./cart.php?object_type=EXPL&item=".$expl->expl_id."', 'cart', 600, 700, -2, -2, '$selector_prop')\"";
			$cart_click_expl = "<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title=\"${msg[400]}\" $cart_click_expl>" ;
			if ($expl->expl_notice) {
				$cart_click_isbd = "onClick=\"openPopUp('./cart.php?object_type=NOTI&item=$expl->expl_notice', 'cart', 600, 700, -2, -2, '$selector_prop')\"";
			} elseif ($expl->expl_bulletin) {
				$cart_click_isbd = "onClick=\"openPopUp('./cart.php?object_type=BULL&item=".$expl->expl_bulletin."', 'cart', 600, 700, -2, -2, '$selector_prop')\"";
			} 
			$cart_click_isbd = "<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title=\"${msg[400]}\" $cart_click_isbd>" ;
			if (SESSrights & CATALOGAGE_AUTH) {
				if ($expl->expl_bulletin) $temp= "<div class='row'><h1>$cart_click_expl&nbsp;<a href='./catalog.php?categ=serials&sub=bulletinage&action=expl_form&bul_id=".$expl->expl_bulletin."&expl_id=".$expl->expl_id."'>${msg[376]}&nbsp;".$expl->expl_cb."</a> : $cart_click_isbd&nbsp;".$expl->aff_reduit."</h1></div><div class='row'><b>".$expl->isbd."</b></div>";
				else $temp= "<div class='row'><h1>$cart_click_expl&nbsp;<a href='./catalog.php?categ=edit_expl&id=".$expl->expl_notice."&expl_id=".$expl->expl_id."'>${msg[376]}&nbsp;".$expl->expl_cb."</a> : $cart_click_isbd&nbsp;".$expl->aff_reduit."</h1></div><div class='row'><b>".$expl->isbd."</b></div>";
			} else $temp= "<div class='row'><h1>$cart_click_expl&nbsp;${msg[376]}&nbsp;".$expl->expl_cb." : $cart_click_isbd&nbsp;".$expl->aff_reduit."</h1></div><div class='row'><b>".$expl->isbd."</b></div>";
			break;
		}
	
	// isbd complet
	$__isbd.= "<div class=\"row\">";
	$__isbd.= $expl->aff_reduit ;
	$__isbd.= "</div>";
	
	// informations de localisation
	$__local.= "<hr /><div class=\"row\">";
	$__local.= "$msg[298]:&nbsp;<b>".$expl->location_libelle."</b>&nbsp;&nbsp;
			$msg[295]:&nbsp;<b>".$expl->section_libelle."</b>&nbsp;&nbsp;
			$msg[296]:&nbsp;<b>".$expl->expl_cote."</b><br />";
	$__local.= "$msg[297]:&nbsp;".$expl->statut_libelle;
	// tester si réservé
	$sql="SELECT resa_cb from resa_ranger where resa_cb='".addslashes($expl->expl_cb)."'";
	$execute_query=mysql_query($sql);
	if (verif_cb_utilise($expl->expl_cb)||mysql_num_rows($execute_query)) $situation = $msg['expl_reserve']; // exemplaire réservé
	elseif ($expl->pret_flag && !$expl->pret_idempr) $situation = "${msg[359]}"; // exemplaire disponible
	else $situation = "";
	$__local.= "&nbsp;&nbsp;<b>".$situation."</b><br />";
	$__local.=$msg[299].":&nbsp;<b>".$expl->codestat_libelle."</b><br />";
	
	
	$__local.= "</div>";

	if ($affichage_emprunteurs) {
		// zone de l'emprunteur
		if($expl->pret_idempr) {
			$__empr.= "<hr /><div class='row'><b>$msg[380]</b><br /> ";
			$link = "<a href='./circ.php?categ=pret&form_cb=".rawurlencode($expl->empr_cb)."'>";
			$__empr.= $link.$expl->empr_prenom." ".$expl->empr_nom." (".$expl->empr_cb.")</a>";
			$__empr.= "&nbsp;${msg[381]}&nbsp;".$expl->aff_pret_date;
			$__empr.= ".&nbsp;${msg[358]}&nbsp;".$expl->aff_pret_retour.".";
			$__empr.= "</div>";
		}
		
		// zone du dernier emrunteur
		if($expl->expl_lastempr) {
			$__empr.= "<hr /><div class='row'><b>$msg[expl_lastempr]</b><br /> ";
			$link = "<a href='./circ.php?categ=pret&form_cb=".rawurlencode($expl->lastempr_cb)."'>";
			$__empr.= $link.$expl->lastempr_prenom.' '.$expl->lastempr_nom.' ('.$expl->lastempr_cb.')</a>';
			$__empr.= "</div>";
		}
	}
	if ($affichage_zone_notes) {
		// zone du message exemplaire
		$__note = "<hr /><div class='row'>";
		$__note.= "<b>${msg[377]}</b><br />";
		if ($expl->expl_note) $__note.= "<div class='message_important'>".$expl->expl_note."</div>";
		$__note.= $expl->expl_comment;
		$__note.= "<br /><input type='button' class='bouton' value='$msg[378]' onclick=\"document.location='./circ.php?categ=note_ex&cb=".rawurlencode($expl->expl_cb)."&id=".$expl->expl_id."'\" />";
		$__note.= "</div><hr />";
	}
	// zone des réservations
	$__resa = check_resa_liste($expl);
	if ($__resa) {
		$__resa = "<div class=\"row\"><b>".$msg["reserv_en_cours_doc"]."</b><br />".$__resa;
		$__resa.= "</div>";
	}
	switch($mode_affichage) {
		case '0':
			$temp = str_replace('!!contenu!!', $__isbd.$__local.$__empr.$__note.$__resa, $temp);
			break;
		case '1':
			$temp = str_replace('!!contenu!!', "", $temp);
			$temp .= $__local.$__empr.$__note.$__resa ;
			break;
		case '2':
			$temp = str_replace('!!contenu!!', "", $temp);
			break;
	}
	return $temp;
}

// récupération des infos exemplaires
function get_expl_info($id, $lien_notice=1) {
	global $dbh;
	global $cart_link_non;

	$query = " select * from exemplaires expl, docs_location location";
	$query .= ", docs_section section, docs_statut statut, docs_type dtype, docs_codestat codestat";
	$query .= " where expl.expl_id='$id'";
	$query .= " and location.idlocation=expl.expl_location";
	$query .= " and section.idsection=expl.expl_section";
	$query .= " and statut.idstatut=expl.expl_statut";
	$query .= " and dtype.idtyp_doc=expl.expl_typdoc";
	$query .= " and codestat.idcode=expl.expl_codestat";
	$result = mysql_query($query, $dbh);
	if(mysql_num_rows($result)) {
		$expl = mysql_fetch_object($result);
		if($expl->expl_notice) {
			if ((SESSrights & CATALOGAGE_AUTH) && $lien_notice) $notice = new mono_display($expl->expl_notice, 1, "./catalog.php?categ=isbd&id=".$expl->expl_notice, 0);
			else $notice = new mono_display($expl->expl_notice, 1, "", 0);
			$expl->isbd = $notice->isbd;
			$expl->code = $notice->notice->code ;
			$expl->aff_reduit = $notice->header;
			$expl->titre=$notice->tit1;
		} elseif ($expl->expl_bulletin) {
			$bl = new bulletinage_display($expl->expl_bulletin);
			$expl->isbd  = $bl->display;
			if ($cart_link_non) $expl->aff_reduit = $bl->header;
			else $expl->aff_reduit = "<a href='./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$expl->expl_bulletin'>".$bl->header."</a>";
		}
		if ($expl->expl_lastempr) {
			$lastempr = new emprunteur($expl->expl_lastempr, '', FALSE, 0) ;
			$expl->lastempr_nom = $lastempr->nom;
			$expl->lastempr_prenom = $lastempr->prenom;
			$expl->lastempr_cb = $lastempr->cb;
		}
		return $expl;
	} else {
		return FALSE;
	}

}

// récupére les réservations associées à la notice
// de l'exemplaire concerné
function check_resa_liste($expl) {
	global $dbh;
	global $msg ;
	
	if(!$expl || !is_object($expl))
		return '';
	
	$requete = "select empr_nom, empr_prenom, empr_cb, resa_date, IF(resa_date_fin>sysdate(),0,1) as perimee, date_format(resa_date, '".$msg["format_date"]."') as aff_resa_date from empr, resa";
	if($expl->expl_notice) $requete .= " where resa.resa_idnotice=".$expl->expl_notice;
	elseif($expl->expl_bulletin) $requete .= " where resa.resa_idbulletin=".$expl->expl_bulletin;
	$requete .= " and empr.id_empr=resa.resa_idempr";
	$requete .= " order by resa.resa_date";
	$query = @mysql_query($requete, $dbh);
	if(mysql_num_rows($query)) {
		while($resa = mysql_fetch_object($query)) {
			$link = "<a href=\"./circ.php?categ=pret&form_cb=".rawurlencode($resa->empr_cb)."\">";
			$resa_list .= $link.$resa->empr_prenom.'&nbsp;'.$resa->empr_nom;
			$resa_list .= "&nbsp;(".$resa->empr_cb.')</a>';
			$resa_list .= '&nbsp;<i>'.$resa->aff_resa_date.'</i><br />';
		}
	}
	
	return $resa_list;
} 

// teste les réservations sur l'exemplaire et le cas échéant,
// retourne les infos de réservation dans l'objet spécifié
function check_resa($expl) {
	global $dbh;
	global $msg; 
	
	if(!is_object($expl))
		die("serious application error occured in ./circ/retour.inc [check_resa()]. Please contact developpment team");

	if (!$expl->expl_notice) $expl->expl_notice=0;
	if (!$expl->expl_bulletin) $expl->expl_bulletin=0 ;
	$rqt = "select *, IF(resa_date_fin>sysdate(),0,1) as perimee, date_format(resa_date_fin, '".$msg["format_date"]."') as aff_resa_date_fin, date_format(resa_date, '".$msg["format_date"]."') as aff_resa_date from resa where resa_idnotice='".$expl->expl_notice."' and resa_idbulletin='".$expl->expl_bulletin."' order by resa_date limit 1 ";
	
	$result = mysql_query($rqt, $dbh) or die (mysql_error()) ;
	if(mysql_num_rows($result)) {

		// des réservations ont été trouvées ->
		// récupération des infos résa
		$resa = mysql_fetch_object($result);
		$expl->id_resa = $resa->id_resa;
		$expl->resa_idempr = $resa->resa_idempr;
		$expl->resa_idnotice = $resa->resa_idnotice;
		$expl->resa_idbulletin = $resa->resa_idbulletin;
		$expl->resa_date = $resa->resa_date;
		$expl->resa_date_fin = $resa->resa_date_fin;
		$expl->aff_resa_date = $resa->aff_resa_date;
		$expl->aff_resa_date_fin = $resa->aff_resa_date_fin;
		$expl->resa_cb = $resa->resa_cb;
		
		// récupération des infos sur le réservataire
		$query = "select empr_nom, empr_prenom, empr_cb, id_empr from empr where id_empr=".$resa->resa_idempr." limit 1";
		$result = mysql_query($query, $dbh);
		if(mysql_num_rows($result)) {
			// stockage des infos sur le réservataire
			$empr = mysql_fetch_object($result);
			$expl->cb_reservataire = $empr->empr_cb;
			$expl->nom_reservataire = $empr->empr_nom;
			$expl->prenom_reservataire = $empr->empr_prenom;
			$expl->id_reservataire = $empr->id_empr;
		}

	}
	return $expl;
}

// teste la situation de l'exemplaire et le cas échéant,
// retourne les infos de pret dans l'objet spécifié
function check_pret($expl) {
	global $dbh;
	global $msg;
	
	if(!is_object($expl))
		die("serious application error occured in ./circ/retour.inc [check_pret()]. Please contact developpment team");

	// récupération des infos du prêt
	$query = "select *, date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, IF(pret_retour>sysdate(),0,1) as retard from pret where pret_idexpl=".$expl->expl_id." limit 1";
	$result = mysql_query($query, $dbh);

	if(mysql_num_rows($result)) {
		$pret = mysql_fetch_object($result);

		// le document était bien en prêt ->
		// récupération des infos du prêt
		$expl->pret_idempr = $pret->pret_idempr;
		$expl->pret_idexpl = $pret->pret_idexpl;
		$expl->pret_date = $pret->pret_date;
		$expl->pret_retour = $pret->pret_retour;
		$expl->aff_pret_date = $pret->aff_pret_date;
		$expl->aff_pret_retour = $pret->aff_pret_retour;
		$expl->pret_arc_id = $pret->pret_arc_id;
		$expl->niveau_relance = $pret->niveau_relance;
		$expl->date_relance = $pret->date_relance;
		$expl->printed = $pret->printed;
		$expl->cpt_prolongation  = $pret->cpt_prolongation;		
		// récupération des infos emprunteur
		$query = "select * from empr where id_empr=".$pret->pret_idempr." limit 1";
		$result = mysql_query($query, $dbh);
		if(mysql_num_rows($result)) {

			// stockage des infos sur l'emprunteur
			$empr = mysql_fetch_object($result);
			$expl->empr_cb = $empr->empr_cb;
			$expl->id_empr = $empr->id_empr;
			$expl->empr_nom = $empr->empr_nom;
			$expl->empr_prenom = $empr->empr_prenom;
			$expl->id_empr = $empr->id_empr;
			$expl->empr_cp = $empr->empr_cp;
			$expl->empr_ville = $empr->empr_ville;
			$expl->empr_pays = $empr->empr_pays;
			$expl->empr_prof = $empr->empr_prof;
			$expl->empr_year = $empr->empr_year;
			$expl->empr_categ = $empr->empr_categ;
			$expl->empr_codestat = $empr->empr_codestat;
			$expl->empr_sexe = $empr->empr_sexe;
			$expl->empr_statut = $empr->empr_statut;
			$expl->empr_msg = $empr->empr_msg;
			$query_groupe = "select libelle_groupe from groupe, empr_groupe where empr_id='".$pret->pret_idempr."' and groupe_id=id_groupe";
			$result_g = mysql_query($query_groupe, $dbh);
			while ($groupes=mysql_fetch_object($result_g)) $groupesarray[]=$groupes->libelle_groupe ;
			$expl->groupes = @implode("/",$groupesarray);
		}
	}
	return $expl;
}

// permet de savoir si un CB expl est déjà en prêt
function verif_cb_utilise_en_pret ($cb) {
	global $dbh ;
	$rqt = "select count(1) from pret, exemplaires where expl_cb='".$cb."' and pret_idexpl=expl_id";
	$res = mysql_query ($rqt, $dbh) ;
	return mysql_result($res, 0, 0) ;
}
	
// permet de savoir si un CB expl existe simplement
function verif_cb_expl ($cb) {
	global $dbh ;
	$rqt = "select count(1) from exemplaires where expl_cb='".$cb."' ";
	$res = mysql_query ($rqt, $dbh) ;
	return mysql_result($res, 0, 0) ;
}
	
