<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: analysis_delete.inc.php,v 1.12 2009-03-13 16:36:15 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

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

		error_message('', htmlentities($dom_1->getComment('mod_bull_error'), ENT_QUOTES, $charset), 1, '');

} else {
	
	// script de suppression d'un dépouillement de périodique 
	
	// mise à jour de l'entête de page
	echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[4023], $serial_header);
	
	$myAnalysis = new analysis($analysis_id, $bul_id);
	$result = $myAnalysis->analysis_delete();
	
	if($result) {
	 	$retour = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$myAnalysis->bulletin_id;
		print "<div class=\"row\"><div class=\"msg-perio\" size=\"+2\">".$msg['catalog_notices_suppression']."</div></div>";
	    print "
			<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
				<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
			</form>
			<script type=\"text/javascript\">document.dummy.submit();</script>
			";
	
	} else {
	    error_message(	$msg['catalog_serie_supp_depouill'] ,
	    			$msg['catalog_serie_supp_depouill_imp'],
	    			1,
	    			"./catalog.php?categ=serials&sub=bulletinage&action=view&serial_id=$serial_id&bul_id=$bul_id");
	}
	
}
?>