<?php
// +-------------------------------------------------+
//  2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_cpt_rameau_first_level.inc.php,v 1.4.4.1 2011-09-27 14:57:00 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

/*
	Fonction personnalisée d'import pour importer au premier niveau des catégories
	les vedettes matières rameau (BnF, SUDOC, etc.)
	Les vedettes sont reconstruites avec les zones 600 à 607, $a, , $x, $y, $z
*/

// enregistrement de la notices dans les catégories
require_once "$include_path/misc.inc.php" ;
require_once($class_path."/thesaurus.class.php");
require_once($class_path."/categories.class.php");
global $thesaurus_defaut;

//Attention, dans le multithesaurus, le thesaurus dans lequel on importe est le thesaurus par defaut
$thes = new thesaurus($thesaurus_defaut);

function recup_noticeunimarc_suite($notice) {
	} // fin recup_noticeunimarc_suite = fin récupération des variables propres BDP : rien de plus
	
function import_new_notice_suite() {
	global $dbh ;
	global $notice_id ;
	global $thes;
	global $index_sujets ;
	global $pmb_keyword_sep ;
	
	global $info_600_a, $info_600_j, $info_600_x, $info_600_y, $info_600_z ;
	global $info_601_a, $info_601_j, $info_601_x, $info_601_y, $info_601_z ;
	global $info_602_a, $info_602_j, $info_602_x, $info_602_y, $info_602_z ;
	global $info_605_a, $info_605_j, $info_605_x, $info_605_y, $info_605_z ;
	global $info_606_a, $info_606_j, $info_606_x, $info_606_y, $info_606_z ;
	global $info_607_a, $info_607_j, $info_607_x, $info_607_y, $info_607_z ;
	
	for ($a=0; $a<sizeof($info_600_a); $a++) {
		$rameau .= "@@@".trim($info_600_a[$a][0]);
		for ($j=0; $j<sizeof($info_600_j[$a]); $j++) $rameau .= " -- ".trim($info_600_j[$a][$j]);
		for ($j=0; $j<sizeof($info_600_x[$a]); $j++) $rameau .= " -- ".trim($info_600_x[$a][$j]);
		for ($j=0; $j<sizeof($info_600_y[$a]); $j++) $rameau .= " -- ".trim($info_600_y[$a][$j]);
		for ($j=0; $j<sizeof($info_600_z[$a]); $j++) $rameau .= " -- ".trim($info_600_z[$a][$j]);
		}
	for ($a=0; $a<sizeof($info_601_a); $a++) {
		$rameau .= "@@@".trim($info_601_a[$a][0]);
		for ($j=0; $j<sizeof($info_601_j[$a]); $j++) $rameau .= " -- ".trim($info_601_j[$a][$j]);
		for ($j=0; $j<sizeof($info_601_x[$a]); $j++) $rameau .= " -- ".trim($info_601_x[$a][$j]);
		for ($j=0; $j<sizeof($info_601_y[$a]); $j++) $rameau .= " -- ".trim($info_601_y[$a][$j]);
		for ($j=0; $j<sizeof($info_601_z[$a]); $j++) $rameau .= " -- ".trim($info_601_z[$a][$j]);
		}
	for ($a=0; $a<sizeof($info_602_a); $a++) {
		$rameau .= "@@@".trim($info_602_a[$a][0]);
		for ($j=0; $j<sizeof($info_602_j[$a]); $j++) $rameau .= " -- ".trim($info_602_j[$a][$j]);
		for ($j=0; $j<sizeof($info_602_x[$a]); $j++) $rameau .= " -- ".trim($info_602_x[$a][$j]);
		for ($j=0; $j<sizeof($info_602_y[$a]); $j++) $rameau .= " -- ".trim($info_602_y[$a][$j]);
		for ($j=0; $j<sizeof($info_602_z[$a]); $j++) $rameau .= " -- ".trim($info_602_z[$a][$j]);
		}
	for ($a=0; $a<sizeof($info_605_a); $a++) {
		$rameau .= "@@@".trim($info_605_a[$a][0]);
		for ($j=0; $j<sizeof($info_605_j[$a]); $j++) $rameau .= " -- ".trim($info_605_j[$a][$j]);
		for ($j=0; $j<sizeof($info_605_x[$a]); $j++) $rameau .= " -- ".trim($info_605_x[$a][$j]);
		for ($j=0; $j<sizeof($info_605_y[$a]); $j++) $rameau .= " -- ".trim($info_605_y[$a][$j]);
		for ($j=0; $j<sizeof($info_605_z[$a]); $j++) $rameau .= " -- ".trim($info_605_z[$a][$j]);
		}
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		$rameau .= "@@@".trim($info_606_a[$a][0]);
		for ($j=0; $j<sizeof($info_606_j[$a]); $j++) $rameau .= " -- ".trim($info_606_j[$a][$j]);
		for ($j=0; $j<sizeof($info_606_x[$a]); $j++) $rameau .= " -- ".trim($info_606_x[$a][$j]);
		for ($j=0; $j<sizeof($info_606_y[$a]); $j++) $rameau .= " -- ".trim($info_606_y[$a][$j]);
		for ($j=0; $j<sizeof($info_606_z[$a]); $j++) $rameau .= " -- ".trim($info_606_z[$a][$j]);
		}
	for ($a=0; $a<sizeof($info_607_a); $a++) {
		$rameau .= "@@@".trim($info_607_a[$a][0]);
		for ($j=0; $j<sizeof($info_607_j[$a]); $j++) $rameau .= " -- ".trim($info_607_j[$a][$j]);
		for ($j=0; $j<sizeof($info_607_x[$a]); $j++) $rameau .= " -- ".trim($info_607_x[$a][$j]);
		for ($j=0; $j<sizeof($info_607_y[$a]); $j++) $rameau .= " -- ".trim($info_607_y[$a][$j]);
		for ($j=0; $j<sizeof($info_607_z[$a]); $j++) $rameau .= " -- ".trim($info_607_z[$a][$j]);
		}

	$categ_first=explode("@@@",stripslashes($rameau));
	for ($i=1; $i<count($categ_first); $i++) {
		$resultat = categories::searchLibelle(addslashes($categ_first[$i]), $thesaurus_defaut, 'fr_FR');
		if (!$resultat){
			/*vérification de l'existence des categs, sinon création */
			$resultat = create_categ_cpt_rameau_first_level($thes->num_noeud_racine, $categ_first[$i], ' '.strip_empty_words($categ_first[$i]).' ');
		} 
		/* ajout de l'indexation à la notice dans la table notices_categories*/
		$rqt_ajout = "insert into notices_categories set notcateg_notice='".$notice_id."', num_noeud='".$resultat."', ordre_categorie=".($i-1) ;
		$res_ajout = @mysql_query($rqt_ajout, $dbh);
	}
} // fin import_new_notice_suite
			
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
	global $msg, $dbh ;
	global $nb_expl_ignores ;
	global $prix, $notice_id, $info_995, $typdoc_995, $tdoc_codage, $book_lender_id, 
		$section_995, $sdoc_codage, $book_statut_id, $locdoc_codage, $codstatdoc_995, $statisdoc_codage,
		$cote_mandatory, $book_location_id ;
		
	// lu en 010$d de la notice
	$price = $prix[0];
	
	// la zone 995 est répétable
	for ($nb_expl = 0; $nb_expl < sizeof ($info_995); $nb_expl++) {
		/* RAZ expl */
		$expl = array();
		
		/* préparation du tableau à passer à la méthode */
		$expl['cb'] 	    = $info_995[$nb_expl]['f'];
		$expl['notice']     = $notice_id ;
		
		// $expl['typdoc']     = $info_995[$nb_expl]['r']; à chercher dans docs_typdoc
		$data_doc=array();
		//$data_doc['tdoc_libelle'] = $info_995[$nb_expl]['r']." -Type doc importé(".$book_lender_id.")";
		$data_doc['tdoc_libelle'] = $typdoc_995[$info_995[$nb_expl]['r']];
		if (!$data_doc['tdoc_libelle']) $data_doc['tdoc_libelle'] = "\$r non conforme -".$info_995[$nb_expl]['r']."-" ;
		$data_doc['duree_pret'] = 0 ; /* valeur par défaut */
		$data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['r'] ;
		if ($tdoc_codage) $data_doc['tdoc_owner'] = $book_lender_id ;
			else $data_doc['tdoc_owner'] = 0 ;
		$expl['typdoc'] = docs_type::import($data_doc);
		
		$expl['cote'] = $info_995[$nb_expl]['k'];
                      	
		// $expl['section']    = $info_995[$nb_expl]['q']; à chercher dans docs_section
		$data_doc=array();
		$info_995[$nb_expl]['q']=trim($info_995[$nb_expl]['q']);
		if (!$info_995[$nb_expl]['q']) 
			$info_995[$nb_expl]['q'] = "u";
		$data_doc['section_libelle'] = $section_995[$info_995[$nb_expl]['q']];
		$data_doc['sdoc_codage_import'] = $info_995[$nb_expl]['q'] ;
		if ($sdoc_codage) $data_doc['sdoc_owner'] = $book_lender_id ;
			else $data_doc['sdoc_owner'] = 0 ;
		$expl['section'] = docs_section::import($data_doc);
		
		$expl['statut'] = $book_statut_id;
		
		$expl['location'] = $book_location_id;
		
		// $expl['codestat']   = $info_995[$nb_expl]['q']; 'q' utilisé éventuellement à fixer par combo_box
		$data_doc=array();
		//$data_doc['codestat_libelle'] = $info_995[$nb_expl]['q']." -Pub visé importé(".$book_lender_id.")";
		$data_doc['codestat_libelle'] = $codstatdoc_995[$info_995[$nb_expl]['q']];
		$data_doc['statisdoc_codage_import'] = $info_995[$nb_expl]['q'] ;
		if ($statisdoc_codage) $data_doc['statisdoc_owner'] = $book_lender_id ;
			else $data_doc['statisdoc_owner'] = 0 ;
		$expl['codestat'] = docs_codestat::import($data_doc);
		
		
		// $expl['creation']   = $info_995[$nb_expl]['']; à préciser
		// $expl['modif']      = $info_995[$nb_expl]['']; à préciser
                      	
		$expl['note']       = $info_995[$nb_expl]['u'];
		$expl['prix']       = $price;
		$expl['expl_owner'] = $book_lender_id ;
		$expl['cote_mandatory'] = $cote_mandatory ;
		
		$expl['date_depot'] = substr($info_995[$nb_expl]['m'],0,4)."-".substr($info_995[$nb_expl]['m'],4,2)."-".substr($info_995[$nb_expl]['m'],6,2) ;      
		$expl['date_retour'] = substr($info_995[$nb_expl]['n'],0,4)."-".substr($info_995[$nb_expl]['n'],4,2)."-".substr($info_995[$nb_expl]['n'],6,2) ;
		
		// quoi_faire
		if ($info_995[$nb_expl]['0']) $expl['quoi_faire'] = $info_995[$nb_expl]['0']  ;
			else $expl['quoi_faire'] = 2 ;
		
		$expl_id = exemplaire::import($expl);
		if ($expl_id == 0) {
			$nb_expl_ignores++;
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

function create_categ_cpt_rameau_first_level($num_parent, $libelle, $index) {
	
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
