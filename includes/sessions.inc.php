<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sessions.inc.php,v 1.36 2008-11-08 07:15:33 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonctions de gestion des sessions

// prevents direct script access
if(preg_match('/sessions\.inc\.php/', $REQUEST_URI)) {
	include('./forbidden.inc.php'); forbidden();
}

define( 'CHECK_USER_NO_SESSION', 1 );
define( 'CHECK_USER_SESSION_DEPASSEE', 2 );
define( 'CHECK_USER_SESSION_INVALIDE', 3 );
define( 'CHECK_USER_AUCUN_DROITS', 4 );
define( 'CHECK_USER_PB_ENREG_SESSION', 5 );
define( 'CHECK_USER_PB_OUVERTURE_SESSION', 6 );
define( 'CHECK_USER_SESSION_OK', 7 );

// checkUser : authentification de l'utilisateur
function checkUser($SESSNAME, $allow=0,$user_connexion='') {
	global $dbh;
	global $nb_per_page_author,$nb_per_page_publisher,$nb_per_page_collection,$nb_per_page_subcollection,$nb_per_page_serie;
	global $nb_per_page_a_search,$nb_per_page_p_search,$nb_per_page_s_search,$nb_per_page_empr ;
	global $nb_per_page_a_select,$nb_per_page_c_select,$nb_per_page_sc_select,$nb_per_page_p_select,$nb_per_page_s_select ;
	global $param_popup_ticket, $param_sounds , $param_licence ;
	
	global $biblio_name,$biblio_adr1,$biblio_adr2,$biblio_cp,$biblio_town,$biblio_state,$biblio_country,$biblio_phone,$biblio_email,$biblio_website,$biblio_logo;
	global $nb_per_page_search, $nb_per_page_select, $nb_per_page_gestion ;
	
	global $PMBuserid, $PMBusername, $PMBgrp_num;
	global $checkuser_type_erreur ;
	global $stylesheet ;
	global $PMBusernom;
	global $PMBuserprenom;
	global $PMBuseremail,$PMBuseremailbcc;
	global $explr_invisible ;
	global $explr_visible_unmod ;
	global $explr_visible_mod ;
	global $check_messages;
	
	// par défaut : pas de session ouverte
	$checkuser_type_erreur = CHECK_USER_NO_SESSION ;
	
	// récupère les infos de session dans les cookies
	$PHPSESSID = $_COOKIE["$SESSNAME-SESSID"];
	if ($user_connexion) $PHPSESSLOGIN = $user_connexion; 
	else $PHPSESSLOGIN = $_COOKIE["$SESSNAME-LOGIN"];
	$PHPSESSNAME = $_COOKIE["$SESSNAME-SESSNAME"];

	// message de debug messages ?
	if ($check_messages==-1) setcookie($SESSNAME."-CHECK-MESSAGES", 0, 0);
	if ($check_messages==1) setcookie($SESSNAME."-CHECK-MESSAGES", 1, 0);

	// on récupère l'IP du client
	$ip = $_SERVER['REMOTE_ADDR'];

	// recherche de la session ouverte dans la table
	$query = "SELECT SESSID, login, IP, SESSstart, LastOn, SESSNAME FROM sessions WHERE ";
	$query .= "SESSID='$PHPSESSID'";
	$txt_er = $query;
	$result = mysql_query($query, $dbh);
	$numlignes = mysql_num_rows($result);

	if(!$result || !$numlignes) {
		$checkuser_type_erreur = CHECK_USER_NO_SESSION ;
		return FALSE;
	}
	
	// vérification de la durée de la session
	$session = mysql_fetch_object($result);
	// durée depuis le dernier rafraichissement
	if(($session->LastOn+SESSION_REACTIVATE) < time()) {
		$checkuser_type_erreur = CHECK_USER_SESSION_DEPASSEE ;
		return FALSE;
	}
	// durée depuis le début de la session
	if(($session->SESSstart+SESSION_MAXTIME) < time()) {
		$checkuser_type_erreur = CHECK_USER_SESSION_DEPASSEE ;
		return FALSE;
	}
	
	// il faut stocker le sessid parce FL réutilise le tableau session pour aller lire les infos de users !!!
	if($session->SESSID=="") {
		$checkuser_type_erreur = CHECK_USER_SESSION_INVALIDE ;
		return FALSE;
	} else {
		$id_session = $session->SESSID ;
		$SESSstart_session = $session->SESSstart ;
	}
	// contrôle des droits utilisateurs
	$query = "SELECT * FROM users WHERE username='$PHPSESSLOGIN' ";
	$result = @mysql_query($query, $dbh);
	$session = mysql_fetch_object($result);

	if($allow) {
		if(!($allow & $session->rights)) {
			$checkuser_type_erreur = CHECK_USER_AUCUN_DROIT ;
			return FALSE;
		}
	}

	// authentification OK, on remet LAstOn à jour
	$t = time();
	$id = $id_session;
	
	// on en profite pour récupérer l'id du user
	$PMBuserid = $session->userid;
	$PMBusername = $session->username;
	
	// on avait bien stocké le sessid, on va pouvoir remettre à jour le laston, avec sessid dans la clause where au lieu de id en outre.
	$query = "UPDATE sessions SET LastOn='$t' WHERE sessid='$id' ";
	$result = mysql_query($query, $dbh) or die (mysql_error());

	if(!$result) {
		$checkuser_type_erreur = CHECK_USER_PB_ENREG_SESSION ;
		return FALSE;
	}
	
	// récupération de la langue de l'utilisateur

	// mise à disposition des variables de la session
	define('SESSlogin'	, $PHPSESSLOGIN);
	define('SESSname'	, $SESSNAME);
	define('SESSid'		, $PHPSESSID);
	define('SESSstart'	, $SESSstart_session);
	define('SESSlang'	, $session->user_lang);
	define('SESSrights'	, $session->rights);
	define('SESSuserid'	, $session->userid);
	
	/* Nbre d'enregistrements affichés par page */
	/* gestion */ 
	$nb_per_page_author = $session->nb_per_page_gestion ;
	$nb_per_page_publisher = $session->nb_per_page_gestion ;
	$nb_per_page_collection = $session->nb_per_page_gestion ;
	$nb_per_page_subcollection = $session->nb_per_page_gestion ;
	$nb_per_page_serie = $session->nb_per_page_gestion ;
	$nb_per_page_search = $session->nb_per_page_search ;
	$nb_per_page_select = $session->nb_per_page_select ;
	$nb_per_page_gestion = $session->nb_per_page_gestion ;
	
	/* param par défaut */	
	$requete_param = "SELECT * FROM users WHERE username='$PHPSESSLOGIN' LIMIT 1 ";
	$res_param = mysql_query($requete_param, $dbh);
	$field_values = mysql_fetch_row ( $res_param );
	$array_values = mysql_fetch_array ( $res_param );
	$i = 0;
	while ($i < mysql_num_fields($res_param)) {
		$field = mysql_field_name($res_param, $i) ;
		$field_deb = substr($field,0,6);
		switch ($field_deb) {
			case "deflt_" :
				global $$field;
				$$field=$field_values[$i];
				break;
			case "deflt2" :
				global $$field;
				$$field=$field_values[$i];
				break;
			case "param_" :
				global $$field;
				$$field=$field_values[$i];
				break ;
			case "value_" :
				global $$field;
				$$field=$field_values[$i];
				break ;
			case "xmlta_" :
				global $$field;
				$$field=$field_values[$i];
				break ;
			case "deflt3" :
				global $$field;
				$$field=$field_values[$i];
				break;
			default :
				break ;
			}
		$i++;
		}
	$requete_nom = "SELECT nom, prenom, user_email, userid, username, environnement, grp_num FROM users WHERE username='$PHPSESSLOGIN' ";
	$res_nom = mysql_query($requete_nom, $dbh);
	$param_nom = @mysql_fetch_object ( $res_nom );
	$PMBusernom=$param_nom->nom ;
	$PMBuserprenom=$param_nom->prenom ;
	$PMBuseremail=$param_nom->user_email ;	
	$PMBgrp_num=$param_nom->grp_num;
	$PMBuseremailbcc=$value_email_bcc ;	
	// pour que l'id user soit dispo partout
	define('SESSuserid'	, $param_nom->userid);
	$PMBuserid = $param_nom->userid;
	$PMBusername = $param_nom->username;
	$menusarray=unserialize($param_nom->environnement);
	if (is_array($menusarray)) $_SESSION["AutoHide"]=$menusarray;
	
	/* on va chercher la feuille de style du user */
	$stylesheet = $deflt_styles ;
	
	/* param de la localisation */	
	if ($deflt2docs_location) $requete_param = "SELECT * FROM docs_location where idlocation='$deflt2docs_location'";
	else $requete_param = "SELECT * FROM docs_location limit 1";
	$res_param = mysql_query($requete_param, $dbh);
	$obj_location = mysql_fetch_object ( $res_param ) ;
	$biblio_name=         $obj_location->name ;  
	$biblio_adr1=         $obj_location->adr1 ;   
	$biblio_adr2=         $obj_location->adr2 ;   
	$biblio_cp=           $obj_location->cp ;   
	$biblio_town=         $obj_location->town ;    
	$biblio_state=        $obj_location->state ;   
	$biblio_country=      $obj_location->country ; 
	$biblio_phone=        $obj_location->phone ;
	$biblio_email=        $obj_location->email ;   
	$biblio_website=      $obj_location->website ; 
	$biblio_logo=         $obj_location->logo ;
	
	/* recherches */
	/* author */
	$nb_per_page_a_search = $session->nb_per_page_search ;
	/* publisher */
	$nb_per_page_p_search = $session->nb_per_page_search ;
	/* subject */
	$nb_per_page_s_search = $session->nb_per_page_search ;
	
	/* lecteur */
	$nb_per_page_empr = $session->nb_per_page_search ;
	
	/* selectors */
	/* author */
	$nb_per_page_a_select = $session->nb_per_page_select ;
	/* collection */
	$nb_per_page_c_select = $session->nb_per_page_select ;
	/* sub-collection */
	$nb_per_page_sc_select = $session->nb_per_page_select ;
	/* publisher */
	$nb_per_page_p_select = $session->nb_per_page_select ;
	/* serie */
	$nb_per_page_s_select = $session->nb_per_page_select ;

	// pour visibilite des exemplaires :
	$explr_invisible = $session->explr_invisible ;
	$explr_visible_unmod = $session->explr_visible_unmod ;
	$explr_visible_mod = $session->explr_visible_mod ;
	
	return TRUE;
	}

