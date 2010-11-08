<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: esusers.inc.php,v 1.2 2009-07-15 14:17:17 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Initialisation des classes
require_once($class_path."/external_services.class.php");
require_once($class_path."/external_services_esusers.class.php");
require_once($include_path."/templates/external_services.tpl.php");

function list_users() {
	global $msg, $charset;
	$esusers = new es_esusers();
	print "<table>
				<tr>
					<th>".$msg["es_user_username"]."</th>
					<th>".$msg["es_user_fullname"]."</th>
				</tr>";
	
	$parity=1;
	foreach($esusers->users as &$aesuser) {
		$pair_impair = $parity++ % 2 ? 'even' : 'odd';
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=external_services&sub=esusers&action=edit&id=$aesuser->esuser_id';\" ";
		print "<tr style='cursor: pointer' class='$pair_impair' $tr_javascript>";
		print "<td>".htmlentities($aesuser->esuser_username ,ENT_QUOTES, $charset)."</td>";
		print "<td>".htmlentities($aesuser->esuser_fullname ,ENT_QUOTES, $charset)."</td>";
		print "</tr>";
	}
	
	if (!count($esusers->users)) {
		print '<tr><td colspan="2">'.$msg["es_users_noesusers"].'</td></tr>';
	}
	
	print "</table>
			<input class='bouton' type='button' value=' $msg[es_users_add] ' onClick=\"document.location='./admin.php?categ=external_services&sub=esusers&action=add'\" />";
}

function show_esuser_form($id=0, $esu_username='', $esu_fullname='', $esu_password='', $esu_groupid=0) {
	global $msg, $charset;
	
	print '<form method="POST" action="admin.php?categ=external_services&sub=esusers&action=update" name="form_esuser">';
	if (!$id)
		print '<h3>'.$msg['es_users_add'].'</h3>';
	else
		print '<h3>'.$msg['es_users_edit'].'</h3>';
		
	print '<div class="form-contenu">';
	
	//id
	print '<input type="hidden" name="id" value="'.$id.'">';
	
	//username
	print '<div class=row><label class="etiquette" for="esuser_username">'.$msg["es_user_username"].'</label><br />';
	print '<input name="esuser_username" type="text" value="'.htmlentities($esu_username,ENT_QUOTES, $charset).'" class="saisie-80em">
			</div>';

	//fullname
	print '<div class=row><label class="etiquette" for="esuser_fullname">'.$msg["es_user_fullname"].'</label><br />';
	print '<input name="esuser_fullname" type="text" value="'.htmlentities($esu_fullname,ENT_QUOTES, $charset).'" class="saisie-80em">
			</div>';

	//password
	print '<div class=row><label class="etiquette" for="esuser_password">'.$msg["es_user_password"].'</label><br />';
	print '<input name="esuser_password" type="text" value="'.htmlentities($esu_password,ENT_QUOTES, $charset).'" class="saisie-80em">
			</div>';
	
	//group
	$esgroups = new es_esgroups();
	$groupselect = '<select name="esuser_esgroup">';
	$groupselect .= '<option value="0">'.$msg["es_user_group_none"].'</option>';
	foreach ($esgroups->groups as &$aesgroup) {
		 $groupselect .= '<option '.($esu_groupid == $aesgroup->esgroup_id ? 'selected' : '').' value="'.$aesgroup->esgroup_id.'">'.htmlentities($aesgroup->esgroup_name.' ('.$aesgroup->esgroup_fullname.')' ,ENT_QUOTES, $charset).'</option>';
	}
	$groupselect .= '</select>';
	
	print '<div class=row><label class="etiquette" for="esuser_esgroup">'.$msg["es_user_group"].'</label><br />';
	print $groupselect;
	print '</div>';
	
	//buttons
	print "<br /><div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=external_services&sub=esusers'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div>
	<div class='right'>";
	if ($id) {
		print confirmation_delete("./admin.php?categ=external_services&sub=esusers&action=del&id=");
		print "<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete('".$id."','".addslashes($esu_username)."')\" />";		
	} 		
	print "</div>
	<br /><br /></div>";
	
	print '</form>';
	
}

function update_esuser_from_form() {
	global $msg, $charset,$dbh,$id;
	global $esuser_username, $esuser_fullname, $esuser_password, $esuser_esgroup;
	if ($esuser_esgroup) {
		//Vérifions que le groupe existe
		if (!es_esgroup::id_exists($esuser_esgroup)) {
			print $msg['es_user_error_unknowngroup'];
			show_esuser_form(0, stripslashes($esuser_username), stripslashes($esuser_fullname), stripslashes($esuser_password), stripslashes($esuser_esgroup));
			return false;
		}
	}
	if (!$id) {
		//Ajout d'un nouvel utilisateur
		if (!$esuser_username) {
			print $msg['es_user_error_emptyfield'];
			show_esuser_form(0, stripslashes($esuser_username), stripslashes($esuser_fullname), stripslashes($esuser_password), stripslashes($esuser_esgroup));
			return false;
		}
		if (es_esuser::username_exists($esuser_username)) {
			print $msg['es_user_error_usernamealreadyexists'];
			show_esuser_form(0, stripslashes($esuser_username), stripslashes($esuser_fullname), stripslashes($esuser_password), stripslashes($esuser_esgroup));
			return false;
		}
		$new_esuser = es_esuser::add_new();
		$new_esuser->esuser_username = $esuser_username;
		$new_esuser->esuser_fullname = $esuser_fullname;
		$new_esuser->esuser_password = $esuser_password;
		$new_esuser->esuser_group = $esuser_esgroup;
		$new_esuser->commit_to_db(); 
	}
	else {
		$theuser = new es_esuser($id);
			if ($the_user->error) {
				return false;
		}
		$theuser->esuser_username = $esuser_username;
		$theuser->esuser_fullname = $esuser_fullname;
		$theuser->esuser_password = $esuser_password;
		$theuser->esuser_group = $esuser_esgroup;
		$theuser->commit_to_db(); 
	}
	return true;
}

switch ($action) {
	case "add":
		show_esuser_form(0, '', '', '');
		break;
	case "edit":
		$esu_username='';
		$esu_fullname='';
		$esu_password='';
		$esu_group=0;
		if ($id) {
			$the_user = new es_esuser($id);
			if ($the_user->error) {
				$id = 0;
			}
			else {
				$esu_username=$the_user->esuser_username;
				$esu_fullname=$the_user->esuser_fullname;
				$esu_password=$the_user->esuser_password;
				$esu_group=$the_user->esuser_group;
			}
		}
		show_esuser_form($id, $esu_username, $esu_fullname, $esu_password, $esu_group);
		break;
	case "update":
		if (update_esuser_from_form())
			list_users();
		break;
	case "del":
		if ($id) {
			$the_user = new es_esuser($id);
			$the_user->delete();
		}
		list_users();
		break;
	default:
		list_users();
		break;
}

?>