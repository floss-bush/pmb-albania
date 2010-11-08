<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// mise à jour de l'entete de page
echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[explnum_doc_associe], $serial_header);

$retour = "./catalog.php?categ=serials&sub=view&serial_id=$f_notice";
$explnum = new explnum($f_explnum_id);
$explnum->mise_a_jour($f_notice, $f_bulletin, $f_nom, $f_url, $retour, $conservervignette, $f_statut_chk);	

?>