<?php
header("Content-Type: application/download\n");
header("Content-Disposition: attachement; filename=\"tables_with_phrases.txt\"");

	// définition du minimum nécéssaire 
	include ("../../includes/error_report.inc.php") ;
	include ("../../includes/global_vars.inc.php") ;
	include ("../../includes/config.inc.php");

	$include_path      = "../../".$include_path; 
	$class_path        = "../../".$class_path;
	$javascript_path   = "../../".$javascript_path;
	$styles_path       = "../../".$styles_path;

include("db_doc.php");

function unhtmlentities ($string) {
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	return strtr ($string, $trans_tbl);
}


$ln="\r\n";
$sep="\t";
for ($i=0; $i<count($tables); $i++)
{
	$t=$tables[$i];
	echo "Table".$sep.$t[NAME].$ln;
	echo "Description".$sep.$t[DESCRIPTION].$ln.$ln;
	echo "Phrases".$ln;
	$r=$rel[$t[NAME]];
	for ($j=0; $j<count($r); $j++)
	{
		echo $r[$j][LINKED].$sep.$r[$j][PHRASE].$ln;
	}
	echo $ln;
	echo "Colonnes".$ln;
	echo "Nom".$sep."Description".$sep."Type de données".$sep."Compléments".$sep.$sep.$sep."Ref. à d'autres tables".$sep."Valeur par féfaut".$ln;
	$col=$t[COLUMS];
	for ($j=0; $j<count($col); $j++)
	{
		echo unhtmlentities($col[$j][NAME]).$sep.unhtmlentities($col[$j][DESCRIPTION]).$sep.unhtmlentities($col[$j][DATATYPE]).$sep.unhtmlentities($col[$j][PRIMARY]).$sep.unhtmlentities($col[$j][REQUIRED]).$sep.unhtmlentities($col[$j][UNIQUE]).$sep.unhtmlentities(strip_tags($col[$j][LINKED])).$sep.unhtmlentities($col[$j][DEFAUT]).$ln;
	}
	echo $ln.$ln;
}