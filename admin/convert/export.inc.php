<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.inc.php,v 1.7 2009-05-04 15:09:03 kantin Exp $

require_once($class_path."/export_param.class.php");

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

?>
<div>
<iframe name="ieexport" frameborder="0" scrolling="yes" width="100%" height="500" src="./admin/convert/export.php">
</div>
<noframes>
</noframes>