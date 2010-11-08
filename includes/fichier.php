<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fichier.php,v 1.1 2010-06-21 09:18:27 ngantier Exp $


// définition du minimum nécéssaire 
$base_path=".";                            
$base_auth = "FICHIER_AUTH";  
$base_title = "\$msg[onglet_fichier]";  
$prefix = "gestfic0";  
require_once ("$base_path/includes/init.inc.php");  

// modules propres à demandes.php ou à ses sous-modules
require("$include_path/templates/fichier.tpl.php");

print "<div id='att' style='z-Index:1000'></div>";
print $menu_bar;
print $extra;
print $extra_info;
if($use_shortcuts) {
	include("$include_path/shortcuts/circ.sht");
}

echo window_title($database_window_title.$msg['onglet_fichier'].$msg[1003].$msg[1001]);
print $fichier_layout;

switch($categ){
	case 'consult':
		include("$base_path/fichier/fichier_consult.inc.php");
		break;
	case 'saisie':
		include("$base_path/fichier/fichier_saisie.inc.php");
		break;
	case 'panier':
		include("$base_path/fichier/fichier_panier.inc.php");
		break;
	case 'gerer':
		include("$base_path/fichier/fichier_gestion.inc.php");
		break;
	default:
		include("$include_path/messages/help/$lang/module_fichier.txt");	
		break;
}

print $fichier_layout_end;
// pied de page
print $footer;

// deconnection MYSql
mysql_close($dbh);