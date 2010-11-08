<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: last_records.inc.php,v 1.19 2009-11-30 10:39:25 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage de l'entête de page
print "<div class=\"row\"><h1>${msg[938]}</h1></div>";

// affichage des notices
print "<div class=\"row\">";

// javascript gestion de liste
print $begin_result_liste;

if (!$last_records) $last_records=$pmb_nb_lastnotices;
if ($plus) $last_records = $last_records + $plus; 

//gestion des acces en lecture
$acces_j='';
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_j = $dom_1->getJoin($PMBuserid,4,'notice_id');
} 

if (!$pmb_latest_order) $pmb_latest_order="create_date desc, notice_id desc";
$requete = "SELECT * FROM notices ";
$requete.= $acces_j;
$requete.= "ORDER BY $pmb_latest_order LIMIT $last_records";

$result = mysql_query($requete, $dbh);
if (mysql_num_rows($result)) {
	while(($notice = mysql_fetch_object($result))) {
		if (($notice->niveau_biblio =='s' || $notice->niveau_biblio =='a') && ($notice->niveau_hierar== 1 || $notice->niveau_hierar== 2)) {
			$link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
			$link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
			$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
			$link_explnum = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";
			$serial = new serial_display($notice, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum, 0, 0,1, 1);
			print pmb_bidi($serial->result);
		} elseif ($notice->niveau_biblio=='m' && $notice->niveau_hierar== 0) { 
			$link = './catalog.php?categ=isbd&id=!!id!!';
			$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
			$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!'; 
			// function mono_display($id, $level=1, $action='', $expl=1, $expl_link='', $lien_suppr_cart="", $explnum_link='', $show_resa=0, $print=0, $show_explnum=1, $show_statut=0, $anti_loop='', $draggable=0, $no_link=false, $show_opac_hidden_fields=true ) {
			$display = new mono_display($notice, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1,"", 1, false, true);
			print pmb_bidi($display->result);
        } elseif ($notice->niveau_biblio=='b' && $notice->niveau_hierar==2) { // on est face à une notice de bulletin
        	$requete_suite = "SELECT bulletin_id, bulletin_notice FROM bulletins where num_notice='".$notice->notice_id."'";
        	$result_suite = mysql_query($requete_suite, $dbh) or die("<br /><br />".mysql_error()."<br /><br />");
        	$notice_suite = mysql_fetch_object($result_suite);
        	$notice->bulletin_id=$notice_suite->bulletin_id;
        	$notice->bulletin_notice=$notice_suite->bulletin_notice;
			$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id='.$notice->bulletin_id;
			$display = new mono_display($notice, 6, $link_bulletin, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1);
			print $display->result;
		}
	}
	$plus = $plus + $pmb_nb_lastnotices;
	print "<a href='./catalog.php?categ=last_records&plus=$plus'>...</a>";
} else {
   	print $msg[939];
}

print $end_result_list;
print "</div>";
