<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_rss.inc.php,v 1.5 2007-09-07 08:52:59 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function get_flux($title_form, $message, $form_action, $form_cb="") {
	global $dsi_search_flux_tmpl;

	$dsi_search_tmpl = $dsi_search_flux_tmpl;
	$dsi_search_tmpl = str_replace("!!titre_formulaire!!", $title_form, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!form_action!!", $form_action, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!message!!", $message, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!cb_initial!!", $form_cb, $dsi_search_tmpl);
	return $dsi_search_tmpl;
	}

function dsi_list_flux($form_cb="", $id_rss_flux=0) {

global $dbh, $msg, $charset;
global $page, $nbr_lignes;
global $dsi_list_tmpl;


// nombre de références par pages
$nb_per_page = 10;

if ($form_cb) {
	$form_cb = str_replace("*", "%", $form_cb) ;
	$clause = "WHERE nom_rss_flux like '$form_cb%' " ;
	} else $clause = "WHERE 1 " ;

if(!$nbr_lignes) {
	$requete = "SELECT COUNT(1) FROM rss_flux $clause ";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);
	}

if (!$page) $page=1;
$debut = ($page-1)*$nb_per_page;

if($nbr_lignes) {

		// on lance la vraie requête
		$requete = "SELECT id_rss_flux, nom_rss_flux, format_flux FROM rss_flux $clause ORDER BY nom_rss_flux, id_rss_flux LIMIT $debut,$nb_per_page ";
		$res = @mysql_query($requete, $dbh);

		$parity = 0;
		$flux_trouves =  mysql_num_rows($res) ;
		while(($flux=mysql_fetch_object($res))) {
			if ($parity % 2) $pair_impair = "even";
				else $pair_impair = "odd";
			$td_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./dsi.php?categ=fluxrss&sub=&id_rss_flux=$flux->id_rss_flux&suite=acces';\" ";
			$flux_list .= "<tr class='$pair_impair' $td_javascript style='cursor: pointer' >";
			$flux_list .= "
				<td width='70%'>
					<strong>".htmlentities($flux->nom_rss_flux,ENT_QUOTES, $charset)."</strong>
					</td>";
			$flux_list .= "
				<td width='30%'>
					...
					</td>";
			$flux_list .= "</tr>";
			$parity += 1;
			}
		mysql_free_result($res);

		// constitution des liens
		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;

		// affichage du lien précédent si nécéssaire
		if ($precedente > 0) $nav_bar .= "<a href='$PHP_SELF?categ=fluxrss&sub=&page=$precedente&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."'><img src='./images/left.gif' border='0' title='$msg[48] alt='[$msg[48]]' /></a>";

		for($i = 1; $i <= $nbepages; $i++) {
			if($i==$page) $nav_bar .= "<span>page $i/$nbepages</span>";
			}

		if ($suivante<=$nbepages) $nav_bar .= " <a href='$PHP_SELF?categ=fluxrss&sub=&page=$suivante&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."&id_classement=$id_classement'><img src='./images/right.gif' border='0' title='$msg[49] alt='[$msg[49]]' /></a>";

		if ($flux_trouves>0) $dsi_list_tmpl = str_replace("<!--!!nb_total!!-->", "(".$flux_trouves.")", $dsi_list_tmpl);
		
		$dsi_list_tmpl = str_replace("!!cle!!", $form_cb, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!list!!", $flux_list, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!message_trouve!!", $msg['dsi_flux_trouves'], $dsi_list_tmpl);
		
		return $dsi_list_tmpl;
		} else return $msg['dsi_no_flux_found'] ;
}

function dsi_list_flux_info($form_cb="", $id_rss_flux=0) {

global $dbh, $msg, $charset;
global $page, $nbr_lignes;
global $dsi_list_tmpl;
global $opac_url_base ;

// nombre de références par pages
$nb_per_page = 10;
 
if ($form_cb) {
	$form_cb_save = $form_cb ;
	$form_cb = str_replace("*", "%", $form_cb) ;
	$clause = "WHERE nom_rss_flux like '$form_cb%' " ;
	} else {
		$form_cb_save = "*" ;
		$clause = "WHERE 1 " ;
		}

if(!$nbr_lignes) {
	$requete = "SELECT COUNT(1) FROM rss_flux $clause ";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);
	}

if (!$page) $page=1;
$debut = ($page-1)*$nb_per_page;

if($nbr_lignes) {

		// on lance la vraie requête
		$requete = "SELECT id_rss_flux FROM rss_flux $clause ORDER BY nom_rss_flux, id_rss_flux LIMIT $debut,$nb_per_page ";
		$res = mysql_query($requete, $dbh);

		$flux_list .= "<tr >";
		$flux_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_flux_form_nom],ENT_QUOTES, $charset)."</strong></th>";
		$flux_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_flux_nb_paniers],ENT_QUOTES, $charset)."</strong>
					</th>";
		$flux_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_flux_nb_bannettes],ENT_QUOTES, $charset)."</strong>
					</th>";
		$flux_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_flux_format],ENT_QUOTES, $charset)."</strong>
					</th>";
		$flux_list .= "</tr>";
		
		$parity = 0;
		$flux_trouves =  mysql_num_rows($res) ;
		while(($flux=mysql_fetch_object($res))) {
			$flux = new rss_flux($flux->id_rss_flux) ;
			if ($parity % 2) $pair_impair = "even";
				else $pair_impair = "odd";
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
			$flux_list .= "<tr class='$pair_impair' $tr_javascript >";
			$td_javascript=" onmousedown=\"document.location='./dsi.php?categ=fluxrss&sub=&id_rss_flux=$flux->id_rss_flux";
			$td_javascript.="&suite=acces";
			$td_javascript.="&form_cb=".urlencode($form_cb_save);
			$td_javascript.="';\" ";
			$flux_list .= "
				<td valign='top' $td_javascript style='cursor: pointer' >
					<strong>".htmlentities($flux->nom_rss_flux,ENT_QUOTES, $charset)."</strong>
					</td>";
			$flux_list .= "<td valign='top'>$flux->nb_paniers</td>";
			
			$flux_list .= "<td valign='top'>$flux->nb_bannettes</td>";
			
			$flux_list .= "<td valign='top'>".$opac_url_base."rss.php?id=".$flux->id_rss_flux."</td>";
			
			$flux_list .= "</tr>";
			$parity += 1;
			}
		mysql_free_result($res);

		// constitution des liens
		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;

		// affichage du lien précédent si nécéssaire
		if ($precedente > 0) $nav_bar .= "<a href='$PHP_SELF?categ=fluxrss&sub=&suite=search&page=$precedente&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."'><img src='./images/left.gif' border='0' title='$msg[48] alt='[$msg[48]]' /></a>";

		for($i = 1; $i <= $nbepages; $i++) {
			if($i==$page) $nav_bar .= "<span>page $i/$nbepages</span>";
			}

		if ($suivante<=$nbepages) $nav_bar .= " <a href='$PHP_SELF?categ=fluxrss&sub=&suite=search&page=$suivante&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."'><img src='./images/right.gif' border='0' title='$msg[49] alt='[$msg[49]]' /></a>";

		if ($flux_trouves>0) $dsi_list_tmpl = str_replace("<!--!!nb_total!!-->", "(".$flux_trouves.")", $dsi_list_tmpl);
		
		$dsi_list_tmpl = str_replace("!!cle!!", $form_cb, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!list!!", $flux_list, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!message_trouve!!", $msg['dsi_flux_trouves'], $dsi_list_tmpl);
		
		return $dsi_list_tmpl;
		} else return $msg['dsi_no_flux_found'] ;
}
	