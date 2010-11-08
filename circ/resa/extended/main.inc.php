<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.2 2007-03-14 16:51:33 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/search.class.php");

$sc=new search();
$sc->link = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_notice=!!id!!";
$sc->link_expl = ''; 
$sc->link_explnum = '';
$sc->link_serial = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=view_serial&serial_id=!!id!!";
$sc->link_analysis = '';
$sc->link_bulletin = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=!!id!!";
$sc->link_explnum_serial='';

switch ($sub) {
	case "launch":
		$sc->show_results("./circ.php?categ=resa&mode=6&sub=launch&id_empr=$id_empr&groupID=$groupID","./circ.php?categ=resa&mode=6&id_empr=$id_empr&groupID=$groupID",true);
		break;
	default:
		print $sc->show_form("./circ.php?categ=resa&mode=6&id_empr=$id_empr&groupID=$groupID","./circ.php?categ=resa&mode=6&sub=launch&id_empr=$id_empr&groupID=$groupID");
		break;		
}
?>