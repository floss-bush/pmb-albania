<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: dosync.php,v 1.6 2009-11-06 17:39:08 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/connecteurs.class.php");

set_time_limit(0);

function show_progress($percent,$nlu,$ntotal) {
	global $charset;
	global $source_id;
	print "pb = document.getElementById('progress_bar'); pb.removeAttribute('width'); pb.setAttribute('width',pb.width*".(round($percent*100)?round($percent*100):"100").");
		document.getElementById('percent').innerHTML='".round($percent*100)."%';
		document.getElementById('nlu').innerHTML='".htmlentities($nlu,ENT_QUOTES,$charset)."';
		document.getElementById('ntotal').innerHTML='".htmlentities($ntotal,ENT_QUOTES,$charset)."';";
	$requete="update source_sync set percent=".round($percent*100)." where source_id=$source_id";
	$r=mysql_query($requete);
    ob_flush();
    flush();
}

function return_error($error_message) {
	$result = "var erreur_div = document.createElement('div');
				erreur_div.setAttribute('class', 'erreur');
				erreur_div.setAttribute('id', 'red_erreur_message');
				erreur_div.innerHTML = 'Erreur: ".addslashes($error_message)."';
				if (document.getElementById('red_erreur_message'))
					document.getElementById('erreurpos').removeChild(document.getElementById('red_erreur_message'));
				document.getElementById('erreurpos').appendChild(erreur_div);
	";
	echo $result;
	die();
}

if (isset($env)) {
	$env = stripslashes($env);
	$tenv = unserialize($env);
	if (is_array($tenv)) {
		foreach ($tenv as $aenv=>$aenvv) {
			$$aenv = $aenvv;
		}
	}
}

if ($id) {
	$contrs=new connecteurs();
	require_once($base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."/".$contrs->catalog[$id]["NAME"].".class.php");
	eval("\$conn=new ".$contrs->catalog[$id]["NAME"]."(\"".$base_path."/admin/connecteurs/in/".$contrs->catalog[$id]["PATH"]."\");");
}
else 
	return_error("Missing ID!");

if (!$source_id)
	return_error("Missing source ID!");

//Traitement en cas de requete d'annulation de synchro
if ($cancel) {
	if ($conn->cancel_maj($source_id)) {
		$requete="delete from source_sync where source_id=".$source_id;
	} else {
		$requete="update source_sync set cancel=1 where source_id=".$source_id;
	}
	mysql_query($requete);
	die();
}
	
$conn->get_sources();
//Vérification qu'il n'y a pas de synchronisation en cours...
$is_already_sync=false;
$recover_env="";
$recover=false;
$requete="select * from source_sync where source_id=$source_id";
$resultat=mysql_query($requete);
if (mysql_num_rows($resultat)) {
	$rs_s=mysql_fetch_object($resultat);
	if (!$rs_s->cancel) {
		return_error($conn->msg["connecteurs_sync_currentexists"]);
	} else {
		$recover=true;
		$recover_env=$rs_s->env;
	}
}

flush();
ob_flush();

error_reporting(E_ALL);
ini_set('display_errors', 0);

function shutdown(){
    $isError = false;
    if ($error = error_get_last()){
        switch($error['type']){
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $isError = true;
                break;
        }
    }

    if ($isError){
    	return_error($error['message']);
    }
}

register_shutdown_function('shutdown');

if (!$is_already_sync) {
	if (!$recover) {
		$requete="insert into source_sync (source_id,nrecu,ntotal,date_sync) values($source_id,0,0,now())";
		$r=mysql_query($requete);
	} 
	else {
		$requete="update source_sync set cancel=0 where source_id=$source_id";
		$r=mysql_query($requete);
	}
	if ($r) {
		$n_maj=$conn->maj_entrepot($source_id,"show_progress",$recover,$recover_env);
		if (!$conn->error) {
			show_progress(1,$n_maj,$n_maj);
			print "document.getElementById('cancel_sync').style.visibility='hidden';";
			print "document.getElementById('get_back').style.visibility='visible';";
			print "document.getElementById('sync_message').innerHTML='".htmlentities($msg["connecteurs_sync_syncover"] ,ENT_QUOTES, $charset)."'";
			$requete="delete from source_sync where source_id=".$source_id;
			mysql_query($requete);
			$requete="update connectors_sources set last_sync_date=now() where source_id=".$source_id;
			mysql_query($requete);
		} else {
			if ($conn->break_maj($source_id)) {
				$requete="delete from source_sync where source_id=".$source_id;
			} else {
				$requete="update source_sync set cancel=2 where source_id=".$source_id;
			}
			mysql_query($requete);
			return_error($conn->error_message);
		}
	} else return_error($msg["connecteurs_sync_currentexists"]);
}


?>