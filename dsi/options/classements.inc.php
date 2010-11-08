<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: classements.inc.php,v 1.4 2007-03-10 09:25:49 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo window_title($database_window_title.$msg[dsi_menu_title]);
print "<h1>".$msg[dsi_opt_class]."</h1>" ;
switch($suite) {
    case 'acces':
    	$clas = new classement($id_classement) ;
    	print $clas->show_form();  
		break;
    case 'add':
    	$clas = new classement(0) ;
    	print $clas->show_form();  
        break;
    case 'delete':
    	$clas = new classement($id_classement) ;
    	print $clas->delete();  
		break;
    case 'update':
    	$clas = new classement($id_classement) ;
    	$temp->id_classement=        $id_classement;		
		$temp->nom_classement=       $nom_classement;	
		$temp->type_classement=      $type_classement;	
    	print $clas->update($temp); 
        break;
    }

print pmb_bidi(dsi_list_classements ()) ;
