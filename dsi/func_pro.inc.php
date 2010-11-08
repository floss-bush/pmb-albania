<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_pro.inc.php,v 1.33 2009-05-16 11:08:24 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function get_bannette_pro($title_form, $message, $form_action, $form_cb="") {
	global $dsi_search_bannette_tmpl;
	global $id_classement;
	$dsi_search_tmpl = $dsi_search_bannette_tmpl;
	$dsi_search_tmpl = str_replace("!!titre_formulaire!!", $title_form, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!form_action!!", $form_action, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!message!!", $message, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!cb_initial!!", $form_cb, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!classement!!", gen_liste_classement("BAN", $id_classement, "this.form.submit();")  , $dsi_search_tmpl);
	return $dsi_search_tmpl;
	}

function dsi_list_bannettes($form_cb="", $id_bannette=0, $id_classement="") {

global $dbh, $msg, $charset;
global $page, $nbr_lignes;
global $dsi_list_tmpl;


// nombre de références par pages
$nb_per_page = 10;

if ($form_cb) {
	$form_cb = str_replace("*", "%", $form_cb) ;
	$clause = "WHERE nom_bannette like '$form_cb%' and proprio_bannette=0" ;
	} else $clause = "WHERE proprio_bannette=0" ;
if ($id_classement===0) $clause.= " and num_classement=0 "; 
	elseif ($id_classement>0) $clause.= " and num_classement='$id_classement' " ;

if(!$nbr_lignes) {
	$requete = "SELECT COUNT(1) FROM bannettes $clause ";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);
	}

if (!$page) $page=1;
$debut = ($page-1)*$nb_per_page;

if($nbr_lignes) {

		// on lance la vraie requête
		$requete = "SELECT id_bannette, nom_bannette, comment_gestion FROM bannettes $clause ORDER BY nom_bannette, id_bannette LIMIT $debut,$nb_per_page ";
		$res = @mysql_query($requete, $dbh);

		$parity = 0;
		$ban_trouvees =  mysql_num_rows($res) ;
		while(($bann=mysql_fetch_object($res))) {
			if ($parity % 2) $pair_impair = "even";
				else $pair_impair = "odd";
			$td_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./dsi.php?categ=bannettes&sub=pro&id_bannette=$bann->id_bannette&suite=acces&id_classement=$id_classement';\" ";
			$bann_list .= "<tr class='$pair_impair' $td_javascript style='cursor: pointer' >";
			$bann_list .= "
				<td width='70%'>
					<strong>".htmlentities($bann->nom_bannette,ENT_QUOTES, $charset)."</strong>
					<br />(".htmlentities($bann->comment_gestion,ENT_QUOTES, $charset).")
					</td>";
			$bann_list .= "
				<td width='30%'>
					<a href='./dsi.php?categ=bannettes&sub=pro&suite=affect_equation&id_bannette=$bann->id_bannette'>$msg[dsi_ban_affect_equation]</a>
					<br />
					<a href='./dsi.php?categ=bannettes&sub=pro&suite=affect_lecteurs&id_bannette=$bann->id_bannette'>$msg[dsi_ban_affect_lecteurs]</a>
					</td>";
			$bann_list .= "</tr>";
			$parity += 1;
			}
		mysql_free_result($res);

		// affichage de la barre de navig
		$url_base = "$PHP_SELF?categ=bannettes&sub=pro&form_cb=".rawurlencode($form_cb)."&id_classement=$id_classement" ;
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		
		$dsi_list_tmpl = str_replace("!!cle!!", $form_cb, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!list!!", $bann_list, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!message_trouve!!", $msg['dsi_ban_trouvees'], $dsi_list_tmpl);
		
		return $dsi_list_tmpl;
		} else return $msg['dsi_no_ban_found'] ;
}

