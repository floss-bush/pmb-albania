<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collstate_delete.inc.php,v 1.1 2009-03-10 08:31:00 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/collstate.class.php");
$collstate = new collstate($id);
$collstate->delete();
$view="collstate";
include('./catalog/serials/serial_view.inc.php');

?>