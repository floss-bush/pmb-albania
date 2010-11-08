<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: get_info_expl.inc.php,v 1.1 2008-01-25 15:01:43 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/ajax_pret.class.php");

//cb_doc, id_empr;

// init de la class
$info_expl = new do_pret();
$status = $info_expl->get_info_expl($cb_doc);
ajax_http_send_response("$status","text/xml");

?>