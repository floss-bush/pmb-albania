<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options.php,v 1.6 2007-03-10 09:46:47 touraine37 Exp $

$base_path="../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include($base_path."/includes/init.inc.php");

require_once("$include_path/fields.inc.php");

echo "<form class='form-$current_module' name=\"formulaire\" method=\"post\" action=\"".$options_list[$type]."\">";
echo "<input type=\"hidden\" name=\"name\" value=\"".stripslashes($name)."\">";
echo "<input type=\"hidden\" name=\"type\" value=\"".stripslashes($type)."\">";
echo "<input type=\"hidden\" name=\"options\" value=\"\">";
echo "</form>";
?>
<script>
document.formulaire.options.value=opener.document.formulaire.<?php echo $name; ?>_options.value;
document.formulaire.submit();
</script>
</body>
</html>