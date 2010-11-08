<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.11 2009-05-16 11:12:04 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de switch recherche sujets

// inclusions principales
require_once("$class_path/thesaurus.class.php");


//recuperation du thesaurus session 
if(!$id_thes) {
	$id_thes = thesaurus::getSessionThesaurusId();
}

$search_form_term = "
<form class='form-$current_module' name='term_search_form' method='post' action='./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=1&unq=$unq&mode=5#search_frame'>
<h3>".$msg["search_by_terms"]."</h3>
	<div class='form-contenu'>
		<div class='row'>
			<div class='colonne'>
				<!-- sel_thesaurus -->		
				<input type='text' class='saisie-50em' name='search_term' value='".htmlentities(stripslashes($search_term),ENT_QUOTES,$charset)."'>
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
				var prop = 'scrollbars=yes, resizable=yes';
				fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, prop);
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
	$sel_thesaurus.= "onchange = \"document.location = './circ.php?categ=resa&mode=5&id_empr=$id_empr&groupID=$groupID&unq=$unq&id_thes='+document.getElementById('id_thes').value; \">" ;
	foreach($liste_thesaurus as $id_thesaurus=>$libelle_thesaurus) {
		$sel_thesaurus.= "<option value='".$id_thesaurus."' "; ;
		if ($id_thesaurus == $id_thes) $sel_thesaurus.= " selected";
		$sel_thesaurus.= ">".htmlentities($libelle_thesaurus,ENT_QUOTES,$charset)."</option>";
	}
	$sel_thesaurus.= "<option value=-1 ";
	if ($id_thes == -1) $sel_thesaurus.= "selected ";
	$sel_thesaurus.= ">".htmlentities($msg['thes_all'],ENT_QUOTES,$charset)."</option>";
	$sel_thesaurus.= "</select>&nbsp;";

	$lien_thesaurus = "<a href='./autorites.php?categ=categories&sub=thes'>".$msg[thes_lien]."</a>";

}	
$search_form_term=str_replace("<!-- sel_thesaurus -->",$sel_thesaurus,$search_form_term);
$search_form_term=str_replace("<!-- lien_thesaurus -->",$lien_thesaurus,$search_form_term);


//affichage du choix de langue pour la recherche
//$sel_langue = '';
//$sel_langue = "<div class='row'>";
//$sel_langue.= "<input type='checkbox' name='lg_search' id='lg_search' value='1' />&nbsp;".htmlentities($msg['thes_sel_langue'],ENT_QUOTES,$charset);
//$sel_langue.= "</div><br />";
//$search_form_term=str_replace("<!-- sel_langue -->",$sel_langue,$search_form_term);


echo $search_form_term;


echo "
<a name='search_frame'/>
<div class='row'>
	<iframe name='term_search' src='".$base_path."/circ/resa/terms/term_browse.php?id_empr=$id_empr&groupID=$groupID&mode=1&unq=$unq&search_term=".rawurlencode(stripslashes($search_term))."&id_thes=".$id_thes."' width=100% height=600>
</div>";
