<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: retour.inc.php,v 1.2 2010-03-16 14:25:22 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion du paramétrage de la borne de prêt

function show_param($action='') {
	global $msg,$charset,$pmb_transferts_actif;
	global $selfservice_loc_autre_todo,$selfservice_resa_ici_todo,$selfservice_resa_loc_todo;
	global $selfservice_loc_autre_todo_msg,$selfservice_resa_ici_todo_msg,$selfservice_resa_loc_todo_msg;
	global $selfservice_resa_ici_todo_msg,$selfservice_resa_loc_todo_msg;
	global $selfservice_retour_retard_msg,$selfservice_retour_blocage_msg,$selfservice_retour_amende_msg;
	
	$loc_autre_todo_selected[$selfservice_loc_autre_todo]="selected";
	$resa_ici_todo_selected[$selfservice_resa_ici_todo]="selected";	
	$resa_loc_todo_selected[$selfservice_resa_loc_todo]="selected";
	if($action=="update") $message="<div class='erreur'>".$msg["selfservice_admin_update"]."</div>";
	
	print "
	$message
	<form class='form-admin' name='modifParam' method='post' action='./admin.php?categ=selfservice&sub=retour&action=update'>
	<h3>".$msg["selfservice_admin_retour"]."</h3>
	<div class='form-contenu'>		
		<table>
			<tr>
				<th>".$msg["selfservice_param_tab"]."</th>
				<th>".$msg["selfservice_param_action"]."</th>
				<th>".$msg["selfservice_param_message"]."</th>
			</tr>
			<tr class='even'>
				<td>
					".$msg["selfservice_loc_autre_todo"]."
				</td>  
				<td>
					<select id='loc_autre_todo' name='loc_autre_todo'>
				        <option value='0' $loc_autre_todo_selected[0]>".$msg["selfservice_loc_autre_todo_plus_tard"]."</option>					
				        ".
				        ($pmb_transferts_actif?"
			        	<option value='1' $loc_autre_todo_selected[1]>".$msg["selfservice_loc_autre_todo_gen_trans"]."</option>
			        	<option value='2' $loc_autre_todo_selected[2]>".$msg["selfservice_loc_autre_todo_catalog"]."</option>"
				        :"")."
				        <option value='3' $loc_autre_todo_selected[3]>".$msg["selfservice_loc_autre_todo_catalog_noloc"]."</option>
						<option value='4' $loc_autre_todo_selected[4]>".$msg["selfservice_loc_autre_todo_refus"]."</option>
				    </select>
				</td>
				<td>
					<input name='loc_autre_todo_msg' value='".htmlentities($selfservice_loc_autre_todo_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td> 
			</tr>
			<tr class='odd'>
				<td>
					".$msg["selfservice_resa_ici_todo"]."
				</td>  
				<td>
					<select id='resa_ici_todo' name='resa_ici_todo'>				        
				        <option value='0' $resa_ici_todo_selected[0]>".$msg["selfservice_resa_ici_todo_plus_tard"]."</option>
				        <option value='1' $resa_ici_todo_selected[1]>".$msg["selfservice_resa_ici_todo_valid_resa"]."</option>
				    </select>
				</td>
				<td>
					<input name='resa_ici_todo_msg' value='".htmlentities($selfservice_resa_ici_todo_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>
			<tr class='even'>
				<td>
					".$msg["selfservice_resa_loc_todo"]."
				</td>  
				<td>
					<select id='resa_loc_todo' name='resa_loc_todo'>
						<option value='0' $resa_loc_todo_selected[0]>".$msg["selfservice_resa_loc_todo_plus_tard"]."</option>".
						($pmb_transferts_actif?"
				        <option value='1' $resa_loc_todo_selected[1]>".$msg["selfservice_resa_loc_todo_gen_trans"]."</option>"
				       	:"")."  
				        <option value='2' $resa_loc_todo_selected[2]>".$msg["selfservice_resa_ici_todo_valid_resa"]."</option>				       
				        
				    </select>
				</td>
				<td>
					<input name='resa_loc_todo_msg' value='".htmlentities($selfservice_resa_loc_todo_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>  
			<tr class='odd'>
				<td>
					".$msg["selfservice_admin_retour_retard"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='retour_retard_msg' value='".htmlentities($selfservice_retour_retard_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>			
		    <tr class='even'>
				<td>
					".$msg["selfservice_admin_retour_blocage"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='retour_blocage_msg' value='".htmlentities($selfservice_retour_blocage_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>			
		    <tr class='odd'>
				<td>
					".$msg["selfservice_admin_retour_amende"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='retour_amende_msg' value='".htmlentities($selfservice_retour_amende_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>
		</table>
	</div>
		<input type='hidden' name='form_actif' value='1'>
		<input class='bouton' type='submit' value='". $msg["selfservice_admin_save"] ."' />
	</form>	
	";
}

function memo_param() {
	global $loc_autre_todo,$resa_ici_todo,$resa_loc_todo;
	global $retour_retard_msg,$retour_blocage_msg,$retour_amende_msg;
	global $loc_autre_todo_msg,$resa_ici_todo_msg,$resa_loc_todo_msg;
	global $selfservice_loc_autre_todo,$selfservice_resa_ici_todo,$selfservice_resa_loc_todo;
	global $selfservice_loc_autre_todo_msg,$selfservice_resa_ici_todo_msg,$selfservice_resa_loc_todo_msg;
	global $selfservice_retour_retard_msg,$selfservice_retour_blocage_msg,$selfservice_retour_amende_msg;
	$rqt = "UPDATE parametres SET valeur_param ='$loc_autre_todo' where type_param= 'selfservice' and sstype_param='loc_autre_todo' ";
	$selfservice_loc_autre_todo=$loc_autre_todo;
	mysql_query($rqt);
	
	$rqt = "UPDATE parametres SET valeur_param ='$resa_ici_todo' where type_param= 'selfservice' and sstype_param='resa_ici_todo' ";
	$selfservice_resa_ici_todo=stripslashes($resa_ici_todo);
	mysql_query($rqt);	
	
	$rqt = "UPDATE parametres SET valeur_param ='$resa_loc_todo' where type_param= 'selfservice' and sstype_param='resa_loc_todo' ";
	$selfservice_resa_loc_todo=stripslashes($resa_loc_todo);
	mysql_query($rqt);
	
	$rqt = "UPDATE parametres SET valeur_param ='$loc_autre_todo_msg' where type_param= 'selfservice' and sstype_param='loc_autre_todo_msg' ";
	$selfservice_loc_autre_todo_msg=stripslashes($loc_autre_todo_msg);
	mysql_query($rqt);	
	
	$rqt = "UPDATE parametres SET valeur_param ='$resa_ici_todo_msg' where type_param= 'selfservice' and sstype_param='resa_ici_todo_msg' ";
	$selfservice_resa_ici_todo_msg=stripslashes($resa_ici_todo_msg);
	mysql_query($rqt);
	
	$rqt = "UPDATE parametres SET valeur_param ='$resa_loc_todo_msg' where type_param= 'selfservice' and sstype_param='resa_loc_todo_msg' ";
	$selfservice_resa_loc_todo_msg=stripslashes($resa_loc_todo_msg);
	mysql_query($rqt);

	$rqt = "UPDATE parametres SET valeur_param ='$retour_retard_msg' where type_param= 'selfservice' and sstype_param='retour_retard_msg' ";
	$selfservice_retour_retard_msg=stripslashes($retour_retard_msg);
	mysql_query($rqt);
	
	$rqt = "UPDATE parametres SET valeur_param ='$retour_blocage_msg' where type_param= 'selfservice' and sstype_param='retour_blocage_msg' ";
	$selfservice_retour_blocage_msg=stripslashes($retour_blocage_msg);
	mysql_query($rqt);

	$rqt = "UPDATE parametres SET valeur_param ='$retour_amende_msg' where type_param= 'selfservice' and sstype_param='retour_amende_msg' ";
	$selfservice_retour_amende_msg=stripslashes($retour_amende_msg);
	mysql_query($rqt);
	
}

switch($action) {
	case 'update':
		if($form_actif) memo_param();
		show_param($action);
	break;
	default:
		show_param();
		break;
}
