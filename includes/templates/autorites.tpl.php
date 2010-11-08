<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: autorites.tpl.php,v 1.28 2009-12-29 16:52:27 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// $autorites_menu : menu page autorités
$autorites_menu = "
<div id='menu'>
<h3 onclick='menuHide(this,event)'>$msg[132]</h3>
<ul>
	<li><a href='./autorites.php?categ=auteurs&sub=&id='>$msg[133]</a></li>";
if (SESSrights & THESAURUS_AUTH) $autorites_menu .= "<li><a href='./autorites.php?categ=categories&sub=&parent=0&id=0'>$msg[134]</a></li>";
$autorites_menu .= "	<li><a href='./autorites.php?categ=editeurs&sub=&id='>$msg[135]</a></li>
	<li><a href='./autorites.php?categ=collections&sub=&id='>$msg[136]</a></li>
	<li><a href='./autorites.php?categ=souscollections&sub=&id='>$msg[137]</a></li>
	<li><a href='./autorites.php?categ=series&sub=&id='>$msg[333]</a></li>";
if ($pmb_use_uniform_title) $autorites_menu .= "<li><a href='./autorites.php?categ=titres_uniformes&sub=&id='>".$msg["aut_menu_titre_uniforme"]."</a></li>";
$autorites_menu .= "<li><a href='./autorites.php?categ=indexint&sub=&id='>$msg[indexint_menu]</a></li>
</ul>";
if (SESSrights & THESAURUS_AUTH) $autorites_menu .= "
<h3 onclick='menuHide(this,event)'>$msg[semantique]</h3>
<ul>
	<li><a title='".$msg["word_syn"]."' href='./autorites.php?categ=semantique&sub=synonyms'>".$msg["word_syn"]."</a></li>
	<li><a title='".$msg["empty_words_libelle"]."' href='./autorites.php?categ=semantique&sub=empty_words'>".$msg["empty_words_libelle"]."</a></li>
</ul>";
$autorites_menu .= "</div>
";
//	----------------------------------

// $autorites_layout : layout page autorités
$autorites_layout = "
<div id='conteneur' class='$current_module'>
$autorites_menu
<div id='contenu'>
<!--<h1>$msg[132]</h1>-->
";


// $autorites_layout_end : layout page circulation (fin)
$autorites_layout_end = '
</div>
</div>
';

// $user_query : form de recherche
$user_query = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.user_input.value.length == 0)
			{
				alert(\"$msg[141]\");
				return false;
			}
		return true;
	}
	function aide_regex()
	{
		var fenetreAide;
		var prop = 'scrollbars=yes, resizable=yes';
		fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, prop);
	}
-->
</script>
<form class='form-$current_module' name='search' method='post' action='!!action!!'>
<h3>!!user_query_title!!</h3>
<div class='form-contenu'>
	<div class='row'>
		<div class='colonne'>
			<!-- sel_pclassement -->
			<!-- sel_thesaurus -->
			<!-- sel_autorites -->		
			<input type='text' class='saisie-50em' name='user_input' value='!!user_input!!'/>
		</div>
		<div class='right'></div>
		<div class='row'></div>
	</div>
</div>
<!-- sel_langue -->
";

if ($categ=="indexint") $user_query.="
	<div class='row'>
		<input type='radio' name='exact' id='exact1' value='1' checked/>
		<label class='etiquette' for='exact1'>&nbsp;".$msg["indexint_search_index"]."</label>&nbsp;
		<input type='radio' name='exact' id='exact0' value='0'/>
		<label for='exact0' class='etiquette'>&nbsp;".$msg["indexint_search_comment"]."</label>
	</div><br />";
$user_query.="	
<div class='row'>
	<div class='left'>
		<input type='submit' class='bouton' value='$msg[142]' onClick=\"return test_form(this.form)\" />
		<input class='bouton' type='button' value='!!add_auth_msg!!' onClick=\"document.location='!!add_auth_act!!'\" />
	</div>
	<div class='right'>
		<!-- lien_classement --><!-- lien_derniers --><!-- lien_thesaurus --><!-- imprimer_thesaurus -->
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>
	document.forms['search'].elements['user_input'].focus();
</script>
<div class='row'></div>
";
?>