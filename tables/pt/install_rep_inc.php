<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install_rep_inc.php,v 1.2 2009-05-16 11:04:16 dbellamy Exp $

// plus rien ici : reprise d'un script d'une autre install
if(preg_match('/install_rep_inc\.php/', $_SERVER['REQUEST_URI'])) {
	include('../../includes/forbidden.inc.php'); forbidden();
	}

$header="
<html>
  <head>
  	<META HTTP-EQUIV=\"pragma\" CONTENT=\"no-cache\">
	<META HTTP-EQUIV=\"expires\" CONTENT=\"Wed, 30 Sept 2001 12:00:00 GMT\">
<title>InstalThetion Base bibli pour PMB</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">
</head>

<body bgcolor=\"#FFFFFF\" text=\"#000000\">
";

$footer="
";

$body="
";

$msg_okconnect_usermysql="Connection to the database $dbnamedbhost succeeded with $usermysql";
$msg_okconnect_user     ="<br /><br />Connection to the database $dbname succeeded with $user <br />";
$msg_nodb="Impossible to connect to the database $dbname";
$msg_okdb="<br />Creation of the database completed
				<p align=\"center\"><font color=\"#FF0000\">
				<b>The creation of the database $dbname in Mysql was completed.</b>
				</font>
				</p>";

$msg_crea_01 = "<br /><br />Creation of the tables succeeded";
$msg_crea_02 = "<br /><br />Creation of the tables failed";
$msg_crea_03 = "<br /><br />Minimum data filling required to function successful";
$msg_crea_04 = "<br /><br />Failure of the minimum data filling required to function";

$msg_crea_05 = "<br /><br />Essential data filling for quick-start successful";
$msg_crea_06 = "<br /><br />Failure of the essential data filling for quick-start";

$msg_crea_07 = "<br /><br />Data filling with the example data successful";
$msg_crea_08 = "<br /><br />Failure of the data filling with the example data";

$msg_crea_09 = "<br /><br />data filling with the thesaurus AGNEAUX";
$msg_crea_10 = "<br /><br />Failure of the data filling with the thesaurus AGNEAUX";

$msg_crea_11 = "<br /><br />Data filling with the 100 cases of knowlege successful";
$msg_crea_12 = "<br /><br />Failure of the data filling with the 100 cases of knowlege";

$msg_crea_13 = "<br /><br />Essential data filling for quick-start successful";
$msg_crea_14 = "<br /><br />Failure of the essential data filling for quick-start successful";

$msg_crea_15 = "<br /><br />Data filling with the thesaurus UNESCO";
$msg_crea_16 = "<br /><br />Failure of the data filling with the thesaurus UNESCO";

$msg_crea_17 = "<br /><br />Data filling with the thesaurus AGNEAUX successful";
$msg_crea_18 = "<br /><br />Failure of the data filling with the thesaurus AGNEAUX";

$msg_crea_19 = "<br /><br />Data filling with the thesaurus ENVIRONNEMENT successful";
$msg_crea_20 = "<br /><br />Failure of the data filling with the thesaurus ENVIRONNEMENT";

$msg_crea_23 = "<br /><br />Data filling with the Chambéry library data";
$msg_crea_24 = "<br /><br />Failure of the data filling with the Chambéry library data";

$msg_crea_25 = "<br /><br />Data filling with Dewey style data";
$msg_crea_26 = "<br /><br />Failure of the data filling with the Dewey style data";

$msg_crea_27 = "<br /><br />Data filling with the Dewey index 100 cases of knowlege";
$msg_crea_28 = "<br /><br />Failure of the data filling with the Dewey index 100 cases of knowlege";
$msg_crea_29 = "<br /><br />No data filling of an index.";
$msg_crea_30 = "<p>The installation scripts are renamed so that they cannot be executed.</p>";
$msg_crea_31 = "<p><a href=\"../\">Go to the welcome page</a></p>";
$msg_crea_32 = "Problem with the creation data set...";
					
$msg_crea_control_version = "<h3>The database version is <font color=red>!!pmb_version!!</font>, it should be <font color=red>$pmb_version_database_as_it_should_be</font></h3><br />
			Connect to PMB as usual,<br />
			Go to Administration > Tools > database update before you work with PMB.<br />
			Don't forget to do backups, check that all the tables are saved.";
?>

