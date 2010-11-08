<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pointage_main.inc.php,v 1.2 2007-06-14 15:39:01 ngantier Exp $

require_once($class_path."/abts_pointage.class.php");
$pointage=new abts_pointage($serial_id);
$pointage->proceed();
?>
