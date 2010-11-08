<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categ_empr.inc.php,v 1.11 2007-03-10 08:32:24 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

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
function show_section($dbh) {
	global $msg;
	global $pmb_gestion_financiere,$pmb_gestion_abonnement;
	if ($pmb_gestion_financiere) $gestion_abts=$pmb_gestion_abonnement; else $gestion_abts=0;
	
	print "<table>
	<tr>
		<th>".$msg[103]."</th>
		<th>".$msg[1400]."</th>";
	if ($gestion_abts) print "<th>".$msg["empr_categ_tarif"]."</th>";
	print "
	</tr>";

	// affichage du tableau des utilisateurs

	$requete = "SELECT id_categ_empr, libelle, duree_adhesion, tarif_abt FROM empr_categ ORDER BY libelle, id_categ_empr";
	$res = mysql_query($requete, $dbh);

	$nbr = mysql_num_rows($res);

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_row($res);
		if ($parity % 2) {
			$pair_impair = "even";
			} else {
				$pair_impair = "odd";
				}
		$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=empr&sub=categ&action=modif&id=$row[0]';\" ";
			print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td>$row[1]</td>
						<td>$row[2]</td>");
			if ($gestion_abts==1) 
				$tarif=$row[3]; 
			else if ($gestion_abts==2) 
				$tarif=$msg["finance_see_finance"];
			if ($gestion_abts) print "<td>".$tarif."</td>";
			print  "
					</tr>";
		}
	print "</table>
		<input class='bouton' type='button' value=' $msg[524] ' onClick=\"document.location='./admin.php?categ=empr&sub=categ&action=add'\" />";
	}

function categempr_form($libelle="", $id=0, $duree_adhesion=365, $tarif="0.00") {
	global $msg;
	global $admin_categlec_form ;
	global $charset;
	global $pmb_gestion_financiere,$pmb_gestion_abonnement;
	
	$admin_categlec_form = str_replace('!!id!!', $id, $admin_categlec_form);
	if(!$id) $admin_categlec_form = str_replace('!!form_title!!', $msg[524], $admin_categlec_form);
		else $admin_categlec_form = str_replace('!!form_title!!', $msg[525], $admin_categlec_form);

	$admin_categlec_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_categlec_form);
	$admin_categlec_form = str_replace('!!libelle_suppr!!', addslashes($libelle), $admin_categlec_form);
	$admin_categlec_form = str_replace('!!duree_adhesion!!', htmlentities($duree_adhesion,ENT_QUOTES, $charset), $admin_categlec_form);	
	
	if (($pmb_gestion_financiere)&&($pmb_gestion_abonnement==1)) {
		$tarif_adhesion="
		<div class='row'>
			<label class='etiquette' for='form_tarif_adhesion'>".$msg["empr_categ_tarif"]."</label>
		</div>
		<div class='row'>
			<input type=text name='form_tarif_adhesion' id='form_tarif_adhesion' value='".htmlentities($tarif,ENT_QUOTES,$charset)."' maxlength='10' class='saisie-5em' />
			</div>
		";
	} else $tarif_adhesion="";
	$admin_categlec_form = str_replace('!!tarif_adhesion!!', $tarif_adhesion, $admin_categlec_form);	
	
	print confirmation_delete("./admin.php?categ=empr&sub=categ&action=del&id=");
	print $admin_categlec_form;

	}

switch($action) {
	case 'update':
		// no duplication
		$requete = " SELECT count(1) FROM empr_categ WHERE (libelle='$form_libelle' AND id_categ_empr!='$id' )  LIMIT 1 ";
		$res = mysql_query($requete, $dbh);
		$nbr = mysql_result($res, 0, 0);
		if ($nbr > 0) {
				error_form_message($form_libelle.$msg["docs_label_already_used"]);
		} else {
			// O.k., now if the id already exist UPDATE else INSERT
			if(!empty($form_libelle)) {
				if($id) {
					$requete = "UPDATE empr_categ SET libelle='$form_libelle', duree_adhesion='$form_duree_adhesion', tarif_abt='".$form_tarif_adhesion."' WHERE id_categ_empr=$id ";
					$res = mysql_query($requete, $dbh);
				} else {
					$requete = "SELECT count(1) FROM empr_categ WHERE libelle='$form_libelle' LIMIT 1 ";
					$res = mysql_query($requete, $dbh);
					$nbr = mysql_result($res, 0, 0);
					if($nbr == 0) {
						$requete = "INSERT INTO empr_categ (id_categ_empr,libelle,duree_adhesion,tarif_abt) VALUES ('', '$form_libelle','$form_duree_adhesion','".$form_tarif_adhesion."') ";
						$res = mysql_query($requete, $dbh);
					}
				}
			}
		}
		show_section($dbh);
		break;
	case 'add':
		if(empty($form_libelle) && empty($form_pret)) {
			categempr_form();
		} else {
			show_section($dbh);
		}
		break;
	case 'modif':
		if($id){
			$requete = "SELECT libelle, duree_adhesion, tarif_abt FROM empr_categ WHERE id_categ_empr=$id LIMIT 1 ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_row($res);
				categempr_form($row[0], $id, $row[1],$row[2]);
			} else {
				show_section($dbh);
			}
		} else {
			show_section($dbh);
		}
		break;
	case 'del':
		if($id) {
			$total = 0;
			$total = mysql_result(mysql_query("select count(1) from empr where empr_categ ='".$id."' ", $dbh), 0, 0);
			if ($total==0) {
				$requete = "DELETE FROM empr_categ WHERE id_categ_empr='$id' ";
				$res = mysql_query($requete, $dbh);
				$requete = "OPTIMIZE TABLE empr_categ ";
				$res = mysql_query($requete, $dbh);
				show_section($dbh);
				} else {
					error_message(	$msg[294], $msg[1708], 1, 'admin.php?categ=empr&sub=categ&action=');
					}
			} else show_section($dbh);
		break;
	default:
		show_section($dbh);
		break;
}
