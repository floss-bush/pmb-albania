<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: category.tpl.php,v 1.23 2011-01-07 16:06:55 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// templates du sélecteur catégories

//-------------------------------------------
//	$sel_header : header
//-------------------------------------------
$sel_header = "
<div class='row'>
	<label for='titre_select_categ' class='etiquette'>$msg[323]</label>
	</div>	
<div class='row'>
";

//	----------------------------------
// $categ_browser : template du browser de catégories
//	----------------------------------
$categ_browser = "
<div class='row'>
	!!browser_top!!
	</div>	
<div class='row'>
	<div class='left'>!!browser_header!!</div>";
if (SESSrights & THESAURUS_AUTH) $categ_browser .= "<div class='right'>!!bt_ajouter!!</div>";
$categ_browser .= "</div>
<div class='row'>
		<table border='0'>
			!!browser_content!!
		</table>
</div>
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
		return true;
	}
	return true;
}
-->
</script>
<form name='search_form' method='post' action='$base_url'>

	!!sel_thesaurus!!

	<input type='text' name='f_user_input' value='!!f_user_input_value!!' />
	&nbsp;
	<input type='submit' class='bouton_small' value='$msg[142]' onclick='return test_form(this.form)' />
	<br />
	<input type='radio' value='hierarchy' name='search_type' !!h_checked!! onClick=\"this.form.submit()\" />
	&nbsp;".$msg["term_search_type_h"]."&nbsp;
	<input type='radio' value='term' name='search_type' !!t_checked!! onClick=\"this.form.submit()\" />
	&nbsp;".$msg["term_search_type_t"]."
	
</form>

<script type='text/javascript'>
<!--
	document.forms['search_form'].elements['f_user_input'].focus();
-->
</script>
";

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------

$jscript_ = "
<script type='text/javascript'>
<!--
function set_parent_w(f_caller, id_value, libelle_value,w,callback,id_thesaurus)
{
	
	dyn='$dyn';
	if(dyn==2) { // Pour les liens entre autorités
		n_aut_link=w.opener.document.forms[f_caller].elements['max_aut_link'].value;
		flag = 1;	
		//Vérification que l'autorité n'est pas déjà sélectionnée
		for (i=0; i<n_aut_link; i++) {
			if (w.opener.document.getElementById('f_aut_link_id'+i).value==id_value && w.opener.document.getElementById('f_aut_link_table'+i).value==$p1) {
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
	} else if (dyn) {
		n_categ=w.opener.document.forms[f_caller].elements['max_categ'].value;
		flag = 1;
	
		//Vérification que la catégorie n'est pas déjà sélectionnée
		for (i=0; i<n_categ; i++) {
			if (w.opener.document.getElementById('f_categ_id'+i).value==id_value) {
				alert('".$msg["term_already_in_use"]."');
				flag = 0;
				break;
			}
		}
	
		if (flag) {
			for (i=0; i<n_categ; i++) {
				if ((w.opener.document.getElementById('f_categ_id'+i).value==0)||(w.opener.document.getElementById('f_categ_id'+i).value=='')) break;
			}
	
			if (i==n_categ) w.opener.add_categ();
			w.opener.document.getElementById('f_categ_id'+i).value = id_value;
			w.opener.document.getElementById('f_categ'+i).value = reverse_html_entities(libelle_value);
		}
	} else {
		w.opener.document.forms[f_caller].elements['$p1'].value=id_value;
		w.opener.document.forms[f_caller].elements['$p2'].value=reverse_html_entities(libelle_value);
		var p1 = '$p1';
		var theselector = w.opener.document.getElementById(p1.replace('field','fieldvar').replace('_id','')+'[id_thesaurus][]');
		if(theselector){
			for (var i=1 ; i< theselector.options.length ; i++){
				if (theselector.options[i].value == id_thesaurus){
					theselector.options[i].selected = true;
					break;
				}
			}
		}
		if(callback)
			w.opener[callback]('$infield');
		//parent.parent.close();
	}
}
-->
</script>
";

$jscript = $jscript_."
<script type='text/javascript'>
<!--
function set_parent(f_caller, id_value, libelle_value,callback,id_thesaurus)
{
	set_parent_w(f_caller, id_value, libelle_value,parent,callback,id_thesaurus);
}
-->
</script>
";

$jscript_term = $jscript_."
<script type='text/javascript'>
<!--
function set_parent(f_caller, id_value, libelle_value,callback,id_thesaurus)
{
	set_parent_w(f_caller, id_value, libelle_value,parent.parent,callback,id_thesaurus);
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

//-------------------------------------------
//	$select_category_form : formulaire d'ajout
//-------------------------------------------
$select_category_form = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.category_libelle.value.length == 0)
			{
				alert(\"$msg[320]\");
				return false;
			}
		return true;
	}
	
-->
</script>
<form class='form-$current_module' id='categ_form' name='categ_form' method='post' action='!!action!!'>
<h3>!!form_title!!</h3>
<div class='form-contenu'>

<!--	libelle	-->
<div class='row'>
	<label class='etiquette' for='form_libelle'>$msg[103]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-30em' name='category_libelle' value=\"\" />
	</div>

<!--	Commentaire	-->
<div class='row'>
	<label for='form_comment' class='etiquette'>$msg[categ_commentaire]</label>
	</div>
<div class='row'>
	<textarea class='saisie-80em' id='category_comment' name='category_comment' rows='4' wrap='virtual'></textarea>
	</div>

<!--	categ_parent	-->
<div class='row'>
	<label class='etiquette' for='form_categparent'>$msg[categ_parent]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-50emr' name='category_parent' size='48' readonly value=\"!!parent_libelle!!\" />
	<input type='hidden' name='category_parent_id' value='!!parent_value!!' />
	<input type='hidden' name='parent' value='!!parent_value!!' />
	</div>

</div>

<!--	boutons	-->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton_small' value='$msg[76]' onClick=\"window.history.back()\" />
		<input type='submit' class='bouton_small' value='$msg[77]' onClick=\"return test_form(this.form)\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>
	document.forms['categ_form'].elements['category_libelle'].focus();
</script>
";
