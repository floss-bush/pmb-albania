<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: retour.inc.php,v 1.33 2010-07-06 10:08:48 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/emprunteur.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/comptes.class.php");
require_once("$class_path/amende.class.php");
require_once("$include_path/resa.inc.php");
require_once("$class_path/expl_to_do.class.php");

// gestion des retours
if ($_GET['cb_expl']) {
	$form_cb_expl=$_GET['cb_expl'];
	$_GET['cb_expl']='';
	$confirmed=1;
} else {
	if ($pmb_confirm_retour) $confirmed=0;
		else $confirmed=1;
}	

$expl=new expl_to_do($form_cb_expl);

if($form_cb_expl) {
	$expl->do_form_retour($action_piege,$piege_resa);
}
print $expl->cb_tmpl.$expl->expl_form;

