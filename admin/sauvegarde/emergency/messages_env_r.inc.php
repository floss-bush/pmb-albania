<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: messages_env_r.inc.php,v 1.6 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$filename=$_GET["filename"];
$critical=$_GET["critical"];

$msg["sauv_misc_restaure"]="Restauration de la sauvegarde %s";
$msg["sauv_misc_restaure_bad_sauv_file"]="Ce n'est pas un fichier de sauvegarde !";
$msg["sauv_misc_restaure_set_name"]="Nom du jeu";
$msg["sauv_misc_restaure_date_sauv"]="Date de sauvegarde";
$msg["sauv_misc_restaure_hour_sauv"]="Heure de sauvegarde";
$msg["sauv_misc_restaure_tables_sauv"]="Tables sauvegardées";
$msg["sauv_misc_restaure_compressed"]="La sauvegarde a été compressée";
$msg["sauv_misc_restaure_bz2"]="avec la librarie bz2 de PHP.";
$msg["sauv_misc_restaure_external"]="avec la commande externe";
$msg["sauv_misc_restaure_dec_command"]="La commande proposée pour la décompression est";
$msg["sauv_misc_restaure_dec_ext"]="L'extension par défaut des fichiers compressés est";
$msg["sauv_misc_restaure_crypted"]="La sauvegarde a été cryptée.";
$msg["sauv_misc_restaure_ph1"]="Phrase 1";
$msg["sauv_misc_restaure_ph2"]="Phrase 2";
$msg["sauv_misc_restaure_connect_infos"]="Informations de connexion au serveur";
$msg["sauv_misc_restaure_host_addr"]="Adresse du serveur";
$msg["sauv_misc_restaure_user"]="Utilisateur autorisé";
$msg["sauv_misc_restaure_passwd"]="Mot de passe";
$msg["sauv_misc_restaure_db"]="Base de données";
$msg["sauv_misc_restaure_launch"]="Lancer la restauration";
$msg["sauv_misc_restaure_confirm"]="Etes-vous sur de faire cette restauration ?";
$msg["sauv_misc_restaure_title"]="Restauration d'un jeu";
$msg["sauv_annuler"]="Annuler";
