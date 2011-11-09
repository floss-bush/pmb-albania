<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_includes.inc.php,v 1.33.2.1 2011-04-22 13:47:16 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

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

// classe d'affichage des tags
require_once($base_path.'/classes/tags.class.php');

// classe de gestion des réservations
require_once($base_path.'/classes/resa.class.php');

// pour l'affichage correct des notices
require_once($base_path."/includes/templates/notice.tpl.php");
require_once($base_path."/includes/navbar.inc.php");
require_once($base_path."/includes/explnum.inc.php");
require_once($base_path."/includes/notice_affichage.inc.php");
require_once($base_path."/includes/bulletin_affichage.inc.php");

// autenticazione LDAP - by MaxMan
require_once($base_path."/includes/ldap_auth.inc.php");

// RSS
require_once($base_path."/includes/includes_rss.inc.php");

// pour fonction de formulaire de connexion
require_once($base_path."/includes/empr.inc.php");
// pour fonction de vérification de connexion
require_once($base_path.'/includes/empr_func.inc.php');

//pour la gestion des tris
require_once($base_path."/classes/sort.class.php");

require_once($base_path."/classes/suggestions.class.php");

// si paramétrage authentification particulière
$empty_pwd=true;
$ext_auth=false;
if (file_exists($base_path.'/includes/ext_auth.inc.php')) { $file_orig="empr.php"; require_once($base_path.'/includes/ext_auth.inc.php'); }

//Vérification de la session
$log_ok=connexion_empr();

// connexion en cours et paramètre de rebond ailleurs que sur le compte emprunteur
if (($opac_show_login_form_next) && ($login) && ($first_log)) die ("<SCRIPT>document.location='$opac_show_login_form_next';</SCRIPT>");

if ($is_opac_included) {
	$std_header = $inclus_header ;
	$footer = $inclus_footer ;
}
//Enrichissement OPAC
if($opac_notice_enrichment){
	require_once($base_path."/classes/enrichment.class.php");
		$enrichment = new enrichment();
	$std_header = str_replace("!!enrichment_headers!!",$enrichment->getHeaders(),$std_header);
}else $std_header = str_replace("!!enrichment_headers!!","",$std_header);
		
// si $opac_show_homeontop est à 1 alors on affiche le lien retour à l'accueil sous le nom de la bibliothèque dans la fiche empr
if ($opac_show_homeontop==1) $std_header= str_replace("!!home_on_top!!",$home_on_top,$std_header);
else $std_header= str_replace("!!home_on_top!!","",$std_header);

// mise à jour du contenu opac_biblio_main_header
$std_header= str_replace("!!main_header!!",$opac_biblio_main_header,$std_header);

// RSS
$std_header= str_replace("!!liens_rss!!",genere_link_rss(),$std_header);
// l'image $logo_rss_si_rss est calculée par genere_link_rss() en global
$liens_bas = str_replace("<!-- rss -->",$logo_rss_si_rss,$liens_bas);


if($opac_parse_html){
	ob_start();
}

print $std_header;

require_once ($base_path.'/includes/navigator.inc.php');

if ($opac_empr_code_info) print $opac_empr_code_info;

if (!$tab) {
	switch($lvl) {
		case 'change_password':
		case 'valid_change_password':
		case 'message':
			$tab='account';
			break;
		case 'all':
		case 'old':
		case 'late':
		case 'pret':
		case 'retour':
			$tab='loan';
			break;
		case 'resa':
		case 'resa_planning':
			$tab='reza';
			break;
		case 'bannette':
		case 'bannette_gerer':
		case 'bannette_creer':
			$tab='dsi';
			break;
		case 'make_sugg':
		case 'make_multi_sugg':
		case 'import_sugg':
		case 'transform_to_sugg':
		case 'valid_sugg':
		case 'view_sugg':
		case 'suppr_sugg':
			$tab='sugg';
			break;	
		case 'private_list':
		case 'public_list':
			$tab='lecture';
			break;
		case 'demande_list':
		case 'do_dmde':
		case 'list_dmde':
			$tab='request';
			break;
		default:
			$tab='account';
			break;
		}			
}


