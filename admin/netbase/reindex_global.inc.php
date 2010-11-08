<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: reindex_global.inc.php,v 1.9 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path.'/classes/notice.class.php');
require_once($base_path.'/classes/noeuds.class.php');

// la taille d'un paquet de notices
$lot = REINDEX_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;
$jauge_size .= "px";

// initialisation de la borne de départ
if (!isset($start)) {
	$start=0;
	//remise a zero de la table au début
	mysql_query("delete from notices_global_index",$dbh);
	mysql_query("delete from notices_mots_global_index",$dbh);
}

$v_state=urldecode($v_state);

if (!$count) {
	$notices = mysql_query("SELECT count(1) FROM notices", $dbh);
	$count = mysql_result($notices, 0, 0);
}
	
print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_global"], ENT_QUOTES, $charset)."</h2>";

$NoIndex = 1;

$query = mysql_query("select notice_id from notices order by notice_id LIMIT $start, $lot");
if(mysql_num_rows($query)) {
		
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
	while($mesNotices = mysql_fetch_assoc($query)) {
		// Mise à jour de la table "notices_global_index"
    	notice::majNoticesGlobalIndex($mesNotices['notice_id']);
    	// Mise à jour de la table "notices_mots_global_index"
    	notice::majNoticesMotsGlobalIndex($mesNotices['notice_id']);             		   	
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
	<script type=\"text/javascript\"><!-- 
	setTimeout(\"document.forms['current_state'].submit()\",1000); 
	-->
	</script>";
} else {
	$spec = $spec - INDEX_GLOBAL;
	$not = mysql_query("SELECT count(1) FROM notices_global_index", $dbh);
	$compte = mysql_result($not, 0, 0);
	$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_global"], ENT_QUOTES, $charset)." :";
	$v_state .= $compte." ".htmlentities($msg["nettoyage_res_reindex_global"], ENT_QUOTES, $charset);
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