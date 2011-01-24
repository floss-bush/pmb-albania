<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmb.class.php,v 1.18 2010-12-08 15:40:06 arenou Exp $
require_once("$include_path/notice_affichage.inc.php");
require_once("$include_path/bulletin_affichage.inc.php");
require_once("$class_path/upload_folder.class.php");
require_once("$class_path/search.class.php");

class pmb extends base_params implements params {
	var $listeDocs = array();		//tableau de documents
	var $current = 0;				//position courante dans le tableau
	var $currentDoc = "";			//tableau décrivant le document courant
	var $params;					//tableau de paramètres utiles pour la recontructions des requetes...et même voir plus
	var $listeBulls = array();
	var $listeNotices = array();
	var $watermark = array();			//Url du watermark si défini  + transparence
  
    function pmb($params,$visionneuse_path) {
    	global $opac_photo_mean_size_x,$opac_photo_mean_size_y;
    	$this->params = $params;
    	$this->params["maxX"] = $opac_photo_mean_size_x;
    	$this->params["maxY"] = $opac_photo_mean_size_y;
    	$this->visionneuse_path = $visionneuse_path;
    	if($this->params["lvl"] != "afficheur")
	    	$this->recupListDocNum();
	    if($this->params["lvl"] != "afficheur" && $this->params["explnum"] !== 0)
	    	$this->getDocById($this->params["explnum"]);	
    }
 	//renvoi un param
 	function getParam($parametre){
 		return $this->params[$parametre];
 	}
 	//renvoi le nombre de documents
 	function getNbDocs(){
 		return sizeof($this->listeDocs);
 	}
 	//renvoi un document précis
 	function getDoc($numDoc){
 		if($numDoc >= 0 && $numDoc <= $this->getNbDocs()-1){
 			$this->current = $numDoc;
 			return $this->getCurrentDoc();
 		}else return false;
 	}
 	function getDocById($id){
		$this->getExplnums($id);
 	}
 	
 	function recupListDocNum(){
 		global $dbh;
 		global $opac_indexation_docnum_allfields;
 		global $gestion_acces_active,$gestion_acces_empr_notice;
 		global $opac_photo_filtre_mimetype; //filtre des mimetypes;
 		global $opac_nb_max_tri;
 		
 		//droits d'acces emprunteur/notice
		$acces_j='';
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1){
			$ac= new acces();
			$dom_2= $ac->setDomain(2);
			$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
		}
		if($acces_j){
			$statut_j='';
			$statut_r='';
		}else{
			$statut_j=',notice_statut';
			$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
		}	

