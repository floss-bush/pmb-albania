<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: frame_livraison.php,v 1.27 2008-12-24 14:38:10 dbellamy Exp $

//Liste les lignes d'une facture
$base_path="../../..";                            
//$base_auth = "ACQUISITION_AUTH";  
$include_path = "$base_path/includes";
$class_path = "$base_path/classes";

$current_alert="acquisition";
require_once("$include_path/init.inc.php");

// gestion des lignes de livraison
require_once("$class_path/entites.class.php");
require_once("$class_path/types_produits.class.php");
require_once("$class_path/tva_achats.class.php");
require_once("$class_path/actes.class.php");
require_once("$class_path/lignes_actes.class.php");
require_once("$class_path/liens_actes.class.php");
require_once("$class_path/suggestions.class.php");
require_once("$class_path/suggestions_map.class.php");
require_once("$include_path/isbn.inc.php");
require_once("$include_path/misc.inc.php");
require_once("$include_path/templates/livraisons.tpl.php");
require_once($base_path."/acquisition/suggestions/func_suggestions.inc.php");

$error = false;
$error_msg = '';
$focus = 0;

//verification du formulaire
function verif_liv() {

	global $msg;
	global $max_lig, $max_lig_liv;
	global $sol, $rec;
	global $action;
	global $error, $error_msg;

	//Si pas de lignes sur la livraison >> Avertissement utilisateur et retour
	$qte_liv = 0;
	for($i='1'; $i<=$max_lig; $i++) {
		$qte_liv = $qte_liv + $rec[$i]; 
	}

	if ( ($action == 'update') && ($max_lig_liv == 0) && ($qte_liv == 0)) {
		$error = true;
		$error_msg = $msg['acquisition_liv_vid']; 
		return;
	}

	for($i='1'; $i<=$max_lig; $i++) {
		
		//Vérification quantité livrée
		if (!is_numeric($rec[$i]) || $rec[$i] < '0' || $rec[$i] > '99999' ) {
			$error = true;
			$error_msg = $msg['acquisition_lig'].' '.$i.': '.$msg['acquisition_qte_liv_inv'];
			break;
		}
		if ($rec[$i] > $sol[$i])	{
			$error = true;
			$error_msg = $msg['acquisition_lig'].' '.$i.': '.$msg['acquisition_qte_liv_sup'];
			break;
		}
		
	}
	
}


//Affichage création BL depuis commande
function show_lig_from_cde() {
	
	global $msg, $charset;
	global $frame_modif, $frame_row, $frame_row_bl_header, $frame_row_bl, $bt_sup_lig;
	global $id_bibli, $id_cde, $id_liv;
	global $auto, $focus;
	global $error, $error_msg;
	
	$frame = $frame_modif;
	
	//Affichage du solde à livrer sur la commande
	$lignes_cde = actes::getLignes($id_cde);

	$nb_lig = 0;
	
	while (($row_cde = mysql_fetch_object($lignes_cde))) {
		
		if ($row_cde->type_ligne == 3) {	// Frais, non livrables
			
		} else {
		
			//recherche des lignes de livraison
			$lignes_liv = lignes_actes::getLivraisons($row_cde->id_ligne);
			$sol = $row_cde->nb;
			while (($row_liv = mysql_fetch_object($lignes_liv))) {
				$sol = $sol - $row_liv->nb;
			}
			$rec = 0;
	
			//affichage ligne si solde à livrer >0
			if ($sol) {
	
				$nb_lig++;
				$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);						
				$frame = str_replace('!!no!!', $nb_lig, $frame);
				$frame = str_replace('!!id_lig!!', $row_cde->id_ligne, $frame);
				$frame = str_replace('!!id_prod!!', $row_cde->num_produit, $frame);
				$frame = str_replace('!!code!!', htmlentities($row_cde->code, ENT_QUOTES, $charset), $frame);
				$frame = str_replace('!!hidden_lib!!', htmlentities($row_cde->libelle, ENT_QUOTES, $charset), $frame);
				$frame = str_replace('!!lib!!', nl2br(htmlentities($row_cde->libelle, ENT_QUOTES, $charset)), $frame);
				$frame = str_replace('!!sol!!', $sol, $frame);	
				$frame = str_replace('!!rec!!', $rec, $frame);
			}
		}		
	}	
	$frame = str_replace('!!max_lig!!', $nb_lig, $frame);	
	$frame = str_replace('!!max_lig_liv!!', '0', $frame);

	$frame = str_replace('!!id_liv!!', '0', $frame);

	print $frame; 	
	
}  


