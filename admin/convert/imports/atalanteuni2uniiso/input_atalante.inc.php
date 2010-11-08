<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: input_atalante.inc.php,v 1.3 2007-03-10 08:32:24 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function _get_n_notices_($fi,$file_in,$input_params,$origine) {
	//mysql_query("delete from import_marc");
	$index=array();
	$fcontents=fread($fi,filesize($file_in));
	$n=1;
	$len=0;
	$notices=array();
	//Deux premières lignes ignorées
	$p=strpos($fcontents,"\r\n");
	if ($p!==false) {
		$fcontents=substr($fcontents,$p+2);
		$p=strpos($fcontents,"\r\n");
		if ($p!==false) {
			$fcontents=substr($fcontents,$p+2);
		}
	}
	while ($fcontents) {
		$i1=strpos($fcontents,chr(0x01).chr(0xD).chr(0x0A));
		if ($i1===false) break;
		$ligne=substr($fcontents,0,$i1-1);
		$champs=explode("@",$ligne);
		$notices[$champs[0]][]=$ligne;
		$fcontents=substr($fcontents,$i1+3);
	}
	
	while (list($key,$val)=each($notices)) {
		$notice=implode(chr(0x01).chr(0x0A),$val);
		$notice.=chr(0x01).chr(0x0A);
		$requete="insert into import_marc (no_notice, notice, origine) values($n,'".addslashes($notice)."','$origine')";
		mysql_query($requete);
		$n++;
		$t=array();
		$t["POS"]=$n;
		$t["LENGHT"]=1;
		$index[]=$t;
	}
	return $index;
}


?>