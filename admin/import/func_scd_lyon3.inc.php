<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_scd_lyon3.inc.php,v 1.12 2007-12-07 13:39:47 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/thesaurus.class.php");
require_once($class_path."/categories.class.php");
global $thesaurus_defaut;


// Attention, dans le multithesaurus, on insère les catégories
// dans le thesaurus par defaut
		$thes = new thesaurus($thesaurus_defaut);
		$rac = $thes->num_noeud_racine;
    

// DEBUT paramétrage propre à la base de données d'importation :
// récupération du 606 : récup en catégories en essayant de classer :
//	les sujets sous le terme "Recherche par terme" 
	$id_rech_theme = categories::searchLibelle('Recherche par terme', $thes->id_thesaurus, 'fr_FR');
    if (!$id_rech_theme) $id_rech_theme = create_categ($rac, 'Recherche par terme', strip_empty_words('Recherche par terme', 'fr_FR'));
		
//	les précisions géographiques sous le terme "Recherche géographique" 
	$id_rech_geo = categories::searchLibelle('Recherche géographique', $thes->id_thesaurus, 'fr_FR');
    if (!$id_rech_geo) 	$id_rech_geo = create_categ($rac, 'Recherche géographique', strip_empty_words('Recherche géographique', 'fr_FR'));

//	les précisions de période sous le terme "Recherche chronologique" 
	$id_rech_chrono = categories::searchLibelle('Recherche chronologique', $thes->id_thesaurus, 'fr_FR');
    if (!$id_rech_chrono) $id_rech_chrono = create_categ($rac, 'Recherche chronologique', strip_empty_words('Recherche chronologique', 'fr_FR'));

// FIN paramétrage 

function recup_noticeunimarc_suite($notice) {
} // fin recup_noticeunimarc_suite = fin récupération des variables propres BDP : rien de plus
	
