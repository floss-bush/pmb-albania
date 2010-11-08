<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: affichage.inc.php,v 1.4 2009-12-01 10:59:44 ngantier Exp $

require_once ("$class_path/mono_display.class.php");
require_once ("$class_path/serial_display.class.php");

// affiche les listes de transferts dans les differents ecrans en circulation
function affiche_liste($typeListe, $page, $rqt_select, $rqt_base, $nb_lignes, $html_global, $html_tableau, $html_tableau_ligne, $html_boutons, $html_pas_de_resultats, $lien_edition="", $autres_filtres="",$url_localisation='') {
	
	//le nb de lignes par defaut
	global $transferts_tableau_nb_lignes;

	//requete pour compter les r?sultats
	$res_rqt = mysql_query("SELECT COUNT(*) ".$rqt_base);
	$nbRes = mysql_result($res_rqt,0);
	
	$page = (int)$page;
	
	if ($page=="")
		$page=1;
	
	if ($nb_lignes=="")
		$nb_lignes = $transferts_tableau_nb_lignes;
	
	if ($nbRes>0) {
		//il y a des transferts à afficher !
		
		$tmpString = ""; 
		$nb=0;

		//calcul du nb de pages maximum
		$pagesMax = ceil($nbRes/$nb_lignes);
		
		//on recadre la page en cours
		if ($page>$pagesMax) $page=$pagesMax;

		//requete pour la boucle sur le résultat
		$res_rqt = mysql_query($rqt_select.$rqt_base." LIMIT ".(($page-1)*$nb_lignes).",".$nb_lignes);
		//echo $rqt_select.$rqt_base;
		
		//le nombre de colonnes dans la requete pour remplacer les champs dans le template
		$nbCols = mysql_num_fields($res_rqt);
		
		//on parcours tous les enregistrements de la base
		while ($values=mysql_fetch_array($res_rqt)) {
			
			//pour la coloration
			if ($nb % 2)
				$tmpLigne = str_replace("!!class_ligne!!", "odd", $html_tableau_ligne);
			else			
				$tmpLigne = str_replace("!!class_ligne!!", "even", $html_tableau_ligne);
			
			//on parcours toutes les colonnes de la requete
			for($i=0;$i<$nbCols;$i++) {
				//on remplace les données à afficher
				$tmpLigne = aff_colonne($tmpLigne, mysql_field_name($res_rqt,$i), $values[$i]);
			}
			
			//affichage du titre
			$tmpLigne = str_replace("!!val_titre!!", aff_titre($values[0], $values[1]), $tmpLigne);
			
			//on ajoute la ligne aux autres
			$tmpString .= $tmpLigne;
			
			//le compteur pour la couleur
			$nb++;
		}


		//on met les lignes dans le tableau
		$tmpString = str_replace("!!liste_lignes!!",$tmpString,$html_tableau);

		//on met le tableau dans le corps global
		$tmpString = str_replace("!!corps_liste_transfert!!",$tmpString,$html_global);
		
	} else {
		//pas de transferts à afficher

		//on met le message pas de résultats
		$tmpString = str_replace("!!corps_liste_transfert!!",$html_pas_de_resultats,$html_global);
		
		//on affiche pas les boutons d'actions : y'a rien dans la liste !
		$html_boutons = "";
	}
		
	//pour les fleches de navigation dans la liste
	if ($nbRes>$nb_lignes)
		$tmpNav = aff_pagination("circ.php?categ=trans&sub=".$typeListe.$url_localisation, $nbRes, $nb_lignes, $page, 10, false, false );
	else 
		$tmpNav = "";

	//pour la navigation dans la liste
	$tmpString = str_replace("!!parcours_liste!!",$tmpNav,$tmpString);
	
	//on place des boutons s'il y en a
	$tmpString = str_replace("!!boutons_action!!",$html_boutons,$tmpString);

	//on met le nb de lignes par page
	$tmpString = str_replace("!!nb_res!!",$nb_lignes,$tmpString);

	//pour la destination du formulaire
	$tmpString = str_replace("!!action_formulaire!!","circ.php?categ=trans&sub=".$typeListe,$tmpString);
	
	//pour les autres filtres
	$tmpString = str_replace("!!autres_filtres!!",$autres_filtres,$tmpString);
	
	if ((SESSrights & EDIT_AUTH) && ($lien_edition!="")) { 
		$tmpString = str_replace("!!lien_edition!!",$lien_edition,$tmpString);
	} else
		$tmpString = str_replace("!!lien_edition!!","",$tmpString);
		
	
	return $tmpString;
}

