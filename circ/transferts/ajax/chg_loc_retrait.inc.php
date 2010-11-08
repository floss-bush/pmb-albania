<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: chg_loc_retrait.inc.php,v 1.1 2008-06-04 14:54:25 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$rqt = "UPDATE resa SET resa_loc_retrait=".$loc." WHERE id_resa=".$id;

mysql_query($rqt);

$rqt = "SELECT trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit ".
		" FROM (((resa LEFT JOIN notices AS notices_m ON resa_idnotice = notices_m.notice_id ) ".
			" LEFT JOIN bulletins ON resa_idbulletin = bulletins.bulletin_id) ".
			" LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id) ".
		"WHERE id_resa=".$id;
$lib_titre = mysql_result(mysql_query($rqt),0);
$message = str_replace("!!titre!!",$lib_titre,$msg["transferts_circ_resa_msg_chg_loc_retrait"]);

ajax_http_send_response($message,"text/xml");

?>