if ($log_ok) {
	require_once($base_path."/empr/empr.inc.php");
	/* Affichage du bandeau action en bas de la page. A externaliser dans le template */
	$empr_onglet_menu = "<br />
	 <div id='empr_onglet'>
		<ul class='empr_tabs'>
			<li ".(($tab=="account" || !$tab) ? "id=\"current\"" : "" )."><a href='./empr.php?tab=account'>".htmlentities($msg['empr_menu_account'],ENT_QUOTES,$charset)."</a></li>";
	if ($allow_loan) 
		$empr_onglet_menu .= "<li ".(($tab=="loan") ? "id=\"current\"" : "" )."><a href='./empr.php?tab=loan&lvl=late'>".htmlentities($msg['empr_menu_loan'],ENT_QUOTES,$charset)."</a></li>";
	else if ($allow_loan_hist)
		$empr_onglet_menu .= "<li ".(($tab=="loan") ? "id=\"current\"" : "" )."><a href='./empr.php?tab=loan&lvl=old'>".htmlentities($msg['empr_menu_loan'],ENT_QUOTES,$charset)."</a></li>";
	if ($allow_book) 	
		$empr_onglet_menu .= "<li ".(($tab=="reza") ? "id=\"current\"" : "" )."><a href='./empr.php?tab=reza&lvl=resa'>".htmlentities($msg['empr_menu_resa'],ENT_QUOTES,$charset)."</a></li>";
	if (($opac_dsi_active) && ($allow_dsi || $allow_dsi_priv)) 	
		$empr_onglet_menu .= "<li ".(($tab=="dsi") ? "id=\"current\"" : "" )."><a href='./empr.php?tab=dsi&lvl=bannette'>".htmlentities($msg['empr_menu_dsi'],ENT_QUOTES,$charset)."</a></li>";
	if ($opac_show_suggest && $allow_sugg) 	
		$empr_onglet_menu .= "<li ".(($tab=="sugg") ? "id=\"current\"" : "" )."><a href='./empr.php?tab=sugg&lvl=view_sugg'>".htmlentities($msg['empr_menu_sugg'],ENT_QUOTES,$charset)."</a></li>";
	if ($opac_shared_lists && $allow_liste_lecture) 	
		$empr_onglet_menu .= "<li ".(($tab=="lecture") ? "id=\"current\"" : "" )."><a href='./empr.php?tab=lecture&lvl=private_list'>".htmlentities($msg['empr_menu_lecture'],ENT_QUOTES,$charset)."</a></li>";
	if ($opac_demandes_active && $allow_dema) 	
		$empr_onglet_menu .= "<li ".(($tab=="request") ? "id=\"current\"" : "" )."><a href='./empr.php?tab=request&lvl=do_dmde'>".htmlentities($msg['empr_menu_dmde'],ENT_QUOTES,$charset)."</a></li>";		
	$empr_onglet_menu .= "</ul>";
	
	print $empr_onglet_menu;
	$subitems = "
		<div class='row'>
			!!subonglet!!
		</div>
	</div>
	";
	
	switch($tab){
		case 'loan':
			//Mes prêts
			$loan_item="<ul class='empr_subtabs'>";
			if ($allow_loan){
				$loan_item .= "
					<li><a href='./empr.php?tab=loan&lvl=late'>".htmlentities($msg[empr_bt_show_late],ENT_QUOTES,$charset)."</a></li>
					<li><a href='./empr.php?tab=loan&lvl=all'>".htmlentities($msg[empr_bt_show_all],ENT_QUOTES,$charset)."</a></li>
				";
			}
			if ($allow_loan_hist){
				$loan_item .= "
					<li><a href='./empr.php?tab=loan&lvl=old'>".htmlentities($msg[empr_bt_show_old],ENT_QUOTES,$charset)."</a></li>
				";
			}			
			if($opac_allow_self_checkout){
				if(($opac_allow_self_checkout==1 || $opac_allow_self_checkout==3) && ($allow_self_checkout)) {
					$loan_item .= "<li><a href='./empr.php?tab=loan&lvl=pret'>".htmlentities($msg["empr_bt_checkout"],ENT_QUOTES,$charset)."</a></li>";
				}
				if(($opac_allow_self_checkout==2 || $opac_allow_self_checkout==3) && ($allow_self_checkout)){
					$loan_item .= "<li><a href='./empr.php?tab=loan&lvl=retour'>".htmlentities($msg["empr_bt_checkin"],ENT_QUOTES,$charset)."</a></li>";
				}				
			}
			
			$loan_item."</ul>";	
			$subitems = str_replace("!!subonglet!!",$loan_item,$subitems);
			break;
		case 'reza':
			//Mes resa
			$resa_item="<ul class='empr_subtabs'>";
			if ($opac_resa_planning && $allow_book){
				$resa_item .= "<li><a href='./empr.php?tab=reza&lvl=resa_planning'>".htmlentities($msg[empr_bt_show_resa],ENT_QUOTES,$charset)."</a></li>";
			} else if ($allow_book) {
				$resa_item .= "<li><a href='./empr.php?tab=reza&lvl=resa'>".htmlentities($msg[empr_bt_show_resa],ENT_QUOTES,$charset)."</a></li>";
			}
			$resa_item."</ul>";
			$subitems = str_replace("!!subonglet!!",$resa_item,$subitems);
			break;
		case 'dsi':
			//Mes abonnements
			$abo_item ="<ul class='empr_subtabs'>";
			if (($opac_dsi_active) && ($allow_dsi || $allow_dsi_priv)){
				$abo_item .="<li><a href='./empr.php?tab=dsi&lvl=bannette'>".htmlentities($msg[dsi_bannette_acceder],ENT_QUOTES,$charset)."</a></li>"; 
			}
			if ((($opac_show_categ_bannette && $opac_allow_resiliation) || $opac_allow_bannette_priv) && ($allow_dsi || $allow_dsi_priv)){
				$abo_item .="<li><a href='./empr.php?tab=dsi&lvl=bannette_gerer'>".htmlentities($msg[dsi_bannette_gerer],ENT_QUOTES,$charset)."</a></li>"; 
			}
			if ($opac_allow_bannette_priv && $allow_dsi_priv){
				$abo_item .="<li><a href='./index.php?tab=dsi&bt_cree_bannette_priv=1&search_type_asked=extended_search'>".htmlentities($msg[dsi_bt_bannette_priv],ENT_QUOTES,$charset)."</a></li>"; 
			}
			$abo_item.="</ul>";
			$subitems = str_replace("!!subonglet!!",$abo_item,$subitems);
			break;
		case 'sugg':
			//Mes suggestions
			if ($opac_show_suggest && $allow_sugg) {
				$sugg_onglet="
						<ul class='empr_subtabs'>";
				if ($allow_sugg) {
					$sugg_onglet .= "<li><a href='./empr.php?tab=sugg&lvl=make_sugg'>".htmlentities($msg[empr_bt_make_sugg],ENT_QUOTES,$charset)."</a></li>";
					if($opac_allow_multiple_sugg) $sugg_onglet .= "<li><a href='./empr.php?tab=sugg&lvl=make_multi_sugg'>".htmlentities($msg[empr_bt_make_mul_sugg],ENT_QUOTES,$charset)."</a></li>";
				}
				$sugg_onglet .= "<li><a href='./empr.php?tab=sugg&lvl=view_sugg'>".htmlentities($msg[empr_bt_view_sugg],ENT_QUOTES,$charset)."</a></li>";
				$sugg_onglet .="</ul>";
			}
			$subitems = str_replace("!!subonglet!!",$sugg_onglet,$subitems);
			break;
		case 'lecture':
			//Mes listes de lecture
			if($opac_shared_lists && $allow_liste_lecture){		
				$liste_onglet = "		
					<ul class='empr_subtabs'>		
						<li><a href='./empr.php?tab=lecture&lvl=private_list'>".htmlentities($msg['list_lecture_show_my_list'],ENT_QUOTES,$charset)."</a></li>
						<li><a href='./empr.php?tab=lecture&lvl=public_list'>".htmlentities($msg['list_lecture_show_public_list'],ENT_QUOTES,$charset)."</a></li>
						<li><a href='./empr.php?tab=lecture&lvl=demande_list'>".htmlentities($msg['list_lecture_show_my_requests'],ENT_QUOTES,$charset)."</a></li>
					</ul>
				";
			}
			$subitems = str_replace("!!subonglet!!",$liste_onglet,$subitems);
			break;
		case 'request':
			//Mes demandes de recherche
			if($demandes_active && $opac_demandes_active && $allow_dema){
				$demandes_onglet ="	
					<ul class='empr_subtabs'>		
						<li><a href='./empr.php?tab=request&lvl=do_dmde'>".htmlentities($msg['demandes_do_search'],ENT_QUOTES,$charset)."</a></li>
						<li><a href='./empr.php?tab=request&lvl=list_dmde'>".htmlentities($msg['demandes_list'],ENT_QUOTES,$charset)."</a></li>
					</ul>
				";
			}
			$subitems = str_replace("!!subonglet!!",$demandes_onglet,$subitems);
			break;		
		default:
			//Mon Compte
			$my_account_item = "<ul class='empr_subtabs'>";
			if (!$empr_ldap && $allow_pwd){
				$my_account_item .= "<li><a href='./empr.php?lvl=change_password'>".htmlentities($msg[empr_modify_password],ENT_QUOTES,$charset)."</a></li>";
			}
			$my_account_item.="</ul>";
			$subitems = str_replace("!!subonglet!!",$my_account_item,$subitems);
			break;
	}
	print $subitems;
	switch($lvl) {
		case 'change_password':
				$change_password_checked =" checked";
				require_once($base_path.'/empr/change_password.inc.php');
				break;
		case 'valid_change_password':
				$change_password_checked =" checked";
				require_once($base_path.'/empr/valid_change_password.inc.php');
				break;
		case 'message':
				$message_checked =" checked";
				require_once($base_path.'/empr/message.inc.php');
				break;
		case 'all':
				$all_checked =" checked";
				print "<div id='empr-all'>\n";
				print "<h3><span>$msg[empr_loans]</span></h3>";
				$critere_requete=" AND empr.empr_login='$login' order by pret_retour ";
				require_once($base_path.'/empr/all.inc.php');
				print "</div>";
				break;
		case 'old':
				print "<div id='empr-old'>\n";
				print "<h3><span>$msg[empr_loans_old]</span></h3>";
				require_once($base_path.'/empr/old.inc.php');
				print "</div>\n";
				break;
		case 'resa':
				print "<div id='empr-resa'>\n";
				if ($allow_book) include($base_path.'/includes/resa.inc.php');
				else print $msg[empr_no_allow_book];
				print "</div>";
				break;
		case 'resa_planning':
				include($base_path.'/includes/resa_planning.inc.php');
				break;
		case 'bannette':
				print "<div id='empr-dsi'>\n";
				if ($allow_dsi_priv || $allow_dsi) require_once($base_path.'/includes/bannette.inc.php');
				else print $msg[empr_no_allow_dsi];
				print "</div>";
				break;
		case 'bannette_gerer':
				print "<div id='empr-dsi'>\n";
				if ($allow_dsi_priv || $allow_dsi) require_once($base_path.'/includes/bannette_gerer.inc.php');
				else print $msg[empr_no_allow_dsi];
				print "</div>";
				break;
		case 'bannette_creer':
				print "<div id='empr-dsi'>\n";
				if ($allow_dsi_priv) require_once($base_path.'/includes/bannette_creer.inc.php');
				else print $msg[empr_no_allow_dsi_priv];
				print "</div>";
				break;
		case 'make_sugg':
				print "<div id='empr-sugg'>\n";
				if ($allow_sugg) require_once($base_path.'/empr/make_sugg.inc.php');
				else print $msg[empr_no_allow_sugg];
				print "</div>";
				break;		
		case 'make_multi_sugg':
				print "<div id='empr-sugg'>\n";
				if ($allow_sugg){
					require_once($base_path.'/empr/make_multi_sugg.inc.php');
				} else print $msg[empr_no_allow_sugg];
				print "</div>";
				break;
		case 'import_sugg':
				print "<div id='empr-sugg'>\n";
				if ($allow_sugg){
					require_once($base_path.'/empr/import_sugg.inc.php');
				} else print $msg[empr_no_allow_sugg];
				print "</div>";
				break;	
		case 'transform_to_sugg':
				print "<div id='empr-sugg'>\n";
				if ($allow_sugg){
					require_once($base_path.'/empr/make_multi_sugg.inc.php');
				} else print $msg[empr_no_allow_sugg];
				print "</div>";
				break;			
		case 'valid_sugg':
				print "<div id='empr-sugg'>\n";
				if ($allow_sugg) require_once($base_path.'/empr/valid_sugg.inc.php');
				else print $msg[empr_no_allow_sugg];
				print "</div>";
				break;
		case 'view_sugg':
				print "<div id='empr-sugg'>\n";
				require_once($base_path.'/empr/view_sugg.inc.php');
				print "</div>";
				break;
		case 'suppr_sugg':
			print "<div id='empr-sugg'>\n";
			if ($allow_sugg && $id_sug){
				suggestions::delete($id_sug);
			}
			print "</div>";
			break;	
		case 'private_list':
		case 'public_list':
		case 'demande_list':
			print "<div id='empr-list'>\n";
			require_once($base_path.'/empr/liste_lecture.inc.php');
			print "</div>";
			break;		
		case 'do_dmde':
			print "<div id='empr-dema'>\n";
			if ($allow_dema) require_once($base_path.'/empr/make_demande.inc.php');
			else print $msg[empr_no_allow_dema];
			print "</div>";
			break;
		case 'list_dmde':
			print "<div id='empr-dema'>\n";
			if ($allow_dema) require_once($base_path.'/empr/liste_demande.inc.php');
			else print $msg[empr_no_allow_dema];
			print "</div>";
			break;
		case 'late':
			print "<div id='empr-late'>\n";			
			print "<h3><span>$msg[empr_late]</span></h3>";
			$critere_requete=" AND pret_retour < '".date('Y-m-d')."' AND empr.empr_login='$login' order by pret_retour ";
			require_once($base_path.'/empr/all.inc.php');
			print "</div>\n";
			break;		
		case 'pret':
			print "<div id='empr-sugg'>\n";	
			print "<h3><span>".$msg["empr_checkout_title"]."</span></h3>";
			require_once($base_path.'/empr/self_checkout.inc.php');			
			print "</div>";
			break;
		case 'retour':
			print "<div id='empr-sugg'>\n";	
			print "<h3><span>".$msg["empr_checkin_title"]."</span></h3>";
			require_once($base_path.'/empr/self_checkin.inc.php');			
			print "</div>";
			break;		
		default:
			print pmb_bidi($empr_identite);
			break;
		}

} else {
	// Si la connexion n'a pas pu être établie
	switch ($erreur_connexion) {
		case "1":
				//L'abonnement du lecteur est expiré
				print "<br />".$msg["empr_expire"]."<br /><br /><br />";
				break;
		case "2":
				//Le statut de l'abonné ne l'autorise pas à se connecter
				print "<br />".$msg["empr_connexion_interdite"]."<br /><br /><br />";
				break;
		case "3":
				//Erreur de saisi du mot de passe ou du login ou de connexion avec le ldap
				print "<br />".$msg["empr_bad_login"]."<br /><br /><br />";
				break;	
		default:
				//La session est expiré
				print "<br />".sprintf($msg["session_expired"],round($opac_duration_session_auth/60))."<br /><br /><br />";
				break;
	}
}
if ($erreur_session) print $erreur_session ;

