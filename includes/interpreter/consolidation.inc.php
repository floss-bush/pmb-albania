<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: consolidation.inc.php,v 1.8 2010-08-12 12:33:31 touraine37 Exp $

global $include_path, $class_path, $base_path;
require_once ($include_path . "/misc.inc.php");
require_once ($class_path."/XMLlist.class.php");
require_once ($class_path."/search.class.php");

$func_format['mots_saisis']= aff_mots_saisis;
$func_format['url_ori']= aff_url_ori;
$func_format['url_asked']= aff_url_asked;
$func_format['num_session']=aff_num_session;
$func_format['login']=aff_login;
$func_format['adresse_ip']=aff_adresse_ip;
$func_format['user_agent']=aff_user_agent;
$func_format['type_page']=aff_type_page;
$func_format['sous_type_page']=aff_sous_type_page;
$func_format['type_page_lib']=aff_libelle_type_page;
$func_format['sous_type_page_lib']=aff_libelle_sous_type_page;
$func_format['multi_libelle']=aff_libelle_multicritere;
$func_format['multi_contenu']=aff_contenu_multicritere;
$func_format['multi_intitule']=aff_intitule_multicritere;

//Fonctions emprunteur
$func_format['empr_age']=aff_age_user;
$func_format['empr_groupe']=aff_groupe_user;
$func_format['empr_codestat']=aff_codestat_user;
$func_format['empr_categ']=aff_categ_user;
$func_format['empr_statut']=aff_statut_user;
$func_format['empr_location']=aff_location_user;
$func_format['empr_ville']=aff_ville_user;


//Fonctions date/heure
$func_format['timestamp']=aff_timestamp;
$func_format['date']=aff_date;
$func_format['year']=aff_year;
$func_format['month']=aff_month;
$func_format['day']=aff_day;
$func_format['hour']=aff_hour;
$func_format['minute']=aff_minute;
$func_format['seconde']=aff_seconde;
$func_format['elapsed_time']=aff_elapsed_time;

//Fonctions sur les nombres de résultats
$func_format['nb_all'] = aff_nb_all_result;
$func_format['nb_auteurs'] = aff_nb_auteurs;
$func_format['nb_collectivites'] = aff_nb_auteurs_collectivites;
$func_format['nb_congres'] = aff_nb_auteurs_congres;
$func_format['nb_physiques'] = aff_nb_auteurs_physiques;
$func_format['nb_editeurs'] = aff_nb_editeurs;
$func_format['nb_titres'] = aff_nb_titres;
$func_format['nb_titres_uniformes'] = aff_nb_titres_uniformes;
$func_format['nb_abstract'] = aff_nb_abstract;
$func_format['nb_categories'] = aff_nb_categories;
$func_format['nb_collections'] = aff_nb_collections;
$func_format['nb_subcollections'] = aff_nb_subcollections;
$func_format['nb_docnum'] = aff_nb_docnum;
$func_format['nb_keywords'] = aff_nb_keywords;
$func_format['nb_indexint'] = aff_nb_indexint;
$func_format['nb_total'] = aff_nb_result_total;

/********************************************************************
 * 																	*
 *      FONCTIONS DE CALCULS QUI RETOURNE LES VALEURS DESIREES      *
 *  																*
 ********************************************************************/

/**
 * Retourne l'url appelante
 */
function aff_url_ori($param, $parser){
	return $parser->environnement['ligne']['url_referente'];
}

/**
 * Retourne l'url appelée
 */
function aff_url_asked($param, $parser){
	return $parser->environnement['ligne']['url_demandee'];
}

/**
 * Retourne le numéro de session du log
 */
function aff_num_session($param,$parser){
	return $parser->environnement['ligne']['num_session'];
}

/**
 * Retourne le mot saisi
 */
function aff_mots_saisis($param,$parser){	
	$post = get_var_post($param,$parser);
	return $post['user_query'];
}

