<?php
// +-------------------------------------------------+
// Â© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search_persopac.tpl.php,v 1.5 2011-01-27 10:26:25 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//*******************************************************************
// Définition des templates pour les listes en edition
//*******************************************************************
$tpl_search_persopac_liste_tableau = "

<hr />
<h3>".$msg["search_persopac_list"]."</h3>

	<div class='row'>
		<table>
		<tr>
			<th>".$msg["search_persopac_table_preflink"]."</th>
			<th>".$msg["search_persopac_table_name"]."</th>
			<th>".$msg["search_persopac_table_shortname"]."</th>
			<th>".$msg["search_persopac_table_humanquery"]."</th>
			<th>".$msg["search_persopac_table_edit"]."</th>
		</tr>
		!!lignes_tableau!!
		</table>
	</div>		
<hr />	
<!--	Bouton Ajouter	-->
<div class='row'>
	<input class='bouton' value='".$msg["search_persopac_add"]."' type='button'  onClick=\"document.location='./admin.php?categ=opac&sub=search_persopac&section=liste&action=add'\" >
</div>
";

$tpl_search_persopac_liste_tableau_ligne = "
<tr class='!!pair_impair!!' '!!tr_surbrillance!!' >
	<td !!td_javascript!! >!!directlink!!</td>
	<td !!td_javascript!! >!!name!!</td>
	<td !!td_javascript!! >!!shortname!!</td>
	<td !!td_javascript!! >!!human!!</td>	
	<td><input class='bouton_small' value='".$msg["search_persopac_modifier"]."' type='button'  onClick=\"document.location='./admin.php?categ=opac&sub=search_persopac&section=liste&action=form&id=!!id!!'\" ></td>
</tr>
";

$tpl_search_persopac_form = jscript_unload_question()."
<script type='text/javascript'>

function test_form(form) {
	if(form.name.value.length == 0)	{
		alert(\"".$msg["search_persopac_form_name_empty"]."\");
		return false;
	}
	unload_off();	
	return true;
}

function confirm_delete() {
    result = confirm(\"${msg[confirm_suppr]}\");
    if(result) {
        unload_off();
        document.location='./admin.php?categ=opac&sub=search_persopac&section=liste&action=delete&id=!!id!!';
	} else
        document.forms['search_persopac_form'].elements['name'].focus();
}
function check_link(id) {
	w=window.open(document.getElementById(id).value);
	w.focus();
}
</script>


<form class='form-$current_module' name='search_persopac_form' method='post' action='./admin.php?categ=opac&sub=search_persopac&section=liste&action=save'>
	<h3>!!libelle!!</h3>
	<div class='form-contenu'>
		<!--	nom	-->
		!!name!!
		
		<!--	short nom	-->
		!!shortname!!
		
		<div class='row'>
			<input value='1' name='directlink' !!directlink!! type='checkbox'>
			<label for='directlink' class='etiquette'>".$msg["search_persopac_form_direct_search"]."</label>  
		</div>	
		<div class='row'>
			<input value='1' name='limitsearch' !!limitsearch!! type='checkbox'>
			<label for='limitsearch' class='etiquette'>".$msg["search_perso_form_limitsearch"]."</label>  
		</div>
		<div class='row'>
				!!categorie!!
		</div>				
	</div>
	<input type='hidden' name='query' value='!!query!!' />
	<input type='hidden' name='id' value='!!id!!' />
	<input type='hidden' name='human' value='!!human!!' />
<!--	Boutons	-->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='".$msg["search_persopac_form_annuler"]."' !!annul!! />
		<input type='button' value='".$msg["search_persopac_form_save"]."' class='bouton' id='btsubmit' onClick=\"if (test_form(this.form)) this.form.submit();\" />
		</div>
	<div class='right'>
		!!delete!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>
	document.forms['search_persopac_form'].elements['name'].focus();
</script>
";
			
			
			/*
		<div class='row'>
			<label class='etiquette' for='name'>".$msg["search_persopac_form_name"]."</label>
		</div>* 
		<div class='row'>
			<input type='text' class='saisie-80em' id='form_nom' name='name' value=\"!!name!!\" />
		</div>	
		
		*/
?>
