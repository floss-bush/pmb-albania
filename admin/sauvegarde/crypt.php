<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: crypt.php,v 1.6 2009-05-16 11:11:53 dbellamy Exp $

//Cryptage d'un fichier
$base_path="../..";
$base_auth="SAUV_AUTH|ADMINISTRATION_AUTH";
$base_title="\$msg[sauv_misc_running]";
require($base_path."/includes/init.inc.php");

require_once("lib/api.inc.php");
require_once("$class_path/crypt.class.php");

//Entête
print "<div id=\"contenu-frame\">\n";
echo "<h1>".$msg["sauv_misc_export_running"]."</h1>\n";
echo "<form class='form-$current_module' name=\"sauv\" action=\"\" method=\"post\">\n";
echo "<br /><br />";
echo "<center><input type=\"button\" value=\"".$msg["sauv_annuler"]."\" onClick=\"document.location='launch.php';\" class=bouton></center>\n";

//Jeux à venir
for ($i=0; $i<count($sauvegardes); $i++) {
	echo "<input type=\"hidden\" name=\"sauvegardes[]\" value=\"".$sauvegardes[$i]."\">\n";
}

//Jeu courant
echo "<input type=\"hidden\" name=\"currentSauv\" value=\"".$currentSauv."\">\n";

//Recherche des paramètres de cryptage
$requete="select sauv_sauvegarde_key1, sauv_sauvegarde_key2 from sauv_sauvegardes where sauv_sauvegarde_id=".$currentSauv;
$resultat=mysql_query($requete);

$res=mysql_fetch_object($resultat);

//Création du log dans la base log

echo "<input type=\"hidden\" name=\"filename\" value=\"".$filename."\">\n";
echo "<input type=\"hidden\" name=\"logid\" value=\"".$logid."\">\n";
echo "<input type=\"hidden\" name=\"sauv_sauvegarde_nom\" value=\"".$sauv_sauvegarde_nom."\">\n";
echo "<input type=\"hidden\" name=\"temp_file\" value=\"$temp_file\">";
echo "<center><h2>".sprintf($msg["sauv_misc_crypting"],$sauv_sauvegarde_nom)."</h2></center>";

//Ajout du cryptage
$fe=@fopen($filename,"a");
if (!$fe) abort("The file $filename could not be opened",$logid);
fwrite($fe,"#Crypt : 1\r\n");

$ftemp=@fopen($temp_file,"r");
if (!$ftemp) abort("Temporary file for SQL export could not be opened for crypting",$logid);

if ($res->sauv_sauvegarde_key1=="") $cle1=$sauvegarde_cle_crypt1; else $cle1=$res->sauv_sauvegarde_key1;
if ($res->sauv_sauvegarde_key2=="") $cle2=$sauvegarde_cle_crypt2; else $cle2=$res->sauv_sauvegarde_key2;

$cr=new Crypt($cle1,$cle2);
$to_crypt=fread($ftemp,filesize($temp_file));
fclose($ftemp);

$ftemp=@fopen($temp_file,"w+");
if (!$ftemp) abort("Temporary file for SQL export could not be opened for crypting",$logid);

fwrite($ftemp,$cr->getCrypt("PMBCrypt"));
fwrite($ftemp,$cr->getCrypt($to_crypt));

write_log("Crypt OK : Crypting file is OK",$logid);

fclose($ftemp);

echo "</form></body></html>";

//Suite de la sauvegarde
echo "<script>document.sauv.action=\"end_save.php\"; document.sauv.submit();</script>";
echo "</div>";
?>