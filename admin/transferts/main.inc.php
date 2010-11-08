<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.6 2010-01-21 16:22:36 ngantier Exp $


if (stristr ( $_SERVER ['REQUEST_URI'], ".inc.php" ))
	die ( "no access" );

require_once ($class_path . "/transfert.class.php");
require_once ($base_path . "/admin/transferts/affichage.inc.php");

//description des parametres generaux
$tab_param_general = array (
						array (
							"prefix" => "transferts", 
							"nom" => "validation_actif", 
							"lib" => $msg ["admin_transferts_lib_active_validation"], 
							"champ" => "adminTransValidation", 
							"type" => "select", 
							"val" => array (
										array ("valeur" => "0", "lib" => $msg ["39"] ), 
										array ("valeur" => "1", "lib" => $msg ["40"] ) 
										) 
						), 
						array (
							"prefix" => "transferts", 
							"nom" => "nb_jours_pret_defaut", 
							"lib" => $msg ["admin_transferts_lib_nb_jours_pret"], 
							"champ" => "adminTransNbJours", 
							"type" => "text", 
							"params" => "size='2' maxlength='3'"
						), 
						array (
							"prefix" => "transferts", 
							"nom" => "statut_validation", 
							"lib" => $msg ["admin_transferts_lib_statut_validation"], 
							"champ" => "adminTransStatValidation", 
							"type" => "select", 
							"val" => array (
										array (
											"valeur" => "0", 
											"lib" => $msg ["admin_transferts_lib_statut_validation_pas_chg"] ), 
										array (
											"liste" => "SELECT idstatut, statut_libelle FROM docs_statut",
											"affichage" => "SELECT statut_libelle FROM docs_statut WHERE idstatut=!!id!!" )
									), 
						), 
						array (
							"prefix" => "transferts", 
							"nom" => "statut_transferts", 
							"lib" => $msg ["admin_transferts_lib_statut_transfert"], 
							"champ" => "adminTransStatTransfert", 
							"type" => "select", 
							"val" => array (
										array(
											"liste" => "SELECT idstatut, statut_libelle FROM docs_statut",
											"affichage" => "SELECT statut_libelle FROM docs_statut WHERE idstatut=!!id!!"
											)
									),
							
						) 
					);

