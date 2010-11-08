<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: vig_num.php,v 1.6 2010-04-14 09:20:43 erwanmartin Exp $

// définition du minimum nécéssaire 
$base_path     = ".";                            
$base_auth     = ""; //"CIRCULATION_AUTH";  
$base_title    = "";    
$base_noheader = 1;
$base_nocheck  = 1;
$base_nobody   = 1;
require_once ("$base_path/includes/init.inc.php");  
require_once("$class_path/curl.class.php");

$resultat = mysql_query("SELECT explnum_id, explnum_mimetype, explnum_vignette FROM explnum WHERE explnum_id = '$explnum_id' ", $dbh);
$nb_res = mysql_num_rows($resultat) ;

if (!$nb_res) {
	exit ;
	} 

$ligne = mysql_fetch_object($resultat);
if ($ligne->explnum_vignette) {
	print $ligne->explnum_vignette;
	exit ;
	} else {
		if ($curl_available) {
			$image_url = 'http';
 			if ($_SERVER["HTTPS"] == "on") {$image_url .= "s";}
 			$image_url .= "://";
			$image_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].dirname($_SERVER["SCRIPT_NAME"]).'/images/mimetype/unknown.gif';
			$aCurl = new Curl();
			$content = $aCurl->get($image_url);
			$contenu_vignette = $content->body;
		}
		else {
			$fp = fopen("./images/mimetype/unknown.gif" , "r" ) ;
			$contenu_vignette = fread ($fp, filesize("./images/mimetype/unknown.gif"));
			fclose ($fp) ;			
		}		
		print $contenu_vignette ;
		}
