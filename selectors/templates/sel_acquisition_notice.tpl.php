<?php
// +-------------------------------------------------+
// | PMB                                                                      |
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_acquisition_notice.tpl.php,v 1.11 2009-11-30 11:46:26 dbellamy Exp $


if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// templates du sélecteur notices

//--------------------------------------------
//	$nb_per_page : nombre de lignes par page
//--------------------------------------------
// nombre de références par pages
if ($nb_per_page_a_select != "") 
	$nb_per_page = $nb_per_page_a_select ;
	else $nb_per_page = 10;


//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(f_caller, id_value, code_value, libelle_value, prix_value, typ_lig, tva_value)
{
	window.opener.document.forms[f_caller].elements['$param6'].value = typ_lig;
	window.opener.document.forms[f_caller].elements['$param1'].value = id_value;
	window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(code_value);
	window.opener.document.forms[f_caller].elements['$param3'].value = reverse_html_entities(libelle_value);
	window.opener.document.forms[f_caller].elements['$param4'].value = reverse_html_entities(prix_value);
	if (typ_lig == '3') {
		try {
			window.opener.document.forms[f_caller].elements['$param7'].value = reverse_html_entities(tva_value);
		} catch(e){}
		window.opener.document.forms[f_caller].elements['$param8'].value = '0.00';
		window.opener.document.forms[f_caller].elements['$param9'].value = '0';
		window.opener.document.forms[f_caller].elements['$param10'].value = '';
	}
	window.opener.document.forms[f_caller].elements['$param5'].focus();
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
<hr />
";

//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";
