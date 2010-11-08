<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_diff.inc.php,v 1.11 2009-05-16 11:08:24 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function dif_list_bannettes($form_cb="", $id_bannette=0, $id_classement="", $auto=1, $form_action) {

// auto = 0 : bannettes mannuelles
//        1 : bannettes automatiques sans contrôle de date

global $dbh, $msg, $charset;
global $page, $nbr_lignes;
global $dsi_ban_list_diff;


// nombre de références par pages
$nb_per_page = 10;
$form_cb = str_replace("*", "%", $form_cb) ;
		
if ($form_cb) $clause = "WHERE nom_bannette like '$form_cb%' and bannette_auto='$auto' " ;
	else $clause = "WHERE bannette_auto='$auto' " ;
if ($id_classement===0) $clause.= " and num_classement=0 "; 
	elseif ($id_classement>0) $clause.= " and num_classement='$id_classement' " ;

if(!$nbr_lignes) {
	$requete = "SELECT COUNT(1) FROM bannettes $clause ";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);
	}

if($nbr_lignes) {

		// on lance la vraie requête
		$requete = "SELECT id_bannette, proprio_bannette FROM bannettes $clause ORDER BY nom_bannette, id_bannette ";
		$res = mysql_query($requete, $dbh);

		$parity = 0;
		$ban_trouvees =  mysql_num_rows($res) ;

		$bann_list .= "<tr >";
		$bann_list .= "
				<th width='1%'>
					</th>";
		$bann_list .= "
				<th width='60%'>
					<strong>".htmlentities($msg[dsi_ban_form_nom],ENT_QUOTES, $charset)."</strong>
					<br />(".htmlentities($msg[dsi_ban_form_com_public],ENT_QUOTES, $charset).")
					</th>";
		$bann_list .= "
				<th width='20%'>
					<strong>".htmlentities($msg[dsi_ban_date_last_envoi],ENT_QUOTES, $charset)."</strong>
					<br />(".htmlentities($msg[dsi_ban_date_last_remp],ENT_QUOTES, $charset).")
					</th>";
		$bann_list .= "
				<th width='10%'>
					<strong>".htmlentities($msg[dsi_ban_nb_notices],ENT_QUOTES, $charset)."</strong>
					</th>";
		$bann_list .= "</tr>";
		$id_check_list='';
		while(($bann=mysql_fetch_object($res))) {
			$id_check="auto_".$bannette->id_bannette;
			if($id_check_list)$id_check_list.='|';
			$id_check_list.=$id_check;
			if ($parity % 2) $pair_impair = "even";
				else $pair_impair = "odd";
			$bannette = new bannette($bann->id_bannette) ;
			if ($bann->proprio_bannette) $nom_bannette = "<font color=red>".htmlentities($bannette->nom_bannette,ENT_QUOTES, $charset)."</font>" ;
				else $nom_bannette = htmlentities($bannette->nom_bannette,ENT_QUOTES, $charset) ;
			$td_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
			$bann_list .= "<tr class='$pair_impair' $td_javascript >";
			$bann_list .= "
				<td width='1%' align='center'>
					<input type='checkbox' name='liste_bannette[]' id='$id_check' value='$bannette->id_bannette' />
					</td>";
			$bann_list .= "
				<td width='60%'>
					<strong>".$nom_bannette."</strong>
					<br />(".htmlentities($bannette->comment_public,ENT_QUOTES, $charset).")
					</td>";
			$bann_list .= "<td>
					<strong>".htmlentities($bannette->aff_date_last_envoi,ENT_QUOTES, $charset)."</strong>";
			if ($bannette->alert_diff) $bann_list .= "<br /><font color=red>(".htmlentities($bannette->aff_date_last_remplissage,ENT_QUOTES, $charset).")</font>";
				else $bann_list .= "<br />(".htmlentities($bannette->aff_date_last_remplissage,ENT_QUOTES, $charset).")" ;
			$bann_list .= "
				<td width='10%'>
					<strong>".htmlentities($bannette->nb_notices,ENT_QUOTES, $charset)."</strong>
					</td>";
			$bann_list .= "</tr>";
			$parity += 1;
		}
		$bann_list.="<input type='hidden' id='auto_id_list' name='auto_id_list' value='$id_check_list' >";	
		mysql_free_result($res);

		if ($auto==1) $dsi_ban_list_diff = str_replace("!!titre!!", $msg[dsi_diff_ban_auto_found], $dsi_ban_list_diff);
			else $dsi_ban_list_diff = str_replace("!!titre!!", $msg[dsi_diff_ban_manu_found], $dsi_ban_list_diff);
		$dsi_ban_list_diff = str_replace("!!cle!!", $form_cb, $dsi_ban_list_diff);
		$dsi_ban_list_diff = str_replace("!!id_classement!!", $id_classement, $dsi_ban_list_diff);
		$dsi_ban_list_diff = str_replace("!!list!!", $bann_list, $dsi_ban_list_diff);

		$dsi_ban_list_diff = str_replace("!!form_action!!", $form_action, $dsi_ban_list_diff);
		
		return $dsi_ban_list_diff;
		} else return $msg['dsi_no_ban_found'] ;
}

