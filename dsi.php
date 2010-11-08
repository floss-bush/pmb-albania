<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: dsi.php,v 1.1 2005-05-18 18:32:08 touraine37 Exp $

// définition du minimum nécéssaire 
$base_path=".";                            
$base_auth = "DSI_AUTH";  
$base_title = "\$msg[dsi_menu_title]";    
require_once ("$base_path/includes/init.inc.php");  

// modules propres à autorites.php ou à ses sous-modules
require("$include_path/templates/dsi.tpl.php");

print $menu_bar;
print $extra;
print $extra_info;
if($use_shortcuts) {
	include("$include_path/shortcuts/circ.sht");
}

print $dsi_layout;

include("./dsi/main.inc.php");

print $dsi_layout_end;

// pied de page
print $footer;

// deconnection MYSql
mysql_close($dbh);
