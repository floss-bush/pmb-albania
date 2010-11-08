<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: statut.inc.php,v 1.3 2010-07-22 14:56:27 mbertin Exp $

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
	<tr>
		<th rowspan=2 >".$msg["collstate_statut_gestion"]."</th>
		<th colspan=2 >".$msg["collstate_statut_opac"]."</th>
	</tr><tr>
		
		
		<th>".$msg["collstate_statut_libelle"]."</th>
		<th>".$msg["collstate_statut_visu_opac"]."</th>
	</tr>";//<th>".$msg["collstate_statut_libelle"]."</th> <th>".$msg["collstate_statut_visu_gestion"]."</th>

	// affichage du tableau des statuts
	$requete = "SELECT * FROM arch_statut ORDER BY archstatut_gestion_libelle ";
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
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=collstate&sub=statut&action=modif&id=$row->archstatut_id';\" ";
		print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>");
		print pmb_bidi("<td><span class='$row->archstatut_class_html'  style='margin-right: 3px;'><img src='./images/spacer.gif' width='10' height='10' /></span>") ;
		print pmb_bidi("$row->archstatut_gestion_libelle</td>"); 
		/*if($row->archstatut_visible_gestion) print "<td>X</td>";
			else print "<td>&nbsp;</td>";*/
		print "<td>$row->archstatut_opac_libelle</td>"; 
		if($row->archstatut_visible_opac) print "<td>X</td>";
			else print "<td>&nbsp;</td>";

		print "</tr>";
		}
	print "</table>
		<input class='bouton' type='button' value=' $msg[115] ' onClick=\"document.location='./admin.php?categ=collstate&sub=statut&action=add'\" />";
	}

function statut_form($id=0, $gestion_libelle="", $opac_libelle="", $visible_opac=1, $visible_gestion=1, $class_html='', $visible_opac_abon=0) {

	global $msg;
	global $admin_collstate_statut_form;
	global $charset;

	if (!$id) {
		$admin_collstate_statut_form = str_replace('!!form_title!!', $msg[115], $admin_collstate_statut_form);
		$admin_collstate_statut_form = str_replace("!!bouton_supprimer!!","",$admin_collstate_statut_form) ;
	} else {
		$admin_collstate_statut_form = str_replace("!!bouton_supprimer!!","<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />",$admin_collstate_statut_form) ;
		$admin_collstate_statut_form = str_replace('!!form_title!!', $msg[118], $admin_collstate_statut_form);
	}
	$admin_collstate_statut_form = str_replace('!!id!!', $id, $admin_collstate_statut_form);

	$admin_collstate_statut_form = str_replace('!!gestion_libelle!!', htmlentities($gestion_libelle,ENT_QUOTES, $charset), $admin_collstate_statut_form);
	$admin_collstate_statut_form = str_replace('!!libelle_suppr!!', addslashes($gestion_libelle), $admin_collstate_statut_form);
	//if ($visible_gestion) $checkbox="checked"; else $checkbox="";
	//$admin_collstate_statut_form = str_replace('!!checkbox_visible_gestion!!', $checkbox, $admin_collstate_statut_form);
	
	$admin_collstate_statut_form = str_replace('!!opac_libelle!!', htmlentities($opac_libelle,ENT_QUOTES, $charset), $admin_collstate_statut_form);
	if ($visible_opac) $checkbox="checked"; else $checkbox="";
	$admin_collstate_statut_form = str_replace('!!checkbox_visible_opac!!', $checkbox, $admin_collstate_statut_form);
		
	if ($visible_opac_abon) $checkbox="checked"; else $checkbox="";
	$admin_collstate_statut_form = str_replace('!!checkbox_visu_abon!!', $checkbox, $admin_collstate_statut_form);
	
	for ($i=1;$i<=20; $i++) {
		if ($class_html=="statutnot".$i) $checked = "checked";
		else $checked = "";
		$couleur[$i]="<span for='statutnot".$i."' class='statutnot".$i."' style='margin: 7px;'><img src='./images/spacer.gif' width='10' height='10' />
					<input id='statutnot".$i."' type=radio name='form_class_html' value='statutnot".$i."' $checked class='checkbox' /></span>";
		if ($i==10) $couleur[10].="<br />";
		elseif ($i!=20) $couleur[$i].="<b>|</b>";
	}
	
	$couleurs=implode("",$couleur);
	$admin_collstate_statut_form = str_replace('!!class_html!!', $couleurs, $admin_collstate_statut_form);

	print confirmation_delete("./admin.php?categ=collstate&sub=statut&action=del&id=");
	print $admin_collstate_statut_form;

	}

switch($action) {
	case 'update':
		if ($id) {
			if ($id==1) $visu=", archstatut_visible_gestion=1, archstatut_visible_opac='$form_visible_opac', archstatut_visible_opac_abon='$form_visu_abon' ";
				else $visu=", archstatut_visible_gestion='$form_visible_gestion', archstatut_visible_opac='$form_visible_opac', archstatut_visible_opac_abon='$form_visu_abon' "; 
			$requete = "UPDATE arch_statut SET archstatut_gestion_libelle='$form_gestion_libelle', archstatut_opac_libelle='$form_opac_libelle', archstatut_class_html='$form_class_html' $visu WHERE archstatut_id='$id' ";
			$res = mysql_query($requete, $dbh);
		} else {
			$requete = "INSERT INTO arch_statut SET archstatut_gestion_libelle='$form_gestion_libelle',archstatut_visible_gestion='$form_visible_gestion',archstatut_opac_libelle='$form_opac_libelle', archstatut_visible_opac='$form_visible_opac', archstatut_class_html='$form_class_html', archstatut_visible_opac_abon='$form_visu_abon' ";
			$res = mysql_query($requete, $dbh);
		}
		show_statut($dbh);
		break;
	case 'add':
		if (empty($form_gestion_libelle)) statut_form();
			else show_statut($dbh);
		break;
	case 'modif':
		if ($id) {
			$requete = "SELECT * FROM arch_statut WHERE archstatut_id='$id'";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				statut_form($row->archstatut_id, $row->archstatut_gestion_libelle, $row->archstatut_opac_libelle, $row->archstatut_visible_opac, $row->archstatut_visible_gestion, $row->archstatut_class_html, $row->archstatut_visible_opac_abon );
			} else {
				show_statut($dbh);
			}
		} else {
			show_statut($dbh);
		}
		break;
	case 'del':
		if ($id) {
			$total = 0;
			$total = mysql_result(mysql_query("select count(1) from collections_state where collstate_statut ='".$id."' ", $dbh), 0, 0);
			if ($total==0) {
				$requete = "DELETE FROM arch_statut WHERE archstatut_id='$id' ";
				$res = mysql_query($requete, $dbh);
				$requete = "OPTIMIZE TABLE arch_statut ";
				$res = mysql_query($requete, $dbh);
				show_statut($dbh);
				} else {
					error_message(	$msg[294], $msg["collstate_statut_used"], 1, 'admin.php?categ=collstate&sub=statut&action=');
				}
			} else show_statut($dbh);
		break;
	default:
		show_statut($dbh);
		break;
	}
