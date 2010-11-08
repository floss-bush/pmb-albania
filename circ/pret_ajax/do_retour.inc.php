<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: do_retour.inc.php,v 1.2 2008-02-12 11:07:52 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/ajax_retour_class.php");

// init de la class
$retour = new retour();
$status = $retour->do_retour($cb_doc);

ajax_http_send_response("$status","text/xml");

?>