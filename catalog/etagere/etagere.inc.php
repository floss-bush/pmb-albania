<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: etagere.inc.php,v 1.15 2011-03-30 14:54:21 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch ($action) {
	case 'new_etagere':
		$etagere_form = str_replace('!!formulaire_titre!!', $msg['etagere_new_etagere'], $etagere_form);
		$etagere_form = str_replace('!!autorisations_users!!', aff_form_autorisations_etagere("",1), $etagere_form);
		$etagere_form = str_replace('!!formulaire_action!!', "./catalog.php?categ=etagere&sub=gestion&action=valid_new_etagere", $etagere_form);
		$etagere_form = str_replace('!!formulaire_annuler!!', "./catalog.php?categ=etagere&sub=gestion&action=", $etagere_form);
		$etagere_form = str_replace('!!name!!', "", $etagere_form);
		$etagere_form = str_replace('!!comment!!', "", $etagere_form);
		$etagere_form = str_replace('!!checkbox_all!!', "checked", $etagere_form);
		$etagere_form = str_replace('!!form_visible_deb!!', "", $etagere_form);
		$etagere_form = str_replace('!!form_visible_fin!!', "", $etagere_form);
		$etagere_form = str_replace('!!checkbox_accueil!!', "", $etagere_form);
		$etagere_form = str_replace('!!tri_name!!', $msg['etagere_form_no_active_tri'], $etagere_form);	
		print pmb_bidi($etagere_form) ;
		break;
	case 'edit_etagere':
		$myEtagere = new etagere($idetagere);
		$etagere_form = str_replace('!!formulaire_titre!!', $msg['etagere_edit_etagere'], $etagere_form);
		$etagere_form = str_replace('!!formulaire_action!!', "./catalog.php?categ=etagere&sub=gestion&action=save_etagere&idetagere=$idetagere", $etagere_form);
		$etagere_form = str_replace('!!formulaire_annuler!!', "./catalog.php?categ=etagere&sub=gestion&action=", $etagere_form);
		$etagere_form = str_replace('!!idetagere!!', $idetagere, $etagere_form);
		$etagere_form = str_replace('!!name!!', htmlentities($myEtagere->name,ENT_QUOTES, $charset), $etagere_form);
		$bouton_suppr = "<input type='button' class='bouton' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete($idetagere,'".htmlentities(addslashes($myEtagere->name),ENT_QUOTES, $charset)."')\" />" ;
		$etagere_form = str_replace('<!--!!bouton_suppr!!-->', $bouton_suppr, $etagere_form);
		$etagere_form = str_replace('!!comment!!', $myEtagere->comment, $etagere_form);
		$etagere_form = str_replace('!!autorisations_users!!', aff_form_autorisations_etagere($myEtagere->autorisations,0), $etagere_form);
		if($myEtagere->id_tri>0){
			$sort = new sort("notices","base");
			$etagere_form = str_replace('!!tri!!', $myEtagere->id_tri, $etagere_form);
			$etagere_form = str_replace('!!tri_name!!', $sort->descriptionTriParId($myEtagere->id_tri), $etagere_form);
		}else{
			$etagere_form = str_replace('!!tri!!', "", $etagere_form);
			$etagere_form = str_replace('!!tri_name!!', $msg['etagere_form_no_active_tri'], $etagere_form);	
		}
		if ($myEtagere->validite) {
			$etagere_form = str_replace('!!checkbox_all!!', "checked", $etagere_form);
			$etagere_form = str_replace('!!form_visible_deb!!', "", $etagere_form);
			$etagere_form = str_replace('!!form_visible_fin!!', "", $etagere_form);
		} else {
			$etagere_form = str_replace('!!checkbox_all!!', "", $etagere_form);
			$etagere_form = str_replace('!!form_visible_deb!!', $myEtagere->validite_date_deb_f, $etagere_form);
			$etagere_form = str_replace('!!form_visible_fin!!', $myEtagere->validite_date_fin_f, $etagere_form);
			}
		if ($myEtagere->visible_accueil) $etagere_form = str_replace('!!checkbox_accueil!!', "checked", $etagere_form);
		else $etagere_form = str_replace('!!checkbox_accueil!!', "", $etagere_form);
			
		print confirmation_delete("./catalog.php?categ=etagere&action=del_etagere&idetagere=");
		print $etagere_form ;
		break;
	case 'del_etagere':
		$myEtagere= new etagere($idetagere);
		$myEtagere->delete();
		aff_etagere("edit_etagere",1);
		break;
	case 'save_etagere':
		$myEtagere= new etagere($idetagere);
		if (is_array($etagere_autorisations)) $autorisations=implode(" ",$etagere_autorisations);
		else $autorisations="1";
		$myEtagere->autorisations = $autorisations;
		$myEtagere->name = $form_etagere_name;
		$myEtagere->comment = $form_etagere_comment;
		$myEtagere->validite = $form_visible_all;
		$myEtagere->validite_date_deb_f = $form_visible_deb;
		$myEtagere->validite_date_fin_f = $form_visible_fin;
		$myEtagere->validite_date_deb = extraitdate($form_visible_deb);
		$myEtagere->validite_date_fin = extraitdate($form_visible_fin);
		$myEtagere->visible_accueil = $form_visible_accueil;
		$myEtagere->tri = $tri;
		$myEtagere->save_etagere();
		aff_etagere("edit_etagere",1);
		break;
	case 'valid_new_etagere':
		$myEtagere = new etagere(0);
		$myEtagere->create_etagere();
		if (is_array($etagere_autorisations)) $autorisations=implode(" ",$etagere_autorisations);
		else $autorisations="1";
		$myEtagere->autorisations = $autorisations;
		$myEtagere->name = $form_etagere_name;
		$myEtagere->comment = $form_etagere_comment;
		$myEtagere->validite = $form_visible_all;
		$myEtagere->validite_date_deb_f = $form_visible_deb;
		$myEtagere->validite_date_fin_f = $form_visible_fin;
		$myEtagere->validite_date_deb = extraitdate($form_visible_deb);
		$myEtagere->validite_date_fin = extraitdate($form_visible_fin);
		$myEtagere->visible_accueil = $form_visible_accueil;
		$myEtagere->tri = $tri;
		$myEtagere->save_etagere();
		aff_etagere("edit_etagere",1);
		break;
	default:
		aff_etagere("edit_etagere",1);
	}
