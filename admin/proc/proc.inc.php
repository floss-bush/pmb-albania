<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: proc.inc.php,v 1.16.2.2 2011-09-06 09:11:22 jpermanne Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/remote_procedure_client.class.php");
require_once("$include_path/parser.inc.php");
require_once("./classes/notice_tpl_gen.class.php");

print "<script type='text/javascript'>
function test_form(form) {
	if(form.f_proc_name.value.length == 0) {
		alert(\"$msg[702]\");
		form.f_proc_name.focus();
		return false;
		}
	if(form.f_proc_code.value.length == 0) {
		alert(\"$msg[703]\");
		form.f_proc_code.focus();
		return false;
		}
	return true;
	}
</script>";

//Verification de la presence et de la syntaxe des parametres de la requete
//retourne true si OK, le nom du parametre entre parentheses sinon
function check_param($requete) {
	$query_parameters=array();
	//S'il y a des termes !!*!! dans la requête alors il y a des paramètres
	if (preg_match_all("|!!(.*)!!|U",$requete,$query_parameters)) {
			for ($i=0; $i<count($query_parameters[1]); $i++) {
				if (!preg_match("/^[A-Za-z][A-Za-z0-9_]*$/",$query_parameters[1][$i])) {
					return "(".$query_parameters[1][$i].")";
				}
			}
	}
	return true;
}

function show_procs($dbh) {
	global $msg, $javascript_path, $pmb_procedure_server_address, $pmb_procedure_server_credentials, $charset;

	print "
		<script type=\"text/javascript\" src=\"".$javascript_path."/tablist.js\"></script>
		<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
		<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>
		";
	// affichage du tableau des procédures
	$requete = "SELECT idproc, name, requete, comment, libproc_classement, num_classement FROM procs left join procs_classements on idproc_classement=num_classement ORDER BY libproc_classement,name ";
	$res = mysql_query($requete, $dbh);
	$nbr = mysql_num_rows($res);

	$class_prec=$msg[proc_clas_aucun];
	$buf_tit="";
	$buf_class=0;
	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_row($res);
		$classement=$row[4];
		if ($class_prec!=$classement) {
			if (!$row[4]) $row[4]=$msg[proc_clas_aucun];
			if ($buf_tit) {
				$buf_contenu="<table><tr><th colspan=4>".$buf_tit."</th></tr>".$buf_contenu."</table>";
				print gen_plus("procclass".$buf_class,$buf_tit,$buf_contenu);
				$buf_contenu="";
			}
			$buf_tit=$row[4];
			$buf_class=$row[5];
			$class_prec=$classement;
		}		
		if ($parity % 2) {
			$pair_impair = "even";
			} else {
				$pair_impair = "odd";
				}
		$parity += 1;
		$action = "onmousedown=\"document.location='./admin.php?categ=proc&sub=proc&action=modif&id=$row[0]';\"";
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"  ";
        $buf_contenu.="\n<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
				<td width='10'>
					<input class='bouton' type='button' value=' $msg[708] ' onClick=\"document.location='./admin.php?categ=proc&sub=proc&action=execute&id=$row[0]'\" />
					</td>
				<td $action>
					<strong>$row[1]</strong><br />
					<small>$row[3]&nbsp;</small>
					</td>
				<td $action>";
		if (preg_match_all("|!!(.*)!!|U",$row[2],$query_parameters)) $buf_contenu.="<a href='admin.php?categ=proc&sub=proc&action=configure&id_query=".$row[0]."'>".$msg["procs_options_config_param"]."</a>";
		$buf_contenu.="</td>";
		$buf_contenu.="<td><input class='bouton' type='button' value=\"".$msg[procs_bt_export]."\" onClick=\"document.location='./export.php?quoi=procs&sub=actionsperso&id=$row[0]'\" /></td>
					</tr>";
		}
	$buf_contenu="<table><tr><th colspan=4>".$buf_tit."</th></tr>".$buf_contenu."</table>";
	print gen_plus("procclass".$buf_class,$buf_tit,$buf_contenu);	
	
	//
	//Procédures Externes
	//
	$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
	if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
		$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
		$procedures = $aremote_procedure_client->get_procs("AP");
		
		if ($procedures) {
			if ($procedures->error_information->error_code) {
				$buf_contenu=$msg['remote_procedures_error_server'].":<br><i>".$procedures->error_information->error_string."</i>";
				print gen_plus("procclass_remote",$msg["remote_procedures"],$buf_contenu);
			}
			else if (isset($procedures->elements)){
				$buf_contenu="";
				$current_set="";
				foreach ($procedures->elements as $aprocedure) {
					if ($aprocedure->current_attached_set != $current_set) {
						$parity=0;
						$current_set = $aprocedure->current_attached_set;
						$buf_contenu .= '<tr><th colspan=4>'.htmlentities($current_set, ENT_QUOTES, $charset).'</th>'; 
					}
					if ($parity % 2) {$pair_impair = "even"; } else {$pair_impair = "odd";}
					$parity += 1;
					$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=proc&sub=proc&action=view_remote&id=$aprocedure->id';\" ";
				        $buf_contenu.="\n<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
							<td width='10'>
								<input class='bouton' type='button' value=' $msg[708] ' onClick=\"document.location='./admin.php?categ=proc&sub=proc&action=execute_remote&id=$aprocedure->id'\" />
								</td>
							<td>
								".($aprocedure->untested ? "[<i>".$msg["remote_procedures_procedure_non_validated"]."</i>]&nbsp;&nbsp;" : '')."<strong>$aprocedure->name</strong><br/>
								<small>$aprocedure->comment&nbsp;</small>
								</td>
							<td>";
					//if (preg_match_all("|!!(.*)!!|U",$row[2],$query_parameters)) $buf_contenu.="<a href='admin.php?categ=proc&sub=proc&action=configure&id_query=".$row[0]."'>".$msg["procs_options_config_param"]."</a>";
					$buf_contenu.="</td>";
					$buf_contenu.="<td><input class='bouton' type='button' value=\"".$msg[remote_procedures_import]."\" onClick=\"document.location='./admin.php?categ=proc&sub=proc&action=import_remote&id=$aprocedure->id'\" /></td>
								</tr>";
				}
				$buf_contenu="<table></tr>".$buf_contenu."</table>";
				print gen_plus("procclass_remote",$msg["remote_procedures"],$buf_contenu);
			}
			else {
				$buf_contenu="<br>".$msg["remote_procedures_no_procs"]."<br><br>";
				print gen_plus("procclass_remote",$msg["remote_procedures"],$buf_contenu);
			}
		}
	}

	print "<br>
		<input class='bouton' type='button' value=' $msg[704] ' onClick=\"document.location='./admin.php?categ=proc&sub=proc&action=add'\" />
		<input class='bouton' type='button' value=' $msg[procs_bt_import] ' onClick=\"document.location='./admin.php?categ=proc&sub=proc&action=import'\" />
		<input class='bouton' type='button' value=' $msg[admin_menu_req] ' onClick=\"document.location='./admin.php?categ=proc&sub=req&action=add'\" />";
	
}

