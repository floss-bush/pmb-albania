<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bul_expl_delete.inc.php,v 1.17 2010-05-05 12:28:39 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// suppression d'un exemplaire de bulletinage
echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[313], $serial_header);


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

		error_message('', htmlentities($dom_1->getComment('mod_expl_error'), ENT_QUOTES, $charset), 1, '');

} else {
	
	print "<div class=\"row\"><div class=\"msg-perio\">".$msg['catalog_notices_suppression']."</div></div>";
	
	$requete = "select 1 from pret where pret_idexpl='$expl_id' ";
	$result=@mysql_query($requete);
	if (mysql_num_rows($result)) {
		// gestion erreur prêt en cours
		error_message($msg[416], $msg[impossible_expl_del_pret], 1, "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$bul_id");
	} else {
	
		// préparation de la requête
		$requete = "DELETE FROM exemplaires WHERE expl_id='$expl_id' AND expl_bulletin='$bul_id' LIMIT 1";
		$myQuery = mysql_query($requete, $dbh);
		audit::delete_audit (AUDIT_EXPL, $expl_id) ;
	
		$query_caddie = "select caddie_id from caddie_content, caddie where type='EXPL' and object_id in ($expl_id) and caddie_id=idcaddie ";
		$result_caddie = @mysql_query($query_caddie, $dbh);
		while($cad = mysql_fetch_object($result_caddie)) {
			$req_suppr_caddie="delete from caddie_content where caddie_id = '$cad->caddie_id' and object_id in ($expl_id) " ;
			@mysql_query($req_suppr_caddie, $dbh);
		}
	
		$p_perso=new parametres_perso("expl");
		$p_perso->delete_values($expl_id);

		// nettoyage transfert
		$requete_suppr = "delete from transferts_demande where num_expl='$expl_id'";
		$result_suppr = mysql_query($requete_suppr);
		
		$retour = "./catalog.php?categ=serials&sub=view&sub=bulletinage&action=view&bul_id=$bul_id";
		print "<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
			<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
			</form>
			<script type=\"text/javascript\">document.dummy.submit();</script>";
	}

}
?>
		

