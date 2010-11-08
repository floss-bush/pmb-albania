<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: id_notice.inc.php,v 1.1 2009-11-30 16:44:16 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


//droits d'acces lecture notice
$acces_j='';
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_j = $dom_1->getJoin($PMBuserid,4,'notice_id');
}

$param_notice_id = explode(",",$pmb_show_notice_id);
$prefix_id = $param_notice_id[1];
if($prefix_id){
	$f_notice_id = str_replace($prefix_id,"",$f_notice_id);
}

$rqt = "select * from notices where notice_id='".$f_notice_id."'";
$res = mysql_query($rqt,$dbh);

if(mysql_num_rows($res)){
	$ident = mysql_fetch_object($res);	
	if($ident->niveau_biblio == 'a' && $ident->niveau_hierar == '2'){
		$rqt_bull = "select analysis_bulletin from analysis where analysis_notice='".$ident->notice_id."'";
		$res_bull = mysql_query($rqt_bull);
		if(mysql_num_rows($res_bull)){
			$ident_bull = mysql_result($res_bull,0,0);
			print "<script type=\"text/javascript\">";
			print "document.location = \"./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$ident_bull&art_to_show=".$ident->notice_id."\"";
			print "</script>";
		}
	} elseif ($ident->niveau_biblio == 's' && $ident->niveau_hierar == '1'){
		print "<script type=\"text/javascript\">";
		print "document.location = \"./catalog.php?categ=serials&sub=view&serial_id=".$ident->notice_id."\"";
		print "</script>";
	} elseif ($ident->niveau_biblio == 'b' && $ident->niveau_hierar == '2'){
		$rqt_bull = "select bulletin_id from bulletins where num_notice='".$ident->notice_id."'";
		$res_bull = mysql_query($rqt_bull);
		if(mysql_num_rows($res_bull)){	
			$ident_bull = mysql_result($res_bull,0,0);	
			print "<script type=\"text/javascript\">";
			print "document.location = \"./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$ident_bull."\"";
			print "</script>";
		}
	} else {
		print "<script type=\"text/javascript\">";
		print "document.location = \"./catalog.php?categ=isbd&id=".$ident->notice_id."\"";
		print "</script>";
	}
} else {
	error_message($msg[235], $msg['notice_id_query_failed']." ".$f_notice_id, 1, "./catalog.php?categ=search&mode=0");
	die();
}

?>