<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: thes_delete.inc.php,v 1.4 2009-12-18 11:18:25 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// si tout est OK, on a les variables suivantes à exploiter :
// $id_thes				identifiant de thesaurus (0 si nouveau)
// $libelle_thesaurus	libelle du thesaurus
// $langue_defaut		langue par défaut du thesaurus (rien si inchangée)


require_once("$class_path/thesaurus.class.php");


if (thesaurus::hasNotices($id_thes)){		//le thesaurus est utilisé dans les notices.
	error_form_message($msg["thes_suppr_impossible"]);
	exit;
} else {
	if(($opac_thesaurus_defaut === $id_thes) or ($thesaurus_defaut === $id_thes) or ($deflt_thesaurus === $id_thes)){
		error_form_message($msg["thes_suppr_categ_utilisee"]);
	}else{
		thesaurus::delete($id_thes);
		thesaurus::setSessionThesaurusId(-1);
	}	
}

include('./autorites/subjects/thesaurus.inc.php');
