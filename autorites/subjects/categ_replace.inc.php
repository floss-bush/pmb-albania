<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categ_replace.inc.php,v 1.2 2010-12-06 15:51:18 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// si tout est OK, on a les variables suivantes à exploiter :
// $id			identifiant du noeuds
// $parent	identifiant du parent du noeuds
// $by éventuelement l'identifiant du noeud à utiliser à la place

require_once("$class_path/noeuds.class.php");

$noeuds = new noeuds($id);
if(!$by) {
	if (noeuds::hasChild($id)) {//On regarde si le noeud remplacé a des enfants
		error_message($msg[321],$msg["categ_imposible_remplace_avec_fille"], 1, "./autorites.php?categ=categories&id=$id&sub=categ_form&parent=$parent");
		exit();
	}else{
		$noeuds->replace_categ_form($parent);
	}
} else {
	$rep=$noeuds->replace($by,$aut_link_save);
	if(!$rep){
		$id=0;
		$parent=0;
		include("./autorites/subjects/default.inc.php");
	}else{
		error_message($msg[132], $rep, 1, "./autorites.php?categ=categories&sub=categ_replace&id=$id&parent=$parent");
	}
}



