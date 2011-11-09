<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: doc_num.php,v 1.24 2011-01-28 15:09:35 arenou Exp $

$base_path=".";
require_once($base_path."/includes/init.inc.php");

require_once($base_path."/includes/error_report.inc.php") ;

//Sessions !! Attention, ce doit être impérativement le premer include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");
require_once($base_path."/includes/global_vars.inc.php");
require_once('./includes/opac_config.inc.php');


if ($css=="") $css=1;
	
// récupération paramètres MySQL et connection á la base
require_once('./includes/opac_db_param.inc.php');
require_once('./includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

require_once('./includes/start.inc.php');

require_once("./includes/check_session_time.inc.php");

// récupération localisation
require_once('./includes/localisation.inc.php');

// version actuelle de l'opac
require_once('./includes/opac_version.inc.php');

require_once ("./includes/explnum.inc.php");  

require_once ($class_path."/upload_folder.class.php"); 

//gestion des droits
require_once($class_path."/acces.class.php");

$requete = "SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_nomfichier, explnum_mimetype, explnum_url, 
			explnum_data, explnum_extfichier, explnum_path, concat(repertoire_path,explnum_path,explnum_nomfichier) as path, repertoire_id
			FROM explnum left join upload_repertoire on repertoire_id=explnum_repertoire WHERE explnum_id = '$explnum_id' ";
$resultat = mysql_query($requete,$dbh);
$nb_res = mysql_num_rows($resultat) ;


if (!$nb_res) {
	header("Location: images/mimetype/unknown.gif");
	exit ;
} 
	
$ligne = mysql_fetch_object($resultat);

if($ligne->explnum_bulletin != 0){
	//si bulletin, les droits sont rattachés à la notice du pério...
	$req = "select bulletin_notice from bulletins where bulletin_id =".$ligne->explnum_bulletin;
	$res = mysql_query($req);
	if(mysql_num_rows($res)){
		$perio_id = mysql_result($res,0,0);
	}
}else $perio_id = 0;
//droits d'acces emprunteur/notice
if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
	$ac= new acces();
	$dom_2= $ac->setDomain(2);
	$rights= $dom_2->getRights($_SESSION['id_empr_session'],($perio_id != 0 ? $perio_id : $ligne->explnum_notice));
}

//Accessibilité des documents numériques aux abonnés en opac
if ($ligne->explnum_notice) {
	$req_restriction_abo = "SELECT explnum_visible_opac, explnum_visible_opac_abon FROM notices,notice_statut WHERE notice_id='".$ligne->explnum_notice."' AND statut=id_notice_statut ";
} else {
	$req_restriction_abo = "SELECT explnum_visible_opac, explnum_visible_opac_abon FROM bulletins,notices,notice_statut WHERE bulletin_id='".$ligne->explnum_bulletin."' and bulletin_notice=notice_id AND statut=id_notice_statut ";
}
$result=mysql_query($req_restriction_abo,$dbh) or die(mysql_error()." <br />".$req_restriction_abo);
$expl_num=mysql_fetch_object($result);

if( $rights & 16 || (is_null($dom_2) && $expl_num->explnum_visible_opac && (!$expl_num->explnum_visible_opac_abon || ($expl_num->explnum_visible_opac_abon && $_SESSION["user_code"])))){
	if (($ligne->explnum_data)||($ligne->explnum_path)) {

		if ($ligne->explnum_path) {
			$up = new upload_folder($ligne->repertoire_id);
			$path = str_replace("//","/",$ligne->path);
			$path=$up->encoder_chaine($path);
			$fo = fopen($path,'rb');
			$ligne->explnum_data=fread($fo,filesize($path));
			fclose($fo);
		}

		create_tableau_mimetype() ;
		$name=$_mimetypes_bymimetype_[$ligne->explnum_mimetype]["plugin"] ;
		if ($name) {
			$type = "" ;
			// width='700' height='525' 
			$name = " name='$name' ";
		} else $type="type='$ligne->explnum_mimetype'" ;
		
		if ($_mimetypes_bymimetype_[$ligne->explnum_mimetype]["embeded"]=="yes") {
			print "<html><body><EMBED src=\"./doc_num_data.php?explnum_id=$explnum_id\" $type $name controls='console' ></EMBED></body></html>" ;
			exit ;
		}
		
		$nomfichier="";
		if ($ligne->explnum_nomfichier) {
			$nomfichier=$ligne->explnum_nomfichier;
		} elseif ($ligne->explnum_extfichier)
			$nomfichier="pmb".$ligne->explnum_id.".".$ligne->explnum_extfichier;
		if ($nomfichier) header("Content-Disposition: inline; filename=".$nomfichier);
		
		if ((substr($ligne->explnum_mimetype,0,5)=="image")&&($opac_photo_watermark)) {
			$content_image=reduire_image_middle($ligne->explnum_data);
			if ($content_image) {
				print header("Content-Type: image/png");
				print $content_image;
			} else {
				header("Content-Type: ".$ligne->explnum_mimetype);
				print $ligne->explnum_data;
			}
		} else {
			header("Content-Type: ".$ligne->explnum_mimetype);
			print $ligne->explnum_data;
		}
		exit ;
	}
	
	if ($ligne->explnum_mimetype=="URL") {
		if ($ligne->explnum_url) header("Location: $ligne->explnum_url");
		exit ;
	}
}
