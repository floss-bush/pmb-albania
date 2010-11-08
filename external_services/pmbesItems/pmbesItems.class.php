<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmbesItems.class.php,v 1.7 2010-07-29 12:50:54 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/external_services.class.php");

class pmbesItems extends external_services_api_class {
	var $error=false;		//Y-a-t-il eu une erreur
	var $error_message="";	//Message correspondant à l'erreur
	var $es;				//Classe mère qui implémente celle-ci !
	var $msg;
	
	function restore_general_config() {
		
	}
	
	function form_general_config() {
		return false;
	}
	
	function save_general_config() {
		
	}
	
	function fetch_notice_items($notice_id, $OPACUserId=-1) {
		global $dbh;
		global $msg;
		$result = array();

		$notice_id += 0;
		if (!$notice_id)
			throw new Exception("Missing parameter: notice_id");

		//Si on a un OPACUserId, vérifions si cet emprunteur à le droit de voir les exemplaires des notices
		if ($OPACUserId != -1) {
			global $gestion_acces_active, $gestion_acces_empr_notice;
			if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
				$ac= new acces();
				$dom_2= $ac->setDomain(2);
				$rights= $dom_2->getRights($OPACUserId, $notice_id);
				//Pas le droit de voir les exemplaires? plouf!
				if (!($rights & 8)) {
					return array();
				}
			}
		}

		$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, tdoc_libelle, ";
		$requete .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
		$requete .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
		$requete .= " IF(pret_retour>sysdate(),0,1) as retard " ;
		$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl ";
		$requete .= " left join docs_location on exemplaires.expl_location=docs_location.idlocation ";
		$requete .= " left join docs_section on exemplaires.expl_section=docs_section.idsection ";
		$requete .= " left join docs_statut on exemplaires.expl_statut=docs_statut.idstatut ";
		$requete .= " left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc  ";
		$requete .= " WHERE expl_notice=$notice_id ";
		$requete .= " order by location_libelle, section_libelle, expl_cote, expl_cb ";

		$res = mysql_query($requete, $dbh);
		while($row=mysql_fetch_assoc($res)) {
			$expl = new exemplaire('', $row["expl_id"]);

			if($row["pret_retour"]) {
				// exemplaire sorti
				$rqt_empr = "SELECT empr_nom, empr_prenom, id_empr, empr_cb FROM empr WHERE id_empr='".$row["pret_idempr"]."' ";
				$res_empr = mysql_query ($rqt_empr, $dbh) ;
				$res_empr_obj = mysql_fetch_object ($res_empr) ;
				$situation = "<strong>${msg[358]} ".$row["aff_pret_retour"]."</strong>";
			} else {
				// tester si réservé 						
				$result_resa = mysql_query("select 1 from resa where resa_cb='".addslashes($row["expl_cb"])."' ", $dbh);
				$reserve = mysql_num_rows($result_resa);

				if ($reserve) $situation = $msg['expl_reserve']; // exemplaire réservé
				elseif ($row["pret_flag"]) $situation = "${msg[359]}"; // exemplaire disponible
				else $situation = "";
			}
			
			$expl_return=array();
			$expl_return["id"] = $expl->expl_id;
			$expl_return["cb"] = $expl->cb;
			$expl_return["cote"] = $expl->cote;
			$expl_return["location_id"] = $expl->location_id;
			$expl_return["location_caption"] = utf8_normalize($row["location_libelle"]);
			$expl_return["section_id"] = $expl->section_id;
			$expl_return["section_caption"] = utf8_normalize($row["section_libelle"]);
			$expl_return["support"] = utf8_normalize($row["tdoc_libelle"]);
			$expl_return["statut"] = utf8_normalize($row["statut_libelle"]);
			$expl_return["situation"] = utf8_normalize(strip_tags($situation));
			
			$result[] = $expl_return;
		}
		
