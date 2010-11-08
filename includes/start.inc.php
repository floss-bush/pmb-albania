<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: start.inc.php,v 1.10 2008-11-08 07:15:33 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// paramêtres par défaut de l'applic :
// ce système crée des variables de nom type_param_sstype_param et de contenu valeur_param à partir de la table parametres

// prevents direct script access
if(preg_match('/start\.inc\.php/', $REQUEST_URI)) {
	include('./forbidden.inc.php'); forbidden();
}

/* param par défaut */	
$requete_param = "SELECT type_param, sstype_param, valeur_param FROM parametres ";
$res_param = mysql_query($requete_param, $dbh);
while ($field_values = mysql_fetch_row ( $res_param )) {
	$field = $field_values[0]."_".$field_values[1] ;
	global $$field;
	$$field = $field_values[2];
}