//Affichage des lignes de livraison
function show_lig_liv() {
	
	global $msg, $charset;
	global $frame_modif, $frame_row, $frame_row_bl_header, $frame_row_bl, $frame_row_arc, $bt_sup_lig, $no_bt_sup_lig;
	global $id_bibli, $id_cde, $id_liv;
	global $auto, $focus;
	global $error, $error_msg;
	
	$frame = $frame_modif;
	
	//Lecture des éléments de la commande
	$cde = new actes($id_cde);	
	$lignes_cde = actes::getLignes($id_cde);


	if( ($cde->statut & 32) == 32 ) { 	//La commande est archivée donc le bl non modifiable
	
		$nb_lig = 0;

		while (($row_cde = mysql_fetch_object($lignes_cde))) {
			
			
			if ($row_cde->type_ligne == 3) {	// Frais, non livrables
				
			} else {
			
		
				//recherche des lignes de livraison
				$lignes_liv = lignes_actes::getLivraisons($row_cde->id_ligne);
				$rec = 0;
				while (($row_liv = mysql_fetch_object($lignes_liv))) {
					$rec = $rec + $row_liv->nb;
				}
				$sol = $row_cde->nb - $rec;
	
				$nb_lig++;
				$frame = str_replace('<!-- lignes -->', $frame_row_arc.'<!-- lignes -->', $frame);						
				$frame = str_replace('!!no!!', $nb_lig, $frame);
				$frame = str_replace('!!id_lig!!', $row_cde->id_ligne, $frame);
				$frame = str_replace('!!id_prod!!', $row_cde->num_produit, $frame);
				$frame = str_replace('!!code!!', htmlentities($row_cde->code, ENT_QUOTES, $charset), $frame);
				$frame = str_replace('!!hidden_lib!!', htmlentities($row_cde->libelle, ENT_QUOTES, $charset), $frame);
				$frame = str_replace('!!lib!!', nl2br(htmlentities($row_cde->libelle, ENT_QUOTES, $charset)), $frame);
				$frame = str_replace('!!sol!!', $sol, $frame);	
				$frame = str_replace('!!rec!!', $rec, $frame);
		
			}		
		
		}	
		$frame = str_replace('!!max_lig!!', $nb_lig, $frame);	
		$frame = str_replace('!!max_lig_liv!!', 0, $frame);
		$frame = str_replace('!!id_liv!!', '0', $frame);
		$frame = str_replace('<!-- bouton_sup_lig -->', $no_bt_sup_lig, $frame);
		print $frame;				
	
	} else {	//le bl est modifiable

		
		if( ($cde->statut & 4) != 4 ) { 	//La commande est soldée, on n'affiche pas les lignes restant à livrer
			
			$nb_lig = 0;
			
			while (($row_cde = mysql_fetch_object($lignes_cde))) {
				
			
				if ($row_cde->type_ligne == 3) {	// Frais, non livrables
				} else {
			
				
					//recherche des lignes de livraison
					$lignes_liv = lignes_actes::getLivraisons($row_cde->id_ligne);
					$sol = $row_cde->nb;
					while (($row_liv = mysql_fetch_object($lignes_liv))) {
						
						$sol = $sol - $row_liv->nb; 
					}
					$rec = 0;
			
					//affichage ligne si solde à livrer >0
					if ($sol) {
			
						$nb_lig++;
						$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);						
						$frame = str_replace('!!no!!', $nb_lig, $frame);
						$frame = str_replace('!!id_lig!!', $row_cde->id_ligne, $frame);
						$frame = str_replace('!!id_prod!!', $row_cde->num_produit, $frame);
						$frame = str_replace('!!code!!', htmlentities($row_cde->code, ENT_QUOTES, $charset), $frame);
						$frame = str_replace('!!hidden_lib!!', htmlentities($row_cde->libelle, ENT_QUOTES, $charset), $frame);						
						$frame = str_replace('!!lib!!', nl2br(htmlentities($row_cde->libelle, ENT_QUOTES, $charset)), $frame);
						$frame = str_replace('!!sol!!', $sol, $frame);	
						$frame = str_replace('!!rec!!', $rec, $frame);
					}
				}		
			}	
			$frame = str_replace('!!max_lig!!', $nb_lig, $frame);	

		} else {

			$frame = str_replace('!!max_lig!!', 0, $frame);

		}	
		
		//affichage du déjà livré sur le bon de livraison courant
		if ($id_liv) {
			$frame = str_replace('<!-- lignes -->', $frame_row_bl_header.'<!-- lignes -->', $frame);
			$lignes_liv = actes::getLignes($id_liv);
			$max_lig_liv = mysql_num_rows($lignes_liv);
			$frame = str_replace('!!max_lig_liv!!', $max_lig_liv, $frame);
		
			while (($row_liv = mysql_fetch_object($lignes_liv))) {
				$nb_lig++;
				$frame = str_replace('<!-- lignes -->', $frame_row_bl.'<!-- lignes -->', $frame);
				$frame = str_replace('!!no!!', $nb_lig, $frame);
				$frame = str_replace('!!id_lig!!', $row_liv->lig_ref, $frame);
				$frame = str_replace('!!id_prod!!', $row_liv->num_produit, $frame);
				$frame = str_replace('!!code!!', htmlentities($row_liv->code, ENT_QUOTES, $charset), $frame);
				$frame = str_replace('!!hidden_lib!!', htmlentities($row_liv->libelle, ENT_QUOTES, $charset), $frame);	
				$frame = str_replace('!!lib!!', nl2br(htmlentities($row_liv->libelle, ENT_QUOTES, $charset)), $frame);	
				$frame = str_replace('!!rec!!', $row_liv->nb, $frame);
			}
			
			$frame = str_replace('!!id_liv!!', $id_liv, $frame);
			$frame = str_replace('<!-- bouton_sup_lig -->', $bt_sup_lig, $frame);
	
		} else {
			$frame = str_replace('!!id_liv!!', '0', $frame);
			$frame = str_replace('<!-- bouton_sup_lig -->', $no_bt_sup_lig, $frame);
		}
	
	
		if ($error) {
			$frame = str_replace('<!-- error -->', "<script type='text/javascript'>alert(\"".$error_msg."\"); </script>", $frame);
		}
		print $frame; 	
	}
}  


