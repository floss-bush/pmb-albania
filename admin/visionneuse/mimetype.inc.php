<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mimetype.inc.php,v 1.3 2010-07-08 15:28:34 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($action){
	case "" :
		show_form();
		break;
	case "update" :
		if ($form_actif) update_mimetypeConf();
		show_form($action);
		break;
}

function show_form($action =''){
	global $msg;
	global $class_param;
	global $mimetypeConf,$mimetypeConfByDefault;
	
	if($action=="update") $message="<div class='erreur'>".$msg["visionneuse_admin_update"]."</div>";
	$form = "
	$message
	<form class='form-admin' name='modifParam' method='post' action='./admin.php?categ=visionneuse&sub=mimetype&action=update'>
		<h3>".$msg["visionneuse_admin_mimetype"]."</h3>
		<table>
			<tr>
				<th>".$msg["visionneuse_param_mimetype"]."</th>
				<th>".$msg["visionneuse_param_class_dispo"]."</th>
			</tr>";
	$i=0;
	foreach($class_param->mimetypeClasses as $mimetype => $classes){
		$form.="
		    <tr class='".($i%2 ? "odd":"even")."'>
				<td>$mimetype</td>
				<td>
					<br />
					<select name='$mimetype'>";
		foreach($classes as $key =>$class){
			if(sizeof($mimetypeConf)>0){
				$form.="
				<option value='$class'".($mimetypeConf[$mimetype]==$class ? " selected":"").">$class</option>";
			}else{
				$form.="
				<option value='$class'".($mimetypeConfByDefault[$mimetype]==$class ? " selected":"").">$class</option>";
			}
		}
		$form.="
					</select>
					<br />
					<br />
				</td>
			</tr>";
		$i++;
	}		
	$form.="
		</table>
		<input type='hidden' name='form_actif' value='1'>
		<input class='bouton' type='submit' value='". $msg["visionneuse_admin_save"] ."' />
	</form>	
	";
	
	print $form;
}

function update_mimetypeConf() {
	global $mimetypeConf;
	global $class_param;
	global $charset;
	
	foreach ($_POST as $key => $value){
		//pour les mimetypes
		if($key != "form_actif"){
			//si la valeur existe, on doit mettre a jour
			if(isset($mimetypeConf[$key])){
				//on évite une requete de mise à jour sans modification...
				if($value != $mimetypeConf[$key]){
					//$rqt = "UPDATE visionneuse SET visionneuse_class = '$value' WHERE visionneuse_mimetype LIKE '$key'";
					$mimetypeConf[$key] = $value;
				}
			//sinon on crée l'entrée en base...
			}else{
				//$rqt = "INSERT INTO visionneuse SET visionneuse_mimetype = '$key',visionneuse_class = '$value'";
				$mimetypeConf[$key] = $value;
			}
		}
	}
	$rqt ="UPDATE parametres SET valeur_param = '".htmlspecialchars(addslashes(serialize($mimetypeConf)),ENT_QUOTES,$charset)."' WHERE type_param LIKE 'opac' AND sstype_param LIKE 'visionneuse_params' ";
	$res = mysql_query($rqt);
}