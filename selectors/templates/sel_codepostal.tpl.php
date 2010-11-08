<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_codepostal.tpl.php,v 1.6 2009-05-16 10:52:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// templates du sélecteur auteur

//--------------------------------------------
//	$nb_per_page : nombre de lignes par page
//--------------------------------------------
// nombre de références par pages
if ($nb_per_page_a_select != "") 
	$nb_per_page = $nb_per_page_a_select ;
	else $nb_per_page = 10;

//-------------------------------------------
//	$sel_header : header
//-------------------------------------------
$sel_header = "
<div class='row'>
	<label for='titre_select_empr' class='etiquette'>".$msg['select_ville']."</label>
	</div>
<div class='row'>
";

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(f_caller, libelle_value, cp_value)
{
	window.opener.document.forms[f_caller].elements['$param1'].value = reverse_html_entities(libelle_value);
	window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(cp_value);
	window.close();
}
-->
</script>
";

//-------------------------------------------
//	$sel_search_form : module de recherche
//-------------------------------------------
$sel_search_form ="
<script type='text/javascript'>
<!--
function test_form(form)
{
	if(form.f_user_input.value.length == 0)
	{
		return false;
	}
	return true;
}
-->
</script>
<form name='search_form' method='post' action='$base_url'>
<input type='text' name='f_user_input' value=\"!!deb_rech!!\">
&nbsp;
<input type='submit' class='bouton_small' value='$msg[142]' onclick='return test_form(this.form)'>
</form>
<script type='text/javascript'>
<!--
	document.forms['search_form'].elements['f_user_input'].focus();
-->
</script>
<hr />
";

//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";