//Affichage des lignes si erreur
function show_lig_bak() {
	
	global $msg, $charset;
	global $frame_modif, $frame_row, $frame_row_bl_header, $frame_row_bl, $bt_sup_lig, $no_bt_sup_lig;
	global $id_bibli, $id_cde, $id_liv;
	global $max_lig, $max_lig_liv;
	global $id_lig, $id_prod, $code, $lib, $sol, $rec;
	global $error, $error_msg;
	global $warning, $warning_msg;

	$frame = $frame_modif;
	
	$lig_aliv = array(); //Tableau des lignes à livrer
	$lig_dliv = array(); //Tableau des lignes déjà livrées
	$focus = 0;
	
	//Les lignes restant à livrer sont reprises telles quelles
	for($i=1;$i<=$max_lig;$i++) {
		
		$lig_aliv[$i]['id_lig'] = $id_lig[$i];
		$lig_aliv[$i]['id_prod']=$id_prod[$i];
		$lig_aliv[$i]['code']=htmlentities(stripslashes($code[$i]), ENT_QUOTES, $charset);
		$lig_aliv[$i]['lib']= htmlentities(stripslashes($lib[$i]), ENT_QUOTES, $charset);
		$lig_aliv[$i]['sol']= $sol[$i];
		$lig_aliv[$i]['rec']= $rec[$i];
	}
	
	//Les lignes déjà livrées sont reprises telles quelles
	for($i;$i<=$max_lig+$max_lig_liv;$i++) {
			
		$lig_dliv[$i]['id_lig'] = $id_lig[$i];
		$lig_dliv[$i]['id_prod']=$id_prod[$i];
		$lig_dliv[$i]['code']=htmlentities(stripslashes($code[$i]), ENT_QUOTES, $charset);
		$lig_dliv[$i]['lib']= htmlentities(stripslashes($lib[$i]), ENT_QUOTES, $charset);
		$lig_dliv[$i]['rec']= $rec[$i];
		
	}
	

	$index = 1;	
	$max_lig = count($lig_aliv);
	//Affichage des lignes restant à livrer
	foreach($lig_aliv as $key=>$value) {
				
		$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);
		$frame = str_replace('!!no!!', $index, $frame);
		$frame = str_replace('!!id_lig!!', $lig_aliv[$key]['id_lig'], $frame);
		$frame = str_replace('!!id_prod!!', $lig_aliv[$key]['id_prod'], $frame);
		$frame = str_replace('!!code!!', $lig_aliv[$key]['code'], $frame);
		$frame = str_replace('!!hidden_lib!!', $lig_aliv[$key]['lib'], $frame);
		$frame = str_replace('!!lib!!', nl2br($lig_aliv[$key]['lib']), $frame);
		$frame = str_replace('!!sol!!', $lig_aliv[$key]['sol'], $frame);
		$frame = str_replace('!!rec!!', $lig_aliv[$key]['rec'], $frame);		
		$index++;			
		
	}
	$frame = str_replace('!!max_lig!!', $max_lig, $frame);
	
	$max_lig_liv = count($lig_dliv);
	if ($max_lig_liv) {

		$frame = str_replace('<!-- lignes -->', $frame_row_bl_header.'<!-- lignes -->', $frame);
	
		//Affichage des lignes déjà livrées
		foreach($lig_dliv as $key=>$value) {
			
			$frame = str_replace('<!-- lignes -->', $frame_row_bl.'<!-- lignes -->', $frame);
			$frame = str_replace('!!no!!', $index, $frame);
			$frame = str_replace('!!id_lig!!', $lig_dliv[$key]['id_lig'], $frame);
			$frame = str_replace('!!id_prod!!', $lig_dliv[$key]['id_prod'], $frame);
			$frame = str_replace('!!code!!', $lig_dliv[$key]['code'], $frame);
			$frame = str_replace('!!hidden_lib!!', $lig_dliv[$key]['lib'], $frame);
			$frame = str_replace('!!lib!!', nl2br($lig_dliv[$key]['lib']), $frame);
			$frame = str_replace('!!rec!!', $lig_dliv[$key]['rec'], $frame);		
			$index++;
		}
	}
	$frame = str_replace('!!max_lig_liv!!', $max_lig_liv, $frame);

		
	if ($error) {
		$frame = str_replace('<!-- error -->', "<script type='text/javascript'>alert(\"".$error_msg."\"); </script>", $frame);
	}

	$frame = str_replace('!!id_liv!!', $id_liv, $frame);

	print $frame; 	
	
}



