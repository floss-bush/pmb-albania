<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transferts.inc.php,v 1.1 2008-06-04 14:54:24 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ("$include_path/templates/transferts.tpl.php");
require_once ("$class_path/mono_display.class.php");
require_once ("$class_path/serial_display.class.php");

//le titre de la page
echo "<h1>".$msg["transferts_edition_titre"]."&nbsp;&gt;&nbsp;".$msg["transferts_edition_".$sub]."</h1>";

// en fonction de l'etat du transfert
switch($sub) {
	case "validation":
		//initialisation du site d'origine
		if ($site_origine=="")
			$site_origine = $deflt_docs_location;
		
		//initialisation du site de destination
		if ($site_destination=="")
			$site_destination = 0;
	
		//la requete d'affichage
		$rqt = "SELECT ". 
					"num_notice as val_id_notice, num_bulletin as val_id_bulletin, ".
					"expl_cb as val_expl, expl_cote as val_cote, ". 
					"section_libelle as val_section , locd.location_libelle as val_dest, " .
					"loco.location_libelle as val_source " .
				"FROM transferts " .
					"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
					"INNER JOIN exemplaires ON num_expl=expl_id " .
					"INNER JOIN docs_section ON expl_section=idsection " .
					"INNER JOIN docs_location AS locd ON num_location_dest=locd.idlocation " .
					"INNER JOIN docs_location AS loco ON num_location_source=loco.idlocation " .
		"WHERE etat_transfert=0 ". 
					"AND etat_demande=0 ";
		
		//filtre source si nécéssaire
		if ($site_origine!=0)
			$rqt .= " AND num_location_source="  .$site_origine;
		
		//filtre destination si nécéssaire
		if ($site_destination!=0)
			$rqt .= " AND num_location_dest=" . $site_destination;
		
		break;
		
	case "envoi":
		//initialisation du site d'origine
		if ($site_origine=="")
			$site_origine = $deflt_docs_location;
		
		//initialisation du site de destination
		if ($site_destination=="")
			$site_destination = 0;
	
		//la requete d'affichage
		$rqt = "SELECT ". 
					"num_notice as val_id_notice, num_bulletin as val_id_bulletin,  ".
					"expl_cb as val_expl, expl_cote as val_cote, ". 
					"section_libelle as val_section , locd.location_libelle as val_dest, " .
					"loco.location_libelle as val_source " .
				"FROM transferts " .
					"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
					"INNER JOIN exemplaires ON num_expl=expl_id " .
					"INNER JOIN docs_section ON expl_section=idsection " .
					"INNER JOIN docs_location AS locd ON num_location_dest=locd.idlocation " .
					"INNER JOIN docs_location AS loco ON num_location_source=loco.idlocation " .
		"WHERE etat_transfert=0 ". 
					"AND etat_demande=1 ";

		//filtre source si nécéssaire
		if ($site_origine!=0)
			$rqt .= " AND num_location_source="  .$site_origine;
		
		//filtre destination si nécéssaire
		if ($site_destination!=0)
			$rqt .= " AND num_location_dest="  .$site_destination;
		
		break;
		
	case "retours":
		//initialisation du site d'origine
		if ($site_origine=="")
			$site_origine = $deflt_docs_location;
		
		//initialisation du site de destination
		if ($site_destination=="")
			$site_destination = 0;
	
		//la requete d'affichage
		$rqt = "SELECT ". 
					"num_notice as val_id_notice, num_bulletin as val_id_bulletin, ".
					"expl_cb as val_expl, expl_cote as val_cote, ". 
					"section_libelle as val_section , locd.location_libelle as val_dest, " .
					"loco.location_libelle as val_source " .
				"FROM transferts " .
					"INNER JOIN transferts_demande ON id_transfert=num_transfert " .
					"INNER JOIN exemplaires ON num_expl=expl_id " .
					"INNER JOIN docs_section ON expl_section=idsection " .
					"INNER JOIN docs_location locd ON num_location_source=locd.idlocation " .
					"INNER JOIN docs_location loco ON num_location_dest=loco.idlocation " .
				"WHERE etat_transfert=0 ". 
					"AND type_transfert=1 ".
					"AND etat_demande=3 ";

		//filtre origine si nécéssaire
		if ($site_origine!=0)
			$rqt .= " AND num_location_dest=".$site_origine;
		
		//filtre destination si nécéssaire
		if ($site_destination!=0)
			$rqt .= " AND num_location_source=".$site_destination;
				
		//application du filtre sur la date de retour
		switch ($f_etat_date) {
			case "1":
				$rqt .= " AND (DATEDIFF(DATE_ADD(date_retour,INTERVAL -" . $transferts_nb_jours_alerte . " DAY),CURDATE())<=0";
				$rqt .= " AND DATEDIFF(date_retour,CURDATE())>=0)";
				break;
			case "2":
				$rqt .= " AND DATEDIFF(date_retour,CURDATE())<0";
				break;
		
		}
			
		break;
}

