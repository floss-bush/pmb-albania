<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_abo.inc.php,v 1.27.4.2 2011-09-29 09:18:23 dgoron Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// form de recherche emprunteur par CB ou NOM
function get_cb_dsi($title_form, $message, $form_action, $form_cb="") {
	global $dsi_search_tmpl;
	global $deflt2docs_location, $empr_location_id, $pmb_lecteurs_localises ;
	
	$dsi_search_tmpl = str_replace("!!titre_formulaire!!", $title_form, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!form_action!!", $form_action, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!message!!", $message, $dsi_search_tmpl);
	$dsi_search_tmpl = str_replace("!!cb_initial!!", $form_cb, $dsi_search_tmpl);
	if ((string)$empr_location_id=="") $empr_location_id=$deflt2docs_location;
	if ($pmb_lecteurs_localises) $dsi_search_tmpl = str_replace("!!restrict_location!!", docs_location::gen_combo_box_empr($empr_location_id), $dsi_search_tmpl);
		else $dsi_search_tmpl = str_replace("!!restrict_location!!", "", $dsi_search_tmpl);

	return $dsi_search_tmpl;
	}

function dsi_list_empr($form_cb="",$ban_priv_seuls=1) {

global $dbh, $msg;
global $page, $nbr_lignes;
global $dsi_list_tmpl, $empr_location_id;
global $deflt2docs_location, $empr_location_id, $pmb_lecteurs_localises ;

if ($ban_priv_seuls) {
	$form_restrict_priv = " join bannettes on num_bannette=id_bannette "; 
	$where_restrict_priv = " and  proprio_bannette=id_empr ";
	} 

$retour=array() ;
// nombre de références par pages
$nb_per_page = 10;

if ($form_cb) {
	$form_cb_rech = str_replace("*", "%", $form_cb) ;
	$clause = "WHERE empr_nom like '$form_cb_rech%' and empr_categ=id_categ_empr" ;
	if ($pmb_lecteurs_localises && $empr_location_id) $clause .= " and empr_location=$empr_location_id "; 
	} else $clause = "WHERE empr_categ=id_categ_empr" ;
if ($empr_location_id && $pmb_lecteurs_localises) $clause .= " and empr_location='$empr_location_id' " ;

if(!$nbr_lignes) {
	$requete = "SELECT COUNT(distinct id_empr) FROM empr join bannette_abon on id_empr=num_empr $form_restrict_priv, empr_categ $clause $where_restrict_priv ";
	$res = mysql_query($requete, $dbh) or die (mysql_error()."<br /><br />$requete");
	$nbr_lignes = @mysql_result($res, 0, 0);
	}

if (!$page) $page=1;
$debut = ($page-1)*$nb_per_page;

if ($nbr_lignes == 1) {
	// on lance la vraie requête
	$requete = "SELECT distinct id_empr as id FROM empr join bannette_abon on id_empr=num_empr $form_restrict_priv, empr_categ $clause $where_restrict_priv ";
	$res = @mysql_query($requete, $dbh);
	$id = @mysql_result($res, '0', 'id');
	if($id) {
		$retour['id_empr'] = $id ;
		$retour['message'] = $msg[dsi_encours_de_dev]." // lecteur trouvé à partir du nom...";
		return $retour ;
		}
	
	} else if($nbr_lignes) {

		// on lance la vraie requête
		$requete = "SELECT distinct libelle, empr_cb, empr_nom, empr_prenom, id_empr  FROM empr join bannette_abon on id_empr=num_empr $form_restrict_priv, empr_categ $clause $where_restrict_priv ORDER BY empr_nom, empr_prenom LIMIT $debut,$nb_per_page ";
		$res = mysql_query($requete, $dbh) or die (mysql_error().$requete);
		$parity = 0;
		while(($empr=mysql_fetch_object($res))) {
			if ($parity % 2) $pair_impair = "even";
				else $pair_impair = "odd";
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./dsi.php?categ=bannettes&sub=abo&id_empr=$empr->id_empr&suite=acces';\" ";
			$empr_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
			$empr_list .= "
				<td>
					<strong>$empr->empr_cb</strong>
					</td>
				<td>
        				$empr->empr_nom&nbsp;$empr->empr_prenom
					</td>
				<td>
					$empr->libelle
					</td>
				</tr>";
			$parity += 1;
			}
		mysql_free_result($res);

		// affichage de la barre de navig
		$url_base = "$PHP_SELF?categ=bannettes&sub=abo&suite=search&form_cb=".rawurlencode($form_cb)."&ban_priv_seuls=$ban_priv_seuls&empr_location_id=$empr_location_id" ;
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		
		$dsi_list_tmpl = str_replace("!!cle!!", $form_cb, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!list!!", $empr_list, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!message_trouve!!", $msg['dsi_empr_trouves'], $dsi_list_tmpl);
		
		$retour['id_empr'] = 0 ;
		$retour['message'] = $dsi_list_tmpl;
		return $retour ;
		} else {
			$retour['id_empr'] = 0 ;
			$retour['message'] = $msg[dsi_lect_aucun_trouve] ;
			return $retour ;
			}
}

