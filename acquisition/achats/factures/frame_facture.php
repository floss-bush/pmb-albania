<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: frame_facture.php,v 1.25 2009-06-03 06:06:45 dbellamy Exp $

//Liste les lignes d'une facture
$base_path="../../..";                            
//$base_auth = "ACQUISITION_AUTH";  
$include_path = "$base_path/includes";
$class_path = "$base_path/classes";

$current_alert="acquisition";
require_once("$include_path/init.inc.php");

// gestion des lignes de facture
require_once("$class_path/entites.class.php");
require_once("$class_path/types_produits.class.php");
require_once("$class_path/budgets.class.php");
require_once("$class_path/rubriques.class.php");
require_once("$class_path/tva_achats.class.php");
require_once("$class_path/actes.class.php");
require_once("$class_path/lignes_actes.class.php");
require_once("$class_path/liens_actes.class.php");
require_once("$include_path/isbn.inc.php");
require_once("$include_path/misc.inc.php");
require_once("$include_path/templates/factures.tpl.php");


$error = false;
$error_msg = '';

$focus = 0;

//verification du formulaire
function verif_fac() {

	global $msg;
	global $max_lig, $max_lig_fac;
	global $prix, $tva, $rem, $rub, $sol, $fac;
	global $error, $error_msg;
	global $acquisition_gestion_tva, $acquisition_budget;
	global $action;
	

	//Si pas de lignes sur la facture >> Avertissement utilisateur et retour
	$qte_fac = 0;
	for($i='1'; $i<=$max_lig; $i++) {
		$qte_fac = $qte_fac + $fac[$i]; 
	}

	if ( ($action == 'update') && ($max_lig_fac == 0) && ($qte_fac == 0)) {
		$error = true;
		$error_msg = $msg['acquisition_fac_vid']; 
		return;
	}


	for($i='1'; $i<=$max_lig; $i++) {
		
		//Vérification prix
		$prix[$i] = str_replace(',','.',$prix[$i]);
		if (!is_numeric($prix[$i]) || $prix[$i] < '0' || $prix[$i] > '999999.99' ) {
			$error = true;
			$error_msg = $msg['acquisition_lig'].' '.$i.': '.$msg['acquisition_prix_fac_inv'];
			break;
		}	

		//Vérification tva
		if ($acquisition_gestion_tva) {
			//Vérification tva
			$tva[$i] = str_replace(',','.',$tva[$i]);
			if (!is_numeric($tva[$i]) || $tva[$i] < '0' || $tva[$i] > '99.99' ) {
				$error = true;
				$error_msg = $msg['acquisition_lig'].' '.$i.': '.$msg['acquisition_tva_fac_inv'];
				break;
			}	
		}				
		
		//Vérification remise
		$rem[$i] = str_replace(',','.',$rem[$i]);
		if (!is_numeric($rem[$i]) || $rem[$i] < '0' || $rem[$i] > '99.99' ) {
			$error = true;
			$error_msg = $msg['acquisition_lig'].' '.$i.': '.$msg['acquisition_rem_fac_inv'];
			break;
		}	

		 
		//Vérification quantité facturée
		if (!is_numeric($fac[$i]) || $fac[$i] < '0' || $fac[$i] > '99999' ) {
			$error = true;
			$error_msg = $msg['acquisition_lig'].' '.$i.': '.$msg['acquisition_qte_fac_inv'];
			break;
		}
		if ($fac[$i] > $sol[$i])	{
			$error = true;
			$error_msg = $msg['acquisition_lig'].' '.$i.': '.$msg['acquisition_qte_fac_sup'];
			break;
		}

		//Vérification saisie Budget
		if ($acquisition_budget && !$rub[$i]) {
			$error = true;
			$error_msg = $msg['acquisition_lig'].' '.$i.': '.$msg['acquisition_act_bud_err'];
			break;			
		}

	}

}


//Vérification dépassement de budget
function verif_bud() {

	global $msg, $charset;
	global $max_lig;
	global $prix, $rem, $rub, $fac;
	global $error, $error_msg;
	global $acquisition_budget;

	if ($acquisition_budget) {

		$tot_rub = array();
		$tot_bud = array();
		for ($i=1;$i<=$max_lig;$i++) {
			if ($fac[$i]) $tot_rub[$rub[$i]] = 0;
		}

		//récupère le total facturé par rubrique
		for ($i=1;$i<=$max_lig;$i++) {
			if ($fac[$i]) $tot_rub[$rub[$i]] = $tot_rub[$rub[$i]] + ( $fac[$i]*$prix[$i]*(1 - ($rem[$i]/100) ) );
		}

		//récupère le total facturé par budget
		foreach ($tot_rub as $key=>$value) {
			$r = new rubriques($key);
			if (!array_key_exists($r->num_budget, $tot_bud)) $tot_bud[$r->num_budget] = 0; 
			$tot_bud[$r->num_budget] = $tot_bud[$r->num_budget] + $value; 
		}

		//Vérifie que les budgets affectés par rubrique ne sont pas dépassés		
		foreach ($tot_rub as $key=>$value) {
		
			$r = new rubriques($key);
			$b = new budgets($r->num_budget);
				
			if ( $b->type_budget == TYP_BUD_RUB ) {	//Budget affecté par rubrique
				
				$mnt_rub = $r->montant;
				$eng_rub = rubriques::calcEngagement($key) + $value;
				//Budget rubrique dépassé ?
				if ($eng_rub > $mnt_rub) {
					$error = true;
					
					$tab_rub = rubriques::listAncetres($key, true);
					
					$lib_rub = $b->libelle.":";
					foreach ($tab_rub as $value) {
						$lib_rub.= $value[1];
						if($value[0] != $key) $lib_rub.= ":";
					}				
					
					$error_msg = $msg['acquisition_rub']." :\\n\\n".$lib_rub."\\n\\n".$msg['acquisition_act_bud_dep'];
					break;			
				}
			}
		}
			
		//Vérifie que les budgets affectés globalement ne sont pas dépassés
		foreach ($tot_bud as $key=>$value) {

			$b = new budgets($key);
			
			if ( $b->type_budget == TYP_BUD_GLO ) {			
				
				$mnt_bud = $b->montant_global;
				$eng_bud = budgets::calcEngagement($b->id_budget) + $value;
		
				//Budget dépassé ?
				if ($eng_bud > $mnt_bud) {
					$error = true;
					$error_msg = $msg['acquisition_act_tab_bud']." : ".$b->libelle."\\n\\n".$msg['acquisition_act_bud_dep'];
					break;			
				}
			}
		}	
	}
}


