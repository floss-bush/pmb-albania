<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pclass_update.inc.php,v 1.3 2007-07-31 09:23:03 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// si tout est OK, on a les variables suivantes à exploiter :
// $id_pclass				identifiant de classement (0 si nouveau)
// $libelle					libelle du classement
// $typedoc_list			Liste des document à associer
	
if ($typedoc_list) 
	foreach($typedoc_list as $doc) {
		$typedoc.=	$doc;
	}
// libelle non renseigne
if ( (trim($libelle)) == '' ) {
	error_form_message($msg["pclassement_libelle_manquant"]);
	exit ;	
}
$requete = "";
if($id_pclass) $requete = "UPDATE pclassement SET name_pclass='".$libelle."', typedoc='".$typedoc."'  WHERE id_pclass =".$id_pclass;
	else $requete = "INSERT INTO pclassement SET name_pclass='".$libelle."', typedoc='".$typedoc."' "; 

mysql_query($requete, $dbh);	

include('./autorites/indexint/pclass.inc.php');