//insertions des liens du bas dans le $footer si $opac_show_liensbas
if ($opac_show_liensbas==1) $footer = str_replace("!!div_liens_bas!!",$liens_bas,$footer);
else $footer = str_replace("!!div_liens_bas!!","",$footer);

//affichage du bandeau de gauche si $opac_show_bandeaugauche = 1
if ($opac_show_bandeaugauche==0) {
	$footer= str_replace("!!contenu_bandeau!!","",$footer);
} else {
	$footer = str_replace("!!contenu_bandeau!!","<div id=\"bandeau\">!!contenu_bandeau!!</div>",$footer);
	$home_on_left=str_replace("!!welcome_page!!",$msg["welcome_page"],$home_on_left);
	$adresse=str_replace("!!common_tpl_address!!",$msg["common_tpl_address"],$adresse);
	$adresse=str_replace("!!common_tpl_contact!!",$msg["common_tpl_contact"],$adresse);
	$loginform=str_replace("!!common_tpl_login_invite!!",$msg["common_tpl_login_invite"],$loginform);
	
	// loading the languages avaiable in OPAC - martizva >> Eric
	require_once($base_path.'/includes/languages.inc.php');
	$home_on_left = str_replace("!!common_tpl_lang_select!!", show_select_languages("empr.php"), $home_on_left);
	
	if (!$_SESSION["user_code"]) {
		$loginform__ = genere_form_connexion_empr();
	} else {
		$loginform__.="<b>".$empr_prenom." ".$empr_nom."</b><br />\n";
		$loginform__.="<a href=\"empr.php\" id=\"empr_my_account\">".$msg["empr_my_account"]."</a><br />
				<a href=\"index.php?logout=1\" id=\"empr_logout_lnk\">".$msg["empr_logout"]."</a>";
	}
	$loginform = str_replace("!!login_form!!",$loginform__,$loginform);
	$footer= str_replace("!!contenu_bandeau!!",$home_on_left.$loginform.$meteo.$adresse,$footer);
}

print $footer;

// LOG OPAC
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
	
	//Enregistrement multicritere
	global $search;
	if($search)	{
		$search_stat = new search();
		$log->add_log('multi_search', $search_stat->serialize_search());
		$log->add_log('multi_human_query', $search_stat->make_human_query());
	}
	
	$log->save();
}

/* Fermeture de la connexion */
mysql_close();
if($opac_parse_html){
	$htmltoparse = ob_get_contents();
	ob_end_clean();
	$res = parseHTML($htmltoparse);
	print $res;
}
?>