<?php
 // +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_date_box.php,v 1.5 2007-03-10 09:46:47 touraine37 Exp $

//Gestion des options de type text
$base_path = "../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include ($base_path."/includes/init.inc.php");
require_once("$include_path/fields.inc.php");

require_once ("$include_path/parser.inc.php");

$param["FOR"]="date_box";
$options = array_to_xml($param, "OPTIONS");

?> 
<script>
opener.document.formulaire.<?php  echo $name; ?>_options.value="<?php  echo str_replace("\n", "\\n", addslashes($options));?> ";
opener.document.formulaire.<?php  echo $name; ?>_for.value="date_box";
alert("<?php echo $msg["proc_param_date_options"]; ?>");
self.close();
</script>
</body>
</html>