<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: etagere.tpl.php,v 1.9 2007-03-14 16:58:01 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

$etageres_header = "<div id='etageres'><h3><span id='titre_etagere'>".$msg['accueil_etageres_virtuelles']."</span></h3><span>";

$etageres_footer = "</span></div>" ;			