// fonction affichant les bannettes privées d'un abonné
function dsi_list_bannettes_abo($id_empr=0) {

global $dbh, $msg, $charset;
global $page, $nbr_lignes;
global $dsi_list_tmpl;
global $form_cb;

$clause = "WHERE proprio_bannette='$id_empr' " ;

		$requete = "SELECT id_bannette FROM bannettes $clause ORDER BY nom_bannette, id_bannette ";
		$res = @mysql_query($requete, $dbh);

		$bann_list .= "<tr >";
		$bann_list .= "
				<th>
					<strong>".htmlentities($msg[dsi_ban_form_nom],ENT_QUOTES, $charset)."</strong><ul>
					</li><li>".htmlentities($msg[dsi_ban_form_com_gestion],ENT_QUOTES, $charset)."
					</li><li>".htmlentities($msg[dsi_ban_form_com_public],ENT_QUOTES, $charset)."
					</li></ul></th>";
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
			$td_javascript=" onmousedown=\"document.location='./dsi.php?categ=bannettes&sub=abo&id_bannette=$bann->id_bannette&suite=modif&id_empr=$id_empr';\" ";
			$bann_list .= "
				<td valign='top' $td_javascript>
					<strong>".htmlentities($bann->nom_bannette,ENT_QUOTES, $charset)."</strong><ul>
					</li><li>".htmlentities($bann->comment_gestion,ENT_QUOTES, $charset)."
					</li><li>".htmlentities($bann->comment_public,ENT_QUOTES, $charset)."
					</li></ul></td>";
			$requete = "select id_equation, num_classement, nom_equation, comment_equation, proprio_equation, num_bannette from equations, bannette_equation where num_equation=id_equation and proprio_equation=$id_empr and num_bannette='$bann->id_bannette' order by nom_equation " ;
			$resequ = mysql_query($requete, $dbh) or die ($requete) ;
			$equ_trouvees =  mysql_num_rows($resequ) ;
			$equations = "" ;
			while ($equa=mysql_fetch_object($resequ)) {
				$eq_form= new equation($equa->id_equation) ;
				$equations .= "<li>".$equa->nom_equation.$eq_form->make_hidden_search_form("","PRI", $id_empr)."</li>";
			}
			$td_javascript=" onmousedown= \"document.modif_requete_form_$eq_form->id_equation.submit();\" ";
			$bann_list .= "<td valign='top' $td_javascript><ul>$equations</ul></td>";
			
			$bann_list .= "<td valign='top'>$bann->nb_notices</td>";
			
			$td_javascript=" onmousedown=\"document.location='./dsi.php?categ=diffuser&sub=lancer';\" ";
			$bann_list .= "<td valign='top' $td_javascript>
					<strong>".htmlentities($bann->aff_date_last_envoi,ENT_QUOTES, $charset)."</strong>";
			if ($bann->alert_diff) $bann_list .= "<br /><font color=red>(".htmlentities($bann->aff_date_last_remplissage,ENT_QUOTES, $charset).")</font>";
				else $bann_list .= "<br />(".htmlentities($bann->aff_date_last_remplissage,ENT_QUOTES, $charset).")" ;
			$bann_list .= "</td>";
			$bann_list .= "</tr>";
			$parity += 1;
			}
		$nav_bar = "" ;
		$dsi_list_tmpl = str_replace("!!cle!!", $form_cb, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!list!!", $bann_list, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $dsi_list_tmpl);
		$dsi_list_tmpl = str_replace("!!message_trouve!!", $msg['dsi_ban_abo_trouvees'], $dsi_list_tmpl);
		return $dsi_list_tmpl;
}
	