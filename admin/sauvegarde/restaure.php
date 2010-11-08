<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: restaure.php,v 1.7 2009-05-16 11:11:53 dbellamy Exp $

@error_reporting (E_ERROR | E_PARSE | E_WARNING);
@set_time_limit(1200);

//Restauration d'un jeu

//Récupération du nom de fichier

//Est-ce une restauration critique (dans ce cas, pas de vérification d'utilisateur)?

if ($_GET["critical"]) {
    include("emergency/messages_env_r.inc.php");
} else {
	$base_path="../..";
    $base_auth="SAUV_AUTH|ADMINISTRATION_AUTH";
    $base_title="\$msg[sauv_misc_restaure_title]";
    require($base_path."/includes/init.inc.php");
}

//$filename=$_GET["filename"];
$tFilename=explode("/",$filename);
$file_name=$tFilename[count($tFilename)-1];
//$critical=$_GET["critical"];

require_once("lib/api.inc.php");

print "<div id=\"contenu-frame\">\n";
echo "<center><h1>".sprintf($msg["sauv_misc_restaure"],$file_name)."</h1></center>\n";

echo "<form class='form-$current_module' name=\"infos\" action=\"restaure_act.php\" method=\"post\">\n";

echo "<input type=\"hidden\" name=\"critical\" value=\"$critical\" />";
echo "<input type=\"hidden\" name=\"filename\" value=\"$filename\" />";

$infos=read_infos($filename);
if (count($infos)==0) { echo "<h2>".$msg["sauv_misc_restaure_bad_sauv_file"]."</h2>"; exit(); }
echo "<table align=center>\n";
echo "<tr><td style=\"border-width:1px;border-style:solid\"><b>".$msg["sauv_misc_restaure_set_name"]."</b></td><td style=\"border-width:1px;border-style:solid\">".$infos["Name"]."</td></tr>\n";
echo "<tr><td style=\"border-width:1px;border-style:solid\"><b>".$msg["sauv_misc_restaure_date_sauv"]."</b></td><td style=\"border-width:1px;border-style:solid\">".$infos["Date"]."</td></tr>\n";
echo "<tr><td style=\"border-width:1px;border-style:solid\"><b>".$msg["sauv_misc_restaure_hour_sauv"]."</b></td><td style=\"border-width:1px;border-style:solid\">".$infos["Start time"]."</td></tr>\n";
echo "<tr><td style=\"border-width:1px;border-style:solid\"><b>".$msg["sauv_misc_restaure_tables_sauv"]."</b></td><td style=\"border-width:1px;border-style:solid\">";
$tTables=explode(",",$infos["Tables"]);
echo "<table width=100%>\n";
$n=0;
for ($i=0; $i<count($tTables); $i++) {
	if ($n==0) echo "\n<tr>";
	echo "<td style=\"border-width:1px;border-style:solid\"><input type=\"checkbox\" value=\"".$tTables[$i]."\" name=\"tables[]\" checked />&nbsp;".$tTables[$i]."</td>";
	$n++;
	if ($n==4) { $n=0; echo "</tr>"; }
}
if ($n<4) {
	for ($i=$n; $i<4; $i++) {
		echo "<td style=\"border-width:1px;border-style:solid\">&nbsp;</td>";
	}
	echo "</tr>\n";
}
echo "</table>";
echo "</td></tr>\n";

echo "</table>\n";
echo "<br /><br />";

if ($infos["Compress"]==1) {
	echo "<input type=\"hidden\" name=\"compress\" value=\"1\" />";
	echo "<center><b>".$msg["sauv_misc_restaure_compressed"]." ";
	$tCompressCommand=explode(":",$infos["Compress commands"]);
	echo "<input type=\"hidden\" name=\"decompress_type\" value=\"".$tCompressCommand[0]."\" />";
	if ($tCompressCommand[0]=="internal") {
		echo $msg["sauv_misc_restaure_bz2"]."</b></center>\n<br />\n";
	} else {
		echo $msg["sauv_misc_restaure_external"]." ".$tCompressCommand[1].".</b></center>\n<br />\n";
		echo "<table>";
		echo "<tr><td>".$msg["sauv_misc_restaure_dec_command"]."</td><td><input name=\"decompress\" type=\"text\" value=\"".$tCompressCommand[2]."\"></td></tr>\n";
		echo "<tr><td>".$msg["sauv_misc_restaure_dec_ext"]."</td><td><input name=\"decompress_ext\" type=\"text\" value=\"".$tCompressCommand[3]."\"></td></tr>\n";
		echo "</table>";
	}
}
echo "<br />";
if ($infos["Crypt"]==1) {
	echo "<input type=\"hidden\" name=\"crypt\" value=\"1\" />";
	echo "<center><b>".$msg["sauv_misc_restaure_crypted"]."</b></center>\n<br />\n";
	echo "<table>";
	echo "<tr><td>".$msg["sauv_misc_restaure_ph1"]."</td><td><input type=\"password\" value=\"\" name=\"phrase1\"></td></tr>\n";
	echo "<tr><td>".$msg["sauv_misc_restaure_ph2"]."</td><td><input type=\"password\" value=\"\" name=\"phrase2\"></td></tr>\n";
	echo "</table>\n";
}
echo "<br />";
if ($critical==1) {
	echo "<center><b>".$msg["sauv_misc_restaure_connect_infos"]."</b></center>\n<br />\n";
	echo "<table>";
	echo "<tr><td>".$msg["sauv_misc_restaure_host_addr"]."</td><td><input name=\"host\" type=\"text\"></td></tr>";
	echo "<tr><td>".$msg["sauv_misc_restaure_user"]."</td><td><input name=\"db_user\" type=\"text\"></td></tr>";
	echo "<tr><td>".$msg["sauv_misc_restaure_passwd"]."</td><td><input name=\"db_password\" type=\"password\"></td></tr>";
	echo "<tr><td>".$msg["sauv_misc_restaure_db"]."</td><td><input name=\"db\" type=\"text\"></td></tr>";
	echo "</table>\n";
}

echo "<center><input type=\"submit\" value=\"".$msg["sauv_misc_restaure_launch"]."\" onClick=\"return confirm('".$msg["sauv_misc_restaure_confirm"]."');\" class=\"bouton\">&nbsp<input type=\"button\" value=\"".$msg["sauv_annuler"]."\" class=\"bouton\" onClick=\"self.close()\"></center>";
echo "</form>";
echo "</div>";
?>