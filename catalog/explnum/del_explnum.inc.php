<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: del_explnum.inc.php,v 1.8 2009-07-03 09:35:43 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//verification des droits de modification notice
$acces_m=1;
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$id,8);
}

if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_enum_error'), ENT_QUOTES, $charset), 1, '');
	
} else {

	print "<div class=\"row\"><h1>${msg[313]}</h1></div>";
	
	$expl = new explnum($explnum_id);
	$expl->delete();
	
	print "<div class='row'><div class='msg-perio'>".$msg[maj_encours]."</div></div>";
	$id_form = md5(microtime());
	$retour = "./catalog.php?categ=isbd&id=$id";
	print "
		<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
			<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
			</form>
		<script type=\"text/javascript\">document.dummy.submit();</script>
		</div>
		";
}
?>