<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: selection.inc.php,v 1.15 2010-10-21 08:59:10 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idcaddie) {
	$myCart = new caddie($idcaddie);
	print pmb_bidi(aff_cart_titre ($myCart));
	$droit = verif_droit_proc_caddie($id) ;
	if ((($action=="form_proc")||($action=="add_item"))&&((!$elt_flag)&&(!$elt_no_flag))) {
		error_message_history($msg["caddie_no_elements"], $msg["caddie_no_elements_for_cart"], 1);
		exit();
	}
	switch ($action) {
		case 'form_proc' :
			$hp = new parameters ($id) ;
			$hp->gen_form("./catalog.php?categ=caddie&sub=action&quelle=selection&action=add_item&idcaddie=$idcaddie&id=$id&elt_flag=$elt_flag&elt_no_flag=$elt_no_flag") ;
			break;
		case 'add_item':
			//C'est ici qu'on fait une action
			if ($droit) {
				$hp = new parameters ($id) ;
				$hp->get_final_query();
				echo "<hr />".$hp->final_query."<hr />";
				if (!explain_requete($hp->final_query)) die("<br /><br />".$hp->final_query."<br /><br />".$msg["proc_param_explain_failed"]."<br /><br />".$erreur_explain_rqt);
				//Sélection des éléments du panier
				$nb_elements_flag=0;
				$nb_elements_no_flag=0;
				
				if ($elt_flag) {
					$liste_flag=$myCart->get_cart("FLAG");
					if (count($liste_flag)) {
						if (pmb_strtolower(pmb_substr($hp->final_query,0,6))=='insert') {
							// procédure insert
							for ($icount=0; $icount<count($liste_flag);$icount++) {
								$final_query=str_replace("CADDIE(NOTI)",$liste_flag[$icount],$hp->final_query);
								$final_query=str_replace("CADDIE(EXPL)",$liste_flag[$icount],$final_query);
								$final_query=str_replace("CADDIE(BULL)",$liste_flag[$icount],$final_query);
								$nb_elts_traites = mysql_affected_rows($dbh) ;
								if ($nb_elts_traites>0) $nb_elements_flag+=$nb_elts_traites;
								} // fin for
							} else {
								// autre procédure
								$final_query=preg_replace("/CADDIE\(.*[^\)]\)/i",implode(",",$liste_flag),$hp->final_query);
								$result_selection_flag= mysql_query($final_query, $dbh);
								if ($result_selection_flag) {
									$nb_elements_flag=mysql_affected_rows($dbh);
									} else $error_message_flag=mysql_error();
								} // fin if autre procédure
						}
				}
				if ($elt_no_flag) {
					$liste_no_flag=$myCart->get_cart("NOFLAG");
					if (count($liste_no_flag)) {
						if (pmb_strtolower(pmb_substr($hp->final_query,0,6))=='insert') {
							// procédure insert
							for ($icount=0; $icount<count($liste_no_flag);$icount++) {
								$final_query=str_replace("CADDIE(NOTI)",$liste_no_flag[$icount],$hp->final_query);
								$final_query=str_replace("CADDIE(EXPL)",$liste_no_flag[$icount],$final_query);
								$final_query=str_replace("CADDIE(BULL)",$liste_no_flag[$icount],$final_query);
								$result_selection_no_flag= @mysql_query($final_query, $dbh);
								$nb_elts_traites = mysql_affected_rows($dbh) ;
								if ($nb_elts_traites>0) $nb_elements_no_flag+=$nb_elts_traites;
								} // fin for
							} else {
								// autre procédure
								$final_query=preg_replace("/CADDIE\(.*[^\)]\)/i",implode(",",$liste_no_flag),$hp->final_query);
								$result_selection_no_flag= mysql_query($final_query, $dbh);
								if ($result_selection_no_flag) {
									$nb_elements_no_flag=mysql_affected_rows($dbh);
									} else $error_message_no_flag=mysql_error();
								} // fin if autre procédure
						}
				}
				$error_message="";
				print sprintf($msg["caddie_action_flag_processed"],$nb_elements_flag)."<br />";
				print sprintf($msg["caddie_action_no_flag_processed"],$nb_elements_no_flag)."<br />";
				print "<b>".sprintf($msg["caddie_action_total_processed"],($nb_elements_no_flag+$nb_elements_flag))."</b><br /><br />";
				if ($error_message_flag) {
					$error_message.=sprintf($msg["caddie_action_error"],$error_message_flag)."<br />";
				}
				if ($error_message_no_flag) {
					$error_message.=sprintf($msg["caddie_action_error"],$error_message_no_flag);
				}
				if ($error_message) {
					error_message_history($msg["caddie_action_invalid_query"],$error_message,1);
					exit();
				}
            }
			print aff_cart_nb_items ($myCart) ;
			break;
		default:
			print aff_cart_nb_items ($myCart) ;
			print $cart_choix_quoi_action;
			show_procs($idcaddie);
			break;
		}
	} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=action&quelle=selection", "", $msg["caddie_select_for_action"], "", 0, 0, 0);

function is_for_cart($requete) {
	global $myCart;

	if (preg_match("/CADDIE\(([^\)]*)\)/",$requete,$match)) {
		$m=explode(",",$match[1]);
		$as=array_search($myCart->type,$m);
		if (($as!==NULL)&&($as!==false)) return true; else return false;
	} else return false;
}

function show_procs($idcaddie) {
	global $msg,$charset;
	global $PMBuserid;
	global $dbh;
	
	print "<hr />$msg[caddie_select_proc]<br /><table>";
	// affichage du tableau des procédures
	if ($PMBuserid!=1) $where=" and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') ";
	$requete = "SELECT idproc, type, name, requete, comment, autorisations, parameters FROM caddie_procs WHERE type='ACTION' $where ORDER BY name ";
	$res = mysql_query($requete, $dbh);
	$nbr = mysql_num_rows($res);

	$parity=1;
	$n_proc=0;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_row($res);
		$rqt_autorisation=explode(" ",$row[5]);
		if ((array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid == 1)&&(is_for_cart($row[3]))) {
			$n_proc++;
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
	        if (preg_match_all("|!!(.*)!!|U",$row[3],$query_parameters))  $action = "form_proc" ;
			else $action = "add_item" ;
	        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"if (confirm('".addslashes(str_replace("\"","",sprintf($msg["caddie_action_proc_confirm"],$row[2])))."')) { url='./catalog.php?categ=caddie&sub=action&quelle=selection&action=$action&id=$row[0]&idcaddie=$idcaddie'; if (document.maj_proc.elt_flag.checked) url+='&elt_flag='+document.maj_proc.elt_flag.value; if (document.maj_proc.elt_no_flag.checked) url+='&elt_no_flag='+document.maj_proc.elt_no_flag.value; document.location=url; }\" ";
        	print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
					<td>
						<strong>".htmlentities($row[2],ENT_QUOTES,$charset)."</strong><br />
						<small>".htmlentities($row[4],ENT_QUOTES,$charset)."&nbsp;</small>
						</td>
				</tr>");
		}
	}
	print "</table>";
	if ($n_proc==0) print $msg["caddie_no_action_proc"];
}

