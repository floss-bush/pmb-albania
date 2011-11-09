<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: isbd.inc.php,v 1.42 2011-01-25 16:35:19 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");
	
	$sort_children = 1;
	
	// définition de quelques variables
	$libelle = $msg[270];
	
	$expl_link = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!';
	$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!'; 
	$isbd = new mono_display($id, 6, '', 1, $expl_link, '', $link_explnum,1);
	
	$cart_click_isbd = "onClick=\"openPopUp('./cart.php?object_type=NOTI&item=$id', 'cart', 600, 700, -2, -2, '$selector_prop')\"";
	$cart_click_isbd = "<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title=\"${msg[400]}\" $cart_click_isbd>" ;
	
	if ($current!==false) {
		$print_action = "&nbsp;<a href='#' onClick=\"openPopUp('./print.php?current_print=$current&notice_id=".$id."&action_print=print_prepare','print',500,600,-2,-2,'scrollbars=yes,menubar=0'); w.focus(); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
	}	
	$visualise_click_notice="
		<script type=\"text/javascript\" src='./javascript/select.js'></script>
		
		<a href='#' onClick='show_frame(\"$pmb_opac_url"."notice_view.php?id=$id\")'><img src='./images/search.gif' align='middle'name='imEx'  border='0' /></a>";   
	    
	// header
	print pmb_bidi("
	<div class='row' style='padding-top: 8px;'>
		".$isbd->aff_statut.$cart_click_isbd.$print_action."$visualise_click_notice<h1 style='display: inline;'>".$isbd->header."</h1>
		 </div>");
	
	$boutons  = "<div class='row'><div class='left'><input type='button' name='modifier' class='bouton' value='$msg[62]' onClick=\"document.location='./catalog.php?categ=modif&id=$id';\" />&nbsp;";
	$boutons .= "<input type='button' class='bouton' value='$msg[158]' onclick='document.location=\"./catalog.php?categ=remplace&id=".$id."\"' />&nbsp;";
	if ($z3950_accessible) 
		$boutons .= "<input type='button' class='bouton' value='$msg[notice_z3950_update_bouton]' onclick='document.location=\"./catalog.php?categ=z3950&id_notice=".$id."&isbn=".$isbd->isbn."\"' />&nbsp;";
	if ($pmb_allow_external_search)
		$boutons .= "<input type='button' class='bouton' value='$msg[notice_replace_external]' onclick='document.location=\"./catalog.php?categ=search&mode=7&external_type=simple&notice_id=".$id."&from_mode=0&code=".$isbd->isbn."\"' />&nbsp;";
	$boutons .= "<input type='button' class='bouton' value='$msg[notice_duplicate_bouton]' onclick='document.location=\"./catalog.php?categ=duplicate&id=".$id."\"' />&nbsp;";
	$boutons .= "<input type='button' class='bouton' value='$msg[notice_child_bouton]' onclick='document.location=\"./catalog.php?categ=create_form&id=0&notice_parent=".$id."\"' />&nbsp;";
	if($acquisition_active) {
		$boutons .= "<input type='button' class='bouton' value='".$msg["acquisition_sug_do"]."' onclick='document.location=\"./catalog.php?categ=sug&action=modif&id_bibli=0&id_notice=".$id."\"' />";
	}
	if ($pmb_type_audit) 
		$boutons .= "&nbsp;<input class='bouton' type='button' onClick=\"openPopUp('./audit.php?type_obj=1&object_id=$id', 'audit_popup', 700, 500, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" title=\"".$msg['audit_button']."\" value=\"".$msg['audit_button']."\" />&nbsp;";
	$boutons .="</div>";
	
	global $at_least_one_has_expl;
	$requete_compte_expl_id="select 1 from exemplaires where expl_notice='".$id."'";
	$resultat_compte_expl_id=mysql_query($requete_compte_expl_id);
	if (!mysql_num_rows($resultat_compte_expl_id)) {
		$message=$msg["confirm_suppr_notice"];
		if ($isbd->nb_expl!=0) $at_least_one_has_expl++;
		if ($at_least_one_has_expl) $message=$msg["del_expl_noti_child"];
		$boutons .= "<div class='right'><script type=\"text/javascript\">
						function confirm_delete() {
							result = confirm(\"$message\");
		       			if(result)
		           			document.location = './catalog.php?categ=delete&id=".$id."'
						}
					</script>
					<input type='button' class='bouton' value=\"{$msg[supprimer]}\" onClick=\"confirm_delete();\" />
				</div>";
		
	} 
	$boutons .="</div>";
	
	if($boutons) $isbd->isbd = str_replace('<!-- !!bouton_modif!! -->', $boutons, $isbd->isbd);
	else $isbd->isbd = str_replace('<!-- !!bouton_modif!! -->', "", $isbd->isbd);
	
	// isbd + exemplaires existants
	print pmb_bidi("
	<div class='row'>
		$isbd->isbd
		</div>");
	
	// pour affichage de l'image de couverture
	if ($pmb_book_pics_show=='1' && (($pmb_book_pics_url && $isbd->notice->code) || $isbd->notice->thumbnail_url)) {
		print "<script type='text/javascript'>
			<!--
			var img = document.getElementById('PMBimagecover".$id."');
			isbn=img.getAttribute('isbn');
			vigurl=img.getAttribute('vigurl');
			url_image=img.getAttribute('url_image');
			if (vigurl) {
				if (img.src.substring(img.src.length-8,img.src.length)=='vide.png') {
					img.src=vigurl;
				}
			} else {
				if (isbn) {
					if (img.src.substring(img.src.length-8,img.src.length)=='vide.png') {
						img.src=url_image.replace(/!!noticecode!!/,isbn);
					}
				}
			}		
			//-->
			</script>
			";
	}
	
	
	
	// form de création d'exemplaire
	if ((!$explr_visible_mod)&&($pmb_droits_explr_localises)) {
		$etiquette_expl="";
		$btn_ajouter_expl="";	
		$saisie_num_expl="<div class='colonne10'><img src='./images/error.png' /></div>";
		$saisie_num_expl.= "<div class='colonne-suite'><span class='erreur'>".$msg["err_add_invis_expl"]."</span></div>";
	} else {
		global $pmb_numero_exemplaire_auto;
		//if($pmb_numero_exemplaire_auto>0) $num_exemplaire_auto=" $msg[option_num_auto] <INPUT type=checkbox name='option_num_auto' value='num_auto' checked >";
		if($pmb_numero_exemplaire_auto==1 || $pmb_numero_exemplaire_auto==2) $num_exemplaire_auto=" $msg[option_num_auto] <INPUT type=checkbox name='option_num_auto' value='num_auto' checked >";
		$etiquette_expl="<label class='etiquette' for='form_cb'>$msg[291]</label>";
		$btn_ajouter_expl="<input type='submit' class='bouton' value=' $msg[expl_ajouter] ' onClick=\"return test_form(this.form)\">";
		$saisie_num_expl="<input type='text' class='saisie-20em' name='noex' value=''>".$num_exemplaire_auto;
	}
	$expl_new = str_replace ('!!etiquette!!',$etiquette_expl,$expl_new);
	$expl_new = str_replace ('!!saisie_num_expl!!',$saisie_num_expl,$expl_new);
	$expl_new = str_replace ('!!btn_ajouter!!',$btn_ajouter_expl,$expl_new);
	$expl_new = str_replace('!!id!!', $id, $expl_new);
	print "<div class=\"row\">";
	print $expl_new;
	print "</div>";

?>
