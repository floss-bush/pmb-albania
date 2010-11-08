<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fournisseurs.inc.php,v 1.30 2010-01-11 15:47:37 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], "fournisseurs.inc.php")) die("no access");

// gestion des coordonnées des fournisseurs
require_once("$class_path/entites.class.php");
require_once("$class_path/paiements.class.php");
require_once("$class_path/frais.class.php");
require_once("$class_path/types_produits.class.php");
require_once("$class_path/offres_remises.class.php");
require_once("$include_path/templates/coordonnees.tpl.php");
require_once("$include_path/templates/fournisseurs.tpl.php");


//Affiche la liste des bibliothèques
function show_list_biblio() {
	
	global $msg, $charset;
	global $tab_bib, $nb_bib;
	global $current_module;

	//Affichage de la liste des bibliothèques auxquelles a accès l'utilisateur
	if ($nb_bib == '1') {
		show_list_coord($tab_bib[0][0]);
		exit;
	}		

	$def_bibli=entites::getSessionBibliId();
	if (in_array($def_bibli, $tab_bib[0])) {
		show_list_coord($def_bibli);
		exit;		
	}			
	
	$aff = "<form class='form-".$current_module."' id='list_biblio_form' name='list_biblio_form' method='post' action=\"\" >";
	$aff.= "<h3>".htmlentities($msg['acquisition_menu_chx_ent'], ENT_QUOTES, $charset)."</h3><div class='row'></div>";
	$aff.= "<table>";
	
	$parity=1;
	foreach($tab_bib[0] as $k=>$v) {
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" onmousedown=\"document.forms['list_biblio_form'].setAttribute('action','./acquisition.php?categ=ach&sub=fourn&action=list&id_bibli=".$v."');document.forms['list_biblio_form'].submit(); \" ";
        $aff.= "<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer'><td><i>".htmlentities($tab_bib[1][$k], ENT_QUOTES, $charset)."</i></td></tr>";
	}
	$aff.= "</table></form>";
	print $aff;
}


