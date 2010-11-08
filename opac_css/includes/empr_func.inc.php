<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_func.inc.php,v 1.13 2010-09-14 07:42:58 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function connexion_empr() {
	global $dbh, $msg, $opac_duration_session_auth;
	global $time_expired, $erreur_session, $login, $password ;
	global $auth_ok, $lang, $code, $emprlogin;
	global $first_log;
	global $erreur_connexion;
	
	//a positionner si authentification exterieure
	global $ext_auth,$empty_pwd;
	
	$erreur_connexion=0;
	
	$log_ok=0;
	if (!$_SESSION["user_code"]) {
		if ($time_expired==0) { // début if ($time_expired==0) 1
			//Si pas de session en cours, vérification du login
			$verif_query = "SELECT id_empr, empr_cb, empr_nom, empr_prenom, empr_password, empr_lang, empr_date_expiration<sysdate() as isexp, empr_login, empr_ldap,empr_location, allow_opac 
					FROM empr
					JOIN empr_statut ON empr_statut=idstatut
					WHERE empr_login='".($emprlogin ? $emprlogin :$_POST['login'])."'";
			$verif_result = mysql_query($verif_query) or die(mysql_error());
			// récupération des valeurs MySQL du lecteur et injection dans les variables
			while ($verif_line = mysql_fetch_array($verif_result)) {
				$verif_empr_cb = $verif_line['empr_cb'];
				$verif_empr_login = $verif_line['empr_login'];
				$verif_empr_ldap = $verif_line['empr_ldap'];
				$verif_empr_password = $verif_line['empr_password'];
				$verif_lang = ($verif_line['empr_lang']?$verif_line['empr_lang']:"fr_FR");
				$verif_id_empr = $verif_line['id_empr'];
				$verif_isexp = $verif_line['isexp'];
				$verif_opac = $verif_line['allow_opac'];
				$empr_location = $verif_line['empr_location'];
			}

			if ($ext_auth) $auth_ok=$ext_auth;
			elseif (($verif_empr_ldap)) $auth_ok=auth_ldap($_POST['login'],$password); // auth by server ldap
			elseif($code) $auth_ok = connexion_auto();
			else $auth_ok=( ($empty_pwd ||(!$empty_pwd && $verif_empr_password)) && ($verif_empr_password==stripslashes($password)) && ($verif_empr_login!="")/*&&(!$verif_isexp)*/&&($verif_opac) ); //auth standard

			if ($auth_ok) { // début if ($auth_ok) 1 
				//Si mot de passe correct, enregistrement dans la session de l'utilisateur
				$log_ok=1;
				//Récupération de l'environnement précédent
				$requete="select session from opac_sessions where empr_id=".$verif_id_empr;
				$res_session=mysql_query($requete);
				if (@mysql_num_rows($res_session)) {
					$temp_session=unserialize(mysql_result($res_session,0,0));
					$_SESSION=$temp_session;
				} else 
					$_SESSION=array();
				if(!$code)$_SESSION["connexion_empr_auto"]=0;
				$_SESSION["user_code"]=$verif_empr_login;
				$_SESSION["id_empr_session"]=$verif_id_empr;
				$_SESSION["connect_time"]=time();
				$_SESSION["lang"]=$verif_lang;
				$_SESSION["empr_location"]=$empr_location;
				$req="select location_libelle from docs_location where idlocation='".$_SESSION["empr_location"]."'";
				$_SESSION["empr_location_libelle"]= mysql_result(mysql_query($req, $dbh),0,0);
				
				// change language and charset after login
				$lang=$_SESSION["lang"]; 
				set_language($lang);
				if(!$verif_isexp){
					recupere_pref_droits($_SESSION["user_code"]);
					$_SESSION["user_expired"] = $verif_isexp;
				} else {
					recupere_pref_droits($_SESSION["user_code"],1);
					$_SESSION["user_expired"] = $verif_isexp;
					echo "<script>alert(\"".$msg["empr_expire"]."\");</script>";
					$erreur_connexion=1;
				}
				$first_log=true;
			} else {
				//Sinon, on détruit la session créée
				@session_destroy();
				if (($verif_empr_password!=stripslashes($password)) || ($verif_empr_login=="") || $verif_empr_ldap || $code){
					// la saisie du mot de passe ou du login est incorrect ou erreur de connexion avec le ldap
					$erreur_session = $empr_erreur_header;
					$erreur_session .= $msg["empr_type_card_number"]."<br />";
					$erreur_session .= $empr_erreur_footer;
					$erreur_connexion=3;
				}elseif ($verif_isexp){
					//Si l'abonnement est expiré
					echo "<script>alert(\"".$msg["empr_expire"]."\");</script>";
					$erreur_connexion=1;
				}elseif(!$verif_opac){
					//Si la connexion à l'opac est interdite
					echo "<script>alert(\"".$msg["empr_connexion_interdite"]."\");</script>";
					$erreur_connexion=2;
				}else{
					// Autre cas au cas où...
					$erreur_session = $empr_erreur_header;
					$erreur_session .= $msg["empr_type_card_number"]."<br />";
					$erreur_session .= $empr_erreur_footer;
					$erreur_connexion=3;
				}
				$log_ok=0 ;
				$time_expired = 0 ;
			} // fin if ($auth_ok) 1
		} else  // la session a expiré, on va le lui dire
			echo "<script>alert(\"".sprintf($msg["session_expired"],round($opac_duration_session_auth/60))."\");</script>";
	} else {
		//Si session en cours, pas de problème...
		$log_ok=1;
		$login=$_SESSION["user_code"];
		if($_SESSION["user_expired"]){
			recupere_pref_droits($login,1);
		} else 	recupere_pref_droits($login);
	}
	// pour visualiser une notice issue de DSI avec une connexion auto: on limite la navigation
	if($_SESSION["connexion_empr_auto"] && $log_ok){
		global $connexion_empr_auto,$tab,$lvl,$opac_cart_allow,$opac_resa,$opac_resa_planning,$allow_tag,$allow_sugg,$allow_book;
		$connexion_empr_auto=1;
		if(!$code){
			$tab="dsi";
			$lvl="bannette";			
		}
		$opac_cart_allow=0;
		$opac_resa=0;
		$opac_resa_planning=0;	
		$allow_tag =0;
		$allow_sugg=0;
		$allow_book=0;
	}	
	return $log_ok;

}

