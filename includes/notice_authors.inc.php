<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_authors.inc.php,v 1.5 2008-03-21 11:05:07 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// récupération des responsabilités d'une notice

// get_notice_authors : retourne un tableau avec les responsabilités d'une notice donnée
function get_notice_authors($notice=0) {
	global $dbh;
	
	$responsabilites = array() ;
	$auteurs = array() ;
	
	$res["responsabilites"] = array() ;
	$res["auteurs"] = array() ;
	
	$rqt = "select author_id, responsability_fonction, responsability_type ";
	$rqt.= "from responsability, authors where responsability_notice='$notice' and responsability_author=author_id order by responsability_type, responsability_ordre " ;

	$res_sql = mysql_query($rqt, $dbh);
	while ($notice=mysql_fetch_object($res_sql)) {
		$responsabilites[] = $notice->responsability_type ;
		$auteurs[] = array( 
				'id' => $notice->author_id,
				'fonction' => $notice->responsability_fonction,
				'responsability' => $notice->responsability_type
				) ;
		}
	$res["responsabilites"] = $responsabilites ;
	$res["auteurs"] = $auteurs ;
	return $res;
	}
