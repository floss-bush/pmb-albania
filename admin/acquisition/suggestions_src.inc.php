<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions_src.inc.php,v 1.1 2009-07-31 14:37:09 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/suggestion_source.class.php");

$sug_src = new suggestion_source($id_src);
$sug_src->proceed($act);
?>