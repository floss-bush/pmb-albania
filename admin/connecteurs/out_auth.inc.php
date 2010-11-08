<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: out_auth.inc.php,v 1.2 2009-07-15 14:16:38 erwanmartin Exp $
if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/external_services_esusers.class.php");

function list_esgroups() {
	global $msg, $charset, $dbh;
	$esgroups = new es_esgroups();
	print "<table>
				<tr>
					<th>".$msg["es_group_name"]."</th>
					<th>".$msg["es_group_fullname"]."</th>
					<th>".$msg["connector_out_authorization_authorizedsourcecount"]."</th>
				</tr>";
	
	//Ajoutons l'utilisateur anonyme
	$sql = "SELECT COUNT(1) FROM connectors_out_sources_esgroups WHERE connectors_out_source_esgroup_esgroupnum = -1";
	$anonymous_count = mysql_result(mysql_query($sql, $dbh), 0, 0);
	$pair_impair = "odd";
	$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=connecteurs&sub=out_auth&action=editanonymous'\" ";
	print "		<tr style='cursor: pointer' class='$pair_impair' $tr_javascript>
					<td>&lt;".$msg["admin_connecteurs_outauth_anonymgroupname"]."&gt;</td>
					<td>".$msg["admin_connecteurs_outauth_anonymgroupfullname"]."</td>
					<td>".$anonymous_count."</td>
				</tr>";
	
	$parity=1;
	foreach($esgroups->groups as &$aesgroup) {
		//Récupérons le nombre de sources autorisées dans le groupe
		$sql = "SELECT COUNT(1) FROM connectors_out_sources_esgroups WHERE connectors_out_source_esgroup_esgroupnum = ".$aesgroup->esgroup_id;
		$count = mysql_result(mysql_query($sql, $dbh), 0, 0);

		$pair_impair = $parity++ % 2 ? 'even' : 'odd';
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=connecteurs&sub=out_auth&action=edit&id=$aesgroup->esgroup_id';\" ";
		print "<tr style='cursor: pointer' class='$pair_impair' $tr_javascript>";
		print "<td>".htmlentities($aesgroup->esgroup_name ,ENT_QUOTES, $charset)."</td>";
		print "<td>".htmlentities($aesgroup->esgroup_fullname ,ENT_QUOTES, $charset)."</td>";
		print "<td>".$count."</td>";
		print "</tr>";
	}
	
	print "</table>";
}

function show_auth_edit_form($group_id) {
	global $msg, $charset, $dbh;
	
	$the_group = new es_esgroup($group_id);
	if ($the_group->error) {
		exit();
	}
	
	print '<form method="POST" action="admin.php?categ=connecteurs&sub=out_auth&action=update" name="form_outauth">';
	print '<h3>'.$msg['admin_connecteurs_outauth_edit'].'</h3>';
		
	print '<div class="form-contenu">';
	
	//id
	print '<input type="hidden" name="id" value="'.$group_id.'">';
	
	//Nom du groupe
	print '<div class=row><label class="etiquette" for="set_caption">'.$msg["admin_connecteurs_outauth_groupname"].'</label><br />';
	print $the_group->esgroup_name;
	print '</div><br />';
	
	//Nom complet du groupe
	print '<div class=row><label class="etiquette" for="set_caption">'.$msg["admin_connecteurs_outauth_groupfullname"].'</label><br />';
	print $the_group->esgroup_fullname;
	print '</div><br />';

	$current_sources=array();
	$current_sql = "SELECT connectors_out_source_esgroup_sourcenum FROM connectors_out_sources_esgroups WHERE connectors_out_source_esgroup_esgroupnum = ".$group_id;
	$current_res = mysql_query($current_sql, $dbh);
	while($row = mysql_fetch_assoc($current_res)) {
		$current_sources[] = $row["connectors_out_source_esgroup_sourcenum"];
	}
	
	$data_sql = "SELECT connectors_out_sources_connectornum, connectors_out_source_id, connectors_out_source_name, EXISTS(SELECT 1 FROM connectors_out_sources_esgroups WHERE connectors_out_source_esgroup_sourcenum = connectors_out_source_id AND connectors_out_source_esgroup_esgroupnum = ".$group_id.") AS authorized FROM connectors_out_sources ORDER BY connectors_out_sources_connectornum";
	$data_res = mysql_query($data_sql, $dbh);
	$current_connid = 0;
	print '<div class=row><label class="etiquette">'.$msg["admin_connecteurs_outauth_usesource"].'</label><br />';
	while($asource=mysql_fetch_assoc($data_res)) {
		if ($current_connid != $asource["connectors_out_sources_connectornum"]) {
			if ($current_connid) 
				print '<br />';
			$current_connid = $asource["connectors_out_sources_connectornum"];
		}
		print '<input '.(in_array($asource["connectors_out_source_id"], $current_sources) ? 'checked' : '').' type="checkbox" name="authorized_sources[]" value="'.$asource["connectors_out_source_id"].'">';
		print $asource["connectors_out_source_name"];
		
		print '<br />';
	}
	print '</div>';
	
	//buttons
	print "<br /><div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=connecteurs&sub=out_auth'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div>
	<br /><br /></div>";
	
	print '</form>';
}

