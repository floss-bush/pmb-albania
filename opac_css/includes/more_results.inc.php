<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: more_results.inc.php,v 1.53 2010-04-15 14:11:51 gueluneau Exp $style&lvl=more_results,v 1.2 2003/11/02 17:19:01 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

	// récupération configuration
	require_once($base_path."/includes/opac_config.inc.php");

	// récupération paramètres MySQL et connection à la base
	require_once($base_path."/includes/opac_db_param.inc.php");
	require_once($base_path."/includes/opac_mysql_connect.inc.php");
	$dbh = connection_mysql();
	
	require_once($base_path."/includes/start.inc.php");

	// récupération localisation
	require_once($base_path."/includes/localisation.inc.php");
	// les mots vides sont importants pour la requête à appliquer
	require_once($base_path."/includes/marc_tables/$lang/empty_words");
	
	// version actuelle de l'opac
	require_once($base_path."/includes/opac_version.inc.php");

	// fonctions de formattage requêtes
	require_once($base_path."/includes/misc.inc.php");


	// fonctions de gestion de formulaire
	require_once($base_path."/includes/javascript/form.inc.php");
	require_once($base_path."/includes/templates/common.tpl.php");
	
	require_once($base_path."/includes/rec_history.inc.php");
	
	require_once($include_path.'/surlignage.inc.php');
	
	if ($get_last_query) {
		get_last_history();
	} else {
		if ($_SESSION["new_last_query"]) {
			$_SESSION["last_query"]=$_SESSION["new_last_query"];
			$_SESSION["new_last_query"]="";
		}
		rec_last_history();
	}
	//Surlignage
	require_once("$include_path/javascript/surligner.inc.php");
	print $inclure_recherche;
	// lien pour retour au sommaire
		
	//print "<a href=\"./index.php?lvl=index\">
	//		<img src=\"./images/home.gif\" border=\"0\" title=\"$msg[back_summmary]\" alt=\"$msg[back_summary]\">$msg[back_summary]</a>"; 

	// affichage recherche
	$clause = stripslashes($clause);
	$tri = stripslashes($tri);
	$pert=stripslashes($pert);
	$clause_bull = stripslashes($clause_bull);
	$join = stripslashes($join);
/*	 les données disponibles dans ce script sont :
	$user_query : la requête utilisateur
	$mode : sur quoi porte la recherche
	$count : le nombre de résultats trouvés
	$clause : la chaine contenant la clause MySQL
	$tri : la chaine contenant la clause MySQL de tri
*/
	
	
	// le lien pour retour aux résultatsv : supprimé, le texte recherché est renvoyé dans le formulaire de recherche simple_search.inc.php
	

	// nombre de références par pages (10 par défaut)
	if (!isset($opac_search_results_per_page)) $opac_search_results_per_page=10; 

	if(!$page) $page=1;
	$debut =($page-1)*$opac_search_results_per_page;

	$limiter = "LIMIT $debut,$opac_search_results_per_page";
	
	if ((($opac_cart_allow)&&(!$opac_cart_only_for_subscriber))||(($opac_cart_allow)&&($_SESSION["user_code"]))) $add_cart_link="<a href='javascript:document.cart_values.submit()'>".$msg["cart_add_result_in"]."</a>";
	
	switch($mode) {
		case 'tous':
			require_once($base_path.'/search/level2/tous.inc.php');
			break;
		case 'titre':
			require_once($base_path.'/search/level2/title.inc.php');
			break;
		case 'auteur':
			require_once($base_path.'/search/level2/author.inc.php');
			break;
		case 'editeur':
			require_once($base_path.'/search/level2/publisher.inc.php');
			break;
		case 'titre_uniforme':
			require_once($base_path.'/search/level2/titre_uniforme.inc.php');
			break;			
		case 'collection':
			require_once($base_path.'/search/level2/collection.inc.php');
			break;
		case 'souscollection':
			require_once($base_path.'/search/level2/subcollection.inc.php');
			break;
		case 'categorie':
			require_once($base_path.'/search/level2/category.inc.php');
			break;
		case 'indexint':
			require_once($base_path.'/search/level2/indexint.inc.php');
			break;
		case 'abstract':
			require_once($base_path.'/search/level2/abstract.inc.php');
			break;
		case 'keyword':
			if ($search_type=="extended_search") $search_type="";
			require_once($base_path.'/search/level2/keyword.inc.php');
			break;
		case 'extended':
			require_once($base_path.'/search/level2/extended.inc.php');
			break;
		case 'external':
			require_once($base_path.'/search/level2/external.inc.php');
			break;
		case 'docnum':
			require_once($base_path.'/search/level2/docnum.inc.php');
			break;
		default:
			print $msg[no_document_found];
			break;
	}
