<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: others.inc.php,v 1.5 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// autres recherches (catalogage)
// a priori plus utilisé
$RESA_other_search = str_replace("!!other_query!!",     $other_query,     $RESA_other_search);

if(strlen($other_query) || $obj) {
	include('./circ/resa/others/other_proceed.inc.php');
	} else {
		print $RESA_other_search;
		}
