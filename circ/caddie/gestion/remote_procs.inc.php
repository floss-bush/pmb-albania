<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: remote_procs.inc.php,v 1.4 2010-09-12 17:04:37 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/remote_procedure_client.class.php");


function show_remote_procs($type) {
	global $pmb_procedure_server_credentials, $pmb_procedure_server_address; 
	global $msg;
	global $charset;
	
	$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
	if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
		$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
		$procedures = $aremote_procedure_client->get_procs($type);
		
		if ($procedures) {
			if ($procedures->error_information->error_code) {
				$buf_contenu=$msg["remote_procedures_error_server"].":<br><i>".$procedures->error_information->error_string."</i>";
				print $buf_contenu;
			}
			else if (isset($procedures->elements)) {
				$current_set="";
				foreach ($procedures->elements as $aprocedure) {
					if ($aprocedure->current_attached_set != $current_set) {
						$parity=0;
						$current_set = $aprocedure->current_attached_set;
						$buf_contenu .= '<tr><th colspan=4>'.htmlentities($current_set, ENT_QUOTES, $charset).'</th>'; 
					}
					if ($parity % 2) {$pair_impair = "even"; } else {$pair_impair = "odd";}
					$parity += 1;
					
					$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./circ.php?categ=caddie&sub=gestion&quoi=remote_procs&action=view_remote&id=$aprocedure->id';\" ";
				        $buf_contenu.="\n<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
							<td width='80'>
								".($type == "PEMPS" ? "<input class='bouton' type='button' value=' ".$msg[procs_options_tester_requete]." ' onClick=\"document.location='./circ.php?categ=caddie&sub=gestion&quoi=remote_procs&action=execute_remote&id=$aprocedure->id'\" />" : "")."
								</td>
							<td>
								".($aprocedure->untested ? "[<i>".$msg["remote_procedures_procedure_non_validated"]."</i>]&nbsp;&nbsp;" : '')."<strong>$aprocedure->name</strong><br/>
								<small>$aprocedure->comment&nbsp;</small>
								</td>";
					$buf_contenu.="<td><input class='bouton' type='button' value=\"".$msg[remote_procedures_import]."\" onClick=\"document.location='./circ.php?categ=caddie&sub=gestion&quoi=remote_procs&action=import_remote&id=$aprocedure->id'\" /></td>
								</tr>";
								
								
				}
				$title = ($type == "PEMPS" ? $msg["remote_procedures_circ_select"] : $msg["remote_procedures_circ_action"]);
				$buf_contenu="<h1>".$title."</h1>"."<table></tr>".$buf_contenu."</table><br>";
				print $buf_contenu;
			}
			else {
				$title = ($type == "PEMPS" ? $msg["remote_procedures_circ_select"] : $msg["remote_procedures_circ_action"]);
				$buf_contenu="<h1>".$title."</h1>".$msg[remote_procedures_no_procs]."<br><br>";
				print $buf_contenu;
			}
		}
	}
}

function show_procs() {
	show_remote_procs("PEMPS");
	show_remote_procs("PEMPA");	
}

function display_remote_proc($id) {
	global $pmb_procedure_server_credentials, $pmb_procedure_server_address;
	global $empr_proc_view_remote;
	global $type_list;
	global $msg;
	
	$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
	$the_procedure = 0;
	if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
		$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
		$procedure = $aremote_procedure_client->get_proc($id);
		if ($procedure["error_message"]) {
			$buf_contenu=htmlentities($msg["remote_procedures_error_server"], ENT_QUOTES, $charset).":<br><i>".$procedure["error_message"]."</i>";
			print $buf_contenu;
			return;			
		}
		$the_procedure = $procedure["procedure"];
		
	}
	if (!$the_procedure) {
		echo htmlentities($msg["remote_procedures_error_client"],ENT_QUOTES, $charset);
		return;
	}

	global $msg;
	global $admin_proc_form;
	global $charset;
	
	$empr_proc_view_remote = str_replace('!!id!!', $id, $empr_proc_view_remote);
	$empr_proc_view_remote = str_replace('!!form_title!!', "Détails d'une procédure distante", $empr_proc_view_remote);

	$additional_information = $the_procedure->untested ? $msg["remote_procedures_procedure_non_validated_additional_information"] : "";
	$empr_proc_view_remote = str_replace('!!additional_information!!', htmlentities($additional_information,ENT_QUOTES, $charset), $empr_proc_view_remote);
 	$empr_proc_view_remote = str_replace('!!name!!', htmlentities($the_procedure->name,ENT_QUOTES, $charset), $empr_proc_view_remote);
 	$empr_proc_view_remote = str_replace('!!name_suppr!!', htmlentities(addslashes($the_procedure->name),ENT_QUOTES, $charset), $empr_proc_view_remote);
 	$empr_proc_view_remote = str_replace('!!ptype!!', htmlentities(($the_procedure->type == "PEMPS" ? $msg["caddie_procs_type_SELECT"] : $msg["caddie_procs_type_ACTION"]),ENT_QUOTES, $charset), $empr_proc_view_remote);
 	$empr_proc_view_remote = str_replace('!!code!!', htmlentities($the_procedure->sql,ENT_QUOTES, $charset), $empr_proc_view_remote);
 	$empr_proc_view_remote = str_replace('!!comment!!', htmlentities($the_procedure->comment,ENT_QUOTES, $charset), $empr_proc_view_remote);
	
 	$parameters = $the_procedure->params;
 	$parameters = $aremote_procedure_client->parse_parameters($parameters);
