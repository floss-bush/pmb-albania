<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_cart.tpl.php,v 1.12 2010-04-15 13:28:35 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// templates pour la gestion des paniers

// template pour le form de création d'un panier
$empr_cart_form = "
<script type=\"text/javascript\">
function test_form(form)
{
	if(form.cart_name.value.length == 0)
	{
		alert(\"$msg[caddie_name_oblig]\");
		return false;
	}
	return true;
}
</script>

<form class='form-$current_module' name='cart_form' method='post' action='!!formulaire_action!!'>
<h3>$msg[new_cart]</h3>
<div class='form-contenu'>
<!--	type	-->
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_name]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='cart_name' value='' />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_comment]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='cart_comment' value='' />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_autorisations]</label>
	<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
	<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
	</div>
<div class='row'>
	!!autorisations_users!!
	</div>
</div>
<!--	boutons	-->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='!!formulaire_annuler!!';\">
	<input type='submit' value='$msg[77]' class='bouton' onClick=\"return test_form(this.form)\" />
	<input type='hidden' name='form_actif' value='1'>
	</div>
</form>
<script type=\"text/javascript\">
		document.forms['cart_form'].elements['cart_name'].focus();
</script>
";

$empr_cart_edit_form = "
<script type=\"text/javascript\">
function test_form(form)
{
	if(form.cart_name.value.length == 0)
	{
		alert(\"$msg[caddie_name_oblig]\");
		return false;
	}
	return true;
}
</script>

<form class='form-$current_module' name='cart_form' method='post' action='!!formulaire_action!!'>
<h3>$msg[edit_cart]</h3>
<div class='form-contenu'>
<!--	type	-->
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_name]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='cart_name' value='!!name!!' />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_comment]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='cart_comment' value='!!comment!!' />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_autorisations]</label>
	<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
	<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
</div>
<div class='row'>
	!!autorisations_users!!
	</div>
</div>
<!--	boutons	-->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='!!formulaire_annuler!!';\">&nbsp;
		<input type='submit' value='$msg[77]' class='bouton' onClick=\"return test_form(this.form)\" />
		<input type='hidden' name='form_actif' value='1'>
	</div>
	<div class='right'>
		<input type='button' class='bouton' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!idemprcaddie!!,'!!name_suppr!!')\" />
	</div>
</div>
<div class='row'></div>
</form>
<script type=\"text/javascript\">
		document.forms['cart_form'].elements['cart_name'].focus();
</script>
";

// $empr_cart_procs_form : template form procédures stockées
$empr_cart_procs_form = "
<form class='form-$current_module' name='maj_proc' method='post' action='!!action!!'>
<h3>!!form_title!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_type'>$msg[caddie_procs_type]</label>
		</div>
	<div class='row'>
		<select name='f_proc_type'>
		<option value='SELECT'>$msg[caddie_procs_type_SELECT]</option>
		<option value='ACTION'>$msg[caddie_procs_type_ACTION]</option>
		</select>
		</div>
	<div class='row'>
		<label class='etiquette' for='form_name'>$msg[705]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_name' value='!!name!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[706]</label>
		</div>
	<div class='row'>
		<textarea cols='70' rows='8' name='f_proc_code'>!!code!!</textarea><br />
		".$msg['cart_ex_selection']." select notice_id as <b>object_id</b>, <b>'NOTI'</b> as object_type from notices where ...<br />
		'NOTI' / 'EXPL' / 'BULL'<br />
		".$msg['cart_ex_action']." update exemplaires set expl_statut=!!nouveau_statut!! where expl_id in (CADDIE(<b>EXPL</b>))<br />
		EXPL / NOTI / BULL
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[707]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[procs_autorisations]</label>
		<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
		<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
		</div>
	<div class='row'>
		!!autorisations_users!!
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./circ.php?categ=caddie&sub=gestion&quoi=procs\"' />&nbsp;
	<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form)\" />&nbsp;
	</div>
</form>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>
";

