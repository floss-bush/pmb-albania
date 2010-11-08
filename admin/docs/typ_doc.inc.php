<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: typ_doc.inc.php,v 1.17 2007-05-17 08:07:16 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// modificato da MARCO VANINETTI
// gestion des codes type document
?>

<script type="text/javascript">
function test_form(form)
{
	if(form.form_libelle.value.length == 0)
	{
		alert("<?php echo $msg[98]; ?>");
		return false;
	}
	if(isNaN(form.form_pret.value) || form.form_pret.value.length == 0)
	{
		alert("<?php echo $msg[119]; ?>");
		return false;
	}
	if(isNaN(form.form_resa.value) || form.form_resa.value.length == 0)
	{
		alert("<?php echo $msg[119]; ?>");
		return false;
	}
	return true;

	return true;
}

</script>

<?php
function show_typdoc($dbh) {
	global $msg;
	global $pmb_quotas_avances;
	global $pmb_gestion_financiere,$pmb_gestion_tarif_prets;
	global $charset;
	
	print "<table>
	<tr>
		<th>".$msg[103]."</th>
		<th>".$msg[120]."</th>
		<th>".$msg[duree_resa]."</th>";
	if (($pmb_gestion_financiere)&&($pmb_gestion_tarif_prets)) {
		print "
			<th>".$msg["typ_doc_tarif"]."</th>";
	}
	print "
		<th>".$msg['proprio_codage_proprio']."</th>
		<th>".$msg['import_codage']."</th>
	</tr>
	";

	$requete = "SELECT idtyp_doc, tdoc_libelle, duree_pret, duree_resa, tdoc_owner, tdoc_codage_import, tdoc_owner, lender_libelle, tarif_pret FROM docs_type left join lenders on tdoc_owner=idlender ORDER BY tdoc_libelle, idtyp_doc";
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
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=docs&sub=typdoc&action=modif&id=$row->idtyp_doc';\" ";
		if ($row->tdoc_owner) print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>$row->tdoc_libelle</i></td>");
                	else print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><strong>$row->tdoc_libelle</strong></td>"); 
                print "<td>";
                if (!$pmb_quotas_avances) print $row->duree_pret." ".$msg[121]; 
                	else print $msg["quotas_see_quotas"];
                print "</td><td>";
                if (!$pmb_quotas_avances) print $row->duree_resa." ".$msg[121]; 
                	else print $msg["quotas_see_quotas"]; 
                if (($pmb_gestion_financiere)&&($pmb_gestion_tarif_prets)) {
                	print "</td>";
                	print "<td>";
                	if ($pmb_gestion_tarif_prets==1) print htmlentities($row->tarif_pret,ENT_QUOTES,$charset); else print $msg["finance_see_finance"];
                }
                print pmb_bidi("</td><td>$row->lender_libelle</td>") ;
                print pmb_bidi("<td>$row->tdoc_codage_import</td></tr>");
		}
	print "</table>
		<input class='bouton' type='button' value=' $msg[122] ' onClick=\"document.location='./admin.php?categ=docs&sub=typdoc&action=add'\" />";
	}

