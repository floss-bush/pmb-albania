<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: blocage.php,v 1.5 2008-07-17 12:03:41 erwanmartin Exp $

$base_path="..";
$base_auth = "CIRCULATION_AUTH";
$base_nobody = 1;
require_once($base_path."/includes/init.inc.php");

$requete="select * from empr where id_empr=".$id_empr;
$resultat=mysql_query($requete);
$empr=mysql_fetch_object($resultat);

switch($act) {
	case 'prolong':
		if ($date_prolong) {
			$requete="update empr set date_fin_blocage='".$date_prolong."' where id_empr=".$id_empr;
			mysql_query($requete);
		}
		break;
	case 'annul':
		$requete="update empr set date_fin_blocage='0000-00-00' where id_empr=".$id_empr;
		mysql_query($requete);
		break;
}

if (!$act) {
	print "<body class='circ'>";
	print "<form class='form-circ' name='blocage_form' method='post' action='./blocage.php?id_empr=$id_empr'>";
	print pmb_bidi("<h3>".$empr->empr_prenom." ".$empr->empr_nom."</h3>
	<div class='form-contenu'>
		<div class='row'>
			<input type='radio' name='act' value='prolong' id='prolong' checked><label for='prolong'>".sprintf($msg["blocage_params_jusque"],"<input type='button' value='".formatdate($empr->date_fin_blocage)."' name='date_prolong_lib' class='bouton' onClick=\"openPopUp('../select.php?what=calendrier&caller=blocage_form&date_caller=".str_replace("-","",$empr->date_fin_blocage)."&param1=date_prolong&param2=date_prolong_lib&auto_submit=NO', 'date_blocage', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes');\"/>")."</label>
			<input type='hidden' name='date_prolong' value='".$empr->date_fin_blocage."'/>
		</div>
		<div class='row'>
			<input type='radio' name='act' value='annul' id='annul'><label for='annul'>".$msg["blocage_params_deblocage"]."</label>
		</div>
		<div class='row'></div>
	</div>
	<div class='row'>
		<input type='submit' value='".$msg["blocage_params_apply"]."' class='bouton'/>&nbsp;<input type='button' class='bouton' value='".$msg["76"]."' onClick=\"self.close();\"/>
	</div>
	");
} else {
	echo "<script>opener.document.location='../circ.php?categ=pret&id_empr=$id_empr'; self.close();</script>";
}
print "</body></html>";
?>