<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions_export.inc.php,v 1.1 2009-07-31 14:37:09 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/suggestions_export.class.php");

//Génération des notices
if (count($chk)) {
	$sugg_export=new suggestions_export($chk);
	$origine=SESSid."_".str_replace(" ","",microtime());
	$filename=$origine.".xml";
	$fp=fopen($base_path."/temp/".$filename,"w+");
	fwrite($fp,"<?xml version='1.0' encoding='".$charset."' ?>\n<unimarc>\n");
	if ($fp) {
		while ($notice=$sugg_export->get_next_notice()) {
			fwrite($fp,$notice);
		}
		fwrite($fp,"</unimarc>\n");
		fclose($fp);
		print "<iframe name='frame_export_sugg' src='admin/convert/start_import.php?import_type=$export_list&file_in=".rawurlencode($filename)."&noimport=1&origine=' style='width:100%;height:400px'></iframe>";
	} else {
		error_form_message($msg["write_file_error"]);
	}
} else {
	print "<script>alert(\"".$msg["acquisition_sug_msg_nocheck_export"]."\"); history.go(-1);</script>";
}
?>