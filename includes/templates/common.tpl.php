<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: common.tpl.php,v 1.79.2.2 2011-09-13 09:57:48 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// Get current page...  pour marquer l'onglet...

if (!$current_alert) {
	$current = current_page();
	$current_module=str_replace(".php","",$current);
	} else  $current_module = $current_alert ; 

if (!$current_module) $current_module = "index" ;

require_once($class_path."/sort.class.php");

function link_styles($style) {
	// où $rep = répertoire de stockage des feuilles

	global $feuilles_style_deja_lu ;
	if ($feuilles_style_deja_lu) return $feuilles_style_deja_lu ;
	
	// mise en forme du répertoire
	global $styles_path;
	global $charset;
	
	if($styles_path) $rep = $styles_path;
		else $rep = './styles/';
	
	if(!preg_match('/\/$/', $rep)) $rep .= '/';
	
	$handle = @opendir($rep.$style);
	
	if(!$handle) {
		$result = array();
		return $result;
		}
	$feuilles_style="";
	while($css = readdir($handle)) {
		if(is_file($rep.$style."/".$css) && preg_match('/css$/', $css)) {
			$result[] = $css;
			$feuilles_style.="\n\t<link rel='stylesheet' type='text/css' href='".$rep.$style."/".$css." ' title='lefttoright' />";
	    		}
		}
	
	closedir($handle);

	// RTL / LTR
	global $pmb_show_rtl;
	if ($pmb_show_rtl) {
		$handlertl = @opendir($rep.$style."/rtl/");
		if($handlertl) {
			while($css = readdir($handlertl)) {
				if(is_file($rep.$style."/rtl/".$css) && preg_match('/css$/', $css)) {
					$result[] = $css;
					$feuilles_style.="\n\t<link rel='alternate stylesheet' type='text/css' href='".$rep.$style."/rtl/".$css." ' title='righttoleft' />";
		    		}
				}
			$feuilles_style.="\n\t<script type='text/javascript' src='./javascript/styleswitcher.js'></script>";
			closedir($handlertl);
			}
		}
	$feuilles_style_deja_lu = $feuilles_style; 
	return $feuilles_style;
	}


//	----------------------------------
// $std_header : template header standard
// attention : il n'y a plus le <body> : est envoyé par le fichier init.inc.php, c'est bien un header
$std_header = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='$msg[1002]' charset='".$charset."'>
  <head>
    <title>
      $msg[1001]
    </title>
	<meta name='author' content='PMB Group' />
	<meta name='description' content='Logiciel libre de gestion de médiathèque' />
	<meta name='keywords' content='logiciel, gestion, bibliothèque, médiathèque, libre, free, software, mysql, php, linux, windows, mac' />
	<!--<meta http-equiv='Pragma' content='no-cache' />
	<meta http-equiv='Cache-Control' content='no-cache' />-->
 	<meta http-equiv='Content-Type' content=\"text/html; charset=".$charset."\" />
	<meta http-equiv='Content-Language' content='$lang' />";
$std_header.= link_styles($stylesheet); 
$std_header.="
	<link rel=\"SHORTCUT ICON\" href=\"images/favicon.ico\" />
	<script src=\"".$base_path."/javascript/popup.js\" type=\"text/javascript\"></script>
	<script src=\"".$base_path."/javascript/drag_n_drop.js\" type=\"text/javascript\"></script>
	<script src=\"".$base_path."/javascript/handle_drop.js\" type=\"text/javascript\"></script>
	<script src=\"".$base_path."/javascript/misc.js\" type=\"text/javascript\"></script>
	<script src=\"".$base_path."/javascript/http_request.js\" type=\"text/javascript\"></script>
	<script type=\"text/javascript\">
		function keep_context(myObject,methodName){
			return function(){
			return myObject[methodName]();
		}
		}
	</script>
";
if (function_exists("auto_hide_getprefs")) $std_header.=auto_hide_getprefs()."\n";
$std_header.="	</head>";

//	----------------------------------
// $selector_header : template header selecteur
$selector_header = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='$msg[1002]' charset='".$charset."'>
  <head>
  	<meta name='author' content='PMB Group' />
	<meta name='description' content='Logiciel libre de gestion de médiathèque' />
	<meta name='keywords' content='logiciel, gestion, bibliothèque, médiathèque, libre, free, software, mysql, php, linux, windows, mac' />
  	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$charset."\">
    <title>
      PMB-Selector
    </title>";
