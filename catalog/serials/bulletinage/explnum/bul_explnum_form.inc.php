<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bul_explnum_form.inc.php,v 1.17 2009-07-27 07:13:50 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$include_path/templates/explnum.tpl.php");

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
	
	if (!$explnum_id) {
		error_message('', htmlentities($dom_1->getComment('mod_bull_error'), ENT_QUOTES, $charset), 1, '');
	} else {
		error_message('', htmlentities($dom_1->getComment('mod_enum_error'), ENT_QUOTES, $charset), 1, '');
	}
	
} else {
	
	// affichage des infos du bulletinage pour rappel
	$bulletinage = new bulletinage_display($bul_id);
	print pmb_bidi("<div class='row'><h2>".$bulletinage->display.'</h2></div>');
	
	// l'annulation du form renvoit a :
	$annuler = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$bul_id;
	$action = "./catalog.php?categ=serials&sub=bulletinage&action=explnum_update";
	if($explnum_id) $suppr = "./catalog.php?categ=serials&sub=bulletinage&action=explnum_delete&bul_id=$bul_id&explnum_id=$explnum_id";
	$explnum = new explnum($explnum_id, 0, $bul_id);
	print $explnum->explnum_form($action,$annuler,$suppr);
}
?>

