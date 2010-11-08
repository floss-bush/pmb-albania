<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl_update.inc.php,v 1.22 2009-07-21 09:36:35 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


$expl_id="";

//Vérification des champs personalisés
$p_perso=new parametres_perso("expl");
$nberrors=$p_perso->check_submited_fields();
if ($nberrors) {
	error_message_history($msg["notice_champs_perso"],$p_perso->error_message,1);
	exit();
}

switch($sub) {
	case 'create':
		$requete = "SELECT count(1) FROM exemplaires WHERE expl_cb='$f_ex_cb' ";
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
		$nbr_lignes ? $valid_requete = FALSE : $valid_requete = TRUE;
		$requete = "INSERT INTO exemplaires SET create_date=sysdate(), ";
		$limiter = "";
		$libelle = $msg[4007];
		break;
	case 'update':
		// ceci teste si l'exemplaire cible existe bien
		$requete = "SELECT expl_id FROM exemplaires WHERE expl_cb='$org_cb' ";
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($res);
		$nbr_lignes ? $valid_requete = TRUE : $valid_requete = FALSE;
		if ($nbr_lignes) $expl_id = mysql_result($res,0,0);
		 
		// remplacement code-barre : test sur le nouveau numéro
		if($org_cb != $f_ex_cb) {
			$requete = "SELECT count(1) FROM exemplaires WHERE expl_cb='$f_ex_cb' ";
			$res = mysql_query($requete, $dbh);
			$nbr_lignes = mysql_result($res, 0, 0);
			$nbr_lignes ? $valid_requete = FALSE : $valid_requete = TRUE;
			}
		$requete = "UPDATE exemplaires SET ";
		$limiter = " WHERE expl_cb='${org_cb}' ";
		$libelle = $msg[4007];
		break;
}

print pmb_bidi("<div class=\"row\"><h1>$libelle</h1></div>");

if(!is_numeric($f_ex_nbparts) || !$f_ex_nbparts) $f_ex_nbparts=1;

if($valid_requete) {
	$requete .= "expl_cb='${f_ex_cb}'";
	$requete .= ", expl_notice=${id}";
	$requete .= ", expl_typdoc=${f_ex_typdoc}";
	$requete .= ", expl_cote=trim('${f_ex_cote}')";
	$formlocid="f_ex_section".$f_ex_location ;
	$requete .= ", expl_section='".$$formlocid."'";
	$requete .= ", expl_statut='${f_ex_statut}'";
	$requete .= ", expl_location='$f_ex_location'";
	$requete .= ", expl_codestat='${f_ex_cstat}'";
	$requete .= ", expl_note='".${f_ex_note}."'";
	$requete .= ", expl_comment='".${f_ex_comment}."'";
	$requete .= ", expl_prix='${f_ex_prix}'";
	$requete .= ", expl_owner='${f_ex_owner}'";
	$requete .= ", type_antivol='${type_antivol}'";
	$requete .= ", expl_nbparts='${f_ex_nbparts}'";
	$requete .= $limiter;

	$result = mysql_query($requete, $dbh);
	if (!$expl_id) {
		$expl_id=mysql_insert_id();
		audit::insert_creation (AUDIT_EXPL, $expl_id) ;
	} else audit::insert_modif (AUDIT_EXPL, $expl_id) ;
	
	//Insertion des champs personalisés
	$p_perso->rec_fields_perso($expl_id);
	
	// tout va bene, on réaffiche l'ISBD
	print "<div class='row'><div class='msg-perio'>".$msg[maj_encours]."</div></div>";
	$id_form = md5(microtime());
	$retour = "./catalog.php?categ=isbd&id=$id";
	print "
		<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
			<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
		</form>
		<script type=\"text/javascript\">document.dummy.submit();</script>
		";
} else {
	error_message($msg[301], $msg[303], 1, "./catalog.php?categ=isbd&id=$id");
}
?>
