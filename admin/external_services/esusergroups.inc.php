<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: esusergroups.inc.php,v 1.2 2009-07-15 14:17:11 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Initialisation des classes
require_once($class_path."/external_services.class.php");
require_once($class_path."/external_services_esusers.class.php");
require_once($include_path."/templates/external_services.tpl.php");

function list_groups() {
	global $msg, $charset, $dbh;
	$esgroups = new es_esgroups();
	print "<table>
				<tr>
					<th>".$msg["es_group_name"]."</th>
					<th>".$msg["es_group_fullname"]."</th>
					<th>".$msg["es_group_pmbuserid"]."</th>
					<th>".$msg["es_group_esusers_count"]."</th>
					<th>".$msg["es_group_emprgroup_count"]."</th>
				</tr>";

	//Ajoutons le groupe anonyme
	$pair_impair = "odd";
	$ano_sql = "SELECT CONCAT(users.username, ' (', users.nom, ' ', users.prenom,')') AS pmbusercaption FROM `es_esgroups` LEFT JOIN users ON (users.userid = es_esgroups.esgroup_pmbusernum) WHERE `esgroup_id` = -1";
	$ano_res = mysql_query($ano_sql, $dbh);
	if (!mysql_numrows($ano_res))
		$ano_pmbusercaption = mysql_result(mysql_query("SELECT CONCAT(users.username, ' (', users.nom, ' ', users.prenom,')') FROM users WHERE userid = 1", $dbh), 0, 0);
	else 
		$ano_pmbusercaption = mysql_result($ano_res, 0, 0);
	$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=external_services&sub=esusergroups&action=editanonymous'\" ";
	print "		<tr style='cursor: pointer' class='$pair_impair' $tr_javascript>
					<td>&lt;".$msg["admin_connecteurs_outauth_anonymgroupname"]."&gt;</td>
					<td>".$msg["admin_connecteurs_outauth_anonymgroupfullname"]."</td>
					<td>".htmlentities($ano_pmbusercaption ,ENT_QUOTES, $charset)."</td>
					<td colspan=\"2\"></td>
				</tr>";

	$parity=1;
	foreach($esgroups->groups as &$aesgroup) {
		$pair_impair = $parity++ % 2 ? 'even' : 'odd';
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=external_services&sub=esusergroups&action=edit&id=$aesgroup->esgroup_id';\" ";
		print "<tr style='cursor: pointer' class='$pair_impair' $tr_javascript>";
		print "<td>".htmlentities($aesgroup->esgroup_name ,ENT_QUOTES, $charset)."</td>";
		print "<td>".htmlentities($aesgroup->esgroup_fullname ,ENT_QUOTES, $charset)."</td>";
		print "<td>".htmlentities($aesgroup->esgroup_pmbuser_username.' ('.$aesgroup->esgroup_pmbuser_lastname.' '.$aesgroup->esgroup_pmbuser_firstname.')' ,ENT_QUOTES, $charset)."</td>";
		print "<td>".count($aesgroup->esgroup_esusers)."</td>";
		print "<td>".count($aesgroup->esgroup_emprgroups)."</td>";
		print "</tr>";
	}
	
	if (!count($esgroups->groups)) {
		print '<tr><td colspan="4">'.$msg["es_users_noesgroups"].'</td></tr>';
	}
	
	print "</table>
			<input class='bouton' type='button' value='".htmlentities($msg[es_groups_add] ,ENT_QUOTES, $charset)."' onClick=\"document.location='./admin.php?categ=external_services&sub=esusergroups&action=add'\" />";
}

