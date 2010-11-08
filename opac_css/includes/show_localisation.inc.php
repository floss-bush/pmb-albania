<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: show_localisation.inc.php,v 1.49 2010-05-18 14:27:44 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if (!$opac_nb_sections_per_line) $opac_nb_sections_per_line=6;

function affiche_notice_navigopac($requete){
	global $page, $nbr_lignes, $id, $location, $dcote, $lcote, $nc, $main, $ssub,$plettreaut ;
	global $opac_nb_aut_rec_per_page,$opac_section_notices_order, $msg, $dbh, $opac_notices_depliable, $begin_result_liste, $add_cart_link_spe,$base_path;
	if(!$page) $page=1;
	$debut =($page-1)*$opac_nb_aut_rec_per_page;		
	//On controle paramètre de tri
	if(!trim($opac_section_notices_order)){
		$opac_section_notices_order= "index_serie, tnvol, index_sew";
	}
	if($plettreaut && $plettreaut !="vide"){
		$opac_section_notices_order= "index_author, ".$opac_section_notices_order;
	}
	$requete.= " ORDER BY ".$opac_section_notices_order." LIMIT $debut,$opac_nb_aut_rec_per_page";
	$res = @mysql_query($requete, $dbh);	
	print $nbr_lignes." ".$msg["results"]."<br />";
	
	//Recherche des types doc
	/*$requete="select distinct notices.typdoc FROM notices JOIN temp_n_id on notices.notice_id=temp_n_id.notice_id";
	$res_typdoc = mysql_query($requete, $dbh);
	$t_typdoc=array();
	while ($tpd=mysql_fetch_object($res_typdoc)) {
		$t_typdoc[]=$tpd->typdoc;
	}
	$l_typdoc=implode(",",$t_typdoc);*/
	
	if ($opac_notices_depliable) print $begin_result_liste;
	if ($add_cart_link_spe)
		print pmb_bidi(str_replace("!!spe!!","&location=$location&dcote=$dcote&lcote=$lcote&ssub=$ssub&nc=$nc&plettreaut=$plettreaut",$add_cart_link_spe));
	//affinage
	//enregistrement de l'endroit actuel dans la session
	$_SESSION["last_module_search"]["search_mod"]="section_see";
	$_SESSION["last_module_search"]["search_id"]=$id;
	$_SESSION["last_module_search"]["search_location"]=$location;
	$_SESSION["last_module_search"]["search_page"]=$page;
	
	//affinage
	if(!isset($dcote) && !isset($plettreaut) && !isset($nc)){
		print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=extended_search&mode_aff=aff_module'>".$msg["affiner_recherche"]."</a>";	
	}
	//fin affinage
	
	print "<blockquote>";
	print aff_notice(-1);
	while ($obj=mysql_fetch_object($res)) {
		print pmb_bidi(aff_notice($obj->notice_id));
	}
	print aff_notice(-2);
	print "</blockquote>";
	mysql_free_result($res);	
	// constitution des liens
	$nbepages = ceil($nbr_lignes/$opac_nb_aut_rec_per_page);
	print "<hr /><center>".printnavbar($page, $nbepages, "./index.php?lvl=section_see&id=$id&location=$location&page=!!page!!&nbr_lignes=$nbr_lignes&dcote=$dcote&lcote=$lcote&nc=$nc&main=$main&ssub=$ssub&plettreaut=$plettreaut")."</center>";
}

