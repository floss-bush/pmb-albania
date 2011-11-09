<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: etagere.tpl.php,v 1.12 2011-03-30 14:54:21 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// templates pour la gestion des paniers

// template pour le form de création d'une étagère
$etagere_form = "
<script type=\"text/javascript\">
function test_form(form)
{
	if(form.form_etagere_name.value.length == 0)
	{
		alert(\"$msg[etagere_name_oblig]\");
		return false;
	}
	return true;
}
</script>

<form class='form-$current_module' name='etagere_form' method='post' action='!!formulaire_action!!'>
<h3>!!formulaire_titre!!</h3>
<div class='form-contenu'>
<!--	type	-->
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[etagere_name]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='form_etagere_name' value='!!name!!' />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[etagere_visible_date]</label>
	</div>
<div class='row'>
	$msg[etagere_visible_date_all]&nbsp;<input type=checkbox name=form_visible_all value='1' !!checkbox_all!! class='checkbox' onClick=\"vadidite_check(this.form)\" />&nbsp;&nbsp;$msg[etagere_visible_date_deb]<input type='text' class='saisie-10em' name='form_visible_deb' value='!!form_visible_deb!!' />&nbsp;$msg[etagere_visible_date_fin]&nbsp;<input type='text' class='saisie-10em' name='form_visible_fin' value='!!form_visible_fin!!' />&nbsp;$msg[etagere_visible_accueil]&nbsp;<input type=checkbox name=form_visible_accueil value='1' !!checkbox_accueil!! class='checkbox'  />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[etagere_comment]</label>
	</div>
<div class='row'>
	<textarea id='f_n_contenu' class='saisie-80em' name='form_etagere_comment' cols='62' rows='5' wrap='virtual'>!!comment!!</textarea>
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[etagere_autorisations]</label>
	<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
	<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
	</div>
<div class='row'>
	!!autorisations_users!!
	</div>
<div class='row'>
	<a href=# onClick=\"document.getElementById('history').src='./sort.php?action=0&caller=etagere'; document.getElementById('history').style.display='';return false;\" alt=\"".$msg['tris_dispos']."\" title=\"".$msg['tris_dispos']."\">
		<img src='./images/orderby_az.gif' align='middle' hspace='3'>
	</a>
	<input type='hidden' value='!!tri!!' name='tri'/>
	<span id='etagere_sort'>
		!!tri_name!!
	</span>
	<script type='text/javascript'>
		function getSort(id,name){
			document.forms.etagere_form.tri.value=id;
			var name = document.createTextNode(name);
			var span = document.getElementById('etagere_sort');
			while(span.firstChild){
				span.removeChild(span.firstChild);
			}
			span.appendChild(name);
			
		}
	</script>
	</div>
</div>
<!--	boutons	-->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='!!formulaire_annuler!!';\">
		<input type='submit' value='$msg[77]' class='bouton' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
		<!--!!bouton_suppr!!-->
		</div>
	</div>
<div class='row'></div>
</form>
<script type=\"text/javascript\">
	document.forms['etagere_form'].elements['form_etagere_name'].focus();
	function vadidite_check(form) {
		if (form.form_visible_all.checked==true) {
			form.form_visible_deb.disabled='disabled' ;
			form.form_visible_deb.value='' ;
			form.form_visible_fin.disabled='disabled' ;
			form.form_visible_fin.value='' ;
			} else {
				form.form_visible_deb.disabled='';
				form.form_visible_fin.disabled='';
				}
		}
	vadidite_check(document.forms['etagere_form']);
</script>
";

// template pour le form de constitution d'une étagère
$etagere_constitution_form = "
<form class='form-$current_module' name='etagere_constitution_form' method='post' action='./catalog.php?categ=etagere&sub=constitution&action=save_etagere'>
<h3>!!formulaire_titre!!</h3>
<div class='form-contenu'>
<div class='row'>
	!!constitution!!
	</div>
</div>
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='./catalog.php?categ=etagere&sub=constitution';\">
	<input type='submit' value='$msg[77]' class='bouton' />
	<input type='hidden' name='idetagere' value='!!idetagere!!' />
	</div>
</form>
";