$selector_header.= link_styles($stylesheet); //"    <link rel='stylesheet' type='text/css' href='./styles/$stylesheet'>";
$selector_header.="  </head>
  </head>
  <body>
";

//	----------------------------------
// $selector_header_no_cache : template header selecteur (no cache)
$selector_header_no_cache = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='$msg[1002]' charset='".$charset."'>
  <head>
    <title>
      PMB-selector
    </title>
	<meta name='author' content='PMB Group' />
	<meta name='description' content='Logiciel libre de gestion de médiathèque' />
	<meta name='keywords' content='logiciel, gestion, bibliothèque, médiathèque, libre, free, software, mysql, php, linux, windows, mac' />
	<!--<meta http-equiv='Pragma' content='no-cache'>
    <meta http-equiv='Cache-Control' content='no-cache'>-->
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$charset."\">";
$selector_header_no_cache.= link_styles($stylesheet);
$selector_header_no_cache.="
  </head>
  <body>
";

//	----------------------------------
// $menu_bar : template menu bar
//	Générer le $menu_bar selon les droits...
//	Par défaut : la page d'accueil.

$menu_bar = "
<!--	Menu bar	-->
<div id='navbar'>
<h3><span>$msg[1913]</span></h3>
	<ul>";

//	L'utilisateur fait la CIRCULATION ?
if (SESSrights & CIRCULATION_AUTH) {
	$menu_bar = $menu_bar."\n<li id='navbar-circ' ";
	if ("$current" == "circ.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='$msg[742]' href='./circ.php?categ=' accesskey='$msg[2001]'>$msg[5]</a></li>";
	}

//	L'utilisateur fait le CATALOGAGE ?
if (SESSrights & CATALOGAGE_AUTH) {
	$menu_bar = $menu_bar."\n<li id='navbar-catalog'";
	if ("$current" == "catalog.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='$msg[743]' href='./catalog.php' accesskey='$msg[2002]'>$msg[6]</a></li>";
	}

//	L'utilisateur fait les AUTORITÉS ?
if (SESSrights & AUTORITES_AUTH) {
	$menu_bar = $menu_bar."\n<li id='navbar-autorites'";
	if ("$current" == "autorites.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='$msg[744]' href='./autorites.php?categ=&sub=&id=' accesskey='$msg[2003]'>$msg[132]</a></li>";
	}

//	L'utilisateur fait l'ÉDITIONS ?
if (SESSrights & EDIT_AUTH) {
	$menu_bar = $menu_bar."\n<li id='navbar-edit'";
	if ("$current" == "edit.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='$msg[745]' href='./edit.php?categ=procs' accesskey='$msg[2004]'>$msg[1100]</a></li>";
	}

//	L'utilisateur fait la DSI ?
if ($dsi_active && (SESSrights & DSI_AUTH)) {
	$menu_bar = $menu_bar."\n<li id='navbar-dsi'";
	if ("$current" == "dsi.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='".htmlentities($msg[dsi_menu_title],ENT_QUOTES, $charset)."' href='./dsi.php' >$msg[dsi_menu]</a></li>";
	}

//	L'utilisateur fait l'ACQUISITION ?
if ($acquisition_active && (SESSrights & ACQUISITION_AUTH)) {
	$menu_bar = $menu_bar."\n<li id='navbar-acquisition'";
	if ("$current" == "acquisition.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='".htmlentities($msg[acquisition_menu_title],ENT_QUOTES, $charset)."' href='./acquisition.php' >$msg[acquisition_menu]</a></li>";
}

//	L'utilisateur accède aux extensions ?
if ($pmb_extension_tab && (SESSrights & EXTENSIONS_AUTH)) {
	$menu_bar = $menu_bar."\n<li id='navbar-extensions'";
	if ("$current" == "extensions.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='".htmlentities($msg[extensions_menu_title],ENT_QUOTES, $charset)."' href='./extensions.php' >$msg[extensions_menu]</a></li>";
}

//	L'utilisateur fait les DEMANDES ?
if ($demandes_active && (SESSrights & DEMANDES_AUTH)) {
	$menu_bar = $menu_bar."\n<li id='navbar-demandes'";
	if ("$current" == "demandes.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='".htmlentities($msg[demandes_menu_title],ENT_QUOTES, $charset)."' href='./demandes.php' >$msg[demandes_menu]</a></li>";
}

//	L'utilisateur fait l'onglet FICHES ?
if ($fiches_active && (SESSrights & FICHES_AUTH)) {
	$menu_bar = $menu_bar."\n<li id='navbar-fichier'";
	if ("$current" == "fichier.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='".htmlentities($msg['onglet_fichier'],ENT_QUOTES, $charset)."' href='./fichier.php' >".$msg['onglet_fichier']."</a></li>";
}

//	L'utilisateur fait l'ADMINISTRATION ?
if (SESSrights & ADMINISTRATION_AUTH) {
	$menu_bar = $menu_bar."\n<li id='navbar-admin'";
	if ("$current" == "admin.php") $menu_bar = $menu_bar." class='current'><a class='current' ";
		else $menu_bar = $menu_bar."><a ";
	$menu_bar.= "title='$msg[746]' href='./admin.php?categ=' accesskey='$msg[2005]'>$msg[7]</a></li>";
	}

$menu_bar = $menu_bar."
	</ul>
</div>";

if (SESSrights & CATALOGAGE_AUTH) {
	$extra.="<iframe id='history' style='display:none;'></iframe>";
}
$extra.="
<div id='extra'>
<span id=\"keystatus\">&nbsp;</span>&nbsp;&nbsp;&nbsp;";
if (SESSrights & CATALOGAGE_AUTH) 
	$extra.="<a class=\"icon_history\" href=\"#\" onClick=\"document.getElementById('history').style.display=''; document.getElementById('history').src='./history.php'; return false;\" alt=\"".$msg["menu_bar_title_histo"]."\" title=\"".$msg["menu_bar_title_histo"]."\"><img src='./images/historique.gif' align='middle' hspace='3' alt='' /></a>";


//affichage du lien d'aide, c'est un "?" pour l'instant
if ($pmb_show_help) {
    // remplacement de !!help_link!! par le lien correspondant
    $request_uri  = $_SERVER["REQUEST_URI"];
	$doc_params_explode = explode("?", $request_uri);
	$doc_params = $doc_params_explode[1]; 
 	$pos = strrpos($doc_params_explode[0], "/") + 1;
	$script_name=substr($doc_params_explode[0],$pos);
    $extra .= '<a class="icon_help" href="./doc/index.php?script_name='.$script_name.'&'.$doc_params.'&lang='.$lang.'" alt="'.$msg['1900'].'" title="'.$msg['1900'].'" target="__blank" >';
    $extra .= "<img src='./images/aide.gif' align='middle' hspace='3' alt='' /></a>";
}
if (SESSrights & PREF_AUTH)	
	$extra .="<a class=\"icon_param\" href='./account.php' accesskey='$msg[2006]' alt=\"${msg[934]} ".SESSlogin."\" title=\"${msg[934]} ".SESSlogin."\"><img src='./images/parametres.gif' align='middle' hspace='3' alt='' /></a>";

$extra .="<a class=\"icon_opac\" title='$msg[1027]' href='$pmb_opac_url?database=".LOCATION."' target='_opac_' accesskey='$msg[2007]'><img src='./images/opac2.gif' align='middle' hspace='3' alt='' /></a>";

if (SESSrights & SAUV_AUTH)
	$extra .="<a class=\"icon_sauv\" title='$msg[sauv_shortcuts_title]' href='#' onClick='openPopUp(\"./admin/sauvegarde/launch.php\",\"sauv_launch\",600,500,-2,-2,\"menubar=no,scrollbars=yes\"); w.focus(); return false;'><img src='./images/sauv.gif' align='middle' hspace='3' alt='' /></a>";

if ($pmb_show_rtl) {
	$extra .= "<a title='".$msg['rtl']."' href='#' onclick=\"setActiveStyleSheet('lefttoright'); window.location.reload(false); return false;\"><img 'src=./images/rtl.gif' align='middle' hspace='3' alt='' /></a>";
	$extra .= "<a title='".$msg['ltr']."' href='#' onclick=\"setActiveStyleSheet('righttoleft'); window.location.reload(false); return false;\"><img 'src=./images/ltr.gif' align='middle' hspace='3' alt='' /></a>";
}

$extra .= "<a class=\"icon_quit\" title='$msg[747] : ".LOCATION."' href='./logout.php' accesskey='$msg[2008]'><img src='./images/close.png' align='middle' hspace='3' alt='' /></a>";

$extra .= "</div>";

// Récupération de l'url active et test de présence sur la chaine cir.php'

	$url_active = $_SERVER['PHP_SELF'];
	$presence_chaine = strpos($url_active,'circ.php');

// Masquage de l'iframe d'alerte dans le cas 
// ou l'onglet courant est circulation et utilisateur en circulation restreinte' 
	
if ( !function_exists("auto_hide_getprefs") || ((SESSrights & RESTRICTCIRC_AUTH) && ($categ!="pret") && ($categ!="pretrestrict") &&  ($presence_chaine != false))) {
	$extra_info = ''; 
} else {	
	require_once($base_path."/alert/message.inc.php");
	if ($current_module=="circ" && $categ!="pret" && $categ!="retour") { 
		require_once($base_path."/alert/resa.inc.php");
		require_once("$base_path/alert/expl_todo.inc.php");	
		require_once($base_path."/alert/empr.inc.php");
		//pour les alertes de transferts
		if ($pmb_transferts_actif && (SESSrights & TRANSFERTS_AUTH))
			require_once ($base_path."/alert/transferts.inc.php");
	}
	if ($current_module=="catalog") {
		require_once($base_path."/alert/tag.inc.php");
		require_once($base_path."/alert/sugg.inc.php");
	}	
	if ($current_module=="acquisition") {
		require_once($base_path."/alert/sugg.inc.php");
	}

	$aff_alerte="<div class='erreur'>$aff_alerte</div>";
	
	$extra_info ="<iframe frameborder='0' scrolling='auto' name='alerte' id='alerte' src='$base_path/alert.php?current_alert=$current_module' class='$current_module'></iframe>";
	
	$extra_info="<script type=\"text/javascript\">
		function get_alert() {
			if(!document.getElementById('div_alert')) return;
			if(!session_active) return;
			var req = new http_request();		
			req.request('$base_path/ajax.php?module=ajax&categ=alert&current_alert=$current_module',0,'',1,get_alert_callback,'');
			setTimeout('get_alert()',120000);
		}	
		
		function get_alert_callback(text ) {
			if(text.substring(0,1) != '1') {
				session_active=0;	
				return;
			}	
		  	session_active=1;
			var div_alert = document.getElementById('div_alert');
  			div_alert.innerHTML = text.substring(1);
		}
		
		flag_get_alert=1;
		session_active=1;
	</script>";	
}

//	----------------------------------
// $footer : template footer standard
$footer = "
<div id='footer'>
	<div class='row'>
	
	</div>	
</div>
<script type=\"text/javascript\">
	if (init_drag) init_drag();
	if (typeof flag_get_alert!=\"undefined\"){
		if (flag_get_alert) setTimeout('get_alert()',120000);
	}
	menuAutoHide();
</script>
  </body>
</html>
";

/* listes dépliables et tris */
// ici, templates de gestion des listes dépliables et tris en résultat de recherche catalogage ou autres
if($pmb_recherche_ajax_mode){
	$begin_result_liste = "
<script type=\"text/javascript\" src=\"".$javascript_path."/tablist.js\"></script>
<a href=\"javascript:expandAll_ajax()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>
";
}else{
	$begin_result_liste = "
<script type=\"text/javascript\" src=\"".$javascript_path."/tablist.js\"></script>
<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>
";	
}

$affich_tris_result_liste = "<a href=# onClick=\"document.getElementById('history').src='./sort.php?action=0'; document.getElementById('history').style.display='';return false;\" alt=\"".$msg['tris_dispos']."\" title=\"".$msg['tris_dispos']."\"><img src='./images/orderby_az.gif' align='middle' hspace='3'></a>";
			
if ($_SESSION["tri"]) {
	$sort = new sort("notices","base");
	$affich_tris_result_liste .= $msg['tri_par']." ".$sort->descriptionTriParId($_SESSION["tri"]);
}

$affich_tris_result_liste .="<br />";
$expand_result="
<script type=\"text/javascript\" src=\"./javascript/tablist.js\"></script>
";

$end_result_list = "
"; 

	
/* /listes dépliables et tris */
