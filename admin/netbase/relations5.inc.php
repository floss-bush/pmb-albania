<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: relations5.inc.php,v 1.12 2011-03-17 16:50:41 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la taille d'un paquet de notices
$lot = SERIE_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;

// initialisation de la borne de départ
if(!isset($start)) $start=0;

$v_state=urldecode($v_state);

print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_clean_relations_pan2"], ENT_QUOTES, $charset)."</h2>";

$query = mysql_query("delete caddie_content from caddie join caddie_content on (idcaddie=caddie_id and type='EXPL') left join exemplaires on object_id=expl_id where expl_id is null");
$affected = mysql_affected_rows();
$query = mysql_query("delete explnum from explnum left join notices on notice_id=explnum_notice where notice_id is null and explnum_bulletin=0");
$affected = mysql_affected_rows();
$query = mysql_query("delete explnum from explnum left join bulletins on bulletin_id=explnum_bulletin where bulletin_id is null and explnum_notice=0 ");
$affected = mysql_affected_rows();
$query = mysql_query("delete acces_res_1 from acces_res_1 left join notices on res_num=notice_id where notice_id is null ");
if($query) $affected = mysql_affected_rows();

$query = mysql_query("delete acces_res_2 from acces_res_2 left join notices on res_num=notice_id where notice_id is null ");
if($query) $affected = mysql_affected_rows();

$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_suppr_relations"], ENT_QUOTES, $charset)." : ";
$v_state .= $affected." ".htmlentities($msg["nettoyage_res_suppr_relations_pan2"], ENT_QUOTES, $charset);
$opt = mysql_query('OPTIMIZE TABLE notices_categories');
// mise à jour de l'affichage de la jauge
print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>
  			<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>
 			<div align='center'>100%</div>";
print "
	<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		<input type='hidden' name='pass2' value=\"6\">	
	</form>
	<script type=\"text/javascript\"><!--
		document.forms['process_state'].submit();
		-->
	</script>";
