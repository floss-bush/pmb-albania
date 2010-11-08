<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: tables.inc.php,v 1.5 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Page des gestion des lieux de sauvegarde

require_once ($include_path."/templates/tables.tpl.php");
require_once ($class_path."/sauv_tables.class.php");

$sauv = new sauv_table();

//Traitement et affichage du formulaire
$container = str_replace("!!tables_form!!", $sauv -> proceed(), $container);
//Affichage de l'abre des connexions
$container = str_replace("!!tables_tree!!", $sauv -> showTree(), $container);

echo $container;
?>