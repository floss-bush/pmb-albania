<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Yves PRATTER                                                   |
// +-------------------------------------------------+
// $Id: explnum_doc_data.php,v 1.2 2009-11-04 14:37:54 kantin Exp $

// définition du minimum nécéssaire 
$base_path     = ".";                            
$base_auth     = ""; //"CIRCULATION_AUTH";  
$base_title    = "";    
$base_noheader = 1;
$base_nocheck  = 1;
$base_nobody   = 1;
$base_nosession   = 1;
require_once ("$base_path/includes/init.inc.php");  

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