function typdoc_form($libelle="", $pret="31", $resa="15", $tdoc_codage_import="", $tdoc_owner=0, $id=0, $tarif="0.00") {
	global $msg;
	global $admin_typdoc_form;
	global $charset;
	global $pmb_gestion_financiere,$pmb_gestion_tarif_prets;
	
	$admin_typdoc_form = str_replace('!!id!!', $id, $admin_typdoc_form);

	if(!$id) $admin_typdoc_form = str_replace('!!form_title!!', $msg[122], $admin_typdoc_form);
		else $admin_typdoc_form = str_replace('!!form_title!!', $msg[124], $admin_typdoc_form);
	$admin_typdoc_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_typdoc_form);
	$admin_typdoc_form = str_replace('!!libelle_suppr!!', addslashes($libelle), $admin_typdoc_form);
	$admin_typdoc_form = str_replace('!!pret!!', $pret, $admin_typdoc_form);
	$admin_typdoc_form = str_replace('!!resa!!', $resa, $admin_typdoc_form);
	$admin_typdoc_form = str_replace('!!tdoc_codage_import!!', $tdoc_codage_import, $admin_typdoc_form);
	$combo_lender= gen_liste ("select idlender, lender_libelle from lenders order by lender_libelle ", "idlender", "lender_libelle", "form_tdoc_owner", "", $tdoc_owner, 0, $msg[556],0,$msg["proprio_generique_biblio"]) ;
	$admin_typdoc_form = str_replace('!!lender!!', $combo_lender, $admin_typdoc_form);
	
	if (($pmb_gestion_financiere)&&($pmb_gestion_tarif_prets==1)) {
		$tarif_pret="
		<div class='row'>
			<label class='etiquette' for='form_tarif_pret'>".$msg["typ_doc_tarif"]."</label>
		</div>
		<div class='row'>
			<input type=text name='form_tarif_pret' id='form_tarif_pret' value='".htmlentities($tarif,ENT_QUOTES,$charset)."' maxlength='10' class='saisie-5em' />
			</div>
		";
	} else $tarif_pret="";
	$admin_typdoc_form = str_replace('!!tarif_pret!!', $tarif_pret, $admin_typdoc_form);
	print confirmation_delete("./admin.php?categ=docs&sub=typdoc&action=del&id=");
	print $admin_typdoc_form;
	}

switch($action) {
	case 'update':
		// vérification validité des données fournies.
		$requete = " SELECT count(1) FROM docs_type WHERE (tdoc_libelle='$form_libelle' AND idtyp_doc!='$id' )  LIMIT 1 ";
		$res = mysql_query($requete, $dbh);
		$nbr = mysql_result($res, 0, 0);
		if ($nbr > 0) {
				error_form_message($form_libelle.$msg["docs_label_already_used"]);
		} 
		else {
			// O.k., now if the id already exist UPDATE else INSERT
			if($id) {
				$requete = "UPDATE docs_type SET tdoc_libelle='$form_libelle', duree_pret='$form_pret', duree_resa='$form_resa', tdoc_codage_import='$form_tdoc_codage_import', tdoc_owner='$form_tdoc_owner', tarif_pret='$form_tarif_pret' WHERE idtyp_doc=$id ";
				$res = mysql_query($requete, $dbh);
			} else {
				$requete = "INSERT INTO docs_type (idtyp_doc,tdoc_libelle,duree_pret,duree_resa,tdoc_codage_import,tdoc_owner, tarif_pret) VALUES ('', '$form_libelle', '$form_pret', '$form_resa', '$form_tdoc_codage_import', '$form_tdoc_owner','$form_tarif_pret') ";
				$res = mysql_query($requete, $dbh);
				}
		}
		show_typdoc($dbh);
		break;
	case 'add':
		if(empty($form_libelle) && empty($form_pret) && empty($form_resa)) typdoc_form();
			else show_typdoc($dbh);
		break;
	case 'modif':
		if($id){
			$requete = "SELECT tdoc_libelle,duree_pret,duree_resa,tdoc_codage_import,tdoc_owner, tarif_pret FROM docs_type WHERE idtyp_doc='$id' LIMIT 1 ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				typdoc_form($row->tdoc_libelle, $row->duree_pret, $row->duree_resa, $row->tdoc_codage_import, $row->tdoc_owner, $id, $row->tarif_pret);
				} else {
					show_typdoc($dbh);
					}
			} else {
				show_typdoc($dbh);
				}
		break;
	case 'del':
		if($id) {
			// requête sur 'exemplaires' pour voir si ce typdoc est encore utilisé
			$total = 0;
			$total = mysql_result(mysql_query("select count(1) from exemplaires where expl_typdoc ='".$id."' ", $dbh), 0, 0);
			if ($total==0) {
				$requete = "DELETE FROM docs_type WHERE idtyp_doc=$id ";
				$res = mysql_query($requete, $dbh);
				show_typdoc($dbh);
				} else {
					error_message(	$msg[294], $msg[1700], 1, 'admin.php?categ=docs&sub=typdoc&action=');
					}
			} else show_typdoc($dbh);
		break;
	default:
		show_typdoc($dbh);
		break;
	}
