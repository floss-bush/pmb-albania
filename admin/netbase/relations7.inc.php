<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: relations7.inc.php,v 1.9 2010-09-14 05:56:53 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la taille d'un paquet de notices
$lot = SERIE_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;

// initialisation de la borne de départ
if(!isset($start)) $start=0;

$v_state=urldecode($v_state);
print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_clean_relations_pan3"], ENT_QUOTES, $charset)."</h2>";

$query = mysql_query("delete caddie_content from caddie join caddie_content on (idcaddie=caddie_id and type='BULL') left join bulletins on object_id=bulletin_id where bulletin_id is null");
$affected = mysql_affected_rows();

$query = mysql_query("delete notices_langues from notices_langues left join notices on num_notice=notice_id where notice_id is null");
$affected += mysql_affected_rows();

$query = mysql_query("delete abo_liste_lecture from abo_liste_lecture left join empr on num_empr=id_empr where id_empr is null");
$affected_ll += mysql_affected_rows();

$query = mysql_query("delete abo_liste_lecture from abo_liste_lecture left join opac_liste_lecture on num_liste=id_liste where id_liste is null");
$affected_ll += mysql_affected_rows();

$query = mysql_query("delete opac_liste_lecture from opac_liste_lecture left join empr on num_empr=id_empr where id_empr is null");
$affected_ll += mysql_affected_rows();

$spec = $spec - CLEAN_RELATIONS;
$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_suppr_relations"], ENT_QUOTES, $charset)." : ";
$v_state .= $affected." ".htmlentities($msg["nettoyage_res_suppr_relations_pan3"], ENT_QUOTES, $charset);
$opt = mysql_query('OPTIMIZE TABLE caddie_content');
// mise à jour de l'affichage de la jauge
print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>
  			<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>
 			<div align='center'>100%</div>";
print "
	<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		<input type='hidden' name='pass2' value=\"8\">	
	</form>
	<script type=\"text/javascript\"><!--
		document.forms['process_state'].submit();
		-->
	</script>";
