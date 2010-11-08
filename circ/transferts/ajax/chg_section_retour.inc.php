<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: chg_section_retour.inc.php,v 1.1 2008-06-12 08:30:55 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$rqt = 	"UPDATE exemplaires". 
		" SET expl_section=". $param . 
		" WHERE expl_id=" . $idexpl; 
mysql_query ( $rqt );

ajax_http_send_response($param,"text/xml");

?>