function bannette_equation ($nom="", $id_bannette=0) {
	global $dsi_bannette_equation_assoce, $msg, $dbh, $id_classement ;
	global $charset ;
	
	if (!$id_classement) $id_classement=0;
	$url_base = "./dsi.php?categ=bannettes&sub=pro&id_bannette=$id_bannette&suite=affect_equation"; 
	$url_modif = "./dsi.php?categ=bannettes&sub=pro&id_bannette=$id_bannette&suite=acces"; 
	// $detail_bannette = "<h3>$nom &nbsp;<input type='button' class='bouton' value=\"$msg[dsi_bt_modifier_ban]\" onclick=\"document.location='$url_modif';\" /></h3>";
	if ($id_classement>0) $requete = "select distinct id_equation, num_classement, nom_equation, comment_equation, proprio_equation from equations left join bannette_equation on num_equation=id_equation where proprio_equation=0 and num_classement='$id_classement' order by nom_equation " ;
		elseif ($id_classement==0) $requete = "select distinct id_equation, num_classement, nom_equation, comment_equation, proprio_equation from equations left join bannette_equation on num_equation=id_equation where proprio_equation=0 order by nom_equation " ;
			elseif ($id_classement==-1) $requete = "select distinct id_equation, num_classement, nom_equation, comment_equation, proprio_equation from equations, bannette_equation where num_bannette=$id_bannette and num_equation=id_equation and proprio_equation=0 order by nom_equation " ;
	$res = mysql_query($requete, $dbh) or die ($requete) ;
	$parity = 0;
	$equ_trouvees =  mysql_num_rows($res) ;
	while ($equa=mysql_fetch_object($res)) {
		$equations .= "<input type='checkbox' name='bannette_equation[]' value='$equa->id_equation' ";
		$requete_affect = "SELECT 1 FROM bannette_equation where num_equation='$equa->id_equation' and num_bannette='$id_bannette' ";
		$res_affect = mysql_query($requete_affect, $dbh);
		if (mysql_num_rows($res_affect)) $equations .= "checked" ;
		$equations .= " /> $equa->nom_equation<br />";
		}
	$dsi_bannette_equation_assoce = str_replace("!!form_action!!", $url_base."&faire=enregistrer", $dsi_bannette_equation_assoce);
	$dsi_bannette_equation_assoce = str_replace("!!nom_bannette!!", $nom, $dsi_bannette_equation_assoce);
	$dsi_bannette_equation_assoce = str_replace("!!equations!!", $equations, $dsi_bannette_equation_assoce);
	$dsi_bannette_equation_assoce = str_replace("!!id_classement_anc!!", $id_classement, $dsi_bannette_equation_assoce);
	$dsi_bannette_equation_assoce = str_replace("!!id_bannette!!", $id_bannette, $dsi_bannette_equation_assoce);
	$dsi_bannette_equation_assoce = str_replace("!!classement!!", 
		gen_liste ("select id_classement, nom_classement from classements where id_classement=1 union select 0 as id_classement, '".$msg['dsi_all_classements']."' as nom_classement UNION select id_classement, nom_classement from classements where type_classement='EQU' order by nom_classement", "id_classement", "nom_classement", "id_classement", "this.form.faire.value=''; this.form.submit();", $id_classement, "", "",-1,$msg['dsi_ban_equation_affectees'],0)
		, $dsi_bannette_equation_assoce);

	// afin de revenir où on était : $form_cb, le critère de recherche
	global $form_cb ;
	$dsi_bannette_equation_assoce = str_replace('!!form_cb!!', urlencode($form_cb),  $dsi_bannette_equation_assoce);
	$dsi_bannette_equation_assoce = str_replace('!!form_cb_hidden!!', htmlentities($form_cb,ENT_QUOTES, $charset),  $dsi_bannette_equation_assoce);

	$detail_bannette .= $dsi_bannette_equation_assoce ;
	return $detail_bannette ;
	}

