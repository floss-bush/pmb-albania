<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmbesAuthors.class.php,v 1.3 2010-04-13 09:38:28 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/external_services.class.php");

class pmbesAuthors extends external_services_api_class {
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
	
	function list_author_notices($author_id, $OPACUserId=-1) {
		global $dbh;
		global $msg;
		$result = array();

		$author_id += 0;
		if (!$author_id)
			throw new Exception("Missing parameter: author_id");

		$rqt_auteurs = "select author_id as aut from authors where author_see='$author_id' and author_id!=0 ";
		$rqt_auteurs .= "union select author_see as aut from authors where author_id='$author_id' and author_see!=0 " ;
		$res_auteurs = mysql_query($rqt_auteurs, $dbh);
		$clause_auteurs = " in ('$author_id' ";
		while(($id_aut=mysql_fetch_object($res_auteurs))) {
			$clause_auteurs .= ", '".$id_aut->aut."' ";
			$rqt_auteursuite = "select author_id as aut from authors where author_see='$id_aut->aut' and author_id!=0 ";
			$res_auteursuite = mysql_query($rqt_auteursuite, $dbh);
			while(($id_autsuite=mysql_fetch_object($res_auteursuite))) $clause_auteurs .= ", '".$id_autsuite->aut."' "; 
		} 
		$clause_auteurs .= " ) " ;

		//droits d'acces emprunteur/notice
		$acces_j='';
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if (($OPACUserId != -1) && $gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			$acces_j = $dom_2->getJoin($OPACUserId,4,'notice_id');
		}
			
		if($acces_j) {
			$statut_j='';
			$statut_r='';
		} else {
			$statut_j=',notice_statut';
			$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($OPACUserId?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
		}
		
		$requete = "SELECT distinct notices.notice_id FROM notices $acces_j, responsability $statut_j ";
		$requete.= "where responsability_author $clause_auteurs and notice_id=responsability_notice $statut_r ";
		$requete.= "ORDER BY index_serie,tnvol,index_sew";
		
		$res = mysql_query($requete, $dbh);
		if ($res)
			while($row = mysql_fetch_assoc($res)) {
				$result[] = $row["notice_id"];
			}
	
		return $result;
	}
	
	function get_author_information($author_id) {
		global $dbh;
		global $msg;
		$result = array();

		$author_id += 0;
		if (!$author_id)
			throw new Exception("Missing parameter: author_id");
			
		$sql = "SELECT * FROM authors WHERE author_id = ".$author_id;
		$res = mysql_query($sql);
		if (!$res)
			throw new Exception("Not found: author_id = ".$author_id);
		$row = mysql_fetch_assoc($res);
		
		$result = array(
			"author_id" => $row["author_id"],
			"author_type" => $row["author_type"],
			"author_name" => utf8_normalize($row["author_name"]),
			"author_rejete" => utf8_normalize($row["author_rejete"]),
			"author_see" => $row["author_see"],
			"author_date" => utf8_normalize($row["author_date"]),
			"author_web" => utf8_normalize($row["author_web"]),
			"author_comment" => utf8_normalize($row["author_comment"]),
			"author_lieu" => utf8_normalize($row["author_lieu"]),
			"author_ville" => utf8_normalize($row["author_ville"]),
			"author_pays" => utf8_normalize($row["author_pays"]),
			"author_subdivision" => utf8_normalize($row["author_subdivision"]),
			"author_numero" => utf8_normalize($row["author_numero"])		
		);
		
		return $result;
	}
	
	function get_author_information_and_notices($author_id, $OPACUserId=-1) {
		$result = array(
			"information" => $this->get_author_information($author_id),
			"notice_ids" => $this->list_author_notices($author_id, $OPACUserId)
		);
		return $result;
	}

}




?>