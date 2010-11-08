<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: autorites.php,v 1.10 2007-04-27 13:40:01 gueluneau Exp $

// définition du minimum nécéssaire 
$base_path=".";                            
$base_auth = "AUTORITES_AUTH";  
$base_title = "\$msg[132]";    
require_once ("$base_path/includes/init.inc.php");  

// modules propres à autorites.php ou à ses sous-modules
require("$include_path/templates/autorites.tpl.php");
print "<div id='att' style='z-Index:1000'></div>";
print $menu_bar;
print $extra;
print $extra_info;
if($use_shortcuts) {
	include("$include_path/shortcuts/circ.sht");
}

print $autorites_layout;

include("./autorites/autorites.inc.php");

print $autorites_layout_end;

// pied de page
print $footer;

// deconnection MYSql
mysql_close($dbh);
