<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: budgets.inc.php,v 1.35 2009-05-16 11:11:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des budgets
require_once("$class_path/entites.class.php");
require_once("$class_path/exercices.class.php");
require_once("$class_path/budgets.class.php");
require_once("$class_path/rubriques.class.php");
require_once("$include_path/templates/budgets.tpl.php");


//Affiche la liste des etablissements
function show_list_biblio() {
	
	global $dbh, $msg, $charset;

	//Récupération de l'utilisateur
 	$requete_user = "SELECT userid FROM users where username='".SESSlogin."' limit 1 ";
	$res_user = mysql_query($requete_user, $dbh);
	$row_user=mysql_fetch_row($res_user);
	$user_userid=$row_user[0];


	//Affichage de la liste des etablissements auxquelles a acces l'utilisateur
	$aff = "<table>";
	$q = entites::list_biblio($user_userid);
	$res = mysql_query($q, $dbh);
	$nbr = mysql_num_rows($res);

	if(!$nbr) {
		//Pas d'etablissements définis pour l'utilisateur
		$error = true; 
		$error_msg.= htmlentities($msg["acquisition_err_coord"],ENT_QUOTES, $charset)."<div class='row'></div>";	
	}
	
	if ($error) {
		error_message($msg[321], $error_msg.htmlentities($msg["acquisition_err_par"],ENT_QUOTES, $charset), '1', './admin.php?categ=acquisition');
		die;
	}
	if ($nbr == '1') {
		
		$row = mysql_fetch_object($res);
		show_list_budg($row->id_entite);		
	
	} else {
	
		$parity=1;
		while($row=mysql_fetch_object($res)){
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=acquisition&sub=budget&action=list&id_bibli=$row->id_entite';\" ";
	        $aff.= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>$row->raison_sociale</i></td></tr>";
		}
		
		$aff.= "</table>";
		print $aff;
	}
}


//Affiche la liste des budgets pour un etablissement
function show_list_budg($id_bibli) {
	
	global $dbh;
	global $msg;
	global $charset;

	//Rappel du nom de l'etablissement  
	$biblio = new entites($id_bibli);
	print "<div class='row'><label class='etiquette'>".htmlentities($biblio->raison_sociale,ENT_QUOTES,$charset)."</label></div>";
	print "<table>
	<tr>
		<th>".htmlentities($msg[103],ENT_QUOTES,$charset)."</th>
		<th>".htmlentities($msg[acquisition_statut],ENT_QUOTES,$charset)."</th>
	</tr>";

	//Affichage de la liste des budgets
	$q = budgets::listByEntite($id_bibli);
	$res = mysql_query($q, $dbh);

	$parity=1;
	while($row=mysql_fetch_object($res)){
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=acquisition&sub=budget&action=modif&id_bibli=$row->num_entite&id_bud=$row->id_budget';\" ";
	        print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>".htmlentities($row->libelle, ENT_QUOTES, $charset)."</i></td>";
	        print '<td>';
	        switch ($row->statut) {
	        	case STA_BUD_VAL :
	        		print htmlentities($msg[acquisition_statut_actif],ENT_QUOTES,$charset) ;
	        		break;
	        	case  STA_BUD_CLO :
	        		print htmlentities($msg[acquisition_statut_clot],ENT_QUOTES,$charset) ;
	        		break;
	        	default:
	        		print htmlentities($msg[acquisition_budg_pre],ENT_QUOTES,$charset) ;
	        		break;
	        }
			print "</td></tr>";
	}
	print "</table>
		<input class='bouton' type='button' value=' ".$msg[acquisition_ajout_budg]." ' onClick=\"document.location='./admin.php?categ=acquisition&sub=budget&action=add&id_bibli=$id_bibli'\" />";

}


