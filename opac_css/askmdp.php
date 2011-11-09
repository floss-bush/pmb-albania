<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: askmdp.php,v 1.20.2.4 2011-09-19 15:55:11 dbellamy Exp $

$base_path=".";
require_once($base_path."/includes/init.inc.php");
require_once($base_path."/includes/error_report.inc.php") ;
require_once($base_path."/includes/global_vars.inc.php");
require_once($base_path.'/includes/opac_config.inc.php');

// récupération paramètres MySQL et connection à la base
require_once($base_path.'/includes/opac_db_param.inc.php');
require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

require_once($base_path."/includes/misc.inc.php");

//Sessions !! Attention, ce doit être impérativement le premer include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");
require_once($base_path.'/includes/start.inc.php');

require_once($base_path."/includes/notice_authors.inc.php");
require_once($base_path."/includes/notice_categories.inc.php");

require_once($base_path."/includes/check_session_time.inc.php");

// récupération localisation
require_once($base_path.'/includes/localisation.inc.php');

// version actuelle de l'opac
require_once($base_path.'/includes/opac_version.inc.php');

// fonctions de gestion de formulaire
require_once($base_path.'/includes/javascript/form.inc.php');

require_once($base_path.'/includes/templates/common.tpl.php');
require_once($base_path.'/includes/divers.inc.php');

// classe de gestion des catégories
require_once($base_path.'/classes/categorie.class.php');
require_once($base_path.'/classes/notice.class.php');
require_once($base_path.'/classes/notice_display.class.php');

// classe indexation interne
require_once($base_path.'/classes/indexint.class.php');

// classe d'affichage des tags
require_once($base_path.'/classes/tags.class.php');

// classe de gestion des réservations
require_once($base_path.'/classes/resa.class.php');

// pour l'affichage correct des notices
require_once($base_path."/includes/templates/notice.tpl.php");
require_once($base_path."/includes/navbar.inc.php");
require_once($base_path."/includes/explnum.inc.php");
require_once($base_path."/includes/notice_affichage.inc.php");
require_once($base_path."/includes/bulletin_affichage.inc.php");

// pour l'envoi de mails
require_once($base_path."/includes/mail.inc.php");

// autenticazione LDAP - by MaxMan
require_once($base_path."/includes/ldap_auth.inc.php");

// RSS
require_once($base_path."/includes/includes_rss.inc.php");

// pour fonction de formulaire de connexion
require_once($base_path."/includes/empr.inc.php");
// pour fonction de vérification de connexion
require_once($base_path.'/includes/empr_func.inc.php');

//Vérification de la session
$log_ok=connexion_empr();

// si $opac_show_homeontop est à 1 alors on affiche le lien retour à l'accueil sous le nom de la bibliothèque dans la fiche empr
if ($opac_show_homeontop==1) $std_header= str_replace("!!home_on_top!!",$home_on_top,$std_header);
else $std_header= str_replace("!!home_on_top!!","",$std_header);

// mise à jour du contenu opac_biblio_main_header
$std_header= str_replace("!!main_header!!",$opac_biblio_main_header,$std_header);

// RSS
$std_header= str_replace("!!liens_rss!!",genere_link_rss(),$std_header);

//Enrichissement OPAC
$std_header = str_replace("!!enrichment_headers!!","",$std_header);

if($opac_parse_html){
	ob_start();
}

print $std_header;

require_once ($base_path.'/includes/navigator.inc.php');
	
$query = "SELECT valeur_param FROM parametres WHERE type_param='opac' AND sstype_param = 'biblio_name'";
$result = mysql_query($query) or die ("*** Erreur dans la requ&ecirc;te <br />*** $query<br />\n");
$row = mysql_fetch_array ($result);
$demandeemail= "<hr /><p class='texte'>".$msg[mdp_txt_intro_demande]."</p>
	<form action=\"askmdp.php\" method=\"post\" ><br />
	<input type=\"text\" name=\"email\" size=\"20\" border=\"0\" value=\"email@\" onFocus=\"this.value='';\">&nbsp;&nbsp;
	<input type=\"hidden\" name=\"demande\" value=\"ok\" >
	<input type='submit' name='ok' value='".$msg[mdp_bt_send]."' class='bouton'>
	</form>"; 

