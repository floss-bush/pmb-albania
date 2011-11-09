<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: custom_cote_pnrp.inc.php,v 1.3 2011-01-26 10:03:23 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonction personnalisée pour remplir la cote automatiquement
// reprend le custom_cote 2 mais utilise les trois premieres lettres 
/// de l'éditeur
// si on a un editeur
// cote = indexation + 4 caractères editeur
// si l'éditeur a un sigle saisi entre parenthèses, on prend 4 lettres après la parenthèse
// cote =  indexation + 4 car éditeur entre parenthèses
//

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
		
		// fetch the editor
		$requete = "SELECT ed_name, index_publisher FROM publishers, notices WHERE ed1_id=ed_id and notice_id = '$id_notice'";
    	$result = @mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($result);
		
		// build the code using also the author name
		if ($nbr_lignes) {
			$res = mysql_fetch_object($result);
			if (strpos($res->ed_name,'(')>0) {
        $res_editor =	substr(stristr($res->ed_name,'('),1,4); 
      } else {
        $res_editor =	strtoupper(substr($res->ed_name,0,4)); 
      }
			$res_title = pmb_substr($res_title,0,3);
			$res_cote = $res_dewey." ".$res_editor;
		} else 	{
				// no author at responsability_type 0 so build the code using only the title	
				$res_title = pmb_substr($res_title,0,4);
				$res_cote = $res_dewey." ".$res_title." ".$res_nvol;
		}
		return $value_prefix_cote.$res_cote;		
		} else  return $cote ;
}
