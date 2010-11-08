<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.6 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusions principales
require_once("$include_path/templates/express.tpl.php");

if (!$id_empr) {
	// pas d'id empr, quelque chose ne va pas
	error_message($msg[350], $msg[54], 1 , './circ.php');
} else {
	// récupération nom emprunteur
	$requete = "SELECT empr_nom, empr_prenom, empr_cb FROM empr WHERE id_empr=$id_empr LIMIT 1";
	$result = @mysql_query($requete, $dbh);
	if(!mysql_num_rows($result)) {
		// pas d'emprunteur correspondant, quelque chose ne va pas
		error_message($msg[350], $msg[54], 1 , './circ.php');
	} else {
		$empr = mysql_fetch_object($result);
		$name = $empr->empr_prenom;
		$name ? $name .= ' '.$empr->empr_nom : $name = $empr->empr_nom;
		echo window_title($database_window_title.$name.$msg['pret_express_wtit']);
		$layout_begin = preg_replace('/!!nom_lecteur!!/m', $name, $layout_begin);
		$layout_begin = preg_replace('/!!cb_lecteur!!/m', $empr->empr_cb, $layout_begin);
		print pmb_bidi($layout_begin);
	}
}