function recupere_pref_droits($login,$limitation_adhesion=0) {
	global $dbh, $msg ;
	global $id_empr,
		$empr_cb,
		$empr_nom,
		$empr_prenom,
		$empr_adr1,
		$empr_adr2,
		$empr_cp,
		$empr_ville,
		$empr_mail,
		$empr_tel1,
		$empr_tel2,
		$empr_prof,
		$empr_year,
		$empr_categ,
		$empr_codestat,
		$empr_sexe,
		$empr_login,
		$empr_ldap,
		$empr_location;

	global $allow_loan,
		$allow_loan_hist,
		$allow_book,
		$allow_opac,
		$allow_dsi,
		$allow_dsi_priv,
		$allow_sugg,
		$allow_dema,
		$allow_prol,
		$allow_avis,
		$allow_tag,
		$allow_pwd,
		$allow_liste_lecture,
		$allow_self_checkout,
		$allow_self_checkin;
	global $opac_adhesion_expired_status;
	
	if($limitation_adhesion && $opac_adhesion_expired_status){
		$req = "select * from empr_statut where idstatut='".$opac_adhesion_expired_status."'";
		$res = mysql_query($req,$dbh);
		$data_expired = mysql_fetch_array($res);
		$droit_loan= $data_expired['allow_loan'];
		$droit_loan_hist= $data_expired['allow_loan_hist'];
		$droit_book= $data_expired['allow_book'];
		$droit_opac= $data_expired['allow_opac'];
		$droit_dsi= $data_expired['allow_dsi'];
		$droit_dsi_priv= $data_expired['allow_dsi_priv'];
		$droit_sugg= $data_expired['allow_sugg'];
		$droit_dema= $data_expired['allow_dema'];
		$droit_prol= $data_expired['allow_prol'];
		$droit_avis= $data_expired['allow_avis'];
		$droit_tag= $data_expired['allow_tag'];
		$droit_pwd= $data_expired['allow_pwd'];
		$droit_liste_lecture = $data_expired['allow_liste_lecture'];		
		$droit_self_checkout = $data_expired['allow_self_checkout'];
		$droit_self_checkin = $data_expired['allow_self_checkin'];
	} else {
		$droit_loan= 1;
		$droit_loan_hist=1;
		$droit_book= 1;
		$droit_opac= 1;
		$droit_dsi= 1;
		$droit_dsi_priv=1;
		$droit_sugg= 1;
		$droit_dema= 1;
		$droit_prol= 1;
		$droit_avis=1 ;
		$droit_tag= 1;
		$droit_pwd= 1;
		$droit_liste_lecture = 1;
		$droit_self_checkout=1;
		$droit_self_checkin=1;
	}
	
	$query0 = "select * from empr, empr_statut where empr_login='".$login."' and idstatut=empr_statut ";
	$req0 = mysql_query($query0,$dbh);
	$data = mysql_fetch_array($req0);
	$id_empr = $data['id_empr'];
	$empr_cb = $data['empr_cb'];
	$empr_nom = $data['empr_nom'];
	$empr_prenom= $data['empr_prenom'];
	$empr_adr1= $data['empr_adr1'];
	$empr_adr2= $data['empr_adr2'];
	$empr_cp= $data['empr_cp'];
	$empr_ville= $data['empr_ville'];
	$empr_mail= $data['empr_mail'];
	$empr_tel1= $data['empr_tel1'];
	$empr_tel2= $data['empr_tel2'];
	$empr_prof= $data['empr_prof'];
	$empr_year= $data['empr_year'];
	$empr_categ= $data['empr_categ'];
	$empr_codestat= $data['empr_codestat'];
	$empr_sexe= $data['empr_sexe'];
	$empr_login= $data['empr_login'];
	$empr_ldap= $data['empr_ldap'];
	$empr_location= $data['empr_location'];
	
	// droits de l'utilisateur
	$allow_loan= $data['allow_loan'] & $droit_loan;
	$allow_loan_hist= $data['allow_loan_hist'] & $droit_loan_hist;
	$allow_book= $data['allow_book'] & $droit_book;
	$allow_opac= $data['allow_opac'] & $droit_opac;
	$allow_dsi= $data['allow_dsi'] & $droit_dsi;
	$allow_dsi_priv= $data['allow_dsi_priv'] & $droit_dsi_priv;
	$allow_sugg= $data['allow_sugg'] & $droit_sugg;
	$allow_dema= $data['allow_dema'] & $droit_dema;
	$allow_prol= $data['allow_prol'] & $droit_prol;
	$allow_avis= $data['allow_avis'] & $droit_avis;
	$allow_tag= $data['allow_tag'] & $droit_tag;
	$allow_pwd= $data['allow_pwd'] & $droit_pwd;
	$allow_liste_lecture = $data['allow_liste_lecture'] & $droit_liste_lecture;
	$allow_self_checkout= $data['allow_self_checkout'] & $droit_self_checkout;
	$allow_self_checkin= $data['allow_self_checkin'] & $droit_self_checkin;
}


function connexion_auto(){
	global $opac_connexion_phrase;
	global $date_conex,$emprlogin,$code;
	
	$log_ok=0;
	if($code == md5($opac_connexion_phrase.$emprlogin.$date_conex))
		$log_ok = 1;
	return $log_ok;
}