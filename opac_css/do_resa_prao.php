<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: do_resa_prao.php,v 1.3 2010-03-05 09:02:08 dbellamy Exp $


session_start();

setlocale(LC_ALL,"fr");
include_once("./portail_prao/config/cfg.php");

$bean = new sso();
if (array_key_exists("kticket", $_GET) && $_SESSION["KSESSION"] == "") {
	connecteurMgr::validerTicket($bean);
} elseif (array_key_exists("ksession",$_GET)) {

	$_SESSION["KSESSION"]=$_GET["ksession"];	
	connecteurMgr::verifierSession($bean);
} else {
	connecteurMgr::verifierSession($bean);
}

if ($bean->code_retour!='' && $bean->code_retour!='0' ){
	//Non authentifié
	$kportal_authentication_link="http://www.prao.org/specific/redirect_login.jsp?URL_REDIRECT=";
	print "<script type='text/javascript'>";
	print "document.location=(\"".$kportal_authentication_link."http://prao.centredoc.org/opac/do_resa_prao.php?".urlencode($_SERVER["QUERY_STRING"])."\");";
	print "</script>";
}
//Authentifié

$base_path=".";
$is_opac_included=true;

//Sauvegarde des paramètres de la session
$push["SSOBEAN"]=$_SESSION["SSOBEAN"];
$push["KSESSION"]=$_SESSION["KSESSION"];
$push["URL_KPORTAL"]=$_SESSION["URL_KPORTAL"];
$push["SECURE"]=$_SESSION["SECURE"];
$push["SERVICE"]=$_SESSION["SERVICE"];
$push["LANGUE"]=$_SESSION["LANGUE"];

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

require_once($base_path."/includes/empr.inc.php");
require_once($base_path."/includes/connexion_empr.inc.php");
// pour fonction de vérification de connexion
require_once($base_path.'/includes/empr_func.inc.php');

// autenticazione LDAP - by MaxMan
require_once($base_path."/includes/ldap_auth.inc.php");

// RSS
require_once($base_path."/includes/includes_rss.inc.php");

if ( ($lvl=='make_sugg' || $lvl=='valid_sugg') && $opac_show_suggest == 2) {
	//Suggestion possible sans authentification
	$log_ok = 1;
} else {
	
	if (!$_SESSION["user_code"]) {
		//session dans pmb non initialisee
		$q="select empr_password from empr where empr_login='".addslashes($bean->code_utilisateur_kportal)."' limit 1";
		$res=mysql_query($q,$dbh);
		if ($res) {
			$_POST['login']=$bean->code_utilisateur_kportal;
			$password=mysql_result($res,0,0);
			$time_expired=0;
		}
	}
	//Vérification de la session
	$empty_pwd=true;
	$ext_auth=false;
	$log_ok=connexion_empr();
}
	

//Restauration des paramètres de session
$_SESSION["SSOBEAN"]=$push["SSOBEAN"];
$_SESSION["KSESSION"]=$push["KSESSION"];
$_SESSION["URL_KPORTAL"]=$push["URL_KPORTAL"];
$_SESSION["SECURE"]=$push["SECURE"];
$_SESSION["SERVICE"]=$push["SERVICE"];
$_SESSION["LANGUE"]=$push["LANGUE"];

if ($opac_resa_popup) {
	print $popup_header;
} else {
	connecteurMgr::lireTemplate("haut");
	print link_styles($css);
	include($base_path.'/includes/navigator.inc.php');
}
	

$popup_resa = 1 ;

if ($log_ok) {
	
	switch($lvl) {
		case 'make_sugg' :
			if ($allow_sugg || $opac_show_suggest==2) include($base_path.'/includes/make_sugg.inc.php');
				else print $msg[empr_no_allow_sugg];
			break;
		case 'valid_sugg' :
			if ($allow_sugg || $opac_show_suggest==2) include($base_path.'/includes/valid_sugg.inc.php');
				else print $msg[empr_no_allow_sugg];
			break;
		case 'resa_planning' : 
			if ($allow_book) include($base_path.'/includes/resa_planning.inc.php');
				else print $msg[empr_no_allow_book];
			break;
		default:
		case 'resa':
			if ($allow_book) include($base_path.'/includes/resa.inc.php');
			else print $msg[empr_no_allow_book];
			break;
	}

} else {

	if (!$time_expired) {
		$erreur_session = "" ;
		if ($login) print "<br />".$msg["empr_bad_login"]."<br /><br /><br />";
		else {
			print do_formulaire_connexion() ;
		}
	} else {
		print "<br />".sprintf($msg["session_expired"],round($opac_duration_session_auth/60))."<br /><br /><br />";
		print do_formulaire_connexion() ;
	}
	
}

if ($erreur_session) print $erreur_session ;

if ($opac_resa_popup) {
	print $popup_footer;
} else {
	connecteurMgr::lireTemplate("bas");
}
/* Fermeture de la connexion */
mysql_close();
