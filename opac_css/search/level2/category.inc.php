<?php
// +----------------------------------------------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +----------------------------------------------------------------------------------------+
// $Id: category.inc.php,v 1.30 2010-01-07 10:50:03 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// second niveau de recherche OPAC sur catégorie

require_once($class_path."/thesaurus.class.php");

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['categories'] = $count;
}

print pmb_bidi("<div id=\"resultatrech\"><h3>$msg[resultat_recherche]</h3><div id=\"resultatrech_container\"><div id=\"resultatrech_see\">
<h3><span><b>$count</b> $msg[categs_found] <b>'".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."'");

if ($opac_search_other_function) {
	require_once($include_path."/".$opac_search_other_function);
	print pmb_bidi(" ".search_other_function_human_query($_SESSION["last_query"]));
}

print "</b></font>";
print activation_surlignage();
print "</h3></span></div><div id=\"resultatrech_liste\"><ul>";

$first_clause.= "catdef.libelle_categorie not like '~%' ";

$aq=new analyse_query(stripslashes($user_query));
$members_catdef = $aq->get_query_members('catdef','catdef.libelle_categorie','catdef.index_categorie','catdef.num_noeud');
$members_catlg = $aq->get_query_members('catlg','catlg.libelle_categorie','catlg.index_categorie','catlg.num_noeud');

$list_thes = array();
if ($id_thes == -1) { 
//recherche dans tous les thesaurus
	$list_thes = thesaurus::getThesaurusList();
} else {
//recherche dans le thesaurus transmis
	$thes = new thesaurus($id_thes);
	$list_thes[$id_thes]=$thes->libelle_thesaurus;
}

$q = "drop table if exists catjoin ";
$r = mysql_query($q, $dbh);
$q = "create temporary table catjoin  ENGINE=MyISAM as select ";
$q.= "noeuds.num_thesaurus, ";
$q.= "catdef.num_noeud,catdef.note_application, catdef.comment_public,";
$q.= "noeuds.num_renvoi_voir, ";
$q.= "if (catlg.num_noeud is null, catdef.libelle_categorie, catlg.libelle_categorie) as libelle_categorie, ";
$q.= "if (catlg.num_noeud is null, catdef.index_categorie, catlg.index_categorie) as index_categorie, ";
$q.= "if (catlg.num_noeud is null, ".$members_catdef['select'].", ".$members_catlg['select'].") as pert ";
$q.= "from thesaurus left join noeuds on thesaurus.id_thesaurus = noeuds.num_thesaurus "; 
$q.= "left join categories as catdef on noeuds.id_noeud = catdef.num_noeud and catdef.langue = thesaurus.langue_defaut ";
$q.= "left join categories as catlg on catdef.num_noeud = catlg.num_noeud and catlg.langue = '".$lang."' ";
$q.= "where 1 ";
if ($id_thes != -1) $q.= "and thesaurus.id_thesaurus = '".$thes->id_thesaurus."' ";
$q.= "and ".$first_clause." ";
$q.= "and ( if (catlg.num_noeud is null, ".$members_catdef['where'].", ".$members_catlg['where'].") ) ";
$r = mysql_query($q, $dbh);

$q = 'select distinct catjoin.num_noeud, catjoin.* from catjoin '.$clause.' ORDER BY pert desc, catjoin.index_categorie '.$limiter;
$found = mysql_query($q, $dbh);

while($mesCategories_trouvees = mysql_fetch_object($found)) {
	print "<li class='categ_colonne'>";
	if ($mesCategories_trouvees->num_renvoi_voir) {			// Affichage des renvois_voir
		
		if (categories::exists($mesCategories_trouvees->num_renvoi_voir, $lang)) $lg=$lang;
		else {
			$thes = thesaurus::getByEltId($mesCategories_trouvees->num_noeud);
			$lg = $thes->langue_defaut;
		}
		$q =  "select * from noeuds, categories where num_noeud='".$mesCategories_trouvees->num_renvoi_voir."' and langue = '".$lg."' and noeuds.id_noeud = categories.num_noeud limit 1";
		$found_see = mysql_query ($q, $dbh);
		
		$mesCategories = @mysql_fetch_object($found_see) ;
		print pmb_bidi("<b>".$mesCategories_trouvees->libelle_categorie."</b> ".$msg['term_show_see']." ") ;
	} else $mesCategories = $mesCategories_trouvees ;
	
		
	// Affichage de l'arborescence des renvois voir
	if ($mesCategories->num_parent) {
		$bar = categories::listAncestors($mesCategories->num_noeud, $lang);
		$bar = array_reverse($bar);
		if ($bar[3]) print pmb_bidi("<a href=./index.php?lvl=categ_see&id=".$bar[3]['num_noeud']."><img src='./images/folder.gif' border='0' align='middle'>...</a> > ");
		if ($bar[2]) print pmb_bidi("<a href=./index.php?lvl=categ_see&id=".$bar[2]['num_noeud']."><img src='./images/folder.gif' border='0' align='middle'>".$bar[2]['libelle_categorie'].'</a> > ');
		if ($bar[1]) print pmb_bidi("<a href=./index.php?lvl=categ_see&id=".$bar[1]['num_noeud']."><img src='./images/folder.gif' border='0' align='middle'>".$bar[1]['libelle_categorie'].'</a> > ');
	}
	print "<a href=./index.php?lvl=categ_see&id=".$mesCategories->num_noeud.">";
	$c_categ =  new category($mesCategories_trouvees->num_noeud);
	
	// Si il y a présence d'un commentaire affichage du layer					
	$result_com = categorie::zoom_categ($mesCategories_trouvees->num_noeud, $mesCategories_trouvees->note_application);
	
	if($c_categ->has_notices())
		print " <img src='$base_path/images/folder_search.gif' border=0 align='middle' />";			
	else		
		print "<img src='./images/folder.gif' border='0' align='middle'>"; 

	print pmb_bidi("</a><a href=./index.php?lvl=categ_see&id=".$mesCategories->num_noeud.$result_com['java_com']. ">".$mesCategories->libelle_categorie.'</a>'.$result_com['zoom']);

	/*$q = 'select distinct num_noeud_dest from voir_aussi ';
	$q.= "where num_noeud_orig ='".$mesCategories->num_noeud."' ";
	$found_see_too = mysql_query($q, $dbh);
	
	if (mysql_num_rows($found_see_too)) {
		print "<br />".$msg['term_show_see_also']." " ;
		$deb = 0 ;
		while ($mesCategories_see_too = mysql_fetch_object($found_see_too)) {
			if ($deb) print " / " ;
			if (categories::exists($mesCategories_see_too->num_noeud_dest, $lang)) $lg=$lang;
			else {
				$thes1 = thesaurus::getByEltId($mesCategories_see_too->num_noeud_dest);
				$lg = $thes1->langue_defaut;
			}
			$c2_categ =  new category($mesCategories_see_too->num_noeud_dest);
			
			$q =  "select libelle_categorie from categories where num_noeud = '".$mesCategories_see_too->num_noeud_dest."' and langue = '".$lg."' limit 1";
			$cat = mysql_query($q, $dbh);
			$catvoir = mysql_fetch_object($cat);
			print "<a href=./index.php?lvl=categ_see&id=".$mesCategories_see_too->num_noeud_dest.">";

			if($c2_categ->has_notices())
			{
				print "<img src='./images/folder_search.gif' border='0' align='middle'>";
			}
			else
			{
				print "<img src='./images/folder.gif' border='0' align='middle'>";
			}
			
			print pmb_bidi("</a><a href=./index.php?lvl=categ_see&id=".$mesCategories_see_too->num_noeud_dest.">".$catvoir->libelle_categorie.'</a>');
			$deb = 1 ;
		}
	}*/
		print "</li>";
	}

print "</ul></div></div></div>";

