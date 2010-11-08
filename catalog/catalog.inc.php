<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: catalog.inc.php,v 1.34 2009-03-25 13:15:48 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo window_title($database_window_title.$msg[6].$msg[1003].$msg[1001]);

require_once("$include_path/templates/catalog.tpl.php");
require_once("$include_path/isbn.inc.php");
require_once("$include_path/marc_tables/$lang/empty_words");
require_once("$class_path/marc_table.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/indexint.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/category.class.php");
require_once("$class_path/notice.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/expl.class.php");
require_once("$class_path/explnum.class.php");
require_once("$class_path/emprunteur.class.php");
require_once("$include_path/fields_empr.inc.php");
require_once("$include_path/datatype.inc.php");
require_once("$include_path/parser.inc.php");
require_once("$include_path/notice_authors.inc.php");
require_once("$include_path/notice_categories.inc.php");
require_once("$include_path/explnum.inc.php") ;
require_once("$include_path/expl_info.inc.php") ;
require_once("$include_path/bull_info.inc.php") ;
require_once("$include_path/resa_func.inc.php") ;
if ($pmb_prefill_cote) {
	require_once("./catalog/expl/$pmb_prefill_cote"); 
} else {
	require_once("./catalog/expl/custom_no_cote.inc.php");
}

switch($categ) {
	case 'update':
		include('./catalog/notices/update_notice.inc.php');
		break;
	case 'notice_form':
		include('./catalog/notices/notice_form.inc.php');
		break;
	case 'isbd':
		include('./catalog/notices/isbd.inc.php');
		break;
	case 'expl_update':
		include('./catalog/expl/expl_update.inc.php');
		break;	
	case 'expl_create':
		include('./catalog/expl/expl_create.inc.php');
		break;
	case 'edit_expl':
		include('./catalog/expl/edit_expl.inc.php');
		break;
	case 'create_form':
		include('./catalog/notices/create_form.inc.php');
		break;
	case 'modif':
		include('./catalog/notices/notice_form.inc.php');
		break;
	case 'create':
		include('./catalog/notices/notice_create.inc.php');
		break;
	case 'del_expl':
		include('./catalog/expl/del_expl.inc.php');
		break;	
	case 'dupl_expl':
		include('./catalog/expl/dupl_expl.inc.php');
		break;	
	case 'search':
		include('./catalog/notices/search/main.inc.php');
		break;
	case 'delete':
		include('./catalog/notices/notice_delete.inc.php');
		break;
	case 'serials':
		include('./catalog/serials/serial_main.inc.php');
		break;
	case 'caddie':
		include('./catalog/caddie/caddie.inc.php');
		break;
	case 'etagere':
		include('./catalog/etagere/main.inc.php');
		break;
	case 'z3950':
		include('./catalog/notices/notice_z3950_replace.inc.php');
		break;
	case 'last_records':
		include('./catalog/last_records.inc.php');
		break;
	case 'search_perso':
		include('./catalog/search_perso/main.inc.php');
		break;		
	case 'remplace':
		include('./catalog/notices/notice_replace.inc.php');
		break;
	case 'duplicate':
		print "<h1>$msg[catal_duplicate_notice]</h1>"; 
		// routine de copie
		$notice = new notice($id);
		$notice->id=0 ;
		$notice->code="" ;
		$notice->duplicate_from_id = $id ; 
		print pmb_bidi($notice->show_form()) ;
		break;
	case 'explnum_create':
		include('./catalog/explnum/explnum_create.inc.php'); 
		break;
	case 'explnum_update':
		include('./catalog/explnum/explnum_update.inc.php');
		break;	
	case 'edit_explnum':
		include('./catalog/explnum/edit_explnum.inc.php');
		break;
	case 'del_explnum':
		include('./catalog/explnum/del_explnum.inc.php');
		break;	
	case 'sug' :
		//Création de suggestion
		include("./catalog/suggestions/make_sug.inc.php");
		break;	
	case 'avis':
		include("./catalog/notices/avis.inc.php");
		break;
	case 'tags':
		include("./catalog/notices/tags.inc.php");
		break;
	default:
		include('./catalog/notices/search/main.inc.php');
		break;
}