     	//on reconstruit la requete...
     	$this->listeBulls =array();
     	$this->listeNotices =array();
		$requete_noti = "";
		$requete_bull = "";
		$requete_explnum = "";
		switch($this->params["mode"]){
			case "tous" :
				if(!$opac_indexation_docnum_allfields || $this->params['user_query'] == "*")
					$requete_noti="select distinct notice_id,notices.niveau_biblio,notices.niveau_hierar, ".stripslashes($this->params["pert"])." from notices_global_index,notices $statut_j $acces_j ".stripslashes($this->params["clause"])."  ".stripslashes($this->params["tri"]);
				else $requete_noti="select distinct uni.notice_id,n.niveau_biblio,n.niveau_hierar from  ".stripslashes($this->params["join"])." join notices n on uni.notice_id=n.notice_id  join notices_global_index on num_notice=uni.notice_id ".stripslashes($this->params["tri"]);
				break;
			case "titre":
				$requete_noti="select notice_id,niveau_biblio,niveau_hierar, ".stripslashes($this->params["pert"])." from notices $statut_j $acces_j ".stripslashes($this->params["clause"])." group by notice_id ".stripslashes($this->params["tri"]);
				break;
			case "keyword":
				$requete_noti="select notice_id,niveau_biblio,niveau_hierar, ".stripslashes($this->params["pert"])." from notices $statut_j $acces_j ".stripslashes($this->params["clause"])." group by notice_id ".stripslashes($this->params["tri"]);
				break;
			case "abstract":
				$requete_noti="select notice_id,niveau_biblio,niveau_hierar, ".stripslashes($this->params["pert"])." from notices $statut_j $acces_j ".stripslashes($this->params["clause"])." group by notice_id ".stripslashes($this->params["tri"]);
				break;
			case "extended":
				$es=new search();
				$es->unserialize_search(htmlspecialchars_decode($this->params['search']));
				$table=$es->make_search();
				$requete_noti="select distinct notices.notice_id,notices.niveau_biblio,notices.niveau_hierar from ".$table.",notices, notice_statut where notices.notice_id=".$table.".notice_id and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
				break;
			case "author_see":
				//recup des auteurs associés...
				$rqt_auteurs = "select author_id as aut from authors where author_see='".$this->params["idautorite"]."' and author_id!=0 ";
				$rqt_auteurs .= "union select author_see as aut from authors where author_id='".$this->params["idautorite"]."' and author_see!=0 " ;
				$res_auteurs = mysql_query($rqt_auteurs, $dbh);
				$clause_auteurs = "responsability_author in('".$this->params["idautorite"]."' ";
				while(($id_aut=mysql_fetch_object($res_auteurs))) {
					$clause_auteurs .= ", '".$id_aut->aut."' ";
					$rqt_auteursuite = "select author_id as aut from authors where author_see='$id_aut->aut' and author_id!=0 ";
					$res_auteursuite = mysql_query($rqt_auteursuite, $dbh);
					while(($id_autsuite=mysql_fetch_object($res_auteursuite))) $clause_auteurs .= ", '".$id_autsuite->aut."' "; 
				} 
				$clause_auteurs .= ")";					
					
				// on lance la vraie requête
				$requete_noti = "SELECT distinct notices.notice_id,niveau_biblio,niveau_hierar FROM notices $acces_j, responsability $statut_j ";
				$requete_noti.= "where $clause_auteurs and notice_id=responsability_notice $statut_r ";
				$requete_noti.= "ORDER BY index_serie,tnvol,index_sew";				
		
			case "congres_see" : 
				//on récup les auteurs associés
				$rqt_auteurs = "select author_id as aut from authors where author_see='".$this->params["idautorite"]."' and author_id!=0 ";
				$rqt_auteurs .= "union select author_see as aut from authors where author_id='".$this->params["idautorite"]."' and author_see!=0 " ;
				$res_auteurs = mysql_query($rqt_auteurs, $dbh);
				$clause_auteurs = "responsability_author in('".$this->params["idautorite"]."' ";
				while(($id_aut=mysql_fetch_object($res_auteurs))) {
					$clause_auteurs .= ", '".$id_aut->aut."' ";
					$rqt_auteursuite = "select author_id as aut from authors where author_see='$id_aut->aut' and author_id!=0 ";
					$res_auteursuite = mysql_query($rqt_auteursuite, $dbh);
					while(($id_autsuite=mysql_fetch_object($res_auteursuite))) $clause_auteurs .= ", '".$id_autsuite->aut."' "; 
				} 
				$clause_auteurs .= ")" ;
				
				//on peut lancer la vrai requete maintenant...					
				$requete_noti = "SELECT distinct notices.notice_id,niveau_biblio,niveau_hierar FROM notices $acces_j, responsability $statut_j ";
				$requete_noti.= "where $clause_auteurs and notice_id=responsability_notice $statut_r ";
				$requete_noti.= "ORDER BY index_serie,tnvol,index_sew";
			
				break;
			case "categ_see":
				global $opac_auto_postage_nb_descendant,$opac_auto_postage_nb_montant;
				global $opac_auto_postage_descendant,$opac_auto_postage_montant,$opac_auto_postage_etendre_recherche;
				global $opac_categories_categ_sort_records;
				
				//auto-postage...
				$nb_level_descendant=$opac_auto_postage_nb_descendant;
				$nb_level_montant=$opac_auto_postage_nb_montant;

				$q = "select path from noeuds where id_noeud = '".$this->params["idautorite"]."' ";
				$r = mysql_query($q, $dbh);
				$path=mysql_result($r, 0, 0);
				$nb_pere=substr_count($path,'/');
				
				// Si un path est renseigné et le paramètrage activé			
				if ($path && ($opac_auto_postage_descendant || $opac_auto_postage_montant || $opac_auto_postage_etendre_recherche) && ($nb_level_montant || $nb_level_descendant)){
					
					//Recherche des fils 
					if(($opac_auto_postage_descendant || $opac_auto_postage_etendre_recherche)&& $nb_level_descendant) {
						if($nb_level_descendant != '*' && is_numeric($nb_level_descendant))
							$liste_fils=" path regexp '^$path(\\/[0-9]*){0,$nb_level_descendant}$' ";
						else 
							//$liste_fils=" path regexp '^$path(\\/[0-9]*)*' ";
							$liste_fils=" path like '$path/%' or  path = '$path' ";
					} else {
						$liste_fils=" id_noeud='".$this->params["idautorite"]."' ";
					}
							
					// recherche des pères
					if(($opac_auto_postage_montant || $opac_auto_postage_etendre_recherche) && $nb_level_montant ) {
						
						$id_list_pere=explode('/',$path);	
						$stop_pere=0;
						if($nb_level_montant != '*' && is_numeric($nb_level_montant)) $stop_pere=$nb_pere-$nb_level_montant;
						if($stop_pere<0) $stop_pere=0;
						for($i=$nb_pere;$i>=$stop_pere; $i--) {
							$liste_pere.= " or id_noeud='".$id_list_pere[$i]."' ";
						}
					}			
					$suite_req = " FROM noeuds join notices_categories on id_noeud=num_noeud join notices on notcateg_notice=notice_id  $acces_j $statut_j ";
					$suite_req.= "WHERE ($liste_fils $liste_pere) $statut_r ";
					
				} else {	
					// cas normal d'avant		
					//$suite_req=" FROM notices_categories, notices, notice_statut WHERE (notices_categories.num_noeud = '".$id."' and notices_categories.notcateg_notice = notices.notice_id) and (notices.statut = notice_statut.id_notice_statut and ((notice_statut.notice_visible_opac = 1 and notice_statut.notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_statut.notice_visible_opac_abon=1 and notice_statut.notice_visible_opac = 1)":"").")) ";
					$suite_req = " FROM notices_categories join notices on notcateg_notice=notice_id $acces_j $statut_j ";
					$suite_req.= "WHERE num_noeud=".$this->params["idautorite"]." $statut_r ";
				}
				//on a ce qu'il nous faut, on peut lancer la recherche...
				$requete_noti ="SELECT distinct notices.notice_id, notices.niveau_biblio, notices.niveau_hierar $suite_req ORDER BY $opac_categories_categ_sort_records";
				break;
			case "indexint_see":
				$requete_noti = "SELECT notice_id,niveau_biblio,niveau_hierar FROM notices, notice_statut WHERE indexint='".$this->params["idautorite"]."' and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
				break;
			case "coll_see":
				$requete_noti= "SELECT notices.notice_id,niveau_biblio,niveau_hierar FROM notices $acces_j $statut_j WHERE coll_id='".$this->params["idautorite"]."' $statut_r ";
				break;
			case "publisher_see":
				$requete_noti  = "SELECT notice_id,niveau_biblio,niveau_hierar FROM notices $acces_j $statut_j WHERE (ed1_id='".$this->params["idautorite"]."' or ed2_id='".$this->params["idautorite"]."') $statut_r "; 
				break;
			case "titre_uniforme_see" : 
				$requete_noti = "SELECT notice_id,niveau_biblio,niveau_hierar FROM notices $acces_j $statut_j ,notices_titres_uniformes ";
				$requete_noti.= "WHERE ntu_num_notice=notice_id and ntu_num_tu='".$this->params["idautorite"]."' $statut_r ";
				break;
			case "serie_see":
				$requete_noti  = "SELECT distinct notice_id,niveau_biblio,niveau_hierar FROM notices, notice_statut WHERE tparent_id='".$this->params["idautorite"]."' and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
				break;
			case "subcoll_see":
				$requete_noti = "SELECT notice_id,niveau_biblio,niveau_hierar FROM notices $acces_j $statut_j WHERE subcoll_id='".$this->params["idautorite"]."'  $statut_r ";
				break;
			case "perio_bulletin":
				//TODO : droits sur les bulletins et dépouillements
				$requete_bull = "SELECT bulletin_id FROM bulletins WHERE bulletin_notice='".$this->params["idperio"]."'";
				//on récupère aussi les articles associés aux bulletins
				$requete_noti ="select analysis_notice as notice_id from analysis join bulletins on analysis_bulletin = bulletin_id AND bulletin_notice='".$this->params["idperio"]."'";
				break;
			case "docnum":
				//cas assez particulier, on va pas rechercher toutes les notices et bulletins pour retrouver les explnum le tout en partant des explnums....
				$requete1 ="select explnum_id,explnum_notice,explnum_bulletin,explnum_nom,explnum_mimetype,explnum_url,explnum_extfichier,explnum_nomfichier,explnum_repertoire,explnum_path, notice_id, ".stripslashes($this->params["pert"])." from explnum, notices $statut_j $acces_j ".stripslashes($this->params["clause"])." ";  
				$requete2  = "select explnum_id,explnum_notice,explnum_bulletin,explnum_nom,explnum_mimetype,explnum_url,explnum_extfichier,explnum_nomfichier,explnum_repertoire,explnum_path, notice_id, ".stripslashes($this->params["pert"])." from bulletins, explnum, notices $statut_j $acces_j ".stripslashes($this->params["clause_bull"])." ";
				$requete_explnum = "select explnum_id,explnum_notice,explnum_bulletin,explnum_nom,explnum_mimetype,explnum_url,explnum_extfichier,explnum_nomfichier,explnum_repertoire,explnum_path from ($requete1 UNION $requete2) as uni join notices n on uni.notice_id=n.notice_id  ".stripslashes($this->params["tri"]); 
				break; 
			default :
				//on ne peut avoir que l'id de l'exemplaire
				$requete_noti = "select explnum_notice as notice_id from explnum where explnum_notice != 0 and explnum_id = ".$this->params["explnum_id"];
				$requete_bull = "select explnum_bulletin as bulletin_id from explnum where explnum_bulletin != 0 and explnum_id = ".$this->params["explnum_id"];
				break;
		}

