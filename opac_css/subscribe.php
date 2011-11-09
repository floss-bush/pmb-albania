<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: subscribe.php,v 1.9.2.2 2011-06-08 10:34:06 arenou Exp $

$base_path=".";
require_once($base_path."/includes/init.inc.php");
require_once($base_path."/includes/error_report.inc.php") ;
require_once($base_path."/includes/global_vars.inc.php");
require_once($base_path."/includes/rec_history.inc.php");
require_once($base_path.'/includes/opac_config.inc.php');
	
// récupération paramètres MySQL et connection á la base
if (file_exists($base_path.'/includes/opac_db_param.inc.php')) require_once($base_path.'/includes/opac_db_param.inc.php');
	else die("Fichier opac_db_param.inc.php absent / Missing file Fichier opac_db_param.inc.php");
	
require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

//Sessions !! Attention, ce doit être impérativement le premier include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");

require_once($base_path.'/includes/start.inc.php');

if (!$opac_websubscribe_show) die("");
if ($subsact=="validation" && (!$login || !$cle_validation)) die("");

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

require_once($base_path."/includes/marc_tables/".$lang."/empty_words");
require_once($base_path."/includes/misc.inc.php");
// pour l'affichage correct des notices
require_once($base_path."/includes/templates/common.tpl.php");
require_once($base_path."/includes/templates/notice.tpl.php");
require_once($base_path."/includes/navbar.inc.php");
require_once($base_path."/includes/notice_authors.inc.php");
require_once($base_path."/includes/notice_categories.inc.php");

require_once($base_path."/includes/notice_affichage.inc.php");

require_once($base_path."/classes/analyse_query.class.php");

// pour fonction de formulaire de connexion
require_once($base_path."/includes/empr.inc.php");

//pour la gestion des tris
require_once($base_path."/classes/sort.class.php");

// si paramétrage authentification particulière
if (file_exists($base_path.'/includes/ext_auth.inc.php')) require_once($base_path.'/includes/ext_auth.inc.php');

// pour les étagères et les nouveaux affichages
require_once($base_path."/includes/isbn.inc.php");
require_once($base_path."/classes/notice_affichage.class.php");
require_once($base_path."/includes/etagere_func.inc.php");

require_once($base_path."/includes/websubscribe.inc.php");
require_once($base_path."/includes/mail.inc.php");

// RSS
require_once($base_path."/includes/includes_rss.inc.php");

// si $opac_show_homeontop est à 1 alors on affiche le lien retour à l'accueil sous le nom de la bibliothèque
if ($opac_show_homeontop==1) $std_header= str_replace("!!home_on_top!!",$home_on_top,$std_header);
else $std_header= str_replace("!!home_on_top!!","",$std_header);

// mise à jour du contenu opac_biblio_main_header
$std_header= str_replace("!!main_header!!",$opac_biblio_main_header,$std_header);

// RSS
$std_header= str_replace("!!liens_rss!!",genere_link_rss(),$std_header);
// l'image $logo_rss_si_rss est calculée par genere_link_rss() en global
$liens_bas = str_replace("<!-- rss -->",$logo_rss_si_rss,$liens_bas);

$std_header = str_replace("!!enrichment_headers!!","",$std_header);

if($opac_parse_html){
	ob_start();
}

print $std_header;

if ($time_expired) echo "<script>alert(\"".sprintf($msg["session_expired"],round($opac_duration_session_auth/60))."\");</script>";

echo "<div id='websubscribe'>";

switch($subsact) {
	case 'validation':
		$verif=verif_validation_compte();
		echo $verif[1];
		break;
	case 'inscrire':
		if ($f_verifcode) {
			if (md5($f_verifcode) == $_SESSION['image_random_value']) {
			// set the session
			$_SESSION['image_is_logged_in'] = true;
			// remove the random value from session			
			$_SESSION['image_random_value'] = '';
			$verif=verif_validite_compte();
			echo $verif[1];
			} else {
				// set the session
				$_SESSION['image_is_logged_in'] = false;
				// remove the random value from session			
				$_SESSION['image_random_value'] = '';
				// Raté on repart...
				echo $msg[subs_pb_wrongcode] ;
				echo generate_form_inscription() ;
				}
		} else {
			// vide
			echo $msg[subs_pb_wrongcode] ;
			echo generate_form_inscription() ;
			}	
		break;
	case '':
	default:
		$subsact='';
		echo $msg[subs_intro_services];
		echo str_replace("!!nb_h_valid!!",$opac_websubscribe_valid_limit,$msg[subs_intro_explication]);
		echo generate_form_inscription() ;
		break;
	}
	
echo "</div>";
	
//insertions des liens du bas dans le $footer si $opac_show_liensbas
if ($opac_show_liensbas==1) $footer = str_replace("!!div_liens_bas!!",$liens_bas,$footer);
	else $footer = str_replace("!!div_liens_bas!!","",$footer);

//si ce n'est pas un popup qui est affiché, alors on affiche $footer
if ($opac_show_bandeaugauche==0) {
	print str_replace("!!contenu_bandeau!!","",$footer);
	} else {
		$footer = str_replace("!!contenu_bandeau!!","<div id=\"bandeau\">!!contenu_bandeau!!</div>",$footer);
		$home_on_left=str_replace("!!welcome_page!!",$msg["welcome_page"],$home_on_left);
		$adresse=str_replace("!!common_tpl_address!!",$msg["common_tpl_address"],$adresse);
		$adresse=str_replace("!!common_tpl_contact!!",$msg["common_tpl_contact"],$adresse);
		$loginform=str_replace("!!common_tpl_login_invite!!",$msg["common_tpl_login_invite"],$loginform);
		
		// loading the languages available in OPAC - martizva >> Eric
		require_once($base_path.'/includes/languages.inc.php');
		$home_on_left = str_replace("!!common_tpl_lang_select!!", show_select_languages("index.php"), $home_on_left);
		
		
		if (!$_SESSION["user_code"]) {
			$loginform__ = genere_form_connexion_empr();
			} else {
				$loginform__.="<b>".$empr_prenom." ".$empr_nom."</b><br />\n";
				$loginform__.="<a href=\"empr.php\" id=\"empr_my_account\">".$msg["empr_my_account"]."</a><br />
					<a href=\"index.php?logout=1\" id=\"empr_logout_lnk\">".$msg["empr_logout"]."</a>";
				}
		$loginform = str_replace("!!login_form!!",$loginform__,$loginform);
		print str_replace("!!contenu_bandeau!!",$home_on_left.$loginform.$meteo.$adresse,$footer);
		} 

mysql_close($dbh);

if($opac_parse_html){
	$htmltoparse = ob_get_contents();
	ob_end_clean();
	$res = parseHTML($htmltoparse);
	print $res;
}
