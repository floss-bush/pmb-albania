<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notices.inc.php,v 1.12 2007-10-01 17:25:49 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($sub) {
	case "resa_a_traiter" :
		echo "<h1>".$msg[350]."&nbsp;&gt;&nbsp;".$msg['edit_resa_menu_a_traiter']."</h1>";
		
		$tableau_resa = resa_list_resa_a_traiter () ;
		// echo "<pre>" ; print_r($tableau_resa); echo "</pre>" ;
		for ($j=0; $j< count($tableau_resa); $j++) {
			if ($no_notice!=$tableau_resa[$j]['resa_idnotice'] || $no_bulletin!=$tableau_resa[$j]['resa_idbulletin']) {
				$no_notice=$tableau_resa[$j]['resa_idnotice'] ;
				$no_bulletin=$tableau_resa[$j]['resa_idbulletin'] ;
				$tableau_expl_dispo = expl_dispo ($no_notice, $no_bulletin) ;
				// echo "<pre>" ; print_r($tableau_expl_dispo); echo "</pre>" ;
				$i = 0 ;
				if ($tableau_expl_dispo[$i]['location']) {
					$aff_final .= "<tr><th colspan=7><b>".$tableau_resa[$j]['resa_tit']."</b></th></tr>";
					$aff_final .= "<tr>
						<th>".$msg[366]."</th>
						<th>".$msg[empr_nom_prenom]."</th>
						<th>$msg[298]</th>
						<th>$msg[295]</th>
						<th>$msg[296]</th>
						<th>$msg[297]</th>
						<th>$msg[293]</th>
						</tr>";
				}
			} else $i++ ;
			if ($tableau_expl_dispo[$i]['location']) {
				$aff_final .= "<tr>
						<td>".$tableau_resa[$j]['rank']."</td>
						<td>".$tableau_resa[$j]['resa_empr']."</td>
						<td>".$tableau_expl_dispo[$i]['location']."</td>
						<td>".$tableau_expl_dispo[$i]['section']."</td>
						<td>".$tableau_expl_dispo[$i]['expl_cote']."</td>
						<td>".$tableau_expl_dispo[$i]['statut']."</td>
						<td>".$tableau_expl_dispo[$i]['expl_cb']."</td></tr>";
			}
		}
		if ($aff_final) print pmb_bidi("\n\n<table border='0' >$aff_final</table>\n\n") ;
		if (SESSrights & EDIT_AUTH) print pmb_bidi("<a href='./circ.php?categ=listeresa&sub=encours'>".$msg['lien_traiter_reservations']."<a>");
		// echo "<pre>"; print_r($tableau); echo "</pre>";
		break;
	case "resa" :
	default:
		echo "<h1>".$msg[350]."&nbsp;&gt;&nbsp;".$msg['edit_resa_menu']."</h1>";
		print resa_list (0, 0, 0) ;
		break;
	}
