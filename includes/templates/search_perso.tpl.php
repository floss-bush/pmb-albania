<?php
// +-------------------------------------------------+
// Â© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search_perso.tpl.php,v 1.2 2009-05-16 11:19:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//*******************************************************************
// Définition des templates pour les listes en edition
//*******************************************************************
$tpl_search_perso_liste_tableau = "
<h1>".$msg["search_perso_title"]."</h1>
<div class='hmenu'>
	<span><a href='./catalog.php?categ=search_perso'>".$msg["search_perso_list_title"]."</a></span>!!preflink!!
</div>
<hr />
<h3>".$msg["search_perso_list"]."</h3>

	<div class='row'>
		<table>
		<tr>
			<th>".$msg["search_perso_table_preflink"]."</th>
			<th>".$msg["search_perso_table_name"]."</th>
			<th>".$msg["search_perso_table_shortname"]."</th>
			<th>".$msg["search_perso_table_humanquery"]."</th>
			<th>".$msg["search_perso_table_edit"]."</th>
		</tr>
		!!lignes_tableau!!
		</table>
	</div>		
<hr />	
<!--	Bouton Ajouter	-->
<div class='row'>
	<input class='bouton' value='".$msg["search_perso_add"]."' type='button'  onClick=\"document.location='./catalog.php?categ=search&mode=6&search_perso=add'\" >
</div>
";

$tpl_search_perso_liste_tableau_ligne = "
<tr class='!!pair_impair!!' '!!tr_surbrillance!!'  style='cursor: pointer'>
	<td !!td_javascript!! >!!directlink!!</td>
	<td !!td_javascript!! >!!name!!</td>
	<td !!td_javascript!! >!!shortname!!</td>
	<td !!td_javascript!! >!!human!!</td>	
	<td><input class='bouton_small' value='".$msg["search_perso_modifier"]."' type='button'  onClick=\"document.location='./catalog.php?categ=search_perso&sub=form&id=!!id!!'\" ></td>
</tr>
";

$tpl_search_perso_form = jscript_unload_question()."
<script type='text/javascript'>

function test_form(form) {
	if(form.name.value.length == 0)	{
		alert(\"".$msg["search_perso_form_name_empty"]."\");
		return false;
	}
	unload_off();	
	return true;
}

function confirm_delete() {
    result = confirm(\"${msg[confirm_suppr]}\");
    if(result) {
        unload_off();
        document.location='./catalog.php?categ=search_perso&sub=delete&id=!!id!!';
	} else
        document.forms['search_perso_form'].elements['name'].focus();
}
function check_link(id) {
	w=window.open(document.getElementById(id).value);
	w.focus();
}
</script>

<h1>".$msg["search_perso_form_title"]."</h1>
<form class='form-$current_module' name='search_perso_form' method='post' action='./catalog.php?categ=search_perso&sub=save'>
	<h3>!!libelle!!</h3>
	<div class='form-contenu'>
		<!--	nom	-->
		<div class='row'>
			<label class='etiquette' for='name'>".$msg["search_perso_form_name"]."</label>
		</div>
		<div class='row'>
			<input type='text' class='saisie-80em' id='form_nom' name='name' value=\"!!name!!\" />
		</div>	
		
		<!--	short nom	-->
		<div class='row'>
			<label class='etiquette' for='shortname'>".$msg["search_perso_form_shortname"]."</label>
		</div>
		<div class='row'>
			<input type='text' class='saisie-80em' id='shortname' name='shortname' value=\"!!shortname!!\" />
		</div>			
		
		<div class='row'>
			<input value='1' name='directlink' !!directlink!! type='checkbox'>
			<label for='directlink' class='etiquette'>".$msg["search_perso_form_direct_search"]."</label>  
		</div>	
	</div>
	<input type='hidden' name='query' value='!!query!!' />
	<input type='hidden' name='id' value='!!id!!' />
	<input type='hidden' name='human' value='!!human!!' />
<!--	Boutons	-->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='".$msg["search_perso_form_annuler"]."' !!annul!! />
		<input type='button' value='".$msg["search_perso_form_save"]."' class='bouton' id='btsubmit' onClick=\"if (test_form(this.form)) this.form.submit();\" />
		</div>
	<div class='right'>
		!!delete!!
		</div>
	</div>
<div class='row'></div>

</form>
<script type='text/javascript'>
	document.forms['search_perso_form'].elements['name'].focus();
</script>
";
?>
