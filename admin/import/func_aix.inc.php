<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_aix.inc.php,v 1.6 2009-12-24 16:31:04 dbellamy Exp $


if (stristr ( $_SERVER ['REQUEST_URI'], ".inc.php" ))
	die ( "no access" );

if ($categ == 'import' && $sub == 'import_inv' && $action == 'afterupload') {
	
	print "<div id='contenu-frame'>";
	echo window_title ( $msg [520] . $msg [1003] . $msg [1001] );
	import_inv ();
	print "</div></body></html>";
	die ();
}

if ($categ == 'import' && $sub == 'update_aut' && $action == 'afterupload') {
	
	print "<div id='contenu-frame'>";
	echo window_title ( $msg [520] . $msg [1003] . $msg [1001] );
	update_aut ();
	print "</div></body></html>";
	die ();
}

// DEBUT paramétrage propre à la base de données d'importation :
require_once ($class_path . "/notice.class.php");
require_once ($class_path . "/serials.class.php");
require_once ($class_path . "/categories.class.php");

// templates
$tpl_beforeupload_expl .= "
	<hr />
	<form class='form-$current_module' name='form2' enctype=\"multipart/form-data\" method='post' action=\"iimport_expl.php\" >
		<h3>Import des num&eacute;ros d&apos;inventaire depuis Superdoc</h3>
		<div class='form-contenu'>
			<div class='row'>
				<label class='etiquette' >" . $msg ["import_lec_fichier"] . "</label>
		        <input name='userfile' accept='text/plain' type='file' class='saisie-80em' size='60' />
		        <input name=\"categ\" type=\"hidden\" value=\"import\" />
                <input name=\"sub\" type=\"hidden\" value=\"import_inv\" />
                <input name=\"action\" type=\"hidden\" value=\"afterupload\" />
			</div>	
		    <br />
			<div class='row'>
				<img src='../../images/licence.png' />
		       	<strong>Vous devez avoir import&eacute; les exemplaires depuis Superdoc avant cette &eacute;tape.</strong>
		    </div>
		    <div class='row'></div>
		</div>
		<div class='row'>
			<input type='submit' class='bouton' value='Importer les num&eacute;ros d&apos;inventaire' />
		</div>
	</form>
	<hr />
	<form class='form-$current_module' name='form3' enctype=\"multipart/form-data\" method='post' action=\"iimport_expl.php\" >
		<h3>Correction des auteurs</h3>
		<div class='form-contenu'>
			<div class='row'>
				<label class='etiquette' >" . $msg ["import_lec_fichier"] . "</label>
		        <input name='userfile' accept='text/plain' type='file' class='saisie-80em' size='60' />
		        <input name=\"categ\" type=\"hidden\" value=\"import\" />
                <input name=\"sub\" type=\"hidden\" value=\"update_aut\" />
                <input name=\"action\" type=\"hidden\" value=\"afterupload\" />
			</div>	
		</div>
		<div class='row'>
			<input type='submit' class='bouton' value='Corriger les auteurs' />
		</div>
	</form>
	";

function recup_noticeunimarc_suite($notice) {
	
	global $info_461, $info_463, $info_464;
	global $info_606_a;
	global $info_900, $info_901, $info_902, $info_903, $info_904, $info_905, $info_906;
	
	$info_461 = "";
	$info_463 = "";
	$info_464 = ""; //Compatibilite import memonotices
	$info_900 = "";
	$info_901 = "";
	$info_902 = "";
	$info_903 = "";
	$info_904 = "";
	$info_905 = "";
	$info_906 = "";
	
	$record = new iso2709_record ( $notice, AUTO_UPDATE );
	
	for($i = 0; $i < count ( $record->inner_directory ); $i ++) {
		$cle = $record->inner_directory [$i] ['label'];
		switch ($cle) {
			case "461" :
				//Lien vers perio
				$info_461 = $record->get_subfield ( $cle, "t", "v", "e", "9" );
				break;
			case "463" :
				//Lien vers bulletin
				$info_463 = $record->get_subfield ( $cle, "t", "v", "e", "9" );
				break;
			case "464" :
				//Compatibilite import memonotices
				$info_464 = $record->get_subfield ( $cle, "t", "v", "d", "p", "z", "e" );
				break;
			default :
				break;
		
		} /* end of switch */
	
	} /* end of for */
	
	$info_606_a = $record->get_subfield_array_array ( "606", "a" );
	$info_900 = $record->get_subfield_array_array ( "900", "a" );
	$info_901 = $record->get_subfield_array_array ( "901", "a" );
	$info_902 = $record->get_subfield_array_array ( "902", "a" );
	$info_903 = $record->get_subfield ( "903", "a" );
	$info_904 = $record->get_subfield ( "904", "a" );
	$info_905 = $record->get_subfield_array_array ( "905", "a" );
	$info_906 = $record->get_subfield_array_array ( "906", "a" );

} // fin recup_noticeunimarc_suite = fin récupération des variables propres à la bretagne