/**
 * Retourne le login de l'utilisateur
 
function aff_login($param,$parser){
	return get_info_user($param,$parser,'empr_login');	
}*/

/**
 * Retourne l'adresse IP de l'utilisateur
 */
function aff_adresse_ip($param,$parser){
	$server = get_var_server($param,$parser);
	return $server['REMOTE_ADDR'];
}

/**
 * Retourne le user agent de l'utilisateur
 */
function aff_user_agent($param,$parser){
	$server = get_var_server($param,$parser);
	return $server['HTTP_USER_AGENT'];
}


/****************************************************************************
 * 																	        *
 *  FONCTIONS DE CALCULS QUI RETOURNE LES CARACTERISTIQUES DE L'EMPRUNTEUR  *
 *  																        *
 ****************************************************************************/

/**
 * Retourne l'âge de l'utilisateur
 */
function aff_age_user($param,$parser){
	$info_user = get_info_user($param,$parser);
	$birth_date = $info_user['empr_year'];
	$today = split('-',today());
	if($birth_date){
		return ($today[0]-$birth_date);
	}
}

/**
 * Retourne le groupe de l'utilisateur
 */
function aff_groupe_user($param,$parser){
	$info_user = get_info_user($param,$parser);
	return $info_user['groupe'];	
}

/**
 * Retourne le code statistique de l'utilisateur
 */
function aff_codestat_user($param,$parser){
	$info_user = get_info_user($param,$parser);
	return $info_user['codestat'];	
}

/**
 * Retourne le statut de l'utilisateur
 */
function aff_statut_user($param,$parser){
	$info_user = get_info_user($param,$parser);
	return $info_user['statut'];	
}

/**
 * Retourne la catégorie de l'utilisateur
 */
function aff_categ_user($param,$parser){
	$info_user = get_info_user($param,$parser);
	return $info_user['categ'];	
}

/**
 * Retourne la localisation de l'utilisateur
 */
function aff_location_user($param,$parser){
	$info_user = get_info_user($param,$parser);
	return $info_user['location'];	
}

/**
 * Retourne la ville de l'utilisateur
 */
function aff_ville_user($param,$parser){
	$info_user = get_info_user($param,$parser);
	return $info_user['ville'];	
}

/**
 * Retourne le login de l'utilisateur
 */
function aff_login($param,$parser){
	$info_user = get_info_user($param,$parser);
	return $info_user['empr_login'];	
}
/********************************************************************
 * 																	*
 *           FONCTIONS SUR LA DATE ET l'HEURE DES LOGS				*
 *  																*
 ********************************************************************/

/**
 * Retourne l'heure du log HH:MM:SS du log
 */
function aff_timestamp($param,$parser){	
	return $parser->environnement['ligne']['date_log'];
}

/**
 * Retourne la date du log
 */
function aff_date($param,$parser){	
	return substr($parser->environnement['ligne']['date_log'],0,10);
}

/**
 * Retourne l'heure du log
 */
function aff_hour($param,$parser){	
	return substr($parser->environnement['ligne']['date_log'],11,2);
}

/**
 * Retourne l'année du log
 */
function aff_year($param,$parser){	
	return substr($parser->environnement['ligne']['date_log'],0,4);
}

/**
 * Retourne le jour du log
 */
function aff_day($param,$parser){	
	return substr($parser->environnement['ligne']['date_log'],8,2);
}

/**
 * Retourne le mois du log
 */
function aff_month($param,$parser){	
	return substr($parser->environnement['ligne']['date_log'],5,2);
}

/**
 * Retourne les minutes du log
 */
function aff_minute($param,$parser){	
	return substr($parser->environnement['ligne']['date_log'],14,2);
}

/**
 * Retourne les secondes du log
 */
function aff_seconde($param,$parser){	
	return substr($parser->environnement['ligne']['date_log'],17,2);
}

/**
 * Retourne le temps écoulé dans un intervalle
 */
