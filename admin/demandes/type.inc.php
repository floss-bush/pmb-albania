<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: type.inc.php,v 1.1 2009-10-01 13:29:24 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/liste_simple.class.php");

$dmd_type = new demandes_types("demandes_type","id_type","libelle_type",$id_liste);
$dmd_type->proceed($act);
?>