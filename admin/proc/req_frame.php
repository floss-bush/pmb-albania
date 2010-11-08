<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: req_frame.php,v 1.3 2009-06-25 16:33:21 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// définition du minimum nécéssaire
$base_path="../..";
$base_auth = "ADMINISTRATION_AUTH";
$base_title = "\$msg[7]";    
$include_path = "$base_path/includes";
$class_path = "$base_path/classes";

require_once ("$base_path/includes/init.inc.php");
print "<div id='att' style='z-Index:1000'></div>";

require_once ($class_path.'/request.class.php');  
require_once ($class_path.'/requester.class.php');
require_once ($include_path.'/templates/requests.tpl.php');

$rqt = new requester();

function show_frame_req_add_form() {
	
	global $msg;
	global $rqt;
	global $frame_req_add_form;
	global $req_type,$req_univ;
	global $req_tab_header_select,$req_tab_header_insert,$req_tab_header_update,$req_tab_header_delete;
//	global $req_tab_line_select,$req_tab_line_insert,$req_tab_line_update,$req_tab_line_delete;
	global $joi_tab_header_select,$joi_tab_header_insert,$joi_tab_header_update,$joi_tab_header_delete;
//	global $joi_tab_line_select,$joi_tab_line_insert,$joi_tab_line_update,$joi_tab_line_delete;
	global $lim_tab_header_select,$lim_tab_header_delete;
	global $lim_tab_line_select,$lim_tab_line_delete;
	
	
	$form_title = $msg['req_form_tit_add'];

	$frame_req_add_form = str_replace('!!form_title!!', $form_title, $frame_req_add_form);
	$req_tree_script='';
	
	$req_tab_header='';
	$req_tab_lines='';
	$joi_tab_header='';
	$joi_tab_lines='';
	$lim_tab_header='';
//	$lim_tab_lines='';
	$req_order='';
	
	
	switch ($req_type) {
		
		case '2' :	//select
			$req_tab_header=$req_tab_header_select;
			$joi_tab_header=$joi_tab_header_select;
			$lim_tab_header=$lim_tab_header_select;
			$lim_tab_line=$lim_tab_line_select;
			
			$req_tree_script=$rqt->getFielTree($req_univ);
			$req_tree_script.=$rqt->getFuncTree();
			$req_tree_script.=$rqt->getSubrTree();
			$joi_tab_lines=$rqt->getJoinTab();
			break;

		case '3' :	//insert
			$req_tab_header=$req_tab_header_insert;
//			$req_tree_script=$rqt->getTableSelector($req_univ);
//			$req_tree_script.=$rqt->getFuncTree();
//			$req_tree_script.=$rqt->getSubrTree();
			break;

		case '4' :	//update
			$req_tab_header=$req_tab_header_update;
			$joi_tab_header=$joi_tab_header_update;
			break;

		case '5' :	//delete
			$req_tab_header=$req_tab_header_delete;
			$joi_tab_header=$joi_tab_header_delete;
			$lim_tab_header=$lim_tab_header_delete;
			$lim_tab_line=$lim_tab_line_delete;
			break;

		default :
			break;
	} 
	$frame_req_add_form = str_replace('<!-- req_tree_script -->',$req_tree_script, $frame_req_add_form);
	$frame_req_add_form = str_replace('<!-- req_tab_header -->',$req_tab_header, $frame_req_add_form);
	$frame_req_add_form = str_replace('<!-- req_tab_lines -->',$req_tab_lines, $frame_req_add_form);
	$frame_req_add_form = str_replace('!!req_order!!',$req_order, $frame_req_add_form);
	$frame_req_add_form = str_replace('<!-- joi_tab_header -->',$joi_tab_header, $frame_req_add_form);
	$frame_req_add_form = str_replace('<!-- joi_tab_lines -->',$joi_tab_lines, $frame_req_add_form);
	$frame_req_add_form = str_replace('<!-- lim_tab_header -->',$lim_tab_header, $frame_req_add_form);
	$frame_req_add_form = str_replace('<!-- lim_tab_lines -->',$lim_tab_line, $frame_req_add_form);
	
	print $frame_req_add_form; 	
	
}


//traitement des actions
switch($action) {
	
	case 'add':
		show_frame_req_add_form();
		break;

	case 'modif':
		break;

	case 'update':
		break;

		
	case 'del':
		break;

	case 'list':
	default:
		show_req_add_form();
		break;
}

?>