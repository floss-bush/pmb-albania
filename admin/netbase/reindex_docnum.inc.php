<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: reindex_docnum.inc.php,v 1.1 2009-06-12 10:13:07 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");
require_once("$class_path/indexation_docnum.class.php");


// la taille d'un paquet de notices
$lot = REINDEX_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;
$jauge_size .= "px";

// initialisation de la borne de départ
if (!isset($start)) $start=0;

$v_state=urldecode($v_state);

if (!$count) {
	$explnum = mysql_query("SELECT count(1) FROM explnum", $dbh);
	$count = mysql_result($explnum, 0, 0);
}

print "<br /><br /><h2 align='center'>".htmlentities($msg["docnum_reindexation"], ENT_QUOTES, $charset)."</h2>";

$requete = "select explnum_id as id from explnum order by id LIMIT $start, $lot";
$res_explnum = mysql_query($requete,$dbh);
if(mysql_num_rows($res_explnum)) {
	
	// définition de l'état de la jauge
	$state = floor($start / ($count / $jauge_size));
	$state .= "px";
	// mise à jour de l'affichage de la jauge
	print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge' width='100%'>";
	print "<img src='../../images/jauge.png' width='$state' height='16px'></td></tr></table>";
		
	// calcul pourcentage avancement
	$percent = floor(($start/$count)*100);
	
	// affichage du % d'avancement et de l'état
	print "<div align='center'>$percent%</div>";
	
	while(($explnum = mysql_fetch_object($res_explnum))){
		
		$index = new indexation_docnum($explnum->id);
		$index->indexer();
	}
	
	$next = $start + $lot;
	print "
		<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		<input type='hidden' name='start' value=\"$next\">
		<input type='hidden' name='count' value=\"$count\">
		</form>
		<script type=\"text/javascript\"><!-- 
		setTimeout(\"document.forms['current_state'].submit()\",1000); 
		-->
		</script>";
} else {
	$spec = $spec - INDEX_DOCNUM;
	$not = mysql_query("SELECT count(1) FROM explnum", $dbh);
	$compte = mysql_result($not, 0, 0);
	$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg['docnum_reindexation'], ENT_QUOTES, $charset)." : ";
	$v_state .= $compte." ".htmlentities($msg['docnum_reindex_expl'], ENT_QUOTES, $charset);
	print "
		<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		</form>
		<script type=\"text/javascript\"><!--
			document.forms['process_state'].submit();
			-->
		</script>";
}

?>