// startSession : fonction de démarrage d'une session
function startSession($SESSNAME, $login, $database=LOCATION) {
	global $dbh; // le lien MySQL
	global $stylesheet; /* pour qu'à l'ouverture de la session le user récupère de suite son style */
	global $PMBuserid, $PMBusername, $PMBgrp_num;
	global $checkuser_type_erreur ;
	global $PMBusernom;
	global $PMBuserprenom;
	global $PMBuseremail;
	global $PMBdatabase ;
	
	if (!$PMBdatabase) $PMBdatabase=$database;
	
	// nettoyage des sessions 'oubliées'
	CleanTable();

	// génération d'un identificateur unique

	// initialisation du générateur de nombres aléatoires
	mt_srand((float) microtime()*1000000);

	// nombre aléatoire entre 1111111111 et 9999999999
	$SESSID = mt_rand(1111111111, 9999999999);

	// début session (date UNIX)
	$SESSstart = time();

	// adresse IP du client
	$IP = $_SERVER['REMOTE_ADDR'];

	$query = "SELECT rights, user_lang FROM users WHERE username='$login'";
	$result = mysql_query($query, $dbh);
	$ff = mysql_fetch_object($result);
	$flag = $ff->rights;

	// inscription de la session dans la table
	$query = "INSERT INTO sessions (SESSID, login, IP, SESSstart, LastOn, SESSNAME) VALUES(";
	$query .= "'$SESSID'";
	$query .= ", '$login'";
	$query .= ", '$IP'";
	$query .= ", '$SESSstart'";
	$query .= ", '$SESSstart'";
	$query .= ", '$SESSNAME' )";

	$result = mysql_query($query, $dbh);
	if(!$result) {
		$checkuser_type_erreur = CHECK_USER_PB_OUVERTURE_SESSION ;
		return CHECK_USER_PB_OUVERTURE_SESSION ;
	}

	// cookie pour le login de l'utilisateur
	setcookie($SESSNAME."-LOGIN", $login, 0);

	// cookie pour le nom de la session
	setcookie($SESSNAME."-SESSNAME", $SESSNAME, 0);

	// cookie pour l'ID de session
	setcookie($SESSNAME."-SESSID", $SESSID, 0);

	// cookie pour la base de donnée
	setcookie($SESSNAME."-DATABASE", $PMBdatabase, 0);

	// mise à disposition des variables de la session
	define('SESSlogin'	, $login);
	define('SESSname'	, $SESSNAME);
	define('SESSid'		, $SESSID);
	define('SESSstart'	, $SESSstart);
	define('SESSlang'	, $ff->user_lang);
	define('SESSrights'	, $flag);
	
	/* param par défaut */	
	$requete_param = "SELECT * FROM users WHERE username='$login' LIMIT 1 ";
	$res_param = mysql_query($requete_param, $dbh);
	$field_values = mysql_fetch_row( $res_param );
	$i = 0;
	while ($i < mysql_num_fields($res_param)) {
		$field = mysql_field_name($res_param, $i) ;
		$field_deb = substr($field,0,6);
		switch ($field_deb) {
			case "deflt_" :
				global $$field;
				$$field=$field_values[$i];
				break;
			case "deflt2" :
				global $$field;
				$$field=$field_values[$i];
				break;
			case "param_" :
				global $$field;
				$$field=$field_values[$i];
				break ;
			case "value_" :
				global $$field;
				$$field=$field_values[$i];
				break ;
			case "xmlta_" :
				global $$field;
				$$field=$field_values[$i];
				break ;
			case "deflt3" :
				global $$field;
				$$field=$field_values[$i];
				break;
			default :
				break ;
			}
		$i++;
		}
	$requete_nom = "SELECT nom, prenom, user_email, userid, username, grp_num FROM users WHERE username='$login' ";
	$res_nom = mysql_query($requete_nom, $dbh);
	$param_nom = mysql_fetch_object ( $res_nom );
	$PMBusernom=$param_nom->nom ;
	$PMBuserprenom=$param_nom->prenom ;
	$PMBgrp_num=$param_nom->grp_num;
	$PMBuseremail=$param_nom->user_email ;	
	// pour que l'id user soit dispo partout
	define('SESSuserid'	, $param_nom->userid);
	$PMBuserid = $param_nom->userid;
	$PMBusername = $param_nom->username;
	
	/* on va chercher la feuille de style du user */
	$stylesheet = $deflt_styles ;
	
	//Ouverture de la session php
	header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: post-check=0, pre-check=0",false);
	session_cache_limiter('must-revalidate');
	session_name("pmb".SESSid);
	session_start();
	
	//Récupération  de l'historique
	$query = "select session from admin_session where userid=".$PMBuserid;
	$resultat=mysql_query($query);
	if ($resultat) {
		if (mysql_num_rows($resultat)) {
			$_SESSION["session_history"]=@unserialize(@mysql_result($resultat,0,0));
		}
	}

	return CHECK_USER_SESSION_OK ;
	}