switch ($search_type) {
	case 'simple_search':
	case 'tags_search':
		// constitution du form pour la suite
		$form .= "<input type=\"hidden\" name=\"user_query\" value=\"".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\">\n";
		$form .= "<input type=\"hidden\" name=\"count\" value=\"$count\">\n";
		$form .= "<input type=\"hidden\" name=\"clause\" value=\"".htmlentities($clause,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"clause_bull\" value=\"".htmlentities($clause_bull,ENT_QUOTES,$charset)."\">\n";
		if($opac_indexation_docnum_allfields) 
			$form .= "<input type=\"hidden\" name=\"join\" value=\"".htmlentities($join,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"tri\" value=\"".htmlentities($tri,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"pert\" value=\"".htmlentities($pert,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" name=\"l_typdoc\" value=\"".htmlentities($l_typdoc,ENT_QUOTES,$charset)."\">\n";
		$form .= "<input type=\"hidden\" id=author_type name=\"author_type\" value=\"$author_type\">\n";		
		$form .= "<input type=\"hidden\" id=\"id_thes\" name=\"id_thes\" value=\"".$id_thes."\">\n";
		$form .= "<input type=\"hidden\" name=\"surligne\" value=\"".$surligne."\">\n";
		$form .= "<input type=\"hidden\" name=\"tags\" value=\"".$tags."\">\n";
		$f_values=$form;
		$form = "<form name=\"form_values\" action=\"./index.php?lvl=more_results\" method=\"post\">\n";
		$form .= $f_values;
		$form .= "<input type=\"hidden\" name=\"page\" value=\"$page\">\n";
		$form .= "<input type=\"hidden\" name=\"nbexplnum_to_photo\" value=\"".$nbexplnum_to_photo."\">\n";
		$form .= "</form>";
		if ((($opac_cart_allow)&&(!$opac_cart_only_for_subscriber))||(($opac_cart_allow)&&($_SESSION["user_code"]))) {
			$form .= "<form name='cart_values' action='./cart_info.php?lvl=more_results' method='post' target='cart_info'>\n";
			$form .= $f_values;
			$form .= "</form>";
		}
		break;
	case 'extended_search':
		$form=$es->make_hidden_search_form("./index.php?lvl=more_results&mode=extended");
		if ((($opac_cart_allow)&&(!$opac_cart_only_for_subscriber))||(($opac_cart_allow)&&($_SESSION["user_code"]))) 
			$form.=$es->make_hidden_search_form("./cart_info.php?lvl=more_results&mode=extended","cart_values","cart_info");
		break;
	case 'external_search':
		$form=$es->make_hidden_search_form("./index.php?lvl=more_results&mode=external","form_values","",false);
		if ($_SESSION["ext_type"]!="multi") {
			$form.="<input type='hidden' name='external_env' value='".htmlentities(stripslashes($external_env),ENT_QUOTES,$charset)."'/>";
			$form.="</form>";
		} else $form.="</form>";
		if ((($opac_cart_allow)&&(!$opac_cart_only_for_subscriber))||(($opac_cart_allow)&&($_SESSION["user_code"]))) 
			$form.=$es->make_hidden_search_form("./cart_info.php?lvl=more_results&mode=external","cart_values","cart_info");
		break;
}
print pmb_bidi($form);

// constitution des liens
$nbepages = ceil($count/$opac_search_results_per_page);
echo "<div class='row'></div>";

$url_page = "javascript:document.form_values.page.value=!!page!!; document.form_values.submit()";
$action = "javascript:document.form_values.page.value=document.form.page.value; document.form_values.submit()";
print "<hr />\n<center>".printnavbar($page, $nbepages, $url_page,$action)."</center>";