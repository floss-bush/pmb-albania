<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: publisher.tpl.php,v 1.11 2009-05-16 10:52:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// ce fichier contient des templates indiquant comment doit s'afficher un éditeur

if ( ! defined( 'EDITEUR_TMPL' ) ) {
  define( 'EDITEUR_TMPL', 1 );

//	----------------------------------
//	$publisher_display : écran d'info pour un éditeur
// Liste des variables statiques prises en charges :
// !!id!!        identifiant de l'éditeur
// !!name!!      nom de l'éditeur
// !!adr1!!      champ 1 de l'adresse de l'éditeur
// !!adr2!!      champ 2 de l'adresse de l'éditeur
// !!cp!!        code postal de l'adresse de l'éditeur
// !!ville!!     ville de l'adresse de l'éditeur
// !!pays!!      pays de l'adresse de l'éditeur
// !!web!!       site web de l'éditeur
// !!isbd!!      affichage isbd de l'éditeur

// Liste des variables dynamiques prises en charges. Les affichages dynamiques sont cliquables le plus souvent
// !!link!!      lien vers site web de l'éditeur
// !!colls!!     collections de l'éditeur
// !!address!!   adresse complète


// level 2 : affichage général
$publisher_level2_display = "
<div class=publisherlevel2>
<h3>".sprintf($msg["publisher_details_publisher"],"!!name!!")." !!site_web!!</h3>
<div class=aut_comment>!!aut_comment!!</div>
<p>
".sprintf($msg["publisher_details_location"],"!!ville!!")."<br />
</p>
<p>
!!address!!
</p>
!!colls!!
</div>
";

} # fin de définition
