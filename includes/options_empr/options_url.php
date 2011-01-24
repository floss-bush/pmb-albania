<?php
 // +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_url.php,v 1.1 2011-01-20 14:36:25 arenou Exp $

//Gestion des options de type text
$base_path = "../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include ($base_path."/includes/init.inc.php");

require_once ("$include_path/parser.inc.php");
require_once ("$include_path/fields_empr.inc.php");

$options = stripslashes($options);

//Si enregistrer
if ($first == 1) {
	$param["FOR"] = "url";
	$param[SIZE][0][value] = stripslashes($SIZE*1);
	$param[MAXSIZE][0][value] = stripslashes($MAXSIZE*1);
	$param[TIMEOUT][0][value] = stripslashes($TIMEOUT*1);
	$param[REPEATABLE][0][value] = $REPEATABLE ? 1 : 0;
	
	$options = array_to_xml($param, "OPTIONS");
	?> 
	<script>
	opener.document.formulaire.<?php  echo $name; ?>_options.value="<?php  echo str_replace("\n", "\\n", addslashes($options));?> ";
	opener.document.formulaire.<?php  echo $name; ?>_for.value="url";
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
	if ($param["FOR"] != "url") {
		$param = array();
		$param["FOR"] = "url";
	}
	//Formulaire
	?> 
	
	<form class='form-<?php echo $current_module ?>' name="formulaire" action="options_url.php" method="post">
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
	<tr><td><?php  echo $msg[procs_options_url_max];
	?> </td><td><input type="text" class='saisie-10em' name="MAXSIZE" value="<?php  echo htmlentities(
		$param[MAXSIZE][0][value],
		ENT_QUOTES,
		$charset);
	?>"></td></tr>
	<tr><td><?php  echo $msg[procs_options_url_timeout];
	?> </td><td><input type="text" class='saisie-10em' name="TIMEOUT" value="<?php  echo htmlentities(
		$param[TIMEOUT][0][value],
		ENT_QUOTES,
		$charset);
	?>"></td></tr>
	<tr><td><?php  echo $msg[persofield_urlrepeat];
	?> </td><td><input type="checkbox" name="REPEATABLE" <?php  echo $param[REPEATABLE][0][value] ? ' checked ' : "";
	?>></td></tr>
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