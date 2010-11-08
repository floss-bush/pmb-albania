<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: func_other.inc.php,v 1.14 2009-09-29 12:34:50 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// enregistrement de la notices dans les catégories
function traite_categories_enreg($notice_retour,$categories,$thesaurus_traite=0) {

	global $dbh;
	
	// si $thesaurus_traite fourni, on ne delete que les catégories de ce thesaurus, sinon on efface toutes
	//  les indexations de la notice sans distinction de thesaurus
	if (!$thesaurus_traite) $rqt_del = "delete from notices_categories where notcateg_notice='$notice_retour' ";
	else $rqt_del = "delete from notices_categories where notcateg_notice='$notice_retour' and num_noeud in (select id_noeud from noeuds where num_thesaurus='$thesaurus_traite' and id_noeud=notices_categories.num_noeud) ";
	$res_del = @mysql_query($rqt_del, $dbh);
	
	$rqt_ins = "insert into notices_categories (notcateg_notice, num_noeud, ordre_categorie) VALUES ";
	
	for($i=0 ; $i< sizeof($categories) ; $i++) {
		$id_categ=$categories[$i]['categ_id'];
		if ($id_categ) {
			$rqt = $rqt_ins . " ('$notice_retour','$id_categ', $i) " ; 
			$res_ins = @mysql_query($rqt, $dbh);
		}
	}
}

function traite_categories_for_form($tableau_600="",$tableau_601="",$tableau_602="",$tableau_605="",$tableau_606="",$tableau_607="",$tableau_608="") {
	global $charset, $pmb_keyword_sep, $rameau;
	$rameau = "" ;
	$info_606_a = $tableau_606["info_606_a"] ;
	$info_606_j = $tableau_606["info_606_j"] ;
	$info_606_x = $tableau_606["info_606_x"] ;
	$info_606_y = $tableau_606["info_606_y"] ;
	$info_606_z = $tableau_606["info_606_z"] ;
	
	$champ_rameau="";
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		$libelle_final="";
		$libelle_j="";
		for ($j=0; $j<sizeof($info_606_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= trim($info_606_j[$a][$j]) ;
				else $libelle_j .= " ** ".trim($info_606_j[$a][$j]) ;
		}
		if (!$libelle_j) $libelle_final = trim($info_606_a[$a][0]) ; else $libelle_final = trim($info_606_a[$a][0])." ** ".$libelle_j ;
		if (!$libelle_final) break ;
		for ($j=0; $j<sizeof($info_606_x[$a]); $j++) {
			$libelle_final .= " : ".trim($info_606_x[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_606_y[$a]); $j++) {
			$libelle_final .= " : ".trim($info_606_y[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_606_z[$a]); $j++) {
			$libelle_final .= " : ".trim($info_606_z[$a][$j]) ;
		}
		if ($champ_rameau) $champ_rameau.=" @@@ ";
		$champ_rameau.=$libelle_final;
	} 
	
	return array(
		"form" => "",
		"message" => "L'indexation sera ignorée : ".htmlentities($champ_rameau,ENT_QUOTES,$charset)
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
