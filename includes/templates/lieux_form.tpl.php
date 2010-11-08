<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lieux_form.tpl.php,v 1.8 2008-03-03 13:47:17 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$form='
<script>
//Message d\'erreur avant test de connexion
function callFtpTestError(msg)
{
	alert(msg);
}

//Appel du test de la connexion ftp
function callFtpTest()
{
	f=document.sauv_lieux;
	if (f.sauv_lieu_protocol.options[f.sauv_lieu_protocol.selectedIndex].value!="ftp") {
		callFtpTestError("'.$msg["sauv_lieux_ftp_error_protocol"].'");
		return;
	}
	if (f.sauv_lieu_host.value=="") {
		callFtpTestError("'.$msg["sauv_lieux_ftp_error_host"].'");
		return;
	}
	if (f.sauv_lieu_login.value=="") {
		callFtpTestError("'.$msg["sauv_lieux_ftp_error_user"].'");
		return;
	}
	if (f.sauv_lieu_password.value=="") {
		callFtpTestError("'.$msg["sauv_lieux_ftp_error_password"].'");
		return;
	}
	openPopUp("admin/sauvegarde/lib/test_ftp.php?url="+encodeURI(f.sauv_lieu_host.value)+"&user="+encodeURI(f.sauv_lieu_login.value)+"&password="+encodeURI(f.sauv_lieu_password.value)+"&chemin="+encodeURI(f.sauv_lieu_url.value),"test_ftp", 100, 100, -2, -2, "width=100,height=100,menubar=no,resizable=yes");
}

//Vérification de la saisie du formulaire
function checkForm()
{
	f=document.sauv_lieux;
	if (f.act.value!="cancel")
	{
		if (f.sauv_lieu_nom.value=="") {
			alert("'.$msg["sauv_lieux_valid_form_error_name"].'");
			return false;
		}
		if (f.sauv_lieu_url.value=="") {
			alert("'.$msg["sauv_lieux_valid_form_error_path"].'");
			return false;
		}
		if ((f.sauv_lieu_protocol.options[f.sauv_lieu_protocol.selectedIndex].value!="ftp")&&((f.sauv_lieu_login.value!="")||(f.sauv_lieu_password.value!=""))) {
			if (confirm("'.$msg["sauv_lieux_valid_form_error_bad_protocol"].'"))
			{
				f.sauv_lieu_protocol.options[1].selected=true;
			} else {
				f.sauv_lieu_login.value="";
				f.sauv_lieu_password.value="";
				f.sauv_lieu_host.value="";
			}
		}
	}
	return true;
}

</script>

<!-- Formulaire -->
<form name="sauv_lieux" action="admin.php?categ=sauvegarde&sub=lieux" method="post" onSubmit="return checkForm();">
<input type="hidden" name="act" value="show">
<input type="hidden" name="sauv_lieu_id" value="!!sauv_lieu_id!!">
<input type="hidden" name="first" value="1">
<table width=100%>
<th class="brd" colspan=2><center>!!quel_lieu!!: '.$msg["sauv_lieux_form_prop_general"].'</center></th>
<tr><td class="nobrd">'.$msg["sauv_lieux_form_nom"].'</td><td class="nobrd"><input type="text"  name="sauv_lieu_nom" value="!!sauv_lieu_nom!!" class="saisie-simple"></td></tr>
<tr><td class="nobrd">'.$msg["sauv_lieux_form_chemin"].'</td><td class="nobrd"><input type="text" name="sauv_lieu_url" value="!!sauv_lieu_url!!" size=50 class="saisie-simple"></td></tr>
<tr><td class="nobrd">'.$msg["sauv_lieux_form_protocole"].'</td><td class="nobrd">!!protocol_list!!</td></tr>
!!login!!
</table>
<!-- Boutons de soumission -->
<div class="left">
	<input type="submit" value="'.$msg["sauv_annuler"].'" onClick="this.form.act.value=\'cancel\'" class=\'bouton\'">
	<input type="submit" value="'.$msg["sauv_enregistrer"].'" onClick="this.form.act.value=\'update\'" class=\'bouton\'>&nbsp;
	</div>
<div class="right">
	!!delete!!
	</div>
<div class="row"></div>';
?>