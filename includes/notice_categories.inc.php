<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_categories.inc.php,v 1.9 2008-03-26 12:55:53 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// récupération des categories d'une notice

// get_notice_categories : retourne un tableau avec les categories d'une notice donnée
function get_notice_categories($notice=0) {
	global $dbh;
	$categories = array() ;

	$rqt = "SELECT noeuds.id_noeud as categ_id, noeuds.num_parent as categ_parent, noeuds.num_renvoi_voir as categ_see ";
	$rqt.= "FROM notices_categories, noeuds ";
	$rqt.= "WHERE notices_categories.notcateg_notice='$notice' ";
	$rqt.= "AND notices_categories.num_noeud=noeuds.id_noeud ";
	$rqt.= "ORDER BY ordre_categorie";
	//$rqt.= "ORDER BY num_thesaurus, ordre_categorie";

	$res_sql = mysql_query($rqt, $dbh);
	while ($notice=mysql_fetch_object($res_sql)) {
		$categories[] = array( 
				'categ_id' => $notice->categ_id,
				'categ_parent' => $notice->categ_parent,
				'categ_see' => $notice->categ_see
				) ;
		}
	return $categories;
	}

require_once("$class_path/marc_table.class.php");
// get_notice_langues : retourne un tableau avec les langues d'une notice donnée
function get_notice_langues($notice=0, $quelle_langues=0) {
	global $dbh;

	global $marc_liste_langues ;
	if (!$marc_liste_langues) $marc_liste_langues=new marc_list('lang');

	$langues = array() ;
	$rqt = "select code_langue from notices_langues where num_notice='$notice' and type_langue=$quelle_langues ";
	$res_sql = mysql_query($rqt, $dbh);
	while ($notice=mysql_fetch_object($res_sql)) {
		if ($notice->code_langue)
			$langues[] = array( 
				'lang_code' => $notice->code_langue,
				'langue' => $marc_liste_langues->table[$notice->code_langue]
				) ;
		}
	return $langues;
}

function construit_liste_langues($tableau) {

	for ($i = 0 ; $i < sizeof($tableau) ; $i++) {
		if ($langues) $langues.=" ";
		$langues .= $tableau[$i]["langue"]." (<i>".$tableau[$i]["lang_code"]."</i>)";
		}
	return $langues;
}

