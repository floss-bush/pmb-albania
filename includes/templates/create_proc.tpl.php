<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: create_proc.tpl.php,v 1.3 2009-05-16 11:19:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$create_proc_form="
<form class='form-$current_module' name='search_form' action='!!url!!' method='post'>
	<h3>".$msg["menu_create_proc"]." (!!etape!!/5)</h3>
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette' for='add_field'>!!txtmsg!!</label> !!field_list!! <input type='button' class='bouton' value='".$msg["925"]."' onClick=\"this.form.action='!!url!!'; this.form.target=''; this.form.submit();\"/>
		</div>
		<br />
		<div class='row'>
			!!already_selected_fields!!
		</div>
	</div>
	<div class='row'>
		<input type='submit' class='bouton' value='".$msg["502"]."' onClick=\"this.form.etape.value=!!etape_next!!; this.form.action='!!url_next!!'; this.form.page.value=''; \"/>
	</div>
	<input type='hidden' name='etape' value='!!etape!!'/>
</form>";
?>