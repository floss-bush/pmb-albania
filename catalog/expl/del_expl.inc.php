<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: del_expl.inc.php,v 1.14 2010-05-05 12:28:39 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


print "<div class=\"row\"><h1>${msg[313]}</h1></div>";

//Récupération de l'ID de l'exemplaire
if (!$expl_id || !$cb) {
	$requete = "select expl_id, expl_cb from exemplaires where expl_cb='$cb' or expl_id='$expl_id'";
	$result=@mysql_query($requete);
	if (mysql_num_rows($result)) {
		$expl_id=mysql_result($result,0,0);
		$cb=mysql_result($result,0,1);
	}
}

$requete = "select 1 from pret where pret_idexpl='$expl_id' ";
$result=@mysql_query($requete);
if (mysql_num_rows($result)) {
	// gestion erreur prêt en cours
	error_message($msg[416], $msg[impossible_expl_del_pret], 1, "./catalog.php?categ=isbd&id=$id");
} else {

	$requete = "DELETE FROM exemplaires WHERE expl_cb='$cb' or expl_id='$expl_id'";
	$result = @mysql_query($requete, $dbh);
	audit::delete_audit (AUDIT_EXPL, $expl_id) ;
	
	$query_caddie = "select caddie_id from caddie_content, caddie where type='EXPL' and object_id ='$expl_id' and caddie_id=idcaddie ";
	$result_caddie = @mysql_query($query_caddie, $dbh);
	while($cad = mysql_fetch_object($result_caddie)) {
		$req_suppr_caddie="delete from caddie_content where caddie_id = '$cad->caddie_id' and object_id ='$expl_id' " ;
		@mysql_query($req_suppr_caddie, $dbh);
	}

	//Supression des champs perso
	if ($expl_id) {
		$p_perso=new parametres_perso("expl");
		$p_perso->delete_values($expl_id);
	}
	
	// nettoyage transfert
	$requete_suppr = "delete from transferts_demande where num_expl='$expl_id'";
	$result_suppr = mysql_query($requete_suppr);
	
	print "<div class='row'><div class='msg-perio'>".$msg[maj_encours]."</div></div>";
	$id_form = md5(microtime());
	$retour = "./catalog.php?categ=isbd&id=$id";
	print "<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
		<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
		</form>
		<script type=\"text/javascript\">document.dummy.submit();</script>
		</div>";
}
?>	