function dif_list_bannettes_full_auto($form_action) {

global $dbh, $msg, $charset;
global $page, $nbr_lignes;
global $dsi_ban_list_diff;

$clause = "WHERE (DATE_ADD(date_last_envoi, INTERVAL periodicite DAY) <= sysdate()) and bannette_auto=1 " ;

if(!$nbr_lignes) {
	$requete = "SELECT COUNT(1) FROM bannettes $clause ";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);
	}

if($nbr_lignes) {

		// on lance la vraie requête
		$requete = "SELECT id_bannette, proprio_bannette FROM bannettes $clause ORDER BY nom_bannette, id_bannette ";
		$res = mysql_query($requete, $dbh);

		$parity = 0;
		$ban_trouvees =  mysql_num_rows($res) ;

		$bann_list .= "<tr >";
		$bann_list .= "
				<th width='1%'>
					</th>";
		$bann_list .= "
				<th width='60%'>
					<strong>".htmlentities($msg[dsi_ban_form_nom],ENT_QUOTES, $charset)."</strong>
					<br />(".htmlentities($msg[dsi_ban_form_com_public],ENT_QUOTES, $charset).")
					</th>";
		$bann_list .= "
				<th width='20%'>
					<strong>".htmlentities($msg[dsi_ban_date_last_envoi],ENT_QUOTES, $charset)."</strong>
					<br />(".htmlentities($msg[dsi_ban_date_last_remp],ENT_QUOTES, $charset).")
					</th>";
		$bann_list .= "
				<th width='10%'>
					<strong>".htmlentities($msg[dsi_ban_nb_notices],ENT_QUOTES, $charset)."</strong>
					</th>";
		$bann_list .= "</tr>";
		$id_check_list='';
		while(($bann=mysql_fetch_object($res))) {
			$id_check="auto_".$bannette->id_bannette;
			if($id_check_list)$id_check_list.='|';
			$id_check_list.=$id_check;
			if ($parity % 2) $pair_impair = "even";
				else $pair_impair = "odd";
			$bannette = new bannette($bann->id_bannette) ;
			if ($bann->proprio_bannette) $nom_bannette = "<font color=red>".htmlentities($bannette->nom_bannette,ENT_QUOTES, $charset)."</font>" ;
				else $nom_bannette = htmlentities($bannette->nom_bannette,ENT_QUOTES, $charset) ;
			$td_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
			$bann_list .= "<tr class='$pair_impair' $td_javascript >";
			$bann_list .= "
				<td width='1%' align='center'>
					<input type='checkbox' name='liste_bannette[]' id='$id_check' checked value='$bannette->id_bannette' />
					</td>";
			$bann_list .= "
				<td width='60%'>
					<strong>".$nom_bannette."</strong>
					<br />(".htmlentities($bannette->comment_public,ENT_QUOTES, $charset).")
					</td>";
			$bann_list .= "
				<td width='20%'>
					<strong>".htmlentities($bannette->aff_date_last_envoi,ENT_QUOTES, $charset)."</strong>
					<br />(".htmlentities($bannette->aff_date_last_remplissage,ENT_QUOTES, $charset).")
					</td>";
			$bann_list .= "
				<td width='10%'>
					<strong>".htmlentities($bannette->nb_notices,ENT_QUOTES, $charset)."</strong>
					</td>";
			$bann_list .= "</tr>";
			$parity += 1;
		}
		$bann_list.="<input type='hidden' id='auto_id_list' name='auto_id_list' value='$id_check_list' >";	
		mysql_free_result($res);

		$dsi_ban_list_diff = str_replace("!!titre!!", $msg[dsi_diff_ban_auto_found], $dsi_ban_list_diff);
		$dsi_ban_list_diff = str_replace("!!cle!!", "", $dsi_ban_list_diff);
		$dsi_ban_list_diff = str_replace("!!id_classement!!", 0, $dsi_ban_list_diff);
		$dsi_ban_list_diff = str_replace("!!list!!", $bann_list, $dsi_ban_list_diff);

		$dsi_ban_list_diff = str_replace("!!form_action!!", $form_action, $dsi_ban_list_diff);
		
		return $dsi_ban_list_diff;
		} else return $msg['dsi_no_automatic_ban_found_ech'] ;
}

