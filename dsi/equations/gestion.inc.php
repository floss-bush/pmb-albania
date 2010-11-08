<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: gestion.inc.php,v 1.12 2007-06-28 19:51:12 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

print "<h1>".$msg[dsi_equ_gestion]."</h1>" ;
switch($suite) {
    case 'acces':
    	$equation = new equation($id_equation) ;
    	print $equation->show_form();  
		break;
    case 'add':
    	$equation = new equation(0) ;
    	print $equation->show_form();  
        break;
    case 'transform':
    	if ($id_equation) {
    		$equation = new equation($id_equation) ;
			$equation->requete = stripslashes($requete);	
    		} else {    		
		    	$equation = new equation(0) ;
				$equation->num_classement=      1;	
				$equation->nom_equation=		"";	
				$equation->comment_equation=	"";	
				$equation->requete=	stripslashes($requete);	
				$equation->proprio_equation=	0;
    			}	
    	print $equation->show_form();  
        break;
    case 'delete':
    	$equation = new equation($id_equation) ;
    	$equation->delete();  
        print get_equation ($msg[dsi_equ_search], $msg[dsi_equ_search_nom], './dsi.php?categ=equations', stripslashes($form_cb));
		print pmb_bidi(dsi_list_equations($form_cb)) ;
		break;
    case 'update':
    	$equation = new equation($id_equation) ;
    	$temp->id_equation=         $id_equation;		
		$temp->num_classement=      $num_classement;	
		$temp->nom_equation=        $nom_equation;	
		$temp->comment_equation=	$comment_equation;
		$temp->requete=				$requete;	
		$temp->proprio_equation=	$proprio_equation;	
    	$equation->update($temp); 
    	print get_equation ($msg[dsi_equ_search], $msg[dsi_equ_search_nom], './dsi.php?categ=equations', stripslashes($nom_equation));
		print pmb_bidi(dsi_list_equations($nom_equation)) ; 
        break;
    case 'search':
		print get_equation ($msg[dsi_equ_search], $msg[dsi_equ_search_nom], './dsi.php?categ=equations', stripslashes($form_cb));
		print pmb_bidi(dsi_list_equations($form_cb)) ;
		break;
    default:
		echo window_title($database_window_title.$msg[dsi_menu_title]);
		print get_equation ($msg[dsi_equ_search], $msg[dsi_equ_search_nom], './dsi.php?categ=equations', stripslashes($form_cb));
		print pmb_bidi(dsi_list_equations($form_cb)) ;
        break;
    }