function aff_elapsed_time($param,$parser){
	$filtre = $parser->environnement['filtre'];
	$timestamp_current = sql_value("SELECT date_log from ".$parser->environnement['tempo']." where id_log=".$parser->environnement['num_ligne']);
	return sql_value("SELECT TIME_TO_SEC(TIMEDIFF(date_log,'".$timestamp_current."')) from ".$filtre." where date_log > '".$timestamp_current."' limit 1");
}

/********************************************************************
 * 																	*
 *               CLASSIFICATION DES TYPES DE PAGE					*
 *  																*
 ********************************************************************/


/**
 * Retourne le type de page consultée
 */
function aff_type_page($param, $parser){
	
	$post = get_var_post($param,$parser);
	$get = get_var_get($param,$parser);
	
	if($post['lvl']){
		$niveau = $post['lvl'];
	} elseif ($get['lvl']){
		$niveau = $get['lvl'];
	} else $niveau='';
	
	if($post['mode']){
		$mode = $post['mode'];
	} elseif ($get['mode']){
		$mode = $get['mode'];
	} else $mode='';
	
	if ($get['oresa']){
		$sugg = $get['oresa'];
	} else $sugg='';
	
	
	$page = array("recherche" => 1, "result" => 2, "result_noti" => 3, "result_aut" => 4, "aut" => 5, 
				"display" => 6, "empr" => 7, "caddie" => 8, "histo" => 9, "etagere" => 10, "infopage" => 11,
				"tag" => 12, "notation" => 13, "sugg" => 14, "rss" => 15, "section" => 16,
				"sort" => 17, "information" => 18, "doc_command" => 19 );
	
	//url
	$url = aff_url_asked($param,$parser);
	
	//Avis et tags
	if(strpos($url,'avis.php') && strpos($url,'liste')){
		return $page['notation'];
	} elseif (strpos($url,'avis.php') && strpos($url,'add')){
		return $page['notation'];
	} elseif (strpos($url,'addtags.php')){
		return $page['tag'];
	}
	
	$type_page='';
	switch($niveau){		
		case 'author_see':
		case 'titre_uniforme_see':
		case 'serie_see':
		case 'categ_see':
		case 'indexint_see':
		case 'publisher_see':
		case 'coll_see':
		case 'subcoll_see':
			$type_page=$page['aut'];
			break;		
		case 'more_results':
			if($mode=='titre' || $mode=='tous')
				$type_page=$page['result_noti'];
			else 
				$type_page=$page['result_aut'];
			break;		
		case 'notice_display':
		case 'bulletin_display':
			$type_page=$page['display'];
			break;			
		case 'search_result':
			$type_page=$page['result'];
			break;		
		case 'search_history':
			$type_page=$page['histo'];
			break;	
		case 'etagere_see':
		case 'etageres_see':
			$type_page=$page['etagere'];
			break;
		case 'cart':
		case 'show_cart':
			$type_page=$page['caddie'];
			break;
		case 'section_see':
			$type_page=$page['section'];
			break;
		case 'rss_see':
			$type_page=$page['rss'];
			break;
		case 'doc_command':	
			$type_page=$page['doc_command'];
			break;
		case 'sort':
			$type_page=$page['sort'];
			break;
		case 'lastrecords':
			$type_page=$page['result_noti'];
			break;		
		case 'information':
			$type_page=$page['information'];
			break;
		case 'infopages':
			$type_page=$page['infopage'];
			break;
		case 'index':
			$type_page=$page['recherche'];	
			break;			
		case 'make_sugg':
			if($sugg) $type_page=$page['sugg'];
			else $type_page=$page['empr'];
			break;
		case 'valid_sugg':
		case 'view_sugg':
		case 'late':
		case 'change_password':
		case 'valid_change_password':
		case 'message':
		case 'all':			
		case 'old':
		case 'resa':
		case 'resa_planning':
		case 'bannette':
		case 'bannette_gerer':
		case 'bannette_creer':
		case 'make_multi_sugg':
		case 'private_list':
		case 'public_list':
		case 'demande_list':		
		case 'do_dmde':
		case 'list_dmde':
			$type_page=$page['empr'];
			break;	
		default:	
			$type_page=$page['recherche'];	
			break;
		
	}
	
	return $type_page;
	
}

