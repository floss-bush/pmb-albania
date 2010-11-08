<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cart.inc.php,v 1.67 2010-04-20 13:45:20 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/caddie.class.php");
require_once($class_path."/sort.class.php");
require_once($class_path."/notice.class.php");

function aff_paniers($item=0, $object_type="NOTI", $lien_origine="./cart.php?", $action_click = "add_item", $titre="Cliquez sur le nom d'un panier pour y déposer la notice", $restriction_panier="", $lien_edition=0, $lien_suppr=0, $lien_creation=1, $nocheck=false) {
	global $msg;
	global $PMBuserid;
	global $charset;
	global $myCart;
	global $action;
	
	if ($lien_edition) $lien_edition_panier_cst = "<input type=button class=bouton value='$msg[caddie_editer]' onclick=\"document.location='$lien_origine&action=edit_cart&idcaddie=!!idcaddie!!';\" />";
	else $lien_edition_panier_cst = "";

	$liste = caddie::get_cart_list($restriction_panier);
	print "<script type='text/javascript' src='./javascript/tablist.js'></script>";
	if(($item)&&($nocheck)) {
		print "<form name='print_options' action='$lien_origine&action=$action_click&object_type=".$object_type."&item=$item' method='post'>";
		print "<input type='hidden' id='idcaddie' name='idcaddie' >";
	}	
	if(($item)&&(!$nocheck)) {
		print "<form name='print_options' action='$lien_origine&action=$action_click&object_type=".$object_type."&item=$item' method='post'>";
		if($action!="save_cart")print "<input type='checkbox' name='include_child' >&nbsp;".$msg["cart_include_child"];
	}
	print "<hr />";
	if(sizeof($liste)) {			
		print pmb_bidi("<div class='row'><a href='javascript:expandAll()'><img src='./images/expand_all.gif' id='expandall' border='0'></a>
		<a href='javascript:collapseAll()'><img src='./images/collapse_all.gif' id='collapseall' border='0'></a>$titre</div>");
		print confirmation_delete("$lien_origine&action=del_cart&object_type=".$object_type."&item=$item&idcaddie=");
		
		while(list($cle, $valeur) = each($liste)) {
			$rqt_autorisation=explode(" ",$valeur['autorisations']);
			if (array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid==1) {
				$aff_lien=str_replace('!!idcaddie!!', $valeur['idcaddie'], $lien_edition_panier_cst);
		        
		        $myCart->nb_item=$valeur['nb_item'];
		        $myCart->nb_item_pointe=$valeur['nb_item_pointe'];
		        $myCart->type=$valeur['type'];
		        $print_cart[$myCart->type]["titre"]="<b>".$msg["caddie_de_".$myCart->type]."</b><br />";
		        
		        $parity[$myCart->type]=1-$parity[$myCart->type];
				if ($parity[$myCart->type]) $pair_impair = "even"; 
				else $pair_impair = "odd";	        
		        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
				
				if($item) {
					$print_cart[$myCart->type]["cart_list"].= pmb_bidi("<tr class='$pair_impair' $tr_javascript ><td>".(!$nocheck?"<input type='checkbox' id='id_".$valeur['idcaddie']."' name='caddie[".$valeur['idcaddie']."]' value='".$valeur['idcaddie']."'>":"")."&nbsp;"); 
					$link = "$lien_origine&action=$action_click&object_type=".$object_type."&idcaddie=".$valeur['idcaddie']."&item=$item";	
            		if(!$nocheck)$print_cart[$myCart->type]["cart_list"].= pmb_bidi( "<a href='#' onclick='javascript:document.getElementById(\"id_".$valeur['idcaddie']."\").checked=true;document.forms[\"print_options\"].submit();' /><strong>".$valeur['name']."</strong>")	;
            		else {
               			$print_cart[$myCart->type]["cart_list"].= pmb_bidi( "<a href='#' onclick='javascript:document.getElementById(\"idcaddie\").value=".$valeur['idcaddie'].";document.forms[\"print_options\"].submit();' /><strong>".$valeur['name']."</strong>")	;
            		}			
					if ($valeur['comment']) $print_cart[$myCart->type]["cart_list"].=  pmb_bidi("<br /><small>(".$valeur['comment'].")</small>");
	            	$print_cart[$myCart->type]["cart_list"].=  pmb_bidi("</td>
	            		".aff_cart_nb_items_reduit($myCart)."
	            		<td>$aff_lien</td>
						</tr>");						
				} else {
					$link = "$lien_origine&action=$action_click&object_type=".$object_type."&idcaddie=".$valeur['idcaddie']."&item=$item";
	            	$print_cart[$myCart->type]["cart_list"].= pmb_bidi("<tr class='$pair_impair' $tr_javascript >
	                			<td><a href='$link' /><strong>".$valeur['name']."</strong>");	
	                if ($valeur['comment']) $print_cart[$myCart->type]["cart_list"].=  pmb_bidi("<br /><small>(".$valeur['comment'].")</small>");
	            	$print_cart[$myCart->type]["cart_list"].=  pmb_bidi("</a></td>
	            		<td>".aff_cart_nb_items_reduit($myCart)."</td>
	            		<td>$aff_lien</td>
						</tr>");				
				}		           
			}
		}
		// affichage des paniers par type	
		foreach($print_cart as $key => $cart_type) {
			print gen_plus($key,$cart_type["titre"],"<table border='0' cellspacing='0' width='100%'>".$cart_type["cart_list"]."</table>",1);
		}		
	} else {
		print $msg[398];
	}

	if (!$nocheck) {
		if($item) {
			$boutons_select="<input type='submit' value='".$msg["print_cart_add"]."' class='bouton'/>&nbsp;<input type='button' value='".$msg["print_cancel"]."' class='bouton' onClick='self.close();'/>&nbsp;";
		}	
		if ($lien_creation) {
			print "<div class='row'><hr />
				$boutons_select<input class='bouton' type='button' value=' $msg[new_cart] ' onClick=\"document.location='$lien_origine&action=new_cart&object_type=".$object_type."&item=$item'\" />
				</div>"; 
		} else {
			print "<div class='row'><hr />
				$boutons_select
				</div>"; 		
		}
	} else 	if ($lien_creation) {
		print "<div class='row'><hr />
			$boutons_select<input class='bouton' type='button' value=' $msg[new_cart] ' onClick=\"document.location='$lien_origine&action=new_cart&object_type=".$object_type."&item=$item'\" />
			</div>"; 
	}				
	//if(($item)&&(!$nocheck)) print"</form>";
	if(($item)) print"</form>";		
}

// affichage des autorisations sur les caddies
function aff_form_autorisations ($param_autorisations="1", $creation_cart="1") {
	global $dbh;
	global $msg;
	global $PMBuserid;
	
	$requete_users = "SELECT userid, username FROM users order by username ";
	$res_users = mysql_query($requete_users, $dbh);
	$all_users=array();
	while (list($all_userid,$all_username)=mysql_fetch_row($res_users)) {
		$all_users[]=array($all_userid,$all_username);
	}
	if ($creation_cart) $param_autorisations.=" ".$PMBuserid ;
	
	$autorisations_donnees=explode(" ",$param_autorisations);
	
	for ($i=0 ; $i<count($all_users) ; $i++) {
		if (array_search ($all_users[$i][0], $autorisations_donnees)!==FALSE) $autorisation[$i][0]=1;
		else $autorisation[$i][0]=0;
		$autorisation[$i][1]= $all_users[$i][0];
		$autorisation[$i][2]= $all_users[$i][1];
	}
	$autorisations_users="";
	$id_check_list='';
	while (list($row_number, $row_data) = each($autorisation)) {
		$id_check="auto_".$row_data[1];
		if($id_check_list)$id_check_list.='|';
		$id_check_list.=$id_check;
		if ($row_data[1]==1) $autorisations_users.="<span class='usercheckbox'><input type='checkbox' name='cart_autorisations[]' id='$id_check' value='".$row_data[1]."' checked class='checkbox' readonly /><label for='$id_check' class='normlabel'>&nbsp;".$row_data[2]."</label></span>&nbsp;";
		elseif ($row_data[0]) $autorisations_users.="<span class='usercheckbox'><input type='checkbox' name='cart_autorisations[]' id='$id_check' value='".$row_data[1]."' checked class='checkbox' /><label for='$id_check' class='normlabel'>&nbsp;".$row_data[2]."</label></span>&nbsp;";
		else $autorisations_users.="<span class='usercheckbox'><input type='checkbox' name='cart_autorisations[]' id='$id_check' value='".$row_data[1]."' class='checkbox' /><label for='$id_check' class='normlabel'>&nbsp;".$row_data[2]."</label></span>&nbsp;";
	}
	$autorisations_users.="<input type='hidden' id='auto_id_list' name='auto_id_list' value='$id_check_list' >";
	return $autorisations_users;
}
	
// affichage du contenu complet d'un caddie
function aff_cart_objects ($idcaddie=0, $url_base="./catalog.php?categ=caddie&sub=gestion&quoi=panier&idcaddie=0", $no_del=false,$rec_history=0 ) {
	global $msg;
	global $dbh;
	global $begin_result_liste, $end_result_liste;
	global $affich_tris_result_liste;
	global $pmb_nb_max_tri;
	global $nbr_lignes, $page, $nb_per_page_search ;
	global $url_base_suppr_cart ;
	
	$url_base_suppr_cart = $url_base ;
	
	$cb_display = "
			<div id=\"el!!id!!Parent\" class=\"notice-parent\">
	    		<span class=\"notice-heada\">!!heada!!</span>
	    		<br />
			</div>
			";
	
	// nombre de références par pages
	if ($nb_per_page_search != "") $nb_per_page = $nb_per_page_search ;
	else $nb_per_page = 10;
	
	// on récupére le nombre de lignes
	if(!$nbr_lignes) {
		$requete = "SELECT count(1) FROM caddie_content where caddie_id='".$idcaddie."' ";
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
	}
	
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;
	
	//Calcul des variables pour la suppression de notices
	$modulo = $nbr_lignes%$nb_per_page;
	if($modulo == 1)
		$page_suppr = (!$page ? 1 : $page-1);
	else $page_suppr = $page;	
	$nb_after_suppr = ($nbr_lignes ? $nbr_lignes-1 : 0);
	
	if($nbr_lignes) {
		// on lance la vraie requête
		$myCart = new caddie($idcaddie);
		$caddie_type = $myCart->type ;
		switch ($caddie_type) {
			case "NOTI":
				$from = " caddie_content left join notices on notice_id = object_id ";
				$order_by = " index_sew " ;
				break ;
			case "EXPL":
				$from = " caddie_content left join exemplaires on expl_id=object_id left join notices on notice_id = expl_notice ";
				$order_by = " index_sew " ;
				break ;
			case "BULL":
				$from = " caddie_content left join bulletins on bulletin_id = object_id ";
				$order_by = " date_date " ;
				break ;
		}
				
		$requete = "SELECT * FROM $from where caddie_id='".$idcaddie."' order by $order_by"; 
		$requete.= " LIMIT $debut,$nb_per_page ";
		//gestion du tri
		if ($caddie_type=="NOTI") {
			if ($nbr_lignes<=$pmb_nb_max_tri) {
				if ($_SESSION["tri"]) {
					$requete = "SELECT notice_id FROM $from where caddie_id='".$idcaddie."' order by $order_by";
					$sort=new sort('notices','base');
					$sort->champs_select="caddie_content.object_id, caddie_content.content, caddie_content.blob_type, caddie_content.flag";
					$sort->table_select="caddie_content";
					$sort->table_primary_key_select="object_id";
					$requete = $sort->appliquer_tri($_SESSION["tri"], $requete, "notice_id", $debut, $nb_per_page);
				}
			}
		}
		// fin gestion tri
			
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		// l'affichage du résultat est fait après le else
	} else {
		print $msg[399];
		return;
	}
	
	$liste=array();
	$result = @mysql_query($requete, $dbh) ; // or die (mysql_error());
	
	if(mysql_num_rows($result)) {
		while ($temp = mysql_fetch_object($result)) 
			$liste[] = array('object_id' => $temp->object_id, 'content' => $temp->content, 'blob_type' => $temp->blob_type, 'flag' => $temp->flag ) ;  
	}
	
	if(!sizeof($liste) || !is_array($liste)) {
		print $msg[399];
		return;
	} else {
		// en fonction du type de caddie on affiche ce qu'il faut
		if ($caddie_type=="NOTI") {
			// boucle de parcours des notices trouvées
			// inclusion du javascript de gestion des listes dépliables
			// début de liste
			print $begin_result_liste;
			//Affichage du lien impression et panier
			if (($rec_history)&&($_SESSION["CURRENT"]!==false)) {
				$current=$_SESSION["CURRENT"];
				print "&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$current&action=print_prepare','print',600,700,-2,-2,'scrollbars=yes,menubar=0'); return false;\"><img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&action_print=print_prepare','print',500,600,-2,-2,'scrollbars=yes,menubar=0'); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
				if ($nbr_lignes<=$pmb_nb_max_tri) {
					print "&nbsp;".$affich_tris_result_liste;
				}
			}
							
			while(list($cle, $object) = each($liste)) {
				if ($object[content]=="") {
					// affichage de la liste des notices sous la forme 'expandable'
					$requete = "SELECT * FROM notices WHERE notice_id=$object[object_id] LIMIT 1";
					
					$fetch = mysql_query($requete);
					if(mysql_num_rows($fetch)) {
						$notice = mysql_fetch_object($fetch);
						if ($notice->niveau_biblio == 'b') {
							// notice de bulletin
							$rqtbull="select bulletin_id from bulletins where num_notice=".$notice->notice_id;
							$fetchbull = mysql_query($rqtbull);
							$bull = mysql_fetch_object($fetchbull);
							$link = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$bull->bulletin_id;
							// pas affichés pour l'instant:
							$link_expl = ''; 
							$link_explnum = '';
							if ($object[flag]) $marque_flag ="<img src='images/tick.gif'/>" ;
							else $marque_flag ="" ;
							if (!$no_del) $lien_suppr_cart = "<a href='$url_base&action=del_item&object_type=NOTI&item=$notice->notice_id&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a> $marque_flag";
							else $lien_suppr_cart = $marque_flag ;
							$display = new mono_display($notice, 6, $link, 1, $link_expl, $lien_suppr_cart, $link_explnum, 0, 0, 1, 1);
							print pmb_bidi($display->result);
						} elseif($notice->niveau_biblio != 's' && $notice->niveau_biblio != 'a') {
							// notice de monographie
							$link = './catalog.php?categ=isbd&id=!!id!!';
							$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
							$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!';
							if ($object[flag]) $marque_flag ="<img src='images/tick.gif'/>" ;
							else $marque_flag ="" ;
							if (!$no_del) $lien_suppr_cart = "<a href='$url_base&action=del_item&object_type=NOTI&item=$notice->notice_id&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a> $marque_flag";
							else $lien_suppr_cart = $marque_flag ;
							$display = new mono_display($notice, 6, $link, 1, $link_expl, $lien_suppr_cart, $link_explnum, 0, 0, 1, 1 );
							print pmb_bidi($display->result);
						} else {
							// on a affaire à un périodique
							// préparation des liens pour lui
							$link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
							$link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
							$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
							if ($object[flag]) $marque_flag ="<img src='images/tick.gif'/>" ;
							else $marque_flag ="" ;
							if (!$no_del) $lien_suppr_cart = "<a href='$url_base&action=del_item&action=del_item&object_type=NOTI&item=$notice->notice_id&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a> $marque_flag";
							else $lien_suppr_cart = $marque_flag ;
							$link_explnum = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";
							$serial = new serial_display($notice, 6, $link_serial, $link_analysis, $link_bulletin, $lien_suppr_cart, $link_explnum, 0, 0, 1, 1);
							print pmb_bidi($serial->result);
						}
					}
				} else {
					if ($object[flag]) $marque_flag ="<img src='images/tick.gif'/>" ;
					else $marque_flag ="" ;
					if (!$no_del) $lien_suppr_cart = "<a href='$url_base&action=del_item&object_type=EXPL_CB&item=".$object[content]."&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a> $marque_flag";
					else $lien_suppr_cart = $marque_flag ;
					$cb_display = "
						<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				    		<span class=\"notice-heada\"><strong>$lien_suppr_cart ".$msg["4014"]." : $object[content]&nbsp;: ${msg[395]}</strong></span>
				    		<br />
						</div>
						";
					print $cb_display;
				}
			} // fin de liste
		print $end_result_liste;
		} // fin si NOTI
		// si EXPL
		if ($caddie_type=="EXPL") {
			// boucle de parcours des exemplaires trouvés
			// inclusion du javascript de gestion des listes dépliables
			// début de liste
			print $begin_result_liste;
			while(list($cle, $expl) = each($liste)) {
				if (!$expl[content])
					if($stuff = get_expl_info($expl[object_id])) {
						if ($expl[flag]) $marque_flag ="<img src='images/tick.gif'/>" ;
						else $marque_flag ="" ;
						if (!$no_del) $stuff->lien_suppr_cart = "<a href='$url_base&action=del_item&object_type=EXPL&item=$stuff->expl_id&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a> $marque_flag";
						else $stuff->lien_suppr_cart = $marque_flag ;
						$stuff = check_pret($stuff);
						print pmb_bidi(print_info($stuff,0,1));
					} else {
						print "<strong>ID : $expl[object_id]&nbsp;: ${msg[395]}</strong>";
					}
				else {
					if ($expl[flag]) $marque_flag ="<img src='images/tick.gif'/>" ;
					else $marque_flag ="" ;
					if (!$no_del) $lien_suppr_cart = "<a href='$url_base&action=del_item&object_type=EXPL_CB&item=".$expl[content]."&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a> $marque_flag";
					else $lien_suppr_cart = $marque_flag ;
					$cb_display = "
						<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				    		<span class=\"notice-heada\"><strong>$lien_suppr_cart Code-barre : $expl[content]&nbsp;: ${msg[395]}</strong></span>
				    		<br />
						</div>
						";
					print $cb_display;
				}
			} // fin de liste
		print $end_result_liste;
		} // fin si EXPL
		if ($caddie_type=="BULL") {
			// boucle de parcours des bulletins trouvés
			// inclusion du javascript de gestion des listes dépliables
			// début de liste
			print $begin_result_liste;
			while(list($cle, $expl) = each($liste)) {
				if (!$no_del) $show_del=1; else $show_del=0;
				if($bull_aff = show_bulletinage_info($expl[object_id], 0 , $show_del)) {
					print pmb_bidi($bull_aff);
				} else {
					if ($expl[flag]) $marque_flag ="<img src='images/tick.gif'/>" ;
					else $marque_flag ="" ;
					if (!$no_del) $lien_suppr_cart = "<a href='$url_base&action=del_item&object_type=EXPL_CB&item=".$expl[content]."&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a> $marque_flag";
					else $lien_suppr_cart = $marque_flag ;
					$cb_display = "
						<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				    		<span class=\"notice-heada\"><strong>$lien_suppr_cart Code-barre : $expl[content]&nbsp;: ${msg[395]}</strong></span>
				    		<br />
						</div>
						";
					print $cb_display;
					//print "<strong>$form_cb_expl&nbsp;: ${msg[395]}</strong>";
				}
			} // fin de liste
		print $end_result_liste;
		} // fin si BULL
	}
	print "<br />".$nav_bar ;
	return;
}

// affichage d'un unique objet de caddie
function aff_cart_unique_object ($item, $caddie_type, $url_base="./catalog.php?categ=caddie&sub=gestion&quoi=panier&idcaddie=0" ) {
	global $msg;
	global $dbh;
	global $begin_result_liste;
	global $end_result_list;
	global $page, $nbr_lignes, $nb_per_page;
	
	// nombre de références par pages
	if ($nb_per_page_search != "") $nb_per_page = $nb_per_page_search ;
	else $nb_per_page = 10;
	
	$cb_display = "
			<div id=\"el!!id!!Parent\" class=\"notice-parent\">
	    		<span class=\"notice-heada\">!!heada!!</span>
	    		<br />
			</div>
			";
	
	$liste[] = array('object_id' => $item, 'content' => "", 'blob_type' => "") ;  
	
	$aff_retour = "" ;
	
	//Calcul des variables pour la suppression d'items
	$modulo = $nbr_lignes%$nb_per_page;
	if($modulo == 1){
		$page_suppr = (!$page ? 1 : $page-1);
	} else {
		$page_suppr = $page;
	}	
	$nb_after_suppr = ($nbr_lignes ? $nbr_lignes-1 : 0);	
	
	if(!sizeof($liste) || !is_array($liste)) {
		return $msg[399];
	} else {
		// en fonction du type de caddie on affiche ce qu'il faut
		if ($caddie_type=="NOTI") {
			// boucle de parcours des notices trouvées
			while(list($cle, $object) = each($liste)) {
				if ($object[content]=="") {
					// affichage de la liste des notices sous la forme 'expandable'
					$requete = "SELECT * FROM notices WHERE notice_id=$object[object_id] LIMIT 1";
					$fetch = mysql_query($requete);
					if(mysql_num_rows($fetch)) {
						$notice = mysql_fetch_object($fetch);
						if ($notice->niveau_biblio == 'b') {
							// notice de bulletin
							$rqtbull="select bulletin_id from bulletins where num_notice=".$notice->notice_id;
							$fetchbull = mysql_query($rqtbull);
							$bull = mysql_fetch_object($fetchbull);
							$link = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$bull->bulletin_id;
							// pas affichés pour l'instant:
							$link_expl = ''; 
							$link_explnum = '';
							$display = new mono_display($notice, 6, $link, 1, $link_expl, $lien_suppr_cart, $link_explnum );
							$aff_retour .= $display->result;
						} elseif($notice->niveau_biblio != 's' && $notice->niveau_biblio != 'a') {
							// notice de monographie
							$link = './catalog.php?categ=isbd&id=!!id!!';
							$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
							$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!';   
							$lien_suppr_cart = "<a href='$url_base&action=del_item&object_type=NOTI&item=$notice->notice_id&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a>";
							$display = new mono_display($notice, 6, $link, 1, $link_expl, $lien_suppr_cart, $link_explnum );
							$aff_retour .= $display->result;
						} else {
							// on a affaire à un périodique
							// préparation des liens pour lui
							$link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
							$link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
							$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
							$lien_suppr_cart = "<a href='$url_base&action=del_item&object_type=NOTI&item=$notice->notice_id&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a>";
							$link_explnum = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";
							$serial = new serial_display($notice, 6, $link_serial, $link_analysis, $link_bulletin, $lien_suppr_cart, $link_explnum, 0);
							$aff_retour .= $serial->result;
						}
					}
				} else {
					$cb_display = "
						<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				    		<span class=\"notice-heada\"><strong>Code-barre : $object[content]&nbsp;: ${msg[395]}</strong></span>
				    		<br />
						</div>
						";
					$aff_retour .= $cb_display;
				}
			} // fin de liste
			print $end_result_list;
		} // fin si NOTI
		// si EXPL
		if ($caddie_type=="EXPL") {
			// boucle de parcours des exemplaires trouvés
			// inclusion du javascript de gestion des listes dépliables
			// début de liste
			while(list($cle, $expl) = each($liste)) {
				if (!$expl[content])
					if($stuff = get_expl_info($expl[object_id])) {
						$stuff->lien_suppr_cart = "<a href='$url_base&action=del_item&object_type=EXPL&item=$expl&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a>";
						$stuff = check_pret($stuff);
						$aff_retour .= print_info($stuff,0,1);
					} else {
						$aff_retour .= "<strong>ID : $expl[object_id]&nbsp;: ${msg[395]}</strong>";
					}
				else {
					$cb_display = "
						<div id=\"el!!id!!Parent\" class=\"notice-parent\">
				    		<span class=\"notice-heada\"><strong>Code-barre : $expl[content]&nbsp;: ${msg[395]}</strong></span>
				    		<br />
						</div>
						";
					$aff_retour .= $cb_display;
				}
			} // fin de liste
			print $end_result_list;
		} // fin si EXPL
		if ($caddie_type=="BULL") {
			// boucle de parcours des bulletins trouvés
			// inclusion du javascript de gestion des listes dépliables
			// début de liste
			while(list($cle, $expl) = each($liste)) {
				global $url_base_suppr_cart; 
				$url_base_suppr_cart = $url_base ;
				if ($bull_aff = show_bulletinage_info($expl[object_id],0,1)) {
					$aff_retour .= $bull_aff;
				} else {
					$aff_retour .= "<strong>$form_cb_expl&nbsp;: ${msg[395]}</strong><br />";
				}
			} // fin de liste
			print $end_result_list;
		} // fin si BULL
	}
	return $aff_retour ;
}

// ******************************************
function aff_cart_titre ($myCart) {
	global $msg;
	if ($myCart->comment) $aff_tit_panier = $myCart->name." - ".$myCart->comment;
	else $aff_tit_panier = $myCart->name;
	$lien="./catalog.php?categ=search&mode=3&object_type=".$myCart->type."&idcaddie=".$myCart->idcaddie."&item=";
	return "<div class='titre-panier'><h3><a href='".$lien."'>$aff_tit_panier</a> <i><small>(".$msg["caddie_de_".$myCart->type].")</small></i></h2></div>";
}

function aff_cart_nb_items ($myCart) {
	global $msg;
	return "<div class='row'>
			<div class='colonne3'>
				$msg[caddie_contient]
				</div>
			<div class='colonne3' align='center'>
				$msg[caddie_contient_total]
				</div>
			<div class='colonne_suite' align='center'>
				$msg[caddie_contient_nb_pointe]
				</div>
			</div>
		<div class='row'>
			<div class='colonne3' align='right'>
				$msg[caddie_contient_total]
				</div>
			<div class='colonne3' align='center'>
	<label class='etiquette' id='nb_item'>$myCart->nb_item</label>				
				</div>
			<div class='colonne_suite' align='center'>
	<label class='etiquette' id='nb_item_pointe'>$myCart->nb_item_pointe</label>
				</div>
			</div>
		<div class='row'>
			<div class='colonne3' align='right'>
				$msg[caddie_contient_dont_fonds]
				</div>
			<div class='colonne3' align='center'>
	<label class='etiquette' id='nb_item_base'>$myCart->nb_item_base</label>
				</div>
			<div class='colonne_suite' align='center'>
	<label id='nb_item_base_pointe'>$myCart->nb_item_base_pointe</label>
				</div>
			</div>
		<div class='row'>
			<div class='colonne3' align='right'>
				$msg[caddie_contient_dont_inconnus]
				</div>
			<div class='colonne3' align='center'>
	<label class='etiquette' id='nb_item_blob'>$myCart->nb_item_blob</label>
				</div>
			<div class='colonne_suite' align='center'>
	<label id='nb_item_blob_pointe'>$myCart->nb_item_blob_pointe</label>				
				</div>
		</div>
		<div class='row'></div>";
}

function aff_cart_nb_items_reduit ($myCart) {
	global $msg;
	//return "<b>$myCart->nb_item</b> ".$msg["caddie_nb_".$myCart->type]."</td><td><b>".$myCart->nb_item_pointe."</b>".$msg[caddie_contient_pointes];
	return "<td><b>".$myCart->nb_item_pointe."</b>". $msg[caddie_contient_pointes]." / <b>$myCart->nb_item</b> </td>";
	}
	
function aff_choix_quoi($action="", $action_cancel="", $titre_form="", $bouton_valider="",$onclick="", $aff_choix_dep = false,$caddie_t="") {
	global $quelle;
	global $cart_choix_quoi, $cart_choix_quoi_not_ou_dep,$notice_linked_suppr_form;

	if($caddie_t=="NOTI" && $quelle=="supprbase") 
		$cart_choix_quoi = str_replace('<!--suppr_link-->', $notice_linked_suppr_form, $cart_choix_quoi);
	$cart_choix_quoi = str_replace('!!action!!', $action, $cart_choix_quoi);
	$cart_choix_quoi = str_replace('!!action_cancel!!', $action_cancel, $cart_choix_quoi);
	$cart_choix_quoi = str_replace('!!titre_form!!', $titre_form, $cart_choix_quoi);
	$cart_choix_quoi = str_replace('!!bouton_valider!!', $bouton_valider, $cart_choix_quoi);
	if ($aff_choix_dep) $cart_choix_quoi = str_replace('!!bull_not_ou_dep!!',$cart_choix_quoi_not_ou_dep,$cart_choix_quoi);
	else $cart_choix_quoi = str_replace('!!bull_not_ou_dep!!',"<div class='row'>&nbsp;</div>",$cart_choix_quoi);
	if ($onclick!="") $cart_choix_quoi = str_replace('!!onclick_valider!!','onClick="'.$onclick.'"',$cart_choix_quoi); 
		else $cart_choix_quoi = str_replace('!!onclick_valider!!','',$cart_choix_quoi);
	return $cart_choix_quoi;
}

function verif_droit_proc_caddie($id) {
	global $msg;
	global $PMBuserid;
	global $dbh;
	
	if ($id) {
		$requete = "SELECT autorisations FROM caddie_procs WHERE idproc='$id' ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			$rqt_autorisation=explode(" ",$temp->autorisations);
			if (array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid == 1) return 1 ;
			else return 0 ;
		} else return 0;
	} else return 0 ;
}

function verif_droit_caddie($id) {
	global $msg;
	global $PMBuserid;
	global $dbh ;
	
	if ($id) {
		$requete = "SELECT autorisations FROM caddie WHERE idcaddie='$id' ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			$rqt_autorisation=explode(" ",$temp->autorisations);
			if (array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid == 1) return $id ;
			else return 0 ;
		} else return 0;
	} else return 0 ;
}
