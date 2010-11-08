<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.php,v 1.3 2007-07-14 10:08:55 touraine37 Exp $

// définition du minimum nécéssaire 
$base_path=".";                            
$base_auth = "ADMINISTRATION_AUTH|CATALOGAGE_AUTH";  
$base_title = "";
$base_noheader=1;
$base_nosession=1;
error_reporting (E_ERROR | E_PARSE | E_WARNING);

require_once ("$base_path/includes/init.inc.php");  

switch($quoi) {
	// Export de procédures
	case "procs":
		switch($sub) {
			case "caddie" :
				header("Content-Type: application/download\n");
				header("Content-Disposition: atachement; filename=\"caddie_proc_".$id.".sql\"");
				
				$req="select type, name, requete, comment, autorisations, parameters from caddie_procs where idproc='$id' ";
				$res = mysql_query($req,$dbh);
				if ($p=mysql_fetch_object($res)) {
					$exp="INSERT INTO caddie_procs set type='".addslashes($p->type)."', name='".addslashes($p->name)."', requete='".addslashes($p->requete)."', comment='".addslashes($p->comment)."', autorisations='1', parameters='".addslashes($p->parameters)."' ";
					echo $exp ;
					}			
				break;
			case "empr_caddie" :
				header("Content-Type: application/download\n");
				header("Content-Disposition: atachement; filename=\"empr_caddie_proc_".$id.".sql\"");
				
				$req="select type, name, requete, comment, autorisations, parameters from empr_caddie_procs where idproc='$id' ";
				$res = mysql_query($req,$dbh);
				if ($p=mysql_fetch_object($res)) {
					$exp="INSERT INTO empr_caddie_procs set type='".addslashes($p->type)."', name='".addslashes($p->name)."', requete='".addslashes($p->requete)."', comment='".addslashes($p->comment)."', autorisations='1', parameters='".addslashes($p->parameters)."' ";
					echo $exp ;
					}			
				break;
			case "actionsperso" :
				header("Content-Type: application/download\n");
				header("Content-Disposition: atachement; filename=\"admin_proc_".$id.".sql\"");
				
				$req="select name, requete, comment, autorisations, parameters from procs where idproc='$id' ";
				$res = mysql_query($req,$dbh);
				if ($p=mysql_fetch_object($res)) {
					$exp="INSERT INTO procs set name='".addslashes($p->name)."', requete='".addslashes($p->requete)."', comment='".addslashes($p->comment)."', autorisations='1', parameters='".addslashes($p->parameters)."' ";
					echo $exp ;
					}			
				break;
				
			}
		break;
	}
	
mysql_close($dbh);
