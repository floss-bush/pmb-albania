<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ana_explnum_delete.inc.php,v 1.9 2009-07-03 09:35:43 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// suppression d'un exemplaire de bulletinage
echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[explnum_doc_associe], $serial_header);

//verification des droits de modification notice
$acces_m=1;
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_j = $dom_1->getJoin($PMBuserid,8,'bulletin_notice');
	$q = "select count(1) from bulletins $acces_j where bulletin_id=".$bul_id;
	$r = mysql_query($q, $dbh);
	if(mysql_result($r,0,0)==0) {
		$acces_m=0;
	}
}

if ($acces_m==0) {

		error_message('', htmlentities($dom_1->getComment('mod_depo_error'), ENT_QUOTES, $charset), 1, '');

} else {	
	print "<div class=\"row\"><div class=\"msg-perio\">".$msg['catalog_notices_suppression']."</div></div>";
	
	$expl = new explnum($explnum_id);
	$expl->delete();
	
	$id_form = md5(microtime());
	$retour = "./catalog.php?categ=serials&sub=view&sub=bulletinage&action=view&bul_id=$bul_id";
	print "
		<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
			<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
		</form>
	<script type=\"text/javascript\">document.dummy.submit();</script>
	";
}

?>