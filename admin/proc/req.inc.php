<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: req.inc.php,v 1.1 2008-03-19 11:48:25 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ($class_path.'/request.class.php');  
require_once ($class_path.'/requester.class.php');
require_once ($include_path.'/templates/requests.tpl.php');

$rqt = new requester();

function show_req_add_form($step=0) {
	
	global $dbh, $msg, $charset;
	global $rqt;
	global $req_add_form;
	global $bt_enr_form;
	
	$form_title = $msg['req_form_tit_add'];

	switch($step) {
		default :
		case '0' :
			$req_add_form = str_replace('!!form_title!!', $form_title, $req_add_form);

			$num_classement=0;
			$combo_clas= gen_liste ("SELECT idproc_classement,libproc_classement FROM procs_classements ORDER BY libproc_classement ", "idproc_classement", "libproc_classement", "form_classement", "", $num_classement, 0, $msg['proc_clas_aucun'],0, $msg['proc_clas_aucun']) ;
			$req_add_form = str_replace('!!classement!!', $combo_clas, $req_add_form);
			
			$req_add_form = str_replace('!!req_name!!', '', $req_add_form);
			$req_add_form = str_replace('!!req_type!!',$rqt->getTypeSelector('1','req_typeChg();'), $req_add_form);
			$req_add_form = str_replace('!!req_univ!!',$rqt->getUnivSelector('1','req_univChg();'), $req_add_form);
			$req_add_form = str_replace('!!req_comm!!','', $req_add_form);
			$req_add_form = str_replace('!!req_code!!','', $req_add_form);
			$req_add_form = str_replace('!!req_auth!!', request::getAutorisationsForm(), $req_add_form);
			break;
	}

	print $req_add_form; 	
}

//Verification de la presence et de la syntaxe des parametres de la requete
//retourne true si OK, sinon retourne le nom du parametre entre parentheses
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


//traitement des actions
switch($action) {
	
	case 'add':
		show_req_add_form();
		break;

	case 'modif':
		break;

	case 'update':
		if($req_name && $req_code) {
			$requete = "SELECT count(1) FROM procs WHERE name='".$req_name."' ";
			$res = mysql_query($requete, $dbh);
			$nbr_lignes = mysql_result($res, 0, 0);
			if(!$nbr_lignes) {
				if (is_array($user_aut)) { 
					$autorisations=implode(" ",$user_aut);
				} else {
					$autorisations='';
				}
				$param_name=check_param($req_code);
				if ($param_name!==true) {
					error_message_history($param_name, sprintf($msg['proc_param_check_field_name'],$param_name), 1);
					exit();
				}
				$requete = "INSERT INTO procs (idproc,name,requete,comment,autorisations,num_classement) VALUES ('', '$req_name', '$req_code', '$req_comm', '$autorisations', '$form_classement'  ) ";
				$res = mysql_query($requete, $dbh);
			} else {
				print "<script language='Javascript'>alert(\"".addslashes($msg[709])."\");</script>";
			}
			print "<script type='text/javascript'> document.location='./admin.php?categ=proc&sub=proc&action='</script>";
		}
		break;

		
	case 'del':
		break;

	case 'list':
	default:
		break;
}

?>