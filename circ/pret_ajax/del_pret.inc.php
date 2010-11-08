<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: del_pret.inc.php,v 1.1 2007-09-14 14:55:42 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/ajax_pret.class.php");


//$id_expl;

// init de la class
$pret = new do_pret();
$status = $pret->del_pret( $id_expl);
ajax_http_send_response("$status","text/xml");


?>