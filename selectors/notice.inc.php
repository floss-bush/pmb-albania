<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice.inc.php,v 1.23 2009-05-16 10:52:44 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=notice&caller=$caller&param1=$param1&param2=$param2&no_display=$no_display&bt_ajouter=$bt_ajouter";
if($niveau_biblio){ 
	$filtre_notice=" and niveau_biblio='$niveau_biblio' ";
	$base_url="./select.php?what=notice&niveau_biblio=$niveau_biblio&modele_id=$modele_id&serial_id=$serial_id&caller=$caller&param1=$param1&param2=$param2&no_display=$no_display&bt_ajouter=$bt_ajouter";
}
// contenu popup sélection emprunteur
require('./selectors/templates/sel_notice.tpl.php');
include_once('./includes/isbn.inc.php');



//droits d'acces lecture notice
$acces_j='';
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("./classes/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_j = $dom_1->getJoin($PMBuserid,4,'notice_id');
} 


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

function show_results ($dbh, $user_input, $nbr_lignes=0, $page=0, $id = 0) {
	global $nb_per_page;
	global $base_url;
	global $caller;
	global $msg;
	global $no_display ;
	global $charset;
	global $niveau_biblio,$modele_id,$serial_id;
	global $acces_j;
		
	if($niveau_biblio){ 
		$filtre_notice=" and niveau_biblio='$niveau_biblio' ";
	}
	
	
	
	// on récupére le nombre de lignes qui vont bien
	if($user_input=="") {
		$requete_count = "SELECT COUNT(1) FROM notices ";
		$requete_count.= $acces_j;
		$requete_count.= "where notice_id!='".$no_display."' $filtre_notice ";
	} else {
		$aq=new analyse_query(stripslashes($user_input));
		if ($aq->error) {
			error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
			exit;
		}
		$members=$aq->get_query_members("notices","index_wew","index_sew","notice_id");
		$isbn_verif=traite_code_isbn(stripslashes($user_input));
		$suite_rqt="";
		if (isISBN($isbn_verif)) {
			if (strlen($isbn_verif)==13)
				$suite_rqt=" or code='".formatISBN($isbn_verif,13)."' ";
			else $suite_rqt="or code='".formatISBN($isbn_verif,10)."' ";
		}
		$requete_count = "select count(1) from notices ";
		$requete_count.= $acces_j;
		$requete_count.= "where (".$members["where"]." or code like '".addslashes($isbn_verif)."' ".$suite_rqt." ) ";
		$requete_count.= "and notice_id!='".$no_display."' $filtre_notice";
	}
	$res = mysql_query($requete_count, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);

	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on lance la vraie requête
		$isbn_verif=traite_code_isbn(stripslashes($user_input));
		$suite_rqt="";
		if (isISBN($isbn_verif)) {
			if (strlen($isbn_verif)==13)
				$suite_rqt=" or code='".formatISBN($isbn_verif,13)."' ";
			else $suite_rqt="or code='".formatISBN($isbn_verif,10)."' ";
		}
		if($user_input=="") {
			$requete = "SELECT notice_id, tit1, serie_name, tnvol, code FROM notices ";
			$requete.= $acces_j;
			$requete.= "left join series on serie_id=tparent_id ";
			$requete.= "where notice_id!='".$no_display."' $filtre_notice ORDER BY index_sew, code LIMIT $debut,$nb_per_page ";
		} else {
			$requete = "select notice_id, tit1, serie_name, tnvol, code, ".$members["select"]." as pert from notices ";
			$requete.= $acces_j;
			$requete.= "left join series on serie_id=tparent_id where (".$members["where"]." or (code like '".addslashes($isbn_verif)."' ".$suite_rqt.")) ";
			$requete.= "and notice_id!='".$no_display."' $filtre_notice group by notice_id order by pert desc, index_sew, code limit $debut,$nb_per_page";
		}

		$res = @mysql_query($requete, $dbh);
		while(($notice=mysql_fetch_object($res))) {
			$notice_entry = "";
			if ($notice->serie_name) {
				$notice_entry .= $notice->serie_name;
				if ($notice->tnvol) $notice_entry .= ", ".$notice->tnvol;
			}
			$notice_entry ? $notice_entry .= '. '.$notice->tit1 : $notice_entry  = $notice->tit1;
			if($niveau_biblio){
				$location="./catalog.php?categ=serials&sub=modele&act=copy&modele_id=$modele_id&serial_id=$serial_id&new_serial_id=$notice->notice_id";
				print pmb_bidi("<div class='row'>
								<div class='left'>
									<a href='#' onclick=\"copier_modele('$location')\">".htmlentities($notice_entry,ENT_QUOTES,$charset)."</a>
									</div>
								<div class='right'>
									".htmlentities($notice->code,ENT_QUOTES,$charset)."
									</div>
								</div>");
			}
			else{			
				print pmb_bidi("<div class='row'>
								<div class='left'>
									<a href='#' onclick=\"set_parent('$caller', '$notice->notice_id', '".htmlentities(addslashes($notice_entry),ENT_QUOTES,$charset)." ($notice->code)')\">".htmlentities($notice_entry,ENT_QUOTES,$charset)."</a>
									</div>
								<div class='right'>
									".htmlentities($notice->code,ENT_QUOTES,$charset)."
									</div>
								</div>");
			}									
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
