<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search.inc.php,v 1.39 2010-02-23 10:19:20 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$url_base = "./autorites.php?categ=categories&sub=&id=0&parent=";

// inclusions diverses
include("$include_path/templates/category.tpl.php");
require_once("$class_path/category.class.php");
require_once("$class_path/analyse_query.class.php");
require_once("$class_path/thesaurus.class.php");

// search.inc : recherche des catégories en gestion d'autorités


//Récuperation de la liste des langues définies pour l'interface
$langages = new XMLlist("$include_path/messages/languages.xml", 1);
$langages->analyser();
$lg = $langages->table;


//affichage du selectionneur de thesaurus et du lien vers les thésaurus
$liste_thesaurus = thesaurus::getThesaurusList();
$sel_thesaurus = '';
$lien_thesaurus = '';

if ($thesaurus_mode_pmb != 0) {	 //la liste des thesaurus n'est pas affichée en mode monothesaurus
	$sel_thesaurus = "<select class='saisie-30em' id='id_thes' name='id_thes' ";
	$sel_thesaurus.= "onchange = \"document.location = '".$url_base."&id_thes='+document.getElementById('id_thes').value; \">" ;
	foreach($liste_thesaurus as $id_thesaurus=>$libelle_thesaurus) {
		$sel_thesaurus.= "<option value='".$id_thesaurus."' "; ;
		if ($id_thesaurus == $id_thes) $sel_thesaurus.= " selected";
		$sel_thesaurus.= ">".htmlentities($libelle_thesaurus,ENT_QUOTES, $charset)."</option>";
	}
	$sel_thesaurus.= "<option value=-1 ";
	if ($id_thes == -1) $sel_thesaurus.= "selected ";
	$sel_thesaurus.= ">".htmlentities($msg['thes_all'],ENT_QUOTES, $charset)."</option>";
	$sel_thesaurus.= "</select>&nbsp;";

	$lien_thesaurus = "<a href='./autorites.php?categ=categories&sub=thes'>".$msg[thes_lien]."</a>";

}	
$user_query=str_replace("<!-- sel_thesaurus -->",$sel_thesaurus,$user_query);
$user_query=str_replace("<!-- lien_thesaurus -->",$lien_thesaurus,$user_query);


//affichage du choix de langue pour la recherche
$sel_langue = '';
$sel_langue = "<div class='row'>";
$sel_langue.= "<input type='checkbox' name='lg_search' id='lg_search' value='1' />&nbsp;".htmlentities($msg['thes_sel_langue'],ENT_QUOTES, $charset);
$sel_langue.= "</div><br />";
$user_query=str_replace("<!-- sel_langue -->",$sel_langue,$user_query);
$user_query=str_replace("!!user_input!!",htmlentities(stripslashes($user_input),ENT_QUOTES, $charset),$user_query);


//recuperation du thesaurus session 
if(!$id_thes) {
	$id_thes = thesaurus::getSessionThesaurusId();
} else {
	thesaurus::setSessionThesaurusId($id_thes);
}

if ($id_thes != -1) {
	$thes = new thesaurus($id_thes);
}
	

// nombre de références par pages
if ($nb_per_page_author != "") 
	$nb_per_page = $nb_per_page_author ;
	else $nb_per_page = 10;

// traitement de la saisie utilisateur
include("$include_path/marc_tables/$lang/empty_words");
if($user_input) {
	//a priori pas utile. Armelle
	$clef = reg_diacrit($user_input);
}


// $authors_list_tmpl : template pour la liste auteurs
$categ_list_tmpl = "
<br />
<br />
<div class='row'>
	<h3><! --!!nb_autorite_found!!-- >$msg[1320] !!cle!! </h3>
	</div>
	<table>
		!!list!!
	</table>
<div class='row'>
	!!nav_bar!!
	</div>
";


