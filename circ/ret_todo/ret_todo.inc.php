<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ret_todo.inc.php,v 1.2 2010-03-31 14:14:09 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");
require_once("$class_path/expl_to_do.class.php");

$url="./circ.php?categ=ret_todo";
$expl=new expl_to_do($form_cb_expl,0,$url);
$expl->build_cb_tmpl($msg["alert_circ_retour"]." > ".$msg["alert_circ_retour_todo"], $msg[661], $msg["circ_tit_form_cb_expl"], $url, 1);
if($form_cb_expl){
	$expl->do_form_retour($action_piege,$piege_resa);	
}
print $expl->cb_tmpl.$expl->expl_form.$expl->gen_liste(); 



