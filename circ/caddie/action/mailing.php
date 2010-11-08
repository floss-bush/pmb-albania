<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mailing.php,v 1.15 2009-10-02 10:02:29 kantin Exp $

// définition du minimum nécéssaire
$base_path="../../..";
$base_auth = "CIRCULATION_AUTH";
$base_title = "";
require_once ("$base_path/includes/init.inc.php");

// les requis par mailing.php ou ses sous modules
include_once("$include_path/mail.inc.php") ;
include_once("$include_path/mailing.inc.php") ;

$urlbase="./circ/caddie/";
if (!$idemprcaddie) die();

if ($pmb_javascript_office_editor) {
	print $pmb_javascript_office_editor ;
	}

if (!$f_message && !$pmb_javascript_office_editor) {
	$f_message="
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">
</head>
<body>
</body>
</html>";
} else $f_message=stripslashes($f_message);
$f_objet_mail = stripslashes($f_objet_mail);

print "<div id='contenu-frame'>" ;

switch ($sub) {
	case "redige" :
		echo "<br />
				<form class='form-$current_module' method='post' name='form_message' id='form_message' action='./mailing.php' />
				<h3>$msg[empr_mailing_titre_form]</h3>
				<div class='form-contenu'>
					<div class='row'>
						<label class='etiquette' for='f_objet_mail'>$msg[empr_mailing_form_obj_mail]</label>
						<div class='row'>
							<input type='text' class='saisie-80em' id='f_objet_mail'  name='f_objet_mail' value=\"".htmlentities(stripslashes($f_objet_mail),ENT_QUOTES,$charset)."\" />
							</div>
						</div>

					<div class='row'>
						<label class='etiquette' for='f_message'>$msg[empr_mailing_form_message]</label>
						<div class='row'>
							<textarea id='f_message' name='f_message' cols='100' rows='20'>".htmlentities(stripslashes($f_message),ENT_QUOTES,$charset)."</textarea>
							</div>
						</div>

					<div class='row'></div>
					</div>
					<div class='row'>
						<div class='left'>";
		if (!$pmb_javascript_office_editor) echo "<input type='button' class='bouton' value=\" ".$msg[empr_mailing_bt_visualiser]." \" onClick=\"document.getElementById('form_message').action='visu_message.php'; document.getElementById('form_message').target='visu_message'; document.getElementById('form_message').submit(); \" />";
		echo "					</div>
						<div class='right'>
							<input type='button' class='bouton' value=\" ".$msg[empr_mailing_bt_envoyer]." \" onClick=\"document.getElementById('form_message').action='mailing.php'; document.getElementById('form_message').target='_self'; document.getElementById('form_message').submit(); \" />
							<input type='hidden' name='sub' value='envoi' />
							<input type='hidden' name='idemprcaddie' value='$idemprcaddie' />
							</div>
						</div>
				<div class='row'></div>
				</form>";

		if (!$pmb_javascript_office_editor)	echo "<div class='row'>
					<label class='etiquette'>$msg[empr_mailing_form_obj_mail]</label>
					<div class='row'>
						".htmlentities(stripslashes($f_objet_mail),ENT_QUOTES,$charset)."
						</div>
					</div>
				<div class='row'>
					<label class='etiquette'>$msg[empr_mailing_form_message]</label>
					<div class='row'>
						<center><iframe id='visu_message' name='visu_message' frameborder='2' scrolling='yes' width='80%' height='700' src='./visu_message.php'></iframe>
						</center>
						</div>
					</div>
			";
		break;
	case "envoi" :
		$f_message=stripslashes($f_message);
		$f_objet_mail=stripslashes($f_objet_mail);
		// ajouter les tags <html> si besoin :
		if (strpos("<html>",substr($f_message,0,20))===false) $f_message="<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\"></head><body>$f_message</body></html>";
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1";
		// peut-être à paramétrer un jour ? 
		$paquet_envoi = 20;
		if ($total_envoyes=="") $total_envoyes=0;
		if ($total=="") {
			$sql = "select 1 from empr_caddie_content where (flag='' or flag is null or flag=2) and empr_caddie_id=$idemprcaddie ";
			$sql_result = mysql_query($sql) or die ("Couldn't select count(*) mailing table $sql");
			$total=mysql_num_rows($sql_result);
		}

		$sql = "select *, date_format(empr_date_adhesion, '".$msg["format_date"]."') as aff_empr_date_adhesion, date_format(empr_date_expiration, '".$msg["format_date"]."') as aff_empr_date_expiration from empr, empr_caddie_content where (flag='' or flag is null) and empr_caddie_id=$idemprcaddie and object_id=id_empr limit 0,$paquet_envoi ";
		$sql_result = mysql_query($sql) or die ("Couldn't select empr table !");
		$n_envoi=mysql_num_rows($sql_result);
		$ienvoi=0;
		$envoi_KO=0;
		
		while ($ienvoi<$n_envoi) {
			$destinataire=mysql_fetch_object($sql_result);
			$iddest=$destinataire->id_empr;
			$emaildest=$destinataire->empr_mail;
			$nomdest=$destinataire->empr_nom;
			if ($destinataire->empr_prenom) $nomdest=$destinataire->empr_prenom." ".$destinataire->empr_nom; 
			$f_message_to_send = $f_message;
			$f_message_to_send=str_replace("!!empr_name!!", $destinataire->empr_nom,$f_message_to_send); 
			$f_message_to_send=str_replace("!!empr_first_name!!", $destinataire->empr_prenom,$f_message_to_send); 
			$f_message_to_send=str_replace("!!empr_login!!", $destinataire->empr_login,$f_message_to_send); 
			$f_message_to_send=str_replace("!!empr_password!!", $destinataire->empr_password,$f_message_to_send);
			$f_message_to_send=str_replace("!!empr_mail!!", $destinataire->empr_mail,$f_message_to_send);
			if (strpos($f_message_to_send,"!!empr_loans!!")) $f_message_to_send=str_replace("!!empr_loans!!", m_liste_prets($destinataire),$f_message_to_send);
			if (strpos($f_message_to_send,"!!empr_resas!!")) $f_message_to_send=str_replace("!!empr_resas!!", m_liste_resas($destinataire),$f_message_to_send);
			if (strpos($f_message_to_send,"!!empr_name_and_adress!!")) $f_message_to_send=str_replace("!!empr_name_and_adress!!", nl2br(m_lecteur_adresse($destinataire)),$f_message_to_send);
			if (strpos($f_message_to_send,"!!empr_all_information!!")) $f_message_to_send=str_replace("!!empr_all_information!!", nl2br(m_lecteur_info($destinataire)),$f_message_to_send);
			//générer le corps du message
			$envoi_OK = mailpmb($nomdest, $emaildest, $f_objet_mail, $f_message_to_send, $PMBuserprenom." ".$PMBusernom, $PMBuseremail, $headers, "", $PMBuseremailbcc) ;
			if ($envoi_OK) {
				mysql_query("update empr_caddie_content set flag='1' where object_id='".$iddest."' and empr_caddie_id=$idemprcaddie ") or die ("Couldn't update empr_caddie_content !");
			} else {
				mysql_query("update empr_caddie_content set flag='2' where object_id='".$iddest."' and empr_caddie_id=$idemprcaddie ") or die ("Couldn't update empr_caddie_content !");
				$envoi_KO++;
			}
			$ienvoi++;
		}
		$sql = "select id_empr, empr_mail, empr_nom, empr_prenom from empr, empr_caddie_content where (flag='' or flag is null) and empr_caddie_id=$idemprcaddie and object_id=id_empr";
		$sql_result = mysql_query($sql) or die ("Couldn't select compte reste mailing !");
		$n_envoi_restant=mysql_num_rows($sql_result);
		$total_envoyes=(($total_envoyes+$ienvoi)*1)-$envoi_KO;;
		if ($n_envoi_restant > 0) {
			$parametres[total]=$total;
			$parametres[sub]="envoi";
			$parametres[total_envoyes]=$total_envoyes;
			$parametres[f_objet_mail]=htmlentities($f_objet_mail,ENT_QUOTES,$charset);
			$parametres[f_message]=htmlentities($f_message,ENT_QUOTES,$charset);
			$parametres[idemprcaddie]=$idemprcaddie;
			$msg[empr_mailing_recap_comptes_encours] = str_replace("!!total_envoyes!!", $total_envoyes, $msg[empr_mailing_recap_comptes_encours]) ;
			$msg[empr_mailing_recap_comptes_encours] = str_replace("!!total!!", $total, $msg[empr_mailing_recap_comptes_encours]) ;
			$msg[empr_mailing_recap_comptes_encours] = str_replace("!!n_envoi_restant!!", $n_envoi_restant, $msg[empr_mailing_recap_comptes_encours]) ;
			$message_info="<div class='row'>".
							$msg[empr_mailing_recap_comptes_encours]."
							</div>";
			print construit_formulaire_recharge (1000, "./mailing.php", "envoi_mailing", $parametres, $f_objet_mail, $message_info) ;
		} else {
			print "
			<h1>$msg[empr_mailing_titre_resultat]</h1>
				<div class='row'>
					<strong>$msg[empr_mailing_form_obj_mail]</strong> 
						".htmlentities($f_objet_mail,ENT_QUOTES,$charset)."
					</div>
				<div class='row'>
					<strong>$msg[empr_mailing_resultat_envoi]</strong> ";
			$msg[empr_mailing_recap_comptes] = str_replace("!!total_envoyes!!", $total_envoyes, $msg[empr_mailing_recap_comptes]) ;
			$msg[empr_mailing_recap_comptes] = str_replace("!!total!!", $total, $msg[empr_mailing_recap_comptes]) ;
			print $msg[empr_mailing_recap_comptes] ;
			print "		</div>
				<hr />
				<div class='row'>
					<a href='../../../circ.php?categ=caddie&sub=gestion&quoi=razpointage&moyen=raz&action=&idemprcaddie=$idemprcaddie&item=' target=_top>".$msg[empr_mailing_raz_pointage]."</a>
					</div>
				";
			$sql = "select id_empr, empr_mail, empr_nom, empr_prenom from empr, empr_caddie_content where flag='2' and empr_caddie_id=$idemprcaddie and object_id=id_empr ";
			$sql_result = mysql_query($sql) ;
			if (mysql_num_rows($sql_result)) {
				print "
					<hr /><div class='row'>
					<strong>$msg[empr_mailing_liste_erreurs]</strong>  
					</div>";
				while ($obj_erreur=mysql_fetch_object($sql_result)) {
					print "<div class='row'>
						".$obj_erreur->empr_nom." ".$obj_erreur->empr_prenom." (".$obj_erreur->empr_mail.") 
						</div>
						";
					}
				}
			}
		break;
	
	default:
		// include("$include_path/messages/help/$lang/mailing_empr.txt") ;
		break;
	}
