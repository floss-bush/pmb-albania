<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categ.inc.php,v 1.6 2009-05-16 11:12:02 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/connecteurs.class.php");

function show_categories($dbh) {
	global $msg;

	print "<table>
	<tr>
		<th>".$msg[103]."</th>
		<th>".$msg["count_connecteurs_categ"]."</th>
	</tr>";

	$requete = "SELECT connectors_categ_id, connectors_categ_name FROM connectors_categ order by connectors_categ_name";
	$res = mysql_query($requete, $dbh);

	$parity=1;
	while($row=mysql_fetch_object($res)) {
		$count_query = 'SELECT count(*) FROM connectors_categ_sources WHERE num_categ='.$row->connectors_categ_id;
		$conn_count = mysql_result(mysql_query($count_query, $dbh), 0, 0);
		
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=connecteurs&sub=categ&action=modif&id=$row->connectors_categ_id';\" ";
		print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>");
		print pmb_bidi("<td>".$row->connectors_categ_name."</td>");
		print pmb_bidi("<td>$conn_count</td>") ;
        print pmb_bidi("</tr>");		
	}
	print "</table>
		<input class='bouton' type='button' value=' $msg[connecteurs_categ_add] ' onClick=\"document.location='./admin.php?categ=connecteurs&sub=categ&action=add'\" />";

}


function category_form($categ_id=0, $new_categ_name="", $opac_expanded=false) {
	global $msg, $dbh, $charset;
	print '<form method="POST" action="admin.php?categ=connecteurs&sub=categ&action=update" name="form_categ">';
	if (!$categ_id)
		print '<h3>'.$msg['connecteurs_categ_add'].'</h3>';
	else
		print '<h3>'.$msg['connecteurs_categ_edit'].'</h3>';
		
	print '<div class="form-contenu">';
	
	print '<input type="hidden" name="categ_id" value="'.$categ_id.'">';
	print '<div class=row><label class="etiquette" for="categ_name">'.$msg["connecteurs_categ_caption"].'</label><br />';
	print '<input name="categ_name" type="text" value="'.htmlentities($new_categ_name,ENT_QUOTES, $charset).'" class="saisie-80em">
			</div>';

	print '<div class=row><label class="etiquette" for="categ_opac_expanded">'.$msg["connecteurs_categ_opac_expanded"].'</label><br />';
	print '<input name="categ_opac_expanded" type="checkbox" '.($opac_expanded ? "checked" : "").' >
			</div>';

	$sources_sql = 'SELECT connectors_sources.source_id, connectors_sources.name, connectors_categ_sources.num_categ, id_connector
		FROM connectors_sources LEFT JOIN connectors_categ_sources ON (connectors_sources.source_id = connectors_categ_sources.num_source AND connectors_categ_sources.num_categ='.$categ_id.') 
		order by connectors_sources.id_connector, connectors_sources.name';
	$resultat = mysql_query($sources_sql, $dbh);
	while ($row=mysql_fetch_object($resultat)) {
		$sources[] = $row; 
	}
	$nbsources=count($sources);
	$content_input = '<select MULTIPLE name="categ_content[]" size="'.($nbsources+4).'">';
	if (!$nbsources) 
		$content_input .= '<option value="">'.($msg["connecteurs_categories_none"]).'</option>';
	$idconnectorconserve="";
	foreach ($sources as $source) {
		if ($source->id_connector!=$idconnectorconserve) {
			$idconnectorconserve=$source->id_connector;
			$content_input .= '<optgroup label="'.$idconnectorconserve.'" class="erreur">';
		}
		$content_input .= '<option value="'.$source->source_id.'" '.($source->num_categ ? 'SELECTED' : '').' style="color: rgb(0, 0, 0);">'.htmlentities($source->name ,ENT_QUOTES, $charset).'</option>';		
	}
	$content_input .= '</select>';
	print '<div class=row><label class="etiquette" for="categ_content">'.$msg["connecteurs_included_sources"].'</label><br />';
	print $content_input;
	print '</div></div>';
	
	print "<div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=connecteurs&sub=categ'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div>
	<div class='right'>";
	if ($categ_id) {
		print confirmation_delete("./admin.php?categ=connecteurs&sub=categ&action=del&id=");
		print "<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete('".$categ_id."','".addslashes($new_categ_name)."')\" />";		
	} 		
	print "</div>
	<br /><br /></div>";
	
	print '</form>';
}

switch($action) {
	case 'update':
		//Mettons a jours la catégorie
		if ($categ_id == 0) {
			$sql = "INSERT INTO connectors_categ (connectors_categ_name, opac_expanded) VALUES ('".addslashes($categ_name)."', ".($categ_opac_expanded ? "1" : "0").");";
			mysql_query($sql, $dbh);
			$categ_id = mysql_insert_id($dbh);
		}
		else {
			$sql = "UPDATE connectors_categ SET connectors_categ_name = '".addslashes($categ_name)."', opac_expanded = ".($categ_opac_expanded ? "1" : "0")." WHERE connectors_categ_id = ".addslashes($categ_id);
			mysql_query($sql, $dbh);
		}
		
		$sql = "DELETE FROM connectors_categ_sources WHERE num_categ = ".$categ_id;
		mysql_query($sql, $dbh);
		if ($categ_content && !(count($categ_content == 1) && $categ_content[0] == "")) {
			$values = array();
			foreach($categ_content as $asource_id) {
				$values[] = "(".addslashes($categ_id).", ".addslashes($asource_id).")";
			}
			$values = implode(",", $values);
			$sql = "INSERT INTO connectors_categ_sources (num_categ, num_source) VALUES ".$values;
			mysql_query($sql, $dbh) or die (mysql_error());
		}
		
		show_categories($dbh);
		break;
	case 'add':
		category_form();
		break;
	case 'modif':
		if($id){
			$requete = "SELECT * FROM connectors_categ WHERE connectors_categ_id=".$id;
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				$categ_name = $row->connectors_categ_name; 
				$categ_opac_expanded=$row->opac_expanded;
				category_form($id, $categ_name, $categ_opac_expanded);
			}
		}
		break;
	case 'del':
		if ($id) {
			$ida = addslashes($id);
			$sql = "DELETE FROM connectors_categ WHERE connectors_categ_id=".$ida;
			mysql_query($sql, $dbh);
			$sql = "DELETE FROM connectors_categ_sources WHERE num_categ = ".$ida;
			mysql_query($sql, $dbh);
		}
		show_categories($dbh);		
		break;
	default:
		show_categories($dbh);
		break;
	}

?>
