<?php
// +-------------------------------------------------+

// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_rubriques.tpl.php,v 1.6 2007-03-14 16:22:51 dbellamy Exp $

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
	<label class='etiquette'>".htmlentities($msg['acquisition_sel_rub'], ENT_QUOTES, $charset)."</label>
</div>
<div class='row'>&nbsp;</div>
<div class='row'></div>
";

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(f_caller, rub, lib_rub)
{
	window.opener.document.forms[f_caller].elements['$param1'].value = rub;
	window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(lib_rub);
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