//test et formatage du code saisi 
function test_cb() {
	
	global $cb;
	global $barcode;
	
	$EAN = '';
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
function search_lig_liv() {
	
	global $msg, $charset;
	global $frame_modif, $frame_row, $frame_row_bl_header, $frame_row_bl, $bt_sup_lig, $no_bt_sup_lig;
	global $id_bibli, $id_cde, $id_liv, $auto;
	global $max_lig, $max_lig_liv;
	global $id_lig, $id_prod, $code, $lib, $sol, $rec;
	global $focus, $barcode;
	global $error, $error_msg;
	global $warning, $warning_msg;

	$frame = $frame_modif;
	
	$lig_aliv = array(); //Tableau des lignes à livrer
	$lig_dliv = array(); //Tableau des lignes déjà livrées
	$focus = 0;
	
	//Les lignes restant à livrer sont reprises telles quelles
	for($i=1;$i<=$max_lig;$i++) {
		
		$lig_aliv[$i]['id_lig'] = $id_lig[$i];
		$lig_aliv[$i]['id_prod']=$id_prod[$i];
		$lig_aliv[$i]['code']=htmlentities(stripslashes($code[$i]), ENT_QUOTES, $charset);
		$lig_aliv[$i]['lib']= htmlentities(stripslashes($lib[$i]), ENT_QUOTES, $charset);
		$lig_aliv[$i]['sol']= $sol[$i];
		$lig_aliv[$i]['rec']= $rec[$i];
	}
	
	//Les lignes déjà livrées sont reprises telles quelles
	for($i;$i<=$max_lig+$max_lig_liv;$i++) {
			
		$lig_dliv[$i]['id_lig'] = $id_lig[$i];
		$lig_dliv[$i]['id_prod']=$id_prod[$i];
		$lig_dliv[$i]['code']=htmlentities(stripslashes($code[$i]), ENT_QUOTES, $charset);
		$lig_dliv[$i]['lib']= htmlentities(stripslashes($lib[$i]), ENT_QUOTES, $charset);
		$lig_dliv[$i]['rec']= $rec[$i];
		
	}
	
	//recherche du code saisi
	$trouve = 0;
	$dep = false;
	foreach($lig_aliv as $key=>$value) {
		
		if($lig_aliv[$key]['code'] == $barcode) {	//Code trouvé

			$trouve = $key;

			if ($auto) {	//Mode incrément automatique
								
				if( ($lig_aliv[$key]['rec'] < $lig_aliv[$key]['sol']) ) {	
				
					//La qté saisie est inférieure à la qté restant à recevoir >> Incrément qté saisie et sortie
					$lig_aliv[$key]['rec'] = $lig_aliv[$key]['rec']+1;	
					$dep = false;
					break;
					
				} else {
					
					//La qté saisie est égale à la quantité restant à recevoir >> On note le dépassement et on recherche plus avant 
					$dep = true;
				
				}
			
			} else {		//Mode recherche

				if( ($lig_aliv[$key]['rec'] < $lig_aliv[$key]['sol']) ) {	
				
					//La qté saisie est inférieure à la qté restant à recevoir >> Sortie
					break;
					
				} 			
				//Sinon, si la quantité saisie est égale à la quantité restant à recevoir >> On recherche plus avant 
									
			}
			
		}	
				
	}


	$index = 1;	
	$max_lig = count($lig_aliv);
	//Affichage des lignes restant à livrer
	foreach($lig_aliv as $key=>$value) {
		
		if ($auto && ($trouve == $key)) {
			$focus = $index;
			if ($dep){
				$error = true;
				$error_msg = $msg['acquisition_lig'].' '.$index.': '.$msg['acquisition_qte_liv_sup'];
			}
		}			
		
		if (!$auto && ($trouve == $key)) {
			$focus = $index;
		}
				
		$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);
		$frame = str_replace('!!no!!', $index, $frame);
		$frame = str_replace('!!id_lig!!', $lig_aliv[$key]['id_lig'], $frame);
		$frame = str_replace('!!id_prod!!', $lig_aliv[$key]['id_prod'], $frame);
		$frame = str_replace('!!code!!', $lig_aliv[$key]['code'], $frame);
		$frame = str_replace('!!hidden_lib!!', $lig_aliv[$key]['lib'], $frame);
		$frame = str_replace('!!lib!!', nl2br($lig_aliv[$key]['lib']), $frame);
		$frame = str_replace('!!sol!!', $lig_aliv[$key]['sol'], $frame);
		$frame = str_replace('!!rec!!', $lig_aliv[$key]['rec'], $frame);		
		$index++;			
		
	}
	$frame = str_replace('!!max_lig!!', $max_lig, $frame);
	
	$max_lig_liv = count($lig_dliv);
	if ($max_lig_liv) {

		$frame = str_replace('<!-- lignes -->', $frame_row_bl_header.'<!-- lignes -->', $frame);
	
		//Affichage des lignes déjà livrées
		foreach($lig_dliv as $key=>$value) {
			
			$frame = str_replace('<!-- lignes -->', $frame_row_bl.'<!-- lignes -->', $frame);
			$frame = str_replace('!!no!!', $index, $frame);
			$frame = str_replace('!!id_lig!!', $lig_dliv[$key]['id_lig'], $frame);
			$frame = str_replace('!!id_prod!!', $lig_dliv[$key]['id_prod'], $frame);
			$frame = str_replace('!!code!!', $lig_dliv[$key]['code'], $frame);
			$frame = str_replace('!!hidden_lib!!', $lig_dliv[$key]['lib'], $frame);
			$frame = str_replace('!!lib!!', nl2br($lig_dliv[$key]['lib']), $frame);
			$frame = str_replace('!!rec!!', $lig_dliv[$key]['rec'], $frame);		
			$index++;
		}
	}
	$frame = str_replace('!!max_lig_liv!!', $max_lig_liv, $frame);


	//Mise en place focus si code saisi trouvé
	if ($focus) {
		if(!$auto) {
			$focus = "<script type='text/javascript'>window.location.hash= '#ancre[".$focus."]';f=document.getElementById('rec[".$focus."]');f.focus();</script>";
		} else {
			$focus = "<script type='text/javascript'>window.parent.document.getElementById('cb').focus();</script>";
		}			
	} else { 
		$focus = "<script type='text/javascript'>alert('".$msg['acquisition_liv_code_inex']."'); window.parent.document.getElementById('cb').focus();</script>";
	}
	
	$frame = str_replace('<!-- focus -->', $focus, $frame);
	
		
	if ($error) {
		$frame = str_replace('<!-- error -->', "<script type='text/javascript'>alert(\"".$error_msg."\"); </script>", $frame);
	}

	$frame = str_replace('!!id_liv!!', $id_liv, $frame);

	print $frame; 	
	
}


