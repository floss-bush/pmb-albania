<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl.inc.php,v 1.4 2007-10-02 19:32:49 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// accès à une notice par code-barre, ISBN, ou numéro commercial

// on commence par voir ce que la saisie utilisateur est ($ex_query)

$ex_query = clean_string($ex_query);

$EAN = '';
$isbn = '';
$code = '';
// on teste si c'est un EAN
if(isEAN($ex_query)) {
	// la chaine passée est un EAN pur (rappel : livres seulement)
	$isbn = EANtoISBN($ex_query);	
	$EAN = $ex_query;
	$code10=formatISBN($isbn,10);
} else {
	// apparement pas un EAN
	// l'utilisateur a peut-être saisi un isbn à la main
	if(isISBN($ex_query)) {
		$isbn = formatISBN($ex_query);
		$code13=formatISBN($isbn,13);
	} else {
		$code = $ex_query;
	}
}

if ($EAN && $isbn) {
	// cas des EAN purs : constitution de la requête
	$requete = "SELECT * FROM notices WHERE code='$EAN' OR code in ('$isbn','".$code10."')";
	$myQuery = mysql_query($requete, $dbh);
} elseif ($isbn) {
	// recherche d'un isbn
	$requete = "SELECT * FROM notices WHERE code in ('$isbn','".$code13."')";
	$myQuery = mysql_query($requete, $dbh);
} else {
	// recherche d'un exemplaire
	// note : le code est recherché aussi dans le champ code des notices
	// (cas des code-barres disques qui échappent à l'EAN)
	$requete = 'SELECT notices.* FROM notices left join exemplaires on notice_id=expl_notice ';
	$requete .= " WHERE exemplaires.expl_cb='$code' OR notices.code='$code' ";

	$myQuery = mysql_query($requete, $dbh);

}
			
if(mysql_num_rows($myQuery)) {
	if(mysql_num_rows($myQuery) > 1) {
		// la recherche fournit plusieurs résultats !!!
		// boucle de parcours des notices trouvées
		// inclusion du javascript de gestion des listes dépliables
		// début de liste
		print $begin_result_liste;
		while($notice = mysql_fetch_object($myQuery)) {
			if($notice->niveau_biblio != 's' && $notice->niveau_biblio != 'a') {
				// notice de monographie (les autres n'ont pas de code ni d'exemplaire !!! ;-)
				if ($categ=="visu_rech") {
					if (SESSrights & CATALOGAGE_AUTH) {
						$link = "./catalog.php?categ=isbd&id=!!id!!" ;
					} else $link="" ;
				} else $link = "./circ.php?categ=resa_planning&resa_action=add_resa&id_empr=$id_empr&groupID=$groupID&id_notice=!!id!!";
				$display = new mono_display($notice, 6, $link, 1, "", "", "", 1);
				//($id, $level=1, $action='', $expl=1, $expl_link='', $lien_suppr_cart="", $explnum_link='', $show_resa=0 )
				print pmb_bidi($display->result);
				
			} 
		}
		print $end_result_liste;
	} else {
		$notice = mysql_fetch_object($myQuery);
		if ($categ=="visu_rech") {
			if (SESSrights & CATALOGAGE_AUTH) {
				$link = "./catalog.php?categ=isbd&id=!!id!!" ;
			} else $link="" ;
			print $begin_result_liste;
			$display = new mono_display($notice, 6, $link, 1, "", "", "", 1);
			print pmb_bidi($display->result);
			print $end_result_liste;
		} else {
			print "<div class=\"row\"><div class=\"msg-perio\">".$msg[recherche_encours]."</div></div>";
			// un seul résultat 
			print "<script type=\"text/javascript\">";
			print "document.location = \"./circ.php?categ=resa_planning&resa_action=add_resa&id_empr=$id_empr&groupID=$groupID&id_notice=".$notice->notice_id."\"";
			print "</script>";
		}
	}
} else {
	print $RESA_author_query;
	error_message($msg[235], $msg[307]." $ex_query", 0, "./circ.php?categ=resa_planning&resa_action=search_resa&id_empr=$id_empr&groupID=$groupID&mode=0");
}

