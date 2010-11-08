<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: statut.inc.php,v 1.16 2008-08-13 08:10:27 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des codes statut exemplaires
?>
<script type="text/javascript">
function test_form(form)
{
	if(form.form_libelle.value.length == 0)
	{
		alert("<?php echo $msg[98] ?>");
		return false;
	}
	return true;
}
function test_check(form)
{
	if(form.form_pret.value < 1)
		form.form_pret.value = '1';
	else
		form.form_pret.value = '0';
	return true;
}
function test_check_trans(form)
{
	if(form.form_trans.value < 1)
		form.form_trans.value = '1';
	else
		form.form_trans.value = '0';
	return true;
}

</script>

<?php
function show_statut($dbh) {
	global $msg;

	print "<table>
	<tr>
		<th>".$msg[103]."</th>
		<th>&nbsp;</th>
		<th>".$msg['proprio_codage_proprio']."</th>
		<th>".$msg['import_codage']."</th>
	</tr>";

	// affichage du tableau des statuts
	$requete = "SELECT idstatut, statut_libelle, statusdoc_codage_import, pret_flag, statusdoc_owner, lender_libelle FROM docs_statut left join lenders on statusdoc_owner=idlender ORDER BY statut_libelle ";
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
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=docs&sub=statut&action=modif&id=$row->idstatut';\" ";
        	if ($row->statusdoc_owner) print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>$row->statut_libelle</i></td>");
			else print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><strong>$row->statut_libelle</strong></td>"); 
		if($row->pret_flag) print "<td>$msg[113]</td>";
		else print "<td>$msg[114]</td>";
		print pmb_bidi("<td>$row->lender_libelle</td>") ;
		print pmb_bidi("<td>$row->statusdoc_codage_import</td></tr>");
		}
	print "</table>
		<input class='bouton' type='button' value=' $msg[115] ' onClick=\"document.location='./admin.php?categ=docs&sub=statut&action=add'\" />";
	}

function statut_form($libelle="", $pret=1, $trans=1, $statusdoc_codage_import="", $statusdoc_owner=0, $id=0) {

	global $msg;
	global $admin_statut_form;
	global $charset;

	$admin_statut_form = str_replace('!!id!!', $id, $admin_statut_form);

	if(!$id) $admin_statut_form = str_replace('!!form_title!!', $msg[115], $admin_statut_form);
	else $admin_statut_form = str_replace('!!form_title!!', $msg[118], $admin_statut_form);

	$admin_statut_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_statut_form);
	$admin_statut_form = str_replace('!!libelle_suppr!!', addslashes($libelle), $admin_statut_form);

	if($pret) $checkbox="checked"; else $checkbox="";
	$admin_statut_form = str_replace('!!checkbox!!', $checkbox, $admin_statut_form);
	$admin_statut_form = str_replace('!!pret!!', $pret, $admin_statut_form);
	
	if($trans) $checkbox="checked"; else $checkbox="";
	$admin_statut_form = str_replace('!!checkbox_trans!!', $checkbox, $admin_statut_form);
	$admin_statut_form = str_replace('!!trans!!', $trans, $admin_statut_form);
	
	$admin_statut_form = str_replace('!!statusdoc_codage_import!!', $statusdoc_codage_import, $admin_statut_form);
	$combo_lender= gen_liste ("select idlender, lender_libelle from lenders order by lender_libelle ", "idlender", "lender_libelle", "form_statusdoc_owner", "", $statusdoc_owner, 0, $msg[556],0,$msg["proprio_generique_biblio"]) ;
	$admin_statut_form = str_replace('!!lender!!', $combo_lender, $admin_statut_form);

	print confirmation_delete("./admin.php?categ=docs&sub=statut&action=del&id=");
	print $admin_statut_form;

}

switch($action) {
	case 'update':
		// vérification validité des données fournies.
		$requete = " SELECT count(1) FROM docs_statut WHERE (statut_libelle='$form_libelle' AND idstatut!='$id' )  LIMIT 1 ";
		$res = mysql_query($requete, $dbh);
		$nbr = mysql_result($res, 0, 0);
		if ($nbr > 0) {
			error_form_message($form_libelle.$msg["docs_label_already_used"]);
		} else {
			// O.K.,  now if item already exists UPDATE else INSERT
			if($id) {
				$requete = "UPDATE docs_statut SET statut_libelle='$form_libelle',pret_flag='$form_pret',transfert_flag='$form_trans',statusdoc_codage_import='$form_statusdoc_codage_import', statusdoc_owner='$form_statusdoc_owner' WHERE idstatut=$id ";
				$res = mysql_query($requete, $dbh);
			} else {
				$requete = "INSERT INTO docs_statut (idstatut,statut_libelle,pret_flag,transfert_flag,statusdoc_codage_import,statusdoc_owner) VALUES ('', '$form_libelle','$form_pret','$form_trans','$form_statusdoc_codage_import','$form_statusdoc_owner') ";
				$res = mysql_query($requete, $dbh);
			}
		}
		show_statut($dbh);
		break;
	case 'add':
		if(empty($form_libelle) && empty($form_pret)) statut_form();
		else show_statut($dbh);
		break;
	case 'modif':
		if($id){
			$requete = "SELECT statut_libelle, pret_flag, statusdoc_codage_import, statusdoc_owner, transfert_flag FROM docs_statut WHERE idstatut=$id LIMIT 1 ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				statut_form($row->statut_libelle, $row->pret_flag, $row->transfert_flag, $row->statusdoc_codage_import,$row->statusdoc_owner, $id);
			} else {
				show_statut($dbh);
			}
		} else {
			show_statut($dbh);
		}
		break;
	case 'del':
		if($id) {
			$total = 0;
			$total = mysql_result(mysql_query("select count(1) from exemplaires where expl_statut ='".$id."' ", $dbh), 0, 0);
			if ($total==0) {
				$requete = "DELETE FROM docs_statut WHERE idstatut=$id ";
				$res = mysql_query($requete, $dbh);
				show_statut($dbh);
			} else {
				error_message(	$msg[294], $msg[1703], 1, 'admin.php?categ=docs&sub=statut&action=');
			}
		} else show_statut($dbh);
		break;
	default:
		show_statut($dbh);
		break;
	}
