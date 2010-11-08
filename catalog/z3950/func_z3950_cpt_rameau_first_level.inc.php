<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: func_z3950_cpt_rameau_first_level.inc.php,v 1.10 2009-09-29 12:34:50 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// enregistrement de la notices dans les catégories
require_once "$include_path/misc.inc.php" ;
require_once($class_path."/thesaurus.class.php");
require_once($class_path."/categories.class.php");
global $thesaurus_defaut;

//Attention, dans le multithesaurus, le thesaurus dans lequel on importe est le thesaurus par defaut
$thes = new thesaurus($thesaurus_defaut);
 
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
	global $charset, $rameau;
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
			$libelle_final .= " -- ".trim($info_606_x[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_606_y[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_606_y[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_606_z[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_606_z[$a][$j]) ;
		}
		if ($champ_rameau) $champ_rameau.=" @@@ ";
		$champ_rameau.=$libelle_final;
	} 

	$info_607_a = $tableau_607["info_607_a"] ;
	$info_607_j = $tableau_607["info_607_j"] ;
	$info_607_x = $tableau_607["info_607_x"] ;
	$info_607_y = $tableau_607["info_607_y"] ;
	$info_607_z = $tableau_607["info_607_z"] ;
	for ($a=0; $a<sizeof($info_607_a); $a++) {
		$libelle_final="";
		$libelle_j="";
		for ($j=0; $j<sizeof($info_607_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= trim($info_607_j[$a][$j]) ;
				else $libelle_j .= " ** ".trim($info_607_j[$a][$j]) ;
		}
		if (!$libelle_j) $libelle_final = trim($info_607_a[$a][0]) ; else $libelle_final = trim($info_607_a[$a][0])." ** ".$libelle_j ;
		if (!$libelle_final) break ;
		for ($j=0; $j<sizeof($info_607_x[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_607_x[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_607_y[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_607_y[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_607_z[$a]); $j++) {
			$libelle_final .= " -- ".trim($info_607_z[$a][$j]) ;
		}
		if ($champ_rameau) $champ_rameau.=" @@@ ";
		$champ_rameau.=$libelle_final;
	} 

	// $rameau est la variable traitée par la fonction traite_categories_from_form, 
	// $rameau est normalement POSTée, afin de pouvoir être traitée en lot, donc hors 
	// formulaire, il faut l'affecter.
	$rameau = addslashes($champ_rameau) ;

	return array(
		"form" => "<input type='hidden' name='rameau' value='".htmlentities($champ_rameau,ENT_QUOTES,$charset)."' />",
		"message" => "<br />Rameau sera intégré au premier niveau des catégories : ".str_replace("@@@", "<br />", htmlentities($champ_rameau,ENT_QUOTES,$charset))
	);
}


function traite_categories_from_form() {
	global $rameau ;
	global $thesaurus_defaut;
	global $thes;
	
	$categories=array();
	$categ_first=explode(" @@@ ",stripslashes($rameau));
	
	for ($i=0; $i<count($categ_first); $i++) {
		$categ_first[$i]=trim($categ_first[$i]);
		if ($categ_first[$i]) {
			$resultat = categories::searchLibelle(addslashes($categ_first[$i]), $thesaurus_defaut, 'fr_FR');	
			if (!$resultat){
				$categories[]["categ_id"] = create_categ($thes->num_noeud_racine, $categ_first[$i], " ".addslashes(strip_empty_words($categ_first[$i]))." ");
			} else {
				$categories[]["categ_id"] = $resultat;
			}
		}
	}
		
	return $categories ;
}


function create_categ($num_parent, $libelle, $index) {
	
	global $thes;
	$n = new noeuds();
	$n->num_thesaurus = $thes->id_thesaurus;
	$n->num_parent = $num_parent;
	$n->save();
	
	$c = new categories($n->id_noeud, 'fr_FR');
	$c->libelle_categorie = $libelle;
	$c->index_categorie = $index;
	$c->save();
	
	return $n->id_noeud;
}	
