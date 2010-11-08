<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: emplacement.inc.php,v 1.1 2009-03-10 08:29:07 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des prêteurs de documents
?>
<script type="text/javascript">
function test_form(form)
{
	if(form.form_libelle.value.length == 0)
	{
		alert("<?php echo $msg[559]; ?>");
		return false;
	}
	return true;
}

</script>
<?php
function show_emplacement($dbh) {
	global $msg;

	print "<table>
	<tr>
		<th>".$msg["admin_collstate_emplacement_nom"]."</th>
	</tr>";

	// affichage du tableau des emplacements

	$requete = "SELECT * FROM arch_emplacement ORDER BY archempla_libelle ";
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
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=collstate&sub=emplacement&action=modif&id=$row->archempla_id';\" ";
        	print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td>$row->archempla_libelle</td></tr>");
	}
	print "</table>
		<input class='bouton' type='button' value=\"".$msg["admin_collstate_add_emplacement"]." \" onClick=\"document.location='./admin.php?categ=collstate&sub=emplacement&action=add'\" />";
}

function emplacement_form($libelle="", $id=0) {
	global $msg;
	global $admin_emplacement_form;
	global $charset;
	
	$admin_emplacement_form = str_replace('!!id!!', $id, $admin_emplacement_form);

	if(!$id) {
		$admin_emplacement_form = str_replace('!!form_title!!', $msg["admin_collstate_add_emplacement"], $admin_emplacement_form);
		$admin_emplacement_form = str_replace('!!supprimer!!', "", $admin_emplacement_form);
	}else {
		$admin_emplacement_form = str_replace('!!form_title!!', $msg["admin_collstate_edit_emplacement"], $admin_emplacement_form);	
		print confirmation_delete("./admin.php?categ=collstate&sub=emplacement&action=del&id=");
		$admin_emplacement_form = str_replace('!!supprimer!!', "<input class='bouton' type='button' value=' ".$msg["supprimer"]." ' onClick=\"javascript:confirmation_delete($id,'".addslashes($libelle)."')\" />", $admin_emplacement_form);
	}

	$admin_emplacement_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_emplacement_form);
	print $admin_emplacement_form;

}

switch($action) {
	case 'update':
		// vérification validité des données fournies.
		$requete = " SELECT count(1) FROM arch_emplacement WHERE (archempla_libelle='$form_libelle' AND archempla_id!='$id' )  LIMIT 1 ";
		$res = mysql_query($requete, $dbh);
		$nbr = mysql_result($res, 0, 0);
		if ($nbr > 0) {
			error_form_message($form_libelle.$msg["emplacement_label_already_used"]);
		} else {
			// O.K.,  now if item already exists UPDATE else INSERT
			if($id != 0) {
				$requete = "UPDATE arch_emplacement SET archempla_libelle='$form_libelle' WHERE archempla_id=$id ";
				$res = mysql_query($requete, $dbh);
			} else {
				$requete = "INSERT INTO arch_emplacement (archempla_id,archempla_libelle) VALUES (0, '$form_libelle') ";
				$res = mysql_query($requete, $dbh);
			}
		}
		show_emplacement($dbh);
		break;
	case 'add':
		if(empty($form_libelle) && empty($form_pret)) {
			emplacement_form();
		} else {
			show_emplacement($dbh);
		}
		break;
	case 'modif':
		if($id!=""){
			$requete = "SELECT archempla_libelle FROM arch_emplacement WHERE archempla_id=$id ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				emplacement_form($row->archempla_libelle, $id);
			} else {
					show_emplacement($dbh);
			}
		} else {
			show_emplacement($dbh);
		}
		break;
	case 'del':
		if($id!="") {
			$total = 0;
			$total = mysql_num_rows (mysql_query("select 1 from collections_state where collstate_emplacement='".$id."' limit 0,1", $dbh));
			if ($total==0) {
				$requete = "DELETE FROM arch_emplacement WHERE archempla_id=$id ";
				$res = mysql_query($requete, $dbh);
				show_emplacement($dbh);
			} else {
				error_message(	$msg[294], $msg["collstate_emplacement_used"], 1, 'admin.php?categ=collstate&sub=emplacement&action=');
			}
		}
		break;
	default:
		show_emplacement($dbh);
		break;
	}
