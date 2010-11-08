<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serial_view.inc.php,v 1.10 2009-12-10 14:37:17 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// résultat de recherche pour gestion des périodiques
echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[show], $serial_header);
		
//print $serial_access_form;

if($serial_id) {
	$myQuery = mysql_query("SELECT * FROM notices WHERE notice_id=$serial_id ", $dbh);
}

if($serial_id && mysql_num_rows($myQuery)) {
	$sort_children = 1;
	show_serial_info($serial_id, $page, $nbr_lignes);
} else {
	print "<div class=\"row\"><div class=\"msg-perio\">".$msg['catalog_serie_impossible_aff']."</div></div>";
}


