<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmbesNotices.class.php,v 1.20 2010-09-24 10:41:38 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/external_services.class.php");

class pmbesNotices extends external_services_api_class {
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
	
	function fetchNoticeList($noticelist, $recordFormat, $recordCharset, $includeLinks, $includeItems) {
		$converter = new external_services_converter_notices(1, 600);
		$converter->set_params(array("include_links" => $includeLinks, "include_items" => $includeItems, "include_authorite_ids" => true));
		$notices = $converter->convert_batch($noticelist, $recordFormat, $recordCharset);
		$results = array();
		foreach($notices as $notice_id => $notice_content) {
			$results[] = array(
				'noticeId' => $notice_id,
				'noticeContent' => $notice_content
			);
		}
		return $results;
	}
	
	function fetchExternalNoticeList($noticelist, $recordFormat, $recordCharset) {
		$converter = new external_services_converter_external_notices(4, 600);
		$converter->set_params(array());
		$notices = $converter->convert_batch($noticelist, $recordFormat, $recordCharset);
		$results = array();
		foreach($notices as $notice_id => $notice_content) {
			$results[] = array(
				'noticeId' => $notice_id,
				'noticeContent' => $notice_content
			);
		}
		return $results;
	}
	

	function fetchNoticeListArray($noticelist, $recordCharset, $includeLinks, $includeItems) {
		$converter = new external_services_converter_notices(1, 600);
		$converter->set_params(array("include_links" => $includeLinks, "include_items" => $includeItems, "include_authorite_ids" => true));
		$keyed_results = $converter->convert_batch($noticelist, "raw_array", $recordCharset);
		$array_results = array_values($keyed_results);
		return $array_results;
	}
	
	
	function listNoticeExplNums($noticeId, $OPACUserId=-1) {
		global $dbh;
		global $opac_url_base;
		$noticeId += 0;
		
		//Si on nous fourni un $OPACUserId, vérifions que l'utilsateur fourni a bien le droit de voir les documents numériques
		if ($OPACUserId != -1) {
			global $gestion_acces_active, $gestion_acces_empr_notice;
			if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
				$ac= new acces();
				$dom_2= $ac->setDomain(2);
				$rights= $dom_2->getRights($OPACUserId, $noticeId);
				//Pas le droit de voir les exemplaires? plouf!
				if (!($rights & 16)) {
					return array();
				}
			}
		}

		$sql = "SELECT explnum_id, explnum_nom, explnum_mimetype, explnum_url FROM explnum WHERE explnum_notice = ".$noticeId;
		$res = mysql_query($sql, $dbh);

		$results = array();
		while($row = mysql_fetch_assoc($res)) {
			$aresult = array(
				"id" => $row["explnum_id"],
				"noticeId" => $noticeId,
				"bulletinId" => 0,
				"name" =>utf8_normalize( $row["explnum_nom"]),
				"mimetype" => utf8_normalize($row["explnum_mimetype"]),
				"url" => utf8_normalize($row["explnum_url"]),
				"downloadUrl" => utf8_normalize($opac_url_base."/doc_num.php?explnum_id=".$row["explnum_id"])
			);
			$results[] = $aresult;
		}

