<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.11 2008-06-27 16:01:42 jokester Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/quotas.class.php");

//Parse des quotas possibles
if ($sub) $qt=new quota($sub); else quota::parse_quotas();

//Génération dynamique du menu
$admin_menu_quotas = "
<h1>".$msg["admin_quotas"]." <span>> !!menu_sous_rub!!</span></h1>\n
<div class=\"hmenu\">";
for ($i=0; $i<count($_quotas_types_); $i++) {	
	$admin_menu_quotas.="<span".ongletSelect("categ=quotas&sub=".$_quotas_types_[$i]["ID"])."><a href='./admin.php?categ=quotas&sub=".$_quotas_types_[$i]["ID"]."'>".$_quotas_types_[$i]["SHORT_COMMENT"]."</a></span>\n";
	if ($sub==$_quotas_types_[$i]["ID"]) {
		$menu_sous_rub=$_quotas_types_[$i]["SHORT_COMMENT"];
		if ($elements) $menu_sous_rub.=" > ".$qt->get_title_by_elements_id($elements);
	}
	if($pmb_pret_restriction_prolongation!=2 && $i==1) $i=3;
}
$admin_menu_quotas.="
</div>
";
$admin_menu_quotas=str_replace("!!menu_sous_rub!!",$menu_sous_rub,$admin_menu_quotas);
$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_quotas, $admin_layout);

print $admin_layout;

if ($quota) 
	include("./admin/quotas/quota_test.inc.php");
else {

	switch ($sub) {
		case "":
			break;
		default:
			if (!$elements)
				include("./admin/quotas/quotas_list.inc.php");
			else
				include("./admin/quotas/quota_table.inc.php");
			break;
	}
}
?>