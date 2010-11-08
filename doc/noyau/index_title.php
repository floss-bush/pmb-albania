<?php
	// définition du minimum nécéssaire 
	include ("../../includes/error_report.inc.php") ;
	include ("../../includes/global_vars.inc.php") ;
	include ("../../includes/config.inc.php");

	$include_path      = "../../".$include_path; 
	$class_path        = "../../".$class_path;
	$javascript_path   = "../../".$javascript_path;
	$styles_path       = "../../".$styles_path;



include("db_doc.php"); ?>
<html>
<center><h3><?php echo $version; ?></h3></center>
<center>
<font size=2>
<a href="#" onClick="w=open('scheme.gif','scheme','resizable=yes,scrollbars=yes,width=700,height=500'); w.focus(); return false;">Voir le schéma graphique</a>
&nbsp;
<a href="#" onClick="w=open('report_without_phrases.php','report','resizable=yes,scrollbars=yes,width=300,height=300'); w.focus(); return false;">Rapport texte des tables</a>
&nbsp;<a href="#" onClick="w=open('report_with_phrases.php','report','resizable=yes,scrollbars=yes,width=300,height=300'); w.focus(); return false;">Rapport texte des tables avec les phrases de relations</a>
&nbsp;<a href="#" onClick="w=open('xls_without_phrases.php','report','resizable=yes,scrollbars=yes,width=300,height=300'); w.focus(); return false;">Rapport Excel des tables</a>
&nbsp;<a href="#" onClick="w=open('xls_with_phrases.php','report','resizable=yes,scrollbars=yes,width=300,height=300'); w.focus(); return false;">Rapport Excel des tables avec les phrases de relations</a>
</font>
</center>
</html>