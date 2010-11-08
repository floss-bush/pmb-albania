<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: param.inc.php,v 1.1 2010-07-08 15:28:34 arenou Exp $

require_once($visionneuse_path."/classes/mimetypes/$quoi/$quoi.class.php");	

$current_class = new $quoi();

switch($action){
	case "" :
		$submenu.=show_form();
		break;
	case "update" :
		if ($form_actif) update_params();
		$submenu.=show_form($action);
		break;
}

function show_form($action=''){
	global $quoi,$msg;
	global $current_class;
	
	$i = 0;
	if($action=="update") $message="<div class='erreur'>".$msg["visionneuse_admin_update"]."</div>";
	
	//on récup les infos déjà existantes
	$params = array();
	$rqt = "SELECT visionneuse_params_parameters FROM visionneuse_params WHERE visionneuse_params_class LIKE '$quoi'";
	if($res=mysql_query($rqt)){
		if(mysql_num_rows($res))
		$paramToUnserialize= htmlspecialchars_decode(mysql_result($res,0,0));
		$plop=$current_class->unserializeParams($paramToUnserialize);
		$current_class->getTabParam();
	}
	$form="
		$message
		<form class='form-admin' name='".$quoi."Param' method='POST' action='./admin.php?categ=visionneuse&sub=class&quoi=$quoi&vue=param&action=update' >
			<h3>$quoi</h3>
			<table>
				<tr>
					<th>Nom</th>
					<th>Valeur</th>
					<th>Description</th>
				</tr>";	
	foreach($current_class->tabParam as $key =>$tabParam){
		$form.="
				<tr class='".($i%2 ? "odd":"even")."'>
					<td>$key</td>
					<td><input type='".$tabParam['type']."' name='".$tabParam['name']."' id='".$tabParam['name']." 'value='".$tabParam['value']."' /></td>
					<td>".$tabParam['desc']."</td>
				</tr>";	
		$i++;
	}
	$form.="
			</table>
			<input type='hidden' name='form_actif' value='1'>
			<input class='bouton' type='submit' value='". $msg["visionneuse_admin_save"] ."' />
		</form>
	";
	return $form;
}

function update_params(){
	global $quoi;
	global $current_class;
	global $charset;
	
	$paramsToSerialize =array();
	$current_class->getTabParam();
	foreach ($_POST as $key => $value){
		if(isset($current_class->tabParam[$key])){
			$paramsToSerialize[$key] = $value;
		}
	}
	$serializedParams = $current_class->serializeParams($paramsToSerialize);
	$rqt = "SELECT visionneuse_params_class FROM visionneuse_params WHERE visionneuse_params_class LIKE '$quoi'";
	if($res=mysql_query($rqt)){
		if(mysql_num_rows($res))
			$requete = "UPDATE visionneuse_params SET visionneuse_params_parameters='".htmlspecialchars($serializedParams,ENT_QUOTES,$charset)."' WHERE visionneuse_params_class='$quoi'";
		else $requete= "INSERT INTO visionneuse_params SET visionneuse_params_class='$quoi', visionneuse_params_parameters='".htmlspecialchars($serializedParams,ENT_QUOTES,$charset)."'";
	}
	$res = mysql_query($requete);
}

?>
