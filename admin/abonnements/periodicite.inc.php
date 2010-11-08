<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: periodicite.inc.php,v 1.2 2007-06-05 13:15:16 jlesaint Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des codes statut exemplaires
?>
<script type="text/javascript">
function test_form(form)
{
	if(form.form_gestion_libelle.value.length == 0)
	{
		alert("<?php echo $msg[98] ?>");
		return false;
	}
	return true;
}
</script>

<?php
function show_statut($dbh) {
	global $msg;

	print "<table>
		<th>".$msg[abonnements_periodicite_libelle]."</th>
		<th>".$msg[abonnements_periodicite_duree]."</th>
		<th>".$msg[abonnements_periodicite_unite]."</th>
		<th>".$msg['seuil_periodicite']."</th>
		<th>".$msg['retard_periodicite']."</th>
	</tr>";

	// affichage du tableau des périodicités
	$requete = "SELECT periodicite_id, libelle, duree, unite, seuil_periodicite, retard_periodicite  ";
	$requete .= "FROM abts_periodicites ORDER BY libelle ";
	$res = mysql_query($requete, $dbh);
	$nbr = mysql_num_rows($res);

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_object($res);
		if ($parity % 2) {
			$pair_impair = "even";
			} else {
				$pair_impair = "odd";
				}
		$parity += 1;
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=abonnements&sub=periodicite&action=modif&id=$row->periodicite_id';\" ";
		print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>");
		print pmb_bidi("<td><strong>$row->libelle</strong></td>"); 
		print "<td>$row->duree</td>";
		print "<td>";
		switch($row->unite) {
			case '0':print "$msg[abonnements_periodicite_unite_jour]";break;
        	case '1':print "$msg[abonnements_periodicite_unite_mois]";break;
        	case '2':print "$msg[abonnements_periodicite_unite_annee]";break;	
		}
		print "</td>";
		print "<td>$row->seuil_periodicite</td>";
		print "<td>$row->retard_periodicite</td>";
		
		print "</tr>";
	}
	print "</table>
		<input class='bouton' type='button' value=' $msg[abonnements_ajouter_une_periodicite] ' onClick=\"document.location='./admin.php?categ=abonnements&sub=periodicite&action=add'\" />";
}

function statut_form($id=0, $libelle="", $duree=0, $unite=0, $seuil_periodicite=0, $retard_periodicite=0) {

	global $msg;
	global $admin_abonnements_periodicite_form;
	global $charset;

	if (!$id) {
		$admin_abonnements_periodicite_form = str_replace('!!form_title!!', $msg[abonnements_ajouter_une_periodicite], $admin_abonnements_periodicite_form);
		$admin_abonnements_periodicite_form = str_replace("!!bouton_supprimer!!","",$admin_abonnements_periodicite_form) ;
	} else 
	{
		$admin_abonnements_periodicite_form = str_replace("!!bouton_supprimer!!","<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />",$admin_abonnements_periodicite_form) ;
		$admin_abonnements_periodicite_form = str_replace('!!form_title!!', $msg[118], $admin_abonnements_periodicite_form);
	}
	$admin_abonnements_periodicite_form = str_replace('!!id!!', $id, $admin_abonnements_periodicite_form);

	$admin_abonnements_periodicite_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_abonnements_periodicite_form);
	$admin_abonnements_periodicite_form = str_replace('!!libelle_suppr!!', addslashes($libelle), $admin_abonnements_periodicite_form);

	$admin_abonnements_periodicite_form = str_replace('!!duree!!', htmlentities($duree,ENT_QUOTES, $charset), $admin_abonnements_periodicite_form);

	$selected[$unite]= "selected='selected'";
	$str_unite="
       <select id='unite' name='unite'>
        <option value='0'$selected[0]>$msg[abonnements_periodicite_unite_jour]</option>
        <option value='1'$selected[1]>$msg[abonnements_periodicite_unite_mois]</option>
        <option value='2'$selected[2]>$msg[abonnements_periodicite_unite_annee]</option>
        </select>";
	$admin_abonnements_periodicite_form = str_replace('!!unite!!', $str_unite, $admin_abonnements_periodicite_form);

	$admin_abonnements_periodicite_form = str_replace('!!seuil_periodicite!!', htmlentities($seuil_periodicite,ENT_QUOTES, $charset), $admin_abonnements_periodicite_form);
	
	$admin_abonnements_periodicite_form = str_replace('!!retard_periodicite!!', htmlentities($retard_periodicite,ENT_QUOTES, $charset), $admin_abonnements_periodicite_form);
	
	print confirmation_delete("./admin.php?categ=abonnements&sub=periodicite&action=del&id=");
	print $admin_abonnements_periodicite_form;

	}

switch($action) {
	case 'update':
		if (($retard_periodicite>=$seuil_periodicite)||($retard_periodicite==0)) {
			if ($id) {
				$requete = "UPDATE abts_periodicites SET libelle='$libelle',duree='$duree',unite='$unite', seuil_periodicite='$seuil_periodicite', retard_periodicite='$retard_periodicite' WHERE periodicite_id='$id' ";
				$res = mysql_query($requete, $dbh);
				show_statut($dbh);
			} else {
				$requete1=mysql_query("SELECT count(*) FROM abts_periodicites WHERE libelle='$libelle'");
				if ($requete1)
				{
					$result1=mysql_fetch_array($requete1);
					if ($result1[0]==0) {
						$requete = "INSERT INTO abts_periodicites SET libelle='$libelle',duree='$duree',unite='$unite', seuil_periodicite='$seuil_periodicite', retard_periodicite='$retard_periodicite' ";
						$res = mysql_query($requete, $dbh);
						show_statut($dbh);
					} else {
						error_message_history(	$msg[periodicite_existante], $msg[periodicite_existante], 1);	
					}	
					mysql_free_result($requete1);
				} else {
					print $msg['err_sql']."\n";
					print mysql_error();
				}
			}
		} else {
			error_message(	$msg[retard_rapport_seuil], $msg[retard_rapport_seuil], 1, 'admin.php?categ=abonnements&sub=periodicite&action=');		
		}
		break;
	case 'add':
		if (empty($form_gestion_libelle)) statut_form();
		else show_statut($dbh);
		break;
	case 'modif':
		if ($id) {
			$requete = "SELECT libelle, duree, unite, retard_periodicite, seuil_periodicite FROM abts_periodicites WHERE periodicite_id='$id'";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				statut_form($id, $row->libelle, $row->duree, $row->unite, $row->seuil_periodicite, $row->retard_periodicite);
			} 
		}else {
			show_statut($dbh);
		}
		break;
	case 'del':
		if ($id) {
			$total = 0;
			$total = mysql_result(mysql_query("select count(1) from abts_modeles where num_periodicite ='".$id."' ", $dbh), 0, 0);
			if ($total==0) {
				$requete = "DELETE FROM abts_periodicites WHERE periodicite_id='$id' ";
				$res = mysql_query($requete, $dbh);
				$requete = "OPTIMIZE TABLE abts_periodicites ";
				$res = mysql_query($requete, $dbh);
				show_statut($dbh);
				} else {
					error_message(	$msg[noti_statut_noti], $msg[noti_statut_used], 1, 'admin.php?categ=abonnements&sub=periodicite&action=');
				}
			} else show_statut($dbh);
		break;
	default:
		show_statut($dbh);
		break;
	}
