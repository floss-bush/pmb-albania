<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: visu_message.php,v 1.3 2008-04-01 06:58:02 touraine37 Exp $

$base_path="../../..";
$base_nobody = 1;
include($base_path."/includes/init.inc.php");

echo stripslashes($_POST["f_message"]) ;

?>