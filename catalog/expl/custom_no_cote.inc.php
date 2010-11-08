<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: custom_no_cote.inc.php,v 1.4 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function prefill_cote ($id_notice=0,$cote="") {
	global $value_prefix_cote ;
	if (!$cote) return $value_prefix_cote ;
		else return $cote;
	}