$empr_proc_view_remote = "
<h3><span onclick='menuHide(this,event)'>>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		!!additional_information!!
	</div>
	<div class=colonne2>
		<div class='row'>
		<label class='etiquette' for='form_name'>$msg[remote_procedures_procedure_name]</label>
		</div>
		<div class='row'>
		<input type='text' readonly name='f_proc_name' value='!!name!!' maxlength='255' class='saisie-50em' />
		</div>
	</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[caddie_procs_type]</label>
		</div>
	<div class='row'>
		!!ptype!!
	</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[remote_procedures_procedure_sql]</label>
		</div>
	<div class='row'>
		<textarea cols='80' readonly rows='8' name='f_proc_code'>!!code!!</textarea>
	</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[remote_procedures_procedure_comment]</label>
		</div>
	<div class='row'>
		<input type='text' readonly name='f_proc_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
	</div>
	<div class='row'>
		!!parameters_title!!
	</div>
	<div class='row'>
		!!parameters_content!!
	</div>
</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='".$msg["remote_procedures_back"]."' onClick='document.location=\"./circ.php?categ=caddie&sub=gestion&quoi=remote_procs\"' />&nbsp;
		<input class='bouton' type='button' value=\"".$msg["remote_procedures_import"]."\" onClick=\"document.location='./circ.php?categ=caddie&sub=gestion&quoi=remote_procs&action=import_remote&id=!!id!!'\" />
		</div>
</div>
<div class='row'></div>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>";


// template form édition procédures stockées
$empr_cart_procs_edit_form = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!'>
<h3>!!form_title!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_type'>$msg[caddie_procs_type]</label>
		</div>
	<div class='row'>
		!!type!!
		</div>
	<div class='row'>
		<label class='etiquette' for='form_name'>$msg[705]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_name' value='!!name!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[706]</label>
		</div>
	<div class='row'>
		<textarea cols='70' rows='8' name='f_proc_code'>!!code!!</textarea><br />
		".$msg['cart_ex_selection']." select notice_id as <b>object_id</b>, <b>'NOTI'</b> as object_type from notices where ...<br />
		'NOTI' / 'EXPL' / 'BULL'<br />
		".$msg['cart_ex_action']." update exemplaires set expl_statut=!!nouveau_statut!! where expl_id in (CADDIE(<b>EXPL</b>))<br />
		EXPL / NOTI / BULL
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[707]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[procs_autorisations]</label>
		<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
		<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
		</div>
	<div class='row'>
		!!autorisations_users!!
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./circ.php?categ=caddie&sub=gestion&quoi=procs\"' />&nbsp;
		<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form)\" />&nbsp;
		!!exec_button!!
	</div>
	<div class='right'>
		<input type='button' class='bouton' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!name_suppr!!')\" />
	</div>
</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>
";

// $empr_cart_choix_quoi : template form choix des éléments à traiter
$empr_cart_choix_quoi = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!' >
<h3>!!titre_form!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' name='elt_flag' value='1'>$msg[caddie_item_marque]";
$cart_choix_quoi .= "
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<input type='checkbox' name='elt_no_flag' value='1'>$msg[caddie_item_NonMarque]";
$cart_choix_quoi .= "
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"!!action_cancel!!\"' />&nbsp;
	<input type='submit' class='bouton' value='!!bouton_valider!!' !!onclick_valider!!/>&nbsp;
	</div>
</form>
";

// $empr_cart_choix_quoi_action : template form choix des éléments à traiter pour une procédure d'action
$empr_cart_choix_quoi_action = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='' >
<h3>".$msg["caddie_choix_action"]."</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' name='elt_flag' value='1'>$msg[caddie_item_marque]
		</div>
	<div class='row'>
		<input type='checkbox' name='elt_no_flag' value='1'>$msg[caddie_item_NonMarque]
		</div>
	</div>
</form>
";

// $empr_cart_choix_quoi_edition : template form choix des éléments à éditer
$empr_cart_choix_quoi_edition = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!' >
<h3>!!titre_form!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' name='elt_flag' value='1'>$msg[caddie_item_marque]
		</div>
	<div class='row'>
		<input type='checkbox' name='elt_no_flag' value='1'>$msg[caddie_item_NonMarque]
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"!!action_cancel!!\"' />&nbsp;
	<!-- !!boutons_supp!! -->
	</div>
