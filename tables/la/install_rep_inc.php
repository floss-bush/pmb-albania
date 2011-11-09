<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install_rep_inc.php,v 1.8 2006/09/02 07:55:33 touraine37 Exp $

// plus rien ici : reprise d'un script d'une autre install
if(preg_match('/install_rep_inc\.php/', $_SERVER['REQUEST_URI'])) {
	include('../../includes/forbidden.inc.php'); forbidden();
	}

$header="
<html>
  <head>
  	<META HTTP-EQUIV=\"pragma\" CONTENT=\"no-cache\">
	<META HTTP-EQUIV=\"expires\" CONTENT=\"Wed, 30 Sept 2001 12:00:00 GMT\">
<title>Installation Base bibli pour PMB</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">
</head>

<body bgcolor=\"#FFFFFF\" text=\"#000000\">
";

$footer="
";

$body="
";

$msg_okconnect_usermysql="Connexion &agrave; la base $dbnamedbhost r&eacute;ussie avec $usermysql";
$msg_okconnect_user     ="<br /><br />Connexion &agrave; la base $dbname r&eacute;ussie avec $user <br />";
$msg_nodb="Impossible de se connecter &agrave; la base de donn&eacute;es $dbname";
$msg_okdb="<br />Cr&eacute;ation de la base effectu&eacute;e
				<p align=\"center\"><font color=\"#FF0000\">
				<b>La cr&eacute;ation de la base $dbname dans Mysql vient d'&ecirc;tre cr&eacute;&eacute;e.</b>
				</font>
				</p>";

$msg_crea_01 = "<br /><br />Cr&eacute;ation des tables r&eacute;ussie";
$msg_crea_02 = "<br /><br />Echec de la cr&eacute;ation des tables";
$msg_crea_03 = "<br /><br />Remplissage minimum n&eacute;cessaire au fonctionnement r&eacute;ussi";
$msg_crea_04 = "<br /><br />Echec du Remplissage minimum n&eacute;cessaire au fonctionnement";

$msg_crea_05 = "<br /><br />Remplissage de l'essentiel pour d&eacute;marrer rapidement r&eacute;ussi";
$msg_crea_06 = "<br /><br />Echec du remplissage de l'essentiel pour d&eacute;marrer rapidement";

$msg_crea_07 = "<br /><br />Remplissage avec le jeu d'exemples r&eacute;ussi";
$msg_crea_08 = "<br /><br />Echec du remplissage avec le jeu d'exemples";

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

$msg_crea_21 = "<br /><br />Remplissage avec le th&eacute;saurus MotBis r&eacute;ussi";
$msg_crea_22 = "<br /><br />Echec du remplissage avec le th&eacute;saurus MotBis";

$msg_crea_23 = "<br /><br />Remplissage avec la cote de la BM de Chamb&eacute;ry";
$msg_crea_24 = "<br /><br />Echec du remplissage avec la cote de la BM de Chamb&eacute;ry";

$msg_crea_25 = "<br /><br />Remplissage avec la cote style Dewey";
$msg_crea_26 = "<br /><br />Echec du remplissage avec la cote style Dewey";

$msg_crea_27 = "<br /><br />Remplissage avec l'indexation Dewey 100 cases du savoir";
$msg_crea_28 = "<br /><br />Echec du remplissage avec l'indexation Dewey 100 cases du savoir";
$msg_crea_29 = "<br /><br />Aucun remplissage d'indexation.";
$msg_crea_30 = "<p>les scripts d'installation ont &eacute;t&eacute; renomm&eacute;s afin de ne plus pouvoir &ecirc;tre ex&eacute;cut&eacute;s directement</p>";
$msg_crea_31 = "<p><a href=\"../\">Allez &agrave; la page d'accueil</a></p>";
$msg_crea_32 = "Pb de donn&eacute;es de cr&eacute;ation ";
					
$msg_crea_control_version = "<h3>La version de la base de données est <font color=red>!!pmb_version!!</font>, elle devrait être en <font color=red>$pmb_version_database_as_it_should_be</font></h3><br />
			Connectez-vous à PMB normalement,<br />
			Allez en Administration > Outils > Mise à jour de la base avant de travailler avec PMB.<br />
			N'oubliez pas de faire des sauvegardes, vérifiez notamment que toutes les tables de données sont bien sauvegardées";
?>

