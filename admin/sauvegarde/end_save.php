<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: end_save.php,v 1.10 2009-05-16 11:11:53 dbellamy Exp $

//Création du fichier final et transfert vers les lieux puis passage au jeu suivant
$base_path="../..";
$base_auth="SAUV_AUTH|ADMINISTRATION_AUTH";
$base_title="\$msg[sauv_misc_running]";
require($base_path."/includes/init.inc.php");

require_once("lib/api.inc.php");

//Entête
print "<div id=\"contenu-frame\">\n";
echo "<h1>".$msg["sauv_misc_export_running"]."</h1>\n";
echo "<form class='form-$current_module' name=\"sauv\" action=\"\" method=\"post\">\n";
echo "<br /><br />";
echo "<center><input type=\"button\" value=\"".$msg["sauv_annuler"]."\" onClick=\"document.location='launch.php';\" class=bouton></center>\n";

//Jeux à suivre
for ($i=0; $i<count($sauvegardes); $i++) {
	echo "<input type=\"hidden\" name=\"sauvegardes[]\" value=\"".$sauvegardes[$i]."\">\n";
}

//Sauvegarde courante
echo "<input type=\"hidden\" name=\"currentSauv\" value=\"".$currentSauv."\">\n";

//Fusion des deux fichiers en un seul

//print "<h1>FILENAME=$filename  TEMP_FILE=$temp_file</h1>";

$fe=@fopen($filename,"a");
$fsql=@fopen($temp_file,"rb");

if ((!$fe)||(!$fsql)) abort("Could not create final file",$logid);


//$to_happend=fread($fsql,filesize($temp_file));
//fwrite($fe,"#data-section\r\n".$to_happend);

// MaxMan: modified because this error:
//Fatal error: Allowed memory size of 8388608 bytes exhausted 
//(tried to allocate 6495315 bytes) in 
///var/www/pmb/admin/sauvegarde/end_save.php on line 52

fwrite($fe,"#data-section\r\n");
do {
   $to_append = fread($fsql, 8192);
   if (strlen($to_append) == 0) {
       break;
   }
   fwrite($fe,$to_append);
} while (true);


fclose($fsql);
fclose($fe);
unlink($temp_file);

//Log : Backup complet
write_log("Backup complete",$logid);

//Succeed
$requete="update sauv_log set sauv_log_succeed=1 where sauv_log_id=".$logid;
@mysql_query($requete);

//Paramètres
echo "<input type=\"hidden\" name=\"logid\" value=\"".$logid."\">\n";
echo "<center><h2>".sprintf($msg["sauv_misc_merging"],$sauv_sauvegarde_nom)."</h2></center>";
echo "<input type=\"hidden\" name=\"filename\" value=\"$filename\">";

//Récupération des lieux
$requete="select sauv_sauvegarde_lieux from sauv_sauvegardes where sauv_sauvegarde_id=".$currentSauv;
$resultat=@mysql_query($requete);
$lieux=mysql_result($resultat,0,0);

$tLieux=explode(",",$lieux);
echo "<script>";
//Pour chaque lieu, ouvrir une fenêtre de transfert
for ($i=0; $i<count($tLieux); $i++) {
	echo "openPopUp(\"copy_lieux.php?filename=$filename&logid=$logid&sauv_lieu_id=".$tLieux[$i]."\",\"copy_lieux_$i\", 400, 200, -2, -2, \"menubar=no,resizable=1,scrollbars=yes\");\n";
}
echo "</script>";
echo "</form></body></html>";

//Passer au jeu suivant
echo "<script>document.sauv.action=\"run.php\"; document.sauv.submit();</script>";
print "</div>";
?>