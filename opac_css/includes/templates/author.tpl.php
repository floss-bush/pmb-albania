<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: author.tpl.php,v 1.13 2009-01-07 08:58:08 ngantier Exp $

// ce fichier contient des templates indiquant comment doit s'afficher un auteur

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

if ( ! defined( 'AUTHOR_TMPL' ) ) {
  define( 'AUTHOR_TMPL', 1 );

//	----------------------------------
//	$author_display : écran d'info pour un auteur
// Liste des variables statiques prises en charges :
// !!id!!        identifiant de l'auteur
// !!name!!      nom de l'auteur
// !!rejete!!    forme rejetée de l'auteur
// !!date1!!     date de naissance
// !!date2!!     date du décès

// Liste des variables dynamiques prises en charges. Les affichages dynamiques sont cliquables le plus souvent
// !!allname!!   Nom complet et lisible
// !!allnamenc!! Nom complet et lisible non clikable (pour affichage seulement)
// !!dates!!     date de naissance et de décès

// level 1 : affichage réduit
$author_level1_display = "
<div class=authorlevel1>
!!allname!!
</div>
";

$author_level1_no_dates_info = "";


// level 2 : affichage général
//
$author_level2_display = "
<div class=authorlevel2>
<h3>$msg[author_tpl_author] !!allnamenc!! !!dates!! !!site_web!!</h3>
<div class=aut_comment>!!aut_comment!!</div>
</div>
";
$author_level2_display_congres = "
<div class=authorlevel2>
<h3>".$msg["congres_libelle"].": !!allnamenc!! !!site_web!!</h3>
<div class=aut_comment>!!aut_comment!!</div>
</div>
";
/*
$author_display_similar_congres = "
<div id='categories-container'>
!!congres_contens!!
</div>
";
$author_display_similar_congres_ligne = "
<div class='row_categ'>
!!congres_ligne!!
</div><div class='div_clr'></div>
";
$author_display_similar_congres_element = "
<div class='category'>
	<h2><img src='./images/folder.gif'><a href=\"./index.php?lvl=author_see&id=!!congres_id!!\">!!congres_label!!</a></h2>
	<ul>
		<li>!!congres_detail!!</li>
	</ul>
</div>
";
*/
$author_display_similar_congres = "
<table style='margin-left: 48px;' border='0' cellpadding='3'>
<tbody>
!!congres_contens!!
</tbody></table>
";
$author_display_similar_congres_ligne = "
<tr>
!!congres_ligne!!
</tr>
";
$author_display_similar_congres_element = "
<td align='top'>
	!!img_folder!!<a href=\"./index.php?lvl=author_see&id=!!congres_id!!\">!!congres_label!! !!congres_detail!!</a> 
	
</td>
";
$author_level2_no_dates_info = "";
} # fin de définition