function list_categ($cle, $categ_list, $nav_bar) {
	global $categ_list_tmpl;
	$categ_list_tmpl = str_replace("!!cle!!", $cle, $categ_list_tmpl);
	$categ_list_tmpl = str_replace("!!list!!", $categ_list, $categ_list_tmpl);
	$categ_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $categ_list_tmpl);
	categ_browser::search_form();
	print pmb_bidi($categ_list_tmpl);
}


// on récupére le nombre de lignes qui vont bien
if(!$nbr_lignes) {
	if(!$user_input) {
		$requete = "SELECT count(1) FROM noeuds ";
		if ($id_thes) $requete.= "where num_thesaurus = '".$id_thes."' ";

	} else {
		$aq=new analyse_query($user_input);
		if ($aq->error) {
			categ_browser::search_form($parent);
			error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
			exit;
		}

		if ($lg_search != 1 && $id_thes != -1) {
			
			//1 seul thesaurus
			//on recherche dans la langue de l'interface ou dans la langue par défaut du thesaurus

			$members_catdef = $aq->get_query_members("catdef", "catdef.libelle_categorie", "catdef.index_categorie", "catdef.num_noeud");
			$members_catlg = $aq->get_query_members("catlg", "catlg.libelle_categorie", "catlg.index_categorie", "catlg.num_noeud");

			$requete = "select count(1) ";
			$requete.= "from noeuds left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = '".$thes->langue_defaut."' ";
			$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
			$requete.= "where ";
			$requete.= "noeuds.num_thesaurus = '".$id_thes."' ";
			$requete.= "and ( if(catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catlg["where"].") ) ";

		} elseif ($lg_search == 1) {
			
			//1 seul thesaurus ou tous les thesaurus
			//On recherche dans toutes les langues			
			
			$members = $aq->get_query_members("categories", "libelle_categorie", "index_categorie", "num_noeud");
			
			$requete = "select count(1) from noeuds, categories ";
			$requete.= "where (".$members["where"].") ";	
			if($id_thes != -1) $requete.= "and noeuds.num_thesaurus = '".$id_thes."' ";
			$requete.= "and noeuds.id_noeud = categories.num_noeud ";
	
		} elseif ($lg_search != 1 && $id_thes == -1) {
			
			//tous les thesaurus
			//on recherche dans la langue de l'interface ou dans la langue par défaut du thesaurus

			$members_catdef = $aq->get_query_members("catdef", "catdef.libelle_categorie", "catdef.index_categorie", "catdef.num_noeud");
			$members_catlg = $aq->get_query_members("catlg", "catlg.libelle_categorie", "catlg.index_categorie", "catlg.num_noeud");

			$requete = "select count(1) ";
			$requete.= "from thesaurus left join noeuds on thesaurus.id_thesaurus = noeuds.num_thesaurus ";
			$requete.= "left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = thesaurus.langue_defaut ";
			$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
			$requete.= "where ( if(catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catlg["where"].") ) "; 	
		
		}

	}
	
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);

} else {
	$aq=new analyse_query($user_input);
}

	
if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;

