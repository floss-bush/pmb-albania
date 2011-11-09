<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: coll_see.inc.php,v 1.42.2.1 2011-10-07 07:41:16 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage du detail pour une collection

// inclusion de la classe de gestion des collections
require_once($base_path."/classes/collection.class.php");
require_once($base_path."/classes/publisher.class.php");
require_once($base_path."/includes/templates/collection.tpl.php");
require_once("$class_path/aut_link.class.php");

print "<div id='aut_details'>\n
<h3><span>$msg[detail_coll]</span></h3>\n";

print "<div id='aut_details_container'>\n";
if($id) {
	$id+=0;
	// affichage des informations sur la collection
	print "<div id='aut_see'>\n";
	$ourColl = new collection($id);
	print pmb_bidi($ourColl->print_resume());
	$aut_link= new aut_link(AUT_TABLE_COLLECTIONS,$id);
	print pmb_bidi($aut_link->get_display());
	
	print "</div><!-- fermeture #aut_see -->\n";
	print "<div id='aut_details_liste'>\n";

	// affichage des notices associées
	print "<h3>$msg[available_docs_in_coll]</h3>\n";
	
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
	
	if(!$nbr_lignes) {
		//$requete = "SELECT COUNT(1) FROM notices, notice_statut ";
		//$requete .= " where (coll_id='$id') and (statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))";
		$requete = "SELECT COUNT(1) FROM notices $acces_j $statut_j ";
		$requete.= "where coll_id='$id' $statut_r ";
		
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = @mysql_result($res, 0, 0);
		
		//Recherche des types doc
		//$requete="select distinct notices.typdoc FROM notices, notice_statut ";
		//$requete .= " where (coll_id='$id') and (statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))";
		$requete = "select distinct notices.typdoc from notices $acces_j $statut_j ";
		$clause.= "where coll_id='$id' $statut_r group by notices.typdoc";
		if ($opac_visionneuse_allow){
			$requete_noti = "select distinct notices.typdoc, count(explnum_id) as nbexplnum from notices left join explnum on explnum_mimetype in ($opac_photo_filtre_mimetype) and explnum_notice = notice_id $acces_j $statut_j ";
			$requete_bull = "select distinct notices.typdoc, count(explnum_id) as nbexplnum from notices left join bulletins on bulletins.num_notice = notice_id and bulletins.num_notice != 0 left join explnum on explnum_mimetype in ($opac_photo_filtre_mimetype) and explnum_bulletin != 0 and explnum_bulletin = bulletin_id $acces_j $statut_j ";
			$requete = "select distinct uni.typdoc, sum(nbexplnum) as nbexplnum from ($requete_noti $clause union $requete_bull $clause) as uni group by uni.typdoc";
		}else{
			$requete.= $clause;
		}
		
		$res = mysql_query($requete, $dbh);
		$t_typdoc=array();
		$nbexplnum_to_photo=0;
		while ($tpd=mysql_fetch_object($res)) {
			$t_typdoc[]=$tpd->typdoc;
			if ($opac_visionneuse_allow)
				$nbexplnum_to_photo += $tpd->nbexplnum;
		}
		$l_typdoc=implode(",",$t_typdoc);
	}

	if(!$page) $page=1;
	$debut =($page-1)*$opac_nb_aut_rec_per_page;
		
	if($nbr_lignes) {
		// on lance la requête de sélection des notices
		//$requete = "SELECT notices.notice_id FROM notices, notice_statut WHERE (coll_id='$id') and (statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))";
		$requete = "SELECT notices.notice_id FROM notices $acces_j $statut_j ";
		$requete.= "WHERE coll_id='$id' $statut_r ";
		
		//gestion du tri
		if (isset($_GET["sort"])) {	
			$_SESSION["last_sortnotices"]=$_GET["sort"];
		}
		if ($nbr_lignes>$opac_nb_max_tri) {
			$_SESSION["last_sortnotices"]="";
		}
		if ($_SESSION["last_sortnotices"]!="") {
			$sort = new sort('notices','session');
			$requete = $sort->appliquer_tri($_SESSION["last_sortnotices"], $requete, "notice_id", $debut, $opac_nb_aut_rec_per_page);		
		} else {
			$requete .= " LIMIT $debut,$opac_nb_aut_rec_per_page ";	
		}
		//fin gestion du tri
		
		$res = @mysql_query($requete, $dbh);
		
		if ($opac_notices_depliable) print $begin_result_liste;
				
		//gestion du tri
		if ($nbr_lignes<=$opac_nb_max_tri) {
			$pos=strpos($_SERVER['REQUEST_URI'],"?");
			$pos1=strpos($_SERVER['REQUEST_URI'],"get");
			if ($pos1==0) $pos1=strlen($_SERVER['REQUEST_URI']);
			else $pos1=$pos1-3;
			$para=urlencode(substr($_SERVER['REQUEST_URI'],$pos+1,$pos1-$pos+1));
			$affich_tris_result_liste=str_replace("!!page_en_cours!!",$para,$affich_tris_result_liste); 
			print $affich_tris_result_liste;
			if ($_SESSION["last_sortnotices"]!="") {
				print " ".$msg['tri_par']." ".$sort->descriptionTriParId($_SESSION["last_sortnotices"])."&nbsp;"; 
			}
		} else print "&nbsp;";
		//fin gestion du tri
		
		print $add_cart_link;
		
		if($opac_visionneuse_allow && $nbexplnum_to_photo){
			print "&nbsp;&nbsp;&nbsp;".$link_to_visionneuse;
			$sendToVisionneuseByGet = str_replace("!!mode!!","coll_see",$sendToVisionneuseByGet);
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

		$_SESSION["notice_view".$n]["search_mod"]="coll_see";
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
		while(($obj=mysql_fetch_object($res))) {
			global $infos_notice;
			print pmb_bidi(aff_notice($obj->notice_id));
			$infos_notice['nb_pages'] = ceil($nbr_lignes/$opac_nb_aut_rec_per_page);
		}
		print aff_notice(-2);
		print "</blockquote>\n";
		mysql_free_result($res);

		// constitution des liens

		$nbepages = ceil($nbr_lignes/$opac_nb_aut_rec_per_page);
		print "</div><!-- fermeture #aut_details_liste -->\n";
		print "<hr /><center>".printnavbar($page, $nbepages, "./index.php?lvl=coll_see&id=$id&page=!!page!!&nbr_lignes=$nbr_lignes&l_typdoc=".rawurlencode($l_typdoc))."</center>\n";

	} else {
		print $msg[no_document_found];
		print "</div><!-- fermeture #aut_details_liste -->\n";
	}
}

print "</div><!-- fermeture #aut_details_container -->\n";
print "</div><!-- fermeture #aut_details -->\n";
?>
