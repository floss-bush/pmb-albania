<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_simple.tpl.php,v 1.1 2009-09-24 13:18:58 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$liste_simple_form = "
<form class='form-".$current_module."' id='simple_list_form' name='simple_list_form' method='post' action=\"!!list_simple_action!!\">
<h3>!!form_title!!</h3>
<!--    Contenu du form    -->
<input type='hidden' name='act' id='act' value=''>
<input type='hidden' name='id_liste' id='id_liste' value='!!id_liste!!'>
<div class='form-contenu'>

	<div class='row'>
		<label class='etiquette' for='libelle'>".htmlentities($msg[103],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type='text' id='libelle' name='libelle' value=\"!!libelle!!\" class='saisie-60em' />
	</div>

	<div class='row'></div>
</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='!!list_simple_action!!' \" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick='this.form.act.value=\"save\"'; return test_form(this.form);  />
	</div>
	<div class='right'>
		!!bouton_sup!!
	</div>
</div>
<div class='row'>
</div>
</form>
<script type='text/javascript'>
	document.forms['simple_list_form'].elements['libelle'].focus();
</script>
<script type='text/javascript'>
function test_form(form)
{
	if(form.libelle.value.length == 0)
	{
		alert('".$msg[98]."');
		document.forms['simple_list_form'].elements['libelle'].focus();
		return false;	
	}
	return true;
}
</script>

";
?>
