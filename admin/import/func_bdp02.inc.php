<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_bdp02.inc.php,v 1.16 2009-05-16 11:15:41 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// DEBUT paramétrage propre à la base de données d'importation :
// 995 $8 = section
$bdp02section_995['ANI'] = "Animation" ; 
$bdp02section_995['AVI'] = "Audiovisuel" ; 
$bdp02section_995['LIV'] = "Livres" ; 
$bdp02section_995['DOC'] = "Documentation" ; 
$bdp02section_995['LOC'] = "Fonds local" ; 
$bdp02section_995['PRO'] = "Fonds profesionnel" ; 
$bdp02section_995['RES'] = "Réserve" ; 

// 995 $9 = type documents
$bdp02typdoc_995['A']="Album";
$bdp02typdoc_995['B']="Biographie";
$bdp02typdoc_995['BDA']="Bandes dessinées adultes";
$bdp02typdoc_995['BDJ']="Bandes dessinées jeunesse";
$bdp02typdoc_995['BDS']="Bandes dessinées en série";
$bdp02typdoc_995['C']="Contes";
$bdp02typdoc_995['DCD']="CD audio";
$bdp02typdoc_995['DK']="k7 audio";
$bdp02typdoc_995['DLA']="méthode de langue";
$bdp02typdoc_995['DLC']="Livre CD audio";
$bdp02typdoc_995['DLK']="Livre K7 audio";
$bdp02typdoc_995['DLU']="Livre lu";
$bdp02typdoc_995['DMI']="Musique imprimée";
$bdp02typdoc_995['DOA']="Documentaires adultes";
$bdp02typdoc_995['DOJ']="Documentaires jeunesse";
$bdp02typdoc_995['ER']="Roman jeunesse";
$bdp02typdoc_995['KS']="Caisse de BD";
$bdp02typdoc_995['KSAVI']="Caisse audiovisuelle";
$bdp02typdoc_995['MUL']="Cédérom";
$bdp02typdoc_995['OBJ']="Objet";
$bdp02typdoc_995['P']="Policier";
$bdp02typdoc_995['PAN']="Panneau expo";
$bdp02typdoc_995['R']="Roman";
$bdp02typdoc_995['REV']="Périodiques";
$bdp02typdoc_995['SF']="Science-fiction";
$bdp02typdoc_995['TM']="Travaux manuels";
$bdp02typdoc_995['U']="Usuels de référence";
$bdp02typdoc_995['VD']="Vidéo documentaire";
$bdp02typdoc_995['VDD']="DVD documentaire";
$bdp02typdoc_995['VDF']="DVD fiction";
$bdp02typdoc_995['VDJ']="DVD jeunesse";
$bdp02typdoc_995['VF']="Vidéo fiction";
$bdp02typdoc_995['VJ']="Vidéo jeunesse";
// FIN paramétrage 

function recup_noticeunimarc_suite($notice) {
	} // fin recup_noticeunimarc_suite = fin récupération des variables propres à la BDP02
	
