<?php
// +-------------------------------------------------+

// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_entites.tpl.php,v 1.4 2007-07-31 16:23:08 touraine37 Exp $


if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// templates du sélecteur titre de série

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
	<label for='titre_select_entites' class='etiquette'>$msg[344]</label>
	</div>
<div class='row'>
";

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(f_caller, id_value, libelle_value)
{
	window.opener.document.forms[f_caller].elements['$param1'].value = id_value;
	window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(libelle_value);
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
!!bouton_ajouter!!
";

// ------------------------------------------
// 	$serie_form : form saisie titre de série
// ------------------------------------------

$serie_form = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.serie_nom.value.length == 0)
			{
				alert(\"$msg[338]\");
				return false;
			}
		return true;
	}
-->
</script>
<form name='saisie_serie' method='post' action=\"$base_url&action=update\">
<!-- ajouter un titre de  série -->
<h3>$msg[339]</h3>
<div class='form-contenu'>
	<!-- nom -->
	<div class='row'>
		<label class='etiquette'>$msg[233]</label>
		</div>
	<div class='row'>
		<input type='text' size='40' name='serie_nom' value='' />
		</div>
	</div>
<div class='row'>
	<input type='button' class='bouton_small' value='$msg[76]' onClick=\"document.location='$base_url&what=serie';\">
	<input type='submit' value='$msg[77]' class='bouton_small' onClick=\"return test_form(this.form)\">
	</div>
</form>
<script type='text/javascript'>
	document.forms['saisie_serie'].elements['serie_nom'].focus();
</script>
";

//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";
