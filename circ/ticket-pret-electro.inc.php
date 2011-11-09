<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ticket-pret-electro.inc.php,v 1.1 2011-04-15 15:01:59 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$base_path/circ/pret_func.inc.php");
// liste des prêts et réservations

electronic_ticket($id_empr) ;

?>
