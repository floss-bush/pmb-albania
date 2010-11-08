<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bannette.inc.php,v 1.9 2009-11-10 14:54:42 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage du contenu d'une bannette
require_once($base_path."/includes/bannette_func.inc.php");

// afin de résoudre un pb d'effacement de la variable $id_empr par empr_included, bug à trouver
if (!$id_empr) $id_empr=$_SESSION["id_empr_session"] ;
print "<script type='text/javascript' src='./includes/javascript/tablist.js'></script>" ;
print "<div id='aut_details'>\n";

print "<h3><span>".$msg['accueil_bannette']."</span></h3><br />";
if ($id_bannette)
	$aff = pmb_bidi(affiche_bannette ("$id_bannette", 0, $opac_bannette_notices_format, $opac_bannette_notices_depliables, "./empr.php?lvl=bannette&id_bannette=!!id_bannette!!", $liens_opac )) ; 
else 
	$aff = pmb_bidi(affiche_bannette ("", $opac_bannette_nb_liste, $opac_bannette_notices_format, $opac_bannette_notices_depliables, "./empr.php?lvl=bannette&id_bannette=!!id_bannette!!", $liens_opac )) ;

if($aff){
	if ($opac_bannette_notices_depliables) print $begin_result_liste ;
	print $aff;
} else {
	print $msg['empr_no_alerts'];
}
print "</div><!-- fermeture #aut_see -->\n";	
?>