<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: modele_main.inc.php,v 1.1 2007-05-07 10:29:08 gueluneau Exp $

require_once($class_path."/abts_modeles.class.php");

$modele=new abts_modele($modele_id);
if (!$modele_id) $modele->set_perio($serial_id);
$modele->proceed();

?>
