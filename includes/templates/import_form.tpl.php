<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import_form.tpl.php,v 1.7 2009-05-16 11:19:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// template pour le formulaire d'import
$form="
<form class='form-$current_module' name=\"import_form\" action=\"start_import.php?bidon=1\" method=\"post\" enctype=\"multipart/form-data\">
<h3>".$msg["ie_import_running"]."</h3>
<div class='form-contenu'>
<div class='row'>
	<div class='colonne3'>
		<label class='etiquette'>".$msg["ie_file_to_import"]." :</label>
		</div>
	<div class='colonne_suite'>
		<input type=\"file\" name=\"import_file\" class='saisie-80em'>
		</div>
	</div>
	<br />
<div class='row'>
$msg[ie_import_msg1]<br />
$msg[ie_import_msg2]<br />
$msg[ie_import_msg3]<br />
$msg[ie_import_msg4]

</div>
<br />
<div class='row'>
	<div class='colonne3'>
		<label class='etiquette'>$msg[ie_import_TypConversion]</label>
		</div>
	<div class='colonne_suite'>
		!!import_type!!
		</div>
	</div>
<div class='row'> </div>
	</div>
<div class='row'>
	<input type=\"submit\" class='bouton' value=\"".$msg["ie_import_start"]."\">
	</div>
</form>
";
