<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: input_ris.inc.php,v 1.2 2009-11-06 16:09:33 kantin Exp $

function _get_n_notices_($fi,$file_in,$input_params,$origine) {
	global $base_path;
	
	$first=true;
	$stop=false;
	$content="";
	$index=array();
	$n=1;
	//Lecture du fichier d'entrée
	while (!$stop) {
		//Recherche de TY
		if ($content) $pos_deb=strpos($content,"TY  -",1);
		while ((!$pos_deb)&&(!feof($fi))) {
			$content.=fread($fi,4096);
			$pos_deb=strpos($content,"TY  -",1);
		}
		
		//Début accroché
		if ($pos_deb!==false) {
			//Notice = début jusqu'au TY -
			$notice=substr($content,0,$pos_deb);
			$content=substr($content,$pos_deb);
		} else {
			//Pas de notice suivante, c'est la fin du fichier
			$notice=$content;
			$stop=true;
		}
		
		if ($notice) {
			$requete="INSERT INTO import_marc (no_notice, notice, origine) VALUES ($n,'".addslashes($notice)."','$origine')";
			mysql_query($requete);
			$n++;
			$t=array();
			$t["POS"]=$n;
			$t["LENGHT"]=1;
			$index[]=$t;
		}
	}
	return $index;
}
?>