		if ($requete_explnum != ""){
			$res_explnum = mysql_query($requete_explnum,$dbh);
			while(($expl = mysql_fetch_object($res_explnum))){
				$this->listeDocs[] = $expl;
			}
			$this->current = 0;
			$this->checkCurrentExplnumId();		
		}else{
			if($requete_noti){
				$res_notice = mysql_query($requete_noti,$dbh);
				if(mysql_num_rows($res_notice)){
					while(($not_ids = mysql_fetch_object($res_notice))){
						//cas d'une notice de bulletin, le docnum peut etre rattaché au bulletin
						//donc on va le chercher et le rajoute à la liste...
						if($not_ids->niveau_biblio == "b" && $not_ids->niveau_hierar == "2"){
							$req = "select bulletin_id from bulletins where num_notice = ".$not_ids->notice_id." LIMIT 1";
							$res_notibull = mysql_query($req);
							if(mysql_num_rows($res_notibull))
								$this->listeBulls[] = mysql_result($res_notibull,0,0);
						}else{
							$this->listeNotices[] = $not_ids->notice_id;
						}
					}
				}
			}
		
			if($requete_bull){
				$res_bull = mysql_query($requete_bull,$dbh);
				if(mysql_num_rows($res_bull)){
					while(($bull_ids = mysql_fetch_object($res_bull))){
						$this->listeBulls[]= $bull_ids->bulletin_id;
					}
				}
			}
			
			if($this->listeNotices || $this->listeBulls)
				$this->getExplnums();	
		}
  	}
 	
 	//recupére les documents numériques associés
 	function getExplnums($id=0){
		global $dbh;
		global $opac_photo_filtre_mimetype; //filtre des mimetypes;
		
		if( sizeof($this->listeDocs) ==0 ){
			$requete = "select explnum_id,explnum_notice,explnum_bulletin,explnum_nom,explnum_mimetype,explnum_url,explnum_extfichier,explnum_nomfichier,explnum_repertoire,explnum_path from explnum ";
			if($id !=0){
				$requete .= "where explnum_id = $id";
				if($opac_photo_filtre_mimetype) 
					$requete .= " and explnum_mimetype in ($opac_photo_filtre_mimetype)";
				$res = mysql_query($requete,$dbh);
				$this->listeDocs[] = mysql_fetch_object($res);
				$this->current = 0;
			}else {
				if(sizeof($this->listeNotices) > 0 && sizeof($this->listeBulls) == 0){
					$requete .= "where (explnum_notice in ('".implode("','",$this->listeNotices)."') and explnum_bulletin = 0 ) ";
				}else if(sizeof($this->listeBulls) >0 && sizeof($this->listeNotices) == 0){
					$requete .= "where (explnum_bulletin in ('".implode("','",$this->listeBulls)."') and explnum_notice = 0)";
				}else {
					$requete .= "where ((explnum_notice in ('".implode("','",$this->listeNotices)."') and explnum_bulletin = 0) or (explnum_bulletin in ('".implode("','",$this->listeBulls)."') and explnum_notice = 0))";
				}
				if($opac_photo_filtre_mimetype) 
					$requete .= " and explnum_mimetype in ($opac_photo_filtre_mimetype)";
				$res = mysql_query($requete,$dbh);
				while(($expl = mysql_fetch_object($res))){
					$this->listeDocs[] = $expl;
				}
			}
			$this->checkCurrentExplnumId();		
		}
	} 
	
	function checkCurrentExplnumId(){
		if($this->params["explnum_id"] != 0 && $this->params["start"]){
			for ($i=0;$i<sizeof($this->listeDocs);$i++){
				if($this->params["explnum_id"] === $this->listeDocs[$i]->explnum_id){
					$this->current = $i;
					break;
				}
			}
		}else $this->current = $this->params["position"];			
	}
	
	function getCurrentDoc(){
		$this->currentDoc = "";
		//on peut récup déjà un certain nombre d'infos...
		$this->currentDoc["id"] = $this->listeDocs[$this->current]->explnum_id;
		$this->params["explnum_id"] = $this->listeDocs[$this->current]->explnum_id;
		$this->currentDoc["titre"] = $this->listeDocs[$this->current]->explnum_nom;
		$req_expl = "select explnum_id from explnum ";
		$req_expl.= "where explnum_id = ".$this->listeDocs[$this->current]->explnum_id." and ";
		$terms = explode(" ",$this->params["user_query"]);
		if(sizeof($terms>0)) $req_expl.="(";
		$search = '';
		for ($i=0 ; $i<sizeof($terms) ; $i++){
			if( $search != "") $search .= " or ";
			$search .= "explnum_index_sew LIKE '%".$terms[$i]."%'";
		}
		if( $search != "") $req_expl .= $search;
		if(sizeof($terms>0)) $req_expl.=")";
		$searchInExplnum = mysql_query($req_expl);
		if(mysql_num_rows($searchInExplnum)==0){
			$this->currentDoc["searchterms"]  ="";
		}else{
			$this->currentDoc["searchterms"]  =$this->params["user_query"];
		}
				
		//on récupère le chemin
		if($this->listeDocs[$this->current]->explnum_url != ""){
			//c'est une url
			$this->currentDoc["path"] = $this->listeDocs[$this->current]->explnum_url ;
		}elseif($this->listeDocs[$this->current]->explnum_repertoire != 0){
			//il est en répertoire d'upload
			$rep = new upload_folder($this->listeDocs[$this->current]->explnum_repertoire);
			$this->currentDoc["path"] = $rep->repertoire_path."/".$this->listeDocs[$this->current]->explnum_nomfichier;	
		}else{
			//il est en base
			//faudra revoir ce truc
			$this->currentDoc["path"] = "";
		}

		//dans le cadre d'une URL, on doit récup le mimetype...
		if ($this->listeDocs[$this->current]->explnum_url){
			if (file_exists($this->visionneuse_path."/conf/mime_magic.txt")){
				$fp = fopen($this->visionneuse_path."/conf/mime_magic.txt","r");
				$mimetype_file=fread($fp,filesize($this->visionneuse_path."/conf/mime_magic.txt"));
				fclose($fp);
				if ($mimetype_file == "") $mimetype_file=get_cfg_var("mime_magic.magicfile");
			}else $mimetype_file= get_cfg_var("mime_magic.magicfile");	
			$finfo = finfo_open(FILEINFO_MIME,$mimetype_file); 
			$mime_magic=finfo_file($finfo, $this->listeDocs[$this->current]->explnum_url);
	
			$this->currentDoc["mimetype"] =(strpos($mime_magic,";") >0 ? substr($mime_magic,0,strpos($mime_magic,";")) : $mime_magic);		
	
		}else{
		//sinon il a déjà été détecté et est présent en base...	
			$this->currentDoc["mimetype"] =$this->listeDocs[$this->current]->explnum_mimetype;		
		}
		//on récup la notice associée...
		if($this->listeDocs[$this->current]->explnum_notice)
			$this->currentDoc["desc"]=aff_notice($this->listeDocs[$this->current]->explnum_notice,1,1,0,"",0,1);
		else $this->currentDoc["desc"]=bulletin_affichage($this->listeDocs[$this->current]->explnum_bulletin,"visionneuse");
		
		preg_match_all("/(<a href=[\"'][^#][^>]*>)(.*?)<\/a>/",$this->currentDoc["desc"],$lop);
		for ($i = 0 ; $i <sizeof($lop[0]) ; $i++){
			$plop = explode ($lop[0][$i],$this->currentDoc["desc"]);
			$this->currentDoc["desc"] = implode($lop[2][$i],$plop); 
		}
		return $this->currentDoc;
	}

