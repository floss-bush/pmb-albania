<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa_planning_func.inc.php,v 1.15 2011-04-18 10:30:19 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($include_path."/mail.inc.php") ;


function planning_list () {

global $dbh ;
global $msg, $charset;
global $montrerquoi, $f_loc ;
global $current_module ;
global $pdflettreresa_priorite_email_manuel;

if (!$montrerquoi) $montrerquoi='all' ;
$url_gestion = "./circ.php?categ=resa_planning";

$aff_final = "<form class='form-".$current_module."' name='check_resa_planning' action='".$url_gestion."' method='post' ><div class='left' >" ;
$aff_final.= "<input type='hidden' name='resa_action' value='' />";  
$aff_final.= "<span class='usercheckbox'><input type='radio' name='montrerquoi' value='all' id='all' onclick='this.form.submit();' ";
if ($montrerquoi=='all') {
	$aff_final .= "checked" ;
	$clause = "";
}
$aff_final .= "><label for='all'>".htmlentities($msg['resa_show_all'], ENT_QUOTES, $charset)."</label></span>&nbsp;<span class='usercheckbox'><input type='radio' name='montrerquoi' value='validees' id='validees' onclick='this.form.submit();' ";

if ($montrerquoi=='validees') {
	$aff_final .= "checked" ;
	$clause = "and resa_validee='1' ";
}
$aff_final .= "><label for='validees'>".htmlentities($msg['resa_show_validees'], ENT_QUOTES, $charset)."</label></span>&nbsp;<span class='usercheckbox'><input type='radio' name='montrerquoi' value='invalidees' id='invalidees' onclick='this.form.submit();' ";

if ($montrerquoi=='invalidees') {
	$aff_final .= "checked" ;
	$clause = "and resa_validee='0' ";
}
$aff_final.= "><label for='invalidees'>".htmlentities($msg['resa_show_invalidees'], ENT_QUOTES, $charset)."</label></span>&nbsp;<span class='usercheckbox'><input type='radio' name='montrerquoi' value='valid_noconf' id='valid_noconf' onclick='this.form.submit();' ";

if ($montrerquoi=='valid_noconf') {
	$aff_final .= "checked" ;
	$clause = "and resa_validee='1' and resa_confirmee='0' ";
}
$aff_final .= "><label for='valid_noconf'>".htmlentities($msg['resa_show_non_confirmees'], ENT_QUOTES, $charset)."</label></span></div>";

//la liste de sélection de la localisation
$aff_final .= "<div class='row'>".$msg["transferts_circ_resa_lib_localisation"];
$aff_final .= "<select name='f_loc' onchange='document.check_resa_planning.submit();'>";
$res = mysql_query("SELECT idlocation, location_libelle, count(*) as nb FROM docs_location join empr on empr_location=idlocation join resa_planning on resa_idempr = id_empr group by idlocation, location_libelle order by location_libelle ");
$aff_final .= "<option value='0'>".$msg["all_location"]."</option>";
//on parcours la liste des options
while ($value = mysql_fetch_array($res)) {
	//debut de l'option
	$aff_final .= "<option value='".$value[0]."'";
	if ($value[0]==$f_loc)
		//c'est l'option par défaut
		$aff_final .= " selected";
	
	//fin de l'option
	$aff_final .= ">".$value[1]." (".$value[2].")</option>";
}
$aff_final .= "</select>";
if ($f_loc) {
	$clause .= " AND empr_location='".$f_loc."' ";
}

$aff_final .= "</div><div class='row'>&nbsp;</div>" ;


$q = "select id_resa, resa_idnotice, resa_date, resa_date_debut, resa_date_fin, resa_validee, resa_confirmee, resa_idempr, ";
$q.= "trim(notices.tit1) as tit, ";
$q.= "concat(lower(empr_prenom), ' ', upper(empr_nom)) as empr_nom, id_empr, empr_cb, ";
$q.= "IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, if(resa_date_fin='0000-00-00', '', date_format(resa_date_fin, '".$msg["format_date"]."')) as aff_resa_date_fin, date_format(resa_date, '".$msg["format_date"]."') as aff_resa_date " ;
$q.= "FROM resa_planning LEFT JOIN notices ON resa_idnotice = notices.notice_id, empr ";
$q.= "where resa_idempr = id_empr ";
$q.= $clause;
$q.= "order by tit, resa_idnotice, resa_date ";
$r = mysql_query($q) or die("Erreur SQL !<br />".$q."<br />".mysql_error()); 


$aff_final .= "	<table width='100%'>
					<tr>
						<th>".htmlentities($msg['233'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['empr_nom_prenom'], ENT_QUOTES, $charset)."</th>
 						<th>".htmlentities($msg['374'], ENT_QUOTES, $charset)."</th>
 						<th>".htmlentities($msg['resa_planning_date_debut'], ENT_QUOTES, $charset)."</th>
 						<th>".htmlentities($msg['resa_planning_date_fin'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['resa_validee'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['resa_confirmee'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['resa_selectionner'], ENT_QUOTES, $charset)."</th>
					</tr>";
$odd_even=0;

while ($data = mysql_fetch_object($r)) {

	if ($odd_even==0) {
		$aff_final .= "\n<tr class='odd' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='odd'\">";
		$odd_even=1;
	} else if ($odd_even==1) {
		$aff_final .= "\n<tr class='even' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='even'\">";
		$odd_even=0;
	}

	$link = "<a href='./catalog.php?categ=isbd&id=".$data->resa_idnotice."'>".htmlentities($data->tit, ENT_QUOTES, $charset)."</a>";
	$aff_final.= "<td><b>".$link."</b></td>";    
	if (SESSrights & CIRCULATION_AUTH) $aff_final .= "<td><a href=\"./circ.php?categ=pret&form_cb=".rawurlencode($data->empr_cb)."\">".htmlentities($data->empr_nom,  ENT_QUOTES, $charset)."</a></td>"; 
		else $aff_final .= "<td>".htmlentities($data->empr_nom, ENT_QUOTES, $charset)."</td>";
	$aff_final.= "<input type='hidden' id='id_empr[".$data->id_resa."]' name='id_empr[".$data->id_resa."]' value='".$data->id_empr."' />";
	$aff_final.= "<td>".$data->aff_resa_date."</td>"; 
	if($data->resa_validee) {
		$aff_final.= "<td>".$data->aff_resa_date_debut."</td>";
		$aff_final.= "<td>".$data->aff_resa_date_fin." </td>";
		$aff_final.= "<td><b>X</b></td>";
	} else {
		$aff_final .= "<td><input type='text' id='resa_date_debut[".$data->id_resa."]' name='resa_date_debut[".$data->id_resa."]' class='saisie-10em' value='".$data->aff_resa_date_debut."' /></td>";
		$aff_final .= "<td><input type='text' id= 'resa_date_fin[".$data->id_resa."]' name='resa_date_fin[".$data->id_resa."]' class='saisie-10em' value='".$data->aff_resa_date_fin."' /></td>";
		$aff_final.= "<td></td>";
	}
	if($data->resa_confirmee) $aff_final.= "<td><b>X</b></td>";
		else $aff_final.= "<td></td>";
	$aff_final .= "\n<td style='text-align:center;'><input type='checkbox' id='resa_check[".$data->id_resa."]' name='resa_check[]' value='".$data->id_resa."' /></td>" ;
	$aff_final.= "</tr>";

}
$aff_final.= "</table>";

$aff_final .= "	<div class='right'>
					<input type='button' id='bt_chk' class='bouton' value='".$msg['resa_tout_cocher']."' onClick=\"checkAll('check_resa_planning', 'resa_check', check); return false;\" />
				</div>
				<div class='row'>&nbsp;</div>
				<div class='left' >
					<input type='button' class='bouton' value='".$msg['77']."' onclick=\"this.form.resa_action.value='enr_resa'; this.form.submit();\"/>&nbsp;
					<input type='button' class='bouton' value='".$msg['acquisition_sug_bt_val']."' onclick=\"this.form.resa_action.value='val_resa'; this.form.submit();\"/>&nbsp;
					<input type='button' class='bouton' value='".$msg['resa_impression_confirmation']."' onclick=\"this.form.resa_action.value='conf_resa'; this.form.submit();\"/>&nbsp;
				</div>
				<div class='right' >
					<input type='button' class='bouton' value='".$msg['resa_valider_suppression']."'  onclick=\"this.form.resa_action.value='suppr_resa'; this.form.submit();\" />						
				</div>
				<div class='row'></div>
			</form>" ;


$aff_final.= "
<script type='text/javascript'>
	var check = true;

	//Coche et décoche les éléments de la liste
	function checkAll(the_form, the_objet, do_check) {
	
		var elts = document.forms[the_form].elements[the_objet+'[]'] ;
		var elts_cnt  = (typeof(elts.length) != 'undefined')
	              ? elts.length
	              : 0;
	
		if (elts_cnt) {
			for (var i = 0; i < elts_cnt; i++) {
				elts[i].checked = do_check;
			} 
		} else {
			elts.checked = do_check;
		}
		if (check == true) {
			check = false;
			document.getElementById('bt_chk').value = '".$msg['acquisition_sug_uncheckAll']."';
		} else {
			check = true;
			document.getElementById('bt_chk').value = '".$msg['acquisition_sug_checkAll']."';	
		}
		return true;
	}
</script>";


return $aff_final;


}

