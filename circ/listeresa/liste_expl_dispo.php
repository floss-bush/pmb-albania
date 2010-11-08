<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_expl_dispo.php,v 1.6 2010-05-21 07:10:19 ngantier Exp $

$base_path="./../..";
$base_auth = "CIRCULATION_AUTH";
$base_title = "\$msg[5]";
//permet d'appliquer le style de l'onglet ou apparait la frame
$current_alert = "circ";

require_once ("$base_path/includes/init.inc.php");

$rqt = "SELECT ".
			"trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, ".
			"expl_cb, ".
			"location_libelle, ".
			"expl_id , 
			lender_libelle ".
		"FROM (((exemplaires ".
			"LEFT JOIN notices AS notices_m ON expl_notice=notices_m.notice_id) ".
			"LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) ".
			"LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id) ".
			"INNER JOIN docs_location ON expl_location=idlocation ".
			"INNER JOIN docs_statut ON expl_statut=idstatut ".
			"INNER JOIN lenders ON idlender=expl_owner " .
		"WHERE ".
			"pret_flag=1 ".
			"AND expl_notice=".$idnotice." ".
			"AND expl_bulletin=".$idbulletin." ".
			"AND expl_location<>".$loc." ".
		"ORDER BY transfert_ordre";

//echo $rqt;
$res = mysql_query($rqt);
$st = "odd";
while (($data = mysql_fetch_array($res))) {
	$sel_expl=1;
	$statut="";
	$req_res = "select count(1) from resa where resa_cb='".addslashes($data[1])."' and resa_confirmee='1'";
	$req_res_result = mysql_query($req_res, $dbh);
	if(mysql_result($req_res_result, 0, 0)) {					
		$statut=$msg["transferts_circ_resa_expl_reserve"];
		$sel_expl=0;
	}
	$req_pret = "select date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour  from pret where pret_idexpl='".$data[3]."' ";
	$req_pret_result = mysql_query($req_pret, $dbh);
	if(mysql_num_rows($req_pret_result)) {					
		//$statut=$msg["transferts_circ_resa_expl_en_pret"]."()";
		$statut=$msg[358]." ".mysql_result($req_pret_result, 0,0);
		$sel_expl=0;
	}	
	if ($st=="odd")
		$st = "even";
	else
		$st = "odd";
	if($sel_expl) {
		$liste .= 	"<tr class='" .$st ."' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='" . $st ."'\"  style='cursor: pointer'>
						<td onclick=\"parent.selExpl('".$data[1]."',$id_resa)\">".$data[0]."</td>
						<td onclick=\"parent.selExpl('".$data[1]."',$id_resa)\">".$data[1]."</td>
						<td onclick=\"parent.selExpl('".$data[1]."',$id_resa)\">".$data[2]."</td>
						<td onclick=\"parent.selExpl('".$data[1]."',$id_resa)\">".$data[4]."</td>
						<td onclick=\"parent.selExpl('".$data[1]."',$id_resa)\">".$statut."</td>
					</tr>";
	} else{
		$liste .= 	"<tr class='" .$st ."' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='" . $st ."'\"  style='cursor: pointer'>
						<td>".$data[0]."</td>
						<td>".$data[1]."</td>
						<td>".$data[2]."</td>
						<td>".$data[4]."</td>
						<td class='erreur'>".$statut."</td>
					</tr>";
	}	
}

$global = "
<div class='row'>
	<div class='right'><a href='#' onClick='parent.kill_frame_expl();return false;'><img src='" . $base_path . "/images/close.gif' border='0' align='right'></a></div>
	<h3>" . $msg["transferts_circ_resa_lib_choix_expl"] . "</h3>
	<table>
		<tr>
			<th>" . $msg["transferts_circ_resa_titre_titre"] . "</th>
			<th>" . $msg["transferts_circ_resa_titre_cb"] . "</th>
			<th>" . $msg["transferts_circ_resa_titre_localisation"] . "</th>
			<th align='left'>".$msg[651]."</th>
			<th></th>
		</tr>
		!!liste!!
	</table>
</div>";

echo str_replace("!!liste!!",$liste,$global);

echo "</body></html>";

mysql_close($dbh);

?>