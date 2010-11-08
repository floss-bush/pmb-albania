<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.1 2008-10-02 11:35:19 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/acces.class.php");

//recuperation de la liste des domaines d'acces
$ac = new acces();
$t_cat= $ac->getCatalog();

switch ($sub) {

	case 'domain' :
		require_once("./admin/acces/domain.inc.php");
		break;
	case 'user_prf' :
		require_once("./admin/acces/user_prf.inc.php");
		break;
	case 'res_prf' :
		require_once("./admin/acces/res_prf.inc.php");
		break;
	default :	

		echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);
		//construction menu
		$admin_menu_acces = "<h1>".htmlentities($msg["admin_menu_acces"], ENT_QUOTES, $charset)."<span>&nbsp;&gt;&nbsp;!!menu_sous_rub!!</span></h1>";
		$admin_menu_acces.= "<div class='hmenu'>";
		foreach($t_cat as $k=>$v) {
			$lib=htmlentities($v['comment'], ENT_QUOTES, $charset);
			$admin_menu_acces.= "<span><a href='./admin.php?categ=acces&sub=domain&action=view&id=".$k."'>$lib</a></span>";
		}
		unset($v);
		$admin_menu_acces.= "</div>";
		$admin_menu_acces=str_replace('!!menu_sous_rub!!','', $admin_menu_acces);
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_acces, $admin_layout);
		print $admin_layout;

		require_once("$include_path/messages/help/$lang/admin_acces.txt");
		break;
}			
?>