//
function empr_planning_list($idempr=0) {

global $dbh ;
global $msg, $charset;

$q = "select id_resa, resa_idnotice, resa_date, resa_date_debut, resa_date_fin, resa_validee, resa_confirmee, resa_idempr, ";
$q.= "trim(notices.tit1) as tit, ";
$q.= "concat(lower(empr_prenom), ' ', upper(empr_nom)) as empr_nom, id_empr, empr_cb, ";
$q.= "IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, if(resa_date_fin='0000-00-00', '', date_format(resa_date_fin, '".$msg["format_date"]."')) as aff_resa_date_fin, date_format(resa_date, '".$msg["format_date"]."') as aff_resa_date " ;
$q.= "FROM resa_planning LEFT JOIN notices ON resa_idnotice = notices.notice_id, empr ";
$q.= "where resa_idempr = id_empr ";
$q.= "and id_empr = '".$idempr."' ";
$q.= "order by tit, resa_idnotice, resa_date ";
$r = mysql_query($q) or die("Erreur SQL !<br />".$q."<br />".mysql_error()); 

$aff_final .= "	<table width='100%'>
					<tr>
						<th>".htmlentities($msg['233'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['empr_nom_prenom'], ENT_QUOTES, $charset)."</th>
 						<th>".htmlentities($msg['374'], ENT_QUOTES, $charset)."</th>
 						<th>".htmlentities($msg['resa_planning_date_debut'], ENT_QUOTES, $charset)."</th>
 						<th>".htmlentities($msg['resa_planning_date_fin'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['resa_validee'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['resa_confirmee'], ENT_QUOTES, $charset)."</th>
						<th>&nbsp;</th>
					</tr>";
$odd_even=0;

while ($data = mysql_fetch_object($r)) {

	if ($odd_even==0) {
		$aff_final .= "\n<tr class='odd' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='odd'\">";
		$odd_even=1;
	} else if ($odd_even==1) {
		$aff_final .= "\n<tr class='even' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='even'\">";
		$odd_even=0;
	}

	$link = "<a href='./catalog.php?categ=isbd&id=".$data->resa_idnotice."'>".htmlentities($data->tit, ENT_QUOTES, $charset)."</a>";
	$aff_final.= "<td><b>".$link."</b></td>";    
	if (SESSrights & CIRCULATION_AUTH) $aff_final .= "<td><a href=\"./circ.php?categ=pret&form_cb=".rawurlencode($data->empr_cb)."\">".htmlentities($data->empr_nom,  ENT_QUOTES, $charset)."</a></td>"; 
		else $aff_final .= "<td>".htmlentities($data->empr_nom, ENT_QUOTES, $charset)."</td>";
	$aff_final.= "<td>".$data->aff_resa_date."</td>"; 
	$aff_final.= "<td>".$data->aff_resa_date_debut."</td>";
	$aff_final.= "<td>".$data->aff_resa_date_fin." </td>";
	if($data->resa_validee) $aff_final.= "<td><b>X</b></td>";
		else $aff_final.= "<td></td>";
	if($data->resa_confirmee) $aff_final.= "<td><b>X</b></td>";
		else $aff_final.= "<td></td>";
	$aff_final .= "\n<td ><input type='button' id='resa_supp' name='resa_supp' class='bouton' value='X' onclick=\"document.location='./circ.php?categ=pret&sub=suppr_resa_planning_from_fiche&action=suppr_resa&id_resa=".$data->id_resa."&id_empr=".$idempr."';\" /></td>" ;
	$aff_final.= "</tr>";

}
$aff_final.= "</table>";
$aff_final.= "<div class='row'></div><hr /><div class='row'></div>";

return $aff_final ;
}


