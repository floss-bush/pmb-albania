<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: custom_cote_02.inc.php,v 1.6 2008-03-21 11:05:07 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// custom function to prefill the cote field when adding a new document
//
// if exists an author at level 0 
// cote = dewey + 3 char of author
// else
// cote =  dewey + 3 char of title + vol. number
//
// created by Marco Vaninetti, modified by Gautier Michelin

function prefill_cote($id_notice=0,$cote="") {
 	global $dbh;
 	global $value_prefix_cote ;
	$res_dewey = '';
	$res_author = '';
	$res_title = '';
	$res_nvol = '';
	$res_cote = '';
	if (!$cote) {
	
		// fetch the dewey code
		$requete = "SELECT indexint_name FROM indexint, notices where notice_id='$id_notice' and indexint=indexint_id ";
		$result = @mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($result);
		if ($nbr_lignes) {
			$res = mysql_fetch_object($result) ;
			$res_dewey= $res->indexint_name;
			}
			
		// fetch the title and the volume number
		$requete = "SELECT index_sew, tnvol FROM notices WHERE notice_id= '$id_notice' ";
		$result = @mysql_query($requete, $dbh);
		$res = mysql_fetch_object($result);
		$res_title = pmb_strtoupper(pmb_str_replace(" ","",$res->index_sew));
		$res_nvol = $res->tnvol;
		
		// fetch the first author, but only if his responsability_type is 0
		$requete = "SELECT index_author, responsability_type FROM authors, responsability WHERE author_id=responsability_author and responsability_notice = '$id_notice' ORDER BY responsability_type, responsability_ordre LIMIT 1";
		$result = @mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($result);
		
		// build the code using also the author name
		if ($nbr_lignes) {
			$res = mysql_fetch_object($result);
			$res_author = pmb_strtoupper(pmb_substr(pmb_str_replace(" ","",$res->index_author),0,3));
			$res_title = pmb_substr($res_title,0,3);
			$res_cote = $res_dewey." ".$res_author;
		} else 	{
				// no author at responsability_type 0 so build the code using only the title	
				$res_title = pmb_substr($res_title,0,3);
				$res_cote = $res_dewey." ".$res_title." ".$res_nvol;
		}
		return $value_prefix_cote.$res_cote;		
		} else  return $cote ;
}