//Affichage création facture depuis commande
function show_lig_from_cde() {
	
	global $msg, $charset;
	global $frame_modif, $frame_row, $frame_row_bl_header, $frame_row_bl, $bt_sup_lig;
	global $select_typ;
	global $id_bibli, $id_cde, $id_fac;
	global $focus;
	global $error, $error_msg;
	global $acquisition_gestion_tva;
	
	$frame = $frame_modif;
	
	//Recherche du solde à livrer sur la commande
	$cde = new actes($id_cde);	
	$lignes_cde = actes::getLignes($id_cde);

	$nb_lig = 0;
	
	while (($row_cde = mysql_fetch_object($lignes_cde))) {
		
		
		//recherche des lignes de facture
		$lignes_fac = lignes_actes::getFactures($row_cde->id_ligne);
		$nb_fac = 0;
		while (($row_fac = mysql_fetch_object($lignes_fac))) {
			$nb_fac = $nb_fac + $row_fac->nb;
		}

		$sol = $row_cde->nb - $nb_fac;
		

		//affichage ligne si solde à facturer >0 
		if ($sol) {

			$nb_lig++;
			$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);						
			$frame = str_replace('<!-- select_typ -->', $select_typ[0], $frame);
			$frame = str_replace('<!-- select_bud -->', select_rub($id_cde, $row_cde->num_rubrique), $frame);		
			$frame = str_replace('!!no!!', $nb_lig, $frame);
			$frame = str_replace('!!id_lig!!', $row_cde->id_ligne, $frame);
			$frame = str_replace('!!id_prod!!', $row_cde->num_produit, $frame);
			$frame = str_replace('!!code!!', htmlentities($row_cde->code, ENT_QUOTES, $charset), $frame);
			$frame = str_replace('!!lib!!', htmlentities($row_cde->libelle, ENT_QUOTES, $charset), $frame);
			$frame = str_replace('!!prix!!', number_format($row_cde->prix,2,'.',''), $frame);
			$frame = str_replace('!!sol!!', $sol, $frame);	
			$frame = str_replace('!!fac!!', '0', $frame);
			
			$tp = new types_produits($row_cde->num_type);
			$frame = str_replace('!!typ!!', $tp->id_produit, $frame);
			$frame = str_replace('!!lib_typ!!', $tp->libelle, $frame);

			$frame = str_replace('!!rem!!', number_format($row_cde->remise,2,'.','') , $frame);
		
			//affichage des taux de tva			
			if ($acquisition_gestion_tva) {
				$frame = str_replace('!!tva!!', number_format($row_cde->tva,2,'.',''), $frame);						
			} else {
				$frame = str_replace('!!tva!!', '', $frame);					
			}		
		}	

	}
	$frame = str_replace('!!tot_ht!!', '0.00', $frame);
	$frame = str_replace('!!tot_tva!!', '0.00', $frame);
	$frame = str_replace('!!tot_ttc!!', '0.00', $frame);

	$frame = str_replace('!!max_lig!!', $nb_lig, $frame);	
	$frame = str_replace('!!max_lig_fac!!', '0', $frame);

	$frame = str_replace('!!id_fac!!', '0', $frame);
	$frame = str_replace('!!id_fou!!', $cde->num_fournisseur, $frame);

	print $frame; 	
	
}  


