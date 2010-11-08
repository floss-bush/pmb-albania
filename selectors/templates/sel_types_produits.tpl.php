<?php
// +-------------------------------------------------+

// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_types_produits.tpl.php,v 1.5 2007-03-14 16:22:51 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// templates du sélecteur adresses

//--------------------------------------------
//	$nb_per_page : nombre de lignes par page
//--------------------------------------------
// nombre de références par pages
$nb_per_page = $nb_per_page_select;

//-------------------------------------------
//	$sel_header : header
//-------------------------------------------
$sel_header = "
<div class='row'>
	<label class='etiquette'>".htmlentities($msg['acquisition_sel_type'], ENT_QUOTES, $charset)."</label>
</div>
<div class='row'>&nbsp;</div>
<div class='row'>
";

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(f_caller, typ, lib_typ, rem, tva)
{
	window.opener.document.forms[f_caller].elements['$param1'].value = typ;
	window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(lib_typ);
	window.opener.document.forms[f_caller].elements['$param3'].value = reverse_html_entities(rem);";
if ($acquisition_gestion_tva) {
	$jscript.= "window.opener.document.forms[f_caller].elements['$param4'].value = reverse_html_entities(tva);";
}
$jscript.= "window.close();
}
-->
</script>
";

//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";
