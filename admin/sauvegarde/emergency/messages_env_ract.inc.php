<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: messages_env_ract.inc.php,v 1.5 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$filename=$_POST["filename"];
$compress=$_POST["compress"];
$crypt=$_POST["crypt"];
$tables=$_POST["tables"];
$decompress=$_POST["decompress"];
$decompress_ext=$_POST["decompress_ext"];
$decompress_type=$_POST["decompress_type"];
$phrase1=$_POST["phrase1"];
$phrase2=$_POST["phrase2"];
$db=$_POST["db"];
$host=$_POST["host"];
$db_user=$_POST["db_user"];
$db_password=$_POST["db_password"];
$critical=$_POST["critical"];

$msg["sauv_misc_ract_title"]="Restauration d'un jeu";
$msg["sauv_misc_ract_cant_connect"]="La connexion au serveur de base de données n'a pu être établie";
$msg["sauv_misc_ract_db_dont_exists"]="La base %s n'existe pas";
$msg["sauv_misc_ract_cant_open_file"]="Le fichier n'a pu être ouvert !";
$msg["sauv_misc_ract_no_sauv"]="Le fichier n'est pas un fichier de sauvegarde !";
$msg["sauv_misc_ract_decryt_msg"]="Décryptage du fichier...";
$msg["sauv_misc_ract_bad_keys"]="Vous n'avez pas fourni les bonnes clefs pour décrypter le fichier !";
$msg["sauv_misc_ract_create"]="Le fichier SQL n'a pu être créé, vérifiez les droits du répertoire admin/backup/backups/";
$msg["sauv_misc_ract_decompress"]="Décompression du fichier...";
$msg["sauv_misc_ract_not_bz2"]="Le fichier de données n'a pas été compressé avec bz2";
$msg["sauv_misc_ract_restaure_tables"]="Restauration des tables";
$msg["sauv_misc_ract_open_failed"]="Le fichier SQL n'a pu être ouvert";
$msg["sauv_misc_ract_restaured_t"]="Table %s restaurée.";
$msg["sauv_misc_ract_start_restaure"]="Début de restauration de la table %s...";
$msg["sauv_misc_ract_ignore"]="Ignore la table %s ...";
$msg["sauv_misc_ract_invalid_request"]="Requête invalide : %s";
$msg["sauv_misc_ract_correct"]="La restauration s'est passée correctement";