//Affichage des lignes de facture
function show_lig_fac() {
	
	global $msg, $charset;
	global $frame_modif, $frame_row, $frame_row_fa_header, $frame_row_fa, $frame_row_fa_arc, $bt_sup_lig, $no_bt_sup_lig;
	global $select_typ, $select_rub;
	global $id_bibli, $id_cde, $id_fac;
	global $focus;
	global $error, $error_msg;
	global $acquisition_gestion_tva;
	
	$tot_ht = 0;
	$tot_tva = 0;
	$tot_ttc = 0;
	$frame = $frame_modif;
	
	//Lecture des éléments de la commande
	$cde = new actes($id_cde);	
	$lignes_cde = actes::getLignes($id_cde);

	//Lecture de la facture
	$factu = new actes($id_fac);


	if( (($factu->statut & STA_ACT_PAY) == STA_ACT_PAY) || (($factu->statut & STA_ACT_ARC) == STA_ACT_ARC) ) { 	//La facture est payée ou archivée,  donc non modifiable

		$nb_lig = 0;

		//affichage du déjà facturé sur la facture courante
		$lignes_fac = actes::getLignes($id_fac);
		$max_lig_fac = mysql_num_rows($lignes_fac);
		$frame = str_replace('!!max_lig_fac!!', $max_lig_fac, $frame);
		$frame = str_replace('!!max_lig!!', '0', $frame);
		while (($row_fac = mysql_fetch_object($lignes_fac))) {
			$nb_lig++;
			$frame = str_replace('<!-- lignes -->', $frame_row_fa_arc.'<!-- lignes -->', $frame);
			$frame = str_replace('<!-- select_typ -->', $select_typ[1], $frame);
			$frame = str_replace('<!-- select_bud -->', $select_rub[1], $frame);		
			$frame = str_replace('!!no!!', $nb_lig, $frame);
			$frame = str_replace('!!code!!', htmlentities($row_fac->code, ENT_QUOTES, $charset), $frame);
			$frame = str_replace('!!lib!!', htmlentities($row_fac->libelle, ENT_QUOTES, $charset), $frame);	
			$frame = str_replace('!!prix!!', number_format($row_fac->prix,2,'.',''), $frame);

			$tp = new types_produits($row_fac->num_type);
			$frame = str_replace('!!typ!!', $tp->id_produit, $frame);
			$frame = str_replace('!!lib_typ!!', htmlentities($tp->libelle, ENT_QUOTES, $charset), $frame);		
			
			$id_rub = $row_fac->num_rubrique;
			if (!$id_rub) {
				$id_rub = 0;
				$lib_rub = '';
			} else {
				$rub = new rubriques($id_rub);
				$bud = new budgets($rub->num_budget);
				$lib_bud = $bud->libelle;
				
				$tab_rub = rubriques::listAncetres($id_rub, true);
				$lib_rub = $lib_bud.':';
				foreach ($tab_rub as $value) {
					$lib_rub.= htmlentities($value[1], ENT_QUOTES, $charset);
					if($value[0] != $id_rub) $lib_rub.= ":";
				}
			}
			$frame = str_replace('!!id_rub!!',$id_rub , $frame);
			$frame = str_replace('!!lib_rub!!', $lib_rub, $frame);
			$frame = str_replace('!!fac!!', $row_fac->nb, $frame);
			
			
			$frame = str_replace('!!rem!!', number_format($row_fac->remise,2,'.','') , $frame);		
			
			//calcul des montants ht, ttc, tva			
			if ($acquisition_gestion_tva) {
				$lig_ht = $row_fac->nb * $row_fac->prix * (1-($row_fac->remise/100)) ;
				$tot_ht = $tot_ht + $lig_ht;
				$tot_tva = $tot_tva + ($lig_ht*($row_fac->tva/100) );
				$tot_ttc = $tot_ht + $tot_tva;						
				$frame = str_replace('!!tva!!', number_format($row_fac->tva,2,',',''), $frame);						
			} else {
				$lig_ttc = $row_fac->nb * $row_fac->prix * (1-($row_fac->remise/100)) ;
				$tot_ttc = $tot_ttc + $lig_ttc;
				$frame = str_replace('!!tva!!', '', $frame);											
			}		
			
		}

		$frame = str_replace('!!tot_ht!!', number_format(round($tot_ht,2),2,'.',''), $frame);
		$frame = str_replace('!!tot_tva!!', number_format(round($tot_tva,2),2,'.',''), $frame);
		$frame = str_replace('!!tot_ttc!!', number_format(round($tot_ttc,2),2,'.',''), $frame);
		$frame = str_replace('!!id_fac!!', $id_fac, $frame);
		$frame = str_replace('!!id_fou!!', $cde->num_fournisseur, $frame);
		
		//Affichage du bouton de suppression de lignes
		$frame = str_replace('<!-- bouton_sup_lig -->', $no_bt_sup_lig, $frame);			
		
		print $frame; 	
	
	} else {	//la facture est modifiable
		

		if( ($factu->statut & STA_ACT_PAY) != STA_ACT_PAY ) { 	//La facture n'est pas payée, on affiche les lignes restant à facturer

			$nb_lig = 0;
			
			while (($row_cde = mysql_fetch_object($lignes_cde))) {
				
				//recherche des lignes de Facture
				$lignes_fac = lignes_actes::getFactures($row_cde->id_ligne);
				$sol = $row_cde->nb;
				while (($row_fac = mysql_fetch_object($lignes_fac))) {
					$sol = $sol - $row_fac->nb;
				}
				$fac = 0;
		
				//affichage ligne si solde à facturer >0
				if ($sol) {
		
					$nb_lig++;
					$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);						
					$frame = str_replace('<!-- select_typ -->', $select_typ[0], $frame);
					$frame = str_replace('<!-- select_bud -->', select_rub($id_cde, $row_cde->num_rubrique), $frame);	
					$frame = str_replace('!!no!!', $nb_lig, $frame);
					$frame = str_replace('!!id_lig!!', $row_cde->id_ligne, $frame);
					$frame = str_replace('!!id_prod!!', $row_cde->num_produit, $frame);
					$frame = str_replace('!!code!!', htmlentities($row_cde->code, ENT_QUOTES, $charset), $frame);
					$frame = str_replace('!!lib!!', htmlentities($row_cde->libelle, ENT_QUOTES, $charset), $frame);
					$frame = str_replace('!!prix!!', number_format($row_cde->prix,2,'.',''), $frame);
	
					$tp = new types_produits($row_cde->num_type);
					$frame = str_replace('!!typ!!', $tp->id_produit, $frame);
					$frame = str_replace('!!lib_typ!!', htmlentities($tp->libelle, ENT_QUOTES, $charset), $frame);
	
					$frame = str_replace('!!tva!!', number_format($row_cde->tva,2,'.',''), $frame);
					$frame = str_replace('!!rem!!', number_format($row_cde->remise,2,'.',''), $frame);
					$frame = str_replace('!!sol!!', $sol, $frame);
					$frame = str_replace('!!fac!!', $fac, $frame);
				}		
			}	
			$frame = str_replace('!!max_lig!!', $nb_lig, $frame);

		} else {

			$frame = str_replace('!!max_lig!!', 0, $frame);
			
		}	
		
		//affichage du déjà facturé sur la facture courante
		$frame = str_replace('<!-- lignes -->', $frame_row_fa_header.'<!-- lignes -->', $frame);
		$lignes_fac = actes::getLignes($id_fac);
		$max_lig_fac = mysql_num_rows($lignes_fac);
		$frame = str_replace('!!max_lig_fac!!', $max_lig_fac, $frame);
	
		while  (($row_fac = mysql_fetch_object($lignes_fac))) {
			$nb_lig++;
			$frame = str_replace('<!-- lignes -->', $frame_row_fa.'<!-- lignes -->', $frame);
			$frame = str_replace('<!-- select_typ -->', $select_typ[1], $frame);
			$frame = str_replace('<!-- select_bud -->', $select_rub[1], $frame);		
			$frame = str_replace('!!no!!', $nb_lig, $frame);
			$frame = str_replace('!!id_lig!!', $row_fac->lig_ref, $frame);
			$frame = str_replace('!!id_prod!!', $row_fac->num_produit, $frame);
			$frame = str_replace('!!code!!', htmlentities($row_fac->code, ENT_QUOTES, $charset), $frame);
			$frame = str_replace('!!lib!!', htmlentities($row_fac->libelle, ENT_QUOTES, $charset), $frame);	
			$frame = str_replace('!!prix!!', number_format($row_fac->prix,2,'.',''), $frame);

			$tp = new types_produits($row_fac->num_type);
			$frame = str_replace('!!typ!!', $tp->id_produit, $frame);
			$frame = str_replace('!!lib_typ!!', htmlentities($tp->libelle, ENT_QUOTES, $charset), $frame);		
			
			$id_rub = $row_fac->num_rubrique;
			if (!$id_rub) {
				$id_rub = 0;
				$lib_rub = '';
			} else {
				$rub = new rubriques($id_rub);
				$bud = new budgets($rub->num_budget);
				$lib_bud = $bud->libelle;
				
				$tab_rub = rubriques::listAncetres($id_rub, true);
				$lib_rub = $lib_bud.':';
				foreach ($tab_rub as $value) {
					$lib_rub.= htmlentities($value[1], ENT_QUOTES, $charset);
					if($value[0] != $id_rub) $lib_rub.= ":";
				}
			}
			$frame = str_replace('!!id_rub!!',$id_rub , $frame);
			$frame = str_replace('!!lib_rub!!', $lib_rub, $frame);
			$frame = str_replace('!!fac!!', $row_fac->nb, $frame);
			
			
			$frame = str_replace('!!rem!!', number_format($row_fac->remise,2,'.',''), $frame);		
			
			//calcul des montants ht, ttc, tva			
			if ($acquisition_gestion_tva) {
				$lig_ht = $row_fac->nb * $row_fac->prix * (1-($row_fac->remise/100)) ;
				$tot_ht = $tot_ht + $lig_ht;
				$tot_tva = $tot_tva + ($lig_ht*($row_fac->tva/100) );
				$tot_ttc = $tot_ht + $tot_tva;						
				$frame = str_replace('!!tva!!', number_format($row_fac->tva,2,'.',''), $frame);						
			} else {
				$lig_ttc = $row_fac->nb * $row_fac->prix * (1-($row_fac->remise/100)) ;
				$tot_ttc = $tot_ttc + $lig_ttc;
				$frame = str_replace('!!tva!!', '', $frame);											
			}		
			
		}

		$frame = str_replace('!!tot_ht!!', number_format(round($tot_ht,2),2,'.',''), $frame);
		$frame = str_replace('!!tot_tva!!', number_format(round($tot_tva,2),2,'.',''), $frame);
		$frame = str_replace('!!tot_ttc!!', number_format(round($tot_ttc,2),2,'.',''), $frame);
		$frame = str_replace('!!id_fac!!', $id_fac, $frame);
		$frame = str_replace('!!id_fou!!', $cde->num_fournisseur, $frame);
		
		//Affichage du bouton de suppression de lignes
		if (!$max_lig_fac) {
			$frame = str_replace('<!-- bouton_sup_lig -->', $no_bt_sup_lig, $frame);			
		} else {
			$frame = str_replace('<!-- bouton_sup_lig -->', $bt_sup_lig, $frame);
		}
		
		print $frame; 	
	}
	
}  


