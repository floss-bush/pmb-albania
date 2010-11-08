<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions_empr.inc.php,v 1.1 2009-07-31 14:37:09 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


require_once($class_path."/suggestions_map.class.php");
require_once("./acquisition/suggestions/func_suggestions.inc.php");

global $dbh, $statut;

$sug_map = new suggestions_map();

if($statut == -1 || $statut =='')
	$statut_clause ="";
else  $statut_clause= "and statut='".$statut."'";

$req="select count(id_suggestion) as nb, concat(empr_nom,' ',empr_prenom) as nom, id_empr as id from suggestions, suggestions_origine, empr
 where origine=id_empr and num_suggestion=id_suggestion and type_origine=1 $statut_clause group by nom";
$res = mysql_query($req,$dbh);

	$aff = "
	<h1>".$msg['acquisition_sug_ges']."</h1>
	<form name='list_lecteur_sug' method='post' action='./acquisition.php?categ=sug&sub=empr_sug'>
	<h3>".$msg['acquisition_sugg_list_lecteur']."</h3>
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette'>".
				$msg['acquisition_sugg_filtre_by_etat']."
			</label>	
		</div>
		<div class='row'> ".
			$sug_map->getStateSelector()."
		</div>
		<table>
			<tr>
				<th>".$msg['acquisition_sugg_lecteur']."</th>
				<th>".$msg['acquisition_sugg_nb']."</th>
			</tr>";
	if(!mysql_num_rows($res)){
		$aff .= "<tr><td>".htmlentities($msg['acquisition_sugg_no_state_lecteur'])."</td></tr>";
	} else {
		$parity = 1;
		while(($empr = mysql_fetch_object($res))){
			if ($parity % 2)
				$pair_impair = "even";
			else
				$pair_impair = "odd";
			$parity += 1;
			$tr_javascript = "onclick=\"document.location='./acquisition.php?categ=sug&action=list&user_id=$empr->id&user_statut=1' \" ";
			$aff .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td>".$empr->nom."</td><td>".$empr->nb."</td></tr>";
		}
	}
	$aff .= "</table>
	</div>
	</form>";

if (!$statut) {
	$statut = getSessionSugState(); //Recuperation du statut courant
} else {
	setSessionSugState($statut);	
}
$aff .=  "<script type='text/javascript' >this.document.forms['list_lecteur_sug'].elements['statut'].value = '".$statut."' </script>";
print $aff;

?>