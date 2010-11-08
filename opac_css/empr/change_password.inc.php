<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: change_password.inc.php,v 1.15 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if (!$allow_pwd) die();
print "<div id='change-password'>
<h3><span>".$msg['empr_modify_password']."</span></h3>
<div id='change-password-container'>
<form action=\"empr.php\" method=\"post\" name=\"FormName\">
<table width='60%' cellpadding='5'>
	<tr>
		<td width='50%'>".$msg["empr_new_password"]."</td>
		<td width='50%'><input type=\"hidden\" name=\"lvl\" value=\"valid_change_password\"/><input type=\"password\" name=\"new_password\" size=\"15\" border=\"0\" value=\"$new_password\"/></td>
	</tr>
	<tr>
		<td width='50%'>".$msg["empr_confirm_new_password"]."</td>
		<td width='50%'><input type=\"password\" name=\"confirm_new_password\" size=\"15\" border=\"0\" value=\"$confirm_new_password\"/></td>
	</tr>
	<tr>
		<td colspan=2><input type='button' class='bouton' name='ok' value='&nbsp;$msg[empr_valid_password]&nbsp;' onClick='this.form.submit()'/></td>
	</tr>
</table></form>
</div></div>";