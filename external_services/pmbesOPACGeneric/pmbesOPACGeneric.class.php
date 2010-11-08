<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmbesOPACGeneric.class.php,v 1.8 2010-07-29 12:53:11 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/external_services.class.php");

class pmbesOPACGeneric extends external_services_api_class{

	function list_shelves($OPACUserId) {
		global $dbh;

		global $opac_etagere_order ;
		if (!$opac_etagere_order) $opac_etagere_order =" name ";

		$tableau_etagere = array() ;
		// on constitue un tableau avec les étagères et les caddies associés
		$clause_accueil="visible_accueil=1 and";
		$query = "select idetagere, name, comment from etagere where $clause_accueil ( (validite_date_deb<=sysdate() and validite_date_fin>=sysdate()) or validite=1 ) order by $opac_etagere_order ";
		$result = mysql_query($query, $dbh);
		if (mysql_num_rows($result)) {
			while ($etagere=mysql_fetch_object($result)) {
				$tableau_etagere[] = array (
						'id' => $etagere->idetagere,
						'name' => utf8_normalize($etagere->name),
						'comment' => utf8_normalize($etagere->comment)
						);
			}
		}
		return $tableau_etagere;
	}
	
	function retrieve_shelf_content($shelf_id, $OPACUserId) {
		global $dbh;

		$shelf_id+=0;
		if (!$shelf_id)
			return array();

		//droits d'acces emprunteur/notice
		$acces_j='';
		global $gestion_acces_active, $gestion_acces_empr_notice;
		if ($OPACUserId != -1 && $gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			$acces_j = $dom_2->getJoin($empr_id,4,'notice_id');
		}

		if($acces_j) {
			$statut_j='';
			$statut_r='';
		} else {
			$statut_j=',notice_statut';
			$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0) or (notice_visible_opac_abon=1 and notice_visible_opac=1)) ";
		}

		$sql = "SELECT object_id FROM etagere LEFT JOIN etagere_caddie ON (etagere_id = idetagere) LEFT JOIN caddie_content ON (caddie_content.caddie_id = etagere_caddie.caddie_id) LEFT JOIN notices ON (object_id = notice_id) $acces_j $statut_j WHERE etagere_id = ".$shelf_id." AND object_id $statut_r GROUP BY object_id";
		$res = mysql_query($sql, $dbh);
		$results = array();
		while($row = mysql_fetch_row($res)) {
			$results[] = $row[0];
		}
		return $results;
	}
	
	function list_locations() {
		global $dbh;
		$results = array();
		$sql = "SELECT idlocation, location_libelle FROM docs_location WHERE location_visible_opac = 1";
		$res = mysql_query($sql, $dbh);
		while($row = mysql_fetch_assoc($res)) {
			$results[] = array(
				"location_id" => $row["idlocation"],
				"location_caption" => utf8_normalize($row["location_libelle"])
			);
		}

		return $results;
	}
	
	function list_sections($location) {
		global $dbh;
		$results = array();
		$location+=0;
		$requete="select idsection, section_libelle, section_pic from docs_section, exemplaires where expl_location=$location and section_visible_opac=1 and expl_section=idsection group by idsection order by section_libelle ";
		$resultat=mysql_query($requete);
		$n=0;
		while ($r=mysql_fetch_object($resultat)) {
			$aresult = array();
			$aresult["section_id"] = $r->idsection;
			$aresult["section_location"] = $location;
			$aresult["section_caption"] = utf8_normalize($r->section_libelle);
			$aresult["section_image"] = $r->section_pic ? utf8_normalize($r->section_pic) : "images/rayonnage-small.png";
			$results[] = $aresult;
		}
		return $results;
	}
	
	function is_also_borrowed_enabled() {
		global $opac_autres_lectures_tri;
		return $opac_autres_lectures_tri ? true : false;
	}
	
	function also_borrowed ($notice_id=0,$bulletin_id=0) {
		global $dbh, $msg;
		global $opac_autres_lectures_tri;
		global $opac_autres_lectures_nb_mini_emprunts;
		global $opac_autres_lectures_nb_maxi;
		global $opac_autres_lectures_nb_jours_maxi;
		global $opac_autres_lectures;
		global $gestion_acces_active,$gestion_acces_empr_notice;
		
		$results = array();
		
		if (!$opac_autres_lectures || (!$notice_id && !$bulletin_id)) return $results;
	
		if (!$opac_autres_lectures_nb_maxi) $opac_autres_lectures_nb_maxi = 999999 ;
		if ($opac_autres_lectures_nb_jours_maxi) $restrict_date=" date_add(oal.arc_fin, INTERVAL $opac_autres_lectures_nb_jours_maxi day)>=sysdate() AND ";
		if ($notice_id) $pas_notice = " oal.arc_expl_notice!=$notice_id AND ";
		if ($bulletin_id) $pas_bulletin = " oal.arc_expl_bulletin!=$bulletin_id AND ";
		// Ajout ici de la liste des notices lues par les lecteurs de cette notice
		$rqt_autres_lectures = "SELECT oal.arc_expl_notice, oal.arc_expl_bulletin, count(*) AS total_prets,
					trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if(mention_date, concat(' (',mention_date,')') ,if (date_date, concat(' (',date_format(date_date, '%d/%m/%Y'),')') ,'')))) as tit, if(notices_m.notice_id, notices_m.notice_id, notices_s.notice_id) as not_id 
				FROM ((((pret_archive AS oal JOIN
					(SELECT distinct arc_id_empr FROM pret_archive nbec where (nbec.arc_expl_notice='".$notice_id."' AND nbec.arc_expl_bulletin='".$bulletin_id."') AND nbec.arc_id_empr !=0) as nbec
					ON (oal.arc_id_empr=nbec.arc_id_empr and oal.arc_id_empr!=0 and nbec.arc_id_empr!=0))
					LEFT JOIN notices AS notices_m ON arc_expl_notice = notices_m.notice_id )
					LEFT JOIN bulletins ON arc_expl_bulletin = bulletins.bulletin_id) 
					LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id)
				WHERE $restrict_date $pas_notice $pas_bulletin oal.arc_id_empr !=0
				GROUP BY oal.arc_expl_notice, oal.arc_expl_bulletin
				HAVING total_prets>=$opac_autres_lectures_nb_mini_emprunts 
				ORDER BY $opac_autres_lectures_tri 
				"; 
	
		$res_autres_lectures = mysql_query($rqt_autres_lectures); 
		if (!$res_autres_lectures)
			return $results;
		if (mysql_num_rows($res_autres_lectures)) {
			$odd_even=1;
			$inotvisible=0;
			$aresult = array();
	
			//droits d'acces emprunteur/notice
			$acces_j='';
			if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
				$ac= new acces();
				$dom_2= $ac->setDomain(2);
				$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
			}
				
			if($acces_j) {
				$statut_j='';
				$statut_r='';
			} else {
				$statut_j=',notice_statut';
				$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
			}
			
			while (($data=mysql_fetch_array($res_autres_lectures))) { // $inotvisible<=$opac_autres_lectures_nb_maxi
				$requete = "SELECT  1  ";
				$requete .= " FROM notices $acces_j $statut_j  WHERE notice_id='".$data[not_id]."' $statut_r ";
				$myQuery = mysql_query($requete, $dbh);
				if (mysql_num_rows($myQuery) && $inotvisible<=$opac_autres_lectures_nb_maxi) { // mysql_num_rows($myQuery)
					$inotvisible++;
					$titre = $data['tit'];
					// **********
					$responsab = array("responsabilites" => array(),"auteurs" => array());  // les auteurs
					$responsab = get_notice_authors($data['not_id']) ;
					$as = array_search ("0", $responsab["responsabilites"]) ;
					if ($as!== FALSE && $as!== NULL) {
						$auteur_0 = $responsab["auteurs"][$as] ;
						$auteur = new auteur($auteur_0["id"]);
						$mention_resp = $auteur->isbd_entry;
					} else {
						$as = array_keys ($responsab["responsabilites"], "1" ) ;
						for ($i = 0 ; $i < count($as) ; $i++) {
							$indice = $as[$i] ;
							$auteur_1 = $responsab["auteurs"][$indice] ;
							$auteur = new auteur($auteur_1["id"]);
							$aut1_libelle[]= $auteur->isbd_entry;
						}
						$mention_resp = implode (", ",$aut1_libelle) ;
					}
					$mention_resp ? $auteur = $mention_resp : $auteur="";
				
					// on affiche les résultats 
					if ($odd_even==0) {
						$pair_impair="odd";
						$odd_even=1;
					} else if ($odd_even==1) {
						$pair_impair="even";
						$odd_even=0;
					}
					$aresult["notice_id"] = $data['not_id'];
					$aresult["notice_title"] = $titre;
					$aresult["notice_author"] = $auteur;
					$results[] = $aresult;
				}
			}
		};
		
		return $results;
		}
	
	function get_location_information($location_id) {
		global $dbh;
		$result = array();
		
		$location_id += 0;
		if (!$location_id)
			throw new Exception("Missing parameter: location_id");
		
		$sql = "SELECT idlocation, location_libelle FROM docs_location WHERE location_visible_opac = 1 AND idlocation = ".$location_id;
		$res = mysql_query($sql, $dbh);
		if ($row = mysql_fetch_assoc($res))
			$result = array(
				"location_id" => $row["idlocation"],
				"location_caption" => utf8_normalize($row["location_libelle"])
			);

		return $result;
	}
	
	function get_location_information_and_sections($location_id) {
		return array(
			"location" => $this->get_location_information($location_id),
			"sections" => $this->list_sections($location_id)
		);
	}
	
	function get_section_information($section_id) {
		global $dbh;
		$result = array();
		$section_id+=0;
		if (!$section_id)
			throw new Exception("Missing parameter: section_id");

		$requete="select idsection, section_libelle, section_pic, expl_location from docs_section, exemplaires where idsection = ".$section_id." and section_visible_opac=1 and expl_section=idsection group by idsection order by section_libelle ";
		$resultat=mysql_query($requete);
		if ($r=mysql_fetch_object($resultat)) {
			$result["section_id"] = $r->idsection;
			$result["section_location"] = $r->expl_location;
			$result["section_caption"] = utf8_normalize($r->section_libelle);
			$result["section_image"] = $r->section_pic ? utf8_normalize($r->section_pic) : "images/rayonnage-small.png";
		}
		return $result;
	}
	
	function get_all_locations_and_sections() {
		
		global $dbh;
		$results = array();
		$sql = "SELECT idlocation, location_libelle FROM docs_location WHERE location_visible_opac = 1";
		$res = mysql_query($sql, $dbh);
		while($row = mysql_fetch_assoc($res)) {
			$aresult = array(
				'location' => array(
					"location_id" => $row["idlocation"],
					"location_caption" => utf8_normalize($row["location_libelle"])
				),
				'sections' => array(),
			);
			
			$sql2="select idsection, section_libelle, section_pic from docs_section, exemplaires where expl_location=".($row["idlocation"])." and section_visible_opac=1 and expl_section=idsection group by idsection order by section_libelle ";
			$res2=mysql_query($sql2);
			$n=0;
			while ($r=mysql_fetch_object($res2)) {
				$asection = array();
				$asection["section_id"] = $r->idsection;
				$asection["section_location"] = $row["idlocation"];
				$asection["section_caption"] = utf8_normalize($r->section_libelle);
				$asection["section_image"] = $r->section_pic ? utf8_normalize($r->section_pic) : "images/rayonnage-small.png";
				$aresult['sections'][] = $asection;
			}
			
			$results[] = $aresult;
		}

		return $results;
	}
	
	function get_infopage($infopage_id,$js_subst="",$encoding){
		global $dbh,$charset,$opac_url_base;
		
		$requete = "SELECT content_infopage FROM infopages WHERE id_infopage = $infopage_id";
		$result = mysql_query($requete,$dbh);
		if (mysql_num_rows($result)){
			$infopage = mysql_result($result,0,0);
			if($js_subst){
				$infopage = str_replace($opac_url_base."index.php?lvl=infopages&amp;pagesid=","!!INFOPAGE_URL!!",$infopage);
				preg_match_all("/!!INFOPAGE_URL!!([0-9]+)/",$infopage,$tab);
				for ($i = 0; $i<sizeof($tab[0]);$i++){
					$infopage= preg_replace("/".$tab[0][$i]."/","#\" onclick=\"".str_replace("!!id!!",$tab[1][$i],$js_subst).";return false;",$infopage);	
				}
			}
			if(($encoding == "utf-8") && ($charset!= "utf-8"))return utf8_encode($infopage);
			elseif(($encoding != "utf-8") && ($charset== "utf-8")) return utf8_decode($infopage);
			else return $infopage;
		}
	}
	
	function get_marc_table($type){
		global $charset;
		$marc_list = new marc_list($type);
		if ($charset != "utf-8"){
			foreach($marc_list->table as $key => $value){
				$marc_list->table[$key] = utf8_encode($value);
			}
		}
		return $marc_list->table;
	}	
}


?>