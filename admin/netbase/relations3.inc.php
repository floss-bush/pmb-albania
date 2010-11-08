<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: relations3.inc.php,v 1.8 2010-09-14 05:56:53 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la taille d'un paquet de notices
$lot = SERIE_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;

// initialisation de la borne de départ
if(!isset($start)) $start=0;

$v_state=urldecode($v_state);

print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_clean_relations_pan"], ENT_QUOTES, $charset)."</h2>";

$query = mysql_query("DELETE notices_custom_values FROM notices_custom_values LEFT JOIN notices ON notice_id=notices_custom_origine WHERE notice_id IS NULL ");
$affected += mysql_affected_rows();
$query = mysql_query("delete notices from notices left join bulletins on num_notice=notice_id where num_notice is null and niveau_hierar='2' and niveau_biblio='b' ");
$affected += mysql_affected_rows();
$query = mysql_query("delete notices_titres_uniformes from notices_titres_uniformes left join notices on ntu_num_notice=notice_id where notice_id is null ");
$affected += mysql_affected_rows();
$query = mysql_query("delete notices_categories from notices_categories left join notices on notcateg_notice=notice_id where notice_id is null");
$affected = mysql_affected_rows();
$query = mysql_query("delete responsability from responsability left join notices on responsability_notice=notice_id where notice_id is null");
$affected = mysql_affected_rows();
$query = mysql_query("delete responsability from responsability left join authors on responsability_author=author_id where author_id is null");
$affected = mysql_affected_rows();


$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_suppr_relations"], ENT_QUOTES, $charset)." : ";
$v_state .= $affected." ".htmlentities($msg["nettoyage_res_suppr_relations_pan"], ENT_QUOTES, $charset);
$opt = mysql_query('OPTIMIZE TABLE notices_categories');
// mise à jour de l'affichage de la jauge
print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>
  			<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>
 			<div align='center'>100%</div>";
print "
	<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		<input type='hidden' name='pass2' value=\"4\">	
	</form>
	<script type=\"text/javascript\"><!--
		document.forms['process_state'].submit();
		-->
	</script>";
