<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: self_checkout.tpl.php,v 1.1 2010-08-17 12:32:56 ngantier Exp $

// templates pour gestion des autorités collections

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$form_self_checkout ="
	<script type='text/javascript'>		
		function test_form() {
			if(document.getElementById('cb_expl').value.length == 0) {
				alert('".$msg["empr_checkout_cb_empty"]."');
				document.getElementById('cb_expl').focus();
				return false;
			}			
			return true;
		}		
	</script>
			
	<form class='form-retour-expl' name='saisie_cb_ex' method='post' action='./empr.php?tab=loan&lvl=pret' onsubmit='return test_form()'>
	
		<div class='row'>	
			<label class='etiquette' for='cb_expl'>".$msg["empr_pret_cb_expl"]."</label>
		</div>
		<div class='row'>
			<input class='saisie-20em' id='cb_expl' name='cb_expl' value='' type='text'>
			<input type='submit' class='bouton' value='".$msg["empr_do_checkout_bt"]."' />	
		</div>
	</form>
	
	<script type='text/javascript'>	
		document.getElementById('cb_expl').focus();
	</script>	
";

$form_self_checkin ="
	<script type='text/javascript'>		
		function do_retour() {
			if(document.getElementById('cb_expl').value.length == 0) {
				alert('".$msg["empr_checkout_cb_empty"]."');
				document.getElementById('cb_expl').focus();
				return false;
			}
			return true;
		}		
	</script>
			
	<form class='form-retour-expl' name='saisie_cb_ex' method='post' action='./empr.php?tab=loan&lvl=retour' onsubmit='return test_form()'>
		<div class='row'>	
			<label class='etiquette' for='cb_expl'>".$msg["empr_pret_cb_expl"]."</label>
		</div>
		<div class='row'>
			<input class='saisie-20em' id='cb_expl' name='cb_expl' value='' type='text'>
			<input type='submit' class='bouton' value='".$msg["empr_do_checkin_bt"]."' />	
		</div>
	</form>
		
	<script type='text/javascript'>	
		document.getElementById('cb_expl').focus();
	</script>	
";

?>