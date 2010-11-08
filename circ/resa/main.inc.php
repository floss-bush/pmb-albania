<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.13 2008-06-04 14:54:25 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de switch recherche notice

require_once("$include_path/templates/catalog.tpl.php");
require_once("$include_path/isbn.inc.php");
require_once("$include_path/marc_tables/$lang/empty_words");
require_once("$class_path/marc_table.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/category.class.php");
require_once("$class_path/notice.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/serials.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/expl.class.php");
require_once("$class_path/explnum.class.php");

// inclusions principales
require_once("$include_path/templates/resa.tpl.php");

// gestion des liens en rech resa ou pas 
$link = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_notice=!!id!!";
$link_serial = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=view_serial&serial_id=!!id!!";
$link_analysis = '';
$link_bulletin = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=!!id!!";
				
if (!$id_empr) {
	// pas d'id empr, quelque chose ne va pas
	error_message($msg[350], $msg[54], 1 , './circ.php');
} else {
	if($id_notice || $id_bulletin) {
		// notice sélectionnée -> création de la réservation
		// include du fichier des opérations de réservation
		include('./circ/resa/do_resa.inc.php');
	} else {
		// récupération nom emprunteur
		$requete = "SELECT empr_nom, empr_prenom, empr_cb FROM empr WHERE id_empr=$id_empr LIMIT 1";
		$result = @mysql_query($requete, $dbh);
		if(!mysql_num_rows($result)) {
			// pas d'emprunteur correspondant, quelque chose ne va pas
			error_message($msg[350], $msg[54], 1 , './circ.php');
		} else {
			$empr = mysql_fetch_object($result);
			$name = $empr->empr_prenom;
			$name ? $name .= ' '.$empr->empr_nom : $name = $empr->empr_nom;
			echo window_title($database_window_title.$name.$msg[1003].$msg[352]);
			$layout_begin = preg_replace('/!!nom_lecteur!!/m', $name, $layout_begin);
			$layout_begin = preg_replace('/!!cb_lecteur!!/m', $empr->empr_cb, $layout_begin);
			print pmb_bidi($layout_begin);
			switch($mode) {
				case 1:
					// recherche catégorie/sujet
					print $menu_search[1];
					include('./circ/resa/subjects/main.inc.php');
					break;
				case 5:
					// recherche par termes
					print $menu_search[6];
					include('./circ/resa/terms/main.inc.php');
					break;
				case 2:
					// recherche éditeur/collection
					print $menu_search[2];
					include('./circ/resa/publishers/main.inc.php');
					break;
				case 3:
					// accès aux paniers
					print $menu_search[3];
					include('./circ/resa/cart.inc.php');
					break;
				case 4:
					// autres recherches
					print $menu_search[4];
					include('./circ/resa/others.inc.php');
					break;			
				case 'view_serial':
					// affichage de la liste des éléments bulletinés pour un périodique
					include('./circ/resa/view_serial.inc.php');
					break;
				case 6:
					// recherches avancees
					print $menu_search[6];
					include('./circ/resa/extended/main.inc.php');
					break;	
				default :
					// recherche auteur/titre
					print $menu_search[0];
					$action_form = "./circ.php?categ=resa&mode=0&id_empr=$id_empr&groupID=$groupID" ;
					include('./circ/resa/authors/main.inc.php');
					break;
			}
			print $layout_end;
		}
	}
}



