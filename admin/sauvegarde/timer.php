<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: timer.php,v 1.6 2009-05-16 11:11:53 dbellamy Exp $

$base_path="../..";
$base_auth="SAUV_AUTH|ADMINISTRATION_AUTH";
$base_title="\$msg[sauv_misc_timer_title]";
require($base_path."/includes/init.inc.php");

//Timer pour la sauvegarde
if ($delai=="")
{
	if (!is_array($sauvegardes)) {
		echo "<script>alert(\"".$msg["sauv_misc_timer_valid"]."\"); history.go(-1);</script>";
		exit();
	}
	//calcul du délai en minutes
	if ($sauv_timer==1) {
		$delai=0;
	}
	if ($sauv_timer==2) {
		$delai=$sauv_delay;
	}
	if ($sauv_timer==3) {
		$h=explode(":",date("H:i",time()));
		$h_now=$h[0]*60+$h[1];
		$h_start=$sauv_time_hour*60+$sauv_time_min;
		$delai=$h_start-$h_now;
		if ($delai<0) {
			echo "<script>alert(\"".$msg["sauv_misc_timer_time_out"]."\"); history.go(-1);</script>";
			exit();
		} else if ($delai==0) $delai=0;
	}
}
else
{
	$delai=$delai-1;
}

if ($delai==0) $url="run.php"; else $url="timer.php";
print "<div id=\"contenu-frame\">\n";
echo "<center><h1>".sprintf($msg["sauv_misc_timer_delay"],$delai)."</h1></center>\n";
echo "<form class='form-$current_module' method=\"post\" name=\"timer_form\" action=\"$url\">\n";
echo "<input type=\"hidden\" name=\"delai\" value=\"".$delai."\">\n";
echo "<br /><br /><center>
<table cellspacing=0><tr><td align=center style=\"border:solid;border-width:1px\"><b>".$msg["sauv_misc_timer_sets"]."</b></td><td align=center style=\"border:solid;border-width:1px\"><b>".$msg["sauv_misc_timer_groups"]."</b></td></tr>\n";
$sauv=implode(",",$sauvegardes);
$requete="select sauv_sauvegarde_nom,sauv_sauvegarde_tables from sauv_sauvegardes where sauv_sauvegarde_id in (".$sauv.") order by sauv_sauvegarde_nom";
$resultat=mysql_query($requete) or die(mysql_error());
$sauv_name=array();
$sauv_table=array();
while (list($nom,$tables)=mysql_fetch_row($resultat)) {
	$sauv_name[]=$nom;
	$sauv_tables[]=$tables;
}
for ($i=0; $i<count($sauv_name); $i++) {
	$requete="select sauv_table_nom from sauv_tables where sauv_table_id in (".$sauv_tables[$i].") order by sauv_table_nom";
	$resultat=mysql_query($requete) or die(mysql_error());
	$tTables=array();
	while (list($nom)=mysql_fetch_row($resultat)) {
		$tTables[]=$nom;
	}
	echo "<tr><td style=\"border:solid;border-width:1px\"><b>".$sauv_name[$i]."</b></td><td style=\"border:solid;border-width:1px\">".implode(", ",$tTables)."</td></tr>\n";
}
echo "</table></center>\n";
for ($i=0; $i<count($sauvegardes); $i++)
{
	echo "<input type=\"hidden\" name=\"sauvegardes[]\" value=\"".$sauvegardes[$i]."\">\n";
}
echo "<br /><br />";
echo "<center><input type=\"button\" value=\"".$msg["sauv_annuler"]."\" class=\"bouton\" onClick=\"document.location='launch.php'\"></center>";
if ($delai==0) $timeout=10; else $timeout=30000;
echo "<script>setTimeout(\"document.timer_form.submit()\",$timeout);</script>";
echo "</form>";
echo "<script>self.focus();</script>\n";
//echo $footer;
echo "</div>";
?>