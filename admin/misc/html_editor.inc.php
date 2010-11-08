<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: html_editor.inc.php,v 1.2 2008-08-29 09:58:37 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if ($pmb_javascript_office_editor) {
	print $pmb_javascript_office_editor ;
}

print $admin_layout;
print stripslashes($f_message)."
<form class='form-$current_module' method='post' name='form_message' id='form_message' action='./admin.php?categ=html_editor' />
<h3>".$msg[admin_html_editor]."</h3>
<div class='form-contenu'>
	<div class='row'>					
		<textarea id='f_message' name='f_message' cols='120' rows='40'>".stripslashes($f_message)."</textarea>
	</div>
</div>
</form>" ;
