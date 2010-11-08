<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: view_bulletins.inc.php,v 1.13 2009-05-16 11:12:04 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage des bulletinages associés
// on récupère le nombre de lignes qui vont bien
$bulletins .= "<form action='".$base_url."' method='post' name='filter_form'><input type='hidden' name='location' value='$location'/><table>" ;
$bulletins .= "<tr><th></th><th>$msg[4025]</th><th>$msg[4026]</th><th>$msg[bulletin_mention_titre_court]</th><th><center>".$msg['bul_articles']."</center></th><th><center>".$msg['bul_docnum']."</center></th><th><center>".$msg['bul_exemplaires']."</center></th></tr>" ;
$bulletins .= "<tr>
		<th></th>
		<th>
			<input type='text' class='saisie-10em' name='aff_bulletins_restrict_numero' onchange='this.form.submit();' value='".htmlentities($aff_bulletins_restrict_numero,ENT_QUOTES, $charset)."'/></th>
		<th><input type='text' class='saisie-10em' name='aff_bulletins_restrict_date' onchange='this.form.submit();' value='".htmlentities($aff_bulletins_restrict_date,ENT_QUOTES, $charset)."'/></th>
		<th></th><th></th><th></th><th></th></tr>" ;


// ici : affichage par page des bulletinages associés
// on lance la vraie requette
$myQuery = mysql_query("SELECT distinct bulletin_id FROM bulletins ".($location?",exemplaires ":"")." WHERE ".($location?"(expl_bulletin=bulletin_id and expl_location='$location') and ":"")." bulletin_notice='$serial_id'  $clause ORDER BY date_date DESC, bulletin_numero*1 DESC, bulletin_id DESC LIMIT $debut,$nb_per_page_a_search", $dbh);

if((mysql_num_rows($myQuery))) {
	$parity=1;
	while(($bul = mysql_fetch_object($myQuery))) {
		$bulletin = new bulletinage($bul->bulletin_id,0,'',$location);
		if ($parity % 2) $pair_impair = "even";
		else $pair_impair = "odd";
		$parity += 1;
        $tr_javascript="  onmousedown=\"document.location='./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$bulletin->bulletin_id."';\" ";
        $tr_surbrillance = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
		$bulletins .= "<tr class='$pair_impair' $tr_surbrillance style='cursor: pointer'><td>" ;
		$bulletins .= "<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title='".$msg[400]."' onClick=\"openPopUp('./cart.php?object_type=BULL&item=".$bulletin->bulletin_id."', 'cart', 600, 700, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes')\">";
		global $base_path;
		$drag="<span id=\"BULL_drag_".$bulletin->bulletin_id."\"  dragicon=\"$base_path/images/icone_drag_notice.png\" dragtext=\"".htmlentities($bulletin->bulletin_numero,ENT_QUOTES, $charset)."\" draggable=\"yes\" dragtype=\"notice\" callback_before=\"show_carts\" callback_after=\"\" style=\"padding-left:7px\"><img src=\"".$base_path."/images/notice_drag.png\"/></span>";
		$bulletins .= "$drag</td><td $tr_javascript >" ;
		$bulletins .= $bulletin->bulletin_numero;
		$bulletins .= "</td><td $tr_javascript >" ;
		if ($bulletin->mention_date) $date_affichee = "(".$bulletin->mention_date.")";
		elseif ($bulletin->date_date) $date_affichee = "[".$bulletin->aff_date_date."]";
		else $date_affichee = "&nbsp;" ;
		$bulletins .= $date_affichee ;
		$bulletins .= "</td><td $tr_javascript >";
		$bulletins .= htmlentities($bulletin->bulletin_titre,ENT_QUOTES, $charset) ;
		$bulletins .= "</td><td><center>" ;
		if ($bulletin->nb_analysis) $bulletins .= $bulletin->nb_analysis."&nbsp;<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title='".$msg[400]."' onClick=\"openPopUp('./cart.php?object_type=BULL&item=".$bulletin->bulletin_id."&what=DEP', 'cart', 600, 700, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes')\">"; 
		else $bulletins .= "&nbsp;";
		$bulletins .= "</center></td><td $tr_javascript ><center>" ;
		if (sizeof($bulletin->nbexplnum)) $bulletins .= $bulletin->nbexplnum; else $bulletins .= "&nbsp;";
		$bulletins .= "</center></td><td $tr_javascript ><center>" ;
		if (sizeof($bulletin->expl)) $bulletins .= sizeof($bulletin->expl); else $bulletins .= "&nbsp;";
		$bulletins .= "</center></td $tr_javascript ></tr>";
	}
	$bulletins .= "</table></form>" ;
} else {
	$bulletins .= "</table><br />" ;
   	if ($aff_bulletins_restrict_date || $aff_bulletins_restrict_numero) $bulletins .= $msg[perio_restrict_no_bulletin] ;
   	else $bulletins .= $msg[4024] ;
}
// barre de navigation par page
$pages_display = aff_pagination ($base_url."&location=$location", $nbr_lignes, $nb_per_page_a_search, $page, 10, false, true) ;
?>
