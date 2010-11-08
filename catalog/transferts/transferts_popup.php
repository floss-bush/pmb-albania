<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transferts_popup.php,v 1.3 2010-02-22 13:40:49 ngantier Exp $


// d?finition du minimum n?c?ssaire
$base_path="../..";
$base_auth = "TRANSFERTS_AUTH";
$base_title = "\$msg[6]";

require_once ($base_path."/includes/init.inc.php");
require_once($class_path."/transfert.class.php");

if ($action=="enregistre") {
	//on enregistre la demande de transfert
	
	//on transforme la liste en tableau
	$tab_id = explode(",",$expl_ids);
	
	$trans = new transfert();
	
	//pour chaque exemplaire
	foreach ($tab_id as $id_expl) {
		
		//on genere les transferts
		$trans->creer_transfert_catalogue($id_expl, $dest_id, $date_retour, stripslashes($motif));
		
	}
	
	//le script pour fermer la popup
	echo $transferts_popup_enregistre_demande;

} else {
	
	//on affiche la confirmation de la demande
	$rqt = "SELECT expl_cb, expl_cote, location_libelle, section_libelle, tdoc_libelle, lender_libelle".
			" FROM exemplaires".
				" LEFT JOIN docs_location ON exemplaires.expl_location=docs_location.idlocation".
				" LEFT JOIN docs_section ON exemplaires.expl_section=docs_section.idsection ".
				" LEFT JOIN docs_type ON exemplaires.expl_typdoc=docs_type.idtyp_doc  ".
				" LEFT JOIN lenders ON idlender=expl_owner " .
			" WHERE expl_id IN (".$expl.")";
	
	$res = mysql_query($rqt);
	$nb = 0;
	
	//le nombre de colonnes dans la requete pour remplacer les champs dans le template
	$nbCols = mysql_num_fields($res);
	
	while ($values=mysql_fetch_array($res)) {

		if ($nb % 2)
			$tmpLigne = str_replace("!!class_ligne!!", "odd", $transferts_popup_ligne_tableau);
		else			
			$tmpLigne = str_replace("!!class_ligne!!", "even", $transferts_popup_ligne_tableau);
	
		//on parcours toutes les colonnes de la requete
		for($i=0; $i<$nbCols; $i++) {
			//on remplace les données à afficher
			$tmpLigne = str_replace("!!".mysql_field_name($res,$i)."!!",$values[$i],$tmpLigne);
		}
		
		//on ajoute la ligne aux autres
		$tmpString .= $tmpLigne;
		
		//le compteur pour la couleur
		$nb++;
		
	}
	
	//on remplace la liste d'exemplaire dans le template
	$tmpString = str_replace("!!liste_exemplaires!!", $tmpString, $transferts_popup_global);
	
	//la localisation par d?faut de l'utilisateur pour la destination
	$rqt = "SELECT idlocation, location_libelle " .
			"FROM docs_location " .
			"INNER JOIN users ON idlocation=deflt_docs_location " .
			"WHERE userid=".$PMBuserid;
	$res = mysql_query($rqt);
	$values=mysql_fetch_array($res);
	$tmpString = str_replace("!!dest_localisation!!", $values[1], $tmpString);
	$tmpString = str_replace("!!loc_id!!", $values[0], $tmpString);
	
	//on y met la date de pret par defaut
	$date_pret = mktime(0, 0, 0, date("m"), date("d")+$transferts_nb_jours_pret_defaut, date("Y"));
	$date_pret_aff = date("Ymd", $date_pret);
	$tmpString = str_replace("!!date_retour_simple!!", $date_pret_aff, $tmpString);
	$date_pret_aff = date("Y-m-d", $date_pret);
	$tmpString = str_replace("!!date_retour_mysql!!", $date_pret_aff, $tmpString);
	$date_pret_aff = date("d/m/Y", $date_pret);
	$tmpString = str_replace("!!date_retour!!", $date_pret_aff, $tmpString);
	
	//on y met les id d'exemplaire
	$tmpString = str_replace("!!expl_ids!!", $expl, $tmpString);
	
	echo $tmpString;
}

echo $footer;

// deconnection MYSql
mysql_close($dbh);

?>