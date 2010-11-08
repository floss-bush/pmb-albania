<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.php,v 1.16 2009-12-09 14:30:30 mbertin Exp $

$base_path=".";
require_once($base_path."/includes/init.inc.php");
require_once("./includes/error_report.inc.php") ;
require_once("./includes/global_vars.inc.php");
require_once('./includes/opac_config.inc.php');
	
// récupération paramètres MySQL et connection á la base
require_once('./includes/opac_db_param.inc.php');
require_once('./includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();
// (si la connection est impossible, le script die ici).

require_once("./includes/misc.inc.php");

//Sessions !! Attention, ce doit être impérativement le premier include (à cause des cookies)
require_once("./includes/session.inc.php");
require_once('./includes/start.inc.php');
require_once("./includes/check_session_time.inc.php");

// récupération localisation
require_once('./includes/localisation.inc.php');

// version actuelle de l'opac
require_once('./includes/opac_version.inc.php');

// fonctions de gestion de formulaire
require_once('./includes/javascript/form.inc.php');
require_once('./includes/templates/common.tpl.php');
require_once('./includes/divers.inc.php');
require_once('./includes/notice_categories.inc.php');

// classe de gestion des catégories
require_once($base_path.'/classes/categorie.class.php');
require_once($base_path.'/classes/notice.class.php');
require_once($base_path.'/classes/notice_display.class.php');

// classe indexation interne
require_once($base_path.'/classes/indexint.class.php');

// classe d'affichage des tags
require_once($base_path.'/classes/tags.class.php');

require_once($base_path."/includes/marc_tables/".$lang."/empty_words");

// pour l'affichage correct des notices
require_once($base_path."/includes/templates/common.tpl.php");
require_once($base_path."/includes/templates/notice.tpl.php");
require_once($base_path."/includes/navbar.inc.php");
require_once($base_path."/includes/notice_authors.inc.php");
require_once($base_path."/includes/notice_categories.inc.php");
require_once($base_path."/includes/explnum.inc.php");

require_once('./classes/notice_affichage.class.php');
require_once('./classes/notice_affichage.ext.class.php');

require_once($include_path."/mail.inc.php") ;

// pour export panier
require_once("$base_path/admin/convert/start_export.class.php");

if($select_item){
	$cart_ = explode(",",$select_item);
} else $cart_=$_SESSION["cart"];

if (($opac_export_allow=='1') || (($opac_export_allow=='2') && ($_SESSION["user_code"]))) {
	if (!$opac_export_allow_expl) $keep_expl = 0 ;
	if ($action=="export") {
		$exportation="";
		$nb_fiche=0;
		$nb_fiche_total=count($cart_);
		$n_notices=$nb_fiche_total;
		$_SESSION["param_export"]["notice_exporte"]=array();
		for ($z=0; $z<$nb_fiche_total; $z++) {
			$id_externe = 0;
			$is_externe = false; 					
			if (substr($cart_[$z],0,2)!="es"){
				// Exclure de l'export (opac, panier) les fiches interdites de diffusion dans administration, Notices > Origines des notices NG72
				$sql="select 1 from origine_notice,notices where notice_id = '$cart_[$z]' and origine_catalogage = orinot_id and orinot_diffusion='1'";	 
			} else {
				$id_externe = substr($cart_[$z],2);
				$is_externe = true;
				$requete = "SELECT source_id FROM external_count WHERE rid=".$id_externe;
				$myQuery = mysql_query($requete, $dbh);
				$source_id = mysql_result($myQuery, 0, 0);				
				$sql="select 1 from entrepot_source_$source_id where recid='".$id_externe."' group by ufield,usubfield,field_order,subfield_order,value";
			}		
			$res=mysql_query($sql,$dbh);
			if ($ligne=mysql_fetch_array($res)) {
				$nb_fiche++;
				$export= new start_export(($id_externe ? $id_externe : $cart_[$z]),$typeexport,$is_externe) ;
				$exportation.=$export->output_notice;			
			}	
		}
		if ($nb_fiche>0) {
			$exportation=$export->get_header().$exportation.$export->get_footer();
			header("Content-type: ".$export->get_mime_type());
			header('Content-Disposition: attachment; filename="export.'.$export->get_suffix().'"');
		
			print $exportation;
			
			global $pmb_logs_activate;
			if($pmb_logs_activate){
				global $log, $infos_notice, $infos_expl;
				
				$rqt= " select empr_prof,empr_cp, empr_ville as ville, empr_year, empr_sexe,  empr_date_adhesion, empr_date_expiration, count(pret_idexpl) as nbprets, count(resa.id_resa) as nbresa, code.libelle as codestat, es.statut_libelle as statut, categ.libelle as categ, gr.libelle_groupe as groupe,dl.location_libelle as location 
						from empr e
						left join empr_codestat code on code.idcode=e.empr_codestat
						left join empr_statut es on e.empr_statut=es.idstatut
						left join empr_categ categ on categ.id_categ_empr=e.empr_categ
						left join empr_groupe eg on eg.empr_id=e.id_empr
						left join groupe gr on eg.groupe_id=gr.id_groupe
						left join docs_location dl on e.empr_location=dl.idlocation
						left join resa on e.id_empr=resa_idempr
						left join pret on e.id_empr=pret_idempr
						where e.empr_login='".addslashes($login)."'
						group by resa_idempr, pret_idempr";
				$res=mysql_query($rqt);
				if($res){
					$empr_carac = mysql_fetch_array($res);
					$log->add_log('empr',$empr_carac);
				}
				$log->add_log('num_session',session_id());
				$log->add_log('expl',$infos_expl);
				$log->add_log('docs',$infos_notice);
				$log->save();
			}
			
		} else {
				print "<script>alert(\"".$msg[export_aucune_notice]."\"); history.go(-1);</script>";
		}		
	}
}