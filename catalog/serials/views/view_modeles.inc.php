<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: view_modeles.inc.php,v 1.1 2007-05-03 16:49:11 gueluneau Exp $

global $class_path;
global $include_path;

require_once($class_path."/abts_modeles.class.php");

$modeles=new abts_modeles($serial_id);
$bulletins=$modeles->show_list();
?>
