<?php
 // +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_date_box.php,v 1.8 2008-11-27 08:14:46 kantin Exp $

//Gestion des options de type text
$base_path = "../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include ($base_path."/includes/init.inc.php");
require_once("$include_path/fields_empr.inc.php");

require_once ("$include_path/parser.inc.php");

$options = stripslashes($options);

if ($first == 1) {
	$param["FOR"]="date_box";
	if ($DEFAULT_TODAY) $param["DEFAULT_TODAY"][0]["value"]="yes";
	$options = array_to_xml($param, "OPTIONS");
?> 
<script>
opener.document.formulaire.<?php  echo $name; ?>_options.value="<?php  echo str_replace("\n", "\\n", addslashes($options));?> ";
opener.document.formulaire.<?php  echo $name; ?>_for.value="date_box";
//alert("<?php echo $msg["proc_param_date_options"]; ?>")
self.close();
</script>
<?php } else {
	if($options){
		$param = _parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$options, "OPTIONS");
	}
	if ($param["FOR"] != "date_box") {
		$param = array();
		$param["FOR"] = "date_box";
	}
?>
<form class='form-<?php echo $current_module ?>' name="formulaire" action="options_date_box.php" method="post">
	<h3><?php  echo $type_list_empr[$type];
	?> </h3>
	<div class='form-contenu'>
	<input type="hidden" name="first" value="1">
	<input type="hidden" name="name" value="<?php  echo htmlentities(
		$name,
		ENT_QUOTES,
		$charset);
	?>">
	<!-- Formulaire -->
	<table class='table-no-border' width=100%>
	<tr><td><?php echo $msg["parperso_default_today"]; ?> </td><td><input type="checkbox" name="DEFAULT_TODAY" value="yes" <?php if ($param["DEFAULT_TODAY"][0]["value"]=="yes") echo "checked"; ?>></td></tr>
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