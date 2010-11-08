<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: publishers.inc.php,v 1.13 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la taille d'un paquet de notices
$lot = PUBLISHER_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;

// initialisation de la borne de départ
if(!isset($start)) $start=0;

$v_state=urldecode($v_state);

print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_suppr_editeurs"], ENT_QUOTES, $charset)."</h2>";

// creation table tempo contenant les id des publishers utilisés
$query = mysql_query("create temporary table tmppub as select distinct ed1_id as edid from notices  where ed1_id!=0 union select distinct ed2_id as edid from notices where ed2_id!=0");
$query = mysql_query("alter table tmppub add index (edid)");

// supp des pub non utilisés dans les collections, sous-collections et notices !
$query = mysql_query("delete publishers from publishers left join tmppub on ed_id=edid left join sub_collections on ed_id=sub_coll_parent left join collections on ed_id=collection_parent where sub_coll_parent is null and collection_parent is null and edid is null ");
$affected = mysql_affected_rows();

$spec = $spec - CLEAN_PUBLISHERS;
$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_suppr_editeurs"], ENT_QUOTES, $charset)." : ";
$v_state .= $affected." ".htmlentities($msg["nettoyage_res_suppr_editeurs"], ENT_QUOTES, $charset);
$opt = mysql_query('OPTIMIZE TABLE publishers');
// mise à jour de l'affichage de la jauge
print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>
  			<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>
 			<div align='center'>100%</div>";
print "
	<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
	</form>
	<script type=\"text/javascript\"><!--
		document.forms['process_state'].submit();
		-->
		</script>";