function proc_form($name='', $code='', $comment='', $id=0, $autorisations=array(), $num_classement=0,$notice_tpl=0,$notice_tpl_col='') {
	global $msg;
	global $admin_proc_form;
	global $charset;

	$admin_proc_form = str_replace('!!id!!', $id, $admin_proc_form);
	if (!$id) $admin_proc_form = str_replace('!!form_title!!', $msg[704], $admin_proc_form);
	else $admin_proc_form = str_replace('!!form_title!!', $msg["procs_modification"], $admin_proc_form);

	if ($id && $name && $code) $action = "./admin.php?categ=proc&sub=proc&action=modif&id=$id";
	else $action = "./admin.php?categ=proc&sub=proc&action=add";
	$admin_proc_form = str_replace('!!action!!', $action, $admin_proc_form);
	
 	$admin_proc_form = str_replace('!!name!!', htmlentities($name,ENT_QUOTES, $charset), $admin_proc_form);
 	$admin_proc_form = str_replace('!!name_suppr!!', htmlentities(addslashes($name),ENT_QUOTES, $charset), $admin_proc_form);
 	$admin_proc_form = str_replace('!!code!!', htmlentities($code,ENT_QUOTES, $charset), $admin_proc_form);
 	$admin_proc_form = str_replace('!!comment!!', htmlentities($comment,ENT_QUOTES, $charset), $admin_proc_form);
 	
 	//$sel_notice_tpl=notice_tpl_gen::gen_tpl_select("form_notice_tpl",$notice_tpl);
 	$sel_notice_tpl="<input type='text' class='saisie-15em' name='form_notice_tpl_field' value='".$notice_tpl_col."' >";
	$admin_proc_form = str_replace('!!notice_tpl!!',$sel_notice_tpl, $admin_proc_form);
 	
	$autorisations_users="";
	$id_check_list='';
	while (list($row_number, $row_data) = each($autorisations)) {
		$id_check="auto_".$row_data[1];
		if($id_check_list)$id_check_list.='|';
		$id_check_list.=$id_check;
		if ($row_data[0]) $autorisations_users.="<span class='usercheckbox'><input type='checkbox' name='userautorisation[]' id='$id_check' value='".$row_data[1]."' checked class='checkbox'><label for='$id_check' class='normlabel'>&nbsp;".$row_data[2]."</label></span>&nbsp;&nbsp;";
		else $autorisations_users.="<span class='usercheckbox'><input type='checkbox' name='userautorisation[]' id='$id_check' value='".$row_data[1]."' class='checkbox'><label for='$id_check' class='normlabel'>&nbsp;".$row_data[2]."</label></span>&nbsp;&nbsp;";
	}
	$autorisations_users.="<input type='hidden' id='auto_id_list' name='auto_id_list' value='$id_check_list' >";	
	$admin_proc_form = str_replace('!!autorisations_users!!', $autorisations_users, $admin_proc_form);
	
	$combo_clas= gen_liste ("SELECT idproc_classement,libproc_classement FROM procs_classements ORDER BY libproc_classement ", "idproc_classement", "libproc_classement", "form_classement", "", $num_classement, 0, $msg[proc_clas_aucun],0, $msg[proc_clas_aucun]) ;
	$admin_proc_form = str_replace('!!classement!!', $combo_clas, $admin_proc_form);
	
	print confirmation_delete("./admin.php?categ=proc&sub=proc&action=del&id=");
	print $admin_proc_form;
}

