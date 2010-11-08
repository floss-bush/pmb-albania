<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_cnl.inc.php,v 1.17 2007-03-10 08:32:23 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");




// +-------------------------------------------------+

// Attention, n'a pas été modifié pour le multi-thesaurus

// +-------------------------------------------------+





// DEBUT paramétrage propre à la base de données d'importation :
//	les champs UNIMARC lus qui vont être stockés dans des champs personnalisés sont précisés ici
$id949_c = 1 ;
$id949_b = 2 ;
// aucun code statistique n'est fourni dans la zone d'exemplaires du CNL 997, il faut préciser l'id ici
$idcode_stat_expl = 1 ;
// récupération du 606 : récup en catégories en essayant de classer :
//	les sujets sous le terme "Recherche par terme" 
		$id_rech_theme = 1 ;
//	les précisions géographiques sous le terme "Recherche géographique" 
		$id_rech_geo = 2 ;
//	les précisions de période sous le terme "Recherche chronologique" 
		$id_rech_chrono = 3 ;
// FIN paramétrage 

function recup_noticeunimarc_suite($notice) {
	global $info_949		;
	global $info_997		;

	$info_949 = array();
	$info_997 = array();

	$record = new iso2709_record($notice, AUTO_UPDATE); 
	for ($i=0;$i<count($record->inner_directory);$i++) {
		$cle=$record->inner_directory[$i]['label'];
		switch($cle) {
			case "949": // infos CNL
				$info_949=$record->get_subfield($cle,"a","b","c");
				break;
			case "997": // infos expl CNL
				$info_997=$record->get_subfield($cle,"3","a","c","d","t");
				break;
			default:
				break;
	
			} /* end of switch */
	
		} /* end of for */
	} // fin recup_noticeunimarc_suite = fin récupération des variables propres au CNL
	