function sup_lig_liv() {

	global $msg, $charset;
	global $frame_modif, $frame_row, $frame_row_bl_header, $frame_row_bl, $bt_sup_lig, $no_bt_sup_lig;
	global $id_bibli, $id_cde, $id_liv;
	global $max_lig, $max_lig_liv;
	global $chk, $id_lig, $id_prod, $code, $lib, $sol, $rec;
	
	$frame = $frame_modif;
	
	$lig_aliv = array(); //Tableau des lignes à livrer
	$lig_dliv = array(); //Tableau des lignes déjà livrées
	
	//Les lignes restant à livrer sont reprises telles quelles
	for($i=1;$i<=$max_lig;$i++) {
		
		$lig_aliv[$i]['id_lig'] = $id_lig[$i];
		$lig_aliv[$i]['id_prod']=$id_prod[$i];
		$lig_aliv[$i]['code']=htmlentities(stripslashes($code[$i]), ENT_QUOTES, $charset);
		$lig_aliv[$i]['lib']= htmlentities(stripslashes($lib[$i]), ENT_QUOTES, $charset);
		$lig_aliv[$i]['sol']= $sol[$i];
		$lig_aliv[$i]['rec']= $rec[$i];
	}
	
	//La quantite recue des lignes supprimées est reportée dans le solde des lignes restant à livrer si l'identifiant de ligne existe
	//Sinon, une nouvelle ligne restant à livrer est créée
	for($i;$i<=$max_lig+$max_lig_liv;$i++) {

		if ($chk[$i]) {	//La ligne est cochée pour suppression
			
			$ral = false;
			for($j=1;$j<=$max_lig; $j++) {	//Y avait-il un solde à livrer
			
				if($lig_aliv[$j]['id_lig'] == $id_lig[$i]) {	//Si oui, on rajoute le reçu de la ligne supprimée au solde à livrer
					$lig_aliv[$j]['sol'] = $lig_aliv[$j]['sol']+$rec[$i];
					$ral = true;
					break;
				} 
			}
			if (!$ral) {	//Il n'y avait pas de reste à livrer, on recrée la ligne dans le tableau des restant à livrer
				
				$lig_aliv[$i]['id_lig'] = $id_lig[$i];
				$lig_aliv[$i]['id_prod']=$id_prod[$i];
				$lig_aliv[$i]['code']=htmlentities(stripslashes($code[$i]), ENT_QUOTES, $charset);
				$lig_aliv[$i]['lib']= htmlentities(stripslashes($lib[$i]), ENT_QUOTES, $charset);
				$lig_aliv[$i]['sol']= $rec[$i];
				$lig_aliv[$i]['rec']= '0';
				
			}
			
		} else {	//La ligne n'est pas cochée pour suppression, on la conserve dans les déjà livrées
		
			$lig_dliv[$i]['id_lig'] = $id_lig[$i];
			$lig_dliv[$i]['id_prod']=$id_prod[$i];
			$lig_dliv[$i]['code']=htmlentities(stripslashes($code[$i]), ENT_QUOTES, $charset);
			$lig_dliv[$i]['lib']= htmlentities(stripslashes($lib[$i]), ENT_QUOTES, $charset);
			$lig_dliv[$i]['rec']= $rec[$i];
		
		}
	}
	

	
	$index = 1;	
	$max_lig = count($lig_aliv);
	//Affichage des lignes restant à livrer
	foreach($lig_aliv as $key=>$value) {
		
		$frame = str_replace('<!-- lignes -->', $frame_row.'<!-- lignes -->', $frame);
		$frame = str_replace('!!no!!', $index, $frame);
		$frame = str_replace('!!id_lig!!', $lig_aliv[$key]['id_lig'], $frame);
		$frame = str_replace('!!id_prod!!', $lig_aliv[$key]['id_prod'], $frame);
		$frame = str_replace('!!code!!', $lig_aliv[$key]['code'], $frame);
		$frame = str_replace('!!hidden_lib!!', $lig_aliv[$key]['lib'], $frame);
		$frame = str_replace('!!lib!!', nl2br($lig_aliv[$key]['lib']), $frame);
		$frame = str_replace('!!sol!!', $lig_aliv[$key]['sol'], $frame);
		$frame = str_replace('!!rec!!', $lig_aliv[$key]['rec'], $frame);		
		$index++;
	}
	$frame = str_replace('!!max_lig!!', $max_lig, $frame);
	
	$max_lig_liv = count($lig_dliv);
	if ($max_lig_liv) {

		$frame = str_replace('<!-- lignes -->', $frame_row_bl_header.'<!-- lignes -->', $frame);
	
		//Affichage des lignes déjà livrées
		foreach($lig_dliv as $key=>$value) {
			
			$frame = str_replace('<!-- lignes -->', $frame_row_bl.'<!-- lignes -->', $frame);
			$frame = str_replace('!!no!!', $index, $frame);
			$frame = str_replace('!!id_lig!!', $lig_dliv[$key]['id_lig'], $frame);
			$frame = str_replace('!!id_prod!!', $lig_dliv[$key]['id_prod'], $frame);
			$frame = str_replace('!!code!!', $lig_dliv[$key]['code'], $frame);
			$frame = str_replace('!!hidden_lib!!', $lig_dliv[$key]['lib'], $frame);
			$frame = str_replace('!!lib!!', nl2br($lig_dliv[$key]['lib']), $frame);
			$frame = str_replace('!!rec!!', $lig_dliv[$key]['rec'], $frame);		
			$index++;
		}
	}
	$frame = str_replace('!!max_lig_liv!!', $max_lig_liv, $frame);
	
	$frame = str_replace('!!id_liv!!', $id_liv, $frame);
	
	print $frame;		
} 


