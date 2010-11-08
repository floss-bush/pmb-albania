<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sauvegarde_list.inc.php,v 1.5 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Page des gestion des sauvegardes lancées
require_once ($class_path."/sauvegarde_list.class.php");

$sauv = new sauvegarde_list();

echo $sauv->proceed();

?>