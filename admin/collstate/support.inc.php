<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: support.inc.php,v 1.1 2009-03-10 08:29:07 ngantier Exp $

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
function show_support($dbh) {
	global $msg;

	print "<table>
	<tr>
		<th>".$msg["admin_collstate_support_nom"]."</th>
	</tr>";

	// affichage du tableau des supports

	$requete = "SELECT * FROM arch_type ORDER BY archtype_libelle ";
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
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=collstate&sub=support&action=modif&id=$row->archtype_id';\" ";
        print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td>$row->archtype_libelle</td></tr>");
	}
	print "</table>
		<input class='bouton' type='button' value=\" ".$msg["admin_collstate_add_support"]." \" onClick=\"document.location='./admin.php?categ=collstate&sub=support&action=add'\" />";
}

function support_form($libelle="", $id=0) {
	global $msg;
	global $admin_support_form;
	global $charset;
	
	$admin_support_form = str_replace('!!id!!', $id, $admin_support_form);

	if(!$id) {
		$admin_support_form = str_replace('!!form_title!!', $msg["admin_collstate_add_support"], $admin_support_form);
		$admin_support_form = str_replace('!!supprimer!!', "", $admin_support_form);	
	} else {
		$admin_support_form = str_replace('!!form_title!!', $msg["admin_collstate_edit_support"], $admin_support_form);
		print confirmation_delete("./admin.php?categ=collstate&sub=support&action=del&id=");
		$admin_support_form = str_replace('!!supprimer!!', "<input class='bouton' type='button' value=' ".$msg["supprimer"]." ' onClick=\"javascript:confirmation_delete($id,'".addslashes($libelle)."')\" />", $admin_support_form);		
	}
	$admin_support_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_support_form);
	print $admin_support_form;
}

switch($action) {
	case 'update':
		// vérification validité des données fournies.
		$requete = " SELECT count(1) FROM arch_type WHERE (archtype_libelle='$form_libelle' AND archtype_id!='$id' )  LIMIT 1 ";
		$res = mysql_query($requete, $dbh);
		$nbr = mysql_result($res, 0, 0);
		if ($nbr > 0) {
			error_form_message($form_libelle.$msg["support_label_already_used"]);
		} else {
			// O.K.,  now if item already exists UPDATE else INSERT
			if($id != 0) {
				$requete = "UPDATE arch_type SET archtype_libelle='$form_libelle' WHERE archtype_id=$id ";
				$res = mysql_query($requete, $dbh);
			} else {
				$requete = "INSERT INTO arch_type (archtype_id,archtype_libelle) VALUES (0, '$form_libelle') ";
				$res = mysql_query($requete, $dbh);
			}
		}
		show_support($dbh);
		break;
	case 'add':
		if(empty($form_libelle) && empty($form_pret)) {
			support_form();
		} else {
			show_support($dbh);
		}
		break;
	case 'modif':
		if($id!=""){
			$requete = "SELECT archtype_libelle FROM arch_type WHERE archtype_id=$id ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				support_form($row->archtype_libelle, $id);
			} else {
				show_support($dbh);
			}
		} else {
			show_support($dbh);
		}
		break;
	case 'del':
		if($id!="") {			
			$total = mysql_num_rows (mysql_query("select 1 from collections_state where collstate_type='".$id."' limit 0,1", $dbh));
			if ($total==0) {
				$requete = "DELETE FROM arch_type WHERE archtype_id=$id ";
				$res = mysql_query($requete, $dbh);
				show_support($dbh);
			} else {
				error_message(	$msg[294], $msg["collstate_support_used"], 1, 'admin.php?categ=support&sub=support&action=');
			}
		}
		break;
	default:
		show_support($dbh);
		break;
	}
