<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: edit_expl.inc.php,v 1.33 2010-12-14 13:43:21 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


// gestion des exemplaires
print "<h1>".$msg["4008"]."</h1>";
$notice = new mono_display($id, 1, './catalog.php?categ=modif&id=!!id!!', FALSE);
print pmb_bidi("<div class='row'><b>".$notice->header."</b><br />");
print pmb_bidi($notice->isbd."</div>");
$nex = new exemplaire($cb, $expl_id,$id);

//on compte de nombre de prets pour cet exemplaire
$req = "select count(arc_expl_id) as nb_prets from pret_archive where arc_expl_id = ".$nex->expl_id;
$res = mysql_query($req);
if(mysql_num_rows($res)){
	$nb_prets = mysql_result($res,0,0);
}else $nb_prets = 0;
if($nb_prets)print str_replace("!!nb_prets!!",$nb_prets,$msg['expl_nbprets']);


// visibilité des exemplaires
// $nex->explr_acces_autorise contient INVIS, MODIF ou UNMOD

if ($nex->explr_acces_autorise!="INVIS") {
	
	print "<div class='row'>";
	$expl_form = $nex->expl_form("./catalog.php?categ=expl_update&sub=update&org_cb=".urlencode($cb)."&expl_id=".$expl_id, "./catalog.php?categ=isbd&id=$id");

	if ($nex->explr_acces_autorise=="MODIF") {
		// lien pour suppression
		$supprimer = "
			<script type=\"text/javascript\">
   		 	function confirm_delete() {
	       		result = confirm(\"${msg[314]} ?\");
    	    	if(result) document.location = \"./catalog.php?categ=del_expl&id=$id&cb=".urlencode($cb)."&expl_id=".$expl_id."\";
    	    	else unload_on(); 
     		}
			</script>
			<input type='button' class='bouton' value=\"${msg['63']}\" name='del_ex' id='del_ex' onClick=\"unload_off();confirm_delete();\" />
			";
		$dupliquer = "&nbsp;<input type='button' class='bouton' value=\"".$msg['dupl_expl_bt']."\" name='dupl_ex' id='dupl_ex' onClick=\"unload_off();document.location='./catalog.php?categ=dupl_expl&id=$id&cb=".urlencode($cb)."&expl_id=".$expl_id."' ; \" />
			";
		// lien pour la modification
		$modifier = "<input type='submit' class='bouton' value=' $msg[77] ' onClick=\"unload_off();return test_form(this.form);\" />".$dupliquer;
	} else {
		$modifier="";
		$supprimer="";
	}
	$expl_form = str_replace('!!modifier!!',$modifier,$expl_form);
	$expl_form = str_replace('!!supprimer!!', $supprimer, $expl_form);
	
	if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url) {
		$script_rfid_encode="if(script_rfid_encode()==false) return false;";	
		$expl_form = str_replace('!!questionrfid!!', $script_rfid_encode, $expl_form);
	} else {
		$expl_form = str_replace('!!questionrfid!!', '', $expl_form);
	}
	
	print $expl_form;
	print "</div>";
} else {
	print "<div class='row'><div class='colonne10'><img src='./images/error.png' /></div>";
	print "<div class='colonne-suite'><span class='erreur'>".$msg["err_mod_expl"]."</span>&nbsp;&nbsp;&nbsp;";
	print "<input type='button' class='bouton' value=\"${msg['bt_retour']}\" name='retour' onClick='history.back(-1);'></div></div>";	
}

// zone du dernier emrunteur
if ($nex->lastempr) {
	$lastempr = new emprunteur($nex->lastempr, '', FALSE, 0) ;
	print "<hr /><div class='row'><b>$msg[expl_lastempr] </b>";
	$link = "<a href='./circ.php?categ=pret&form_cb=".rawurlencode($lastempr->cb)."'>";
	print pmb_bidi($link.$lastempr->prenom.' '.$lastempr->nom.' ('.$lastempr->cb.')</a>');
	print "</div>";
}
		
// zone de l'emprunteur
$query = "select empr_cb, empr_nom, empr_prenom, ";
$query .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
$query .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
$query .= " IF(pret_retour>sysdate(),0,1) as retard " ; 
$query .= " from pret, empr where pret_idexpl='".$nex->expl_id."' and pret_idempr=id_empr ";
$result = mysql_query($query, $dbh);
if(mysql_num_rows($result)) {
	$pret = mysql_fetch_object($result);
	print "<hr /><div class='row'><b>$msg[380]</b> ";
	$link = "<a href='./circ.php?categ=pret&form_cb=".rawurlencode($pret->empr_cb)."'>";
	print pmb_bidi($link.$pret->empr_prenom.' '.$pret->empr_nom.' ('.$pret->empr_cb.')</a>');
	print "&nbsp;${msg[381]}&nbsp;".$pret->aff_pret_date;
	print ".&nbsp;${msg[358]}&nbsp;".$pret->aff_pret_retour.".";
	print "</div>";
} 
?>
	