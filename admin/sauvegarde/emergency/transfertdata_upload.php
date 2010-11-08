<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transfertdata_upload.php,v 1.4 2009-05-16 11:12:02 dbellamy Exp $

//Restauration d'urgence

exit();

$base_path="../../..";

include_once ("$base_path/includes/error_report.inc.php") ;
include_once ("$base_path/includes/global_vars.inc.php") ;
require_once ("$base_path/includes/config.inc.php");


$include_path      = $base_path."/".$include_path; 

require_once("$include_path/db_param.inc.php");

if ($_tableau_databases[1] && $base_title) {
	// multi-databases
	$database_window_title=$_libelle_databases[array_search(LOCATION,$_tableau_databases)].": ";
} else $database_window_title="" ; 

@$link=mysql_connect($_POST["host"], $_POST["db_user"], $_POST["db_password"]) or die("Impossible de se connecter au serveur MySql en tant qu'admin USER_NAME "); // Le @ ordonne a php de ne pas afficher de message d'erreur
@mysql_select_db($_POST["db"]) or die("Impossible de se connecter à la base de données $dbnamedbhost");


##### Faire saisir le nom de la bdd, les mots de passe pour securite... et se connecter ensuite. faire quand meme un include db_include pour savoir le charset destination.

move_uploaded_file($_FILES['archive_file']['tmp_name'], "../../backup/backups/".$_FILES['archive_file']['tmp_name']);

function restore($src) {
	global $link;
	global $buffer_sql;

	$SQL='';
	if($src) {
		$filename=$src;
		if(open_restore_stream($src) && $buffer_sql) {
			// open source file
			$SQL = preg_split('/;\s*\n|;\n/m', $buffer_sql);
			for($i=0; $i < sizeof($SQL); $i++) {
				if($SQL[$i]) $result = mysql_query($SQL[$i], $link);
			}
		} else {
			die("can't open file $src to restore");
			return FALSE;
		}
	}
	return TRUE;	
}


function open_restore_stream($src) {
	global $buffer_sql;
	global $charset;
	$in_file = $src;
	$fptr = @fopen($in_file, 'rb');
	if($fptr) {
		$buffer_sql = fread($fptr, filesize($in_file));
		if ($charset != 'iso-8859-1') {
			$buffer_sql = iconv("ISO-8859-1", strtoupper($charset), $buffer_sql);
			$buffer_sql = preg_replace('/iso-8859-1/i', $charset, $buffer_sql);
		}
		fclose($fptr);
		return TRUE;
	} else {
		$buffer_sql = '';
		return FALSE;
	}
}

if (restore("../../backup/backups/".$_FILES['archive_file']['tmp_name'])) 
	print "<br />Upload done";
else 
	print "<br />Should not happen";
