<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des classements d'actions personnalisables
?>
<script type="text/javascript">
function test_form(form) {
	if(form.form_libelle.value.length == 0) {
		alert("<?php echo $msg[98]; ?>");
		return false;
	}
	return true;
}

</script>
<?php
function show_clas($dbh) {
	global $msg;
	print "<table>
	<tr>
		<th>".$msg[proc_clas_lib]."</th>
	</tr>";

	$requete = "SELECT idproc_classement,libproc_classement FROM procs_classements ORDER BY libproc_classement ";
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
        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=proc&sub=clas&action=modif&idproc_classement=$row->idproc_classement';\" ";
		print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><strong>$row->libproc_classement</strong></td>"; 
		print "</tr>";
		}
	print "</table>
		<input class='bouton' type='button' value=' $msg[proc_clas_bt_add] ' onClick=\"document.location='./admin.php?categ=proc&sub=clas&action=add'\" />";
	}

function clas_form($libproc_classement="", $idproc_classement=0) {
	global $msg;
	global $charset;
	global $admin_procs_clas_form;

	$admin_procs_clas_form = str_replace('!!idproc_classement!!', $idproc_classement, $admin_procs_clas_form);
	if(!$idproc_classement) $admin_procs_clas_form = str_replace('!!form_title!!', $msg[proc_clas_bt_add], $admin_procs_clas_form);
	else $admin_procs_clas_form = str_replace('!!form_title!!', $msg["proc_clas_modif"], $admin_procs_clas_form);

	$admin_procs_clas_form = str_replace('!!libelle!!', htmlentities($libproc_classement,ENT_QUOTES, $charset), $admin_procs_clas_form);
	$admin_procs_clas_form = str_replace('!!libelle_suppr!!', addslashes($libproc_classement), $admin_procs_clas_form);

	print confirmation_delete("./admin.php?categ=proc&sub=clas&action=del&idproc_classement=");
	print $admin_procs_clas_form;

	}

switch($action) {
	case 'update':
		// vérification validité des données fournies.
		$requete = " SELECT count(1) FROM procs_classements WHERE (libproc_classement='$form_libproc_classement' AND idproc_classement!='$idproc_classement' ) LIMIT 1 ";
		$res = mysql_query($requete, $dbh);
		$nbr = mysql_result($res, 0, 0);
		if(!trim($form_libproc_classement)){
			error_form_message($msg["acquisition_lib_liv_inv"]);
		}elseif ($nbr > 0) {
			error_form_message($form_libelle.$msg["proc_clas_lib_already_used"]);
		} else {
			// O.K.  if item already exists UPDATE else INSERT
			if ($idproc_classement) {
				$requete = "UPDATE procs_classements SET libproc_classement='$form_libproc_classement' WHERE idproc_classement='$idproc_classement' ";
				$res = mysql_query($requete, $dbh);
			} else {
				$requete = "INSERT INTO procs_classements SET libproc_classement='$form_libproc_classement' ";
				$res = mysql_query($requete, $dbh);
			}
		}
		show_clas($dbh);
		break;
	case 'add':
		if (empty($form_libproc_classement)) {
			clas_form();
		} else {
			show_clas($dbh);
		}
		break;
	case 'modif':
		if ($idproc_classement) {
			$requete = "SELECT libproc_classement FROM procs_classements WHERE idproc_classement='$idproc_classement' ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				clas_form($row->libproc_classement, $idproc_classement);
			} else {
				show_clas($dbh);
			}
		} else {
			show_clas($dbh);
		}
		break;
	case 'del':
		if ($idproc_classement) {
			$total = mysql_result (mysql_query("select count(1) from procs where num_classement='".$idproc_classement."' ", $dbh), 0, 0);
			if ($total==0) {
				$requete = "DELETE FROM procs_classements WHERE idproc_classement='$idproc_classement' ";
				$res = mysql_query($requete, $dbh);
				show_clas($dbh);
			} else {
				error_message(	$msg[proc_clas], $msg[proc_clas_used], 1, 'admin.php?categ=proc&sub=clas&action=');
			}
		} else show_clas($dbh);
		break;
	default:
		show_clas($dbh);
		break;
}
