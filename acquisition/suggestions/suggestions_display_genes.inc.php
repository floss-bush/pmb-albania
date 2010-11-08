<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions_display_genes.inc.php,v 1.16 2010-02-23 16:43:48 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die ("no access");

require_once($class_path.'/suggestions.class.php');
require_once($class_path.'/suggestions_origine.class.php');
require_once($class_path.'/suggestions_categ.class.php');
require_once($class_path.'/suggestions_map.class.php');
require_once($class_path.'/suggestion_source.class.php');
require_once($include_path.'/templates/suggestions.tpl.php');
require_once($class_path.'/notice.class.php');
require_once($class_path.'/author.class.php');
require_once($class_path.'/docs_location.class.php');
require_once($include_path.'/misc.inc.php');
require_once($include_path.'/parser.inc.php');
require_once($include_path.'/templates/suggestions_genes.tpl.php');

//Affiche la liste des suggestions
function show_list_sug($id_bibli=0) {
	
	global $dbh,$base_path;
	global $msg, $charset;
	global $sug_map;
	global $sug_search_form, $sug_list_form, $filtre_src, $user_txt, $user_id, $user_statut;
	global $nb_per_page;
	global $class_path;
	global $user_input, $statut, $num_categ, $page, $nbr_lignes, $last_param;
	global $script, $bt_chk, $bt_imp, $bt_exporter;
	global $acquisition_sugg_categ;
	global $acquisition_sugg_localises,$sugg_location_id;
	global $deflt_docs_location;

	if ($acquisition_sugg_localises) {	
		 $sugg_location_id=((string)$sugg_location_id==""?$deflt_docs_location:$sugg_location_id);
	}
	// nombre de références par pages
	if (!$nb_per_page) $nb_per_page = 10;		
		
	//Affichage form de recherche
	$titre = htmlentities($msg['recherche'].' : '.$msg['acquisition_sug'], ENT_QUOTES, $charset);
	$action ="./acquisition.php?categ=sug&action=list&id_bibli=".$id_bibli."&user_input=&nb_per_page=".$nb_per_page;
	$bouton_add = "<input class='bouton' type='button' value='".$msg['acquisition_ajout_sug']."' onClick=\"document.location='./acquisition.php?categ=sug&sub=todo&action=modif&id_bibli=".$id_bibli."&sugg_location_id=$sugg_location_id';\" />";
	$lien_last_sug = "";
	$sug_search_form = str_replace('!!form_title!!', $titre, $sug_search_form);
	$sug_search_form = str_replace('!!action!!', $action, $sug_search_form);
	$sug_search_form = str_replace('<!-- sel_state -->', $sug_map->getStateSelector(), $sug_search_form);
	$sug_search_form = str_replace('<!-- bouton_add -->', $bouton_add, $sug_search_form);
	$sug_search_form = str_replace('<!-- lien_last -->', $lien_last_sug, $sug_search_form);

	//Selecteur de localisation
	$list_locs='';
	if ($acquisition_sugg_localises) {
		if ($sugg_location_id) $temp_location=$sugg_location_id;
		else $temp_location=0;
		$locs=new docs_location();
		$list_locs=$locs->gen_combo_box_sugg($temp_location,1,"submit();");
	}
	$sug_search_form = str_replace('<!-- sel_location -->', $list_locs, $sug_search_form);
	
	//Selecteur de categories
	if ($acquisition_sugg_categ != '1') {
		$sel_categ="";
	} else {
		$tab_categ = suggestions_categ::getCategList();
		$sel_categ = "<select class='saisie-25em' id='num_categ' name='num_categ' onchange=\"submit();\">";
		$sel_categ.= "<option value='-1'>".htmlentities($msg['acquisition_sug_tous'],ENT_QUOTES, $charset)."</option>";
		foreach($tab_categ as $id_categ=>$lib_categ){
			$sel_categ.= "<option value='".$id_categ."' > ";
			$sel_categ.= htmlentities($lib_categ,ENT_QUOTES, $charset)."</option>";
		}
		$sel_categ.= "</select>";
	}
	$sug_search_form = str_replace('<!-- sel_categ -->', $sel_categ, $sug_search_form);

	//Affichage du filtre par source
	$req = "select * from suggestions_source order by libelle_source";
	$res= mysql_query($req,$dbh);
	$selected ="";
	$option = "<option value='0'>".htmlentities($msg['acquisition_sugg_all_sources'],ENT_QUOTES,$charset)."</option>";
	while(($src=mysql_fetch_object($res))){
		($src->id_source == $filtre_src) ? $selected = "selected" : $selected="";
		$option .= "<option value='".$src->id_source."' $selected>".htmlentities($src->libelle_source,ENT_QUOTES,$charset)."</option>";
	}
	$selecteur = "&nbsp;<select id='filtre_src' name='filtre_src' onchange=\"this.form.submit();\">".$option."</select>";
	$sug_search_form = str_replace('!!sug_filtre_src!!',$selecteur, $sug_search_form); 	 
	$user_name = $user_txt;
	if(!$user_txt && $user_id){
		$req = "select concat(empr_nom,', ',empr_prenom) as nom from empr where id_empr='".$user_id."'";
		$res = mysql_query($req,$dbh);
		$empr = mysql_fetch_object($res);
		$user_name = $empr->nom;
	}
	$sug_search_form = str_replace('!!user_txt!!',htmlentities($user_name,ENT_QUOTES,$charset), $sug_search_form); 
	$sug_search_form = str_replace('!!user_id!!',htmlentities($user_id,ENT_QUOTES,$charset), $sug_search_form); 
	$sug_search_form = str_replace('!!user_statut!!',htmlentities($user_statut,ENT_QUOTES,$charset), $sug_search_form); 
	
	$sug_search_form = str_replace('!!user_input!!',htmlentities($user_input,ENT_QUOTES,$charset), $sug_search_form);
	print $sug_search_form;
	
	//Affiche par defaut toutes les categories de suggestions
	if ($acquisition_sugg_categ != '1') {
		$num_categ = "-1";
	} else {
		if (!$num_categ) $num_categ = '-1';
		print "<script type='text/javascript' >document.forms['search'].elements['num_categ'].value = '".$num_categ."';</script>";
	}
	
	if (!$statut) {
		$statut = getSessionSugState(); //Recuperation du statut courant
	} else {
		setSessionSugState($statut);	
	}
	print "<script type='text/javascript' >document.forms['search'].elements['statut'].value = '".$statut."';document.forms['search'].elements['user_input'].focus();</script>";
	
	
	//Prise en compte du formulaire de recherche
	$mask=$sug_map->getMask_FILED();
	
	// traitement de la saisie utilisateur

	require_once($class_path."/analyse_query.class.php");
	
	//comptage
	if(!$nbr_lignes) {
		if(!$user_input) {
			$nbr_lignes = suggestions::getNbSuggestions($id_bibli, $statut, $num_categ, $mask ,0,$sugg_location_id,'',$filtre_src,$user_id,$user_statut);
		} else {
			$aq=new analyse_query(stripslashes($user_input),0,0,0,0);
			if ($aq->error) {
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				exit;
			}
			$nbr_lignes = suggestions::getNbSuggestions($id_bibli, $statut, $num_categ, $mask, $aq,$sugg_location_id, $user_input,$filtre_src,$user_id,$user_statut);
		}

	} else {
		$aq=new analyse_query(stripslashes($user_input),0,0,0,0);
	}
	
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
	
		$url_base = "acquisition.php?categ=sug&action=list&id_bibli=$id_bibli&user_input=".rawurlencode(stripslashes($user_input))."&statut=$statut&num_categ=$num_categ&sugg_location_id=$sugg_location_id&filtre_src=$filtre_src&user_id=$user_id&user_statut=$user_statut" ;
		//affichage
		if(!$user_input) {
			$q = suggestions::listSuggestions($id_bibli, $statut, $num_categ, $mask, $debut, $nb_per_page,0,'',$sugg_location_id,'', $filtre_src, $user_id, $user_statut);
		} else {
			$q = suggestions::listSuggestions($id_bibli, $statut, $num_categ, $mask, $debut, $nb_per_page, $aq,'',$sugg_location_id, $user_input, $filtre_src, $user_id, $user_statut);
		}
		$res = mysql_query($q, $dbh);
	
		//Affichage liste des suggestions
		$nbr = mysql_num_rows($res);
		$aff_row="";
		$parity=1;
		for($i=0;$i<$nbr;$i++) {
			$row=mysql_fetch_object($res);
			
			//recuperation origine
			$lib_orig = "";
			$typ_orig = "0";
			
			$q = suggestions_origine::listOccurences($row->id_suggestion, '1');
			$list_orig = mysql_query($q, $dbh);
			
			if (mysql_num_rows($list_orig)) {
				$row_orig = mysql_fetch_object($list_orig);
				$orig = $row_orig->origine;
				$typ_orig = $row_orig->type_origine;
			}

			//Récupération du nom du créateur de la suggestion
			$idempr = 0;
			switch($typ_orig){
				default:
				case '0' :
				 	$requete_user = "SELECT userid, nom, prenom FROM users where userid = '".$orig."' limit 1 ";
					$res_user = mysql_query($requete_user, $dbh);
					$row_user=mysql_fetch_row($res_user);
					$lib_orig = $row_user[1];
					if ($row_user[2]) $lib_orig.= ", ".$row_user[2];
					break;
				case '1' :
				 	$requete_empr = "SELECT id_empr, empr_nom, empr_prenom, empr_adr1 FROM empr where id_empr = '".$orig."' limit 1 ";
					$res_empr = mysql_query($requete_empr, $dbh);
					$row_empr=mysql_fetch_row($res_empr);
					$lib_orig = $row_empr[1];
					if ($row_empr[2]) $lib_orig.= ", ".$row_empr[2];	
					$idempr = $row_empr[0];	
					break;
				case '2' :
					$lib_orig = $orig;
					break;
			}	

			$lib_statut=$sug_map->getHtmlComment($row->statut);
			
			$col2="";
			if (trim($row->code)!="") $col2=htmlentities(trim($row->code), ENT_QUOTES, $charset)."<br />";
			$col2.=htmlentities(trim($row->titre), ENT_QUOTES, $charset);
			
			$col3="";
			$col30="";
			$col31="";
			if (trim($row->auteur)!="") $col30=htmlentities(trim($row->auteur), ENT_QUOTES, $charset);
			if (trim($row->editeur)!="")  $col31 ="[".htmlentities(trim($row->editeur), ENT_QUOTES, $charset)."]";
			$col3=$col30;
			if ($col3!="" && $col31!="") $col3.="<br />";
			$col3.=$col31; 
			
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript_l1 = "onmouseover=\"this.className='surbrillance';this.parentNode.rows[this.rowIndex+1].className='surbrillance';\" onmouseout=\"this.className='".$pair_impair."';this.parentNode.rows[this.rowIndex+1].className='".$pair_impair."'\" ";
			$tr_javascript_l2 = "onmouseover=\"this.className='surbrillance';this.parentNode.rows[this.rowIndex-1].className='surbrillance';\" onmouseout=\"this.className='".$pair_impair."';this.parentNode.rows[this.rowIndex-1].className='".$pair_impair."'\" ";
			$dn_javascript = "onmousedown=\"document.location='./acquisition.php?categ=sug&action=modif&id_bibli=".$id_bibli."&id_sug=".$row->id_suggestion."' \" ";
			if($idempr) {
				$link_empr = "<a href='circ.php?categ=pret&id_empr=$idempr'>";
				$link_empr_fin = "</a>";
			} else {
				$link_empr="";
				$link_empr_fin="";
			}
	        $aff_row.=	"<tr class='".$pair_impair."' ".$tr_javascript_l1." style='cursor: pointer' >
						<td ".$dn_javascript." >".formatdate($row->date_creation)."<br />$link_empr".htmlentities($lib_orig, ENT_QUOTES, $charset)."$link_empr_fin</td>
						<td ".$dn_javascript." >".$col2."</td>
						<td ".$dn_javascript." >".$col3."</td>
						<td ".$dn_javascript." >".htmlentities($row->nb, ENT_QUOTES, $charset)."</td>
						<td ".$dn_javascript." >".htmlentities($row->prix, ENT_QUOTES, $charset)."</td>
						<td ".$dn_javascript." >$lib_statut</td>";
	        if(!$row->num_notice) {
				$aff_row.="<td ".$dn_javascript." ></td>";
			} else {
				$req_ana = "select analysis_bulletin as bull , analysis_notice as noti from analysis where analysis_notice ='".$row->num_notice."'";	
				$res_ana = mysql_query($req_ana,$dbh);
				$num_rows_ana = mysql_num_rows($res_ana);			
				if($num_rows_ana){
					$ana = mysql_fetch_object($res_ana);
					$url_view = "catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$ana->bull&art_to_show=".$ana->noti;
				} else $url_view = "./catalog.php?categ=isbd&id=".$row->num_notice;
				$aff_row.="<td style='text-align:center;'><a href=\"".$url_view."\"><img border=\"0\" align=\"middle\" title=\"".$msg['acquisition_sug_view_not']."\" alt=\"".$msg['acquisition_sug_view_not']."\" src=\"./images/notice.gif\" /></a></td>";
			}
	        
			$sug_src = new suggestion_source($row->sugg_source);
			if ($acquisition_sugg_categ == '1') {
				$categ = new suggestions_categ($row->num_categ);
				$aff_row.="<td ".$dn_javascript." >".htmlentities($categ->libelle_categ, ENT_QUOTES, $charset)."<br />".htmlentities($sug_src->libelle_source, ENT_QUOTES, $charset)."</td>";
				$colspan='10';
			} else {
				$aff_row.="<td ".$dn_javascript." >".htmlentities($sug_src->libelle_source, ENT_QUOTES, $charset)."</td>";
				$colspan='9';
			}			
			
			$sug = new suggestions($row->id_suggestion);
			$img_pj = "<a href=\"$base_path/explnum_doc.php?explnumdoc_id=".$sug->get_explnum('id')."\" target=\"_LINK_\"><img src='$base_path/images/globe_orange.png' /></a>";
			$img_import = "<a href=\"$base_path/acquisition.php?categ=sug&sub=import&explnumdoc_id=".$sug->get_explnum('id')." \"><img src='$base_path/images/upload.gif' /></a>";
			$aff_row .="<td align='center'><i>".($sug->get_explnum('id') ? "$img_pj&nbsp;$img_import" : '' )."</i></td>";			
			$aff_row.= "<td ><input type='checkbox' id='chk[".$row->id_suggestion."]' name='chk[]' value='".$row->id_suggestion."' /></td>
					</tr>";		
			
			$aff_row.="		<tr class='".$pair_impair."' ".$tr_javascript_l2." style='cursor: pointer' >
						<td colspan='".$colspan."' ".$dn_javascript." ><i>".htmlentities($row->commentaires, ENT_QUOTES, $charset)."</i></td>
					</tr>";
		}
		$sug_list_form = str_replace('<!-- sug_list -->',$aff_row, $sug_list_form); 
	
	
		//Affichage des boutons
		
		//Bouton Imprimer
		$imp = "openPopUp('./pdf.php?pdfdoc=listsug&user_input=".urlencode(stripslashes($user_input))."&statut=".$statut."&num_categ=".$num_categ."&sugg_location_id=".$sugg_location_id."' ,'print_PDF', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes');" ;
		$bt_imp = str_replace('!!imp!!', $imp, $bt_imp);
		$sug_list_form=str_replace('<!-- bt_imp -->', $bt_imp,$sug_list_form);
		
		//Génération de la liste des conversions possibles
		$catalog=_parser_text_no_function_(file_get_contents($base_path."/admin/convert/imports/catalog.xml"),"CATALOG");
		$list_export="<select name='export_list'>";
		for ($i=0; $i<count($catalog["ITEM"]); $i++) {
			$item=$catalog["ITEM"][$i];
			if ($item["EXPORT"]=="yes") {
				$list_export.="<option value='".$i."'>".htmlentities($item["EXPORTNAME"],ENT_QUOTES,$charset)."</option>\n";
			}
		}
		$list_export.="</select>";
		$bt_exporter=str_replace("<!-- list_export -->",$list_export,$bt_exporter);
		//Lien vers la page suivante
		$link_export="document.sug_list_form.action='acquisition.php?categ=sug&sub=export'; document.sug_list_form.submit();";
		$bt_exporter=str_replace("!!exp!!",$link_export,$bt_exporter);
		$sug_list_form=str_replace('<!-- bt_exporter -->', $bt_exporter,$sug_list_form);
	
		//Bouton Sélectionner
		$sug_list_form=str_replace('<!-- bt_chk -->', $bt_chk,$sug_list_form);
		
		
		//Liste Boutons
		$button_list=$sug_map->getButtonList($statut);
		$sug_list_form = str_replace('<!-- bt_list -->', $button_list,$sug_list_form);
	
	
		//Bouton Reprendre
		$bt_todo=$sug_map->getButtonList_TODO($statut);
		$sug_list_form=str_replace('<!-- bt_todo -->', $bt_todo,$sug_list_form);

		if ($acquisition_sugg_categ == '1' ) { 	
			//Selecteur Affecter a une categorie
			$to_categ=$sug_map->getCategModifier($statut, $num_categ, $nb_per_page);
		} else {
			$to_categ = "";
		}
		$sug_list_form=str_replace('<!-- to_categ -->', $to_categ,$sug_list_form);
		
		
		//Bouton Supprimer
		$button_sup = $sug_map->getButtonList_DELETED($statut);
		$sug_list_form = str_replace('<!-- bt_sup -->', $button_sup, $sug_list_form);
		
		//JavaScript
		$script_list = $sug_map->getScriptList($statut,$num_categ,$nb_per_page);
		$script = str_replace('<!-- script_list -->', $script_list, $script);
		$sug_list_form=str_replace('<!-- script -->', $script,$sug_list_form);
		
		//Barre de navigation
		if (!$last_param) {
			$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, true, true) ;
	    } else {
	    	$nav_bar = "";
	    }
	    $sug_list_form=str_replace('<!-- nav_bar -->', $nav_bar,$sug_list_form);
		
		print $sug_list_form;		
	
	} else {
		// la requête n'a produit aucun résultat
		error_message($msg['acquisition_sug_rech'], str_replace('!!sug_cle!!', stripslashes($user_input), $msg['acquisition_sug_rech_error']), 0, './categ=sug&sub=todo&action=list&id_bibli='.$id_bibli);
	}
	
}


