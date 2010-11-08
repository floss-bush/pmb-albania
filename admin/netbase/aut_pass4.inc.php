<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: aut_pass4.inc.php,v 1.8 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la taille d'un paquet de notices
$lot = AUTHOR_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;

// initialisation de la borne de départ
if(!isset($start)) $start=0;

$v_state=urldecode($v_state);

if(!$count) {
	$notices = mysql_query("SELECT count(1) FROM responsability where responsability_author<>0 ", $dbh);
	$count = mysql_result($notices, 0, 0) ;
	}

print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_responsabilites"], ENT_QUOTES, $charset)." : 2</h2>";

$query = mysql_query("delete responsability from responsability left join authors on responsability_author=author_id where author_id is null ");
$affected = mysql_affected_rows();

$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_responsabilites"], ENT_QUOTES, $charset)." : ";
$v_state .= $affected." ".htmlentities($msg["nettoyage_res_responsabilites"], ENT_QUOTES, $charset);
$opt = mysql_query('OPTIMIZE TABLE authors');
// mise à jour de l'affichage de la jauge
$spec = $spec - CLEAN_AUTHORS;
print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>
    	<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>
  		<div align='center'>100%</div>";
print "
	<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		<input type='hidden' name='pass2' value=\"0\">			
	</form>
	<script type=\"text/javascript\"><!--
		document.forms['process_state'].submit();
		-->
		</script>";
