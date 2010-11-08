<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: publisher_browser.php,v 1.8 2009-05-16 11:12:02 dbellamy Exp $

// page d'affichage du browser de collections

// définition du minimum nécéssaire
$base_path="../../..";
$base_auth = "CIRCULATION_AUTH";
$base_title = "\$msg[6]";
require_once ("$base_path/includes/init.inc.php");

// javascript pour retrouver l'offset dans la liste éditeurs
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

$browser_url = "./publisher_browser.php?id_empr=$id_empr&groupID=$groupID";

// définition de variables
$open_folder = "<img src=\"../../../images/folderopen.gif\" border=\"0\" align=\"top\" hspace='3'>";
$closed_folder = "<img src=\"../../../images/folderclosed.gif\" border=\"0\" align=\"top\" hspace='3'>";
$up_folder = "<img src=\"../../../images/folderup.gif\" border=\"0\" align=\"top\" hspace='3'>";
$document = "<img src=\"../../../images/doc.gif\" border=\"0\" align=\"top\" hspace='3'>";

// affichage de l'entête
print "<div id='contenu-frame'>";

function select($ref, $id) {
	global $id_empr;
	global $groupID;
	// retourne le code javascript changeant l'adresse de la page pour affichage des notices
	// $ref -> type de donnée (editeur, collection)
	// $id -> id de l'objet recherché
	return "window.parent.document.location='../../../circ.php?categ=resa&mode=2&etat=aut_search&aut_type=$ref&aut_id=$id&id_empr=$id_empr&groupID=$groupID'; return(false);";
	}

if($coll_parent) {
	// affichage des enfants de la collection $coll_parent
	$requete = "SELECT * FROM publishers, collections, sub_collections";
	$requete .= " WHERE publishers.ed_id=collections.collection_parent";
	$requete .= " AND collections.collection_id=sub_collections.sub_coll_parent";
	$requete .= " AND sub_collections.sub_coll_parent=$coll_parent";
	$requete .= " ORDER BY sub_collections.sub_coll_name";
	$result = mysql_query($requete, $dbh);
	$item = mysql_fetch_object($result);
	print "<a href='$browser_url&ed_parent=".$item->ed_id."'>$up_folder</a>...<br />";
	print pmb_bidi($open_folder."<a href='#' onClick=\"".select('publisher', $item->ed_id)."\">".$item->ed_name."</a><br />");
	print pmb_bidi("<div style='margin-left:18px'>$open_folder");
	print pmb_bidi("<a href=\"\" onClick=\"".select('collection', $item->collection_id)."\">".$item->collection_name."</a></div>");
	print "<div style='margin-left:36px'>$document";
	print pmb_bidi("<a href=\"\" onClick=\"".select('subcoll', $item->sub_coll_id)."\">".$item->sub_coll_name."</a></div>");
	while($item=mysql_fetch_object($result)) {
		print "<div style='margin-left:36px'>$document";
		print pmb_bidi("<a href=\"\" onClick=\"".select('subcoll', $item->sub_coll_id)."\">".$item->sub_coll_name."</a></div>");
	}
} else {
	if($ed_parent) {
		// affichage des enfants de l'éditeur $ed_parent
		print "<a href='$browser_url&ancre=a$ed_parent'>".$up_folder.'</a>...<br />';

		// c'est Eric qui m'a dit ça. Merci Eric ;-) (de toute façon, j'ai jamais aimé les dimanches).
		$requete = "SELECT * FROM publishers, collections";
		$requete .= " LEFT JOIN sub_collections ON collections.collection_id=sub_collections.sub_coll_parent";
		$requete .= " WHERE publishers.ed_id=collections.collection_parent";
		$requete .= " AND publishers.ed_id=$ed_parent";
		$requete .= " ORDER BY collections.collection_name";

		$result = mysql_query($requete, $dbh);
		$item = mysql_fetch_object($result);
		print pmb_bidi($open_folder."<a href='#' onClick=\"".select('publisher', $item->ed_id)."\">".$item->ed_name.'</a><br />');
		if($item->sub_coll_id && $item->sub_coll_parent==$item->collection_id)
			$image = "<a href='$browser_url&coll_parent=".$item->collection_id."'>$closed_folder</a>";
		else
			$image = $document;
		print pmb_bidi("<div style='margin-left:18px'>".$image."<a href='#' onClick=\"".select('collection', $item->collection_id)."\">".$item->collection_name.'</a></div>');
		while($item=mysql_fetch_object($result)) {
			if($item->sub_coll_id && $item->sub_coll_parent==$item->collection_id)
				$image = "<a href='$browser_url&coll_parent=".$item->collection_id."'>$closed_folder</a>";
			else
				$image = $document;
			print pmb_bidi("<div style='margin-left:18px'>".$image."<a href='#' onClick=\"".select('collection', $item->collection_id)."\">".$item->collection_name.'</a></div>');
		}
	} else {
		if ($limite_affichage=="")
			$restriction = " limit 0,30 ";
			else $restriction = "";
		
		print "<a href='$browser_url&limite_affichage=ALL'>$msg[tout_afficher]</a><br />";
		
		// affichage de la liste des éditeurs (1er niveau)
		$requete = "SELECT * FROM publishers LEFT JOIN collections";
		$requete .= " ON publishers.ed_id=collections.collection_parent";
		$requete .= " GROUP BY ed_name ORDER BY ed_name $restriction ";

		$result = mysql_query($requete, $dbh);

		while($editeur=mysql_fetch_object($result)) {
			if($editeur->collection_id)
				$image = "<a name='a".$editeur->ed_id."' href='$browser_url&ed_parent=".$editeur->ed_id."'>$closed_folder</a>";
				else $image = $document;
			print pmb_bidi($image."<a name='a".$editeur->ed_id."' href='#' onClick=\"".select('publisher', $editeur->ed_id)."\">".$editeur->ed_name."</a><br />\n");
		}
		if($ancre)
			print pmb_bidi($j_offset);

	} // fin clause ed_parent
} // fin clause coll_parent

mysql_close($dbh);

// affichage du footer
print "</div></body></html>";