/**
 * Fonction qui permet de classifier le sous type des pages selon un code 
 */
function aff_sous_type_page($param,$parser){
	
	$post = get_var_post($param,$parser);
	$get = get_var_get($param,$parser);
	$notice = get_info_notice($param,$parser);
	
	//récuperation des différentes variables nécessaires à l'identification des pages
	if($post['lvl']){
		$niveau = $post['lvl'];
	} elseif ($get['lvl']){
		$niveau = $get['lvl'];
	} else $niveau='';
	
	//type recherche
	if($post['search_type_asked']){
		$type = $post['search_type_asked'];
	} elseif ($get['search_type_asked']){
		$type = $get['search_type_asked'];
	} else $type='';
	
	//pour recherche prédéfinie
	if ($post['onglet_persopac']){
		$perso = $post['onglet_persopac'];
	} elseif ($get['onglet_persopac']){
		$perso = $get['onglet_persopac'];
	} else $perso='';	
	
	//pour les types d'autorité
	if($post['mode']){
		$mode = $post['mode'];
	} elseif ($get['mode']){
		$mode = $get['mode'];
	} else $mode='';
	
	//nivo biblio
	if($notice['niveau_biblio']){
		$biblio = $notice['niveau_biblio'];
	} else $biblio='';
	
	//suggestion
	if ($get['oresa']){
		$sugg = $get['oresa'];
	} else {
		$url_ref = aff_url_ori($param,$parser);
		$sugg = strpos($url_ref,'oresa=popup');
	}
	
	//pour le panier
	if($post['action']){
		$action = $post['action'];
	} elseif ($get['action']){
		$action = $get['action'];
	} else $action='';
	
	//url
	$url = aff_url_asked($param,$parser);
	
	//Avis et tags
	if(strpos($url,'avis.php') && strpos($url,'liste')){
		return '1301';
	} elseif (strpos($url,'avis.php') && strpos($url,'add')){
		return '1302';
	} elseif (strpos($url,'addtags.php')){
		return '1201';
	}
	
	
	$search_type='';
	switch($niveau){		
		case 'author_see':
			$search_type = '501'; 
			break;
		case 'categ_see':
			$search_type = '503'; 
			break;		
		case 'indexint_see':
			$search_type = '507'; 
			break;		
		case 'coll_see':
			$search_type = '505'; 
			break;		
		case 'more_results':
			switch($mode){
				case 'titre':
					$search_type = '301'; 
					break;
				case 'tous':
					$search_type = '302'; 
					break;
				case 'auteur':
					$search_type = '401'; 
					break;	
				case 'editeur':
					$search_type = '402'; 
					break;
				case 'categorie':
					$search_type = '403'; 
					break;
				case 'titre_uniforme':
					$search_type = '404'; 
					break;
				case 'collection':
					$search_type = '405'; 
					break;
				case 'souscollection':
					$search_type = '406'; 
					break;	
				case 'indexint':
					$search_type = '407'; 
					break;
				case 'keyword':
					$search_type = '408'; 
					break;
				default:
					break;
			}
			break;		
		case 'notice_display':
			switch($biblio){
				case 's':
					$search_type = '602'; 
				   break;
				case 'b':
					$search_type = '603'; 
					break;
				case 'a':
					$search_type = '604'; 
					break;
				default:
					$search_type = '601'; 
					break;
			}
			break;
		case 'bulletin_display':
			$search_type = '603'; 
			break;			
		case 'publisher_see':
			$search_type = '502'; 
			break;	
		case 'titre_uniforme_see':
			$search_type = '504'; 
			break;		
		case 'serie_see':
			$search_type = '508'; 
			break;		
		case 'search_result':
			switch($type){		
				case 'external_search': 
					$search_type = '204'; 
					break;	
				case 'term_search':
					$search_type = '203';
					break;
				case 'extended_search':
					if($perso) 
						$search_type = '206';
					else $search_type = '202'; 			
					break;	
				case 'search_perso':
					$search_type='206';
					break;
				case 'tags_search':
					$search_type = '205'; 
					break;
				case 'simple_search':
					$search_type = '201'; 
					break;
				default:
					$search_type = '207'; 
					break;
			}	
			break;		
		case 'subcoll_see':
			$search_type = '506'; 
			break;
		case 'search_history':
			$search_type = '901'; 
			break;	
		case 'etagere_see':
			$search_type = '1001'; 
			break;	
		case 'etageres_see':
			$search_type = '1002'; 
			break;
		case 'show_cart':
			$search_type = '801'; 
			break;
		case 'section_see':
			$search_type = '1601'; 
			break;
		case 'rss_see':
			$search_type = '1501';
			break;
		case 'doc_command':	
			$search_type = '1901';
			break;
		case 'sort':
			$search_type = '1701';
			break;
		case 'lastrecords':
			$search_type = '303';
			break;		
		case 'information':
			$search_type = '1801';
			break;
		case 'infopages':
			$search_type = '1101';
			break;
		case 'index':
				switch($type){		
					case 'external_search': 
						$search_type = '104'; 
						break;	
					case 'term_search':
						$search_type = '103';
						break;
					case 'extended_search':
						if($perso) 
							$search_type = '106';
						else $search_type = '102'; 			
						break;	
					case 'search_perso':
						$search_type='106';
						break;
					case 'tags_search':
						$search_type = '105'; 
						break;
					case 'simple_search':
						$search_type = '101'; 
						break;
					default:
						$search_type = '107'; 
						break;
				}	
			break;			
		case 'change_password':
			$search_type = '704';
			break;
		case 'valid_change_password':
			$search_type = '705';
			break;
		case 'message':
			//$type_page=$page['empr'];
			break;
		case 'all':			
			$search_type = '702';	
			break;
		case 'old':
			$search_type = '712';
			break;
		case 'resa':
			$search_type = '703';
			break;
		case 'resa_planning':
			//$type_page=$page['empr'];
			break;
		case 'bannette':
			$search_type = '706';
			break;
		case 'bannette_gerer':
			$search_type = '707';
			break;
		case 'bannette_creer':
			$search_type = '708';
			break;
		case 'make_sugg':
			if($sugg)
				$search_type = '1401';
			else $search_type = '709';
			break;
		case 'valid_sugg':
			if($sugg)
				$search_type = '1402';
			else $search_type = '710';
			break;
		case 'view_sugg':
			$search_type = '711';
			break;
		case 'late':
			$search_type = '701';
			break;
		case 'make_multi_sugg':
			$search_type = '712';
			break;
		case 'private_list':
			$search_type = '713';
			break;
		case 'public_list':
			$search_type = '714';
			break;
		case 'demande_list':		
			$search_type = '715';
			break;
		case 'do_dmde':
			$search_type = '717';
			break;
		case 'list_dmde':
			$search_type = '718';
			break;
		case 'cart':
			switch($action){	
				case 'print_cart':
					$search_type = '802';
					break;		
				default:
					$search_type = '801';
					break;
			}
			break;
		case 'list':
			switch($action){
				case 'print_list':
					$search_type = '716';
					break;
			}
			break;
		default:	
			switch($type){		
				case 'external_search': 
					$search_type = '104'; 
					break;	
				case 'term_search':
					$search_type = '103';
					break;
				case 'extended_search':
					if($perso) 
						$search_type = '106';
					else $search_type = '102'; 			
					break;	
				case 'search_perso':
					$search_type='106';
					break;
				case 'tags_search':
					$search_type = '105'; 
					break;
				case 'simple_search':
					$search_type = '101'; 
					break;
				default:
					$search_type = '107'; 
					break;
			}	
			if($action == 'export')
					$search_type = '803';
			break;
		
	}
	
	return $search_type;
}