//Affichage des lignes si erreur
function show_lig_bak() {
	
	global $msg;
	global $frame_modif, $frame_row, $frame_row_fa_header, $frame_row_fa, $select_typ, $select_rub, $bt_sup_lig, $no_bt_sup_lig;
	global $id_bibli, $id_fou, $id_cde, $id_fac;
	global $max_lig, $max_lig_fac;
	global $id_lig, $id_prod, $code, $lib, $prix, $typ, $tva, $rem , $lib_typ, $rub, $lib_rub, $sol, $fac;
	global $error, $error_msg;
	global $acquisition_gestion_tva;

	$frame = $frame_modif;
	$cde = new actes($id_cde);
	
	$lig_afac = array(); //Tableau des lignes à facturer
	$lig_dfac = array(); //Tableau des lignes déjà facturées
	$focus = 0;
	
	//Les lignes restant à facturer sont reprises telles quelles
	for($i=1;$i<=$max_lig;$i++) {
		
		$lig_afac[$i]['id_lig'] = $id_lig[$i];
		$lig_afac[$i]['id_prod']=$id_prod[$i];
		$lig_afac[$i]['code']=stripslashes($code[$i]);
		$lig_afac[$i]['lib']= stripslashes($lib[$i]);
		$lig_afac[$i]['prix']= number_format(round($prix[$i],2),2,'.','');
		$lig_afac[$i]['typ']= $typ[$i];
		$lig_afac[$i]['tva']= number_format($tva[$i],2,'.','');
		$lig_afac[$i]['rem']= number_format(round($rem[$i],2),2,'.','');
		$lig_afac[$i]['lib_typ']= stripslashes($lib_typ[$i]);
		$lig_afac[$i]['rub']= $rub[$i];
		$lig_afac[$i]['lib_rub']= stripslashes($lib_rub[$i]);
		$lig_afac[$i]['sol']= $sol[$i];
		$lig_afac[$i]['fac']= $fac[$i];

	}
	
	//Les lignes déjà facturées sont reprises telles quelles
	for($i;$i<=$max_lig+$max_lig_fac;$i++) {
			
		$lig_dfac[$i]['id_lig'] = $id_lig[$i];
		$lig_dfac[$i]['id_prod']=$id_prod[$i];
		$lig_dfac[$i]['code']=stripslashes($code[$i]);
		$lig_dfac[$i]['lib']= stripslashes($lib[$i]);
		$lig_dfac[$i]['prix']= number_format(round($prix[$i],2),2,'.','');
		$lig_dfac[$i]['typ']= $typ[$i];
		$lig_dfac[$i]['tva']= number_format($tva[$i],2,'.','');
		$lig_dfac[$i]['rem']= number_format(round($rem[$i],2),2,'.','');
		$lig_dfac[$i]['lib_typ']= stripslashes($lib_typ[$i]);
		$lig_dfac[$i]['rub']= $rub[$i];
		$lig_dfac[$i]['lib_rub']= stripslashes($lib_rub[$i]);
		$lig_dfac[$i]['fac']= $fac[$i];
		
	}
	

	$index = 1;	
	$max_lig = count($lig_afac);
	//Affichage des lignes restant à facturer
	foreach($lig_afac as $key=>$value) {
				
		$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);
		$frame = str_replace('<!-- select_typ -->', $select_typ[0], $frame);
		$frame = str_replace('<!-- select_bud -->', $select_rub[0], $frame);
		$frame = str_replace('!!no!!', $index, $frame);
		$frame = str_replace('!!id_lig!!', $lig_afac[$key]['id_lig'], $frame);
		$frame = str_replace('!!id_prod!!', $lig_afac[$key]['id_prod'], $frame);
		$frame = str_replace('!!code!!', $lig_afac[$key]['code'], $frame);
		$frame = str_replace('!!lib!!', $lig_afac[$key]['lib'], $frame);
		$frame = str_replace('!!prix!!', $lig_afac[$key]['prix'], $frame);
		$frame = str_replace('!!typ!!', $lig_afac[$key]['typ'], $frame);
		$frame = str_replace('!!tva!!', $lig_afac[$key]['tva'], $frame);
		$frame = str_replace('!!rem!!', $lig_afac[$key]['rem'], $frame);
		$frame = str_replace('!!lib_typ!!', $lig_afac[$key]['lib_typ'], $frame);
		$frame = str_replace('!!id_rub!!', $lig_afac[$key]['rub'], $frame);
		$frame = str_replace('!!lib_rub!!', $lig_afac[$key]['lib_rub'], $frame);
		$frame = str_replace('!!sol!!', $lig_afac[$key]['sol'], $frame);
		$frame = str_replace('!!fac!!', $lig_afac[$key]['fac'], $frame);		
		$index++;			
		
	}
	$frame = str_replace('!!max_lig!!', $max_lig, $frame);
	
	$max_lig_fac = count($lig_dfac);
	if ($max_lig_fac) {

		$frame = str_replace('<!-- lignes -->', $frame_row_fa_header.'<!-- lignes -->', $frame);
	
		//Affichage des lignes déjà facturées
		foreach($lig_dfac as $key=>$value) {
			
			$frame = str_replace('<!-- lignes -->', $frame_row_fa.'<!-- lignes -->', $frame);
			$frame = str_replace('<!-- select_typ -->', $select_typ[1], $frame);
			$frame = str_replace('<!-- select_bud -->', $select_rub[1], $frame);
			$frame = str_replace('!!no!!', $index, $frame);
			$frame = str_replace('!!id_lig!!', $lig_dfac[$key]['id_lig'], $frame);
			$frame = str_replace('!!id_prod!!', $lig_dfac[$key]['id_prod'], $frame);
			$frame = str_replace('!!code!!', $lig_dfac[$key]['code'], $frame);
			$frame = str_replace('!!lib!!', $lig_dfac[$key]['lib'], $frame);
			$frame = str_replace('!!prix!!', $lig_dfac[$key]['prix'], $frame);
			$frame = str_replace('!!typ!!', $lig_dfac[$key]['typ'], $frame);
			$frame = str_replace('!!rem!!', $lig_dfac[$key]['rem'], $frame);
			$frame = str_replace('!!lib_typ!!', $lig_dfac[$key]['lib_typ'], $frame);
			$frame = str_replace('!!id_rub!!', $lig_dfac[$key]['rub'], $frame);
			$frame = str_replace('!!lib_rub!!', $lig_dfac[$key]['lib_rub'], $frame);
			$frame = str_replace('!!sol!!', '', $frame);
			$frame = str_replace('!!fac!!', $lig_dfac[$key]['fac'], $frame);		
			$index++;


			//calcul des montants ht, ttc, tva			
			if ($acquisition_gestion_tva) {
				$lig_ht = $lig_dfac[$key]['fac'] * $lig_dfac[$key]['prix'] * (1-($lig_dfac[$key]['rem']/100)) ;
				$tot_ht = $tot_ht + $lig_ht;
				$tot_tva = $tot_tva + ($lig_ht*($lig_dfac[$key]['tva']/100) );
				$tot_ttc = $tot_ht + $tot_tva;						
				$frame = str_replace('!!tva!!', $lig_dfac[$key]['tva'], $frame);
			} else {
				$lig_ttc = $lig_dfac[$key]['fac'] * $lig_dfac[$key]['prix'] * (1-($lig_dfac[$key]['rem']/100)) ;
				$tot_ttc = $tot_ttc + $lig_ttc;
				$frame = str_replace('!!tva!!', '', $frame);											
			}		
		}
	}
	$frame = str_replace('!!max_lig_fac!!', $max_lig_fac, $frame);

	$frame = str_replace('!!tot_ht!!', number_format(round($tot_ht,2),2,'.',''), $frame);
	$frame = str_replace('!!tot_tva!!', number_format(round($tot_tva,2),2,'.',''), $frame);
	$frame = str_replace('!!tot_ttc!!', number_format(round($tot_ttc,2),2,'.',''), $frame);

		
	if ($error) {
		$frame = str_replace('<!-- error -->', "<script type='text/javascript'>alert(\"".$error_msg."\"); </script>", $frame);
	}

	$frame = str_replace('!!id_fac!!', $id_fac, $frame);
	$frame = str_replace('!!id_fou!!', $cde->num_fournisseur, $frame);	

	print $frame; 	
	
}


//test et formatage du code saisi 
function test_cb() {
	
	global $cb;
	global $barcode;
	
	$isbn = '';
	$barcode = '';

	// on commence par voir ce que la saisie utilisateur est ($cb)
	$cb = clean_string($cb);				
	
	if(isEAN($cb)) {
		// la saisie est un EAN -> on tente de le formater en ISBN
		$isbn = EANtoISBN($cb);
		// si échec, on prend l'EAN comme il vient
		if(!$isbn) {
			$barcode = $cb;
		} else {
			$barcode=$isbn;
		}
	} else {
		if(isISBN($cb)) {
			// si la saisie est un ISBN
			$isbn = formatISBN($cb);
			// si échec, ISBN erroné on le prend sous cette forme
			if(!$isbn) $barcode = $cb;
				else $barcode=$isbn ;
		} else {
			// ce n'est rien de tout ça, on prend la saisie telle quelle
			$barcode = $cb;
		}
	}
}