//description des parametres en circulation
$tab_param_circ = array (
					array (
						"prefix" => "transferts", 
						"nom" => "tableau_nb_lignes", 
						"lib" => $msg ["admin_transferts_lib_nb_lignes"], 
						"champ" => "adminTransNbLignes",
						"type" => "text", 
						"params" => "size='2' maxlength='3'" 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "nb_jours_alerte", 
						"lib" => $msg ["admin_transferts_lib_nb_jours_alerte"], 
						"champ" => "adminTransNbJourAlert",
						"type" => "text", 
						"params" => "size='2' maxlength='3'" 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "envoi_lot", 
						"lib" => $msg ["admin_transferts_lib_envoi_lot"], 
						"champ" => "adminTransEnvoiLot", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["39"] ), 
									array ("valeur" => "1", "lib" => $msg ["40"] ) 
									) 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "reception_lot", 
						"lib" => $msg ["admin_transferts_lib_reception_lot"], 
						"champ" => "adminTransReceptionLot", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["39"] ), 
									array ("valeur" => "1", "lib" => $msg ["40"] ) 
									) 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "retour_lot", 
						"lib" => $msg ["admin_transferts_lib_retour_lot"], 
						"champ" => "adminTransRetourLot", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["39"] ), 
									array ("valeur" => "1", "lib" => $msg ["40"] ) 
									) 
					), 
					array (
						"separateur" => $msg ["admin_transferts_sep_pret_exemplaire"] 
					),
					array (
						"prefix" => "transferts", 
						"nom" => "pret_statut_transfert", 
						"lib" => $msg ["admin_transferts_lib_pret_statut_transfert"], 
						"champ" => "adminTransPret", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["admin_transferts_lib_pret_statut_transfert_non"] ),
									array ("valeur" => "1", "lib" => $msg ["admin_transferts_lib_pret_statut_transfert_oui"] ) 
								) 
					),  
					array (
						"separateur" => $msg ["admin_transferts_sep_retour_exemplaire"] 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "retour_origine", 
						"lib" => $msg ["admin_transferts_lib_force_retour_origine"], 
						"champ" => "adminTransRetour", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["39"] ),
									array ("valeur" => "1", "lib" => $msg ["40"] ) 
								) 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "retour_origine_force", 
						"lib" => $msg ["admin_transferts_lib_force_retour_origine_autorise"], 
						"champ" => "adminTransRetourForce", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["39"] ), 
									array ("valeur" => "1", "lib" => $msg ["40"] ) 
								) 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "retour_action_defaut", 
						"lib" => $msg ["admin_transferts_lib_retour_action_defaut"], 
						"champ" => "adminTransRetourAction", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["admin_transferts_lib_retour_action_plus_tard"] ), 
									array ("valeur" => "1", "lib" => $msg ["admin_transferts_lib_retour_action_loc"] ),
									array ("valeur" => "2", "lib" => $msg ["admin_transferts_lib_retour_action_trans"] )
								) 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "retour_change_localisation", 
						"lib" => $msg ["admin_transferts_lib_retour_loc"], 
						"champ" => "adminTransRetourLoc", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["admin_transferts_lib_retour_loc_pas_sauv"] ),
									array ("valeur" => "1", "lib" => $msg ["admin_transferts_lib_retour_loc_sauv"] ) 
								) 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "retour_etat_transfert", 
						"lib" => $msg ["admin_transferts_lib_retour_trans"], 
						"champ" => "adminTransRetourTrans", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["admin_transferts_lib_retour_trans_creer"] ), 
									array ("valeur" => "1", "lib" => $msg ["admin_transferts_lib_retour_trans_envoi"] ) 
								) 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "retour_motif_transfert", 
						"lib" => $msg ["admin_transferts_lib_motif_transfert"], 
						"champ" => "adminTransMotifTrans", 
						"type" => "text", 
						"params" => "size='40'" 
					), 
					array (
						"prefix" => "transferts", 
						"nom" => "retour_action_autorise_autre", 
						"lib" => $msg ["admin_transferts_lib_retour_autorise_autre"], 
						"champ" => "adminTransRetourAutreAction", 
						"type" => "select", 
						"val" => array (
									array ("valeur" => "0", "lib" => $msg ["39"] ), 
									array ("valeur" => "1", "lib" => $msg ["40"] ) 
								) 
					) 
				);

//description des parametres pour l'OPAC
$tab_param_opac = 	array (
						array (
							"prefix" => "transferts", 
							"nom" => "choix_lieu_opac", 
							"lib" => $msg ["admin_transferts_lib_choix_opac"], 
							"champ" => "adminTransOpac", 
							"type" => "select", 
							"val" => array (
										array ("valeur" => "0", "lib" => $msg ["admin_transferts_opac_site_util"] ), 
										array ("valeur" => "1", "lib" => $msg ["admin_transferts_opac_site_choix"] ), 
										array ("valeur" => "2", "lib" => $msg ["admin_transferts_opac_site_precise"] ), 
										array ("valeur" => "3", "lib" => $msg ["admin_transferts_opac_site_ex"] ) 
										) 
						), 
						array (
							"prefix" => "transferts", 
							"nom" => "site_fixe", 
							"lib" => $msg ["admin_transferts_lib_site_fixe"], 
							"champ" => "adminTransSite", 
							"type" => "select", 
							"val" => array (
										array (
											"liste" => "SELECT idlocation,location_libelle FROM docs_location",
											"affichage" => "SELECT location_libelle FROM docs_location WHERE idlocation=!!id!!"
										)
									) 
							 
						),
						array (
							"prefix" => "transferts", 
							"nom" => "resa_motif_transfert", 
							"lib" => $msg ["admin_transferts_lib_motif_transfert_resa"], 
							"champ" => "adminTransMotifTransResa", 
							"type" => "text", 
							"params" => "size='40'" 
						),
						array (
							"prefix" => "transferts", 
							"nom" => "resa_etat_transfert", 
							"lib" => $msg ["admin_transferts_lib_etat_trans_resa"], 
							"champ" => "adminTransResaEtat", 
							"type" => "select", 
							"val" => array (
										array ("valeur" => "0", "lib" => $msg ["admin_transferts_lib_retour_trans_creer"] ), 
										array ("valeur" => "1", "lib" => $msg ["admin_transferts_lib_retour_trans_envoi"] ) 
									) 
						), 
				);