function run_form($id, $dbh) {
	global $msg;
	global $charset;
	
	$hp=new parameters($id,"procs");
	if (preg_match_all("|!!(.*)!!|U",$hp->proc->requete,$query_parameters))
		$hp->gen_form("admin.php?categ=proc&sub=proc&action=final&id=$id");
	else echo "<script>document.location='admin.php?categ=proc&sub=proc&action=final&id=$id'</script>";
}


function display_remote_proc($id) {
	global $pmb_procedure_server_credentials, $pmb_procedure_server_address;
	global $admin_proc_view_remote;
	global $type_list;
	global $msg;
	
	$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
	$the_procedure = 0;
	if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
		$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
		$procedure = $aremote_procedure_client->get_proc($id,"AP");
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
	
	$admin_proc_view_remote = str_replace('!!id!!', $id, $admin_proc_view_remote);
	$admin_proc_view_remote = str_replace('!!form_title!!', htmlentities($msg["remote_procedures_detail_procedure_distante"],ENT_QUOTES, $charset), $admin_proc_view_remote);

	$additional_information = $the_procedure->untested ? $msg["remote_procedures_procedure_non_validated_additional_information"] : "";
	$admin_proc_view_remote = str_replace('!!additional_information!!', htmlentities($additional_information,ENT_QUOTES, $charset), $admin_proc_view_remote);
	$admin_proc_view_remote = str_replace('!!name!!', htmlentities($the_procedure->name,ENT_QUOTES, $charset), $admin_proc_view_remote);
 	$admin_proc_view_remote = str_replace('!!name_suppr!!', htmlentities(addslashes($the_procedure->name),ENT_QUOTES, $charset), $admin_proc_view_remote);
 	$admin_proc_view_remote = str_replace('!!code!!', htmlentities($the_procedure->sql,ENT_QUOTES, $charset), $admin_proc_view_remote);
 	$admin_proc_view_remote = str_replace('!!comment!!', htmlentities($the_procedure->comment,ENT_QUOTES, $charset), $admin_proc_view_remote);
	
 	$parameters = $the_procedure->params;
 	$parameters = $aremote_procedure_client->parse_parameters($parameters);
//	highlight_string(print_r($parameters, true));
	if ($parameters) {
		$admin_proc_view_remote = str_replace('!!parameters_title!!', "<label class='etiquette' for='form_comment'>".htmlentities($msg["remote_procedures_procedure_parameters"],ENT_QUOTES, $charset)."</label>", $admin_proc_view_remote);
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
		$admin_proc_view_remote = str_replace('!!parameters_content!!', $parameters_display, $admin_proc_view_remote);
	}
	
	$admin_proc_view_remote = str_replace('!!parameters_title!!', "", $admin_proc_view_remote);
	$admin_proc_view_remote = str_replace('!!parameters_content!!', "", $admin_proc_view_remote);
	
	print confirmation_delete("./admin.php?categ=proc&sub=proc&action=del&id=");
	print $admin_proc_view_remote;
	
}

