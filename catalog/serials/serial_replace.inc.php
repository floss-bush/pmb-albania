<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serial_replace.inc.php,v 1.2 2009-03-13 16:36:16 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//verification des droits de modification notice
$acces_m=1;
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$serial_id,8);
}

if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_seri_error'), ENT_QUOTES, $charset), 1, '');

} else {
		
	print pmb_bidi("<h1>$msg[catal_rep_per_h1]</h1>");
	if(!$by) {
		$perio = new serial($serial_id);
		$perio->replace_form();
	} else {
		// routine de remplacement
		$perio = new serial($serial_id);
		$rep_result = $perio->replace($by);
		if(!$rep_result) {
			print pmb_bidi("<div class='row'><div class='msg-perio'>".$msg["maj_encours"]."</div></div>
						<script type=\"text/javascript\">document.location='./catalog.php?categ=serials&sub=view&serial_id=$by'</script>");
		} else {
			error_message($msg[132], $rep_result, 1, "./catalog.php?categ=serials&sub=view&serial_id=$id");
		}
	}
}
?>