		return $results;
	}
	
	function listNoticesExplNums($notice_ids, $OPACUserId=-1) {
		global $dbh;
		global $opac_url_base;
		if (!$notice_ids)
			throw new Exception("Missing parameter: notice_ids");

		array_walk($notice_ids, create_function('&$a', '$a+=0;'));	//Virons ce qui n'est pas entier
		$notice_ids = array_unique($notice_ids);
		
		//Si on nous fourni un $OPACUserId, vérifions que l'utilsateur fourni a bien le droit de voir les documents numériques
		if ($OPACUserId != -1) {
			global $gestion_acces_active, $gestion_acces_empr_notice;
			if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
				$ac= new acces();
				$dom_2= $ac->setDomain(2);
				foreach($notice_ids as $anotice_id) {
					$rights= $dom_2->getRights($OPACUserId, $anotice_id);
					//Pas le droit de voir les exemplaires? plouf!
					if (!($rights & 16)) {
						$notice_ids = array_diff($notice_ids, array($anotice_id));
					}
				}
			}
		}

		$sql = "SELECT explnum_id, explnum_nom, explnum_mimetype, explnum_url, explnum_notice FROM explnum WHERE explnum_notice IN (".(implode(',', $notice_ids)).") order by explnum_notice";
		$res = mysql_query($sql, $dbh);

		$results = array();
		$current_noticeid = 0;
		$current_explnums = array();		
		while($row = mysql_fetch_assoc($res)) {
			if (!$current_noticeid)
				$current_noticeid = $row['explnum_notice'];
				
			if ($current_noticeid != $row['explnum_notice']){
				$results[] = array(
					'noticeid' => $current_noticeid,
					'explnums' => $current_explnums,
				);
				$current_explnums = array();
				$current_noticeid = $row['explnum_notice'];
			}
			
			$aresult = array(
				"id" => $row["explnum_id"],
				"noticeId" => $row['explnum_notice'],
				"bulletinId" => 0,
				"name" =>utf8_normalize( $row["explnum_nom"]),
				"mimetype" => utf8_normalize($row["explnum_mimetype"]),
				"url" => utf8_normalize($row["explnum_url"]),
				"downloadUrl" => utf8_normalize($opac_url_base."doc_num.php?explnum_id=".$row["explnum_id"])
			);
			$current_explnums[] = $aresult;
		}
		$results[] = array(
			'noticeid' => $current_noticeid,
			'explnums' => $current_explnums,
		);
		
		return $results;
	}
	
	function listBulletinExplNums($bulletinId, $OPACUserId=-1) {
		global $dbh;
		global $opac_url_base;
		$bulletinId += 0;
		
		//TODO: Vérifier les droits de voir les bulletins, si applicable
		
		$sql = "SELECT explnum_id, explnum_nom, explnum_mimetype, explnum_url FROM explnum WHERE explnum_bulletin = ".$bulletinId;
		$res = mysql_query($sql, $dbh);

		$results = array();
		while($row = mysql_fetch_assoc($res)) {
			$aresult = array(
				"id" => $row["explnum_id"],
				"bulletinId" => $bulletinId,
				"noticeId" => 0,
				"name" => utf8_normalize($row["explnum_nom"]),
				"mimetype" => utf8_normalize($row["explnum_mimetype"]),
				"url" => utf8_normalize($row["explnum_url"]),
				"downloadUrl" => utf8_normalize($opac_url_base."/doc_num.php?explnum_id=".$row["explnum_id"])
			);
			$results[] = $aresult;
		}

		return $results;
	}
	
	function listBulletinsExplNums($bulletin_ids, $OPACUserId=-1) {
		global $dbh;
		global $opac_url_base;
		if (!$bulletin_ids)
			throw new Exception("Missing parameter: bulletin_ids");

		//TODO: Vérifier les droits de voir les bulletins, si applicable
			
		array_walk($bulletin_ids, create_function('&$a', '$a+=0;'));	//Virons ce qui n'est pas entier
		$bulletin_ids = array_unique($bulletin_ids);
		if (!$bulletin_ids)
			return array();
		
		$sql = "SELECT explnum_id, explnum_nom, explnum_mimetype, explnum_url, explnum_bulletin FROM explnum WHERE explnum_bulletin IN (".implode(',', $bulletin_ids).') ORDER BY explnum_bulletin';
		$res = mysql_query($sql, $dbh);

		$results = array();
		$current_id = 0;
		$current_explnums = array();
		while($row = mysql_fetch_assoc($res)) {
			if (!$current_id)
				$current_id = $row["explnum_bulletin"];

			if ($current_id != $row["explnum_bulletin"]) {
				$results[] = array(
					'bulletin_id' => $current_id,
					'bulletin_explnums' => $current_explnums,
				);
				$current_explnums = array();
				$current_id = $row["explnum_bulletin"];
			}
			$aresult = array(
				"id" => $row["explnum_id"],
				"noticeId" => 0,
				"bulletinId" => $current_id,
				"name" => utf8_normalize($row["explnum_nom"]),
				"mimetype" => utf8_normalize($row["explnum_mimetype"]),
				"url" => utf8_normalize($row["explnum_url"]),
				"downloadUrl" => utf8_normalize($opac_url_base."/doc_num.php?explnum_id=".$row["explnum_id"])
			);
			$current_explnums[] = $aresult;
		}
		$results[] = array(
			'bulletin_id' => $current_id,
			'bulletin_explnums' => $current_explnums,
		);

		return $results;
	}
	
	function fetchNoticeByExplCb($emprId,$explCb, $recordFormat, $recordCharset, $includeLinks, $includeItems) {
		global $dbh;
		$sql = "SELECT expl_notice FROM exemplaires WHERE expl_cb LIKE '$explCb'";
		$res = mysql_query($sql, $dbh);
		$results= array();
		$noticelist = array();
		if(mysql_num_rows($res)){
			$noticelist[0]= mysql_result($res,0,0);
		}
		
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
		return $this->proxy_parent->pmbesNotices_fetchNoticeListFull($noticelist, $recordFormat, $recordCharset, $includeLinks);
	}
	
	function fetchNoticeListFull($noticelist, $recordFormat, $recordCharset, $includeLinks) {
		$results = array();
		$notices_ = $this->proxy_parent->pmbesNotices_fetchNoticeList($noticelist, $recordFormat, $recordCharset, $includeLinks, false);
		$notices = array();
		foreach($notices_ as $anotice) {
			$notices[$anotice['noticeId']] = $anotice['noticeContent'];
		}
		$items = $this->proxy_parent->pmbesItems_fetch_notices_items($noticelist, -1);
		$expl_nums = $this->proxy_parent->pmbesNotices_listNoticesExplNums($noticelist, -1);
		$bulletins = $this->proxy_parent->pmbesNotices_fetch_notices_bulletins($noticelist, -1);
		$collstates = $this->proxy_parent->pmbesNotices_fetchNoticesCollstates($noticelist,-1);
		
		foreach($notices as $notice_id => $notice_content) {
			$aresult = array();
			$aresult['noticeId'] = $notice_id;
			$aresult['noticeContent'] = $notice_content;
			$aresult['noticeItems'] = array();
			$aresult['noticeExplNums'] = array();
			$aresult['noticeBulletins'] = array();
			$aresult['noticeCollstates'] = array();
			foreach($items as $key => $aitem) {
				if ($aitem['noticeid'] == $notice_id) {
					$aresult['noticeItems'] = $aitem['items'];
					unset($items[$key]);
					break;
				}
			}
			foreach($expl_nums as $key => $aexplnum) {
				if ($aexplnum['noticeid'] == $notice_id) {
					$aresult['noticeExplNums'] = $aexplnum['explnums'];
					unset($expl_nums[$key]);
					break;
				}
			}
			foreach($bulletins as $key => $abulletin) {
				if ($abulletin['noticeid'] == $notice_id) {
					$aresult['noticeBulletins'] = $abulletin['bulletins'];
					unset($bulletins[$key]);
					break;
				}
			}
			foreach($collstates as $key => $acollstate) {
				if ($acollstate['noticeid'] == $notice_id) {
					$aresult['noticeCollstates'] = $acollstate['collstates'];
					unset($collstates[$key]);
					break;
				}
			}
			$results[] = $aresult;
		}
		return $results;
	}
	
	function fetch_bulletin_list($bulletin_ids) {
		global $dbh;
		global $msg;
		$result = array();

		if (!$bulletin_ids)
			throw new Exception("Missing parameter: bulletin_ids");

		array_walk($bulletin_ids, create_function('&$a', '$a+=0;'));	//Virons ce qui n'est pas entier
		$bulletin_ids = array_unique($bulletin_ids);
		if (!$bulletin_ids)
			return array();

		//TODO: Vérifier les droits de voir les bulletins, si applicable
		
		$sql = "SELECT bulletins.*,notices.tit1 FROM bulletins LEFT JOIN notices ON bulletin_notice = notice_id WHERE bulletin_id IN (".implode(',', $bulletin_ids).") ORDER BY bulletin_notice, date_date DESC";
		$res = mysql_query($sql);
		
		$current_noticeid = 0;
		$current_bulletins = array();
		while($row=mysql_fetch_assoc($res)) {
			$abulletin = array(
				'bulletin_id' => $row['bulletin_id'],
				'serial_id' => $row['bulletin_notice'],
				'serial_title' => $row['tit1'],
				'notice_id' => $row['num_notice'],
				'bulletin_numero' => utf8_normalize($row['bulletin_numero']),
				'bulletin_date_caption' => utf8_normalize($row['mention_date']),
				'bulletin_date' => utf8_normalize($row['date_date']),
				'bulletin_title' => utf8_normalize($row['bulletin_titre']),
				'bulletin_barcode' => utf8_normalize($row['bulletin_cb']),
			);
			$result[] = $abulletin;
		}

		return $result;
	}
	
	function fetchBulletinListFull($bulletinlist, $recordFormat, $recordCharset) {
		$results = array();
		$bulletins = $this->proxy_parent->pmbesNotices_fetch_bulletin_list($bulletinlist);
		$items = $this->proxy_parent->pmbesItems_fetch_bulletins_items($bulletinlist, -1);
		$expl_nums = $this->proxy_parent->pmbesNotices_listBulletinsExplNums($bulletinlist, -1);

		$notice_ids = array();
		foreach($bulletins as $bulletin_content) {
			if ($bulletin_content['notice_id'])
				$notice_ids[] = $bulletin_content['notice_id'];
		}
		
		$notices = $this->proxy_parent->pmbesNotices_fetchNoticeList($notice_ids, $recordFormat, $recordCharset, true, false);

		foreach($bulletins as $bulletin_content) {
			$aresult = array();
			$aresult['bulletin_id'] = $bulletin_content['bulletin_id'];
			$aresult['bulletin_notice'] = isset($notices[$bulletin_content['notice_id']]) ? $notices[$bulletin_content['notice_id']] : '';
			$aresult['bulletin_bulletin'] = $bulletin_content;
			$aresult['bulletin_items'] = array();
			$aresult['bulletin_doc_nums'] = array();
			foreach($items as $key => $aitem) {
				if ($aitem['bulletinid'] == $bulletin_content['bulletin_id']) {
					$aresult['bulletin_items'] = $aitem['items'];
					unset($items[$key]);
					break;
				}
			}
			foreach($expl_nums as $key => $aexplnum) {
				if ($aexplnum['bulletin_id'] == $bulletin_content['bulletin_id']) {
					$aresult['bulletin_doc_nums'] = $aexplnum['bulletin_explnums'];
					unset($expl_nums[$key]);
					break;
				}
			}
			global $dbh;
			$sql = 'SELECT analysis_notice FROM analysis WHERE analysis_bulletin = '.$bulletin_content['bulletin_id'];
			$res = mysql_query($sql, $dbh);
			$aresult['bulletin_analysis_notice_ids'] = array();
			while($row = mysql_fetch_row($res))
				$aresult['bulletin_analysis_notice_ids'][] = $row[0];
			$results[] = $aresult;
		}
		return $results;
	}
	
	function fetch_notices_bulletins($notice_ids, $OPACUserId=-1) {
		global $dbh;
		global $msg;
		$result = array();

		if (!$notice_ids)
			throw new Exception("Missing parameter: notice_ids");

		array_walk($notice_ids, create_function('&$a', '$a+=0;'));	//Virons ce qui n'est pas entier
		$notice_ids = array_unique($notice_ids);
		if (!$notice_ids)
			return array();

		//TODO: Vérifier les droits de voir les bulletins, si applicable
		
		$sql = "SELECT * FROM bulletins WHERE bulletin_notice IN (".implode(',', $notice_ids).") ORDER BY bulletin_notice, date_date DESC";
		$res = mysql_query($sql);
		
		$current_noticeid = 0;
		$current_bulletins = array();
		while($row=mysql_fetch_assoc($res)) {
			if (!$current_noticeid)
				$current_noticeid = $row['bulletin_notice'];
				
			if ($current_noticeid != $row['bulletin_notice']){
				$result[] = array(
					'noticeid' => $current_noticeid,
					'bulletins' => $current_bulletins,
				);
				$current_items = array();
				$current_noticeid = $row['expl_notice'];
			}

			$abulletin = array(
				'bulletin_id' => $row['bulletin_id'],
				'serial_id' => $row['bulletin_notice'],
				'bulletin_numero' => utf8_normalize($row['bulletin_numero']),
				'bulletin_date_caption' => utf8_normalize($row['mention_date']),
				'bulletin_date' => utf8_normalize($row['date_date']),
				'bulletin_title' => utf8_normalize($row['bulletin_titre']),
				'bulletin_barcode' => utf8_normalize($row['bulletin_cb']),
			);
			
			$current_bulletins[] = $abulletin;
		}

		$result[] = array(
			'noticeid' => $current_noticeid,
			'bulletins' => $current_bulletins,
		);

		return $result;
	}
	
	function findNoticeBulletinId($noticeId) {
		global $dbh;
		$noticeId += 0;
		if (!$noticeId)
			return 0;
		$sql = 'SELECT bulletin_id FROM bulletins WHERE num_notice = '.$noticeId.' LIMIT 1';
		$res = mysql_query($sql);
		if ($row = mysql_fetch_row($res))
			return $row[0];
		return 0;
	}
	
	function fetchNoticesCollstates($serial_ids, $OPACUserId=-1) {
		global $dbh;
		
		if (!$serial_ids)
			throw new Exception("Missing parameter: serial_ids");
	
		array_walk($serial_ids, create_function('&$a', '$a+=0;'));	//Virons ce qui n'est pas entier
		$serial_ids = array_unique($serial_ids);
		if (!$serial_ids)
			return array();

		$req="SELECT id_serial, collstate_id FROM arch_statut, collections_state LEFT JOIN docs_location ON location_id=idlocation LEFT JOIN arch_emplacement ON collstate_emplacement=archempla_id WHERE  
		id_serial IN (".implode(',', $serial_ids).") and archstatut_id=collstate_statut and ((archstatut_visible_opac=1 and archstatut_visible_opac_abon=0)".( $OPACUserId? " or (archstatut_visible_opac_abon=1 and archstatut_visible_opac=1)" : "").")
		 ORDER BY archempla_libelle, collstate_cote";	
		$myQuery = mysql_query($req, $dbh);
		
		//TODO: Vérifier les droits de voir les collstate, si applicable
		
		
		$current_noticeid = 0;
		$current_collstates = array();
		if((mysql_num_rows($myQuery))) {
			while(($row = mysql_fetch_object($myQuery))) {
				if (!$current_noticeid)
					$current_noticeid = $row->id_serial;
				
				if ($current_noticeid != $row->id_serial){
					$result[] = array(
						'noticeid' => $current_noticeid,
						'collstates' => $current_collstates,
					);
				}			
				
				$collstate=new collstate($row->collstate_id);
				$acollstate = array(
				'collstate_id' => $collstate->collstate_id,
				'collstate_location_libelle' => utf8_normalize($collstate->location_libelle),
				'collstate_cote' => utf8_normalize($collstate->cote),        
				'collstate_type_libelle' => utf8_normalize($collstate->type_libelle),
				'collstate_emplacement_libelle' => utf8_normalize($collstate->emplacement_libelle),
				'collstate_statut_opac_libelle' => utf8_normalize($collstate->statut_opac_libelle),
				'collstate_origine' => utf8_normalize($collstate->origine),
				'collstate_state_collections' => utf8_normalize($collstate->state_collections),
				'collstate_lacune' => utf8_normalize($collstate->lacune)
				);
				
				$current_collstates[] = $acollstate;
			}	
		}
		$result[] = array(
			'noticeid' => $current_noticeid,
			'collstates' => $current_collstates,
		);
	
		return $result;
	}
	
