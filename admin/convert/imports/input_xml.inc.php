<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: input_xml.inc.php,v 1.8 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function _get_n_notices_($fi,$file_in,$input_params,$origine) {
	//mysql_query("delete from import_marc");
	$index=array();
	$i=false;
	$n=1;
	$fcontents="";
	while ($i===false) {
		$i=strpos($fcontents,"<".$input_params['NOTICEELEMENT'].">");
		if ($i===false) $i=strpos($fcontents,"<".$input_params['NOTICEELEMENT']." ");
		if ($i!==false) {
			$i1=strpos($fcontents,"</".$input_params['NOTICEELEMENT'].">");
			while ((!feof($fi))&&($i1===false)) {
				$fcontents.=fread($fi,4096);
				$i1=strpos($fcontents,"</".$input_params['NOTICEELEMENT'].">");
			}
			if ($i1!==false) {
				$notice=substr($fcontents,$i,$i1+strlen("</".$input_params['NOTICEELEMENT'].">")-$i);
				$requete="insert into import_marc (no_notice, notice, origine) values($n,'".addslashes($notice)."','$origine')";
				mysql_query($requete);
				$n++;
				$index[]=$n;
				$fcontents=substr($fcontents,$i1+strlen("</".$input_params['NOTICEELEMENT'].">"));
				$i=false;
			}
		} else {
			if (!feof($fi))
				$fcontents.=fread($fi,4096);
			else break;
		}
	}

	return $index;
}

?>