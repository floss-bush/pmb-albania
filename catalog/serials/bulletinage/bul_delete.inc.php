<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bul_delete.inc.php,v 1.17 2009-03-13 16:36:16 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// script de suppression d'un bulletinage

// mise à jour de l'entete de page
echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg['catalog_serie_supp_bull'], $serial_header);


//verification des droits de modification notice
$acces_m=1;
if ($bul_id && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_j = $dom_1->getJoin($PMBuserid,8,'bulletin_notice');
	$q = "select count(1) from bulletins $acces_j where bulletin_id = $bul_id ";
	$r = mysql_query($q, $dbh);
	if(mysql_result($r,0,0)==0) {
		$acces_m=0;
	}
}

if ($acces_m==0) {
	
	error_message('', htmlentities($dom_1->getComment('mod_bull_error'), ENT_QUOTES, $charset), 1, '');
		
} else {


	print "<div class=\"row\"><div class=\"msg-perio\">".$msg['catalog_notices_suppression']."</div></div>";
	
	$requete = "select 1 from pret, exemplaires, bulletins where bulletin_id='$bul_id' ";
	$requete .="and pret_idexpl=expl_id and expl_bulletin=bulletin_id ";
	$result=@mysql_query($requete);
	if (mysql_num_rows($result)) {
		// gestion erreur pret en cours
		error_message($msg[416], $msg[impossible_bull_del_pret], 1, "catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$bul_id");
	
	} else {
	
		$myBulletinage = new bulletinage($bul_id);
		$myBulletinage->delete();		
		
		$retour =  "./catalog.php?categ=serials&sub=view&serial_id=".$myBulletinage->bulletin_notice;
		
		// form de retour vers la page de gestion du periodique chapeau (auto-submit)
		print "
			<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
				<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
			</form>
			<script type=\"text/javascript\">document.dummy.submit();</script>";
	}
}
?>