//Affiche le formulaire d'un budget
function show_budg_form($id_bibli, $id_bud=0) {
		
	global $dbh, $msg, $charset;
	global $budg_form, $bt_add_lig;
	global $ptab;
	global $lig_rub, $lig_rub_img;
	global $mnt_form, $sel_typ_form;

	//Récuperation du budget
	if ($id_bud) $bud= new budgets($id_bud);

	//Affichage exercices actifs
	$q = exercices::listByEntite($id_bibli, STA_EXE_ACT, 'statut desc, date_debut desc');
	$res = mysql_query($q, $dbh);

	
	if (!$id_bud) {	//Nouveau budget ->choix exercice possible & choix type possible (affectation globale ou par lignes)
		
		$form_exer = "<select id='exer' name ='exer' >";
		while ($row=mysql_fetch_object($res)) {
			$form_exer.="<option value='".$row->id_exercice."' >".$row->libelle."</option>";
		}
		$form_exer.= "</select>";		
		
		$mnt = $mnt_form[0];
		$sel_typ = $sel_typ_form;
		
		$bouton_dup = "";
				
	} else {			//Modification

		if (($bud->statut == STA_BUD_PRE) || ($bud->statut == STA_BUD_VAL && (!budgets::hasLignes($id_bud)) ) ) {		//Exercice modifiable si budget non activé ou pas de lignes d'actes affectées
			
			$form_exer = "<select id='exer' name ='exer' >";
			while ($row=mysql_fetch_object($res)) {
				$form_exer.="<option value='".$row->id_exercice."' ";
				if($bud->num_exercice == $row->id_exercice) $form_exer.= "selected='selected' ";
				$form_exer.=">".$row->libelle."</option>";
			}
			$form_exer.= "</select>";	
		
		} else {	// Exercice non modifiable si budget activé et non vide ou cloturé
		
			$exer = new exercices($bud->num_exercice);
			$form_exer = "<input type='hidden' id='exer' name='exer' value='".$exer->id_exercice."' />".htmlentities($exer->libelle, ENT_QUOTES, $charset);
		}

		if ($bud->type_budget == TYP_BUD_RUB) {
			$mnt = $bud->montant_global;
		} else {
			$mnt = str_replace('!!mnt_bud!!', $bud->montant_global, $mnt_form[1]);
		}

		if(!$bud->type_budget) {
			$sel_typ = htmlentities($msg['acquisition_budg_aff_rub'], ENT_QUOTES, $charset);
		} else {
			$sel_typ = htmlentities($msg['acquisition_budg_aff_glo'], ENT_QUOTES, $charset);
		}
		
		$bouton_dup = $ptab[5];
	}
	
	
	//Affichage entete formulaire
	if(!$id_bud) {

		$budg_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_ajout_budg],ENT_QUOTES,$charset), $budg_form);
		$budg_form = str_replace('!!libelle!!', '', $budg_form);
		$budg_form = str_replace('!!seuil!!', '100', $budg_form);
		$budg_form = str_replace('!!comment!!', '', $budg_form);
		$budg_form = str_replace('!!statut!!', htmlentities($msg[acquisition_budg_pre], ENT_QUOTES, $charset), $budg_form);
		$budg_form = str_replace('!!val_statut!!', '0', $budg_form);

	} else {
		
		$budg_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_modif_budg],ENT_QUOTES,$charset), $budg_form);
		$budg_form = str_replace('!!libelle!!', htmlentities($bud->libelle,ENT_QUOTES,$charset), $budg_form);
		$budg_form = str_replace('!!seuil!!', $bud->seuil_alerte, $budg_form);
		$budg_form = str_replace('!!comment!!', htmlentities($bud->commentaires,ENT_QUOTES,$charset), $budg_form);


		switch ($bud->statut) {
			
			case STA_BUD_PRE :
				$budg_form = str_replace('!!statut!!', htmlentities($msg[acquisition_budg_pre],ENT_QUOTES,$charset), $budg_form);
				//Affichage du bouton d'activation
				$budg_form = str_replace('<!-- bouton_act -->', $ptab[2], $budg_form);
				break;
				
			case STA_BUD_VAL :
				$budg_form = str_replace('!!statut!!', htmlentities($msg[acquisition_statut_actif],ENT_QUOTES,$charset), $budg_form);
				//Affichage du bouton de cloture
				$budg_form = str_replace('<!-- bouton_clot -->', $ptab[0], $budg_form);
				break;
				
			case STA_BUD_CLO :
				$budg_form = str_replace('!!statut!!', htmlentities($msg[acquisition_statut_clot],ENT_QUOTES,$charset), $budg_form);
				break;
				
			default :
				$budg_form = str_replace('!!statut!!', htmlentities($msg[acquisition_budg_pre],ENT_QUOTES,$charset), $budg_form);
				//Affichage du bouton d'activation
				$budg_form = str_replace('<!-- bouton_act -->', $ptab[2], $budg_form);
				break;	
		}

		$budg_form = str_replace('!!val_statut!!', $bud->statut, $budg_form);
		$budg_form = str_replace('<!-- bouton_sup -->', $ptab[1], $budg_form);
			
	}

	$budg_form = str_replace('!!montant!!', $mnt, $budg_form);
	$budg_form = str_replace('!!sel_typ!!', $sel_typ, $budg_form);

	$budg_form = str_replace('!!id!!', $id_bud, $budg_form);
	$budg_form = str_replace('!!libelle_suppr!!', addslashes($bud->libelle), $budg_form);

	$budg_form = str_replace('!!id_parent!!', 0, $budg_form);	
	
	//Affichage rubriques budgetaires
	if (!$id_bud) {
		
		$budg_form = str_replace('!!lib_mnt!!', htmlentities($msg['acquisition_rub_mnt'], ENT_QUOTES, $charset), $budg_form);
		$budg_form = str_replace('<!-- rubriques -->', '', $budg_form);
				
	} else {
		

		if ($bud->type_budget == TYP_BUD_RUB ) {		
			$budg_form = str_replace('!!lib_mnt!!', htmlentities($msg['acquisition_rub_mnt'], ENT_QUOTES, $charset), $budg_form);
		} else {
			$budg_form = str_replace('!!lib_mnt!!', '&nbsp;', $budg_form);
		}			

		
		$q = budgets::listRubriques($id_bud);		
		$list_n1 = mysql_query($q, $dbh);
		while($row=mysql_fetch_object($list_n1)){
			
			$budg_form = str_replace('<!-- rubriques -->', $lig_rub[0].'<!-- rubriques -->', $budg_form);
			$budg_form = str_replace('<!-- marge -->', '', $budg_form);
			if (rubriques::countChilds($row->id_rubrique)) {
				$budg_form = str_replace('<!-- img_plus -->', $lig_rub_img, $budg_form);
			} else {
				$budg_form = str_replace('<!-- img_plus -->', '', $budg_form);
			}
			$budg_form = str_replace('!!id_rub!!', $row->id_rubrique, $budg_form);
			$budg_form = str_replace('!!id_parent!!', $row->num_parent, $budg_form);			
			$budg_form = str_replace('!!lib_rub!!', $row->libelle, $budg_form);
			if ($bud->type_budget == TYP_BUD_RUB ) {
				$budg_form = str_replace('!!lib_mnt!!', htmlentities($msg['acquisition_rub_mnt'], ENT_QUOTES, $charset), $budg_form);
				$budg_form = str_replace('!!mnt!!', $row->montant, $budg_form);
			} else {
				$budg_form = str_replace('!!lib_mnt!!', '&nbsp;', $budg_form);
				$budg_form = str_replace('!!mnt!!', '&nbsp', $budg_form);
			}
			$budg_form = str_replace('!!ncp!!', $row->num_cp_compta, $budg_form);
			$budg_form = str_replace('<!-- sous_rub -->', '<!-- sous_rub'.$row->id_rubrique.' -->', $budg_form);
			
			afficheSousRubriques($id_bud, $row->id_rubrique, $budg_form, 1);
		
		}
		
		if ($bud->statut != STA_BUD_CLO ) {		
			$budg_form = str_replace('<!-- bouton_lig -->', $bt_add_lig, $budg_form);
		}
		
	}
		
				
	$biblio = new entites($id_bibli);	
	print "<div class='row'><label class='etiquette'>".htmlentities($biblio->raison_sociale,ENT_QUOTES,$charset)."</label></div>";	

	$budg_form = str_replace('<!-- bouton_dup -->', $bouton_dup, $budg_form);

	$budg_form = str_replace('!!exer!!', $form_exer, $budg_form);	
	$budg_form = str_replace('!!id_bibli!!', $id_bibli, $budg_form);
	$budg_form = str_replace('!!id_bud!!', $id_bud, $budg_form);
	$budg_form = str_replace('!!id_rub!!', 0, $budg_form);
	$budg_form = str_replace('!!id_parent!!', 0, $budg_form);	

	print $budg_form;
	print confirmation_delete("./admin.php?categ=acquisition&sub=budget&action=del&id_bibli=".$id_bibli."&id_bud=");

	
}


