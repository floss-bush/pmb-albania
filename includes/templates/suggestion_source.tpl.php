<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestion_source.tpl.php,v 1.1 2009-07-31 14:37:09 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$src_form = "
<form class='form-".$current_module."' id='srcform' name='srcform' method='post' action=\"./admin.php?categ=acquisition&sub=src&id_src=!!id!!\">
<h3>!!form_title!!</h3>
<!--    Contenu du form    -->
<input type='hidden' name='act' id='act' value=''>
<div class='form-contenu'>

	<div class='row'>
		<label class='etiquette' for='libelle'>".htmlentities($msg[103],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type=text id='libelle' name='libelle' value=\"!!libelle!!\" class='saisie-60em' />
	</div>

	<div class='row'></div>
</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=acquisition&sub=src' \" />&nbsp;
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
	document.forms['srcform'].elements['libelle'].focus();
</script>
<script type='text/javascript'>
function test_form(form)
{
	if(form.libelle.value.length == 0)
	{
		alert('".$msg[98]."');
		document.forms['srcform'].elements['libelle'].focus();
		return false;	
	}
	return true;
}
</script>

";
?>