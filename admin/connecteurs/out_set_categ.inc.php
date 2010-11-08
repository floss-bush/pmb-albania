<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: out_set_categ.inc.php,v 1.2 2009-10-06 04:00:09 touraine37 Exp $
if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/connecteurs_out_sets.class.php");

function list_categ() {
	global $msg, $charset;
	$categs = new connector_out_setcategs();
	print "<table>
				<tr>
					<th>".$msg["admin_connecteurs_setcateg_name"]."</th>
					<th>".$msg["admin_connecteurs_setcateg_setcount"]."</th>
				</tr>";
	
	$parity=1;
	foreach($categs->categs as &$acateg) {
		$pair_impair = $parity++ % 2 ? 'even' : 'odd';
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=connecteurs&sub=categout_sets&action=edit&id=$acateg->id';\" ";
		print "<tr style='cursor: pointer' class='$pair_impair' $tr_javascript>";
		print "<td>".htmlentities($acateg->name ,ENT_QUOTES, $charset)."</td>";
		print "<td>".count($acateg->sets)."</td>";
		print "</tr>";
	}
	
	if (!count($categs->categs)) {
		print '<tr><td colspan="4">'.$msg["admin_connecteurs_sets_nosetcateg"].'</td></tr>';
	}
	
	print "</table>
			<input class='bouton' type='button' value='".htmlentities($msg[admin_connecteurs_setcateg_add] ,ENT_QUOTES, $charset)."' onClick=\"document.location='./admin.php?categ=connecteurs&sub=categout_sets&action=add'\" />";
}

function show_categ_form($id=0, $setcateg_name='', $setcateg_sets=array()) {
	global $msg, $charset;
	
	print '<form method="POST" action="admin.php?categ=connecteurs&sub=categout_sets&action=update" name="form_outsetcateg">';
	if (!$id)
		print '<h3>'.$msg['admin_connecteurs_setcateg_add'].'</h3>';
	else
		print '<h3>'.$msg['admin_connecteurs_setcateg_edit'].'</h3>';
		
	print '<div class="form-contenu">';
	
	//id
	print '<input type="hidden" name="id" value="'.$id.'">';
	
	//name
	print '<div class=row><label class="etiquette" for="setcateg_name">'.$msg["admin_connecteurs_setcateg_name"].'</label><br />';
	print '<input name="setcateg_name" type="text" value="'.htmlentities($setcateg_name,ENT_QUOTES, $charset).'" class="saisie-80em">
			</div>';

	//included sets
	$out_sets = new connector_out_sets();
	$included_sets = '<select MULTIPLE name="setcateg_sets[]">';
	$included_sets .= '<option value="">'.$msg["admin_connecteurs_setcateg_none"].'</option>';
	foreach ($out_sets->sets as &$aset) {
		$included_sets .= '<option '.(in_array($aset->id, $setcateg_sets) ? ' selected ' : '').' value="'.$aset->id.'">'.htmlentities($aset->caption ,ENT_QUOTES, $charset).'</option>';
	}
	$included_sets .= '</select>';
	print '<div class=row><label class="etiquette" for="setcateg_sets">'.$msg["admin_connecteurs_setcateg_includedsets"].'</label><br />';
	print $included_sets;
	print '</div>';
	
	//buttons
	print "</div><div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=connecteurs&sub=categout_sets'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div>
	<div class='right'>";
	if ($id) {
		print confirmation_delete("./admin.php?categ=connecteurs&sub=categout_sets&action=del&id=");
		print "<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete('".$id."','".addslashes($setcateg_name)."')\" />";		
	} 		
	print "</div>&nbsp;";
	
	print '</form>';
	
}

function update_setcateg_from_form() {
	global $msg, $charset,$dbh,$id;
	global $setcateg_name, $setcateg_sets;
	if (!$id) {
		//Ajout d'un nouveau set
		if (!$setcateg_name) {
			print $msg['admin_connecteurs_setcateg_emptyfield'];
			show_categ_form(0, stripslashes($setcateg_name));
			return false;
		}
		if (connector_out_setcateg::name_exists($set_caption)) {
			print $msg['admin_connecteurs_setcateg_namealreadyexists'];
			show_categ_form(0, stripslashes($setcateg_name));
			return false;
		}
		$new_setcateg = connector_out_setcateg::add_new();
		$new_setcateg->name = $setcateg_name;
		$new_setcateg->sets = $setcateg_sets;
		$new_setcateg->commit_to_db(); 
	}
	else {
		$thecateg = new connector_out_setcateg($id);
			if ($thecateg->error) {
				return false;
		}
		$thecateg->caption = $setcateg_name;
		$thecateg->sets = $setcateg_sets;
		$thecateg->commit_to_db(); 
	}
	return true;
}

switch ($action) {
	case "add":
		show_categ_form(0, '', array());
		break;
	case "edit":
		$categ_name='';
		$categ_sets=array();
		if ($id) {
			$the_categ = new connector_out_setcateg($id);
			if ($the_categ->error) {
				$id = 0;
			}
			else {
				$categ_name=$the_categ->name;
				$categ_sets=$the_categ->sets;
			}
		}
		show_categ_form($id, $categ_name, $categ_sets);
		break;
	case "update":
		if (update_setcateg_from_form())
			list_categ();
		break;
	case "del":
		if ($id) {
			$the_categ = new connector_out_setcateg($id);
			$the_categ->delete();
		}
		list_categ();
		break;
	default:
		list_categ();
		break;
}

?>