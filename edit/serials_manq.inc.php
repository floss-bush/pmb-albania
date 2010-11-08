<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serials_manq.inc.php,v 1.9 2007-09-07 08:52:59 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusion du template de gestion des périodiques
require_once("$include_path/templates/serials.tpl.php");
require_once("./catalog/serials/serial_func.inc.php");


$base_url = "./catalog.php?categ=serials&sub=search&user_query=$user_query";

if (!$user_query) $user_query ="*" ;
$user_query = str_replace("*","%",$user_query ); 


$serial_edit_access = str_replace('!!message!!',$msg[1914] , $serial_edit_access);
$serial_edit_access = str_replace('!!etat!!',manquant , $serial_edit_access);

print $serial_edit_access;

// nombre de références par page
if ($nb_per_page_empr != "") $nb_per_page = $nb_per_page_empr ;
	else $nb_per_page = 10;


// comptage du nombre de résultats
$count_query = mysql_query("SELECT COUNT(notice_id) FROM notices WHERE index_sew like '".$user_query."' AND niveau_biblio='s' AND niveau_hierar='1'");
$nbr_lignes = mysql_result($count_query, 0, 0);

if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;

if($nbr_lignes) {
	$myQuery = mysql_query(" SELECT notices.notice_id, notices.tit1, notices.ed1_id, bulletins.bulletin_id, bulletins.mention_date, bulletins.date_date, bulletins.bulletin_numero, exemplaires.expl_id, publishers.ed_name, publishers.ed_pays
		FROM notices, publishers
		LEFT JOIN bulletins ON bulletins.bulletin_notice = notices.notice_id
		LEFT JOIN exemplaires ON bulletins.bulletin_id = exemplaires.expl_bulletin
		WHERE notices.ed1_id = publishers.ed_id AND niveau_biblio = 's' AND niveau_hierar = '1'
		and ISNULL( expl_id )
		and index_sew like '".$user_query."' AND ORDER BY TIT1, notice_id, bulletins.date_date, bulletins.bulletin_numero, bulletin_id LIMIT  $debut,$nb_per_page ");

	// Variable permettant de conserver les valeurs precedentes pour calcul du nombre
	$parity=1;
	$wnotice_id=0;
	$wbulletin_id=0;
	$wtit1="";
	$wed_name="";
	$wed_pays="";
	$wmention_date="";
	$wbulletin_numero="";
	$nbexemplaires = 0;
	$cpt_notice = 1;

	while($serial=mysql_fetch_object($myQuery)) {
			if ($parity % 2) {
			$pair_impair = "even";
			} else {
				$pair_impair = "odd";
				}
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./catalog.php?categ=serials&sub=view&serial_id=$wnotice_id';\" ";
		if ($wnotice_id != $serial->notice_id or $wbulletin_id != $serial->bulletin_id) {
			if ($wnotice_id != 0) {
				if ($wnotice_id == $serial->notice_id) {
				# affichage du titre seulement lors de la premiere notice
					if ($cpt_notice == 1){
						$serial_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
						$serial_list .= "		<td>
							<strong>$wtit1</strong>
							</td>
							<td>
							$wed_name
							</td>
							<td>
							$wed_pays
							</td>";
						}
					else
						{
							$serial_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
							$serial_list .= "		<td>
							</td>
							<td>
							</td>
							<td>
							</td>";
						}
				$cpt_notice=0;
				}
				else
				{
					$parity += 1;
					$cpt_notice=1;
					$serial_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
					$serial_list .= "		<td>
							</td>
							<td>
							</td>
							<td>
							</td>";
				}
				$serial_list .= "		<td>
						$wmention_date
						</td>
						<td>
						$wbulletin_numero
						</td>
					</tr>";
			}
			$wnotice_id=$serial->notice_id;
			$wtit1=$serial->tit1;
			$wed_name=$serial->ed_name;
			$wed_pays=$serial->ed_pays;

			$wbulletin_id=$serial->bulletin_id;
			$wmention_date=$serial->mention_date;
			$wbulletin_numero=$serial->bulletin_numero;


		}
	}

	// Affichage dernier element
			if ($cpt_notice == 1){
				$serial_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
				$serial_list .= "		<td>
					<strong>$wtit1</strong>
					</td>
					<td>
					$wed_name
					</td>
					<td>
					$wed_pays
					</td>";
				}
				else
				{
					$serial_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
					$serial_list .= "		<td>
					</td>
					<td>
					</td>
					<td>
					</td>";
				}
				$serial_list .= "		<td>
						$wmention_date
						</td>
						<td>
						$wbulletin_numero
						</td>
					</tr>";

	$myQuery->clean_query();

	// constitution des liens

	$nbepages = ceil($nbr_lignes/$nb_per_page);
	$suivante = $page+1;
	$precedente = $page-1;

	// affichage du lien prÔø‡Ôø‡ent si nÔø‡Ôø‡saire

	if($precedente > 0){
		$nav_bar .= "<a href='$PHP_SELF?categ=serial&sub=collect&page=$precedente&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."'>
					<img src='./images/left.gif' border='0' title='$msg[48] alt='[$msg[48]]' hspace='3' align='middle'></a>";
					}

	for($i = 1; $i <= $nbepages; $i++) {
		if($i==$page)
			$nav_bar .= "<strong>page $i/$nbepages</strong>";
		}


	if($suivante<=$nbepages)
		$nav_bar .= "<a href='$PHP_SELF?categ=serial&sub=collect&page=$suivante&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."'>
					<img src='./images/right.gif' border='0' title='$msg[49] alt='[$msg[49]]' hspace='3' align='middle'></a>";

	// affichage du rÔø‡ultat

	list_serial($user_query, $serial_list, $nav_bar);

} else {
	// la requÔø‡e n'a produit aucun rÔø‡ultat
	error_message($msg[46], str_replace('!!user_query!!', $user_query, $msg[1153]), 1, './edit.php?categ=serials&sub=collect');
}

?>
