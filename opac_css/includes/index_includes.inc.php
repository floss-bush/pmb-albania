<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: index_includes.inc.php,v 1.25 2010-12-08 15:46:42 gueluneau Exp $

error_reporting(E_ALL);
if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path."/includes/init.inc.php");
require_once($base_path."/includes/error_report.inc.php") ;
require_once($base_path."/includes/global_vars.inc.php");
require_once($base_path."/includes/rec_history.inc.php");
require_once($base_path.'/includes/opac_config.inc.php');
	
// récupération paramètres MySQL et connection á la base
if (file_exists($base_path.'/includes/opac_db_param.inc.php')) require_once($base_path.'/includes/opac_db_param.inc.php');
	else die("Fichier opac_db_param.inc.php absent / Missing file Fichier opac_db_param.inc.php");
	
require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

//Sessions !! Attention, ce doit être impérativement le premier include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");

require_once($base_path.'/includes/start.inc.php');
require_once($base_path."/includes/check_session_time.inc.php");

// récupération localisation
require_once($base_path.'/includes/localisation.inc.php');

// version actuelle de l'opac
require_once($base_path.'/includes/opac_version.inc.php');

// fonctions de gestion de formulaire
require_once($base_path.'/includes/javascript/form.inc.php');

require_once($base_path.'/includes/templates/common.tpl.php');
require_once($base_path.'/includes/divers.inc.php');

// classe de gestion des catégories
require_once($base_path.'/classes/categorie.class.php');
// ER 2008/08/03 N'EST PLUS UTILISEE que dans 
	// bulletin_affichage.inc.php 
	// bulletin_display.inc.php 
	// resa_planning.inc.php 
	// resa.inc.php: 
// require_once($base_path.'/classes/notice.class.php');

// classe indexation interne
require_once($base_path.'/classes/indexint.class.php');

// classe d'affichage des tags
require_once($base_path.'/classes/tags.class.php');
require_once($base_path."/includes/marc_tables/".$lang."/empty_words");

require_once($base_path."/includes/misc.inc.php");

// pour l'affichage correct des notices
require_once($base_path."/includes/templates/common.tpl.php");
require_once($base_path."/includes/templates/notice.tpl.php");
require_once($base_path."/includes/navbar.inc.php");
require_once($base_path."/includes/notice_authors.inc.php");
require_once($base_path."/includes/notice_categories.inc.php");

require_once($base_path."/includes/notice_affichage.inc.php");

require_once($base_path."/classes/analyse_query.class.php");

// pour fonction de formulaire de connexion
require_once($base_path."/includes/empr.inc.php");

//pour la gestion des tris
require_once($base_path."/classes/sort.class.php");

// si paramétrage authentification particulière
if (file_exists($base_path.'/includes/ext_auth.inc.php')) require_once($base_path.'/includes/ext_auth.inc.php');

// pour visualiser une notice issue de DSI avec une connexion auto: on limite la navigation
if($code) {		
	// pour fonction de vérification de connexion
	require_once($base_path.'/includes/empr_func.inc.php');
	$log_ok=connexion_empr();
	if($log_ok) $_SESSION["connexion_empr_auto"]=1;
}
if($_SESSION["connexion_empr_auto"]){
	$connexion_empr_auto=1;
	$opac_cart_allow=0;
	$opac_resa=0;
	$opac_resa_planning=0;
	$allow_tag =0;
	$allow_sugg=0;
	$allow_book=0;
	if( $lvl=='show_cart') $lvl='';
}

//Premier accès ??
if ($search_type_asked) $_SESSION["search_type"]=$search_type_asked;

if (($_SESSION["search_type"]=="")||((($lvl=="")||($lvl=="index"))&&($search_type_asked==""))) {
	$_SESSION["search_type"]="simple_search";
}

//Conserver l'endroit où on est et l'endroit où on va

if (!$_SESSION["nb_sortnotices"]) $_SESSION["nb_sortnotices"]=0; 


//Récupération du type de recherche
$search_type=$_SESSION["search_type"];