//Recherche dans les lignes de commandes après saisie code barre
function search_lig_fac() {
	
	global $msg;
	global $frame_modif, $frame_row, $frame_row_fa_header, $frame_row_fa, $select_typ, $select_rub, $bt_sup_lig, $no_bt_sup_lig;
	global $id_bibli, $id_cde, $id_fac;
	global $max_lig, $max_lig_fac;
	global $id_lig, $id_prod, $code, $lib, $prix, $typ, $tva, $rem, $lib_typ, $rub, $lib_rub, $sol, $fac;
	global $focus, $barcode;
	global $error, $error_msg;
	global $warning, $warning_msg;
	global $acquisition_gestion_tva;
	
	$frame = $frame_modif;
	$cde = new actes($id_cde);
	
	$lig_afac = array(); //Tableau des lignes à facturer
	$lig_dfac = array(); //Tableau des lignes déjà facturées
	$focus = 0;
	
	//Les lignes restant à facturer sont reprises telles quelles
	for($i=1;$i<=$max_lig;$i++) {
		
		$lig_afac[$i]['id_lig'] = $id_lig[$i];
		$lig_afac[$i]['id_prod']=$id_prod[$i];
		$lig_afac[$i]['code']=stripslashes($code[$i]);
		$lig_afac[$i]['lib']= stripslashes($lib[$i]);
		$lig_afac[$i]['prix']= number_format(round($prix[$i],2),2,'.','');
		$lig_afac[$i]['typ']= $typ[$i];
		$lig_afac[$i]['tva']= number_format($tva[$i],2,'.','');
		$lig_afac[$i]['rem']= number_format(round($rem[$i],2),2,'.','');
		$lig_afac[$i]['lib_typ']= stripslashes($lib_typ[$i]);
		$lig_afac[$i]['rub']= $rub[$i];
		$lig_afac[$i]['lib_rub']= stripslashes($lib_rub[$i]);
		$lig_afac[$i]['sol']= $sol[$i];
		$lig_afac[$i]['fac']= $fac[$i];

	}
	
	//Les lignes déjà facturées sont reprises telles quelles
	for($i;$i<=$max_lig+$max_lig_fac;$i++) {
			
		$lig_dfac[$i]['id_lig'] = $id_lig[$i];
		$lig_dfac[$i]['id_prod']=$id_prod[$i];
		$lig_dfac[$i]['code']=stripslashes($code[$i]);
		$lig_dfac[$i]['lib']= stripslashes($lib[$i]);
		$lig_dfac[$i]['prix']= number_format(round($prix[$i],2),2,'.','');
		$lig_dfac[$i]['typ']= $typ[$i];
		$lig_dfac[$i]['tva']= number_format($tva[$i],2,'.','');
		$lig_dfac[$i]['rem']= number_format(round($rem[$i],2),2,'.','');
		$lig_dfac[$i]['lib_typ']= stripslashes($lib_typ[$i]);
		$lig_dfac[$i]['rub']= $rub[$i];
		$lig_dfac[$i]['lib_rub']= stripslashes($lib_rub[$i]);
		$lig_dfac[$i]['fac']= $fac[$i];
			
	}
	
	//recherche du code saisi
	$trouve = 0;
	foreach($lig_afac as $key=>$value) {
		
		if($lig_afac[$key]['code'] == $barcode) {	//Code trouvé

			$trouve = $key;

				if( ($lig_afac[$key]['fac'] < $lig_afac[$key]['sol']) ) {	
				
					//La qté saisie est inférieure à la qté restant à recevoir >> Sortie
					break;
					
				//Sinon, si la quantité saisie est égale à la quantité restant à recevoir >> On recherche plus avant 
									
			}
			
		}	
				
	}


	$index = 1;	
	$max_lig = count($lig_afac);
	//Affichage des lignes restant à facturer
	foreach($lig_afac as $key=>$value) {

				
		if ($trouve == $key) {
			$focus = $index;
		}
				
		$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);
		$frame = str_replace('<!-- select_typ -->', $select_typ[0], $frame);
		$frame = str_replace('<!-- select_bud -->', $select_rub[0], $frame);
		$frame = str_replace('!!no!!', $index, $frame);
		$frame = str_replace('!!id_lig!!', $lig_afac[$key]['id_lig'], $frame);
		$frame = str_replace('!!id_prod!!', $lig_afac[$key]['id_prod'], $frame);
		$frame = str_replace('!!code!!', $lig_afac[$key]['code'], $frame);
		$frame = str_replace('!!lib!!', $lig_afac[$key]['lib'], $frame);
		$frame = str_replace('!!prix!!', $lig_afac[$key]['prix'], $frame);
		$frame = str_replace('!!typ!!', $lig_afac[$key]['typ'], $frame);
		$frame = str_replace('!!tva!!', $lig_afac[$key]['tva'], $frame);
		$frame = str_replace('!!rem!!', $lig_afac[$key]['rem'], $frame);
		$frame = str_replace('!!lib_typ!!', $lig_afac[$key]['lib_typ'], $frame);
		$frame = str_replace('!!id_rub!!', $lig_afac[$key]['rub'], $frame);
		$frame = str_replace('!!lib_rub!!', $lig_afac[$key]['lib_rub'], $frame);
		$frame = str_replace('!!sol!!', $lig_afac[$key]['sol'], $frame);
		$frame = str_replace('!!fac!!', $lig_afac[$key]['fac'], $frame);		
		$index++;			
		
	}
	$frame = str_replace('!!max_lig!!', $max_lig, $frame);
	
	$max_lig_fac = count($lig_dfac);
	if ($max_lig_fac) {

		$frame = str_replace('<!-- lignes -->', $frame_row_fa_header.'<!-- lignes -->', $frame);
	
		//Affichage des lignes déjà facturées
		foreach($lig_dfac as $key=>$value) {
			
			$frame = str_replace('<!-- lignes -->', $frame_row_fa.'<!-- lignes -->', $frame);
			$frame = str_replace('<!-- select_typ -->', $select_typ[1], $frame);
			$frame = str_replace('<!-- select_bud -->', $select_rub[1], $frame);
			$frame = str_replace('!!no!!', $index, $frame);
			$frame = str_replace('!!id_lig!!', $lig_dfac[$key]['id_lig'], $frame);
			$frame = str_replace('!!id_prod!!', $lig_dfac[$key]['id_prod'], $frame);
			$frame = str_replace('!!code!!', $lig_dfac[$key]['code'], $frame);
			$frame = str_replace('!!lib!!', $lig_dfac[$key]['lib'], $frame);
			$frame = str_replace('!!prix!!', $lig_dfac[$key]['prix'], $frame);
			$frame = str_replace('!!typ!!', $lig_dfac[$key]['typ'], $frame);
			$frame = str_replace('!!rem!!', $lig_dfac[$key]['rem'], $frame);
			$frame = str_replace('!!lib_typ!!', $lig_dfac[$key]['lib_typ'], $frame);
			$frame = str_replace('!!id_rub!!', $lig_dfac[$key]['rub'], $frame);
			$frame = str_replace('!!lib_rub!!', $lig_dfac[$key]['lib_rub'], $frame);
			$frame = str_replace('!!sol!!', '', $frame);
			$frame = str_replace('!!fac!!', $lig_dfac[$key]['fac'], $frame);		
			$index++;


			//calcul des montants ht, ttc, tva			
			if ($acquisition_gestion_tva) {
				$lig_ht = $lig_dfac[$key]['fac'] * $lig_dfac[$key]['prix'] * (1-($lig_dfac[$key]['rem']/100)) ;
				$tot_ht = $tot_ht + $lig_ht;
				$tot_tva = $tot_tva + ($lig_ht*($lig_dfac[$key]['tva']/100) );
				$tot_ttc = $tot_ht + $tot_tva;						
				$frame = str_replace('!!tva!!', $lig_dfac[$key]['tva'], $frame);
			} else {
				$lig_ttc = $lig_dfac[$key]['fac'] * $lig_dfac[$key]['prix'] * (1-($lig_dfac[$key]['rem']/100)) ;
				$tot_ttc = $tot_ttc + $lig_ttc;
				$frame = str_replace('!!tva!!', '', $frame);											
			}		

		}
	}
	$frame = str_replace('!!max_lig_fac!!', $max_lig_fac, $frame);

	$frame = str_replace('!!tot_ht!!', number_format(round($tot_ht,2),2,'.',''), $frame);
	$frame = str_replace('!!tot_tva!!', number_format(round($tot_tva,2),2,'.',''), $frame);
	$frame = str_replace('!!tot_ttc!!', number_format(round($tot_ttc,2),2,'.',''), $frame);

	//Mise en place focus si code saisi trouvé
	if ($focus) {
		$focus = "<script type='text/javascript'>window.location.hash= '#ancre[".$focus."]';f=document.getElementById('fac[".$focus."]');f.focus();</script>";
	} else { 
		$focus = "<script type='text/javascript'>alert('".$msg['acquisition_liv_code_inex']."'); window.parent.document.getElementById('cb').focus();</script>";
	}
	
	$frame = str_replace('<!-- focus -->', $focus, $frame);
	
		
	if ($error) {
		$frame = str_replace('<!-- error -->', "<script type='text/javascript'>alert(\"".$error_msg."\"); </script>", $frame);
	}

	$frame = str_replace('!!id_fac!!', $id_fac, $frame);
	$frame = str_replace('!!id_fou!!', $cde->num_fournisseur, $frame);
	

	print $frame; 	
	
}