function import_new_notice_suite() {
	global $index_sujets ;
	global $pmb_keyword_sep ;
	global $dbh;
	global $info_600_a, $info_600_j, $info_600_x, $info_600_y, $info_600_z ;
	global $info_601_a, $info_601_j, $info_601_x, $info_601_y, $info_601_z ;
	global $info_602_a, $info_602_j, $info_602_x, $info_602_y, $info_602_z ;
	global $info_605_a, $info_605_j, $info_605_x, $info_605_y, $info_605_z ;
	global $info_606_a, $info_606_j, $info_606_x, $info_606_y, $info_606_z ;
	global $info_607_a, $info_607_j, $info_607_x, $info_607_y, $info_607_z ;

	if (is_array($index_sujets)) $mots_cles = implode (" $pmb_keyword_sep ",$index_sujets);
		else $mots_cles = $index_sujets;
	
	for ($a=0; $a<sizeof($info_600_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_600_a[$a][0] ;
		for ($j=0; $j<sizeof($info_600_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_600_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_600_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_600_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_600_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_601_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_601_a[$a][0] ;
		for ($j=0; $j<sizeof($info_601_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_601_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_601_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_601_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_601_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_602_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_602_a[$a][0] ;
		for ($j=0; $j<sizeof($info_602_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_602_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_602_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_602_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_602_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_605_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_605_a[$a][0] ;
		for ($j=0; $j<sizeof($info_605_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_605_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_605_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_605_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_605_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_606_a[$a][0] ;
		for ($j=0; $j<sizeof($info_606_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_606_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_606_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_606_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_606_z[$a][$j] ;
		}
	for ($a=0; $a<sizeof($info_607_a); $a++) {
		$mots_cles .= " $pmb_keyword_sep ".$info_607_a[$a][0] ;
		for ($j=0; $j<sizeof($info_607_j[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_j[$a][$j] ;
		for ($j=0; $j<sizeof($info_607_x[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_x[$a][$j] ;
		for ($j=0; $j<sizeof($info_607_y[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_y[$a][$j] ;
		for ($j=0; $j<sizeof($info_607_z[$a]); $j++) $mots_cles .= " $pmb_keyword_sep ".$info_607_z[$a][$j] ;
		}
	$mots_cles ? $index_matieres = strip_empty_words($mots_cles) : $index_matieres = '';
	$rqt_maj = "update notices set index_l='".addslashes($mots_cles)."', index_matieres=' ".addslashes($index_matieres)." ' where notice_id='$notice_id' " ;
	$res_ajout = mysql_query($rqt_maj, $dbh);
	} // fin import_new_notice_suite
			
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
	global $msg, $dbh ;
	global $nb_expl_ignores ;
	global $prix, $notice_id, $info_995, $typdoc_995, $tdoc_codage, $book_lender_id, 
		$bdp02section_995, $bdp02typdoc_995, $sdoc_codage, $book_statut_id, $locdoc_codage, $codstatdoc_995, $statisdoc_codage,
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
		
		// $expl['typdoc']     = $info_995[$nb_expl]['9']; à chercher dans docs_typdoc
		$data_doc=array();
		$data_doc['tdoc_libelle'] = $bdp02typdoc_995[$info_995[$nb_expl]['9']];
		$data_doc['duree_pret'] = 0 ; /* valeur par défaut */
		$data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['9'] ;
		if ($tdoc_codage) $data_doc['tdoc_owner'] = $book_lender_id ;
			else $data_doc['tdoc_owner'] = 0 ;
		$expl['typdoc'] = docs_type::import($data_doc);
		
		$expl['cote'] = $info_995[$nb_expl]['k'];
                      	
		// $expl['section']    = $info_995['8']; à chercher dans docs_section
		$data_doc=array();
		$data_doc['section_libelle'] = $bdp02section_995[$info_995[$nb_expl]['8']];
		$data_doc['sdoc_codage_import'] = $info_995[$nb_expl]['8'] ;
		if ($sdoc_codage) $data_doc['sdoc_owner'] = $book_lender_id ;
			else $data_doc['sdoc_owner'] = 0 ;
		$expl['section'] = docs_section::import($data_doc);
		
		// echo "Section : ".$bdp02section_995[$info_995[$nb_expl]['8']]." - ".$info_995[$nb_expl]['8']."<br />" ; 
		// echo "TypDoc : ".$bdp02typdoc_995[$info_995[$nb_expl]['9']]." - ".$info_995[$nb_expl]['9']."<br />" ; 

		// le statut est choisi lors de l'import
		
		$expl['statut'] = $book_statut_id;
		
		$expl['location'] = $book_location_id;
		
		// $expl['codestat']   = $info_995[$nb_expl]['q']; 'q' utilisé, éventuellement à fixer par combo_box
		$data_doc=array();
		//$data_doc['codestat_libelle'] = $info_995[$nb_expl]['q']." -Pub visé importé (".$book_lender_id.")";
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

// fonction spécifique d'export de la zone 995
function export_traite_exemplaires ($ex=array()) {
	global $msg, $dbh ;
	
	$subfields["a"] = $ex -> lender_libelle;
	$subfields["c"] = $ex -> lender_libelle;
	$subfields["f"] = $ex -> expl_cb;
	$subfields["k"] = $ex -> expl_cote;
	$subfields["u"] = $ex -> expl_note;

	$subfields["o"] = "e";
	$subfields["9"] = $ex -> tdoc_codage_import;
	$subfields["r"] = "uu";
	$subfields["8"] = $ex -> sdoc_codage_import;
	$subfields["q"] = "u";

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