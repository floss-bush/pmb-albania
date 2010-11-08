<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: custom_cote_pmi.inc.php,v 1.1 2009-10-22 07:58:42 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


/*
 * Cote pour PMI : Plan de classement plus nb_expl associé+1
 */
function prefill_cote($id_notice=0,$cote="") {
 	global $dbh;
 	global $value_prefix_cote ;
	$res_dewey = '';
	$res_cote = '';
	$res_expl = '';
	if (!$cote) {
	
		// fetch the dewey code
		$requete = "SELECT indexint_name as index_cote FROM indexint, notices where notice_id='$id_notice' and indexint=indexint_id ";
		$result = @mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($result); 
		if ($nbr_lignes) {
			$res = mysql_fetch_object($result) ;
			$res_dewey= $res->index_cote;
		}

		$requete = "SELECT expl_cote as cote_expl from exemplaires where expl_cote like '".$res_dewey." %' ";
		$result = @mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($result);
		
		// build the code using also the author name
		if ($nbr_lignes) {
			$max_value = 0;
			while($res = mysql_fetch_object($result)){
				$cote_value = substr($res->cote_expl,strpos($res->cote_expl,' '));
				if($max_value < $cote_value) $max_value = $cote_value;
			}
			$res_cote = $res_dewey." ".($max_value+1);
		} else 	{
			$res_cote = $res_dewey." 1";
		}
		return $value_prefix_cote.$res_cote;		
	} else  return $cote ;
}
?>