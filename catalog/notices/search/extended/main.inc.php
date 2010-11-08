<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.12 2009-11-30 10:39:25 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/search.class.php");

$sc=new search(true);

$sc->link = './catalog.php?categ=isbd&id=!!id!!';
$sc->link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
$sc->link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!';
$sc->link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
$sc->link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
$sc->link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
$sc->link_explnum_serial = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";

switch ($sub) {
	case "launch":
		if ((string)$page=="") {
			$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["URI"]="./catalog.php?categ=search&mode=6";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"]=$_POST;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["GET"]=$_GET;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["GET"]["sub"]="";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"]["sub"]="";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$sc->make_human_query();
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=$msg["search_extended"];
			$_POST["page"]=0;
			$page=0;
		}
		$sc->show_results("./catalog.php?categ=search&mode=6&sub=launch","./catalog.php?categ=search&mode=6", true, '', true );
		if ($_SESSION["CURRENT"]!==false) {
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["URI"]="./catalog.php?categ=search&mode=6&sub=launch";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["POST"]=$_POST;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["GET"]=$_GET;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["PAGE"]=$page+1;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["HUMAN_QUERY"]=$sc->make_human_query();
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["SEARCH_TYPE"]="extended";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["TEXT_QUERY"]="";
		}
		break;
	default:
		print $sc->show_form("./catalog.php?categ=search&mode=6","./catalog.php?categ=search&mode=6&sub=launch");
		break;		
}

?>