<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: caddie_func.inc.php,v 1.8 2008-01-25 14:59:52 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// form de saisie cb expl
function get_cb_expl($title, $title_form, $form_action, $check=0) {

global $expl_cb_caddie_tmpl;
global $script1expl;
global $script2expl;
global $idcaddie;
if($check) {
	$expl_cb_caddie_tmpl = str_replace("!!script!!", $script2expl, $expl_cb_caddie_tmpl);
	} else {
		$expl_cb_caddie_tmpl = str_replace("!!script!!", $script1expl, $expl_cb_caddie_tmpl);
		}
$expl_cb_caddie_tmpl = str_replace("!!titre_formulaire!!", $title_form, $expl_cb_caddie_tmpl);
$expl_cb_caddie_tmpl = str_replace("!!form_action!!", $form_action, $expl_cb_caddie_tmpl);
$expl_cb_caddie_tmpl = str_replace("!!title!!", $title, $expl_cb_caddie_tmpl);
$expl_cb_caddie_tmpl = str_replace("!!message!!", "", $expl_cb_caddie_tmpl);
$expl_cb_caddie_tmpl = str_replace("!!idcaddie!!", "$idcaddie",$expl_cb_caddie_tmpl );
return $expl_cb_caddie_tmpl;
}