if($nbr_lignes) {
	$categ_list_tmpl=str_replace( "<! --!!nb_autorite_found!!-- >",$nbr_lignes.' ',$categ_list_tmpl);	
	// on lance la vraie requête
	if(!$user_input) {

		$requete = "select catdef.num_noeud as categ_id, ";
		$requete.= "if (catlg.num_noeud is null, catdef.langue, catlg.langue) as langue, ";
		$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle, ";
		$requete.= "noeuds.num_parent as categ_parent, ";
		$requete.= "noeuds.num_renvoi_voir as categ_see, ";
		$requete.= "noeuds.num_thesaurus, ";
		$requete.= "if (catlg.num_noeud is null, catdef.note_application, catlg.note_application) as categ_comment, ";
		$requete.= "if (catlg.num_noeud is null, catdef.index_categorie, catlg.index_categorie) as index_categorie ";
		$requete.= "from thesaurus left join noeuds on thesaurus.id_thesaurus = noeuds.num_thesaurus ";
		$requete.= "left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = thesaurus.langue_defaut ";
		$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
		if($id_thes != -1) $requete.= "where id_thesaurus = '".$id_thes."' ";	
		$requete.= "limit ".$debut.",".$nb_per_page." ";		
	
	} else {
		
		if ($lg_search != 1 && $id_thes != -1) {
			
			//1 seul thesaurus
			//on recherche dans la langue de l'interface ou dans la langue par défaut du thesaurus			

			$members_catdef = $aq->get_query_members("catdef", "catdef.libelle_categorie", "catdef.index_categorie", "catdef.num_noeud");
			$members_catlg = $aq->get_query_members("catlg", "catlg.libelle_categorie", "catlg.index_categorie", "catlg.num_noeud");

			$requete = "select catdef.num_noeud as categ_id, ";
			$requete.= "if (catlg.num_noeud is null, catdef.langue, catlg.langue) as langue, ";
			$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle, ";
			$requete.= "noeuds.num_parent as categ_parent, ";
			$requete.= "noeuds.num_renvoi_voir as categ_see, ";
			$requete.= "noeuds.num_thesaurus, ";
			$requete.= "if (catlg.num_noeud is null, catdef.note_application, catlg.note_application) as categ_comment, ";
			$requete.= "if (catlg.num_noeud is null, catdef.index_categorie, catlg.index_categorie) as index_categorie, ";
			$requete.= "if (catlg.num_noeud is null, (".$members_catdef["select"]."), (".$members_catlg["select"].") ) as pert ";
			$requete.= "from noeuds left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = '".$thes->langue_defaut."' ";
			$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
			$requete.= "where noeuds.num_thesaurus = '".$id_thes."' ";
			$requete.= "and ( if (catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catdef["where"].") ) ";	
			$requete.= "order by pert desc,index_categorie ";
			$requete.= "limit ".$debut.",".$nb_per_page." ";
			
		} elseif ($lg_search == 1) {
			
			//1 seul thesaurus ou tous les thesaurus
			//On recherche dans toutes les langues			

			$members = $aq->get_query_members("categories", "libelle_categorie", "index_categorie", "num_noeud");
			
			$requete = "select noeuds.id_noeud as categ_id, ";
			$requete.= "noeuds.num_parent as categ_parent, ";
			$requete.= "noeuds.num_renvoi_voir as categ_see, ";
			$requete.= "noeuds.num_thesaurus, ";
			$requete.= "categories.langue as langue, ";
			$requete.= "categories.libelle_categorie as categ_libelle, ";
			$requete.= "categories.note_application as categ_comment, ";
			$requete.= "categories.index_categorie as index_categorie, ";
			$requete.= $members["select"]." as pert "; 
			$requete.= "from noeuds, categories ";
			$requete.= "where (".$members["where"].") ";	
			if($id_thes != -1) $requete.= "and noeuds.num_thesaurus = '".$id_thes."' ";
			$requete.= "and noeuds.id_noeud = categories.num_noeud ";
			$requete.= "order by pert desc, num_thesaurus, index_categorie ";
			$requete.= "limit ".$debut.",".$nb_per_page." ";
			
		} elseif ($lg_search != 1 && $id_thes == -1) {
			
			//tous les thesaurus
			//on recherche dans la langue de l'interface ou dans la langue par défaut du thesaurus
			

			$members_catdef = $aq->get_query_members("catdef", "catdef.libelle_categorie", "catdef.index_categorie", "catdef.num_noeud");
			$members_catlg = $aq->get_query_members("catlg", "catlg.libelle_categorie", "catlg.index_categorie", "catlg.num_noeud");

			$requete = "select catdef.num_noeud as categ_id, ";
			$requete.= "noeuds.num_parent as categ_parent, ";
			$requete.= "noeuds.num_renvoi_voir as categ_see, ";
			$requete.= "noeuds.num_thesaurus, ";
			$requete.= "if (catlg.num_noeud is null, catdef.langue, catlg.langue) as langue, ";
			$requete.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as categ_libelle, ";
			$requete.= "if (catlg.num_noeud is null, catdef.index_categorie, catlg.index_categorie) as index_categorie, ";
			$requete.= "if (catlg.num_noeud is null, catdef.note_application, catlg.note_application) as categ_comment, ";
			$requete.= "if (catlg.num_noeud is null, (".$members_catdef["select"]."), (".$members_catlg["select"].") ) as pert ";
			$requete.= "from thesaurus left join noeuds on thesaurus.id_thesaurus = noeuds.num_thesaurus ";
			$requete.= "left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = thesaurus.langue_defaut ";
			$requete.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
			$requete.= "where ( if(catlg.num_noeud is null, ".$members_catdef["where"].", ".$members_catlg["where"].") ) "; 	
			$requete.= "order by pert desc,num_thesaurus, index_categorie ";
			$requete.= "limit ".$debut.",".$nb_per_page." ";
		}
	}
	
	
	$res = @mysql_query($requete, $dbh);

	$parity=1;
	while(($categ=mysql_fetch_object($res))) {
		
		$temp = new categories($categ->categ_id, $categ->langue);
		if ($id_thes == -1) {
			$thes = new thesaurus($categ->num_thesaurus);
			$display = '['.htmlentities($thes->libelle_thesaurus,ENT_QUOTES, $charset).']';
		} else {
			$display = '';
		}
		if ($lg_search) $display.= '['.$lg[$categ->langue].'] '; else $display.= '';				
		if($categ->categ_see) {
			$temp = new categories($categ->categ_see, $categ->langue);
			$display.= $categ->categ_libelle." -&gt; <i>";
			if ($thesaurus_categories_show_only_last) {
				$display.= $temp->libelle_categorie;
			} else {
				$display.= categories::listAncestorNames($categ->categ_see, $categ->langue);
			} 
			$display.= "@</i>";
		} else {
			if ($thesaurus_categories_show_only_last) {
				$display.= $categ->categ_libelle;
			} else {
				$display.= categories::listAncestorNames($categ->categ_id, $categ->langue);
			} 			
		}	

		$acateg = new category($categ->categ_id);
		$notice_count = $acateg->notice_count(false);
		
		$categ_entry = $display ;
		$categ_comment = $categ->categ_comment;
		$link_categ = "./autorites.php?categ=categories&sub=categ_form&parent=0&id=".$categ->categ_id."&id_thes=".$categ->num_thesaurus;
		
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		
		$parity += 1;
	        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"  ";
                $categ_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
                				<td valign='top' onmousedown=\"document.location='$link_categ';\">
								$categ_entry
								</td>
								<td valign='top' onmousedown=\"document.location='$link_categ';\">
								$categ_comment
								</td>";
				if($notice_count && $notice_count!=0)	
					$categ_list .= "<td onmousedown=\"document.location='./catalog.php?categ=search&mode=1&etat=aut_search&aut_type=categ&aut_id=$categ->categ_id'\">".$notice_count."</a></td>";
				else $categ_list .= "<td>&nbsp;</td>";
				$categ_list .= "</tr>";
			
	} // fin while
	

	mysql_free_result($res);


	//Création barre de navigation
	$url_base=$PHP_SELF.'?categ=categories&sub=search&id_thes='.$id_thes.'&user_input='.rawurlencode(stripslashes($user_input)).'&lg_search='.$lg_search;
	if (!$last_param) $nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
        else $nav_bar = "";

	
	// affichage du résultat
	list_categ(stripslashes($user_input), $categ_list, $nav_bar);

} else {
	// la requête n'a produit aucun résultat
	categ_browser::search_form($parent);
	error_message($msg[211], str_replace('!!categ_cle!!', stripslashes($user_input), $msg["categ_no_categ_found_with"]), 0, './autorites.php?categ=categories&sub=search');
}

