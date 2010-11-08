<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categ_see.inc.php,v 1.58 2010-07-05 12:40:29 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage recherche
require_once($base_path.'/includes/simple_search.inc.php');
require_once($base_path.'/includes/rec_history.inc.php');
require_once("$class_path/aut_link.class.php");

//recuperation du thesaurus session 
if (!$id_thes) {
	$id_thes = thesaurus::getSessionThesaurusId();
} else {
	thesaurus::setSessionThesaurusId($id_thes);
}
$thes = new thesaurus($id_thes);
$id_top = $thes->num_noeud_racine;

print "<div id='aut_details'>\n
		<h3><span>$msg[categ_see_tpl_categ]</span></h3>\n";

print "<div id='aut_details_container'>\n";

if ($id) {
	// instanciation de la catégorie
	$ourCateg = new categorie($id);
	// affichage du path de la catégorie
	print "	<div id='aut_see'>\n
			<a href=\"./index.php?lvl=categ_see&id=".$ourCateg->thes->num_noeud_racine."\"><img src='./images/home.gif' border='0'></a>\n"; 

	print pmb_bidi($ourCateg->categ_path($opac_categories_categ_path_sep,$css));

	// si la catégorie à des enfants, on les affiche
	if ($ourCateg->has_child) print pmb_bidi($ourCateg->child_list('./images/folder.gif',$css));
	
	$q = "select ";
	$q.= "distinct catdef.num_noeud,catdef.note_application, catdef.comment_public,";
	$q.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as libelle_categorie ";
	$q.= "from voir_aussi left join noeuds on noeuds.id_noeud=voir_aussi.num_noeud_dest ";
	$q.= "left join categories as catdef on noeuds.id_noeud=catdef.num_noeud and catdef.langue = '".$thes->langue_defaut."' "; 
	$q.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' "; 
	$q.= "where ";
	$q.= "voir_aussi.num_noeud_orig = '".$id."' ";
	$q.= "order by libelle_categorie limit ".$opac_categories_max_display;

	$found_see_too = mysql_query($q, $dbh); 
	
	if (mysql_num_rows($found_see_too)) {
		print "<br />".$msg['term_show_see_also']." " ;
		$deb = 0 ;
		while (($mesCategories_see_too = mysql_fetch_object($found_see_too))) {
			if ($deb) print " / " ;
			$note = $mesCategories_see_too->comment_public;
			$c_categ =  new category($mesCategories_see_too->num_noeud);
			// Affichage du commentaire par le layer sur les "Voir aussi"
			 $result_com = categorie::zoom_categ($mesCategories_see_too->num_noeud, $note);			
		
			print "<a href=./index.php?lvl=categ_see&id=".$mesCategories_see_too->num_noeud.">";
		
			if ($c_categ->has_notices()) print " <img src='$base_path/images/folder_search.gif' border=0 align='absmiddle'>";	
			else print  " <img src='./images/folder.gif' border='0' align='middle'>";	
			print pmb_bidi("</a><a href=./index.php?lvl=categ_see&id=".$mesCategories_see_too->num_noeud."".$result_com['java_com'].">".$mesCategories_see_too->libelle_categorie.'</a>'.$result_com['zoom']);
			$deb = 1 ;
		}
	}
	$aut_link= new aut_link(AUT_TABLE_CATEG,$id);
	print pmb_bidi($aut_link->get_display());
	
	print "</div><!-- //fermeture aut_see -->\n";
	print "<div id='aut_details_liste'>\n";
	
	//Lire le champ path du noeud pour étendre la recherche éventuellement au fils et aux père de la catégorie
	// lien Etendre auto_postage
	if (!isset($nb_level_enfants)) {
		// non defini, prise des valeurs par défaut
		if (isset($_SESSION["nb_level_enfants"]) && $opac_auto_postage_etendre_recherche) $nb_level_descendant=$_SESSION["nb_level_enfants"];
		else $nb_level_descendant=$opac_auto_postage_nb_descendant;
	} else {
		$nb_level_descendant=$nb_level_enfants;
	}
	
	// lien Etendre auto_postage
	if(!isset($nb_level_parents)) {
		// non defini, prise des valeurs par défaut
		if(isset($_SESSION["nb_level_parents"]) && $opac_auto_postage_etendre_recherche) $nb_level_montant=$_SESSION["nb_level_parents"];
		else $nb_level_montant=$opac_auto_postage_nb_montant;
	} else {
		$nb_level_montant=$nb_level_parents;
	}	
	
	$_SESSION["nb_level_enfants"]=	$nb_level_descendant;
	$_SESSION["nb_level_parents"]=	$nb_level_montant;
	
	$q = "select path from noeuds where id_noeud = '".$id."' ";
	$r = mysql_query($q, $dbh);
	$path=mysql_result($r, 0, 0);
	$nb_pere=substr_count($path,'/');
	
	$acces_j='';
	if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
		require_once("$class_path/acces.class.php");
		$ac= new acces();
		$dom_2= $ac->setDomain(2);
		$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notcateg_notice');
	}
		
	if($acces_j) {
		$statut_j='';
		$statut_r='';
	} else {
		$statut_j=',notice_statut';
		$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
	}
	
	
	// Si un path est renseigné et le paramètrage activé			
	if ($path && ($opac_auto_postage_descendant || $opac_auto_postage_montant || $auto_postage_etendre_recherche) && ($nb_level_montant || $nb_level_descendant)){
		
		//Recherche des fils 
		if(($opac_auto_postage_descendant || $opac_auto_postage_etendre_recherche)&& $nb_level_descendant) {
			if($nb_level_descendant != '*' && is_numeric($nb_level_descendant))
				$liste_fils=" path regexp '^$path(\\/[0-9]*){0,$nb_level_descendant}$' ";
			else 
				$liste_fils=" path regexp '^$path(\\/[0-9]*)*' ";
		} else {
			$liste_fils=" id_noeud='".$id."' ";
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
		// requete permettant de remonter les notices associées à la liste des catégories trouvées;
		//$suite_req = " FROM noeuds inner join notices_categories on id_noeud=num_noeud inner join notices on notcateg_notice=notice_id, notice_statut 
		//	WHERE ($liste_fils $liste_pere)	and (notices.statut = notice_statut.id_notice_statut 
		//	and ((notice_statut.notice_visible_opac = 1 and notice_statut.notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_statut.notice_visible_opac_abon=1 and notice_statut.notice_visible_opac = 1)":"").")) ";
		$suite_req = " FROM noeuds join notices_categories on id_noeud=num_noeud join notices on notcateg_notice=notice_id !!opac_phototeque!! $acces_j $statut_j ";
		$suite_req.= "WHERE ($liste_fils $liste_pere) $statut_r ";
		
	} else {	
		// cas normal d'avant		
		//$suite_req=" FROM notices_categories, notices, notice_statut WHERE (notices_categories.num_noeud = '".$id."' and notices_categories.notcateg_notice = notices.notice_id) and (notices.statut = notice_statut.id_notice_statut and ((notice_statut.notice_visible_opac = 1 and notice_statut.notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_statut.notice_visible_opac_abon=1 and notice_statut.notice_visible_opac = 1)":"").")) ";
		$suite_req = " FROM notices_categories join notices on notcateg_notice=notice_id !!opac_phototeque!! $acces_j $statut_j ";
		$suite_req.= "WHERE num_noeud=".$id." $statut_r ";
	}
	if ($path) {
		if ($opac_auto_postage_etendre_recherche == 1 || ($opac_auto_postage_etendre_recherche == 2 && !$nb_pere)) {
			//$base_path/index.php?lvl=categ_see&id=$id&main=$main&

			$input_txt="<input name='nb_level_enfants' type='text' size='2' value='$nb_level_descendant' 
				onchange=\"document.location='$base_path/index.php?lvl=categ_see&id=$id&main=$main&nb_level_enfants='+this.value\">";
			$auto_postage_form=str_replace("!!nb_level_enfants!!",$input_txt,$msg["categories_autopostage_enfants"]);
			
		} elseif ($opac_auto_postage_etendre_recherche == 2 && $nb_pere) {
			$input_txt="<input name='nb_level_enfants' id='nb_level_enfants' type='text' size='2' value='$nb_level_descendant' 
				onchange=\"document.location='$base_path/index.php?lvl=categ_see&id=$id&main=$main&nb_level_enfants='+this.value+'&nb_level_parents='+document.getElementById('nb_level_parents').value;\">";
			$auto_postage_form=str_replace("!!nb_level_enfants!!",$input_txt,$msg["categories_autopostage_parents_enfants"]);
		
			$input_txt="<input name='nb_level_parents' id='nb_level_parents' type='text' size='2' value='$nb_level_montant'		
				onchange=\"document.location='$base_path/index.php?lvl=categ_see&id=$id&main=$main&nb_level_parents='+this.value+'&nb_level_enfants='+document.getElementById('nb_level_enfants').value;\">";
			$auto_postage_form=str_replace("!!nb_level_parents!!",$input_txt,$auto_postage_form);
	
		} elseif ($opac_auto_postage_etendre_recherche == 3 ) {
			if($nb_pere) {
				$input_txt="<input name='nb_level_parents' type='text' size='2' value='$nb_level_montant'
					onchange=\"document.location='$base_path/index.php?lvl=categ_see&id=$id&main=$main&nb_level_parents='+this.value\">";
				$auto_postage_form=str_replace("!!nb_level_parents!!",$input_txt,$msg["categories_autopostage_parents"]);
			}
		}
	}

	// comptage des notices associées
	if (!$nbr_lignes) {
		$requete = "SELECT count(distinct notice_id) ".str_replace("!!opac_phototeque!!","",$suite_req);
		$res = mysql_query($requete, $dbh);
		
		$nbr_lignes = mysql_result($res, 0, 0);
		
		//Recherche des types doc
		$requete="select distinct notices.typdoc ";
		if($opac_visionneuse_allow){
			$requete.= ",count(explnum_id) as nbexplnum ";
			$suite_req = str_replace("!!opac_phototeque!!","LEFT JOIN explnum on notcateg_notice = explnum_notice and explnum_mimetype in ($opac_photo_filtre_mimetype)",$suite_req);
			$suite_req.= "group by notices.typdoc";
		}else $suite_req = str_replace("!!opac_phototeque!!","",$suite_req);
		$requete.= $suite_req;	
		$res = mysql_query($requete, $dbh);
		$t_typdoc=array();
		$nbexplnum_to_photo=0;
		while (($tpd=mysql_fetch_object($res))) {
			$t_typdoc[]=$tpd->typdoc;
			if($opac_visionneuse_allow)
				$nbexplnum_to_photo += $tpd->nbexplnum;
		}
		$l_typdoc=implode(",",$t_typdoc);
	}

	if (!$page) $page=1;
	$debut =($page-1)*$opac_nb_aut_rec_per_page;
	print pmb_bidi(str_replace("!!categ_libelle!!",$ourCateg->libelle,$categ_notices));
	if ($nbr_lignes) {
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
		
		if($opac_visionneuse_allow && $nbexplnum_to_photo){
			print "&nbsp;&nbsp;&nbsp;".$link_to_visionneuse;
			$sendToVisionneuseByGet = str_replace("!!mode!!","categ_see",$sendToVisionneuseByGet);
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
		if ($main) {
			//enregistrement de l'endroit actuel dans la session
			$_SESSION["last_module_search"]["search_mod"]="categ_see";
			$_SESSION["last_module_search"]["search_id"]=$id;
			$_SESSION["last_module_search"]["search_page"]=$page;
			
			//affichage
			print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=extended_search&mode_aff=aff_module'>".$msg["affiner_recherche"]."</a>";	
			//Etendre
			if ($opac_allow_external_search) print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=external_search&mode_aff=aff_module&external_type=simple'>".$msg["connecteurs_external_search_sources"]."</a>";
			//fin etendre			
		} else {
			//enregistrement de l'endroit actuel dans la session
			if ($_SESSION["last_query"]) {	$n=$_SESSION["last_query"]; } else { $n=$_SESSION["nb_queries"]; }
			
			$_SESSION["notice_view".$n]["search_mod"]="categ_see";
			$_SESSION["notice_view".$n]["search_id"]=$id;
			$_SESSION["notice_view".$n]["search_page"]=$page;	

			//affichage
			print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=extended_search&mode_aff=aff_simple_search'>".$msg["affiner_recherche"]."</a>";
			//Etendre
			if ($opac_allow_external_search) print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=external_search&mode_aff=aff_simple_search'>".$msg["connecteurs_external_search_sources"]."</a>";
			
			//fin etendre
		}
		//fin affinage
		if ($auto_postage_form) print "<div id='autopostageform'>".$auto_postage_form."</div>";
		print "<blockquote>\n";
		// on lance la vraie requête
		$requete = "SELECT distinct notices.notice_id $suite_req ORDER BY $opac_categories_categ_sort_records";
		
		//gestion du tri
		if ($_SESSION["last_sortnotices"]!="") {
			$requete = $sort->appliquer_tri($_SESSION["last_sortnotices"], $requete, "notice_id", $debut, $opac_nb_aut_rec_per_page);			
		} else {
			$requete .= " LIMIT $debut,$opac_nb_aut_rec_per_page ";
		}
		//fin gestion du tri
		$res = @mysql_query($requete, $dbh);
		print aff_notice(-1);
		while (($obj=mysql_fetch_object($res))) {
			global $infos_notice;
			print pmb_bidi(aff_notice($obj->notice_id));
			$infos_notice['nb_pages'] = ceil($nbr_lignes/$opac_nb_aut_rec_per_page);
		}
		print aff_notice(-2);
		mysql_free_result($res);

		// constitution des liens
		$nbepages = ceil($nbr_lignes/$opac_nb_aut_rec_per_page);
		print "</blockquote>\n";
		print "</div><!-- fermeture aut_details_liste -->\n";
		print "<hr />\n<center>".printnavbar($page, $nbepages, "./index.php?lvl=categ_see&id=$id&page=!!page!!&nbr_lignes=$nbr_lignes&main=$main&l_typdoc=".rawurlencode($l_typdoc))."</center>";	

	} else {
		print $msg['categ_empty'];
		if($auto_postage_form) print "<br />".$auto_postage_form;		
		print "</div><!-- fermeture aut_details_liste -->\n";
	}
} else {
	$ourCateg = new categorie(0);
	print pmb_bidi($ourCateg->child_list('./images/folder.gif',$css));
}

print "</div><!-- fermeture aut_details_container -->\n";
print "</div><!-- fermeture aut_details -->\n";
