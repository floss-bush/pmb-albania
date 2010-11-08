<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serial_delete.inc.php,v 1.23 2009-03-13 16:36:16 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg['catalog_serie_suppression'], $serial_header);

//verification des droits de modification notice
$acces_m=1;
if ($serial_id!=0 && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$serial_id,8);
}

if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_seri_error'), ENT_QUOTES, $charset), 1, '');
	
} else {
	print "<div class=\"row\"><div class=\"msg-perio\">".$msg['catalog_notices_suppression']."</div></div>";
	$requete = "select 1 from pret, exemplaires, bulletins, notices where notice_id='$serial_id' and expl_notice=0 ";
	$requete .="and pret_idexpl=expl_id and expl_bulletin=bulletin_id and bulletin_notice=notice_id";
	$result=@mysql_query($requete);
	if (mysql_num_rows($result)) {
		// gestion erreur pret en cours
		error_message($msg[416], $msg[impossible_perio_del_pret], 1, "./catalog.php?categ=serials&sub=view&serial_id=$serial_id");
	} else {
		//suppression du périodique
		$serial = new serial($serial_id);
		$serial->serial_delete();
		
		$retour = "./catalog.php?categ=serials";
		print "
			<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
				<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
			</form>
			<script type=\"text/javascript\">document.dummy.submit();</script>
			";
	}
}
?>