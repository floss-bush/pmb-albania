<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: thesaurus.tpl.php,v 1.9 2009-12-18 11:18:25 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// templates pour la gestion des thesaurus


// $thes_browser : template du browser de thesaurus
$thes_browser = "
<div class='row'>
	<h3>&nbsp;".$msg[thes_liste]."</h3>
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
	<input class='bouton' type='button' value='".$msg[thes_ajouter]."' onclick = \"document.location = '!!action!!' \" />
</div>

";


// $thes_form : template du form de thesaurus
$thes_form = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(document.getElementById('libelle_thesaurus').value.length == 0)
			{
				var msg = \"".$msg[thes_libelle_manquant]."\";
				alert(msg);
	           	document.forms['thes_form'].elements['libelle_thesaurus'].focus();
			return false;
			}
		return true;
	}
	
function confirm_delete() {
		has_categ='!!thesaurus_as_categ!!';
        result = confirm(\"".$msg[confirm_suppr]."\");
        if(result){
        	if(has_categ == 'oui'){
        		if(confirm(\"".$msg[supp_thes_avec_categ]."\")){
        			document.location='!!delete_url!!';
        		}else{
        			document.forms['thes_form'].elements['libelle_thesaurus'].focus();
        		}
        	}else{
        		document.location='!!delete_url!!';
        	}
        }else {
        	document.forms['thes_form'].elements['libelle_thesaurus'].focus();
        }     
    }
-->
</script>

<form class='form-".$current_module."' id='thes_form' name='thes_form' method='post' action='!!update_url!!'>
	<h3>!!form_title!!</h3>
	<div class='form-contenu'>

		<!-- identifiant thesaurus -->
		!!identifiant_thesaurus!!
		
		<!-- libelle thesaurus -->
		<div class='row'>
			<label class='etiquette' >".$msg[103]."</label><label class='etiquette'></label>
		</div>
		<div class='row'>
			<input type='text' class='saisie-80em' id='libelle_thesaurus' name='libelle_thesaurus' value=\"!!libelle_thesaurus!!\" />
		</div>

		<!-- langue defaut -->
		<div class='row'>
			<label class='etiquette' >".$msg[thes_langue_defaut]."</label><label class='etiquette'></label>
		</div>
		<div class='row'>
			!!langue_defaut!! 
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
	document.forms['thes_form'].elements['libelle_thesaurus'].focus();
</script>
";