function fetchNoticeListFullWithBullId($noticelist, $recordFormat, $recordCharset, $includeLinks) {
		$results = array();
		$notices_ = $this->proxy_parent->pmbesNotices_fetchNoticeList($noticelist, $recordFormat, $recordCharset, $includeLinks, false);
		$notices = array();
		foreach($notices_ as $anotice) {
			$notices[$anotice['noticeId']] = $anotice['noticeContent'];
		}
		$items = $this->proxy_parent->pmbesItems_fetch_notices_items($noticelist, -1);
		$expl_nums = $this->proxy_parent->pmbesNotices_listNoticesExplNums($noticelist, -1);
		$bulletins = $this->proxy_parent->pmbesNotices_fetchNoticesBulletinsList($noticelist, -1);
		$collstates = $this->proxy_parent->pmbesNotices_fetchNoticesCollstates($noticelist,-1);

		foreach($notices as $notice_id => $notice_content) {
			$aresult = array();
			$aresult['noticeId'] = $notice_id;
			$aresult['noticeContent'] = $notice_content;
			$aresult['noticeItems'] = array();
			$aresult['noticeExplNums'] = array();
			$aresult['noticeBulletinIds'] = array();
			$aresult['noticeCollstates'] = array();
			foreach($items as $key => $aitem) {
				if ($aitem['noticeid'] == $notice_id) {
					$aresult['noticeItems'] = $aitem['items'];
					unset($items[$key]);
					break;
				}
			}
			foreach($expl_nums as $key => $aexplnum) {
				if ($aexplnum['noticeid'] == $notice_id) {
					$aresult['noticeExplNums'] = $aexplnum['explnums'];
					unset($expl_nums[$key]);
					break;
				}
			}
			foreach($bulletins as $key => $abulletin) {
				if ($abulletin['noticeid'] == $notice_id) {
					$aresult['noticeBulletinIds'] = $abulletin['bulletinids'];
					unset($bulletins[$key]);
					break;
				}
			}
			foreach($collstates as $key => $acollstate) {
				if ($acollstate['noticeid'] == $notice_id) {
					$aresult['noticeCollstates'] = $acollstate['collstates'];
					unset($collstates[$key]);
					break;
				}
			}
			$results[] = $aresult;
		}
		return $results;
	}

	function fetchNoticesBulletinsList($notice_ids,$OPACUserId=-1) {
		global $dbh;
		global $msg;
		$result = array();
		
		if (!$notice_ids)
			throw new Exception("Missing parameter: notice_ids");
	
		array_walk($notice_ids, create_function('&$a', '$a+=0;'));	//Virons ce qui n'est pas entier
		$notice_ids = array_unique($notice_ids);
		if (!$notice_ids)
			return array();

		//TODO: Vérifier les droits de voir les bulletins, si applicable
		
		$sql = "SELECT bulletin_id,bulletin_notice FROM bulletins WHERE bulletin_notice IN (".implode(',', $notice_ids).")  ORDER BY bulletin_notice,date_date DESC";
		$res = mysql_query($sql);
		
		$current_noticeid = 0;
		$current_bulletinIds = array();
		while($row=mysql_fetch_assoc($res)) {
			if (!$current_noticeid)
				$current_noticeid = $row['bulletin_notice'];
				
			if ($current_noticeid != $row['bulletin_notice']){
				$result[] = array(
					'noticeid' => $current_noticeid,
					'bulletinids' => $current_bulletinIds,
				);
			}
			
			$current_bulletinIds[] = $row['bulletin_id'];
		}
		
		$result[] = array(
			'noticeid' => $current_noticeid,
			'bulletinids' => $current_bulletinIds,
		);
		
		return $result;
	}
	
	function fetchSerialList($OPACUserId=-1) {
		global $dbh;
		$sql = "
SELECT n1.notice_id,
       n1.tit1,
       (SELECT COUNT(1)
        FROM   notices n2
               RIGHT JOIN bulletins b2
                 ON ( n2.notice_id = b2.bulletin_notice )
        WHERE  n2.notice_id = n1.notice_id) AS issue_count,
       (SELECT COUNT(1)
        FROM   notices n3
               RIGHT JOIN bulletins b3
                 ON ( n3.notice_id = b3.bulletin_notice )
               RIGHT JOIN analysis a3
                 ON ( b3.bulletin_id = a3.analysis_bulletin )
        WHERE  n3.notice_id = n1.notice_id) AS analysis_count,
       (SELECT COUNT(1)
        FROM   notices n4
               RIGHT JOIN bulletins b4
                 ON ( n4.notice_id = b4.bulletin_notice )
               RIGHT JOIN exemplaires e4
                 ON ( b4.bulletin_id = e4.expl_bulletin )
        WHERE  n4.notice_id = n1.notice_id) AS item_count
FROM   notices n1
WHERE  n1.niveau_biblio = 's'
       AND n1.niveau_hierar = 1
ORDER  BY tit1  ";
		$res = mysql_query($sql, $dbh);
		$results = array();
		while($row = mysql_fetch_assoc($res)) {
			$aresult = array(
				'serial_id' => $row['notice_id'],
				'serial_title' => utf8_normalize($row['tit1']),
				'serial_issues_count' => $row['issue_count'],
				'serial_items_count' => $row['item_count'],
				'serial_analysis_count' => $row['analysis_count'],
			);
			$results[] = $aresult;
		}
		return $results;
	}
}



?>