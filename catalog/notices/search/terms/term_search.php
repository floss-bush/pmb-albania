<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_search.php,v 1.9 2007-07-28 07:04:29 touraine37 Exp $
//
// Recherche des termes correspondants à la saisie

$base_path="../../../..";                            
$base_auth = ""; 
$base_title="Recherche par termes";

require_once ("$base_path/includes/init.inc.php");  
require_once ("$class_path/term_search.class.php");


//Récupération des paramètres du formulaire appellant
$base_query = "";

//Page en cours d'affichage
$n_per_page=$thesaurus_categories_term_search_n_per_page;

if ($page_search) 
	$page=$page_search; 
else {
	if (($_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["SEARCH_TYPE"]=="term_search")&&($_SESSION["CURRENT"]!==false)) {
		$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"]["page_search"]=$page;
		$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["PAGE"]=($page+1);
		$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY_START"].", page ".($page+1);	
	}
}
$ts=new term_search("user_input","f_user_input",$n_per_page,$base_query,"term_show.php","term_search.php", 0, $id_thes);

echo $ts->show_list_of_terms();

echo $footer;
?>