//Affiche le formulaire d'une rubrique
function show_rub_form($id_bud, $id_rub=0, $id_parent=0) {
		
	global $dbh, $msg, $charset;
	global $rub_form, $bt_add_lig;
	global $ptab;
	global $lig_rub, $lig_rub_img;
	global $mnt_rub_form;


	//Récuperation du budget
	if ($id_bud) $bud= new budgets($id_bud);
		else die();


	//Récupération entité
	$id_bibli = $bud->num_entite;
	$biblio = new entites($id_bibli);	
	$head_form = "<div class='row'><label class='etiquette'>".htmlentities($biblio->raison_sociale,ENT_QUOTES,$charset)."</label></div>";	

		
	//Affichage entete formulaire
	if(!$id_rub) { //création de rubrique

		$rub_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_ajout_rub],ENT_QUOTES,$charset), $rub_form);

		//Affichage barre de navigation
		$nav_form.= "<a href=\"./admin.php?categ=acquisition&sub=budget&action=modif&id_bibli=".$id_bibli."&id_bud=".$id_bud."\" >".$bud->libelle."</a>";
		if ($id_parent) {
			$list_bar = rubriques::listAncetres($id_parent, TRUE); 			
			foreach ($list_bar as $value) {
				$nav_form.= "&nbsp;&gt;&nbsp;<a href=\"./admin.php?categ=acquisition&sub=budget&action=modif_rub&id_bud=".$id_bud."&id_rub=".$value[0]."&id_parent=".$value[2]."\" >".htmlentities($value[1], ENT_QUOTES, $charset)."</a>";
			}
		}
		$rub_form = str_replace('<!-- nav_form -->', $nav_form, $rub_form);	
		$rub_form = str_replace('!!libelle!!', '', $rub_form);

		if ($bud->type_budget == TYP_BUD_RUB ) {		
			$rub_form = str_replace('<!-- lib_mnt -->', $mnt_rub_form[0], $rub_form);
			$mnt_rub = str_replace('!!mnt_rub!!', '0.00',$mnt_rub_form[1]);
			$rub_form = str_replace('<!-- montant -->', $mnt_rub, $rub_form);
			$rub_form = str_replace('!!lib_mnt!!', htmlentities($msg['acquisition_rub_mnt'], ENT_QUOTES, $charset), $rub_form);
		} else {
			$rub_form = str_replace('!!lib_mnt!!', '&nbsp;', $rub_form);
		}			
		
		$label_ncp ="<label class='etiquette' for='ncp'>".htmlentities($msg[acquisition_num_cp_compta],ENT_QUOTES,$charset)."</label>";
		$rub_form = str_replace('<!-- label_ncp -->', $label_ncp, $rub_form);
		
		$ncp = "<input type='text' id='ncp' name='ncp' class='saisie-30em' style='text-align:right' value='' />";
		$rub_form = str_replace('!!ncp!!', $ncp, $rub_form);
		$rub_form = str_replace('!!comment!!', '', $rub_form);

		//Complément du bouton annuler
		if(!$id_parent) {
			$undo = "modif";
		} else {
			$undo = "modif_rub";
		}
		$rub_form = str_replace('!!undo!!', $undo, $rub_form);		

		//complément du formulaire
		$rub_form = str_replace('!!id_bibli!!', $id_bibli, $rub_form);
		$rub_form = str_replace('!!id_bud!!', $id_bud, $rub_form);
		$rub_form = str_replace('!!id_rub!!', 0, $rub_form);
		$rub_form = str_replace('!!id_parent!!', $id_parent, $rub_form);

		//Affichage des autorisations
		autorisations($id_rub, $id_parent, $id_bud);

	} else { //modification de rubrique


		$rub_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_modif_rub],ENT_QUOTES,$charset), $rub_form);

		//Récupération rubrique
		if ($id_rub) $rub = new rubriques($id_rub);				

		//Affichage barre de navigation
		$nav_form.= "<a href=\"./admin.php?categ=acquisition&sub=budget&action=modif&id_bibli=".$id_bibli."&id_bud=".$id_bud."\" >".$bud->libelle."</a>";
		$list_bar = rubriques::listAncetres($id_rub, FALSE); 			
		foreach ($list_bar as $value) {
			$nav_form.= "&nbsp;&gt;&nbsp;<a href=\"./admin.php?categ=acquisition&sub=budget&action=modif_rub&id_bud=".$id_bud."&id_rub=".$value[0]."&id_parent=".$value[2]."\" >".htmlentities($value[1], ENT_QUOTES, $charset)."</a>";
			
		}
		$rub_form = str_replace('<!-- nav_form -->', $nav_form, $rub_form);
		$rub_form = str_replace('!!libelle!!', htmlentities($rub->libelle,ENT_QUOTES,$charset), $rub_form);


		if (!$bud->type_budget) {

			$rub_form = str_replace('!!lib_mnt!!', htmlentities($msg['acquisition_rub_mnt'], ENT_QUOTES, $charset), $rub_form);

			if($rub->countChilds()) {
				$ncp = '&nbsp;';
				$aut = FALSE;
			} else {
				$rub_form = str_replace('<!-- lib_mnt -->', $mnt_rub_form[0], $rub_form);
				$mnt_rub = str_replace('!!mnt_rub!!', $rub->montant, $mnt_rub_form[1]);
				$rub_form = str_replace('<!-- montant -->', $mnt_rub, $rub_form);
				$label_ncp ="<label class='etiquette' for='ncp'>".htmlentities($msg[acquisition_num_cp_compta],ENT_QUOTES,$charset)."</label>";
				$ncp = "<input type='text' id='ncp' name='ncp' class='saisie-30em' style='text-align:right' value='".$rub->num_cp_compta."' />";
				$aut = TRUE;
			}

		} else {
			
			$rub_form = str_replace('!!lib_mnt!!', '&nbsp;', $rub_form);
			
			if($rub->countChilds()) {
				$ncp = '&nbsp;';
				$aut = FALSE;
			} else {
				$label_ncp ="<label class='etiquette' for='ncp'>".htmlentities($msg[acquisition_num_cp_compta],ENT_QUOTES,$charset)."</label>";
				$ncp = "<input type='text' id='ncp' name='ncp' class='saisie-30em' style='text-align:right' value='".$rub->num_cp_compta."' />";
				$aut = TRUE;
			}
			
			
		}


		$rub_form = str_replace('<!-- label_ncp -->', $label_ncp, $rub_form);
		$rub_form = str_replace('!!ncp!!', $ncp, $rub_form);
			
		$rub_form = str_replace('!!comment!!', htmlentities($rub->commentaires,ENT_QUOTES,$charset), $rub_form);
		
		//complément du formulaire
		$rub_form = str_replace('!!id_rub!!', $id_rub, $rub_form);
		$rub_form = str_replace('!!id_parent!!', $id_parent, $rub_form);
		
		//affichage du bouton ajout rubrique si budget non actif
			$bt_add_lig = str_replace('!!id_rub!!', '0', $bt_add_lig);
			$bt_add_lig = str_replace('!!id_parent!!', $id_rub, $bt_add_lig);
			$rub_form = str_replace('<!-- bouton_lig -->', $bt_add_lig, $rub_form);

		//Complément du bouton annuler
		if(!$id_parent) {
			$undo = "modif";
		} else {
			$undo = "modif_rub";
		}
		$rub_form = str_replace('!!undo!!', $undo, $rub_form);		
		
		//Affichage du bouton supprimer
		$rub_form = str_replace('<!-- bouton_sup -->', $ptab[1], $rub_form);
		print confirmation_delete("./admin.php?categ=acquisition&sub=budget&action=del_rub&id_bibli=".$id_bibli."&id_bud=".$id_bud."&id_parent=".$id_parent."&id_rub=");						
		$rub_form = str_replace('!!id!!', $id_rub, $rub_form);	
		$rub_form = str_replace('!!libelle_suppr!!', addslashes($rub->libelle), $rub_form);		

		
		//Affichage rubriques budgetaires
		$q = budgets::listRubriques($id_bud, $id_rub);	
		$list_n1 = mysql_query($q, $dbh); 

		while($row=mysql_fetch_object($list_n1)){
				
			$rub_form = str_replace('<!-- rubriques -->', $lig_rub[0].'<!-- rubriques -->', $rub_form);
			$rub_form = str_replace('<!-- marge -->', '', $rub_form);
			if (rubriques::countChilds($row->id_rubrique)) {
				$rub_form = str_replace('<!-- img_plus -->', $lig_rub_img, $rub_form);
			} else {
				$rub_form = str_replace('<!-- img_plus -->', '', $rub_form);
			}
			$rub_form = str_replace('!!id_rub!!', $row->id_rubrique, $rub_form);
			$rub_form = str_replace('!!id_parent!!', $row->num_parent, $rub_form);
			$rub_form = str_replace('!!lib_rub!!', $row->libelle, $rub_form);
			if (!$bud->type_budget) {
				$rub_form = str_replace('!!mnt!!', $row->montant, $rub_form);
			} else {
				$rub_form = str_replace('!!mnt!!', '&nbsp;', $rub_form);
			}
			$rub_form = str_replace('!!ncp!!', $row->num_cp_compta, $rub_form);
			$rub_form = str_replace('<!-- sous_rub -->', '<!-- sous_rub'.$row->id_rubrique.' -->', $rub_form);
			
			afficheSousRubriques($id_bud, $row->id_rubrique, $rub_form, 1);	
			
		}

		//complément du formulaire
		$rub_form = str_replace('!!id_bibli!!', $id_bibli, $rub_form);
		$rub_form = str_replace('!!id_bud!!', $id_bud, $rub_form);	
	
		//Affichage des autorisations
		if ($aut) {
			autorisations($id_rub, $id_parent, $id_bud);
		}
	
	}

	print $head_form.$rub_form;
		
}