//	highlight_string(print_r($parameters, true));
	if ($parameters) {
		$empr_proc_view_remote = str_replace('!!parameters_title!!', "<label class='etiquette' for='form_comment'>".htmlentities($msg["remote_procedures_procedure_parameters"],ENT_QUOTES, $charset)."</label>", $empr_proc_view_remote);
		$parameters_display = '<table><tr><th>'.htmlentities($msg["remote_procedures_procedure_parameters_name"],ENT_QUOTES, $charset).'</th><th>'.htmlentities($msg["remote_procedures_procedure_parameters_title"],ENT_QUOTES, $charset).'</th><th>'.htmlentities($msg["remote_procedures_procedure_parameters_type"],ENT_QUOTES, $charset).'</th><th>'.htmlentities($msg["remote_procedures_procedure_parameters"],ENT_QUOTES, $charset).'</th></tr>';
		$parity = 0;
		foreach($parameters as $parametername => $parameter) {
			$pair_impair = $parity++ % 2 ? "even" : "odd";
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
			$parameters_display .= '<tr class="'.$pair_impair.'" '.$tr_javascript.'>';
			$parameters_display .= '<td align="center">'.htmlentities($parametername,ENT_QUOTES, $charset).'</td>';
			$parameters_display .= '<td align="center">'.htmlentities($parameter["title"]['value'],ENT_QUOTES, $charset).'</td>';
			$parameters_display .= '<td align="center">'.htmlentities($type_list[$parameter["type"]["value"]],ENT_QUOTES, $charset).'</td>';
			switch ($parameter["type"]["value"]) {
				case "query_list":
					$parameters_display .= '<td><ul><li>'.htmlentities($msg["procs_options_requete"], ENT_QUOTES, $charset).': '.htmlentities($parameter["options"]["QUERY"][0]["value"],ENT_QUOTES, $charset).'</li><li>'.htmlentities($msg["procs_options_liste_multi"], ENT_QUOTES, $charset).': '.($parameter["options"]["MULTIPLE"][0]["value"] == "yes" ? htmlentities($msg["40"], ENT_QUOTES, $charset) : htmlentities($msg["39"], ENT_QUOTES, $charset)).'</li></ul></td>';
					break;
				case "text":
					$parameters_display .= '<td><ul><li>'.htmlentities($msg["procs_options_text_taille"], ENT_QUOTES, $charset).': '.htmlentities($parameter["options"]["SIZE"][0]["value"],ENT_QUOTES, $charset).'</li><li>'.htmlentities($msg["procs_options_text_max"], ENT_QUOTES, $charset).': '.($parameter["options"]["MAXSIZE"][0]["value"]).'</li></ul></td>';
					break;
				case "list":
					$parameters_display .= '<td><ul>';
					$parameters_display .= '<li>'.htmlentities($msg["procs_options_liste_multi"], ENT_QUOTES, $charset).': '.($parameter["options"]["MULTIPLE"][0]["value"] == "yes" ? htmlentities($msg["40"], ENT_QUOTES, $charset) : htmlentities($msg["39"], ENT_QUOTES, $charset)).'</li>';
					$parameters_display .= '<li>'.htmlentities($msg["procs_options_choix_vide"], ENT_QUOTES, $charset).': '.(htmlentities($parameter["options"]["UNSELECT_ITEM"][0]["value"],ENT_QUOTES, $charset)).' ('.htmlentities($parameter["options"]["UNSELECT_ITEM"][0]["VALUE"],ENT_QUOTES, $charset).')</li>';
					$choix=array();
					foreach($parameter["options"]["ITEMS"][0]["ITEM"] as $achoix) {
						$choix[] = $achoix["value"]." (".$achoix["VALUE"].")"; 
					}
					$parameters_display .= '<li>'.htmlentities($msg["procs_options_liste_options"], ENT_QUOTES, $charset).': '.(htmlentities(implode("; ", $choix),ENT_QUOTES, $charset)).'</li>';
					$parameters_display .= '</ul></td>';
					break;
				case "date_box":
					$parameters_display .= '<td><br><br></td>';
					break;
				case "selector":
					$parameters_display .= '<td><ul>';
					$parameters_display .= '<li>'.htmlentities($msg["include_option_methode"], ENT_QUOTES, $charset).': '.($parameter["options"]["METHOD"][0]["value"] == "1" ? $msg['parperso_include_option_selectors_id'] : $msg['parperso_include_option_selectors_label']).'</li>';
					$id_captions=array($msg['133'], $msg['134'], $msg['135'], $msg['136'], $msg['137'], $msg['333'], $msg['indexint_menu']);
					$parameters_display .= '<li>'.htmlentities($msg["include_option_type_donnees"], ENT_QUOTES, $charset).': '.(htmlentities($id_captions[$parameter["options"]["DATA_TYPE"][0]["value"]], ENT_QUOTES, $charset)).'</li>';
					$parameters_display .= '</ul></td>';
					break;
				case "file_box":
					$parameters_display .= '<td><ul>';
					$parameters_display .= '<li>'.htmlentities($msg["include_option_methode"], ENT_QUOTES, $charset).': '.($parameter["options"]["METHOD"][0]["value"] == "1" ? htmlentities($msg["57"], ENT_QUOTES, $charset) : htmlentities($msg["include_option_table"], ENT_QUOTES, $charset)).'</li>';
					$parameters_display .= '<li>'.htmlentities($msg["include_option_nom_table"], ENT_QUOTES, $charset).': '.(htmlentities($parameter["options"]["TEMP_TABLE_NAME"][0]["value"],ENT_QUOTES, $charset)).'</li>';
					$parameters_display .= '<li>'.htmlentities($msg["include_option_type_donnees"], ENT_QUOTES, $charset).': '.($parameter["options"]["DATA_TYPE"][0]["value"] == "1" ? "Chaine" : "Entier").'</li>';
					$parameters_display .= '</ul></td>';
					break;
				default:
				break;
			}
			
			$parameters_display .= '</tr>';
		}
		$parameters_display .= '</table>';
		$empr_proc_view_remote = str_replace('!!parameters_content!!', $parameters_display, $empr_proc_view_remote);
	}
	
	$empr_proc_view_remote = str_replace('!!parameters_title!!', "", $empr_proc_view_remote);
	$empr_proc_view_remote = str_replace('!!parameters_content!!', "", $empr_proc_view_remote);
	
	print $empr_proc_view_remote;
	
}