function import_new_notice_suite() {
	global $dbh ;
	global $notice_id ;
	
	global $info_606_a, $info_606_j, $info_606_x, $info_606_y, $info_606_z ;
	global $id_rech_theme, $id_rech_geo, $id_rech_chrono ; 
	global $thesaurus_defaut;
	global $thes;
	
	// les champs $606 sont stockés dans les catégories
	//	$a >> en sous catégories de $id_rech_theme
	// 		$j en complément de $a
	//		$x en sous catégories de $a
	// $y >> en sous catégories de $id_rech_geo
	// $z >> en sous catégories de $id_rech_chrono
	// TRAITEMENT :
	// pour $a=0 à size_of $info_606_a
	//	pour $j=0 à size_of $info_606_j[$a]
	//		concaténer $libelle_j .= $info_606_j[$a][$j]
	//	$libelle_final = $info_606_a[0]." ** ".$libelle_j
	//	Rechercher si l'enregistrement existe déjà dans categories = 
	//	$categid = categories::searchLibelle(addslashes($libelle_final), $thesaurus_defaut, 'fr_FR', $id_rech_theme)

	//	Créer si besoin et récupérer l'id $categid_a
	//	$categid_parent =  $categid_a
	//	pour $x=0 à size_of $info_606_x[$a]
	//		Rechercher si l'enregistrement existe déjà dans categories = 
	//	$categid = categories::searchLibelle(addslashes($info_606_x[$a][$x]), $thesaurus_defaut, 'fr_FR', $categ_parent)

	//		Créer si besoin et récupérer l'id $categid_parent
	//
	//	$categid_parent =  $id_rech_geo
	//	pour $y=0 à size_of $info_606_y[$a]
	//		Rechercher si l'enregistrement existe déjà dans categories = 
	//	$categid = categories::searchLibelle(addslashes($info_606_y[$a][$y]), $thesaurus_defaut, 'fr_FR', $categ_parent)

	//		Créer si besoin et récupérer l'id $categid_parent
	//
	//	$categid_parent =  $id_rech_chrono
	//	pour $y=0 à size_of $info_606_z[$a]
	//		Rechercher si l'enregistrement existe déjà dans categories = 
	//	$categid = categories::searchLibelle(addslashes($info_606_z[$a][$y]]), $thesaurus_defaut, 'fr_FR', $categ_parent)

	//		Créer si besoin et récupérer l'id $categid_parent
	//
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		for ($j=0; $j<sizeof($info_606_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= $info_606_j[$a][$j] ;
				else $libelle_j .= " ** ".$info_606_j[$a][$j] ;
		}
		if (!$libelle_j) $libelle_final = $info_606_a[$a][0] ;
			else $libelle_final = $info_606_a[$a][0]." ** ".$libelle_j ;
		if (!$libelle_final) break ; 
		$res_a = categories::searchLibelle(addslashes($libelle_final), $thesaurus_defaut, 'fr_FR', $id_rech_theme);
		if ($res_a) {
			$categid_a = $res_a;
		} else {
			$categid_a = create_categ($id_rech_theme, $libelle_final, strip_empty_words($libelle_final, 'fr_FR'));
		}
		// récup des sous-categ en cascade sous $a
		$categ_parent =  $categid_a ;
		for ($x=0 ; $x < sizeof($info_606_x[$a]) ; $x++) {
			$res_x = categories::searchLibelle(addslashes($info_606_x[$a][$x]), $thesaurus_defaut, 'fr_FR', $categ_parent);
			if ($res_x) {
				$categ_parent = $res_x;
			} else {
				$categ_parent = create_categ($categ_parent, $info_606_x[$a][$x], strip_empty_words($info_606_x[$a][$x], 'fr_FR'));
			}
		} // fin récup des $x en cascade sous l'id de la catégorie 606$a
		
		if ($categ_parent != $id_rech_theme) {
			// insertion dans la table notices_categories
			$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', num_noeud='".$categ_parent."' " ;

			$res_ajout = @mysql_query($rqt_ajout, $dbh);
		}
				
		// récup des categ géo à loger sous la categ géo principale
		$categ_parent =  $id_rech_geo ;
		for ($y=0 ; $y < sizeof($info_606_y[$a]) ; $y++) {
			$res_y = categories::searchLibelle(addslashes($info_606_y[$a][$y]), $thesaurus_defaut, 'fr_FR', $categ_parent);
			if($res_y) {
				$categ_parent = $res_y;
			} else {
				$categ_parent = create_categ($categ_parent, $info_606_y[$a][$y], strip_empty_words($info_606_y[$a][$y], 'fr_FR'));
			}
		} // fin récup des $y en cascade sous l'id de la catégorie principale thème géo
		
		if ($categ_parent != $id_rech_geo) {
			// insertion dans la table notices_categories
			$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', num_noeud='".$categ_parent."' " ;
			$res_ajout = @mysql_query($rqt_ajout, $dbh);
		}
		
		// récup des categ chrono à loger sous la categ chrono principale
		$categ_parent =  $id_rech_chrono ;
		for ($z=0 ; $z < sizeof($info_606_z[$a]) ; $z++) {
			$res_z = categories::searchLibelle(addslashes($info_606_z[$a][$z]), $thesaurus_defaut, 'fr_FR', $categ_parent);
			if ($res_z) {
				$categ_parent = $res_z;
			} else {
				$categ_parent = create_categ($categ_parent, $info_606_z[$a][$z], strip_empty_words($info_606_z[$a][$z], 'fr_FR'));
			}
		} // fin récup des $z en cascade sous l'id de la catégorie principale thème chrono
		
		if ($categ_parent != $id_rech_chrono) {
			// insertion dans la table notices_categories
			$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', num_noeud='".$categ_parent."' " ;

			$res_ajout = @mysql_query($rqt_ajout, $dbh);
		}
	}
	
} // fin import_new_notice_suite


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

			
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
	global $msg, $dbh ;
	
	global $prix, $notice_id, $info_852, $tdoc_codage, $book_lender_id, 
		$sdoc_codage, $book_statut_id, $locdoc_codage, $statisdoc_codage,
		$cote_mandatory, $book_location_id ;
	
	// débuggage
	/*	echo "<pre>" ;
	print_r($info_852) ;
	echo "</pre>" ;
	exit ;
	 */ 

	// lu en 010$d de la notice
	$price = $prix[0];
	// la zone 852 est répétable
	for ($nb_expl = 0; $nb_expl < sizeof ($info_852); $nb_expl++) {
		if ($info_852[$nb_expl]['b']=="IDC") {
			/* préparation du tableau à passer à la méthode */
			/* RAZ expl */
			$expl = array();

			$expl['cb'] 	    = $info_852[$nb_expl]['p'];
			$expl['cote'] 	    = $info_852[$nb_expl]['h'];
			$expl['notice']     = $notice_id ;

			// $expl['section']    = $info_852[$nb_expl]['h']; à chercher dans docs_section
			$data_doc=array();
			
			$pos_section = strpos($info_852[$nb_expl]['h']," ") ;
			if ($pos_section) $section = substr($info_852[$nb_expl]['h'],0,$pos_section) ;
				else $section = "XXX" ;
			
			if ($section=="XXX") $data_doc['section_libelle'] = "SECTION INDETERMINEE";
				else $data_doc['section_libelle'] = "Libellé pour ".$section;
			$data_doc['sdoc_codage_import'] = $section;
			if ($sdoc_codage) $data_doc['sdoc_owner'] = $book_lender_id ;
				else $data_doc['sdoc_owner'] = 0 ;
			$expl['section'] = docs_section::import($data_doc);

			// $expl['typdoc'] 
			$data_doc=array();
			$data_doc['tdoc_libelle'] = "Type doc indéterminé";
			$data_doc['duree_pret'] = 15 ; /* valeur par défaut */
			$data_doc['tdoc_codage_import'] = "XXX" ;
			if ($tdoc_codage) $data_doc['tdoc_owner'] = $book_lender_id ;
				else $data_doc['tdoc_owner'] = 0 ;
			$expl['typdoc'] = docs_type::import($data_doc);

			$expl['statut'] = $book_statut_id;
	
			$expl['location'] = $book_location_id;
		
			// $expl['codestat']
			$data_doc=array();
			$data_doc['codestat_libelle'] = "AUCUN CODE STATISTIQUE";
			$data_doc['statisdoc_codage_import'] = "XXX" ;
			if ($statisdoc_codage) $data_doc['statisdoc_owner'] = $book_lender_id ;
				else $data_doc['statisdoc_owner'] = 0 ;
			$expl['codestat'] = docs_codestat::import($data_doc);
		
			$expl['note']       = "" ;
			$expl['prix']       = $price;
			$expl['expl_owner'] = $book_lender_id ;
			$expl['cote_mandatory'] = $cote_mandatory ;
		
			$expl_id = exemplaire::import($expl);
			if ($expl_id == 0) {
				$nb_expl_ignores++;
				}
                      	
			}
		
		} // fin for
	} // fin traite_exemplaires	TRAITEMENT DES EXEMPLAIRES JUSQU'ICI

