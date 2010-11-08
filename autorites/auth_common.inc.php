<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: auth_common.inc.php,v 1.6 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonctions communes à la gestion des autorités

if ( ! defined( 'AUTH_COMMON' ) ) {
  define( 'AUTH_COMMON', 1 );

function ps_form($action) {
	global $user_query;
	print str_replace ('!!action!!', $action, $user_query);
	}

} # fin de déclaration

