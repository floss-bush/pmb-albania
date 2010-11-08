<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lender.inc.php,v 1.11 2008-01-07 13:56:47 dbellamy Exp $

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
function show_lender($dbh) {
	global $msg;

	print "<table>
	<tr>
		<th>".$msg[558]."</th>
	</tr>";

	// affichage du tableau des utilisateurs

	$requete = "SELECT idlender,lender_libelle FROM lenders ORDER BY lender_libelle ";
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
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=docs&sub=lenders&action=modif&id=$row->idlender';\" ";
        	print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td>$row->lender_libelle</td>
                	</tr>");
		}
	print "</table>
		<input class='bouton' type='button' value=' $msg[555] ' onClick=\"document.location='./admin.php?categ=docs&sub=lenders&action=add'\" />";
	}

function lender_form($libelle="", $id=0) {
	global $msg;
	global $admin_lender_form;
	global $charset;
	
	$admin_lender_form = str_replace('!!id!!', $id, $admin_lender_form);

	if(!$id) $admin_lender_form = str_replace('!!form_title!!', $msg[555], $admin_lender_form);
		else $admin_lender_form = str_replace('!!form_title!!', $msg[557], $admin_lender_form);

	$admin_lender_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_lender_form);
	$admin_lender_form = str_replace('!!libelle_suppr!!', addslashes($libelle), $admin_lender_form);
	
	print confirmation_delete("./admin.php?categ=docs&sub=lenders&action=del&id=");
	print $admin_lender_form;

	}

switch($action) {
	case 'update':
		// vérification validité des données fournies.
		$requete = " SELECT count(1) FROM lenders WHERE (lender_libelle='$form_libelle' AND idlender!='$id' )  LIMIT 1 ";
		$res = mysql_query($requete, $dbh);
		$nbr = mysql_result($res, 0, 0);
		if ($nbr > 0) {
			error_form_message($form_libelle.$msg["docs_label_already_used"]);
		} else {
			// O.K.,  now if item already exists UPDATE else INSERT
			if($id != 0) {
				$requete = "UPDATE lenders SET lender_libelle='$form_libelle' WHERE idlender=$id ";
				$res = mysql_query($requete, $dbh);
			} else {
				$requete = "INSERT INTO lenders (idlender,lender_libelle) VALUES (0, '$form_libelle') ";
				$res = mysql_query($requete, $dbh);
				}
			}
		show_lender($dbh);
		break;
	case 'add':
		if(empty($form_libelle) && empty($form_pret)) {
			lender_form();
			} else {
				show_lender($dbh);
				}
		break;
	case 'modif':
		if($id!=""){
			$requete = "SELECT lender_libelle FROM lenders WHERE idlender=$id ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				lender_form($row->lender_libelle, $id);
				} else {
					show_lender($dbh);
					}
			} else {
				show_lender($dbh);
				}
		break;
	case 'del':
		if($id!="") {
			$total = 0;
			$total = mysql_num_rows (mysql_query("select 1 from exemplaires where expl_owner='".$id."' limit 0,1", $dbh));
			$total = $total + mysql_num_rows (mysql_query("select 1 from docs_codestat where statisdoc_owner='".$id."' limit 0,1", $dbh));
			$total = $total + mysql_num_rows (mysql_query("select 1 from docs_location where locdoc_owner='".$id."' limit 0,1", $dbh));
			$total = $total + mysql_num_rows (mysql_query("select 1 from docs_section where sdoc_owner ='".$id."' limit 0,1", $dbh));
			$total = $total + mysql_num_rows (mysql_query("select 1 from docs_statut where statusdoc_owner ='".$id."' limit 0,1", $dbh));
			$total = $total + mysql_num_rows (mysql_query("select 1 from docs_type where tdoc_owner ='".$id."' limit 0,1", $dbh));
						
			if ($total==0) {
				$requete = "DELETE FROM lenders WHERE idlender=$id ";
				$res = mysql_query($requete, $dbh);
				show_lender($dbh);
				} else {
					error_message(	$msg[294], $msg[1705], 1, 'admin.php?categ=docs&sub=lenders&action=');
					}
			} else {
				error_message(	$msg[294], $msg[1706], 1, 'admin.php?categ=docs&sub=lenders&action=');
				}
		break;
	default:
		show_lender($dbh);
		break;
	}
