<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: out_sets.inc.php,v 1.2 2009-10-06 04:00:09 touraine37 Exp $
if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/connecteurs_out_sets.class.php");
require_once ($class_path."/search.class.php");

function list_out_sets() {
	global $msg, $charset;
	global $connector_out_set_types_msgs;
	
	$sets = new connector_out_sets();
	$current_type=0;
	$tableheader = "<table>
				<tr>
					<th>".$msg["admin_connecteurs_sets_setcaption"]."</th>
					<th>".$msg["admin_connecteurs_sets_settype"]."</th>
					<th>".$msg["admin_connecteurs_sets_setadditionalinfo"]."</th>
					<th>".$msg["admin_connecteurs_setcateg_latestcacheupdate"]."</th>
					<th>".$msg["admin_connecteurs_setcateg_manualupdate"]."</th>
				</tr>";
	$tablefooter = '</table>';
	
	$parity=1;
	foreach($sets->sets as &$aset) {
		if ($current_type!=$aset->type) {
			print $current_type != 0 ? $tablefooter : '';
			print "<h1>".htmlentities($msg[$connector_out_set_types_msgs[$aset->type]] ,ENT_QUOTES, $charset)."</h1>";
			print $tableheader;
			$current_type = $aset->type;
		}
		$pair_impair = $parity++ % 2 ? 'even' : 'odd';
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=connecteurs&sub=out_sets&action=edit&id=$aset->id';\" ";
		print "<tr style='cursor: pointer' class='$pair_impair' $tr_javascript>";
		print "<td>".htmlentities($aset->caption ,ENT_QUOTES, $charset)."</td>";
		print "<td>".htmlentities($msg[$connector_out_set_types_msgs[$aset->type]] ,ENT_QUOTES, $charset)."</td>";
		print "<td>".htmlentities($aset->get_third_column_info() ,ENT_QUOTES, $charset)."</td>";
		$date_caption = strtotime($aset->cache->last_updated_date) ? formatdate($aset->cache->last_updated_date, 1) : $msg["admin_connecteurs_setcateg_latestcacheupdate_never"];
		print "<td>".htmlentities($date_caption ,ENT_QUOTES, $charset)."</td>";
		print "<td align=\"center\">"."<input type='button' class='bouton_small' value='".htmlentities($msg["admin_connecteurs_setcateg_updatemanually"] ,ENT_QUOTES, $charset)."' onClick='document.location=\"admin.php?categ=connecteurs&sub=out_sets&action=manual_update&id=$aset->id\"'/>"."</td>";
		print "</tr>";
	}
	
	if (!count($sets->sets)) {
		print '<tr><td colspan="2">'.$msg["admin_connecteurs_sets_nosets"].'</td></tr>';
	}
	
	print $tablefooter;
	
	print "<br /><hr /><input class='bouton' type='button' value=' $msg[admin_connecteurs_set_add] ' onClick=\"document.location='./admin.php?categ=connecteurs&sub=out_sets&action=add'\" />";
}

function show_set_form($id=0, $set_type=0, $set_caption='', $config_form=NULL, $cache_config_form=NULL) {
	global $msg, $charset;
	global $connector_out_set_types, $connector_out_set_types_msgs;
	
	print '<form method="POST" action="admin.php?categ=connecteurs&sub=out_sets&action=update" name="form_outset">';
	if (!$id)
		print '<h3>'.$msg['admin_connecteurs_set_add'].'</h3>';
	else
		print '<h3>'.$msg['admin_connecteurs_set_edit'].'</h3>';
		
	print '<div class="form-contenu">';
	
	//id
	print '<input type="hidden" name="id" value="'.$id.'">';
	
	//caption
	print '<div class=row><label class="etiquette" for="set_caption">'.$msg["admin_connecteurs_sets_setcaption"].'</label><br />';
	print '<input name="set_caption" type="text" value="'.htmlentities($set_caption,ENT_QUOTES, $charset).'" class="saisie-80em">
			</div>';

	//type
	if (!$id) {
		$type_input = '<select name="set_type">';
		foreach ($connector_out_set_types as $aconnector_out_set_type) {
			$type_input .= '<option '.($aconnector_out_set_type==$set_type ? ' selected ' : "").' value="'.$aconnector_out_set_type.'">'.htmlentities($msg[$connector_out_set_types_msgs[$aconnector_out_set_type]] ,ENT_QUOTES, $charset).'</option>';
		}
		$type_input .= '</select>';
	}
	else {
		$type_input = htmlentities($msg[$connector_out_set_types_msgs[$set_type]] ,ENT_QUOTES, $charset);
		$type_input .= '<input type="hidden" name="set_type" value="'.$set_type.'">';
	}
	print '<div class=row><label class="etiquette" for="set_type">'.$msg["admin_connecteurs_sets_settype"].'</label><br />';
	print $type_input;
	print '</div>';
	
	if ($config_form) {
		print '<div class=row>';
		print call_user_func_array($config_form, array(&$out_of_form_result));
		print '</div>';
	}

	if ($cache_config_form) {
		print '<div class=row>';
		print call_user_func($cache_config_form);
		print '</div>';
	}
	
	//buttons
	print "
	</div>
	<div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=connecteurs&sub=out_sets'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div>
		<div class='right'>";
	if ($id) {
		print confirmation_delete("./admin.php?categ=connecteurs&sub=out_sets&action=del&id=");
		print "<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete('".$id."','".addslashes($set_caption)."')\" />";		
	} 		
	print "</div>
	&nbsp;</div>";
	
	print '</form>';
	
	if ($out_of_form_result)
		print $out_of_form_result;
	
}

