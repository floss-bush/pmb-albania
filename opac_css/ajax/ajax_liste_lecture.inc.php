<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_liste_lecture.inc.php,v 1.5 2010-08-19 07:35:07 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($include_path."/mail.inc.php");

switch($quoifaire){
	
	case 'show_form':
		show_form($id);	
	break;
	
	case 'send_demande':
		send_demande($id);
	break;
	
	case 'show_refus_form':
		show_refus_form();
		break;
	
	case 'delete_empr':
		delete_empr($id,$empr);
	break;
}

/**
 * Formulaire de saisie pour l'envoi d'une demande
 */
function show_form($id){
	global $dbh, $msg, $charset; 
	
	$req = "select id_empr from empr where empr_login='".$_SESSION['user_code']."'";
	$res=mysql_query($req,$dbh);
	$idempr = mysql_result($res,0,0);
	
	$display .= "<div class='row'>
					<font style='color:red'><label class='etiquette'>".htmlentities($msg[list_lecture_mail_inscription],ENT_QUOTES,$charset)."</label></font>
				</div>
				<div class='row'>
					<label class='etiquette' >".htmlentities($msg[list_lecture_demande_inscription],ENT_QUOTES,$charset)."</label>
				</div>
				<div class='row'>
					<blockquote>
						<textarea style='vertical-align:top' id='liste_demande_$id' name='liste_demande_$id' cols='50' rows='5'></textarea>
					</blockquote>
				</div>				
				<input type='button' class='bouton' name='send_mail_$id' id='send_mail_$id' value='$msg[list_lecture_send_mail]' />
				<input type='button' class='bouton' name='cancel_$id' id='cancel_$id' value='$msg[list_lecture_cancel_mail]' />
				<input type='hidden' name='id_empr' id='id_empr' value='$idempr' />
				
				";
	print $display;
}

/*
 * Formulaire de saisie pour un motif de refus
 */
function show_refus_form(){
	global $msg;
	$display .= "
				<div class='row'>
					<label class='etiquette' >".htmlentities($msg[list_lecture_motif_refus],ENT_QUOTES,$charset)."</label>
				</div>
				<div class='row'>
					<blockquote>
						<textarea style='vertical-align:top' id='com' name='com' cols='50' rows='5'></textarea>
					</blockquote>
				</div>				
				<input type='submit' class='bouton' name='refus_dmd_btn' id='refus_dmd_btn' value='$msg[list_lecture_send_refus]' onclick='this.form.lvl.value=\"demande_list\"; this.form.action.value=\"refus_acces\";'/>
				<input type='button' class='bouton' name='cancel' id='cancel' value='$msg[list_lecture_cancel_mail]' />";
	print $display;
}

/**
 * Envoyer un mail de demande d'accès à la liste confidentielle
 */
function send_demande($id_liste){
	global $dbh, $com, $id_empr, $empr_nom, $empr_prenom,$empr_mail, $msg, $pmb_opac_url, $opac_connexion_phrase;
	
	$requete = "replace into  abo_liste_lecture (num_empr,num_liste,commentaire,etat) values ('".$id_empr."','".$id_liste."','".$com."','1')";
	mysql_query($requete,$dbh);
	
	//Coordonnées du diffuseur de la liste
	$req = "select empr_login, empr_mail, concat(empr_prenom,' ',empr_nom) as nom, nom_liste from opac_liste_lecture, empr where num_empr=id_empr and id_liste='".$id_liste."'";
	$res = mysql_query($req,$dbh);
	$diffuseur = mysql_fetch_object($res);
	
	$objet = sprintf($msg['list_lecture_objet_mail'],$diffuseur->nom_liste);
	$date = time();
	$login = $diffuseur->empr_login;
	$code=md5($opac_connexion_phrase.$login.$date);
	$corps = sprintf($msg['list_lecture_intro_mail'],$diffuseur->nom,$sender->nom_liste).", <br />".sprintf($msg['list_lecture_corps_mail'],$empr_prenom." ".$empr_nom,$diffuseur->nom_liste);
	if($com) $corps .= sprintf("<br />".$msg['list_lecture_corps_com_mail'],$empr_prenom." ".$empr_nom,"<br />".$com);
	$corps .= "<br /><br /><a href='".$pmb_opac_url."empr.php?code=$code&emprlogin=$login&date_conex=$date&tab=lecture&lvl=demande_list' >".$msg['list_lecture_activation_mail']."</a>";
	
	mailpmb($diffuseur->nom,$diffuseur->empr_mail,$objet,stripslashes($corps),$empr_prenom." ".$empr_nom,$empr_mail);
}

/*
 * Fonction qui supprime un inscrit à une liste confidentielle
 */
function delete_empr($id_liste,$id_empr){
	global $dbh, $msg, $pmb_opac_url, $opac_connexion_phrase, $empr_nom, $empr_prenom,$empr_mail;
	
	//envoi du mail de désinscription
	$req = "select empr_login, empr_mail, concat(empr_prenom,' ',empr_nom) as nom, nom_liste 
	from abo_liste_lecture abo, opac_liste_lecture, empr 
	where abo.num_empr=id_empr and id_liste=num_liste
	and num_liste='".$id_liste."' and abo.num_empr='".$id_empr."'";
	$res = mysql_query($req,$dbh);
	$inscrit = mysql_fetch_object($res);
	
	$objet = sprintf($msg['list_lecture_objet_unsubscribe_mail'],$inscrit->nom_liste);
	$date = time();
	$login = $inscrit->empr_login;
	$code=md5($opac_connexion_phrase.$login.$date);
	$corps = sprintf($msg['list_lecture_intro_mail'],$inscrit->nom,$inscrit->nom_liste).", <br />".sprintf($msg['list_lecture_unsubscribe_mail'],$empr_prenom." ".$empr_nom,$inscrit->nom_liste);
	if($com) $corps .= sprintf("<br />".$msg['list_lecture_corps_com_mail'],$empr_prenom." ".$empr_nom,"<br />".$com."<br />");
	$corps .= "<br /><br /><a href='".$pmb_opac_url."empr.php?code=$code&emprlogin=$login&date_conex=$date&tab=lecture&lvl=private_list&sub=my_list' >".$msg['redirection_mail_link']."</a>";
	
	mailpmb($inscrit->nom,$inscrit->empr_mail,$objet,stripslashes($corps),$empr_prenom." ".$empr_nom,$empr_mail);
	
	//désinscripiton
	$requete = "delete from abo_liste_lecture where num_liste='".$id_liste."' and num_empr='".$id_empr."'"; 
	mysql_query($requete,$dbh);
	
	//réaffichage de la liste
	$req = "select id_empr, trim(concat(empr_prenom,' ',empr_nom)) as nom, confidential 
	from empr e, abo_liste_lecture abo, opac_liste_lecture oll 
	where abo.num_empr=e.id_empr and oll.id_liste=abo.num_liste 
	and etat=2 and num_liste='".$id_liste."'
	order by nom";
	$res=mysql_query($req,$dbh);
	if(!mysql_num_rows($res)){
		$display = $msg[list_lecture_no_user_inscrit];
		print $display;
		return;
	}
	$display="";
	while(($empr=mysql_fetch_object($res))){
		if($empr->confidential) $display .= "<img border=0 align='top' src='".$opac_url_base."images/cross.png'  onclick=\"delete_from_liste('".$id_liste."','".$empr->id_empr."');\">";
		$display .= $empr->nom."<br />";
	}
	
	print $display;
	
}
?>