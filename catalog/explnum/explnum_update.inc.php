<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: explnum_update.inc.php,v 1.14 2009-07-03 09:35:43 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//verification des droits de modification notice
$acces_m=1;
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$f_notice,8);
}

if ($acces_m==0) {
	
	if (!$f_explnum_id) {
		error_message('', htmlentities($dom_1->getComment('mod_noti_error'), ENT_QUOTES, $charset), 1, '');
	} else {
		error_message('', htmlentities($dom_1->getComment('mod_enum_error'), ENT_QUOTES, $charset), 1, '');
	}
	
} else {	
	$retour = "./catalog.php?categ=isbd&id=$f_notice";
	
	$explnum = new explnum($f_explnum_id);
	$explnum->mise_a_jour($f_notice, $f_bulletin, $f_nom, $f_url, $retour, $conservervignette, $f_statut_chk);	
}
?>