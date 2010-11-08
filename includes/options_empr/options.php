<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options.php,v 1.7 2007-03-10 09:46:47 touraine37 Exp $

$base_path="../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include($base_path."/includes/init.inc.php");

require_once("$include_path/fields_empr.inc.php");

echo "<form class='form-$current_module' name=\"formulaire\" method=\"post\" action=\"".$options_list_empr[$type]."\">";
echo "<input type=\"hidden\" name=\"name\" value=\"".stripslashes($name)."\">";
echo "<input type=\"hidden\" name=\"type\" value=\"".stripslashes($type)."\">";
echo "<input type=\"hidden\" name=\"options\" value=\"\">";
echo "<input type=\"hidden\" name=\"idchamp\" value=\"\">";
echo "<input type=\"hidden\" name=\"_custom_prefixe_\" value=\"".stripslashes($_custom_prefixe_)."\">";
echo "</form>";
?>
<script>
document.formulaire.options.value=opener.document.formulaire.<?php echo $name; ?>_options.value;
document.formulaire.idchamp.value=opener.document.formulaire.idchamp.value;
document.formulaire.submit();
</script>
</body>
</html>