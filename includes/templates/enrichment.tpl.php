<?php
// +-------------------------------------------------+
// | 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: enrichment.tpl.php,v 1.1 2011-04-15 15:16:01 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");



$admin_enrichment_form="
<form class='form-$current_module' id='enrichment' name='enrichment' method='post' action='./admin.php?categ=connecteurs&sub=enrichment&action=update'>
	<h3>".$msg['admin_connecteurs_enrichment_def']."</h3>
	<div class='form-contenu'>
	!!table!!
	</div>
	<div class='row'>
		<input type='button' value='Enregistrer' class='bouton' onClick='this.form.submit()'/>
	</div>
</form>
<script type='text/javascript' src='javascript/tablist.js'></script>";
?>