// cleanTable : nettoyage des sessions terminées (user non-deconnecté)
function cleanTable() {
	global $dbh;

	// heure courante moins une heure
	$time_out = time() - SESSION_MAXTIME;

	// suppression des sessions inactives
	$query = "DELETE FROM sessions WHERE LastOn < $time_out ";
	$result = mysql_query($query, $dbh);
	}

// sessionDelete : fin d'une session
function sessionDelete($SESSNAME) {
	global $dbh;

	$login = $_COOKIE[$SESSNAME.'-LOGIN'];

	$PHPSESSID = $_COOKIE["$SESSNAME-SESSID"];
	$PHPSESSLOGIN = $_COOKIE["$SESSNAME-LOGIN"];
	$PHPSESSNAME = $_COOKIE["$SESSNAME-SESSNAME"];



	// altération du cookie-client (au cas où la suppression ne fonctionnerait pas)

	setcookie($SESSNAME."-LOGIN", "no_login", 0);
	setcookie($SESSNAME."-SESSNAME", "no_session", 0);
	setcookie($SESSNAME."-SESSID", "no_id_session", 0);

	// tentative de suppression ddes cookies

	setcookie($SESSNAME."-SESSNAME");
	setcookie($SESSNAME."-SESSID");
	setcookie($SESSNAME."-LOGIN");

	//Destruction de la session php
	session_destroy();

	// effacement de la session de la table des sessions

	$query = "DELETE FROM sessions WHERE login='$login'";
	$query .= " AND SESSNAME='$SESSNAME' and SESSID='$PHPSESSID'";

	$result = @mysql_query($query, $dbh);
	if($result)
		return TRUE;

	return FALSE;

	}