function update_set_from_form() {
	global $msg, $charset,$dbh,$id;
	global $set_type, $set_caption;
	if (!$id) {
		//Ajout d'un nouveau set
		if (!$set_caption) {
			print $msg['admin_connecteurs_set_emptyfield'];
			show_set_form(0, stripslashes($set_type), stripslashes($set_caption));
			return false;
		}
		if (connector_out_set::caption_exists($set_caption)) {
			print $msg['admin_connecteurs_set_namealreadyexists'];
			show_set_form(0, stripslashes($set_type), stripslashes($set_caption));
			return false;
		}
		$new_set = connector_out_set::add_new();
		$new_set->type = $set_type;
		$new_set->caption = stripslashes($set_caption);
		$new_set->commit_to_db(); 
	}
	else {
		$theset = new_connector_out_set_typed($id);
			if ($theset->error) {
				return false;
		}
		$theset->type = $set_type;
		$theset->caption = stripslashes($set_caption);
		$theset->update_config_from_form();
		$theset->cache->update_from_form();
		$theset->commit_to_db();
		$theset->cache->commit_to_db();
	}
	return true;
}

function show_import_noticesearch_into_multicritere_set_form() {
	global $toset_search, $msg, $candidate_id;
	$candidate_id += 0;

	$serialized_search = stripslashes($toset_search);

	//Un petit tour dans la classe search histoire de filtrer la recherche
	$sc = new search(false);
	$sc->unserialize_search($serialized_search);
	$serialized_search = $sc->serialize_search();
	
	$human_query = $sc->make_human_query();

	print '<form method="POST" action="admin.php?categ=connecteurs&sub=out_sets&action=import_notice_search_into_set_do" name="form_outset">';
	print '<h3>'.$msg['search_notice_to_connector_out_set_formtitle'].'</h3>';
		
	print '<div class="form-contenu">';
	
	//la recherche
	print '<input type="hidden" name="toset_search" value="'.htmlentities($serialized_search ,ENT_QUOTES, $charset).'">';
	
	//caption
	print '<div class=row><label class="etiquette" for="set_caption">'.$msg["search_notice_to_connector_out_set_search"].':</label><br />';
	print $human_query;
	print '</div><br />';

	//set d'acceuil
	$set_list = '<select name="set_id">';
	$sets = new connector_out_sets();
	foreach ($sets->sets as &$aset) {
		if ($aset->type != 2)
			continue;
		$set_list .= '<option '.($candidate_id == $aset->id ? 'selected' : '').' value="'.$aset->id.'">'.htmlentities($aset->caption ,ENT_QUOTES, $charset).'</option>';
	}
	$set_list .= '</select>';
	
	//caption
	print '<div class=row><label class="etiquette" for="set_caption">'.$msg["search_notice_to_connector_out_set_set"].':</label><br />';
	print $set_list;
	print '</div><br />';
	
	//buttons
	print "</div><div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"history.go(-1)\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div>
	<div class='right'>";
	print "</div>
	<br /><br /></div>";
	
	print '</form>';
}

function import_noticesearch_into_multicritere_set() {
	global $dbh, $toset_search, $set_id;
	$set_id+=0;
	
	//Pas de set spécifié?
	if (!$set_id)
		return;
		
	//Vérifions que le set spécifié est bien un bon set multicritère
	$the_set = new connector_out_set($set_id, true);
	if ($the_set->type != 2)
		return;
	
	$serialized_search = stripslashes($toset_search);

	//Un petit tour dans la classe search histoire de filtrer la recherche
	$sc = new search(false);
	$sc->unserialize_search($serialized_search);
	$serialized_search = $sc->serialize_search();
	
	//Mettons à jour le set
	$the_set_m = new connector_out_set_noticemulticritere($set_id, true);
	$the_set_m->config["search"] = $serialized_search;
	$the_set_m->commit_to_db();
	$the_set_m->clear_cache(true);
}

if (!isset($action))
	$action="";
switch ($action) {
	case "add":
		show_set_form(0, '', '');
		break;
	case "edit":
		$set_type=0;
		$set_caption='';
		$set_config_form=NULL;
		$setcache_config_form=NULL;
		if ($id) {
			$the_set = new_connector_out_set_typed($id);
			if ($the_set->error) {
				$id = 0;
			}
			else {
				$set_type=$the_set->type;
				$set_caption=$the_set->caption;
				$set_config_form=array($the_set, 'get_config_form');
				$setcache_config_form=array($the_set->cache, 'get_config_form');				
			}
		}
		show_set_form($id, $set_type, $set_caption, $set_config_form, $setcache_config_form);
		break;
	case "update":
		if (update_set_from_form())
			list_out_sets();
		break;
	case "manual_update":
		$theset = new_connector_out_set_typed($id);
			if ($theset->error) {
				return false;
		}
		$theset->update_cache();
		list_out_sets();
		break;
	case "del":
		if ($id) {
			$the_set = new connector_out_set($id);
			$the_set->delete();
		}
		list_out_sets();
		break;
	case "import_notice_search_into_set":
		show_import_noticesearch_into_multicritere_set_form();
		break;
	case "import_notice_search_into_set_do":
		import_noticesearch_into_multicritere_set();
		list_out_sets();
		break;
	default:
		list_out_sets();
		break;
}

?>