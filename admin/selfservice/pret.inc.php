<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pret.inc.php,v 1.2 2010-03-16 14:25:22 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion du paramétrage de la borne de prêt

function show_param($action='') {
	global $msg,$charset,$pmb_transferts_actif;
	global $selfservice_pret_carte_invalide_msg;
	global $selfservice_pret_pret_interdit_msg;
	global $selfservice_pret_deja_prete_msg;
	global $selfservice_pret_deja_reserve_msg;
	global $selfservice_pret_quota_bloc_msg;
	global $selfservice_pret_non_pretable_msg;
	global $selfservice_pret_expl_inconnu_msg;
	global $selfservice_pret_prolonge_non_msg;
	
	if($action=="update") $message="<div class='erreur'>".$msg["selfservice_admin_update"]."</div>";
	
	print "
	$message
	<form class='form-admin' name='modifParam' method='post' action='./admin.php?categ=selfservice&sub=pret&action=update'>
	<h3>".$msg["selfservice_admin_pret"]."</h3>
	<div class='form-contenu'>		
		<table>
			<tr>
				<th>".$msg["selfservice_param_tab"]."</th>
				<th>".$msg["selfservice_param_action"]."</th>
				<th>".$msg["selfservice_param_message"]."</th>
			</tr>		
		    <tr class='odd'>
				<td>
					".$msg["selfservice_admin_pret_carte_invalide"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='pret_carte_invalide_msg' value='".htmlentities($selfservice_pret_carte_invalide_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>
		    <tr class='even'>
				<td>
					".$msg["selfservice_admin_pret_pret_interdit"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='pret_pret_interdit_msg' value='".htmlentities($selfservice_pret_pret_interdit_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>
		    <tr class='odd'>
				<td>
					".$msg["selfservice_admin_pret_deja_prete"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='pret_deja_prete_msg' value='".htmlentities($selfservice_pret_deja_prete_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>
		    <tr class='even'>
				<td>
					".$msg["selfservice_admin_pret_deja_reserve"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='pret_deja_reserve_msg' value='".htmlentities($selfservice_pret_deja_reserve_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>
		    <tr class='odd'>
				<td>
					".$msg["selfservice_admin_pret_quota_bloc"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='pret_quota_bloc_msg' value='".htmlentities($selfservice_pret_quota_bloc_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>
		    <tr class='even'>
				<td>
					".$msg["selfservice_admin_pret_non_pretable"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='pret_non_pretable_msg' value='".htmlentities($selfservice_pret_non_pretable_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
				</td>   
		    </tr>
		    <tr class='odd'>
				<td>
					".$msg["selfservice_admin_pret_expl_inconnu"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='pret_expl_inconnu_msg' value='".htmlentities($selfservice_pret_expl_inconnu_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
			</td> 
		    <tr>
		    	<th colspan='3'>".$msg["selfservice_param_prolong"]."</th>
		    </tr>
		    <tr class='even'>
				<td>
					".$msg["selfservice_admin_pret_prolonge_non"]."
				</td>  
				<td>					
				</td>
				<td>
					<input name='pret_prolonge_non_msg' value='".htmlentities($selfservice_pret_prolonge_non_msg, ENT_QUOTES, $charset)."' size='40' type='text'>
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
	global $selfservice_pret_carte_invalide_msg, $pret_carte_invalide_msg;
	global $selfservice_pret_pret_interdit_msg, $pret_pret_interdit_msg;
	global $selfservice_pret_deja_prete_msg, $pret_deja_prete_msg;
	global $selfservice_pret_deja_reserve_msg, $pret_deja_reserve_msg;
	global $selfservice_pret_quota_bloc_msg, $pret_quota_bloc_msg;
	global $selfservice_pret_non_pretable_msg, $pret_non_pretable_msg;
	global $selfservice_pret_expl_inconnu_msg, $pret_expl_inconnu_msg;
	global $selfservice_pret_prolonge_non_msg, $pret_prolonge_non_msg;
	
	
	$rqt = "UPDATE parametres SET valeur_param ='$pret_carte_invalide_msg' where type_param= 'selfservice' and sstype_param='pret_carte_invalide_msg' ";
	$selfservice_pret_carte_invalide_msg=stripslashes($pret_carte_invalide_msg);
	mysql_query($rqt);


	$rqt = "UPDATE parametres SET valeur_param ='$pret_pret_interdit_msg' where type_param= 'selfservice' and sstype_param='pret_pret_interdit_msg' ";
	$selfservice_pret_pret_interdit_msg=stripslashes($pret_pret_interdit_msg);
	mysql_query($rqt);


	$rqt = "UPDATE parametres SET valeur_param ='$pret_deja_prete_msg' where type_param= 'selfservice' and sstype_param='pret_deja_prete_msg' ";
	$selfservice_pret_deja_prete_msg=stripslashes($pret_deja_prete_msg);
	mysql_query($rqt);


	$rqt = "UPDATE parametres SET valeur_param ='$pret_deja_reserve_msg' where type_param= 'selfservice' and sstype_param='pret_deja_reserve_msg' ";
	$selfservice_pret_deja_reserve_msg=stripslashes($pret_deja_reserve_msg);
	mysql_query($rqt);


	$rqt = "UPDATE parametres SET valeur_param ='$pret_quota_bloc_msg' where type_param= 'selfservice' and sstype_param='pret_quota_bloc_msg' ";
	$selfservice_pret_quota_bloc_msg=stripslashes($pret_quota_bloc_msg);
	mysql_query($rqt);


	$rqt = "UPDATE parametres SET valeur_param ='$pret_non_pretable_msg' where type_param= 'selfservice' and sstype_param='pret_non_pretable_msg' ";
	$selfservice_pret_non_pretable_msg=stripslashes($pret_non_pretable_msg);
	mysql_query($rqt);


	$rqt = "UPDATE parametres SET valeur_param ='$pret_expl_inconnu_msg' where type_param= 'selfservice' and sstype_param='pret_expl_inconnu_msg' ";
	$selfservice_pret_expl_inconnu_msg=stripslashes($pret_expl_inconnu_msg);
	mysql_query($rqt);


	$rqt = "UPDATE parametres SET valeur_param ='$pret_prolonge_non_msg' where type_param= 'selfservice' and sstype_param='pret_prolonge_non_msg' ";
	$selfservice_pret_prolonge_non_msg=stripslashes($pret_prolonge_non_msg);
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
