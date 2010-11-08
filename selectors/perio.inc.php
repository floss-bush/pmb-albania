<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: perio.inc.php,v 1.5 2009-05-16 10:52:44 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=perio&caller=$caller&param1=$param1&param2=$param2&no_display=$no_display&bt_ajouter=$bt_ajouter";

// contenu popup sélection emprunteur
require('./selectors/templates/sel_perio.tpl.php');

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
	
	// on récupére le nombre de lignes qui vont bien
	if($user_input=="") {
		$requete = "SELECT COUNT(1) FROM notices where notice_id!='".$no_display."' and niveau_biblio='s' and niveau_hierar='1' ";
	} else {
		$aq=new analyse_query(stripslashes($user_input));
		if ($aq->error) {
			error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
			exit;
		}
		$members=$aq->get_query_members("notices","index_wew","index_sew","notice_id");
		$requete = "select count(notice_id) from notices where (".$members["where"]." or code like '".stripslashes($user_input)."') and notice_id!='".$no_display."' and niveau_biblio='s' and niveau_hierar='1'";
	}
	
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);

	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on lance la vraie requête

		if($user_input=="") {
			$requete = "SELECT notice_id, tit1, code FROM notices where notice_id!='".$no_display."' and niveau_biblio='s' and niveau_hierar='1' ORDER BY tit1, code LIMIT $debut,$nb_per_page ";
		} else {
			$requete = "select notice_id, tit1, code, ".$members["select"]." as pert from notices where (".$members["where"]." or code like '".stripslashes($user_input)."') and notice_id!='".$no_display."' and niveau_biblio='s' and niveau_hierar='1' group by notice_id order by pert desc, index_serie, tnvol, index_sew, code limit $debut,$nb_per_page";
		}

		$res = @mysql_query($requete, $dbh);
		print "<table><tr>";
		while(($notice=mysql_fetch_object($res))) {
			$notice_entry = $notice->tit1."&nbsp;".$notice->code;
			print "
				<tr>
					<td>
						<a href='#' onclick=\"set_parent('$caller', '$notice->notice_id', '".htmlentities(addslashes($notice->tit1),ENT_QUOTES,$charset)." ($notice->code)')\">".htmlentities($notice->tit1,ENT_QUOTES,$charset)."</a></td>
					<td>$notice->code</td>";
			print "</tr>";
		}
		print "</table>";
		mysql_free_result($res);

		// constitution des liens

		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;

	}
		// affichage de la pagination
		
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
