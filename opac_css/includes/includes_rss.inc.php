<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: includes_rss.inc.php,v 1.15 2010-02-28 21:45:37 jokester Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if (!$lang) $lang='fr_FR' ;

require_once($base_path."/includes/init.inc.php");
include_once($base_path."/includes/error_report.inc.php") ;
include_once($base_path."/includes/global_vars.inc.php");

// récupération paramètres MySQL et connection á la base
require_once($base_path."/includes/opac_db_param.inc.php");
require_once($base_path."/includes/opac_mysql_connect.inc.php");
$dbh = connection_mysql();
require_once($base_path.'/includes/opac_config.inc.php');
require_once($base_path."/includes/marc_tables/".$lang."/empty_words");
require_once($base_path."/includes/start.inc.php");

// récupération localisation
require_once($base_path.'/includes/localisation.inc.php');

require_once($base_path."/classes/rss_flux.class.php");
require_once($base_path."/classes/notice_affichage.class.php");
require_once($base_path."/includes/notice_categories.inc.php");
require_once($base_path."/includes/misc.inc.php");
require_once($base_path."/includes/explnum.inc.php");
require_once($base_path."/classes/collection.class.php");
require_once($base_path."/classes/subcollection.class.php");
require_once($base_path."/classes/indexint.class.php");

// classe d'affichage des tags
require_once($base_path.'/classes/tags.class.php');

//pour la gestion des tris
require_once($base_path."/classes/sort.class.php");

$liens_opac['lien_rech_notice'] 		= $opac_url_base."index.php?lvl=notice_display&id=!!id!!";

function genere_link_rss() {
	global $dbh, $opac_url_base, $charset, $logo_rss_si_rss, $msg ;
	$rqt = "select id_rss_flux, nom_rss_flux, descr_rss_flux from rss_flux order by 2 ";
	$res = mysql_query($rqt,$dbh);
	while ($obj=mysql_fetch_object($res)) {
		$liens .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".htmlentities($obj->nom_rss_flux,ENT_QUOTES, $charset)."\" href=\"".$opac_url_base."rss.php?id=".$obj->id_rss_flux."\" alt=\"\" />" ;
		}
	if ($liens) $logo_rss_si_rss = "<a href='index.php?lvl=rss_see&id=' alt=\"".$msg[show_rss_dispo]."\" title=\"".$msg[show_rss_dispo]."\"><img id=\"rss_logo\" src='".$opac_url_base."images/rss.png' valign='middle' border=none /></a>" ;
	return $liens ;
}

function genere_page_rss($id=0) {
	global $dbh, $opac_url_base, $charset, $msg ;
	
	if ($id) $clause = " where id_rss_flux='$id' ";  
	$rqt = "select id_rss_flux, nom_rss_flux, img_url_rss_flux from rss_flux $clause order by 2 ";
	$res = mysql_query($rqt,$dbh);
	while ($obj=mysql_fetch_object($res)) {
		$liens .= "
		<tr>
			<td width=10%>";
		if ($obj->img_url_rss_flux) $liens .= "<a href=\"index.php?lvl=rss_see&id=".$obj->id_rss_flux."\"><img src='".$obj->img_url_rss_flux."' border=none /></a>";
		$liens .= "</td><td width=50%><a href=\"index.php?lvl=rss_see&id=".$obj->id_rss_flux."\">".htmlentities($obj->nom_rss_flux,ENT_QUOTES, $charset)."</a>
			</td><td><a href=\"".$opac_url_base."rss.php?id=".$obj->id_rss_flux."\" alt=\"".$msg[abonne_rss_dispo]."\" title=\"".$msg[abonne_rss_dispo]."\"><img id=\"rss_logo\" src='".$opac_url_base."images/rss.png' border=none /></a>
			".htmlentities($opac_url_base."rss.php?id=".$obj->id_rss_flux,ENT_QUOTES, $charset)."
				</td></tr>" ;
		}
	if ($liens) $liens = "<table> $liens </table>" ;
	return $liens ;
}

