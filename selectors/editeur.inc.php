<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: editeur.inc.php,v 1.23 2010-07-28 07:44:39 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=editeur&caller=$caller&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn";

// contenu popup sélection éditeur
require_once('./selectors/templates/sel_editeur.tpl.php');

// traitement en entrée des requêtes utilisateur
if ($deb_rech) $f_user_input = $deb_rech ;
if($f_user_input=="" && $user_input=="") {
	$user_input='';
	} else {
		// traitement de la saisie utilisateur
		if ($user_input) $f_user_input=$user_input;
		if (($f_user_input)&&(!$user_input)) $user_input=$f_user_input;	
	}

// affichage du header
print $sel_header;

if($bt_ajouter == "no"){
	$bouton_ajouter="";
}else{
	$bouton_ajouter= "<input type='button' class='bouton_small' onclick=\"document.location='$base_url&action=add&deb_rech=this.form.f_user_input.value'\" value='$msg[143]'>";
}
echo "valeur : ".$f_user_input."<br>";
// affichage des membres de la page
switch($action){
	case 'add':
		$publisher_form = str_replace("!!deb_saisie!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $publisher_form);
		print $publisher_form;
		break;
	case 'update':
		$value['name']	=	$ed_nom;
		$value['adr1']	=	$ed_adr1;
		$value['adr2']	=	$ed_adr2;
		$value['cp']	=	$ed_cp;
		$value['ville']	=	$ed_ville;
		$value['pays']	=	$ed_pays;
		$value['web']	=	$ed_web;
		// classe pour la gestion des éditeurs
		require_once("$class_path/editor.class.php");
		$editeur = new editeur();
		$editeur->update($value);
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $ed_nom, 0, 0, $editeur->id);
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
	global $msg;
 	global $charset;
 	global $no_display ;

	// on récupére le nombre de lignes qui vont bien
	if (!$id) {
		if($user_input=="") {
			$requete = "SELECT COUNT(1) FROM publishers where ed_id!='$no_display'";
		} else {
			$aq=new analyse_query(stripslashes($user_input));
			if ($aq->error) {
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				exit;
			}
			$requete=$aq->get_query_count("publishers","ed_name","index_publisher","ed_id", "ed_id!='$no_display'");
		}
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = @mysql_result($res, 0, 0);
	} else $nbr_lignes=1;
	
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on construit la vraie requête
		if (!$id) {
			if($user_input=="") $requete = "SELECT * FROM publishers where ed_id!='$no_display' ORDER BY index_publisher LIMIT $debut,$nb_per_page ";
				else {
					$members=$aq->get_query_members("publishers","ed_name","index_publisher","ed_id");
					$requete="select *,".$members["select"]." as pert from publishers where ".$members["where"]." and ed_id!='$no_display' group by ed_id order by pert desc,index_publisher limit $debut,$nb_per_page";
					}
			} else $requete="select * from publishers where ed_id='".$id."'";
		$res = @mysql_query($requete, $dbh);
		while(($ed=mysql_fetch_object($res))) {
			$affcall=$ed->ed_name;
			if ($ed->ed_ville) 
				if ($ed->ed_pays) $affcall.=" ($ed->ed_ville - $ed->ed_pays)";
				else $affcall.=" ($ed->ed_ville)";
			print pmb_bidi("
 				<a href='#' onclick=\"set_parent('$caller', '$ed->ed_id', '".htmlentities(addslashes($affcall),ENT_QUOTES, $charset)."')\">".
				htmlentities($affcall,ENT_QUOTES, $charset)."</a><br />");
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