<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: vign_middle.php,v 1.6 2010-07-02 08:15:17 arenou Exp $

$base_path=".";
require_once($base_path."/includes/init.inc.php");

// définition du minimum nécéssaire 
require_once($base_path."/includes/error_report.inc.php") ;

//Sessions !! Attention, ce doit être impérativement le premer include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");
require_once($base_path."/includes/global_vars.inc.php");
require_once($base_path.'/includes/opac_config.inc.php');

// récupération paramètres MySQL et connection á la base
require_once($base_path.'/includes/opac_db_param.inc.php');
require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

require_once($base_path.'/includes/start.inc.php');

require_once($base_path."/includes/check_session_time.inc.php");

// récupération localisation
require_once($base_path.'/includes/localisation.inc.php');

// version actuelle de l'opac
require_once($base_path.'/includes/opac_version.inc.php');

//Fonctions exemplaires numériques
require_once($include_path."/explnum.inc.php");


$resultat = mysql_query("SELECT explnum_id, explnum_mimetype, explnum_data, explnum_nom as nom, explnum_repertoire, explnum_path, explnum_nomfichier FROM explnum WHERE explnum_id = '$explnum_id' ", $dbh);
$nb_res = mysql_num_rows($resultat) ;

if (!$nb_res) {
	exit ;
} 

$ligne = mysql_fetch_object($resultat);
if ($ligne->explnum_data) {
	if($ligne->explnum_mimetype == 'application/pdf'){
		$contenu_vignette = $ligne->explnum_data;
		header('Content-type: application/pdf');
		print $contenu_vignette;
	} else $contenu_vignette=reduire_image_middle($ligne->explnum_data);
	if ($contenu_vignette) {
		header('Content-type: image/png');		
		print $contenu_vignette;
	} else {
			$fp = fopen("./images/mimetype/unknown.gif" , "r" ) ;
			$contenu_vignette = fread ($fp, filesize("./images/mimetype/unknown.gif"));
			fclose ($fp) ;
			header('Content-type: image/gif');
			print $contenu_vignette ;
	}
} elseif($ligne->explnum_repertoire != 0){
	$req="select repertoire_path from upload_repertoire where repertoire_id='".$ligne->explnum_repertoire."'";
	$res=mysql_query($req,$dbh);
	if(mysql_num_rows($res))
		$rep_upload_path =mysql_result($res,0,0);
	
	if($ligne->explnum_mimetype == 'application/pdf'){
		$fp = fopen($rep_upload_path.$ligne->explnum_nomfichier , "r" ) ;
		$contenu_vignette = fread($fp, filesize($rep_upload_path.$ligne->explnum_nomfichier));
		header('Content-type: application/pdf');
		print $contenu_vignette;
	}else{
		$fp = fopen($rep_upload_path.$ligne->explnum_nomfichier, "r" ) ;
		$contenu_vignette =fread($fp,filesize($rep_upload_path.$ligne->explnum_nomfichier));
		fclose ($fp) ;
		header('Content-type: image/gif');
		print $contenu_vignette ;		
	}
} else{
	$fp = fopen("./images/mimetype/unknown.gif" , "r" ) ;
	$contenu_vignette = fread ($fp, filesize("./images/mimetype/unknown.gif"));
	fclose ($fp) ;
	header('Content-type: image/gif');
	print $contenu_vignette ;
}

?>