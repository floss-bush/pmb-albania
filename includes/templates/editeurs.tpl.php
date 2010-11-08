<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: editeurs.tpl.php,v 1.22 2010-06-16 12:13:47 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$selector_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";

// $publisher_form : form saisie éditeur

$publisher_form = jscript_unload_question()."
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.ed_nom.value.length == 0)
			{
				alert(\"$msg[144]\");
				return false;
			}
		unload_off();
		return true;
	}
function confirm_delete() {
        result = confirm(\"${msg[confirm_suppr]}\");
        if(result) {
        	unload_off();
            document.location='./autorites.php?categ=editeurs&sub=delete&id=!!id!!&user_input=!!user_input_url!!&page=!!page!!&nbr_lignes=!!nbr_lignes!!';
		} else
            document.forms['saisie_editeur'].elements['ed_nom'].focus();
    }
	function check_link(id) {
		w=window.open(document.getElementById(id).value);
		w.focus();
	}
-->
</script>
<form class='form-$current_module' id='saisie_editeur' name='saisie_editeur' method='post' action='!!action!!' onSubmit=\"return false\" >
<h3>!!libelle!!</h3>
<div class='form-contenu'>
	<!-- nom -->
	<div class='row'>
		<label class='etiquette' for='form_nom'>$msg[editeur_nom]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-80em' name='ed_nom' value=\"!!ed_nom!!\" />
		</div>
	<!-- adr1 -->
	<div class='row'>
		<label class='etiquette' for='form_adr1'>$msg[69]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-80em' name='ed_adr1' value=\"!!ed_adr1!!\" />
		</div>
	<!-- adr2 -->
	<div class='row'>
		<label class='etiquette' for='form_adr2'>$msg[70]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-80em' name='ed_adr2' value=\"!!ed_adr2!!\" />
		</div>
	
	<!-- cp -->
	<div class='colonne4'>
	<div class='row'>
		<label class='etiquette' for='form_cp'>$msg[71]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-10em' name='ed_cp' value=\"!!ed_cp!!\" maxlength='10' />
		</div>
	</div>
	
	<!-- ville -->
	<div class='colonne_suite'>
	<div class='row'>
		<label class='etiquette' for='form_ville'>$msg[72]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-20em' name='ed_ville' value=\"!!ed_ville!!\" />
		</div>
	
	</div>
	<!-- pays -->
	<div class='row'>
		<label class='etiquette' for='form_pays'>$msg[146]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-20em' name='ed_pays' value=\"!!ed_pays!!\" />
		</div>
	
	<!-- web -->
	<div class='row'>
		<label class='etiquette' for='form_web'>$msg[147]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-80em' name='ed_web' id='ed_web' value=\"!!ed_web!!\" />
		<input class='bouton' type='button' onClick=\"check_link('ed_web')\" title='$msg[CheckLink]' value='$msg[CheckButton]' />
		</div>

	<!-- Commentaire -->
	<div class='row'>
		<label class='etiquette'>$msg[ed_comment]</label>
		</div>
	<div class='row'>
		<textarea class='saisie-80em' name='ed_comment' cols='62' rows='4' wrap='virtual'>!!ed_comment!!</textarea>
		</div>
	<!-- aut_link -->
	</div>
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick=\"unload_off();document.location='./autorites.php?categ=editeurs&sub=reach&user_input=!!user_input_url!!&page=!!page!!&nbr_lignes=!!nbr_lignes!!';\" />
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
	document.forms['saisie_editeur'].elements['ed_nom'].focus();
</script>
";

// $publisher_replace : form remplacement éditeur
$publisher_replace = "
<script src='javascript/ajax.js'></script>
<form class='form-$current_module' name='publisher_replace' method='post' action='./autorites.php?categ=editeurs&sub=replace&id=!!id!!' onSubmit=\"return false\" >
<h3>$msg[159] !!ed_name!! </h3>
<div class='form-contenu'>
<div class='row'>
		<label class='etiquette' for='par'>$msg[160]</label>
		</div>
<div class='row'>
	<input type='text' class='saisie-50emr' id='ed_libelle' name='ed_libelle' value=\"\" completion=\"publishers\" autfield=\"ed_id\" autexclude=\"!!id!!\"
   	onkeypress=\"if (window.event) { e=window.event; } else e=event; if (e.keyCode==9) { openPopUp('./select.php?what=editeur&caller=publisher_replace&p1=ed_id&p2=ed_libelle&no_display=!!id!!', 'select_ed', $selector_x_size, $selector_y_size, -2 ,-2, '$selector_prop'); }\" />
	<input class='bouton' type='button' onclick=\"openPopUp('./select.php?what=editeur&caller=publisher_replace&p1=ed_id&p2=ed_libelle&no_display=!!id!!', 'select_ed', $selector_x_size, $selector_y_size, -2 ,-2, '$selector_prop')\" title='$msg[157]' value='$msg[parcourir]' />
	<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.ed_libelle.value=''; this.form.ed_id.value='0'; \" />
	<input type='hidden' name='ed_id' id='ed_id'>
	</div>
</div>
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='./autorites.php?categ=editeurs&sub=editeur_form&id=!!id!!';\">
	<input type='button' class='bouton' value='$msg[159]' id='btsubmit' onClick=\"this.form.submit();\" >
	</div>
</form>
<script type='text/javascript'>
	ajax_parse_dom();
	document.forms['publisher_replace'].elements['ed_libelle'].focus();
</script>
";

