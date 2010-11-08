<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: req.inc.php,v 1.2 2009-06-25 16:33:22 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ($class_path.'/request.class.php');  
require_once ($class_path.'/requester.class.php');
require_once ($include_path.'/templates/requests.tpl.php');


//Traitement des données
//TODO traitement pour conversion en UTF8 
$req_datas=$_POST;

$rqt = new requester();

switch ($fname) {
	case 'buildRequest':
		$request=$rqt->buildRequest($req_type,$req_univ,$req_nb_lines,$req_datas);
		ajax_http_send_response($request);
		break;
	case 'getAttributes':
		if($c_type!="FI") $c_type='';
		$attr=$rqt->getAttributes($fct_id,$c_type);
		ajax_http_send_response($attr,"text/xml");
		break;
	default:
		break;
}

?>