//Si vidage historique des recherches demandé ?
if ($raz_history) {
	
	require_once($base_path."/includes/history_functions.inc.php");
	
	if ((isset($_POST['cases_suppr'])) && !empty($_POST['cases_suppr'])) {
		$cases_a_suppr=$_POST['cases_suppr'];
		$t = array();
		
		//remplissage du tableau temporaire  de l'historique des recherches $t, si une recherche est sélectionnée, la valeur l'élément du tableau temporaire sera à -1 
		
		for ($i=1;$i<=$_SESSION["nb_queries"];$i++) {
			$bool=false;
			for ($j=0;$j<count($cases_a_suppr);$j++) {
				if ($i==$cases_a_suppr[$j]) {
					$bool=true;
					$j=count($cases_a_suppr);
				} else {
					$t[$i]=$i;
				}
			}
			if ($bool==true) {
				$t[$i]=-1;
			}
		}
		//parcours du tableau temporaire, et réécriture des variables de session
		
		for ($i=count($t);$i>=1;$i--) {
			if ($t[$i]=="-1") {
				$t1=array();
				$t1=suppr_histo($i,$t1);
				$t1=reorg_tableau_suppr($t1);
				$_SESSION["nb_queries"]=count($t1);
				foreach ($t1 as $key => $value) {
					if ($key!=$value) {
						$_SESSION["human_query".(string)$value]=$_SESSION["human_query".(string)$key];	
						$_SESSION["search_type".(string)$value]=$_SESSION["search_type".(string)$key];
						$_SESSION["user_query".(string)$value]=$_SESSION["user_query".(string)$key];
						$_SESSION["typdoc".(string)$value]=$_SESSION["typdoc".(string)$key];
						$_SESSION["look_TITLE".(string)$value]=$_SESSION["look_TITLE".(string)$key];
	       				$_SESSION["look_AUTHOR".(string)$value]=$_SESSION["look_AUTHOR".(string)$key];
	      				$_SESSION["look_PUBLISHER".(string)$value]=$_SESSION["look_PUBLISHER".(string)$key];
	      				$_SESSION["look_TITRE_UNIFORME".(string)$value]=$_SESSION["look_TITRE_UNIFORME".(string)$key];
	       				$_SESSION["look_COLLECTION".(string)$value]=$_SESSION["look_COLLECTION".(string)$key];
	       				$_SESSION["look_SUBCOLLECTION".(string)$value]=$_SESSION["look_SUBCOLLECTION".(string)$key];
	        			$_SESSION["look_CATEGORY".(string)$value]=$_SESSION["look_CATEGORY".(string)$key];
	       				$_SESSION["look_INDEXINT".(string)$value]=$_SESSION["look_INDEXINT".(string)$key];
	       				$_SESSION["look_KEYWORDS".(string)$value]=$_SESSION["look_KEYWORDS".(string)$key];
	       				$_SESSION["look_ABSTRACT".(string)$value]=$_SESSION["look_ABSTRACT".(string)$key];
	       				$_SESSION["look_CONTENT".(string)$value]=$_SESSION["look_CONTENT".(string)$key];
	       				$_SESSION["look_ALL".(string)$value]=$_SESSION["look_ALL".(string)$key];
	       				$_SESSION["look_DOCNUM".(string)$value]=$_SESSION["look_DOCNUM".(string)$key];
	       				$_SESSION["l_typdoc".(string)$value]=$_SESSION["l_typdoc".(string)$key];
					}
				}
			}
		}
		
		//si il ne subsiste plus d'historique de recherches, mise à null des variables de session
		if ($_SESSION["nb_queries"]==0) {
			$_SESSION["last_query"]="";
		} 
	} 
}


//Enregistrement dans historique si visualisation en mode term_search
if (($search_type=="term_search")&&($lvl=="categ_see")&&($rec_history==1)) {
	require_once($base_path."/includes/rec_history.inc.php");
	rec_history();
}
// pour les étagères et les nouveaux affichages
require_once($base_path."/includes/isbn.inc.php");
require_once($base_path."/classes/notice_affichage.class.php");
require_once($base_path."/includes/etagere_func.inc.php");
require_once($base_path."/includes/templates/etagere.tpl.php");