function show_esgroup_form($id=0, $esg_name='', $esg_fullname="", $esg_pmbuserid='', $esg_esusers=array(), $esg_emprgroups=array()) {
	global $msg, $charset, $dbh;

	print '<form method="POST" action="admin.php?categ=external_services&sub=esusergroups&action=update" name="form_esgroup">';
	if (!$id)
		print '<h3>'.$msg['es_groups_add'].'</h3>';
	else
		print '<h3>'.$msg['es_groups_edit'].'</h3>';
		
	print '<div class="form-contenu">';

	//id
	print '<input type="hidden" name="id" value="'.$id.'">';

	//name
	print '<div class=row><label class="etiquette" for="es_group_name">'.$msg["es_group_name"].'</label><br />';
	print '<input name="es_group_name" type="text" value="'.htmlentities($esg_name,ENT_QUOTES, $charset).'" class="saisie-80em">
			</div>';

	//fullname
	print '<div class=row><label class="etiquette" for="es_group_fullname">'.$msg["es_group_fullname"].'</label><br />';
	print '<input name="es_group_fullname" type="text" value="'.htmlentities($esg_fullname,ENT_QUOTES, $charset).'" class="saisie-80em">
			</div>';

	$pmbusers_sql = "SELECT userid, username, nom, prenom FROM users";
	$pmbusers_res = mysql_query($pmbusers_sql, $dbh);
	$pmbusers = array();
	while($pmbusers_row = mysql_fetch_assoc($pmbusers_res)) {
		$pmbusers[] = $pmbusers_row;
	}
	
	//pmbuser
	print '<div class=row><label class="etiquette" for="es_group_pmbuserid">'.$msg["es_group_pmbuserid"].'</label><br />';
	print '<select name="es_group_pmbuserid">';
	foreach ($pmbusers as $apmbuser) {
		print '<option '.($apmbuser["userid"] == $esg_pmbuserid ? ' selected ' : '').' value="'.$apmbuser["userid"].'">'.htmlentities($apmbuser["username"].' ('.$apmbuser["nom"].' '.$apmbuser['prenom'].')' ,ENT_QUOTES, $charset).'</option>';
	}
	print '</select></div>';

	//es_users
	$es_users = new es_esusers();
	print '<div class=row><label class="etiquette" for="es_group_esusers">'.$msg["es_group_esusers"].'</label><br />';
	print '<select name="es_group_esusers[]" DISABLED MULTIPLE>';
	foreach ($es_users->users as &$aesuser) {
		print '<option '.(in_array($aesuser->esuser_id, $esg_esusers) ? ' selected ' : '').' value="'.$aesuser->esuser_id.'">'.htmlentities($aesuser->esuser_username.' ('.$aesuser->esuser_fullname.')' ,ENT_QUOTES, $charset).'</option>';
	}
	print '</select></div>';
	
	//empr_groups
	$pmbemprgroups = array();
	$pmbemprgroup_sql = "SELECT id_groupe, libelle_groupe FROM groupe";
	$pmbemprgroup_res = mysql_query($pmbemprgroup_sql, $dbh);
	while($row=mysql_fetch_assoc($pmbemprgroup_res))
		$pmbemprgroups[] = $row;

	print '<div class=row><label class="etiquette" for="es_group_emprgroupe">'.$msg["es_group_emprgroupe"].'</label><br />';
	print '<select name="es_group_emprgroups[]" MULTIPLE>';
	print "<option value=''>".htmlentities($msg["es_group_emprgroupe_none"] ,ENT_QUOTES, $charset)."</option>";
	foreach ($pmbemprgroups as $aemprgroups) {
		print '<option '.(in_array($aemprgroups["id_groupe"], $esg_emprgroups) ? ' selected ' : '').' value="'.$aemprgroups["id_groupe"].'">'.htmlentities($aemprgroups["libelle_groupe"] ,ENT_QUOTES, $charset).'</option>';
	}
	print '</select></div>';
		
	//buttons
	print "<br /><div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=external_services&sub=esusergroups'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div>
	<div class='right'>";
	if ($id) {
		print confirmation_delete("./admin.php?categ=external_services&sub=esusergroups&action=del&id=");
		print "<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete('".$id."','".addslashes($esg_name)."')\" />";		
	} 		
	print "</div>
	<br /><br /></div>";
	
	print '</form>';
	
}

function show_esgroup_form_anonymous() {
	global $msg, $charset, $dbh;

	print '<form method="POST" action="admin.php?categ=external_services&sub=esusergroups&action=updateanonymous" name="form_esgroup">';
	print '<h3>'.$msg['es_groups_edit'].'</h3>';
		
	print '<div class="form-contenu">';

	//id
	print '<input type="hidden" name="id" value="'.$id.'">';

	//name
	print '<div class=row><label class="etiquette" for="es_group_name">'.$msg["es_group_name"].'</label><br />';
	print $msg["admin_connecteurs_outauth_anonymgroupname"];
	print '</div>';

	//fullname
	print '<div class=row><label class="etiquette" for="es_group_fullname">'.$msg["es_group_fullname"].'</label><br />';
	print $msg["admin_connecteurs_outauth_anonymgroupfullname"];
	print '</div>';

	$pmbusers_sql = "SELECT userid, username, nom, prenom FROM users";
	$pmbusers_res = mysql_query($pmbusers_sql, $dbh);
	$pmbusers = array();
	while($pmbusers_row = mysql_fetch_assoc($pmbusers_res)) {
		$pmbusers[] = $pmbusers_row;
	}
	
	$sql = "SELECT esgroup_pmbusernum FROM es_esgroups WHERE esgroup_id = -1";
	$res = mysql_query($sql, $dbh);
	if (!mysql_numrows($res))
		 $esg_pmbuserid = 1;
	else
		$esg_pmbuserid = mysql_result($res, 0, 0);
	
	//pmbuser
	print '<div class=row><label class="etiquette" for="es_group_pmbuserid">'.$msg["es_group_pmbuserid"].'</label><br />';
	print '<select name="es_group_pmbuserid">';
	foreach ($pmbusers as $apmbuser) {
		print '<option '.($apmbuser["userid"] == $esg_pmbuserid ? ' selected ' : '').' value="'.$apmbuser["userid"].'">'.htmlentities($apmbuser["username"].' ('.$apmbuser["nom"].' '.$apmbuser['prenom'].')' ,ENT_QUOTES, $charset).'</option>';
	}
	print '</select></div>';

		
	//buttons
	print "<br /><div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=external_services&sub=esusergroups'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div>
	<br /><br /></div>";
	
	print '</form>';
	
}

