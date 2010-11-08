<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: input_txt.inc.php,v 1.8 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function _get_n_notices_($fi,$file_in,$input_params,$origine) {
	//$fcontents=fread($fi,filesize($file_in));
	$index=array();
	$n=0;
	$deb_notice="#*#Numéro";
	$en_cours=false;	
	while (!feof($fi)) {
		$line=fgets($fi,4096);
		$line=rtrim($line);
		if (substr($line,0,9)==$deb_notice) {
			//Accrochage début de notice
			if ($en_cours) {
				$n++;
				$requete="insert into import_marc (no_notice, notice, origine) values($n,'".addslashes($notice)."','$origine')";
				mysql_query($requete);
				$t=array();
				$t["POS"]=$n;
				$t["LENGHT"]=1;
				$index[]=$t;
			}
			$notice="";
			$en_cours=true;
		} else {
			if (($en_cours)&&($line!="")) {
				$notice.=$line."\r\n";
			}
		}
	}
	if ($en_cours) {
		$n++;
		$requete="insert into import_marc (no_notice, notice, origine) values($n,'".addslashes($notice)."','$origine')";
		mysql_query($requete);
		$t=array();
		$t["POS"]=$n;
		$t["LENGHT"]=1;
		$index[]=$t;
	}
	return $index;
}


?>