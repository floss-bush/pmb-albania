<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: series.tpl.php,v 1.17 2010-06-16 12:13:47 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$selector_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";

// $serie_form : form saisie titre de série
$serie_form = jscript_unload_question()."
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.serie_nom.value.length == 0)
			{
				alert(\"$msg[338]\");
				return false;
			}
		unload_off();
		return true;
	}
function confirm_delete() {
        result = confirm(\"${msg[confirm_suppr]}\");
        if(result) {
        	unload_off();
            document.location='./autorites.php?categ=series&sub=delete&id=!!id!!&user_input=!!user_input_url!!&page=!!page!!&nbr_lignes=!!nbr_lignes!!';
		} else
            document.forms['saisie_serie'].elements['serie_nom'].focus();
    }
-->
</script>
<form class='form-$current_module' id='saisie_serie' name='saisie_serie' method='post' action='!!action!!' onSubmit=\"return false\" >
<h3>!!libelle!!</h3>
<div class='form-contenu'>
<div class='row'>
	<label class='etiquette' for='form_nom'>$msg[233]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='serie_nom' value=\"!!serie_nom!!\" />
	</div>
	<!-- aut_link -->
</div>
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick=\"unload_off();document.location='./autorites.php?categ=series&sub=reach&user_input=!!user_input_url!!&page=!!page!!&nbr_lignes=!!nbr_lignes!!';\" />
		<input type='button' value='$msg[77]' class='bouton' id='btsubmit' onClick=\"if (test_form(this.form)) this.form.submit();\" />
		!!remplace!!
		!!voir_notices!!
		<input type='hidden' name='page' value='!!page!!' />
		<input type='hidden' name='nbr_lignes' value='!!nbr_lignes!!' />
		<input type='hidden' name='user_input' value=\"!!user_input!!\" />
		</div>
	<div class='right'>
		!!delete!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>
	document.forms['saisie_serie'].elements['serie_nom'].focus();
</script>
";

// $serie_replace : form remplacement titre de série
$serie_replace = "
<script src='javascript/ajax.js'></script>
<form class='form-$current_module' name='serie_replace' method='post' action='./autorites.php?categ=series&sub=replace&id=!!id!!' onSubmit=\"return false\" >
<h3>$msg[159] !!serie_name!! </h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='par'>$msg[160]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-80emr' id='serie_libelle' name='serie_libelle' value=\"\" completion=\"series\" autfield=\"n_serie_id\" autexclude=\"!!id!!\"
    	onkeypress=\"if (window.event) { e=window.event; } else e=event; if (e.keyCode==9) { openPopUp('./select.php?what=serie&caller=serie_replace&param1=n_serie_id&param2=serie_libelle&no_display=!!id!!', 'select_serie', $selector_x_size, $selector_y_size, -2, -2, '$selector_prop'); }\" />

		<input class='bouton' type='button' onclick=\"openPopUp('./select.php?what=serie&caller=serie_replace&param1=n_serie_id&param2=serie_libelle&no_display=!!id!!', 'select_serie', $selector_x_size, $selector_y_size, -2, -2, '$selector_prop')\" title='$msg[157]' value='$msg[parcourir]' />
		<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.serie_libelle.value=''; this.form.n_serie_id.value='0'; \" />
		<input type='hidden' name='n_serie_id' id='n_serie_id' value='0' />
		</div>
	</div>
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='./autorites.php?categ=series&sub=serie_form&id=!!id!!';\">
	<input type='button' class='bouton' value='$msg[159]' id='btsubmit' onClick=\"this.form.submit();\" >
	</div>
</form>
<script type='text/javascript'>
	ajax_parse_dom();
	document.forms['serie_replace'].elements['serie_libelle'].focus();
</script>
";

