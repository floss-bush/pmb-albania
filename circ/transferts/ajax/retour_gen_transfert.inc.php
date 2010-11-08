<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: retour_gen_transfert.inc.php,v 1.1 2008-06-04 14:54:25 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/transfert.class.php");

$trans = new transfert();

//annule changement de localisation
$trans->retour_exemplaire_restaure_localisation( $idexpl , $param );

//genere le transfert
$num = $trans->retour_exemplaire_genere_transfert_retour( $idexpl );

ajax_http_send_response($num,"text/xml");

?>