		return $result;
	}
	
		function fetch_notices_items($notice_ids, $OPACUserId=-1) {
		global $dbh;
		global $msg;
		$result = array();

		if (!$notice_ids)
			throw new Exception("Missing parameter: notice_ids");

		array_walk($notice_ids, create_function('&$a', '$a+=0;'));	//Virons ce qui n'est pas entier
		$notice_ids = array_unique($notice_ids);

		//Si on a un OPACUserId, vérifions si cet emprunteur à le droit de voir les exemplaires des notices
		if ($OPACUserId != -1) {
			global $gestion_acces_active, $gestion_acces_empr_notice;
			if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
				$ac= new acces();
				$dom_2= $ac->setDomain(2);
				foreach($notice_ids as $anotice_id) {
					$rights= $dom_2->getRights($OPACUserId, $anotice_id);
					//Pas le droit de voir les exemplaires? plouf!
					if (!($rights & 8)) {
						$notice_ids = array_diff($notice_ids, array($anotice_id));
					}
				}
			}
		}

		$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, tdoc_libelle, ";
		$requete .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
		$requete .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
		$requete .= " IF(pret_retour>sysdate(),0,1) as retard " ;
		$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl ";
		$requete .= " left join docs_location on exemplaires.expl_location=docs_location.idlocation ";
		$requete .= " left join docs_section on exemplaires.expl_section=docs_section.idsection ";
		$requete .= " left join docs_statut on exemplaires.expl_statut=docs_statut.idstatut ";
		$requete .= " left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc  ";
		$requete .= " WHERE expl_notice IN (".(implode(',', $notice_ids)).") ";
		$requete .= " order by expl_notice, location_libelle, section_libelle, expl_cote, expl_cb ";

		$res = mysql_query($requete, $dbh);
		$current_noticeid = 0;
		$current_items = array();
		while($row=mysql_fetch_assoc($res)) {
			if (!$current_noticeid)
				$current_noticeid = $row['expl_notice'];
				
			if ($current_noticeid != $row['expl_notice']){
				$result[] = array(
					'noticeid' => $current_noticeid,
					'items' => $current_items,
				);
				$current_items = array();
				$current_noticeid = $row['expl_notice'];
			}

			$expl = new exemplaire('', $row["expl_id"]);

			if($row["pret_retour"]) {
				// exemplaire sorti
				$rqt_empr = "SELECT empr_nom, empr_prenom, id_empr, empr_cb FROM empr WHERE id_empr='".$row["pret_idempr"]."' ";
				$res_empr = mysql_query ($rqt_empr, $dbh) ;
				$res_empr_obj = mysql_fetch_object ($res_empr) ;
				$situation = "<strong>${msg[358]} ".$row["aff_pret_retour"]."</strong>";
			} else {
				// tester si réservé 						
				$result_resa = mysql_query("select 1 from resa where resa_cb='".addslashes($row["expl_cb"])."' ", $dbh);
				$reserve = mysql_num_rows($result_resa);

				if ($reserve) $situation = $msg['expl_reserve']; // exemplaire réservé
				elseif ($row["pret_flag"]) $situation = "${msg[359]}"; // exemplaire disponible
				else $situation = "";
			}
			
			$expl_return=array();
			$expl_return["id"] = $expl->expl_id;
			$expl_return["cb"] = $expl->cb;
			$expl_return["cote"] = $expl->cote;
			$expl_return["location_id"] = $expl->location_id;
			$expl_return["location_caption"] = utf8_normalize($row["location_libelle"]);
			$expl_return["section_id"] = $expl->section_id;
			$expl_return["section_caption"] = utf8_normalize($row["section_libelle"]);
			$expl_return["support"] = utf8_normalize($row["tdoc_libelle"]);
			$expl_return["statut"] = utf8_normalize($row["statut_libelle"]);
			$expl_return["situation"] = utf8_normalize(strip_tags($situation));
			
			$current_items[] = $expl_return;
		}

		$result[] = array(
			'noticeid' => $current_noticeid,
			'items' => $current_items,
		);
		$current_items = array();
		$current_noticeid = $row['expl_notice'];		
		
		return $result;
	}
	
		function fetch_bulletins_items($bulletin_ids, $OPACUserId=-1) {
		global $dbh;
		global $msg;
		$result = array();

		if (!$bulletin_ids)
			throw new Exception("Missing parameter: bulletin_ids");

		array_walk($bulletin_ids, create_function('&$a', '$a+=0;'));	//Virons ce qui n'est pas entier
		$bulletin_ids = array_unique($bulletin_ids);

		//TODO: Vérifier les droits des exemplaires des bulletins

		$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, tdoc_libelle, ";
		$requete .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
		$requete .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
		$requete .= " IF(pret_retour>sysdate(),0,1) as retard " ;
		$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl ";
		$requete .= " left join docs_location on exemplaires.expl_location=docs_location.idlocation ";
		$requete .= " left join docs_section on exemplaires.expl_section=docs_section.idsection ";
		$requete .= " left join docs_statut on exemplaires.expl_statut=docs_statut.idstatut ";
		$requete .= " left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc  ";
		$requete .= " WHERE expl_bulletin IN (".(implode(',', $bulletin_ids)).") ";
		$requete .= " order by expl_bulletin, location_libelle, section_libelle, expl_cote, expl_cb ";

		$res = mysql_query($requete, $dbh);
		$current_bulletinid = 0;
		$current_items = array();
		while($row=mysql_fetch_assoc($res)) {
			if (!$current_bulletinid)
				$current_bulletinid = $row['expl_bulletin'];
				
			if ($current_bulletinid != $row['expl_bulletin']){
				$result[] = array(
					'bulletinid' => $current_bulletinid,
					'items' => $current_items,
				);
				$current_items = array();
				$current_bulletinid = $row['expl_bulletin'];
			}

			$expl = new exemplaire('', $row["expl_id"]);

			if($row["pret_retour"]) {
				// exemplaire sorti
				$rqt_empr = "SELECT empr_nom, empr_prenom, id_empr, empr_cb FROM empr WHERE id_empr='".$row["pret_idempr"]."' ";
				$res_empr = mysql_query ($rqt_empr, $dbh) ;
				$res_empr_obj = mysql_fetch_object ($res_empr) ;
				$situation = "<strong>${msg[358]} ".$row["aff_pret_retour"]."</strong>";
			} else {
				// tester si réservé 						
				$result_resa = mysql_query("select 1 from resa where resa_cb='".addslashes($row["expl_cb"])."' ", $dbh);
				$reserve = mysql_num_rows($result_resa);

				if ($reserve) $situation = $msg['expl_reserve']; // exemplaire réservé
				elseif ($row["pret_flag"]) $situation = "${msg[359]}"; // exemplaire disponible
				else $situation = "";
			}
			
			$expl_return=array();
			$expl_return["id"] = $expl->expl_id;
			$expl_return["cb"] = $expl->cb;
			$expl_return["cote"] = $expl->cote;
			$expl_return["location_id"] = $expl->location_id;
			$expl_return["location_caption"] = utf8_normalize($row["location_libelle"]);
			$expl_return["section_id"] = $expl->section_id;
			$expl_return["section_caption"] = utf8_normalize($row["section_libelle"]);
			$expl_return["support"] = utf8_normalize($row["tdoc_libelle"]);
			$expl_return["statut"] = utf8_normalize($row["statut_libelle"]);
			$expl_return["situation"] = utf8_normalize(strip_tags($situation));
			
			$current_items[] = $expl_return;
		}

		$result[] = array(
			'bulletinid' => $current_bulletinid,
			'items' => $current_items,
		);
		$current_items = array();
		$current_bulletinid = $row['expl_bulletin'];		
		
		return $result;
	}	
	
	function fetch_item($item_cb='', $item_id='') {
		global $dbh;
		global $msg;
		
		global $charset;
		if ($this->proxy_parent->input_charset!='utf-8' && $charset == 'utf-8') {
			$item_cb = utf8_encode($item_cb);
		}
		else if ($this->proxy_parent->input_charset=='utf-8' && $charset != 'utf-8') {
			$item_cb = utf8_decode($item_cb);	
		}
		
		$empty_result = array(
			'id' => 0,
			'cb' => '',
			'cote' => '',
			'location' => '',
			'section' => '',
			'support' => '',
			'statut' => '',
			'situation' => ''
		);
		
		$item_id += 0;
		$wheres = array();
		if ($item_cb) {
			$wheres[] = "expl_cb = '".addslashes($item_cb)."'";
		}
		if ($item_id) {
			$wheres[] = "expl_id = ".$item_id."";
		}
		
		$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, tdoc_libelle, ";
		$requete .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
		$requete .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
		$requete .= " IF(pret_retour>sysdate(),0,1) as retard " ;
		$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl ";
		$requete .= " left join docs_location on exemplaires.expl_location=docs_location.idlocation ";
		$requete .= " left join docs_section on exemplaires.expl_section=docs_section.idsection ";
		$requete .= " left join docs_statut on exemplaires.expl_statut=docs_statut.idstatut ";
		$requete .= " left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc  ";
		$requete .= " WHERE 1 and ".implode(' and ', $wheres);
		
		$res = mysql_query($requete, $dbh);
		if (!mysql_numrows($res))
			return $empty_result;
		
		$row=mysql_fetch_assoc($res);
		$expl = new exemplaire('', $row["expl_id"]);

		if($row["pret_retour"]) {
			// exemplaire sorti
			$rqt_empr = "SELECT empr_nom, empr_prenom, id_empr, empr_cb FROM empr WHERE id_empr='".$row["pret_idempr"]."' ";
			$res_empr = mysql_query ($rqt_empr, $dbh) ;
			$res_empr_obj = mysql_fetch_object ($res_empr) ;
			$situation = "<strong>${msg[358]} ".$row["aff_pret_retour"]."</strong>";
		} else {
			// tester si réservé 						
			$result_resa = mysql_query("select 1 from resa where resa_cb='".addslashes($row["expl_cb"])."' ", $dbh);
			$reserve = mysql_num_rows($result_resa);

			if ($reserve) $situation = $msg['expl_reserve']; // exemplaire réservé
			elseif ($row["pret_flag"]) $situation = "${msg[359]}"; // exemplaire disponible
			else $situation = "";
		}
		
		$expl_return=array();
		$expl_return["id"] = $expl->expl_id;
		$expl_return["cb"] = $expl->cb;
		$expl_return["cote"] = $expl->cote;
		$expl_return["location_id"] = $expl->location_id;
		$expl_return["location_caption"] = utf8_normalize($row["location_libelle"]);
		$expl_return["section_id"] = $expl->section_id;
		$expl_return["section_caption"] = utf8_normalize($row["section_libelle"]);
		$expl_return["support"] = utf8_normalize($row["tdoc_libelle"]);
		$expl_return["statut"] = utf8_normalize($row["statut_libelle"]);
		$expl_return["situation"] = utf8_normalize($situation);

		
		return $expl_return;
	}
}




?>