// fonction spécifique d'export de la zone 995
function export_traite_exemplaires ($ex=array()) {
	global $msg, $dbh ;
	
	$subfields["a"] = $ex -> lender_libelle;
	$subfields["c"] = $ex -> lender_libelle;
	$subfields["f"] = $ex -> expl_cb;
	$subfields["k"] = $ex -> expl_cote;
	$subfields["u"] = $ex -> expl_note;

	if ($ex->statusdoc_codage_import) $subfields["o"] = $ex -> statusdoc_codage_import;
	if ($ex -> tdoc_codage_import) $subfields["r"] = $ex -> tdoc_codage_import;
		else $subfields["r"] = "uu";
	if ($ex -> sdoc_codage_import) $subfields["q"] = $ex -> sdoc_codage_import;
		else $subfields["q"] = "u";
	
	global $export996 ;
	$export996['f'] = $ex -> expl_cb ;
	$export996['k'] = $ex -> expl_cote ;
	$export996['u'] = $ex -> expl_note ;

	$export996['m'] = substr($ex -> expl_date_depot, 0, 4).substr($ex -> expl_date_depot, 5, 2).substr($ex -> expl_date_depot, 8, 2) ;
	$export996['n'] = substr($ex -> expl_date_retour, 0, 4).substr($ex -> expl_date_retour, 5, 2).substr($ex -> expl_date_retour, 8, 2) ;

	$export996['a'] = $ex -> lender_libelle;
	$export996['b'] = $ex -> expl_owner;

	$export996['v'] = $ex -> location_libelle;
	$export996['w'] = $ex -> ldoc_codage_import;

	$export996['x'] = $ex -> section_libelle;
	$export996['y'] = $ex -> sdoc_codage_import;

	$export996['e'] = $ex -> tdoc_libelle;
	$export996['r'] = $ex -> tdoc_codage_import;

	$export996['1'] = $ex -> statut_libelle;
	$export996['2'] = $ex -> statusdoc_codage_import;
	$export996['3'] = $ex -> pret_flag;
	
	global $export_traitement_exemplaires ;
	$export996['0'] = $export_traitement_exemplaires ;
	
	return 	$subfields ;

	}	
