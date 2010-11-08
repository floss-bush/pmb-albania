<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: origine_notice.inc.php,v 1.7 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des codes statut exemplaires
?>
<script type="text/javascript">
function test_form(form)
{
	if(form.form_nom.value.length == 0)
	{
		alert("<?php echo $msg[98] ?>");
		return false;
	}
	return true;
}
</script>

<?php
function show_orinot($dbh) {
	global $msg;
	global $charset ;

	print "<table>
	<tr>
		<th>$msg[orinot_nom]</th>
		<th>$msg[orinot_pays]</th>
		<th>$msg[orinot_diffusable]</th>
	</tr>";

	// affichage du tableau des statuts
	$requete = "SELECT orinot_id, orinot_nom, orinot_pays, orinot_diffusion FROM origine_notice ORDER BY orinot_nom ";
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
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=notices&sub=orinot&action=modif&id=$row->orinot_id';\" ";
        	
		print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td>".htmlentities($row->orinot_nom,ENT_QUOTES, $charset)."</td><td>".htmlentities($row->orinot_pays,ENT_QUOTES, $charset)."</td>");
		if ($row->orinot_diffusion) print "<td>$msg[orinot_diffusable_oui]</td>";
			else print "<td>$msg[orinot_diffusable_non]</td>";
		print "</tr>";
		}
	print "</table>
		<input class='bouton' type='button' value=' $msg[orinot_ajout] ' onClick=\"document.location='./admin.php?categ=notices&sub=orinot&action=add'\" />";
	}

function orinot_form($nom="", $pays="FR", $diffusion=1, $id=0) {

	global $msg;
	global $admin_orinot_form;
	global $charset;

	$admin_orinot_form = str_replace('!!id!!', $id, $admin_orinot_form);

	if(!$id) $admin_orinot_form = str_replace('!!form_title!!', $msg[orinot_ajout], $admin_orinot_form);
		else $admin_orinot_form = str_replace('!!form_title!!', $msg[orinot_modification], $admin_orinot_form);

	$admin_orinot_form = str_replace('!!nom!!', htmlentities($nom,ENT_QUOTES, $charset), $admin_orinot_form);
	$admin_orinot_form = str_replace('!!nom_suppr!!', addslashes($nom), $admin_orinot_form);
	$admin_orinot_form = str_replace('!!pays!!', htmlentities($pays,ENT_QUOTES, $charset), $admin_orinot_form);

	if($diffusion) $checkbox="checked"; else $checkbox="";
	$admin_orinot_form = str_replace('!!checkbox!!', $checkbox, $admin_orinot_form);
	$admin_orinot_form = str_replace('!!diffusion!!', $diffusion, $admin_orinot_form);


	print confirmation_delete("./admin.php?categ=notices&sub=orinot&action=del&id=");
	print $admin_orinot_form;

	}

switch($action) {
	case 'update':
		if(!empty($form_nom)) {
			if($id) {
				$requete = "UPDATE origine_notice SET orinot_nom='$form_nom',orinot_pays='$form_pays',orinot_diffusion='$form_diffusion' WHERE orinot_id='$id' ";
				$res = mysql_query($requete, $dbh);
				} else {
					$requete = "SELECT count(1) FROM origine_notice WHERE orinot_nom='$form_nom' LIMIT 1 ";
					$res = mysql_query($requete, $dbh);
					$nbr = mysql_result($res, 0, 0);
					if($nbr == 0){
						$requete = "INSERT INTO origine_notice (orinot_nom,orinot_pays,orinot_diffusion) VALUES ('$form_nom','$form_pays','$form_diffusion') ";
						$res = mysql_query($requete, $dbh);
						}
					}
			}
		show_orinot($dbh);
		break;
	case 'add':
		if(empty($form_nom) && empty($form_pays)) orinot_form();
			else show_orinot($dbh);
		break;
	case 'modif':
		if($id){
			$requete = "SELECT orinot_nom, orinot_pays, orinot_diffusion FROM origine_notice WHERE orinot_id='$id' ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				orinot_form($row->orinot_nom, $row->orinot_pays, $row->orinot_diffusion, $id);
				} else {
					show_orinot($dbh);
					}
			} else {
				show_orinot($dbh);
				}
		break;
	case 'del':
		if (($id) && ($id!=1)) {
			$total = 0;
			$total = mysql_num_rows (mysql_query("select origine_catalogage from notices where origine_catalogage ='".$id."' ", $dbh));
			if ($total==0) {
				$requete = "DELETE FROM origine_notice WHERE orinot_id='$id' ";
				$res = mysql_query($requete, $dbh);
				$requete = "OPTIMIZE TABLE origine_notice ";
				$res = mysql_query($requete, $dbh);
				show_orinot($dbh);
				} else {
					error_message(	"", $msg[orinot_used], 1, 'admin.php?categ=notices&sub=orinot&action=');
					}
			} else show_orinot($dbh);
		break;
	default:
		show_orinot($dbh);
		break;
	}
