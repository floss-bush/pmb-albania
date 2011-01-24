<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: etagere_func.inc.php,v 1.38 2010-10-21 07:13:26 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path.'/includes/templates/notice_display.tpl.php');
require_once($base_path.'/includes/explnum.inc.php');

// tableau des étagères avec leurs petits caddies associés
// 	$accueil=1 filtre les étagères de l'accueil uniquement
//	$idetagere permet de récupérer soit toutes les éragères soit une seule
function tableau_etagere($idetagere, $accueil=0) {
	global $dbh;
	
	global $opac_etagere_order ;
	if (!$opac_etagere_order) $opac_etagere_order =" name ";
	
	$tableau_etagere = array() ;
	
	// on constitue un tableau avec les étagères et les caddies associés
	if ($accueil) $clause_accueil="visible_accueil=1 and";
	if ($idetagere) $clause_etagere="idetagere in ($idetagere) and";
	$query = "select idetagere, name, comment from etagere where $clause_accueil $clause_etagere ( (validite_date_deb<=sysdate() and validite_date_fin>=sysdate()) or validite=1 ) order by $opac_etagere_order ";
	$result = mysql_query($query, $dbh);
	if (mysql_num_rows($result)) {
		while ($etagere=mysql_fetch_object($result)) {
			$tableau_etagere[] = array (
					'idetagere' => $etagere->idetagere,
					'nometagere' => $etagere->name,
					'commentetagere' => $etagere->comment,
					'idcaddies' => caddies_etagere($etagere->idetagere)
					);
		}
	}
	return $tableau_etagere;
}

// tableau des caddies d'une étagère
function caddies_etagere($idetagere) {
	global $dbh ;
	$caddie_tableau=array() ;
	// on constitue un tableau avec les caddies de l'étagère
	$query_caddie = "select caddie_id from etagere_caddie where etagere_id='".$idetagere."' ";
	$result_caddie = mysql_query($query_caddie, $dbh);
	if (mysql_num_rows($result_caddie)) {
		while (($caddie=mysql_fetch_object($result_caddie))) {
			$caddie_tableau[]= $caddie->caddie_id ; 
		}
	} // fin if caddies
	return 	$caddie_tableau ;
}
	
// tableau des notices d'une étagère
function notices_caddie($idetagere, &$notices, $acces_j='', $statut_j='', $statut_r='') {
	
	global $dbh ;
	global $opac_etagere_notices_order ;
	
	if (!$opac_etagere_notices_order) {
		$opac_etagere_notices_order =" index_serie, tit1 ";
	} else {
		$opac_etagere_notices_order = " $opac_etagere_notices_order ";	
	}
	
	// on constitue un tableau avec les notices du caddie
	$query_notice = "select distinct notice_id from caddie_content, etagere_caddie, notices $acces_j $statut_j ";
	$query_notice.= "where etagere_id=$idetagere and caddie_content.caddie_id=etagere_caddie.caddie_id and notice_id=object_id $statut_r ";
	$query_notice.= "order by $opac_etagere_notices_order ";
	
	$result_notice = mysql_query($query_notice, $dbh);
	
	if (mysql_num_rows($result_notice)) {
		while (($notice=mysql_fetch_object($result_notice))) {
			$notices[$notice->notice_id]= $notice->niveau_biblio ; 
		}
	} // fin if notices
}

