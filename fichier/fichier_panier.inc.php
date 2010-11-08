<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fichier_panier.inc.php,v 1.1 2010-06-21 09:10:48 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($mode){
	case 'gestion':
		
		break;
	case 'collect':
		switch($sub){
			case 'proc':
				$fichier_menu_collecter = str_replace('!!menu_sous_rub!!',$msg['fichier_menu_panier_collecter']." > ".$msg['fichier_pointer_procedures'],$fichier_menu_collecter);
				print $fichier_menu_collecter;
			break;
			default:
				$fichier_menu_collecter = str_replace('!!menu_sous_rub!!',$msg['fichier_menu_panier_collecter'],$fichier_menu_collecter);
				print $fichier_menu_collecter;
			break;
		}
		
		break;
	case 'pointer':
		switch($sub){
			case 'proc':
				$fichier_menu_pointer = str_replace('!!menu_sous_rub!!',$msg['fichier_menu_panier_pointer']." > ".$msg['fichier_pointer_procedures'],$fichier_menu_pointer);
				print $fichier_menu_pointer;
			break;
			default:
				$fichier_menu_pointer = str_replace('!!menu_sous_rub!!',$msg['fichier_menu_panier_pointer'],$fichier_menu_pointer);
				print $fichier_menu_pointer;
			break;
		}
		print $fichier_menu_pointer;
		break;
	case 'action':
		switch($sub){
			case 'proc':
				$fichier_menu_actions = str_replace('!!menu_sous_rub!!',$msg['fichier_menu_panier_action']." > ".$msg['fichier_pointer_procedures'],$fichier_menu_actions);
				print $fichier_menu_actions;
				break;
			case 'edit':
				$fichier_menu_actions = str_replace('!!menu_sous_rub!!',$msg['fichier_menu_panier_action']." > ".$msg['fichier_action_edit'],$fichier_menu_actions);
				print $fichier_menu_actions;
				break;
			case 'mail':
				$fichier_menu_actions = str_replace('!!menu_sous_rub!!',$msg['fichier_menu_panier_action']." > ".$msg['fichier_action_mail'],$fichier_menu_actions);
				print $fichier_menu_actions;
				break;
			case 'sms':
				$fichier_menu_actions = str_replace('!!menu_sous_rub!!',$msg['fichier_menu_panier_action']." > ".$msg['fichier_action_sms'],$fichier_menu_actions);
				print $fichier_menu_actions;
				break;
			default :
				$fichier_menu_actions = str_replace('!!menu_sous_rub!!',$msg['fichier_menu_panier_action'],$fichier_menu_actions);
				print $fichier_menu_actions;
				break;				
		}		
		break;

}