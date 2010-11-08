<?php
if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/suggestions_origine.class.php");

switch($quoifaire){
	
	case 'ajout_origine':
		add_origine();
		break;
}

function add_origine(){
	global $id_sugg,$orig, $type_orig,$dbh, $msg;
	
	
	$sug_ori = new suggestions_origine($orig,$id_sugg);
	if($sug_ori){
		if($type_orig) $sug_ori->type_origine = $type_orig;		
		$sug_ori->save();
	}
	$list_user = "";
	$req_select = "select origine, type_origine from suggestions_origine where num_suggestion='".$id_sugg."' ";
	$res = mysql_query($req_select,$dbh);
	$nb_user = 0;
	while(($user = mysql_fetch_object($res))){
		switch($user->type_origine){
			default:
				case '0' :
				 	$requete_user = "SELECT userid, nom, prenom FROM users where userid = '".$user->origine."'";
					$res_user = mysql_query($requete_user, $dbh);
					$row_user=mysql_fetch_row($res_user);
					$lib_orig = $row_user[1];
					if ($row_user[2]) $lib_orig.= ", ".$row_user[2];					
					if(empty($premier_user) || !isset($premier_user)) $premier_user = $lib_orig;
					else $list_user .= $lib_orig."<br />";
					break;
				case '1' :
				 	$requete_empr = "SELECT id_empr, empr_nom, empr_prenom FROM empr where id_empr = '".$user->origine."'";
					$res_empr = mysql_query($requete_empr, $dbh);
					$row_empr=mysql_fetch_row($res_empr);
					$lib_orig = $row_empr[1];
					if ($row_empr[2]) $lib_orig.= ", ".$row_empr[2];
					if(empty($premier_user) || !isset($premier_user)) $premier_user = $lib_orig;
					else $list_user .= $lib_orig."<br />";
					break;
				case '2' :
					if($user->origin) $lib_orig = $user->origin;
					else $lib_orig = $msg['suggest_anonyme'];
					if(empty($premier_user) || !isset($premier_user)) $premier_user = $lib_orig;
					else $list_user .= $lib_orig."<br />";
					break;
		}
		$nb_user++;
	}
	$ajout_create = "
		<input type='text' id='creator_lib_orig_ajax' name='creator_lib_orig' class='saisie-10emr'/>
		<input type='button' id='creator_btn_orig_ajax' class='bouton_small' value='...' onclick=\"openPopUp('./select.php?what=origine&caller=sug_modif_form&param1=orig&param2=creator_lib_orig_ajax&param3=typ&param4=&param5=&param6=&callback=ajax_origine&deb_rech='+document.getElementById('creator_lib_orig_ajax').value, 'select_creator_orig', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />";
	$list_user .= $ajout_create;
	  
	if(mysql_num_rows($res) > 1){
		$result = gen_plus('ori_ajax',$msg['suggest_creator']. " (".($nb_user-1).")",$list_user,0);
	} else if(mysql_num_rows($res) == 1){
		$result = $list_user;
	}
	
	ajax_http_send_response($result);
}
?>