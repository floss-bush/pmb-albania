<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: show_late.inc.php,v 1.1 2010-04-16 05:31:24 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


//Affichage d'un compte lecteur

$empr=new emprunteur($id,'', FALSE, 1);

$empr->do_fiche_retard();

print $empr->fiche_retard;