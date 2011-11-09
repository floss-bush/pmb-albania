<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install_rep_inc.php,v 1.8.4.1 2011-05-09 13:03:14 dbellamy Exp $

// plus rien ici : reprise d'un script d'une autre install
if(preg_match('/install_rep_inc\.php/', $_SERVER['REQUEST_URI'])) {
	include('../../includes/forbidden.inc.php'); forbidden();
	}

$header="
<html>
  <head>
  	<META HTTP-EQUIV=\"pragma\" CONTENT=\"no-cache\">
	<META HTTP-EQUIV=\"expires\" CONTENT=\"Wed, 30 Sept 2001 12:00:00 GMT\">
<title>Installazione database PMB</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">
</head>

<body bgcolor=\"#FFFFFF\" text=\"#000000\">
";

$footer="
";

$body="
";

$msg_okconnect_usermysql="Connessione al database $dbnamedbhost riuscita come $usermysql";
$msg_okconnect_user     ="<br /><br />Connessione al database $dbname riuscita come $user <br />";
$msg_nodb="Impossibile creare il database $dbname";
$msg_okdb="<br />Creazione del database:<p align=\"center\"><font color=\"#FF0000\">
				<b>La creazione del database $dbname in Mysql &egrave; stata appena fatta.</b>
				</font>
				</p>";

$msg_crea_01 = "<br /><br />Creazione delle tabelle riuscita";
$msg_crea_02 = "<br /><br />Creazione delle tabelle FALLITA";
$msg_crea_03 = "<br /><br />Caricamento del minimo necessario al funzionamento riuscito";
$msg_crea_04 = "<br /><br />Caricamento minimo necessario al funzionamento FALLITO";

$msg_crea_05 = "<br /><br />Caricamento di quanto basta per essere operativi riuscito";
$msg_crea_06 = "<br /><br />Caricamento di quanto basta per essere operativi FALLITO";

$msg_crea_07 = "<br /><br />Caricamento dei dati di prova riuscito";
$msg_crea_08 = "<br /><br />Caricamento dei dati di prova FALLITO";

$msg_crea_09 = "<br /><br />Remplissage avec le th&eacute;saurus AGNEAUX";
$msg_crea_10 = "<br /><br />Echec du remplissage avec le th&eacute;saurus AGNEAUX";

$msg_crea_11 = "<br /><br />Remplissage avec la cote 100 cases du savoir r&eacute;ussi";
$msg_crea_12 = "<br /><br />Echec du remplissage avec la cote 100 cases du savoir ";

$msg_crea_13 = "<br /><br />Remplissage de l'essentiel pour d&eacute;marrer rapidement r&eacute;ussi";
$msg_crea_14 = "<br /><br />Echec du remplissage de l'essentiel pour d&eacute;marrer rapidement r&eacute;ussi";

$msg_crea_15 = "<br /><br />Remplissage avec le th&eacute;saurus UNESCO";
$msg_crea_16 = "<br /><br />Echec du remplissage avec le th&eacute;saurus UNESCO";

$msg_crea_17 = "<br /><br />Remplissage avec le th&eacute;saurus AGNEAUX r&eacute;ussi";
$msg_crea_18 = "<br /><br />Echec du remplissage avec le th&eacute;saurus AGNEAUX";

$msg_crea_19 = "<br /><br />Remplissage avec le th&eacute;saurus ENVIRONNEMENT r&eacute;ussi";
$msg_crea_20 = "<br /><br />Echec du remplissage avec le th&eacute;saurus ENVIRONNEMENT";

$msg_crea_23 = "<br /><br />Remplissage avec la cote de la BM de Chamb&eacute;ry";
$msg_crea_24 = "<br /><br />Echec du remplissage avec la cote de la BM de Chamb&eacute;ry";

$msg_crea_25 = "<br /><br />Caricamento indicizzazione Dewey effettuato";
$msg_crea_26 = "<br /><br />Caricamento indicizzazione Dewey FALLITO";

$msg_crea_27 = "<br /><br />Remplissage avec l'indexation Dewey 100 cases du savoir";
$msg_crea_28 = "<br /><br />Echec du remplissage avec l'indexation Dewey 100 cases du savoir";
$msg_crea_29 = "<br /><br />Aucun remplissage d'indexation.";
$msg_crea_30 = "<p>Gli script d&iacute;nstallazione sono stati rinominati al fine di non poter essere pi&ugrave; richiamati direttamente</p>";
$msg_crea_31 = "<p><a href=\"../\">Vai alla HOME PAGE</a></p>";
$msg_crea_32 = "Pb dei dati di creazione ";
					
$msg_crea_control_version = "<h3>The database version is <font color=red>!!pmb_version!!</font>, it should be <font color=red>$pmb_version_database_as_it_should_be</font></h3><br />
			Connect to PMB as usual,<br />
			Go to Administration > Tools > database update before you work with PMB.<br />
			Don't forget to do backups, check that all the tables are saved.";
?>

