<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: view_serial.inc.php,v 1.12 2009-05-16 11:12:03 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page d'affichage des éléments bulletinés d'un périodique en recherche réservation

require_once("$class_path/serials.class.php");

$serial = new serial($serial_id);
print pmb_bidi("<h3>".$msg[1150]." : ".$serial->tit1."</h3>");

$requete = "select bulletin_id from bulletins WHERE bulletin_notice=$serial_id ORDER BY bulletin_id DESC"; 
$myQuery = mysql_query($requete, $dbh);

if(mysql_num_rows($myQuery)) {
	
	while($bulletin = mysql_fetch_object($myQuery)) {
		
		$entry = new bulletinage($bulletin->bulletin_id);

		if(sizeof($entry->expl)) {
			
			$link_bulletin_temp = str_replace('!!id!!', $bulletin->bulletin_id, $link_bulletin );
			if ($link_bulletin_temp) 
				print pmb_bidi("<br /><b><a href='$link_bulletin_temp'>".$entry->header."</a>");
			else print pmb_bidi("<br /><b>".$entry->header);
			
			print pmb_bidi("</b>&nbsp;".sizeof($entry->expl)."&nbsp;exemplaire(s)");
		} else {
			print pmb_bidi('<br />'.$entry->header);
		}
	}
}
