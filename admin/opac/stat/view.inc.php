<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: view.inc.php,v 1.1 2009-05-20 15:19:31 kantin Exp $at

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/stat_view.class.php");

$stat_view = new stat_view($act);

$stat_view->proceed();


?>