switch($action) {
	case 'configure':
		$hp=new parameters($id_query,"procs");
		$hp->show_config_screen("admin.php?categ=proc&sub=proc&action=update_config","admin.php?categ=proc&sub=proc");
		break;
	case 'update_config':
		$hp=new parameters($id_query,"procs");
		$hp->update_config("admin.php?categ=proc&sub=proc");
		break;
	case 'final':
		$hp=new parameters($id_query,"procs");
		if (preg_match_all("|!!(.*)!!|U",$hp->proc->requete,$query_parameters)) {
			$hp->get_final_query();
			$code=$hp->final_query;
			$id=$id_query;
		}
		include("./admin/proc/execute.inc.php");
		break;
	case 'execute':
		// form pour params et validation
		run_form($id, $dbh);
		break;
	case 'modif':
		if($id) {
			if($f_proc_name && $f_proc_code) {
				// faire la modification
				if (is_array($userautorisation)) $autorisations=implode(" ",$userautorisation);
					else $autorisations='';
				$param_name=check_param($f_proc_code);
				if ($param_name!==true) {
					error_message_history($param_name, sprintf($msg["proc_param_check_field_name"],$param_name), 1); 
					exit();
				}
				$requete = "UPDATE procs SET name='$f_proc_name',requete='$f_proc_code',comment='$f_proc_comment' , autorisations='$autorisations' , num_classement='$form_classement', 
				proc_notice_tpl='$form_notice_tpl', proc_notice_tpl_field='$form_notice_tpl_field' 
				WHERE idproc=$id ";
				$res = mysql_query($requete, $dbh);
				show_procs($dbh);
			} else {
				// afficher le form avec les bonnes valeurs
				// ALTER TABLE procs ADD autorisations MEDIUMTEXT;
				$requete = "SELECT idproc, name, requete, comment, autorisations, num_classement, proc_notice_tpl, proc_notice_tpl_field FROM procs WHERE idproc=$id LIMIT 1 ";
				$res = mysql_query($requete, $dbh);
				$requete_users = "SELECT userid, username FROM users order by username ";
				$res_users = mysql_query($requete_users, $dbh);
				$all_users=array();
				while (list($all_userid,$all_username)=mysql_fetch_row($res_users)) {
					$all_users[]=array($all_userid,$all_username);
					}
				if(mysql_num_rows($res)) {
					$row = mysql_fetch_row($res);
					$autorisations_donnees=explode(" ",$row[4]);
					for ($i=0 ; $i<count($all_users) ; $i++) {
						if (array_search ($all_users[$i][0], $autorisations_donnees)!==FALSE) $autorisation[$i][0]=1;
							else $autorisation[$i][0]=0;
						$autorisation[$i][1]= $all_users[$i][0];
						$autorisation[$i][2]= $all_users[$i][1];
					}						
					proc_form($row[1], $row[2], $row[3], $row[0],$autorisation, $row[5], $row[6], $row[7]);
					
				}
			}
		} else {
			show_procs($dbh);
		}
		break;
	case 'add':
		if($f_proc_name && $f_proc_code) {
			$requete = "SELECT count(1) FROM procs WHERE name='$f_proc_name' ";
			$res = mysql_query($requete, $dbh);
			$nbr_lignes = mysql_result($res, 0, 0);
			if(!$nbr_lignes) {
				if (is_array($userautorisation)) {
					$autorisations=implode(" ",$userautorisation);
				} else {
					$autorisations='';
				}
				$param_name=check_param($f_proc_code);
				if ($param_name!==true) {
					error_message_history($param_name, sprintf($msg["proc_param_check_field_name"],$param_name), 1);
					die();
				}
				$requete = "INSERT INTO procs (idproc,name,requete,comment,autorisations,num_classement, proc_notice_tpl, proc_notice_tpl_field) 
				VALUES ('', '$f_proc_name', '$f_proc_code', '$f_proc_comment', '$autorisations', '$form_classement', '$form_notice_tpl', '$form_notice_tpl_field' ) ";
				$res = mysql_query($requete, $dbh);
			} else {
				print "<script language='Javascript'>alert(\"$msg[709]\");</script>";
				print "<script language='Javascript'>history.go(-1);</script>";
			}
			show_procs($dbh);
		} else {
			$requete_users = "SELECT userid, username FROM users order by username ";
			$res_users = mysql_query($requete_users, $dbh);
			$autorisation=array();
			while (list($all_userid,$all_username)=mysql_fetch_row($res_users)) {
				$autorisation[]=array(0,$all_userid,$all_username);
				}
			proc_form("", "", "", 0, $autorisation);
		}
		break;
	case 'import':
		$import_proc_tmpl = str_replace("!!action!!", "./admin.php?categ=proc&sub=proc&action=importsuite", $import_proc_tmpl);
		print $import_proc_tmpl ;
		break;
	case 'importsuite':
		procs_create ("PROCS", "./admin.php?categ=proc&sub=proc&action=modif&id=!!id!!", "./admin.php?categ=proc&sub=proc&action=importsuite") ;
		break;
	case 'del':
		if($id) {
			$requete = "DELETE FROM procs WHERE idproc=$id ";
			$res = mysql_query($requete, $dbh);
			$requete = "OPTIMIZE TABLE procs ";
			$res = mysql_query($requete, $dbh);
			show_procs($dbh);
		}
		break;
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
					$procedure = $aremote_procedure_client->get_proc($id,"AP");
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

				$sql = "INSERT INTO procs (name, requete, comment, autorisations, parameters, num_classement) VALUES ('".$the_procedure->name."', '".mysql_escape_string($the_procedure->sql)."', '".$the_procedure->comment."', '".mysql_escape_string($autorisations)."', '".mysql_escape_string($parameters)."', ".mysql_escape_string($proc_classement).")";
				$res = mysql_query($sql, $dbh);
				show_procs($dbh);
			}
			else {
				$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
				$the_procedure = 0;
				if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
					$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
					$procedure = $aremote_procedure_client->get_proc($id,"AP");
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
				
				$form = "<form class='form-$current_module' name='maj_proc' method='post' action='admin.php?categ=proc&sub=proc&action=import_remote&id=".$id."&do_import=1'>"; 
				$combo_clas= gen_liste ("SELECT idproc_classement,libproc_classement FROM procs_classements ORDER BY libproc_classement ", "idproc_classement", "libproc_classement", "proc_classement", "", $num_classement, 0, $msg[proc_clas_aucun],0, $msg[proc_clas_aucun]);
				$form .= "<h3><span onclick='menuHide(this,event)'>>".$msg["remote_procedures_import_remote"]."</span></h3>";
				$form .= "<div class='form-contenu'>";
				$form .= '<b>'.$msg["remote_procedures_procedure_name"].':</b><br><input name="imported_name" size="70" type="text" value="'.htmlentities($the_procedure->name, ENT_QUOTES, $charset).'" /><br><br>';
				$form .= '<b>'.$msg["remote_procedures_procedure_comment"].':</b><br><input name="imported_comment" size="70" type="text" value="'.htmlentities($the_procedure->comment, ENT_QUOTES, $charset).'" /><br><br>';
				$form .= '<b>'.$msg["remote_procedures_putin"].':</b><br>'.$combo_clas."<br><br>";
				//$form .= '<b>'.$msg["remote_procedures_putin"].':</b><br>'.$combo_clas."<br><br>";
				
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
				$form .= "<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./admin.php?categ=proc&sub=proc\"' />&nbsp;";
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
			$procedure = $aremote_procedure_client->get_proc($id,"AP");
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
				$hp->gen_form("admin.php?categ=proc&sub=proc&action=final_remote&id=$id");
			else echo "<script>document.location='admin.php?categ=proc&sub=proc&action=final_remote&id=".$id."'</script>";
		}
		else echo "<script>document.location='admin.php?categ=proc&sub=proc&action=final_remote&id=".$id."'</script>";

		break;
	case 'final_remote':
		if (!$id)
			break;

		$pmb_procedure_server_credentials_exploded = explode("\n", $pmb_procedure_server_credentials);
		$the_procedure = 0;
		if ($pmb_procedure_server_address && (count($pmb_procedure_server_credentials_exploded) == 2)) {
			$aremote_procedure_client = new remote_procedure_client($pmb_procedure_server_address, trim($pmb_procedure_server_credentials_exploded[0]), trim($pmb_procedure_server_credentials_exploded[1]));
			$procedure = $aremote_procedure_client->get_proc($id,"AP");
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
		
		include("./admin/proc/execute.inc.php");
		
		break;
	default:
		show_procs($dbh);
		break;
	}
