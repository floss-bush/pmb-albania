<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: clean_pret_temp.inc.php,v 1.2 2010-11-17 15:12:41 ngantier Exp $


function clean_pret_temp() {
	global $pmb_pret_timeout_temp,$_SERVER;
	global $dbh;
	
	if( $pmb_pret_timeout_temp) {
		$rqt="delete from pret where pret_temp != '' and pret_date > DATE_SUB( sysdate( ) , INTERVAL '$pmb_pret_timeout_temp' MINUTE )";
		mysql_query($rqt, $dbh);	
	}	
	$rqt="delete from pret where pret_temp = '".$_SERVER['REMOTE_ADDR']."' and  pret_temp != '' ";
	mysql_query($rqt, $dbh);
}