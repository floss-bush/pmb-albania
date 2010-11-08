<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: diffusion_auto.php,v 1.3 2010-04-29 08:52:27 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$base_path = "../.";
$base_auth = "DSI_AUTH";  
$base_noheader = 1;
$base_nobody = 1;
$clean_pret_tmp=1;

require_once($base_path."/includes/init.inc.php");
require_once($class_path."/bannette.class.php");

diff_all_bannettes_full_auto();

function diff_all_bannettes_full_auto() {
	global $dbh;
	global $status_diffusion;	
	$status_diffusion=array();
	$requete = "SELECT id_bannette, proprio_bannette FROM bannettes WHERE (DATE_ADD(date_last_envoi, INTERVAL periodicite DAY) <= sysdate()) and bannette_auto=1 ";
	$res = mysql_query($requete, $dbh);
	print "<table>";		
	while(($bann=mysql_fetch_object($res))) {
		$bannette = new bannette($bann->id_bannette);
		if(!$bannette->limite_type)$bannette->vider();
		$bannette->remplir();
		$bannette->purger();
		print"<tr>";
		print"<td>".$bannette->nom_bannette."</td>";
		print"<td>".$bannette->aff_date_last_envoi."</td>";
		print"<td>".$bannette->diffuser()."</td>";
		print"</tr>";		
		
	}	
	print"</table>";
	
}
