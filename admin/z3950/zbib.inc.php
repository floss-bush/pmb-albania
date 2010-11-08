<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: zbib.inc.php,v 1.11 2009-05-04 14:50:05 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des attributs de recherche z3950
?>

<script type="text/javascript">
function test_form(form)
{
	if( (form.form_nom.value.length == 0) || (form.form_base.value.length == 0) || (form.form_url.value.length == 0) || (form.form_format.value.length == 0) || (form.form_search_type.value.length == 0) || (form.form_port.value.length == 0) ) {
		alert("<?php echo $msg['zbib_renseign_valeurs'] ?>");
		return false;
		}
	if(isNaN(form.form_port.value))
	{
		alert("<?php echo $msg['zbib_error_port_no_num'] ?>");
		return false;
	}

	return true;
}
// Now use the javascript confirmation_delete function - Marco Vaninetti
// function confirm_delete(bib_id)
//     {
//         result = confirm("confirmez-vous la suppression de ce serveur ?");
//         if(result)
//             document.location = "./admin.php?categ=z3950&sub=zbib&action=del&id="+bib_id;
//     }

</script>

<?php
function show_zbib($dbh)
{
	global $msg;

	print "<table>
		<tr>
		<th class='titre_data'>$msg[zbib_nom]</th>
		<th class='titre_data'>$msg[zbib_base]</th>
		<th class='titre_data'>$msg[zbib_utilisation]</th>
		<th class='titre_data'>$msg[zbib_nb_attr]</th>
		</tr>";

	// affichage du tableau des z_bib
	$requete = "SELECT bib_id, bib_nom, base, search_type, count(*) as nb_attr FROM z_bib left outer join z_attr on bib_id=attr_bib_id group by bib_id, bib_nom, base, search_type ORDER BY bib_nom, base, search_type ";
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
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=z3950&sub=zbib&action=modif&id=$row->bib_id';\" ";
				print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
					print "<td><strong>".$row->bib_nom."</strong></td>";
					print "<td>".$row->base."</td>";
		print "<td>".$row->search_type."</td>";
		print "<td>".$row->nb_attr."</td>";
		print "</tr>";
		}
	print "</table>
		<input class='bouton' type='button' value='".$msg["ajouter"]."' onClick=\"document.location='./admin.php?categ=z3950&sub=zbib&action=add'\" />
		";
		
	}

function zbib_form($znom="", $zbase="", $zsearch_type="CATALOG", $zurl="", $zport="211", $zformat="UNIMARC", $zuser="", $zpassword="", $zsutrs="",$zid=0, $zfunc='') {
	global $msg;
	global $admin_zbib_form;


	$admin_zbib_form = str_replace('!!id!!', $zid, $admin_zbib_form);

	if(!$zid) $admin_zbib_form = str_replace('!!form_title!!', $msg["zbib_ajouter_serveur"], $admin_zbib_form);
		else $admin_zbib_form = str_replace('!!form_title!!',$msg["zbib_modifier_serveur"], $admin_zbib_form);

	$admin_zbib_form = str_replace('!!nom!!',         $znom,         $admin_zbib_form);
	$admin_zbib_form = str_replace('!!base!!',        $zbase,        $admin_zbib_form);
	$admin_zbib_form = str_replace('!!search_type!!', $zsearch_type, $admin_zbib_form);
	$admin_zbib_form = str_replace('!!url!!',         $zurl,         $admin_zbib_form);
	$admin_zbib_form = str_replace('!!port!!',        $zport,        $admin_zbib_form);
	$admin_zbib_form = str_replace('!!format!!',      $zformat,      $admin_zbib_form);
	$admin_zbib_form = str_replace('!!user!!',        $zuser,        $admin_zbib_form);
	$admin_zbib_form = str_replace('!!password!!',    $zpassword,    $admin_zbib_form);
	$admin_zbib_form = str_replace('!!sutrs!!',  	  $zsutrs,       $admin_zbib_form);
	$admin_zbib_form = str_replace('!!zfunc!!',  	  $zfunc,        $admin_zbib_form);
	$admin_zbib_form = str_replace('!!nom_script!!',  	  addslashes($znom),        $admin_zbib_form);
	
	// added by Marco Vaninetti
	print confirmation_delete("./admin.php?categ=z3950&sub=zbib&action=del&id=");
	// end
	print $admin_zbib_form;
	}

