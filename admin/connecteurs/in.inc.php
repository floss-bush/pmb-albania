<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: in.inc.php,v 1.19 2011-04-15 15:16:02 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/connecteurs.class.php");

//Affichage de la liste des connecteurs disponibles
function show_connectors() {
	global $msg,$lang,$charset,$base_path;
	
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
			<th>Service</th>
			<th>Sources</th>
			<th>&nbsp;</th>
		</tr>";
	
	$contrs=new connecteurs();
	$parity=1;
	foreach ($contrs->catalog as $id=>$prop) {
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$comment=$prop["COMMENT"];
		$sign=$prop["NAME"]." : ".$comment." - ";
		if ($prop["STATUS"]!="open") $sign.="(c) ";
		$sign.="Auteur : ".$prop["AUTHOR"]." - ".$prop["ORG"]." - ";
		$sign.=formatdate($prop["DATE"]);
		//Recherche du nombre de sources
		$n_sources=0;
		if (is_file($base_path."/admin/connecteurs/in/".$prop["PATH"]."/".$prop["NAME"].".class.php")) {
			require_once($base_path."/admin/connecteurs/in/".$prop["PATH"]."/".$prop["NAME"].".class.php");
			eval("\$conn=new ".$prop["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$prop["PATH"]."\");");
			$conn->get_sources();
			$n_sources=count($conn->sources);
		}
	    $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"if (event) e=event; else e=window.event; if (e.srcElement) target=e.srcElement; else target=e.target; if ((target.nodeName!='IMG')&&(target.nodeName!='INPUT')) document.location='./admin.php?categ=connecteurs&sub=in&act=modif&id=".$id."';\" ";
	    print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer' title='".htmlentities($sign,ENT_QUOTES,$charset)."' alter='".htmlentities($sign,ENT_QUOTES,$charset)."' id='tr$id'><td>".($n_sources?"<img src='images/plus.gif' class='img_plus' onClick='if (event) e=event; else e=window.event; e.cancelBubble=true; if (e.stopPropagation) e.stopPropagation(); show_sources(\"".addslashes($prop["NAME"])."\"); '/>":"&nbsp;")."</td><td>".htmlentities($comment,ENT_QUOTES,$charset)."</td>
		<td>".sprintf($msg["connecteurs_count_sources"],$n_sources)."</td><td style='text-align:right'><input type='button' value='".$msg["connecteurs_add_source"]."' class='bouton_small' onClick='document.location=\"admin.php?categ=connecteurs&sub=in&act=add_source&id=".$id."\"'/></td></tr>\n";
		if ($n_sources) {
			print "<tr class='$pair_impair' style='display:none' id='".$prop["NAME"]."'><td>&nbsp;</td><td colspan='3'><table style='border:1px solid'>";
			$parity_source=$parity;
/*			$requete = "SELECT count( * ) AS count, source_id FROM `external_count` WHERE 1 GROUP BY source_id;";
			$resultat=mysql_query($requete);
			while ($count=mysql_fetch_row($resultat)) {
				$counts[$count["1"]] = $count["0"];
			}*/

			foreach($conn->sources as $source_id=>$s) {
				if ($parity_source % 2) {
					$pair_impair_source = "even";
				} else {
					$pair_impair_source = "odd";
				}
				$parity_source += 1;
				$tr_javascript_source=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair_source'\" onmousedown=\"if (event) e=event; else e=window.event; if (e.srcElement) target=e.srcElement; else target=e.target; if (target.nodeName!='INPUT') document.location='./admin.php?categ=connecteurs&sub=in&act=add_source&id=".$id."&source_id=".$s["SOURCE_ID"]."';\" ";
				print "<tr style='cursor: pointer' class='$pair_impair_source' $tr_javascript_source>
					<td>".htmlentities($s["NAME"],ENT_QUOTES,$charset)."</td>
					<td>".htmlentities(substr($s["COMMENT"],0,60),ENT_QUOTES,$charset)."</td>
					<td>";
				if (($s["REPOSITORY"]==1)||($s["REPOSITORY"]==2)) {
					$requete="select count(distinct recid) from entrepot_source_".$source_id." where 1";
					$rnn=mysql_query($requete);
					$scount = mysql_result($rnn,0,0); //$counts[$source_id]; //)
					if (!$scount) $scount = 0;
					print "<td>".sprintf($msg["connecteurs_count_notices"],$scount)."</td>";
				}	
			if ($s["REPOSITORY"]==1) {
				print "<td>";
				if ($s["CANCELLED"]) {
					print "<input type='button' class='bouton_small' value='".$msg["connecteurs_sync_resume"]."' onClick='document.location=\"admin.php?categ=connecteurs&sub=in&act=sync&go=1&source_id=".$s["SOURCE_ID"]."&id=$id\"'/>&nbsp;";
					print "<input type='button' class='bouton_small' value='".$msg["connecteurs_sync_cancel"]."' onClick='document.location=\"admin.php?categ=connecteurs&sub=in&act=cancel_sync&source_id=".$s["SOURCE_ID"]."&id=$id\"'/>";
				}
				else if ($s["DATESYNC"]) {
					print sprintf($msg["connecteurs_sync_exists_menu"],$s["PERCENT"]);
					print "&nbsp;<input type='button' class='bouton_small' value='".$msg["connecteurs_sync_abort"]."' onClick='document.location=\"admin.php?categ=connecteurs&sub=in&act=abort_sync&source_id=".$s["SOURCE_ID"]."&id=$id\"'/>";
				}
				else {
					print "<input type='button' class='bouton_small' value='".$msg["connecteurs_sync"]."' onClick='document.location=\"admin.php?categ=connecteurs&sub=in&act=sync&source_id=".$s["SOURCE_ID"]."&id=$id\"'/>";
					print $s["LASTSYNCDATE"] != 0 ? "&nbsp;&nbsp;(".sprintf($msg["connecteurs_sync_lastdate"], format_date($s["LASTSYNCDATE"]), 1).")" : "";
				}
				print "</td>";
				
				print "<td>";
				print "<input type='button' class='bouton_small' value='".$msg["connecteurs_empty"]."' onClick='if (confirm(\"".$msg["connecteurs_del_notice_confirm"]."\")) document.location=\"admin.php?categ=connecteurs&sub=in&act=empty&source_id=".$s["SOURCE_ID"]."&id=$id\"'/>";
				print "</td>";
			}
			else {
				print "<td>&nbsp;</td><td>&nbsp;</td>";
			}
			print "</tr>\n";
			}
			print "</table></td></tr>";
		}
	}
	print "</table>";
}

switch ($act)  {
	case "modif":
		$contrs=new connecteurs();
		print $contrs->show_connector_form($id);
		break;
	case "update":
		if ($id) {
			$contrs=new connecteurs();
			require_once($base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."/".$contrs->catalog[$id]["NAME"].".class.php");
			eval("\$conn=new ".$contrs->catalog[$id]["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."\");");
			if ($conn) {
				$conn->timeout=$timeout;
				$conn->retry=$retry;
				$conn->ttl=$ttl;
				$conn->repository=$repository;
				$conn->rep_upload=$rep_upload;
				$conn->upload_doc_num=$upload_doc_num;
				$conn->save_property_form();
			}
		}
		show_connectors(); 
		break;
	case "cancel_sync":
		$sql = "DELETE FROM source_sync WHERE source_id = $source_id AND cancel > 0";
		mysql_query($sql);
		show_connectors();		
		break;
	case "abort_sync":
		$sql = "DELETE FROM source_sync WHERE source_id = $source_id ";
		mysql_query($sql);
		show_connectors();
		break;
	case "add_source":
		$contrs=new connecteurs();
		print $contrs->show_source_form($id,$source_id);
		break;
	case "update_source":
		if ($id) {
			$contrs=new connecteurs();
			require_once($base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."/".$contrs->catalog[$id]["NAME"].".class.php");
			eval("\$conn=new ".$contrs->catalog[$id]["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."\");");
			if (!$source_id) $source_id=0; 
			if ($conn) {
				$conn->sources[$source_id]["TIMEOUT"]=$timeout;
				$conn->sources[$source_id]["RETRY"]=$retry;
				$conn->sources[$source_id]["TTL"]=$ttl;
				$conn->sources[$source_id]["REPOSITORY"]=$repository;
				$conn->sources[$source_id]["NAME"]=stripslashes($name);
				$conn->sources[$source_id]["COMMENT"]=stripslashes($comment);
				$conn->sources[$source_id]["OPAC_ALLOWED"]=stripslashes($opac_allowed);
				$conn->sources[$source_id]["REP_UPLOAD"]=stripslashes($rep_upload);
				$conn->sources[$source_id]["ENRICHMENT"]=stripslashes($enrichment);
				$conn->sources[$source_id]["UPLOAD_DOC_NUM"]=stripslashes($upload_doc_num);
				//Vérification du nom
				$requete="select count(*) from connectors_sources where name='".$name."' and source_id!=$source_id and id_connector='".addslashes($contrs->catalog[$id]["NAME"])."'";
				$resultat=mysql_query($requete);
				if (mysql_result($resultat,0,0)==0) {
					$conn->source_save_property_form($source_id);
					show_connectors();
				} else {
					error_form_message($msg["connecteurs_name_exists"]);
				}
			}
		}
		break;
	case "delete_source":
		if ($id) {
			$contrs=new connecteurs();
			require_once($base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."/".$contrs->catalog[$id]["NAME"].".class.php");
			eval("\$conn=new ".$contrs->catalog[$id]["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."\");");
			if (($source_id)&&($conn)) { 
				$conn->del_source($source_id);
			}
			show_connectors();
		}
		break;
	case "sync":
		if ($id) {

			$contrs=new connecteurs();
			require_once($base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."/".$contrs->catalog[$id]["NAME"].".class.php");
			eval("\$conn=new ".$contrs->catalog[$id]["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."\");");

			//Si on doit afficher un formulaire de synchronisation
			$syncr_form = $conn->form_pour_maj_entrepot($source_id);			
			if (!$go && $syncr_form) {
				print '<form name="sync_form" action="'."admin.php?categ=connecteurs&sub=in&act=sync&source_id=".$source_id."&go=1&id=$id".'" method="POST"  enctype="multipart/form-data">';
				print $syncr_form;
				print "<input type='submit' class='bouton_small' value='".$msg["connecteurs_sync"]."'/>";
				print '</form>';				
			}
			else {
				if (($source_id)&&($conn)) {
					require_once($base_path."/admin/connecteurs/in/sync.inc.php");
				} 				
			}
		} else show_connectors();
		break;
	case "sync_custom_page":
		if ($id) {

			$contrs=new connecteurs();
			require_once($base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."/".$contrs->catalog[$id]["NAME"].".class.php");
			eval("\$conn=new ".$contrs->catalog[$id]["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."\");");
			print $conn->sync_custom_page($source_id);
		} 
		break;
	case "empty":
		if ($id) {
			$contrs=new connecteurs();
			require_once($base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."/".$contrs->catalog[$id]["NAME"].".class.php");
			eval("\$conn=new ".$contrs->catalog[$id]["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."\");");
			if (($source_id)&&($conn)) { 
				$conn->del_notices($source_id);
			}
			$sql = "UPDATE connectors_sources SET last_sync_date = '0000-00-00 00:00:00' WHERE source_id = $source_id ";
			mysql_query($sql); 
		} else show_connectors();
	default:
		show_connectors();
		break;
}
?>