function afficheSousRubriques($id_bud, $id_rub, &$form, $indent=0) {
	
	global $dbh, $msg, $charset;
	global $lig_rub, $lig_rub_img, $lig_indent;

	$bud = new budgets($id_bud);
	$q = budgets::listRubriques($id_bud, $id_rub);
	$list_n = mysql_query($q, $dbh); 
	
	while($row=mysql_fetch_object($list_n)){
			
		$form = str_replace('<!-- sous_rub'.$id_rub.' -->', $lig_rub[0].'<!-- sous_rub'.$id_rub.' -->', $form);
		$marge = '';
		for($i=0;$i<$indent;$i++){
			$marge.= $lig_indent;
		}
		$form = str_replace('<!-- marge -->', $marge, $form);
		
		if (rubriques::countChilds($row->id_rubrique)) {
			$form = str_replace('<!-- img_plus -->', $lig_rub_img, $form);
		} else {
			$form = str_replace('<!-- img_plus -->', '', $form);
		}
		$form = str_replace('<!-- sous_rub -->', '<!-- sous_rub'.$row->id_rubrique.' -->', $form);
		$form = str_replace('!!id_rub!!', $row->id_rubrique, $form);
		$form = str_replace('!!id_parent!!', $row->num_parent, $form);
		$form = str_replace('!!lib_rub!!', $row->libelle, $form);
		if ($bud->type_budget == TYP_BUD_RUB ) {
			$form = str_replace('!!mnt!!', $row->montant, $form);
		} else {
			$form = str_replace('!!mnt!!', '&nbsp;', $form);
		}
		$form = str_replace('!!ncp!!', $row->num_cp_compta, $form);
		
		afficheSousRubriques($id_bud, $row->id_rubrique, $form, $indent+1);
		
	}

}


