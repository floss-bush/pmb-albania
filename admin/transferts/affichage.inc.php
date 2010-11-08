<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: affichage.inc.php,v 1.3 2008-06-12 08:30:54 ohennequin Exp $


// affiche un tableau de parametres
function admin_affiche_params($sub,$tab_param,$tab_gen,$tab_ligne,$tab_ligne_sep) {
	
	//initialisation des variables
	$nb = 0;
	$lignesTableau = "";
	
	foreach ($tab_param as $param) {
		
		if ($param["separateur"]!="") {
				$tmpLigne = str_replace("!!lib_separateur!!",$param["separateur"],$tab_ligne_sep);
		} else {
			
			//pour alterner le styles des lignes
			if ($nb%2)
				$tmpLigne = str_replace("!!class_ligne!!","odd",$tab_ligne);
			else
				$tmpLigne = str_replace("!!class_ligne!!","even",$tab_ligne);
	
			//le libelle du parametre
			$tmpLigne = str_replace("!!lib_param!!",$param["lib"],$tmpLigne);
			$tmpLigne = str_replace("!!nom_champ!!",$param["champ"],$tmpLigne);
			
			//en fonction du type on affiche la valeur
			switch ($param["type"]) {
	
				//pour un champ texte
				case "text":
					$varGlobal= $param["prefix"]."_".$param["nom"];
					global $$varGlobal;
					$tmpLigne = str_replace("!!val_param!!", $$varGlobal, $tmpLigne);
					break;
	
				
				//pour une liste venant d'une requete
				case "select":
					$varGlobal = $param["prefix"]."_".$param["nom"];
					global $$varGlobal;

					foreach($param["val"] as $lgOpt) {
						if ($lgOpt["valeur"]!="") {
							//c'est une valeur fixe
							if ($lgOpt["valeur"]==$$varGlobal) {
								$tmpLigne = str_replace("!!val_param!!",$lgOpt["lib"], $tmpLigne);
								break;
							}
						} else {
							//c'est une requete
							$res = mysql_query(str_replace("!!id!!",$$varGlobal,$lgOpt["affichage"]));
							if ($res) {
								//il y a un resultat a la requete
								$tmpLigne = str_replace("!!val_param!!", mysql_result($res,0), $tmpLigne);
								break;
							}
						}
					} //foreach
					
					break;
			} //switch
	
		}// else de if ($param["separateur"]!="")

		//on ajoute la ligne aux autres
		$lignesTableau .= $tmpLigne;
		$nb++;
	
	} //foreach
	
	//on met les lignes dans le tableau
	$res_affiche = str_replace("!!lignes_aff!!",$lignesTableau, $tab_gen);
	
	//on precise quels parametres pour la modif
	$res_affiche = str_replace("!!sub!!",$sub, $res_affiche);
	
	return $res_affiche;
}

