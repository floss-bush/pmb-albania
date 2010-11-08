<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: thesaurus.inc.php,v 1.5 2009-12-18 11:18:25 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusions diverses
include("$include_path/templates/thesaurus.tpl.php");
require_once("$class_path/thesaurus.class.php");

//affichage de la liste des thesaurus
$base_url = "./autorites.php?categ=categories&sub=";
$action = $base_url."thes_form&id_thes=0";

$q = "select id_thesaurus, libelle_thesaurus, num_noeud_racine from thesaurus ";
$r = mysql_query($q, $dbh);
if (mysql_num_rows($r) == 0) {
	$browser_content = $msg[4051];
	affiche();
	exit;
}

$odd_even = 1;
while ($row = mysql_fetch_object($r)) {
	if ($odd_even==0) {
		$browser_content .= "	<tr class='odd'>";
		$odd_even=1;
	} else {
		$browser_content .= "	<tr class='even'>";
		$odd_even=0;
	}
	$browser_content .= "<td>";
	$browser_content .= "<a href='".$base_url."thes_form&id_thes=".$row->id_thesaurus."' >".$row->libelle_thesaurus."</a>";
	$browser_content .= "</td></tr>";
}		

affiche();
exit;


// création du tableau à partir du template et affichage
function affiche() {
	
	global $thes_browser;
	global $browser_content;
	global $action;
	global $browser_header;

	$thes_browser = str_replace('!!browser_header!!', $browser_header, $thes_browser);
	$thes_browser = str_replace('!!browser_content!!', $browser_content, $thes_browser);
	$thes_browser = str_replace('!!action!!', $action, $thes_browser);	
	print pmb_bidi($thes_browser);
}


?>