switch($action) {
	case 'view_remote':
		if ($id) {
			display_remote_proc($id);
		}
		break;
	case 'import_remote':
		if ($id) {
			if($do_import) {
				$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
				$the_procedure = 0;
				if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
					$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
					$procedure = $aremote_procedure_client->get_proc($id);
					if ($procedure["error_message"]) {
						$buf_contenu=htmlentities($msg["remote_procedures_error_server"], ENT_QUOTES, $charset).":<br><i>".$procedure["error_message"]."</i>";
						print $buf_contenu;
						return;			
					}
					$the_procedure = $procedure["procedure"];
				}
				
				if (!$the_procedure) {
					echo htmlentities($msg["remote_procedures_error_client"],ENT_QUOTES, $charset);
					break;
				}
				$proc_classement = isset($proc_classement) ? $proc_classement : 0;
				$proc_classement += 0; 
				
				if (is_array($userautorisation)) $autorisations=implode(" ",$userautorisation);
					else $autorisations='';
				
				if ($imported_name)
					$the_procedure->name = $imported_name;
				else
					$the_procedure->name = mysql_escape_string($the_procedure->name);
				if ($imported_comment)
					$the_procedure->comment = $imported_comment;
				else
					$the_procedure->comment = mysql_escape_string($the_procedure->comment);
					
				$parameters=$the_procedure->params;
				$type = $the_procedure->type == "PEMPA" ? 'ACTION' : 'SELECT';
				$sql = "INSERT INTO empr_caddie_procs (type, name, requete, comment, autorisations, parameters) VALUES ('".$type."', '".$the_procedure->name."', '".mysql_escape_string($the_procedure->sql)."', '".$the_procedure->comment."', '".mysql_escape_string($autorisations)."', '".mysql_escape_string($parameters)."')";
				$res = mysql_query($sql, $dbh);
				show_procs($dbh);
			}
			else {
				$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
				$the_procedure = 0;
				if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
					$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
					$procedure = $aremote_procedure_client->get_proc($id);
					if ($procedure["error_message"]) {
						$buf_contenu=htmlentities($msg["remote_procedures_error_server"], ENT_QUOTES, $charset).":<br><i>".$procedure["error_message"]."</i>";
						print $buf_contenu;
						return;			
					}
					$the_procedure = $procedure["procedure"];
				}
				
				if (!$the_procedure) {
					echo htmlentities($msg["remote_procedures_error_client"],ENT_QUOTES, $charset);
					break;
				}
				
				//Regardons si on a déjà une procédure avec ce nom là dans la base de donnée
				$type = $the_procedure->type == "PEMPA" ? 'ACTION' : 'SELECT';
				$sql_test = "SELECT COUNT(*) FROM empr_caddie_procs WHERE type ='".$type."' AND name='".addslashes($the_procedure->name)."'";
				$count = mysql_result(mysql_query($sql_test), 0, 0);
				if ($count) {
					print "
						<br/><div class='erreur'>$msg[remote_procedures_import_remote_already_exists_caution]</div>
						<script type='text/javascript' src='./javascript/tablist.js'></script>
						<div class='row'>
							<div class='colonne10'>
								<img src='./images/error.gif' align='left'>
							</div>
							<div class='colonne80'>
								<strong>".$msg["remote_procedures_import_remote_already_exists"]."</strong>
							</div>
						</div><br><br>
						";
				}
				
				$form = "<form class='form-$current_module' name='maj_proc' method='post' action='circ.php?categ=caddie&sub=gestion&quoi=remote_procs&action=import_remote&id=".$id."&do_import=1'>"; 
				$form .= "<h3><span onclick='menuHide(this,event)'>>".$msg["remote_procedures_import_remote"]."</span></h3>";
				$form .= "<div class='form-contenu'>";
				$form .= '<b>'.$msg["remote_procedures_procedure_name"].':</b><br><input name="imported_name" size="70" type="text" value="'.htmlentities($the_procedure->name, ENT_QUOTES, $charset).'" /><br><br>';
				$form .= '<b>'.$msg["caddie_procs_type"].':</b><br>'.htmlentities((in_array($the_procedure->type, array('PNS', 'PES', 'PEMPS', 'PBS')) ? $msg["caddie_procs_type_SELECT"] : $msg["caddie_procs_type_ACTION"]), ENT_QUOTES, $charset)."<br><br>";
				$form .= '<b>'.$msg["remote_procedures_procedure_comment"].':</b><br><input name="imported_comment" size="70" type="text" value="'.htmlentities($the_procedure->comment, ENT_QUOTES, $charset).'" /><br><br>';
				
				$requete_users = "SELECT userid, username FROM users order by username ";
				$res_users = mysql_query($requete_users, $dbh);
				$all_users=array();
				while (list($all_userid,$all_username)=mysql_fetch_row($res_users)) {
					$all_users[]=array($all_userid,$all_username);
				}
				foreach($all_users as $a_user) {
					$id_check="auto_".$a_user[0];
					if($id_check_list)$id_check_list.='|';
					$id_check_list.=$id_check;
					$autorisations_users.="<span class='usercheckbox'><input type='checkbox' name='userautorisation[]' id='$id_check' value='".$a_user[0]."' class='checkbox'><label for='$id_check' class='normlabel'>&nbsp;".$a_user[1]."</label></span>&nbsp;&nbsp;";
				}
				$autorisations_users.="<input type='hidden' id='auto_id_list' name='auto_id_list' value='$id_check_list' >";
				$form .= "<div class='row'>
				<label class='etiquette' for='form_comment'>$msg[procs_autorisations]</label>
				<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
				<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
				</div>";
				$form .= $autorisations_users;
				
				$form .= '</div>';
				$form .= "<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./circ.php?categ=caddie&sub=gestion&quoi=remote_procs\"' />&nbsp;";
				$form .= "<input type='submit' class='bouton' value='".$msg["remote_procedures_import"]."' />&nbsp;";
				$form .= '</form>';
				print $form;
			}
		}
		break;
	case 'execute_remote':
		if (!$id)
			break;

		$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
		$the_procedure = 0;
		if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
			$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
			$procedure = $aremote_procedure_client->get_proc($id);
			if ($procedure["error_message"]) {
				$buf_contenu=htmlentities($msg["remote_procedures_error_server"], ENT_QUOTES, $charset).":<br><i>".$procedure["error_message"]."</i>";
				print $buf_contenu;
				return;			
			}
			$the_procedure = $procedure["procedure"];
		}
		
		if (!$the_procedure) {
			echo htmlentities($msg["remote_procedures_error_client"],ENT_QUOTES, $charset);
			break;
		}
		
		if ($the_procedure->type != "PEMPS") {
			echo htmlentities($msg["remote_procedures_circ_noPEMPS"],ENT_QUOTES, $charset);
			break;
		}
			
		if ($the_procedure->params && ($the_procedure->params != "NULL")) {
//			$sql = "DROP TABLE IF EXISTS remote_proc";
//			mysql_query($sql, $dbh) or die(mysql_error());
			
			$sql = "CREATE TEMPORARY TABLE remote_proc LIKE procs";
			mysql_query($sql, $dbh) or die(mysql_error());
			
			$sql = "INSERT INTO remote_proc (idproc, name, requete, comment, autorisations, parameters, num_classement) VALUES (0, '".mysql_escape_string($the_procedure->name)."', '".mysql_escape_string($the_procedure->sql)."', '".mysql_escape_string($the_procedure->comment)."', '', '".mysql_escape_string($the_procedure->params)."', 0)";
			mysql_query($sql, $dbh) or die(mysql_error());
			$idproc = mysql_insert_id($dbh);
			
			$hp=new parameters($idproc,"remote_proc");
			if (preg_match_all("|!!(.*)!!|U",$hp->proc->requete,$query_parameters))
				$hp->gen_form("circ.php?categ=caddie&sub=gestion&quoi=remote_procs&action=final_remote&id=$id");
			else echo "<script>document.location='circ.php?categ=caddie&sub=gestion&quoi=remote_procs&action=final_remote&id=".$id."'</script>";
		}
		else echo "<script>document.location='circ.php?categ=caddie&sub=gestion&quoi=remote_procs&action=final_remote&id=".$id."'</script>";

		break;
	case 'final_remote':
		if (!$id)
			break;

		$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
		$the_procedure = 0;
		if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
			$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
			$procedure = $aremote_procedure_client->get_proc($id);
			if ($procedure["error_message"]) {
				$buf_contenu=htmlentities($msg["remote_procedures_error_server"], ENT_QUOTES, $charset).":<br><i>".$procedure["error_message"]."</i>";
				print $buf_contenu;
				return;			
			}
			$the_procedure = $procedure["procedure"];
		}
		
		if (!$the_procedure) {
			echo htmlentities($msg["remote_procedures_error_client"],ENT_QUOTES, $charset);
			break;
		}
		
		if ($the_procedure->type != "PEMPS") {
			echo htmlentities($msg["remote_procedures_circ_noPEMPS"],ENT_QUOTES, $charset);
			break;
		}
		
		$sql = "CREATE TEMPORARY TABLE remote_proc LIKE procs";
		mysql_query($sql, $dbh) or die(mysql_error());
		
		$sql = "INSERT INTO remote_proc (idproc, name, requete, comment, autorisations, parameters, num_classement) VALUES (0, '".mysql_escape_string($the_procedure->name)."', '".mysql_escape_string($the_procedure->sql)."', '".mysql_escape_string($the_procedure->comment)."', '', '".mysql_escape_string($the_procedure->params)."', 0)";
		mysql_query($sql, $dbh) or die(mysql_error());
		$idproc = mysql_insert_id($dbh);
		
		$hp=new parameters($idproc,"remote_proc");
		if (preg_match_all("|!!(.*)!!|U",$hp->proc->requete,$query_parameters)) {
			$hp->get_final_query();
			$the_procedure->sql = $hp->final_query;
		}

		$execute_external = true;
		$execute_external_procedure = $the_procedure;
		
		include("./circ/caddie/gestion/execute.inc.php");
		
		break;
	default:
		if (!$pmb_procedure_server_address) {
			echo $msg["remote_procedures_error_noaddress"];
			break;
		}
		show_procs();
		break;
}
?>