//Affiche le formulaire de modification de suggestion
function show_form_sug($update_action) {
	
	global $dbh, $msg, $charset;
	global $id_bibli, $id_sug;
	global $sug_map;
	global $sug_modif_form;
	global $acquisition_poids_sugg, $lk_url_sug;
	global $acquisition_sugg_categ, $acquisition_sugg_categ_default;
	global $orig_form_mod;
	global $orig_champ_modif;
	global $id_notice;
	global $acquisition_sugg_localises;
	global $deflt_docs_location;
	global $sugg_location_id;
	global $javascript_path;
	
	$form = $sug_modif_form;

	//Récupération des pondérations de suggestions
	$tab_poids = explode(",", $acquisition_poids_sugg);
	$tab_poids[0] = substr($tab_poids[0], 2); //utilisateur
	$tab_poids[1] = substr($tab_poids[1], 2); //abonné
	$tab_poids[2] = substr($tab_poids[2], 2); //visiteur

	if(!$id_sug) {	//Création de suggestion
	
		$titre = htmlentities($msg['acquisition_sug_cre'], ENT_QUOTES, $charset);
		
		//Récupération de l'utilisateur
	 	$requete_user = "SELECT userid, nom, prenom FROM users where username='".SESSlogin."' limit 1 ";
		$res_user = mysql_query($requete_user, $dbh);
		$row_user=mysql_fetch_row($res_user);
		$orig = $row_user[0];
		$lib_orig = $row_user[1];
		if ($row_user[2]) $lib_orig.= $row_user[2].", ".$row_user[1];

		$form = str_replace('!!lib_orig!!', $orig_form_mod, $form);
				
		$form = str_replace('!!dat_cre!!', formatdate(today()), $form);
		$form = str_replace('!!orig!!', $orig, $form);
		$form = str_replace('!!lib_orig!!', htmlentities($lib_orig, ENT_QUOTES, $charset), $form);
		$form = str_replace('!!typ!!', '0', $form);
		$form = str_replace('!!poi!!', $tab_poids[0], $form);
		$form = str_replace('!!poi_tot!!', $tab_poids[0], $form);
		$statut = $sug_map->getFirstStateId();
		$form = str_replace('!!statut!!', $statut, $form);
		$form = str_replace('!!lib_statut!!', $sug_map->getHtmlComment($statut), $form);
		$form = str_replace('!!list_user!!', '', $form);
		$form = str_replace('!!creator_ajout!!', '', $form);
		$form = str_replace('!!lien!!', '', $form);

		if ($acquisition_sugg_categ != '1') {
			$sel_categ="";
		} else {
			
			if (suggestions_categ::exists($acquisition_sugg_categ_default)) {
				$sugg_categ = new suggestions_categ($acquisition_sugg_categ_default);
			} else {
				$sugg_categ = new suggestions_categ('1');
			}
			$tab_categ = suggestions_categ::getCategList();
			$sel_categ = "<select class='saisie-25em' id='num_categ' name='num_categ'>";
			foreach($tab_categ as $id_categ=>$lib_categ){
				$sel_categ.= "<option value='".$id_categ."' ";
				if ($id_categ==$sugg_categ->id_categ) $sel_categ.= "selected='selected' ";
				$sel_categ.= ">";
				$sel_categ.= htmlentities($lib_categ,ENT_QUOTES, $charset)."</option>";
			}
			$sel_categ.= "</select>"; 
		}
		
		$form = str_replace('!!nombre_expl!!', '1', $form);
		
		$list_locs='';
		if ($acquisition_sugg_localises) {		
		 	$sugg_location_id=((string)$sugg_location_id==""?$deflt_docs_location:$sugg_location_id);
			if ($sugg_location_id) $temp_location=$sugg_location_id;
			else $temp_location=0;
			$locs=new docs_location();
			$list_locs=$locs->gen_combo_box_sugg($temp_location,1,"");
		}
		$form = str_replace('<!-- sel_location -->', $list_locs, $form);
		
		// si suggestion concernant une notice avec 	$id_notice en parametre, on pre-rempli les champs
		if($id_notice) {
			$notice=new notice($id_notice);
			$tit=htmlentities($notice->tit1,ENT_QUOTES, $charset);
			$edi=htmlentities($notice->ed1,ENT_QUOTES, $charset);
			$prix=$notice->prix;
			$cod=$notice->code;
			$url_sug=$notice->lien;
			$as = array_search ("0", $notice->responsabilites["responsabilites"]) ;
			if ($as!== FALSE && $as!== NULL) {
				$auteur_0 = $notice->responsabilites["auteurs"][$as] ;
				$auteur = new auteur($auteur_0["id"]);
			}
			$aut=htmlentities($auteur->display,ENT_QUOTES, $charset);
			$form = str_replace('!!id_notice!!', $id_notice, $form);
		} else {
			$form = str_replace('!!id_notice!!', 0, $form);
		}
		$form = str_replace('!!categ!!', $sel_categ, $form);
		$form = str_replace('!!tit!!', $tit, $form);
		$form = str_replace('!!edi!!', $edi, $form);
		$form = str_replace('!!aut!!', $aut, $form);
		$form = str_replace('!!cod!!', $cod, $form);
		$form = str_replace('!!pri!!', $prix, $form);
		$form = str_replace('!!com!!', '', $form);	
		$form = str_replace('!!url_sug!!', $url_sug, $form);
		
		
		//Affichage du selecteur de source
		$req = "select * from suggestions_source order by libelle_source";
		$res= mysql_query($req,$dbh);
		
		$option = "<option value='0' selected>".htmlentities($msg['acquisition_sugg_no_src'],ENT_QUOTES,$charset)."</option>";
		while(($src=mysql_fetch_object($res))){
			$option .= "<option value='".$src->id_source."' $selected >".htmlentities($src->libelle_source,ENT_QUOTES,$charset)."</option>";
			$selected="";
		}
		$selecteur = "<select id='sug_src' name='sug_src'>".$option."</select>";
		$form = str_replace('!!liste_source!!',$selecteur, $form); 
		$form = str_replace('!!date_publi!!','', $form);
		
		$pj = "<div class='row'>
					<input type='file' id='piece_jointe_sug' name='piece_jointe_sug' class='saisie-80em' size='60' />
			  </div>";
		$form= str_replace('!!div_pj!!',$pj, $form);
		
	} else {	//Modification de suggestion

		$titre = htmlentities($msg['acquisition_sug_mod'], ENT_QUOTES, $charset);

		$sug = new suggestions($id_sug);
		$q = suggestions_origine::listOccurences($id_sug);
		$list_orig = mysql_query($q, $dbh);
		
		$orig = 0;
		$poids_tot = 0;
		$users = array();
		while(($row_orig = mysql_fetch_object($list_orig))) {
			if (!$orig) {
				$orig = $row_orig->origine;
				$typ = $row_orig->type_origine;
				$poids = $tab_poids[$row_orig->type_origine]; 
			}
			array_push($users,$row_orig);
			$poids_tot = $poids_tot + $tab_poids[$row_orig->type_origine];
		}
		
		//On parcourt tous les créateurs de suggestions
		for($i=0;$i<sizeof($users);$i++){
   			
			$orig = $users[$i]->origine;
			$typ = $users[$i]->type_origine;
			
			//Récupération du nom du créateur de la suggestion
			switch($typ){
				default:
				case '0' :
				 	$requete_user = "SELECT userid, nom, prenom FROM users where userid = '".$orig."'";
					$res_user = mysql_query($requete_user, $dbh);
					$row_user=mysql_fetch_row($res_user);
					$lib_orig = $row_user[1];
					if ($row_user[2]) $lib_orig.= ", ".$row_user[2];					
					if(empty($premier_user) || !isset($premier_user)) $premier_user = $lib_orig;
					else $list_user .= $lib_orig."<br />";
					break;
				case '1' :
				 	$requete_empr = "SELECT id_empr, empr_nom, empr_prenom FROM empr where id_empr = '".$orig."'";
					$res_empr = mysql_query($requete_empr, $dbh);
					$row_empr=mysql_fetch_row($res_empr);
					$lib_orig = $row_empr[1];
					if ($row_empr[2]) $lib_orig.= ", ".$row_empr[2];
					if(empty($premier_user) || !isset($premier_user)) $premier_user = $lib_orig;
					else $list_user .= $lib_orig."<br />";
					break;
				case '2' :
					if($orig) $lib_orig = $orig;
					else $lib_orig = $msg['suggest_anonyme'];
					if(empty($premier_user) || !isset($premier_user)) $premier_user = $lib_orig;
					else $list_user .= $lib_orig."<br />";
					break;
			}	
		}
		
		//Récupération du statut de la suggestion
		$lib_statut=$sug_map->getHtmlComment($sug->statut);
	
		$form = str_replace('!!dat_cre!!', formatdate($sug->date_creation), $form);
		$form = str_replace('!!orig!!', $orig, $form);
		
		//Ajout du champ de saisie du nouveau créateur
		$ajout_create = "<input id='creator_orig_id' type='hidden' name='creator_orig_id'>
		<input type='text' id='creator_lib_orig' name='creator_lib_orig' class='saisie-10emr'/>
		<input type='button' class='bouton_small' value='...' onclick=\"openPopUp('./select.php?what=origine&caller=sug_modif_form&param1=creator_orig_id&param2=creator_lib_orig&param3=typ&param4=&param5=&param6=&deb_rech=', 'select_creator_orig', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />";
				
		if(sizeof($users)>1) {
			//on ajoute le champ à la liste
			$list_user.=$ajout_create;
			$form = str_replace('!!creator_ajout!!', '', $form);
		} else $form = str_replace('!!creator_ajout!!', "<br />".$ajout_create, $form);
		
		//Menu dépliant
		$deroul_user=gen_plus('ori',$msg['suggest_creator']. " (".(sizeof($users)-1).")",$list_user,0);
		
		if ($lib_orig) {
			$form = str_replace('!!lib_orig!!', htmlentities($premier_user, ENT_QUOTES, $charset), $form);
			if(sizeof($users)>1) $form = str_replace('!!list_user!!', $deroul_user, $form);
			else $form = str_replace('!!list_user!!', '', $form);
		} else {
			$form = str_replace('!!lib_orig!!', '&nbsp;', $form);
			$form = str_replace('!!list_user!!', '', $form);
		}
		$form = str_replace('!!typ!!', $typ, $form);
		$form = str_replace('!!poi!!', $poids, $form);
		$form = str_replace('!!poi_tot!!', $poids_tot, $form);
		$form = str_replace('!!statut!!', $sug->statut, $form);
		$form = str_replace('!!lib_statut!!', $lib_statut, $form);
		
		if ($acquisition_sugg_categ != '1') {
			$sel_categ="";
		} else {
			
			$state_name = $sug_map->getStateNameFromId($sug->statut);
			$categ = $sug_map->getState_CATEG($state_name);
			$sugg_categ = new suggestions_categ($sug->num_categ);

			if ($categ == 'YES') {
				$tab_categ = suggestions_categ::getCategList();
				$sel_categ = "<select class='saisie-25em' id='num_categ' name='num_categ'>";
				foreach($tab_categ as $id_categ=>$lib_categ){
					$sel_categ.= "<option value='".$id_categ."' ";
					if ($id_categ==$sug->num_categ) $sel_categ.= "selected='selected' ";
					$sel_categ.= ">";
					$sel_categ.= htmlentities($lib_categ,ENT_QUOTES, $charset)."</option>";
				}
				$sel_categ.= "</select>"; 
			} else {
				$sel_categ = htmlentities($sugg_categ->libelle_categ, ENT_QUOTES,$charset);
			}			
		}
		//Nombre d'exemplaire
		$form = str_replace('!!nombre_expl!!', $sug->nb, $form);
		
		//Selecteur de localisation
		$list_locs='';
		if ($acquisition_sugg_localises) {
			$sugg_location_id=$sug->sugg_location;
			if ($sugg_location_id) $temp_location=$sugg_location_id;
			else $temp_location=0;
			$locs=new docs_location();
			$list_locs=$locs->gen_combo_box_sugg($temp_location,1,"");
		}
		$form = str_replace('<!-- sel_location -->', $list_locs, $form);
		
		if($sug->num_notice && $sug->num_notice !=0){
			$req_ana = "select analysis_bulletin as bull , analysis_notice as noti from analysis where analysis_notice ='".$sug->num_notice."'";	
			$res_ana = mysql_query($req_ana,$dbh);
			$num_rows_ana = mysql_num_rows($res_ana);			
			if($num_rows_ana){
				$ana = mysql_fetch_object($res_ana);
				$url_view = "catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$ana->bull&art_to_show=".$ana->noti;
			} else $url_view = "./catalog.php?categ=isbd&id=".$sug->num_notice;
			$lien = "<a href='$url_view'> ".$msg['acquisition_sug_view_not']."</a>";
			$form = str_replace('!!lien!!',$lien, $form);
		} else $form = str_replace('!!lien!!','', $form);
		
		$form = str_replace('!!categ!!', $sel_categ, $form);
		$form = str_replace('!!tit!!', htmlentities($sug->titre, ENT_QUOTES, $charset), $form);
		$form = str_replace('!!edi!!', htmlentities($sug->editeur, ENT_QUOTES, $charset), $form);
		$form = str_replace('!!aut!!', htmlentities($sug->auteur, ENT_QUOTES, $charset), $form);
		$form = str_replace('!!cod!!', htmlentities($sug->code, ENT_QUOTES, $charset), $form);
		$form = str_replace('!!pri!!', round($sug->prix, 2), $form);
		$form = str_replace('!!com!!', htmlentities($sug->commentaires, ENT_QUOTES, $charset), $form);
		
		$req = "select * from suggestions_source order by libelle_source";
		$res= mysql_query($req,$dbh);
		$selected = "";
		$option = "<option value='0' selected>".htmlentities($msg['acquisition_sugg_no_src'],ENT_QUOTES,$charset)."</option>";
		while(($src=mysql_fetch_object($res))){
			 ($src->id_source == $sug->sugg_src ? $selected = " selected ": $selected ="");
			$option .= "<option value='".$src->id_source."' $selected>".htmlentities($src->libelle_source,ENT_QUOTES,$charset)."</option>";
		}
		$selecteur = "<select id='sug_src' name='sug_src'>".$option."</select>";
		$form = str_replace('!!liste_source!!',$selecteur, $form); 
		$form=str_replace("!!date_publi!!",htmlentities($sug->date_publi, ENT_QUOTES, $charset),$form);		
		
		if(!$sug->get_explnum('id')){
			$pj = "<div class='row'>
					<input type='file' id='piece_jointe_sug' name='piece_jointe_sug' class='saisie-80em' size='60' />
			  </div>";
		} else {
			$pj = "
			<input type='hidden' name='id_pj' id='id_pj' value='".$sug->get_explnum('id')."' />
			<div class='row'>".
				$sug->get_explnum('nom')."&nbsp;<input type='submit' class='bouton' name='del_pj' id='del_pj' value='X' onclick='this.form.action=\"./acquisition.php?categ=sug&action=del_pj&id_bibli=".$id_bibli."&id_sug=".$id_sug."\"' /> 
			</div>";
		}
		$form= str_replace('!!div_pj!!',$pj, $form);
		
		if ($sug->url_suggestion ) {
			$form = str_replace('<!-- url_sug -->', $lk_url_sug, $form);
		}
		$form = str_replace('!!url_sug!!', htmlentities($sug->url_suggestion, ENT_QUOTES, $charset), $form);	
		$form = str_replace('!!id_notice!!', $sug->num_notice, $form);
		

		// Affichage du bouton supprimer
		$bt_sup = $sug_map->getButton_DELETED($sug->statut, $id_bibli, $id_sug);
		$form = str_replace('<!-- bouton_sup -->', $bt_sup, $form);
		
		if ($sug->num_notice) {
			//Eventuellement, lien vers la notice	

		} else {
			
			// Affichage du bouton cataloguer
			$bt_cat = $sug_map->getButton_CATALOG($sug->statut, $id_bibli, $id_sug);
			$button = "<input type='radio' name='catal_type' id='not_type' value='0' checked /><label class='etiquette' for='not_type'>".htmlentities($msg['acquisition_type_mono'],ENT_QUOTES,$charset)."</label>
			<input type='radio' name='catal_type' value='1' id='art_type'/><label for='art_type' class='etiquette'>".htmlentities($msg['acquisition_type_art'],ENT_QUOTES,$charset)."</label>";
			if($sug->sugg_noti_unimarc){
				$bt_cat = str_replace('!!type_catal!!',"&nbsp;<label style='color:red'>Notice externe existante</label>",$bt_cat);
			} else $bt_cat = str_replace('!!type_catal!!',$button,$bt_cat);
			
			$form = str_replace('<!-- bouton_cat -->', $bt_cat, $form);
		}
	}
	
	//$action ="./acquisition.php?categ=sug&action=update&id_bibli=".$id_bibli."&id_sug=".$id_sug;
	$form = str_replace('!!action!!', $update_action, $form);
	$form = str_replace('!!form_title!!', $titre, $form);
	
	print "<script type=\"text/javascript\" src=\"".$javascript_path."/tablist.js\"></script>";
	print $form;
}

?>

