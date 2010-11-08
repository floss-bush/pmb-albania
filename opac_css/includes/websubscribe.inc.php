<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: websubscribe.inc.php,v 1.3 2009-05-16 10:52:50 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "inc.php")) die("no access");

define('PBINSC_OK'		,    0);
define('PBINSC_MAIL'	,    1);
define('PBINSC_LOGIN'	,    2);
define('PBINSC_BDD'		,    3);
define('PBINSC_MAIL'	,    4);
define('PBINSC_INVALID'	,    5);
define('PBINSC_INCONNUE',    6);
define('PBINSC_CLE'		,    7);

require_once($base_path."/includes/templates/websubscribe.tpl.php");

function generate_form_inscription() {
	global $subs_form_create, $msg ;
	global $f_nom, $f_prenom, $f_email, $f_login, $f_password ;
	global $f_msg, $f_adr1, $f_adr2, $f_cp, $f_ville, $f_pays, $f_tel1;
	
	$subs_form_create = str_replace ("!!f_nom!!",		stripslashes($f_nom),		$subs_form_create);
	$subs_form_create = str_replace ("!!f_prenom!!",	stripslashes($f_prenom),	$subs_form_create);
	$subs_form_create = str_replace ("!!f_email!!",		stripslashes($f_email),		$subs_form_create);
	$subs_form_create = str_replace ("!!f_login!!",		stripslashes($f_login),		$subs_form_create);
	$subs_form_create = str_replace ("!!f_password!!",	"",							$subs_form_create);
	$subs_form_create = str_replace ("!!f_passwordv!!",	"",							$subs_form_create);
	$subs_form_create = str_replace ("!!f_adr1!!",		stripslashes($f_adr1),		$subs_form_create);
	$subs_form_create = str_replace ("!!f_adr2!!",		stripslashes($f_adr2),		$subs_form_create);
	$subs_form_create = str_replace ("!!f_cp!!",		stripslashes($f_cp),		$subs_form_create);
	$subs_form_create = str_replace ("!!f_ville!!",		stripslashes($f_ville),		$subs_form_create);
	$subs_form_create = str_replace ("!!f_pays!!",		stripslashes($f_pays),		$subs_form_create);
	$subs_form_create = str_replace ("!!f_tel1!!",		stripslashes($f_tel1),		$subs_form_create);
	$subs_form_create = str_replace ("!!f_msg!!",		stripslashes($f_msg),		$subs_form_create);
	return $subs_form_create;
	}

function verif_validite_compte() {
	global $dbh, $msg, $opac_default_lang ;
	global $f_nom, $f_prenom, $f_email, $f_login, $f_password ;
	global $f_msg, $f_adr1, $f_adr2, $f_cp, $f_ville, $f_pays, $f_tel1;
	
	$ret=array();

	$rqt = "select id_empr from empr where empr_mail like '%".$f_email."%' ";
	$res = mysql_query($rqt,$dbh);
	if (mysql_num_rows($res)>0) {
		$ret[0]=PBINSC_MAIL;
		$ret[1]=str_replace("!!email!!",urlencode($f_email),$msg[subs_pb_email]);
		return $ret ;
	}

	$rqt = "select id_empr from empr where empr_login ='".$f_login."' ";
	$res = mysql_query($rqt,$dbh);
	if (mysql_num_rows($res)>0) {
		$ret[0]=PBINSC_LOGIN;
		$ret[1]=str_replace("!!f_login!!",$f_login,$msg[subs_pb_login]).generate_form_inscription();
		return $ret ;
	}

	// préparation des données:
	// langue:
	if ($_COOKIE['PhpMyBibli-LANG']) $lang=$_COOKIE['PhpMyBibli-LANG'];
	if (!$lang) {
		if ($opac_default_lang) $lang = $opac_default_lang;
		else $lang = "fr_FR";
	}
	
	// paramétrage :
	global $opac_websubscribe_empr_location, $opac_websubscribe_empr_status, $opac_websubscribe_empr_categ, $opac_websubscribe_empr_stat, $opac_websubscribe_valid_limit ;
	$opac_websubscribe_empr_status_array=explode(",",$opac_websubscribe_empr_status);
	// codes-barres emprunteur bidon :
	$pe_emprcb='www'.rand(0,100000);
	// durée d'adhésion de la categ web
	$rqt="select duree_adhesion from empr_categ where id_categ_empr='".$opac_websubscribe_empr_categ."' ";
	$res = mysql_query($rqt,$dbh);
	$obj=mysql_fetch_object($res);
	$duree_adhesion=$obj->duree_adhesion;

	// clé de validation :
	$alphanum  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	$cle_validation = substr(str_shuffle($alphanum), 0, 20);

	$rqt = "insert into empr set "; 
	$rqt.= "id_empr=0, "; 
	$rqt.= "empr_cb ='".$pe_emprcb."', "; 
	$rqt.= "empr_login ='".$f_login."', "; 
	$rqt.= "empr_mail='".$f_email."', "; 
	$rqt.= "empr_nom='".$f_nom."', ";
	$rqt.= "empr_prenom='".$f_prenom."', ";
	$rqt.= "empr_password='".$f_password."', ";
	$rqt.= "empr_creation=sysdate(), ";
	$rqt.= "empr_modif=sysdate(), ";
	$rqt.= "empr_date_adhesion=sysdate(), ";
	$rqt.= "empr_date_expiration=date_add(sysdate(), INTERVAL $duree_adhesion DAY), ";
	$rqt.= "empr_lang='".$lang."', ";
	$rqt.= "empr_statut='".$opac_websubscribe_empr_status_array[0]."', ";
	$rqt.= "empr_location='".$opac_websubscribe_empr_location."', ";
	$rqt.= "empr_categ='".$opac_websubscribe_empr_categ."', ";
	$rqt.= "empr_codestat='".$opac_websubscribe_empr_stat."', ";
	$rqt.= "empr_msg='".$f_msg."', ";
	$rqt.= "empr_adr1='".$f_adr1."', ";
	$rqt.= "empr_adr2='".$f_adr2."', ";
	$rqt.= "empr_cp='".$f_cp."', ";
	$rqt.= "empr_ville='".$f_ville."', ";
	$rqt.= "empr_pays='".$f_pays."', ";
	$rqt.= "empr_tel1='".$f_tel1."', ";
	$rqt.= "cle_validation='".$cle_validation."' ";
	
	$res = mysql_query($rqt,$dbh) or die (mysql_error()."<br /><br />$rqt");
	$id_empr = mysql_insert_id();
				
	if ($id_empr) {
		//redefine empr.empr_cb   
		$pe_emprcb='www'.$id_empr;
		$rqt = "UPDATE empr SET empr_cb='$pe_emprcb' WHERE id_empr='$id_empr'";
		$res = mysql_query($rqt, $dbh) or die (mysql_error()."<br /><br />$rqt");

		// envoyer le mail de demande de confirmation
		global $opac_biblio_name,$opac_biblio_email,$opac_url_base ;
		$obj = str_replace("!!biblio_name!!",$opac_biblio_name,$msg[subs_mail_obj]) ;
		$corps = str_replace("!!biblio_name!!",$opac_biblio_name,$msg[subs_mail_corps]) ;
		$lien_validation = "<a href='".$opac_url_base."subscribe.php?subsact=validation&login=$f_login&cle_validation=$cle_validation'>".$opac_url_base."subscribe.php?subsact=validation&login=$f_login&cle_validation=$cle_validation</a>";
		$corps = str_replace("!!lien_validation!!",$lien_validation,$corps) ;
		
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
						
		$res_envoi=@mailpmb(trim(stripslashes($f_prenom." ".$f_nom)), stripslashes($f_email),$obj,$corps,$opac_biblio_name, $opac_biblio_email, $headers);
		if (!$res_envoi) {
			$ret[0]=PBINSC_MAIL;
			$ret[1]=str_replace("!!f_email!!",$f_email,$msg[subs_pb_mail]);
			return $ret ;
		}
		$ret[0]=PBINSC_OK;
		$ret[1]=str_replace("!!f_email!!",$f_email,$msg[subs_ok_inscrit]);
		$ret[1]=str_replace("!!nb_h_valid!!",$opac_websubscribe_valid_limit,$ret[1]);
		return $ret ;
				
	} else {
		$ret[0]=PBINSC_BDD;
		$ret[1]=$msg[subs_pb_bdd];
		return $ret ;
	}

}

