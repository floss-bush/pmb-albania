<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: doc_num_data.php,v 1.16 2009-10-16 15:51:30 gueluneau Exp $

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

require_once ($class_path."/upload_folder.class.php"); 

$resultat = mysql_query("SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, length(explnum_data) as taille,explnum_path, concat(repertoire_path,explnum_path,explnum_nomfichier) as path, repertoire_id FROM explnum left join upload_repertoire on repertoire_id=explnum_repertoire WHERE explnum_id = '$explnum_id' ", $dbh);
$nb_res = mysql_num_rows($resultat) ;

if (!$nb_res) {
	exit ;
} 

$ligne = mysql_fetch_object($resultat);

//Accessibilité des documents numériques aux abonnés en opac
$req_restriction_abo = "SELECT  explnum_visible_opac, explnum_visible_opac_abon FROM notice_statut, explnum, notices WHERE explnum_notice=notice_id AND statut=id_notice_statut  AND explnum_id='$explnum_id'";
$result=mysql_query($req_restriction_abo,$dbh) or die(mysql_error()." <br />".$req_restriction_abo);
$expl_num=mysql_fetch_object($result);

if($expl_num->explnum_visible_opac && (!$expl_num->explnum_visible_opac_abon || ($expl_num->explnum_visible_opac_abon && $_SESSION["user_code"]))){
	if (($ligne->explnum_data)||($ligne->explnum_path)) {
		if ($ligne->explnum_path) {
			$up = new upload_folder($ligne->repertoire_id);
			$path = str_replace("//","/",$ligne->path);
			$path=$up->encoder_chaine($path);
			$fo = fopen($path,'rb');
			$ligne->explnum_data=fread($fo,filesize($path));
			$ligne->taille=filesize($path);
			fclose($fo);
		}
		header("Content-Type: ".$ligne->explnum_mimetype);
		header("Content-Length: ".$ligne->taille);
		print $ligne->explnum_data;
		exit ;
	}
}