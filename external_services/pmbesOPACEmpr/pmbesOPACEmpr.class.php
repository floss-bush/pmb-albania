<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmbesOPACEmpr.class.php,v 1.30.2.1 2011-09-28 14:36:21 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/external_services.class.php");
require_once($class_path."/external_services_caches.class.php");

define("LIST_LOAN_LATE",0);
define("LIST_LOAN_CURRENT",1);
define("LIST_LOAN_PRECEDENT",2);

class pmbesOPACEmpr extends external_services_api_class{
	var $error=false;		//Y-a-t-il eu une erreur
	var $error_message="";	//Message correspondant à l'erreur
	
	function restore_general_config() {
		
	}
	
	function form_general_config() {
		return false;
	}
	
	function save_general_config() {
		
	}
	
	function check_auth(&$empr_login, &$empr_password, &$empr_id) {
		//grassement copié de opac_css/includes/empr_func.inc.php
		global $dbh, $verif_empr_ldap;
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$empr_login = utf8_encode($empr_login);
			$empr_password = utf8_encode($empr_password);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$empr_login = utf8_decode($empr_login);	
			$empr_password = utf8_decode($empr_password);	
		}
		
		$verif_query = "SELECT id_empr, empr_cb, empr_nom, empr_prenom, empr_password, empr_lang, empr_date_expiration<sysdate() as isexp, empr_login, empr_ldap,empr_location 
						FROM empr 
						WHERE empr_login='".addslashes($empr_login)."'";
		$verif_result = mysql_query($verif_query);
		if (!$verif_result)
			return 0;
		
		// récupération des valeurs MySQL du lecteur et injection dans les variables
		$verif_line = mysql_fetch_array($verif_result);
		$verif_empr_cb = $verif_line['empr_cb'];
		$verif_empr_login = $verif_line['empr_login'];
		$verif_empr_ldap = $verif_line['empr_ldap'];
		$verif_empr_password = $verif_line['empr_password'];
		$verif_lang = ($verif_line['empr_lang']?$verif_line['empr_lang']:"fr_FR");
		$verif_id_empr = $verif_line['id_empr'];
		$empr_id = $verif_id_empr;
		$verif_isexp = $verif_line['isexp'];
		$empr_location = $verif_line['empr_location'];

