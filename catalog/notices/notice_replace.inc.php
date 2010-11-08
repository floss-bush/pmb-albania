<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_replace.inc.php,v 1.2 2009-03-13 16:36:14 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de remplacement notice

//verification des droits de modification notice
$acces_m=1;
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$id,8);
}

if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_noti_error'), ENT_QUOTES, $charset), 1, '');

} else {

	print "<h1>$msg[catal_rep_not_h1]</h1>";
	if(!$by) {
		require_once("$include_path/templates/catal_form.tpl.php");
		$notice = new notice($id);
		$notice->replace_form();
	} else {
		// routine de remplacement
		$notice = new notice($id);
		$rep_result = $notice->replace($by);
		if(!$rep_result)
			print "<div class='row'><div class='msg-perio'>".$msg["maj_encours"]."</div></div>
				<script type=\"text/javascript\">document.location='./catalog.php?categ=isbd&id=$by'</script>";
			
		else {
			error_message($msg[132], $rep_result, 1, "./catalog.php?categ=modif&id=$id");
		}
	}
}
?>