<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sauvegardes.inc.php,v 1.5 2007-03-14 16:51:33 gueluneau Exp $

//Page des gestion des sauvegardes

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ($include_path."/templates/sauvegardes.tpl.php");
require_once ($class_path."/sauv_sauvegarde.class.php");

$sauv = new sauv_sauvegarde();

//Traitement et affichage du formulaire
$container = str_replace("!!sauvegardes_form!!", $sauv -> proceed(), $container);
//Affichage de l'abre des connexions
$container = str_replace("!!sauvegardes_tree!!", $sauv -> showTree(), $container);

echo $container;
?>