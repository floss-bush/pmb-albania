<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function affichage_liste_bulletins_normale($res) {
	global $charset, $dbh;
	
	while(($tableau=mysql_fetch_array($res))) {
	
		$sql = "SELECT COUNT(1) FROM explnum WHERE explnum_bulletin='".$tableau["bulletin_id"]."'";
		$result = @mysql_query($sql, $dbh);
		$count=mysql_result($result, 0, 0);
		
		print "<span class='liste_bulletins'>";
		if ($count){
			$padding = "";
			print '<img src="./images/attachment.png">';
		}
		else 
			$padding = "style=\"padding-left:11px;\"";
		
		print "<a href=./index.php?lvl=bulletin_display&id=".$tableau['bulletin_id']." ".$padding.">".$tableau['bulletin_numero'];
		if ($tableau['mention_date']) print pmb_bidi(" (".$tableau['mention_date'].")\n"); 
			elseif ($tableau['date_date']) print pmb_bidi(" (".formatdate($tableau['date_date']).")\n");
		if ($tableau['bulletin_titre']) print pmb_bidi(" : ".htmlentities($tableau['bulletin_titre'],ENT_QUOTES, $charset)."\n"); 
		print "</a> ;";
		print "</span>\n";
	}
}

function affichage_liste_bulletins_tableau($res) {
	global $charset,$msg;

	print "<table cellpadding='2' class='exemplaires' width='100%'><tr><th><b>".$msg[bull_numero]."</b></th><th><b>".$msg[bull_mention_date]."</b></th><th><b>".$msg['etat_collection_title']."</b></th></tr>";
	$odd_even=1;
	while(($tableau=mysql_fetch_array($res))) {

		if ($odd_even==0) {
			$pair_impair="odd";
			$odd_even=1;
			} else if ($odd_even==1) {
				$pair_impair="even";
				$odd_even=0;
				}
		$tr_javascript=" class='$pair_impair' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./index.php?lvl=bulletin_display&id=".$tableau['bulletin_id']."';\" style='cursor: pointer' ";
		print "<tr $tr_javascript><td>".$tableau['bulletin_numero'];
		print "</td><td>";
		if ($tableau['mention_date']) print pmb_bidi(" ".$tableau['mention_date']."\n"); 
		elseif ($tableau['date_date']) print pmb_bidi(" ".formatdate($tableau['date_date'])."\n");
		print "</td><td>";
		if ($tableau['bulletin_titre']) print pmb_bidi(" ".htmlentities($tableau['bulletin_titre'],ENT_QUOTES, $charset)."\n"); 
		print "</td></tr>";
	}
	print "</table><br /><br />";
}