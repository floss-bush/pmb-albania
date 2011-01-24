<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serie_see.inc.php,v 1.17 2010-11-17 17:15:23 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage du detail pour une serie

// inclusion de classe utiles
require_once($base_path.'/classes/publisher.class.php');
require_once($base_path.'/includes/templates/publisher.tpl.php');
require_once("$class_path/aut_link.class.php");

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

print "<div id='aut_details'>\n
		<h3><span>".$msg["serie_see_title"]."</span></h3>\n" ;

print "<div id='aut_details_container'>\n";
if($id) {

	// affichage des informations sur l'éditeur
	print "<div id='aut_see'>\n";
	$ourSerie = new serie($id);
	print pmb_bidi($ourSerie->print_resume());
	
	$aut_link= new aut_link(AUT_TABLE_SERIES,$id);
	print pmb_bidi($aut_link->get_display());
	
	print "</div><!-- fermeture #aut_see -->\n";
	
	// affichage des notices associées
	print "	<div id='aut_details_liste'>\n
			<h3>$msg[doc_serie_title]</h3>\n";

	
	
	
		// comptage des notices associées
		if(!$nbr_lignes) {
			//$requete = "SELECT COUNT(notice_id) FROM notices, notice_statut ";
			//$requete .= " where tparent_id='$id' and (statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))";
			$requete = "SELECT COUNT(*) FROM notices $acces_j $statut_j where tparent_id=$id $statut_r ";
			$res = mysql_query($requete, $dbh);
			$nbr_lignes = @mysql_result($res, 0, 0);
			
			//Recherche des types doc
			//$requete="select distinct notices.typdoc FROM notices, notice_statut ";
			//$requete .= " where tparent_id='$id' and (statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))";
			$requete="select distinct typdoc,count(explnum_id) as nbexplnum FROM notices left join explnum on explnum_notice=notice_id $acces_j $statut_j where tparent_id=$id $statut_r group by typdoc";
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
			// on lance la vraie requête
			//$requete  = "SELECT notice_id FROM notices, notice_statut WHERE tparent_id='$id' and (statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))";
			$requete  = "SELECT  notice_id FROM notices $acces_j $statut_j where tparent_id=$id $statut_r ";
			
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
				$sendToVisionneuseByGet = str_replace("!!mode!!","serie_see",$sendToVisionneuseByGet);
				$sendToVisionneuseByGet = str_replace("!!idautorite!!",$id,$sendToVisionneuseByGet);
				print $sendToVisionneuseByGet;
			}
			
			print "<blockquote>\n";
			print aff_notice(-1);
			while(($obj=mysql_fetch_object($res))) {
				print pmb_bidi(aff_notice($obj->notice_id));
			}
			print aff_notice(-2);
			print "</blockquote>\n";
			mysql_free_result($res);

			// constitution des liens

			$nbepages = ceil($nbr_lignes/$opac_nb_aut_rec_per_page);
			print "</div><!-- fermeture aut_details_liste -->\n";
			print "<hr /><center>".printnavbar($page, $nbepages, "./index.php?lvl=serie_see&id=$id&page=!!page!!&nbr_lignes=$nbr_lignes&l_typdoc=".rawurlencode($l_typdoc))."</center>\n";
		} else {
			print $msg["no_document_found"];
			print "</div><!-- fermeture aut_details_liste -->\n";
		}

}
print "</div><!-- fermeture #aut_details_container -->\n";
print "</div><!-- fermeture #aut_details -->\n";

?>
