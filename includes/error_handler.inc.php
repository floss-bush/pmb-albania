<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: error_handler.inc.php,v 1.5 2007-03-10 09:46:47 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// error_handler pour PMB

// fonction interne de gestion des erreurs
function internal_error_handler( $errno, $errmsg, $filename, $linenum, $vars) {

	// fichier de log
	global $logfile;

	// choses à surveiller
	global $logged_errors;

	// date de l'erreur
	$dt = date("Y-m-d H:i:s");

	// définition des types d'erreurs
	$error_type = array(
				1	=>	'error',
				2	=>	'warning',
				4	=>	'parsing error',
				8	=>	'notice',
				16	=>	'core error',
				32	=>	'core warning',
				64	=>	'compile error',
				128	=>	'compile warning',
				256	=>	'user error',
				512	=>	'user warning',
				1024	=>	'user notice'
				);


	if(in_array($errno, $logged_errors)) {

		// composition du message d'erreur

		$err = '<errorentry>';
		$err .= "<datetime>$dt</datetime>";
		$err .= "<errornum>$errno</errornum>";
		$err .= "<errortype>$error_type[$errno]</errortype>";
		$err .= "<errormsg>$errmsg</errormsg>";
		$err .= "<scriptname>$filename</scriptname>";
		$err .= "<scriptlinenum>$linenum</scriptlinenum>";
		$err .= '</errorentry>';

		// écriture de l'erreur dans le fichier

		error_log($err, 3, $logfile);
	}
}


switch($loglevel) {
	case 'unstable':
		$logged_errors = array(
								E_ERROR,
								E_WARNING,
								E_PARSE,
								E_NOTICE,
								E_CORE_ERROR,
								E_CORE_WARNING,
								E_COMPILE_ERROR,
								E_COMPILE_WARNING,
								E_USER_ERROR,
								E_USER_WARNING,
								E_USER_NOTICE);
		// on prend la main sur le gestionnaire d'erreurs
		error_reporting(0);
		// mise en place du nouveau gestionnaire
		$old_error_handler = set_error_handler("internal_error_handler");
		break;
	case 'off':
		break;
	default:
		$logged_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
		// on prend la main sur le gestionnaire d'erreurs
		error_reporting(0);
		// mise en place du nouveau gestionnaire
		$old_error_handler = set_error_handler("internal_error_handler");
		break;
}


