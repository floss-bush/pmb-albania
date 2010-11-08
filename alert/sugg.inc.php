<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sugg.inc.php,v 1.4 2009-05-16 11:12:02 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$temp_aff = alerte_sugg() ;
if ($temp_aff) $aff_alerte.= "<ul>".$msg["alerte_suggestion"].$temp_aff."</ul>" ;

function alerte_sugg () {
	global $dbh ;
	global $msg;
	global $opac_show_suggest;
	
	if (!$opac_show_suggest) return "";			
	// comptage des tags à valider
	$sql = " SELECT 1 FROM suggestions where statut=1 limit 1";
	$req = mysql_query($sql) or die ($msg["err_sql"]."<br />".$sql."<br />".mysql_error());
	$nb_limite = mysql_num_rows($req) ;
	if (!$nb_limite) return "" ;
	else return "<li><a href='./acquisition.php?categ=sug&action=list&statut=1' target='_parent'>$msg[alerte_suggestion_traiter]</a></li>" ;
}