/*******************************************************************
 *  Renvoi le contenu du document brut et gère le cache si besoin  *
 ******************************************************************/
	function openCurrentDoc(){
		global $dbh;
				
		//s'il est en cache, c'est vachement simple
		if($this->isInCache($this->listeDocs[$this->current]->explnum_id)){
			$document = $this->readInCache($this->listeDocs[$this->current]->explnum_id);
		//sinon on va devoir regarder un peu ou ca se passe...
		}elseif($this->listeDocs[$this->current]->explnum_url != ""){
			//on est sur une URL
			$document = file_get_contents($this->listeDocs[$this->current]->explnum_url);	
			//on met les documents issues d'une URL en cache, ca évite les problèmes de connexion plus tard...
			$this->setInCache($this->listeDocs[$this->current]->explnum_id,$document);
		}elseif($this->listeDocs[$this->current]->explnum_repertoire != 0){
			//le document est stocké dans un répertoire d'upload
			$rep = new upload_folder($this->listeDocs[$this->current]->explnum_repertoire);
			$document = file_get_contents($rep->repertoire_path."/".$this->listeDocs[$this->current]->explnum_nomfichier);	
		}else{
			$requete ="SELECT explnum_data FROM explnum WHERE explnum_id = ".$this->listeDocs[$this->current]->explnum_id;
			$res = mysql_query($requete,$dbh);
			if(mysql_num_rows($res))
				$document = mysql_result($res,0,0);			
		}
		//on renvoit le contenu du document
		return $document;
	}
	
	function getMimetypeConf(){
		global $opac_visionneuse_params;
	
 		return unserialize(htmlspecialchars_decode($opac_visionneuse_params));
	}
	
	function getUrlImage($img){
		global $opac_url_base;
	
		if($img !== "")
			$img = $opac_url_base."images/".$img;
			
		return $img;
	}
	
	function getClassParam($class){
		$params = array();
		if($class != ""){
			$req="SELECT visionneuse_params_parameters FROM visionneuse_params WHERE visionneuse_params_class LIKE '$class'";
			if($res=mysql_query($req)){
				if(mysql_num_rows($res)){
					$result = mysql_fetch_object($res);
					$params = htmlspecialchars_decode($result->visionneuse_params_parameters);
				}
			}
		}
		return $params;
	}
}
?>