<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// © 2006 mental works / www.mental-works.com contact@mental-works.com
// 	repris et corrigé par PMB Services 
// +-------------------------------------------------+
// $Id: tags.class.php,v 1.3 2009-06-02 12:05:19 kantin Exp $

// définition de la classe d'affichage des 'tags'



class tags {

	// ---------------------------------------------------------------
	//		propriétés de la classe
	// ---------------------------------------------------------------
	var $search_tag=""; 
	// ---------------------------------------------------------------
	//		constructeur
	// ---------------------------------------------------------------
	function tags() {		
	}
	
	function get_array($start="", $pos_cursor=0){
		global $dbh;
		global $pmb_keyword_sep;
		
		
		$liste_mots = array();
		$liste_res = array();
		$tags = array();
		$liste_finale = array();

	 	if(strlen($start)==$pos_cursor){
	 		$liste_mots = explode($pmb_keyword_sep,$start);
			$mot = $liste_mots[sizeof($liste_mots)-1];
			$liste_mots[sizeof($liste_mots)-1] = '';
			$deb_chaine = implode($pmb_keyword_sep,$liste_mots);	
		} else {
	 		$liste_mots = explode($pmb_keyword_sep,substr($start,0,$pos_cursor));
	 		$mot = $liste_mots[sizeof($liste_mots)-1];
	 		$liste_mots[sizeof($liste_mots)-1] = '';
			if($mot){
				$fin_chaine = substr($start,$pos_cursor);
				$temp = explode($pmb_keyword_sep,$fin_chaine);
				$temp[0]='';
				$fin_chaine = implode($pmb_keyword_sep,$temp);
			} else $fin_chaine = substr($start,$pos_cursor);	
			$deb_chaine = implode($pmb_keyword_sep,$liste_mots); 	
	 	}
		$this->search_tag = $mot;
	 	
		$requete = "select index_l from notices where index_l is not null and index_l like '".addslashes($mot)."%' or index_l like '%".$pmb_keyword_sep.addslashes($mot)."%' limit 0,20";
		//print $requete;
		$res = mysql_query($requete,$dbh);
		while(($mot_trouve=mysql_fetch_object($res))){
			$liste_res[] = explode($pmb_keyword_sep,$mot_trouve->index_l);
		}
		
		$cpt=0;
		$buffer_keys = array();
		
		for($i=0;$i<sizeof($liste_res);$i++){
			for($j=0;$j<sizeof($liste_res[$i]);$j++){
				if((substr(strip_empty_chars($liste_res[$i][$j]),0,strlen($mot)) == strip_empty_chars($mot)) && !array_key_exists($liste_res[$i][$j],$tags)){					 				
					if(!array_key_exists($liste_res[$i][$j],$buffer_keys)) {
						$tags[$liste_res[$i][$j]]=$deb_chaine.$liste_res[$i][$j].($mot?'':$pmb_keyword_sep).$fin_chaine;
						$buffer_keys[$liste_res[$i][$j]] = $cpt;		
						$liste_finale[$cpt] = $tags;	
						$tags=array();
						$cpt++;	
					}
				}
			}
		}
		return $liste_finale;
	}
	
	function get_taille_search(){
		return strlen($this->search_tag);
	}
	
}
?>