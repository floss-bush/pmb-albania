<?php
// +-------------------------------------------------+
// Â© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: retours.inc.php,v 1.5 2010-02-22 13:40:49 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// Titre de la fenêtre
echo window_title($database_window_title.$msg[transferts_circ_menu_retour].$msg[1003].$msg[1001]);

//creation de l'objet transfert
$obj_transfert = new transfert();

switch ($action) {
	case "aff_ret":
		//on affiche l'écran de validation
		echo "<h1>" . $msg[transferts_circ_menu_titre] . " > " . $msg[transferts_circ_menu_retour] . "</h1>";
		echo "<div class='row' />";
		echo affiche_liste_valide(
								$transferts_retour_liste_valide,
								$transferts_retour_liste_valide_ligne,
								"SELECT num_notice, num_bulletin, " .
									"expl_cb as val_ex,lender_libelle, transferts.date_retour as val_date_retour, " .
									"date_reception as val_date_reception, location_libelle as val_dest " .
								"FROM transferts " .
									"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
									"INNER JOIN exemplaires ON num_expl=expl_id " .
									"INNER JOIN lenders ON idlender=expl_owner " .
									"INNER JOIN docs_location ON num_location_source=idlocation " .
								"WHERE ".
									"id_transfert IN (!!liste_numeros!!) ".
									"AND etat_demande=3",
								"circ.php?categ=trans&sub=". $sub
								);
		break;
	case "ret":
		//on enregistre les validations des exemplaires sélectionnés
		$obj_transfert->enregistre_retour($liste_transfert);
		$action="";
		break;
}

if ($action == "") {
	//pas d'action donc affichage de la liste des validations en attente

	get_cb_expl($msg[transferts_circ_menu_titre]." > ".$msg[transferts_circ_menu_retour],
					$msg[661], $msg[transferts_circ_retour_exemplaire], "./circ.php?categ=trans&sub=".$sub."&f_destination=".$f_destination."&nb_per_page=".$nb_per_page, 0);
	echo "<div class='row' />";

	//pour la validation d'un exemplaire
	if ($form_cb_expl != "") {
		
		//enregistre l'acceptation du transfert
		$res_val = $obj_transfert->enregistre_retour_cb($form_cb_expl);
		
		if ($res_val==false) {
			// la validation ne s'est pas faite !
			echo $transferts_retour_acceptation_erreur;
		} else {
			// la validation est faite
			$aff=str_replace("!!cb_expl!!", $form_cb_expl,$transferts_retour_acceptation_OK);
			echo str_replace("!!new_location!!", $obj_transfert->new_location_libelle,$aff);
		}
	} 
	
	//le filtre des destinations
	$filtres = "&nbsp;".$msg["transferts_circ_retour_filtre_destination"].str_replace("!!nom_liste!!","f_destination",$transferts_liste_localisations_tous);
	$filtres = str_replace("!!liste_localisations!!", do_liste_localisation($f_destination), $filtres);
	
	//le filtre de l'etat de la date
	$filtres .= str_replace("!!sel_" . $f_etat_date . "!!", "selected", $transferts_retour_filtre_etat);
		
	// la fin de la requete d'affichage
	$req =	"FROM transferts " .
				"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
				"INNER JOIN exemplaires ON num_expl=expl_id " .
				"INNER JOIN lenders ON idlender=expl_owner " .
				"INNER JOIN docs_location ON num_location_source=idlocation " .
			"WHERE etat_transfert=0 " . //pas fini
				"AND type_transfert=1 " . //Aller-retour
				"AND etat_demande=3 " . //Aller fini
				"AND num_location_dest=".$deflt_docs_location; //pour le site de l'utilisateur
	
	$req.=	" AND num_expl not in (select num_expl from transferts_demande,transferts WHERE id_transfert=num_transfert and etat_transfert=0 AND etat_demande=1 )";
	//l'url pour accéder a l'edition
	$url_edition = "./edit.php?categ=transferts&sub=retours";
	
	//application du filtre sur la destination
	if ($f_destination) {
		$req .= " AND num_location_source=".$f_destination;
		$url_edition .= "&site_destination=" .$f_destination;
	}
	
	//application du filtre sur la date de retour
	switch ($f_etat_date) {
		case "1":
			$req .= " AND (DATEDIFF(DATE_ADD(date_retour,INTERVAL -" . $transferts_nb_jours_alerte . " DAY),CURDATE())<=0";
			$req .= " AND DATEDIFF(date_retour,CURDATE())>=0)";
			$url_edition .= "&f_etat_date=" .$f_etat_date;
			break;
		case "2":
			$req .= " AND DATEDIFF(date_retour,CURDATE())<0";
			$url_edition .= "&f_etat_date=" .$f_etat_date;
			break;
	
	}
	
	//fin de la requete
	$req .= " ORDER BY transferts.date_retour ASC";
	
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
				"expl_cb as val_ex, lender_libelle, transferts.date_retour as val_date_retour, " .
				"date_reception as val_date_reception, location_libelle as val_dest ",
			$req, 
			$nb_per_page,
			$transferts_retour_form_global,
			$transferts_retour_tableau_definition,
			$transferts_retour_tableau_ligne,
			$transferts_retour_boutons_action,
			$transferts_retour_pas_de_resultats,
			$lien_edition,
			$filtres,
			"&f_destination=".$f_destination 
		);
}

?>