$rqt .=	" ORDER BY val_section, val_expl";

//echo $rqt;

$cols_supp = "";
// si la destination n'est pas précisé
if ($site_origine==0) {
	$cols_supp .= $transferts_edition_titre_source;
	$cols_supp_ligne .= $transferts_edition_ligne_source;
}

if ($site_destination==0) {
	$cols_supp .= $transferts_edition_titre_destination;
	$cols_supp_ligne .= $transferts_edition_ligne_destination;
}

$tabLigne = str_replace("!!colonnes_variables!!", $cols_supp_ligne, $transferts_edition_ligne);

//echo $rqt;
//execution de la requete
$req = mysql_query($rqt);

//le nombre de colonnes dans la requete pour remplacer les champs dans le template
$nbCols = mysql_num_fields($req);

$tmpAff = "";

//on boucle sur la liste
while ($value = mysql_fetch_array($req)) {

	//pour la coloration
	if ($nb % 2)
		$tmpLigne = str_replace("!!class_ligne!!", "odd", $tabLigne);
	else			
		$tmpLigne = str_replace("!!class_ligne!!", "even", $tabLigne);
	
	//on parcours toutes les colonnes de la requete
	for($i=0;$i<$nbCols;$i++) {
		$tmpLigne = str_replace("!!".mysql_field_name($req,$i)."!!",$value[$i],$tmpLigne);
	}

	//affichage du titre
	$tmpLigne = str_replace("!!val_titre!!", aff_titre($value[0], $value[1]), $tmpLigne);
	
	//on ajoute la ligne a la liste
	$tmpAff .= $tmpLigne;
	$nb++;

} //fin while

//on met les lignes du tableau dans le tableau
$tmpAff = str_replace("!!lignes_tableau!!",$tmpAff,$transferts_edition_tableau);

//si on a des colonnes en plus
$tmpAff = str_replace("!!colonnes_variables!!", $cols_supp, $tmpAff);

//la sub pour retomber sur ses pattes
$tmpAff = str_replace("!!sub!!",$sub,$tmpAff);

//les filtres
//pour la liste des origines
$filtres = str_replace("!!liste_sites_origine!!",creer_liste_localisations($site_origine),$transferts_edition_filtre_source);
//pour la liste de destination
$filtres .= str_replace("!!liste_sites_destination!!",creer_liste_localisations($site_destination),$transferts_edition_filtre_destination);

if ($sub=="retours") {
	//le filtre de l'etat de la date
	$filtres .= str_replace("!!sel_" . $f_etat_date . "!!", "selected", $transferts_retour_filtre_etat);
}

//la sub pour retomber sur ses pattes
$tmpAff = str_replace("!!filtres_edition!!",$filtres,$tmpAff);

//on affiche la page !
echo $tmpAff;

$transferts_retour_filtre_etat;


//***********************************************************************************************************

//renvoi le titre de l'exemplaire pour le tableau
function aff_titre($id_notice,$id_bulletin) {
	if ($id_notice!=0) {
		//c'est une notice
		$disp = new mono_display($id_notice);

	} else {
		//c'est un bulletin
		$disp = new bulletinage_display($id_bulletin);
	}
	
	return $disp->header;
}

//***********************************************************************************************************

//crée la liste des localisations en précisant une de sélectionner et si on rajoute une ligne tous
function creer_liste_localisations($loc_select,$tous = true) {
	global $msg;

	//la requete
	$rqt="SELECT idlocation, location_libelle FROM docs_location ORDER BY location_libelle ";
	$req = mysql_query($rqt);
	
	
	//initialisation de la liste
	if ($tous) 
		$tmpListe = "<option value=0>".$msg["all_location"]."</option>";
	else
		$tmpListe = "";
	
	//on parcours
	while ($value = mysql_fetch_array($req)) {
		
		$tmpListe .= "<option value=".$value[0]; 
		
		if ($value[0]==$loc_select)
			$tmpListe .= " selected";
		
		$tmpListe .= ">".$value[1]."</option>";
		
	}
	
	return $tmpListe;

}

?>