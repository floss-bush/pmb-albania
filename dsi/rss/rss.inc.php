<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rss.inc.php,v 1.7 2008-09-16 21:52:20 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

print "<h1>".$msg[dsi_rss_titre]."</h1>" ;
switch($suite) {
    case 'acces':
    	$flux = new rss_flux($id_rss_flux) ;
    	print $flux->show_form();  
		break;
    case 'add':
    	$flux = new rss_flux(0) ;
    	print $flux->show_form();  
        break;
    case 'delete':
    	$flux = new rss_flux($id_rss_flux) ;
    	print $flux->delete();  
        print get_flux ($msg[dsi_flux_search], $msg[dsi_flux_search_nom], './dsi.php?categ=fluxrss&sub=', stripslashes($form_cb));
		print pmb_bidi(dsi_list_flux_info($form_cb, 0)) ;
		break;
    case 'update':
    	$flux = new rss_flux($id_rss_flux) ;

		$temp->id_rss_flux         	=$id_rss_flux ;
		$temp->nom_rss_flux        	=$nom_rss_flux ;
		$temp->link_rss_flux       	=$link_rss_flux ;
		$temp->descr_rss_flux      	=$descr_rss_flux ;
		$temp->lang_rss_flux       	=$lang_rss_flux ;
		$temp->copy_rss_flux       	=$copy_rss_flux ;
		$temp->editor_rss_flux     	=$editor_rss_flux ;
		$temp->webmaster_rss_flux  	=$webmaster_rss_flux ;
		$temp->ttl_rss_flux        	=$ttl_rss_flux ;
		$temp->img_url_rss_flux    	=$img_url_rss_flux ;
		$temp->img_title_rss_flux  	=$img_title_rss_flux ;
		$temp->img_link_rss_flux   	=$img_link_rss_flux ;
		$temp->format_flux         	=$format_flux ;
		$temp->export_court_flux	=$export_court_flux ? 1 : 0;

		if (!$paniers) $paniers = array();
		if (!$bannettes) $bannettes = array();
    	$temp->num_paniers=			$paniers;
    	$temp->num_bannettes=		$bannettes;
		$flux->update($temp); 

    	print get_flux ($msg[dsi_flux_search], $msg[dsi_flux_search_nom], './dsi.php?categ=fluxrss&sub=', stripslashes($nom_rss_flux));
		print pmb_bidi(dsi_list_flux_info($form_cb, $id_rss_flux)) ;
        break;
    case 'search':
        print get_flux ($msg[dsi_flux_search], $msg[dsi_flux_search_nom], './dsi.php?categ=fluxrss&sub=', stripslashes($form_cb));
		print pmb_bidi(dsi_list_flux_info($form_cb, $id_rss_flux)) ;
		break;
    default:
		echo window_title($database_window_title.$msg[dsi_menu_flux]);
		print get_flux ($msg[dsi_flux_search], $msg[dsi_flux_search_nom], './dsi.php?categ=fluxrss&sub=', stripslashes($form_cb));
		print pmb_bidi(dsi_list_flux_info($form_cb, $id_rss_flux)) ;
        break;
    }

