<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: congres_see.inc.php,v 1.11 2010-12-02 15:46:51 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage du detail pour un auteur

require_once($base_path.'/includes/templates/author.tpl.php');
require_once($base_path.'/classes/author.class.php');
require_once("$class_path/aut_link.class.php");

print "<div id='aut_details'>\n";

if($id) {
	$id+=0;
	$rqt_auteurs = "select author_id as aut from authors where author_see='$id' and author_id!=0 ";
	$rqt_auteurs .= "union select author_see as aut from authors where author_id='$id' and author_see!=0 " ;
	$res_auteurs = mysql_query($rqt_auteurs, $dbh);
	$clause_auteurs = " in ('$id' ";
	while(($id_aut=mysql_fetch_object($res_auteurs))) {
		$clause_auteurs .= ", '".$id_aut->aut."' ";
		$rqt_auteursuite = "select author_id as aut from authors where author_see='$id_aut->aut' and author_id!=0 ";
		$res_auteursuite = mysql_query($rqt_auteursuite, $dbh);
		while(($id_autsuite=mysql_fetch_object($res_auteursuite))) $clause_auteurs .= ", '".$id_autsuite->aut."' "; 
	} 
	$clause_auteurs .= " ) " ;

	// affichage des informations sur l'auteur
	$ourAuteur = new auteur($id);	
	if($ourAuteur->type == 72) {
		// Congrès
		print pmb_bidi("<h3><span>".$msg["congres_see_title"]." $renvoi</span></h3>\n");
	} else if($ourAuteur->type == 71) {
		// Collectivités
		print pmb_bidi("<h3><span>".$msg["collectivite_see_title"]." $renvoi</span></h3>\n");
	} else {
		print pmb_bidi("<h3><span>".$msg["author_see_title"]." $renvoi</span></h3>\n");
	}
	print "<div id='aut_details_container'>\n";	
	print "	<div id='aut_see'>\n
			<img src='./images/home.gif' border='0'></a> >\n"; 
	
	print pmb_bidi($ourAuteur->print_congres_titre()); 
	
	$ourAuteur->get_similar_name($ourAuteur->type);
	print $ourAuteur->print_similar_name();
	
	print $ourAuteur->author_comment;
	
	// récupération des formes rejetées pour affichage
	$requete = "select distinct author_id as aut from authors where author_id $clause_auteurs and author_id!=$id " ;
	$res = mysql_query($requete, $dbh);
	while (($obj=mysql_fetch_object($res))) {
		$objRenvoi = new auteur($obj->aut);
		pmb_strlen($renvoi) ? $renvoi .= ', ('.$objRenvoi->isbd_entry.")" : $renvoi = $objRenvoi->isbd_entry;
	}

	if (pmb_strlen($renvoi)) print pmb_bidi("<font class=number_results>$renvoi</font><br />\n");

	$aut_link= new aut_link(AUT_TABLE_AUTHORS,$id);
	print pmb_bidi($aut_link->get_display());
	
	print "</div><!-- fermeture #aut_see -->\n";
	// affichage des notices associées
	print "<div id='aut_details_liste'>\n";
	if($ourAuteur->type == 72) {
		//Congrès
		print pmb_bidi("<h3>$msg[documents_disponibles_meme_congres]");
	} else if($ourAuteur->type == 71) {
		// Collectivités
		print pmb_bidi("<h3><span>".$msg["doc_collectivite_title"]." $renvoi</span></h3>\n");
	} else {
		print pmb_bidi("<h3>$msg[documents_disponibles_meme_auteur]");
	}
	print "</h3>\n";

	//droits d'acces emprunteur/notice
	$acces_j='';
	if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
		require_once("$class_path/acces.class.php");
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
	
	
	// comptage des notices associées
	if(!$nbr_lignes) {
		$requete = "SELECT count(distinct notices.notice_id) FROM notices $acces_j, responsability $statut_j ";
		$requete.= "where responsability_author $clause_auteurs and notice_id=responsability_notice $statut_r ";
		$res = mysql_query($requete, $dbh);
		if ($res) $nbr_lignes = mysql_result($res,0,0); else $nbr_lignes=0;
		
		//Recherche des types doc
		$where = "where responsability_author $clause_auteurs and notice_id=responsability_notice $statut_r  group by notices.typdoc";
		if ($opac_visionneuse_allow){
			$requete_noti = "select distinct notices.typdoc, count(explnum_id) as nbexplnum FROM notices left join explnum on explnum_mimetype in ($opac_photo_filtre_mimetype) and explnum_notice = notice_id $acces_j, responsability $statut_j  ";
			$requete_bull = "select distinct notices.typdoc, count(explnum_id) as nbexplnum FROM notices left join bulletins on bulletins.num_notice = notice_id and bulletins.num_notice != 0 left join explnum on explnum_mimetype in ($opac_photo_filtre_mimetype) and explnum_bulletin = bulletin_id and explnum_bulletin != 0 $acces_j, responsability $statut_j  ";
			$where = "where responsability_author $clause_auteurs and notice_id=responsability_notice $statut_r group by notices.typdoc";
			$requete = "select distinct uni.typdoc, sum(nbexplnum) as nbexplnum from ($requete_noti $where union $requete_bull $where) as uni group by typdoc";
		}else{
			$requete = "select distinct notices.typdoc FROM notices $acces_j, responsability $statut_j $where";
		}
		
		$res = mysql_query($requete, $dbh);
		$t_typdoc=array();
		$nbexplnum_to_photo=0;
		while ($tpd=mysql_fetch_object($res)) {
			$t_typdoc[]=$tpd->typdoc;
			$nbexplnum_to_photo += $tpd->nbexplnum;
		}
		$l_typdoc=implode(",",$t_typdoc);
	}

	if(!$page) $page=1;
	$debut =($page-1)*$opac_nb_aut_rec_per_page;
		
	if($nbr_lignes) {
		if ($opac_notices_depliable) print $begin_result_liste;
		
		//gestion du tri
		
		//si on dépasse le nombre de lignes maxi à trier
		if ($nbr_lignes>$opac_nb_max_tri) {
			//on annule tri selectionner
			$_SESSION["last_sortnotices"]="";
			print "&nbsp;";
		} else {
			$pos=strpos($_SERVER['REQUEST_URI'],"?");
			$pos1=strpos($_SERVER['REQUEST_URI'],"get");
			if ($pos1==0) $pos1=strlen($_SERVER['REQUEST_URI']);
			else $pos1=$pos1-3;
			$para=urlencode(substr($_SERVER['REQUEST_URI'],$pos+1,$pos1-$pos+1));
			$affich_tris_result_liste=str_replace("!!page_en_cours!!",$para,$affich_tris_result_liste); 
			print $affich_tris_result_liste;

			//on recupere le tri à appliquer
			if (isset($_GET["sort"])) {	
				$_SESSION["last_sortnotices"]=$_GET["sort"];
			}

			if ($_SESSION["last_sortnotices"]!="") {
				$sort = new sort('notices','session');
				print " ".$msg['tri_par']." ".$sort->descriptionTriParId($_SESSION["last_sortnotices"])."&nbsp;"; 
			}
		}
		//fin gestion du tri
		
		print $add_cart_link;
		
		if($opac_visionneuse_allow && $nbexplnum_to_photo){
			print "&nbsp;&nbsp;&nbsp;".$link_to_visionneuse;
			$sendToVisionneuseByGet = str_replace("!!mode!!","congres_see",$sendToVisionneuseByGet);
			$sendToVisionneuseByGet = str_replace("!!idautorite!!",$id,$sendToVisionneuseByGet);
			print $sendToVisionneuseByGet;
		}
		
		if ($opac_show_suggest) {
			$bt_sugg = "&nbsp;&nbsp;&nbsp;<a href=# ";		
			if ($opac_resa_popup) $bt_sugg .= " onClick=\"w=window.open('./do_resa.php?lvl=make_sugg&oresa=popup','doresa','scrollbars=yes,width=600,height=600,menubar=0,resizable=yes'); w.focus(); return false;\"";
				else $bt_sugg .= "onClick=\"document.location='./do_resa.php?lvl=make_sugg&oresa=popup' \" ";			
			$bt_sugg.= " >".$msg[empr_bt_make_sugg]."</a>";
				print $bt_sugg;
		
		}

		//affinage
		//enregistrement de l'endroit actuel dans la session
		if ($_SESSION["last_query"]) {	$n=$_SESSION["last_query"]; } else { $n=$_SESSION["nb_queries"]; }

		$_SESSION["notice_view".$n]["search_mod"]="author_see";
		$_SESSION["notice_view".$n]["search_id"]=$id;
		$_SESSION["notice_view".$n]["search_page"]=$page;

		//affichage
		print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=extended_search&mode_aff=aff_simple_search'>".$msg["affiner_recherche"]."</a>";
		//fin affinage
		//Etendre
		if ($opac_allow_external_search) print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=external_search&mode_aff=aff_simple_search&external_type=simple'>".$msg["connecteurs_external_search_sources"]."</a>";
		//fin etendre

		print "<blockquote>\n";
		print aff_notice(-1);
		// on lance la vraie requête
		$requete = "SELECT distinct notices.notice_id FROM notices $acces_j, responsability $statut_j ";
		$requete.= "where responsability_author $clause_auteurs and notice_id=responsability_notice $statut_r ";
		$requete.= "ORDER BY index_serie,tnvol,index_sew";
		
		//gestion du tri
		if ($_SESSION["last_sortnotices"]!="") {
			$requete = $sort->appliquer_tri($_SESSION["last_sortnotices"], $requete, "notice_id", $debut, $opac_nb_aut_rec_per_page);		
		} else {
			$requete .= " LIMIT $debut,$opac_nb_aut_rec_per_page ";
		}
		//fin gestion du tri		
		$res = mysql_query($requete, $dbh);
		while(($obj=mysql_fetch_object($res))) {
			global $infos_notice;
			print pmb_bidi(aff_notice($obj->notice_id));
			$infos_notice['nb_pages'] = ceil($nbr_lignes/$opac_nb_aut_rec_per_page);
		}
		mysql_free_result($res);

		// constitution des liens pur affichage de la barre de navigation
		$nbepages = ceil($nbr_lignes/$opac_nb_aut_rec_per_page);
		print aff_notice(-2);
		print "	</blockquote>\n
				</div><!-- fermeture du div aut_details_liste -->\n";
		print "<hr /><center>".printnavbar($page, $nbepages, "./index.php?lvl=author_see&id=$id&page=!!page!!&nbr_lignes=$nbr_lignes&l_typdoc=".rawurlencode($l_typdoc))."</center>\n";

	} else {
		print $msg[no_document_found];
		print "</div><!-- fermeture du div aut_details_liste -->\n";
	}
} else {
	print pmb_bidi("<h3><span>".$msg["author_see_title"]." $renvoi</span></h3>\n");
	print "<div id='aut_details_container'>\n"; 
}

print "	</div><!-- fermeture du div aut_details_container -->\n";
print "	</div><!-- fermeture du div aut_details -->\n";
	