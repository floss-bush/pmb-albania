<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collection.inc.php,v 1.19 2009-12-05 14:10:19 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// second niveau de recherche OPAC sur collections

print pmb_bidi("	<div id=\"resultatrech\"><h3>$msg[resultat_recherche]</h3>\n
		<div id=\"resultatrech_container\">
		<div id=\"resultatrech_see\">
		<h3><span><b>$count</b> $msg[collections_found] <b>'".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."'");
if ($opac_search_other_function) {
	require_once($include_path."/".$opac_search_other_function);
	print pmb_bidi(" ".search_other_function_human_query($_SESSION["last_query"]));
}
print "</b>
	   ";
print activation_surlignage();
print "</h3></span>\n";

$found = mysql_query("select collection_id, ".$pert.",collection_name from collections $clause group by collection_id $tri $limiter", $dbh);

print "	</div>\n
		<div id=\"resultatrech_liste\">
	   	<ul>";
while($mesCollections = mysql_fetch_object($found)) {
	print pmb_bidi("<li class='categ_colonne'><font class='notice_fort'><a href=index.php?lvl=coll_see&id=".$mesCollections->collection_id.">".$mesCollections->collection_name."</a></font></li>\n");
	}
print "</ul>";
print " </div>\n
		</div>
		</div>";

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['collections'] = $count;
}