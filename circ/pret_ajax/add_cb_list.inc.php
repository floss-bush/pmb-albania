<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: add_cb_list.inc.php,v 1.1 2010-06-16 12:19:04 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/ajax_pret.class.php");
require_once("$include_path/ajax.inc.php");

//input: cb_list, id_empr;

if($del_pret){
	$query = "delete from pret where pret_idempr = '" . $id_empr . "' and pret_temp = '".$_SERVER['REMOTE_ADDR']."'";
	mysql_query($query);		
}

foreach($cb_list as $cb_doc){
	// init de la class
	$info_1=array();
	$info_2=array();
	$pret = new do_pret();
	$info_1 = $pret->mode1_get_info_expl($cb_doc);
	if($info_1["error_message"]) $erreur=1;
	$id_expl=$info_1["expl_id"];
	$forcage=$force[$cb_doc];
	if(!$erreur) $info_2 = $pret->mode1_check_pieges($cb_empr, $id_empr,$cb_doc, $id_expl,$forcage);
	if($info_2["error_message"]) $erreur=1;
	$result[] = array_merge($info_1, $info_2);
}
		
ajax_http_send_response(array2xml($result),"text/xml");

?>