<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: envoi.inc.php,v 1.4 2010-02-22 13:40:49 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


// Titre de la fenetre
echo window_title($database_window_title.$msg[transferts_circ_menu_envoi].$msg[1003].$msg[1001]);

//creation de l'objet transfert
$obj_transfert = new transfert();

switch ($action) {
	
	case "aff_env":
		echo "<h1>" . $msg[transferts_circ_menu_titre] . " > " . $msg[transferts_circ_menu_envoi] . "</h1>";
		echo "<div class='row' />";
		echo affiche_liste_valide(
								$transferts_envoi_liste_valide_envoi,
								$transferts_envoi_liste_valide_envoi_ligne,
								"SELECT num_notice, num_bulletin, " .
									"expl_cb as val_ex, lender_libelle, transferts.date_creation as val_date_creation, " .
									"date_visualisee as val_date_accepte, location_libelle as val_dest  " .
								"FROM transferts " .
									"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
									"INNER JOIN exemplaires ON num_expl=expl_id " .
									"INNER JOIN lenders ON idlender=expl_owner " .
									"INNER JOIN docs_location ON num_location_dest=idlocation " .
								"WHERE ".
									"id_transfert IN (!!liste_numeros!!) ".
									"AND etat_demande=1",
								"circ.php?categ=trans&sub=". $sub
								);
		break;
	case "env":
		//on valide les envois
		$obj_transfert->enregistre_envoi($liste_transfert);
		//on affiche l'ecran principal
		$action = "";
		break;

	case "aff_refus":
		//on affiche l'écran de saisie du refus
		echo "<h1>" . $msg[transferts_circ_menu_titre] . " > " . $msg[transferts_circ_menu_envoi] . "</h1>";
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
									"AND etat_demande=1",
								"circ.php?categ=trans&sub=". $sub
								);
		break;
	case "refus":
		//on enregistre les refus
		$obj_transfert->enregistre_refus($liste_transfert,$motif_refus);
		$action="";
		break;
}

if ($action=="") {

	get_cb_expl($msg[transferts_circ_menu_titre]." > ".$msg[transferts_circ_menu_envoi],
					$msg[661], $msg[transferts_circ_envoi_exemplaire], "./circ.php?categ=trans&sub=".$sub."&f_destination=".$f_destination."&nb_per_page=".$nb_per_page, 0);
	
	echo "<div class='row' />";

	if ($form_cb_expl != "") {
		//enregistrement de l'envoi
		$res_env = $obj_transfert->enregistre_envoi_cb($form_cb_expl);

		if ($res_env==false) {
			// l'envoi n'est pas valide
			echo $transferts_envoi_erreur;
		} else {
			// l'envoi est fait
			$aff=str_replace("!!cb_expl!!", $form_cb_expl,$transferts_envoi_OK);
			echo str_replace("!!new_location!!", $obj_transfert->new_location_libelle,$aff);
		}
	}
	
	$filtres = "&nbsp;".$msg["transferts_circ_envoi_filtre_destination"].str_replace("!!nom_liste!!","f_destination",$transferts_liste_localisations_tous);
	$filtres = str_replace("!!liste_localisations!!", do_liste_localisation($f_destination), $filtres);
	
	if ($transferts_validation_actif=="1")
		$req =	"FROM transferts " .
					"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
					"INNER JOIN exemplaires ON num_expl=expl_id " .
					"INNER JOIN lenders ON idlender=expl_owner " .
					"INNER JOIN docs_location ON num_location_dest=idlocation " .
				"WHERE etat_transfert=0 " . //pas fini
					"AND etat_demande=1 " . //validé
					"AND num_location_source=".$deflt_docs_location; //pour le site de l'utilisateur
	else
		$req =	"FROM transferts " .
					"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
					"INNER JOIN exemplaires ON num_expl=expl_id " .
					"INNER JOIN lenders ON idlender=expl_owner " .
					"INNER JOIN docs_location ON num_location_dest=idlocation " .
				"WHERE etat_transfert=0 " . //pas fini
					"AND (etat_demande=0 " . //pas validé
					"OR etat_demande=1) " . //validé
					"AND num_location_source=".$deflt_docs_location; //pour le site de l'utilisateur

	//pour l'edition de la liste
	$url_edition = "./edit.php?categ=transferts&sub=envoi";
	
	//on applique la seletion du filtre
	if ($f_destination) {
		$req .= " AND num_location_dest=".$f_destination;
		$url_edition .= "&site_destination=" .$f_destination;
	}
	
	//le lien pour l'édition si on a le droit ...
	if (SESSrights & EDIT_AUTH)
		$lien_edition = "<a href='" . $url_edition . "'>".$msg[1100]."</a>";
	else
		$lien_edition = "";
	//on affiche la liste
	echo affiche_liste(
		$sub,
		$page,
		"SELECT num_notice, num_bulletin, id_transfert as val_id, " .
			"expl_cb as val_ex, lender_libelle, transferts.date_creation as val_date_creation, " .
			"date_visualisee as val_date_accepte, location_libelle as val_dest ",
		$req,
		$nb_per_page,
		$transferts_envoi_form_global,
		$transferts_envoi_tableau_definition,
		$transferts_envoi_tableau_ligne,
		$transferts_envoi_boutons_action,
		$transferts_envoi_pas_de_resultats,
		$lien_edition,
		$filtres,
		"&f_destination=".$f_destination  
		);

}
?>
