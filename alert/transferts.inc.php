<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transferts.inc.php,v 1.3 2009-05-16 11:12:02 dbellamy Exp $


if (stristr ( $_SERVER ['REQUEST_URI'], ".inc.php" ))
	die ( "no access" );

$tmpAff = "";

//pour les validations
if (cpt_transferts ( "etat_transfert=0 AND etat_demande=0 AND num_location_source =" . $deflt_docs_location ) != 0)
	$tmpAff.= "<li><a href='./circ.php?categ=trans&sub=valid' target='_parent'>$msg[alerte_transferts_validation]</a></li>";
	
//pour les envois
if (cpt_transferts ( "etat_transfert=0 AND etat_demande=1 AND num_location_source =" . $deflt_docs_location ) != 0)
	$tmpAff.= "<li><a href='./circ.php?categ=trans&sub=envoi' target='_parent'>$msg[alerte_transferts_envoi]</a></li>";
	
//pour les receptions
if (cpt_transferts ( "etat_transfert=0 AND etat_demande=2 AND num_location_dest =" . $deflt_docs_location ) != 0)
	$tmpAff.= "<li><a href='./circ.php?categ=trans&sub=recep' target='_parent'>$msg[alerte_transferts_reception]</a></li>";
	
//pour les retours
if (cpt_transferts ( "etat_transfert=0 AND type_transfert=1 AND etat_demande=3 AND num_location_dest =" . $deflt_docs_location  . " AND DATE_ADD(date_retour,INTERVAL -" . $transferts_nb_jours_alerte . " DAY)<=CURDATE()") != 0)
	$tmpAff.= "<li><a href='./circ.php?categ=trans&sub=retour' target='_parent'>$msg[alerte_transferts_retours]</a></li>";
	
//pour les refus
if (cpt_transferts ( "etat_transfert=0 AND type_transfert=1 AND etat_demande=4 AND num_location_dest =" . $deflt_docs_location) != 0)
	$tmpAff.= "<li><a href='./circ.php?categ=trans&sub=refus' target='_parent'>$msg[alerte_transferts_refus]</a></li>";
	
//affichage des alertes si besoin
if ($tmpAff)
	$aff_alerte.= "<ul>".$msg ["alerte_avis_transferts"] . $tmpAff."</ul>";
	
//fonction pour compter les transferts	
function cpt_transferts($clause_where) {
	global $deflt_docs_location;
	global $msg;
	
	$rqt = 	"SELECT 1 " . 
			"FROM transferts " . 
				"INNER JOIN transferts_demande ON id_transfert = num_transfert " . 
			"WHERE " . $clause_where . " " . 
			"LIMIT 1";
	//echo $rqt."<br />";
	$req = mysql_query ( $rqt ) or die ( $msg ["err_sql"] . "<br />" . $rqt . "<br />" . mysql_error () );
	$nb_limite = mysql_num_rows ( $req );
	
	return $nb_limite;
}

?>