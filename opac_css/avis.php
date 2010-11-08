<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// © 2006 mental works / www.mental-works.com contact@mental-works.com
// 	complètement repris et corrigé par PMB Services 
// +-------------------------------------------------+
// $Id: avis.php,v 1.33 2010-03-05 09:02:08 dbellamy Exp $

$base_path=".";
require_once($base_path."/includes/init.inc.php");
require_once($base_path."/includes/error_report.inc.php") ;
require_once($base_path."/includes/global_vars.inc.php");
require_once($base_path.'/includes/opac_config.inc.php');

// récupération paramètres MySQL et connection à la base
require_once($base_path.'/includes/opac_db_param.inc.php');
require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

require_once($base_path."/includes/misc.inc.php");

//Sessions !! Attention, ce doit être impérativement le premer include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");
require_once($base_path.'/includes/start.inc.php');

require_once($base_path."/includes/notice_authors.inc.php");
require_once($base_path."/includes/notice_categories.inc.php");

require_once($base_path."/includes/check_session_time.inc.php");

// récupération localisation
require_once($base_path.'/includes/localisation.inc.php');

// version actuelle de l'opac
require_once($base_path.'/includes/opac_version.inc.php');

// fonctions de gestion de formulaire
require_once($base_path.'/includes/javascript/form.inc.php');

require_once($base_path.'/includes/templates/common.tpl.php');
require_once($base_path.'/includes/divers.inc.php');

// classe de gestion des catégories
require_once($base_path.'/classes/categorie.class.php');
require_once($base_path.'/classes/notice.class.php');
require_once($base_path.'/classes/notice_display.class.php');

// classe indexation interne
require_once($base_path.'/classes/indexint.class.php');

// classe de gestion des réservations
require_once($base_path.'/classes/resa.class.php');

// pour l'affichage correct des notices
require_once($base_path."/includes/templates/notice.tpl.php");
require_once($base_path."/includes/navbar.inc.php");
require_once($base_path."/includes/explnum.inc.php");
require_once($base_path."/includes/notice_affichage.inc.php");
require_once($base_path."/includes/bulletin_affichage.inc.php");

require_once($base_path."/includes/connexion_empr.inc.php");

// autenticazione LDAP - by MaxMan
require_once($base_path."/includes/ldap_auth.inc.php");

// RSS
require_once($base_path."/includes/includes_rss.inc.php");

// pour fonction de formulaire de connexion
require_once($base_path."/includes/empr.inc.php");
// pour fonction de vérification de connexion
require_once($base_path.'/includes/empr_func.inc.php');

if ($opac_avis_allow==0) die("");
// par défaut, on suppose que le droit donné par le statut est Ok
$allow_avis = 1 ;
$allow_tag = 1 ;

if (($todo=='liste' || !$todo) && ($opac_avis_allow==3)) {
	//consultation possible sans authentification
	$log_ok = 1;
} else {
	//Vérification de la session
	$empty_pwd=true;
	$ext_auth=false;
	// si paramétrage authentification particulière
	if (file_exists($base_path.'/includes/ext_auth.inc.php')) require_once($base_path.'/includes/ext_auth.inc.php');
	$log_ok=connexion_empr();
}

$allow_avis_ajout=true;
// on a tout vérifié mais si tout est libre alors on force le log_ok à 1
if ($opac_avis_allow==3) {
	$log_ok=1;
	$allow_avis=1;
}
if ($opac_avis_allow==1 && !$log_ok) {
	$allow_avis_ajout=false ;
}
// La consultation d'avis est autorisé mais son statut bloque...
if ($opac_avis_allow>0 && $allow_avis==0) {
	$log_ok=1;
	$allow_avis=1;
	$allow_avis_ajout=false ;
}

// pour template des avis
require_once($base_path.'/includes/templates/avis.tpl.php');

print $popup_header;

if ($opac_avis_allow && !$allow_avis) die($popup_footer);

print $avis_tpl_header ;

switch($todo) {
	case 'save':
		if (!$allow_avis_ajout) die();
		if (!$note) $note="NULL";
		$masque="@<[\/\!]*?[^<>]*?>@si";
		$commentaire = preg_replace($masque,'',$commentaire);
		$sql="insert into avis (num_empr,num_notice,note,sujet,commentaire) values ('$id_empr','$noticeid','$note','$sujet','$commentaire')";
		if (mysql_query($sql, $dbh)) {
			print $avis_tpl_post_add;
		} else {
			print $avis_tpl_post_add_pb;
		}
		break;

	case 'add' :
		if (!$allow_avis_ajout) die();
		//ajout d'un avis
		echo $avis_tpl_form;
		break;
	default:
	case 'liste' :
		if ($log_ok && $allow_avis && $allow_avis_ajout)
			echo "<p align='right'><a href=\"avis.php?todo=add&noticeid=$noticeid&login=$user_code\">".$msg[avis_lien_ajout]."</a></p>";	

		//affichage de la liste des avis
		//moyenne des notes

		$sql="select avg(note) as m from avis where valide=1 and num_notice='".$noticeid."' group by num_notice";
		$r = mysql_query($sql, $dbh);
		$loc = mysql_fetch_object($r);
		$moyenne=number_format($loc->m,1, ',', '');
		$c_notice = new notice_affichage($noticeid);
		$etoiles_moyenne = $c_notice->stars();
		//liste des témoignages
		$requete="select note,sujet,commentaire,DATE_FORMAT(dateajout,'".$msg['format_date']."') as ladate,empr_login,empr_nom, empr_prenom 
				from avis 
				left join empr on id_empr=num_empr 
				where valide=1 and num_notice='".$noticeid."' 
				order by dateajout desc";
	
		$r = mysql_query($requete, $dbh);
		if (mysql_numrows($r)){
			echo "<div class='row'>
					<div class='left'><b>".$msg['avis_titre_tous'].":</b> ".$msg['avis_note'].": $moyenne</div>
					<div class='right'>$etoiles_moyenne</div>&nbsp;
					</div>";
			$cpt_star = 4;
			while ($loc = mysql_fetch_object($r)) {
				$etoiles="";
				for ($i = 1; $i <= $loc->note; $i++) {
					$etoiles.="<img src='images/star.png' width='15' height='15' align='absmiddle'>";
				}
				for ( $j = round($loc->note);$j <= $cpt_star ; $j++) {
					$etoiles .= "<img border=0 src='images/star_unlight.png' align='absmiddle'>";
				}		
				
				echo "<hr /><div class='row'>
					<div class='left'><b>$loc->sujet</b>, ".$loc->ladate ;
				if ($opac_avis_show_writer==1) echo " ".$msg['avis_de']." $loc->empr_prenom $loc->empr_nom ";
				if ($opac_avis_show_writer==2) echo " ".$msg['avis_de']." $loc->empr_login ";
				echo "</div><div class='right'>$etoiles</div>";
				echo "
					<div class='row'>$loc->commentaire</div>
					</div>";
			}
		} else {
			echo "<div align='center'><br /><br />".$msg['avis_aucun']."</div>";
		}

		break;
	}

if (!$log_ok && $opac_avis_allow==2) {
	$lvl='avis_'.$todo;
	print do_formulaire_connexion();
}

//Enregistrement du log
global $pmb_logs_activate;
if($pmb_logs_activate){	
	global $log;
	$log->add_log('num_session',session_id());
	$log->save();
}

print $popup_footer;

/* Fermeture de la connexion */
mysql_close();