function import_new_notice_suite() {
	global $dbh;
	global $notice_id, $bulletin_ex;
	
	global $info_461, $info_463, $info_464;
	global $info_606_a;
	global $info_900, $info_901, $info_902, $info_903, $info_904, $info_905, $info_906;
	global $pmb_keyword_sep;
	$bulletin_ex = 0;
	$is_object = false;
	
	//compatibilite avec import memonotices
	if (is_array ( $info_464 )) {
		unset ( $info_461 );
		unset ( $info_463 );
		
		$info_461 [0] ['t'] = $info_464 [0] ['t'];
		$info_461 [0] ['9'] = 'lnk:perio';
		
		$info_463 [0] ['v'] = $info_464 [0] ['v'];
		$info_463 [0] ['e'] = $info_464 [0] ['d'];
		$info_463 [0] ['9'] = 'lnk:bull';
		if (strpos ( $info_904 [0], "/" ) !== FALSE) {
			$dc = substr ( $info_904 [0], 6, 4 ) . '-' . substr ( $info_904 [0], 3, 2 ) . '-' . substr ( $info_904 [0], 0, 2 );
			$info_904 [0] = $dc;
		}
	}
	
	//Si article
	if (is_array ( $info_461 ) && is_array ( $info_463 )) {
		
		//recuperation infos notice
		$requete = "select * from notices where notice_id=$notice_id";
		$resultat = mysql_query ( $requete );
		$r = mysql_fetch_object ( $resultat );
		
		//Notice chapeau existe-t-elle ?
		$requete = "select notice_id from notices where tit1='" . addslashes ( $info_461 [0] ['t'] ) . "' and niveau_hierar='1' and niveau_biblio='s'";
		$resultat = mysql_query ( $requete );
		if (@mysql_num_rows ( $resultat )) {
			
			//Si oui, récupération id
			$chapeau_id = mysql_result ( $resultat, 0, 0 );
			//Bulletin existe-t-il ?
			$requete = "select bulletin_id from bulletins where bulletin_numero='" . addslashes ( $info_463 [0] ['v'] ) . "' and  mention_date='" . addslashes ( $info_463 [0] ['e'] ) . "' and bulletin_notice=$chapeau_id ";
			$resultat = mysql_query ( $requete );
			if (@mysql_num_rows ( $resultat )) {
				//Si oui, récupération id bulletin
				$bulletin_id = mysql_result ( $resultat, 0, 0 );
			} else {
				//Si non, création bulletin
				$info = array ();
				$bulletin = new bulletinage ( "", $chapeau_id );
				$info ['bul_titre'] = "Bulletin " . $info_463 [0] ['v'];
				if ($info_463 [0] ['e']) {
					$info ['bul_titre'] .= " - " . $info_463 [0] ['e'];
				}
				$info ['bul_titre'] = addslashes ( $info ['bul_titre'] );
				$info ['bul_no'] = addslashes ( $info_463 [0] ['v'] );
				$info ['bul_date'] = addslashes ( $info_463 [0] ['e'] );
				$date_date = explode ( "/", $info_463 [0] ['e'] );
				if (count ( $date_date )) {
					if (count ( $date_date ) == 1)
						$info ['date_date'] = $date_date [0] . "-01-01";
					if (count ( $date_date ) == 2)
						$info ['date_date'] = $date_date [1] . "-" . $date_date [0] . "-01";
					if (count ( $date_date ) == 3)
						$info ['date_date'] = $date_date [2] . "-" . $date_date [1] . "-" . $date_date [0];
				} else {
					if ($info_904 [0]) {
						$info ['date_date'] = $info_904 [0];
					}
				}
				$bulletin_id = $bulletin->update ( $info );
			}
		
		} else {
			
			//Si non, création notice chapeau et bulletin
			$chapeau = new serial ( );
			$info = array ();
			$info ['tit1'] = addslashes ( $info_461 [0] ['t'] );
			$info ['niveau_biblio'] = 's';
			$info ['niveau_hierar'] = '1';
			$info ['typdoc'] = $r->typdoc;
			$chapeau->update ( $info );
			$chapeau_id = $chapeau->serial_id;
			
			$bulletin = new bulletinage ( "", $chapeau_id );
			$info = array ();
			$info ['bul_titre'] = "Bulletin " . $info_463 [0] ['v'];
			if ($info_463 [0] ['e']) {
				$info ['bul_titre'] .= " - " . $info_463 [0] ['e'];
			}
			$info ['bul_titre'] = addslashes ( $info ['bul_titre'] );
			$info ['bul_no'] = addslashes ( $info_463 [0] ['v'] );
			$info ['bul_date'] = addslashes ( $info_463 [0] ['e'] );
			$date_date = explode ( "/", $info_463 [0] ['e'] );
			if (count ( $date_date )) {
				if (count ( $date_date ) == 1)
					$info ['date_date'] = $date_date [0] . "-01-01";
				if (count ( $date_date ) == 2)
					$info ['date_date'] = $date_date [1] . "-" . $date_date [0] . "-01";
				if (count ( $date_date ) == 3)
					$info ['date_date'] = $date_date [2] . "-" . $date_date [1] . "-" . $date_date [0];
			} else {
				if ($info_904 [0]) {
					$info ['date_date'] = $info_904 [0];
				}
			}
			$bulletin_id = $bulletin->update ( $info );
		}
		
		$bulletin_ex = $bulletin_id;
		
		if ($r->tit1 == '_OBJECT_BULLETIN_' || (is_array ( $info_464 ) && $info_464 [0] ['z'] == 'objet')) { //$info_464[0]['z']=='objet' >> Compatibilite import memonotices
			$is_object = true;
			//notice de bulletin a supprimer
			notice::del_notice ( $notice_id );
		} else {
			//Passage de la notice en article
			$np = '';
			if (is_array ( $info_464 ) && $info_464 [0] ['p'] != '') {
				$np = ", npages='" . addslashes ( $info_464 [0] ['p'] ) . "' ";
			}
			$requete = "update notices set niveau_biblio='a', niveau_hierar='2'" . $np . " where notice_id=$notice_id";
			mysql_query ( $requete );
			$requete = "insert into analysis (analysis_bulletin,analysis_notice) values($bulletin_id,$notice_id)";
			mysql_query ( $requete );
		}
	}
	
	if (! $is_object) {
		
		//Traitement du thésaurus
		$unknown_desc = array ();
		$ordre_categ = 0;
		for($i = 0; $i < count ( $info_606_a ); $i ++) {
			for($j = 0; $j < count ( $info_606_a [$i] ); $j ++) {
				$descripteur = $info_606_a [$i] [$j];
				//Recherche du terme
				//dans le thesaurus par defaut et dans la langue de l'interface
				$libelle = addslashes ( $descripteur );
				$categ_id = categories::searchLibelle ( $libelle );
				
				if ($categ_id) {
					$requete = "INSERT INTO notices_categories (notcateg_notice,num_noeud,ordre_categorie) values($notice_id,$categ_id,$ordre_categ)";
					mysql_query ( $requete, $dbh );
					$ordre_categ ++;
				} else {
					$unknown_desc [] = $descripteur;
				}
			}
		}
		if ($unknown_desc) {
			$mots_cles = implode ( $pmb_keyword_sep, $unknown_desc );
			$il = '';
			$qil = "select index_l from notices where notice_id=$notice_id ";
			$ril = mysql_query ( $qil, $dbh );
			$il = trim ( mysql_result ( $ril, 0, 0 ) );
			if ($il)
				$mots_cles = $il . $pmb_keyword_sep . $mots_cles;
			
			$requete = "update notices set index_l='" . addslashes ( $mots_cles ) . "', index_matieres=' " . addslashes ( strip_empty_words ( $mots_cles ) ) . " ' where notice_id=$notice_id";
			mysql_query ( $requete, $dbh );
		}
		
		$notes = '';
		
		//Thème
		$qn = "select idchamp from notices_custom where name='theme' ";
		$rn = mysql_query ( $qn, $dbh );
		if (mysql_num_rows ( $rn )) {
			$idc_theme = mysql_result ( $rn, 0, 0 );
		}
		if (count ( $info_900 ) && $idc_theme) {
			
			for($i = 0; $i < count ( $info_900 ); $i ++) {
				for($j = 0; $j < count ( $info_900 [$i] ); $j ++) {
					$requete = "select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='" . addslashes ( $info_900 [$i] [$j] ) . "' and notices_custom_champ=$idc_theme ";
					$resultat = mysql_query ( $requete, $dbh );
					if (mysql_num_rows ( $resultat )) {
						$value = mysql_result ( $resultat, 0, 0 );
						$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_theme,$notice_id,$value)";
						mysql_query ( $requete, $dbh );
					} else {
						//sinon dans notes
						$notes .= 'thème : ' . $info_900 [$i] [$j];
					}
				}
			}
		}
		
		//Genres
		$qn = "select idchamp from notices_custom where name='genre' ";
		$rn = mysql_query ( $qn, $dbh );
		if (mysql_num_rows ( $rn )) {
			$idc_genre = mysql_result ( $rn, 0, 0 );
		}
		if (count ( $info_901 ) && $idc_genre) {
			
			for($i = 0; $i < count ( $info_901 ); $i ++) {
				for($j = 0; $j < count ( $info_901 [$i] ); $j ++) {
					$requete = "select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='" . addslashes ( $info_901 [$i] [$j] ) . "' and notices_custom_champ=$idc_genre ";
					$resultat = mysql_query ( $requete, $dbh );
					if (mysql_num_rows ( $resultat )) {
						$value = mysql_result ( $resultat, 0, 0 );
						$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_genre,$notice_id,$value)";
						mysql_query ( $requete, $dbh );
					} else {
						//sinon dans notes
						if ($notes)
							$notes .= "\n";
						$notes .= 'genre : ' . $info_901 [$i] [$j];
					}
				}
			}
		}
		
		//Discipline
		$qn = "select idchamp from notices_custom where name='discipline' ";
		$rn = mysql_query ( $qn, $dbh );
		if (mysql_num_rows ( $rn )) {
			$idc_discipline = mysql_result ( $rn, 0, 0 );
		}
		if (count ( $info_902 ) && $idc_discipline) {
			
			for($i = 0; $i < count ( $info_902 ); $i ++) {
				for($j = 0; $j < count ( $info_902 [$i] ); $j ++) {
					$requete = "select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='" . addslashes ( $info_902 [$i] [$j] ) . "' and notices_custom_champ=$idc_discipline ";
					$resultat = mysql_query ( $requete, $dbh );
					if (mysql_num_rows ( $resultat )) {
						$value = mysql_result ( $resultat, 0, 0 );
						$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_discipline,$notice_id,$value)";
						mysql_query ( $requete, $dbh );
					} else {
						//sinon dans notes
						if ($notes)
							$notes .= "\n";
						$notes .= 'discipline : ' . $info_902 [$i] [$j];
					}
				}
			}
		}
		
		//Type de nature
		$qn = "select idchamp from notices_custom where name='type_nature' ";
		$rn = mysql_query ( $qn, $dbh );
		if (mysql_num_rows ( $rn )) {
			$idc_type_nature = mysql_result ( $rn, 0, 0 );
		}
		$qn = "select idchamp from notices_custom where name='pays' ";
		$rn = mysql_query ( $qn, $dbh );
		if (mysql_num_rows ( $rn )) {
			$idc_pays = mysql_result ( $rn, 0, 0 );
		}
		$qn = "select idchamp from notices_custom where name='periode' ";
		$rn = mysql_query ( $qn, $dbh );
		if (mysql_num_rows ( $rn )) {
			$idc_periode = mysql_result ( $rn, 0, 0 );
		}
		if (count ( $info_905 )) {
			
			for($i = 0; $i < count ( $info_905 ); $i ++) {
				for($j = 0; $j < count ( $info_905 [$i] ); $j ++) {
					
					//essai dans type de nature
					$done = FALSE;
					if ($idc_type_nature) {
						$requete = "select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='" . addslashes ( $info_905 [$i] [$j] ) . "' and notices_custom_champ=$idc_type_nature ";
						$resultat = mysql_query ( $requete, $dbh );
						if (mysql_num_rows ( $resultat )) {
							$value = mysql_result ( $resultat, 0, 0 );
							$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_type_nature,$notice_id,$value)";
							mysql_query ( $requete, $dbh );
							$done = TRUE;
						}
					}
					
					//essai dans genre
					if (! $done && $idc_genre) {
						$requete = "select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='" . addslashes ( $info_905 [$i] [$j] ) . "' and notices_custom_champ=$idc_genre ";
						$resultat = mysql_query ( $requete, $dbh );
						if (mysql_num_rows ( $resultat )) {
							$value = mysql_result ( $resultat, 0, 0 );
							$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_genre,$notice_id,$value)";
							mysql_query ( $requete, $dbh );
							$done = TRUE;
						}
					}
					
					//essai dans theme
					if (! $done && $idc_theme) {
						$requete = "select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='" . addslashes ( $info_905 [$i] [$j] ) . "' and notices_custom_champ=$idc_theme ";
						$resultat = mysql_query ( $requete, $dbh );
						if (mysql_num_rows ( $resultat )) {
							$value = mysql_result ( $resultat, 0, 0 );
							$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_theme,$notice_id,$value)";
							mysql_query ( $requete, $dbh );
							$done = TRUE;
						}
					}
					
					//essai dans discipline
					if (! $done && $idc_discipline) {
						$requete = "select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='" . addslashes ( $info_905 [$i] [$j] ) . "' and notices_custom_champ=$idc_discipline ";
						$resultat = mysql_query ( $requete, $dbh );
						if (mysql_num_rows ( $resultat )) {
							$value = mysql_result ( $resultat, 0, 0 );
							$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_discipline,$notice_id,$value)";
							mysql_query ( $requete, $dbh );
							$done = TRUE;
						}
					}
					
					//essai dans pays
					if (! $done) {
						$done_pa = FALSE;
						if (! $done && $idc_pays) {
							$i_pays = strip_empty_chars ( $info_905 [$i] [$j] );
							$requete = "select notices_custom_list_value,notices_custom_list_lib from notices_custom_lists where notices_custom_champ=$idc_pays ";
							$resultat = mysql_query ( $requete, $dbh );
							if (mysql_num_rows ( $resultat )) {
								while ( ($row = mysql_fetch_object ( $resultat )) ) {
									$r_pays = strip_empty_chars ( $row->notices_custom_list_lib );
									if (strpos ( $i_pays, $r_pays ) !== FALSE) {
										$value = $row->notices_custom_list_value;
										$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_pays,$notice_id,$value)";
										mysql_query ( $requete, $dbh );
										$done_pa = TRUE;
										break;
									}
								}
							}
						}
						
						//essai dans periode 
						$done_pe = FALSE;
						if (! $done && $idc_periode) {
							$i_periode = strip_empty_chars ( $info_905 [$i] [$j] );
							$requete = "select notices_custom_list_value,notices_custom_list_lib from notices_custom_lists where notices_custom_champ=$idc_periode ";
							$resultat = mysql_query ( $requete, $dbh );
							if (mysql_num_rows ( $resultat )) {
								while ( ($row = mysql_fetch_object ( $resultat )) ) {
									$r_periode = strip_empty_chars ( $row->notices_custom_list_lib );
									if (strpos ( $i_periode, $r_periode ) !== FALSE) {
										$value = $row->notices_custom_list_value;
										$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_periode,$notice_id,$value)";
										mysql_query ( $requete, $dbh );
										$done_pe = TRUE;
										break;
									}
								}
							}
						}
						if ($done_pa && $done_pe)
							$done = TRUE;
					}
					
					//sinon dans notes
					if (! $done) {
						if ($notes)
							$notes .= "\n";
						$notes .= 'type de nature : ' . $info_905 [$i] [$j];
					}
				
				}
			}
		}
		
		//Niveau
		if (count ( $info_906 )) {
			$qn = "select idchamp from notices_custom where name='niveau' ";
			$rn = mysql_query ( $qn, $dbh );
			if (mysql_num_rows ( $rn )) {
				$idc_niveau = mysql_result ( $rn, 0, 0 );
				
				for($i = 0; $i < count ( $info_906 ); $i ++) {
					for($j = 0; $j < count ( $info_906 [$i] ); $j ++) {
						$requete = "select notices_custom_list_value from notices_custom_lists where notices_custom_list_lib='" . addslashes ( $info_906 [$i] [$j] ) . "' and notices_custom_champ=$idc_niveau ";
						$resultat = mysql_query ( $requete, $dbh );
						if (mysql_num_rows ( $resultat )) {
							$value = mysql_result ( $resultat, 0, 0 );
							$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) values($idc_niveau,$notice_id,$value)";
							mysql_query ( $requete, $dbh );
						} else {
							//sinon dans notes
							if ($notes)
								$notes .= "\n";
							$notes .= 'niveau : ' . $info_906 [$i] [$j];
						}
					}
				}
			}
		}
		
		//notes
		if ($notes) {
			$notes .= "\n";
			$notes = addslashes ( $notes );
			$q = "update notices set n_contenu=concat('" . $notes . "',n_contenu) where notice_id='" . $notice_id . "' ";
			mysql_query ( $q, $dbh );
		}
		
		//Année de péremption
		if ($info_903 [0]) {
			$qn = "select idchamp from notices_custom where name='annee_peremption' ";
			$rn = mysql_query ( $qn, $dbh );
			if (mysql_num_rows ( $rn )) {
				$idc_ap = mysql_result ( $rn, 0, 0 );
				$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_small_text) values($idc_ap,$notice_id,'" . addslashes ( $info_903 [0] ) . "')";
				mysql_query ( $requete, $dbh );
			}
		}
		
		//Date de saisie
		if ($info_904 [0]) {
			$qn = "select idchamp from notices_custom where name='date_creation' ";
			$rn = mysql_query ( $qn, $dbh );
			if (mysql_num_rows ( $rn )) {
				$idc_ds = mysql_result ( $rn, 0, 0 );
				$requete = "insert into notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_date) values($idc_ds,$notice_id,'" . $info_904 [0] . "')";
				mysql_query ( $requete, $dbh );
			}
		}
	}

} // fin import_new_notice_suite


// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires() {
	global $msg, $dbh;
	
	global $prix, $notice_id, $info_995, $typdoc_995, $tdoc_codage, $book_lender_id, $section_995, $sdoc_codage, $book_statut_id, $locdoc_codage, $codstatdoc_995, $statisdoc_codage, $cote_mandatory;
	
	global $info_461, $bulletin_ex;
	
	// lu en 010$d de la notice
	$price = $prix [0];
	
	// la zone 995 est répétable
	for($nb_expl = 0; $nb_expl < sizeof ( $info_995 ); $nb_expl ++) {
		
		/* RAZ expl */
		$expl = array ();
		
		/* préparation du tableau à passer à la méthode */
		$expl ['cb'] = $info_995 [$nb_expl] ['f'];
		if (($bulletin_ex) && (is_array ( $info_461 ))) {
			$expl ['bulletin'] = $bulletin_ex;
			$expl ['notice'] = 0;
		} else {
			$expl ['notice'] = $notice_id;
			$expl ['bulletin'] = 0;
		}
		
		$data_doc = array ();
		$data_doc ['duree_pret'] = 0; /* valeur par défaut */
		$data_doc ['tdoc_codage_import'] = $info_995 [$nb_expl] ['r'];
		$data_doc ['tdoc_libelle'] = $info_995 [$nb_expl] ['r'];
		$data_doc ['tdoc_owner'] = 0;
		$expl ['typdoc'] = docs_type::import ( $data_doc );
		
		$expl ['cote'] = $info_995 [$nb_expl] ['k'];
		if (! trim ( $expl ['cote'] )) {
			$expl ['cote'] = "INDETERMINE";
		}
		
		$data_doc = array ();
		if (! $info_995 [$nb_expl] ['q']) {
			$info_995 [$nb_expl] ['q'] = "INDETERMINE";
		}
		$data_doc ['section_libelle'] = $info_995 [$nb_expl] ['q'];
		$data_doc ['sdoc_codage_import'] = $info_995 [$nb_expl] ['q'];
		$data_doc ['sdoc_owner'] = 0;
		$expl ['section'] = docs_section::import ( $data_doc );
		
		$expl ['statut'] = $book_statut_id;
		
		$data_doc = array ();
		$data_doc ['location_libelle'] = "CDI";
		$data_doc ['locdoc_codage_import'] = "CDI";
		$data_doc ['locdoc_owner'] = 0;
		$expl ['location'] = docs_location::import ( $data_doc );
		
		$data_doc = array ();
		if (! $info_995 [$nb_expl] ['q']) {
			$info_995 [$nb_expl] ['q'] = "IN";
		}
		$data_doc ['codestat_libelle'] = $info_995 [$nb_expl] ['q'];
		$data_doc ['statisdoc_codage_import'] = $info_995 [$nb_expl] ['q'];
		$data_doc ['statisdoc_owner'] = 0;
		$expl ['codestat'] = docs_codestat::import ( $data_doc );
		
		$expl ['note'] = $info_995 [$nb_expl] ['u'];
		$expl ['prix'] = $price;
		$expl ['expl_owner'] = $book_lender_id;
		$expl ['cote_mandatory'] = $cote_mandatory;
		
		$expl_id = exemplaire::import ( $expl );
		if ($expl_id == 0) {
			$nb_expl_ignores ++;
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
function export_traite_exemplaires($ex = array()) {
	
	$subfields ["a"] = $ex->lender_libelle;
	$subfields ["c"] = $ex->lender_libelle;
	$subfields ["f"] = $ex->expl_cb;
	$subfields ["k"] = $ex->expl_cote;
	$subfields ["u"] = $ex->expl_note;
	
	if ($ex->statusdoc_codage_import)
		$subfields ["o"] = $ex->statusdoc_codage_import;
	if ($ex->tdoc_codage_import)
		$subfields ["r"] = $ex->tdoc_codage_import;
	else
		$subfields ["r"] = "uu";
	if ($ex->sdoc_codage_import)
		$subfields ["q"] = $ex->sdoc_codage_import;
	else
		$subfields ["q"] = "u";
	
	global $export996;
	$export996 ['f'] = $ex->expl_cb;
	$export996 ['k'] = $ex->expl_cote;
	$export996 ['u'] = $ex->expl_note;
	
	$export996 ['m'] = substr ( $ex->expl_date_depot, 0, 4 ) . substr ( $ex->expl_date_depot, 5, 2 ) . substr ( $ex->expl_date_depot, 8, 2 );
	$export996 ['n'] = substr ( $ex->expl_date_retour, 0, 4 ) . substr ( $ex->expl_date_retour, 5, 2 ) . substr ( $ex->expl_date_retour, 8, 2 );
	
	$export996 ['a'] = $ex->lender_libelle;
	$export996 ['b'] = $ex->expl_owner;
	
	$export996 ['v'] = $ex->location_libelle;
	$export996 ['w'] = $ex->ldoc_codage_import;
	
	$export996 ['x'] = $ex->section_libelle;
	$export996 ['y'] = $ex->sdoc_codage_import;
	
	$export996 ['e'] = $ex->tdoc_libelle;
	$export996 ['r'] = $ex->tdoc_codage_import;
	
	$export996 ['1'] = $ex->statut_libelle;
	$export996 ['2'] = $ex->statusdoc_codage_import;
	$export996 ['3'] = $ex->pret_flag;
	
	global $export_traitement_exemplaires;
	$export996 ['0'] = $export_traitement_exemplaires;
	
	return $subfields;

}

function import_inv() {
	global $dbh;
	global $text, $n, $t_xml;
	
	//La structure du fichier xml doit être la suivante : 
	/*    
	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
	<inm:Results productTitle="Superdoc Premium" productVersion="9.00" xmlns:inm="http://www.inmagic.com/webpublisher/query">
	<inm:Recordset setCount="241">
	<inm:Record setEntry="0">
	<inm:Date-de-creation>15/01/2008</inm:Date-de-creation>
	<inm:ID>661</inm:ID>
	<inm:Code-Barre>017887</inm:Code-Barre>
	<inm:Numero-Inventaire>017887</inm:Numero-Inventaire>
	<inm:Cote>915.4 LAN</inm:Cote>
	<inm:Centre>CDI LYC. MENDES FRANCE</inm:Centre>
	<inm:Localisation>DOC PROF</inm:Localisation>
	<inm:Exclusion-du-pret />
	<inm:Groupe>MULTIMEDIA</inm:Groupe>
	<inm:Etat-du-document />
	<inm:Origine />
	<inm:Notes />
	<inm:Inventaire />
	</inm:Record>...
	*/
	
	//Upload du fichier
	if (! ($_FILES ['userfile'] ['tmp_name'])) {
		print "Cliquez sur Pr&eacute;c&eacute;dent et choisissez un fichier";
		exit ();
	} elseif (! (move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], "../../temp/" . basename ( $_FILES ['userfile'] ['tmp_name'] ) ))) {
		print "Le fichier n'a pas pu être t&eacute;l&eacute;charg&eacute;. Voici plus d'informations :<br />";
		print_r ( $_FILES ) . "<p>";
		exit ();
	}
	$fichier = @fopen ( "../../temp/" . basename ( $_FILES ['userfile'] ['tmp_name'] ), "r" );
	
	if ($fichier) {
		
		print "<br /><br />";
		print "T&eacute;l&eacute;chargement du fichier effectu&eacute;.<br /><hr />";
		
		print "Traitement du fichier en cours.<br />";
		
		$nb_ok = 0;
		$tab_err = array ();
		
		//definition header et footer
		$header = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><inm:results>";
		$footer = "</inm:results>";
		
		while ( ! feof ( $fichier ) ) {
			
			$buffer = "";
			$deb = FALSE;
			$i = 0;
			
			while ( $i < 200 && ! feof ( $fichier ) ) {
				$line = fgets ( $fichier, 4096 );
				if ((strpos ( $line, "<inm:Recordset" ) === FALSE) && (strpos ( $line, "<inm:Record" ) !== FALSE)) {
					$deb = TRUE;
				}
				if ($deb) {
					$buffer .= trim ( $line );
				}
				if (strpos ( $line, "</inm:Record>" ) !== FALSE) {
					$deb = FALSE;
					$i ++;
				}
			}
			
			if ($buffer) {
				$buffer = $header . $buffer . $footer;
				
				//parse buffer
				$text = '';
				$t_xml = array ();
				$n = 0;
				
				$encoding = "UTF-8";
				$parser = xml_parser_create ( $encoding );
				xml_parser_set_option ( $parser, XML_OPTION_TARGET_ENCODING, $encoding );
				xml_parser_set_option ( $parser, XML_OPTION_CASE_FOLDING, true );
				xml_set_element_handler ( $parser, "debutBalise", "finBalise" );
				xml_set_character_data_handler ( $parser, "texte" );
				
				if (! xml_parse ( $parser, $buffer, TRUE )) {
					die ( sprintf ( "erreur XML %s à la ligne: %d", xml_error_string ( xml_get_error_code ( $parser ) ), xml_get_current_line_number ( $parser ) ) );
				}
				xml_parser_free ( $parser );
				
				//traitement des enregistrements
				for($i = 1; $i <= count ( $t_xml ); $i ++) {
					
					//il faut un cb exemplaire et un n° d'inventaire
					$t_xml [$i] ['INM:CODE-BARRE'] [0] = trim ( $t_xml [$i] ['INM:CODE-BARRE'] [0] );
					$t_xml [$i] ['INM:NUMERO-INVENTAIRE'] [0] = trim ( $t_xml [$i] ['INM:NUMERO-INVENTAIRE'] [0] );
					
					if (($t_xml [$i] ['INM:CODE-BARRE'] [0] != '') && ($t_xml [$i] ['INM:NUMERO-INVENTAIRE'] [0] != '')) {
						
						//id exemplaire
						$expl_id = 0;
						$q = "select expl_id from exemplaires where expl_cb='" . $t_xml [$i] ['INM:CODE-BARRE'] [0] . "' ";
						$r = mysql_query ( $q, $dbh );
						if (mysql_num_rows ( $r )) {
							$expl_id = mysql_result ( $r, 0, 0 );
						} else {
							$tab_err [] = $t_xml [$i] ['INM:ID'] [0];
							continue;
						}
						
						//insert n° inventaire
						$qn = "select idchamp from expl_custom left join expl_custom_values on expl_custom_origine=idchamp where name='no_inventaire' and expl_custom_small_text is null ";
						$rn = mysql_query ( $qn, $dbh );
						if (mysql_num_rows ( $rn )) {
							$idc = mysql_result ( $rn, 0, 0 );
							$requete = "insert into expl_custom_values (expl_custom_champ,expl_custom_origine,expl_custom_small_text) values($idc,$expl_id,'" . addslashes ( $t_xml [$i] ['INM:NUMERO-INVENTAIRE'] [0] ) . "')";
							mysql_query ( $requete, $dbh );
							$nb_ok ++;
						}
					
					}
				}
			}
		}
		
		fclose ( $fichier );
		unlink ( "../../temp/" . basename ( $_FILES ['userfile'] ['tmp_name'] ) );
		print "Traitement du fichier termin&eacute;.";
		print "<br /><hr />";
		
		print "Nombre de n° d&apos;inventaire import&eacute;s : " . $nb_ok . "<br />";
		print "Nombre d'erreurs de traitement : " . count ( $tab_err ) . "<br /><hr />";
		
		if (count ( $tab_err )) {
			for($i = 0; $i < count ( $tab_err ); $i ++) {
				print "Erreur &agrave; l&apos;enregistrement n° " . $tab_err [$i] . "<br />";
			}
			print "<hr /><br />";
		}
	
	} else {
		print "Le fichier n&apos;a pu &ecirc;tre lu .";
	}
}