if (!$location) {
	//Il n'y a pas de localisation selectionnée, afficher les localisations
	print "<div id='aut_details'>\n";
	print "<h3><span>".htmlentities($msg["l_browse_bibliotheques"],ENT_QUOTES,$charset)."</span></h3>";

	print "<div id='aut_details_container'>\n";
	$requete="select idlocation, location_libelle, location_pic from docs_location where location_visible_opac=1 order by location_libelle ";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)>1) {
		print "<table align='center' width='100%'>";
		$npl=0;
		while ($r=mysql_fetch_object($resultat)) {
			if ($npl==0) print "<tr>";
			if ($r->location_pic) $image_src = $r->location_pic ;
			else  $image_src = "images/bibli-small.png" ;
			print "<td align='center'>
					<a href='./index.php?lvl=section_see&location=".$r->idlocation."'><img src='$image_src' border='0' alt='".$r->location_libelle."' title='".$r->location_libelle."'/></a>
					<br /><a href='./index.php?lvl=section_see&location=".$r->idlocation."'><b>".$r->location_libelle."</b></a></td>";
			$npl++;
			if ($npl==$opac_nb_localisations_per_line) {
				print "</tr>";
				$npl=0;
			}
		}
		if ($npl!=0) {
			while ($npl<$opac_nb_localisations_per_line) {
				print "<td></td>";
				$npl++;
			}
			print "</tr>";
		}
		print "</table>";
	} else {
		// zéro ou une seule localisation
		if (mysql_num_rows($resultat)) {
			$location=mysql_result($resultat,0,0);
			$requete="select idsection, section_libelle, section_pic from docs_section, exemplaires where expl_location=$location and section_visible_opac=1 and expl_section=idsection group by idsection order by section_libelle ";
			$resultat=mysql_query($requete);
			print "<table align='center' width='100%'>";
			$npl=0;
			while ($r=mysql_fetch_object($resultat)) {
				if ($npl==0) print "<tr>";
				if ($r->section_pic) $image_src = $r->section_pic ;
				else  $image_src = "images/rayonnage-small.png" ;
				print "<td align='center'>
						<a href='./index.php?lvl=section_see&location=".$location."&id=".$r->idsection."'><img src='$image_src' border='0' alt='".$r->section_libelle."' title='".$r->section_libelle."'/></a>
						<br /><a href='./index.php?lvl=section_see&location=".$location."&id=".$r->idsection."'><b>".$r->section_libelle."</b></a></td>";
				$npl++;
				if ($npl==$opac_nb_localisations_per_line) {
					print "</tr>";
					$npl=0;
				}
			}
			if ($npl!=0) {
				while ($npl<$opac_nb_localisations_per_line) {
					print "<td></td>";
					$npl++;
				}
				print "</tr>";
			}
			print "</table>";
		}
	}
} else {
	// id localisation fournie
	$location+=0;
	$requete="select location_libelle, location_pic, name, adr1, adr2, cp, town, state, country, phone, email, website, commentaire from docs_location where idlocation='$location' and location_visible_opac=1";
	$resultat=mysql_query($requete);
	$objloc=mysql_fetch_object($resultat);
	
	print "<div id='aut_details'>\n";
	print "<h3><span><a href=\"index.php?lvl=section_see\"><img src='images/home.gif' border='0' align='center'/></a>&nbsp;". htmlentities($objloc->location_libelle,ENT_QUOTES,$charset)."</span></h3>";
	if ($objloc->commentaire || $objloc->location_pic) {
		print "<table class='loc_comment'><tr><td width='3%'>";
		if ($objloc->location_pic) 
			print "&nbsp;<img src='".$objloc->location_pic."' border='0' align='center' />";
		else
			print "&nbsp;";
		print "</td><td>";
		if ($objloc->commentaire) 
			print $objloc->commentaire;
		else
			print "&nbsp;";
		print "</td></tr></table>";
	}
	$Fnm = "includes/mw_liste_type.inc.php";
	if (file_exists($Fnm)) { include($Fnm);}
	
	print "<div id='aut_details_container'>\n";
	
	//Il n'y a pas de section sélectionnée
	if (!$id) {
		$location+=0;
		$requete="select idsection, section_libelle, section_pic from docs_section, exemplaires where expl_location=$location and section_visible_opac=1 and expl_section=idsection group by idsection order by section_libelle ";
		$resultat=mysql_query($requete);
		print "<b>".sprintf($msg["l_title_search"],"<a href='index.php?'>","</a>")."</b><br /><br />";
		print "<table align='center' width='100%'>";
		$n=0;
		while ($r=mysql_fetch_object($resultat)) {
			if ($n==0) print "<tr>";
			if ($r->section_pic) $image_src = $r->section_pic ;
			else  $image_src = "images/rayonnage-small.png" ;
			print "<td align='center' width='120px'>
					<a href='./index.php?lvl=section_see&location=".$location."&id=".$r->idsection."'><img src='$image_src' border='0'/></a>
					<br /><a href='./index.php?lvl=section_see&location=".$location."&id=".$r->idsection."'><b>".htmlentities($r->section_libelle,ENT_QUOTES,$charset)."</b></a></td>";
			$n++;
			if ($n==$opac_nb_sections_per_line) { print "</tr>"; $n=0; } 
		}
		if ($n!=0) {
			while ($n<$opac_nb_sections_per_line) {
				print "<td></td>";
				$n++;
			}
			print "</tr>";
		}
		print "</table>";
	} else {
		$id+=0;
		$location+=0;
		$requete="select section_libelle, section_pic from docs_section where idsection=$id";
		$section_libelle=mysql_result(mysql_query($requete),0,0);
		$section_pic=mysql_result(mysql_query($requete),0,1);
		if ($section_pic) $image_src = $section_pic ;
		else  $image_src = "images/rayonnage-small.png" ;
		print "<div id='aut_see'><h3>";
		if (!file_exists($Fnm))	print "<a href='index.php?lvl=section_see&location=$location'><img src='".$image_src."' border='0' align='center' alt='".$msg["l_rayons"]."' title='".$msg["l_rayons"]."'/></a>&nbsp;";
		
		$requete="SELECT num_pclass FROM docsloc_section WHERE num_location='".$location."' AND num_section='".$id."' ";
		$res=mysql_query($requete);
		$type_aff_navigopac=0;
		if(mysql_num_rows($res)){
			$type_aff_navigopac=mysql_result($res,0,0);
		}
		
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
		
		if($type_aff_navigopac == 0){//Pas de navigation
			print pmb_bidi($section_libelle);
			print "</h3>\n";
			print "</div>";
			//On récupère les notices de monographie avec au moins un exemplaire dans la localisation et la section
			$requete="create temporary table temp_n_id ENGINE=MyISAM ( SELECT notice_id FROM notices ".$acces_j." JOIN exemplaires ON expl_section='".$id."' and expl_location='".$location."' and expl_notice=notice_id ".$statut_j." WHERE 1 ".$statut_r." GROUP BY notice_id)";
			mysql_query($requete) or die(mysql_error()."<br /><br />$requete<br /><br />");
			//On récupère les notices de périodique avec au moins un exemplaire d'un bulletin dans la localisation et la section
			$requete="INSERT INTO temp_n_id (SELECT notice_id FROM exemplaires JOIN bulletins ON expl_section='".$id."' and expl_location='".$location."' and expl_bulletin=bulletin_id JOIN notices ON notice_id=bulletin_notice ".$acces_j." ".$statut_j." WHERE 1 ".$statut_r." GROUP BY notice_id)";
			mysql_query($requete) or die(mysql_error()."<br /><br />$requete<br /><br />");
			@mysql_query("alter table temp_n_id add index(notice_id)");
			$requete = "SELECT notices.notice_id FROM temp_n_id JOIN notices ON notices.notice_id=temp_n_id.notice_id GROUP BY notices.notice_id";
			$nbr_lignes=mysql_num_rows(mysql_query($requete));
			affiche_notice_navigopac($requete);
		}elseif($type_aff_navigopac == -1){//Navigation par auteurs
			//On récupère les notices de monographie avec au moins un exemplaire dans la localisation et la section
			$requete="create temporary table temp_n_id ENGINE=MyISAM ( SELECT notice_id FROM notices ".$acces_j." JOIN exemplaires ON expl_section='".$id."' and expl_location='".$location."' and expl_notice=notice_id ".$statut_j." WHERE 1 ".$statut_r." GROUP BY notice_id)";
			mysql_query($requete) or die(mysql_error()."<br /><br />$requete<br /><br />");
			//On récupère les notices de périodique avec au moins un exemplaire d'un bulletin dans la localisation et la section
			$requete="INSERT INTO temp_n_id (SELECT notice_id FROM exemplaires JOIN bulletins ON expl_section='".$id."' and expl_location='".$location."' and expl_bulletin=bulletin_id JOIN notices ON notice_id=bulletin_notice ".$acces_j." ".$statut_j." WHERE 1 ".$statut_r." GROUP BY notice_id)";
			mysql_query($requete) or die(mysql_error()."<br /><br />$requete<br /><br />");
			@mysql_query("alter table temp_n_id add index(notice_id)");
			if(!$plettreaut){
				$nb_auteur_max=18;
				//On a pas encore choisi de première lettre d'auteur
				print pmb_bidi($section_libelle);
				print " > ".$msg["navigopac_aut"];
				print "</h3>\n";
				
				//On va chercher tous les auteurs des notices
				$requete = "SELECT IF(SUBSTRING(TRIM(index_author),1,1) != '' ,SUBSTRING(TRIM(index_author),1,1),'vide') as plettre, COUNT(1) as nb FROM temp_n_id LEFT JOIN responsability ON responsability_notice=notice_id LEFT JOIN authors ON author_id=responsability_author GROUP BY IF(index_author IS NOT NULL and TRIM(index_author) !='',SUBSTRING(TRIM(index_author),1,1),index_author) ORDER BY 1";
				$res=mysql_query($requete);
				$tab_aut=array();
				while ($ligne = mysql_fetch_object($res)) {
					//echo " Lettre : ".$ligne->plettre." Nombre : ".$ligne->nb."<br />";
					if($ligne->plettre == "vide"){
						if($tab_aut[$ligne->plettre]){
							$nb=$tab_aut[$ligne->plettre][0]+$ligne->nb;
							$tab_aut[$ligne->plettre]=array($nb,$msg["navigopac_ss_aut"]);
						}else{
							$tab_aut[$ligne->plettre]=array($ligne->nb,$msg["navigopac_ss_aut"]);
						}
					}elseif(ereg("[0-9]",$ligne->plettre)){
						if($tab_aut["num"]){
							$nb=$tab_aut["num"][0]+$ligne->nb;
							$tab_aut["num"]=array($nb,"0-9");
						}else{
							$tab_aut["num"]=array($ligne->nb,"0-9");
						}
					}else{
						$tab_aut[mb_strtoupper($ligne->plettre)]=array($ligne->nb,mb_strtoupper($ligne->plettre));
					}
				}
				while(count($tab_aut) > $nb_auteur_max){//Pour minimiser le nombre d'étagère à afficher
					//Je vais chercher deux valeurs qui peuvent être regroupées
					$coupl_plus_petit=10000000;
					$ancienne_valeur=0;
					$ancienne_lettre="";
					$lettre_a_regoupe=array();
					foreach ($tab_aut as $key => $value ) {
       					if($key != "num" && $key != "vide"){
       						if($ancienne_valeur && ($ancienne_valeur + $value[0] < $coupl_plus_petit)){
								$coupl_plus_petit=$ancienne_valeur + $value[0];
								$lettre_a_regoupe=array($ancienne_lettre,$key);
							}
							$ancienne_valeur=$value[0];
							$ancienne_lettre=$key;
       					}
					}
					//J'en regroupe deux
					$new_key=substr($lettre_a_regoupe[0],0,1)."-".substr($lettre_a_regoupe[1],-1);
					$tab_aut[$new_key]=array(($tab_aut[$lettre_a_regoupe[0]][0]*1+$tab_aut[$lettre_a_regoupe[1]][0]*1),$new_key);
					unset($tab_aut[$lettre_a_regoupe[0]]);
					unset($tab_aut[$lettre_a_regoupe[1]]);
					ksort($tab_aut);
				}
				print "<table align='center' width='100%'>";
				$n=0;
				foreach ( $tab_aut as $key => $value ) {
					if ($n==0) print "<tr>";
					print "<td width='120px'><a href='./index.php?lvl=section_see&location=".$location."&id=".$id."&plettreaut=".$key."'><img src='./images/folder.gif' align='center' border='0'/>".htmlentities($value[1],ENT_QUOTES,$charset)."</a></td>";
					/*$image_src = "images/folder.gif" ;
					print "<td align='center' width='120px'>
							<a href='./index.php?lvl=section_see&location=".$location."&id=".$id."&plettreaut=".$key." '><img src='$image_src' border='0'/></a>
							<br /><a href='./index.php?lvl=section_see&location=".$location."&id=".$id."&plettreaut=".$key." '><b>".htmlentities($value[1],ENT_QUOTES,$charset)."</b></a></td>";*/
					$n++;
					if ($n==$opac_nb_sections_per_line) { print "</tr>"; $n=0; } 
				}
				if ($n!=0) {
					while ($n<$opac_nb_sections_per_line) {
						print "<td></td>";
						$n++;
					}
					print "</tr>";
				}
				print "</table>";
				print "</div>";
				$requete = "SELECT notices.notice_id FROM temp_n_id JOIN notices ON notices.notice_id=temp_n_id.notice_id GROUP BY notices.notice_id";
				$nbr_lignes=mysql_num_rows(mysql_query($requete));
				affiche_notice_navigopac($requete);
			}else{
				//On sait par quoi doit commencer le nom de l'auteur
				print "<a href='index.php?lvl=section_see&location=$location&id=$id'>";
				print pmb_bidi($section_libelle);
				print "</a>";
				
				if($plettreaut == "num"){
					$requete = "SELECT notices.notice_id FROM temp_n_id JOIN responsability ON responsability_notice=temp_n_id.notice_id JOIN authors ON author_id=responsability_author and trim(index_author) REGEXP '^[0-9]' JOIN notices ON notices.notice_id=temp_n_id.notice_id GROUP BY notices.notice_id";
					print " > ".$msg["navigopac_aut_com_par_chiffre"];
				}elseif($plettreaut == "vide"){
					$requete = "SELECT notices.notice_id FROM temp_n_id LEFT JOIN responsability ON responsability_notice=temp_n_id.notice_id LEFT JOIN notices ON notices.notice_id=temp_n_id.notice_id WHERE responsability_author IS NULL GROUP BY notices.notice_id";
					print " > ".$msg["navigopac_ss_aut"];
				}else{
					$requete = "SELECT notices.notice_id FROM temp_n_id JOIN responsability ON responsability_notice=temp_n_id.notice_id JOIN authors ON author_id=responsability_author and trim(index_author) REGEXP '^[".$plettreaut."]' JOIN notices ON notices.notice_id=temp_n_id.notice_id GROUP BY notices.notice_id";
					print " > ".$msg["navigopac_aut_com_par"]." ".$plettreaut;
				}
				$nbr_lignes=mysql_num_rows(mysql_query($requete));
				print "</h3>\n";
				print "</div>";
				affiche_notice_navigopac($requete);
			}
		}else{//Navigation par un plan de classement
			
			if (strlen($dcote)||($nc==1)) print "<a href='index.php?lvl=section_see&location=$location&id=$id'>";
			print pmb_bidi($section_libelle);
		
			if (strlen($dcote)||($nc==1)) print "</a>";
			//Calcul du chemin
			if (strlen($dcote)) {
				if (!$ssub) {
					for ($i=0; $i<strlen($dcote); $i++) {
						$chemin="";
						$ccote=substr($dcote,0,$i+1);
						$ccote=$ccote.str_repeat("0",$lcote-$i-1);
						if ($i>0) {
							$cote_n_1=substr($dcote,0,$i);
							$compl_n_1=str_repeat("0",$lcote-$i);
							if (($ccote)==($cote_n_1.$compl_n_1)) $chemin=$msg["l_general"];
						}
						if (!$chemin) {
							$requete="select indexint_name,indexint_comment from indexint where indexint_name='".$ccote."' and num_pclass='".$type_aff_navigopac."'";
							$res_ch=mysql_query($requete);
							if (mysql_num_rows($res_ch))
								$chemin=mysql_result(mysql_query($requete),0,1);
							else
								$chemin=$msg["l_unclassified"];
						}
						print " > ";
						if ((($i+1)<strlen($dcote))||($nc==1)) print "<a href='index.php?lvl=section_see&location=$location&id=$id&dcote=".substr($dcote,0,$i+1)."&lcote=$lcote'>";
						print pmb_bidi($chemin);
						if ((($i+1)<strlen($dcote))||($nc==1)) print "</a>"; else $theme=$chemin;
					}
				} else {
					$t_dcote=explode(",",$dcote);
					$requete="select indexint_comment from indexint where indexint_name='".stripslashes($t_dcote[0])."' and num_pclass='".$type_aff_navigopac."'";
					$res_ch=mysql_query($requete);
					if (mysql_num_rows($res_ch))
						$chemin=mysql_result(mysql_query($requete),0,0);
					else
						$chemin=$msg["l_unclassified"];
					print pmb_bidi(" > ".$chemin);
				}
			}
			if ($nc==1) { print " > ".$msg["l_unclassified"]; $theme=$msg["l_unclassified"]; }
			print "</h3>\n";
			if ($ssub) {
				$t_expl_cote_cond=array();
				for ($i=0; $i<count($t_dcote); $i++) {
					$t_expl_cote_cond[]="expl_cote regexp '(^".$t_dcote[$i]." )|(^".$t_dcote[$i]."[0-9])|(^".$t_dcote[$i]."$)|(^".$t_dcote[$i].".)'";
				}
				$expl_cote_cond="(".implode(" or ",$t_expl_cote_cond).")";
			}			
			
			if(!$nbr_lignes) {
							
				if (!$ssub) {
					/*
					$requete = "SELECT COUNT(distinct notice_id) FROM notices,exemplaires, notice_statut where  expl_location=$location and expl_section=$id and notice_id=expl_notice ";
					if (strlen($dcote)) {
						$requete.=" and expl_cote regexp '".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote))."' and expl_cote not regexp '(\\\\.[0-9]*".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")|([^0-9]*[0-9]+\\\\.?[0-9]*.+".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")'";
					}
					$requete.=" and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
					*/
					$requete = "SELECT COUNT(distinct notice_id) FROM notices $acces_j ,exemplaires $statut_j ";
					$requete.= "where expl_location=$location and expl_section=$id and notice_id=expl_notice ";
					if (strlen($dcote)) {
						$requete.= "and expl_cote regexp '".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote))."' and expl_cote not regexp '(\\\\.[0-9]*".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")|([^0-9]*[0-9]+\\\\.?[0-9]*.+".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")' ";
					}
					$requete.= $statut_r;
					$res = mysql_query($requete, $dbh);
					$nbr_lignes = @mysql_result($res, 0, 0);
	
					/*
					$requete2 = "SELECT COUNT(distinct notice_id) FROM notices,exemplaires, bulletins, notice_statut where  expl_location=$location and expl_section=$id and notice_id=bulletin_notice and expl_bulletin=bulletin_id";
					if (strlen($dcote)) {
						$requete2.=" and expl_cote regexp '".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote))."' and expl_cote not regexp '(\\\\.[0-9]*".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")|([^0-9]*[0-9]+\\\\.?[0-9]*.+".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")'";
					}
					$requete2.=" and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
					*/
					$requete2 = "SELECT COUNT(distinct notice_id) FROM notices $acces_j ,exemplaires, bulletins $statut_j ";
					$requete2.= "where  expl_location=$location and expl_section=$id and notice_id=bulletin_notice and expl_bulletin=bulletin_id ";
					if (strlen($dcote)) {
						$requete2.= "and expl_cote regexp '".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote))."' and expl_cote not regexp '(\\\\.[0-9]*".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")|([^0-9]*[0-9]+\\\\.?[0-9]*.+".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")' ";
					}
					$requete2.= $statut_r;
					$res = mysql_query($requete2, $dbh);
					$nbr_lignes += @mysql_result($res, 0, 0);
					
				} else {
					/*
					$requete="select COUNT(distinct notice_id) FROM notices,exemplaires,notice_statut where  expl_location=$location and expl_section=$id and notice_id=expl_notice";
					if (strlen($dcote)) {
						$requete.=" and ".$expl_cote_cond;
					}
					$requete.=" and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
					*/
					$requete = "select COUNT(distinct notice_id) FROM notices $acces_j ,exemplaires $statut_j ";
					$requete.= "where expl_location=$location and expl_section=$id and notice_id=expl_notice ";
					if (strlen($dcote)) {
						$requete.= " and $expl_cote_cond ";
					}
					$requete.= $statut_r;
					$res = mysql_query($requete, $dbh);
					$nbr_lignes = @mysql_result($res, 0, 0);		
					
					/*
					$requete2 = "SELECT COUNT(distinct notice_id) FROM notices,exemplaires, bulletins, notice_statut where  expl_location=$location and expl_section=$id and notice_id=bulletin_notice and expl_bulletin=bulletin_id";
					if (strlen($dcote)) {
						$requete2.=" and ".$expl_cote_cond;
					}
					$requete2.=" and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
					*/
					$requete2 = "SELECT COUNT(distinct notice_id) FROM notices $acces_j ,exemplaires, bulletins $statut_j ";
					$requete2.= "where  expl_location=$location and expl_section=$id and notice_id=bulletin_notice and expl_bulletin=bulletin_id ";
					if (strlen($dcote)) {
						$requete2.= "and $expl_cote_cond ";
					}
					$requete2.= $statut_r;
					$res = mysql_query($requete2, $dbh);
					$nbr_lignes += @mysql_result($res, 0, 0);
					
				}
			}

			if($nbr_lignes) {
				//Table temporaire de tous les id
				/*
				$requete="create temporary table temp_n_id ENGINE=MyISAM (select notice_id FROM notices,exemplaires, notice_statut WHERE expl_location=$location and expl_section=$id and notice_id=expl_notice ";
				if (strlen($dcote)) {
					if (!$ssub) {	
						$requete.=" and expl_cote regexp '".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote))."' and expl_cote not regexp '(\\\\.[0-9]*".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")|([^0-9]*[0-9]+\\\\.?[0-9]*.+".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")'";
						$level_ref=strlen($dcote)+1;
					} else {
						$requete.=" and ".$expl_cote_cond;
					}
				}
				$requete.=" and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
				$requete.=" group by notice_id ";
				$requete .= " ORDER BY index_serie,tnvol,index_sew) ";
				*/
				$requete = "create temporary table temp_n_id ENGINE=MyISAM (select notice_id FROM notices $acces_j ,exemplaires $statut_j ";
				$requete.= "WHERE expl_location=$location and expl_section=$id and notice_id=expl_notice ";
				if (strlen($dcote)) {
					if (!$ssub) {	
						$requete.= "and expl_cote regexp '".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote))."' and expl_cote not regexp '(\\\\.[0-9]*".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")|([^0-9]*[0-9]+\\\\.?[0-9]*.+".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")' ";
						$level_ref=strlen($dcote)+1;
					} else {
						$requete.= "and $expl_cote_cond ";
					}
				}
				$requete.= "$statut_r ";
				$requete.= "group by notice_id) ";
				mysql_query($requete) or die(mysql_error()."<br /><br />$requete<br /><br />");
				
				/*
				$requete2 = " insert into temp_n_id (SELECT notice_id FROM notices,exemplaires, bulletins, notice_statut where  expl_location=$location and expl_section=$id and notice_id=bulletin_notice and expl_bulletin=bulletin_id";
				if (strlen($dcote)) {
					if (!$ssub) {
						$requete2.=" and expl_cote regexp '".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote))."' and expl_cote not regexp '(\\\\.[0-9]*".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")|([^0-9]*[0-9]+\\\\.?[0-9]*.+".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")'";
					} else {
						$requete2.=" and ".$expl_cote_cond;
					}
				}
				$requete2.=" and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
				$requete2.=" group by notice_id ";
				$requete2.= " ORDER BY index_serie,tnvol,index_sew )";	
				*/	
				$requete2 = "insert into temp_n_id (SELECT notice_id FROM notices $acces_j ,exemplaires, bulletins $statut_j ";
				$requete2.= "where  expl_location=$location and expl_section=$id and notice_id=bulletin_notice and expl_bulletin=bulletin_id ";
				if (strlen($dcote)) {
					if (!$ssub) {
						$requete2.= "and expl_cote regexp '".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote))."' and expl_cote not regexp '(\\\\.[0-9]*".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")|([^0-9]*[0-9]+\\\\.?[0-9]*.+".$dcote.str_repeat("[0-9]",$lcote-strlen($dcote)).")' ";
					} else {
						$requete2.= "and $expl_cote_cond ";
					}
				}
				$requete2.= "$statut_r ";
				$requete2.= "group by notice_id) ";
				@mysql_query($requete2);
				@mysql_query("alter table temp_n_id add index(notice_id)");
				//Calcul du classement
				if (!$ssub) {
					$rq1_index="create temporary table union1 ENGINE=MyISAM (select distinct expl_cote from exemplaires, temp_n_id where expl_location=$location and expl_section=$id and expl_notice=temp_n_id.notice_id) ";
					$res1_index=mysql_query($rq1_index);
					$rq2_index="create temporary table union2 ENGINE=MyISAM (select distinct expl_cote from exemplaires, temp_n_id, bulletins where expl_location=$location and expl_section=$id and bulletin_notice=temp_n_id.notice_id and expl_bulletin=bulletin_id) ";
					$res2_index=mysql_query($rq2_index);			
					$req_index="select distinct expl_cote from union1 union select distinct expl_cote from union2";
					$res_index=mysql_query($req_index);
					
					if ($level_ref==0) $level_ref=1;
					
					// Prepare indexint pre selection - Zend
					$zendIndexInt = array();
					//$zendIndexIntCache = array();
					$zendQ1 = "SELECT indexint_name, indexint_comment FROM indexint WHERE indexint_name NOT REGEXP '^[0-9][0-9][0-9]' AND indexint_comment != '' AND num_pclass='".$type_aff_navigopac."'";
					$zendRes = mysql_query($zendQ1);
					while ($zendRow = mysql_fetch_assoc($zendRes)) {
						$zendIndexInt[$zendRow['indexint_name']] = $zendRow['indexint_comment'];
					}
					// Zend
					while ($ct=mysql_fetch_object($res_index)) {
						//Je regarde si le début existe dans indexint
						$lf=5;
						$t=array();
						while ($lf>0) {
							$zendKey = substr($ct->expl_cote, 0, $lf);
							if ($zendIndexInt[$zendKey]) {
								if (!$nc) {
									$t["comment"]=$zendIndexInt[$zendKey];
									$t["dcote"]=$zendKey;
							/*$rq_index_lf="select indexint_name,indexint_comment from indexint where indexint_name='".addslashes(substr($ct->expl_cote,0,$lf))."' and (indexint_name not regexp '^[0-9][0-9][0-9]') and indexint_comment!='' and num_pclass='".$type_aff_navigopac."' limit 1";
							$res_index_lf=mysql_query($rq_index_lf); 
							if (mysql_num_rows($res_index_lf)) {
								if (!$nc) {		
									$t["comment"]=mysql_result($res_index_lf,0,1);
									$t["dcote"]=mysql_result($res_index_lf,0,0);*/
									$t["ssub"]=1;
									$index[$t["dcote"]]=$t;
									break;
								} else {
									$rq_del="select distinct notice_id from notices, exemplaires where expl_cote='".$ct->expl_cote."' and expl_notice=notice_id ";
									$rq_del.=" union select distinct notice_id from notices, exemplaires, bulletins where expl_cote='".$ct->expl_cote."' and expl_bulletin=bulletin_id and bulletin_notice=notice_id ";
									$res_del=mysql_query($rq_del) ;
									while (list($n_id)=mysql_fetch_row($res_del)) {
										mysql_query("delete from temp_n_id where notice_id=".$n_id);
									}
								}
							} 
							$lf--;
						}
						if ($lf==0) {
							if (preg_match("/[0-9][0-9][0-9]/",$ct->expl_cote,$c)) {
								$found=false;
								$lcote=3;
								//$lcote=(strlen($c[0])>=3) ? 3 : strlen($c[0]);
								$level=$level_ref;
								while ((!$found)&&($level<=$lcote)) {
									$cote=substr($c[0],0,$level);
									$compl=str_repeat("0",$lcote-$level);
									$rq_index="select indexint_name,indexint_comment from indexint where indexint_name='".$cote.$compl."' and length(indexint_name)>=$lcote and indexint_comment!='' and num_pclass='".$type_aff_navigopac."' order by indexint_name limit 1 ";
									$res_index_1=mysql_query($rq_index);
									if (mysql_num_rows($res_index_1)) {
										$name=mysql_result($res_index_1,0,0);
										if (!$nc) {
											if (substr($name,0,$level-1)==$dcote) {
												$t["comment"]=mysql_result($res_index_1,0,1);
												if ($level>1) {
													$cote_n_1=substr($c[0],0,$level-1);
													$compl_n_1=str_repeat("0",$lcote-$level+1);
													if (($cote.$compl)==($cote_n_1.$compl_n_1))
														$t["comment"]="Généralités";
												}
												$t["lcote"]=$lcote;
												$t["dcote"]=$cote;
												$index[$name]=$t;
												$found=true;
											} else $level++;
										} else {
											if (substr($name,0,$level-1)==$dcote) {
												$rq_del="select distinct notice_id from notices, exemplaires where expl_cote='".$ct->expl_cote."' and expl_notice=notice_id ";
												$rq_del.=" union select distinct notice_id from notices, exemplaires, bulletins where expl_cote='".$ct->expl_cote."' and expl_bulletin=bulletin_id and bulletin_notice=notice_id ";
												$res_del=mysql_query($rq_del);
												while (list($n_id)=mysql_fetch_row($res_del)) {
													mysql_query("delete from temp_n_id where notice_id=".$n_id);
												}
												$found=true;
											} else $level++;
										}
									} else $level++;
								}
								if (($level>$lcote)&&($lf==0)) {
									$t["comment"]=$msg["l_unclassified"];
									$t["lcote"]=$lcote;
									$t["dcote"]=$dcote;
									$index["NC"]=$t;
								}
							} else {
								$t["comment"]=$msg["l_unclassified"];
								$t["lcote"]=$lcote;
								$t["dcote"]=$dcote;
								$index["NC"]=$t;
							}
						}
					}
				}
				if ($nc) {
					$nbr_lignes=mysql_result(mysql_query("select count(1) from temp_n_id"),0,0);
				}
				if ($nbr_lignes) {
					//Affichage des sous catégories
					if (count($index)>1) {
						if (!strlen($dcote)) 
							print pmb_bidi(sprintf($msg["l_etageres"],htmlentities($section_libelle,ENT_QUOTES,$charset)));
						else if (strlen($dcote)==1)
							print pmb_bidi(sprintf($msg["l_themes"],htmlentities($theme,ENT_QUOTES,$charset)));
						else
							pmb_bidi(print sprintf($msg["l_sub_themes"],htmlentities($theme,ENT_QUOTES,$charset)));
						reset($index);
						$ssub_val=array();
						//Regroupement des libellés identiques hors dewey
						while (list($key,$val)=each($index)) {
							if ($val["ssub"]) {
								if ($ssub_val[$val["comment"]]) {
									$ssub_val[$val["comment"]]["dcote"].=",".$val["dcote"];
								} else {
									$ssub_val[$val["comment"]]=$val;
								}
							} else {
								$ssub_val[$val["comment"]."@ssub"]=$val;
							}
						}
						//Affichage du classement si il reste suffisamment de catégories
						if (count($ssub_val)>1) {	
							$opac_categories_nb_col_subcat;
							$cur_col=0;
							reset($ssub_val);
							asort($ssub_val);
							print "<table>";
							while (list($key,$val)=each($ssub_val)) {
								if ($cur_col==0) print "<tr>";
								if (($key=="NC")||($key==$msg["l_unclassified"]."@ssub")) $nc1=1; else $nc1=0;
								print "<td width='33%'><a href='./index.php?lvl=section_see&id=$id&location=$location&dcote=".$val["dcote"]."&lcote=".$val["lcote"]."&nc=$nc1&ssub=".$val["ssub"]."'><img src='./images/folder.gif' align='center' border='0'/>".htmlentities($val["comment"],ENT_QUOTES,$charset)."</a></td>";
								$cur_col++;
								if ($cur_col==$opac_categories_nb_col_subcat) {
									print "</tr>";
									$cur_col=0;
								}
							}
							if ($cur_col<$opac_categories_nb_col_subcat) {
								for ($i=$curl_col; $i<$opac_categories_nb_col_subcat; $i++) {
									print "<td>&nbsp;</td>";
								}
								print "</tr>";
							} 
							print "</table><br />";
						}
					}
					print "</div>";
					$requete = "SELECT notices.notice_id FROM temp_n_id JOIN notices ON notices.notice_id=temp_n_id.notice_id ";
					affiche_notice_navigopac($requete);
				} else {
					print "</div><br /><blockquote>$msg[categ_empty]</blockquote><br />";
				}
			} else {
				print "</div><br /><blockquote>$msg[categ_empty]</blockquote><br />";
			}
		}
	}
}
print "</div><!-- / #aut_details_container -->\n";
print "</div><!-- / #aut_details -->\n";
?>