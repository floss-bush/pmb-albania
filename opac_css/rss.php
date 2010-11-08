<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rss.php,v 1.8 2008-03-22 17:15:07 touraine37 Exp $

@error_reporting (E_ERROR | E_PARSE);
$base_path=".";
$include_path=$base_path."/includes";
$class_path=$base_path."/classes";
require_once($base_path."/includes/includes_rss.inc.php");

// pour affichage de liens sur les éléments affichés :
/*
$liens_opac['lien_rech_notice'] 		= "./index.php?lvl=notice_display&id=!!id!!";
$liens_opac['lien_rech_auteur'] 		= "./index.php?lvl=author_see&id=!!id!!";
$liens_opac['lien_rech_editeur'] 		= "./index.php?lvl=publisher_see&id=!!id!!";
$liens_opac['lien_rech_serie'] 		= "./index.php?lvl=serie_see&id=!!id!!";
$liens_opac['lien_rech_collection'] 		= "./index.php?lvl=coll_see&id=!!id!!";
$liens_opac['lien_rech_subcollection'] 		= "./index.php?lvl=subcoll_see&id=!!id!!";
$liens_opac['lien_rech_indexint'] 		= "./index.php?lvl=indexint_see&id=!!id!!";
$liens_opac['lien_rech_motcle'] 		= "./index.php?lvl=more_results&mode=keyword&user_query=!!mot!!";
$liens_opac['lien_rech_categ'] 			= "./index.php?lvl=categ_see&id=!!id!!";
$liens_opac['lien_rech_perio'] 			= "./index.php?lvl=notice_display&id=!!id!!";
$liens_opac['lien_rech_bulletin'] 		= "./index.php?lvl=bulletin_display&id=!!id!!";
*/

// paramètres :
//	$accueil : filtres les étagères de l'accueil uniquement si 1
//	$etageres : les numéros des étagères séparés par les ',' toutes si vide
//	$commentaire : affiche ou non le commentaire
//	$aff_notices_nb : nombres de notices affichées : toutes = 0 
//	$mode_aff_notice : mode d'affichage des notices, REDUIT (titre+auteur principal) ou ISBD ou PMB ou les deux : dans ce cas : (titre + auteur) en entête du truc, à faire dans notice_display.class.php
//	$depliable : affichage des notices une par ligne avec le bouton de dépliable
//	$link_to_etagere : lien pour afficher le contenu de l'étagère
//	$htmldiv_id="etagere-container", $htmldiv_class="etagere-container", $htmldiv_zindex="" : les id, class et zindex du <DIV > englobant le résultat de la fonction
//	$liens_opac : tableau contenant les url destinatrices des liens si voulu 
// function affiche_etagere($accueil=0, $etageres="", $aff_commentaire=0, $aff_notices_nb=0, $mode_aff_notice=AFF_ETA_NOTICES_BOTH, $depliable=AFF_ETA_NOTICES_DEPLIABLES_OUI, $link_to_etagere="", $htmldiv_id="etagere-container", $htmldiv_class="etagere-container", $htmldiv_zindex="", $liens_opac=array() ) {

$flux = new rss_flux($id) ;
if (!$flux->contenu_du_flux) {
	$flux->items_notices() ;
	$flux->xmlfile() ;
	$flux->contenu_du_flux = str_replace ("!!items!!",$flux->notices,$flux->envoi);
	$flux->stocke_cache() ;
} 
@header('Content-type: text/xml');
echo $flux->contenu_du_flux ;