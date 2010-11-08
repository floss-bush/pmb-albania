<?php
 // +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_external.php,v 1.2 2009-05-16 11:05:14 dbellamy Exp $

//Gestion des options de type text
$base_path = "../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include ($base_path."/includes/init.inc.php");

require_once ("$include_path/parser.inc.php");
require_once ("$include_path/fields_empr.inc.php");

$options = stripslashes($options);

//Si enregistrer
if ($first == 1) {
	$param["FOR"] = "external";
	$param["URL"][0][value] = stripslashes($URL);
	$param["HIDE"][0][value] = stripslashes($HIDE);
	$param["DELETE"][0][value] = stripslashes($DELETE);
	$param["BUTTONTEXT"][0][value] = stripslashes($BUTTONTEXT);
	$param["WIDTH"][0][value] = stripslashes($WIDTH);
	$param["HEIGHT"][0][value] = stripslashes($HEIGHT);
	$param["SIZE"][0][value] = stripslashes($SIZE*1);
	$param["MAXSIZE"][0][value] = stripslashes($MAXSIZE*1);
	$param["QUERY"][0][value]=stripslashes($QUERY);
	
	$options = array_to_xml($param, "OPTIONS");
	?> 
	<script>
	opener.document.formulaire.<?php  echo $name; ?>_options.value="<?php  echo str_replace("\n", "\\n", addslashes($options));?> ";
	opener.document.formulaire.<?php  echo $name; ?>_for.value="external";
	self.close();
	</script>
	<?php
	 } else {
	?> 
	<h3><?php  echo $msg[procs_options_param].$name;
	?> </h3><hr />
	
	<?php
	if (!$options) $options = "<OPTIONS></OPTIONS>";
	 $param = _parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$options, "OPTIONS");
	if ($param["FOR"] != "external") {
		$param = array();
		$param["FOR"] = "external";
	}
	//Formulaire
	?> 
	
	<form class='form-<?php echo $current_module ?>' name="formulaire" action="options_external.php" method="post">
	<h3><?php  echo $type_list_empr[$type];
	?> </h3>
	<div class='form-contenu'>
	<input type="hidden" name="first" value="1">
	<input type="hidden" name="name" value="<?php  echo htmlentities(
		$name,
		ENT_QUOTES,
		$charset);
	?>">
	<table class='table-no-border' width=100%>
	<tr><td><?php  echo $msg["parperso_options_external_url"];
	?> </td><td><input class='saisie-40em' type="text" name="URL" value="<?php  echo htmlentities(
		$param["URL"][0][value],
		ENT_QUOTES,
		$charset);
	?>"></td></tr>
	<tr><td><?php  echo $msg["parperso_options_external_hide"];
	?> </td><td><select name="HIDE" value="<?php  echo htmlentities(
		$param["HIDE"][0][value],
		ENT_QUOTES,
		$charset);
	?>"><option value='0' <?php if (!$param["HIDE"][0][value]) echo "selected"; ?>><?php echo $msg["parperso_external_no"] ?></option>
	<option value='1' <?php if ($param["HIDE"][0][value]) echo "selected"; ?>><?php echo $msg["parperso_external_yes"]; ?></option>
	</select></td></tr>
	<tr><td><?php  echo $msg["parperso_options_external_del"];
	?> </td><td><select name="DELETE" value="<?php  echo htmlentities(
		$param["DELETE"][0][value],
		ENT_QUOTES,
		$charset);
	?>"><option value='0' <?php if (!$param["DELETE"][0][value]) echo "selected"; ?>>Non</option>
	<option value='1' <?php if ($param["DELETE"][0][value]) echo "selected"; ?>>Oui</option>
	</select></td></tr>
	
	<tr><td><?php  echo $msg["parperso_options_external_button"];
	?> </td><td><input class='saisie-40em' type="text" name="BUTTONTEXT" value="<?php  echo htmlentities(
		$param["BUTTONTEXT"][0][value],
		ENT_QUOTES,
		$charset);
	?>"></td></tr>
	
	<tr><td><?php  echo $msg["parperso_options_external_width"];
	?> </td><td><input class='saisie-10em' type="text" name="WIDTH" value="<?php  echo htmlentities(
		$param["WIDTH"][0][value],
		ENT_QUOTES,
		$charset);
	?>"></td></tr>
	
	<tr><td><?php  echo $msg["parperso_options_external_height"];
	?> </td><td><input class='saisie-10em' type="text" name="HEIGHT" value="<?php  echo htmlentities(
		$param["HEIGHT"][0][value],
		ENT_QUOTES,
		$charset);
	?>"></td></tr>
	
	<tr><td><?php  echo $msg[procs_options_text_taille];
	?> </td><td><input class='saisie-10em' type="text" name="SIZE" value="<?php  echo htmlentities(
		$param["SIZE"][0][value],
		ENT_QUOTES,
		$charset);
	?>"></td></tr>
	<tr><td><?php  echo $msg[procs_options_text_max];
	?> </td><td><input type="text" class='saisie-10em' name="MAXSIZE" value="<?php  echo htmlentities(
		$param["MAXSIZE"][0][value],
		ENT_QUOTES,
		$charset);
	?>"></td></tr>
	
	<tr><td><?php  echo $msg["parperso_options_external_query"];
	?> </td><td><textarea cols=50 rows=5 wrap=virtual name="QUERY"><?php  echo htmlentities(
		$param["QUERY"][0][value],
		ENT_QUOTES,
		$charset);
	?></textarea></td></tr>
	</table>
	</div>
	<input class="bouton" type="submit" value="<?php  echo $msg[77];
	?>">
	</form>
	<?php
	 }
?>
</body>
</html>