<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: clean_expl.inc.php,v 1.16 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/parametres_perso.class.php");
require_once($class_path."/audit.class.php");

// la taille d'un paquet de notices
$lot = NOEXPL_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;

// initialisation de la borne de départ
if(!isset($start)) $start=0;

$v_state=urldecode($v_state);

print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_suppr_notices"], ENT_QUOTES, $charset)."</h2>";

// La routine ne nettoie pour l'instant que les monographies
$query = mysql_query("delete notices  
	FROM notices left join exemplaires on expl_notice=notice_id  
		left join explnum on explnum_notice=notice_id 
		left join notices_relations NRN on NRN.num_notice=notice_id  
		left join notices_relations NRL on NRL.linked_notice=notice_id 
	WHERE niveau_biblio='m' AND niveau_hierar='0' and explnum_notice is null and expl_notice is null and NRN.num_notice is null and NRL.linked_notice is null");
$affected = mysql_affected_rows();
 
$spec = $spec - CLEAN_NOTICES;
$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg[nettoyage_suppr_notices], ENT_QUOTES, $charset);
$v_state .= $affected." ".htmlentities($msg["nettoyage_res_suppr_notices"], ENT_QUOTES, $charset);
$opt = mysql_query('OPTIMIZE TABLE notices');
// mise à jour de l'affichage de la jauge
print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>
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

