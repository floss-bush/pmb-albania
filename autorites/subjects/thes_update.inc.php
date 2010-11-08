<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: thes_update.inc.php,v 1.4 2007-03-10 09:03:18 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// si tout est OK, on a les variables suivantes à exploiter :
// $id_thes				identifiant de thesaurus (0 si nouveau)
// $libelle_thesaurus	libelle du thesaurus
// $langue_defaut		langue par défaut du thesaurus (rien si inchangée)


require_once("$class_path/thesaurus.class.php");


// libelle thesaurus non renseigne
if ( (trim($libelle_thesaurus)) == '' ) {
	error_form_message($msg["thes_libelle_manquant"]);
	exit ;	
}


if($id_thes) {	
		//thesaurus existant
		
	$thes = new thesaurus($id_thes);
	$thes->libelle_thesaurus = $libelle_thesaurus;
	$thes->save();
	
} else {
		//thesaurus a creer
		
	$thes = new thesaurus();
	$thes->libelle_thesaurus = $libelle_thesaurus;
	$thes->langue_defaut = $langue_defaut;
	$thes->save();
	
}


include('./autorites/subjects/thesaurus.inc.php');
