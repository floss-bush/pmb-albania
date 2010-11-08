<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/thesaurus.class.php");
require_once($class_path."/categories.class.php");
require_once($class_path."/lender.class.php");
global $thesaurus_defaut;

// DEBUT paramétrage propre à la base de données d'importation :

// récupération du 606 : récup en catégories en essayant de classer :
// RECUP intégrale : 

// Attention, dans le multithesaurus, l'identifiant de recherche par terme est
// la racine du thesaurus par defaut

//	les sujets sous le terme "Recherche par terme" 
		$thes = new thesaurus($thesaurus_defaut);
		$id_rech_theme = $thes->num_noeud_racine;
		
// FIN paramétrage 

function recup_noticeunimarc_suite($notice) {
} // fin recup_noticeunimarc_suite = fin récupération des variables propres au CNL
	
function import_new_notice_suite() {
	global $dbh ;
	global $notice_id ;
	
	global $info_606_a, $info_606_j, $info_606_x, $info_606_y, $info_606_z ;
	global $id_rech_theme ; 
	global $thesaurus_defaut;
	global $thes;
	
	/* 
	echo "<pre>";
	print_r ($info_949);
	print_r ($info_997);
	echo "</pre>";
	*/
	
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
	$libelle_j="";
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		for ($j=0; $j<sizeof($info_606_j[$a]); $j++) {
			if (!$libelle_j) $libelle_j .= trim($info_606_j[$a][$j]) ;
				else $libelle_j .= " ** ".trim($info_606_j[$a][$j]) ;
		}
		if (!$libelle_j) $libelle_final = trim($info_606_a[$a][0]) ;
			else $libelle_final = trim($info_606_a[$a][0])." ** ".$libelle_j ;
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
			$res_x = categories::searchLibelle(addslashes(trim($info_606_x[$a][$x])), $thesaurus_defaut, 'fr_FR', $categ_parent);
			if ($res_x) {
				$categ_parent = $res_x;
			} else {
				$categ_parent = create_categ($categ_parent, trim($info_606_x[$a][$x]), strip_empty_words($info_606_x[$a][$x], 'fr_FR'));
			}
		} // fin récup des $x en cascade sous l'id de la catégorie 606$a
		
		if ($categ_parent != $id_rech_theme) {
			// insertion dans la table notices_categories
			$rqt_ajout = "INSERT INTO notices_categories (notcateg_notice,num_noeud,ordre_categorie) VALUES($notice_id,$categ_parent,$a)";
			$res_ajout = @mysql_query($rqt_ajout, $dbh);
		}
		
		// récup TOUT EN CASCADE
		$id_rech_geo = $categ_parent ;		
		// récup des categ géo à loger sous la categ géo principale
		$categ_parent =  $id_rech_geo ;
		for ($y=0 ; $y < sizeof($info_606_y[$a]) ; $y++) {
			$res_y = categories::searchLibelle(addslashes(trim($info_606_y[$a][$y])), $thesaurus_defaut, 'fr_FR', $categ_parent);
			if($res_y) {
				$categ_parent = $res_y;
			} else {
				$categ_parent = create_categ($categ_parent, trim($info_606_y[$a][$y]), strip_empty_words($info_606_y[$a][$y], 'fr_FR'));
			}
		} // fin récup des $y en cascade sous l'id de la catégorie principale thème géo
		
		if ($categ_parent != $id_rech_geo) {
			// insertion dans la table notices_categories
			$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', num_noeud='".$categ_parent."' " ;
			$res_ajout = @mysql_query($rqt_ajout, $dbh);
		}

		// récup TOUT EN CASCADE
		$id_rech_chrono = $categ_parent ;		
		// récup des categ chrono à loger sous la categ chrono principale
		$categ_parent =  $id_rech_chrono ;
		for ($z=0 ; $z < sizeof($info_606_z[$a]) ; $z++) {
			$res_z = categories::searchLibelle(addslashes(trim($info_606_z[$a][$z])), $thesaurus_defaut, 'fr_FR', $categ_parent);
			if ($res_z) {
				$categ_parent = $res_z;
			} else {
				$categ_parent = create_categ($categ_parent, trim($info_606_z[$a][$z]), strip_empty_words($info_606_z[$a][$z], 'fr_FR'));
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
	
	global $prix, $notice_id, $info_995, $typdoc_995, $tdoc_codage, $book_lender_id, 
		$section_995, $sdoc_codage, $book_statut_id, $locdoc_codage, $codstatdoc_995, $statisdoc_codage,
		$cote_mandatory, $book_location_id, $nb_expl_ignores ;
		
	// la zone 995 est répétable
	for ($nb_expl = 0; $nb_expl < sizeof ($info_995); $nb_expl++) {
		/* RAZ expl */
		$expl = array();
		
		/* préparation du tableau à passer à la méthode */
		$expl['cb'] 	    = $info_995[$nb_expl]['a'];
		if (!$expl['cb']) $expl['cb']="NOTI-".$notice_id;
		
		$expl['notice']     = $notice_id ;
		$expl['cote'] 		= $info_995[$nb_expl]['f'];
		$expl['note']       = $info_995[$nb_expl]['u'];
		$expl['prix']       = $info_995[$nb_expl]['B'];
		
		$expl['date_depot'] = today();      
		$expl['date_retour'] = today();
		
		// type de support
		$data_doc=array();
		$data_doc['tdoc_libelle'] = "Type doc - ".$info_995[$nb_expl]['c'];
		$data_doc['duree_pret'] = 0 ; /* valeur par défaut */
		$data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['c'] ;
		$data_doc['tdoc_owner'] = 0 ;
		$expl['typdoc'] = docs_type::import($data_doc);
		
		// $expl['section']    = $info_995[$nb_expl]['x']; à chercher dans docs_section
		$data_doc=array();
		$data_doc['section_libelle'] = $info_995[$nb_expl]['x'];
		$data_doc['sdoc_codage_import'] = $info_995[$nb_expl]['x'] ;
		$data_doc['sdoc_owner'] = 0 ;
		$expl['section'] = docs_section::import($data_doc);
		if (!$expl['section']) $expl['section']=31;
		
		// $expl['statut']
		$data_doc=array();
		$data_doc['statut_libelle'] = "Statut - ".$info_995[$nb_expl]['y'];
		$data_doc['pret_flag'] = 1 ; 
		$data_doc['statusdoc_codage_import'] = $info_995[$nb_expl]['y'] ;
		$data_doc['statusdoc_owner'] = 0 ;
		$expl['statut'] = docs_statut::import($data_doc);
		
		// $expl['location']
		$data_doc=array();
		$data_doc['location_libelle'] = "Localisation - ".$info_995[$nb_expl]['w'];
		$data_doc['locdoc_codage_import'] = $info_995[$nb_expl]['w'] ;
		$data_doc['locdoc_owner'] = 0 ;
		$expl['location'] = docs_location::import($data_doc);
		
		// $expl['codestat']   = $info_995[$nb_expl]['O']; (O majuscule, pas zéro)
		$data_doc=array();
		$data_doc['codestat_libelle'] = "Code stat - ".$info_995[$nb_expl]['O'];
		$data_doc['statisdoc_codage_import'] = $info_995[$nb_expl]['O'] ;
		$data_doc['statisdoc_owner'] = 0 ;
		$expl['codestat'] = docs_codestat::import($data_doc);
		if (!$expl['codestat']) $expl['codestat']=38;
		 
		// $expl['expl_owner']
		$data_doc=array();
		$data_doc['lender_libelle'] = $info_995[$nb_expl]['R'];
		$expl['expl_owner'] = lender::import($data_doc) ;
		if (!$expl['expl_owner']) $expl['expl_owner']=3;
		  
		$expl['cote_mandatory'] = $cote_mandatory ;
		$expl_id = exemplaire::import($expl);
		if ($expl_id == 0) {
			$nb_expl_ignores++;
			}
                      	
		// Numéro du jeu
		if ($info_995[$nb_expl]['v'] && $expl_id) {
			$requete="insert into expl_custom_values (expl_custom_champ,expl_custom_origine,expl_custom_small_text) values(1,$expl_id,'".addslashes($info_995[$nb_expl]['v'])."')";
			mysql_query($requete);
			}
	
		//debug : affichage zone 995 
		/*
		echo "995\$a =".$info_995[$nb_expl]['a']."<br />";
		echo "995\$b =".$info_995[$nb_expl]['b']."<br />";
		echo "995\$c =".$info_995[$nb_expl]['c']."<br />";
		echo "995\$d =".$info_995[$nb_expl]['d']."<br />";
		echo "995\$f =".$info_995[$nb_expl]['f']."<br />";
		echo "995\$k =".$info_995[$nb_expl]['k']."<br />";
		echo "995\$m =".$info_995[$nb_expl]['m']."<br />";
		echo "995\$n =".$info_995[$nb_expl]['n']."<br />";
		echo "995\$o =".$info_995[$nb_expl]['o']."<br />";
		echo "995\$q =".$info_995[$nb_expl]['q']."<br />";
		echo "995\$r =".$info_995[$nb_expl]['r']."<br />";
		echo "995\$u =".$info_995[$nb_expl]['u']."<br /><br />";
		*/
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