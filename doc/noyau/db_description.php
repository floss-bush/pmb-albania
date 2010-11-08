<?php 
	// définition du minimum nécéssaire 
	include ("../../includes/error_report.inc.php") ;
	include ("../../includes/global_vars.inc.php") ;
	include ("../../includes/config.inc.php");

	$include_path      = "../../".$include_path; 
	$class_path        = "../../".$class_path;
	$javascript_path   = "../../".$javascript_path;
	$styles_path       = "../../".$styles_path;

include("db_doc.php");
echo "<style>
td {
	font-size:12px;
}
</style>
	";
if ($table=="") 
	echo "<center>Sélectionnez une table</center>";
else
{
	echo "<table width=100% border=1>";
	echo "<tr><td colspan=8 align=center bgcolor=#CCCCCC><b>".$tables[$table][NAME]."</b></td></tr>\n";
	echo "<tr><td colspan=8 align=center bgcolor=#EEEEEE><i>".$tables[$table][DESCRIPTION]."</i></td></tr>\n";
	echo "<tr><td align=center>Nom champ</td><td align=center>Description</td><td align=center>Type</td><td colspan=3 align=center>Infos. complémentaires</td><td align=center>Réf. à d'autres tables</td><td align=center>Valeur par défaut</td></tr>\n";
	$colums=$tables[$table][COLUMS];
	for ($i=0; $i<count($colums); $i++)
	{
		echo "<tr>";
		echo "<td><b>".$colums[$i][NAME]."</b></td>";
		echo "<td><i>".$colums[$i][DESCRIPTION]."</i></td>";
		echo "<td>".$colums[$i][DATATYPE]."</td>";
		echo "<td>".$colums[$i][PRIMARY]."</td>";
		echo "<td>".$colums[$i][REQUIRED]."</td>";
		echo "<td>".$colums[$i][UNIQUE]."</td>";
		echo "<td>".$colums[$i][LINKED]."</td>";
		echo "<td>".$colums[$i][DEFAUT]."</td>";
		echo "</tr>\n";
	}
	echo "</table>";
	if ($table_old!="") echo "<center><i><a href=\"db_description.php?table=".$table_old."\" onClick=\"parent.tables.location='db_tables.php#".$table_old."'\">Retour à la table ".$tables[$table_old][NAME]."</a></i></center>";
}
?>