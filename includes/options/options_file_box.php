<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_file_box.php,v 1.10 2009-05-16 11:05:14 dbellamy Exp $

//Gestion des options de type text
$base_path = "../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include ($base_path."/includes/init.inc.php");

require_once ("$include_path/parser.inc.php");
require_once ("$include_path/fields.inc.php");

$options = stripslashes($options);

//Si enregistrer
if ($first == 1) {
	$param["FOR"] = "file_box";
	$param["METHOD"][0][value] = stripslashes($METHOD);
	$param["TEMP_TABLE_NAME"][0][value] = stripslashes($TEMP_TABLE_NAME);
	$param["DATA_TYPE"][0][value] = $DATA_TYPE;
	$options = array_to_xml($param, "OPTIONS");
	?> 
	<script>
	opener.document.formulaire.<?php  echo $name; ?>_options.value="<?php  echo str_replace("\n", "\\n", addslashes($options));?> ";
	opener.document.formulaire.<?php  echo $name; ?>_for.value="file_box";
	self.close();
	</script>
	<?php
	 } else {
	?> 
	<h3><?php  echo $msg[procs_options_param].$name;
	?> </h3><hr />
	
	<?php
	 $param = _parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$options, "OPTIONS");
	if ($param["FOR"] != "file_box") {
		$param = array();
		$param["FOR"] = "file_box";
	}
	
	//Formulaire
	?> 
	
	<form class='form-<?php echo $current_module ?>' name="formulaire" action="options_file_box.php" method="post">
	<h3><?php  echo $type_list[$type];
	?> </h3>
	<div class='form-contenu'>
	<input type="hidden" name="first" value="1">
	<input type="hidden" name="name" value="<?php  echo htmlentities(
		$name,
		ENT_QUOTES,
		$charset);
	?>">
	<table class='table-no-border' width=100%>
	<tr><td><?php  echo $msg['include_option_methode']; ?> </td><td><table width=100% valign="center"><tr><td><center>Liste <br /><input type="radio" name="METHOD" value="1" <?php if ($param["METHOD"]["0"]["value"]==1) echo "checked";?>></center></td><td><center><?php echo $msg['include_option_table']; ?><br /><input type="radio" name="METHOD" value="2" <?php if ($param["METHOD"]["0"]["value"]==2) echo "checked"; ?>></center></td></tr></table></td></tr>
	<tr><td><?php  echo $msg['include_option_nom_table']; ?></td><td><input type="text" class='saisie-10em' name="TEMP_TABLE_NAME" value="<?php  echo htmlentities($param["TEMP_TABLE_NAME"][0][value],ENT_QUOTES,$charset);?>"></td></tr>
	<tr><td><?php  echo $msg['include_option_type_donnees']; ?></td><td><select name="DATA_TYPE"><option value="1" <?php if ($param["DATA_TYPE"][0][value]==1) echo "selected"; ?>><?php echo $msg['include_option_chaine']; ?></option><option value="2" <?php if ($param["DATA_TYPE"][0][value]==2) echo "selected"; ?>><?php echo $msg['include_option_entier']; ?></option></select></td></tr>
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