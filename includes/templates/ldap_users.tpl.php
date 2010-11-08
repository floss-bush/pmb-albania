<?php
// +-------------------------------------------------+
// ? 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ldap_users.tpl.php,v 1.7 2009-05-16 11:19:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// templates for ldap_import operations (groups choice and list of users from ldap server)

$form_ldap_groups="
<form class='form-$current_module' name='form1' method='post' action=\"./admin.php?categ=empr&sub=ldap&action=ldapOK\">
<h3>".$msg["import_ldap"]."</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='ldap_import_grp'>".$msg["import_ldap_grp"]."</label>
		<select  name='ldap_grp' value='' class='saisie-10em'>
			!!opz_grp!!
		</select>
	</div>
</div>
<div class='row'>
		<input type='submit' class='bouton' name='import' value='".$msg[502]."'/>
	</div>
</form>
";

$form_show_ldap_users="
<script type='text/javascript'>
<!--
function setCheckboxColumn(theCheckbox){
	if (document.getElementById(theCheckbox)) {
		document.getElementById(theCheckbox).checked = (document.getElementById(theCheckbox).checked ? false : true);
		if (document.getElementById(theCheckbox + 'r')) {
				document.getElementById(theCheckbox + 'r').checked = document.getElementById(theCheckbox).checked;
		}
	} else {
		if (document.getElementById(theCheckbox + 'r')) {
				document.getElementById(theCheckbox + 'r').checked = (document.getElementById(theCheckbox +'r').checked ? false : true);
				if (document.getElementById(theCheckbox)) {
					document.getElementById(theCheckbox).checked = document.getElementById(theCheckbox + 'r').checked;
				}
		}
	}
}
//-->
</script>
<form class='form-$current_module' name='form1' method='post' action=\"./admin.php?categ=empr&sub=ldap&action=ldapOK\">
<div class='row'>
	<div class='left'>
		<h3>".$msg["import_ldap_usr"]."</h3>
	</div>
	<div class='right'>".$msg["npp_ctrl"]."!!npp_ctrl!!
	</div>
</div>
<div class='row'>
	<table>
		<th></th>
		<th></th>
		<th>$msg[38]</th>
		<th>$msg[67]</th>
		<th>$msg[68]</th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		!!usr_list!!
	</table>
</div>
<div class='row'>
	<table class='table-but'><tr>
	<td class='td-lbut'>
			<table class='table-but'><tr>
				<td class='td-lbut'><center>!!nav_barL!!</center></td>
				<td class='td-cbutb'>!!nav_barC!!</td>
				<td class='td-lbut'><center>!!nav_barR!!</center></td>
			</tr></table>
	</td>
	<td class='td-cbut'>
		<input type='submit' class='bouton' name='btsubmit' value='$msg[import_ldap_exe]' />
	</td>
	<td class='td-rbut'>
		<input type='submit' class='bouton' name='btsubmit' value='$msg[del_ldap_usr]' />
	</td>
	</tr></table>
</div>
<br />
!!hid_vars!!
</form>
";

$form_show_exldap_users="
<script type='text/javascript'>
<!--
function setCheckboxColumn(theCheckbox){
	if (document.getElementById(theCheckbox)) {
		document.getElementById(theCheckbox).checked = (document.getElementById(theCheckbox).checked ? false : true);
		if (document.getElementById(theCheckbox + 'r')) {
				document.getElementById(theCheckbox + 'r').checked = document.getElementById(theCheckbox).checked;
		}
	} else {
		if (document.getElementById(theCheckbox + 'r')) {
				document.getElementById(theCheckbox + 'r').checked = (document.getElementById(theCheckbox +'r').checked ? false : true);
				if (document.getElementById(theCheckbox)) {
					document.getElementById(theCheckbox).checked = document.getElementById(theCheckbox + 'r').checked;
				}
		}
	}
}
//-->
</script>
<form class='form-$current_module' name='form1' method='post' action=\"./admin.php?categ=empr&sub=exldap&action=exldapDEL\">
<div class='row'>
	<div class='left'>
		<h3>$msg[exldap_titolo]</h3>
	</div>
	<div class='right'>".$msg["npp_ctrl"]."!!npp_ctrl!!
	</div>
</div>
<div class='row'>
	<table>
		<th></th>
		<th></th>
		<th>$msg[66]</th>
		<th>$msg[67]</th>
		<th>$msg[68]</th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		!!usr_list!!
	</table>
</div>
<div class='row'>
	<table class='table-but'><tr>
	<td class='td-lbut'>
			<table class='table-but'><tr>
				<td class='td-lbut'><center>!!nav_barL!!</center></td>
				<td class='td-cbutb'>!!nav_barC!!</td>
				<td class='td-lbut'><center>!!nav_barR!!</center></td>
			</tr></table>
	</td>
	<td class='td-rbut'>
		<input type='submit' class='bouton' name='btsubmit' value='$msg[exldap_conserva]' />
	</td>
	<td class='td-rbut'>
		<input type='submit' class='bouton' name='btsubmit' value='$msg[exldap_normale]' />
	</td>
	<td class='td-cbut'>
		<input type='submit' class='bouton' name='btsubmit' value='$msg[exldap_elimina]' />
	</td>
	</tr></table>
</div>
<br />
!!hid_vars!!
</form>
";
