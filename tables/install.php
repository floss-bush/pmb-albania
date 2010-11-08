<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install.php,v 1.22 2009-05-16 11:04:16 dbellamy Exp $

// plus rien ici : reprise d'un script d'une autre install
if(preg_match('/noinstall\.php/', $_SERVER['REQUEST_URI'])) {
	include('../includes/forbidden.inc.php'); forbidden();
	}

@error_reporting (E_ERROR | E_PARSE | E_WARNING);

include('../includes/config.inc.php');
	

$sel_lang="
	<html>
	<head>
		<title>PMB setup</title>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
		<style type=\"text/css\">
			body {
				font-family: \"Verdana\", \"Arial\", sans-serif;
				background: #eeeae4;
				text-align: center;
			}
			.bouton {
				color: #fff;
				font-size: 12pt;
				font-weight: bold;
				border: 1px outset #D47800;
				background-color: #5483AC;
			}

			.bouton:hover {
				border-style: inset;
				border: 1px solid #ED8600;
				background-color: #7DC2FF;
			}
		</style>
	</head>
	</body>
	<center>
	<form method='post' action=install.php>
	<h1><img src='../images/logo_pmb_rouge.png'>&nbsp;&nbsp;install</h1>
	<br />
	<h3>Encodage de caractère (charset) :</h3><br />
	<ul> 
		<li>
    		<input type=\"radio\" name=\"charset\" checked value=\"iso-8859-1\"/>iso-8859-1 (Uniquement les caractères latins)
   		</li>
     	<li>
     		<input type=\"radio\" name=\"charset\" value=\"utf-8\"/>utf-8 (Tous alphabets, a choisir pour l'Arabe en plus de l'installation en français) 
    	</li>
	</ul>
	<h3>Langue:</h3><br />
	<table>
		<tr>
			<td><input type='submit' class='bouton' name='submit' value='Français'></td>
			<td>&nbsp;&nbsp;</td>
			<td><input type='submit' class='bouton' name='submit' value='Italiano'></td>
			<td>&nbsp;&nbsp;</td>
			<td><input type='submit' class='bouton' name='submit' value='English'></td>
			<td>&nbsp;&nbsp;</td>
			<td><input type='submit' class='bouton' name='submit' value='Català'></td>
			<td>&nbsp;&nbsp;</td>
			<td><input type='submit' class='bouton' name='submit' value='Español'></td>
			<td>&nbsp;&nbsp;</td>
			<td><input type='submit' class='bouton' name='submit' value='Portuguese'></td>
		<tr>
	</table>

	</form>
	</center>
	</body>
	</html>
";

switch ($_REQUEST['submit']){
	case 'Italiano':
		$lang='it';
		break;
	case 'Français':
		$lang='fr';
		break;
	case 'English':
		$lang='en';
		break;
	case 'Català':
		$lang='ca';
		break;
	case 'Español':
		$lang='es';
		break;
	case 'Portuguese':
		$lang='pt';
		break;
	default:
		print $sel_lang;
}

$charset = $_REQUEST['charset'];
if ($lang && $lang != $default_lang){
	include("./$lang/install_inc.php");
	print $header;
	print $body;
	print $footer;
}
	

?>

