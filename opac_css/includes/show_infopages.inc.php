<?php 
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: show_infopages.inc.php,v 1.2 2010-06-09 08:44:21 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage des infopages demandées 

function show_infopages($infopagesid="") {
	if (!$infopagesid) return "";
	$retaff="";
	$t_infopagesid=explode(",",$infopagesid);
	$t_infopageslues=array();
	$requete="select id_infopage, content_infopage from infopages where id_infopage in($infopagesid) and valid_infopage=1";
	$resultat=mysql_query($requete) or die(mysql_error().$requete);
	while ($res=mysql_fetch_object($resultat)) {
		$t_infopageslues[$res->id_infopage]=$res->content_infopage;
	}
	for ($i=0; $i<count($t_infopagesid); $i++)  {
		if ($t_infopageslues[$t_infopagesid[$i]]) $retaff.= $t_infopageslues[$t_infopagesid[$i]]; 
	}
	return $retaff;
}

