<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: func_bretagne.inc.php,v 1.7 2009-09-29 12:34:50 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// DEBUT paramétrage propre à la base de données d'importation :
require_once($base_path."/admin/import/func_bretagne.inc.php");

function z_recup_noticeunimarc_suite($notice) {
	recup_noticeunimarc_suite($notice);
} // fin recup_noticeunimarc_suite = fin récupération des variables propres à la bretagne
	
function z_import_new_notice_suite() {
	import_new_notice_suite();
} // fin import_new_notice_suite
			
// TRAITEMENT DES EXEMPLAIRES ICI
function z_traite_exemplaires () {
	traite_exemplaires();
	} // fin traite_exemplaires	TRAITEMENT DES EXEMPLAIRES JUSQU'ICI
	
// enregistrement de la notices dans les catégories
function traite_categories_enreg($notice_retour,$categories,$thesaurus_traite=0) {

	global $dbh;
	
	// si $thesaurus_traite fourni, on ne delete que les catégories de ce thesaurus, sinon on efface toutes
	//  les indexations de la notice sans distinction de thesaurus
	if (!$thesaurus_traite) $rqt_del = "delete from notices_categories where notcateg_notice='$notice_retour' ";
	else $rqt_del = "delete from notices_categories where notcateg_notice='$notice_retour' and num_noeud in (select id_noeud from noeuds where num_thesaurus='$thesaurus_traite' and id_noeud=notices_categories.num_noeud) ";
	$res_del = @mysql_query($rqt_del, $dbh);
	
	$rqt_ins = "INSERT INTO notices_categories (notcateg_notice, notcateg_categorie, ordre_categorie) VALUES ";
	for($i=0 ; $i< sizeof($categories) ; $i++) {
		$id_categ=$categories[$i]['categ_id'];
		if ($id_categ) {
			$rqt = $rqt_ins . " ('$notice_retour','$id_categ',$i) " ; 
			$res_ins = @mysql_query($rqt, $dbh);
		}
	}
}

function traite_categories_for_form($tableau_600="",$tableau_601="",$tableau_602="",$tableau_605="",$tableau_606="",$tableau_607="",$tableau_608="") {
	return array(
		"form" => "",
		"message" => ""
	);
}

function traite_categories_from_form() {
global $max_categ ;
$categories = array () ;
for ($i=0; $i< $max_categ ; $i++) {
	$var_categ = "f_categ_id$i" ;
	global $$var_categ ;
	if ($$var_categ) 
		$categories[] = array('categ_id' => $$var_categ );
	}
return $categories ;
}
	