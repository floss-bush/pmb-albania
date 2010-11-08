<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: retour_change_loc.inc.php,v 1.1 2008-06-04 14:54:25 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/transfert.class.php");

$trans = new transfert();

//supprime le transfert
$trans->retour_exemplaire_supprime_transfert( $idexpl, $param );

//change la localisation de l'exemplaire
$num = $trans->retour_exemplaire_change_localisation( $idexpl );

ajax_http_send_response($num,"text/xml");

?>