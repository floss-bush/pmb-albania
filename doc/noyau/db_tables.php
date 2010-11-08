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
echo "<table width=100% cellpadding=0 cellspacing=0>\n";
$pair=0;
for ($i=0; $i<count($tables); $i++)
{
	if ($pair==0) $color="#DDDDDD"; else $color="#FFFFFF";
	echo "<tr bgcolor=$color><td><a name=$i><a href=\"\" onClick=\"javascript:sty=document.getElementById('t$i').style.display; if (sty=='') { document.getElementById('t$i').style.display='none'; document.getElementById('i$i').src='plus.gif';} else { document.getElementById('t$i').style.display=''; document.getElementById('i$i').src='minus.gif';} return false;\"><img src=\"plus.gif\" border=0 id=\"i$i\"></a><b><a href=\"db_description.php?table=$i\" target=description>".$tables[$i][NAME]."</a></b></td></tr><tr bgcolor=$color><td><i>".$tables[$i]["DESCRIPTION"]."</i></td></tr>\n";	$r=$rel[$tables[$i]["NAME"]];
	
	echo "<tr  id='t$i' style='display:none'><td style='border-width:1px;border-style:solid;border-color:#000000'><blockquote>";
	for ($j=0; $j<count($r) ; $j++)
	{
		echo "<a href=\"#".$tables_inv[$r[$j]["LINKED"]]."\" onClick=\"parent.description.location='db_description.php?table=".$tables_inv[$r[$j]["LINKED"]]."&table_old=".$i."'\">".$r[$j]["LINKED"]."</a> : ".$r[$j]["PHRASE"]."<br />";
	}
	echo "</blockquote></td></tr>\n";	
	if ($pair==0) $pair=1; else $pair=0;
}
echo "</table>";
?>