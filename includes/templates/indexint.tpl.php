<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: indexint.tpl.php,v 1.19 2010-12-06 15:53:22 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$selector_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";

// $indexint_form : form saisie titre de série
$indexint_form = jscript_unload_question()."
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.indexint_nom.value.length == 0) {
			alert(\"$msg[indexint_name_oblig]\");
			return false;
		}
		unload_off();
		return true;
	}
function confirm_delete() {
        result = confirm(\"${msg[confirm_suppr]}\");
        if(result) {
        	unload_off();
            document.location='./autorites.php?categ=indexint&sub=delete&id=!!id!!&user_input=!!user_input_url!!&page=!!page!!&nbr_lignes=!!nbr_lignes!!&exact=!!exact!!&id_pclass=!!id_pclass!!';
		} else
            document.forms['saisie_indexint'].elements['indexint_nom'].focus();
    }
-->
</script>
<form class='form-$current_module' id='saisie_indexint' name='saisie_indexint' method='post' action='!!action!!'>
<h3>!!libelle!!</h3>
<div class='form-contenu'>
<div class='row'>
	<label class='etiquette' for='form_nom'>$msg[indexint_nom]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-50em' name='indexint_nom' value=\"!!indexint_nom!!\" />
	</div>
<div class='row'>
	<label class='etiquette' for='form_comment'>$msg[indexint_comment]</label>
	</div>
<div class='row'>
	<textarea id='indexint_comment' class='saisie-80em' name='indexint_comment' cols='62' rows='6' wrap='virtual'>!!indexint_comment!!</textarea>
	</div>
	<!-- aut_link -->
</div>
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick=\"unload_off();document.location='./autorites.php?categ=indexint&sub=reach&user_input=!!user_input_url!!&page=!!page!!&nbr_lignes=!!nbr_lignes!!&id_pclass=!!id_pclass!!&exact=!!exact!!';\" />
		<input type='submit' value='$msg[77]' class='bouton' onClick=\"return test_form(this.form)\" />
		!!remplace!!
		!!voir_notices!!
		<input type='hidden' name='page' value='!!page!!' />
		<input type='hidden' name='nbr_lignes' value='!!nbr_lignes!!' />
		<input type='hidden' name='user_input' value=\"!!user_input!!\" />
		<input type='hidden' name='exact' value=\"!!exact!!\" />
		</div>
	<div class='right'>
		!!delete!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>
	document.forms['saisie_indexint'].elements['indexint_nom'].focus();
</script>
";

// $indexint_replace : form remplacement Indexation interne
$indexint_replace = "
<script src='javascript/ajax.js'></script>
<form class='form-$current_module' name='indexint_replace' method='post' action='./autorites.php?categ=indexint&sub=replace&id=!!id!!&id_pclass=!!id_pclass!!' onSubmit=\"return false\" >
<h3>$msg[159] !!indexint_name!! </h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='par'>$msg[160]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-50emr' id='indexint_libelle' name='indexint_libelle' value=\"\" completion=\"indexint\" autfield=\"n_indexint_id\" autexclude=\"!!id!!\"
    	onkeypress=\"if (window.event) { e=window.event; } else e=event; if (e.keyCode==9) { openPopUp('./select.php?what=indexint&caller=indexint_replace&param1=n_indexint_id&param2=indexint_libelle&no_display=!!id!!&id_pclass=!!id_pclass!!', 'select_indexint', $selector_x_size, $selector_y_size, -2, -2, '$selector_prop'); }\" />

		<input class='bouton' type='button' onclick=\"openPopUp('./select.php?what=indexint&caller=indexint_replace&param1=n_indexint_id&param2=indexint_libelle&no_display=!!id!!&id_pclass=!!id_pclass!!', 'select_indexint', $selector_x_size, $selector_y_size, -2, -2, '$selector_prop')\" title='$msg[157]' value='$msg[parcourir]' />
		<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.indexint_libelle.value=''; this.form.n_indexint_id.value='0'; \" />
		<input type='hidden' name='n_indexint_id' id='n_indexint_id' value='0' />
		</div>
	<div class='row'>		
		<input id='aut_link_save' name='aut_link_save' type='checkbox'  value='1'>".$msg["aut_replace_link_save"]."
	</div>	
	</div>
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='./autorites.php?categ=indexint&sub=indexint_form&id=!!id!!&id_pclass=!!id_pclass!!';\">
	<input type='button' class='bouton' value='$msg[159]' id='btsubmit' onClick=\"this.form.submit();\" >
	</div>
</form>
<script type='text/javascript'>
	ajax_parse_dom();
	document.forms['indexint_replace'].elements['indexint_libelle'].focus();
</script>
<br />
<div class='row'>
	!!liste_remplacantes!!
	</div>
";

