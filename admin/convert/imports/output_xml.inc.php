<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: output_xml.inc.php,v 1.9 2009-07-30 10:17:46 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function _get_header_($output_params) {
global $charset;

if (isset($output_params["CHARSET"]))
	$charset = $output_params["CHARSET"];

$r="<?xml version=\"1.0\" encoding=\"$charset\"?>\n";
$r.="<".$output_params['ROOTELEMENT'][0][value].">\n";
$r.=$output_params['ADDHEADER'][0][value];
return $r;
}

function _get_footer_($output_params) {
	$r=$output_params['ADDFOOTER'][0][value];
	return $r."</".$output_params['ROOTELEMENT'][0][value].">";
}

