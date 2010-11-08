<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pclass.tpl.php,v 1.5 2009-05-16 11:19:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// templates pour la gestion des plans de classements 

//Template du browser
$browser = "
<div class='row'>
	<h3>&nbsp;".$msg[pclassement_liste]."</h3>
</div>
<br />
<br />
<div class='row'>
	<table border='0'>
			!!browser_content!!
	</table>
</div>
<br />
<br />
<div class='row'>
	<input class='bouton' type='button' value='".$msg[pclassement_ajouter]."' onclick = \"document.location = '!!action!!' \" />
</div>
";

//Template du formulaire
$pclassement_form = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(document.getElementById('libelle').value.length == 0)
			{
				var msg = \"".$msg[pclassement_libelle_manquant]."\";
				alert(msg);
	           	document.forms['pclass'].elements['libelle'].focus();
			return false;
			}
		return true;
	}
	
function confirm_delete() {
        result = confirm(\"".$msg[confirm_suppr]."\");
        if(result)
            document.location='!!delete_url!!';
        else
            document.forms['pclass'].elements['libelle'].focus();
    }
-->
</script>

<form class='form-".$current_module."' id='pclass' name='pclass' method='post' action='!!update_url!!'>
	<h3>!!form_title!!</h3>
	<div class='form-contenu'>

		<!-- identifiant -->
		!!identifiant!!
		
		<!-- libelle -->
		<div class='row'>
			<label class='etiquette' >".$msg[103]."</label><label class='etiquette'></label>
		</div>
		<div class='row'>
			<input type='text' class='saisie-80em' id='libelle' name='libelle' value=\"!!libelle!!\" />
		</div>

		<!-- langue defaut -->
		<div class='row'>
			<label class='etiquette' >".$msg[pclassement_type_doc_titre]."</label><label class='etiquette'></label>
		</div>
		<div class='row'>
			!!type_doc!! 
		</div>
	</div>


	<!--	boutons	-->
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg[76]."' onClick=\"document.location='!!cancel_url!!'\" />
			<input type='submit' class='bouton' value='".$msg[77]."' onClick=\"return test_form(this.form)\" />
		</div>
		<div class='right'>
			!!delete_button!!
		</div>
	</div>
	<div class='row'>
	</div>	

</form>

<script type='text/javascript'>
	document.forms['pclass'].elements['libelle'].focus();
</script>
";
