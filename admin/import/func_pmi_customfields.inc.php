<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_pmi_customfields.inc.php,v 1.4 2009-12-14 10:45:47 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


function recup_noticeunimarc_suite($notice) {
	global $info_900;

	$info_900=array();

	$record = new iso2709_record($notice, AUTO_UPDATE); 
	
	$info_900=$record->get_subfield("900","a","l","n");
	
} // fin recup_noticeunimarc_suite 
	
function import_new_notice_suite() {
	global $dbh ;
	global $notice_id ;
	global $info_606_a;
	global $pmb_keyword_sep ;
	global $info_900;
	$mots_cles='';
	for ($a=0; $a<sizeof($info_606_a); $a++) {
		$mots_cles .= $info_606_a[$a][0]." $pmb_keyword_sep " ;
	}
	$mots_cles ? $index_matieres = strip_empty_words($mots_cles) : $index_matieres = '';
	$rqt_maj = "update notices set index_l='".addslashes($mots_cles)."', index_matieres=' ".addslashes($index_matieres)." ' where notice_id='$notice_id' " ;
	mysql_query($rqt_maj, $dbh);
	
	for($i=0;$i<count($info_900);$i++){		
		
		$req = " select idchamp, type, datatype from notices_custom where name='".$info_900[$i]['n']."'";
		$res = mysql_query($req,$dbh);
		if(mysql_num_rows($res)){
			$perso = mysql_fetch_object($res);
			if($perso->idchamp){
				if($perso->type == 'list'){
					$requete="select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='".addslashes($info_900[$i]['a'])."' and notices_custom_champ=$perso->idchamp";
					$resultat=mysql_query($requete);
					if (mysql_num_rows($resultat)) {
						$value=mysql_result($resultat,0,0);
					} else {
						$requete="select max(notices_custom_list_value*1) from notices_custom_lists where notices_custom_champ=$perso->idchamp";
						$resultat=mysql_query($requete);
						$max=@mysql_result($resultat,0,0);
						$n=$max+1;
						$requete="insert into notices_custom_lists (notices_custom_champ,notices_custom_list_value,notices_custom_list_lib) values($perso->idchamp,$n,'".addslashes($info_900[$i]['a'])."')";
						mysql_query($requete);
						$value=$n;
					}
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($perso->idchamp,$notice_id,$value)";
					mysql_query($requete);
				} elseif($perso->type == 'date_box'){
					$tmp_date='';
					$tmp_date=str_replace('/','',$info_900[$i]['a']);
					$tmp_date=substr($tmp_date,4,4).'-'.substr($tmp_date,0,2).'-'.substr($tmp_date,2,2);
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_date) values($perso->idchamp,$notice_id,'".addslashes($tmp_date)."')";
					mysql_query($requete);
				} else {
					$requete="insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_".$perso->datatype.") values($perso->idchamp,$notice_id,'".addslashes($info_900[$i]['a'])."')";
					mysql_query($requete);
				}
			}	
		}
	}	
} 

			
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
	global $nb_expl_ignores,$bulletin_ex ;
	global $prix, $notice_id, $info_995, $typdoc_995, $tdoc_codage, $book_lender_id, 
		$section_995, $sdoc_codage, $book_statut_id, $codstatdoc_995, $statisdoc_codage,
		$cote_mandatory, $book_location_id ;
		
	// lu en 010$d de la notice
	$price = $prix[0];
	
	// la zone 995 est répétable
	for ($nb_expl = 0; $nb_expl < sizeof ($info_995); $nb_expl++) {
		
		/* RAZ expl */
		$expl = array();
		
		/* préparation du tableau à passer à la méthode */
		$expl['cb'] 	    = $info_995[$nb_expl]['f'];
		
		if ($bulletin_ex) {
			$expl['bulletin']=$bulletin_ex;
			$expl['notice']=0;
		} else {
			$expl['notice']     = $notice_id ;
			$expl['bulletin']=0;
		}
		
		// $expl['typdoc']     = $info_995[$nb_expl]['r']; à chercher dans docs_typdoc
		$data_doc=array();
		//$data_doc['tdoc_libelle'] = $info_995[$nb_expl]['r']." -Type doc importé (".$book_lender_id.")";
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
			$info_995[$nb_expl]['q'] = "In";
		$data_doc['section_libelle'] = 'unknown';
		$data_doc['sdoc_codage_import'] = $info_995[$nb_expl]['q'] ;
		if ($sdoc_codage) $data_doc['sdoc_owner'] = $book_lender_id ;
			else $data_doc['sdoc_owner'] = 0 ;
		$expl['section'] = docs_section::import($data_doc);
		
		/* $expl['statut']     à chercher dans docs_statut */
		/* TOUT EST COMMENTE ICI, le statut est maintenant choisi lors de l'import
		if ($info_995[$nb_expl]['o']=="") $info_995[$nb_expl]['o'] = "e";
		$data_doc=array();
		$data_doc['statut_libelle'] = $info_995[$nb_expl]['o']." -Statut importé (".$book_lender_id.")";
		$data_doc['pret_flag'] = 1 ; 
		$data_doc['statusdoc_codage_import'] = $info_995[$nb_expl]['o'] ;
		$data_doc['statusdoc_owner'] = $book_lender_id ;
		$expl['statut'] = docs_statut::import($data_doc);
		FIN TOUT COMMENTE */
		
		$expl['statut'] = $book_statut_id;
		$expl['location'] = $book_location_id;
		
		// $expl['codestat']   = $info_995[$nb_expl]['q']; 'q' utilisé, éventuellement à fixer par combo_box
		$data_doc=array();
		//$data_doc['codestat_libelle'] = $info_995[$nb_expl]['q']." -Pub visé importé (".$book_lender_id.")";
		$data_doc['codestat_libelle'] = 'Unknown';
		$data_doc['statisdoc_codage_import'] = 'Un' ;
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

// fonction spécifique d'export de la zone 995
function export_traite_exemplaires ($ex=array()) {
	
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