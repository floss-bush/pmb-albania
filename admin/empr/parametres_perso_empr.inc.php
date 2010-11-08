<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: parametres_perso_empr.inc.php,v 1.9 2010-07-22 14:56:27 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/parametres_perso.class.php");

$option_visibilite=array();
$option_visibilite["multiple"]="block";
$option_visibilite["obligatoire"]="block";
$option_visibilite["search"]="none";
$option_visibilite["export"]="none";
$option_visibilite["exclusion"]="none";

$p_perso=new parametres_perso("empr","./admin.php?categ=empr&sub=parperso",$option_visibilite);

$p_perso->proceed();

?>