//affiche un tableau de parametres en modification
function admin_modif_params($sub,$tab_param,$tab_gen,$tab_ligne,$tab_ligne_sep) {
	//initialisation des variables
	$nb = 0;
	$lignesTableau = "";
	$actionsScript = "";
	
	foreach ($tab_param as $param) {
		
		if ($param["separateur"]!="") {
				$tmpLigne = str_replace("!!lib_separateur!!",$param["separateur"],$tab_ligne_sep);
		} else {
			//pour alterner le styles des lignes
			if ($nb%2)
				$tmpLigne = str_replace("!!class_ligne!!","odd",$tab_ligne);
			else
				$tmpLigne = str_replace("!!class_ligne!!","even",$tab_ligne);
			
			//le libelle du parametre
			$tmpLigne = str_replace("!!lib_param!!",$param["lib"],$tmpLigne);
			$tmpLigne = str_replace("!!nom_champ!!",$param["champ"],$tmpLigne);
			
			//en fonction du type on affiche la valeure
			switch ($param["type"]) {
	
				//pour un champ texte
				case "text":
					$varGlobal= $param["prefix"]."_".$param["nom"];
					global $$varGlobal;
					$tmpInput = "<input type=\"text\" class=\"\" name=\"".$param["champ"]."\" value=\"".htmlentities($$varGlobal, ENT_QUOTES, $charset)."\" ".$param["params"].">";
					$tmpLigne = str_replace("!!input_param!!",$tmpInput, $tmpLigne);
					break;
	
				//pour une liste de valeurs fixes
				case "select":
					$varGlobal = $param["prefix"]."_".$param["nom"];
					global $$varGlobal;
					
					$tmpInput = "<select name='".$param["champ"]."' ".$param["params"].">";
					foreach($param["val"] as $lgOpt) {
						if ($lgOpt["valeur"]!="") {
							$tmpInput .= "<option value='".$lgOpt["valeur"]."'";
							if ($$varGlobal==$lgOpt["valeur"])
								$tmpInput .= " selected";
							$tmpInput .= ">".$lgOpt["lib"]."</option>";
						} else {
							$res = mysql_query($lgOpt["liste"]);
							while($val = mysql_fetch_array($res)) {
								$tmpInput .= "<option value='".$val[0]."'";
								if ($$varGlobal==$val[0])
									$tmpInput .= " selected";
								$tmpInput .= ">".$val[1]."</option>";
							}
						}
					}
					$tmpInput .= "</select>";
	
					$tmpLigne = str_replace("!!input_param!!", $tmpInput, $tmpLigne);
					break;
					
			}

		}		
		//on ajoute la ligne aux autres
		$lignesTableau .= $tmpLigne;
		$nb++;
	}
	
	//on met les lignes dans le tableau
	$res_affiche = str_replace("!!liste_lignes!!",$lignesTableau, $tab_gen);
	
	//on precise quels parametres pour la modif
	$res_affiche = str_replace("!!sub!!",$sub, $res_affiche);
	$res_affiche = str_replace("!!titre!!", $msg ["admin_tranferts_" . $sub], $res_affiche);
	
	return $res_affiche;
	
}

//affiche le tableau des localisations pour modifier l'ordre
function admin_affiche_ordre_localisation() {
	//les templates
	global $transferts_admin_modif_ordre_loc;
	global $transferts_admin_modif_ordre_loc_ligne;
	global $transferts_admin_modif_ordre_loc_ligne_flBas;
	global $transferts_admin_modif_ordre_loc_ligne_flHaut;
	
	//on genere le tableau des sites
	$rqt = "SELECT idlocation,location_libelle,transfert_ordre FROM docs_location ORDER BY transfert_ordre, idLocation";
	$res = mysql_query($rqt);
	
	//le nb de lignes
	$nb=0;
	$nbTotal = mysql_num_rows($res);
	$tmpString = "";
	
	while ($value = mysql_fetch_array($res)) {
		
		//la classe de la ligne
		if ($nb % 2)
			$tmpLigne = str_replace('!!class_ligne!!', "even", $transferts_admin_modif_ordre_loc_ligne);
		else
			$tmpLigne = str_replace('!!class_ligne!!', "odd", $transferts_admin_modif_ordre_loc_ligne);
					
		//le libellé du site
		$tmpLigne = str_replace('!!lib_site!!', $value[1], $tmpLigne);
		
		if ($nb==0) {
			//on est sur la premiere ligne
			if ($nbTotal>1) {
				//on a plus d'une ligne 
				$tmpLigne = str_replace("!!fl_bas!!",str_replace("!!idSite!!",$value[0],$transferts_admin_modif_ordre_loc_ligne_flBas),$tmpLigne);
			} else {
				$tmpLigne = str_replace("!!fl_bas!!","",$tmpLigne);
			}
			$tmpLigne = str_replace("!!fl_haut!!","",$tmpLigne);
		
		} else {
			if ($nb==($nbTotal-1)) {
				//on est sur la derniere ligne
				$tmpLigne = str_replace("!!fl_bas!!","",$tmpLigne);
				$tmpLigne = str_replace("!!fl_haut!!",str_replace("!!idSite!!",$value[0],$transferts_admin_modif_ordre_loc_ligne_flHaut),$tmpLigne);
			} else {
				//on est sur ligne du milieu
				$tmpLigne = str_replace("!!fl_bas!!",str_replace("!!idSite!!",$value[0],$transferts_admin_modif_ordre_loc_ligne_flBas),$tmpLigne);
				$tmpLigne = str_replace("!!fl_haut!!",str_replace("!!idSite!!",$value[0],$transferts_admin_modif_ordre_loc_ligne_flHaut),$tmpLigne);
			}
		}
		
		//on verifie que l'ordre est respecté
		if ($value[2]!=$nb) {
			//on met a jour le no d'ordre
			$rqt = "UPDATE docs_location SET transfert_ordre=".$nb." WHERE idlocation=".$value[0];
			mysql_query($rqt);
		}
		
		$nb++;
		$tmpString .= $tmpLigne;
		
	}

	//on insere la liste dans le template global
	$tmpString = str_replace("!!liste_sites!!",$tmpString,$transferts_admin_modif_ordre_loc);

	echo $tmpString;
	
}

