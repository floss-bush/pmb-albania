<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_suggestions.inc.php,v 1.19 2009-11-30 10:39:25 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des suggestions
require_once($class_path.'/entites.class.php');
require_once($class_path.'/suggestions.class.php');
require_once($class_path.'/suggestions_origine.class.php');
require_once($class_path.'/suggestions_map.class.php');
require_once($include_path.'/mail.inc.php');
require_once($include_path.'/explnum.inc.php');
require_once($class_path.'/explnum_doc.class.php');
require_once($class_path.'/z3950_notice.class.php');

//Supprime la suggestion
function sup_sug() {

	global $id_sug;
		
	suggestions::delete($id_sug);
	suggestions_origine::delete($id_sug);	
	
}


//Enregistre la suggestion
function update_sug() {

	global $id_bibli, $id_sug,$id_notice;	
	global $tit, $edi, $aut, $cod, $pri, $com, $date_publi;
	global $statut, $orig, $typ, $url_sug, $sug_src;	
	global $sug_map;
	global $acquisition_sugg_categ, $acquisition_sugg_categ_default;
	global $num_categ;
	global $sugg_location_id;
	global $nombre_expl;
	global $creator_orig_id;
	global $dbh;
	
	if (!$id_sug && suggestions::exists($orig, $tit, $aut, $edi, $cod)) return;
	
	$sug = new suggestions($id_sug);
	$sug->titre = stripslashes($tit);
	$sug->editeur = stripslashes($edi);
	$sug->auteur = stripslashes($aut);
	$sug->code = stripslashes($cod);	
	$sug->num_notice=$id_notice;
	$pri = str_replace(',','.',$pri);
	if (is_numeric($pri)) $sug->prix = $pri; 
	$sug->url_suggestion = stripslashes($url_sug);
	$sug->commentaires = stripslashes($com);
	$sug->nb=$nombre_expl;
	$sug->date_publi = $date_publi;
	$sug->sugg_src = $sug_src; 
		
	$q = "select count(1) from docs_location where idlocation = '".$sugg_location_id."' ";
	$r = mysql_query($q); 
	if ($sugg_location_id && mysql_result($r, 0, 0)) {
		$sug->sugg_location=$sugg_location_id;
	} else {
		$sug->sugg_location=0;
	}	
	
	// chargement de la PJ
	$explnum_doc = "";
	if($_FILES['piece_jointe_sug']['name']){			
		$explnum_doc = new explnum_doc();
		$explnum_doc->load_file($_FILES['piece_jointe_sug']);
		$explnum_doc->analyse_file();
	} 
			
	if (!$id_sug) {

		$sug->statut = $sug_map->getFirstStateId(); 
		$sug->date_creation = today();

		if ($num_categ && suggestions_categ::exists($num_categ)) {
			$sug->num_categ=$num_categ;
		} else {
			$sug->num_categ='1';
		}
		
		$sug->save($explnum_doc);		
		
		$sug_orig = new suggestions_origine($orig, $sug->id_suggestion);
		$sug_orig->type_origine = $typ;
		$sug_orig->save();
		
	} else {
		
		if ($num_categ && suggestions_categ::exists($num_categ)) {
			$sug->num_categ=$num_categ;
		}						
		$sug->save($explnum_doc);
				
		if($creator_orig_id){
			$sug_orig = new suggestions_origine($creator_orig_id, $sug->id_suggestion);
			$sug_orig->type_origine = $typ;
			$sug_orig->save();
		}
		
	}

}


