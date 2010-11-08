<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: recouvr.inc.php,v 1.3 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Gestion des recouvrements

//Titre de la page
echo "<h1>".$msg["relance_menu"]."&nbsp;:&nbsp;".$msg["relance_recouvrement"]."</h1>";

switch ($act) {
	case "recouvr_reader":
		require_once("recouvr_reader.inc.php");
		break;
	case "recouvr_liste":
	default:
		require_once("recouvr_liste.inc.php");
		break;
}
?>