function aff_libelle_type_page($param,$parser){
	global $lang, $include_path;
	
	if(file_exists($include_path."/interpreter/statopac/$lang.xml")){
		$liste_libelle = new XMLlist($include_path."/interpreter/statopac/$lang.xml");
	} else {
		$liste_libelle = new XMLlist($include_path."/interpreter/statopac/fr_FR.xml");
	}
	$liste_libelle->analyser();
	$libelles = $liste_libelle->table;
	
	$value_page = aff_type_page($param,$parser);
		
	return $libelles[$value_page];
}

function aff_libelle_sous_type_page($param,$parser){
	global $lang, $include_path;
	
	if(file_exists($include_path."/interpreter/statopac/$lang.xml")){
		$liste_libelle = new XMLlist($include_path."/interpreter/statopac/$lang.xml");
	} else {
		$liste_libelle = new XMLlist($include_path."/interpreter/statopac/fr_FR.xml");
	}
	$liste_libelle->analyser();
	$libelles = $liste_libelle->table;
	
	$value_page = aff_sous_type_page($param,$parser);
		
	return $libelles[$value_page];
}
/********************************************************************
 * 																	*
 *              FONCTIONS SUR LE NOMBRE DE RESULTATS           		*
 *  																*
 ********************************************************************/

function aff_nb_all_result($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['tous'];
}

