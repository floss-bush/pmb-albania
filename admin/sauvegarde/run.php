<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: run.php,v 1.9 2009-05-16 11:11:53 dbellamy Exp $

//Sauvegarde des jeux : initialisation de la sauvegarde d'un jeu, création du fichier SQL et compression éventuelle, log
$base_path="../..";
$base_auth="SAUV_AUTH|ADMINISTRATION_AUTH";
$base_title="\$msg[sauv_misc_running]";
require($base_path."/includes/init.inc.php");

require_once("lib/api.inc.php");

//Récupération de l'id utilisateur
$requete="select userid from users where username='".SESSlogin."'";
$resultat=mysql_query($requete) or die(mysql_error());
$userid=mysql_result($resultat,0,0);

//Si tableau des sauvegardes est vide alors fin
if (!is_array($sauvegardes)) {
	echo "<script>document.location=\"end.php\";</script>";
	exit();
}

//Entête
print "<div id=\"contenu-frame\">\n";
echo "<h1>".$msg["sauv_misc_export_running"]."</h1>\n";
echo "<form class='form-$current_module' name=\"sauv\" action=\"\" method=\"post\">\n";
echo "<br /><br />";
echo "<center><input type=\"button\" value=\"".$msg["sauv_annuler"]."\" onClick=\"document.location='launch.php';\" class=bouton></center>\n";

//Pour le premier jeux de sauvegarde de la liste restante
$currentSauv=$sauvegardes[0];

//Affichage des jeux restants
for ($i=1; $i<count($sauvegardes); $i++) {
	echo "<input type=\"hidden\" name=\"sauvegardes[]\" value=\"".$sauvegardes[$i]."\">\n";
}

//Jeu en cours
echo "<input type=\"hidden\" name=\"currentSauv\" value=\"".$currentSauv."\">\n";

//Recherche des paramètres de la sauvegarde
$requete="select sauv_sauvegarde_nom, sauv_sauvegarde_file_prefix, sauv_sauvegarde_tables, sauv_sauvegarde_compress,sauv_sauvegarde_compress_command, sauv_sauvegarde_crypt from sauv_sauvegardes where sauv_sauvegarde_id=".$currentSauv;
$resultat=mysql_query($requete);
$res=mysql_fetch_object($resultat);

//Création du log dans la base de log
$log_messages="Start time : ".date("H:i",time())."\r\n";
$log_file=$res->sauv_sauvegarde_file_prefix."_".date("Y_m_d",time());

//Recherche si nom de fichier déjà existant
$n_version=0;
if (!defined('SAUV_PREFIX')) define( 'SAUV_PREFIX', "" );
	else define( 'SAUV_PREFIX', SAUV_PREFIX."_" );
$log_file_test=SAUV_PREFIX.$log_file.".sav";
while (file_exists("../../admin/backup/backups/".$log_file_test)) {
	$n_version++;
	$log_file_test=$log_file."_".$n_version.".sav";
}
$log_file=$log_file_test;

$requete="insert into sauv_log (sauv_log_start_date,sauv_log_file,sauv_log_messages,sauv_log_userid) values(now(),'$log_file','$log_messages',$userid)";
mysql_query($requete) or die(mysql_error());
$logid=mysql_insert_id();

//Eleéments nécessaires pour la suite
echo "<input type=\"hidden\" name=\"filename\" value=\"../../admin/backup/backups/".$log_file."\">\n";
echo "<input type=\"hidden\" name=\"logid\" value=\"".$logid."\">\n";
echo "<input type=\"hidden\" name=\"sauv_sauvegarde_nom\" value=\"".$res->sauv_sauvegarde_nom."\">\n";
echo "<center><h2>".sprintf($msg["sauv_misc_export_SQL"],$res->sauv_sauvegarde_nom)."</h2></center>";

//Création du fichier d'export
$fe=@fopen("../../admin/backup/backups/".$log_file,"w+");
if (!$fe) abort("The file $log_file could not be created",$logid);
fwrite($fe,"#Name : ".$res->sauv_sauvegarde_nom."\r\n");
fwrite($fe,"#".$log_messages);
fwrite($fe,"#Date : ".date("Y-m-d",time())."\r\n");

//Récupération des tables
$requete="select sauv_table_tables from sauv_tables where sauv_table_id in (".$res->sauv_sauvegarde_tables.")";
$resultat=mysql_query($requete) or abort("Tables could not be retrived",$logid);
$tables=array();
while (list($sauv_table_tables)=mysql_fetch_row($resultat)) {
	$tSauv_table_tables=explode(",",$sauv_table_tables);
	for ($i=0; $i<count($tSauv_table_tables); $i++) {
		$as=array_search($tSauv_table_tables[$i],$tables);
		if (($as!==null)&&($as!==false)) {
			//
		} else {
			$tables[]=$tSauv_table_tables[$i];
		}
	}
}

//Export SQL

$temp_file="temp_".(SAUV_PREFIX!=""?SAUV_PREFIX."_":"").$res->sauv_sauvegarde_file_prefix."_".date("d_m_Y",time()).".sql";
$ftemp=@fopen("../../admin/backup/backups/".$temp_file,"w+");
if (!$ftemp) abort("Temporary file for SQL export could not be created",$logid);

//Log de l'entête
fwrite($fe,"#Groups : ".$res->sauv_sauvegarde_tables."\r\n");
fwrite($fe,"#Tables : ".implode(",",$tables)."\r\n");

//Ecriture du fichier SQL
for ($i=0; $i<count($tables); $i++) {
	table_dump($tables[$i],$ftemp);
}

write_log("SQL OK : SQL export is OK",$logid);

fclose($ftemp);

//Compression éventuelle
fwrite($fe,"#Compress : ".$res->sauv_sauvegarde_compress."\r\n");
if ($res->sauv_sauvegarde_compress==1) {
	fwrite($fe,"#Compress commands : ".$res->sauv_sauvegarde_compress_command."\r\n");
	$command=explode(":",$res->sauv_sauvegarde_compress_command);
	
	switch ($command[0]) {
		case 'external' :
			$c_command=str_replace("%s","../../admin/backup/backups/".$temp_file,$command[1]);
			exec($c_command);
			@unlink("../../admin/backup/backups/".$temp_file);
			$temp_file="../../admin/backup/backups/".$temp_file.".".$command[3];
			if (!file_exists($temp_file)) abort("Compression failed",$logid);
		break;
		case 'internal' :
			$fz=bzopen("../../admin/backup/backups/".$temp_file.".bz2","w+");
			if (!$fz) abort("Compression failed",$logid);
			$ftemp=fopen("../../admin/backup/backups/".$temp_file,"r");
			if (!$ftemp) abort("Compression failed",$logid);
			$to_crypt=fread($ftemp,filesize("../../admin/backup/backups/".$temp_file));
			bzwrite($fz,$to_crypt);
			bzclose($fz);
			fclose($ftemp);
			unlink("../../admin/backup/backups/".$temp_file);
			$temp_file="../../admin/backup/backups/".$temp_file.".bz2";
		break;
	}
	write_log("Compress OK : Compress is OK",$logid);
} else {
	$temp_file="../../admin/backup/backups/".$temp_file;
}

//Fichier temporaire contenant le SQL compressé ou non
echo "<input type=\"hidden\" name=\"temp_file\" value=\"$temp_file\">";

//cryptage ?
if ($res->sauv_sauvegarde_crypt==1) {
	$action="crypt.php";
} else {
	$action="end_save.php";
}
echo "</form></body></html>";

//Etape suivante
echo "<script>document.sauv.action=\"$action\"; document.sauv.submit();</script>";
print "</div>";
?>