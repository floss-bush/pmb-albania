<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_atal.inc.php,v 1.8 2009-05-16 11:15:42 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// récupération des données d'un concurrent :
/* 	la 995 reconstituée est du type : 
  <f c='995' ind='  '>
    <s c='6'>Roman</s>
    <s c='f'>01000040</s>
    <s c='k'>R BEN i </s>
    <s c='4'>1</s>
    <s c='5'>ADU</s>
  </f>

$4 1 : serait là pour dire livre ? >> type doc 
$5 : ADU = public visé ? : >> dans les stats
$6 : la section, en l'absence on pencherait pour les documentaires >> docs section
$a : propriétaire mais mal renseigné
$k : cote
$f : code barre : distinguer le propriétaire avec le CB de la BDP dépositaire...
*/


function recup_noticeunimarc_suite($notice) {
	} // fin recup_noticeunimarc_suite = fin récupération des variables propres BDP : rien de plus
	
function import_new_notice_suite() {
	} // fin import_new_notice_suite
			
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
	global $msg, $dbh ;
	
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
		$expl['cote'] 		= $info_995[$nb_expl]['k'];
        $expl['note']       = $info_995[$nb_expl]['u'];
		$expl['prix']       = $price;              	
		
		$data_doc=array();
		$data_doc['tdoc_libelle'] = $typdoc_995[$info_995[$nb_expl]['4']];
		if (!$data_doc['tdoc_libelle']) $data_doc['tdoc_libelle'] = "Reprise LIVRE -".$info_995[$nb_expl]['4']."-" ;
		$data_doc['duree_pret'] = 15 ; /* valeur par défaut */
		$data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['4'] ;
		if ($tdoc_codage) $data_doc['tdoc_owner'] = $book_lender_id ;
			else $data_doc['tdoc_owner'] = 0 ;
		$expl['typdoc'] = docs_type::import($data_doc);
		
		$data_doc=array();
		if (!$info_995[$nb_expl]['6']) 
			$info_995[$nb_expl]['6'] = "u";
		$data_doc['section_libelle'] = "Reprise ".$info_995[$nb_expl]['6'];
		$data_doc['sdoc_codage_import'] = $info_995[$nb_expl]['6'] ;
		if ($sdoc_codage) $data_doc['sdoc_owner'] = $book_lender_id ;
			else $data_doc['sdoc_owner'] = 0 ;
		$expl['section'] = docs_section::import($data_doc);
		
		$expl['statut'] = $book_statut_id;
		
		$expl['location'] = $book_location_id;
				
		$data_doc=array();
		if (!$info_995[$nb_expl]['5']) $info_995[$nb_expl]['5']="ADU";
		$data_doc['codestat_libelle'] = $codstatdoc_995[$info_995[$nb_expl]['5']];
		if (!$data_doc['codestat_libelle']) $data_doc['codestat_libelle'] = "Statistique ".$info_995[$nb_expl]['5'];  
		$data_doc['statisdoc_codage_import'] = $info_995[$nb_expl]['5'] ;
		if ($statisdoc_codage) $data_doc['statisdoc_owner'] = $book_lender_id ;
			else $data_doc['statisdoc_owner'] = 0 ;
		$expl['codestat'] = docs_codestat::import($data_doc);
		
		// calcul du proprétaire sur le CB car les 995 ne sont pas propres propres
		if (substr((string)$expl['cb'],0,6)=="337000") $expl['expl_owner'] = 1 ;
			else  $expl['expl_owner']= 2 ;
		
		// $expl['expl_owner'] = $book_lender_id ;
		
		
		$expl['cote_mandatory'] = $cote_mandatory ;
		$expl_id = exemplaire::import($expl);
		if ($expl_id == 0) {
			$nb_expl_ignores++;
			}
                      	
		//debug : affichage zone 995 
		/*
		echo "995\$4 =".$info_995[$nb_expl]['4']."<br />";
		echo "995\$5 =".$info_995[$nb_expl]['5']."<br />";
		echo "995\$6 =".$info_995[$nb_expl]['6']."<br />";
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