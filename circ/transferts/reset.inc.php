<?php
// +-------------------------------------------------+
// Â© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: reset.inc.php,v 1.1.2.3 2011-05-27 10:27:19 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


require_once ("$include_path/expl_info.inc.php");


// Titre de la fenêtre
echo window_title($database_window_title.$msg[transferts_circ_menu_reset].$msg[1003].$msg[1001]);

//creation de l'objet transfert
$obj_transfert = new transfert();
get_cb_expl($msg[transferts_circ_menu_titre]." > ".$msg[transferts_circ_menu_reset],
					$msg[661], $msg[transferts_circ_reset_exemplaire], "./circ.php?categ=trans&sub=".$sub, 0);
	echo "<div class='row' />";

//si cb
if ($form_cb_expl != "") {	
	$query = "select * from exemplaires where expl_cb='".$form_cb_expl."' ";	
	$result = mysql_query($query, $dbh);
	$expl_info = mysql_fetch_object($result);
	if($expl_info->expl_id) {
		// Reset des transferts en cours
		$rqt = "UPDATE transferts,transferts_demande, exemplaires set etat_transfert=1							
				WHERE id_transfert=num_transfert and num_expl=expl_id  and etat_transfert=0 AND expl_cb='".$form_cb_expl."' " ;
		mysql_query ( $rqt );
		
		//on met à jour la localisation de expl avec celle de l'utilisateur
		$rqt = "UPDATE exemplaires SET expl_location=".$deflt_docs_location." WHERE expl_cb='".$form_cb_expl."' " ;
		mysql_query ( $rqt );
	
		//on met à jour le statut d'origine
	//	$rqt = "UPDATE exemplaires SET expl_statut=transfert_statut_origine WHERE transfert_statut_origine!=0 AND expl_cb='".$form_cb_expl."' " ;
		$rqt = "UPDATE exemplaires SET expl_statut=$deflt_docs_statut WHERE expl_cb='".$form_cb_expl."' " ;
		mysql_query ( $rqt );
		
		// le reset est fait
		$aff=str_replace("!!cb_expl!!", $form_cb_expl,$transferts_reset_OK);
		echo str_replace("!!new_location!!", $obj_transfert->new_location_libelle,$aff);
		
		$stuff = get_expl_info($expl_info->expl_id);
		$stuff = check_pret($stuff);
		print print_info($stuff,1,1,0);
	}else{
		// cb inconnu
		print "<strong>".$form_cb_expl." : ".$msg[367]."</strong>";
	}	
} 




?>