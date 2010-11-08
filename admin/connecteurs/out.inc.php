<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: out.inc.php,v 1.3 2009-10-06 04:00:09 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/connecteurs_out.class.php");

function list_connectors_out() {
	global $msg, $charset;
	$conns = new connecteurs_out();
	
	print "
	<script>
		function show_sources(id) {
			if (document.getElementById(id).style.display=='none') {
				document.getElementById(id).style.display='';
				
			} else {
				document.getElementById(id).style.display='none';
			}
		} 
	</script>
	<table>
		<tr>
			<th>&nbsp;</th>
			<th>".$msg["connector_out_service"]."</th>
			<th>".$msg["connector_out_sources"]."</th>
			<th>&nbsp;</th>
		</tr>";
	
	$pair_impair=0;
	$parity=0;
	foreach($conns->connectors as $aconn) {
		$pair_impair = $parity++ % 2 ? "even" : "odd";
		$comment=$aconn->comment;
		$sign=$aconn->name." : ".$comment." - ";
		$sign.="Auteur : ".$aconn->author." - ".$aconn->org." - ";
		$sign.=formatdate($aconn->date);
		$n_sources=count($aconn->sources);
	    $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"if (event) e=event; else e=window.event; if (e.srcElement) target=e.srcElement; else target=e.target; if (target.nodeName!='IMG') document.location='./admin.php?categ=connecteurs&sub=out&action=edit&id=".$aconn->id."';\" ";
	    print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer' title='".htmlentities($sign,ENT_QUOTES,$charset)."' alter='".htmlentities($sign,ENT_QUOTES,$charset)."' id='tr".$aconn->id."'><td>".($n_sources?"<img src='images/plus.gif' class='img_plus' onClick='if (event) e=event; else e=window.event; e.cancelBubble=true; if (e.stopPropagation) e.stopPropagation(); show_sources(\"".addslashes($aconn->path)."\"); '/>":"&nbsp;")."</td><td>".htmlentities($aconn->comment,ENT_QUOTES,$charset)."</td>
		<td>".sprintf($msg["connecteurs_count_sources"],$n_sources)."</td><td style='text-align:right'><input type='button' value='".$msg["connector_out_sourceadd"]."' class='bouton_small' onClick='document.location=\"admin.php?categ=connecteurs&sub=out&action=source_add&connector_id=".$aconn->id."\"'/></td></tr>\n";
	    
	    print "<tr class='$pair_impair' style='display:none' id='".$aconn->path."'><td>&nbsp;</td><td colspan='3'><table style='border:1px solid'>";
	    $parity_source=0;
	    foreach ($aconn->sources as $asource) {
	    	$pair_impair_source = $parity_source++ % 2 ? "even" : "odd";
			$tr_javascript_source=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair_source'\" onmousedown=\"if (event) e=event; else e=window.event; if (e.srcElement) target=e.srcElement; else target=e.target; if (target.nodeName!='INPUT') document.location='./admin.php?categ=connecteurs&sub=out&action=source_edit&connector_id=".$aconn->id."&source_id=".$asource->id."';\" ";
			print "<tr style='cursor: pointer' class='$pair_impair_source' $tr_javascript_source>
				<td>".htmlentities($asource->name,ENT_QUOTES,$charset)."</td>
				<td>".htmlentities(substr($asource->comment,0,60),ENT_QUOTES,$charset)."</td>
				<td></td><td></td></tr>";
	    }
	    print "</table></td></tr>";
	    
	}
	
	print "</table>";
}

function show_connector_out_form($connector_id) {
	global $msg;
	print '<form method="POST" action="admin.php?categ=connecteurs&sub=out&action=update" name="form_connectorout">';
	print '<h3>'.$msg['connector_out_edit'].'</h3>';
		
	print '<div class="form-contenu">';
	
	//id
	print '<input type="hidden" name="id" value="'.$connector_id.'">';
	
	$daconn = instantiate_connecteur_out($connector_id);
	if ($daconn) {
		echo $daconn->get_config_form();		
	}
	
	//buttons
	print "</div><div class='row'>
	<div class='left'>";
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=connecteurs&sub=out'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';	
	print "</div></div>&nbsp;";
	print '</form>';
	
}

