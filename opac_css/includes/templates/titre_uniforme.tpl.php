<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: titre_uniforme.tpl.php,v 1.1 2008-12-19 14:57:04 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// ce fichier contient des templates indiquant comment doit s'afficher un titre uniforme

if ( ! defined( 'TITRE_UNIFORME_TMPL' ) ) {
  define( 'TITRE_UNIFORME_TMPL', 1 );


// level 2 : affichage général
$titre_uniforme_level2_display = "
<div class=publisherlevel2>
<h3>".sprintf($msg["titre_uniforme_detail"],"!!name!!")."</h3>
<div class=aut_comment>!!aut_comment!!</div>
<p>
	!!distribution!!
</p>
<p>
	!!reference!!
</p>
<p>
	!!tonalite!!
</p>
<p>
	!!subdivision!!
</p>
</div>
";

} # fin de définition