// action en fonction du type
switch ( $sub) {
	
	case 'general' :
	case 'circ' :
	case 'opac' :
		//l'entete de la page
		$admin_layout = str_replace ( '!!menu_sous_rub!!', $msg ["admin_tranferts_" . $sub], $admin_layout );
		print $admin_layout . "<br />";
		
		//pour recuperer le bon tableau de parametres
		$tab_params = "tab_param_" . $sub;
		
		switch ( $action) {
			
			case "modif" :
				//on est en modification
				echo admin_modif_params ( $sub, $$tab_params, $transferts_admin_tableau_modif, $transferts_admin_ligne_modif, $transferts_admin_ligne_separateur );
			break;
			
			case "enregistre" :
				//on enregistre les modifications
				if($form_actif)transfert::admin_enregistre_params ( $$tab_params );
				//puis on affiche le tableau
				//echo admin_affiche_params ( $sub, $$tab_params, $transferts_admin_tableau_affiche, $transferts_admin_ligne_affiche, $transferts_admin_ligne_separateur );
				echo admin_modif_params ( $sub, $$tab_params, $transferts_admin_tableau_modif, $transferts_admin_ligne_modif, $transferts_admin_ligne_separateur );
				break;
			
			default :
				//on affiche le tableau
				echo admin_modif_params ( $sub, $$tab_params, $transferts_admin_tableau_modif, $transferts_admin_ligne_modif, $transferts_admin_ligne_separateur );
				//echo admin_affiche_params ( $sub, $$tab_params, $transferts_admin_tableau_affiche, $transferts_admin_ligne_affiche, $transferts_admin_ligne_separateur );
			break;
		
		}
	break;
	
	case 'ordreloc' :
		//gere l'ordre des localisations pour la recherche d'un exemplaire
		$admin_layout = str_replace ( '!!menu_sous_rub!!', $msg ["admin_tranferts_ordre_localisation"], $admin_layout );
		print $admin_layout . "<br />";
		
		switch ( $action) {
			
			case "enregistre" :
				//on enregistre les modifications
				transfert::admin_enregistre_ordre_localisation ( $sens, $idLoc );
				//puis on affiche le tableau
				admin_affiche_ordre_localisation ();
			break;
			
			default :
				//on affiche le tableau
				admin_affiche_ordre_localisation ();
			break;
		
		}
	break;
	
	case 'statutsdef' :
		//gere le statut par défaut de l'exemplaire lors de la réception
		$admin_layout = str_replace ( '!!menu_sous_rub!!', $msg ["admin_tranferts_statuts_defaut"], $admin_layout );
		print $admin_layout . "<br />";
		
		switch ( $action) {
			
			case "modif" :
				//on est en modification
				admin_modif_statuts_defaut ( $id );
			break;
			
			case "enregistre" :
				//on enregistre les modifications
				transfert::admin_enregistre_statuts_defaut ( $id, $statutDef );
				//puis on affiche le tableau
				admin_affiche_statuts_defaut ();
			break;
			
			default :
				//on affiche le tableau
				admin_affiche_statuts_defaut ();
			break;
		
		}
	break;
	
	case 'purge' :
		//gere le statut par défaut de l'exemplaire lors de la réception
		$admin_layout = str_replace ( '!!menu_sous_rub!!', $msg ["admin_tranferts_purge"], $admin_layout );
		print $admin_layout . "<br />";
		
		switch ( $action) {
			
			case "purge" :
				//on enregistre les modifications
				transfert::admin_purge_historique ( $date_purge );
				//le message de purge effectuée
				echo str_replace ( "!!date_purge!!", formatdate ( $date_purge ), $msg ["admin_transferts_message_purge"] );
				//puis on affiche l'ecran
				admin_affiche_purge ();
			break;
			
			default :
				//on affiche l'ecran de purge
				admin_affiche_purge ();
			break;
		
		}
	break;
	
	default :
		//affiche l'écran d'information par défaut
		$admin_layout = str_replace ( '!!menu_sous_rub!!', "", $admin_layout );
		print $admin_layout . "<br />";
		echo window_title ( $database_window_title . $msg [7] . $msg [1003] . $msg [1001] );
		
		//on affiche le message de présentation
		include ("$include_path/messages/help/$lang/admin_transferts.txt");
	
	break;
}
?>
