<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: perso.inc.php,v 1.6 2010-07-22 14:56:28 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/parametres_perso.class.php");

$option_visibilite=array();
$option_visibilite["multiple"]="block";
$option_visibilite["obligatoire"]="block";
$option_visibilite["search"]="block";
$option_visibilite["export"]="block";
$option_visibilite["exclusion"]="block";

$p_perso=new parametres_perso("notices","./admin.php?categ=notices&sub=perso",$option_visibilite);

$p_perso->proceed();

?>