<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pclass_delete.inc.php,v 1.3 2009-05-16 11:12:00 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// si tout est OK, on a les variables suivantes à exploiter :
// $id_pclass				identifiant de classement (0 si nouveau)
if($id_pclass == 1){
	// Interdire l'effacement de l'id 1
	error_form_message($msg["pclassement_suppr_impossible"]);
	exit;
}	
$requete = "SELECT indexint_id FROM indexint WHERE num_pclass='".$id_pclass."' " ;
$result = mysql_query($requete, $dbh) or die ($requete."<br />".mysql_error());
if(mysql_num_rows($result)) {
	// Il y a des enregistrements. Interdire l'effacement.
	error_form_message($msg["pclassement_suppr_impossible"]);
	exit;
	
} else {
	// effacement
	$dummy = "delete FROM pclassement WHERE id_pclass='$id_pclass' ";
	mysql_query($dummy, $dbh);		
}
include('./autorites/indexint/pclass.inc.php');
