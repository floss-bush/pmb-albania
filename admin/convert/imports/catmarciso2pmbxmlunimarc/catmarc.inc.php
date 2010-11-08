<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: catmarc.inc.php,v 1.1 2008-05-21 14:23:18 gueluneau Exp $


function cut_header($notice, $s, $islast, $isfirst, $param_path) {
	global $ISO_decode_do_not_decode;
	$ISO_decode_do_not_decode=true;
	if ($notice) {
		if (substr($notice,0,2)=="\r\n")
				$data=substr($notice,2);
		else $data=$notice;
	} else {	
			$error="Registre buit";
	}
	if ((!$data)&&(!$error)) $error="Dernière notice : ne pas tenir compte !";
	if (!$error) $r['VALID'] = true; else $r['VALID']=false;
	$r['ERROR'] = $error;
	$r['DATA'] = $data;
	return $r;
}
?>