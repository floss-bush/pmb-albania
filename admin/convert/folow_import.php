<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: folow_import.php,v 1.10 2007-02-23 13:49:11 gueluneau Exp $

//Transmission ensuite du fichier converti
$base_path = "../..";
$base_auth = "ADMINISTRATION_AUTH|CATALOGAGE_AUTH";
$base_title = "\$msg[ie_import_running]";
$base_noheader=1;
$base_nobody=1;
$base_nosession=0;
require ($base_path."/includes/init.inc.php");

//Supression du fichier transmis
@unlink("$base_path/temp/$file_in");

//Fichier converti
$f = explode(".", $file_in);
if (count($f) > 1) {
	unset($f[count($f) - 1]);
}
$file_out = implode(".", $f).".".$suffix."~";

//Téléchargement
if (!file_exists("$base_path/temp/$file_out")) {
		print $std_header;
		print "<body>";
		error_message_history($msg['admin_convert_erreur_destination'],$msg['admin_convert_fichier_existe'],0);
		exit;
}

if ($deliver==3) {
	if (!$mimetype) {
		header("Content-Type: application/download");
	} else {
		header("Content-Type: ".$mimetype);
	}
	header("Content-Length: ".filesize("$base_path/temp/$file_out"));
	header("Content-Disposition: attachment; filename=".implode(".", $f).".$suffix");
	@readfile("$base_path/temp/$file_out");
	@unlink("$base_path/temp/$file_out");
} else {
	//@copy("$base_path/temp/$file_out","$base_path/admin/import/unimarc.fic");
	@copy("$base_path/temp/$file_out","$base_path/admin/import/unimarc".(defined("LOCATION")?"_".constant("LOCATION"):"").".fic");

	@unlink("$base_path/temp/$file_out");
	if ($deliver==1) $sub="import"; else $sub="import_expl";
	echo "<script>document.location=\"$base_path/admin/import/iimport_expl.php?categ=import&sub=$sub&action=preload\";</script>";
}
?>