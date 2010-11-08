<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.18 2009-05-16 11:12:02 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de switch recherche sujets

// inclusions principales
require_once("$class_path/thesaurus.class.php");


//recuperation du thesaurus session 
if(!$id_thes) {
$id_thes = thesaurus::getSessionThesaurusId();
} else {
	thesaurus::setSessionThesaurusId($id_thes);
}


$search_form_term = "
<form class='form-$current_module' name='term_search_form' method='post' action='./catalog.php?categ=search&mode=5'>
<h3>".$msg["search_by_terms"]."</h3>
	<div class='form-contenu'>
		<div class='row'>
			<div class='colonne'>
				<!-- sel_thesaurus -->		
				<input type='text' class='saisie-50em' name='search_term' value='".htmlentities(stripslashes($search_term),ENT_QUOTES,$charset)."' />
			</div>
		</div>
		<div class='row'>
			<span class='saisie-contenu'>
				$msg[155]&nbsp;<a class='aide' title='$msg[1900]$msg[1901]$msg[1902]' href='./help.php?whatis=regex' onclick='aide_regex();return false;'>$msg[1550]</a>
				</span>
			</div>
		</div>
	<!--	Bouton Rechercher -->
	<div class='row'>
		<input type='submit' class='bouton' value='$msg[142]' />
		</div>
	</form>
	<script type='text/javascript'>
		document.forms['term_search_form'].elements['search_term'].focus();
		function aide_regex()
			{
				var fenetreAide;
				fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, 'scrollbars=yes, resizable=yes');
			}
		</script>
	<br />
	";
	
	
//affichage du selectionneur de thesaurus et du lien vers les thésaurus
$liste_thesaurus = thesaurus::getThesaurusList();
$sel_thesaurus = '';
$lien_thesaurus = '';

if ($thesaurus_mode_pmb != 0) {	 //la liste des thesaurus n'est pas affichée en mode monothesaurus
	$sel_thesaurus = "<select class='saisie-30em' id='id_thes' name='id_thes' ";
	$sel_thesaurus.= "onchange = \"document.location = './catalog.php?categ=search&mode=5&id_thes='+document.getElementById('id_thes').value; \">" ;
	foreach($liste_thesaurus as $id_thesaurus=>$libelle_thesaurus) {
		$sel_thesaurus.= "<option value='".$id_thesaurus."' "; ;
		if ($id_thesaurus == $id_thes) $sel_thesaurus.= " selected";
		$sel_thesaurus.= ">".htmlentities($libelle_thesaurus,ENT_QUOTES,$charset)."</option>";
	}
	$sel_thesaurus.= "<option value=-1 ";
	if ($id_thes == -1) $sel_thesaurus.= "selected ";
	$sel_thesaurus.= ">".htmlentities(addslashes($msg['thes_all']),ENT_QUOTES,$charset)."</option>";
	$sel_thesaurus.= "</select>&nbsp;";

	$lien_thesaurus = "<a href='./autorites.php?categ=categories&sub=thes'>".$msg[thes_lien]."</a>";

}	
$search_form_term=str_replace("<!-- sel_thesaurus -->",$sel_thesaurus,$search_form_term);
$search_form_term=str_replace("<!-- lien_thesaurus -->",$lien_thesaurus,$search_form_term);


//affichage du choix de langue pour la recherche
//$sel_langue = '';
//$sel_langue = "<div class='row'>";
//$sel_langue.= "<input type='checkbox' name='lg_search' id='lg_search' value='1' />&nbsp;".htmlentities(addslashes($msg['thes_sel_langue']),ENT_QUOTES,$charset);
//$sel_langue.= "</div><br />";
//$search_form_term=str_replace("<!-- sel_langue -->",$sel_langue,$search_form_term);


echo $search_form_term;


//Nouvelle recherche
if (($search_term)&&(!$recalled)) {
	$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["URI"]="./catalog.php?categ=search&mode=5&id_thes=".$id_thes;
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["SEARCH_TYPE"]="term_search";
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"]=$_POST;
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"]["recalled"]=1;
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["GET"]=$_GET;
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]="<b>".$msg["histo_term"]."</b> ".htmlentities(stripslashes($search_term),ENT_QUOTES,$charset);
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY_START"]="<b>".$msg["histo_term"]."</b> ".htmlentities(stripslashes($search_term),ENT_QUOTES,$charset);
	$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=$msg["search_by_terms"];
} else if ((!$search_term)&&(!$recalled)&&($_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["SEARCH_TYPE"]=="term_search")&&($_SESSION["CURRENT"]!==false)) {  
		$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["SEARCH_TYPE"]="";
	} else if (($recalled)&&($_SESSION["CURRENT"]!==false)) {
		$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["SEARCH_TYPE"]="term_search";
	}
echo "
<a name='search_frame'/>
<div class='row'>
	<iframe name='term_search' src='".$base_path."/catalog/notices/search/terms/term_browse.php?search_term=".rawurlencode(stripslashes($search_term))."&page_search=$page_search&term_click=".rawurlencode(stripslashes($term_click))."&id_thes=".$id_thes."'width=100% height=600></iframe>
</div>";