function verif_validation_compte() {
	global $dbh, $msg;
	global $login, $cle_validation, $form_access_compte ;
	global $opac_websubscribe_empr_status, $opac_websubscribe_valid_limit  ;
	$opac_websubscribe_empr_status_array=explode(",",$opac_websubscribe_empr_status);

	$ret=array();

	$rqt = "select id_empr, if(date_add(empr_creation, INTERVAL $opac_websubscribe_valid_limit HOUR)>=sysdate(),1,0) as not_depasse, empr_password, cle_validation from empr where empr_login ='".$login."' and empr_statut='".$opac_websubscribe_empr_status_array[0]."' "; 
	$res = mysql_query($rqt,$dbh) or die (mysql_error()."<br /><br />$rqt");
	if (mysql_num_rows($res)>0) {
		// trouvé !
		$obj=mysql_fetch_object($res);
		if ($obj->not_depasse) {
			// validation pas dépassée
			if ($obj->cle_validation==$cle_validation) {
				$rqt = "update empr set cle_validation='', empr_statut='".$opac_websubscribe_empr_status_array[1]."' where empr_login='".$login."' ";
				$res = mysql_query($rqt,$dbh) or die (mysql_error()."<br /><br />$rqt");
				$ret[0]=PBINSC_OK;
				$form_access_compte=str_replace("!!login!!",$login,$form_access_compte) ;
				$form_access_compte=str_replace("!!password!!",$obj->empr_password,$form_access_compte) ;
				$ret[1] = str_replace("!!form_access_compte!!",$form_access_compte,$msg[subs_ok_validation]) ;
				return $ret ;
			} else {
				// login Ok mais clé pas valide
				$rqt = "delete from empr where empr_login='".$login."' ";
				$res = mysql_query($rqt,$dbh) or die (mysql_error()."<br /><br />$rqt");
				$ret[0]=PBINSC_CLE;
				$ret[1]=$msg[subs_pb_cle];
				return $ret ;
			}
		} else {
			// dépassée
			$rqt = "delete from empr where empr_login='".$login."' ";
			$res = mysql_query($rqt,$dbh) or die (mysql_error()."<br /><br />$rqt");
			$ret[0]=PBINSC_INVALID;
			$ret[1]=$msg[subs_pb_invalid];
			return $ret ;
		}			
	}
	// n'existe même pas !
	$ret[0]=PBINSC_INCONNUE;
	$ret[1] = str_replace("!!login!!",$login,$msg[subs_pb_inconnue]) ;
	return $ret ;

}
