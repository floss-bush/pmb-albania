<?php

// +-------------------------------------------------+
// | PMB                                                                      |
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: tables_form.tpl.php,v 1.7 2009-12-14 16:36:00 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$form="
<script>

//Vérification de la saisie du formulaire
function checkForm()
{
	f=document.sauv_tables;
	if (f.act.value!='cancel')
	{
		if (f.sauv_tables_nom.value=='') {
			alert(\"".$msg['sauv_tables_valid_form_error_name']."\");
			return false;
		}
		
	}
	return true;
}

</script>

<!-- Formulaire -->
<script type='text/javascript'>

function check_all(the_form,the_objet,do_check){

	var elts = document.forms[the_form].elements[the_objet+'[]'] ;
	var elts_cnt  = (typeof(elts.length) != 'undefined')
              ? elts.length
              : 0;

	if (elts_cnt) {
		for (var i = 0; i < elts_cnt; i++) {
			elts[i].checked = do_check;
		} 
	} else {
		elts.checked = do_check;
	}
	return true;
}
</script>
<form name='sauv_tables' action='admin.php?categ=sauvegarde&sub=tables' method='post' onSubmit='return checkForm();'>
<input type=\"hidden\" name=\"act\" value=\"show\">
<input type=\"hidden\" name=\"sauv_table_id\" value=\"!!sauv_table_id!!\">
<input type=\"hidden\" name=\"first\" value=\"1\">
<table class=\"nobrd\">
<th class=\"brd\" colspan=3><center>!!quel_table!!: ".$msg['sauv_tables_form_prop_general']."</center></th>

<!--
	<tr><td colspan=3><b>".$msg['sauv_tables_form_prop_general']."</b></td></tr>
-->
<tr><td  class=\"nobrd\" colspan=3>&nbsp;</td></tr>
<tr>
	<td class=\"nobrd\">".$msg['sauv_tables_form_nom']."</td>
	<td class=\"nobrd\"><input type=\"text\"  name=\"sauv_table_nom\" value=\"!!sauv_table_nom!!\" class=\"saisie-simple\"></td>	
	<td class=\"nobrd\" align=\"right\"><input type=\"button\" class=\"bouton\" value=\"".$msg['tout_cocher_checkbox']."\" onclick=\"check_all('sauv_tables','sauv_table_tables',true);\" >
	<input type=\"button\" class=\"bouton\" value=\"".$msg['tout_decocher_checkbox']."\" onclick=\"check_all('sauv_tables','sauv_table_tables',false);\"  ></td>
</tr>
<tr><td class=\"nobrd\" colspan=3><b>\"".$msg['sauv_tables_form_tables']."\"</b></td></tr>
<tr><td class=\"nobrd\" colspan=3>!!tables_list!!</td></tr>
</table>
<center>
<!-- Boutons de soumission -->
<div class=\"left\">
	<input type=\"submit\" value=\"".$msg['sauv_annuler']."\" onClick=\"this.form.act.value='cancel'\" class=\"bouton\">
	<input type=\"submit\" value=\"".$msg['sauv_enregistrer']."\" onClick=\"this.form.act.value='update'\" class=\"bouton\">&nbsp;
	</div>
<div class=\"right\">
	!!delete!!
	</div>
<div class=\"row\"></div>
</center></form>";
?>