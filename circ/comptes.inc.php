<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: comptes.inc.php,v 1.4 2007-10-28 11:24:59 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Affichage d'un compte lecteur

$empr=new emprunteur($id,'', FALSE, 1);

$empr->do_fiche_compte($typ_compte);

print pmb_bidi($empr->fiche_compte);
?>