//Fusionne les suggestions cochées
//En cours/Validées
function sug_fusChk(){

	global $dbh;
	global $msg, $charset;
	global $error;
	global $current_module;
	global $chk;
	global $bt_fusVal, $script;
	global $sug_map;
	
	$tab_enc = array();
	$tab_val = array();
	foreach($chk as $key=>$id_sug) {
		$sug = new suggestions($id_sug);
			
		$state_name = $sug_map->getStateNameFromId($sug->statut);
		$merge=$sug_map->getState_MERGE($state_name);
		if ($merge == 'FROM') $tab_enc[] = $sug;
		if ($merge == 'TO') $tab_val[] = $sug;			


	}
	
	$titre = htmlentities($msg['acquisition_sug_fus'].' : '.$msg['acquisition_sug'], ENT_QUOTES, $charset);
	$action ="./acquisition.php?categ=sug&action=fusVal";

	print "	
			<form class='form-$current_module' id='sug_list_form' name='sug_list_form' method='post' action=\"$action\" >
			<h3>$titre</h3>
			<!--    Contenu du form    -->
			<div class='form-contenu'>
				<table width='100%'>
					<tr>
						<th>".htmlentities($msg['acquisition_sug_dat_cre'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['acquisition_sug_tit'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['acquisition_sug_edi'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['acquisition_sug_aut'], ENT_QUOTES, $charset)."</th>
						<th>".htmlentities($msg['acquisition_sug_etat'], ENT_QUOTES, $charset)."</th>	
						<th>&nbsp;</th>
					</tr>";
				
	$parity=1;

	if(count($tab_val) != 0) {	//S'il y a des suggestions validées, on ne peut fusionner qu'avec l'une d'elles.
		
		foreach($tab_val as $key=>$sug) {
						
			$lib_statut = htmlentities($msg['acquisition_sug_val'], ENT_QUOTES, $charset);
			
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" onclick=\"document.getElementById('chk[".$sug->id_suggestion."]').checked = true;\"";
		    print ("<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer' >
						<td><i>".formatdate($sug->date_creation)."</i></td>
						<td><i>".htmlentities($sug->titre, ENT_QUOTES, $charset)."</i></td>
						<td><i>".htmlentities($sug->editeur, ENT_QUOTES, $charset)."</i></td>
						<td><i>".htmlentities($sug->auteur, ENT_QUOTES, $charset)."</i></td>
						<td><i>$lib_statut</i></td>
						<td>
							<input type='hidden' name='sug[]' value='".$sug->id_suggestion."' /> 
							<input type='radio' id='chk[".$sug->id_suggestion."]' name='chk[]' value='".$sug->id_suggestion."' />
						</td>
					</tr>");
		}
		foreach($tab_enc as $key=>$sug) {
						
			$lib_statut = htmlentities($msg['acquisition_sug_enc'], ENT_QUOTES, $charset);
			
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
		    print ("<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer' >
						<td><i>".formatdate($sug->date_creation)."</i></td>
						<td><i>".htmlentities($sug->titre, ENT_QUOTES, $charset)."</i></td>
						<td><i>".htmlentities($sug->editeur, ENT_QUOTES, $charset)."</i></td>
						<td><i>".htmlentities($sug->auteur, ENT_QUOTES, $charset)."</i></td>
						<td><i>$lib_statut</i></td>
						<td>
							<input type='hidden' name='sug[]' value='".$sug->id_suggestion."' /> 
						</td>
					</tr>");
		}

		
	} else {	//Sinon on peut fusionner avec n'importe quelle suggestion.

		foreach($tab_val as $key=>$sug) {
						
			$lib_statut = htmlentities($msg['acquisition_sug_val'], ENT_QUOTES, $charset);
			
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" onclick=\"document.getElementById('chk[".$sug->id_suggestion."]').checked = true;\"";
		    print ("<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer' >
						<td><i>".formatdate($sug->date_creation)."</i></td>
						<td><i>".htmlentities($sug->titre, ENT_QUOTES, $charset)."</i></td>
						<td><i>".htmlentities($sug->editeur, ENT_QUOTES, $charset)."</i></td>
						<td><i>".htmlentities($sug->auteur, ENT_QUOTES, $charset)."</i></td>
						<td><i>$lib_statut</i></td>
						<td>
							<input type='hidden' name='sug[]' value='".$sug->id_suggestion."' /> 
							<input type='radio' id='chk[".$sug->id_suggestion."]' name='chk[]' value='".$sug->id_suggestion."' />
						</td>
					</tr>");
		}
		foreach($tab_enc as $key=>$sug) {
						
			$lib_statut = htmlentities($msg['acquisition_sug_enc'], ENT_QUOTES, $charset);
			
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" onclick=\"document.getElementById('chk[".$sug->id_suggestion."]').checked = true;\"";
		    print ("<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer' >
						<td><i>".formatdate($sug->date_creation)."</i></td>
						<td><i>".htmlentities($sug->titre, ENT_QUOTES, $charset)."</i></td>
						<td><i>".htmlentities($sug->editeur, ENT_QUOTES, $charset)."</i></td>
						<td><i>".htmlentities($sug->auteur, ENT_QUOTES, $charset)."</i></td>
						<td><i>$lib_statut</i></td>
						<td>
							<input type='hidden' name='sug[]' value='".$sug->id_suggestion."' /> 
							<input type='radio' id='chk[".$sug->id_suggestion."]' name='chk[]' value='".$sug->id_suggestion."' />
						</td>
					</tr>");
		}
		
	}
	
	print "		</table>
			</div>
			<div class='row'>
				<div class='left'>
					<input type='button' class='bouton' value='$msg[76]' onClick=\"history.go(-1);\" />
				</div>
				<div class='right'>".$bt_fusVal."</div>
			</div>
			<div class='row'></div>
			</div>
			</form>";
	print $script;			
			
}


//Valide la fusion de suggestions
function sug_fusVal(){
	
	global $dbh; 
	global $msg, $charset;
	global $chk, $sug;

	$fus = new suggestions($chk[0]);
	$q = suggestions_origine::listOccurences($chk[0], 1);
	$tab_orig = mysql_query($q, $dbh);
	$row_orig = mysql_fetch_object($tab_orig);
	$orig = $row_orig->origine;
	
	foreach($sug as $key=>$id_sug) {
		if ($id_sug != $chk[0]){
			suggestions::delete($id_sug);
			suggestions_origine::fusionne($orig, $id_sug, $chk[0]);
		}
	}
	
}


//Recuperation du statut session d'affichage des suggestions
function getSessionSugState() {
	global $deflt3sug_statut;
	if (!$_SESSION['sug_statut'] && $deflt3sug_statut) {
		$_SESSION['sug_statut']=$deflt3sug_statut;
	}
	return $_SESSION['sug_statut'];
}
//Definition du statut session d'affichage des suggestions
function setSessionSugState($statut) {
	$_SESSION['sug_statut']=$statut;
	return;
}

//Catalogue la notice à partir du blob unimarc
function save_unimarc_notice(){
	global $msg, $idbibli, $id_sug, $dbh;
	
	$req_uni = "select notice_unimarc from suggestions where id_suggestion='".$id_sug."'";
	$res = mysql_query($req_uni,$dbh);
	if(mysql_num_rows($res)){
		$notice_uni = mysql_result($res,0,0);
	}
	$z=new z3950_notice("form");
	if($notice_uni) $z->notice = $notice_uni;
	$ret=$z->insert_in_database();
	if ($ret[0]) {
		if($z->bull_id && $z->perio_id){
			$notice_display=new serial_display($ret[1],6);
		} else $notice_display=new mono_display($ret[1],6);
		$retour = "
		<script src='javascript/tablist.js'></script>
		<br /><div class='erreur'></div>
		<div class='row'>
			<div class='colonne10'>
				<img src='./images/error.gif' align='left'>
			</div>
			<div class='colonne80'>
				<strong>".$msg["z3950_integr_not_ok"]."</strong>
				".$notice_display->result."
			</div>
		</div>
		<div class='row'>";
		
		if($z->bull_id && $z->perio_id)
			$url_view = "catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$z->bull_id&art_to_show=$ret[1]";
		else $url_view = "catalog.php?categ=isbd&id=".$ret[1];
		$retour .= "
		<form class='form-$current_module' name='dummy' >
			<input type='hidden' name='page' value='".htmlentities($page,ENT_QUOTES,$charset)."'/>	
			<input type='button' name='ok' class='bouton' value='".$msg["bt_retour"]."'  onClick=\"document.location='acquisition.php?categ=sug&action=modif&id_bibli=$idbibli&id_sug=$id_sug'\" />&nbsp;
			<input type='button' name='cancel' class='bouton' value='".$msg["z3950_integr_not_lavoir"]."' onClick=\"document.location='$url_view'\"/>
		</form>
		<script type='text/javascript'>
			document.forms['dummy'].elements['ok'].focus();
		</script>
		</div>
		";
		print $retour;
		
		//On attache la notice à la suggestion
		$req = " update suggestions set num_notice='".$ret[1]."' where id_suggestion='".$id_sug."'";
		mysql_query($req,$dbh);
		
	} else if ($ret[1]){
		if($z->bull_id && $z->perio_id){
			$notice_display=new serial_display($ret[1],6);
		} else $notice_display=new mono_display($ret[1],6);
		$retour = "
		<script src='javascript/tablist.js'></script>
		<br /><div class='erreur'>$msg[540]</div>
		<div class='row'>
			<div class='colonne10'>
				<img src='./images/error.gif' align='left'>
			</div>
			<div class='colonne80'>
				<strong>".($msg["z3950_integr_not_existait"])."</strong><br /><br />
				".$notice_display->result."
			</div>
		</div>
		<div class='row'>";
		if($z->bull_id && $z->perio_id)
			$url_view = "catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$z->bull_id&art_to_show=$ret[1]";
		else $url_view = "catalog.php?categ=isbd&id=".$ret[1];
		$retour .= "
		<form class='form-$current_module' name='dummy'>
			<input type='hidden' name='page' value='".htmlentities($page,ENT_QUOTES,$charset)."'/>	
			<input type='button' name='ok' class='bouton' value='".$msg["bt_retour"]."' onClick=\"document.location='acquisition.php?categ=sug&action=modif&id_bibli=$idbibli&id_sug=$id_sug'\" />&nbsp;
			<input type='button' name='cancel' class='bouton' value='".$msg["z3950_integr_not_lavoir"]."' onClick=\"document.location='$url_view'\"/>
		</form>
		<script type='text/javascript'>
			document.forms['dummy'].elements['ok'].focus();
		</script>
		</div>
		";
		print $retour;
		
		//On attache la notice à la suggestion
		$req = " update suggestions set num_notice='".$ret[1]."' where id_suggestion='".$id_sug."'";
		mysql_query($req,$dbh);
	}
	else {
		$retour = "<script src='javascript/tablist.js'></script>";
		$retour .= form_error_message($msg["connecteurs_cant_integrate_title"], ($ret[1]?$msg["z3950_integr_not_existait"]:$msg["z3950_integr_not_newrate"]), $msg["connecteurs_back_to_list"], "catalog.php?categ=search&mode=7&sub=launch",array("serialized_search"=>$sc->serialize_search()));
		print $retour;
	}
}

/*
 * Formulaire de validation de la suppression de notice
 */
function catalog_notice_form(){
	global $msg, $chk, $statut;
	
	$display = "
	<form class='form-$current_module' name='cat_noti'  method='post' action='./acquisition.php?categ=sug&action=list&statut=$statut'>
		<h3>".$msg["acquisition_catalog_notice_associee"]."</h3>
		<div class='form-contenu'>
			<div class='row'>
				<div>
					<img src='./images/error.gif'  >
					<strong>".$msg["acquisition_catalog_notice_ask"]."</strong>
				</div>
			</div>
		</div>
		<div></div>
		<div class='row'>
			<input type='hidden' name='catnoti' id='catnoti'>";
			if($chk) 
				$display .= "<input type='hidden' name='chk' value='".implode(',',$chk)."' />";
			$display .= 
				"<input type='submit' name='non_btn' class='bouton' value='$msg[39]' onclick='this.form.catnoti.value=\"0\";'/>
				<input type='submit' class='bouton' name='ok_btn' value='$msg[40]' onclick='this.form.catnoti.value=\"1\";'/>					
		</div>
		
	</form>		
	";
				
	print $display;
}
?>

