<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cbgenlibre.inc.php,v 1.4 2009-05-16 11:11:51 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// $cbgen_query : form de demande d'info pour génération

$cbgen_query = "

<form class='form-$current_module' id = 'cbgen_query' name='cbgen_query' method='post' enctype='multipart/form-data' action='edit/generate.php' >
<div class='form-contenu'>

	<div class='row'>
		<label class='etiquette'>Format étiquette</label>
		<select id='etiq_fmt' name='etiq_fmt' size='1'>
  			<option value='1'>format1</option>
  			<option value='2'>format2</option>
			<option value='3'>format3</option>
		</select> 
	</div>
	
	<br />


	<div class='row'>
		<label class='etiquette' for='etiq_num' >Commencer à l'étiquette n°</label>
		<input id='etiq_num' name='etiq_num' class='saisie-2em' />
	</div>



</div>
<div class='row'>
	<input class='bouton' type='submit' value='$msg[804]' />
	</div>
</form>


";

print $cbgen_query;

?>
