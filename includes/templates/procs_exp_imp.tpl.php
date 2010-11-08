<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: procs_exp_imp.tpl.php,v 1.3 2008-08-08 15:56:18 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$import_proc_tmpl = "
<form class='form-$current_module' ENCTYPE='multipart/form-data' name='fileform' method='post' action='!!action!!' >
<h3>".$msg[procs_title_form_import]."</h3>
<div class='form-contenu' >
	<div class='row'>
		<label class='etiquette' for='explnum_nom'>".$msg[procs_file_import]."</label>
		</div>
	<div class='row'>
		<INPUT NAME='f_fichier' 'saisie-80em' TYPE='file' size='60'>
		</div>
	</div>
<input type='submit' class='bouton' value=' ".$msg[procs_bt_import]." ' />
</form>" ;
