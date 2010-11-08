<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: config.inc.php,v 1.164 2010-09-25 05:42:27 touraine37 Exp $

// fichier de configuration générale

$pmb_version = "</b>3.4.RC2</b>";
$pmb_version_brut = "3.4.RC2";
$pmb_version_database_as_it_should_be = "v4.90";
$pmb_version_web = "http://www.sigb.net/config.inc.php" ;
@error_reporting (E_ERROR | E_PARSE | E_WARNING);

@include_once("includes/global_vars.inc.php") ;
@include_once("global_vars.inc.php") ;

// prevents direct script access
if(strpos($HTTP_SERVER_VARS['PHP_SELF'],'config.inc.php')) {
	echo $pmb_version_brut ;
	exit ;
}

$default_lang = 'fr_FR';
$default_helpdir = $default_lang;
// Character set = encodage des données. Attention ne pas modifier en cours d'utilisation, votre base de données serait pleine de caracteres bizarre !!!
$charset= 'iso-8859-1';

// feuille de style à utiliser
$stylesheet = 'light';

// utilisation des raccourcis clavier (0=non ; 1=oui)
$use_shortcuts = 1;

// taille des fenêtres de selecteurs
$selector_x_size = 400; 	# largeur
$selector_y_size = 400;		# hauteur

// niveau du fichier de log :
// stable=erreurs utilisateur seulement
// unstable=toutes erreurs
// off=pas de gestion des erreurs

$loglevel = 'off';

// fichier de log
$logfile = './journal.log';

// flags pour la gestion des droits utilisateurs
define('CIRCULATION_AUTH'		,    1);
define('CATALOGAGE_AUTH'		,    2);
define('AUTORITES_AUTH'			,    4);
define('ADMINISTRATION_AUTH'	,    8);
define('EDIT_AUTH'				,   16);
define('SAUV_AUTH'				,   32);
define('DSI_AUTH'				,   64);
define('PREF_AUTH'				,  128);
define('ACQUISITION_AUTH'		,  256);
define('RESTRICTCIRC_AUTH'		,  512);
define('RESTRICTCATAL_AUTH'		, 1024);
define('THESAURUS_AUTH'			, 2048);
define('TRANSFERTS_AUTH'		, 4096);
define('EXTENSIONS_AUTH'		, 8192);
define('DEMANDES_AUTH'		, 16384);

// durée des sessions
define('SESSION_REACTIVATE', 7200); // refresh max = 120 minutes
define('SESSION_MAXTIME', 86400);	// durée de vie maximum d'une session = 24h

// définition des périodicités de pério
define('ABT_PERIODICITE_JOUR'		,    1);

// définition des types d'audit
define('AUDIT_NOTICE'	,    1);
define('AUDIT_EXPL'		,    2);
define('AUDIT_BULLETIN'	,    3);
define('AUDIT_ACQUIS'	,    4);
define('AUDIT_PRET'		,    5);

/* la langue est fixée sur la valeur par défaut pour l'instant */
$lang= $default_lang;
$helpdir = $lang;

/* répertoire où sont stockées les sauvegardes (dans le rép 'admin/backup') */
$backup_dir = "backups";

// est stockée en base mais par défaut, si vide ...
if (!$pmb_opac_url) $pmb_opac_url = "./opac_css/";
	
/* Nbre d'enregistrements affichés par page */
/* autorités */                  /* each was 10 */
$nb_per_page_author = 20 ;
$nb_per_page_publisher = 20 ;
$nb_per_page_collection = 20 ;
$nb_per_page_subcollection = 20 ;
$nb_per_page_serie = 20 ;

/* recherches */
/* author */
$nb_per_page_a_search = 10 ; /* was 3 */
/* publisher */
$nb_per_page_p_search = 10 ; /* was 4 */
/* subject */
$nb_per_page_s_search = 10 ; /* was 4 */

/* lecteur */
$nb_per_page_empr = 10 ; /* was 4 */

/* selectors */
/* author */
$nb_per_page_a_select = 10 ; /* was 10 */
/* collection */
$nb_per_page_c_select = 10 ; /* was 10 */
/* sub-collection */
$nb_per_page_sc_select = 10 ; /* was 10 */
/* publisher */
$nb_per_page_p_select = 10 ; /* was 10 */
/* serie */
$nb_per_page_s_select = 10 ; /* was 10 */
/* groups */
$nb_per_page_group = 10; /* is 10 */

$include_path      = 'includes';               // includes
$class_path        = 'classes';                // classes
$javascript_path   = 'javascript';             // scripts
$styles_path       = 'styles';                 // styles

// alertes sonores, en tableau pour pouvoir en mettre plusieurs dans le futur :
$alertsound[critique]="<embed src='sounds/boing.wav' autostart='true' loop='false' hidden='true' width='0' height='0'>";
$alertsound[information]="<embed src='sounds/waou.wav' autostart='true' loop='false' hidden='true' width='0' height='0'>";
$alertsound[question]="<embed src='sounds/boing.wav' autostart='true' loop='false' hidden='true' width='0' height='0'>";
$alertsound[application]="<embed src='sounds/boing.wav' autostart='true' loop='false' hidden='true' width='0' height='0'>";
$param_sounds = 1 ;

$homepage = 'http://www.sigb.net/';

@include_once("includes/config_local.inc.php") ;
@include_once("config_local.inc.php") ;

