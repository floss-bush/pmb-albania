<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: etagere_see.inc.php,v 1.28 2009-07-02 13:50:18 mhalm Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage du contenu d'une étagère

print "<div id='aut_details'>\n";

if ($id) {
	//Récupération des infos de l'étagère
	$id+=0;
	$requete="select idetagere,name,comment from etagere where idetagere=$id";
	$resultat=mysql_query($requete);
	$r=mysql_fetch_object($resultat);
	
	print pmb_bidi("<h3><span>".$r->name."</span></h3>\n");
	print "<div id='aut_details_container'>\n";
	if ($r->comment){
			print "<div id='aut_see'>\n";
			print pmb_bidi("<strong>".$r->comment."</strong><br /><br />");
			print "	</div><!-- fermeture #aut_see -->\n";			
		}

	print "<div id='aut_details_liste'>\n";

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
	
	//$requete = "select count(distinct object_id) from caddie_content, etagere_caddie, notices, notice_statut where etagere_id=$id and caddie_content.caddie_id=etagere_caddie.caddie_id and notice_id=object_id ";
	//$requete.= " and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
	$requete = "select count(distinct object_id) from caddie_content, etagere_caddie, notices $acces_j $statut_j ";
	$requete.= "where etagere_id=$id and caddie_content.caddie_id=etagere_caddie.caddie_id and notice_id=object_id $statut_r ";
	$resultat=mysql_query($requete);
	$nbr_lignes=mysql_result($resultat,0,0);
	
	//Recherche des types doc
	//$requete="select distinct notices.typdoc FROM caddie_content, etagere_caddie, notices, notice_statut where etagere_id=$id and caddie_content.caddie_id=etagere_caddie.caddie_id and notice_id=object_id ";
	//$requete .= " and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
	$requete = "select distinct typdoc FROM caddie_content, etagere_caddie, notices $acces_j $statut_j ";
	$requete.= "where etagere_id=$id and caddie_content.caddie_id=etagere_caddie.caddie_id and notice_id=object_id $statut_r ";
	$res = mysql_query($requete, $dbh);

	
	$t_typdoc=array();
	while ($tpd=mysql_fetch_object($res)) {
		$t_typdoc[]=$tpd->typdoc;
	}
	$l_typdoc=implode(",",$t_typdoc);
	
	if(!$page) $page=1;
	$debut =($page-1)*$opac_nb_aut_rec_per_page;
		
	if($nbr_lignes) {
		if ($opac_notices_depliable) print $begin_result_liste;
				
		//gestion du tri
		if (isset($_GET["sort"])) {	
			$_SESSION["last_sortnotices"]=$_GET["sort"];
		}
		if ($nbr_lignes>$opac_nb_max_tri) {
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
			if ($_SESSION["last_sortnotices"]!="") {
				$sort = new sort('notices','session');
				print " ".$msg['tri_par']." ".$sort->descriptionTriParId($_SESSION["last_sortnotices"])."&nbsp;"; 
			}
		} 
		//fin gestion du tri
		
		print $add_cart_link;
		
		//affinage
		//enregistrement de l'endroit actuel dans la session
		$_SESSION["last_module_search"]["search_mod"]="etagere_see";
		$_SESSION["last_module_search"]["search_id"]=$id;
		$_SESSION["last_module_search"]["search_page"]=$page;
			
		//affichage
		print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=extended_search&mode_aff=aff_module'>".$msg["affiner_recherche"]."</a>";	
		//fin affinage
		
		print "<blockquote>\n";
		print aff_notice(-1);
		// on lance la vraie requête
		//$requete = "select distinct notice_id from caddie_content, etagere_caddie, notices, notice_statut where etagere_id=$id and caddie_content.caddie_id=etagere_caddie.caddie_id and notice_id=object_id";
		//$requete .= " and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
		$requete = "select distinct notice_id from caddie_content, etagere_caddie, notices $acces_j $statut_j ";
		$requete.= "where etagere_id=$id and caddie_content.caddie_id=etagere_caddie.caddie_id and notice_id=object_id $statut_r ";
		
		// ER: supprimé du tri aléatoire parce que affichage d'UNE seule étagère en détail et paginé, donc aléatoire à faire sauter.
		// if ($opac_etagere_notices_order) $requete.=" order by ".$opac_etagere_notices_order;
		//gestion du tri
		if ($_SESSION["last_sortnotices"]!="") {
			$requete = $sort->appliquer_tri($_SESSION["last_sortnotices"], $requete, "notice_id", $debut, $opac_nb_aut_rec_per_page);		
		} else {
			$requete .= "order by ".$opac_etagere_notices_order." LIMIT $debut,$opac_nb_aut_rec_per_page ";	
		}
		//fin gestion du tri
		
		$res = mysql_query($requete, $dbh);
		while(($obj=mysql_fetch_object($res))) {
			print pmb_bidi(aff_notice($obj->notice_id));
			}
		print aff_notice(-2);
		mysql_free_result($res);
		// constitution des liens pur affichage de la barre de navigation
		$nbepages = ceil($nbr_lignes/$opac_nb_aut_rec_per_page);
		print "	</blockquote>\n
				</div><!-- fermeture #aut_details_liste -->\n";
		print "<hr /><center>".printnavbar($page, $nbepages, "./index.php?lvl=etagere_see&id=$id&page=!!page!!&nbr_lignes=$nbr_lignes")."</center>\n";
	} else {
			print $msg[no_document_found];
			print "</div><!-- fermeture #aut_details_liste -->\n";
	}
	print "</div><!-- fermeture #aut_details_container -->\n";
}

print "</div><!-- fermeture #aut_see -->\n";	
?>