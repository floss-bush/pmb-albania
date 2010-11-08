<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: admin.php,v 1.35 2010-07-02 08:15:16 arenou Exp $

// définition du minimum nécessaire 
$base_path=".";                            
$base_auth = "ADMINISTRATION_AUTH";  
$base_title = "\$msg[7]";    
require_once ("$base_path/includes/init.inc.php");  

// les requis par admin.php ou ses sous modules
require("$include_path/account.inc.php");
require_once("$class_path/iso2709.class.php");
require("$include_path/templates/admin.tpl.php");

// remplacement de !!help_link!! par le lien correspondant
if ($pmb_show_help) {
	$pos = strrpos($_SERVER["SCRIPT_NAME"], "/") + 1;
	$doc_script_name=substr($_SERVER["SCRIPT_NAME"],$pos,strlen($_SERVER["SCRIPT_NAME"]));
	$extra = str_replace("!!help_link!!","<a href=# onclick=\"openPopUp('doc/index.php?doc_script_name=".$doc_script_name."&doc_categ=".$categ."&doc_sub=".$sub."&doc_lang=".$lang."', 'documentation', 480, 550, -2, -2, 'toolbar=0,menubar=0,dependent=0,resizable=1,alwaysRaised=1');return false;\">?</a>",$extra);
}

print "<div id='att' style='z-Index:1000'></div>";
print $menu_bar;
print $extra;
print $extra_info;

if($use_shortcuts) {
	include("$include_path/shortcuts/circ.sht");
	}

switch($categ) {
	case 'users':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_users, $admin_layout);
		include("./admin/users/main.inc.php");
		break;
	case 'netbase':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_misc, $admin_layout);
		include("./admin/netbase/main.inc.php");
		break;
	case 'chklnk':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_misc, $admin_layout);
		include("./admin/netbase/chklnk.inc.php");
		break;
	case 'infopages':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_infopages, $admin_layout);
		include("./admin/misc/infopages.inc.php");
		break;
	case 'docs':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_docs, $admin_layout);
		include("./admin/docs/main.inc.php");
		break;
	case 'notices':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_notices, $admin_layout);
		include("./admin/notices/main.inc.php");
		break;
	case 'collstate':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_collstate, $admin_layout);
		include("./admin/collstate/main.inc.php");
		break;	
	case 'abonnements':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_abonnements, $admin_layout);
		include("./admin/abonnements/main.inc.php");
		break;		
	case 'empr':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_empr, $admin_layout);
		include("./admin/empr/main.inc.php");
		break;
	case 'misc':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_misc, $admin_layout);
		include("./admin/misc/main.inc.php");
		break;
	case 'import':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_import, $admin_layout);
		include("./admin/import/main.inc.php");
		break;
	case 'log':
		echo window_title($database_window_title.$msg["216"].$msg["1003"].$msg["1001"]);
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_misc, $admin_layout);
		include("./admin/view_log.inc.php");
		break;
	case 'param':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_misc, $admin_layout);
		include("./admin/param/main.inc.php");
		break;
	case 'z3950':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_z3950, $admin_layout);
		include("./admin/z3950/main.inc.php");
		break;
	case 'alter':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_misc, $admin_layout);
		include("./admin/misc/alter.inc.php");
		break;
	case 'sauvegarde':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_sauvegarde, $admin_layout);
		include("./admin/sauvegarde/main.inc.php");
		break;
	case 'convert':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_convert, $admin_layout);
		include("./admin/convert/main.inc.php");
		break;
	case 'finance':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_finance, $admin_layout);
		include("./admin/finance/main.inc.php");
		break;
	case 'quotas':
		include("./admin/quotas/main.inc.php");
		break;
	case 'calendrier':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_calendrier, $admin_layout);
		include("./admin/calendrier/main.inc.php");
		break;
	case 'acquisition':		
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_acquisition, $admin_layout);
		include("./admin/acquisition/main.inc.php");
		break;			
	case 'html_editor':		
		$admin_layout = str_replace('!!menu_contextuel!!', "", $admin_layout);
		include("./admin/misc/html_editor.inc.php");
		break;			
	case 'connecteurs':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_connecteurs, $admin_layout);
		include("./admin/connecteurs/main.inc.php");
		break;		
	case 'selfservice':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_selfservice, $admin_layout);
		include("./admin/selfservice/main.inc.php");
		break;
	case 'proc':
		$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_act, $admin_layout);
		include("./admin/proc/main.inc.php");
		break;	
	case 'transferts' :
		$admin_layout = str_replace ( '!!menu_contextuel!!', $admin_menu_transferts, $admin_layout );
		include ("./admin/transferts/main.inc.php");
		break;
	case 'acces':
		include("./admin/acces/main.inc.php");
		break;		
	case 'opac':
		$admin_layout = str_replace ( '!!menu_contextuel!!', $admin_menu_opac, $admin_layout );		
		include("admin/opac/main.inc.php");
		break;
	case 'docnum':
		$admin_layout = str_replace ( '!!menu_contextuel!!', $admin_menu_upload_docnum, $admin_layout );		
		include("./admin/upload/main.inc.php");
		break;
	case 'external_services':
		$admin_layout = str_replace ( '!!menu_contextuel!!', $admin_menu_external_services, $admin_layout );
		include("./admin/external_services/main.inc.php");
		break;
	case 'demandes':
		$admin_layout = str_replace ( '!!menu_contextuel!!', $admin_menu_demandes, $admin_layout );		
		include("./admin/demandes/main.inc.php");
		break;
	case 'visionneuse':
		$admin_layout = str_replace ( '!!menu_contextuel!!', $admin_menu_visionneuse, $admin_layout );		
		include("./admin/visionneuse/main.inc.php");
		break;
	default:
		$admin_layout = str_replace('!!menu_contextuel!!', "", $admin_layout);
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["7"].$msg["1003"].$msg["1001"]);
		include("$include_path/messages/help/$lang/admin.txt");
		break;
	}

print $admin_layout_end;
print $footer;

// deconnection MYSql
mysql_close($dbh);
