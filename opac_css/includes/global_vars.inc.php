<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: global_vars.inc.php,v 1.9 2008-09-23 22:16:29 dbellamy Exp $

// fichier de configuration générale

// prevents direct script access
pt_register ("SERVER", "REQUEST_URI");
if(preg_match('/global_vars\.inc\.php/', $REQUEST_URI)) {
	require_once('./forbidden.inc.php'); forbidden();
}

/* VERSION SUPER GLOBALS */
/* on commence par tout unset... */
//$arr = array_merge(&$_ENV, &$_GET, &$_POST, &$_COOKIE,  &$_FILES, &$_REQUEST, &$_SERVER);
//while(list($__key__PMB) = each($arr)) unset(${$__key__PMB});                 
//$arr = array_merge(&$HTTP_GET_VARS, &$HTTP_POST_VARS,&$HTTP_POST_FILES,&$HTTP_COOKIE_VARS, &$HTTP_SERVER_VARS, &$HTTP_ENV_VARS );
//while(list($__key__PMB) = each($arr)) unset(${$__key__PMB});                 


function add_sl(&$var) {
	if (is_array($var)) {
		reset($var);
		while (list($k,$v)=each($var)) {
			add_sl($var[$k]);
		}
	} else {
		$var=addslashes($var);
	}
}

/* on récupère tout sans se poser de question, attention à la sécurité ! */
while (list($__key__PMB, $val) = @each($_GET)) {
	if (($__key__PMB!="base_path")&&($__key__PMB!="include_path")&&($__key__PMB!="class_path")) {
		if (get_magic_quotes_gpc())
			$GLOBALS[$__key__PMB] = $val;
		else {
			add_sl($val);
			$GLOBALS[$__key__PMB] = $val;
		}
	}
}
while (list($__key__PMB, $val) = @each($_POST)) {
	if (($__key__PMB!="base_path")&&($__key__PMB!="include_path")&&($__key__PMB!="class_path")) {
		if (get_magic_quotes_gpc())
			$GLOBALS[$__key__PMB] = $val;
		else {
			add_sl($val);
			$GLOBALS[$__key__PMB] = $val;
		}
	}
}

//Post de fichiers
while (list($__key__PMB, $val) = @each($_FILES)) {
	if (($__key__PMB!="base_path")&&($__key__PMB!="include_path")&&($__key__PMB!="class_path")) {
		if (get_magic_quotes_gpc())
			$GLOBALS[$__key__PMB] = $val;
		else {
			add_sl($val);
			$GLOBALS[$__key__PMB] = $val;
		}
	}
}

// Inutile
//while (list($key, $val) = @each($_REQUEST)) {
//	$GLOBALS[$key] = $val;
//	}

// Récupérées par pt_register
//while (list($key, $val) = @each($_SERVER)) {
//	$GLOBALS[$key] = $val;
//	}
	
function pt_register() {
	$num_args = func_num_args();
	$vars = array();
	if ($num_args >= 2) {
		$method = strtoupper(func_get_arg(0));
		
		if (	($method != 'SESSION') && 
			($method != 'GET') && 
			($method != 'POST') && 
			($method != 'SERVER') && 
			($method != 'COOKIE') && 
			($method != 'FILES') && 
			($method != 'REQUEST') && 
			($method != 'ENV')) {
			die('The first argument of pt_register must be one of the following: 
				SESSION, GET, POST, SERVER, COOKIE, FILES, REQUEST or ENV');
			}
		
		$varname = "_{$method}";
		global ${$varname};
		
		for ($i = 1; $i < $num_args; $i++) {
			$parameter = func_get_arg($i);
			if (isset(${$varname}[$parameter])) {
		        	global $$parameter;
				$$parameter = ${$varname}[$parameter];
				}
			}
		
		} else {
	    		die('You must specify at least two arguments');
			}
	
	} /* fin pt_register() */

/* quand register_globals sera à off il faudra récupérer en automatique
	le strict minum : */
pt_register ("COOKIE", "PhpMyBibli-SESSID","PhpMyBibli-LOGIN","PhpMyBibli-SESSNAME","PhpMyBibli-LOGIN","PhpMyBibli-LANG");
pt_register ("SERVER", "REMOTE_ADDR","HTTP_USER_AGENT", "PHP_SELF", "REQUEST_URI", "SCRIPT_NAME");


