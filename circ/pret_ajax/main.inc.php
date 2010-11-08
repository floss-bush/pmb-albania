<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.3 2010-06-16 12:19:04 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//En fonction de $sub, il inclut les fichiers correspondants

switch($sub):
	case 'confirm_pret':
		include('./circ/pret_ajax/confirm_pret.inc.php');
	break;
	case 'get_info_expl':
		include('./circ/pret_ajax/get_info_expl.inc.php');
	break;
	case 'add_cb':
		include('./circ/pret_ajax/add_cb.inc.php');
	break;
	case 'del_pret':
		include('./circ/pret_ajax/del_pret.inc.php');
	break;	
	case 'do_retour':
		include('./circ/pret_ajax/do_retour.inc.php');
	break;	
	case 'add_cb_list':
		include('./circ/pret_ajax/add_cb_list.inc.php');
	break;
	
	default:
		ajax_http_send_error('400',"commande inconnue");
	break;		
endswitch;	
