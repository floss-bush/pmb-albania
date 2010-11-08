<?php
// +-------------------------------------------------+

// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_fournisseur.tpl.php,v 1.12 2008-12-11 16:08:01 dbellamy Exp $


if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// templates du sélecteur fournisseurs

//--------------------------------------------
//	$nb_per_page : nombre de lignes par page
//--------------------------------------------
// nombre de références par pages
if ($nb_per_page_s_select != "") 
	$nb_per_page = $nb_per_page_s_select ;
	else $nb_per_page = 10;

//-------------------------------------------
//	$sel_header : header
//-------------------------------------------
$sel_header = "
<div class='row'>
	<label for='titre_select_fourn' class='etiquette'>".htmlentities($msg['acquisition_sel_fou'], ENT_QUOTES, $charset)."</label>
	</div>
<div class='row'>
";

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(f_caller, id_value, raison, adresse)
{
	//var temp = window.opener.document.forms[f_caller].elements['$param1'].value;
	window.opener.document.forms[f_caller].elements['$param1'].value = id_value;
	window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(raison);
	try {
		window.opener.document.forms[f_caller].elements['$param3'].value = reverse_html_entities(adresse);
		} catch (err){}
	window.close();
}
-->
</script>
";

//-------------------------------------------
//	$sel_search_form : module de recherche
//-------------------------------------------
$sel_search_form ="
<form name='search_form' method='post' action='$base_url'>
<input type='text' name='f_user_input' value=\"!!deb_rech!!\">
&nbsp;
<input type='submit' class='bouton_small' value='$msg[142]' />
</form>
<script type='text/javascript'>
<!--
	document.forms['search_form'].elements['f_user_input'].focus();
-->
</script>
";


//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";
