<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: semantique_main.inc.php,v 1.1 2007-10-02 17:49:31 jlesaint Exp $sub

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch ($sub) {
	case 'synonyms':
		include('./autorites/semantique/dico_synonymes.inc.php');
	break;
	case 'empty_words':
		include('./autorites/semantique/dico_empty_words.inc.php');
	break;
	default:
		include('./autorites/semantique/dico_synonymes.inc.php');
	break;
}
?>