		if ($verif_empr_ldap) {
			//Authentification par LDAP
			global $ldap_server, $ldap_basedn, $ldap_port, $ldap_proto, $ldap_binddn;
			define ('LDAP_SERVER',$ldap_server);  //url server ldap
			define ('LDAP_BASEDN',$ldap_basedn);  //search base
			define ('LDAP_PORT'  ,$ldap_port);    //port
			define ('LDAP_PROTO'  ,$ldap_proto);    //protocollo
			define ('LDAP_BINDDN',$ldap_binddn);
			
			global $ldap_accessible ;
			if (!$ldap_accessible) return 0;
			$ret = 0;
			if ($pwd){
				$dn=str_replace('UID',$uid,LDAP_BINDDN);
				$conn=@ldap_connect(LDAP_SERVER,LDAP_PORT);  // must be a valid LDAP server!
				ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, LDAP_PROTO);
				if ($conn) {
					$ret = @ldap_bind($conn, $dn, $pwd);
					ldap_close($conn);
				}
			}
			return $ret;
			
		}
		else {
			//Autentification standard
			return (($verif_empr_password==$empr_password)&&($verif_empr_login!="")&&(!$verif_isexp));
		}		
	}
	
	function check_auth_md5($empr_login, $empr_password_md5, &$empr_id) {
		//grassement copié de opac_css/includes/empr_func.inc.php
		//note: cette fonction ne permet pas l'autentification synchronisée sur ldap
		global $dbh, $verif_empr_ldap;
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$empr_login = utf8_encode($empr_login);
			$empr_password_md5 = utf8_encode($empr_password_md5);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$empr_login = utf8_decode($empr_login);	
			$empr_password_md5 = utf8_decode($empr_password_md5);	
		}
		
		$verif_query = "SELECT id_empr, empr_cb, empr_nom, empr_prenom, empr_password, empr_lang, empr_date_expiration<sysdate() as isexp, empr_login, empr_ldap,empr_location 
						FROM empr 
						WHERE empr_login='".addslashes($empr_login)."'";
		$verif_result = mysql_query($verif_query);
		if (!$verif_result)
			return 0;
		
		// récupération des valeurs MySQL du lecteur et injection dans les variables
		$verif_line = mysql_fetch_array($verif_result);
		$verif_empr_cb = $verif_line['empr_cb'];
		$verif_empr_login = $verif_line['empr_login'];
		$verif_empr_ldap = $verif_line['empr_ldap'];
		$verif_empr_password = $verif_line['empr_password'];
		$verif_lang = ($verif_line['empr_lang']?$verif_line['empr_lang']:"fr_FR");
		$verif_id_empr = $verif_line['id_empr'];
		$empr_id = $verif_id_empr;
		$verif_isexp = $verif_line['isexp'];
		$empr_location = $verif_line['empr_location'];

		if ($verif_empr_ldap) {
			return 0;
		}
		else {
			//Autentification standard
			return ((md5($verif_empr_password)==$empr_password_md5)&&($verif_empr_login!="")&&(!$verif_isexp));
		}		
	}
	
	function retrieve_session_information($session_id, $no_update_session=false) {
		if (!$session_id)
			return;
		//Allons chercher les infos
		$es_cache = new external_services_cache('es_cache_blob', 1200);
		$session_info = $es_cache->decache_single_object($session_id, CACHE_TYPE_OPACEMPRSESSION);
		if ($session_info === false) {
			return 0;
		}
		$session_info = unserialize($session_info);
		
		//Mettons à jour la date de dernière utilisation de la session si besoin est
		if (!$no_update_session) {
			$session_info["lastused_date"] = time();
			$es_cache->encache_single_object($session_info["sess_id"], CACHE_TYPE_OPACEMPRSESSION, serialize($session_info));
		}
		return $session_info;
	}
	
	function login($empr_login, $empr_password) {
		$empr_id = 0;
		if (!$this->check_auth($empr_login, $empr_password, $empr_id))
			return 0;

		//Crééons la session
		$session_info = array();
		usleep(1);
		$session_info["sess_id"] = md5(microtime()).$empr_id;
		$session_info["empr_id"] = $empr_id;
		$session_info["login_date"] = time();
		$session_info["lastused_date"] = time();

		//Mettons la dans le cache
		$es_cache = new external_services_cache('es_cache_blob', 1200);
		$es_cache->encache_single_object($session_info["sess_id"], CACHE_TYPE_OPACEMPRSESSION, serialize($session_info));
		
		return $session_info["sess_id"];
	}
	
	function login_md5($empr_login, $empr_password) {
		$empr_id = 0;
		if (!$this->check_auth_md5($empr_login, $empr_password, $empr_id))
			return 0;

		//Crééons la session
		$session_info = array();
		usleep(1);
		$session_info["sess_id"] = md5(microtime()).$empr_id;
		$session_info["empr_id"] = $empr_id;
		$session_info["login_date"] = time();
		$session_info["lastused_date"] = time();

		//Mettons la dans le cache
		$es_cache = new external_services_cache('es_cache_blob', 1200);
		$es_cache->encache_single_object($session_info["sess_id"], CACHE_TYPE_OPACEMPRSESSION, serialize($session_info));
		
		return $session_info["sess_id"];
	}
	
	function logout($session_id) {
		if (!$session_id)
			return;

		$es_cache = new external_services_cache('es_cache_blob', 1200);
		$session_info = $es_cache->decache_single_object($session_id, CACHE_TYPE_OPACEMPRSESSION);
		if ($session_info !== false) {
			$es_cache->delete_single_object($session_id, CACHE_TYPE_OPACEMPRSESSION);
		}
	}
	
	function get_account_info($session_id) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
			
		$result = array();
		$empr = new emprunteur($empr_id);
		$result["id"] = $empr->id;
		$result["cb"] = $empr->cb;
		$result["personal_information"] = array();
		$result["personal_information"]["firstname"] = utf8_normalize($empr->prenom);
		$result["personal_information"]["lastname"] = utf8_normalize($empr->nom);
		$result["personal_information"]["address_part1"] = utf8_normalize($empr->adr1);
		$result["personal_information"]["address_part2"] = utf8_normalize($empr->adr2);
		$result["personal_information"]["address_cp"] = utf8_normalize($empr->cp);
		$result["personal_information"]["address_city"] = utf8_normalize($empr->ville);
		$result["personal_information"]["phone_number1"] = utf8_normalize($empr->tel1);
		$result["personal_information"]["phone_number2"] = utf8_normalize($empr->tel2);
		$result["personal_information"]["email"] = utf8_normalize($empr->mail);
		$result["personal_information"]["birthyear"] = utf8_normalize($empr->birth);
		$result["personal_information"]["sex"] = utf8_normalize($empr->sexe);
		$result["location_caption"] = utf8_normalize($empr->empr_location_l);
		$result["location_id"] = utf8_normalize($empr->empr_location);
		$result["adhesion_date"] = utf8_normalize($empr->date_adhesion);
		$result["expiration_date"] = utf8_normalize($empr->date_expiration);
		return $result;
	}
	
	function change_password($session_id, $old_password, $new_password) {
		global $dbh;
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$old_password = utf8_encode($old_password);
			$new_password = utf8_encode($new_password);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$old_password = utf8_decode($old_password);
			$new_password = utf8_decode($new_password);	
		}
		
		if (!$session_id || !$old_password || !$new_password)
			return 0;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;

		$empr = new emprunteur($empr_id);
		//Vérifions que le mot de passe fourni est le bon
		if ($empr->pwd != $old_password)
			return 0;

		//Pas de changement? On ne fait rien
		if ($old_password == $new_password)
			return true;

		//Changement
		$sql = "UPDATE empr SET empr_password = '".addslashes($new_password)."' WHERE id_empr = ".$empr_id;
		mysql_query($sql, $dbh);
		return mysql_error($dbh) == '';
	}
	
	function list_loans($session_id, $loan_type) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		$empr = new emprunteur($empr_id);
		
		switch ($loan_type) {
			case LIST_LOAN_LATE:
			case LIST_LOAN_CURRENT:
						$empr->fetch_info_suite();
				$results = array();
				foreach ($empr->prets as $apret) {
					if ($loan_type == LIST_LOAN_LATE && !$apret["pret_retard"])
						continue;
					$expl_object = new exemplaire($apret["cb"]);
					$aresult = array(
						"empr_id" => $empr_id,
						"notice_id" => $expl_object->id_notice,
						"bulletin_id" => $expl_object->id_bulletin,
						"expl_id" => $apret["id"],
						"expl_cb" => utf8_normalize($apret["cb"]),
						"expl_support" => utf8_normalize($apret["typdoc"]),
						"expl_location_id" => $expl_object->location_id,
						"expl_location_caption" => utf8_normalize($apret["location"]),
						"expl_section_id" => $expl_object->section_id,
						"expl_section_caption" => utf8_normalize($apret["section"]),
						"expl_libelle" => utf8_normalize(strip_tags($apret["libelle"])),
						"loan_startdate" => $apret["date_pret"],
						"loan_returndate" => $apret["date_retour"]
					);
					$results[] = $aresult;
				}
				break;
			case LIST_LOAN_PRECEDENT:
				$sql = "SELECT arc_expl_notice, arc_expl_bulletin, arc_expl_id, tdoc_libelle," ;
				$sql.= "group_concat(distinct date_format(arc_debut, '".$msg["format_date"]."') separator '<br />') as aff_pret_debut, ";
				$sql.= "group_concat(distinct date_format(arc_fin, '".$msg["format_date"]."') separator '<br />') as aff_pret_fin, ";
				$sql.= "trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if(mention_date, concat(' (',mention_date,')') ,if (date_date, concat(' (',date_format(date_date, '".$msg["format_date"]."'),')') ,'')))) as tit, if(notices_m.notice_id, notices_m.notice_id, notices_s.notice_id) as not_id ";
				$sql.= "FROM (((pret_archive LEFT JOIN notices AS notices_m ON arc_expl_notice = notices_m.notice_id ) ";
				$sql.= "        LEFT JOIN bulletins ON arc_expl_bulletin = bulletins.bulletin_id) ";
				$sql.= "        LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id) ";
				$sql.= "        LEFT JOIN docs_type ON docs_type.idtyp_doc = pret_archive.arc_expl_typdoc, ";
				$sql.= "        empr ";
				$sql.= "WHERE empr.id_empr = arc_id_empr and arc_id_empr='$empr_id' ";
				$sql.= "group by arc_expl_notice, arc_expl_bulletin, tit, not_id ";
				$sql.= "order by arc_debut desc";
				$res = mysql_query($sql, $dbh);
				while($row = mysql_fetch_assoc($res)) {
					$expl_object = new exemplaire('', $row["arc_expl_id"]);
					$expl_libelle="";
					if ($expl_object->id_bulletin) {
						$bulletin_display = new bulletinage_display($expl_object->id_bulletin);
						$expl_libelle = $bulletin_display->header;
					}
					else {
						$notice_display = new mono_display($expl_object->id_notice, 0);
						$expl_libelle = $notice_display->header;
					}
					$aresult = array(
						"empr_id" => $empr_id,
						"notice_id" => $expl_object->id_notice,
						"bulletin_id" => $expl_object->id_bulletin,
						"expl_id" => $row["arc_expl_id"],
						"expl_cb" => utf8_normalize($expl_object->cb),
						"expl_support" => utf8_normalize($row["tdoc_libelle"]),
						"expl_location_id" => $expl_object->location_id,
						"expl_location_caption" => utf8_normalize($expl_object->location),
						"expl_section_id" => $expl_object->section_id,
						"expl_section_caption" => utf8_normalize($expl_object->section),
						"expl_libelle" => utf8_normalize($expl_libelle),
						"loan_startdate" => $row["aff_pret_debut"],
						"loan_returndate" => $row["aff_pret_fin"]
					);
					$results[] = $aresult;
				}
				break;
		}

		return $results;
	}
	
	function list_resas($session_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		$results = array();
		$requete3 = "SELECT id_resa, resa_idempr, resa_idnotice, resa_idbulletin, resa_date, resa_date_fin, resa_cb, IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_fin, '".$msg["format_date"]."') as aff_date_fin, resa_loc_retrait, location_libelle FROM resa LEFT JOIN docs_location ON (idlocation = resa_loc_retrait) WHERE resa_idempr=".$empr_id;
		$result3 = @mysql_query($requete3, $dbh);
		while ($resa = mysql_fetch_array($result3)) {
			$message_null_resa="";
			$id_resa = $resa['id_resa'];
			$resa_idempr = $resa['resa_idempr'];
			$resa_idnotice = $resa['resa_idnotice'];
			$resa_idbulletin = $resa['resa_idbulletin'];
			$resa_date = $resa['resa_date'];
			$resa_retrait_location_id = $resa["resa_loc_retrait"];
			$resa_retrait_location = $resa["location_libelle"];

			if ($resa['resa_cb']) {
				$resa_dateend = $resa['aff_date_fin'];
			}
			else {
				$resa_dateend = "";
			}
			
			$rang = recupere_rang($resa_idempr, $resa_idnotice, $resa_idbulletin) ;

			$aresult = array(
				"resa_id" => $id_resa,
				"empr_id" => $empr_id,
				"notice_id" => $resa_idnotice,
				"bulletin_id" => $resa_idbulletin,
				"resa_rank" => $rang,
				"resa_dateend" => $resa_dateend,
				"resa_retrait_location" => utf8_normalize($resa_retrait_location),
				"resa_retrait_location_id" => $resa_retrait_location_id
			);
			$results[] = $aresult;
		}

		return $results;
	}
	
	function delete_resa($session_id, $resa_id) {
		global $dbh;
		if (!$session_id)
			return FALSE;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return FALSE;
			
		$resa_id += 0;
		if (!$resa_id)
			return FALSE;
			
		// *** Traitement de la suppression d'une résa affectée 
		$recup_id_resa = "select id_resa, resa_cb FROM resa WHERE resa_idempr=".$empr_id;
		$recup_id_resa .= " AND id_resa = $resa_id"; 
		$resrecup_id_resa = mysql_query($recup_id_resa, $dbh);
		$obj_recupidresa = mysql_fetch_object($resrecup_id_resa) ;
		$suppr_id_resa = $obj_recupidresa->id_resa ;
		
		// récup éventuelle du cb
		$cb_recup = $obj_recupidresa->resa_cb ;
		// archivage resa
		$rqt_arch = "UPDATE resa_archive, resa SET resarc_anulee = 1 WHERE id_resa = '".$suppr_id_resa."' AND resa_arc = resarc_id ";	
		mysql_query($rqt_arch, $dbh);
		// suppression
		$rqt = "delete from resa where id_resa='".$suppr_id_resa."' ";
		$res = mysql_query ($rqt, $dbh) ;
		$nb_resa_suppr = mysql_affected_rows() ;
		
		// réaffectation du doc éventuellement
		if ($cb_recup) {
			if (!affecte_cb ($cb_recup) && $cb_recup) {
				// cb non réaffecté, il faut transférer les infos de la résa dans la table des docs à ranger
				$rqt = "insert into resa_ranger (resa_cb) values ('".$cb_recup."') ";
				$res = mysql_query ($rqt, $dbh) ;
			}
		};
		return TRUE;
	}
	
	function list_suggestions($session_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		$empr = new emprunteur($empr_id);
		
		$sql = "select *, date_format(date_suggestion, '".$msg["format_date"]."') as aff_date, libelle_categ as sugg_category_caption, libelle_source as sugg_source_caption from suggestions_origine, suggestions LEFT JOIN suggestions_source ON suggestions_source.id_source = suggestions.sugg_source LEFT JOIN suggestions_categ ON suggestions_categ.id_categ = suggestions.num_categ where origine = '".$empr_id."' ";
		$sql .= "and type_origine = '1' ";
		$sql .= "and id_suggestion=num_suggestion order by date_suggestion ";
		$res = mysql_query($sql, $dbh);
		if (!$res)
			return array();

		$sug_map = new suggestions_map();

		$results = array();
		while($row = mysql_fetch_assoc($res)) {
			$sugg_state = $sug_map->getTextComment($row["statut"]);
			
			$aresult = array(
				"sugg_id" => $row["id_suggestion"],
				"sugg_date" => $row["aff_date"],
				"sugg_title" => utf8_normalize($row["titre"]),
				"sugg_author" => utf8_normalize($row["auteur"]),
				"sugg_editor" => utf8_normalize($row["editeur"]),
				"sugg_barcode" => utf8_normalize($row["code"]),
				"sugg_price" => utf8_normalize($row["prix"]),
				"sugg_url" => utf8_normalize($row["url_suggestion"]),
				"sugg_comment" => utf8_normalize($row["commentaires"]),
				"sugg_date" => utf8_normalize($row["date_publication"]),
				"sugg_source_caption" => utf8_normalize($row["sugg_source_caption"]),
				"sugg_source" => utf8_normalize($row["sugg_source"]),
				"sugg_category_caption" => utf8_normalize($row["sugg_category_caption"]),
				"sugg_category" => utf8_normalize($row["num_categ"]),
				"sugg_location" => utf8_normalize($row["sugg_location"]),
				"sugg_state" => utf8_normalize($row["statut"]),
				"sugg_state_caption" => utf8_normalize($sugg_state),
			);
			$results[] = $aresult;
		}
		return $results;
	}
	
	function add_review($session_id, $notice_id, $note, $comment, $subject) {
		global $dbh, $msg, $opac_avis_allow;
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$note = utf8_encode($note);
			$comment = utf8_encode($comment);
			$subject = utf8_encode($subject);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$note = utf8_decode($note);
			$comment = utf8_decode($comment);
			$subject = utf8_decode($subject);	
		}
		
		if (!$session_id)
			return 0;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;

		//Vérifions qu'on peut poster des avis:
		//Valeurs correctes: 1 2 3
		//Valeurs pas correctes: 0
		if ($opac_avis_allow == 0)
			return 0;
			
		//Vérifions que la notice demandée existe
		$notice_id+=0;
		$sql = "SELECT COUNT(1) > 0 FROM notices WHERE notice_id = ".$notice_id;
		$exists = mysql_result(mysql_query($sql, $dbh), 0, 0);
		if (!$exists)
			return 0;

		//Vérifions que la note est conforme:
		$note+=0;
		if (!$note)
			return 0;

		//Ajoutons l'avis:
		//Copié de /opac_css/avis.php
		$masque="@<[\/\!]*?[^<>]*?>@si";
		$commentaire = preg_replace($masque,'',$comment);
		$sql="insert into avis (num_empr,num_notice,note,sujet,commentaire) values ('$empr_id','$notice_id','$note','".addslashes($subject)."','".addslashes($comment)."')";
		$res = mysql_query($sql, $dbh);
		return $res != false ? 1 : 0;

		break;
	}
	
	function add_tag($session_id, $notice_id, $tag) {
		global $dbh, $msg, $opac_avis_allow;
		if (!$session_id)
			return 0;
			
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$tag = utf8_encode($tag);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$tag = utf8_decode($tag);
		}
			
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;

		//Vérifions qu'on peut poster des avis:
		//Valeurs correctes: 1 2
		//Valeurs pas correctes: 0
		if ($opac_allow_add_tag == 0)
			return 0;
			
		//Vérifions que la notice demandée existe
		$notice_id+=0;
		$sql = "SELECT COUNT(1) > 0 FROM notices WHERE notice_id = ".$notice_id;
		$exists = mysql_result(mysql_query($sql, $dbh), 0, 0);
		if (!$exists)
			return 0;

		//Vérifions que le tag existe
		if (!$tag)
			return 0;

		//Vérifions si le tag n'est pas déjà dans la notice
		$sql="select * from notices where index_l like '%".addslashes($tag)."%' and notice_id=$notice_id";
		$r = mysql_query($sql, $dbh);
		if (mysql_numrows($r)>=1)
			return 0;

		//Si tout va bien, on y va
		$sql="insert into tags (libelle, num_notice,user_code,dateajout) values ('".addslashes($tag)."',$notice_id,'". $empr_id ."',CURRENT_TIMESTAMP())";
		return mysql_query($sql, $dbh) != false ? 1 : 0;
	}
	
	function list_suggestion_categories($session_id) {
		global $dbh, $msg, $opac_sugg_categ;
		if (!$session_id)
			return 0;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;
		if (!$opac_sugg_categ)
			return array();

		$results = array();
		$sugg_categs = suggestions_categ::getCategList();
		foreach ($sugg_categs as $categ_id => $categ_caption) {
			$results[] = array(
				"category_id" => $categ_id,
				"category_caption" => utf8_normalize($categ_caption)
			);
		}
		return $results;
	}

	function list_suggestion_sources($session_id) {
		global $dbh, $msg, $opac_sugg_categ;
		if (!$session_id)
			return 0;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;
		if (!$opac_sugg_categ)
			return array();

		$req = "select * from suggestions_source order by libelle_source";
		$res=mysql_query($req,$dbh);
		$results = array();
		while ($row = mysql_fetch_object($res)){
			$results[] = array(
				"source_id" => $row->id_source,
				"source_caption" => utf8_normalize($row->libelle_source)
			);
		}
		return $results;
	}
	
	function list_suggestion_sources_and_categories($session_id) {
		return array(
			'sources' => $this->proxy_parent->pmbesOPACEmpr_list_suggestion_sources($session_id),
			'categories' => $this->proxy_parent->pmbesOPACEmpr_list_suggestion_categories($session_id),
		);
	}
	
	function list_locations($session_id) {
		global $dbh;
		if (!$session_id)
			return 0;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;

		return $this->proxy_parent->pmbesOPACGeneric_list_locations();
	}
	
	function add_suggestion($session_id, $title, $author, $editor, $isbn_or_ean, $price, $url, $comment, $sugg_categ, $sugg_location) {
		global $dbh, $msg;
		if (!$session_id)
			return 0;
			
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$title = utf8_encode($title);
			$author = utf8_encode($author);
			$editor = utf8_encode($editor);
			$isbn_or_ean = utf8_encode($isbn_or_ean);
			$price = utf8_encode($price);
			$url = utf8_encode($url);
			$comment = utf8_encode($comment);
			$sugg_categ = utf8_encode($sugg_categ);
			$sugg_location = utf8_encode($sugg_location);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$title = utf8_decode($title);
			$author = utf8_decode($author);
			$editor = utf8_decode($editor);
			$isbn_or_ean = utf8_decode($isbn_or_ean);
			$price = utf8_decode($price);
			$url = utf8_decode($url);
			$comment = utf8_decode($comment);
			$sugg_categ = utf8_decode($sugg_categ);
			$sugg_location = utf8_decode($sugg_location);
		}
			
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;

		$sug_map = new suggestions_map();
		global $opac_sugg_categ, $opac_sugg_categ_default;

		//copié de /opac_css/empr/make_sugg.inc.php
		//On évite de saisir 2 fois la même suggestion
		if (!suggestions::exists($empr_id, $title, $author, $editor, $isbn_or_ean)) {
			$su = new suggestions();
			$su->titre = $title;
			$su->editeur = $editor;
			$su->auteur = $author;
			$su->code = $isbn_or_ean;
			$price = str_replace(',','.',$price);
			if (is_numeric($price)) $su->prix = $price;
			$su->nb = 1;
			$su->statut = $sug_map->getFirstStateId();
			$su->url_suggestion = $url;
			$su->commentaires = $comment;
			$su->date_creation = today();
			
			if ($opac_sugg_categ == '1' ) {
				
				if (!suggestions_categ::exists($sugg_categ) ){
					$num_categ = $opac_sugg_categ_default;
				}
				if (!suggestions_categ::exists($num_categ) ) {
					$num_categ = '1';
				}
				$su->num_categ = $num_categ;	
			}
			$su->sugg_location=$sugg_location;
			$su->save();

			$orig = new suggestions_origine($empr_id, $su->id_suggestion);
			$orig->type_origine = 1;
			$orig->save();
			return true;
		}
		return 0;
	}
	
	function add_suggestion2($session_id, $suggestion) {
		global $dbh, $msg;
		if (!$session_id)
			return 0;

		$title = $suggestion['sugg_title'];
		$author = $suggestion['sugg_author'];
		$editor = $suggestion['sugg_editor'];
		$isbn_or_ean = $suggestion['sugg_barcode'];
		$price = $suggestion['sugg_price'];
		$url = $suggestion['sugg_url'];
		$comment = $suggestion['sugg_comment'];
		$sugg_categ = $suggestion['sugg_category'];
		$sugg_source = $suggestion['sugg_source'];
		$sugg_location = $suggestion['sugg_location'];
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$title = utf8_encode($suggestion['sugg_title']);
			$author = utf8_encode($suggestion['sugg_author']);
			$editor = utf8_encode($suggestion['sugg_editor']);
			$isbn_or_ean = utf8_encode($suggestion['sugg_barcode']);
			$price = utf8_encode($suggestion['sugg_price']);
			$url = utf8_encode($suggestion['sugg_url']);
			$comment = utf8_encode($suggestion['sugg_comment']);
			$sugg_categ = utf8_encode($suggestion['sugg_category']);
			$sugg_source = utf8_encode($suggestion['sugg_source']);
			$sugg_location = utf8_encode($suggestion['sugg_location']);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$title = utf8_decode($suggestion['sugg_title']);
			$author = utf8_decode($suggestion['sugg_author']);
			$editor = utf8_decode($suggestion['sugg_editor']);
			$isbn_or_ean = utf8_decode($suggestion['sugg_barcode']);
			$price = utf8_decode($suggestion['sugg_price']);
			$url = utf8_decode($suggestion['sugg_url']);
			$comment = utf8_decode($suggestion['sugg_comment']);
			$sugg_categ = utf8_decode($suggestion['sugg_category']);
			$sugg_source = utf8_decode($suggestion['sugg_source']);
			$sugg_location = utf8_decode($suggestion['sugg_location']);
		}
			
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;

		$sug_map = new suggestions_map();
		global $opac_sugg_categ, $opac_sugg_categ_default;

		//copié de /opac_css/empr/make_sugg.inc.php
		//On évite de saisir 2 fois la même suggestion
		if (!suggestions::exists($empr_id, $title, $author, $editor, $isbn_or_ean)) {
			$su = new suggestions();
			$su->titre = $title;
			$su->editeur = $editor;
			$su->auteur = $author;
			$su->code = $isbn_or_ean;
			$price = str_replace(',','.',$price);
			if (is_numeric($price)) $su->prix = $price;
			$su->nb = 1;
			$su->statut = $sug_map->getFirstStateId();
			$su->url_suggestion = $url;
			$su->commentaires = $comment;
			$su->date_creation = today();
			$su->sugg_src = $sugg_source;

			if ($opac_sugg_categ == '1' ) {
				
				if (!suggestions_categ::exists($sugg_categ) ){
					$sugg_categ = $opac_sugg_categ_default;
				}
				if (!suggestions_categ::exists($sugg_categ) ) {
					$sugg_categ = '1';
				}
				$su->num_categ = $sugg_categ;	
			}
			$su->sugg_location=$sugg_location;
			$su->save();

			$orig = new suggestions_origine($empr_id, $su->id_suggestion);
			$orig->type_origine = 1;
			$orig->save();
			return true;
		}
		return 0;
	}

	function edit_suggestion($session_id, $suggestion) {
		global $dbh, $msg;
		if (!$session_id)
			return FALSE;

		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;
			
		$id = $suggestion['sugg_id']+0;
		if (!$id)
			return FALSE;
			
		$exists = suggestions_origine::exists($empr_id, $id, 1);
		if (!$exists)
			return FALSE;
			
		$title = $suggestion['sugg_title'];
		$author = $suggestion['sugg_author'];
		$editor = $suggestion['sugg_editor'];
		$isbn_or_ean = $suggestion['sugg_barcode'];
		$price = $suggestion['sugg_price'];
		$url = $suggestion['sugg_url'];
		$comment = $suggestion['sugg_comment'];
		$sugg_categ = $suggestion['sugg_category'];
		$sugg_source = $suggestion['sugg_source'];
		$sugg_location = $suggestion['sugg_location'];
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$title = utf8_encode($suggestion['sugg_title']);
			$author = utf8_encode($suggestion['sugg_author']);
			$editor = utf8_encode($suggestion['sugg_editor']);
			$isbn_or_ean = utf8_encode($suggestion['sugg_barcode']);
			$price = utf8_encode($suggestion['sugg_price']);
			$url = utf8_encode($suggestion['sugg_url']);
			$comment = utf8_encode($suggestion['sugg_comment']);
			$sugg_categ = utf8_encode($suggestion['sugg_category']);
			$sugg_source = utf8_encode($suggestion['sugg_source']);
			$sugg_location = utf8_encode($suggestion['sugg_location']);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$title = utf8_decode($suggestion['sugg_title']);
			$author = utf8_decode($suggestion['sugg_author']);
			$editor = utf8_decode($suggestion['sugg_editor']);
			$isbn_or_ean = utf8_decode($suggestion['sugg_barcode']);
			$price = utf8_decode($suggestion['sugg_price']);
			$url = utf8_decode($suggestion['sugg_url']);
			$comment = utf8_decode($suggestion['sugg_comment']);
			$sugg_categ = utf8_decode($suggestion['sugg_category']);
			$sugg_source = utf8_decode($suggestion['sugg_source']);
			$sugg_location = utf8_decode($suggestion['sugg_location']);
		}
			
		$sug_map = new suggestions_map();
		global $opac_sugg_categ, $opac_sugg_categ_default;

		//copié de /opac_css/empr/make_sugg.inc.php
		//On évite de saisir 2 fois la même suggestion
		$su = new suggestions($id);
		$su->titre = $title;
		$su->editeur = $editor;
		$su->auteur = $author;
		$su->code = $isbn_or_ean;
		$price = str_replace(',','.',$price);
		if (is_numeric($price)) $su->prix = $price;
		$su->nb = 1;
		$su->statut = $sug_map->getFirstStateId();
		$su->url_suggestion = $url;
		$su->commentaires = $comment;
		$su->date_creation = today();
		$su->sugg_src = $sugg_source;

		if ($opac_sugg_categ == '1' ) {
			
			if (!suggestions_categ::exists($sugg_categ) ){
				$sugg_categ = $opac_sugg_categ_default;
			}
			if (!suggestions_categ::exists($sugg_categ) ) {
				$sugg_categ = '1';
			}
			$su->num_categ = $sugg_categ;	
		}
		$su->sugg_location=$sugg_location;
		$su->save();
		return true;
	}
	
	function delete_suggestion($session_id, $suggestion_id) {
		global $dbh;
		if (!$session_id)
			return FALSE;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return FALSE;
		
		$exists = suggestions_origine::exists($empr_id, $suggestion_id, 1);
		if (!$exists)
			return FALSE;

		$sugg = new suggestions($suggestion_id);
		if (!($sugg->sugg_origine_type == 1) && ($sugg->sugg_origine == $empr_id))
			return FALSE;
			
		$sugg->delete($suggestion_id);
		return TRUE;
	}
	
	function list_resa_locations($session_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		$empr = new emprunteur($empr_id);
		
		global $pmb_transferts_actif, $transferts_choix_lieu_opac, $pmb_location_reservation;
		if (($pmb_transferts_actif!="1")||($transferts_choix_lieu_opac!="1"))
			return array();

		$results=array();
		if($pmb_location_reservation) {			
			$loc_req="SELECT idlocation, location_libelle FROM docs_location WHERE location_visible_opac=1  and idlocation in (select resa_loc from resa_loc where resa_emprloc=".$empr->empr_location.") ORDER BY location_libelle ";
		} else {
			$loc_req="SELECT idlocation, location_libelle FROM docs_location WHERE location_visible_opac=1 ORDER BY location_libelle";
		}
		$res = mysql_query($loc_req);
		//on parcours la liste des localisations
		while ($value = mysql_fetch_array($res)) {
			$results[] = array(
				"location_id" => $value[0],
				"location_caption" => utf8_normalize($value[1])
			);
		}
		return $results;
	}
	
	function can_reserve_notice($session_id, $id_notice, $id_bulletin) {
		global $dbh, $msg;
		if (!$session_id)
			return FALSE;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return FALSE;
		$resa= new reservation($empr_id, $id_notice, $id_bulletin);
		return $resa->can_reserve();
	}
	
	//TODO: vérifier tous les comportements de cette fonction et y placer un mécanisme gestion des messages d'erreur
	function add_resa($session_id, $id_notice, $id_bulletin, $location) {
		global $dbh, $msg;
		$results=array();
		if (!$session_id){
			$results["error"]="no_session_id";
			$results["status"]= FALSE;
			return $results;
		}	
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id){
			$results["error"]="no_empr_id";
			$results["status"]= FALSE;
			return $results;
		}				
		$resa= new reservation($empr_id, $id_notice, $id_bulletin);
		if($resa->add($location) == FALSE) {
			$results["error"]=$resa->service->error;
			$results["message"]=$resa->service->message;
			$results["status"]= FALSE;
		} else $results["status"]=TRUE;
		return $results;
	}		
			

	
	function list_abonnements($session_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		$empr = new emprunteur($empr_id);

		global $opac_allow_resiliation, $opac_allow_bannette_priv;
		$results = array();
		
		$tableau_bannette_pub = tableau_gerer_bannette($empr_id, $empr->categ, "PUB");
		$tableau_bannette_priv = tableau_gerer_bannette($empr_id, $empr->categ, "PRI");
		$tableau_bannettes = array_merge($tableau_bannette_pub, $tableau_bannette_priv);
		$search = new search();
		foreach ($tableau_bannettes as $abanette) {
			// Construction de l'affichage de l'info bulle de la requette			
			$requete="select * from bannette_equation, equations where num_equation=id_equation and num_bannette=".$abanette["id_bannette"];	
			$resultat=mysql_query($requete);
			if (($r=mysql_fetch_object($resultat))) {				 
				$equ = new equation ($r->num_equation);
				$search->unserialize_search($equ->requete);
				$recherche = $search->make_human_query();
			}

			$a_abonnement = array(
				'abonnement_id' => $abanette["id_bannette"],
				'abonnement_type' => ($abanette["priv_pub"] == 'PUB' ? "PUBLIC" : 'PRIVATE'),
				'abonnement_title' => utf8_normalize($abanette["comment_public"]),
				'abonnement_lastsentdate' => utf8_normalize($abanette["aff_date_last_envoi"]),
				'abonnement_notice_count' => $abanette["nb_contenu"],
				'abonnement_equation_human' => utf8_normalize($recherche),
				'empr_subscriber' => ($abanette["priv_pub"] == 'PUB' ? ($abanette["abonn"] == 'checked') : true)
			);
			$results[] = $a_abonnement;
		}

		return $results;
		
	}
	
	function list_cart_content($session_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		$results = array();
		$sql = "SELECT session FROM opac_sessions WHERE empr_id = ".$empr_id;
		$res = mysql_query($sql, $dbh);
		if (mysql_num_rows($res)) {
			$row = mysql_fetch_assoc($res);
			$empr_session = unserialize($row["session"]);
			if (isset($empr_session["cart"])) {
				foreach ($empr_session["cart"] as $anotice_id) {
					$results[] = $anotice_id;
				}
			}
		}
		return $results;
	}
	
	function empty_cart($session_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		$results = array();
		$sql = "SELECT session FROM opac_sessions WHERE empr_id = ".$empr_id;
		$res = mysql_query($sql, $dbh);
		if (mysql_num_rows($res)) {
			$row = mysql_fetch_assoc($res);
			$empr_session = unserialize($row["session"]);
			$empr_session["cart"] = array();
			$new_row_session = serialize($empr_session);
			$sql_update = "UPDATE opac_sessions SET session ='".addslashes($new_row_session)."', date_rec = NOW() WHERE empr_id = ".$empr_id;
			mysql_query($sql_update, $dbh);
		}
		return $results;
	}
	
	function add_notices_to_cart($session_id, $notice_ids) {
		global $dbh, $msg;
		if (!$session_id)
			return 0;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;

		if (!is_array($notice_ids))
			return 0;
		//Enlevons ce qui n'est pas entier dans le tableau
		array_filter($notice_ids, create_function('$o', 'return $o+0;'));
		if (!$notice_ids)
			return 0;
			
		$sql = "SELECT session FROM opac_sessions WHERE empr_id = ".$empr_id;
		$res = mysql_query($sql, $dbh);
		if (mysql_num_rows($res)) {
			$row = mysql_fetch_assoc($res);
			$empr_session = unserialize($row["session"]);
			if (!isset($empr_session["cart"]))
				$empr_session["cart"] = array();

			//Vérifions que l'emprunteur a bien le droit de toucher les notices
			global $gestion_acces_active, $gestion_acces_empr_notice;
			if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
				$ac= new acces();
				$dom_2= $ac->setDomain(2);
				foreach ($notice_ids as $anotice_id) {
					$rights= $dom_2->getRights($empr_id, $anotice_id);
					if ($rights && !($rights & 4)) {
						$noticelist = array_diff($noticelist, array($anotice_id));
					}
				}
			}
				
			foreach ($notice_ids as $anotice_id) {
				$empr_session["cart"][] = $anotice_id;
			}
			$empr_session["cart"] = array_unique($empr_session["cart"]);

			global $opac_max_cart_items;
			$empr_session["cart"] = array_slice($empr_session["cart"], 0, $opac_max_cart_items);
			
			$new_row_session = serialize($empr_session);
			$sql_update = "UPDATE opac_sessions SET session ='".addslashes($new_row_session)."', date_rec = NOW() WHERE empr_id = ".$empr_id;
			mysql_query($sql_update, $dbh);
		}
		return true;
	}
	
	function delete_notices_from_cart($session_id, $notice_ids) {
		global $dbh, $msg;
		if (!$session_id)
			return 0;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return 0;

		if (!is_array($notice_ids))
			return 0;
		//Enlevons ce qui n'est pas entier dans le tableau
		array_filter($notice_ids, create_function('$o', 'return $o+0;'));
		if (!$notice_ids)
			return 0;
			
		$sql = "SELECT session FROM opac_sessions WHERE empr_id = ".$empr_id;
		$res = mysql_query($sql, $dbh);
		if (mysql_num_rows($res)) {
			$row = mysql_fetch_assoc($res);
			$empr_session = unserialize($row["session"]);
			if (!isset($empr_session["cart"]))
				$empr_session["cart"] = array();

			$empr_session["cart"] = array_diff($empr_session["cart"], $notice_ids);
			$new_row_session = serialize($empr_session);
			$sql_update = "UPDATE opac_sessions SET session ='".addslashes($new_row_session)."', date_rec = NOW() WHERE empr_id = ".$empr_id;
			mysql_query($sql_update, $dbh);
		}
		return true;
	}
	
	function list_shelves($session_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesOPACGeneric_list_shelves($empr_id);
	}
	
	function retrieve_shelf_content($session_id, $shelf_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesOPACGeneric_retrieve_shelf_content($shelf_id, $empr_id);
	}
	
	function simpleSearch($session_id, $searchType=0,$searchTerm="") {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_simpleSearch($searchType, $searchTerm, -1, $empr_id);
	}
	
	function simpleSearchLocalise($session_id, $searchType=0,$searchTerm="",$location,$section=0) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_simpleSearchLocalise($searchType, $searchTerm, -1, $empr_id,$location,$section);
	}

	function get_sort_types($session_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_get_sort_types();
	}
	
	function fetchSearchRecords($session_id, $searchId, $firstRecord, $recordCount, $recordFormat, $recordCharset='iso-8859-1') {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_fetchSearchRecords($searchId, $firstRecord, $recordCount, $recordFormat, $recordCharset, true, true);
	}
	
	function fetchSearchRecordsSorted($session_id, $searchId, $firstRecord, $recordCount, $recordFormat, $recordCharset='iso-8859-1', $sort_type="") {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsSorted($searchId, $firstRecord, $recordCount, $recordFormat, $recordCharset, true, true, $sort_type);
	}
	
	function fetchSearchRecordsArray($session_id, $searchId, $firstRecord, $recordCount, $recordCharset='iso-8859-1') {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsArray($searchId, $firstRecord, $recordCount, $recordCharset, true, true);
	}
	
	function fetchSearchRecordsArraySorted($session_id, $searchId, $firstRecord, $recordCount, $recordCharset='iso-8859-1', $sort_type="") {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsArraySorted($searchId, $firstRecord, $recordCount, $recordCharset, true, true, $sort_type);
	}
	
	function getAdvancedSearchFields($session_id, $fetch_values=false) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
			
		$empr = new emprunteur($empr_id);
		$lang = $empr->empr_lang;
		return $this->proxy_parent->pmbesSearch_getAdvancedSearchFields("opac|search_fields", $lang, $fetch_values);
	}
	
	function getAdvancedExternalSearchFields($session_id, $fetch_values=false) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
			
		$empr = new emprunteur($empr_id);
		$lang = $empr->empr_lang;
		
		return $this->proxy_parent->pmbesSearch_getAdvancedSearchFields("opac|search_fields_unimarc", $lang, $fetch_values);
	}
	
	function advancedSearch($session_id, $search_description) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_advancedSearch("opac|search_fields", $search_description, -1, $empr_id);
	}

	function advancedSearchExternal($session_id, $search_description, $source_ids) {
		global $dbh, $msg;
		if (!$session_id)
			return FALSE;
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return FALSE;
		
		array_walk($source_ids, create_function('&$a', '$a+=0;')); //Soyons sûr de ne stocker que des entiers dans le tableau.
		$source_ids = array_unique($source_ids);
		if (!$source_ids)
			return FALSE;
		return $this->proxy_parent->pmbesSearch_advancedSearch("opac|search_fields_unimarc|sources(".implode(',',$source_ids).")", $search_description, -1, 0);
	}
	
	function fetch_notice_items($session_id, $notice_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
			
		return $this->proxy_parent->pmbesItems_fetch_notice_items($notice_id, $empr_id);
	}

	function fetch_item($session_id, $item_cb='', $item_id='') {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
			
		return $this->proxy_parent->pmbesItems_fetch_item($item_cb, $item_id, $empr_id);
	}
	
	function listNoticeExplNums($session_id, $notice_id) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
			
		return $this->proxy_parent->pmbesNotices_listNoticeExplNums($notice_id, $empr_id);
	}
	
	function listBulletinExplNums($session_id, $bulletinId) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
			
		return $this->proxy_parent->pmbesNotices_listBulletinExplNums($bulletinId, $empr_id);
	}
	
	function fetchNoticeList($session_id, $noticelist, $recordFormat, $recordCharset) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		
		if (!is_array($noticelist))
			return array();
			
		//Vérifions que l'emprunteur a bien le droit de voir les notices
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			foreach ($noticelist as $anoticeid) {
				$rights= $dom_2->getRights($empr_id, $anoticeid);
				if ($rights && !($rights & 4)) {
					$noticelist = array_diff($noticelist, array($anoticeid));
				}
			}
		}

		return $this->proxy_parent->pmbesNotices_fetchNoticeList($noticelist, $recordFormat, $recordCharset, true, true);
	}
	
	function fetchExternalNoticeList($session_id, $noticelist, $recordFormat, $recordCharset) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		
		return $this->proxy_parent->pmbesNotices_fetchExternalNoticeList($noticelist, $recordFormat, $recordCharset);
	}
	
	function fetchNoticeListFull($session_id, $noticelist, $recordFormat, $recordCharset, $includeLinks) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		
		if (!is_array($noticelist))
			return array();
			
		//Vérifions que l'emprunteur a bien le droit de voir les notices
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			foreach ($noticelist as $anoticeid) {
				$rights= $dom_2->getRights($empr_id, $anoticeid);
				if ($rights && !($rights & 4)) {
					$noticelist = array_diff($noticelist, array($anoticeid));
				}
			}
		}

		if (!$noticelist)
			return array();
		
		$results = $this->proxy_parent->pmbesNotices_fetchNoticeListFull($noticelist, $recordFormat, $recordCharset, $includeLinks);
		
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			foreach($results as &$aresult) {
				$rights= $dom_2->getRights($empr_id, $aresult['noticeId']);
				if ($rights && !($rights & 16)) {
					$aresult['noticeExplNums'] = array();
				}
				if ($rights && !($rights & 8)) {
					$aresult['noticeItems'] = array();
				}				
			}
		}
		
		return $results;
	}
	
	function fetchNoticeByExplCb($session_id, $expl_cb, $recordFormat, $recordCharset) {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		
		return $this->proxy_parent->pmbesNotices_fetchNoticeByExplCb($empr_id,$expl_cb, $recordFormat, $recordCharset, true, true);
	}
	
	function findNoticeBulletinId($session_id,$noticeId) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		
		//Vérifions que l'emprunteur a bien le droit de voir les notices
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			$rights= $dom_2->getRights($empr_id, $noticeId);
			if (!($rights & 4)) {
				return 0;
			}
		}
		return $this->proxy_parent->pmbesNotices_findNoticeBulletinId($noticeId);
	}		
	
	function get_author_information_and_notices($session_id, $author_id) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		
		return $this->proxy_parent->pmbesAuthors_get_author_information_and_notices($author_id, $empr_id);
	}
	
	function get_collection_information_and_notices($session_id, $collection_id) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		return $this->proxy_parent->pmbesCollections_get_collection_information_and_notices($collection_id, $empr_id);
	}
	
	function get_subcollection_information_and_notices($session_id, $subcollection_id) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		return $this->proxy_parent->pmbesCollections_get_subcollection_information_and_notices($subcollection_id, $empr_id);
	}
	
	function get_publisher_information_and_notices($session_id, $publisher_id) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		return $this->proxy_parent->pmbesPublishers_get_publisher_information_and_notices($publisher_id, $empr_id);
	}
	
	function list_thesauri($session_id) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		return $this->proxy_parent->pmbesThesauri_list_thesauri($empr_id);
	}
	
	function fetch_thesaurus_node_full($session_id,$node_id) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		return $this->proxy_parent->pmbesThesauri_fetch_node_full($node_id, $empr_id);
	}
	
	function self_Checkout($session_id,$expl_cb){
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		return $this->proxy_parent->pmbesSelfServices_self_checkout($expl_cb,$empr_id);
	}
	
	function fetchNoticesCollstates($session_id,$serialIds){
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();	
		$serialIds;	
		//Vérifions que l'emprunteur a bien le droit de voir les notices
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			foreach ($serialIds as $anoticeid) {
				$rights= $dom_2->getRights($emprId, $anoticeid);
				if ($rights && !($rights & 4)) {
					$serialIds = array_diff($serialIds, array($anoticeid));
				}
			}
		}

		return $this->proxy_parent->pmbesNotices_fetchNoticeCollstates($serialIds,$emprId);
	}
	
	function fetch_notices_bulletins($session_id,$noticelist){
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();
		
		//Vérifions que l'emprunteur a bien le droit de voir les notices
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			foreach ($noticelist as $anoticeid) {
				$rights= $dom_2->getRights($emprId, $anoticeid);
				if ($rights && !($rights & 4)) {
					$noticelist = array_diff($noticelist, array($anoticeid));
				}
			}
		}
		
		return $this->proxy_parent->pmbesNotices_fetchNoticeCollstates($noticelist,$emprId);	
	}
	
	function fetchNoticeListFullWithBullId($session_id, $noticelist, $recordFormat, $recordCharset, $includeLinks=true) {
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();
		
		if (!is_array($noticelist))
			return array();
			
		//Vérifions que l'emprunteur a bien le droit de voir les notices
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			foreach ($noticelist as $anoticeid) {
				$rights= $dom_2->getRights($empr_id, $anoticeid);
				if ($rights && !($rights & 4)) {
					$noticelist = array_diff($noticelist, array($anoticeid));
				}
			}
		}

		if (!$noticelist)
			return array();
		
		$results = $this->proxy_parent->pmbesNotices_fetchNoticeListFullWithBullId($noticelist, $recordFormat, $recordCharset, $includeLinks);
		
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			foreach($results as &$aresult) {
				$rights= $dom_2->getRights($empr_id, $aresult['noticeId']);
				if ($rights && !($rights & 16)) {
					$aresult['noticeExplNums'] = array();
				}
				if ($rights && !($rights & 8)) {
					$aresult['noticeItems'] = array();
				}				
			}
		}
		
		return $results;
	}

	function fetchNoticesBulletinsList($session_id,$noticelist){
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();
		
		//Vérifions que l'emprunteur a bien le droit de voir les notices
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			foreach ($noticelist as $anoticeid) {
				$rights= $dom_2->getRights($emprId, $anoticeid);
				if ($rights && !($rights & 4)) {
					$noticelist = array_diff($noticelist, array($anoticeid));
				}
			}
		}
		
		return $this->proxy_parent->pmbesNotices_fetchNoticesBulletinsList($noticelist,$emprId);	
	}	
	
	function fetchSearchRecordsFull($session_id, $searchId, $firstRecord, $recordCount,  $recordCharset='iso-8859-1') {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsFull($searchId, $firstRecord, $recordCount,  $recordCharset, true, true);
	}
	
	function fetchSearchRecordsFullSorted($session_id, $searchId, $firstRecord, $recordCount,  $recordCharset='iso-8859-1', $sort_type="") {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsFullSorted($searchId, $firstRecord, $recordCount,  $recordCharset, true, true, $sort_type);
	}
	
	function fetchSearchRecordsFullWithBullId($session_id, $searchId, $firstRecord, $recordCount,  $recordCharset='iso-8859-1') {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsFullWithBullId($searchId, $firstRecord, $recordCount,  $recordCharset, true, true);
	}
	
	function fetchSearchRecordsFullWithBullIdSorted($session_id, $searchId, $firstRecord, $recordCount,  $recordCharset='iso-8859-1', $sort_type="") {
		global $dbh, $msg;
		if (!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$empr_id = $session_info["empr_id"];
		if (!$empr_id)
			return array();

		return $this->proxy_parent->pmbesSearch_fetchSearchRecordsFullSorted($searchId, $firstRecord, $recordCount,  $recordCharset, true, true, $sort_type);
	}
	
	function fetchSerialList($session_id) {
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();
		return $this->proxy_parent->pmbesNotices_fetchSerialList($emprId);
	}
	
	function listExternalSources($session_id) {
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();
		return $this->proxy_parent->pmbesSearch_listExternalSources($emprId);
	}
	
	function fetchBulletinListFull($session_id,$bulletinlist, $recordFormat, $recordCharset) {
		//TODO vérifier les droits sur les bulletins 
		return $this->proxy_parent->pmbesNotices_fetchBulletinListFull($bulletinlist, $recordFormat, $recordCharset);
	}
	
	function getReadingLists($session_id) {
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();
		global $dbh;
		$sql = "select * from opac_liste_lecture where num_empr='".$emprId."'";
		$res = mysql_query($sql);
		$empr = new emprunteur($emprId);

		$results = array();
		while($row = mysql_fetch_assoc($res)) {
			$aresult = array(
				'reading_list_id' => $row['id_liste'],
				'reading_list_name' => utf8_normalize($row['nom_liste']),
				'reading_list_caption' => utf8_normalize($row['description']),
				'reading_list_emprid' => $row['num_empr'],
				'reading_list_empr_caption' => utf8_normalize($empr->nom." ".$empr->prenom),
				'reading_list_confidential' => $row['confidential'],
				'reading_list_public' => $row['public'],
				'reading_list_readonly' => $row['read_only'],
				'reading_list_notice_ids' => $row['notices_associees'] ? explode(',', $row['notices_associees']) : array(),
			);
			$results[] = $aresult;
		}
		return $results;
	}
	
	function getPublicReadingLists($session_id) {
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();
		global $dbh;
		$sql = "select opac_liste_lecture.*, empr.empr_prenom, empr.empr_nom from opac_liste_lecture left join empr on empr.id_empr = opac_liste_lecture.num_empr where public=1";
		$res = mysql_query($sql);

		$results = array();
		while($row = mysql_fetch_assoc($res)) {
			$aresult = array(
				'reading_list_id' => $row['id_liste'],
				'reading_list_name' => utf8_normalize($row['nom_liste']),
				'reading_list_caption' => utf8_normalize($row['description']),
				'reading_list_emprid' => $row['num_empr'],
				'reading_list_empr_caption' => utf8_normalize($row['empr_nom']." ".$row['empr_prenom']),
				'reading_list_confidential' => $row['confidential'],
				'reading_list_public' => $row['public'],
				'reading_list_readonly' => $row['read_only'],
				'reading_list_notice_ids' => explode(',', $row['notices_associees']),
			);
			$results[] = $aresult;
		}
		return $results;
	}
	
	function addNoticesToReadingList($session_id, $list_id, $notice_ids) {
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();
		global $dbh;
		
		$list_id += 0;
		if (!$list_id)
			return FALSE;
		
		if (!is_array($notice_ids))
			$notice_ids = array($notice_ids);
			
		//Vérifions que l'emprunteur a bien le droit de voir les notices
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			foreach ($notice_ids as $anoticeid) {
				$rights= $dom_2->getRights($emprId, $anoticeid);
				if ($rights && !($rights & 4)) {
					$notice_ids = array_diff($notice_ids, array($anoticeid));
				}
			}
		}

		if (!$notice_ids)
			return FALSE;
			
		//Vérifions que l'utilisateur a bien le droit de modifier la liste
		$sql = "select * from opac_liste_lecture where id_liste = '".$list_id."' and num_empr='".$emprId."'";
		$res = mysql_query($sql);
		if (!mysql_num_rows($res))
			return FALSE;
			
		$list = mysql_fetch_assoc($res);
		$list_content = $list['notices_associees'] ? explode(',', $list['notices_associees']) : array();
		$list_content = array_unique(array_merge($list_content, $notice_ids));
		
		$sql = "update opac_liste_lecture set notices_associees = '".addslashes(implode(',', $list_content))."' where id_liste = ".$list_id;
		mysql_query($sql);
		return TRUE;
	}
	
	function removeNoticesFromReadingList($session_id, $list_id, $notice_ids) {
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();
		global $dbh;
		
		$list_id += 0;
		if (!$list_id)
			return FALSE;

		if (!$notice_ids)
			return FALSE;
			
		//Vérifions que l'utilisateur a bien le droit de modifier la liste
		$sql = "select * from opac_liste_lecture where id_liste = '".$list_id."' and num_empr='".$emprId."'";
		$res = mysql_query($sql);
		if (!mysql_num_rows($res))
			return FALSE;
			
		$list = mysql_fetch_assoc($res);
		$list_content = explode(',', $list['notices_associees']);
		$list_content = array_diff($list_content, $notice_ids);
		
		$sql = "update opac_liste_lecture set notices_associees = '".addslashes(implode(',', $list_content))."' where id_liste = ".$list_id;
		mysql_query($sql);
		return TRUE;
	}

	function emptyReadingList($session_id, $list_id) {
		if(!$session_id)
			return array();
		$session_info = $this->retrieve_session_information($session_id);
		$emprId = $session_info["empr_id"];
		if (!$emprId)
			return array();
		global $dbh;
		
		$list_id += 0;
		if (!$list_id)
			return FALSE;

		//Vérifions que l'utilisateur a bien le droit de modifier la liste
		$sql = "select * from opac_liste_lecture where id_liste = '".$list_id."' and num_empr='".$emprId."'";
		$res = mysql_query($sql);
		if (!mysql_num_rows($res))
			return FALSE;
		$list_content = array();
		
		$sql = "update opac_liste_lecture set notices_associees = '".addslashes(implode(',', $list_content))."' where id_liste = ".$list_id;
		mysql_query($sql);
		return TRUE;
	}
}


?>