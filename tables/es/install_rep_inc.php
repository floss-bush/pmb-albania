<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install_rep_inc.php,v 1.6 2009-05-16 11:04:15 dbellamy Exp $

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

$msg_okconnect_usermysql="Conexi&oacute;n a la base $dbnamedbhost realizada  con &eacute;xito con $usermysql";
$msg_okconnect_user     ="<br /><br />Connexi&oacute;n a la base $dbname conseguida  con &eacute;xito con $user <br />";
$msg_nodb="Imposible conectar a la base de datos $dbname";
$msg_okdb="<br />Creaci&oacute;de la base realizada  con &eacute;xito
				<p align=\"center\"><font color=\"#FF0000\">
				<b>La creaci&eocute;n de la base $dbname en Mysql se ha realizado con &eacute;xito.</b>
				</font>
				</p>";

$msg_crea_01 = "<br /><br />Creaci&oacute;n de las tablas realizada con &eacute;xito";
$msg_crea_02 = "<br /><br />Fallo al crear las tablas";
$msg_crea_03 = "<br /><br />Introducci&oacute;n de los datos m&iacute;nimos necesarios para el funcionamiento realizado con &eacute;xito";
$msg_crea_04 = "<br /><br />Fallo al incluir los datos m&iacute;minos para el funcionamiento";

$msg_crea_05 = "<br /><br />Introducci&oacute;n de los datos esenciales para iniciar r&aacute;pidamente realizado con &eacute;xito";
$msg_crea_06 = "<br /><br />Fallo al incluir los datos esenciales para el inicio r&aacute;pido";

$msg_crea_07 = "<br /><br />Introducci&oacute;n del juego de ejemplos realizado con &eacute;xito";
$msg_crea_08 = "<br /><br />Fallo al incluir el juego de ejemplos";

$msg_crea_09 = "<br /><br />Introducci&oacute;n del tesauro AGNEAUX";
$msg_crea_10 = "<br /><br />Fallo al introducir el tesauro AGNEAUX";

$msg_crea_11 = "<br /><br />Introducci&oacute;n de los 100 casos del saber realizada con &eacute;xito";
$msg_crea_12 = "<br /><br />Fallo al introducir los 100 casos del saber";

$msg_crea_13 = "<br /><br />Introducci&oacute;n de los datos esenciales para iniciar rápidamente realizada con &eacute;xito";
$msg_crea_14 = "<br /><br />Fallo al introducir los datos esenciales para iniciar rápidamente";

$msg_crea_15 = "<br /><br />Introducci&oacute;n del tesauro UNESCO";
$msg_crea_16 = "<br /><br />Fallo al introducir el tesauro UNESCO";

$msg_crea_17 = "<br /><br />Introducci&oacute;n del tesauro AGNEAUX realizada con &eacute;xito";
$msg_crea_18 = "<br /><br />Fallo al introducir el tesuaro AGNEAUX";

$msg_crea_19 = "<br /><br />Introducci&oacute;n  del tesauro de MEDIO AMBIENTE realizado con &eacute;xito";
$msg_crea_20 = "<br /><br />Fallo al introducir el tesauro de MEDIO AMBIENTE";

$msg_crea_23 = "<br /><br />Introducci&oacute;n  de la clasificaci&oacute;n de la BM de Chamb&eacute;ry";
$msg_crea_24 = "<br /><br />Fallo al introducir la clasificaci&oacute;n  de la BM de Chamb&eacute;ry";

$msg_crea_25 = "<br /><br />Introducci&oacute;n  de la clasificaci&oacute;n estilo Dewey";
$msg_crea_26 = "<br /><br />Fallo al introducir la clasificaci&oacute; estilo Dewey";

$msg_crea_27 = "<br /><br />Introducci&oacute;n  de la clasificaci&oacute;n Dewey 100 casos del saber";
$msg_crea_28 = "<br /><br />Fallo al introducir la clasificaci&oacute;n Dewey 100 casos del saber";
$msg_crea_29 = "<br /><br />No se ha introducido ninguna clasificaci&oacute;n.";
$msg_crea_30 = "<p>los scripts de instalaci&oacute;n han sido renombrados para no poder poder ser ejecutados directamente</p>";
$msg_crea_31 = "<p><a href=\"../\">Ir a la p&aacute;gina de inicio</a></p>";
$msg_crea_32 = "Pb de datos de creaci&oacute;n ";
					
$msg_crea_control_version = "<h3>La versi&óacute;n de la base de datos es <font color=red>!!pmb_version!!</font>, deber&iacute;a ser <font color=red>$pmb_version_database_as_it_should_be</font></h3><br />
			Conn&eacute;ctate a PMB de la forma normal,<br />
			Ve a Administraci&aacute;on > Herramientas > y actualiza la base de datos antes de empezar a trabajar con PMB.<br />
			No te olvides de hacer copias de seguridad, y comprueba que se han guardado todas las tablas.";
?>

