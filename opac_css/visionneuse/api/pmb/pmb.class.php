<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmb.class.php,v 1.6 2010-07-08 15:28:34 arenou Exp $
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
		$requete_noti = "";
		$requete_bull = "";
		switch($this->params["mode"]){
			case "tous" :
				if(!$opac_indexation_docnum_allfields)
					$requete_noti="select distinct notice_id, ".stripslashes($this->params["pert"])." from notices_global_index,notices $statut_j $acces_j ".stripslashes($this->params["clause"])."  ".stripslashes($this->params["tri"]);
				else $requete_noti="select distinct uni.notice_id from  ".stripslashes($this->params["join"])." join notices n on uni.notice_id=n.notice_id  join notices_global_index on num_notice=uni.notice_id ".stripslashes($this->params["tri"]);
				break;
			case "titre":
				$requete_noti="select notice_id, ".stripslashes($this->params["pert"])." from notices $statut_j $acces_j ".stripslashes($this->params["clause"])." group by notice_id ".stripslashes($this->params["tri"]);
				break;
			case "keyword":
				$requete_noti="select notice_id, ".stripslashes($this->params["pert"])." from notices $statut_j $acces_j ".stripslashes($this->params["clause"])." group by notice_id ".stripslashes($this->params["tri"]);
				break;
			case "abstract":
				$requete_noti="select notice_id, ".stripslashes($this->params["pert"])." from notices $statut_j $acces_j ".stripslashes($this->params["clause"])." group by notice_id ".stripslashes($this->params["tri"]);
				break;
			case "extended":
				$es=new search();
				$es->unserialize_search(htmlspecialchars_decode($this->params['search']));
				$table=$es->make_search();
				$requete_noti="select distinct notices.notice_id from ".$table.",notices, notice_statut where notices.notice_id=".$table.".notice_id and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
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
				$requete_noti = "SELECT distinct notices.notice_id FROM notices $acces_j, responsability $statut_j ";
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
				$requete_noti = "SELECT distinct notices.notice_id FROM notices $acces_j, responsability $statut_j ";
				$requete_noti.= "where $clause_auteurs and notice_id=responsability_notice $statut_r ";
				$requete_noti.= "ORDER BY index_serie,tnvol,index_sew";
			
				break;
			case "categ_see":
				$requete_noti = "SELECT distinct notices.notice_id FROM notices, notices_categories, notice_statut WHERE num_noeud='".$this->params["idautorite"]."' and notcateg_notice=notice_id and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
				break;
			case "indexint_see":
				$requete_noti = "SELECT notice_id FROM notices, notice_statut WHERE indexint='".$this->params["idautorite"]."' and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
				break;
			case "coll_see":
				$requete_noti= "SELECT notices.notice_id FROM notices $acces_j $statut_j WHERE coll_id='".$this->params["idautorite"]."' $statut_r ";
				break;
			case "publisher_see":
				$requete_noti  = "SELECT notice_id FROM notices $acces_j $statut_j WHERE (ed1_id='".$this->params["idautorite"]."' or ed2_id='".$this->params["idautorite"]."') $statut_r "; 
				break;
			case "titre_uniforme_see" : 
				$requete_noti = "SELECT notice_id FROM notices $acces_j $statut_j ,notices_titres_uniformes ";
				$requete_noti.= "WHERE ntu_num_notice=notice_id and ntu_num_tu='".$this->params["idautorite"]."' $statut_r ";
				break;
			case "serie_see":
				$requete_noti  = "SELECT distinct notice_id FROM notices, notice_statut WHERE tparent_id='".$this->params["idautorite"]."' and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".( $_SESSION["user_code"]? " or (notice_visible_opac_abon=1 and notice_visible_opac=1)" : "").")" ;
				break;
			case "subcoll_see":
				$requete_noti = "SELECT notice_id FROM notices $acces_j $statut_j WHERE subcoll_id='".$this->params["idautorite"]."'  $statut_r ";
				break;
			case "perio_bulletin":
				$requete_bull = "SELECT bulletin_id FROM bulletins,notice_statut, notices WHERE bulletin_notice='".$this->params["idperio"]."'
					and notice_id=num_notice
					and statut=id_notice_statut 
					and((notice_visible_opac=1 and notice_visible_opac_abon=0) ".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":")");
				break;
			case "docnum":
				$requete_noti = "select notice_id, ".stripslashes($this->params["pert"])." from explnum, notices $statut_j $acces_j ".stripslashes($this->params["clause"])." "; 
				$requete_bull  = "select  notice_id, ".stripslashes($this->params["pert"])." from bulletins, explnum, notices $statut_j $acces_j ".stripslashes($this->params["clause_bull"])." ";
				$requete_noti = "select distinct uni.notice_id, pert from ($requete_noti UNION $requete_bull) as uni join notices n on uni.notice_id=n.notice_id  ".stripslashes($this->params["tri"]) ; 
		}

		if($requete_noti){
			$res_notice = mysql_query($requete_noti,$dbh);
			if(mysql_num_rows($res_notice)){
				while(($not_ids = mysql_fetch_object($res_notice))){
					$this->listeNotices[] = $not_ids->notice_id;
				}
			}
		}
		
		if($requete_bull){
			$res_bull = mysql_query($requete_bull,$dbh);
			if(mysql_num_rows($res_bull)){
				$i=0;
				while(($bull_ids = mysql_fetch_object($res_bull))){
					$this->listeBulls[]= $bull_ids->bulletin_id;
//					if ($this->params["bulletin"]){
//						if($this->params["bulletin"] == $this->listeBulls[$i]){
//							$this->current = $i;
//						}else $i++;
//					}
				}
			}
		}
		
		if($this->listeNotices || $this->listeBulls)
			$this->getExplnums();			
  	}
 	
 	//recupére les documents numériques associés
 	function getExplnums($id=0){
		global $dbh;
		global $opac_photo_filtre_mimetype; //filtre des mimetypes;
		
		$filtre_condition = "";
		$this->listeDocs = array();
		
		$requete = "select explnum_id,explnum_notice,explnum_bulletin,explnum_nom,explnum_mimetype,explnum_url,explnum_extfichier,explnum_nomfichier,explnum_repertoire,explnum_path from explnum ";
		if($id !=0){
			$requete .= "where explnum_id = $id";
			if($opac_photo_filtre_mimetype) 
				$requete .= " and explnum_mimetype in ($opac_photo_filtre_mimetype)";
			$res = mysql_query($requete,$dbh);
			$this->listeDocs[] = mysql_fetch_object($res);
			$this->current = 0;
		}elseif($this->listeNotices || $this->listeBulls){
			if($this->listeNotices && !$this->listeBulls){
				$requete .= "where explnum_notice in ('".implode("','",$this->listeNotices)."') ";
			} else if($this->listeBulls && !$this->listeNotices){
				$requete .= "where explnum_bulletin in ('".implode("','",$this->listeBulls)."') ";
			} else {
				$requete .= "where explnum_notice in ('".implode("','",$this->listeNotices)."') or explnum_bulletin in ('".implode("','",$this->listeBulls)."')";
			}
			if($opac_photo_filtre_mimetype) 
				$requete .= " and explnum_mimetype in ($opac_photo_filtre_mimetype)";
			$res = mysql_query($requete,$dbh);
			while(($expl = mysql_fetch_object($res))){
				$this->listeDocs[] = $expl;
			}
			if($this->params["explnum_id"] != 0 && $this->params["start"]){
				for ($i=0;$i<sizeof($this->listeDocs);$i++){
					if($this->params["explnum_id"] === $this->listeDocs[$i]->explnum_id){
						$this->current = $i;
						break;
					}
				}
			}else $this->current = $this->params["position"];		
		}
	} 
	
	function getCurrentDoc(){
		$this->currentDoc = "";
		//on peut récup déjà un certain nombre d'infos...
		$this->currentDoc["id"] = $this->listeDocs[$this->current]->explnum_id;
		$this->params["explnum_id"] = $this->listeDocs[$this->current]->explnum_id;
		$this->currentDoc["titre"] = $this->listeDocs[$this->current]->explnum_nom;
		$this->currentDoc["searchterms"] = $this->params["user_query"];

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
			$finfo = finfo_open(FILEINFO_MIME,get_cfg_var("mime_magic.magicfile")); 
			$mime_magic=finfo_file($finfo, $this->listeDocs[$this->current]->explnum_url);
			$this->currentDoc["mimetype"] = substr($mime_magic,0,strpos($mime_magic,";"));
		}else{
		//sinon il a déjà été détecté et est présent en base...	
			$this->currentDoc["mimetype"] =$this->listeDocs[$this->current]->explnum_mimetype;		
		}
		//on récup la notice associée...
		if($this->listeDocs[$this->current]->explnum_notice)
			$this->currentDoc["desc"]=aff_notice($this->listeDocs[$this->current]->explnum_notice,0,1,0,"",0);
		else $this->currentDoc["desc"]=bulletin_affichage($this->listeDocs[$this->current]->explnum_bulletin);
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
	
	function getUrlWatermark($watermark){
		global $opac_url_base;
	
		if($watermark !== "")
			$watermark = $opac_url_base."images/".$watermark;
			
		return $watermark;
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