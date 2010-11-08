<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: tarif_prets.inc.php,v 1.2 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Gestion des tarifs de prêt

require_once($class_path."/quotas.class.php");

$menu_sous_rub=$msg["finance_prets"];
$qt=new quota("COST_LEND_QUOTA","$include_path/quotas/own/$lang/finances.xml");
if ($elements) {
	$menu_sous_rub.=" > ".$qt->get_title_by_elements_id($elements);
}
$admin_layout = str_replace('!!menu_sous_rub!!', $menu_sous_rub, $admin_layout);
print $admin_layout;

if (!$elements)
	include("./admin/quotas/quotas_list.inc.php");
else
	include("./admin/quotas/quota_table.inc.php");

?>