//affiche la liste des sites avec leur statut par défaut
function admin_affiche_statuts_defaut() {
	global $transferts_admin_statuts_loc_liste;
	global $transferts_admin_statuts_loc_ligne;
	global $msg;
	
	// la liste des sites
	$rqt = "SELECT idlocation,location_libelle,statut_libelle FROM docs_location LEFT JOIN docs_statut ON idstatut=transfert_statut_defaut";
	$res = mysql_query($rqt);
	$tmpOpt = "";
	$nb = 0;
	while ($value = mysql_fetch_array($res)) {
		//on boucle sur les localisations
		if ($nb%2)
			$tmpString = str_replace("!!class_ligne!!","odd",$transferts_admin_statuts_loc_ligne);
		else
			$tmpString = str_replace("!!class_ligne!!","even",$transferts_admin_statuts_loc_ligne);
		
		//l'id du site
		$tmpString = str_replace("!!id_site!!",$value[0],$tmpString);
		
		//le libellé du statut
		if ($value[2] != "")
			$tmpString = str_replace("!!nom_statut!!", $value[2], $tmpString);
		else
			$tmpString = str_replace("!!nom_statut!!", $msg["admin_transferts_statut_transfert_non_defini"], $tmpString);

		//le nom du site
		$tmpOpt .= str_replace("!!nom_site!!",$value[1],$tmpString);

		$nb++;
	}
	
	$tmpString = str_replace("!!liste_sites!!", $tmpOpt, $transferts_admin_statuts_loc_liste);
	
	echo $tmpString;
}

//affiche l'écran de modification du statut par défaut d'un site
function admin_modif_statuts_defaut($id) {
	global $transferts_admin_statuts_loc_modif;
	
	//la requete 
	$rqt = "SELECT idlocation, location_libelle, transfert_statut_defaut FROM docs_location WHERE idlocation=".$id;
	$res = mysql_query($rqt);
	$value = mysql_fetch_array($res);
	
	//on remplace dans le template
	$tmpString = str_replace("!!nom_site!!",$value[1],$transferts_admin_statuts_loc_modif);
	$tmpString = str_replace("!!id_site!!",$value[0],$tmpString);
	$tmpString = str_replace("!!selStatut!!",$value[2],$tmpString);
	
	//la liste des statuts
	$rqt = "SELECT idstatut, statut_libelle FROM docs_statut";
	$res = mysql_query($rqt);
	$tmpOpt = "";
	while ($value = mysql_fetch_array($res)) {
		$tmpOpt .= "<option value='" . $value[0] . "'>" . $value[1] . "</option>";
	}
	$tmpString = str_replace("!!liste_statuts!!", $tmpOpt, $tmpString);
	
	echo $tmpString;
	
}

function admin_affiche_purge($date_purge=null) {
	global $transferts_admin_purge_defaut;
	global $msg;
	
	
	if ($date_purge==null) {
		$tmpString = str_replace("!!message_purge!!", "", $transferts_admin_purge_defaut);
	} else {
		$tmpString = str_replace("!!date_purge!!",formatdate($date_purge),$msg["admin_transferts_message_purge"]);
		$tmpString = str_replace("!!message_purge!!", $tmpString, $transferts_admin_purge_defaut);	
	}
	
	//on met la date du jour
	$date_purge_dt = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
	$date_purge_aff = date("Y-m-d", $date_purge_dt);
	$tmpString = str_replace("!!date_purge_mysql!!", $date_purge_aff, $tmpString);
	$date_purge_aff = date("d/m/Y", $date_purge_dt);
	$tmpString = str_replace("!!date_purge!!", $date_purge_aff, $tmpString);

	echo $tmpString;
}

?>
