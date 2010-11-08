<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: launch.inc.php,v 1.6 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo "<iframe name=\"ititre\" frameborder=\"0\" scrolling=\"yes\" width=\"100%\" height=\"300\" src=\"./admin/sauvegarde/launch.php\">";

?>