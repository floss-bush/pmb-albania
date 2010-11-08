<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: show_list.inc.php,v 1.5 2009-11-17 14:02:23 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ($class_path."/liste_lecture.class.php");
require_once ($base_path."/includes/templates/liste_lecture.tpl.php");

if($_SESSION['user_code']){
	// TRES JOLI mais inutile ! global $notice, $notices, $id_liste;
	$listes = new liste_lecture($_SESSION['user_code'],$act,$id_liste);
	
	switch($sub){
		case 'transform_caddie' :
			$notices = $_SESSION['cart'];
			$listes->affichage_saveform($notices);			
			break;
		case 'transform_check':		
			$notices = $notice;
			$listes->affichage_saveform($notices);
			break;
		case 'view':			
			$listes->affichage_saveform();
			break;	
		case 'consultation':
			$listes->consulter_liste();
			break;
		default:
			$listes->generate_mylist();
			break;
	}
} else {
	print "<script>document.location='empr.php';</script>";
}

?>