<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: help.php,v 1.7 2009-03-24 08:39:40 ngantier Exp $

$base_path="./";
require_once($base_path."/includes/init.inc.php");
require_once($base_path."/includes/error_report.inc.php") ;
require_once("./includes/global_vars.inc.php");
require_once($base_path.'/includes/opac_config.inc.php');
	
// récupération paramètres MySQL et connection á la base
require_once($base_path.'/includes/opac_db_param.inc.php');
require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();
require_once($base_path."includes/start.inc.php");

// récupération localisation
require_once('./includes/localisation.inc.php');

print "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" >
<head>
	<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\" />
	<meta name=\"author\" content=\"PMB Group\" />
	<meta name=\"keywords\" content=\"OPAC, web, libray, opensource, catalog, catalogue, bibliothèque, médiathèque, pmb, phpmybibli\" />
	<meta name=\"description\" content=\"Recherches simples dans l'OPAC de PMB\" />
	<meta name=\"robots\" content=\"all\" />
	<title>pmb : opac</title>
	<script>
	function div_show(name) {
		var z=document.getElementById(name);
		if (z.style.display==\"none\") {
			z.style.display=\"block\"; }
		else { z.style.display=\"none\"; }
		}
	</script>
	<link rel=\"stylesheet\" href=\"./styles/$css/$css.css\" />
</head>

<body onload=\"window.defaultStatus='pmb : opac';\" id=\"pmb.opac\">
<div id='help-container'>
<p align=right style=\"margin-top:4px;\"><a name='top' ></a><a href='#' onclick=\"self.close();return false\" title=\"".$msg[search_close]."\" alt=\"".$msg[search_close]."\"><img src=\"images/close.gif\" align=\"absmiddle\" border=\"0\"></a></p>

";

switch($whatis) {
	case 'expbool':
		include("includes/messages/$lang/doc_expbool.txt");
		break;
	case 'search_multi':
		include("includes/messages/$lang/doc_search_multi.txt");
		break;
	case 'search_terms':
		include("includes/messages/$lang/doc_search_terms.txt");
		break;
	case 'simple_search':
		include("includes/messages/$lang/doc_simple_search.txt");
		break;
	default:
		break;
	}
print "
<p align=\"right\"><a href='#top' title=\"".$msg[search_up]."\" alt=\"".$msg[search_up]."\"><img src=\"images/up.gif\" align=\"absmiddle\" border=\"0\"></a></p>
</div>
<script>self.focus();</script>";
print "</body></html>"
?>