</form>
";

//******************* Procédures *****************************
// $cart_procs_form : template form procédures stockées
$empr_cart_procs_form = "
<form class='form-$current_module' name='maj_proc' method='post' action='!!action!!'>
<h3>!!form_title!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_type'>$msg[caddie_procs_type]</label>
		</div>
	<div class='row'>
		<select name='f_proc_type'>
		<option value='SELECT'>$msg[caddie_procs_type_SELECT]</option>
		<option value='ACTION'>$msg[caddie_procs_type_ACTION]</option>
		</select>
		</div>
	<div class='row'>
		<label class='etiquette' for='form_name'>$msg[705]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_name' value='!!name!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[706]</label>
		</div>
	<div class='row'>
		<textarea cols='70' rows='10' name='f_proc_code'>!!code!!</textarea><br />
		".$msg['cart_ex_selection']." select id_empr as <b>object_id</b> from empr where ...<br />
		".$msg['cart_ex_action']." update empr set empr_statut=!!nouveau_statut!! where id_empr in (CADDIE(<b>EMPR</b>))
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[707]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[procs_autorisations]</label>
		<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
		<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
		</div>
	<div class='row'>
		!!autorisations_users!!
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./circ.php?categ=caddie&sub=gestion&quoi=procs\"' />&nbsp;
	<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form)\" />&nbsp;
	</div>
</form>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>
";

// template form édition procédures stockées
$empr_cart_procs_edit_form = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!'>
<h3>!!form_title!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_type'>$msg[caddie_procs_type]</label>
		</div>
	<div class='row'>
		!!type!!
		</div>
	<div class='row'>
		<label class='etiquette' for='form_name'>$msg[705]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_name' value='!!name!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[706]</label>
		</div>
	<div class='row'>
		<textarea cols='70' rows='10' name='f_proc_code'>!!code!!</textarea><br />
		".$msg['cart_ex_selection']." select id_empr as <b>object_id</b> from empr where ...<br />
		".$msg['cart_ex_action']." update empr set empr_statut=!!nouveau_statut!! where id_empr in (CADDIE(<b>EMPR</b>))
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[707]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[procs_autorisations]</label>
		<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
		<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
		</div>
	<div class='row'>
		!!autorisations_users!!
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./circ.php?categ=caddie&sub=gestion&quoi=procs\"' />&nbsp;
		<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form)\" />&nbsp;
		!!exec_button!!
	</div>
	<div class='right'>
		<input type='button' class='bouton' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!name_suppr!!')\" />
	</div>
</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>
";

// $empr_cart_choix_quoi : template form choix des éléments à traiter
$empr_cart_choix_quoi = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!' >
<h3>!!titre_form!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' name='elt_flag' value='1'>$msg[caddie_item_marque]
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<input type='checkbox' name='elt_no_flag' value='1'>$msg[caddie_item_NonMarque]
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"!!action_cancel!!\"' />&nbsp;
	<input type='submit' class='bouton' value='!!bouton_valider!!' !!onclick_valider!!/>&nbsp;
	</div>
</form>
";

// $cart_choix_quoi_action : template form choix des éléments à traiter pour une procédure d'action
$empr_cart_choix_quoi_action = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='' >
<h3>".$msg["caddie_choix_action"]."</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' name='elt_flag' value='1'>$msg[caddie_item_marque]
		</div>
	<div class='row'>
		<input type='checkbox' name='elt_no_flag' value='1'>$msg[caddie_item_NonMarque]
		</div>
	</div>
</form>
";

// $empr_cart_choix_quoi_exporter : template form choix des éléments à exporter
$empr_cart_choix_quoi_exporter = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!' >
<h3>!!titre_form!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' name='elt_flag' value='1'>$msg[caddie_item_marque]
		</div>
	<div class='row'>
		<input type='checkbox' name='elt_no_flag' value='1'>$msg[caddie_item_NonMarque]
		</div>
	<div class='row'>
		Type d'export  !!export_type!!
	</div>
</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"!!action_cancel!!\"' />&nbsp;
	<input type='submit' class='bouton' value='!!bouton_valider!!' />&nbsp;
	</div>
</form>
";