// RSS
require_once($base_path."/includes/includes_rss.inc.php");

if ($is_opac_included) {
	$std_header = $inclus_header ;
	$footer = $inclus_footer ;
}

// si $opac_show_homeontop est à 1 alors on affiche le lien retour à l'accueil sous le nom de la bibliothèque
if ($opac_show_homeontop==1) $std_header= str_replace("!!home_on_top!!",$home_on_top,$std_header);
else $std_header= str_replace("!!home_on_top!!","",$std_header);

// mise à jour du contenu opac_biblio_main_header
$std_header= str_replace("!!main_header!!",$opac_biblio_main_header,$std_header);

// RSS
$std_header= str_replace("!!liens_rss!!",genere_link_rss(),$std_header);
// l'image $logo_rss_si_rss est calculée par genere_link_rss() en global
$liens_bas = str_replace("<!-- rss -->",$logo_rss_si_rss,$liens_bas);

print $std_header;

if ($time_expired) echo "<script>alert(\"".sprintf($msg["session_expired"],round($opac_duration_session_auth/60))."\");</script>";

require_once($base_path.'/includes/navigator.inc.php');

if ((($opac_cart_allow)&&(!$opac_cart_only_for_subscriber))||(($opac_cart_allow)&&($_SESSION["user_code"]))) {
	$add_cart_link="<a href='cart_info.php?lvl=$lvl&id=$id' target='cart_info'>".$msg["cart_add_result_in"]."</a>";
	$add_cart_link_spe="<a href='cart_info.php?lvl=$lvl&id=$id!!spe!!' target='cart_info'>".$msg["cart_add_result_in"]."</a>";
}

$link_to_visionneuse = "
<script type='text/javascript' src='$base_path/visionneuse/javascript/visionneuse.js'></script>
<a href='#' onclick=\"open_visionneuse(sendToVisionneuse);return false;\">".$msg["result_to_phototeque"]."</a>";

//cas général
$sendToVisionneuseByPost ="
<script type='text/javascript'>
	function sendToVisionneuse(explnum_id){
		if (typeof(explnum_id)!= 'undefined') {
			var explnum =document.createElement('input');
			explnum.setAttribute('type','hidden');
			explnum.setAttribute('name','explnum_id');
			explnum.setAttribute('value',explnum_id);
			document.form_values.appendChild(explnum);
		}
		document.form_values.action='visionneuse.php';
		document.form_values.target='visionneuse';
		document.form_values.submit();	
	}
</script>";

//cas des autorités
$sendToVisionneuseByGet ="
<script type='text/javascript'>
	function sendToVisionneuse(explnum_id){
		document.getElementById('visionneuseIframe').src = \"visionneuse.php?mode=!!mode!!&idautorite=!!idautorite!!\"+(typeof(explnum_id) != 'undefined' ? '&explnum_id='+explnum_id : \"\");
	}
</script>";