function autorisations($id_rub=0, $id_parent=0, $id_bud=0) {
	
	global $dbh;
	global $charset;
	global $rub_form;
	global $ptab;
		

	//affichage entete
	$rub_form = str_replace('<!-- autorisations -->', $ptab[3].'<!-- autorisations -->', $rub_form);
	
	$bud = new budgets($id_bud);


	//récupération des autorisations de l'entité
	$bibli = new entites($bud->num_entite);
	$aut_entite = $bibli->autorisations;

	
	$aut = '';
	//récupération autorisations rubrique	
	if ($id_rub) {
		$rub = new rubriques($id_rub);
		$aut = $rub->autorisations;
		
	} else {
	
		//récupération autorisations rubrique parent
		if ($id_parent) {
			$rub_par = new rubriques($id_parent);
			$aut = $rub_par->autorisations;
		} 
		
		if ($aut=='') $aut = $aut_entite;	

	}	

	$aut = explode(' ',$aut);
	$aut_entite = explode(' ', $aut_entite);


	//récupération liste des noms d'utilisateurs pmb
	$q = "SELECT userid, username FROM users order by username ";
	$r = mysql_query($q, $dbh);

	while($row = mysql_fetch_object($r)){

		if(in_array($row->userid, $aut_entite)) {			
			
			$rub_form = str_replace('<!-- autorisations -->', $ptab[4].'<!-- autorisations -->', $rub_form);
		
			$rub_form = str_replace('!!user_name!!', htmlentities($row->username,ENT_QUOTES, $charset), $rub_form);
			$rub_form = str_replace('!!user_id!!', $row->userid, $rub_form);
			if (in_array($row->userid, $aut)) { 
				$chk = 'checked=\'checked\'';
			} else {
				$chk = '';
			}
			$rub_form = str_replace('!!checked!!', $chk, $rub_form);
		}				
	}
		
}


