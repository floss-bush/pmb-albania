<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$include_path/templates/explnum.tpl.php");

echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[explnum_doc_associe], $serial_header);


//verification des droits de modification notice
$acces_m=1;
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$serial_id,8);
}

if ($acces_m==0) {

	if (!$explnum_id) {
		error_message('', htmlentities($dom_1->getComment('mod_seri_error'), ENT_QUOTES, $charset), 1, '');
	} else {
		error_message('', htmlentities($dom_1->getComment('mod_enum_error'), ENT_QUOTES, $charset), 1, '');
	}
		
} else {
	// affichage des infos du bulletinage pour rappel
	$perio = new serial_display($serial_id, 0);
	print pmb_bidi("<div class='row'><h2>".$perio->result.'</h2></div>');
	
	// l'annulation du form renvoit a :
	$annuler = "./catalog.php?categ=serials&sub=view&serial_id=".$serial_id;
	$action = "./catalog.php?categ=serials&sub=explnum_update";
	if($explnum_id) $suppr = "./catalog.php?categ=serials&sub=explnum_delete&serial_id=$serial_id&explnum_id=$explnum_id";
	$explnum = new explnum($explnum_id, $serial_id, 0);
	print $explnum->explnum_form($action,$annuler,$suppr);
}
?>
