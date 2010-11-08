<?php
// +-------------------------------------------------+
// Â© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: refuse.inc.php,v 1.3 2010-02-22 13:40:49 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// Titre de la fenêtre
echo window_title($database_window_title.$msg[transferts_circ_menu_refuse].$msg[1003].$msg[1001]);

//creation de l'objet transfert
$obj_transfert = new transfert();

switch ($action) {

	case "aff_supp":
		//on affiche l'écran de validation de suppression
		echo "<h1>" . $msg[transferts_circ_menu_titre] . " > " . $msg[transferts_circ_menu_refuse] . "</h1>";
		echo "<div class='row' />";
		echo affiche_liste_valide(
				$transferts_refus_liste_valide,
				$transferts_refus_liste_valide_ligne,
				"SELECT num_notice, num_bulletin, " .
					"expl_cb as val_ex, lender_libelle, location_libelle as val_source, " .
					"transferts_demande.date_creation as val_date_creation, date_visualisee as val_date_refus," .
					"motif_refus as val_refusMotif ".
				"FROM transferts " .
					"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
					"INNER JOIN exemplaires ON num_expl=expl_id " .
									"INNER JOIN lenders ON idlender=expl_owner " .
					"INNER JOIN docs_location ON num_location_source=idlocation " .
				"WHERE ".
					"id_transfert IN (!!liste_numeros!!) ".
					"AND etat_demande=4",
				"circ.php?categ=trans&sub=".$sub
				);
		break;

	case "supp":
		//on supprime les transferts sélectionner
		$obj_transfert->cloture_transferts($liste_transfert);
		$action="";
		break;

	case "aff_redem" :
		//affiche l'ecran pour proposer de relancer une nouvelle demande de transfert
		echo "<h1>" . $msg[transferts_circ_menu_titre] . " > " . $msg[transferts_circ_menu_refuse] . "</h1>";
		echo "<div class='row' />";

		//on recupere les id de l'exemplaire
		$idNotice = pmb_sql_value("SELECT num_notice FROM transferts WHERE id_transfert=".$transid);
		$idBulletin = pmb_sql_value("SELECT num_bulletin FROM transferts WHERE id_transfert=".$transid);
		
		//on genere la liste des sites ou un exemplaire est disponible
		$rqt = "SELECT DISTINCT expl_location,location_libelle " .
				"FROM exemplaires " .
					"INNER JOIN docs_location ON expl_location=idlocation " .
				"WHERE " .
					"expl_notice=".$idNotice." ".
					"AND expl_bulletin=".$idBulletin." ".
				"ORDER BY ".
					"transfert_ordre";
		$res = mysql_query($rqt);
		$tmpOpt = "";
		while ($value = mysql_fetch_array($res)) {
			$tmpOpt .= "<option value='" . $value[0] . "'>" . $value[1] . "</option>";
		}
		$tmpString = str_replace("!!liste_sites!!",$tmpOpt,$transferts_refus_redemande_global);

		//le titre
		$tmpString = str_replace("!!detail_notice!!",aff_titre($idNotice,$idBulletin),$tmpString);

		//l'action du formulaire
		$tmpString = str_replace("!!action_formulaire!!","circ.php?categ=trans&sub=". $sub,$tmpString);

		//on y met la date de pret par defaut
		$date_pret = mktime(0, 0, 0, date("m"), date("d")+$transferts_nb_jours_pret_defaut, date("Y"));
		$date_pret_aff = date("Y-m-d", $date_pret);
		$tmpString = str_replace("!!date_retour_mysql!!", $date_pret_aff, $tmpString);
		$date_pret_aff = date("d/m/Y", $date_pret);
		$tmpString = str_replace("!!date_retour!!", $date_pret_aff, $tmpString);

		//l'id de la transaction
		$tmpString = str_replace("!!trans_id!!",$transid,$tmpString);
		
		echo pmb_bidi($tmpString);
	
		break;

	case "redem":
		//enregistre la nouvelle demande
		//transfert::creer_transfert(2, "", $id_expl, 1, $dest_id, $date_retour, $motif);
		$obj_transfert->ajoute_demande($transid,$source,$motif,$date_retour);
		$action = "";
		break;
}

if ($action == "") {
	//pas d'action donc affichage de la liste des transferts refusés

	echo "<h1>" . $msg[transferts_circ_menu_titre] . " > " . $msg[transferts_circ_menu_refuse] . "</h1>";
	echo "<div class='row' />";
	
	$filtres = "&nbsp;".$msg["transferts_circ_reception_filtre_source"].str_replace("!!nom_liste!!","f_source",$transferts_liste_localisations_tous);
	$filtres = str_replace("!!liste_localisations!!", do_liste_localisation($f_source), $filtres);
	
	
	$req =	"FROM transferts " .
				"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
				"INNER JOIN exemplaires ON num_expl=expl_id " .
				"INNER JOIN lenders ON idlender=expl_owner " .
				"INNER JOIN docs_location ON num_location_source=idlocation " .
			"WHERE etat_transfert=0 " . //pas fini
				"AND type_transfert=1 " . //aller-retour
				"AND etat_demande=4 " . //Refus
				"AND num_location_dest=".$deflt_docs_location; //pour le site de l'utilisateur
	
	if ($f_source)
		$req .= " AND num_location_source=".$f_source;
	
	
	echo affiche_liste(
			$sub,
			$page,
			"SELECT num_notice, num_bulletin, id_transfert as val_id, " .
				"expl_cb as val_ex, lender_libelle, location_libelle as val_source, " .
				"transferts_demande.date_creation as val_date_creation, date_visualisee as val_date_refus," .
				"motif_refus as val_refusMotif ",
			$req,
			$nb_per_page,
			$transferts_refus_form_global,
			$transferts_refus_tableau_definition,
			$transferts_refus_tableau_ligne,
			$transferts_refus_boutons_action,
			$transferts_refus_pas_de_resultats,
			"",
			$filtres,
			"&f_source=".$f_source 
		);
}

?>
