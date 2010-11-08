<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cod_stat.inc.php,v 1.15 2007-05-17 08:07:16 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des codes stat exemplaires
?>
<script type="text/javascript">
function test_form(form)
{
	if(form.form_libelle.value.length == 0)
	{
		alert("<?php echo $msg[98]; ?>");
		return false;
	}
	return true;
}

</script>
<?php
function show_codstat($dbh) {
	global $msg;
	print "<table>
	<tr>
		<th>".$msg[103]."</th>
		<th>".$msg['proprio_codage_proprio']."</th>
		<th>".$msg['import_codage']."</th>
	</tr>";

	$requete = "SELECT idcode, codestat_libelle, statisdoc_codage_import, statisdoc_owner, lender_libelle FROM docs_codestat left join lenders on statisdoc_owner=idlender ORDER BY codestat_libelle ";
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
	        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=docs&sub=codstat&action=modif&id=$row->idcode';\" ";
		if ($row->statisdoc_owner) print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>$row->codestat_libelle</i></td>"); 
                	else print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><strong>$row->codestat_libelle</strong></td>"); 
		print pmb_bidi("<td>$row->lender_libelle</td>") ;
		print pmb_bidi("<td>$row->statisdoc_codage_import</td></tr>");
		}
	print "</table>
		<input class='bouton' type='button' value=' $msg[99] ' onClick=\"document.location='./admin.php?categ=docs&sub=codstat&action=add'\" />";
	}

function codstat_form($libelle="", $statisdoc_codage_import="", $statisdoc_owner=0, $id=0) {
	global $msg;
	global $charset;
	global $admin_codstat_form;

	$admin_codstat_form = str_replace('!!id!!', $id, $admin_codstat_form);
	if(!$id) $admin_codstat_form = str_replace('!!form_title!!', $msg[101], $admin_codstat_form);
		else $admin_codstat_form = str_replace('!!form_title!!', $msg[102], $admin_codstat_form);

	$admin_codstat_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_codstat_form);
	$admin_codstat_form = str_replace('!!libelle_suppr!!', addslashes($libelle), $admin_codstat_form);
	$admin_codstat_form = str_replace('!!statisdoc_codage_import!!', $statisdoc_codage_import, $admin_codstat_form);
	$combo_lender= gen_liste ("select idlender, lender_libelle from lenders order by lender_libelle ", "idlender", "lender_libelle", "form_statisdoc_owner", "", $statisdoc_owner, 0, $msg[556],0,$msg["proprio_generique_biblio"]) ;
	$admin_codstat_form = str_replace('!!lender!!', $combo_lender, $admin_codstat_form);

	print confirmation_delete("./admin.php?categ=docs&sub=codstat&action=del&id=");
	print $admin_codstat_form;

	}

switch($action) {
	case 'update':
	
		// vérification validité des données fournies.
		$requete = " SELECT count(1) FROM docs_codestat WHERE (codestat_libelle='$form_libelle' AND idcode!='$id' )  LIMIT 1 ";
		$res = mysql_query($requete, $dbh);
		$nbr = mysql_result($res, 0, 0);
		if ($nbr > 0) {
			error_form_message($form_libelle.$msg["docs_label_already_used"]);
		} else {
			// O.K.  if item already exists UPDATE else INSERT
			if($id) {
				$requete = "UPDATE docs_codestat SET codestat_libelle='$form_libelle', statisdoc_codage_import='$form_statisdoc_codage_import', statisdoc_owner='$form_statisdoc_owner' WHERE idcode=$id  ";
				$res = mysql_query($requete, $dbh);
			} else {
				$requete = "INSERT INTO docs_codestat (idcode,codestat_libelle,statisdoc_codage_import,statisdoc_owner) VALUES ('', '$form_libelle','$form_statisdoc_codage_import','$form_statisdoc_owner') ";
				$res = mysql_query($requete, $dbh);
			}
		}
		show_codstat($dbh);
		break;
	case 'add':
		if(empty($form_libelle) && empty($form_pret)) {
			codstat_form();
		} else {
			show_codstat($dbh);
		}
		break;
	case 'modif':
		if($id){
			$requete = "SELECT codestat_libelle, statisdoc_codage_import, statisdoc_owner FROM docs_codestat WHERE idcode=$id ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				codstat_form($row->codestat_libelle,$row->statisdoc_codage_import,$row->statisdoc_owner, $id);
			} else {
				show_codstat($dbh);
			}
		} else {
			show_codstat($dbh);
		}
		break;
	case 'del':
		if($id) {
			$total = mysql_result (mysql_query("select count(1) from exemplaires where expl_codestat ='".$id."' ", $dbh), 0, 0);
			if ($total==0) {
				$requete = "DELETE FROM docs_codestat WHERE idcode=$id ";
				$res = mysql_query($requete, $dbh);
				show_codstat($dbh);
				} else {
					error_message(	$msg[294], $msg[1701], 1, 'admin.php?categ=docs&sub=codstat&action=');
					}
			} else show_codstat($dbh);
		break;
	default:
		show_codstat($dbh);
		break;
}
