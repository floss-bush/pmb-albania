<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.10 2009-09-01 05:30:30 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$admin_layout = str_replace('!!menu_sous_rub!!', $msg[329], $admin_layout);
print $admin_layout;

?>

<br /><br /><iframe name="ititre" frameborder="0" scrolling="auto" width="600" height="700" src="./admin/netbase/clean.php">
