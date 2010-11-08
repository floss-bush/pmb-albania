<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: connector_out.php,v 1.2 2010-07-29 12:50:27 erwanmartin Exp $
//Here be komodo dragons

//Les erreurs php font taches dans les protocols des connecteurs
if (!isset($_GET["debug"])) {
	ini_set('display_errors', 0);
	error_reporting(0);
}

$base_path="..";
$base_nobody = 1;
$base_noheader = 1;
$base_nocheck = 1;
$class_path = $base_path."/classes";
$include_path = $base_path."/includes";

//Cette fonction recréer un environnement de session, comme si l'utilisateur était loggué
function create_user_environment($user_id) {
	//Copié de /includes/sessions.inc.php
	global $dbh; // le lien MySQL
	global $stylesheet; /* pour qu'à l'ouverture de la session le user récupère de suite son style */
	global $PMBuserid, $PMBusername, $PMBgrp_num;
	global $checkuser_type_erreur ;
	global $PMBusernom;
	global $PMBuserprenom;
	global $PMBuseremail;
	global $PMBdatabase ;
	global $database;
	global $deflt_styles;
	
	if (!$PMBdatabase) $PMBdatabase=$database;
	
	$user_id+=0;
	$query = "SELECT rights, username, user_lang FROM users WHERE userid=$user_id";
	$result = mysql_query($query, $dbh);
	if (!$result)
		return false;
	$ff = mysql_fetch_object($result);
	$flag = $ff->rights;

	// mise à disposition des variables de la session
	define('SESSlogin'	, $ff->username);
	define('SESSname'	, 'PhpMyBibli');
	define('SESSid'		, 0);
	define('SESSstart'	, 0);
	define('SESSlang'	, $ff->user_lang);
	define('SESSrights'	, $flag);
	
	/* param par défaut */	
	$requete_param = "SELECT * FROM users WHERE userid=$user_id LIMIT 1 ";
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
	$requete_nom = "SELECT nom, prenom, user_email, userid, username, grp_num FROM users WHERE userid=$user_id ";
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

	//Récupération  de l'historique
	$query = "select session from admin_session where userid=".$PMBuserid;
	$resultat=mysql_query($query);
	if ($resultat) {
		if (mysql_num_rows($resultat)) {
			$_SESSION["session_history"]=@unserialize(@mysql_result($resultat,0,0));
		}
	}

	return true;
}

//Ignition sequence:
require_once ("$base_path/includes/init.inc.php");

//Les erreurs php font taches dans les protocols des connecteurs
if (!isset($_GET["debug"])) {
	ini_set('display_errors', 0);
	error_reporting(~E_ALL);
}

require_once ($class_path."/connecteurs_out.class.php");
require_once ($class_path."/external_services.class.php");

$source_id = $_GET["source_id"];
$source_id += 0;

if (!$source_id)
	die();

//Trouvons de quel connecteur dépend la source
$sql = "SELECT connectors_out_sources_connectornum FROM connectors_out_sources WHERE connectors_out_source_id = ".$source_id;
$res = mysql_query($sql, $dbh);
if (!mysql_num_rows($res))
	die();
$connector_id = mysql_result($res, 0, 0);
if (!$connector_id)
	die();

//Instantions le connecteur
$daconn = instantiate_connecteur_out($connector_id);
//Cherchons l'id de l'utilisateur pmb qui doit faire tourner les fonctions
$running_pmb_user_id = $daconn->get_running_pmb_userid($source_id);
//Créeons un environnement de session virtuel.
if (!create_user_environment($running_pmb_user_id))
	die();

if(SESSlang) {
	$lang=SESSlang;
	$helpdir = $lang;
}
else {
	$lang="fr_FR";
	$helpdir = "fr_FR";	
}

if ($daconn->need_global_messages()) {
	//Allons chercher les messages
	include_once("$class_path/XMLlist.class.php");
	$messages = new XMLlist("$include_path/messages/$lang.xml", 0);
	$messages->analyser();
	$msg = $messages->table;
}

if (!$daconn) {
	die(); //Oups!
}

//Au boulot le connecteur!
$daconn->process($source_id, $running_pmb_user_id);

?>