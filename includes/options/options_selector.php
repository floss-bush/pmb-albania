<?php
 // +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_selector.php,v 1.2 2009-05-16 11:05:14 dbellamy Exp $

//Gestion des options de type text
$base_path = "../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include ($base_path."/includes/init.inc.php");

require_once ("$include_path/parser.inc.php");
require_once ("$include_path/fields.inc.php");

$options = stripslashes($options);

//Si enregistrer
if ($first == 1) {
	$param["FOR"] = "selector";
	$param["METHOD"][0][value] = stripslashes($METHOD);
	$param["DATA_TYPE"][0][value] = $DATA_TYPE;

	$options = array_to_xml($param, "OPTIONS");
	
	print"
	<script>
	opener.document.formulaire.".$name."_options.value='".str_replace("\n", "\\n", addslashes($options)) ."';
	opener.document.formulaire.".$name."_for.value='selector';
	self.close();
	</script>
	";
	
} else {
// Création formulaire
   $param = _parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$options, "OPTIONS");
	if ($param["FOR"] != "selector") {
		$param = array();
		$param["FOR"] = "selector";
	}
	
	if($param["METHOD"]["0"]["value"])$method_checked[$param["METHOD"]["0"]["value"]]="checked";
	else $method_checked[1]="checked";
	$data_type_selected[$param["DATA_TYPE"]["0"]["value"]]="selected"; 
	
	//Formulaire	
	$form="
	<h3>".$msg[procs_options_param].$name."</h3><hr />
	<form class='form-$current_module' name='formulaire' action='options_selector.php' method='post'>
	<h3>".$type_list[$type]."</h3>
	<div class='form-contenu'>
	<input type='hidden' name='first' value='1'>
	<input type='hidden' name='name' value='".htmlentities(	$name,ENT_QUOTES,$charset)."'>
	<table class='table-no-border' width=100%>	
		<tr><td>".$msg['parperso_include_option_methode']."</td><td>
		<table width=100% valign='center'>
			<tr><td><center>".$msg['parperso_include_option_selectors_id']."
			<br />
			<input type='radio' name='METHOD' value='1' ".$method_checked[1].">
			</center></td>
			<td><center>".$msg['parperso_include_option_selectors_label']."
			<br />
			<input type='radio' name='METHOD' value='2' ".$method_checked[2].">
			</center></td></tr>
		</table></td></tr>
	
		<tr><td>".$msg['include_option_type_donnees']."
		</td><td><select name='DATA_TYPE'>
		<option value='1' ".$data_type_selected[1]." >".$msg['133']."</option>
		<option value='2' ".$data_type_selected[2]." >".$msg['134']."</option>
		<option value='3' ".$data_type_selected[3]." >".$msg['135']."</option>
		<option value='4' ".$data_type_selected[4]." >".$msg['136']."</option>
		<option value='5' ".$data_type_selected[5]." >".$msg['137']."</option>
		<option value='6' ".$data_type_selected[6]." >".$msg['333']."</option>
		<option value='7' ".$data_type_selected[7]." >".$msg['indexint_menu']."</option>
		</select></td></tr>

	</table>
	</div>
	<input class='bouton' type='submit' value='".$msg[77]."'>
	</form>
	</body>
	</html>
	";
	print $form;
}