function bannette_lecteur ($nom="", $id_bannette=0) {
	global $dsi_bannette_lecteurs_assoce, $msg, $dbh, $id_categorie ;
	global $lect_restrict, $empr_location_id, $deflt2docs_location, $pmb_lecteurs_localises ;
	global $charset ;
	
	$nb_limit = 20 ;
	if ($lect_restrict) {
		$lect_query = str_replace("*","%",$lect_restrict."*") ;
		$limit_nb = "" ;
		} else {
			$lect_query = "%" ;
			$limit_nb = " limit $nb_limit " ;
			}
	
	if ($pmb_lecteurs_localises && (string)$empr_location_id!="0") {
		if ((string)$empr_location_id=="") $empr_location_id=$deflt2docs_location;
		$restrict_loc = " and empr_location=$empr_location_id ";
		} else $restrict_loc = "";

	if (!$id_categorie) $id_categorie=0;
	$url_base = "./dsi.php?categ=bannettes&sub=pro&id_bannette=$id_bannette&suite=affect_lecteurs"; 
	$url_modif = "./dsi.php?categ=bannettes&sub=pro&id_bannette=$id_bannette&suite=acces"; 


	if ($id_categorie>0) $requete = "select id_empr, empr_cb, concat(empr_nom, ' ', empr_prenom) as nom_prenom, empr_mail from empr where empr_categ='$id_categorie' and empr_nom like '$lect_query' $restrict_loc order by nom_prenom, empr_cb $limit_nb" ;
		elseif ($id_categorie==0) $requete = "select id_empr, empr_cb, concat(empr_nom, ' ', empr_prenom) as nom_prenom, empr_mail from empr where empr_nom like '$lect_query' $restrict_loc order by nom_prenom, empr_cb $limit_nb " ;
			elseif ($id_categorie==-1) $requete = "select id_empr, empr_cb, concat(empr_nom, ' ', empr_prenom) as nom_prenom, empr_mail from empr, bannette_abon where num_bannette='$id_bannette' and id_empr=num_empr and empr_nom like '$lect_query' $restrict_loc order by nom_prenom, empr_cb $limit_nb" ;
	$res = mysql_query($requete, $dbh) or die ($requete) ;
	$parity = 0;
	$lec_trouvees =  mysql_num_rows($res) ;
	while ($lec=mysql_fetch_object($res)) {
		$lecteurs .= "<input type='checkbox' name='bannette_abon[]' value='$lec->id_empr' ";
		$requete_affect = "SELECT 1 FROM bannette_abon where num_empr='$lec->id_empr' and num_bannette='$id_bannette' ";
		$res_affect = mysql_query($requete_affect, $dbh);
		if (mysql_num_rows($res_affect)) $lecteurs .= "checked" ;
		if ($lec->empr_mail) $aff_lec_nom_email = "<b>".$lec->nom_prenom."</b> <font color=red>".$lec->empr_mail."</font>";
			else $aff_lec_nom_email = $lec->nom_prenom ;
		$lecteurs .= " /> $aff_lec_nom_email<br />";
		}
	$dsi_bannette_lecteurs_assoce = str_replace("!!form_action!!", $url_base."&faire=enregistrer", $dsi_bannette_lecteurs_assoce);
	$dsi_bannette_lecteurs_assoce = str_replace("!!nom_bannette!!", $nom, $dsi_bannette_lecteurs_assoce);
	$dsi_bannette_lecteurs_assoce = str_replace("!!lecteurs!!", $lecteurs, $dsi_bannette_lecteurs_assoce);
	$dsi_bannette_lecteurs_assoce = str_replace("!!id_bannette!!", $id_bannette, $dsi_bannette_lecteurs_assoce);
	$dsi_bannette_lecteurs_assoce = str_replace("!!lect_restrict!!", $lect_restrict, $dsi_bannette_lecteurs_assoce);
	if ($limit_nb) $dsi_bannette_lecteurs_assoce = str_replace("!!limitation!!", str_replace('!!nbres!!',$nb_limit,$msg[dsi_ban_abo_limit_abon]), $dsi_bannette_lecteurs_assoce);
		$dsi_bannette_lecteurs_assoce = str_replace("!!limitation!!", $msg[dsi_ban_abo_nolimit_abon], $dsi_bannette_lecteurs_assoce);
	
	$liste_localisations=gen_liste ("select 0 as idlocation, '".$msg['all_location']."' as libelle UNION select idlocation, location_libelle as libelle from docs_location order by libelle", "idlocation", "libelle", "empr_location_id", "this.form.faire.value=''; this.form.submit();", $empr_location_id, "", "","","",0);
	//if ($pmb_lecteurs_localises) $dsi_bannette_lecteurs_assoce = str_replace("!!restrict_location!!", docs_location::gen_combo_box_empr($empr_location_id), $dsi_bannette_lecteurs_assoce);
	if ($pmb_lecteurs_localises) $dsi_bannette_lecteurs_assoce = str_replace("!!restrict_location!!", $liste_localisations, $dsi_bannette_lecteurs_assoce);
		else $dsi_bannette_lecteurs_assoce = str_replace("!!restrict_location!!", "", $dsi_bannette_lecteurs_assoce);
	
	$dsi_bannette_lecteurs_assoce = str_replace("!!classement!!",  
		gen_liste ("select 0 as id_categorie, '".addslashes($msg['dsi_all_categories'])."' as libelle UNION select id_categ_empr as id_categorie, libelle from empr_categ order by libelle", "id_categorie", "libelle", "id_categorie", "this.form.faire.value=''; this.form.submit();", $id_categorie, "", "",-1,$msg['dsi_ban_lecteurs_affectes'],0)
		, $dsi_bannette_lecteurs_assoce);

	// afin de revenir où on était : $form_cb, le critère de recherche
	global $form_cb ;
	$dsi_bannette_lecteurs_assoce = str_replace('!!form_cb!!', urlencode($form_cb),  $dsi_bannette_lecteurs_assoce);
	$dsi_bannette_lecteurs_assoce = str_replace('!!form_cb_hidden!!', htmlentities($form_cb,ENT_QUOTES, $charset),  $dsi_bannette_lecteurs_assoce);

	$detail_lecteurs .= $dsi_bannette_lecteurs_assoce ;
	return $detail_lecteurs ;
	}
	
