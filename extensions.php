<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: extensions.php,v 1.4 2009-07-22 08:40:16 touraine37 Exp $

// définition du minimum nécéssaire
$base_path=".";
$base_auth = "EXTENSIONS_AUTH";
$base_title = "\$msg[extensions_menu]";
require_once ("$base_path/includes/init.inc.php");


print "<div id='att' style='z-Index:1000'></div>";
print $menu_bar;
print $extra;
print $extra_info;
if ($use_shortcuts) include("$include_path/shortcuts/circ.sht");

// ATTENTION: la ligne suivante (21) et la ligne 27 (les /DIV correspondants) sont à reproduire dans le fichier inclus "extensions.inc.php"
// print "<div id='conteneur' class='$current_module'><div id='contenu'>";

// 
if (file_exists("$include_path/extensions.inc.php")) 
	include("$include_path/extensions.inc.php");

// print "</div></div>";

print $footer;

// deconnection MYSql
mysql_close($dbh);