function show_auth_edit_form_anonymous() {
	global $msg, $charset, $dbh;
	
	print '<form method="POST" action="admin.php?categ=connecteurs&sub=out_auth&action=updateanonymous" name="form_outauth">';
	print '<h3>'.$msg['admin_connecteurs_outauth_edit'].'</h3>';
		
	print '<div class="form-contenu">';
	
	//Nom du groupe
	print '<div class=row><label class="etiquette" for="set_caption">'.$msg["admin_connecteurs_outauth_groupname"].'</label><br />';
	print '&lt;'.$msg["admin_connecteurs_outauth_anonymgroupname"].'&gt;';
	print '</div><br />';
	
	//Nom complet du groupe
	print '<div class=row><label class="etiquette" for="set_caption">'.$msg["admin_connecteurs_outauth_groupfullname"].'</label><br />';
	print $msg["admin_connecteurs_outauth_anonymgroupfullname"];
	print '</div><br />';

	$current_sources=array();
	$current_sql = "SELECT connectors_out_source_esgroup_sourcenum FROM connectors_out_sources_esgroups WHERE connectors_out_source_esgroup_esgroupnum = -1";
	$current_res = mysql_query($current_sql, $dbh);
	while($row = mysql_fetch_assoc($current_res)) {
		$current_sources[] = $row["connectors_out_source_esgroup_sourcenum"];
	}
	
	$data_sql = "SELECT connectors_out_sources_connectornum, connectors_out_source_id, connectors_out_source_name, EXISTS(SELECT 1 FROM connectors_out_sources_esgroups WHERE connectors_out_source_esgroup_sourcenum = connectors_out_source_id AND connectors_out_source_esgroup_esgroupnum = -1) AS authorized FROM connectors_out_sources ORDER BY connectors_out_sources_connectornum";
	$data_res = mysql_query($data_sql, $dbh);
	$current_connid = 0;
	print '<div class=row><label class="etiquette">'.$msg["admin_connecteurs_outauth_usesource"].'</label><br />';
	while($asource=mysql_fetch_assoc($data_res)) {
		if ($current_connid != $asource["connectors_out_sources_connectornum"]) {
			if ($current_connid) 
				print '<br />';
			$current_connid = $asource["connectors_out_sources_connectornum"];
		}
		print '<input '.(in_array($asource["connectors_out_source_id"], $current_sources) ? 'checked' : '').' type="checkbox" name="authorized_sources[]" value="'.$asource["connectors_out_source_id"].'">';
		print $asource["connectors_out_source_name"];
		
		print '<br />';
	}
	print '</div>';
	
	//buttons
	print "<br /><div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=connecteurs&sub=out_auth'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div>
	<br /><br /></div>";
	
	print '</form>';
}

if (!isset($action))
	$action="";
switch ($action) {
	case "edit":
		if (!isset($id) || !$id) {
			list_esgroups();
			exit();
		}
		show_auth_edit_form($id+0);
		break;
	case "editanonymous":
		show_auth_edit_form_anonymous();
		break;
	case "update":
		if (isset($id) && $id) {
			array_walk($authorized_sources, create_function('&$a', '$a+=0;')); //Virons de la liste ce qui n'est pas entier
			//Croisons ce que l'on nous propose avec ce qui existe vraiment dans la base
			//Vérifions que les sources existents
			$sql = "SELECT connectors_out_source_id FROM connectors_out_sources WHERE connectors_out_source_id IN (".implode(",", $authorized_sources).')';
			$res = mysql_query($sql, $dbh);
			$final_authorized_sources = array();
			while ($row=mysql_fetch_assoc($res))
				$final_authorized_sources[] = $row["connectors_out_source_id"];

			//Vérifions que le groupe existe
			$esgroup = new es_esgroup($id);
			if ($esgroup->error) {
				exit();
			}
			
			//On vire ce qui existe déjà:
			$sql = "DELETE FROM connectors_out_sources_esgroups WHERE connectors_out_source_esgroup_esgroupnum = ".$id;
			mysql_query($sql, $dbh);
			
			//Tout est bon? On insert
			$values = array();
			$insert_sql = "INSERT INTO connectors_out_sources_esgroups (connectors_out_source_esgroup_sourcenum, connectors_out_source_esgroup_esgroupnum) VALUES ";
			foreach ($final_authorized_sources as $an_authorized_source) {
				$values[] = '('.$an_authorized_source.','.$id.')';
			}
			$insert_sql .= implode(",", $values);
			mysql_query($insert_sql, $dbh);
		}
		list_esgroups();
		break;
	case "updateanonymous":
		if (!$authorized_sources)
			$final_authorized_sources=array();
		else {
			array_walk($authorized_sources, create_function('&$a', '$a+=0;')); //Virons de la liste ce qui n'est pas entier
			//Croisons ce que l'on nous propose avec ce qui existe vraiment dans la base
			//Vérifions que les sources existents
			$sql = "SELECT connectors_out_source_id FROM connectors_out_sources WHERE connectors_out_source_id IN (".implode(",", $authorized_sources).')';
			$res = mysql_query($sql, $dbh);
			$final_authorized_sources = array();
			while ($row=mysql_fetch_assoc($res))
				$final_authorized_sources[] = $row["connectors_out_source_id"];
			
		}

		//On vire ce qui existe déjà:
		$sql = "DELETE FROM connectors_out_sources_esgroups WHERE connectors_out_source_esgroup_esgroupnum = -1";
		mysql_query($sql, $dbh);
		
		//Tout est bon? On insert
		$values = array();
		$insert_sql = "INSERT INTO connectors_out_sources_esgroups (connectors_out_source_esgroup_sourcenum, connectors_out_source_esgroup_esgroupnum) VALUES ";
		foreach ($final_authorized_sources as $an_authorized_source) {
			$values[] = '('.$an_authorized_source.', -1)';
		}
		$insert_sql .= implode(",", $values);
		mysql_query($insert_sql, $dbh);

		list_esgroups();
		break;
	default:
		list_esgroups();
		break;
}


?>