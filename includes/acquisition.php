<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: acquisition.php,v 1.4 2008-09-07 12:26:36 dbellamy Exp $


// définition du minimum nécéssaire 
$base_path=".";                            
$base_auth = "ACQUISITION_AUTH";  
$base_title = "\$msg[acquisition_menu_title]";    
require_once ("$base_path/includes/init.inc.php");  

// modules propres à acquisition.php ou à ses sous-modules
require("$include_path/templates/acquisition.tpl.php");
print "<div id='att' style='z-Index:1000'></div>";
print $menu_bar;
print $extra;
print $extra_info;
if($use_shortcuts) {
	include("$include_path/shortcuts/circ.sht");
}

print $acquisition_layout;

include("./acquisition/acquisition.inc.php");

print $acquisition_layout_end;

// pied de page
print $footer;

// deconnection MYSql
mysql_close($dbh);
?>