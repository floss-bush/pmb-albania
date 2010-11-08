<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: others.inc.php,v 1.5 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// autres recherches (catalogage)
// Armelle : a priori plus utilisé
$other_search_form = str_replace("!!other_query!!",     $other_query,     $other_search_form);

if(strlen($other_query) || $obj) {
	include_once('./catalog/notices/search/others/other_proceed.inc.php');
	} else {
		print $other_search_form;
		}
