<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: visu_rech.inc.php,v 1.9 2010-05-25 08:21:30 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de switch recherche notice

require_once("$include_path/templates/catalog.tpl.php");
require_once("$include_path/isbn.inc.php");
require_once("$include_path/marc_tables/$lang/empty_words");
require_once("$class_path/marc_table.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/category.class.php");
require_once("$class_path/notice.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/serials.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/expl.class.php");
require_once("$class_path/explnum.class.php");

// inclusions principales
require_once("$include_path/templates/resa.tpl.php");
require_once("$class_path/searcher.class.php");

// gestion des liens en rech resa ou pas 

//Lien pour l'affichage
$link = "./catalog.php?categ=isbd&id=!!id!!" ;	
$link_analysis="./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!";
$link_serial="./catalog.php?categ=serials&sub=view&serial_id=!!id!!";
$link_bulletin="./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!";
$link_explnum_serial="";
$link_expl="./catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!";		

$base_url = "./circ.php?categ=visu_rech";

print str_replace("!!mode_recherche!!", $msg[354], $menu_search_visu_rech);
switch($mode) {
	case 'view_serial'://Ce cas n'est plus possible depuis le 19/05/2010
		// affichage de la liste des éléments bulletinés pour un périodique
		include('./circ/resa/view_serial.inc.php');
		break;
	default :
		if ($ex_query) {
			$back_to_visu=1;
			include('./circ/visu_ex.inc.php');
		} else{
			$sh=new searcher_title($base_url);
		}
		break;
}