//Vérification qu'un exercice actif existe pour création budget
function verif_exercice($id_bibli) {
	
	global $charset;
	global $msg;
	global $dbh;
	
	$q = entites::getCurrentExercices($id_bibli);
	$r = mysql_query($q, $dbh);
	
	if (mysql_num_rows($r)) return; 

	//Pas d'exercice actif pour la bibliothèque
	$error_msg.= htmlentities($msg["acquisition_err_exer"],ENT_QUOTES, $charset)."<div class='row'></div>";	
	error_message($msg[321], $error_msg.htmlentities($msg["acquisition_err_par"],ENT_QUOTES, $charset), '1', './admin.php?categ=acquisition');
	die;
}



//Traitement des actions
switch($action) {

	
	case 'list':		show_list_budg($id_bibli);
		break;
		
	case 'add':
		verif_exercice($id_bibli);			
		show_budg_form($id_bibli);
		break;
		
	case 'modif':
		if (budgets::exists($id_bud)) {
			show_budg_form($id_bibli, $id_bud);
		} else {
			show_list_budg($id_bibli);
		}
		break;
		
	case 'update':
		// vérification validité des données fournies.
		//Pas deux libelles de budgets identiques pour la même entité et le même exercice
		$nbr = budgets::existsLibelle($id_bibli, $libelle, $exer, $id_bud);
		if ( $nbr > 0 ) {
			error_form_message($libelle.$msg["acquisition_budg_already_used"]);
			break;
		}
		//Seuil d'alerte compris entre 0 et 100
		if (!is_numeric($seuil) || $seuil < 0 || $seuil > 100 ) {
			error_form_message($libelle.$msg["acquisition_budg_seu_error"]);
			break;
		}	
		//Montant du budget compris entre 0.00 et 999999.99 si global
		if ( (!$id_bud && $sel_typ==1) || $id_bud ) {  
			if ( $mnt_bud && (!is_numeric($mnt_bud) || $mnt_bud < 0.00 || $mnt_bud > 999999.99 )) {
				error_form_message($libelle.$msg["acquisition_bud_mnt_error"]);
				break;
			}
		}
		$bu = new budgets($id_bud);				
		$bu->num_entite = $id_bibli;
		$bu->num_exercice = $exer;
		$bu->libelle = $libelle;
		$bu->commentaires = $comment;
		$bu->seuil_alerte = $seuil;
		$bu->statut = $val_statut;
		if (!$id_bud) {
			if (!$sel_typ) {
				$bu->type_budget = TYP_BUD_RUB ; //Affectation par rubriques
				$bu->montant_global = 0;
			} else {
				$bu->type_budget = TYP_BUD_GLO ; //Affectation globale
				$bu->montant_global = $mnt_bud;				
			}
		} else {
			if ($bu->type_budget == TYP_BUD_GLO ) $bu->montant_global = $mnt_bud;
		}
		$bu->save();
		
		if ($id_bud) show_list_budg($id_bibli);
			else show_budg_form($id_bibli, $bu->id_budget);
		break;
		
	case 'del':
		if($id_bud) {
			$budg = new budgets($id_bud);
			$total1 = budgets::hasLignes($id_bud); 
			$total2 = budgets::countRubriques($id_bud);
						
			if ( ($total1==0) &&  ($total2==0) ) {
				budgets::delete($id_bud);
				show_list_budg($id_bibli);
			} else {
				$msg_suppr_err = $msg[acquisition_budg_used] ;
				if ($total1) $msg_suppr_err .= "<br />- ".$msg[acquisition_budg_used_lg] ;
				if ($total2) $msg_suppr_err .= "<br />- ".$msg[acquisition_budg_used_rubr] ;
				error_message($msg[321], $msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=budget&action=list&id_bibli='.$id_bibli);
			}
		
		} else show_list_budg($id_bibli);
		break;
		
	case 'dup' :
		if (budgets::exists($id_bud)) {
			$id_new_bud = budgets::duplicate($id_bud);
			show_budg_form($id_bibli, $id_new_bud);			
		} else {
			show_list_budg($id_bibli);
		}
		break;
		
	case 'add_rub' :
		show_rub_form($id_bud, $id_rub, $id_parent);
		break;
		
	case 'modif_rub' :
		show_rub_form($id_bud, $id_rub, $id_parent);
		break;

	case 'update_rub' :
		//vérification des éléments saisis
		if ($mnt && (!is_numeric($mnt) || $mnt < 0.00 || $mnt > 999999.99 )) {
			error_form_message($libelle.$msg["acquisition_rub_mnt_error"]);
			break;
		}

		$rub = new rubriques($id_rub);
		$rub->num_budget = $id_bud;
		$rub->num_parent = $id_parent;
		$rub->libelle = $libelle;
		$rub->commentaires = $comment;
		if(isset($mnt))$rub->montant = $mnt;
		$rub->num_cp_compta = $ncp;
		if (is_array($user_aut)) $rub->autorisations = ' '.implode(' ',$user_aut).' ';
			else $rub->autorisations = '';			
		$rub->save();


		$bud = new budgets($id_bud); 
		if ($bud->type_budget == TYP_BUD_RUB) {		
			//màj des rubriques supérieures
			rubriques::maj($id_parent, TRUE);
			//recalcul du montant global de budget
			budgets::calcMontant($id_bud);
		} else {
			//màj des rubriques supérieures sans recalcul
			rubriques::maj($id_parent, FALSE);
		}
		
		
		if ($id_parent) {
			$rub_parent = new rubriques($id_parent);
			show_rub_form($id_bud, $id_parent, $rub_parent->num_parent);
		}
		else show_budg_form($id_bibli, $id_bud);
		break;

	case 'del_rub':
		if($id_rub) {
			$rub = new rubriques($id_rub);
			$total1 = rubriques::hasLignes($id_rub);
			$total2 = rubriques::countChilds($id_rub); 
						
			if ( ($total1==0) && $total2==0 ) {

				rubriques::delete($id_rub);
				
				$bud = new budgets($id_bud); 
				if ($bud->type_budget == TYP_BUD_RUB) {		
					//màj des rubriques supérieures
					rubriques::maj($id_parent, TRUE);
					//recalcul du montant global de budget
					budgets::calcMontant($id_bud);
				} else {
					//màj des rubriques supérieures sans recalcul
					rubriques::maj($id_parent, FALSE);
				}
					
				if ($id_parent) {
					$rub_parent = new rubriques($id_parent);
					show_rub_form($id_bud, $id_parent, $rub_parent->num_parent);
				}
				else show_budg_form($id_bibli, $id_bud);		
			
			} else {
				$msg_suppr_err = $msg[acquisition_rub_used] ;
				if ($total1) $msg_suppr_err .= "<br />- ".$msg[acquisition_rub_used_lg] ;
				if ($total2) $msg_suppr_err .= "<br />- ".$msg[acquisition_rub_used_childs] ;
				error_message($msg[321],$msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=budget&action=modif_rub&id_bud='.$id_bud.'&id_rub='.$id_rub.'&id_parent='.$rub->num_parent);
			}
		
		} else {
			
			if ($id_parent) {
				$rub_parent = new rubriques($id_parent);
				show_rub_form($id_bud, $id_parent, $rub_parent->num_parent);
			}
			else {
				show_budg_form($id_bibli, $id_bud);
			}
			
		}
		break;
		
	default:
		show_list_biblio();
		break;
}

?>
