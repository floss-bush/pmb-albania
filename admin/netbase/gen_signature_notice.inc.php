<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: gen_signature_notice.inc.php,v 1.2 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/notice_doublon.class.php");

$sign= new notice_doublon();

// la taille d'un paquet de notices
$lot = NOEXPL_PAQUET_SIZE*10; // defini dans ./params.inc.php
// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;

// initialisation de la borne de départ
if(!isset($start)) $start=0;

$v_state=urldecode($v_state);

if(!$count) {
	$notices = mysql_query("SELECT count(1) FROM notices", $dbh);
	$count = mysql_result($notices, 0, 0);
}

print "<br /><br /><h2 align='center'>".htmlentities($msg["gen_signature_notice"], ENT_QUOTES, $charset)."</h2>";

$query = mysql_query("SELECT notice_id FROM notices LIMIT $start, $lot");
if(mysql_num_rows($query)) {

    // définition de l'état de la jauge
    $state = floor($start / ($count / $jauge_size));

    // mise à jour de l'affichage de la jauge
    print "<table border='0' align='center' width='$jauge_size' cellpadding='0' border='0'><tr><td class='jauge'>";
    print "<img src='../../images/jauge.png' width='$state' height='16'></td></tr></table>";

    // calcul pourcentage avancement
    $percent = floor(($start/$count)*100);

    // affichage du % d'avancement et de l'état
    print "<div align='center'>$percent%</div>";
   	while ($row = mysql_fetch_row($query) )  { 		
   		$val= $sign->gen_signature($row[0]);
		mysql_query("update notices set signature='$val' where notice_id=".$row[0], $dbh);		
   	}
   	mysql_free_result($query);
	$next = $start + $lot;
 	print "
	<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		<input type='hidden' name='start' value=\"$next\">
		<input type='hidden' name='count' value=\"$count\">
	</form>
	<script type=\"text/javascript\">
	<!--
		document.forms['current_state'].submit();
	-->
	</script>";
} else {
	$spec = $spec - GEN_SIGNATURE_NOTICE;
	$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["gen_signature_notice_status"], ENT_QUOTES, $charset);
	$v_state .= $count." ".htmlentities($msg["gen_signature_notice_status_end"], ENT_QUOTES, $charset);
	$opt = mysql_query('OPTIMIZE TABLE notices');
	// mise à jour de l'affichage de la jauge
	print "
	<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>
	<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>
	<div align='center'>100%</div>";

	print "
	<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
	</form>
	<script type=\"text/javascript\">
	<!--
		document.forms['process_state'].submit();
	-->
	</script>";	
}	