<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serial_form.inc.php,v 1.8 2009-03-13 16:36:15 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


//verification des droits de modification notice
$acces_m=1;
if ($id!=0 && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$id,8);
}

if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_seri_error'), ENT_QUOTES, $charset), 1, '');

} else {
	
	// affichage d'un form pour création, modification d'un périodique
	if(!$id) {
		// pas d'id, c'est une création
		echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[4003], $serial_header);
		} else {
			echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[4004], $serial_header);
			}
	
	$mySerial = new serial($id);
	echo $mySerial->do_form();

}
?>