//affichage de la liste de validation pour acceptation ou refus d'une action
function affiche_liste_valide($tpl_global, $tpl_ligne, $rqt_liste, $action) {
	
	//on parcours tous les résultats de retours de la page de liste
	foreach ($_REQUEST as $k => $v) {
		//si c'est une case a cocher d'une liste
		if ((substr($k,0,4)=="sel_") && ($v=="1")) {
			//le no de transfert
			$numeros .= substr($k,4,strlen($k)) . ",";
		}
	}
	
	//on enleve la derniere virgule
	$numeros =  substr($numeros, 0, strlen($numeros)-1);
	
	//la requete pour récupérer les infos
	$rqt = str_replace("!!liste_numeros!!", $numeros, $rqt_liste);
	$res_rqt = mysql_query($rqt);
		
	//le nombre de colonnes dans la requete pour remplacer les champs dans le template
	$nbCols = mysql_num_fields($res_rqt);
	
	$nb = 0;
	
	//on parcours tous les enregistrements
	while ($values=mysql_fetch_array($res_rqt)) {
		
		//pour la coloration
		if ($nb % 2)
			$tmpLigne = str_replace("!!class_ligne!!", "odd", $tpl_ligne);
		else			
			$tmpLigne = str_replace("!!class_ligne!!", "even", $tpl_ligne);
		
		//on parcours toutes les colonnes de la requete
		for($i=0; $i<$nbCols; $i++) {
			//on remplace les données à afficher
			$tmpLigne = aff_colonne($tmpLigne, mysql_field_name($res_rqt,$i), $values[$i]);
		}
		
		//affichage du titre
		$tmpLigne = str_replace("!!val_titre!!", aff_titre($values[0], $values[1]), $tmpLigne);
		
		//on ajoute la ligne aux autres
		$tmpString .= $tmpLigne;
		
		//le compteur pour la couleur
		$nb++;
	}
	
	$tmpString = str_replace("!!liste_transferts!!",$tmpString,$tpl_global);
	$tmpString = str_replace("!!liste_id!!",$numeros,$tmpString);
	$tmpString = str_replace("!!action_formulaire!!", $action, $tmpString);
	return $tmpString;
	
}

//traite l'affichage d'une colonne
function aff_colonne($str_ligne, $nom_col, $val_col) {
	
	if 	(substr($nom_col, 0 , 9) == "val_date_") {
		$str_ligne = str_replace("!!".$nom_col."!!", formatdate($val_col), $str_ligne);
		$str_ligne = str_replace("!!".$nom_col."_mysql!!", $val_col, $str_ligne);
	} elseif ($nom_col=="val_ex") {
		//c'est le no d'exemplaire
		$str_ligne = str_replace("!!val_ex!!",aff_exemplaire($val_col),$str_ligne);
	} elseif ($nom_col=="val_section") {
		$str_ligne = str_replace("!!".$nom_col."!!",do_liste_section($val_col), $str_ligne);
	} else {
		$str_ligne = str_replace("!!".$nom_col."!!",$val_col, $str_ligne);
	}

	return $str_ligne;
}

//renvoi le no d'exemplaire pour le tableau avec ou sans lien
function aff_exemplaire($cb_expl) {

	if (SESSrights & CATALOGAGE_AUTH) { 
		$des_expl = "<a href='./catalog.php?categ=edit_expl&cb=" . $cb_expl . "'>";
		$des_expl .= $cb_expl;
		$des_expl .= "</a>";
	} else
		$des_expl = $cb_expl;

	return $des_expl;
}

//renvoi le titre de l'exemplaire pour le tableau avec ou sans lien
function aff_titre($id_notice,$id_bulletin) {
	if ($id_notice!=0) {
		//c'est une notice
		$disp = new mono_display($id_notice);
		
		if (SESSrights & CATALOGAGE_AUTH)
			$link = "./catalog.php?categ=isbd&id=" . $id_notice;
		
	} else {
		//c'est un bulletin
		$disp = new bulletinage_display($id_bulletin);
		if (SESSrights & CATALOGAGE_AUTH) 
			$link = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=" . $id_bulletin;
	}
	
	return "<a href='" . $link . "'>" . $disp->header . "</a>";
}

//fonction de generation de select
function do_liste($rqt, $idsel) {
	//on execute la requete
	$res = mysql_query($rqt);
	$tmpOpt = "";
	
	//on parcours la liste des options
	while ($value = mysql_fetch_array($res)) {
		//debut de l'option
		$tmpOpt .= "<option value='" . $value[0] . "'";
		
		if ($value[0]==$idsel)
			//c'est l'option par défaut
			$tmpOpt .= " selected";
		
		//fin de l'option
		$tmpOpt .= ">" . $value[1] . "</option>";
	}
	
	//on retourne la liste
	return $tmpOpt;
}

//fonction de generation de select avec les statuts
function do_liste_section($idselect) {
	global $deflt_docs_location;
	return do_liste("SELECT idsection, section_libelle FROM docs_section INNER JOIN docsloc_section ON idsection=num_section WHERE num_location=".$deflt_docs_location ,$idselect);
}

//fonction de generation de select avec les statuts
function do_liste_statut($idselect) {
	return do_liste("SELECT idstatut, statut_libelle FROM docs_statut",$idselect);
}

//fonction de generation de select avec les localisations
function do_liste_localisation($idselect) {
	return do_liste("SELECT idlocation, location_libelle FROM docs_location",$idselect);
}

?>
