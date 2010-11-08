<?php
header("Content-Type: application/download\n");
header("Content-Disposition: attachement; filename=\"tables_with_phrases.xls\"");

	// définition du minimum nécéssaire 
	include ("../../includes/error_report.inc.php") ;
	include ("../../includes/global_vars.inc.php") ;
	include ("../../includes/config.inc.php");

	$include_path      = "../../".$include_path; 
	$class_path        = "../../".$class_path;
	$javascript_path   = "../../".$javascript_path;
	$styles_path       = "../../".$styles_path;
include("db_doc.php");

$ln="\r\n";
echo "<html>".$ln;
for ($i=0; $i<count($tables); $i++)
{
	echo "<table border=1>".$ln;
	$t=$tables[$i];
	echo "<tr bgcolor=#AAAAAA><td><b>Table</td><td><b>".$t[NAME]."</td></tr>".$ln;
	echo "<tr><td><b>Description</td><td><i>".$t[DESCRIPTION]."</td></tr>".$ln;
	echo "<tr></tr>".$ln;
	
	echo "<tr><td colspan=2 bgcolor=#DDDDDD><b>Phrases</td></tr>".$ln;
	$r=$rel[$t[NAME]];
	for ($j=0; $j<count($r); $j++)
	{
		echo "<tr><td>".$r[$j][LINKED]."</td><td>".$r[$j][PHRASE]."</td></tr>".$ln;
	}
	echo "<tr></tr>";
	echo "<tr><td colspan=2 bgcolor=#DDDDDD><b>Colonnes</td></tr>".$ln;
	echo "</table>".$ln;
	$col=$t[COLUMS];
	echo "<table border=1>".$ln;
	echo "<tr bgcolor=#DDDDDD><td align=center>Nom</td><td align=center>Description</td><td align=center>Type de données</td><td align=center colspan=3>Compléments</td><td align=center>Ref. à d'autres tables</td><td align=center>Valeur par défaut</td></tr>".$ln;
	for ($j=0; $j<count($col); $j++)
	{
		echo "<tr><td><b>".$col[$j][NAME]."</td><td><i>".$col[$j][DESCRIPTION]."</td><td>".$col[$j][DATATYPE]."</td><td>".$col[$j][PRIMARY]."</td><td>".$col[$j][REQUIRED]."</td><td>".$col[$j][UNIQUE]."</td><td>".strip_tags($col[$j][LINKED])."</td><td>".$col[$j][DEFAUT]."</td></tr>".$ln;
	}
	echo "</table>".$ln;
	echo "<br /><br />".$ln;
}
echo "</html>";