// paramètres :
//	$accueil : filtres les étagères de l'accueil uniquement si 1
//	$etageres : les numéros des étagères séparés par les ',' toutes si vides
//	$aff_notices_nb : nombres de notices affichées : toutes = 0 
//	$mode_aff_notice : mode d'affichage des notices, REDUIT (titre+auteur principal) ou ISBD ou PMB ou les deux : dans ce cas : (titre + auteur) en entête du truc, à faire dans notice_display.class.php
//	$depliable : affichage des notices une par ligne avec le bouton de dépliable
//	$link_to_etagere : lien pour afficher le contenu de l'étagère "./index.php?lvl=etagere_see&id=!!id!!"
//	$htmldiv_id="etagere-container", $htmldiv_class="etagere-container", $htmldiv_zindex="" : les id, class et zindex du <DIV > englobant le résultat de la fonction
//	$liens_opac : tableau contenant les url destinatrices des liens si voulu 
function affiche_etagere($accueil=0, $etageres="", $aff_commentaire=0, $aff_notices_nb=0, $mode_aff_notice=AFF_ETA_NOTICES_BOTH, $depliable=AFF_ETA_NOTICES_DEPLIABLES_OUI, $link_to_etagere="", $liens_opac=array(), $htmldiv_id="etagere-container", $htmldiv_class="etagere-container", $htmldiv_zindex="") {
	
	global $charset;
	global $opac_etagere_nbnotices_accueil;
	global $gestion_acces_active, $gestion_acces_empr_notice;
	global $class_path;
	
	//droits d'acces emprunteur/notice
	$acces_j='';
	if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
		require_once("$class_path/acces.class.php");
		$ac= new acces();
		$dom_2= $ac->setDomain(2);
		$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
	}
		
	if($acces_j) {
		$statut_j='';
		$statut_r='';
	} else {
		$statut_j=',notice_statut';
		$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
	}	

	// récupération des étagères
	if (!$etageres) $tableau_etageres = tableau_etagere(0, $accueil) ;
	else $tableau_etageres = tableau_etagere($etageres, $accueil) ;

	if (!sizeof($tableau_etageres)) return "" ;
		
	// préparation du div comme il faut
	$retour_aff = "<div id='$htmldiv_id' class='$htmldiv_class'";
	if ($htmldiv_zindex) $retour_aff .=" zindex='$htmldiv_zindex' ";
	$retour_aff .=" >";

	for ($i=0; $i<sizeof($tableau_etageres); $i++ ) {
		$idetagere=$tableau_etageres[$i]['idetagere'] ;
		$nometagere=$tableau_etageres[$i]['nometagere'] ;
		$commentetagere=$tableau_etageres[$i]['commentetagere'] ;
		$retour_aff.="\n<div id='etagere' class='etagere' ><div id='etagere-titre'><h1>";
		if ($link_to_etagere) $retour_aff.="<a href=\"".str_replace("!!id!!",$idetagere,$link_to_etagere)."\">";
		$retour_aff.= htmlentities($nometagere,ENT_QUOTES, $charset);
		if ($link_to_etagere) $retour_aff.="</a>";
		$retour_aff.= "</h1></div>";
		if ($aff_commentaire) {
			$retour_aff .="\n<div id='etagere-comment'><h2>".htmlentities($commentetagere,ENT_QUOTES, $charset)."</h2></div>";
		}
		$idcaddies=$tableau_etageres[$i]['idcaddies'] ;
		$notices = array() ;
		//On récupère les notices associées à l'étagère
		notices_caddie($idetagere, $notices, $acces_j, $statut_j, $statut_r) ;
	
		if ($aff_notices_nb>0) $limite_notices = min($aff_notices_nb, count($notices)) ;
		elseif ($aff_notices_nb<0) $limite_notices = min($aff_notices_nb, count($notices)) ;
		else  $limite_notices = count($notices) ;
		reset ($notices) ;
		$limit=0;
		if ($limite_notices) $retour_aff.= "<div id='etagere-notice-list'>";
		while ((list($idnotice, $niveau_biblio)= each($notices)) && ($limit<$limite_notices)) {
			$limit++;
			$retour_aff .= aff_notice($idnotice, 0, 1, 0, $mode_aff_notice, $depliable);
		}
		//if ($limite_notices&&($limite_notices<count($notices))) $retour_aff.= "<br />";
		if ($opac_etagere_nbnotices_accueil>=0 && (count($notices)>$limite_notices) ) {
			if ($link_to_etagere) $retour_aff.="<a href=\"".str_replace("!!id!!",$idetagere,$link_to_etagere)."\">";
			$retour_aff.="<span class='etagere-suite'>...</span>";
			if ($link_to_etagere) $retour_aff.="</a>";
		}
		if ($limite_notices) $retour_aff.= "</div>";
		$retour_aff .= "</div>" ;		
	}
	
	// fermeture du DIV
	$retour_aff .= "</div><!-- fin id='$htmldiv_id' class='$htmldiv_class' -->";
	return $retour_aff ; 
	
}

// paramètres :
//	$idetagere : l'id de l'étagère
//	$aff_notices_nb : nombres de notices affichées : toutes = 0 
//	$mode_aff_notice : mode d'affichage des notices, REDUIT (titre+auteur principal) ou ISBD ou PMB ou les deux : dans ce cas : (titre + auteur) en entête du truc, à faire dans notice_display.class.php
//	$depliable : affichage des notices une par ligne avec le bouton de dépliable
//	$link_to_etagere : 0 ou 1 pour afficher le lien d'accès à l'étagère en cas de nb notices > nb max
//  $link : "./index.php?lvl=etagere_see&id=!!id!!"
function contenu_etagere($idetagere, $aff_notices_nb=0, $mode_aff_notice=AFF_ETA_NOTICES_BOTH, $depliable=AFF_ETA_NOTICES_DEPLIABLES_OUI, $link_to_etagere="", $link) {
	
	global $charset;
	global $gestion_acces_active, $gestion_acces_empr_notice;
	global $class_path;
	
	//droits d'acces emprunteur/notice
	$acces_j='';
	if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
		require_once("$class_path/acces.class.php");
		$ac= new acces();
		$dom_2= $ac->setDomain(2);
		$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
	}
		
	if($acces_j) {
		$statut_j='';
		$statut_r='';
	} else {
		$statut_j=',notice_statut';
		$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
	}	

	if (!$idetagere) return "" ;
		
	$notices = array() ;
	//On récupère les notices associées à l'étagère
	notices_caddie($idetagere, $notices, $acces_j, $statut_j, $statut_r) ;

	if ($aff_notices_nb>0) $limite_notices = min($aff_notices_nb, count($notices)) ;
	elseif ($aff_notices_nb<0) $limite_notices = min($aff_notices_nb, count($notices)) ;
	else  $limite_notices = count($notices) ;
	reset ($notices) ;
	$limit=0;
	while ((list($idnotice, $niveau_biblio)= each($notices)) && ($limit<$limite_notices)) {
		$limit++;
		$retour_aff .= aff_notice($idnotice, 0, 1, 0, $mode_aff_notice, $depliable);
	}

	if (count($notices)>$limite_notices) {
		if ($link_to_etagere) $retour_aff.="<a href=\"".str_replace("!!id!!",$idetagere,$link)."\">";
		$retour_aff.="<span class='etagere-suite'>...</span>";
		if ($link_to_etagere) $retour_aff.="</a>";
	}
	return $retour_aff ; 
	
}
