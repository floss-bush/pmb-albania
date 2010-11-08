<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_lecture.inc.php,v 1.5 2009-11-17 14:02:23 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ($class_path."/liste_lecture.class.php");

$listes = new liste_lecture(($emprlogin ? $emprlogin : $login),$act,$id_liste);

switch($lvl){
	case 'public_list' :
		$listes->generate_publiclist();
		print $listes->display;
		break;
		
	case 'private_list':
		switch($sub) {
			case 'my_list':
				$listes->generate_mylist();
				print $listes->display;
				break;
			case 'shared_list':
				$listes->generate_sharedlist();
				print $listes->display;
				break;
			default:
				$listes->generate_mylist();
				print $listes->display;
				break;
		}
		break;
	case 'demande_list':
		$listes->generate_demandes();
		print $listes->display;
		break; 
	default:
		break;
}


?>