function sup_lig_fac() {

	global $msg;
	global $frame_modif, $frame_row, $frame_row_fa_header, $frame_row_fa, $select_typ, $select_rub, $bt_sup_lig, $no_bt_sup_lig;
	global $id_bibli, $id_cde, $id_fac;
	global $max_lig, $max_lig_fac;
	global $chk, $id_lig, $id_prod, $code, $lib, $prix, $typ, $tva, $rem, $lib_typ, $rub, $lib_rub, $sol, $fac;
	global $acquisition_gestion_tva;
	
	$tot_ht = 0;
	$tot_tva = 0;
	$tot_ttc = 0;
	$frame = $frame_modif;
	
	$cde = new actes($id_cde);
	
	$lig_afac = array(); //Tableau des lignes à facturer
	$lig_dfac = array(); //Tableau des lignes déjà facturées
	
	//Les lignes restant à facturer sont reprises telles quelles
	for($i=1;$i<=$max_lig;$i++) {
		
		$lig_afac[$i]['id_lig'] = $id_lig[$i];
		$lig_afac[$i]['id_prod']=$id_prod[$i];
		$lig_afac[$i]['code']=stripslashes($code[$i]);
		$lig_afac[$i]['lib']= stripslashes($lib[$i]);
		$lig_afac[$i]['prix']= number_format(round($prix[$i],2),2,'.','');
		$lig_afac[$i]['typ']= $typ[$i];
		$lig_afac[$i]['tva']= number_format($tva[$i],2,'.','');
		$lig_afac[$i]['rem']= number_format(round($rem[$i],2),2,'.','');
		$lig_afac[$i]['lib_typ']= stripslashes($lib_typ[$i]);
		$lig_afac[$i]['rub']= $rub[$i];
		$lig_afac[$i]['lib_rub']= stripslashes($lib_rub[$i]);
		$lig_afac[$i]['sol']= $sol[$i];
		$lig_afac[$i]['fac']= $fac[$i];
		
	}
	
	//La quantite facturée des lignes supprimées est reportée dans le solde des lignes restant à facturer si l'identifiant de ligne existe
	//Sinon, une nouvelle ligne restant à facturer est créée
	for($i;$i<=$max_lig+$max_lig_fac;$i++) {

		if ($chk[$i]) {	//La ligne est cochée pour suppression
			
			$raf = false;
			for($j=1;$j<=$max_lig; $j++) {	//Y avait-il un solde à facturer
			
				if($lig_afac[$j]['id_lig'] == $id_lig[$i]) {	//Si oui, on rajoute le facturé de la ligne supprimée au solde à facturer
					$lig_afac[$j]['sol'] = $lig_afac[$j]['sol']+$fac[$i];
					$raf = true;
					break;								
				} 
			}
			if (!$raf) {	//Il n'y avait pas de reste à facturer, on recrée la ligne dans le tableau des restant à facturer
				
				$lig_afac[$i]['id_lig'] = $id_lig[$i];
				$lig_afac[$i]['id_prod']=$id_prod[$i];
				$lig_afac[$i]['code']=stripslashes($code[$i]);
				$lig_afac[$i]['lib']= stripslashes($lib[$i]);
				$lig_afac[$i]['prix']= number_format(round($prix[$i],2),2,'.','');
				$lig_afac[$i]['typ']= $typ[$i];
				$lig_afac[$i]['tva']= number_format($tva[$i],2,'.','');
				$lig_afac[$i]['rem']= number_format(round($rem[$i],2),2,'.','');
				$lig_afac[$i]['lib_typ']= stripslashes($lib_typ[$i]);
				$lig_afac[$i]['rub']= $rub[$i];
				$lig_afac[$i]['lib_rub']= stripslashes($lib_rub[$i]);
				$lig_afac[$i]['sol']= $fac[$i];
				$lig_afac[$i]['fac']= '0';
				
			}
			
		} else {	//La ligne n'est pas cochée pour suppression, on la conserve dans les déjà facturées
		
			$lig_dfac[$i]['id_lig'] = $id_lig[$i];
			$lig_dfac[$i]['id_prod']=$id_prod[$i];
			$lig_dfac[$i]['code']=stripslashes($code[$i]);
			$lig_dfac[$i]['lib']= stripslashes($lib[$i]);
			$lig_dfac[$i]['prix']= number_format(round($prix[$i],2),2,'.','');
			$lig_dfac[$i]['typ']= $typ[$i];
			$lig_dfac[$i]['tva']= number_format($tva[$i],2,'.','');
			$lig_dfac[$i]['rem']= number_format(round($rem[$i],2),2,'.','');
			$lig_dfac[$i]['lib_typ']= stripslashes($lib_typ[$i]);
			$lig_dfac[$i]['rub']= $rub[$i];
			$lig_dfac[$i]['lib_rub']= stripslashes($lib_rub[$i]);
			$lig_dfac[$i]['sol']= $sol[$i];
			$lig_dfac[$i]['fac']= $fac[$i];
		
		}
	}
	

	
	$index = 1;	
	$max_lig = count($lig_afac);
	//Affichage des lignes restant à facturer
	foreach($lig_afac as $key=>$value) {
		
		$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);
		$frame = str_replace('<!-- select_typ -->', $select_typ[0], $frame);
		$frame = str_replace('<!-- select_bud -->', $select_rub[0], $frame);
		$frame = str_replace('!!no!!', $index, $frame);
		$frame = str_replace('!!id_lig!!', $lig_afac[$key]['id_lig'], $frame);
		$frame = str_replace('!!id_prod!!', $lig_afac[$key]['id_prod'], $frame);
		$frame = str_replace('!!code!!', $lig_afac[$key]['code'], $frame);
		$frame = str_replace('!!lib!!', $lig_afac[$key]['lib'], $frame);
		$frame = str_replace('!!prix!!', $lig_afac[$key]['prix'], $frame);
		$frame = str_replace('!!typ!!', $lig_afac[$key]['typ'], $frame);
		$frame = str_replace('!!tva!!', $lig_afac[$key]['tva'], $frame);
		$frame = str_replace('!!rem!!', $lig_afac[$key]['rem'], $frame);
		$frame = str_replace('!!lib_typ!!', $lig_afac[$key]['lib_typ'], $frame);
		$frame = str_replace('!!id_rub!!', $lig_afac[$key]['rub'], $frame);
		$frame = str_replace('!!lib_rub!!', $lig_afac[$key]['lib_rub'], $frame);
		$frame = str_replace('!!sol!!', $lig_afac[$key]['sol'], $frame);
		$frame = str_replace('!!fac!!', $lig_afac[$key]['fac'], $frame);		
		$index++;
	}
	$frame = str_replace('!!max_lig!!', $max_lig, $frame);
	
	$max_lig_fac = count($lig_dfac);
	if ($max_lig_fac) {

		$frame = str_replace('<!-- lignes -->', $frame_row_fa_header.'<!-- lignes -->', $frame);
	
		//Affichage des lignes déjà facturées
		foreach($lig_dfac as $key=>$value) {
			
			$frame = str_replace('<!-- lignes -->', $frame_row_fa.'<!-- lignes -->', $frame);
			$frame = str_replace('<!-- select_typ -->', $select_typ[1], $frame);
			$frame = str_replace('<!-- select_bud -->', $select_rub[1], $frame);
			$frame = str_replace('!!no!!', $index, $frame);
			$frame = str_replace('!!id_lig!!', $lig_dfac[$key]['id_lig'], $frame);
			$frame = str_replace('!!id_prod!!', $lig_dfac[$key]['id_prod'], $frame);
			$frame = str_replace('!!code!!', $lig_dfac[$key]['code'], $frame);
			$frame = str_replace('!!lib!!', $lig_dfac[$key]['lib'], $frame);
			$frame = str_replace('!!prix!!', $lig_dfac[$key]['prix'], $frame);
			$frame = str_replace('!!typ!!', $lig_dfac[$key]['typ'], $frame);
			$frame = str_replace('!!rem!!', $lig_dfac[$key]['rem'], $frame);
			$frame = str_replace('!!lib_typ!!', $lig_dfac[$key]['lib_typ'], $frame);
			$frame = str_replace('!!id_rub!!', $lig_dfac[$key]['rub'], $frame);
			$frame = str_replace('!!lib_rub!!', $lig_dfac[$key]['lib_rub'], $frame);
			$frame = str_replace('!!sol!!', '', $frame);
			$frame = str_replace('!!fac!!', $lig_dfac[$key]['fac'], $frame);		
			$index++;


			//calcul des montants ht, ttc, tva			
			if ($acquisition_gestion_tva) {
				$lig_ht = $lig_dfac[$key]['fac'] * $lig_dfac[$key]['prix'] * (1-($lig_dfac[$key]['rem']/100)) ;
				$tot_ht = $tot_ht + $lig_ht;
				$tot_tva = $tot_tva + ($lig_ht*($lig_dfac[$key]['tva']/100) );
				$tot_ttc = $tot_ht + $tot_tva;						
				$frame = str_replace('!!tva!!', $lig_dfac[$key]['tva'], $frame);
			} else {
				$lig_ttc = $lig_dfac[$key]['fac'] * $lig_dfac[$key]['prix'] * (1-($lig_dfac[$key]['rem']/100)) ;
				$tot_ttc = $tot_ttc + $lig_ttc;
				$frame = str_replace('!!tva!!', '', $frame);											
			}		
			
		}
	}
	
	$frame = str_replace('!!tot_ht!!', number_format(round($tot_ht,2),2,'.',''), $frame);
	$frame = str_replace('!!tot_tva!!', number_format(round($tot_tva,2),2,'.',''), $frame);
	$frame = str_replace('!!tot_ttc!!', number_format(round($tot_ttc,2),2,'.',''), $frame);
	
	$frame = str_replace('!!max_lig_fac!!', $max_lig_fac, $frame);
	
	$frame = str_replace('!!id_fac!!', $id_fac, $frame);
	$frame = str_replace('!!id_fou!!', $cde->num_fournisseur, $frame);

	//Affichage du bouton de suppression de lignes
	if (!$max_lig_fac) {
		$frame = str_replace('<!-- bouton_sup_lig -->', $no_bt_sup_lig, $frame);			
	} else {
		$frame = str_replace('<!-- bouton_sup_lig -->', $bt_sup_lig, $frame);
	}

		
	print $frame;		
} 


