<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: confirm_pret.inc.php,v 1.2 2009-07-21 09:36:35 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/ajax_pret.class.php");
// init de la class
$expl = new do_pret();
	
if ( is_array($id_expl)) {
	foreach($id_expl as $id) {

		$status= $expl->confirm_pret($id_empr, $id);		
	}
	ajax_http_send_response("$status","text/xml");	
} else {
	$status = $expl->confirm_pret($id_empr, $id_expl);
	ajax_http_send_response("$status","text/xml");
}
?>