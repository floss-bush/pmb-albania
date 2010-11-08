<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: func_customfields.inc.php,v 1.1 2009-12-11 12:54:47 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// DEBUT paramétrage propre à la base de données d'importation :
require_once($base_path."/admin/import/func_customfields.inc.php");

function z_recup_noticeunimarc_suite($notice) {
	recup_noticeunimarc_suite($notice);
} 
	
function z_import_new_notice_suite() {
	import_new_notice_suite();
} 

// enregistrement de la notices dans les catégories
function traite_categories_enreg($notice_retour,$categories,$thesaurus_traite=0) {

}

function traite_categories_for_form($tableau_600="",$tableau_601="",$tableau_602="",$tableau_605="",$tableau_606="",$tableau_607="",$tableau_608="") {
	
}

function traite_categories_from_form() {

}
	
?>