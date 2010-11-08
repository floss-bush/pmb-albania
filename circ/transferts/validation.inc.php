<?php
// +-------------------------------------------------+
// Â© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: validation.inc.php,v 1.4 2010-02-22 13:40:49 ngantier Exp $


if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// Titre de la fenêtre
echo window_title($database_window_title.$msg[transferts_circ_menu_validation].$msg[1003].$msg[1001]);

//creation de l'objet transfert
$obj_transfert = new transfert();

switch ($action) {
	case "aff_val":
		//on affiche l'écran de validation
		echo "<h1>" . $msg[transferts_circ_menu_titre] . " > " . $msg[transferts_circ_menu_validation] . "</h1>";
		echo "<div class='row' />";
		echo affiche_liste_valide(
								$transferts_validation_liste_valide,
								$transferts_validation_liste_valide_ligne,
								"SELECT num_notice, num_bulletin, " .
									"expl_cb as val_ex, lender_libelle, transferts.date_creation as val_date_creation, " .
									"motif as val_motif, location_libelle as val_dest  " .
								"FROM transferts " .
									"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
									"INNER JOIN exemplaires ON num_expl=expl_id " .
									"INNER JOIN lenders ON idlender=expl_owner " .
									"INNER JOIN docs_location ON num_location_dest=idlocation " .
								"WHERE ".
									"id_transfert IN (!!liste_numeros!!) ".
									"AND etat_demande=0",
								"circ.php?categ=trans&sub=". $sub
								);
		break;
	case "val":
		//on enregistre les validations des exemplaires sélectionnés
		$obj_transfert->enregistre_validation($liste_transfert);
		$action="";
		break;
	case "aff_refus":
		//on affiche l'écran de saisie du refus
		echo "<h1>" . $msg[transferts_circ_menu_titre] . " > " . $msg[transferts_circ_menu_validation] . "</h1>";
		echo "<div class='row' />";
		echo affiche_liste_valide(
								$transferts_validation_liste_refus,
								$transferts_validation_liste_valide_ligne,
								"SELECT num_notice, num_bulletin, " .
									"expl_cb as val_ex, lender_libelle, transferts.date_creation as val_date_creation, " .
									"motif as val_motif, location_libelle as val_dest  " .
								"FROM transferts " .
									"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
									"INNER JOIN exemplaires ON num_expl=expl_id " .
									"INNER JOIN lenders ON idlender=expl_owner " .
									"INNER JOIN docs_location ON num_location_dest=idlocation " .
								"WHERE ".
									"id_transfert IN (!!liste_numeros!!) ".
									"AND etat_demande=0",
								"circ.php?categ=trans&sub=". $sub
								);
		break;
	case "refus":
		//on enregistre les refus
		$obj_transfert->enregistre_refus($liste_transfert,$motif_refus);
		$action="";
		break;
}

if ($action == "") {
	//pas d'action donc affichage de la liste des validations en attente

	get_cb_expl($msg[transferts_circ_menu_titre]." > ".$msg[transferts_circ_menu_validation],
					$msg[661], $msg[transferts_circ_validation_exemplaire], "./circ.php?categ=trans&sub=".$sub."&f_destination=".$f_destination."&nb_per_page=".$nb_per_page, 0);
	echo "<div class='row' />";

	//pour la validation d'un exemplaire
	if ($form_cb_expl != "") {
		
		//enregistre l'acceptation du transfert
		$res_val = $obj_transfert->enregistre_validation_cb($form_cb_expl);
		
		if ($res_val==false) {
			// la validation ne s'est pas faite !
			echo $transferts_validation_acceptation_erreur;
		} else {
			// la validation est faite
			$aff=str_replace("!!cb_expl!!", $form_cb_expl,$transferts_validation_acceptation_OK);
			echo str_replace("!!new_location!!", $obj_transfert->new_location_libelle,$aff);
		}
	} 

	$filtres = "&nbsp;".$msg["transferts_circ_validation_filtre_destination"].str_replace("!!nom_liste!!","f_destination",$transferts_liste_localisations_tous);
	$filtres = str_replace("!!liste_localisations!!", do_liste_localisation($f_destination), $filtres);

	$req =	"FROM transferts " .
				"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
				"INNER JOIN exemplaires ON num_expl=expl_id " .
				"INNER JOIN lenders ON idlender=expl_owner " .
				"INNER JOIN docs_location ON num_location_dest=idlocation " .
			"WHERE etat_transfert=0 " . //pas fini
				"AND etat_demande=0 " . //pas validé
				"AND num_location_source=".$deflt_docs_location; //pour le site de l'utilisateur
	
	$url_edition = "./edit.php?categ=transferts&sub=validation";
	
	// si une destination est sélectionnée
	if ($f_destination) {
		$req .= " AND num_location_dest=".$f_destination;
		$url_edition .= "&site_destination=" .$f_destination;
	}
	
	//le lien pour l'édition si on a le droit ...
	if (SESSrights & EDIT_AUTH)
		$lien_edition = "<a href='" . $url_edition . "'>".$msg[1100]."</a>";
	else
		$lien_edition = "";
	
	//on affihce la liste
	echo affiche_liste(
			$sub,
			$page,
			"SELECT num_notice, num_bulletin, ".
				"id_transfert as val_id, " .
				"expl_cb as val_ex, lender_libelle, transferts.date_creation as val_date_creation, " .
				"motif as val_motif, location_libelle as val_dest ",
			$req,
			$nb_per_page,
			$transferts_validation_form_global,
			$transferts_validation_tableau_definition,
			$transferts_validation_tableau_ligne,
			$transferts_validation_boutons_action,
			$transferts_validation_pas_de_resultats,
			$lien_edition,
			$filtres,
			"&f_destination=".$f_destination 
		);
}

?>