//Affiche la liste des fournisseurs pour une bibliotheque
function show_list_coord($id_bibli) {
	
	global $msg, $charset;
	global $search_form;
	global $nb_per_page_acq;
	global $class_path;
	global $user_input, $page, $nbr_lignes, $tri_param, $limit_param, $last_param;
	global $tab_bib;
	//Creation selecteur etablissement
	$sel_bibli ="<select class='saisie-50em' id='id_bibli' name='id_bibli' onchange=\"submit();\" >";
	foreach($tab_bib[0] as $k=>$v) {
		$sel_bibli.="<option value='".$v."' ";
		if($v==$id_bibli) $sel_bibli.="selected='selected' ";
		$sel_bibli.=">".htmlentities($tab_bib[1][$k], ENT_QUOTES, $charset)."</option>";
	}
	$sel_bibli.="</select>";
	$search_form=str_replace('<!-- sel_bibli -->', $sel_bibli,$search_form);
	
	//Affichage form de recherche
	$titre = htmlentities($msg['recherche'].' : '.$msg['acquisition_ach_fou'], ENT_QUOTES, $charset);
	$action ="./acquisition.php?categ=ach&sub=fourn&action=list&user_input=";
	$bouton_add = "<input class='bouton' type='button' value=' ".$msg[acquisition_ajout_fourn]." ' onclick=\"document.location='./acquisition.php?categ=ach&sub=fourn&action=add&id_bibli=".$id_bibli."';\" />";
	$lien_last_fou = "";
	$search_form = str_replace('!!form_title!!', $titre, $search_form);
	$search_form = str_replace('!!action!!', $action, $search_form);
	$search_form = str_replace('<!-- bouton_add -->', $bouton_add, $search_form);
	$search_form = str_replace('<!-- lien_last -->', $lien_last_fou, $search_form);
	
	print $search_form;



	//Prise en compte du formulaire de recherche
	// nombre de références par pages
	if ($nb_per_page_acq != "") 
		$nb_per_page = $nb_per_page_acq ;
	else $nb_per_page = 10;
	
	
	// traitement de la saisie utilisateur

	require_once($class_path."/analyse_query.class.php");


	// on récupére le nombre de lignes qui vont bien
	if(!$nbr_lignes) {

		if(!$user_input) {
			$nbr_lignes = entites::getNbFournisseurs($id_bibli);
		} else {
			$aq=new analyse_query(stripslashes($user_input),0,0,0,0);
			if ($aq->error) {
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				exit;
			}
			$nbr_lignes = entites::getNbFournisseurs($id_bibli, $aq);
		}

	} else {
		$aq=new analyse_query(stripslashes($user_input),0,0,0,0);
	}

	
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;


	if($nbr_lignes) {
	
		$url_base = "$PHP_SELF?categ=ach&sub=fourn&action=list&id_bibli=".$id_bibli."&user_input=".rawurlencode(stripslashes($user_input)) ;
		
		// on lance la vraie requête
		if(!$user_input) {
			$res = entites::list_fournisseurs($id_bibli, $debut, $nb_per_page);
		} else {
			$res = entites::list_fournisseurs($id_bibli, $debut, $nb_per_page, $aq);
		}

		//Affichage liste des fournisseurs
		print "<table>";
	
		$nbr = mysql_num_rows($res);
	
		$parity=1;
		for($i=0;$i<$nbr;$i++) {
			$row=mysql_fetch_object($res);
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" onmousedown=\"document.location='./acquisition.php?categ=ach&sub=fourn&action=modif&id_bibli=".$id_bibli."&id=".$row->id_entite."' \" ";
	        print ("<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer' >
						<td><i>".htmlentities($row->raison_sociale, ENT_QUOTES, $charset)."</i></td>
						<td class='right' ><input class='bouton' type='button' value='".$msg['acquisition_cond_fourn']."' onClick=\"document.location='./acquisition.php?categ=ach&sub=fourn&action=cond&id_bibli=".$id_bibli."&id=".$row->id_entite."' \" /></td>
					</tr>");
		}
		print "</table>";

	
		if (!$last_param) $nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page) ;
	        else $nav_bar = "";
	    print $nav_bar;
			
	
	} else {
		// la requête n'a produit aucun résultat
		error_message($msg['acquisition_fou_rech'], str_replace('!!fou_cle!!', stripslashes($user_input), $msg['acquisition_fou_rech_error']), 0, './categ=ach&sub=fourn&action=list&id_bibli='.$id_bibli);
	}

}


