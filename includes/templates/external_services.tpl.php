<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: external_services.tpl.php,v 1.1 2009-07-15 08:01:02 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//Administration générale
$es_admin_general="
<form class='form-$current_module' id='es_rights' name='es_rights' method='post' action='./admin.php?categ=external_services&sub=general'>
	<h3>Définition des droits pour les groupes et les méthodes</h3>
	<div class='form-contenu'>
	<input type='hidden' name='is_not_first' value='1'/>
	!!table_rights!!
	</div>
	<div class='row'>
		<input type='button' value='Annuler' class='bouton' onClick='document.location=\"admin.php?categ=external_services\"'/>&nbsp;
		<input type='button' value='Enregistrer' class='bouton' onClick='this.form.submit()'/>
	</div>
</form>";

//Par utilisateur
$es_admin_peruser="
<form class='form-$current_module' id='es_rights' name='es_rights' method='post' action='./admin.php?categ=external_services&sub=peruser'>
	<h3>Définition des droits pour l'utilisateur !!user!!</h3>
	<div class='form-contenu'>
	<input type='hidden' name='is_not_first' value=''/>
	!!table_rights!!
	</div>
	<div class='row'>
		<input type='button' value='Annuler' class='bouton' onClick='document.location=\"admin.php?categ=external_services\"'/>&nbsp;
		<input type='button' value='Enregistrer' class='bouton' onClick='this.form.is_not_first.value=1; this.form.submit()'/>
	</div>
</form>";
?>