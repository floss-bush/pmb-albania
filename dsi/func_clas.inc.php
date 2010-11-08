<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_clas.inc.php,v 1.6 2009-05-16 11:08:24 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function dsi_list_classements() {

global $dbh, $msg;
global $page, $nbr_lignes;
global $dsi_list_tmpl;
global $form_cb;

// nombre de références par pages
$nb_per_page = 10;

if(!$nbr_lignes) {
	$requete = "SELECT COUNT(1) FROM classements ";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);
	}

if (!$page) $page=1;
$debut = ($page-1)*$nb_per_page;

if($nbr_lignes) {

		// on lance la vraie requête
		$requete = "SELECT id_classement, nom_classement, type_classement FROM classements ORDER BY type_classement, nom_classement, id_classement LIMIT $debut,$nb_per_page ";
		$res = @mysql_query($requete, $dbh);

		$parity = 0;
		while(($clas=mysql_fetch_object($res))) {
			if ($parity % 2) $pair_impair = "even";
				else $pair_impair = "odd";
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./dsi.php?categ=options&sub=classements&id_classement=$clas->id_classement&suite=acces';\" ";
			$empr_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
			$empr_list .= "
				<td>
					<strong>".$msg['dsi_clas_type_class_'.$clas->type_classement]."</strong>
					</td>
				<td>
					<strong>$clas->nom_classement</strong>
					</td>
				</tr>";
			$parity += 1;
			}
		mysql_free_result($res);

		// affichage de la barre de navig
		$url_base = "$PHP_SELF?categ=options&sub=classements" ;
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;

		if ($nbr_lignes>0) $dsi_list_tmpl = str_replace("<!--!!nb_total!!-->", "(".$nbr_lignes.")", $dsi_list_tmpl);

		$dsi_list_tmpl = str_replace("!!cle!!", $form_cb, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!list!!", $empr_list, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!message_trouve!!", "", $dsi_list_tmpl);
		$ajout = "<br /><input type='button' class='bouton' value='$msg[dsi_clas_ajouter]' onclick=\"document.location='./dsi.php?categ=options&sub=classements&suite=add'\" />" ;
		
		return $dsi_list_tmpl.$ajout;
		} else return "";
}
	