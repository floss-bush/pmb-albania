<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


// gestion des exemplaires
print "<h1>".$msg["dupl_expl_titre"]."</h1>";
$notice = new mono_display($id, 1, './catalog.php?categ=modif&id=!!id!!', FALSE);
print pmb_bidi("<div class='row'><b>".$notice->header."</b><br />");
print pmb_bidi($notice->isbd."</div>");

$nex = new exemplaire($cb, $expl_id,$id);

// visibilité des exemplaires
// $nex->explr_acces_autorise contient INVIS, MODIF ou UNMOD

if ($nex->explr_acces_autorise!="INVIS") {
	
	print "<div class='row'>";
	$nex->cb="";
	$nex->expl_id=0;
	$expl_form = $nex->expl_form("./catalog.php?categ=expl_update&sub=create", "./catalog.php?categ=isbd&id=$id");

	if ($nex->explr_acces_autorise=="MODIF") {
		// lien pour suppression
		$supprimer = "";
		// lien pour la modification
		$modifier = "<input type='submit' class='bouton' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />";
	} else {
		$modifier="";
		$supprimer="";
	}
	
	$expl_form = str_replace('!!questionrfid!!',"",$expl_form);
	$expl_form = str_replace('!!modifier!!',$modifier,$expl_form);
	$expl_form = str_replace('!!supprimer!!', $supprimer, $expl_form);
	print $expl_form;
	print "</div>";
} else {
	print "<div class='row'><div class='colonne10'><img src='./images/error.png' /></div>";
	print "<div class='colonne-suite'><span class='erreur'>".$msg["err_mod_expl"]."</span>&nbsp;&nbsp;&nbsp;";
	print "<input type='button' class='bouton' value=\"${msg['bt_retour']}\" name='retour' onClick='history.back(-1);'></div></div>";	
}
?>