print "</div></body></html>";



// Fonction qui construit un formulaire qui relance
function construit_formulaire_recharge ($time_out, $action, $name, $hidden_param, $texte_titre="",$texte_message="") {
	global $current_module, $msg;
	
	if (!is_array($hidden_param)) return "";
	$formulaire="\n<form class='form-$current_module' name=\"$name\" method=\"post\" action=\"$action\">";
	$formulaire.="\n<h3>$texte_titre</h3>
		<div class='form-contenu'>";
		
	while (list($cle, $params) = each($hidden_param)) {
		$formulaire.="\n<INPUT NAME=\"$cle\" TYPE=\"hidden\" value=\"$params\">";
		} // fin de liste
	$formulaire.=$texte_message;
	$formulaire.="\n</div>";
	if ($time_out<0) $formulaire.="\n<div class='row'><input type=submit class=bouton value='".$msg[form_recharge_bt_continuer]."' /></div>";
	$formulaire.="\n</form>";
	switch ($time_out) {
		case 0:
			$formulaire.="\n<script>document.".$name.".submit();</script>";
		 	break;
		case -1:
		 	break;
		default:
			$formulaire.="\n<script>setTimeout(\"document.".$name.".submit()\",".$time_out.");</script>";
		 	break;
		}
	return $formulaire;
	} 