<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: folders.inc.php,v 1.1 2009-07-03 09:35:36 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/upload_folder.class.php");

$upload_folder = new upload_folder($id,$action);
$upload_folder->proceed();

?>