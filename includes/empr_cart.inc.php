<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_cart.inc.php,v 1.18.4.1 2011-06-16 08:01:42 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// ********************************************************************************
// affichage des paniers existants
function aff_paniers_empr($item=0, $lien_origine="./circ.php?", $action_click = "add_item", $titre="", $restriction_panier="", $lien_edition=0, $lien_suppr=0, $lien_creation=1,$creation_param="") {
	global $msg;
	global $PMBuserid;
	global $charset;
	global $myCart;
	global $sub;
	global $action;
	
	if ($lien_edition) $lien_edition_panier_cst = "<input type=button class=bouton value='$msg[caddie_editer]' onclick=\"document.location='$lien_origine&action=edit_cart&idemprcaddie=!!idemprcaddie!!';\" />";
		else $lien_edition_panier_cst = "";
	 if($sub!='gestion' && $sub!='action') {
		print "<form name='print_options' action='$lien_origine&action=$action_click&item=$item' method='post'>";
	}
	$liste = empr_caddie::get_cart_list($restriction_panier);
	print "<hr />";
	if(sizeof($liste)) {
		print "<div class='row'>$titre</div>";
		print confirmation_delete("$lien_origine&action=del_cart&item=$item&idemprcaddie=");
		print "<script type='text/javascript'>
			function add_to_cart(form) {
        		var inputs = form.getElementsByTagName('input');
        		var count=0;
        		for(i=0;i<inputs.length;i++){
					if(inputs[i].type=='checkbox' && inputs[i].checked==true)
        				count ++;
				}
				if(count == 0){
					alert(\"$msg[no_emprcart_selected]\");
					return false;
				}
				return true;
   			}
   		</script>";
		print "<table border='0' cellspacing='0' width='100%'>";
		$parity=0;
		while (list($cle, $valeur) = each($liste)) {
			$rqt_autorisation=explode(" ",$valeur['autorisations']);
			if (array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid==1) {
				
				$link = "$lien_origine&action=$action_click&idemprcaddie=".$valeur['idemprcaddie']."&item=$item";
				
				if (($parity=1-$parity)) $pair_impair = "even"; else $pair_impair = "odd";
	
				$lien_edition_panier = str_replace('!!idemprcaddie!!', $valeur['idemprcaddie'], $lien_edition_panier_cst);
		        $aff_lien = $lien_edition_panier;
		        
		        $myCart->nb_item=$valeur['nb_item'];
		        $myCart->nb_item_pointe=$valeur['nb_item_pointe'];
		        $myCart->type=$valeur['type'];
		        $print_cart[$myCart->type]["titre"]="<b>".$msg["caddie_de_".$myCart->type]."</b><br />";
		        
		        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
				if($item) {
		            $print_cart[$myCart->type]["cart_list"].= pmb_bidi("<tr class='$pair_impair' $tr_javascript ><td>");
		            if($action != "transfert" && $action != "del_cart" && $action!="save_cart") {
		            	$print_cart[$myCart->type]["cart_list"].= pmb_bidi("<input type='checkbox' id='id_".$valeur['idemprcaddie']."' name='caddie[".$valeur['idemprcaddie']."]' value='".$valeur['idemprcaddie']."'>&nbsp;");
		            	$print_cart[$myCart->type]["cart_list"].= pmb_bidi("<a href='#' onClick='javascript:document.getElementById(\"id_".$valeur['idemprcaddie']."\").checked=true; document.forms[\"print_options\"].submit();' /><strong>".$valeur['name']."</strong>");
		            	// form_filters_cart
		            	//$print_cart[$myCart->type]["cart_list"].= pmb_bidi("<a href='#' onClick=\"if (document.forms.length) {  if (document.forms['print_options'].elements.length) { document.forms['print_options'].idemprcaddie.value='".$valeur['idemprcaddie']."'; document.forms['form_filters_cart'].submit(); } }\"/><strong>".$valeur['name']."</strong>");
		            } else {		            
						$print_cart[$myCart->type]["cart_list"].= pmb_bidi("<a href='$link' /><strong>".$valeur['name']."</strong>");
		            }	
	                if ($valeur['comment']) $print_cart[$myCart->type]["cart_list"].=  pmb_bidi("<br /><small>(".$valeur['comment'].")</small>");
	            	$print_cart[$myCart->type]["cart_list"].=  pmb_bidi("</td>
	            		<td>".aff_cart_nb_items_reduit($myCart)."</td>
	            		<td>$aff_lien</td>
						</tr>");						
				} else {		        
		            $print_cart[$myCart->type]["cart_list"].= "<tr class='$pair_impair' $tr_javascript ><td>";
		            if($sub!='gestion' && $sub!='action'  && $action!="save_cart") {
						$print_cart[$myCart->type]["cart_list"].= "<input type='checkbox' id='id_".$valeur['idemprcaddie']."' name='caddie[".$valeur['idemprcaddie']."]' value='".$valeur['idemprcaddie']."'>&nbsp;";		            	
						$print_cart[$myCart->type]["cart_list"].= "<a href='#' onClick='javascript:document.getElementById(\"id_".$valeur['idemprcaddie']."\").checked=true; document.forms[\"print_options\"].submit();' /><strong>".$valeur['name']."</strong>";
		            }		                
		            else $print_cart[$myCart->type]["cart_list"].= "<a href='$link' /><strong>".$valeur['name']."</strong>";
		            if ($valeur['comment']) $print_cart[$myCart->type]["cart_list"].= "<br /><small>(".$valeur['comment'].")</small>";
		            $print_cart[$myCart->type]["cart_list"].="</a></td>
		            		<td>".aff_cart_nb_items_reduit($myCart)."</td>
		            		<td>$aff_lien</td>
							</tr>";
				}		
			}
		}
		
		// affichage des paniers par type	
		foreach($print_cart as $key => $cart_type) {
			print $cart_type["cart_list"];
		}	
		
		print "</table>";
	} else {
		print $msg[398];
	}
	
	 if($sub!='gestion' && $sub!='action'&& $action != "del_cart") {
		$boutons_select="<input type='submit' value='".$msg["print_cart_add"]."' class='bouton' onclick=\"return add_to_cart(this.form);\"/>&nbsp;<input type='button' value='".$msg["print_cancel"]."' class='bouton' onClick='self.close();'/>&nbsp;";
	}	
	if ($lien_creation) {
		print "<div class='row'><hr />
			$boutons_select<input class='bouton' type='button' value=' $msg[new_cart] ' onClick=\"document.location='$lien_origine&action=new_cart$creation_param&item=$item'\" />
			</div>"; 
	} else {
		print "<div class='row'><hr />
			$boutons_select
			</div>"; 		
	}			
	 if($sub!='gestion')  print"</form>";
	

}

// ********************************************************************************
function aff_empr_cart_titre ($myCart) {
	global $msg;
	if ($myCart->comment) $aff_tit_panier = $myCart->name." - ".$myCart->comment;
		else $aff_tit_panier = $myCart->name;
	return "<div class='titre-panier'><h3><a href='./circ.php?categ=caddie&sub=gestion&quoi=panier&action=&idemprcaddie=".$myCart->idemprcaddie."'>$aff_tit_panier</a></h3></div>";
	}

// ********************************************************************************
function aff_empr_cart_nb_items ($myCart) {
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
				<b>$myCart->nb_item</b>
				</div>
			<div class='colonne_suite' align='center'>
				<b>$myCart->nb_item_pointe</b>
				</div>
			</div>
		<br />";
	}

// ****************************** aff_empr_cart_objects
function aff_empr_cart_objects ($idemprcaddie=0, $url_base="./circ.php?categ=caddie&sub=gestion&quoi=panier&idemprcaddie=0", $no_del=false,$rec_history=0 ) {
	global $msg, $begin_result_liste;
	global $dbh;
	global $nbr_lignes, $page, $nb_per_page_search ;
	global $url_base_suppr_empr_cart ;
	
	$url_base_suppr_empr_cart = $url_base ;
	
	// nombre de références par pages
	if ($nb_per_page_search != "") 
		$nb_per_page = $nb_per_page_search ;
	else $nb_per_page = 10;
	
	// on récupére le nombre de lignes
	if(!$nbr_lignes) {
		$requete = "SELECT count(1) FROM empr_caddie_content where empr_caddie_id='".$idemprcaddie."' ";
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
	}
	
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;
	
	//Calcul des variables pour la suppression d'items
	$modulo = $nbr_lignes%$nb_per_page;
	if($modulo == 1){
		$page_suppr = (!$page ? 1 : $page-1);
	} else {
		$page_suppr = $page;
	}	
	$nb_after_suppr = ($nbr_lignes ? $nbr_lignes-1 : 0);	
	
		
	if($nbr_lignes) {
		// on lance la vraie requête
		$myCart = new empr_caddie($idemprcaddie);
		$from = " empr_caddie_content left join empr on id_empr = object_id ";
		$order_by = " empr_nom, empr_prenom " ;
		$requete = "SELECT object_id, flag FROM $from where empr_caddie_id='".$idemprcaddie."' order by $order_by"; 
		$requete.= " LIMIT $debut,$nb_per_page ";
			
		
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		// l'affichage du résultat est fait après le else
	} else {
		print $msg[399];
		return;
	}
	
	$liste=array();
	$result = @mysql_query($requete, $dbh);
	
	if(mysql_num_rows($result)) {
		while ($temp = mysql_fetch_object($result)) 
			$liste[] = array('object_id' => $temp->object_id, 'flag' => $temp->flag ) ;  
	}
	
	if(!sizeof($liste) || !is_array($liste)) {
		print $msg[399];
		return;
	} else {
		print $begin_result_liste;
		while(list($cle, $object) = each($liste)) {
			// affichage de la liste des emprunteurs 
			$requete = "SELECT * FROM empr WHERE id_empr=$object[object_id] LIMIT 1";
			$fetch = mysql_query($requete);
			if(mysql_num_rows($fetch)) {
				$empr = mysql_fetch_object($fetch);
				// emprunteur
				$link = './circ.php?categ=pret&form_cb='.rawurlencode($empr->empr_cb);
				if ($object[flag]) $marque_flag ="<img src='images/tick.gif'/>" ;
					else $marque_flag ="" ;
				if (!$no_del) $lien_suppr_cart = "<a href='$url_base&action=del_item&item=$empr->id_empr&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title=\"".$msg[caddie_icone_suppr_elt]."\" /></a> $marque_flag";
					else $lien_suppr_cart = $marque_flag ;
				$empr = new emprunteur($empr->id_empr, "", FALSE, 3);
				$empr->fiche_consultation = str_replace('!!image_suppr_caddie_empr!!'    , $lien_suppr_cart    , $empr->fiche_consultation);
				$empr->fiche_consultation = str_replace('!!lien_vers_empr!!'    , $link    , $empr->fiche_consultation);
				print $empr->fiche_consultation; 
			}
		} // fin de liste
	
	}
	print "<br />".$nav_bar ;
	return;
}

//*********************************************************************************
function aff_empr_choix_quoi($action="", $action_cancel="", $titre_form="", $bouton_valider="",$onclick="") {
	
	global $empr_cart_choix_quoi;
	
	$empr_cart_choix_quoi = str_replace('!!action!!', $action, $empr_cart_choix_quoi);
	$empr_cart_choix_quoi = str_replace('!!action_cancel!!', $action_cancel, $empr_cart_choix_quoi);
	$empr_cart_choix_quoi = str_replace('!!titre_form!!', $titre_form, $empr_cart_choix_quoi);
	$empr_cart_choix_quoi = str_replace('!!bouton_valider!!', $bouton_valider, $empr_cart_choix_quoi);
	if ($onclick!="") $empr_cart_choix_quoi = str_replace('!!onclick_valider!!','onClick="'.$onclick.'"',$empr_cart_choix_quoi); 
		else $empr_cart_choix_quoi = str_replace('!!onclick_valider!!','',$empr_cart_choix_quoi);
	return $empr_cart_choix_quoi;
	}

// ********************************************************************************
function verif_droit_proc_empr_caddie($id) {
	global $msg;
	global $PMBuserid;
	global $dbh;
	
	if ($id) {
		$requete = "SELECT autorisations FROM empr_caddie_procs WHERE idproc='$id' ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			$rqt_autorisation=explode(" ",$temp->autorisations);
			if (array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid == 1) return 1 ;
				else return 0 ;
			} else return 0;
		} else return 0 ;
	}

// ********************************************************************************
function verif_droit_empr_caddie($id) {
	global $msg;
	global $PMBuserid;
	global $dbh ;
	
	if ($id) {
		$requete = "SELECT autorisations FROM empr_caddie WHERE idemprcaddie='$id' ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			$rqt_autorisation=explode(" ",$temp->autorisations);
			if (array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid == 1) return $id ;
				else return 0 ;
			} else return 0;
		} else return 0 ;
	}
