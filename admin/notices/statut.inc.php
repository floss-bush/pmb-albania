<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: statut.inc.php,v 1.13 2009-05-16 11:11:54 dbellamy Exp $

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
		<th colspan=2>".$msg[noti_statut_gestion]."</th>
		<th colspan=4>".$msg[noti_statut_opac]."</th>
	</tr><tr>
		<th>".$msg[noti_statut_libelle]."</th>
		<th>".$msg[noti_statut_visu_gestion]."</th>
		<th>".$msg[noti_statut_libelle]."</th>
		<th>".$msg[noti_statut_visu_opac]."</th>
		<th>".$msg[noti_statut_visu_expl]."</th>
		<th>".$msg[noti_statut_visu_explnum]."</th>
	</tr>";

	// affichage du tableau des statuts
	$requete = "SELECT id_notice_statut, gestion_libelle, opac_libelle, ";
	$requete .= "notice_visible_opac, notice_visible_gestion, notice_visible_opac_abon,";
	$requete .= "expl_visible_opac, expl_visible_opac_abon, ";
	$requete .= "explnum_visible_opac, explnum_visible_opac_abon, ";
	$requete .= "class_html FROM notice_statut ORDER BY gestion_libelle ";
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
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=notices&sub=statut&action=modif&id=$row->id_notice_statut';\" ";
		print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>");
		print pmb_bidi("<td><span class='$row->class_html'  style='margin-right: 3px;'><img src='./images/spacer.gif' width='10' height='10' /></span>") ;
		if ($row->id_notice_statut<3) print pmb_bidi("<strong>$row->gestion_libelle</strong></td>"); 
		else print pmb_bidi("$row->gestion_libelle</td>"); 
		if($row->notice_visible_gestion) print "<td>X</td>";
			else print "<td>&nbsp;</td>";
		print "<td>$row->opac_libelle</td>"; 
		if($row->notice_visible_opac) print "<td>X</td>";
			else print "<td>&nbsp;</td>";
		if($row->expl_visible_opac) print "<td>X</td>";
			else print "<td>&nbsp;</td>";
		if($row->explnum_visible_opac) print "<td>X</td>";
			else print "<td>&nbsp;</td>";
		print "</tr>";
		}
	print "</table>
		<input class='bouton' type='button' value=' $msg[115] ' onClick=\"document.location='./admin.php?categ=notices&sub=statut&action=add'\" />";
	}

function statut_form($id=0, $gestion_libelle="", $opac_libelle="", $visible_opac=1, $visible_gestion=1, $expl_visible_opac=1, $class_html='', $visible_opac_abon=0, $expl_visible_opac_abon=0, $explnum_visible_opac=1, $explnum_visible_opac_abon=0) {

	global $msg;
	global $admin_notice_statut_form;
	global $charset;

	if (!$id) {
		$admin_notice_statut_form = str_replace('!!form_title!!', $msg[115], $admin_notice_statut_form);
		$admin_notice_statut_form = str_replace("!!bouton_supprimer!!","",$admin_notice_statut_form) ;
		} else {
			$admin_notice_statut_form = str_replace("!!bouton_supprimer!!","<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />",$admin_notice_statut_form) ;
			$admin_notice_statut_form = str_replace('!!form_title!!', $msg[118], $admin_notice_statut_form);
			}
	$admin_notice_statut_form = str_replace('!!id!!', $id, $admin_notice_statut_form);

	$admin_notice_statut_form = str_replace('!!gestion_libelle!!', htmlentities($gestion_libelle,ENT_QUOTES, $charset), $admin_notice_statut_form);
	$admin_notice_statut_form = str_replace('!!libelle_suppr!!', addslashes($gestion_libelle), $admin_notice_statut_form);
	if ($visible_gestion) $checkbox="checked"; else $checkbox="";
	$admin_notice_statut_form = str_replace('!!checkbox_visible_gestion!!', $checkbox, $admin_notice_statut_form);
	
	$admin_notice_statut_form = str_replace('!!opac_libelle!!', htmlentities($opac_libelle,ENT_QUOTES, $charset), $admin_notice_statut_form);
	if ($visible_opac) $checkbox="checked"; else $checkbox="";
	$admin_notice_statut_form = str_replace('!!checkbox_visible_opac!!', $checkbox, $admin_notice_statut_form);
	
	if ($expl_visible_opac) $checkbox="checked"; else $checkbox="";
	$admin_notice_statut_form = str_replace('!!checkbox_visu_expl!!', $checkbox, $admin_notice_statut_form);
	
	if ($visible_opac_abon) $checkbox="checked"; else $checkbox="";
	$admin_notice_statut_form = str_replace('!!checkbox_visu_abon!!', $checkbox, $admin_notice_statut_form);
	
	// $expl_visible_opac_abon=0, $explnum_visible_opac=1, $explnum_visible_opac_abon=0
	if ($expl_visible_opac_abon) $checkbox="checked"; else $checkbox="";
	$admin_notice_statut_form = str_replace('!!checkbox_expl_visu_abon!!', $checkbox, $admin_notice_statut_form);

	if ($explnum_visible_opac) $checkbox="checked"; else $checkbox="";
	$admin_notice_statut_form = str_replace('!!checkbox_explnum_visu!!', $checkbox, $admin_notice_statut_form);

	if ($explnum_visible_opac_abon) $checkbox="checked"; else $checkbox="";
	$admin_notice_statut_form = str_replace('!!checkbox_explnum_visu_abon!!', $checkbox, $admin_notice_statut_form);
	
	for ($i=1;$i<=20; $i++) {
		if ($class_html=="statutnot".$i) $checked = "checked";
			else $checked = "";
		$couleur[$i]="<span for='statutnot".$i."' class='statutnot".$i."' style='margin: 7px;'><img src='./images/spacer.gif' width='10' height='10' />
					<input id='statutnot".$i."' type=radio name='form_class_html' value='statutnot".$i."' $checked class='checkbox' /></span>";
		if ($i==10) $couleur[10].="<br />";
		elseif ($i!=20) $couleur[$i].="<b>|</b>";
		}
	
	$couleurs=implode("",$couleur);
	$admin_notice_statut_form = str_replace('!!class_html!!', $couleurs, $admin_notice_statut_form);

	print confirmation_delete("./admin.php?categ=notices&sub=statut&action=del&id=");
	print $admin_notice_statut_form;

	}

