<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collection.inc.php,v 1.23 2010-07-28 07:44:39 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=collection&caller=$caller&mode=$mode&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn";

// contenu popup sélection collection
require('./selectors/templates/sel_collection.tpl.php');

// affichage du header
print $sel_header;

// traitement en entrée des requêtes utilisateur
if ($deb_rech) $f_user_input = $deb_rech ;
$rech_regexp = 0 ;
if($f_user_input=="" && $user_input=="") {
	$user_input='';
} else {
	// traitement de la saisie utilisateur
	if ($user_input) $f_user_input=$user_input;
	if (($f_user_input)&&(!$user_input)) $user_input=$f_user_input;
}

// affichage des membres de la page

if($bt_ajouter == "no"){
	$bouton_ajouter="";
}else{
	$bouton_ajouter= "<input type='button' class='bouton_small' onclick=\"document.location='$base_url&action=add'\" value='$msg[163]' />";
}

switch($action){
	case 'add':
		print $collection_form;
		break;
	case 'update':
		require_once("$class_path/editor.class.php");
		require_once("$class_path/collection.class.php");
		$value['name']		=	$collection_nom;
		$value['parent']	=	$ed_id;
		$value['issn'] = $issn;
		$collection = new collection();
		$collection->update($value);
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $collection_nom, 0, 0, $collection->id);
		break;
	default:
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $user_input, $nbr_lignes, $page);
		break;
	}

function show_results($dbh, $user_input, $nbr_lignes=0, $page=0, $id = 0) {
	global $nb_per_page;
	global $base_url;
	global $caller;
 	global $charset;
	global $msg;
	global $no_display ;
	
	// on récupére le nombre de lignes qui vont bien
	if (!$id) {
		if($user_input=="") {
			$requete = "SELECT COUNT(1) FROM collections where collection_id!='$no_display' ";	
		} else {
			$aq=new analyse_query(stripslashes($user_input));
			if ($aq->error) {
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				exit;
			}
			$requete=$aq->get_query_count("collections","collection_name","index_coll","collection_id", "collection_id!='$no_display'");
		}
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = @mysql_result($res, 0, 0);
	} else $nbr_lignes=1;
	
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on lance la vraie requête
		if (!$id) {
			if($user_input=="") {
				$requete = "SELECT collections.*,publishers.* FROM collections, publishers WHERE collection_id!='$no_display' and ed_id=collection_parent group by collection_id";
				$requete .= " ORDER BY index_coll, index_publisher LIMIT $debut,$nb_per_page ";
			} else {
				$members=$aq->get_query_members("collections","collection_name","index_coll","collection_id");
				$requete="select collections.*,publishers.*, ".$members["select"]." as pert from collections, publishers where ".$members["where"]." and collection_id!='$no_display' and ed_id=collection_parent group by collection_id order by pert desc, index_coll, index_publisher LIMIT $debut,$nb_per_page";
			}
		} else $requete="select collections.*,publishers.* from collections,publishers where collection_id='".$id."' and collection_parent=ed_id group by collection_id";
		$res = @mysql_query($requete, $dbh);
		while(($col=mysql_fetch_object($res))) {
			print pmb_bidi("
 			<a href='#' onclick=\"set_parent('$caller', $col->collection_id, '".htmlentities(addslashes($col->collection_name),ENT_QUOTES, $charset)."', $col->ed_id, '".htmlentities(addslashes($col->ed_name),ENT_QUOTES,$charset)."')\">
				$col->collection_name</a>");
			print pmb_bidi(".&nbsp;$col->ed_name<br />");
		}
		mysql_free_result($res);

		// constitution des liens
		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;	

		// affichage pagination
		print "<div class='row'>&nbsp;<hr /></div><div align='center'>";
		$url_base = $base_url."&user_input=".rawurlencode(stripslashes($user_input));
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		print $nav_bar;
		print "</div>";

	}
}

print $sel_footer;
