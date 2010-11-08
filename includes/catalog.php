<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: catalog.php,v 1.19 2008-07-17 15:26:39 touraine37 Exp $

// définition du minimum nécéssaire
$base_path=".";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "\$msg[6]";
require_once ("$base_path/includes/init.inc.php");

// pour droit UNIQUE d'ajout de notices
if ((SESSrights & RESTRICTCATAL_AUTH) 
	&& ($categ!="create") 
	&& ($categ!="create_form") 
	&& ($categ!="update") 
	&& ($categ!="explnum_update") 
	&& ($categ!="explnum_create") 
	&& ($categ!="isbd") 
	) {
	$sub="";
	$categ="create";
	}

// modules propres à catalog.php ou à ses sous-modules
include("$include_path/templates/expl.tpl.php");
require("$include_path/templates/catalog.tpl.php");
print "<div id='att' style='z-Index:1000'></div>";
print $menu_bar;
print $extra;
print $extra_info;
if ($use_shortcuts) include("$include_path/shortcuts/circ.sht");
	

if ($categ!="caddie") {
	print $catalog_layout;
	}

include("./catalog/catalog.inc.php");

print $catalog_layout_end;
print $footer;

// deconnection MYSql
mysql_close($dbh);