switch($action) {
	case 'update':
		if ($id) {
			if ($id==1) $visu=", notice_visible_gestion=1, notice_visible_opac='$form_visible_opac', expl_visible_opac='$form_visu_expl', notice_visible_opac_abon='$form_visu_abon', expl_visible_opac_abon='$form_expl_visu_abon', explnum_visible_opac='$form_explnum_visu', explnum_visible_opac_abon='$form_explnum_visu_abon' ";
				else $visu=", notice_visible_gestion='$form_visible_gestion', notice_visible_opac='$form_visible_opac', expl_visible_opac='$form_visu_expl', notice_visible_opac_abon='$form_visu_abon', expl_visible_opac_abon='$form_expl_visu_abon', explnum_visible_opac='$form_explnum_visu', explnum_visible_opac_abon='$form_explnum_visu_abon' "; 
			$requete = "UPDATE notice_statut SET gestion_libelle='$form_gestion_libelle', opac_libelle='$form_opac_libelle', class_html='$form_class_html' $visu WHERE id_notice_statut='$id' ";
			$res = mysql_query($requete, $dbh);
			} else {
				$requete = "INSERT INTO notice_statut SET gestion_libelle='$form_gestion_libelle',notice_visible_gestion='$form_visible_gestion',opac_libelle='$form_opac_libelle', notice_visible_opac='$form_visible_opac', expl_visible_opac='$form_visu_expl', class_html='$form_class_html', notice_visible_opac_abon='$form_visu_abon', expl_visible_opac_abon='$form_expl_visu_abon', explnum_visible_opac='$form_explnum_visu', explnum_visible_opac_abon='$form_explnum_visu_abon' ";
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
			$requete = "SELECT id_notice_statut, gestion_libelle, opac_libelle, notice_visible_opac, notice_visible_gestion, expl_visible_opac, class_html, notice_visible_opac_abon, expl_visible_opac_abon, explnum_visible_opac, explnum_visible_opac_abon FROM notice_statut WHERE id_notice_statut='$id'";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				statut_form($row->id_notice_statut, $row->gestion_libelle, $row->opac_libelle, $row->notice_visible_opac, $row->notice_visible_gestion, $row->expl_visible_opac, $row->class_html, $row->notice_visible_opac_abon, $row->expl_visible_opac_abon, $row->explnum_visible_opac, $row->explnum_visible_opac_abon );
				} else {
					show_statut($dbh);
					}
			} else {
				show_statut($dbh);
				}
		break;
	case 'del':
		if ($id && $id!=1 && $id!=2) {
			$total = 0;
			$total = mysql_result(mysql_query("select count(1) from notices where statut ='".$id."' ", $dbh), 0, 0);
			if ($total==0) {
				$requete = "DELETE FROM notice_statut WHERE id_notice_statut='$id' ";
				$res = mysql_query($requete, $dbh);
				$requete = "OPTIMIZE TABLE notice_statut ";
				$res = mysql_query($requete, $dbh);
				show_statut($dbh);
				} else {
					error_message(	$msg[noti_statut_noti], $msg[noti_statut_used], 1, 'admin.php?categ=notices&sub=statut&action=');
					}
			} else show_statut($dbh);
		break;
	default:
		show_statut($dbh);
		break;
	}
