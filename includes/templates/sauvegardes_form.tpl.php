<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sauvegardes_form.tpl.php,v 1.6 2007-03-14 16:51:33 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$form='
<script>
//Vérification de la saisie du formulaire
function checkForm()
{
	f=document.sauv_sauvegardes;
	if (f.act.value!="cancel")
	{
		//vérifications avant post
		if (f.sauv_sauvegarde_nom.value=="") {
			alert("'.$msg["sauv_sauvegardes_valid_form_name"].'");
			return false;
		}
	}
	return true;
}

</script>

<!-- Formulaire -->

<form name="sauv_sauvegardes" action="admin.php?categ=sauvegarde&sub=gestsauv" method="post" onSubmit="return checkForm();">
<input type="hidden" name="act" value="show">
<input type="hidden" name="sauv_sauvegarde_id" value="!!sauv_sauvegarde_id!!">
<input type="hidden" name="first" value="1">
<table >
<th class="brd" colspan=2><center>!!quel_proc!!: '.$msg["sauv_sauvegardes_form_prop_general"].'</center></th>

<!--
	<tr><td colspan=2><b>'.$msg["sauv_sauvegardes_form_prop_general"].'</b></td></tr>
-->
<tr><td class="nobrd" colspan=2>&nbsp;</td></tr>
<tr><td class="nobrd" width="50%">'.$msg["sauv_sauvegardes_form_nom"].'</td><td class="nobrd"><input type="text"  name="sauv_sauvegarde_nom" value="!!sauv_sauvegarde_nom!!" class="saisie-simple"></td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_prefix"].'</td><td class="nobrd"><input type="text"  name="sauv_sauvegarde_file_prefix" value="!!sauv_sauvegarde_file_prefix!!" class="saisie-simple"></td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_groupe_tables"].'</td><td class="nobrd">!!sauv_sauvegarde_tables!!</td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_lieux"].'</td><td class="nobrd">!!sauv_sauvegarde_lieux!!</td></tr>
<tr><th class="nobrd" colspan=2>'.$msg["sauv_sauvegardes_form_prop_cpr"].'</th></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_compress"].'</td><td class="nobrd"><input type="radio" name="sauv_sauvegarde_compress" value="0" !!checked_compress_no!! class="saisie-simple">&nbsp;'.$msg["sauv_sauvegardes_form_non"].'&nbsp;<input type="radio" name="sauv_sauvegarde_compress" value="1" !!checked_compress_yes!! class="saisie-simple">&nbsp;'.$msg["sauv_sauvegardes_form_oui"].'</td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_compress_method"].'</td><td class="nobrd">!!sauv_sauvegarde_compress_method!!</td></tr>
<tr><td class="nobrd">&nbsp;</td><td class="nobrd"><i>'.$msg["sauv_sauvegardes_form_external_cmd"].'</i></td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_compr_cmd"].'</td><td class="nobrd"><input type="text"  name="sauv_sauvegarde_zip_command" value="!!sauv_sauvegarde_zip_command!!" class="saisie-simple"></td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_decompr_cmd"].'</td><td class="nobrd"><input type="text"  name="sauv_sauvegarde_unzip_command" value="!!sauv_sauvegarde_unzip_command!!" class="saisie-simple"></td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_compr_ext"].'</td><td class="nobrd"><input type="text"  name="sauv_sauvegarde_zip_ext" value="!!sauv_sauvegarde_zip_ext!!" class="saisie-simple"></td></tr>
<tr><th class="nobrd" colspan=2>'.$msg["sauv_sauvegardes_form_security"].'</th></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_users_auth"].'</td><td class="nobrd">!!sauv_sauvegarde_users!!</td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_crypt"].'</td><td class="nobrd"><input type="radio" name="sauv_sauvegarde_crypt" value="0" !!checked_crypt_no!! class="saisie-simple">&nbsp;'.$msg["sauv_sauvegardes_form_non"].'&nbsp;<input type="radio" name="sauv_sauvegarde_crypt" value="1" !!checked_crypt_yes!! class="saisie-simple">&nbsp;'.$msg["sauv_sauvegardes_form_oui"].'</td></tr>
<tr><td class="nobrd" colspan=2><i>'.$msg["sauv_sauvegardes_form_crypt_keys"].' !!crypt_msg!!</i></td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_phrase1"].'</td><td class="nobrd"><input type="text"  name="sauv_sauvegarde_key1" value="" class="saisie-simple"></td></tr>
<tr><td class="nobrd">'.$msg["sauv_sauvegardes_form_phrase2"].'</td><td class="nobrd"><input type="text"  name="sauv_sauvegarde_key2" value="" class="saisie-simple"></td></tr>
!!sauv_sauvegarde_erase_keys!!
</table>
<!-- Boutons de soumission -->
<div class="left">
	<input type="submit" value="'.$msg["sauv_annuler"].'" onClick="this.form.act.value=\'cancel\'" class=\'bouton\'">
	<input type="submit" value="'.$msg["sauv_enregistrer"].'" onClick="this.form.act.value=\'update\'" class=\'bouton\'>&nbsp;
	</div>
<div class="right">
	!!delete!!&nbsp;
</div>
<div class="row"></div>';
?>