function update_esgroup_from_form() {
	global $msg, $charset,$dbh,$id;
	global $es_group_name, $es_group_fullname, $es_group_pmbuserid/*, $es_group_esusers*/, $es_group_emprgroups;
	if (!is_array($es_group_esusers))
		$es_group_esusers = array($es_group_esusers);
	if (!is_array($es_group_emprgroups))
		$es_group_emprgroups = array($es_group_emprgroups);
	if (!$id) {
		//Ajout d'un nouveau groupe

		if (!$es_group_name) {
			print $msg['es_group_error_emptyfield'];
			show_esgroup_form(0, stripslashes($es_group_name), stripslashes($es_group_fullname), stripslashes($es_group_pmbuserid), array(), $es_group_emprgroups);
			return false;
		}
		if (es_esgroup::name_exists($es_group_name)) {
			print $msg['es_group_error_namealreadyexists'];
			show_esgroup_form(0, stripslashes($es_group_name), stripslashes($es_group_fullname), stripslashes($es_group_pmbuserid), array(), $es_group_emprgroups);
			return false;
		}
		$new_esgroup = es_esgroup::add_new();
		$new_esgroup->esgroup_name = $es_group_name;
		$new_esgroup->esgroup_fullname = $es_group_fullname;
		$new_esgroup->esgroup_pmbuserid = $es_group_pmbuserid;
//		$new_esgroup->esgroup_esusers = $es_group_esusers;
		$new_esgroup->esgroup_emprgroups = $es_group_emprgroups;
		$new_esgroup->commit_to_db(); 
	}
	else {
		$thegroup = new es_esgroup($id);
			if ($the_group->error) {
				return false;
		}
		$thegroup->esgroup_name = $es_group_name;
		$thegroup->esgroup_fullname = $es_group_fullname;
		$thegroup->esgroup_pmbuserid = $es_group_pmbuserid;
		$thegroup->esgroup_esusers = $es_group_esusers;
		$thegroup->esgroup_emprgroups = $es_group_emprgroups;
		$thegroup->commit_to_db(); 
	}
	return true;
}

switch ($action) {
	case "add":
		show_esgroup_form(0, '', '');
		break;
	case "edit":
		$esg_name='';
		$esg_fullname='';
		$esg_pmbuserid='';
		$esg_esusers=array();
		$esg_emprgroups=array();
		if ($id) {
			$the_group = new es_esgroup($id);
			if ($the_group->error) {
				$id = 0;
			}
			else {
				$esg_name=$the_group->esgroup_name;
				$esg_fullname=$the_group->esgroup_fullname;
				$esg_pmbuserid=$the_group->esgroup_pmbuserid;
				$esg_esusers=$the_group->esgroup_esusers;
				$esg_emprgroups=$the_group->esgroup_emprgroups;
			}
		}
		show_esgroup_form($id, $esg_name, $esg_fullname, $esg_pmbuserid, $esg_esusers, $esg_emprgroups);
		break;
	case "editanonymous":
		show_esgroup_form_anonymous();
		break;
	case "update":
		if (update_esgroup_from_form())
			list_groups();
		break;
	case 'updateanonymous':
		if ($es_group_pmbuserid) {
			$es_group_pmbuserid += 0;
			$sql = "REPLACE INTO es_esgroups SET esgroup_id = -1, esgroup_name = '".$msg["admin_connecteurs_outauth_anonymgroupname"]."', esgroup_fullname = '".$msg["admin_connecteurs_outauth_anonymgroupfullname"]."', esgroup_pmbusernum = ".$es_group_pmbuserid;
			mysql_query($sql, $dbh);
		}
		list_groups();
		break;
	case "del":
		if ($id) {
			$the_groupe = new es_esgroup($id);
			$the_groupe->delete();
		}
		list_groups();
		break;
	default:
		list_groups();
		break;
}

?>