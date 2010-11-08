<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abonnement_main.inc.php,v 1.1 2007-06-08 13:33:00 ngantier Exp $

require_once($class_path."/abts_abonnements.class.php");

$abonnement=new abts_abonnement($abt_id);
if (!$abt_id) $abonnement->set_perio($serial_id);
$abonnement->proceed();

?>
