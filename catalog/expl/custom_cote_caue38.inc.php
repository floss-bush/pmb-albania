<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: custom_cote_caue38.inc.php,v 1.3 2008-03-26 12:55:54 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function prefill_cote ($id_notice=0,$cote="") {
 	global $dbh;
	global $value_prefix_cote ;
	if (!$cote) {
		
		

		//Recherche du Plan de classement
		$q = "select cat1.libelle_categorie as libelle_pc ";
		$q.= "from notices, notices_categories, noeuds, categories as cat1, categories as cat2 ";
		$q.= "where notice_id='".$id_notice."' and num_thesaurus='2' ";
		$q.= "and cat2.num_noeud=noeuds.num_renvoi_voir and cat1.num_noeud=noeuds.id_noeud ";
		$q.= "and notices_categories.num_noeud=cat2.num_noeud and notices.notice_id=notices_categories.notcateg_notice ";
		$q.= "order by ordre_categorie ";
		$q.= "limit 1 ";
		$r = mysql_query($q, $dbh);
		$nbr_lignes = mysql_num_rows($r);


		$q1 = "select typdoc from notices where notice_id='".$id_notice."' ";
		$r1 = mysql_query($q1, $dbh);
		$typdoc = mysql_result($r1, 0, 0);

		if ($nbr_lignes) {
			
			$l1=mysql_fetch_object($r);
			
			$pc=trim($l1->libelle_pc);
			$cotem="";
			if ($typdoc == "m") {
				$cotem = "MULT/";
				$pcm = $cotem.$pc; 
			}

			//Recherche du +grand numéro inutilisé dans la cotation
			$q = "select convert( if (expl_cote like ('MULT%'), substring(trim(expl_cote), length('".$pcm."')+2) , substring(trim(expl_cote), length('".$pc."')+2) ), unsigned)  as numero from exemplaires where expl_cote like('%".$pc."/%') order by numero asc ";
			$r = mysql_query($q, $dbh);

			$max=0;
			$before_num = 0;
			while($cote=mysql_fetch_object($r)) {
			
				$current_num= $cote->numero;
				
				if ($current_num > $before_num+1) {
					$max=$before_num;
					break;
				} else {
					$before_num=$current_num;
					$max=$current_num;
				} 
				
			}
			$max++;				
			$res_cote = $value_prefix_cote.$cotem.$pc."/".$max;	
			return $res_cote;

		} else {

			return $value_prefix_cote ;

		}
		
	} else {
		return $cote;
	}
}