function aff_nb_auteurs($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['auteurs'];
}

function aff_nb_auteurs_collectivites($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['collectivites'];
}

function aff_nb_auteurs_congres($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['congres'];
}

function aff_nb_auteurs_physiques($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['physiques'];
}

function aff_nb_editeurs($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['editeurs'];
}

function aff_nb_titres($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['titres'];
}
function aff_nb_titres_uniformes($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['titres_uniformes'];
}

function aff_nb_abstract($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['abstract'];
}

function aff_nb_categories($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['categories'];
}

function aff_nb_collections($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['collections'];	
}

function aff_nb_subcollections($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['subcollections'];
}

function aff_nb_docnum($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['docnum'];
}

function aff_nb_keywords($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['keywords'];
}

function aff_nb_indexint($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	return $nb_result['indexint'];
}

function aff_nb_result_total($param,$parser){
	$nb_result = get_nb_result($param,$parser);
	if(!count($nb_result))
		return 0;
	else {
		$nb=0;
		foreach ($nb_result as $key=>$value){
			if(is_array($value)) {
				for($i=0;$i<count($value);$i++){
					$nb = $nb + $value[$i];
				}
			} else 
				$nb = $nb + $value;
		}
		return $nb;
	}
}

/*
 * Affiche le libelle des champs sélectionnés dans la multicritere
 */
function aff_libelle_multicritere($param,$parser){
	
	$tab = get_info_generique($param,$parser);

	if($tab['multi_search']){	 
		$to_unserialize=unserialize($tab['multi_search']);
	    $search=$to_unserialize["SEARCH"];
		$sc = new search();
		$title = array();
		for ($i=0; $i<count($search); $i++) {
	   		$s=explode("_",$search[$i]);
	   		if ($s[0]=="f") {
	   			$title[]=$sc->fixedfields[$s[1]]["TITLE"]; 
	   			
	   		} elseif ($s[0]=="d") {
	   			$title[]=$sc->pp->t_fields[$s[1]]["TITRE"];
	   		} elseif ($s[0]=="s") {
	   			$title[]=$sc->specialfields[$s[1]]["TITLE"];
	   		}
		}
		return implode(',',$title);
	}
	return '';
	
}

