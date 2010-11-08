<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: publisher.inc.php,v 1.21 2009-12-05 14:10:19 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// second niveau de recherche OPAC sur editeur

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['editeurs'] = $count;
}

print "	<div id=\"resultatrech\"><h3>$msg[resultat_recherche]</h3>\n
		<div id=\"resultatrech_container\">
		<div id=\"resultatrech_see\">
";
// requête de recherche sur les editeurs
print pmb_bidi("<h3><span>$count $msg[publishers_found] <b>'".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."'");
if ($opac_search_other_function) {
	require_once($include_path."/".$opac_search_other_function);
	print pmb_bidi(" ".search_other_function_human_query($_SESSION["last_query"]));
}
print "</b>";
print activation_surlignage();
print "</h3></span>\n";

print "	</div>\n
		<div id=\"resultatrech_liste\">";
		
$found = mysql_query("select ed_id, ".$pert.",ed_name from publishers $clause group by ed_id $tri $limiter", $dbh);

print "<ul>";
while($mesEditeurs = mysql_fetch_object($found)) {
	print pmb_bidi("<li class='categ_colonne'><font class='notice_fort'><a href=index.php?lvl=publisher_see&id=".$mesEditeurs->ed_id.">".$mesEditeurs->ed_name."</a></font></li>\n");
	}
print "</ul>";
print " </div>\n
		</div>
		</div>";

