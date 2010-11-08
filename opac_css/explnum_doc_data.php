<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: explnum_doc_data.php,v 1.2 2009-11-24 16:06:24 kantin Exp $

$base_path=".";
require_once($base_path."/includes/init.inc.php");

require_once($base_path."/includes/error_report.inc.php") ;

//Sessions !! Attention, ce doit être impérativement le premer include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");
require_once($base_path."/includes/global_vars.inc.php");
require_once($base_path."/includes/opac_config.inc.php");

if ($css=="") $css=1;

// récupération paramètres MySQL et connection á la base
require_once($base_path."/includes/opac_db_param.inc.php");
require_once($base_path."/includes/opac_mysql_connect.inc.php");
$dbh = connection_mysql();

require_once($base_path."/includes/start.inc.php");

require_once($base_path."/includes/check_session_time.inc.php");

// récupération localisation
require_once($base_path."/includes/localisation.inc.php");

// version actuelle de l'opac
require_once($base_path."/includes/opac_version.inc.php");

$resultat = mysql_query("SELECT explnum_doc_nomfichier, explnum_doc_mimetype, explnum_doc_data, explnum_doc_extfichier
			FROM explnum_doc WHERE id_explnum_doc = '$explnumdoc_id' ", $dbh);
$nb_res = mysql_num_rows($resultat) ;

if (!$nb_res) {
	exit ;
	} 
	
$ligne = mysql_fetch_object($resultat);
if ($ligne->explnum_doc_data) {
	header("Content-Type: ".$ligne->explnum_doc_mimetype);
	header("Content-Length: ".$ligne->taille);
	print $ligne->explnum_doc_data;
	exit ;
} else print "ERROR".mysql_error() ;
?>