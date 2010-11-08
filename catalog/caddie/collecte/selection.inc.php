<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: selection.inc.php,v 1.18 2009-05-16 11:12:02 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idcaddie) {
	$myCart = new caddie($idcaddie);
	print pmb_bidi(aff_cart_titre ($myCart));
	$droit = verif_droit_proc_caddie($id) ;
	switch ($action) {
		case 'form_proc' :
			$hp = new parameters ($id) ;
			$hp->gen_form("./catalog.php?categ=caddie&sub=collecte&moyen=selection&action=add_item&idcaddie=$idcaddie&id=$id") ;
			break;
		case 'add_item':
			if ($droit) {
				$hp = new parameters ($id) ;
				$hp->get_final_query();
				echo "<hr />".$hp->final_query."<hr />"; ;
				
				$line = pmb_split("\n", $hp->final_query);
				$nb_element_avant = $myCart->nb_item;
				while(list($cle, $valeur)= each($line)) {
					if ($valeur != '') {
						if ( (pmb_strtolower(pmb_substr($valeur,0,6))=="select") || (pmb_strtolower(pmb_substr($valeur,0,6))=="create")) {
							} else {
								echo pmb_substr($valeur,0,6);
								error_message_history($msg['caddie_action_invalid_query'],$msg['requete_selection'],1);
								exit();
							}
						if (!explain_requete($valeur)) die("<br /><br />".$valeur."<br /><br />".$msg["proc_param_explain_failed"]."<br /><br />".$erreur_explain_rqt); 
						$result_selection = mysql_query($valeur, $dbh);
						if (!$result_selection) {
							error_message_history($msg['caddie_action_invalid_query'],$msg['requete_echouee'].mysql_error(),1);
							exit();
							}
						if (pmb_strtolower(pmb_substr($valeur,0,6))=="select") {
							$nb_element_a_ajouter += mysql_num_rows($result_selection);
							if(mysql_num_rows($result_selection)) {
								while ($obj_selection = mysql_fetch_object($result_selection)) 
									$myCart->add_item($obj_selection->object_id,$obj_selection->object_type);
								} // fin if mysql_num_rows
								$myCart->compte_items();
							} // fin if rqt sélection
						} //fin valeur nonvide
					} // fin while list $cle
				$nb_element_apres = $myCart->nb_item;
				$msg["caddie_affiche_nb_ajouts"] = str_replace('!!nb_a_ajouter!!', $nb_element_a_ajouter, $msg["caddie_affiche_nb_ajouts"]);
				$msg["caddie_affiche_nb_ajouts"] = str_replace('!!nb_ajoutes!!', ($nb_element_apres-$nb_element_avant), $msg["caddie_affiche_nb_ajouts"]);
				$res_exec="<hr />$msg[caddie_affiche_nb_ajouts]<hr />";
				print pmb_bidi($res_exec);
				} // fin if $droit
			print aff_cart_nb_items ($myCart) ;
			break;
		default:
			print aff_cart_nb_items ($myCart) ;
			show_procs($idcaddie);
			break;
		}
	} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=collecte&moyen=selection", "", $msg["caddie_select_ajouter"], "", 0, 0, 0);

function show_procs($idcaddie) {
	global $msg;
	global $PMBuserid;
	global $dbh;
	
	print "<hr />$msg[caddie_select_proc]<br /><table>";
	// affichage du tableau des procédures
	if ($PMBuserid!=1) $where=" and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') ";
	$requete = "SELECT idproc, type, name, requete, comment, autorisations, parameters FROM caddie_procs WHERE type='SELECT' $where ORDER BY name ";
	$res = mysql_query($requete, $dbh);

	$nbr = mysql_num_rows($res);

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_row($res);
		$rqt_autorisation=explode(" ",$row[5]);
		if (array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid == 1) {
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
	        if (preg_match_all("|!!(.*)!!|U",$row[3],$query_parameters))  $action = "form_proc" ;
			else $action = "add_item" ;
	        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./catalog.php?categ=caddie&sub=collecte&moyen=selection&action=$action&id=$row[0]&idcaddie=$idcaddie';\" ";
        	print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
					<td>
						<strong>$row[2]</strong><br />
						<small>$row[4]&nbsp;</small>
						</td>
				</tr>");
		}
	}
	print "</table>";
}

