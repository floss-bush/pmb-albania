<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: indexint.inc.php,v 1.18 2009-12-05 14:10:19 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// second niveau de recherche OPAC sur indexation interne

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['indexint'] = $count;
}

print "	<div id=\"resultatrech\"><h3>$msg[resultat_recherche]</h3>\n
		<div id=\"resultatrech_container\">
		<div id=\"resultatrech_see\">
";
print pmb_bidi("<h3><span><b>$count</b> $msg[indexint_found] <b>'".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."'");
if ($opac_search_other_function) {
	require_once($include_path."/".$opac_search_other_function);
	print pmb_bidi(" ".search_other_function_human_query($_SESSION["last_query"]));
}
print "</b></font>";
print activation_surlignage();
print "</h3></span>\n<ul>";

$found = mysql_query("select *,".$pert." from indexint $clause group by indexint_id $tri $limiter", $dbh);

print "	</div>\n
		<div id=\"resultatrech_liste\">";
while($mesCategories = mysql_fetch_object($found)) {
	print '<li>';
	$categ_lien = $mesCategories->indexint_id ;
	print pmb_bidi("<a href=./index.php?lvl=indexint_see&id=".$categ_lien."><img src='./images/folder.gif' border='0'/> ".$mesCategories->indexint_name." ".$mesCategories->indexint_comment."</a>");
	print "</li>";
	}
print "</UL>";
print " </div>\n
		</div>
		</div>";
