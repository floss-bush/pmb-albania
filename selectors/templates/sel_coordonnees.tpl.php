<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_coordonnees.tpl.php,v 1.4 2007-07-31 16:23:08 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// templates du sélecteur adresses

//--------------------------------------------
//	$nb_per_page : nombre de lignes par page
//--------------------------------------------
// nombre de références par pages
$nb_per_page = 5;

//-------------------------------------------
//	$sel_header : header
//-------------------------------------------
$sel_header = "
<div class='row'>
	<label class='etiquette'>".htmlentities($msg['acquisition_sel_coord'], ENT_QUOTES, $charset)."</label>
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
function set_parent(f_caller, id_adr, adresse)
{
	window.opener.document.forms[f_caller].elements['$param1'].value = id_adr;
	window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(adresse);
	window.close();
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
