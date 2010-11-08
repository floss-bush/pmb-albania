<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: zattr.inc.php,v 1.11 2009-05-16 11:12:04 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des attributs de recherche z3950

?>
<script type="text/javascript">
function test_form(form)
{
	if( (form.form_attr_bib_id.value.length == 0) || (form.form_attr_libelle.value.length == 0) || (form.form_attr_attr.value.length == 0) ) {
		alert("<?php echo $msg['zattr_renseign_lib_et_attr'] ?>");
		return false;
		}
	return true;
}
</script>

<?php
function show_zattr($dbh, $bib_id) {
	global $msg;
	global $include_path ;
	global $lang;
	global $charset;
	
	print "<table>
	<tr>
		<td>$msg[zattr_libelle]</td>
		<td>$msg[zattr_attr]</td>
	</tr>
	";

	// affichage du tableau des z_attr

	$requete = "SELECT attr_bib_id,  attr_libelle,  attr_attr  FROM z_attr where attr_bib_id ='$bib_id' ORDER BY attr_libelle,  attr_attr ";
	$res = mysql_query($requete, $dbh);

	$nbr = mysql_num_rows($res);
	
	// loading the localized attributes labels
	$la = new XMLlist($include_path."/marc_tables/z3950attributes.xml", 0);
	$la->analyser();
	$codici = $la->table;

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_object($res);
		if ($parity % 2) {
			$pair_impair = "even";
			} else {
				$pair_impair = "odd";
				}
		$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=z3950&sub=zattr&action=modif&bib_id=$row->attr_bib_id&attr_libelle=$row->attr_libelle';\" ";
				print "";
			print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
					print "<td><strong>".$msg["z3950_".$codici[$row->attr_libelle]]."</strong></td>";
					print "<td>".$row->attr_attr."</td>";
					print "</tr>";
	}
	print "</table>
		<input class='bouton' type='button' value='$msg[76]' onClick=\"document.location='./admin.php?categ=z3950&sub=zbib&action=modif&id=$bib_id'\" />
		<input class='bouton' type='button' value='".$msg["ajouter"]."' onClick=\"document.location='./admin.php?categ=z3950&sub=zattr&action=add&bib_id=$bib_id'\" />&nbsp;
		";
}

function zattr_form($zbib_id="", $zattr_libelle="", $zattr_attr="") {
	global $msg;
	global $admin_zattr_form;
	global $include_path ;
	global $lang;
	global $charset;
	
	// loading the localized attributes labels
	$la = new XMLlist($include_path."/marc_tables/z3950attributes.xml", 0);
	$la->analyser();
	$codici = $la->table;
	
	if (!$zattr_libelle) {
		$admin_zattr_form = str_replace('!!form_title!!', $msg["zattr_ajouter_attr"], $admin_zattr_form);
		$admin_zattr_form = str_replace('!!bib_id!!', "", $admin_zattr_form);
		// here the combo box must be enabled because the user is adding a new attr.
		$select = "<div class='row'>
				<div class='colonne4' align='right'>
					<label class='etiquette'>$msg[zattr_libelle] &nbsp;</label>
				</div>
				<div class='colonne_suite'> ";
		
		$select .= "<select name='form_attr_libelle'>	";
		while(list($codeattr, $libelle) = each($codici)) {
			if($zattr_libelle == $codeattr) $select .= "<option value='".htmlentities($codeattr,ENT_QUOTES, $charset)."' SELECTED>".htmlentities($msg["z3950_".$libelle],ENT_QUOTES, $charset)."</option>";
				else $select .= "<option value='".htmlentities($codeattr,ENT_QUOTES, $charset)."'>".htmlentities($msg["z3950_".$libelle],ENT_QUOTES, $charset)."</option>";
			}
		$select .= "</select></div></div>";
		
		} else {
			$admin_zattr_form = str_replace('!!form_title!!', $msg["zattr_modifier_attr"]." : ".$msg["z3950_".$codici[$zattr_libelle]], $admin_zattr_form);
			$admin_zattr_form = str_replace('!!bib_id!!', $zbib_id, $admin_zattr_form);
			// here the combo box doesn't appear because the user can't change the attr. label
			
			$select = "<input type=hidden name=form_attr_libelle value='$zattr_libelle'>";
			}
	
	
	
	$admin_zattr_form = str_replace('!!code!!', $select, $admin_zattr_form);
	
	$admin_zattr_form = str_replace('!!attr_bib_id!!',			$zbib_id,        $admin_zattr_form);
	$admin_zattr_form = str_replace('!!attr_libelle!!',			$zattr_libelle,  $admin_zattr_form);
	$admin_zattr_form = str_replace('!!attr_attr!!',			$zattr_attr,     $admin_zattr_form);
	$admin_zattr_form = str_replace('!!local_attr_libelle!!',	$msg["z3950_".$codici[$zattr_libelle]],  $admin_zattr_form);
	
	print confirmation_delete("./admin.php?categ=z3950&sub=zattr&action=del&");
	
	print $admin_zattr_form;
	}

$requete = "SELECT bib_nom, base, search_type FROM z_bib where bib_id ='$bib_id' or bib_id='$form_attr_bib_id' ";
$res = mysql_query($requete, $dbh);
$row=mysql_fetch_object($res);
echo "<hr /><strong>$row->bib_nom - $row->base - $row->search_type</strong><hr />";
switch($action) {
	case 'update':
		if(!empty($form_attr_bib_id) && !empty($form_attr_libelle) && !empty($form_attr_attr)) {
			if($bib_id) {
				$requete = "UPDATE z_attr SET attr_libelle='$form_attr_libelle', attr_attr='$form_attr_attr' WHERE attr_bib_id='$bib_id' and attr_libelle='$form_attr_libelle' ";
				$res = mysql_query($requete, $dbh);
				} else {
					$requete = "INSERT INTO z_attr (attr_bib_id,  attr_libelle, attr_attr) VALUES ('$form_attr_bib_id', '$form_attr_libelle', '$form_attr_attr') ";
					$res = mysql_query($requete, $dbh);
					$bib_id = $form_attr_bib_id ;
					}
			}
		show_zattr($dbh,$bib_id);
		break;
	case 'add':
		if(empty($form_attr_bib_id) || empty($form_attr_libelle) || empty($form_attr_attr)) {
			zattr_form($bib_id, $form_attr_libelle, $form_attr_attr);
			} else {
				show_zattr($dbh, $bib_id);
				}
		break;
	case 'modif':
		if($bib_id){
			$requete = "SELECT attr_bib_id, attr_libelle, attr_attr FROM z_attr WHERE attr_bib_id=$bib_id and attr_libelle='$attr_libelle' ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				zattr_form ($row->attr_bib_id, $row->attr_libelle, $row->attr_attr );
				} else {
					show_zattr($dbh,$bib_id);
					}
			} else {
				show_zattr($dbh,$bib_id);
				}
		break;
	case 'del':
		if (($bib_id) && ($attr_libelle)) {
			$requete = "DELETE FROM z_attr WHERE attr_bib_id='$bib_id' and attr_libelle='$attr_libelle' ";
			$res = mysql_query($requete, $dbh);
			show_zattr($dbh,$bib_id);
			} else show_zattr($dbh,$bib_id);
		break;
	default:
		show_zattr($dbh,$bib_id);
		break;
	}
