<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abts.inc.php,v 1.7 2009-12-21 10:02:55 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Gestion des paramètres des abonnements
require_once("$include_path/templates/finance.tpl.php");
?>

<script type="text/javascript">
function test_form(form)
{
	if(form.typ_abt_libelle.value.length == 0)
	{
		alert("<?php echo $msg[98]; ?>");
		return false;
	}
	return true;
}

</script>
<?php
function show_abts($dbh) {
	global $msg;	
	global $charset;
	
	print "<table>
		<tr>
		<th>".$msg[103]."</th>
		<th style='display:none'>".$msg["type_abts_prepay"]."</th>
		<th style='display:none'>".$msg["type_abts_prepay_dflt"]."</th>
		<th>".$msg["type_abts_tarif"]."</th>
		<th>".$msg["type_abts_caution"]."</th>
		</tr>";

	// affichage du tableau des utilisateurs

	$requete = "SELECT id_type_abt, type_abt_libelle, prepay, prepay_deflt_mnt,tarif,caution FROM type_abts ORDER BY type_abt_libelle,id_type_abt";
	$res = mysql_query($requete, $dbh);

	$nbr = mysql_num_rows($res);

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_row($res);
		if ($row[2]) $prepay="x";
		
		if ($parity % 2) {
			$pair_impair = "even";
			} else {
				$pair_impair = "odd";
				}
		$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=finance&sub=abts&action=modif&id=$row[0]';\" ";
			print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td>".htmlentities($row[1],ENT_QUOTES,$charset)."</td>
			<td style='text-align:center;display:none'>$prepay</td><td style='display:none'>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td>
						</tr>");
		}
	print "</table>
		<input class='bouton' type='button' value=\" ".$msg["type_abts_add"]." \" onClick=\"document.location='./admin.php?categ=finance&sub=abts&action=add'\" />";
	}

function abts_form($libelle="", $id=0, $prepay=0, $prepay_mnt_deflt=0, $tarif=0, $commentaire="", $caution=0, $localisations="")
{
	global $msg;
	global $finance_abts_form ;
	global $charset;
	
	$finance_abts_form = str_replace('!!id!!', $id, $finance_abts_form);
	if(!$id) $finance_abts_form = str_replace('!!form_title!!', $msg["type_abts_add"], $finance_abts_form);
		else $finance_abts_form = str_replace('!!form_title!!', $msg["type_abts_update"], $finance_abts_form);

	if ($prepay) $prepay_checked="checked";

	$finance_abts_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $finance_abts_form);
	$finance_abts_form = str_replace('!!libelle_suppr!!', htmlentities($libelle,ENT_QUOTES, $charset), $finance_abts_form);
	$finance_abts_form = str_replace('!!commentaire!!', htmlentities($commentaire,ENT_QUOTES, $charset), $finance_abts_form);
	$finance_abts_form = str_replace('!!prepay_checked!!', $prepay_checked, $finance_abts_form);
	$finance_abts_form = str_replace('!!prepay_deflt_mnt!!', htmlentities($prepay_mnt_deflt,ENT_QUOTES, $charset), $finance_abts_form);
	$finance_abts_form = str_replace('!!tarif!!', htmlentities($tarif,ENT_QUOTES, $charset), $finance_abts_form);
	$finance_abts_form = str_replace('!!caution!!', htmlentities($caution,ENT_QUOTES, $charset), $finance_abts_form);
	
	//Localisations
	$loc_checkbox="";
	$loc=explode(",",$localisations);
	$requete="select idlocation, location_libelle from docs_location";
	$resultat=mysql_query($requete);
	$n=0;
	$c=0;
	if ($resultat) {
		while ($l=mysql_fetch_object($resultat)) {
			if ($c==0) $loc_checkbox.="<div class='row'>";
			$loc_checkbox.="<div class='colonne3'>";
			$loc_checkbox.="<input type='checkbox' name='localisation[]' id='l_$n' value='".$l->idlocation."' ";
			$as=array_search($l->idlocation,$loc);
			if (($as!==false)&&($as!==null)) $loc_checkbox.="checked";
			$loc_checkbox.=">";
			$loc_checkbox.="<label class='class='etiquette' for='l_$n'>".htmlentities($l->location_libelle,ENT_QUOTES,$charset)."</label>&nbsp;";
			$loc_checkbox.="</div>";
			$n++;
			$c++;
			if ($c==3) {
				$c=0;
				$loc_checkbox.="</div>";
			}
		}
		if ($c!=0) $loc_checkbox.="<div class='colonne_suite'>&nbsp;</div></div>"; 
		$loc_checkbox.="<div class='row'></div>"; 
	}
	$finance_abts_form = str_replace('!!localisations!!', $loc_checkbox, $finance_abts_form);
	
	print confirmation_delete("./admin.php?categ=finance&sub=abts&action=del&id=");
	print $finance_abts_form;
}

switch($action) {
	case 'update':
		// O.k., now if the id already exist UPDATE else INSERT
		if(!empty($typ_abt_libelle)) {
			if($id) {
				if (count($localisation))
					$localisations=implode(",",$localisation);
				else $localisations="";
				$requete = "UPDATE type_abts SET type_abt_libelle='".$typ_abt_libelle."', prepay='$prepay', prepay_deflt_mnt='$prepay_deflt_mnt', tarif='$tarif', commentaire='$commentaire', caution='$caution', localisations='".$localisations."' WHERE id_type_abt=$id ";
				$res = mysql_query($requete, $dbh);
			} else {
				if (count($localisation))
					$localisations=implode(",",$localisation);
				else $localisations="";
				$requete = "INSERT INTO type_abts (id_type_abt,type_abt_libelle, prepay, prepay_deflt_mnt, tarif, commentaire, caution, localisations) VALUES ('', '$typ_abt_libelle','$prepay', '$prepay_deflt_mnt', '$tarif', '$commentaire', '$caution','$localisations') ";
				$res = mysql_query($requete, $dbh);
			}
		}
		show_abts($dbh);
		break;
	case 'add':
		abts_form();
		break;
	case 'modif':
		if($id){
			$requete = "SELECT id_type_abt, type_abt_libelle, prepay, prepay_deflt_mnt,tarif,commentaire, caution, localisations FROM type_abts WHERE id_type_abt=$id LIMIT 1";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_row($res);
				abts_form($row[1], $id,$row[2],$row[3],$row[4],$row[5],$row[6],$row[7]);
			} else {
				show_abts($dbh);
			}
		} else {
			show_abts($dbh);
		}
		break;
	case 'del':
		if($id) {
			$total = 0;
			$total = mysql_result(mysql_query("select count(1) from empr where type_abt ='".$id."' ", $dbh), 0, 0);
			if ($total==0) {
				$requete = "DELETE FROM type_abts WHERE id_type_abt=$id ;";
				$res = mysql_query($requete, $dbh);
				$requete = "OPTIMIZE TABLE type_abts ";
				$res = mysql_query($requete, $dbh);
				show_abts($dbh);
				} else {
					error_message(	$msg["type_abts_type"], $msg["type_abts_del_error"], 1, 'admin.php?categ=finance&sub=abts&action=');
					}
			} else show_abts($dbh);
		break;
	default:
		show_abts($dbh);
		break;
}


?>