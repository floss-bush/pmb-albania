<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: types_produits.inc.php,v 1.11 2009-05-16 10:52:44 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=types_produits&caller=$caller&param1=$param1&param2=$param2&param3=$param3&param4=$param4&id_fou=$id_fou&no_display=$no_display&bt_ajouter=$bt_ajouter";

// contenu popup sélection fournisseur
require_once('./selectors/templates/sel_types_produits.tpl.php');
require_once($class_path.'/types_produits.class.php');
require_once($class_path.'/tva_achats.class.php');
require_once($class_path.'/entites.class.php');
require_once($class_path.'/offres_remises.class.php');


// affichage du header
print $sel_header;

print $jscript;
show_results($dbh, $nbr_lignes, $page);


// affichage des membres de la page
function show_results($dbh, $nbr_lignes=0, $page=0) {
	
	global $nb_per_page;
	global $base_url;
	global $caller;
 	global $charset;
	global $msg;
	global $id_fou;

	// on récupére le nombre de lignes qui vont bien
	$nbr_lignes = types_produits::countTypes(); 
	if (!$page) $page=1;
	$debut = ($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on lance la vraie requête
		$q = types_produits::listTypes($debut, $nb_per_page);
		$res = mysql_query($q, $dbh);

		while($row=mysql_fetch_object($res)) {
				
			$typ = $row->id_produit;
			$lib_typ = $row->libelle;
			
			$taux_tva = new tva_achats($row->num_tva_achat);
			$lib_tva = htmlentities($taux_tva->taux_tva, ENT_QUOTES, $charset);										
			
			$offre = new offres_remises($id_fou, $row->id_produit);
			if ($offre->remise) {
				$lib_rem = htmlentities($offre->remise, ENT_QUOTES, $charset);
			} else $lib_rem = '0';
			 
			print pmb_bidi("
			<a href='#' onclick=\"set_parent('$caller', '$row->id_produit', '".htmlentities(addslashes($lib_typ), ENT_QUOTES, $charset)."', '$lib_rem', '$lib_tva'  )\">$lib_typ</a>");
			print "<br />";

		}
		mysql_free_result($res);

		// constitution des liens

		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;

		// affichage du lien précédent si nécessaire
		print '<hr /><div align=center>';
		if($precedente > 0)
		print "<a href='$base_url&page=$precedente&nbr_lignes=$nbr_lignes&no_display=$no_display'><img src='./images/left.gif' border='0' title='$msg[48]' alt='[$msg[48]]' hspace='3' align='middle' /></a>";
		for($i = 1; $i <= $nbepages; $i++) {
			if($i==$page)
				print "<b>$i/$nbepages</b>";
		}

	if($suivante<=$nbepages)
		print "<a href='$base_url&page=$suivante&nbr_lignes=$nbr_lignes&no_display=$no_display'><img src='./images/right.gif' border='0' title='$msg[49]' alt='[$msg[49]]' hspace='3' align='middle' /></a>";

	}
	print '</div>';
}

print $sel_footer;