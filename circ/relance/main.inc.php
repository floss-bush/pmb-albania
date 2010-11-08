<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.3 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch ($sub) {
	case "todo":
		require_once("./circ/relance/relance.inc.php");
		break;
	case "recouvr":
		require_once("./circ/relance/recouvr.inc.php");
		break;
}

?>