//Affichage entete réservation avec verification numero lecteur
function aff_entete($id_empr=0) {
	
	global $msg,$dbh, $layout_begin;
	
	if (!$id_empr) {
		// pas d'id empr, quelque chose ne va pas
		error_message($msg[350], $msg[54], 1 , './circ.php');
		break;
	} else {
		// récupération nom emprunteur
		$requete = "SELECT empr_nom, empr_prenom, empr_cb FROM empr WHERE id_empr='".$id_empr."' LIMIT 1";
		$result = @mysql_query($requete, $dbh);
		if(!mysql_num_rows($result)) {
			// pas d'emprunteur correspondant, quelque chose ne va pas
			error_message($msg[350], $msg[54], 1 , './circ.php');
			break;
		} else {
			$empr = mysql_fetch_object($result);
			$name = $empr->empr_prenom;
			$name ? $name .= ' '.$empr->empr_nom : $name = $empr->empr_nom;
			//echo window_title($database_window_title.$name.$msg[1003].$msg[352]);
			$layout_begin = str_replace('!!nom_lecteur!!', $name, $layout_begin);
			$layout_begin = str_replace('!!cb_lecteur!!', $empr->empr_cb, $layout_begin);
			return $layout_begin;
		}
	}
}

//Affichage des réservations planifiées sur le document courant par le lecteur courant
function doc_planning_list($id_empr, $id_notice) {

	global $msg, $dbh;

	$requete3 = "SELECT id_resa, resa_idempr, resa_idnotice, resa_date, resa_date_debut, resa_date_fin, resa_validee, IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_fin, '".$msg["format_date_sql"]."') as aff_date_fin ";
	$requete3.= "FROM resa_planning ";
	$requete3.= "WHERE resa_idempr='".$id_empr."' and resa_idnotice='".$id_notice."' ";
	$result3 = mysql_query($requete3, $dbh);
	
	if (mysql_num_rows($result3)) $message_resa = '<br /><b>'.$msg['resa_planning_enc'].'</b>'; 
	while ($resa = mysql_fetch_array($result3)) {
		$id_resa = $resa['id_resa'];
		$resa_idempr = $resa['resa_idempr'];
		$resa_idnotice = $resa['resa_idnotice'];
		$resa_idbulletin = $resa['resa_idbulletin'];
		$resa_date = $resa['resa_date'];
		$resa_date_debut = $resa['resa_date_debut'];
		$resa_date_fin = $resa['resa_date_fin'];
		$resa_validee = $resa['resa_validee'];
		$message_resa.= "<blockquote><b>".$msg['resa_planning_date_debut']."</b> ".formatdate($resa_date_debut)."&nbsp;<b>".$msg['resa_planning_date_fin']."</b> ".formatdate($resa_date_fin)."&nbsp;" ;
		if (!$resa['perimee']) {
			if ($resa['resa_validee'])  $message_resa.= " ".$msg['resa_validee'] ;
				else $message_resa.= " ".$msg['resa_attente_validation']." " ;
		} else  $message_resa.= " ".$msg['resa_overtime']." " ;
		$message_resa.= "</blockquote>" ;
	}

	return $message_resa;

}