//cas de notice display
$sendToVisionneuseNoticeDisplay ="
<script type='text/javascript'>
	function sendToVisionneuse(explnum_id){
		document.getElementById('visionneuseIframe').src = 'visionneuse.php?'+(typeof(explnum_id) != 'undefined' ? 'explnum_id='+explnum_id+\"\" : '\'');
	}
</script>";



switch($lvl) {
	case 'author_see':
		$author_type_aff=0;
		if($opac_congres_affichage_mode && $id) {
			$requete="select author_type from authors where author_id=".$id;
			$r_author=mysql_query($requete);
			if (@mysql_num_rows($r_author)) {
				$author_type=mysql_result($r_author,0,0);
				if($author_type == '71' || $author_type == '72') $author_type_aff=1;
			}			
		}
		if($author_type_aff) require_once($base_path.'/includes/congres_see.inc.php');
		else require_once($base_path.'/includes/author_see.inc.php');
	break;
	case 'categ_see':
		require_once($base_path.'/includes/categ_see.inc.php');
		break;		
	case 'indexint_see':
		require_once($base_path.'/includes/indexint_see.inc.php');
		break;		
	case 'coll_see':
		require_once($base_path.'/includes/coll_see.inc.php');
		break;		
	case 'more_results':
		require_once($base_path.'/includes/more_results.inc.php');
		break;		
	case 'notice_display':
		require_once($base_path.'/includes/notice_display.inc.php');
		break;
	case 'bulletin_display':
		require_once($base_path.'/includes/bulletin_display.inc.php');
		break;			
	case 'publisher_see':
		require_once($base_path.'/includes/publisher_see.inc.php');
		break;	
	case 'titre_uniforme_see':
		require_once($base_path.'/includes/titre_uniforme_see.inc.php');
		break;		
	case 'serie_see':
		require_once($base_path.'/includes/serie_see.inc.php');
		break;		
	case 'search_result':
		require_once($base_path.'/includes/search_result.inc.php');
		break;		
	case 'subcoll_see':
		require_once($base_path.'/includes/subcoll_see.inc.php');
		break;
	case 'search_history':
		require_once($base_path.'/includes/search_history.inc.php');
		break;	
	case 'etagere_see':
		require_once($base_path.'/includes/etagere_see.inc.php');
		break;	
	case 'etageres_see':
		require_once($base_path.'/includes/etageres_see.inc.php');
		break;
	case 'show_cart':
		require_once($base_path.'/includes/show_cart.inc.php');
		break;
	case 'show_list':
		require_once($base_path.'/includes/show_list.inc.php');
		break;
	case 'section_see':
		require_once($base_path.'/includes/show_localisation.inc.php');
		break;
	case 'rss_see':
		require_once($base_path.'/includes/rss_see.inc.php');
		break;
	case 'doc_command':	
		require_once($base_path.'/includes/doc_command.inc.php');
		break;
	case 'sort':
		require_once($base_path.'/includes/sort.inc.php');
		break;

	case 'lastrecords':
		require_once ($base_path.'/includes/templates/last_records.tpl.php');
		require_once ($base_path.'/includes/last_records.inc.php');
		break;
		
	case 'information':
		// Insertion page d'information
		// Ceci permet d'afficher une page d'info supplémentaire en incluant un fichier.
		// Ce fichier s'appelle sous la forme ./index.php?lvl=information&askedpage=NOM_DE_MON_FICHIER
		// NOM_DE_MON_FICHIER peut être une URL si le serveur l'autorise
		// NOM_DE_MON_FICHIER doit être déclaré dans les paramètres de l'OPAC de PMB : 
		// $opac_authorized_information_pages, tous les noms de fichiers autorisés séparés par une virgule
		//
		// Code pour tester la validité de la page demandée. Si la page ne figure pas dans les pages demandées : rien. 
		if ($opac_authorized_information_pages) {
			$array_pages = explode(",",$opac_authorized_information_pages);
			$as=array_search($askedpage,$array_pages);
			if (($as!==null)&&($as!==false)) include ($askedpage) ;
		}
		break;
	case 'infopages':
		// Insertion pages d'information internes paramétrées dans PMB
		// Ceci permet d'afficher une page d'info supplémentaire en incluant un code HTML lu en table.
		// Cette page s'appelle sous la forme ./index.php?lvl=internal&pagesid=#,#,#
		// tous les id des pages à afficher, séparés par une virgule, ils seront affichés dans l'ordre
		$idpages = array() ;
		$idpages = explode(",",$pagesid);
		require_once($base_path.'/includes/infopages.inc.php');
		break;
	case 'extend':
		if(file_exists($base_path.'/includes/extend.inc.php'))
			require_once($base_path.'/includes/extend.inc.php');
		break;
	case 'index':
	default:
		$lvl='index';
		require_once($base_path.'/includes/index.inc.php');
		break;
	}
	
	

if($pmb_logs_activate){
	//Enregistrement du log
	global $log, $infos_notice, $infos_expl, $nb_results_tab;
			
	$rqt= " select empr_prof,empr_cp, empr_ville, empr_year, empr_sexe, empr_login, empr_date_adhesion, empr_date_expiration, count(pret_idexpl) as nbprets, count(resa.id_resa) as nbresa, code.libelle as codestat, es.statut_libelle as statut, categ.libelle as categ, gr.libelle_groupe,dl.location_libelle 
			from empr e
			left join empr_codestat code on code.idcode=e.empr_codestat
			left join empr_statut es on e.empr_statut=es.idstatut
			left join empr_categ categ on categ.id_categ_empr=e.empr_categ
			left join empr_groupe eg on eg.empr_id=e.id_empr
			left join groupe gr on eg.groupe_id=gr.id_groupe
			left join docs_location dl on e.empr_location=dl.idlocation
			left join resa on e.id_empr=resa_idempr
			left join pret on e.id_empr=pret_idempr
			where e.empr_login='".addslashes($_SESSION['user_code'])."'
			group by resa_idempr, pret_idempr";	
	$res=mysql_query($rqt);
	if($res){
		$empr_carac = mysql_fetch_array($res);
		$log->add_log('empr',$empr_carac);
	}

	$log->add_log('num_session',session_id());
	$log->add_log('expl',$infos_expl);
	$log->add_log('docs',$infos_notice);
	
	//Enregistrement du nombre de résultats	
	$log->add_log('nb_results', $nb_results_tab);
	
	//Enregistrement multicritere
	global $search;
	if($search)	{
		if ($search_type=="external_search") {
			switch($_SESSION["ext_type"]) {
				case "multi":
					$search_file="search_fields_unimarc";
					break;
				default:
					$search_file="search_simple_fields_unimarc";
					break;
			}
			$search_stat = new search($search_file);
		} else 
			$search_stat = new search();
		$log->add_log('multi_search', $search_stat->serialize_search());
		$log->add_log('multi_human_query', $search_stat->make_human_query());
	}
	$log->save();
}
	
//insertions des liens du bas dans le $footer si $opac_show_liensbas
if ($opac_show_liensbas==1) $footer = str_replace("!!div_liens_bas!!",$liens_bas,$footer);
	else $footer = str_replace("!!div_liens_bas!!","",$footer);

//si ce n'est pas un popup qui est affiché, alors on affiche $footer
if ($opac_show_bandeaugauche==0) {
	print str_replace("!!contenu_bandeau!!","",$footer);
} else {
	$footer = str_replace("!!contenu_bandeau!!","<div id=\"bandeau\">!!contenu_bandeau!!</div>",$footer);
	$home_on_left=str_replace("!!welcome_page!!",$msg["welcome_page"],$home_on_left);
	$adresse=str_replace("!!common_tpl_address!!",$msg["common_tpl_address"],$adresse);
	$adresse=str_replace("!!common_tpl_contact!!",$msg["common_tpl_contact"],$adresse);
	$loginform=str_replace("!!common_tpl_login_invite!!",$msg["common_tpl_login_invite"],$loginform);
	
	// loading the languages available in OPAC - martizva >> Eric
	require_once($base_path.'/includes/languages.inc.php');
	$home_on_left = str_replace("!!common_tpl_lang_select!!", show_select_languages("index.php"), $home_on_left);
	
	if (!$_SESSION["user_code"]) {
		$loginform__ = genere_form_connexion_empr();
	} else {
		$loginform__.="<b>".$empr_prenom." ".$empr_nom."</b><br />\n";
		$loginform__.="<a href=\"empr.php\">".$msg["empr_my_account"]."</a><br />
			<a href=\"index.php?logout=1\" id=\"empr_logout_lnk\">".$msg["empr_logout"]."</a>";
	}
	$loginform = str_replace("!!login_form!!",$loginform__,$loginform);
	print str_replace("!!contenu_bandeau!!",$home_on_left.$loginform.$meteo.$adresse,$footer);
} 

mysql_close($dbh);
?>