print "<blockquote>";
if ($demande!="ok" || $email=='') {

	// Mettre ici le formulaire de saisie de l'email
	print $demandeemail ;
	
} else {
	$query = "SELECT empr_login, empr_password, empr_location,empr_mail,concat(empr_prenom,' ',empr_nom) as nom_prenom FROM empr WHERE empr_mail like '%".$email."%'";
	$result = mysql_query($query) or die ("*** Erreur dans la requ&ecirc;te <br />*** $query<br />\n");
	if (mysql_num_rows($result)!=0) {
		while ($row = mysql_fetch_object ($result)) {
			if (!$opac_biblio_name) {
				$query_loc = "SELECT name, email FROM docs_location WHERE idlocation='$row->empr_location'";
				$result_loc = mysql_query($query_loc) or die ("*** Erreur dans la requ&ecirc;te <br />*** $query_loc<br />\n");
				$info_loc = mysql_fetch_object ($result_loc) ;
				$biblio_name_temp=$info_loc->name ;
				$biblio_email_temp=$info_loc->email ;
			} else {
				$biblio_name_temp=$opac_biblio_name;
				$biblio_email_temp=$opac_biblio_email;
			}
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";

			// Pour faire suite à votre demande, nous vous prions de trouver ci-dessous vos informations de connexion pour <b>!!biblioname!!</b> :<br /> -Identifiant: !!login!! <br /> -Mot de passe: !!password!! <br /><br />Si vous rencontrez des difficultés, adressez un mail à !!biblioemail!!.
			$messagemail = $msg[mdp_mail_body] ;
			$messagemail = str_replace("!!login!!",$row->empr_login,$messagemail);
			$messagemail = str_replace("!!password!!",$row->empr_password,$messagemail);
			$messagemail = str_replace("!!biblioname!!","<a href=\"$opac_url_base\">".$biblio_name_temp."</a>",$messagemail);
			$messagemail = str_replace("!!biblioemail!!","<a href=mailto:$opac_biblio_email>$biblio_email_temp</a>",$messagemail);

			$objetemail = str_replace("!!biblioname!!",$biblio_name_temp,$msg[mdp_mail_obj]);
			print "<hr />";
			
			if($opac_parse_html){
				$objetemail = parseHTML($objetemail);
				$messagemail = parseHTML($messagemail);
				$biblio_name_temp = parseHTML($biblio_name_temp);
				$biblio_email_temp = parseHTML($biblio_email_temp); 
			}	

			$res_envoi=@mailpmb(trim($row->nom_prenom), $row->empr_mail,$objetemail,$messagemail,$biblio_name_temp, $biblio_email_temp, $headers);
			if (!$res_envoi) {
				print "<p class='texte'>Could not send information to $email.</p><br />" ;
				echo "<br />";
				print_r($error_send_mail);
				echo "<br />";
			}
			print "<p class='texte'>".$msg[mdp_sent_ok]." $email.</p>" ;
		}
	} else {
		print "<hr /><p class='texte'>".str_replace("!!biblioemail!!","<a href=mailto:$opac_biblio_email>$opac_biblio_email</a>",$msg[mdp_no_email])."</p>" ;
		print $demandeemail ;
	}
}

print "</blockquote>";

//insertions des liens du bas dans le $footer si $opac_show_liensbas
if ($opac_show_liensbas==1) $footer = str_replace("!!div_liens_bas!!",$liens_bas,$footer);
else $footer = str_replace("!!div_liens_bas!!","",$footer);

//affichage du bandeau de gauche si $opac_show_bandeaugauche = 1
if ($opac_show_bandeaugauche==0) {
	$footer= str_replace("!!contenu_bandeau!!","",$footer);
} else {
	$footer = str_replace("!!contenu_bandeau!!","<div id=\"bandeau\">!!contenu_bandeau!!</div>",$footer);
	$home_on_left=str_replace("!!welcome_page!!",$msg["welcome_page"],$home_on_left);
	$adresse=str_replace("!!common_tpl_address!!",$msg["common_tpl_address"],$adresse);
	$adresse=str_replace("!!common_tpl_contact!!",$msg["common_tpl_contact"],$adresse);
	$loginform=str_replace("!!common_tpl_login_invite!!",$msg["common_tpl_login_invite"],$loginform);
	
	// loading the languages avaiable in OPAC - martizva >> Eric
	require_once($base_path.'/includes/languages.inc.php');
	$home_on_left = str_replace("!!common_tpl_lang_select!!", show_select_languages("empr.php"), $home_on_left);
	
	if (!$_SESSION["user_code"]) {
		$loginform__ = genere_form_connexion_empr();
	} else {
		$loginform__.="<b>".$empr_prenom." ".$empr_nom."</b><br />\n";
		$loginform__.="<a href=\"empr.php\" id=\"empr_my_account\">".$msg["empr_my_account"]."</a><br />
			<a href=\"index.php?logout=1\" id=\"empr_logout_lnk\">".$msg["empr_logout"]."</a>";
	}
	$loginform = str_replace("!!login_form!!",$loginform__,$loginform);
	$footer= str_replace("!!contenu_bandeau!!",$home_on_left.$loginform.$meteo.$adresse,$footer);
}

//Enregistrement du log
global $pmb_logs_activate;
if($pmb_logs_activate){	
	global $log;
	$log->add_log('num_session',session_id());
	$log->save();
}

print $footer;

/* Fermeture de la connexion */
mysql_close();

if($opac_parse_html){
	$htmltoparse = ob_get_contents();
	ob_end_clean();
	$res = parseHTML($htmltoparse);
	print $res;
}
