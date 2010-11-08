<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_bdp37_pln.inc.php,v 1.19 2009-05-16 11:15:41 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$tpl_beforeupload_expl = "
                <form class='form-$current_module' ENCTYPE=\"multipart/form-data\" METHOD=\"post\" ACTION=\"iimport_expl.php\">
                <h3>".$msg['import_expl_form_titre']."</h3>
                <div class='form-contenu'>

	<INPUT TYPE='hidden' NAME='isbn_mandatory' id='io1' VALUE='1' />
	<INPUT TYPE='hidden' NAME='isbn_dedoublonnage' VALUE='1' />
	<INPUT TYPE='hidden' NAME='cote_mandatory' VALUE='0' />
	<INPUT TYPE='hidden' NAME='book_lender_id' value='1' />
	<INPUT TYPE='hidden' NAME='book_statut_id' value='1' />
	<INPUT TYPE='hidden' NAME='statutnot' value='1' />
	<INPUT TYPE='hidden' NAME='book_location_id' value='1' />
	<input type='hidden' name='isbn_only' value='1'/>

                    <div class='row'>
                        <label class='etiquette'>Supprimer, Mettre à jour ou Ajouter ?</label>
                        </div>
                    <div class='row'>
                        <INPUT TYPE='radio' NAME='que_faire' id='sdc0' VALUE='0' CLASS='radio' /><label for='sdc0'> Supprimer </label><br />
                        <INPUT TYPE='radio' NAME='que_faire' id='sdc1' VALUE='1' CLASS='radio' /><label for='sdc1'> Mettre à jour </label><br />
                        <INPUT TYPE='radio' NAME='que_faire' id='sdc2' VALUE='2' CLASS='radio' /><label for='sdc2'> Ajouter </label><br />
                        </div>

                    <div class='row'>
                        <label class='etiquette' for='txt_suite'>$msg[501]</label>
                        </div>
                    <div class='row'>
                        <INPUT NAME='userfile' class='saisie-80em' TYPE='file' size='60'>
                        <INPUT NAME=\"categ\" TYPE=\"hidden\" value=\"import\">
                        <INPUT NAME=\"sub\" TYPE=\"hidden\" value=\"import_expl\">
                        <INPUT NAME=\"action\" TYPE=\"hidden\" value=\"afterupload\">
                        </div>
                    </div>
                <INPUT TYPE='SUBMIT' class='bouton' NAME='upload' VALUE='".$msg[502]."' />
                </FORM>"; 

$tpl_beforeupload_notices = "";

// créer statut id=1 : indisponible, en prêt
// créer codestat id=1 : rien, juste pour faire joli 
 
function recup_noticeunimarc_suite($notice) {
	// modif en fichier import_func : tous les champs de la zone 995 sont lus
	// traitement PLN : sera traité plus loin, pb des exemplaires. 
	} // fin recup_noticeunimarc_suite = fin récupération des variables propres BDP : rien de plus
	
function import_new_notice_suite() {
	global $dbh ;
	global $notice_id ;
	
	global $index_sujets ;
	global $pmb_keyword_sep ;
	
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
	global $prix, $notice_id, $info_996, $info_995 ;
	
	// Afin de ne pas remettre en cause le script programmé en 995 :
	$info_995 = $info_996 ;
	
	// lu en 010$d de la notice
	$price = $prix[0];
	
	// la zone 995 est répétable
	for ($nb_expl = 0; $nb_expl < sizeof ($info_995); $nb_expl++) {
		/* RAZ expl */
		$expl = array();
		
		/* préparation du tableau à passer à la méthode */
		$expl['notice']     = $notice_id ;
		$expl['cb'] 	    = $info_995[$nb_expl]['f'];
        $expl['cote'] 		= $info_995[$nb_expl]['k'];
		$expl['note']       = $info_995[$nb_expl]['u'];
		$expl['prix']       = $price;
		$expl['cote_mandatory'] = 0 ;
		
		$expl['date_depot'] = substr($info_995[$nb_expl]['m'],0,4)."-".substr($info_995[$nb_expl]['m'],4,2)."-".substr($info_995[$nb_expl]['m'],6,2) ;      
		$expl['date_retour'] = substr($info_995[$nb_expl]['n'],0,4)."-".substr($info_995[$nb_expl]['n'],4,2)."-".substr($info_995[$nb_expl]['n'],6,2) ;

		// propriétaire
		$owner=array();
		$owner['lender_libelle'] = $info_995[$nb_expl]['a'] ;
		if (!$owner['lender_libelle']) $owner['lender_libelle'] = $info_995[$nb_expl]['b'] ;
		$expl['expl_owner'] = lender::import($owner);
		$book_lender_id = $expl['expl_owner'] ;
		
		// docs_location
		$data_doc=array();
		$data_doc['location_libelle']  = $info_995[$nb_expl]['v'] ;
		$data_doc['locdoc_codage_import'] = $info_995[$nb_expl]['w'] ;
		if (!$data_doc['locdoc_codage_import']) $data_doc['locdoc_codage_import'] = $data_doc['location_libelle'] ;
		//$data_doc['locdoc_owner'] = $book_lender_id ;
		$data_doc['locdoc_owner'] = 0 ;
		$expl['location'] = docs_location::import($data_doc);

		// docs_section
		$data_doc=array();
		$data_doc['section_libelle']  = $info_995[$nb_expl]['x'] ;
		$data_doc['sdoc_codage_import'] = $info_995[$nb_expl]['y'] ;
		if (!$data_doc['sdoc_codage_import']) $data_doc['sdoc_codage_import'] = $data_doc['section_libelle'] ;
		//$data_doc['sdoc_owner'] = $book_lender_id ;
		$data_doc['sdoc_owner'] = 0;
		$expl['section'] = docs_section::import($data_doc);
		
		// typedoc
		$data_doc=array();
		$data_doc['tdoc_libelle'] = $info_995[$nb_expl]['e'];
		$data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['r'] ;
		if (!$data_doc['tdoc_codage_import']) $data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['e'] ;
		$data_doc['duree_pret'] = 28 ; /* valeur par défaut */
		$data_doc['tdoc_owner'] = $book_lender_id ;
		$expl['typdoc'] = docs_type::import($data_doc);		
		
		// statut doc
		$data_doc=array();
		$data_doc['statut_libelle'] = $info_995[$nb_expl]['1'];
		$data_doc['statusdoc_codage_import'] = $info_995[$nb_expl]['2'] ;
		if (!$data_doc['statusdoc_codage_import']) $data_doc['statusdoc_codage_import'] = $info_995[$nb_expl]['1'] ;
		$data_doc['pret_flag'] = $info_995[$nb_expl]['3'] ; 
		$data_doc['statusdoc_owner'] = $book_lender_id ;
		$expl['statut'] = docs_statut::import($data_doc);
		
		// codestat
		$expl['codestat'] = 1 ;
		
		// quoi_faire
		// $que_faire vient du formulaire de chargement, à utiliser en attente de l'info dans la zone 996
		global $que_faire ;	
		if ($que_faire=="") {
			if ($info_995[$nb_expl]['0']) $expl['quoi_faire'] = $info_995[$nb_expl]['0']  ;
				else $expl['quoi_faire'] = 2 ;
			} else {
				$expl['quoi_faire'] = $que_faire ;
				}
		// 0 : supprimer, 1 ou vide : Mettre à jour ou ajouter, 2 : ajouter si possible, sinon rien.
		
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