<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: titre_uniforme.inc.php,v 1.4 2010-12-15 13:37:03 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], "inc.php")) die("no access");

// gestion d'un élément à ne pas afficher
if (!$no_display) $no_display=0;

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=titre_uniforme&caller=$caller&param1=$param1&param2=$param2&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn&callback=$callback&infield=$infield";

// contenu popup sélection auteur
require("./selectors/templates/sel_titre_uniforme.tpl.php");

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
	$bouton_ajouter= "<input type='button' class='bouton_small' onclick=\"document.location='$base_url&action=add'\" value='".$msg["aut_titre_uniforme_ajouter"]."'>";
}

switch($action){
	case 'add':
		print $titre_uniforme_form;
		break;
	case 'update':	
		$value['name'] = $name;

		require_once("$class_path/titre_uniforme.class.php");
		$titre_uniforme = new titre_uniforme();
		$titre_uniforme->update($value);
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $name, 0, 0,$titre_uniforme->id);
		break;
	default:
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $user_input, $nbr_lignes, $page, 0);
		break;
}

print $sel_footer;

// function d'affichage
function show_results($dbh, $user_input, $nbr_lignes=0, $page=0, $id = 0) {
	global $nb_per_page;
	global $base_url;
	global $caller;
	global $class_path;
	global $no_display;
 	global $charset;
 	global $msg ;
 	global $callback;

	if (!$id) { 	
		// on récupére le nombre de lignes 
		if($user_input=="") {
			$requete = "SELECT COUNT(1) FROM titres_uniformes where tu_id!='$no_display' ";
		} else {
			$aq=new analyse_query(stripslashes($user_input));
			if ($aq->error) {
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				exit;
			}
			$requete=$aq->get_query_count("titres_uniformes","tu_name,","index_tu","tu_id","tu_id!='$no_display'");
			print $requete;
		}
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = @mysql_result($res, 0, 0);
	} else {
		$nbr_lignes=1;
	}
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on lance la vraie requête
		if (!$id) {
			if($user_input=="") {
				$requete = "SELECT * FROM titres_uniformes where tu_id!='$no_display' ORDER BY tu_name LIMIT $debut,$nb_per_page ";
			} else {
				$members=$aq->get_query_members("titres_uniformes","tu_name","index_tu","tu_id");
				$requete="select *,".$members["select"]." as pert from titres_uniformes where ".$members["where"]." and tu_id!='$no_display' group by tu_id order by pert desc,index_tu limit $debut,$nb_per_page";	
			}
		} else {
			$requete="select * from titres_uniformes where tu_id='".$id."'";
		}
		$res = @mysql_query($requete, $dbh);
		while(($titre_uniforme=mysql_fetch_object($res))) {
			$name = $titre_uniforme->tu_name;

			print "<div class='row'>";
			print pmb_bidi("<a href='#' onclick=\"set_parent('$caller', '$titre_uniforme->tu_id', '".htmlentities(addslashes($name),ENT_QUOTES, $charset)."','$callback')\">$name</a>");
			print "</div>";

		}
		mysql_free_result($res);

		// constitution des liens
		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;

		// affichage du lien précédent si nécéssaire
		print "<div class='row'>&nbsp;<hr /></div><div align='center'>";
		$url_base = $base_url."&rech_regexp=$rech_regexp&user_input=".rawurlencode(stripslashes($user_input));
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		print $nav_bar;
		print "</div>";
		}
	else {
		print $msg["aut_titre_uniforme_not_found"];
	}
}

