<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: chg_date_retour.inc.php,v 1.1 2008-06-04 14:54:25 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/transfert.class.php");

transfert::change_date_retour($id,$dt);

ajax_http_send_response("1","text/xml");

?>