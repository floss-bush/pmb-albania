<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_form.inc.php,v 1.6 2009-03-13 16:36:14 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de catalogage

//droits d'acces utilisateur/notice (modification)
$acces_m=1;
if ($id!=0 && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$id,8);
}
if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_noti_error'), ENT_QUOTES, $charset), 1, '');

} else {

	// affichage du form de création/modification d'une notice
	$myNotice = new notice($id, $saisieISBN);
	print $myNotice->show_form();	
}
?>