//Enregistre la facture
function update_fac($statut=0) {
	
	global $id_bibli, $id_cde, $id_fac, $comment, $ref;
	global $max_lig, $max_lig_fac, $id_lig, $id_prod, $code, $lib, $prix, $typ, $tva, $rem, $rub, $fac;
	global $date_pay, $num_pay, $devise;

	$tab_fac = array(); //Tableau des lignes facturées
	
	//Les lignes restant à facturer sont reprises dans le tableau si la qté facturée est >0
	for($i=1;$i<=$max_lig;$i++) {
		
		if ($fac[$i]) {
			$tab_fac[$i]['id_lig'] = $id_lig[$i];
			$tab_fac[$i]['id_prod'] = $id_prod[$i];
			$tab_fac[$i]['code'] = $code[$i];
			$tab_fac[$i]['lib'] = $lib[$i];
			$tab_fac[$i]['prix'] = round($prix[$i],2);
			$tab_fac[$i]['typ'] = $typ[$i];
			$tab_fac[$i]['tva'] = $tva[$i];
			$tab_fac[$i]['rem'] = round($rem[$i],2);
			$tab_fac[$i]['rub'] = $rub[$i];
			$tab_fac[$i]['fac'] = $fac[$i];
		}
	}

	//Les quantités facturées des lignes déjà facturées sont reportées dans le tableau si l'identifiant de ligne existe
	//et que les prix, types de produits, remises et budgets sont égaux
	//Sinon une nouvelle ligne est créée 
	for($i;$i<=$max_lig+$max_lig_fac;$i++) {

		$deja = false;
		for($j=1;$j<=$max_lig; $j++) {	//Y a-t'il une ligne deja créée

			if( 	($tab_fac[$j]['id_lig'] == $id_lig[$i]) && 
					($tab_fac[$j]['prix'] == $prix[$i]) &&
					($tab_fac[$j]['typ'] == $typ[$i]) &&
					($tab_fac[$j]['rem'] == $rem[$i]) &&
					($tab_fac[$j]['rub'] == $rub[$i])		) {	//Si oui, on rajoute la quantité reçue dans le tableau
				$tab_fac[$j]['fac'] = $tab_fac[$j]['fac']+$fac[$i];
				$deja = true;
				break;
			} 
		}
		if (!$deja) {	//Sinon, on crée la ligne dans le tableau 
			$tab_fac[$i]['id_lig'] = $id_lig[$i];
			$tab_fac[$i]['id_prod'] = $id_prod[$i];
			$tab_fac[$i]['code'] = $code[$i];
			$tab_fac[$i]['lib'] = $lib[$i];
			$tab_fac[$i]['prix'] = round($prix[$i],2);
			$tab_fac[$i]['typ'] = $typ[$i];
			$tab_fac[$i]['tva'] = $tva[$i];
			$tab_fac[$i]['rem'] = round($rem[$i],2);
			$tab_fac[$i]['rub'] = $rub[$i];
			$tab_fac[$i]['fac'] = $fac[$i];
		}		
	}
	
	//Récupération de la commande
	$cde = new actes($id_cde);
	
	if (!$id_fac) {	//Création de la facture

		$factu = new actes();
		$factu->date_acte = today();
		$factu->type_acte = TYP_ACT_FAC;
		if($statut) $factu->statut = $statut;
			else $factu->statut = STA_ACT_REC;
		$factu->num_entite = $cde->num_entite;
		$factu->num_fournisseur = $cde->num_fournisseur;
		$factu->num_contact_livr = $cde->num_contact_livr;
		$factu->num_contact_fact = $cde->num_contact_fact;
		$factu->num_exercice = $cde->num_exercice;
		$factu->commentaires = $comment;
		$factu->reference = $ref;
		$factu->devise = $devise;
		if ($date_pay != '') $factu->date_paiement = extraitdate($date_pay);
		$factu->num_paiement = $num_pay;
		$factu->calc();
		$factu->save();
		
		$id_fac = $factu->id_acte;
		//création des liens entre actes
		$la = new liens_actes($id_cde, $id_fac);
	
	} else {	//Modification de la facture

		$factu = new actes($id_fac);
		$factu->numero=addslashes($factu->numero);
		$factu->commentaires = trim($comment);
		$factu->reference = trim($ref);
		if ($date_pay != '') $factu->date_paiement = extraitdate($date_pay);
		$factu->num_paiement = trim($num_pay);
		if($statut) $factu->statut = $statut;
		$factu->devise = trim($devise);
		$factu->save();
		
	}
	
	//Suppression des lignes de facture précédemment enregistrées
	actes::deleteLignes($id_fac);

	//Création des lignes de facture
	foreach ($tab_fac as $key=>$value) {

		$lig_cde = new lignes_actes($tab_fac[$key]['id_lig']);
		
		$lig_fac = new lignes_actes();	
		$lig_fac->num_acte = $factu->id_acte;
		$lig_fac->lig_ref = $lig_cde->id_ligne;
		$lig_fac->num_produit = $lig_cde->num_produit;
		$lig_fac->code = addslashes($lig_cde->code);
		$lig_fac->libelle = addslashes($lig_cde->libelle);
		$lig_fac->num_acquisition = $lig_cde->num_acquisition; 				
		$lig_fac->num_type = $lig_cde->num_type;
		$lig_fac->prix = $tab_fac[$key]['prix'];
		$lig_fac->tva = $tab_fac[$key]['tva'];
		$lig_fac->remise = $tab_fac[$key]['rem'];
		$lig_fac->num_rubrique = $tab_fac[$key]['rub'];
		$lig_fac->nb = $tab_fac[$key]['fac'];
		$lig_fac->date_cre = today();
		$lig_fac->save();		
	}

	//La commande est-elle entièrement facturée
	$tab_cde = actes::getLignes($id_cde);
	$facture = true;
	while (($row_cde = mysql_fetch_object($tab_cde))) {
		$tab_fac = lignes_actes::getFactures($row_cde->id_ligne);
		$nb_fac = 0;
		while (($row_fac = mysql_fetch_object($tab_fac))) {
			$nb_fac = $nb_fac + $row_fac->nb;
		}
		if ($row_cde->nb > $nb_fac) {
			$facture = false;
			break;
		}
		
	}
	if ($facture) {
		
		 //Pas de reste à facturer >>Statut commande=facturée
		$cde->statut = ($cde->statut | STA_ACT_FAC);
		
		//Si de plus toutes les factures sont payées, Statut commande=payé
		$tab_pay = liens_actes::getChilds($id_cde, TYP_ACT_FAC);
		$paye= true;
		while (($row_pay = mysql_fetch_object($tab_pay))) {
			if(($row_pay->statut & STA_ACT_PAY) != STA_ACT_PAY){
				$paye = false;
				break;
			}
		}
		if ($paye) $cde->statut = ($cde->statut | STA_ACT_PAY);		
			
	} else {
		
		//Sinon, la commande repasse en statut en cours
		$cde->statut = ($cde->statut & ~(STA_ACT_FAC | STA_ACT_PAY));
	}
		$cde->update_statut();

	
}  


