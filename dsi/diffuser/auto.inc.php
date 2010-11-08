<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: auto.inc.php,v 1.20 2010-07-06 09:20:16 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

print "<h1>".$msg[dsi_dif_auto]."</h1>" ;

// en visualisation, possibilité de supprimer des notices à la demande
if ($suite=="suppr_notice") {
	$bannette = new bannette($id_bannette) ;
	$bannette->suppr_notice($num_notice);
	// on réaffiche la bannette de laquelle on a supprimé une notice
	$liste_bannette[] = $id_bannette ;
	$suite = "visualiser";
	}
// récupérer les bannettes cochées
if (!$liste_bannette) $liste_bannette = array() ;
for ($iba=0 ; $iba < sizeof($liste_bannette) ; $iba++) {
	$bannette = new bannette($liste_bannette[$iba]) ;
	switch($suite) {
    	case "vider" :
			$action_diff_aff .= $msg['dsi_dif_vidage'].": ".$bannette->nom_bannette."<br />" ; 
			$bannette->vider();
			break ;
		case "remplir" :
			$action_diff_aff .= $msg['dsi_dif_remplissage'].": ".$bannette->nom_bannette ; 
			$action_diff_aff .= $bannette->remplir();
			$bannette->purger();
			break ;
		case "diffuser" :
			$action_diff_aff .= "<strong>".$msg['dsi_dif_diffusion'].": ".$bannette->nom_bannette."</strong><br />" ;
			$action_diff_aff .= $bannette->diffuser();
			break ;
		case "visualiser" :
			$action_diff_aff .= "<h3>".$msg['dsi_dif_ban_contenu'].": ".$bannette->nom_bannette."</h3>" ; 
			$action_diff_aff .= $bannette->aff_contenu_bannette("./dsi.php?categ=diffuser&sub=auto", 0);
			break ;
		case "full_auto" :
			$action_diff_aff .= $msg['dsi_dif_vidage'].": ".$bannette->nom_bannette."<br />" ; 
			if(!$bannette->limite_type)$action_diff_aff .= $bannette->vider();
			$action_diff_aff .= $msg['dsi_dif_remplissage'].": ".$bannette->nom_bannette ; 
			$action_diff_aff .= $bannette->remplir();
			$bannette->purger();
			$action_diff_aff .= "<strong>".$msg['dsi_dif_diffusion'].": ".$bannette->nom_bannette."</strong><br />" ; 
			$action_diff_aff .= $bannette->diffuser();
			break ;
		case "exporter" :
			$action_diff_aff .= "<script>openPopUp('./print_dsi.php?id_bannette=$bannette->id_bannette', 'Impression de DSI : $bannette->id_bannette ', 500, 400, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')</script>" ; 
			break ;
	    }
	}

switch($suite) {
    case "search":
	case "vider" :
	case "remplir" :
	case "diffuser" :
	case "full_auto" :
	case "exporter" :
		print get_bannette_pro ($msg[dsi_ban_search], $msg[dsi_ban_search_nom], './dsi.php?categ=diffuser&sub=auto', stripslashes($form_cb));
		if ($action_diff_aff) print "<h1>".$msg['dsi_dif_action_effectuee']." : </h1>".$action_diff_aff ;
		print pmb_bidi(dif_list_bannettes($form_cb, $id_bannette, $id_classement, 1, "./dsi.php?categ=diffuser&sub=auto")) ;
		break ;
	case "visualiser" :
		print get_bannette_pro ($msg[dsi_ban_search], $msg[dsi_ban_search_nom], './dsi.php?categ=diffuser&sub=auto', stripslashes($form_cb));
		if ($action_diff_aff) print $action_diff_aff;
		break ;
    default:
		echo window_title($database_window_title.$msg[dsi_dif_auto]);
		print get_bannette_pro ($msg[dsi_ban_search], $msg[dsi_ban_search_nom], './dsi.php?categ=diffuser&sub=auto', stripslashes($form_cb));
		print pmb_bidi(dif_list_bannettes($form_cb, $id_bannette, $id_classement, 1, "./dsi.php?categ=diffuser&sub=auto")) ;
        break;
    }