function dsi_list_bannettes_info($form_cb="", $id_bannette=0, $id_classement="") {

global $dbh, $msg, $charset;
global $page, $nbr_lignes;
global $dsi_list_tmpl;


// nombre de références par pages
$nb_per_page = 10;
 
if ($form_cb) {
	$form_cb_save = $form_cb ;
	$form_cb = str_replace("*", "%", $form_cb) ;
	$clause = "WHERE nom_bannette like '$form_cb%' and proprio_bannette=0 " ;
	} else {
		$form_cb_save = "*" ;
		$clause = "WHERE proprio_bannette=0 " ;
		}
if ($id_classement===0) $clause.= " and num_classement=0 "; 
	elseif ($id_classement>0) $clause.= " and num_classement='$id_classement' " ;

if(!$nbr_lignes) {
	$requete = "SELECT COUNT(1) FROM bannettes $clause ";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);
	}

if (!$page) $page=1;
$debut = ($page-1)*$nb_per_page;

if($nbr_lignes) {

		// on lance la vraie requête
		$requete = "SELECT id_bannette FROM bannettes $clause ORDER BY nom_bannette, id_bannette LIMIT $debut,$nb_per_page ";
		$res = @mysql_query($requete, $dbh);

		$bann_list .= "<tr >";
		$bann_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_ban_form_nom],ENT_QUOTES, $charset)."</strong>
					(".htmlentities($msg[dsi_classement],ENT_QUOTES, $charset).")<ul>
					".htmlentities($msg[dsi_ban_form_com_gestion],ENT_QUOTES, $charset)."
					</ul></th>";
		$bann_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_ban_list_equ],ENT_QUOTES, $charset)."</strong>
					</th>";
		$bann_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_ban_nb_notices],ENT_QUOTES, $charset)."</strong>
					</th>";
		$bann_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_ban_nb_abonnes],ENT_QUOTES, $charset)."</strong>
					</th>";
		$bann_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_ban_date_last_envoi],ENT_QUOTES, $charset)."</strong>
					<br />(".htmlentities($msg[dsi_ban_date_last_remp],ENT_QUOTES, $charset).")
					</th>";
		$bann_list .= "</tr>";
		
		$parity = 0;
		$ban_trouvees =  mysql_num_rows($res) ;
		while(($ban=mysql_fetch_object($res))) {
			$bann = new bannette($ban->id_bannette) ;
			if ($parity % 2) $pair_impair = "even";
				else $pair_impair = "odd";
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
			$bann_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer' >";
			$td_javascript=" onmousedown=\"document.location='./dsi.php?categ=bannettes&sub=pro&id_bannette=$bann->id_bannette";
			$td_javascript.="&suite=acces&id_classement=$id_classement";
			$td_javascript.="&form_cb=".urlencode($form_cb_save);
			$td_javascript.="';\" ";
			$bann_list .= "
				<td valign='top' $td_javascript>
					<strong>".htmlentities($bann->nom_bannette,ENT_QUOTES, $charset)."</strong>
					<strong>(".htmlentities($bann->nom_classement,ENT_QUOTES, $charset).")</strong><ul>
					<em>".htmlentities($bann->comment_gestion,ENT_QUOTES, $charset)."</em>";
			$bann_list .= "</ul></td>";
			$requete = "select id_equation, num_classement, nom_equation, comment_equation, proprio_equation, num_bannette from equations, bannette_equation where num_equation=id_equation and proprio_equation=0 and num_bannette='$bann->id_bannette' order by nom_equation " ;
			$resequ = mysql_query($requete, $dbh) or die ($requete) ;
			$equ_trouvees =  mysql_num_rows($resequ) ;
			$equations = "" ;
			while ($equa=mysql_fetch_object($resequ)) {
				$equations .= "<li>$equa->nom_equation</li>";
				}
			$td_javascript=" onmousedown=\"document.location='./dsi.php?categ=bannettes&sub=pro&id_bannette=$bann->id_bannette&suite=affect_equation&id_classement=$id_classement&form_cb=".urlencode($form_cb_save)."';\" ";
			if($equ_trouvees == 0) $bann_list .= "<td valign='top' $td_javascript><ul>".$msg[dsi_ban_no_equ]."</ul></td>";
				else $bann_list .= "<td valign='top' $td_javascript><ul>$equations</ul></td>";
			$bann_list .= "<td valign='top'>$bann->nb_notices</td>";
			
			$td_javascript=" onmousedown=\"document.location='./dsi.php?categ=bannettes&sub=pro&id_bannette=$bann->id_bannette&suite=affect_lecteurs&id_classement=$id_classement&form_cb=".urlencode($form_cb_save)."';\" ";
			if ($bann->num_panier) $aff_bann_fills_basket = "&nbsp;&nbsp;<img src='./images/basket_small_20x20.gif' border='0' align='center' />";
				else $aff_bann_fills_basket = "";
			$bann_list .= "<td valign='top' $td_javascript>".$bann->nb_abonnes.$aff_bann_fills_basket."</td>";
			
			$td_javascript=" onmousedown=\"document.location='./dsi.php?categ=diffuser&sub=auto&id_bannette=$bann->id_bannette&id_classement=$id_classement&form_cb=".urlencode($form_cb_save)."';\" ";
			$bann_list .= "<td valign='top' $td_javascript>
					<strong>".htmlentities($bann->aff_date_last_envoi,ENT_QUOTES, $charset)."</strong>";
			if ($bann->alert_diff) $bann_list .= "<br /><font color=red>(".htmlentities($bann->aff_date_last_remplissage,ENT_QUOTES, $charset).")</font>";
				else $bann_list .= "<br />(".htmlentities($bann->aff_date_last_remplissage,ENT_QUOTES, $charset).")" ;
			$bann_list .= "</td>";
			$bann_list .= "</tr>";
			$parity += 1;
			}
		mysql_free_result($res);


		// affichage de la barre de navig
		$url_base = "$PHP_SELF?categ=bannettes&sub=pro&suite=search&form_cb=".rawurlencode($form_cb)."&id_classement=$id_classement" ;
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		
		if ($nbr_lignes>0) $dsi_list_tmpl = str_replace("<!--!!nb_total!!-->", "(".$nbr_lignes.")", $dsi_list_tmpl);
		
		$dsi_list_tmpl = str_replace("!!cle!!", $form_cb, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!list!!", $bann_list, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!message_trouve!!", $msg['dsi_ban_trouvees'], $dsi_list_tmpl);
		
		return $dsi_list_tmpl;
		} else return $msg['dsi_no_ban_found'] ;
}
	