/********************************************************************
 * 																	*
 *  			FONCTIONS POUR LA MULTICRITERE   					*
 *  																*
 ********************************************************************/

/*
 * Affiche le contenu des champs sélectionnés dans la multicritere
 */
function aff_contenu_multicritere($param,$parser){
	
	$tab = get_info_generique($param,$parser);

	if($tab['multi_search']){	 
		$to_unserialize=unserialize($tab['multi_search']);
	    $search=$to_unserialize["SEARCH"];
		$sc = new search();
		$mots = array();
		for ($i=0; $i<count($search); $i++) {
	   		$field = "field_".$i."_".$search[$i];
	   		$$field = $to_unserialize[$i]["FIELD"][0];
	   		$mots[] = $$field;
		}
		return implode(',',$mots);
	}
	return '';
	
}

/*
 * Affiche l'intitulé de la requête multicritère
 */
function aff_intitule_multicritere($param,$parser){
	$tab = get_info_generique($param,$parser);
	
	return strip_tags($tab['multi_human_query']);
}

/********************************************************************
 * 																	*
 *   FONCTIONS SUR LES VARIABLES GLOBALES ET LES CARACTERISTIQUES	*
 * 			 DES NOTICES, EXEMPLAIRES ET EMPRUNTEURS				*
 *  																*
 ********************************************************************/

/**
 * Retourne les valeurs de la variable $_POST 
 */
function get_var_post($param,$parser){
	if($parser->environnement['num_ligne']){
		return unserialize($parser->environnement['ligne']['post_log']);
	}
	return '';
}

/**
 * Retourne les valeurs de la variable $_GET 
 */
function get_var_get($param,$parser){
	if($parser->environnement['num_ligne']){
		return unserialize($parser->environnement['ligne']['get_log']);
	}
	return '';
}

/**
 * Retourne les valeurs de la variable $_SERVER 
 */
function get_var_server($param,$parser){
	if($parser->environnement['num_ligne']){
		return unserialize($parser->environnement['ligne']['server_log']);
	}
	return '';
}

/**
 * Retourne les informations sur l'utilisateur(année de naissance, ...) 
 */
function get_info_user($param,$parser){
	if($parser->environnement['num_ligne']){
		return unserialize($parser->environnement['ligne']['empr_carac']);
	}
	return '';
}

/**
 * Retourne les informations sur la notice
 */
function get_info_notice($param,$parser){
	
	if($parser->environnement['num_ligne']){
		return unserialize($parser->environnement['ligne']['empr_doc']);
	}
	return '';
}

/**
 * Retourne les informations sur l'exemplaire 
 */
function get_info_expl($param,$parser){
	if($parser->environnement['num_ligne']){
		return unserialize($parser->environnement['ligne']['empr_expl']);
	}
	return '';
}

/**
 * Retourne les nombres de résultats de recherche
 */
function get_nb_result($param,$parser){
	if($parser->environnement['num_ligne']){
		return unserialize($parser->environnement['ligne']['nb_result']);
	}
	return 0;
}

/**
 * Retourne les informations du tableau générique
 */
function get_info_generique($param,$parser){
	if($parser->environnement['num_ligne']){
		return unserialize($parser->environnement['ligne']['gen_stat']);
	}
	return '';
}

function get_infos($param,$parser){
	if($parser->environnement['num_ligne']){
		return $parser->environnement['ligne'];
	}
	return '';
}

/****************************************
 * 										*							
 *   FONCTIONS GENERIQUES USUELLES		*		
 *  									*							
 ****************************************/

/**
 * Teste si la fonction existe
 * 
 */
function func_test($f_name){
	global $func_format;
	if($func_format[$f_name]) return 1;
return 0;
}


/**
 * Retourne la valeur associée à la requête si elle existe
 */
function sql_value($rqt) {
	if($result=mysql_query($rqt)){
		if($row = mysql_fetch_row($result))	
			return $row[0];
	}
	return '';
}
?>