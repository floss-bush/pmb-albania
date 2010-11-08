<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: infopages.inc.php,v 1.1 2008-08-29 09:58:37 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$retaff = "";
for ($ip=0; $ip<count($idpages); $ip++) {

	$requete="select id_infopage, content_infopage from infopages where id_infopage=".$idpages[$ip]." and valid_infopage=1";
	$resultat=mysql_query($requete) or die(mysql_error().$requete);
	while ($res=mysql_fetch_object($resultat)) {
		$retaff.=$res->content_infopage ;
	}
}

print $retaff;
?>