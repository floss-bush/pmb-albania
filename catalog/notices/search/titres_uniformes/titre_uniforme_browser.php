<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: titre_uniforme_browser.php,v 1.2 2009-05-16 11:12:03 dbellamy Exp $

// page d'affichage du browser de collections

// définition du minimum nécéssaire
$base_path="../../../..";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "\$msg[6]";
require_once ("$base_path/includes/init.inc.php");

// javascript pour retrouver l'offset dans la liste des titres uniformes
$j_offset = "
<script type='text/javascript'>
<!--
function jump_anchor(anc) {
	// récupération de l'index de l'ancre
	for ( i = 0; i <= document.anchors.length; i++) {
		if(document.anchors[i].name == anc) {
			anc_index = i;
			break;
		}
	}
	if (document.all) {
		// code pour IE
		document.anchors[anc_index].scrollIntoView();
	} else {
		// mettre ici le code pour Mozilla et Netscape quand on aura trouvé
	}
}
// -->
jump_anchor('$ancre');
</script>
";

// url du présent browser
$browser_url = "./titre_uniforme_browser.php";

print "<div id='contenu-frame'>";

function select($ref, $id) {	
	return "window.parent.document.location='../../../../catalog.php?categ=search&mode=9&etat=aut_search&aut_type=$ref&aut_id=$id&no_rec_history=1'; return(false);";
}

if ($limite_affichage=="")$restriction = " limit 0,30 ";
else $restriction = "";

print "<a href='$browser_url?limite_affichage=ALL'>$msg[tout_afficher]</a><br />";
// affichage de la liste
$requete = "SELECT * FROM titres_uniformes ORDER BY tu_name $restriction ";

$result = mysql_query($requete, $dbh);

while(($tu=mysql_fetch_object($result))) {
	if($tu->tu_id)
		print "<a name='a".$tu->tu_id."'  href='#' onClick=\"".select('titre_uniforme', $tu->tu_id)."\">$tu->tu_name</a><br />\n";
}
if($ancre)
	print $j_offset;
mysql_close($dbh);

// affichage du footer
print "</div></body></html>";
