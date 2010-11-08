<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_demandes.inc.php,v 1.5 2010-02-09 20:29:27 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


require_once($base_path."/classes/demandes_action.class.php");
require_once($base_path."/includes/templates/demandes.tpl.php");
require_once($base_path."/includes/mail.inc.php");

switch($quoifaire){
	
	case 'show_form':
		show_form($id,$type);	
	break;
	case 'save_ask':
		save_ask($id,$type);	
	break;
	case 'add_note':
		add_note();
		break;
	case 'save_note':
		save_note($id_action,$id_note,$id_demande);
		break;
}

/*
 * Affiche le formulaire d'ajout d'un action
 */
function show_form($id,$type){
	global $dbh, $msg; 
	
	if($type == 'ask'){
		$title = $msg['demandes_question_form'];
		$btn = $msg['demandes_save_question'];
	} elseif($type == 'info'){
		$title = $msg['demandes_info_form'];
		$btn = $msg['demandes_save_info'];		
	} elseif($type == 'rdv'){
		$title = $msg['demandes_rdv_form'];
		$btn = $msg['demandes_save_rdv'];
		$date = date('Ymd',time());
		$div_date= "
		<div class='row' >
			<label class='etiquette' >".$msg['demandes_action_date_rdv']."</label>
		</div>
		<div class='row'>
			<blockquote>
				<input type='hidden' id='date_rdv' name='date_rdv' value='$date' />
				<input type='button' class='bouton' id='date_rdv_btn' name='date_rdv_btn' value='".formatdate($date)."' onClick=\"window.open('./select.php?what=calendrier&caller=liste_action&date_caller=$date&param1=date_rdv&param2=date_rdv_btn&auto_submit=NO&date_anterieure=YES', 'date_rdv', 'width=250,height=300,toolbar=no,dependent=yes,resizable=yes')\"/>
			</blockquote>
		</div>";	
	}
	$display .= "
		<div class='row'>
			<h3>".$title."</h3>
		</div>";
	if($div_date) $display .= $div_date;
	$display .="
		<div class='row'>
			<label class='etiquette' >".$msg['demandes_action_sujet']."</label>
		</div>
		<div class='row'>
			<blockquote>
			<input type='text' name='sujet' id='sujet' />
			</blockquote>
		</div>
		<div class='row'>
			<label class='etiquette' >".$msg['demandes_action_detail']."</label>
		</div>
		<div class='row'>
			<blockquote>
				<textarea style='vertical-align:top' id='detail' name='detail' cols='50' rows='5'></textarea>
			</blockquote>
		</div>				
		<input type='button' class='bouton' name='ask' id='ask' value='".$btn."' />
		<input type='button' class='bouton' name='cancel' id='cancel' value='".$msg['demandes_cancel']."' />
		";

	ajax_http_send_response($display);
}

/*
 * Enregistrement de la nouvelle action question/réponse
 */
function save_ask($id,$type){
	
	global $dbh, $sujet, $detail, $date_rdv,$id_empr;
	
	$date = date("Y-m-d",time());
	if($type=='ask'){
		$req = "insert into demandes_actions set 
			sujet_action = '".$sujet."',
			detail_action = '".$detail."',
			date_action = '".$date."',
			deadline_action = '".$date."',
			type_action=1,
			statut_action=1,
			num_demande = '".$id."',
			actions_num_user ='".$id_empr."',
			actions_type_user=1,
			actions_read=1	
		";
	} elseif($type=='info'){
		$req = "insert into demandes_actions set 
			sujet_action = '".$sujet."',
			detail_action = '".$detail."',
			date_action = '".$date."',
			deadline_action = '".$date."',
			type_action=3,
			statut_action=1,
			num_demande = '".$id."',
			actions_num_user ='".$id_empr."',
			actions_type_user=1,
			actions_read=1		
		";
	} elseif($type=='rdv'){
		$req = "insert into demandes_actions set 
			sujet_action = '".$sujet."',
			detail_action = '".$detail."',
			date_action = '".$date_rdv."',
			deadline_action = '".$date_rdv."',
			type_action=4,
			statut_action=2,
			num_demande = '".$id."',
			actions_num_user ='".$id_empr."',
			actions_type_user=1	,
			actions_read=1		
		";
	}
	mysql_query($req,$dbh);
	$idaction = mysql_insert_id();
	
	$dmde_act = new demandes_action($id,$idaction);
	$display = $dmde_act->getContenuForm();
		
	ajax_http_send_response($display);
	
}

/*
 * Ajouter une note à une action
 */
function add_note(){
	global $msg,$dbh, $id_action;
	
	$req = "select type_action from demandes_actions where id_action='".$id_action."'";
	$res = mysql_query($req,$dbh);
	$action = mysql_fetch_object($res);
	if($action->type_action == '1'){
		$titre = $msg['demandes_notes_question_form'];
	} else $titre = $msg['demandes_notes_form'];
	$display .= "
		<div class='row'>
			<h3>".$titre."</h3>
		</div>
		<div class='row'>
			<label class='etiquette' >".$msg['demandes_notes_contenu']."</label>
		</div>
		<div class='row'>
			<blockquote>
				<textarea style='vertical-align:top' id='contenu' name='contenu' cols='50' rows='5'></textarea>
			</blockquote>
		</div>
		<input type='button' class='bouton' name='save_note' id='save_note' value='".$msg['demandes_notes_save']."' />
		<input type='button' class='bouton' name='cancel' id='cancel' value='".$msg['demandes_cancel']."' />";
		
		
	ajax_http_send_response($display);
}

/*
 * Enregistrer la note
 */
function save_note($idaction, $idnote=0, $id_demande=0){
	
	global $contenu,$dbh, $charset, $id_empr;
	
	$date = date("Y-m-d",time());
	$req = " insert into demandes_notes 
		set contenu='".$contenu."',
		date_note='".$date."',";
	if($idnote) $req .= "num_note_parent='".$idnote."',";
	$req .= " num_action='".$idaction."',";
	$req .= " notes_num_user='".$id_empr."', notes_type_user=1 ";	
	mysql_query($req,$dbh);
	
	$req_up = "update demandes_actions set actions_read=1 where id_action='".$idaction."'";
	mysql_query($req_up,$dbh);
	
	$dmde_act = new demandes_action($id_demande,$idaction);
	$display = $dmde_act->getContenuForm();
	
	$dmde_act->send_alert_by_mail($id_empr,$idnote);
	
	ajax_http_send_response($display);
}
?>