function import_new_notice_suite() {
	global $dbh ;
	global $notice_id ;
	
	global $info_949 ;
	global $id949_c, $id949_b ;
	
	global $info_606_a, $info_606_j, $info_606_x, $info_606_y, $info_606_z ;
	global $id_rech_theme, $id_rech_geo, $id_rech_chrono ; 
	
	/* 
	echo "<pre>";
	print_r ($info_949);
	print_r ($info_997);
	echo "</pre>";
	*/
	
	// 949$c est stocké dans un champ personnalisé "liste de valeur"
	// ce champ personnalisé a l'id $id949_c
	// le code de la liste de valeur (949$c) est dans notices_custom_lists.notices_custom_list_value WHERE notices_custom_champ=$id949_c
	// TRAITEMENT :
	//	Chercher si 949$c est présent dans notices_custom_lists.notices_custom_list_value where notices_custom_champ=$id949_c
	//	Créer si besoin = INSERT INTO notices_custom_lists (notices_custom_champ, notices_custom_list_value, notices_custom_list_lib, ordre) VALUES ($id949_c, $info_949['c'], $info_949['c']." -Importé", 0 )
	//	Rechercher si l'enregistrement existe déjà dans notices_custom_values = SELECT 1 FROM notices_custom_values WHERE notices_custom_champ=$id949_c AND notices_custom_origine=$notice_id AND notices_custom_small_text=$info_949['c']
	//	Créer si besoin
	// CORRECTION suite à test d'import : $c contient $a."texte à la con" on ne retient donc que $a
	$info_949[0]['c'] = $info_949[0]['a'] ; 
	$rqt = "select count(1) from notices_custom_lists where notices_custom_champ='".$id949_c."' and notices_custom_list_value='".$info_949[0]['c']."'" ;
	if (!mysql_result(mysql_query($rqt, $dbh),0,0)) {
		$rqt_ajout = "INSERT INTO notices_custom_lists (notices_custom_champ, notices_custom_list_value, notices_custom_list_lib, ordre) VALUES ('".$id949_c."', '".$info_949[0]['c']."', '".$info_949[0]['c']." -Importé','0')" ;
		$res_ajout = mysql_query ($rqt_ajout, $dbh) ;
		}
	$rqt = "SELECT count(1) FROM notices_custom_values WHERE notices_custom_champ='".$id949_c."' AND notices_custom_origine='".$notice_id."' " ;
	if (!mysql_result(mysql_query($rqt, $dbh),0,0)) {
		$rqt_ajout = "INSERT INTO notices_custom_values (notices_custom_champ, notices_custom_origine, notices_custom_small_text) VALUES ('".$id949_c."', '".$notice_id."', '".$info_949[0]['c']."') " ;
		$res_ajout = mysql_query ($rqt_ajout, $dbh) ;
		}
	
	// 949$b est stocké dans un champ personnalisé texte
	// ce champ personnalisé a l'id $id949_b
	// TRAITEMENT :
	//	Rechercher si l'enregistrement existe déjà dans notices_custom_values = SELECT 1 FROM notices_custom_values WHERE notices_custom_champ=$id949_b AND notices_custom_origine=$notice_id
	//	Créer si besoin
	$rqt = "SELECT count(1) FROM notices_custom_values WHERE notices_custom_champ='".$id949_b."' AND notices_custom_origine='".$notice_id."' " ;
	if (!mysql_result(mysql_query($rqt, $dbh),0,0)) {
		$rqt_ajout = "INSERT INTO notices_custom_values (notices_custom_champ, notices_custom_origine, notices_custom_small_text) VALUES ('".$id949_b."', '".$notice_id."', '".$info_949[0]['b']."')" ;
		$res_ajout = mysql_query ($rqt_ajout, $dbh) ;
		}

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
	//		SELECT categ_id FROM categories WHERE categ_parent='".$id_rech_theme."' AND categ_libelle='".addslashes($libelle_final)."' "
	//	Créer si besoin et récupérer l'id $categid_a
	//	$categid_parent =  $categid_a
	//	pour $x=0 à size_of $info_606_x[$a]
	//		Rechercher si l'enregistrement existe déjà dans categories = 
	//			SELECT categ_id FROM categories WHERE categ_parent='".$categ_parent."' AND categ_libelle='".addslashes($info_606_x[$a][$x])."' "
	//		Créer si besoin et récupérer l'id $categid_parent
	//
	//	$categid_parent =  $id_rech_geo
	//	pour $y=0 à size_of $info_606_y[$a]
	//		Rechercher si l'enregistrement existe déjà dans categories = 
	//			SELECT categ_id FROM categories WHERE categ_parent='".$categ_parent."' AND categ_libelle='".addslashes($info_606_y[$a][$y])."' "
	//		Créer si besoin et récupérer l'id $categid_parent
	//
	//	$categid_parent =  $id_rech_chrono
	//	pour $y=0 à size_of $info_606_z[$a]
	//		Rechercher si l'enregistrement existe déjà dans categories = 
	//			SELECT categ_id FROM categories WHERE categ_parent='".$categ_parent."' AND categ_libelle='".addslashes($info_606_z[$a][$y])."' "
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
		$rqt_a = "SELECT categ_id FROM categories WHERE categ_parent='".$id_rech_theme."' AND categ_libelle='".addslashes($libelle_final)."' " ;
		$res_a = mysql_query($rqt_a,$dbh) ;
		if (mysql_num_rows($res_a)) {
			$categid_a = mysql_result($res_a, 0, 0) ;
			} else {
				$rqt_ajout = "insert into categories set categ_parent='".$id_rech_theme."', categ_libelle='".addslashes($libelle_final)."', index_categorie=' ".strip_empty_words($libelle_final)." ' " ;
				$res_ajout = mysql_query($rqt_ajout, $dbh);
				$categid_a = mysql_insert_id($dbh) ;
				}
		// récup des sous-categ en cascade sous $a
		$categ_parent =  $categid_a ;
		for ($x=0 ; $x < sizeof($info_606_x[$a]) ; $x++) {
			$rqt_x = "SELECT categ_id FROM categories WHERE categ_parent='".$categ_parent."' AND categ_libelle='".addslashes($info_606_x[$a][$x])."' " ;
			$res_x = mysql_query($rqt_x,$dbh) ;
			if (mysql_num_rows($res_x)) {
				$categ_parent = mysql_result($res_x, 0, 0) ;
				} else {
					$rqt_ajout = "insert into categories set categ_parent='".$categ_parent."', categ_libelle='".addslashes($info_606_x[$a][$x])."', index_categorie=' ".strip_empty_words($info_606_x[$a][$x])." ' " ;
					$res_ajout = mysql_query($rqt_ajout, $dbh);
					$categ_parent = mysql_insert_id($dbh) ;
					}
			} // fin récup des $x en cascade sous l'id de la catégorie 606$a
		
		if ($categ_parent != $id_rech_theme) {
			// insertion dans la table notices_categories
			$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', notcateg_categorie='".$categ_parent."' " ;
			$res_ajout = @mysql_query($rqt_ajout, $dbh);
			}
				
		// récup des categ géo à loger sous la categ géo principale
		$categ_parent =  $id_rech_geo ;
		for ($y=0 ; $y < sizeof($info_606_y[$a]) ; $y++) {
			$rqt_y = "SELECT categ_id FROM categories WHERE categ_parent='".$categ_parent."' AND categ_libelle='".addslashes($info_606_y[$a][$y])."' " ;
			$res_y = mysql_query($rqt_y,$dbh) ;
			if (mysql_num_rows($res_y)) {
				$categ_parent = mysql_result($res_y, 0, 0) ;
				} else {
					$rqt_ajout = "insert into categories set categ_parent='".$categ_parent."', categ_libelle='".addslashes($info_606_y[$a][$y])."', index_categorie=' ".strip_empty_words($info_606_y[$a][$y])." ' " ;
					$res_ajout = mysql_query($rqt_ajout, $dbh);
					$categ_parent = mysql_insert_id($dbh) ;
					}
			} // fin récup des $y en cascade sous l'id de la catégorie principale thème géo
		
		if ($categ_parent != $id_rech_geo) {
			// insertion dans la table notices_categories
			$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', notcateg_categorie='".$categ_parent."' " ;
			$res_ajout = @mysql_query($rqt_ajout, $dbh);
			}
		
		// récup des categ chrono à loger sous la categ chrono principale
		$categ_parent =  $id_rech_chrono ;
		for ($z=0 ; $z < sizeof($info_606_z[$a]) ; $z++) {
			$rqt_z = "SELECT categ_id FROM categories WHERE categ_parent='".$categ_parent."' AND categ_libelle='".addslashes($info_606_z[$a][$z])."' " ;
			$res_z = mysql_query($rqt_z,$dbh) ;
			if (mysql_num_rows($res_z)) {
				$categ_parent = mysql_result($res_z, 0, 0) ;
				} else {
					$rqt_ajout = "insert into categories set categ_parent='".$categ_parent."', categ_libelle='".addslashes($info_606_z[$a][$z])."', index_categorie=' ".strip_empty_words($info_606_z[$a][$z])." ' " ;
					$res_ajout = mysql_query($rqt_ajout, $dbh);
					$categ_parent = mysql_insert_id($dbh) ;
					}
			} // fin récup des $z en cascade sous l'id de la catégorie principale thème chrono
		
		if ($categ_parent != $id_rech_chrono) {
			// insertion dans la table notices_categories
			$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', notcateg_categorie='".$categ_parent."' " ;
			$res_ajout = @mysql_query($rqt_ajout, $dbh);
			}
		}
	
	} // fin import_new_notice_suite
			
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
	global $msg, $dbh ;
	
	global $prix, $notice_id, $tdoc_codage, $book_lender_id, 
		$sdoc_codage, $book_statut_id, $locdoc_codage, $statisdoc_codage,
		$cote_mandatory ;
		
	global $info_997 ;
	global $idcode_stat_expl ;
	
	// lu en 010$d de la notice
	$price = $prix[0];
	
	// la zone 997 est répétable ?
	for ($nb_expl = 0; $nb_expl < sizeof ($info_997); $nb_expl++) {
		/* RAZ expl */
		$expl = array();
		
		/* préparation du tableau à passer à la méthode */
		if (!$info_997[$nb_expl]['a']) $expl['cb'] = "ABS $notice_id" ;
			else $expl['cb'] = $info_997[$nb_expl]['a'];
		$expl['notice']     = $notice_id ;
		
		// Type de document
		$data_doc=array();
		$data_doc['tdoc_libelle'] = $info_997[$nb_expl]['t']." -Importé";
		$data_doc['duree_pret'] = 0 ; /* valeur par défaut */
		$data_doc['tdoc_codage_import'] = $info_997[$nb_expl]['t'] ;
		if ($tdoc_codage) $data_doc['tdoc_owner'] = $book_lender_id ;
			else $data_doc['tdoc_owner'] = 0 ;
		$expl['typdoc'] = docs_type::import($data_doc);
		
		// cote du document : $3 ?
		$expl['cote'] = $info_997[$nb_expl]['3'];
                      	
		// Section
		$data_doc=array();
		$data_doc['section_libelle'] = $info_997[$nb_expl]['d']." -Importé";
		$data_doc['sdoc_codage_import'] = $info_997[$nb_expl]['d'] ;
		if ($sdoc_codage) $data_doc['sdoc_owner'] = $book_lender_id ;
			else $data_doc['sdoc_owner'] = 0 ;
		$expl['section'] = docs_section::import($data_doc);
		
		// Statut : choisi lors de l'import
		$expl['statut'] = $book_statut_id;
		
		// Localisation
		$data_doc=array();
		$data_doc['location_libelle'] = $info_997[$nb_expl]['c']."-Importé";
		$data_doc['locdoc_codage_import'] = $info_997[$nb_expl]['c'] ;
		if ($locdoc_codage) $data_doc['locdoc_owner'] = $book_lender_id ;
			else $data_doc['locdoc_owner'] = 0 ;
		$expl['location'] = docs_location::import($data_doc);
		
		// Code statistique : fixé dans le paramétrage
		$expl['codestat'] = $idcode_stat_expl;
		
		$expl['prix']       = $price;
		$expl['expl_owner'] = $book_lender_id ;
		$expl['cote_mandatory'] = $cote_mandatory ;
		
		$expl_id = exemplaire::import($expl);
		if ($expl_id == 0) {
			$nb_expl_ignores++;
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