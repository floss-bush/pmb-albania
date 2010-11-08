<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categ_browser.php,v 1.1 2006-10-30 17:20:53 dbellamy Exp $

// affichage du browser de catégories

// définition du minimum nécéssaire
$base_path="../../..";
$base_auth = "CIRCULATION_AUTH";
$base_title = "\$msg[6]";
require_once ("$base_path/includes/init.inc.php");

include("$class_path/categ_browser.class.php");

// url du présent browser

$browser_url = "./categ_browser.php?id_empr=$id_empr&groupID=$groupID";

print "<div id='contenu-frame'>";

function select() {
	global $id_empr;
	global $groupID;
	$unq = md5(microtime());
	// retourne le code javascript changeant l'adresse de la page pour affichage des notices
	// $ref -> type de donnée (editeur, collection)
	// $id -> id de l'objet recherché
	return "window.parent.document.location='../../../circ.php?categ=resa_planning&resa_action=search_resa&mode=1&aut_id=!!id!!&aut_type=categ&etat=aut_search&id_empr=$id_empr&groupID=$groupID&unq=$unq'; return(false);";
}

$up_folder = "<img src='../../../images/folderup.gif' />";
$closed_folder = "<img src='../../../images/folderclosed.gif' />";
$open_folder = "<img src='../../../images/folderopen.gif' />";
$document = "<img src='../../../images/doc.gif' hspace='3' />";
$see = "<img src='../../../images/see.gif' />";

if ($id_thes != -1) {
	if($parent) {
		// affichage du browser pour le parent concerné
		$myBrowser = new categ_browser(	$parent,
										"<a href='./categ_browser.php?parent=!!id!!&id_empr=$id_empr&groupID=$groupID'>",
										"<a href='#' onClick=\"".select()."\">", $id_thes);
		$myBrowser->set_images($up_folder, $closed_folder, $open_folder, $document, $see);
		$myBrowser->do_browser();
		print pmb_bidi($myBrowser->display);
	} else {
		// page de démarrage du browser
		$myBrowser = new categ_browser(	0,
		 								"<a href='./categ_browser.php?parent=!!id!!&id_empr=$id_empr&groupID=$groupID'>",
		 								"<a href='#' onClick=\"".select()."\">", $id_thes);
		$myBrowser->set_images($up_folder, $closed_folder, $open_folder, $document);
		$myBrowser->do_browser();
		print pmb_bidi($myBrowser->display);
	}
} else {
//	Afficher ici la liste des thesaurus si besoin en mode tous les thesaurus
}
mysql_close($dbh);

// affichage du footer
print "</div></body></html>";
