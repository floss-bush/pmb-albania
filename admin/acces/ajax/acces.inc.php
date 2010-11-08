<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: acces.inc.php,v 1.2 2009-07-28 17:01:08 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//droits d'acces actives
if ($gestion_acces_active==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
}


//droits d'acces utilisateur/notice
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1 && $dom_id==1) {
	$dom= $ac->setDomain(1);
}

//droits d'acces emprunteur/notice
if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1 && $dom_id==2) {
	$dom= $ac->setDomain(2);
}

if (is_object($dom)) {
	switch ($fname) {
		case 'getNbResourcesToUpdate':
			$nb=$dom->getNbResourcesToUpdate();
			ajax_http_send_response($nb);
			break;
		case 'updateRessources' :
			if(!$nb_done) $nb_done=0;
			$nb=$dom->applyDomainRights($nb_done,$chk_sav_spe_rights);
			ajax_http_send_response($nb);
			break;
		case 'cleanResources' :
			$dom->cleanResources();
			ajax_http_send_response('done');
		default:
			break;
	}
}
?>