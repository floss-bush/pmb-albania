<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_z3950_replace.inc.php,v 1.2 2009-03-13 16:36:14 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de remplacement notice par z3950

//verification des droits de modification notice
$acces_m=1;
if ($id_notice!=0 && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$id_notice,8);
}

if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_noti_error'), ENT_QUOTES, $charset), 1, '');

} else {

	if ($z3950_accessible) {
		// menage dans les trucs un peu vieux qui ont ete remontes
		// on delete ce qui est vieux de plus de deux jours.
		$rqt = "select zquery_id from z_query where zquery_date < date_sub(now(),INTERVAL 2 DAY) ";
		$res_zquery=mysql_query($rqt,$dbh);
		while ($ligne=mysql_fetch_array($res_zquery)) {
			$zquery_id=$ligne["zquery_id"];
			$rqt_notices = "delete from z_notices where znotices_query_id ='".$zquery_id."' ";
			$res=mysql_query($rqt_notices,$dbh);
			$rqt_query = "delete from z_query where zquery_id ='".$zquery_id."' ";
			$del_znotices=mysql_query($rqt_query,$dbh);
		}
		include('./catalog/z3950/main.inc.php');
	}

}
?>