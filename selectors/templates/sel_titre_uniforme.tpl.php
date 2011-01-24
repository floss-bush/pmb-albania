<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_titre_uniforme.tpl.php,v 1.4 2010-12-15 13:37:03 arenou Exp $

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
	<label for='titre_select_titre_uniforme' class='etiquette'>".$msg["aut_menu_titre_uniforme"]."</label>
	</div>	
<div class='row'>
";

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
if ($dyn==2) { // Pour les liens entre autorités
	$jscript = "
	<script type='text/javascript'>
	<!--
	function set_parent(f_caller, id_value, libelle_value)
	{	
		w=window;
		n_aut_link=w.opener.document.forms[f_caller].elements['max_aut_link'].value;
		flag = 1;	
		//Vérification que l'autorité n'est pas déjà sélectionnée
		for (i=0; i<n_aut_link; i++) {
			if (w.opener.document.getElementById('f_aut_link_id'+i).value==id_value && w.opener.document.getElementById('f_aut_link_table'+i).value==$param1) {
				alert('".$msg["term_already_in_use"]."');
				flag = 0;
				break;
			}
		}	
		if (flag) {
			for (i=0; i<n_aut_link; i++) {
				if ((w.opener.document.getElementById('f_aut_link_id'+i).value==0)||(w.opener.document.getElementById('f_aut_link_id'+i).value=='')) break;
			}	
			if (i==n_aut_link) w.opener.add_aut_link();
			
			var selObj = w.opener.document.getElementById('f_aut_link_table_list');
			var selIndex=selObj.selectedIndex;
			w.opener.document.getElementById('f_aut_link_table'+i).value= selObj.options[selIndex].value;
			
			w.opener.document.getElementById('f_aut_link_id'+i).value = id_value;
			w.opener.document.getElementById('f_aut_link_libelle'+i).value = reverse_html_entities('['+selObj.options[selIndex].text+']'+libelle_value);		
		}	
	}
	-->
	</script>
	";
}elseif ($dyn!=1) {
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(f_caller, id_value, libelle_value,callback)
{
	window.opener.document.forms[f_caller].elements['$param1'].value = id_value;
	window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(libelle_value);
	if(callback)
		window.opener[callback]('$infield');	
	window.close();
}
-->
</script>
";
} else {
	$jscript = "
<script type='text/javascript'>
<!--
function set_parent(f_caller, id_value, libelle_value)
{
	window.opener.document.getElementById('$param1').value = id_value;
	window.opener.document.getElementById('$param2').value = reverse_html_entities(libelle_value);
	window.close();
}
-->
</script>";
}

//-------------------------------------------
//	$sel_search_form : module de recherche
//-------------------------------------------
$sel_search_form ="
<form name='search_form' method='post' action='$base_url'>
<input type='text' name='f_user_input' value=\"!!deb_rech!!\">&nbsp;
<input type='submit' class='bouton_small' value='$msg[142]' >&nbsp;!!bouton_ajouter!!<br />
</form>
<script type='text/javascript'>
<!--
	document.forms['search_form'].elements['f_user_input'].focus();
-->
</script>
";

// ------------------------------------------
// 	$author_form : form saisie éditeur
// ------------------------------------------
$titre_uniforme_form = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.name.value.length == 0)
			{
				return false;
			}
		return true;
	}
-->

</script>
<form name='saisie_titre_uniforme' method='post' action=\"$base_url&action=update\">
<!-- ajouter un titre uniforme -->
<h3>".$msg["aut_titre_uniforme_ajouter"]."</h3>
<div class='form-contenu'>
	<!--	nom	-->
	<div class='row'>
		<label class='etiquette' for='form_name'>".$msg["aut_titre_uniforme_form_nom"]."</label>
	</div>
	<div class='row'>
		<input type='text' class='saisie-50em' name='name' value='' />
	</div>

</div>	
<div class='row'>
	<input type='button' class='bouton_small' value='$msg[76]' onClick=\"document.location='$base_url&what=titre_uniforme';\">
	<input type='submit' value='$msg[77]' class='bouton_small' onClick=\"return test_form(this.form)\">
	</div>
</form>
<script type='text/javascript'>
	document.forms['saisie_titre_uniforme'].elements['name'].focus();
</script>
";

//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";
