<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
/* 

Ce logiciel est un programme informatique servant à gérer une bibliothèque
ou un centre de documentation et notamment le catalogue des ouvrages et le
fichier des lecteurs. PMB est conforme à la déclaration simplifiée de la CNIL
en ce qui concerne le respect de la Loi Informatique et Libertés applicable
en France.

Ce logiciel est régi par la licence CeCILL soumise au droit français et
respectant les principes de diffusion des logiciels libres. Vous pouvez
utiliser, modifier et/ou redistribuer ce programme sous les conditions
de la licence CeCILL telle que diffusée par le CEA, le CNRS et l'INRIA 
sur le site "http://www.cecill.info".

En contrepartie de l'accessibilité au code source et des droits de copie,
de modification et de redistribution accordés par cette licence, il n'est
offert aux utilisateurs qu'une garantie limitée.  Pour les mêmes raisons,
seule une responsabilité restreinte pèse sur l'auteur du programme,  le
titulaire des droits patrimoniaux et les concédants successifs.

A cet égard  l'attention de l'utilisateur est attirée sur les risques
associés au chargement,  à l'utilisation,  à la modification et/ou au
développement et à la reproduction du logiciel par l'utilisateur étant 
donné sa spécificité de logiciel libre, qui peut le rendre complexe à 
manipuler et qui le réserve donc à des développeurs et des professionnels
avertis possédant  des  connaissances  informatiques approfondies.  Les
utilisateurs sont donc invités à charger  et  tester  l'adéquation  du
logiciel à leurs besoins dans des conditions permettant d'assurer la
sécurité de leurs systèmes et ou de leurs données et, plus généralement, 
à l'utiliser et l'exploiter dans les mêmes conditions de sécurité. 

Le fait que vous puissiez accéder à cet en-tête signifie que vous avez 
pris connaissance de la licence CeCILL, et que vous en avez accepté les
termes.

 */
// +-------------------------------------------------+
// $Id: index.php,v 1.16 2009-05-16 11:17:04 dbellamy Exp $

// définition du minimum nécéssaire 
$base_path=".";

include_once ("./includes/error_report.inc.php") ;
include_once ("./includes/global_vars.inc.php") ;
include_once ("./includes/config.inc.php");

if (file_exists("$include_path/db_param.inc.php")) require_once("$include_path/db_param.inc.php") ;
require_once("$include_path/mysql_connect.inc.php");
$dbh = connection_mysql(0);

require_once("$include_path/sessions.inc.php");

require_once("$include_path/misc.inc.php");
include_once("$javascript_path/misc.inc.php");


// récupération des messages avec localisation

// localisation (fichier XML)
include_once("$class_path/XMLlist.class.php");

$messages = new XMLlist("$include_path/messages/$lang.xml", 0);
$messages->analyser();
$msg = $messages->table;

// temporaire :
$inst_language = "";
	
require_once("$include_path/templates/common.tpl.php");
require_once("$include_path/templates/index.tpl.php");

if (!$dbh) {
	header ("Content-Type: text/html; charset=".$charset);
	print $index_header;
	print $extra_version;
	print "<br /><br /><div class='erreur'> $__erreur_cnx_base__ </div><br /><br />" ;
	print $msg["cnx_base_err1"]." <a href='./tables".$inst_language."/install.php'>./tables/install.php</a> ? \<br\>\<br\>.".$msg["cnx_base_err2"];
	print $index_footer;
	exit ;
	}

// affichage du form de login
if ($demo=="") $demo = 0;
header ("Content-Type: text/html; charset=$charset");

if (!$login_error) {
	//Est-on déjà authentifié ?
	if (checkUser('PhpMyBibli')) {
		header("Location: ./main.php");
		exit();
	}
}

print $index_layout;
if ($demo) {
	if (!$login_error) {
		$login_form_demo = str_replace("!!erreur!!", "&nbsp;", $login_form_demo);
		print $login_form_demo;
		} else {
			$login_form_demo = str_replace("!!erreur!!", $login_form_error, $login_form_demo);
			print $login_form_demo;
			}
	} else {
		if (!$login_error) {
			$login_form = str_replace("!!erreur!!", "&nbsp;", $login_form);
			print $login_form;
			} else {
				$login_form = str_replace("!!erreur!!", $login_form_error, $login_form);
				print $login_form;
				}
		}

print form_focus('login', 'user');
print $index_footer;
