<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_author.tpl.php,v 1.17 2010-06-16 12:14:36 ngantier Exp $

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
	<label id='id_header' for='titre_select_author' class='etiquette'>!!select_titre!!</label>
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
function set_parent(f_caller, id_value, libelle_value)
{
	window.opener.document.forms[f_caller].elements['$param1'].value = id_value;
	window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(libelle_value);
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
<select id='id_type_autorite' name='id_type_autorite' onchange=\"document.location='$base_url&id_type_autorite='+this.value\">
	<option value='7' !!sel_all!!>".$msg["autorites_auteurs_all"]."</option>
	<option value='70' !!sel_pp!!>$msg[203]</option>
	<option value='71' !!sel_coll!!>$msg[204]</option>
	<option value='72' !!sel_con!! >".$msg["congres_libelle"]."</option>
</select>
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
$author_form = "
<script type='text/javascript'>

function test_form(form) {
	if(form.author_name.value.length == 0) {
		return false;
	}
	return true;
}

function display_part(type)
{
	
	var collectivite_part = document.getElementById('collectivite_part');
	if(type == '70') {
		collectivite_part.style.display = 'none';
	} else {		
		collectivite_part.style.display = 'inline';		
	} 
	
	var label_header = document.getElementById('id_header');
	if(type == '70') {
		label_header.innerHTML='".addslashes($msg[214])."';
	} else if(type == '71'){
		label_header.innerHTML='".addslashes($msg["aut_select_coll"])."';
	} else if(type == '72'){
		label_header.innerHTML='".addslashes($msg["aut_select_congres"])."';
	}
	
	var label_titre = document.getElementById('titre_ajout');
	if(type == '70') {
		label_titre.innerHTML='".addslashes($msg[207])."';
	} else if(type == '71'){
		label_titre.innerHTML='".addslashes($msg["aut_ajout_collectivite"])."';
	} else if(type == '72'){
		label_titre.innerHTML='".addslashes($msg["aut_ajout_congres"])."';
	}
	if(type == '71') 
		document.getElementById('author_nom').setAttribute('completion', 'collectivite_name');
	else if(type == '72')  
		document.getElementById('author_nom').setAttribute('completion', 'congres_name');
	else	
		document.getElementById('author_nom').setAttribute('completion', '');		
} 
</script>
<form name='saisie_auteur' method='post' action=\"$base_url&action=update\">
<!-- ajouter un auteur -->
<h3><label id='titre_ajout'>!!titre_ajout!!</label></h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='author_type'>$msg[205]</label>
		</div>
	<div class='row'>
		<select id='author_type' name='author_type' onchange='display_part(this.value)'>
			<option value='70' !!sel_pp!!>$msg[203]</option>
			<option value='71' !!sel_coll!!>$msg[204]</option>
			<option value='72' !!sel_con!!>".$msg["congres_libelle"]."</option>
			</select>
		</div>
	<div class='row'>
		<label class='etiquette' for='author_nom'>$msg[201]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-30em' id='author_nom' name='author_name' autfield='rien' completion='!!completion_name!!' value=''>
		<input id='rien' name='rien' value='' type='hidden'>
		</div>
	<div class='row'>
		<label class='etiquette' for='author_rejete'>$msg[202]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-30em' id='author_rejete' name='author_rejete' value=''>
		</div>
	<div class='row'>
		<label class='etiquette' for='date'>$msg[713]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-30em' id='date' name='date' value=''>
		</div>
	<div id='collectivite_part' style='!!display!!'>		
		<!--	lieu	-->
		<div class='row'>
			<label class='etiquette' for='form_lieu'>".$msg["congres_lieu_libelle"]."</label>
			</div>
		<div class='row'>
			<input type='text' class='saisie-30em' id='form_lieu' name='lieu' value=''>
		</div>
		
		<!--	ville	-->
		<div class='row'>
			<label class='etiquette' for='form_ville'>".$msg["congres_ville_libelle"]."</label>
		</div>
		<div class='row'>
			<input type='text' class='saisie-30em' id='form_ville' name='ville' value=\"\" />
		</div>	      
	
		<!--	pays	-->
		<div class='row'>
			<label class='etiquette' for='form_pays'>".$msg["congres_pays_libelle"]."</label>
		</div>
		<div class='row'>
			<input type='text' class='saisie-30em' id='form_pays' name='pays' value=\"\"  />
		</div>       
	
		<!--	subdivision	-->
		<div class='row'>
			<label class='etiquette' for='form_subdivision'>".$msg["congres_subdivision_libelle"]."</label>
		</div>
		<div class='row'>
			<input type='text' class='saisie-30em' id='form_subdivision' name='subdivision' value=\"\" />
		</div>
	
		<!--	numero	-->
		<div class='row'>
			<label class='etiquette' for='form_numero'>".$msg["congres_numero_libelle"]."</label>
		</div>
		<div class='row'>
			<input type='text' class='saisie-30em' id='form_numero' name='numero' value=\"\"  />
		</div>	
	</div>		
	</div>	
<div class='row'>
	<input type='button' class='bouton_small' value='$msg[76]' onClick=\"document.location='$base_url&what=auteur';\">
	<input type='submit' value='$msg[77]' class='bouton_small' onClick=\"return test_form(this.form)\">
	</div>
</form>
<script type='text/javascript'>
	document.forms['saisie_auteur'].elements['author_name'].focus();
</script>
";

//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";
