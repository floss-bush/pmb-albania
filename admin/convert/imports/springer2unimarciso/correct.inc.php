<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: correct.inc.php,v 1.1 2008-07-31 20:26:29 gueluneau Exp $


function correct($notice, $s, $islast, $isfirst, $param_path) {
	global $charset;
	if ($notice) {
		$notice=str_replace(" ".chr(0x1E).chr(0x1D),chr(0x1E).chr(0x1D),$notice);
		$end=strpos($notice,chr(0x1D));
		if ($end!==false) { 
			$length=substr($notice,0,5)*1;
			if ($length!=strlen($notice)) {
				$length=str_pad(strlen($notice),5,"0",STR_PAD_LEFT);
				$notice=$length.substr($notice,5);
			}
		} else {
			$error="Fin de fichier non conforme, ne pas tenir compte";
		}
	} else {	
		$error="Notice vide";
	}
	if (!$error) $r['VALID'] = true; else $r['VALID']=false;
	$r['ERROR'] = $error;
	$r['DATA'] = $notice;
	return $r;
}
?>