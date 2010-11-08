<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: self_checkin.inc.php,v 1.3 2010-08-19 13:54:00 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path.'/includes/templates/self_checkout.tpl.php');
require_once($class_path.'/pmb_jsonrpc_client.inc');
//script du selfsevice de retour

print $form_self_checkin;
if($cb_expl){
	
	$rpc=new pmb_jsonrpc_client($opac_self_checkout_url_connector);
	
/*	
	$rpc->setAuthenticationType('http');
	$rpc->setCredentials($login, $password);

*/	
	$result = $rpc->pmbesSelfServices_self_checkin($cb_expl);	
	if ($charset!= "utf-8") {
		$result->title=utf8_decode($result->title);
		$result->message=utf8_decode($result->message);
		$result->message_loc=utf8_decode($result->message_loc);
		$result->message_resa=utf8_decode($result->message_resa);
		$result->message_retard=utf8_decode($result->message_retard);
		$result->message_amende=utf8_decode($result->message_amende);
	}
	
	if($result->status){
		// retour ok
		$aff=str_replace('!!expl_cb!!',"'$cb_expl'",  $msg["empr_do_checkin_ok"]);
		$aff="<br />".$aff."<br />";
	} else {
		$aff="<br />".$msg["empr_do_checkin_nok"]."<br />";
	}
//	if($result->title) $aff.="<h2>".$result->title."</h2>";
	if($result->message) $aff.="<span>".$result->message."</span><br />";
	if($result->message_loc) $aff.="<span>".$result->message_loc."</span><br />";
	if($result->message_resa) $aff.="<span>".$result->message_resa."</span><br />";
	if($result->message_retard) $aff.="<span>".$result->message_retard."</span><br />";
	if($result->message_amende) $aff.="<span>".$result->message_amende."</span><br />";
	print $aff;
	
	$requete="select expl_notice, expl_bulletin from exemplaires where expl_cb ='$cb_expl'";
	$resultat=mysql_query($requete);
	$prix=0;
	if($r=mysql_fetch_object($resultat)) {
		$current = new notice_affichage($r->expl_notice+$r->expl_bulletin,0,1);
		$current->do_header();
		$current->do_isbd();
		$current->do_public();
		$current->genere_double(0,"public");
		$output_final .= $current->result;			
		print "<br />".$output_final;
	}
}
?>