<?php 
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rss.inc.php,v 1.6 2009-08-25 22:51:12 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ($include_path."/rss_func.inc.php") ;

$req_rss = "select notice_id, eformat from notices where eformat like 'RSS%' order by tit1 " ;
$res_rss = mysql_query($req_rss);
while ($rss = mysql_fetch_object($res_rss)) {
	$rss_lu = explode(' ', $rss->eformat) ;
	if ($rss_lu[2]) $sites[]=$rss->notice_id ;
}

// $sites=array("http://www.lemonde.fr/rss/sequence/0,2-3208,1-0,0.xml","http://www.liberation.fr/rss.php","http://www.lefigaro.fr/rss/figaro_une.xml","http://www.aful.org/nouvelles/rss","http://rss.zdnet.fr/feeds/rss/actualites/","http://rss.zdnet.fr/feeds/rss/actualites/informatique/");

if (count($sites)>0) {
	print pmb_bidi("<div id='rss'><h3><span id='titre_rss'>".htmlentities($msg[rss_titre],ENT_QUOTES, $charset)."</span></h3><span>");
	$red=false;
	$articles="";
	for ($i=0; $i<count($sites); $i++) {
		$articles .= affiche_rss($sites[$i]) ;
	}
	print pmb_bidi($articles);
	print "</span></div>" ;			
}