require_once ($class_path . "/author.class.php");

function update_aut() {
	global $dbh;
	global $text, $n, $t_xml;
	
	//Reprise des auteurs sans élément rejeté et des titres de notices lorsque tronqués. 
	
	//Upload du fichier
	if (! ($_FILES ['userfile'] ['tmp_name'])) {
		print "Cliquez sur Pr&eacute;c&eacute;dent et choisissez un fichier";
		exit ();
	} elseif (! (move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], "../../temp/" . basename ( $_FILES ['userfile'] ['tmp_name'] ) ))) {
		print "Le fichier n'a pas pu être t&eacute;l&eacute;charg&eacute;. Voici plus d'informations :<br />";
		print_r ( $_FILES ) . "<p>";
		exit ();
	}
	$fichier = @fopen ( "../../temp/" . basename ( $_FILES ['userfile'] ['tmp_name'] ), "r" );
	
	if ($fichier) {
		
		print "<br /><br />";
		print "T&eacute;l&eacute;chargement du fichier effectu&eacute;.<br /><hr />";
		
		print "Traitement du fichier en cours.<br />";
		
		$nb_ok = 0;
		$tab_err = array ();
		
		//definition header et footer
		$header = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><inm:results>";
		$footer = "</inm:results>";
		
		$compte = 0;
		
		while ( ! feof ( $fichier ) ) {
			
			$buffer = "";
			$deb = FALSE;
			$i = 0;
			
			while ( $i < 200 && ! feof ( $fichier ) ) {
				$line = fgets ( $fichier, 4096 );
				if ((strpos ( $line, "<inm:Recordset" ) === FALSE) && (strpos ( $line, "<inm:Record" ) !== FALSE)) {
					$deb = TRUE;
				}
				if ($deb) {
					$buffer .= trim ( $line );
				}
				if (strpos ( $line, "</inm:Record>" ) !== FALSE) {
					$deb = FALSE;
					$i ++;
				}
			}
			
			if ($buffer) {
				$buffer = $header . $buffer . $footer;
				
				//parse buffer
				$text = '';
				$t_xml = array ();
				$n = 0;
				
				$encoding = "UTF-8";
				$parser = xml_parser_create ( $encoding );
				xml_parser_set_option ( $parser, XML_OPTION_TARGET_ENCODING, $encoding );
				xml_parser_set_option ( $parser, XML_OPTION_CASE_FOLDING, true );
				xml_set_element_handler ( $parser, "debutBalise", "finBalise" );
				xml_set_character_data_handler ( $parser, "texte" );
				
				if (! xml_parse ( $parser, $buffer, TRUE )) {
					die ( sprintf ( "erreur XML %s à la ligne: %d", xml_error_string ( xml_get_error_code ( $parser ) ), xml_get_current_line_number ( $parser ) ) );
				}
				xml_parser_free ( $parser );
				
				$tmp_compte = $compte;
				$tmp_val = array ();
				//traitement des enregistrements
				for($i = 1; $i <= count ( $t_xml ); $i ++) {
					
					//Il faut un code-barres d'exemplaire et un auteur sans element rejete
					
					$t_xml [$i] ['INM:CODE-BARRE'] [0] = trim ( $t_xml [$i] ['INM:CODE-BARRE'] [0] );
					$q = "select notice_id,tit1 from notices join exemplaires on expl_notice=notice_id where expl_cb='" . $t_xml [$i] ['INM:CODE-BARRE'] [0] . "' ";
					$r = mysql_query ( $q, $dbh );
					
					if (mysql_num_rows ( $r )) {
						
						$n = mysql_result ( $r, 0, 0 );
						$t = mysql_result ( $r, 0, 1 );
						/*
						if ($t != $t_xml[$i]['INM:TITRE'][0]) {
							print "ancien titre = ".$t.'<br/>';
							print "nouveau titre=".$t_xml[$i]['INM:TITRE'][0].'<br/>';
						}
						*/
						foreach ( $t_xml [$i] ['INM:AUTEUR'] as $k => $v ) {
							$t_xml [$i] ['INM:AUTEUR'] [$k] = trim ( $v );
							if (strpos ( $v, ',' ) === FALSE) {
								$compte ++;
								$tmp_val [$compte] ['name'] = clean_string ( utf8_decode ( $v ) );
								$tmp_val [$compte] ['type'] = '70';
								$aut = auteur::import ( $tmp_val [$compte] );
								$q1 = "select count(*) from responsability join authors on author_id=responsability_author where responsability_notice='" . $n . "' and responsability_type='0' ";
								$r1 = mysql_query ( $q1, $dbh );
								$n1 = mysql_result ( $r1, 0, 0 );
								if ($n1) {
									$q2 = "select max(ordre)*1+1 from responsability join authors on author_id=responsability_author where responsability_notice_id='" . $n . "' and responsability_type='1' ";
									$r2 = mysql_query ( $q2, $dbh );
									$n2 = mysql_result ( $r2, 0, 0 );
									$q3 = "insert ignore into responsability (responsability_author,responsability_notice,responsability_fonction,responsability_type,responsability_ordre) ";
									$q3 .= "values ('" . $aut . "','" . $n . "','','1','" . $n2 . "') ";
									mysql_query ( $q3, $dbh );
								} else {
									$q3 = "insert ignore into responsability (responsability_author,responsability_notice,responsability_fonction,responsability_type,responsability_ordre) ";
									$q3 .= "values ('" . $aut . "','" . $n . "','','0','0') ";
									mysql_query ( $q3, $dbh );
								}
							}
						}
						foreach ( $t_xml [$i] ['INM:AUTEUR-COLLECTIF'] as $k => $v ) {
							$t_xml [$i] ['INM:AUTEUR-COLLECTIF'] = trim ( $v );
							if (strpos ( $v, ',' ) === FALSE) {
								$compte ++;
								$tmp_val [$compte] ['name'] = clean_string ( utf8_decode ( $v ) );
								$tmp_val [$compte] ['type'] = '71';
								$aut = auteur::import ( $tmp_val [$compte] );
								$q1 = "select count(*) from responsability join authors on author_id=responsability_author where responsability_notice='" . $n . "' and responsability_type='0' ";
								$r1 = mysql_query ( $q1, $dbh );
								$n1 = mysql_result ( $r1, 0, 0 );
								if ($n1) {
									$q2 = "select max(ordre)*1+1 from responsability join authors on author_id=responsability_author where responsability_notice_id='" . $n . "' and responsability_type='1' ";
									$r2 = mysql_query ( $q2, $dbh );
									$n2 = mysql_result ( $r2, 0, 0 );
									$q3 = "insert ignore into responsability (responsability_author,responsability_notice,responsability_fonction,responsability_type,responsability_ordre) ";
									$q3 .= "values ('" . $aut . "','" . $n . "','','1','" . $n2 . "') ";
									mysql_query ( $q3, $dbh );
								} else {
									$q3 = "insert ignore into responsability (responsability_author,responsability_notice,responsability_fonction,responsability_type,responsability_ordre) ";
									$q3 .= "values ('" . $aut . "','" . $n . "','','0','0') ";
									mysql_query ( $q3, $dbh );
								}
							}
						}
						foreach ( $t_xml [$i] ['INM:AUTEUR-SECONDAIRE'] as $k => $v ) {
							$t_xml [$i] ['INM:AUTEUR-SECONDAIRE'] [$k] = trim ( $v );
							if (strpos ( $v, ',' ) === FALSE) {
								$compte ++;
								$tmp_val [$compte] ['name'] = clean_string ( utf8_decode ( $v ) );
								$tmp_val [$compte] ['type'] = '70';
								$aut = auteur::import ( $tmp_val [$compte] );
								$q2 = "select max(ordre)*1+1 from responsability join authors on author_id=responsability_author where responsability_notice_id='" . $n . "' and responsability_type='2' ";
								$r2 = mysql_query ( $q2, $dbh );
								$n2 = mysql_result ( $r2, 0, 0 );
								$q3 = "insert ignore into responsability (responsability_author,responsability_notice,responsability_fonction,responsability_type,responsability_ordre) ";
								$q3 .= "values ('" . $aut . "','" . $n . "','','2','" . $n2 . "') ";
								mysql_query ( $q3, $dbh );
							}
						}
						
						if ($compte != $tmp_compte) {
							print 'notice n° ' . $n . ' - ' . $t . '<br />';
							foreach($tmp_val as $v) {
								print $v['name'].'<br />';
							}
							print '<br/>';
							print '_______________________________<br />';
							$tmp_val = array ();
							$tmp_compte = $compte;
						}
					}
				}
			}
		}
		
		fclose ( $fichier );
		unlink ( "../../temp/" . basename ( $_FILES ['userfile'] ['tmp_name'] ) );
		print "Traitement du fichier termin&eacute;.";
		print "<br /><hr />";
		
		print "Nb total d'enregistrements modifi&eacute;s = " . $compte . '<br />';
		
		if (count ( $tab_err )) {
			for($i = 0; $i < count ( $tab_err ); $i ++) {
				print "Erreur &agrave; l&apos;enregistrement n° " . $tab_err [$i] . "<br />";
			}
			print "<hr /><br />";
		}
	
	} else {
		print "Le fichier n&apos;a pu &ecirc;tre lu .";
	}
}

//Méthodes du parser
function debutBalise($parser, $tag, $att) {
	return;
}

function finBalise($parser, $tag) {
	
	global $text, $t_xml, $n;
	if ($text === '')
		return;
	switch ($tag) {
		
		case 'INM:ID' :
			$n = $n + 1;
			$t_xml [$n] = array ();
			$t_xml [$n] [$tag] [] = $text;
			break;
		default :
			if ($n)
				$t_xml [$n] [$tag] [] = $text;
			break;
	}
	$text = '';
	return;
}

function texte($parser, $data) {
	
	global $text;
	if (trim ( $data ))
		$text .= $data;
	return;
}

?>
