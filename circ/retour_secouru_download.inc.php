<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: retour_secouru_download.inc.php,v 1.3 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

print "
<h1>".$msg['secouru_tit']."</h1>
<form name='form_secouru' class='form-$current_module' action='circ.php?categ=retour_secouru_int' enctype='multipart/form-data' method='post'>
	<h3>".$msg['secouru_tit_form']."</h3>
	<div class='form-contenu'>
	<div class='row'><label class='etiquette' for='fichier_secouru'>".$msg['secouru_tit_input']."</label></div>
	<div class='row'><input type='file' name='fichier_secouru' size='50'/></div>
	</div>
	<input type='submit' value=\"".$msg['secouru_bt_envoyer']."\" class='bouton'/>
</form>";

?>