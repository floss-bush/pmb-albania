<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serie.inc.php,v 1.22 2010-07-28 07:44:39 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=serie&caller=$caller&param1=$param1&param2=$param2&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn";

// contenu popup sélection auteur
require('./selectors/templates/sel_serie.tpl.php');

// affichage du header
print $sel_header;

// traitement en entrée des requêtes utilisateur
if ($deb_rech) $f_user_input = $deb_rech ;
if($f_user_input=="" && $user_input=="") {
	$user_input='';
} else {
	// traitement de la saisie utilisateur
	if ($user_input) $f_user_input=$user_input;
	if (($f_user_input)&&(!$user_input)) $user_input=$f_user_input;	
}

if($bt_ajouter == "no"){
	$bouton_ajouter="";
}else{
	$bouton_ajouter= "<input type='button' class='bouton_small' onclick=\"document.location='$base_url&action=add&no_display=$no_display'\" value='$msg[339]' />";
}
		
switch($action){
	case 'add':
		print $serie_form;
		break;
	case 'update':
		$value=	$serie_nom;
		require_once("$class_path/serie.class.php");
		$serie = new serie(0);
		$serie->update($value);
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $serie_nom, 0, 0, $serie->id);
		break;
	default:
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $user_input, $nbr_lignes, $page);
		break;
	}

// affichage des membres de la page
function show_results($dbh, $user_input, $nbr_lignes=0, $page=0, $id = 0) {
	global $nb_per_page;
	global $base_url;
	global $caller;
	global $no_display;
 	global $charset;
	global $msg;
	
	// on récupére le nombre de lignes qui vont bien

	if (!$id) {
		if($user_input=="") {
			$requete = "SELECT COUNT(1) FROM series where serie_id!='$no_display' ";
		} else {
			$aq=new analyse_query(stripslashes($user_input));
			if ($aq->error) {
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				exit;
			}
			$requete=$aq->get_query_count("series","serie_name","serie_index","serie_id","serie_id!='$no_display'");
		}
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
	} else $nbr_lignes=1;
	
	if (!$page) $page=1;
	$debut = ($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on lance la vraie requête
		if (!$id) {
			if($user_input=="") {
				$requete = "SELECT * FROM series where serie_id!='$no_display' ";
				$requete .= "ORDER BY serie_index LIMIT $debut,$nb_per_page ";
			} else {
				$members=$aq->get_query_members("series","serie_name","serie_index","serie_id");
				$requete="select *,".$members["select"]." as pert from series where ".$members["where"]." and serie_id!='$no_display' group by serie_id order by pert desc,serie_index limit $debut,$nb_per_page";
			}
		} else $requete="select * from series where serie_id='".$id."'";				
		$res = @mysql_query($requete, $dbh);
		while(($serie=mysql_fetch_object($res))) {
			$entry = $serie->serie_name;
			print pmb_bidi("
			<a href='#' onclick=\"set_parent('$caller', '$serie->serie_id', '".htmlentities(addslashes($entry),ENT_QUOTES,$charset)."')\">
				$entry</a>");
			print "<br />";

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