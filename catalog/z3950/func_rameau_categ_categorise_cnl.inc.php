<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: func_rameau_categ_categorise_cnl.inc.php,v 1.2 2009-09-29 12:34:50 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// enregistrement de la notices dans les catégories
require_once($class_path."/thesaurus.class.php");
require_once($class_path."/categories.class.php");
global $thesaurus_defaut;

//Attention, dans le multithesaurus, le thesaurus dans lequel on importe est le thesaurus par defaut
$thes = new thesaurus($thesaurus_defaut);
$rac = $thes->num_noeud_racine;
// DEBUT paramétrage propre à la base de données d'importation :
// récupération du 606 : récup en catégories en essayant de classer :
//	les sujets sous le terme "Recherche par terme" 
	$id_rech_theme = categories::searchLibelle('Recherche par terme', $thesaurus_defaut, 'fr_FR');
    if (!$id_rech_theme) $id_rech_theme = create_categ($rac, 'Recherche par terme', strip_empty_words('Recherche par terme', 'fr_FR'));
		
//	les précisions géographiques sous le terme "Recherche géographique" 
	$id_rech_geo = categories::searchLibelle('Recherche géographique', $thesaurus_defaut, 'fr_FR');
    if (!$id_rech_geo) 	$id_rech_geo = create_categ($rac, 'Recherche géographique', strip_empty_words('Recherche géographique', 'fr_FR'));

//	les précisions de période sous le terme "Recherche chronologique" 
	$id_rech_chrono = categories::searchLibelle('Recherche chronologique', $thesaurus_defaut, 'fr_FR');
    if (!$id_rech_chrono) $id_rech_chrono = create_categ($rac, 'Recherche chronologique', strip_empty_words('Recherche chronologique', 'fr_FR'));

// FIN paramétrage 

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
			$rqt = $rqt_ins . " ('$notice_retour','$id_categ',$i) " ; 
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

	$rameau_form = serialize($tableau_606) ;
	
	// $rameau est la variable traitée par la fonction traite_categories_from_form, 
	// $rameau est normalement POSTée, afin de pouvoir être traitée en lot, donc hors 
	// formulaire, il faut l'affecter.
	$rameau = addslashes(serialize($tableau_606)) ;

	return array(
		"form" => "<input type='hidden' name='rameau' value='".htmlentities($rameau_form,ENT_QUOTES,$charset)."' />",
		"message" => "Rameau sera intégré sous forme d'arborescence répartie Recherche par terme \$a \$x Recherche géographique \$y Recherche chronologique \$z : <b>".htmlentities($champ_rameau,ENT_QUOTES,$charset)."</b>"
	);
}


function traite_categories_from_form() {
		
	global $rameau ;
	global $dbh;
	global $thes;
	global $id_rech_geo, $id_rech_chrono, $id_rech_theme ;
	
	$tableau_606 = unserialize(stripslashes($rameau)) ;
	
	$info_606_a = $tableau_606["info_606_a"] ;
	$info_606_j = $tableau_606["info_606_j"] ;
	$info_606_x = $tableau_606["info_606_x"] ;
	$info_606_y = $tableau_606["info_606_y"] ;
	$info_606_z = $tableau_606["info_606_z"] ;
	
	// ici récupération du code de admin/import/func_cnl.inc.php puis modif pour création du tableau des catégories, ce qui doit être retourné par la fonction
	$libelle_j = "" ;
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		for ($j=0; $j<sizeof($info_606_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= trim($info_606_j[$a][$j]) ;
				else $libelle_j .= " ** ".trim($info_606_j[$a][$j]) ;
			}
		if (!$libelle_j) $libelle_final = trim($info_606_a[$a][0]) ;
			else $libelle_final = trim($info_606_a[$a][0])." ** ".$libelle_j ;
		if (!$libelle_final) break ; 
		$res_a = categories::searchLibelle(addslashes($libelle_final), $thes->id_thesaurus, 'fr_FR', $id_rech_theme);
		if ($res_a) {
			$categid_a = $res_a;
		} else {
			$categid_a = create_categ($id_rech_theme, $libelle_final, strip_empty_words($libelle_final));
		}
		// récup des sous-categ en cascade sous $a
		$categ_parent =  $categid_a ;
		for ($x=0 ; $x < sizeof($info_606_x[$a]) ; $x++) {
			$res_x = categories::searchLibelle(addslashes(trim($info_606_x[$a][$x])), $thes->id_thesaurus, 'fr_FR', $categ_parent);
			if ($res_x) {
				$categ_parent = $res_x;
			} else {
				$categ_parent = create_categ($categ_parent, trim($info_606_x[$a][$x]), strip_empty_words($info_606_x[$a][$x]));
			}
		} // fin récup des $x en cascade sous l'id de la catégorie 606$a
		
		if ($categ_parent != $id_rech_theme) {
			$categ_retour[]['categ_id'] = $categ_parent ;
			}
		
		// récup des categ géo à loger sous la categ géo principale
		$categ_parent =  $id_rech_geo ;
		for ($y=0 ; $y < sizeof($info_606_y[$a]) ; $y++) {
			$res_y = categories::searchLibelle(addslashes(trim($info_606_y[$a][$y])), $thes->id_thesaurus, 'fr_FR', $categ_parent);
			if ($res_y) {
				$categ_parent = $res_y;		
			} else {
				$categ_parent = create_categ($categ_parent, trim($info_606_y[$a][$y]), strip_empty_words($info_606_y[$a][$y]));
			}
		} // fin récup des $y en cascade sous l'id de la catégorie principale thème géo
		
		if ($categ_parent != $id_rech_geo) {
			$categ_retour[]['categ_id'] = $categ_parent ;
			}
		
		// récup des categ chrono à loger sous la categ chrono principale
		$categ_parent =  $id_rech_chrono ;
		for ($z=0 ; $z < sizeof($info_606_z[$a]) ; $z++) {
			$res_z = categories::searchLibelle(addslashes(trim($info_606_z[$a][$z])), $thes->id_thesaurus, 'fr_FR', $categ_parent);
			if ($res_z) {
				$categ_parent = $res_z;
			} else {
				$categ_parent = create_categ($categ_parent, trim($info_606_z[$a][$z]), strip_empty_words($info_606_z[$a][$z]));
			}
		} // fin récup des $z en cascade sous l'id de la catégorie principale thème chrono
		
		if ($categ_parent != $id_rech_chrono) {
			$categ_retour[]['categ_id'] = $categ_parent ;
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

// Permet de mémoriser la valeur d'un import extern pour ensuite l'intégré dans un champ perso de la notice avec param_perso_form
function param_perso_prepare($record) {
	global $param_perso_910_a;
	$param_perso_910_a='';
	for ($i=0;$i<count($record->inner_directory);$i++) {
		$cle=$record->inner_directory[$i]['label'];
		switch($cle) {
			case "910": /* isbn */
				$param_perso_910_a=$record->get_subfield_array($cle, 'a');
			break;
		}
	}					
}

function param_perso_form(&$p_perso) {
	global $param_perso_910_a;
	$tab_date=explode("/",$param_perso_910_a[0]);
	$param_perso_910_sql=$tab_date[2].'-'.$tab_date[1].'-'.$tab_date[0];
	$q = "select idchamp from notices_custom where name='dateparution' limit 1 "; 
	$r = mysql_query ($q);
	if (mysql_num_rows($r)) {
		$field_dateparution = mysql_result($r,0,0);	
		$p_perso->values[$field_dateparution][0]=$param_perso_910_sql;
	}
	
}
				
