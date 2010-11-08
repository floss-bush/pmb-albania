<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: misc.inc.php,v 1.9 2007-09-13 16:10:27 jlesaint Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$func_format=array(
"verifdate"=>"ajax_verif_date",
);

if($func_format[$fname]) $ret = $func_format[$fname] ( );

/***********************************************
 *Fonction ajax_verif_date
 *	Check la date saisie en format local 
 *input :
 *	- $p1, date envoyée par POST or GET metod
 *Output:
 * retourne la date ou un code d'erreur http
 */	
function ajax_verif_date() {
	global $msg,$p1;
	$mysql_date= extraitdate($p1);
	$rqt= "SELECT DATE_ADD('" .$mysql_date. "', INTERVAL 0 DAY)";
	if($result=mysql_query($rqt))
		if($row = mysql_fetch_row($result))	
			if($row[0]){
				ajax_http_send_response($row[0]);
				return;
			}
	ajax_http_send_error('400',$msg['error_message_invalid_date']);
}


?>