function select_rub($id_cde, $id_rubrique=0) {
	
	global $msg, $charset;
	global $select_rub ;
	
	$cde = new actes($id_cde);

	if ($cde->statut >= STA_ACT_ARC) {	//Commande archivée, facture non modifiable

		$lig_rub = $select_rub[1];

		if (!$id_rubrique) {
			$id_rubrique = 0;
			$lib_rub = '';
		} else {
			$rub = new rubriques($id_rubrique);
			$bud = new budgets($rub->num_budget);
			$lib_bud = $bud->libelle;
			
			$tab_rub = rubriques::listAncetres($id_rubrique, true);
			$lib_rub = $lib_bud.':';
			foreach ($tab_rub as $value) {
				$lib_rub.= htmlentities($value[1], ENT_QUOTES, $charset);
				if($value[0] != $id_rubrique) $lib_rub.= ":";
			}
		}
		$lig_rub = str_replace('!!id_rub!!',$id_rubrique , $lig_rub);
		$lig_rub = str_replace('!!lib_rub!!', $lib_rub, $lig_rub);

	} else {	//Commande non archivée, modification des rubriques possible		
					 
		if (!$id_rubrique) {	//Pas de rubrique sélectionnée

			$lig_rub = $select_rub[0];
			$lig_rub = str_replace('!!id_rub!!', 0, $lig_rub);
			$lig_rub = str_replace('!!lib_rub!!', '', $lig_rub);

		} else {	//Rubrique selectionnée
		
			$user_userid = getCurrentUserId();
			$rub = new rubriques($id_rubrique);
			$bud = new budgets($rub->num_budget);
	
			if (!rubriques::getAutorisations($id_rubrique, $user_userid)) { //L'utilisateur n'a pas de droits sur la rubrique
			
				$lig_rub = $select_rub[1];
				
			} else { //L'utilisateur à des droits sur la rubrique
	
				$lig_rub = $select_rub[0];
							
			}
	
			$lig_rub = str_replace('!!id_rub!!', $rub->id_rubrique, $lig_rub);

			$lib_bud = htmlentities($bud->libelle, ENT_QUOTES, $charset);
			
			$tab_rub = rubriques::listAncetres($id_rubrique, true);
			$lib_rub = $lib_bud.':';
			foreach ($tab_rub as $value) {
				$lib_rub.= htmlentities($value[1], ENT_QUOTES, $charset);
				if($value[0] != $id_rubrique) $lib_rub.= ":";
			}

			$lig_rub = str_replace('!!lib_rub!!', $lib_rub, $lig_rub);			
	
		}			
	}
	return $lig_rub;
}


//Récupération de l'utilisateur courant
function getCurrentUserId() {
	
	global $dbh;
		
 	$requete_user = "SELECT userid FROM users where username='".SESSlogin."' limit 1 ";
	$res_user = mysql_query($requete_user, $dbh);
	$row_user=mysql_fetch_row($res_user);
	$user_userid=$row_user[0];
	return $user_userid;

}


// gestion des achats : factures

switch($action) {

	case 'from_cde' :
		show_lig_from_cde();
		break;		

	case 'show_lig' :
		show_lig_fac();
		break;

	case 'search' :
		test_cb();
		search_lig_fac();
		break;

	case 'sup_lig' :
		sup_lig_fac();
		break;

	case 'update' :
		verif_fac();
		if ($error) {
			show_lig_bak();
			break;
		}
		verif_bud();
		if ($error) {
			show_lig_bak();
			break;
		}					
		update_fac();
		print $retour_liste;	
		break;

	case 'pay' :
		verif_fac();
		if ($error) {
			show_lig_bak();
			break;			
		}
		verif_bud();
		if ($error) {
			show_lig_bak();
			break;
		}					
		update_fac(STA_ACT_PAY);
		print $retour_liste;
		break;
	

	default:
		break;
		
}

?>