function alert_empr_resa_planning($id_resa=0, $id_empr_concerne=0) {

	global $dbh;
	global $msg, $charset;
	global $PMBuserid, $PMBuseremail, $PMBuseremailbcc ;
	global $pdflettreresa_priorite_email ;
	global $pdflettreresa_before_list , $pdflettreresa_madame_monsieur, $pdflettreresa_after_list, $pdflettreresa_fdp;
	global $biblio_name, $biblio_email ;
	global $biblio_adr1, $biblio_adr2, $biblio_cp, $biblio_town, $biblio_phone ; 
	global $pdflettreresa_priorite_email_manuel;
	
	if ($pdflettreresa_priorite_email_manuel==3) return ;
	
	$query = "select distinct "; 
	$query.= "trim(notices.tit1) as tit, ";  
	$query.= "date_format(resa_date_fin, '".$msg["format_date"]."') as aff_resa_date_fin, ";
	$query.= "date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, ";
	$query.= "empr_prenom, empr_nom, empr_cb, empr_mail ";  
	$query.= "from resa_planning LEFT JOIN notices ON resa_idnotice = notices.notice_id, empr ";
	$query.= "where id_resa in (".$id_resa.") and resa_idempr=id_empr ";
	if ($id_empr_concerne) $query .= "and id_empr=$id_empr_concerne ";

	$result = mysql_query($query, $dbh);
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=".$charset."\n";

	$var = "pdflettreresa_fdp";
	eval ("\$pdflettreresa_fdp=\"".$$var."\";");
	
	// le texte après la liste des ouvrages en résa
	$var = "pdflettreresa_after_list";
	eval ("\$pdflettreresa_after_list=\"".$$var."\";");
		
	// le texte avant la liste des ouvrages en réservation
	$var = "pdflettreresa_before_list";
	eval ("\$pdflettreresa_before_list=\"".$$var."\";");
	
	// le "Madame, Monsieur," ou tout autre truc du genre "Cher adhérent,"
	$var = "pdflettreresa_madame_monsieur";
	eval ("\$pdflettreresa_madame_monsieur=\"".$$var."\";");

	while ($empr=mysql_fetch_object($result)) {
		$id_empr = $empr->id_empr ;
		if (($pdflettreresa_priorite_email_manuel==1 || $pdflettreresa_priorite_email_manuel==2) && $empr->empr_mail) {
			$to = $empr->empr_prenom." ".$empr->empr_nom." <".$empr->empr_mail.">";
			$output_final = "<html><body>" ;
			$pdflettreresa_madame_monsieur=str_replace("!!empr_first_name!!", $empr->empr_prenom,$pdflettreresa_madame_monsieur);
			$output_final .= $pdflettreresa_madame_monsieur.' <br />'.$pdflettreresa_before_list ;
			$output_final .= '<hr /><strong>'.$empr->tit.'</strong>';
			$output_final .= '<br />' ;
			$output_final .= $msg['resa_planning_date_debut'].'  '.$empr->aff_resa_date_debut.'  '.$msg['resa_planning_date_fin'].'  '.$empr->aff_resa_date_fin ;
			
			$output_final .= '<hr />'.$pdflettreresa_after_list.' <br />'.$pdflettreresa_fdp."<br /><br />".mail_bloc_adresse() ;
			$output_final .= '</body></html>';
			$res_envoi=mailpmb($empr->empr_prenom.' '.$empr->empr_nom, $empr->empr_mail,$msg['mail_obj_resa_validee'],$output_final,$biblio_name, $biblio_email, $headers, "", $PMBuseremailbcc);
			if (!$res_envoi || $pdflettreresa_priorite_email_manuel==2) {
				print "<script type='text/javascript'>openPopUp('./pdf.php?pdfdoc=lettre_resa_planning&id_resa=$id_resa', 'lettre_confirm_resa".$id_resa."', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');</script>";
			}
		} elseif ($pdflettreresa_priorite_email_manuel!=3) {
			print "<script type='text/javascript'>openPopUp('./pdf.php?pdfdoc=lettre_resa_planning&id_resa=$id_resa', 'lettre_confirm_resa".$id_resa."', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');</script>";
		}
		$rqt_maj = "update resa_planning set resa_confirmee=1 where id_resa in (".$id_resa.") " ;
		if ($id_empr_concerne) $rqt_maj .= " and resa_idempr=$id_empr_concerne ";
		mysql_query($rqt_maj, $dbh);
	} // end while

}


?>
