<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notes.inc.php,v 1.2 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=notes&caller=$caller&param1=$param1&param2=$param2&idaction=$idaction&current_note=$current_note";

// contenu popup sélection notes
require('./selectors/templates/sel_notes.tpl.php');

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

function show_results($dbh,$user_input,$nbr_lignes=0,$page=0){
	global $nb_per_page;
	global $base_url;
	global $caller;
	global $msg;
	global $charset;
	global $idaction;
	global $current_note;
	
	$user_input = str_replace('*','%',$user_input);
	if($user_input == ""){
		$req_count = "select count(1) from demandes_notes where num_action='".$idaction."' and id_note !='".$current_note."'";		
	} else {		
		$req_count = "select count(1) from demandes_notes where num_action='".$idaction."' and contenu like '%".$user_input."%' and id_note !='".$current_note."'";
	}
	$res = mysql_query($req_count, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);
	
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on lance la vraie requête
		if($user_input == ""){
			$req = "select id_note, date_note, CONCAT(SUBSTRING(contenu,1,50),'','...') as note from demandes_notes where num_action='".$idaction."' and id_note !='".$current_note."'";
		} else {
			$req = "select id_note, date_note, CONCAT(SUBSTRING(contenu,1,50),'','...') as note from demandes_notes where num_action='".$idaction."' and contenu like '%".$user_input."%' and id_note !='".$current_note."'";
		}
		
		$res = mysql_query($req,$dbh);
		while(($note = mysql_fetch_object($res))){
			print "<div class='row'>";
			print "<a href='#' onclick=\"set_parent('$caller', '$note->id_note', '".htmlentities(addslashes($note->note),ENT_QUOTES,$charset)."')\"> [".htmlentities(formatdate($note->date_note),ENT_QUOTES,$charset).'] '.htmlentities($note->note,ENT_QUOTES,$charset)."</a>";
			print "</div>";
		}
		mysql_free_result($res);

		// constitution des liens
		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;
	}
	print "<div class='row'>&nbsp;<hr /></div><div align='center'>";
	$url_base = $base_url."&user_input=".rawurlencode(stripslashes($user_input));
	$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
	print $nav_bar;
	print "</div>";
}

// affichage des membres de la page
$sel_search_form = str_replace("!!deb_rech!!", stripslashes($f_user_input), $sel_search_form);
print $sel_search_form;
print $jscript;
show_results($dbh, $user_input, $nbr_lignes, $page);
print $sel_footer;
?>