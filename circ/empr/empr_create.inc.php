<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_create.inc.php,v 1.11 2008-12-17 08:33:01 kantin Exp $

// récupération code barre en vue saisie d'un emprunteur (modifié F CEROVETTI 12/2007 pour marcher aussi avec ALPHANUMERIQUE )
// corrigé et augmenté par Eric ROBERT

// modifier ds administration, outils, paramètres, généraux :  "num_carte_auto" en 0 ,1 , 10 , 12, 13 ou autre selon le fonctionnement désiré:

// Explication 
// Numéro de carte de lecteur automatique et nombre de caratères du préfixe
// Numéro de carte de lecteur automatique ? 
//  0: Non (si utilisation de cartes pré-imprimées)
//  1: Oui, entièrement numérique
//  2,a,b,c: Oui avec préfixe: a=longueur du préfixe, b=nombre de chiffres de la partie numérique, c=préfixe fixé (facultatif)
  

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// récupération code barre en vue saisie d'un emprunteur
echo window_title($database_window_title.$msg[42].$msg[1003].$msg[1001]);

$pmb_num_carte_auto_array=array();
$pmb_num_carte_auto_array=explode(",",$pmb_num_carte_auto);

if ($pmb_num_carte_auto_array[0] == "1" ) {
	$rqt = "select max(empr_cb+1) as max_cb from empr ";
	$res = mysql_query($rqt, $dbh);
	$cb_initial = mysql_fetch_object($res);
	$cb_a_creer = (string)$cb_initial->max_cb;
} elseif ($pmb_num_carte_auto_array[0] == "2" ) {

	$long_prefixe = $pmb_num_carte_auto_array[1];
	$nb_chiffres = $pmb_num_carte_auto_array[2];
	$prefix = $pmb_num_carte_auto_array[3];
	
    $rqt =  "SELECT CAST(SUBSTRING(empr_cb,".($long_prefixe+1).") AS UNSIGNED) AS max_cb, SUBSTRING(empr_cb,1,".($long_prefixe*1).") AS prefixdb FROM empr ORDER BY max_cb DESC limit 0,1" ; // modif f cerovetti pour sortir dernier code barre tri par ASCII
	$res = mysql_query($rqt, $dbh);
	$cb_initial = mysql_fetch_object($res);
	$cb_a_creer = ($cb_initial->max_cb*1)+1;
	if (!$nb_chiffres) $nb_chiffres=strlen($cb_a_creer);
	if (!$prefix) $prefix = $cb_initial->prefixdb;
	
	$cb_a_creer = $prefix.substr((string)str_pad($cb_a_creer, $nb_chiffres, "0", STR_PAD_LEFT),-$nb_chiffres);
	}

get_cb($msg[42], "", $msg[43], './circ.php?categ=empr_saisie', 1, (string)$cb_a_creer, 1);
