<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bul_func.inc.php,v 1.44 2010-02-15 10:05:45 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonctions pour le bulletinage-----------------------------------------------

$selector_prop = "toolbar=no, dependent=yes, width=500, height=400, resizable=yes, scrollbars=yes";
$cart_click_bull = "onClick=\"openPopUp('./cart.php?object_type=BULL&item=!!item!!', 'cart', 600, 700, -2, -2, '$selector_prop')\"";
$cart_click_expl = "onClick=\"openPopUp('./cart.php?object_type=EXPL&item=!!item!!', 'cart', 600, 700, -2, -2, '$selector_prop')\"";

// affichage d'informations pour une entrée de bulletinage
function show_bulletinage_info_catalogage($bul_id) {
	global $dbh;
	global $msg, $base_path;
	global $liste_script;
	global $liste_debut;
	global $liste_fin;
	global $bul_action_bar;
	global $bul_cb_form;
	global $cart_click_bull;
	global $charset;
	global $pmb_droits_explr_localises;
	global $explr_visible_mod;
	
	if ($bul_id) {
		$myBul = new bulletinage($bul_id, 0, "./catalog.php?categ=serials&sub=bulletinage&action=explnum_form&bul_id=$bul_id&explnum_id=!!explnum_id!!");
		// lien vers la notice chapeau
		$link_parent = "<a href=\"./catalog.php?categ=serials\">";
		$link_parent .= $msg[4010]."</a>";
		$link_parent .= "<img src=\"./images/d.gif\" align=\"middle\" hspace=\"5\">";
		$link_parent .= "<a href=\"./catalog.php?categ=serials&sub=view&serial_id=";
		$link_parent .= $myBul->bulletin_notice."\">".$myBul->tit1.'</a>';
		$link_parent .= "<img src=\"./images/d.gif\" align=\"middle\" hspace=\"5\">";
		$txt_drag="";
		if ($myBul->bulletin_numero) $txt_drag .= $myBul->bulletin_numero." ";
		if ($myBul->mention_date) $txt_drag .= " (".$myBul->mention_date.") ";
		$txt_drag .= "[".$myBul->aff_date_date."]";
		$link_parent.=$txt_drag;
		if ($myBul->bulletin_titre) $link_parent .= " : ".htmlentities($myBul->bulletin_titre,ENT_QUOTES, $charset) ;
		
		print pmb_bidi("<div class='row'><div class='perio-barre'>".$link_parent."</div></div>");
		
		$cart_link = "<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title=\"${msg[400]}\" $cart_click_bull>";
		$cart_link = str_replace('!!item!!', $bul_id, $cart_link);
		$cart_link.="<span id='BULL_drag_".$bul_id."'  dragicon=\"$base_path/images/icone_drag_notice.png\" dragtext=\"".htmlentities($txt_drag,ENT_QUOTES, $charset)."\" draggable=\"yes\" dragtype=\"notice\" callback_before=\"show_carts\" callback_after=\"\" style=\"padding-left:7px\"><img src=\"".$base_path."/images/notice_drag.png\"/></span>";
		
		$bul_action_bar = str_replace('!!bul_id!!', $bul_id, $bul_action_bar);
		$bul_action_bar = str_replace('!!serial_id!!', $myBul->bulletin_notice, $bul_action_bar);
		$bul_action_bar = str_replace('!!nb_expl!!', sizeof($myBul->expl), $bul_action_bar);
		
		$bul_isbd = $myBul->display;

		$affichage_expl=get_expl($myBul->expl);
		global $flag_no_delete_bulletin;
		if(!$flag_no_delete_bulletin)$bul_action_bar = str_replace("!!bulletin_delete_button!!", "<input type='button' class='bouton' onclick=\"confirm_bul_delete();\" value='$msg[63]' />", $bul_action_bar);
		else $bul_action_bar = str_replace("!!bulletin_delete_button!!", "", $bul_action_bar);

		if($myBul->bull_num_notice)
			print $liste_script;
		print pmb_bidi("		
		<div class='bulletins-perio'>
			<div class='row'>
				<h2>$cart_link $bul_isbd</h2>
				</div>
			<div class='row'>
				$bul_action_bar
				</div>
			</div>");
		
		// affichage des exemplaires associés
		$list_expl  = "<div class='exemplaires-perio'>";
		$list_expl .= "<h3>".$msg[4012]."</h3>";
	
		$list_expl .= "<div class='row'>".$affichage_expl."</div></div>";
		print pmb_bidi($list_expl);
		
		$aff_expl_num=$myBul->explnum ;
		if ($aff_expl_num) {
			$list_expl = "<div class='exemplaires-perio'><h3>".$msg[explnum_docs_associes]."</h3>";
			$list_expl .= "<div class='row'>".$aff_expl_num."</div></div>";
			print pmb_bidi($list_expl);
			}
		if ((!$explr_visible_mod)&&($pmb_droits_explr_localises==1)) {
			$etiquette_expl="";
			$btn_ajouter_expl="";
			$saisie_num_expl="<div class='colonne10'><img src='./images/error.png' /></div>";
			$saisie_num_expl.= "<div class='colonne-suite'><span class='erreur'>".$msg["err_add_invis_expl"]."</span></div>";
		} else {
			$etiquette_expl="<div class='row'>
						<label class='etiquette' for='form_cb'>$msg[291]</label>
						</div>";
			$btn_ajouter_expl="<input type='submit' class='bouton' value=' $msg[expl_ajouter] ' onClick=\"return test_form(this.form)\">";
			global $pmb_numero_exemplaire_auto;
			if($pmb_numero_exemplaire_auto>0) $num_exemplaire_auto=" $msg[option_num_auto] <INPUT type=checkbox name='option_num_auto' value='num_auto' checked >";
			$saisie_num_expl="<input type='text' class='saisie-20em' name='noex' value=''>".$num_exemplaire_auto;				
		}
		$bul_cb_form = str_replace('!!bul_id!!', $bul_id, $bul_cb_form);
		$bul_cb_form = str_replace('!!etiquette!!', $etiquette_expl, $bul_cb_form);	
		$bul_cb_form = str_replace('!!saisie_num_expl!!', $saisie_num_expl, $bul_cb_form);
		$bul_cb_form = str_replace('!!btn_ajouter!!', $btn_ajouter_expl, $bul_cb_form);
		print "<div class='row'>".$bul_cb_form."</div>";
		
		// zone d'affichage des dépouillements
		$liste = get_analysis($bul_id);
		if ($liste) {
			$icones_exp .= $liste_debut."&nbsp;<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title='".$msg[400]."' onClick=\"openPopUp('./cart.php?object_type=BULL&item=".$bul_id."&what=DEP', 'cart', 500, 400, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes')\">";
			$liste_dep = $liste;
			$liste_dep .= $liste_fin;
			// inclusion du javascript inline
			$liste_dep .= (!$myBul->bull_num_notice ? $liste_script : "");
		} else {
			$icones_exp .= "";
			$liste_dep .= "<div class='row'>".$msg['bulletin_no_analysis']."</div>";
		}
		$link_new_dep = "<input type='button' class='bouton' value=' $msg[4021] ' onClick=\"document.location='./catalog.php?categ=serials&sub=analysis&action=analysis_form&bul_id=$bul_id&analysis_id=0';\" />";
		print pmb_bidi("
			<div class='depouillements-perio'>
				<h3>".$msg[4013].$icones_exp." $link_new_dep</h3>
				<div class='row'>
					$liste_dep
					</div>
				</div>");
				
		$rqt_nt="select count(*) from exemplaires, bulletins, docs_statut where exemplaires.expl_statut=docs_statut.idstatut and bulletins.bulletin_id=exemplaires.expl_bulletin and pret_flag=1 and bulletins.bulletin_id=".$bul_id;
		$result = mysql_query($rqt_nt, $dbh) or die ($rqt_nt. " ".mysql_error()) ;
		if ($result) {
			$aff_resa=resa_list(0, $bul_id, 0) ;
			$ouvrir_reserv = "onclick=\"parent.location.href='./circ.php?categ=resa_from_catal&id_bulletin=".$bul_id."'; return(false) \"";
			if ($aff_resa) print pmb_bidi("<b>$msg[resas]</b><br /><input type='button' class='bouton' value='".$msg['351']."' $ouvrir_reserv><br /><br />".$aff_resa."<br />");
			else {
				$affich=mysql_fetch_array($result);
				if ($affich[0]!=0) print pmb_bidi("<b>$msg[resas]</b><br /><input type='button' class='bouton' value='".$msg['351']."' $ouvrir_reserv><br /><br />");
			}
		}
		//$aff_resa=resa_list (0, $bul_id, 0) ;
		//if ($aff_resa) print pmb_bidi("<h3>$msg[resas]</h3>".$aff_resa);
		}
	}
