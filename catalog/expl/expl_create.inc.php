<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl_create.inc.php,v 1.20 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


// gestion des exemplaires

print "<h1>".$msg[290]."</h1>";

// on checke si l'exemplaire n'existe pas déjà
$requete = "SELECT count(1) FROM exemplaires WHERE expl_cb='$noex'";
$res = mysql_query($requete, $dbh);

if((!mysql_result($res, 0, 0))||(($option_num_auto)&&($noex==''))) {
	$notice = new mono_display($id, 1, './catalog.php?categ=modif&id=!!id!!', FALSE);
	print pmb_bidi("<div class='row'><b>".$notice->header."</b><br />");
	print pmb_bidi($notice->isbd.'</div>');

	// visibilité des exemplaires
	// On ne vérifie que si l'utlisateur peut créer sur au moins une localisation : 
	if (!$pmb_droits_explr_localises||$explr_visible_mod) {
		$nex = new exemplaire($noex, 0, $id);
		print "<div class='row'>";
		$expl_form = $nex->expl_form('./catalog.php?categ=expl_update&sub=create', "./catalog.php?categ=isbd&id=$id");
		$modifier = "<input type='submit' class='bouton' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />";
		$expl_form = str_replace('!!modifier!!',$modifier,$expl_form);
		$expl_form = str_replace('!!supprimer!!', '', $expl_form);
		if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url) {
			$script_rfid_encode="if(script_rfid_encode()==false) return false;";	
			$expl_form = str_replace('!!questionrfid!!', $script_rfid_encode, $expl_form);
		}
		else $expl_form = str_replace('!!questionrfid!!', '', $expl_form);
		print $expl_form;
		print "</div>";
	} 
} else {
	error_message($msg[301], $msg[302], 1, "./catalog.php?categ=expl&id=$id");
}
?>