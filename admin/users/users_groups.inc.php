<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: users_groups.inc.php,v 1.4 2009-05-16 11:12:02 dbellamy Exp $

// gestion des groupes d'utilisateurs

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$admin_layout = str_replace('!!menu_sous_rub!!', htmlentities($msg['admin_usr_grp_ges'], ENT_QUOTES, $charset), $admin_layout);
print $admin_layout;

print "
<script type=\"text/javascript\">
function test_form(form) {
	if(form.form_libelle.value.length == 0) {
		alert(\"".$msg[559]."\");
		return false;
	}
	return true;
}
</script>
";


function show_group_list() {
	
	global $dbh, $msg, $charset;

	print "<table>
	<tr>
		<th>".htmlentities($msg['admin_usr_grp_lib'], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg['admin_usr_grp_usr'], ENT_QUOTES, $charset)."</th>
	</tr>";

	// affichage du tableau des groupes

	$q = "SELECT grp_id, grp_name FROM users_groups ORDER BY grp_name ";
	$r = mysql_query($q, $dbh);

	$nb = mysql_num_rows($r);
	$parity=1;
	for($i=0;$i<$nb;$i++) {
		$row=mysql_fetch_object($r);
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=users&sub=groups&action=modif&id=$row->grp_id';\" ";
        	print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
        	print '<td>'.htmlentities($row->grp_name, ENT_QUOTES, $charset)."</td>";
        	print '<td>';
        	$q1="select userid, username, prenom, nom from users where grp_num='".$row->grp_id."' order by username ";
        	$r1 = mysql_query($q1, $dbh);
        	while (($row1 = mysql_fetch_object($r1))) {
        		print "<a href= \"./admin.php?categ=users&sub=users&action=modif&id=".$row1->userid."\" >";
        		$lib = $row1->username.' (';
        		if (trim($row1->prenom)!=='') $lib.= $row1->prenom.' ';
        		$lib.= $row1->nom.')';
        		print htmlentities($lib, ENT_QUOTES, $charset);
        		print '</a><br />';
        	}
        	print '</td></tr>';
	}
	
	//non affectes
	$q2 ="select userid, username, prenom, nom from users where grp_num='0' order by username ";
	$r2 = mysql_query($q2, $dbh);
	if (mysql_num_rows($r2)) {
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		print "<tr class='$pair_impair'><td>".htmlentities($msg['admin_usr_grp_non_aff'], ENT_QUOTES, $charset)."</td><td>";
        	while (($row2 = mysql_fetch_object($r2))) {
        		print "<a href= \"./admin.php?categ=users&sub=users&action=modif&id=".$row2->userid."\" >";
        		$lib = $row2->username.' (';
        		if (trim($row2->prenom)!=='') $lib.= $row2->prenom.' ';
        		$lib.= $row2->nom.')';
        		print htmlentities($lib, ENT_QUOTES, $charset);
        		print '</a><br />';
        	}
        	print '</td></tr>';
	}
	
	print "</table>";
	print "<input type='button' class='bouton' value='".$msg['admin_usr_grp_add']."' onClick=\"document.location='./admin.php?categ=users&sub=groups&action=add'\" />";
}


function group_form($libelle='', $id=0) {
	
	global $msg, $charset;
	global $admin_group_form;
	
	$admin_group_form = str_replace('!!id!!', $id, $admin_group_form);

	if(!$id) {
		$admin_group_form = str_replace('!!form_title!!', $msg['admin_usr_grp_add'], $admin_group_form);
	} else {
		$admin_group_form = str_replace('!!form_title!!', $msg['admin_usr_grp_mod'], $admin_group_form);
	}

	$admin_group_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_group_form);
	$admin_group_form = str_replace('!!libelle_suppr!!', addslashes($libelle), $admin_group_form);
	
	print confirmation_delete("./admin.php?categ=users&sub=groups&action=del&id=");
	print $admin_group_form;
}


switch($action) {
	case 'update':
		// verification validite des donnees fournies
		$q = "SELECT count(1) FROM users_groups WHERE grp_name='$form_libelle' AND grp_id!='$id'  LIMIT 1 ";
		$r = mysql_query($q, $dbh);
		$nb = mysql_result($r, 0, 0);
		if ($nb > 0) {
			error_form_message($form_libelle.$msg['admin_usr_grp_lib_used']);
		} else {
			//if item already exists UPDATE else INSERT
			if($id != 0) {
				$q = "UPDATE users_groups SET grp_name='$form_libelle' WHERE grp_id='$id' ";
				mysql_query($q, $dbh);
			} else {
				$q = "INSERT INTO users_groups (grp_id, grp_name) VALUES (0, '$form_libelle') ";
				mysql_query($q, $dbh);
			}
		}
		show_group_list();
		break;
	case 'add':
		if(empty($form_libelle)) {
			group_form($libelle="", $id=0);
		} else {
			show_group_list();
		}
		break;
	case 'modif':
		if($id){
			$q = "SELECT grp_name FROM users_groups WHERE grp_id='$id' ";
			$r = mysql_query($q, $dbh);
			if(mysql_num_rows($r)) {
				$row=mysql_fetch_object($r);
				group_form($row->grp_name, $id);
			} else {
				show_group_list();
			}
		} else {
			show_group_list();
		}
		break;
	case 'del':
		if($id) {
			$total = 0;
			$total = mysql_result(mysql_query("select count(1) from users where grp_num='".$id."' ", $dbh),0 ,0 );				
			if ($total==0) {
				$q = "DELETE FROM users_groups WHERE grp_id='$id' ";
				mysql_query($q, $dbh);
				show_group_list();
			} else {
				error_message(	$msg['admin_usr_grp_ges'], htmlentities($msg['admin_usr_grp_del_imp'], ENT_QUOTES, $charset), 1);
			}
		} else {
			show_group_list();
		}
		break;
	default:
		show_group_list();
		break;
	}
?>