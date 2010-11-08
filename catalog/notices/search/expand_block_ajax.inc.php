<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expand_block_ajax.inc.php,v 1.7 2010-05-25 08:21:30 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// functions particulières à ce module
require_once("$class_path/mono_display.class.php");
require_once("$class_path/serial_display.class.php");

$cmd_tab=explode("|*|*|",$display_cmd);
foreach($cmd_tab as $cmd) {

	$html.=read_notice_contenu($cmd).'|*|*|';
}

ajax_http_send_response(substr($html,0,-5));

function read_notice_contenu($cmd) {
	global $msg,$categ,$id_empr;

	$param=unserialize(stripslashes($cmd));
	
	$selector_prop = "toolbar=no, dependent=yes, width=500, height=400, resizable=yes, scrollbars=yes";
	$cart_click = "onClick=\"openPopUp('./cart.php?object_type=NOTI&item=".$param['id']."', 'cart', 600, 700, -2, -2, '$selector_prop')\"";

	$current=$_SESSION["CURRENT"];
	if ($current!==false) {
		$print_action = "&nbsp;<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&notice_id=".$param['id']."&action_print=print_prepare','print',500,600,-2,-2,'scrollbars=yes,menubar=0'); w.focus(); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
	}		
	$categ=$param['categ'];
	$id_empr=$param['id_empr'];
					
	switch($param['function_to_call']) {
		case 'serial_display' :
			// on a affaire à un périodique
			// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", 
			//$lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true, $draggable=0 ) {
			$display = new serial_display($param['id'],6, $param['action_serial'], $param['action_analysis'], 
				$param['action_bulletin'], $param['lien_suppr_cart'], $param['$lien_explnum'],$param['bouton_explnum'],
				$param['print'],1,1, 1, 1);
			if(SESSrights & CATALOGAGE_AUTH){
				$display->result="	<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title=\"${msg[400]}\" $cart_click>$print_action !!serial_type!! !!ISBD!!";
			}else{
				$display->result="	$print_action !!serial_type!! !!ISBD!!";
			}
			$display->finalize();
			$html=$display->result;	
		break;
		case 'mono_display' :
			// on a affaire à un bulletin ou monographie
			$display = new mono_display($param['id'], 6, $param['action'], $param['expl'], 
				$param['expl_link'], $param['lien_suppr_cart'], $param['explnum_link'],1,
				$param['print'],1, 1, $param['anti_loop'], 1);	
			if(SESSrights & CATALOGAGE_AUTH){
				$display->result="	<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title=\"${msg[400]}\" $cart_click>$print_action !!ISBD!!";
			}else{
				$display->result="	$print_action !!ISBD!!";
			}
			$display->finalize();
			$html=$display->result;				
		break;
	}
	
	return $param['id'].'|*|'.$html;
}	


?>