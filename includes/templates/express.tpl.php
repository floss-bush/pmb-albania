<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: express.tpl.php,v 1.5 2009-05-16 11:19:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// select "type document"
$sel_tdoc=do_selector('docs_type', 'pe_tdoc', 0);

$layout_begin = "
<script type=\"text/javascript\">
<!--
function test_form(form)
{
	if (!form.pe_titre.value){
		alert(\"".$msg['pret_express_err']."\");
		form.pe_titre.focus();
		return false;
	}
	document.pret_express_form.submit();
}

-->
</script>

<div class=\"row\">
<h1>$msg[pret_express]</h1>
$msg[pret_express_new] <a href='./circ.php?categ=pret&form_cb=!!cb_lecteur!!&groupID=$groupID'>!!nom_lecteur!!</a>
</div>
<br />

<form class='form-$current_module' id='express' name='pret_express_form' method='post' action='./circ.php?categ=pret&sub=pret_express&id_empr=$id_empr&groupID=$groupID'>
<h3>$msg[pret_express_cap]</h3>

<div class='form-contenu'>
	<!--	ISBN	-->
	<div class='row'>
		<label class='etiquette' for='pe_isbn'>$msg[pret_express_cod]</label>
		<br />
		<input class='saisie-20em' id='pe_isbn' type='text' value='' name='pe_isbn' />
		</div>

	<!--	titolo	-->
	<div class='row'>
		<label class='etiquette' for='pe_titre'>$msg[pret_express_tit]</label>
		<br />
		<input class='saisie-80em' id='pe_titre' type='text' value='' name='pe_titre' />
		</div>


	<div class='row'><br /><hr /></div>
	<!-- type document -->
	<div class='colonne3'>
		<label class='etiquette' for='f_ex_typdoc'>$msg[294]</label>
		<br />
		$sel_tdoc
	</div>
	<div class='colonne_suite'>
	<!-- codice a barre	-->
		<label class='etiquette' for='pe_excb'>$msg[pret_express_ecb]</label>
		<br />
		<input class='saisie-20em' id='pe_excb' type='text' value='' name='pe_excb' />
		</div>
	<div class='row'></div>
	</div>

<!--	Bouton d'envoi	-->
<div class='row'>
	<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./circ.php?categ=pret&sub=&id_empr=$id_empr&groupID=$groupID'\" />&nbsp;
	<input class='bouton' type='button' value='$msg[pret_express_reg]' onClick=\"return test_form(this.form)\" />
	</div>
</form>";



