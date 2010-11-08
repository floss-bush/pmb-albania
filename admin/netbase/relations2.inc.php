<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: relations2.inc.php,v 1.9 2010-09-14 05:56:53 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la taille d'un paquet de notices
$lot = SERIE_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;

// initialisation de la borne de départ
if(!isset($start)) $start=0;

$v_state=urldecode($v_state);

print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_clean_relations_cat"], ENT_QUOTES, $charset)."</h2>";

$query = mysql_query("delete from notices_custom_values where notices_custom_champ not in (select idchamp from notices_custom)");
$affected = mysql_affected_rows();
$query = mysql_query("delete from expl_custom_values where expl_custom_champ not in (select idchamp from expl_custom)");
$affected = mysql_affected_rows();
$query = mysql_query("DELETE empr_custom_values FROM empr_custom_values LEFT JOIN empr ON id_empr=empr_custom_origine WHERE id_empr IS NULL ");
$affected = mysql_affected_rows();
$query = mysql_query("delete from empr_custom_values where empr_custom_champ not in (select idchamp from empr_custom)");
$affected = mysql_affected_rows();

$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_suppr_relations"], ENT_QUOTES, $charset)." : ";
$v_state .= $affected." ".htmlentities($msg["nettoyage_res_suppr_relations_cat"], ENT_QUOTES, $charset);
$opt = mysql_query('OPTIMIZE TABLE notices_categories');
// mise à jour de l'affichage de la jauge
print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>
  			<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>
 			<div align='center'>100%</div>";
print "
	<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		<input type='hidden' name='pass2' value=\"3\">	
	</form>
	<script type=\"text/javascript\"><!--
		document.forms['process_state'].submit();
		-->
	</script>";
