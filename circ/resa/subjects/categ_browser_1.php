<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categ_browser_1.php,v 1.4 2007-03-10 09:03:18 touraine37 Exp $

// affichage du browser de catégories

// définition du minimum nécéssaire
$base_path="../../..";
$base_auth = "CIRCULATION_AUTH";
$base_title = "\$msg[6]";
require_once ("$base_path/includes/init.inc.php");

print "<div id='contenu-frame'>";

include("$include_path/tree.inc.php");
print "
	<div id='contenu-frame'>";
tree("window.parent.document.location='../../../circ.php?categ=resa&mode=1&categ_id=!!id!!&id_empr=$id_empr&groupID=$groupID&unq=$unq'; return(false);");

// affichage du footer
print "</div></body></html>";