//Affiche le formulaire d'edition d'un fournisseur
function show_coord_form($id_bibli, $id_fou= 0) {
	
	global $msg;
	global $charset;
	global $coord_form2;
	global $ptab, $script;
	global $pmb_gestion_devise;
	
	$bibli = new entites($id_bibli);

	$ptab[1] = $ptab[1].$ptab[11];
	$ptab[1] = str_replace('!!adresse!!', htmlentities($msg[acquisition_adr_fou], ENT_QUOTES, $charset), $ptab[1]);
	$coord_form2 = str_replace('!!id!!', $id_fou, $coord_form2);
	$coord_form2 = str_replace('!!id_bibli!!', $id_bibli, $coord_form2);
	$coord_form2 = str_replace('!!lib_bibli!!', htmlentities($bibli->raison_sociale, ENT_QUOTES, $charset), $coord_form2);	

	$ptab[3] = str_replace('!!id!!', $id_fou, $ptab[3]);

	if(!$id_fou) {
		$coord_form2 = str_replace('!!form_title!!', htmlentities($msg[acquisition_ajout_fourn],ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!raison_suppr!!', '', $coord_form2);
		$coord_form2 = str_replace('!!raison!!', '', $coord_form2);
		$coord_form2 = str_replace('!!num_cp!!', '', $coord_form2);
		
		$coord_form2 = str_replace('!!contact!!', $ptab[1], $coord_form2);
		$coord_form2 = str_replace('!!max_coord!!', '1', $coord_form2);
		
		$coord_form2 = str_replace('!!lib_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!id1!!', '0', $coord_form2);
		$coord_form2 = str_replace('!!cta_1!!', '', $coord_form2);		
		$coord_form2 = str_replace('!!ad1_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!ad2_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!cpo_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!vil_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!eta_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!pay_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!te1_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!te2_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!fax_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!ema_1!!', '', $coord_form2);
		$coord_form2 = str_replace('!!com_1!!', '', $coord_form2);
		
		$coord_form2 = str_replace('!!commentaires!!', '', $coord_form2);
		$coord_form2 = str_replace('!!siret!!', '', $coord_form2);
		$coord_form2 = str_replace('!!rcs!!', '', $coord_form2);
		$coord_form2 = str_replace('!!naf!!', '', $coord_form2);
		$coord_form2 = str_replace('!!tva!!', '', $coord_form2);
		$coord_form2 = str_replace('!!site_web!!', '', $coord_form2);


	} else {
		
		$fourn = new entites($id_fou);
		$coord_form2 = str_replace('!!form_title!!', htmlentities($msg[acquisition_modif_fourn],ENT_QUOTES,$charset), $coord_form2);
		
		$coord_form2 = str_replace('!!raison!!', htmlentities($fourn->raison_sociale,ENT_QUOTES, $charset), $coord_form2);
		$coord_form2 = str_replace('!!num_cp!!',htmlentities($fourn->num_cp_client, ENT_QUOTES, $charset), $coord_form2);
		$coord_form2 = str_replace('!!contact!!', $ptab[1], $coord_form2);

		$row = mysql_fetch_object($fourn->get_coordonnees($fourn->id_entite,'1'));
		$coord_form2 = str_replace('!!id1!!', $row->id_contact, $coord_form2);
		$coord_form2 = str_replace('!!lib_1!!', htmlentities($row->libelle,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!cta_1!!', htmlentities($row->contact,ENT_QUOTES,$charset), $coord_form2);		
		$coord_form2 = str_replace('!!ad1_1!!', htmlentities($row->adr1,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!ad2_1!!', htmlentities($row->adr2,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!cpo_1!!', htmlentities($row->cp,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!vil_1!!', htmlentities($row->ville,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!eta_1!!', htmlentities($row->etat,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!pay_1!!', htmlentities($row->pays,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!te1_1!!', htmlentities($row->tel1,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!te2_1!!', htmlentities($row->tel2,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!fax_1!!', htmlentities($row->fax,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!ema_1!!', htmlentities($row->email,ENT_QUOTES,$charset), $coord_form2);
		$coord_form2 = str_replace('!!com_1!!', htmlentities($row->commentaires,ENT_QUOTES,$charset), $coord_form2);
	
		$liste_coord = $fourn->get_coordonnees($fourn->id_entite,'0');
		$coord_form2 = str_replace('!!max_coord!!', (mysql_num_rows($liste_coord)+1), $coord_form2);
		$i=2;
		while ($row = mysql_fetch_object($liste_coord)) {
			
			$coord_form2 = str_replace('<!--coord_repetables-->', $ptab[2].'<!--coord_repetables-->', $coord_form2);
			$coord_form2 = str_replace('!!no_X!!', $i, $coord_form2);
			$i++;
			$coord_form2 = str_replace('!!idX!!', $row->id_contact, $coord_form2);
			$coord_form2 = str_replace('!!lib_X!!', htmlentities($row->libelle,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!cta_X!!', htmlentities($row->contact,ENT_QUOTES,$charset), $coord_form2);		
			$coord_form2 = str_replace('!!ad1_X!!', htmlentities($row->adr1,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!ad2_X!!', htmlentities($row->adr2,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!cpo_X!!', htmlentities($row->cp,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!vil_X!!', htmlentities($row->ville,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!eta_X!!', htmlentities($row->etat,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!pay_X!!', htmlentities($row->pays,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!te1_X!!', htmlentities($row->tel1,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!te2_X!!', htmlentities($row->tel2,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!fax_X!!', htmlentities($row->fax,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!ema_X!!', htmlentities($row->email,ENT_QUOTES,$charset), $coord_form2);
			$coord_form2 = str_replace('!!com_X!!', htmlentities($row->commentaires,ENT_QUOTES,$charset), $coord_form2);				
		 
		}
								
		$coord_form2 = str_replace('!!commentaires!!', htmlentities($fourn->commentaires,ENT_QUOTES, $charset), $coord_form2);
		$coord_form2 = str_replace('!!siret!!', htmlentities($fourn->siret,ENT_QUOTES, $charset), $coord_form2);
		$coord_form2 = str_replace('!!rcs!!', htmlentities($fourn->rcs,ENT_QUOTES, $charset), $coord_form2);
		$coord_form2 = str_replace('!!naf!!', htmlentities($fourn->naf,ENT_QUOTES, $charset), $coord_form2);
		$coord_form2 = str_replace('!!tva!!', htmlentities($fourn->tva,ENT_QUOTES, $charset), $coord_form2);
		$coord_form2 = str_replace('!!site_web!!', htmlentities($fourn->site_web,ENT_QUOTES, $charset), $coord_form2);
		
		$coord_form2 = str_replace('<!-- bouton_sup -->', $ptab[3], $coord_form2); 
		$coord_form2 = str_replace('!!raison_suppr!!', addslashes($fourn->raison_sociale), $coord_form2);

	}

	print confirmation_delete("./acquisition.php?categ=ach&sub=fourn&action=del&id_bibli=$id_bibli&id=");
	print $script;
	print $coord_form2;
	
}


//Affiche la liste des conditions fournisseurs 
function show_list_cond($id_bibli, $id_fou) {
	
	global $msg;
	global $charset;
	global $cond_form;
	global $pmb_gestion_devise;
	global $frame, $bt_add, $ptab;

	$bibli = new entites($id_bibli);
	
	$cond_form = str_replace('!!id!!', $id_fou, $cond_form);
	$cond_form = str_replace('!!id_bibli!!', $id_bibli, $cond_form);
	$cond_form = str_replace('!!lib_bibli!!', htmlentities($bibli->raison_sociale, ENT_QUOTES, $charset), $cond_form);	

	$fourn = new entites($id_fou);
	$cond_form = str_replace('!!raison!!', htmlentities($fourn->raison_sociale ,ENT_QUOTES,$charset), $cond_form);
	
	$cond_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_cond_fourn],ENT_QUOTES,$charset), $cond_form);

	$cond_form = str_replace('<!-- bouton_sup -->', $ptab[3], $cond_form); 
	$cond_form = str_replace('!!raison_suppr!!', htmlentities($fourn->raison_sociale,ENT_QUOTES, $charset), $cond_form);

	
	//Conditions de paiement
	$list_paie = paiements::listPaiements();
	$form_paie = "<select name='paiement' id='paiement'>";
	$form_paie.= "<option value='0' ";
	if (!$id_fou || !$fourn->num_paiement) $form_paie.= "selected='selected' ";
	$form_paie.= ">".htmlentities($msg['acquisition_fou_select'], ENT_QUOTES, $charset)."</option>";
	while ($row = mysql_fetch_object($list_paie)) {
		$form_paie.="<option value='".$row->id_paiement."' ";
		if ($fourn->num_paiement == $row->id_paiement) $form_paie.="selected='selected' ";
		$form_paie.= ">".htmlentities($row->libelle, ENT_QUOTES, $charset)."</option>";
	}
	$form_paie.= "</select>";
	$cond_form = str_replace('<!-- paiements -->', $form_paie, $cond_form);
	
	
	//offres de remises par types de produits
	$list_cond = entites::listOffres($id_fou);
	$list_no_cond = entites::listNoOffres($id_fou);
	
	// affichage des offres déjà saisies

	$lig = "";
	$i = 1;
	$parity=1;
	while($row=mysql_fetch_object($list_cond)){
		
		
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
		$dn_javascript=" onmousedown=\"document.forms['condform'].setAttribute('action', './acquisition.php?categ=ach&sub=fourn&action=modrem&id_bibli=".$id_bibli."&id=".$id_fou."&id_prod=".$row->id_produit."'); document.forms['condform'].submit(); \" ";
        $lig.= "<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer' title='".htmlentities($row->condition_remise, ENT_QUOTES, $charset)."'>
					<td ".$dn_javascript.">".htmlentities($row->libelle, ENT_QUOTES, $charset)."</td>
					<td ".$dn_javascript." >
						<input type='hidden' id='idprod[".$i."]' name='idprod[".$i."]' value='".$row->id_produit."' />
						".$row->remise."&nbsp;%
					</td>
				</tr>";

	}


	$frame = str_replace('<!-- frames_rows -->', $lig, $frame);
	
	//Affichage bouton ajout remise
	if (mysql_num_rows($list_no_cond) != '0') {
		$cond_form = str_replace('<!-- bt_add -->', $bt_add, $cond_form);
	}
	$cond_form = str_replace('<!-- frame -->', $frame , $cond_form);

	print $cond_form;
	
}


//Affiche le formulaire de remise par type de produits 
function show_rem_form($id_bibli, $id_fou, $id_prod) {
	
	global $msg;
	global $charset;
	global $rem_form, $bt_sup;
	
	$bibli = new entites($id_bibli);
	
	$fou = new entites($id_fou);
	$rem_form = str_replace('!!raison!!', htmlentities($fou->raison_sociale, ENT_QUOTES, $charset), $rem_form);
	
	if(!$id_prod) {	
		$id_prod = 0;
		$rem_form = str_replace('!!form_title!!', htmlentities($msg['acquisition_rem_add'], ENT_QUOTES, $charset), $rem_form);

		//Produits non remisés pour le selecteur
		$list_no_cond = entites::listNoOffres($id_fou);
		$sel_prod = "<select name='sel_prod' id='sel_prod'>";
		while ($row = mysql_fetch_object($list_no_cond)) {
			$sel_prod.="<option value='".$row->id_produit."' >".htmlentities($row->libelle, ENT_QUOTES, $charset)."</option>";
		}
		$sel_prod.= "</select>";
		$rem_form = str_replace('!!lib_prod!!', $sel_prod, $rem_form);

		$rem_form = str_replace('!!rem!!', '0.00', $rem_form);
		$rem_form = str_replace('!!commentaires!!', '', $rem_form);
		
		$rem_form = str_replace('!!bouton_sup!!', '', $rem_form);
		
	} else {
		
		$typ= new types_produits($id_prod);
		$rem_form = str_replace('!!form_title!!', htmlentities($msg['acquisition_rem_mod'], ENT_QUOTES, $charset), $rem_form);
		$rem_form = str_replace('!!lib_prod!!', htmlentities($typ->libelle, ENT_QUOTES, $charset), $rem_form);
		
		$offre = new offres_remises($id_fou, $id_prod);
		$rem_form = str_replace('!!rem!!', number_format($offre->remise, 2,'.','' ), $rem_form);
		$rem_form = str_replace('!!commentaires!!', htmlentities($offre->condition_remise, ENT_QUOTES, $charset), $rem_form);

		$rem_form = str_replace('!!bouton_sup!!', $bt_sup, $rem_form);

		
	}
	$rem_form = str_replace('!!id_fou!!', $id_fou, $rem_form);
	$rem_form = str_replace('!!id_bibli!!', $id_bibli, $rem_form);
	$rem_form = str_replace('!!lib_bibli!!', htmlentities($bibli->raison_sociale, ENT_QUOTES, $charset), $rem_form);	
	$rem_form = str_replace('!!id_prod!!', $id_prod, $rem_form);
	
	print $rem_form;
	
}




//Traitement des actions
print "<h1>".htmlentities($msg['acquisition_ach_ges'],ENT_QUOTES, $charset)."&nbsp;:&nbsp;".htmlentities($msg['acquisition_ach_fou'],ENT_QUOTES, $charset)."</h1>";

switch($action) {


	case 'list':
		entites::setSessionBibliId($id_bibli);
		show_list_coord($id_bibli);
		break;


	case 'update':
		
		// vérification validité des données fournies.
		$nbr = entites::exists_rs($raison,$id_bibli,$id);
		if ($nbr > 0) {
			error_form_message($raison.$msg["acquisition_raison_already_used"]);
			break;
		} 

		$fourn = new entites($id);
		$fourn->type_entite = '0';
		$fourn->num_bibli = $id_bibli;
		$fourn->raison_sociale = $raison;
		$fourn->num_cp_client = $num_cp;
		$fourn->commentaires = $comment;
		$fourn->siret = $siret;
		$fourn->naf = $naf;
		$fourn->rcs = $rcs;
		$fourn->tva = $tva;
		$fourn->site_web = $site_web;
		$fourn->save();
		$id = $fourn->id_entite;
		
		for($i=1; $i <= $max_coord; $i++) {
			switch ($mod_[$i]) {
				case '1' :
					$coord = new coordonnees($no_[$i]); 
					$coord->num_entite = $id;
					if ($i == 1) $coord->type_coord = $i; else $coord->type_coord = 0;
					$coord->libelle = $lib_[$i];
					$coord->contact = $cta_[$i];
					$coord->adr1 = $ad1_[$i];
					$coord->adr2 = $ad2_[$i];
					$coord->cp = $cpo_[$i];
					$coord->ville = $vil_[$i];
					$coord->etat = $eta_[$i];
					$coord->pays = $pay_[$i];
					$coord->tel1 = $te1_[$i];
					$coord->tel2 = $te2_[$i];
					$coord->fax = $fax_[$i];
					$coord->email = $ema_[$i];
					$coord->save();
					break;
					
				case '-1' :
					if($no_[$i]) {
						$coord = new coordonnees($no_[$i]);
						$coord->delete($no_[$i]);
					}
					break;
					
				default :
					break;
				
			}
			
		} 
		
		show_list_coord($id_bibli);
		break;

		
	case 'add':
		show_coord_form($id_bibli);
		break;

		
	case 'modif':
		if (entites::exists($id)) {
			show_coord_form($id_bibli, $id);
		} else {
			show_list_coord($id_bibli);
		}
		break;

		
	case 'del':
		if($id) {
			$total7 = entites::has_actes($id);
			if (($total7)==0) {
				entites::delete($id);
				show_list_coord($id_bibli);
			} else {
				$msg_suppr_err = $msg[acquisition_fou_used] ;
				if ($total7) $msg_suppr_err .= "<br />- ".$msg[acquisition_fou_used_act] ;		
				
				error_message($msg[321], $msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=entite');
			}
		} else show_list_coord($id_bibli);
		break;


	case 'cond':
		show_list_cond($id_bibli, $id);
		break;

	case 'updatecond':
		$fourn = new entites($id);
		$fourn->num_paiement = $paiement;
		$fourn->save(); 		
		show_list_coord($id_bibli);
		break;


	case 'modrem':
		$fourn = new entites($id);
		$fourn->num_paiement = $paiement;
		$fourn->raison_sociale = $fourn->raison_sociale;
		$fourn->commentaires = $fourn->commentaires;
		$fourn->siret = $fourn->siret;
		$fourn->naf = $fourn->naf;
		$fourn->rcs = $fourn->rcs;
		$fourn->tva = $fourn->tva;
		$fourn->site_web = $fourn->site_web;
		$fourn->save(); 		
		show_rem_form($id_bibli, $id, $id_prod);	
		break;	


	case 'updaterem':
		$rem = str_replace(',','.',$rem);
		if( (!is_numeric($rem)) || ($rem < 0) || ($rem >= 100) ) {
			error_form_message($msg['acquisition_rem_err']);
			break;
		}
		if (!$id_prod) $id_prod = $sel_prod;
		if ($id_prod) {
			$offre = new offres_remises($id, $id_prod);
			$offre->remise = $rem;
			$offre->condition_remise = $comment;
			$offre->save();
		}	
		show_list_cond($id_bibli, $id);	
		break;

		
	case 'deleterem':
		offres_remises::delete($id, $id_prod);
		show_list_cond($id_bibli, $id);
		break;
	
	default:
		if (entites::exists($id_bibli)) show_list_coord($id_bibli);
			else show_list_biblio();
		break;
}

?>
