<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_includes.inc.php,v 1.29 2010-07-30 12:46:30 mbertin Exp $

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
		
// si $opac_show_homeontop est à 1 alors on affiche le lien retour à l'accueil sous le nom de la bibliothèque dans la fiche empr
if ($opac_show_homeontop==1) $std_header= str_replace("!!home_on_top!!",$home_on_top,$std_header);
else $std_header= str_replace("!!home_on_top!!","",$std_header);

// mise à jour du contenu opac_biblio_main_header
$std_header= str_replace("!!main_header!!",$opac_biblio_main_header,$std_header);

// RSS
$std_header= str_replace("!!liens_rss!!",genere_link_rss(),$std_header);
// l'image $logo_rss_si_rss est calculée par genere_link_rss() en global
$liens_bas = str_replace("<!-- rss -->",$logo_rss_si_rss,$liens_bas);

print $std_header;

require_once ($base_path.'/includes/navigator.inc.php');

if ($opac_empr_code_info) print $opac_empr_code_info;

if (!$lvl) $lvl="late" ;
if ($log_ok) {
	require_once($base_path."/empr/empr.inc.php");
	
	/* Affichage du bandeau action en bas de la page. A externaliser dans le template */
	print "<br />
		<form action=\"empr.php\" method=\"post\" name=\"FormName\">\n<div class='boutoncirculation'>";
	if ($allow_loan) print "<INPUT type=\"button\" class=\"bouton\" name=\"lvlx\" value=\"".$msg[empr_bt_show_late]."\" onClick=\"this.form.lvl.value='late'; this.form.submit();\">&nbsp;
		<INPUT type='button' class='bouton' name='lvlx' value=\"".$msg[empr_bt_show_all]."\" onClick=\"this.form.lvl.value='all'; this.form.submit();\">&nbsp;";
	if ($allow_loan_hist) print "<INPUT type='button' class='bouton' name='lvlx' value=\"".$msg[empr_bt_show_old]."\" onClick=\"this.form.lvl.value='old'; this.form.submit();\">&nbsp;";

	if ($opac_resa_planning && $allow_book) print "<input type=\"button\" class=\"bouton\" name='ok' value=\"".$msg[empr_bt_show_resa]."\" onClick=\"this.form.lvl.value='resa_planning'; this.form.submit();\">\n";
		else if ($allow_book) print "<input type=\"button\" class=\"bouton\" name='ok' value=\"".$msg[empr_bt_show_resa]."\" onClick=\"this.form.lvl.value='resa'; this.form.submit();\">\n";

	print "</div>
		<input type=\"hidden\" name=\"lvl\" value=\"$lvl\"/>
		";

	if (!$empr_ldap && $allow_pwd) print "<br /><br /><input type='button' class='bouton' name='ok' value='$msg[empr_modify_password]' onClick=\"this.form.lvl.value='change_password'; this.form.submit();\">&nbsp;";
	if (($opac_dsi_active) && ($allow_dsi || $allow_dsi_priv)) print "<br /><br /><input type='button' class='bouton dsi_bannette_acceder' name='dsi_afficher' value=\"$msg[dsi_bannette_acceder]\" onClick=\"this.form.lvl.value='bannette'; this.form.submit();\">&nbsp;";
	if ((($opac_show_categ_bannette && $opac_allow_resiliation) || $opac_allow_bannette_priv) && ($allow_dsi || $allow_dsi_priv)) print "<input type='button' class='bouton dsi_bannette_gerer' name='dsi_gerer' value=\"$msg[dsi_bannette_gerer]\" onClick=\"this.form.lvl.value='bannette_gerer'; this.form.submit();\">&nbsp;";
	if ($opac_allow_bannette_priv && $allow_dsi_priv) print "<input type='button' class='bouton dsi_bt_bannette_priv' name='dsi_priv' value=\"$msg[dsi_bt_bannette_priv]\" onClick=\"document.location='./index.php?bt_cree_bannette_priv=1&search_type_asked=extended_search'\">&nbsp;";
	if ($opac_show_suggest && $allow_sugg) {
		print "<br /><br />";
		print "<input type='button' class='bouton' id='make_sugg' name='make_sugg' value='$msg[empr_bt_make_sugg]' onClick=\"this.form.lvl.value='make_sugg'; this.form.submit();\">&nbsp;";
		if($opac_allow_multiple_sugg) print "<input type='button' class='bouton' id='make_mul_sugg' name='make_mul_sugg' value='$msg[empr_bt_make_mul_sugg]' onClick=\"this.form.lvl.value='make_multi_sugg'; this.form.submit();\">&nbsp;";
		print "<input type='button' class='bouton' id='view_sugg' name='view_sugg' value='$msg[empr_bt_view_sugg]' onClick=\"this.form.lvl.value='view_sugg'; this.form.submit();\">";
					
	}
	if($opac_shared_lists && $allow_liste_lecture){
		print "<br /><br /><div class='reading_lists_menu'><input type='button' class='bouton' name='private_list' value='$msg[list_lecture_show_my_list]' onClick=\"this.form.lvl.value='private_list'; this.form.submit();\">&nbsp;";
		print "<input type='button' class='bouton' name='public_list' value='$msg[list_lecture_show_public_list]' onClick=\"this.form.lvl.value='public_list'; this.form.submit();\">&nbsp;";
		print "<input type='button' class='bouton' name='public_list' value='".htmlentities($msg[list_lecture_show_my_requests],ENT_QUOTES,$charset)."' onClick=\"this.form.lvl.value='demande_list'; this.form.submit();\">&nbsp;</div>";
	}
	if($demandes_active && $opac_demandes_active && $allow_dema){
		print "<br /><br /><div class='requests_menu'><input type='button' class='bouton' name='do_dmde' value='$msg[demandes_do_search]' onClick=\"this.form.lvl.value='do_dmde'; this.form.submit();\">&nbsp;";
		print "<input type='button' class='bouton' name='list_dmde' value='$msg[demandes_list]' onClick=\"this.form.lvl.value='list_dmde'; this.form.submit();\">&nbsp;</div>";
	}
	print "</form><br />";
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
				print "<h3><span>$msg[empr_loans]</span></h3>";
				$critere_requete=" AND empr.empr_login='$login' order by pret_retour ";
				require_once($base_path.'/empr/all.inc.php');
				break;
		case 'old':
				print "<h3><span>$msg[empr_loans_old]</span></h3>";
				require_once($base_path.'/empr/old.inc.php');
				break;
		case 'resa':
				if ($allow_book) include($base_path.'/includes/resa.inc.php');
					else print $msg[empr_no_allow_book];
				break;
		case 'resa_planning':
				include($base_path.'/includes/resa_planning.inc.php');
				break;
		case 'bannette':
				if ($allow_dsi_priv || $allow_dsi) require_once($base_path.'/includes/bannette.inc.php');
					else print $msg[empr_no_allow_dsi];
				break;
		case 'bannette_gerer':
				if ($allow_dsi_priv || $allow_dsi) require_once($base_path.'/includes/bannette_gerer.inc.php');
					else print $msg[empr_no_allow_dsi];
				break;
		case 'bannette_creer':
				if ($allow_dsi_priv) require_once($base_path.'/includes/bannette_creer.inc.php');
					else print $msg[empr_no_allow_dsi_priv];
				break;
		case 'make_sugg':
				if ($allow_sugg) require_once($base_path.'/empr/make_sugg.inc.php');
				else print $msg[empr_no_allow_sugg];
				break;		
		case 'make_multi_sugg':
				if ($allow_sugg){
					require_once($base_path.'/empr/make_multi_sugg.inc.php');
				} else print $msg[empr_no_allow_sugg];
				break;
		case 'import_sugg':
				if ($allow_sugg){
					require_once($base_path.'/empr/import_sugg.inc.php');
				} else print $msg[empr_no_allow_sugg];
				break;	
		case 'transform_to_sugg':
				if ($allow_sugg){
					require_once($base_path.'/empr/make_multi_sugg.inc.php');
				} else print $msg[empr_no_allow_sugg];
				break;			
		case 'valid_sugg':
				if ($allow_sugg) require_once($base_path.'/empr/valid_sugg.inc.php');
				else print $msg[empr_no_allow_sugg];
				break;
		case 'view_sugg':
				require_once($base_path.'/empr/view_sugg.inc.php');
				break;
		case 'private_list':
		case 'public_list':
		case 'demande_list':
			require_once($base_path.'/empr/liste_lecture.inc.php');
			break;		
		case 'do_dmde':
			if ($allow_dema) require_once($base_path.'/empr/make_demande.inc.php');
			else print $msg[empr_no_allow_dema];
			break;
		case 'list_dmde':
			if ($allow_dema) require_once($base_path.'/empr/liste_demande.inc.php');
			else print $msg[empr_no_allow_dema];
			break;
		case 'suppr_sugg':
			if ($allow_sugg && $id_sug){
				suggestions::delete($id_sug);
			}
			break;	
		default:
		case 'late':
			print pmb_bidi($empr_identite);
			print "<h3><span>$msg[empr_late]</span></h3>";
			$critere_requete=" AND pret_retour < '".date('Y-m-d')."' AND empr.empr_login='$login' order by pret_retour ";
			require_once($base_path.'/empr/all.inc.php');
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
		$loginform__.="<a href=\"empr.php\">".$msg["empr_my_account"]."</a><br />
				<a href=\"index.php?logout=1\" id=\"empr_logout_lnk\">".$msg["empr_logout"]."</a>";
	}
	$loginform = str_replace("!!login_form!!",$loginform__,$loginform);
	$footer= str_replace("!!contenu_bandeau!!",$home_on_left.$loginform.$meteo.$adresse,$footer);
}

print $footer;


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

?>