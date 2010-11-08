<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice.tpl.php,v 1.15 2007-03-14 16:58:00 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// ce fichier contient des templates indiquant comment doit s'afficher une notice

if ( ! defined( 'NOTICE_TMPL' ) ) {
  define( 'NOTICE_TMPL', 1 );

//	----------------------------------
//	$notice_resume : écran de liste des exemplaires pour une notice
// Liste des variables statiques prises en charges :
// !!notice_id!! numéro de notice
// !!notice_id_only!!
// !!typdoc!!    type du document
// !!tit1!!      titre propre
// !!tit2!!      titre propre 2
// !!tit3!!      titre parallèle
// !!tit4!!      complément du titre
// !!tparent!!   libellé du titre parent (nom de la série)
// !!tnvol!!     numéro de partie (numéro de volume dans une série par exemple)
// !!aut1!!      auteur principal
// !!f1!!        fonction de l'auteur principal
// !!aut2!!      coauteur
// !!f2!!        fonction du coauteur
// !!aut3!!      auteur secondaire 1
// !!f3!!        fonction auteur secondaire 1
// !!aut4!!      auteur secondaire 2
// !!f4!!        fonction auteur secondaire 2
// !!ed1!!       éditeur principal
// !!ed2!!       autre éditeur
// !!coll!!      collection
// !!subcoll!!   sous-collection
// !!year!!      année de publication
// !!nocoll!!    numéro dans la collection
// !!code!!      isbn, code barre commercial ou n° commercial
// !!npages!!    nombre de pages
// !!ill!!       mention d'illustration
// !!size!!      format
// !!prix!!      prix
// !!accomp!!    matériel d'accompagnement
// !!n_gen!!     note générale
// !!n_contenu!! note de contenu
// !!n_resume!!  résumé/extrait
// !!index_l!!   indexation libre / mots-clés
// !!lang!!      langue du document
// !!org_lang!!  langue originale du document
// !!lien!!      URL de la ressource électronique associée
// !!eformat!!   format de la ressource électronique associée

// Liste des variables dynamiques prises en charges. Les affichages sont cliquables le plus souvent
// !!auteur!!    nom de l'auteur principal. S'il n'existe pas, affiche le premier auteur trouvé et sa fonction.
// !!auteurs!!   noms des auteurs séparés par des virgules. La fonction des auteurs secondaire est précisée
//               entre parenthèses
// !!editeur!!   nom de l'éditeur principal
// !!collection!! nom de la collection et de la sous collection si elle existe
// !!level1!!    affichage de niveau 1 (affichage réduit)
//
// !!prix!! prix du document (défini dans la notice et non dans les exemplaires)
//
// A ajouter à ces variables, les variables de localisation qui permettent de fixer des termes d'intro et de fin
// ex : !!notice_id_start!! ou !!notice_id_end!!

// level 1 : affichage sur une ligne titre, auteur principal, disponibilité
$notice_level1_display = "
!!level1!!
";

$notice_level1_no_coll_info = "";
$notice_level1_no_author_info = "";
$notice_level1_no_authors_info = "";
$notice_level1_no_publisher_info = "";



// level 2 : affichage réduit mais général
$notice_level2_display = "
<h3>!!tit1_ico!! <a href=index.php?lvl=notice_display&id=!!notice_id_only!!>!!tit1!!</a></h3>
<ul>
!!auteur!!
!!editeur!!
!!collection!!
!!typdocdisplay!!
</ul>
";

$oldnotice_level2_display="!!tit1_ico!! <a href=index.php?lvl=notice_display&id=!!notice_id_only!!>!!tit1!!</a>
<ul>
!!auteur!!
!!editeur!!
!!collection!!
!!typdocdisplay!!
</ul>
";

$notice_level2_no_coll_info = $msg["notice_display_coll_empty"];
$notice_level2_no_author_info = $msg["notice_display_author_empty"];
$notice_level2_no_authors_info = $msg["notice_display_authors_empty"];
$notice_level2_no_publisher_info = $msg["notice_display_publisher_empty"];

// level 3 : affichage standard dit public
$notice_level3_display = "
<h3>!!tit1_ico!! !!tit1!!</h3>

!!image_petit!!
!!tit1display!!
!!tit2!! 
!!tit3!!
!!tit4!! 
!!tparent!!
!!auteur!! 
!!typdocdisplay!!
!!editeur!!
!!collection!!
!!year!!
!!code!!
!!npages!!   
!!size!!
!!ill!!
!!prix!!
!!accomp!!
!!n_gen!!
!!n_contenu!!
!!n_resume!!
!!lien!! 
!!eformat!!";


// level 4 : affichage isbd
$notice_level4_display = "
NON UTILISE POUR L INSTANT voir 'list notices.inc.php' EFFACE par ER le 04/09/2006
";

// level 5 : affichage intermarc
$notice_level5_display = "
<table border='0' width='$largeur'>
			<tr>
				<td class='listheader' colspan='2'>
				<h3>".$msg["notice_display_intermarc"]."</h3>
				</td>
			</tr>
			<tr>
				<td>
				</td></tr></table>
				";


} # fin de définition
