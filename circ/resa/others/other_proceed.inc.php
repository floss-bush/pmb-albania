<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// $Id: other_proceed.inc.php,v 1.9 2009-05-16 11:12:03 dbellamy Exp $
// Armelle : a priori plus utilisé
// la couleur pour la mise en évidence des mots trouvés
$high_color = "#800080";
define('DEBUG', 0);

// définition de la classe de passage par page
class other_search {
	var $requete			= '';	// la requête SQL complète
	var $nbr_rows 			= 0;	// le nombre de résultats trouvés
	var $results_per_pages	= 0;	// nombre de résultats par page à afficher
	var $display			= '';	// affichage en clair de la requête utilisateur
	var $terms					;	// tableau des mots de la requête (pour highlight)
}

if($obj) {
	// $obj est réputé objet (serialisé et urlencodé)
	// on a juste à le decoder pour récupérer notre instance de la classe other_search
	$ourSearch = unserialize(urldecode($obj));
	} else {
		// sinon, il faut instancier ourSearch (other_search) avec ce dont on dispose 
		// include de fabrication de la fonction ad-hoc
		include('./catalog/notices/search/others/make_object.inc.php');
		$other_query = clean_string(($other_query));
		// on définit un tableau contenant les termes de la saisie utilisateur
		// récupération du nombre de résultats par page
		if($res_per_page) $results_per_page = $res_per_page;
			else $results_per_page = $nb_per_page_a_search;
		$ourSearch = new other_search();
		$ourSearch->terms = preg_split('/[\s]+/', $other_query, -1, PREG_SPLIT_NO_EMPTY);
		$query = test_other_query($n_resume_flag, $n_gen_flag, $n_titres_flag, $n_matieres_flag, $other_query, $search_type);
		// si la recherche match/against n'a rien donné, on force en regexp
		if($query['type'] == 1 && $query['nbr_rows'] == 0)
			$query = test_other_query($n_resume_flag, $n_gen_flag, $n_titres_flag, $n_matieres_flag, $other_query, $search_type, TRUE);
		$ourSearch->requete = "SELECT * FROM notices WHERE ${query['restr']} ORDER BY ${query['order']}";
		$ourSearch->nbr_rows = $query['nbr_rows'];
		$ourSearch->results_per_page = $results_per_page;
		$ourSearch->display = $query['display'];
		}



if($ourSearch->nbr_rows == 0) {
	print $RESA_other_search;
	error_message($msg[4043], $ourSearch->display." : ".$msg[1915], 0, 'javascript:history.go(-1)');
} else {
	// fabrication de l'objet transmis de pages en pages
	$obj = urlencode(serialize($ourSearch));
	print pmb_bidi("<div class='othersearchinfo'>$msg[401] ".$ourSearch->display." | ".$ourSearch->nbr_rows.$msg[1916]."</div>");

	// définition de la page actuelle
	if(!$page) $page=1;
	$debut =($page-1)*$ourSearch->results_per_page;
	$requete = $ourSearch->requete." LIMIT $debut,".$ourSearch->results_per_page;

	// inclusion du javascript de gestion des listes dépliables
	// début de liste
	print $begin_result_liste;

	// boucle de fetch des notices
	$res = @mysql_query($requete, $dbh);
	while(($n=mysql_fetch_object($res))) { 
		if($n->niveau_biblio != 's' && $n->niveau_biblio != 'a') {
			// notice de monographie
			$link = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_notice=!!id!!";;
			$display = new mono_display($n, 6, $link, 1, '');
			$notice = $display->result;
		} else {
			// on a affaire à un périodique
			// préparation des liens pour lui
			$link_serial = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=view_serial&serial_id=!!id!!";
			$link_analysis = '';
			$link_bulletin = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=!!id!!";
			$serial = new serial_display($n, 6, $link_serial, $link_analysis, $link_bulletin);
			$notice = $serial->result;
		}
 		print pmb_bidi($notice);
	}

	// fin de liste

print	$end_result_list;

	// constitution des liens
	$nbepages = ceil($ourSearch->nbr_rows/$ourSearch->results_per_page);
	$suivante = $page+1;
	$precedente = $page-1;

	// affichage du lien précédent si nécéssaire

	$unq=md5(microtime());

	if($precedente > 0) {
		$nav_bar .= "<form class='form-$current_module' name='page_prec' action=\"./circ.php?categ=resa&mode=4&id_empr=$id_empr&group_ID=$groupID&unq=$unq\" method='post'>
		<input type='hidden' name='obj' value=\"$obj\" />
		<input type='hidden' name='page' value=\"$precedente\" />
		</form>";
		$nav_bar .= "<img src='./images/left.gif' hspace='3' align='middle' border='0' onClick=\"document.page_prec.submit();\">";
		}

	for($i = 1; $i <= $nbepages; $i++) {
		if($i==$page) $nav_bar .= "<b>page $i/$nbepages</b>";
		}
	
	if($suivante<=$nbepages) {
		$nav_bar .= "<img src='./images/right.gif' hspace='3' align='middle' border='0' onClick=\"document.page_next.submit();\">";
		$nav_bar .= "<form class='form-$current_module' name=\"page_next\" method=\"post\" action=\"./circ.php?categ=resa&mode=4&id_empr=$id_empr&groupID=$groupID&unq=$unq\">
		<input type='hidden' name='obj' value=\"$obj\" />
		<input type='hidden' name='page' value=\"$suivante\" />
		</form>";
		}	

	print "<div class=\"row\"><div align='center'>$nav_bar</div></div>";	
}


// la couleur pour la mise en évidence des mots trouvés
$high_color = "#800080";

?>

<?php

// pour débuggage
if(DEBUG) {
	print "<p><font color=#ff0000>&lt;debug mode&gt;</font>";
	print '<br />$ourSearch->requete : '.$ourSearch->requete;
	print '<br />$ourSearch->nbr_rows : '.$ourSearch->nbr_rows;
//	print '<br />$ourSearch->nb_results : '.$ourSearch->nb_results;
	print '<br />$ourSearch->results_per_page : '.$ourSearch->results_per_page;
/*	print '<br />$ourSearch->sql_sep : '.$ourSearch->sql_sep;
	print '<br />$ourSearch->on_resume : '.$ourSearch->on_resume;
	print '<br />$ourSearch->on_contenu : '.$ourSearch->on_contenu;
	print '<br />$ourSearch->accept_subset : '.$ourSearch->accept_subset; */
	print '<br />$ourSearch->display : '.$ourSearch->display.'<br /></p>';
	print "<p><strong>object serialized</strong> :<br />"; 
	$result = serialize($ourSearch);
	print "<br />$result<br />";
	print '<br /><strong>$obj content (sent to hidden form)</strong> :<br />'.$obj;
	print '<br /><font color=#ff0000>&lt;/debug mode&gt;</font></p>';
}

?>