switch($action) {
	case 'update':
	// no duplication
	$requete = " SELECT count(1) FROM z_bib WHERE (bib_nom='$form_nom' AND bib_id!='$id' )  LIMIT 1 ";
	$res = mysql_query($requete, $dbh);
	$nbr = mysql_result($res, 0, 0);
	if ($nbr > 0) {
			error_form_message($form_nom.$msg["docs_label_already_used"]);
	} else {
		// O.k., now if the id already exist UPDATE else INSERT
		if(!empty($form_nom) && !empty($form_base) && !empty($form_search_type) && !empty($form_url) && !empty($form_port) && !empty($form_format)) {
			if($id) {
				$requete = "UPDATE z_bib SET bib_nom='$form_nom', base='$form_base', 
					search_type='$form_search_type', url='$form_url', port='$form_port', 
					format='$form_format', auth_user='$form_user', 
					auth_pass='$form_password', sutrs_lang='$form_sutrs', fichier_func='$form_zfunc' WHERE bib_id=$id ";
				$res = mysql_query($requete, $dbh);
			} else {
				$requete = "INSERT INTO z_bib (bib_nom, search_type, url, port, base, format, auth_user, auth_pass, sutrs_lang, fichier_func) VALUES ('$form_nom', '$form_search_type', '$form_url', '$form_port', '$form_base', '$form_format', '$form_user', '$form_password', '$form_sutrs', '$form_zfunc') ";
				$res = mysql_query($requete, $dbh);
				$id_insert=mysql_insert_id();
				$requete = "INSERT INTO z_attr (attr_bib_id,  attr_libelle, attr_attr) VALUES ('$id_insert', 'sujet', '21') ";
				$res = mysql_query($requete, $dbh);
				$requete = "INSERT INTO z_attr (attr_bib_id,  attr_libelle, attr_attr) VALUES ('$id_insert', 'auteur', '1003') ";
				$res = mysql_query($requete, $dbh);
				$requete = "INSERT INTO z_attr (attr_bib_id,  attr_libelle, attr_attr) VALUES ('$id_insert', 'isbn', '7') ";
				$res = mysql_query($requete, $dbh);
				$requete = "INSERT INTO z_attr (attr_bib_id,  attr_libelle, attr_attr) VALUES ('$id_insert', 'titre', '4') ";
				$res = mysql_query($requete, $dbh);
				}
			}
		}
		
		show_zbib($dbh);
		break;
	case 'add':
		if(empty($form_nom) || empty($form_base) || empty($form_search_type) || empty($form_url) || empty($form_port) || empty($form_format)) {
			zbib_form($form_nom, $form_base, $form_search_type, $form_url, $form_port, $form_format, $form_user, $form_password, $form_sutrs, $form_zfunc);
		} else {
			show_bib($dbh);
		}
		break;
	case 'modif':
		if($id){
			$requete = "SELECT bib_id, bib_nom, base, search_type, url, port, format, auth_user, auth_pass, sutrs_lang, fichier_func FROM z_bib WHERE bib_id=$id ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				zbib_form($row->bib_nom, $row->base, $row->search_type, $row->url, $row->port, $row->format, $row->auth_user, $row->auth_pass, $row->sutrs_lang, $id, $row->fichier_func);
			} else {
				show_zbib($dbh);
			}
		} else {
			show_zbib($dbh);
		}
		break;
	case 'del':
		if($id) {
			$requete = "DELETE FROM z_bib WHERE bib_id=$id ";
			$res = mysql_query($requete, $dbh);
			$requete = "DELETE FROM z_attr WHERE attr_bib_id=$id ";
			$res = mysql_query($requete, $dbh);
			show_zbib($dbh);
			} else show_zbib($dbh);
		break;
	default:
		show_zbib($dbh);
		break;
}
print "</td></tr></table>";

