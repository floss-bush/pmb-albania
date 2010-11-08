<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: relations1.inc.php,v 1.14 2010-09-14 05:56:53 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la taille d'un paquet de notices
$lot = SERIE_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;

// initialisation de la borne de départ
if(!isset($start)) $start=0;

$v_state=urldecode($v_state);

print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_clean_relations_ban"], ENT_QUOTES, $charset)."</h2>";

$query = mysql_query("DELETE bannettes FROM bannettes LEFT JOIN empr ON proprio_bannette = id_empr WHERE id_empr IS NULL AND proprio_bannette !=0");
$affected += mysql_affected_rows();
$query = mysql_query("DELETE equations FROM equations LEFT JOIN empr ON proprio_equation = id_empr WHERE id_empr IS NULL AND proprio_equation !=0 ");
$affected += mysql_affected_rows();
$query = mysql_query("DELETE bannette_equation FROM bannette_equation LEFT JOIN bannettes ON num_bannette = id_bannette WHERE id_bannette IS NULL ");
$affected += mysql_affected_rows();
$query = mysql_query("DELETE bannette_equation FROM bannette_equation LEFT JOIN equations on num_equation=id_equation WHERE id_equation is null");
$affected += mysql_affected_rows();
$query = mysql_query("DELETE bannette_abon FROM bannette_abon LEFT JOIN empr on num_empr=id_empr WHERE id_empr is null");
$affected += mysql_affected_rows();
$query = mysql_query("DELETE bannette_abon FROM bannette_abon LEFT JOIN bannettes ON num_bannette=id_bannette WHERE id_bannette IS NULL ");
$affected += mysql_affected_rows();
$query = mysql_query("delete caddie_content from caddie join caddie_content on (idcaddie=caddie_id and type='NOTI') left join notices on object_id=notice_id where notice_id is null");
$affected = mysql_affected_rows();

$query = mysql_query("delete bannette_contenu FROM bannette_contenu left join notices on num_notice=notice_id where notice_id is null ");
$affected += mysql_affected_rows();

$query = mysql_query("delete bannette_contenu FROM bannette_contenu left join bannettes on num_bannette=id_bannette where id_bannette is null ");
$affected += mysql_affected_rows();

$query = mysql_query("DELETE avis FROM avis LEFT JOIN notices ON num_notice=notice_id WHERE notice_id IS NULL ");

$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_suppr_relations"], ENT_QUOTES, $charset)." : ";
$v_state .= $affected." ".htmlentities($msg["nettoyage_res_suppr_relations_ban"], ENT_QUOTES, $charset);
$opt = mysql_query('OPTIMIZE TABLE bannette_contenu');
// mise à jour de l'affichage de la jauge
print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>
  			<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>
 			<div align='center'>100%</div>";
print "
	<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		<input type='hidden' name='pass2' value=\"2\">	
	</form>
	<script type=\"text/javascript\"><!--
		document.forms['process_state'].submit();
		-->
	</script>";
