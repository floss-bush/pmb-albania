<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: func_rameau_to_thesaurus.inc.php,v 1.8 2009-09-29 12:34:50 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// enregistrement de la notices dans les catégories
require_once($class_path."/thesaurus.class.php");
require_once($class_path."/categories.class.php");
global $thesaurus_defaut;

//Attention, dans le multithesaurus, le thesaurus dans lequel on importe est le thesaurus par defaut
$thes = new thesaurus($thesaurus_defaut);
$rac = $thes->num_noeud_racine;

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
				else $libelle_j .= " $pmb_keyword_sep ".trim($info_606_j[$a][$j]) ;
		}
		if (!$libelle_j) $libelle_final = trim($info_606_a[$a][0]) ; else $libelle_final = trim($info_606_a[$a][0])." $pmb_keyword_sep ".$libelle_j ;
		if (!$libelle_final) break ;
		for ($j=0; $j<sizeof($info_606_x[$a]); $j++) {
			$libelle_final .= " $pmb_keyword_sep ".trim($info_606_x[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_606_y[$a]); $j++) {
			$libelle_final .= " $pmb_keyword_sep ".trim($info_606_y[$a][$j]) ;
		}
		for ($j=0; $j<sizeof($info_606_z[$a]); $j++) {
			$libelle_final .= " $pmb_keyword_sep ".trim($info_606_z[$a][$j]) ;
		}
		if ($champ_rameau) $champ_rameau.=" $pmb_keyword_sep ";
		$champ_rameau.=$libelle_final;
	} 

	$rameau_form = serialize($tableau_606);
	
	// $rameau est la variable traitée par la fonction traite_categories_from_form, 
	// $rameau est normalement POSTée, afin de pouvoir être traitée en lot, donc hors 
	// formulaire, il faut l'affecter.
	$rameau = addslashes(serialize($tableau_606)) ;

	return array(
		"form" => "<input type='hidden' name='rameau' value='".htmlentities($rameau_form,ENT_QUOTES,$charset)."' />",
		"message" => "Rameau sera intégré avec les catégories existantes dans votre thésaurus par défaut (les catégories absentes seront intégrées en zone de mots clés libres) : <b>".htmlentities($champ_rameau,ENT_QUOTES,$charset)."</b>"
	);
}


function traite_categories_from_form() {
		
	global $rameau, $categ_pas_trouvee ;
	global $dbh;
	global $thes;
	
	$tableau_606 = unserialize(stripslashes($rameau)) ;
	
	$info_606_a = $tableau_606["info_606_a"] ;
	$info_606_j = $tableau_606["info_606_j"] ;
	$info_606_x = $tableau_606["info_606_x"] ;
	$info_606_y = $tableau_606["info_606_y"] ;
	$info_606_z = $tableau_606["info_606_z"] ;
	
	$categ_pas_trouvee=array();
	
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		$libelle_final = trim($info_606_a[$a][0]) ;
		if (!$libelle_final) break ; 
		$res_a = categories::searchLibelle(addslashes($libelle_final), $thes->id_thesaurus, 'fr_FR', '');
		if ($res_a) $categ_retour[]['categ_id'] = $res_a;
		else $categ_pas_trouvee[]=$libelle_final;

		// récup des sous-categ $j
		for ($j=0 ; $j < sizeof($info_606_j[$a]) ; $j++) {
			$res_j = categories::searchLibelle(addslashes(trim($info_606_j[$a][$j])), $thes->id_thesaurus, 'fr_FR', '');
			if ($res_j) $categ_retour[]['categ_id'] = $res_j;
			else $categ_pas_trouvee[]=trim($info_606_j[$a][$j]);
		} 
		
		// récup des sous-categ $x
		for ($x=0 ; $x < sizeof($info_606_x[$a]) ; $x++) {
			$res_x = categories::searchLibelle(addslashes(trim($info_606_x[$a][$x])), $thes->id_thesaurus, 'fr_FR', '');
			if ($res_x) $categ_retour[]['categ_id'] = $res_x;
			else $categ_pas_trouvee[]=trim($info_606_x[$a][$x]);
		} 
		
		for ($y=0 ; $y < sizeof($info_606_y[$a]) ; $y++) {
			$res_y = categories::searchLibelle(addslashes(trim($info_606_y[$a][$y])), $thes->id_thesaurus, 'fr_FR', '');
			if ($res_y) $categ_retour[]['categ_id'] = $res_y;
			else $categ_pas_trouvee[]=trim($info_606_y[$a][$y]);
		} 
		
		for ($z=0 ; $z < sizeof($info_606_z[$a]) ; $z++) {
			$res_z = categories::searchLibelle(addslashes(trim($info_606_z[$a][$z])), $thes->id_thesaurus, 'fr_FR', '');
			if ($res_z) $categ_retour[]['categ_id'] = $res_z;
			else $categ_pas_trouvee[]=trim($info_606_z[$a][$z]);
		} 
	}
// DEBUG echo "<pre>"; print_r($categ_retour) ; echo "</pre>"; exit ;
return $categ_retour ;
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
