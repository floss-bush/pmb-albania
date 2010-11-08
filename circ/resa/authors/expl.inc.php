<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl.inc.php,v 1.12 2007-10-03 17:51:07 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// accès à une notice par code-barre, ISBN, ou numéro commercial
// on commence par voir ce que la saisie utilisateur est ($ex_query)

$ex_query = clean_string($ex_query);

$EAN = '';
$isbn = '';
$code = '';

if(isEAN($ex_query)) {
	// la saisie est un EAN -> on tente de le formater en ISBN
	$EAN=$ex_query;
	$isbn = EANtoISBN($ex_query);
	// si échec, on prend l'EAN comme il vient
	if(!$isbn) 
		$code = str_replace("*","%",$ex_query);
	else {
		$code=$isbn;
		$code10=formatISBN($code,10);
	}
} else {
	if(isISBN($ex_query)) {
		// si la saisie est un ISBN
		$isbn = formatISBN($ex_query);
		// si échec, ISBN erroné on le prend sous cette forme
		if(!$isbn) 
			$code = str_replace("*","%",$ex_query);
		else {
			$code10=$isbn ;
			$code=formatISBN($code10,13);
		}
	} else {
		// ce n'est rien de tout ça, on prend la saisie telle quelle
		$code = str_replace("*","%",$ex_query);
	}
}
			
if ($EAN && $isbn) {
	// cas des EAN purs : constitution de la requête
	$requete = "SELECT distinct notices.* FROM (notices left join exemplaires on notices.notice_id=exemplaires.expl_notice)";
	$requete .= " WHERE niveau_biblio='m' AND (exemplaires.expl_cb like '$code' OR exemplaires.expl_cb='$ex_query' OR notices.code in ('$code','$EAN'".($code10?",'$code10'":"").")) limit 10";
	$myQuery = mysql_query($requete, $dbh);
} elseif ($isbn) {
	// recherche d'un isbn
	$requete = "SELECT distinct notices.* FROM (notices left join exemplaires on notices.notice_id=exemplaires.expl_notice)";
	$requete .= " WHERE niveau_biblio='m' AND (exemplaires.expl_cb like '$code' OR exemplaires.expl_cb='$ex_query' OR notices.code in ('$code'".($code10?",'$code10'":"").")) limit 10";
	$myQuery = mysql_query($requete, $dbh);
} elseif ($code) {
	// recherche d'un exemplaire
	// note : le code est recherché aussi dans le champ code des notices
	// (cas des code-barres disques qui échappent à l'EAN)
	//
	$requete = "SELECT distinct notices.* FROM (notices left join exemplaires on notices.notice_id=exemplaires.expl_notice)";
	$requete .= " WHERE niveau_biblio='m' AND (exemplaires.expl_cb like '$code' OR notices.code like '$code') limit 10";
	$myQuery = mysql_query($requete, $dbh);
	if(mysql_num_rows($myQuery)==0) {
		// rien trouvé en monographie
		$requete = 'SELECT distinct notices.*, expl_bulletin, bulletin_id FROM notices left join bulletins on bulletin_notice=notice_id left join exemplaires on (bulletin_id=expl_bulletin and expl_notice=0) ';
		$requete .= " WHERE niveau_biblio='s' AND (exemplaires.expl_cb like '$code' OR bulletin_numero like '$code' OR bulletin_cb like '$code' OR notices.code like '$code') ";
		$requete .= " GROUP BY bulletin_id limit 10";
		$rqt_bulletin=1;
	}
} 

if ($rqt_bulletin!=1) {
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
						} else $link = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_notice=!!id!!";
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
						print "document.location = \"./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_notice=".$notice->notice_id."\"";
						print "</script>";
						}
				}
		} else {
			print $RESA_author_query;
			error_message($msg[235], $msg[307]." $ex_query", 0, "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=0");
			}
	} else {
		// C'est un périodique
		$res = @mysql_query($requete, $dbh);
		if (mysql_num_rows($res)) {
			print $begin_result_liste;
			while (($n=mysql_fetch_object($res))) {
				if ($categ=="visu_rech") {
					if (SESSrights & CATALOGAGE_AUTH) {
						$link_serial = "./catalog.php?categ=serials&sub=view&serial_id=!!id!!";
						$link_analysis = "";
						$link_bulletin = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!";
						} else {
							$link_serial = "";
							$link_analysis = "";
							$link_bulletin = "";
							}
					require_once ("$include_path/bull_info.inc.php") ;
					require_once ("$class_path/serials.class.php") ;
					$expl->isbd = show_bulletinage_info($n->bulletin_id);
					print pmb_bidi($expl->isbd) ;
					// JUSQU'ICI
					} else {
						$link_serial = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=view_serial&serial_id=!!id!!";
						$link_analysis = "";
						$link_bulletin = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=!!id!!";
						$serial = new serial_display($n, 6, $link_serial, $link_analysis, $link_bulletin);
						print pmb_bidi($serial->result);
						}
				}
			print $end_result_liste;
			} else {
				print $RESA_author_query;
				error_message($msg[235], $msg[307]." $ex_query", 0, "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=0");
				}
		}

