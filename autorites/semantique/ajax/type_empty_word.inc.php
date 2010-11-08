<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: type_empty_word.inc.php,v 1.2 2007-10-26 10:34:58 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");
require_once("$class_path/semantique.class.php");

function ajax_modify_type_empty_word() {
	global $id_mot,$type_lien;
	@mysql_query("update linked_mots set type_lien=".$type_lien." where num_mot=".$id_mot);	
	
	
	semantique::gen_table_empty_word();
	
	ajax_http_send_response("1","text/text");
	
	return;
}

switch ($fname) {
	case "modify_type_empty_word":
		ajax_modify_type_empty_word();
		break;
	default:
		ajax_http_send_error("404 Not Found","Invalid command : ".$fname);
		break;
}
?>