function show_sourceout_form($source_id=0, $connector_id, $name="", $comment="", $config_form=NULL) {
	global $msg;
	print '<form method="POST" action="admin.php?categ=connecteurs&sub=out&action=source_update" name="form_connectorout">';
	if ($source_id)
		print '<h3>'.$msg['connector_out_sourceedit'].'</h3>';
	else 
		print '<h3>'.$msg['connector_out_sourceadd'].'</h3>';
		
	print '<div class="form-contenu">';
	
	//id
	print '<input type="hidden" name="id" value="'.$source_id.'">';
	print '<input type="hidden" name="connector_id" value="'.$connector_id.'">';
	
	if ($config_form) {
		print '<br />';
		print call_user_func($config_form);
		print '<br />';
	}
	
	//buttons
	print "</div><div class='row'>";
	print '<div class="left">';
	print "<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=connecteurs&sub=out'\" />&nbsp";
	print '<input class="bouton" type="submit" value="'.$msg[77].'">';
	print "</div><div class='right'>";
	if ($source_id) {
		print confirmation_delete("./admin.php?categ=connecteurs&sub=out&action=source_del&id=");
		print "<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete('".$source_id."','".addslashes($name)."')\" />";		
	} 		
	
	print '</form>';
}

/*$conn = new connecteur_out(0, "dummy");
highlight_string(print_r($conn, true));
echo $conn->ckeck_api_requirements();*/

/*$conns = new connecteurs_out();
highlight_string(print_r($conns, true));*/

switch ($action)  {
	case "update":
		$daconn = instantiate_connecteur_out($id);
		if ($daconn) {
			$daconn->update_config_from_form();
			$daconn->commit_to_db();	
		}
		list_connectors_out();
		break;
	case "edit":
		show_connector_out_form($id);
		break;
	case "source_add":
		if (!$connector_id) {
			list_connectors_out();
			break;			
		}
		$daconn = instantiate_connecteur_out($connector_id);
		if (!$daconn) {
			list_connectors_out();
			break;
		}
		$source_object = $daconn->instantiate_source_class(0);
		show_sourceout_form($id, $connector_id, "", "", array($source_object, 'get_config_form'));
		break;
	case "source_del":
		if (!$id) {
			list_connectors_out();
			break;			
		}
		connecteur_out_source::delete($id);
		list_connectors_out();
		break;
	case "source_edit":
		if (!$connector_id || !$source_id) {
			list_connectors_out();
			break;			
		}
		$daconn = instantiate_connecteur_out($connector_id);
		if (!$daconn) {
			list_connectors_out();
			break;
		}
		$source_object = $daconn->instantiate_source_class($source_id);
		show_sourceout_form($source_object->id, $connector_id, $source_object->name, $source_object->comment, array($source_object, 'get_config_form'));
		
		break;
	case "source_update":
		if (!$connector_id) {
			list_connectors_out();
			break;			
		}
		if (!$id) {
			//Création d'une nouvelle source
				//Récupération d'un nouvel id d'une nouvelle source générique vide
			$new_source = connecteur_out_source::add_new($connector_id);
			$new_source_id = $new_source->id;
			
			//Instantiation de cette nouvelle source en tant que source du connecteur
			$daconn = instantiate_connecteur_out($connector_id);
			if (!$daconn) {
				list_connectors_out();
				break;
			}
			$source_object = $daconn->instantiate_source_class($new_source_id);
			
			//Mise à jour
			$source_object->update_config_from_form();
			$source_object->commit_to_db();
		}
		else {
			//Modification d'une existante
			if (!$connector_id || !$id) {
				list_connectors_out();
				break;			
			}
			$daconn = instantiate_connecteur_out($connector_id);
			if (!$daconn) {
				list_connectors_out();
				break;
			}
			$source_object = $daconn->instantiate_source_class($id);
			$source_object->update_config_from_form();
			$source_object->commit_to_db();
		}
		
		list_connectors_out();
		break;
	default:
		list_connectors_out();
		break;
}

?>