//Enregistre le bon de livraison
function update_liv() {
	
	global $id_bibli, $id_cde, $id_liv, $comment, $ref;
	global $max_lig, $max_lig_liv, $id_lig, $id_prod, $code, $rec;
	global $acquisition_email_sugg;
		
	$tab_liv = array(); //Tableau des lignes livrées
	
	//Les lignes restant à livrer sont reprises dans la tableau si la qté reçue est >0
	for($i=1;$i<=$max_lig;$i++) {
		
		if ($rec[$i]) {
			$tab_liv[$i]['id_lig'] = $id_lig[$i];
			$tab_liv[$i]['rec']= $rec[$i];
		}
	}

	//Les quantités recues des lignes déjà livrées sont reportées dans le tableau si l'identifiant de ligne existe
	//Sinon une nouvelle ligne est créée 
	for($i;$i<=$max_lig+$max_lig_liv;$i++) {

		$deja = false;
		for($j=1;$j<=$max_lig; $j++) {	//Y a-t'il une ligne deja créée

			if($tab_liv[$j]['id_lig'] == $id_lig[$i]) {	//Si oui, on rajoute la quantité reçue dans le tableau
				$tab_liv[$j]['rec'] = $tab_liv[$j]['rec']+$rec[$i];
				$deja = true;
				break;
			} 
		}
		if (!$deja) {	//Sinon, on crée la ligne dans le tableau 
			$tab_liv[$i]['id_lig'] = $id_lig[$i];
			$tab_liv[$i]['rec']= $rec[$i];
			
		}		
	}
	
	//Récupération de la commande
	$cde = new actes($id_cde);
	
	if (!$id_liv) {	//Création du bon de livraison

		$liv = new actes();
		$liv->date_acte = today();
		$liv->type_acte = '2';
		$liv->statut = '4';		//Statut BL = recu
		$liv->num_entite = $cde->num_entite;
		$liv->num_fournisseur = $cde->num_fournisseur;
		$liv->num_contact_livr = $cde->num_contact_livr;
		$liv->num_contact_fact = $cde->num_contact_fact;
		$liv->num_exercice = $cde->num_exercice;
		$liv->commentaires = $comment;
		$liv->reference = $ref;
		$liv->calc();
		$liv->save();
		
		$id_liv = $liv->id_acte;
		//création des liens entre actes
		$la = new liens_actes($id_cde, $id_liv);
	
	} else {	//Modification du bon de livraison

		$liv = new actes($id_liv);
		$liv->numero=addslashes($liv->numero);
		$liv->commentaires = trim($comment);
		$liv->reference = trim($ref);
		$liv->save();
		
	}
	
	//Suppression des lignes de livraisons précédemment enregistrées
	actes::deleteLignes($id_liv);

	$sug_map = new suggestions_map();

	//Création des lignes de livraison
	foreach ($tab_liv as $key=>$value) {

		$lig_cde = new lignes_actes($tab_liv[$key]['id_lig']);
		
		
		$lig_liv = new lignes_actes();	
		$lig_liv->num_acte = $liv->id_acte;
		$lig_liv->lig_ref = $lig_cde->id_ligne;
		$lig_liv->num_acquisition = $lig_cde->num_acquisition; 				
		$lig_liv->num_rubrique = $lig_cde->num_rubrique;
		$lig_liv->num_produit = $lig_cde->num_produit;
		$lig_liv->num_type = $lig_cde->num_type;
		$lig_liv->libelle = addslashes($lig_cde->libelle);
		$lig_liv->code = addslashes($lig_cde->code);
		$lig_liv->prix = $lig_cde->prix;
		$lig_liv->tva = $lig_cde->tva;
		$lig_liv->remise = $lig_cde->remise;
		$lig_liv->nb = $tab_liv[$key]['rec'];
		$lig_liv->date_cre = today();
		$lig_liv->save();		

		if ( $lig_cde->num_acquisition != 0 ) {
			$sug = array();
			$sug[] = $lig_cde->num_acquisition;
			$sug_map->doTransition('RECEIVED', $sug);						
		}

	}

	//La commande est-elle soldée
	$tab_cde = actes::getLignes($id_cde);
	$solde = true;
	while (($row_cde = mysql_fetch_object($tab_cde))) {
		
		if ($row_cde->type_ligne == 3) {	// Frais, non livrables
			
		} else {
	

			$tab_liv = lignes_actes::getLivraisons($row_cde->id_ligne);
			$nb_liv = 0;
			while (($row_liv = mysql_fetch_object($tab_liv))) {
				$nb_liv = $nb_liv + $row_liv->nb;
			}
			
		
			if ($row_cde->nb > $nb_liv) {
				$solde = false;
				break;
			}
		
		}		
		
	}
	
	if ($solde) {
		$cde->statut = ($cde->statut & (~2) | 4); // Cde soldée >> Statut commande = en cours->soldé
	}
	$cde->update_statut();

	
}  



// gestion des achats : livraisons

switch($action) {

	case 'from_cde' :
		show_lig_from_cde();
		break;		

	case 'show_lig' :
		show_lig_liv();
		break;

	case 'search' :
		test_cb();
		search_lig_liv();
		break;

	case 'sup_lig' :
		sup_lig_liv();
		break;

	case 'update' :
		verif_liv();
		if ($error) {
			show_lig_bak();
			break;			
		} else {
			update_liv();
			print $retour_liste;
		}	
		break;

	default:
		break;
		
}

?>
