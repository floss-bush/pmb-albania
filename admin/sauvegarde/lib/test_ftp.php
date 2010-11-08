<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: test_ftp.php,v 1.7 2007-10-27 08:04:40 touraine37 Exp $

require_once ("../../../includes/error_report.inc.php") ;
require_once ("../../../includes/global_vars.inc.php") ;
require_once ("../../../includes/config.inc.php");

$include_path      = "../../../".$include_path; 
$class_path        = "../../../".$class_path;
$javascript_path   = "../../../".$javascript_path;
$styles_path       = "../../../".$styles_path;

require("$include_path/db_param.inc.php");
require("$include_path/mysql_connect.inc.php");
// connection MySQL
$dbh = connection_mysql();

include("$include_path/error_handler.inc.php");
include("$include_path/sessions.inc.php");
include("$include_path/misc.inc.php");
include("$class_path/XMLlist.class.php");

//Test d'une connexion ftp
require_once ("api.inc.php");

if(!checkUser('PhpMyBibli', ADMINISTRATION_AUTH)) {
	// localisation (fichier XML) (valeur par défaut)
	$messages = new XMLlist("$include_path/messages/$lang.xml", 0);
	$messages->analyser();
	$msg = $messages->table;
	print '<html><head><link rel=\"stylesheet\" type=\"text/css\" href=\"../../styles/$stylesheet; ?>\"></head><body>';
	require_once("$include_path/user_error.inc.php");
	error_message($msg[11], $msg[12], 1);
	print '</body></html>';
	exit;
	}


if(SESSlang) {
	$lang=SESSlang;
	$helpdir = $lang;
	}



// localisation (fichier XML)

$messages = new XMLlist("$include_path/messages/$lang.xml", 0);
$messages->analyser();
$msg = $messages->table;

require("$include_path/templates/common.tpl.php");

header ("Content-Type: text/html; charset=".$charset);

print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='$msg[1002]' charset='".$charset."'>
	<meta http-equiv='Pragma' content='no-cache'>
		<meta http-equiv='Cache-Control' content='no-cache'>";
print link_styles($stylesheet) ;
print "	</head>
	<body>";




echo "<center><small><b>".$msg["sauv_ftp_test_running"]."</b></small></center>";
echo "<center><img src=\"connect.gif\"></center>";
flush();
$msg_="";
if ($chemin=="") $chemin="/";
$conn_id = connectFtp($url, $user, $password, $chemin, $msg_);
if ($conn_id != "")
	$msg_ = $msg["sauv_ftp_test_succeed"];	

echo "<script>alert(\"$msg_\");self.close();</script>";
